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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Module/Plug-in Ads/Banner Management
// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to make global replacements

// $content['all'] = str_replace('{MY_TAG}', 'My Replacement', $content['all'];

// OK, lets get path of that file - normally we know but yes we can take it:
// $_module_root = dirname($value);
// better than using this:
// $_module_root = PHPWCMS_ROOT.'/include/inc_module/mod_ads';
// but OK too as long mods folder is not renamed:

if (strpos($content['all'], '{ADS_')) {

    // OK ADS TAG found and now do the rest :)
    include_once dirname($value) . '/inc/ads.fe_render.inc.php';

    $content['ADS_ALL'] = array();
    $content['all'] = preg_replace_callback('/\{ADS_(\d+)\}/', 'renderAds', $content["all"]);

    if (count($content['ADS_ALL'])) {

        //render ads tracking image here.
        $content['all'] .= '<img alt="blank" width="0" height="0" src="img/blank.php?t=';
        $content['all'] .= implode('%2C', $content['ADS_ALL']) . '&amp;u=' . PHPWCMS_USER_KEY;
        $content['all'] .= '&amp;r=' . (empty($_SERVER['HTTP_REFERER']) ? '' : urlencode($_SERVER['HTTP_REFERER']));
        $content['all'] .= '&amp;c=' . $aktion[0] . '&amp;a=' . $aktion[1] . '&amp;k=' . md5(microtime()) . '" />';
    }
}
