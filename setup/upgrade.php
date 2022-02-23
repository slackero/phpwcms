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

$phpwcms = array();

$_this_path = realpath(dirname(__FILE__).'/../');
if(is_file($_this_path.'/include/config/conf.inc.php')) {
	require_once $_this_path.'/include/config/conf.inc.php';
} else {
	die('Please proof location of "conf.inc.php".');
}

if (!defined('PHPWCMS_INCLUDE_CHECK')) {
   define('PHPWCMS_INCLUDE_CHECK', true);
}

require_once $_this_path.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/setup/inc/upgrade.func.inc.php';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Upgrade phpwcms</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="inc/install.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	padding: 0 15px 15px 15px;
	margin: 0;
}
td.chatlist {
    vertical-align: top;
    line-height: 135%;
    padding-bottom: 3px;
    padding-top: 3px;
}
</style>
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
          <td valign="top" class="navtext">PHPWCMS UPGRADE VERSION&nbsp;<?php echo $phpwcms['release'].', RELEASE '.$phpwcms['release_date'] ?></td>
          <td align="right" valign="top" class="navtext"><a href="../index.php" target="_top">HOME</a> |
            <a href="setup.php">SETUP</a> | <a href="index.php" target="_top">LICENCE</a> | <a href="<?php echo PHPWCMS_URL.get_login_file() ?>" target="_top">LOGIN</a></td>
        </tr>
    </table></td>
    <td valign="top" style="background: url(../img/backend/backend_r3_c4.gif) repeat-x;"><img src="../img/backend/backend_r3_c7.jpg" alt="" width="15" height="40" /></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td width="15" bgcolor="#FFFFFF" style="background: url(../img/backend/preinfo2_r7_c2.gif) repeat-y;"><img src="../img/leer.gif" alt="" width="15" height="1" /></td>
    <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px dotted #7599BB;" summary="">
        <tr>
          <td colspan="4"><img src="../img/leer.gif" alt="" width="1" height="6" /></td>
        </tr>
        <tr>
          <td width="6" rowspan="10"><img src="../img/leer.gif" alt="" width="6" height="1" /></td>
          <td align="right" class="chatlist">&nbsp;system:&nbsp;</td>
          <td class="chatlist" width="100%"><?php echo php_uname() ?></td>
          <td width="6" rowspan="10"><img src="../img/leer.gif" alt="" width="6" height="1" /></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">server:&nbsp;</td>
          <td class="chatlist"><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">php:&nbsp;</td>
          <td class="chatlist">v<?php echo PHP_VERSION ?></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">mysql:&nbsp;</td>
          <td class="chatlist"><?php echo _dbGetClientInfo() ?><br /><em>based on client, server might be different</em></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">path:&nbsp;</td>
          <td class="chatlist"><?php echo html_specialchars(str_replace("\\", '/', preg_replace('/\/setup$/','', dirname(__FILE__)))); ?></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">php.ini:&nbsp;</td>
          <td class="chatlist"><?php

		  if(ini_get('register_globals')) {
		  	echo 'register_globals = On -&gt; should always be set Off because of <a href="http://phpsec.org/projects/guide/1.html#1.3" target="_blank" style="text-decoration:underline">security risks</a>';
		  } else {
		    echo 'register_globals = Off -&gt; that\'s good :)';
		  }



		  ?></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">&nbsp;</td>
          <td class="chatlist"><?php

		  if(ini_get('safe_mode')) {
		  	echo 'safe_mode = On -&gt; you have limited permissions, you might not be able to use ImageMagick';
		  } else {
		    echo 'safe_mode = Off -&gt; good when you want to use ImageMagick, may have some <a href="http://phpsec.org/" target="_blank" style="text-decoration:underline">security risks</a>';
		  }

		  ?></td>
        </tr>
        <tr>
          <td align="right" class="chatlist">&nbsp;</td>
          <td class="chatlist">GD = <?php

				$_phpinfo = parsePHPModules();
				if(isset($_phpinfo['gd']['GD Support']) && $_phpinfo['gd']['GD Support'] == 'enabled') {
					echo 'On';
					echo isset($_phpinfo['gd']['GD Version']) ? ' -&gt; '.$_phpinfo['gd']['GD Version'] : '';
				} else {
					echo 'Off';
				}



		?></td>
        </tr>
        <tr>
          <td colspan="2" class="chatlist"><img src="../img/leer.gif" alt="" width="1" height="6" /></td>
        </tr>
        <tr>
          <td colspan="2" class="chatlist"><div id="warning">
<p><strong>ATTENTION! </strong>Before you start updating &#8212; <strong>backup</strong> all
  phpwcms files AND  all databases. Sometimes it might be better you merge SQL
   files manually. Don't forget to make copies of  CSS files, templates,
  images, settings and custom scripts.</p>
</div></td>
        </tr>

        <tr>
          <td colspan="4"><img src="../img/leer.gif" alt="" width="1" height="6" /></td>
        </tr>
    </table>



  <h1>When upgrading from releases older than 1.1.9:</h1>
     <p>
     There are some deeper changes. After upgrading db frame the following<br />
      files needs to be processed.<br />
      1) <a href="upgrade_filestorage.php" target="_blank"><strong>UPGRADE FILESTORAGE</strong></a> (all
      files will be moved and renamed)<br />
      2) <a href="upgrade_articleimages.php" target="_blank"><strong>UPGRADE ARTICLE
        CONTENT IMAGE</strong></a><br />
      3) <a href="upgrade_articleimagelist.php" target="_blank"><strong>UPGRADE ARTICLE
    CONTENT IMAGELIST</strong></a><br />
     4) <a href="upgrade_articleimg.php" target="_blank"><strong>UPGRADE ARTICLE
     SUMMARY IMAGE</strong></a></p>

     <h1>When upgrading from releases older than 1.2.9:</h1>
     <p>5) <a href="upgrade_pagelayout.php" target="_blank"><strong>UPGRADE PAGELAYOUT</strong></a></p>

     <h1>When upgrading from releases older than 1.3.1:</h1>
     <p>6) <a href="upgrade_multimedia.php" target="_blank"><strong>UPGRADE CONTENT PART MULTIMEDIA</strong></a><br />
     7) <a href="upgrade_articlealias.php" target="_blank"><strong>UPDATE ARTICLE ALIAS</strong></a></p>

<h1>Update old default article end date 2010-12-31, 23:59:59:</h1>
<p>8) <a href="upgrade_articledate.php" target="_blank"><strong>SET ARTICLE END 2010-12-31, 23:59:59 plus 20 YEARS</strong></a></p>

<?php
$do = 0;
if(isset($_POST['sqlfile']) && isset($_GET["do"]) && $_GET["do"] == "upgrade") {

	$file = str_replace('inc/showsql.php?f=', '', slweg($_POST['sqlfile']));
	if(file_exists("update_sql/".$file)) {
		$do = 1;
	}
}

if($do) {

    if (!PHPWCMS_DB_VERSION_57PLUS) {
        _dbQuery('SET storage_engine=MYISAM', 'SET');
    }
    _dbQuery("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'", 'SET');
    _dbQuery("SET NAMES '".$phpwcms['db_charset']."'".(empty($phpwcms['db_collation']) ? '' : " COLLATE '".$phpwcms['db_collation']."'"), 'SET');

	$sql_data = read_textfile("update_sql/".$file);
	$sql_data = preg_replace("/#.*.\n/", "", $sql_data );
	$sql_data = preg_replace("/ `phpwcms/", " `".DB_PREPEND."phpwcms", $sql_data );
	$sql = explode(";", $sql_data);

	echo '<div id="license" style="width:550px">';
	echo '<p><a href="upgrade.php">Chosse another SQL file...</a></p>';
	echo '<pre>';

	foreach($sql as $key => $value) {
		$value = trim($value);
		if(!$value) {
			unset($sql[$key]);
		} else {

			if($phpwcms['db_charset'] === 'utf8') {
				$value = utf8_encode($value);
			}

			if(!mysqli_query($GLOBALS['db'], $value)) {
    			echo '<span class="error">ERROR: '.html_entities(_dbError())." -&gt; </span>";
            }
			echo html_specialchars($value).";\n";
		}
	}

	echo '</pre></div>';

} else {

?>
<form action="upgrade.php?do=upgrade" method="post" name="form1" id="form1">
<p><strong>Please proof!</strong> Upgrade script will use following data:</p>
<table border="0" cellpadding="0" cellspacing="0" class="sqlselect" summary="">

<?php

if(empty($phpwcms['db_charset']) || empty($phpwcms['db_collation'])) {

?>
  <tr bgcolor="#FFFFFF">
    <td align="right" valign="top">MySQL basics:</td>
    <td style="color:#FFFFFF;background-color:	#CC3300;" valign="top">
		<strong>Before you continue proof the following config settings:</strong><br />
		$phpwcms['db_charset']<br />
		$phpwcms['db_collation']<br />
		If you are not sure how to handle this <br />
		try to start setup process! <br />
		<strong>But STOP SETUP BEFORE SQL IMPORT!!!</strong> </td>
  </tr>
<?php
}
?>

  <tr bgcolor="#FFFFFF">
    <td align="right">MySQL host:</td>
    <td style="font-weight:bold; "><?php echo $phpwcms["db_host"] ?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td align="right">Database:</td>
    <td style="font-weight:bold; "><?php echo $phpwcms["db_table"] ?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td align="right">DB user:</td>
    <td style="font-weight:bold; "><?php echo $phpwcms["db_user"] ?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td align="right">DB table prefix:</td>
    <td style="font-weight:bold; "><?php echo $phpwcms["db_prepend"] ?>&nbsp;</td>
  </tr>





  <tr bgcolor="#FFFFFF">
    <td align="right">&nbsp;select&nbsp;SQL&nbsp;file:</td>
    <td><select name="sqlfile" id="sqlfile" onchange="window.open(this.options[this.selectedIndex].value,'sqlqueries')">
      <option value="inc/showsql.php" style="font-weight:bold; font-style:italic;">Please select&#8230;</option>
      <?php

$dir = 'update_sql';
if(is_dir($dir)) {
	$ph			= opendir($dir);
	$dir_sql	= array();
	while($pf = readdir($ph)) {
   		if( substr($pf, 0, 1) !== '.' && is_file($dir.'/'.$pf) && preg_match('/(\.sql)$/i', $pf) ) {
			$dir_sql[] = html_specialchars($pf);
		}
	}
	closedir($ph);

	natsort($dir_sql);

	foreach($dir_sql as $pf) {

		echo '<option value="inc/showsql.php?f='.$pf.'">'.$pf."</option>\n";

	}

}

?>
    </select></td>
  </tr>
</table>
<p style="margin-top:15px;"><strong>SQL queries to be processed:</strong></p>
<iframe name="sqlqueries" id="sqlqueries" frameborder="0" scrolling="auto" src="inc/showsql.php"></iframe>
<p><input name="submit" type="submit" value="Upgrade database" /></p>
</form>
<?php

}

?>
	</td>
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
        Extensions are copyright of their respective owners.</td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="8" /></td>
  </tr>
</table>
</body>
</html>