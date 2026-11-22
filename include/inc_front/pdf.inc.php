<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
$_PDF_html = PHPWCMS_TEMP.$_PDF_temp;
$_PDF_file = PHPWCMS_TEMP.$_print_settings['PDF_filename'];

if (function_exists('phpwcms_storage_ensure')) {
    phpwcms_storage_ensure(PHPWCMS_TEMP, 0700);
}

// Staging and output stay in private storage. Assets in the page are already
// absolute URLs, so wkhtmltopdf can read the private local staging file.
if(write_textfile($_PDF_html, $_PDF_page)) {

		@chmod($_PDF_html, 0600);

		$cmd = $phpwcms['wkhtmltopdf_path'] . ' ' . escapeshellarg($_PDF_html) . ' ' . escapeshellarg($_PDF_file);

		@exec($cmd, $output, $retval);

		if(is_file($_PDF_file)) {

			@chmod($_PDF_file, 0600);
			@unlink($_PDF_html);

			$_PDF_name = basename($_PDF_file);
			$_PDF_ascii = str_replace(array('"', '\\', "\r", "\n"), '_', (string)preg_replace('/[^\x20-\x7E]/', '_', $_PDF_name));

			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="' . ($_PDF_ascii !== '' ? $_PDF_ascii : 'print.pdf') . '"; filename*=UTF-8\'\'' . rawurlencode($_PDF_name));
			header('Content-Length: ' . filesize($_PDF_file));
			header('Cache-Control: private, no-store');
			readfile($_PDF_file);
			@unlink($_PDF_file);
			exit();

		}
}

@unlink($_PDF_html);
headerRedirect(abs_url());
