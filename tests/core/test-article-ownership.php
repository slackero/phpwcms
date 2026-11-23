<?php
// tests/core/test-article-ownership.php — run: php8 tests/core/test-article-ownership.php
// Non-admin backend users may only write articles they own. The GET read path
// already filters by article_uid, but every POST/write path must enforce the
// same ownership itself, otherwise a forged request rewrites another user's
// article, content part or visibility flag.

$root = dirname(__DIR__, 2);
$failures = 0;

function check(bool $ok, string $message): void
{
    global $failures;
    echo ($ok ? 'OK   ' : 'FAIL ') . $message . "\n";
    if (!$ok) {
        $failures++;
    }
}

define('PHPWCMS_ROOT', $root);
define('DB_PREPEND', 'phpwcms_');
$phpwcms = [];
$GLOBALS['phpwcms'] = &$phpwcms;

$GLOBALS['test_admin_permission'] = false;
function has_admin_permission($permission)
{
    return $GLOBALS['test_admin_permission'] && $permission === 'artall';
}

$db_queries = [];
function _dbGet($table = '', $select = '*', $where = '', $group_by = '', $order_by = '', $limit = '', $prefix = null, $_queryMode = 'ASSOC')
{
    global $db_queries;
    $db_queries[] = ['table' => $table, 'select' => $select, 'where' => $where, 'limit' => $limit];
    if ($table === 'article' && str_contains($where, 'article_id=5') && str_contains($where, 'article_uid=7')) {
        return [['article_id' => 5]];
    }
    if ($table === 'article' && str_contains($where, 'article_id=5')) {
        return [['article_uid' => 7]];
    }
    return false;
}

require_once $root . '/include/inc_lib/backend.functions.inc.php';

$admin = true;
$editor = false;

check(function_exists('article_owner_restriction'), 'article_owner_restriction() exists');
check(
    function_exists('article_owner_restriction') && article_owner_restriction(7, $admin) === '',
    'admin writes are not restricted'
);
check(
    function_exists('article_owner_restriction') && article_owner_restriction(7, $editor) === ' AND phpwcms_article.article_uid = 7',
    'editor article writes are restricted to their own uid'
);
check(
    function_exists('article_owner_restriction') && article_owner_restriction(7, $editor, 'article_uid') === ' AND article_uid = 7',
    'the restricted column is configurable'
);

check(function_exists('articlecontent_owner_restriction'), 'articlecontent_owner_restriction() exists');
check(
    function_exists('articlecontent_owner_restriction') && articlecontent_owner_restriction(7, $admin) === '',
    'admin content part writes are not restricted'
);
check(
    function_exists('articlecontent_owner_restriction')
        && articlecontent_owner_restriction(7, $editor) === ' AND phpwcms_articlecontent.acontent_aid IN (SELECT article_id FROM phpwcms_article WHERE article_uid = 7)',
    'editor content part writes follow the owning article'
);

check(function_exists('has_article_edit_all_permission'), 'has_article_edit_all_permission() exists');
$GLOBALS['test_admin_permission'] = false;
unset($_SESSION['wcs_user_admin']);
check(
    function_exists('has_article_edit_all_permission') && has_article_edit_all_permission() === false,
    'a user outside the artall group is restricted'
);
$GLOBALS['test_admin_permission'] = true;
check(
    function_exists('has_article_edit_all_permission') && has_article_edit_all_permission() === true,
    'a member of the artall group may edit all articles'
);
$GLOBALS['test_admin_permission'] = false;
$_SESSION['wcs_user_admin'] = 1;
check(
    function_exists('has_article_edit_all_permission') && has_article_edit_all_permission() === true,
    'an administrator may edit all articles'
);
unset($_SESSION['wcs_user_admin']);

check(function_exists('article_ownership_enabled'), 'article_ownership_enabled() exists');
$phpwcms = [];
check(
    function_exists('article_ownership_enabled') && article_ownership_enabled() === true && has_article_edit_all_permission() === false,
    'article ownership is enforced by default'
);
$phpwcms['article_ownership'] = 0;
check(
    function_exists('article_ownership_enabled') && article_ownership_enabled() === false
        && has_article_edit_all_permission() === true,
    'article_ownership = 0 restores shared editing'
);
$phpwcms['article_ownership'] = 1;
check(
    function_exists('article_ownership_enabled') && article_ownership_enabled() === true
        && has_article_edit_all_permission() === false,
    'article_ownership = 1 enforces the ownership checks'
);
$phpwcms = [];
$GLOBALS['phpwcms'] = &$phpwcms;

check(function_exists('article_owner_on_save'), 'article_owner_on_save() exists');
check(
    function_exists('article_owner_on_save') && article_owner_on_save(5, 0, 1, true) === 7,
    'an admin save without the owner field keeps the existing owner'
);
check(
    function_exists('article_owner_on_save') && article_owner_on_save(5, 9, 1, true) === 9,
    'an explicitly selected owner is applied'
);
check(
    function_exists('article_owner_on_save') && article_owner_on_save(5, [9], 1, true) === 7,
    'non-scalar owner input is ignored'
);
check(
    function_exists('article_owner_on_save') && article_owner_on_save(0, 0, 1, true) === 1,
    'a new article is owned by its creator'
);
check(
    function_exists('article_owner_on_save') && article_owner_on_save(5, 9, 2, false) === 2,
    'a non-admin save cannot change the owner'
);

check(function_exists('article_is_writable_by_current_user'), 'article_is_writable_by_current_user() exists');
$_SESSION['wcs_user_id'] = 7;
$_SESSION['wcs_user_admin'] = 0;
$GLOBALS['test_admin_permission'] = false;
check(
    function_exists('article_is_writable_by_current_user') && article_is_writable_by_current_user(7) === true,
    'the owner may submit the article form'
);
check(
    function_exists('article_is_writable_by_current_user') && article_is_writable_by_current_user(8) === false,
    'a non-owner gets a read-only article form'
);
check(
    function_exists('article_is_writable_by_current_user') && article_is_writable_by_current_user(0) === false,
    'a missing article is never writable'
);
$GLOBALS['test_admin_permission'] = true;
check(
    function_exists('article_is_writable_by_current_user') && article_is_writable_by_current_user(8) === true,
    'the artall group may submit any article form'
);
$GLOBALS['test_admin_permission'] = false;
$phpwcms['article_ownership'] = 0;
check(
    function_exists('article_is_writable_by_current_user') && article_is_writable_by_current_user(8) === true,
    'shared editing keeps every article form writable'
);
$phpwcms['article_ownership'] = 1;
$_SESSION['wcs_user_admin'] = 0;
unset($_SESSION['wcs_user_id']);
unset($_SESSION['wcs_user_admin']);

check(function_exists('article_owned_by_user'), 'article_owned_by_user() exists');
$db_queries = [];
check(
    function_exists('article_owned_by_user') && article_owned_by_user(5, 7) === true,
    'an article owned by the editor is writable'
);
check(
    !empty($db_queries[0]) && $db_queries[0]['table'] === 'article'
        && str_contains($db_queries[0]['where'], 'article_uid=7'),
    'the ownership probe filters by article_uid'
);
check(
    function_exists('article_owned_by_user') && article_owned_by_user(5, 8) === false,
    'an article owned by somebody else is not writable'
);
check(
    function_exists('article_owned_by_user') && article_owned_by_user(0, 7) === false,
    'a missing article id is never writable'
);

$editcontent = (string)file_get_contents($root . '/include/inc_lib/article.editcontent.inc.php');
$readform = (string)file_get_contents($root . '/include/inc_lib/article.readform.inc.php');
$actcontent = (string)file_get_contents($root . '/include/inc_act/act_articlecontent.php');

check(
    str_contains($editcontent, '$sql .= article_owner_restriction((int)$_SESSION["wcs_user_id"]'),
    'the article summary save carries the owner restriction'
);
check(
    str_contains($editcontent, 'articlecontent_owner_restriction((int)$_SESSION["wcs_user_id"]'),
    'the content part update carries the owner restriction'
);
check(
    str_contains($editcontent, 'article_owned_by_user($content["aid"]') || str_contains($editcontent, 'article_owned_by_user($content[\'aid\']'),
    'creating a content part checks the target article owner'
);
check(
    preg_match('/\}\s*elseif\(\$content\["id"\]\)\s*\{\s*\/\/if content part should be updated/', $editcontent) === 1,
    'a refused content part insert never falls through to the update branch'
);
check(
    str_contains($readform, 'article_owner_restriction('),
    'moving a content part to another article checks that article owner'
);
check(
    preg_match('/case 3:.*?article_owner_restriction\(/s', $actcontent) === 1,
    'the article visibility toggle carries the owner restriction'
);

check(
    substr_count($editcontent, 'has_article_edit_all_permission()') >= 4,
    'every article write gate grants the artall group'
);
check(
    str_contains($readform, 'has_article_edit_all_permission()'),
    'the content part move target grants the artall group'
);
check(
    str_contains($actcontent, 'has_article_edit_all_permission()'),
    'the visibility toggle grants the artall group'
);

$shared_start = strpos($editcontent, 'acontent_aid               = ');
$shared_end = strpos($editcontent, '$SQL = trim(trim($SQL)', $shared_start);
check(
    $shared_start !== false && $shared_end !== false
        && !str_contains(substr($editcontent, $shared_start, $shared_end - $shared_start), 'acontent_uid'),
    'saving an existing content part does not change its owner'
);
check(
    preg_match('/INSERT INTO "\.DB_PREPEND\."articlecontent SET acontent_created=NOW\(\),\s*" \.\s*"acontent_uid=/', $editcontent) === 1,
    'only a new content part records the creating user as owner'
);

$navi = (string)file_get_contents($root . '/include/inc_lib/navi_text.inc.php');
check(str_contains($navi, "'artall'"), 'the artall group has a backend label');
$inserts = (string)file_get_contents($root . '/setup/default_sql/phpwcms_inserts.sql');
check(str_contains($inserts, "'artall'"), 'the artall group is seeded for new installations');
check(is_file($root . '/include/inc_lib/revision/r567.php'), 'existing installations get the artall group through r567');
$adminfunctions = (string)file_get_contents($root . '/include/inc_lib/admin.functions.inc.php');
check(
    substr_count($adminfunctions, 'article_is_writable_by_current_user($article[$akey]["article_uid"])') >= 5,
    'the article and content lists follow the write permission'
);
check(
    substr_count($adminfunctions, 'aria-disabled="true"') >= 3,
    'list actions are disabled instead of hidden without write permission'
);
$listtpl = (string)file_get_contents($root . '/include/inc_tmpl/articlecontent.list.tmpl.php');
check(
    substr_count($listtpl, '$article_writable') >= 5,
    'content list icons are disabled without write permission'
);
$backendcss = (string)file_get_contents($root . '/include/inc_css/phpwcms.css');
check(
    str_contains($adminfunctions, 'btn fa btn-sm visible btn-secondary disabled')
        && str_contains($listtpl, 'btn fa btn-sm visible btn-secondary disabled'),
    'disabled visibility toggles keep the toggle button size'
);
check(
    str_contains($backendcss, '.visible.btn-secondary'),
    'the stylesheet sizes the disabled visibility toggle'
);

$structure = (string)file_get_contents($root . '/include/inc_act/act_structure.php');
check(
    substr_count($structure, 'article_owner_restriction(') >= 3,
    'structure move, sort and delete actions follow the article owner'
);
$ajaxalias = (string)file_get_contents($root . '/include/inc_act/ajax_alias.php');
check(
    str_contains($ajaxalias, 'article_owner_restriction('),
    'the alias editor follows the article owner'
);

$langFiles = glob($root . '/include/inc_lang/backend/*/lang.inc.php');
$langHits = 0;
foreach ($langFiles as $langFile) {
    if (str_contains((string)file_get_contents($langFile), 'be_article_no_write_permission')) {
        $langHits++;
    }
}
check(
    count($langFiles) > 0 && $langHits === count($langFiles),
    'the write denial message exists in every backend language'
);

$actsort = (string)file_get_contents($root . '/include/inc_act/act_articlesort.php');
check(
    substr_count($actcontent, 'articlecontent_owner_restriction(') >= 2,
    'content part trash and visibility follow the article owner'
);
check(
    str_contains($actsort, 'articlecontent_owner_restriction('),
    'content part sorting follows the article owner'
);
check(
    str_contains($editcontent, 'set_status_message('),
    'a refused write reports a status message'
);

$contenttpl = (string)file_get_contents($root . '/include/inc_tmpl/articlecontent.edit.tmpl.php');
$summarytpl = (string)file_get_contents($root . '/include/inc_tmpl/article.editsummary.tmpl.php');
check(
    substr_count($contenttpl, 'phpwcmsArticleWritable') >= 2,
    'scripted submits respect the read-only state'
);
check(
    str_contains($contenttpl, 'aria-disabled') && str_contains($summarytpl, 'aria-disabled'),
    'disabled submit buttons expose their state'
);
check(
    str_contains($editcontent, '$article_writable = article_is_writable_by_current_user('),
    'the edit controller resolves the form writability'
);
check(
    str_contains($contenttpl, 'empty($article_writable)') && str_contains($contenttpl, ' disabled'),
    'the content part form is disabled for a non-owner'
);
check(
    str_contains($summarytpl, 'empty($article_writable)') && str_contains($summarytpl, ' disabled'),
    'the article form is disabled for a non-owner'
);

$distconf = (string)file_get_contents($root . '/include/config/dist.conf.inc.php');
check(str_contains($distconf, "\$phpwcms['article_ownership']"), 'the ownership switch ships in the default configuration');

// r567 must be idempotent and create the group only when it is missing
$GLOBALS['db_has_artall'] = false;
$GLOBALS['db_inserts'] = [];
function _dbEscape($value, $quotes = true, $like = '', $wildcard = '', $escape = false)
{
    return "'" . $value . "'";
}
function _dbQuery($query, $mode = 'ASSOC')
{
    if (str_contains($query, 'group_syskey')) {
        return $GLOBALS['db_has_artall'] ? [['group_id' => 40]] : [];
    }
    if (str_contains($query, 'usr_admin')) {
        return [['usr_id' => 1], ['usr_id' => 2]];
    }
    return [];
}
function _dbInsert($table, $data)
{
    $GLOBALS['db_inserts'][] = [$table, $data];
    return true;
}
require_once $root . '/include/inc_lib/revision/r567.php';
check(phpwcms_revision_r567() === true, 'r567 runs');
check(count($GLOBALS['db_inserts']) === 1, 'r567 creates the missing group');
check(
    ($GLOBALS['db_inserts'][0][0] ?? null) === 'usergroup'
        && ($GLOBALS['db_inserts'][0][1]['group_syskey'] ?? null) === 'artall',
    'r567 seeds the artall syskey'
);
check(
    ($GLOBALS['db_inserts'][0][1]['group_member'] ?? null) === '1,2',
    'r567 seeds the group with the current administrators'
);
$GLOBALS['db_has_artall'] = true;
$GLOBALS['db_inserts'] = [];
check(phpwcms_revision_r567() === true && count($GLOBALS['db_inserts']) === 0, 'r567 leaves an existing group alone');

exit($failures === 0 ? 0 : 1);
