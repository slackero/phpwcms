<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

?>
<h1><span class="number">3.</span> MySQL database settings </h1>
<?php
if(isset($_POST["dbsavesubmit"]) && $err) {
	echo errorWarning('Please proof your database settings!');
	$_SESSION['admin_set'] = false;
}
?>
<form action="setup.php?step=1" method="post" autocomplete="off">
        <table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td align="right" class="v10" width="120">MySQL host:&nbsp;</td>
            <td width="270"><input name="db_host" type="text" class="v12" id="db_host" value="<?php echo html_specialchars($phpwcms["db_host"]) ?>" size="30" style="width:250px" /></td>
            <td class="chatlist"><em>default: localhost</em></td>
          </tr>
		   <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
		   </tr>
          <tr>
            <td align="right" class="v10">DB user:&nbsp;</td>
            <td><input name="db_user" type="text" class="v12" id="db_user" style="width:250px" value="<?php echo html_specialchars($phpwcms["db_user"]) ?>" size="30" /></td>
            <td class="chatlist">&nbsp;</td>
          </tr>
		   <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
		   </tr>
          <tr>
            <td align="right" class="v10">DB password:&nbsp;</td>
            <td><input name="db_pass" type="text" class="v12" id="db_pass" style="width:250px" value="<?php echo html_specialchars($phpwcms["db_pass"]) ?>" size="30" /></td>
            <td class="chatlist">&nbsp;</td>
          </tr>
		   <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
		   </tr>
          <tr>
            <td align="right" class="v10">DB database:&nbsp;</td>
            <td><input name="db_table" type="text" class="v12" id="db_table" style="width:250px" value="<?php echo html_specialchars($phpwcms["db_table"]) ?>" size="30" maxlength="255" /></td>
            <td class="chatlist"><em>you have to create it <strong>before</strong> setup!!!</em></td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
		  </tr>
          <tr>
            <td align="right" class="v10">DB table prefix:&nbsp;</td>
            <td><input name="db_prepend" type="text" class="v12" id="db_prepend" style="width:250px" value="<?php echo html_specialchars($prepend) ?>" size="30" maxlength="10" /></td>
            <td class="chatlist"><em>default: none (&quot;&quot;), if filled
                in it will be <strong>prefix</strong>+<strong>_</strong></em></td>
          </tr>
          <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="6" /></td>
          </tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
				<tr>
				<td><input name="db_pers" type="checkbox" id="db_pers" value="1" <?php echo ($phpwcms["db_pers"]) ? "checked" : ""; ?> /></td>
				<td><label for="db_pers" class="v12">&nbsp;use&nbsp;persistent&nbsp;database&nbsp;connection&nbsp;</label></td>
				</tr>
			</table></td>
            <td class="chatlist"><em>recommend setting is to enable it</em></td>
          </tr>

<?php
if(!empty($db_additional)) {

?>
	<tr><td colspan="3" style="padding: 10px 0 10px 0;"><h1><span class="number">4.</span> Charset &amp; MySQL <span class="v11">(v<?php echo html_specialchars($row[0]) ?>)</span> collation
	      settings <a href="http://dev.mysql.com/doc/refman/4.1/en/charset.html" target="_blank" title="MySQL information"><img src="../img/famfamfam/icon_info.gif" alt="Info" border="0" class="icon" /></a></h1></td></tr>
	<tr>
            <td align="right" class="v10"><a href="http://www.w3.org/International/O-HTTP-charset" target="_blank" title="HTTP charset"><img src="../img/famfamfam/icon_info.gif" alt="Info" border="0" class="icon1" /></a>Charset (recommend: UTF8):&nbsp;</td>
            <td colspan="2"><select name="charset">
			
			<?php
			
			if($phpwcms['db_version'] < 40100) {
				$_availableCharsets = _dbQuery("SHOW VARIABLES LIKE 'character_sets'", 'ROW');
				if(isset($_availableCharsets[0][1])) {
					$_availableCharsets = $_availableCharsets[0][1];
					$_availableCharsets = str_replace('_', '', $_availableCharsets);
					$_availableCharsets = strtolower($_availableCharsets);
					$_availableCharsets = explode(' ', $_availableCharsets);
				}
			}
			
		
			foreach($available_languages as $key => $value) {
			
				list(, $_lang_charset)	= explode('-', $value[1], 2);
				list(, $_lang_en)	 	= explode('|', $value[0]);
				
				if($phpwcms['db_version'] < 40100 && !in_array($mysql_charset_map[$_lang_charset], $_availableCharsets)) continue;
			
				echo '<option value="'.$key.'"';
				
				if($key == strtolower(str_replace('-', '', $phpwcms['default_lang'])  .'-'. $phpwcms['charset'])) {
					echo ' selected="selected"';
				}
				
				echo '>';
				echo empty($value[3]) ? '' : $value[3].' - ';
				echo ucfirst($_lang_en);
				echo ' ['.$_lang_charset;
				if(!empty($mysql_charset_map[$_lang_charset])) {
					echo ' / '.$mysql_charset_map[$_lang_charset];
				}
				echo ']';
				echo "</option>\n";
			
			
			}
			
			?>
			</select></td>
          </tr>
<?php

	// collation setting 
	if($phpwcms['db_version'] > 40100) {
		
		$_collation 			= array();
		$_collation_selected	= false;
		
		echo '<tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="6"></td></tr>';
		
		
		// make db connect
		if(empty($db)) {
			$db = @mysql_connect($phpwcms["db_host"], $phpwcms["db_user"], $phpwcms["db_pass"]);
			@mysql_select_db($phpwcms["db_table"], $db);
		}	
		if($result = mysql_query("SHOW COLLATION", $db)) {
		
			while($row = mysql_fetch_assoc($result)) {
		
				if(in_array($row['Charset'], $mysql_charset_map)) {
					if($phpwcms['db_collation']==$row['Collation']) {
						$_collation_selected = true;
						$_collation[ $row['Charset'] ][ $row['Collation'] ] = true;
					} else {
						$_collation[ $row['Charset'] ][ $row['Collation'] ] = false;
					}
				}
		
			}
			
			ksort($_collation);
		
		}
		
		
		// warn again
		if(isset($_collation_warning) && $_collation_warning === true) {
		
			echo '<tr><td>&nbsp;</td><td colspan="2">'.errorWarning('Proof collation again!').'</td></tr>';
			$_SESSION['admin_set'] = false;
		
		}
		

?>
		
		<tr>
            <td align="right" class="v10">MySQL Collation (recommend: utf8_general_ci):&nbsp;</td>
            <td colspan="2"><select name="collation">
<?php
			
		foreach($_collation as $key => $value) {
		
			ksort($value);
			
			echo '		<optgroup label="'.$key.'" style="margin-bottom:5px;">'. "\n";
			
			foreach($value as $colkey => $status) {
		
				echo '			<option value="'.$colkey.'"';
				if($status) {
					echo ' selected="selected" style="color:#009900;font-weight:bold;"';
				}
				echo '>'.$colkey.'</option>'. "\n";
		
			}
			
			echo '		</optgroup>' . "\n";
		
		}
			
			
			
?>
			</select></td>
          </tr>
		  
		  

<?php

	}

}

// now show setting which enables creating database
if(!empty($db_init)) {

?>
	<tr><td colspan="3" style="padding: 10px 0 10px 0;"><h1><span class="number">5.</span> Default phpwcms database schema</h1></td></tr>
<?php



	if(empty($db_no_create) && !empty($_db_prepend_error) && isset($_POST['db_sql_hidden'])) {
		echo '<tr><td>&nbsp;</td><td colspan="2">';
		echo errorWarning('phpwcms tables still exists in choosen database. Rename table prefix might help!');
		echo "</td></tr>\n";
		$_SESSION['admin_set'] = false;
	}
	if(isset($db_create_err) && count($db_create_err)) {
		echo '<tr><td>&nbsp;</td><td colspan="2">';
		echo errorWarning('Errors while creating initial phpwcms tables. Solve it manually:</b></p><pre class="errorBox">'.html_specialchars(implode(";\n\n", $db_create_err).';').'</pre><p><b> ');
		echo "</td></tr>\n";
		
		$_SESSION['admin_set']	= false;
		$sql_data				= false;
		$db_sql					= false;
	
	} elseif(isset($db_create_err) || !empty($db_no_create)) {
	
		// OK fine - initial tables were created without error
		$_db_prepend = ($phpwcms["db_prepend"] ? $phpwcms["db_prepend"].'_' : '');
		$check = _dbQuery("SHOW TABLES LIKE '".$_db_prepend."phpwcms_%'");

		if($check && count($check)) {
		
			$sql_data				= false;
			$db_sql					= false;
			$db_fine				= true;
			
?>
	  <tr>
		<td align="right" class="v10">&nbsp;</td>
		<td colspan="2"><img src="../img/famfamfam/icon_accept.gif" alt="Juchu" class="icon1" /><strong>Fine!</strong> All initial phpwcms tables were created or still exists.<input type="hidden" name="db_sql_hidden" value="1" /></td>
	  </tr>
<?php
	  
	  	} else {
		
			$_SESSION['admin_set']	= false;
		
			$sql_data				= false;
			$db_sql					= false;
		
			echo '<tr><td>&nbsp;</td><td colspan="2">';
			echo errorWarning('No phpwcms database table exists. Check before you continue!');
			echo '<input type="hidden" name="db_sql_hidden" value="1" />';
			echo "</td></tr>\n";		
		
		}
	
	}
	
	
	if(empty($db_fine)) {
		// show info 
	
?>
	  <tr>
		<td align="right" class="v10">&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
			<td><input name="db_sql" type="checkbox" id="db_sql" value="1"<?php if(!empty($db_sql)) echo ' checked="checked"' ?> /></td>
			<td><label for="db_sql" class="v12">&nbsp;create phpwcms db tables&nbsp;</label><input type="hidden" name="db_sql_hidden" value="1" /></td>
			</tr>
		</table></td>
		<td class="chatlist">&nbsp;</td>
	  </tr>

<?php

	}

	if(!empty($sql_data)) {
	
		$sql_data = explode(';', $sql_data);
		$c = 0;
		foreach($sql_data as $key => $value) {
		
			$value = trim($value);
			
			if(empty($value)) {
				unset($sql_data[$key]);
				continue;
			}
			
			$value = html_specialchars($value);
			$value = str_replace(' ', '&nbsp;', $value);
			$value = nl2br($value);
			
			$sql_data[$key]  = '<div style="margin:0;padding:0 5px 0 5px;';
			if($c % 2) {
				$sql_data[$key] .= ';background-color:#F6F8FA;';
			}
			$sql_data[$key] .= '"><p>'.$value;
			
			if($phpwcms['db_version'] > 40100 && strpos(strtoupper(trim($value)), 'INSERT') !== 0) {
				$sql_data[$key] .= ' DEFAULT';
				$sql_data[$key] .= ' CHARACTER SET '.$phpwcms['db_charset'];
				$sql_data[$key] .= ' COLLATE '.$phpwcms['db_collation'];
			}
						
			$sql_data[$key] .= '&nbsp;;</p></div>';
			$c++;
		}
		
		$sql_data = implode("\n", $sql_data);
	
		echo '<tr><td>&nbsp;';
		if(empty($_db_prepend_error) && isset($_POST['db_sql_hidden'])) {
			echo '<input type="hidden" name="db_create" value="1" />';
		}
		echo '</td><td colspan="2">';
		echo '<div id="license" style="width:550px">';
		echo $sql_data;
		echo "</div></td></tr>\n";

	}
}


//	OK now lets create superuser
if(!empty($_SESSION['admin_set'])) {

?>
	<tr><td colspan="3" style="padding: 10px 0 10px 0;"><h1><span class="number">6.</span> Superuser settings</h1></td></tr>
<?php

	// as long as admin info wasn't written
	if(empty($_SESSION['admin_save'])) {

?>
	<tr>
		<td align="right" class="v10">Name:&nbsp;</td>
		<td><input name="admin_name" type="text" id="admin_name" class="v12" style="width:250px" value="<?php echo empty($phpwcms["admin_name"]) ? "Webmaster" : html_specialchars($phpwcms["admin_name"]) ?>" size="30" /></td>
		<td class="chatlist"><em>&nbsp;default: Webmaster</em></td>
	</tr>
	
	<tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
	</tr>	
	
	<tr>
		<td align="right" class="v10">Admin login:&nbsp;</td>
		<td><input name="admin_user" type="text" id="admin_user" class="v12" style="width:250px" value="<?php echo empty($phpwcms["admin_user"]) ? "webmaster" : html_specialchars($phpwcms["admin_user"]) ?>" size="30" /></td>
		<td class="chatlist"><em>&nbsp;default: admin </em></td>
	</tr>
	
	<tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td></tr>

<?php
	if(!empty($admin_err_pass)) {
		echo '<tr><td>&nbsp;</td><td colspan="2">';
		echo errorWarning('Invalid password! Password is case senitive, empty password not allowed.');
		echo "</td></tr>\n";
	}
?>

	<tr>
		<td align="right" class="v10">Admin password:&nbsp;</td>
		<td><input name="admin_pass" type="password" id="admin_pass" class="v12" style="width:250px" size="30" autocomplete="off" /></td>
		<td class="chatlist"><em>&nbsp;default: phpwcms </em></td>
	</tr>
	
	<tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
	</tr>
	
	<tr>
		<td align="right" class="v10">Repeat password:&nbsp;</td>
		<td><input name="admin_passrepeat" type="password" id="admin_passrepeat" class="v12" style="width:250px" size="30" autocomplete="off" /></td>
		<td class="chatlist"><em>&nbsp;</em></td>
	</tr>
	
	<tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4" /></td>
	</tr>
	
	<tr>
		<td align="right" class="v10">Admin email:&nbsp;</td>
		<td><input name="admin_email" type="text" id="admin_email" class="v12" style="width:250px" value="<?php echo html_specialchars($phpwcms["admin_email"]) ?>" size="30" /></td>
		<td class="chatlist"><em>&nbsp;is used site wide</em></td>
	</tr>
<?php

	} else {
	
		$_db_prepend = ($phpwcms["db_prepend"] ? $phpwcms["db_prepend"].'_' : '');
	
		//show Info that admin info was saved
		//and also if stored in database
		
		$user_check = _dbQuery('SELECT * FROM '.$_db_prepend."phpwcms_user WHERE usr_login='".aporeplace($phpwcms['admin_user'])."'");
		
		if($user_check !== false && count($user_check)) {
		
			//hm user still exists - so try to update
			$sql  = "UPDATE ".$_db_prepend."phpwcms_user SET ";
			$sql .= "usr_login		= '".aporeplace($phpwcms['admin_user'])."', ";
			$sql .= "usr_pass		= '".aporeplace($phpwcms["admin_pass"])."', ";
			$sql .= "usr_email		= '".aporeplace($phpwcms["admin_email"])."', ";
			$sql .= "usr_admin		= 1, ";
			$sql .= "usr_aktiv		= 1, ";
			$sql .= "usr_name		= '".aporeplace($phpwcms['admin_name'])."', ";
			$sql .= "usr_lang		= '".aporeplace($phpwcms['default_lang'])."', ";
			$sql .= "usr_wysiwyg	= 2, ";
			$sql .= "usr_fe			= 2 ";
			$sql .= "WHERE usr_login='".aporeplace($phpwcms['admin_user'])."' LIMIT 1";
			
			$update_user = _dbQuery($sql, 'UPDATE');


		} elseif($user_check !== false) {
		
			//fine lets create new user
			$sql  = "INSERT INTO ".$_db_prepend."phpwcms_user (";
			$sql .= "usr_login, usr_pass, usr_email, ";
			$sql .= "usr_admin, usr_aktiv, usr_name, ";
			$sql .= "usr_lang, usr_wysiwyg, usr_fe";
			$sql .= ") VALUES (";
			$sql .= "'".aporeplace($phpwcms['admin_user'])."', ";
			$sql .= "'".aporeplace($phpwcms["admin_pass"])."', ";
			$sql .= "'".aporeplace($phpwcms["admin_email"])."', ";
			$sql .= "1, 1, ";
			$sql .= "'".aporeplace($phpwcms['admin_name'])."', ";
			$sql .= "'".aporeplace($phpwcms['default_lang'])."', ";
			$sql .= "2, 2";
			$sql .= ")";
			
			$create_user = _dbQuery($sql, 'INSERT');
		
		
		}
		
		echo '<tr><td>&nbsp;</td><td colspan="2">';
		
		if(!empty($create_user)) {
		
			// update
			echo '<img src="../img/famfamfam/icon_accept.gif" alt="Juchu" class="icon1" />';
			echo '<strong>Done!</strong> Account for user <b>'.html_specialchars($phpwcms['admin_user']).'</b> was created.';
			echo '<input type="hidden" name="user_account" value="1" />';
		
		}
		
		if(!empty($update_user)) {
		
			// update
			echo '<img src="../img/famfamfam/icon_accept.gif" alt="Juchu" class="icon1" />';
			echo '<strong>Done!</strong> Account of user <b>'.html_specialchars($phpwcms['admin_user']).'</b> was updated.';
			echo '<input type="hidden" name="user_account" value="1" />';
		
		}		
		
		if($user_check === false) {
		
			// db error
			echo errorWarning('There is a database problem!');
			echo '<p>Account for user <b>'.html_specialchars($phpwcms['admin_user']).'</b> was not created or updated.<br />Click <b>continue</b> to try again.</p>';
			$_SESSION['admin_save'] = false;
			
		}
		
		
		echo '</td></tr>';
	
	
	
	}

}


?>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="15" /></td>
		  </tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2"><input name="dbsavesubmit" type="submit" value="Continue" /></td>
          </tr>
</table><input name="do" type="hidden" value="1" /></form>