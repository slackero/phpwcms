<?php
// tests/core/test-feedimport-html.php — run: php7.4 tests/core/test-feedimport-html.php
// The feed importer stored `content:encoded`/`description` verbatim as article
// summary and Content Part 14 HTML. An item carrying `<img onload=...>`
// therefore executed script in the site origin. Imported markup is now
// allowlisted through phpwcms_purify_html() before it is stored, and items the
// allowlist rejects are reduced to plain text.

$root = dirname(__DIR__, 2);
$general = file_get_contents($root . '/include/inc_lib/general.inc.php');
$importer_source = file_get_contents($root . '/include/inc_module/mod_feedimport/frontend.init.php');

$failures = 0;
require_once __DIR__ . '/lib/source-fixture.php';

// the helper keeps its purifier cache below the installation root
define('PHPWCMS_ROOT', temp_root('feed-purify'));
@mkdir(PHPWCMS_ROOT . '/content/tmp', 0777, true);

require_once $root . '/include/vendor/autoload.php';

// stand-in for the phpwcms helper the importer fallback uses
function plaintext_htmlencode(string $text, string $encode_function = 'html_specialchars', bool $render_bbcode = true): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

extract_fn($general, 'phpwcms_purify_html');
extract_fn($importer_source, 'feedimport_html_to_plain_text');

$payloads = array(
    '<img src="x" onload="alert(1)">' => 'onload',
    '<img src="x" onerror=alert(1)>' => 'onerror',
    '<svg/onload=alert(1)>' => 'onload',
    '<p onclick="alert(1)">text</p>' => 'onclick',
    '<script>alert(1)</script>' => '<script',
    '<a href="javascript:alert(1)">x</a>' => 'javascript:',
    '<iframe src="https://example.com"></iframe>' => '<iframe',
);

foreach ($payloads as $payload => $needle) {
    $clean = phpwcms_purify_html($payload, 'feed-content-');
    check(strpos($clean, $needle) === false, 'purifier removes ' . $needle);
}

check(
    strpos(phpwcms_purify_html('<p><strong>bold</strong> text</p>', 'feed-content-'), '<strong>') !== false,
    'safe formatting survives purification'
);
check(
    strpos(phpwcms_purify_html('<p><strong>bold</strong> text</p>', 'feed-content-'), 'text') !== false,
    'safe text survives purification'
);
check(
    strpos(phpwcms_purify_html('<a href="https://example.com/">link</a>', 'feed-content-'), 'https://example.com/') !== false,
    'safe absolute link survives purification'
);
check(
    phpwcms_purify_html('<script>alert(1)</script>', 'feed-content-') === '',
    'a markup-only item purifies to an empty string'
);
check(
    strpos(phpwcms_purify_html('<div id="item">x</div>', 'feed-content-'), 'id="feed-content-item"') !== false,
    'imported IDs are prefixed'
);
$anchored = phpwcms_purify_html('<h2 id="top">T</h2><a href="#top">go</a>', 'feed-content-');
check(strpos($anchored, 'id="feed-content-top"') !== false, 'anchor target is prefixed');
check(strpos($anchored, 'href="#feed-content-top"') !== false, 'fragment link follows the prefixed anchor');

check(feedimport_html_to_plain_text('<div>plain text</div>') === 'plain text', 'fallback keeps element text');
check(feedimport_html_to_plain_text('<script>alert(1)</script>') === '', 'fallback drops script bodies');
check(feedimport_html_to_plain_text('<script>alert(1)') === '', 'fallback drops unpaired script tags and their tail');
check(feedimport_html_to_plain_text('<style>.x{color:red}</style>') === '', 'fallback drops style bodies');
check(feedimport_html_to_plain_text('<object data="x">hidden</object>') === '', 'fallback drops object bodies');

$purify_content = strpos($importer_source, '$purified_content = phpwcms_purify_html($article_content, ');
$purify_summary = strpos($importer_source, '$article_summary = phpwcms_purify_html(');
$fallback = strpos($importer_source, ': feedimport_html_to_plain_text($article_content)');
$store_html = strpos($importer_source, '$cpdata[\'acontent_html\'] = $article_content');
$store_summary = strpos($importer_source, '"article_summary"       => $article_summary');

check($purify_content !== false, 'imported content is purified');
check($purify_summary !== false, 'imported summary is purified');
check(
    $purify_content !== false && $fallback !== false && $purify_content < $fallback,
    'content the allowlist empties falls back to plain text'
);
check($purify_content !== false && $store_html !== false && $purify_content < $store_html, 'content is purified before it is stored as CP14 HTML');
check($purify_summary !== false && $store_summary !== false && $purify_summary < $store_summary, 'summary is purified before it is stored on the article');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
