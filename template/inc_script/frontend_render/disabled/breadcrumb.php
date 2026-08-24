<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * Alternative way of building a breadcrumb
 * It will show article title too and act different when in article list mode
 * This works different from default breadcrumb because it is level based
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 */

if (strpos($content['all'], '{BREADCRUMB_ARTICLE}') !== false) {

    // Set level where to start with breadcrumb - default 0 = Root level
    $_breadcrumb_start_level    = 0;

    // Separate Breadcrumb items with
    $_breadcrumb_spacer         = ' &gt; ';

    // Wrap inner link text by prefix/suffix <a> %PREFIX% Linktext %SUFFIX% </a>
    $_breadcrumb_link_prefix    = '<span>';
    $_breadcrumb_link_suffix    = '</span>';

    // Additional link attributes like class, rel, style
    $_breadcrumb_link_attribute = 'class="breadcrumb-link"';


    ////// Do not edit below ////////

    $_breadcrumb = [];

    if (count($LEVEL_ID) > $_breadcrumb_start_level) {

        foreach ($LEVEL_ID as $level => $item) {

            if ($level < $_breadcrumb_start_level) {
                continue;
            }

            if (empty($content['struct'][$item]['acat_hidden'])) {
                $_breadcrumb[] = getStructureLevelLink(
                    ($content['cat_id'] == $item && $content['list_mode']) ? $content['struct'][$item]['acat_name'] : $content['struct'][$item],
                    $_breadcrumb_link_attribute,
                    $_breadcrumb_link_prefix,
                    $_breadcrumb_link_suffix
                );
            }

        }

    }

    // Article
    if (!empty($aktion[1])) {
        $_breadcrumb[] = '<span class="breadcrumb-current">' . html_specialchars($content['article_title']) . '</span>';
    }

    $_breadcrumb = implode($_breadcrumb_spacer, array_filter($_breadcrumb));

    $content['all'] = str_replace('{BREADCRUMB_ARTICLE}', $_breadcrumb, $content['all']);

}

