<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



//multimedia
$media				= @unserialize($crow['acontent_form']);
$media["source"]	= '';
$media["code"]		= '';

$media["media_control"]	= $media["media_control"] ? 'true' : 'false';
$media["media_auto"]	= $media["media_auto"] ? 'true' : 'false';

if($media["media_src"]) {

	$media["source"] = $media['media_extern'];

} elseif($media["media_id"]) {

	$media["sql"]  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_id=".intval($media["media_id"])." AND ";
	$media["sql"] .= "f_name='".aporeplace($media["media_name"])."' LIMIT 1";

	$media["result"] = _dbQuery($media["sql"]);
	
	if(isset($media["result"][0])) {

		$media["mime"]	 = $media["result"][0]["f_type"];
		$media["source"] = PHPWCMS_FILES.$media["result"][0]["f_hash"];
				
		if($media["result"][0]["f_ext"]) {
			$media["source"] .= '.'.$media["result"][0]["f_ext"];
		}
	}
}

//Aufbauen der Plugin-Codeteile
if($media["source"]) {
	
	$randomID = 'mediaID'.$crow['acontent_id'];

	switch($media["media_player"]) {
		
		case 0:	//Quicktime Player/Plugin
				$block['custom_htmlhead']['AC_QuickTime.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/AC_QuickTime.js" type="text/javascript"></script>';
				
				$media["media_height"] = $media["media_height"] + ( $media["media_control"] == "true" ? 16 : 0 );
				$media["width"]  = $media["media_width"]  ? 'width="'.$media["media_width"].'" '   : '';
				$media["height"] = $media["media_height"] ? 'height="'.$media["media_height"].'" ' : '';
				
				$media["code"]  = LF.'<script type="text/javascript" language="javascript">'.LF.SCRIPT_CDATA_START.LF;
				$media['code'] .= empty($phpwcms['mode_XHTML']) ? '	QT_WriteOBJECT' : '	QT_WriteOBJECT_XHTML';
				$media['code'] .= "('".$media["source"]."', '".$media["media_width"]."', '".$media["media_height"]."', '', ";
				$media['code'] .= "'autoplay', '".$media["media_auto"]."', ";
				$media['code'] .= "'bgcolor', 'black', 'align', 'middle', 'cache', 'true', ";
				$media['code'] .= "'controller', '".$media["media_control"]."', 'type', 'video/quicktime')";
				$media["code"] .= LF.SCRIPT_CDATA_END.LF.'</script>';

				$media["code"] .= '<noscript><object '.$media["width"].$media["height"].'border="0" id="'.$randomID.'" ';
				if(BROWSER_NAME == 'IE' && BROWSER_OS == 'Win') {
					$media["code"] .= 'classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab"';
				} else {
					$media["code"] .= 'data="'.$media["source"].'" type="video/quicktime"';
				}
				$media['code'] .= '>'.LF;
				$media["code"] .= '	<param name="src" value="'.$media["source"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="type" value="video/quicktime"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="align" value="middle"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="autoplay" value="'.$media["media_auto"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="controller" value="'.$media["media_control"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="bgcolor" value="black"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="cache" value="true"'.HTML_TAG_CLOSE.LF;

				$media["code"] .= '</object></noscript>'.LF;
				break;

		case 1:	//Real Player/Plugin
				$console = 'real'.$randomID;
				
				$block['custom_htmlhead']['AC_WriteActiveX.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/AC_WriteActiveX.js" type="text/javascript"></script>';
				
				$media["width"]			= $media["media_width"]  ? 'width="'.$media["media_width"].  '" ' : '';
				$media["height"]		= $media["media_height"] ? 'height="'.$media["media_height"].'" ' : '';
				$media['console']		= 'real'.$randomID;
				
				$media["code"]  = LF.'<object id="'.$randomID.'" name="'.$randomID.'" '.$media["width"].$media["height"];
				$media["code"] .= 'classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">'.LF;
				$media["code"] .= '	<param name="src" value="'.$media["source"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="autostart" value="'.$media["media_auto"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="controls" value="ImageWindow"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="console" value="'.$media['console'].'"'.HTML_TAG_CLOSE.LF;
				if(BROWSER_NAME == 'Mozilla') {
					$media["code"] .= '	<embed src="'.$media["source"].'" border="0" autostart="'.$media["media_auto"].'" ';
					$media["code"] .= 'id="e'.$randomID.'" name="e'.$randomID.'" '.$media["width"].$media["height"];
					$media["code"] .= 'controls="ImageWindow" console="'.$media['console'].'" type="audio/x-pn-realaudio-plugin">';
					$media["code"] .= '</embed>';
				}
				$media["code"] .= '</object>'.LF;
				if($media["media_control"] == "true") {
					$media["code"] .= '<br />'.LF.'<object id="'.$randomID.'_C" name="'.$randomID.'_C" height="32" '.$media["width"];
					$media["code"] .= 'classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">'.LF;
					$media["code"] .= '	<param name="src" value="'.$media["source"].'"'.HTML_TAG_CLOSE.LF;
					$media["code"] .= '	<param name="autostart" value="'.$media["media_auto"].'"'.HTML_TAG_CLOSE.LF;
					$media["code"] .= '	<param name="controls" value="ControlPanel"'.HTML_TAG_CLOSE.LF;
					$media["code"] .= '	<param name="console" value="'.$media['console'].'"'.HTML_TAG_CLOSE.LF;
					if(BROWSER_NAME == 'Mozilla') {
						$media["code"] .= '	<embed src="'.$media["source"].'" border="0" autostart="'.$media["media_auto"].'" ';
						$media["code"] .= 'id="e'.$randomID.'_C" name="e'.$randomID.'_C" height="32" '.$media["width"];
						$media["code"] .= 'controls="ControlPanel" console="'.$media['console'].'" type="audio/x-pn-realaudio-plugin">';
						$media["code"] .= '</embed>';
					}
					$media["code"] .= '</object>'.LF;
				}
				
				if(BROWSER_NAME == 'IE' && BROWSER_OS == 'Win') {
					$media["code"]    = trim($media["code"]);
					$media["iecode"]  = LF.'<script type="text/javascript" language="javascript">'.LF.SCRIPT_CDATA_START.LF;
					$media["iecode"] .= "	_writeActiveXObject('".str_replace(LF, '', $media["code"])."');";
					$media["iecode"] .= LF.SCRIPT_CDATA_END.LF.'</script>'.LF;
					$media["iecode"] .= '<noscript>'.$media["code"].'</noscript>'.LF;
					$media["code"]    = $media["iecode"];
				}

				break;
				
				
		case 2:	//Windows Media Player/Plugin
				$block['custom_htmlhead']['AC_WriteActiveX.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/AC_WriteActiveX.js" type="text/javascript"></script>';
		
				$media["width"]			= $media["media_width"]  ? 'width="'.$media["media_width"].'" ' : '';
				$media["media_height"]	= $media["media_height"] + ($media["media_control"] == "true" ? 45 : 0);
				$media["height"]		= $media["media_height"] ? 'height="'.$media["media_height"].'" ' : '';
				
				$media["code"]  = LF.'<object id="'.$randomID.'" name="'.$randomID.'" '.$media["width"].$media["height"];
				if(BROWSER_NAME == 'IE' && BROWSER_OS == 'Win') {
					$media["code"] .= 'classid="clsid:22D6f312-B0F6-11D0-94AB-0080C74C7E95" ';
					//$media["code"] .= 'classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" ';
					$media["code"] .= 'codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" ';
					$media["code"] .= 'type="application/x-oleobject"';
				} else {
					$media["code"] .= 'data="'.$media["source"].'" type="'.((!$media["media_src"] && $media["media_type"]) ? $media["media_type"] : 'video/x-ms-wmv').'"';
				}
				$media["code"] .= '>'.LF;
				$media["code"] .= '	<param name="filename" value="'.$media["source"].'"'.HTML_TAG_CLOSE.LF;
				//$media["code"] .= '	<param name="src" value="'.$media["source"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="autostart" value="'.($media["media_auto"]=='true'?1:0).'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="autosize" value="0"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="showstatusbar" value="0"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="showcontrols" value="'.($media["media_control"]=='true'?1:0).'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="showdisplay" value="0"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="displaysize" value="0"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="showtracker" value="1"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="enabletracker" value="1"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="autorewind" value="0"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="animationatstart" value="1"'.HTML_TAG_CLOSE.LF;
				if($media["width"] && $media["width"] <=240) {
					$media["code"] .= '	<param name="showpositioncontrols" value="0"'.HTML_TAG_CLOSE.LF;
				}
				$media["code"] .= '</object>'.LF;
				
				if(BROWSER_NAME == 'IE' && BROWSER_OS == 'Win') {
					$media["code"]    = trim($media["code"]);
					$media["iecode"]  = LF.'<script type="text/javascript" language="javascript">'.LF.SCRIPT_CDATA_START.LF;
					$media["iecode"] .= "	_writeActiveXObject('".str_replace(LF, '', $media["code"])."');";
					$media["iecode"] .= LF.SCRIPT_CDATA_END.LF.'</script>'.LF;
					$media["iecode"] .= '<noscript>'.$media["code"].'</noscript>'.LF;
					$media["code"]    = $media["iecode"];
				}
				
				break;
				
				
		case 3:	//Flash Player/Plugin
				$block['custom_htmlhead']['AC_RunActiveContent.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/AC_RunActiveContent.js" type="text/javascript"></script>';
		
				if(!$media["media_src"] && (!$media["media_width"] || !$media["media_height"])) {
					$media['local'] = @getimagesize(PHPWCMS_ROOT.'/'.$media["source"]);
					if(is_array($media['local'])) {
						$media["media_width"]  = $media['local'][0];
						$media["media_height"] = $media['local'][1];
					}
				}
				
				$media["width"]  = $media["media_width"]  ? 'width="'.$media["media_width"].'" ' : '';
				$media["height"] = $media["media_height"] ? 'height="'.$media["media_height"].'" ' : '';
				
				$media["code"]  = LF.'<noscript><object id="'.$randomID.'" name="'.$randomID.'" '.$media["width"].$media["height"];
				if(BROWSER_NAME == 'IE' && BROWSER_OS == 'Win') {
					$media["code"] .= 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
					$media["code"] .= 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" ';
				} else {
					$media["code"] .= 'data="'.$media["source"].'" ';
				}
				$media["code"] .= 'type="application/x-shockwave-flash">'.LF;
				
				$media["code"] .= '	<param name="movie" value="'.$media["source"].'"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="quality" value="high"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="scale" value="noborder"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="loop" value="false"'.HTML_TAG_CLOSE.LF;
				$media["code"] .= '	<param name="play" value="'.$media["media_auto"].'"'.HTML_TAG_CLOSE.LF;
				
				$wmode = ''; $wmode_js = '';
				if($media["media_transparent"]) {
					$media["code"] .= '	<param name="wmode" value="transparent"'.HTML_TAG_CLOSE.LF;
					$wmode			= ' wmode="transparent"';
					$wmode_js		= "'wmode','transparent',";
				}
				
				$media["code"] .= '</object></noscript>'.LF;
				
				$media["source"] = cut_ext($media["source"]);
				$media["code"] .= '<script type="text/javascript" language="javascript">'.LF.SCRIPT_CDATA_START.LF;
				$media["code"] .= "  AC_FL_RunContent('codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0','id','".$randomID."','width','".$media["media_width"]."','height','".$media["media_height"]."','src','".$media["source"]."','quality','high','scale','noborder',".$wmode_js."'pluginspage','http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash','movie','".$media["source"]."' );";
				$media["code"] .= LF.SCRIPT_CDATA_END.LF.'</script>';
				break;
	}
}

if($media["code"]) {

	switch($media["media_pos"]) {
	
		case 0:	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= $media["code"];
				break;
				
		case 1:	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= "<div align=\"center\">".$media["code"]."</div>";
				break;
				
		case 2:	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= "<div align=\"right\">".$media["code"]."</div>";
				break;
				
		case 3: $CNT_TMP .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">\n";
				$CNT_TMP .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
				$CNT_TMP .= ($crow["acontent_title"]) ?	"<tr><td class=\"tableHead\">".html_specialchars($crow["acontent_title"])."</td><td>".
														spacer(5,1)."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
				$CNT_TMP .= ($crow["acontent_subtitle"]) ?	"<tr><td class=\"tableSubHead\">".html_specialchars($crow["acontent_subtitle"])."</td><td>".
															spacer(5,1)."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
				$CNT_TMP .= "<tr><td>".$media["code"]."</td><td>".spacer(5,1)."</td></tr>\n";
				$CNT_TMP .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
				$CNT_TMP .= "</table>\n";
				break;
				
		case 4: $CNT_TMP .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\">\n";
				$CNT_TMP .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
				$CNT_TMP .= ($crow["acontent_title"]) ?	"<tr><td>".spacer(5,1)."</td><td class=\"tableHead\">".html_specialchars($crow["acontent_title"])."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
				$CNT_TMP .= ($crow["acontent_subtitle"]) ?	"<tr><td>".spacer(5,1)."</td><td class=\"tableSubHead\">".html_specialchars($crow["acontent_subtitle"])."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
				$CNT_TMP .= "<tr><td>".spacer(5,1)."</td><td>".$media["code"]."</td></tr>\n";
				$CNT_TMP .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
				$CNT_TMP .= "</table>\n";
				break;
	}

} else {

	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

}
									
?>