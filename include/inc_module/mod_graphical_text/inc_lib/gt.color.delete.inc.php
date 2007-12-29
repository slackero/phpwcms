<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



$color_id = intval($_GET["color_id"]);

if ($color_id)
{
	$query = "DELETE FROM ".DB_PREPEND."phpwcms_fonts_colors WHERE color_id = ".$color_id." LIMIT 1";
	
	$results = mysql_query($query, $db) or die ("Error in query:$query");
}


header("Location: phpwcms.php?do=modules&p=2&s=colors");
exit();

?>