<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type Form Email
$content["form"]			= explode("#:#", $row["acontent_form"]);
$content["mailform"]		= base64_decode($content["form"][0]);
$content["mailsubject"]		= $content["form"][1];
$content["mailrecipient"]	= $content["form"][2];
$content["mailbutton"]		= $content["form"][3];
$content["mailhtml"]		= intval($content["form"][4]);

?>