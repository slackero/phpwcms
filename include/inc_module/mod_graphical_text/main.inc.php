<?php
// MOD Title: phpwcms Graphical Text MOD 
// MOD Author: Jerome < spam@jerome-gamez.de > (Jerome Gamez) http://jerome-gamez.de/ 
// MOD Description: Adds the possibilty to create dynamic graphical text 
//                  with replacement tags 
// 
// MOD Version: 2.0

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_lib/functions.general.inc.php');
include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_front/gt.func.inc.php');
// Jetzt kommt das company mod-menü. Soll auf jeder Seite angezeigt werden.
include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.menu.tmpl.php');

$gt_mod_s = empty($_GET["s"]) ? false : $_GET["s"];
$gt_mod_t = empty($_GET["t"]) ? false : $_GET["t"];

switch($gt_mod_s)
{
	
	case "fonts":	// Schriftarten
			switch($gt_mod_t)
			{
				case "add":		// Schriftart, die im Ordner ist, in die Datenbank übernehmen
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.font.update.tmpl.php');
							break;
							
				case "update":	// Schriftart-Daten bearbeiten
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.font.update.tmpl.php');
							break;
							
				default:	// Schriftartenliste
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.fonts.tmpl.php');
							break;
			}
			break;
		
	case "colors":	// Farben
			switch($gt_mod_t)
			{
				case "add":	// Farbe hinzufügen
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.color.update.tmpl.php');
							break;
				
				case "update":	// Farbdaten bearbeiten
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.color.update.tmpl.php');
							break;

				case "delete":	// Farbdaten löschen
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_lib/gt.color.delete.inc.php');
							break;

				default:	// Farbliste
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.colors.tmpl.php');
							break;
			}
			break;
	
	case "styles":	// Styles
			switch($gt_mod_t)
			{
				case "add":	// Stil hinzufügen
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.style.update.tmpl.php');
							break;
				
				case "update":	// Stil bearbeiten
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.style.update.tmpl.php');
							break;

				case "delete":	// Stil löschen
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_lib/gt.style.delete.inc.php');
							break;

				default:	// Styles-Liste
							include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.styles.tmpl.php');
							break;
			}
			break;
			
	default: // Graphischer Text MOD-Startseite 
			 include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.index.tmpl.php');
			 break;
}

include_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_tmpl/gt.footer.tmpl.php');
?>