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

// some mod ADS functions only needed in frontend, but done right after opening page
// script contains everything necessary to track ad banner clicks and so on...

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// first check
if(isset($_GET['u']) && $_GET['u'] == PHPWCMS_USER_KEY) {

	$ads_id = intval($_GET['adclickval']);

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_campaign ';
	$sql .= 'WHERE adcampaign_id='.$ads_id.' AND adcampaign_status=1 LIMIT 1';
	$ad_data = _dbQuery($sql);

	if(!empty($ad_data[0]['adcampaign_data'])) {
		$ad_data = @unserialize($ad_data[0]['adcampaign_data']);

		$ads_userip		= PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP();
		$ads_useragent	= $_SERVER['HTTP_USER_AGENT'];
		$ads_ref		= isset($_GET['r']) ? trim($_GET['r']) : '';
		$ads_cat		= empty($_GET['c']) ? 0 : intval($_GET['c']);
		$ads_article	= empty($_GET['a']) ? 0 : intval($_GET['a']);

		if(empty($_COOKIE['phpwcmsAdsUserId']) || !preg_match('/^[0-9a-f]{32}$/', ($ads_userid = $_COOKIE['phpwcmsAdsUserId']) ) ) {
			$ads_userid	= md5($ads_userip.microtime());
			setcookie('phpwcmsAdsUserId', $ads_userid, time()+63072000, '/', getCookieDomain(), PHPWCMS_SSL, true);
		}

		$sql  =	'INSERT DELAYED INTO '.DB_PREPEND.'phpwcms_ads_tracking (';
		$sql .= 'adtracking_created, adtracking_campaignid, adtracking_ip, adtracking_cookieid, ';
		$sql .= 'adtracking_countclick, adtracking_countview, adtracking_useragent, adtracking_ref, ';
		$sql .= 'adtracking_catid, adtracking_articleid) VALUES (';
		$sql .= "NOW(), ".$ads_id.", "._dbEscape($ads_userip).", "._dbEscape($ads_userid).", ";
		$sql .= "1, 0, "._dbEscape($ads_useragent).", "._dbEscape($ads_ref).", ".$ads_cat.", ".$ads_article.")";

		@_dbQuery($sql, 'INSERT');

		$sql  = 'UPDATE LOW_PRIORITY '.DB_PREPEND.'phpwcms_ads_campaign SET ';
		$sql .= 'adcampaign_curclick=adcampaign_curclick+1 WHERE adcampaign_id='.$ads_id;

		@_dbQuery($sql, 'UPDATE');

		headerRedirect($ad_data['url']);

	}
}

headerRedirect(PHPWCMS_URL);
