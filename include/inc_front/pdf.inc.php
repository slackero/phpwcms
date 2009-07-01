<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!

   **********************************************************************************

   phpwcms2pdf - Print as PDF
   ===========
   marcus@localhorst, 23-Apr-2007

	 
   CREDITS:
   # This use the html2fpdf Library
     http://html2fpdf.sourceforge.net/
     http://html2fpdf.sourceforge.net/classdoc.php
     fpdf Doc (html2fpdf is build on a changed fpdf class!)
     http://www.fpdf.org/en/doc/

   # Based on this thread: http://www.phpwcms.de/forum/viewtopic.php?t=13594#79835
     which is based on the "nice formatted printing page" hack
     http://www.phpwcms.de/forum/viewtopic.php?t=3759
     I just add the template feature and some finetunings for my needs.

*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//@ini_set('display_errors','0');
//error_reporting(0);

unset($_GET);
unset($_POST);

// include html2fpdf.php
define( 'RELATIVE_PATH', PHPWCMS_ROOT.'/include/inc_ext/html2fpdf/' );
define( 'FPDF_FONTPATH', RELATIVE_PATH.'font/' );

require_once(PHPWCMS_ROOT.'/include/inc_ext/html2fpdf/html2fpdf.php' );


$_print_settings['PDF_font_size']		= empty($_print_settings['PDF_font_size']) ? 5 : intval($_print_settings['PDF_font_size']);
$_print_settings['PDF_file_prefix']		= empty($_print_settings['PDF_file_prefix']) ? '' : trim($_print_settings['PDF_file_prefix']);
$_print_settings['PDF_disable_tags']	= empty($_print_settings['PDF_disable_tags']) ? 'div,input,form,object,embed,script' : trim($_print_settings['PDF_disable_tags']);

$_print_settings['PDF_title']			= decode_entities(makeCharsetConversion($content["pagetitle"], PHPWCMS_CHARSET, 'iso-8859-1', 0));
$_print_settings['PDF_keywords']		= is_array($content['all_keywords']) ? decode_entities(makeCharsetConversion(implode(', ', $content['all_keywords']), PHPWCMS_CHARSET, 'iso-8859-1', 0)) : '';
$_print_settings['PDF_filename']		= str_replace($pagelayout['layout_title_spacer'], '-', $_print_settings['PDF_title']);
$_print_settings['PDF_filename']		= trim(preg_replace('/[^0-9a-zA-Z_\.\-,\s]/', '', $_print_settings['PDF_filename']));
$_print_settings['PDF_filename']		= $_print_settings['PDF_file_prefix'].substr($_print_settings['PDF_filename'], 0, 150).'.pdf';
$_print_settings['PDF_filename']		= str_replace('|', '-', $_print_settings['PDF_filename']);
$_print_settings['PDF_filename']		= str_replace('--', '-', $_print_settings['PDF_filename']);

$_PDF = new HTML2FPDF();
$_PDF->AddPage();
$_PDF->DisableTags( $_print_settings['PDF_disable_tags'] );
$_PDF->SetTitle( $_print_settings['PDF_title'] );
$_PDF->SetSubject( '' );
$_PDF->SetKeywords( $_print_settings['PDF_keywords'] );
$_PDF->SetAuthor( '' );

unset($content);

$_PDF_page = ob_get_contents();
ob_end_clean();

$SPECIAL_ENTITIES_TABLES['symbol_decode'] = array(
	'ƒ', 
	'?', 
	'?', 
	'G', 
	'?', 
	'?', 
	'?', 
	'?', 
	'T', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'S', 
	'?', 
	'?', 
	'F', 
	'?', 
	'?', 
	'O', 
	'a', 
	'ß', 
	'?', 
	'd', 
	'e', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'µ', 
	'?', 
	'?', 
	'?', 
	'p', 
	'?', 
	'?', 
	's', 
	't', 
	'?', 
	'f', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'•', 
	'…', 
	'\'', 
	'?', 
	'?', 
	'/', 
	'P', 
	'I', 
	'R', 
	'™', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'Ø', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'-', 
	'*', 
	'v', 
	'?', 
	'8', 
	'?', 
	'?', 
	'?', 
	'n', 
	'?', 
	'?', 
	'?', 
	'~', 
	'?', 
	'˜', 
	'?', 
	'=', 
	'=', 
	'=', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'·', 
	'?', 
	'?', 
	'?', 
	'?', 
	'<', 
	'>', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?'
    );
    
$SPECIAL_ENTITIES_TABLES['specialchars_decode'] = array( 
    '"', 
    '&', 
    '<', 
    '>', 
	'Œ', 
	'œ', 
	'Š', 
	'š', 
	'Ÿ', 
	'ˆ', 
	'˜', 
	' ', 
	' ', 
	'?', 
	'?', 
	'?', 
	'?', 
	'?', 
	'–', 
	'—', 
	'‘', 
	'’', 
	'‚', 
	'“', 
	'”', 
	'„', 
	'†', 
	'‡', 
	'‰', 
	'‹', 
	'›', 
	'€'
    );


$_PDF_page = str_replace($SPECIAL_ENTITIES_TABLES['latin1_encode'], $SPECIAL_ENTITIES_TABLES['latin1_decode'], $_PDF_page);
$_PDF_page = str_replace($SPECIAL_ENTITIES_TABLES['symbol_encode'], $SPECIAL_ENTITIES_TABLES['symbol_decode'], $_PDF_page);
$_PDF_page = str_replace($SPECIAL_ENTITIES_TABLES['specialchars_encode'], $SPECIAL_ENTITIES_TABLES['specialchars_decode'], $_PDF_page);
$_PDF_page = trim(makeCharsetConversion($_PDF_page, PHPWCMS_CHARSET, 'iso-8859-15', 0));


$include_urlparts = parse_url(PHPWCMS_URL);
$_PDF_page = preg_replace_callback('/(href|src|action)=[\'|"]{0,1}(.*?)[\'|"]{0,1}( .*?){0,1}>/i', 'make_absoluteURL', $_PDF_page);

$include_urlparts['falsepart']	= $include_urlparts['host'].'/'.trim($phpwcms["root"].$phpwcms["root"], ' /').'/';
$include_urlparts['corrected']	= $include_urlparts['host'].'/'.trim($phpwcms["root"], ' /').'/';

$_PDF_page = str_replace($include_urlparts['falsepart'], $include_urlparts['corrected'], $_PDF_page);

//$_PDF->ReadMetaTags(	$_PDF_page	);
$_PDF->ReadCSS(			$_PDF_page	);
$_PDF->SetFontSize(		$_print_settings['PDF_font_size'] );
$_PDF->WriteHTML(		$_PDF_page	);

// Output -> use file save and redirect
$_PDF->Output( PHPWCMS_CONTENT.'tmp/'.$_print_settings['PDF_filename'], 'F');
headerRedirect(PHPWCMS_URL . CONTENT_PATH . 'tmp/'.$_print_settings['PDF_filename']);

exit();

?>