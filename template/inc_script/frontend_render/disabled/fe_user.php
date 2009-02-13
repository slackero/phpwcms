<?php

// first check what to do
if(_getFeUserLoginStatus() && strpos($content['all'], '{FE_USER_MANAGE}')) {

	$fe_action = '{FE_USER_MANAGE}';
	
	if( $_SESSION[ $_loginData['session_key'].'_userdata']['source'] == 'BACKEND' ) {

		$fe_action = false;
	}
	

} elseif(strpos($content['all'], '{FE_USER_REGISTER}')) {

	$fe_action = '{FE_USER_REGISTER}';

} else {

	$fe_action = false;

}


// fe user register
if($fe_action) {

	$udata = array(
						'user_login'		=> '',
						'user_password'		=> '',
						
						'user_company'		=> '',
						'user_title'		=> '',
						'user_name'			=> '',
						'user_firstname'	=> '',
						'user_street'		=> '',
						'user_zip'			=> '',
						'user_city'			=> '',
						'user_tel'			=> '',
						'user_email'		=> '',
						
						'user_profile_1'	=> '', 
						'user_profile_2'	=> '', 
						'user_profile_3'	=> '',
						'user_profile_4'	=> '',
						'user_profile_5'	=> '',
						'user_profile_6'	=> '',
						'user_profile_7'	=> array(4=>1),
						'user_profile_8'	=> '',
						'user_profile_9'	=> '',
						'user_profile_10'	=> '',
						'user_profile_11'	=> '',
						'user_profile_12'	=> '',
						'user_profile_13'	=> '',
						'user_profile_14'	=> '',
					);		

	switch($fe_action) {
	
		case '{FE_USER_MANAGE}':	$_uri = 'index.php'.returnGlobalGET_QueryString('htmlentities', array('profile_manage'=>'edit'), array('profile_register', 'rofile_reminder') );
									
									// at the moment it is only possible to edit user data of "real" FRONTEND users
									// all BACKEND users should login to backend and edit their data there
									$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE ';
									$sql .= 'detail_id=' . intval($_SESSION[ $_loginData['session_key'].'_userdata']['id']).' LIMIT 1';
									$result = _dbQuery($sql);
									if(isset($result[0])) {
										$udata = unserialize($result[0]['detail_notes']);
									}
									$udata['user_password'] = '';
									
									break;
									
		case '{FE_USER_REGISTER}':	$_uri = 'index.php'.returnGlobalGET_QueryString('htmlentities', array('profile_register'=>'create'), array('profile_manage', 'rofile_reminder') );
		

									break;
	
	}

	
	$uerror = array(
	
		'status'			=> false,
	
		'user_login'		=> '',
		'user_password'		=> '',
		
		'user_company'		=> '',
		'user_title'		=> '',
		'user_name'			=> '',
		'user_firstname'	=> '',
		'user_street'		=> '',
		'user_zip'			=> '',
		'user_city'			=> '',
		'user_tel'			=> '',
		'user_email'		=> '',
		
		'user_profile_1'	=> '', 
		'user_profile_2'	=> '',
		'user_profile_3'	=> '',
		'user_profile_4'	=> '',
		'user_profile_5'	=> '',
		'user_profile_6'	=> '',
		'user_profile_7'	=> '',
		'user_profile_8'	=> '',
		'user_profile_9'	=> '',
		'user_profile_10'	=> '',
		'user_profile_11'	=> '',
		'user_profile_12'	=> '',
		'user_profile_13'	=> '',
		'user_profile_14'	=> '',
	
	);

	$user_title = array('Herr', 'Frau', '');

	if(isset($_POST['user_login'])) {
	
		$fe_csv = array();
		
		$udata['user_login']		= clean_slweg($_POST['user_login']);
		$udata['user_password']		= slweg($_POST['user_password']);
		$udata['user_password2']	= slweg($_POST['user_password2']);
			
		$udata['user_company']		= clean_slweg($_POST['user_company']);
		$udata['user_title']		= clean_slweg($_POST['user_title']);
		$udata['user_name']			= clean_slweg($_POST['user_name']);
		$udata['user_firstname']	= clean_slweg($_POST['user_firstname']);
		$udata['user_street']		= clean_slweg($_POST['user_street']);
		$udata['user_zip']			= clean_slweg($_POST['user_zip']);
		$udata['user_city']			= clean_slweg($_POST['user_city']);
		$udata['user_tel']			= preg_replace('/[^0-9\+\-\(\) ]/', '', clean_slweg($_POST['user_tel']) );
		$udata['user_email']		= clean_slweg($_POST['user_email']);

		$fe_csv['login'] 		= $udata['user_login'];
		$fe_csv['company'] 		= $udata['user_company'];
		$fe_csv['title'] 		= $udata['user_title'];
		$fe_csv['name'] 		= $udata['user_name'];
		$fe_csv['firstname']	= $udata['user_firstname'];
		$fe_csv['street'] 		= $udata['user_street'];
		$fe_csv['zip'] 			= $udata['user_zip'];
		$fe_csv['city'] 		= $udata['user_city'];
		$fe_csv['tel'] 			= $udata['user_tel'];
		$fe_csv['email'] 		= $udata['user_email'];
		
		if($fe_action == '{FE_USER_REGISTER}') {
		
			
			$udata['user_profile_1']	= isset($_POST['user_profile_1']) ? intval($_POST['user_profile_1']) : '';
			$udata['user_profile_2']	= isset($_POST['user_profile_2']) ? clean_slweg($_POST['user_profile_2']) : '';
			
			$udata['user_profile_5']	= isset($_POST['user_profile_5']) ? intval($_POST['user_profile_5']) : '';
			$udata['user_profile_6']	= clean_slweg($_POST['user_profile_6']);
			$udata['user_profile_8']	= clean_slweg($_POST['user_profile_8']);
			$udata['user_profile_9']	= isset($_POST['user_profile_9']) ? clean_slweg($_POST['user_profile_9']) : '';
			$udata['user_profile_10']	= clean_slweg($_POST['user_profile_10']);
			$udata['user_profile_11']	= isset($_POST['user_profile_11']) ? clean_slweg($_POST['user_profile_11']) : '';
			
			$udata['user_profile_13']	= isset($_POST['user_profile_13']) ? clean_slweg($_POST['user_profile_13']) : '';
			$udata['user_profile_14']	= clean_slweg($_POST['user_profile_14']);
		
		
		
			$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_userdetail WHERE ";
			$sql .= "detail_login LIKE '" . aporeplace($udata['user_login'])."'";
		
			if( empty($udata['user_login']) ) {
				$uerror['user_login'] = 'Login muss ausgef&uuml;llt werden';
			} elseif( strlen($udata['user_login']) < 4 ) {
				$uerror['user_login'] = 'Login muss mindestens 4 Zeichen lang sein';
			} elseif( _dbCount( $sql )	) {
				$uerror['user_login'] = 'Dieser Login ist bereits vergeben';
			}
			
			if( empty($udata['user_password']) ) {
				$uerror['user_password'] = 'Passwort muss ausgef&uuml;llt werden';
			} elseif( strlen($udata['user_password']) < 4 ) {
				$uerror['user_password'] = 'Passwort muss mindestens 4 Zeichen lang sein';
			} elseif( $udata['user_password'] !== $udata['user_password2'] ) {
				$uerror['user_password'] = 'Passwort und Passwort Wiederholung sind nicht identisch';
			}
			
			
			if(isset($_POST['user_profile_3']) && is_array($_POST['user_profile_3'])) {
				foreach($_POST['user_profile_3'] as $key => $value) {
					$udata['user_profile_3'][$key] = clean_slweg($value);
				}
			}
			
			if(isset($_POST['user_profile_4']) && is_array($_POST['user_profile_4'])) {
				foreach($_POST['user_profile_4'] as $key => $value) {
					$udata['user_profile_4'][$key] = clean_slweg($value);
				}
			}
			
			if(isset($_POST['user_profile_7']) && is_array($_POST['user_profile_7'])) {
				foreach($_POST['user_profile_7'] as $key => $value) {
					$udata['user_profile_7'][$key] = intval($value);
				}
			}
			
			if(isset($_POST['user_profile_12']) && is_array($_POST['user_profile_12'])) {
				foreach($_POST['user_profile_12'] as $key => $value) {
					$udata['user_profile_12'][$key] = clean_slweg($value);
				}
			}
			
			
			$fe_csv['Data-1'] 		= empty($udata['user_profile_1']) ? 'Nein' : 'Ja';
			$fe_csv['Data-2'] 				= $udata['user_profile_2'];
			$fe_csv['Data-3'] 		= implode(', ', $udata['user_profile_3']);
			$fe_csv['Data-4'] 			= implode(', ', $udata['user_profile_4']);
			$fe_csv['Data-5'] 				= empty($udata['user_profile_5']) ? 'Nein' : 'Ja';
			$fe_csv['Data-6'] 		= $udata['user_profile_6'];
			$fe_csv['Data-7'] 				= empty($udata['user_profile_7'][0]) ? 'Nein' : 'Ja';
			$fe_csv['Data-8']	= empty($udata['user_profile_7'][1]) ? 'Nein' : 'Ja';
			$fe_csv['Data-9'] 				= $udata['user_profile_8'];
			$fe_csv['Data-10'] 			= empty($udata['user_profile_7'][3]) ? 'Nein' : 'Ja';
			$fe_csv['Data-11'] 			= empty($udata['user_profile_7'][4]) ? 'Nein' : 'Ja';
			$fe_csv['Data-12'] 			= $udata['user_profile_9'];
			$fe_csv['Data-13'] 			= $udata['user_profile_10'];
			$fe_csv['Data-14'] 						= $udata['user_profile_11'];
			$fe_csv['Data-15'] 				= implode(', ', $udata['user_profile_12']);
			$fe_csv['Data-16'] 		= str_replace(array('+','-'), array('> ', '< '), $udata['user_profile_13']);
			$fe_csv['Data-17'] 			= $udata['user_profile_14'];
			
			
		} else {
		
			$udata['user_login'] = $_SESSION[ $_loginData['session_key'].'_userdata']['login'];
			
			if( !empty($udata['user_password']) && strlen($udata['user_password']) < 4 ) {
				$uerror['user_password'] = 'Passwort muss mindestens 4 Zeichen lang sein';
			} elseif( $udata['user_password'] !== $udata['user_password2'] ) {
				$uerror['user_password'] = 'Passwort und Passwort Wiederholung sind nicht identisch';
			}
			
			$udata['user_profile_7'][3] = empty($_POST['user_profile_7'][3]) ? 0 : 1;
					
		}
		
		$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_userdetail WHERE ";
		$sql .= "detail_login != '" . aporeplace($udata['user_login']) . "' AND ";
		$sql .= "detail_email = '" . aporeplace(strtolower($udata['user_email']))."'";
		
		if( empty($udata['user_email']) ) {
			$uerror['user_email'] = 'E-Mail muss ausgef&uuml;llt werden';
		} elseif( !is_valid_email($udata['user_email']) ) {
			$uerror['user_email'] = 'E-Mail muss valide sein';
		} elseif( _dbCount( $sql )	) {
			$uerror['user_email'] = 'E-Mail bereits registriert';
		}
		
		if( empty($udata['user_tel']) ) {
			$uerror['user_tel'] = 'Telefon muss ausgef&uuml;llt werden';
		} elseif( preg_match('/[^0-9\+\-\(\) ]/', $udata['user_tel']) ) {
			$uerror['user_tel'] = 'Telefonnummer darf nur Zahlen, Leerzeichen, Klammern, + oder - enthalten';
		}
		
		if( empty($udata['user_name']) ) {
			$uerror['user_name'] = 'Name muss ausgef&uuml;llt werden';
		}
		
		if( empty($udata['user_firstname']) ) {
			$uerror['user_firstname'] = 'Vorname muss ausgef&uuml;llt werden';
		}
		if( empty($udata['user_firstname']) ) {
			$uerror['user_firstname'] = 'Vorname muss ausgef&uuml;llt werden';
		}
		if( empty($udata['user_company']) ) {
			$uerror['user_company'] = 'Firma muss ausgef&uuml;llt werden';
		}
		if( empty($udata['user_street']) ) {
			$uerror['user_street'] = 'Stra&szlig;e muss ausgef&uuml;llt werden';
		}
		if( empty($udata['user_zip']) || empty($udata['user_city']) ) {
			$uerror['user_zip'] = 'PLZ und Ort m&uuml;ssen ausgef&uuml;llt werden';
		}

	
	}
	
	$fe_reg = array();	
	
	if($fe_action == '{FE_USER_REGISTER}') {
	
		$fe_reg[] = '<p>Text</p>';
		
	} else {
	
		$fe_reg[] = '<p>Text</p>';
	
	}
	
	$fe_reg[] = '<form action="' .$_uri. '" method="post">';
	
	// Fieldset 1 -> login basics
	$fe_reg[] = '<fieldset>';
	$fe_reg[] = '<legend> Login Data </legend>';
	
	$fe_reg[] = is_uerror('user_login');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_login">Login</label>';
	if($fe_action == '{FE_USER_REGISTER}') {
		$fe_reg[] = '<input type="text" name="user_login" id="user_login" value="' .html_specialchars($udata['user_login']). '" class="textfield" maxlength="200" size="30" />';
	} else {
		$fe_reg[] = '<strong>' .html_specialchars($udata['user_login']). '</strong>';
		$fe_reg[] = '<input type="hidden" name="user_login" value="' .html_specialchars($udata['user_login']). '" />';
	}
	$fe_reg[] = '</p>';

	$fe_reg[] = is_uerror('user_password');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_password">Password</label>';
	$fe_reg[] = '<input type="password" name="user_password" id="user_password" class="textfield" maxlength="20" size="30" />';
	$fe_reg[] = '</p>';

	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_password2">Password repeat</label>';
	$fe_reg[] = '<input type="password" name="user_password2" id="user_password2" class="textfield" maxlength="20" size="30" />';
	$fe_reg[] = '</p>';
	$fe_reg[] = '</fieldset>';
	
	$fe_reg[] = '<fieldset>';
	$fe_reg[] = '<legend> Adress information </legend>';
	
	$fe_reg[] = is_uerror('user_company');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_company">Company</label>';
	$fe_reg[] = '<input type="text" name="user_company" id="user_company" value="' .html_specialchars($udata['user_company']). '" class="textfield" maxlength="200" size="30" />';
	$fe_reg[] = '</p>';
	
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos">Title</label>';
	foreach($user_title as $key => $value) {
		$fe_reg['title'.$key]  = '<input type="radio" name="user_title" id="title'.$key.'" value="' ;
		$fe_reg['title'.$key] .= html_specialchars($value) . '"'.is_checked($value, $udata['user_title'], 1, 0).' />';
		if($value) {
			$fe_reg['title'.$key] .= '<label class="inline" for="title'.$key.'">' . html_specialchars($value) . '</label>';
		} else {
			$fe_reg['title'.$key] .= '<label class="inline" for="title'.$key.'">no title</label>';
		}
	}
	$fe_reg[] = '</p>';

	$fe_reg[] = is_uerror('user_firstname');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_firstname">First name</label>';
	$fe_reg[] = '<input type="text" name="user_firstname" id="user_firstname" value="' .html_specialchars($udata['user_firstname']). '" class="textfield" maxlength="200" size="30" />';
	$fe_reg[] = '</p>';

	$fe_reg[] = is_uerror('user_name');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_name">Name</label>';
	$fe_reg[] = '<input type="text" name="user_name" id="user_name" value="' .html_specialchars($udata['user_name']). '" class="textfield" maxlength="200" size="30" />';
	$fe_reg[] = '</p>';
	
	$fe_reg[] = is_uerror('user_street');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_street">Street</label>';
	$fe_reg[] = '<input type="text" name="user_street" id="user_street" value="' .html_specialchars($udata['user_street']). '" class="textfield" maxlength="200" size="30" />';
	$fe_reg[] = '</p>';
	
	$fe_reg[] = is_uerror('user_zip');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos">Post code, city</label>';
	$fe_reg[] = '<input type="text" name="user_zip" id="user_zip" value="' .html_specialchars($udata['user_zip']). '" class="textfield_zip" maxlength="5" size="5" />';
	$fe_reg[] = '<input type="text" name="user_city" id="user_city" value="' .html_specialchars($udata['user_city']). '" class="textfield_city" maxlength="200" size="25" />';
	$fe_reg[] = '</p>';

	$fe_reg[] = '</fieldset>';
	
	$fe_reg[] = '<fieldset>';
	$fe_reg[] = '<legend> Kommunikation </legend>';

	$fe_reg[] = is_uerror('user_tel');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_tel">Phone</label>';
	$fe_reg[] = '<input type="text" name="user_tel" id="user_tel" value="' .html_specialchars($udata['user_tel']). '" class="textfield" maxlength="200" size="30" />';
	$fe_reg[] = '</p>';	
	
	$fe_reg[] = is_uerror('user_email');
	$fe_reg[] = '<p>';
	$fe_reg[] = '<label class="labelpos" for="user_email">Email</label>';
	$fe_reg[] = '<input type="text" name="user_email" id="user_email" value="' .html_specialchars($udata['user_email']). '" class="textfield" maxlength="200" size="30" />';
	$fe_reg[] = '</p>';	
	$fe_reg[] = '</fieldset>';


	if($fe_action == '{FE_USER_REGISTER}') {


		$fe_reg[] = '<fieldset class="profile">';
		$fe_reg[] = '<legend> Infos zu HP ProCurve </legend>';

		//$fe_reg[] = is_uerror('user_profile_1');
		$fe_reg[] = '';
		$fe_reg[] = '<p class="title">Text?</p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_1" value="1"'.is_checked(1, $udata['user_profile_1'], 1, 0).' />' .
					'Ja</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_1" value="0"'.is_checked(0, $udata['user_profile_1'], 1, 0).' />' .
					'Nein</label>';
		$fe_reg[] = '';
		
		//$fe_reg[] = is_uerror('user_profile_2');
		$fe_reg[] = '<p>';
		$fe_reg[] = '<p class="title">Text?</p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_2" value="1"'.is_checked('SMB', $udata['user_profile_2'], 1, 0).' />' .
					'1</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_2" value="2"'.is_checked('Enterprise', $udata['user_profile_2'], 1, 0).' />' .
					'2</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_2" value="3"'.is_checked('Beides', $udata['user_profile_2'], 1, 0).' />' .
					'3</label>';
		$fe_reg[] = '</p>';
		
		//$fe_reg[] = is_uerror('user_profile_3');
		$fe_reg[] = '<p>';
		$fe_reg[] = '<p class="title">Text?</p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_3[cisco]" value="1"'.is_checked(1, isset($udata['user_profile_3']['cisco']) ? 1 : 0 , 1, 0).' />' .
					'1</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_3[nortel]" value="2"'.is_checked(1, isset($udata['user_profile_3']['nortel']) ? 1 : 0 , 1, 0).' />' .
					'2</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_3[keine]" value="none"'.is_checked(1, isset($udata['user_profile_3']['keine']) ? 1 : 0 , 1, 0).' />' .
					'None</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_3[andere]" value="other"'.is_checked(1, isset($udata['user_profile_3']['andere']) ? 1 : 0 , 1, 0).' />' .
					'Other</label> <input type="text" name="user_profile_3[andere_text]" value="' .
					(isset($udata['user_profile_3']['andere_text']) ? html_specialchars($udata['user_profile_3']['andere_text']) : '') . 
					'" size="15" maxlength="100" class="textfield inline" />';
	
		$fe_reg[] = '</p>';
		
		
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<p class="title">Vertreiben Sie andere Technologien von HP?</p>';
		$fe_reg[] = 'Ja &#8211; <label class="inline">' .
					'<input type="checkbox" name="user_profile_4[server]" value="Server"'.is_checked(1, isset($udata['user_profile_4']['server']) ? 1 : 0 , 1, 0).' />' .
					'Server</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_4[storage]" value="Storage"'.is_checked(1, isset($udata['user_profile_4']['storage']) ? 1 : 0 , 1, 0).' />' .
					'Storage</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_4[software]" value="Software"'.is_checked(1, isset($udata['user_profile_4']['software']) ? 1 : 0 , 1, 0).' />' .
					'Software</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_4[andere]" value="Andere"'.is_checked(1, isset($udata['user_profile_4']['andere']) ? 1 : 0 , 1, 0).' />' .
					'Andere</label> <input type="text" name="user_profile_4[andere_text]" value="' .
					(isset($udata['user_profile_4']['andere_text']) ? html_specialchars($udata['user_profile_4']['andere_text']) : '') . 
					'" size="15" maxlength="100" class="textfield inline" />';
	
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '</fieldset>';
	
	
		$fe_reg[] = '<fieldset class="profile">';
		$fe_reg[] = '<legend> Infos zum Partnerstatus </legend>';
	
		$fe_reg[] = '';
		$fe_reg[] = '<p class="title">Sind Sie bereits HP Vertriebspartner?</p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_5" value="1"'.is_checked(1, $udata['user_profile_5'], 1, 0).' />' .
					'Ja</label>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_5" value="0"'.is_checked(0, $udata['user_profile_5'], 1, 0).' />' .
					'Nein</label>';
		$fe_reg[] = '';
		
		$fe_reg[] = '<p>';
		$fe_reg[] = 'Wenn Ja, welcher Status';
		$fe_reg[] = '<input type="text" name="user_profile_6" value="' . html_specialchars($udata['user_profile_6']) . '" size="15" maxlength="150" class="textfield inline" />';
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '</fieldset>';
	
	}
	
		$fe_reg[] = '<fieldset class="profile_info">';
		$fe_reg[] = '<legend> Informationen </legend>';
	
	if($fe_action == '{FE_USER_REGISTER}') {
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_7[0]" value="1"'.is_checked(1, empty($udata['user_profile_7'][0]) ? 0 : 1 , 1, 0).' />' .
					'Text.</label>';
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<p>';				
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_7[1]" value="1"'.is_checked(1, empty($udata['user_profile_7'][1]) ? 0 : 1 , 1, 0).' />' .
					'Text.</label>';
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<p>';				
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_7[2]" value="1"'.is_checked(1, empty($udata['user_profile_7'][2]) ? 0 : 1 , 1, 0).' />' .
					'Ich habe noch Fragen zu</label> '.
					'<input type="text" name="user_profile_8" value="' . html_specialchars($udata['user_profile_8']) . '" size="15" maxlength="200" class="textfield inline" />';
		$fe_reg[] = '</p>';
		
	}
	
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_7[3]" value="1"'.is_checked(1, empty($udata['user_profile_7'][3]) ? 0 : 1 , 1, 0).' />' .
					'Text.</label>';
		$fe_reg[] = '</p>';
	
	if($fe_action == '{FE_USER_REGISTER}') {
	
		$fe_reg[] = '<p>';	
		$fe_reg[] = '<label class="inline">' .
					'<input type="checkbox" name="user_profile_7[4]" value="1"'.is_checked(1, empty($udata['user_profile_7'][4]) ? 0 : 1 , 1, 0).' />' .
					'<strong>I want a login.</strong></label>';
		$fe_reg[] = '</p>';
	
	}	
	
		$fe_reg[] = '</fieldset>';
	
	if($fe_action == '{FE_USER_REGISTER}') {
	
		$fe_reg[] = '<fieldset class="profile_info">';
		$fe_reg[] = '<legend> Weitere Angaben </legend>';
	
		$fe_reg[] = '<p class="title">';
		$fe_reg[] = 'Wie lässt sich Ihr Geschäftsbereich am besten beschreiben?';
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_9" value="Systemhaus"'.is_checked('Systemhaus', $udata['user_profile_9'], 1, 0).' />' .
					'Systemhaus</label>';
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_9" value="Value Added Reseller"'.is_checked('Value Added Reseller', $udata['user_profile_9'], 1, 0).' />' .
					'Value Added Reseller</label>';
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_9" value="Reseller"'.is_checked('Reseller', $udata['user_profile_9'], 1, 0).' />' .
					'Reseller</label>';
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_9" value="Anderes"'.is_checked('Anderes', $udata['user_profile_9'], 1, 0).' />' .
					'Anderes</label> <input type="text" name="user_profile_10" value="' . html_specialchars($udata['user_profile_10']) . 
					'" size="15" maxlength="200" class="textfield inline" />';
		$fe_reg[] = '</p>';
		
	
		$fe_reg[] = '<p class="title">';
		$fe_reg[] = 'Wieviele Mitarbeiter beschäftigt Ihr Unternehmen?';
		$fe_reg[] = '</p>';
	
		foreach(array('weniger als 15', '15 - 49', '50 – 149', '150 – 499', '500 – 999', 'mehr als 1.000') as $value) {
		
			$fe_reg[] = '<p>';
			$fe_reg[] = '<label class="inline">' .
						'<input type="radio" name="user_profile_11" value="'.$value.'"'.is_checked($value, $udata['user_profile_11'], 1, 0).' />' . $value .'</label>';
			$fe_reg[] = '</p>';
		
		}
		
		
		$fe_reg[] = '<p class="title">';
		$fe_reg[] = 'In welchen Branchen sind Ihre Kunden hauptsächlich tätig?';
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<p>';
		foreach(array(	'Automotive', 'Banken & Versicherungen', 'Energie', 
						'ITK', 'Großhandel', 'Einzelhandel', 'Fertigung', 
						'Gesundheit', 'Medien', 'Öffentliche Einrichtungen', 
						'Transport/Logistik', 'Dienstleistungen', 'Sonstiges') as $key => $value) {
		
			$fe_reg[] = '<label class="column" style="float:left;width:200px;">' .
						'<input type="checkbox" name="user_profile_12['.$key.']" value="'.html_specialchars($value).'"' .
						is_checked(1, isset($udata['user_profile_12'][$key]) ? 1 : 0, 1, 0) .' />' .
						html_specialchars($value) .'</label>';
		
		}
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<div style="clear:both"></div><p class="title">';
		$fe_reg[] = 'Wer sind Ihre Kunden vorwiegend?';
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_13" value="+500"'.is_checked('+500', $udata['user_profile_13'], 1, 0).' />' .
					'Gro&szlig;unternehmen (&gt; 500 MA)</label>';
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_13" value="-500"'.is_checked('-500', $udata['user_profile_13'], 1, 0).' />' .
					'Mittelstand (&lt; 500 MA)</label>';
		$fe_reg[] = '</p>';
		
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_13" value="-20"'.is_checked('-20', $udata['user_profile_13'], 1, 0).' />' .
					'Kleinunternehmen (&lt; 20 MA)</label>';
		$fe_reg[] = '</p>';
	
		$fe_reg[] = '<p>';
		$fe_reg[] = '<label class="inline">' .
					'<input type="radio" name="user_profile_13" value="Andere"'.is_checked('Andere', $udata['user_profile_13'], 1, 0).' />' .
					'Andere</label> <input type="text" name="user_profile_14" value="' . html_specialchars($udata['user_profile_14']) . 
					'" size="15" maxlength="200" class="textfield inline" />';
		$fe_reg[] = '</p>';
	
	
		$fe_reg[] = '</fieldset>';

	}

	
	
	$fe_reg[] = '<p>';
	$fe_reg[] = '<input type="submit" value="Senden" class="button" />';
	$fe_reg[] = '</p>';




	$fe_reg[] = '</form>';

	$fe_reg = implode(LF, $fe_reg);
	
	
	if(isset($_POST['user_login']) && $fe_action == '{FE_USER_REGISTER}') {
		if($uerror['status']) {
		
			$fe_reg = '<p class="error">Es sind Fehler bei der Verarbeitung des Formulars aufgetreten. Bitte pr&uuml;fen Sie Ihre Angaben.</p>' . LF . $fe_reg;
		
		} else {
		
			$profile_data = $udata;
			unset($profile_data['user_password'], $profile_data['user_password2']);
		
			$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_userdetail (';
			$sql .= 'detail_title, detail_firstname, detail_lastname, detail_company, detail_street, detail_city, detail_zip, ';
			$sql .= 'detail_fon, detail_notes, detail_aktiv, detail_newsletter, detail_varchar1, detail_email, detail_login, detail_password) VALUES (';
			$sql .= "'" . aporeplace($udata['user_title']) . "', ";
			$sql .= "'" . aporeplace($udata['user_firstname']) . "', ";
			$sql .= "'" . aporeplace($udata['user_name']) . "', ";
			$sql .= "'" . aporeplace($udata['user_company']) . "', ";
			$sql .= "'" . aporeplace($udata['user_street']) . "', ";
			$sql .= "'" . aporeplace($udata['user_city']) . "', ";
			$sql .= "'" . aporeplace($udata['user_zip']) . "', ";
			$sql .= "'" . aporeplace($udata['user_tel']) . "', ";
			$sql .= "'" . aporeplace(serialize($profile_data)) . "', ";
			$sql .= "'0', ";
			$sql .= "'" . ( empty($udata['user_profile_7'][3]) ? '' : 1 ) . "', ";
			$sql .= "'fereg', ";
			$sql .= "'" . aporeplace(strtolower($udata['user_email'])) . "', ";
			$sql .= "'" . aporeplace($udata['user_login']) . "', ";
			$sql .= "'" . aporeplace(md5($udata['user_password'])) . "')";
			
			$queryResult = _dbQuery($sql, 'INSERT');
			if(!empty($queryResult['INSERT_ID'])) {
				$fe_reg  = '<p class="success">Vielen Dank '.html_specialchars($udata['user_firstname'].' '.$udata['user_name']).'! Ihre Registrierungsanfrage wurden erfolgreich &uuml;bertragen.</p>';
				$fe_reg .= '<p>Ihnen wird in wenigen Augenblicken eine Bestätigung an die E-Mail <b>'.html_specialchars($udata['user_email']).'</b> zugesendet.</p>';
				
				$fe_text  = 'Hallo '.trim($udata['user_title'] . ' ' . trim( $udata['user_firstname'].' '.$udata['user_name']) ) . LF . LF;
				$fe_text .= 'Ihre Registrierung haben wir erhalten.' . LF;
				$fe_text .= 'Wir prüfen Ihre Daten und melden uns umgehend bei Ihnen.' . LF . LF;
				
				if(empty($udata['user_profile_7'][4])) {
					$fe_text .= 'Sie möchten keinen Zugriff auf unser Partnerbackend. ' .LF . 'Allerdings haben wir folgende Zugangsdaten für Sie hinterlegt:' . LF;
				} else {
					$fe_text .= 'Sie möchten Zugriff auf unser Partnerbackend. ' .LF . 'Folgende Zugangsdaten sind von Ihnen gesendet worden:' . LF;
				}
				$fe_text .= '  Login:    ' . $udata['user_login'] . LF;
				$fe_text .= '  Passwort: ' . $udata['user_password'] . LF . LF;
				$fe_text .= 'Ihr Passwort ist nicht reproduizierbar verschlüsselt in unserem System abgelegt worden.' . LF . LF . LF;
				$fe_text .= 'Mit besten Grüßen' . LF;
				$fe_text .= 'phpwcms, Oliver' . LF;
				
				$fe_text1  = 'Neue Benutzerregistrierung' . LF;
				$fe_text1 .= '--------------------------' . LF . LF;
				
				$fe_text1 .= 'Die Benutzerdaten können im Backend eingesehen werden.' . LF;
				
				if(!empty($udata['user_profile_7'][4])) {
					$fe_text1 .= 'Der Benutzer wünscht die Freischaltung für den Partnerbereich!' .LF;
					$fe_text1 .= '  Login:    ' . $udata['user_login'] . LF;
				}
				
				$fe_text1 .= LF;
				$fe_text1 .= 'Benutzerangaben:' . LF;
				$fe_text1 .= '================' . LF . LF;
				
				$fe_text1 .= 'Firma:   ' . $udata['user_company'] . LF;
				$fe_text1 .= 'Anrede:  ' . $udata['user_title'] . LF;
				$fe_text1 .= 'Vorname: ' . $udata['user_firstname'] . LF;
				$fe_text1 .= 'Name:    ' . $udata['user_name'] . LF;
				$fe_text1 .= 'Straße:  ' . $udata['user_street'] . LF;
				$fe_text1 .= 'PLZ:     ' . $udata['user_zip'] . LF;
				$fe_text1 .= 'Ort:     ' . $udata['user_city'] . LF;
				$fe_text1 .= 'Telefon: ' . $udata['user_tel'] . LF;
				$fe_text1 .= 'E-Mail:  ' . $udata['user_email'] . LF;
	
				$fe_text1 .= LF . '-----------------------------------------------------------' . LF;
				$fe_text1 .= 'IP: '. getRemoteIP();
				
				$fe_csv_attach  = implode(';', array_keys($fe_csv) );
				$fe_csv_attach .= LF;
				$fe_csv_attach .= implode(';', $fe_csv );
				
				$fe_csv = array();
				
				$fe_csv['filename']	= date('Y-m-d_H-i-s') . '_' . preg_replace('/[^a-zA-Z0-9\-_]/', '', $udata['user_login']).'.csv';
				$fe_csv['mime']		= 'text/csv';
				$fe_csv['data']		= $fe_csv_attach;

				sendEmail(array(
					'recipient'		=> strtolower($udata['user_email']),
					'toName'		=> trim($udata['user_firstname'].' '.$udata['user_name']),
					'subject'		=> 'phpwcms Registration',
					'text'			=> $fe_text,
					'from'			=> 'oliver@phpwcms.de',
					'fromName'		=> 'phpwcms',
					'sender'		=> 'oliver@phpwcms.de' ));
					
				sendEmail(array(
					'recipient'		=> 'slackero+phpwcms-registration@gmail.com',
					'subject'		=> 'New registration',
					'text'			=> $fe_text1,
					'from'			=> strtolower($udata['user_email']),
					'fromName'		=> trim($udata['user_firstname'].' '.$udata['user_name']),
					'sender'		=> strtolower($udata['user_email']),
					'stringAttach'	=> array($fe_csv) ));
				
				
			} else {
				$fe_reg = '<p class="error">Beim Speichern Ihrer Daten ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut oder wenden Sie sich an den Webmaster.</p>' . LF . $fe_reg;
			}
		}
	}
	
	if(isset($_POST['user_login']) && $fe_action == '{FE_USER_MANAGE}') {
	
		if($uerror['status']) {
		
			$fe_reg = '<p class="error">Es sind Fehler bei der Verarbeitung des Formulars aufgetreten. Bitte pr&uuml;fen Sie Ihre Angaben.</p>' . LF . $fe_reg;
		
		} else {
		
			$profile_data = $udata;
			unset($profile_data['user_password'], $profile_data['user_password2']);
		
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_userdetail SET ';
			$sql .= "detail_title		= '".aporeplace($udata['user_title'])."', ";
			$sql .= "detail_firstname	= '".aporeplace($udata['user_firstname'])."', ";
			$sql .= "detail_lastname	= '".aporeplace($udata['user_name'])."', ";
			$sql .= "detail_company		= '".aporeplace($udata['user_company'])."', ";
			$sql .= "detail_street		= '".aporeplace($udata['user_street'])."', ";
			$sql .= "detail_city		= '".aporeplace($udata['user_city'])."', ";
			$sql .= "detail_zip			= '".aporeplace($udata['user_zip'])."', ";
			$sql .= "detail_fon			= '".aporeplace($udata['user_tel'])."', ";
			$sql .= "detail_notes		= '".aporeplace(serialize($profile_data))."', ";
			$sql .= "detail_newsletter	= '".( empty($udata['user_profile_7'][3]) ? '' : 1 )."', ";
			if($udata['user_password']) {
				$sql .= "detail_password	= '".aporeplace(md5($udata['user_password']))."', ";
			}
			$sql .= "detail_email		= '".aporeplace(strtolower($udata['user_email']))."' ";
			$sql .= 'WHERE detail_id=' . intval($_SESSION[ $_loginData['session_key'].'_userdata']['id']).' LIMIT 1';
			
			$queryResult = _dbQuery($sql, 'UPDATE');
			if(isset($queryResult['AFFECTED_ROWS'])) {
		
				$fe_reg = '<p>Ihre Profildaten wurden erfolgreich aktualisiert</p>' . LF . $fe_reg;
		
			} else {
			
				$fe_reg = '<p class="error">Leider konnten Ihre Anfgaben nicht in der Datenbank gesichert werden. Bitte pr&uuml;fen Sie Ihre Angaben oder wenden Sie sich an den Systemadministrator.</p>' . LF . $fe_reg;
			
			}
		
		}
	
	}

	$content['all'] = str_replace($fe_action, $fe_reg, $content['all']);

} else {

	$content['all'] = str_replace('{FE_USER_MANAGE}', '<p class="error">Diese Aktion ist leider nicht zul&auml;ssig</p>', $content['all']);

}

function is_uerror($field='') {
	global $uerror;
	if(!empty($uerror[$field])) {
		$uerror['status'] = true;
		return '<p class="error">' . $uerror[$field] . '</p>';
	}
	return '';
}


?>