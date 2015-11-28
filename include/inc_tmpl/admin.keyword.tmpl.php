<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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


// keyword administration

include_once PHPWCMS_ROOT.'/include/inc_lib/lib.keywords.inc.php';

echo '<h3 class="title">'.$BL['be_admin_keywords'].'</h3>'.LF;

// check if rights to edit keywords
if(!IS_ADMIN) {

	echo '<p>Sorry, you have no rights to edit keywords</p>';


// list keywords
} elseif(empty($_POST['keyword_action'])) {

	echo backend_list_keywords();

// new keyword
} elseif($_POST['keyword_action'] == 'update') {



// update keyword
} elseif($_POST['keyword_action'] == 'edit') {

	echo backend_edit_keywords();

// delete keyword
}  elseif($_POST['keyword_action'] == 'delete') {




// error
} else {

	echo '<p>There seems to be a problem editing keywords. Contact admin.</p>';

}


// old
$keyword["id"] = 0;

?>

<!--
<table width="538" border="0" cellpadding="0" cellspacing="0">
          <tr><td colspan="2" class="title"><?php echo $BL['be_admin_keywords'] ?></td></tr>
		  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
		  <?php
		  if(isset($_GET["keyid"])) {
		  ?>
		  <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
          <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
		  <?php
		  	$keyword["id"] = intval($_GET["keyid"]);
			if($keyword["id"]) {
				$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_id=".$keyword["id"]." LIMIT 1;";
				if($result = mysql_query($sql, $db) or die("error while retrieving keywords")) {
					if($row = mysql_fetch_array($result)) {
						$keyword["name"]	= $row["keyword_name"];
					}
					mysql_free_result($result);
				}
				$sendbutton = $BL['be_admin_fcat_button1'];
			} else {
				$sendbutton = $BL['be_admin_fcat_button2'];
			}

			if(isset($_POST["keyword_aktion"]) && intval($_POST["keyword_aktion"])) { // show form for editing keywords

				$keyword["name"]	= clean_slweg($_POST["keyword_name"], 250);
				$keyword["id"]		= intval($_POST["keyword_id"]);

				$keyword["name"]	= str_replace(';', ' ', $keyword["name"]);
				$keyword["name"]	= str_replace(',', ' ', $keyword["name"]);
				$keyword["name"]	= preg_replace('/\s{1,}/', ' ', $keyword["name"]);

				if(empty($keyword["name"])) {
					$keyword["error"] = 1;
				} else {
					if(!$keyword["id"]) {
						$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_keyword SET ";
						$sql .= "keyword_name = '".aporeplace($keyword["name"])."'";
					} else {
						$sql  = "UPDATE ".DB_PREPEND."phpwcms_keyword SET ";
						$sql .= "keyword_name='".aporeplace($keyword["name"]);
						$sql .= "' WHERE keyword_id=".$keyword["id"];
					}
					if($result = mysql_query($sql, $db) or die("error while inserting/updating keyword")) {
						if(!$keyword["id"]) $keyword["id"] = mysql_insert_id($db);
						headerRedirect(PHPWCMS_URL."phpwcms.php?do=admin&p=5");
					}
				}

			}

		  ?>
		  <form action="phpwcms.php?do=admin&amp;p=5&amp;keyid=<?php echo $keyword["id"] ?>" method="post" name="keywords">
		  <tr align="center" bgcolor="#F0F2F4"><td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
		  	<?php if(!empty($keyword["error"])) { ?>
		    <tr>
		      <td align="right" class="chatlist"><font color="#FF3300"><?php echo $BL['be_admin_usr_err'] ?>:</font>&nbsp;</td>
		      <td class="error"><strong><?php echo $BL['be_admin_keywords_err'] ?></strong></td>
		    </tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
			<?php } ?>
		    <tr>
		      <td align="right" class="chatlist"><?php echo $BL['be_admin_keywords_key'] ?>:&nbsp;</td>
		      <td><input name="keyword_name" type="text" id="keyword_name" class="f11b" style="width: 430px" value="<?php echo empty($keyword["name"]) ? '' : html($keyword["name"]) ?>" size="40" maxlength="250"></td>
		    </tr>
		    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
		    <tr>
		      <td><input name="keyword_id" type="hidden" id="keyword_id" value="<?php echo intval($keyword["id"]) ?>"><input name="keyword_aktion" type="hidden" id="keyword_aktion" value="1"></td>
		      <td><input name="Submit" type="submit" class="button" style="width: 150px;" value="<?php echo $sendbutton ?>">&nbsp;&nbsp;<input name="donotsubmit" type="button" class="button" style="width: 80px;" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='phpwcms.php?do=admin&p=5';"></td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
		  </form>
		  <?php
		  } //Ende Anzeige Category Name Formular
	  ?>
          <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
		  <?php
			$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_trash=0 ORDER BY keyword_name;";
			if($result = mysql_query($sql, $db) or die("error while browsing keyword list")) {
				while($row = mysql_fetch_assoc($result)) {

		 			echo "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
					echo "<td width=\"483\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n";

					echo "<td>";
					echo "<img src=\"img/symbole/plus_empty.gif\" width=\"15\" height=\"15\" border=\"0\"></td>\n";

              		echo "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\"></td>\n";
					echo "<td class=\"dir\"><strong>".html($row["keyword_name"])."</strong></td>\n";
              		echo "</tr>\n</table></td>\n<td width=\"55\" align=\"right\">";

					echo "<a href=\"phpwcms.php?do=admin&p=5&keyid=".$row["keyword_id"]."\" title =\"".$BL['be_admin_keyword_edit']."\">";
					echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\"></a>";

					echo "<a href=\"include/inc_act/act_filecat.php?do=8,".$row["keyword_id"]."\" title =\"".$BL['be_admin_keyword_del']."\" ";
					echo "onclick=\"return confirm('".$BL['be_admin_keyword_delmsg']."\\n[".html($row["keyword_name"])."] ');\">";
					echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\"></a>";

					echo "</td>\n</tr>\n";

		  		}
		  		mysql_free_result($result);
			}
		?>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
          <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
          <tr>
            <td><img src="img/leer.gif" alt="" width="483" height="1"></td>
			<td><img src="img/leer.gif" alt="" width="55" height="5"></td>
          </tr>
          <tr><td colspan="2"><form action="phpwcms.php?do=admin&amp;p=5&amp;keyid=0" method="post"><input type="submit" value="<?php echo $BL['be_admin_keyword_add'] ?>" class="button" title="<?php echo $BL['be_admin_keyword_add'] ?>"></form></td></tr>
</table>
// -->