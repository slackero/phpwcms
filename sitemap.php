<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

// build Google Sitemap based on available articles

$phpwcms = array();
require_once ('config/phpwcms/conf.inc.php');

// set neccessary charset
$phpwcms["charset"] = 'utf-8';
require_once ('include/inc_lib/default.inc.php');
define('VISIBLE_MODE', 0);
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/config/phpwcms/conf.indexpage.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_front/front.func.inc.php');

// start XML 
echo '<?xml version="1.0" encoding="utf-8"?>'.LF;
echo '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" 
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
	xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 
	http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">'.LF;
echo '	<!-- Google Sitemap, http://www.google.com/webmasters/sitemaps/ -->'.LF.LF;


//reads complete structure as array
$struct	= get_struct_data();

// fallback value when no article available
$base	= true;

// now retrieve all articles
$sql  =	"SELECT article_id, article_cid, DATE_FORMAT(article_tstamp, '%Y-%m-%d') AS article_tstamp, ";
$sql .= "article_title, article_redirect, article_aliasid, article_alias ";
$sql .= "FROM ".DB_PREPEND."phpwcms_article WHERE ";
$sql .= "article_public=1 AND article_aktiv=1 AND article_deleted=0 AND article_nosearch!='1' AND ";
$sql .= "article_nositemap=1 AND article_begin < NOW() AND article_end > NOW() ";
$sql .= "ORDER BY article_tstamp DESC";

if($result = mysql_query($sql, $db)) {

	while($data = mysql_fetch_assoc($result)) {
	
		// first proof if this article is within an "public" structure section
		$_CAT = $struct[$data['article_cid']];
		if($_CAT['acat_regonly'] || $_CAT['acat_nosearch']=='1' || !$_CAT['acat_nositemap']) {
			// no it is no public article - so jump to next entry
			continue;
		}
		
		// now add article URL to Google sitemap
		if(empty($phpwcms['rewrite_url']) || empty($data['article_alias'])) {
			$_link = PHPWCMS_URL.'index.php?'.setGetArticleAid( $data );
		} else {
			$_link = PHPWCMS_URL.rawurlencode($data['article_alias']).'.phtml';
		}
		echo '	<url>'.LF;
    	echo '		<loc>'.$_link.'</loc>'.LF;
		echo '		<lastmod>'.$data["article_tstamp"].'</lastmod>'.LF;
		echo '	</url>'.LF;  
		
		// yes we have a minimum of 1 article link
		$base = false;

	}
}

echo LF.'	<!-- try to add custom URLs from INI file... ';
$_addURL = parse_ini_file (PHPWCMS_ROOT.'/config/phpwcms/sitemap.custom.ini', true);
echo '-->'.LF;

if(is_array($_addURL) && count($_addURL)) {
	
	foreach($_addURL as $value) {
	
		$_link = empty($value['url']) ? '' : trim($value['url']);
		if(empty($_link)) {
			continue;
		}
		$_lastmod = empty($value['date']) ? '' : trim($value['date']);
		if(empty($value['date'])) {
			$_lastmod = date('Y-m-d');
		}
		echo '	<url>'.LF;
    	echo '		<loc>'.$_link.'</loc>'.LF;
		echo '		<lastmod>'.$_lastmod.'</lastmod>'.LF;
		echo '	</url>'.LF;  
	
	}
	
} else {
	echo '	<!-- ...no custom URL available for inclusion -->'.LF.LF;
}

if($base) {
	// just return the main URL
	echo '	<url>'.LF;
    echo '		<loc>'.PHPWCMS_URL.'</loc>'.LF;
	echo '		<lastmod>'.date('Y-m-d').'</lastmod>'.LF;
	echo '	</url>'.LF;  
}

echo '</urlset>';

?>