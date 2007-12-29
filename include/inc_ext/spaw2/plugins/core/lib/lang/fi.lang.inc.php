<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Finnish language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Finnish translation: Teemu Joensuu teemu.joensuu@saunalahti.fi
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
    'title' => 'Leikkaa'
  ),
  'copy' => array(
    'title' => 'Kopioi'
  ),
  'paste' => array(
    'title' => 'Liitä'
  ),
  'undo' => array(
    'title' => 'Kumoa'
  ),
  'redo' => array(
    'title' => 'Tee uudelleen'
  ),
  'hyperlink' => array(
    'title' => 'Linkki'
  ),
  'image_insert' => array(
    'title' => 'Lisää kuva',
    'select' => 'Valitse',
    'cancel' => 'Peruuta',
    'library' => 'Kirjasto',
    'preview' => 'Esikatselu',
    'images' => 'Kuvat',
    'upload' => 'Lähetä kuva palvelimelle',
    'upload_button' => 'Lähetä',
    'error' => 'Virhe',
    'error_no_image' => 'Et valinnut kuvaa listalta.',
    'error_uploading' => 'Kuvan palvelimelle lähetyksessä esiintyi virhe. Yritä myöhemmin uudelleen.',
    'error_wrong_type' => 'Lähettämäsi tiedosto ei ollut tuettua tiedostomuotoa',
    'error_no_dir' => 'Kirjastoa ei ole fyysisesti olemassa.',
  ),
  'image_prop' => array(
    'title' => 'Kuvan ominaisuudet',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
    'source' => 'Kuva',
    'alt' => 'Kuvaus',
    'align' => 'Suhde tekstiin',
    'justifyleft' => 'kuva vasemmalla, teksti kiertää',
    'justifyright' => 'kuva oikealla, teksti kiertää',
    'top' => 'teksti asettuu kuvan yläreunaan',
    'middle' => 'teksti asettuu kuvan keskikork.',
    'bottom' => 'teksti asettuu kuvan alareunaan',
    'absmiddle' => 'absmiddle',
    'texttop' => 'texttop',
    'baseline' => 'baseline',
    'width' => 'Leveys',
    'height' => 'Korkeus',
    'border' => 'Reunus',
    'hspace' => 'Vaakas. tyhjä tila',
    'vspace' => 'Pystys. tyhjä tila',
    'error' => 'Virhe',
    'error_width_nan' => 'Leveyden arvo ei ole numero',
    'error_height_nan' => 'Korkeuden arvo ei ole numero',
    'error_border_nan' => 'Reunuksen arvo ei ole numero',
    'error_hspace_nan' => 'Vaakasuoran tyhjän tilan arvo ei ole numero',
    'error_vspace_nan' => 'Pystysuoran tyhjän tilan arvo ei ole numero',
  ),
  'inserthorizontalrule' => array(
    'title' => 'Vaakaviiva'
  ),
  'table_create' => array(
    'title' => 'Luo taulukko'
  ),
  'table_prop' => array(
    'title' => 'Taulukon ominaisuudet',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
    'rows' => 'Rivejä',
    'columns' => 'Sarakkeita',
    'width' => 'Leveys',
    'height' => 'Korkeus',
    'border' => 'Reunaviiva',
    'pixels' => 'kuvapistettä',
    'cellpadding' => 'Tekstin etäisyys solun reunasta',
    'cellspacing' => 'Solujen välinen tyhjä tila',
    'bg_color' => 'Taustaväri',
    'error' => 'Virhe',
    'error_rows_nan' => 'Rivimäärän arvo ei ole numero',
    'error_columns_nan' => 'Sarakemäärän arvo ei ole numero',
    'error_width_nan' => 'Leveyden arvo ei ole numero',
    'error_height_nan' => 'Korkeuden arvo ei ole numero',
    'error_border_nan' => 'Reunuksen arvo ei ole numero',
    'error_cellpadding_nan' => 'Tekstin etäisyys solun reunasta -kentän arvo ei ole numero',
    'error_cellspacing_nan' => 'Solujen välinen tyhjä tila -arvo ei ole numero',
  ),
  'table_cell_prop' => array(
    'title' => 'Taulukon solun ominaisuudet',
    'horizontal_align' => 'Tasaus vaakasuunnassa',
    'vertical_align' => 'Tasaus pystysuunnassa',
    'width' => 'Leveys',
    'height' => 'Korkeus',
    'css_class' => 'CSS luokka',
    'no_wrap' => 'No wrap',
    'bg_color' => 'Taustaväri',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
    'justifyleft' => 'Vasen',
    'justifycenter' => 'Keskitä',
    'justifyright' => 'Oikea',
    'top' => 'Ylös',
    'middle' => 'Keskelle',
    'bottom' => 'Alas',
    'baseline' => 'Baseline',
    'error' => 'Virhe',
    'error_width_nan' => 'Leveyden arvo ei ole numero',
    'error_height_nan' => 'Korkeuden arvo ei ole numero',
    
  ),
  'table_row_insert' => array(
    'title' => 'Lisää rivi taulukkoon'
  ),
  'table_column_insert' => array(
    'title' => 'Lisää sarake taulukkoon'
  ),
  'table_row_delete' => array(
    'title' => 'Poista rivi taulukosta'
  ),
  'table_column_delete' => array(
    'title' => 'Poista sarake taulukosta'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Yhdistä oikealla puolella olevaan soluun'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Yhdistä alapuolella olevaan soluun'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Jaa solu vaakasuunnassa'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Jaa solu pystysuunnassa'
  ),
  'style' => array(
    'title' => 'Tyyli'
  ),
  'fontname' => array(
    'title' => 'Fontti'
  ),
  'fontsize' => array(
    'title' => 'Koko'
  ),
  'formatBlock' => array(
    'title' => 'Kappale'
  ),
  'bold' => array(
    'title' => 'Lihavoi'
  ),
  'italic' => array(
    'title' => 'Kursivoi'
  ),
  'underline' => array(
    'title' => 'Alleviivaa'
  ),
  'insertorderedlist' => array(
    'title' => 'Numeroitu luettelo'
  ),
  'insertunorderedlist' => array(
    'title' => 'Luettelomerkit'
  ),
  'indent' => array(
    'title' => 'Sisennä'
  ),
  'outdent' => array(
    'title' => 'Poista sisennystä'
  ),
  'justifyleft' => array(
    'title' => 'Tasaa vasempaan reunaan'
  ),
  'justifycenter' => array(
    'title' => 'Keskitä'
  ),
  'justifyright' => array(
    'title' => 'Tasaa oikeaan reunaan'
  ),
  'fore_color' => array(
    'title' => 'Tekstin väri'
  ),
  'bg_color' => array(
    'title' => 'Tekstin taustaväri'
  ),
  'design' => array(
    'title' => 'Vaihda sisältöeditorin tekstinkäsittelyn kaltaiseen  WYSIWYG (design) -tilaan.'
  ),
  'html' => array(
    'title' => 'Vaihda HTML-kooditilaan'
  ),
  'colorpicker' => array(
    'title' => 'Värivalitsin',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
  ),
	  'cleanup' => array(
    'title' => 'HTML-koodin puhdistus (poistaa tyylimäärittelyt)',
    'confirm' => 'Tämä toiminto poistaa tämän sivun sisällöstä kaikki tyylimäärittelyt, fonttimäärittelyt ja tarpeettomat komennot. Kaikki tekstin muotoilu tai osa muotoilusta voi kadota.',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
  ),
  'toggle_borders' => array(
    'title' => 'Näytä/Piilota reunuksettomien taulukkojen reunat',
  ),
  'hyperlink' => array(
    'title' => 'Linkki',
    'url' => 'Kohdeosoite (URL)',
    'name' => 'Nimi',
    'target' => 'Target (kohdeikkuna)',
    'title_attr' => 'Otsikko',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
  ),
  'table_row_prop' => array(
    'title' => 'Taulukon rivin ominaisuudet',
    'horizontal_align' => 'Tasaus vaakasuunnassa',
    'vertical_align' => 'Tasaus Pystysuunnassa',
    'css_class' => 'CSS luokka',
    'no_wrap' => 'No wrap',
    'bg_color' => 'Taustaväri',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
    'justifyleft' => 'Vasen',
    'justifycenter' => 'Keskitä',
    'justifyright' => 'Oikea',
    'top' => 'Ylös',
    'middle' => 'Keskelle',
    'bottom' => 'Alas',
    'baseline' => 'Alareunaan',
  ),
  'symbols' => array(
    'title' => 'Erikoismerkit',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
  ),
  'templates' => array(
    'title' => 'Ulkoasupohjat',
  ),
  'page_prop' => array(
    'title' => 'Sivun ominaisuudet',
    'title_tag' => 'Otsikko (Title)',
    'charset' => 'Charset',
    'background' => 'Taustakuva',
    'bgcolor' => 'Taustaväri',
    'text' => 'Tekstin väri',
    'link' => 'Linkin väri',
    'vlink' => 'Vieraillun linkin väri',
    'alink' => 'Aktiivisen linkin väri',
    'leftmargin' => 'Vasen reunus',
    'topmargin' => 'Yläreunus',
    'css_class' => 'CSS luokka',
    'ok' => '   OK   ',
    'cancel' => 'Peruuta',
  ),
  'preview' => array(
    'title' => 'Esikatselu',
  ),
  'image_popup' => array(
    'title' => 'Ponnahduskuva',
  ),
  'zoom' => array(
    'title' => 'Zoomaa',
  )

);
?>

