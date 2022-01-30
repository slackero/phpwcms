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


// Language: Norwegian
// Language Code: NO
//
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'
//
// Translated by Rolf Dahl  rolf@dahltech.no
// Last updated 4.jan 2004
// Entries from around line 675 to 960 updated 3. aug 2005 by J.Haugland jorn@igang.no



$BL['usr_online']                       = 'Brukere  online';

// Login Page
$BL["login_text"]                       = 'Tast inn login data';
$BL['login_error']                      = 'Feil ved innlogging!';
$BL["login_username"]                   = 'brukernavn';
$BL["login_userpass"]                   = 'passord';
$BL["login_button"]                     = 'Logg inn';
$BL["login_lang"]                       = 'spr&#229;k';

// phpwcms.php
$BL['be_nav_logout']                    = 'LOGG UT';
$BL['be_nav_articles']                  = 'ARTIKLER';
$BL['be_nav_files']                     = 'FILER';
$BL['be_nav_modules']                   = 'MODULER';
$BL['be_nav_messages']                  = 'BESKJEDER';
$BL['be_nav_chat']                      = 'PRAT';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISKUTER';

$BL['be_page_title']                    = 'phpwcms administrasjon';

$BL['be_subnav_article_center']         = 'Artikkelsenter';
$BL['be_subnav_article_new']            = 'Ny artikkel';
$BL['be_subnav_file_center']            = 'fil senter';
$BL['be_subnav_file_ftptakeover']       = 'hent filer fra ftp mappe';
$BL['be_subnav_mod_artists']            = 'artist, kategori, genre';
$BL['be_subnav_msg_center']             = 'Beskjed senter';
$BL['be_subnav_msg_new']                = 'Ny beskjed';
$BL['be_subnav_msg_newsletter']         = 'nyhetsbrev abonnementer';
$BL['be_subnav_chat_main']              = 'hovedside for prat';
$BL['be_subnav_chat_internal']          = 'intern prat';
$BL['be_subnav_profile_login']          = 'login informasjon';
$BL['be_subnav_profile_personal']       = 'personlige data';
$BL['be_subnav_admin_pagelayout']       = 'side layout';
$BL['be_subnav_admin_templates']        = 'maler';
$BL['be_subnav_admin_css']              = 'standard css';
$BL['be_subnav_admin_sitestructure']    = 'meny oppsett';
$BL['be_subnav_admin_users']            = 'brukeradministrasjon';
$BL['be_subnav_admin_filecat']          = 'filkategorier';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'artikkel ID';
$BL['be_func_struct_preview']           = 'forh&#229;ndsvising';
$BL['be_func_struct_edit']              = 'redigere artikkel';
$BL['be_func_struct_sedit']             = 'redigere dette niv&#229;';
$BL['be_func_struct_cut']               = 'klipp artikkel';
$BL['be_func_struct_nocut']             = 'angre klipp artikkel';
$BL['be_func_struct_svisible']          = 'skift synlig/usynlig';
$BL['be_func_struct_spublic']           = 'skift offentlig/ikke offentlig';
$BL['be_func_struct_sort_up']           = 'flytt opp';
$BL['be_func_struct_sort_down']         = 'flytt ned';
$BL['be_func_struct_del_article']       = 'slett artikkel';
$BL['be_func_struct_del_jsmsg']         = 'er du sikker p&#229; at du vil slette\ndenne artikkel?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'oprett ny artikkel i dette strukturniv&#229;';
$BL['be_func_struct_paste_article']     = 'lim artikkel inn i dette strukturniv&#229;';
$BL['be_func_struct_insert_level']      = 'Sett inn menypunkt under';
$BL['be_func_struct_paste_level']       = 'lim inn i dette strukturniv&#229;';
$BL['be_func_struct_cut_level']         = 'klipp strukturniv&#229;';
$BL['be_func_struct_no_cut']            = "det er ikke mulig &#229; klippe ut root niv&#229;!";
$BL['be_func_struct_no_paste1']         = "du kan ikke lime in her!";
$BL['be_func_struct_no_paste2']         = 'er underniv&#229;et p&#229; linje med root av struktur treet';
$BL['be_func_struct_no_paste3']         = 'det skal limes her';
$BL['be_func_struct_paste_cancel']      = 'avbryt endring av strukturniv&#229;';
$BL['be_func_struct_del_struct']        = 'slett strukturniv&#229;';
$BL['be_func_struct_del_sjsmsg']        = 'Er du sikker p&#229;  at du Do vil\nslette dette dette strukturniv&#229;et?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = '&#229;pne';
$BL['be_func_struct_close']             = 'lukke';
$BL['be_func_struct_empty']             = 'tom';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'ren tekst';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kode';
$BL['be_ctype_textimage']               = 'tekst m/bilde';
$BL['be_ctype_images']                  = 'bilder';
$BL['be_ctype_bulletlist']              = 'punktliste';
$BL['be_ctype_link']                    = 'link &amp; epost';
$BL['be_ctype_linklist']                = 'link liste';
$BL['be_ctype_linkarticle']             = 'artikkellink liste';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'filliste';
$BL['be_ctype_emailform']               = 'epostformular';
$BL['be_ctype_newsletter']              = 'nyhetsbrev';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profilen er n&#229; endret.';
$BL['be_profile_create_error']          = 'En feil oppstod under oppretting.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profil data er oppdatert.';
$BL['be_profile_update_error']          = 'En feil oppstod under oppdatering.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'brukernavnet {VAL} er ulovlig';
$BL['be_profile_account_err2']          = 'passordet er for kort (kun {VAL} tegn: minimum 5 tegn m&#229; brukes)';
$BL['be_profile_account_err3']          = 'Passordet m&#229; hvere likt med  det repeterte passordet';
$BL['be_profile_account_err4']          = 'email {VAL} is invalid';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'Dine personlige data:';
$BL['be_profile_data_text']             = 'personlige data er frivillig. De kan v&#230;re til hjelp for andre brukere eller bes&#248;kene for &#229; vite litt om deg som interesser og kompetanse. Hvis du krysser av boksen for \'offentlig\'  kan andre se din profilinformasjon i offentlige omr&#229;de og/eller p&#229; artikkelsidene avhengig av dine innstillinger.';
$BL['be_profile_label_title']           = 'tittel';
$BL['be_profile_label_firstname']       = 'fornavn';
$BL['be_profile_label_name']            = 'etternavn';
$BL['be_profile_label_company']         = 'firma';
$BL['be_profile_label_street']          = 'adresse';
$BL['be_profile_label_city']            = 'by';
$BL['be_profile_label_state']           = 'provins, stat';
$BL['be_profile_label_zip']             = 'postnummer';
$BL['be_profile_label_country']         = 'land';
$BL['be_profile_label_phone']           = 'telefonnummer';
$BL['be_profile_label_fax']             = 'telefax';
$BL['be_profile_label_cellphone']       = 'mobiltelefon';
$BL['be_profile_label_signature']       = 'underskrift';
$BL['be_profile_label_notes']           = 'noter';
$BL['be_profile_label_profession']      = 'yrke';
$BL['be_profile_label_newsletter']      = 'nyhetsbrev';
$BL['be_profile_text_newsletter']       = 'Jeg &#248;nsker &#229; motta det generelle phpwcms nyhetsbrev.';
$BL['be_profile_label_public']          = 'offentlig';
$BL['be_profile_text_public']           = 'Alle skal kunne se mine personlige profil.';
$BL['be_profile_label_button']          = 'oppdater personlige data';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Din innloggings informasjon';
$BL['be_profile_account_text']          = 'Vanligvis trenger du ikke &#229; skifte brukernavn. Men det anbefales at du skifter passord med jevne mellomrom for &#229; &#248;ke sikkerheten.';
$BL['be_profile_label_err']             = 'vennligst kontroller';# ??
$BL['be_profile_label_username']        = 'brukernavn';
$BL['be_profile_label_newpass']         = 'nytt passord';
$BL['be_profile_label_repeatpass']      = 'gjenta passord';
$BL['be_profile_label_email']           = 'epost';
$BL['be_profile_account_button']        = 'oppdater login data';
$BL['be_profile_label_lang']            = 'spr&#229;k';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'ta over filer som er lastet opp med ftp';
$BL['be_ftptakeover_mark']              = 'merk';
$BL['be_ftptakeover_available']         = 'tilgjengelige filer';
$BL['be_ftptakeover_size']              = 'st&#248;rrelse';
$BL['be_ftptakeover_nofile']            = 'det er fortsatt ikke noen filer tilgjengelig &#8211; du laste de opp med et ftp program';
$BL['be_ftptakeover_all']               = 'ALLE';
$BL['be_ftptakeover_directory']         = 'mappe';
$BL['be_ftptakeover_rootdir']           = 'root mappen';
$BL['be_ftptakeover_needed']            = 'n&#248;dvendig!! (du m&#229; velge minst en)';
$BL['be_ftptakeover_optional']          = 'valgfritt';
$BL['be_ftptakeover_keywords']          = 'n&#248;kkelord';
$BL['be_ftptakeover_additional']        = 'filkommentar';
$BL['be_ftptakeover_longinfo']          = 'lang info';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'aktiv';
$BL['be_ftptakeover_public']            = 'offentlig';
$BL['be_ftptakeover_createthumb']       = 'opprett minibilde';
$BL['be_ftptakeover_button']            = 'overta valgte filer';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'fil senter';
$BL['be_ftab_createnew']                = 'opprett ny mappe root';
$BL['be_ftab_paste']                    = 'lim fil fra klippebordet til root mappe';
$BL['be_ftab_disablethumb']             = 'deaktiver minibilder i listen';
$BL['be_ftab_enablethumb']              = 'aktiver minibilder i listen';
$BL['be_ftab_private']                  = 'private&nbsp;filer';
$BL['be_ftab_public']                   = 'offentlige&nbsp;filer';
$BL['be_ftab_search']                   = 's&#248;k';
$BL['be_ftab_trash']                    = 'papirkurven';
$BL['be_ftab_open']                     = '&#229;pne alle mapper';
$BL['be_ftab_close']                    = 'lukk alle &#229;pne mapper';
$BL['be_ftab_upload']                   = 'last opp fil til root mappe';
$BL['be_ftab_filehelp']                 = '&#229;pne filhjelp';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'root mappe';
$BL['be_fpriv_title']                   = 'opprett ny mappe';
$BL['be_fpriv_inside']                  = 'inne i';
$BL['be_fpriv_error']                   = 'feil: skriv inn navn p&#229; mappe';
$BL['be_fpriv_name']                    = 'navn';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'opprett ny mappe';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'editer mappe';
$BL['be_fpriv_newname']                 = 'nytt navn';
$BL['be_fpriv_updatebutton']            = 'oppdater mappe info';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'velg en fil du vil laste opp';
$BL['be_fprivup_err2']                  = 'St&#248;rrelsen p&#229; den opplastede filen er st&#248;rre enn';
$BL['be_fprivup_err3']                  = 'feil under opplasting av filen';
$BL['be_fprivup_err4']                  = 'feil under oppretting av bruker mappe.';
$BL['be_fprivup_err5']                  = 'ingen minibilder eksisterer';
$BL['be_fprivup_err6']                  = 'Vennligs ikke pr&#248;v igjen dette er en feil p&#229; serveren! kontakt <a href="mailto:{VAL}">webmaster</a> s&#229; raskt som mulig!';
$BL['be_fprivup_title']                 = 'last opp filer';
$BL['be_fprivup_button']                = 'last opp filer';
$BL['be_fprivup_upload']                = 'last opp';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'rediger filinformasjon';
$BL['be_fprivedit_filename']            = 'filnavn';
$BL['be_fprivedit_created']             = 'opprettet';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'filens opprinnelige navn (sett tilbake til opprinnelig navn)';
$BL['be_fprivedit_clockwise']           = 'roter bilde med klokken  [originalfile +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'roter bilde mot klokken  [originalfil -90&deg;]';
$BL['be_fprivedit_button']              = 'oppdater filinfo';
$BL['be_fprivedit_size']                = 'st&#248;rrelse';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'last opp fil til mappen';
$BL['be_fprivfunc_makenew']             = 'opprett ny mappe inne i denne';
$BL['be_fprivfunc_paste']               = 'lim fil fra klippebordet til denne mappen';
$BL['be_fprivfunc_edit']                = 'rediger mappe';
$BL['be_fprivfunc_cactive']             = 'skift aktiver/deaktiver';
$BL['be_fprivfunc_cpublic']             = 'skift offentlig/privat';
$BL['be_fprivfunc_deldir']              = 'slett mappe';
$BL['be_fprivfunc_jsdeldir']            = '&#248;nsker du virkelig\n&#229; slette denne mappen';
$BL['be_fprivfunc_notempty']            = 'mappen {VAL} er ikke tom!';
$BL['be_fprivfunc_opendir']             = '&#229;pne mappe';
$BL['be_fprivfunc_closedir']            = 'lukk mappe';
$BL['be_fprivfunc_dlfile']              = 'hent fil';
$BL['be_fprivfunc_clipfile']            = 'klippebord fil';
$BL['be_fprivfunc_cutfile']             = 'klipp';
$BL['be_fprivfunc_editfile']            = 'rediger filinfo';
$BL['be_fprivfunc_cactivefile']         = 'skift aktiver/deaktiver';
$BL['be_fprivfunc_cpublicfile']         = 'skift offentlig/privat';
$BL['be_fprivfunc_movetrash']           = 'legg i papirkurven';
$BL['be_fprivfunc_jsmovetrash1']        = '&#248;nsker du du virkelig &#229; legge';
$BL['be_fprivfunc_jsmovetrash2']        = 'i papirkurv mappen?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'ingen private filer eller mapper';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'bruker';
$BL['be_fpublic_nofiles']               = 'ingen offentlige filer eller mapper';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'papirkurven er tom';
$BL['be_ftrash_show']                   = 'vis private filer';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = '&#248;nsker du &#229; gjennoprette {VAL} \nog legge den tilbake til privat liste?';
$BL['be_ftrash_delete']                 = '&#248;nsker du &#229; slette {VAL}?';
$BL['be_ftrash_undo']                   = 'gjenopprette  (angre sletting)';
$BL['be_ftrash_delfinal']               = 'siste sletting';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 's&#248;kefelt er tomt.';
$BL['be_fsearch_title']                 = 's&#248;k filer';
$BL['be_fsearch_infotext']              = 'Dette er et enkelt s&#248;k etter filinformasjon, det s&#248;kes etter treff i n&#248;kkelord,<br /> filnavn og lang info. Jokertegn st&#248;ttes ikke. En kan skrive flere ord etter hverandre separert med mellomrom. velg og/eller og hvilke filer du vil s&#248;ke etter: private/offentlige.';
$BL['be_fsearch_nonfound']              = 'Det ble ikke funnet noen filer som passer til s&#248;kebegrepet. Pr&#248;v &#229; endre ditt s&#248;kebegrep!';
$BL['be_fsearch_fillin']                = 'Vennlig fyll inn s&#248;kebegrep i feltet ovenfor.';
$BL['be_fsearch_searchlabel']           = 's&#248;k etter';
$BL['be_fsearch_startsearch']           = 'start s&#248;k';
$BL['be_fsearch_and']                   = 'OG';
$BL['be_fsearch_or']                    = 'ELLER';
$BL['be_fsearch_all']                   = 'alle filer';
$BL['be_fsearch_personal']              = 'private';
$BL['be_fsearch_public']                = 'offentlige';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'intern prat';
$BL['be_chat_info']                     = 'her kan du prate med andre brukere som har tilgang til administrasjon sidnene til phpwcms.';
$BL['be_chat_start']                    = 'klikk her for &#229; starte prat';
$BL['be_chat_lines']                    = 'prate linje';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'beskjed senter';
$BL['be_msg_new']                       = 'nye';
$BL['be_msg_old']                       = 'gamle';
$BL['be_msg_senttop']                   = 'sendt';
$BL['be_msg_del']                       = 'slettet';
$BL['be_msg_from']                      = 'fra';
$BL['be_msg_subject']                   = 'emne';
$BL['be_msg_date']                      = 'dato/tid';
$BL['be_msg_close']                     = 'lukk beskjed';
$BL['be_msg_create']                    = 'opprett ny beskjed';
$BL['be_msg_reply']                     = 'besvar denne beskjeden';
$BL['be_msg_move']                      = 'flytt denne beskjeden til papirkurven';
$BL['be_msg_unread']                    = 'ulest eller ny beskjed';
$BL['be_msg_lastread']                  = 'seneste {VAL} leste beskjeder';
$BL['be_msg_lastsent']                  = 'seneste {VAL} sendte beskjeder';
$BL['be_msg_marked']                    = 'beskjeder merket for sletting (papirkurven)';
$BL['be_msg_nomsg']                     = 'ingen beskjeder i denne mappen';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'SV';
$BL['be_msg_by']                        = 'sendt av';
$BL['be_msg_on']                        = 'den';
$BL['be_msg_msg']                       = 'beskjed';
$BL['be_msg_err1']                      = 'du har glemt &#229; skrive inn mottaker...';
$BL['be_msg_err2']                      = 'fyll ut emne feltet (mottakeren vil da lettere kunne h&#229;ndtere din beskjed)';
$BL['be_msg_err3']                      = 'det gir ingen mening &#229; sende en beskjed uten innhold ;-)';
$BL['be_msg_sent']                      = 'ny beskjed ble sendt!';
$BL['be_msg_fwd']                       = 'du vil ble videresendt til beskjed senter eller';
$BL['be_msg_newmsgtitle']               = 'skriv ny beskjed';
$BL['be_msg_err']                       = 'feil under sending av beskjed';
$BL['be_msg_sendto']                    = 'send beskjed til';
$BL['be_msg_available']                 = 'mulige mottakere';
$BL['be_msg_all']                       = 'send beskejden til alle valgte mottakere';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'abonnementer p&#229; nyhetsbrev';
$BL['be_newsletter_titleedit']          = 'rediger abonnement p&#229; nyhetsbrev';
$BL['be_newsletter_new']                = 'opprett ny';
$BL['be_newsletter_add']                = 'legg til&nbsp;nyhetsbrev&nbsp;abonnement';
$BL['be_newsletter_name']               = 'navn';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'lagre abonnement';
$BL['be_newsletter_button_cancel']      = 'avbryt';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'brukernavn er ulovlig, velg et annet';
$BL['be_admin_usr_err2']                = 'brukernavn er tomt (obligatorisk)';
$BL['be_admin_usr_err3']                = 'passordet er tomt (obligatorisk)';
$BL['be_admin_usr_err4']                = "epsot er ugyldig";
$BL['be_admin_usr_err']                 = 'feil';
$BL['be_admin_usr_mailsubject']         = 'Velkommen til phpwcms adminstasjon';
$BL['be_admin_usr_mailbody']            = "Velkommen til PHPWCMS administrasjon\n\n    brukernavn: {LOGIN}\n    passord: {PASSWORD}\n\n\nDu kan logge inn her: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'opprett ny bruker konto';
$BL['be_admin_usr_realname']            = 'riktig navn';
$BL['be_admin_usr_setactive']           = 'sett bruker aktiv';
$BL['be_admin_usr_iflogin']             = 'kryss her for at brukeren skal kunne logge inn';
$BL['be_admin_usr_isadmin']             = 'bruker er admin';
$BL['be_admin_usr_ifadmin']             = 'kryss her for at brukerene skal ha samme rettigheter som administartor';
$BL['be_admin_usr_verify']              = 'bekreftelse';
$BL['be_admin_usr_sendemail']           = 'send epost til ny bruker med konto informasjon';
$BL['be_admin_usr_button']              = 'send brukerdata';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'rediger bruker konto';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - konto data endret';
$BL['be_admin_usr_emailbody']           = "PHPWCMS BRUKERKONTO INFORMASJON ER ENDRET\n\n    brukernavn: {LOGIN}\n    passord: {PASSWORD}\n\n\nDu kan logge inn her: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[INGEN ENDRING - BRUK DITT GAMLE PASSORD]';
$BL['be_admin_usr_ebutton']             = 'oppdater bruker data';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms bruker liste';
$BL['be_admin_usr_ldel']                = 'OBS!&#13Dette vil slette permanent brukeren';
$BL['be_admin_usr_create']              = 'opprett ny bruker';
$BL['be_admin_usr_editusr']             = 'rediger bruker';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'Meny oppsett';
$BL['be_admin_struct_child']            = '(under)';
$BL['be_admin_struct_index']            = 'index (websidens start)';
$BL['be_admin_struct_cat']              = 'tittel p&#229; kategori';
$BL['be_admin_struct_hide1']            = 'skjul';
$BL['be_admin_struct_hide2']            = 'vis&nbsp;denne&nbsp;kategorien&nbsp;i&nbsp;menyen';
$BL['be_admin_struct_info']             = 'kategori infotext';
$BL['be_admin_struct_template']         = 'mal';
$BL['be_admin_struct_alias']            = 'alias for denne kategori';
$BL['be_admin_struct_visible']          = 'synlig';
$BL['be_admin_struct_button']           = 'send kategori data';
$BL['be_admin_struct_close']            = 'lukk';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'fil kategori';
$BL['be_admin_fcat_err']                = 'kategorinavn er tomt!';
$BL['be_admin_fcat_name']               = 'kategorinavn';
$BL['be_admin_fcat_needed']             = 'n&#248;dvendig';
$BL['be_admin_fcat_button1']            = 'oppdater';
$BL['be_admin_fcat_button2']            = 'opprett';
$BL['be_admin_fcat_delmsg']             = '&#248;nsker du virkelig\n&#229; slette filn&#248;kkel?';
$BL['be_admin_fcat_fcat']               = 'fil kategori';
$BL['be_admin_fcat_err1']               = 'filn&#248;kkel er ikke oppgitt';
$BL['be_admin_fcat_fkeyname']           = 'filn&#248;kkelens navn';
$BL['be_admin_fcat_exit']               = 'lukk';
$BL['be_admin_fcat_addkey']             = 'opprett ny n&#248;kkel';
$BL['be_admin_fcat_editcat']            = 'rediger kategoriens navn';
$BL['be_admin_fcat_delcatmsg']          = '&#248;nsker du virkelig\n&#229; slette denne filkategorien?';
$BL['be_admin_fcat_delcat']             = 'slett filkategori';
$BL['be_admin_fcat_delkey']             = 'slett filn&#248;kkelens navn';
$BL['be_admin_fcat_editkey']            = 'rediger n&#248;kkel';
$BL['be_admin_fcat_addcat']             = 'opprett ny filkategori';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend oppsett: siden  layout';
$BL['be_admin_page_align']              = 'side justering';
$BL['be_admin_page_align_left']         = 'standard justering(venstre) av hele sidens innhold';
$BL['be_admin_page_align_center']       = 'midtstill hele sidens innhold';
$BL['be_admin_page_align_right']        = 'h&#248;yrejuster hele sidens innhold';
$BL['be_admin_page_margin']             = 'marg';
$BL['be_admin_page_top']                = 'topp';
$BL['be_admin_page_bottom']             = 'bunn';
$BL['be_admin_page_left']               = 'venstre';
$BL['be_admin_page_right']              = 'h&#248;yre';
$BL['be_admin_page_bg']                 = 'bakgrunn';
$BL['be_admin_page_color']              = 'farge';
$BL['be_admin_page_height']             = 'h&#248;yde';
$BL['be_admin_page_width']              = 'bredde';
$BL['be_admin_page_main']               = 'hoved';
$BL['be_admin_page_leftspace']          = 'venstre avstand';
$BL['be_admin_page_rightspace']         = 'h&#248;yre avstand';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'bilde';
$BL['be_admin_page_text']               = 'tekst';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'bes&#248;kt';
$BL['be_admin_page_pagetitle']          = 'side&nbsp;tittel';
$BL['be_admin_page_addtotitle']         = 'tilf&#248;y&nbsp;til&nbsp;tittel';
$BL['be_admin_page_category']           = 'kategori';
$BL['be_admin_page_articlename']        = 'artikkel&nbsp;navn';
$BL['be_admin_page_blocks']             = 'blokk';
$BL['be_admin_page_allblocks']          = 'alle blokker';
$BL['be_admin_page_col1']               = '3 kolonners layout';
$BL['be_admin_page_col2']               = '2 kolonners layout (hovedkolonne til h&#248;yre, navigasjonskolonne venstre)';
$BL['be_admin_page_col3']               = '2 kolonners layout (hovedkolonne til venstre, navigasjonskolonne til h&#248;yre)';
$BL['be_admin_page_col4']               = '1 kolonnes layout';
$BL['be_admin_page_header']             = 'sidehode';
$BL['be_admin_page_footer']             = 'sidefot';
$BL['be_admin_page_topspace']           = 'topp&nbsp;avstand';
$BL['be_admin_page_bottomspace']        = 'bunn&nbsp;avstand';
$BL['be_admin_page_button']             = 'lagre side layout';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend oppsett: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'lagre css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend oppsett: maler';
$BL['be_admin_tmpl_default']            = 'standard';
$BL['be_admin_tmpl_add']                = 'legg&nbsp;til&nbsp;mal';
$BL['be_admin_tmpl_edit']               = 'rediger mal';
$BL['be_admin_tmpl_new']                = 'opprett ny';
$BL['be_admin_tmpl_css']                = 'css fil';
$BL['be_admin_tmpl_head']               = 'html hode';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'feil';
$BL['be_admin_tmpl_button']             = 'lagre mal';
$BL['be_admin_tmpl_name']               = 'navn';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'side struktur og artikkelliste';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'Du har ikke skrevet inn noen tittel for denne artikkel';
$BL['be_article_err2']                  = 'startdatoen er ikke gyldig - satt til n&#229;';
$BL['be_article_err3']                  = 'sluttdatoen er ikke gyldig - satt til n&#229;';
$BL['be_article_title1']                = 'basis artikkel informasjon';
$BL['be_article_cat']                   = 'kategori';
$BL['be_article_atitle']                = 'artikkel tittel';
$BL['be_article_asubtitle']             = 'undertittel';
$BL['be_article_abegin']                = 'vises fra';
$BL['be_article_aend']                  = 'slutter';
$BL['be_article_aredirect']             = 'videresend til';
$BL['be_article_akeywords']             = 'n&#248;kkelord';
$BL['be_article_asummary']              = 'sammendrag';
$BL['be_article_abutton']               = 'opprett ny artikkel';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'sluttdatoen er ikke gyldig - satt til n&#229; + 1 uke';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'rediger artikkelens basis informasjon';
$BL['be_article_eslastedit']            = 'sist endret';
$BL['be_article_esnoupdate']            = 'formularet er ikke oppdatert';
$BL['be_article_esbutton']              = 'oppdater artikkeldata';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'artikkel innhold';
$BL['be_article_cnt_type']              = 'innholdstype';
$BL['be_article_cnt_space']             = 'mellomrom';
$BL['be_article_cnt_before']            = 'f&#248;r';
$BL['be_article_cnt_after']             = 'etter';
$BL['be_article_cnt_top']               = 'topp';
$BL['be_article_cnt_ctitle']            = 'overskrift';
$BL['be_article_cnt_back']              = 'komplett artikkel info';
$BL['be_article_cnt_button1']           = 'oppdater innhold';
$BL['be_article_cnt_button2']           = 'opprett innhold';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'artikkel innhold';
$BL['be_article_cnt_ledit']             = 'rediger artikkel';
$BL['be_article_cnt_lvisible']          = 'skift synlig/usynlig';
$BL['be_article_cnt_ldel']              = 'slett denne artikkel';
$BL['be_article_cnt_ldeljs']            = 'Slett artikkel?';
$BL['be_article_cnt_redirect']          = 'videresend til';
$BL['be_article_cnt_edited']            = 'redigert av';
$BL['be_article_cnt_start']             = 'start dato';
$BL['be_article_cnt_end']               = 'stopp dato';
$BL['be_article_cnt_add']               = 'legg til nytt  innhold';
$BL['be_article_cnt_up']                = 'flytt innhold opp';
$BL['be_article_cnt_down']              = 'flytt innhold ned';
$BL['be_article_cnt_edit']              = 'rediger innhold';
$BL['be_article_cnt_delpart']           = 'slett denne del av artikkelen';
$BL['be_article_cnt_delpartjs']         = 'Slett denne del?';
$BL['be_article_cnt_center']            = 'artikkelsenter';

// content forms
$BL['be_cnt_plaintext']                 = 'ren tekst';
$BL['be_cnt_htmltext']                  = 'html tekst';
$BL['be_cnt_image']                     = 'bilde';
$BL['be_cnt_position']                  = 'posisjon';
$BL['be_cnt_pos0']                      = 'Over, venstre';
$BL['be_cnt_pos1']                      = 'Over, sentrert';
$BL['be_cnt_pos2']                      = 'Over, h&#248;yre';
$BL['be_cnt_pos3']                      = 'Under, venstre';
$BL['be_cnt_pos4']                      = 'Under, sentrert';
$BL['be_cnt_pos5']                      = 'Under, h&#248;yre';
$BL['be_cnt_pos6']                      = 'I tekst, venstre';
$BL['be_cnt_pos7']                      = 'I tekst, h&#248;yre';
$BL['be_cnt_pos0i']                     = 'juster bilde over og til venstre for tekstblokk';
$BL['be_cnt_pos1i']                     = 'juster bilde sentrert over tekstblokk';
$BL['be_cnt_pos2i']                     = 'juster bilde over og til h&#248;yre for tekstblokk';
$BL['be_cnt_pos3i']                     = 'juster bilde til venstre under tekstblokk';
$BL['be_cnt_pos4i']                     = 'juster bilde sentrert under tekstblokk';
$BL['be_cnt_pos5i']                     = 'juster bilde til h&#248;yre under tekstblokk';
$BL['be_cnt_pos6i']                     = 'juster bilde til venstre i tekstblokk';
$BL['be_cnt_pos7i']                     = 'juster bilde til h&#248;yre i tekstblokk';
$BL['be_cnt_maxw']                      = 'maks.&nbsp;bredde';
$BL['be_cnt_maxh']                      = 'maks.&nbsp;h&#248;yde';
$BL['be_cnt_enlarge']                   = 'klikk&nbsp;for&nbsp;st&#248;rre&nbsp;bilde';
$BL['be_cnt_caption']                   = 'bildetekst';
$BL['be_cnt_subject']                   = 'emne';
$BL['be_cnt_recipient']                 = 'mottaker';
$BL['be_cnt_buttontext']                = 'tekst p&#229; knapp';## Hvor er dette??
$BL['be_cnt_sendas']                    = 'send som';
$BL['be_cnt_text']                      = 'tekst';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'formular felter';
$BL['be_cnt_code']                      = 'kode';
$BL['be_cnt_infotext']                  = 'infotekst';
$BL['be_cnt_subscription']              = 'mottakere';
$BL['be_cnt_labelemail']                = 'label&nbsp;epost';
$BL['be_cnt_tablealign']                = 'tabell&nbsp;plasering';
$BL['be_cnt_labelname']                 = 'label&nbsp;navn';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;mottaker.';
$BL['be_cnt_allsubsc']                  = 'alle&nbsp;mottaker';
$BL['be_cnt_default']                   = 'standard';
$BL['be_cnt_left']                      = 'venstre';
$BL['be_cnt_center']                    = 'sentrer';
$BL['be_cnt_right']                     = 'h&#248;yre';
$BL['be_cnt_buttontext']                = 'tekst&nbsp;p&#229;&nbsp;knapp';
$BL['be_cnt_successtext']               = 'utf&#248;rt&nbsp;tekst';
$BL['be_cnt_regmail']                   = 'regist.epost';
$BL['be_cnt_logoffmail']                = 'logg av.epost';
$BL['be_cnt_changemail']                = 'endre.epost';
$BL['be_cnt_openimagebrowser']          = '&#229;pne bildeviser';
$BL['be_cnt_openfilebrowser']           = '&#229;pne filbrowser';
$BL['be_cnt_sortup']                    = 'flytt opp';
$BL['be_cnt_sortdown']                  = 'flytt ned';
$BL['be_cnt_delimage']                  = 'fjern valgt bilde';
$BL['be_cnt_delfile']                   = 'fjern valgt fil';
$BL['be_cnt_delmedia']                  = 'fjern valgt media';
$BL['be_cnt_column']                    = 'kolonne';
$BL['be_cnt_imagespace']                = 'bilde mellomrom';
$BL['be_cnt_directlink']                = 'direktelink';
$BL['be_cnt_target']                    = 'm&#229;l';
$BL['be_cnt_target1']                   = 'i et nytt vindu';
$BL['be_cnt_target2']                   = 'i forreldrerammen til vinduet';
$BL['be_cnt_target3']                   = 'i samme vindu uten rammer';
$BL['be_cnt_target4']                   = 'i samme ramme eller vindu';
$BL['be_cnt_bullet']                    = 'punktliste';
$BL['be_cnt_linklist']                  = 'linkliste';
$BL['be_cnt_plainhtml']                 = 'ren html';
$BL['be_cnt_files']                     = 'filer';
$BL['be_cnt_description']               = 'beskrivelse';
$BL['be_cnt_linkarticle']               = 'artikkellink liste';
$BL['be_cnt_articles']                  = 'artikler';
$BL['be_cnt_movearticleto']             = 'flytt valgt artikkel til artikkellink liste';
$BL['be_cnt_removearticleto']           = 'fjern valgt artikkel fra artikkellink liste';
$BL['be_cnt_mediatype']                 = 'media type';
$BL['be_cnt_control']                   = 'kontrol';
$BL['be_cnt_showcontrol']               = 'vis kontrollfelt';
$BL['be_cnt_autoplay']                  = 'autostart';
$BL['be_cnt_source']                    = 'kilde';
$BL['be_cnt_internal']                  = 'intern';
$BL['be_cnt_openmediabrowser']          = '&#229;pne media browser';
$BL['be_cnt_external']                  = 'ekstern';
$BL['be_cnt_mediapos0']                 = 'venstre (standard)';
$BL['be_cnt_mediapos1']                 = 'sentrer';
$BL['be_cnt_mediapos2']                 = 'h&#248;yre';
$BL['be_cnt_mediapos3']                 = 'blokk, venstre';
$BL['be_cnt_mediapos4']                 = 'blokk, h&#248;yre';
$BL['be_cnt_mediapos0i']                = 'juster media over og til venstre for tekstblokk';
$BL['be_cnt_mediapos1i']                = 'juster media sentrert over tekstblokk';
$BL['be_cnt_mediapos2i']                = 'juster media over og til h&#248;yre for tekstblokk';
$BL['be_cnt_mediapos3i']                = 'juster media til venstre i tekstblokk';
$BL['be_cnt_mediapos4i']                = 'juster media til h&#248;yre i tekstblokk';
$BL['be_cnt_setsize']                   = 'sett st&#248;rrelse';
$BL['be_cnt_set1']                      = 'sett media st&#248;rrelse til 160x120px';
$BL['be_cnt_set2']                      = 'sett media st&#248;rrelse til 240x180px';
$BL['be_cnt_set3']                      = 'sett media st&#248;rrelse til 320x240px';
$BL['be_cnt_set4']                      = 'sett media st&#248;rrelse tilo 480x360px';
$BL['be_cnt_set5']                      = 'slett media bredde og h&#248;yde';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'opprett ny side layout';
$BL['be_admin_page_name']               = 'layout navn';
$BL['be_admin_page_edit']               = 'rediger side layout';
$BL['be_admin_page_render']             = 'gjengivelse';
$BL['be_admin_page_table']              = 'tabell';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'egendefinert';
$BL['be_admin_page_custominfo']         = 'fra hovedblokkmal';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'Ingen side layout eksisterer!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 's&#248;k';
$BL['be_cnt_results']                   = 'resultater';
$BL['be_cnt_results_per_page']          = 'pr&nbsp;side (hvis blank vis 25)';
$BL['be_cnt_opennewwin']                = '&#229;pne nytt vindu';
$BL['be_cnt_searchlabeltext']           = 'dette er forh&#229;ndsdefinerte tekster og verdier for s&#248;keformularet og s&#248;ke resultatsiden. disse blir blir vist hvis flere enn en side blir vist.'; # er dette riktig?
$BL['be_cnt_input']                     = 'ledetekst';
$BL['be_cnt_style']                     = 'style';
$BL['be_cnt_result']                    = 'resultat';
$BL['be_cnt_next']                      = 'neste';
$BL['be_cnt_previous']                  = 'forrige';
$BL['be_cnt_align']                     = 'juster';
$BL['be_cnt_searchformtext']            = 'disse tekstene blir vist n&#229;r s&#248;keformularet eller s&#248;keresultater for et s&#248;k treffer/bommer.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'ingen resultar';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'deaktiver';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'artikkel eier';
$BL['be_article_adminuser']             = 'administrator';
$BL['be_article_username']              = 'skrevet av';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible for users logged on only';
$BL['be_admin_struct_status']           = 'frontend meny status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';


// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'backend default text';
$BL['be_ctype_ecard']                   = 'e-card';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'titel/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail tmpl';
$BL['be_cnt_ecard_image']               = 'e-card bilde';
$BL['be_cnt_ecard_title']               = 'e-card titel';
$BL['be_cnt_alignment']                 = 'justering';
$BL['be_cnt_ecardform']                 = 'skjemamal';
$BL['be_cnt_ecardform_err']             = 'Alle felt merket * m&#229; fylles ut';
$BL['be_cnt_ecardform_sender']          = 'Avsende';
$BL['be_cnt_ecardform_recipient']       = 'Mottaker';
$BL['be_cnt_ecardform_name']            = 'Navn';
$BL['be_cnt_ecardform_msgtext']         = 'Din melding til mottaker';
$BL['be_cnt_ecardform_button']          = 'send e-card';
$BL['be_cnt_ecardsend']                 = 'sent tmpl';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend default startup text';
$BL['be_admin_startup_text']            = 'startup text';
$BL['be_admin_startup_button']          = 'save startup text';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'guestbook/comment';
$BL['be_cnt_guestbook_listing']         = 'listing';
$BL['be_cnt_guestbook_listing_all']     = 'list&nbsp;alle&nbsp;innlegg';
$BL['be_cnt_guestbook_list']            = 'list';
$BL['be_cnt_guestbook_perpage']         = 'pr&nbsp;side';
$BL['be_cnt_guestbook_form']            = 'skjema';
$BL['be_cnt_guestbook_signed']          = 'signed';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'f&#248;r';
$BL['be_cnt_guestbook_after']           = 'etter';
$BL['be_cnt_guestbook_entry']           = 'innlegg';
$BL['be_cnt_guestbook_edit']            = 'rediger';
$BL['be_cnt_ecardform_selector']        = 'velger';
$BL['be_cnt_ecardform_radiobutton']     = 'radio knapp';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript funksjonalitet';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'top article count';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'nyhetsbrev';
$BL['be_newsletter_addnl']              = 'legg til nyhetsbrev';
$BL['be_newsletter_titleeditnl']        = 'rediger nyhetsbrev';
$BL['be_newsletter_newnl']              = 'lag nytt';
$BL['be_newsletter_button_savenl']      = 'lagre nyhetsbrev';
$BL['be_newsletter_fromname']           = 'avsendernavn';
$BL['be_newsletter_fromemail']          = 'avsender email';
$BL['be_newsletter_replyto']            = 'reply email';
$BL['be_newsletter_changed']            = 'siste endring';
$BL['be_newsletter_placeholder']        = 'placeholder';
$BL['be_newsletter_htmlpart']           = 'HTML nyhetsbrev innhold';
$BL['be_newsletter_textpart']           = 'TEXT nyhetsbrev innhold';
$BL['be_newsletter_allsubscriptions']   = 'alle abonnement';
$BL['be_newsletter_verifypage']         = 'bekreft link';
$BL['be_newsletter_open']               = 'HTML og TEXT innhold';
$BL['be_newsletter_open1']              = '(klikk ikon for &#229; &#229;pne)';
$BL['be_newsletter_sendnow']            = 'Send nyhetsbrev';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">ADVARSEL!</strong> Sending av nyhetsbrev til flere mottakere kan være risikabelt. Mottakere b&#248;r være bekreftet hvis ikke nyhetsbrevet skal regnes som spam. Tenk deg om f&#248;r du sender nyhetsbrevet. Kontroller nyhetsbrevet ved &#229; sende en test.';
$BL['be_newsletter_attention1']         = 'Hvis du har gjort endringer i nyhetsbrevet m&#229; du lagre det f&#248;rst. Hvis ikke vil endringene bli ignorert.';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'send nyhetsbrev';
$BL['be_newsletter_sendprocess']        = 'sendeprosess';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">ADVARSEL!</strong> Ikke stopp sendeprosessen. Dersom du gj&#248;r det er det mulig at samme mottaker vil f&#229; nyhetsbrevet to ganger. Dersom sendingen feiler vil alle mottakere som ikke har f&#229;tt nyhetsbrevet være lagret i en session array og vil bli benyttet dersom du sender igjen &#248;yeblikkelig.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">email adresse for test <strong>###TEST###</strong> is NOT valid!<br />&nbsp;<br />Vennligs pr&#248;v igjen!';
$BL['be_newsletter_to']                 = 'Mottakere';
$BL['be_newsletter_ready']              = 'sending av nyhetsbrev: FERDIG';
$BL['be_newsletter_readyfailed']        = 'Kunne ikke sende til';
$BL['be_subnav_msg_subscribers']        = 'nyhetsbrev abonnenter';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'sidekart';
$BL['be_cnt_sitemap_catimage']          = 'niv&#229; ikon';
$BL['be_cnt_sitemap_articleimage']      = 'artikkel ikon';
$BL['be_cnt_sitemap_display']           = 'vis';
$BL['be_cnt_sitemap_structuronly']      = 'kun strukturniv&#229;';
$BL['be_cnt_sitemap_structurarticle']   = 'strukturniv&#229; + artikler';
$BL['be_cnt_sitemap_catclass']          = 'niv&#229; class';
$BL['be_cnt_sitemap_articleclass']      = 'artikkel class';
$BL['be_cnt_sitemap_count']             = 'teller';
$BL['be_cnt_sitemap_classcount']        = 'legg til class name';
$BL['be_cnt_sitemap_noclasscount']      = 'ikke legg til class name';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'by';
$BL['be_cnt_bid_bidtext']               = 'budtekst';
$BL['be_cnt_bid_sendtext']              = 'sendt tekst';
$BL['be_cnt_bid_verifiedtext']          = 'bekreftet tekst';
$BL['be_cnt_bid_errortext']             = 'bud slettet';
$BL['be_cnt_bid_verifyemail']           = 'bekreft email';
$BL['be_cnt_bid_startbid']              = 'start bud';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = '&#248;k&nbsp;med';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'eksternt innhold';
$BL['be_cnt_pages_select']              = 'velg fil';
$BL['be_cnt_pages_fromfile']            = 'fil fra struktur';
$BL['be_cnt_pages_manually']            = 'custom sti/fil eller URL';
$BL['be_cnt_pages_cust']                = 'fil/URL';
$BL['be_cnt_pages_from']                = 'kilde';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover images';
$BL['be_cnt_reference_basis']           = 'justering';
$BL['be_cnt_reference_horizontal']      = 'horisontal';
$BL['be_cnt_reference_vertical']        = 'vertikal';
$BL['be_cnt_reference_aligntext']       = 'sm&#229; referansebilder';
$BL['be_cnt_reference_largetext']       = 'stort referansebilde';
$BL['be_cnt_reference_zoom']            = 'zoom';
$BL['be_cnt_reference_middle']          = 'midtstilt';
$BL['be_cnt_reference_border']          = 'ramme';
$BL['be_cnt_reference_block']           = 'blokk B x H';

// added: 31-05-2004
$BL['be_article_rendering']             = 'rendring';
$BL['be_article_nosummary']             = 'ikke vis sammendrag i full artikkel';
$BL['be_article_forlist']               = 'artikkelliste';
$BL['be_article_forfull']               = 'vis full artikkel';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ADVARSEL!</strong> Katalogen &quot;SETUP&quot; eksisterer fremdeles! Slett denne katalogen - den er en potensiell sikkerhetsrisiko.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'bannlyste ord';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'sett cookie';
$BL['be_cnt_guestbook_allowed']         = 'tillat igjen etter';
$BL['be_cnt_guestbook_seconds']         = 'sekunder';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Vil du virkelig slette \nALLE FILER i s&#248;ppelkurven?";
$BL['be_ftrash_delallfiles']            = 'slett alle filer i s&#248;ppelkurven';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'CSV abonnentimport';
$BL['be_newsletter_importtitle']        = 'Importer Nyhetsbrevabonnenter';
$BL['be_newsletter_entriesfound']       = 'poster&nbsp;funnet';
$BL['be_newsletter_foundinfile']        = 'i filen';
$BL['be_newsletter_addresses']          = 'adresser';
$BL['be_newsletter_csverror']           = 'Importert CSV fil er ikke korrekt! Sjekk skilletegn!';
$BL['be_newsletter_importall']          = 'importer alle poster';
$BL['be_newsletter_addressesadded']     = 'adresser lagt til.';
$BL['be_newsletter_newimport']          = 'ny import';
$BL['be_newsletter_importerror']        = 'Vennligst sjekk din CSV fil - ingen adresser kan legges til!';
$BL['be_newsletter_shouldbe1']          = 'Din CSV fil m&#229; være formattert slik';
$BL['be_newsletter_shouldbe2']          = 'men du kan velge skilletegn';
$BL['be_newsletter_sample']             = 'eksempel';
$BL['be_newsletter_selectCSV']          = 'velg CSV fil';
$BL['be_newsletter_delimeter']          = 'skilletegn';
$BL['be_newsletter_importCSV']          = 'import CSV fil';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'sortering av tilordnede artikler';
$BL['be_admin_struct_orderdate']        = 'opprettet dato';
$BL['be_admin_struct_orderchangedate']  = 'endret dato';
$BL['be_admin_struct_orderstartdate']   = 'start dato';
$BL['be_admin_struct_orderdesc']        = 'minkende';
$BL['be_admin_struct_orderasc']         = '&#248;kende';
$BL['be_admin_struct_ordermanual']      = 'manuell (pil opp/ned)';
$BL['be_cnt_sitemap_startid']           = 'start ved';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'map';
$BL['be_save_btn']                      = 'Lagre';
$BL['be_cmap_location_error_notitle']   = 'fyll inn tittel for denne lokasjon.';
$BL['be_cnt_map_add']                   = 'legg til lokasjon';
$BL['be_cnt_map_edit']                  = 'rediger lokasjon';
$BL['be_cnt_map_title']                 = 'lokasjonstittel';
$BL['be_cnt_map_info']                  = 'entry/info';
$BL['be_cnt_map_list']                  = 'lokasjonsliste';
$BL['be_btn_delete']                    = 'Er du sikker p&#229; at du vil \nslette denne lokasjonen?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP variabler';
$BL['be_cnt_vars']                      = 'variabler';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'kopier artikkel';
$BL['be_func_struct_nocopy']            = 'disable kopier artikkel';
$BL['be_func_struct_copy_level']        = 'kopier strukturniv&#229;';
$BL['be_func_struct_no_copy']           = "Det er ikke mulig &#229; kopiere root-niv&#229;et!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minutt';
$BL['be_date_minutes']                  = 'minutter';
$BL['be_date_hour']                     = 'time';
$BL['be_date_hours']                    = 'timer';
$BL['be_date_day']                      = 'dag';
$BL['be_date_days']                     = 'dager';
$BL['be_date_week']                     = 'uke';
$BL['be_date_weeks']                    = 'uker';
$BL['be_date_month']                    = 'm&#229;ned';
$BL['be_date_months']                   = 'm&#229;neder';
$BL['be_off']                           = 'Av';
$BL['be_on']                            = 'P&#229;';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'timeout';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'brukere &amp; grupper';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'forumliste';
$BL['be_forum_title']                   = 'forum tittel';
$BL['be_forum_permission']              = 'tillatelser';
$BL['be_forum_add']                     = 'legg til forum';
$BL['be_forum_titleedit']               = 'rediger forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'custom';
$BL['be_show_content']                  = 'vis';
$BL['be_main_content']                  = 'hovedspalte';
$BL['be_admin_template_jswarning']      = 'ADVARSEL!!! \nCustom blokker kan endres! \n\nHvis du avbryter \nm&#229; din sidelayout resettes! \n\nEndre mal?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS feed';
$BL['be_cnt_rssfeed_url']               = 'RSS url';
$BL['be_cnt_rssfeed_item']              = 'items';
$BL['be_cnt_rssfeed_max']               = 'maks.';
$BL['be_cnt_rssfeed_cut']               = 'skjul f&#248;rste item';

$BL['be_ctype_simpleform']              = 'email kontaktskjema';

$BL['be_cnt_onsuccess']                 = 'ved suksess';
$BL['be_cnt_onerror']                   = 'ved feil';
$BL['be_cnt_onsuccess_redirect']        = 'videresend ved suksess';
$BL['be_cnt_onerror_redirect']          = 'videresend ved feil';

$BL['be_cnt_form_class']                = 'skjema class';
$BL['be_cnt_label_wrap']                = 'label wrap';
$BL['be_cnt_error_class']               = 'feil class';
$BL['be_cnt_req_mark']                  = 'p&#229;krevd markering';
$BL['be_cnt_mark_as_req']               = 'merk som p&#229;krevd';
$BL['be_cnt_mark_as_del']               = 'merk felt for sletting';


$BL['be_cnt_type']                      = 'type';
$BL['be_cnt_label']                     = 'label';
$BL['be_cnt_needed']                    = 'p&#229;krevd';
$BL['be_cnt_delete']                    = 'slett';
$BL['be_cnt_value']                     = 'verdi';
$BL['be_cnt_error_text']                = 'feilmelding';
$BL['be_cnt_css_style']                 = 'CSS stil';

$BL['be_cnt_field']                     = array("text"=>'text (single-line)', "email"=>'email', "textarea"=>'text (multi-line)',
                                                "hidden"=>'skjult', "password"=>'passord', "select"=>'select meny',
                                                "list"=>'list menu', "checkbox"=>'checkbox', "radio"=>'radio knapp',
                                                "upload"=>'fil', "submit"=>'sendeknapp', "reset"=>'reset knapp',
                                                "break"=>'break', "breaktext"=>'break text', "special"=>'text (spezial)');

$BL['be_cnt_access']                    = 'tilgang';
$BL['be_cnt_activated']                 = 'aktivert';
$BL['be_cnt_available']                 = 'tilgjengelig';
$BL['be_cnt_guests']                    = 'gjester';
$BL['be_cnt_admin']                     = 'admin';
$BL['be_cnt_write']                     = 'skrive';
$BL['be_cnt_read']                      = 'lese';

$BL['be_cnt_no_wysiwyg_editor']         = 'deaktiver WYSIWYG editor';
$BL['be_cnt_cache_update']              = 'reset cache';
$BL['be_cnt_cache_delete']              = 'slett cache';
$BL['be_cnt_cache_delete_msg']          = 'Vil du virkelig slette cache?  \nDette kan p&#229;virke s&#248;kefunksjonen.  \n';

$BL['be_admin_usr_issection']           = 'login section';
$BL['be_admin_usr_ifsection0']          = 'frontend';
$BL['be_admin_usr_ifsection1']          = 'backend';
$BL['be_admin_usr_ifsection2']          = 'frontend og backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'rediger denne artikkelens innhold';
$BL['be_func_content_paste0']            = 'lim inn i artikkel';
$BL['be_func_content_paste']             = 'paste later article content part';
$BL['be_func_content_cut']               = 'klipp ut denne artikkelens innhold';
$BL['be_func_content_no_cut']            = "Det er ikke mulig &#229; klippe ut denne artikkelens innhold!";
$BL['be_func_content_copy']              = 'kopier denne artikkelens innhold';
$BL['be_func_content_no_copy']           = "Det er ikke mulig &#229; kopiere denne artikkelens innhold!";
$BL['be_func_content_paste_cancel']      = 'avbryt endringer i artikkelinnhold';







