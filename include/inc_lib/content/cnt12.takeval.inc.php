<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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
