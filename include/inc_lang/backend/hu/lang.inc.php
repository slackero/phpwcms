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


// Language: Hungarian, Language Code: hu
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'online felhasználók';

// Login Page
$BL["login_text"]                       = 'Kérem, adja meg a bejelentkezés adatait';
$BL['login_error']                      = 'Hiba a bejelentkezéskor!';
$BL["login_username"]                   = 'felhasználó neve';
$BL["login_userpass"]                   = 'jelszó';
$BL["login_button"]                     = 'Bejelenkezés';
$BL["login_lang"]                       = 'backend (adminisztráció) nyelve';

// phpwcms.php
$BL['be_nav_logout']                    = 'KIJELENTKEZÉS';
$BL['be_nav_articles']                  = 'CIKKEK';
$BL['be_nav_files']                     = 'FILE';
$BL['be_nav_modules']                   = 'MODULOK';
$BL['be_nav_messages']                  = 'ÜZENET';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'FÓRUM';

$BL['be_page_title']                    = 'phpwcms backend (adminisztráció)';

$BL['be_subnav_article_center']         = 'cikkek';
$BL['be_subnav_article_new']            = 'új cikk';
$BL['be_subnav_file_center']            = 'file-ok';
$BL['be_subnav_file_ftptakeover']       = 'ftp feltöltétés';
$BL['be_subnav_mod_artists']            = 'szerzõ, kategória, mûfaj';
$BL['be_subnav_msg_center']             = 'üzenetek';
$BL['be_subnav_msg_new']                = 'új üzenet';
$BL['be_subnav_msg_newsletter']         = 'hírlevél elõfizetések';
$BL['be_subnav_chat_main']              = 'chat kezdõoldal';
$BL['be_subnav_chat_internal']          = 'belsõ chat';
$BL['be_subnav_profile_login']          = 'bejelentkezés adatai';
$BL['be_subnav_profile_personal']       = 'személyes adatok';
$BL['be_subnav_admin_pagelayout']       = 'lapszerkezet (layout)';
$BL['be_subnav_admin_templates']        = 'sablonok (templates)';
$BL['be_subnav_admin_css']              = 'alapértelmezett css';
$BL['be_subnav_admin_sitestructure']    = 'weblap (site) struktúra';
$BL['be_subnav_admin_users']            = 'felhasználó adminisztráció';
$BL['be_subnav_admin_filecat']          = 'file kategóriák';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'cikk ID';
$BL['be_func_struct_preview']           = 'elõnézet (preview)';
$BL['be_func_struct_edit']              = 'cikk szerkesztése';
$BL['be_func_struct_sedit']             = 'struktúra szint szerkesztése';
$BL['be_func_struct_cut']               = 'cikk kivágása';
$BL['be_func_struct_nocut']             = 'cikk kivágásának tiltása (visszavonása)';
$BL['be_func_struct_svisible']          = 'váltás látható/láthatatlan';
$BL['be_func_struct_spublic']           = 'váltás publikus / nem publikus';
$BL['be_func_struct_sort_up']           = 'átrendezés: fel';
$BL['be_func_struct_sort_down']         = 'átrendezés: le';
$BL['be_func_struct_del_article']       = 'cikk törlése';
$BL['be_func_struct_del_jsmsg']         = 'Biztos törli a \ncikket?';
$BL['be_func_struct_new_article']       = 'új cikk létrehozása a struktúra szinten belül';
$BL['be_func_struct_paste_article']     = 'cikk beillestése a struktúra szinten belülre';
$BL['be_func_struct_insert_level']      = 'struktúra szint beillesztése ide:';
$BL['be_func_struct_paste_level']       = 'beillesztés a struktúra szintbe:';
$BL['be_func_struct_cut_level']         = 'kivágás, struktúra szint:';
$BL['be_func_struct_no_cut']            = "Nem lehetséges a gyökér szintet kivágni!";
$BL['be_func_struct_no_paste1']         = "Nem lehetséges ide beilleszteni!";
$BL['be_func_struct_no_paste2']         = 'a fa szint gyökérének gyermeke';
$BL['be_func_struct_no_paste3']         = 'ide kellene beilleszteni';
$BL['be_func_struct_paste_cancel']      = 'a struktúra szint változtatás vége';
$BL['be_func_struct_del_struct']        = 'struktúra szint törlése';
$BL['be_func_struct_del_sjsmsg']        = 'Biztos törli a \nstruktúra szintet?';
$BL['be_func_struct_open']              = 'megnyitás';
$BL['be_func_struct_close']             = 'lezárás';
$BL['be_func_struct_empty']             = 'üres';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'egyszerû szöveg';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kód';
$BL['be_ctype_textimage']               = 'szöveg képpel';
$BL['be_ctype_images']                  = 'képek';
$BL['be_ctype_bulletlist']              = 'lista (felsorolás)';
$BL['be_ctype_ullist']                  = 'lista';
$BL['be_ctype_link']                    = 'link &amp; email';
$BL['be_ctype_linklist']                = 'link lista';
$BL['be_ctype_linkarticle']             = 'cikk link';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'file lista';
$BL['be_ctype_emailform']               = 'email ûrlap';
$BL['be_ctype_newsletter']              = 'hírlevél';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'A profil sikeresen létrejött';
$BL['be_profile_create_error']          = 'Hiba a létrehozás során';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'A profil sikeresen módosítva';
$BL['be_profile_update_error']          = 'Hiba a módosítás során';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'felhaszáló név (VAL) érvénytelen';
$BL['be_profile_account_err2']          = 'túl rövid jelszó (csak {VAL} karakter: legalább 5 szükséges)';
$BL['be_profile_account_err3']          = 'a jelszó és a megismételt jelszó pontosan meg kell egyezzen';
$BL['be_profile_account_err4']          = 'érvénytelen email {VAL}';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'az Ön személyes adatai';
$BL['be_profile_data_text']             = 'a személyes adatok megdása opcionális. Segítheti a többi felhasználót, vagy a weblap látógatóit, hogy többet tudjanak meg érdeklõdési körérõl, képzettségérõl. Ha bejelöli a megfelelõ checkbox-ot, a felhasználók láthatják az ön személyes adatait (vagy nem, ha letiltja).';
$BL['be_profile_label_title']           = 'titulus';
$BL['be_profile_label_firstname']       = 'keresztnév';
$BL['be_profile_label_name']            = 'vezetéknév';
$BL['be_profile_label_company']         = 'cég';
$BL['be_profile_label_street']          = 'utca';
$BL['be_profile_label_city']            = 'város';
$BL['be_profile_label_state']           = 'megye';
$BL['be_profile_label_zip']             = 'irányítószám';
$BL['be_profile_label_country']         = 'ország';
$BL['be_profile_label_phone']           = 'telefon';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobil';
$BL['be_profile_label_signature']       = 'aláírás';
$BL['be_profile_label_notes']           = 'megjegyzés';
$BL['be_profile_label_profession']      = 'foglalkozás';
$BL['be_profile_label_newsletter']      = 'hírlevél';
$BL['be_profile_text_newsletter']       = 'Feliratokozom a general phpwcms newsletter-re';
$BL['be_profile_label_public']          = 'publikus';
$BL['be_profile_text_public']           = 'Bárki láthatja a személyes adataimat.';
$BL['be_profile_label_button']          = 'személyes adatok módosítása';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'az ön bejelentkezési adatai';
$BL['be_profile_account_text']          = 'Normális esetben nem szükséges, hogy a felhaszálói nevét megváltoztassa.<br />Cserélje rendszeresen a jelszavát, a biztonság növelése érdekében!';
$BL['be_profile_label_err']             = 'kérem, ellenõrizze';
$BL['be_profile_label_username']        = 'felhaszáló neve';
$BL['be_profile_label_newpass']         = 'új jelszó';
$BL['be_profile_label_repeatpass']      = 'új jelszó mégegyszer';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'bejelentkezési adatok módosítása';
$BL['be_profile_label_lang']            = 'nyelv';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'ftp-vel feltöltött file-ok átvétele';
$BL['be_ftptakeover_mark']              = 'jel';
$BL['be_ftptakeover_available']         = 'rendelkezésre álló file-ok';
$BL['be_ftptakeover_size']              = 'méret';
$BL['be_ftptakeover_nofile']            = 'még nincs egy file sem, feltölthet ftp segítségével';
$BL['be_ftptakeover_all']               = 'MIND';
$BL['be_ftptakeover_directory']         = 'könyvtár';
$BL['be_ftptakeover_rootdir']           = 'gyökér könyvtár';
$BL['be_ftptakeover_needed']            = 'szükséges!!! (egyet választani)';
$BL['be_ftptakeover_optional']          = 'opcionális';
$BL['be_ftptakeover_keywords']          = 'kulcsszavak';
$BL['be_ftptakeover_additional']        = 'kiegészítõ';
$BL['be_ftptakeover_longinfo']          = 'bõvebb információ';
$BL['be_ftptakeover_status']            = 'státusz';
$BL['be_ftptakeover_active']            = 'aktív';
$BL['be_ftptakeover_public']            = 'publikus';
$BL['be_ftptakeover_createthumb']       = 'kis kép (thumbnail) létrehozása';
$BL['be_ftptakeover_button']            = 'a kiválasztott file-ok átvétele';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'file-ok';
$BL['be_ftab_createnew']                = 'új file létrehozása a gyökér könyvtárba';
$BL['be_ftab_paste']                    = 'file beillesztése a gyökér könyvtárba vágólapról';
$BL['be_ftab_disablethumb']             = 'kis kép (thumbnail) letiltása';
$BL['be_ftab_enablethumb']              = 'kis kép (thumbnail) engedélyezése';
$BL['be_ftab_private']                  = 'privát&nbsp;file-ok';
$BL['be_ftab_public']                   = 'publikus&nbsp;file-ok';
$BL['be_ftab_search']                   = 'keresés';
$BL['be_ftab_trash']                    = 'lomtár';
$BL['be_ftab_open']                     = 'minden könytár megnyitása';
$BL['be_ftab_close']                    = 'minden nyitott könyvtár bezárása';
$BL['be_ftab_upload']                   = 'file feltöltése a gyökér könyvtárba';
$BL['be_ftab_filehelp']                 = 'file súgó megnyitása';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'gyökér könyvtár';
$BL['be_fpriv_title']                   = 'új könyvtár létrehozása';
$BL['be_fpriv_inside']                  = 'ezen belülre';
$BL['be_fpriv_error']                   = 'hiba: könytár nevének kitöltése';
$BL['be_fpriv_name']                    = 'név';
$BL['be_fpriv_status']                  = 'státusz';
$BL['be_fpriv_button']                  = 'új könyvtár létrehozása';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'könyvtár módosítás';
$BL['be_fpriv_newname']                 = 'új név';
$BL['be_fpriv_updatebutton']            = 'könyvtár információ módosítás';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Válassza ki a feltöltendõ file-okat';
$BL['be_fprivup_err2']                  = 'A feltöltött file-ok mérete nagyobb mint';
$BL['be_fprivup_err3']                  = 'Hiba írás közben';
$BL['be_fprivup_err4']                  = 'Hiba könyvtár létrehozás közben';
$BL['be_fprivup_err5']                  = 'nincs kis kép (thumbnail)';
$BL['be_fprivup_err6']                  = 'Kérem ne próbálja újra - szerver hiba! Vegye fel a kapcsolatot a <a href="mailto:{VAL}">webmesterrel</a> mielõbb';
$BL['be_fprivup_title']                 = 'file-ok feltöltése';
$BL['be_fprivup_button']                = 'file-ok feltöltése';
$BL['be_fprivup_upload']                = 'feltöltése';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'file információk módosítása';
$BL['be_fprivedit_filename']            = 'file-név';
$BL['be_fprivedit_created']             = 'létrehozva';
$BL['be_fprivedit_dateformat']          = 'Y-m-d H:i';
$BL['be_fprivedit_err1']                = 'file név felülvizsgálat (erdeti visszaállítás)';
$BL['be_fprivedit_clockwise']           = 'forgatása órajárás szerint [eredeti file +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'forgatása órajárás ellen [eredeti file -90&deg;]';
$BL['be_fprivedit_button']              = 'file információk módosítása';
$BL['be_fprivedit_size']                = 'méret';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'file feltöltése a könyvtárba';
$BL['be_fprivfunc_makenew']             = 'új könyvtár létrehozása ezen belülre';
$BL['be_fprivfunc_paste']               = 'a vágólapon levõ file beillesztése a könyvtárba';
$BL['be_fprivfunc_edit']                = 'könyvtár szerkesztése';
$BL['be_fprivfunc_cactive']             = 'váltás aktív/inaktív';
$BL['be_fprivfunc_cpublic']             = 'vátás publikus/nem publikus';
$BL['be_fprivfunc_deldir']              = 'könyvtár törlése';
$BL['be_fprivfunc_jsdeldir']            = 'Biztos törli \na könyvtárat?';
$BL['be_fprivfunc_notempty']            = 'a könyvtár {VAL} nem üres';
$BL['be_fprivfunc_opendir']             = 'könyvtár megnyitása';
$BL['be_fprivfunc_closedir']            = 'könyvtár bezárása';
$BL['be_fprivfunc_dlfile']              = 'file letöltése';
$BL['be_fprivfunc_clipfile']            = 'vágólap file';
$BL['be_fprivfunc_cutfile']             = 'kivágás';
$BL['be_fprivfunc_editfile']            = 'file információk szerkesztése';
$BL['be_fprivfunc_cactivefile']         = 'váltás aktív/inaktív';
$BL['be_fprivfunc_cpublicfile']         = 'vátás publikus/nem publikus';
$BL['be_fprivfunc_movetrash']           = 'lomtárba tesz';
$BL['be_fprivfunc_jsmovetrash1']        = 'Biztos a lomtár';
$BL['be_fprivfunc_jsmovetrash2']        = 'könyvtárba teszi?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'nincs privát file vagy könyvtár';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'felhasználó';
$BL['be_fpublic_nofiles']               = 'nincs publikus file vagy könyvtár';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'a lomtár üres';
$BL['be_ftrash_show']                   = 'privát file-ok megtekintése';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Visszaállítja {VAL} -t\nés visszateszi a privát listába?';
$BL['be_ftrash_delete']                 = 'Biztos törli {VAL} -t?';
$BL['be_ftrash_undo']                   = 'visszállítás (törlés visszavonás)';
$BL['be_ftrash_delfinal']               = 'végleges törlés';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'a keresõ mezõ üres';
$BL['be_fsearch_title']                 = 'file keresése';
$BL['be_fsearch_infotext']              = 'File információk egyszerû keresése. Egyezést keres a kulcsszavak ,<br /> file-név és a bõvebb file információban. Nem támogatja wildcard megadását. A keresõ szavakat szóközzel kell elválaztani. Válassza ki a keresõ szavak közti logikai kapcsolatot ÉS/VAGY és a file típusát privát/publikus';
$BL['be_fsearch_nonfound']              = 'Nincs file, amely megfelelne a feltételnek. Módosítsa a keresési feltételt.';
$BL['be_fsearch_fillin']                = 'Kérem töltse ki a fenti keresõ mezõt.';
$BL['be_fsearch_searchlabel']           = 'keresendõ';
$BL['be_fsearch_startsearch']           = 'keresés indítása';
$BL['be_fsearch_and']                   = 'ÉS';
$BL['be_fsearch_or']                    = 'VAGY';
$BL['be_fsearch_all']                   = 'minden';
$BL['be_fsearch_personal']              = 'privát';
$BL['be_fsearch_public']                = 'publikus';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'belsõ chat';
$BL['be_chat_info']                     = 'Itt bármirõl chat-elhet más phpwcms backend felhasználókkal. Ez valós idejû beszálgetésre alkalmas, de lehet olyan üzenetet írni, amit mindenki olvashat. Ha eszmét akar cserélni más felhasználókkal, használja a discussion-t (késõbbi phpwcms verzióban).';
$BL['be_chat_start']                    = 'kattintson ide a chat elkezdéséhez';
$BL['be_chat_lines']                    = 'chat sorok';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'üzenetek';
$BL['be_msg_new']                       = 'új';
$BL['be_msg_old']                       = 'régi';
$BL['be_msg_senttop']                   = 'küldött';
$BL['be_msg_del']                       = 'törölt';
$BL['be_msg_from']                      = 'feladó';
$BL['be_msg_subject']                   = 'tárgy';
$BL['be_msg_date']                      = 'dátum/idõ';
$BL['be_msg_close']                     = 'üzenet lezárása';
$BL['be_msg_create']                    = 'új üzenet létrehozása';
$BL['be_msg_reply']                     = 'válasz az üzenetre';
$BL['be_msg_move']                      = 'az üzenetet lomtárba teszi';
$BL['be_msg_unread']                    = 'olvasatlan vagy új üzenetek';
$BL['be_msg_lastread']                  = 'utolsó {VAL} olvasott üzenet';
$BL['be_msg_lastsent']                  = 'utolsó {VAL} elküldött üzenet';
$BL['be_msg_marked']                    = 'törlésre kijelölt üzenetek (lomtár)';
$BL['be_msg_nomsg']                     = 'nincs üzenet ebben a mappában';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'küldte';
$BL['be_msg_on']                        = 'dátum';
$BL['be_msg_msg']                       = 'üzenet';
$BL['be_msg_err1']                      = 'elfelejtette a címzettet beállítani';
$BL['be_msg_err2']                      = 'töltse ki a tárgy mezõt (a címzett így jobban tudja kezelni az üzenetet)';
$BL['be_msg_err3']                      = 'nem írt üzenetet';
$BL['be_msg_sent']                      = 'az új üzenet elküldve';
$BL['be_msg_fwd']                       = 'Önt átirányítjuk az üzenetek menübe, vagy';
$BL['be_msg_newmsgtitle']               = 'új üzenet írása';
$BL['be_msg_err']                       = 'hiba az üzenet küldésekor';
$BL['be_msg_sendto']                    = 'címzett';
$BL['be_msg_available']                 = 'az elérhetõ címzettek listája';
$BL['be_msg_all']                       = 'üzenet küldése az összes kiválasztott címzettnek';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'hírlevél elõfizetések';
$BL['be_newsletter_titleedit']          = 'hírlevél elõfizetés szerkesztése';
$BL['be_newsletter_new']                = 'új létrehozás';
$BL['be_newsletter_add']                = 'hírlevél&nbsp;elõfizetés&nbsp;hozzáadása';
$BL['be_newsletter_name']               = 'név';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'elõfizetés mentése';
$BL['be_newsletter_button_cancel']      = 'mégsem';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'a felhasználói név érvénytelen, válasszon másikat';
$BL['be_admin_usr_err2']                = 'a felhasználói név üres (ki kell tölteni)';
$BL['be_admin_usr_err3']                = 'a jelszó üres (ki kell tölteni)';
$BL['be_admin_usr_err4']                = "email érvénytelen";
$BL['be_admin_usr_err']                 = 'hiba';
$BL['be_admin_usr_mailsubject']         = 'üdvözlöm a phpwcms backend-ben';
$BL['be_admin_usr_mailbody']            = "ÜDVÖZLÖM A PHPWCMS BACKEND-ben\n\n    felhasználói neve: {LOGIN}\n    jelszó: {PASSWORD}\n\n\nIde jelentkezhet be: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'új felhasználó hozzáadása';
$BL['be_admin_usr_realname']            = 'igazi név';
$BL['be_admin_usr_setactive']           = 'felhasználó aktiválása';
$BL['be_admin_usr_iflogin']             = 'ha beállítja, a felhasználó bejelentkezhet';
$BL['be_admin_usr_isadmin']             = 'a felhasználó adminisztrátor';
$BL['be_admin_usr_ifadmin']             = 'ha beállítja, a felhasználó adminisztrátori jogot kap';
$BL['be_admin_usr_verify']              = 'megerõsítes';
$BL['be_admin_usr_sendemail']           = 'email küldése az új felhasználónak a bejelentkezés adataival';
$BL['be_admin_usr_button']              = 'felhasználói adatok küldése';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'felhasználó szerkesztése';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - felhasználói adatok megváltoztak';
$BL['be_admin_usr_emailbody']           = "PHPWCMS FELHASZNÁLÓI ADATOK MEGVÁLTOZTAK\n\n    felhasználói neve: {LOGIN}\n    jelszó: {PASSWORD}\n\n\nIde jelentkezhet be: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[NINCS VÁLTOZÁS - A RÉGI JELSZÓT HASZNÁLJA]';
$BL['be_admin_usr_ebutton']             = 'felhasználói adatok módosítása';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms felhasználók listája';
$BL['be_admin_usr_ldel']                = 'FIGYELEM!&#13Ez törli a felhasználót';
$BL['be_admin_usr_create']              = 'új felhasználó létrehozása';
$BL['be_admin_usr_editusr']             = 'felhasználó szerkesztése';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'weblap (site) struktúra';
$BL['be_admin_struct_child']            = '(gyermeke a stuktúra szintnek)';
$BL['be_admin_struct_index']            = 'index (website start)';
$BL['be_admin_struct_cat']              = 'kategória cím';
$BL['be_admin_struct_hide1']            = 'elrejt';
$BL['be_admin_struct_hide2']            = 'ez a kategória a menüben';
$BL['be_admin_struct_info']             = 'kategória info szöveg';
$BL['be_admin_struct_template']         = 'sablon&nbsp;(template )';
$BL['be_admin_struct_alias']            = 'kategória alias';
$BL['be_admin_struct_visible']          = 'látható';
$BL['be_admin_struct_button']           = 'kategória adatok elküldése';
$BL['be_admin_struct_close']            = 'bezárás';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'file kategóriák';
$BL['be_admin_fcat_err']                = 'a kategória név üres';
$BL['be_admin_fcat_name']               = 'kategória név';
$BL['be_admin_fcat_needed']             = 'szükséges';
$BL['be_admin_fcat_button1']            = 'módosítás';
$BL['be_admin_fcat_button2']            = 'létrehozás';
$BL['be_admin_fcat_delmsg']             = 'Biztos törli a\nfile kulcsot?';
$BL['be_admin_fcat_fcat']               = 'file kategória';
$BL['be_admin_fcat_err1']               = 'file kulcs név üres';
$BL['be_admin_fcat_fkeyname']           = 'file kulcs név';
$BL['be_admin_fcat_exit']               = 'kilépés';
$BL['be_admin_fcat_addkey']             = 'új kulcs hozzáadása';
$BL['be_admin_fcat_editcat']            = 'kategória név szeresztése';
$BL['be_admin_fcat_delcatmsg']          = 'Biztos törli a\nfile kategóriát';
$BL['be_admin_fcat_delcat']             = 'file kategória törlése';
$BL['be_admin_fcat_delkey']             = 'file kulcs név törlése';
$BL['be_admin_fcat_editkey']            = 'kulcs szerkesztése';
$BL['be_admin_fcat_addcat']             = 'új file kategória létrehozása';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend setup: lapszerkezet (page layout)';
$BL['be_admin_page_align']              = 'lap igazítás';
$BL['be_admin_page_align_left']         = 'standard igazítás (balra) a lap teljes tartalmát';
$BL['be_admin_page_align_center']       = 'középre a lap teljes tartalmát';
$BL['be_admin_page_align_right']        = 'jobbra a lap teljes tartalmát';
$BL['be_admin_page_margin']             = 'margó';
$BL['be_admin_page_top']                = 'felsõ';
$BL['be_admin_page_bottom']             = 'alsó';
$BL['be_admin_page_left']               = 'bal';
$BL['be_admin_page_right']              = 'jobb';
$BL['be_admin_page_bg']                 = 'háttér';
$BL['be_admin_page_color']              = 'szín';
$BL['be_admin_page_height']             = 'magasság';
$BL['be_admin_page_width']              = 'szélesség';
$BL['be_admin_page_main']               = 'fõrész';
$BL['be_admin_page_leftspace']          = 'bal térköz';
$BL['be_admin_page_rightspace']         = 'jobb térköz';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'kép';
$BL['be_admin_page_text']               = 'szöveg';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'látogatott';
$BL['be_admin_page_pagetitle']          = 'lap&nbsp;cím';
$BL['be_admin_page_addtotitle']         = 'címhez&nbspad';
$BL['be_admin_page_category']           = 'kategória';
$BL['be_admin_page_articlename']        = 'cikk&nbsp;neve';
$BL['be_admin_page_blocks']             = 'blokkok';
$BL['be_admin_page_allblocks']          = 'összes blokk';
$BL['be_admin_page_col1']               = '3 oszlopos elrendezés';
$BL['be_admin_page_col2']               = '2 oszlopos elrendezés (fõ oszlop jobbra, navigációs oszlop balra)';
$BL['be_admin_page_col3']               = '2 oszlopos elrendezés (fõ oszlop balra, navigációs oszlop jobbra)';
$BL['be_admin_page_col4']               = '1 oszlopos elrendezés';
$BL['be_admin_page_header']             = 'fejléc';
$BL['be_admin_page_footer']             = 'lábléc';
$BL['be_admin_page_topspace']           = 'felsõ térköz';
$BL['be_admin_page_bottomspace']        = 'alsó térköz';
$BL['be_admin_page_button']             = 'lapszerkezet mentése';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend setup: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'mentés css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'fontend setup: sablonok (templates)';
$BL['be_admin_tmpl_default']            = 'alapértelmezett';
$BL['be_admin_tmpl_add']                = 'sablon&nbsp;hozzáadása';
$BL['be_admin_tmpl_edit']               = 'sablon (template) szerkesztése';
$BL['be_admin_tmpl_new']                = 'új létrehozása';
$BL['be_admin_tmpl_css']                = 'css file';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'hiba';
$BL['be_admin_tmpl_button']             = 'sablon mentése';
$BL['be_admin_tmpl_name']               = 'név';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'weblap (site) struktúra és cikklista';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'a cikk címe üres';
$BL['be_article_err2']                  = 'kezdés dátum megadása hibás - beállítva az aktuális';
$BL['be_article_err3']                  = 'vége dátum hibás - beállítva az aktuális';
$BL['be_article_title1']                = 'cikk alap információ';
$BL['be_article_cat']                   = 'kategória';
$BL['be_article_atitle']                = 'cikk cím';
$BL['be_article_asubtitle']             = 'alcím';
$BL['be_article_abegin']                = 'kezdés';
$BL['be_article_aend']                  = 'vége';
$BL['be_article_aredirect']             = 'redirect to';
$BL['be_article_akeywords']             = 'kulcsszavak';
$BL['be_article_asummary']              = 'összefoglalás';
$BL['be_article_abutton']               = 'új cikk létrehozása';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'a vég dátum hibás - beállítva aktuális + 1 hét-re';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'cikk alap információ szerkesztése';
$BL['be_article_eslastedit']            = 'utolsó szerkesztés';
$BL['be_article_esnoupdate']            = 'az adatok nem módusultak';
$BL['be_article_esbutton']              = 'cikk adatok módosítása';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'cikk tartalom';
$BL['be_article_cnt_type']              = 'tartalom típus';
$BL['be_article_cnt_space']             = 'térköz';
$BL['be_article_cnt_before']            = 'elõtte';
$BL['be_article_cnt_after']             = 'utána';
$BL['be_article_cnt_top']               = 'fel (top)';
$BL['be_article_cnt_ctitle']            = 'tartalom cím';
$BL['be_article_cnt_back']              = 'teljes cikk info';
$BL['be_article_cnt_button1']           = 'tartalom módosítása';
$BL['be_article_cnt_button2']           = 'tartalom létrehozása';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'cikk információ';
$BL['be_article_cnt_ledit']             = 'cikk szerkesztése';
$BL['be_article_cnt_lvisible']          = 'váltás látható/láthatatlan';
$BL['be_article_cnt_ldel']              = 'a cikk törlése';
$BL['be_article_cnt_ldeljs']            = 'Biztos törli a cikket?';
$BL['be_article_cnt_redirect']          = 'átirányítás';
$BL['be_article_cnt_edited']            = 'szerkesztette';
$BL['be_article_cnt_start']             = 'kezdõ dátum';
$BL['be_article_cnt_end']               = 'vég dátum';
$BL['be_article_cnt_add']               = 'új tartalom rész hozzáadása';
$BL['be_article_cnt_up']                = 'tartalom mozgatása fel';
$BL['be_article_cnt_down']              = 'tartalom mozgatása le';
$BL['be_article_cnt_edit']              = 'tartalom rész szerkesztése';
$BL['be_article_cnt_delpart']           = 'a tartalom rész törlése';
$BL['be_article_cnt_delpartjs']         = 'Biztos törli a tartalom részt?';
$BL['be_article_cnt_center']            = 'cikkek';

// content forms
$BL['be_cnt_plaintext']                 = 'egyszerû szöveg';
$BL['be_cnt_htmltext']                  = 'html text';
$BL['be_cnt_image']                     = 'kép';
$BL['be_cnt_position']                  = 'pozíció';
$BL['be_cnt_pos0']                      = 'Fel balra';
$BL['be_cnt_pos1']                      = 'Fel középre';
$BL['be_cnt_pos2']                      = 'Fel jobbra';
$BL['be_cnt_pos3']                      = 'Le balra';
$BL['be_cnt_pos4']                      = 'Le középre';
$BL['be_cnt_pos5']                      = 'Le jobbra';
$BL['be_cnt_pos6']                      = 'Szövegben balra';
$BL['be_cnt_pos7']                      = 'Szövegben jobbra';
$BL['be_cnt_pos0i']                     = 'a szövegblokk felsõ, bal oldalához igazítja a képet';
$BL['be_cnt_pos1i']                     = 'a szövegblokk felsõ, közepére igazítja a képet';
$BL['be_cnt_pos2i']                     = 'a szövegblokk felsõ, jobb oldalához igazítja a képet';
$BL['be_cnt_pos3i']                     = 'a szövegblokk alsó, bal oldalához igazítja a képet';
$BL['be_cnt_pos4i']                     = 'a szövegblokk alsó, közepére igazítja a képet';
$BL['be_cnt_pos5i']                     = 'a szövegblokk alsó, jobb oldalához igazítja a képet';
$BL['be_cnt_pos6i']                     = 'balra igazítja a képet a szövegblokkban';
$BL['be_cnt_pos7i']                     = 'jobbra igazítja a képet a szövegblokkban';
$BL['be_cnt_maxw']                      = 'max.&nbsp;szélesség';
$BL['be_cnt_maxh']                      = 'max.&nbsp;magasság';
$BL['be_cnt_enlarge']                   = 'click&nbsp;nagyít';
$BL['be_cnt_caption']                   = 'képaláírás';
$BL['be_cnt_subject']                   = 'tárgy';
$BL['be_cnt_recipient']                 = 'címzett';
$BL['be_cnt_buttontext']                = 'gomb szöveg';
$BL['be_cnt_sendas']                    = 'küldje mint';
$BL['be_cnt_text']                      = 'egyszerü szöveg';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'form mezõk';
$BL['be_cnt_code']                      = 'kód';
$BL['be_cnt_infotext']                  = 'info&nbsp;szöveg';
$BL['be_cnt_subscription']              = 'elõfizetés';
$BL['be_cnt_labelemail']                = 'email&nbsp;cimke';
$BL['be_cnt_tablealign']                = 'tábla&nbsp;igazítás';
$BL['be_cnt_labelname']                 = 'név&nbsp;cimke';
$BL['be_cnt_labelsubsc']                = 'elõfizetés cimke';
$BL['be_cnt_allsubsc']                  = 'minden elõf.';
$BL['be_cnt_default']                   = 'alapértelmezett';
$BL['be_cnt_left']                      = 'balra';
$BL['be_cnt_center']                    = 'középre';
$BL['be_cnt_right']                     = 'jobbra';
$BL['be_cnt_buttontext']                = 'gomb&nbsp;szöveg';
$BL['be_cnt_successtext']               = 'sikeres&nbsp;szöveg';
$BL['be_cnt_regmail']                   = 'regisztrációs email';
$BL['be_cnt_logoffmail']                = 'kijelentkezõ email';
$BL['be_cnt_changemail']                = 'változás email';
$BL['be_cnt_openimagebrowser']          = 'kép böngészõ megnyitása';
$BL['be_cnt_openfilebrowser']           = 'file böngészõ megnyitása';
$BL['be_cnt_sortup']                    = 'mozgatés fel';
$BL['be_cnt_sortdown']                  = 'mozgatás le';
$BL['be_cnt_delimage']                  = 'a kiválasztott kép eltávolítása';
$BL['be_cnt_delfile']                   = 'a kiválasztott file eltávolítása';
$BL['be_cnt_delmedia']                  = 'a kiválasztott média eltávolítása';
$BL['be_cnt_column']                    = 'oszlop';
$BL['be_cnt_imagespace']                = 'kép&nbsp;térköz';
$BL['be_cnt_directlink']                = 'direkt link';
$BL['be_cnt_target']                    = 'cél';
$BL['be_cnt_target1']                   = 'új ablakban';
$BL['be_cnt_target2']                   = 'az ablak szülõ keretében';
$BL['be_cnt_target3']                   = 'ugyanabban az ablakban, keret nélkül';
$BL['be_cnt_target4']                   = 'ugyanabban az ablakban, vagy keretben';
$BL['be_cnt_bullet']                    = 'lista (táblázat)';
$BL['be_cnt_ullist']                    = 'lista';
$BL['be_cnt_ullist_desc']               = '~ = 1. Szint, &nbsp; ~~ = 2. szint, &nbsp; stb.';
$BL['be_cnt_linklist']                  = 'link lista';
$BL['be_cnt_plainhtml']                 = 'egyszerû html';
$BL['be_cnt_files']                     = 'file-ok';
$BL['be_cnt_description']               = 'leírás';
$BL['be_cnt_linkarticle']               = 'cikk link';
$BL['be_cnt_articles']                  = 'cikkek';
$BL['be_cnt_movearticleto']             = 'a választott cikk mozgatása a cikk link listába';
$BL['be_cnt_removearticleto']           = 'a választott cikk eltávolítása a cikk link listából';
$BL['be_cnt_mediatype']                 = 'média típus';
$BL['be_cnt_control']                   = 'vezérlés';
$BL['be_cnt_showcontrol']               = 'a control bar látszik';
$BL['be_cnt_autoplay']                  = 'automatikus lejátszás';
$BL['be_cnt_source']                    = 'forrás';
$BL['be_cnt_internal']                  = 'belsõ';
$BL['be_cnt_openmediabrowser']          = 'média böngészõ megnyitása';
$BL['be_cnt_external']                  = 'külsõ';
$BL['be_cnt_mediapos0']                 = 'balra (alapértelmezett)';
$BL['be_cnt_mediapos1']                 = 'középre';
$BL['be_cnt_mediapos2']                 = 'jobbra';
$BL['be_cnt_mediapos3']                 = 'blokkban jobbra';
$BL['be_cnt_mediapos4']                 = 'blokkban balra';
$BL['be_cnt_mediapos0i']                = 'a szövegblokk felsõ, bal oldalához igazítja a médiát';
$BL['be_cnt_mediapos1i']                = 'a szövegblokk felsõ, közepére igazítja a médiát';
$BL['be_cnt_mediapos2i']                = 'a szövegblokk felsõ, jobb oldalához igazítja a médiát';
$BL['be_cnt_mediapos3i']                = 'balra igazítja a médiát a szövegblokkban';
$BL['be_cnt_mediapos4i']                = 'jobbra igazítja a médiát a szövegblokkban';
$BL['be_cnt_setsize']                   = 'méret';
$BL['be_cnt_set1']                      = 'média méret 160x120px';
$BL['be_cnt_set2']                      = 'média méret 240x180px';
$BL['be_cnt_set3']                      = 'média méret 320x240px';
$BL['be_cnt_set4']                      = 'média méret 480x360px';
$BL['be_cnt_set5']                      = 'média szélesség, magasság törlése';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'új lapszerkezet (layout) létrehozása';
$BL['be_admin_page_name']               = 'layout neve';
$BL['be_admin_page_edit']               = 'lapszerkezet (layout) módosítása';
$BL['be_admin_page_render']             = 'lapfelépítés';
$BL['be_admin_page_table']              = 'table';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'saját';
$BL['be_admin_page_custominfo']         = 'a fõ blokk sablonjából (template)';
$BL['be_admin_tmpl_layout']             = 'szerkezet (layout)';
$BL['be_admin_tmpl_nolayout']           = 'Nem áll rendelkezésre lapszerkezet (layout)';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'keresés';
$BL['be_cnt_results']                   = 'eredmények';
$BL['be_cnt_results_per_page']          = 'per&nbsp;lap (ha üres, mindet mutatja)';
$BL['be_cnt_opennewwin']                = 'új ablak nyitása';
$BL['be_cnt_searchlabeltext']           = 'Ezek elõre definiált szövegek és értékek a keresés ûrlapon, az eredmény lapon, és a több lapnyi eredmény navigációs szövegei.';
$BL['be_cnt_input']                     = 'input';
$BL['be_cnt_style']                     = 'stílus(style)';
$BL['be_cnt_result']                    = 'eredmény';
$BL['be_cnt_next']                      = 'következõ';
$BL['be_cnt_previous']                  = 'elõzõ';
$BL['be_cnt_align']                     = 'igazítás';
$BL['be_cnt_searchformtext']            = 'A következõ szövegek jelennek meg, mikor a keresõ ûrlap megnyílik, vagy a keresés eredménye megjelenik, illetve nincs eredménye a keresésnek.';
$BL['be_cnt_intro']                     = 'bevezetés';
$BL['be_cnt_noresult']                  = 'nincs&nbsp;eredmény';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'letiltva';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'cikk tulajdonos';
$BL['be_article_adminuser']             = 'admin felhasználó';
$BL['be_article_username']              = 'szerzõ';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'csak a bejelentkezett felhasználók láthatják';
$BL['be_admin_struct_status']           = 'frontend menü státus';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'cikk menü';
$BL['be_cnt_sitelevel']                 = 'weblap&nbsp;(site)&nbsp;szint';
$BL['be_cnt_sitecurrent']               = 'aktuális weblap (site) szint';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'backend alapértelmezett szöveg';
$BL['be_ctype_ecard']                   = 'e-card';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'cím/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail tmpl';
$BL['be_cnt_ecard_image']               = 'e-card kép';
$BL['be_cnt_ecard_title']               = 'e-card cím';
$BL['be_cnt_alignment']                 = 'igazítás';
$BL['be_cnt_ecardform']                 = 'form tmpl';
$BL['be_cnt_ecardform_err']             = 'Minden *-al jelzett mezõ kötelezõ';
$BL['be_cnt_ecardform_sender']          = 'Feladó';
$BL['be_cnt_ecardform_recipient']       = 'Címzett';
$BL['be_cnt_ecardform_name']            = 'Név';
$BL['be_cnt_ecardform_msgtext']         = 'Üzenet a címzettnek';
$BL['be_cnt_ecardform_button']          = 'e-card küldése';
$BL['be_cnt_ecardsend']                 = 'elküldve&nbsp;tmpl';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend alapértelmezett bejelentkezõ szöveg';
$BL['be_admin_startup_text']            = 'bejelentkezõ szöveg';
$BL['be_admin_startup_button']          = 'bejelentkezõ szöveg mentése';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'vendégkönyv/comm.';
$BL['be_cnt_guestbook_listing']         = 'listázás';
$BL['be_cnt_guestbook_listing_all']     = 'minden&nbsp;bejegyzés&nbsp;kiírva';
$BL['be_cnt_guestbook_list']            = 'kiírva';
$BL['be_cnt_guestbook_perpage']         = 'per&nbsp;lap';
$BL['be_cnt_guestbook_form']            = 'form';
$BL['be_cnt_guestbook_signed']          = 'bejegyzést&nbsp;tett';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'elõtte';
$BL['be_cnt_guestbook_after']           = 'utána';
$BL['be_cnt_guestbook_entry']           = 'belépés';
$BL['be_cnt_guestbook_edit']            = 'szerkesztés';
$BL['be_cnt_ecardform_selector']        = 'választás';
$BL['be_cnt_ecardform_radiobutton']     = 'radio button';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript functionality';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = '"top" cikkek száma';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'hírlevél';
$BL['be_newsletter_addnl']              = 'hírlevél hozzáadása';
$BL['be_newsletter_titleeditnl']        = 'hírlevél szerkesztése';
$BL['be_newsletter_newnl']              = 'új létrehozása';
$BL['be_newsletter_button_savenl']      = 'hírlevél mentése';
$BL['be_newsletter_fromname']           = 'feladó&nbsp;név';
$BL['be_newsletter_fromemail']          = 'feladó&nbsp;email';
$BL['be_newsletter_replyto']            = 'reply email';
$BL['be_newsletter_changed']            = 'utolsó&nbsp;módosítás';
$BL['be_newsletter_placeholder']        = 'placeholder';
$BL['be_newsletter_htmlpart']           = 'HTML hírlevél tartalom';
$BL['be_newsletter_textpart']           = 'TEXT hírlevél tartalom';
$BL['be_newsletter_allsubscriptions']   = 'minden elõfizetés';
$BL['be_newsletter_verifypage']         = 'link ellenõrzés';
$BL['be_newsletter_open']               = 'HTML és TEXT input';
$BL['be_newsletter_open1']              = '(click a képre a megnyitáshoz)';
$BL['be_newsletter_sendnow']            = 'Hírlevél köldése';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Figyelem!</strong> Hírlevél küldése több címzettnek nagyon kockázatos. Ha a címzettek nem erõsítették meg a feliratkozást, ön könnyen potenciális spam küldõvé válhat. Gondolja meg kétszer, elküldi-e a hírlevelet. Ellenõrizze hírlevelét teszt küldéssel.';
$BL['be_newsletter_attention1']         = 'Ha a hírlevelet módosította, kérem elõbb mentse, különben a javítás nem érvényesül';
$BL['be_newsletter_testemail']          = 'teszt email';
$BL['be_newsletter_sendnlbutton']       = 'hírlevél küldése';
$BL['be_newsletter_sendprocess']        = 'küldési folyamat';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Figyelem!</strong> Kérem, ne szakítsa meg a küldés folyamatát! Ellenkezõ esetben elõfordulhat hogy többször elküldi a hírlevelet a címzettnek. Ha a küldés meghiúsul, az elérhetetlen címzettek tárolódnak, a lista felhasználható az azonnali ismételt küldéshez.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">a teszt email cím <strong>###TEST###</strong> érvénytelen!<br />&nbsp;<br />Kérem próbálja újra!';
$BL['be_newsletter_to']                 = 'Címzettek';
$BL['be_newsletter_ready']              = 'hírlevél küldése: RENDBEN';
$BL['be_newsletter_readyfailed']        = 'Meghiúsult a hírlevél küldése';
$BL['be_subnav_msg_subscribers']        = 'hírlevél elõfizetõk';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'oldaltérkép';
$BL['be_cnt_sitemap_catimage']          = 'szint ikon';
$BL['be_cnt_sitemap_articleimage']      = 'cikk ikon';
$BL['be_cnt_sitemap_display']           = 'megjelenítés';
$BL['be_cnt_sitemap_structuronly']      = 'csak struktúra szintek';
$BL['be_cnt_sitemap_structurarticle']   = 'struktúra szintek + cikkek';
$BL['be_cnt_sitemap_catclass']          = 'szint Class';
$BL['be_cnt_sitemap_articleclass']      = 'cikk Class';
$BL['be_cnt_sitemap_count']             = 'számláló';
$BL['be_cnt_sitemap_classcount']        = 'a "Class name"-hez ad';
$BL['be_cnt_sitemap_noclasscount']      = 'nem ad a "Class name"-hez';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'licit';
$BL['be_cnt_bid_bidtext']               = 'licit szöveg';
$BL['be_cnt_bid_sendtext']              = 'elküldve&nbsp;szöveg';
$BL['be_cnt_bid_verifiedtext']          = 'megerõsítve&nbsp;szöveg';
$BL['be_cnt_bid_errortext']             = 'licit törölve';
$BL['be_cnt_bid_verifyemail']           = 'megerõsítõ email';
$BL['be_cnt_bid_startbid']              = 'licit kezdõ értéke';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'növelés&nbsp;ennyivel';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'külsõ tartalom';
$BL['be_cnt_pages_select']              = 'file kiválasztása';
$BL['be_cnt_pages_fromfile']            = 'file a struktúrából';
$BL['be_cnt_pages_manually']            = 'saját path/file vagy URL';
$BL['be_cnt_pages_cust']                = 'file/URL';
$BL['be_cnt_pages_from']                = 'forrás';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover képek';
$BL['be_cnt_reference_basis']           = 'igazítás';
$BL['be_cnt_reference_horizontal']      = 'horizontális';
$BL['be_cnt_reference_vertical']        = 'vertikális';
$BL['be_cnt_reference_aligntext']       = 'kis hivatkozási képek';
$BL['be_cnt_reference_largetext']       = 'nagy hivatkozási képek';
$BL['be_cnt_reference_zoom']            = 'nagyítás';
$BL['be_cnt_reference_middle']          = 'közép';
$BL['be_cnt_reference_border']          = 'szegély';
$BL['be_cnt_reference_block']           = 'blokk sz x m';

// added: 31-05-2004
$BL['be_article_rendering']             = 'közlés';
$BL['be_article_nosummary']             = 'nem jeleníti meg az összefoglalót a teljes cikkben';
$BL['be_article_forlist']               = 'cikk listázás';
$BL['be_article_forfull']               = 'a teljes cikk kiírása';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<div style="font-size: 14px;">Figyelem!</div>A &quot;SETUP&quot; könyvtár létezik!<br>Törölje a könyvtárat, a potenciális adatvédelmi probléma miatt.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'tiltott szavak';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'cookie beállítás';
$BL['be_cnt_guestbook_allowed']         = 'engedélyezve ismét ha eltelt';
$BL['be_cnt_guestbook_seconds']         = 'másodperc';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Bizos töröl \nALL MINDEN file-t a lomtárból?";
$BL['be_ftrash_delallfiles']            = 'minden file törlése a lomtárból';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'elõfizetõk CSV importálása';
$BL['be_newsletter_importtitle']        = 'Hírlevél elõfizetõk importálása';
$BL['be_newsletter_entriesfound']       = 'talált&nbsp;tételek';
$BL['be_newsletter_foundinfile']        = 'a file-ban';
$BL['be_newsletter_addresses']          = 'címek';
$BL['be_newsletter_csverror']           = 'Az importálandó CSV file helytelennek tûnik! Ellenõrizze a delimeter-t!';
$BL['be_newsletter_importall']          = 'minden tétel importálása';
$BL['be_newsletter_addressesadded']     = 'hozzáadott címek.';
$BL['be_newsletter_newimport']          = 'új import';
$BL['be_newsletter_importerror']        = 'Kérem ellenõrizze a CSV file-t - nem lehetett címet hozzáadni!';
$BL['be_newsletter_shouldbe1']          = 'A CSV file így kellene kinézzen';
$BL['be_newsletter_shouldbe2']          = 'de kiválaszthat saját delimeter-t';
$BL['be_newsletter_sample']             = 'példa';
$BL['be_newsletter_selectCSV']          = 'CSV file választás';
$BL['be_newsletter_delimeter']          = 'delimeter';
$BL['be_newsletter_importCSV']          = 'CSV file importálása';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'a hozzá tartozó cikkek rendezése';
$BL['be_admin_struct_orderdate']        = 'létrehozás dátuma';
$BL['be_admin_struct_orderchangedate']  = 'módosítás dátuma';
$BL['be_admin_struct_orderstartdate']   = 'kezdõ dátum';
$BL['be_admin_struct_orderdesc']        = 'csökkenõ';
$BL['be_admin_struct_orderasc']         = 'növekvõ';
$BL['be_admin_struct_ordermanual']      = 'manualis (nyil fel/le)';
$BL['be_cnt_sitemap_startid']           = 'ezzel&nbspkezdõdik';


