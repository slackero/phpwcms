<?php

/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// necessary guestbook functions

function guestbook_pages($pages=0, $wrap=' ||', $current=0, $total=1, $link='', $linkadd='') {
	// used to create pages listing ... 1 2 3 ...
	$wrap 		= explode('|', $wrap);
	$pages 		= intval($pages);
	$current 	= intval($current);
	$total 		= intval($total);
	$navpages	= '';
	$start 		= 1;
	$end		= $total;
	
	if($pages && $pages < $total) {
		$start 	= $current - (ceil(($pages-1) / 2) - 1);
		if($start < 1) $start = 1;
		$end = $start + $pages - 1;
		if($end > $total) {
			$start = $total - $pages + 1;
			$end = $total;
		}
		
	}
	// pages listing
	for($x=$start; $x<=$end; $x++) {
		if($navpages) $navpages .= $wrap[0];
		if($x-1 != $current) {
			$navpages .= '<a href="'.$link.($x-1).$linkadd.'">';
			$navpages .= $x.'</a>';
		} else {
			$navpages .= $wrap[1].$x.$wrap[2];
		}			
	}
	//return $total.' '.$current;
	return $navpages;

}



?>