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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



// Content Type Sitemap
$content["bid"]	= unserialize($row["acontent_form"]);

if(!$content['bid']['text']) {

	$content['bid']['text']  = '<p>###BID_IMG:left### You can bid on following product from ###BID_START:m/d/y### until ';
	$content['bid']['text'] .= '###BID_END:m/d/y###. Please notice: this is no auction. We want to know how much ';
	$content['bid']['text'] .= 'you would pay for the product. The current bid is: ###BID_CURRENT### EUR (bid ';
	$content['bid']['text'] .= 'starts at ###START_BID### EUR). You can bid by sending your price and email ';
	$content['bid']['text'] .= 'address. Then we will send an email to the given address where you have to ';
	$content['bid']['text'] .= 'verify your bid. Only if your bid is verified successfully it will be taken.</p> ';
	$content['bid']['text'] .= '<p>###BID_FORM###</p>';
	
}

if(!$content['bid']['form']) {

	$content['bid']['form']  = '<!--FORM_ERROR_START-->error: check fields!<br /><!--FORM_ERROR_END-->email:&nbsp;';
	$content['bid']['form'] .= '<input name="###BID_EMAIL###" type="text" size="30" value="###BID_EMAIL###" />&nbsp;&nbsp;bid:';
	$content['bid']['form'] .= '&nbsp;<input name="###BID_AMOUNT###" type="text" size="15" value="###BID_AMOUNT###" />&nbsp;';
	$content['bid']['form'] .= '<input type="submit" name="Submit" value="send bid" />';
	
}

if(!$content["bid"]["emailmsg"]) {

	$content["bid"]["emailmsg"]  = 'This is an automaticly send verification email.';
	$content["bid"]["emailmsg"] .= "\n\n\n";
	$content["bid"]["emailmsg"] .= "Hi,\n\n";
	$content["bid"]["emailmsg"] .= "you have send a new bid from our website:\n###BID_URL###\n\n";
	$content["bid"]["emailmsg"] .= "The amount of your bid is: ###BID### EUR\n\n";
	$content["bid"]["emailmsg"] .= "Your bid is taken only after you have verified by clicking\n###VERIFY_LINK###\n\n";
	$content["bid"]["emailmsg"] .= "If you do not want to bid please do nothing or delete the bid\n###DELETE_LINK###\n\n";
	$content["bid"]["emailmsg"] .= "Period: ###BID_START:m/d/y### - ###BID_END:m/d/y###\n";
	$content["bid"]["emailmsg"] .= "Start bid was: ###START_BID### EUR\n\nRegards\n";
	
}

if(!$content["bid"]["sent"])	 		$content["bid"]["sent"]			= 'Your bid was sent. You will receive a verification email.';
if(!$content["bid"]["verified"])	 	$content["bid"]["verified"]		= 'You have been verified successfully.';
if(!$content["bid"]["notverified"]) 	$content["bid"]["notverified"]	= 'Your bid was deleted.';

?>