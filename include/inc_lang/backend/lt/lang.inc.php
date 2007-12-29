<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2006 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.

   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license
   from the author is found in LICENSE.txt distributed with these scripts.

   This script is distributed in the hope that it will be useful, but WITHOUT ANY
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.

   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/


// Language: Lithuanian, Language Code: lt
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'prisijungæ vartotojai';

// Login Page
$BL["login_text"]                       = 'Áveskite prisijungimo duomenis';
$BL['login_error']                      = 'Prisijungimo klaidos!';
$BL["login_username"]                   = 'vardas';
$BL["login_userpass"]                   = 'slaptaþodis';
$BL["login_button"]                     = 'Prisijungti';
$BL["login_lang"]                       = 'administravimo kalba';

// phpwcms.php
$BL['be_nav_logout']                    = 'ATSIJUNGTI';
$BL['be_nav_articles']                  = 'TURINYS';
$BL['be_nav_files']                     = 'FAILAI';
$BL['be_nav_modules']                   = 'MODULIAI';
$BL['be_nav_messages']                  = 'ÞINUTËS';
$BL['be_nav_chat']                      = 'POKALBIAI';
$BL['be_nav_profile']                   = 'PROFILIS';
$BL['be_nav_admin']                     = 'ADMINISTRAVIMAS';
$BL['be_nav_discuss']                   = 'DISKUSIJOS';

$BL['be_page_title']                    = 'phpwcms administravimas';

$BL['be_subnav_article_center']         = 'straipsniø centras';
$BL['be_subnav_article_new']            = 'naujas straipsnis';
$BL['be_subnav_file_center']            = 'failø centras';
$BL['be_subnav_file_ftptakeover']       = 'ftp perëmimas';
$BL['be_subnav_mod_artists']            = 'atlikëjas, kategojis, þanras';
$BL['be_subnav_msg_center']             = 'þinuèiø centras';
$BL['be_subnav_msg_new']                = 'nauja þinutë';
$BL['be_subnav_msg_newsletter']         = 'naujienlaiðkiø prenumerata';
$BL['be_subnav_chat_main']              = 'pagrindinis pokalbiø puslapis';
$BL['be_subnav_chat_internal']          = 'vidiniai pokalbiai';
$BL['be_subnav_profile_login']          = 'prisijungimo informacija';
$BL['be_subnav_profile_personal']       = 'asmeniniai duomenus';
$BL['be_subnav_admin_pagelayout']       = 'puslapio maketas';
$BL['be_subnav_admin_templates']        = 'ðablonai';
$BL['be_subnav_admin_css']              = 'css pagal nutylëjimà';
$BL['be_subnav_admin_sitestructure']    = 'tinklapio struktûra';
$BL['be_subnav_admin_users']            = 'vartotojø valdymas';
$BL['be_subnav_admin_filecat']          = 'failø kategorijos';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'straipsnio ID';
$BL['be_func_struct_preview']           = 'perþiûra';
$BL['be_func_struct_edit']              = 'redaguoti straipsná';
$BL['be_func_struct_sedit']             = 'redaguoti struktûros lygá';
$BL['be_func_struct_cut']               = 'iðkirpti straipsná';
$BL['be_func_struct_nocut']             = 'deaktyvuoti straipsnio iðkirpimà';
$BL['be_func_struct_svisible']          = 'perjungti matomà/nematomà';
$BL['be_func_struct_spublic']           = 'perjungti vieðas/ne vieðas';
$BL['be_func_struct_sort_up']           = 'aukðtyn';
$BL['be_func_struct_sort_down']         = 'þemyn';
$BL['be_func_struct_del_article']       = 'iðtrinti straipsná';
$BL['be_func_struct_del_jsmsg']         = 'Ar tikrai norite \niðtrinti straipsná?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'sukurti naujà straipsná ðiame struktûros lygyje';
$BL['be_func_struct_paste_article']     = 'áklijuoti straipsná ðiame struktûros lygyje';
$BL['be_func_struct_insert_level']      = 'áterpti struktûros lygá';
$BL['be_func_struct_paste_level']       = 'áklijuoti struktûros lygá';
$BL['be_func_struct_cut_level']         = 'iðkirpti struktûros lygá';
$BL['be_func_struct_no_cut']            = "Neámanoma iðkirpti pradinio lygio!";
$BL['be_func_struct_no_paste1']         = "Neámanoma èia áklijuoti!";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'áklijuoti reikëtø èia';
$BL['be_func_struct_paste_cancel']      = 'cancel structure level change';
$BL['be_func_struct_del_struct']        = 'iðtrinti struktûros lygá';
$BL['be_func_struct_del_sjsmsg']        = 'Ar tikrai norite iðtrinti \nstruktûros lygá?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'atidaryti';
$BL['be_func_struct_close']             = 'uþdaryti';
$BL['be_func_struct_empty']             = 'tuðèias';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'paprastas tekstas';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'programos kodas (source)';
$BL['be_ctype_textimage']               = 'tekstas su paveikslëliu';
$BL['be_ctype_images']                  = 'paveikslëliai';
$BL['be_ctype_bulletlist']              = 'sàraðas';
$BL['be_ctype_link']                    = 'nuorodos &amp; el. paðto adresai';
$BL['be_ctype_linklist']                = 'nuorodø sàraðas';
$BL['be_ctype_linkarticle']             = 'nuoroda á straipsná';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'failø sàraðas';
$BL['be_ctype_emailform']               = 'el. paðto forma';
$BL['be_ctype_newsletter']              = 'naujienlaiðkis';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profilis sëkmingai sukurtas.';
$BL['be_profile_create_error']          = 'Sukuriant ávyko klaida.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profilio duomenys sëkmingai atnaujinti.';
$BL['be_profile_update_error']          = 'Atnaujinant ávyko klaida.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'vartotoja vardas {VAL} netinka';
$BL['be_profile_account_err2']          = 'slaptaþodis per trumtas (tik {VAL} simboliai: maþiausiai reikia 5-kiø)';
$BL['be_profile_account_err3']          = 'pakartoti reikia toká patá slaptaþodá';
$BL['be_profile_account_err4']          = 'el. paðto adresas {VAL} neteisingas';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'jûsø asmeniniai duomenys';
$BL['be_profile_data_text']             = 'asmeniniai duomenys ávedami pasirinktinai. Jie kitiems vartotojams arba lankytojams leis daugiau suþinoti apie jus, jûsø interesus ir sugebëjimus. Jei paþymësite atitinkamus punktus varnelëmis, kiti vartotojai galës matyti jûsø duomenis vieðoje srityje arba straipsniø puslapiuose.';
$BL['be_profile_label_title']           = 'titulas';
$BL['be_profile_label_firstname']       = 'vardas';
$BL['be_profile_label_name']            = 'pavardë';
$BL['be_profile_label_company']         = 'kompanija';
$BL['be_profile_label_street']          = 'gatvë';
$BL['be_profile_label_city']            = 'miestas';
$BL['be_profile_label_state']           = 'provincija, valstija';
$BL['be_profile_label_zip']             = 'paðto kodas';
$BL['be_profile_label_country']         = 'ðalis';
$BL['be_profile_label_phone']           = 'telefonas';
$BL['be_profile_label_fax']             = 'faksas';
$BL['be_profile_label_cellphone']       = 'mobilus telefonas';
$BL['be_profile_label_signature']       = 'paraðas';
$BL['be_profile_label_notes']           = 'pastabos';
$BL['be_profile_label_profession']      = 'profesija';
$BL['be_profile_label_newsletter']      = 'naujienlaiðkis';
$BL['be_profile_text_newsletter']       = 'Að noriu gauti bendrà phpwcms naujienlaiðká.';
$BL['be_profile_label_public']          = 'vieðai matoma';
$BL['be_profile_text_public']           = 'Bet kas gali pasiþiûrëti mano duomenis.';
$BL['be_profile_label_button']          = 'atnaujinti asmeninius duomenis';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'jûsø prisijungimo informacija';
$BL['be_profile_account_text']          = 'Paprastai nereikia keisti vartotojo vardo. <br />Saugimo sumetimais kartas nuo karto pakeiskite savo slaptaþodá.';
$BL['be_profile_label_err']             = 'reikia paþymëti (patikrinti?)';
$BL['be_profile_label_username']        = 'vartotojo vardas';
$BL['be_profile_label_newpass']         = 'naujas slaptaþodis';
$BL['be_profile_label_repeatpass']      = 'pakartokite naujà slaptaþodá';
$BL['be_profile_label_email']           = 'el. paðto adresas';
$BL['be_profile_account_button']        = 'atjaujinti prisijungimo duomenis';
$BL['be_profile_label_lang']            = 'kalba';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'perimti failus atsiøstus per ftp';
$BL['be_ftptakeover_mark']              = 'mark';
$BL['be_ftptakeover_available']         = 'esantys failai';
$BL['be_ftptakeover_size']              = 'dydis';
$BL['be_ftptakeover_nofile']            = 'failø nëra &#8211; atsiøskite juos per ftp';
$BL['be_ftptakeover_all']               = 'VISUS';
$BL['be_ftptakeover_directory']         = 'direktorija';
$BL['be_ftptakeover_rootdir']           = 'pagrindinë direktorija';
$BL['be_ftptakeover_needed']            = 'reikalinga!!! (turite paþymëti vienà)';
$BL['be_ftptakeover_optional']          = 'pasirinktinai';
$BL['be_ftptakeover_keywords']          = 'raktiniai þodþiai';
$BL['be_ftptakeover_additional']        = 'papildomai';
$BL['be_ftptakeover_longinfo']          = 'ilgas apraðymas';
$BL['be_ftptakeover_status']            = 'statusas';
$BL['be_ftptakeover_active']            = 'aktyvus';
$BL['be_ftptakeover_public']            = 'vieðas';
$BL['be_ftptakeover_createthumb']       = 'sukurti perþiûros paveiksliukà';
$BL['be_ftptakeover_button']            = 'perimti paþymëtus failus';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'failø centras';
$BL['be_ftab_createnew']                = 'sukurti naujà direktorijà pagrindinëje direktorijoje';
$BL['be_ftab_paste']                    = 'nukopijuoti atmintyje esantá failà á pagrindinæ direktorijà';
$BL['be_ftab_disablethumb']             = 'sàraðe nerodyti perþiûros paveiksliukø';
$BL['be_ftab_enablethumb']              = 'sàraðe rodyti perþiûros paveiksliukus';
$BL['be_ftab_private']                  = 'privatûs&nbsp;failai';
$BL['be_ftab_public']                   = 'vieði&nbsp;failai';
$BL['be_ftab_search']                   = 'paieðka';
$BL['be_ftab_trash']                    = 'ðiukðliø&nbsp;dëþë';
$BL['be_ftab_open']                     = 'atidaryti visas direktorijas';
$BL['be_ftab_close']                    = 'uþdaryti visas atidarytas direktorijas';
$BL['be_ftab_upload']                   = 'uþkrauti failà á pagrindinæ direktorijà';
$BL['be_ftab_filehelp']                 = 'atidaryti failø pagalbà';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'pagrindinë direktorija';
$BL['be_fpriv_title']                   = 'sukurti naujà direktorijà';
$BL['be_fpriv_inside']                  = 'viduje';
$BL['be_fpriv_error']                   = 'klaida: nurodykite direktorijos vardà';
$BL['be_fpriv_name']                    = 'pavadinimas';
$BL['be_fpriv_status']                  = 'statusas';
$BL['be_fpriv_button']                  = 'sukurti naujà direktorijà';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'redaguoti direktorijà';
$BL['be_fpriv_newname']                 = 'naujas pavadinimas';
$BL['be_fpriv_updatebutton']            = 'atnaujinti direktorijos informacijà';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Pasirinkite failà, kurá norite uþkrauti';
$BL['be_fprivup_err2']                  = 'Uþkrauto failo dydis yra didesnis negu';
$BL['be_fprivup_err3']                  = 'Klaida raðant failà á saugyklà';
$BL['be_fprivup_err4']                  = 'Klaida kuriant vartotojo direktorijà.';
$BL['be_fprivup_err5']                  = 'perþiûros paveikslëlio nëra';
$BL['be_fprivup_err6']                  = 'Praðome daugiau nebandyti - tai serverio klaida! Susisiekite su savo <a href="mailto:{VAL}">webmasteriu</a> kaip galima greièiau!';
$BL['be_fprivup_title']                 = 'uþkrauti failus';
$BL['be_fprivup_button']                = 'uþkrauti failus';
$BL['be_fprivup_upload']                = 'uþkrauti';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'redaguoti failo apraðymà';
$BL['be_fprivedit_filename']            = 'failo pavadinimas';
$BL['be_fprivedit_created']             = 'sukurtas';
$BL['be_fprivedit_dateformat']          = 'Y-m-d H:i';
$BL['be_fprivedit_err1']                = 'proof name of file (atstatyti pradiná pavadinimà)';
$BL['be_fprivedit_clockwise']           = 'pasukti perþiûros paveikslëlá pagal laikrodþio rodyklæ [pradinis failas +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'pasukti perþiûros paveikslëlá prieð laikrodþio rodyklæ [pradinis failas -90&deg;]';
$BL['be_fprivedit_button']              = 'atnaujinti failo apraðymà';
$BL['be_fprivedit_size']                = 'dydis';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'uþkrauti failà á direktorijà';
$BL['be_fprivfunc_makenew']             = 'viduje sukurti naujà direktorijà';
$BL['be_fprivfunc_paste']               = 'nukopijuoti failà ið atminties á direktorijà';
$BL['be_fprivfunc_edit']                = 'redaguoti direktorijà';
$BL['be_fprivfunc_cactive']             = 'perjungti á aktyvià/neaktyvià';
$BL['be_fprivfunc_cpublic']             = 'perjungti á vieðà/nevieðà';
$BL['be_fprivfunc_deldir']              = 'Iðtrinti direktorijà';
$BL['be_fprivfunc_jsdeldir']            = 'Ar tikrai norite \niðtrinti direktorijà?';
$BL['be_fprivfunc_notempty']            = 'direktorija {VAL} nëra tuðèia!';
$BL['be_fprivfunc_opendir']             = 'atidaryti direktorijà';
$BL['be_fprivfunc_closedir']            = 'uþdaryti direktorijà';
$BL['be_fprivfunc_dlfile']              = 'atisiøsti failà';
$BL['be_fprivfunc_clipfile']            = 'atmintyje esantis failas';
$BL['be_fprivfunc_cutfile']             = 'iðkirpti';
$BL['be_fprivfunc_editfile']            = 'redaguoti failo apraðymà';
$BL['be_fprivfunc_cactivefile']         = 'perjungti á aktyvø/neaktyvø';
$BL['be_fprivfunc_cpublicfile']         = 'perjungti á vieðà/nevieðà';
$BL['be_fprivfunc_movetrash']           = 'iðmesti á ðiukðliø dëþæ';
$BL['be_fprivfunc_jsmovetrash1']        = 'Ar tikrai norite iðmesti';
$BL['be_fprivfunc_jsmovetrash2']        = 'á ðiukðiø dëþæ?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'nëra privaèiø failø ir direktorijø';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'vartotojas';
$BL['be_fpublic_nofiles']               = 'nëra vieðø failø ir direktorijø';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'ðiukðiø dëþë yra tuðèia';
$BL['be_ftrash_show']                   = 'rodyti privaèius failus';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Ar norite atkurti {VAL} \nir gràþinti á privatø sàraðà?';
$BL['be_ftrash_delete']                 = 'Ar norite iðtrinti {VAL}?';
$BL['be_ftrash_undo']                   = 'atstatyti (gràþinti ið ðiukðliadëþës)';
$BL['be_ftrash_delfinal']               = 'galutinis iðtrynimas';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'tuðèia paieðkos uþklausa.';
$BL['be_fsearch_title']                 = 'ieðkoti failø';
$BL['be_fsearch_infotext']              = 'Èia galima atlikti paprastà failø paieðkà. Ieðkoma atitinkamø raktiniø þodþiø,<br />failø pavadinimø ir iðsamiuose faulø apraðymuose. Simbolio '*' naudoti negalima. Þodþius atskirkite tarpais. <br /> Pasirinkite IR/ARBA ir kokiø failø ieðkoti: asmeniniø/vieðai prieinamø.';
$BL['be_fsearch_nonfound']              = 'pagal jûsø uþklausta failø nerasta. patikslinkite uþklausà!';
$BL['be_fsearch_fillin']                = 'malonëkite áraðyti uþklausà á virðuje esantá langelá.';
$BL['be_fsearch_searchlabel']           = 'ieðkoti';
$BL['be_fsearch_startsearch']           = 'pradëti paieðkà';
$BL['be_fsearch_and']                   = 'IR';
$BL['be_fsearch_or']                    = 'ARBA';
$BL['be_fsearch_all']                   = 'visi failai';
$BL['be_fsearch_personal']              = 'privatûs';
$BL['be_fsearch_public']                = 'vieðai prieinami';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'vidiniai pokalbiai';
$BL['be_chat_info']                     = 'Èia galite kalbëtis su kitais phpwcms sistemos vartotojais. Ði terpë yra skirta ðnekëtis real-time, bet taip pat galite palikti þinutæ, kurià galës perskaityti kiti.';
$BL['be_chat_start']                    = 'norëdami pradëti kalbëtis, paspauskite èia';
$BL['be_chat_lines']                    = 'pokalbio eilutës';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'þinuèiø centras';
$BL['be_msg_new']                       = 'nauja';
$BL['be_msg_old']                       = 'sena';
$BL['be_msg_senttop']                   = 'iðsiøstos';
$BL['be_msg_del']                       = 'iðtrintos';
$BL['be_msg_from']                      = 'nuo';
$BL['be_msg_subject']                   = 'tema';
$BL['be_msg_date']                      = 'data/laikas';
$BL['be_msg_close']                     = 'uþdaryti þinutæ';
$BL['be_msg_create']                    = 'sukurti naujà þinutæ';
$BL['be_msg_reply']                     = 'atsakyti á ðià þinutæ';
$BL['be_msg_move']                      = 'iðmesti ðià þinutæ á ðiukðliø dëþæ';
$BL['be_msg_unread']                    = 'neperskaitytos arba naujos þinutës';
$BL['be_msg_lastread']                  = 'paskutinës {VAL} perskaitytos þinutës';
$BL['be_msg_lastsent']                  = 'paskutinës {VAL} iðsiøstos þinutës';
$BL['be_msg_marked']                    = 'þinutës paþymëtos iðmetimui á ðiukðliø dëþæ';
$BL['be_msg_nomsg']                     = 'ðiame aplanke þinuèiø nëra';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'ATS';
$BL['be_msg_by']                        = 'atsiøsta nuo';
$BL['be_msg_on']                        = 'on';
$BL['be_msg_msg']                       = 'þinutë';
$BL['be_msg_err1']                      = 'pamirðote nurodyti gavëjà...';
$BL['be_msg_err2']                      = 'uþpildykite temos laukelá (gavëjui bus aiðkiau)';
$BL['be_msg_err3']                      = 'nëra prasmës siøsti þinutës be teksto ;-)';
$BL['be_msg_sent']                      = 'þinutë iðsiøsta.';
$BL['be_msg_fwd']                       = 'jûs bûsite perkelti á þinuèiø centrà arba';
$BL['be_msg_newmsgtitle']               = 'raðykite naujà þinutæ';
$BL['be_msg_err']                       = 'klaida siunèiant þinutæ';
$BL['be_msg_sendto']                    = 'kam siøsti þinutæ';
$BL['be_msg_available']                 = 'galimø adresatø sàraðas';
$BL['be_msg_all']                       = 'siøsti þinutæ visiems paþymëtiems adresatams';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'naujienlaiðkio uþsakymai';
$BL['be_newsletter_titleedit']          = 'redaguoti naujienlaiðkio uþsakymà';
$BL['be_newsletter_new']                = 'sukurti naujà';
$BL['be_newsletter_add']                = 'pridëti&nbsp;naujienlaiðkio&nbsp;uþsakymà';
$BL['be_newsletter_name']               = 'vardas';
$BL['be_newsletter_info']               = 'informacija';
$BL['be_newsletter_button_save']        = 'áraðyti uþsakymà';
$BL['be_newsletter_button_cancel']      = 'atðaukti';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'netinkamas vartotojo vardas, áveskite naujà ';
$BL['be_admin_usr_err2']                = 'neávestas vartotojo vardas (bûtinas)';
$BL['be_admin_usr_err3']                = 'neávestas slaptaþodis (bûtinas)';
$BL['be_admin_usr_err4']                = "negaliojantis el. paðto adresas";
$BL['be_admin_usr_err']                 = 'klaida';
$BL['be_admin_usr_mailsubject']         = 'sveiki atvykæ á tinklapio administravimà';
$BL['be_admin_usr_mailbody']            = "TINKLAPIO ADMINISTRAVIMO SISTEMA\n\n    vartotojo vardas: {LOGIN}\n    slapradþodis: {PASSWORD}\n\n\nGalite prisijungti èia: {SITE}\n\nsistemos administratorius\n ";
$BL['be_admin_usr_title']               = 'pridëti naujà vartotojà';
$BL['be_admin_usr_realname']            = 'vardas ir pavardë';
$BL['be_admin_usr_setactive']           = 'aktyvuoti vartotojà';
$BL['be_admin_usr_iflogin']             = 'jei èia paþymëta, vartotojas gali prisijungti';
$BL['be_admin_usr_isadmin']             = 'vartotojas turi administratoriaus teises';
$BL['be_admin_usr_ifadmin']             = 'jei paþymëta, vartotojas turi visas valdymo teises';
$BL['be_admin_usr_verify']              = 'patikrinimas';
$BL['be_admin_usr_sendemail']           = 'nusiøsti naujam vartotojui el. laiðkà su jo prisijungimo duomenimis';
$BL['be_admin_usr_button']              = 'áraðyti vartotojo duomenis';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'redaguoti vartotojo duomenis';
$BL['be_admin_usr_emailsubject']        = 'duomenys pakeisti';
$BL['be_admin_usr_emailbody']           = "VARTOTOJO INFORMACIJA PAKEISTA\n\n    vartotojo vardas: {LOGIN}\n    slaptaþodis: {PASSWORD}\n\n\nPrisijungti galite èia: {SITE}\n\nsistemos administratorius\n ";
$BL['be_admin_usr_passnochange']        = '[NAUDOKITE SAVO SLAPTAÞODÁ]';
$BL['be_admin_usr_ebutton']             = 'atnaujinti vartotojo duomenis';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'sistemos vartotojø sàraðas';
$BL['be_admin_usr_ldel']                = 'DËMESIO!&#13Ðitas mygtukas iðtrina vartotojà';
$BL['be_admin_usr_create']              = 'sukurti naujà vartotojà';
$BL['be_admin_usr_editusr']             = 'redaguoti vartotojà';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'tinklapio struktûra';
$BL['be_admin_struct_child']            = '(priklauso)';
$BL['be_admin_struct_index']            = 'indeksas (tinklapio pradþia)';
$BL['be_admin_struct_cat']              = 'kategorijos pavadinimas';
$BL['be_admin_struct_status']           = 'meniu statusas';
$BL['be_admin_struct_hide1']            = 'paslëpti';
$BL['be_admin_struct_hide2']            = 'ði&nbsp;kategorija&nbsp;yra&nbsp;meniu';
$BL['be_admin_struct_info']             = 'categorijos infotekstas';
$BL['be_admin_struct_template']         = 'ðablonas';
$BL['be_admin_struct_alias']            = 'trumpas ðios kategorijos pavadinimas';
$BL['be_admin_struct_visible']          = 'matoma';
$BL['be_admin_struct_button']           = 'áraðyti kategorijos duomenis';
$BL['be_admin_struct_close']            = 'uþdaryti';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'failø kategorijos';
$BL['be_admin_fcat_err']                = 'neávestas kategorijos pavadinimas!';
$BL['be_admin_fcat_name']               = 'kategorijos pavadinimas';
$BL['be_admin_fcat_needed']             = 'reikalinga';
$BL['be_admin_fcat_button1']            = 'atnaujinti';
$BL['be_admin_fcat_button2']            = 'sukurti';
$BL['be_admin_fcat_delmsg']             = 'Ar tikrai norite\niðtrinti failo raktà?';
$BL['be_admin_fcat_fcat']               = 'failo kategorija';
$BL['be_admin_fcat_err1']               = 'nenurodytas failo rakto pavadinimas!';
$BL['be_admin_fcat_fkeyname']           = 'failo rakto pavadinimas';
$BL['be_admin_fcat_exit']               = 'iðeiti ið redagavimo';
$BL['be_admin_fcat_addkey']             = 'pridëti naujà raktà';
$BL['be_admin_fcat_editcat']            = 'redaguoti kategorijos pavadinimà';
$BL['be_admin_fcat_delcatmsg']          = 'Ar tikrai norite \niðtrinti failo kategorijà?';
$BL['be_admin_fcat_delcat']             = 'iðtrinti failo kategorijà';
$BL['be_admin_fcat_delkey']             = 'iðtrinti failo rakto pavadinimà';
$BL['be_admin_fcat_editkey']            = 'redaguoti raktà';
$BL['be_admin_fcat_addcat']             = 'sukurti naujà failø kategorijà';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'tinklapio nustatymai: puslapio maketas';
$BL['be_admin_page_align']              = 'puslapio lygiavimas';
$BL['be_admin_page_align_left']         = 'standartinis viso puslapio turinio lygiavimas (kairëje)';
$BL['be_admin_page_align_center']       = 'centruoti visà puslapio turiná';
$BL['be_admin_page_align_right']        = 'viso puslapio turinys deðinëje';
$BL['be_admin_page_margin']             = 'pakraðèiai';
$BL['be_admin_page_top']                = 'virðus';
$BL['be_admin_page_bottom']             = 'apaèia';
$BL['be_admin_page_left']               = 'kairë';
$BL['be_admin_page_right']              = 'deðinë';
$BL['be_admin_page_bg']                 = 'fonas';
$BL['be_admin_page_color']              = 'spalva';
$BL['be_admin_page_height']             = 'aukðtis';
$BL['be_admin_page_width']              = 'plotis';
$BL['be_admin_page_main']               = 'pagrindinë dalis';
$BL['be_admin_page_leftspace']          = 'kairës atskyrimas';
$BL['be_admin_page_rightspace']         = 'deðinës atskyrimas';
$BL['be_admin_page_class']              = 'klasë';
$BL['be_admin_page_image']              = 'paveikslëlis';
$BL['be_admin_page_text']               = 'tekstas';
$BL['be_admin_page_link']               = 'nuoroda';
$BL['be_admin_page_js']                 = 'javascriptas';
$BL['be_admin_page_visited']            = 'aplankyta nuoroda';
$BL['be_admin_page_pagetitle']          = 'puslapio&nbsp;antraðtë';
$BL['be_admin_page_addtotitle']         = 'pridëti&nbsp;prie&nbsp;antraðtës';
$BL['be_admin_page_category']           = 'kategorija';
$BL['be_admin_page_articlename']        = 'straipsnio&nbsp;pavadinimas';
$BL['be_admin_page_blocks']             = 'blokai';
$BL['be_admin_page_allblocks']          = 'visi blokai';
$BL['be_admin_page_col1']               = '3 stulpeliø maketas';
$BL['be_admin_page_col2']               = '2 stulpeliø maketas (turinys deðinëje, navigacija kairëje)';
$BL['be_admin_page_col3']               = '2 stulpeliø maketas (turinys kairëje, navigacija deðinëje)';
$BL['be_admin_page_col4']               = '1 stulpelio maketas';
$BL['be_admin_page_header']             = 'virðus';
$BL['be_admin_page_footer']             = 'apaèia';
$BL['be_admin_page_topspace']           = 'virðaus&nbsp;atskyrimas';
$BL['be_admin_page_bottomspace']        = 'apaèios&nbsp;atskyrimas';
$BL['be_admin_page_button']             = 'iðsaugoti puslapio maketà';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'tinklapio nustatymai: css duomenys';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'iðsaugoti css duomenis';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'tinklapio nustatymai: ðablonai';
$BL['be_admin_tmpl_default']            = 'pagal nutylëjimà';
$BL['be_admin_tmpl_add']                = 'pridëti&nbsp;ðablonà';
$BL['be_admin_tmpl_edit']               = 'redaguoti ðablonà';
$BL['be_admin_tmpl_new']                = 'sukurti naujà';
$BL['be_admin_tmpl_css']                = 'css failas';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'kai&nbsp;puslapis&nbsp;tuðèias';
$BL['be_admin_tmpl_button']             = 'iðsaugoti ðablonà';
$BL['be_admin_tmpl_name']               = 'pavadinimas';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'tinklapio struktûra ir straipsniø sàraðas';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'neávestas straipsnio pavadinimas';
$BL['be_article_err2']                  = 'buvo nurodyta klaidinga pradþios data - nustatyta dabartinë data';
$BL['be_article_err3']                  = 'buvo nurodyta klaidinga pabaigos data - nustatyta dabartinë data';
$BL['be_article_title1']                = 'pagrindinë straipsnio informacija';
$BL['be_article_cat']                   = 'kategorija';
$BL['be_article_atitle']                = 'straipsnio pavadinimas';
$BL['be_article_asubtitle']             = 'paantraðtë';
$BL['be_article_abegin']                = 'rodymo pradþia';
$BL['be_article_aend']                  = 'rodymo pabaiga';
$BL['be_article_aredirect']             = 'nukreipti á';
$BL['be_article_akeywords']             = 'raktiniai þodþiai';
$BL['be_article_asummary']              = 'santrauka';
$BL['be_article_abutton']               = 'sukurti naujà straipsná';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'pabaigos data buvo kladinga - nustatyta dabartinë data + 1 savaitë';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'redaguoti pagrindinæ straipsnio informacijà';
$BL['be_article_eslastedit']            = 'paskutinis redagavimas';
$BL['be_article_esnoupdate']            = 'forma neatnaujinta';
$BL['be_article_esbutton']              = 'atnaujinti straipsnio duomenis';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'straipsnio turinys';
$BL['be_article_cnt_type']              = 'turinio tipas';
$BL['be_article_cnt_space']             = 'tarpas';
$BL['be_article_cnt_before']            = 'prieð';
$BL['be_article_cnt_after']             = 'po';
$BL['be_article_cnt_top']               = 'top';
$BL['be_article_cnt_ctitle']            = 'pavadinimas';
$BL['be_article_cnt_back']              = 'visa straipsnio informacija';
$BL['be_article_cnt_button1']           = 'atnaujinti turiná';
$BL['be_article_cnt_button2']           = 'sukurti turiná';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'straipsnio informacija';
$BL['be_article_cnt_ledit']             = 'redaguoti straipsná';
$BL['be_article_cnt_lvisible']          = 'perjungti á rodomà/nerodomà';
$BL['be_article_cnt_ldel']              = 'iðtrinti ðá straipsná';
$BL['be_article_cnt_ldeljs']            = 'Iðtrinti straipsná?';
$BL['be_article_cnt_redirect']          = 'peradresavimas';
$BL['be_article_cnt_edited']            = 'redagavo';
$BL['be_article_cnt_start']             = 'rodymo pradþios data';
$BL['be_article_cnt_end']               = 'rodymo pabaigos data';
$BL['be_article_cnt_add']               = 'pridëti naujà turinio elementà';
$BL['be_article_cnt_up']                = 'perkelti turiná aukðtyn';
$BL['be_article_cnt_down']              = 'perkelti turiná aukðtyn';
$BL['be_article_cnt_edit']              = 'redaguoti turinio elementà';
$BL['be_article_cnt_delpart']           = 'iðtrinti ðá turinio elementà';
$BL['be_article_cnt_delpartjs']         = 'Iðtrinti turinio elementà?';
$BL['be_article_cnt_center']            = 'straipsniø centras';

// content forms
$BL['be_cnt_plaintext']                 = 'paprastas tekstas';
$BL['be_cnt_htmltext']                  = 'html tekstas';
$BL['be_cnt_image']                     = 'paveikslëlis';
$BL['be_cnt_position']                  = 'pozicija';
$BL['be_cnt_pos0']                      = 'Virð, keirëje';
$BL['be_cnt_pos1']                      = 'Virð, per vidurá';
$BL['be_cnt_pos2']                      = 'Virð, deðinëje';
$BL['be_cnt_pos3']                      = 'Apaèioje, kairëje';
$BL['be_cnt_pos4']                      = 'Apaèioje, per vidurá';
$BL['be_cnt_pos5']                      = 'Apaèioje, deðinëje';
$BL['be_cnt_pos6']                      = 'Tekste, kairëje';
$BL['be_cnt_pos7']                      = 'Tekste, deðinëje';
$BL['be_cnt_pos0i']                     = 'Paveikslëlis virð teksto kairëje';
$BL['be_cnt_pos1i']                     = 'Paveikslëlis virð teksto per vidurá';
$BL['be_cnt_pos2i']                     = 'Paveikslëlis virð teksto deðinëje';
$BL['be_cnt_pos3i']                     = 'Paveikslëlis po tekstu kairëje';
$BL['be_cnt_pos4i']                     = 'Paveikslëlis po tekstu per vidurá';
$BL['be_cnt_pos5i']                     = 'Paveikslëlis po tekstu deðinëje';
$BL['be_cnt_pos6i']                     = 'Paveikslëlis tekste kairëje';
$BL['be_cnt_pos7i']                     = 'Paveikslëlis tekste deðinëje';
$BL['be_cnt_maxw']                      = 'maks.&nbsp;plotis';
$BL['be_cnt_maxh']                      = 'maks.&nbsp;aukðtis';
$BL['be_cnt_enlarge']                   = 'paspaudus&nbsp;padidinti';
$BL['be_cnt_caption']                   = 'pavadinimas';
$BL['be_cnt_subject']                   = 'tema';
$BL['be_cnt_recipient']                 = 'gavëjas';
$BL['be_cnt_buttontext']                = 'mygtuko tekstas';
$BL['be_cnt_sendas']                    = 'siøsti kaip';
$BL['be_cnt_text']                      = 'tekstà';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'formos laukai';
$BL['be_cnt_code']                      = 'kodas';
$BL['be_cnt_infotext']                  = 'apraðymas';
$BL['be_cnt_subscription']              = 'galimos&nbsp;prenumeratos';
$BL['be_cnt_labelemail']                = 'el. paðto&nbsp;laukas';
$BL['be_cnt_tablealign']                = 'lentelës&nbsp;lygiavimas';
$BL['be_cnt_labelname']                 = 'vardo&nbsp;laukas';
$BL['be_cnt_labelsubsc']                = 'prenumeratos&nbsp;laukas';
$BL['be_cnt_allsubsc']                  = 'visos&nbsp;prenum.';
$BL['be_cnt_default']                   = 'pagal nutylëjimà';
$BL['be_cnt_left']                      = 'kairëje';
$BL['be_cnt_center']                    = 'per vidurá';
$BL['be_cnt_right']                     = 'deðineje';
$BL['be_cnt_buttontext']                = 'mygtuko&nbsp;tekstas';
$BL['be_cnt_successtext']               = 'tektas&nbsp;po&nbsp;registr.';
$BL['be_cnt_regmail']                   = 'registravimo&nbsp;el. paðto adresas';
$BL['be_cnt_logoffmail']                = 'iðregistravimo&nbsp;el. paðto adresas';
$BL['be_cnt_changemail']                = 'pakeitimo&nbsp;el. paðto adresas';
$BL['be_cnt_openimagebrowser']          = 'atidaryti paveikslëliø narðyklæ';
$BL['be_cnt_openfilebrowser']           = 'atidaryti failø narðyklæ';
$BL['be_cnt_sortup']                    = 'aukðtyn';
$BL['be_cnt_sortdown']                  = 'þemyn';
$BL['be_cnt_delimage']                  = 'paðalinti pasirinktà paveikslëlá';
$BL['be_cnt_delfile']                   = 'paðalinti pasirinktà failà';
$BL['be_cnt_delmedia']                  = 'paðalinti pasirinktà objektà';
$BL['be_cnt_column']                    = 'stulpelis';
$BL['be_cnt_imagespace']                = 'paveikslëlio&nbsp;erdvë';
$BL['be_cnt_directlink']                = 'tiesioginë nuoroda';
$BL['be_cnt_target']                    = 'atidaryti';
$BL['be_cnt_target1']                   = 'naujame lange';
$BL['be_cnt_target2']                   = 'in parent frame of the window';
$BL['be_cnt_target3']                   = 'tame paèiame lange be rëmø';
$BL['be_cnt_target4']                   = 'tame paèiame rëme arba lange';
$BL['be_cnt_bullet']                    = 'bullet list';
$BL['be_cnt_linklist']                  = 'nuorodø sàraðas';
$BL['be_cnt_plainhtml']                 = 'paprastas html';
$BL['be_cnt_files']                     = 'failai';
$BL['be_cnt_description']               = 'apraðymas';
$BL['be_cnt_linkarticle']               = 'nuoroda á straipsná';
$BL['be_cnt_articles']                  = 'straipsniai';
$BL['be_cnt_movearticleto']             = 'perkelti pasirinktà straipsná á straipsniø sàraðà';
$BL['be_cnt_removearticleto']           = 'iðimti pasirinktà straipsná ið straipsniø sàraðo';
$BL['be_cnt_mediatype']                 = 'media tipas';
$BL['be_cnt_control']                   = 'kontrolë';
$BL['be_cnt_showcontrol']               = 'radyti kontrolës juostà';
$BL['be_cnt_autoplay']                  = 'paleisti automatiðkai';
$BL['be_cnt_source']                    = 'kodas';
$BL['be_cnt_internal']                  = 'vidinis';
$BL['be_cnt_openmediabrowser']          = 'atidaryti media objektø narðyklæ';
$BL['be_cnt_external']                  = 'iðorinis';
$BL['be_cnt_mediapos0']                 = 'kairëje (pagal nutylëjimà)';
$BL['be_cnt_mediapos1']                 = 'per vidurá';
$BL['be_cnt_mediapos2']                 = 'deðineje';
$BL['be_cnt_mediapos3']                 = 'blokas, kairëje';
$BL['be_cnt_mediapos4']                 = 'blokas, deðinëje';
$BL['be_cnt_mediapos0i']                = 'media virð teksto kairëje';
$BL['be_cnt_mediapos1i']                = 'media virð teksto per vidurá';
$BL['be_cnt_mediapos2i']                = 'media virð teksto deðinëje';
$BL['be_cnt_mediapos3i']                = 'media tekste kairëje';
$BL['be_cnt_mediapos4i']                = 'media tekste deðinëje';
$BL['be_cnt_setsize']                   = 'nustatyti dydá';
$BL['be_cnt_set1']                      = 'media uþims 160x120px';
$BL['be_cnt_set2']                      = 'media uþims 240x180px ';
$BL['be_cnt_set3']                      = 'media uþims 320x240px';
$BL['be_cnt_set4']                      = 'media uþims 480x360px';
$BL['be_cnt_set5']                      = 'iðvalyti media aukðèio ir ploèio parametrus';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'sukurti naujà puslapio maketà';
$BL['be_admin_page_name']               = 'maketo pavadinimas';
$BL['be_admin_page_edit']               = 'redaguoti maketà';
$BL['be_admin_page_render']             = 'atvaizdavimas';
$BL['be_admin_page_table']              = 'lentelë';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'kitas';
$BL['be_admin_page_custominfo']         = 'ið ðablono pagrindinio bloko';
$BL['be_admin_tmpl_layout']             = 'maketas';
$BL['be_admin_tmpl_nolayout']           = 'Nëra puslapio maketo!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'paieðka';
$BL['be_cnt_results']                   = 'rezultatai';
$BL['be_cnt_results_per_page']          = 'per&nbsp;puslapá (jei nepaþymëta, rodyti viskà)';
$BL['be_cnt_opennewwin']                = 'atidaryti naujà langà';
$BL['be_cnt_searchlabeltext']           = 'tai yra ið anksto nustatyti tekstai ir parametrai paieðkos formai ir rezultatø puslapiui, tekstai yra rodomi kuomet rezultatai netelpa á vienà puslapá.';
$BL['be_cnt_input']                     = 'ávedimas';
$BL['be_cnt_style']                     = 'stilius';
$BL['be_cnt_result']                    = 'rezultatai';
$BL['be_cnt_next']                      = 'kitas';
$BL['be_cnt_previous']                  = 'prieð tai buvæs';
$BL['be_cnt_align']                     = 'lygiuoti';
$BL['be_cnt_searchformtext']            = 'þemiau esantys tekstai yra rodomi atidarius paieðkos formà arba kai paieðka neduoda rezultato.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'kai nëra rezultatø';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'deaktyvuoti';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'straipsnio savininkas';
$BL['be_article_adminuser']             = 'vartotojas administratorius';
$BL['be_article_username']              = 'autorius';

// added: 10-01-2004
$BL['be_ctype_wysywig']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible for users logged on only';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']				= 'article menu';
$BL['be_cnt_sitelevel']					= 'site level';
$BL['be_cnt_sitecurrent']				= 'current site level';

?>