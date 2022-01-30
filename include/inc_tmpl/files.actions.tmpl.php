<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 *
 * @author Marus Köhl <info@pagewerkstatt.ch>
 * @link http://www.pagewerkstatt.ch
 *
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

$file_action = array(
    'file_dir' => empty($_POST['file_dir']) ? 0 : intval($_POST['file_dir']),
    'mark' => empty($_POST['ftp_mark']) || !is_array($_POST['ftp_mark']) ? array() : $_POST['ftp_mark'],
    'newdir' => empty($_POST['file_newdir']) ? 0 : intval($_POST['file_newdir'])
);

//Get post variables
if(isset($_POST['file_action'])) {
    $file_action['action']  = intval($_POST['file_action']);
    $file_action_msg        = $BL['file_actions_msg_error'];
} else {
    $file_action['action']  = 0;
    $file_action_msg        = '';
}

if($file_action['action'] === 1 && $file_action["mark"]) {
    if(is_array($file_action["mark"]) && count($file_action["mark"])) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql = 'UPDATE '.DB_PREPEND.'phpwcms_file SET f_trash=1 WHERE f_id='.$key;
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_delete'];
    }
} elseif($file_action['action'] === 2 && $file_action["mark"]) {
    $newdir = intval($file_action["newdir"]);
    if(is_array($file_action["mark"]) && count($file_action["mark"])) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_file SET f_pid='.$newdir.' WHERE f_id='.$key;
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_move'];
    }
} elseif($file_action['action'] === 3 && $file_action["mark"]) {
    $file_action["aktiv"]   = empty($_POST["file_aktiv"]) ? 0 : 1;
    $file_action["public"]  = empty($_POST["file_public"]) ? 0 : 1;
    if(is_array($file_action["mark"]) && count($file_action["mark"])) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_file SET ';
                $sql .= "f_aktiv= " . $file_action["aktiv"] . ", ";
                $sql .= "f_public= " . $file_action["public"] . " ";
                $sql .= "WHERE f_id='".$key."'";
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_status'];
    }
} elseif($file_action['action'] === 4 && $file_action["mark"]) {
    $file_action["file_user"]   = intval($_POST["file_user"]);
    if(is_array($file_action["mark"]) && count($file_action["mark"]) && $file_action["file_user"] > 0) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_file SET ';
                $sql .= "f_pid=0, ";
                $sql .= "f_uid= " . $file_action["file_user"] . " ";
                $sql .= "WHERE f_id='".$key."'";
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_user'];
    }
}


?>
<script type=text/javascript>
function showAction() {
    divid = document.filetakeover.file_action.value;
    if (divid === '0') {
        document.getElementById("div_button").style.display='none';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '1') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '2') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='block';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '3') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='block';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '4') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='block';
    }
}
</script>

<h1 class="title"><?php echo $BL['be_subnav_file_actions'] ?></h1>
<?php if($file_action_msg) { echo '<p><b>'.$file_action_msg.'</b></p>'; } ?>

<form action="phpwcms.php?do=files&amp;p=4" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 10px 8px" name="folderform" id="folderform">
    <strong><?php echo $BL['file_actions_step1'] ?></strong><br />
    <select name="file_dir" id="file_dir" class="v11 width400" onchange="submit();">
        <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
        <?php //get folders for user
            dir_menu(0, $file_action["file_dir"], "+", $_SESSION["wcs_user_id"], "+");
        ?>
    </select>
</form>

&nbsp;&nbsp;<strong><?php echo $BL['file_actions_step2'] ?><strong>
<form action="phpwcms.php?do=files&amp;p=4" method="post" name="filetakeover" id="filetakeover" style="margin-top:3px">
    <input name="file_dir" type="hidden" value="<?php echo $file_action["file_dir"] ?>" />

<table width="538" border="0" cellpadding="0" cellspacing="0" summary="" style="margin-bottom:10px">
    <tr bgcolor="#92A1AF"><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
    </tr>
        <tr bgcolor="#D9DEE3">
            <td width="35" align="center" class="v09"><?php echo $BL['be_ftptakeover_mark'] ?></td>
            <td width="1" bgcolor="#F2F3F5"><img src="img/leer.gif" alt="" width="1" height="14" /></td>
            <td width="21"><img src="img/leer.gif" alt="" width="21" height="1" /></td>
            <td width="420" class="v09"><?php echo $BL['be_ftptakeover_available'] ?></td>
            <td width="1" bgcolor="#F2F3F5"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td width="50" align="right" class="v09"><?php echo $BL['be_ftptakeover_status'] ?>&nbsp;&nbsp;</td>
        </tr>
        <tr bgcolor="#92A1AF"><td colspan="6" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
    </tr>
<?php
//Browse files in selected folder
$fx = 0;
$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=" . $file_action["file_dir"] .
            " AND f_trash=0 AND f_kid = 1 AND f_uid = " . $_SESSION["wcs_user_id"] . " ORDER BY f_name";
$file_result = _dbQuery($file_sql);
if(isset($file_result[0]['f_id'])) {

    foreach($file_result as $file_row) {
        $fxb = ($fx % 2) ? " bgColor=\"#F9FAFB\"" : "";
        // there is a big problem with special chars on Mac OS X and seems Windows too
        if(PHPWCMS_CHARSET != 'utf-8' && phpwcms_seems_utf8($file_row["f_name"])) {
            $filename = str_replace('?', '', utf8_decode($file_row["f_name"]));
        } else {
            $filename = $file_row["f_name"];
        }
        $filename = html($filename);
?>
    <tr<?php echo $fxb ?>>
        <td align="center"><input name="ftp_mark[<?php echo $file_row["f_id"] ?>]" type="checkbox" id="ftp_mark_<?php echo $file_row["f_id"] ?>" value="1" class="ftp_mark" /></td>
        <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="17" /></td>
        <td align="center"><img src="img/icons/small_<?php echo extimg($file_row["f_ext"]) ?>" alt="" width="13" height="11" /></td>
        <td class="v10"><?php echo $filename ?></td>
        <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
        <td align="right" class="v10">
            <?php
            //Icons Public/Non-Public
            echo "<img src=\"img/button/aktiv_12x13_".$file_row["f_aktiv"].".gif\" border=\"0\">";
            echo "<img src=\"img/button/public_12x13_".$file_row["f_public"].".gif\" border=\"0\">";
             ?>&nbsp;
            <input name="ftp_fileid[<?php echo $fx ?>]" type="hidden" value="<?php echo $file_row["f_id"] ?>" />
        </td>
    </tr>
<?php
        $fx++;
    }
}
if(!$fx) {
?>
    <tr>
        <td colspan="5" class="dir">&nbsp;<?php echo $BL['file_actions_no'] ?></td>
        <td><img src="img/leer.gif" alt="" width="1" height="17" /></td>
    </tr>
<?php } else { ?>
    <tr bgcolor="#92A1AF"><td colspan="6" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#EAEDF0">
        <td align="center" class="subnavactive"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
        <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="17" /></td>
        <td>&nbsp;</td>
        <td class="v10">
            <?php echo $BL['be_ftptakeover_all'] ?>
            <button id="delete-selected-files" style="display:none;margin-left:3em;" class="v10"><?php echo $BL['be_delete_selected_files'] ?></button>
        </td>
        <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
        <td align="right" class="v10"></td>
    </tr>
    <tr bgcolor="#92A1AF"><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php } ?>
    <tr bgcolor="#D9DEE3">
        <td><img src="img/leer.gif" alt="" width="35" height="1" /></td>
        <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
        <td><img src="img/leer.gif" alt="" width="21" height="1" /></td>
        <td><img src="img/leer.gif" alt="" width="400" height="1" /></td>
        <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
        <td><img src="img/leer.gif" alt="" width="50" height="1" /></td>
    </tr>
</table>
<?php
//if files available
if($fx) {
?>
<div style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
    <strong style="display:block;margin-bottom:3px"><?php echo $BL['file_actions_step3'] ?></strong>
        <div id="div_folder" style="display: none;">
        <table>
            <tr>
                <td>&nbsp;</td>
                <td><?php echo $BL['file_actions_bemfolder']; ?></td>
            </tr>
            <tr>
                <td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
                <td class="v10">
                <select name="file_newdir" id="file_newdir" class="v11 width400">
                    <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                    <?php dir_menu(0, 0, "+", $_SESSION["wcs_user_id"], "+"); ?>
                </select></td>
            </tr>
        </table>
        </div>
        <div id="div_status" style="display: none;">
        <table>
            <tr>
            <td align="right" class="v09" valign="top"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
            <td>
            <table border="0" cellpadding="1" cellspacing="0" bgcolor="#E6EAED" summary="">
                <tr>
                    <td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1" /></td>
                    <td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;</td>
                    <td><input name="file_public" type="checkbox" id="file_public" value="1" /></td>
                    <td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong>&nbsp;&nbsp;</td>
                </tr>
            </table>
            </td>
        </tr>
        </table>
        </div>
        <div id="div_user" style="display: none;">
        <table>
            <tr>
                <td align="right" class="v09" valign="top"><?php echo $BL["login_username"] ?>:&nbsp;</td>
                <td class="v10">
                <select name="file_user" id="file_user" class="v11 width400">
<?php
    $sql = "SELECT usr_id, usr_name FROM ".DB_PREPEND."phpwcms_user WHERE usr_aktiv=1 AND usr_id !=".intval($_SESSION["wcs_user_id"])." ORDER BY usr_name";
    $result = _dbQuery($sql);
    if(isset($result[0]['usr_id'])) {
        foreach($result as $row) {
            echo "<option value='".$row['usr_id']."'>".html($row['usr_name'])."</option>\n";
        }
    }
?>
                </select><br />
                <?php echo $BL['file_actions_bemuser']; ?>
                </td>
            </tr>
         </table>
         </div>

    <table border="0" cellpadding="0" cellspacing="0" summary="" style="margin-top:3px">

        <tr>
            <td>
            <select name="file_action" id="file_action" class="v12" onChange="showAction()">
                <option value="0">- <?php echo $BL['file_actions_pdl_empty'] ?> -</option>
                <option value="1"><?php echo $BL['file_actions_pdl_delete'] ?></option>
                <option value="2"><?php echo $BL['file_actions_pdl_move'] ?></option>
                <option value="3"><?php echo $BL['file_actions_pdl_status'] ?></option>
                <option value="4"><?php echo $BL['file_actions_pdl_user'] ?></option>
            </select></td>

            <td>&nbsp;</td>

            <td><div id="div_button" style="display: none;"><input name="Submit" type="submit" class="button" value="<?php echo $BL['file_actions_button'] ?>" /></div></td>
        </tr>

    </table>
</div>

<?php } ?>
</form>
<?php if($fx) { ?>
<script type="text/javascript">
$('toggle').addEvent('change',function(e) {
    var toggle = $('toggle').checked;
    $$('#filetakeover input.ftp_mark').each(function(check) {
        check.checked = toggle;
    });
});
</script>
<?php } ?>
