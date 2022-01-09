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

// Content Part Types
// DO NOT define 30 = used for modules
$wcs_content_type = array(
     0 => $BL['be_ctype_plaintext'] ,
     6 => $BL['be_ctype_html'],
    14 => $BL['be_ctype_wysiwyg'],
    11 => $BL['be_ctype_code'],
     1 => $BL['be_ctype_textimage'],
    29 => $BL['be_ctype_imagesdiv'],
    31 => $BL['be_ctype_imagesspecial'],
    32 => $BL['be_ctype_tabs'],
     2 => $BL['be_ctype_images'],
     4 => $BL['be_ctype_bulletlist'],
   100 => $BL['be_ctype_ullist'],
     3 => $BL['be_ctype_link'],
     5 => $BL['be_ctype_linklist'],
     8 => $BL['be_ctype_linkarticle'],
    33 => $BL['be_news'],
    15 => $BL['be_ctype_articlemenu'],
     9 => $BL['be_ctype_multimedia'],
     7 => $BL['be_ctype_filelist'],
    16 => $BL['be_ctype_ecard'],
    23 => $BL['be_ctype_simpleform'],
    10 => $BL['be_ctype_emailform'].' [old]',
    12 => $BL['be_ctype_newsletter'],
    13 => $BL['be_ctype_search'],
    18 => $BL['be_ctype_guestbook'],
    19 => $BL['be_ctype_sitemap'],
    21 => $BL['be_ctype_pages'],
    22 => $BL['be_ctype_rssfeed'],
    50 => $BL['be_ctype_reference'],
    51 => $BL['be_ctype_map'],
    52 => $BL['be_ctype_phpvar'],
    24 => $BL['be_ctype_alias'],
    89 => $BL['be_ctype_poll'], // jens poll
    26 => $BL['be_ctype_recipe'],
    27 => $BL['be_ctype_faq'],
    28 => $BL['be_ctype_felogin'],
    25 => $BL['be_ctype_flashplayer']
);

// set module content parts = 30
if(count($phpwcms['modules'])) {
    foreach($phpwcms['modules'] as $value) {
        if($value['cntp']) {
            $wcs_content_type[30] = $BL['be_ctype_module'];
            break;
        }
    }
}

if(!empty($phpwcms['cnt_sort'])) {
    if($phpwcms['cnt_sort'] == 'a-z') {
        natcasesort($wcs_content_type);
    } elseif($phpwcms['cnt_sort'] == 'z-a') {
        natcasesort($wcs_content_type);
        $wcs_content_type = array_reverse($wcs_content_type, true);
    }
}
