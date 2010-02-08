<?php

/**
 * Wrapper Frontend Render Script for MediaBoxAdvanced
 *
 * Will replace MooTools 1.2 based Lightbox effect
 * and handle [MEDIABOX] replacement tags.
 *
 * Sample Quicktime, white|black|my.css CSS
 * <!-- MEDIABOX: white | @@More…@@ -->[MEDIABOX quicktime|640 360|My Private Video]http://symboleffects.com/media/2007vfx.mov[/MEDIABOX]
 */
 
$mediaBox = array(
		
		// set the CSS which should be used for mediaBox
		// available by default: mediaboxAdvBlack.css, mediaboxAdvWhite.css
		'css'		=> 'mediaboxAdvBlack.css',
		
		// default linktext
		'linktext'	=> '@@More…@@'

	);


// check if current JS Lib is MooTools 1.2 otherwise it will not load
if(PHPWCMS_JSLIB != 'mootools-1.2' || ( strpos($content['all'], '[MEDIABOX') === false && strpos($content['all'], 'rel="lightbox') === false ) ) {

	// remove all [MEDIABOX] replacement tags
	$content['all'] = preg_replace( array('/\[MEDIABOX.*?\]/i', '/\[\/MEDIABOX\]/i'), '', $content['all'] );

} else {

	// unset Slimbox CCC and JS
	unset(
		$block['custom_htmlhead']['lightbox.css'],
		$block['custom_htmlhead']['slimbox.js']
	);
	
	// Set CSS color and/or More Text:
	// <!-- MEDIABOX: black | @@More…@@ -->
	// <!-- MEDIABOX: white | @@More…@@ -->
	// <!-- MEDIABOX: myCustomMediaBox.css | @@More…@@ -->
	if(preg_match('/<!-- MEDIABOX:(.+?)-->/', $content['all'], $match)) {
		
		$match		= explode('|', trim($match[1]), 2);
		$match[0]	= trim($match[0]);
		if($match[0] == 'black') {
			$mediaBox['css'] = 'mediaboxAdvBlack.css';
		} elseif($match[0] != 'white') {
			$mediaBox['css'] = $match[0];
		} else {
			$mediaBox['css'] = 'mediaboxAdvWhite.css';
		}
		$match[1]	= empty($match[1]) ? '' : trim($match[1]);
		if($match[1]) {
			$mediaBox['linktext'] = $match[1];
		}
	}

	initJSLIb();

	// load mediaBox JavaScript and CSS
	set_css_link( 'lib/mediabox/css/'.$mediaBox['css'] );
	$block['custom_htmlhead']['mediabox.js'] =  getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mediabox/mediaboxAdv-yui.js');
	initJSPlugin('Quickie-yui');


	// parse and render MEDIABOX
	$content['all'] = preg_replace_callback('/\[MEDIABOX(.*?)\](.*?)\[\/MEDIABOX\]/is', 'renderMediaBox', $content['all']);

}

function renderMediaBox($match) {

	$types		= array('twitter', 'social', 'flash', 'audio', 'inline', 'external', 'quicktime');

	$set		= trim($match[1]);
	$inner		= trim($match[2]);
	
	$type		= '';
	$caption	= '';
	$url		= '';
	$linktext	= '';
	$html		= '';
	$size		= '';
	$lightbox	= '';
	
	// define type, size, caption - elements divided by pipe |
	$set	= explode('|', $set, 3);
	foreach($set as $key => $item) {
		
		$item = trim($item);
		if($item == '') {
			continue;
		}
		if($type == '' && in_array(strtolower($item), $types)) {
			$type = strtolower($item);
			continue;
		}
		if(!$size && preg_match('/^([0-9]+).([0-9]+)$/', $item, $s)) {
			$size	= intval($s[1]) . ' ' . intval($s[2]);	
			continue;
		}
		
		$caption = $item;

		break;
	}
	
	// check inner elements - elements divided by pipe |
	// it is formatted this way: url | linktext
	$inner		= explode('|', $inner, 2);
	$inner[0]	= trim($inner[0]);
	$inner[1]	= empty($inner[1]) ? '' : trim($inner[1]);
	if($inner[1]) {
		$linktext = $inner[1];
	} elseif($caption) {
		$linktext = explode('::', $caption);
		$linktext = trim($linktext[0]);
	}
	if($linktext == '') {
		$linktext = $GLOBALS['mediaBox']['linktext'];
	}

	// test if first element is URL
	if( preg_match('/^http|https:\/\//i', $inner[0]) ) {
		$url = $inner[0];
	} 

	// test against .flv, .mp3, .mp4, .aac, .m4a
	if( preg_match('/(\.flv|\.mp3|\.mp4|\.m4a|\.aac|\.mov|\.m4v|\.aiff|\.avi|\.caf|\.dv|\.mid|\.m3u|\.mp2|\.qtz|\.f4v|\.f4p|\.f4a|\.f4b)$/i', $inner[0], $ext) ) {
		
		$ext = strtolower($ext[1]);
		$url = $inner[0];
		
		// set type Flash (Video)
		if($ext === '.flv' || $ext === '.mp4' || $ext === '.m4v') {
			$type = 'flash';
		} elseif($ext === '.mp3' || $ext === '.m4a' || $ext === '.aac' || $ext === '.f4a' || $ext === '.f4b') {
			$type = 'audio';
		} else {
			$type = 'quicktime';
		}

	}
	
	// what to do if URL is empty
	if($url == '') {
		$type = 'inline';
		$html = $inner[0];
	}
	
	if($type != 'inline') {
		
		if($type == 'audio' && $size == '') {
			$size = '350 20';
		}
	
		$lightbox  = '<a href="' . html_specialchars($url) . '" ';
		$lightbox .= 'rel="lightbox[' . trim( $type . ' ' . $size ) . ']"';
		$lightbox .= ' title="' . html_specialchars( $caption ? $caption : $linktext ) . '"';
		$lightbox .= '>' . html_specialchars( $linktext ) . '</a>';
		
	} else {
		
		$id = 'mb_inline_' . generic_string(6, 1);
	
		$lightbox  = '<a href="#' . $id . '" ';
		$lightbox .= 'rel="lightbox[' . trim( $type . ' ' . $size ) . ']"';
		$lightbox .= ' title="' . html_specialchars( $caption ? $caption : $linktext ) . '"';
		$lightbox .= '>' . html_specialchars( $linktext ) . '</a>';
		$lightbox .= LF . '<div id="' . $id . '" style="display:none;">';
		$lightbox .= $html;
		//$lightbox .= '<p><a href="#" onclick="Mediabox.close();return false;">@@close onClick@@</a></p>';
		$lightbox .= '</div>';
		
	}

	return $lightbox;

}

?>