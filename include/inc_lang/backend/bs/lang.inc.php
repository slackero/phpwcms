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


// Language: Bosanski, Language Code: bs
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'korisnika online';

// Login Page
$BL["login_text"]                       = 'Unesite:';
$BL['login_error']                      = 'Greska prilikom unosa!';
$BL["login_username"]                   = 'korisnik';
$BL["login_userpass"]                   = 'sifra';
$BL["login_button"]                     = 'OK';
$BL["login_lang"]                       = 'jezik administracije';

// phpwcms.php
$BL['be_nav_logout']                    = 'IZLAZ';
$BL['be_nav_articles']                  = 'CLANAK';
$BL['be_nav_files']                     = 'DATOTEKE';
$BL['be_nav_modules']                   = 'MODULI';
$BL['be_nav_messages']                  = 'KOMUNIKACIJA';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFILI';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'RASPRAVA';

$BL['be_page_title']                    = 'phpwcms (administracija)';

$BL['be_subnav_article_center']         = 'centar clanaka';
$BL['be_subnav_article_new']            = 'novi clanak';
$BL['be_subnav_file_center']            = 'datotecni centar';
$BL['be_subnav_file_ftptakeover']       = 'ftp preuzimanje';
$BL['be_subnav_mod_artists']            = 'izvodjac, kategorija, zanr';
$BL['be_subnav_msg_center']             = 'centar poruka';
$BL['be_subnav_msg_new']                = 'nova poruka';
$BL['be_subnav_msg_newsletter']         = 'newsletter pretplate';
$BL['be_subnav_chat_main']              = 'glavna strana chat-a';
$BL['be_subnav_chat_internal']          = 'interni chat';
$BL['be_subnav_profile_login']          = 'informacije o prijavi';
$BL['be_subnav_profile_personal']       = 'licni podaci';
$BL['be_subnav_admin_pagelayout']       = 'raspored stranice';
$BL['be_subnav_admin_templates']        = 'predlosci';
$BL['be_subnav_admin_css']              = 'glavni css';
$BL['be_subnav_admin_sitestructure']    = 'struktura stranice';
$BL['be_subnav_admin_users']            = 'administracija korisnika';
$BL['be_subnav_admin_filecat']          = 'kategorije datoteka';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID clanka';
$BL['be_func_struct_preview']           = 'pregled';
$BL['be_func_struct_edit']              = 'izmjeni clanak';
$BL['be_func_struct_sedit']             = 'izmjeni strukturni nivo';
$BL['be_func_struct_cut']               = 'izrezi clanak';
$BL['be_func_struct_nocut']             = 'onemoguci "izrezi clanak"';
$BL['be_func_struct_svisible']          = 'vidljivo/nevidljivo';
$BL['be_func_struct_spublic']           = 'javno/privatno';
$BL['be_func_struct_sort_up']           = 'sortiraj gore';
$BL['be_func_struct_sort_down']         = 'sortiraj dole';
$BL['be_func_struct_del_article']       = 'izbrisi clanak';
$BL['be_func_struct_del_jsmsg']         = 'da li stvarno zelite \nizbrisati clanak?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'kreiraj novi clanak u strukturnom nivou';
$BL['be_func_struct_paste_article']     = 'zalijepi clanak u strukturni nivo';
$BL['be_func_struct_insert_level']      = 'ubaci strukturni nivo u';
$BL['be_func_struct_paste_level']       = 'zalijepi u strukturni nivo';
$BL['be_func_struct_cut_level']         = 'izrezi strukturni nivo';
$BL['be_func_struct_no_cut']            = "Ne moze se izrezati korijenski nivo!";
$BL['be_func_struct_no_paste1']         = "Ne moze se zalijepiti ovdje!";
$BL['be_func_struct_no_paste2']         = 'je "dijete" u korijenskoj liniji nivoa';
$BL['be_func_struct_no_paste3']         = 'treba zalijepiti ovdje';
$BL['be_func_struct_paste_cancel']      = 'otkazi promjenu strukturnog nivoa';
$BL['be_func_struct_del_struct']        = 'izbrisi strukturni nivo';
$BL['be_func_struct_del_sjsmsg']        = 'da li stvarno zelite \nda izbrisete strukturni nivo?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'otvori';
$BL['be_func_struct_close']             = 'zatvori';
$BL['be_func_struct_empty']             = 'prazno';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'obicni tekst';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'kod';
$BL['be_ctype_textimage']               = 'tekst sa slikom';
$BL['be_ctype_images']                  = 'slike';
$BL['be_ctype_bulletlist']              = 'lista (tabela)';
$BL['be_ctype_ullist']                  = 'lista';
$BL['be_ctype_link']                    = 'link i email';
$BL['be_ctype_linklist']                = 'lista linkova';
$BL['be_ctype_linkarticle']             = 'teaser/link clanaka';
$BL['be_ctype_multimedia']              = 'multimedija';
$BL['be_ctype_filelist']                = 'lista datoteka';
$BL['be_ctype_emailform']               = 'email forma generator';
$BL['be_ctype_newsletter']              = 'newsletter';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profil uspjesno kreiran.';
$BL['be_profile_create_error']          = 'Greska prilikom kreiranja.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profil uspjesno izmjenjen.';
$BL['be_profile_update_error']          = 'Greska prilikom izmjene.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'korisnik {VAL} nije validan(ne postoji)';
$BL['be_profile_account_err2']          = 'sifra prekratka (samo {VAL} karaktera: potrebno najmanje 5)';
$BL['be_profile_account_err3']          = 'sifra mora biti identicna oba puta';
$BL['be_profile_account_err4']          = 'email {VAL} nije validan';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'vasi licni podaci';
$BL['be_profile_data_text']             = 'licni podaci su opcionalni. Mozete ih unijeti, a i ne morate';
$BL['be_profile_label_title']           = 'naslov';
$BL['be_profile_label_firstname']       = 'ime';
$BL['be_profile_label_name']            = 'prezime';
$BL['be_profile_label_company']         = 'firma';
$BL['be_profile_label_street']          = 'ulica';
$BL['be_profile_label_city']            = 'grad';
$BL['be_profile_label_state']           = 'entitet/provincija';
$BL['be_profile_label_zip']             = 'postanski broj';
$BL['be_profile_label_country']         = 'zemlja';
$BL['be_profile_label_phone']           = 'tel';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mob';
$BL['be_profile_label_signature']       = 'potpis';
$BL['be_profile_label_notes']           = 'note';
$BL['be_profile_label_profession']      = 'profesija';
$BL['be_profile_label_newsletter']      = 'newsletter';
$BL['be_profile_text_newsletter']       = 'zelim da primam newsletter.';
$BL['be_profile_label_public']          = 'javno';
$BL['be_profile_text_public']           = 'Svi mogu da vide moj profil.';
$BL['be_profile_label_button']          = 'izmjeni licne podatke';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'informacije o prijavi';
$BL['be_profile_account_text']          = 'Obicno ne treba mjenjati sifru, ali preporuceno je to uraditi sa vremena na vrijeme.';
$BL['be_profile_label_err']             = 'molimo provjerite';
$BL['be_profile_label_username']        = 'korisnicko ime';
$BL['be_profile_label_newpass']         = 'nova sifra';
$BL['be_profile_label_repeatpass']      = 'ponovite novu sifru';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'izmjeni';
$BL['be_profile_label_lang']            = 'jezik';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'preuzimanje datoteka sa ftp-a';
$BL['be_ftptakeover_mark']              = 'oznaci';
$BL['be_ftptakeover_available']         = 'dostupne datoteke';
$BL['be_ftptakeover_size']              = 'velicina';
$BL['be_ftptakeover_nofile']            = 'ne postoje dostupne datoteke &#8211; morate ih ubaciti preko ftp-a';
$BL['be_ftptakeover_all']               = 'SVE';
$BL['be_ftptakeover_directory']         = 'direktorij';
$BL['be_ftptakeover_rootdir']           = 'glavi direktorij';
$BL['be_ftptakeover_needed']            = 'potrebno!!! (morate oznaciti jedno)';
$BL['be_ftptakeover_optional']          = 'opcionalno';
$BL['be_ftptakeover_keywords']          = 'kljucne rijeci';
$BL['be_ftptakeover_additional']        = 'dodatno';
$BL['be_ftptakeover_longinfo']          = 'dugi opis';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'aktivno';
$BL['be_ftptakeover_public']            = 'javno';
$BL['be_ftptakeover_createthumb']       = 'napravi malu slicicu';
$BL['be_ftptakeover_button']            = 'OK';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'datotecni centar';
$BL['be_ftab_createnew']                = 'napravi novi direktorij u glavnom direktoriju';
$BL['be_ftab_paste']                    = 'ubaci datoteku iz memorije u glavni direktorij';
$BL['be_ftab_disablethumb']             = 'iskljuci male slike u listi';
$BL['be_ftab_enablethumb']              = 'ukljuci male slike u listi';
$BL['be_ftab_private']                  = 'privatne&nbsp;dat.';
$BL['be_ftab_public']                   = 'javne&nbsp;datoteke';
$BL['be_ftab_search']                   = 'trazi';
$BL['be_ftab_trash']                    = 'kanta&nbsp;za smece';
$BL['be_ftab_open']                     = 'otvori sve direktorije';
$BL['be_ftab_close']                    = 'zatvori sve otvorene direktorije';
$BL['be_ftab_upload']                   = 'ubaci datoteku u glavni direktorij';
$BL['be_ftab_filehelp']                 = 'otvori pomoc - na engleskom';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'glavni direktorij';
$BL['be_fpriv_title']                   = 'napravi novi direktorij';
$BL['be_fpriv_inside']                  = 'u';
$BL['be_fpriv_error']                   = 'greska: upisite ime za vas direktorij';
$BL['be_fpriv_name']                    = 'ime';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'OK';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'izmjeni direktorij';
$BL['be_fpriv_newname']                 = 'novo ime';
$BL['be_fpriv_updatebutton']            = 'OK';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Oznacite datoteku koju zelite ubaciti';
$BL['be_fprivup_err2']                  = 'Velicina datoteka je veca od';
$BL['be_fprivup_err3']                  = 'Greska prilikom upisa datoteke na server';
$BL['be_fprivup_err4']                  = 'Greska prilikom kreiranja direktorija.';
$BL['be_fprivup_err5']                  = 'ne postoje male slicice';
$BL['be_fprivup_err6']                  = 'Molimo ne pokusavajte vise - Ovo je interna serverska greska! Kontaktirajte vaseg <a href="mailto:{VAL}">webmaster-a</a> sto je prije moguce!';
$BL['be_fprivup_title']                 = 'ubaci datoteke';
$BL['be_fprivup_button']                = 'OK';
$BL['be_fprivup_upload']                = 'ubaci';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'promjeni informaciju o datoteci';
$BL['be_fprivedit_filename']            = 'ime datoteke';
$BL['be_fprivedit_created']             = 'kreirana';
$BL['be_fprivedit_dateformat']          = 'd-m-Y H:i';
$BL['be_fprivedit_err1']                = 'provjeri ime datoteke (vrati nazad na originalno ime)';
$BL['be_fprivedit_clockwise']           = 'okreni malu slicicu u smjeru kazaljke  [original +90&stepeni;]';
$BL['be_fprivedit_cclockwise']          = 'okreni malu slicicu u suprotnom smjeru kazaljke [original -90&stepeni;]';
$BL['be_fprivedit_button']              = 'OK';
$BL['be_fprivedit_size']                = 'velicina';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'ubaci datoteku u direktorij';
$BL['be_fprivfunc_makenew']             = 'napravi novi direktorij u ';
$BL['be_fprivfunc_paste']               = 'umetni datoteku iz memorije u';
$BL['be_fprivfunc_edit']                = 'izmjeni direktorij';
$BL['be_fprivfunc_cactive']             = 'vidljivo/nevidljivo';
$BL['be_fprivfunc_cpublic']             = 'javno/privatno';
$BL['be_fprivfunc_deldir']              = 'izbrisi direktorij';
$BL['be_fprivfunc_jsdeldir']            = 'Da li stvarno zelite da \nizbrisete direktorij';
$BL['be_fprivfunc_notempty']            = 'direktorij {VAL} nije prazan!';
$BL['be_fprivfunc_opendir']             = 'otvori direktorij';
$BL['be_fprivfunc_closedir']            = 'zatvori direktorij';
$BL['be_fprivfunc_dlfile']              = 'preuzmi datoteku na kompjuter';
$BL['be_fprivfunc_clipfile']            = 'prebaci datoteku u radnu memoriju';
$BL['be_fprivfunc_cutfile']             = 'izrezi';
$BL['be_fprivfunc_editfile']            = 'promjeni informaciju o datoteci';
$BL['be_fprivfunc_cactivefile']         = 'vidljivo/nevidljivo';
$BL['be_fprivfunc_cpublicfile']         = 'javno/privatno';
$BL['be_fprivfunc_movetrash']           = 'baci u kantu za smece';
$BL['be_fprivfunc_jsmovetrash1']        = 'Da li stvarno zelite da bacite';
$BL['be_fprivfunc_jsmovetrash2']        = 'u kantu za smece?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'nema privatnih datoteka i direktorija';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'korisnik';
$BL['be_fpublic_nofiles']               = 'nema javnih datoteka i direktorija';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'kanta za smece je prazna';
$BL['be_ftrash_show']                   = 'prikazi privatne datoteke';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Zelite li da vratite {VAL} \nnazad u privatnu listu?';
$BL['be_ftrash_delete']                 = 'Zelite li da izbrisete {VAL}?';
$BL['be_ftrash_undo']                   = 'vrati nazad (ne brisi)';
$BL['be_ftrash_delfinal']               = 'konacno brisanje';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'upit za trazenje je prazan.';
$BL['be_fsearch_title']                 = 'trazi datoteke';
$BL['be_fsearch_infotext']              = 'Ovo je osnovno pretrazivanje datotecnih informacija. Pretrazuje kljucne rijeci,<br />ime datoteke i dugi opis.';
$BL['be_fsearch_nonfound']              = 'vasa pretraga nije nasla rezultate. pokusajte sa drugim rijecima!';
$BL['be_fsearch_fillin']                = 'molimo popunite polje za trazenje iznad.';
$BL['be_fsearch_searchlabel']           = 'trazi';
$BL['be_fsearch_startsearch']           = 'pocni pretragu';
$BL['be_fsearch_and']                   = 'I';
$BL['be_fsearch_or']                    = 'ILI';
$BL['be_fsearch_all']                   = 'sve datoteke';
$BL['be_fsearch_personal']              = 'privatne';
$BL['be_fsearch_public']                = 'javne';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'interno caskanje';
$BL['be_chat_info']                     = 'Ovdje mozete caskati sa ostalim korisnicima.';
$BL['be_chat_start']                    = 'kliknite ovdje da zapocnete caskanja';
$BL['be_chat_lines']                    = 'tekst poruka';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'centar poruka';
$BL['be_msg_new']                       = 'novo';
$BL['be_msg_old']                       = 'staro';
$BL['be_msg_senttop']                   = 'poslano';
$BL['be_msg_del']                       = 'izbrisano';
$BL['be_msg_from']                      = 'od';
$BL['be_msg_subject']                   = 'naslov';
$BL['be_msg_date']                      = 'datum/vrijeme';
$BL['be_msg_close']                     = 'zatvori poruku';
$BL['be_msg_create']                    = 'napravi novu poruku';
$BL['be_msg_reply']                     = 'odgovori na ovu poruku';
$BL['be_msg_move']                      = 'pomjeri ovu poruku u smece';
$BL['be_msg_unread']                    = 'neprocitane ili nove poruke';
$BL['be_msg_lastread']                  = 'zadnje {VAL} procitane poruke';
$BL['be_msg_lastsent']                  = 'zadnje {VAL} poslane poruke';
$BL['be_msg_marked']                    = 'poruke oznacene za brisanje (smece)';
$BL['be_msg_nomsg']                     = 'u ovom direktoriju nema poruka';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'poslano od';
$BL['be_msg_on']                        = 'na';
$BL['be_msg_msg']                       = 'poruka';
$BL['be_msg_err1']                      = 'zaboravili ste staviti primaoca...';
$BL['be_msg_err2']                      = 'popunite polje primaoca';
$BL['be_msg_err3']                      = 'nema smisla poslati poruku bez same poruke ;-)';
$BL['be_msg_sent']                      = 'nova poruka je poslana!';
$BL['be_msg_fwd']                       = 'bicete preusmjereni na centar poruka ili';
$BL['be_msg_newmsgtitle']               = 'napisi novu poruku';
$BL['be_msg_err']                       = 'greska prilikom slanja';
$BL['be_msg_sendto']                    = 'posalji poruku';
$BL['be_msg_available']                 = 'lista dostupnih primaoca';
$BL['be_msg_all']                       = 'posalji poruku svim oznacenim primaocima';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'newsletter pretplata';
$BL['be_newsletter_titleedit']          = 'izmjeni newsletter pretplatu';
$BL['be_newsletter_new']                = 'napravi novo';
$BL['be_newsletter_add']                = 'dodaj&nbsp;newsletter&nbsp;pretplatu';
$BL['be_newsletter_name']               = 'ime';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'OK';
$BL['be_newsletter_button_cancel']      = 'Odustani';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'korisnicko ime neispravno, molimo pokusajte sa drugim';
$BL['be_admin_usr_err2']                = 'korisnicko ime je prazno (potrebno)';
$BL['be_admin_usr_err3']                = 'sifra je prazna (potrebno)';
$BL['be_admin_usr_err4']                = "email nije ispravan";
$BL['be_admin_usr_err']                 = 'greska';
$BL['be_admin_usr_mailsubject']         = 'dobrodosli na administracijske stranice';
$BL['be_admin_usr_mailbody']            = "Dobrodosli na administracijske stranice\n\n    korisnicko ime: {LOGIN}\n    sifra: {PASSWORD}\n\n\nMozete se prijaviti ovdje: {LOGIN_PAGE}\n\nadministrator\n ";
$BL['be_admin_usr_title']               = 'dodaj novog korisnika';
$BL['be_admin_usr_realname']            = 'pravo ime';
$BL['be_admin_usr_setactive']           = 'korisnik je aktivan';
$BL['be_admin_usr_iflogin']             = 'ako je oznaceno korisnik se moze prijaviti';
$BL['be_admin_usr_isadmin']             = 'korisnik je administrator';
$BL['be_admin_usr_ifadmin']             = 'ako je oznaceno korisnik ima administratorska prava';
$BL['be_admin_usr_verify']              = 'verifikacija';
$BL['be_admin_usr_sendemail']           = 'posalji email novom korisniku sa podacim o prijavi';
$BL['be_admin_usr_button']              = 'OK';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'izmjeni korisnicki racun';
$BL['be_admin_usr_emailsubject']        = 'podaci racuna izmjenjeni';
$BL['be_admin_usr_emailbody']           = "Informacije korisnickog racuna izmjenjene. \n\n    korisnicko ime: {LOGIN}\n    sifra: {PASSWORD}\n\n\nMozete se prijaviti ovdje: {LOGIN_PAGE}\n\nadministrator\n ";
$BL['be_admin_usr_passnochange']        = '[bez promjene - koristite postojecu sifru]';
$BL['be_admin_usr_ebutton']             = 'OK';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'lista korisnika';
$BL['be_admin_usr_ldel']                = 'UPOZORENJE!&#13;Ovim cete izbrisati korisnika';
$BL['be_admin_usr_create']              = 'napravi novog korisnika';
$BL['be_admin_usr_editusr']             = 'izmjeni korisnika';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'struktura stranice';
$BL['be_admin_struct_child']            = '(podanik od)';
$BL['be_admin_struct_index']            = 'index (start internet stranice)';
$BL['be_admin_struct_cat']              = 'naslov kategorije';
$BL['be_admin_struct_hide1']            = 'sakrij';
$BL['be_admin_struct_hide2']            = 'ovu&nbsp;kategoriju&nbsp;u&nbsp;meniju';
$BL['be_admin_struct_info']             = 'info kategorije';
$BL['be_admin_struct_template']         = 'predlozak';
$BL['be_admin_struct_alias']            = 'drugi naziv kategorije - alias';
$BL['be_admin_struct_visible']          = 'vidljivo';
$BL['be_admin_struct_button']           = 'OK';
$BL['be_admin_struct_close']            = 'Zatvori';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'kategorije datoteka';
$BL['be_admin_fcat_err']                = 'ime kategorije je prazno!';
$BL['be_admin_fcat_name']               = 'ime kategorije';
$BL['be_admin_fcat_needed']             = 'potrebno';
$BL['be_admin_fcat_button1']            = 'osvjezi';
$BL['be_admin_fcat_button2']            = 'kreiraj';
$BL['be_admin_fcat_delmsg']             = 'Da li stvarno zelite\nda izbrisete datotecni kljuc?';
$BL['be_admin_fcat_fcat']               = 'kategorija datoteke';
$BL['be_admin_fcat_err1']               = 'ime datotecnog kljuca je prazno!';
$BL['be_admin_fcat_fkeyname']           = 'ime datotecnog kljuca';
$BL['be_admin_fcat_exit']               = 'odustani';
$BL['be_admin_fcat_addkey']             = 'dodaj novi kljuc';
$BL['be_admin_fcat_editcat']            = 'izmjeni ime kategorije';
$BL['be_admin_fcat_delcatmsg']          = 'Da li stvarno zelite\nda izbrisete kategoriju datoteka?';
$BL['be_admin_fcat_delcat']             = 'izbrisi kategoriju datoteka';
$BL['be_admin_fcat_delkey']             = 'izbrisi ime datotecnog kljuca';
$BL['be_admin_fcat_editkey']            = 'izmjeni kljuc';
$BL['be_admin_fcat_addcat']             = 'nova kategorija datoteka';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend postavke: izgled stranice - layout';
$BL['be_admin_page_align']              = 'poravnanje stranice';
$BL['be_admin_page_align_left']         = 'standardno poravnanje (lijevo) cijelog sadrzaja stranice';
$BL['be_admin_page_align_center']       = 'centar cijelog sadrzaja stranice';
$BL['be_admin_page_align_right']        = 'desno poravnanje cijelog sadrzaja stranice';
$BL['be_admin_page_margin']             = 'margine';
$BL['be_admin_page_top']                = 'vrh';
$BL['be_admin_page_bottom']             = 'dno';
$BL['be_admin_page_left']               = 'lijevo';
$BL['be_admin_page_right']              = 'desno';
$BL['be_admin_page_bg']                 = 'pozadina';
$BL['be_admin_page_color']              = 'boja';
$BL['be_admin_page_height']             = 'visina';
$BL['be_admin_page_width']              = 'sirina';
$BL['be_admin_page_main']               = 'glavni prostor - main';
$BL['be_admin_page_leftspace']          = 'lijevi razmak';
$BL['be_admin_page_rightspace']         = 'desni razmak';
$BL['be_admin_page_class']              = 'klasa - class';
$BL['be_admin_page_image']              = 'slika';
$BL['be_admin_page_text']               = 'tekst';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript-a';
$BL['be_admin_page_visited']            = 'posjeceno';
$BL['be_admin_page_pagetitle']          = 'naslov&nbsp;stranice';
$BL['be_admin_page_addtotitle']         = 'dodaj&nbsp;u&nbsp;naslov';
$BL['be_admin_page_category']           = 'kategorija';
$BL['be_admin_page_articlename']        = 'naslov&nbsp;sadrzaja';
$BL['be_admin_page_blocks']             = 'blokovi';
$BL['be_admin_page_allblocks']          = 'svi blokovi';
$BL['be_admin_page_col1']               = '3 kolone layout';
$BL['be_admin_page_col2']               = '2 kolone layout (glavni prostor - desno, navigacijski prostor lijevo)';
$BL['be_admin_page_col3']               = '2 kolone layout (glavni prostor - lijevo, navigacijski prostor desno)';
$BL['be_admin_page_col4']               = '1 kolona layout';
$BL['be_admin_page_header']             = 'header';
$BL['be_admin_page_footer']             = 'footer';
$BL['be_admin_page_topspace']           = 'razmak&nbsp;od vrha';
$BL['be_admin_page_bottomspace']        = 'razmak&nbsp;od dna';
$BL['be_admin_page_button']             = 'OK';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend postavke: css postavke';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'OK';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend postavke: predlosci';
$BL['be_admin_tmpl_default']            = 'glavni';
$BL['be_admin_tmpl_add']                = 'dodaj&nbsp;predloske';
$BL['be_admin_tmpl_edit']               = 'izmjeni predlozak';
$BL['be_admin_tmpl_new']                = 'napravi novi';
$BL['be_admin_tmpl_css']                = 'css datoteka';
$BL['be_admin_tmpl_head']               = 'html zaglavlje';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'greska';
$BL['be_admin_tmpl_button']             = 'OK';
$BL['be_admin_tmpl_name']               = 'ime';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'struktura stranice i lista sadrzaja';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'naslov ovog sadrzaja je prazan';
$BL['be_article_err2']                  = 'pocetni datum je pogresan - postavite danasnji datum';
$BL['be_article_err3']                  = 'krajnji datum je pogresan - postavite danasnji datum';
$BL['be_article_title1']                = 'osnovne informacije sadrzaja';
$BL['be_article_cat']                   = 'kategorija';
$BL['be_article_atitle']                = 'naslov sadrzaja';
$BL['be_article_asubtitle']             = 'podnaslov';
$BL['be_article_abegin']                = 'pocinje';
$BL['be_article_aend']                  = 'zavrsava';
$BL['be_article_aredirect']             = 'redirekcija na';
$BL['be_article_akeywords']             = 'kljucne rijeci';
$BL['be_article_asummary']              = 'zakljucak';
$BL['be_article_abutton']               = 'OK';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'krajnji datum je pogresan - postavite danasnji datum + 1 sedmicu';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'izmjeni osnovne informacije sadrzaja';
$BL['be_article_eslastedit']            = 'posljednja izmjena';
$BL['be_article_esnoupdate']            = 'forma nije osvjezena';
$BL['be_article_esbutton']              = 'OK';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'sadrzaj clanka';
$BL['be_article_cnt_type']              = 'tip sadrzaja';
$BL['be_article_cnt_space']             = 'razmak';
$BL['be_article_cnt_before']            = 'ispred';
$BL['be_article_cnt_after']             = 'iza';
$BL['be_article_cnt_top']               = 'vrh';
$BL['be_article_cnt_toplink']           = 'link na vrh';
$BL['be_article_cnt_anchor']            = 'usidrenje';
$BL['be_article_cnt_ctitle']            = 'naslov sadrzaja';
$BL['be_article_cnt_back']              = 'kompletan info sadrzaja';
$BL['be_article_cnt_button1']           = 'osvjezi';
$BL['be_article_cnt_button2']           = 'kreiraj';
$BL['be_article_cnt_button3']           = 'snimi i zatvori';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'informacije o clanku';
$BL['be_article_cnt_ledit']             = 'izmjeni clanak';
$BL['be_article_cnt_lvisible']          = 'vidljivo/nevidljivo';
$BL['be_article_cnt_ldel']              = 'izbrisi ovaj clanak';
$BL['be_article_cnt_ldeljs']            = 'Izbrisati clanak?';
$BL['be_article_cnt_redirect']          = 'redirekcija';
$BL['be_article_cnt_edited']            = 'izmjenjeno od';
$BL['be_article_cnt_start']             = 'pocetni datum';
$BL['be_article_cnt_end']               = 'krajnji datum';
$BL['be_article_cnt_add']               = 'dodaj';
$BL['be_article_cnt_addtitle']          = 'dodaj novi dio sadrzaja';
$BL['be_article_cnt_up']                = 'pomjeri sadrzaj gore';
$BL['be_article_cnt_down']              = 'pomjeri sadrzaj dole';
$BL['be_article_cnt_edit']              = 'izmjeni dio sadrzaja';
$BL['be_article_cnt_delpart']           = 'izbrisi dio sadrzaja ovog clanka';
$BL['be_article_cnt_delpartjs']         = 'Izbrisati dio sadrzaja?';
$BL['be_article_cnt_center']            = 'centar sadrzaja';

// content forms
$BL['be_cnt_plaintext']                 = 'obicni tekst';
$BL['be_cnt_htmltext']                  = 'html tekst';
$BL['be_cnt_image']                     = 'slika';
$BL['be_cnt_position']                  = 'pozicija';
$BL['be_cnt_pos0']                      = 'iznad, lijevo';
$BL['be_cnt_pos1']                      = 'iznad, centar';
$BL['be_cnt_pos2']                      = 'iznad, desno';
$BL['be_cnt_pos3']                      = 'ispod, lijevo';
$BL['be_cnt_pos4']                      = 'ispod, centar';
$BL['be_cnt_pos5']                      = 'ispod, desno';
$BL['be_cnt_pos6']                      = 'u tekstu, lijevo';
$BL['be_cnt_pos7']                      = 'u tekstu, desno';
$BL['be_cnt_pos0i']                     = 'poravnaj sliku iznad i lijevo od tekst bloka';
$BL['be_cnt_pos1i']                     = 'poravnaj sliku iznad i centriraj sa tekst blokom';
$BL['be_cnt_pos2i']                     = 'poravnaj sliku iznad i desno od tekst bloka';
$BL['be_cnt_pos3i']                     = 'poravnaj sliku ispod i lijevo od tekst bloka';
$BL['be_cnt_pos4i']                     = 'poravnaj sliku ispod i centriraj sa tekst blokom';
$BL['be_cnt_pos5i']                     = 'poravnaj sliku ispod i desno od tekst bloka';
$BL['be_cnt_pos6i']                     = 'poravnaj sliku lijevo u tekst bloku';
$BL['be_cnt_pos7i']                     = 'poravnaj sliku desno u tekst bloku';
$BL['be_cnt_maxw']                      = 'max.&nbsp;sirina';
$BL['be_cnt_maxh']                      = 'max.&nbsp;visina';
$BL['be_cnt_enlarge']                   = 'uvecanje&nbsp;klikom';
$BL['be_cnt_caption']                   = 'dodatak';
$BL['be_cnt_subject']                   = 'naslov';
$BL['be_cnt_recipient']                 = 'primaoc';
$BL['be_cnt_buttontext']                = 'tekst dugmeta';
$BL['be_cnt_sendas']                    = 'posalji kao';
$BL['be_cnt_text']                      = 'tekst';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'polja forme';
$BL['be_cnt_code']                      = 'kod';
$BL['be_cnt_infotext']                  = 'info&nbsp;tekst';
$BL['be_cnt_subscription']              = 'pretplata';
$BL['be_cnt_labelemail']                = 'oznaci&nbsp;email';
$BL['be_cnt_tablealign']                = 'poravnanje&nbsp;tabele';
$BL['be_cnt_labelname']                 = 'ime&nbsp;oznake';
$BL['be_cnt_labelsubsc']                = 'oznaka&nbsp;pretpl.';
$BL['be_cnt_allsubsc']                  = 'sve&nbsp;pretpl.';
$BL['be_cnt_default']                   = 'default';
$BL['be_cnt_left']                      = 'lijevo';
$BL['be_cnt_center']                    = 'centar';
$BL['be_cnt_right']                     = 'desno';
$BL['be_cnt_buttontext']                = 'tekst&nbsp;dugmeta';
$BL['be_cnt_successtext']               = 'tekst&nbsp;o uspjehu';
$BL['be_cnt_regmail']                   = 'registruj email';
$BL['be_cnt_logoffmail']                = 'odjavi email';
$BL['be_cnt_changemail']                = 'promijeni email';
$BL['be_cnt_openimagebrowser']          = 'otvori preglednik slika';
$BL['be_cnt_openfilebrowser']           = 'otvori preglednik datoteka';
$BL['be_cnt_sortup']                    = 'pomjeri gore';
$BL['be_cnt_sortdown']                  = 'pomjeri dole';
$BL['be_cnt_delimage']                  = 'odstrani oznacenu sliku';
$BL['be_cnt_delfile']                   = 'odstrani oznacenu datoteku';
$BL['be_cnt_delmedia']                  = 'odstrani oznaceni medij';
$BL['be_cnt_column']                    = 'kolona';
$BL['be_cnt_imagespace']                = 'razmak&nbsp;slike';
$BL['be_cnt_directlink']                = 'direktni link';
$BL['be_cnt_target']                    = 'meta';
$BL['be_cnt_target1']                   = 'u novom prozoru';
$BL['be_cnt_target2']                   = 'u ramu trenutnog prozora  ';
$BL['be_cnt_target3']                   = 'u istom prozoru bez ramova';
$BL['be_cnt_target4']                   = 'u istom ramu ili prozoru';
$BL['be_cnt_bullet']                    = 'lista (tabela)';
$BL['be_cnt_ullist']                    = 'lista';
$BL['be_cnt_ullist_desc']               = '~ = prvi nivo, &nbsp; ~~ = drugi nivo, &nbsp; itd.';
$BL['be_cnt_linklist']                  = 'lista linkova';
$BL['be_cnt_plainhtml']                 = 'cisti html';
$BL['be_cnt_files']                     = 'datoteke';
$BL['be_cnt_description']               = 'opis';
$BL['be_cnt_linkarticle']               = 'link na clanak';
$BL['be_cnt_articles']                  = 'clanci';
$BL['be_cnt_movearticleto']             = 'pomjeri oznaceni clanak u link na listu clanaka';
$BL['be_cnt_removearticleto']           = 'odstrani oznaceni clanak iz linkova u listi clanaka';
$BL['be_cnt_mediatype']                 = 'tip medija';
$BL['be_cnt_control']                   = 'kontrola';
$BL['be_cnt_showcontrol']               = 'pokazi kontrolnu liniju';
$BL['be_cnt_autoplay']                  = 'autostart';
$BL['be_cnt_source']                    = 'izvor';
$BL['be_cnt_internal']                  = 'interni';
$BL['be_cnt_openmediabrowser']          = 'otvori preglednik medija';
$BL['be_cnt_external']                  = 'eksterni';
$BL['be_cnt_mediapos0']                 = 'lijevo (default)';
$BL['be_cnt_mediapos1']                 = 'centar';
$BL['be_cnt_mediapos2']                 = 'desno';
$BL['be_cnt_mediapos3']                 = 'blok, lijevo';
$BL['be_cnt_mediapos4']                 = 'blok, desno';
$BL['be_cnt_mediapos0i']                = 'poravnaj medij iznad i lijevo od tekst bloka';
$BL['be_cnt_mediapos1i']                = 'poravnaj medij iznad i centriraj sa tekst blokom';
$BL['be_cnt_mediapos2i']                = 'poravnaj medij iznad i desno od tekst bloka';
$BL['be_cnt_mediapos3i']                = 'poravnaj medij lijevo u tekst bloku';
$BL['be_cnt_mediapos4i']                = 'poravnaj medij desno u tekst bloku';
$BL['be_cnt_setsize']                   = 'postavi velicinu';
$BL['be_cnt_set1']                      = 'postavi na 160x120px';
$BL['be_cnt_set2']                      = 'postavi na 240x180px';
$BL['be_cnt_set3']                      = 'postavi na 320x240px';
$BL['be_cnt_set4']                      = 'postavi na 480x360px';
$BL['be_cnt_set5']                      = 'odstrani sirinu i visinu medija';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'napravi novi raspored stranice';
$BL['be_admin_page_name']               = 'ime rasporeda';
$BL['be_admin_page_edit']               = 'izmjeni raspored';
$BL['be_admin_page_render']             = 'generisanje';
$BL['be_admin_page_table']              = 'tabela';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'prilagodjeno';
$BL['be_admin_page_custominfo']         = 'iz glavnog bloka predloska';
$BL['be_admin_tmpl_layout']             = 'raspored';
$BL['be_admin_tmpl_nolayout']           = 'Nema dostupan raspored stranice!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'pretraga';
$BL['be_cnt_results']                   = 'rezultati';
$BL['be_cnt_results_per_page']          = 'po&nbsp;stranici (prazno - pokazi sve)';
$BL['be_cnt_opennewwin']                = 'otvori novi prozor';
$BL['be_cnt_searchlabeltext']           = 'ovo je predefinisani tekst i vrijednosti za formu pretrage.';
$BL['be_cnt_input']                     = 'ulaz';
$BL['be_cnt_style']                     = 'stil';
$BL['be_cnt_result']                    = 'rezultat';
$BL['be_cnt_next']                      = 'slijedece';
$BL['be_cnt_previous']                  = 'prethodno';
$BL['be_cnt_align']                     = 'poravnaj';
$BL['be_cnt_searchformtext']            = 'slijedeci tekstovi se prikazuju kod ispisa rezultata uspjesne/neuspjesne pretrage.';
$BL['be_cnt_intro']                     = 'uvod';
$BL['be_cnt_noresult']                  = 'nema rezultata';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'onemoguci';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'vlasnik clanka';
$BL['be_article_adminuser']             = 'admin korisnik';
$BL['be_article_username']              = 'autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'vidljivo samo za prijavljene korisnike';
$BL['be_admin_struct_status']           = 'frontend meni status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'meni clanaka';
$BL['be_cnt_sitelevel']                 = 'nivo stranice';
$BL['be_cnt_sitecurrent']               = 'trenutni nivo stranice';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'backend tekst';
$BL['be_ctype_ecard']                   = 'e-card';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'naslov/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail predlozak';
$BL['be_cnt_ecard_image']               = 'e-card slika';
$BL['be_cnt_ecard_title']               = 'e-card naslov';
$BL['be_cnt_alignment']                 = 'poravnanje';
$BL['be_cnt_ecardform']                 = 'predlozak forme';
$BL['be_cnt_ecardform_err']             = 'Sva polja oznacena sa * su obavezna';
$BL['be_cnt_ecardform_sender']          = 'Posaljioc';
$BL['be_cnt_ecardform_recipient']       = 'Primaoc';
$BL['be_cnt_ecardform_name']            = 'Ime';
$BL['be_cnt_ecardform_msgtext']         = 'Vasa poruka';
$BL['be_cnt_ecardform_button']          = 'posalji e-card';
$BL['be_cnt_ecardsend']                 = 'poslani predlosci';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend pocetni tekst';
$BL['be_admin_startup_text']            = 'pocetni tekst';
$BL['be_admin_startup_button']          = 'OK';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'knjiga gostiju/komentari';
$BL['be_cnt_guestbook_listing']         = 'izlistavanje';
$BL['be_cnt_guestbook_listing_all']     = 'izlistaj&nbsp;sve&nbsp;unose';
$BL['be_cnt_guestbook_list']            = 'izlistaj';
$BL['be_cnt_guestbook_perpage']         = 'po&nbsp;stranici';
$BL['be_cnt_guestbook_form']            = 'forma';
$BL['be_cnt_guestbook_signed']          = 'potpisan';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'ispred';
$BL['be_cnt_guestbook_after']           = 'iza';
$BL['be_cnt_guestbook_entry']           = 'unos';
$BL['be_cnt_guestbook_edit']            = 'izmjeni';
$BL['be_cnt_ecardform_selector']        = 'selektor';
$BL['be_cnt_ecardform_radiobutton']     = 'radio dugme';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript funkcionalnost';
$BL['be_cnt_ecardform_over']            = 'na mis iznad - onMouseOver';
$BL['be_cnt_ecardform_click']           = 'na klik - onClick';
$BL['be_cnt_ecardform_out']             = 'na mis van - nMouseOut';
$BL['be_admin_struct_topcount']         = 'broj clanka na vrhu';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'newsletter';
$BL['be_newsletter_addnl']              = 'dodaj newsletter';
$BL['be_newsletter_titleeditnl']        = 'izmjeni newsletter';
$BL['be_newsletter_newnl']              = 'novi';
$BL['be_newsletter_button_savenl']      = 'snimi newsletter';
$BL['be_newsletter_fromname']           = 'od';
$BL['be_newsletter_fromemail']          = 'od email';
$BL['be_newsletter_replyto']            = 'odgovor na email';
$BL['be_newsletter_changed']            = 'zadnja izmjena';
$BL['be_newsletter_placeholder']        = 'pozicioner';
$BL['be_newsletter_htmlpart']           = 'HTML newsletter sadrzaj';
$BL['be_newsletter_textpart']           = 'TEKST newsletter sadrzaj';
$BL['be_newsletter_allsubscriptions']   = 'sve pretplate';
$BL['be_newsletter_verifypage']         = 'verificiraj link';
$BL['be_newsletter_open']               = 'HTML i TEKST ulaz';
$BL['be_newsletter_open1']              = '(klikni na sliku da je otvoris)';
$BL['be_newsletter_sendnow']            = 'posalji newsletter';
$BL['be_newsletter_attention']          = 'Jeste li sigurni da zelite poslati na vise adresa';
$BL['be_newsletter_attention1']         = 'Ako ste napravili neke promjene, snimite ih prvo, jer inace nece biti upotrebljene.';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'posalji newsletter';
$BL['be_newsletter_sendprocess']        = 'posalji proces';
$BL['be_newsletter_attention2']         = 'Molimo ne prekidajte proces slanja.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">test email adresa <strong>###TEST###</strong> nije validna!<br />&nbsp;<br />Pokusajte ponovo!';
$BL['be_newsletter_to']                 = 'primaoci';
$BL['be_newsletter_ready']              = 'slanje newsletter-a: URADJENO';
$BL['be_newsletter_readyfailed']        = 'Neuspjesno slanje newsletter-a za';
$BL['be_subnav_msg_subscribers']        = 'newsletter pretplatnici';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'mapa stranice';
$BL['be_cnt_sitemap_catimage']          = 'ikonica nivoa';
$BL['be_cnt_sitemap_articleimage']      = 'ikonica clanka';
$BL['be_cnt_sitemap_display']           = 'prikaz';
$BL['be_cnt_sitemap_structuronly']      = 'samo strukturni nivoi';
$BL['be_cnt_sitemap_structurarticle']   = 'strukturni nivoi + clanci';
$BL['be_cnt_sitemap_catclass']          = 'nivoi klasa';
$BL['be_cnt_sitemap_articleclass']      = 'klase clanaka';
$BL['be_cnt_sitemap_count']             = 'brojac';
$BL['be_cnt_sitemap_classcount']        = 'dodaj imenima klasa';
$BL['be_cnt_sitemap_noclasscount']      = 'nemoj dodati imenima klasa';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'bid';
$BL['be_cnt_bid_bidtext']               = 'bid tekst';
$BL['be_cnt_bid_sendtext']              = 'poslani tekst';
$BL['be_cnt_bid_verifiedtext']          = 'verificirani tekst';
$BL['be_cnt_bid_errortext']             = 'bid izbrisan';
$BL['be_cnt_bid_verifyemail']           = 'verificiraj email';
$BL['be_cnt_bid_startbid']              = 'startaj bid';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'povecaj&nbsp;za';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'eksterni sadrzaj';
$BL['be_cnt_pages_select']              = 'selektuj datoteku';
$BL['be_cnt_pages_fromfile']            = 'datoteka iz strukture';
$BL['be_cnt_pages_manually']            = 'put/datoteka ili adresa';
$BL['be_cnt_pages_cust']                = 'datoteka/adresa';
$BL['be_cnt_pages_from']                = 'izvor';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover slike';
$BL['be_cnt_reference_basis']           = 'poravnanje';
$BL['be_cnt_reference_horizontal']      = 'horizontalno';
$BL['be_cnt_reference_vertical']        = 'verticalno';
$BL['be_cnt_reference_aligntext']       = 'male referencne slike';
$BL['be_cnt_reference_largetext']       = 'velike referencne slike';
$BL['be_cnt_reference_zoom']            = 'zum';
$BL['be_cnt_reference_middle']          = 'srednje';
$BL['be_cnt_reference_border']          = 'okvir';
$BL['be_cnt_reference_block']           = 'sirina i visina bloka';

// added: 31-05-2004
$BL['be_article_rendering']             = 'renderiranje';
$BL['be_article_nosummary']             = 'ne prikazuj zakljucak u kompletnom clanku';
$BL['be_article_forlist']               = 'lista clanaka';
$BL['be_article_forfull']               = 'prikazi kompletan clanak';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ATTENTION!</strong> The &quot;SETUP&quot; directory still exists! Delete that directory - it\'s potential security problem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'zabranjene rijeci';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'postavi cookie';
$BL['be_cnt_guestbook_allowed']         = 'odobri opet kasnije';
$BL['be_cnt_guestbook_seconds']         = 'sekundi';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Da li stvarno zelite da obrisete \nsve datoteke iz smeca?";
$BL['be_ftrash_delallfiles']            = 'izbrisi sve datoteke iz smeca';

// added: 16-08-2004
// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'ubaci CSV pretplatnika';
$BL['be_newsletter_importtitle']        = 'ubaci Newsletter pretplatnika';
$BL['be_newsletter_entriesfound']       = 'nadjenih&nbsp;unosa';
$BL['be_newsletter_foundinfile']        = 'u datoteci';
$BL['be_newsletter_addresses']          = 'adrese';
$BL['be_newsletter_csverror']           = 'ubacena CSV datoteka nije ispravna!';
$BL['be_newsletter_addressesadded']     = 'adrese dodane';
$BL['be_newsletter_newimport']          = 'importuj';
$BL['be_newsletter_importerror']        = 'slijedeci podaci nisu ispravni:';
$BL['be_newsletter_shouldbe1']          = 'CSV/TXT file treba biti u formatu:';
$BL['be_newsletter_shouldbe2']          = 'default = <b>;</b>';
$BL['be_newsletter_sample']             = 'primjer';
$BL['be_newsletter_selectCSV']          = 'selektuj CSV file';
$BL['be_newsletter_delimeter']          = 'delimeter';
$BL['be_newsletter_importCSV']          = 'ubaci CSV dat.';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'narucivanje dodjeljenih clanaka';
$BL['be_admin_struct_orderdate']        = 'datum kreiranja';
$BL['be_admin_struct_orderchangedate']  = 'datum izmjene';
$BL['be_admin_struct_orderstartdate']   = 'pocetni datum';
$BL['be_admin_struct_orderdesc']        = 'opadajuce';
$BL['be_admin_struct_orderasc']         = 'rastuce';
$BL['be_admin_struct_ordermanual']      = 'rucno (strelice gore/dole)';
$BL['be_cnt_sitemap_startid']           = 'pocinje u';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'mapa';
$BL['be_save_btn']                      = 'Snimi';
$BL['be_cmap_location_error_notitle']   = 'upisi naslov ove lokacije.';
$BL['be_cnt_map_add']                   = 'dodaj lokaciju';
$BL['be_cnt_map_edit']                  = 'izmjeni lokaciju';
$BL['be_cnt_map_title']                 = 'naslov lokacije';
$BL['be_cnt_map_info']                  = 'unos/info';
$BL['be_cnt_map_list']                  = 'lista lokacija';
$BL['be_btn_delete']                    = 'Da li stvarno zelite da \nizbrisete ovu lokaciju?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP varijable';
$BL['be_cnt_vars']                      = 'varijable';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'kopiraj sadrzaj';
$BL['be_func_struct_nocopy']            = 'iskljuci kopiranje sadrzaja';
$BL['be_func_struct_copy_level']        = 'kopiraj nivo strukture';
$BL['be_func_struct_no_copy']           = "Nije moguce kopirati ovaj nivo!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minuta';
$BL['be_date_minutes']                  = 'minute';
$BL['be_date_hour']                     = 'sat';
$BL['be_date_hours']                    = 'sati';
$BL['be_date_day']                      = 'dan';
$BL['be_date_days']                     = 'dana';
$BL['be_date_week']                     = 'sedmica';
$BL['be_date_weeks']                    = 'sedmica';
$BL['be_date_month']                    = 'mjesec';
$BL['be_date_months']                   = 'mjeseci';
$BL['be_off']                           = 'iskljuceno';
$BL['be_on']                            = 'ukljuceno';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'timeout';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'grupe korisnika';
$BL['be_admin_group_add']               = 'dodaj grupu';
$BL['be_admin_group_nogroup']           = 'nije nadjena grupa korisnika';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'lista foruma';
$BL['be_forum_title']                   = 'naziv foruma';
$BL['be_forum_permission']              = 'dozvole';
$BL['be_forum_add']                     = 'dodaj forum';
$BL['be_forum_titleedit']               = 'izmjeni forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'prilagodjen';
$BL['be_show_content']                  = 'prikaz';
$BL['be_main_content']                  = 'glavna kolona';
$BL['be_admin_template_jswarning']      = 'Upozorenje!!! \nPrilagodjeni blokovi se mogu promjeniti! \n\nAko odustanete \nresetujte vas izgled stranica! \n\nPromjeniti predlozak?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS feed';
$BL['be_cnt_rssfeed_url']               = 'RSS adresa';
$BL['be_cnt_rssfeed_item']              = 'jedinica';
$BL['be_cnt_rssfeed_max']               = 'max.';
$BL['be_cnt_rssfeed_cut']               = 'sakrij jedinicu liste';

$BL['be_ctype_simpleform']              = 'email kontakt forma';

$BL['be_cnt_onsuccess']                 = 'na uspjeh';
$BL['be_cnt_onerror']                   = 'na gresku';
$BL['be_cnt_onsuccess_redirect']        = 'redirekcija na uspjeh';
$BL['be_cnt_onerror_redirect']          = 'redirekcija na gresku';

$BL['be_cnt_form_class']                = 'klasa forme';
$BL['be_cnt_label_wrap']                = 'omotati labelu';
$BL['be_cnt_error_class']               = 'klasa greske';
$BL['be_cnt_req_mark']                  = 'potrebno obiljezje';
$BL['be_cnt_mark_as_req']               = 'obiljezi kao potrebno';
$BL['be_cnt_mark_as_del']               = 'obiljezi dio za brisati';


$BL['be_cnt_type']                      = 'tip';
$BL['be_cnt_label']                     = 'labela';
$BL['be_cnt_needed']                    = 'potrebno';
$BL['be_cnt_delete']                    = 'izbrisi';
$BL['be_cnt_value']                     = 'vrijednost';
$BL['be_cnt_error_text']                = 'tekst greske';
$BL['be_cnt_css_style']                 = 'CSS stil';
$BL['be_cnt_css_class']                 = 'CSS klasa';
$BL['be_cnt_send_copy_to']              = 'CC ka';

$BL['be_cnt_field']                     = array("text"=>'text (single-line)', "email"=>'email', "textarea"=>'text (multi-line)',
                                                "hidden"=>'hidden', "password"=>'password', "select"=>'select menu',
                                                "list"=>'list menu', "checkbox"=>'checkbox', "radio"=>'radio button',
                                                "upload"=>'file', "submit"=>'send button', "reset"=>'reset button',
                                                "break"=>'break', "breaktext"=>'break text', "special"=>'text (spezial)',
                                                "captchaimg"=>'captcha image', "captcha"=>'captcha code', 'newsletter'=>'newsletter');

$BL['be_cnt_access']                    = 'pristup';
$BL['be_cnt_activated']                 = 'aktivirano';
$BL['be_cnt_available']                 = 'dostupno';
$BL['be_cnt_guests']                    = 'gostiju';
$BL['be_cnt_admin']                     = 'admin';
$BL['be_cnt_write']                     = 'pisi';
$BL['be_cnt_read']                      = 'citaj';

$BL['be_cnt_no_wysiwyg_editor']         = 'iskljuci WYSIWYG editor';
$BL['be_cnt_cache_update']              = 'resetuj cache';
$BL['be_cnt_cache_delete']              = 'izbrisi cache';
$BL['be_cnt_cache_delete_msg']          = 'Da li stvarno zelite da izbrisete cache?  \nOvo moze djelovati na pretragu.  \n';

$BL['be_admin_usr_issection']           = 'sekcija za prijavu';
$BL['be_admin_usr_ifsection0']          = 'frontend';
$BL['be_admin_usr_ifsection1']          = 'backend';
$BL['be_admin_usr_ifsection2']          = 'frontend and backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'izmjeni sadrzaj ovog clanka';
$BL['be_func_content_paste0']            = 'zalijepi u clanak';
$BL['be_func_content_paste']             = 'zalijepi sadrzaj ovog clanka';
$BL['be_func_content_cut']               = 'izrezi sadrzaj ovog clanka';
$BL['be_func_content_no_cut']            = "Nije moguce izrezati sadrzaj ovog clanka!";
$BL['be_func_content_copy']              = 'kopiraj sadrzaj ovog clanka';
$BL['be_func_content_no_copy']           = "Nije moguce kopirati sadrzaj ovog clanka!";
$BL['be_func_content_paste_cancel']      = 'odustani od izmjene sadrzaja ovog clanka';

$BL['be_cnt_move_deleted'] = 'odstrani obrisane datoteke';
$BL['be_cnt_move_deleted_msg'] = 'Da li stvarno zelite da pomjerite datoteke  \noznacene kao obrisane u specijalni direktorij za brisanje?  \n';

$BL['be_admin_struct_permit'] = 'autorizovano za pristup (ostavite prazno da svi mogu pristupiti)';
$BL['be_admin_struct_adduser_all']   = 'preuzmi sve korisnike';
$BL['be_admin_struct_adduser_this']  = 'preuzmi oznacene korisnike';
$BL['be_admin_struct_remove_all']    = 'odstrani sve korisnike';
$BL['be_admin_struct_remove_this']   = 'odstrani oznacene korisnike';


$BL['be_ctype_alias'] = 'alias za dio sadrzaja';
$BL['be_cnt_setting'] = 'preuzmi';
$BL['be_cnt_spaces'] = 'razmak aliasa za dio sadrzaja';
$BL['be_cnt_toplink'] = 'podesavanja linka za vrh aliasa za dio sadrzaja';
$BL['be_cnt_block'] = 'prikazi (blok) podesavanja aliasa za dio sadrzaja';
$BL['be_cnt_title'] = 'naslovi aliasa za dio sadrzaja';

$BL['be_file_replace'] = 'Premjesti datoteke';

$BL['be_alias_articleID'] = 'ID broj aliasa';
$BL['be_alias_useAll'] = "iskoristi naslov ovog sadrzaja";
$BL['be_article_morelink'] = '[vise&#8230;] adresa';
$BL['be_admin_tmpl_copy']               = 'kopiraj predlozak';

$BL['be_ctype_filelist1']                = 'lista datoteka';
$BL['be_cnt_fpro_usecaption']            = 'iskoristi centar datoteka &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']                = 'Kljucne rijeci';
$BL['be_admin_keywords_key']            = 'Kljucna rijec';
$BL['be_admin_keywords_err']            = 'Unesi jedinstvenu kljucnu rijec';
$BL['be_admin_keyword_edit']            = 'izmjeni kljucnu rijec';
$BL['be_admin_keyword_del']             = 'izbrisi kljucnu rijec';
$BL['be_admin_keyword_delmsg']          = 'Da li stvarno zelite da\nizbrisete kljucnu rijec?';
$BL['be_admin_keyword_add']             = 'dodaj kljucnu rijec';

$BL['be_cnt_transparent'] = 'Flash transparentno';


// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']   = 'datum isteka (terminacije)';
$BL['be_func_switch_contentpart'] = 'Jeste li sigurni da zelite promjeniti tip sadrzaja? \n\nBudite pazljivi! \nVazne postavke mogu biti izbrisane! \n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>UPOZORENJE!</strong> The &quot;CODE-SNIPPETS&quot; directory still exists! Delete directory <strong>phpwcms_code_snippets</strong> - this is a potential security problem.';

$BL['be_ctype_poll'] = 'poll';
$BL['be_cnt_pos8']                      = 'tabela, lijevo';
$BL['be_cnt_pos9']                      = 'tabela, desno';
$BL['be_cnt_pos8i']                     = 'poravnaj sliku lijevo u tabeli';
$BL['be_cnt_pos9i']                     = 'poravnaj sliku desno u tabeli';


$BL['be_WYSIWYG']                       = 'WYSIWYG editor';
$BL['be_WYSIWYG_disabled']              = 'WYSIWYG editor iskljucen';
$BL['be_admin_struct_acat_hiddenactive'] = 'vidljivo dok je aktivno';



$BL['be_login_jsinfo']                  = 'Molimo ukljucite JavaScript-u!';

$BL['be_admin_struct_maxlist']          = 'maksimalan broj clanaka u modu liste';

$BL['be_admin_optgroup_label']          = array(1 => 'text', 2 => 'image', 3 => 'form', 4 => 'admin', 5 => 'special');
$BL['be_cnt_articlemenu_maxchar']       = 'max. Chars';

$BL['be_cnt_sysadmin_system']           = 'system';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date']               = 'Vasa instalacija je svjeza, nema novih nadogradnji.';
$BL['Version_not_up_to_date']           = 'Vasa instalacija  <b>nije</b> svjeza. Postoje nadogradnje. Kontaktirajte svog administratora.';
$BL['Latest_version_info']              = 'Najnovija dostupna verzija <b>phpwcms %s</b>.';
$BL['Current_version_info']             = 'Vasa verzija <b>phpwcms %s</b>.';
$BL['Connect_socket_error']             = 'Nije moguce pristupiti serveru. Kod greske je:<br />%s';
$BL['Socket_functions_disabled']        = 'Nije moguce iskoristiti funkciju "socket-a".';
$BL['Mailing_list_subscribe_reminder']  = 'Za informacije o najnovijim verzijama, zasto se ne pretplatite na nasu <a href="http://eepurl.com/bm-BrH" target="_blank">e-mail listu</a>.';
$BL['Version_information']              = 'phpwcms-info o verziji';

$BL['be_cnt_search_highlight']          = 'osvijetli';
$BL['be_cnt_results_wordlimit']         = 'maksimalno rijeci za sazetak';
$BL['be_cnt_page_of_pages']             = 'navi pretrage';
$BL['be_cnt_page_of_pages_descr']       = '{PREV:Back} page #/##, result ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Next}';
$BL['be_cnt_search_show_top']           = 'vrh';
$BL['be_cnt_search_show_bottom']        = 'dno';
$BL['be_cnt_search_show_next']          = 'slijedece (takodje ako nema linka)';
$BL['be_cnt_search_show_prev']          = 'prethodno (takodje ako nema linka)';
$BL['be_cnt_search_show_forall']        = 'uvijek prikazi';
$BL['be_cnt_search_startlevel']         = 'zapocni pretragu';
$BL['be_cnt_results_minchar']           = 'minimalno karaktera za pretragu';

$BL['be_cnt_pagination']                = 'linkuj dijelove clanaka';
$BL['be_article_pagination']            = 'linkuj clanke';
$BL['be_article_per_page']              = 'clanaka po stranici';
$BL['be_pagination']                    = 'linkovanje';


$BL['be_ctype_recipe']                  = 'recept';
$BL['be_ctype_faq']                     = 'faq';
$BL['be_cnt_additional']                = 'dodaci';
$BL['be_cnt_question']                  = 'pitanje';
$BL['be_cnt_answer']                    = 'odgovor';
$BL['be_cnt_same_as_summary']           = 'iskoristi podatke o slici clanka';
$BL['be_cnt_sorting']                   = 'sortiranje';
$BL['be_cnt_imgupload']                 = 'ubaci&nbsp;sliku';
$BL['be_cnt_filesize']                  = 'velicina datoteke';
$BL['be_cnt_captchalength']             = 'duzina captcha koda';
$BL['be_cnt_chars']                     = 'karaktera';
$BL['be_cnt_download']                  = 'download';
$BL['be_cnt_download_direct']           = 'direktan';
$BL['be_cnt_database']                  = 'baza podataka';
$BL['be_cnt_formsave_in_db']            = 'snimi rezultate forme';

$BL['be_cnt_email_notify']              = 'obavijesti email-om';
$BL['be_cnt_notify_by_email']           = 'posalji email-om za';
$BL['be_cnt_last_edited']               = 'zadnja izmjena';

$BL['be_cnt_export_selection']          = 'eksportuj selekciju';
$BL['be_cnt_delete_duplicates']         = 'izbrisi duplikate';
$BL['be_cnt_new_recipient']             = 'dodaj primaoca';


$BL['be_cnt_newsletter_prepare']        = 'aktivni newsletter';
$BL['be_cnt_newsletter_prepare1']       = 'svi primaoci ce biti stavljeni u red za slanje';
$BL['be_cnt_newsletter_prepare2']       = 'red za slanje ce biti osvjezen&#8230;';

$BL['be_cnt_export']                    = 'eksport';
$BL['be_cnt_formsave_profile']          = 'snimi podatke licnog profila';
$BL['be_profile_label_add']             = 'dodaci';
$BL['be_profile_label_website']         = 'web adresa';
$BL['be_profile_label_gender']          = 'pol';
$BL['be_profile_label_birthday']        = 'rodjendan';

$BL['be_cnt_store_in']                  = 'snimi u polje';
$BL['be_aboutlink_title']               = 'info uo sistemu i licencama';

$BL['be_shortdate']                     = 'n/j/y';
$BL['be_shortdatetime']                 = 'n/j/y G:i';

$BL['be_confirm_sending']               = 'potvrdi slanje';
$BL['be_confirm_text']                  = 'Da, zelim poslati newsletter svim primaocima!';

$BL['be_cnt_queued']                    = 'u redu cekanja';
$BL['be_last_sending']                  = 'zadnje poslano';
$BL['be_last_edited']                   = 'zadnje izmjenjeno';
$BL['be_total']                         = 'ukupno';

$BL['be_settings']                      = 'postavke';
$BL['be_ctype']                         = 'dio clanka';
$BL['be_selection']                     = 'selekcija';

$BL['be_ctype_module']                  = 'plug-in';
$BL['be_cnt_lightbox']                  = 'galerija slika';
$BL['be_cnt_behavior']                  = 'ponasanje';
$BL['be_cnt_imglist_nocaption']         = 'sakrij caption za thumbnails';

$BL['be_ctype_felogin']                 = 'prijava na "frontend" stranicu';
$BL['be_cookie_runtime']                = 'cookie istice';
$BL['be_locale']                        = 'lokalno';
$BL['be_date_format']                   = 'format datuma';

$BL['be_check_login_against']           = 'kontrola prijave';
$BL['be_userprofile_db']                = 'baza korisnickog profila';
$BL['be_backenduser_db']                = '"backend" korisnicka baza';

$BL['be_gb_post_login']                 = 'postavi samo trenutno prijavljenim korisnicima';
$BL['be_gb_show_login']                 = 'prikazi samo trenutno prijavljenim korisnicima';
$BL['be_gb_urlcheck']                   = 'omoguci udaljnu URL validaciju';
$BL['be_order']                         = 'uredi';

$BL['be_unique_teaser_entry']           = 'prikazi teaser/link clanka samo jednom po stranici';
$BL['be_allowed_tags']                  = 'dozvoljeni tagovi';
$BL['be_fe_login_url']                  = 'FE login url';
$BL['be_ctype_imagesdiv']               = 'slike &lt;div&gt;';
$BL['be_cnt_imagecenter']               = 'horizontalni/vertikalni centar';

$BL['be_overwrite_default']             = 'Ovo ce prebrisati sadrzaj config fajla';
$BL['be_cnt_sortvalue']                 = 'sortiraj&nbsp;vrijednost';
$BL['be_dialog_warn_nosave']            = 'Ako nastavite, promjene nece biti sacuvane!\nDa li zelite da nastavite?';
$BL['be_cnt_paginate_subsection']       = 'podsekcija';
$BL['be_cnt_subsection_tite']           = 'naslov podsekcije';
$BL['be_cnt_subsection_warning']        = 'Linkovanje podsekcija (linkovanje dijelova clanaka) \n je omoguceno samo za glavnu kolonu!';

$BL['be_no_search']                     = 'bez pretrage';
$BL['be_priorize']                      = 'prioritizacija';
$BL['be_change_articleID']              = 'promjeni ID clanka';
$BL['be_title_wrap']                    = 'Prelomi naslov clanka';

$BL['be_no_rss']                        = 'RSS';
$BL['be_article_urlalias']              = 'alias clanka';

$BL['be_image_crop']                    = '"crop" male slike';
$BL['be_image_cropit']                  = '"crop" slike';
$BL['be_image_align']                   = 'poravnanje slike';

$BL['be_ctype_flashplayer']             = 'flash media player';
$BL['be_flashplayer_caption']           = 'uvod';
$BL['be_flashplayer_thumbnail']         = 'mala slika';
$BL['be_flashplayer_selectsize']        = 'Odaberi velicinu player-a';

$BL['be_check_feuser_profile']          = 'frontend korisnicki profil';
$BL['be_check_feuser_registration']     = 'registracija';
$BL['be_check_feuser_manage']           = 'rukovan od strane korisnika';
$BL['be_hide_active_articlelink']       = 'sakrij aktivni clanaka u meniju clanaka';

$BL['be_module_search']                 = 'takodjer pretrazi';

$BL['be_ctype_imagesspecial']           = 'slike - specijal';
$BL['be_image_WxHpx']                   = 'S x V px';
$BL['be_fx_1']                          = 'efekt 1';
$BL['be_fx_2']                          = 'efekt 2';
$BL['be_fx_3']                          = 'efekt 3';
$BL['be_image_zoom']                    = 'zumirani pogled';
$BL['be_image_delete_js']               = 'Da li zelite da obrisete oznacene slike?';

$BL['be_news']                          = 'News - Vijesti';
$BL['be_news_create']                   = 'Kreiraj unos nove vijesti';
$BL['be_tags']                          = 'tagovi';
$BL['be_title']                         = 'naslov';
$BL['be_delete_dataset']                = 'Izbrisi oznaceni set podataka?';
$BL['be_action_notvalid']               = 'Vasa zadnja oznacena akcija je odbacena zato sto nije validna!';
$BL['be_action_deleted']                = 'Oznaceni set podataka sa ID {ID} je obrisan.';
$BL['be_action_status']                 = 'Status oznacenog seta podataka sa ID {ID} je izmjenjen.';
$BL['be_data_select_failed']            = 'Pristup oznacenom setu podataka nije uspio. Molimo provjerite vasu selekciju.';
$BL['be_alias']                         = 'alias';
$BL['be_url_value']                     = 'naslov URL-a';
$BL['default_date_format']              = 'DD/MM/YYYY';
$BL['default_date']                     = 'd/m/Y'; // do not use something diffrent than "d, m, Y" here
$BL['default_date_delimiter']           = '/';
$BL['default_time_format']              = 'HH:MM';
$BL['default_time']                     = 'H:i';  // do not use something diffrent than "H, i" here
$BL['be_place']                         = 'mjesto';
$BL['be_teasertext']                    = 'tekst teaser-a';
$BL['be_published']                     = 'objavi';
$BL['be_show_archived']                 = 'omoguceno i nakon datuma isteka (arhiva)';
$BL['be_save_copy']                     = 'spasi unos kao duplikat';
$BL['be_read_more_link']                = 'vise URL/ID';
$BL['be_news_name_mandatory']           = "Popunite naslov vijesti. To je obavezno!";
$BL['be_successfully_saved']            = 'Svi podaci su uspjesno snimljeni!';
$BL['be_successfully_updated']          = 'Svi podaci su uspjesno izmjenjeni!';
$BL['be_error_while_save']              = 'Snimanje podataka je neuspjesno.';
$BL['be_copyright']                     = 'copyright';
$BL['be_file_multiple_upload']          = 'ubacivanje vise datoteka';
$BL['be_files_browse']                  = 'Izaberite datoteke';
$BL['be_files_upload']                  = 'Ubacite oznacene datoteke';
$BL['be_archive']                       = 'arhiva';
$BL['be_off']                           = 'iskljuceno';
$BL['be_on']                            = 'ukljuceno';
$BL['be_random']                        = 'nasumicno';
$BL['be_sorted']                        = 'sortirano';
$BL['be_granted_download']              = 'samo "secure download" na frontend-u';
$BL['be_granted_feuser']                = 'samo za prijavljene korisnike na frontend-u';

$BL['be_ctype_tabs']                    = 'tabovi';
$BL['be_tab_add']                       = 'dodaj tab';
$BL['be_tab_name']                      = 'tab';
$BL['be_headline']                      = 'naslov';
$BL['be_tab_delete_js']                 = 'Da li zelite da izbrisete oznaceni tab?';

$BL['be_pagniate_count']                = 'artikala po stranici';
$BL['be_limit_to']                      = 'ogranici na';
$BL['be_archived_items']                = 'arhivirani artikli';
$BL['be_include']                       = 'ukljucujuci';
$BL['be_exclude']                       = 'iskljucujuci';
$BL['be_solely']                        = 'iskljucivo';
$BL['be_fsearch_not']                   = 'NE';
$BL['be_date_year']                     = 'godina';
$BL['be_archive_link']                  = 'arhiviraj link';
$BL['be_use_prio']                      = 'primjeni prioritizaciju';
$BL['be_skip_first_items']              = 'preskoci artikle na vrhu';
$BL['be_news_detail_link']              = 'clanak vijesti';

$BL['be_gallerydownload']               = 'omoguci download u galeriji';
$BL['be_gallery_root']                  = 'galerija - glavni direktorij';
$BL['be_gallery_directory']             = 'galerija - poddirektorij';
$BL['be_gallery']                       = 'galerija';

$BL['be_sort_date']                     = 'datum sortiranja';

$BL['group_superuser']                  = 'superuser';
$BL['group_admin']                      = 'administrator';
$BL['group_editor']                     = 'editor';
$BL['group_newsletter']                 = 'editor newsletter-a';
$BL['group_client']                     = 'klijent';
$BL['group_guest']                      = 'gost';

$BL['php_function']                     = 'php funkcija';
$BL['article_menu_title']               = 'naslov menija';

$BL['content_type']                     = 'tip sadrzaja';
$BL['automatic']                        = 'automatski';

$BL['random_image']                     = 'nasumicna slika';
$BL['random_image_from_list']           = 'Oznaci jednu nasumicnu sliku iz liste slika';
