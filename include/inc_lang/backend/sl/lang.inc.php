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


// Language: Slovenian
// Language Code: sl
// Translated By: Boris Jerenec <boris@studiotandem.si>
// Character Set: UTF-8 no BOM

// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'prijavljeni uporabniki';

// Login Page
$BL["login_text"]                       = 'Vnesite svoje uporabniško ime in geslo';
$BL['login_error']                      = 'Napake med prijavo!';
$BL["login_username"]                   = 'uporabniško ime';
$BL["login_userpass"]                   = 'geslo';
$BL["login_button"]                     = 'Prijava';
$BL["login_lang"]                       = 'jezik';

// phpwcms.php
$BL['be_nav_logout']                    = 'ODJAVA';
$BL['be_nav_articles']                  = 'ČLANKI';
$BL['be_nav_files']                     = 'DATOTEKE';
$BL['be_nav_modules']                   = 'MODULI';
$BL['be_nav_messages']                  = 'KOMUNIKACIJA';
$BL['be_nav_chat']                      = 'KLEPET';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'RAZPRAVE';

$BL['be_page_title']                    = 'phpwcms administracija';

$BL['be_subnav_article_center']         = 'vsi članki';
$BL['be_subnav_article_new']            = 'nov članek';
$BL['be_subnav_file_center']            = 'vse datoteke';
$BL['be_subnav_file_ftptakeover']       = 'ftp prevzem';
$BL['be_subnav_mod_artists']            = 'umetnik, kategorija, žanr';
$BL['be_subnav_msg_center']             = 'vsa sporočila';
$BL['be_subnav_msg_new']                = 'novo sporočilo';
$BL['be_subnav_msg_newsletter']         = 'naročila na e-časopise';
$BL['be_subnav_chat_main']              = 'glavna stran klepeta';
$BL['be_subnav_chat_internal']          = 'interni klepet';
$BL['be_subnav_profile_login']          = 'informacije o prijavi';
$BL['be_subnav_profile_personal']       = 'osebni podatki';
$BL['be_subnav_admin_pagelayout']       = 'oblika strani';
$BL['be_subnav_admin_templates']        = 'predloge';
$BL['be_subnav_admin_css']              = 'privzet css';
$BL['be_subnav_admin_sitestructure']    = 'struktura strani';
$BL['be_subnav_admin_users']            = 'uporabniki';
$BL['be_subnav_admin_filecat']          = 'datotečne kategorije';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID članka';
$BL['be_func_struct_preview']           = 'predogled';
$BL['be_func_struct_edit']              = 'uredi članek';
$BL['be_func_struct_sedit']             = 'uredi strukturo';
$BL['be_func_struct_cut']               = 'izreži članek';
$BL['be_func_struct_nocut']             = 'onemogoči izrez članka';
$BL['be_func_struct_svisible']          = 'preklopi vidno/skrito';
$BL['be_func_struct_spublic']           = 'preklopi javno/zasebno';
$BL['be_func_struct_sort_up']           = 'sortiraj navzgor';
$BL['be_func_struct_sort_down']         = 'sortiraj navzdol';
$BL['be_func_struct_del_article']       = 'zbriši članek';
$BL['be_func_struct_del_jsmsg']         = 'Ali res želiš \nizbrisati članek?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'ustvari nov članek';
$BL['be_func_struct_paste_article']     = 'prilepi članek v strukturo';
$BL['be_func_struct_insert_level']      = 'vstavi strukturo v';
$BL['be_func_struct_paste_level']       = 'prilepi v strukturo';
$BL['be_func_struct_cut_level']         = 'izreži strukturo';
$BL['be_func_struct_no_cut']            = "najvišjega nivoja ni možno izrezati!";
$BL['be_func_struct_no_paste1']         = "na to mesto ni možno prilepiti!";
$BL['be_func_struct_no_paste2']         = 'je otrok korenskega zapisa';
$BL['be_func_struct_no_paste3']         = 'prilepi sem';
$BL['be_func_struct_paste_cancel']      = 'prekliči spremembe v strukturi';
$BL['be_func_struct_del_struct']        = 'zbriši strukturo';
$BL['be_func_struct_del_sjsmsg']        = 'Ali res želiš \n zbrisati strukturo?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'odprto';
$BL['be_func_struct_close']             = 'zaprto';
$BL['be_func_struct_empty']             = 'prazno';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'tekst';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'koda';
$BL['be_ctype_textimage']               = 'tekst s sliko';
$BL['be_ctype_images']                  = 'slike';
$BL['be_ctype_bulletlist']              = 'seznam (tabela)';
$BL['be_ctype_ullist']                  = 'seznam';
$BL['be_ctype_link']                    = 'povezava &amp; email';
$BL['be_ctype_linklist']                = 'seznam povezav';
$BL['be_ctype_linkarticle']             = 'povezani članki';
$BL['be_ctype_multimedia']              = 'multimedija';
$BL['be_ctype_filelist']                = 'seznam datotek';
$BL['be_ctype_emailform']               = 'obrazec za email';
$BL['be_ctype_newsletter']              = 'naročilo na e-časopis';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profil uspešno ustvarjen.';
$BL['be_profile_create_error']          = 'Napaka pri ustvarjanju profila.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profil uspešno posodobljen.';
$BL['be_profile_update_error']          = 'Napaka pri posodabljanju profila.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'uporabniško ime {VAL} je neveljavno';
$BL['be_profile_account_err2']          = 'geslo je prekratko (samo {VAL} znaki: potrebnih je najmanj 5)';
$BL['be_profile_account_err3']          = 'gesli morata biti identični ';
$BL['be_profile_account_err4']          = 'email {VAL} je neveljaven';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'vaši osebni podatki';
$BL['be_profile_data_text']             = 'Osebni podatki niso obvezni. Ostalim uporabnikom ali obiskovalcem lahko povedo več o vas, vaših interesih in usposobljenosti. Če odkljukate pravilno izbiro, bodo uporabniki lahko videli vaše podatke na javnih straneh ali člankih.';
$BL['be_profile_label_title']           = 'naslov';
$BL['be_profile_label_firstname']       = 'ime';
$BL['be_profile_label_name']            = 'priimek';
$BL['be_profile_label_company']         = 'podjetje';
$BL['be_profile_label_street']          = 'naslov';
$BL['be_profile_label_city']            = 'mesto';
$BL['be_profile_label_state']           = 'pokrajina';
$BL['be_profile_label_zip']             = 'poštna številka';
$BL['be_profile_label_country']         = 'država';
$BL['be_profile_label_phone']           = 'telefon';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobilni telefon';
$BL['be_profile_label_signature']       = 'podpis';
$BL['be_profile_label_notes']           = 'opombe';
$BL['be_profile_label_profession']      = 'poklic';
$BL['be_profile_label_newsletter']      = 'e-časopis';
$BL['be_profile_text_newsletter']       = 'Želim prejemati splošne phpwcms novice.';
$BL['be_profile_label_public']          = 'javno dostopno';
$BL['be_profile_text_public']           = 'Vsak lahko vidi moje osebne podatke.';
$BL['be_profile_label_button']          = 'posodobi osebne podatke';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'informacije o prijavi';
$BL['be_profile_account_text']          = 'V normalnih okoliščinah porabniško ime ni potrebno spreminjati.<br />Zaradi varnosti občasno zamenjajte geslo.';
$BL['be_profile_label_err']             = 'prosim preverite';
$BL['be_profile_label_username']        = 'uporabniško ime';
$BL['be_profile_label_newpass']         = 'novo geslo';
$BL['be_profile_label_repeatpass']      = 'ponovite novo geslo';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'posodobi informacije o prijavi';
$BL['be_profile_label_lang']            = 'jezik';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'prevzemi datoteke naložene s ftp-jem';
$BL['be_ftptakeover_mark']              = 'označi';
$BL['be_ftptakeover_available']         = 'dostopne datoteke';
$BL['be_ftptakeover_size']              = 'velikost';
$BL['be_ftptakeover_nofile']            = 'ni dovolj datotek na voljo - prenesti jih morate na ftp strežnik';
$BL['be_ftptakeover_all']               = 'VSE';
$BL['be_ftptakeover_directory']         = 'direktorij';
$BL['be_ftptakeover_rootdir']           = 'najvišji nivo';
$BL['be_ftptakeover_needed']            = 'morate!!! (eno morate izbrati)';
$BL['be_ftptakeover_optional']          = 'na izbiro';
$BL['be_ftptakeover_keywords']          = 'ključne besede';
$BL['be_ftptakeover_additional']        = 'dodatno';
$BL['be_ftptakeover_longinfo']          = 'dolg opis';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'aktivno';
$BL['be_ftptakeover_public']            = 'javno';
$BL['be_ftptakeover_createthumb']       = 'kreiraj sličico za predogled';
$BL['be_ftptakeover_button']            = 'prevzami izbrane datoteke';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'vse datoteke';
$BL['be_ftab_createnew']                = 'ustvari nov imenik na najvišjem nivoju';
$BL['be_ftab_paste']                    = 'prilepi datoteko s odložišča v najvišji nivo';
$BL['be_ftab_disablethumb']             = 'skrij sličice v seznamu';
$BL['be_ftab_enablethumb']              = 'prikaži sličice v seznamu';
$BL['be_ftab_private']                  = 'zasebne&nbsp;datoteke';
$BL['be_ftab_public']                   = 'javne&nbsp;datoteke';
$BL['be_ftab_search']                   = 'iskanje';
$BL['be_ftab_trash']                    = 'koš';
$BL['be_ftab_open']                     = 'odpri vse imenike';
$BL['be_ftab_close']                    = 'zapri vse odprte imenike';
$BL['be_ftab_upload']                   = 'upload file to root directory';
$BL['be_ftab_filehelp']                 = 'odpri direktorij pomoč';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'najvišji nivo';
$BL['be_fpriv_title']                   = 'ustvari nov imenik';
$BL['be_fpriv_inside']                  = 'znotraj';
$BL['be_fpriv_error']                   = 'napaka: vstavi ime imenika';
$BL['be_fpriv_name']                    = 'ime';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'ustvari nov imenik';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'uredi imenik';
$BL['be_fpriv_newname']                 = 'novo ime';
$BL['be_fpriv_updatebutton']            = 'posodobi informacije o imeniku';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'izberi datoteko, ki jo želiš naložiti';
$BL['be_fprivup_err2']                  = 'velikost naložene datoteke je prevelika';
$BL['be_fprivup_err3']                  = 'napaka pri zapisu datoteke na strežniku';
$BL['be_fprivup_err4']                  = 'napaka pri kreiranju imenika';
$BL['be_fprivup_err5']                  = 'sličica ne obstaja';
$BL['be_fprivup_err6']                  = 'prosim ne poskušajte znova - to je napaka strežnika! Čimprej obvestite svojega <a href="mailto:{VAL}">administratorja</a>!';
$BL['be_fprivup_title']                 = 'naloži datoteke';
$BL['be_fprivup_button']                = 'naloži datoteke';
$BL['be_fprivup_upload']                = 'naloži';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'uredi informacije o datoteki';
$BL['be_fprivedit_filename']            = 'ime datoteke';
$BL['be_fprivedit_created']             = 'ustvarjeno';
$BL['be_fprivedit_dateformat']          = 'd.m.Y H:i';
$BL['be_fprivedit_err1']                = 'preimenuj datoteko v originalno ime (set back to original)';
$BL['be_fprivedit_clockwise']           = 'zasuči sličico v smeri urinega kazalca [+90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'zasuči sličico v nasprotni smeri urinega kazalca [-90&deg;]';
$BL['be_fprivedit_button']              = 'popravi informacije o datoteki';
$BL['be_fprivedit_size']                = 'velikost';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'naloži datoteko v imenik';
$BL['be_fprivfunc_makenew']             = 'ustvari nov imenik znotraj';
$BL['be_fprivfunc_paste']               = 'prilepi datoteko z odložišča v imenik';
$BL['be_fprivfunc_edit']                = 'uredi imenik';
$BL['be_fprivfunc_cactive']             = 'preklopi aktivno/neaktivno';
$BL['be_fprivfunc_cpublic']             = 'preklopi javno/zasebno';
$BL['be_fprivfunc_deldir']              = 'izbriši imenik';
$BL['be_fprivfunc_jsdeldir']            = 'Ali res želite \nizbrisati direktorij';
$BL['be_fprivfunc_notempty']            = 'imenik {VAL} ni prazen!';
$BL['be_fprivfunc_opendir']             = 'odpri imenik';
$BL['be_fprivfunc_closedir']            = 'zapri imenik';
$BL['be_fprivfunc_dlfile']              = 'snemi datoteko';
$BL['be_fprivfunc_clipfile']            = 'datoteka na odložišču';
$BL['be_fprivfunc_cutfile']             = 'izreži';
$BL['be_fprivfunc_editfile']            = 'uredi informacije o datoteki';
$BL['be_fprivfunc_cactivefile']         = 'preklopi aktiven/neaktiven';
$BL['be_fprivfunc_cpublicfile']         = 'preklopi javno/zasebno';
$BL['be_fprivfunc_movetrash']           = 'vrži v koš';
$BL['be_fprivfunc_jsmovetrash1']        = 'Ali res želite vreči ';
$BL['be_fprivfunc_jsmovetrash2']        = 'v koš?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'ni zasebnih datotek ali imenikov ';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'uporabnik';
$BL['be_fpublic_nofiles']               = 'ni javnih datotek ali imenikov';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'koš je prazen';
$BL['be_ftrash_show']                   = 'pokaži zasebne datoteke';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Ali želite obnoviti datoteko {VAL} \nin jo shraniti nazaj v zasebni seznam?';
$BL['be_ftrash_delete']                 = 'Ali želite izbrisati {VAL}?';
$BL['be_ftrash_undo']                   = 'obnovitev datoteke';
$BL['be_ftrash_delfinal']               = 'dokončno izbriši';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'okvirček za iskanje je prazen.';
$BL['be_fsearch_title']                 = 'iskanje datotek';
$BL['be_fsearch_infotext']              = 'To je osnovno iskanje datotek. Išče po imenu, ključnih besedah in dolgem opisu. Ne podpira uporabo zamenjav (?, *). Več iskanih besed razmejite s presledkom. Izberite med IN/ALI iskanjem med temi besedami. Izberete lahko tudi ali naj iskanje poteka med javnimi ali zasebnimi datotekami.';
$BL['be_fsearch_nonfound']              = 'ne najdem nobenih datotek, ki bi ustrezala izbranim pogojem.';
$BL['be_fsearch_fillin']                = 'prosim, izpolnite okvirček za iskanje.';
$BL['be_fsearch_searchlabel']           = 'išči';
$BL['be_fsearch_startsearch']           = 'začni iskanje';
$BL['be_fsearch_and']                   = 'IN';
$BL['be_fsearch_or']                    = 'ALI';
$BL['be_fsearch_all']                   = 'vse datoteke';
$BL['be_fsearch_personal']              = 'zasebne datoteke';
$BL['be_fsearch_public']                = 'javne datoteke';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'interni klepet';
$BL['be_chat_info']                     = 'Tukaj lahko klepetate z ostalimi phpwcms uporabniki o čemerkoli. ';
$BL['be_chat_start']                    = 'kliknite tukaj za pričetek klepeta';
$BL['be_chat_lines']                    = 'število vrstic';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'center za sporočila';
$BL['be_msg_new']                       = 'nova';
$BL['be_msg_old']                       = 'stara';
$BL['be_msg_senttop']                   = 'poslana';
$BL['be_msg_del']                       = 'izbrisana';
$BL['be_msg_from']                      = 'od';
$BL['be_msg_subject']                   = 'zadeva';
$BL['be_msg_date']                      = 'datum/ura';
$BL['be_msg_close']                     = 'zapri sporočilo';
$BL['be_msg_create']                    = 'ustvari novo sporočilo';
$BL['be_msg_reply']                     = 'odgovori na sporočilo';
$BL['be_msg_move']                      = 'premkni sporočilo v koš';
$BL['be_msg_unread']                    = 'neprebrana oziroma nova sporočila';
$BL['be_msg_lastread']                  = 'zadnje {VAL} preberi sporočila';
$BL['be_msg_lastsent']                  = 'zadnjih {VAL} poslanih sporočil';
$BL['be_msg_marked']                    = 'izbrisana sporočila (koš)';
$BL['be_msg_nomsg']                     = 'nobeno sporočilo ni bilo najdeno znotraj tega imenika';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'pošiljatelj';
$BL['be_msg_on']                        = 'dne';
$BL['be_msg_msg']                       = 'sporočilo';
$BL['be_msg_err1']                      = 'pozabili ste vpisati naslovnika...';
$BL['be_msg_err2']                      = 'izpolnite polje z Zadevo (naslovnik bo lažje razporedil vaše sporočilo)';
$BL['be_msg_err3']                      = 'sporočilo brez vsebine nima nobenega smisla ;-)';
$BL['be_msg_sent']                      = 'novo sporočilo je bilo poslano!';
$BL['be_msg_fwd']                       = 'preusmerjeni boste v center za sporočila';
$BL['be_msg_newmsgtitle']               = 'napišite novo sporočilo';
$BL['be_msg_err']                       = 'napaka pri pošiljanju sporočila';
$BL['be_msg_sendto']                    = 'pošlji sporočilo';
$BL['be_msg_available']                 = 'seznam razpoložljivih naslovnikov';
$BL['be_msg_all']                       = 'pošlji sporočilo vsem izbranim naslovnikom';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'naročila na e-časopis';
$BL['be_newsletter_titleedit']          = 'uredi naročilo na e-časopis ';
$BL['be_newsletter_new']                = 'ustvari nov e-časopis';
$BL['be_newsletter_add']                = 'dodaj&nbsp;naročilo&nbsp;na&nbsp;e-časopis';
$BL['be_newsletter_name']               = 'ime';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'shrani naročilo';
$BL['be_newsletter_button_cancel']      = 'prekliči';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'uporabniško ime je nepravilno, izberite drugega';
$BL['be_admin_usr_err2']                = 'uporabniško ime je prazno (zahtevano)';
$BL['be_admin_usr_err3']                = 'geslo je prazno (zahtevano)';
$BL['be_admin_usr_err4']                = "email ni pravilen";
$BL['be_admin_usr_err']                 = 'napaka';
$BL['be_admin_usr_mailsubject']         = 'dobrodošli v uredniški sistem phpwcms';
$BL['be_admin_usr_mailbody']            = "DOBRODOŠLI V UREDNIŠKI SISTEM PHPWCMS\n\n    uporabniško ime: {LOGIN}\n    geslo: {PASSWORD}\n\n\nPrijavite se lahko tukaj: {LOGIN_PAGE}\n\nadministrator sistema phpwcms\n ";
$BL['be_admin_usr_title']               = 'dodaj novega uporabnika';
$BL['be_admin_usr_realname']            = 'pravo ime';
$BL['be_admin_usr_setactive']           = 'uporabnik je aktiven';
$BL['be_admin_usr_iflogin']             = 'če je odkljukano, se lahko prijavi';
$BL['be_admin_usr_isadmin']             = 'uporabnik je administrator';
$BL['be_admin_usr_ifadmin']             = 'če je odkljukano, ima uporabnik pravice administratorja';
$BL['be_admin_usr_verify']              = 'preverjanje';
$BL['be_admin_usr_sendemail']           = 'pošlji novemu uporabniku  email s podatki o prijavi';
$BL['be_admin_usr_button']              = 'shrani uporabnikove podatke';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'urejanje uporabniškega računa';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - uporabniški račun spremenjen';
$BL['be_admin_usr_emailbody']           = "PHPWCMS UPORABNIŠKI RAČUN SPREMENJEN\n\n    uporabniško ime: {LOGIN}\n    geslo: {PASSWORD}\n\n\nPrijavite se lahko tukaj: {LOGIN_PAGE}\n\nadministrator sistema phpwcms\n ";
$BL['be_admin_usr_passnochange']        = '[BREZ SPREMEMBE - UPORABITE ZNANO GESLO]';
$BL['be_admin_usr_ebutton']             = 'popravi uporabnikove podatke';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'seznam uporabnikov';
$BL['be_admin_usr_ldel']                = 'POZOR!&#13To bo izbrisalo uporabnika';
$BL['be_admin_usr_create']              = 'ustvari novega uporabnika';
$BL['be_admin_usr_editusr']             = 'uredi uporabnika';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'struktura strani';
$BL['be_admin_struct_child']            = '(otrok od)';
$BL['be_admin_struct_index']            = 'index (vrh spletnih strani)';
$BL['be_admin_struct_cat']              = 'kategorija';
$BL['be_admin_struct_hide1']            = 'skrij';
$BL['be_admin_struct_hide2']            = 'to&nbsp;kategorijo&nbsp;v&nbsp;meniju';
$BL['be_admin_struct_info']             = 'informacije o kategoriji';
$BL['be_admin_struct_template']         = 'predloga';
$BL['be_admin_struct_alias']            = 'alias te kategorije';
$BL['be_admin_struct_visible']          = 'vidno';
$BL['be_admin_struct_button']           = 'shrani podatke o kategoriji';
$BL['be_admin_struct_close']            = 'zapri';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'datotečne kategorije';
$BL['be_admin_fcat_err']                = 'ime kategorije je prazno!';
$BL['be_admin_fcat_name']               = 'ime kategorije';
$BL['be_admin_fcat_needed']             = 'je potrebna';
$BL['be_admin_fcat_button1']            = 'popravi';
$BL['be_admin_fcat_button2']            = 'ustvari';
$BL['be_admin_fcat_delmsg']             = 'Ali res želite\nodstraniti ključ datoteke?';
$BL['be_admin_fcat_fcat']               = 'datotečna kategorija';
$BL['be_admin_fcat_err1']               = 'ključ je prazen!';
$BL['be_admin_fcat_fkeyname']           = 'ime ključa';
$BL['be_admin_fcat_exit']               = 'izhod';
$BL['be_admin_fcat_addkey']             = 'dodaj nov ključ';
$BL['be_admin_fcat_editcat']            = 'uredi ime kategorije';
$BL['be_admin_fcat_delcatmsg']          = 'Ali res želite\nodstraniti datotečno kategorijo?';
$BL['be_admin_fcat_delcat']             = 'izbriši datotečno kategorijo';
$BL['be_admin_fcat_delkey']             = 'izbriši ključ';
$BL['be_admin_fcat_editkey']            = 'uredi ključ';
$BL['be_admin_fcat_addcat']             = 'ustvari novo datotečno kategorijo';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'oblika strani';
$BL['be_admin_page_align']              = 'poravnava strani';
$BL['be_admin_page_align_left']         = 'standardna poravnava (levo)';
$BL['be_admin_page_align_center']       = 'center';
$BL['be_admin_page_align_right']        = 'desna poravnava';
$BL['be_admin_page_margin']             = 'robovi';
$BL['be_admin_page_top']                = 'zgoraj';
$BL['be_admin_page_bottom']             = 'spodaj';
$BL['be_admin_page_left']               = 'levo';
$BL['be_admin_page_right']              = 'desno';
$BL['be_admin_page_bg']                 = 'ozadje';
$BL['be_admin_page_color']              = 'barva';
$BL['be_admin_page_height']             = 'višina';
$BL['be_admin_page_width']              = 'širina';
$BL['be_admin_page_main']               = 'vsebina (main)';
$BL['be_admin_page_leftspace']          = 'levi prostor';
$BL['be_admin_page_rightspace']         = 'desni prostor';
$BL['be_admin_page_class']              = 'razred';
$BL['be_admin_page_image']              = 'slika';
$BL['be_admin_page_text']               = 'tekst';
$BL['be_admin_page_link']               = 'povezava';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'obiskano';
$BL['be_admin_page_pagetitle']          = 'naslov&nbsp;strani';
$BL['be_admin_page_addtotitle']         = 'dodaj&nbsp;k&nbsp;naslovu';
$BL['be_admin_page_category']           = 'kategorijo';
$BL['be_admin_page_articlename']        = 'ime&nbsp;članka';
$BL['be_admin_page_blocks']             = 'bloki';
$BL['be_admin_page_allblocks']          = 'vsi bloki';
$BL['be_admin_page_col1']               = '3 kolonska oblika';
$BL['be_admin_page_col2']               = '2 kolonska oblika (vsebina desno, navigacija levo)';
$BL['be_admin_page_col3']               = '2 kolonska oblika (vsebina levo, navigacija desno)';
$BL['be_admin_page_col4']               = '1 kolonska oblika';
$BL['be_admin_page_header']             = 'glava';
$BL['be_admin_page_footer']             = 'noga';
$BL['be_admin_page_topspace']           = 'prostor&nbsp;na&nbsp;vrhu';
$BL['be_admin_page_bottomspace']        = 'prostor&nbsp;na&nbsp;dnu';
$BL['be_admin_page_button']             = 'shrani obliko strani';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'css datoteka';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'shrani css datoteko';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'predloge';
$BL['be_admin_tmpl_default']            = 'privzeta';
$BL['be_admin_tmpl_add']                = 'dodaj&nbsp;predlogo';
$BL['be_admin_tmpl_edit']               = 'uredi predlogo';
$BL['be_admin_tmpl_new']                = 'ustvari novo';
$BL['be_admin_tmpl_css']                = 'css datoteka';
$BL['be_admin_tmpl_head']               = 'html glava';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'napaka';
$BL['be_admin_tmpl_button']             = 'shrani predlogo';
$BL['be_admin_tmpl_name']               = 'ime';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'struktura strani in seznam člankov';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'naslov tega članka je prazen';
$BL['be_article_err2']                  = 'začetni datum je napačen - nastavljen na danes';
$BL['be_article_err3']                  = 'končni datum je napačen - nastavljen na danes';
$BL['be_article_title1']                = 'osnovni podatki o članku';
$BL['be_article_cat']                   = 'kategorija';
$BL['be_article_atitle']                = 'naslov članka';
$BL['be_article_asubtitle']             = 'podnaslov';
$BL['be_article_abegin']                = 'začne se';
$BL['be_article_aend']                  = 'konča se';
$BL['be_article_aredirect']             = 'preusmeri v';
$BL['be_article_akeywords']             = 'ključne besede';
$BL['be_article_asummary']              = 'povzetek';
$BL['be_article_abutton']               = 'ustvari nov članek';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'končni datum je bil napačen - nastavljen na danes + 1 teden';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'urejanje osnovnih podatke o članku';
$BL['be_article_eslastedit']            = 'zadnji popravek';
$BL['be_article_esnoupdate']            = 'obrazec ni posodobljen';
$BL['be_article_esbutton']              = 'popravi podatke o članku';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'vsebina članka';
$BL['be_article_cnt_type']              = 'tip vsebine';
$BL['be_article_cnt_space']             = 'prostor';
$BL['be_article_cnt_before']            = 'pred';
$BL['be_article_cnt_after']             = 'za';
$BL['be_article_cnt_top']               = 'oznaka na vrh';
$BL['be_article_cnt_toplink']           = 'povezava na vrh';
$BL['be_article_cnt_anchor']            = 'sidro';

$BL['be_article_cnt_ctitle']            = 'naslov vsebine';
$BL['be_article_cnt_back']              = 'kompletni članek';
$BL['be_article_cnt_button1']           = 'popravi vsebino';
$BL['be_article_cnt_button2']           = 'ustvari vsebino';
$BL['be_article_cnt_button3']           = 'shrani in zapri';


// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'informacije o članku';
$BL['be_article_cnt_ledit']             = 'uredi članek';
$BL['be_article_cnt_lvisible']          = 'preklopi vidno/skrito';
$BL['be_article_cnt_ldel']              = 'izbriši članek';
$BL['be_article_cnt_ldeljs']            = 'Ali izbrišem članek?';
$BL['be_article_cnt_redirect']          = 'preusmeritev';
$BL['be_article_cnt_edited']            = 'uredil';
$BL['be_article_cnt_start']             = 'začetni datum';
$BL['be_article_cnt_end']               = 'končni datum';
$BL['be_article_cnt_add']               = 'dodaj';
$BL['be_article_cnt_addtitle']          = 'dodaj nov del vsebine';
$BL['be_article_cnt_up']                = 'premakni ta del navzgor';
$BL['be_article_cnt_down']              = 'premakni ta del navzdol';
$BL['be_article_cnt_edit']              = 'uredi ta del vsebine';
$BL['be_article_cnt_delpart']           = 'izbriši ta del vsebine';
$BL['be_article_cnt_delpartjs']         = 'Ali izbrišem ta del vsebine?';
$BL['be_article_cnt_center']            = 'vsi članki';

// content forms
$BL['be_cnt_plaintext']                 = 'preprost tekst';
$BL['be_cnt_htmltext']                  = 'html tekst';
$BL['be_cnt_image']                     = 'slika';
$BL['be_cnt_position']                  = 'položaj';
$BL['be_cnt_pos0']                      = 'zgoraj levo';
$BL['be_cnt_pos1']                      = 'zgoraj center';
$BL['be_cnt_pos2']                      = 'zgoraj desno';
$BL['be_cnt_pos3']                      = 'spodaj levo';
$BL['be_cnt_pos4']                      = 'spodaj center';
$BL['be_cnt_pos5']                      = 'spodaj desno';
$BL['be_cnt_pos6']                      = 'v tekstu levo';
$BL['be_cnt_pos7']                      = 'v tekstu desno';
$BL['be_cnt_pos0i']                     = 'poravnaj sliko zgoraj levo nad tekstom';
$BL['be_cnt_pos1i']                     = 'poravnaj sliko zgoraj v center nad tekstom';
$BL['be_cnt_pos2i']                     = 'poravnaj sliko zgoraj desno nad tekstom';
$BL['be_cnt_pos3i']                     = 'poravnaj sliko spodaj levo pod tekstom';
$BL['be_cnt_pos4i']                     = 'poravnaj sliko spodaj v center pod tekstom';
$BL['be_cnt_pos5i']                     = 'poravnaj sliko spodaj desno pod tekstom';
$BL['be_cnt_pos6i']                     = 'poravnaj sliko levo od teksta';
$BL['be_cnt_pos7i']                     = 'poravnaj sliko desno od teksta';
$BL['be_cnt_maxw']                      = 'maks.&nbsp;širina';
$BL['be_cnt_maxh']                      = 'maks.&nbsp;višina';
$BL['be_cnt_enlarge']                   = 'klik&nbsp;povečava';
$BL['be_cnt_caption']                   = 'podpis k sliki';
$BL['be_cnt_subject']                   = 'zadeva';
$BL['be_cnt_recipient']                 = 'prejemnik';
$BL['be_cnt_buttontext']                = 'tekst na gumbu';
$BL['be_cnt_sendas']                    = 'pošlji kot';
$BL['be_cnt_text']                      = 'tekst';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'polja na obrazcu';
$BL['be_cnt_code']                      = 'koda';
$BL['be_cnt_infotext']                  = 'info&nbsp;tekst';
$BL['be_cnt_subscription']              = 'naročilo na';
$BL['be_cnt_labelemail']                = 'oznaka&nbsp;emaila';
$BL['be_cnt_tablealign']                = 'poravnava&nbsp;tabele';
$BL['be_cnt_labelname']                 = 'oznaka&nbsp;imena';
$BL['be_cnt_labelsubsc']                = 'oznaka&nbsp;naroč.';
$BL['be_cnt_allsubsc']                  = 'vsi&nbsp;naroč.';
$BL['be_cnt_default']                   = 'privzeta';
$BL['be_cnt_left']                      = 'levo';
$BL['be_cnt_center']                    = 'center';
$BL['be_cnt_right']                     = 'desno';
$BL['be_cnt_buttontext']                = 'tekst&nbsp;na&nbsp;gumbu';
$BL['be_cnt_successtext']               = 'tekst&nbsp;ob&nbsp;uspehu';
$BL['be_cnt_regmail']                   = 'email registr.';
$BL['be_cnt_logoffmail']                = 'email odjava';
$BL['be_cnt_changemail']                = 'email sprememba';
$BL['be_cnt_openimagebrowser']          = 'odpri pregledovalnik slik';
$BL['be_cnt_openfilebrowser']           = 'odpri pregledovalnik datotek';
$BL['be_cnt_sortup']                    = 'premakni gor';
$BL['be_cnt_sortdown']                  = 'premakni dol';
$BL['be_cnt_delimage']                  = 'odstrani izbrano sliko';
$BL['be_cnt_delfile']                   = 'odstrani izbrano datoteko';
$BL['be_cnt_delmedia']                  = 'odstrani izbrano multimedijsko datoteko';
$BL['be_cnt_column']                    = 'št. stolpcev';
$BL['be_cnt_imagespace']                = 'prostor&nbsp;okoli&nbsp;slike';
$BL['be_cnt_directlink']                = 'direktna povezava';
$BL['be_cnt_target']                    = 'cilj';
$BL['be_cnt_target1']                   = 'v novem oknu';
$BL['be_cnt_target2']                   = 'v okvirju starša';
$BL['be_cnt_target3']                   = 'v istem oknu brez okvirjev';
$BL['be_cnt_target4']                   = 'v istem okvirju ali oknu';
$BL['be_cnt_bullet']                    = 'seznam (tabela)';
$BL['be_cnt_ullist']                    = 'seznam';
$BL['be_cnt_ullist_desc']               = '~ = 1. stopnja, &nbsp; ~~ = 2. stopnja, &nbsp; itd.';
$BL['be_cnt_linklist']                  = 'seznam povezav';
$BL['be_cnt_plainhtml']                 = 'preprost html';
$BL['be_cnt_files']                     = 'datoteke';
$BL['be_cnt_description']               = 'opis';
$BL['be_cnt_linkarticle']               = 'povezani članki';
$BL['be_cnt_articles']                  = 'članki';
$BL['be_cnt_movearticleto']             = 'premakni izbrani članek med povezane članke';
$BL['be_cnt_removearticleto']           = 'odstrani izbrani povezani članek iz seznama';
$BL['be_cnt_mediatype']                 = 'tip medija';
$BL['be_cnt_control']                   = 'kontrole';
$BL['be_cnt_showcontrol']               = 'prikaži kontrolno vrstico';
$BL['be_cnt_autoplay']                  = 'avtomatsko predvajaj';
$BL['be_cnt_source']                    = 'vir';
$BL['be_cnt_internal']                  = 'interni';
$BL['be_cnt_openmediabrowser']          = 'odpri brskalnik multimedijskih datotek';
$BL['be_cnt_external']                  = 'zunanji';
$BL['be_cnt_mediapos0']                 = 'levo (privzeto)';
$BL['be_cnt_mediapos1']                 = 'center';
$BL['be_cnt_mediapos2']                 = 'desno';
$BL['be_cnt_mediapos3']                 = 'levo v tekstu';
$BL['be_cnt_mediapos4']                 = 'desno v tekstu';
$BL['be_cnt_mediapos0i']                = 'poravnaj zgoraj levo nad tekstom';
$BL['be_cnt_mediapos1i']                = 'poravnaj zgoraj v center nad tekstom';
$BL['be_cnt_mediapos2i']                = 'poravnaj zgoraj desno nad tekstom';
$BL['be_cnt_mediapos3i']                = 'poravnaj levo v tekstu';
$BL['be_cnt_mediapos4i']                = 'poravnaj desno v tekstu';
$BL['be_cnt_setsize']                   = 'velikost';
$BL['be_cnt_set1']                      = 'nastavi velikost na 160x120px';
$BL['be_cnt_set2']                      = 'nastavi velikost na 240x180px';
$BL['be_cnt_set3']                      = 'nastavi velikost na 320x240px';
$BL['be_cnt_set4']                      = 'nastavi velikost na 480x360px';
$BL['be_cnt_set5']                      = 'izbriši širino in višino';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'ustvari novo obliko strani';
$BL['be_admin_page_name']               = 'ime oblike';
$BL['be_admin_page_edit']               = 'uredi obliko strani';
$BL['be_admin_page_render']             = 'izris';
$BL['be_admin_page_table']              = 'tabela';
$BL['be_admin_page_div']                = 'css div elementi';
$BL['be_admin_page_custom']             = 'poljubna';
$BL['be_admin_page_custominfo']         = 'iz glavnega bloka predloge';
$BL['be_admin_tmpl_layout']             = 'oblika';
$BL['be_admin_tmpl_nolayout']           = 'Ne obstaja nobena oblika strani!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'iskanje';
$BL['be_cnt_results']                   = 'zadetki';
$BL['be_cnt_results_per_page']          = 'na&nbsp;stran (če je prazno prikaže vse)';
$BL['be_cnt_opennewwin']                = 'odpri v novem oknu';
$BL['be_cnt_searchlabeltext']           = 'to so predefinirani teksti na obrazcu za iskanje  in strani z zadetki';
$BL['be_cnt_input']                     = 'vnosno polje';
$BL['be_cnt_style']                     = 'stil';
$BL['be_cnt_result']                    = 'zadetki';
$BL['be_cnt_next']                      = 'naprej';
$BL['be_cnt_previous']                  = 'nazaj';
$BL['be_cnt_align']                     = 'poravnava';
$BL['be_cnt_searchformtext']            = 'besedila za podrobnejšo razlago (uvodno sporočilo, sporočilo ob zadetku in sporočilu, kadar ni nobenega zadetka)';
$BL['be_cnt_intro']                     = 'uvod';
$BL['be_cnt_noresult']                  = 'brez zadetka';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'onemogoči';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'lastnik članka';
$BL['be_article_adminuser']             = 'uporabnik-administrator';
$BL['be_article_username']              = 'avtor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'oblikovano besedilo';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'vidno samo prijavljenim uporabnikom';
$BL['be_admin_struct_status']           = 'status menija';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'meni člankov';
$BL['be_cnt_sitelevel']                 = 'nivo strani';
$BL['be_cnt_sitecurrent']               = 'trenutni nivo';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'privzeto uvodno besedilo';
$BL['be_ctype_ecard']                   = 'e-razglednica';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'naslov/e-razglednica';
$BL['be_cnt_ecardtmpl']                 = 'predloga pošte';
$BL['be_cnt_ecard_image']               = 'slika e-razglednice';
$BL['be_cnt_ecard_title']               = 'naslov e-razglednice';
$BL['be_cnt_alignment']                 = 'poravnava';
$BL['be_cnt_ecardform']                 = 'predloga obrazca';
$BL['be_cnt_ecardform_err']             = 'Vsa polja označena z * so obvezna';
$BL['be_cnt_ecardform_sender']          = 'Pošiljatelj';
$BL['be_cnt_ecardform_recipient']       = 'Prejemnik';
$BL['be_cnt_ecardform_name']            = 'Ime';
$BL['be_cnt_ecardform_msgtext']         = 'Vaše sporočilo prejemniku';
$BL['be_cnt_ecardform_button']          = 'pošlji e-razglednico';
$BL['be_cnt_ecardsend']                 = 'predloga poslano';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Privzeto uvodno besedilo uredniškega sistema';
$BL['be_admin_startup_text']            = 'uvodno besedilo';
$BL['be_admin_startup_button']          = 'shrani uvodno besedilo';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'knjiga gostov/komentarji';
$BL['be_cnt_guestbook_listing']         = 'seznam';
$BL['be_cnt_guestbook_listing_all']     = 'prikaži&nbsp;vse&nbsp;zapise';
$BL['be_cnt_guestbook_list']            = 'prikaži';
$BL['be_cnt_guestbook_perpage']         = 'zapisov&nbsp;na&nbsp;stran';
$BL['be_cnt_guestbook_form']            = 'obrazec';
$BL['be_cnt_guestbook_signed']          = 'podpisan';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'pred';
$BL['be_cnt_guestbook_after']           = 'za';
$BL['be_cnt_guestbook_entry']           = 'zapis';
$BL['be_cnt_guestbook_edit']            = 'uredi';
$BL['be_cnt_ecardform_selector']        = 'izbira';
$BL['be_cnt_ecardform_radiobutton']     = 'gumb (radio button)';
$BL['be_cnt_ecardform_javascript']      = 'uporabi JavaScript';
$BL['be_cnt_ecardform_over']            = 'onmouseover';
$BL['be_cnt_ecardform_click']           = 'onclick';
$BL['be_cnt_ecardform_out']             = 'onmouseout';
$BL['be_admin_struct_topcount']         = 'maks. število člankov';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'e-časopis (newsletter)';
$BL['be_newsletter_addnl']              = 'dodaj e-časopis';
$BL['be_newsletter_titleeditnl']        = 'uredi e-časopis';
$BL['be_newsletter_newnl']              = 'ustvari nov';
$BL['be_newsletter_button_savenl']      = 'shrani e-časopis';
$BL['be_newsletter_fromname']           = 'ime od';
$BL['be_newsletter_fromemail']          = 'email od';
$BL['be_newsletter_replyto']            = 'reply email';
$BL['be_newsletter_changed']            = 'zadnja sprememba';
$BL['be_newsletter_placeholder']        = 'posebne oznake';
$BL['be_newsletter_htmlpart']           = 'HTML vsebina';
$BL['be_newsletter_textpart']           = 'TEXT vsebina';
$BL['be_newsletter_allsubscriptions']   = 'vse e-časopise';
$BL['be_newsletter_verifypage']         = 'preveri povezavo';
$BL['be_newsletter_open']               = 'HTML in TEXT vsebina';
$BL['be_newsletter_open1']              = '(kliknite na sličico)';
$BL['be_newsletter_sendnow']            = 'Pošlji e-časopis';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Pozor!</strong> Pošiljanje e-časopisa je lahko zelo sporno. Naročniki morajo biti potrjeni, drugače boste pošiljali nezaželeno pošto (spam)! Premislite preden pošiljate e-časopis. Preden ga pošljete vsem, ga preverite s testnim pošiljanjem!';
$BL['be_newsletter_attention1']         = 'Če ste zgornji e-časopis spremenili, ga prvo shranite, drugače spremembe ne bodo uporabljene!';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'pošlji e-časopis';
$BL['be_newsletter_sendprocess']        = 'proces pošiljanja';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Pozor!</strong> Prosim, ne prekinjajte procesa pošiljanja. Drugače je mogoče, da boste e-časopis poslali naročnikom večkrat. Če pošiljanje ne uspe, bodo naročniki, ki jim ni uspelo poslati pošto shranjeni in jim bo e-časopis poslan znova takoj.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">testni email naslov <strong>###TEST###</strong> ni pavilen<br />&nbsp;<br />Poskusite znova!';
$BL['be_newsletter_to']                 = 'Naročniki';
$BL['be_newsletter_ready']              = 'pošiljanje e-časopisa: KONČANO';
$BL['be_newsletter_readyfailed']        = 'Pošiljanje ni uspelo za:';
$BL['be_subnav_msg_subscribers']        = 'naročniki';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'kazalo strani (sitemap)';
$BL['be_cnt_sitemap_catimage']          = 'ikona nivoja';
$BL['be_cnt_sitemap_articleimage']      = 'ikona članka';
$BL['be_cnt_sitemap_display']           = 'prikaži';
$BL['be_cnt_sitemap_structuronly']      = 'nivoje strukture';
$BL['be_cnt_sitemap_structurarticle']   = 'nivoje strukture + članke';
$BL['be_cnt_sitemap_catclass']          = 'razred nivoja';
$BL['be_cnt_sitemap_articleclass']      = 'razred članka';
$BL['be_cnt_sitemap_count']             = 'števec';
$BL['be_cnt_sitemap_classcount']        = 'dodaj imenu razreda';
$BL['be_cnt_sitemap_noclasscount']      = 'ne dodaj imenu razreda';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'avkcija';
$BL['be_cnt_bid_bidtext']               = 'opis avkcije';
$BL['be_cnt_bid_sendtext']              = 'poslano besedilo';
$BL['be_cnt_bid_verifiedtext']          = 'preverjeno besedilo ';
$BL['be_cnt_bid_errortext']             = 'ponudba izbrisana';
$BL['be_cnt_bid_verifyemail']           = 'email za preverjanje';
$BL['be_cnt_bid_startbid']              = 'začni avkcijo';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'povačaj&nbsp;za';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'zunanja vsebina';
$BL['be_cnt_pages_select']              = 'izberite datoteko';
$BL['be_cnt_pages_fromfile']            = 'datoteka iz strukture';
$BL['be_cnt_pages_manually']            = 'poljubna pot/datoteka ali URL';
$BL['be_cnt_pages_cust']                = 'datoteka/URL';
$BL['be_cnt_pages_from']                = 'vir';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover slike';
$BL['be_cnt_reference_basis']           = 'poravnava';
$BL['be_cnt_reference_horizontal']      = 'horizontalna';
$BL['be_cnt_reference_vertical']        = 'vertikalna';
$BL['be_cnt_reference_aligntext']       = 'mala referenčna slika';
$BL['be_cnt_reference_largetext']       = 'velika referenčna slika';
$BL['be_cnt_reference_zoom']            = 'povečava';
$BL['be_cnt_reference_middle']          = 'sredina';
$BL['be_cnt_reference_border']          = 'okvir';
$BL['be_cnt_reference_block']           = 'blok š x v';

// added: 31-05-2004
$BL['be_article_rendering']             = 'izris';
$BL['be_article_nosummary']             = 'ne prikaži povzetka v polnem članku';
$BL['be_article_forlist']               = 'seznam člankov';
$BL['be_article_forfull']               = 'polni članek';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<div style="font-size: 14px;">POZOR!</div>Imenik  &quot;SETUP&quot; še vedno obstaja!<br>Izbrišite ta imenik - predstavlja varnostni problem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'prepovedane besede';
$BL['be_cnt_guestbook_flooding']        = 'poplavljanje';
$BL['be_cnt_guestbook_setcookie']       = 'nastavi piškotek (cookie)';
$BL['be_cnt_guestbook_allowed']         = 'znova dovoljeno po';
$BL['be_cnt_guestbook_seconds']         = 'sekundah';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Ali res želite izbrisati vse datoteke v košu?";
$BL['be_ftrash_delallfiles']            = 'izbriši vse datoteke v košu';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'uvoz naročnikov CSV';
$BL['be_newsletter_importtitle']        = 'uvoz naročnikov na e-časopis';
$BL['be_newsletter_entriesfound']       = 'zapisov&nbsp;našel';
$BL['be_newsletter_foundinfile']        = 'v datoteki';
$BL['be_newsletter_addresses']          = 'naslovi';
$BL['be_newsletter_csverror']           = 'CSV datoteka ni pravilna! Preverite znak za deljenje!';
$BL['be_newsletter_importall']          = 'uvozi vse zapise';
$BL['be_newsletter_addressesadded']     = 'naslovov dodani.';
$BL['be_newsletter_newimport']          = 'nov uvoz';
$BL['be_newsletter_importerror']        = 'Prosim, preverite vašo CSV datoteko - ne morem dodati nobenega naslova!';
$BL['be_newsletter_shouldbe1']          = 'Vaša CSV datoteka mora biti oblikovana na naslednji način';
$BL['be_newsletter_shouldbe2']          = 'vendar lahko izberete poljuben znak za deljenje podatkov';
$BL['be_newsletter_sample']             = 'primer';
$BL['be_newsletter_selectCSV']          = 'izberite CSV datoteko';
$BL['be_newsletter_delimeter']          = 'delitelj';
$BL['be_newsletter_importCSV']          = 'uvozi CSV datoteko';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'vrstni red člankov';
$BL['be_admin_struct_orderdate']        = 'datum kreiranja';
$BL['be_admin_struct_orderchangedate']  = 'datum spremembe';
$BL['be_admin_struct_orderstartdate']   = 'datum začetka objave';
$BL['be_admin_struct_orderdesc']        = 'navzdol';
$BL['be_admin_struct_orderasc']         = 'navgor';
$BL['be_admin_struct_ordermanual']      = 'ročno (s puščico gor/dol)';
$BL['be_cnt_sitemap_startid']           = 'začni na';



// added: 20-10-2004
$BL['be_ctype_map']                     = 'zemljevid';
$BL['be_save_btn']                      = 'shrani';
$BL['be_cmap_location_error_notitle']   = 'vnesite naslov te lokacije';
$BL['be_cnt_map_add']                   = 'dodaj lokacijo';
$BL['be_cnt_map_edit']                  = 'uredi lokacijo';
$BL['be_cnt_map_title']                 = 'naslov lokacije';
$BL['be_cnt_map_info']                  = 'zapis/informacije';
$BL['be_cnt_map_list']                  = 'seznam lokacij';
$BL['be_btn_delete']                    = 'Ali v resnici želite izbrisati to lokacijo?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP spremenljivke';
$BL['be_cnt_vars']                      = 'spremenljivke';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'kopiraj članek';
$BL['be_func_struct_nocopy']            = 'onemogoči kopiranje članka';
$BL['be_func_struct_copy_level']        = 'kopiraj strukturo';
$BL['be_func_struct_no_copy']           = "Vrhnje stopnje ni mogoče kopirati!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minuta';
$BL['be_date_minutes']                  = 'minut';
$BL['be_date_hour']                     = 'ura';
$BL['be_date_hours']                    = 'ure';
$BL['be_date_day']                      = 'dan';
$BL['be_date_days']                     = 'dni';
$BL['be_date_week']                     = 'teden';
$BL['be_date_weeks']                    = 'tedna';
$BL['be_date_month']                    = 'mesec';
$BL['be_date_months']                   = 'mescev';
$BL['be_off']                           = 'izklopljen/o';
$BL['be_on']                            = 'vklopljen/o';
$BL['be_cache']                         = 'predpomnilnik';
$BL['be_cache_timeout']                 = 'časovna omejitev';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'skupine uporabnikov';
$BL['be_admin_group_add']               = 'dodaj skupino';
$BL['be_admin_group_nogroup']           = 'ne najdem nobene skupine uporabnikov';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'seznam forumov';
$BL['be_forum_title']                   = 'naslov foruma';
$BL['be_forum_permission']              = 'pravice';
$BL['be_forum_add']                     = 'dodaj forum';
$BL['be_forum_titleedit']               = 'uredi forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'dodatni bloki';
$BL['be_show_content']                  = 'prikaži v';
$BL['be_main_content']                  = 'glavni stolpec';
$BL['be_admin_template_jswarning']      = 'Opozorilo!!! \nDodani bloki po meri se lahko spremenijo! \n\nČe prekličete, \nresetirajte vaše nastavitve oblike strani! \n\nSpremenim predlogo?\n\n';

$BL['be_ctype_rssfeed']                 = 'vir RSS';
$BL['be_cnt_rssfeed_url']               = 'url vira RSS';
$BL['be_cnt_rssfeed_item']              = 'zapisov';
$BL['be_cnt_rssfeed_max']               = 'maks.';
$BL['be_cnt_rssfeed_cut']               = 'skrij 1. zapis';

$BL['be_ctype_simpleform']              = 'obrazec';

$BL['be_cnt_onsuccess']                 = 'ob uspehu';
$BL['be_cnt_onerror']                   = 'ob napaki';
$BL['be_cnt_onsuccess_redirect']        = 'preusmeri ob uspehu';
$BL['be_cnt_onerror_redirect']          = 'preusmeri ob napaki';

$BL['be_cnt_form_class']                = 'obrazec/css razred';
$BL['be_cnt_label_wrap']                = 'prelom oznake';
$BL['be_cnt_error_class']               = 'napaka/css razred';
$BL['be_cnt_req_mark']                  = 'znak za zahtevano';
$BL['be_cnt_mark_as_req']               = 'označi kot zahtevano';
$BL['be_cnt_mark_as_del']               = 'označi za brisanje';


$BL['be_cnt_type']                      = 'tip';
$BL['be_cnt_label']                     = 'oznaka';
$BL['be_cnt_needed']                    = 'zahtevano';
$BL['be_cnt_delete']                    = 'izbriši';
$BL['be_cnt_value']                     = 'vrednost';
$BL['be_cnt_error_text']                = 'tekst ob napaki';
$BL['be_cnt_css_style']                 = 'css stil';
$BL['be_cnt_css_class']                 = 'css razred';
$BL['be_cnt_send_copy_to']              = 'kopiraj v';

$BL['be_cnt_field']           = array("text"=>'besedilo (1 vrstica)', "email"=>'email', "textarea"=>'besedilo (več vrstic)',
                        "hidden"=>'skrito polje', "password"=>'geslo', "select"=>'izbirni meni',
                        "list"=>'seznam', "checkbox"=>'potrditveno polje', "radio"=>'izbirni gumb',
                        "upload"=>'datoteka', "submit"=>'gumb za pošiljanje', "reset"=>'gumb za resetiranje',
                        "break"=>'prekinitev', "breaktext"=>'prekinitveno besedilo', "special"=>'besedilo (specialno)',
                        "captchaimg"=>'captcha slika', "captcha"=>'captcha polje', 'newsletter'=>'e-časopis');

$BL['be_cnt_access']                    = 'dostop';
$BL['be_cnt_activated']                 = 'aktiven';
$BL['be_cnt_available']                 = 'dostopen';
$BL['be_cnt_guests']                    = 'gosti';
$BL['be_cnt_admin']                     = 'administrator';
$BL['be_cnt_write']                     = 'pisanje';
$BL['be_cnt_read']                      = 'branje';

$BL['be_cnt_no_wysiwyg_editor']         = 'onemogoči WYSIWYG urejevalnik';
$BL['be_cnt_cache_update']              = 'resetiraj predpomnilnik';
$BL['be_cnt_cache_delete']              = 'briši predpomnilnik';
$BL['be_cnt_cache_delete_msg']          = 'Ali v resnici želite izbrisati predpomnilnik?\nTo lahko vpliva tudi na iskalnik.\n';

$BL['be_admin_usr_issection']           = 'prijava v';
$BL['be_admin_usr_ifsection0']          = 'frontend';
$BL['be_admin_usr_ifsection1']          = 'backend (administracija)';
$BL['be_admin_usr_ifsection2']          = 'frontend in backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']             = 'uredi ta delček vsebine';
$BL['be_func_content_paste0']           = 'prilepi v članek';
$BL['be_func_content_paste']            = 'prilepi za tem delčkom vsebine';
$BL['be_func_content_cut']              = 'izreži ta delček vsebine';
$BL['be_func_content_no_cut']           = "Ni mogoče izrezati tega delčka vsebine!";
$BL['be_func_content_copy']             = 'kopiraj ta delček vsebine';
$BL['be_func_content_no_copy']          = "Ni mogoče kopirati tega delčka vsebine!";
$BL['be_func_content_paste_cancel']     = 'prekliči spremembo delčka vsebine';

$BL['be_cnt_move_deleted']              = 'dokončno izbriši datoteke v košu';
$BL['be_cnt_move_deleted_msg']          = 'Ali v resnici želite vse datoteke iz koša premakniti\nv poseben direktorij za brisanje?  \n';

// NEZNAN
$BL['be_admin_struct_permit']           = 'avtoriziran dostop za (pustite prazno za vse)';
$BL['be_admin_struct_adduser_all']      = 'dodaj vse uporabnike';
$BL['be_admin_struct_adduser_this']     = 'dodaj izbranega uporabnika';
$BL['be_admin_struct_remove_all']       = 'odstrani vse uporabnike';
$BL['be_admin_struct_remove_this']      = 'odstrani izbranega uporabnika';


$BL['be_ctype_alias']                   = 'alias delčka vsebine';
$BL['be_cnt_setting']                   = 'prevzemi od originala';
$BL['be_cnt_spaces']                    = 'nastavitve prostora';
$BL['be_cnt_toplink']                   = 'nastavitve za povezavo na vrh';
$BL['be_cnt_block']                     = 'prikaz v bloku';
$BL['be_cnt_title']                     = 'naslov in podnaslov';

$BL['be_file_replace']                  = 'prepiši enako poimenovane datoteke?';

$BL['be_alias_articleID']               = 'alias ID';
$BL['be_alias_useAll']                  = "uporabi podatke za glavo iz originalnega članka";
$BL['be_article_morelink']              = 'povezava [več&#8230;]';
$BL['be_admin_tmpl_copy']               = 'kopiraj predlogo';

// NEZNAN
$BL['be_ctype_filelist1']                = 'file list pro';
$BL['be_cnt_fpro_usecaption']            = 'use file center &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']                = 'ključne besede';
$BL['be_admin_keywords_key']            = 'KLJUČNA BESEDA';
$BL['be_admin_keywords_err']            = 'Vstavi unikatno ime KLJUČNE BESEDE';
$BL['be_admin_keyword_edit']            = 'uredi KLJUČNO BESEDO';
$BL['be_admin_keyword_del']             = 'izbriši KLJUČNO BESEDO';
$BL['be_admin_keyword_delmsg']          = 'Ali želite v resnici izbrisati KLJUČNO BESEDO?';
$BL['be_admin_keyword_add']             = 'dodaj KLJUČNO BESEDO';

$BL['be_cnt_transparent']               = 'transparenten Flash';


// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']    = 'datum odstranitve';
$BL['be_func_switch_contentpart']       = 'Ali v resnici želite spremeniti delček vsebine?\n\nPazljivo!\nMorda bodo prepisane pomembne nastavitve!\n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>POZOR!</strong> Direktorij &quot;CODE-SNIPPETS&quot; še vedno obstaja! Izbrišite direktorij <strong>phpwcms_code_snippets</strong> - lahko predstavlja varnostno težavo.';

$BL['be_ctype_poll']                    = 'anketa';
$BL['be_cnt_pos8']                      = 'tabela, levo';
$BL['be_cnt_pos9']                      = 'tabela, desno';
$BL['be_cnt_pos8i']                     = 'postavi sliko levo od tabele';
$BL['be_cnt_pos9i']                     = 'postavi sliko desno od tabele';


$BL['be_WYSIWYG']                       = 'WYSIWYG urejevalnik';
$BL['be_WYSIWYG_disabled']              = 'WYSIWYG urejevalnik onemogočen';
$BL['be_admin_struct_acat_hiddenactive'] = 'vidno, ko je aktivno';



$BL['be_login_jsinfo']                  = 'Prosim, omogočite uporabo JavaScripta v vašem brskalniku. Za uporabo administracije je nujen.';

$BL['be_admin_struct_maxlist']          = 'maks. število člankov v seznamu';

$BL['be_admin_optgroup_label']          = array(1 => 'text', 2 => 'image', 3 => 'form', 4 => 'admin', 5 => 'special');
$BL['be_cnt_articlemenu_maxchar']       = 'maks. št. znakov';

$BL['be_cnt_sysadmin_system']           = 'sistem';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date']               = 'Nameščeno imate zadnjo verzijo phpwcms-ja. Na voljo ni nobene posodobitve.';
$BL['Version_not_up_to_date']           = 'Vaša namestitev <strong>ni</strong> posodobljena. Na voljo so posodobitve - obiščite <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a>.';
$BL['Latest_version_info']              = 'Zadnja verzija je: <b>phpwcms %s</b>.';
$BL['Current_version_info']             = 'Nameščeno imate: <b>phpwcms %s</b>.';
$BL['Connect_socket_error']             = 'Ne morem odpreti povezave do phpwcms strežnika - napaka:<br />%s';
$BL['Socket_functions_disabled']        = 'Ne morem uporaviti "socket" funkcije.';
$BL['Mailing_list_subscribe_reminder']  = 'Za prejemanje informacij o zadnjih verzijah phpwcms-ja se lahko prijavite <a href="http://eepurl.com/bm-BrH" target="_blank">tukaj</a>.';
$BL['Version_information']              = 'informacije o verziji phpwcms-ja';

$BL['be_cnt_search_highlight']          = 'poudari zadetek';
$BL['be_cnt_results_wordlimit']         = 'maks. št. besed povzetka';
$BL['be_cnt_page_of_pages']             = 'iskalna nav.';
$BL['be_cnt_page_of_pages_descr']       = '{PREV:Back} page #/##, result ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Next}';
$BL['be_cnt_search_show_top']           = 'na vrhu';
$BL['be_cnt_search_show_bottom']        = 'na dnu';
$BL['be_cnt_search_show_next']          = 'naslednja (tudi, če ni povezave)';
$BL['be_cnt_search_show_prev']          = 'prejšnja (tudi, če ni povezave)';
$BL['be_cnt_search_show_forall']        = 'prikaži vedno';
$BL['be_cnt_search_startlevel']         = 'začetek iskanja na';
$BL['be_cnt_results_minchar']           = 'minimalno št. znakov za iskanje';

$BL['be_cnt_pagination']                = 'paginacija delčkov vsebine';
$BL['be_article_pagination']            = 'paginacija člankov';
$BL['be_article_per_page']              = 'člankov na stran';
$BL['be_pagination']                    = 'paginacija';


$BL['be_ctype_recipe']                  = 'recept';
$BL['be_ctype_faq']                     = 'pogosta vprašanja (faq)';
$BL['be_cnt_additional']                = 'dodatno';
$BL['be_cnt_question']                  = 'vprašanje';
$BL['be_cnt_answer']                    = 'odgovor';
$BL['be_cnt_same_as_summary']           = 'uporabi sliko iz polnega članka';
$BL['be_cnt_sorting']                   = 'sortiranje';
$BL['be_cnt_imgupload']                 = 'nalaganje&nbsp;slike';
$BL['be_cnt_filesize']                  = 'velikost datoteke';
$BL['be_cnt_captchalength']             = 'dolžina captcha kode';
$BL['be_cnt_chars']                     = 'znakov';
$BL['be_cnt_download']                  = 'download';
$BL['be_cnt_download_direct']           = 'direkten';
$BL['be_cnt_database']                  = 'podatkovna baza';
$BL['be_cnt_formsave_in_db']            = 'shrani rezultate obrazca';

$BL['be_cnt_email_notify']              = 'obvesti po e-pošti';
$BL['be_cnt_notify_by_email']           = 'na naslov';
$BL['be_cnt_last_edited']               = 'zadnja sprememba';

$BL['be_cnt_export_selection']          = 'izvozi izbrane';
$BL['be_cnt_delete_duplicates']         = 'briši dvojnike';
$BL['be_cnt_new_recipient']             = 'dodaj naročnika';


$BL['be_cnt_newsletter_prepare']        = 'e-časopis aktiven';
$BL['be_cnt_newsletter_prepare1']       = 'vsi naročniki bodo prestavljeni v vrsto za pošiljanje';
$BL['be_cnt_newsletter_prepare2']       = 'vrsta za pošiljanje bo posodobljena&#8230;';

$BL['be_cnt_export']                    = 'izvozi';
$BL['be_cnt_formsave_profile']          = 'shrani uporabnikov profil';
$BL['be_profile_label_add']             = 'dodaj';
$BL['be_profile_label_website']         = 'spletna stran (URL)';
$BL['be_profile_label_gender']          = 'spol';
$BL['be_profile_label_birthday']        = 'rojstni dan';

$BL['be_cnt_store_in']                  = 'shrani v polje';
$BL['be_aboutlink_title']               = 'informacije o phpwcms-ju in licenca';

$BL['be_shortdate']                     = 'n/j/y';
$BL['be_shortdatetime']                 = 'n/j/y G:i';

$BL['be_confirm_sending']               = 'potrdi pošiljanje';
$BL['be_confirm_text']                  = 'Da, pošlji e-časopis vsem naročnikom!';

$BL['be_cnt_queued']                    = 'čakalna vrsta';
$BL['be_last_sending']                  = 'zadnje pošiljanje';
$BL['be_last_edited']                   = 'zadnje urejanje';
$BL['be_total']                         = 'skupaj';

$BL['be_settings']                      = 'nastavitve';
$BL['be_ctype']                         = 'delček vsebine';
$BL['be_selection']                     = 'izbira';

$BL['be_ctype_module']                  = 'plug-in';


// 1.3.5 - preview
$BL['be_longdatetime'] = "d.m.Y H:i:s";
$BL['be_cnt_lightbox'] = "galerija slik";
$BL['be_cnt_behavior'] = "obnašanje";
$BL['be_cnt_imglist_nocaption'] = "skrij podpis k sličici";
$BL['be_ctype_felogin'] = "prijava v sistem";
$BL['be_cookie_runtime'] = "iztek piškotka v";
$BL['be_locale'] = "lokalnost";
$BL['be_date_format'] = "oblika datuma";
$BL['be_check_login_against'] = "preveri prijavo z";
$BL['be_userprofile_db'] = "profili uporabnikov";
$BL['be_backenduser_db'] = "profili backend uporabnikov";
$BL['be_gb_post_login'] = "dovoli pisanje samo prijavljenim uporabnikom";
$BL['be_gb_show_login'] = "prikaži samo prijavljenim uporabnikom";
$BL['be_gb_urlcheck'] = "omogoči daljinsko preverjanje URL naslova";
$BL['be_order'] = "vrstni red";
$BL['be_unique_teaser_entry'] = "prikaži povzetek/povezavo članka samo enkrat na stran";
$BL['be_allowed_tags'] = "dovoljene oznake (tags)";
$BL['be_fe_login_url'] = "url za prijavo v frontend";
$BL['be_ctype_imagesdiv'] = "css slike";
$BL['be_cnt_imagecenter'] = "centriraj horizontalno/vertikalno";
$BL['be_cnt_imagenocenter'] = "ne centriraj";
$BL['be_cnt_imagecenterh'] = "centriraj horizontalno";
$BL['be_cnt_imagecenterv'] = "centriraj vertikalno";
$BL['be_overwrite_default'] = "To bo prepisalo privzete vrednosti konfiguracijske datoteke";
$BL['be_cnt_sortvalue'] = "vrstni red";
$BL['be_dialog_warn_nosave'] = "Nastavitve ne bodo shranjene!\nAli ste prepričani, da želite nadaljevati?";
$BL['be_cnt_paginate_subsection'] = "podsekcija";
$BL['be_cnt_subsection_tite'] = "naslov podsekcije";
$BL['be_cnt_subsection_warning'] = "Številčenje podsekcij (paginiranje vsebine) je mogoče le za \nglavni stolpec (CONTENT)!";
$BL['be_no_search'] = "preskoči v iskanju";
$BL['be_priorize'] = "prioriteta";
$BL['be_change_articleID'] = "spremeni ID članka";
$BL['be_title_wrap'] = "lomi naslov članka";
$BL['be_no_rss'] = "RSS";
$BL['be_article_urlalias'] = "alias članka";
$BL['be_image_crop'] = "izsek sličice";
$BL['be_image_align'] = "postavitev slike";
$BL['be_ctype_flashplayer'] = "flash predvajalnik";
$BL['be_flashplayer_caption'] = "naslov";
$BL['be_flashplayer_thumbnail'] = "sličica";
$BL['be_flashplayer_selectsize'] = "Izberite velikost predvajalnika";
$BL['be_check_feuser_profile'] = "profil frontend uporabnika";
$BL['be_check_feuser_registration'] = "registracija";
$BL['be_check_feuser_manage'] = "upravlja uporabnik";

// r213 -- 2008-08-14
$BL['be_image_cropit'] = "obreži sliko";
$BL['be_hide_active_articlelink'] = "skrij aktivni članek v meniju člankov";
$BL['be_module_search'] = "išči tudi";
$BL['be_ctype_imagesspecial'] = "slike (specialno)";
$BL['be_image_WxHpx'] = "širina &times; višina px";
$BL['be_fx_1'] = "efekt 1";
$BL['be_fx_2'] = "efekt 2";
$BL['be_fx_3'] = "efekt 3";
$BL['be_image_zoom'] = "povečan pogled";
$BL['be_image_delete_js'] = "Ali želite izbrisati izbrano sliko?";
$BL['be_news'] = "novice";
$BL['be_news_create'] = "nova novica";
$BL['be_tags'] = "oznake";
$BL['be_title'] = "naslov";
$BL['be_delete_dataset'] = "Izbrišem izbrane podatke?";
$BL['be_action_notvalid'] = "Vaša zadnja izbrana kacija je bila prekinjena, saj je neveljavna!";
$BL['be_action_deleted'] = "Izbrani podatki (ID {ID}) so bili izbrisani.";
$BL['be_action_status'] = "Status izbranih podatkov (ID {ID}) so bili spremenjeni.";
$BL['be_data_select_failed'] = "Dostop do izbranih podatkov ni mogoč. Preverite vašo izbiro.";
$BL['be_alias'] = "alias";
$BL['be_url_value'] = "URL naslov";
$BL['default_date_format'] = "DD.MM.YYYY";
$BL['default_date'] = "d.m.Y";
$BL['default_date_delimiter'] = ".";
$BL['default_time_format'] = "HH:MM";
$BL['default_time'] = "H:i";
$BL['be_place'] = "položaj";
$BL['be_teasertext'] = "uvodno besedilo";
$BL['be_published'] = "objavi";
$BL['be_show_archived'] = "dosegljivo po končnem datumu (arhiv)";
$BL['be_save_copy'] = "shrani zapis kot duplikat";
$BL['be_read_more_link'] = "več URL/ID";
$BL['be_news_name_mandatory'] = "Izpolni naslov novice!";
$BL['be_successfully_saved'] = "Vsi podatki so bili uspešno shranjeni!";
$BL['be_successfully_updated'] = "Vsi podatki so bili uspešno posodobljeni!";
$BL['be_error_while_save'] = "Napaka pri shranjevanju podatkov.";
$BL['be_copyright'] = "avtorske pravice";
$BL['be_file_multiple_upload'] = "hkratno nalaganje večih datotek";
$BL['be_files_browse'] = "izberi datoteke";
$BL['be_files_upload'] = "naloži izbrane datoteke";
$BL['be_archive'] = "arhiv";
$BL['be_random'] = "naključno";
$BL['be_sorted'] = "urejeno";
$BL['be_granted_download'] = "samo zaščiten prenos";
$BL['be_granted_feuser'] = "samo za prijavljene frontend uporabnike";
$BL['be_ctype_tabs'] = "zavihki";
$BL['be_tab_add'] = "dodaj zavihek";
$BL['be_tab_name'] = "zavihek";
$BL['be_headline'] = "naslov";
$BL['be_tab_delete_js'] = "Ali želite izbrisati izbrani zavihek?";
$BL['be_pagniate_count'] = "zapisov na stran";
$BL['be_limit_to'] = "omejitev na";
$BL['be_archived_items'] = "arhivirani zapisi";
$BL['be_include'] = "vključi";
$BL['be_exclude'] = "izključi";
$BL['be_solely'] = "izključno";
$BL['be_fsearch_not'] = "NE";
$BL['be_date_year'] = "leto";
$BL['be_archive_link'] = "povezava v arhiv";
$BL['be_use_prio'] = "uporabi prioritizacijo";
$BL['be_skip_first_items'] = "preskoči vrhnje zapise";
$BL['be_news_detail_link'] = "novica - članek";
$BL['be_gallerydownload'] = "dovoli prenosi iz galerije";
$BL['be_gallery_root'] = "osnovni direktorij galerije";
$BL['be_gallery_directory'] = "poddirektorij galerije";
$BL['be_gallery'] = "galerija";

$BL['be_sort_date'] = "uredi po datumu";
