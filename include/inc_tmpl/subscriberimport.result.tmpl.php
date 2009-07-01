<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
	
		echo '<p>'.sprintf('%0'.strlen(strval($c1*10)).'s', $c).':&nbsp;'.html_specialchars($row).'</p>'.LF;
	
	}
	echo '</div>';

}

?>	
<form action="phpwcms.php?do=messages&amp;p=4" method="post" style="text-align:center;margin-top:12px;"><input name="close" type="submit" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" /></form>
</div>
