<?php

if(!function_exists('fmod')) { 
   function fmod($x,$y) { 
      $i = floor($x/$y); 
      return $x - $i * $y; 
   } 
}

function gt2array ($db)
{
	// Die Werte werden mehrmals gespeichert, damit man sie sowohl über die ID als auch über den
	// Namen / Kurznamen ansprechen kann

	$array = array();
	
	// Fonts information
	$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts ORDER BY font_name";
	$result = mysql_query($query, $db) or die ("Error in query:$query");
	
	while ($row = mysql_fetch_assoc($result))
	{
		$font_shortname = "\"".$row["font_shortname"]."\"";
		$array["fonts_name"][$font_shortname]["id"] = $row["font_id"];
		$array["fonts_name"][$font_shortname]["name"] = $row["font_name"];
		$array["fonts_name"][$font_shortname]["shortname"] = $row["font_shortname"];
		$array["fonts_name"][$font_shortname]["filename"] = $row["font_filename"];
		
		$array["fonts_id"][$row["font_id"]]["id"] = $row["font_id"];
		$array["fonts_id"][$row["font_id"]]["name"] = $row["font_name"];
		$array["fonts_id"][$row["font_id"]]["shortname"] = $row["font_shortname"];
		$array["fonts_id"][$row["font_id"]]["filename"] = $row["font_filename"];
	}

	// Colors information
	$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts_colors ORDER BY color_name";
	$result = mysql_query($query, $db) or die ("Error in query:$query");
	
	while ($row = mysql_fetch_assoc($result))
	{
		$colorname = "\"".$row["color_name"]."\"";
		$array["colors_name"][$colorname]["id"] = $row["color_id"];
		$array["colors_name"][$colorname]["name"] = $row["color_name"];
		$array["colors_name"][$colorname]["value"] = $row["color_value"];
		
		$array["colors_id"][$row["color_id"]]["id"] = $row["color_id"];
		$array["colors_id"][$row["color_id"]]["name"] = $row["color_name"];
		$array["colors_id"][$row["color_id"]]["value"] = $row["color_value"];
	}
	
	// Styles information
	$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts_styles ORDER BY style_name";
	$result = mysql_query($query, $db) or die ("Error in query:$query");
	
	while ($row = mysql_fetch_assoc($result)) {
	
		$stylename = "\"".$row["style_name"]."\"";
		$array["styles_name"][$stylename]["id"] = $row["style_id"];
		$array["styles_name"][$stylename]["name"] = $row["style_name"];
		
		$array["styles_id"][$row["style_id"]]["id"] = $row["style_id"];
		$array["styles_id"][$row["style_id"]]["name"] = $row["style_name"];
		
		$style_info = explode(":", $row["style_info"]);
		$array["styles_name"][$stylename]["font"] = $style_info[0];
		$array["styles_name"][$stylename]["antialiasing"] = $style_info[1];
		$array["styles_name"][$stylename]["size"] = $style_info[2];
		$array["styles_name"][$stylename]["fgcolor"] = $style_info[3];
		$array["styles_name"][$stylename]["fgtransparency"] = $style_info[4];
		$array["styles_name"][$stylename]["bgcolor"] = $style_info[5];
		$array["styles_name"][$stylename]["bgtransparency"] = $style_info[6];
		$array["styles_name"][$stylename]["line_width"] = $style_info[7];
		$array["styles_name"][$stylename]["format"] = $style_info[8];
		
		$array["styles_name"][$stylename]["start_x"] = empty($style_info[9]) ? 0 : $style_info[9] ;
		$array["styles_name"][$stylename]["start_y"] = empty($style_info[10]) ? 0 : $style_info[10] ;
		$array["styles_name"][$stylename]["height"] = empty($style_info[11]) ? 5 : $style_info[11] ;
		$array["styles_name"][$stylename]["rotation"] = empty($style_info[12]) ? 'default' : $style_info[12] ;
		
		
		$array["styles_id"][$row["style_id"]]["font"] = $style_info[0];
		$array["styles_id"][$row["style_id"]]["antialiasing"] = $style_info[1];
		$array["styles_id"][$row["style_id"]]["size"] = $style_info[2];
		$array["styles_id"][$row["style_id"]]["fgcolor"] = $style_info[3];
		$array["styles_id"][$row["style_id"]]["fgtransparency"] = $style_info[4];
		$array["styles_id"][$row["style_id"]]["bgcolor"] = $style_info[5];
		$array["styles_id"][$row["style_id"]]["bgtransparency"] = $style_info[6];
		$array["styles_id"][$row["style_id"]]["line_width"] = $style_info[7];
		$array["styles_id"][$row["style_id"]]["format"] = $style_info[8];
		
		$array["styles_id"][$row["style_id"]]["start_x"] = empty($style_info[9]) ? 0 : $style_info[9] ;
		$array["styles_id"][$row["style_id"]]["start_y"] = empty($style_info[10]) ? 0 : $style_info[10] ;
		$array["styles_id"][$row["style_id"]]["height"] = empty($style_info[11]) ? 5 : $style_info[11] ;
		$array["styles_id"][$row["style_id"]]["rotation"] = empty($style_info[12]) ? 'default' : $style_info[12] ;

	}
	
	return $array;
}

?>