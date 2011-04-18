<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

// content type 89: poll 		jens
$poll_html			= "";
$poll_image			= @unserialize($crow["acontent_image"]);
$poll_text			= @unserialize($crow["acontent_text"]);
$poll_form			= @unserialize($crow["acontent_form"]);
$poll_choice_count	= 0;
$remoteIP			= getRemoteIP();

if(!isset($poll_form["ip"])) {
	$poll_form["ip"] = array();
}

$poll_id 	= isset($_POST["hidden_acontent_id"]) ? intval($_POST["hidden_acontent_id"]) : 0;
$poll_count	= max(count($poll_form["choice"]), count($poll_image["images"]));

if($poll_id == $crow['acontent_id'] && isset($_POST["poll"]) && !in_array($remoteIP, $poll_form["ip"])) {
	$poll_choosen 						= intval($_POST["poll"]);
	$poll_form["count"][$poll_choosen] += 1;
	$poll_form["ip"][] = $remoteIP; 
	$poll_choice_count = $poll_form["count"][$poll_choosen];
		
	$sql  = "UPDATE ".DB_PREPEND."phpwcms_articlecontent ";
	$sql .= "SET acontent_form	='".aporeplace(serialize($poll_form))."' ";
	$sql .= "WHERE acontent_id = ".$poll_id." LIMIT 1";
	@mysql_query($sql, $db);
}



$CNT_TMP .= '<div class="';
$CNT_TMP .= empty($poll_text['poll_buttonstyle']) ? 'defaultPollClass' : $poll_text['poll_buttonstyle'];
$CNT_TMP .= "\">\n";
$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);



if(in_array($remoteIP, $poll_form["ip"])) {
	$poll_total_votes = 0;
	foreach($poll_form["count"] as $key => $value) {
		$poll_total_votes += $value;
	}
	if($poll_total_votes > 0) {
		$poll_html .= '<table cellpadding="0" cellspacing="0" border="0">';
	
		for($key = 0; $key < $poll_count; $key++)
		{
			$poll_html .= "\n<tr>\n\t<td>";
			$poll_do_br = '';
			$poll_form["choice"][$key] = isset($poll_form["choice"][$key]) ? trim($poll_form["choice"][$key]) : '';
			if(!empty($poll_form["choice"][$key])) {
				$poll_html .= html_specialchars($poll_form["choice"][$key]);
				$poll_do_br = '<br />';
			}
			if(is_array($poll_image["images"][$key]) && count($poll_image["images"][$key]))
			{
				$poll_html .= $poll_do_br;
				$poll_html .= showPollImage($poll_image["images"][$key]);
			}
			$barWidth = round(($poll_form["count"][$key] / $poll_total_votes * 100), 0);
			$poll_html .= "</td>\n\t".'<td class="pollBarCell">';
			$poll_html .= '<span class="pollBarBegin"><!-- --></span>';
			$poll_html .= '<span class="pollBarMain" style="width:'.$barWidth.'px;"><!-- --></span>';
			$poll_html .= '<span class="pollBarPercent">'.$barWidth."%</span></td>\n</tr>\n";
		}
		$poll_html .= "</table>";
	}
} elseif((is_array($poll_form["choice"]) && count($poll_form["choice"])) || (is_array($poll_image["images"]) && count($poll_image["images"]))) {

	$form_name  = "form_".randpassword(6);
	$poll_html .= '<form action="'.FE_CURRENT_URL.'" method="post" name="'.$form_name.'" id="'.$form_name.'">';
	$poll_html .= '<table cellpadding="0" cellspacing="0" border="0">';
		
	for($key = 0; $key < $poll_count; $key++) {
	
		$poll_html .= "\n<tr>\n\t".'<td class="pollRadioCell">';
		$poll_html .= '<input type="radio" name="poll" value="'.$key.'" />';
		$poll_html .= "</td>\n\t";
		$poll_html .= '<td class="pollInfo">';
		
		$poll_do_br = '';
		
		$poll_form["choice"][$key] = isset($poll_form["choice"][$key]) ? trim($poll_form["choice"][$key]) : '';
		if(!empty($poll_form["choice"][$key])) {
			$poll_html .= $poll_form["choice"][$key];
			$poll_do_br = '<br />';
		}
		if(is_array($poll_image["images"][$key]) && count($poll_image["images"][$key])) {
			$poll_html .= $poll_do_br;
			$poll_html .= showPollImage($poll_image["images"][$key]);
		}
		$poll_html .= "</td>\n</tr>";
	}
	$poll_html .= "</table>\n";
	$poll_html .= '<input type="submit"';
	$poll_html .= empty($poll_text['poll_buttontext']) ? '' : ' value="'.html_specialchars($poll_text['poll_buttontext']).'"';
	$poll_html .= ' class="pollSubmitButton" />';
	$poll_html .= '<input type="hidden" value="'.$crow['acontent_id'].'" name="hidden_acontent_id" />';
	$poll_html .= "</form>";
}

$CNT_TMP .= $poll_html;
$CNT_TMP .= "\n</div>\n";

unset($poll_image);
unset($poll_text);
unset($poll_form);

?>