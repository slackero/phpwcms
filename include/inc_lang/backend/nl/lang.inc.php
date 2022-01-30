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

// Language: Dutch, Language Code: nl
// Original translation by http://www.repute.nl and http://www.voskotan.com
// Major revision and final editing by http://www.argosmedia.nl (26-03-2004)
// Updated by F. de Groot 03/2007
// Please use HTML safe strings ONLY,neccessary to reduce processing time
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

// phpwcms.php
$BL['be_nav_logout']                    = 'UITLOGGEN';
$BL['be_nav_articles']                  = 'ARTIKELEN';
$BL['be_nav_files']                     = 'BESTANDSBEHEER';
$BL['be_nav_modules']                   = 'MODULES';
$BL['be_nav_messages']                  = 'BERICHTEN';
$BL['be_nav_chat']                      = 'CHATTEN';
$BL['be_nav_profile']                   = 'LOGIN GEGEVENS';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISCUSSIE';

$BL['be_page_title']                    = 'phpwcms backend (beheer)';

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
$BL['be_profile_text_newsletter']       = 'Aanmelden voor phpwcms nieuwsbrief.';
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
$BL['be_ftptakeover_needed']            = 'U dient minimaal één bestand te selecteren';
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
$BL['be_chat_info']                     = 'Hier kunt u chatten met andere phpwcms backend (administratie) gebruikers. Dit is bedoeld voor "realtime"-communicatie, maar u kunt ook een bericht achterlaten zodat andere gebruikers op een later tijdstip dit kunnen lezen.';
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
$BL['be_admin_usr_mailsubject']         = 'Welkom bij de administratiemodule (de zg. backend) van phpwcms!';
$BL['be_admin_usr_mailbody']            = "Welkom bij de administratiemodule (de zg. backend) van phpwcms!\n\n    Uw gebruikersnaam: {LOGIN}\n    Uw wachtwoord: {PASSWORD}\n\n\nU kunt hier inloggen: {LOGIN_PAGE}\n\nphpwcms admin\n ";
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
$BL['be_admin_usr_emailsubject']        = 'phpwcms account-info aangepast';
$BL['be_admin_usr_emailbody']           = "PHPWCMS GEBRUIKERS-PROFIEL AANGEPAST\n\n    uw gebruikersnaam: {LOGIN}\n    Uw wachtwoord: {PASSWORD}\n\n\nU kunt hier inloggen: {LOGIN_PAGE}\n\nphpwcms admin\n ";
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
$BL['be_cnt_results_per_page']          = 'per&nbsp;pagina (default: 25)';
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
$BL["phpwcms_code_snippets_dir_exists"]  = '<strong>LET OP!</strong> De &quot;CODE-SNIPPETS&quot; directory is nog steeds aanwezig! Verwijder de map <strong>phpwcms_code_snippets</strong> - Het is een potentieel beveiligings probleem.';

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
$BL['Version_not_up_to_date']           = 'De installatie is <b>niet</b> up to date. Er zijn updates beschikbaar. Ga naar <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a> om de laatste versie te downloaden.';
$BL['Latest_version_info']              = 'De laatste versie is <b>phpwcms %s</b>.';
$BL['Current_version_info']             = 'Versie: <b>phpwcms %s</b>.';
$BL['Connect_socket_error']             = 'Kan geen verbinding maken met de phpwcms server. De foutcode is:<br />%s';
$BL['Socket_functions_disabled']        = 'Niet mogelijk om socket functies te gebruiken.';
$BL['Mailing_list_subscribe_reminder']  = 'Schrijf je in voor de mailing list om up to date te blijve van de laatste informatie en versies. <a href="http://eepurl.com/bm-BrH" target="_blank">Abonneer</a>.';
$BL['Version_information']              = 'phpwcms Versie Informatie';

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
$BL['be_aboutlink_title']               = 'Informatie over phpwcms en licentie';

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

