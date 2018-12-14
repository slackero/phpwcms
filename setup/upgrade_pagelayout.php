<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

//used to convert old style file uploads

$cmsgo = array();

require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';

echo '<html><body><pre>';

echo 'CONVERT PAGELAYOUT' . LF;
echo '=================================================================' . LF.LF;

$pagelayout = _dbQuery("SELECT * FROM ".DB_PREPEND."cmsgo_pagelayout WHERE pagelayout_var NOT LIKE '%:{%'");

$c = 0;

foreach($pagelayout as $var) {
	$sql  = "UPDATE ".DB_PREPEND."cmsgo_pagelayout SET ";
	$sql .=	"pagelayout_var='".aporeplace(base64_decode($var['pagelayout_var']))."' ";
	$sql .= "WHERE pagelayout_id = ".$var['pagelayout_id'];
	$upgrade = _dbQuery($sql, 'UPDATE');

	echo html_specialchars($var['pagelayout_name']).': ';
	echo $upgrade['AFFECTED_ROWS'] ? $upgrade['AFFECTED_ROWS'] : html_specialchars($sql);
	echo LF;

	$c++;
}

if(!$c) {
	echo 'No pagelayout for conversation found!';
}

echo '</pre></body></html>';
