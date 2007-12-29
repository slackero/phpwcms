<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


function get_submenustatus($m)
{
	$gt_mod_s = empty($_GET["s"]) ? false : $_GET["s"];
	if ($m == $gt_mod_s) {
		return "class=\"active\"";
	} else {
		return "";
	}
}


$BE['HEADER']['gt_mod'] = '
<style type="text/css">
<!--
	.submenu
	{
		padding: 5px;
		background: #FEE3CF;
		color: #000000;
		border: 1px solid #888888;
	}
	
	.submenu a, .submenu a:link, .submenu a:visited
	{
		font-weight: normal;
		text-decoration: none;
		color: #000000;
	} 
	
	.submenu a:hover
	{
		font-weight: normal;
		text-decoration: none;
		border-bottom: 1px solid #FF0000;
		color: #000000;
	} 
	
	.submenu a.active
	{
		text-decoration: none;
		font-weight: bold;
		color: #FF0000;
	} 
	
	.submenu .inactive
	{
		color: #666666;
		font-style: italic;
	}
	
	.hidden 
	{
		visibility: hidden;
		display: none;
	}
	
	.visible 
	{
		visibility: visible;
		display: inline;
	}
	.jeromefooter
	{
		border: 1px solid #444444;
		background: #F5F5F5;
		color: #333333;
		text-align: right;
		padding: 2px 5px 2px 5px;
		margin: 5px 0px 0px 0px;
	}
//-->
</style>
<script type="text/javascript">
<!--
	function toggletransparency()
	{
		format = document.forms["gt_styles"].elements["format"].value;
		
		if (format == "jpg")
		{
			document.getElementById("fgt").className = "hidden";
			document.getElementById("bgt").className = "hidden";
		}
		else
		{
			document.getElementById("fgt").className = "visible";
			document.getElementById("bgt").className = "visible";	
		}
	}
//-->
</script>
';
?>
<div class="submenu">
	<a href="phpwcms.php?do=modules&amp;p=2" <?php echo get_submenustatus(""); ?>><?php echo $BL['be_gt_submenu_home']; ?></a> | 
	<a href="phpwcms.php?do=modules&amp;p=2&amp;s=fonts" <?php echo get_submenustatus("fonts"); ?>><?php echo $BL['be_gt_submenu_fonts']; ?></a> |
	<a href="phpwcms.php?do=modules&amp;p=2&amp;s=colors" <?php echo get_submenustatus("colors"); ?>><?php echo $BL['be_gt_submenu_colors']; ?></a> |
	<a href="phpwcms.php?do=modules&amp;p=2&amp;s=styles" <?php echo get_submenustatus("styles"); ?>><?php echo $BL['be_gt_submenu_styles']; ?></a></div>
