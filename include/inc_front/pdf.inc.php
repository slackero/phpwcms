<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

unset($_GET, $_POST);

//$_print_settings['PDF_font_size']		= empty($_print_settings['PDF_font_size']) ? 5 : intval($_print_settings['PDF_font_size']);
$_print_settings['PDF_file_prefix']		= empty($_print_settings['PDF_file_prefix']) ? '' : trim($_print_settings['PDF_file_prefix']);
//$_print_settings['PDF_disable_tags']	= empty($_print_settings['PDF_disable_tags']) ? 'div,input,form,object,embed,script' : trim($_print_settings['PDF_disable_tags']);

$_print_settings['PDF_filename']		= $_print_settings['PDF_file_prefix'].substr($content["pagetitle"], 0, 150);
$_print_settings['PDF_filename']		= str_replace(array('?', '%', '*', '"', "'", '<', '>', '|', '/', '\\', ':', '&'), '-', $_print_settings['PDF_filename']);
$_print_settings['PDF_filename']		= str_replace('--', '-', $_print_settings['PDF_filename']) . '.pdf';

unset($content);

$_PDF_page = ob_get_clean();

$include_urlparts = parse_url(CMSGO_URL);
$_PDF_page = preg_replace_callback('/(href|src|action)=[\'|"]{0,1}(.*?)[\'|"]{0,1}( .*?){0,1}>/i', 'make_absoluteURL', $_PDF_page);

$include_urlparts['falsepart']	= $include_urlparts['host'].'/'.trim($cmsgo["root"].$cmsgo["root"], ' /').'/';
$include_urlparts['corrected']	= $include_urlparts['host'].'/'.trim($cmsgo["root"], ' /').'/';

$_PDF_page = str_replace($include_urlparts['falsepart'], $include_urlparts['corrected'], $_PDF_page);
$_PDF_temp = md5($_print_settings['PDF_filename'].microtime()).'.html';

// Output -> use file save and redirect
if(write_textfile( CMSGO_CONTENT.'tmp/'.$_PDF_temp, $_PDF_page )) {

		$cmd = $cmsgo['wkhtmltopdf_path'] . ' ' . escapeshellarg(CMSGO_URL.CONTENT_PATH.'tmp/'.$_PDF_temp) . ' ' . escapeshellarg(CMSGO_CONTENT.'tmp/'.$_print_settings['PDF_filename']);

		@exec($cmd, $output, $retval);

		if(is_file(CMSGO_CONTENT.'tmp/'.$_print_settings['PDF_filename'])) {

			// Set the file to 777
			@chmod(CMSGO_CONTENT.'tmp/'.$_print_settings['PDF_filename'], 0666);

			headerRedirect(CMSGO_URL.CONTENT_PATH.'tmp/'.$_print_settings['PDF_filename']);

		}
}

headerRedirect(abs_url());
