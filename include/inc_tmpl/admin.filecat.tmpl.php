<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if(isset($_GET["open"])) {
	list($open_id, $open_value) = explode(":", $_GET["open"]);
	$_SESSION["fcatlist"][intval($open_id)] = intval($open_value);
}
?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
          <tr><td colspan="2" class="title"><?php echo $BL['be_admin_fcat_title'] ?></td></tr>
		  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
		  <?php
		  if(isset($_GET["fcatid"])) {
		  ?>
		  <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
          <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
		  <?php
		  	$fcat["id"] = intval($_GET["fcatid"]);
			if($fcat["id"]) {	
				$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_id=".$fcat["id"]." LIMIT 1;";
				if($result = mysql_query($sql, $db) or die("error while getting file category infos")) {
					if($row = mysql_fetch_array($result)) {
						$fcat["name"]	= $row["fcat_name"];
						$fcat["active"]	= $row["fcat_aktiv"];
						$fcat["needed"]	= $row["fcat_needed"];
					}
					mysql_free_result($result);
				}
				$sendbutton = $BL['be_admin_fcat_button1'];
			} else {
				$sendbutton = $BL['be_admin_fcat_button2'];
			}
		  
			if(isset($_POST["fcat_aktion"]) && intval($_POST["fcat_aktion"])) { //Formular zum Bearbeiten der Dateikategorie-Namen
				
				$fcat["name"]	= clean_slweg($_POST["fcat_name"], 250);
				$fcat["id"]		= intval($_POST["fcat_id"]);
				$fcat["active"]	= empty($_POST["fcat_active"]) ? 0 : 1;
				$fcat["needed"]	= empty($_POST["fcat_needed"]) ? 0 : 1;
			
				if(isEmpty($fcat["name"])) {
					$fcat["error"] = 1; 
				} else {
					if(!$fcat["id"]) {
						$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_filecat (fcat_name, fcat_aktiv, fcat_needed) VALUES ('";
						$sql .= aporeplace($fcat["name"])."', ".$fcat["active"].", ".$fcat["needed"].");";						
					} else {
						$sql  = "UPDATE ".DB_PREPEND."phpwcms_filecat SET fcat_name='".aporeplace($fcat["name"]);
						$sql .= "', fcat_aktiv=".$fcat["active"].", fcat_needed=".$fcat["needed"]." WHERE fcat_id=".$fcat["id"].";";
					}
					if($result = mysql_query($sql, $db) or die("error while inserting/updating file category")) {
						if(!$fcat["id"]) $fcat["id"] = mysql_insert_id($db);
						headerRedirect(PHPWCMS_URL."phpwcms.php?do=admin&p=7");
					}			
				}
			
			}
		  
		  ?>
		  <form action="phpwcms.php?do=admin&p=7&fcatid=<?php echo $fcat["id"] ?>" method="post" name="filecategory">
		  <tr align="center" bgcolor="#F0F2F4"><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" summary="">
		  	<?php if(!empty($fcat["error"])) { ?>
		    <tr>
		      <td align="right" class="chatlist"><font color="#FF3300"><?php echo $BL['be_admin_usr_err'] ?>:</font>&nbsp;</td>
		      <td class="error"><strong><?php echo $BL['be_admin_fcat_err'] ?></strong></td>
		    </tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
			<?php } ?>
		    <tr>
		      <td align="right" class="chatlist"><?php echo $BL['be_admin_fcat_name'] ?>:&nbsp;</td>
		      <td><input name="fcat_name" type="text" id="fcat_name" class="f11b" style="width: 430px" value="<?php echo  empty($fcat["name"]) ? '' : html_specialchars($fcat["name"]) ?>" size="40" maxlength="250"></td>
		    </tr>
		    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
		    <tr>
		      <td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		      <td><table border="0" cellpadding="2" cellspacing="0" bgcolor="#D9DEE3" summary="">
                <tr>
                  <td><input name="fcat_active" type="checkbox" id="fcat_active" value="1"<?php is_checked(1, empty($fcat["active"]) ? 0 : $fcat["active"]); ?>></td>
                  <td>&nbsp;<?php echo $BL['be_ftptakeover_active'] ?></td>
                  <td><img src="img/leer.gif" alt="" width="15" height="1"></td>
                  <td><input name="fcat_needed" type="checkbox" id="fcat_needed" value="1"<?php is_checked(1, empty($fcat["needed"]) ? 0 : $fcat["needed"]); ?>></td>
                  <td><?php echo $BL['be_admin_fcat_needed'] ?>&nbsp;&nbsp;</td>
                </tr>
              </table></td>
		      </tr>
		    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
		    <tr>
		      <td><input name="fcat_id" type="hidden" id="fcat_id" value="<?php echo intval($fcat["id"]) ?>"><input name="fcat_aktion" type="hidden" id="fcat_aktion" value="1"></td>
		      <td><input name="Submit" type="submit" class="button10" style="width: 150px;" value="<?php echo $sendbutton ?>">&nbsp;&nbsp;<input name="donotsubmit" type="button" class="button10" style="width: 80px;" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='phpwcms.php?do=admin&p=7';"></td>
		      </tr>
		    </table></td>
		  </tr>
		  <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
		  </form>
		  <?php
		  } //Ende Anzeige Category Name Formular
		  
		  if(isset($_GET["fkeyid"])) { //Keyname
		  ?>
		  <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
          <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td>
        </tr>
		  <?php
		  	$fkey["id"] = intval($_GET["fkeyid"]);
			$fkey["cid"] = intval($_GET["cid"]);
			if($fkey["id"]) {	
				$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_id=".$fkey["id"]." LIMIT 1;";
				if($result = mysql_query($sql, $db) or die("error while getting file key infos")) {
					if($row = mysql_fetch_array($result)) {
						$fkey["name"]	= $row["fkey_name"];
						$fkey["active"]	= $row["fkey_aktiv"];
						$fkey["cid"]	= $row["fkey_cid"];
					}
					mysql_free_result($result);
				}
				$sendbutton = "update";
			} else {
				$sendbutton = "create";
			}
		  
			if(!empty($_POST["fkey_aktion"])) { //Formular zum Bearbeiten der Dateischlüssel-Namen
				
				$fkey["name"]	= clean_slweg($_POST["fkey_name"], 250);
				$fkey["id"]		= intval($_POST["fkey_id"]);
				$fkey["active"]	= intval($_POST["fkey_active"]);
				$fkey["cid"]	= intval($_POST["fkey_cid"]);
			
				if(isEmpty($fkey["name"])) {
					$fkey["error"] = 1; 
				} else {
					if(!$fkey["id"]) {
						$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_filekey (fkey_name, fkey_aktiv, fkey_cid) VALUES ('";
						$sql .= aporeplace($fkey["name"])."', ".$fkey["active"].", ".$fkey["cid"].");";						
					} else {
						$sql  = "UPDATE ".DB_PREPEND."phpwcms_filekey SET fkey_name='".aporeplace($fkey["name"]);
						$sql .= "', fkey_aktiv=".$fkey["active"].", fkey_cid=".$fkey["cid"]." WHERE fkey_id=".$fkey["id"].";";
					}
					if($result = mysql_query($sql, $db) or die("error while inserting/updating file key")) {
						if(!$fkey["id"]) $fkey["id"] = mysql_insert_id($db);
						headerRedirect(PHPWCMS_URL."phpwcms.php?do=admin&p=7");
					}			
				}
			
			}
		  
		  ?>
		  <form action="phpwcms.php?do=admin&p=7&fkeyid=<?php echo $fkey["id"]."&cid=".$fkey["cid"] ?>" method="post" name="filekey">
		  <tr align="center" bgcolor="#F0F2F4"><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" summary="">
		  <tr>
		      <td align="right" class="chatlist"><?php echo $BL['be_admin_fcat_fcat'] ?>:&nbsp;</td>
		      <td><select name="fkey_cid" class="f11b" id="fkey_cid">
			  <?php
			  	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_name;";
				if($result = mysql_query($sql, $db) or die("error while creating file category list")) {
					while($row = mysql_fetch_array($result)) {
						echo "<option value=\"".$row["fcat_id"]."\"".
							 (($row["fcat_id"]==$fkey["cid"])?" selected":"").
							 ">".html_specialchars($row["fcat_name"])."</option>\n";
					}
					mysql_free_result($result);
				}
			  
			  ?>
		        </select></td>
		    </tr>
		    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
		  	<?php if(!empty($fkey["error"])) { ?>
		    <tr>
		      <td align="right" class="chatlist"><span style="color:#FF3300"><?php echo $BL['be_admin_usr_err'] ?>:</span>&nbsp;</td>
		      <td class="error"><strong><?php echo $BL['be_admin_fcat_err1']  ?></strong></td>
		    </tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
			<?php } ?>
		    <tr>
		      <td align="right" class="chatlist"><?php echo $BL['be_admin_fcat_fkeyname'] ?>:&nbsp;</td>
		      <td><input name="fkey_name" type="text" id="fkey_name" class="f11b" style="width: 430px" value="<?php echo html_specialchars(empty($fkey["name"]) ? '' : $fkey["name"]) ?>" size="40" maxlength="250"></td>
		    </tr>
		    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
		    <tr>
		      <td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		      <td><table border="0" cellpadding="2" cellspacing="0" bgcolor="#D9DEE3" summary="">
                <tr>
                  <td><input name="fkey_active" type="checkbox" id="fkey_active" value="1"<?php is_checked(1, empty($fkey["active"]) ? 0 : $fkey["active"]); ?>></td>
                  <td>&nbsp;<?php echo $BL['be_ftptakeover_active'] ?></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1"></td>
                </tr>
              </table></td>
		      </tr>
		    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
		    <tr>
		      <td><input name="fkey_id" type="hidden" id="fkey_id" value="<?php echo intval($fkey["id"]) ?>"><input name="fkey_aktion" type="hidden" id="fkey_aktion" value="1"></td>
		      <td><input name="Submit" type="submit" class="button10" style="width: 125px;" value="<?php echo $sendbutton ?>">&nbsp;&nbsp;<input name="donotsubmit" type="button" class="button10" style="width: 80px;" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='phpwcms.php?do=admin&p=7';"></td>
		    </tr>
		    </table></td>
		  </tr>
		  <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
		  </form>
		  <?php
		  } //Ende Anzeige Key Name Formular
		  
		  
		  ?>
          <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
		  <?php
			$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_name;";
			if($result = mysql_query($sql, $db) or die("error while browsing file categories")) {
				while($row = mysql_fetch_array($result)) {
					
		 			echo "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
					echo "<td width=\"483\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n";
					
					$child_count = get_filecat_childcount ($row["fcat_id"], $db);
					//echo "<!-- ".$child_count." //-->\n";
					echo "<td>";
					echo ($child_count) ? "<a href=\"phpwcms.php?do=admin&p=7&open=".$row["fcat_id"].":".(empty($_SESSION["fcatlist"][$row["fcat_id"]])?1:0)."\">" : "";
					echo "<img src=\"img/symbole/plus_".(($child_count) ? (empty($_SESSION["fcatlist"][$row["fcat_id"]]) ? "open" : "close") : "empty");
					echo ".gif\" width=\"15\" height=\"15\" border=\"0\">".(($child_count) ? "</a>" : "")."</td>\n";
						
              		echo "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\"></td>\n";
					echo "<td class=\"dir\"><strong".(($row["fcat_needed"])?" style=\"color:#FF3300\"":"").">".html_specialchars($row["fcat_name"])."</strong></td>\n";
              		echo "</tr>\n</table></td>\n<td width=\"55\">";
					
					echo "<a href=\"phpwcms.php?do=admin&p=7&fkeyid=0&cid=".$row["fcat_id"]."\" title=\"".$BL['be_admin_fcat_addkey']."\">";
					echo "<img src=\"img/button/add_11x11.gif\" width=\"11\" height=\"11\" border=\"0\"></a>";
					
					echo "<a href=\"phpwcms.php?do=admin&p=7&fcatid=".$row["fcat_id"]."\" title =\"".$BL['be_admin_fcat_editcat']."\">";
					echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\"></a>";
					
					echo "<a href=\"include/inc_act/act_filecat.php?do=1,".$row["fcat_id"].",".(($row["fcat_aktiv"])?0:1)."\" title =\"".$BL['be_fprivfunc_cactivefile']."\">";
					echo "<img src=\"img/button/active_11x11_".$row["fcat_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\"></a>";
					
					echo "<a href=\"include/inc_act/act_filecat.php?do=8,".$row["fcat_id"]."\" title =\"".$BL['be_admin_fcat_delcat']."\" ";
					echo "onclick=\"return confirm('".$BL['be_admin_fcat_delcatmsg']."\\n[".html_specialchars($row["fcat_name"])."] ');\">";
					echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\"></a>";
					
					echo "</td>\n</tr>\n";
					
					
					if(isset($_SESSION["fcatlist"]) && isset($_SESSION["fcatlist"][$row["fcat_id"]]) && $_SESSION["fcatlist"][$row["fcat_id"]]) { //List key names for this categroy
						$ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_cid=".$row["fcat_id"].
								" AND fkey_deleted=0 ORDER BY fkey_name;";
						if($kresult = mysql_query($ksql, $db)) {
							while($krow = mysql_fetch_array($kresult)) {
								echo "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
								echo "<td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n";
								echo "<td><img src=\"img/leer.gif\" width=\"16\" height=\"1\">";
								echo "<img src=\"img/symbole/key_1.gif\" width=\"11\" height=\"15\"></td>\n";
								echo "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\"></td>\n";
								echo "<td class=\"dir\">".html_specialchars($krow["fkey_name"])."</td>\n";
								echo "</tr>\n</table></td>\n";
								echo "<td><img src=\"img/leer.gif\" width=\"11\" height=\"11\">";
							
								echo "<a href=\"phpwcms.php?do=admin&p=7&fkeyid=".$krow["fkey_id"]."&cid=".$row["fcat_id"]."\" title =\"".$BL['be_admin_fcat_editkey']."\">";
								echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\"></a>";
							
								echo "<a href=\"include/inc_act/act_filecat.php?do=2,".$krow["fkey_id"].",".(($krow["fkey_aktiv"])?0:1)."\" title =\"".$BL['be_fprivfunc_cactivefile']."\">";
								echo "<img src=\"img/button/active_11x11_".$krow["fkey_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\"></a>";
								
								echo "<a href=\"include/inc_act/act_filecat.php?do=9,".$krow["fkey_id"].",".($krow["fkey_cid"])."\" title =\"".$BL['be_admin_fcat_delkey']."\" ";
								echo "onclick=\"return confirm('".$BL['be_admin_fcat_delmsg']."\\n[".html_specialchars($krow["fkey_name"])."] ');\">";
								echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\"></a>";
								echo "</td>\n</tr>\n";
							}
							mysql_free_result($kresult);
						}
					} //Ende List Keynames
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
          <tr>
            <td colspan="2"><form action="phpwcms.php?do=admin&amp;p=7&amp;fcatid=0" method="post"><input type="submit" value="<?php echo $BL['be_admin_fcat_addcat'] ?>" class="button10" title="<?php echo $BL['be_admin_fcat_addcat'] ?>"></form></td>
          </tr>
</table>