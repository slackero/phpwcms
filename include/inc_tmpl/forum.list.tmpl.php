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

$forum["text"] = '';
$forum["title"] = '';
$forum['id'] = 0;
$row_count = 0;

if(!isset($_GET["s"])) {
// check if subscription should be edited
?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="3" class="title"><?php echo $BL['be_subnav_msg_forum'] ?></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<?php
// loop listing available subscriptions
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ORDER BY forum_changed DESC";
$result = _dbQuery($sql);
if(isset($result[0]['forum_id'])) {
    foreach($result as $row) {
        echo "<tr".( ($row_count % 2) ? " bgcolor=\"#F3F5F8\"" : "" ).">\n<td width=\"25\">";
        echo '<img src="img/symbols/icon_minicategory1.gif" width="14" height="14" alt="" style="margin:4px 4px 4px 5px;"></td>'."\n";
        echo '<td width="473" class="dir"><a href="phpwcms.php?';
        $tempQuery = build_QueryString('&amp;', 'do=messages', 'p=6', 's='.$row["forum_id"]);
        echo $tempQuery;
        echo '"><strong>'.html($row["forum_title"])."</strong></a></td>\n".'<td width="40" align="right">';
        echo '<a href="phpwcms.php?';
        echo $tempQuery;
        echo '"><img src="img/button/edit_22x11.gif" width="22" height="11" border="0"></a>';
        echo '<img src="img/leer.gif" width="2" height="1">';
        echo '<img src="img/button/del_11x11.gif" width="11" height="11">';
        echo '<img src="img/leer.gif" width="2" height="1">'."</td>\n</tr>\n";
        $row_count++;
    }
} // end listing

?>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
    <tr><td colspan="3"><form action="phpwcms.php?do=messages&amp;p=6&amp;s=0" method="post"><input name="addforum" type="submit" class="button" value="<?php echo $BL['be_forum_add'] ?>"></form></td></tr>
</table>
<?php

} else {

// should the edit forum dialog
    $forum["id"] = (!empty($_GET["s"])) ? intval($_GET["s"]) : 0;

    if(isset($_POST["forum_id"])) {
    // read the create or edit forum form data
        $forum["id"] = intval($_POST["forum_id"]);
        $forum["title"] = clean_slweg($_POST["forum_title"]);
        if(!$forum["title"]) {
            $forum["title"] = "Forum ".date('Y/m/d H:i');
        }
        $forum["text"] = clean_slweg($_POST["forum_text"]);

        $sqla  = "forum_title = '" . aporeplace($forum["title"]) ."', ";
        $sqla .= "forum_text  = '" . aporeplace($forum["text"])  ."'";

        if($forum["id"]) {

            $query_mode = 'UPDATE';
            $sql  = "UPDATE ".DB_PREPEND."phpwcms_forum SET " . $sqla;
            $sql .= " WHERE forum_entry=0 AND forum_id=".$forum["id"];
            $sql .= " LIMIT 1";

        } else {

            $query_mode = 'INSERT';
            $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_forum SET ";
            $sql .= "forum_entry='0', ";
            $sql .= "forum_uid='" . $_SESSION["wcs_user_id"] . "', ";
            $sql .= "forum_created = '".time()."', ";
            $sql .= $sqla;

        }
        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);
        if($query_mode === 'INSERT' && isset($result['INSERT_ID'])) {
            $forum["id"] = $result['INSERT_ID'];
        }
        if($forum["id"]) {
            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&'.build_QueryString('&', 'do=messages', 'p=6', 's='.$forum["id"]));
        }
    }

    if($forum["id"]) {
    // read the given subscription datas from db
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_id=".$forum["id"]." LIMIT 1";
        $result = _dbQuery($sql);

        if(isset($result[0]['forum_id'])) {

            $forum["id"] = $result[0]["forum_id"];
            $forum["title"] = html($result[0]["forum_title"]);
            $forum["text"] = html($result[0]["forum_text"]);

        }
    }

    // show form
?>
<form action="phpwcms.php?<?php echo build_QueryString('&amp;', 'do=messages', 'p=6', 's='.$forum["id"]) ?>" method="post" name="forums" target="_self">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
      <td colspan="2" class="title"><?php echo  $BL['be_forum_titleedit'].": ".( $forum["id"] ? $forum["title"] : $BL['be_newsletter_new']); ?></td>
    </tr>
    <tr>
        <td width="73"><img src="img/leer.gif" alt="" width="73" height="6"></td>
        <td width="465"><img src="img/leer.gif" alt="" width="1" height="1"></td>
    </tr>
    <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="12"></td></tr>
    <tr bgcolor="#E6EAED">
        <td align="right" class="chatlist">&nbsp;<?php echo  $BL['be_forum_title'] ?>:&nbsp;</td>
        <td><input name="forum_title" type="text" class="f11b" id="forum_title" style="width:440px" value="<?php echo $forum["title"] ?>" size="50" maxlength="250"></td>
    </tr>
    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
    <tr bgcolor="#E6EAED">
        <td align="right" valign="top" bgcolor="#E6EAED" class="chatlist"><img src="img/leer.gif" alt="" width="5" height="16"><?php echo  $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td><textarea name="forum_text" cols="35" rows="6" class="width440" id="forum_text"><?php echo $forum["text"]; ?></textarea></td>
    </tr>
    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
    <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
    <tr>
        <td>&nbsp;<input name="forum_id" type="hidden" value="<?php echo $forum["id"] ?>"></td>
        <td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_save_btn'] ?>">&nbsp;&nbsp;<input type="button" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=messages&p=6';"></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
</table>
</form>
<?php
}

?>