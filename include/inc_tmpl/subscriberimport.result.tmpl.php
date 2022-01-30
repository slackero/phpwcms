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



?>
<div style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:2px 10px 15px 10px">
<?php

echo '<p><strong>'.$BL['be_newsletter_importtitle'] .':</strong><br />';

if($c) $c--;

if(isset($_userInfo['nonImported'])) {
	$c1 = count($_userInfo['nonImported']);
	$c = $c - $c1;
}
echo $c.' '.$BL['be_newsletter_addressesadded'].'</p>';


if(!empty($c1)) {

	echo '<p class="chatlist">'.$BL['be_newsletter_importerror'].'&nbsp;</p>'.LF;
	echo '<div id="codebox">';

	foreach($_userInfo['nonImported'] as $c => $row) {

		echo '<p>'.sprintf('%0'.strlen(strval($c1*10)).'s', $c).':&nbsp;'.html($row).'</p>'.LF;

	}
	echo '</div>';

}

?>
<form action="phpwcms.php?do=messages&amp;p=4" method="post" style="text-align:center;margin-top:12px;"><input name="close" type="submit" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" /></form>
</div>
