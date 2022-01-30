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

//guestbook/comments

// include neccessary frontend functions, but only once
include_once PHPWCMS_ROOT.'/include/inc_front/content/cnt_functions/cnt18.func.inc.php';

$crow['attr_class_id'] = array();
if($crow['acontent_attr_class']) {
    $crow['attr_class_id'][] = 'class="'.html($crow['acontent_attr_class']).'"';
}
if($crow['acontent_attr_id']) {
    $crow['attr_class_id'][] = 'id="'.html($crow['acontent_attr_id']).'"';
}

if(($crow['attr_class_id'] = implode(' ', $crow['attr_class_id']))) {
    $CNT_TMP .= '<div '.$crow['attr_class_id'].'>';
    $crow['attr_class_id_close'] = '</div>';
} else {
    $crow['attr_class_id_close'] = '';
}

$CNT_TMP                .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
global $guestbook; // make it global
$guestbook               = unserialize($crow["acontent_form"]);
$guestbook['error']      = array();
$guestbook['cid']        = intval( empty($guestbook['aliasID']) ? $crow["acontent_id"] : $guestbook['aliasID'] );
$guestbook['image_dir']  = PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'guestbook_'.$guestbook['cid'];

// getting guestbook template
if(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/guestbook/'.$guestbook['template'])) {
    $guestbook['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/guestbook/'.$guestbook['template']) );
} else {
    $guestbook['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/guestbook.tmpl') );
}


// check 'visible' status
if(empty($guestbook['gb_login_show'])) {
    $guestbook['visible'] = true;
} elseif(_getFeUserLoginStatus()) {
    $guestbook['visible'] = true;
} else {
    $guestbook['visible'] = false;
    // get template replacement in case login necessary and user not logged in
    $CNT_TMP .= get_tmpl_section('LOGIN_INFO', $guestbook['template']);
}

if($guestbook['visible']) {

    // get guestbook sections
    $guestbook['form']          = get_tmpl_section('FORM', $guestbook['template']);
    $guestbook['signed']        = get_tmpl_section('FORM_SUCCESS', $guestbook['template']);
    $guestbook['nav']           = get_tmpl_section('NAV', $guestbook['template']);
    $guestbook['entry']         = get_tmpl_section('GUESTBOOK_ENTRY', $guestbook['template']);
    $guestbook['list']          = get_tmpl_section('GUESTBOOK', $guestbook['template']);
    $guestbook['ban']           = trim(get_tmpl_section('BAN', $guestbook['template']).' '.$guestbook['banned']);
    $guestbook['replace']       = strip_tags(trim(get_tmpl_section('BAN_REPLACE', $guestbook['template'])));
    $guestbook['ban_ip']        = trim(get_tmpl_section('BAN_IP', $guestbook['template']));
    $guestbook['comment']       = trim(get_tmpl_section('COMMENT', $guestbook['template']));
    $guestbook['comment']       = explode('|', $guestbook['comment']);
    $guestbook['comment'][0]    = trim($guestbook['comment'][0]);
    $guestbook['comment'][1]    = trim($guestbook['comment'][1]);


    // processiong post values
    if(isset($_POST['guestbook_send'])) {

        $guestbook['post']['email'] = clean_slweg(remove_unsecure_rptags($_POST['guestbook_email']));
        $guestbook['post']['name']  = clean_slweg(remove_unsecure_rptags($_POST['guestbook_name']));
        $guestbook['post']['url']   = clean_slweg(remove_unsecure_rptags($_POST['guestbook_url']));
        $guestbook['post']['msg']   = clean_slweg(remove_unsecure_rptags($_POST['guestbook_msg']));
        $guestbook['post']['msg']   = preg_replace('/\[c\](.*?)\[\/c\]/is', "$1", $guestbook['post']['msg']);
        $guestbook['post']['show']  = intval($_POST['guestbook_show']);
        if($guestbook['post']['show'] > 2) {
            $guestbook['post']['show'] = 0;
        }

        // email error
        if(!is_valid_email($guestbook['post']['email'])) {
            $guestbook['error']['email'] = 'Proof the email address: it is empty or false.';
        }
        // name error
        if(empty($guestbook['post']['name'])) {
            $guestbook['error']['name'] = 'Don&#039;t forget to insert your name.';
        }


        // banned stuff
        $guestbook['ban_count'] = 0;
        if($guestbook['ban']) {

            $guestbook['ban'] = convertStringToArray($guestbook['ban'], ' ');
            if(is_array($guestbook['ban']) && count($guestbook['ban'])) {
                foreach($guestbook['ban'] as $key => $value) {
                    if($value = trim($value)) {
                        $guestbook['ban'][$key] = '/'.preg_quote($value, '/').'/i';
                        $guestbook['ban_count']++;
                    }
                }
            }

            if($guestbook['ban_count']) {
                $guestbook['post']['msg'] = preg_replace($guestbook['ban'], $guestbook['replace'], $guestbook['post']['msg']);
            }

        }

        // processing image upload
        if(!empty($guestbook["image_upload"])) {

            $guestbook['error']['image'] = array();

            // guestbook image
            if(is_uploaded_file($_FILES['guestbook_image']['tmp_name']) && !$_FILES['guestbook_image']['error']) {

                $guestbook['image']['info'] = @getimagesize($_FILES['guestbook_image']['tmp_name']);

                if(is_array($guestbook['image']['info'])) {

                    // check if it is GIF, JPG or PNG
                    if($guestbook['image']['info'][2] == 1 || $guestbook['image']['info'][2] == 2 || $guestbook['image']['info'] == 3) {

                        $guestbook["max_image_filesize"] = return_bytes($guestbook["max_image_filesize"]);
                        if($_FILES['guestbook_image']['size'] > $guestbook["max_image_filesize"]) {

                            $guestbook['error']['image']['size']  = 'File size of uploaded image (';
                            $guestbook['error']['image']['size'] .= return_bytes_shorten($_FILES['guestbook_image']['size']);
                            $guestbook['error']['image']['size'] .= ') is larger than allowed (max.';
                            $guestbook['error']['image']['size'] .= return_bytes_shorten($guestbook["max_image_filesize"]);
                            $guestbook['error']['image']['size'] .= ').';

                        } else {

                            $guestbook['image']['name'] = $_FILES['guestbook_image']['name'];
                            $guestbook['image']['hash'] = md5($_FILES['guestbook_image']['name'].$_FILES['guestbook_image']['size'].$guestbook['image']['info'][3]);
                            $guestbook['image']['file'] = $guestbook['image']['hash'].'.';
                            switch($guestbook['image']['info'][2]) {
                                case 1: $guestbook['image']['file'] .= 'gif';   break;  //GIF
                                case 2: $guestbook['image']['file'] .= 'jpg';   break;  //JPG
                                case 3: $guestbook['image']['file'] .= 'png';   break;  //PNG
                            }

                            // create neccessary guestbook image directory
                            if(!is_dir($guestbook['image_dir'])) {
                                $old_umask = umask(0);
                                $guestbook['owner'] = fileowner(PHPWCMS_ROOT.'/'.PHPWCMS_FILES);
                                @mkdir($guestbook['image_dir'], 0777);
                                @chmod($guestbook['image_dir'], 0777);
                                @chown($guestbook['image_dir'], intval($guestbook['owner']));
                                umask($old_umask);
                            }
                            if(is_writable($guestbook['image_dir'])) {

                                if(!move_uploaded_file($_FILES['guestbook_image']['tmp_name'], $guestbook['image_dir'].'/'.$guestbook['image']['file'])) {

                                    $guestbook['error']['image']['move'] = 'Image '.html_specialchars($guestbook['image']['name']).' could not be stored. Try again!';
                                    $guestbook['image']['name'] = '';
                                    $guestbook['image']['hash'] = '';
                                    $guestbook['image']['file'] = '';
                                    unlink($_FILES['guestbook_image']['tmp_name']);

                                } else {

                                    chmod($guestbook['image_dir'].'/'.$guestbook['image']['file'], 0666);

                                }

                            } else {

                                $guestbook['error']['image']['writable'] = "Image directory is not writable. Send a notice to the webmaster of this site.";

                            }

                        }

                    } else {

                        $guestbook['error']['image']['format'] = "Proof image format: only JPG, GIF, PNG allowed.";

                    }


                } else {

                    if($_FILES['guestbook_image']['error']) {
                        $guestbook['error']['image']['system'] = return_upload_errormsg($_FILES['guestbook_image']['error']);
                    }
                    $guestbook['error']['image']['general'] = "Proof uploaded image file (only JPG, GIF, PNG allowed).";

                }

            } elseif(!empty($_POST['guestbook_hiddenfile'])) { //same file was just uploaded

                $guestbook['hidden'] = unserialize(base64_decode($_POST['guestbook_hiddenfile']));

                $guestbook['image']['name'] = $guestbook['hidden']['name'];
                $guestbook['image']['hash'] = $guestbook['hidden']['hash'];
                $guestbook['image']['file'] = $guestbook['hidden']['file'];
                if(!file_exists($guestbook['image_dir'].'/'.$guestbook['image']['file'])) {

                    $guestbook['image']['name'] = '';
                    $guestbook['image']['hash'] = '';
                    $guestbook['image']['file'] = '';

                }
            }

            $guestbook['image_error_count'] = count($guestbook['error']['image']);

            if(!$guestbook['image_error_count'] && isset($guestbook['image']['file']) && file_exists($guestbook['image_dir'].'/'.$guestbook['image']['file'])) {

                $guestbook['hidden']  = '<input type="hidden" name="guestbook_hiddenfile" value="';
                $guestbook['hidden'] .= base64_encode(serialize(array('name'=>$guestbook['image']['name'], 'hash'=>$guestbook['image']['hash'], 'file'=>$guestbook['image']['file'])));
                $guestbook['hidden'] .= '" />';

            } else {

                $guestbook['hidden'] = '';

            }

            if($guestbook['image_error_count']) {

                $guestbook['error'] = array_merge($guestbook['error'], $guestbook['error']['image']);

            }

            unset($guestbook['error']['image']);

        }
        // end of image upload


    } else {

        if(_getFeUserLoginStatus() && isset($_SESSION[ session_id().'_userdata'])) {
            $guestbook['post']['email']     = $_SESSION[ session_id().'_userdata']['email'];
            $guestbook['post']['name']      = $_SESSION[ session_id().'_userdata']['login'];
            $guestbook['post']['url']       = $_SESSION[ session_id().'_userdata']['url'];
        } else {
            $guestbook['post']['email']     = '';
            $guestbook['post']['name']      = '';
            $guestbook['post']['url']       = '';
        }
        $guestbook['post']['msg']       = '';
        $guestbook['post']['show']      = 0;
    }

    // set data for image
    if(empty($guestbook["image_upload"])) {

        $guestbook['form'] = replace_tmpl_section('IMAGE_UPLOAD', $guestbook['form']);

    } else {

        $guestbook['imgdata']   = '';
        $guestbook['entry']     = preg_replace_callback(
            '/{IMAGE:(.*)}/i',
            function($matches) {
                $GLOBALS['guestbook']['imgdata'] = $matches[1];
                return '{IMAGE}';
            },
            $guestbook['entry']
        );
        $guestbook['imgdata']   = explode('x', strtolower($guestbook['imgdata']));

        // image width
        $guestbook['imgdata'][0] = empty($guestbook['imgdata'][0]) ? '' : intval($guestbook['imgdata'][0]);
        if($guestbook['imgdata'][0] === 0) {
            $guestbook['imgdata'][0] = '';
        }
        // image height
        $guestbook['imgdata'][1] = empty($guestbook['imgdata'][1]) ? '' : intval($guestbook['imgdata'][1]);
        if($guestbook['imgdata'][1] === 0) {
            $guestbook['imgdata'][1] = '';
        }
        // image zoom
        $guestbook['imgdata'][2] = empty($guestbook['imgdata'][2]) ? 0 : 1;

    }

    $guestbook['readform']      = 0;
    $guestbook['flooding']      = 0;
    $guestbook['spamalert']     = '';

    // flooding check (cookie and time)
    if(!empty($guestbook['cookie']) && !empty($guestbook['time'])) {

        if(isset($_COOKIE['phpwcms_guestbook'.$guestbook['cid']])) {

            if($_COOKIE['phpwcms_guestbook'.$guestbook['cid']]+$guestbook['time'] >= time()) {
                $guestbook['flooding'] = 1;
                $guestbook['readform'] = 1;
            }

        }

        if(!$guestbook['flooding']) {

            $guestbook['sql']  = "SELECT MAX(guestbook_created) AS dbcreate FROM ".DB_PREPEND."phpwcms_guestbook WHERE ";
            $guestbook['sql'] .= "guestbook_cid="._dbEscape($guestbook['cid'])." AND ";
            $guestbook['sql'] .= "guestbook_trashed != '9' AND ";
            $guestbook['sql'] .= "guestbook_ip="._dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP())." AND ";
            $guestbook['sql'] .= "guestbook_useragent=MD5("._dbEscape($_SERVER['HTTP_USER_AGENT']).")";

            $guestbook['result'] = _dbQuery($guestbook['sql']);

            if(isset($guestbook['result'][0]['dbcreate']) && ($guestbook['result'][0]['dbcreate']+$guestbook['time']) >= time()) {
                $guestbook['flooding'] = 1;
                $guestbook['readform'] = 1;
            }
        }
    }

    // Captcha check
    if(empty($guestbook['captcha'])) {

        $guestbook['form'] = replace_tmpl_section('CAPTCHA', $guestbook['form']);

    } else {

        $guestbook['captcha_maxchar'] = empty($guestbook['captcha_maxchar']) ? 5 : $guestbook['captcha_maxchar'];
        $guestbook['form'] = str_replace('{CAPTCHA}', '<img src="img/captcha.php?regen=y&amp;length='.$guestbook['captcha_maxchar'].'&amp;'.time().'" alt="Captcha" id="gbCaptchaImage" />', $guestbook['form']);

    }

    if(isset($_POST['guestbook_email']) && !empty($guestbook['captcha'])) {

        include_once PHPWCMS_ROOT.'/include/inc_ext/SPAF_FormValidator.class.php';
        // instantiate the object
        $spaf_obj = new SPAF_FormValidator();
        $guestbook['post']['captcha'] = isset($_POST['guestbook_captcha']) ? clean_slweg($_POST['guestbook_captcha']) : '';
        if ($spaf_obj->validRequest($guestbook['post']['captcha'])) {
            // destroy successful code
            $spaf_obj->destroy();
        } else {
            $guestbook['error']['captcha'] = 'Fill in the correct captcha code. Proof it twice!';
        }
    }

    if(isset($_POST['guestbook_email']) && !$guestbook['flooding']) {
        // make global spam check
        if(!checkFormTrackingValue()) {
            $guestbook['flooding']  = 1;
            $guestbook['readform']  = 1;
            $guestbook['spamalert'] = '<div class="spamFormAlert">Your IP '.(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()).' is not allowed to send form!</div>';
        }
    }

    // final guestbook form check and insert into db
    if(isset($_POST['guestbook_email']) && !$guestbook['flooding']) {

        // check URL and try to connect - if fails set to ''
        if($guestbook['post']['url']) {
            $guestbook['post']['url'] = preg_replace('/(mailto|http|https):{0,1}/i', '', $guestbook['post']['url']);
            list($guestbook['post']['url']) = explode('?', $guestbook['post']['url'], 2);
            $guestbook['post']['url'] = str_replace('//', '', trim($guestbook['post']['url']));
            if($content["guestbook"]["gb_urlcheck"] && @ini_get('allow_url_fopen')) {
                if($guestbook['fp'] = @fopen('http://'.$guestbook['post']['url'], 'r')) {
                    @fclose($guestbook['fp']);
                }
                if(empty($guestbook['fp'])) {
                    $guestbook['error']['url'] = 'The given URL could not be verified.';
                }
            }
        }

        if(!count($guestbook['error'])) {

            $guestbook['sql']  = "INSERT INTO ".DB_PREPEND."phpwcms_guestbook SET ";
            $guestbook['sql'] .= "guestbook_cid="._dbEscape($guestbook['cid']).", ";
            $guestbook['sql'] .= "guestbook_msg="._dbEscape($guestbook['post']['msg']).", ";
            $guestbook['sql'] .= "guestbook_name="._dbEscape($guestbook['post']['name']).", ";
            $guestbook['sql'] .= "guestbook_email="._dbEscape($guestbook['post']['email']).", ";
            $guestbook['sql'] .= "guestbook_created='".time()."', ";
            $guestbook['sql'] .= "guestbook_url="._dbEscape($guestbook['post']['url']).", ";
            $guestbook['sql'] .= "guestbook_show='".$guestbook['post']['show']."', ";
            $guestbook['sql'] .= "guestbook_ip="._dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()).", ";
            $guestbook['sql'] .= "guestbook_useragent=MD5("._dbEscape($_SERVER['HTTP_USER_AGENT']).")";

            if(!empty($guestbook["image_upload"]) && !empty($guestbook['image']['file']) && !empty($guestbook['image']['name'])) {

                $guestbook['sql'] .= ', ';
                $guestbook['sql'] .= "guestbook_image="._dbEscape($guestbook['image']['file']).", ";
                $guestbook['sql'] .= "guestbook_imagename="._dbEscape($guestbook['image']['name']);

            }

            $guestbook['result'] = _dbQuery($guestbook['sql'], 'INSERT');

            if(isset($guestbook['result']['INSERT_ID'])) {

                $guestbook['readform'] = 1;
                if($guestbook['cookie'] && $guestbook['time']) {
                    setcookie('phpwcms_guestbook'.$guestbook['cid'], time(), time()+intval($guestbook['time']), '/', getCookieDomain(), PHPWCMS_SSL, true);
                }

                // check if notify email should be sent
                if(!empty($guestbook['notify'])) {

                    $guestbook['notify'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/guestbook/notify_email.txt');
                    if(!$guestbook['notify']) {
                        $guestbook['notify'] = 'New entry - please proof:'.LF.PHPWCMS_URL.'index.php?id='.implode(',', $aktion);
                    }

                    $guestbook['notify']    = str_replace(
                        array(
                            '{FE_URL}', '{BE_URL}', '{IP}', '{BROWSER}', '{DATE}', '{NAME}', '{EMAIL}', '{URL}', '{MESSAGE}', '{IMG_NAME}', '{IMG_URL}'
                        ),
                        array(
                            PHPWCMS_URL.'index.php?id='.implode(',', $aktion),
                            PHPWCMS_URL.'phpwcms.php?do=articles&p=2&s=1&aktion=2&id='.$aktion[1].'&acid='.$guestbook['cid'],
                            PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(),  $_SERVER['HTTP_USER_AGENT'], date('Y/m/d H:i:s'),
                            $guestbook['post']['name'], $guestbook['post']['email'],
                            $guestbook['post']['url'], $guestbook['post']['msg'],
                            empty($guestbook['image']['name']) ? '' : $guestbook['image']['name'],
                            empty($guestbook['image']['file']) ? '' : PHPWCMS_URL.PHPWCMS_FILES.'guestbook_'.$guestbook['cid'].'/'.$guestbook['image']['file']
                        ),
                        $guestbook['notify']
                    );

                    sendEmail(
                        array(
                                'recipient' => $guestbook['notify_email'],
                                'subject'   => 'New guestbook/comment entry',
                                'isHTML'    => 0,
                                'text'      => $guestbook['notify'],
                                'from'      => $phpwcms["admin_email"],
                                'sender'    => $phpwcms["admin_email"]
                        )
                    );

                }

                $GLOBALS['_getVar']['guestbookentry'] = $guestbook['result']['INSERT_ID'];

                // to avoid double Post
                headerRedirect(abs_url( array(), array(), '', 'urlencode'));

            } else {
                $guestbook['readform'] = 0;
                $CNT_TMP .= '<div style="color:#FF3300;">A technical problem occured while signing to the guestbook</div>';
            }
        }


    }

    // do this after new gb entry was created
    if(isset($GLOBALS['_getVar']['guestbookentry'])) {

        $guestbook['sql']  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_guestbook ';
        $guestbook['sql'] .= 'WHERE guestbook_id='.intval($GLOBALS['_getVar']['guestbookentry']);
        $guestbook['sql'] .= " AND guestbook_ip="._dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP());

        $guestbook['new_entry'] = _dbQuery($guestbook['sql']);

        if(!empty($guestbook['new_entry'][0])) {

            $guestbook['readform'] = 1;

            $guestbook['post']['email'] = $guestbook['new_entry'][0]['guestbook_email'];
            $guestbook['post']['name']  = $guestbook['new_entry'][0]['guestbook_name'];
            $guestbook['post']['url']   = $guestbook['new_entry'][0]['guestbook_url'];
            $guestbook['post']['msg']   = $guestbook['new_entry'][0]['guestbook_msg'];

        }

        unset($GLOBALS['_getVar']['guestbookentry']);
    }


    // start guestbook form
    if(empty($guestbook['gb_login_post'])) {
        $guestbook['show_postform'] = true;
    } elseif(_getFeUserLoginStatus()) {
        $guestbook['show_postform'] = true;
    } else {
        $guestbook['show_postform'] = false;
        $guestbook['form']          = get_tmpl_section('LOGIN_INFO', $guestbook['template']);
    }

    if($guestbook['show_postform']) {


        if(!$guestbook['readform']) {

            if(!count($guestbook['error'])) {
                // remove post form error part
                $guestbook['form'] = replace_tmpl_section('FORM_ERROR', $guestbook['form'], '');
            }

            //try to replace all error messages first
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_EMAIL',     empty($guestbook['error']['email'])    ? '' : $guestbook['error']['email']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_NAME',      empty($guestbook['error']['name'])     ? '' : $guestbook['error']['name']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_IMGSIZE',   empty($guestbook['error']['size'])     ? '' : $guestbook['error']['size']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_IMGSAVE',   empty($guestbook['error']['move'])     ? '' : $guestbook['error']['move']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_IMGWRITE',  empty($guestbook['error']['writable']) ? '' : $guestbook['error']['writable']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_IMGFORMAT', empty($guestbook['error']['format'])   ? '' : $guestbook['error']['format']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_IMGUPLOAD', empty($guestbook['error']['system'])   ? '' : $guestbook['error']['system']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_IMG',       empty($guestbook['error']['general'])  ? '' : $guestbook['error']['general']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_URL',       empty($guestbook['error']['url'])      ? '' : $guestbook['error']['url']);
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'ERROR_CAPTCHA',   empty($guestbook['error']['captcha'])  ? '' : $guestbook['error']['captcha']);

            $guestbook['form'] = render_cnt_template($guestbook['form'], 'EMAIL',   html_specialchars($guestbook['post']['email']));
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'NAME',    html_specialchars($guestbook['post']['name']));
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'URL',     html_specialchars($guestbook['post']['url']));
            $guestbook['form'] = render_cnt_template($guestbook['form'], 'MSG',     html_specialchars($guestbook['post']['msg']));

            $guestbook['GBSHOW_0'] = '';
            $guestbook['GBSHOW_1'] = '';
            $guestbook['GBSHOW_2'] = '';

            switch($guestbook['post']['show']) {
                case 0: $guestbook['GBSHOW_0'] = ' checked="checked"';  break;
                case 1: $guestbook['GBSHOW_1'] = ' checked="checked"';  break;
                case 2: $guestbook['GBSHOW_2'] = ' checked="checked"';  break;
            }

            $guestbook['form'] = str_replace('{GBSHOW_0}', $guestbook['GBSHOW_0'], $guestbook['form']);
            $guestbook['form'] = str_replace('{GBSHOW_1}', $guestbook['GBSHOW_1'], $guestbook['form']);
            $guestbook['form'] = str_replace('{GBSHOW_2}', $guestbook['GBSHOW_2'], $guestbook['form']);

            // build sign guestbook form
            $guestbook['form']  = '<form name="sign_guestbook" action="'.rel_url().'" method="post"' .
                                  (empty($guestbook["image_upload"]) ? '' : ' enctype="multipart/form-data"') .
                                  '>'.$guestbook['form'];
            if(!empty($guestbook['hidden'])) {
                $guestbook['form'] .= $guestbook['hidden'];
            }
            $guestbook['form'] .= getFormTrackingValue().'</form>';

        } elseif(!$guestbook['flooding']) {
            // if successfully signed show signed info
            $guestbook['signed'] = render_cnt_template($guestbook['signed'], 'EMAIL',   html_specialchars($guestbook['post']['email']));
            $guestbook['signed'] = render_cnt_template($guestbook['signed'], 'NAME',    html_specialchars($guestbook['post']['name']));
            $guestbook['signed'] = render_cnt_template($guestbook['signed'], 'URL',     html_specialchars($guestbook['post']['url']));
            $guestbook['signed'] = render_cnt_template($guestbook['signed'], 'MSG',     html_specialchars($guestbook['post']['msg']));
            $guestbook['form'] = $guestbook['signed'];
        } else {
            $guestbook['form'] = $guestbook['spamalert'];
        }

    }
    // end guestbook form

    // start guestbook listing

    // first check for all available related guestbook entries
    $guestbook['archivedate'] = false;
    $guestbook['archiveselect'] = false;
    $guestbook['sql']  = "SELECT * FROM ".DB_PREPEND."phpwcms_guestbook WHERE guestbook_cid=";
    $guestbook['sql'] .= $guestbook['cid']." AND guestbook_trashed=0 ";
    if(isset($_GET['gbd']) && $_GET['gbs']) {
        //$aktion[5] = 0;
        $guestbook['archivedate']   = $_GET['gbd'];
        $guestbook['archiveselect'] = $_GET['gbs'];
        $guestbook['sql'] .= "AND FROM_UNIXTIME(guestbook_created,"._dbEscape($guestbook['archivedate']);
        $guestbook['sql'] .= ")="._dbEscape($guestbook['archiveselect'])." ";
    }
    if(isset($_POST['showarchive']) && $_POST['showarchive']) {
        //$aktion[5] = 0;
        $guestbook['archivedate']   = $_POST['archivedate'];
        $guestbook['archiveselect'] = $_POST['showarchive'];
        $guestbook['sql'] .= "AND FROM_UNIXTIME(guestbook_created,"._dbEscape($guestbook['archivedate']);
        $guestbook['sql'] .= ")="._dbEscape($guestbook['archiveselect'])." ";
    }
    $guestbook['sql'] .= "AND guestbook_msg NOT LIKE '%[url%' ";
    $guestbook['sql'] .= "ORDER BY guestbook_created ";
    $guestbook['sql'] .= empty($guestbook['sorting']) ? 'DESC' : 'ASC';

    $guestbook['counter'] = 1;

    if($guestbook['listing'] && $guestbook['listcount']) {

        $guestbook['count'] = _dbQuery($guestbook['sql'], 'COUNT');
        $guestbook['pagecount'] = ceil($guestbook['count'] / $guestbook['listcount']);
        if($guestbook['pagecount'] > 1 || $guestbook['archivedate']) {

            if(isset($_POST['showguestbookpage'])) {
                $aktion[5] = intval($_POST['showguestbookpage'])-1;
            }
            $guestbook['start_entry'] = $aktion[5] * $guestbook['listcount'];
            $guestbook['sql'] .= ' LIMIT '.$guestbook['start_entry'].','.$guestbook['listcount'];

            $guestbook['link_to']  = 'index.php?';
            $guestbook['link_to'] .= 'id='.$aktion[0].','.$aktion[1].','.$aktion[2].','.$aktion[3].','.$aktion[4].',';
            $guestbook['link_add'] = '';
            if($guestbook['archivedate']) {
                $guestbook['link_add'] .= '&amp;gbd='.html_specialchars(urlencode($guestbook['archivedate']));
                $guestbook['link_add'] .= '&amp;gbs='.html_specialchars(urlencode($guestbook['archiveselect']));
            }

            // goto previous guestbook page
            if($aktion[5] > 0) {
                $guestbook['prev_replace']  = '<a href="'.$guestbook['link_to'].($aktion[5] - 1).$guestbook['link_add'].'">$1</a>';
                $guestbook['first_replace'] = '<a href="'.$guestbook['link_to'].'0'.$guestbook['link_add'].'">$1</a>';
            } else {
                $guestbook['prev_replace']  = '$1';
                $guestbook['first_replace'] = $guestbook['prev_replace'];
            }
            $guestbook['nav'] = preg_replace('/{BACK:(.*?)}/s', $guestbook['prev_replace'], $guestbook['nav']);
            $guestbook['nav'] = preg_replace('/{FIRST:(.*?)}/s', $guestbook['first_replace'], $guestbook['nav']);

            // goto next guestbook page
            if($aktion[5]+1 < $guestbook['pagecount']) {
                $guestbook['next_replace'] = '<a href="'.$guestbook['link_to'].($aktion[5] + 1).$guestbook['link_add'].'">$1</a>';
                $guestbook['last_replace'] = '<a href="'.$guestbook['link_to'].($guestbook['pagecount']-1).$guestbook['link_add'].'">$1</a>';
            } else {
                $guestbook['next_replace'] = '$1';
                $guestbook['last_replace'] = $guestbook['next_replace'];
            }
            $guestbook['nav'] = preg_replace('/{NEXT:(.*?)}/s', $guestbook['next_replace'], $guestbook['nav']);
            $guestbook['nav'] = preg_replace('/{LAST:(.*?)}/s', $guestbook['last_replace'], $guestbook['nav']);

            $guestbook['nav'] = preg_replace_callback('/{PAGE:(\d+):(.*?)}/s', 'guestbook_pages', $guestbook['nav']);

            // archive (form)
            if( ! ( strpos($guestbook['nav'],'{ARCHIVE')===false ) ) {
                preg_match('/{ARCHIVE:(.*?)}/s', $guestbook['nav'], $guestbook['archiveval']);
                $guestbook['archiveval'] = explode('|', $guestbook['archiveval'][1]);
                $guestbook['archive']  = '<form name="guestbookarchive" id="guestbookarchive" method="post" action="index.php?id='.implode(',', $aktion).'">';
                $guestbook['archive'] .= '<select name="showarchive" id="showarchive" onchange="document.guestbookarchive.submit();">';

                if(!isset($guestbook['archiveval'][1]) || !$guestbook['archiveval'][1]) {
                    $guestbook['archiveval'][1] = 'all entries';
                }
                $guestbook['archive'] .= '<option value="">'.$guestbook['archiveval'][1]."</option>\n";

                if(empty($guestbook['archiveval'][0])) {
                    $guestbook['archiveval'][0] = '%m/%Y';
                }

                $guestbook['asql']  = "SELECT DISTINCT FROM_UNIXTIME(guestbook_created,"._dbEscape($guestbook['archiveval'][0]);
                $guestbook['asql'] .= ") AS guestbook_date FROM ".DB_PREPEND."phpwcms_guestbook WHERE guestbook_cid=";
                $guestbook['asql'] .= $guestbook['cid']." AND guestbook_trashed=0 ORDER BY guestbook_created DESC";

                $guestbook['result'] = _dbQuery($guestbook['asql']);

                if(isset($guestbook['result'][0]['guestbook_date'])) {

                    foreach($guestbook['result'] as $guestbook['row']) {

                        $guestbook['row']['guestbook_date'] = html($guestbook['row']['guestbook_date']);
                        $guestbook['archive'] .= '<option value="'.$guestbook['row']['guestbook_date'].'"';
                        if($guestbook['archiveselect'] == $guestbook['row']['guestbook_date']) {
                            $guestbook['archive'] .= ' selected="selected"';
                        }
                        $guestbook['archive'] .= '>'.$guestbook['row']['guestbook_date']."</option>\n";

                    }
                }

                $guestbook['archive'] .= '</select>';
                $guestbook['archive'] .= '<input type="hidden" name="archivedate" value="'.html_specialchars($guestbook['archiveval'][0]).'" />';

                if(isset($guestbook['archiveval'][2]) && $guestbook['archiveval'][2]) {
                    $guestbook['archive'] .= (empty($guestbook['archiveval'][3])) ? '' : $guestbook['archiveval'][3];
                    // check if send button is image or text
                    if(preg_match('/[\.png|\.jpg|\.jpeg|\.gif]$/i', $guestbook['archiveval'][2], $matches)) {
                        $guestbook['archive'] .= '<input name="archivesubmit" class="guestbookArchiveSubmit" type="image" src="'.trim($guestbook['archiveval'][2]).'" />';
                    } else {
                        $guestbook['archive'] .= '<input name="archivesubmit" class="guestbookArchiveSubmit" type="submit" value="'.$guestbook['archiveval'][2].'" />';
                    }
                }
                $guestbook['archive'] .= '</form>';
                $guestbook['nav'] = preg_replace('/{ARCHIVE:(.*?)}/s', $guestbook['archive'], $guestbook['nav']);

            }

            // jump to menu (form)
            if( ! ( strpos($guestbook['nav'],'{JUMP')===false ) ) {
                preg_match('/{JUMP:(.*?)}/s', $guestbook['nav'], $guestbook['jumpval']);
                $guestbook['jumpval'] = explode('|', $guestbook['jumpval'][1]);
                $guestbook['jump']  = '<form name="guestbookjump" id="guestbookjump" method="post" action="index.php?id='.implode(',', $aktion).'">';
                $guestbook['jump'] .= '<select name="showguestbookpage" id="showpage" onchange="document.guestbookjump.submit();">';
                for($ixx=1; $ixx <= $guestbook['pagecount']; $ixx++) {
                    if($ixx != $aktion[5]+1) {
                        $guestbook['jump'] .= '<option value="'.$ixx.'">'.$guestbook['jumpval'][0].$ixx."</option>\n";
                    } else {
                        $guestbook['jump'] .= '<option value="'.$ixx.'" selected="selected">'.$guestbook['jumpval'][0].$ixx."</option>\n";
                    }
                }
                $guestbook['jump'] .= '</select>';
                if($guestbook['archivedate']) {
                    $guestbook['jump'] .= '<input type="hidden" name="archivedate" value="'.html($guestbook['archivedate']).'" />';
                    $guestbook['jump'] .= '<input type="hidden" name="showarchive" value="'.html($guestbook['archiveselect']).'" />';
                }
                if(isset($guestbook['jumpval'][1]) && $guestbook['jumpval'][1]) {
                    $guestbook['jump'] .= empty($guestbook['jumpval'][2]) ? '' : $guestbook['jumpval'][2];
                    // check if send button is image or text
                    if(preg_match('/[\.png|\.jpg|\.jpeg|\.gif]$/i', $guestbook['jumpval'][1], $matches)) {
                        $guestbook['jump'] .= '<input name="jumpsubmit" class="guestbookJumpSubmit" type="image" src="'.trim($guestbook['jumpval'][1]).'" />';
                    } else {
                        $guestbook['jump'] .= '<input name="jumpsubmit" class="guestbookJumpSubmit" type="submit" value="'.$guestbook['jumpval'][1].'" />';
                    }
                }
                $guestbook['jump'] .= '</form>';
                $guestbook['nav'] = preg_replace('/{JUMP:(.*?)}/s', $guestbook['jump'], $guestbook['nav']);
            }

            $guestbook['counter'] = $guestbook['start_entry']+1;

        } else {
            // no navigation neccessary
            $guestbook['nav'] = '';
        }

    } else {
        // no navigation neccessary
        $guestbook['nav'] = '';
    }

    $guestbook['entry_list'] = '';
    $guestbook['result'] = _dbQuery($guestbook['sql']);

    if(isset($guestbook['result'][0]['guestbook_msg'])) {

        if(!function_exists('guestbook_date_callback')) {
            function guestbook_date_callback($matches) {
                return date($matches[1], empty($GLOBALS['guestbook']['row']['guestbook_created']) ? now() : $GLOBALS['guestbook']['row']['guestbook_created']);
            }
        }

        $thumb_image = false;

        foreach($guestbook['result'] as $guestbook['row']) {

            $guestbook['row']['guestbook_msg'] = html($guestbook['row']['guestbook_msg']);

            $guestbook['c'] = str_replace('{ID}',   $guestbook['counter'],              $guestbook['entry']);
            $guestbook['c'] = str_replace('{DBID}', $guestbook['row']['guestbook_id'],  $guestbook['c']);

            $guestbook['c'] = render_cnt_template($guestbook['c'], 'URL', empty($guestbook['row']['guestbook_url']) ? '' : html('http://'.$guestbook['row']['guestbook_url']));

            switch($guestbook['row']['guestbook_show']) {
                case 1:
                    $guestbook['row']['guestbook_email'] = '';
                    break;

                case 2:
                    $guestbook['row']['guestbook_email'] = preg_replace('/(.*?)@(.*?)\.([a-zA-Z]+)$/i', "$1 at $2 dot $3", $guestbook['row']['guestbook_email']);
                    $guestbook['c'] = preg_replace('/\[EMAIL\](.*?){0,1}<a (.*?)>(.*?)<\/a>(.*?){0,1}\[\/EMAIL\]/is', "[EMAIL]$1".$guestbook['row']['guestbook_email']."$4[/EMAIL]", $guestbook['c']); //"$3"
                    break;
            }

            $guestbook['c'] = render_cnt_template($guestbook['c'], 'EMAIL', html($guestbook['row']['guestbook_email']));
            $guestbook['c'] = render_cnt_template($guestbook['c'], 'NAME',  html($guestbook['row']['guestbook_name']));
            $guestbook['c'] = render_cnt_template($guestbook['c'], 'MSG',   nl2br($guestbook['row']['guestbook_msg']));
            $guestbook['c'] = preg_replace_callback('/{TIMESTAMP:(.*)}/', 'guestbook_date_callback', $guestbook['c']);

            // do gb image ;-)
            $guestbook['entry_image'] = '';
            if(isset($guestbook['imgdata']) && !empty($guestbook['row']['guestbook_image'])) {

                if(file_exists($guestbook['image_dir'].'/'.$guestbook['row']['guestbook_image'])) {

                    $thumb_image    = false;
                    $thumb_img      = '';

                    $thumb_image = get_cached_image(array(
                        "target_ext"    =>  which_ext($guestbook['row']['guestbook_image']),
                        "image_name"    =>  $guestbook['row']['guestbook_image'],
                        "image_dir"     =>  $guestbook['image_dir'].'/',
                        "max_width"     =>  $guestbook['imgdata'][0],
                        "max_height"    =>  $guestbook['imgdata'][1],
                        "thumb_name"    =>  md5($guestbook['row']['guestbook_image'].$guestbook['imgdata'][0].$guestbook['imgdata'][1].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                    ));

                    if($thumb_image != false) {

                        $guestbook['entry_image']  = '<img src="' . $thumb_image['src'] . '" ' . $thumb_image[3];
                        $guestbook['entry_image'] .= ' alt="' . html($guestbook['row']['guestbook_imagename']) . '"';
                        $guestbook['entry_image'] .= PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

                        //zoom
                        if($guestbook['imgdata'][2]) {
                            $zoominfo = get_cached_image(array(
                                "target_ext"    =>  which_ext($guestbook['row']['guestbook_image']),
                                "image_name"    =>  $guestbook['row']['guestbook_image'],
                                "image_dir"     =>  $guestbook['image_dir'].'/',
                                "max_width"     =>  $phpwcms["img_prev_width"],
                                "max_height"    =>  $phpwcms["img_prev_height"],
                                "thumb_name"    =>  md5($guestbook['row']['guestbook_image'].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                            ));

                            if($zoominfo != false) {

                                $popup_img = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $guestbook['row']['guestbook_image']);
                                $guestbook['entry_image'] = '<a href="'.$popup_img.'" onclick="window.open(\''.$popup_img.
                                                            "','previewpic','width=".$zoominfo[1].",height=".$zoominfo[2]."');return false;".
                                                            '">'.$guestbook['entry_image'].'</a>';
                            }
                        }
                    }
                }
            }
            $guestbook['c'] = render_cnt_template($guestbook['c'], 'IMAGE', $guestbook['entry_image']);

            $guestbook['entry_list'] .= $guestbook['c'];

            $guestbook['counter']++;
        }

        // initialize lightbox
        if($thumb_image != false) {
            initSlimbox();
        }

        // comments
        $guestbook['entry_list'] = preg_replace('/\[c\](.*?)\[\/c\]/is', $guestbook['comment'][0]."$1".$guestbook['comment'][1], $guestbook['entry_list']);

    }
    $guestbook['list'] = str_replace('{NAV}', $guestbook['nav'], $guestbook['list']);
    $guestbook['list'] = str_replace('{FORM}', $guestbook['form'], $guestbook['list']);
    $guestbook['list'] = replace_tmpl_section('GUESTBOOK_ENTRY', $guestbook['list'], $guestbook['entry_list']);

    $CNT_TMP .= $guestbook['list'];

}

$CNT_TMP .= $crow['attr_class_id_close'];

// delete guetbook array
unset($guestbook);
