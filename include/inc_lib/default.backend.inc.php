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


// user is admin
define('IS_ADMIN',			empty($_SESSION["wcs_user_admin"]) ? false : true);
define('BE_CURRENT_URL',	PHPWCMS_URL.'phpwcms.php?'.$_SERVER['QUERY_STRING']);
define('ACTIVE_REFERER',	$_SESSION['REFERER_URL']);

$_SESSION['REFERER_URL'] =	BE_CURRENT_URL;


// some more important constants
define('JS_START',	'<script type="text/javascript">' . LF . '<!--' . LF);
define('JS_END',	LF . '// -->' . LF . '</script>');


?>