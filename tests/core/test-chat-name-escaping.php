<?php
// tests/core/test-chat-name-escaping.php — run: php7.4 tests/core/test-chat-name-escaping.php
// The global chat template emitted the author cell as `$row["chat_name"]`
// while the message text was passed through html(). A backend user who
// renamed the account to SVG markup therefore stored script for every other
// chat reader.

$root = dirname(__DIR__, 2);
$chat = file_get_contents($root . '/include/inc_tmpl/chat.list.tmpl.php');

$failures = 0;
require_once __DIR__ . '/lib/source-fixture.php';

check(strpos($chat, 'html($row["chat_name"])') !== false, 'chat author is HTML escaped');
check(strpos($chat, '">".$row["chat_name"]') === false, 'chat author is no longer concatenated raw');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
