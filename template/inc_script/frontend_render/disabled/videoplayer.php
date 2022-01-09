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


$content['all'] = preg_replace_callback('/\[(youtube|sevenload)\]([a-zA-Z0-9\-_]+)\[\/(youtube|sevenload)\]/', 'show_videoplayer', $content['all']);


function show_videoplayer($matches) {

	if(empty($matches[2])) {
	    return ' ';
    }

	$player = '';
	$player_id = 'vp'.substr(md5($matches[1].$matches[2].microtime()), 15);

	// Load SwfObject JavaScript
	initSwfObject();

	if($matches[1] === 'youtube') {

		$player  = ' <span id="'.$player_id.'" class="youtube_player"><a href="http://www.youtube.com/watch?v='.$matches[2].'" target="_blank">';
		$player .= 'http://www.youtube.com/watch?v='.$matches[2].'</a></span>' . LF;

		$GLOBALS['block']['custom_htmlhead'][] = '<script type="text/javascript">'.LF.SCRIPT_CDATA_START.LF.'
	    var flashvars_'.$player_id.' = {}, params_'.$player_id.'= {wmode: "opaque"}, attributes_'.$player_id.'= {};
        swfobject.embedSWF("http://www.youtube.com/v/'.$matches[2].'", "'.$player_id.'", "425", "350", "8.0.0", false, flashvars_'.$player_id.', params_'.$player_id.', attributes_'.$player_id.');'.
        LF.SCRIPT_CDATA_END.LF.'</script>';

	} elseif($matches[1] === 'sevenload') {

		$player  = ' <span id="'.$player_id.'" class="sevenload_player"><a href="http://www.sevenload.com/videos/'.$matches[2].'" target="_blank">';
		$player .= 'http://www.sevenload.com/videos/'.$matches[2].'</a></span> ';

		$GLOBALS['block']['custom_htmlhead'][] = '<script type="text/javascript">'.LF.SCRIPT_CDATA_START.LF.'
	    var flashvars_'.$player_id.'= {slxml: "en.sevenload.com"}, params_'.$player_id.'= {wmode: "opaque"}, attributes_'.$player_id.'= {};
	    swfobject.embedSWF("http://en.sevenload.com/pl/'.$matches[2].'/425x350/swf", "'.$player_id.'", "425", "350", "8.0.0", false, flashvars_'.$player_id.', params_'.$player_id.', attributes_'.$player_id.');'.
		LF.SCRIPT_CDATA_END.LF.'</script>';

	}

	return $player;
}
