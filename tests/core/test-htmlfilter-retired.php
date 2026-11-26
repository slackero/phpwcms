<?php
// tests/core/test-htmlfilter-retired.php — run: php8 tests/core/test-htmlfilter-retired.php
// include/inc_ext/htmlfilter.php was a 2005 copy of the unmaintained Duke
// kses port. It is retired in favour of HTMLPurifier, exposed through
// phpwcms_purify_html() in include/inc_lib/general.inc.php.

$root = dirname(__DIR__, 2);
$general = file_get_contents($root . '/include/inc_lib/general.inc.php');
$front = file_get_contents($root . '/include/inc_front/front.func.inc.php');

$failures = 0;
require_once __DIR__ . '/lib/source-fixture.php';

// the helper keeps its purifier cache below the installation root
define('PHPWCMS_ROOT', temp_root('purify'));
@mkdir(PHPWCMS_ROOT . '/content/tmp', 0777, true);

require_once $root . '/include/vendor/autoload.php';

extract_fn($general, 'phpwcms_purify_html');

check(phpwcms_purify_html('') === '', 'empty input returns an empty string');
check(
    !str_contains(phpwcms_purify_html('<img src="x" onload="alert(1)">'), 'onload'),
    'event handler is removed'
);
check(
    !str_contains(phpwcms_purify_html('<script>alert(1)</script>'), '<script'),
    'script element is removed'
);
check(
    !str_contains(phpwcms_purify_html('<a href="javascript:alert(1)">x</a>'), 'javascript:'),
    'javascript URL is removed'
);
check(
    !str_contains(phpwcms_purify_html('<link rel="stylesheet" href="x.css">'), '<link'),
    'link element is removed'
);
check(
    !str_contains(phpwcms_purify_html('<meta http-equiv="refresh" content="0;url=x">'), '<meta'),
    'meta element is removed'
);
check(
    str_contains(phpwcms_purify_html('<p><strong>bold</strong></p>'), '<strong>'),
    'safe formatting is kept'
);
check(
    str_contains(phpwcms_purify_html('<p><strong>bold</strong></p>'), 'bold'),
    'text content is kept'
);
check(
    str_contains(phpwcms_purify_html('<img src="https://example.com/a.png" alt="a">'), 'https://example.com/a.png'),
    'safe image source is kept'
);
check(
    str_contains(phpwcms_purify_html('<div id="section-2" class="note">x</div>'), 'id="section-2"'),
    'in-page anchor target is kept'
);
check(
    !str_contains(phpwcms_purify_html('<div id="location">x</div>'), 'id="location"'),
    'clobbering ID is removed'
);
check(
    !str_contains(phpwcms_purify_html('<div id="Location">x</div>'), 'id='),
    'clobbering ID is matched case-insensitively'
);
check(
    !str_contains(phpwcms_purify_html('<div id="origin">x</div>'), 'id='),
    'clobbering ID outside the original list is removed'
);
check(
    str_contains(phpwcms_purify_html('<a href="/x" target="_blank">x</a>'), 'target="_blank"'),
    'new-tab link target is kept'
);
check(
    !str_contains(phpwcms_purify_html('<a href="/x" target="_blank">x</a>'), 'onclick'),
    'link sanitizing keeps no handlers'
);

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
