<?php 
// ================================================
// SPAW File Manager plugin
// ================================================
// English language file
// ================================================
// Developed: Saulius Okunevicius, saulius@solmetra.com
// Copyright: Solmetra (c)2006 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2006-11-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'spawfm' => array(
    'title' => 'SPAW Dateimanager',
    'error_reading_dir' => 'Fehler: Verzeichnis kann nicht gelesen werden.',
    'error_upload_forbidden' => 'Fehler: Upload in dieses Verzeichnis nicht gestattet.',
    'error_upload_file_too_big' => 'Upload fehlgeschlagen: Datei ist zu groß.',
    'error_upload_failed' => 'Upload der Datei fehlgeschlagen.',
    'error_upload_file_incomplete' => 'Hochgeladene Datei ist unvolsständig. Bitte erneut versuchen.',
    'error_bad_filetype' => 'Fehler: Dateiformat nicht zulässig.',
    'error_max_filesize' => 'Maximal zul&auml;ssige Dateigr&ouml;&szlig;e:',
    'error_delete_forbidden' => 'Fehler: Löschen von Dateien in diesem Verzeichnis nicht zuässig.',
    'confirm_delete' => 'Möchten Sie die Datei "[*file*]" wirklich löschen?',
    'error_delete_failed' => 'Fehler: Datei konnte nicht gelöscht werden (Zugriffsrechte prüfen).',
    'error_no_directory_available' => 'Keine Verzeichnisse verfügbar.',
    'download_file' => '[Datei herunterladen]',
    'error_chmod_uploaded_file' => 'Upload der Datei erfolgreich, aber das Setzen der Dateizugriffsrechte ist fehlgeschlagen.',
    'error_img_width_max' => 'Max. zulässige Bildbreite: [*MAXWIDTH*]px',
    'error_img_height_max' => 'Max. zulässige Bildhöhe: [*MAXHEIGHT*]px',
    'rename_text' => 'Neuer Namen für Datei "[*FILE*]":',
    'error_rename_file_missing' => 'Ändern des Namens fehlgeschlagen - Datei konnte nicht gefunden werden.',
    'error_rename_directories_forbidden' => 'Fehler: Umbenennen von Verzeichnissen in diesem Verzeichnis nicht zulässig.',
    'error_rename_forbidden' => 'Fehler: Umbenennen von Dateien in diesem Verzeichnis nicht zulässig.',
    'error_rename_file_exists' => 'Fehler: "[*FILE*]" bereits vorhanden.',
    'error_rename_failed' => 'Fehler: Umbennen fehlgeschlagen (Zugriffsrechte prüfen).',
    'error_rename_extension_changed' => 'Fehler: Ändern der Dateierweiterung nicht zulässig!',
    'newdirectory_text' => 'Verzeichnisname eingeben:',
    'error_create_directories_forbidden' => 'Fehler: Erstellen von Verzeichnissen nicht zulässig',
    'error_create_directories_name_used' => 'Der Name ist bereits in Nutzung; bitte anderen wählen.',
    'error_create_directories_failed' => 'Fehler: Verzeichnis konnte nicht erstellt werden (Zugriffsrechte prüfen).',
    'error_create_directories_name_invalid' => 'Folgende Zeichen sind in Verzeichnisnamen nicht zulässig: / \\ : * ? " < > |',
    'confirmdeletedir_text' => 'Möchten Sie das Verzeichnis "[*DIR*]" wirklich löschen?',
    'error_delete_subdirectories_forbidden' => 'Löschen von Verzeichnissen nicht zulässig.',
    'error_delete_subdirectories_failed' => 'Verzeichnis konnte nicht gelöscht werden (Zugriffsrechte prüfen).',
    'error_delete_subdirectories_not_empty' => 'Verzeichnis ist nicht leer.',
  ),
  'buttons' => array(
    'ok'        => '  OK  ',
    'cancel'    => 'Abbruch',
    'view_list' => 'Anzeigemodus: Liste',
    'view_details' => 'Anzeigemodus: Detail',
    'view_thumbs' => 'Anzeigemodus: Vorschaubild',
    'rename'    => 'Umbenennen...',
    'delete'    => 'Löschen',
    'go_up'     => 'Hoch',
    'upload'    =>  'Upload',
    'create_directory'  =>  'Neues Verzeichnis...',
  ),
  'file_details' => array(
    'name'  =>  'Name',
    'type'  =>  'Typ',
    'size'  =>  'Größe',
    'date'  =>  'Änderungsdatum',
    'filetype_suffix'  =>  'Datei',
    'img_dimensions'  =>  'Größe',
    'file_folder'  =>  'Dateiordner',
  ),
  'filetypes' => array(
    'any'       => 'Alle Dateien (*.*)',
    'images'    => 'Bilddateien',
    'flash'     => 'Flashfilme',
    'documents' => 'Dokumente',
    'audio'     => 'Audiodateien',
    'video'     => 'Videodateien',
    'archives'  => 'Archive',
    '.jpg'  =>  'JPG Bilddatei',
    '.jpeg'  =>  'JPG Bilddatei',
    '.gif'  =>  'GIF Bilddatei',
    '.png'  =>  'PNG Bilddatei',
    '.swf'  =>  'Flashfilm',
    '.doc'  =>  'Microsoft Word Datei',
    '.xls'  =>  'Microsoft Excel Datei',
    '.pdf'  =>  'PDF Datei',
    '.rtf'  =>  'RTF Datei',
    '.odt'  =>  'OpenDocument Text',
    '.ods'  =>  'OpenDocument Spreadsheet',
    '.sxw'  =>  'OpenOffice.org 1.0 Textdatei',
    '.sxc'  =>  'OpenOffice.org 1.0 Spreadsheet',
    '.wav'  =>  'WAV Audiodatei',
    '.mp3'  =>  'MP3 Audiodatei',
    '.ogg'  =>  'Ogg Vorbis Audiodatei',
    '.wma'  =>  'Windows Audiodatei',
    '.avi'  =>  'AVI Videodatei',
    '.mpg'  =>  'MPEG Videodatei',
    '.mpeg'  =>  'MPEG Videodatei',
    '.mov'  =>  'QuickTime Videodatei',
    '.wmv'  =>  'Windows Videodatei',
    '.zip'  =>  'ZIP Archiv',
    '.rar'  =>  'RAR Archiv',
    '.gz'  =>  'gzip Archiv',
    '.txt'  =>  'Textdatei',
    ''  =>  '',
  ),
);
?>