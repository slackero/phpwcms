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

$phpwcms = array('SESSION_START' => true);

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(isset($_GET['del']) && intval($_GET['del'])) {

    $sql  = "UPDATE ".DB_PREPEND."phpwcms_guestbook SET guestbook_trashed=9 WHERE guestbook_cid=";
    $sql .= intval($_GET['cid'])." AND guestbook_id=".intval($_GET['del'])." LIMIT 1;";
    _dbQuery($sql, 'UPDATE');

}

if(isset($_GET['edit']) && intval($_GET['edit'])) {

    $gberror = '';

    if(isset($_POST['gbsubmit'])) {
        $gbemail    = clean_slweg(remove_unsecure_rptags($_POST['gbemail']));
        $gbname     = clean_slweg(remove_unsecure_rptags($_POST['gbname']));
        $gburl      = clean_slweg(remove_unsecure_rptags($_POST['gburl']));
        $gbmsg      = clean_slweg(remove_unsecure_rptags($_POST['gbmsg']));
        $gbshow     = intval($_POST['gbshow']);
        if($gbshow > 2) {
            $gbshow = 0;
        }
        $gbid       = intval($_POST['gbid']);
        $gbcid      = intval($_POST['gbcid']);

        if(!$gbemail || !$gbname) {
            $gberror = 'Old values recovered - no changes made';
        }

        if(!$gberror) {
            $sql  = "UPDATE ".DB_PREPEND."phpwcms_guestbook SET ";
            $sql .= "guestbook_msg="._dbEscape($gbmsg).", ";
            $sql .= "guestbook_name="._dbEscape($gbname).", ";
            $sql .= "guestbook_email="._dbEscape($gbemail).", ";
            $sql .= "guestbook_url="._dbEscape($gburl).", ";
            $sql .= "guestbook_show="._dbEscape($gbshow)." WHERE ";
            $sql .= "guestbook_cid="._dbEscape($gbcid)." AND guestbook_id="._dbEscape($gbid);
            _dbQuery($sql, 'UPDATE');
        }
    }

    $edit_ID = ' AND guestbook_id='.intval($_GET['edit']);
} else {
    $edit_ID = '';
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />
<title>phpwcms Backend Guestbook</title>
<style type="text/css">
body,td,th {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    color: #000000;
}
body {
    background-color: #F3F4F5;
    margin: 3px;
    width: 417px;
}
a {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    color: #000000;
}
a:visited {
    color: #000000;
}
a:active {
    color: #000000;
}
td {
    padding: 2px 4px;
}

input, textarea {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
}
</style>
</head>
<body>
<table width="100%" border="0" cellpadding="2" cellspacing="0" summary="">
<?php

$gbid = empty($_GET['cid']) ? 0 : intval($_GET['cid']);
$c = 0;

if($gbid) {
    $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_guestbook WHERE guestbook_cid=".$gbid;
    $sql .= $edit_ID." AND guestbook_trashed=0 ORDER BY guestbook_created DESC";

    $result = _dbQuery($sql);
}

if(isset($result[0]['guestbook_cid'])) {

    if(!$edit_ID) {

        foreach($result as $row) {

            $action_basis = get_token_get_string().'&amp;cid='.$row['guestbook_cid'].'&amp;';

?>
  <tr bgcolor="#E7E8EB">
    <td><strong><?php echo date('Y-m-d H:i', intval($row['guestbook_created'])).' | IP: <a href="http://www.dnsstuff.com/tools/ptr.ch?ip='.$row['guestbook_ip'].'" target="_blank">'.$row['guestbook_ip'].'</a> | <a href="http://www.dnsstuff.com/tools/whois.ch?ip='.$row['guestbook_ip'].'" target="_blank">WHOIS</a>' ?></strong></td>
    <td align="right"><a href="act_guestbook.php?<?php echo $action_basis.'edit='.$row['guestbook_id'] ?>" target="_self"><img src="../../img/button/edit_22x13.gif" width="22" height="13" border="0" alt="edit guestbook entry" /></a><img src="../../img/leer.gif" alt="" width="2" height="1" /><a href="act_guestbook.php?<?php echo $action_basis.'del='.$row['guestbook_id'] ?>" target="_self" onclick="return confirm('Do you really want to \ndelete this guestbook entry?');"><img src="../../img/button/trash_13x13_1.gif" alt="delete entry" width="13" height="13" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="2"><?php

    echo htmlspecialchars($row['guestbook_name']);
    echo ', ';
    echo '<a href="mailto:'.htmlspecialchars($row['guestbook_email']);
    echo '">'.htmlspecialchars($row['guestbook_email']).'</a>';
    if($row['guestbook_url']) {
        echo ' (<a href="'.htmlspecialchars($row['guestbook_url']).'" target="_blank" title="'.htmlspecialchars($row['guestbook_url']).'">URL</a>)';
    }

        if($row['guestbook_msg']) {
            echo '<br />'.nl2br(html($row['guestbook_msg']));
        }

    ?></td>
  </tr>
  <tr>
    <td colspan="2"><img src="../../img/leer.gif" alt="" width="1" height="1" /></td>
  </tr>
<?php
            $c++;
        }

    } else {

        foreach($result as $row) {
?>
  <tr bgcolor="#E7E8EB">
    <td>[<a href="act_guestbook.php?<?php echo get_token_get_string(); ?>&amp;cid=<?php echo $row['guestbook_cid'] ?>" target="_self">close</a>]<br /><img src="../../img/leer.gif" alt="" width="1" height="2" /></td>
    <td><strong><?php echo date('Y-m-d H:i', intval($row['guestbook_created'])).' | IP: <a href="http://www.dnsstuff.com/tools/ptr.ch?ip='.$row['guestbook_ip'].'" target="_blank">'.$row['guestbook_ip'].'</a> | <a href="http://www.dnsstuff.com/tools/whois.ch?ip='.$row['guestbook_ip'].'" target="_blank">WHOIS</a>' ?></strong></td>
  </tr>
  <tr><td colspan="2"><img src="../../img/leer.gif" alt="" width="1" height="1" /></td></tr>
  <?php

  if($gberror) {
  ?>  <tr>
  <td style="color:#FF3333;">error:&nbsp;</td>
  <td><strong style="color:#FF3333;"><?php echo $gberror ?></strong></td>
  </tr><?php

    }

    $token_name = generate_token_name();
    $token_value = generate_session_token($token_name);

  ?>
  <form name="editguestbook" action="act_guestbook.php?<?php echo get_token_get_string().'&amp;cid='.$row['guestbook_cid'].'&amp;edit='.$row['guestbook_id'] ?>" target="_self" method="post">

  <tr>
      <td>name:&nbsp;</td>
      <td><input name="gbname" type="text" id="gbname" class="width350" value="<?php echo htmlspecialchars($row['guestbook_name']) ?>" /></td>
  </tr>
  <tr>
      <td>email:&nbsp;</td>
      <td><input name="gbemail" type="text" id="gbemail" class="width350" value="<?php echo htmlspecialchars($row['guestbook_email']) ?>" /></td>
  </tr>
  <tr>
      <td>URL:&nbsp;</td>
      <td><input name="gburl" type="text" id="gburl" class="width350" value="<?php echo htmlspecialchars($row['guestbook_url']) ?>" /></td>
  </tr>
  <tr>
      <td valign="top">msg:<img src="../../img/leer.gif" alt="" width="1" height="15" />&nbsp;</td>
      <td><textarea name="gbmsg" rows="10" id="gbmsg" class="width350"><?php echo htmlspecialchars($row['guestbook_msg']) ?></textarea></td>
  </tr>
  <tr>
    <td valign="top" class="v10">display:<img src="../../img/leer.gif" alt="" width="1" height="15" />&nbsp;</td>
    <td><input name="gbshow" type="radio" value="0"<?php is_checked(0, intval($row['guestbook_show']), 1); ?> />
    show email&nbsp;&nbsp;    <input name="gbshow" type="radio" value="1"<?php is_checked(1, intval($row['guestbook_show']), 1); ?> />hide email<br />
     <input type="radio" name="gbshow" value="2"<?php is_checked(2, intval($row['guestbook_show']), 1); ?> />show email as &quot;info at mail dot com&quot;</td>
  </tr>
  <tr>
  <td><img src="../../img/leer.gif" alt="" width="1" height="30" /><input name="gbcid" type="hidden" value="<?php echo intval($row['guestbook_cid']) ?>" /><input name="gbid" type="hidden" value="<?php echo intval($row['guestbook_id']) ?>" /></td>
      <td valign="bottom">
        <input type="hidden" name="csrf_token_name" value="<?php echo $token_name; ?>" />
        <input type="hidden" name="csrf_token_value" value="<?php echo $token_value; ?>" />
        <input name="gbsubmit" type="submit" id="gbsubmit" value="submit changes" />
        <input name="gbcancel" type="button" id="gbcancel" value="close" onclick="location.href='act_guestbook.php?<?php echo get_token_get_string(); ?>&amp;cid=<?php echo $row['guestbook_cid']; ?>';" />
    </td>
  </tr>
  </form>
<?php
            $c++;
        }
    }
}

// if no guestbook entry available
if(!$c): ?>
    <tr>
        <td colspan="2">No guestbook entry available</td>
    </tr>
<?php

endif;

?>
</table>
</body>
</html>