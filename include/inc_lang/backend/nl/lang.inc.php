<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Language: Dutch, Language Code: nl
// Original translation by http://www.repute.nl and http://www.voskotan.com
// Major revision and final editing by http://www.argosmedia.nl (26-03-2004)
// Updated by F. de Groot 03/2007
// Please use HTML safe strings ONLY, necessary to reduce processing time
// Normal line break: '&#13'
// JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'Gebruikers online:';

// Login Page
$BL["login_text"]                       = 'Vul uw login-gegevens in';
$BL['login_error']                      = 'Fout tijdens het inloggen';
$BL["login_username"]                   = 'Gebruikersnaam';
$BL["login_userpass"]                   = 'Wachtwoord';
$BL["login_button"]                     = 'Inloggen';
$BL["login_lang"]                       = 'Backend-taal';

// cmsgo.php
$BL['be_nav_logout']                    = 'UITLOGGEN';
$BL['be_nav_articles']                  = 'ARTIKELEN';
$BL['be_nav_files']                     = 'BESTANDSBEHEER';
$BL['be_nav_modules']                   = 'MODULES';
$BL['be_nav_messages']                  = 'BERICHTEN';
$BL['be_nav_chat']                      = 'CHATTEN';
$BL['be_nav_profile']                   = 'LOGIN GEGEVENS';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISCUSSIE';

$BL['be_page_title']                    = 'cmsgo backend (beheer)';

$BL['be_subnav_article_center']         = 'Artikelbeheer';
$BL['be_subnav_article_new']            = 'Nieuw artikel';
$BL['be_subnav_file_center']            = 'Bestandsbeheer';
$BL['be_subnav_file_ftptakeover']       = 'Registratie na FTP-upload';
$BL['be_subnav_mod_artists']            = 'Artiest, categorie, genre';
$BL['be_subnav_msg_center']             = 'Berichtbeheer';
$BL['be_subnav_msg_new']                = 'Nieuw bericht';
$BL['be_subnav_msg_newsletter']         = 'Nieuwsbriefabonnementen';
$BL['be_subnav_chat_main']              = 'Chatten (onderling)';
$BL['be_subnav_chat_internal']          = 'Start chat';
$BL['be_subnav_profile_login']          = 'Login-informatie';
$BL['be_subnav_profile_personal']       = 'Persoonlijke gegevens';
$BL['be_subnav_admin_pagelayout']       = 'Layouts';
$BL['be_subnav_admin_templates']        = 'Templates';
$BL['be_subnav_admin_css']              = 'Stylesheet (CSS)';
$BL['be_subnav_admin_sitestructure']    = 'Structuur';
$BL['be_subnav_admin_users']            = 'Gebruikers';
$BL['be_subnav_admin_filecat']          = 'Bestandscategorieen';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'Artikel-ID';
$BL['be_func_struct_preview']           = 'Voorbeeld';
$BL['be_func_struct_edit']              = 'Artikel bewerken';
$BL['be_func_struct_sedit']             = 'Categorie bewerken';
$BL['be_func_struct_cut']               = 'Artikel knippen en ergens anders plakken';
$BL['be_func_struct_nocut']             = 'Artikel knippen annuleren';
$BL['be_func_struct_svisible']          = 'Schakelen zichtbaar/onzichtbaar';
$BL['be_func_struct_spublic']           = 'Schakelen openbaar/niet openbaar';
$BL['be_func_struct_sort_up']           = 'Categorie omhoog verplaatsen';
$BL['be_func_struct_sort_down']         = 'Categorie omlaag verplaatsen';
$BL['be_func_struct_del_article']       = 'Artikel verwijderen';
$BL['be_func_struct_del_jsmsg']         = 'Artikel verwijderen?';
$BL['be_func_struct_new_article']       = 'Artikel aanmaken ';
$BL['be_func_struct_paste_article']     = 'Artikel plakken';
$BL['be_func_struct_insert_level']      = 'Subcategorie invoegen';
$BL['be_func_struct_paste_level']       = 'Categorie plakken';
$BL['be_func_struct_cut_level']         = 'Categorie knippen';
$BL['be_func_struct_no_cut']            = "Het is onmogelijk om de root van de structuur te verwijderen!";
$BL['be_func_struct_no_paste1']         = "Het is onmogelijk om hier naartoe te kopieren!";
$BL['be_func_struct_no_paste2']         = 'is het kind in de top van de structuur';
$BL['be_func_struct_no_paste3']         = 'dat moet hier gekopieerd worden';
$BL['be_func_struct_paste_cancel']      = 'Knippen categorie annuleren';
$BL['be_func_struct_del_struct']        = 'Categorie verwijderen';
$BL['be_func_struct_del_sjsmsg']        = 'Categorie verwijderen?';
$BL['be_func_struct_open']              = 'Openen';
$BL['be_func_struct_close']             = 'Sluiten';
$BL['be_func_struct_empty']             = 'Legen';

// article.contenttype.inc.php
$BL['be_func_switch_contentpart']       = 'Wilt u echt de inhoud wisselen? \n\n De inhoud wordt overschreven ! \n';
$BL['be_ctype_plaintext']               = 'ASCII-tekst';
$BL['be_ctype_html']                    = 'HTML (HTML Code)';
$BL['be_ctype_code']                    = 'Scriptcode';
$BL['be_ctype_textimage']               = 'Tekst (Met afbeelding uit bestandsbeheer)';
$BL['be_ctype_images']                  = 'Afbeeldingen uit bestandsbeheer';
$BL['be_ctype_bulletlist']              = 'Ongeordende lijst';
$BL['be_ctype_ullist']                  = 'Geordende lijst';
$BL['be_ctype_link']                    = 'Link naar pagina of mail';
$BL['be_ctype_linklist']                = 'Lijst met links';
$BL['be_ctype_linkarticle']             = 'Link naar een artikel';
$BL['be_ctype_multimedia']              = 'Media bestand (Geluid of video)';
$BL['be_ctype_filelist']                = 'Lijst met bestanden uit bestandsbeheer';
$BL['be_ctype_emailform']               = 'E-mailformulier';
$BL['be_ctype_newsletter']              = 'Nieuwsbrief';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Het profiel is succesvol aangemaakt.';
$BL['be_profile_create_error']          = 'Er is een fout opgetreden tijdens het aanmaken van het profiel.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'De profielgegevens zijn succesvol gewijzigd.';
$BL['be_profile_update_error']          = 'Er is een fout opgetreden tijdens het wijzigen van het profiel.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'Gebruikersnaam {VAL} is ongeldig';
$BL['be_profile_account_err2']          = 'Het opgegeven wachtwoord bevat slechts {VAL} karakters en is te kort. Een minimum van 5 karakters is vereist.';
$BL['be_profile_account_err3']          = 'De twee wachtwoorden moeten identiek zijn.';
$BL['be_profile_account_err4']          = 'E-mailadres {VAL} is ongeldig.';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'Persoonlijke gegevens';
$BL['be_profile_data_text']             = 'Persoonlijke gegevens zijn optioneel. Deze kunnen van dienst zijn voor andere gebruikers of bezoekers van deze site.';
$BL['be_profile_label_title']           = 'Titel';
$BL['be_profile_label_firstname']       = 'Voornaam';
$BL['be_profile_label_name']            = 'Achternaam';
$BL['be_profile_label_company']         = 'Bedrijf';
$BL['be_profile_label_street']          = 'Straat';
$BL['be_profile_label_city']            = 'Plaats';
$BL['be_profile_label_state']           = 'Provincie';
$BL['be_profile_label_zip']             = 'Postcode';
$BL['be_profile_label_country']         = 'Land';
$BL['be_profile_label_phone']           = 'Telefoon';
$BL['be_profile_label_fax']             = 'Fax';
$BL['be_profile_label_cellphone']       = 'GSM-nummer';
$BL['be_profile_label_signature']       = 'Ondertekening';
$BL['be_profile_label_notes']           = 'Notities';
$BL['be_profile_label_profession']      = 'Beroep';
$BL['be_profile_label_newsletter']      = 'Nieuwsbrief';
$BL['be_profile_text_newsletter']       = 'Aanmelden voor cmsgo nieuwsbrief.';
$BL['be_profile_label_public']          = 'Zichtbaarheid';
$BL['be_profile_text_public']           = 'Profielgegevens voor iedereen zichtbaar maken.';
$BL['be_profile_label_button']          = 'Opslaan';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Login gegevens';
$BL['be_profile_account_text']          = 'Het is niet nodig om uw gebruikersnaam te wijzigen. Het is wel belangrijk om af en toe uw wachtwoord te wijzigen.';
$BL['be_profile_label_err']             = 'Aub controleren';
$BL['be_profile_label_username']        = 'Gebruikersnaam';
$BL['be_profile_label_newpass']         = 'Nieuw wachtwoord';
$BL['be_profile_label_repeatpass']      = 'Nieuw wachtwoord herhalen';
$BL['be_profile_label_email']           = 'E-mailadres';
$BL['be_profile_account_button']        = 'Opslaan';
$BL['be_profile_label_lang']            = 'Taal';

// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'Via FTP verzonden bestanden registreren in bestandsbeheer';
$BL['be_ftptakeover_mark']              = 'Markeren';
$BL['be_ftptakeover_available']         = 'Beschikbare bestanden';
$BL['be_ftptakeover_size']              = 'Grootte';
$BL['be_ftptakeover_nofile']            = '<br>U heeft geen bestanden geupload. <br><br> Deze functie zorgt ervoor dat meerdere bestanden in 1 keer in de database geplaatst worden en is hierdoor tijdsbesparend. <br><br>U dient eerst bestanden te uploaden via een FTP programma. Deze bestanden dient u te plaatsen in de map <strong>"upload"</strong>.';
$BL['be_ftptakeover_all']               = 'Alles versturen';
$BL['be_ftptakeover_directory']         = 'Directory';
$BL['be_ftptakeover_rootdir']           = 'Root directory';
$BL['be_ftptakeover_needed']            = 'U dient minimaal &eacute;&eacute;n bestand te selecteren';
$BL['be_ftptakeover_optional']          = 'Optioneel';
$BL['be_ftptakeover_keywords']          = 'Sleutelwoorden';
$BL['be_ftptakeover_additional']        = '(extra)';
$BL['be_ftptakeover_longinfo']          = 'Informatie over&nbsp;&#13;de bestanden';
$BL['be_ftptakeover_status']            = 'Status';
$BL['be_ftptakeover_active']            = 'Actief';
$BL['be_ftptakeover_public']            = 'Openbaar';
$BL['be_ftptakeover_createthumb']       = 'Thumbnail aanmaken';
$BL['be_ftptakeover_button']            = 'Bestanden overnemen';

// files.reiter.tmpl.php
$BL['be_fprivadd_nofolders']            = 'Geen folders aangemaakt';
$BL['be_ftab_title']                    = 'Bestandsbeheer - Uploaden van plaatjes, Pdf, Word, Excel en Mp3';
$BL['be_ftab_createnew']                = 'Nieuwe directory aanmaken in de root';
$BL['be_ftab_paste']                    = 'Kopieren naar de root directory';
$BL['be_ftab_disablethumb']             = 'Thumbnails verbergen in overzicht';
$BL['be_ftab_enablethumb']              = 'Thumbnails laten zien in overzicht';
$BL['be_ftab_private']                  = 'Persoonlijk';
$BL['be_ftab_public']                   = 'Openbaar';
$BL['be_ftab_search']                   = 'Zoeken';
$BL['be_ftab_trash']                    = 'Prullenbak';
$BL['be_ftab_open']                     = 'Alle directories openen';
$BL['be_ftab_close']                    = 'Alle directories sluiten';
$BL['be_ftab_upload']                   = 'Bestand uploaden naar de root directory';
$BL['be_ftab_filehelp']                 = 'Helpbestand openen';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'Root directory';
$BL['be_fpriv_title']                   = 'Nieuwe (sub)directory aanmaken';
$BL['be_fpriv_inside']                  = 'In directory';
$BL['be_fpriv_error']                   = 'Fout: vul een directory-naam in';
$BL['be_fpriv_name']                    = 'Naam';
$BL['be_fpriv_status']                  = 'Status';
$BL['be_fpriv_button']                  = 'Aanmaken';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'Bewerk directory';
$BL['be_fpriv_newname']                 = 'Nieuwe naam';
$BL['be_fpriv_updatebutton']            = 'Opslaan';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Selecteer een bestand om te uploaden';
$BL['be_fprivup_err2']                  = 'De grootte van het te uploaden bestand is groter dan';
$BL['be_fprivup_err3']                  = 'Foutmelding tijdens het wegschrijven van een bestand';
$BL['be_fprivup_err4']                  = 'Foutmelding tijdens het creeren van een gebruikersdirectory.';
$BL['be_fprivup_err5']                  = 'Er zijn geen thumbnails aanwezig';
$BL['be_fprivup_err6']                  = 'Probeer het NIET nog een keer - dit is een foutmelding van de server! Neem zo snel mogelijk contact op met de<a href="mailto:{VAL}">webmaster</a>!';
$BL['be_fprivup_title']                 = 'Bestanden uploaden';
$BL['be_fprivup_button']                = 'Bestanden uploaden';
$BL['be_fprivup_upload']                = 'Uploaden';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'Bestandsinformatie bewerken';
$BL['be_fprivedit_filename']            = 'Bestandsnaam';
$BL['be_fprivedit_created']             = 'Datum';
$BL['be_fprivedit_dateformat']          = 'dd-mm-yy H:i';
$BL['be_fprivedit_err1']                = 'Proefnaam voor het bestand (terug naar het origineel)';
$BL['be_fprivedit_clockwise']           = 'Roteer thumbnail kloksgewijs [origineel bestand +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'Roteer thumbnail tegen de wijzers van de klok in [origineel bestand -90&deg;]';
$BL['be_fprivedit_button']              = 'Opslaan';
$BL['be_fprivedit_size']                = 'Grootte';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'Bestand uploaden naar directory';
$BL['be_fprivfunc_makenew']             = 'Nieuwe directory aanmaken';
$BL['be_fprivfunc_paste']               = 'Kopieren naar directory';
$BL['be_fprivfunc_edit']                = 'Directory-info bewerken';
$BL['be_fprivfunc_cactive']             = 'Schakelen actief/non actief';
$BL['be_fprivfunc_cpublic']             = 'Schakelen openbaar/niet openbaar';
$BL['be_fprivfunc_deldir']              = 'Directory verwijderen';
$BL['be_fprivfunc_jsdeldir']            = 'Weet u zeker dat u deze\ndirectory wilt verwijderen?';
$BL['be_fprivfunc_notempty']            = 'Directory {VAL} is niet leeg!';
$BL['be_fprivfunc_opendir']             = 'Directory openen';
$BL['be_fprivfunc_closedir']            = 'Directory sluiten';
$BL['be_fprivfunc_dlfile']              = 'Bestand openen in browser';
$BL['be_fprivfunc_clipfile']            = 'Bestand kopieren';
$BL['be_fprivfunc_cutfile']             = 'Bestand knippen';
$BL['be_fprivfunc_editfile']            = 'Bestandsinformatie bewerken';
$BL['be_fprivfunc_cactivefile']         = 'Schakelen actief/non actief';
$BL['be_fprivfunc_cpublicfile']         = 'Schakelen openbaar/niet openbaar';
$BL['be_fprivfunc_movetrash']           = 'Bestand verwijderen';
$BL['be_fprivfunc_jsmovetrash1']        = 'Weet u zeker dat u';
$BL['be_fprivfunc_jsmovetrash2']        = 'wilt verwijderen naar de prullenbak?';

// files.private.additions.inc.php
$BL['be_fprivadd_nodirectorys']         = 'Geen privebestanden of directory';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'Gebruiker';
$BL['be_fpublic_nofiles']               = 'Geen openbare bestanden of directory';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'De prullenbak is leeg';
$BL['be_ftrash_show']                   = 'Laat privebestanden zien';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Wilt u {VAL} terugzetten \nen verplaatsen naar de privelijst?';
$BL['be_ftrash_delete']                 = 'Wilt u {VAL} echt verwijderen?';
$BL['be_ftrash_undo']                   = 'Terugzetten (verwijderen ongedaan maken)';
$BL['be_ftrash_delfinal']               = 'Voorgoed verwijderen';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'Zoekopdrachtveld is leeg.';
$BL['be_fsearch_title']                 = 'Bestanden zoeken';
$BL['be_fsearch_infotext']              = '<br />* Er wordt gezocht in sleutelwoorden, bestandsnamen en uitgebreide bestandsinformatie.<br />* U kunt geen zg. wildcards gebruiken.<br />* Onderbreek meerdere zoekwoorden met een spatie.<br />* Selecteer AND/OR en welke soort bestanden u wilt zoeken: prive/openbaar.<br />';
$BL['be_fsearch_nonfound']              = 'Er werden met deze zoekopdracht geen bestanden gevonden. Probeer een andere zoekopdracht!';
$BL['be_fsearch_fillin']                = 'U moet eerst een zoekopdracht ingeven in het bovenstaande veld.';
$BL['be_fsearch_searchlabel']           = 'Zoek naar';
$BL['be_fsearch_startsearch']           = 'Start zoekopdracht';
$BL['be_fsearch_and']                   = 'AND';
$BL['be_fsearch_or']                    = 'OR';
$BL['be_fsearch_all']                   = 'Beide';
$BL['be_fsearch_personal']              = 'Prive';
$BL['be_fsearch_public']                = 'Openbaar';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'Chatten (onderling)';
$BL['be_chat_info']                     = 'Hier kunt u chatten met andere cmsgo backend (administratie) gebruikers. Dit is bedoeld voor "realtime"-communicatie, maar u kunt ook een bericht achterlaten zodat andere gebruikers op een later tijdstip dit kunnen lezen.';
$BL['be_chat_start']                    = 'Start met chatten';
$BL['be_chat_lines']                    = 'Chat-zinnen';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'Berichten';
$BL['be_msg_new']                       = 'nieuw';
$BL['be_msg_old']                       = 'Oud';
$BL['be_msg_senttop']                   = 'verzonden';
$BL['be_msg_del']                       = 'verwijder';
$BL['be_msg_from']                      = 'Van';
$BL['be_msg_subject']                   = 'Onderwerp';
$BL['be_msg_date']                      = 'Datum/tijd';
$BL['be_msg_close']                     = 'Bericht sluiten';
$BL['be_msg_create']                    = 'Nieuw bericht';
$BL['be_msg_reply']                     = 'Bericht beantwoorden';
$BL['be_msg_move']                      = 'Bericht verwijderen';
$BL['be_msg_unread']                    = 'Ongelezen of nieuwe berichten';
$BL['be_msg_lastread']                  = 'Laatst {VAL} gelezen berichten';
$BL['be_msg_lastsent']                  = 'Laatst {VAL} verzonden berichten';
$BL['be_msg_marked']                    = 'Berichten die klaarstaan om definitief te verwijderen';
$BL['be_msg_nomsg']                     = 'Geen berichten gevonden in deze directory.';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'Verzonden door';
$BL['be_msg_on']                        = 'op';
$BL['be_msg_msg']                       = 'Bericht';
$BL['be_msg_err1']                      = 'U bent vergeten een ontvanger in te vullen';
$BL['be_msg_err2']                      = 'Vul het onderwerpveld in (zo kan de ontvanger uw bericht beter verwerken)';
$BL['be_msg_err3']                      = 'Het heeft geen zin om een bericht te versturen zonder bericht ;-)';
$BL['be_msg_sent']                      = 'Het bericht is verstuurd';
$BL['be_msg_fwd']                       = 'U wordt doorgestuurd naar het berichtenbeheer of';
$BL['be_msg_newmsgtitle']               = 'Nieuw bericht';
$BL['be_msg_err']                       = 'Fout bij verzenden van het bericht';
$BL['be_msg_sendto']                    = 'Stuur bericht naar';
$BL['be_msg_available']                 = 'Contactpersonen';
$BL['be_msg_all']                       = 'Versturen naar de geselecteerde ontvangers';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'Nieuwsbriefabonnementen';
$BL['be_newsletter_titleedit']          = 'Wijzig nieuwsbrief subscription';
$BL['be_newsletter_new']                = 'Nieuw';
$BL['be_newsletter_add']                = 'Abonnement toevoegen';
$BL['be_newsletter_name']               = 'Naam';
$BL['be_newsletter_info']               = 'Info';
$BL['be_newsletter_button_save']        = 'Opslaan';
$BL['be_newsletter_button_cancel']      = 'Annuleren';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'Gebruikersnaam is foutief, kies een andere.';
$BL['be_admin_usr_err2']                = 'Gebruikersnaamveld is leeg (vereist).';
$BL['be_admin_usr_err3']                = 'Wachtwoordveld is leeg (vereist).';
$BL['be_admin_usr_err4']                = "E-mailadres is foutief ingevoerd.";
$BL['be_admin_usr_err']                 = 'Fout';
$BL['be_admin_usr_mailsubject']         = 'Welkom bij de administratiemodule (de zg. backend) van cmsgo!';
$BL['be_admin_usr_mailbody']            = "Welkom bij de administratiemodule (de zg. backend) van cmsgo!\n\n    Uw gebruikersnaam: {LOGIN}\n    Uw wachtwoord: {PASSWORD}\n\n\nU kunt hier inloggen: {LOGIN_PAGE}\n\ncmsgo admin\n ";
$BL['be_admin_usr_title']               = 'Nieuwe gebruiker toevoegen';
$BL['be_admin_usr_realname']            = 'Echte naam';
$BL['be_admin_usr_setactive']           = 'Inlog-rechten';
$BL['be_admin_usr_iflogin']             = 'Inlog-rechten toekennen';
$BL['be_admin_usr_isadmin']             = 'Admin-rechten';
$BL['be_admin_usr_ifadmin']             = 'Admin-rechten toekennen';
$BL['be_admin_usr_verify']              = 'Verificatie';
$BL['be_admin_usr_sendemail']           = 'Stuur accountinformatie per e-mail naar de nieuwe gebruiker';
$BL['be_admin_usr_button']              = 'Opslaan';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'Gebruikersprofiel bewerken';
$BL['be_admin_usr_emailsubject']        = 'cmsgo account-info aangepast';
$BL['be_admin_usr_emailbody']           = "CMSGO GEBRUIKERS-PROFIEL AANGEPAST\n\n    uw gebruikersnaam: {LOGIN}\n    Uw wachtwoord: {PASSWORD}\n\n\nU kunt hier inloggen: {LOGIN_PAGE}\n\ncmsgo admin\n ";
$BL['be_admin_usr_passnochange']        = '[GEEN VERANDERING - GEBRUIK HET BESTAANDE WACHTWOORD]';
$BL['be_admin_usr_ebutton']             = 'Opslaan';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'Gebruikers';
$BL['be_admin_usr_ldel']                = 'Let op!&#13Deze actie zal de gebruiker verwijderen';
$BL['be_admin_usr_create']              = 'Nieuwe gebruiker toevoegen';
$BL['be_admin_usr_editusr']             = 'Gebruikersgegevens bewerken';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'Structuur (categorieen)';
$BL['be_admin_struct_child']            = '(subcategorie van)';
$BL['be_admin_struct_index']            = 'Index';
$BL['be_admin_struct_cat']              = 'Titel';
$BL['be_admin_struct_hide1']            = 'Onzichtbaar';
$BL['be_admin_struct_hide2']            = 'Deze&nbsp;categorie&nbsp;in&nbsp;menu';
$BL['be_admin_struct_info']             = 'Infotekst';
$BL['be_admin_struct_template']         = 'Template';
$BL['be_admin_struct_alias']            = 'Alias';
$BL['be_admin_struct_visible']          = 'Zichtbaar';
$BL['be_admin_struct_button']           = 'Opslaan';
$BL['be_admin_struct_close']            = 'Sluiten';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'Bestandscategorieen';
$BL['be_admin_fcat_err']                = 'Categorienaamveld is leeg!';
$BL['be_admin_fcat_name']               = 'Categorienaam';
$BL['be_admin_fcat_needed']             = 'Vereist';
$BL['be_admin_fcat_button1']            = 'Opslaan';
$BL['be_admin_fcat_button2']            = 'Opslaan';
$BL['be_admin_fcat_delmsg']             = 'Weet u zeker dat u\ndit sleutelwoord wilt verwijderen?';
$BL['be_admin_fcat_fcat']               = 'Categorie';
$BL['be_admin_fcat_err1']               = 'Sleutelwoord-veld is leeg!';
$BL['be_admin_fcat_fkeyname']           = 'Sleutelwoord';
$BL['be_admin_fcat_exit']               = 'Annuleren';
$BL['be_admin_fcat_addkey']             = 'Nieuw sleutelwoord toevoegen';
$BL['be_admin_fcat_editcat']            = 'Categorienaam bewerken';
$BL['be_admin_fcat_delcatmsg']          = 'Weet u zeker dat u\ndeze categorie wilt verwijderen?';
$BL['be_admin_fcat_delcat']             = 'Categorie verwijderen';
$BL['be_admin_fcat_delkey']             = 'Sleutelwoord verwijderen';
$BL['be_admin_fcat_editkey']            = 'Sleutelwoord bewerken';
$BL['be_admin_fcat_addcat']             = 'Nieuwe categorie toevoegen';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'Frontend setup: Layouts';
$BL['be_admin_page_align']              = 'Pagina-uitlijning';
$BL['be_admin_page_align_left']         = 'Links uitlijnen';
$BL['be_admin_page_align_center']       = 'Centreren';
$BL['be_admin_page_align_right']        = 'Rechts uitlijnen';
$BL['be_admin_page_margin']             = 'Marge';
$BL['be_admin_page_top']                = 'Boven';
$BL['be_admin_page_bottom']             = 'Onder';
$BL['be_admin_page_left']               = 'Linkerblok';
$BL['be_admin_page_right']              = 'Rechterblok';
$BL['be_admin_page_bg']                 = 'Achtergrond';
$BL['be_admin_page_color']              = 'Kleur';
$BL['be_admin_page_height']             = 'Hoogte';
$BL['be_admin_page_width']              = 'Breedte';
$BL['be_admin_page_main']               = 'Hoofdblok';
$BL['be_admin_page_leftspace']          = 'Linkerkolom';
$BL['be_admin_page_rightspace']         = 'Rechterkolom';
$BL['be_admin_page_class']              = 'Class';
$BL['be_admin_page_image']              = 'Afbeelding';
$BL['be_admin_page_text']               = 'Tekst';
$BL['be_admin_page_link']               = 'Link';
$BL['be_admin_page_js']                 = 'Javascript';
$BL['be_admin_page_visited']            = 'Bezocht';
$BL['be_admin_page_pagetitle']          = 'Paginatitel';
$BL['be_admin_page_addtotitle']         = 'Titel&nbsp;toevoegen';
$BL['be_admin_page_category']           = 'Categorie';
$BL['be_admin_page_articlename']        = 'Artikel';
$BL['be_admin_page_blocks']             = 'Blokindeling';
$BL['be_admin_page_allblocks']          = 'Totaal';
$BL['be_admin_page_col1']               = '3-bloks layout';
$BL['be_admin_page_col2']               = '2-bloks layout (hoofdblok rechts, navigatie in linkerblok)';
$BL['be_admin_page_col3']               = '2-bloks layout (hoofdblok links, navigatie in rechterblok)';
$BL['be_admin_page_col4']               = '1-bloks layout';
$BL['be_admin_page_header']             = 'Header-blok';
$BL['be_admin_page_footer']             = 'Footer-blok';
$BL['be_admin_page_topspace']           = 'Header-marge';
$BL['be_admin_page_bottomspace']        = 'Footer-marge';
$BL['be_admin_page_button']             = 'Opslaan';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'Frontend setup: Stylesheet (CSS)';
$BL['be_admin_css_css']                 = 'CSS';
$BL['be_admin_css_button']              = 'Opslaan';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'Frontend setup: Templates';
$BL['be_admin_tmpl_default']            = 'default';
$BL['be_admin_tmpl_add']                = 'Nieuwe template toevoegen';
$BL['be_admin_tmpl_edit']               = 'Template bewerken';
$BL['be_admin_tmpl_new']                = 'Nieuwe template aanmaken';
$BL['be_admin_tmpl_css']                = 'Stylesheet&nbsp;&#13;(CSS)';
$BL['be_admin_tmpl_head']               = 'HTML-head';
$BL['be_admin_tmpl_js']                 = 'JS onload';
$BL['be_admin_tmpl_error']              = 'Foutmelding';
$BL['be_admin_tmpl_button']             = 'Opslaan';
$BL['be_admin_tmpl_name']               = 'Naam';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'Artikelen';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'De titel voor dit artikel is niet ingevuld.';
$BL['be_article_err2']                  = 'De begindatum mag niet in het verleden liggen!';
$BL['be_article_err3']                  = 'De einddatum mag niet in het verleden liggen!';
$BL['be_article_title1']                = 'Artikel-basisinformatie';
$BL['be_article_cat']                   = 'Categorie';
$BL['be_article_atitle']                = 'Titel';
$BL['be_article_asubtitle']             = 'Subtitel';
$BL['be_article_abegin']                = 'Begin';
$BL['be_article_aend']                  = 'Eind';
$BL['be_article_aredirect']             = 'Redirect naar';
$BL['be_article_akeywords']             = 'Sleutelwoorden';
$BL['be_article_asummary']              = 'Samenvatting';
$BL['be_article_abutton']               = 'Opslaan';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'De einddatum kan niet voor de begindatum liggen.';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'Artikel-basisinformatie';
$BL['be_article_eslastedit']            = 'Bewerkingsdatum';
$BL['be_article_esnoupdate']            = 'Formulier niet geupdate';
$BL['be_article_esbutton']              = 'Opslaan';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'Artikelinhoud';
$BL['be_article_cnt_type']              = 'Type inhoud';
$BL['be_article_cnt_space']             = 'Ruimte';
$BL['be_article_cnt_before']            = 'Voor';
$BL['be_article_cnt_after']             = 'Na';
$BL['be_article_cnt_top']               = 'Bovenaan';
$BL['be_article_cnt_toplink']           = 'Top link';
$BL['be_article_cnt_anchor']            = 'Anker';
$BL['be_article_cnt_ctitle']            = 'Titel';
$BL['be_article_cnt_back']              = 'Terug naar Artikelinformatie';
$BL['be_article_cnt_button1']           = 'Opslaan';
$BL['be_article_cnt_button2']           = 'Opslaan';
$BL['be_article_cnt_button3']           = 'Opslaan &amp; sluiten';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'Artikelinformatie';
$BL['be_article_cnt_ledit']             = 'Artikel bewerken';
$BL['be_article_cnt_lvisible']          = 'Schakel zichtbaar/onzichtbaar';
$BL['be_article_cnt_ldel']              = 'Artikel verwijderen';
$BL['be_article_cnt_ldeljs']            = 'Artikel echt verwijderen?';
$BL['be_article_cnt_redirect']          = 'Redirection';
$BL['be_article_cnt_edited']            = 'Bewerkt door';
$BL['be_article_cnt_start']             = 'Startdatum';
$BL['be_article_cnt_end']               = 'Einddatum';
$BL['be_article_cnt_add']               = 'Nieuwe inhoud toevoegen';
$BL['be_article_cnt_up']                = 'Artikel omhoog verplaatsen';
$BL['be_article_cnt_down']              = 'Artikel omlaag verplaatsen';
$BL['be_article_cnt_edit']              = 'Inhoud bewerken';
$BL['be_article_cnt_delpart']           = 'Inhoud verwijderen';
$BL['be_article_cnt_delpartjs']         = 'Inhoud verwijderen?';
$BL['be_article_cnt_center']            = 'Terug naar Artikelbeheer';

// content forms
$BL['be_cnt_plaintext']                 = 'ASCII-tekst';
$BL['be_cnt_htmltext']                  = 'HTML (WYSIWYG)';
$BL['be_cnt_image']                     = 'Afbeelding';
$BL['be_cnt_position']                  = 'Positie';
$BL['be_cnt_pos0']                      = 'Boven, links';
$BL['be_cnt_pos1']                      = 'Boven, midden';
$BL['be_cnt_pos2']                      = 'Boven, rechts';
$BL['be_cnt_pos3']                      = 'Onder, links';
$BL['be_cnt_pos4']                      = 'Onder, midden';
$BL['be_cnt_pos5']                      = 'Onder, rechts';
$BL['be_cnt_pos6']                      = 'In tekst, links';
$BL['be_cnt_pos7']                      = 'In tekst, rechts';
$BL['be_cnt_pos0i']                     = 'Afbeelding linksboven, tekst onder';
$BL['be_cnt_pos1i']                     = 'Afbeelding middenboven, tekst onder';
$BL['be_cnt_pos2i']                     = 'Afbeelding rechtsboven, tekst onder';
$BL['be_cnt_pos3i']                     = 'Afbeelding linksonder, tekst boven';
$BL['be_cnt_pos4i']                     = 'Afbeelding middenonder, tekst boven';
$BL['be_cnt_pos5i']                     = 'Afbeelding rechtsonder, tekst boven';
$BL['be_cnt_pos6i']                     = 'Afbeelding links, in tekst';
$BL['be_cnt_pos7i']                     = 'Afbeelding rechts, in tekst';
$BL['be_cnt_maxw']                      = 'Max.&nbsp;breedte';
$BL['be_cnt_maxh']                      = 'Max.&nbsp;hoogte';
$BL['be_cnt_enlarge']                   = 'Vergroten';
$BL['be_cnt_caption']                   = 'Titel';
$BL['be_cnt_subject']                   = 'Onderwerp';
$BL['be_cnt_recipient']                 = 'Ontvanger';
$BL['be_cnt_buttontext']                = 'Knoptekst';
$BL['be_cnt_sendas']                    = 'Verstuur als';
$BL['be_cnt_text']                      = 'Tekst';
$BL['be_cnt_html']                      = 'HTML';
$BL['be_cnt_formfields']                = 'Formuliervelden';
$BL['be_cnt_code']                      = 'Code';
$BL['be_cnt_infotext']                  = 'Inhoud';
$BL['be_cnt_subscription']              = 'Abonnement';
$BL['be_cnt_labelemail']                = 'Bijschrift&nbsp;&#13emailveld';
$BL['be_cnt_tablealign']                = 'Tabel&nbsp;uitlijnen';
$BL['be_cnt_labelname']                 = 'Bijschrift&nbsp;&#13naamveld';
$BL['be_cnt_labelsubsc']                = 'Bijschrift&nbsp;&#13abonnement';
$BL['be_cnt_allsubsc']                  = 'Alle&nbsp;&#13abonnementen';
$BL['be_cnt_default']                   = 'Standaard';
$BL['be_cnt_left']                      = 'Links';
$BL['be_cnt_center']                    = 'Midden';
$BL['be_cnt_right']                     = 'Rechts';
$BL['be_cnt_buttontext']                = 'Knoptekst';
$BL['be_cnt_successtext']               = 'Schermtekst&nbsp&nbsp&#13na&nbsp;aanmelding';
$BL['be_cnt_regmail']                   = 'E-mailbericht&nbsp&nbsp&#13na&nbsp;aanmelding';
$BL['be_cnt_logoffmail']                = 'E-mailbericht&nbsp&nbsp&#13na&nbsp;afmelding';
$BL['be_cnt_changemail']                = 'E-mailbericht&nbsp&nbsp&#13na&nbsp;aanpassing';
$BL['be_cnt_openimagebrowser']          = 'Afbeeldingen-browser openen';
$BL['be_cnt_openfilebrowser']           = 'Bestanden-browser openen';
$BL['be_cnt_sortup']                    = 'Omhoog verplaatsen';
$BL['be_cnt_sortdown']                  = 'Omlaag verplaatsen';
$BL['be_cnt_delimage']                  = 'Afbeelding verwijderen';
$BL['be_cnt_delfile']                   = 'Bestand verwijderen';
$BL['be_cnt_delmedia']                  = 'Media verwijderen';
$BL['be_cnt_column']                    = 'Kolom';
$BL['be_cnt_imagespace']                = 'Afbeeldingsruimte';
$BL['be_cnt_directlink']                = 'Directe link';
$BL['be_cnt_target']                    = 'Doel';
$BL['be_cnt_target1']                   = 'Nieuw venster';
$BL['be_cnt_target2']                   = 'Ouderframe van het venster';
$BL['be_cnt_target3']                   = 'Zelfde venster, zonder frames';
$BL['be_cnt_target4']                   = 'Zelfde frame of venster';
$BL['be_cnt_bullet']                    = 'Ongeordende lijst';
$BL['be_cnt_linklist']                  = 'Lijst met links';
$BL['be_cnt_plainhtml']                 = 'HTML';
$BL['be_cnt_files']                     = 'Bestanden';
$BL['be_cnt_description']               = 'Beschrijving';
$BL['be_cnt_linkarticle']               = 'Link naar artikel';
$BL['be_cnt_articles']                  = 'Artikelen';
$BL['be_cnt_ullist_desc']               = '~ = 1e Level, &nbsp; ~~ = 2e level, &nbsp; etc.';
$BL['be_cnt_movearticleto']             = 'Artikel verplaatsen naar lijst met artikelen';
$BL['be_cnt_removearticleto']           = 'Artikel verwijderen uit lijst met artikelen';
$BL['be_cnt_mediatype']                 = 'Mediatype';
$BL['be_cnt_control']                   = 'Controle';
$BL['be_cnt_showcontrol']               = 'Laat controlebalk zien';
$BL['be_cnt_autoplay']                  = 'Automatisch starten';
$BL['be_cnt_source']                    = 'Bron';
$BL['be_cnt_internal']                  = 'Intern';
$BL['be_cnt_openmediabrowser']          = 'Open media-browser';
$BL['be_cnt_external']                  = 'Extern';
$BL['be_cnt_mediapos0']                 = 'Boven, links';
$BL['be_cnt_mediapos1']                 = 'Boven, midden';
$BL['be_cnt_mediapos2']                 = 'Boven, rechts';
$BL['be_cnt_mediapos3']                 = 'In tekst, links';
$BL['be_cnt_mediapos4']                 = 'In tekst, rechts';
$BL['be_cnt_mediapos0i']                = 'Media linksboven, tekst onder';
$BL['be_cnt_mediapos1i']                = 'Media middenboven, tekst onder';
$BL['be_cnt_mediapos2i']                = 'Media rechtsboven, tekst onder';
$BL['be_cnt_mediapos3i']                = 'Media links, in tekst';
$BL['be_cnt_mediapos4i']                = 'Media links, in tekst';
$BL['be_cnt_setsize']                   = 'Grootte';
$BL['be_cnt_set1']                      = 'Mediagrootte 160 x 120 px';
$BL['be_cnt_set2']                      = 'Mediagrootte 240 x 180 px';
$BL['be_cnt_set3']                      = 'Mediagrootte 320 x 240 px';
$BL['be_cnt_set4']                      = 'Mediagrootte 480 x 360 px';
$BL['be_cnt_set5']                      = 'Breedte en hoogte annuleren';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'Nieuwe layout toevoegen';
$BL['be_admin_page_name']               = 'Layout-naam';
$BL['be_admin_page_edit']               = 'Layout bewerken';
$BL['be_admin_page_render']             = 'Opmaaktype';
$BL['be_admin_page_table']              = 'Tabel';
$BL['be_admin_page_div']                = 'CSS-DIV';
$BL['be_admin_page_custom']             = 'Overig';
$BL['be_admin_page_custominfo']         = 'gebruik inhoud van template-hoofdblok';
$BL['be_admin_tmpl_layout']             = 'Layout';
$BL['be_admin_tmpl_nolayout']           = 'Er is geen layout beschikbaar';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'Zoekformulier';
$BL['be_cnt_results']                   = 'Aantal&nbsp;&#13resultaten';
$BL['be_cnt_results_per_page']          = 'per&nbsp;pagina (default: alles)';
$BL['be_cnt_opennewwin']                = 'Resultaten&nbsp;in&nbsp;nieuw&nbsp;venster';
$BL['be_cnt_searchlabeltext']           = 'Dit zijn standaardteksten en -waarden voor het zoekformulier en de zoekpagina, indien er meer overeenkomsten zijn dan het opgegeven aantal.';
$BL['be_cnt_input']                     = 'Ingave';
$BL['be_cnt_style']                     = 'Stijl';
$BL['be_cnt_result']                    = 'Resultaat';
$BL['be_cnt_next']                      = 'Volgende';
$BL['be_cnt_previous']                  = 'Vorige';
$BL['be_cnt_align']                     = 'Uitlijnen';
$BL['be_cnt_searchformtext']            = 'De volgende teksten worden weergegeven als het zoekformulier is geopend, of als er geen overeenkomstige resultaten zijn met de opgegeven zoekterm.';
$BL['be_cnt_intro']                     = 'Intro';
$BL['be_cnt_noresult']                  = 'Geen&nbsp;&#13resultaten';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'Uitzetten';
$BL['be_cnt_move_deleted']              = 'Leeg prullenbak';
$BL['be_admin_keyword_add']             = 'Keyword toevoegen';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'Eigenaar';
$BL['be_article_adminuser']             = 'Admin';
$BL['be_article_username']              = 'Auteur';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'Tekst met Word editor';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'Alleen zichtbaar voor gebruikers';
$BL['be_admin_struct_status']           = 'Status in frontend-menu';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'Lijst met artikelen';
$BL['be_cnt_sitelevel']                 = 'Site-categorie';
$BL['be_cnt_sitecurrent']               = 'Huidige site-categorie';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'Backend default tekst';
$BL['be_ctype_ecard']                   = 'E-kaart';
$BL['be_ctype_blog']                    = 'Blog';
$BL['be_cnt_ecardtext']                 = 'Titel/e-card';
$BL['be_cnt_ecardtmpl']                 = 'Mail tmpl';
$BL['be_cnt_ecard_image']               = 'E-card afbeelding';
$BL['be_cnt_ecard_title']               = 'E-card titel';
$BL['be_cnt_alignment']                 = 'Positie';
$BL['be_cnt_ecardform']                 = 'Formulier tmpl';
$BL['be_cnt_ecardform_err']             = 'Alle velden met * zijn verplicht';
$BL['be_cnt_ecardform_sender']          = 'Van';
$BL['be_cnt_ecardform_recipient']       = 'Aan';
$BL['be_cnt_ecardform_name']            = 'Naam';
$BL['be_cnt_ecardform_msgtext']         = 'Jouw bericht';
$BL['be_cnt_ecardform_button']          = 'Vertuur e-card';
$BL['be_cnt_ecardsend']                 = 'Verstuur tmpl';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend default startup tekst';
$BL['be_admin_startup_text']            = 'Startup tekst';
$BL['be_admin_startup_button']          = 'Bewaar startup tekst';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'Gastenboek';
$BL['be_cnt_guestbook_listing']         = 'Berichten';
$BL['be_cnt_guestbook_listing_all']     = 'Laat alle berichten zien';
$BL['be_cnt_guestbook_list']            = 'Lijst';
$BL['be_cnt_guestbook_perpage']         = 'Berichten per&nbsp;pagina';
$BL['be_cnt_guestbook_form']            = 'Formulier';
$BL['be_cnt_guestbook_signed']          = 'Getekend';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'voor';
$BL['be_cnt_guestbook_after']           = 'na';
$BL['be_cnt_guestbook_entry']           = 'Bericht';
$BL['be_cnt_guestbook_edit']            = 'Bewerk';
$BL['be_cnt_ecardform_selector']        = 'Selecteer';
$BL['be_cnt_ecardform_radiobutton']     = 'Radio button';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript functionaliteit';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'Top artikel berichten';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'Nieuwsbrief';
$BL['be_newsletter_addnl']              = 'Nieuwsbrief toevoegen';
$BL['be_newsletter_titleeditnl']        = 'Nieuwsbrief bewerken';
$BL['be_newsletter_newnl']              = 'Nieuwsbrief aanmaken';
$BL['be_newsletter_button_savenl']      = 'Nieuwsbrief bewaren';
$BL['be_newsletter_fromname']           = 'van naam';
$BL['be_newsletter_fromemail']          = 'van email';
$BL['be_newsletter_replyto']            = 'antwoordt email';
$BL['be_newsletter_changed']            = 'Laatste verandering';
$BL['be_newsletter_placeholder']        = 'Placeholder';
$BL['be_newsletter_htmlpart']           = 'HTML nieuwsbrief inhoud';
$BL['be_newsletter_textpart']           = 'TEXT nieuwsbrief inhoud';
$BL['be_newsletter_allsubscriptions']   = 'Alle abonnementen';
$BL['be_newsletter_verifypage']         = 'Link goedkeuren';
$BL['be_newsletter_open']               = 'HTML en TEXT toevoegen';
$BL['be_newsletter_open1']              = '(klik op het plaatje om te openen)';
$BL['be_newsletter_sendnow']            = 'Verstuur nieuwsbrief';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Let op!</strong> Wees er zeker van dat de nieuwsbrief goed is opgesteld. Stuur voor de zekerheid eerst een test';
$BL['be_newsletter_attention1']         = 'Waaneer er veranderingen zijn gemaakt dien je eerst op bewaren te klikken!';
$BL['be_newsletter_testemail']          = 'Test email';
$BL['be_newsletter_sendnlbutton']       = 'Verstuur nieuwsbrief';
$BL['be_newsletter_sendprocess']        = 'Versturings process';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Let op!</strong> Klik maar een keer op versturen en wacht tot u een melding krijgt dat het versturen van de nieuwsbrief correct is verlopen. Afhankelijk van de snelheid van de gebruikte server en het aantal nieuwsbrief gebruikers kan dit even duren.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">Het test email adres <strong>###TEST###</strong> is niet correct!<br />&nbsp;<br />Probeer het opnieuw!';
$BL['be_newsletter_to']                 = 'Ontvangers';
$BL['be_newsletter_ready']              = 'Versturen van de nieuwsbrief is correct verlopen!';
$BL['be_newsletter_readyfailed']        = 'Versturen van de nieuwsbrief is mislukt aan';
$BL['be_subnav_msg_subscribers']        = 'Nieuwsbrief abonnees';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'Sitemap';
$BL['be_cnt_sitemap_catimage']          = 'Level icoon';
$BL['be_cnt_sitemap_articleimage']      = 'Artikel icoon';
$BL['be_cnt_sitemap_display']           = 'Laat zien';
$BL['be_cnt_sitemap_structuronly']      = 'Aleen structuur levels';
$BL['be_cnt_sitemap_structurarticle']   = 'Structuur levels + artikelen';
$BL['be_cnt_sitemap_catclass']          = 'Level class';
$BL['be_cnt_sitemap_articleclass']      = 'Artikel class';
$BL['be_cnt_sitemap_count']             = 'Teller';
$BL['be_cnt_sitemap_classcount']        = 'Voeg toe aan class naam';
$BL['be_cnt_sitemap_noclasscount']      = 'Niet toevoegen aan class naam';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'Bieden';
$BL['be_cnt_bid_bidtext']               = 'Bied tekst';
$BL['be_cnt_bid_sendtext']              = 'Verzonden tekst';
$BL['be_cnt_bid_verifiedtext']          = 'Goedgekeurde tekst';
$BL['be_cnt_bid_errortext']             = 'Bod verwijderd';
$BL['be_cnt_bid_verifyemail']           = 'Email goedkeuren';
$BL['be_cnt_bid_startbid']              = 'Start bod';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'verminder&nbsp;met';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'Externe content';
$BL['be_cnt_pages_select']              = 'selecteer bestand';
$BL['be_cnt_pages_fromfile']            = 'nestand van structuur';
$BL['be_cnt_pages_manually']            = 'custom pad/bestand of URL';
$BL['be_cnt_pages_cust']                = 'bestand/URL';
$BL['be_cnt_pages_from']                = 'bron';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'Rollover afbeelding';
$BL['be_cnt_reference_basis']           = 'Positie';
$BL['be_cnt_reference_horizontal']      = 'Horizontaal';
$BL['be_cnt_reference_vertical']        = 'Verticaal';
$BL['be_cnt_reference_aligntext']       = 'kleine referentie afbeelding';
$BL['be_cnt_reference_largetext']       = 'grote referentie afbeelding';
$BL['be_cnt_reference_zoom']            = 'Inzoomen';
$BL['be_cnt_reference_middle']          = 'Midden';
$BL['be_cnt_reference_border']          = 'Rand';
$BL['be_cnt_reference_block']           = 'Blokeer b x h';

// added: 31-05-2004
$BL['be_article_rendering']             = 'Rendering';
$BL['be_article_nosummary']             = 'Laat geen opsomming zien in hele artikel';
$BL['be_article_forlist']               = 'Artikel opsomming';
$BL['be_article_forfull']               = 'Laat hele artikel zien';

// new translation yet to be reviewed
// translated: 25-06-2005

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>Let OP!</strong> De &quot;SETUP&quot; folder bestaat nog! Verwijder deze, het is een potentieel veiligheidsprobleem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'Verboden woorden';
$BL['be_cnt_guestbook_flooding']        = 'Flooding';
$BL['be_cnt_guestbook_setcookie']       = 'Maak een cookie';
$BL['be_cnt_guestbook_allowed']         = 'Mag weer na';
$BL['be_cnt_guestbook_seconds']         = 'Seconden';
$BL['be_alias_ID']                      = 'Alias ID';
$BL['be_ftrash_delall']                 = "Wil je echt \nALLE BESTANDEN verwijderen?";
$BL['be_ftrash_delallfiles']            = 'Verwijder alle bestanden in prullenbak';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'Invoeren van CSV abonnees';
$BL['be_newsletter_importtitle']        = 'Invoer Nieuwsbrief Abonnees';
$BL['be_newsletter_entriesfound']       = 'invoeringen&nbsp;gevonden';
$BL['be_newsletter_foundinfile']        = 'in bestand';
$BL['be_newsletter_addresses']          = 'Adressen';
$BL['be_newsletter_csverror']           = 'Ingevoerde CSV bestanden blijken incorrect! Check afbakening!';
$BL['be_newsletter_importall']          = 'Voer alle entries in';
$BL['be_newsletter_addressesadded']     = 'Adressen toegevoegd.';
$BL['be_newsletter_newimport']          = 'Importeer';
$BL['be_newsletter_importerror']        = 'Check uw CSV bestand aub - er kunnen geen addressen bij!';
$BL['be_newsletter_shouldbe1']          = 'Uw CSV bestand zou moeten geformateerd worden in deze wijze';
$BL['be_newsletter_shouldbe2']          = 'maar je kan je eigen afbakening kiezen';
$BL['be_newsletter_sample']             = 'monster';
$BL['be_newsletter_selectCSV']          = 'selecteer CSV bestand';
$BL['be_newsletter_delimeter']          = 'afbakening';
$BL['be_newsletter_importCSV']          = 'importeer CSV bestand';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'order van toegewezen bestanden';
$BL['be_admin_struct_orderdate']        = 'creatie datum';
$BL['be_admin_struct_orderchangedate']  = 'wijziging datum';
$BL['be_admin_struct_orderstartdate']   = 'start datum';
$BL['be_admin_struct_orderdesc']        = 'dalende';
$BL['be_admin_struct_orderasc']         = 'stijgende';
$BL['be_admin_struct_ordermanual']      = 'manueel (pijl boven/beneden)';
$BL['be_cnt_sitemap_startid']           = 'begin aan';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'Landkaart';
$BL['be_save_btn']                      = 'Opslaan';
$BL['be_cmap_location_error_notitle']   = 'pik een titel voor deze plaats.';
$BL['be_cnt_map_add']                   = 'voeg een plaats toe';
$BL['be_cnt_map_edit']                  = 'wijzig plaats';
$BL['be_cnt_map_title']                 = 'plaatstitel';
$BL['be_cnt_map_info']                  = 'entry/info';
$BL['be_cnt_map_list']                  = 'plaats lijst';
$BL['be_btn_delete']                    = 'Will je deze plaats echt \nverwijderen?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP variabelen';
$BL['be_cnt_vars']                      = 'variabelen';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'Artikel kopieren en ergens anders plakken';
$BL['be_func_struct_nocopy']            = 'Artikel kopieren annuleren';
$BL['be_func_struct_copy_level']        = 'Kopieer structuur niveau';
$BL['be_func_struct_no_copy']           = "Het is onmogelijk het root niveau te kopieren!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minuut';
$BL['be_date_minutes']                  = 'minuten';
$BL['be_date_hour']                     = 'uur';
$BL['be_date_hours']                    = 'uren';
$BL['be_date_day']                      = 'dag';
$BL['be_date_days']                     = 'dagen';
$BL['be_date_week']                     = 'week';
$BL['be_date_weeks']                    = 'weken';
$BL['be_date_month']                    = 'maand';
$BL['be_date_months']                   = 'maanden';
$BL['be_off']                           = 'Uit';
$BL['be_on']                            = 'Aan';
$BL['be_cache']                         = 'Tussengeheugen';
$BL['be_cache_timeout']                 = 'Tijdsoverschrijding';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'Gebruikers &amp; groepen';
$BL['be_admin_group_add']               = 'Groep toevoegen';
$BL['be_admin_group_nogroup']           = 'Geen gebruikersgroep gevonden';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'Forum';
$BL['be_subnav_msg_forum']              = 'forums lijst';
$BL['be_forum_title']                   = 'forum titel';
$BL['be_forum_permission']              = 'toestemmingen';
$BL['be_forum_add']                     = 'voeg forum toe';
$BL['be_forum_titleedit']               = 'bewerk forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'aanpasbare';
$BL['be_show_content']                  = 'weergave';
$BL['be_main_content']                  = 'hoofdkolom';
$BL['be_admin_template_jswarning']      = 'Pas op!!! \nAangespaste blokken kunnen veranderen! \n\nWanneer je de paginalayour \nverwerkt! \n\nWijzig voorbeelddocument?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS feed';
$BL['be_cnt_rssfeed_url']               = 'RSS url';
$BL['be_cnt_rssfeed_item']              = 'Items';
$BL['be_cnt_rssfeed_max']               = 'Max.';
$BL['be_cnt_rssfeed_cut']               = 'verstop eerste item';

$BL['be_ctype_simpleform']              = 'Email contact formulier';

$BL['be_cnt_onsuccess']                 = 'Succes';
$BL['be_cnt_onerror']                   = 'Fout';
$BL['be_cnt_onsuccess_redirect']        = 'Redirect bij succes';
$BL['be_cnt_onerror_redirect']          = 'Redirect wanneer het fout gaat';

$BL['be_cnt_form_class']                = 'Formulier class';
$BL['be_cnt_label_wrap']                = 'Label omslag';
$BL['be_cnt_error_class']               = 'Foute class';
$BL['be_cnt_req_mark']                  = 'Vereist teken';
$BL['be_cnt_mark_as_req']               = 'Maak het vereist';
$BL['be_cnt_mark_as_del']               = 'Selecteer item voor verwijderen';


$BL['be_cnt_type']                      = 'Type';
$BL['be_cnt_label']                     = 'Label';
$BL['be_cnt_needed']                    = 'Geeist';
$BL['be_cnt_delete']                    = 'Verwijder';
$BL['be_cnt_value']                     = 'Waarde';
$BL['be_cnt_error_text']                = 'Foute tekst';
$BL['be_cnt_css_style']                 = 'CSS style';
$BL['be_cnt_send_copy_to']              = 'Kopieer naar';

$BL['be_cnt_field']                     = array("text"=>'texst (1 lijn)', "email"=>'email', "textarea"=>'tekst (meerdere-lijnen)',
                                                "hidden"=>'verborgen', "password"=>'wachtwoord', "select"=>'selecteer menu',
                                                "list"=>'lijst menu', "checkbox"=>'checkbox', "radio"=>'radio drukknop',
                                                "upload"=>'bestand', "submit"=>'verzend drukknop', "reset"=>'resetknop',
                                                "break"=>'break', "breaktext"=>'breaktekst', "special"=>'tekst (speciaal)'
                                                , "captcha"=>'captcha code', "captchaimg"=>'captcha image');



$BL['be_cnt_access']                    = 'Toegang';
$BL['be_cnt_activated']                 = 'Geactiveerd';
$BL['be_cnt_available']                 = 'Beschikbaar';
$BL['be_cnt_guests']                    = 'Gast';
$BL['be_cnt_admin']                     = 'Admin';
$BL['be_cnt_write']                     = 'Schrijf';
$BL['be_cnt_read']                      = 'Lees';

$BL['be_cnt_no_wysiwyg_editor']         = 'Blokeer WYSIWYG editor';
$BL['be_cnt_cache_update']              = 'Reset tussengeheugen';
$BL['be_cnt_cache_delete']              = 'Verwijder tussengeheugen';
$BL['be_cnt_cache_delete_msg']          = 'Wil je echt het tussengeheugen verwijderen?  \nDit kan het zoeken vertragen.  \n';

$BL['be_admin_usr_issection']           = 'Aanmeldingssectie';
$BL['be_admin_usr_ifsection0']          = 'Frontend';
$BL['be_admin_usr_ifsection1']          = 'Backend';
$BL['be_admin_usr_ifsection2']          = 'Frontend en backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'Bewerk dit artikelinhoudsdeel';
$BL['be_func_content_paste0']            = 'Kopieer in artikel';
$BL['be_func_content_paste']             = 'Kopieer latere artikelinhoudsdeel';
$BL['be_func_content_cut']               = 'Knip dit artikelinhoudsdeel';
$BL['be_func_content_no_cut']            = 'Het is niet mogelijk om dit artikelinhoudsdeel te knippen!';
$BL['be_func_content_copy']              = 'Kopieer dit artikelinhoudsdeel';
$BL['be_func_content_no_copy']           = 'Het is niet mogelijk om dit artikelinhoudsdeel te kopieren!';
$BL['be_func_content_paste_cancel']      = 'Annuleer deze inhoudsdeel wijziging';
$BL['be_article_cnt_button3']            = 'Opslaan &amp; sluiten';

$BL['be_cnt_move_deleted']               = 'Verwijder bestanden uit de prullenbak';
$BL['be_cnt_move_deleted_msg']           = 'Weet je zeker dat je alle bestanden wilt verwijderen?\n';

$BL['be_admin_struct_permit']            = 'Geautorizeerd voor toegang';
$BL['be_admin_struct_adduser_all']       = 'Neem controle over van alle gebruikers';
$BL['be_admin_struct_adduser_this']      = 'Neem controle over van geselecteerde gebruiker';
$BL['be_admin_struct_remove_all']        = 'Verwijder alle gebruikers';
$BL['be_admin_struct_remove_this']       = 'Verwijder geselecteerde gebruiker';

$BL['be_ctype_alias']                    = 'Contentpart alias';
$BL['be_cnt_setting']                    = 'Neem over';
$BL['be_cnt_spaces']                     = 'Ruimte in contentpart alias';
$BL['be_cnt_toplink']                    = 'Top link instelling van contentpart alias';
$BL['be_cnt_block']                      = 'Laat de (blok) instelling van de contentpart alias zien';
$BL['be_cnt_title']                      = 'Titels van de contentpart alias';

$BL['be_file_replace']                   = 'Vervang titel bestanden';

$BL['be_alias_articleID']                = 'Alias ID';
$BL['be_alias_useAll']                   = "Gebruik dit artikel&#8217;s header data";
$BL['be_article_morelink']               = '[Meer&#8230;] link';
$BL['be_admin_tmpl_copy']                = 'Kopieer template';

$BL['be_ctype_filelist1']                = 'Bestanden lijst pro';
$BL['be_cnt_fpro_usecaption']            = 'Gebruik bestandsbeheer &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']                 = 'Keywords';
$BL['be_admin_keywords_key']             = 'KEYWORD';
$BL['be_admin_keywords_err']             = 'Voeg een uniek KEYWORD naam in';
$BL['be_admin_keyword_edit']             = 'Wijzig KEYWORD';
$BL['be_admin_keyword_del']              = 'Verwijder KEYWORD';
$BL['be_admin_keyword_delmsg']           = 'Weet je zeker dat je\nto het KEYWORD wilt verwijderen?';
$BL['be_admin_keyword_add']              = 'KEYWORD toevoegen';

$BL['be_cnt_transparent']                = 'Flash transparant';


// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']     = 'Datum verlopen';
$BL['be_func_switch_contentpart']        = 'Weet je zeker dat je wilt wisselen tussen de content parts? \n\nWees hier voorzichtig mee! \nBelangrijke instellingen kunnen hierdoor overschreven worden! \n';
$BL["cmsgo_code_snippets_dir_exists"]  = '<strong>LET OP!</strong> De &quot;CODE-SNIPPETS&quot; directory is nog steeds aanwezig! Verwijder de map <strong>cmsgo_code_snippets</strong> - Het is een potentieel beveiligings probleem.';

$BL['be_ctype_poll'] = 'poll';
$BL['be_cnt_pos8']                      = 'Tabel, links';
$BL['be_cnt_pos9']                      = 'Tabel, rechts';
$BL['be_cnt_pos8i']                     = 'Plaatje links in de tabel uitlijnen';
$BL['be_cnt_pos9i']                     = 'Plaatje rechts in de tabel uitlijnen';


$BL['be_WYSIWYG']                       = 'WYSIWYG editor';
$BL['be_WYSIWYG_disabled']              = 'WYSIWYG editor uitgeschakeld';
$BL['be_admin_struct_acat_hiddenactive'] = 'Zichtbaar wanneer actief';



$BL['be_login_jsinfo']                  = 'U dient de JavaScript functionaliteit aan te zetten in uw browser. De administratie werkt niet zonder JavaScript functionaliteit!';

$BL['be_admin_struct_maxlist']          = 'Max. aantal artikelen in lijst mode';

$BL['be_admin_optgroup_label']          = array(1 => 'tekst', 2 => 'plaatje', 3 => 'formulier', 4 => 'admin', 5 => 'speciaal');
$BL['be_cnt_articlemenu_maxchar']       = 'Max. aantal karakters';

$BL['be_cnt_sysadmin_system']           = 'Systeem';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date']               = 'De installatie is up to dat. Er zijn geen updates beschikbaar voor deze versie.';
$BL['Version_not_up_to_date']           = 'De installatie is <b>niet</b> up to date. Er zijn updates beschikbaar. Ga naar <a href="https://github.com/slackero/cmsgo/releases" target="_blank">GitHub Releases</a> om de laatste versie te downloaden.';
$BL['Latest_version_info']              = 'De laatste versie is <b>cmsgo %s</b>.';
$BL['Current_version_info']             = 'Versie: <b>cmsgo %s</b>.';
$BL['Connect_socket_error']             = 'Kan geen verbinding maken met de cmsgo server. De foutcode is:<br />%s';
$BL['Socket_functions_disabled']        = 'Niet mogelijk om socket functies te gebruiken.';
$BL['Mailing_list_subscribe_reminder']  = 'Schrijf je in voor de mailing list om up to date te blijve van de laatste informatie en versies. <a href="http://eepurl.com/bm-BrH" target="_blank">Abonneer</a>.';
$BL['Version_information']              = 'cmsgo Versie Informatie';

$BL['be_cnt_search_highlight']          = 'Markeer';
$BL['be_cnt_results_wordlimit']         = 'Max. aantal woorden voor opsomming';
$BL['be_cnt_page_of_pages']             = 'Zoek navi';
$BL['be_cnt_page_of_pages_descr']       = '{PREV:Terug} page #/##, resultaat ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Volgende}';
$BL['be_cnt_search_show_top']           = 'Top';
$BL['be_cnt_search_show_bottom']        = 'Bodem';
$BL['be_cnt_search_show_next']          = 'Volgende (ook wanneer er geen link is)';
$BL['be_cnt_search_show_prev']          = 'Vorige (ook wanneer er geen link is)';
$BL['be_cnt_search_show_forall']        = 'Altijd zichtbaar';
$BL['be_cnt_search_startlevel']         = 'Zoeken starten';
$BL['be_cnt_results_minchar']           = 'Minimale aantal karakters voor het zoekformulier';

$BL['be_cnt_pagination']                = 'Pagineer content parts';
$BL['be_article_pagination']            = 'Pagineer artikelen';
$BL['be_article_per_page']              = 'Artikelen per pagina';
$BL['be_pagination']                    = 'Pagineer';

$BL['be_ctype_recipe']                  = 'Recept';
$BL['be_ctype_faq']                     = 'Veel gestelde vragen';
$BL['be_cnt_additional']                = 'Toevoeging';
$BL['be_cnt_question']                  = 'Vraag';
$BL['be_cnt_answer']                    = 'Antwoord';
$BL['be_cnt_same_as_summary']           = 'Gebruik de data van het artikel plaatje';
$BL['be_cnt_sorting']                   = 'Sorteren';
$BL['be_cnt_imgupload']                 = 'Upload&nbsp;plaatje';
$BL['be_cnt_filesize']                  = 'Bestandsgroote';
$BL['be_cnt_captchalength']             = 'Lengte van de captcha code';
$BL['be_cnt_chars']                     = 'Klusjes';
$BL['be_cnt_download']                  = 'Download';
$BL['be_cnt_download_direct']           = 'Direct';
$BL['be_cnt_database']                  = 'Database';
$BL['be_cnt_formsave_in_db']            = 'Sla formulier resultaten op';

$BL['be_cnt_email_notify']              = 'Op de hoogte houden via email';
$BL['be_cnt_notify_by_email']           = 'van email naar';
$BL['be_cnt_last_edited']               = 'Laatste wijziging';

$BL['be_cnt_export_selection']          = 'Exporteer selectie';
$BL['be_cnt_delete_duplicates']         = 'Verwijder duplicaten';
$BL['be_cnt_new_recipient']             = 'Ontvanger toevoegen';


$BL['be_cnt_newsletter_prepare']        = 'Activeer nieuwsbrief';
$BL['be_cnt_newsletter_prepare1']       = 'Alle Ontvangers worden naar de verzendlijst verstuurd';
$BL['be_cnt_newsletter_prepare2']       = 'Verzendlijst wordt geupdate&#8230;';

$BL['be_cnt_export']                    = 'Exporteer';
$BL['be_cnt_formsave_profile']          = 'Sla gebruikers profiel op';
$BL['be_profile_label_add']             = 'Additief';
$BL['be_profile_label_website']         = 'Website (URL)';
$BL['be_profile_label_gender']          = 'Geslacht';
$BL['be_profile_label_birthday']        = 'Geboortedatum';

$BL['be_cnt_store_in']                  = 'Opslaan in veld';
$BL['be_aboutlink_title']               = 'Informatie over cmsgo en licentie';

$BL['be_shortdate']                     = 'n/j/y';
$BL['be_shortdatetime']                 = 'n/j/y G:i';

$BL['be_confirm_sending']               = 'confirm sending';
$BL['be_confirm_text']                  = 'Ja, verzend de nieuwsbrief naar alle ontvangers!';

$BL['be_cnt_queued']                    = 'In de wachtrij plaatsen';
$BL['be_last_sending']                  = 'Laatst verzonden';
$BL['be_last_edited']                   = 'Laatst gewijzigd';
$BL['be_total']                         = 'Totaal';

$BL['be_settings']                      = 'Instellingen';
$BL['be_ctype']                         = 'Contentpart';
$BL['be_selection']                     = 'Selectie';

$BL['be_ctype_module']                  = 'Plug-in';

$BL['CSRF_ERROR_TITLE'] = 'Beveiligingsvalidatie Mislukt';
$BL['CSRF_POST_INVALID'] = 'Geen POST <a href="https://nl.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a>-parameters gevonden. Formulierverzending afgebroken.';
$BL['CSRF_POST_FAILED'] = 'Validatie van POST <a href="https://nl.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a>-parameters is mislukt. Formulierverzending afgebroken.';
$BL['CSRF_GET_INVALID'] = 'Geen GET <a href="https://nl.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a>-parameters gevonden. Navigatie afgebroken.';
$BL['CSRF_GET_FAILED'] = 'Validatie van GET <a href="https://nl.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a>-parameters is mislukt. Navigatie afgebroken.';
$BL['CSRF_BTN_BACK'] = 'Terug';
$BL['CSRF_BTN_LOGIN'] = 'Inloggen';
$BL['CSRF_BTN_DASHBOARD'] = 'Dashboard';



// --- Equalized English Fallbacks ---
$BL['be_subnav_file_actions']           = 'file actions';
$BL['be_ftptakeover_new_folder']        = 'create folder';
$BL['be_ftptakeover_new_folder_placeholder'] = 'name of the new folder in the root directory';
$BL['be_ftabhelp_add']                  = 'add new private directory';
$BL['be_ftabhelp_upload']               = 'upload new file to private directory';
$BL['be_ftabhelp_disablethumb']         = 'disable thumbnails in file list';
$BL['be_ftabhelp_enablethumb']          = 'enable thumbnails in file list';
$BL['be_ftabhelp_edit']                 = 'edit file information details';
$BL['be_ftabhelp_cut']                  = 'cut file into clipboard for moving to another directory';
$BL['be_ftabhelp_cutmark']              = 'marked file is in clipboard';
$BL['be_ftabhelp_paste']                = 'paste file into this directory';
$BL['be_ftabhelp_download']             = 'download file (if it is a picture or text it will open in new browser window - maybe / on PC use right mouse click and save under in context menu / on mac hold down control key and mouse click...)';
$BL['be_ftabhelp_delete']               = 'delete directory or move file to trash or if the file is in trash delete file';
$BL['be_ftabhelp_cantdelete']           = 'directory can\'t be deleted because it contains files or subdirectories';
$BL['be_ftabhelp_restore']              = 'restore file from trash can (undo) and move back to private file list (file will be moved back to the root dir)';
$BL['be_ftabhelp_openfolder']           = 'fold out (open) every directory and its subdirectories';
$BL['be_ftabhelp_closefolder']          = 'fold in (close) every directory and its subdirectories';
$BL['be_ftabhelp_inactive']             = 'file or directory is inactive - click on it to switch status to active';
$BL['be_ftabhelp_active']               = 'file or directory is active - click on it to switch status to inactive';
$BL['be_ftabhelp_private']              = 'file or directory is private - click on it to switch status to public';
$BL['be_ftabhelp_public']               = 'file or directory is public - click on it to switch status to private';
$BL['be_fpriv_errordir']                = 'error: directory cannot be subfolder of itself';
$BL['be_fprivup_err7']                  = 'For security reasons the file %s cannot be uploaded.';
$BL['be_fprivup_err8']                  = 'File with extension %s is not allowed for upload. Allowed extensions are: %s.';
$BL['be_fprivup_err9']                  = 'File without extension is not allowed for upload. Allowed extensions are: %s.';
$BL['be_admin_struct_alt']              = 'category alternative title';
$BL['be_article_cnt_addtitle']          = 'add new content part';
$BL['be_cnt_ullist']                    = 'list';
$BL['be_cnt_search_default_type']       = 'search type';
$BL['be_cnt_sitemap_without_parent']    = 'without start level';
$BL['be_cnt_pages_php_render_warning']  = 'inline PHP <code>$cmsgo[&#39;enable_inline_php&#39;]</code> is disabled';
$BL['be_cnt_css_class']                 = 'CSS class';
$BL['be_cnt_optin']                     = 'Double Opt-In';
$BL['be_cnt_doubleoptin']               = 'activate Double Opt-In according to <a href="https://en.wikipedia.org/wiki/General_Data_Protection_Regulation" target="_blank">General Data Protection Regulation</a> (GDPR)';
$BL['be_cnt_novalidate']                = 'Novalidate';
$BL['be_cnt_status'] = 'visibility of contentpart alias';
$BL['be_cnt_plugin_n.a.'] = 'plugin not available';
$BL['gd_not_loaded'] = '<strong>No GD functionality available!</strong> Please make sure that the PHP GD library is activated, otherwise the processing of images will not work reliably.';
$BL['be_cnt_search_hidesummary']        = 'hide search teaser text';
$BL['be_cnt_search_searchnot']          = 'no search for';
$BL['be_longdatetime']                  = 'm/d/Y H:i:s';
$BL['be_cnt_lightbox']                  = 'gallery image';
$BL['be_cnt_behavior']                  = 'behavior';
$BL['be_cnt_imglist_nocaption']         = 'hide caption for thumbnails';
$BL['be_ctype_felogin']                 = 'frontend login';
$BL['be_cookie_runtime']                = 'cookie expire';
$BL['be_locale']                        = 'locale';
$BL['be_date_format']                   = 'date format';
$BL['be_check_login_against']           = 'validate login against';
$BL['be_userprofile_db']                = 'user profile database';
$BL['be_backenduser_db']                = 'backend user database';
$BL['be_check_login_allow_email']       = 'Accept email as login';
$BL['be_gb_post_login']                 = 'post for users logged in only';
$BL['be_gb_show_login']                 = 'show for users logged in only';
$BL['be_gb_urlcheck']                   = 'enable remote URL validation';
$BL['be_order']                         = 'order';
$BL['be_unique_teaser_entry']           = 'show teaser/link article only once per page';
$BL['be_allowed_tags']                  = 'allowed tags';
$BL['be_fe_login_url']                  = 'FE login url';
$BL['be_ctype_imagesdiv']               = 'images &lt;div&gt;';
$BL['be_cnt_imagecenter']               = 'center horizontal/vertical';
$BL['be_cnt_imagenocenter']             = 'do not center';
$BL['be_cnt_imagecenterh']              = 'center horizontal';
$BL['be_cnt_imagecenterv']              = 'center vertical';
$BL['be_check_against_category_alias']  = 'link single article inside structure level with structure level';
$BL['be_overwrite_default']             = 'Will overwrite default settings of config file';
$BL['be_cnt_sortvalue']                 = 'sort&nbsp;value';
$BL['be_dialog_warn_nosave']            = 'If you continue no change will be saved!\nAre you sure you want to continue?';
$BL['be_cnt_paginate_subsection']       = 'subsection';
$BL['be_cnt_subsection_tite']           = 'subsection title';
$BL['be_cnt_subsection_warning']        = 'Numbering subsections (paginate content parts) is available for\nmain column (CONTENT) only!';
$BL['be_no_search']                     = 'no search';
$BL['be_priorize']                      = 'prioritization';
$BL['be_change_articleID']              = 'change article ID';
$BL['be_title_wrap']                    = 'wrap article title';
$BL['be_no_rss']                        = 'RSS';
$BL['be_article_urlalias']              = 'article alias';
$BL['be_image_crop']                    = 'crop thumbnail';
$BL['be_image_cropit']                  = 'crop image';
$BL['be_image_align']                   = 'image alignment';
$BL['be_ctype_flashplayer']             = 'HTML5/Flash media player';
$BL['be_flashplayer_caption']           = 'caption';
$BL['be_flashplayer_thumbnail']         = 'thumbnail';
$BL['be_flashplayer_selectsize']        = 'Select player size';
$BL['be_flash_media']                   = 'Flash';
$BL['be_html5_media']                   = 'HTML5';
$BL['be_html5_h264']                    = 'MPEG/H.264';
$BL['be_html5_webm']                    = 'WebM';
$BL['be_html5_ogg']                     = 'Ogg';
$BL['be_media_format']                  = 'format';
$BL['be_media_watermark']               = 'watermark';
$BL['be_skin']                          = 'skin';
$BL['be_foreground_color']              = 'foreground color';
$BL['be_background_color']              = 'background color';
$BL['be_highlight_color']               = 'highlight color';
$BL['be_check_feuser_profile']          = 'frontend user profile';
$BL['be_check_feuser_registration']     = 'registration';
$BL['be_check_feuser_manage']           = 'managed by user';
$BL['be_hide_active_articlelink']       = 'hide active article in article menu';
$BL['be_module_search']                 = 'search also';
$BL['be_ctype_imagesspecial']           = 'images special';
$BL['be_image_WxHpx']                   = 'W x H px';
$BL['be_fx_1']                          = 'effect 1';
$BL['be_fx_2']                          = 'effect 2';
$BL['be_fx_3']                          = 'effect 3';
$BL['be_image_zoom']                    = 'zoomed view';
$BL['be_image_delete_js']               = 'Do you want to delete selected image entry?';
$BL['be_news']                          = 'News';
$BL['be_news_create']                   = 'Create news entry';
$BL['be_tags']                          = 'tags';
$BL['be_title']                         = 'title';
$BL['be_delete_dataset']                = 'Delete selected dataset?';
$BL['be_action_notvalid']               = 'Your last selected action was dropped because it was not valid!';
$BL['be_action_deleted']                = 'The selected dataset having ID {ID} was deleted.';
$BL['be_action_status']                 = 'The status of the selected dataset having ID {ID} was changed.';
$BL['be_data_select_failed']            = 'Accessing the selected data has failed. Please proof your selection.';
$BL['be_alias']                         = 'alias';
$BL['be_url_value']                     = 'URL title';
$BL['default_date_format']              = 'DD/MM/YYYY';
$BL['default_date']                     = 'd/m/Y';
$BL['default_date_delimiter']           = '/';
$BL['default_time_format']              = 'HH:MM';
$BL['default_time']                     = 'H:i';
$BL['be_place']                         = 'place';
$BL['be_teasertext']                    = 'teaser text';
$BL['be_published']                     = 'publish';
$BL['be_show_archived']                 = 'available after end date (archive)';
$BL['be_save_copy']                     = 'save entry as duplicate';
$BL['be_read_more_link']                = 'more URL/ID';
$BL['be_news_name_mandatory']           = "Fill in a news title. It's mandatory!";
$BL['be_successfully_saved']            = 'All data were saved successfully!';
$BL['be_successfully_updated']          = 'All data were updated successfully!';
$BL['be_error_while_save']              = 'Storing data failed.';
$BL['be_copyright']                     = 'copyright';
$BL['be_file_multiple_upload']          = 'multiple file upload';
$BL['be_files_select_available']        = 'Select previously uploaded files';
$BL['be_files_browse']                  = 'Browse files';
$BL['be_files_upload']                  = 'Upload selected files';
$BL['be_archive']                       = 'archive';
$BL['be_random']                        = 'random';
$BL['be_sorted']                        = 'sorted';
$BL['be_granted_download']              = 'secured frontend download only';
$BL['be_granted_feuser']                = 'Only visible for logged-in frontend users';
$BL['be_hidden_for_feuser']             = 'Hidden for logged-in frontend users';
$BL['be_visible_for_everybody']         = 'Visible for everybody (default)';
$BL['be_fileuploader_typeError']        = "{file} has an invalid extension. Valid extension(s): {extensions}.";
$BL['be_fileuploader_sizeError']        = "{file} is too large, maximum file size is {sizeLimit}.";
$BL['be_fileuploader_minSizeError']     = "{file} is too small, minimum file size is {minSizeLimit}.";
$BL['be_fileuploader_emptyError']       = "{file} is empty, please select files again without it.";
$BL['be_fileuploader_noFilesError']     = "No files to upload.";
$BL['be_fileuploader_onLeave']          = "The files are being uploaded, if you leave now the upload will be cancelled.";
$BL['be_fileuploader_dragText']         = "Drop files here to upload!";
$BL['be_fileuploader_uploadButtonText'] = 'Select files or drop here';
$BL['be_delete_selected_files']         = 'Delete selected files';
$BL['be_delete_selected_files_confirm'] = 'Do you really want to delete all selected files?';
$BL['be_ctype_tabs']                    = 'tabs';
$BL['be_tab_add']                       = 'add tab';
$BL['be_tab_name']                      = 'tab';
$BL['be_headline']                      = 'headline';
$BL['be_tab_delete_js']                 = 'Do you want to delete the selected tab?';
$BL['be_pagniate_count']                = 'items per page';
$BL['be_limit_to']                      = 'limit to';
$BL['be_archived_items']                = 'archived items';
$BL['be_include']                       = 'include';
$BL['be_exclude']                       = 'exclude';
$BL['be_solely']                        = 'solely';
$BL['be_fsearch_not']                   = 'NOT';
$BL['be_date_year']                     = 'year';
$BL['be_archive_link']                  = 'archive link';
$BL['be_use_prio']                      = 'apply priorization';
$BL['be_skip_first_items']              = 'skip top items';
$BL['be_news_detail_link']              = 'news article';
$BL['be_gallerydownload']               = 'allow download in gallery';
$BL['be_gallery_root']                  = 'gallery root directory';
$BL['be_gallery_directory']             = 'gallery subdirectory';
$BL['be_gallery']                       = 'gallery';
$BL['be_sort_date']                     = 'sort date';
$BL['group_superuser']                  = 'superuser';
$BL['group_admin']                      = 'administrator';
$BL['group_editor']                     = 'editor';
$BL['group_newsletter']                 = 'newsletter editor';
$BL['group_client']                     = 'client';
$BL['group_guest']                      = 'guest';
$BL['php_function']                     = 'php function';
$BL['article_menu_title']               = 'menu title';
$BL['content_type']                     = 'content-type';
$BL['automatic']                        = 'automatic';
$BL['random_image']                     = 'select images randomly';
$BL['limit_image_from_list']            = 'Images max.';
$BL['alt_image']                        = 'alt. image';
$BL['alt_text']                         = 'alt. text';
$BL['over']                             = 'over';
$BL['js_lib']                           = 'JS Library';
$BL['js_lib_alwaysload']                = 'always load';
$BL['frontendjs_load']                  = 'load frontend.js (more for historical reasons)';
$BL['googleapi_load']                   = 'use CDN';
$BL['fancyupload_clear_list']           = 'Clear List';
$BL['fancyupload_file_uploaded']        = 'File was uploaded';
$BL['fancyupload_file_error']           = 'An error occurred';
$BL['fancyupload_adblock_error']        = 'To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).';
$BL['fancyupload_flashblock_error']     = 'To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).';
$BL['fancyupload_required_error']       = 'A required file was not found, please be patient and we fix this.';
$BL['fancyupload_flash_error']          = 'To enable the embedded uploader, install the latest Adobe Flash plugin.';
$BL['be_cnt_function_validate']         = 'PHP validation';
$BL['be_structform_selected_cp']        = 'Limit selection of usable content parts';
$BL['be_structform_select_cp']          = 'Select content parts';
$BL['source_image_not_found']           = 'Source image error: The image %s seems not to exist';
$BL['form_force_ssl']                   = 'Force sending forms with SSL';
$BL['numerize_title']                   = 'Numbered instead of article titles';
$BL['be_article_noteaser']              = 'no teaser';
$BL['be_acat_disable301']               = 'article 301 redirect';
$BL['file_actions_step1']               = "Step 1: select folder";
$BL['file_actions_step2']               = "Step 2: select file";
$BL['file_actions_step3']               = "Step 3: select action";
$BL['file_actions_button']              = 'Perform action';
$BL['file_actions_no']                  = 'No files for editing. Please select another folder ';
$BL['file_actions_delete']              = 'Are you sure that the selected files should be deleted?';
$BL['file_actions_bemuser']             = 'The selected files will be assigned to the new user and moved to its root.';
$BL['file_actions_bemfolder']           = 'Please select the destination folder. The selected files are moved to this folder. ';
$BL['file_actions_pdl_empty']           = 'select action';
$BL['file_actions_pdl_delete']          = 'delete files';
$BL['file_actions_pdl_move']            = 'move files';
$BL['file_actions_pdl_status']          = 'change status';
$BL['file_actions_pdl_user']            = 'change owner';
$BL['file_actions_msg_move']            = 'Files were moved successfully';
$BL['file_actions_msg_delete']          = 'Files were deleted successfully';
$BL['file_actions_msg_status']          = 'The status of files successfully changed';
$BL['file_actions_msg_error']           = 'There are no files selected';
$BL['file_actions_msg_user']            = 'Files were successfully assigned to the new user';
$BL['be_imagefiles_as_gallery']         = 'create gallery from image files';
$BL['be_link']                          = 'link';
$BL['be_links']                         = 'links';
$BL['be_redirect']                      = 'redirect';
$BL['be_redirects']                     = 'redirects';
$BL['be_views']                         = 'views';
$BL['be_structure_id']                  = 'structure ID';
$BL['be_shortcut']                      = 'shortcut';
$BL['be_target_type']                   = 'target type';
$BL['be_http_status']                   = 'HTTP status';
$BL['be_http_status301']                = 'moved permanently';
$BL['be_http_status307']                = 'temporary redirect';
$BL['be_http_status404']                = 'not found';
$BL['be_http_status401']                = 'unauthorized';
$BL['be_http_status503']                = 'service unavailable';
$BL['be_redirect_error1']               = 'Alias/Shortcut, structure or article ID is required';
$BL['be_redirect_error2']               = 'Target is required';
$BL['be_redirect_error3']               = 'For target type article ID and structure ID only integers are allowed as target';
$BL['be_new_linkredirect']              = 'Add link/redirect';
$BL['be_ctype_accordion']               = 'group (accordion)';
$BL['be_ctype_number']                  = 'number';
$BL['be_inactive']                      = 'inactive';
$BL['be_locked']                        = 'locked';
$BL['be_n/a']                           = 'n/a';
$BL['be_opengraph_support']             = 'Allow Social Sharing';
$BL['be_player_volume']                 = 'Volume';
$BL['be_player_volume_muted']           = 'muted';
$BL['be_keyword']                       = 'Keyword';
$BL['be_tag']                           = 'tag';
$BL['be_system_container']              = 'system container';
$BL['be_system_container_norender']     = 'no regular frontend rendering';
$BL['be_custom_scriptlogic']            = 'custom (script logic)';
$BL['be_flush_image_cache']             = 'flush image cache';
$BL['be_caption_alt']                   = 'alt attr.';
$BL['be_caption_title']                 = 'title attr.';
$BL['be_caption_file_imagesize']        = 'WxHxC <em>(if image)</em>';
$BL['be_caption_file_title']            = 'file title';
$BL['be_caption_descr.']                = 'descr.';
$BL['be_display_html5_only']            = 'HTML5 only';
$BL['be_audio_only']                    = 'audio only';
$BL['be_hide_downloadbutton']           = 'hide HTML5 download button';
$BL['be_filter']                        = 'filter';
$BL['be_filter_with_tags']              = 'by tag';
$BL['be_filter_not_selected']           = 'no category selected';
$BL['be_empty_search_result']           = 'The search returned no results.';
$BL['confirm_cp_tab_warning']           = 'The subsection has no title and no number is assigned. The selection will get lost on save or update.';
$BL['be_canonical']                     = 'canonical link';
$BL['be_breadcrumb']                    = 'breadcrumb display behavior';
$BL['be_breadcrumb_nothidden']          = 'visible if level is hidden';
$BL['be_breadcrumb_nolink']             = 'do not link';
$BL['be_parental_alias'] = 'parental alias';
$BL['be_fsearch_nor'] = 'NONE';
$BL['be_tab_toggle'] = 'Toggle tab to expanded or closed';
$BL['be_custom_textfield'] = 'custom text';
$BL['be_tab_template_toggle_warning'] = 'Changing the template can have the effect that custom fields get changed too and existing values get lost.\n\nAre you really sure to continue?';
$BL['be_onepage_id'] = 'OnePage ID (#anchor) support';
$BL['be_onepage_template'] = 'treat as OnePage template';
$BL['be_yes'] = 'Yes';
$BL['be_no'] = 'No';
$BL['be_attr_title'] = 'title (attribute)';
$BL['be_attr_alt'] = 'alternative text';
$BL['be_ie8ignore'] = 'disable <a href="https://en.wikipedia.org/wiki/Conditional_comment" target="_blank" class="underline">conditional comments</a> for IE8';
$BL['be_cookie_consent_enable'] = 'enable Cookie Consent v2 plugin (v3 will be disabled)';
$BL['be_cookie_consent_message'] = 'consent message';
$BL['be_cookie_consent_translatable'] = 'This installation has support for multiple languages (&#36;cmsgo[&#39;allowed_lang&#39;]) enabled. For translated cookie consent texts use the <b>@@Text@@</b> syntax and check `template/template_lang` after rendering.';
$BL['cookie_consent_message'] = 'This website uses cookies to ensure you get the best experience on our website';
$BL['be_cookie_consent_dismiss'] = 'dismiss button text';
$BL['cookie_consent_dismiss'] = 'Got it!';
$BL['be_cookie_consent_more'] = 'learn more button text';
$BL['cookie_consent_more'] = 'More info';
$BL['be_cookie_consent_link'] = 'cookie policy url/alias';
$BL['be_cookie_consent_theme'] = 'theme (empty = no CSS)';
$BL['be_google_analytics_enable'] = 'use Google Analytics';
$BL['be_google_tag_manager_enable'] = 'use Google Tag Manager';
$BL['be_piwik_enable'] = 'use Matomo/Piwik';
$BL['be_tracking_anonymize'] = 'anonymize the IP';
$BL['be_tracking_cookie_flags'] = 'enable <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/cookies-user-id#cookie_flags" target="_blank"><u>cookie flags</u> (generated automatically)</a>';
$BL['be_tracking_custom_properties'] = 'custom <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/" target="_blank"><u>config parameters</u></a> (prop1: val1, prop2, val2)';
$BL['be_tracking_id'] = 'tracking ID';
$BL['be_site_id'] = 'site ID';
$BL['be_piwik_url'] = 'Matomo/Piwik URL';
$BL['be_filedownload_direct_blocked'] = 'blocked by <abbr title="%s">.htaccess</abbr>';
$BL['be_tracking_optout'] = 'support for Opt-Out cookie <i>&lt;a href=&quot;javascript:gaOptout()&quot;&gt;&lt;/a&gt;</i>';
$BL['be_require_consent'] = 'Deactivate tracking code widthout consent';
$BL['be_consent_cookie_name'] = 'Consent cookie name';
$BL['be_consent_cookie_value'] = 'Consent cookie value';
$BL['be_respect_donottrack'] = 'Respect the Do-Not-Track browser setting';
$BL['placeholder_require_cookie_name'] = 'cookieconsent_dismissed';
$BL['placeholder_require_cookie_value'] = 'yes';
$BL['be_cc_v3_enable'] = 'enable Cookie Consent v3 plugin (v2 will be disabled)';
$BL['be_cc_v3_title'] = 'cookie modal title';
$BL['cc_v3_title_placeholder'] = 'We value your privacy';
$BL['be_cc_v3_description'] = 'description';
$BL['cc_v3_description_placeholder'] = 'We use cookies to enhance your browsing experience, serve personalized ads or content, and analyze our traffic. By clicking &quot;Accept all&quot;, you consent to our use of cookies.';
$BL['be_cc_v3_accept_all'] = 'button &quot;accept all&quot;';
$BL['cc_v3_accept_all_placeholder'] = 'Accept all';
$BL['be_cc_v3_accept_necessary'] = 'button &quot;accept necessary&quot;';
$BL['cc_v3_accept_necessary_placeholder'] = 'Accept necessary';
$BL['be_cc_v3_accept_selected'] = 'button &quot;accept selected&quot;';
$BL['cc_v3_accept_selected_placeholder'] = 'Accept current selection';
$BL['be_cc_v3_reject_all'] = 'button &quot;reject all&quot;';
$BL['cc_v3_reject_all_placeholder'] = 'Reject all';
$BL['be_cc_v3_customize'] = 'button &quot;settings&quot;';
$BL['cc_v3_customize_placeholder'] = 'Customize';
$BL['be_cc_v3_link'] = 'cookie policy url/alias';
$BL['be_cc_v3_more'] = 'more info text';
$BL['be_cc_v3_theme'] = 'theme (empty = light)';
$BL['cc_v3_more_placeholder'] = 'more info';
$BL['be_cc_v3_sections'] = 'cookie sections';
$BL['be_cc_v3_sections_title'] = 'title';
$BL['be_cc_v3_sections_description'] = 'description';
$BL['be_cc_v3_sections_active'] = 'show the section';
$BL['be_cc_v3_section_general'] = 'general';
$BL['be_cc_v3_section_general_title_placeholder'] = 'Manage your cookies';
$BL['be_cc_v3_section_general_description_placeholder'] = 'We use cookies to help you navigate efficiently and perform certain functions. You will find detailed information about all cookies under each consent category below. The cookies that are categorized as &quot;Necessary&quot; are stored on your browser as they are essential for enabling the basic functionalities of the site. We also use third-party cookies that help us analyze how you use this website, store your preferences, and provide the content and advertisements that are relevant to you. These cookies will only be stored in your browser with your prior consent. You can choose to enable or disable some or all of these cookies but disabling some of them may affect your browsing experience.';
$BL['be_cc_v3_section_necessary'] = 'necessary';
$BL['be_cc_v3_section_necessary_title_placeholder'] = 'Strictly necessary cookies';
$BL['be_cc_v3_section_necessary_description_placeholder'] = 'Necessary cookies are required to enable the basic features of this site, such as providing secure log-in or adjusting your consent preferences. These cookies do not store any personally identifiable data.';
$BL['be_cc_v3_section_functional'] = 'functional';
$BL['be_cc_v3_section_functional_title_placeholder'] = 'Functional cookies';
$BL['be_cc_v3_section_functional_description_placeholder'] = 'Functionality cookies are used to improve the performance of websites, because without them certain features of the website may not be available. For example, they allow important information and user preferences to be saved. The information may include login details, region, language and enhanced content.';
$BL['be_cc_v3_section_analytics'] = 'analytics';
$BL['be_cc_v3_section_analytics_title_placeholder'] = 'Performance and Analytics cookies';
$BL['be_cc_v3_section_analytics_description_placeholder'] = 'Analytical cookies are used to understand how visitors interact with the website. These cookies help provide information on metrics such as the number of visitors, bounce rate, traffic source, etc.';
$BL['be_cc_v3_section_marketing'] = 'marketing';
$BL['be_cc_v3_section_marketing_title_placeholder'] = 'Advertising and marketing cookies';
$BL['be_cc_v3_section_marketing_description_placeholder'] = 'Advertising and marketing cookies are used to deliver advertising that is more relevant to you and your interests. May also be used to limit the number of times you see an advertisement and measure the effectiveness of advertising campaigns. Advertising networks usually place them with the permission of the website operator.';
$BL['be_cc_v3_section_social'] = 'social';
$BL['be_cc_v3_section_social_title_placeholder'] = 'Social media cookies';
$BL['be_cc_v3_section_social_description_placeholder'] = 'Social media cookies are used to understand how visitors interact with social media. These cookies may be used to deliver relevant advertising on other platforms.';
$BL['be_cc_v3_section_more'] = 'more';
$BL['be_cc_v3_section_more_title_placeholder'] = 'More information';
$BL['be_cc_v3_section_more_description_placeholder'] = 'For any queries in relation to our policy on cookies and your choices, please &lt;a class=&quot;cc__link&quot; href=&quot;#yourdomain.com&quot;&gt;contact us&lt;/a&gt;.';
$BL['be_cc_v3_builtin'] = 'built-in';
$BL['be_cc_v3_default'] = 'default';
$BL['be_cc_v3_btn_flip'] = 'flip buttons';
$BL['be_cc_v3_btn_equal'] = 'equal weight buttons';
$BL['be_cc_v3_consent_modal'] = 'consent modal';
$BL['be_cc_v3_preferences_modal'] = 'preferences modal';
$BL['be_cc_v3_layout'] = 'layout';
$BL['be_cc_v3_position'] = 'position';
$BL['be_cc_v3_top_left'] = 'top left';
$BL['be_cc_v3_top_center'] = 'top center';
$BL['be_cc_v3_top_right'] = 'top right';
$BL['be_cc_v3_middle_left'] = 'middle left';
$BL['be_cc_v3_middle_center'] = 'middle center';
$BL['be_cc_v3_middle_right'] = 'middle right';
$BL['be_cc_v3_bottom_left'] = 'bottom left';
$BL['be_cc_v3_bottom_center'] = 'bottom center';
$BL['be_cc_v3_bottom_right'] = 'bottom right';
$BL['be_cc_v3_left'] = 'left';
$BL['be_cc_v3_right'] = 'right';
$BL['be_cc_v3_top'] = 'top';
$BL['be_cc_v3_bottom'] = 'bottom';
$BL['be_cc_v3_reload_on_change'] = 'reload page after changing the cookie settings';
$BL['be_cc_v3_on_change'] = 'on change';
$BL['be_iptc_data'] = 'IPTC data';
$BL['be_iptc_as_caption'] = 'use for caption, copyright etc. as long yet unset';
$BL['iptc_ImageDescription'] = 'image description';
$BL['iptc_Copyright'] = 'copyright';
$BL['iptc_Artist'] = 'artist';
$BL['iptc_Keywords'] = 'keywords';
$BL['iptc_CountryDest'] = 'country';
$BL['iptc_ProvinceOrStateDest'] = 'region';
$BL['iptc_CityDest'] = 'city';
$BL['iptc_SublocationDest'] = 'sublocation';
$BL['iptc_ObjectName'] = 'object name';
$BL['iptc_SpecialInstructions'] = 'special instructions';
$BL['iptc_Headline'] = 'headline';
$BL['iptc_Credit'] = 'credit';
$BL['iptc_Source'] = 'source';
$BL['iptc_EditStatus'] = 'edit status';
$BL['iptc_iimCategory'] = 'category';
$BL['iptc_iimSupplementalCategory'] = 'supplemental category';
$BL['iptc_Urgency'] = 'urgency';
$BL['iptc_FixtureIdentifier'] = 'fixture identifier';
$BL['iptc_LocationDestCode'] = 'location code';
$BL['iptc_LocationDest'] = 'location';
$BL['iptc_Software'] = 'software';
$BL['iptc_SoftwareVersion'] = 'software version';
$BL['iptc_ObjectCycle'] = 'object cycle';
$BL['iptc_CountryCodeDest'] = 'country code';
$BL['iptc_OriginalTransmissionRef'] = 'original transmission';
$BL['iptc_Contact'] = 'contact';
$BL['iptc_Writer'] = 'writer';
$BL['iptc_LanguageCode'] = 'language code';
$BL['iptc_DateTimeOriginal'] = 'date/time original';
$BL['iptc_DateTimeDigitized'] = 'date/time digitized';
$BL['iptc_DateTimeReleased'] = 'date/time released';
$BL['iptc_DateTimeExpires'] = 'date/time expires';
$BL['iptc_IntellectualGenre'] = 'intellectual genre';
$BL['iptc_SubjectNewsCode'] = 'subject news code';
$BL['iptc_iimVersion'] = 'version';
$BL['be_suppress_render_caption'] = 'suppress rendering of the caption';
$BL['be_cnt_attribute_class'] = 'CSS class';
$BL['be_cnt_attribute_id'] = 'CSS id';
$BL['be_cnt_avoid_duplicates'] = 'allow unique values only';
$BL['be_not_set'] = 'not set';
$BL['be_licensed_under_GPL'] = 'Licensed under GPL.';
$BL['be_extensions_copyright'] = 'Extensions are copyright of their respective owners.';
$BL['be_allowed_filetypes'] = 'Allowed file types';
$BL['be_imagediv_template_toggle_warning'] = 'Changing the template can have the effect that custom fields get changed too and existing values get lost.\n\nAre you really sure to continue?';
$BL['be_password_show'] = 'Show password';
$BL['be_password_hide'] = 'Hide password';
$BL['be_admin_template_choose_file'] = 'Text template, alternatively select file template';
$BL['be_flashplayer_marker'] = 'Marker';
$BL['be_marker_time'] = 'Time (seconds, i.e. 10.5)';
$BL['be_marker_text'] = 'Text';
$BL['be_marker_overlaytext'] = 'Overlay text';
$BL['copy_to_clipboard'] = 'Copy to Clipboard';
$BL['url_parameter'] = 'URL parameter';
$BL['file_extension'] = 'Extension';
$BL['download_link'] = 'Download link';
$BL['disposition_attachment'] = 'Attachment';
$BL['disposition_attachment_description'] = 'direct download';
$BL['disposition_inline'] = 'Inline';
$BL['disposition_inline_description'] = 'display in browser';
$BL['be_robots'] = 'Search index';
$BL['be_robots_noindex'] = 'block search indexing (noindex)';
$BL['be_robots_nofollow'] = 'do not follow the links (nofollow)';
$BL['be_cnt_form_direct_download'] = 'allow download';
$BL['be_cnt_form_direct_download_apikey'] = 'API key';
$BL['be_cnt_form_apikey_reset'] = 'reset';
$BL['be_copy_link'] = 'copy link';
$BL['be_articlebrowser_selector'] = 'Article browser';
$BL['be_about_headline'] = 'cmsGO! content management system';
$BL['be_about_version'] = 'Version';
$BL['be_about_maintainer'] = 'Maintainer';
$BL['be_about_website'] = 'Website';
$BL['be_about_copyright'] = 'Copyright';
$BL['be_about_contributors'] = 'and contributors';
$BL['be_about_and_contributors'] = 'and other contributors (including Marcus Obst, Fernando Batista, KoMa, geckse, phalancs, q23, and others) &ndash; see <a href="https://github.com/systron-dev/cmsgo" title="Source code on GitHub" target="_blank">source code</a> for detailed copyright and license information.';
