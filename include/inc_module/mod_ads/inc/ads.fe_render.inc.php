<?php

/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// some mod ADS functions only needed in frontend

function renderAds($match) {

	if(empty($match[1])) {
		return '';
	} elseif(!($adID = intval($match[1])))  {
		return '';
	} elseif($GLOBALS['IS_A_BOT']) {
		return '';
	} elseif(BROWSER_OS == 'Other') {
		return '';
	}

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_campaign ac ';
	$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_ads_place ap ON ';
	$sql .= 'ap.adplace_id=ac.adcampaign_place ';
	$sql .= 'WHERE ac.adcampaign_place='.$adID.' AND ';
	$sql .= 'ac.adcampaign_status=1 AND ap.adplace_status=1 AND ';
	$sql .= '(ac.adcampaign_datestart IS NULL OR ac.adcampaign_datestart < NOW()) AND ';
	$sql .= '(ac.adcampaign_dateend IS NULL OR ac.adcampaign_dateend > NOW()) AND ';
	$sql .= '(ac.adcampaign_maxview=0 OR (ac.adcampaign_maxview > 0 AND ac.adcampaign_maxview >= ac.adcampaign_curview)) AND ';
	$sql .= '(ac.adcampaign_maxclick=0 OR (ac.adcampaign_maxclick > 0 AND ac.adcampaign_maxclick >= ac.adcampaign_curclick))';

	$ads  = _dbQuery($sql);
    $ad = array();

	if(is_array($ads) && count($ads) ) {

		if(empty($_COOKIE['phpwcmsAdsUserId'])) {

			$ad = $ads[array_rand($ads)];

		} else {

			$ads_userid = $_COOKIE['phpwcmsAdsUserId'];
			while(count($ads)) {

				$ad_index	= array_rand($ads);
				$ad			= $ads[$ad_index];

				if($ad['adcampaign_maxviewuser']) {

					//check how often selected ad was viewed by user
					$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_ads_tracking WHERE ';
					$sql .= 'adtracking_campaignid='.$ad['adcampaign_id'].' AND ';
					$sql .= "adtracking_cookieid="._dbEscape($ads_userid);
					$ads_viewed = _dbQuery($sql, 'COUNT');

					if($ads_viewed <= $ad['adcampaign_maxviewuser']) {
						break;
					} else {
						unset($ads[$ad_index]);
					}

				} else {
					break;
				}

			}
			if(!count($ads)) {
				return '';
			}

		}

	} else {
		return '';
	}

	$ad['adcampaign_data']	= @unserialize($ad['adcampaign_data'], ['allowed_classes' => false]);
	$ad['dir']				= PHPWCMS_CONTENT.PHPWCMS_ADS_DIR.'/'.$ad['adcampaign_id'];
	$ad['content_dir']		= CONTENT_PATH.PHPWCMS_ADS_DIR.'/'.$ad['adcampaign_id'].'/';
	if($ad['adcampaign_type']!=2 && $ad['adcampaign_type']!=4 && !is_dir($ad['dir'])) {
		return '';
	}
	$ad['dir']			   .= '/';
	if(!empty($ad['adcampaign_data']['css']) && is_file($ad['dir'].$ad['adcampaign_data']['css'])) {
		$GLOBALS['block']['custom_htmlhead'][] = '  <link rel="stylesheet" type="text/css" href="'.$ad['content_dir'].$ad['adcampaign_data']['css'].'"'.HTML_TAG_CLOSE;
	}

	$ad_media	= '';
	$ad_title	= ' title="'.html($ad['adcampaign_data']['title_text'] ? $ad['adcampaign_data']['title_text'] : $ad['adcampaign_data']['url']).'"';
	$ad_alt		= $ad['adcampaign_data']['alt_text'] ? ' alt="'.html_specialchars($ad['adcampaign_data']['alt_text']).'"' : ' alt=""';
	$ad_wxh		= ' style="width:'.$ad['adplace_width'].'px;height:'.$ad['adplace_height'].'px;"';
	$ad_imgsrc	= $ad['content_dir'].$ad['adcampaign_data']['image'];
	$ad_swfsrc	= $ad['content_dir'].$ad['adcampaign_data']['flash'];
	$ad_random	= md5( time().@microtime() );
	$ad_urldata	= '&amp;u='.PHPWCMS_USER_KEY.'&amp;r='.(empty($_SERVER['HTTP_REFERER']) ? '' : urlencode($_SERVER['HTTP_REFERER'])).'&amp;c='.$GLOBALS['aktion'][0].'&amp;a='.$GLOBALS['aktion'][1].'&amp;k='.$ad_random;

	switch($ad['adcampaign_type']) {

		case 0:	//Bild
				if(empty($ad['adcampaign_data']['image']) || !is_file($ad['dir'].$ad['adcampaign_data']['image'])) {
					return '';
				}
				$ad_imgsrc	 = html_specialchars($ad_imgsrc);
				$ad_media	.= '<a href="index.php?adclickval='.$ad['adcampaign_id'].'&amp;url='.urlencode($ad['adcampaign_data']['url']).$ad_urldata.'"';
				$ad_media	.= $ad_title;
				if($ad['adcampaign_data']['target']) {
					$ad_media	.= ' target="'.$ad['adcampaign_data']['target'].'"';
				}
				$ad_media	.= '><img src="'.$ad_imgsrc.'" border="0"'.$ad_wxh.$ad_alt.HTML_TAG_CLOSE.'</a>';
				break;

		case 1:	//Video (MP4 / WebM)
				$_videosrc = '';
				if(!empty($ad['adcampaign_data']['video']) && is_file($ad['dir'].$ad['adcampaign_data']['video'])) {
					$_videosrc = $ad['content_dir'].$ad['adcampaign_data']['video'];
				}
				if(empty($_videosrc)) {
					if(!empty($ad['adcampaign_data']['image']) && is_file($ad['dir'].$ad['adcampaign_data']['image'])) {
						$ad_imgsrc = html_specialchars($ad_imgsrc);
						$ad_media .= '<a href="index.php?adclickval='.$ad['adcampaign_id'].'&amp;url='.urlencode($ad['adcampaign_data']['url']).$ad_urldata.'"';
						$ad_media .= $ad_title;
						if($ad['adcampaign_data']['target']) {
							$ad_media .= ' target="'.$ad['adcampaign_data']['target'].'"';
						}
						$ad_media .= '><img src="'.$ad_imgsrc.'" border="0"'.$ad_wxh.$ad_alt.HTML_TAG_CLOSE.'</a>';
					}
					break;
				}

				$_videotype = (which_ext($_videosrc) === 'webm') ? 'video/webm' : 'video/mp4';
				$_poster = (!empty($ad['adcampaign_data']['image']) && is_file($ad['dir'].$ad['adcampaign_data']['image'])) ? ' poster="'.html_specialchars($ad_imgsrc).'"' : '';
				
				$ad_media .= '<a href="index.php?adclickval='.$ad['adcampaign_id'].'&amp;url='.urlencode($ad['adcampaign_data']['url']).$ad_urldata.'"';
				$ad_media .= $ad_title;
				if($ad['adcampaign_data']['target']) {
					$ad_media .= ' target="'.$ad['adcampaign_data']['target'].'"';
				}
				$ad_media .= ' style="display:block;position:relative;width:'.$ad['adplace_width'].'px;height:'.$ad['adplace_height'].'px;overflow:hidden;">';
				$ad_media .= '<video autoplay muted loop playsinline'.$_poster.' style="width:100%;height:100%;object-fit:contain;pointer-events:none;">';
				$ad_media .= '<source src="'.html_specialchars($_videosrc).'" type="'.$_videotype.'" />';
				$ad_media .= '</video>';
				$ad_media .= '</a>';
				break;

		case 2:	//HTML
				if(!empty($ad['adcampaign_data']['html'])) {
					if($ad['adcampaign_data']['bordercolor']) {
						$ad_wxh  = ' style="width:'.($ad['adplace_width']-2).'px;height:'.($ad['adplace_height']-2).'px;';
						$ad_wxh .= 'border:1px solid '.$ad['adcampaign_data']['bordercolor'].';';
					} else {
						$ad_wxh  = ' style="width:'.$ad['adplace_width'].'px;height:'.$ad['adplace_height'].'px;';
					}
					if($ad['adcampaign_data']['bgcolor']) {
						$ad_wxh .= 'background-color:'.$ad['adcampaign_data']['bgcolor'].';';
					}
					$ad_media .= '<div id="adBannerHTML'.$adID.'"'.$ad_wxh.'">';
					$ad_media .= '<a href="index.php?adclickval='.$ad['adcampaign_id'].'&amp;url='.urlencode($ad['adcampaign_data']['url']).$ad_urldata.'"';
					$ad_media .= $ad_title;
					if($ad['adcampaign_data']['target']) {
						$ad_media .= ' target="'.$ad['adcampaign_data']['target'].'"';
					}
					$ad_media .= ' style="width:'.$ad['adplace_width'].'px;height:'.$ad['adplace_height'].'px;display:block;">';
					$ad_media .= $ad['adcampaign_data']['html'] . '</a></div>';
				}

				break;

		case 3:	//HTML5 Package (Zip/iframe)
				$_html5_src = '';
				if(!empty($ad['adcampaign_data']['html5']) && is_file($ad['dir'].$ad['adcampaign_data']['html5'])) {
					$_html5_src = $ad['content_dir'].$ad['adcampaign_data']['html5'];
				} elseif(is_file($ad['dir'].'index.html')) {
					$_html5_src = $ad['content_dir'].'index.html';
				}

				if(!empty($_html5_src)) {
					$_click_url = 'index.php?adclickval='.$ad['adcampaign_id'].'&amp;url='.urlencode($ad['adcampaign_data']['url']).$ad_urldata;
					$_sep = (strpos($_html5_src, '?') !== false) ? '&amp;' : '?';
					$_iframe_src = $_html5_src . $_sep . 'clickTag=' . urlencode($_click_url);

					$ad_media .= '<iframe src="'.html_specialchars($_iframe_src).'" style="width:'.$ad['adplace_width'].'px;height:'.$ad['adplace_height'].'px;border:0;overflow:hidden;" scrolling="no" frameborder="0"></iframe>';
				} elseif(!empty($ad['adcampaign_data']['image']) && is_file($ad['dir'].$ad['adcampaign_data']['image'])) {
					$ad_imgsrc = html_specialchars($ad_imgsrc);
					$ad_media .= '<a href="index.php?adclickval='.$ad['adcampaign_id'].'&amp;url='.urlencode($ad['adcampaign_data']['url']).$ad_urldata.'"';
					$ad_media .= $ad_title;
					if($ad['adcampaign_data']['target']) {
						$ad_media .= ' target="'.$ad['adcampaign_data']['target'].'"';
					}
					$ad_media .= '><img src="'.$ad_imgsrc.'" border="0"'.$ad_wxh.$ad_alt.HTML_TAG_CLOSE.'</a>';
				}
				break;

		case 4: //Remote HTML Code
				if(!empty($ad['adcampaign_data']['html'])) {
					$ad_media .= $ad['adcampaign_data']['html'];
				}
				break;

	}

	//set ads tracking image here.
	$GLOBALS['content']['ADS_ALL'][] = $ad['adcampaign_id'];

	return $ad['adplace_prefix'].$ad_media.$ad['adplace_suffix'];

}
