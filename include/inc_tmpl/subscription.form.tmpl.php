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

$subscription["id"] = intval($_GET["s"]);

if(isset($_POST["subscription_id"])) {

    // read the create or edit subscription form data
    $subscription["id"]         = intval($_POST["subscription_id"]);
    $subscription["name"]       = clean_slweg($_POST["subscription_name"]);
    if(empty($subscription["name"])) {
        $subscription["name"] = "subscription_".generic_string(3);
    }
    $subscription["info"]       = clean_slweg($_POST["subscription_info"]);

    if($subscription["id"]) {
        $query_mode = 'UPDATE';
        $sql =  "UPDATE ".DB_PREPEND."phpwcms_subscription SET ".
                "subscription_name='".aporeplace($subscription["name"])."', ".
                "subscription_info='".aporeplace($subscription["info"])."' ".
                "WHERE subscription_id=".$subscription["id"];
    } else {
        $query_mode = 'INSERT';
        $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_subscription (".
                "subscription_name, subscription_info) VALUES ('".
                aporeplace($subscription["name"])."', '".
                aporeplace($subscription["info"])."')";
    }
    // update or insert data entry
    $result = _dbQuery($sql, $query_mode);

    if($query_mode === 'INSERT' && isset($result['INSERT_ID'])) {
        $subscription["id"] = $result['INSERT_ID'];
    }

    if($subscription["id"]) {
        headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=messages&p=2&s='.$subscription["id"]);
    }
}

if($subscription["id"]) {
// read the given subscription datas from db
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_subscription WHERE subscription_id=".$subscription["id"]." LIMIT 1";
    $result = _dbQuery($sql);
    if(isset($result[0]['subscription_id'])) {
        $subscription["id"] = $result[0]["subscription_id"];
        $subscription["name"] = html($result[0]["subscription_name"]);
        $subscription["info"] = html($result[0]["subscription_info"]);
    }
}

    // show form
?>
<form action="phpwcms.php?do=messages&amp;p=2&amp;s=<?php echo $subscription["id"] ?>&amp;edit=1" method="post" name="subscriptions" id="subscriptions" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 10px 15px 10px">
    <table border="0" cellpadding="0" cellspacing="0" summary="newsletter subscription form">

        <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_newsletter_name'] ?>:&nbsp;</td>
            <td><input name="subscription_name" type="text" class="f11b" id="subscription_name" style="width:440px" value="<?php echo  empty($subscription["name"]) ? '' : html($subscription["name"]) ?>" size="50" maxlength="250" /></td>
        </tr>

        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
        <tr>
            <td align="right" valign="top" class="chatlist" style="padding-top:3px;"><?php echo $BL['be_newsletter_info'] ?>:&nbsp;</td>
            <td><textarea name="subscription_info" cols="35" rows="6" class="width440 autosize" id="subscription_info"><?php echo empty($subscription["info"]) ? '' : html($subscription["info"]); ?></textarea></td>
        </tr>

        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

        <tr>
            <td>&nbsp;<input name="subscription_id" type="hidden" value="<?php echo $subscription["id"] ?>" /></td>
            <td>
                <input name="Submit" type="submit" class="button" value="<?php echo $BL['be_newsletter_button_save'] ?>" />
                &nbsp;&nbsp;
                <input type="button" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=messages&amp;p=2';" /></td>
        </tr>

    </table>
</form>