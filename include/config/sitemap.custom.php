<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

function phpwcms_getCustomSitemap(&$struct) {

    // Check sitemap.php to see how $struct is used there
    global $phpwcms;

    // Avoid rendering Base sitemap link (default URL)
    //$phpwcms['sitemap_set_base'] = false;

    // Avoid default sitemap rendering, otherwise used in addition
    //$phpwcms['sitemap_set_default'] = false;

    $url = array(
        // array('url' => 'https://www.phpwcms.org', 'date' => '')
    );

    // Do everything here needed to build your custom sitemap links
    // ...

    return $url;
}
