<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
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


// Content Type Newsletter Subscription
$content["newsletter"]["text"]                  = clean_slweg($_POST["cnewsletter_text"]);
$content["newsletter"]["label_email"]           = clean_slweg($_POST["cnewsletter_label_email"]);
$content["newsletter"]["label_name"]            = clean_slweg($_POST["cnewsletter_label_name"]);
$content["newsletter"]["label_subscriptions"]   = clean_slweg($_POST["cnewsletter_label_subscriptions"]);
$content["newsletter"]["all_subscriptions"]     = clean_slweg($_POST["cnewsletter_all_subscriptions"]);
$content["newsletter"]["button_text"]           = clean_slweg($_POST["cnewsletter_button_text"]);
$content["newsletter"]["success_text"]          = clean_slweg($_POST["cnewsletter_success_text"]);
$content["newsletter"]["reg_text"]              = clean_slweg($_POST["cnewsletter_reg_text"]);
$content["newsletter"]["logoff_text"]           = clean_slweg($_POST["cnewsletter_logoff_text"]);
$content["newsletter"]["change_text"]           = clean_slweg($_POST["cnewsletter_change_text"]);
$content["newsletter"]["url1"]                  = clean_slweg($_POST["cnewsletter_url1"]);
$content["newsletter"]["url2"]                  = clean_slweg($_POST["cnewsletter_url2"]);

$content['subscription_temp']                   = convertStringToArray($_POST['cnewsletter_subscription_left']);
$content["newsletter"]["subscription"]          = array();
foreach($content['subscription_temp'] as $subscr_value) {
    $subscr_value = intval($subscr_value);
    $content["newsletter"]["subscription"][$subscr_value] = $subscr_value;
}

$content["newsletter"]["pos"]                   = intval($_POST["cnewsletter_pos"]);
