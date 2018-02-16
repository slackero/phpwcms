<?php

/**
 * Alternative way of building a breadcrumb
 * It will show article title too and act different when in article list mode
 * This works different from default breadcrumb because it is level based
 *
 * (c) 07/12/2009 Oliver Georgi
 */

if(strpos($content['all'], '{BREADCRUMB_ARTICLE}')) {

    // Set level where to start with breadcrumb - default 0 = Root level
    $_breadcrumb_start_level    = 0;

    // Separate Breadcrumb items with
    $_breadcrumb_spacer         = ' &gt; ';

    // Wrap inner link text by prefix/suffix <a> %PREFIX% Linktext %SUFFIX% </a>
    $_breadcrumb_link_prefix    = '<b>';
    $_breadcrumb_link_suffix    = '</b>';

    // additional link attributes like class, rel, style
    // remember there is no active link - active (last) item has no link
    $_breadcrumb_link_attribute = 'class="breadcrumb-link"';


    ////// Do not edit below ////////

    $_breadcrumb = array();

    if(count($LEVEL_ID) > $_breadcrumb_start_level) {

        foreach($LEVEL_ID as $level => $item) {

            if($level < $_breadcrumb_start_level) {
                continue;
            }

            if($content['struct'][$item]["acat_hidden"] == false) {
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
    if($aktion[1]) {

        $_breadcrumb[] = html_specialchars( $content['article_title'] );

    }

    $_breadcrumb = implode($_breadcrumb_spacer, array_diff( $_breadcrumb , array('', NULL) ) );

    $content['all'] = str_replace('{BREADCRUMB_ARTICLE}', $_breadcrumb, $content['all']);

}
