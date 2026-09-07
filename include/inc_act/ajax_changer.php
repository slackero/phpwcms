<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// change value in table (aktive, public etc.)

$phpwcms = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
    headerRedirect('', 401);
    die('Sorry, access forbidden');
}

$table = isset($_GET['table']) ? _dbEscape(clean_slweg($_GET['table']), false) : false;
$field = isset($_GET['field']) ? _dbEscape(clean_slweg($_GET['field']), false) : false;
$fieldid = isset($_GET['fieldid']) ? _dbEscape(clean_slweg($_GET['fieldid']), false) : false;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($table && $field && $fieldid && $id) {

    $table = _dbNormalizeTable($table);

    //check if both fields are existing
    $result1 = _dbQuery("SHOW COLUMNS FROM " . DB_PREPEND . $table . " LIKE '" . $field . "'");
    $result2 = _dbQuery("SHOW COLUMNS FROM " . DB_PREPEND . $table . " LIKE '" . $fieldid . "'");

    if (!empty($result1) && !empty($result2)) {
        $sql = "UPDATE `" . DB_PREPEND . $table . "` SET `" . $field . "`= (CASE `" . $field . "` WHEN 1 THEN 0 ELSE 1 END) WHERE `" . $fieldid . "`=" . $id;
        _dbQuery($sql, 'UPDATE');
    }

}
