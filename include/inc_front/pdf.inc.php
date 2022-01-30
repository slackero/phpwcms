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

unset($_GET, $_POST);

//$_print_settings['PDF_font_size']		= empty($_print_settings['PDF_font_size']) ? 5 : intval($_print_settings['PDF_font_size']);
$_print_settings['PDF_file_prefix']		= empty($_print_settings['PDF_file_prefix']) ? '' : trim($_print_settings['PDF_file_prefix']);
//$_print_settings['PDF_disable_tags']	= empty($_print_settings['PDF_disable_tags']) ? 'div,input,form,object,embed,script' : trim($_print_settings['PDF_disable_tags']);

$_print_settings['PDF_filename']		= $_print_settings['PDF_file_prefix'].substr($content["pagetitle"], 0, 150);
$_print_settings['PDF_filename']		= str_replace(array('?', '%', '*', '"', "'", '<', '>', '|', '/', '\\', ':', '&'), '-', $_print_settings['PDF_filename']);
$_print_settings['PDF_filename']		= str_replace('--', '-', $_print_settings['PDF_filename']) . '.pdf';

unset($content);

$_PDF_page = ob_get_clean();

$include_urlparts = parse_url(PHPWCMS_URL);
$_PDF_page = preg_replace_callback('/(href|src|action)=[\'|"]{0,1}(.*?)[\'|"]{0,1}( .*?){0,1}>/i', 'make_absoluteURL', $_PDF_page);

$include_urlparts['falsepart']	= $include_urlparts['host'].'/'.trim($phpwcms["root"].$phpwcms["root"], ' /').'/';
$include_urlparts['corrected']	= $include_urlparts['host'].'/'.trim($phpwcms["root"], ' /').'/';

$_PDF_page = str_replace($include_urlparts['falsepart'], $include_urlparts['corrected'], $_PDF_page);
$_PDF_temp = md5($_print_settings['PDF_filename'].microtime()).'.html';

// Output -> use file save and redirect
if(write_textfile( PHPWCMS_CONTENT.'tmp/'.$_PDF_temp, $_PDF_page )) {

		$cmd = $phpwcms['wkhtmltopdf_path'] . ' ' . escapeshellarg(PHPWCMS_URL.CONTENT_PATH.'tmp/'.$_PDF_temp) . ' ' . escapeshellarg(PHPWCMS_CONTENT.'tmp/'.$_print_settings['PDF_filename']);

		@exec($cmd, $output, $retval);

		if(is_file(PHPWCMS_CONTENT.'tmp/'.$_print_settings['PDF_filename'])) {

			// Set the file to 777
			@chmod(PHPWCMS_CONTENT.'tmp/'.$_print_settings['PDF_filename'], 0666);

			headerRedirect(PHPWCMS_URL.CONTENT_PATH.'tmp/'.$_print_settings['PDF_filename']);

		}
}

headerRedirect(abs_url());
