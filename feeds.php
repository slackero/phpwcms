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

// creates feeds

$phpwcms = array();

require_once ('config/phpwcms/conf.inc.php');
require_once ('include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_front/front.func.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_ext/feedcreator.class.php');
require_once (PHPWCMS_ROOT.'/config/phpwcms/conf.indexpage.inc.php');

$feeds_formats	= array('0.91', 'RSS0.91', '1.0', 'RSS1.0', '2.0', 'RSS2.0', 'ATOM', 'ATOM1.0', 'ATOM0.3');
$feeds 			= array();
$feeds 			= @parse_ini_file(PHPWCMS_ROOT.'/config/phpwcms/feeds.ini.php', true);

if(empty($feeds) || (is_array($feeds) && count($feeds) == 0)) {

	$feeds['default'] = array(
								"title"				=> "RSS 2.0",
								"description"		=> "",
								"link"				=> PHPWCMS_URL,
								"syndicationURL"	=> PHPWCMS_URL.'feeds.php',
								"imagesrc"			=> "",
								"imagetitle"		=> "",
								"imagelink"			=> "",
								"imagedescription"	=> "",
								"timeZone"			=> "+01:00",
								"cacheTTL"			=> 3600,
								"structureID"		=> "",
								"useauthor"			=> 1,
								"feedAuthor"		=> "",
								"feedEmail"			=> "",
								"maxentries"		=> 10,
								"encoding"			=> "UTF-8",
								"defaultFormat"		=> "RSS2.0",
								"filename"			=> "default_feed.xml",
								"orderBy"			=> 'livedate'
							  );

}

// chheck which feed data should be used
reset($feeds);
$default 				= isset($feeds['default']) ? 'default' : key($feeds);
$custom  				= isset($_GET['feed']) ? strval(clean_slweg($_GET['feed'])) : $default;

if(!isset($feeds[$custom])) {

	if($custom != '') {
		$feeds[$default]['structureID']	= $custom;
	}
	$custom				= $default;

}

$FEED 					= $feeds[$custom];

$FEED['defaultFormat']	= empty($_GET['format']) ? trim($FEED['defaultFormat']) : strtoupper(clean_slweg($_GET['format']));
$FEED['defaultFormat']	= in_array($FEED['defaultFormat'], $feeds_formats) ? $FEED['defaultFormat'] : "RSS2.0";

if(!empty($FEED['structureID'])) {

	$FEED['structureID'] = explode(',', $FEED['structureID']);
	foreach($FEED['structureID'] as $key => $value) {
		$value = getFeedStructureID($value);
		if($value == '') {
			unset($FEED['structureID'][$key]);
		} else {
			$FEED['structureID'][$key] = intval($value);
		}
	}
	$FEED['structureID'] = array_unique($FEED['structureID']);

	if(count($FEED['structureID'])) {
		$FEED['structureID'] = implode(',', $FEED['structureID']);
		
		if(isset($_GET['feed']) && $FEED['structureID'] != '') {
			$FEED['filename'] = $FEED['structureID'].'.xml';
		}
		
	} else {
		$FEED['structureID'] = '';
	}

}

if(empty($FEED['filename'])) {
	$FEED['filename'] = md5($custom.$FEED['title']).'.xml';
}
$FEED['filename']		= 'content/rss/'.$FEED['defaultFormat'].'-'.$FEED['filename'];
$FEED['maxentries']		= intval($FEED['maxentries']);
$FEED['useauthor']		= intval($FEED['useauthor']);
$FEED['encoding']		= empty($FEED['encoding']) ? 'utf-8' : $FEED['encoding'];

define('FEED_ENCODING', trim(strtolower($FEED['encoding'])));
define("TIME_ZONE","+01:00");

$rss 						= new UniversalFeedCreator();
$rss->useCached($FEED['defaultFormat'], $FEED['filename'], intval($FEED['cacheTTL'])); 
$rss->title 				= $FEED['title']; 
$rss->description 			= $FEED['description']; 
$rss->link 					= $FEED['link']; 
$rss->syndicationURL 		= $FEED['syndicationURL'];
$rss->encoding				= FEED_ENCODING;
if(!empty($FEED['feedAuthor'])) {
	$rss->editor			= $FEED['feedAuthor'];
}
if(!empty($FEED['feedEmail'])) {
	$rss->editorEmail		= $FEED['feedEmail'];
}

if(!empty($FEED['imagesrc'])) {

	$image 					= new FeedImage(); 
	$image->title 			= $FEED['imagetitle']; 
	$image->url 			= $FEED['imagesrc']; 
	$image->link			= $FEED['imagelink']; 
	$image->description		= $FEED['imagedescription']; 
	$rss->image 			= $image;

}

$sql  =	"SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_changeDate ";
$sql .= "FROM ".DB_PREPEND."phpwcms_article ar LEFT JOIN ".DB_PREPEND."phpwcms_articlecat ac ON ";
$sql .=	"ar.article_cid=ac.acat_id WHERE ";

if(isset($FEED['structureID']) && $FEED['structureID'] != '') {

	$sql .= " ar.article_cid IN (". $FEED['structureID'] .") AND ";

}

$sql .= "ar.article_public=1 AND ar.article_aktiv=1 AND ";
$sql .= "ar.article_deleted=0 AND ar.article_begin < NOW() ";
$sql .= "AND ar.article_end > NOW() AND ar.article_nosearch=0 ";
$sql .= "AND article_norss=1 AND IF(ar.article_cid=0, ";
$sql .= $indexpage['acat_aktiv'] && empty($indexpage['acat_regonly']) ? '1' : '0';
$sql .= ", ac.acat_aktiv=1 AND ac.acat_trash=0 AND ac.acat_regonly=0) ";

// define ordering
if(empty($FEED['orderBy'])) {
	$FEED['orderBy'] = 'livedate';
}
switch(strtolower(trim($FEED['orderBy']))) {

						// createdate
	case 'createdate':	$FEED['orderBy'] = 'ar.article_created';	
						break;

						// changedate
	case 'changedate':	$FEED['orderBy'] = 'ar.article_tstamp';	
						break;

						// killdate
	case 'killdate':	$FEED['orderBy'] = 'ar.article_end';	
						break;
	
						// livedate
	default:			$FEED['orderBy'] = 'ar.article_begin';

}
// define ASC, DESC, RAND
if(empty($FEED['order'])) {
	$FEED['order'] = 'DESC';
}
switch(strtoupper(trim($FEED['orderBy']))) {

					// random
	case 'RAND':	$FEED['order'] = 'RAND()';
					break;

					// ascending
	case 'ASC':		$FEED['order'] = $FEED['orderBy'] . ' ASC';
					break;
	
					// descending
	default:		$FEED['order'] = $FEED['orderBy'] . ' DESC';

}

//$sql .= "ORDER BY ar.article_begin DESC";
$sql .= 'ORDER BY ' . $FEED['order'];

if($FEED['maxentries']) {
	$sql .= " LIMIT ".$FEED['maxentries'];
}
$timePlus = 0;

//dumpVar($sql); exit();

if($result = mysql_query($sql, $db)) {
	while($data = mysql_fetch_assoc($result)) {
	
		$item = new FeedItem();
		$item->title 			= combinedParser($data["article_title"], FEED_ENCODING);
		$item->link 			= PHPWCMS_URL.'index.php?'.setGetArticleAid( $data );
		$item->description 		= combinedParser( empty($data["article_summary"]) ? $data["article_subtitle"] : $data["article_summary"] , FEED_ENCODING); 
		$item->date 			= $data['article_created'] + $timePlus;
		$item->updateDate		= $data['article_changeDate'] + $timePlus + 1;
		$item->source 			= PHPWCMS_URL;
		
		if($FEED['useauthor'] || $FEED['defaultFormat'] == 'ATOM' || $FEED['defaultFormat'] == 'ATOM1.0') {
		
			if(!empty($data["article_username"])) {
				$item->author 	= $FEED['feedEmail'].' ('.combinedParser($data["article_username"]).')';
			} elseif($FEED['defaultFormat'] == 'ATOM' || $FEED['defaultFormat'] == 'ATOM1.0') {
				$item->author 	= $FEED['feedAuthor'];
			}

		}
		
		$item->guid				= PHPWCMS_URL.'index.php?'.setGetArticleAid( $data );
		$rss->addItem($item);
		
		$timePlus += 2;
	}
} 



$rss->saveFeed($FEED['defaultFormat'], $FEED['filename']); 



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//enym.com function  remove incompatible characters
function utf8_for_xml($string)
  {
      return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
  }


function combinedParser($string, $charset='utf-8', $allowed_tags='') {

	$string = html_parser($string);
	$string = clean_replacement_tags($string, $allowed_tags);
	
	$string = str_replace('&nbsp;', ' ', $string);

	$string = decode_entities($string);
	$string = cleanUpSpecialHtmlEntities($string);

	if(!empty($string) && PHPWCMS_CHARSET != $charset) {
		$string = makeCharsetConversion($string, PHPWCMS_CHARSET, $charset);
	} else {
		$string = html_specialchars($string);
		//enym.com remove incompatible characters 
		$string = utf8_for_xml($string);
	}
	
	return $string;
}


function getFeedStructureID($value) {
	$value = trim($value);	
	if($value != '' && !is_num($value)) {
		//check for correct structureID when alias is given
		global $indexpage;
		$value = strtolower($value);
		if($indexpage['acat_aktiv'] && empty($indexpage['acat_regonly']) && strtolower($indexpage['acat_alias']) == $value) {
			return '0';
		}
		$sql  = "SELECT acat_id FROM ".DB_PREPEND."phpwcms_articlecat WHERE ";
		$sql .= "acat_aktiv=1 AND acat_trash=0 AND acat_regonly=0 AND acat_alias LIKE '";
		$sql .= aporeplace($value)."' LIMIT 1";
		$value = '';
		if($result = mysql_query($sql, $GLOBALS['db'])) {
			if($row = mysql_fetch_row($result)) {
				$value = strval($row[0]);
			}
			mysql_free_result($result);
		}
	}
	return $value;
}

function is_num($var) {
	for ($i = 0; $i < strlen($var); $i++) {
		$ascii_code = ord($var[$i]);
		if(intval($ascii_code) >= 48 && intval($ascii_code) <= 57) {
			continue;
		} else {          
			return false;
		}
	} 
	return true;
}



?>
