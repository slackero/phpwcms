<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

function cmsgo_getCustomSitemap(&$struct) {

    // Check sitemap.php to see how $struct is used there
    global $cmsgo;

    // Avoid rendering Base sitemap link (default URL)
    //$cmsgo['sitemap_set_base'] = false;

    // Avoid default sitemap rendering, otherwise used in addition
    //$cmsgo['sitemap_set_default'] = false;

    $url = array(
        // array('url' => 'https://pixels-points.ch', 'date' => '')
    );

    // Do everything here needed to build your custom sitemap links
    // ...

    return $url;
}
