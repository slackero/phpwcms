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


// Language: Danish, Language Code: dk
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'
//
// Translated by Frold og Alleykat v. 1.0
// Valid for the relaese of phpwcms (31/12/2003)
// #Changelog#
// release
// 31/12/2003


$BL['usr_online']                       = 'Brugere online';

// Login Page
$BL['login_text']                       = 'Indtast dine logindata';
$BL['login_error']                      = 'Der opstod en fejl under login!';
$BL['login_username']                   = 'brugernavn';
$BL['login_userpass']                   = 'kodeord';
$BL['login_button']                     = 'Login';
$BL['login_lang']                       = 'Sprog';

// phpwcms.php
$BL['be_nav_logout']                    = 'LOG UD';
$BL['be_nav_articles']                  = 'ARTIKLER';
$BL['be_nav_files']                     = 'FILER';
$BL['be_nav_modules']                   = 'MODULER';
$BL['be_nav_messages']                  = 'POST';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DEBAT';

$BL['be_page_title']                    = 'phpwcms administration';

$BL['be_subnav_article_center']         = 'artikeldatabase';
$BL['be_subnav_article_new']            = 'opret artikel';
$BL['be_subnav_file_center']            = 'filcentral';
$BL['be_subnav_file_ftptakeover']       = 'overtag ftp-uploadede filer';
$BL['be_subnav_mod_artists']            = 'kunstner, kategori, genre';
$BL['be_subnav_msg_center']             = 'beskeder';
$BL['be_subnav_msg_new']                = 'ny besked';
$BL['be_subnav_msg_newsletter']         = 'nyhedsbrevs abonnementer';
$BL['be_subnav_chat_main']              = 'chattens forside';
$BL['be_subnav_chat_internal']          = 'intern chat';
$BL['be_subnav_profile_login']          = 'login information';
$BL['be_subnav_profile_personal']       = 'personlige data';
$BL['be_subnav_admin_pagelayout']       = 'layouts';
$BL['be_subnav_admin_templates']        = 'skabeloner';
$BL['be_subnav_admin_css']              = 'standard css';
$BL['be_subnav_admin_sitestructure']    = 'menu og sidestruktur';
$BL['be_subnav_admin_users']            = 'administration af brugere';
$BL['be_subnav_admin_filecat']          = 'filkategorier';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'artikel-ID';
$BL['be_func_struct_preview']           = 'vis pr&oslash;ve';
$BL['be_func_struct_edit']              = 'redigér artikel';
$BL['be_func_struct_sedit']             = 'redigér dette niveau';
$BL['be_func_struct_cut']               = 'klip artikel';
$BL['be_func_struct_nocut']             = 'fortryd klip (artikel)';
$BL['be_func_struct_svisible']          = 'skift til synlig/usynlig';
$BL['be_func_struct_spublic']           = 'skift til offentlig/ikke offentlig';
$BL['be_func_struct_sort_up']           = 'flyt op';
$BL['be_func_struct_sort_down']         = 'flyt ned';
$BL['be_func_struct_del_article']       = 'slet artikel';
$BL['be_func_struct_del_jsmsg']         = 'Er du sikker p&aring; at du vil \nslette denne artikel?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'opret ny artikel i dette stukuturniveau';
$BL['be_func_struct_paste_article']     = 'inds&aelig;t artikel i dette strukturniveau';
$BL['be_func_struct_insert_level']      = 'inds&aelig;t menupunkt under';
$BL['be_func_struct_paste_level']       = 'inds&aelig;t i dette niveau';
$BL['be_func_struct_cut_level']         = 'klip strukturniveau';
$BL['be_func_struct_no_cut']            = 'Det er ikke muligt at klippe dette niveau!';
$BL['be_func_struct_no_paste1']         = 'Du kan desv&aelig;rre ikke inds&aelig;tte det her!';
$BL['be_func_struct_no_paste2']         = 'er underniveauet p&aring; linie med roden af strukturtr&aelig;et';
$BL['be_func_struct_no_paste3']         = 'der skal inds&aelig;ttes her';
$BL['be_func_struct_paste_cancel']      = 'fortryd &aelig;ndringer af strukturen';
$BL['be_func_struct_del_struct']        = 'slet dette strukturniveau';
$BL['be_func_struct_del_sjsmsg']        = 'Er du sikker p&aring; du &oslash;nsker \nat slette dette strukturniveau?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = '&aring;ben';
$BL['be_func_struct_close']             = 'luk';
$BL['be_func_struct_empty']             = 't&oslash;m';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'ren tekst';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kode';
$BL['be_ctype_textimage']               = 'tekst m. billede';
$BL['be_ctype_images']                  = 'billeder';
$BL['be_ctype_bulletlist']              = 'punkt-liste';
$BL['be_ctype_link']                    = 'link &amp; email';
$BL['be_ctype_linklist']                = 'link liste';
$BL['be_ctype_linkarticle']             = 'artikellink liste';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'filliste';
$BL['be_ctype_emailform']               = 'emailformular';
$BL['be_ctype_newsletter']              = 'nyhedsbrev';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profilen er nu &aelig;ndret.';
$BL['be_profile_create_error']          = 'Der opstod desv&aelig;rre en fejl, pr&oslash;v igen.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profildata er nu opdateret.';
$BL['be_profile_update_error']          = 'Der opstod desv&aelig;rre en fejl, pr&oslash;v igen.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'brugnavn {VAL} er ugyldigt';
$BL['be_profile_account_err2']          = 'kodeordet er for kort (kun {VAL} tegn og mindst 5 tegn)';
$BL['be_profile_account_err3']          = 'kodeordet og det gentagne kodeord skal v&aelig;re ens';
$BL['be_profile_account_err4']          = 'emailen {VAL} er ugyldig';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'Dine personlige data';
$BL['be_profile_data_text']             = 'Dine personlige data kan hj&aelig;lpe andre brugere og sidens bes&oslash;gende til finde ud af hvem du er, samt hvad dine interesser og kvalifikationer er. Hvis du krydser af i de rigtige checkboxe kan brugere se din profilinformation i det offentlige omr&aring;de og/eller p&aring; artikelsiderne - afh&aelig;ngigt af dine instillinger.';
$BL['be_profile_label_title']           = 'titel';
$BL['be_profile_label_firstname']       = 'fornavn';
$BL['be_profile_label_name']            = 'efternavn';
$BL['be_profile_label_company']         = 'firma';
$BL['be_profile_label_street']          = 'adresse';
$BL['be_profile_label_city']            = 'by';
$BL['be_profile_label_state']           = 'provins, stat';
$BL['be_profile_label_zip']             = 'postnr';
$BL['be_profile_label_country']         = 'land';
$BL['be_profile_label_phone']           = 'telefonnr';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobil';
$BL['be_profile_label_signature']       = 'underskrift';
$BL['be_profile_label_notes']           = 'noter';
$BL['be_profile_label_profession']      = 'profession';
$BL['be_profile_label_newsletter']      = 'nyhedsbreve';
$BL['be_profile_text_newsletter']       = 'Jeg &oslash;nsker at modtage det generelle phpwcms nyhedsbrev.';
$BL['be_profile_label_public']          = 'offentlig';
$BL['be_profile_text_public']           = 'Alle skal kunne se min personlige profil.';
$BL['be_profile_label_button']          = 'opdatér dine personlige data';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'din logininformation';
$BL['be_profile_account_text']          = 'Normalt er det ikke n&oslash;dvendigt at &aelig;ndre dit brugernavn.<br />Men det anbefales at du &aelig;ndre dit kodeord fra tid til anden for at &oslash;ge sikkerheden omkring din profil.';
$BL['be_profile_label_err']             = 'markér venligst';
$BL['be_profile_label_username']        = 'brugernavn';
$BL['be_profile_label_newpass']         = 'kodeord';
$BL['be_profile_label_repeatpass']      = 'gentag kodeord';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'opdatér logindata';
$BL['be_profile_label_lang']            = 'sprog';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'overtag filer der er uploadet via ftp';
$BL['be_ftptakeover_mark']              = 'markér';
$BL['be_ftptakeover_available']         = 'tilg&aelig;ngelige filer';
$BL['be_ftptakeover_size']              = 'st&oslash;rrelse';
$BL['be_ftptakeover_nofile']            = 'der er ikke nogle filer tilg&aelig;ngelige &#8211; du skal uploade mindst én til den angivne ftp overtagelsesmappe';
$BL['be_ftptakeover_all']               = 'ALLE';
$BL['be_ftptakeover_directory']         = 'mappe';
$BL['be_ftptakeover_rootdir']           = 'rodmappen';
$BL['be_ftptakeover_needed']            = 'n&oslash;dvendigt!!! (du skal v&aelig;lge mindst én)';
$BL['be_ftptakeover_optional']          = 'valgfri';
$BL['be_ftptakeover_keywords']          = 'n&oslash;gleord';
$BL['be_ftptakeover_additional']        = 'filkommentar';
$BL['be_ftptakeover_longinfo']          = 'lang info';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'aktiv';
$BL['be_ftptakeover_public']            = 'offentlig';
$BL['be_ftptakeover_createthumb']       = 'opret thumbnail';
$BL['be_ftptakeover_button']            = 'overtag de valgt filer';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'filcentral';
$BL['be_ftab_createnew']                = 'opret ny mappe i roden';
$BL['be_ftab_paste']                    = 'inds&aelig;t fil fra klipbordet i rodmappen';
$BL['be_ftab_disablethumb']             = 'frav&aelig;lg thumbnails i listen';
$BL['be_ftab_enablethumb']              = 'acceptér thumbnails i listen';
$BL['be_ftab_private']                  = 'private&nbsp;filer';
$BL['be_ftab_public']                   = 'offentlige&nbsp;filer';
$BL['be_ftab_search']                   = 's&oslash;g';
$BL['be_ftab_trash']                    = 'skraldespand';
$BL['be_ftab_open']                     = '&aring;ben alle mapper';
$BL['be_ftab_close']                    = 'luk alle &aring;bne mapper';
$BL['be_ftab_upload']                   = 'upload fil til rodmappen';
$BL['be_ftab_filehelp']                 = '&aring;ben hj&aelig;lp til filer';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'rodmappen';
$BL['be_fpriv_title']                   = 'opret ny mappe';
$BL['be_fpriv_inside']                  = 'inde i';
$BL['be_fpriv_error']                   = 'fejl: udfyld navnet p&aring; mappen';
$BL['be_fpriv_name']                    = 'navn';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'opret ny mappe';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'redigér mappe';
$BL['be_fpriv_newname']                 = 'nyt navn';
$BL['be_fpriv_updatebutton']            = 'opdatér mappe info';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'V&aelig;lg en fil du &oslash;nsker at uploade';
$BL['be_fprivup_err2']                  = 'St&oslash;rrelsen af den uploadede fil er st&oslash;rrren end';
$BL['be_fprivup_err3']                  = 'Fejl under overf&oslash;rslen af filen';
$BL['be_fprivup_err4']                  = 'Fejl under oprettelsen af bruger mappe.';
$BL['be_fprivup_err5']                  = 'thumbnail findes ikke';
$BL['be_fprivup_err6']                  = 'Pr&oslash;v venligt ikke igen - dette er en fejl p&aring; serveren! Kontakt din <a href="mailto:{VAL}">webmaster</a> hurtigst muligt, hvis du &oslash;nsker at denne funktion skal v&aelig;re tilg&aelig;ngelig!';
$BL['be_fprivup_title']                 = 'upload filer';
$BL['be_fprivup_button']                = 'upload filer';
$BL['be_fprivup_upload']                = 'upload';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'redigér filinformation';
$BL['be_fprivedit_filename']            = 'filnavn';
$BL['be_fprivedit_created']             = 'oprettet';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'filens faktiske navn (s&aelig;t tilbage til oprindelige)';
$BL['be_fprivedit_clockwise']           = 'rotér thumbnail med uret [original fil +90&grader;]';
$BL['be_fprivedit_cclockwise']          = 'rotér thumbnail mod uret [original fil -90&grader;]';
$BL['be_fprivedit_button']              = 'opdatér filinfo';
$BL['be_fprivedit_size']                = 'st&oslash;rrelse';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'upload fil til mappen';
$BL['be_fprivfunc_makenew']             = 'opret ny mappe inden i denne';
$BL['be_fprivfunc_paste']               = 'inds&aelig;t fil fra klipbordet i denne mappe';
$BL['be_fprivfunc_edit']                = 'redigér mappe';
$BL['be_fprivfunc_cactive']             = 'skift til aktiv/inaktiv';
$BL['be_fprivfunc_cpublic']             = 'skift til offentlig/ikke offentlig';
$BL['be_fprivfunc_deldir']              = 'slet mappe';
$BL['be_fprivfunc_jsdeldir']            = '&oslash;nsker du virkelig \nat slette denne mappe?';
$BL['be_fprivfunc_notempty']            = 'mappen {VAL} er ikke tom!';
$BL['be_fprivfunc_opendir']             = '&aring;ben mappe';
$BL['be_fprivfunc_closedir']            = 'luk mappe';
$BL['be_fprivfunc_dlfile']              = 'hent fil';
$BL['be_fprivfunc_clipfile']            = 'klippebords fil';
$BL['be_fprivfunc_cutfile']             = 'klip';
$BL['be_fprivfunc_editfile']            = 'redigér filinfo';
$BL['be_fprivfunc_cactivefile']         = 'skift til aktiv/inaktiv';
$BL['be_fprivfunc_cpublicfile']         = 'skift til offentlig/ikke offentlig';
$BL['be_fprivfunc_movetrash']           = 'overf&oslash;r til skraldespand';
$BL['be_fprivfunc_jsmovetrash1']        = '&oslash;nsker du virkelig at overf&oslash;re';
$BL['be_fprivfunc_jsmovetrash2']        = 'til skraldespanden?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'ingen private filer eller mapper';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'bruger';
$BL['be_fpublic_nofiles']               = 'ingen offentlige filer eller mapper';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'skraldespanden er tom';
$BL['be_ftrash_show']                   = 'vis private filer';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = '&oslash;nsker du at gendanne {VAL} \nog overf&oslash;re den tilbage til den private liste?';
$BL['be_ftrash_delete']                 = '&oslash;nsker du at slette {VAL}?';
$BL['be_ftrash_undo']                   = 'gendan (fortryd skraldespand)';
$BL['be_ftrash_delfinal']               = 'sidste sletning';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 's&oslash;gefeltet er tomt.';
$BL['be_fsearch_title']                 = 's&oslash;g filer';
$BL['be_fsearch_infotext']              = 'Dette er en basis s&oslash;gning efter filinformation. Der krydss&oslash;ges efter oplyste n&oslash;gleord,<br />filnavne og i den lange filinfo. Der er ingen underst&oslash;ttelse af wildcards, s&aring; der skal s&oslash;ges p&aring; det pr&aelig;cise ord. Adskil s&oslash;geord med et mellemrum og v&aelig;lg OG/ELLER og hvorvidt der<br />skal s&oslash;ges efter private/offentlige filer.';
$BL['be_fsearch_nonfound']              = 'Der blev ikke fundet nogen filer matchnende din s&oslash;gning. Ret dine s&oslash;gev&aelig;rdier og pr&oslash;v igen.';
$BL['be_fsearch_fillin']                = 'udfyld venligt s&oslash;gefeltet ovenfor.';
$BL['be_fsearch_searchlabel']           = 's&oslash;g efter';
$BL['be_fsearch_startsearch']           = 'start s&oslash;gning';
$BL['be_fsearch_and']                   = 'OG';
$BL['be_fsearch_or']                    = 'ELLER';
$BL['be_fsearch_all']                   = 'alle filer';
$BL['be_fsearch_personal']              = 'private';
$BL['be_fsearch_public']                = 'offentlige';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'intern chat';
$BL['be_chat_info']                     = 'Her kan du chatte med andre brugere der har adgang til administrationen af phpwcms.';
$BL['be_chat_start']                    = 'klik her for at g&aring; til chatten';
$BL['be_chat_lines']                    = 'antal chatlinier';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'beskedcentral';
$BL['be_msg_new']                       = 'nye';
$BL['be_msg_old']                       = 'gamle';
$BL['be_msg_senttop']                   = 'sendt';
$BL['be_msg_del']                       = 'slettet';
$BL['be_msg_from']                      = 'fra';
$BL['be_msg_subject']                   = 'emne';
$BL['be_msg_date']                      = 'dato/tid';
$BL['be_msg_close']                     = 'luk besked';
$BL['be_msg_create']                    = 'opret ny besked';
$BL['be_msg_reply']                     = 'besvar denne besked';
$BL['be_msg_move']                      = 'flyt denne besked til skraldespanden';
$BL['be_msg_unread']                    = 'ul&aelig;st eller ny besked';
$BL['be_msg_lastread']                  = 'seneste {VAL} l&aelig;ste beskeder';
$BL['be_msg_lastsent']                  = 'seneste {VAL} sendte beskeder';
$BL['be_msg_marked']                    = 'beskeder makeret klar til at blive slettet (skraldespand)';
$BL['be_msg_nomsg']                     = 'ingen beskeder i denne mappe';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'SV';
$BL['be_msg_by']                        = 'sendt af';
$BL['be_msg_on']                        = 'den';
$BL['be_msg_msg']                       = 'besked';
$BL['be_msg_err1']                      = 'du har glemt at angive en modtager...';
$BL['be_msg_err2']                      = 'udfyld emnefeltet, s&aring; kan modtageren bedre holde styr p&aring; sine beskeder :-)';
$BL['be_msg_err3']                      = 'det giver ikke megen mening at sende en besked uden selve beskeden ;-)';
$BL['be_msg_sent']                      = 'den nye besked er sendt!';
$BL['be_msg_fwd']                       = 'du vil blive viderestillet til beskedcentralen eller';
$BL['be_msg_newmsgtitle']               = 'skriv ny besked';
$BL['be_msg_err']                       = 'fejl da beskeden skulle sendes';
$BL['be_msg_sendto']                    = 'send beskeden til';
$BL['be_msg_available']                 = 'mulige modtagere';
$BL['be_msg_all']                       = 'send besked til alle valgte modtagere';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'abonnementer p&aring; nyhedsbreve';
$BL['be_newsletter_titleedit']          = 'redigér abonnement p&aring; nyhedsbrev';
$BL['be_newsletter_new']                = 'opret ny';
$BL['be_newsletter_add']                = 'tilf&oslash;j&nbsp;abonnement&nbsp;p&aring;&nbsp;nyhedsbrev';
$BL['be_newsletter_name']               = 'navn';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'gem abonnement';
$BL['be_newsletter_button_cancel']      = 'fortryd';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'brugernavnet er ugyldigt - v&aelig;lg venligst et andet';
$BL['be_admin_usr_err2']                = 'brugernavnet er ikke udfyldt (skal udfyldes)';
$BL['be_admin_usr_err3']                = 'password er ikke angivet (skal angives)';
$BL['be_admin_usr_err4']                = "email-adressen er ikke gyldig";
$BL['be_admin_usr_err']                 = 'fejl';
$BL['be_admin_usr_mailsubject']         = 'velkommen til administrationen af phpwcms';
$BL['be_admin_usr_mailbody']            = "VELKOMMEN TIL ADMINISTRATIONEN AF  HPWCMS\n\n    brugernavn: {LOGIN}\n    password: {PASSWORD}\n\n\nDu kan logge ind her: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'tilf&oslash;j ny bruger';
$BL['be_admin_usr_realname']            = 'rigtige navn';
$BL['be_admin_usr_setactive']           = 's&aelig;t til at v&aelig;re aktiv';
$BL['be_admin_usr_iflogin']             = 'hvis kryds her kan brugeren logge ind';
$BL['be_admin_usr_isadmin']             = 'brugeren er admin';
$BL['be_admin_usr_ifadmin']             = 'hvis kryds her har brugeren samme rettigheder som administratoren';
$BL['be_admin_usr_verify']              = 'bekr&aelig;ftelse';
$BL['be_admin_usr_sendemail']           = 'send en email til den nye bruger med dennes konto information';
$BL['be_admin_usr_button']              = 'send brugerdata';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'redigér brugerkonto';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - kontodata er &aelig;ndret';
$BL['be_admin_usr_emailbody']           = "PHPWCMS BRUGERINFORMATION &aelig;NDRET\n\n    brugernavn: {LOGIN}\n    password: {PASSWORD}\n\n\nDu kan logge ind her: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[INGEN &aelig;NDRINGER - BRUG BLOT DET KENDTE PASSWORD]';
$BL['be_admin_usr_ebutton']             = 'opdatér brugerdata';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'liste over brugere i phpwcms';
$BL['be_admin_usr_ldel']                = 'OBS!&#13Dette vil slette flg. bruger permanent:';
$BL['be_admin_usr_create']              = 'opret ny bruger';
$BL['be_admin_usr_editusr']             = 'redigér brugeren';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'sidestruktur';
$BL['be_admin_struct_child']            = '(under)';
$BL['be_admin_struct_index']            = 'index (websidens start)';
$BL['be_admin_struct_cat']              = 'titel p&aring; kategori';
$BL['be_admin_struct_hide1']            = 'skjul';
$BL['be_admin_struct_hide2']            = 'vis&nbsp;denne&nbsp;kategori&nbsp;i&nbsp;menuen';
$BL['be_admin_struct_info']             = 'kategoriens infotext';
$BL['be_admin_struct_template']         = 'skabelon';
$BL['be_admin_struct_alias']            = 'alias for denne kategori';
$BL['be_admin_struct_visible']          = 'synlig';
$BL['be_admin_struct_button']           = 'godkend';
$BL['be_admin_struct_close']            = 'luk';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'filkategorier';
$BL['be_admin_fcat_err']                = 'kategoriens navn er ikke angivet!';
$BL['be_admin_fcat_name']               = 'kategoriens navn';
$BL['be_admin_fcat_needed']             = 'skal udfyldes';
$BL['be_admin_fcat_button1']            = 'opdatér';
$BL['be_admin_fcat_button2']            = 'opret';
$BL['be_admin_fcat_delmsg']             = 'Sikker p&aring; at du vil\nslette filn&oslash;gle?';
$BL['be_admin_fcat_fcat']               = 'filens kategori';
$BL['be_admin_fcat_err1']               = 'filn&oslash;gle er ikke angivet!';
$BL['be_admin_fcat_fkeyname']           = 'filn&oslash;glens navn';
$BL['be_admin_fcat_exit']               = 'luk';
$BL['be_admin_fcat_addkey']             = 'tilf&oslash;j ny n&oslash;gle';
$BL['be_admin_fcat_editcat']            = 'redigér kategoriens navn';
$BL['be_admin_fcat_delcatmsg']          = '&oslash;nsker du virkelig\nat slette filkategorien?';
$BL['be_admin_fcat_delcat']             = 'slet filens kategori';
$BL['be_admin_fcat_delkey']             = 'slet filn&oslash;glens navn';
$BL['be_admin_fcat_editkey']            = 'redigér n&oslash;gle';
$BL['be_admin_fcat_addcat']             = 'opret ny filkategori';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend ops&aelig;tning: sidens layout';
$BL['be_admin_page_align']              = 'placering';
$BL['be_admin_page_align_left']         = 'standard er at siden venstre-stilles';
$BL['be_admin_page_align_center']       = 'centré hele sidens indhold';
$BL['be_admin_page_align_right']        = 'h&oslash;jrestil hele sidens indhold';
$BL['be_admin_page_margin']             = 'margin';
$BL['be_admin_page_top']                = 'top';
$BL['be_admin_page_bottom']             = 'bund';
$BL['be_admin_page_left']               = 'venstre';
$BL['be_admin_page_right']              = 'h&oslash;jre';
$BL['be_admin_page_bg']                 = 'baggrund';
$BL['be_admin_page_color']              = 'farve';
$BL['be_admin_page_height']             = 'h&oslash;jde';
$BL['be_admin_page_width']              = 'bredde';
$BL['be_admin_page_main']               = 'main';
$BL['be_admin_page_leftspace']          = 'venstre afstand';
$BL['be_admin_page_rightspace']         = 'h&oslash;jre afstand';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'billede';
$BL['be_admin_page_text']               = 'tekst';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'bes&oslash;gte';
$BL['be_admin_page_pagetitle']          = 'sidens titel';
$BL['be_admin_page_addtotitle']         = 'tilf&oslash;j&nbsp;til&nbsp;titel';
$BL['be_admin_page_category']           = 'kategori';
$BL['be_admin_page_articlename']        = 'artikelens navn';
$BL['be_admin_page_blocks']             = 'blokke';
$BL['be_admin_page_allblocks']          = 'alle blokke';
$BL['be_admin_page_col1']               = '3-kolonne layout';
$BL['be_admin_page_col2']               = '2-kolonne layout (hovedramme til h&oslash;jre, navigationsramme til venstre)';
$BL['be_admin_page_col3']               = '2-kolonne layout (hovedramme til venstre, navigationsramme til h&oslash;jre)';
$BL['be_admin_page_col4']               = '1-kolonne layout';
$BL['be_admin_page_header']             = 'sidehoved';
$BL['be_admin_page_footer']             = 'sidefod';
$BL['be_admin_page_topspace']           = 'top&nbsp;afstand';
$BL['be_admin_page_bottomspace']        = 'bund&nbsp;afstand';
$BL['be_admin_page_button']             = 'opdatér sidens layout';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend setup: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'gem css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'phpwcms frontend skabeloner';
$BL['be_admin_tmpl_default']            = 'standard';
$BL['be_admin_tmpl_add']                = 'tilf&oslash;j&nbsp;skabelon';
$BL['be_admin_tmpl_edit']               = 'redigér skabelon';
$BL['be_admin_tmpl_new']                = 'opret ny';
$BL['be_admin_tmpl_css']                = 'css fil';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'fejlside';
$BL['be_admin_tmpl_button']             = 'gem skabelon';
$BL['be_admin_tmpl_name']               = 'navn';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'sidestruktur og artikelliste';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'Der er ikke angivet nogen titel for denne artikel';
$BL['be_article_err2']                  = 'startdatoen er ikke gyldig - s&aelig;t til nu';
$BL['be_article_err3']                  = 'slutdatoen er ikke gyldig - s&aelig;t til nu';
$BL['be_article_title1']                = 'artiklens basisinformation';
$BL['be_article_cat']                   = 'kategori';
$BL['be_article_atitle']                = 'overskrift';
$BL['be_article_asubtitle']             = 'undertext';
$BL['be_article_abegin']                = 'vises fra';
$BL['be_article_aend']                  = 'vises ikke l&aelig;ngere fra';
$BL['be_article_aredirect']             = 'videresend til';
$BL['be_article_akeywords']             = 'n&oslash;gleord';
$BL['be_article_asummary']              = 'brø&oslash;dtekst';
$BL['be_article_abutton']               = 'opret ny artikel';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'du har oplyst en ikke gyldig slutdato - sat til nu + 1 uge';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'ret artiklens basisinformation';
$BL['be_article_eslastedit']            = '&aelig;ndret';
$BL['be_article_esnoupdate']            = 'formularen er ikke opdateret';
$BL['be_article_esbutton']              = 'opdatér artikelens data';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'artikelinformation';
$BL['be_article_cnt_type']              = 'indholdstype';
$BL['be_article_cnt_space']             = 'mellemrum';
$BL['be_article_cnt_before']            = 'f&oslash;r';
$BL['be_article_cnt_after']             = 'efter';
$BL['be_article_cnt_top']               = 'top';
$BL['be_article_cnt_ctitle']            = 'overskrift';
$BL['be_article_cnt_back']              = 'komplet artikelinfo';
$BL['be_article_cnt_button1']           = 'opdatér indhold';
$BL['be_article_cnt_button2']           = 'opret indhold';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'artikelens information';
$BL['be_article_cnt_ledit']             = 'redigér artikel';
$BL['be_article_cnt_lvisible']          = 'skift til synlig/usynlig';
$BL['be_article_cnt_ldel']              = 'slet denne artikel';
$BL['be_article_cnt_ldeljs']            = 'Slet artiklen?';
$BL['be_article_cnt_redirect']          = 'videresend til';
$BL['be_article_cnt_edited']            = 'redigeret af';
$BL['be_article_cnt_start']             = 'vises fra';
$BL['be_article_cnt_end']               = 'vises til';
$BL['be_article_cnt_add']               = 'tilf&oslash;j nyt artikelindhold';
$BL['be_article_cnt_up']                = 'flyt indhold op';
$BL['be_article_cnt_down']              = 'flyt indhold ned';
$BL['be_article_cnt_edit']              = 'redigér denne del af artiklen';
$BL['be_article_cnt_delpart']           = 'slet denne del af artiklen';
$BL['be_article_cnt_delpartjs']         = 'Slet denne del?';
$BL['be_article_cnt_center']            = 'artikeldatabase';

// content forms
$BL['be_cnt_plaintext']                 = 'ren tekst';
$BL['be_cnt_htmltext']                  = 'html tekst';
$BL['be_cnt_image']                     = 'billede';
$BL['be_cnt_position']                  = 'placering';
$BL['be_cnt_pos0']                      = 'Over, ventre';
$BL['be_cnt_pos1']                      = 'Over, midtfor';
$BL['be_cnt_pos2']                      = 'Over, h&oslash;jre';
$BL['be_cnt_pos3']                      = 'Under, vensre';
$BL['be_cnt_pos4']                      = 'Under, midtfor';
$BL['be_cnt_pos5']                      = 'Under, h&oslash;jre';
$BL['be_cnt_pos6']                      = 'I tekst, ventre';
$BL['be_cnt_pos7']                      = 'I tekst, h&oslash;jre';
$BL['be_cnt_pos0i']                     = 'inds&aelig;t billedet over teksten og til venste herfor';
$BL['be_cnt_pos1i']                     = 'inds&aelig;t billedet midt over teksten';
$BL['be_cnt_pos2i']                     = 'inds&aelig;t billedet over teksten og til h&oslash;jre herfor';
$BL['be_cnt_pos3i']                     = 'inds&aelig;t billedet under teksten og til ventre herfor';
$BL['be_cnt_pos4i']                     = 'inds&aelig;t billedet midt under teksten';
$BL['be_cnt_pos5i']                     = 'inds&aelig;t billedet under teksten og til h&oslash;jre herfor';
$BL['be_cnt_pos6i']                     = 'inds&aelig;t billedet til ventre med teksten omkring';
$BL['be_cnt_pos7i']                     = 'inds&aelig;t billedet til h&oslash;jre med teksten omkring';
$BL['be_cnt_maxw']                      = 'max.&nbsp;bredde';
$BL['be_cnt_maxh']                      = 'max.&nbsp;h&oslash;jde';
$BL['be_cnt_enlarge']                   = 'tillad&nbsp;forst&oslash;rrelse&nbsp;ved&nbsp;klik';
$BL['be_cnt_caption']                   = 'billedtekst';
$BL['be_cnt_subject']                   = 'emne';
$BL['be_cnt_recipient']                 = 'modtager';
$BL['be_cnt_buttontext']                = 'undertekst';
$BL['be_cnt_sendas']                    = 'send som';
$BL['be_cnt_text']                      = 'tekst';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'formular felter';
$BL['be_cnt_code']                      = 'kode';
$BL['be_cnt_infotext']                  = 'infotekst';
$BL['be_cnt_subscription']              = 'modtagere';
$BL['be_cnt_labelemail']                = 'label&nbsp;email';
$BL['be_cnt_tablealign']                = 'tabel&nbsp;placering';
$BL['be_cnt_labelname']                 = 'label&nbsp;navn';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;modtagere';
$BL['be_cnt_allsubsc']                  = 'alle&nbsp;modt.';
$BL['be_cnt_default']                   = 'standard';
$BL['be_cnt_left']                      = 'ventre';
$BL['be_cnt_center']                    = 'centrér';
$BL['be_cnt_right']                     = 'h&oslash;jre';
$BL['be_cnt_buttontext']                = 'undertekst';
$BL['be_cnt_successtext']               = 'udf&oslash;rt-tekst';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = '&aelig;ndre.email';
$BL['be_cnt_openimagebrowser']          = '&aring;ben billedbrowser';
$BL['be_cnt_openfilebrowser']           = '&aring;ben filbrowser';
$BL['be_cnt_sortup']                    = 'flyt op';
$BL['be_cnt_sortdown']                  = 'flyt ned';
$BL['be_cnt_delimage']                  = 'fjern valgte billed';
$BL['be_cnt_delfile']                   = 'fjern valgte fil';
$BL['be_cnt_delmedia']                  = 'fjern valgte medier';
$BL['be_cnt_column']                    = 'kolonne';
$BL['be_cnt_imagespace']                = 'billedplads';
$BL['be_cnt_directlink']                = 'direkte link';
$BL['be_cnt_target']                    = 'target';
$BL['be_cnt_target1']                   = 'i et nyt vindue';
$BL['be_cnt_target2']                   = 'i hovedrammen af det nye vindue';
$BL['be_cnt_target3']                   = 'i samme vindue uden rammer';
$BL['be_cnt_target4']                   = 'i samme ramme eller vindue';
$BL['be_cnt_bullet']                    = 'punkt-liste';
$BL['be_cnt_linklist']                  = 'link-liste';
$BL['be_cnt_plainhtml']                 = 'ren html';
$BL['be_cnt_files']                     = 'filer';
$BL['be_cnt_description']               = 'beskrivelse';
$BL['be_cnt_linkarticle']               = 'artikellink liste';
$BL['be_cnt_articles']                  = 'artikler';
$BL['be_cnt_movearticleto']             = 'flyt valgte artikler til artikellink liste';
$BL['be_cnt_removearticleto']           = 'fjern valgte artikler fra artikellink liste';
$BL['be_cnt_mediatype']                 = 'medietype';
$BL['be_cnt_control']                   = 'kontrol';
$BL['be_cnt_showcontrol']               = 'vis kontrolbar';
$BL['be_cnt_autoplay']                  = 'afspil automatisk';
$BL['be_cnt_source']                    = 'filplacering';
$BL['be_cnt_internal']                  = 'internt';
$BL['be_cnt_openmediabrowser']          = '&aring;ben mediebrowser';
$BL['be_cnt_external']                  = 'eksternt';
$BL['be_cnt_mediapos0']                 = 'ventre (standard)';
$BL['be_cnt_mediapos1']                 = 'centrér';
$BL['be_cnt_mediapos2']                 = 'h&oslash;jre';
$BL['be_cnt_mediapos3']                 = 'blok, vensre';
$BL['be_cnt_mediapos4']                 = 'blok, h&oslash;jre';
$BL['be_cnt_mediapos0i']                = 'inds&aelig;t medie over teksten og til venste herfor';
$BL['be_cnt_mediapos1i']                = 'inds&aelig;t medie midt over teksten';
$BL['be_cnt_mediapos2i']                = 'inds&aelig;t medie over teksten og til h&oslash;jre herfor';
$BL['be_cnt_mediapos3i']                = 'inds&aelig;t medie til ventre med teksten omkring';
$BL['be_cnt_mediapos4i']                = 'inds&aelig;t medie til h&oslash;jre med teksten omkring';
$BL['be_cnt_setsize']                   = 'v&aelig;lg st&oslash;rrelse';
$BL['be_cnt_set1']                      = 's&aelig;t mediest&oslash;rrelse til 160x120px';
$BL['be_cnt_set2']                      = 's&aelig;t mediest&oslash;rrelse til 240x180px';
$BL['be_cnt_set3']                      = 's&aelig;t mediest&oslash;rrelse til 320x240px';
$BL['be_cnt_set4']                      = 's&aelig;t mediest&oslash;rrelse til 480x360px';
$BL['be_cnt_set5']                      = 'ryd mediebredde og h&oslash;jde';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'opret nyt sidelayout';
$BL['be_admin_page_name']               = 'layoutets navn';
$BL['be_admin_page_edit']               = 'redigér sidelayout';
$BL['be_admin_page_render']             = 'opstilling';
$BL['be_admin_page_table']              = 'tabel';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'brugerdefineret';
$BL['be_admin_page_custominfo']         = 'brug v&aelig;rdi fra hovedblokken';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'Der er intet sidelayout tilg&aelig;ngeligt!';

// added: 31-12-2003
$BL['be_ctype_search'] = 's&oslash;ge-form';
$BL['be_cnt_results'] = 'resulter';
$BL['be_cnt_results_per_page'] = 'pr&nbsp;side (hvis tom vises 25)';
$BL['be_cnt_opennewwin'] = '&aring;ben link i nyt vindue';
$BL['be_cnt_searchlabeltext'] = 'Dette er forudbestemte v&aelig;rdier og tekst for s&aring;vel s&oslash;geresultater, som s&oslash;geformularen.';
$BL['be_cnt_input'] = 'input';
$BL['be_cnt_style'] = 'style';
$BL['be_cnt_result'] = 'resulter';
$BL['be_cnt_next'] = 'n&aelig;ste';
$BL['be_cnt_previous'] = 'foreg&aring;ende';
$BL['be_cnt_align'] = 'placering';
$BL['be_cnt_searchformtext'] = 'Den f&oslash;lgende tekst vises under brugen af s&oslash;geforumularen';
$BL['be_cnt_intro'] = 'intro';
$BL['be_cnt_noresult'] = 'intet resultat';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'frav&aelig;lg';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'artikel admin';
$BL['be_article_adminuser']             = 'brugeradmin';
$BL['be_article_username']              = 'forfatter';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'kun synlig for brugere der er logget in';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';

