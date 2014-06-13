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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//newsletter subscription

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$content["newsletter"] = unserialize($crow["acontent_newsletter"]);
$content["newsletter"]["email_address_error"] = 0;
$content["newsletter"]["success"] = 0;
$content["newsletter"]["email_subscription"] = array();
if(empty($content["newsletter"]["email_address"])) {
	$content["newsletter"]["email_address"] = '';
}
if(empty($content["newsletter"]["email_name"])) {
	$content["newsletter"]["email_name"] = '';
}
if(!isset($content["newsletter"]["label_pos"])) {
	$content["newsletter"]["label_pos"] = 0;
}

if(!$content["newsletter"]["change_text"] || !$content["newsletter"]["reg_text"]) {
	$temp_mailtext  = 'Hi {NEWSLETTER_NAME},'."\n\n";
	$temp_mailtext .= 'You have subscribed to the newsletter at'." \n";
	$temp_mailtext .= $phpwcms["site"]." \n\n";
	$temp_mailtext .= 'Before you will receive any newsletter '."\n";
	$temp_mailtext .= 'you have to verify the email address '."\n\n";
	$temp_mailtext .= '   {NEWSLETTER_EMAIL} '."\n\n\n";
	$temp_mailtext .= 'To verify click the link '."\n";
	$temp_mailtext .= '{NEWSLETTER_VERIFY} '."\n\n";
	$temp_mailtext .= 'To delete your entry from our database '."\n";
	$temp_mailtext .= '{NEWSLETTER_DELETE}'."\n\n\n";
	$temp_mailtext .= 'Best Regards'."\n";
	$temp_mailtext .= $phpwcms['SMTP_FROM_NAME']."\n";
	$temp_mailtext .= $phpwcms["admin_email"]."\n\n";
	$temp_mailtext .= "--\nIP: {IP}, Date: {DATE:d-m-Y, H:i:s}\n";

	if(!$content["newsletter"]["change_text"]) {
		$content["newsletter"]["change_text"] = $temp_mailtext;
	}
	if(!$content["newsletter"]["reg_text"]) {
		$content["newsletter"]["reg_text"] = $temp_mailtext;
	}
}

if(isset($_POST["newsletter_send"]) && intval($_POST["newsletter_send"])) {
	unset($content["newsletter"]["email_subscription"]);

	$content["newsletter"]["email_address"] 		= clean_slweg(remove_unsecure_rptags($_POST["newsletter_email"]), 250);
	$content["newsletter"]["email_name"]			= clean_slweg(remove_unsecure_rptags($_POST["newsletter_name"]), 250);
	$content["newsletter"]["email_subscription"]	= isset($_POST["email_subscription"]) && is_array($_POST["email_subscription"]) ? $_POST["email_subscription"] : array(0 => 0);

	if(empty($content["newsletter"]["url1"])) $content["newsletter"]["url1"] = '';
	if(empty($content["newsletter"]["url2"])) $content["newsletter"]["url2"] = '';

	if(is_valid_email($content["newsletter"]["email_address"])) {
		//Success
		$content["newsletter"]["success"] = 1;
		$content["newsletter"]["reffering_key"] = "";
		$check_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_address WHERE address_email='".
		aporeplace($content["newsletter"]["email_address"])."' LIMIT 1";
		if($check_result = mysql_query($check_sql, $db)) {
			if($check_row = mysql_fetch_array($check_result, MYSQL_ASSOC)) {
				$content["newsletter"]["reffering_key"] = $check_row["address_key"];
				$content["newsletter"]["reffering_id"]  = $check_row["address_id"];
			}
			mysql_free_result($check_result);
		}
		if($content["newsletter"]["reffering_key"]) {
			//if email exists in newsletter address list update entry
			$e_sql = "UPDATE ".DB_PREPEND."phpwcms_address SET ".
			"address_name='".aporeplace($content["newsletter"]["email_name"])."', ".
			"address_verified=0, ".
			"address_subscription='".aporeplace(serialize($content["newsletter"]["email_subscription"]))."', ".
			"address_url1='".aporeplace($content["newsletter"]["url1"])."', ".
			"address_url2='".aporeplace($content["newsletter"]["url2"])."' ".
			"WHERE address_id=".aporeplace($content["newsletter"]["reffering_id"]).";";
			$content["newsletter"]["updated"] = 1;
		} else {
			$content["newsletter"]["reffering_key"] = preg_replace('/[^a-z0-9]/i', '', shortHash($content["newsletter"]["email_address"].time()) );
			//if email not exists in newsletter address list insert entry
			$e_sql = "INSERT INTO ".DB_PREPEND."phpwcms_address (".
			"address_email, address_name, address_key, address_subscription, address_url1, address_url2) VALUES ('".
			aporeplace($content["newsletter"]["email_address"])."', '".
			aporeplace($content["newsletter"]["email_name"])."', '".
			aporeplace($content["newsletter"]["reffering_key"])."', '".
			aporeplace(serialize($content["newsletter"]["email_subscription"]))."', '".
			aporeplace($content["newsletter"]["url1"]).
			"', '".
			aporeplace($content["newsletter"]["url2"]).
			"');";
			$content["newsletter"]["updated"] = 0;
		}
		mysql_query($e_sql, $db);
		$content["newsletter"]["verify_link"] = PHPWCMS_URL."verify.php?s=".rawurlencode($content["newsletter"]["reffering_key"]);
		$content["newsletter"]["delete_link"] = PHPWCMS_URL."verify.php?u=".rawurlencode($content["newsletter"]["reffering_key"]);
		$content["newsletter"]["mailtext"] = ($content["newsletter"]["updated"]) ? $content["newsletter"]["change_text"] : $content["newsletter"]["reg_text"];
		$content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_NAME}", 	$content["newsletter"]["email_name"], 		$content["newsletter"]["mailtext"]);
		$content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_EMAIL}", 	$content["newsletter"]["email_address"], 	$content["newsletter"]["mailtext"]);
		$content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_VERIFY}", $content["newsletter"]["verify_link"], 		$content["newsletter"]["mailtext"]);
		$content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_DELETE}", $content["newsletter"]["delete_link"], 		$content["newsletter"]["mailtext"]);
		$content["newsletter"]["mailtext"] = replaceGlobalRT($content["newsletter"]["mailtext"]);

		$content['newsletter']['subject']  = returnTagContent($content["newsletter"]["mailtext"], 'SUBJECT');
		if(empty($content['newsletter']['subject']['tag'])) {
			if(isset($content['newsletter']['subject']['new'])) {
				$content["newsletter"]["mailtext"] = $content['newsletter']['subject']['new'];
			}
			$content['newsletter']['subject'] = 'Newsletter verification for '.$phpwcms["site"];
		} else {
			$content["newsletter"]["mailtext"]	= $content['newsletter']['subject']['new'];
			$content['newsletter']['subject']	= $content['newsletter']['subject']['tag'];
		}

		require_once PHPWCMS_ROOT.'/include/inc_ext/phpmailer/PHPMailerAutoload.php';

		// phpMailer Class
		$mail = new PHPMailer();
		$mail->Mailer 			= $phpwcms['SMTP_MAILER'];
		$mail->Host 			= $phpwcms['SMTP_HOST'];
		$mail->Port 			= $phpwcms['SMTP_PORT'];
		if($phpwcms['SMTP_AUTH']) {
			$mail->SMTPAuth 	= 1;
			$mail->Username 	= $phpwcms['SMTP_USER'];
			$mail->Password 	= $phpwcms['SMTP_PASS'];
		}
		$mail->SMTPKeepAlive 	= false;
		$mail->CharSet	 		= $phpwcms["charset"];
		$mail->isHTML(0);
		$mail->Subject			= $content['newsletter']['subject'];
		$mail->Body 			= $content["newsletter"]["mailtext"];

		if(!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/')) {
			$mail->setLanguage('en', PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/');
		}

		$mail->From 		= $phpwcms['SMTP_FROM_EMAIL'];
		$mail->FromName 	= $phpwcms['SMTP_FROM_NAME'];
		$mail->Sender	 	= $phpwcms["admin_email"];

		$mail->clearAddresses();
		$mail->addAddress($content["newsletter"]["email_address"]);

		if(!$mail->send()) {
			$template_default["article"]["newsletter_error"] = html_specialchars($mail->ErrorInfo);
			$content["newsletter"]["success"] = 0;
			$content["newsletter"]["email_address_error"] = 1;
		}

		$mail->smtpClose();

	} else {
		//Error
		$content["newsletter"]["email_address_error"] = 1;
	}

	$content["newsletter"]["email_address"] = html_specialchars($content["newsletter"]["email_address"]);
	$content["newsletter"]["email_name"] = html_specialchars($content["newsletter"]["email_name"]);
}

if($content["newsletter"]["success"]) {
	$content["newsletter"]["success_text"] = str_replace("{NEWSLETTER_EMAIL}", "<strong>".$content["newsletter"]["email_address"]."</strong>", $content["newsletter"]["success_text"]);
	$CNT_TMP .= div_class(	(
							($content["newsletter"]["success_text"]) ? nl2br($content["newsletter"]["success_text"]) : "Email: ".$content["newsletter"]["email_address"].
								" successfully registred. You will receive a verification email within seconds.")
	, $template_default["article"]["text_class"]);

} else {

	if(empty($content["newsletter"]["label_pos"])) {

		$label_pos			= false;
		$label_pos_tr		= LF;
		$label_pos_colspan	= ' colspan="2"';

	} else {

		$label_pos			= true;
		$label_pos_tr		= '</tr>'.LF.'<tr>';
		$label_pos_colspan	= '';

	}


	$CNT_TMP .= ($content["newsletter"]["text"]) ? "<br />".nl2br(div_class($content["newsletter"]["text"],$template_default["article"]["text_class"])) : "";
	$CNT_TMP .= '<form action="'.FE_CURRENT_URL.'" method="post" id="newsletterSubscribeForm">'.LF;
	$CNT_TMP .= '<table border="0" cellpadding="0" cellspacing="0"';
	switch($content["newsletter"]["pos"]) {
		case 1: $CNT_TMP .= ' align="left"'; break;
		case 2: $CNT_TMP .= ' align="center"'; break;
		case 3: $CNT_TMP .= ' align="right"'; break;
	}
	$CNT_TMP .= ' summary="">'.LF;
	if($content["newsletter"]["email_address_error"]) {
		$CNT_TMP .= "<tr>";
		if(!$label_pos) {
			$CNT_TMP .= "\n<td>&nbsp;</td>";
		}
		$CNT_TMP .= "<td class=\"formError\">".$template_default["article"]["newsletter_error"]."</td>\n</tr>\n";
	}
	$CNT_TMP .= "<tr>\n<td class=\"formLabel\">";
	$CNT_TMP .= (($content["newsletter"]["label_email"]) ? $content["newsletter"]["label_email"] : "email:")."&nbsp;</td>";
	$CNT_TMP .= $label_pos_tr;
	$CNT_TMP .= "<td><input name=\"newsletter_email\" type=\"text\" class=\"inputNewsletter\" size=\"30\" maxlength=\"250\" ";
	$CNT_TMP .= "value=\"".$content["newsletter"]["email_address"]."\" /></td>\n</tr>\n";
	$CNT_TMP .= "<tr>\n<td class=\"formLabel\">";
	$CNT_TMP .= (($content["newsletter"]["label_name"]) ? $content["newsletter"]["label_name"] : "name:")."&nbsp;</td>";
	$CNT_TMP .= $label_pos_tr;
	$CNT_TMP .= "<td><input name=\"newsletter_name\" type=\"text\" class=\"inputNewsletter\" size=\"30\" maxlength=\"250\" ";
	$CNT_TMP .= "value=\"".$content["newsletter"]["email_name"]."\" /></td>\n</tr>\n";

	if(is_array($content["newsletter"]["subscription"]) && count($content["newsletter"]["subscription"])) {

		$CNT_TMP .= '<tr><td'.$label_pos_colspan.'>'.spacer(1,3)."</td></tr>\n";

		// retrieve all active newsletters
		$content["newsletter"]['temp'] = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription WHERE subscription_active=1 ORDER BY subscription_name");
		foreach($content["newsletter"]['temp'] as $nlvalue) {

			if(isset($content["newsletter"]["subscription"][ $nlvalue['subscription_id'] ])) {
				$content["newsletter"]["subscription"][ $nlvalue['subscription_id'] ] = $nlvalue['subscription_name'];
			}

		}
		// check for "all" subscriptions setting
		if(isset($content["newsletter"]["subscription"][0])) {
			$content["newsletter"]["subscription"][0] = empty($content["newsletter"]["all_subscriptions"]) ? 'all subscriptions' : $content["newsletter"]["all_subscriptions"];
		}

		$content["newsletter"]['c'] = 0;
		$content["newsletter"]['t'] = '';
		foreach($content["newsletter"]["subscription"] as $nlkey => $nlvalue) {

			if(is_numeric($nlvalue)) continue;

			$content["newsletter"]['t'] .= '<tr>'.LF.'<td><input name="email_subscription['.$nlkey.']" type="checkbox" value="'.$nlkey.'"';
			if(isset($content["newsletter"]["email_subscription"][$nlkey])) $content["newsletter"]['t'] .= ' checked="checked"';
			$content["newsletter"]['t'] .= ' id="email_subscription_'.$nlkey.'"/></td>'.LF;
			$content["newsletter"]['t'] .= '<td><label for="email_subscription_'.$nlkey.'">';
			$content["newsletter"]['t'] .= html_specialchars($nlvalue);
			$content["newsletter"]['t'] .= '</label></td>'.LF.'</tr>'.LF;

			$content["newsletter"]['c']++;

		}

		if($content["newsletter"]['c']) {

			$CNT_TMP .= "<tr>\n<td valign=\"top\" class=\"formLabel subscriptions\">";
			$CNT_TMP .= empty($content["newsletter"]["label_subscriptions"]) ? 'subscribe&nbsp;to:' : $content["newsletter"]["label_subscriptions"];
			$CNT_TMP .= '&nbsp;</td>'.$label_pos_tr.'<td valign="top">';
			$CNT_TMP .= '<table border="0" cellpadding="0" cellspacing="0" class="subscriptions">'.LF;
			$CNT_TMP .= $content["newsletter"]['t'];
			$CNT_TMP .= "</table></td>\n</tr>\n";
		}

	}


	$CNT_TMP .= '<tr><td'.$label_pos_colspan.'>'.spacer(1,3)."</td></tr>\n<tr>";
	if(!$label_pos) {
		$CNT_TMP .= "\n<td>&nbsp;</td>";
	}
	$CNT_TMP .= "<td><input name=\"submit\" type=\"submit\" class=\"formButton\" value=\"";
	$CNT_TMP .= (($content["newsletter"]["button_text"]) ? $content["newsletter"]["button_text"] : "send")."\" />";
	$CNT_TMP .= '<input name="newsletter_send" type="hidden" value="1" />';
	$CNT_TMP .= "</td>\n</tr>\n</table></form>";
}

?>