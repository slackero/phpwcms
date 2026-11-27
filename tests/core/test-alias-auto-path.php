<?php
// tests/core/test-alias-auto-path.php — run: php8 tests/core/test-alias-auto-path.php
// alias_auto_path builds category and article aliases from the structure
// path. The own leaf stays editable, the prefix follows the menu, and a
// renamed or moved structure level re-roots the aliases of its branch.

$root = dirname(__DIR__, 2);
$failures = 0;

function check(bool $ok, string $message, string $got = ''): void
{
    global $failures;
    echo ($ok ? 'OK   ' : 'FAIL ') . $message . ($got === '' ? '' : " [$got]") . "\n";
    if (!$ok) {
        $failures++;
    }
}

define('PHPWCMS_ROOT', $root);
define('DB_PREPEND', 'phpwcms_');
define('PHPWCMS_REWRITE_EXT', '.html');
define('PHPWCMS_ALIAS_WSLASH', true);
define('PHPWCMS_ALIAS_AUTO_PATH', true);
define('PHPWCMS_ALIAS_MAXLEN', 170);

require_once $root . '/include/vendor/autoload.php';

function clean_slweg($string, $maxlen = 0)
{
    return trim((string)$string);
}

function uri_sanitize($text, $allow_slash = true)
{
    $text = strtolower(trim((string)$text));
    $pattern = $allow_slash ? '/[^a-z0-9\/]+/' : '/[^a-z0-9]+/';
    return trim(preg_replace($pattern, '-', $text), '-');
}

function _dbEscape($value, $like = false, $pre = '', $post = '')
{
    return "'" . addslashes((string)$value) . "'";
}

function returnSubdirListAsArray($path)
{
    return [];
}

$GLOBALS['alias_test_db'] = [
    'articlecat' => [
        1 => ['acat_id' => 1, 'acat_struct' => 0, 'acat_name' => 'folder', 'acat_alias' => 'folder'],
        2 => ['acat_id' => 2, 'acat_struct' => 1, 'acat_name' => 'sub', 'acat_alias' => 'folder/sub'],
        3 => ['acat_id' => 3, 'acat_struct' => 2, 'acat_name' => 'leaf', 'acat_alias' => 'folder/sub/leaf'],
    ],
    'article' => [
        10 => ['article_id' => 10, 'article_cid' => 3, 'article_title' => 'Child', 'article_alias' => 'folder/sub/leaf/child'],
    ],
];

function _dbGet($table = '', $select = '*', $where = '', $group_by = '', $order_by = '', $limit = '', $prefix = null, $_queryMode = 'ASSOC')
{
    $db = &$GLOBALS['alias_test_db'];
    if (!isset($db[$table])) {
        return false;
    }
    if (preg_match("/acat_id='?(\d+)'?/", $where, $m) && $table === 'articlecat') {
        return isset($db[$table][(int)$m[1]]) ? [$db[$table][(int)$m[1]]] : false;
    }
    if (preg_match("/acat_struct='?(\d+)'?/", $where, $m) && $table === 'articlecat') {
        $rows = [];
        foreach ($db[$table] as $row) {
            if ((int)$row['acat_struct'] === (int)$m[1]) {
                $rows[] = $row;
            }
        }
        return $rows ?: false;
    }
    if (preg_match("/article_cid='?(\d+)'?/", $where, $m) && $table === 'article') {
        $rows = [];
        foreach ($db[$table] as $row) {
            if ((int)$row['article_cid'] === (int)$m[1]) {
                $rows[] = $row;
            }
        }
        return $rows ?: false;
    }
    if ($table === 'articlecat' && str_contains($where, 'acat_trash=0')) {
        return array_values($db['articlecat']);
    }
    if ($table === 'article' && $where === 'article_deleted=0') {
        return array_values($db['article']);
    }
    if ($table === 'article' && str_contains($where, 'article_deleted=0 AND article_id>')) {
        $last = (int)substr($where, strpos($where, '>') + 1);
        $rows = [];
        foreach ($db['article'] as $row) {
            if ((int)$row['article_id'] > $last) {
                $rows[] = $row;
            }
        }
        return $rows ?: false;
    }
    return false;
}

function _dbQuery($sql, $mode = 'ASSOC')
{
    $db = &$GLOBALS['alias_test_db'];
    if ($mode === 'COUNT') {
        return 0;
    }
    if ($mode === 'UPDATE') {
        if (preg_match("/UPDATE phpwcms_article SET article_alias='([^']*)' WHERE article_id=(\d+)/", $sql, $m)) {
            $db['article'][(int)$m[2]]['article_alias'] = stripslashes($m[1]);
            return true;
        }
        if (preg_match("/UPDATE phpwcms_articlecat SET acat_alias='([^']*)' WHERE acat_id=(\d+)/", $sql, $m)) {
            $db['articlecat'][(int)$m[2]]['acat_alias'] = stripslashes($m[1]);
            return true;
        }
    }
    return false;
}

require_once $root . '/include/inc_lib/backend.functions.inc.php';

check(phpwcms_alias_auto_path_enabled(), 'auto path enabled');

check(phpwcms_alias_leaf('folder/sub/leaf') === 'leaf', 'leaf returns last segment', phpwcms_alias_leaf('folder/sub/leaf'));
check(phpwcms_alias_structure_path(3) === 'folder/sub/leaf', 'structure path of nested level', phpwcms_alias_structure_path(3));
check(phpwcms_alias_structure_path(3) !== 'folder/sub/sub', 'stored parent path is not duplicated');

check(
    phpwcms_alias_resolve(0, '', 'ARTICLE', 3, 'Child') === 'folder/sub/leaf/child',
    'empty alias uses title leaf under structure path',
    phpwcms_alias_resolve(0, '', 'ARTICLE', 3, 'Child')
);
check(
    phpwcms_alias_resolve(0, 'other/prefix/child', 'ARTICLE', 3) === 'folder/sub/leaf/child',
    'manual input keeps own leaf only',
    phpwcms_alias_resolve(0, 'other/prefix/child', 'ARTICLE', 3)
);
check(
    phpwcms_alias_resolve(0, 'Custom Leaf', 'ARTICLE', 1) === 'folder/custom-leaf',
    'manual leaf under parent path',
    phpwcms_alias_resolve(0, 'Custom Leaf', 'ARTICLE', 1)
);
check(
    phpwcms_alias_resolve(99, 'x', 'CONTENT', 3) === 'x',
    'content aliases stay flat',
    phpwcms_alias_resolve(99, 'x', 'CONTENT', 3)
);

$GLOBALS['alias_test_db']['articlecat'][1]['acat_alias'] = 'renamed';
phpwcms_alias_repath_branch(1, 'folder', 'renamed');

check(
    $GLOBALS['alias_test_db']['articlecat'][2]['acat_alias'] === 'renamed/sub',
    'child category is re-rooted',
    $GLOBALS['alias_test_db']['articlecat'][2]['acat_alias']
);
check(
    $GLOBALS['alias_test_db']['article'][10]['article_alias'] === 'renamed/sub/leaf/child',
    'article is re-rooted',
    $GLOBALS['alias_test_db']['article'][10]['article_alias']
);

$GLOBALS['alias_test_db']['articlecat'][2]['acat_alias'] = 'manual-alias';
phpwcms_alias_repath_branch(1, 'renamed', 'moved');
check(
    $GLOBALS['alias_test_db']['articlecat'][2]['acat_alias'] === 'manual-alias',
    'manual alias outside the old path is left alone',
    $GLOBALS['alias_test_db']['articlecat'][2]['acat_alias']
);

// Bulk recalc: consistent aliases produce no changes.
$GLOBALS['alias_test_db'] = [
    'articlecat' => [
        1 => ['acat_id' => 1, 'acat_struct' => 0, 'acat_name' => 'folder', 'acat_alias' => 'folder'],
        2 => ['acat_id' => 2, 'acat_struct' => 1, 'acat_name' => 'sub', 'acat_alias' => 'folder/sub'],
        3 => ['acat_id' => 3, 'acat_struct' => 2, 'acat_name' => 'leaf', 'acat_alias' => 'folder/sub/leaf'],
    ],
    'article' => [
        10 => ['article_id' => 10, 'article_cid' => 3, 'article_title' => 'Child', 'article_alias' => 'folder/sub/leaf/child'],
    ],
];
$recalc = phpwcms_alias_recalc('structure');
check(count($recalc['rows']) === 0, 'bulk recalc leaves consistent aliases alone', (string)count($recalc['rows']));

// A flat alias is re-rooted under its structure path.
$GLOBALS['alias_test_db']['article'][10]['article_alias'] = 'child';
$recalc = phpwcms_alias_recalc('structure');
check(count($recalc['rows']) === 1, 'bulk recalc finds one change', (string)count($recalc['rows']));
check(
    ($recalc['rows'][0]['new'] ?? '') === 'folder/sub/leaf/child',
    'bulk recalc re-roots a flat article alias',
    $recalc['rows'][0]['new'] ?? ''
);

// Title mode rebuilds the leaf from the title.
$GLOBALS['alias_test_db']['articlecat'][3]['acat_name'] = 'Leaf New';
$GLOBALS['alias_test_db']['article'][10]['article_title'] = 'Renamed Child';
$recalc = phpwcms_alias_recalc('title');
$pairs = [];
foreach ($recalc['rows'] as $row) {
    $pairs[$row['type'] . ':' . $row['id']] = $row['new'];
}
check(
    ($pairs['category:3'] ?? '') === 'folder/sub/leaf-new',
    'bulk recalc rebuilds a category leaf from its name',
    $pairs['category:3'] ?? ''
);
check(
    ($pairs['article:10'] ?? '') === 'folder/sub/leaf-new/renamed-child',
    'bulk recalc rebuilds an article leaf from its title',
    $pairs['article:10'] ?? ''
);

// Empty leaf keeps the current alias and is not reported as a change.
$GLOBALS['alias_test_db'] = [
    'articlecat' => [
        1 => ['acat_id' => 1, 'acat_struct' => 0, 'acat_name' => 'folder', 'acat_alias' => 'folder'],
        2 => ['acat_id' => 2, 'acat_struct' => 1, 'acat_name' => 'sub', 'acat_alias' => 'folder/sub'],
        3 => ['acat_id' => 3, 'acat_struct' => 2, 'acat_name' => '', 'acat_alias' => 'folder/sub/leaf'],
    ],
    'article' => [
        10 => ['article_id' => 10, 'article_cid' => 3, 'article_title' => '', 'article_alias' => 'child'],
    ],
];
$recalc = phpwcms_alias_recalc('title');
check(count($recalc['rows']) === 0, 'empty leaf keeps the current alias', (string)count($recalc['rows']));

// Categories with a trashed parent and their articles are not touched.
$GLOBALS['alias_test_db']['articlecat'][4] = ['acat_id' => 4, 'acat_struct' => 99, 'acat_name' => 'orphan', 'acat_alias' => 'orphan/x'];
$GLOBALS['alias_test_db']['articlecat'][5] = ['acat_id' => 5, 'acat_struct' => 4, 'acat_name' => 'child', 'acat_alias' => 'orphan/x/child'];
$GLOBALS['alias_test_db']['article'][11] = ['article_id' => 11, 'article_cid' => 5, 'article_title' => 'Orphan article', 'article_alias' => 'orphan/x/child/a'];
$recalc = phpwcms_alias_recalc('title');
$touched = array_map(static fn($row) => $row['type'] . ':' . $row['id'], $recalc['rows']);
check(!in_array('category:4', $touched, true), 'category with trashed parent is skipped');
check(!in_array('category:5', $touched, true), 'child of a skipped category is skipped');
check(!in_array('article:11', $touched, true), 'article in a skipped category is skipped');

// Redirect entries: safe aliases only.
$entries = phpwcms_alias_redirect_entries([
    ['old' => 'folder/old', 'new' => 'folder/new'],
    ['old' => '', 'new' => 'folder/new'],
    ['old' => 'folder/wild_card', 'new' => 'folder/new'],
    ['old' => 'folder/percent%name', 'new' => 'folder/new'],
    ['old' => 'folder/same', 'new' => 'folder/same'],
    ['old' => str_repeat('a', 260), 'new' => 'folder/new'],
    ['old' => 'folder/new', 'new' => 'folder/other'],
]);
check(count($entries) === 1, 'only safe redirect entries are created', (string)count($entries));
check(
    ($entries[0]['alias'] ?? '') === 'folder/old' && ($entries[0]['target'] ?? '') === 'folder/new'
        && ($entries[0]['code'] ?? '') === '301' && ($entries[0]['type'] ?? '') === 'alias'
        && ($entries[0]['active'] ?? 0) === 1,
    'redirect entry carries alias, target, type, code and active state'
);

// Plan signatures are order independent and detect changed plans.
$plan_a = [
    ['type' => 'category', 'id' => 1, 'old' => 'a', 'new' => 'b'],
    ['type' => 'article', 'id' => 2, 'old' => 'c', 'new' => 'd'],
];
$plan_b = array_reverse($plan_a);
$plan_c = $plan_a;
$plan_c[1]['new'] = 'e';
check(phpwcms_alias_plan_signature($plan_a) === phpwcms_alias_plan_signature($plan_b), 'plan signature ignores row order');
check(phpwcms_alias_plan_signature($plan_a) !== phpwcms_alias_plan_signature($plan_c), 'plan signature detects a changed row');

// Pending confirmation: token and TTL.
$pending_plan = ['pending' => true, 'token' => str_repeat('a', 32), 'time' => 1000];
check(phpwcms_alias_pending_valid($pending_plan, $pending_plan['token'], 1100), 'pending token valid inside TTL');
check(!phpwcms_alias_pending_valid($pending_plan, $pending_plan['token'], 2000), 'expired pending token rejected');
check(!phpwcms_alias_pending_valid($pending_plan, str_repeat('b', 32), 1100), 'wrong pending token rejected');
check(!phpwcms_alias_pending_valid(null, $pending_plan['token'], 1100), 'missing pending rejected');

echo $failures === 0 ? "ALL OK\n" : "$failures FAILED\n";
exit($failures === 0 ? 0 : 1);
