<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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

// Module/Plug-in Shop & Products
require_once dirname(__FILE__).'/inc/functions.global.inc.php';

function is_cart_filled() {

    if(empty($_SESSION[CART_KEY]['products'])) {

        return false;

    } elseif(!is_array($_SESSION[CART_KEY]['products'])) {

        return false;

    } elseif(!count($_SESSION[CART_KEY]['products'])) {

        return false;

    } elseif(isset($_SESSION[CART_KEY]['total']) && empty($_SESSION[CART_KEY]['total'])) {

        return false;
    }

    return true;
}

function get_cart_data() {

    // retrieve all cart data
    if(!is_cart_filled()) {
        return array();
    }

    $in = array();
    foreach($_SESSION[CART_KEY]['products'] as $key => $value) {
        $key = intval($key);
        $in[$key] = $key;
    }

    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_shop_products WHERE shopprod_status=1 AND ';
    $sql .= 'shopprod_id IN (' . implode(',', $in) . ')';

    $data = _dbQuery($sql);

    if(isset($data[0])) {

        foreach($data as $key => $value) {

            $data[$key]['shopprod_quantity'] = $_SESSION[CART_KEY]['products'][ $value['shopprod_id'] ];

        }

    }

    return $data;
}

function shop_image_tag($img=array(), $counter=0, $title='') {

    $config =& $GLOBALS['_tmpl']['config'];

    // set image values
    $width		= $config['image_'.$config['mode'].'_width'];
    $height		= $config['image_'.$config['mode'].'_height'];
    $crop		= $config['image_'.$config['mode'].'_crop'];
    $caption	= empty($img['caption']) ? '' : ' :: '.$img['caption'];
    $title		= empty($title) ? '' : ' title="'.html($title.$caption).'"';

    $thumb_image = get_cached_image(array(
        "target_ext"	=>	$img['f_ext'],
        "image_name"	=>	$img['f_hash'] . '.' . $img['f_ext'],
        "max_width"		=>	$width,
        "max_height"	=>	$height,
        "thumb_name"	=>	md5($img['f_hash'].$width.$height.$GLOBALS['phpwcms']["sharpen_level"].$crop.$GLOBALS['phpwcms']['colorspace']),
        'crop_image'	=>	$crop
    ));

    if($thumb_image) {

        // now try to build caption and if neccessary add alt to image or set external link for image
        $caption	= getImageCaption(array('caption' => $img['caption'], 'file' => $img['f_id']));
        // set caption and ALT Image Text for imagelist
        $caption[3] = empty($caption[3]) ? $title : ' title="'.html($caption[3]).'"'; //title
        $caption[1] = html(empty($caption[1]) ? $img['f_name'] : $caption[1]);

        $list_img_temp  = '<img src="'.$thumb_image['src'].'" class="' . $config['image_class'] . '" ';
        $list_img_temp .= $thumb_image[3] . ' alt="' . $caption[1] . '"' . $caption[3];
        $list_img_temp .= PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

        // use lightbox effect
        if($config['image_'.$config['mode'].'_lightbox']) {

            $a  = '<a href="'.PHPWCMS_RESIZE_IMAGE.'/';
            $a .= $config['image_zoom_width'] . 'x' . $config['image_zoom_height'] . '/';
            $a .= $img['f_hash'] . '.' . $img['f_ext'] . '/' . rawurlencode($img['f_name']) . '" ';
            $a .= 'target="_blank" rel="lightbox'.$config['lightbox_id'].'"' . get_attr_data_gallery($config['lightbox_id'], ' ', '');
            $a .= $caption[3] . '>';

            $list_img_temp = $a . $list_img_temp . '</a>';
        }

        $class = empty($counter) ? '' : ' img-num-'.$counter;

        return '<span class="shop-article-img'.$class.'">' . $list_img_temp . '</span>';

    }

    return '';
}

function get_shop_category_name($id=0, $subid=0) {
    if(empty($id)) {
        return '';
    }
    $cat_name = '';

    $sql  = 'SELECT cat_name FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
    $sql .= "cat_type='module_shop' AND cat_status=1 AND cat_id=" . $id . ' LIMIT 1';
    $data = _dbQuery($sql);

    if(isset($data[0]['cat_name'])) {
        $cat_name = $data[0]['cat_name'];
    }

    if($subid) {

        $sql  = 'SELECT cat_name FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
        $sql .= "cat_type='module_shop' AND cat_status=1 AND cat_id=" . $subid . ' LIMIT 1';
        $data = _dbQuery($sql);

        if(isset($data[0]['cat_name'])) {
            if($cat_name) {
                $cat_name .= str_replace('_', ' ', $GLOBALS['_tmpl']['config']['cat_subcat_spacer']);
            }
            $cat_name .= $data[0]['cat_name'];
        }
    }

    return $cat_name;
}

function get_payment_options() {

    $payment_prefs = _getConfig( 'shop_pref_payment', '_shopPref' );
    $supported = array('prepay' => 0, 'pod' => 0, 'onbill' => 0, 'cash' => 0);
    $available = array();
    foreach($supported as $key => $value) {
        if(!empty($payment_prefs[$key])) {
            $available[$key] = $payment_prefs[$key];
        }
    }
    return $available;
}

function get_category_products($selected_product_cat, $shop_detail_id, $shop_cat_selected, $shop_subcat_selected, $shop_alias) {

    $shop_cat_prods = '';

    $sql  = "SELECT * FROM ".DB_PREPEND.'phpwcms_shop_products WHERE ';
    $sql .= "shopprod_status=1";
    $sql .= ' AND (';
    $sql .= "shopprod_category = '" . $selected_product_cat . "' OR ";
    $sql .= "shopprod_category LIKE '%," . $selected_product_cat . ",%' OR ";
    $sql .= "shopprod_category LIKE '" . $selected_product_cat . ",%' OR ";
    $sql .= "shopprod_category LIKE '%," . $selected_product_cat . "'";
    $sql .= ')';
    // FE language
    $sql .= SHOP_FELANG_SQL;
    $pdata = _dbQuery($sql);

    if(is_array($pdata) && count($pdata)) {

        $z = 0;
        $shop_cat_prods = array();
        foreach($pdata as $prow) {

            $shop_cat_prods[$z] = '<li id="shopcat-product-'.$prow['shopprod_id'].'"';
            if($prow['shopprod_id'] == $shop_detail_id) {
                $shop_cat_prods[$z] .= ' class="active"';
            }
            $shop_cat_prods[$z] .= '>';

            $prow['get'] = array(
                'shop_cat' => $shop_cat_selected,
                'shop_detail' => $prow['shopprod_id']
            );
            if($shop_subcat_selected) {
                $prow['get']['shop_cat'] .= '_' . $shop_subcat_selected;
            }

            $shop_cat_prods[$z] .= '<a href="' . rel_url($prow['get'], array(), $shop_alias) . '">';
            $shop_cat_prods[$z] .= html($prow['shopprod_name1']);
            $shop_cat_prods[$z] .= '</a>';
            $shop_cat_prods[$z] .= '</li>';
            $z++;
        }

        if(count($shop_cat_prods)) {
            $shop_cat_prods = LF . '		<ul class="'.$GLOBALS['template_default']['classes']['shop-products-menu'].'">' . LF.'			' . implode(LF.'			', $shop_cat_prods) . LF .'		</ul>' . LF.'	';
        }

    }

    return $shop_cat_prods;

}

function shop_files($data=array()) {

    global $phpwcms;

    $value = array(
        'cnt_object'			=> array('cnt_files' => array('id' => array(), 'caption' => array())), // id, caption
        'files_direct_download'	=> $GLOBALS['_tmpl']['config']['files_direct_download'],
        'files_template'		=> $GLOBALS['_tmpl']['config']['files_template']
    );

    foreach($data as $item) {
        $value['cnt_object']['cnt_files']['id'][]		= $item['f_id'];
        $value['cnt_object']['cnt_files']['caption'][]	= $item['caption'];
    }

    $IS_NEWS_CP	= true;
    $news		= array('files_result' => '');
    $crow		= array();

    // include content part files renderer
    include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

    return $news['files_result'];

}

function get_shop_option_value_config() {
    return array(
        'dec_point' => $GLOBALS['_tmpl']['config']['dec_point'],
        'thousands_sep' => $GLOBALS['_tmpl']['config']['thousands_sep'],
        'null' => $GLOBALS['_tmpl']['config']['price_option_null'],
        'prefix' => $GLOBALS['_tmpl']['config']['price_option_prefix'],
        'suffix' => $GLOBALS['_tmpl']['config']['price_option_suffix'],
        'hide' => $GLOBALS['_tmpl']['config']['price_option_hide'] ? true : false,
    );
}
