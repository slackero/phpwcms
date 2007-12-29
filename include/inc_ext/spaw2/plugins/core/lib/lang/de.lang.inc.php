<?php 
// ================================================
// SPAW v.2.0
// ================================================
// English language file
// ================================================
// Author: Alan Mendelevich, UAB Solmetra
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.2.0
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Ausschneiden'
  ),
  'copy' => array(
    'title' => 'Kopieren'
  ),
  'paste' => array(
    'title' => 'Einfügen'
  ),
  'undo' => array(
    'title' => 'Rückgängig'
  ),
  'redo' => array(
    'title' => 'Wiederherstellen'
  ),
  'image' => array(
    'title' => 'Schnelles Bild-Einfügen'
  ),
  'image_prop' => array(
    'title' => 'Bild',
    'ok' => '   OK   ',
    'cancel' => 'Abbrechen',
    'source' => 'Quelle',
    'alt' => 'Alternativer Text',
    'align' => 'Ausrichten',
    'left' => 'links',
    'right' => 'rechts',
    'top' => 'oben',
    'middle' => 'mittig',
    'bottom' => 'unten',
    'absmiddle' => 'absolut mittig',
    'texttop' => 'Text oben',
    'baseline' => 'Grundlinie',
    'width' => 'Breite',
    'height' => 'Höhe',
    'border' => 'Rahmen',
    'hspace' => 'Hor. Abstand',
    'vspace' => 'Vert. Abstand',
    'dimensions' => 'Größe', // <= new in 2.0.1
    'reset_dimensions' => 'Größe zurücksetzen', // <= new in 2.0.1
    'title_attr' => 'Titel', // <= new in 2.0.1
    'constrain_proportions' => 'Proportionen beibehalten', // <= new in 2.0.1
    'Fehler' => 'Fehler',
    'Fehler_width_nan' => 'Breite ist keine Zahl',
    'Fehler_height_nan' => 'Höhe ist keine Zahl',
    'Fehler_border_nan' => 'Rahmen ist keine Zahl',
    'Fehler_hspace_nan' => 'Horizontaler Abstand ist keine Zahl',
    'Fehler_vspace_nan' => 'Vertikaler Abstand ist keine Zahl',
  ),
  'flash_prop' => array(                // <= new in 2.0
    'title' => 'Flash',
    'ok' => '   OK   ',
    'cancel' => 'Abbrechen',
    'source' => 'Quelle',
    'width' => 'Breite',
    'height' => 'Höhe',
    'Fehler' => 'Fehler',
    'Fehler_width_nan' => 'Breite ist keine Zahl',
    'Fehler_height_nan' => 'Höhe ist keine Zahl',
  ),
  'inserthorizontalrule' => array( // <== v.2.0 changed from hr
    'title' => 'Horizontale Linie'
  ),
  'table_create' => array(
    'title' => 'Tabelle erstellen'
  ),
  'table_prop' => array(
    'title' => 'Tabelleneinstellungen',
    'ok' => '   OK   ',
    'cancel' => 'Abbrech',
    'rows' => 'Zeilen',
    'columns' => 'Spalten',
    'css_class' => 'CSS Klasse',
    'width' => 'Breite',
    'height' => 'Höhe',
    'border' => 'Rahmen',
    'pixels' => 'Pixel',
    'cellpadding' => 'Zellenauffüllung',
    'cellspacing' => 'Zellenabstand',
    'bg_color' => 'Hintergrundfarbe',
    'background' => 'Hintergrundbild',
    'Fehler' => 'Fehler',
    'Fehler_rows_nan' => 'Zeilen ist keine Zahl',
    'Fehler_columns_nan' => 'Spalten ist keine Zahl',
    'Fehler_width_nan' => 'Breite ist keine Zahl',
    'Fehler_height_nan' => 'Höhe ist keine Zahl',
    'Fehler_border_nan' => 'Rahmen ist keine Zahl',
    'Fehler_cellpadding_nan' => 'Zellenauffüllung ist keine Zahl',
    'Fehler_cellspacing_nan' => 'Zellenabstand ist keine Zahl',
  ),
  'table_cell_prop' => array(
    'title' => 'Tabellenzellen Einstellungen',
    'horizontal_align' => 'Horizontale Ausrichtung',
    'vertical_align' => 'Vertikale Ausrichtung',
    'width' => 'Breite',
    'height' => 'Höhe',
    'css_class' => 'CSS Klasse',
    'no_wrap' => 'Kein Umbruch',
    'bg_color' => 'Hintergrundfarbe',
    'background' => 'Hintergrundbild',
    'ok' => '   OK   ',
    'cancel' => 'Abbrechen',
    'left' => 'Links',
    'center' => 'Zentriert',
    'right' => 'Rechts',
    'top' => 'Oben',
    'middle' => 'Mittig',
    'bottom' => 'Unten',
    'baseline' => 'Grundline',
    'error' => 'Fehler',
    'error_width_nan' => 'Breite ist keine Zahl',
    'eror_height_nan' => 'Höhe ist keine Zahl',
  ),
  'table_row_insert' => array(
    'title' => 'Zeile einfügen'
  ),
  'table_column_insert' => array(
    'title' => 'Spalte einfügen'
  ),
  'table_row_delete' => array(
    'title' => 'Zeile löschen'
  ),
  'table_column_delete' => array(
    'title' => 'Spalte löschenn'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Nach rechts verbinden'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Nach unten verbinden'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Zelle horizontal trennen'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Zelle vertikal trennen'
  ),
  'style' => array(
    'title' => 'Stil'
  ),
  'fontname' => array( // <== v.2.0 changed from font
    'title' => 'Zeichensatz'
  ),
  'fontsize' => array(
    'title' => 'Größe'
  ),
  'formatBlock' => array( // <= v.2.0: changed from paragraph
    'title' => 'Absatz'
  ),
  'bold' => array(
    'title' => 'Fett'
  ),
  'italic' => array(
    'title' => 'Kursiv'
  ),
  'underline' => array(
    'title' => 'Unterstrichen'
  ),
  'strikethrough' => array(
    'title' => 'Durchgestrichen'
  ),
  'insertorderedlist' => array( // <== v.2.0 changed from ordered_list
    'title' => 'Sortierte Liste'
  ),
  'insertunorderedlist' => array( // <== v.2.0 changed from bulleted list
    'title' => 'Liste'
  ),
  'indent' => array(
    'title' => 'Einzug nach rechts'
  ),
  'outdent' => array( // <== v.2.0 changed from unindent
    'title' => 'Einzug rückgängig'
  ),
  'justifyleft' => array( // <== v.2.0 changed from left
    'title' => 'Links'
  ),
  'justifycenter' => array( // <== v.2.0 changed from center
    'title' => 'Zentriert'
  ),
  'justifyright' => array( // <== v.2.0 changed from right
    'title' => 'Rechts'
  ),
  'justifyfull' => array( // <== v.2.0 changed from justify
    'title' => 'Blocksatz'
  ),
  'fore_color' => array(
    'title' => 'Vordergrundfarbe'
  ),
  'bg_color' => array(
    'title' => 'Hintergrundfarbe'
  ),
  'design' => array( // <== v.2.0 changed from design_tab
    'title' => 'In den WYSIWYG-Modus wechseln (Design)'
  ),
  'html' => array( // <== v.2.0 changed from html_tab
    'title' => 'In den HTML-Modus wechseln (Code)'
  ),
  'colorpicker' => array(
    'title' => 'Farbpalette',
    'ok' => '   OK   ',
    'cancel' => 'Abbruch',
  ),
  'cleanup' => array(
    'title' => 'HTML säubern (Stile entfernen)',
    'confirm' => 'Beim Ausführen dieser Aktion werden alle Stile, Schriftarten und überfüssigen Tags aus dem aktuellen Inhalt entfernt. Einige oder alle Formatierung können dabei verloren gehen.',
    'ok' => '   OK   ',
    'cancel' => 'Abbruch',
  ),
  'toggle_borders' => array(
    'title' => 'Rahmen umschalten',
  ),
  'hyperlink' => array(
    'title' => 'Hypertext-Link',
    'url' => 'URL',
    'name' => 'Name',
    'target' => 'Ziel',
    'title_attr' => 'Titel',
  	'a_type' => 'Typ',
  	'type_link' => 'Link',
  	'type_anchor' => 'Anker',
  	'type_link2anchor' => 'Ankerlink',
  	'anchors' => 'Anker',
    'ok' => '   OK   ',
    'cancel' => 'Abbruch',
  ),
  'hyperlink_targets' => array(
  	'_self' => 'gleicher Frame (_self)',
  	'_blank' => 'neues Fenster (_blank)',
  	'_top' => 'Hauptframe (_top)',
  	'_parent' => 'übergeordneter Frame (_parent)'
  ),
  'unlink' => array( // <=== new v.2.0
    'title' => 'Hypertext-Link entfernen'
  ),
  'table_row_prop' => array(
    'title' => 'Zeilendefinitionen',
    'horizontal_align' => 'Horizontale Ausrichtung',
    'vertical_align' => 'Vertikale Ausrichtung',
    'css_class' => 'CSS Klasse',
    'no_wrap' => 'Kein Umbruch',
    'bg_color' => 'Hintergrundfarbe',
    'ok' => '   OK   ',
    'cancel' => 'Abbruch',
    'left' => 'Links',
    'center' => 'Zentriert',
    'right' => 'Rechts',
    'top' => 'Oben',
    'middle' => 'Mittig',
    'bottom' => 'Unten',
    'baseline' => 'Grundlinie',
  ),
  'symbols' => array(
    'title' => 'Sonderzeichen',
    'ok' => '   OK   ',
    'cancel' => 'Abbruch',
  ),
  'templates' => array(
    'title' => 'Vorlagen',
  ),
  'page_prop' => array(
    'title' => 'Seitendefinitionen',
    'title_tag' => 'Titel',
    'charset' => 'Zeichensatz',
    'background' => 'Hintergrundbild',
    'bgcolor' => 'Hintergrundfarbe',
    'text' => 'Textfarbe',
    'link' => 'Linkfarbe',
    'vlink' => 'Farbe für besuchte Links',
    'alink' => 'Farbe für aktive Links',
    'leftmargin' => 'Linker Rand',
    'topmargin' => 'Rechter Rand',
    'css_class' => 'CSS Klasse',
    'ok' => '   OK   ',
    'cancel' => 'Abbruch',
  ),
  'preview' => array(
    'title' => 'Vorschau',
  ),
  'image_popup' => array(
    'title' => 'Bildpopup',
  ),
  'zoom' => array(
    'title' => 'Vergrößern',
  ),
  'subscript' => array(
    'title' => 'Tiefgestellt',
  ),
  'superscript' => array(
    'title' => 'Hochgestellt',
  ),
);
?>