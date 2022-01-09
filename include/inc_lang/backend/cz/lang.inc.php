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


// Language: Czech, Language Code: cz
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'

$BL['usr_online']                       = 'Pøihláení uivatelé';

// Login Page
$BL["login_text"]                       = 'Vlote Vae pøihlaovací údaje';
$BL['login_error']                      = 'Po dobu pøihlaování nastala chyba!';
$BL["login_username"]                   = 'uivatelské jméno';
$BL["login_userpass"]                   = 'heslo';
$BL["login_button"]                     = 'Pøihlásit';
$BL["login_lang"]                       = 'Jazyk administrace';

// phpwcms.php
$BL['be_nav_logout']                    = 'ODHLÁENÍ';
$BL['be_nav_articles']                  = 'ÈLÁNKY';
$BL['be_nav_files']                     = 'SOUBORY';
$BL['be_nav_modules']                   = 'MODULY';
$BL['be_nav_messages']                  = 'ZPRÁVY';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISKUZE';

$BL['be_page_title']                    = 'phpwcms backend (administration)';

$BL['be_subnav_article_center']         = 'centrum èlánkù';
$BL['be_subnav_article_new']            = 'nový èlánek';
$BL['be_subnav_file_center']            = 'centrum souborù';
$BL['be_subnav_file_ftptakeover']       = 'pøenos pøes ftp';
$BL['be_subnav_mod_artists']            = 'umìlec, kategorie, ánr';
$BL['be_subnav_msg_center']             = 'centrum zpráv';
$BL['be_subnav_msg_new']                = 'nová zpráva';
$BL['be_subnav_msg_newsletter']         = 'newsletter pøedplatitelé';
$BL['be_subnav_chat_main']              = 'chat hlavní stránka';
$BL['be_subnav_chat_internal']          = 'interní chat';
$BL['be_subnav_profile_login']          = 'pøihaovací informace';
$BL['be_subnav_profile_personal']       = 'osobní údaje';
$BL['be_subnav_admin_pagelayout']       = 'layout stránky';
$BL['be_subnav_admin_templates']        = 'ablony';
$BL['be_subnav_admin_css']              = 'standardní css';
$BL['be_subnav_admin_sitestructure']    = 'struktura stránky';
$BL['be_subnav_admin_users']            = 'uivatelská administrace';
$BL['be_subnav_admin_filecat']          = 'kategorie souborù';

// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID èlánku';
$BL['be_func_struct_preview']           = 'náhled';
$BL['be_func_struct_edit']              = 'editovat èlánek';
$BL['be_func_struct_sedit']             = 'editovat strukturu stránky';
$BL['be_func_struct_cut']               = 'vyjmout èlánek';
$BL['be_func_struct_nocut']             = 'zruit vyjmutí èlánku';
$BL['be_func_struct_svisible']          = 'pøepnout viditelný/neviditelný';
$BL['be_func_struct_spublic']           = 'pøepnout veøejný/neveøejný';
$BL['be_func_struct_sort_up']           = 'zatøídit nahoru';
$BL['be_func_struct_sort_down']         = 'zatøídit dolù';
$BL['be_func_struct_del_article']       = 'vymazat èlánek';
$BL['be_func_struct_del_jsmsg']         = 'Chcete opravdu \nsmazat èlánek?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'vytvoøit nový èlánek v úrovni struktury';
$BL['be_func_struct_paste_article']     = 'vloit èlánek v úrovni struktury';
$BL['be_func_struct_insert_level']      = 'vloit úroveò struktury v';
$BL['be_func_struct_paste_level']       = 'vloit do úrovnì struktury';
$BL['be_func_struct_cut_level']         = 'vyjmout úroveò struktury';
$BL['be_func_struct_no_cut']            = "Základní úroveò není moné vyjmout!";
$BL['be_func_struct_no_paste1']         = "Není moné vloit na toto místo!";
$BL['be_func_struct_no_paste2']         = 'je podkategorie základu stromové úrovnì';
$BL['be_func_struct_no_paste3']         = 'to by se mìlo vloit sem';
$BL['be_func_struct_paste_cancel']      = 'zruit zmìnu úrovnì struktury';
$BL['be_func_struct_del_struct']        = 'vymazat úroveò struktury';
$BL['be_func_struct_del_sjsmsg']        = 'Opravdu chcete smazat \núroveò struktury?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'otevøít';
$BL['be_func_struct_close']             = 'zavøít';
$BL['be_func_struct_empty']             = 'prázdné';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'obyèejný text';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kód';
$BL['be_ctype_textimage']               = 'text s obrázkem';
$BL['be_ctype_images']                  = 'obrázky';
$BL['be_ctype_bulletlist']              = 'seznam (tabulka)';
$BL['be_ctype_ullist']                    = 'seznam';
$BL['be_ctype_link']                    = 'odkaz &amp; email';
$BL['be_ctype_linklist']                = 'seznam odkazù';
$BL['be_ctype_linkarticle']             = 'link na èlánek';
$BL['be_ctype_multimedia']              = 'multimédia';
$BL['be_ctype_filelist']                = 'seznam souborù';
$BL['be_ctype_emailform']               = 'email formuláø';
$BL['be_ctype_newsletter']              = 'newsletter';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profil úspìnì vytvoøen.';
$BL['be_profile_create_error']          = 'Pøi vytváøení nastala chyba.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Údaje profilu byli úspìnì aktualizovány.';
$BL['be_profile_update_error']          = 'Pøi aktualizaci nastala chyba.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'uívatelské jméno {VAL} je chybné';
$BL['be_profile_account_err2']          = 'krátké heslo (pouze {VAL} písmená: nejménì 5 znakù)';
$BL['be_profile_account_err3']          = 'heslo musí být identické, zopakujte';
$BL['be_profile_account_err4']          = 'email {VAL} je chybný';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'vae osobní údaje';
$BL['be_profile_data_text']             = 'Osobní údaje jsou volitelné.<br />To pomùe jiným uivatelùm získat o vás více informací.<br />Pokud zvolíte správný checkbox uivatelé budou moci vidìt Vá profil ve veøejné èásti, nebo na stránkách èlánkù.';
$BL['be_profile_label_title']           = 'titul';
$BL['be_profile_label_firstname']       = 'Jméno';
$BL['be_profile_label_name']            = 'Pøijmení';
$BL['be_profile_label_company']         = 'spoleènost';
$BL['be_profile_label_street']          = 'ulice';
$BL['be_profile_label_city']            = 'mìsto';
$BL['be_profile_label_state']           = 'okres';
$BL['be_profile_label_zip']             = 'PSÈ';
$BL['be_profile_label_country']         = 'stát';
$BL['be_profile_label_phone']           = 'telefon';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobil';
$BL['be_profile_label_signature']       = 'podpis';
$BL['be_profile_label_notes']           = 'poznámky';
$BL['be_profile_label_profession']      = 'profese';
$BL['be_profile_label_newsletter']      = 'newsletter';
$BL['be_profile_text_newsletter']       = 'Chci dostávat veobecný newsletter.';
$BL['be_profile_label_public']          = 'veøejné';
$BL['be_profile_text_public']           = 'Kadý mùe vidìt mùj profil.';
$BL['be_profile_label_button']          = 'aktualizace osobních údajù';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'vae pøihlaovací informace';
$BL['be_profile_account_text']          = 'Normálnì není nutné si mìnit pøihlaovací jméno.<br />Èas od èasu by jste si mìli zmìnit heslo pro zvýení bezpeènosti.';
$BL['be_profile_label_err']             = 'prosím zkontrolujte';
$BL['be_profile_label_username']        = 'uivatelské jméno';
$BL['be_profile_label_newpass']         = 'nové heslo';
$BL['be_profile_label_repeatpass']      = 'zopakujte nové heslo';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'aktualizovat pøihlaovací údaje';
$BL['be_profile_label_lang']            = 'jazyk';

// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'pøevzít soubory pøenesené pøes ftp';
$BL['be_ftptakeover_mark']              = 'výbìr';
$BL['be_ftptakeover_available']         = 'dostupné soubory';
$BL['be_ftptakeover_size']              = 'velikost';
$BL['be_ftptakeover_nofile']            = 'ádné soubory nejsou dostupné &#8211; soubory musíte nahrát pøes ftp';
$BL['be_ftptakeover_all']               = 'VECHNY';
$BL['be_ftptakeover_directory']         = 'adresáø';
$BL['be_ftptakeover_rootdir']           = 'root adresáø';
$BL['be_ftptakeover_needed']            = 'potøebné!!! (jeden musíte zvolit)';
$BL['be_ftptakeover_optional']          = 'volitelné';
$BL['be_ftptakeover_keywords']          = 'klíèové slova';
$BL['be_ftptakeover_additional']        = 'dodateèné';
$BL['be_ftptakeover_longinfo']          = 'informace';
$BL['be_ftptakeover_status']            = 'stav';
$BL['be_ftptakeover_active']            = 'aktivní';
$BL['be_ftptakeover_public']            = 'veøejné';
$BL['be_ftptakeover_createthumb']       = 'vytvoøit náhled';
$BL['be_ftptakeover_button']            = 'prevzít vybrané soubory';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'centrum souborù';
$BL['be_ftab_createnew']                = 'vytvoøit nový adresáø v root adresáøi';
$BL['be_ftab_paste']                    = 'vloit soubory z clipboardu do root adresáøe';
$BL['be_ftab_disablethumb']             = 'zakázat náhledy v seznamu';
$BL['be_ftab_enablethumb']              = 'povolit náhledy v seznamu';
$BL['be_ftab_private']                  = 'souk.&nbsp;soubory';
$BL['be_ftab_public']                   = 'veøejné&nbsp;soubory';
$BL['be_ftab_search']                   = 'vyhledat';
$BL['be_ftab_trash']                    = 'odpadkový&nbsp;ko';
$BL['be_ftab_open']                     = 'otevøít vechny adresáøe';
$BL['be_ftab_close']                    = 'zavøíet vechny otevøené adresáøe';
$BL['be_ftab_upload']                   = 'nahrát soubor do root adresáøe';
$BL['be_ftab_filehelp']                 = 'zobrazit pomoc';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'root adresáø';
$BL['be_fpriv_title']                   = 'vytvoø nový adresáø';
$BL['be_fpriv_inside']                  = 'uvnitø';
$BL['be_fpriv_error']                   = 'chyba: vyplòte jméno adresáøe';
$BL['be_fpriv_name']                    = 'jméno';
$BL['be_fpriv_status']                  = 'stav';
$BL['be_fpriv_button']                  = 'vytvoøit nový adresáø';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'editovat adresáø';
$BL['be_fpriv_newname']                 = 'nové jméno';
$BL['be_fpriv_updatebutton']            = 'aktualizovat adresáøové informace';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Zvolte soubor, který chcete nahrát';
$BL['be_fprivup_err2']                  = 'Velikost pøenáeného souboru je vìtí ne';
$BL['be_fprivup_err3']                  = 'Chyba zápisu souboru';
$BL['be_fprivup_err4']                  = 'Chyba pøi vytváraní uivatelského adresáøe.';
$BL['be_fprivup_err5']                  = 'ádné náhledy';
$BL['be_fprivup_err6']                  = 'Prosím, nezkouejte znovu - toto je chyba na stranì serveru! Kontaktujte vaeho <a href="mailto:{VAL}">webmastera</a> co najdøíve!';
$BL['be_fprivup_title']                 = 'nahrát soubor';
$BL['be_fprivup_button']                = 'nahrát soubor';
$BL['be_fprivup_upload']                = 'nahrát';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'editovat informace souboru';
$BL['be_fprivedit_filename']            = 'jméno souboru';
$BL['be_fprivedit_created']             = 'vytvoøené';
$BL['be_fprivedit_dateformat']          = 'd.m.Y H:i';
$BL['be_fprivedit_err1']                = 'provìrit jméno souboru (vrátit zpìt)';
$BL['be_fprivedit_clockwise']           = 'otoèit náhled po smìru hod. ruèièek [originál +90°]';
$BL['be_fprivedit_cclockwise']          = 'otoèit náhled proti smeru hod. ruèièek [originál -90°]';
$BL['be_fprivedit_button']              = 'aktualizovat informace souboru';
$BL['be_fprivedit_size']                = 'velikost';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'nahrát soubor do adresáøe';
$BL['be_fprivfunc_makenew']             = 'vytvoøit nový adresáø uvnitø';
$BL['be_fprivfunc_paste']               = 'vloit soubor ze schránky do adresáøe';
$BL['be_fprivfunc_edit']                = 'editovat adresáø';
$BL['be_fprivfunc_cactive']             = 'pøepnout aktivní/neaktivní';
$BL['be_fprivfunc_cpublic']             = 'pøepnout veøejné/neveøejné';
$BL['be_fprivfunc_deldir']              = 'smazat adresáø';
$BL['be_fprivfunc_jsdeldir']            = 'Opravdu chcete \nsmazat adresáø?';
$BL['be_fprivfunc_notempty']            = 'adresáø {VAL} není prázdný!';
$BL['be_fprivfunc_opendir']             = 'otevøít adresáø';
$BL['be_fprivfunc_closedir']            = 'zavøít adresáø';
$BL['be_fprivfunc_dlfile']              = 'stáhnout soubor';
$BL['be_fprivfunc_clipfile']            = 'soubor ve schránce';
$BL['be_fprivfunc_cutfile']             = 'vyjmout';
$BL['be_fprivfunc_editfile']            = 'editovar informace souboru';
$BL['be_fprivfunc_cactivefile']         = 'pøepnout aktivní/neaktivní';
$BL['be_fprivfunc_cpublicfile']         = 'pøepnout veøejné/neveøejné';
$BL['be_fprivfunc_movetrash']           = 'vyhodit do koe';
$BL['be_fprivfunc_jsmovetrash1']        = 'Chcete opravdu vyhodit';
$BL['be_fprivfunc_jsmovetrash2']        = 'do koe?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'ádné privátní soubory nebo adresáøe';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'uivatel';
$BL['be_fpublic_nofiles']               = 'ádné veøejné soubory nebo adresáøe';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'ko je prázdný';
$BL['be_ftrash_show']                   = 'uka privátní soubory';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Chcete obnovit {VAL} \na dát zpìt do privátního seznamu?';
$BL['be_ftrash_delete']                 = 'Chcete smazat {VAL}?';
$BL['be_ftrash_undo']                   = 'obnovit (vybrat z koe)';
$BL['be_ftrash_delfinal']               = 'finálnì vymazat';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'Hledaný výraz je prázdný.';
$BL['be_fsearch_title']                 = 'hledat soubory';
$BL['be_fsearch_infotext']              = 'Toto je základní vyhledávání souborù podle informací.<br />Vyhledávají se zhody v klíèových slovech, jménech souborù a souborových informací.<br />Wildcards znaky (*,?,...) nejsou podporovány.<br />Oddìlte vícenásobné vyhledávaní slov mezerou.<br />Zvolte AND/OR a hledané soubory: osobní/veøejné.';
$BL['be_fsearch_nonfound']              = 'Nebyli nalezeny ádné odpovídající soubory!';
$BL['be_fsearch_fillin']                = 'prosím vyplòte hledaný výraz do vrchního políèka.';
$BL['be_fsearch_searchlabel']           = 'hledat';
$BL['be_fsearch_startsearch']           = 'zaèít hledat';
$BL['be_fsearch_and']                   = 'AND';
$BL['be_fsearch_or']                    = 'OR';
$BL['be_fsearch_all']                   = 'vechny soubory';
$BL['be_fsearch_personal']              = 'privátní';
$BL['be_fsearch_public']                = 'veøejné';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'vnitøní chat';
$BL['be_chat_info']                     = 'Zde mùete chatovat s ostatními uivateli o èem chcete. Toto médium je pro online rozhovor, ale mùete zde zanechat zprávu, kterou bude vidìt kadý. Poku si chcete vymìòovat nápady s jinými uivateli, pouijte diskusi.';
$BL['be_chat_start']                    = 'pro sputìní chatu kliknìte sem';
$BL['be_chat_lines']                    = 'poèet zobrazených vstupù';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'centrum zpráv';
$BL['be_msg_new']                       = 'nové';
$BL['be_msg_old']                       = 'staré';
$BL['be_msg_senttop']                   = 'odeslané';
$BL['be_msg_del']                       = 'smazané';
$BL['be_msg_from']                      = 'od';
$BL['be_msg_subject']                   = 'pøedmìt';
$BL['be_msg_date']                      = 'datum/èas';
$BL['be_msg_close']                     = 'zavøít zprávu';
$BL['be_msg_create']                    = 'vytvoøit novou zprávu';
$BL['be_msg_reply']                     = 'odpovìdìt na tuto zprávu';
$BL['be_msg_move']                      = 'pøesunout zprávu do koe';
$BL['be_msg_unread']                    = 'nepøeètené nebo nové zprávy';
$BL['be_msg_lastread']                  = 'naposledy {VAL} ètené zprávy';
$BL['be_msg_lastsent']                  = 'naposledy {VAL} odeslané zprávy';
$BL['be_msg_marked']                    = 'zprávy oznaèené pro smazání (ko)';
$BL['be_msg_nomsg']                     = 'ádné zprávy v této sloce';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'odeslané od';
$BL['be_msg_on']                        = '';
$BL['be_msg_msg']                       = 'zpráva';
$BL['be_msg_err1']                      = 'zapomìli jste zadat pøíjemce...';
$BL['be_msg_err2']                      = 'vyplòte políèko pøedmìt (pøíjemce mùe jednodueji pracovat se zprávou)';
$BL['be_msg_err3']                      = 'zkuste zadat i tìlo zprávy ;-)';
$BL['be_msg_sent']                      = 'nová zpráva byla odeslaná!';
$BL['be_msg_fwd']                       = 'budete pøesmerován do centra zpráv nebo';
$BL['be_msg_newmsgtitle']               = 'napsat novou zprávu';
$BL['be_msg_err']                       = 'chyba pøi odesílání zprávy';
$BL['be_msg_sendto']                    = 'odeslat zprávu komu';
$BL['be_msg_available']                 = 'seznam dostupných uivatelù';
$BL['be_msg_all']                       = 'zaslat zprávu vem oznaèeným pøíjemcùm';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'newsletter pøedplatné';
$BL['be_newsletter_titleedit']          = 'editovat newsletter pøedplatné';
$BL['be_newsletter_new']                = 'vytvoøit nové';
$BL['be_newsletter_add']                = 'pridat&nbsp;newsletter&nbsp;pøedplatné';
$BL['be_newsletter_name']               = 'jméno';
$BL['be_newsletter_info']               = 'informace';
$BL['be_newsletter_button_save']        = 'uloit pøedplatné';
$BL['be_newsletter_button_cancel']      = 'zruit';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'uivatelské jméno je chybné, zvolte jiné';
$BL['be_admin_usr_err2']                = 'uivatelské jméno je prázdné (povinné)';
$BL['be_admin_usr_err3']                = 'heslo je prázdné (povinné)';
$BL['be_admin_usr_err4']                = "email není platný";
$BL['be_admin_usr_err']                 = 'chyba';
$BL['be_admin_usr_mailsubject']         = 'vítejte v phpwcms backend';
$BL['be_admin_usr_mailbody']            = "VÍTEJTE V PHPWCMS BACKEND\n\n    uivatelské jméno: {LOGIN}\n    heslo: {PASSWORD}\n\n\nPøihlásit se mùete zde: {LOGIN_PAGE}\n\nphpwcms administrátor\n ";
$BL['be_admin_usr_title']               = 'pøidat nový uivatelský úèet';
$BL['be_admin_usr_realname']            = 'skuteèné jméno';
$BL['be_admin_usr_setactive']           = 'aktivovat uivatele';
$BL['be_admin_usr_iflogin']             = 'jestltie je zvolené, uivatel se mùe pøihlásit';
$BL['be_admin_usr_isadmin']             = 'uivatel je administrátor';
$BL['be_admin_usr_ifadmin']             = 'jestltie je zvolené, uivatel dostane administrátorské práva';
$BL['be_admin_usr_verify']              = 'ovìøení';
$BL['be_admin_usr_sendemail']           = 'zaslat email novému uivateli s informacemi o úètu';
$BL['be_admin_usr_button']              = 'uloit uivatelské informace';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'editovat uivatelský úèet';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - informace úètu byli zmìnìné';
$BL['be_admin_usr_emailbody']           = "PHPWCMS INFORMACE UIVATELSKÉHO ÚÈTU SE ZMÌNILI\n\n    uivatelské jméno: {LOGIN}\n    heslo: {PASSWORD}\n\n\nPøihlásit se mùete tu: {LOGIN_PAGE}\n\nphpwcms administrátor\n ";
$BL['be_admin_usr_passnochange']        = '[BEZ ZMENY - POUIJTE PRO ZJITÌNÍE HESLA]';
$BL['be_admin_usr_ebutton']             = 'aktualizovat uivatelské data';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms seznam uivatelù';
$BL['be_admin_usr_ldel']                = 'VÝSTRAHA!&#13 Toto smae  uivetele';
$BL['be_admin_usr_create']              = 'vytvorit nového uivatele';
$BL['be_admin_usr_editusr']             = 'editovat uivatele';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'struktura stránky';
$BL['be_admin_struct_child']            = '(dítì od)';
$BL['be_admin_struct_index']            = 'index (zaèátek webstránky)';
$BL['be_admin_struct_cat']              = 'nadpis kategorie';
$BL['be_admin_struct_hide1']            = 'skrýt';
$BL['be_admin_struct_hide2']            = 'tato&nbsp;kategorie&nbsp;v&nbsp;menu';
$BL['be_admin_struct_info']             = 'informaèní text kategorie';
$BL['be_admin_struct_template']         = 'ablona';
$BL['be_admin_struct_alias']            = 'alias této kategorie';
$BL['be_admin_struct_visible']          = 'viditelné';
$BL['be_admin_struct_button']           = 'zaslat data kategorie';
$BL['be_admin_struct_close']            = 'zavøít';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'kategorie souborù';
$BL['be_admin_fcat_err']                = 'jméno kategorie je prázdné!';
$BL['be_admin_fcat_name']               = 'jméno kategorie';
$BL['be_admin_fcat_needed']             = 'potøebné';
$BL['be_admin_fcat_button1']            = 'aktualizovat';
$BL['be_admin_fcat_button2']            = 'vytvoøit';
$BL['be_admin_fcat_delmsg']             = 'Chcete skuteènì smazat \nklíè souboru?';
$BL['be_admin_fcat_fcat']               = 'kategorie souboru';
$BL['be_admin_fcat_err1']               = 'jméno klíèe souboru je prázdne!';
$BL['be_admin_fcat_fkeyname']           = 'jméno klíèe souboru';
$BL['be_admin_fcat_exit']               = 'konec';
$BL['be_admin_fcat_addkey']             = 'pøidat nový klíè';
$BL['be_admin_fcat_editcat']            = 'editovat jméno kategorie';
$BL['be_admin_fcat_delcatmsg']          = 'Chcete skuteènì\nsmazat kategorii souborù?';
$BL['be_admin_fcat_delcat']             = 'smazat kategorii souborù';
$BL['be_admin_fcat_delkey']             = 'smazat jméno klíèe souboru';
$BL['be_admin_fcat_editkey']            = 'editovat klíè';
$BL['be_admin_fcat_addcat']             = 'vytvoøit novou kategorii souborù';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend nastavení: uspoøádání stránky';
$BL['be_admin_page_align']              = 'zarovnání stránky';
$BL['be_admin_page_align_left']         = 'standardní zarovnání (vlevo) celého obsahu stránky';
$BL['be_admin_page_align_center']       = 'centrovat celý obsah stránky';
$BL['be_admin_page_align_right']        = 'celý obsah stránky zarovnat vpravo';
$BL['be_admin_page_margin']             = 'okraj';
$BL['be_admin_page_top']                = 'vrch';
$BL['be_admin_page_bottom']             = 'spodek';
$BL['be_admin_page_left']               = 'vlevo';
$BL['be_admin_page_right']              = 'vpravo';
$BL['be_admin_page_bg']                 = 'pozadí';
$BL['be_admin_page_color']              = 'barva';
$BL['be_admin_page_height']             = 'výka';
$BL['be_admin_page_width']              = 'íøka';
$BL['be_admin_page_main']               = 'hlavní';
$BL['be_admin_page_leftspace']          = 'levá mezera';
$BL['be_admin_page_rightspace']         = 'pravá mezera';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'obrázek';
$BL['be_admin_page_text']               = 'text';
$BL['be_admin_page_link']               = 'linka';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'navtívené';
$BL['be_admin_page_pagetitle']          = 'titulek&nbsp;stránky';
$BL['be_admin_page_addtotitle']         = 'pridat&nbsp;do&nbsp;titulku';
$BL['be_admin_page_category']           = 'kategorii';
$BL['be_admin_page_articlename']        = 'jméno&nbsp;èlánku';
$BL['be_admin_page_blocks']             = 'bloky';
$BL['be_admin_page_allblocks']          = 'vechny bloky';
$BL['be_admin_page_col1']               = '3 sloupcové rozmístìní';
$BL['be_admin_page_col2']               = '2 sloupcové rozmístìní (hlavní sloupec vpravo, navigaèní sloupec vlevo)';
$BL['be_admin_page_col3']               = '2 sloupcové rozmístìní (hlavní sloupec vlevo, navigaèní sloupec vpravo)';
$BL['be_admin_page_col4']               = '1 sloupcové rozmístìní';
$BL['be_admin_page_header']             = 'hlavièka';
$BL['be_admin_page_footer']             = 'patièka';
$BL['be_admin_page_topspace']           = 'vrchní&nbsp;mezera';
$BL['be_admin_page_bottomspace']        = 'dolní&nbsp;mezera';
$BL['be_admin_page_button']             = 'uloit rozmístìní stránky';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend nastavení: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'uloit css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend nastavení: ablony';
$BL['be_admin_tmpl_default']            = 'pøednastavené';
$BL['be_admin_tmpl_add']                = 'pøidat&nbsp;ablonu';
$BL['be_admin_tmpl_edit']               = 'editovat ablonu';
$BL['be_admin_tmpl_new']                = 'vytvoøit novou';
$BL['be_admin_tmpl_css']                = 'css soubor';
$BL['be_admin_tmpl_head']               = 'html&nbsp;&nbsp;<br />hlavièky';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'chyba';
$BL['be_admin_tmpl_button']             = 'uloit ablonu';
$BL['be_admin_tmpl_name']               = 'jméno';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'struktura stránky a seznam èlánkù';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'název èlánku je prázdný';
$BL['be_article_err2']                  = 'zadané datum zaèátku bylo chybné - nastaveno na teï';
$BL['be_article_err3']                  = 'zadané datum ukonèení bylo chybné - nastaveno na teï';
$BL['be_article_title1']                = 'hlavièka èlánku';
$BL['be_article_cat']                   = 'kategore';
$BL['be_article_atitle']                = 'název èlánku';
$BL['be_article_asubtitle']             = 'podtitul';
$BL['be_article_abegin']                = 'zaèíná';
$BL['be_article_aend']                  = 'konèí';
$BL['be_article_aredirect']             = 'pøesmìrovat na';
$BL['be_article_akeywords']             = 'klíèové slova';
$BL['be_article_asummary']              = 'souhrn';
$BL['be_article_abutton']               = 'vytvoøit nový èlánek';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'zadané datum ukonèení bylo chybné - nastaveno na teï + 1 týden';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'editovat hlavièku èlánku';
$BL['be_article_eslastedit']            = 'naposledy editované';
$BL['be_article_esnoupdate']            = 'formuláø neaktualizován';
$BL['be_article_esbutton']              = 'aktualizovat data èlánku';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'obsah èlánku';
$BL['be_article_cnt_type']              = 'typ obsahu';
$BL['be_article_cnt_space']             = 'mezera';
$BL['be_article_cnt_before']            = 'pøed';
$BL['be_article_cnt_after']             = 'za';
$BL['be_article_cnt_top']               = 'nahoru';
$BL['be_article_cnt_ctitle']            = 'název obsahu';
$BL['be_article_cnt_back']              = 'kompletní informace èlánku';
$BL['be_article_cnt_button1']           = 'aktualizovat obsah';
$BL['be_article_cnt_button2']           = 'vytvoøit obsah';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'informace èlánku';
$BL['be_article_cnt_ledit']             = 'editovat èlánek';
$BL['be_article_cnt_lvisible']          = 'pøepnout viditelný/neviditelný';
$BL['be_article_cnt_ldel']              = 'smazat tento èlánek';
$BL['be_article_cnt_ldeljs']            = 'Smazat èlánek?';
$BL['be_article_cnt_redirect']          = 'pøesmìrování';
$BL['be_article_cnt_edited']            = 'napsal';
$BL['be_article_cnt_start']             = 'poèáteèní datum';
$BL['be_article_cnt_end']               = 'datum ukonèení';
$BL['be_article_cnt_add']               = 'pøidat novou èást èlánku';
$BL['be_article_cnt_up']                = 'presunout obsah nahoru';
$BL['be_article_cnt_down']              = 'presunout obsah dolù';
$BL['be_article_cnt_edit']              = 'editovat èást obsahu';
$BL['be_article_cnt_delpart']           = 'smazat tuto èást obsahu èlánku';
$BL['be_article_cnt_delpartjs']         = 'smazat èást obsahu?';
$BL['be_article_cnt_center']            = 'centrum èlánkù';

// content forms
$BL['be_cnt_plaintext']                 = 'obyèejný text';
$BL['be_cnt_htmltext']                  = 'html text';
$BL['be_cnt_image']                     = 'obrázek';
$BL['be_cnt_position']                  = 'pozice';
$BL['be_cnt_pos0']                      = 'Nad, vlevo';
$BL['be_cnt_pos1']                      = 'Nad, na støed';
$BL['be_cnt_pos2']                      = 'Nad, vpravo';
$BL['be_cnt_pos3']                      = 'Pod, vlevo';
$BL['be_cnt_pos4']                      = 'Pod, na støed';
$BL['be_cnt_pos5']                      = 'Pod, vpravo';
$BL['be_cnt_pos6']                      = 'V textu, vlevo';
$BL['be_cnt_pos7']                      = 'v textu, vpravo';
$BL['be_cnt_pos0i']                     = 'zarovnat obrázek nad a vlevo od textového bloku';
$BL['be_cnt_pos1i']                     = 'zarovnat obrázek nad a na støed textového bloku';
$BL['be_cnt_pos2i']                     = 'zarovnat obrázek nad a vpravo od textového bloku';
$BL['be_cnt_pos3i']                     = 'zarovnat obrázek pod a vlevo od textového bloku';
$BL['be_cnt_pos4i']                     = 'zarovnat obrázek pod a na støed od textového bloku';
$BL['be_cnt_pos5i']                     = 'zarovnat obrázek pod a vpravo od textového bloku';
$BL['be_cnt_pos6i']                     = 'zarovnat obrázek vlevo u vnitø textového bloku';
$BL['be_cnt_pos7i']                     = 'zarovnat obrázek vpravo u vnitø textového bloku';
$BL['be_cnt_maxw']                      = 'max.&nbsp;íøka';
$BL['be_cnt_maxh']                      = 'max.&nbsp;výka';
$BL['be_cnt_enlarge']                   = 'zvìtit&nbsp;kliknutím';
$BL['be_cnt_caption']                   = 'popis';
$BL['be_cnt_subject']                   = 'pøedmìt';
$BL['be_cnt_recipient']                 = 'pøijemce';
$BL['be_cnt_buttontext']                = 'text tlaèítka';
$BL['be_cnt_sendas']                    = 'poslat jako';
$BL['be_cnt_text']                      = 'text';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'políèka formuláøe';
$BL['be_cnt_code']                      = 'kód';
$BL['be_cnt_infotext']                  = 'informaèní&nbsp;text';
$BL['be_cnt_subscription']              = 'pøedplatné';
$BL['be_cnt_labelemail']                = 'návìstí&nbsp;email (???)';
$BL['be_cnt_tablealign']                = 'zarovnání&nbsp;tabulky';
$BL['be_cnt_labelname']                 = 'návìstí&nbsp;jméno';
$BL['be_cnt_labelsubsc']                = 'návìstí&nbsp;pøedplat.';
$BL['be_cnt_allsubsc']                  = 'vichni&nbsp;pøedplat.';
$BL['be_cnt_default']                   = 'pøednastavené';
$BL['be_cnt_left']                      = 'vlevo';
$BL['be_cnt_center']                    = 'na støedu';
$BL['be_cnt_right']                     = 'vpravo';
$BL['be_cnt_buttontext']                = 'text&nbsp;tlaèítka';
$BL['be_cnt_successtext']               = 'úspìný&nbsp;text';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'change.email';
$BL['be_cnt_openimagebrowser']          = 'otevøít prohlíeè obrázkù';
$BL['be_cnt_openfilebrowser']           = 'otevøít prohlíeè souborù';
$BL['be_cnt_sortup']                    = 'pøesunout výe';
$BL['be_cnt_sortdown']                  = 'pøesunout níe';
$BL['be_cnt_delimage']                  = 'odstranit oznaèený obrázek';
$BL['be_cnt_delfile']                   = 'odstranit oznaèený soubor';
$BL['be_cnt_delmedia']                  = 'odstranit oznaèený médiální soub.';
$BL['be_cnt_column']                    = 'sloupec';
$BL['be_cnt_imagespace']                = 'prostor&nbsp;obrázku';
$BL['be_cnt_directlink']                = 'pøímý odkaz';
$BL['be_cnt_target']                    = 'cíl';
$BL['be_cnt_target1']                   = 'v novém oknì';
$BL['be_cnt_target2']                   = 'v nadøazeném rámu okna';
$BL['be_cnt_target3']                   = 'v tomto oknì bez rámù';
$BL['be_cnt_target4']                   = 'v tomto rámu nebo oknì';
$BL['be_cnt_bullet']                    = 'seznam (tabulka)';
$BL['be_cnt_ullist']                        = 'seznam';
$BL['be_cnt_ullist_desc']                 = '~ = První úroveò, &nbsp; ~~ = Druhá úroveò, &nbsp; atd.';
$BL['be_cnt_linklist']                  = 'seznam odkazù';
$BL['be_cnt_plainhtml']                 = 'obyèejné html';
$BL['be_cnt_files']                     = 'soubory';
$BL['be_cnt_description']               = 'popis';
$BL['be_cnt_linkarticle']               = 'odkaz na èlánek';
$BL['be_cnt_articles']                  = 'èlánky';
$BL['be_cnt_movearticleto']             = 'pøesunout oznaèený èlánek do seznamu odkazù èlánkù';
$BL['be_cnt_removearticleto']           = 'odstranit oznaèený èlánek do seznamu odkazù èlánkù';
$BL['be_cnt_mediatype']                 = 'druh mediálního souboru';
$BL['be_cnt_control']                   = 'ovládání';
$BL['be_cnt_showcontrol']               = 'ukázat ovládací litu';
$BL['be_cnt_autoplay']                  = 'auto. sputìní';
$BL['be_cnt_source']                    = 'zdroj';
$BL['be_cnt_internal']                  = 'vnitøní';
$BL['be_cnt_openmediabrowser']          = 'otevøít prohlíeè mediálních souborù';
$BL['be_cnt_external']                  = 'Venkovní';
$BL['be_cnt_mediapos0']                 = 'vlevo (pøedvolené)';
$BL['be_cnt_mediapos1']                 = 'na støedu';
$BL['be_cnt_mediapos2']                 = 'vpravo';
$BL['be_cnt_mediapos3']                 = 'blok, vlevo';
$BL['be_cnt_mediapos4']                 = 'blok, vpravo';
$BL['be_cnt_mediapos0i']                = 'zarovnat mediál. soub. nahoru a vlevo od textového bloku';
$BL['be_cnt_mediapos1i']                = 'zarovnat mediál. soub. nahoru a ve støedu od textového bloku';
$BL['be_cnt_mediapos2i']                = 'zarovnat mediál. soub. nahoru a vpravo od textového bloku';
$BL['be_cnt_mediapos3i']                = 'zarovnat mediál. soub. nalevo uvnitø textového bloku';
$BL['be_cnt_mediapos4i']                = 'zarovnat mediál. soub. napravo uvnitø textového bloku';
$BL['be_cnt_setsize']                   = 'nastavit velikost';
$BL['be_cnt_set1']                      = 'nastavit velikost mediálního soub. na 160x120px';
$BL['be_cnt_set2']                      = 'nastavit velikost mediálního soub. na 240x180px';
$BL['be_cnt_set3']                      = 'nastavit velikost mediálního soub. na 320x240px';
$BL['be_cnt_set4']                      = 'nastavit velikost mediálního soub. na 480x360px';
$BL['be_cnt_set5']                      = 'vymazat výku a íøku mediálního souboru';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'vytvoøit nový layout stránky';
$BL['be_admin_page_name']               = 'jméno layoutu';
$BL['be_admin_page_edit']               = 'editovat layout stránky';
$BL['be_admin_page_render']             = 'pøeklad';
$BL['be_admin_page_table']              = 'tabulka';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'volitelný';
$BL['be_admin_page_custominfo']         = 'ze ablony hlavního bloku';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'ádny layout stránky není dostupný!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'hledat';
$BL['be_cnt_results']                   = 'výsledky';
$BL['be_cnt_results_per_page']          = 'po&nbsp;stránce (jestli prázdné, zobrazit vechny)';
$BL['be_cnt_opennewwin']                = 'otevøít nové okno';
$BL['be_cnt_searchlabeltext']           = 'toto jsou pøeddefinované texty a hodnoty pro vyhledávací formuláø a stránku výsledkù hledaní a texty jsou zobrazené, kdy má být zobrazený vìtí poèet výsledkù vyhledávání na stránku ne byl zadaný.';
$BL['be_cnt_input']                     = 'vstup';
$BL['be_cnt_style']                     = 'styl';
$BL['be_cnt_result']                    = 'výsledek';
$BL['be_cnt_next']                      = 'nasledující';
$BL['be_cnt_previous']                  = 'pøedchozí';
$BL['be_cnt_align']                     = 'zarovnání';
$BL['be_cnt_searchformtext']            = 'nasledující texty jsou uvedeny pøi otevøení vyhledávacího formuláøì nebo pokud výsledky pro dané vyhledávaní (ne) jsou dostupné.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'bez výsledku';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'vypnou';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'vlastník èlánku';
$BL['be_article_adminuser']             = 'administrátor';
$BL['be_article_username']              = 'autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'viditelné pouze pro pøihláené uivatele';
$BL['be_admin_struct_status']           = 'frontend menu stav';

// added: 15-02-2004
$BL['be_ctype_articlemenu']                   = 'menu èlánku';
$BL['be_cnt_sitelevel']                         = 'úroveò stránky';
$BL['be_cnt_sitecurrent']                       = 'aktuální úroveò stránky';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']            = 'backend pøedvolený text';
$BL['be_ctype_ecard']                             = 'e-card';
$BL['be_ctype_blog']                              = 'blog';
$BL['be_cnt_ecardtext']                 = 'titulek/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mailová ablona';
$BL['be_cnt_ecard_image']               = 'e-card obrázek';
$BL['be_cnt_ecard_title']               = 'e-card nadpis';
$BL['be_cnt_alignment']                 = 'zarovnání';
$BL['be_cnt_ecardform']                 = 'formuláø';
$BL['be_cnt_ecardform_err']             = 'Vechny políèka oznaèené * jsou povinné';
$BL['be_cnt_ecardform_sender']          = 'Odesílatel';
$BL['be_cnt_ecardform_recipient']       = 'Pøíjemce';
$BL['be_cnt_ecardform_name']            = 'Jméno';
$BL['be_cnt_ecardform_msgtext']         = 'Vae zpráva pøíjemci';
$BL['be_cnt_ecardform_button']          = 'odeslat e-card';
$BL['be_cnt_ecardsend']                 = 'odeslané';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend pøedvolený prvotní text';
$BL['be_admin_startup_text']            = 'prvotní text';
$BL['be_admin_startup_button']          = 'uloit text';

// added: 17-04-2004
$BL['be_ctype_guestbook']                       = 'guestbook/comm.';
$BL['be_cnt_guestbook_listing']             = 'seznam';
$BL['be_cnt_guestbook_listing_all']       = 'procházet&nbsp;vechny&nbsp;vstupy';
$BL['be_cnt_guestbook_list']                  = 'procházet';
$BL['be_cnt_guestbook_perpage']             = 'po&nbsp;stránce';
$BL['be_cnt_guestbook_form']                  = 'formuláø';
$BL['be_cnt_guestbook_signed']              = 'podepsaný';
$BL['be_cnt_guestbook_nav']                   = 'navigace';
$BL['be_cnt_guestbook_before']              = 'pøed';
$BL['be_cnt_guestbook_after']                 = 'po';
$BL['be_cnt_guestbook_entry']                 = 'záznam';
$BL['be_cnt_guestbook_edit']                  = 'editovat';
$BL['be_cnt_ecardform_selector']        = 'výbìr';
$BL['be_cnt_ecardform_radiobutton']     = 'radio tlaèítko';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript funkènost';
$BL['be_cnt_ecardform_over']              = 'onMouseOver';
$BL['be_cnt_ecardform_click']             = 'onClick';
$BL['be_cnt_ecardform_out']               = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'mnoství top èlánkù';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'newsletter';
$BL['be_newsletter_addnl']              = 'pøidat newsletter';
$BL['be_newsletter_titleeditnl']        = 'editovat newsletter';
$BL['be_newsletter_newnl']              = 'vytvoøit nový';
$BL['be_newsletter_button_savenl']      = 'uloit newsletter';
$BL['be_newsletter_fromname']           = 'jméno odesilatele';
$BL['be_newsletter_fromemail']          = 'email odesilatele';
$BL['be_newsletter_replyto']            = 'odpovìdìt na email';
$BL['be_newsletter_changed']            = 'poslední zmìna';
$BL['be_newsletter_placeholder']        = 'vlastník';
$BL['be_newsletter_htmlpart']           = 'HTML newsletter obsah';
$BL['be_newsletter_textpart']           = 'TEXT newsletter obsah';
$BL['be_newsletter_allsubscriptions']   = 'vechny pøedplatné';
$BL['be_newsletter_verifypage']         = 'ovìøit odkaz';
$BL['be_newsletter_open']               = 'HTML a TEXT vstup';
$BL['be_newsletter_open1']              = '(pro otevøení kliknìte na obrázek)';
$BL['be_newsletter_sendnow']            = 'Odeslat newsletter';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Výstraha!</strong> Odesílat newsletter více uivatelùm je nebezpeèné. Pøíjemci by mìli být ovìøení jinak polete potenciální spam. Myslete dvakrát ne odelete newsletter. Provìøte si newsletter odesláním testem.';
$BL['be_newsletter_attention1']         = 'Pokud jste provedli zmìny ve vrchních datech newsletteru, prosím nejprve ho ulote jinak se zmìny neprojeví.';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'odeslat newsletter';
$BL['be_newsletter_sendprocess']        = 'proces odesílání';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Výstraha!</strong> Prosím nezastavujte proces odesílání. Jinak je moné e zalete newsletter víckrát ne jednou! Pokud odesílíní sele, vichni nedoruèení pøíjemcové jsou uloeni odelete newsletter jetì jednou.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">adresa testovacího emailu <strong>###TEST###</strong> je neplatná!<br />&nbsp;<br />Zkuste znovu prosím!';
$BL['be_newsletter_to']                 = 'Pøíjemci';
$BL['be_newsletter_ready']              = 'odeslání newsletteru: HOTOVO';
$BL['be_newsletter_readyfailed']        = 'Selhalo odeslání newsletteru na';
$BL['be_subnav_msg_subscribers']        = 'newsletter odbìratelé';

// added: 20-04-2004
$BL['be_ctype_sitemap']                       = 'mapa stránek';
$BL['be_cnt_sitemap_catimage']          = 'ikona úrovnì';
$BL['be_cnt_sitemap_articleimage']      = 'ikona èlánku';
$BL['be_cnt_sitemap_display']           = 'zobrazení';
$BL['be_cnt_sitemap_structuronly']      = 'pouze úrovnì struktury';
$BL['be_cnt_sitemap_structurarticle']   = 'úrovnì struktury + èlánky';
$BL['be_cnt_sitemap_catclass']          = 'class úrovnì';
$BL['be_cnt_sitemap_articleclass']      = 'class èlánku';
$BL['be_cnt_sitemap_count']             = 'poèítadlo';
$BL['be_cnt_sitemap_classcount']        = 'pøidat do class name';
$BL['be_cnt_sitemap_noclasscount']      = 'nepøidávat do class name';

// added: 23-04-2004
$BL['be_ctype_bid']                           = 'nabídka';
$BL['be_cnt_bid_bidtext']               = 'text nabídky';
$BL['be_cnt_bid_sendtext']              = 'odeslat text';
$BL['be_cnt_bid_verifiedtext']          = 'ovìøený text';
$BL['be_cnt_bid_errortext']             = 'smazané';
$BL['be_cnt_bid_verifyemail']           = 'ovìøit email';
$BL['be_cnt_bid_startbid']              = 'start nabidky';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'zvýit&nbsp;o';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'ext. obsah';
$BL['be_cnt_pages_select']              = 'zvolit soubor';
$BL['be_cnt_pages_fromfile']            = 'soubor ze struktury';
$BL['be_cnt_pages_manually']            = 'volitelná cesta/soubor nebo URL';
$BL['be_cnt_pages_cust']                = 'soubor/URL';
$BL['be_cnt_pages_from']                = 'zdroj';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'promìnlivé obrázky';
$BL['be_cnt_reference_basis']           = 'zarovnaní';
$BL['be_cnt_reference_horizontal']      = 'vodorovné';
$BL['be_cnt_reference_vertical']        = 'svislé';
$BL['be_cnt_reference_aligntext']       = 'malé referenèní obrázky';
$BL['be_cnt_reference_largetext']       = 'velký referenèní obrázek';
$BL['be_cnt_reference_zoom']            = 'zvìtení';
$BL['be_cnt_reference_middle']          = 'støední';
$BL['be_cnt_reference_border']          = 'okraj';
$BL['be_cnt_reference_block']           = 'blok  x v';

// added: 31-05-2004
$BL['be_article_rendering']             = 'pøeklad';
$BL['be_article_nosummary']             = 'nezobrazovat souhrn v celém èlánku';
$BL['be_article_forlist']               = 'prohlíení èlánkù';
$BL['be_article_forfull']               = 'zobrazit celý èlánek';


// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ATTENTION!</strong> The &quot;SETUP&quot; directory still exists! Delete that directory - it\'s potential security problem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'banned words';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'set cookie';
$BL['be_cnt_guestbook_allowed']         = 'allowed again after';
$BL['be_cnt_guestbook_seconds']         = 'seconds';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Do you really want to delete \nALL FILES in trash?";
$BL['be_ftrash_delallfiles']            = 'delete all files in trash';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'CSV subscribers import';
$BL['be_newsletter_importtitle']        = 'Import Newsletter Subscribers';
$BL['be_newsletter_entriesfound']       = 'entries&nbsp;found';
$BL['be_newsletter_foundinfile']        = 'in file';
$BL['be_newsletter_addresses']          = 'addresses';
$BL['be_newsletter_csverror']           = 'Imported CSV file seems to be incorrect! Check delimeter!';
$BL['be_newsletter_importall']          = 'import all entries';
$BL['be_newsletter_addressesadded']     = 'addresses added.';
$BL['be_newsletter_newimport']          = 'new import';
$BL['be_newsletter_importerror']        = 'Please check your CSV file - no address can be added!';
$BL['be_newsletter_shouldbe1']          = 'Your CSV file should be formatted like this';
$BL['be_newsletter_shouldbe2']          = 'but you can choose a custom delimeter';
$BL['be_newsletter_sample']             = 'sample';
$BL['be_newsletter_selectCSV']          = 'select CSV file';
$BL['be_newsletter_delimeter']          = 'delimeter';
$BL['be_newsletter_importCSV']          = 'import CSV file';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'ordering of assigned articles';
$BL['be_admin_struct_orderdate']        = 'creation date';
$BL['be_admin_struct_orderchangedate']  = 'change date';
$BL['be_admin_struct_orderstartdate']   = 'start date';
$BL['be_admin_struct_orderdesc']        = 'descending';
$BL['be_admin_struct_orderasc']         = 'ascending';
$BL['be_admin_struct_ordermanual']      = 'manual (arrow up/down)';
$BL['be_cnt_sitemap_startid']           = 'start at';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'map';
$BL['be_save_btn']                      = 'Save';
$BL['be_cmap_location_error_notitle']   = 'fill in a title for this location.';
$BL['be_cnt_map_add']                   = 'add location';
$BL['be_cnt_map_edit']                  = 'edit location';
$BL['be_cnt_map_title']                 = 'location title';
$BL['be_cnt_map_info']                  = 'entry/info';
$BL['be_cnt_map_list']                  = 'location list';
$BL['be_btn_delete']                    = 'Do you really want to \ndelete this location?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP variables';
$BL['be_cnt_vars']                      = 'variables';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'copy article';
$BL['be_func_struct_nocopy']            = 'disable copy article';
$BL['be_func_struct_copy_level']        = 'copy structure level';
$BL['be_func_struct_no_copy']           = "It's not possible to copy the root level!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minute';
$BL['be_date_minutes']                  = 'minutes';
$BL['be_date_hour']                     = 'hour';
$BL['be_date_hours']                    = 'hours';
$BL['be_date_day']                      = 'day';
$BL['be_date_days']                     = 'days';
$BL['be_date_week']                     = 'week';
$BL['be_date_weeks']                    = 'weeks';
$BL['be_date_month']                    = 'month';
$BL['be_date_months']                   = 'months';
$BL['be_off']                           = 'Off';
$BL['be_on']                            = 'On';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'timeout';

// added: 13-12-2004
$BL['be_subnav_admin_groups']                 = 'uivatelé &amp; skupiny';

// added: 20-12-2004
$BL['be_ctype_forum']                             = 'fórum';
$BL['be_subnav_msg_forum']                    = 'seznam fór';
$BL['be_forum_title']                             = 'jméno fóra';
$BL['be_forum_permission']                    = 'práva';
$BL['be_forum_add']                               = 'nové fórum';
$BL['be_forum_titleedit']                       = 'editovat fórum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'uivatelský';
$BL['be_show_content']                  = 'zobrazení';
$BL['be_main_content']                  = 'hlavní sloupec';
$BL['be_admin_template_jswarning']      = 'Varování!!! \nUivatelský blok byl zmìnìn! \n\nPro zachování nastavení zvolte storno! \n\nZmìnit ablonu?\n\n';

$BL['be_ctype_rssfeed']                         = 'RSS zdroj';
$BL['be_cnt_rssfeed_url']                       = 'RSS url';
$BL['be_cnt_rssfeed_item']                    = 'poloka(y)';
$BL['be_cnt_rssfeed_max']                       = 'max.';
$BL['be_cnt_rssfeed_cut']                       = 'skrýt první poloku';

$BL['be_ctype_simpleform']                    = 'kontaktní formuláø';

$BL['be_cnt_onsuccess']                       = 'pøi úspìchu';
$BL['be_cnt_onerror']                           = 'pøi chybì';
$BL['be_cnt_onsuccess_redirect']          = 'pøesmìrování pøi úspìchu';
$BL['be_cnt_onerror_redirect']          = 'pøesmìrování pøi chybì';

$BL['be_cnt_form_class']                        = 'class formuláøe';
$BL['be_cnt_label_wrap']                        = 'znaèka zalomení';
$BL['be_cnt_error_class']                       = 'chybová class';
$BL['be_cnt_req_mark']                        = 'vyadované';
$BL['be_cnt_mark_as_req']                       = 'oznaèené jsou vyadované';
$BL['be_cnt_mark_as_del']                       = 'oznaèit pro smazání';


$BL['be_cnt_type']                            = 'typ';
$BL['be_cnt_label']                           = 'jméno';
$BL['be_cnt_needed']                              = 'vyadovat';
$BL['be_cnt_delete']                              = 'smazat';
$BL['be_cnt_value']                               = 'hodnota';
$BL['be_cnt_error_text']                        = 'chybový text';
$BL['be_cnt_css_style']                         = 'CSS styl';

$BL['be_cnt_field']                               = array("text"=>'text (single-line)', "email"=>'email', "textarea"=>'text (multi-line)',
                                                                        "hidden"=>'hidden', "password"=>'password', "select"=>'select menu',
                                                                        "list"=>'list menu', "checkbox"=>'checkbox', "radio"=>'radio button',
                                                                        "upload"=>'file', "submit"=>'send button', "reset"=>'reset button',
                                                                        "break"=>'break', "breaktext"=>'break text', "special"=>'text (spezial)');

$BL['be_cnt_access']                              = 'access';
$BL['be_cnt_activated']                         = 'activated';
$BL['be_cnt_available']                         = 'available';
$BL['be_cnt_guests']                              = 'guests';
$BL['be_cnt_admin']                               = 'admin';
$BL['be_cnt_write']                               = 'write';
$BL['be_cnt_read']                                = 'read';

$BL['be_cnt_no_wysiwyg_editor']             = 'zakázat WYSIWYG editor';
$BL['be_cnt_cache_update']                    = 'reset cache';
$BL['be_cnt_cache_delete']                    = 'smazání cache';
$BL['be_cnt_cache_delete_msg']              = 'Opravdu chcete smazat cache?\n';

$BL['be_admin_usr_issection']                 = 'pøístup do sekcí';
$BL['be_admin_usr_ifsection0']              = 'frontend';
$BL['be_admin_usr_ifsection1']              = 'backend';
$BL['be_admin_usr_ifsection2']              = 'frontend and backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'upravit tuto èást èlánku';
$BL['be_func_content_paste0']            = 'vloit do tohoto èlánku';
$BL['be_func_content_paste']             = 'vloit èást na konec èlánku';
$BL['be_func_content_cut']               = 'vyjmout tuto èást èlánku';
$BL['be_func_content_no_cut']            = "Není moné vloit tuto èást èlánku do tohoto èlánku!";
$BL['be_func_content_copy']              = 'kopírovat tuto èást èlánku do jiného èlánku';
$BL['be_func_content_no_copy']           = "Není moné kopírovat tuto èást èlánku do tohoto èlánku!";
$BL['be_func_content_paste_cancel']      = 'zruit akci s touto èástí èlánku';


