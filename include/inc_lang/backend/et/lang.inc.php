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


// Language: Estonian, Language Code: ET
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'sisse loginud:';

// Login Page
$BL["login_text"]                       = 'Sisenemine';
$BL['login_error']                      = 'Viga sisselogimisel!';
$BL["login_username"]                   = 'kasutajatunnus';
$BL["login_userpass"]                   = 'parool';
$BL["login_button"]                     = 'Sisene';
$BL["login_lang"]                       = 'vali keel';

// phpwcms.php
$BL['be_nav_logout']                    = 'VÄLJUN';
$BL['be_nav_articles']                  = 'ARTIKLID';
$BL['be_nav_files']                     = 'FAILID';
$BL['be_nav_modules']                   = 'MOODULID';
$BL['be_nav_messages']                  = 'SÕNUMID';
$BL['be_nav_chat']                      = 'VESTLUS';
$BL['be_nav_profile']                   = 'PROFIIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'ARUTELU';

$BL['be_page_title']                    = 'phpwcms back-end kasutajaliides (administreerimine)';

$BL['be_subnav_article_center']         = 'artiklite haldus';
$BL['be_subnav_article_new']            = 'uus artikkel';
$BL['be_subnav_file_center']            = 'failide haldus';
$BL['be_subnav_file_ftptakeover']       = 'ftp-ga saadetud failid';
$BL['be_subnav_mod_artists']            = 'artist, kategooria, stiil';
$BL['be_subnav_msg_center']             = 'sõnumihaldus';
$BL['be_subnav_msg_new']                = 'uus sõnum';
$BL['be_subnav_msg_newsletter']         = 'uudiskirja tellimine';
$BL['be_subnav_chat_main']              = 'vestluse haldus';
$BL['be_subnav_chat_internal']          = 'vestlusruum';
$BL['be_subnav_profile_login']          = 'sisselogimise andmed';
$BL['be_subnav_profile_personal']       = 'isiklikud andmed';
$BL['be_subnav_admin_pagelayout']       = 'vormingud';
$BL['be_subnav_admin_templates']        = 'mallid';
$BL['be_subnav_admin_css']              = 'css vaikeväärtused';
$BL['be_subnav_admin_sitestructure']    = 'veebilehe struktuur';
$BL['be_subnav_admin_users']            = 'kasutajate haldus';
$BL['be_subnav_admin_filecat']          = 'failikategooriad';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'artikli ID';
$BL['be_func_struct_preview']           = 'eelvaatlus';
$BL['be_func_struct_edit']              = 'redigeeri artiklit';
$BL['be_func_struct_sedit']             = 'redigeeri struktuuritaset';
$BL['be_func_struct_cut']               = 'teisalda lõikepuhvrisse';
$BL['be_func_struct_nocut']             = 'keela lõikamine';
$BL['be_func_struct_svisible']          = 'lülita nähtav/mittenähtav';
$BL['be_func_struct_spublic']           = 'lülita avalik/mitteavalik';
$BL['be_func_struct_sort_up']           = 'liiguta üles';
$BL['be_func_struct_sort_down']         = 'liiguta alla';
$BL['be_func_struct_del_article']       = 'kustuta artikkel';
$BL['be_func_struct_del_jsmsg']         = 'Kas soovid \nto artikli kustutada?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'loo sellel struktuuritasemel uus artikkel';
$BL['be_func_struct_paste_article']     = 'kleebi sellel struktuuritasemel artikkel';
$BL['be_func_struct_insert_level']      = 'lisa struktuuritase';
$BL['be_func_struct_paste_level']       = 'kleebi struktuuritase';
$BL['be_func_struct_cut_level']         = 'teisalda struktuuritase lõikepuhvrisse';
$BL['be_func_struct_no_cut']            = "Juurtaset ei saa teisaldada lõikepuhvrisse!";
$BL['be_func_struct_no_paste1']         = "Siia ei saa kleepida!";
$BL['be_func_struct_no_paste2']         = 'on juurtaseme alamtase';
$BL['be_func_struct_no_paste3']         = 'saab siia kleepida';
$BL['be_func_struct_paste_cancel']      = 'tühista struktuuritaseme muudatus';
$BL['be_func_struct_del_struct']        = 'kustuta struktuuritase';
$BL['be_func_struct_del_sjsmsg']        = 'Kas soovid \nto struktuuritaseme kustutada?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'ava';
$BL['be_func_struct_close']             = 'sulge';
$BL['be_func_struct_empty']             = 'tühi';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'lihttekst';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kood';
$BL['be_ctype_textimage']               = 'tekst ja pilt';
$BL['be_ctype_images']                  = 'pildid';
$BL['be_ctype_bulletlist']              = 'loetelu (tabel)';
$BL['be_ctype_ullist']                  = 'loetelu';
$BL['be_ctype_link']                    = 'link &amp; e-mail';
$BL['be_ctype_linklist']                = 'linkide loetelu';
$BL['be_ctype_linkarticle']             = 'link artiklile';
$BL['be_ctype_multimedia']              = 'multimeedia';
$BL['be_ctype_filelist']                = 'failide loetelu';
$BL['be_ctype_emailform']               = 'vormkiri e-mailile';
$BL['be_ctype_newsletter']              = 'uudiskiri';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Uus profiil loodud.';
$BL['be_profile_create_error']          = 'Viga profiili loomisel.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profiili andmed värskendatud.';
$BL['be_profile_update_error']          = 'Viga profiili andmete värskendamisel.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'kasutajatunnus {VAL} on vale';
$BL['be_profile_account_err2']          = 'parool on liiga lühike (ainult {VAL} tähemärki: vähemalt 5 on nõutav)';
$BL['be_profile_account_err3']          = 'uuesti sisestamisel peab parool olema identne eelmisega';
$BL['be_profile_account_err4']          = 'e-maili aadress {VAL} on vigane';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'isiklikud andmed';
$BL['be_profile_data_text']             = 'isiklike andmete sisestamine ei ole kohustuslik. Kui märgid linnukesega "avalik", annad teistele kasutajatele võimaluse tutvuda oma profiiliga.';
$BL['be_profile_label_title']           = 'tiitel';
$BL['be_profile_label_firstname']       = 'eesnimi';
$BL['be_profile_label_name']            = 'perekonnanimi';
$BL['be_profile_label_company']         = 'firma';
$BL['be_profile_label_street']          = 'tänav';
$BL['be_profile_label_city']            = 'linn';
$BL['be_profile_label_state']           = 'maakond';
$BL['be_profile_label_zip']             = 'postiindeks';
$BL['be_profile_label_country']         = 'riik';
$BL['be_profile_label_phone']           = 'telefon';
$BL['be_profile_label_fax']             = 'faks';
$BL['be_profile_label_cellphone']       = 'mobiiltelefon';
$BL['be_profile_label_signature']       = 'signatuur';
$BL['be_profile_label_notes']           = 'märkus';
$BL['be_profile_label_profession']      = 'tegevusala';
$BL['be_profile_label_newsletter']      = 'uudiskiri';
$BL['be_profile_text_newsletter']       = 'Soovin saada uudiskirja.';
$BL['be_profile_label_public']          = 'avalik';
$BL['be_profile_text_public']           = 'Soovin teha oma andmed teistele kasutajatele avalikuks.';
$BL['be_profile_label_button']          = 'värskenda isiklikke andmeid';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Sinu sisselogimise andmed';
$BL['be_profile_account_text']          = 'Oma kasutajatunnuse muutmiseks pole tavaliselt vajadust.<br />Kuid aeg-ajalt võid sa muuta oma parooli.';
$BL['be_profile_label_err']             = 'palun kontrolli';
$BL['be_profile_label_username']        = 'kasutajatunnus';
$BL['be_profile_label_newpass']         = 'uus parool';
$BL['be_profile_label_repeatpass']      = 'uus parool uuesti';
$BL['be_profile_label_email']           = 'e-mail';
$BL['be_profile_account_button']        = 'värskenda sisselogimise andmeid';
$BL['be_profile_label_lang']            = 'keel';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'võta ftp kaudu laaditud failid üle';
$BL['be_ftptakeover_mark']              = 'märgi';
$BL['be_ftptakeover_available']         = 'kättesaadavad failid';
$BL['be_ftptakeover_size']              = 'suurus';
$BL['be_ftptakeover_nofile']            = 'pole ühtegi kättesaadavat faili &#8211; laadi fail kõigepealt ftp kaudu "phpwcms_ftp" kataloogi';
$BL['be_ftptakeover_all']               = 'KÕIK';
$BL['be_ftptakeover_directory']         = 'kataloog';
$BL['be_ftptakeover_rootdir']           = 'juurkataloog';
$BL['be_ftptakeover_needed']            = 'vajalik!!! (pead valima vähemalt ühe)';
$BL['be_ftptakeover_optional']          = 'pole kohustuslik';
$BL['be_ftptakeover_keywords']          = 'võtmesõnad';
$BL['be_ftptakeover_additional']        = 'täiendavalt';
$BL['be_ftptakeover_longinfo']          = 'pikk kirjeldus';
$BL['be_ftptakeover_status']            = 'olek';
$BL['be_ftptakeover_active']            = 'aktiivne';
$BL['be_ftptakeover_public']            = 'avalik';
$BL['be_ftptakeover_createthumb']       = 'loo miniatuurpilt';
$BL['be_ftptakeover_button']            = 'võta valitud failid üle';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'failihaldus';
$BL['be_ftab_createnew']                = 'loo juurkataloogis uus kataloog';
$BL['be_ftab_paste']                    = 'kleebi lõikepuhvrist fail juurkataloogi';
$BL['be_ftab_disablethumb']             = 'blokeeri loetelus miniatuurpildid';
$BL['be_ftab_enablethumb']              = 'võimalda loetelus miniatuurpildid';
$BL['be_ftab_private']                  = 'isiklikud&nbsp;failid';
$BL['be_ftab_public']                   = 'avalikud&nbsp;failid';
$BL['be_ftab_search']                   = 'otsi';
$BL['be_ftab_trash']                    = 'prügikast';
$BL['be_ftab_open']                     = 'ava kõik kataloogid';
$BL['be_ftab_close']                    = 'sule kõik avatud kataloogid';
$BL['be_ftab_upload']                   = 'laadi fail juurkatalogi';
$BL['be_ftab_filehelp']                 = 'spikker';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'juurkataloog';
$BL['be_fpriv_title']                   = 'loo uus kataloog';
$BL['be_fpriv_inside']                  = 'sees';
$BL['be_fpriv_error']                   = 'viga: lisa kataloogi nimi';
$BL['be_fpriv_name']                    = 'nimi';
$BL['be_fpriv_status']                  = 'olek';
$BL['be_fpriv_button']                  = 'loo uus kataloog';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'muuda kataloogi';
$BL['be_fpriv_newname']                 = 'uus nimi';
$BL['be_fpriv_updatebutton']            = 'värskenda kataloogi infot';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Vali fail, mille soovid üles laadida';
$BL['be_fprivup_err2']                  = 'Üles laaditud fail on suurem kui';
$BL['be_fprivup_err3']                  = 'Viga faili salvestamisel';
$BL['be_fprivup_err4']                  = 'Viga kasutaja kataloogi loomisel.';
$BL['be_fprivup_err5']                  = 'pisipilte pole';
$BL['be_fprivup_err6']                  = 'Pole vajadust uuesti proovida - serveri viga! Võta ühendust <a href="mailto:{VAL}">veebilehe haldajaga</a> as soon as possible!';
$BL['be_fprivup_title']                 = 'Laadi failid üles';
$BL['be_fprivup_button']                = 'Laadi üles';
$BL['be_fprivup_upload']                = 'Laadi üles';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'redigeeri faili andmeid';
$BL['be_fprivedit_filename']            = 'faili nimi';
$BL['be_fprivedit_created']             = 'loodud';
$BL['be_fprivedit_dateformat']          = 'd-m-Y H:i';
$BL['be_fprivedit_err1']                = 'faili algne nimi (taasta algne)';
$BL['be_fprivedit_clockwise']           = 'pööra miniatuurpilti päripäeva [original file +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'pööra miniatuurpilti vastupäeva [original file -90&deg;]';
$BL['be_fprivedit_button']              = 'värskenda faili andmeid';
$BL['be_fprivedit_size']                = 'suurus';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'laadi fail kataloogi';
$BL['be_fprivfunc_makenew']             = 'loo kataloogi sees uus kataloog';
$BL['be_fprivfunc_paste']               = 'kleebi lõikepuhvris olev fail kataloogi';
$BL['be_fprivfunc_edit']                = 'muuda kataloogi';
$BL['be_fprivfunc_cactive']             = 'lülita aktiivne/mitteaktiivne';
$BL['be_fprivfunc_cpublic']             = 'lülita avalik/mitteavalik';
$BL['be_fprivfunc_deldir']              = 'kustuta kataloog';
$BL['be_fprivfunc_jsdeldir']            = 'Kas soovid \nto kustutada kataloogi?';
$BL['be_fprivfunc_notempty']            = 'kataloog {VAL} sisaldab faile!';
$BL['be_fprivfunc_opendir']             = 'ava kataloog';
$BL['be_fprivfunc_closedir']            = 'sule kataloog';
$BL['be_fprivfunc_dlfile']              = 'faili allalaadimine';
$BL['be_fprivfunc_clipfile']            = 'fail lõikepuhvrisse';
$BL['be_fprivfunc_cutfile']             = 'lõika';
$BL['be_fprivfunc_editfile']            = 'muuda faili andmeid';
$BL['be_fprivfunc_cactivefile']         = 'lülita aktiivne/mitteaktiivne';
$BL['be_fprivfunc_cpublicfile']         = 'lülita avalik/mitteavalik';
$BL['be_fprivfunc_movetrash']           = 'saada prügikasti';
$BL['be_fprivfunc_jsmovetrash1']        = 'Kas soovid saata';
$BL['be_fprivfunc_jsmovetrash2']        = 'prügikasti?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'isiklikud failid või kaustad puuduvad';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'kasutaja';
$BL['be_fpublic_nofiles']               = 'isiklikud failid või kaustad puuduvad';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'prügikast on tühi';
$BL['be_ftrash_show']                   = 'näita isiklikke faile';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Kas soovid seda taastada {VAL} \nisiklike failde kausta?';
$BL['be_ftrash_delete']                 = 'Kas soovid kustutada {VAL}?';
$BL['be_ftrash_undo']                   = 'taasta (võta tagasi)';
$BL['be_ftrash_delfinal']               = 'lõplik kustutamine';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'otsingustring on tühi.';
$BL['be_fsearch_title']                 = 'otsi faile';
$BL['be_fsearch_infotext']              = 'Otsinguliides otsib sisestatud märksõnade,<br />failinimede ja täiendava informatsiooni hulgast. Metamärke ei saa otsingus kasutada. <br />Mitu otsingusõna eralda omavahel tühikuga. <br />Vali JA/VÕI ning milliste failide hulgast otsida: isiklikud/avalikud.';
$BL['be_fsearch_nonfound']              = 'ühtegi otsingule vastavat faili ei leitud. korrigeeri otsingu parameetreid!';
$BL['be_fsearch_fillin']                = 'palun täida otsingustring.';
$BL['be_fsearch_searchlabel']           = 'otsi';
$BL['be_fsearch_startsearch']           = 'alusta otsingut';
$BL['be_fsearch_and']                   = 'JA';
$BL['be_fsearch_or']                    = 'VÕI';
$BL['be_fsearch_all']                   = 'kõik failid';
$BL['be_fsearch_personal']              = 'isiklikud';
$BL['be_fsearch_public']                = 'avalikud';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'vestlusruum';
$BL['be_chat_info']                     = 'Ruum teiste sisse loginud kasutajatega back-end vestluse pidamiseks. Lisaks reaalajas vestlusele saab jätta ka sõnumeid, mida kõik kasutajad lugeda saavad.';
$BL['be_chat_start']                    = 'vestluse alustamiseks kliki siia';
$BL['be_chat_lines']                    = 'näita ridasid';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'sõnumihaldus';
$BL['be_msg_new']                       = 'uus';
$BL['be_msg_old']                       = 'vana';
$BL['be_msg_senttop']                   = 'saadetud';
$BL['be_msg_del']                       = 'kustutatud';
$BL['be_msg_from']                      = 'kellelt';
$BL['be_msg_subject']                   = 'teema';
$BL['be_msg_date']                      = 'kuupäev/aeg';
$BL['be_msg_close']                     = 'sulge sõnum';
$BL['be_msg_create']                    = 'kirjuta uus sõnum';
$BL['be_msg_reply']                     = 'vasta sõnumile';
$BL['be_msg_move']                      = 'saada sõnum prügikasti';
$BL['be_msg_unread']                    = 'uued või lugemata sõnumid';
$BL['be_msg_lastread']                  = 'viimased {VAL} loetud sõnumit';
$BL['be_msg_lastsent']                  = 'viimased {VAL} saadetud sõnumit';
$BL['be_msg_marked']                    = 'kustutamiseks märgitud sõnumid (prügikast)';
$BL['be_msg_nomsg']                     = 'kaustas pole sõnumeid';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'saatja';
$BL['be_msg_on']                        = 'on';
$BL['be_msg_msg']                       = 'teade';
$BL['be_msg_err1']                      = 'puudub saaja...';
$BL['be_msg_err2']                      = 'märgi teema (saaja saab paremini hallata oma sõnumeid)';
$BL['be_msg_err3']                      = 'puudub mõte saata endale ilma sisuta sõnumit ;-)';
$BL['be_msg_sent']                      = 'sõnum on saadetud!';
$BL['be_msg_fwd']                       = 'hetke pärast suunatakse sind tagasi sõnumikeskusesse või';
$BL['be_msg_newmsgtitle']               = 'kirjuta uus sõnum';
$BL['be_msg_err']                       = 'sõnumi saatmisel viga';
$BL['be_msg_sendto']                    = 'saaja(d)';
$BL['be_msg_available']                 = 'vali saaja';
$BL['be_msg_all']                       = 'saada sõnum kõigile valitud isikutele';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'uudiskirja tellimus';
$BL['be_newsletter_titleedit']          = 'muuda uudiskirja tellimust';
$BL['be_newsletter_new']                = 'loo uus';
$BL['be_newsletter_add']                = 'lisa&nbsp;uudiskirja&nbsp;tellimus';
$BL['be_newsletter_name']               = 'nimi';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'salvesta';
$BL['be_newsletter_button_cancel']      = 'katkesta';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'kasutajatunnus ei sobi, vali teine';
$BL['be_admin_usr_err2']                = 'kasutajatunnus on tühi (nõutav)';
$BL['be_admin_usr_err3']                = 'parool on tühi (nõutav)';
$BL['be_admin_usr_err4']                = "e-maili aadress pole korrektselt";
$BL['be_admin_usr_err']                 = 'viga';
$BL['be_admin_usr_mailsubject']         = 'tere tulemast phpwcms back-end kasutajaliidesesse';
$BL['be_admin_usr_mailbody']            = "TERE TULEMAST PHPWCMS BACK-END KASUTAJALIIDESESSE\n\n    kasutajatunnus: {LOGIN}\n    parool: {PASSWORD}\n\n\nLogi sisse: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'lisa kasutajakonto';
$BL['be_admin_usr_realname']            = 'tegelik nimi';
$BL['be_admin_usr_setactive']           = 'aktiveeri kasutajakonto';
$BL['be_admin_usr_iflogin']             = 'aktiveerides saab kasutaja sisse logida';
$BL['be_admin_usr_isadmin']             = 'kasutaja on admin';
$BL['be_admin_usr_ifadmin']             = 'kasutaja saab administraatori õigused';
$BL['be_admin_usr_verify']              = 'tuvastus';
$BL['be_admin_usr_sendemail']           = 'saada uuele kasutajale e-mail kasutaja andmetega';
$BL['be_admin_usr_button']              = 'saada kasutaja andmed';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'muuda kasutajakontot';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - kontoandmed on muudetud';
$BL['be_admin_usr_emailbody']           = "PHPWCMS KASUTAJAKONTO ANDMED ON MUUDETUD\n\n    kasutajatunnus: {LOGIN}\n    parool: {PASSWORD}\n\n\nLogi sisse: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[MUUDATUSI POLE - KASUTA VANA PAROOLI]';
$BL['be_admin_usr_ebutton']             = 'värskenda kasutaja andmeid';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms back-end kasutajate haldus';
$BL['be_admin_usr_ldel']                = 'TÄHELEPANU!&#13See võib kasutaja kustutada';
$BL['be_admin_usr_create']              = 'loo uus kasutaja';
$BL['be_admin_usr_editusr']             = 'muuda kasutajat';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'Veebilehe struktuur  (ehk sisukord).';
$BL['be_admin_struct_child']            = '| käesolev on alamtasemeks sisukorrapunktile';
$BL['be_admin_struct_index']            = 'index (veebilehe algus)';
$BL['be_admin_struct_cat']              = 'struktuuritaseme nimetus';
$BL['be_admin_struct_hide1']            = 'peida';
$BL['be_admin_struct_hide2']            = 'this&nbsp;category&nbsp;in&nbsp;menu';
$BL['be_admin_struct_info']             = 'kategooria infotekst';
$BL['be_admin_struct_template']         = 'mall';
$BL['be_admin_struct_alias']            = 'kategooria alias';
$BL['be_admin_struct_visible']          = 'nähtav';
$BL['be_admin_struct_button']           = 'salvesta';
$BL['be_admin_struct_close']            = 'katkesta';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'failikategooriad';
$BL['be_admin_fcat_err']                = 'kategooria nimetus puudub!';
$BL['be_admin_fcat_name']               = 'kategooria nimetus';
$BL['be_admin_fcat_needed']             = 'nõutav';
$BL['be_admin_fcat_button1']            = 'värskenda';
$BL['be_admin_fcat_button2']            = 'salvesta';
$BL['be_admin_fcat_delmsg']             = 'Kas soovid\nkustutada faili võtme?';
$BL['be_admin_fcat_fcat']               = 'faili kategooria';
$BL['be_admin_fcat_err1']               = 'faili võtme nimetus puudub!';
$BL['be_admin_fcat_fkeyname']           = 'faili võtme nimetus';
$BL['be_admin_fcat_exit']               = 'katkesta';
$BL['be_admin_fcat_addkey']             = 'lisa uus võti';
$BL['be_admin_fcat_editcat']            = 'muuda kategooria nime';
$BL['be_admin_fcat_delcatmsg']          = 'Kas soovid\nkustutada failikategooria?';
$BL['be_admin_fcat_delcat']             = 'kustuta failikategooria';
$BL['be_admin_fcat_delkey']             = 'kustuta faili võtme nimetus';
$BL['be_admin_fcat_editkey']            = 'muuda võtit';
$BL['be_admin_fcat_addcat']             = 'loo uus failikategooria';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'Front-end vorming, veebilehe küljendus.<br /> Iga mall on seotud ühe vorminguga ning igale struktuuritasemele võid rakendada erineva malli. Loo uus vorming, seejärel lisa mall, siis pane paika lehe struktuur ehk sisukord. Kõige lõpuks lisa artiklid.<br />Siin määrad ära põhiandmed: lehekülje suuruse, asetuse, küljenduse, värvid, plokid jne.';
$BL['be_admin_page_align']              = 'lehekülje joondamine';
$BL['be_admin_page_align_left']         = 'joonda vasakule';
$BL['be_admin_page_align_center']       = 'joonda keskele';
$BL['be_admin_page_align_right']        = 'joonda paremale';
$BL['be_admin_page_margin']             = 'veeris';
$BL['be_admin_page_top']                = 'ülemine';
$BL['be_admin_page_bottom']             = 'alumine';
$BL['be_admin_page_left']               = 'vasak';
$BL['be_admin_page_right']              = 'parem';
$BL['be_admin_page_bg']                 = 'taust';
$BL['be_admin_page_color']              = 'värv';
$BL['be_admin_page_height']             = 'kõrgus';
$BL['be_admin_page_width']              = 'laius';
$BL['be_admin_page_main']               = 'põhiplokk';
$BL['be_admin_page_leftspace']          = 'vasak vahe';
$BL['be_admin_page_rightspace']         = 'parem vahe';
$BL['be_admin_page_class']              = 'klass';
$BL['be_admin_page_image']              = 'pilt';
$BL['be_admin_page_text']               = 'tekst';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'külastatud';
$BL['be_admin_page_pagetitle']          = 'lehe&nbsp;tiitel';
$BL['be_admin_page_addtotitle']         = 'lisa&nbsp;tiitlile';
$BL['be_admin_page_category']           = 'kategooria';
$BL['be_admin_page_articlename']        = 'artikli&nbsp;nimi';
$BL['be_admin_page_blocks']             = 'plokid';
$BL['be_admin_page_allblocks']          = 'kõik plokid';
$BL['be_admin_page_col1']               = '3-tulbaline küljend';
$BL['be_admin_page_col2']               = '2-tulbaline küljend (põhitulp paremal, navigeerimistulp vasakul)';
$BL['be_admin_page_col3']               = '2-tulbaline küljend (põhitulp vasakul, navigeerimistulp paremal)';
$BL['be_admin_page_col4']               = '1-tulbaline küljend';
$BL['be_admin_page_header']             = 'ülemine plokk';
$BL['be_admin_page_footer']             = 'alumine plokk';
$BL['be_admin_page_topspace']           = 'ülemine&nbsp;vahe';
$BL['be_admin_page_bottomspace']        = 'alumine&nbsp;vahe';
$BL['be_admin_page_button']             = 'salvesta';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'Front-end vorming, css vaikeväärtused. Siin määrad kasutatavad kirjastiilid, teksti suurused, -värvid, reavahed jne.';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'salvesta';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'Front-end vorming, mallid.<br />Iga mall on seotud ühe vorminguga ning igale struktuuritasemele võid rakendada erineva malli.<br />Siin määrad ära plokkide sisu. Näiteks kas menüü asetseb ülal või vasakul jne.';
$BL['be_admin_tmpl_default']            = 'vaikimisi';
$BL['be_admin_tmpl_add']                = 'lisa&nbsp;mall';
$BL['be_admin_tmpl_edit']               = 'muuda malli';
$BL['be_admin_tmpl_new']                = 'loo uus';
$BL['be_admin_tmpl_css']                = 'css fail';
$BL['be_admin_tmpl_head']               = 'html päis';
$BL['be_admin_tmpl_js']                 = 'laadi javasc.';
$BL['be_admin_tmpl_error']              = 'veateade';
$BL['be_admin_tmpl_button']             = 'salvesta';
$BL['be_admin_tmpl_name']               = 'nimi';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'veebilehe struktuur (ehk sisukord), artiklite haldus';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'artikli pealkiri puudub';
$BL['be_article_err2']                  = 'alguskuupäev on vale - määra uuesti';
$BL['be_article_err3']                  = 'lõpukuupäev on vale - määra uuesti';
$BL['be_article_title1']                = 'artikli põhiandmed';
$BL['be_article_cat']                   = 'struktuuritase';
$BL['be_article_atitle']                = 'pealkiri';
$BL['be_article_asubtitle']             = 'alampealkiri';
$BL['be_article_abegin']                = 'algab';
$BL['be_article_aend']                  = 'lõpeb';
$BL['be_article_aredirect']             = 'suuna ümber';
$BL['be_article_akeywords']             = 'võtmesõnad';
$BL['be_article_asummary']              = 'kokkuvõte';
$BL['be_article_abutton']               = 'loo uus artikkel';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'lõpukuupäev on vale - määra uuesti + 1 nädal';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'muuda artikli põhiandmeid';
$BL['be_article_eslastedit']            = 'viimati muudetud';
$BL['be_article_esnoupdate']            = 'vormi ei värskendatud';
$BL['be_article_esbutton']              = 'salvesta';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'artikli sisu';
$BL['be_article_cnt_type']              = 'sisu tüüp';
$BL['be_article_cnt_space']             = 'tühi rida';
$BL['be_article_cnt_before']            = 'enne';
$BL['be_article_cnt_after']             = 'pärast';
$BL['be_article_cnt_top']               = 'üles';
$BL['be_article_cnt_ctitle']            = 'sisu pealkiri';
$BL['be_article_cnt_back']              = 'kogu artikli info';
$BL['be_article_cnt_button1']           = 'värskenda sisu';
$BL['be_article_cnt_button2']           = 'salvesta';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'artikli info';
$BL['be_article_cnt_ledit']             = 'muuda artiklit';
$BL['be_article_cnt_lvisible']          = 'lülita nähtav/mittenähtav';
$BL['be_article_cnt_ldel']              = 'kustuta artikkel';
$BL['be_article_cnt_ldeljs']            = 'Kustutad artikli?';
$BL['be_article_cnt_redirect']          = 'ümbersuunamine';
$BL['be_article_cnt_edited']            = 'viimati muutis';
$BL['be_article_cnt_start']             = 'alguskuupäev (aaaa-kk-pp)';
$BL['be_article_cnt_end']               = 'lõpukuupäev (aaaa-kk-pp)';
$BL['be_article_cnt_add']               = 'lisa uus sisuosa';
$BL['be_article_cnt_up']                = 'liiguta sisu üles';
$BL['be_article_cnt_down']              = 'liiguta sisu alla';
$BL['be_article_cnt_edit']              = 'muuda sisuosa';
$BL['be_article_cnt_delpart']           = 'kustuta artikli sisuosa';
$BL['be_article_cnt_delpartjs']         = 'Kustutad sisuosa?';
$BL['be_article_cnt_center']            = 'artiklite haldus';

// content forms
$BL['be_cnt_plaintext']                 = 'lihttekst';
$BL['be_cnt_htmltext']                  = 'html tekst';
$BL['be_cnt_image']                     = 'pilt';
$BL['be_cnt_position']                  = 'asend';
$BL['be_cnt_pos0']                      = 'Ülal, vasakul';
$BL['be_cnt_pos1']                      = 'Ülal, keskel';
$BL['be_cnt_pos2']                      = 'Ülal, paremal';
$BL['be_cnt_pos3']                      = 'All, vasakul';
$BL['be_cnt_pos4']                      = 'All, keskel';
$BL['be_cnt_pos5']                      = 'All, paremal';
$BL['be_cnt_pos6']                      = 'Tekstis, vasakul';
$BL['be_cnt_pos7']                      = 'Tekstis, paremal';
$BL['be_cnt_pos0i']                     = 'joonda pilt tekstiplokis üles vasakule';
$BL['be_cnt_pos1i']                     = 'joonda pilt tekstiplokis üles keskele';
$BL['be_cnt_pos2i']                     = 'joonda pilt tekstiplokis üles paremale';
$BL['be_cnt_pos3i']                     = 'joonda pilt tekstiplokis alla vasakule';
$BL['be_cnt_pos4i']                     = 'joonda pilt tekstiplokis alla keskele';
$BL['be_cnt_pos5i']                     = 'joonda pilt tekstiplokis alla paremale';
$BL['be_cnt_pos6i']                     = 'joonda pilt tekstiploki sees vasakule';
$BL['be_cnt_pos7i']                     = 'joonda pilt tekstiploki sees paremale';
$BL['be_cnt_maxw']                      = 'maks.&nbsp;laius';
$BL['be_cnt_maxh']                      = 'maks.&nbsp;kõrgus';
$BL['be_cnt_enlarge']                   = 'kliki&nbsp;suuremaks';
$BL['be_cnt_caption']                   = 'piltide infotekst';
$BL['be_cnt_subject']                   = 'teema';
$BL['be_cnt_recipient']                 = 'saaja';
$BL['be_cnt_buttontext']                = 'nupu tekst';
$BL['be_cnt_sendas']                    = 'saada kui';
$BL['be_cnt_text']                      = 'tekst';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'vormi väljad';
$BL['be_cnt_code']                      = 'kood';
$BL['be_cnt_infotext']                  = 'info&nbsp;tekst';
$BL['be_cnt_subscription']              = 'tellimus';
$BL['be_cnt_labelemail']                = 'märgend&nbsp;e-mail';
$BL['be_cnt_tablealign']                = 'tabel&nbsp;joonda';
$BL['be_cnt_labelname']                 = 'märgend&nbsp;nimi';
$BL['be_cnt_labelsubsc']                = 'märgend&nbsp;subscr.';
$BL['be_cnt_allsubsc']                  = 'kõik&nbsp;tellij.';
$BL['be_cnt_default']                   = 'vaikimisi';
$BL['be_cnt_left']                      = 'vasakule';
$BL['be_cnt_center']                    = 'keskele';
$BL['be_cnt_right']                     = 'paremale';
$BL['be_cnt_buttontext']                = 'tekst&nbsp;nupul';
$BL['be_cnt_successtext']               = '"saadetud"&nbsp;tekst';
$BL['be_cnt_regmail']                   = 'regist.e-mail';
$BL['be_cnt_logoffmail']                = 'logoff.e-mail';
$BL['be_cnt_changemail']                = 'muuda.e-mail';
$BL['be_cnt_openimagebrowser']          = 'ava pildibrauser';
$BL['be_cnt_openfilebrowser']           = 'ava failibrauser';
$BL['be_cnt_sortup']                    = 'liiguta üles';
$BL['be_cnt_sortdown']                  = 'liiguta alla';
$BL['be_cnt_delimage']                  = 'eemalda valitud pilt';
$BL['be_cnt_delfile']                   = 'eemalda valitud fail';
$BL['be_cnt_delmedia']                  = 'eemalda valitud meedia';
$BL['be_cnt_column']                    = 'tulp';
$BL['be_cnt_imagespace']                = 'piltide&nbsp;vahe';
$BL['be_cnt_directlink']                = 'otselink';
$BL['be_cnt_target']                    = 'paneel';
$BL['be_cnt_target1']                   = 'uues aknas';
$BL['be_cnt_target2']                   = 'sama akna freimis';
$BL['be_cnt_target3']                   = 'samas aknas ilma freimideta';
$BL['be_cnt_target4']                   = 'samas freimis või aknas';
$BL['be_cnt_bullet']                    = 'loetelu tabelina';
$BL['be_cnt_ullist']                    = 'loetelu';
$BL['be_cnt_ullist_desc']               = '~ = 1ne tase, &nbsp; ~~ = 2ne tase, &nbsp; jne.';
$BL['be_cnt_linklist']                  = 'linkide loetelu';
$BL['be_cnt_plainhtml']                 = 'html kood';
$BL['be_cnt_files']                     = 'failid';
$BL['be_cnt_description']               = 'kirjeldus';
$BL['be_cnt_linkarticle']               = 'link artiklitele';
$BL['be_cnt_articles']                  = 'artiklid';
$BL['be_cnt_movearticleto']             = 'vii valitud artikkel "lingid artiklitele" loetelusse';
$BL['be_cnt_removearticleto']           = 'kõrvalda valitud artikkel "lingid artiklitele" loetelust';
$BL['be_cnt_mediatype']                 = 'meedia tüüp';
$BL['be_cnt_control']                   = 'juhtimine';
$BL['be_cnt_showcontrol']               = 'näita juhtimisriba';
$BL['be_cnt_autoplay']                  = 'käivita automaatselt';
$BL['be_cnt_source']                    = 'allikas';
$BL['be_cnt_internal']                  = 'sisemine';
$BL['be_cnt_openmediabrowser']          = 'ava meediabrauser';
$BL['be_cnt_external']                  = 'välimine';
$BL['be_cnt_mediapos0']                 = 'vasakule (vaikimisi)';
$BL['be_cnt_mediapos1']                 = 'keskele';
$BL['be_cnt_mediapos2']                 = 'paremale';
$BL['be_cnt_mediapos3']                 = 'tekstplokki, vasakule';
$BL['be_cnt_mediapos4']                 = 'tekstplokki, paremale';
$BL['be_cnt_mediapos0i']                = 'joonda meedia tekstplokis üles vasakule';
$BL['be_cnt_mediapos1i']                = 'joonda meedia tekstplokis üles keskele';
$BL['be_cnt_mediapos2i']                = 'joonda meedia tekstplokis üles paremale';
$BL['be_cnt_mediapos3i']                = 'joonda meedia tekstploki sees vasakule';
$BL['be_cnt_mediapos4i']                = 'joonda meedia tekstploki sees paremale';
$BL['be_cnt_setsize']                   = 'määra suurus';
$BL['be_cnt_set1']                      = 'määra suuruseks 160x120px';
$BL['be_cnt_set2']                      = 'määra suuruseks 240x180px';
$BL['be_cnt_set3']                      = 'määra suuruseks 320x240px';
$BL['be_cnt_set4']                      = 'määra suuruseks 480x360px';
$BL['be_cnt_set5']                      = 'puhasta meedia laius ja kõrgus';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'loo uus vorming';
$BL['be_admin_page_name']               = 'vormingu nimi';
$BL['be_admin_page_edit']               = 'muuda vormingut';
$BL['be_admin_page_render']             = 'viimistlus';
$BL['be_admin_page_table']              = 'tabel';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'kohandatud';
$BL['be_admin_page_custominfo']         = 'malli põhiplokist';
$BL['be_admin_tmpl_layout']             = 'vorming';
$BL['be_admin_tmpl_nolayout']           = 'Pole ühtegi vormingut!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'otsing';
$BL['be_cnt_results']                   = 'tulemused';
$BL['be_cnt_results_per_page']          = 'lehekülje&nbsp;kohta (kui tühi, näita kõiki)';
$BL['be_cnt_opennewwin']                = 'ava uus aken';
$BL['be_cnt_searchlabeltext']           = 'eeldefineeritud tekstid ja väärtused otsinguvormi ja otsinguresultaadi lehe jaoks.';
$BL['be_cnt_input']                     = 'sisestus';
$BL['be_cnt_style']                     = 'stiil';
$BL['be_cnt_result']                    = 'tulemus';
$BL['be_cnt_next']                      = 'järgmine';
$BL['be_cnt_previous']                  = 'eelmine';
$BL['be_cnt_align']                     = 'joonda';
$BL['be_cnt_searchformtext']            = 'järgnevad tekstid esinevad otsinguvormis või otsingutulemuste lehel.';
$BL['be_cnt_intro']                     = 'sissejuhatus';
$BL['be_cnt_noresult']                  = 'ei leitud';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'keela';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'artikli saatja';
$BL['be_article_adminuser']             = 'administraator';
$BL['be_article_username']              = 'autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'nähtav ainult sisse loginud kasutajatele';
$BL['be_admin_struct_status']           = 'olek front-end menüüs';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'artiklite sisukord';
$BL['be_cnt_sitelevel']                 = 'lehe struktuuritase';
$BL['be_cnt_sitecurrent']               = 'käesolev struktuuritase';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'back-end avatekst';
$BL['be_ctype_ecard']                   = 'e-kaart';
$BL['be_ctype_blog']                    = 'veebipäevik (blog)';
$BL['be_cnt_ecardtext']                 = 'e-kaart/pealkiri';
$BL['be_cnt_ecardtmpl']                 = 'e-maili mall';
$BL['be_cnt_ecard_image']               = 'e-kaardi pilt';
$BL['be_cnt_ecard_title']               = 'e-kaardi pealkiri';
$BL['be_cnt_alignment']                 = 'joonda';
$BL['be_cnt_ecardform']                 = 'vormi mall';
$BL['be_cnt_ecardform_err']             = '* väljad nõutavad';
$BL['be_cnt_ecardform_sender']          = 'Saatja';
$BL['be_cnt_ecardform_recipient']       = 'Saaja';
$BL['be_cnt_ecardform_name']            = 'Nimi';
$BL['be_cnt_ecardform_msgtext']         = 'Sinu tekst';
$BL['be_cnt_ecardform_button']          = 'saada e-kaart';
$BL['be_cnt_ecardsend']                 = '"saadetud" mall';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Back-end vaikimisi avatekst';
$BL['be_admin_startup_text']            = 'avatekst';
$BL['be_admin_startup_button']          = 'salvesta';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'külalisteraamat';
$BL['be_cnt_guestbook_listing']         = 'kuva';
$BL['be_cnt_guestbook_listing_all']     = 'kuva&nbsp;kõik&nbsp;sissekanded';
$BL['be_cnt_guestbook_list']            = 'sissekanded';
$BL['be_cnt_guestbook_perpage']         = 'lehekülje&nbsp;kohta';
$BL['be_cnt_guestbook_form']            = 'vorm';
$BL['be_cnt_guestbook_signed']          = 'signed';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'enne';
$BL['be_cnt_guestbook_after']           = 'pärast';
$BL['be_cnt_guestbook_entry']           = 'sissekanne';
$BL['be_cnt_guestbook_edit']            = 'muuda';
$BL['be_cnt_ecardform_selector']        = 'märkija';
$BL['be_cnt_ecardform_radiobutton']     = 'radio button';
$BL['be_cnt_ecardform_javascript']      = 'JavaScripti kasutusega';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'mitu artiklit näidata uuemate artiklite loetelus (kui 1, siis esimene artikkel täismahus)';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'uudiskiri';
$BL['be_newsletter_addnl']              = 'lisa uudiskiri';
$BL['be_newsletter_titleeditnl']        = 'muuda uudiskirja';
$BL['be_newsletter_newnl']              = 'loo uus';
$BL['be_newsletter_button_savenl']      = 'salvesta';
$BL['be_newsletter_fromname']           = 'kellelt e-mail';
$BL['be_newsletter_fromemail']          = 'kellele e-mail';
$BL['be_newsletter_replyto']            = 'reply e-mail';
$BL['be_newsletter_changed']            = 'viimane muudatus';
$BL['be_newsletter_placeholder']        = 'kohatäide';
$BL['be_newsletter_htmlpart']           = 'HTML uudiskirja sisu';
$BL['be_newsletter_textpart']           = 'Sisukokkuvõte';
$BL['be_newsletter_allsubscriptions']   = 'kõik tellijad';
$BL['be_newsletter_verifypage']         = 'kontrolli linki';
$BL['be_newsletter_open']               = 'HTML/TEXT sisend';
$BL['be_newsletter_open1']              = '(avamiseks kliki ikoonile)';
$BL['be_newsletter_sendnow']            = 'Saada uudiskiri';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Tähelepanu!</strong> Uudiskirja saatmist paljudele saajatele korraga võib käsitleda spämmina, kontrolli saajate nimekirja ning saada uudiskiri igaks juhuks eelnevalt testiks oma aadressil.';
$BL['be_newsletter_attention1']         = 'Kui oled teinud muudatusi, palun salvesta need kõigepealt!';
$BL['be_newsletter_testemail']          = 'test e-mail';
$BL['be_newsletter_sendnlbutton']       = 'saada uudiskiri';
$BL['be_newsletter_sendprocess']        = 'saadan';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Tähelepanu!</strong> Ära katkesta kuni uudiskirja saatmine pole õnnelikult lõpule jõudnud. Vastasel korral loetakse sessioon ebaõnnestunuks ja järgmine kord saadetakse sama uudiskiri uuesti!.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">test e-maili aaddress <strong>###TEST###</strong> pole õige!<br />&nbsp;<br />Palun proovi uuesti!';
$BL['be_newsletter_to']                 = 'Saajad';
$BL['be_newsletter_ready']              = 'uudiskirja saatmine: VALMIS';
$BL['be_newsletter_readyfailed']        = 'Saatmine ebaõnnestus';
$BL['be_subnav_msg_subscribers']        = 'uudiskirja tellijad';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'sisukord';
$BL['be_cnt_sitemap_catimage']          = 'taseme ikoon';
$BL['be_cnt_sitemap_articleimage']      = 'artikli ikoon';
$BL['be_cnt_sitemap_display']           = 'kuva';
$BL['be_cnt_sitemap_structuronly']      = 'ainult struktuuritasemed';
$BL['be_cnt_sitemap_structurarticle']   = 'struktuuritasemed + artiklid';
$BL['be_cnt_sitemap_catclass']          = 'taseme klass';
$BL['be_cnt_sitemap_articleclass']      = 'artikli klass';
$BL['be_cnt_sitemap_count']             = 'counter';
$BL['be_cnt_sitemap_classcount']        = 'lisa klassi nimele';
$BL['be_cnt_sitemap_noclasscount']      = 'mitte lisada klassi nimele';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'pakkumine';
$BL['be_cnt_bid_bidtext']               = 'pakkumise tekst';
$BL['be_cnt_bid_sendtext']              = '"saadetud" tekst';
$BL['be_cnt_bid_verifiedtext']          = 'kinnitatud tekst';
$BL['be_cnt_bid_errortext']             = 'pakkumine kustutatud';
$BL['be_cnt_bid_verifyemail']           = 'kinnita e-mail';
$BL['be_cnt_bid_startbid']              = 'alghind';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'pakkumise&nbsp;samm';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'ext. content';
$BL['be_cnt_pages_select']              = 'vali fail';
$BL['be_cnt_pages_fromfile']            = 'fail struktuurist';
$BL['be_cnt_pages_manually']            = 'kohandatud tee/fail või URL';
$BL['be_cnt_pages_cust']                = 'fail/URL';
$BL['be_cnt_pages_from']                = 'läte';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover pildid';
$BL['be_cnt_reference_basis']           = 'joondamine';
$BL['be_cnt_reference_horizontal']      = 'horisontaalne';
$BL['be_cnt_reference_vertical']        = 'vertikaalne';
$BL['be_cnt_reference_aligntext']       = 'miniatuurpildid';
$BL['be_cnt_reference_largetext']       = 'suured pildid';
$BL['be_cnt_reference_zoom']            = 'zoom';
$BL['be_cnt_reference_middle']          = 'keskmine';
$BL['be_cnt_reference_border']          = 'äärisjoon';
$BL['be_cnt_reference_block']           = 'plokk l x k';

// added: 31-05-2004
$BL['be_article_rendering']             = 'viimistlus';
$BL['be_article_nosummary']             = 'ära näita artikli juures kokkuvõtet';
$BL['be_article_forlist']               = 'artiklite loetelu';
$BL['be_article_forfull']               = 'kuva terve artikkel';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<div style="font-size: 14px;">TÄHELEPANU!</div>SETUP&quot; kataloog on kustutamata!<br>Kui oled phpwcms-i edukalt seadistanud, siis kustuta see kataloog. Jättes kataloogi kustutamata ning vaikimisi kasutajanime ja parooli, jätad ka võimaluse oma server üle võtta.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'keelatud sõnad';
$BL['be_cnt_guestbook_flooding']        = 'marsruutimine';
$BL['be_cnt_guestbook_setcookie']       = 'saada küpsis';
$BL['be_cnt_guestbook_allowed']         = 'lubatud jälle peale';
$BL['be_cnt_guestbook_seconds']         = 'järgmised';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Kas soovid prügikasti tühjendada?";
$BL['be_ftrash_delallfiles']            = 'tühjenda prügikast';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'CSV tellijate import';
$BL['be_newsletter_importtitle']        = 'Impordi uudiskirja tellijad';
$BL['be_newsletter_entriesfound']       = 'sissekannet&nbsp;leitud';
$BL['be_newsletter_foundinfile']        = 'failis';
$BL['be_newsletter_addresses']          = 'aadressid';
$BL['be_newsletter_csverror']           = 'Imporditud CSV fail pole korrektne! Kontrolli eraldajat!';
$BL['be_newsletter_importall']          = 'impordi kõik kirjed';
$BL['be_newsletter_addressesadded']     = 'aadressid on lisatud.';
$BL['be_newsletter_newimport']          = 'uus import';
$BL['be_newsletter_importerror']        = 'Palun kontrolli CSV faili - aadressi ei saa lisada!';
$BL['be_newsletter_shouldbe1']          = 'Sinu CSV fail võiks olla sellisel kujul';
$BL['be_newsletter_shouldbe2']          = 'aga sa võid valida kohandatud eraldaja';
$BL['be_newsletter_sample']             = 'näidis';
$BL['be_newsletter_selectCSV']          = 'vali CSV fail';
$BL['be_newsletter_delimeter']          = 'eraldaja';
$BL['be_newsletter_importCSV']          = 'impordi CSV fail';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'määratud artiklite tellimine';
$BL['be_admin_struct_orderdate']        = 'loodud';
$BL['be_admin_struct_orderchangedate']  = 'muudetud';
$BL['be_admin_struct_orderstartdate']   = 'alustatud';
$BL['be_admin_struct_orderdesc']        = 'laskuvas järjestuses';
$BL['be_admin_struct_orderasc']         = 'tõusvas järjestuses';
$BL['be_admin_struct_ordermanual']      = 'käsitsi (nool üles/alla)';
$BL['be_cnt_sitemap_startid']           = 'alusta';


