<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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

// tracking pixel

$phpwcms				= array();
$phpwcms['THIS_ROOT']	= realpath(dirname(__FILE__).'/../../');
require($phpwcms['THIS_ROOT'].'/config/phpwcms/conf.inc.php');
require($phpwcms['THIS_ROOT'].'/include/inc_lib/default.inc.php');

// first check
if(!empty($_GET['t']) && isset($_GET['u']) && $_GET['u'] == PHPWCMS_USER_KEY) {

	$ads = explode(',', $_GET['t']);
	$ads = array_map('intval', $ads);
	$ads = array_diff($ads, array(0));
	$ads = array_unique($ads);

	if(count($ads)) {

		require(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
		require(PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');	

		$ads_userip		= getRemoteIP();
		$ads_useragent	= $_SERVER['HTTP_USER_AGENT'];
		$ads_ref		= isset($_GET['r']) ? trim($_GET['r']) : '';
		$ads_cat		= empty($_GET['c']) ? 0 : intval($_GET['c']);
		$ads_article	= empty($_GET['a']) ? 0 : intval($_GET['a']);
	
		if(empty($_COOKIE['phpwcmsAdsUserId']) || !preg_match('/^[0-9a-f]{32}$/', ($ads_userid = $_COOKIE['phpwcmsAdsUserId']) ) ) {
			$ads_userid	= md5($ads_userip.microtime());
			setcookie('phpwcmsAdsUserId', $ads_userid, time()+63072000, '/', getCookieDomain() );
		}
		
		$t = array();
		foreach($ads as $value) {
			$t[] = "(NOW(), ".$value.", '".mysql_escape_string($ads_userip)."', '".mysql_escape_string($ads_userid)."', ".
				   "0, 1, '".mysql_escape_string($ads_useragent)."', '".mysql_escape_string($ads_ref)."', ".$ads_cat.", ".$ads_article.")";
		}
		
		$sql  =	'INSERT DELAYED INTO '.DB_PREPEND.'phpwcms_ads_tracking ('.
				'adtracking_created, adtracking_campaignid, adtracking_ip, adtracking_cookieid, '.
				'adtracking_countclick, adtracking_countview, adtracking_useragent, adtracking_ref, '.
				'adtracking_catid, adtracking_articleid) VALUES ';
		
		$sql .= implode(',', $t);
		
		@_dbQuery($sql, 'INSERT');
		
		$sql  = 'UPDATE LOW_PRIORITY '.DB_PREPEND.'phpwcms_ads_campaign SET ';
		$sql .= 'adcampaign_curview=adcampaign_curview+1 ';
		$sql .= 'WHERE adcampaign_id IN('.implode(',', $ads).')';
		
		@_dbQuery($sql, 'UPDATE');
	
	}

}

// return blank gif image 1x1 pixel
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: image/gif');
echo base64_decode('R0lGODlhAQABAPAAAAAAAP///yH5BAEAAAEALAAAAAABAAEAAAICTAEAOw==');
exit();


?>