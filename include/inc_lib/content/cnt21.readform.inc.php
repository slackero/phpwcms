<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
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
$content['page_file']['source'] = intval($_POST['cpage_source']);

/**
 * Whether a manually entered page address may be fetched.
 *
 * Content part 21 imports an external page. Only HTTP(S) is fetched; local
 * stream wrappers such as file:// or php:// would turn the renderer into a
 * reader for arbitrary files on the server.
 *
 * @param string $url
 *
 * @return bool
 */
function cnt21_external_url_allowed($url) {
    $url    = (string) $url;
    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

    return in_array($scheme, array('http', 'https'), true) && filter_var($url, FILTER_VALIDATE_URL) !== false;
}

if (!$content['page_file']['source']) {

	$content['page_file']['pfile'] = isset($_POST['cpage_file']) ? clean_slweg($_POST['cpage_file']) : '';
	if ($content['page_file']['pfile'] !== '') {
		// local page files are only accepted inside the content pages directory
		$pagesRoot = realpath(PHPWCMS_ROOT . '/' . CONTENT_PATH . 'pages');
		$realFile  = realpath(PHPWCMS_ROOT . '/' . $content['page_file']['pfile']);
		if ($realFile === false || !path_is_within($realFile, $pagesRoot)) {
			$content['page_file']['pfile'] = '';
		}
	}

} else {

	$content['page_file']['pfile'] = clean_slweg($_POST['cpage_custom']);

	// The renderer fetches the page when the article is displayed. Probing it
	// here as well would send a second request to an author supplied address
	// from the backend.
	if (!cnt21_external_url_allowed($content['page_file']['pfile'])) {
		$content['page_file']['pfile'] = '';
	}

}
