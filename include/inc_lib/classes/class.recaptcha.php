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

class phpwcmsRecaptcha {

    public $sitekey = '';
    public $secretkey = '';
    public $callback = '';
    private $api_script_src = 'https://www.google.com/recaptcha/api.js';
    private $api_verify_url = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct($sitekey=null, $secretkey=null) {

        if($sitekey !== null && is_string($sitekey)) {
            $this->sitekey = $sitekey;
        } elseif(!empty($GLOBALS['phpwcms']['recaptcha_site_key'])) {
            $this->sitekey = $GLOBALS['phpwcms']['recaptcha_site_key'];
        }

        if($secretkey !== null && is_string($secretkey)) {
            $this->secretkey = $secretkey;
        } elseif(!empty($GLOBALS['phpwcms']['recaptcha_secret_key'])) {
            $this->secretkey = $GLOBALS['phpwcms']['recaptcha_secret_key'];
        }

    }

    public function get_site_key() {

        return $this->sitekey;

    }

    public function get_secret_key() {

        return $this->secretkey;

    }

    public function get_callback() {

        return $this->callback;

    }

    public function verify_response($recaptcha){

        $result = array(
            'success' => false,
            'error-codes' => 'missing-input',
        );

        if(empty($recaptcha)) {
            return $result;
        }

        $data = array(
            'secret' => $this->get_secret_key(),
            'remoteip' => getRemoteIP(),
            'response' => $recaptcha,
        );

        if(($verify_response = file_get_contents($this->api_verify_url.'?'.$this->encode_query_string($data)))) {
            $response = json_decode($verify_response, true);
        }

        if (!empty($response['success'])) {
            $result['success'] = true;
            $result['error-codes'] = null;
        } else {
            $result['error-codes'] = empty($response['error-codes']) ? 'invalid-input-response' : $response['error-codes'];
        }

        return $result;

    }

    public function get_api_src($lang=null, $script_tag=false) {

        $src = $this->api_script_src;

        if($lang !== null) {
            $src .= '?hl='.$lang;
        }

        if($script_tag === true) {

            return '<script'.SCRIPT_ATTRIBUTE_TYPE.' src="'.$src.'"></script>';

        }

        return $api_src;

    }

    private function encode_query_string($data) {

        $param = array();

        foreach($data as $key => $value) {
            $param[] = $key . '=' . rawurlencode(stripslashes(trim($value)));
        }

        return implode('&', $param);
    }

    public function get_onsubmit_function($uniquekey='', $formid='', $script=true) {

        $uniquekey = trim($uniquekey);
        $formid = trim($formid);

        $this->callback = 'onSubmitRecaptchaInv'.$uniquekey;

        $js = 'function onSubmitRecaptchaInv'.$uniquekey.'(token){document.getElementById("'.$formid.'").submit();}';
        if($script) {
            return '<script'.SCRIPT_ATTRIBUTE_TYPE.'> ' . $js . '</script>';
        }

        return $js;
    }

}
