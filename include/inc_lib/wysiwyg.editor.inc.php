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

// wysiwyg editor

if(!isset($wysiwyg_editor['value']))	$wysiwyg_editor['value']	= '';
if(!isset($wysiwyg_editor['field']))	$wysiwyg_editor['field']	= 'wysiwyg_editor';
if(!isset($wysiwyg_editor['height']))	$wysiwyg_editor['height']	= '350px';
if(!isset($wysiwyg_editor['width']))	$wysiwyg_editor['width']	= '440px';
if(!isset($wysiwyg_editor['rows']))		$wysiwyg_editor['rows']		= '15';
if(!isset($wysiwyg_editor['editor'])){
	$wysiwyg_editor['editor']	= 1;
	if(isset($_SESSION["WYSIWYG_EDITOR"])) $wysiwyg_editor['editor'] = $_SESSION["WYSIWYG_EDITOR"];
}
$wysiwyg_editor['lang']	= isset($_SESSION["wcs_user_lang"]) ? $_SESSION["wcs_user_lang"] : 'en';

switch($wysiwyg_editor['editor']) {

	//load Spaw2
	case 3:
	case 4:
	case 5:
	case 1:	
			include_once(PHPWCMS_ROOT.'/include/inc_ext/spaw2/spaw.inc.php');
			
			SpawConfig::setStaticConfigValue('default_height','250px');
			SpawConfig::setStaticConfigValue('default_output_charset', PHPWCMS_CHARSET);
			//SpawConfig::setStaticConfigValue('base_href', PHPWCMS_URL);
			SpawConfig::setStaticConfigValue('default_toolbarset', $_SESSION['WYSIWYG_TEMPLATE']);
			
			$spaw1 = new SpawEditor($wysiwyg_editor['field'], $wysiwyg_editor['value']);
			$spaw1->setLanguage($wysiwyg_editor['lang'], PHPWCMS_CHARSET);
			// setting global SpawFm settings for a SPAW editor instance:
			$spaw1->setConfigItem(
				'PG_SPAWFM_SETTINGS',
				array(
					'allow_upload' => true,
					'allow_modify' => true,
					'max_upload_filesize' => $phpwcms['file_maxsize'],
					'allowed_filetypes' => array('images', 'flash', 'documents'),
					'recursive' => true,
					'allow_create_subdirectories' => true
				),
				SPAW_CFG_TRANSFER_SECURE
			);
			// setting directories for a SPAW editor instance:
			$spaw1->setConfigItem(
				'PG_SPAWFM_DIRECTORIES',
				array(
					array(
						'dir' => '/picture/upload/',
						'caption' => 'spaw2 Files',
						'params' => array(
							'allowed_filetypes' => array('images', 'flash', 'documents')
							)
					),
				),
				SPAW_CFG_TRANSFER_SECURE
			);
			$spaw1->show();
			break;
	
	//load FCKeditor
	case 2:
			if ( version_compare( phpversion(), '5', '<' ) ) {
				include_once(PHPWCMS_ROOT.'/include/inc_ext/fckeditor/fckeditor_php4.php');
			} else {
				include_once(PHPWCMS_ROOT.'/include/inc_ext/fckeditor/fckeditor_php5.php');
			}
			
			//include_once(PHPWCMS_ROOT.'/include/inc_ext/fckeditor/fckeditor.php');

			$oFCKeditor = new FCKeditor($wysiwyg_editor['field']);
			//$oFCKeditor->BasePath 							= PHPWCMS_BASEPATH.'include/inc_ext/fckeditor/';
			//$oFCKeditor->Config['CustomConfigurationsPath']	= PHPWCMS_BASEPATH.'include/inc_ext/fckeditor/fckeditor_config.js.php' ;
			$oFCKeditor->BasePath 							= PHPWCMS_URL.'include/inc_ext/fckeditor/';
			$oFCKeditor->Config['CustomConfigurationsPath']	= PHPWCMS_URL.'include/inc_ext/fckeditor/fckeditor_config.js.php' ;
			
			$oFCKeditor->Value 								= $wysiwyg_editor['value'];
			$oFCKeditor->Width 								= str_replace('px', '', $wysiwyg_editor['width']);
			$oFCKeditor->Height 							= str_replace('px', '', $wysiwyg_editor['height']);
			$oFCKeditor->ToolbarSet							= $_SESSION['WYSIWYG_TEMPLATE'];
			$oFCKeditor->Create();
			break;

	/*
	//load spaw editor
	case 3:
	case 4:
	case 5:
			// first do a check if translation for given language exists
			$spaw_language_check 		= strtolower(str_replace('-', '', PHPWCMS_CHARSET));
			$wysiwyg_editor['lang']		= strtolower($wysiwyg_editor['lang']);
			
			$spaw_language_folder	= $wysiwyg_editor['lang'].'-'.$spaw_language_check;
			$spaw_language_file		= $wysiwyg_editor['lang'].'-'.$spaw_language_check.'_lang_data.inc.php';
	
			if(file_exists(PHPWCMS_ROOT.'/include/inc_ext/spaw/lib/lang/'.$spaw_language_folder.'/'.$spaw_language_file)) {
				$wysiwyg_editor['lang'] = $spaw_language_folder;
			} elseif(!file_exists(PHPWCMS_ROOT.'/include/inc_ext/spaw/lib/lang/'.$wysiwyg_editor['lang'].'/'.$wysiwyg_editor['lang'].'_lang_data.inc.php')) {
				$wysiwyg_editor['lang'] = 'en';
			}
			if(empty($wysiwyg_spaw_template)) {
				$wysiwyg_spaw_template = $_SESSION['WYSIWYG_TEMPLATE'];
			}
			include_once(PHPWCMS_ROOT.'/include/inc_ext/spaw/spaw_control.class.php');

			$sw = new SPAW_Wysiwyg( $wysiwyg_editor['field'], 
									$wysiwyg_editor['value'], 
									$wysiwyg_editor['lang'],
									$wysiwyg_spaw_template,
									'default', 
									$wysiwyg_editor['width'],
									$wysiwyg_editor['height']
								   );
			$sw->show();
			break;
	*/

	// just show general textarea
	default:	echo '<textarea name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'];
				echo '" class="v12" id="'.$wysiwyg_editor['field'].'" ';
				echo 'style="width:'.$wysiwyg_editor['width'].';height:'.$wysiwyg_editor['height'].';">';
				echo htmlentities($wysiwyg_editor['value']).'</textarea>';

}

?>