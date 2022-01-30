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
//  based on FormMail v1
//  (c) 2003 webverbund.de Oliver Georgi (info@webverbund.de)

// Only internal form sender allowed
$phpwcms = array();
require_once '../../include/config/conf.inc.php';

$url = $phpwcms["site"];
$url = str_replace('http://', '', $url);
$url = str_replace('https://', '', $url);
$url = preg_replace('/\/$/', '', $url);
$ref = $_SERVER['HTTP_REFERER'];
$ref = str_replace('http://', '', $ref);
$ref = str_replace('https://', '', $ref);
if( strpos($ref, $url) === false) {
	headerRedirect($phpwcms["site"].$phpwcms["root"]);
}

if(is_array($_GET)) {
	$_GET = array('');
}

require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
include_once PHPWCMS_ROOT.'/include/inc_lang/formmailer/lang.formmailer.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_ext/phpmailer/PHPMailerAutoload.php';

if(!checkFormTrackingValue()) {

    header("HTTP/1.0 405 Method Not Allowed");

    echo '<html lang="en"><head><meta charset="utf-8"><title>phpwcms Formmailer</title></head>';
	echo '<body>';
	echo '<h1>You are not allowed to send the form!</h1><pre>';
	if(!PHPWCMS_GDPR_MODE) {
        echo 'Your IP: ' . html(getRemoteIP()) . LF;
    }
	echo 'HTTP-REFERRER: ' . (empty($ref) ? 'unknown' : html($ref));
	echo '</pre></body></html>';
	exit();

}

function phpwcms_form_encode($in_str, $charset) {
   $out_str = $in_str;
   if ($out_str && $charset) {

       // define start delimimter, end delimiter and spacer
       $end = "?=";
       $start = "=?" . $charset . "?B?";
       $spacer = $end . "\r\n " . $start;

       // determine length of encoded text within chunks
       // and ensure length is even
       $length = 75 - strlen($start) - strlen($end);
       $length = floor($length/2) * 2;

       // encode the string and split it into chunks
       // with spacers after each chunk
       $out_str = base64_encode($out_str);
       $out_str = chunk_split($out_str, $length, $spacer);

       // remove trailing spacer and
       // add start and end delimiters
       $spacer = preg_quote($spacer, '/');
       $out_str = preg_replace("/" . $spacer . "$/", "", $out_str);
       $out_str = $start . $out_str . $end;
   }
   return $out_str;
}

//check which language to use
$lang = "EN";
if(isset($_POST["language"]) && strlen($_POST['language']) < 3) {
    $_POST["language"] = trim(strtoupper($_POST["language"]));
	if (isset($translate[$_POST["language"]])) {
	    $lang = $_POST["language"];
        $translate[$lang] = array_merge($translate['EN'], $translate[$lang]);
	}
    unset($_POST["language"]);
}

//charset
if(isset($_POST["charset"])) {
	$charset = trim($_POST["charset"]);
	$charset = urldecode($charset);
	$charset = str_replace('..', '', $charset);
	$charset = str_replace('/', '', $charset);
	$charset = str_replace('/', '', $charset);
	unset($_POST["charset"]);
}
if(empty($charset)) {
    $charset = 'utf-8';
}
$content_type = 'Content-Type: text/plain; charset='.$charset."\n";

//getting the required fields list
if(isset($_POST["required"])) {
	$req_key = explode(",", trim($_POST["required"]));
	if(count($req_key)) {
		$err_num=0;
		foreach($req_key as $value) {
			$required_val[$value] = 1;
			if(!isset($_POST[$value])) {
				$form_error[400+$err_num] = str_replace("###value###", strtoupper($value), $translate[$lang]["error400"]);
				$err_num+=10;
			}
		}
	}
	unset($_POST["required"]);
}

if(isset($_POST["Captcha_Validation"])) {
	include_once PHPWCMS_ROOT.'/include/inc_ext/SPAF_FormValidator.class.php';
	$spaf_obj = new SPAF_FormValidator();
	if($spaf_obj->validRequest($_POST["Captcha_Validation"])) {
		$spaf_obj->destroy();
		unset($_POST["Captcha_Validation"]);
	} else {
		$form_error[350] = $translate[$lang]["error350"];
	}
}
//getting the label fields list
if(isset($_POST["label"])) {
	$label = explode(',', trim($_POST["label"]));
	if($label) {
		foreach($label as $value) {
			list($field_name, $field_label) = explode('|', $value);
			$form_label[$field_name] = $field_label;
		}
	}
	unset($_POST["label"]);
}

//checking for base values
//recipient, recipient name:
if(isset($_POST["recipient"])) {
	$recipient = cleanUpFormMailerPostValue($_POST["recipient"]);
	unset($_POST["recipient"]);
}
//check if recipient's email address is defined in conf.inc.php
if(	isset($phpwcms["formmailer_set"])
	&& !empty($phpwcms["formmailer_set"]['global_recipient_email'])
	&& $phpwcms["formmailer_set"]['global_recipient_email'] != 'form@localhost'
	&& is_valid_email($phpwcms["formmailer_set"]['global_recipient_email'])) {
	$recipient = $phpwcms["formmailer_set"]['global_recipient_email'];
}

if(!is_valid_email($recipient)) { //if recipient mail address is invalid
	$form_error[100] = $translate[$lang]["error100"];
}
if(isset($_POST["recipient_name"])) {
	$recipient_name = cleanUpFormMailerPostValue($_POST["recipient_name"]);
	unset($_POST["recipient_name"]);
}
//subject:
if(isset($_POST["subject"])) {
	$subject = cleanUpFormMailerPostValue($_POST["subject"]);
	$subject_encoded = phpwcms_form_encode($subject, $charset);
	unset($_POST["subject"]);
}
if(empty($subject)) { //if recipient mail address is invalid
	$form_error[200] = $translate[$lang]["error200"];
}
//send copy to form sender
if(isset($_POST["send_copy"])) {
	if(!empty($phpwcms["formmailer_set"]['allow_send_copy']) && intval($_POST["send_copy"])) {
		$send_copy_to = cleanUpFormMailerPostValue($_POST["email"]);
		if(!is_valid_email($send_copy_to)) {
			$form_error[300] = $translate[$lang]["error300"];
			unset($send_copy_to);
		}
	}
	unset($_POST["send_copy"]);
}

//get values for redirecting
if(isset($_POST["redirect"])) {
	$redirect = trim($_POST["redirect"]);
	unset($_POST["redirect"]);
}
if(isset($_POST["redirect_template"])) {
	$redirect_template = trim($_POST["redirect_template"]);
	unset($_POST["redirect_template"]);
}
if(isset($_POST["redirect_error"])) {
	$redirect_error = trim($_POST["redirect_error"]);
	unset($_POST["redirect_error"]);
}
if(isset($_POST["redirect_error_template"])) {
	$redirect_error_template = trim($_POST["redirect_error_template"]);
	unset($_POST["redirect_error_template"]);
}

if(isset($_POST["submit"])) {
	unset($_POST["submit"]);
}
if(isset($_POST["type"])) {
	unset($_POST["type"]);
}

//checking values and setting labels
if(count($_POST)) {
	$err_num = 0;
	foreach($_POST as $key => $value) {

		//Check for required fields
		if(!empty($required_val[$key]) && str_empty($value) && $key !== 'Captcha_Validation') {
			if(isset($form_label[$key])) {
				$form_error[500+$err_num] = str_replace("###value###", $form_label[$key], $translate[$lang]["error400"]);
			} else {
				$form_error[500+$err_num] = str_replace("###value###", strtoupper($key), $translate[$lang]["error400"]);
			}
			$err_num+=10;
		}

		if(is_array($value)) { //if field value is an array then split form name
			$x = 1;
			foreach($value as $field_value) {
				$form[$key."[".$x."]"] = trim($field_value);
				$x++;
			}
		} else {
			$form[$key] = trim($value);
		}
	}
}

if(isset($form_error)) {
	if(isset($redirect_error)) {
		headerRedirect($redirect_error);
	} else {
		//if error show error template
		$table = "";
		foreach($form_error as $key => $value) {
  			$table .= "<tr bgcolor=\"#F4F4F4\">";
    		$table .= "<td class=\"error\">[".html($key)."]</td>";
    		$table .= "<td class=\"error\">".html($value)."</td>";
  			$table .= "</tr>\n";
		}

		$error_template = read_textfile(PHPWCMS_ROOT.'/include/inc_lang/formmailer/'.$lang.'_formmailer.error.html');
		$error_template = str_replace("<!-- RESULT //-->", $table, $error_template);
		echo $error_template;

	}

} else {
	$translate[$lang]["bodyLine1"] = str_replace("###date###", date($translate[$lang]["dateFormat"]), $translate[$lang]["bodyLine1"]);
	$translate[$lang]["bodyLine1"] = str_replace("###time###", date($translate[$lang]["timeFormat"]), $translate[$lang]["bodyLine1"]);
	$body = $translate[$lang]["bodyLine1"]."\n";
	$body.= $translate[$lang]["bodyLine2"]."\n";
	$body.= $_SERVER['HTTP_REFERER']." \n";
	$body .= PHPWCMS_GDPR_MODE ?  : "IP: " . getRemoteIP() . " \n\n";
	$body.= "====================================================================\n\n";
	$body.= $translate[$lang]["bodyRecipient"];
	if($recipient_name) {
		$body.= $recipient_name." (".$recipient.")\n\n";
	} else {
		$body.= $recipient."\n\n";
	}
	$body.= "====================================================================\n\n";
	$body.= $subject."\n";
	$body.= "--------------------------------------------------------------------\n";

	$l=0;
	if(is_array($form) && count($form)) {
		foreach($form as $key => $value) {
			$x = strlen($key);
			if($x > $l) $l = $x;
		}
		foreach($form as $key => $value) {
			$body.= str_pad($key, $l, ".").": ".$value."\n";
		}
	} else {
		$body .= LF.LF.LF;
		$form = array();
	}

	$body.= "\n====================================================================\n";
	$body.= "phpwcms formmailer  | Copyright (C) 2003 \n";

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
	if(!empty($phpwcms['SMTP_SECURE'])) {
		$mail->SMTPSecure 	= $phpwcms['SMTP_SECURE'];
	}
	if(!empty($phpwcms['SMTP_AUTH_TYPE'])) {
		$mail->AuthType = $phpwcms['SMTP_AUTH_TYPE'];
		if($phpwcms['SMTP_AUTH_TYPE'] === 'NTLM') {
			if(!empty($phpwcms['SMTP_REALM'])) {
				$mail->Realm = $phpwcms['SMTP_REALM'];
			}
			if(!empty($phpwcms['SMTP_WORKSTATION'])) {
				$mail->Workstation = $phpwcms['SMTP_WORKSTATION'];
			}
		}
	}
	$mail->SMTPKeepAlive 	= true;
	$mail->CharSet	 		= $phpwcms["charset"];
	$mail->isHTML(0);
	$mail->Subject			= $subject;
	$mail->Body 			= $body;

	if(!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/')) {
		$mail->setLanguage('en', PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/');
	}

	$false = '';

    $mail->setFrom($recipient, $phpwcms['SMTP_FROM_NAME']);
    $mail->addReplyTo($recipient);

    if(isset($send_copy_to)) {

        $mail->addAddress($send_copy_to);

		if(!$mail->send()) {
			$false .= '(1) '.html($mail->ErrorInfo).'<br>';
		}

		$mail->setFrom($send_copy_to);
		$mail->addReplyTo($send_copy_to);

	}

    $mail->clearAddresses();
	$mail->addAddress($recipient);

	if(!$mail->send()) {
		$false .= '(2) '.html($mail->ErrorInfo).'<br>';
	}

	$mail->smtpClose();

	if(isset($redirect) && !$false) {
		headerRedirect($redirect);
	} else {

		//Success show form success template
		$table = "";
		if($false) {
			$table .= '<tr bgcolor="#F4F4F4">';
			$table .= "<td>Mailer Error:</td>";
			$table .= "<td>".$false."</td>";
			$table .= "</tr>\n";
		}

		foreach($form as $key => $value) {
			$table .= "<tr bgcolor=\"#F4F4F4\">";
			$table .= "<td>".html($key)."</td>";
			$table .= "<td>".html($value)."</td>";
			$table .= "</tr>\n";
		}

		$success_template = read_textfile(PHPWCMS_ROOT.'/include/inc_lang/formmailer/'.$lang.'_formmailer.success.html');
		$success_template = str_replace("<!-- RESULT //-->", $table, $success_template);
		echo $success_template;

	}
}
