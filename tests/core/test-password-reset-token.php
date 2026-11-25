<?php
// tests/core/test-password-reset-token.php — run: php tests/core/test-password-reset-token.php
// Content Part 28 reset the stored password during an anonymous reminder and
// mailed the new password. The reminder now issues a single-use, expiring
// token; the password changes only after that token is submitted.

$root = dirname(__DIR__, 2);
$general = file_get_contents($root . '/include/inc_lib/general.inc.php');
$content = file_get_contents($root . '/include/inc_front/content/cnt28.article.inc.php');
$template = file_get_contents($root . '/template/inc_cntpart/felogin/example/fe_login.tmpl');

$failures = 0;

function check(bool $ok, string $message): void
{
    global $failures;
    if ($ok) {
        echo "OK   $message\n";
        return;
    }
    echo "FAIL $message\n";
    $failures++;
}

check(str_contains($general, 'function phpwcms_password_reset_token_create('), 'token creation helper exists');
check(str_contains($general, "bin2hex(random_bytes(32))"), 'reset token comes from a CSPRNG');
check(str_contains($general, "hash('sha256', \$token)"), 'only the token hash is stored');
check(str_contains($general, 'function phpwcms_password_reset_token_consume('), 'token consumption helper exists');
check(str_contains($general, 'function phpwcms_password_reset_rate_limited('), 'rate limit helper exists');

check(!str_contains($content, "\$result[0]['PASSWORD'] = generic_string(8)"), 'reminder no longer generates a password');
check(!str_contains($content, "'usr_pass'=>md5(\$result[0]['PASSWORD'])"), 'reminder no longer updates the backend password');
check(!str_contains($content, "'detail_password'=>md5(\$result[0]['PASSWORD'])"), 'reminder no longer updates the frontend password');
check(str_contains($content, 'phpwcms_password_reset_token_create('), 'reminder issues a reset token');
check(str_contains($content, '{RESET_URL}'), 'reminder mail carries the reset link');
check(str_contains($content, 'phpwcms_password_reset_token_consume('), 'reset submission consumes the token before updating');
check(str_contains($content, 'feResetToken'), 'reset form submits the token');
check(str_contains($content, "'REMINDER_RESET'"), 'reset section is read from the template');
check(str_contains($content, 'feResetPassword2'), 'reset form requires the password twice');

// The reset link is mailed as plain text; rel_url() must keep the raw `&`.
$rel_url_args = null;
function rel_url($add, $remove, $id_alias = '', $format = 'htmlspecialchars')
{
    global $rel_url_args;
    $rel_url_args = array($add, $remove, $id_alias, $format);

    return 'index.php?profile_reminder=1&token=' . $add['token'];
}
if (!preg_match('/^function phpwcms_password_reset_url\b.*?^}/ms', $general, $match)) {
    echo "FAIL cannot extract phpwcms_password_reset_url\n";
    exit(1);
}
eval($match[0]);
$reset_url = phpwcms_password_reset_url(str_repeat('a', 64), 'https://example.test/');
check(is_array($rel_url_args) && $rel_url_args[3] === '', 'reset URL asks rel_url() for the raw separator format');
check(str_contains($reset_url, '&token=') && !str_contains($reset_url, '&amp;token='), 'reset URL keeps &token= in the plain text mail');
check(str_contains($content, 'phpwcms_password_reset_url('), 'reminder builds the link through the URL helper');

check(str_contains($template, '{RESET_URL}'), 'shipped template uses the reset link');
check(!str_contains($template, 'reset your passwort: {PASSWORD}'), 'shipped template no longer mails a password');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
