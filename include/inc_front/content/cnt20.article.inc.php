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



//bid


$bid = unserialize($crow["acontent_form"]);

//first check if period is valid for showing the bid
$bid['start_show'] = 0;	$bid['end_show'] = 0;
if(($bid['start_date'] 	&& $bid['start_date'] 	< time()) || !$bid['start_date'])	$bid['start_show'] = 1;
if(($bid['end_date'] 	&& $bid['end_date'] 	> time()) || !$bid['end_date']) 	$bid['end_show'] = 1;

if($bid['start_show'] && $bid['end_show']) {

	//verify or delete given hash
	if(!(strpos($_SERVER['REQUEST_URI'],'hash=') === false)) {

		if(isset($_GET['hash'])) {
			$bid['get_hash'] = $_GET['hash'];
		} else {
			list($bid['part1'], $bid['get_hash']) = explode('hash=', trim($_SERVER['REQUEST_URI']));
		}
		
		$bid['do']			= strtolower(substr($bid['get_hash'], 0, 1));
		$bid['get_hash']	= substr($bid['get_hash'], 1);
		
		if($bid['do'] == 'v') {
			//verify bid
			$bid['sql']  = "UPDATE ".DB_PREPEND."phpwcms_bid SET ";
			$bid['sql'] .= "bid_verified='1' WHERE bid_hash='".aporeplace($bid['get_hash']);
			$bid['sql'] .= "' AND bid_verified=0 LIMIT 1;";
			$bid['form'] = $bid['verified'];
		}
		if($bid['do'] == 'd') {
			//delete bid
			$bid['sql']  = "DELETE FROM ".DB_PREPEND."phpwcms_bid ";
			$bid['sql'] .= "WHERE bid_hash='".aporeplace($bid['get_hash'])."' LIMIT 1;";		
			$bid['form'] = $bid['notverified'];
		}
		mysql_query($bid['sql'], $db);

	}

	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
	$CNT_TMP .= $bid['before'];

	if($bid['start_date']) {
		$bid['text'] = preg_replace('/###BID_START:(.*?)###/e', "date('$1',\$bid['start_date'])", $bid['text']);
	} else {
		$bid['text'] = preg_replace('/###BID_START:(.*?)###/', '&infin;', $bid['text']);
	}
	
	if($bid['end_date']) {
		$bid['text'] = preg_replace('/###BID_END:(.*?)###/e', "date('$1',\$bid['end_date'])", $bid['text']);
	} else {
		$bid['text'] = preg_replace('/###BID_END:(.*?)###/', '&infin;', $bid['text']);
	}
	

	//bid form start values
	$bid['post_email']  = '';
	$bid['post_amount'] = $bid['startbid'];
	$bid['post_error']  = 0;
	$bid['amount'] = $bid['startbid'];

	// first check for all available related bid entries
	$bid['sql']  = "SELECT * FROM ".DB_PREPEND."phpwcms_bid WHERE bid_cid=";
	$bid['sql'] .= $crow["acontent_id"]." AND bid_verified=1 AND bid_trashed=0 ORDER BY bid_amount DESC LIMIT 1";
	
	if($bid['result'] = mysql_query($bid['sql'], $db)) {
		if($bid['row'] = mysql_fetch_assoc($bid['result'])) {
			if($bid['post_amount'] < $bid['row']['bid_amount']) $bid['post_amount'] = $bid['row']['bid_amount'];
			$bid['amount'] = $bid['row']['bid_amount'];
		}
		if(!$bid['amount']) $bid['amount'] = $bid['startbid'];
		mysql_free_result($bid['result']);
	}
	
	if(isset($_POST['bid_email']) && isset($_POST['bid_amount'])) {
	
		$bid['post_email']  = clean_slweg(remove_unsecure_rptags($_POST['bid_email']));
		$bid['post_amount'] = clean_slweg(remove_unsecure_rptags($_POST['bid_amount']));
		$bid['post_amount'] = str_replace('.', '', $bid['post_amount']);
		$bid['post_amount'] = str_replace(',', '.', $bid['post_amount']);
		$bid['post_amount'] = floatval($bid['post_amount']);
		
		
		
		
		if(!is_valid_email($bid['post_email']) || !$bid['post_email']) $bid['post_error'] = 1;
		if(!$bid['post_amount']) $bid['post_error'] = 1;
			
		
		if(!$bid['post_error']) {
		
			$bid['hash'] = md5($bid['post_email'].time());
			$bid['sql']  = "INSERT INTO ".DB_PREPEND."phpwcms_bid SET ";
			$bid['sql'] .= "bid_cid='".$crow["acontent_id"]."', ";
			$bid['sql'] .= "bid_email='".aporeplace($bid['post_email'])."', ";
			$bid['sql'] .= "bid_hash='".$bid['hash']."', ";
			$bid['sql'] .= "bid_amount='".$bid['post_amount']."';";
			
			//if(mysql_query($bid['sql'], $db) OR die('<pre>'.$bid['sql'].'</pre>'));
			mysql_query($bid['sql'], $db);
		
			//send validation
			include_once('include/inc_ext/phpmailer/class.phpmailer.php');
			
			$bid_mailer = new PHPMailer();
			
			$bid_mailer->SetLanguage('en', 'include/inc_ext/phpmailer/language/');
			$bid_mailer->Mailer = $phpwcms['SMTP_MAILER'];
			$bid_mailer->From = $bid['emailfrom'];
			$bid_mailer->FromName = $bid['emailfromname'];
			$bid_mailer->AddAddress($bid['post_email']);
			$bid_mailer->CharSet = $phpwcms["charset"];
			$bid_mailer->Subject = ($crow["acontent_title"]) ? $crow["acontent_title"] : 'bid validation';

			list($bid["uri"], $bid["query"]) = explode('?', $_SERVER['REQUEST_URI']);
 			$bid['url']  = preg_replace('/\/$/', '', $phpwcms['site']);
			//$bid['url'] .= ($phpwcms["root"]) ? "/".$phpwcms["root"] : '';
			$bid['url']  = preg_replace('/\/$/', '', $bid['url']).$bid["uri"];
			
			$bid["delurl"] = '';
			if($bid["query"]) $bid["delurl"] = $bid["query"].'&';
			$bid["delurl"] = $bid['url'].'?'.$bid["delurl"].'hash=D'.$bid['hash'];
			
			$bid["verifyurl"] = '';
			if($bid["query"]) $bid["verifyurl"] = $bid["query"].'&';
			$bid["verifyurl"] = $bid['url'].'?'.$bid["verifyurl"].'hash=V'.$bid['hash'];
			
			if($bid["query"]) $bid['url'].'?'.$bid["query"];
			$bid["emailmsg"] = str_replace('###BID_URL###', $bid['url'], $bid["emailmsg"]);
			
			$bid["emailmsg"] = str_replace('###VERIFY_LINK###', $bid["verifyurl"], $bid["emailmsg"]);
			$bid["emailmsg"] = str_replace('###DELETE_LINK###', $bid["delurl"], $bid["emailmsg"]);
			$bid["emailmsg"] = str_replace('###EMAIL###', $bid['post_email'], $bid["emailmsg"]);
			
			$bid["emailmsg"] = str_replace('###BID###', number_format($bid['post_amount'], 2, ',', '.'), $bid["emailmsg"]);
			$bid["emailmsg"] = str_replace('###START_BID###', number_format($bid['startbid'], 2, ',', '.'), $bid["emailmsg"]);

			if($bid['start_date']) {
				$bid["emailmsg"] = preg_replace('/###BID_START:(.*?)###/e', "date('$1',\$bid['start_date'])", $bid["emailmsg"]);
			} else {
				$bid["emailmsg"] = preg_replace('/###BID_START:(.*?)###/', '-', $bid["emailmsg"]);
			}
	
			if($bid['end_date']) {
				$bid["emailmsg"] = preg_replace('/###BID_END:(.*?)###/e', "date('$1',\$bid['end_date'])", $bid["emailmsg"]);
			} else {
				$bid["emailmsg"] = preg_replace('/###BID_END:(.*?)###/', '-', $bid["emailmsg"]);
			}

			$bid_mailer->Body = $bid["emailmsg"];

			if(strtolower($phpwcms['SMTP_MAILER']) == 'smtp') {
				$bid_mailer->Port = (!$phpwcms['SMTP_PORT']) ? 25 : $phpwcms['SMTP_PORT'];
				$bid_mailer->Host = $phpwcms['SMTP_HOST'];
				$bid_mailer->SMTPAuth = $phpwcms['SMTP_AUTH'];
				$bid_mailer->Username = $phpwcms['SMTP_USER'];
				$bid_mailer->Password = $phpwcms['SMTP_PASS'];
			}
			
			if(!$bid_mailer->Send()) {
				$bid['form'] = 'Mail-Error: '.html_specialchars($bid['post_email'].' ('.$bid_mailer->ErrorInfo).')<br>';
			} else {
				$bid['form'] = $bid["sent"];
			}
			
			unset($bid_mailer);
		}
	}

	$bid['text'] = str_replace('###BID_CURRENT###', number_format($bid['amount'], 2, ',', '.'), $bid['text']);
	$bid['text'] = str_replace('###START_BID###', number_format($bid['startbid'], 2, ',', '.'), $bid['text']);

	if($bid['image_cname']) {
		$bid['image_cname'] = '<img src="'.$phpwcms["content_path"].$phpwcms["cimage_path"].$bid['image_cname'].'" border=0" alt=""###ALIGN### />';
		if($bid['image_zoom']) {	
			$open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($bid['image_prev']);
			$bid['image_cname'] = 	'<a href="'.$open_popup_link.'" '.
									"onclick=\"window.open('".$open_popup_link."','previewpic','width=".
									$bid['image_prev_info'][0].',height='.$bid['image_prev_info'][1].
									"');return false;\">".$bid['image_cname'].'</a>';
		}
	
		preg_match('/###BID_IMG:(.*)###/U', $bid['text'], $match);
		if(isset($match[1]) && $match[1]) {
			$match[1] = strtolower(trim($match[1]));
			if($match[1] == 'center') {
				$bid['image_cname'] = str_replace('###ALIGN###', '',$bid['image_cname']);
				$bid['image_cname'] = '<div align="center">'.$bid['image_cname'].'</div>';				
			} else {
				$bid['image_cname'] = str_replace('###ALIGN###', ' align="'.$match[1].'"',$bid['image_cname']);
			}
		} else {
			$bid['image_cname'] = str_replace('###ALIGN###', '',$bid['image_cname']);
		}
	
	}
	$bid['text'] = preg_replace('/###BID_IMG:(.*)###/U', $bid['image_cname'], $bid['text']);
	
	
	if(!$bid['post_error']) {
		// remove post form error part
		$bid['form'] = preg_replace("/<!--FORM_ERROR_START-->(.*?)<!--FORM_ERROR_END-->/si", '', $bid['form']);
	} else {
		$bid['form'] = preg_replace("/<!--FORM_ERROR_START-->(.*?)<!--FORM_ERROR_END-->/si", '$1', $bid['form']);
	}
	
	$bid['form'] = str_replace('name="###BID_EMAIL###"', 'name="bid_email"', $bid['form']);
	$bid['form'] = str_replace('value="###BID_EMAIL###"', 'value="'.html_specialchars($bid['post_email']).'"', $bid['form']);
	$bid['form'] = str_replace('name="###BID_AMOUNT###"', 'name="bid_amount"', $bid['form']);
	
	if(!isset($_POST['bid_email']) || !isset($_POST['bid_amount'])) $bid['post_amount'] += $bid['nextbidadd'];
	
	$bid['form'] = str_replace('value="###BID_AMOUNT###"', 'value="'.html_specialchars(number_format($bid['post_amount'],2,',','.')).'"', $bid['form']);

	$bid['form'] = '<form name="sendbid" method="post" action="index.php?id="'.implode(',', $aktion).'">'.$bid['form'].'</form>';
	$bid['text'] = str_replace('###BID_FORM###', $bid['form'], $bid['text']);

	$CNT_TMP .= $bid['text'];
	$CNT_TMP .= $bid['after'];

}

unset($bid);

?>