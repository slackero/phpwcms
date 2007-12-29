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


$content['all'] = preg_replace_callback('/\[(youtube|sevenload)\]([a-zA-Z0-9\-]{1,32})\[\/(youtube|sevenload)\]/', 'show_videoplayer', $content['all']);


function show_videoplayer($matches) {

	if(empty($matches[2])) return ' ';

	$player		= '';
	$swf		= '';
	$player_id	= 'vp'.substr(md5($matches[1].$matches[2].microtime()), 15);
	
	$GLOBALS['block']['custom_htmlhead']['swfobject.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/swfobject/swfobject.js" type="text/javascript"></script>';

	if($matches[1] == 'youtube') {
	
		$player  = ' <span id="'.$player_id.'" class="youtube_player"><a href="http://www.youtube.com/watch?v='.$matches[2].'" target="_blank">';
		$player .= 'http://www.youtube.com/watch?v='.$matches[2].'</a></span>' . LF;
		
		$swf  = "	var yt".$player_id." = new SWFObject('http://www.youtube.com/v/".$matches[2];
		$swf .=	"', '".$player_id."', '425', '350', '7', '#000000', 'autohigh');";
		$swf .= LF.'	yt'.$player_id.'.addParam("wmode", "transparent");';
		$swf .= LF.'	yt'.$player_id.'.write("' . $player_id . '");';
		

	} elseif($matches[1] == 'sevenload') {
		
		$player  = ' <span id="'.$player_id.'" class="sevenload_player"><a href="http://www.sevenload.com/videos/'.$matches[2].'" target="_blank">';
		$player .= 'http://www.sevenload.com/videos/'.$matches[2].'</a></span> ';

		$swf  = "	var sl".$player_id." = new SWFObject('http://en.sevenload.com/pl/".$matches[2]."/425x350/swf";
		$swf .=	"', '".$player_id."', '425', '350', '7', '#000000', 'autohigh');";
		$swf .= LF.'	sl'.$player_id.'.addParam("wmode", "transparent");';
		$swf .= LF.'	sl'.$player_id.'.addVariable("slxml", "en.sevenload.com");';
		$swf .= LF.'	sl'.$player_id.'.write("' . $player_id . '");';

	}
	
	$player .= '  <script type="text/javascript" language="javascript" defer="defer">'.LF.SCRIPT_CDATA_START.LF;
	$player .= $swf;
	$player .= LF.SCRIPT_CDATA_END.LF.'  </script>'.LF;

	return $player;
}


?>