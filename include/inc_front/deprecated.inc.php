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

// These are all kind of deprecated replacer and function
// might kicked off the system in the near future
// If you need these, please use config setting
// $phpwcms['enable_deprecated'] = true;

function get_random_image_tag($path) {
	// returns an random image from the give path
	// it looks for image of following type: gif, jpg, jpeg, png
	// {RANDOM:path} willl return <img src="path/rand_image" />
	// {RANDOM:SRC:path} willl return absolute URI PHPWCMS_URL/path/rand_image

	$imgArray	= array();
	$path		= trim(is_array($path) && isset($path[1]) ? $path[1] : $path);
	if(strtoupper(substr($path, 0, 4)) == 'SRC:') {
		$tag	= false;
		$path	= trim(substr($path, 4));
	} else {
		$tag	= true;
	}

	$path		= trim($path, '/');
	$imgpath	= PHPWCMS_ROOT . '/' . $path . '/';
	$imageinfo	= false;

	if(is_dir($imgpath)) {
		$handle = opendir($imgpath);
		while($file = readdir( $handle )) {
   			if( substr($file, 0, 1) !== '.' && is_file($imgpath.$file) && preg_match('/(\.jpg|\.jpeg|\.gif|\.png)$/i', $file)) {
				$imgArray[] = $file;
			}
		}
		closedir( $handle );
	}

	if(count($imgArray) && ($imageinfo = is_random_image($imgArray, $imgpath))) {
		if($tag) {
			return '<img src="' . $path . '/'. urlencode($imageinfo['imagename']) . '" ' . $imageinfo[3] . ' alt="' . html($imageinfo["imagename"]) . '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
		} else {
			return PHPWCMS_URL . $path . '/' . urlencode($imageinfo['imagename']);
		}
	}

	return '';
}

function is_random_image($imgArray, $imagepath, $count=0) {
	// tests if the random choosed image is really an image
	$count++;
	$randval = mt_rand( 0, count( $imgArray ) - 1 );
	$file = $imagepath.$imgArray[ $randval ];
	$imageinfo = @getimagesize($file);
	//if $imageinfo is not true repeat function and count smaller count all images
	if(!$imageinfo && $count < count($imgArray)) {
		$imageinfo = is_random_image($imgArray, $imagepath, $count);
	} else {
		$imageinfo["imagename"] = $imgArray[ $randval ];
	}
	return $imageinfo;
}

function html_parser_deprecated($string='') {

	$search			= array();
	$replace		= array();

	// random GIF Image
	$search[0]		= '/\{RANDOM_GIF:(.*?)\}/';
	$replace[0]		= '<img src="img/random_image.php?type=0&amp;imgdir=$1" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

	// random JPEG Image
	$search[1]		= '/\{RANDOM_JPEG:(.*?)\}/';
	$replace[1]		= '<img src="img/random_image.php?type=1&amp;imgdir=$1" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

	// random PNG Image
	$search[2]		= '/\{RANDOM_PNG:(.*?)\}/';
	$replace[2]		= '<img src="img/random_image.php?type=2&amp;imgdir=$1" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

	// insert non db image standard
	$search[3]		= '/\{IMAGE:(.*?)\}/';
	$replace[3]		= '<img src="picture/$1" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

	// insert non db image left
	$search[4]		= '/\{IMAGE_LEFT:(.*?)\}/';
	$replace[4]		= '<img src="picture/$1" align="left" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

	// insert non db image right
	$search[5]		= '/\{IMAGE_RIGHT:(.*?)\}/';
	$replace[5]		= '<img src="picture/$1" align="right" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

	// insert non db image center
	$search[6]		= '/\{IMAGE_CENTER:(.*?)\}/';
	$replace[6]		= '<div style="text-align:center;"><img src="picture/$1" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE . '</div>';

	// random Image Tag
	$string			= preg_replace_callback('/\{RANDOM:(.*?)\}/', 'get_random_image_tag', $string);

	return preg_replace($search, $replace, $string);

}

//menu creating
function nav_table_simple_struct($struct, $act_cat_id, $link_to="index.php") {
	//returns a simple table based navigation menu of possible
	//structure levels based on current structure level
	$nav_table  = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" summary=\"\">\n<tr>\n";
	$nav_table .= "<td width=\"10\"><img src=\"img/leer.gif\" width=\"10\" height=\"1\" alt=\"\" /></td>\n";
	$nav_table .= '<td width="100%"'.(empty($struct[$act_cat_id]["acat_class"]) ? '' : ' class="'.$struct[$act_cat_id]["acat_class"].'"').'><strong>';
	$nav_table .= html_specialchars($struct[$act_cat_id]["acat_name"]);
	$nav_table .= "</strong></td>\n<tr>";
	foreach($struct as $key => $value) {

		if($key != $act_cat_id && _getStructureLevelDisplayStatus($key, $act_cat_id) ) {

			$nav_table .= "<tr>\n";
			$nav_table .= "<td width=\"10\"><img src=\"img/leer.gif\" width=\"10\" height=\"1\" alt=\"\" /></td>\n";
			$nav_table .= '<td width="100%"'.(empty($struct[$key]["acat_class"]) ? '' : ' class="'.$struct[$key]["acat_class"].'"').'>';

			if(!$struct[$key]["acat_redirect"]) {
				$nav_table .= '<a href="index.php?';
				if($struct[$key]["acat_alias"]) {
					$nav_table .= html_specialchars($struct[$key]["acat_alias"]);
				} else {
					$nav_table .= 'id='.$key; //',0,0,1,0,0';
				}
				$nav_table .= '">';
			} else {
				$redirect = get_redirect_link($struct[$key]["acat_redirect"], ' ', '');
				$nav_table .= '<a href="'.$redirect['link'].'"'.$redirect['target'].'>';
			}

			$nav_table .= html_specialchars($struct[$key]["acat_name"])."</a></td>\n<tr>";
		}
	}
	$nav_table .= '</table>';
	return $nav_table;
}

function nav_table_struct_callback($matches) {
	return nav_table_struct($GLOBALS['content']["struct"], $GLOBALS['content']["cat_id"], $matches[1], $GLOBALS['template_default']["nav_table_struct"]);
}

function nav_table_struct ($struct, $act_cat_id, $level, $nav_table_struct, $link_to="index.php") {
	// start with home directory for the listing = top nav structure
	// 1. Build the recursive tree for given actual article category ID

	// return the tree starting with given start_id (like breadcrumb)
	// if the $start_id = 0 then this stops because 0 = top level

	$level 			= intval($level);
	$start_id 		= $act_cat_id;
	$data 			= array();
	$c 				= 0;
	$total_levels 	= 0;
	$level_depth 	= 0;
	//$start_level 	= $level;
	while ($start_id) {
		$data[$start_id]	= 1;
		$start_id			= $struct[$start_id]["acat_struct"];
		$total_levels++;
	}

	$temp_tree = is_array($data) && count($data) ? array_reverse($data, 1) : array();

	foreach($struct as $key => $value) {
		if($struct[$key]["acat_struct"] == $act_cat_id && $key && (!$struct[$key]["acat_hidden"] || isset($GLOBALS['LEVEL_KEY'][$key]))) {
			$c++;
		}
	}
	$c = (!$c) ? 1 : 0;

	//build image src path and real image tag
	$nav_table_struct["linkimage_over_js"]  = get_real_imgsrc($nav_table_struct["linkimage_over"]);
	$nav_table_struct["linkimage_norm"]		= add_linkid($nav_table_struct["linkimage_norm"],   '#');
	$nav_table_struct["linkimage_over"]		= add_linkid($nav_table_struct["linkimage_over"],   '#');
	$nav_table_struct["linkimage_active"]	= add_linkid($nav_table_struct["linkimage_active"], '#');

	$lc = count($temp_tree);
	$ld = false;
	for($l = 0; $l <= $lc; $l++) {

		if(isset($GLOBALS['LEVEL_ID'][$l])) {

			$curStructID = $GLOBALS['LEVEL_ID'][$l];
			// now all deeper levels can be deleted
			if($ld) {
				unset($temp_tree[$curStructID]);
			}

			if(!isset($nav_table_struct['array_struct'][$l])) {

				$nav_table_struct['array_struct'][$l]["linkimage_over_js"]	= $nav_table_struct["linkimage_over_js"];
				$nav_table_struct['array_struct'][$l]["linkimage_norm"]		= $nav_table_struct["linkimage_norm"];
				$nav_table_struct['array_struct'][$l]["linkimage_over"]		= $nav_table_struct["linkimage_over"];
				$nav_table_struct['array_struct'][$l]["linkimage_active"]	= $nav_table_struct["linkimage_active"];

				$nav_table_struct['array_struct'][$l]["link_before"]		= $nav_table_struct["link_before"];
				$nav_table_struct['array_struct'][$l]["link_after"]			= $nav_table_struct["link_after"];
				$nav_table_struct['array_struct'][$l]["link_active_before"]	= $nav_table_struct["link_active_before"];
				$nav_table_struct['array_struct'][$l]["link_active_after"]	= $nav_table_struct["link_active_after"];

				$nav_table_struct['array_struct'][$l]["row_norm_bgcolor"]	= $nav_table_struct["row_norm_bgcolor"];
				$nav_table_struct['array_struct'][$l]["row_norm_class"]		= $nav_table_struct["row_norm_class"];
				$nav_table_struct['array_struct'][$l]["row_over_bgcolor"]	= $nav_table_struct["row_over_bgcolor"];
				$nav_table_struct['array_struct'][$l]["row_active_bgcolor"]	= $nav_table_struct["row_active_bgcolor"];
				$nav_table_struct['array_struct'][$l]["row_active_class"]	= $nav_table_struct["row_active_class"];

				$nav_table_struct['array_struct'][$l]["space_celltop"]		= $nav_table_struct["space_celltop"];
				$nav_table_struct['array_struct'][$l]["space_cellbottom"]	= $nav_table_struct["space_cellbottom"];

				$nav_table_struct['array_struct'][$l]["cell_height"]		= $nav_table_struct["cell_height"];
				$nav_table_struct['array_struct'][$l]["cell_class"]			= $nav_table_struct["cell_class"];
				$nav_table_struct['array_struct'][$l]["cell_active_height"]	= $nav_table_struct["cell_active_height"];
				$nav_table_struct['array_struct'][$l]["cell_active_class"]	= $nav_table_struct["cell_active_class"];

			} else {

				$nav_table_struct['array_struct'][$l]["linkimage_over_js"]	= get_real_imgsrc($nav_table_struct['array_struct'][$l]["linkimage_over"]);
				$nav_table_struct['array_struct'][$l]["linkimage_norm"]		= add_linkid($nav_table_struct['array_struct'][$l]["linkimage_norm"],   '#');
				$nav_table_struct['array_struct'][$l]["linkimage_over"]		= add_linkid($nav_table_struct['array_struct'][$l]["linkimage_over"],   '#');
				$nav_table_struct['array_struct'][$l]["linkimage_active"]	= add_linkid($nav_table_struct['array_struct'][$l]["linkimage_active"], '#');

			}

			if($struct[$curStructID]['acat_hidden'] == 1) {
				unset($temp_tree[$curStructID]);
				$ld = true;
			}

		}

	}

	$temp_menu = build_levels ($struct, $level, $temp_tree, $act_cat_id, $nav_table_struct, $level_depth, $c, $link_to); //starts at root level
	if($temp_menu) {
		initFrontendJS();
		return "<table".table_attributes($nav_table_struct, "table", 0)." summary=\"\">\n".$temp_menu."</table>";
	}
	return '';
}

function nav_level_row($show_id, $show_home=1) {
	//returns a simple row based navigation
	if(is_array($show_id) && isset($show_id[1])) {
		$show_home	= $show_id[2];
		$show_id	= $show_id[1];
	}

	if(strtoupper($show_id) == 'CURRENT') {
		$act_cat_id = $GLOBALS['content']["cat_id"];
	} else {
		$act_cat_id = intval($show_id);
	}

	$nav = '';

	if($show_home && $GLOBALS['content']['struct'][$act_cat_id]['acat_hidden'] != 1) {
		if($GLOBALS['content']["cat_id"] == $act_cat_id) {
			$before = $GLOBALS['template_default']["nav_row"]["link_before_active"];
			$after  = $GLOBALS['template_default']["nav_row"]["link_after_active"];
			$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before_active"];
			$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after_active"];
		} else {
			$before = $GLOBALS['template_default']["nav_row"]["link_before"];
			$after  = $GLOBALS['template_default']["nav_row"]["link_after"];
			$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before"];
			$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after"];
		}
		$nav .= $before;
		$nav .= '<a href="index.php?';
		$nav .= ($GLOBALS['content']['struct'][$act_cat_id]['acat_alias']) ? html_specialchars($GLOBALS['content']['struct'][$act_cat_id]['acat_alias']) : 'id='.$act_cat_id; //',0,0,1,0,0';
		$nav .= '"'.(empty($GLOBALS['content']['struct'][$act_cat_id]["acat_class"]) ? '' : ' class="'.$GLOBALS['content']['struct'][$act_cat_id]["acat_class"].'"').'>'.$direct_before;
		$nav .= html_specialchars($GLOBALS['content']['struct'][$act_cat_id]['acat_name']);
		$nav .= $direct_after.'</a>'.$after;
	}

	// check against breadcrumb - active site tree
	if($GLOBALS['content']['struct'][$GLOBALS['content']["cat_id"]]['acat_struct'] != 0) {
		$breadcrumb = get_breadcrumb($GLOBALS['content']["cat_id"], $GLOBALS['content']['struct']);
	}

	foreach($GLOBALS['content']['struct'] as $key => $value) {

		if($key != $act_cat_id && _getStructureLevelDisplayStatus($key, $act_cat_id) ) {

			$class = empty($GLOBALS['content']['struct'][$key]["acat_class"]) ? '' : ' class="'.$GLOBALS['content']['struct'][$key]["acat_class"].'"';

			if($nav) {
				$nav .= $GLOBALS['template_default']["nav_row"]["between"];
			}

			if($GLOBALS['content']["cat_id"] == $key || isset($breadcrumb[$key])) {
				$before = $GLOBALS['template_default']["nav_row"]["link_before_active"];
				$after  = $GLOBALS['template_default']["nav_row"]["link_after_active"];
				$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before_active"];
				$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after_active"];
			} else {
				$before = $GLOBALS['template_default']["nav_row"]["link_before"];
				$after  = $GLOBALS['template_default']["nav_row"]["link_after"];
				$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before"];
				$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after"];
			}

			$nav .= $before;

			if(!$GLOBALS['content']['struct'][$key]["acat_redirect"]) {
				$nav .= '<a href="index.php?';
				if($GLOBALS['content']['struct'][$key]["acat_alias"]) {
					$nav .= html_specialchars($GLOBALS['content']['struct'][$key]["acat_alias"]);
				} else {
					$nav .= 'id='.$key; //',0,0,1,0,0';
				}
				$nav .= '"'.$class.'>';
			} else {
				$redirect = get_redirect_link($GLOBALS['content']['struct'][$key]["acat_redirect"], ' ', '');
				$nav .= '<a href="'.$redirect['link'].'"'.$redirect['target'].$class.'>';
			}
			$nav .= $direct_before;
			$nav .= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
			$nav .= $direct_after.'</a>'.$after;
		}
	}
	if($nav) {
		$nav  = $GLOBALS['template_default']["nav_row"]["before"].$nav;
		$nav .= $GLOBALS['template_default']["nav_row"]["after"];
	}
	return $nav;
}

function nav_list_struct_callback($matches) {
	return nav_list_struct($GLOBALS['content']["struct"], $GLOBALS['content']["cat_id"], $matches[1], $matches[2]);
}

function nav_list_struct($struct, $act_cat_id, $level, $class='') {
	// start with home directory for the listing = top nav structure
	// 1. Build the recursive tree for given actual article category ID

	// return the tree starting with given start_id (like breadcrumb)
	// if the $start_id = 0 then this stops because 0 = top level

	$level		= intval($level);
	$data		= array();
	$start_id 	= $act_cat_id;
	$class 		= trim($class);
	$depth		= 0;

	while ($start_id) {
		$data[$start_id] = 1;
		$start_id		 = $struct[$start_id]["acat_struct"];
	}
	$temp_tree = sizeof($data) ? array_reverse($data, 1) : false;

	$temp_menu = build_list($struct, $level, $temp_tree, $act_cat_id, $class, $depth);
	$temp_menu = str_replace("\n\n", LF, $temp_menu);
	return $temp_menu ? $temp_menu : '';
}

function css_level_list_current_callback($matches) {
	return css_level_list($GLOBALS['content']["struct"], $GLOBALS['content']["cat_path"], $GLOBALS['content']["cat_id"], $matches[2], $matches[1], $matches[3]);
}

function css_level_list_top_callback($matches) {
	return css_level_list($GLOBALS['content']["struct"], $GLOBALS['content']["cat_path"], 0, $matches[1], 1, $matches[2]);
}

function css_level_list(&$struct, $struct_path, $level, $parent_level_name='', $parent_level=1, $class='') {
	// returns list <div><ul><li></li></ul></div> of the current structure level
	// if $parent_level=1 the first list entry will be the parent level
	// $parent_level=0 - only the list of all levels in this structure
	// if $parent_leve_name != "" then it uses the given string
	// predefined class for this menu is "list_level"
	if(!trim($class)) {
		$class = 'list_level';
	}
	$parent_level_name	= trim($parent_level_name);
	$level 				= intval($level);
	$parent_level 		= intval($parent_level);
	$activated			= 0;
	$css_list			= '';

	//returns the complete level of NON hidden categories
	$level_struct 		= return_struct_level($struct, $level);
	$breadcrumb 		= get_breadcrumb(key($struct_path), $struct);

	foreach($level_struct as $key => $value) {

		if(!$level_struct[$key]["acat_redirect"]) {
			$link = 'index.php?';
			if($level_struct[$key]["acat_alias"]) {
				$link .= html_specialchars($level_struct[$key]["acat_alias"]);
			} else {
				$link .= 'id='.$key;
			}
			$redirect['target'] = '';
		} else {
			$redirect = get_redirect_link($level_struct[$key]["acat_redirect"], ' ', '');
			$link = $redirect['link'];
		}
		$css_list .= '	<li';
		$liclass   = trim( (empty($breadcrumb[$key]) ? '' : 'active ') . $level_struct[$key]["acat_class"] );
		$css_list .= empty($liclass) ? '' : ' class="'.$liclass.'"';
		$css_list .= '><a href="'.$link.'"'.$redirect['target'].'>';
		$css_list .= html_specialchars($level_struct[$key]["acat_name"]);
		$css_list .= '</a></li>'.LF;

	}

	if($parent_level) {
		if(!$struct[$level]["acat_redirect"]) {
			$link = 'index.php?';
			if($struct[$level]["acat_alias"]) {
				$link .= html_specialchars($struct[$level]["acat_alias"]);
			} else {
				$link .= 'id='.$level;
			}
			$redirect['target'] = '';
		} else {
			$redirect = get_redirect_link($struct[$level]["acat_redirect"], ' ', '');
			$link = $redirect['link'];
		}

		$css_list_home  = '	<li class="' . trim( ($GLOBALS['aktion'][0] == $level ? 'active' : 'parent') . ' ' . $struct[$level]["acat_class"] ) .'">';
		$css_list_home .= '<a href="'.$link.'"'.$redirect['target'].'>';
		$css_list_home .= html_specialchars((!$parent_level_name) ? $struct[$level]["acat_name"] : $parent_level_name);
		$css_list_home .= '</a></li>'.LF;
		$css_list = $css_list_home . $css_list;
	}
	if($css_list) {
		$css_list = LF.'<ul class="'.$class.'">'.LF . $css_list .'</ul>'.LF;
	}
	return $css_list;
}
