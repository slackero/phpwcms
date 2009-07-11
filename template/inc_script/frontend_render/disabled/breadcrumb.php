<?php

/**
 * Alternative way of building a breadcrumb
 * It will show article title too and act different when in article list mode
 */

if(strpos($content['all'], '{BREADCRUMB_ARTICLE}')) {
	
	$_breadcrumb_spacer			= ' &gt; ';
	$_breadcrumb_link_prefix	= '<b>';
	$_breadcrumb_link_suffix	= '</b>';
	$_breadcrumb_link_attribute	= 'class="breadcrumb-link"';

	$_breadcrumb = array();

	foreach($LEVEL_ID as $item) {
	
		if($content['struct'][$item]["acat_hidden"] == false) {
			$_breadcrumb[] = getStructureLevelLink( 
					($content['cat_id'] == $item && $content['list_mode']) ? $content['struct'][$item]['acat_name'] : $content['struct'][$item], 
					$_breadcrumb_link_attribute, 
					$_breadcrumb_link_prefix, 
					$_breadcrumb_link_suffix
				);
		}
	
	}
	
	// Article
	if($aktion[1]) {
	
		$_breadcrumb[] = html_specialchars( $content['article_title'] );
	
	}
	
	$_breadcrumb = implode($_breadcrumb_spacer, array_diff( $_breadcrumb , array('', NULL) ) );
	
	$content['all'] = str_replace('{BREADCRUMB_ARTICLE}', $_breadcrumb, $content['all']);

}

?>