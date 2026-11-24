<?php
// tests/core/test-ajax-changer-rules.php — run: php8 tests/core/test-ajax-changer-rules.php
// ajax_changer.php toggled any column of any row for every logged-in backend
// user. Toggles are now limited to a whitelist of list columns, the id column
// is fixed per table and the same ownership/permission rule as the rendering
// screen is enforced.

$root = dirname(__DIR__, 2);
$changer = file_get_contents($root . '/include/inc_act/ajax_changer.php');
$backend = file_get_contents($root . '/include/inc_lib/backend.functions.inc.php');

define('DB_PREPEND', 'phpwcms_');
define('PHPWCMS_ROOT', $root);

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

function extract_fn(string $source, string $name): void
{
    if (!preg_match('/^function ' . preg_quote($name, '/') . '\b.*?^}/ms', $source, $match)) {
        echo "FAIL cannot extract $name\n";
        exit(1);
    }

    eval($match[0]);
}

extract_fn($backend, 'article_owner_restriction');
extract_fn($backend, 'articlecontent_owner_restriction');
extract_fn($backend, 'has_article_edit_all_permission');
extract_fn($changer, 'ajax_toggle_rules');
extract_fn($changer, 'ajax_toggle_restriction');

$GLOBALS['stub_permissions'] = [];
$GLOBALS['stub_groups'] = [];
$GLOBALS['stub_modules'] = [];
$GLOBALS['stub_ownership'] = true;

function article_ownership_enabled()
{
    return $GLOBALS['stub_ownership'];
}

function has_admin_permission($permission)
{
    return in_array($permission, $GLOBALS['stub_permissions'], true);
}

function is_user_in_group($user_id, $key)
{
    return in_array($key, $GLOBALS['stub_groups'], true);
}

function is_user_in_module_group($user_id, $key)
{
    return in_array($key, $GLOBALS['stub_modules'], true);
}

$rules = ajax_toggle_rules();

$expected = [
    'article.article_aktiv',
    'articlecontent.acontent_visible',
    'articlecat.acat_aktiv',
    'file.f_aktiv',
    'file.f_public',
    'content.cnt_status',
    'redirect.active',
    'subscription.subscription_active',
    'address.address_verified',
    'usergroup.group_active',
    'user.usr_aktiv',
    'shop_products.shopprod_status',
    'categories.cat_status',
];
$actual = [];
foreach ($rules as $table => $fields) {
    foreach ($fields as $field => $rule) {
        $actual[] = $table . '.' . $field;
    }
}
sort($expected);
sort($actual);
check($actual === $expected, 'whitelist covers exactly the list toggles and nothing else');

check(!isset($rules['user']['usr_admin']), 'user.usr_admin cannot be toggled');
check(!isset($rules['user']['usr_rechte']), 'user.usr_rechte cannot be toggled');
check(!isset($rules['usergroup']['group_member']), 'usergroup.group_member cannot be toggled');

$_SESSION = [];
$GLOBALS['stub_ownership'] = true;

[$allowed, $where] = ajax_toggle_restriction($rules['article']['article_aktiv'], 7);
check($allowed && $where === ' AND phpwcms_article.article_uid = 7', 'non-admin article toggle is limited to the owner');

$_SESSION['wcs_user_admin'] = 1;
[$allowed, $where] = ajax_toggle_restriction($rules['article']['article_aktiv'], 7);
check($allowed && $where === '', 'admin article toggle is unrestricted');
unset($_SESSION['wcs_user_admin']);

$GLOBALS['stub_permissions'] = ['artall'];
[$allowed, $where] = ajax_toggle_restriction($rules['article']['article_aktiv'], 7);
check($allowed && $where === '', 'artall permission lifts the owner restriction');
$GLOBALS['stub_permissions'] = [];

$GLOBALS['stub_ownership'] = false;
[$allowed, $where] = ajax_toggle_restriction($rules['article']['article_aktiv'], 7);
check($allowed && $where === '', 'disabled article ownership keeps shared editing');
$GLOBALS['stub_ownership'] = true;

[$allowed, $where] = ajax_toggle_restriction($rules['articlecontent']['acontent_visible'], 7);
check(
    $allowed && str_contains($where, 'acontent_aid IN (SELECT article_id FROM phpwcms_article WHERE article_uid = 7)'),
    'content part toggle is limited to content parts of owned articles'
);

[$allowed, $where] = ajax_toggle_restriction($rules['file']['f_public'], 7);
check($allowed && $where === ' AND f_uid = 7', 'file toggle is limited to own files without filecenter rights');

$GLOBALS['stub_groups'] = ['filecent'];
[$allowed, $where] = ajax_toggle_restriction($rules['file']['f_public'], 7);
check($allowed && $where === '', 'filecenter permission lifts the file owner restriction');
$GLOBALS['stub_groups'] = [];

[$allowed] = ajax_toggle_restriction($rules['user']['usr_aktiv'], 7);
check($allowed === false, 'user status toggle requires admuser');

$GLOBALS['stub_permissions'] = ['admuser'];
[$allowed] = ajax_toggle_restriction($rules['user']['usr_aktiv'], 7);
check($allowed === true, 'admuser grants the user status toggle');
$GLOBALS['stub_permissions'] = [];

$GLOBALS['stub_permissions'] = ['adm'];
[$allowed] = ajax_toggle_restriction($rules['articlecat']['acat_aktiv'], 7);
check($allowed === true, 'adm grants the structure visibility toggle');
$GLOBALS['stub_permissions'] = [];

$GLOBALS['stub_groups'] = ['artcent'];
[$allowed] = ajax_toggle_restriction($rules['articlecat']['acat_aktiv'], 7);
check($allowed === true, 'artcent grants the structure visibility toggle');
$GLOBALS['stub_groups'] = [];

[$allowed] = ajax_toggle_restriction($rules['articlecat']['acat_aktiv'], 7);
check($allowed === false, 'structure visibility toggle requires adm or artcent');

$GLOBALS['stub_groups'] = ['artnews'];
[$allowed] = ajax_toggle_restriction($rules['content']['cnt_status'], 7);
check($allowed === true, 'artnews grants the news status toggle');
$GLOBALS['stub_groups'] = [];

$GLOBALS['stub_groups'] = ['artcent'];
[$allowed] = ajax_toggle_restriction($rules['content']['cnt_status'], 7);
check($allowed === true, 'artcent grants the news status toggle');
$GLOBALS['stub_groups'] = [];

$GLOBALS['stub_modules'] = ['shop'];
[$allowed] = ajax_toggle_restriction($rules['shop_products']['shopprod_status'], 7);
check($allowed === true, 'shop module permission grants the product status toggle');
$GLOBALS['stub_modules'] = [];

[$allowed] = ajax_toggle_restriction($rules['subscription']['subscription_active'], 7);
check($allowed === false, 'subscription toggle requires an administrator');
$_SESSION['wcs_user_admin'] = 1;
[$allowed] = ajax_toggle_restriction($rules['subscription']['subscription_active'], 7);
check($allowed === true, 'administrator may toggle subscriptions');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
