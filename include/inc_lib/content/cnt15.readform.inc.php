<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



// Content Type Article List Menu
$content["alist"]["cat"]			= isset($_POST['calist_cat']) ? intval($_POST['calist_cat']) : 0;
$content["alist"]["catid"]			= intval($_POST['calist_catid']);
$content["alist"]["headertext"]		= isset($_POST['calist_headertext']) ? 1 : 0;
$content["alist"]["ul"]				= isset($_POST['calist_ul']) ? intval($_POST['calist_ul']) : 0;
$content["alist"]["class"]			= clean_slweg($_POST['calist_class']);
$content["alist"]["maxchar"]		= intval($_POST['calist_maxchar']);
$content["alist"]["morelink"]		= slweg($_POST['calist_morelink']);
$content["alist"]["titlewrap"]		= clean_slweg($_POST['calist_titlewrap']);
$content["alist"]["hideactive"]		= empty($_POST['calist_hideactive']) ? 0 : 1;
$content["alist"]["titleasnumber"]	= empty($_POST['calist_titleasnumber']) ? 0 : 1;
$content["alist"]["break"]			= slweg($_POST['calist_break'], 0, false);
$content["alist"]["label"]			= slweg($_POST['calist_label']);

switch($content["alist"]["ul"]) {
	case 4:	// SPAN
	case 3:	// DL
	case 2:	// DIV
	case 1: // UL
	    break;
	default: // TABLE
	    $content["alist"]["ul"] = 0;
}
