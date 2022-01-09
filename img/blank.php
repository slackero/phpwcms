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

// tracking pixel

$phpwcms = array();
$root = rtrim(str_replace('\\', '/', realpath(dirname(__FILE__).'/../') ), '/').'/';
require_once $root.'/include/config/conf.inc.php';
require_once $root.'/include/inc_lib/default.inc.php';

// first check
if(!empty($_GET['t']) && isset($_GET['u']) && $_GET['u'] == PHPWCMS_USER_KEY) {

	$ads = explode(',', $_GET['t']);
	$ads = array_map('intval', $ads);
	$ads = array_diff($ads, array(0));
	$ads = array_unique($ads);

	if(count($ads)) {

		require PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
		require PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
		require PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

		$ads_userip		= PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP();
		$ads_useragent	= $_SERVER['HTTP_USER_AGENT'];
		$ads_ref		= isset($_GET['r']) ? trim($_GET['r']) : '';
		$ads_cat		= empty($_GET['c']) ? 0 : intval($_GET['c']);
		$ads_article	= empty($_GET['a']) ? 0 : intval($_GET['a']);

		if(empty($_COOKIE['phpwcmsAdsUserId']) || !preg_match('/^[0-9a-f]{32}$/', ($ads_userid = $_COOKIE['phpwcmsAdsUserId']) ) ) {
			$ads_userid	= md5($ads_userip.microtime());
			setcookie('phpwcmsAdsUserId', $ads_userid, time()+63072000, '/', getCookieDomain(), PHPWCMS_SSL, true);
		}

		$t = array();
		foreach($ads as $value) {
			$t[] = "(NOW(), ".$value.", "._dbEscape($ads_userip).", "._dbEscape($ads_userid).", ".
				   "0, 1, "._dbEscape($ads_useragent).", "._dbEscape($ads_ref).", ".$ads_cat.", ".$ads_article.")";
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
