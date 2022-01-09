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

//user group
$GLOBALS['BE']['HEADER']['optionselect.js'] = getJavaScriptSourceLink('include/inc_js/optionselect.js');

?>

<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td colspan="3" class="title"><?php echo $BL['be_subnav_admin_groups'] ;?></td></tr>
<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<?php

    $bg_color1 = "#FFFFFF";
    $bg_color2 = "#F3F5F8";
    $zaehler = 0;
    if(empty($new_group_id)) {
        $new_group_id = 0;
    }
    //Liste aller Gruppen erzeugen
    $result = _dbGet('phpwcms_usergroup', '*', 'group_active != 9', '', 'group_name');

    if(isset($result[0]['group_id'])) {

        foreach($result as $grouplist) {

            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;

            if($grouplist["group_id"] == $new_group_id) {
                $bg_color = "#FFCC00";
            }

            $goto = "phpwcms.php?do=admin&amp;p=1&amp;s=2&amp;u=".$grouplist["group_id"];

?>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr bgcolor="<?php echo  $bg_color ?>" onmouseover="bgColor='#DBFF48'" onmouseout="bgColor='<?php echo $bg_color ?>'">
          <td width="19" align="center"><img src="img/usricon/group_<?php echo $grouplist["group_active"] == 1 ? 'norm' : 'inaktiv'; ?>.gif" alt="" width="19" height="16" border="0" /></td>
          <td width="458" class="<?php echo $grouplist["group_active"] ? 'dir' : 'inaktiv'; ?>"><a href="<?php echo $goto ?>"><?php

            $grouparray = convertStringToArray($grouplist["group_member"]);
            $total_member = empty($grouparray[0]) ? 0 : count($grouparray);

            echo $grouplist["group_name"] ? html($grouplist["group_name"]).' <span style="color:#999999;">('.$total_member.' '.$BL['be_cnt_rssfeed_item'].')</span>' : 'n.a.';


          ?></a></td>
          <td width="61" align="right"><a href="<?php echo $phpwcms['modules']['usergroup']['dir']; ?>include/inc_act/act_usergroup.php?aktiv=<?php

            echo $grouplist["group_id"].':'.($grouplist["group_active"] ? "0" : "1");

            ?>"><img src="img/button/<?php
                if(empty($grouplist["group_active"])) echo "in";
            ?>aktiv_mini.gif" alt="" width="14" height="15" border="0" /></a><a href="<?php echo $goto ?>"><img src="img/button/edit.gif" alt="" width="24" height="15" border="0" title="<?php
                echo $BL['modules']['usergroup']['be_admin_group_edit'].": ".html($grouplist["group_name"])
            ?>"></a><a href="<?php echo $phpwcms['modules']['usergroup']['dir']; ?>include/inc_act/act_usergroup.php?del=<?php
                echo urlencode($grouplist["group_id"].":".$grouplist["group_name"]);
            ?>" onclick="return confirm('Delete group <?php echo js_singlequote($grouplist["group_name"]) ?>');"><img src="img/button/del_message_final.gif" alt="" width="22" height="15" border="0" title="<?php
                echo $BL['modules']['usergroup']['be_admin_group_ldel']." ".html($grouplist["group_login"])
            ?>"></a></td>
        </tr>
        <?php

        }

    } else {
            echo '<tr><td colspan="3">'.$BL['be_admin_group_nogroup'].': <a href="phpwcms.php?do=admin&amp;p=1&amp;create_group=1">'.$BL['be_admin_group_add'].'</a></td></tr>';

    }

        ?>
            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
            <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
            <tr><td colspan="3"><form action="phpwcms.php?do=admin&amp;p=1&amp;create_group=1" method="post"><input type="submit" value="<?php echo $BL['be_admin_group_add'] ?>" class="button" title="<?php echo $BL['be_admin_group_add'] ?>"></form></td></tr>
        </table>

<!-- CREATE GROUP -->


          <?php
            //enym was groupid
          if(isset($_GET["create_group"]) || isset($_GET["u"])) {
          ?>
            <br /><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
          <tr><td colspan="2" class="title"><?php echo isset($_GET["create_group"]) ? $BL['be_admin_group_add'] : $BL['be_subnav_admin_groups'] ." ". $BL['be_cnt_guestbook_edit']; ?></td></tr>

          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
          <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
    </tr>
          <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
    </tr>
          <?php

            $group["id"]        = empty($_GET["u"]) ? 0 : intval($_GET["u"]);
            $group["name"]      = '';
            $group["member"]    = array();
            $group["value"]     = '';
            $group["trash"]     = 0;
            $group["active"]    = 1;

            if($group["id"]) {

                $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_usergroup WHERE group_id=".$group["id"]." LIMIT 1";
                $result = _dbQuery($sql);
                if(isset($result[0]['group_id'])) {
                    $group["name"]      = $result[0]["group_name"];
                    $group["member"]    = empty($result[0]["group_member"]) ? array() : explode(',', $result[0]["group_member"]);
                    $group["value"]     = $result[0]["group_value"];
                    $group["trash"]     = $result[0]["group_trash"];
                    $group["active"]    = $result[0]["group_active"];
                }

                $sendbutton = $BL['be_admin_fcat_button1']; //UPDATE GROUP

            } else {

                $sendbutton = $BL['be_admin_fcat_button2'];   //create group
            }

            if(!empty($_POST["group_aktion"])) {

                $group["id"]        = intval($_POST["group_id"]);
                $group["name"]      = clean_slweg($_POST["group_name"], 250);
                $group["member"]    = isset($_POST["acat_access"]) && is_array($_POST["acat_access"]) ? implode(',', $_POST["acat_access"]) : '';
                $group["value"]     = clean_slweg($_POST["group_value"]);
                $group["trash"]     = empty($_POST["group_trash"]) ? 0 : intval($_POST["group_trash"]);
                $group["active"]    = empty($_POST["group_active"]) ? 0 : 1;

                if(empty($group["name"])) {

                    $group["error"] = 1;

                } else {

                    $data = array(

                        'group_name'    => $group["name"],
                        'group_member'  => $group["member"],
                        'group_value'   => $group["value"],
                        'group_trash'   => $group["trash"],
                        'group_active'  => $group["active"]
                    );

                    $result = $group["id"] ? _dbUpdate('phpwcms_usergroup', $data, 'group_id='.$group["id"]) : _dbInsert('phpwcms_usergroup', $data);

                    if(isset($result['AFFECTED_ROWS']) || isset($result['INSERT_ID'])) {
                        headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=1');
                    } else {
                        echo _dbError();
                    }

                }

                $group["member"] = convertStringToArray($group["member"]);

            }
          ?>

          <form action="phpwcms.php?do=admin&amp;p=1&amp;create_group=1" method="post" name="editsitestructure" id="editsitestructure" onsubmit="selectAllOptions(this.acat_access);selectAllOptions(this.acat_cp);var x = wordcount(this.acat_name.value);if(x&lt;1) {alert('Fill in a category title! \n\n('+x+' words total)');this.acat_name.focus();return false;}">
          <tr align="center" bgcolor="#F0F2F4"><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" summary="">
            <?php if(!empty($group["error"])) { ?>
            <tr>
              <td align="right" class="chatlist" style="color:#FF3300;"><?php echo $BL['be_admin_usr_err'] ?>:&nbsp;</td>
              <td class="error"><strong><?php echo $BL['be_fpriv_name'] ?></strong></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
            </tr>
            <?php } ?>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_fpriv_name'] ?>:&nbsp;</td>
              <td><input name="group_name" type="text" id="group_name" class="width400" value="<?php echo empty($group["name"]) ? '' : html($group["name"]) ?>" size="40" maxlength="250" /></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

        <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
              <td><textarea name="group_value" id="group_value" class="width400" rows="3"><?php echo html($group["value"]) ?></textarea></td>
            </tr>

        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

          <!-- USER RIGHTS enym.com   -->

        <tr>
    <td align="right" class="chatlist"><?php echo $BL['be_selection'] ?>:&nbsp;</td>

    <td valign="top">
<?php

// list all available frontend users and put into temp array
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_aktiv != 9 ORDER BY usr_fe, usr_name, usr_login";
$result = _dbQuery($sql);
$_temp_usr = array();
if(isset($result[0]['usr_id'])) {
    foreach($result as $row) {
        $_temp_usr[$row['usr_id']]['name']   = html($row['usr_name']);
        $_temp_usr[$row['usr_id']]['login']  = html($row['usr_login']);
        $_temp_usr[$row['usr_id']]['fe']     = $row['usr_fe'];
        $_temp_usr[$row['usr_id']]['active'] = $row['usr_aktiv'];
        $_temp_usr[$row['usr_id']]['admin'] = $row['usr_admin'];
    }
}

?>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><select name="acat_access[]" id="acat_access" size="12" multiple="multiple" class="width185" onDblClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);">
<?php
    if(count($_temp_usr)) {
        // list all fe_users
        foreach($_temp_usr as $key => $value) {
            if(isset($group["member"]) && empty($group["error"])) {
                if(in_array($key, $group["member"])) {
                    echo '<option value="'.$key.'"';
                    if(!$_temp_usr[$key]['active']) {
                        echo ' style="color:#999999;"';
                    } elseif($_temp_usr[$key]['admin']) {
                        echo ' style="color:#3F61BF;"';
                    }
                    echo '>'.trim($_temp_usr[$key]['name']. ' ('.$_temp_usr[$key]['login'].')')."</option>\n";
                    unset($_temp_usr[$key]);
                }
            }
        }
    }

?>
        </select></td>
                <td valign="top" style="padding-left:5px;padding-right:5px;"><img src="img/button/put_left.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onClick="moveAllOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access);selectAllOptions(document.editsitestructure.acat_access);"><br><img src="img/leer.gif" width="1" height="3"><br><img src="img/button/put_left_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"><br><img src="img/leer.gif" width="1" height="6"><br><img src="img/button/put_right_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_this']?>" onClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);"><br><img src="img/leer.gif" width="1" height="3"><br><img src="img/button/put_right.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_all']?>" onClick="moveAllOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers);"></td>
                <td><select name="acat_feusers" size="12" multiple="multiple" id="acat_feusers" class="width185" onDblClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);">
<?php

    // list all available fe_users
    if(count($_temp_usr)) {
        foreach($_temp_usr as $key => $value) {
            echo '<option value="'.$key.'"';
            if(!$_temp_usr[$key]['active']) {
                echo ' style="color:#999999"';
            } elseif($_temp_usr[$key]['admin']) {
                echo ' style="color:#3F61BF"';
            }
            echo '>'.trim($_temp_usr[$key]['name']. ' ('.$_temp_usr[$key]['login'].')')."</option>\n";
        }
    }

?>
        </select></td>
              </tr>
            </table></td>
          </tr>

     <!-- USER RIGHTS -->

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
              <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#D9DEE3" summary="">
               <tr>
                  <td><input name="group_active" type="checkbox" id="group_active" value="1"<?php is_checked(1, empty($group["active"]) ? 0 : $group["active"]); ?> /></td>
                  <td><label for="group_active"><?php echo $BL['be_ftptakeover_active'] ?></label>&nbsp;&nbsp;</td>
                </tr>
              </table></td>
              </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
            </tr>
            <tr>
              <td>
              &nbsp;
              <input name="group_id" type="hidden" id="group_id" value="<?php echo $group["id"] ?>" />
              <input name="group_aktion" type="hidden" id="group_aktion" value="1" />
              </td>
              <td>
              <input name="Submit" type="submit" class="button" value="<?php echo $sendbutton ?>" />
              &nbsp;&nbsp;
              <input name="donotsubmit" type="button" class="button" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=1';" /></td>
              </tr>
            </table></td>
          </tr>
          <tr bgcolor="#F0F2F4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
          </tr>
          </form>
      </table>
<?php
}
?>