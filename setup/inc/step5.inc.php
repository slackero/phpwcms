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

if (!defined('PHP8')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

$_SERVER['DOCUMENT_ROOT'] = $phpwcms['DOC_ROOT'];
$phpwcms["root"] = !empty($phpwcms["root"]) ? "/".$phpwcms["root"] : "";

?>

<p><span class="title"><strong>Ready to start phpwcms?</strong> Some &quot;problems&quot;
    maybe OK - you can check by testing phpwcms installation.</span></p>
<table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr><?php

  $status = check_path_status($phpwcms["root"]."/".$phpwcms["file_path"]);
  if($status != 2) {
  	$status = set_chmod($phpwcms["root"]."/".$phpwcms["file_path"], 0777, $status);
  }

  ?>
    <td align="right" class="v10">filestorage:&nbsp;</td>
	<td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["file_path"]) ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

  <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
    <tr><?php

  $status = check_path_status($phpwcms["root"]."/".$phpwcms["file_path"].'/can_be_deleted');
  if($status != 2) {
  	$status = set_chmod($phpwcms["root"]."/".$phpwcms["file_path"].'/can_be_deleted', 0777, $status);
  }


  ?>
	<td align="right" class="v10">deleted&nbsp;files:&nbsp;</td>
	<td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["file_path"].'/can_be_deleted') ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

  <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  $status = check_path_status($phpwcms["root"]."/".$phpwcms["templates"]);

  ?>
    <td align="right" class="v10">templates:&nbsp;</td>
    <td<?php echo gib_bg_color($status) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["templates"]) ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status==1 ? 3 : $status) ?></td>
  </tr>

  <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php

  $template_lang_path = trim($phpwcms["templates"], '/').'/template_lang';
  $status = check_path_status($phpwcms["root"]."/".$template_lang_path);
  if($status != 2) {
  	$status = set_chmod($phpwcms["root"]."/".$template_lang_path, 0777, $status);
  }

  ?>
    <td align="right" class="v10">template&nbsp;languages:&nbsp;</td>
    <td<?php echo gib_bg_color($status) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($template_lang_path) ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

    <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  $status = check_path_status($phpwcms["root"]."/".$phpwcms["ftp_path"]);
  if($status != 2) {
  	$status = set_chmod($phpwcms["root"]."/".$phpwcms["ftp_path"], 0777, $status);
  }

  ?>
    <td align="right" class="v10">ftp&nbsp;takeover:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["ftp_path"]) ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

  <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="8"></td></tr>
  <tr><?php	$status = check_path_status($phpwcms["root"]."/".$phpwcms["content_path"]);	?>
    <td align="right" class="v10">frontend&nbsp;content:&nbsp;</td>
    <td<?php echo gib_bg_color($status) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["content_path"]) ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status==1 ? 3 : $status) ?></td>
  </tr>
  <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_path_status($phpwcms["root"]."/".$phpwcms["content_path"]."/images");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["content_path"]."/images", 0777, $status);
  	}

	?>
    <td align="right" class="v10">frontend&nbsp;images:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["content_path"]."/images") ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

    <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_path_status($phpwcms["root"]."/".$phpwcms["content_path"]."/form");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["content_path"]."/form", 0777, $status);
  	}
	?>
    <td align="right" class="v10">frontend&nbsp;form:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["content_path"]."/form") ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

    <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_path_status($phpwcms["root"]."/".$phpwcms["content_path"]."/tmp");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["content_path"]."/tmp", 0777, $status);
  	}

	?>
    <td align="right" class="v10">frontend&nbsp;tmp:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["content_path"]."/tmp") ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

    <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_path_status($phpwcms["root"]."/".$phpwcms["content_path"]."/rss");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["content_path"]."/rss", 0777, $status);
  	}
	?>
    <td align="right" class="v10">frontend&nbsp;rss:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["content_path"]."/rss") ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

    <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_path_status($phpwcms["root"]."/".$phpwcms["content_path"]."/pages");
	?>
    <td align="right" class="v10">frontend&nbsp;pages:&nbsp;</td>
    <td<?php echo gib_bg_color($status) ?>>&nbsp;<strong style="color:#fff;"><?php echo html_specialchars($phpwcms["content_path"]."/pages") ?></strong>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status==1 ? 3 : $status) ?></td>
  </tr>

       <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="8"></td></tr>
  <tr><?php
  	$status = check_file_status($phpwcms["root"]."/".$phpwcms["templates"]."/inc_default/startup.php");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["templates"]."/inc_default/startup.php", 0666, $status, 1);
  	}
	?>
    <td align="right" class="v10">startup text:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<span style="color:#fff;"><?php echo html_specialchars($phpwcms["templates"]."/inc_default/startup.php") ?></span>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

         <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_file_status($phpwcms["root"]."/".$phpwcms["templates"]."/inc_css/frontend.css");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["templates"]."/inc_css/frontend.css", 0666, $status, 1);
  	}

	?>
    <td align="right" class="v10">main CSS file:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<span style="color:#fff;"><?php echo html_specialchars($phpwcms["templates"]."/inc_css/frontend.css") ?></span>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

  <tr><td colspan="4" class="v10"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
  <tr><?php
  	$status = check_file_status($phpwcms["root"]."/include/config/conf.indexpage.inc.php");
	if($status != 2) {
  		$status = set_chmod($phpwcms["root"]."/".$phpwcms["templates"]."/include/config/conf.indexpage.inc.php", 0666, $status, 1);
  	}

	?>
    <td align="right" class="v10">index level settings:&nbsp;</td>
    <td<?php echo gib_bg_color($status==2?2:0) ?>>&nbsp;<span style="color:#fff;"><?php echo html_specialchars("include/config/conf.indexpage.inc.php") ?></span>&nbsp;</td>
    <td><img src="../img/leer.gif" alt="" width="1" height="19"></td>
    <td><?php echo gib_status_text($status) ?></td>
  </tr>

  <tr>
    <td colspan="4" class="v10">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="v10">&nbsp;</td>
    <td colspan="3"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td bgcolor="#99CC00">&nbsp;&nbsp;&nbsp;</td>
        <td class="v10">&nbsp;OK&nbsp;&nbsp;</td>
        <td bgcolor="#FF3300">&nbsp;&nbsp;&nbsp;</td>
        <td class="v10">&nbsp;PROBLEM</td>
      </tr>
    </table></td>
  </tr>
</table>
<?php

$this_root = dirname(dirname(dirname(__FILE__)));
$config_setup = read_textfile($this_root.'/setup/setup.conf.inc.php');
$config_setup = str_replace('?>', "\$NO_ACCESS = true;\n\n", $config_setup);
$result = false;
if(!is_file($this_root.'/include/config/conf.inc.php')) {

	// try to chmod
	//set_chmod($phpwcms["root"]."/include/config',

	// Try to write config file to the correct position
	if(!write_textfile($this_root.'/include/config/conf.inc.php', $config_setup)) {

		// Try to copy
		if(!@copy($this_root.'/setup/setup.conf.inc.php', $this_root.'/include/config/conf.inc.php')) {

			// Try to move
			if(@rename($this_root.'/setup/setup.conf.inc.php', $this_root.'/include/config/conf.inc.php')) {
				// moved successfully
				$result = true;
			}

		} else {
			// copied successfully
			$result = true;
		}

	} else {
		// written successfully
		$result = true;
	}
}

// Try to secure setup folder
@write_textfile($this_root.'/setup/.htaccess', 'Deny from all');

if($result): ?>
<p style="font-weight:bold;color:#99CC00;">
	The conf.inc.php was created successfully and placed at the right position by the setup script.
</p>
<?php else: ?>
<p style="font-weight:bold;color:#FF3300;">
	The conf.inc.php could not placed at the right position by the setup script.
</p>
<?php endif; ?>
<?php
// Create default .htaccess
if(is_file($this_root.'/.htaccess')):
?>
<p style="font-weight:bold;color:#FF3300;">
    A <strong>.htaccess</strong> file exists. Compare against the <a href="../_.htaccess" target="_blank">default</a>.
    If you want to use segmented URLs it is necessary to configure the Rewrite process.
</p>
<?php else:
    $result = false;
    // Try to copy
    if(!@copy($this_root.'/_.htaccess', $this_root.'/.htaccess')) {
        // Try to move
        if(@rename($this_root.'/_.htaccess', $this_root.'/.htaccess')) {
            // moved successfully
            $result = true;
        }
    } else {
        $result = true;
    }
    if($result): ?>
        <p style="font-weight:bold;color:#99CC00;">
            A default <strong>.htaccess</strong> file was placed in the document root of your installation.
            This file usually is hidden because of the leading <strong>.</strong> in the file name.
            <?php
            if($phpwcms["root"] && $htaccess = @read_textfile($this_root.'/.htaccess')) {
                $htaccess = str_replace('RewriteBase /', '#RewriteBase /', $htaccess);
                $htaccess = str_replace('#RewriteBase /subfolder/', 'RewriteBase /' . trim($phpwcms["root"], '/') . '/', $htaccess);
                write_textfile($this_root.'/.htaccess', $htaccess);
            }
            ?>
        </p>
    <?php else: ?>
        <p style="font-weight:bold;color:#FF3300;">
            Writing the <strong>.htaccess</strong> file to the document root of your installation failed.
            If you want to use Rewrite please configure it manually. See the file
            <a href="../_.htaccess" target="_blank">_.htaccess</a>.
        </p>
    <?php endif; ?>
<?php endif; ?>
<h4>The manual way to finish setup</h4>
<p>
	Download the config file <a href="get_conf_file.php"><strong>here</strong></a> and copy it to ./include/config/<strong>conf.inc.php</strong>
	&#8212; you can also edit values manually. Maybe you have to rename the file if you download
	using Internet Explorer (name is: <strong>conf.inc.php</strong>). Another possible way is to
	connect to your account by ftp. Then place the file ./setup/<strong>setup.conf.inc.php</strong>  in ./include/config. There you have to delete or rename default conf.inc.php &#8212; then
	rename setup.conf.inc.php to conf.inc.php. It's not possible to start the setup
	process again as long the setup folder still exists and if you have downloaded
	the created config file once.<br>
	<br>
	<strong style="color:#FF0000;">ATTENTION!!!<br>Delete
	the &quot;setup&quot; folder
	otherwise everybody might see your username, passwords and settings.</strong>
</p>
<p>
	To makes changes again or proof your values:<br />
	&#8212; <a href="setup.php?step=1">MySQL database infos</a><br />
	&#8212; <a href="setup.php?step=2">site infos and admin account</a><br />
	&#8212; <a href="setup.php?step=3">path values</a><br />
	&#8212; <a href="setup.php?step=4">content values</a>
</p>
<p>
	Please check infos about system and PHP version with the requirements. <strong>Very
    important when you use ImageMagick:</strong> Please check that the system
    can find the application (paths must be registered to the system - try this
    by using &quot;convert -version&quot; inside your terminal or command line).
    Check <a href="http://www.imagemagick.org/" target="_blank">http://www.imagemagick.org</a>
    for additional information.
</p>
