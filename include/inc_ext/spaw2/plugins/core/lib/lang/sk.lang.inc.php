<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Slovak language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Slovak translation: Pavel Koutny
//                     pavel.koutny@inetix.sk
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Vystrihnú'
  ),
  'copy' => array(
    'title' => 'Kopírova'
  ),
  'paste' => array(
    'title' => 'Vloi'
  ),
  'undo' => array(
    'title' => 'Vráti'
  ),
  'redo' => array(
    'title' => 'Vykona'
  ),
  'hyperlink' => array(
    'title' => 'Hyperlink'
  ),
  'image_insert' => array(
    'title' => 'Vloi obrázok',
    'select' => 'Vybra',
    'cancel' => 'Zrui',
    'library' => 'Kninica',
    'preview' => 'Náhžad',
    'images' => 'Obrázky',
    'upload' => 'Nahra obrázek',
    'upload_button' => 'Nahra',
    'error' => 'Chyba',
    'error_no_image' => 'Vyberte prosím obrázok',
    'error_uploading' => 'V priebehu nahrávania dolo k chybe. Zopakujte to.',
    'error_wrong_type' => 'chybný formát obrázku',
    'error_no_dir' => 'Kninica neexistuje',
  ),
  'image_prop' => array(
    'title' => 'Vlastnosti obrázku',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
    'source' => 'Zdroj',
    'alt' => 'Alternatívny text',
    'align' => 'Zarovnanie',
    'justifyleft' => 'vževo',
    'justifyright' => 'vpravo',
    'top' => 'Zhora',
    'middle' => 'Na stred',
    'bottom' => 'Zdole',
    'absmiddle' => 'absolútny stred',
    'texttop' => 'text-hore',
    'baseline' => 'Základná linka',
    'width' => 'írka',
    'height' => 'Výka',
    'border' => 'Okraje',
    'hspace' => 'Hor. medzera',
    'vspace' => 'Vert. medzera',
    'error' => 'Chyba',
    'error_width_nan' => 'írka nie je číslo',
    'error_height_nan' => 'Výka nie je číslo',
    'error_border_nan' => 'Okraj nie je číslo',
    'error_hspace_nan' => 'Horizontálna medzera nie je číslo',
    'error_vspace_nan' => 'Vertikálna medzera nie je číslo',
  ),
  'inserthorizontalrule' => array(
    'title' => 'Horizontálny oddelovač'
  ),
  'table_create' => array(
    'title' => 'Vytvor tabužku'
  ),
  'table_prop' => array(
    'title' => 'Vlastnosti tabužky',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
    'rows' => 'Riadkov',
    'columns' => 'Stĺpcov',
    'width' => 'írka',
    'height' => 'Výka',
    'border' => 'Okraje',
    'pixels' => 'pixelov',
    'cellpadding' => 'Odsadenie v bunke',
    'cellspacing' => 'Vzdialenos buniek',
    'bg_color' => 'Farba pozadia',
    'error' => 'Chyba',
    'error_rows_nan' => 'Riadky nie sú číslo',
    'error_columns_nan' => 'Stĺpce nie sú číslo',
    'error_width_nan' => 'írka nie je číslo',
    'error_height_nan' => 'Výka nie je číslo',
    'error_border_nan' => 'Okraj nie je číslo',
    'error_cellpadding_nan' => 'Odsadenie v bunke nie je číslo',
    'error_cellspacing_nan' => 'Vzdialenos buniek nie je číslo',
  ),
  'table_cell_prop' => array(
    'title' => 'Vlastnosti bunky',
    'horizontal_align' => 'Horizontálne zarovnanie',
    'vertical_align' => 'Vertikálne zarovnanie',
    'width' => 'írka',
    'height' => 'Výka',
    'css_class' => 'CSS trieda',
    'no_wrap' => 'nezalamova',
    'bg_color' => 'Farba pozadia',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
    'justifyleft' => 'Vževo',
    'justifycenter' => 'V strede',
    'justifyright' => 'Vpravo',
    'top' => 'Zhora',
    'middle' => 'Na stred',
    'bottom' => 'Zdola',
    'baseline' => 'Základná linka',
    'error' => 'Chyba',
    'error_width_nan' => 'írka nie je číslo',
    'error_height_nan' => 'Výka nie je číslo',
    
  ),
  'table_row_insert' => array(
    'title' => 'Vloi riadok'
  ),
  'table_column_insert' => array(
    'title' => 'Vloi stĺpec'
  ),
  'table_row_delete' => array(
    'title' => 'Vyma riadok'
  ),
  'table_column_delete' => array(
    'title' => 'Vyma stĺpec'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Zlúi vpravo'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Zlúči nadol'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Rozdeli bunku horizontálne'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Rozdeli bunku vertikálne'
  ),
  'style' => array(
    'title' => 'týl'
  ),
  'fontname' => array(
    'title' => 'Font'
  ),
  'fontsize' => array(
    'title' => 'Vežkos'
  ),
  'formatBlock' => array(
    'title' => 'Odstavec'
  ),
  'bold' => array(
    'title' => 'Tučné'
  ),
  'italic' => array(
    'title' => 'ikmé'
  ),
  'underline' => array(
    'title' => 'Podčiarknuté'
  ),
  'insertorderedlist' => array(
    'title' => 'Číslovanie'
  ),
  'insertunorderedlist' => array(
    'title' => 'Odráky'
  ),
  'indent' => array(
    'title' => 'Odsadenie'
  ),
  'outdent' => array(
    'title' => 'Zrui odsadenie'
  ),
  'justifyleft' => array(
    'title' => 'Vžavo'
  ),
  'justifycenter' => array(
    'title' => 'V strede'
  ),
  'justifyright' => array(
    'title' => 'Vpravo'
  ),
  'fore_color' => array(
    'title' => 'Barva popredia'
  ),
  'bg_color' => array(
    'title' => 'Barva pozadia'
  ),
  'design' => array(
    'title' => 'Prepnú do WYSIWYG módu'
  ),
  'html' => array(
    'title' => 'Přepnú do HTML módu'
  ),
  'colorpicker' => array(
    'title' => 'Paleta farieb',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
  ),
  // <<<<<<<<< NEW >>>>>>>>>
  'cleanup' => array(
    'title' => 'HTML kontrola (odstraní týly)',
    'confirm' => 'Prevedením akcie odstránite vetky týly, fonty a zbytočné tagy z aktuálneho obsahu. Jedno, alebo vetky formátovania budú odstránené.',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
  ),
  'toggle_borders' => array(
    'title' => 'Upravi okraje',
  ),
  'hyperlink' => array(
    'title' => 'Hyperlink',
    'url' => 'URL',
    'name' => 'Meno',
    'target' => 'Ciež',
    'title_attr' => 'Názov',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
  ),
  'table_row_prop' => array(
    'title' => 'Vlastnosti riadku',
    'horizontal_align' => 'Horizontálne zarovnanie',
    'vertical_align' => 'Vertikálne zarovnanie',
    'css_class' => 'CSS trieda',
    'no_wrap' => 'Nezalamova',
    'bg_color' => 'Farba pozadia',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
    'justifyleft' => 'Vževo',
    'justifycenter' => 'V strede',
    'justifyright' => 'Vpravo',
    'top' => 'Zhora',
    'middle' => 'Na stred',
    'bottom' => 'Zdola',
    'baseline' => 'Základná linka',
  ),
  'symbols' => array(
    'title' => 'peciálne znaky',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
  ),
  'symbols' => array(
    'title' => 'peciálne znaky',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
  ),
  'templates' => array(
    'title' => 'ablóny',
  ),
  'page_prop' => array(
    'title' => 'Vlastnosti stránky',
    'title_tag' => 'Názov',
    'charset' => 'Kódovanie',
    'background' => 'Obrázok pozadia',
    'bgcolor' => 'Farba pozadia',
    'text' => 'Farba textu',
    'link' => 'Farba odkazu',
    'vlink' => 'Farba navtíveného odkazu',
    'alink' => 'Farba aktívneho odkazu',
    'leftmargin' => 'źavý okraj',
    'topmargin' => 'Horný okraj',
    'css_class' => 'CSS trieda',
    'ok' => '   OK   ',
    'cancel' => 'Zrui',
  ),
  'preview' => array(
    'title' => 'Náhžad',
  ),
  'image_popup' => array(
    'title' => 'Prekrývanie obrázkov',
  ),
  'zoom' => array(
    'title' => 'Zväčenie',
  ),
);
?>

