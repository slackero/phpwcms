<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$style_id = intval($_GET["style_id"]);

if ($style_id)
{
	$query = "DELETE FROM ".DB_PREPEND."phpwcms_fonts_styles WHERE style_id = ".$style_id." LIMIT 1";
	
	$results = mysql_query($query, $db) or die ("Error in query:$query");
}


header("Location: phpwcms.php?do=modules&p=2&s=styles");
exit();

?>