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


// Language: Swedish, Country Code: se
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'
//
// Translated by Spirou v. 1.0
// Valid for the relaese of phpwcms (31/12/2003)
// #Changelog#
// release
// 31/12/2003


$BL['usr_online']                       = 'Anv&auml;ndare online';

// Login Page
$BL['login_text']                       = 'Fyll i ditt anv&auml;ndarnamn och l&ouml;senord';
$BL['login_error']                      = 'Inloggningen misslyckades. Kontrollera anv&auml;ndarnamn och l&ouml;senord';
$BL['login_username']                   = 'anv&auml;ndarnamn';
$BL['login_userpass']                   = 'l&ouml;senord';
$BL['login_button']                     = 'Login';
$BL['login_lang']                       = 'Spr&aring;k';

// phpwcms.php
$BL['be_nav_logout']                    = 'LOGGA UT';
$BL['be_nav_articles']                  = 'ARTIKLAR';
$BL['be_nav_files']                     = 'FILER';
$BL['be_nav_modules']                   = 'MODULER';
$BL['be_nav_messages']                  = 'POST';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DEBATT';

$BL['be_page_title']                    = 'phpwcms administration';

$BL['be_subnav_article_center']         = 'artikeldatabas';
$BL['be_subnav_article_new']            = 'skapa ny artikel';
$BL['be_subnav_file_center']            = 'filcentral';
$BL['be_subnav_file_ftptakeover']       = 'h&auml;mta FTP-&ouml;verf&ouml;rda filer';
$BL['be_subnav_mod_artists']            = 'artist, kategori, genre';
$BL['be_subnav_msg_center']             = 'meddelanden';
$BL['be_subnav_msg_new']                = 'nytt meddelande';
$BL['be_subnav_msg_newsletter']         = 'nyhetsbrev prenumeranter';
$BL['be_subnav_chat_main']              = 'chattens f&ouml;rsta sida';
$BL['be_subnav_chat_internal']          = 'intern chat';
$BL['be_subnav_profile_login']          = 'login information';
$BL['be_subnav_profile_personal']       = 'personliga uppgifter';
$BL['be_subnav_admin_pagelayout']       = 'layout';
$BL['be_subnav_admin_templates']        = 'templat';
$BL['be_subnav_admin_css']              = 'standard css';
$BL['be_subnav_admin_sitestructure']    = 'meny och sidstruktur';
$BL['be_subnav_admin_users']            = 'administrera anv&auml;ndare';
$BL['be_subnav_admin_filecat']          = 'filkategorier';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'artikel-ID';
$BL['be_func_struct_preview']           = 'förhandsgranska';
$BL['be_func_struct_edit']              = 'modifiera artikel';
$BL['be_func_struct_sedit']             = 'modifiera denna kategori';
$BL['be_func_struct_cut']               = 'kopiera artikel';
$BL['be_func_struct_nocut']             = 'klistra in artikel';
$BL['be_func_struct_svisible']          = 'byt mellan synlig/osynlig';
$BL['be_func_struct_spublic']           = 'byt mellan offentlig/ej offentlig';
$BL['be_func_struct_sort_up']           = 'flytta upp&aring;t';
$BL['be_func_struct_sort_down']         = 'flytta ned&aring;t';
$BL['be_func_struct_del_article']       = 'radera artikel';
$BL['be_func_struct_del_jsmsg']         = 'Vill du verkligen radera denna artikel?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'Skapa ny artikel i denna kategori';
$BL['be_func_struct_paste_article']     = 'Kopiera artikel till denna kategori';
$BL['be_func_struct_insert_level']      = 'Skapa underkategori';
$BL['be_func_struct_paste_level']       = 'Kopiera till denna kategori';
$BL['be_func_struct_cut_level']         = 'flytta kategori';
$BL['be_func_struct_no_cut']            = 'Denna kategori kan inte flyttas!';
$BL['be_func_struct_no_paste1']         = 'Du kan inte kopiera till denna kategori!';
$BL['be_func_struct_no_paste2']         = '&auml;r underkategori till roten i strukturen';
$BL['be_func_struct_no_paste3']         = 'som ska infogas h&auml;r';
$BL['be_func_struct_paste_cancel']      = '&aring;ngra';
$BL['be_func_struct_del_struct']        = 'Radera kategori';
$BL['be_func_struct_del_sjsmsg']        = 'Vill du verkligen radera denna kategori?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = '&ouml;ppna';
$BL['be_func_struct_close']             = 'st&auml;ng';
$BL['be_func_struct_empty']             = 't&ouml;m';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'ren text';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kod';
$BL['be_ctype_textimage']               = 'text och bilder';
$BL['be_ctype_images']                  = 'bilder';
$BL['be_ctype_bulletlist']              = 'punkt-lista';
$BL['be_ctype_link']                    = 'l&auml;nk &amp; e-post';
$BL['be_ctype_linklist']                = 'l&auml;nk listning';
$BL['be_ctype_linkarticle']             = 'artikell&auml;nk listning';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'fil-lista';
$BL['be_ctype_emailform']               = 'e-post formul&auml;r';
$BL['be_ctype_newsletter']              = 'nyhetsbrev';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profilen har &auml;ndrats.';
$BL['be_profile_create_error']          = 'Det uppstod ett fel. Var god pr&ouml;va p&aring; nytt.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profilinfo har uppdaterats.';
$BL['be_profile_update_error']          = 'Det uppstod ett fel. Var god pr&ouml;va p&aring; nytt.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'Anv&auml;ndarnamnet {VAL} &auml;r ogiltigt. V&auml;lj ett annat.';
$BL['be_profile_account_err2']          = 'l&ouml;senordet &auml;r f&ouml;r kort ({VAL} tecken.) L&ouml;senordet m&aring;ste inneh&aring;lla minst 5 tecken)';
$BL['be_profile_account_err3']          = 'l&ouml;senorden du angav st&auml;mmer inte &ouml;verens. Var god kontrollera.';
$BL['be_profile_account_err4']          = 'e-postadressen {VAL} kunde inte sparas. Var god kontrollera att den st&auml;mmer.';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'Dina personliga uppgifter';
$BL['be_profile_data_text']             = 'Personliga uppgifter &auml;r frivilliga. Genom att uppge dessa f&aring;r andra anv&auml;ndare till&auml;ggsinformation om dig, dina intressen eller meriter. Genom att v&auml;lja vilken ruta som du kryssar för kan andra se din profil eller via artikelsidor (eller vice versa)..';
$BL['be_profile_label_title']           = 'titel';
$BL['be_profile_label_firstname']       = 'f&ouml;rnamn';
$BL['be_profile_label_name']            = 'efternamn';
$BL['be_profile_label_company']         = 'f&ouml;retag';
$BL['be_profile_label_street']          = 'address';
$BL['be_profile_label_city']            = 'stad';
$BL['be_profile_label_state']           = 'l&auml;n';
$BL['be_profile_label_zip']             = 'postnr';
$BL['be_profile_label_country']         = 'land';
$BL['be_profile_label_phone']           = 'telefon';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobil';
$BL['be_profile_label_signature']       = 'signatur';
$BL['be_profile_label_notes']           = 'noter';
$BL['be_profile_label_profession']      = 'yrke';
$BL['be_profile_label_newsletter']      = 'nyhetsbrev';
$BL['be_profile_text_newsletter']       = 'Jag vill prenumerera p&aring; phpwcms nyhetsbrev.';
$BL['be_profile_label_public']          = 'offentlig';
$BL['be_profile_text_public']           = 'Min profil &auml;r synlig f&ouml;r alla.';
$BL['be_profile_label_button']          = '&Auml;ndra dina personliga uppgifter';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'din login-information';
$BL['be_profile_account_text']          = 'Normalt beh&ouml;ver ditt anv&auml;ndarnamn inte &auml;ndras. D&auml;remot rekommenderas det att du med j&auml;mna mellanrum &auml;ndrar ditt l&ouml;senord f&ouml;r &ouml;kad s&auml;kerhet.';
$BL['be_profile_label_err']             = 'Markera filnamn';
$BL['be_profile_label_username']        = 'anv&auml;ndarnamn';
$BL['be_profile_label_newpass']         = 'l&ouml;senord';
$BL['be_profile_label_repeatpass']      = 'l&ouml;senord p&aring; nytt';
$BL['be_profile_label_email']           = 'e-post';
$BL['be_profile_account_button']        = 'spara &auml;ndringar';
$BL['be_profile_label_lang']            = 'spr&aring;k';


// files.ftptakeOvan.tmpl.php
$BL['be_ftptakeover_title']             = 'h&auml;mta filer som &ouml;verf&ouml;rts till servern via ftp';
$BL['be_ftptakeover_mark']              = 'markera';
$BL['be_ftptakeover_available']         = 'tillg&auml;ngliga filer';
$BL['be_ftptakeover_size']              = 'st&oslash;rrelse';
$BL['be_ftptakeover_nofile']            = 'der er ikke nogle filer tilg&auml;ngelige &#8211; du skal uploade mindst én til den angivne ftp Ovantagelsesmappe';
$BL['be_ftptakeover_all']               = 'ALLA';
$BL['be_ftptakeover_directory']         = 'katalog';
$BL['be_ftptakeover_rootdir']           = 'rotkatalog';
$BL['be_ftptakeover_needed']            = 'obligatoriskt!!! (du m&aring;ste v&auml;lja minst en)';
$BL['be_ftptakeover_optional']          = 'valfri';
$BL['be_ftptakeover_keywords']          = 's&ouml;kord';
$BL['be_ftptakeover_additional']        = '';
$BL['be_ftptakeover_longinfo']          = 'fil info';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'aktiv';
$BL['be_ftptakeover_public']            = 'offentlig';
$BL['be_ftptakeover_createthumb']       = 'skapa thumbnail';
$BL['be_ftptakeover_button']            = 'H&auml;mta de valda filerna';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'filcentral';
$BL['be_ftab_createnew']                = 'skapa ny mapp i roten';
$BL['be_ftab_paste']                    = 'infoga fil i rotkatalogen';
$BL['be_ftab_disablethumb']             = 'deaktivera thumbnails i listan';
$BL['be_ftab_enablethumb']              = 'aktivera thumbnails i listan';
$BL['be_ftab_private']                  = 'privata&nbsp;filer';
$BL['be_ftab_public']                   = 'offentliga filer';
$BL['be_ftab_search']                   = 's&ouml;k';
$BL['be_ftab_trash']                    = 'skr&auml;pkorg';
$BL['be_ftab_open']                     = '&ouml;ppna alla kataloger';
$BL['be_ftab_close']                    = 'st&auml;ng alla &ouml;ppna kataloger';
$BL['be_ftab_upload']                   = 'spara fil till rotkatalogen';
$BL['be_ftab_filehelp']                 = 'hj&auml;lp';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'rotkatalogen';
$BL['be_fpriv_title']                   = 'skapa ny katalog';
$BL['be_fpriv_inside']                  = 'inne i';
$BL['be_fpriv_error']                   = 'du gl&ouml;mde namnge katalogen';
$BL['be_fpriv_name']                    = 'namn';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'skapa ny katalog';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = '&auml;ndra katalog';
$BL['be_fpriv_newname']                 = 'nytt namn';
$BL['be_fpriv_updatebutton']            = '&auml;ndra kataloginfo';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'V&auml;lj en fil som du vill h&auml;mta';
$BL['be_fprivup_err2']                  = 'Storleken p&aring; den h&auml;mtade filen &auml;r större &auml;n';
$BL['be_fprivup_err3']                  = '&Ouml;verf&ouml;ring av fil misslyckades';
$BL['be_fprivup_err4']                  = 'Skapandet av katalog misslyckades';
$BL['be_fprivup_err5']                  = 'thumbnail saknas';
$BL['be_fprivup_err6']                  = 'Ett serverfel har uppst&aring;tt! Kontakta din <a href="mailto:{VAL}">webmaster</a> ifall du vill att denna funktion ska finnas tillg&auml;nglig!';
$BL['be_fprivup_title']                 = 'h&auml;mta filer';
$BL['be_fprivup_button']                = 'h&auml;mta filer';
$BL['be_fprivup_upload']                = 'h&auml;mta';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = '&auml;ndra filinformation';
$BL['be_fprivedit_filename']            = 'filnamn';
$BL['be_fprivedit_created']             = 'skapad';
$BL['be_fprivedit_dateformat']          = 'm.d.Y H:i';
$BL['be_fprivedit_err1']                = 'filens namn (visa filens ursprungliga namn)';
$BL['be_fprivedit_clockwise']           = 'rotera thumbnail medurs [original fil +90&grader;]';
$BL['be_fprivedit_cclockwise']          = 'rotera thumbnail moturs [original fil -90&grader;]';
$BL['be_fprivedit_button']              = '&auml;ndra filinfo';
$BL['be_fprivedit_size']                = 'storlek';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'h&auml;mta fil till katalogen';
$BL['be_fprivfunc_makenew']             = 'skapa ny underkatalog';
$BL['be_fprivfunc_paste']               = 'flytta fil till denna katalog';
$BL['be_fprivfunc_edit']                = '&auml;ndra katalog';
$BL['be_fprivfunc_cactive']             = 'byt till aktiv/inaktiv';
$BL['be_fprivfunc_cpublic']             = 'byt till offentlig/icke offentlig';
$BL['be_fprivfunc_deldir']              = 'radera katalog';
$BL['be_fprivfunc_jsdeldir']            = 'Vill du verkligen radera denna katalog?';
$BL['be_fprivfunc_notempty']            = 'katalogen {VAL} &auml;r inte tom!';
$BL['be_fprivfunc_opendir']             = 'öppna katalog';
$BL['be_fprivfunc_closedir']            = 'st&auml;ng katalogen';
$BL['be_fprivfunc_dlfile']              = 'h&auml;mta fil';
$BL['be_fprivfunc_clipfile']            = 'fil att flytta';
$BL['be_fprivfunc_cutfile']             = 'flytta';
$BL['be_fprivfunc_editfile']            = '&auml;ndra filinfo';
$BL['be_fprivfunc_cactivefile']         = 'byt till aktiv/inaktiv';
$BL['be_fprivfunc_cpublicfile']         = 'byt till offentlig/icke offentlig';
$BL['be_fprivfunc_movetrash']           = 'flytta till skr&auml;pkorg';
$BL['be_fprivfunc_jsmovetrash1']        = 'Vill du faktiskt flytta';
$BL['be_fprivfunc_jsmovetrash2']        = 'till skr&auml;pkorgen?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'inga privata filer eller kataloger';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'anv&auml;ndare';
$BL['be_fpublic_nofiles']               = 'inga offentliga filer eller kataloger';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'skr&auml;pkorgen &auml;r tom';
$BL['be_ftrash_show']                   = 'visa privata filer';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Vill du flytta {VAL} \n till privata filer?';
$BL['be_ftrash_delete']                 = 'vill du radera {VAL}?';
$BL['be_ftrash_undo']                   = 'flytta fr&aring;n skr&auml;pkorg';
$BL['be_ftrash_delfinal']               = 'radera permanent';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'Du fyllde inte i s&ouml;kf&auml;ltet.';
$BL['be_fsearch_title']                 = 's&ouml;k filer';
$BL['be_fsearch_infotext']              = 'H&auml;r kan du söka efter filer med hj&auml;lp av sökord, filnamn och fil-information.<br /> Sökningen understöder inte sk. wildcards (*). Sök med flera ord genom att separera dessa med ett blanksteg.<br /> V&auml;lj OCH/ELLER samt filtyp (privata/offentliga.';
$BL['be_fsearch_nonfound']              = 'S&ouml;kningen gav inget matchande resultat.';
$BL['be_fsearch_fillin']                = 'fyll i s&ouml;kf&auml;ltet.';
$BL['be_fsearch_searchlabel']           = 's&oslash;k efter';
$BL['be_fsearch_startsearch']           = 'starta s&oslash;kning';
$BL['be_fsearch_and']                   = 'OCH';
$BL['be_fsearch_or']                    = 'ELLER';
$BL['be_fsearch_all']                   = 'alla filer';
$BL['be_fsearch_personal']              = 'privata';
$BL['be_fsearch_public']                = 'offentliga';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'intern chat';
$BL['be_chat_info']                     = 'Chatta med andra registrerade anv&auml;ndare.';
$BL['be_chat_start']                    = 'klicka h&auml;r f&ouml;r att chatta';
$BL['be_chat_lines']                    = 'antal rader';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'meddelandecentral';
$BL['be_msg_new']                       = 'nya';
$BL['be_msg_old']                       = 'gamla';
$BL['be_msg_senttop']                   = 'skickade';
$BL['be_msg_del']                       = 'raderade';
$BL['be_msg_from']                      = 'fr&aring;n';
$BL['be_msg_subject']                   = 'rubrik';
$BL['be_msg_date']                      = 'datum/tid';
$BL['be_msg_close']                     = 'st&auml;ng meddelande';
$BL['be_msg_create']                    = 'skriv ett nytt meddelande';
$BL['be_msg_reply']                     = 'svara p&aring; detta meddelande';
$BL['be_msg_move']                      = 'flytta detta meddelande till skr&auml;pkorgen';
$BL['be_msg_unread']                    = 'ol&auml;st eller nytt meddelande';
$BL['be_msg_lastread']                  = 'senaste {VAL} l&auml;sta meddelanden';
$BL['be_msg_lastsent']                  = 'senaste {VAL} skickade meddelanden';
$BL['be_msg_marked']                    = 'meddelanden som valts att flytta till skr&auml;pkorgen';
$BL['be_msg_nomsg']                     = 'inga meddelanden i denna katalog';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'SV';
$BL['be_msg_by']                        = 'avs&auml;ndare';
$BL['be_msg_on']                        = 'den';
$BL['be_msg_msg']                       = 'meddelande';
$BL['be_msg_err1']                      = 'meddelandet saknar mottagare...';
$BL['be_msg_err2']                      = 'Genom att ange en rubrik h&aring;ller mottagaren b&auml;ttre ordning p&aring; sina meddelanden!';
$BL['be_msg_err3']                      = 'Ditt meddelande saknar text i inneh&aring;llsf&auml;ltet';
$BL['be_msg_sent']                      = 'Meddelandet har skickats!';
$BL['be_msg_fwd']                       = 'vill du g&aring; till meddelandecentralen eller';
$BL['be_msg_newmsgtitle']               = 'Skriv ett nytt meddelande';
$BL['be_msg_err']                       = 'Ett fel uppstod vid s&auml;ndningen av meddelandet';
$BL['be_msg_sendto']                    = 'skicka meddelandet till';
$BL['be_msg_available']                 = 'Anv&auml;ndare';
$BL['be_msg_all']                       = 'skicka meddelande till alla valda mottagare';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'prenumerera p&aring; nyhetsbrev';
$BL['be_newsletter_titleedit']          = 'l&auml;gg till/&auml;ndra prenumerantuppgifter';
$BL['be_newsletter_new']                = 'skapa ny';
$BL['be_newsletter_add']                = 'l&auml;gg till prenumenrant&nbsp;p&aring;&nbsp;nyhetsbrev';
$BL['be_newsletter_name']               = 'namn';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'spara prenumerant';
$BL['be_newsletter_button_cancel']      = 'st&auml;ng';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'anv&auml;ndarnamnet &auml;r ogiltigt - var god v&auml;lj ett annat';
$BL['be_admin_usr_err2']                = 'anv&auml;ndarnamn saknas (obligatoriskt)';
$BL['be_admin_usr_err3']                = 'l&ouml;senord saknas (obligatoriskt)';
$BL['be_admin_usr_err4']                = "e-post adressen &auml;r inte giltig";
$BL['be_admin_usr_err']                 = 'fel';
$BL['be_admin_usr_mailsubject']         = 'V&auml;lkommen till administrationen av phpwcms';
$BL['be_admin_usr_mailbody']            = "VÄLKOMMEN TILL ADMINISTRATIONEN AV PHPWCMS\n\n    anv&auml;ndarnamn: {LOGIN}\n    l&ouml;senord: {PASSWORD}\n\n\nDu kan logga in h&auml;r: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'L&auml;gg till ny anv&auml;ndare';
$BL['be_admin_usr_realname']            = 'Ditt egentliga namn';
$BL['be_admin_usr_setactive']           = 'Aktivera anv&auml;ndarkonto';
$BL['be_admin_usr_iflogin']             = 'ger anv&auml;ndaren r&auml;tt att logga in';
$BL['be_admin_usr_isadmin']             = 'admin';
$BL['be_admin_usr_ifadmin']             = 'Anv&auml;ndaren f&aring;r admin-r&auml;ttigheter';
$BL['be_admin_usr_verify']              = 'bekr&auml;fta';
$BL['be_admin_usr_sendemail']           = 'skicka e-post till anv&auml;ndaren med anv&auml;ndarnamn och l&ouml;senord';
$BL['be_admin_usr_button']              = 'spara anv&auml;ndardata';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = '&auml;ndra anv&auml;ndarkonto';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - kontodata har &auml;ndrats';
$BL['be_admin_usr_emailbody']           = "PHPWCMS ANVÄNDARINFORMATION &auml;NDRAT\n\n    anv&auml;ndarnamn: {LOGIN}\n    l&ouml;senord: {PASSWORD}\n\n\nDu kan logga in h&auml;r: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[INGA &auml;NDRINGAR - ANVÄND SAMMA LÖSENORD SOM TIDIGARE]';
$BL['be_admin_usr_ebutton']             = 'spara anv&auml;ndardata';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'Registrerade anv&auml;ndare';
$BL['be_admin_usr_ldel']                = 'OBS!&#13Denna anv&auml;ndare raderas permanent:';
$BL['be_admin_usr_create']              = 'L&auml;gg till en ny anv&auml;ndare';
$BL['be_admin_usr_editusr']             = '&auml;ndra anv&auml;ndarinfo';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'sidstruktur';
$BL['be_admin_struct_child']            = '(under)';
$BL['be_admin_struct_index']            = 'index (webbsidans start)';
$BL['be_admin_struct_cat']              = 'titel p&aring; kategori';
$BL['be_admin_struct_hide1']            = 'd&ouml;lj';
$BL['be_admin_struct_hide2']            = 'vis&nbsp;denna&nbsp;kategori&nbsp;i&nbsp;menyn';
$BL['be_admin_struct_info']             = 'kategorins infotext';
$BL['be_admin_struct_template']         = 'templat';
$BL['be_admin_struct_alias']            = 'alias f&ouml;r denna kategori';
$BL['be_admin_struct_visible']          = 'synlig';
$BL['be_admin_struct_button']           = 'spara';
$BL['be_admin_struct_close']            = 'st&auml;ng';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'filkategorier';
$BL['be_admin_fcat_err']                = 'kategorins namn saknas!';
$BL['be_admin_fcat_name']               = 'kategorins namn';
$BL['be_admin_fcat_needed']             = 'obilgatorisk';
$BL['be_admin_fcat_button1']            = 'obligatoriskt';
$BL['be_admin_fcat_button2']            = 'uppr&auml;tta';
$BL['be_admin_fcat_delmsg']             = 'Vill du verkligen radera filens sökord?';
$BL['be_admin_fcat_fcat']               = 'filkategori';
$BL['be_admin_fcat_err1']               = 'sökord fattas!';
$BL['be_admin_fcat_fkeyname']           = 'filn&oslash;glens navn';
$BL['be_admin_fcat_exit']               = 'st&auml;ng';
$BL['be_admin_fcat_addkey']             = 'tilf&oslash;j ny n&oslash;gle';
$BL['be_admin_fcat_editcat']            = '&auml;ndra kategorins namn';
$BL['be_admin_fcat_delcatmsg']          = '&oslash;nsker du virkelig\nat slette filkategorien?';
$BL['be_admin_fcat_delcat']             = 'radera filens kategori';
$BL['be_admin_fcat_delkey']             = 'slet filn&oslash;glens navn';
$BL['be_admin_fcat_editkey']            = 'redigér n&oslash;gle';
$BL['be_admin_fcat_addcat']             = 'skapa ny filkategori';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend: sidlayout';
$BL['be_admin_page_align']              = 'placering';
$BL['be_admin_page_align_left']         = 'standard &auml;r att sidan v&auml;nsterjusteras';
$BL['be_admin_page_align_center']       = 'centrera hela sidans inneh&aring;ll';
$BL['be_admin_page_align_right']        = 'h&ouml;gerjustera hela sidans inneh&aring;ll';
$BL['be_admin_page_margin']             = 'margin';
$BL['be_admin_page_top']                = 'top';
$BL['be_admin_page_bottom']             = 'botten';
$BL['be_admin_page_left']               = 'v&auml;nster';
$BL['be_admin_page_right']              = 'h&ouml;ger';
$BL['be_admin_page_bg']                 = 'bakgrund';
$BL['be_admin_page_color']              = 'f&auml;rg';
$BL['be_admin_page_height']             = 'h&ouml;jd';
$BL['be_admin_page_width']              = 'bredd';
$BL['be_admin_page_main']               = 'main';
$BL['be_admin_page_leftspace']          = 'v&auml;nster avst&aring;nd';
$BL['be_admin_page_rightspace']         = 'h&ouml;ger avst&aring;nd';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'bild';
$BL['be_admin_page_text']               = 'text';
$BL['be_admin_page_link']               = 'l&auml;nk';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'bes&oslash;kt';
$BL['be_admin_page_pagetitle']          = 'sidans titel';
$BL['be_admin_page_addtotitle']         = 'visa i titel';
$BL['be_admin_page_category']           = 'kategori';
$BL['be_admin_page_articlename']        = 'artikelns namn';
$BL['be_admin_page_blocks']             = 'block';
$BL['be_admin_page_allblocks']          = 'alla block';
$BL['be_admin_page_col1']               = '3-kolumners layout';
$BL['be_admin_page_col2']               = '2-kolumners layout (huvudram till h&ouml;ger, navigationsramme til venstre)';
$BL['be_admin_page_col3']               = '2-kolumners layout (huvudram till v&auml;nster, navigationsram till h&ouml;ger)';
$BL['be_admin_page_col4']               = '1-kolumns layout';
$BL['be_admin_page_header']             = 'sidhuvud';
$BL['be_admin_page_footer']             = 'sidfot';
$BL['be_admin_page_topspace']           = 'top&nbsp;avst&aring;nd';
$BL['be_admin_page_bottomspace']        = 'botten&nbsp;avst&aring;nd';
$BL['be_admin_page_button']             = 'uppdatera sidans layout';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend setup: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'spara css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'phpwcms frontend templat';
$BL['be_admin_tmpl_default']            = 'standard';
$BL['be_admin_tmpl_add']                = 'skapa nytt templat';
$BL['be_admin_tmpl_edit']               = 'definiera templat';
$BL['be_admin_tmpl_new']                = 'skapa ny';
$BL['be_admin_tmpl_css']                = 'css fil';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'felsida';
$BL['be_admin_tmpl_button']             = 'spara templat';
$BL['be_admin_tmpl_name']               = 'namn';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'sidstruktur och artikellista';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'Titel f&ouml;r denna artikel saknas';
$BL['be_article_err2']                  = 'startdatum &auml;r ogiltigt - s&auml;t til nu';
$BL['be_article_err3']                  = 'slutdatum &auml;r ogiltigt - s&auml;t til nu';
$BL['be_article_title1']                = 'artikelns basinformation';
$BL['be_article_cat']                   = 'kategori';
$BL['be_article_atitle']                = 'Rubrik';
$BL['be_article_asubtitle']             = 'underrubrik';
$BL['be_article_abegin']                = 'b&ouml;rjar';
$BL['be_article_aend']                  = 'slutar';
$BL['be_article_aredirect']             = 'l&auml;nk';
$BL['be_article_akeywords']             = 's&ouml;kord';
$BL['be_article_asummary']              = 'brödtext';
$BL['be_article_abutton']               = 'skapa ny artikel';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'du har angivit ett ogiltigt slutdatum - ange dagens datum + 1 vecka';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'redigera artikelinfo';
$BL['be_article_eslastedit']            = '&auml;ndrad';
$BL['be_article_esnoupdate']            = 'formul&auml;ret har inte uppdaterats';
$BL['be_article_esbutton']              = '&auml;ndra artikelns data';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'artikelinformation';
$BL['be_article_cnt_type']              = 'inneh&aring;ll';
$BL['be_article_cnt_space']             = 'mellanrum';
$BL['be_article_cnt_before']            = 'f&ouml;re';
$BL['be_article_cnt_after']             = 'efter';
$BL['be_article_cnt_top']               = 'top';
$BL['be_article_cnt_ctitle']            = 'Rubrik';
$BL['be_article_cnt_back']              = 'artikel översikt';
$BL['be_article_cnt_button1']           = '&auml;ndra inneh&aring;ll';
$BL['be_article_cnt_button2']           = 'spara';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'artikel information';
$BL['be_article_cnt_ledit']             = '&auml;ndra artikel';
$BL['be_article_cnt_lvisible']          = 'byt till synlig/osynlig';
$BL['be_article_cnt_ldel']              = 'radera denna artikel';
$BL['be_article_cnt_ldeljs']            = 'Radera artikeln?';
$BL['be_article_cnt_redirect']          = 'l&auml;nk';
$BL['be_article_cnt_edited']            = 'skribent';
$BL['be_article_cnt_start']             = 'börjar';
$BL['be_article_cnt_end']               = 'slutar';
$BL['be_article_cnt_add']               = 'tilf&oslash;j nyt artikelindhold';
$BL['be_article_cnt_up']                = 'flytta inneh&aring;ll upp&aring;t';
$BL['be_article_cnt_down']              = 'flytta inneh&aring;ll ned&aring;t';
$BL['be_article_cnt_edit']              = '&auml;ndra denna del av artikeln';
$BL['be_article_cnt_delpart']           = 'ta bort denna del av artikeln';
$BL['be_article_cnt_delpartjs']         = 'Ta bort denna del?';
$BL['be_article_cnt_center']            = 'artikeldatabas';

// content forms
$BL['be_cnt_plaintext']                 = 'ren text';
$BL['be_cnt_htmltext']                  = 'html text';
$BL['be_cnt_image']                     = 'bild';
$BL['be_cnt_position']                  = 'placering';
$BL['be_cnt_pos0']                      = 'Ovan, v&auml;nster';
$BL['be_cnt_pos1']                      = 'Ovan, mitten';
$BL['be_cnt_pos2']                      = 'Ovan, h&oslash;ger';
$BL['be_cnt_pos3']                      = 'Under, v&auml;nster';
$BL['be_cnt_pos4']                      = 'Under, mitten';
$BL['be_cnt_pos5']                      = 'Under, h&oslash;ger';
$BL['be_cnt_pos6']                      = 'I text, v&auml;nster';
$BL['be_cnt_pos7']                      = 'I text, h&ouml;ger';
$BL['be_cnt_pos0i']                     = 'infoga bild ovan texten och till v&auml;nster';
$BL['be_cnt_pos1i']                     = 'infoga bild i mitten ovan texten';
$BL['be_cnt_pos2i']                     = 'infoga bild ovan texten och till h&ouml;ger ';
$BL['be_cnt_pos3i']                     = 'infoga bild under texten och till v&auml;nster ';
$BL['be_cnt_pos4i']                     = 'infoga bild mitt under texten';
$BL['be_cnt_pos5i']                     = 'infoga bild under texten och till h&ouml;ger ';
$BL['be_cnt_pos6i']                     = 'infoga bild till v&auml;nster med texten omkring';
$BL['be_cnt_pos7i']                     = 'infoga bild til h&ouml;ger med texten omkring';
$BL['be_cnt_maxw']                      = 'max.&nbsp;bredd';
$BL['be_cnt_maxh']                      = 'max.&nbsp;höjd';
$BL['be_cnt_enlarge']                   = 'förstora bilden (musklick)';
$BL['be_cnt_caption']                   = 'bildtext';
$BL['be_cnt_subject']                   = 'rubrik';
$BL['be_cnt_recipient']                 = 'mottagare';
$BL['be_cnt_buttontext']                = 'knapptext';
$BL['be_cnt_sendas']                    = 'skicka som';
$BL['be_cnt_text']                      = 'text';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'formul&auml;rf&auml;lt';
$BL['be_cnt_code']                      = 'kod';
$BL['be_cnt_infotext']                  = 'infotext';
$BL['be_cnt_subscription']              = 'mottagare';
$BL['be_cnt_labelemail']                = 'label&nbsp;e-post';
$BL['be_cnt_tablealign']                = 'tabellens&nbsp;placering';
$BL['be_cnt_labelname']                 = 'label&nbsp;namn';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;mottagare';
$BL['be_cnt_allsubsc']                  = 'alle&nbsp;modt.';
$BL['be_cnt_default']                   = 'standard';
$BL['be_cnt_left']                      = 'v&auml;nster';
$BL['be_cnt_center']                    = 'mitten';
$BL['be_cnt_right']                     = 'h&ouml;ger';
$BL['be_cnt_buttontext']                = 'knapptext';
$BL['be_cnt_successtext']               = 'utfört-text';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = '&auml;ndra.e-postadress';
$BL['be_cnt_openimagebrowser']          = '&ouml;ppna bildbrowser';
$BL['be_cnt_openfilebrowser']           = '&ouml;ppna filbrowser';
$BL['be_cnt_sortup']                    = 'flytta upp';
$BL['be_cnt_sortdown']                  = 'flytta ned';
$BL['be_cnt_delimage']                  = 'ta bort vald bild';
$BL['be_cnt_delfile']                   = 'ta bort vald fil';
$BL['be_cnt_delmedia']                  = 'ta bort vald media';
$BL['be_cnt_column']                    = 'kolumn';
$BL['be_cnt_imagespace']                = 'bildutrymme';//
$BL['be_cnt_directlink']                = 'direkt l&auml;nk';
$BL['be_cnt_target']                    = 'target';
$BL['be_cnt_target1']                   = 'i nytt f&ouml;nster';
$BL['be_cnt_target2']                   = 'i huvudramen i det nya f&ouml;nstret';
$BL['be_cnt_target3']                   = 'i samma f&ouml;nster utan ramar';
$BL['be_cnt_target4']                   = 'i samma ram eller f&ouml;nster';
$BL['be_cnt_bullet']                    = 'punktlista';
$BL['be_cnt_linklist']                  = 'l&auml;nklista';
$BL['be_cnt_plainhtml']                 = 'ren html';
$BL['be_cnt_files']                     = 'filer';
$BL['be_cnt_description']               = 'beskrivning';
$BL['be_cnt_linkarticle']               = 'artikell&auml;nk lista';
$BL['be_cnt_articles']                  = 'artiklar';
$BL['be_cnt_movearticleto']             = 'flytta valda artiklar till artikel-lista';
$BL['be_cnt_removearticleto']           = 'flytta valda artiklar fr&aring;n artikell&auml;nk lista';
$BL['be_cnt_mediatype']                 = 'mediatyp';
$BL['be_cnt_control']                   = 'kontroll';
$BL['be_cnt_showcontrol']               = 'visa kontrollpanel';//
$BL['be_cnt_autoplay']                  = 'spela upp automatiskt';
$BL['be_cnt_source']                    = 'filplacering';
$BL['be_cnt_internal']                  = 'internt';
$BL['be_cnt_openmediabrowser']          = '&ouml;ppna mediabrowser';
$BL['be_cnt_external']                  = 'externt';
$BL['be_cnt_mediapos0']                 = 'v&auml;nster (standard)';
$BL['be_cnt_mediapos1']                 = 'mitten';
$BL['be_cnt_mediapos2']                 = 'h&ouml;ger';
$BL['be_cnt_mediapos3']                 = 'block, v&auml;nster';
$BL['be_cnt_mediapos4']                 = 'block, h&ouml;ger';
$BL['be_cnt_mediapos0i']                = 'infoga media ovan texten och till v&auml;nster ';
$BL['be_cnt_mediapos1i']                = 'infoga media i mitten ovan texten';
$BL['be_cnt_mediapos2i']                = 'infoga media ovan texten och till h&ouml;ger ';
$BL['be_cnt_mediapos3i']                = 'infoga media till v&auml;nster med texten omkring';
$BL['be_cnt_mediapos4i']                = 'infoga media till h&ouml;ger med texten omkring';
$BL['be_cnt_setsize']                   = 'v&auml;lj storlek';
$BL['be_cnt_set1']                      = 'storlek 160x120px';
$BL['be_cnt_set2']                      = 'storlek 240x180px';
$BL['be_cnt_set3']                      = 'storlek 320x240px';
$BL['be_cnt_set4']                      = 'storlek 480x360px';
$BL['be_cnt_set5']                      = 'ignorera bredd och höjd';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'skapa ny sidlayout';
$BL['be_admin_page_name']               = 'namn';
$BL['be_admin_page_edit']               = '&auml;ndra sidlayout';
$BL['be_admin_page_render']             = 'rendering';
$BL['be_admin_page_table']              = 'tabell';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'anv&auml;ndardefinierad';
$BL['be_admin_page_custominfo']         = 'anv&auml;nd blockv&auml;rden';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'Ingen sidlayout finns tillg&auml;nglig!';

// added: 31-12-2003
$BL['be_ctype_search'] = 'sökformul&auml;r';
$BL['be_cnt_results'] = 'resultat';
$BL['be_cnt_results_per_page'] = 'resultat per sida (tomt ger alla res.)';
$BL['be_cnt_opennewwin'] = '&ouml;ppna l&auml;nk i nytt f&ouml;nster';
$BL['be_cnt_searchlabeltext'] = 'Definiera text och sökv&auml;rden för sökformul&auml;ret och meddelanden som visas p&aring; resultatsidan samt vad som visas n&auml;r antalet matchningar  överstiger det angivna antalet tr&auml;ffar per sida.';
$BL['be_cnt_input'] = 'input';
$BL['be_cnt_style'] = 'style';
$BL['be_cnt_result'] = 'resultat';
$BL['be_cnt_next'] = 'n&auml;sta';
$BL['be_cnt_previous'] = 'f&ouml;reg&aring;ende';
$BL['be_cnt_align'] = 'placering';
$BL['be_cnt_searchformtext'] = 'Följande text anger vad som ska visas n&auml;r resultat h&auml;mtas fr&aring;n fr&aring;geformul&auml;ret';
$BL['be_cnt_intro'] = 'intro';
$BL['be_cnt_noresult'] = 'inga resultat';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'deaktivera';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'article owner';
$BL['be_article_adminuser']             = 'admin user';
$BL['be_article_username']              = 'author';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible for users logged on only';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';

