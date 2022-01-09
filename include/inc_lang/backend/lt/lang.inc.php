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


// Language: Lithuanian, Language Code: lt
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'prisijungę vartotojai';

// Login Page
$BL["login_text"]                       = 'Įveskite prisijungimo duomenis';
$BL['login_error']                      = 'Prisijungimo klaidos!';
$BL["login_username"]                   = 'vardas';
$BL["login_userpass"]                   = 'slaptažodis';
$BL["login_button"]                     = 'Prisijungti';
$BL["login_lang"]                       = 'administravimo kalba';

// phpwcms.php
$BL['be_nav_logout']                    = 'ATSIJUNGTI';
$BL['be_nav_articles']                  = 'TURINYS';
$BL['be_nav_files']                     = 'FAILAI';
$BL['be_nav_modules']                   = 'MODULIAI';
$BL['be_nav_messages']                  = 'ŽINUTĖS';
$BL['be_nav_chat']                      = 'POKALBIAI';
$BL['be_nav_profile']                   = 'PROFILIS';
$BL['be_nav_admin']                     = 'ADMINISTRAVIMAS';
$BL['be_nav_discuss']                   = 'DISKUSIJOS';

$BL['be_page_title']                    = 'phpwcms administravimas';

$BL['be_subnav_article_center']         = 'straipsnių centras';
$BL['be_subnav_article_new']            = 'naujas straipsnis';
$BL['be_subnav_file_center']            = 'failų centras';
$BL['be_subnav_file_ftptakeover']       = 'ftp perėmimas';
$BL['be_subnav_mod_artists']            = 'atlikėjas, kategojis, žanras';
$BL['be_subnav_msg_center']             = 'žinučių centras';
$BL['be_subnav_msg_new']                = 'nauja žinutė';
$BL['be_subnav_msg_newsletter']         = 'naujienlaiškių prenumerata';
$BL['be_subnav_chat_main']              = 'pagrindinis pokalbių puslapis';
$BL['be_subnav_chat_internal']          = 'vidiniai pokalbiai';
$BL['be_subnav_profile_login']          = 'prisijungimo informacija';
$BL['be_subnav_profile_personal']       = 'asmeniniai duomenus';
$BL['be_subnav_admin_pagelayout']       = 'puslapio maketas';
$BL['be_subnav_admin_templates']        = 'šablonai';
$BL['be_subnav_admin_css']              = 'css pagal nutylėjimą';
$BL['be_subnav_admin_sitestructure']    = 'tinklapio struktūra';
$BL['be_subnav_admin_users']            = 'vartotojų valdymas';
$BL['be_subnav_admin_filecat']          = 'failų kategorijos';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'straipsnio ID';
$BL['be_func_struct_preview']           = 'peržiūra';
$BL['be_func_struct_edit']              = 'redaguoti straipsnį';
$BL['be_func_struct_sedit']             = 'redaguoti struktūros lygį';
$BL['be_func_struct_cut']               = 'iškirpti straipsnį';
$BL['be_func_struct_nocut']             = 'deaktyvuoti straipsnio iškirpimą';
$BL['be_func_struct_svisible']          = 'perjungti matomą/nematomą';
$BL['be_func_struct_spublic']           = 'perjungti viešas/ne viešas';
$BL['be_func_struct_sort_up']           = 'aukštyn';
$BL['be_func_struct_sort_down']         = 'žemyn';
$BL['be_func_struct_del_article']       = 'ištrinti straipsnį';
$BL['be_func_struct_del_jsmsg']         = 'Ar tikrai norite \ništrinti straipsnį?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'sukurti naują straipsnį šiame struktūros lygyje';
$BL['be_func_struct_paste_article']     = 'įklijuoti straipsnį šiame struktūros lygyje';
$BL['be_func_struct_insert_level']      = 'įterpti struktūros lygį';
$BL['be_func_struct_paste_level']       = 'įklijuoti struktūros lygį';
$BL['be_func_struct_cut_level']         = 'iškirpti struktūros lygį';
$BL['be_func_struct_no_cut']            = "Neįmanoma iškirpti pradinio lygio!";
$BL['be_func_struct_no_paste1']         = "Neįmanoma čia įklijuoti!";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'įklijuoti reikėtų čia';
$BL['be_func_struct_paste_cancel']      = 'cancel structure level change';
$BL['be_func_struct_del_struct']        = 'ištrinti struktūros lygį';
$BL['be_func_struct_del_sjsmsg']        = 'Ar tikrai norite ištrinti \nstruktūros lygį?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'atidaryti';
$BL['be_func_struct_close']             = 'uždaryti';
$BL['be_func_struct_empty']             = 'tuščias';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'paprastas tekstas';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'programos kodas (source)';
$BL['be_ctype_textimage']               = 'tekstas su paveikslėliu';
$BL['be_ctype_images']                  = 'paveikslėliai';
$BL['be_ctype_bulletlist']              = 'sąrašas';
$BL['be_ctype_link']                    = 'nuorodos &amp; el. pašto adresai';
$BL['be_ctype_linklist']                = 'nuorodų sąrašas';
$BL['be_ctype_linkarticle']             = 'nuoroda į straipsnį';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'failų sąrašas';
$BL['be_ctype_emailform']               = 'el. pašto forma';
$BL['be_ctype_newsletter']              = 'naujienlaiškis';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profilis sėkmingai sukurtas.';
$BL['be_profile_create_error']          = 'Sukuriant įvyko klaida.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profilio duomenys sėkmingai atnaujinti.';
$BL['be_profile_update_error']          = 'Atnaujinant įvyko klaida.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'vartotoja vardas {VAL} netinka';
$BL['be_profile_account_err2']          = 'slaptažodis per trumtas (tik {VAL} simboliai: mažiausiai reikia 5-kių)';
$BL['be_profile_account_err3']          = 'pakartoti reikia tokį patį slaptažodį';
$BL['be_profile_account_err4']          = 'el. pašto adresas {VAL} neteisingas';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'jūsų asmeniniai duomenys';
$BL['be_profile_data_text']             = 'asmeniniai duomenys įvedami pasirinktinai. Jie kitiems vartotojams arba lankytojams leis daugiau sužinoti apie jus, jūsų interesus ir sugebėjimus. Jei pažymėsite atitinkamus punktus varnelėmis, kiti vartotojai galės matyti jūsų duomenis viešoje srityje arba straipsnių puslapiuose.';
$BL['be_profile_label_title']           = 'titulas';
$BL['be_profile_label_firstname']       = 'vardas';
$BL['be_profile_label_name']            = 'pavardė';
$BL['be_profile_label_company']         = 'kompanija';
$BL['be_profile_label_street']          = 'gatvė';
$BL['be_profile_label_city']            = 'miestas';
$BL['be_profile_label_state']           = 'provincija, valstija';
$BL['be_profile_label_zip']             = 'pašto kodas';
$BL['be_profile_label_country']         = 'šalis';
$BL['be_profile_label_phone']           = 'telefonas';
$BL['be_profile_label_fax']             = 'faksas';
$BL['be_profile_label_cellphone']       = 'mobilus telefonas';
$BL['be_profile_label_signature']       = 'parašas';
$BL['be_profile_label_notes']           = 'pastabos';
$BL['be_profile_label_profession']      = 'profesija';
$BL['be_profile_label_newsletter']      = 'naujienlaiškis';
$BL['be_profile_text_newsletter']       = 'Aš noriu gauti bendrą phpwcms naujienlaiškį.';
$BL['be_profile_label_public']          = 'viešai matoma';
$BL['be_profile_text_public']           = 'Bet kas gali pasižiūrėti mano duomenis.';
$BL['be_profile_label_button']          = 'atnaujinti asmeninius duomenis';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'jūsų prisijungimo informacija';
$BL['be_profile_account_text']          = 'Paprastai nereikia keisti vartotojo vardo. <br />Saugimo sumetimais kartas nuo karto pakeiskite savo slaptažodį.';
$BL['be_profile_label_err']             = 'reikia pažymėti (patikrinti?)';
$BL['be_profile_label_username']        = 'vartotojo vardas';
$BL['be_profile_label_newpass']         = 'naujas slaptažodis';
$BL['be_profile_label_repeatpass']      = 'pakartokite naują slaptažodį';
$BL['be_profile_label_email']           = 'el. pašto adresas';
$BL['be_profile_account_button']        = 'atjaujinti prisijungimo duomenis';
$BL['be_profile_label_lang']            = 'kalba';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'perimti failus atsiųstus per ftp';
$BL['be_ftptakeover_mark']              = 'mark';
$BL['be_ftptakeover_available']         = 'esantys failai';
$BL['be_ftptakeover_size']              = 'dydis';
$BL['be_ftptakeover_nofile']            = 'failų nėra &#8211; atsiųskite juos per ftp';
$BL['be_ftptakeover_all']               = 'VISUS';
$BL['be_ftptakeover_directory']         = 'direktorija';
$BL['be_ftptakeover_rootdir']           = 'pagrindinė direktorija';
$BL['be_ftptakeover_needed']            = 'reikalinga!!! (turite pažymėti vieną)';
$BL['be_ftptakeover_optional']          = 'pasirinktinai';
$BL['be_ftptakeover_keywords']          = 'raktiniai žodžiai';
$BL['be_ftptakeover_additional']        = 'papildomai';
$BL['be_ftptakeover_longinfo']          = 'ilgas aprašymas';
$BL['be_ftptakeover_status']            = 'statusas';
$BL['be_ftptakeover_active']            = 'aktyvus';
$BL['be_ftptakeover_public']            = 'viešas';
$BL['be_ftptakeover_createthumb']       = 'sukurti peržiūros paveiksliuką';
$BL['be_ftptakeover_button']            = 'perimti pažymėtus failus';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'failų centras';
$BL['be_ftab_createnew']                = 'sukurti naują direktoriją pagrindinėje direktorijoje';
$BL['be_ftab_paste']                    = 'nukopijuoti atmintyje esantį failą į pagrindinę direktoriją';
$BL['be_ftab_disablethumb']             = 'sąraše nerodyti peržiūros paveiksliukų';
$BL['be_ftab_enablethumb']              = 'sąraše rodyti peržiūros paveiksliukus';
$BL['be_ftab_private']                  = 'privatūs&nbsp;failai';
$BL['be_ftab_public']                   = 'vieši&nbsp;failai';
$BL['be_ftab_search']                   = 'paieška';
$BL['be_ftab_trash']                    = 'šiukšlių&nbsp;dėžė';
$BL['be_ftab_open']                     = 'atidaryti visas direktorijas';
$BL['be_ftab_close']                    = 'uždaryti visas atidarytas direktorijas';
$BL['be_ftab_upload']                   = 'užkrauti failą į pagrindinę direktoriją';
$BL['be_ftab_filehelp']                 = 'atidaryti failų pagalbą';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'pagrindinė direktorija';
$BL['be_fpriv_title']                   = 'sukurti naują direktoriją';
$BL['be_fpriv_inside']                  = 'viduje';
$BL['be_fpriv_error']                   = 'klaida: nurodykite direktorijos vardą';
$BL['be_fpriv_name']                    = 'pavadinimas';
$BL['be_fpriv_status']                  = 'statusas';
$BL['be_fpriv_button']                  = 'sukurti naują direktoriją';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'redaguoti direktoriją';
$BL['be_fpriv_newname']                 = 'naujas pavadinimas';
$BL['be_fpriv_updatebutton']            = 'atnaujinti direktorijos informaciją';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Pasirinkite failą, kurį norite užkrauti';
$BL['be_fprivup_err2']                  = 'Užkrauto failo dydis yra didesnis negu';
$BL['be_fprivup_err3']                  = 'Klaida rašant failą į saugyklą';
$BL['be_fprivup_err4']                  = 'Klaida kuriant vartotojo direktoriją.';
$BL['be_fprivup_err5']                  = 'peržiūros paveikslėlio nėra';
$BL['be_fprivup_err6']                  = 'Prašome daugiau nebandyti - tai serverio klaida! Susisiekite su savo <a href="mailto:{VAL}">webmasteriu</a> kaip galima greičiau!';
$BL['be_fprivup_title']                 = 'užkrauti failus';
$BL['be_fprivup_button']                = 'užkrauti failus';
$BL['be_fprivup_upload']                = 'užkrauti';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'redaguoti failo aprašymą';
$BL['be_fprivedit_filename']            = 'failo pavadinimas';
$BL['be_fprivedit_created']             = 'sukurtas';
$BL['be_fprivedit_dateformat']          = 'Y-m-d H:i';
$BL['be_fprivedit_err1']                = 'proof name of file (atstatyti pradinį pavadinimą)';
$BL['be_fprivedit_clockwise']           = 'pasukti peržiūros paveikslėlį pagal laikrodžio rodyklę [pradinis failas +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'pasukti peržiūros paveikslėlį prieš laikrodžio rodyklę [pradinis failas -90&deg;]';
$BL['be_fprivedit_button']              = 'atnaujinti failo aprašymą';
$BL['be_fprivedit_size']                = 'dydis';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'užkrauti failą į direktoriją';
$BL['be_fprivfunc_makenew']             = 'viduje sukurti naują direktoriją';
$BL['be_fprivfunc_paste']               = 'nukopijuoti failą iš atminties į direktoriją';
$BL['be_fprivfunc_edit']                = 'redaguoti direktoriją';
$BL['be_fprivfunc_cactive']             = 'perjungti į aktyvią/neaktyvią';
$BL['be_fprivfunc_cpublic']             = 'perjungti į viešą/neviešą';
$BL['be_fprivfunc_deldir']              = 'Ištrinti direktoriją';
$BL['be_fprivfunc_jsdeldir']            = 'Ar tikrai norite \ništrinti direktoriją?';
$BL['be_fprivfunc_notempty']            = 'direktorija {VAL} nėra tuščia!';
$BL['be_fprivfunc_opendir']             = 'atidaryti direktoriją';
$BL['be_fprivfunc_closedir']            = 'uždaryti direktoriją';
$BL['be_fprivfunc_dlfile']              = 'atisiųsti failą';
$BL['be_fprivfunc_clipfile']            = 'atmintyje esantis failas';
$BL['be_fprivfunc_cutfile']             = 'iškirpti';
$BL['be_fprivfunc_editfile']            = 'redaguoti failo aprašymą';
$BL['be_fprivfunc_cactivefile']         = 'perjungti į aktyvų/neaktyvų';
$BL['be_fprivfunc_cpublicfile']         = 'perjungti į viešą/neviešą';
$BL['be_fprivfunc_movetrash']           = 'išmesti į šiukšlių dėžę';
$BL['be_fprivfunc_jsmovetrash1']        = 'Ar tikrai norite išmesti';
$BL['be_fprivfunc_jsmovetrash2']        = 'į šiukšių dėžę?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'nėra privačių failų ir direktorijų';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'vartotojas';
$BL['be_fpublic_nofiles']               = 'nėra viešų failų ir direktorijų';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'šiukšių dėžė yra tuščia';
$BL['be_ftrash_show']                   = 'rodyti privačius failus';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Ar norite atkurti {VAL} \nir grąžinti į privatų sąrašą?';
$BL['be_ftrash_delete']                 = 'Ar norite ištrinti {VAL}?';
$BL['be_ftrash_undo']                   = 'atstatyti (grąžinti iš šiukšliadėžės)';
$BL['be_ftrash_delfinal']               = 'galutinis ištrynimas';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'tuščia paieškos užklausa.';
$BL['be_fsearch_title']                 = 'ieškoti failų';
$BL['be_fsearch_infotext']              = 'Čia galima atlikti paprastą failų paiešką. Ieškoma atitinkamų raktinių žodžių,<br />failų pavadinimų ir išsamiuose faulų aprašymuose. Simbolio '*' naudoti negalima. Žodžius atskirkite tarpais. <br /> Pasirinkite IR/ARBA ir kokių failų ieškoti: asmeninių/viešai prieinamų.';
$BL['be_fsearch_nonfound']              = 'pagal jūsų užklausta failų nerasta. patikslinkite užklausą!';
$BL['be_fsearch_fillin']                = 'malonėkite įrašyti užklausą į viršuje esantį langelį.';
$BL['be_fsearch_searchlabel']           = 'ieškoti';
$BL['be_fsearch_startsearch']           = 'pradėti paiešką';
$BL['be_fsearch_and']                   = 'IR';
$BL['be_fsearch_or']                    = 'ARBA';
$BL['be_fsearch_all']                   = 'visi failai';
$BL['be_fsearch_personal']              = 'privatūs';
$BL['be_fsearch_public']                = 'viešai prieinami';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'vidiniai pokalbiai';
$BL['be_chat_info']                     = 'Čia galite kalbėtis su kitais phpwcms sistemos vartotojais. Ši terpė yra skirta šnekėtis real-time, bet taip pat galite palikti žinutę, kurią galės perskaityti kiti.';
$BL['be_chat_start']                    = 'norėdami pradėti kalbėtis, paspauskite čia';
$BL['be_chat_lines']                    = 'pokalbio eilutės';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'žinučių centras';
$BL['be_msg_new']                       = 'nauja';
$BL['be_msg_old']                       = 'sena';
$BL['be_msg_senttop']                   = 'išsiųstos';
$BL['be_msg_del']                       = 'ištrintos';
$BL['be_msg_from']                      = 'nuo';
$BL['be_msg_subject']                   = 'tema';
$BL['be_msg_date']                      = 'data/laikas';
$BL['be_msg_close']                     = 'uždaryti žinutę';
$BL['be_msg_create']                    = 'sukurti naują žinutę';
$BL['be_msg_reply']                     = 'atsakyti į šią žinutę';
$BL['be_msg_move']                      = 'išmesti šią žinutę į šiukšlių dėžę';
$BL['be_msg_unread']                    = 'neperskaitytos arba naujos žinutės';
$BL['be_msg_lastread']                  = 'paskutinės {VAL} perskaitytos žinutės';
$BL['be_msg_lastsent']                  = 'paskutinės {VAL} išsiųstos žinutės';
$BL['be_msg_marked']                    = 'žinutės pažymėtos išmetimui į šiukšlių dėžę';
$BL['be_msg_nomsg']                     = 'šiame aplanke žinučių nėra';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'ATS';
$BL['be_msg_by']                        = 'atsiųsta nuo';
$BL['be_msg_on']                        = 'on';
$BL['be_msg_msg']                       = 'žinutė';
$BL['be_msg_err1']                      = 'pamiršote nurodyti gavėją...';
$BL['be_msg_err2']                      = 'užpildykite temos laukelį (gavėjui bus aiškiau)';
$BL['be_msg_err3']                      = 'nėra prasmės siųsti žinutės be teksto ;-)';
$BL['be_msg_sent']                      = 'žinutė išsiųsta.';
$BL['be_msg_fwd']                       = 'jūs būsite perkelti į žinučių centrą arba';
$BL['be_msg_newmsgtitle']               = 'rašykite naują žinutę';
$BL['be_msg_err']                       = 'klaida siunčiant žinutę';
$BL['be_msg_sendto']                    = 'kam siųsti žinutę';
$BL['be_msg_available']                 = 'galimų adresatų sąrašas';
$BL['be_msg_all']                       = 'siųsti žinutę visiems pažymėtiems adresatams';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'naujienlaiškio užsakymai';
$BL['be_newsletter_titleedit']          = 'redaguoti naujienlaiškio užsakymą';
$BL['be_newsletter_new']                = 'sukurti naują';
$BL['be_newsletter_add']                = 'pridėti&nbsp;naujienlaiškio&nbsp;užsakymą';
$BL['be_newsletter_name']               = 'vardas';
$BL['be_newsletter_info']               = 'informacija';
$BL['be_newsletter_button_save']        = 'įrašyti užsakymą';
$BL['be_newsletter_button_cancel']      = 'atšaukti';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'netinkamas vartotojo vardas, įveskite naują ';
$BL['be_admin_usr_err2']                = 'neįvestas vartotojo vardas (būtinas)';
$BL['be_admin_usr_err3']                = 'neįvestas slaptažodis (būtinas)';
$BL['be_admin_usr_err4']                = "negaliojantis el. pašto adresas";
$BL['be_admin_usr_err']                 = 'klaida';
$BL['be_admin_usr_mailsubject']         = 'sveiki atvykę į tinklapio administravimą';
$BL['be_admin_usr_mailbody']            = "TINKLAPIO ADMINISTRAVIMO SISTEMA\n\n    vartotojo vardas: {LOGIN}\n    slapradžodis: {PASSWORD}\n\n\nGalite prisijungti čia: {LOGIN_PAGE}\n\nsistemos administratorius\n ";
$BL['be_admin_usr_title']               = 'pridėti naują vartotoją';
$BL['be_admin_usr_realname']            = 'vardas ir pavardė';
$BL['be_admin_usr_setactive']           = 'aktyvuoti vartotoją';
$BL['be_admin_usr_iflogin']             = 'jei čia pažymėta, vartotojas gali prisijungti';
$BL['be_admin_usr_isadmin']             = 'vartotojas turi administratoriaus teises';
$BL['be_admin_usr_ifadmin']             = 'jei pažymėta, vartotojas turi visas valdymo teises';
$BL['be_admin_usr_verify']              = 'patikrinimas';
$BL['be_admin_usr_sendemail']           = 'nusiųsti naujam vartotojui el. laišką su jo prisijungimo duomenimis';
$BL['be_admin_usr_button']              = 'įrašyti vartotojo duomenis';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'redaguoti vartotojo duomenis';
$BL['be_admin_usr_emailsubject']        = 'duomenys pakeisti';
$BL['be_admin_usr_emailbody']           = "VARTOTOJO INFORMACIJA PAKEISTA\n\n    vartotojo vardas: {LOGIN}\n    slaptažodis: {PASSWORD}\n\n\nPrisijungti galite čia: {LOGIN_PAGE}\n\nsistemos administratorius\n ";
$BL['be_admin_usr_passnochange']        = '[NAUDOKITE SAVO SLAPTAŽODĮ]';
$BL['be_admin_usr_ebutton']             = 'atnaujinti vartotojo duomenis';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'sistemos vartotojų sąrašas';
$BL['be_admin_usr_ldel']                = 'DĖMESIO!&#13Šitas mygtukas ištrina vartotoją';
$BL['be_admin_usr_create']              = 'sukurti naują vartotoją';
$BL['be_admin_usr_editusr']             = 'redaguoti vartotoją';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'tinklapio struktūra';
$BL['be_admin_struct_child']            = '(priklauso)';
$BL['be_admin_struct_index']            = 'indeksas (tinklapio pradžia)';
$BL['be_admin_struct_cat']              = 'kategorijos pavadinimas';
$BL['be_admin_struct_status']           = 'meniu statusas';
$BL['be_admin_struct_hide1']            = 'paslėpti';
$BL['be_admin_struct_hide2']            = 'ši&nbsp;kategorija&nbsp;yra&nbsp;meniu';
$BL['be_admin_struct_info']             = 'categorijos infotekstas';
$BL['be_admin_struct_template']         = 'šablonas';
$BL['be_admin_struct_alias']            = 'trumpas šios kategorijos pavadinimas';
$BL['be_admin_struct_visible']          = 'matoma';
$BL['be_admin_struct_button']           = 'įrašyti kategorijos duomenis';
$BL['be_admin_struct_close']            = 'uždaryti';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'failų kategorijos';
$BL['be_admin_fcat_err']                = 'neįvestas kategorijos pavadinimas!';
$BL['be_admin_fcat_name']               = 'kategorijos pavadinimas';
$BL['be_admin_fcat_needed']             = 'reikalinga';
$BL['be_admin_fcat_button1']            = 'atnaujinti';
$BL['be_admin_fcat_button2']            = 'sukurti';
$BL['be_admin_fcat_delmsg']             = 'Ar tikrai norite\ništrinti failo raktą?';
$BL['be_admin_fcat_fcat']               = 'failo kategorija';
$BL['be_admin_fcat_err1']               = 'nenurodytas failo rakto pavadinimas!';
$BL['be_admin_fcat_fkeyname']           = 'failo rakto pavadinimas';
$BL['be_admin_fcat_exit']               = 'išeiti iš redagavimo';
$BL['be_admin_fcat_addkey']             = 'pridėti naują raktą';
$BL['be_admin_fcat_editcat']            = 'redaguoti kategorijos pavadinimą';
$BL['be_admin_fcat_delcatmsg']          = 'Ar tikrai norite \ništrinti failo kategoriją?';
$BL['be_admin_fcat_delcat']             = 'ištrinti failo kategoriją';
$BL['be_admin_fcat_delkey']             = 'ištrinti failo rakto pavadinimą';
$BL['be_admin_fcat_editkey']            = 'redaguoti raktą';
$BL['be_admin_fcat_addcat']             = 'sukurti naują failų kategoriją';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'tinklapio nustatymai: puslapio maketas';
$BL['be_admin_page_align']              = 'puslapio lygiavimas';
$BL['be_admin_page_align_left']         = 'standartinis viso puslapio turinio lygiavimas (kairėje)';
$BL['be_admin_page_align_center']       = 'centruoti visą puslapio turinį';
$BL['be_admin_page_align_right']        = 'viso puslapio turinys dešinėje';
$BL['be_admin_page_margin']             = 'pakraščiai';
$BL['be_admin_page_top']                = 'viršus';
$BL['be_admin_page_bottom']             = 'apačia';
$BL['be_admin_page_left']               = 'kairė';
$BL['be_admin_page_right']              = 'dešinė';
$BL['be_admin_page_bg']                 = 'fonas';
$BL['be_admin_page_color']              = 'spalva';
$BL['be_admin_page_height']             = 'aukštis';
$BL['be_admin_page_width']              = 'plotis';
$BL['be_admin_page_main']               = 'pagrindinė dalis';
$BL['be_admin_page_leftspace']          = 'kairės atskyrimas';
$BL['be_admin_page_rightspace']         = 'dešinės atskyrimas';
$BL['be_admin_page_class']              = 'klasė';
$BL['be_admin_page_image']              = 'paveikslėlis';
$BL['be_admin_page_text']               = 'tekstas';
$BL['be_admin_page_link']               = 'nuoroda';
$BL['be_admin_page_js']                 = 'javascriptas';
$BL['be_admin_page_visited']            = 'aplankyta nuoroda';
$BL['be_admin_page_pagetitle']          = 'puslapio&nbsp;antraštė';
$BL['be_admin_page_addtotitle']         = 'pridėti&nbsp;prie&nbsp;antraštės';
$BL['be_admin_page_category']           = 'kategorija';
$BL['be_admin_page_articlename']        = 'straipsnio&nbsp;pavadinimas';
$BL['be_admin_page_blocks']             = 'blokai';
$BL['be_admin_page_allblocks']          = 'visi blokai';
$BL['be_admin_page_col1']               = '3 stulpelių maketas';
$BL['be_admin_page_col2']               = '2 stulpelių maketas (turinys dešinėje, navigacija kairėje)';
$BL['be_admin_page_col3']               = '2 stulpelių maketas (turinys kairėje, navigacija dešinėje)';
$BL['be_admin_page_col4']               = '1 stulpelio maketas';
$BL['be_admin_page_header']             = 'viršus';
$BL['be_admin_page_footer']             = 'apačia';
$BL['be_admin_page_topspace']           = 'viršaus&nbsp;atskyrimas';
$BL['be_admin_page_bottomspace']        = 'apačios&nbsp;atskyrimas';
$BL['be_admin_page_button']             = 'išsaugoti puslapio maketą';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'tinklapio nustatymai: css duomenys';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'išsaugoti css duomenis';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'tinklapio nustatymai: šablonai';
$BL['be_admin_tmpl_default']            = 'pagal nutylėjimą';
$BL['be_admin_tmpl_add']                = 'pridėti&nbsp;šabloną';
$BL['be_admin_tmpl_edit']               = 'redaguoti šabloną';
$BL['be_admin_tmpl_new']                = 'sukurti naują';
$BL['be_admin_tmpl_css']                = 'css failas';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'kai&nbsp;puslapis&nbsp;tuščias';
$BL['be_admin_tmpl_button']             = 'išsaugoti šabloną';
$BL['be_admin_tmpl_name']               = 'pavadinimas';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'tinklapio struktūra ir straipsnių sąrašas';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'neįvestas straipsnio pavadinimas';
$BL['be_article_err2']                  = 'buvo nurodyta klaidinga pradžios data - nustatyta dabartinė data';
$BL['be_article_err3']                  = 'buvo nurodyta klaidinga pabaigos data - nustatyta dabartinė data';
$BL['be_article_title1']                = 'pagrindinė straipsnio informacija';
$BL['be_article_cat']                   = 'kategorija';
$BL['be_article_atitle']                = 'straipsnio pavadinimas';
$BL['be_article_asubtitle']             = 'paantraštė';
$BL['be_article_abegin']                = 'rodymo pradžia';
$BL['be_article_aend']                  = 'rodymo pabaiga';
$BL['be_article_aredirect']             = 'nukreipti į';
$BL['be_article_akeywords']             = 'raktiniai žodžiai';
$BL['be_article_asummary']              = 'santrauka';
$BL['be_article_abutton']               = 'sukurti naują straipsnį';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'pabaigos data buvo kladinga - nustatyta dabartinė data + 1 savaitė';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'redaguoti pagrindinę straipsnio informaciją';
$BL['be_article_eslastedit']            = 'paskutinis redagavimas';
$BL['be_article_esnoupdate']            = 'forma neatnaujinta';
$BL['be_article_esbutton']              = 'atnaujinti straipsnio duomenis';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'straipsnio turinys';
$BL['be_article_cnt_type']              = 'turinio tipas';
$BL['be_article_cnt_space']             = 'tarpas';
$BL['be_article_cnt_before']            = 'prieš';
$BL['be_article_cnt_after']             = 'po';
$BL['be_article_cnt_top']               = 'top';
$BL['be_article_cnt_ctitle']            = 'pavadinimas';
$BL['be_article_cnt_back']              = 'visa straipsnio informacija';
$BL['be_article_cnt_button1']           = 'atnaujinti turinį';
$BL['be_article_cnt_button2']           = 'sukurti turinį';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'straipsnio informacija';
$BL['be_article_cnt_ledit']             = 'redaguoti straipsnį';
$BL['be_article_cnt_lvisible']          = 'perjungti į rodomą/nerodomą';
$BL['be_article_cnt_ldel']              = 'ištrinti šį straipsnį';
$BL['be_article_cnt_ldeljs']            = 'Ištrinti straipsnį?';
$BL['be_article_cnt_redirect']          = 'peradresavimas';
$BL['be_article_cnt_edited']            = 'redagavo';
$BL['be_article_cnt_start']             = 'rodymo pradžios data';
$BL['be_article_cnt_end']               = 'rodymo pabaigos data';
$BL['be_article_cnt_add']               = 'pridėti naują turinio elementą';
$BL['be_article_cnt_up']                = 'perkelti turinį aukštyn';
$BL['be_article_cnt_down']              = 'perkelti turinį aukštyn';
$BL['be_article_cnt_edit']              = 'redaguoti turinio elementą';
$BL['be_article_cnt_delpart']           = 'ištrinti šį turinio elementą';
$BL['be_article_cnt_delpartjs']         = 'Ištrinti turinio elementą?';
$BL['be_article_cnt_center']            = 'straipsnių centras';

// content forms
$BL['be_cnt_plaintext']                 = 'paprastas tekstas';
$BL['be_cnt_htmltext']                  = 'html tekstas';
$BL['be_cnt_image']                     = 'paveikslėlis';
$BL['be_cnt_position']                  = 'pozicija';
$BL['be_cnt_pos0']                      = 'Virš, keirėje';
$BL['be_cnt_pos1']                      = 'Virš, per vidurį';
$BL['be_cnt_pos2']                      = 'Virš, dešinėje';
$BL['be_cnt_pos3']                      = 'Apačioje, kairėje';
$BL['be_cnt_pos4']                      = 'Apačioje, per vidurį';
$BL['be_cnt_pos5']                      = 'Apačioje, dešinėje';
$BL['be_cnt_pos6']                      = 'Tekste, kairėje';
$BL['be_cnt_pos7']                      = 'Tekste, dešinėje';
$BL['be_cnt_pos0i']                     = 'Paveikslėlis virš teksto kairėje';
$BL['be_cnt_pos1i']                     = 'Paveikslėlis virš teksto per vidurį';
$BL['be_cnt_pos2i']                     = 'Paveikslėlis virš teksto dešinėje';
$BL['be_cnt_pos3i']                     = 'Paveikslėlis po tekstu kairėje';
$BL['be_cnt_pos4i']                     = 'Paveikslėlis po tekstu per vidurį';
$BL['be_cnt_pos5i']                     = 'Paveikslėlis po tekstu dešinėje';
$BL['be_cnt_pos6i']                     = 'Paveikslėlis tekste kairėje';
$BL['be_cnt_pos7i']                     = 'Paveikslėlis tekste dešinėje';
$BL['be_cnt_maxw']                      = 'maks.&nbsp;plotis';
$BL['be_cnt_maxh']                      = 'maks.&nbsp;aukštis';
$BL['be_cnt_enlarge']                   = 'paspaudus&nbsp;padidinti';
$BL['be_cnt_caption']                   = 'pavadinimas';
$BL['be_cnt_subject']                   = 'tema';
$BL['be_cnt_recipient']                 = 'gavėjas';
$BL['be_cnt_buttontext']                = 'mygtuko tekstas';
$BL['be_cnt_sendas']                    = 'siųsti kaip';
$BL['be_cnt_text']                      = 'tekstą';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'formos laukai';
$BL['be_cnt_code']                      = 'kodas';
$BL['be_cnt_infotext']                  = 'aprašymas';
$BL['be_cnt_subscription']              = 'galimos&nbsp;prenumeratos';
$BL['be_cnt_labelemail']                = 'el. pašto&nbsp;laukas';
$BL['be_cnt_tablealign']                = 'lentelės&nbsp;lygiavimas';
$BL['be_cnt_labelname']                 = 'vardo&nbsp;laukas';
$BL['be_cnt_labelsubsc']                = 'prenumeratos&nbsp;laukas';
$BL['be_cnt_allsubsc']                  = 'visos&nbsp;prenum.';
$BL['be_cnt_default']                   = 'pagal nutylėjimą';
$BL['be_cnt_left']                      = 'kairėje';
$BL['be_cnt_center']                    = 'per vidurį';
$BL['be_cnt_right']                     = 'dešineje';
$BL['be_cnt_buttontext']                = 'mygtuko&nbsp;tekstas';
$BL['be_cnt_successtext']               = 'tektas&nbsp;po&nbsp;registr.';
$BL['be_cnt_regmail']                   = 'registravimo&nbsp;el. pašto adresas';
$BL['be_cnt_logoffmail']                = 'išregistravimo&nbsp;el. pašto adresas';
$BL['be_cnt_changemail']                = 'pakeitimo&nbsp;el. pašto adresas';
$BL['be_cnt_openimagebrowser']          = 'atidaryti paveikslėlių naršyklę';
$BL['be_cnt_openfilebrowser']           = 'atidaryti failų naršyklę';
$BL['be_cnt_sortup']                    = 'aukštyn';
$BL['be_cnt_sortdown']                  = 'žemyn';
$BL['be_cnt_delimage']                  = 'pašalinti pasirinktą paveikslėlį';
$BL['be_cnt_delfile']                   = 'pašalinti pasirinktą failą';
$BL['be_cnt_delmedia']                  = 'pašalinti pasirinktą objektą';
$BL['be_cnt_column']                    = 'stulpelis';
$BL['be_cnt_imagespace']                = 'paveikslėlio&nbsp;erdvė';
$BL['be_cnt_directlink']                = 'tiesioginė nuoroda';
$BL['be_cnt_target']                    = 'atidaryti';
$BL['be_cnt_target1']                   = 'naujame lange';
$BL['be_cnt_target2']                   = 'in parent frame of the window';
$BL['be_cnt_target3']                   = 'tame pačiame lange be rėmų';
$BL['be_cnt_target4']                   = 'tame pačiame rėme arba lange';
$BL['be_cnt_bullet']                    = 'bullet list';
$BL['be_cnt_linklist']                  = 'nuorodų sąrašas';
$BL['be_cnt_plainhtml']                 = 'paprastas html';
$BL['be_cnt_files']                     = 'failai';
$BL['be_cnt_description']               = 'aprašymas';
$BL['be_cnt_linkarticle']               = 'nuoroda į straipsnį';
$BL['be_cnt_articles']                  = 'straipsniai';
$BL['be_cnt_movearticleto']             = 'perkelti pasirinktą straipsnį į straipsnių sąrašą';
$BL['be_cnt_removearticleto']           = 'išimti pasirinktą straipsnį iš straipsnių sąrašo';
$BL['be_cnt_mediatype']                 = 'media tipas';
$BL['be_cnt_control']                   = 'kontrolė';
$BL['be_cnt_showcontrol']               = 'radyti kontrolės juostą';
$BL['be_cnt_autoplay']                  = 'paleisti automatiškai';
$BL['be_cnt_source']                    = 'kodas';
$BL['be_cnt_internal']                  = 'vidinis';
$BL['be_cnt_openmediabrowser']          = 'atidaryti media objektų naršyklę';
$BL['be_cnt_external']                  = 'išorinis';
$BL['be_cnt_mediapos0']                 = 'kairėje (pagal nutylėjimą)';
$BL['be_cnt_mediapos1']                 = 'per vidurį';
$BL['be_cnt_mediapos2']                 = 'dešineje';
$BL['be_cnt_mediapos3']                 = 'blokas, kairėje';
$BL['be_cnt_mediapos4']                 = 'blokas, dešinėje';
$BL['be_cnt_mediapos0i']                = 'media virš teksto kairėje';
$BL['be_cnt_mediapos1i']                = 'media virš teksto per vidurį';
$BL['be_cnt_mediapos2i']                = 'media virš teksto dešinėje';
$BL['be_cnt_mediapos3i']                = 'media tekste kairėje';
$BL['be_cnt_mediapos4i']                = 'media tekste dešinėje';
$BL['be_cnt_setsize']                   = 'nustatyti dydį';
$BL['be_cnt_set1']                      = 'media užims 160x120px';
$BL['be_cnt_set2']                      = 'media užims 240x180px ';
$BL['be_cnt_set3']                      = 'media užims 320x240px';
$BL['be_cnt_set4']                      = 'media užims 480x360px';
$BL['be_cnt_set5']                      = 'išvalyti media aukščio ir pločio parametrus';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'sukurti naują puslapio maketą';
$BL['be_admin_page_name']               = 'maketo pavadinimas';
$BL['be_admin_page_edit']               = 'redaguoti maketą';
$BL['be_admin_page_render']             = 'atvaizdavimas';
$BL['be_admin_page_table']              = 'lentelė';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'kitas';
$BL['be_admin_page_custominfo']         = 'iš šablono pagrindinio bloko';
$BL['be_admin_tmpl_layout']             = 'maketas';
$BL['be_admin_tmpl_nolayout']           = 'Nėra puslapio maketo!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'paieška';
$BL['be_cnt_results']                   = 'rezultatai';
$BL['be_cnt_results_per_page']          = 'per&nbsp;puslapį (jei nepažymėta, rodyti viską)';
$BL['be_cnt_opennewwin']                = 'atidaryti naują langą';
$BL['be_cnt_searchlabeltext']           = 'tai yra iš anksto nustatyti tekstai ir parametrai paieškos formai ir rezultatų puslapiui, tekstai yra rodomi kuomet rezultatai netelpa į vieną puslapį.';
$BL['be_cnt_input']                     = 'įvedimas';
$BL['be_cnt_style']                     = 'stilius';
$BL['be_cnt_result']                    = 'rezultatai';
$BL['be_cnt_next']                      = 'kitas';
$BL['be_cnt_previous']                  = 'prieš tai buvęs';
$BL['be_cnt_align']                     = 'lygiuoti';
$BL['be_cnt_searchformtext']            = 'žemiau esantys tekstai yra rodomi atidarius paieškos formą arba kai paieška neduoda rezultato.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'kai nėra rezultatų';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'deaktyvuoti';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'straipsnio savininkas';
$BL['be_article_adminuser']             = 'vartotojas administratorius';
$BL['be_article_username']              = 'autorius';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible for users logged on only';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';

