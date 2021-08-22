<?php

$ACCESS                 = array();

$ACCESS['prefix']       = '<!-- Access //-->';
$ACCESS['suffix']       = '<!-- Access Close //-->';

$ACCESS['error']        = false;
$ACCESS['error_js']     = 'You have to agree.';

// file which contains dialog info located under 'template/inc_script/access/'
$ACCESS['source_data']  = 'access_template.html';

$redirect_id    = $content['struct'][ $content['cat_id'] ]['acat_struct'];
$redirect       = empty($content['struct'][ $redirect_id ]['acat_alias']) ? '?id=' . $redirect_id : '?' . $content['struct'][ $redirect_id ]['acat_alias'];
$redirect       = PHPWCMS_URL.'index.php'.$redirect;

if(!empty($_SERVER['HTTP_REFERER']) && strpos(strtolower($_SERVER['HTTP_REFERER']), strtolower(PHPWCMS_URL)) !== FALSE) {
    $redirect = $_SERVER['HTTP_REFERER'];
}

if(isset($_POST['agree_reject'])) {

    if(isset($_SESSION['phpwcmsAgree'])) {
        unset($_SESSION['phpwcmsAgree']);
    }
    setcookie('phpwcmsAgree', '0', time()-1000000, '/', getCookieDomain(), PHPWCMS_SSL, true);

    if(isset($_POST['agree_redirect'])) {
        $redirect = clean_slweg($_POST['agree_redirect']);
    }

    headerRedirect($redirect);

} elseif(isset($_POST['agree_agree'])) {

    if(empty($_POST['access_agree']) || $_POST['access_agree'] != 'agree') {

        $ACCESS['error'] = true;

    } else {

        setcookie('phpwcmsAgree', '1', 0, '/', getCookieDomain(), PHPWCMS_SSL, true);
        $_SESSION['phpwcmsAgree'] = true;

    }

}

if(!empty($_SESSION['phpwcmsAgree']) || ( isset($_COOKIE['phpwcmsAgree']) && $_COOKIE['phpwcmsAgree'] == 1)) {

    $content['all'] = str_replace($ACCESS['prefix'], '', $content['all']);
    $content['all'] = str_replace($ACCESS['suffix'], '', $content['all']);

} elseif( strpos($content['all'], $ACCESS['prefix']) !== FALSE && strpos($content['all'], $ACCESS['suffix']) !== FALSE ) {

    $block['custom_htmlhead']['js.cookie.min.js']   = '  <script src="'.TEMPLATE_PATH.'lib/js-cookie/js.cookie.min.js" type="text/javascript"></script>';
    $block['custom_htmlhead']['access.js']   = '  <script src="'.TEMPLATE_PATH.'inc_script/access/access.js" type="text/javascript"></script>';
    $block['custom_htmlhead']['set_vars']    = '  <script type="text/javascript">'.LF;
    $block['custom_htmlhead']['set_vars']   .= '  var redirect="'.$redirect.'";' . LF;
    $block['custom_htmlhead']['set_vars']   .= '  var erroralert="'.$ACCESS['error_js'].'";';
    $block['custom_htmlhead']['set_vars']   .= LF.'  </script>';
    $block['custom_htmlhead']['access.css']  = '  <link rel="stylesheet" type="text/css" href="'.TEMPLATE_PATH.'inc_script/access/access.css" />';

    $ACCESS['source_data'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_script/access/'.$ACCESS['source_data']);

    if($ACCESS['source_data']) {

        $ACCESS['source_data']  = render_cnt_template($ACCESS['source_data'], 'ERROR', $ACCESS['error'] ? '-' : '');

        $ACCESS['source_data']  = str_replace('{CURRENT_URL}', FE_CURRENT_URL, $ACCESS['source_data']);
        $ACCESS['source_data']  = str_replace('{REDIRECT}', html_specialchars($redirect), $ACCESS['source_data']);

        $ACCESS['dialog']  = '<div id="access_dialog">' . LF . $ACCESS['source_data'] . LF . '</div>' . LF ;
        $ACCESS['dialog'] .= '<div id="access_save" style="display:none">';

        $content['all'] = str_replace($ACCESS['prefix'], $ACCESS['dialog'], $content['all']);
        $content['all'] = str_replace($ACCESS['suffix'], LF . '</div>', $content['all']);

    }

} else {

    $content['all'] = str_replace($ACCESS['prefix'], '', $content['all']);
    $content['all'] = str_replace($ACCESS['suffix'], '', $content['all']);

}
