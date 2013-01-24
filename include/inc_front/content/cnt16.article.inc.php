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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// load special functions
require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');

//ecard

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$ecard = unserialize($crow["acontent_form"]);

$ecard["send_err"]		= 0;
$ecard["send_success"]	= 0;
$ecard["selected"]		= '';

// check if e-card was posted
if(isset($_POST['ecard_chooser'])) {
									
	$ecard["chooser"] 			= isset($_POST['ecard_chooser']) ? intval($_POST['ecard_chooser']) : 0;
	$ecard["selected"]			= $ecard["chooser"];
	$ecard["sender_name"]		= clean_slweg(remove_unsecure_rptags($_POST["ecard_sender_name"]));
	$ecard["sender_email"]		= clean_slweg(remove_unsecure_rptags($_POST["ecard_sender_email"]));
	$ecard["recipient_name"]	= clean_slweg(remove_unsecure_rptags($_POST["ecard_recipient_name"]));
	$ecard["recipient_email"]	= clean_slweg(remove_unsecure_rptags($_POST["ecard_recipient_email"]));
	$ecard["sender_msg"]		= clean_slweg(remove_unsecure_rptags($_POST["ecard_sender_msg"]));
									
	if(!is_valid_email($ecard["sender_email"]) || !is_valid_email($ecard["recipient_email"])) {
		$ecard["send_err"] = 1;
	} else {
		//send message
		include_once('include/inc_ext/phpmailer/class.phpmailer.php');
		$ecard["capt"] = explode("\n", $ecard["caption"]);
		
		$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$ecard['images'][$ecard["chooser"]][3],
								"image_name"	=>	$ecard['images'][$ecard["chooser"]][2] . '.' . $ecard['images'][$ecard["chooser"]][3],
								"max_width"		=>	$ecard['images'][$ecard["chooser"]][4],
								"max_height"	=>	$ecard['images'][$ecard["chooser"]][5],
								"thumb_name"	=>	md5(	$ecard['images'][$ecard["chooser"]][2].$ecard['images'][$ecard["chooser"]][4].
															$ecard['images'][$ecard["chooser"]][5].$GLOBALS['phpwcms']["sharpen_level"]
														)
        					  )
						);
		$list_img_temp  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" '.$thumb_image[3].' alt="'.html_specialchars($ecard['images'][$ecard["chooser"]][1]).'" />';

		$ecard["send"] = str_replace('###ECARD_TITLE###', html_specialchars(chop($ecard["capt"][$ecard["chooser"]])), $ecard["send"]);
		$ecard["send"] = str_replace('###ECARD_IMAGE###', $list_img_temp, $ecard["send"]);
		$ecard["send"] = str_replace('###RECIPIENT_NAME###', ($ecard["recipient_name"]) ? html_specialchars($ecard["recipient_name"]) : html_specialchars($ecard["recipient_email"]), $ecard["send"]);
		$ecard["send"] = str_replace('###RECIPIENT_EMAIL###', html_specialchars($ecard["recipient_email"]), $ecard["send"]);
		$ecard["send"] = str_replace('###SENDER_MESSAGE###', nl2br(html_specialchars($ecard["sender_msg"])), $ecard["send"]);
		$ecard["send"] = str_replace('###ECARD_SUBJECT###', html_specialchars($ecard["subject"]), $ecard["send"]);

		$ecard["mailer"] = new PHPMailer();
		$ecard["mailer"]->Mailer = $phpwcms['SMTP_MAILER'];
		$ecard["mailer"]->IsHTML(1);
		$ecard['mailer']->CharSet = $phpwcms["charset"];
		$ecard["mailer"]->From = $ecard["sender_email"];
		if($ecard["sender_name"]) $ecard["mailer"]->FromName = $ecard["sender_name"];
		$ecard["mailer"]->AddAddress($ecard["recipient_email"], $ecard["recipient_name"]);
		$ecard["mailer"]->Subject = ($ecard["subject"]) ? $ecard["subject"] : 'E-Card: '.chop($ecard["capt"][$ecard["chooser"]]);
		
		$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$ecard['images'][$ecard["chooser"]][3],
								"image_name"	=>	$ecard['images'][$ecard["chooser"]][2] . '.' . $ecard['images'][$ecard["chooser"]][3],
								"max_width"		=>	$GLOBALS['phpwcms']["img_prev_width"],
								"max_height"	=>	$GLOBALS['phpwcms']["img_prev_height"],
								"thumb_name"	=>	md5(	$ecard['images'][$ecard["chooser"]][2].$GLOBALS['phpwcms']["img_prev_width"].
															$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"].'ecard'
														)
        					  )
						);
		$list_img_temp  = '<img src="'.PHPWCMS_URL.PHPWCMS_IMAGES.$thumb_image[0].'" '.$thumb_image[3].' alt="'.html_specialchars($ecard['images'][$ecard["chooser"]][1]).'" />';
		
		if($ecard["mail"]) {
			$ecard["mail"] = str_replace('###ECARD_TITLE###', html_specialchars(chop($ecard["capt"][$ecard["chooser"]])), $ecard["mail"]);
			$ecard["mail"] = str_replace('###ECARD_IMAGE###', $list_img_temp, $ecard["mail"]);
			$ecard["mail"] = str_replace('###RECIPIENT_NAME###', ($ecard["recipient_name"]) ? html_specialchars($ecard["recipient_name"]) : html_specialchars($ecard["recipient_email"]), $ecard["mail"]);
			$ecard["mail"] = str_replace('###RECIPIENT_EMAIL###', html_specialchars($ecard["recipient_email"]), $ecard["mail"]);
			$ecard["mail"] = str_replace('###SENDER_MESSAGE###', nl2br(html_specialchars($ecard["sender_msg"])), $ecard["mail"]);
			$ecard["mail"] = str_replace('###SENDER_NAME###', ($ecard["sender_name"]) ? html_specialchars($ecard["sender_name"]) : html_specialchars($ecard["sender_email"]), $ecard["mail"]);
			$ecard["mail"] = str_replace('###SENDER_EMAIL###', html_specialchars($ecard["sender_email"]), $ecard["mail"]);
			$ecard["mail"] = str_replace('###ECARD_SUBJECT###', html_specialchars($ecard["subject"]), $ecard["mail"]);
			$ecard["mailer"]->Body = $ecard["mail"];
		} else {
			$ecard["mailer"]->Body = '<div align="center"><h3>E-Card &quot;'.html_specialchars(chop($ecard["capt"][$ecard["chooser"]])).'&quot;</h3>'.
									 '<p><strong>sent to you from '.html_specialchars($ecard["sender_name"].(($ecard["sender_name"]) ? ' ('.$ecard["sender_email"].')': $ecard["sender_email"])).'</strong></p>'.
									 '<p>'.$list_img_temp.'</p>'.
									 '<p>'.nl2br(html_specialchars($ecard["sender_msg"])).'</p><hr /><a href="'.$phpwcms["site"].'" target="_blank">'.$phpwcms["site"].'</a></div>';
		}

		if(strtolower($phpwcms['SMTP_MAILER']) == 'smtp') {
			$ecard["mailer"]->Port = (!$phpwcms['SMTP_PORT']) ? 25 : $phpwcms['SMTP_PORT'];
			$ecard["mailer"]->Host = $phpwcms['SMTP_HOST'];
			$ecard["mailer"]->SMTPAuth = $phpwcms['SMTP_AUTH'];
			$ecard["mailer"]->Username = $phpwcms['SMTP_USER'];
			$ecard["mailer"]->Password = $phpwcms['SMTP_PASS'];
		}
		
		$ecard["mailer"]->Send();

		$CNT_TMP .= $ecard["send"];
		$ecard["send_success"]	= 1;
	}
}
									
if(is_array($ecard['images']) && count($ecard['images']) && !$ecard["send_success"]) {
	//Nochmal Prüfen auf leere Werte oder Dopplungen und Zuweisen der einzelnen Werte
	
	$ecard["onover"]	= preg_replace('/;{1,}$/', '', $ecard["onover"])	.';';
	$ecard["onclick"]	= preg_replace('/;{1,}$/', '', $ecard["onclick"])	.';';
	$ecard["onout"]		= preg_replace('/;{1,}$/', '', $ecard["onout"])		.';';
	
	//$ecard["capt"] = explode("\n", $ecard["caption"]);
	$ecard["show"] = array();
	
	$ecard_count = 0;
	
	foreach($ecard['images'] as $key => $value) {

			$ecard['temp_caption'] = explode('|', $ecard['images'][$key][6], 2);
			$ecard['images'][$key][6] = $ecard['temp_caption'][0];
			//check if image should be available as e-card
			if(substr($ecard['images'][$key][6], 0, 1) != '~') {
			
				//check if radio button or javascript
				if(!$ecard["selector"]) {
				
					$temp_cap  = '<table '.$template_default["article"]["ecard_chooser_css"].' border="0" cellpadding="0" cellspacing="0">'."\n<tr>\n";
					$temp_cap .= '<td valign="top"><input type="radio" name="ecard_chooser" id="ecard_chooser_'.$ecard_count.'" value="'.$key.'" ';
					if(isset($ecard["chooser"]) && $ecard["chooser"] == $key) {
						$temp_cap .= ' checked="checked" ';
					}
					$temp_cap .= "/></td>\n<td ".$template_default["article"]["ecard_chooser_text"].">";
					$temp_cap .= html_specialchars(trim($ecard['images'][$key][6]))."</td>\n</tr>\n</table>"; //Bildunterschrift
			
				} else {
			
					$temp_cap  = '<table width="100%" '.$template_default["article"]["ecard_chooser_css"].' border="0" cellpadding="0" cellspacing="0">';
					$temp_cap .= '<tr><td id="ecard'.$key.'" '.$template_default["article"]["ecard_chooser_text"];
					if($ecard["onover"]) {
						$temp_cap .= ' onmouseover="'.$ecard["onover"].'"';
					}
					if($ecard["onclick"]) {
						$temp_cap .= ' onclick="'.$ecard["onclick"].'"';
					}
					if($ecard["onout"]) {
						$temp_cap .= ' onmouseout="'.$ecard["onout"].'"';
					}
					$temp_cap .= '>'.html_specialchars(trim($ecard['images'][$key][6])).'</td></tr></table>';
				
				}
				
			} else {
			
				// show image caption only
				$temp_cap = html_specialchars(substr($ecard['images'][$key][6], 1));
			
			}
			
			$ecard['images'][$key][6] = $temp_cap;
			if(!empty($ecard['temp_caption'][1])) {
				$ecard['images'][$key][6] .= '|'.$ecard['temp_caption'][1];
			}
			
			$ecard_count++;
			
	}
	switch($ecard["pos"]) {
		case 0: $ecard["chooser"] = imagelisttable($ecard, "0:5:0:0", '', 1); 		break;	//links
		case 1:	$ecard["chooser"] = imagelisttable($ecard, "0:5:0:0", "center", 1); break;	//center
		case 1:	$ecard["chooser"] = imagelisttable($ecard, "0:5:0:0", "center", 1); break; 	//right
	}
	$ecard["form"] = str_replace('###ECARD_CHOOSER###', $ecard["chooser"], $ecard["form"]);
	if(!$ecard["send_err"]) {
		$ecard["form"] = preg_replace("/<!--FORM_ERROR_START-->(.*?)<!--FORM_ERROR_END-->/si", '', $ecard["form"]);
	}
	$ecard["form"] = preg_replace("/name=[\'|\"]###SENDER_NAME###[\'|\"]/i", 'name="ecard_sender_name"', $ecard["form"]);
	$ecard["form"] = preg_replace("/name=[\'|\"]###SENDER_EMAIL###[\'|\"]/i", 'name="ecard_sender_email"', $ecard["form"]);
	$ecard["form"] = preg_replace("/name=[\'|\"]###RECIPIENT_NAME###[\'|\"]/i", 'name="ecard_recipient_name"', $ecard["form"]);
	$ecard["form"] = preg_replace("/name=[\'|\"]###RECIPIENT_EMAIL###[\'|\"]/i", 'name="ecard_recipient_email"', $ecard["form"]);
	$ecard["form"] = preg_replace("/name=[\'|\"]###SENDER_MESSAGE###[\'|\"]/i", 'name="ecard_sender_msg"', $ecard["form"]);

	$ecard["form"] = str_replace('###SENDER_NAME###', isset($ecard["sender_name"]) ? html_specialchars($ecard["sender_name"]) : '', $ecard["form"]);
	$ecard["form"] = str_replace('###SENDER_EMAIL###', isset($ecard["sender_email"]) ? html_specialchars($ecard["sender_email"]) : '', $ecard["form"]);
	$ecard["form"] = str_replace('###RECIPIENT_NAME###', isset($ecard["recipient_name"]) ? html_specialchars($ecard["recipient_name"]) : '', $ecard["form"]);
	$ecard["form"] = str_replace('###RECIPIENT_EMAIL###', isset($ecard["recipient_email"]) ? html_specialchars($ecard["recipient_email"]) : '', $ecard["form"]);
	$ecard["form"] = str_replace('###SENDER_MESSAGE###', isset($ecard["sender_msg"]) ? html_specialchars($ecard["sender_msg"]) : '', $ecard["form"]);
	$ecard["form"] = str_replace('###ECARD_SUBJECT###', isset($ecard["subject"]) ? html_specialchars($ecard["subject"]) : '', $ecard["form"]);

	$CNT_TMP .= '<form action="'.html_specialchars($_SERVER['REQUEST_URI']).'" method="post" name="send_ecard">';
	$CNT_TMP .= $ecard["form"];
	if($ecard["selector"]) {
		//add hidden form field ecard_chooser
		$CNT_TMP .= '<input type="hidden" name="ecard_chooser" value="'.$ecard["selected"].'" />';
	}
	$CNT_TMP .= '</form>';
}

?>