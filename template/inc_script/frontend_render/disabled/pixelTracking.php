<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// chnage setting Off to On -> do not wrap in ""

/*
 * Piwik
 * Piwik aims to be an open source alternative to Google Analytics.
 * Piwik is created from the team behind phpMyVisites.
 * visit: http://piwik.org
 */
$_Tracking_Piwik				= Off;
$_Tracking_PiwikSiteID			= 1; //usually it is ID 1
$_Tracking_PiwikURL 			= 'mystats.url/piwik';	// fill in the URI where Piwik is installed without http:// or https://
$_Tracking_PiwikUsePageTitle	= Off;


/*
 * phpMyVisites (deprecated)
 * phpMyVisites is free and is a powerful solution
 * visit: http://www.phpmyvisites.net
 *
 * !!! DO NOT USE phpMyVisites any longer !!!
 * switch to Piwik (http://www.piwik.org)
 *
 */
$_Tracking_phpMyVisites			= Off;
$_Tracking_phpMyVisitesSite		= 1; //typically it is ID 1
$_Tracking_phpMyVisistesURL 	= 'http://mystats.url/phpmyvisites.php';	// fill in the remote URL here
$_Tracking_phpMyVisistesJS  	= 'http://mystats.url/phpmyvisites.js';
$_Tracking_phpMyVisitesVars		= Off; // additional vars: full page title, cms page ID, form sender IP


/*
 * Google Analytics
 */
$_Tracking_GoogleAnalytics		= Off;
$_Tracking_GoogleAnalyticsCode	= 'UA-00000-1';
$_Tracking_GoogleSSL			= Off;


/*
 * Yahoo Analytics
 */
$_Tracking_YahooAnalytics		= Off;
$_Tracking_YahooAnalyticsCode	= '01234567890';
$_Tracking_YahooAnalyticsGroup	= Off;

/*
 * StatCounter
 * good tracking solution - basic service is free
 */
$_Tracking_StatCounter			= Off;
$_Tracking_StatCounterCode		= '000000';
$_Tracking_StatCounterSecCode	= 'aaaaaaaaa';
$_Tracking_StatCounterPartition	= 5;
$_Tracking_StatCounterSSL		= Off;


/*
 * eTracker
 * eTracker is a widely used tracking solution in Germany
 */
$_Tracking_eTracker				= Off;
$_Tracking_eTrackerCode 		= '000000';
$_Tracking_eTrackerSSL			= Off;



/// some minor things ////////////////////////////////////////////////////////////////////////////

$_TrackingCategory = array();
if(is_array($LEVEL_STRUCT) && count($LEVEL_STRUCT)) {
	$_TrackingCategory['phpwcmscategory'] = implode('%%%', $LEVEL_STRUCT);
	$_TrackingCategory['phpwcmscategory'] = cleanUpSpecialHtmlEntities($_TrackingCategory['phpwcmscategory']);
	$_TrackingCategory['phpwcmscategory'] = str_replace(array("'", '"', '/', ' ', '%%%'), array('', '', '-', '_', '/'), $_TrackingCategory['phpwcmscategory']);
}
$_TrackingAlias = PHPWCMS_ALIAS;
if($_TrackingAlias == '') {
	if($aktion[1]) {
		$_TrackingAlias = 'aid='.$aktion[1];
	} elseif($aktion[0]) {
		$_TrackingAlias = 'id='.$aktion[0];
	}
}
$_TrackingPageName = abs_url($_TrackingCategory, array(), $_TrackingAlias, 'rawurlencode');


/// phpMyVisites /////////////////////////////////////////////////////////////////////////////////

if($_Tracking_phpMyVisites) {
	$_TrackingCode  = '<script type="text/javascript">' . LF;
	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= '	var a_vars = Array();' . LF;
	if($_Tracking_phpMyVisitesVars) {
		$_TrackingCode .= '	a_vars[\'pageTitle\'] = "'.str_replace('"', '\"', $content["pagetitle"]).'";' . LF;
		$_TrackingCode .= '	a_vars[\'pageID\'] = "id='.implode(',', $aktion).'";' . LF;
		if(isset($_POST) && count($_POST) > 1) {
			$_TrackingCode .= '	a_vars[\'formSentByIP\'] = "'.getRemoteIP().'";' . LF;
		}
		if(isset($content["search_word"])) {
			$_TrackingCode .= '	a_vars[\'searchFor\'] = "';
			$_TrackingCode .= str_replace('"', '\"', $content["search_word"]);
			$_TrackingCode .= '";' . LF;
		}
	}
	$_TrackingCode .= '	var pagename="' . str_replace('"', '\"', $_TrackingPageName) . '";' . LF;
	$_TrackingCode .= '	var phpmyvisitesSite = '.$_Tracking_phpMyVisitesSite.';' . LF;
	$_TrackingCode .= '	var phpmyvisitesURL = "'.$_Tracking_phpMyVisistesURL.'";' . LF;
	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>' . LF;
	$_TrackingCode .= '<script src="'.$_Tracking_phpMyVisistesJS.'" type="text/javascript"></script>' . LF;
	$_TrackingCode .= '<noscript><img src="'.$_Tracking_phpMyVisistesURL.'" alt="" width="0" height="0" border="0" style="border:0" /></noscript>' . LF;

	$content['all'] .= $_TrackingCode;

}

//////////////////////////////////////////////////////////////////////////////////////////////////


/// Piwik ////////////////////////////////////////////////////////////////////////////////////////

if($_Tracking_Piwik) {

	$_Tracking_PiwikURL = trim($_Tracking_PiwikURL, '/');
	$_TrackingCode  = '<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://'.$_Tracking_PiwikURL.'/" : "http://'.$_Tracking_PiwikURL.'/");
document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", '.intval($_Tracking_PiwikSiteID).');';
	if($_Tracking_PiwikUsePageTitle) {
		$_TrackingCode .= LF . 'piwikTracker.setDocumentTitle("'.str_replace('"', '\"', $content["pagetitle"]).'");';
	}
	$_TrackingCode .= LF . 'piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script>
<!-- End Piwik Tag -->';

	$content['all'] .= $_TrackingCode;

}

//////////////////////////////////////////////////////////////////////////////////////////////////


/// GoogleAnalytics///////////////////////////////////////////////////////////////////////////////

if($_Tracking_GoogleAnalytics) {

	$_TrackingCode  = '<script src="';
	$_TrackingCode .= $_Tracking_GoogleSSL ? 'https://ssl' : 'http://www';
	//$_TrackingCode .= '.google-analytics.com/urchin.js" type="text/javascript">< /script>' . LF;
	$_TrackingCode .= '.google-analytics.com/ga.js" type="text/javascript"></script>' . LF;
	$_TrackingCode .= '<script type="text/javascript">' .LF;
//	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= '	try {' . LF;
	$_TrackingCode .= '		var pageTracker = _gat._getTracker("' . $_Tracking_GoogleAnalyticsCode . '");' .LF;
//	$_TrackingCode .= '	pageTracker._initData();' .LF;
// _gaq.push(['_gat._anonymizeIp']);
	$_TrackingCode .= '		pageTracker._trackPageview("'.$_TrackingPageName.'");' .LF;
	$_TrackingCode .= '	} catch(err) {}' . LF;
	//$_TrackingCode .= '	_uacct = "' . $_Tracking_GoogleAnalyticsCode . '";' .LF;
	//$_TrackingCode .= '	urchinTracker("'.$_TrackingPageName.'");' .LF;
//	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>';

	$content['all'] .= $_TrackingCode;

}
//////////////////////////////////////////////////////////////////////////////////////////////////


/// StatCounter //////////////////////////////////////////////////////////////////////////////////

if($_Tracking_StatCounter) {

	$_TrackingCode  = '<!-- Start of StatCounter Code -->' .LF;
	$_TrackingCode .= '<script type="text/javascript">' .LF;
	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= '	var sc_project="' . $_Tracking_StatCounterCode . '";' .LF;
	$_TrackingCode .= '	var sc_invisible=1;' .LF;
	$_TrackingCode .= '	var sc_partition=' . $_Tracking_StatCounterPartition . ';'. LF;
	$_TrackingCode .= '	var sc_security="' . $_Tracking_StatCounterSecCode . '";' .LF;
	$_TrackingCode .= '	var sc_https='. ( $_Tracking_StatCounterSSL ? 1 : 0 ) .';'. LF;
	$_TrackingCode .= '	var sc_remove_link=1;'. LF;
	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>' .LF;
	$_TrackingCode .= '<script type="text/javascript" src="';
	$_TrackingCode .= $_Tracking_StatCounterSSL ? 'https://secure' : 'http://www';
	$_TrackingCode .= '.statcounter.com/counter/counter';
	$_TrackingCode .= XHTML_MODE ? '_xhtml' : '';
	$_TrackingCode .= '.js"></script>' .LF;
	$_TrackingCode .= '<noscript><img src="http://c8.statcounter.com/'. $_Tracking_StatCounterCode . '/0/' . $_Tracking_StatCounterSecCode . '/1/" ';
	$_TrackingCode .= 'width="0" height="0" border="0" style="border:0;overflow:hidden;" alt="" /></noscript>' .LF;
	$_TrackingCode .= '<!-- End of StatCounter Code -->';

	$content['all'] .= $_TrackingCode;

}

//////////////////////////////////////////////////////////////////////////////////////////////////


/// eTracker /////////////////////////////////////////////////////////////////////////////////////

if($_Tracking_eTracker) {

	$_TrackingCode  = '<!-- Copyright (c) 2000-2009 etracker GmbH. All rights reserved. -->
<!-- This material may not be reproduced, displayed, modified or distributed -->
<!-- without the express prior written permission of the copyright holder. -->
<!-- BEGIN etracker Tracklet 3.0 -->
<script type="text/javascript">document.write(String.fromCharCode(60)+"script type=\"text/javascript\" src=\"http"+("https:"==document.location.protocol?"s":"")+"://code.etracker.com/t.js?et='.$_Tracking_eTrackerCode.'\">"+String.fromCharCode(60)+"/script>");</script>
<!-- etracker PARAMETER 3.0 -->
<script type="text/javascript">
var et_pagename     = "'.rawurlencode($content["pagetitle"]).'";
var et_areas		= "'.str_replace('"', '\"', implode('', $_TrackingCategory)).'";
var et_url          = "'.abs_url(array(), array('phpwcmscategory'), $_TrackingAlias, 'rawurlencode').'";
</script>
<!-- etracker PARAMETER END -->
<script type="text/javascript">_etc();</script>
<noscript><div style="overflow:hidden;width:0;height:0;"><img src="https://www.etracker.com/nscnt.php?et='.$_Tracking_eTrackerCode.'" border="0" alt="" /></div></noscript>
<!-- etracker CODE END -->';

	$content['all'] .= $_TrackingCode;

}

//////////////////////////////////////////////////////////////////////////////////////////////////


/// Yahoo Aanalytics /////////////////////////////////////////////////////////////////////////////

if($_Tracking_YahooAnalytics) {

	$_TrackingCode  = '<!-- Yahoo! Web Analytics - All rights reserved -->
<script type="text/javascript" src="http://d.yimg.com/mi/eu/ywa.js"></script>
<script type="text/javascript">
// globals YWA
var YWATracker = YWA.getTracker("'.$_Tracking_YahooAnalyticsCode.'");
//YWATracker.setDocumentName("");
'.($_Tracking_YahooAnalyticsGroup == Off ? '//' : '').'YWATracker.setDocumentGroup("'.str_replace('"', '\"', implode('', $_TrackingCategory)).'");
YWATracker.submit();
</script>
<noscript><div style="width:0;height:0;overflow:hidden"><img src="http://s.analytics.yahoo.com/p.pl?a=10001633077682&amp;js=no" width="1" height="1" alt="" /></div></noscript>';

	$content['all'] .= $_TrackingCode;

}

//////////////////////////////////////////////////////////////////////////////////////////////////


?>