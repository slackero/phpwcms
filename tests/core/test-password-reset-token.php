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

function check($ok, $message)
{
    global $failures;
    if ($ok) {
        echo "OK   $message\n";
        return;
    }
    echo "FAIL $message\n";
    $failures++;
}

check(strpos($general, 'function phpwcms_password_reset_token_create(') !== false, 'token creation helper exists');
check(strpos($general, 'bin2hex(random_bytes(32))') !== false, 'reset token comes from a CSPRNG');
check(strpos($general, "hash('sha256', \$token)") !== false, 'only the token hash is stored');
check(strpos($general, 'function phpwcms_password_reset_token_consume(') !== false, 'token consumption helper exists');
check(strpos($general, 'function phpwcms_password_reset_rate_limited(') !== false, 'rate limit helper exists');

check(strpos($content, "\$result[0]['PASSWORD'] = generic_string(8)") === false, 'reminder no longer generates a password');
check(strpos($content, "'usr_pass'=>md5(\$result[0]['PASSWORD'])") === false, 'reminder no longer updates the backend password');
check(strpos($content, "'detail_password'=>md5(\$result[0]['PASSWORD'])") === false, 'reminder no longer updates the frontend password');
check(strpos($content, 'phpwcms_password_reset_token_create(') !== false, 'reminder issues a reset token');
check(strpos($content, '{RESET_URL}') !== false, 'reminder mail carries the reset link');
check(strpos($content, 'phpwcms_password_reset_token_consume(') !== false, 'reset submission consumes the token before updating');
check(strpos($content, 'feResetToken') !== false, 'reset form submits the token');
check(strpos($content, "'REMINDER_RESET'") !== false, 'reset section is read from the template');
check(strpos($content, 'feResetPassword2') !== false, 'reset form requires the password twice');

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
check(strpos($reset_url, '&token=') !== false && strpos($reset_url, '&amp;token=') === false, 'reset URL keeps &token= in the plain text mail');
check(strpos($content, 'phpwcms_password_reset_url(') !== false, 'reminder builds the link through the URL helper');

check(strpos($template, '{RESET_URL}') !== false, 'shipped template uses the reset link');
check(strpos($template, 'reset your passwort: {PASSWORD}') === false, 'shipped template no longer mails a password');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
