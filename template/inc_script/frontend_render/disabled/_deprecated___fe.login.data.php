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

// general frontend login form

$_loginData				= array();
$_loginData['template']	= ''; // this is what is to be displayed in frontend

if(strpos($content['all'], '{LOGIN_DATA}')) {

	// 1st we load template data
	if(is_file(PHPWCMS_TEMPLATE.'inc_default/fe_login.tmpl')) {
		
		$_loginData['template']		= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/fe_login.tmpl');
	
		$_loginData['form']			= get_tmpl_section('LOGIN_FORM', $_loginData['template']);
		$_loginData['logged_in']	= get_tmpl_section('LOGIN_IS', $_loginData['template']);
		$_loginData['settings']		= get_tmpl_section('LOGIN_SETTINGS', $_loginData['template']);
		$_loginData['settings']		= parse_ini_str($_loginData['settings'], false);
		$_loginData['settings']		= array_merge(	array(	'date_format'	=> "%m/%d/%y",
															'set_locale'	=> '',
															'cookie_exire'	=> 7776000
														  )	, $_loginData['settings'] );
		
		// for future use
		//$_loginData['profile']		= get_tmpl_section('LOGIN_PROFIE', $_loginData['template']);
		
		$_loginData['session_key']	= session_id();
		
		$_loginData['template']	= $_loginData['form'];
		$_loginData['error']	= false;
		$_loginData['login']	= '';
		$_loginData['password']	= '';
		$_loginData['remember']	= 0;		
		
		// handle Login
		if(isset($_POST['feLogin'])) {
		
			$_loginData['login']	= slweg($_POST['feLogin']);
			$_loginData['password']	= slweg($_POST['fePassword']);
			$_loginData['remember']	= empty($_POST['feRemember']) ? 0 : 1;
			
			$_loginData['query_result'] = _checkFrontendUserLogin($_loginData['login'], md5($_loginData['password']));
			
			// ok, and now check if we got valid login data
			if($_loginData['query_result'] !== false && is_array($_loginData['query_result']) && count($_loginData['query_result'])) {
			
				$_SESSION[ $_loginData['session_key'] ] = $_loginData['login'];
				
				if($_loginData['remember']) {
	
					setcookie(	'phpwcmsFeLoginRemember', $_loginData['login'].'##-|-##'.md5($_loginData['password']), 
								time()+$_loginData['settings']['cookie_expire'], '/', getCookieDomain() );
				
				}
			
			} else {
			
				$_loginData['error'] = true;
			
			}
		
		}
		
		if(_getFeUserLoginStatus()) {
			// user is logged in
			if(isset($_POST['feLogin'])) {
				headerRedirect(decode_entities(FE_CURRENT_URL));
			}
			$_loginData['template']	= $_loginData['logged_in'];
			$_loginData['template']	= str_replace('{LOGIN}', html_specialchars( $_SESSION[ $_loginData['session_key'] ] ), $_loginData['template']);
			
		} else {
		
			$_loginData['template'] = render_cnt_template($_loginData['template'], 'ERROR', ($_loginData['error'] ? 'login/pass wrong' : '') );
			$_loginData['template'] = render_cnt_template($_loginData['template'], 'LOGIN', html_specialchars($_loginData['login']));
			$_loginData['template'] = render_cnt_template($_loginData['template'], 'PASSWORD', '');
			$_loginData['template'] = render_cnt_template($_loginData['template'], 'REMEMBER', ($_loginData['remember'] ? ' checked="checked"' : '') );
		
		}
		
		$_loginData['template'] = str_replace('{FORM_TARGET}', FE_CURRENT_URL, $_loginData['template']);
		
	}
}

$content['all'] = str_replace('{LOGIN_DATA}', $_loginData['template'], $content['all']);

?>