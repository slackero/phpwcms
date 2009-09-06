<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Flash Media Player

$fmp_data = array(

		'fmp_template'				=> '',
		'fmp_width'					=> 320,
		'fmp_height'				=> 240,
		'fmp_sort'					=> 1,
		'fmp_int_ext'				=> 0,
		'fmp_internal_id'			=> 0,
		'fmp_internal_name'			=> '',
		'fmp_external_file'			=> '',
		'fmp_caption'				=> '',
		'fmp_link'					=> '',
		'fmp_img_id'				=> 0,
		'fmp_img_name'				=> '',
		'fmp_set_logo'				=> '',
		'fmp_set_hcolor'			=> '',
		'fmp_set_color'				=> '',
		'fmp_set_bgcolor'			=> '',
		'fmp_set_showvolume'		=> 1,
		'fmp_set_showeq'			=> 0,
		'fmp_set_showdigits'		=> 0,
		'fmp_set_showcontrols'		=> 'bottom',
		'fmp_set_largecontrols'		=> 0,
		'fmp_set_autostart'			=> 0,
		'fmp_set_autohidecontrol'	=> 0,
		'fmp_set_flashversion'		=> '7',
		'fmp_set_showdownload'		=> 0,
		'fmp_set_overstretch'		=> 'default',
		'fmp_set_skin'				=> 'default'

				 );
				 
if( $content["id"] ) {

	if( $row["acontent_form"] = @unserialize($row["acontent_form"]) ) {
	
		$fmp_data = array_merge($fmp_data, $row["acontent_form"]);
	
	}

	$fmp_data['fmp_template'] = $row["acontent_template"];
	
	// format color
	if($fmp_data['fmp_set_hcolor'])		$fmp_data['fmp_set_hcolor'] = '#'.$fmp_data['fmp_set_hcolor'];
	if($fmp_data['fmp_set_bgcolor'])	$fmp_data['fmp_set_bgcolor'] = '#'.$fmp_data['fmp_set_bgcolor'];
	if($fmp_data['fmp_set_color'])		$fmp_data['fmp_set_color'] = '#'.$fmp_data['fmp_set_color'];

}


?>