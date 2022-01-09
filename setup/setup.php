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

session_start();

$phpwcms = array();

require_once dirname(__FILE__).'/inc/setup.func.inc.php';
require_once $DOCROOT.'/setup/setup.conf.inc.php';

if (is_file($DOCROOT . '/include/config/conf.inc.php')) {
    header('HTTP/1.1 401 Authorization Required');
    header('Location: ../login.php');
    exit();
}

$step		= isset($_GET["step"]) ? intval($_GET["step"]) : 0;
$do			= isset($_POST["do"]) ? intval($_POST["do"]) : 0;
$err		= 0;
$prepend	= $phpwcms["db_prepend"];

if($do) require_once($DOCROOT.'/setup/inc/setup.check.inc.php');


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $phpwcms['charset'] ?>" />
<title>phpwcms Install</title>
<link href="inc/install.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" summary="">
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="[beliebiger Wert]" width="1" height="7" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="15" height="1" /><a href="http://www.phpwcms.org" target="_blank"><img src="../img/backend/backend_r1_c3.jpg" alt="phpwcms" width="95" height="24" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="7" /></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td valign="top" style="background: url(../img/backend/backend_r3_c4.gif) repeat-x;"><img src="../img/backend/backend_r3_c1.jpg" alt="" width="15" height="40" /></td>
    <td valign="top" style="background: url(../img/backend/backend_r3_c4.gif) repeat-x;"><table width="740" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
          <td colspan="2"><img src="../img/leer.gif" alt="" width="1" height="9" /></td>
        </tr>
        <tr>
          <td valign="top" class="navtext">PHPWCMS SETUP VERSION&nbsp;<?php echo $phpwcms_version.', RELEASE '.$phpwcms_release_date ?></td>
          <td align="right" valign="top" class="navtext"><a href="../index.php" target="_top">HOME</a> |
            <a href="upgrade.php">UPGRADE</a> | <a href="index.php" target="_top">LICENCE</a> | <a href="../<?php echo $phpwcms['login.php'] ?>" target="_top">LOGIN</a></td>
        </tr>
    </table></td>
    <td valign="top" style="background: url(../img/backend/backend_r3_c4.gif) repeat-x;"><img src="../img/backend/backend_r3_c7.jpg" alt="" width="15" height="40" /></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td width="15" bgcolor="#FFFFFF" style="background: url(../img/backend/preinfo2_r7_c2.gif) repeat-y;"><img src="../img/leer.gif" alt="" width="15" height="1" /></td>
    <td valign="top" bgcolor="#FFFFFF"><?php

		if(empty($NO_ACCESS)) {

			switch($step) {
				case  1:	include $DOCROOT.'/setup/inc/step1.inc.php'; break;
				case  2:	include $DOCROOT.'/setup/inc/step2.inc.php'; break;
				case  3:	include $DOCROOT.'/setup/inc/step3.inc.php'; break;
				case  4:	include $DOCROOT.'/setup/inc/step4.inc.php'; break;
				case  5:	include $DOCROOT.'/setup/inc/step5.inc.php'; break;
				default:	include $DOCROOT.'/setup/inc/step0.inc.php';
			}

		} else {
			?>
    <h1><img src="../img/famfamfam/action_stop.gif" alt="Setup STOP" class="icon" /> Setup
      stopped </h1>
			<p>Access on setup process is restricted if the setup config file is downloaded once. To re-enable setup remove the line</p>
			<p class="code">$NO_ACCESS = true;</p>
			<p>in setup.conf.inc.php.</p>
			<?php
		}
		?></td>
    <td width="15" bgcolor="#FFFFFF" style="background: url(../img/backend/preinfo2_r7_c7.gif) repeat-y right;"><img src="../img/leer.gif" alt="" width="15" height="1" /></td>
  </tr>
  <tr>
    <td><img src="../img/backend/backend_a_r1_c1.gif" alt="" width="15" height="15" border="0" /></td>
    <td valign="bottom" bgcolor="#FFFFFF" class="navtext"><img src="../img/backend/backend_r6_c2.jpg" alt="" width="740" height="15" border="0" /></td>
    <td valign="bottom" class="navtext"><img src="../img/backend/backend_a_r1_c7.gif" alt="" width="15" height="15" border="0" /></td>
  </tr>
  <tr>
    <td width="15"><img src="../img/leer.gif" alt="" width="14" height="20" /></td>
    <td colspan="2" valign="bottom" class="navtext">
		<a href="http://www.phpwcms.org" target="_blank">phpwcms</a>
		&copy; 2003&#8212;<?php echo date('Y') ?>
		<a title="oliver at phpwcms dot de" onclick="location.href='mailto:oliver'+'@'+'phpwcms'+'.'+'de';return false;" href="#">Oliver Georgi</a>.
		Licensed under <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GPL</a>.
        Extensions are copyright of their respective owners.
	</td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="8" /></td>
  </tr>
</table>
</body>
</html>