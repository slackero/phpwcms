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

// newsletter subscription

echo '<div class="title" style="margin-bottom:10px">'.$BL['be_newsletter_title'].'</div>';

if(!empty($_GET["s"]) && isset($_GET['active'])) {

    $sql  = "UPDATE ".DB_PREPEND."phpwcms_subscription SET ";
    $sql .= "subscription_active=".(intval($_GET["active"]) ? 1 : 0)." ";
    $sql .= "WHERE subscription_id=".intval($_GET["s"]);
    @_dbQuery($sql, 'UPDATE');
}

if(isset($_GET["s"]) && isset($_GET['edit'])) {
    include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscription.form.tmpl.php';
}

?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="newsletter susbcription listing">
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<?php
// loop listing available subscriptions
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name";
$result = _dbQuery($sql);
if(isset($result[0]['subscription_id'])) {
    $row_count = 0;
    foreach($result as $row) {

        echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).">\n<td width=\"25\" style=\"padding:1px 0 1px 0;\">";

        echo '<img src="img/symbole/newsletter_susbcription.gif" width="25" height="16" alt="" /></td>'.LF;

        echo '<td width="473" class="dir">';
        echo '<a href="phpwcms.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;edit=1">';
        echo '<strong>'.html($row["subscription_name"])."</strong></a></td>\n";

        echo '<td align="right" class="nowrap button_td">';

        echo '<a href="phpwcms.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;edit=1">';
        echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';

        echo '<a href="phpwcms.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;active=';
        echo ($row['subscription_active']) ? '0' : '1';
        echo '">';
        echo '<img src="img/button/aktiv_12x13_'.$row['subscription_active'].'.gif" border="0" alt="" /></a>';

        echo "</td>\n</tr>\n";

        $row_count++;
    }
} // end listing

?>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
    <tr><td colspan="3"><form action="phpwcms.php?do=messages&amp;p=2&amp;s=0&amp;edit=1" method="post"><input type="submit" value="<?php echo $BL['be_newsletter_new'] ?>" class="button" title="<?php echo $BL['be_newsletter_add'] ?>"></form></td></tr>
</table>
