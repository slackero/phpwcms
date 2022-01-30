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

// Content Type Newsletter Subscription
$content["newsletter"] = unserialize($row["acontent_newsletter"]);

if(empty($content["newsletter"]["recaptcha_config"])) {

    $content["newsletter"]["recaptcha_config"] = '';

} else {

    $recaptcha_config = '';

    foreach($content["newsletter"]["recaptcha_config"] as $key => $value) {
        $recaptcha_config .= trim($key . ' = ' . $value) . LF;
    }

    $content["newsletter"]["recaptcha_config"] = trim($recaptcha_config);

}
