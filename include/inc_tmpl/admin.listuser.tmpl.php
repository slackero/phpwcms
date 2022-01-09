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


// create paginating for users
if(isset($_GET['c'])) {
    $_SESSION['list_user_count'] = (trim($_GET['c']) == 'all') ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['list_user_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
    $_SESSION['list_user_count'] = 25;
}

$_userInfo = array();


// get filter and paginating form values
if(isset($_POST['do_pagination'])) {

    $_SESSION['list_admin']     = empty($_POST['showadmin']) ? 0 : 1;
    $_SESSION['list_befe']      = empty($_POST['showbefe']) ? 0 : 1;
    $_SESSION['list_norm']      = empty($_POST['shownorm']) ? 0 : 1;
    $_SESSION['list_fe']        = empty($_POST['showfe']) ? 0 : 1;

    $_SESSION['list_user_page'] = intval($_POST['page']);
    $_SESSION['filter_results'] = clean_slweg($_POST['filter']);
    if(empty($_SESSION['filter_results'])) {
        unset($_SESSION['filter_results']);
    } else {
        $_SESSION['filter_results'] = convertStringToArray($_SESSION['filter_results'], ' ');
    }
}

if(empty($_SESSION['list_user_page'])) {
    $_SESSION['list_user_page'] = 1;
}

$_userInfo['list_admin']    = isset($_SESSION['list_admin']) ? $_SESSION['list_admin'] : 0;
$_userInfo['list_befe']     = isset($_SESSION['list_befe']) ? $_SESSION['list_befe'] : 0;
$_userInfo['list_norm']     = isset($_SESSION['list_norm']) ? $_SESSION['list_norm'] : 0;
$_userInfo['list_fe']       = isset($_SESSION['list_fe']) ? $_SESSION['list_fe'] : 0;

$_userInfo['list']          = array();
// if admin user should be listed
$_userInfo['where_query']    = ' WHERE usr_aktiv != 9';
if($_userInfo['list_admin']) {
$_userInfo['where_query']   .= ' AND usr_admin=1';
}
if($_userInfo['list_befe']) {
    $_userInfo['list'][]    = ' usr_fe=2 ';
}
if($_userInfo['list_norm']) {
    $_userInfo['list'][]    = ' usr_fe=1 ';
}
if($_userInfo['list_fe']) {
    $_userInfo['list'][]    = ' usr_fe=0 ';
}
$_userInfo['list']          = trim(implode('OR', $_userInfo['list']));
if($_userInfo['list']) {
    $_userInfo['where_query'] .= ' AND ('.$_userInfo['list'].')';
}
if(isset($_SESSION['filter_results']) && count($_SESSION['filter_results'])) {

    $_userInfo['filter_array'] = array();

    foreach($_SESSION['filter_results'] as $_userInfo['filter']) {
        //usr_name, usr_login, usr_email
        $_userInfo['filter_array'][] = "CONCAT(usr_name, usr_login, usr_email) LIKE '%".aporeplace($_userInfo['filter'])."%'";
    }
    if(count($_userInfo['filter_array'])) {

        $_userInfo['where_query'] .= ' AND ('.implode('OR', $_userInfo['filter_array']).')';

    }

}

// paginating values
$_userInfo['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_user ".$_userInfo['where_query'], 'COUNT');
$_userInfo['pages_total'] = ceil($_userInfo['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['list_user_page'] > $_userInfo['pages_total']) {
    $_SESSION['list_user_page'] = empty($_userInfo['pages_total']) ? 1 : $_userInfo['pages_total'];
}

?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr><td colspan="3" class="title"><?php echo $BL['be_admin_usr_ltitle'] ?></td></tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
        <tr><td colspan="3"><form action="phpwcms.php?do=admin" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
        <tr>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>

                    <td><input type="checkbox" name="showadmin" id="showadmin" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_admin'], 1) ?> /></td>
                    <td><label for="showadmin"><img src="img/usricon/usr_admin.gif" alt="" /></label></td>
                    <td><input type="checkbox" name="showbefe" id="showbefe" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_befe'], 1) ?> /></td>
                    <td><label for="showbefe"><img src="img/usricon/usr_befe.gif" alt="" /></label></td>
                    <td><input type="checkbox" name="shownorm" id="shownorm" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_norm'], 1) ?> /></td>
                    <td><label for="shownorm"><img src="img/usricon/usr_norm.gif" alt="" /></label></td>
                    <td><input type="checkbox" name="showfe" id="showfe" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_fe'], 1) ?> /></td>
                    <td><label for="showfe"><img src="img/usricon/usr_16.gif" alt="" /></label></td>

<?php
if($_userInfo['pages_total'] > 1) {

    echo '<td class="chatlist">|&nbsp;</td>';
    echo '<td>';
    if($_SESSION['list_user_page'] > 1) {
        echo '<a href="phpwcms.php?do=admin&amp;page='.($_SESSION['list_user_page']-1).'">';
        echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
    } else {
        echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
    }
    echo '</td>';
    echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['list_user_page'];
    echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
    echo '<td class="chatlist">/'.$_userInfo['pages_total'].'&nbsp;</td>';
    echo '<td>';
    if($_SESSION['list_user_page'] < $_userInfo['pages_total']) {
        echo '<a href="phpwcms.php?do=admin&amp;page='.($_SESSION['list_user_page']+1).'">';
        echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" /></a>';
    } else {
        echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" class="inactive" />';
    }
    echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

    echo '<td class="chatlist">|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>

    <td><input type="search" name="filter" id="filter" size="10" value="<?php

    if(isset($_SESSION['filter_results']) && count($_SESSION['filter_results']) ) {
        echo html(implode(' ', $_SESSION['filter_results']));
    }

    ?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
    <td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" alt="" /></td>
            </table></td>
            <td class="chatlist" align="right">
                <a href="phpwcms.php?do=admin&amp;c=10">10</a>
                <a href="phpwcms.php?do=admin&amp;c=25">25</a>
                <a href="phpwcms.php?do=admin&amp;c=50">50</a>
                <a href="phpwcms.php?do=admin&amp;c=100">100</a>
                <a href="phpwcms.php?do=admin&amp;c=250">250</a>
                <a href="phpwcms.php?do=admin&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
            </td>
        </tr>
    </table>
    </form></td></tr>

        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

        <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <?php
    $bg_color1 = "#FFFFFF";
    $bg_color2 = "#F3F5F8";
    $zaehler = 0;
    if(!isset($new_user_id)) {
        $new_user_id = 0;
    }
    // Generate list of all users
    $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_user ".$_userInfo['where_query'].' ';
    $sql .= "ORDER BY usr_aktiv DESC, usr_fe DESC, usr_admin DESC, usr_name ASC ";
    $sql .= "LIMIT ".(($_SESSION['list_user_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
    $result = _dbQuery($sql);
    if(isset($result[0]['usr_id'])) {
        foreach($result as $userlist) {
            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            if($userlist["usr_id"] == $new_user_id) {
                $bg_color = "#FFCC00";
            }
            $goto = "phpwcms.php?do=admin&amp;s=2&amp;u=".$userlist["usr_id"];
?>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr bgcolor="<?php echo  $bg_color ?>" onmouseover="bgColor='#DBFF48'" onmouseout="bgColor='<?php echo  $bg_color ?>'">
          <td width="19" align="center"><img src="img/usricon/usr_<?php

            if($userlist["usr_aktiv"] == 1) {
                if(!$userlist["usr_admin"]) {
                    switch($userlist["usr_fe"]) {
                        case 0: echo '16'; break;
                        case 1: echo 'norm'; break;
                        case 2: echo 'befe'; break;
                    }
                } else {
                    echo "admin";
                }
            } else {
                echo "inaktiv";
            }

          ?>.gif" alt="" width="19" height="16" border="0"></td>
          <td width="458" <?php if($userlist["usr_aktiv"]==1) {echo "class=\"dir\"";} else {echo "class=\"inaktiv\"";} ?>><a href="<?php echo $goto ?>"><?php

            if($userlist["usr_name"]) {
                $userlist["usr_name"] = html($userlist["usr_name"]." (".$userlist["usr_login"].")");
            } else {
                $userlist["usr_name"] = html($userlist["usr_login"]);
            }
            echo $userlist["usr_name"];

          ?></a></td>
          <td width="61" align="right"><a href="include/inc_act/act_user.php?aktiv=<?php
            echo $userlist["usr_id"].":";
            if($userlist["usr_aktiv"]) {
                echo "0";
            } else {
                echo "1";
            }
            ?>"><img src="img/button/<?php
            if(!$userlist["usr_aktiv"]) echo "in";
            ?>aktiv_mini.gif" alt="" width="14" height="15" border="0"></a><a href="<?php echo $goto ?>"><img src="img/button/edit.gif" alt="" width="24" height="15" border="0" title="<?php echo $BL['be_admin_usr_editusr'].": ".html($userlist["usr_login"]) ?>"></a><a href="include/inc_act/act_user.php?del=<?php
          echo urlencode($userlist["usr_id"].":".$userlist["usr_email"]);
          ?>" onclick="return confirm('Delete user <?php echo js_singlequote($userlist["usr_name"]) ?>');"><img src="img/button/del_message_final.gif" alt="" width="22" height="15" border="0" title="<?php echo $BL['be_admin_usr_ldel']." ".html($userlist["usr_login"]) ?>"></a></td>
        </tr>
        <?php
            $zaehler++;
        }
    } //Ende Schleife Anzeige User

        if($zaehler) {
            echo '<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>';
            echo '<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>';
        }

        ?>
        <tr>
          <td><img src="img/leer.gif" alt="" width="19" height="5"></td>
          <td><img src="img/leer.gif" alt="" width="458" height="1"></td>
          <td><img src="img/leer.gif" alt="" width="61" height="1"></td>
        </tr>
        <tr>
          <td colspan="3"><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
              <tr>
                <td><form action="phpwcms.php?do=admin&amp;s=1" method="post"><input type="submit" value="<?php echo $BL['be_admin_usr_create'] ?>" class="button" title="<?php echo $BL['be_admin_usr_create'] ?>"></form></td>
                <td>&nbsp;&nbsp;</td>
                <td align="right"><img src="img/symbols/userlegende1.gif" alt="" width="225" height="13"></td>
              </tr>
            </table></td>
        </tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
</table>