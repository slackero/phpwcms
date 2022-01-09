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

// Language: German, Country Code: de
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'

$BL['usr_online'] = 'Benutzer online';

// Login Page
$BL["login_text"] = 'Anmelde-Daten eingeben';
$BL['login_error'] = 'Fehler beim Anmelden!';
$BL["login_username"] = 'Benutzer';
$BL["login_userpass"] = 'Passwort';
$BL["login_button"] = 'Anmelden';
$BL["login_lang"] = 'Backend-Sprache';

// phpwcms.php
$BL['be_nav_logout'] = 'LOGOUT';
$BL['be_nav_articles'] = 'ARTIKEL';
$BL['be_nav_files'] = 'DATEI';
$BL['be_nav_modules'] = 'MODULE';
$BL['be_nav_messages'] = 'KOMMUNIKATION';
$BL['be_nav_chat'] = 'CHAT';
$BL['be_nav_profile'] = 'PROFIL';
$BL['be_nav_admin'] = 'ADMIN';
$BL['be_nav_discuss'] = 'DISKUSSION';

$BL['be_page_title'] = 'phpwcms Backend (Verwaltung)';

$BL['be_subnav_article_center'] = 'Artikelzentrale';
$BL['be_subnav_article_new'] = 'Neuer Artikel';
$BL['be_subnav_file_center'] = 'Dateizentrale';
$BL['be_subnav_file_ftptakeover'] = 'FTP &Uuml;bernahme';
$BL['be_subnav_file_actions'] = 'Dateiaktionen';
$BL['be_subnav_mod_artists'] = 'K&uuml;nstler, Kategorie, Genre';
$BL['be_subnav_msg_center'] = 'Nachrichtenzentrale';
$BL['be_subnav_msg_new'] = 'Neue Nachricht';
$BL['be_subnav_msg_newsletter'] = 'Newsletter Abonnement';
$BL['be_subnav_chat_main'] = 'Chat Hauptseite';
$BL['be_subnav_chat_internal'] = 'Interner Chat';
$BL['be_subnav_profile_login'] = 'Anmeldedaten';
$BL['be_subnav_profile_personal'] = 'Pers&ouml;nliche Daten';
$BL['be_subnav_admin_pagelayout'] = 'Seitenlayout';
$BL['be_subnav_admin_templates'] = 'Vorlagen';
$BL['be_subnav_admin_css'] = 'Standard CSS';
$BL['be_subnav_admin_sitestructure'] = 'Seitenstruktur';
$BL['be_subnav_admin_users'] = 'Benutzerverwaltung';
$BL['be_subnav_admin_filecat'] = 'Dateikategorien';

// admin.functions.inc.php
$BL['be_func_struct_articleID'] = 'Artikel-ID';
$BL['be_func_struct_preview'] = 'Voransicht';
$BL['be_func_struct_edit'] = 'Artikel bearbeiten';
$BL['be_func_struct_sedit'] = 'Seitenstruktur bearbeiten';
$BL['be_func_struct_cut'] = 'Artikel ausschneiden';
$BL['be_func_struct_nocut'] = 'Artikel ausschneiden aufheben';
$BL['be_func_struct_svisible'] = 'Wechsel sichtbar/nicht sichtbar';
$BL['be_func_struct_spublic'] = 'Wechsel &ouml;ffentlich/nicht &ouml;ffentlich';
$BL['be_func_struct_sort_up'] = 'Sortieren: hoch';
$BL['be_func_struct_sort_down'] = 'Sortieren: runter';
$BL['be_func_struct_del_article'] = 'Artikel l&ouml;schen';
$BL['be_func_struct_del_jsmsg'] = 'M&ouml;chten Sie den Artikel \nwirklich l&ouml;schen?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article'] = 'Neuen Artikel erstellen in Strukturebene';
$BL['be_func_struct_paste_article'] = 'Artikel einf&uuml;gen in Strukturebene';
$BL['be_func_struct_insert_level'] = 'Strukturebene einsetzen in';
$BL['be_func_struct_paste_level'] = 'Strukturebene einf&uuml;gen in';
$BL['be_func_struct_cut_level'] = 'Strukturebene ausschneiden';
$BL['be_func_struct_no_cut'] = "Die oberste Ebene kann nicht ausgeschnitten werden!";
$BL['be_func_struct_no_paste1'] = "Kein Einf&uuml;gen an dieser Stelle m&ouml;glich!";
$BL['be_func_struct_no_paste2'] = 'Ist Kind in der Wurzelebene der Baumstruktur';
$BL['be_func_struct_no_paste3'] = 'Sollte hier eingef&uuml;gt werden';
$BL['be_func_struct_paste_cancel'] = 'Strukturebenen-Wechsel abbrechen';
$BL['be_func_struct_del_struct'] = 'L&ouml;schen der Strukturebene';
$BL['be_func_struct_del_sjsmsg'] = 'M&ouml;chten Sie die Strukturebene \nwirklich l&ouml;schen?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open'] = '&Ouml;ffnen';
$BL['be_func_struct_close'] = 'Schlie&szlig;en';
$BL['be_func_struct_empty'] = 'leer';

// article.contenttype.inc.php
$BL['be_ctype_plaintext'] = 'Einfacher Text';
$BL['be_ctype_html'] = 'HTML';
$BL['be_ctype_code'] = 'Code';
$BL['be_ctype_textimage'] = 'Text mit Bild';
$BL['be_ctype_images'] = 'Bilder';
$BL['be_ctype_bulletlist'] = 'Aufz&auml;hlung';
$BL['be_ctype_ullist'] = 'Liste';
$BL['be_ctype_link'] = 'Link &amp; E-Mail';
$BL['be_ctype_linklist'] = 'Linkliste';
$BL['be_ctype_linkarticle'] = 'Teaser/Artikellink';
$BL['be_ctype_multimedia'] = 'Multimedia';
$BL['be_ctype_filelist'] = 'Dateiliste';
$BL['be_ctype_emailform'] = 'E-Mail Formulargenerator';
$BL['be_ctype_newsletter'] = 'Newsletter-Anmeldung';

// profile.create.inc.php
$BL['be_profile_create_success'] = 'Profil erfolgreich erstellt.';
$BL['be_profile_create_error'] = 'Fehler beim Erzeugen des Profils.';

// profile.update.inc.php
$BL['be_profile_update_success'] = 'Profil erfolgreich aktualisiert.';
$BL['be_profile_update_error'] = 'Fehler beim Aktualisieren des Profils.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1'] = 'Benutzername {VAL} ist ung&uuml;ltig';
$BL['be_profile_account_err2'] = 'Kennwort zu kurz  (nur {VAL} Buchstaben: mind. 5 n&ouml;tig)';
$BL['be_profile_account_err3'] = 'Kennwortwiederholung stimmt nicht mit Kennwort &uuml;berein';
$BL['be_profile_account_err4'] = 'E-Mail {VAL} ist ung&uuml;ltig';

// profile.data.tmpl.php
$BL['be_profile_data_title'] = 'Ihre pers&ouml;nlichen Daten';
$BL['be_profile_data_text'] = 'Pers&ouml;nliche Daten sind optional. Andere Benutzer k&ouml;nnen hierdurch mehr &uuml;ber Sie erfahren (Ausbildung, Interessen). Je nach Auswahl k&ouml;nnen andere Benutzer Ihre pers&ouml;nlichen Daten einsehen.';
$BL['be_profile_label_title'] = 'Titel';
$BL['be_profile_label_firstname'] = 'Vorname';
$BL['be_profile_label_name'] = 'Nachname';
$BL['be_profile_label_company'] = 'Firma';
$BL['be_profile_label_street'] = 'Stra&szlig;e';
$BL['be_profile_label_city'] = 'Stadt';
$BL['be_profile_label_state'] = 'Bundesland';
$BL['be_profile_label_zip'] = 'PLZ';
$BL['be_profile_label_country'] = 'Land';
$BL['be_profile_label_phone'] = 'Telefon';
$BL['be_profile_label_fax'] = 'Fax';
$BL['be_profile_label_cellphone'] = 'Mobil';
$BL['be_profile_label_signature'] = 'Signatur';
$BL['be_profile_label_notes'] = 'Notiz';
$BL['be_profile_label_profession'] = 'Ausbildung';
$BL['be_profile_label_newsletter'] = 'Newsletter';
$BL['be_profile_text_newsletter'] = 'Ich m&ouml;chte den allgemeinen Newsletter abonnieren.';
$BL['be_profile_label_public'] = '&Ouml;ffentlich';
$BL['be_profile_text_public'] = 'Jeder kann meine pers&ouml;nlichen Informationen sehen.';
$BL['be_profile_label_button'] = 'Pers&ouml;nliche Daten aktualisieren';

// profile.account.tmpl.php
$BL['be_profile_account_title'] = 'Ihre Anmelde-Daten';
$BL['be_profile_account_text'] = 'Normalerweise sollten Sie Ihren Benutzernamen nicht wechseln.<br />Zur Erh&ouml;hung der Sicherheit &auml;ndern Sie Ihr Kennwort von Zeit zu Zeit.';
$BL['be_profile_label_err'] = 'Bitte pr&uuml;fen';
$BL['be_profile_label_username'] = 'Benutzer';
$BL['be_profile_label_newpass'] = 'Neues Kennwort';
$BL['be_profile_label_repeatpass'] = 'Kennwort erneut';
$BL['be_profile_label_email'] = 'E-Mail';
$BL['be_profile_account_button'] = 'Aktualisieren';
$BL['be_profile_label_lang'] = 'Sprache';

// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title'] = 'Dateien aus FTP Verzeichnis &uuml;bernehmen';
$BL['be_ftptakeover_mark'] = 'Wahl';
$BL['be_ftptakeover_available'] = 'Verf&uuml;gbare Dateien';
$BL['be_ftptakeover_size'] = 'Gr&ouml;&szlig;e';
$BL['be_ftptakeover_nofile'] = 'Keine Dateien verf&uuml;gbar &#8211; Sie m&uuml;ssen diese per FTP oder Mehrfachupload hochladen.';
$BL['be_ftptakeover_all'] = 'Alle';
$BL['be_ftptakeover_directory'] = 'Ordner';
$BL['be_ftptakeover_rootdir'] = 'Wurzelverzeichnis';
$BL['be_ftptakeover_needed'] = 'Ben&ouml;tigt!!! (unbedingt ausw&auml;hlen)';
$BL['be_ftptakeover_optional'] = 'Optional';
$BL['be_ftptakeover_keywords'] = 'Keywords';
$BL['be_ftptakeover_additional'] = 'Zus&auml;tzlich';
$BL['be_ftptakeover_longinfo'] = 'Info';
$BL['be_ftptakeover_status'] = 'Status';
$BL['be_ftptakeover_active'] = 'Aktiv';
$BL['be_ftptakeover_public'] = '&Ouml;ffentlich';
$BL['be_ftptakeover_createthumb'] = 'Vorschau erzeugen';
$BL['be_ftptakeover_button'] = 'Dateien &uuml;bernehmen';
$BL['be_ftptakeover_new_folder'] = 'Ordner anlegen';
$BL['be_ftptakeover_new_folder_placeholder'] = 'neuer Name des Ordners im Wurzelverzeichnis';

// files.reiter.tmpl.php
$BL['be_ftab_title'] = 'Dateizentrale';
$BL['be_ftab_createnew'] = 'Neues Verzeichnis im Wurzelverzeichnis erstellen';
$BL['be_ftab_paste'] = 'Datei aus Zwischenablage in das Wurzelverzeichnis verschieben';
$BL['be_ftab_disablethumb'] = 'Vorschau in der Liste abschalten';
$BL['be_ftab_enablethumb'] = 'Vorschau in der Liste anzeigen';
$BL['be_ftab_private'] = 'Eigene';
$BL['be_ftab_public'] = '&Ouml;ffentliche';
$BL['be_ftab_search'] = 'Suche';
$BL['be_ftab_trash'] = 'Papierkorb';
$BL['be_ftab_open'] = 'Alle Verzeichnisse &ouml;ffnen';
$BL['be_ftab_close'] = 'Alle Verzeichnisse schlie&szlig;en';
$BL['be_ftab_upload'] = 'Datei in das Wurzelverzeichnis hochladen';
$BL['be_ftab_filehelp'] = 'Dateihilfe &ouml;ffnen';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir'] = 'Wurzelverzeichnis';
$BL['be_fpriv_title'] = 'Neues Verzeichnis erstellen';
$BL['be_fpriv_inside'] = 'innerhalb';
$BL['be_fpriv_error'] = 'Fehler: Verzeichnisname angeben';
$BL['be_fpriv_errordir'] = 'Fehler: Verzeichnis kann kein Unterordner von sich selbst sein';
$BL['be_fpriv_name'] = 'Name';
$BL['be_fpriv_status'] = 'Status';
$BL['be_fpriv_button'] = 'Verzeichnis erstellen';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle'] = 'Verzeichnis bearbeiten';
$BL['be_fpriv_newname'] = 'Name neu';
$BL['be_fpriv_updatebutton'] = 'Verzeichnis aktualisieren';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1'] = 'Datei zum Hochladen ausw&auml;hlen';
$BL['be_fprivup_err2'] = 'Hochgeladene Datei ist gr&ouml;&szlig;er als';
$BL['be_fprivup_err3'] = 'Fehler beim schreiben in den Speicher';
$BL['be_fprivup_err4'] = 'Fehler beim Erstellen des Benutzerverzeichnisses.';
$BL['be_fprivup_err5'] = 'Keine Vorschau vorhanden';
$BL['be_fprivup_err6'] = 'Bitte nicht erneut versuchen! Dies ist ein Serverfehler! Setzen Sie sich unverz&uuml;glich mit Ihrem <a href="mailto:{VAL}">webmaster</a> in Verbindung!';
$BL['be_fprivup_err7'] = 'Datei %s darf aus Sicherheitsgr&uuml;nden nicht hochladen werden.';
$BL['be_fprivup_err8'] = 'Datei mit der Erweiterung %s darf nicht hochladen werden.<br />Zul&auml;ssig sind: %s.';
$BL['be_fprivup_err9'] = 'Datei ohne Erweiterung darf nicht hochladen werden.<br />Zul&auml;ssig sind: %s.';
$BL['be_fprivup_title'] = 'Datei hochladen';
$BL['be_fprivup_button'] = 'Datei hochladen';
$BL['be_fprivup_upload'] = 'Hochladen';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title'] = 'Datei-Informationen bearbeiten';
$BL['be_fprivedit_filename'] = 'Dateiname';
$BL['be_fprivedit_created'] = 'erstellt';
$BL['be_fprivedit_dateformat'] = 'd.m.Y, H:i \U\h\r';
$BL['be_fprivedit_err1'] = 'Dateiname &uuml;berpr&uuml;fen (zur&uuml;ckgesetzt)';
$BL['be_fprivedit_clockwise'] = 'Vorschau im Uhrzeigersinn drehen [Originaldatei +90&deg;]';
$BL['be_fprivedit_cclockwise'] = 'Vorschau entgegen Uhrzeigersinn drehen [Originaldatei -90&deg;]';
$BL['be_fprivedit_button'] = 'Aktualisieren';
$BL['be_fprivedit_size'] = 'Gr&ouml;&szlig;e';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload'] = 'Datei hochladen in Verzeichnis';
$BL['be_fprivfunc_makenew'] = 'Neues Verzeichnis erstellen in';
$BL['be_fprivfunc_paste'] = 'Zwischenablage-Datei einf&uuml;gen in Verzeichnis';
$BL['be_fprivfunc_edit'] = 'Verzeichnis bearbeiten';
$BL['be_fprivfunc_cactive'] = 'Wechsel aktiv/inaktiv';
$BL['be_fprivfunc_cpublic'] = 'Wechsel &ouml;ffentlich/nicht &ouml;ffentlich';
$BL['be_fprivfunc_deldir'] = 'Verzeichnis l&ouml;schen';
$BL['be_fprivfunc_jsdeldir'] = 'M&ouml;chten Sie das Verzeichnis \nwirklich l&ouml;schen?';
$BL['be_fprivfunc_notempty'] = 'Das Verzeichnis {VAL} ist nicht leer!';
$BL['be_fprivfunc_opendir'] = 'Verzeichnis &ouml;ffnen';
$BL['be_fprivfunc_closedir'] = 'Verzeichnis schlie&szlig;en';
$BL['be_fprivfunc_dlfile'] = 'Datei herunterladen';
$BL['be_fprivfunc_clipfile'] = 'Datei in Zwischenablage';
$BL['be_fprivfunc_cutfile'] = 'Ausschneiden';
$BL['be_fprivfunc_editfile'] = 'Datei-Info bearbeiten';
$BL['be_fprivfunc_cactivefile'] = 'Wechsel aktiv/inaktiv';
$BL['be_fprivfunc_cpublicfile'] = 'Wechsel &ouml;ffentlich/nicht &ouml;ffentlich';
$BL['be_fprivfunc_movetrash'] = 'In Papierkorb legen';
$BL['be_fprivfunc_jsmovetrash1'] = 'Wirklich';
$BL['be_fprivfunc_jsmovetrash2'] = 'in den Papierkorb verschieben?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders'] = 'Keine eigenen Dateien und Verzeichnisse';

// files.public.list.tmpl.php
$BL['be_fpublic_user'] = 'Benutzer';
$BL['be_fpublic_nofiles'] = 'Keine &ouml;ffentlichen Dateien und Verzeichnisse';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles'] = 'Papierkorb ist leer';
$BL['be_ftrash_show'] = 'Eigene Dateien zeigen';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore'] = 'Datei {VAL} \nin in eigene Dateien\nzur&uuml;cklegen?';
$BL['be_ftrash_delete'] = 'Datei {VAL} wirklich l&ouml;schen?';
$BL['be_ftrash_undo'] = 'Wiederherstellen (aus Papierkorb herausnehmen)';
$BL['be_ftrash_delfinal'] = 'endg&uuml;ltig l&ouml;schen';

// files.search.tmpl.php
$BL['be_fsearch_err1'] = 'Die Sucheingabe ist leer.';
$BL['be_fsearch_title'] = 'Dateisuche';
$BL['be_fsearch_infotext'] = 'Das ist eine einfache Suche nach Datei-Informationen. Es wird in den Schl&uuml;sselw&ouml;rtern,<br />dem Dateinamen sowie der ausf&uuml;hrlichen Information gesucht.<br />Es werden keine Platzhalter (Wildcards) unterst&uuml;tzt. Mehrere Suchworte bitte <br />mit Leerzeichen voneinander trennen. W&auml;hlen Sie bitte die Option UND/ODER <br />und welche Dateien gesucht werden sollen: eigene/&ouml;ffentlich.';
$BL['be_fsearch_nonfound'] = 'F&uuml;r die Sucheingabe konnten keine Dateien gefunden werden.<br />&nbsp;&nbsp;&nbsp;&nbsp;Bitte versuchen Sie es erneut!';
$BL['be_fsearch_fillin'] = 'Bitte geben Sie eine Suchanweisung im entsprechendem Feld ein.';
$BL['be_fsearch_searchlabel'] = 'Suche';
$BL['be_fsearch_startsearch'] = 'Suchen';
$BL['be_fsearch_and'] = 'UND';
$BL['be_fsearch_or'] = 'ODER';
$BL['be_fsearch_all'] = 'Alle Dateien';
$BL['be_fsearch_personal'] = 'Eigene';
$BL['be_fsearch_public'] = '&Ouml;ffentliche';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title'] = 'Interner Chat';
$BL['be_chat_info'] = 'An dieser Stelle k&ouml;nnen Sie mit anderen Backend Benutzern chatten. Der Chat ist f&uuml;r die Echtzeit-Unterhaltung gedacht, kann aber auch genutzt werden, um Nachrichten f&uuml;r jedermann zu hinterlassen. Um Gedanken auszutauschen, nutzen Sie bitte den Bereich Diskussion (in Planung).';
$BL['be_chat_start'] = 'Gehe zum Chat (hier klicken)';
$BL['be_chat_lines'] = 'Anzeige Chat-Verlauf';

// message.center.tmpl.php
$BL['be_msg_title'] = 'Nachrichtenzentrale';
$BL['be_msg_new'] = 'neu';
$BL['be_msg_old'] = 'alt';
$BL['be_msg_senttop'] = 'gesendet';
$BL['be_msg_del'] = 'gel&ouml;scht';
$BL['be_msg_from'] = 'von';
$BL['be_msg_subject'] = 'Betreff';
$BL['be_msg_date'] = 'Datum/Zeit';
$BL['be_msg_close'] = 'Nachricht schlie&szlig;en';
$BL['be_msg_create'] = 'Neue Nachricht erstellen';
$BL['be_msg_reply'] = 'Auf Nachricht antworten';
$BL['be_msg_move'] = 'Nachricht in Papierkorb legen';
$BL['be_msg_unread'] = 'ungelesene oder neue Nachrichten';
$BL['be_msg_lastread'] = '{VAL} zuletzt gelesene Nachrichten';
$BL['be_msg_lastsent'] = '{VAL} zuletzt gesendete Nachrichten';
$BL['be_msg_marked'] = 'Nachrichten markiert zum L&ouml;schen (Papierkorb)';
$BL['be_msg_nomsg'] = 'keine Nachricht in der Ablage gefunden';

// message.send.tmpl.php
$BL['be_msg_RE'] = 'RE';
$BL['be_msg_by'] = 'gesendet von';
$BL['be_msg_on'] = 'am';
$BL['be_msg_msg'] = 'Nachricht';
$BL['be_msg_err1'] = 'Kein Empf&auml;nger angegeben...';
$BL['be_msg_err2'] = 'Betreff eintragen (der Empf&auml;nger kann die Nachricht besser einordnen)';
$BL['be_msg_err3'] = 'Es macht keinen Sinn eine Nachricht ohne Text zu senden ;-)';
$BL['be_msg_sent'] = 'Neue Nachricht wurde versendet!';
$BL['be_msg_fwd'] = 'Sie werden zur Nachrichtenzentrale weitergeleitet...';
$BL['be_msg_newmsgtitle'] = 'Neue Nachricht verfassen';
$BL['be_msg_err'] = 'Fehler beim Senden der Nachricht';
$BL['be_msg_sendto'] = 'Nachricht senden an';
$BL['be_msg_available'] = 'Verf&uuml;gbare Empf&auml;nger';
$BL['be_msg_all'] = 'Nachricht an alle ausgew&auml;hlten Empf&auml;nger senden';

// message.subscription.tmpl.php
$BL['be_newsletter_title'] = 'Newsletter Abonnements';
$BL['be_newsletter_titleedit'] = 'Newsletter Abonnement bearbeiten';
$BL['be_newsletter_new'] = 'Neu erstellen';
$BL['be_newsletter_add'] = 'Newsletter Abonnement hinzuf&uuml;gen';
$BL['be_newsletter_name'] = 'Name';
$BL['be_newsletter_info'] = 'Info';
$BL['be_newsletter_button_save'] = 'Newsletter Abo speichern';
$BL['be_newsletter_button_cancel'] = 'Abbrechen';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1'] = 'Benutzername ist ung&uuml;ltig, anderen w&auml;hlen';
$BL['be_admin_usr_err2'] = 'Benutzername ist leer (ben&ouml;tigt)';
$BL['be_admin_usr_err3'] = 'Kennwort ist leer (ben&ouml;tigt)';
$BL['be_admin_usr_err4'] = "E-Mail ist ung&uuml;ltig";
$BL['be_admin_usr_err'] = 'Fehler';
$BL['be_admin_usr_mailsubject'] = 'Willkommen im phpwcms Backend';
$BL['be_admin_usr_mailbody'] = "WILLKOMMEN IM PHPWCMS BACKEND\n\n    Benutzer: {LOGIN}\n    Kennwort: {PASSWORD}\n\n\nSie können sich hier anmelden: {LOGIN_PAGE}\n\nphpwcms Administrator\n ";
$BL['be_admin_usr_title'] = 'Neuen Benutzer anlegen';
$BL['be_admin_usr_realname'] = 'Wahrer Name';
$BL['be_admin_usr_setactive'] = 'Aktivieren';
$BL['be_admin_usr_iflogin'] = 'Benutzer kann sich anmelden';
$BL['be_admin_usr_isadmin'] = 'Administrator';
$BL['be_admin_usr_ifadmin'] = 'Benutzer erh&auml;lt Administrator-Rechte';
$BL['be_admin_usr_verify'] = '&Uuml;berpr&uuml;fung';
$BL['be_admin_usr_sendemail'] = 'E-Mail mit den Anmeldedaten an den neuen Benutzer senden';
$BL['be_admin_usr_button'] = 'Benutzerdaten senden';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle'] = 'Benutzerdaten bearbeiten';
$BL['be_admin_usr_emailsubject'] = 'phpwcms - Anmeldedaten ge&auml;ndert';
$BL['be_admin_usr_emailbody'] = "PHPWCMS ANMELDEDATEN GEÄNDERT\n\n    Benutzer: {LOGIN}\n    Kennwort: {PASSWORD}\n\n\nSie können sich hier anmelden: {LOGIN_PAGE}\n\nphpwcms Administrator\n ";
$BL['be_admin_usr_passnochange'] = '[KEINE ÄNDERUNG - DAS BEKANNTE KENNWORT NUTZEN]';
$BL['be_admin_usr_ebutton'] = 'Benutzerdaten aktualisieren';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle'] = 'phpwcms Benutzerliste';
$BL['be_admin_usr_ldel'] = 'ACHTUNG!&#13;Der Benutzer wird gel&ouml;scht';
$BL['be_admin_usr_create'] = 'Benutzer erstellen';
$BL['be_admin_usr_editusr'] = 'Benutzerdaten bearbeiten';

// admin.structform.tmpl.php
$BL['be_admin_struct_title'] = 'Seitenstruktur';
$BL['be_admin_struct_child'] = '(enthalten in)';
$BL['be_admin_struct_index'] = 'Homepage (Startseite)';
$BL['be_admin_struct_cat'] = 'Seitenebene Name';
$BL['be_admin_struct_alt'] = 'Alternativer Name der Seitenebene';
$BL['be_admin_struct_hide1'] = 'versteckt';
$BL['be_admin_struct_hide2'] = 'in der Men&uuml;struktur zeigen';
$BL['be_admin_struct_info'] = 'Beschreibung der Seitenebene';
$BL['be_admin_struct_template'] = 'Vorlage';
$BL['be_admin_struct_alias'] = 'Alias der Seitenebene';
$BL['be_admin_struct_visible'] = 'sichtbar';
$BL['be_admin_struct_button'] = 'Struktur sichern';
$BL['be_admin_struct_close'] = 'Schlie&szlig;en';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title'] = 'Dateikategorien';
$BL['be_admin_fcat_err'] = 'Kategoriename ist leer!';
$BL['be_admin_fcat_name'] = 'Kategoriename';
$BL['be_admin_fcat_needed'] = 'ben&ouml;tigt';
$BL['be_admin_fcat_button1'] = 'Aktualisieren';
$BL['be_admin_fcat_button2'] = 'Erstellen';
$BL['be_admin_fcat_delmsg'] = 'Den folgenden Dateischl&uuml;ssel wirklich l&ouml;schen?';
$BL['be_admin_fcat_fcat'] = 'Dateikategorie';
$BL['be_admin_fcat_err1'] = 'Dateischl&uuml;ssel-Name ist leer!';
$BL['be_admin_fcat_fkeyname'] = 'Dateischl&uuml;ssel';
$BL['be_admin_fcat_exit'] = 'Abbruch';
$BL['be_admin_fcat_addkey'] = 'Neuen Schl&uuml;ssel hinzuf&uuml;gen';
$BL['be_admin_fcat_editcat'] = 'Kategoriename bearbeiten';
$BL['be_admin_fcat_delcatmsg'] = 'Die folgende Dateikategorie wirklich l&ouml;schen?';
$BL['be_admin_fcat_delcat'] = 'Dateikategorie l&ouml;schen';
$BL['be_admin_fcat_delkey'] = 'Dateischl&uuml;ssel l&ouml;schen';
$BL['be_admin_fcat_editkey'] = 'Dateischl&uuml;ssel bearbeiten';
$BL['be_admin_fcat_addcat'] = 'Neue Dateikategorie erstellen';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title'] = 'Frontend Setup: Seitenlayout';
$BL['be_admin_page_align'] = 'Ausrichtung';
$BL['be_admin_page_align_left'] = 'Standardausrichtung (links) des gesamten Seiteninhalts';
$BL['be_admin_page_align_center'] = 'Seiteninhalt zentriert ausrichten';
$BL['be_admin_page_align_right'] = 'Seiteninhalt rechts ausrichten';
$BL['be_admin_page_margin'] = 'Rand';
$BL['be_admin_page_top'] = 'oben';
$BL['be_admin_page_bottom'] = 'unten';
$BL['be_admin_page_left'] = 'links';
$BL['be_admin_page_right'] = 'rechts';
$BL['be_admin_page_bg'] = 'Hintergrund';
$BL['be_admin_page_color'] = 'Farbe';
$BL['be_admin_page_height'] = 'H&ouml;he';
$BL['be_admin_page_width'] = 'Breite';
$BL['be_admin_page_main'] = 'Haupt';
$BL['be_admin_page_leftspace'] = 'Abstand links';
$BL['be_admin_page_rightspace'] = 'Abstand rechts';
$BL['be_admin_page_class'] = 'Class';
$BL['be_admin_page_image'] = 'Bild';
$BL['be_admin_page_text'] = 'Text';
$BL['be_admin_page_link'] = 'Link';
$BL['be_admin_page_js'] = 'JavaScript';
$BL['be_admin_page_visited'] = 'besucht';
$BL['be_admin_page_pagetitle'] = 'Seitentitel';
$BL['be_admin_page_addtotitle'] = 'Titel zuf&uuml;gen';
$BL['be_admin_page_category'] = 'Kategorie';
$BL['be_admin_page_articlename'] = 'Artikelname';
$BL['be_admin_page_blocks'] = 'Bl&ouml;cke';
$BL['be_admin_page_allblocks'] = 'Alle Bl&ouml;cke';
$BL['be_admin_page_col1'] = '3-Spalten-Layout';
$BL['be_admin_page_col2'] = '2-Spalten-Layout &#13;(Hauptspalte rechts, Navigationsspalte links)';
$BL['be_admin_page_col3'] = '2-Spalten-Layout &#13;(Hauptspalte links, Navigationsspalte rechts)';
$BL['be_admin_page_col4'] = '1-Spalten-Layout';
$BL['be_admin_page_header'] = 'Kopfzeile';
$BL['be_admin_page_footer'] = 'Fu&szlig;zeile';
$BL['be_admin_page_topspace'] = 'Abstand oben';
$BL['be_admin_page_bottomspace'] = 'Abstand unten';
$BL['be_admin_page_button'] = 'Seitenlayout speichern';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title'] = 'Frontend Setup: CSS Daten';
$BL['be_admin_css_css'] = 'CSS';
$BL['be_admin_css_button'] = 'CSS Daten speichern';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title'] = 'Frontend Setup: Vorlagen';
$BL['be_admin_tmpl_default'] = 'Standard';
$BL['be_admin_tmpl_add'] = 'Vorlage hinzuf&uuml;gen';
$BL['be_admin_tmpl_edit'] = 'Vorlage bearbeiten';
$BL['be_admin_tmpl_new'] = 'Neue Vorlage';
$BL['be_admin_tmpl_css'] = 'CSS Datei';
$BL['be_admin_tmpl_head'] = 'HTML Kopf';
$BL['be_admin_tmpl_js'] = 'JS onload';
$BL['be_admin_tmpl_error'] = 'Fehler';
$BL['be_admin_tmpl_button'] = 'Vorlage speichern';
$BL['be_admin_tmpl_name'] = 'Name';

// article.structlist.tmpl.php
$BL['be_article_title'] = 'Seitenstruktur und Artikelliste';

// article.new.tmpl.php
$BL['be_article_err1'] = 'Artikeltitel ist leer';
$BL['be_article_err2'] = 'Falsches Startdatum - auf JETZT gesetzt';
$BL['be_article_err3'] = 'Falsches Enddatum - auf JETZT gesetzt';
$BL['be_article_title1'] = 'Artikel Basisinformation';
$BL['be_article_cat'] = 'Kategorie';
$BL['be_article_atitle'] = 'Artikeltitel';
$BL['be_article_asubtitle'] = 'Untertitel';
$BL['be_article_abegin'] = 'Anzeige von';
$BL['be_article_aend'] = 'bis';
$BL['be_article_aredirect'] = 'Weiterleiten';
$BL['be_article_akeywords'] = 'Schl&uuml;sselw&ouml;rter';
$BL['be_article_asummary'] = 'Schlagtext';
$BL['be_article_abutton'] = 'Artikel erstellen';

// article.editcontent.inc.php
$BL['be_article_err4'] = 'Falsches Enddatum - auf JETZT + 1 Woche gesetzt';

// article.editsummary.tmpl.php
$BL['be_article_estitle'] = 'Artikel Basisinformation bearbeiten';
$BL['be_article_eslastedit'] = 'zuletzt';
$BL['be_article_esnoupdate'] = 'Keine Aktualisierung';
$BL['be_article_esbutton'] = 'Artikeldaten aktualisieren';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title'] = 'Artikelinhalt';
$BL['be_article_cnt_type'] = 'Inhaltsart';
$BL['be_article_cnt_space'] = 'Abstand';
$BL['be_article_cnt_before'] = 'davor';
$BL['be_article_cnt_after'] = 'danach';
$BL['be_article_cnt_top'] = 'oben';
$BL['be_article_cnt_toplink'] = 'Top-Link';
$BL['be_article_cnt_anchor'] = 'Anker';
$BL['be_article_cnt_ctitle'] = 'Inhaltstitel';
$BL['be_article_cnt_back'] = 'Gesamten Artikelinhalt anzeigen';
$BL['be_article_cnt_button1'] = 'Aktualisieren';
$BL['be_article_cnt_button2'] = 'Erstellen';
$BL['be_article_cnt_button3'] = 'Sichern';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle'] = 'Artikelinformation';
$BL['be_article_cnt_ledit'] = 'Artikel bearbeiten';
$BL['be_article_cnt_lvisible'] = 'Wechsel sichtbar/nicht sichtbar';
$BL['be_article_cnt_ldel'] = 'Diesen Artikel l&ouml;schen';
$BL['be_article_cnt_ldeljs'] = 'Artikel l&ouml;schen?';
$BL['be_article_cnt_redirect'] = 'Weiterleitung';
$BL['be_article_cnt_edited'] = 'bearbeitet von';
$BL['be_article_cnt_start'] = 'Startdatum';
$BL['be_article_cnt_end'] = 'Enddatum';
$BL['be_article_cnt_add'] = 'Hinzuf&uuml;gen';
$BL['be_article_cnt_addtitle'] = 'Neuen Inhaltsabschnitt hinzuf&uuml;gen';
$BL['be_article_cnt_up'] = 'Inhalt nach oben';
$BL['be_article_cnt_down'] = 'Inhalt nach unten';
$BL['be_article_cnt_edit'] = 'Inhaltsabschnitt bearbeiten';
$BL['be_article_cnt_delpart'] = 'Diesen Artikel-Inhaltsabschnitt l&ouml;schen';
$BL['be_article_cnt_delpartjs'] = 'Artikel-Inhaltsabschnitt l&ouml;schen?';
$BL['be_article_cnt_center'] = 'Artikelzentrale';

// content forms
$BL['be_cnt_plaintext'] = 'Einf. Text';
$BL['be_cnt_htmltext'] = 'HTML Text';
$BL['be_cnt_image'] = 'Bild';
$BL['be_cnt_position'] = 'Position';
$BL['be_cnt_pos0'] = 'Dar&uuml;ber, links';
$BL['be_cnt_pos1'] = 'Dar&uuml;ber, zentriert';
$BL['be_cnt_pos2'] = 'Dar&uuml;ber, rechts';
$BL['be_cnt_pos3'] = 'Unterhalb, links';
$BL['be_cnt_pos4'] = 'Unterhalb, zentriert';
$BL['be_cnt_pos5'] = 'Unterhalb, rechts';
$BL['be_cnt_pos6'] = 'Im Text, links';
$BL['be_cnt_pos7'] = 'Im Text, rechts';
$BL['be_cnt_pos0i'] = 'Bild links &uuml;ber dem Textblock anordnen';
$BL['be_cnt_pos1i'] = 'Bild zentriert &uuml;ber dem Textblock anordnen';
$BL['be_cnt_pos2i'] = 'Bild rechts &uuml;ber dem Textblock anordnen';
$BL['be_cnt_pos3i'] = 'Bild links unter dem Textblock anordnen';
$BL['be_cnt_pos4i'] = 'Bild zentriert unter dem Textblock anordnen';
$BL['be_cnt_pos5i'] = 'Bild rechts unter dem Textblock anordnen';
$BL['be_cnt_pos6i'] = 'Bild innerhalb des Textblocks links anordnen';
$BL['be_cnt_pos7i'] = 'Bild innerhalb des Textblocks rechts anordnen';
$BL['be_cnt_maxw'] = 'max.&nbsp;Breite';
$BL['be_cnt_maxh'] = 'max.&nbsp;H&ouml;he';
$BL['be_cnt_enlarge'] = 'Klick&nbsp;vergr&ouml;&szlig;ern';
$BL['be_cnt_caption'] = 'Bildunterzeile';
$BL['be_cnt_subject'] = 'Betreff';
$BL['be_cnt_recipient'] = 'Empf&auml;nger';
$BL['be_cnt_buttontext'] = 'Button';
$BL['be_cnt_sendas'] = 'Senden als';
$BL['be_cnt_text'] = 'Text';
$BL['be_cnt_html'] = 'HTML';
$BL['be_cnt_formfields'] = 'Form.Felder';
$BL['be_cnt_code'] = 'Code';
$BL['be_cnt_infotext'] = 'Infotext';
$BL['be_cnt_subscription'] = 'Abos/Kanal';
$BL['be_cnt_labelemail'] = 'Label E-Mail';
$BL['be_cnt_tablealign'] = 'Ausrichten';
$BL['be_cnt_labelname'] = 'Label Name';
$BL['be_cnt_labelsubsc'] = 'Label Abo';
$BL['be_cnt_allsubsc'] = 'Alle Abos';
$BL['be_cnt_default'] = 'Standard';
$BL['be_cnt_left'] = 'Links';
$BL['be_cnt_center'] = 'Zentriert';
$BL['be_cnt_right'] = 'Rechts';
$BL['be_cnt_successtext'] = 'Erfolgsmeld.';
$BL['be_cnt_regmail'] = 'Anmeldung';
$BL['be_cnt_logoffmail'] = 'Abmeldung';
$BL['be_cnt_changemail'] = '&Auml;nderung';
$BL['be_cnt_openimagebrowser'] = 'Bildbrowser &ouml;ffnen';
$BL['be_cnt_openfilebrowser'] = 'Dateibrowser &ouml;ffnen';
$BL['be_cnt_sortup'] = 'nach oben';
$BL['be_cnt_sortdown'] = 'nach unten';
$BL['be_cnt_delimage'] = 'Gew&auml;hltes Bild entfernen';
$BL['be_cnt_delfile'] = 'Gew&auml;hlte Datei entfernen';
$BL['be_cnt_delmedia'] = 'Gew&auml;hlte Media-Datei entfernen';
$BL['be_cnt_column'] = 'Spalten';
$BL['be_cnt_imagespace'] = 'Bildabstand';
$BL['be_cnt_directlink'] = 'Direkter Link';
$BL['be_cnt_target'] = 'Ziel';
$BL['be_cnt_target1'] = 'in neuem Fenster';
$BL['be_cnt_target2'] = 'im Parent-Frame des Fensters';
$BL['be_cnt_target3'] = 'im selben Fenster ohne Frames';
$BL['be_cnt_target4'] = 'im selben Frame oder Fenster';
$BL['be_cnt_bullet'] = 'Aufz&auml;hlung';
$BL['be_cnt_ullist'] = 'Liste';
$BL['be_cnt_ullist_desc'] = '~ = 1. Ebene, &nbsp ~~ = 2. Ebene, &nbsp; usw.';
$BL['be_cnt_linklist'] = 'Linkliste';
$BL['be_cnt_plainhtml'] = 'reines HTML';
$BL['be_cnt_files'] = 'Dateien';
$BL['be_cnt_description'] = 'Beschreibung';
$BL['be_cnt_linkarticle'] = 'Artikellink';
$BL['be_cnt_articles'] = 'Artikel';
$BL['be_cnt_movearticleto'] = 'ausgew&auml;hlten Artikel der Artikellinks-Liste hinzuf&uuml;gen';
$BL['be_cnt_removearticleto'] = 'ausgew&auml;hlten Artikel von der Artikellinks-Liste entfernen';
$BL['be_cnt_mediatype'] = 'Medientyp';
$BL['be_cnt_control'] = 'Steuerung';
$BL['be_cnt_showcontrol'] = 'Steuerung zeigen';
$BL['be_cnt_autoplay'] = 'AutoPlay';
$BL['be_cnt_source'] = 'Quelle';
$BL['be_cnt_internal'] = 'intern';
$BL['be_cnt_openmediabrowser'] = 'Medienbrowser &ouml;ffnen';
$BL['be_cnt_external'] = 'extern';
$BL['be_cnt_mediapos0'] = 'links (Standard)';
$BL['be_cnt_mediapos1'] = 'zentriert';
$BL['be_cnt_mediapos2'] = 'rechts';
$BL['be_cnt_mediapos3'] = 'Absatz, links';
$BL['be_cnt_mediapos4'] = 'Absatz, rechts';
$BL['be_cnt_mediapos0i'] = 'Mediainhalt links &uuml;ber dem Absatz ausrichten';
$BL['be_cnt_mediapos1i'] = 'Mediainhalt zentriert &uuml;ber dem Absatz ausrichten';
$BL['be_cnt_mediapos2i'] = 'Mediainhalt rechts &uuml;ber dem Absatz ausrichten';
$BL['be_cnt_mediapos3i'] = 'Mediainhalt innerhalb des Absatzes links ausrichten';
$BL['be_cnt_mediapos4i'] = 'Mediainhalt innerhalb des Absatzes rechts ausrichten';
$BL['be_cnt_setsize'] = 'Ma&szlig;e setzen';
$BL['be_cnt_set1'] = 'Mediama&szlig; auf 160x120px einstellen';
$BL['be_cnt_set2'] = 'Mediama&szlig; auf 240x180px einstellen';
$BL['be_cnt_set3'] = 'Mediama&szlig; auf 320x240px einstellen';
$BL['be_cnt_set4'] = 'Mediama&szlig; auf 480x360px einstellen';
$BL['be_cnt_set5'] = 'Mediama&szlig;e entfernen';

// added: 28-12-2003
$BL['be_admin_page_add'] = 'Neues Seitenlayout anlegen';
$BL['be_admin_page_name'] = 'Layout Name';
$BL['be_admin_page_edit'] = 'Seitenlayout bearbeiten';
$BL['be_admin_page_render'] = 'Seitenaufbau';
$BL['be_admin_page_table'] = 'Tabelle';
$BL['be_admin_page_div'] = 'CSS DIV';
$BL['be_admin_page_custom'] = 'Eigener';
$BL['be_admin_page_custominfo'] = 'aus Vorlage Haupt-Block';
$BL['be_admin_tmpl_layout'] = 'Layout';
$BL['be_admin_tmpl_nolayout'] = 'Kein Seitenlayout verf&uuml;gbar!';

// added: 31-12-2003
$BL['be_ctype_search'] = 'Suche';
$BL['be_cnt_results'] = 'Ergebnisse';
$BL['be_cnt_results_per_page'] = 'pro&nbsp;Seite (wenn leer, zeige max. 25)';
$BL['be_cnt_opennewwin'] = '&Ouml;ffne neues Fenster';
$BL['be_cnt_searchlabeltext'] = 'Dies sind vordefinierte Texte und Werte f&uuml;r die Anzeige des Suchformulars sowie der Suchergebnis-Seiten, wenn die Anzahl an Suchergebnissen pro Seite gr&ouml;&szlig;er ist';
$BL['be_cnt_input'] = 'Eingabe';
$BL['be_cnt_style'] = 'Stil';
$BL['be_cnt_result'] = 'Ergebnis';
$BL['be_cnt_next'] = 'Weitere';
$BL['be_cnt_previous'] = 'Vorige';
$BL['be_cnt_align'] = 'Ausrichtung';
$BL['be_cnt_searchformtext'] = 'Die folgenden Texte werden angezeigt, wenn das Suchformular aufgerufen, ein Ergebnis angezeigt oder kein Ergebnis zur&uuml;ckgegeben wird.';
$BL['be_cnt_intro'] = 'Intro';
$BL['be_cnt_noresult'] = 'Kein Ergebnis';
$BL['be_cnt_search_default_type'] = 'Suchtyp';

// added: 02-01-2004
$BL['be_admin_page_disable'] = 'abschalten';

// added: 09-01-2004
$BL['be_article_articleowner'] = 'Artikelbesitzer';
$BL['be_article_adminuser'] = 'hat Admin-Rechte';
$BL['be_article_username'] = 'Autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg'] = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly'] = 'sichtbar nur f&uuml;r angemeldete Benutzer';
$BL['be_admin_struct_status'] = 'Frontend Men&uuml;status';

// added: 15-02-2004
$BL['be_ctype_articlemenu'] = 'Artikelmen&uuml;';
$BL['be_cnt_sitelevel'] = 'Seitenebene';
$BL['be_cnt_sitecurrent'] = 'Aktuelle Seitenebene';

// added: 24-03-2004
$BL['be_subnav_admin_starttext'] = 'Backend Standardtext';
$BL['be_ctype_ecard'] = 'E-Card';
$BL['be_ctype_blog'] = 'Blog';
$BL['be_cnt_ecardtext'] = 'Titel/E-Card';
$BL['be_cnt_ecardtmpl'] = 'E-Mail';
$BL['be_cnt_ecard_image'] = 'E-Card Bild';
$BL['be_cnt_ecard_title'] = 'E-Card Titel';
$BL['be_cnt_alignment'] = 'Ausrichtung';
$BL['be_cnt_ecardform'] = 'Formular';
$BL['be_cnt_ecardform_err'] = 'Alle mit * markierten Felder m&uuml;ssen korrekt ausgef&uuml;llt werden';
$BL['be_cnt_ecardform_sender'] = 'Absender';
$BL['be_cnt_ecardform_recipient'] = 'Empf&auml;nger';
$BL['be_cnt_ecardform_name'] = 'Name';
$BL['be_cnt_ecardform_msgtext'] = 'Ihre Mitteilung an den Empf&auml;nger';
$BL['be_cnt_ecardform_button'] = 'E-Card versenden';
$BL['be_cnt_ecardsend'] = 'Gesendet';

// added: 28-03-2004
$BL['be_admin_startup_title'] = 'Backend Standard-Starttext';
$BL['be_admin_startup_text'] = 'Starttext';
$BL['be_admin_startup_button'] = 'Starttext speichern';

// added: 17-04-2004
$BL['be_ctype_guestbook'] = 'G&auml;stebuch/Kommentar';
$BL['be_cnt_guestbook_listing'] = 'Anzeige';
$BL['be_cnt_guestbook_listing_all'] = 'Alle Eintr&auml;ge';
$BL['be_cnt_guestbook_list'] = 'Zeige';
$BL['be_cnt_guestbook_perpage'] = 'Eintr&auml;ge&nbsp;je&nbsp;Seite';
$BL['be_cnt_guestbook_form'] = 'Formular';
$BL['be_cnt_guestbook_signed'] = 'Eingetragen';
$BL['be_cnt_guestbook_nav'] = 'Navigation';
$BL['be_cnt_guestbook_before'] = 'Davor';
$BL['be_cnt_guestbook_after'] = 'Danach';
$BL['be_cnt_guestbook_entry'] = 'Eintrag';
$BL['be_cnt_guestbook_edit'] = 'Bearbeiten';
$BL['be_cnt_ecardform_selector'] = 'Auswahlart';
$BL['be_cnt_ecardform_radiobutton'] = 'Radio Button';
$BL['be_cnt_ecardform_javascript'] = 'JavaScript Funktionalit&auml;t';
$BL['be_cnt_ecardform_over'] = 'onMouseOver';
$BL['be_cnt_ecardform_click'] = 'onClick';
$BL['be_cnt_ecardform_out'] = 'onMouseOut';
$BL['be_admin_struct_topcount'] = 'Anzahl an Top-Artikeln';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend'] = 'Newsletter';
$BL['be_newsletter_addnl'] = 'Neuen Newsletter erstellen';
$BL['be_newsletter_titleeditnl'] = 'Newsletter bearbeiten';
$BL['be_newsletter_newnl'] = 'Neuen Newsletter anlegen';
$BL['be_newsletter_button_savenl'] = 'Newsletter sichern';
$BL['be_newsletter_fromname'] = 'Von Name';
$BL['be_newsletter_fromemail'] = 'Von E-Mail';
$BL['be_newsletter_replyto'] = 'Reply E-Mail';
$BL['be_newsletter_changed'] = 'ge&auml;ndert';
$BL['be_newsletter_placeholder'] = 'Platzhalter';
$BL['be_newsletter_htmlpart'] = 'HTML Newsletter Inhalt';
$BL['be_newsletter_textpart'] = 'TEXT Newletter Inhalt';
$BL['be_newsletter_allsubscriptions'] = 'Alle Abonnements';
$BL['be_newsletter_verifypage'] = '&Auml;nderungslink';
$BL['be_newsletter_open'] = 'HTML und TEXT Eingabe';
$BL['be_newsletter_open1'] = '(Aufklappgrafik anklicken)';
$BL['be_newsletter_sendnow'] = 'Newsletter versenden';
$BL['be_newsletter_attention'] = '<strong style="color:#CC3300;">Achtung!</strong> Das Versenden eines Newsletters ist eine sehr sensible Angelegenheit. Empf&auml;nger sollten best&auml;tigt sein, anderenfalls versenden Sie potentielle Spam E-Mails. &Uuml;berlegen Sie zweimal, vor dem Versenden eines Newsletters. Testen Sie den Newsletter, bevor Sie diesen endg&uuml;ltig versenden.';
$BL['be_newsletter_attention1'] = 'Sollten Sie soeben &Auml;nderungen an den Daten des Newsletter vorgenommen haben, so sichern Sie diese bitte zuerst, anderenfalls werden diese nicht &uuml;bernommen.';
$BL['be_newsletter_testemail'] = 'Testempf&auml;nger';
$BL['be_newsletter_sendnlbutton'] = 'VERSENDEN!!!';
$BL['be_newsletter_sendprocess'] = 'Sendevorgang';
$BL['be_newsletter_attention2'] = '<strong style="color:#CC3300;">Achtung!</strong> Stoppen Sie den Vorgang bitte nicht, anderenfalls ist es m&ouml;glich, dass der Newsletter mehrfach an Empf&auml;nger gesendet wird. Wenn der Sendevorgang aufgrund eines Fehlers stoppt, werden alle bisher nicht erreichten Empf&auml;nger in der aktuellen Session gespeichert und genutzt, wenn der Newsletterversand sofort wiederholt wird.';
$BL['be_newsletter_testerror'] = '<p style="color:#CC3300;">Die Test E-Mail Adresse</p><blockquote>###TEST###</blockquote><p style="color:#CC3300;">ist NICHT g&uuml;ltig!<br />&nbsp;<br />Bitte versuchen Sie es erneut!</p>';
$BL['be_newsletter_to'] = 'Empf&auml;nger';
$BL['be_newsletter_ready'] = 'Newsletter-Versand: FERTIG';
$BL['be_newsletter_readyfailed'] = 'Nicht erfolgreich gesendet an';
$BL['be_subnav_msg_subscribers'] = 'Newsletter Abonnenten';

// added: 20-04-2004
$BL['be_ctype_sitemap'] = 'Sitemap';
$BL['be_cnt_sitemap_catimage'] = 'Ebene Icon';
$BL['be_cnt_sitemap_articleimage'] = 'Artikel Icon';
$BL['be_cnt_sitemap_display'] = 'Anzeigen';
$BL['be_cnt_sitemap_structuronly'] = 'Nur Seitenebenen';
$BL['be_cnt_sitemap_structurarticle'] = 'Seiteneben + Artikel';
$BL['be_cnt_sitemap_catclass'] = 'Ebene CSS Class';
$BL['be_cnt_sitemap_articleclass'] = 'Artikel CSS Class';
$BL['be_cnt_sitemap_count'] = 'Z&auml;hler';
$BL['be_cnt_sitemap_classcount'] = 'im CSS Class Namen mitf&uuml;hren';
$BL['be_cnt_sitemap_noclasscount'] = 'nicht im CSS Class Namen mitf&uuml;hren';
$BL['be_cnt_sitemap_without_parent'] = 'ohne Startebene';

// added: 23-04-2004
$BL['be_ctype_bid'] = 'Angebot';
$BL['be_cnt_bid_bidtext'] = 'Angebotstext';
$BL['be_cnt_bid_sendtext'] = 'Nach Senden';
$BL['be_cnt_bid_verifyemail'] = 'Verify-Email';
$BL['be_cnt_bid_verifiedtext'] = 'Verify-Text';
$BL['be_cnt_bid_errortext'] = 'Gel&ouml;scht';
$BL['be_cnt_bid_startbid'] = 'Startgebot';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd'] = 'erh&ouml;hen&nbsp;um';

// added: 10-05-2004
$BL['be_ctype_pages'] = 'Ext. Content';
$BL['be_cnt_pages_select'] = 'Datei w&auml;hlen';
$BL['be_cnt_pages_fromfile'] = 'Datei aus der Struktur';
$BL['be_cnt_pages_manually'] = 'Eigene Pfad/Datei oder URL';
$BL['be_cnt_pages_cust'] = 'Datei/URL';
$BL['be_cnt_pages_from'] = 'Herkunft';

// added: 24-05-2004
$BL['be_ctype_reference'] = 'Bilderwechsel';
$BL['be_cnt_reference_basis'] = 'Anordnung';
$BL['be_cnt_reference_horizontal'] = 'horizontal';
$BL['be_cnt_reference_vertical'] = 'vertikal';
$BL['be_cnt_reference_aligntext'] = 'Kleine Referenzbilder';
$BL['be_cnt_reference_largetext'] = 'Gro&szlig;es Referenzbild';
$BL['be_cnt_reference_zoom'] = 'Vergr&ouml;&szlig;ern';
$BL['be_cnt_reference_middle'] = 'mittig';
$BL['be_cnt_reference_border'] = 'Rand';
$BL['be_cnt_reference_block'] = 'Block BxH';

// added: 31-05-2004
$BL['be_article_rendering'] = 'Anzeige';
$BL['be_article_nosummary'] = 'Schlagtext nicht anzeigen';
$BL['be_article_forlist'] = 'Artikellisting';
$BL['be_article_forfull'] = 'Artikeldetail';

// added: 08-07-2004
$BL["setup_dir_exists"] = '<strong>ACHTUNG!</strong> Das &quot;SETUP&quot; Verzeichnis ist noch immer vorhanden! L&ouml;schen Sie dieses Verzeichnis, sonst haben Sie ein potentielles Sicherheitproblem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned'] = 'Wortsperre';
$BL['be_cnt_guestbook_flooding'] = 'Flooding';
$BL['be_cnt_guestbook_setcookie'] = 'Cookie setzen';
$BL['be_cnt_guestbook_allowed'] = 'erneut erlaubt nach';
$BL['be_cnt_guestbook_seconds'] = 'Sekunden';
$BL['be_alias_ID'] = 'Alias ID';
$BL['be_ftrash_delall'] = "M&ouml;chten Sie wirklich \nALLE DATEIEN im Papierkorb l&ouml;schen?";
$BL['be_ftrash_delallfiles'] = 'Alle Dateien im Papierkorb l&ouml;schen';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers'] = 'CSV Empf&auml;nger importieren';
$BL['be_newsletter_importtitle'] = 'Newsletter Abonnenten importieren';
$BL['be_newsletter_addresses'] = 'Adressen';
$BL['be_newsletter_csverror'] = 'Die importierte CSV Datei scheint inkorrekt zu sein! Pr&uuml;fen Sie das Delimeter-Zeichen!';
$BL['be_newsletter_addressesadded'] = 'Adressen hinzugef&uuml;gt';
$BL['be_newsletter_newimport'] = 'Import';
$BL['be_newsletter_importerror'] = 'Folgende Daten sind fehlerhaft:';
$BL['be_newsletter_shouldbe1'] = 'Die CSV/TXT Datei sollte wie folgt aufgebaut sein:';
$BL['be_newsletter_shouldbe2'] = 'Standard = <b>;</b>';
$BL['be_newsletter_sample'] = 'Beispiel';
$BL['be_newsletter_selectCSV'] = 'CSV Datei w&auml;hlen';
$BL['be_newsletter_delimeter'] = 'Delimeter';
$BL['be_newsletter_importCSV'] = 'CSV Datei importieren';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle'] = 'Anordnung zugeordneter Artikel';
$BL['be_admin_struct_orderdate'] = 'Erstellungsdatum';
$BL['be_admin_struct_orderchangedate'] = '&Auml;nderungsdatum';
$BL['be_admin_struct_orderstartdate'] = 'Startdatum';
$BL['be_admin_struct_orderdesc'] = 'absteigend';
$BL['be_admin_struct_orderasc'] = 'aufsteigend';
$BL['be_admin_struct_ordermanual'] = 'Manuell (Pfeil auf/ab)';
$BL['be_cnt_sitemap_startid'] = 'Startet ab';

// added: 20-10-2004
$BL['be_ctype_map'] = 'Landkarte';
$BL['be_save_btn'] = 'Speichern';
$BL['be_cmap_location_error_notitle'] = 'Titel f&uuml;r den Ort angeben.';
$BL['be_cnt_map_add'] = 'Neue Lage';
$BL['be_cnt_map_edit'] = 'Lage bearb.';
$BL['be_cnt_map_title'] = 'Lage-Titel';
$BL['be_cnt_map_info'] = 'Eintrag/Info';
$BL['be_cnt_map_list'] = 'Lage-Liste';
$BL['be_btn_delete'] = 'Soll folgende Lage gel&ouml;scht werden?';

// added: 05-11-2004
$BL['be_ctype_phpvar'] = 'PHP Variablen';
$BL['be_cnt_vars'] = 'Variablen';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy'] = 'Artikel kopieren';
$BL['be_func_struct_nocopy'] = 'Artikel kopieren beenden';
$BL['be_func_struct_copy_level'] = 'Strukturebene kopieren';
$BL['be_func_struct_no_copy'] = 'Es ist nicht m&ouml;glich, die Wurzelebene (Root) zu kopieren!';

// added: 27-11-2004
$BL['be_date_minute'] = 'Minute';
$BL['be_date_minutes'] = 'Minuten';
$BL['be_date_hour'] = 'Stunde';
$BL['be_date_hours'] = 'Stunden';
$BL['be_date_day'] = 'Tag';
$BL['be_date_days'] = 'Tage';
$BL['be_date_week'] = 'Woche';
$BL['be_date_weeks'] = 'Wochen';
$BL['be_date_month'] = 'Monat';
$BL['be_date_months'] = 'Monate';
$BL['be_cache'] = 'Cache';
$BL['be_cache_timeout'] = 'Verfallszeit';

// added: 13-12-2004
$BL['be_subnav_admin_groups'] = 'Benutzergruppen';
$BL['be_admin_group_add'] = 'Neue Benutzergruppe';
$BL['be_admin_group_nogroup'] = 'Es konnte keine Benutzergruppe gefunden werden';

// added: 20-12-2004
$BL['be_ctype_forum'] = 'Forum';
$BL['be_subnav_msg_forum'] = 'Forenliste';
$BL['be_forum_title'] = 'Forumstitel';
$BL['be_forum_permission'] = 'Berechtigungen';
$BL['be_forum_add'] = 'Neues Forum';
$BL['be_forum_titleedit'] = 'Forum bearbeiten';

// added: 15-01-2005
$BL['be_admin_page_customblocks'] = 'Eigene';
$BL['be_show_content'] = 'Ausgabe';
$BL['be_main_content'] = 'Hauptspalte';
$BL['be_admin_template_jswarning'] = 'Achtung!!! \nEs kann zu Änderungen der \nnutzerdefinierten Blöcke kommen! \n\nWenn Sie abbrechen, setzen Sie \nden Wert für das Seitenlayout zurück! \n\nWirklich Vorlage ändern?\n\n';

$BL['be_ctype_rssfeed'] = 'RSS Feed';
$BL['be_cnt_rssfeed_url'] = 'RSS URL';
$BL['be_cnt_rssfeed_item'] = 'Eintr&auml;ge';
$BL['be_cnt_rssfeed_max'] = 'max.';
$BL['be_cnt_rssfeed_cut'] = '1. Eintrag nicht anzeigen';

$BL['be_ctype_simpleform'] = 'Formular';

$BL['be_cnt_onsuccess'] = 'bei Erfolg';
$BL['be_cnt_onerror'] = 'bei Fehler';
$BL['be_cnt_onsuccess_redirect'] = 'Weiterleitung wenn erfolgreich';
$BL['be_cnt_onerror_redirect'] = 'Weiterleitung wenn Fehler auftritt';

$BL['be_cnt_form_class'] = 'Formular CSS Class';
$BL['be_cnt_label_wrap'] = 'Label wrap';
$BL['be_cnt_error_class'] = 'Fehler CSS Class';
$BL['be_cnt_req_mark'] = 'Pflichtzeichen';
$BL['be_cnt_mark_as_req'] = 'Als zwingend erforderlich kennzeichnen';
$BL['be_cnt_mark_as_del'] = 'Eintrag zum L&ouml;schen vormerken';

$BL['be_cnt_type'] = 'Typ';
$BL['be_cnt_label'] = 'Label';
$BL['be_cnt_needed'] = 'Zwingend';
$BL['be_cnt_delete'] = 'L&ouml;schen';
$BL['be_cnt_value'] = 'Wert';
$BL['be_cnt_error_text'] = 'Fehlertext';
$BL['be_cnt_css_style'] = 'CSS Stil';
$BL['be_cnt_css_class'] = 'CSS Klasse';
$BL['be_cnt_send_copy_to'] = 'Kopie an';

$BL['be_cnt_field'] = array(
    "text" => 'Text (einzeilig)',
    "email" => 'E-Mail',
    "textarea" => 'Text (mehrzeilig)',
    "hidden" => 'Versteckt',
    "password" => 'Passwort',
    "select" => 'Ausklappmen&uuml;',
    "list" => 'Liste',
    "checkbox" => 'Checkbox',
    "checkboxcopy" => 'Checkbox (E-Mail Kopie an/aus)',
    "radio" => 'Optionsschalter',
    "upload" => 'Datei',
    "submit" => 'Sende-Taste',
    "reset" => 'Zur&uuml;cksetzen',
    "break" => 'Trenner',
    "breaktext" => 'Zwischentext',
    "special" => 'Text (spezial)',
    "captchaimg" => 'Captcha Bild',
    "captcha" => 'Captcha Code',
    'newsletter' => 'Newsletter',
    'selectemail' => 'E-Mail Ausklappmen&uuml;',
    'country' => 'L&auml;nder-Ausklappmen&uuml;',
    'mathspam' => 'Mathe Spam Schutz',
    'summing' => 'Summieren',
    'subtract' => 'Subtrahieren',
    'divide' => 'Dividieren',
    'multiply' => 'Multiplizieren',
    'calculation' => 'Berechnung:',
    'formtracking_off' => 'Formular-Tracking ausschalten',
    'checktofrom' => 'Empf&auml;nger und Absender E-mail m&uuml;ssen verschieden sein',
    'recaptcha' => 'reCAPTCHA',
    'recaptcha_signapikey' => 'Registrierung eines reCAPTCHA API-Schl&uuml;ssels',
    'recaptchainv' => 'Unsichtbares reCAPTCHA',
);

$BL['be_cnt_optin'] = 'Double Opt-In';
$BL['be_cnt_doubleoptin'] = 'aktiviere Double Opt-In gem&auml;&szlig; der <a href="https://de.wikipedia.org/wiki/Datenschutz-Grundverordnung" target="_blank">Datenschutzgrundverordnung</a> (DSGVO)';

$BL['be_cnt_access'] = 'Zugriff';
$BL['be_cnt_activated'] = 'aktiviert';
$BL['be_cnt_available'] = 'verf&uuml;gbar';
$BL['be_cnt_guests'] = 'G&auml;ste';
$BL['be_cnt_admin'] = 'Admin';
$BL['be_cnt_write'] = 'Schreiben';
$BL['be_cnt_read'] = 'Lesen';

$BL['be_cnt_no_wysiwyg_editor'] = 'WYSIWYG Editor ausschalten';
$BL['be_cnt_cache_update'] = 'Cache zur&uuml;cksetzen';
$BL['be_cnt_cache_delete'] = 'Cache l&ouml;schen';
$BL['be_cnt_cache_delete_msg'] = 'Soll der Cache wirklich gel&ouml;scht werden?  \nDies kann die Suche beeinflussen.  \n';

$BL['be_admin_usr_issection'] = 'Login-Bereich';
$BL['be_admin_usr_ifsection0'] = 'Frontend';
$BL['be_admin_usr_ifsection1'] = 'Backend';
$BL['be_admin_usr_ifsection2'] = 'Frontend und Backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit'] = 'Diesen Content Part bearbeiten';
$BL['be_func_content_paste0'] = 'Diesem Artikel hinzuf&uuml;gen';
$BL['be_func_content_paste'] = 'Nach dem letzten Content Part einf&uuml;gen';
$BL['be_func_content_cut'] = 'Diesen Content Part ausschneiden';
$BL['be_func_content_no_cut'] = "Es ist nicht m&ouml;glich, diesen Content Part auszuschneiden!";
$BL['be_func_content_copy'] = 'Diesen Content Part kopieren';
$BL['be_func_content_no_copy'] = "Es ist nicht m&ouml;glich, diesen Content Part zu kopieren!";
$BL['be_func_content_paste_cancel'] = 'Content Part &Auml;nderung abbrechen';

$BL['be_cnt_move_deleted'] = 'Dateien final l&ouml;schen';
$BL['be_cnt_move_deleted_msg'] = 'Sollen wirklich alle Dateien,  \ndie als gel&ouml;scht markiert sind in den  \nL&ouml;schordner verschoben werden?  \n';

$BL['be_admin_struct_permit'] = 'Zugriffsberechtigt (leer f&uuml;r Jeden)';
$BL['be_admin_struct_adduser_all'] = 'Alle Benutzer &uuml;bernehmen';
$BL['be_admin_struct_adduser_this'] = 'Ausgew&auml;hlten Benutzer &uuml;bernehmen';
$BL['be_admin_struct_remove_all'] = 'Alle Benutzer entfernen';
$BL['be_admin_struct_remove_this'] = 'Ausgew&auml;hlten Benutzer entfernen';

$BL['be_ctype_alias'] = 'Contentpart Alias';
$BL['be_cnt_setting'] = '&Uuml;bernahme';
$BL['be_cnt_spaces'] = 'Abst&auml;nde des Alias Contentparts';
$BL['be_cnt_toplink'] = 'Top-Link Einstellung des Alias Contentparts';
$BL['be_cnt_block'] = 'Ausgabe-Einstellung des Alias Contentparts';
$BL['be_cnt_title'] = '&Uuml;berschriften des Alias Contentparts';
$BL['be_cnt_status'] = 'Sichtbarkeit des Alias Contentparts';
$BL['be_cnt_plugin_n.a.'] = 'Plugin nicht verf&uuml;gbar';

$BL['be_file_replace'] = 'Ersetze gleichnamige Dateien';
$BL['be_admin_tmpl_copy'] = 'Vorlage kopieren';
$BL['be_alias_articleID'] = 'Alias ID';
$BL['be_alias_useAll'] = 'Aktuelle Artikelkopfdaten benutzen';
$BL['be_article_morelink'] = '[Weiter&#8230;] Link';

$BL['be_ctype_filelist1'] = 'Dateiliste Pro';

$BL['be_admin_keywords'] = 'Keywords';
$BL['be_admin_keywords_key'] = 'KEYWORD';
$BL['be_admin_keywords_err'] = 'Kein eindeutiger Namer f&uuml;r das KEYWORD eintragen';
$BL['be_admin_keyword_edit'] = 'KEYWORD editieren';
$BL['be_admin_keyword_del'] = 'KEYWORD l&ouml;schen';
$BL['be_admin_keyword_delmsg'] = 'Soll das KEYWORD wirklich \ngel&ouml;scht werden?';
$BL['be_admin_keyword_add'] = 'Neues KEYWORD';

$BL['be_cnt_transparent'] = 'Flash transparent';

// added: 02-04-2006
$BL['be_admin_struct_orderkilldate'] = 'Enddatum';
$BL['be_func_switch_contentpart'] = 'Soll der Content Part wirklich ge&auml;ndert werden? \n\nBitte sind Sie &auml;u&szlig;erst vorsichtig damit! \nWichtige Einstellungen k&ouml;nnten &uuml;berschrieben werden! \n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>ACHTUNG!</strong> Das &quot;CODE-SNIPPETS&quot; Verzeichnis ist noch immer vorhanden! L&ouml;schen Sie das Verzeichnis <strong>&quot;phpwcms_code_snippets&quot;</strong>, sonst haben Sie ein potentielles Sicherheitproblem.';
$BL['gd_not_loaded'] = '<strong>Keine GD-Funktionalit&auml;t vorhanden!</strong> Bitte pr&uuml;fen Sie, dass die PHP GD-Erweiterung aktiviert ist, da sonst das Verarbeiten von Bildern nicht zuverl&auml;ssig funktioniert.';

$BL['be_ctype_poll'] = 'Poll';
$BL['be_cnt_pos8'] = 'Tabelle, links';
$BL['be_cnt_pos9'] = 'Tabelle, rechts';
$BL['be_cnt_pos8i'] = 'Bild in Tabelle links anordnen';
$BL['be_cnt_pos9i'] = 'Bild in Tabelle rechts anordnen';

$BL['be_WYSIWYG'] = 'WYSIWYG Editor';
$BL['be_WYSIWYG_disabled'] = 'WYSIWYG Editor ausgeschaltet';

$BL['be_admin_struct_acat_hiddenactive'] = 'sichtbar, wenn aktiv';

$BL['be_login_jsinfo'] = 'JavaScript wird im Backend ben&ouml;tigt!';

$BL['be_admin_struct_maxlist'] = 'max. Artikel im Listenmodus';
$BL['be_admin_optgroup_label'] = array(1 => 'Text', 2 => 'Bild', 3 => 'Formular', 4 => 'Admin', 5 => 'Spezial');
$BL['be_cnt_articlemenu_maxchar'] = 'max. Zeichen';

$BL['be_cnt_sysadmin_system'] = 'System';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date'] = 'Diese Installation ist auf dem neuesten Stand; es sind keine Updates f&uuml;r diese Version von phpwcms verf&uuml;gbar.';
$BL['Version_not_up_to_date'] = 'Diese Installation ist wahrscheinlich <b>nicht</b> auf dem neuesten Stand. Es sind Updates f&uuml;r diese Version von phpwcms verf&uuml;gbar, bitte <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a> besuchen, um die aktuellste Version zu erhalten.';
$BL['Latest_version_info'] = 'Die neueste offizielle Version ist <b>phpwcms %s</b>.';
$BL['Current_version_info'] = 'Aktuell verwendete Version <b>phpwcms %s</b>.';
$BL['Connect_socket_error'] = 'Die Verbindung zum phpwcms-Server konnte nicht aufgebaut werden. Es trat folgender Fehler auf:<br />%s';
$BL['Socket_functions_disabled'] = 'Die Socket-Funktionen konnten nicht benutzt werden.';
$BL['Mailing_list_subscribe_reminder'] = 'Um immer die neuesten Informationen zu Updates von phpwcms zu erhalten, wird empfohlen, sich bei der <a href="http://eepurl.com/bm-BrH" target="_blank">phpwcms Mailingliste</a> anzumelden.';
$BL['Version_information'] = 'phpwcms Versionsinformation';

$BL['be_cnt_search_highlight'] = 'Highlight';
$BL['be_cnt_results_wordlimit'] = 'max. Anzahl Worte (Zusammenfassung)';
$BL['be_cnt_page_of_pages'] = 'Suchnavi';
$BL['be_cnt_page_of_pages_descr'] = '{PREV:Zur&uuml;ck}, Seite #/##, Ergebnis ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Vorw&auml;rts}';
$BL['be_cnt_search_show_top'] = 'oben';
$BL['be_cnt_search_show_bottom'] = 'unten';
$BL['be_cnt_search_show_next'] = 'vorw&auml;rts (auch ohne Link)';
$BL['be_cnt_search_show_prev'] = 'zur&uuml;ck (auch ohne Link)';
$BL['be_cnt_search_show_forall'] = 'immer zeigen';
$BL['be_cnt_search_startlevel'] = 'Suche ab';
$BL['be_cnt_search_searchnot'] = 'Suche nicht';
$BL['be_cnt_results_minchar'] = 'Minimale Anzahl Zeichen der Sucheingabe';
$BL['be_cnt_search_hidesummary'] = 'Such-Teasertext ausblenden';

$BL['be_cnt_pagination'] = 'Content Parts paginieren';
$BL['be_article_pagination'] = 'Artikel paginieren';
$BL['be_article_per_page'] = 'Artikel je Seite';
$BL['be_pagination'] = 'Paginierung';

$BL['be_ctype_recipe'] = 'Kochrezept';
$BL['be_ctype_faq'] = 'FAQ';
$BL['be_cnt_additional'] = 'Zusatz';
$BL['be_cnt_question'] = 'Frage';
$BL['be_cnt_answer'] = 'Antwort';
$BL['be_cnt_same_as_summary'] = 'Daten des Artikelbildes nutzen';
$BL['be_cnt_sorting'] = 'Sortierung';
$BL['be_cnt_imgupload'] = 'Bildupload';
$BL['be_cnt_filesize'] = 'Dateigr&ouml;&szlig;e';
$BL['be_cnt_captchalength'] = 'L&auml;nge des Captcha Codes';
$BL['be_cnt_chars'] = 'Zeichen';
$BL['be_cnt_download'] = 'Download';
$BL['be_cnt_download_direct'] = 'direkt (nicht empfohlen!)';
$BL['be_cnt_database'] = 'Datenbank';
$BL['be_cnt_formsave_in_db'] = 'Formularergebnis speichern';

$BL['be_cnt_email_notify'] = 'Mitteilung';
$BL['be_cnt_notify_by_email'] = 'per E-Mail an';
$BL['be_cnt_last_edited'] = 'zuletzt ge&auml;ndert';
$BL['be_cnt_export_selection'] = 'Auswahl exportieren';
$BL['be_cnt_delete_duplicates'] = 'Duplikate l&ouml;schen';
$BL['be_cnt_new_recipient'] = 'Neuer Empf&auml;nger';

$BL['be_cnt_newsletter_prepare'] = 'Newsletter aktiv schalten!';
$BL['be_cnt_queued'] = 'wartend';
$BL['be_cnt_newsletter_prepare1'] = 'Alle Empf&auml;nger werden in die Sende-Warteschlange &uuml;bernommen';
$BL['be_cnt_newsletter_prepare2'] = 'Die Sende-Warteschlange wird aktualisiert&#8230;';

$BL['be_cnt_export'] = 'Export';

$BL['be_cnt_formsave_profile'] = 'Benutzerdaten speichern';
$BL['be_profile_label_add'] = 'Zusatz';
$BL['be_profile_label_website'] = 'URL';
$BL['be_profile_label_gender'] = 'Geschlecht';
$BL['be_profile_label_birthday'] = 'Geburtstag';

$BL['be_cnt_store_in'] = 'Sichern im Feld';
$BL['be_aboutlink_title'] = 'Informationen zu phpwcms sowie den Lizenzbedingungen';

$BL['be_shortdate'] = 'd.m.y';
$BL['be_shortdatetime'] = 'd.m.y H:i';
$BL['be_longdatetime'] = 'd.m.Y H:i:s';

$BL['be_confirm_sending'] = 'Best&auml;tigen';
$BL['be_confirm_text'] = 'Ja, Newsletter an alle Empf&auml;nger versenden!';

$BL['be_last_sending'] = 'zuletzt gesendet';
$BL['be_last_edited'] = 'zuletzt bearbeitet';
$BL['be_total'] = 'gesamt';

$BL['be_settings'] = 'Einstellungen';
$BL['be_ctype'] = 'Contentpart';
$BL['be_selection'] = 'Auswahl';

$BL['be_ctype_module'] = 'Plug-in';
$BL['be_cnt_lightbox'] = 'Galeriebild';
$BL['be_cnt_behavior'] = 'Verhalten';
$BL['be_cnt_imglist_nocaption'] = 'Vorschaubilder ohne Bildunterzeile';

$BL['be_ctype_felogin'] = 'Frontend Login';
$BL['be_cookie_runtime'] = 'Cookie g&uuml;ltig';
$BL['be_locale'] = 'Gebietsschema';
$BL['be_date_format'] = 'Datumsformat';

$BL['be_check_login_against'] = 'Login pr&uuml;fen gegen';
$BL['be_userprofile_db'] = 'Benutzerprofil-Datenbank';
$BL['be_backenduser_db'] = 'Backendnutzer-Datenbank';
$BL['be_check_login_allow_email'] = 'E-Mail als Login akzeptieren';

$BL['be_gb_post_login'] = 'Posten nur f&uuml;r angemeldete Benutzer';
$BL['be_gb_show_login'] = 'Anzeigen nur f&uuml;r angemeldete Benutzer';
$BL['be_gb_urlcheck'] = 'Remote URL Pr&uuml;fung aktivieren';
$BL['be_order'] = 'Reihenfolge';
$BL['be_unique_teaser_entry'] = 'Zeige Teaser/Artikellink nur einmal je Seite';
$BL['be_check_against_category_alias'] = 'Einzelartikel innerhalb einer Strukturebene mit der Strukturebene verlinken';

$BL['be_allowed_tags'] = 'Erlaubte Tags';
$BL['be_fe_login_url'] = 'FE LoginURL';
$BL['be_ctype_imagesdiv'] = 'Bilder &lt;div&gt;';
$BL['be_cnt_imagecenter'] = 'horizontal/vertikal zentrieren';
$BL['be_cnt_imagenocenter'] = 'nicht zentrieren';
$BL['be_cnt_imagecenterh'] = 'horizontal zentrieren';
$BL['be_cnt_imagecenterv'] = 'vertikal zentrieren';

$BL['be_overwrite_default'] = '&Uuml;berschreibt Standardeinstellungen der Konfigurationsdatei';
$BL['be_cnt_sortvalue'] = 'Sort.Wert';
$BL['be_dialog_warn_nosave'] = 'Wenn Sie fortsetzen, werden &Auml;nderungen nicht gespeichert!\nM&ouml;chten Sie den Vorgang fortsetzen?';
$BL['be_cnt_paginate_subsection'] = 'Abschnitt';
$BL['be_cnt_subsection_tite'] = 'Abschnittstitel';
$BL['be_cnt_subsection_warning'] = 'Die Nummerierung von Abschnitten (Content Part Paginierung)\nist nur f&uuml;r die Ausgabe in\nder Hauptspalte (CONTENT) m&ouml;glich!';

$BL['be_no_search'] = 'keine Suche';
$BL['be_priorize'] = 'Priorisierung';
$BL['be_change_articleID'] = 'Artikel-ID &auml;ndern';
$BL['be_title_wrap'] = 'Artikeltitel umschlie&szlig;en';

$BL['be_no_rss'] = 'RSS';
$BL['be_article_urlalias'] = 'Artikelalias';

$BL['be_image_crop'] = 'Vorschau auf Gr&ouml;&szlig;e schneiden';
$BL['be_image_cropit'] = 'Bild auf Gr&ouml;&szlig;e schneiden';
$BL['be_image_align'] = 'Bildausrichtung';

$BL['be_ctype_flashplayer'] = 'HTML5/Flash Media-Player';
$BL['be_flashplayer_caption'] = 'Beschreibung';
$BL['be_flashplayer_thumbnail'] = 'Vorschau';
$BL['be_flashplayer_selectsize'] = 'Playergr&ouml;&szlig;e w&auml;hlen';
$BL['be_flash_media'] = 'Flash';
$BL['be_html5_media'] = 'HTML5';
$BL['be_html5_h264'] = 'MPEG/H.264';
$BL['be_html5_webm'] = 'WebM';
$BL['be_html5_ogg'] = 'Ogg';
$BL['be_media_format'] = 'Format';
$BL['be_media_watermark'] = 'Wasserzeichen';
$BL['be_skin'] = 'Aussehen/Skin';
$BL['be_foreground_color'] = 'Vordergrundfarbe';
$BL['be_background_color'] = 'Hintergrundfarbe';
$BL['be_highlight_color'] = 'Hervorhebungsfarbe';

$BL['be_check_feuser_profile'] = 'Frontend Benutzerprofil';
$BL['be_check_feuser_registration'] = 'Registrierung';
$BL['be_check_feuser_manage'] = 'verwaltet vom Benutzer';
$BL['be_hide_active_articlelink'] = 'aktiven Artikel im Artikelmen&uuml; ausblenden';

$BL['be_module_search'] = 'Suche auch';

$BL['be_ctype_imagesspecial'] = 'Bilder spezial';
$BL['be_image_WxHpx'] = 'B x H px';
$BL['be_fx_1'] = 'Effekt 1';
$BL['be_fx_2'] = 'Effekt 2';
$BL['be_fx_3'] = 'Effekt 3';
$BL['be_image_zoom'] = 'Gro&szlig;ansicht';
$BL['be_image_delete_js'] = 'Soll der Bildeintrag wirklich entfernt werden?';

$BL['be_news'] = 'News';
$BL['be_news_create'] = 'Neue News erstellen';
$BL['be_tags'] = 'Tag/Schlagwort';
$BL['be_title'] = 'Bezeichnung';
$BL['be_delete_dataset'] = 'Gew&auml;hlten Eintrag l&ouml;schen?';
$BL['be_action_notvalid'] = 'Die von Ihnen gew&auml;hlte Aktion ist nicht zul&auml;ssig!';
$BL['be_action_deleted'] = 'Der von Ihnen gew&auml;hlte Datensatz mit der ID {ID} wurde gel&ouml;scht.';
$BL['be_action_status'] = 'Der Status des zuletzt gew&auml;hlten Datensatzes mit der ID {ID} wurde ge&auml;ndert.';
$BL['be_data_select_failed'] = 'Auf die gew&auml;hlten Daten konnte nicht erfolgreich zugegriffen werden.';
$BL['be_alias'] = 'Alias';
$BL['be_url_value'] = 'URL-Bezeichnung';
$BL['default_date_format'] = 'TT.MM.JJJJ';
$BL['default_date_delimiter'] = '.';
$BL['default_time_format'] = 'HH:MM';
$BL['default_date'] = 'd.m.Y';
$BL['default_time'] = 'H:i';
$BL['be_place'] = 'Ort';
$BL['be_teasertext'] = 'Teasertext';
$BL['be_published'] = 'ver&ouml;ffentlichen';
$BL['be_show_archived'] = 'verf&uuml;gbar nach Enddatum (archivieren)';
$BL['be_save_copy'] = 'Eintrag als Duplikat speichern';
$BL['be_read_more_link'] = 'Mehr URL/ID';
$BL['be_news_name_mandatory'] = 'Bitte einen News-Titel eingeben. Dieser ist unbedingt erforderlich!';
$BL['be_successfully_saved'] = 'Die Daten wurden erfolgreich gespeichert!';
$BL['be_successfully_updated'] = 'Die Daten wurden erfolgreich aktualisiert!';
$BL['be_error_while_save'] = 'Das Speichern der Daten ist fehlgeschlagen!';
$BL['be_copyright'] = 'Copyright';
$BL['be_file_multiple_upload'] = 'Mehrfach-Dateiupload';
$BL['be_files_browse'] = 'Dateien ausw&auml;hlen';
$BL['be_files_upload'] = 'Gew&auml;hlte Dateien hochladen';
$BL['be_files_select_available'] = 'Bereits hochgeladene Dateien ausw&auml;hlen';
$BL['be_archive'] = 'Archiv';
$BL['be_off'] = 'aus';
$BL['be_on'] = 'an';
$BL['be_random'] = 'zuf&auml;llig';
$BL['be_sorted'] = 'sortiert';
$BL['be_granted_download'] = 'gesch&uuml;tzter Download im Frontend';
$BL['be_granted_feuser'] = 'Nur sichtbar f&uuml;r angemeldete Frontend Benutzer';
$BL['be_hidden_for_feuser'] = 'Ausblenden f&uuml;r angemeldete Frontend Benutzer';
$BL['be_visible_for_everybody'] = 'F&uuml;r jeden sichtbar (Standard)';
$BL['be_fileuploader_typeError'] = "{file} hat eine nicht zulässige Erweiterung. Zulässig: {extensions}";
$BL['be_fileuploader_sizeError'] = "{file} ist zu groß, Dateigröße maximal {sizeLimit}.";
$BL['be_fileuploader_minSizeError'] = "{file} ist zu klein, Dateigröße mindestens {minSizeLimit}.";
$BL['be_fileuploader_emptyError'] = "{file} ist leer. Diese Datei bitte auslassen.";
$BL['be_fileuploader_noFilesError'] = "Keine Dateien zum Hochladen.";
$BL['be_fileuploader_onLeave'] = "Das Hochladen l&auml;uft gerade. Wenn Sie jetzt beenden, wird das Hochladen abgebrochen.";
$BL['be_fileuploader_dragText'] = "Dateien zum Upload hierher ziehen!";
$BL['be_fileuploader_uploadButtonText'] = 'Dateien w&auml;hlen oder hier ablegen';
$BL['be_delete_selected_files'] = 'Markierte Dateien l&ouml;schen';
$BL['be_delete_selected_files_confirm'] = 'Sollen wirklich alle markierten Dateien gel&ouml;scht werden?';

$BL['be_ctype_tabs'] = 'Register (Tabs)';
$BL['be_tab_add'] = 'Register hinzuf&uuml;gen';
$BL['be_tab_name'] = 'Register';
$BL['be_headline'] = '&Uuml;berschrift';
$BL['be_tab_delete_js'] = 'Soll das Registerelement wirklich entfernt werden?';

$BL['be_pagniate_count'] = 'Eintr&auml;ge pro Seite';
$BL['be_limit_to'] = 'limitieren auf';
$BL['be_archived_items'] = 'Archiveintr&auml;ge';
$BL['be_include'] = 'einbeziehen';
$BL['be_exclude'] = 'ausschlie&szlig;en';
$BL['be_solely'] = 'ausschlie&szlig;lich';
$BL['be_fsearch_not'] = 'NICHT';
$BL['be_date_year'] = 'Jahr';
$BL['be_archive_link'] = 'Archivlink';
$BL['be_use_prio'] = 'Priorisierung anwenden';
$BL['be_skip_first_items'] = 'Eintr&auml;ge am Anfang &uuml;berspringen';
$BL['be_news_detail_link'] = 'Newsartikel';

$BL['be_gallerydownload'] = 'Galeriedownload gestatten';
$BL['be_gallery_root'] = 'Galerie Wurzelverzeichnis';
$BL['be_gallery_directory'] = 'Galerie Unterverzeichnis';
$BL['be_gallery'] = 'Galerie';

$BL['be_sort_date'] = 'Sortierdatum';

$BL['group_superuser'] = 'Superuser';
$BL['group_admin'] = 'Administrator';
$BL['group_editor'] = 'Redakteur';
$BL['group_newsletter'] = 'Newsletter-Redakteur';
$BL['group_client'] = 'Kunde';
$BL['group_guest'] = 'Besucher';

$BL['php_function'] = 'PHP-Funktion';
$BL['article_menu_title'] = 'Men&uuml;titel';

$BL['content_type'] = 'Content-Type';
$BL['automatic'] = 'automatisch';

$BL['random_image'] = 'Bilder zuf&auml;llig w&auml;hlen';
$BL['limit_image_from_list'] = 'Bilder max.';

$BL['alt_image'] = 'Alt. Bild';
$BL['alt_text'] = 'Alt. Text';
$BL['over'] = 'dar&uuml;ber';
$BL['js_lib'] = 'JS Bibliothek';
$BL['js_lib_alwaysload'] = 'immer laden';
$BL['frontendjs_load'] = 'frontend.js laden (mehr aus historischen Gr&uuml;nden)';
$BL['googleapi_load'] = 'CDN benutzen';
$BL['fancyupload_clear_list'] = 'Dateiliste leeren';
$BL['fancyupload_file_uploaded'] = 'Datei wurde hochgeladen';
$BL['fancyupload_file_error'] = 'Fehler beim Hochladen';
$BL['fancyupload_adblock_error'] = 'Erlauben Sie die Nutzung von Flash im Browser und laden neu (siehe Adblock), um den integrierten Uploader zu aktivieren.';
$BL['fancyupload_flashblock_error'] = 'Aktivieren Sie die blockierte Flash-Datei (siehe Flashblock), um den integrierten Uploader nutzen zu k&ouml;nnen.';
$BL['fancyupload_required_error'] = 'Eine erforderliche Datei wurde nicht gefunden. Bitte gedulden Sie sich. Wir &ouml;sen das Problem.';
$BL['fancyupload_flash_error'] = 'Installieren Sie das neueste Adobe Flash Plugin, um den integrierten Uploader zu aktivieren.';

$BL['be_cnt_function_validate'] = 'PHP-Validierung';
$BL['be_structform_selected_cp'] = 'Auswahl nutzbarer Content Parts begrenzen';
$BL['be_structform_select_cp'] = 'Content Parts w&auml;hlen';

$BL['source_image_not_found'] = 'Quelldatei Fehler: Die Datei %s konnte nicht gefunden werden.';
$BL['form_force_ssl'] = 'Formularversand per SSL erzwingen';
$BL['numerize_title'] = 'Nummerieren anstatt Artikeltitel';
$BL['be_article_noteaser'] = 'kein Teaser';
$BL['be_acat_disable301'] = 'Artikel 301 Weiterleitung';

$BL['file_actions_step1'] = "Schritt 1: Verzeichnis ausw&auml;hlen";
$BL['file_actions_step2'] = "Schritt 2: Dateien ausw&auml;hlen";
$BL['file_actions_step3'] = "Schritt 3: Gew&uuml;nschte Aktion ausw&auml;hlen";
$BL['file_actions_button'] = 'Aktion ausf&uuml;hren';
$BL['file_actions_no'] = 'Keine Dateien zum Bearbeiten. Bitte anderen Ordner ausw&auml;hlen';
$BL['file_actions_delete'] = 'Sind sie sicher, dass die gew&auml;hlten Dateien gel&ouml;schen werden sollen?';
$BL['file_actions_bemuser'] = 'Die ausgew&auml;hlten Dateien werden dem neuen Benutzer zugeordnet und in dessen Wurzelverzeichnis verschoben.';
$BL['file_actions_bemfolder'] = 'Bitte w&auml;hlen sie den Zielordner. Die ausgew&auml;hlten Dateien werden in diesen Order verschoben.';
$BL['file_actions_pdl_empty'] = 'Aktion w&auml;hlen';
$BL['file_actions_pdl_delete'] = 'Dateien l&ouml;schen';
$BL['file_actions_pdl_move'] = 'Dateien verschieben';
$BL['file_actions_pdl_status'] = 'Status &auml;ndern';
$BL['file_actions_pdl_user'] = 'Inhaber &auml;ndern';
$BL['file_actions_msg_move'] = 'Dateien wurden erfolgreich verschoben';
$BL['file_actions_msg_delete'] = 'Dateien wurden erfolgreich gel&ouml;scht';
$BL['file_actions_msg_status'] = 'Der Status der Dateien wurde erfolgreich ge&auml;ndert';
$BL['file_actions_msg_error'] = 'Es wurden keine Dateien ausgew&auml;hlt';
$BL['file_actions_msg_user'] = 'Dateien wurden erfolgreich dem neuen Benutzer zugeordnet';

$BL['be_imagefiles_as_gallery'] = 'Bildergalerie aus Bilddateien generieren';
$BL['be_link'] = 'Verlinkung';
$BL['be_links'] = 'Verlinkungen';
$BL['be_redirect'] = 'Umleitung';
$BL['be_redirects'] = 'Umleitungen';
$BL['be_views'] = 'Aufrufe';
$BL['be_structure_id'] = 'Struktur-ID';
$BL['be_shortcut'] = 'Shortcut';
$BL['be_target_type'] = 'Typ des Ziels';
$BL['be_http_status'] = 'HTTP Status';
$BL['be_http_status301'] = 'Permanent';
$BL['be_http_status307'] = 'Tempor&auml;r';
$BL['be_http_status404'] = 'Nicht gefunden';
$BL['be_http_status401'] = 'Nicht authorisiert';
$BL['be_http_status503'] = 'Nicht verf&uuml;gbar';
$BL['be_redirect_error1'] = 'Alias/Shortcut, Struktur- oder Artikel-ID m&uuml;ssen angegeben werden';
$BL['be_redirect_error2'] = 'Ziel muss angegeben werden';
$BL['be_redirect_error3'] = 'Zieltyp Artikel-ID und Struktur-ID gestatten nur Ganzzahlen als Ziel';
$BL['be_new_linkredirect'] = 'Neue Verlinkung/Weiterleitung';

$BL['be_ctype_accordion'] = 'Gruppe (Accordion)';
$BL['be_ctype_number'] = 'Nummer';
$BL['be_inactive'] = 'inaktiv';
$BL['be_locked'] = 'gesch&uuml;tzt';
$BL['be_n/a'] = 'n. a.';
$BL['be_opengraph_support'] = 'Social Sharing erlaubt';
$BL['be_player_volume'] = 'Lautst&auml;rke';
$BL['be_player_volume_muted'] = 'stumm';
$BL['be_keyword'] = 'Schl&uuml&sselwort';
$BL['be_tag'] = 'Tag';

$BL['be_system_container'] = 'Systemcontainer';
$BL['be_system_container_norender'] = 'ohne regul&auml;re Ausgabe im Frontend';
$BL['be_custom_scriptlogic'] = 'benutzerdefiniert (Scriptlogik)';
$BL['be_flush_image_cache'] = 'Bildcache leeren';

$BL['be_caption_alt'] = 'alt Attr.';
$BL['be_caption_title'] = 'title Attr.';
$BL['be_caption_file_imagesize'] = 'BxHxC <em>(wenn Bild)</em>';
$BL['be_caption_file_title'] = 'Dateititel';
$BL['be_caption_descr.'] = 'Beschr.';
$BL['be_display_html5_only'] = 'ausschlie&szlig;lich HTML5';
$BL['be_audio_only'] = 'nur Audio';
$BL['be_hide_downloadbutton'] = 'HTML5 Download-Button ausblenden';

$BL['be_filter'] = 'Filter';
$BL['be_filter_with_tags'] = 'nach Schlagwort';
$BL['be_filter_not_selected'] = 'Keine Kategorie ausgew&auml;hlt';
$BL['be_empty_search_result'] = 'Die Suche lieferte ein leeres Ergebnis.';
$BL['confirm_cp_tab_warning'] = 'Der gewählte Abschnitt ist unbenannt und auch keiner Nummer zugeordnet. Beim Speichen bzw. Aktualisieren geht die Auswahl verloren.';

$BL['be_canonical'] = 'Canonical Link';
$BL['be_breadcrumb'] = 'Breadcrumb Anzeigeverhalten';
$BL['be_breadcrumb_nothidden'] = 'sichtbar, wenn Seitenebene versteckt';
$BL['be_breadcrumb_nolink'] = 'nicht verlinken';

$BL['CSRF_POST_INVALID'] = 'Keine POST <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a>-Pr&uuml;fparamter gefunden. Aus Sicherheitsgr&uuml;nden wurde die Session beendet.';
$BL['CSRF_POST_FAILED'] = 'Die POST <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a>-Pr&uuml;fung ist fehlgeschlagen. Aus Sicherheitsgr&uuml;nden wurde die Session beendet.';
$BL['CSRF_GET_INVALID'] = 'Keine GET <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a>-Pr&uuml;fparamter gefunden. Aus Sicherheitsgr&uuml;nden wurde die Session beendet.';
$BL['CSRF_GET_FAILED'] = 'Die GET <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a>-Pr&uuml;fung ist fehlgeschlagen. Aus Sicherheitsgr&uuml;nden wurde die Session beendet.';

$BL['be_parental_alias'] = 'Eltern-Alias';
$BL['be_fsearch_nor'] = 'KEINES';
$BL['be_tab_toggle'] = 'Reiter aus- bzw. einklappen';
$BL['be_custom_textfield'] = 'Freitext';
$BL['be_tab_template_toggle_warning'] = 'Wenn Sie die Vorlage umstellen, kann passieren, dass sich die Freitextfelder ändern und Werte verloren gehen.\n\nMöchten Sie wirklich fortfahren?';

$BL['be_onepage_id'] = 'OnePage ID (#Anker) Unterst&uuml;tzung';
$BL['be_onepage_template'] = 'Als OnePage Vorlage behandeln';
$BL['be_yes'] = 'Ja';
$BL['be_no'] = 'Nein';
$BL['be_attr_title'] = 'Titel (Attribut)';
$BL['be_attr_alt'] = 'Alternativer Text';
$BL['be_ie8ignore'] = '<a href="https://de.wikipedia.org/wiki/Conditional_Comments" target="_blank" class="underline">Conditional Comments</a> f&uuml;r IE8 deaktivieren';
$BL['be_cookie_consent_enable'] = 'Cookie Consent Plugin aktivieren';
$BL['be_cookie_consent_message'] = 'Zustimmungstext';
$BL['be_cookie_consent_translatable'] = 'Diese Installation unterst&uuml;tzt mehrere Sprachen (&#36;phpwcms[&#39;allowed_lang&#39;]). Mittels <b>@@Text@@</b> Syntax k&ouml;nnen Cookie Consent Texte &uuml;bersetzt werden. Nach dem Rendern `template/template_lang` pr&uuml;fen.';
$BL['cookie_consent_message'] = 'Diese Website benutzt Cookies, um eine umfassende Darstellung sowie die Funktionalit&auml;t der Webseite sicherzustellen';
$BL['be_cookie_consent_dismiss'] = 'Best&auml;tigungs-Button';
$BL['cookie_consent_dismiss'] = 'Verstanden!';
$BL['be_cookie_consent_more'] = 'Infolink-Button';
$BL['cookie_consent_more'] = 'Weitere Informationen';
$BL['be_cookie_consent_link'] = 'Cookie-Policy URL/Alias';
$BL['be_cookie_consent_theme'] = 'Vorlage (leer = ohne CSS)';
$BL['be_google_analytics_enable'] = 'Google Analytics benutzen';
$BL['be_google_tag_manager_enable'] = 'Google Tag Manager benutzen';
$BL['be_piwik_enable'] = 'Matomo/Piwik benutzen';
$BL['be_tracking_anonymize'] = 'IP anonymisieren';
$BL['be_tracking_cookie_flags'] = '<a href="https://developers.google.com/analytics/devguides/collection/gtagjs/cookies-user-id?hl=de#cookie_flags" target="_blank"><u>Cookie Flags</u></a> aktivieren (automatisch generiert)';
$BL['be_tracking_custom_properties'] = 'Zus&auml;tzliche <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/?hl=de" target="_blank"><u>Konfigurations-Parameter</u></a> (prop1: val1, prop2, val2)';
$BL['be_tracking_id'] = 'Tracking-ID';
$BL['be_site_id'] = 'Site-ID';
$BL['be_piwik_url'] = 'Matomo/Piwik URL';
$BL['be_filedownload_direct_blocked'] = 'geblockt durch <abbr title="%s">.htaccess</abbr>';
$BL['be_tracking_optout'] = 'Opt-Out-Cookie unterst&uuml;tzen <i>&lt;a href=&quot;javascript:gaOptout()&quot;&gt;&lt;/a&gt;</i>';
$BL['be_require_consent'] = 'Tracking-Code ohne Consent nicht aktivieren';
$BL['be_consent_cookie_name'] = 'Name des Consent-Cookies';
$BL['be_consent_cookie_value'] = 'Wert des Consent-Cookies';
$BL['be_respect_donottrack'] = 'Browser-Einstellung Do-Not-Track respektieren';
$BL['placeholder_require_cookie_name'] = 'cookieconsent_dismissed';
$BL['placeholder_require_cookie_value'] = 'yes';

$BL['be_iptc_data'] = 'IPTC-Angaben';
$BL['be_iptc_as_caption'] = 'f&uuml;r Beschreibung, Copyright etc. nutzen, solange nicht gesetzt';
$BL['iptc_ImageDescription'] = 'Bildbeschreibung';
$BL['iptc_Copyright'] = 'Copyright';
$BL['iptc_Artist'] = 'K&uuml;nstler';
$BL['iptc_Keywords'] = 'Schlagworte';
$BL['iptc_CountryDest'] = 'Land';
$BL['iptc_ProvinceOrStateDest'] = 'Region';
$BL['iptc_CityDest'] = 'Ort';
$BL['iptc_SublocationDest'] = 'Standort';
$BL['iptc_ObjectName'] = 'Objektname';
$BL['iptc_SpecialInstructions'] = 'Spezielle Anweisungen';
$BL['iptc_Headline'] = '&Uuml;berschrift';
$BL['iptc_Credit'] = 'Nennung';
$BL['iptc_Source'] = 'Quelle';
$BL['iptc_EditStatus'] = 'Bearbeitungsstatus';
$BL['iptc_iimCategory'] = 'Kategorie';
$BL['iptc_iimSupplementalCategory'] = 'Erg&auml;zende Kategorie';
$BL['iptc_Urgency'] = 'Dringlichkeit';
$BL['iptc_FixtureIdentifier'] = 'Bezeichner';
$BL['iptc_LocationDestCode'] = 'Positionscode';
$BL['iptc_LocationDest'] = 'Position';
$BL['iptc_Software'] = 'Software';
$BL['iptc_SoftwareVersion'] = 'Softwareversion';
$BL['iptc_ObjectCycle'] = 'Objektzyklus';
$BL['iptc_CountryCodeDest'] = 'L&auml;nderkennung';
$BL['iptc_OriginalTransmissionRef'] = 'Urspr&uuml;ngliche Herkunft';
$BL['iptc_Contact'] = 'Kontakt';
$BL['iptc_Writer'] = 'Autor';
$BL['iptc_LanguageCode'] = 'Sprachkennung';
$BL['iptc_DateTimeOriginal'] = 'Originaldatum';
$BL['iptc_DateTimeDigitized'] = 'Digitalisierungsdatum';
$BL['iptc_DateTimeReleased'] = 'Ver&ouml;ffentlichungsdatum';
$BL['iptc_DateTimeExpires'] = 'Ablaufdatum';
$BL['iptc_IntellectualGenre'] = 'Genre';
$BL['iptc_SubjectNewsCode'] = 'Betreffkennung';
$BL['iptc_iimVersion'] = 'Version';

$BL['be_suppress_render_caption'] = 'Ausgabe der Bildunterschrift unterdr&uuml;cken';
$BL['be_cnt_attribute_class'] = 'CSS Klasse [class]';
$BL['be_cnt_attribute_id'] = 'CSS ID [id]';
$BL['be_cnt_avoid_duplicates'] = 'Nur eindeutige Werte zulassen';
$BL['be_not_set'] = 'nicht gesetzt';
$BL['be_licensed_under_GPL'] = 'Lizenziert unter GPL.';
$BL['be_extensions_copyright'] = 'Erweiterungen sind urheberrechtlich gesch&uuml;tzt.';

$BL['be_password_show'] = 'Passwort anzeigen';
$BL['be_password_hide'] = 'Password verstecken';

$BL['be_admin_template_choose_file'] = 'Textvorlage, alternativ Dateivorlage ausw&auml;hlen';

$BL['be_flashplayer_marker'] = 'Markierung';
$BL['be_marker_time'] = 'Zeit (Sekunden, z.B. 10.5)';
$BL['be_marker_text'] = 'Text';
$BL['be_marker_overlaytext'] = '&Uuml;berlagernder Text';

$BL['copy_to_clipboard'] = 'In die Zwischenablage kopieren';
$BL['url_parameter'] = 'URL-Parameter';
$BL['file_extension'] = 'Erweiterung';
$BL['download_link'] = 'Download-Link';
$BL['disposition_attachment'] = 'Attachment';
$BL['disposition_attachment_description'] = 'direkt laden';
$BL['disposition_inline'] = 'Inline';
$BL['disposition_inline_description'] = 'im Browser anzeigen';
