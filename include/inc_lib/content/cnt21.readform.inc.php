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



// Content Type Page/ext. Content
$content["page_file"]['source'] = intval($_POST['cpage_source']);

if(!$content["page_file"]['source']) {

	$content["page_file"]['pfile'] = isset($_POST['cpage_file']) ? clean_slweg($_POST['cpage_file']) : '';
	if(!file_exists($content["page_file"]['pfile'])) {
		$content["page_file"]['pfile'] = '';
	}

} else {

	$content["page_file"]['pfile'] = clean_slweg($_POST['cpage_custom']);

	if(!file_exists($content["page_file"]['pfile'])) {

		list($content["page_file"]['checkurl']) = explode('?', $content["page_file"]['pfile']);

		if(!file_get_contents($content["page_file"]['checkurl'])) {
			$content["page_file"]['pfile'] = '';
		}
		unset($content["page_file"]['checkurl']);
	}

}
