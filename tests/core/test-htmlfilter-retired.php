<?php
// tests/core/test-htmlfilter-retired.php — run: php7.4 tests/core/test-htmlfilter-retired.php
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

check(!is_file($root . '/include/inc_ext/htmlfilter.php'), 'htmlfilter.php is removed');
check(strpos($general, 'inc_ext/htmlfilter.php') === false, 'general.inc.php no longer requires htmlfilter.php');
check(
    strpos($general, 'htmlfilter_sanitize(') === false,
    'getCleanSubString sanitizes through the purifier helper'
);
check(
    strpos($front, 'htmlfilter_sanitize(') === false,
    'include_url sanitizes through the purifier helper'
);
check(strpos($general, 'function phpwcms_purify_html(') !== false, 'the purifier helper is defined');
check(strpos($general, 'phpwcms_purify_html($cutString)') !== false, 'getCleanSubString calls the helper');
check(strpos($front, 'phpwcms_purify_html(trim($k)') !== false, 'include_url calls the helper');

check(phpwcms_purify_html('') === '', 'empty input returns an empty string');
check(
    strpos(phpwcms_purify_html('<img src="x" onload="alert(1)">'), 'onload') === false,
    'event handler is removed'
);
check(
    strpos(phpwcms_purify_html('<script>alert(1)</script>'), '<script') === false,
    'script element is removed'
);
check(
    strpos(phpwcms_purify_html('<a href="javascript:alert(1)">x</a>'), 'javascript:') === false,
    'javascript URL is removed'
);
check(
    strpos(phpwcms_purify_html('<link rel="stylesheet" href="x.css">'), '<link') === false,
    'link element is removed'
);
check(
    strpos(phpwcms_purify_html('<meta http-equiv="refresh" content="0;url=x">'), '<meta') === false,
    'meta element is removed'
);
check(
    strpos(phpwcms_purify_html('<p><strong>bold</strong></p>'), '<strong>') !== false,
    'safe formatting is kept'
);
check(
    strpos(phpwcms_purify_html('<p><strong>bold</strong></p>'), 'bold') !== false,
    'text content is kept'
);
check(
    strpos(phpwcms_purify_html('<img src="https://example.com/a.png" alt="a">'), 'https://example.com/a.png') !== false,
    'safe image source is kept'
);
check(
    strpos(phpwcms_purify_html('<div id="section-2" class="note">x</div>'), 'id="section-2"') !== false,
    'in-page anchor target is kept'
);
check(
    strpos(phpwcms_purify_html('<div id="location">x</div>'), 'id="location"') === false,
    'clobbering ID is removed'
);
check(
    strpos(phpwcms_purify_html('<div id="Location">x</div>'), 'id=') === false,
    'clobbering ID is matched case-insensitively'
);
check(
    strpos(phpwcms_purify_html('<div id="origin">x</div>'), 'id=') === false,
    'clobbering ID outside the original list is removed'
);
check(
    strpos(phpwcms_purify_html('<a href="/x" target="_blank">x</a>'), 'target="_blank"') !== false,
    'new-tab link target is kept'
);

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
