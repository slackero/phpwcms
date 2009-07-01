<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// chnage setting Off to On -> do not wrap in ""


/*
 * phpMyVisites
 * phpMyVisites is free and is a pwerful solution
 * visit: http://www.phpmyvisites.net
 */
$_Tracking_phpMyVisites			= Off;
$_Tracking_phpMyVisitesSite		= 1; //typically it is ID 1
$_Tracking_phpMyVisistesURL 	= 'http://mystats.url/phpmyvisites.php';	// fill in the remote URL here
$_Tracking_phpMyVisistesJS  	= 'http://mystats.url/phpmyvisites.js';
$_Tracking_phpMyVisitesVars		= Off; // additional vars: full page title, cms page ID, form sender IP 


/*
 * Google Aanalytics
 */
$_Tracking_GoogleAnalytics		= Off;
$_Tracking_GoogleAnalyticsCode	= 'UA-00000-1';
$_Tracking_GoogleSSL			= Off;


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





/// some minor thinhgs////////////////////////////////////////////////////////////////////////////

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
$_TrackingPageName = rel_url($_TrackingCategory, array(), $_TrackingAlias, 'rawurlencode');


/// phpMyVisites /////////////////////////////////////////////////////////////////////////////////

if($_Tracking_phpMyVisites) {
	$_TrackingCode  = '<script language="javascript" type="text/javascript">' . LF;
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
	$_TrackingCode .= '<script language="javascript" src="'.$_Tracking_phpMyVisistesJS.'" type="text/javascript"></script>' . LF;
	$_TrackingCode .= '<noscript><img src="'.$_Tracking_phpMyVisistesURL.'" alt="" width="0" height="0" border="0" style="border:0" /></noscript>' . LF;

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
	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= '	var pageTracker = _gat._getTracker("' . $_Tracking_GoogleAnalyticsCode . '");' .LF;
	$_TrackingCode .= '	pageTracker._initData();' .LF;
	$_TrackingCode .= '	pageTracker._trackPageview("'.$_TrackingPageName.'");' .LF;
	//$_TrackingCode .= '	_uacct = "' . $_Tracking_GoogleAnalyticsCode . '";' .LF;
	//$_TrackingCode .= '	urchinTracker("'.$_TrackingPageName.'");' .LF;
	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>';
	
	$content['all'] .= $_TrackingCode;

}
//////////////////////////////////////////////////////////////////////////////////////////////////


/// StatCounter //////////////////////////////////////////////////////////////////////////////////

if($_Tracking_StatCounter) {

	$_TrackingCode  = LF . '<!-- Start of StatCounter Code -->' .LF;
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
	$_TrackingCode .= '<noscript><img src="http://c8.statcounter.com/counter.php?sc_project='. $_Tracking_StatCounterCode;
	$_TrackingCode .= '&amp;java=0&amp;security=' . $_Tracking_StatCounterSecCode . '&amp;invisible=1" ';
	$_TrackingCode .= 'width="0" height="0" border="0" style="border:0" alt="" /></noscript>' .LF;
	$_TrackingCode .= '<!-- End of StatCounter Code -->';
	
	$content['all'] .= $_TrackingCode;
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////


/// eTracker /////////////////////////////////////////////////////////////////////////////////////

if($_Tracking_eTracker) {

	$_TrackingCode  = LF . '<!-- etracker PLUGIN DETECTION 2.3 -->' . LF;
	$_TrackingCode .= '<script type="text/javascript" src="http'.($_Tracking_eTrackerSSL ? 's' : '').'://www.etracker.de/et_mmedetect.js"></script>' . LF;
	$_TrackingCode .= '<!-- etracker PLUGIN DETECTION END -->' . LF;

	$_TrackingCode .= LF . '<!-- etracker PARAMETER 2.3 -->' . LF;
	$_TrackingCode .= '<script type="text/javascript">' . LF;
	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= 'var et_easy     = 0;' . LF;
	$_TrackingCode .= 'var et_ssl      = 0;' . LF;
	$_TrackingCode .= 'var et_pagename = "' . rawurlencode($_TrackingPageName) . '";' . LF;
	$_TrackingCode .= 'var et_areas    = "";' . LF;
	$_TrackingCode .= 'var et_ilevel   = 0;' . LF;
	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>' .LF;
	$_TrackingCode .= '<!-- etracker PARAMETER END -->' . LF;
	
	$_TrackingCode .= '<!-- etracker URL PARAMETER 2.3 -->' . LF;
	$_TrackingCode .= '<script type="text/javascript">' . LF;
	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= "var et_up=''; function et_pQ(param){var ll,fl, pV, rS; var qS=
document.location.search; pV=''; if(qS.length>1){ qS=qS.substr
(1);fl=qS.indexOf(param);if(fl!=-1){fl+=param.length + 1;ll=qS
.indexOf('&',fl);if(ll== - 1 )ll = qS.length;pV = qS.substring
(fl,ll);rS=new RegExp(' ','g');pV=pV.replace( rS,'+' ); fl=pV.
indexOf('=',0);pV = pV.substring( fl+1 );}}return pV;}if((tc =
et_pQ( 'et_cid' ))&&(tl=et_pQ( 'et_lid' )))et_up += '&amp;et'+
'_cid=' + tc +  '&amp;et_lid='  + tl ; if( typeof( et_sub ) !=
'undefined' && typeof( et_sub )!='unknown')et_up += '&amp;et'+
'_sub='+et_sub;else if( et_tmp=et_pQ( 'et_sub' ))et_up += '&'+
'amp;et_sub='+et_tmp;if( typeof( et_target ) != 'undefined' &&
typeof( et_target )!='unknown') if (tt = et_pQ('et_target') ||
et_target!='' ){ tv = et_pQ('et_tval'); to = et_pQ('et_tonr');
ts=et_pQ('et_tsale');}" . LF;
	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>' . LF;
	$_TrackingCode .= '<!-- etracker URL PARAMETER 2.3 END -->' . LF;
	
	$_TrackingCode .= '<!-- etracker CODE 2.3 -->' . LF;
	$_TrackingCode .= '<script type="text/javascript">' . LF;
	$_TrackingCode .= SCRIPT_CDATA_START . LF;
	$_TrackingCode .= 'var et_server = "http://www.etracker.de";' . LF;
	$_TrackingCode .= 'var et_sslserver = "https://www.etracker.de";' . LF;
	$_TrackingCode .= "var et_referer = et_server + '/app?et=', cex = '';" .LF;
	$_TrackingCode .= "var et_la = '', et_js = 1, et_pl, et_co, et_iw,  et_sh, et_sw;" .LF;
	$_TrackingCode .= "var et_sc = 'na',   et_gp = '',   lt = '<',   et_ih ,  et_up ;" .LF;
	$_TrackingCode .= 'if( typeof( tt ) == "undefined" || typeof( tt ) == "unknown" )' .LF;
	$_TrackingCode .= "{var tt='';} var tv, to,  ts,  et_tmp; function et_eC( param )" .LF;
	$_TrackingCode .= "{var et_a = '', et_t = '', et_p = '', et_b='', et_s='',et; " .LF;
	$_TrackingCode .= 'var et_ref=escape(document.referrer);if(et_js>=1.3){if(typeof(top.' . LF;
	$_TrackingCode .= "document)=='object')eval('try{et_ref=escape(top.document.ref'+" . LF;
	$_TrackingCode .= "'errer);}catch(e){et_ref=\"\";}');} if(et_ref!='') et_gp+='&am'+" .LF;
	$_TrackingCode .= "'p;ref='+et_ref; et_gp +='&amp;swidth='+et_sw+'&amp;sheight='+" .LF;
	$_TrackingCode .= "et_sh+'&amp;siwidth='+et_iw+'&amp;siheight='+et_ih+'&amp;sco'+" .LF;
	$_TrackingCode .= "'okie='+et_co+'&amp;scolor='+et_sc;if(et_easy)et_gp+='&amp;e'+" .LF;
	$_TrackingCode .= "'t_easy=1'; if( et_pl!='' ) et_gp+='&amp;p='+escape(et_pl);if(" .LF;
	$_TrackingCode .= "et_areas != '' )et_gp += '&amp;et_areas='+escape(et_areas);if(" .LF;
	$_TrackingCode .= 'typeof(et_target)== "undefined"||typeof(et_target)=="unknown")' .LF;
	$_TrackingCode .= '{et_target = ""; et_tval = "0";  et_tonr = "0"; et_tsale = 0;}' .LF;
	$_TrackingCode .= 'et_gp+="&amp;"+"et_target="+ escape( tt.length?tt:et_target )+' .LF;
	$_TrackingCode .= '"," + ( tv?tv:et_tval ) + "," + ( to?to:et_tonr )+ ","+(ts?ts:' .LF;
	$_TrackingCode .= 'et_tsale )+","+( typeof( et_cust ) == "number"?et_cust:0 );if(' .LF;
	$_TrackingCode .= "typeof( et_lpage )=='undefined' ||typeof(et_lpage)=='unknown')" .LF;
	$_TrackingCode .= "et_lpage=''; else et_gp += '&amp;et_lpage='+et_lpage;if(typeof" .LF;
	$_TrackingCode .= "(et_se) == 'undefined' || typeof(et_se)=='unknown')et_se='';if" .LF;
	$_TrackingCode .= "(et_se!='')et_gp +='&amp;et_se='+et_se;if(typeof( et_trig ) ==" .LF;
	$_TrackingCode .= "'undefined'||typeof(et_trig)=='unknown')et_trig='';if( et_trig" .LF;
	$_TrackingCode .= "!='' )et_gp+='&amp;et_trig='+et_trig;if(et_pagename!='') et_gp" .LF;
	$_TrackingCode .= "+='&amp;et_pagename='+escape(et_pagename);if(typeof(et_basket)" .LF;
	$_TrackingCode .= "=='string')et_gp += '&amp;et_basket=' + escape(et_basket); if(" .LF;
	$_TrackingCode .= "typeof(et_up)=='undefined'||typeof(et_up)=='unknown')et_up='';" .LF;
	$_TrackingCode .= "et=document.location.href.split('?'); et_gp += '&amp;et_url='+" .LF;
	$_TrackingCode .= "escape( et[0] ); et_gp += '&amp;slang=' +et_la; tc=new Date();" .LF;
	$_TrackingCode .= "document.write( lt + \"a target='_blank' href='\" +  et_referer+" .LF;
	$_TrackingCode .= "param+\"'>\" +lt  + \"img border='0' alt='' src='\" + ( et_ssl==1?" .LF;
	$_TrackingCode .= "et_sslserver:et_server) + \"/fcnt.php?v=2.3&amp;java=y&amp;tc=\"+" .LF;
	$_TrackingCode .= 'tc.getTime()+ "&amp;et="+param +"&amp;et_ilevel=" + et_ilevel+' .LF;
	$_TrackingCode .= "et_gp  +  et_up  +  \"'/>\" +lt+\"/a>\"  ) ; }" .LF;
	
	$_TrackingCode .= "document.write(lt+'script lan'+'guage=\"JavaScript1.3\"> var et_js = 1.3;' + lt + '/script>' );" . LF . LF;
	
	$_TrackingCode .= 'et_sw = screen.width;' .LF ;
	$_TrackingCode .= 'et_sh = screen.height;' . LF;
	$_TrackingCode .= 'et_sc = ( screen.pixelDepth ) ? screen.pixelDepth : screen.colorDepth;'. LF;
	$_TrackingCode .= "if(et_js>=1.3) {eval('try{et_iw='+'top.innerWidth;et_ih=top.innerHeight;} catch(e){et_iw=window'+'.innerWidth;et_ih=window.innerHeight;}');}" . LF;
	$_TrackingCode .= 'et_co = (navigator.cookieEnabled==true ? 1 : (navigator.cookieEnabled==false ? 2 : 0));' . LF;
	$_TrackingCode .= 'if(navigator.language) {et_la=navigator.language;} else if(navigator.userLanguage) {et_la  =  navigator.userLanguage;}' . LF . LF;
	$_TrackingCode .= '// start eTracker JavaScript Tracking' .LF;
	$_TrackingCode .= "et_eC( '".$_Tracking_eTrackerCode."' );" . LF . LF;
	
	$_TrackingCode .= SCRIPT_CDATA_END . LF;
	$_TrackingCode .= '</script>' . LF;
	
	$_TrackingCode .= '<!-- etracker CODE NOSCRIPT -->' . LF;
	$_TrackingCode .= '<noscript>';
	//$_TrackingCode .= '<a href="http://www.etracker.de/app?et='.$_Tracking_eTrackerCode.'">';
	$_TrackingCode .= '<img alt="eTracker" src="http'.($_Tracking_eTrackerSSL ? 's' : '').'://www.etracker.de/fcnt.php?et=8SVy5E&amp;v=2.3&amp;java=n&amp;et_easy=0';
	$_TrackingCode .= '&amp;et_pagename='.rawurlencode($_TrackingPageName);
	$_TrackingCode .= '&amp;et_areas=&amp;et_ilevel=0&amp;et_target=,,,1';
	$_TrackingCode .= '&amp;et_lpage=&amp;et_trig=&amp;et_se=0&amp;et_cust=0';
	$_TrackingCode .= '&amp;et_basket=&amp;et_url=" width="0" height="0" border="0" style="border:0px;" />';
	//$_TrackingVode .= '</a>';
	$_TrackingCode .= '</noscript>' . LF;
	$_TrackingCode .= '<!-- etracker CODE END -->';
	
	$content['all'] .= $_TrackingCode;

}

//////////////////////////////////////////////////////////////////////////////////////////////////


?>