<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// change value in table (aktive, public etc.)

session_start();
$cmsgo = array();
require '../../include/config/conf.inc.php';
require '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require CMSGO_ROOT.'/include/inc_lib/general.inc.php';
require CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION["wcs_user"])) {
  headerRedirect('', 401);
  die('Sorry, access forbidden');
}

$table = isset($_GET['table']) ? clean_slweg($_GET['table']) : false;
$field = isset($_GET['field']) ? clean_slweg($_GET['field']) : false;
$fieldid = isset($_GET['fieldid']) ? clean_slweg($_GET['fieldid']) : false;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//check if both fileds are existing
$result1 = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_".$table." LIKE '".$field."'");
$result2 = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_".$table." LIKE '".$fieldid. $fieldidadd ."'");

if(!empty($result1) && !empty($result2)) {
  $sql = "UPDATE ".DB_PREPEND."cmsgo_".$table." SET ".$field."= (CASE ".$field." WHEN 1 THEN 0 ELSE 1 END) WHERE ".$fieldid."=".$id.";";
  _dbQuery($sql, 'UPDATE');
  //echo $sql;
}
