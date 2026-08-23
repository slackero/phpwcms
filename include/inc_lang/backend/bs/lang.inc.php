<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Language: Bosanski, Language Code: bs
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'korisnika online';

// Login Page
$BL["login_text"]                       = 'Unesite:';
$BL['login_error']                      = 'Greska prilikom unosa!';
$BL["login_username"]                   = 'korisnik';
$BL["login_userpass"]                   = 'sifra';
$BL["login_button"]                     = 'OK';
$BL["login_lang"]                       = 'jezik administracije';
$BL['login_forgot_password'] = 'Zaboravili ste lozinku?';
$BL['login_reset_title'] = 'Resetovanje lozinke';
$BL['login_reset_desc'] = 'Unesite svoje korisni&ccaron;ko ime ili e-mail adresu. Posla&cacute;emo vam siguran link za resetovanje lozinke.';
$BL['login_reset_button'] = 'Pošalji link za resetovanje';
$BL['login_reset_back'] = 'Nazad na prijavu';
$BL['login_reset_sent'] = 'Ako postoji aktivan ra&ccaron;un s ovim podacima, poslan je e-mail s uputama za resetovanje lozinke.';
$BL['login_reset_invalid_token'] = 'Ovaj link za resetovanje je nevaže&cacute;i ili je istekao. Zatražite novi link.';
$BL['login_reset_set_new_title'] = 'Postavljanje nove lozinke';
$BL['login_reset_set_new_desc'] = 'Unesite i potvrdite svoju novu lozinku.';
$BL['login_reset_new_password'] = 'Nova lozinka';
$BL['login_reset_repeat_password'] = 'Ponovite lozinku';
$BL['login_reset_password_mismatch'] = 'Lozinke se ne podudaraju!';
$BL['login_reset_password_empty'] = 'Lozinka ne može biti prazna!';
$BL['login_reset_success'] = 'Vaša lozinka je uspješno resetovana. Sada se možete prijaviti.';
$BL['login_reset_email_subject'] = 'Zahtjev za resetovanje lozinke za {SITE}';
$BL['login_reset_email_body'] = 'Pozdrav {NAME},&#13;&#13;Zaprimljen je zahtjev za resetovanje lozinke za vaš ra&ccaron;un ({LOGIN}) na {SITE}.&#13;&#13;Za postavljanje nove lozinke kliknite na sljede&cacute;i link:&#13;{RESET_LINK}&#13;&#13;Link vrijedi 1 sat. Ako niste zatražili resetovanje, možete ignorisati ovu poruku.&#13;&#13;Srda&ccaron;an pozdrav,&#13;{SITE}';

// phpwcms.php
$BL['be_nav_logout']           = 'Odjava';
$BL['be_nav_articles']         = '&Ccaron;lanci';
$BL['be_nav_files']            = 'Datoteke';
$BL['be_nav_modules']          = 'Moduli';
$BL['be_nav_messages']         = 'Poruke';
$BL['be_nav_chat']             = 'Chat';
$BL['be_nav_profile']          = 'Profil';
$BL['be_nav_admin']            = 'Administracija';
$BL['be_nav_discuss']          = 'Diskusija';
$BL['be_nav_collapse_menu'] = 'Skupi meni';

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
$BL['be_func_struct_del_jsmsg']         = 'da li stvarno zelite\nizbrisati clanak?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'kreiraj novi clanak u strukturnom nivou';
$BL['be_func_struct_new_article_short']   = 'kreiraj novi članak';
$BL['be_func_struct_paste_article']     = 'zalijepi clanak u strukturni nivo';
$BL['be_func_struct_insert_level']      = 'ubaci strukturni nivo u';
$BL['be_func_struct_insert_level_short'] = 'ubaci strukturni nivo';
$BL['be_func_struct_paste_level']       = 'zalijepi u strukturni nivo';
$BL['be_func_struct_cut_level']         = 'izrezi strukturni nivo';
$BL['be_func_struct_no_cut']            = "Ne moze se izrezati korijenski nivo!";
$BL['be_func_struct_no_paste1']         = "Ne moze se zalijepiti ovdje!";
$BL['be_func_struct_no_paste2']         = 'je "dijete" u korijenskoj liniji nivoa';
$BL['be_func_struct_no_paste3']         = 'treba zalijepiti ovdje';
$BL['be_func_struct_paste_cancel']      = 'otkazi promjenu strukturnog nivoa';
$BL['be_func_struct_del_struct']        = 'izbrisi strukturni nivo';
$BL['be_func_struct_del_sjsmsg']        = 'da li stvarno zelite\nda izbrisete strukturni nivo?'; // "\n" = JavaScript Linebreak
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
$BL['be_profile_2fa_title'] = 'Dvofaktorska autentifikacija (2FA)';
$BL['be_profile_2fa_text'] = 'Zaštitite svoj ra&ccaron;un zahtijevanjem dodatnog 6-cifrenog koda iz aplikacije za autentifikaciju (npr. Google Authenticator, 1Password, Bitwarden) prilikom prijave.';
$BL['be_profile_2fa_status'] = '2FA status';
$BL['be_profile_2fa_enabled'] = 'Omogu&cacute;eno';
$BL['be_profile_2fa_disabled'] = 'Onemogu&cacute;eno';
$BL['be_profile_2fa_btn_enable'] = 'Podesi dvofaktorsku autentifikaciju';
$BL['be_profile_2fa_btn_disable'] = 'Onemogu&cacute;i 2FA';
$BL['be_profile_2fa_btn_confirm'] = 'Potvrdi i omogu&cacute;i 2FA';
$BL['be_profile_2fa_btn_show_backup'] = 'Prikaži rezervne kodove';
$BL['be_profile_2fa_step1'] = 'Skenirajte QR kod aplikacijom';
$BL['be_profile_2fa_step1_text'] = 'Skenirajte ovaj QR kod pomo&cacute;u aplikacije ili ru&ccaron;no unesite tajni klju&ccaron;:';
$BL['be_profile_2fa_secret_key'] = 'Tajni klju&ccaron;';
$BL['be_profile_2fa_step2'] = 'Unesite verifikacijski kod';
$BL['be_profile_2fa_step2_text'] = 'Unesite 6-cifreni kod iz aplikacije kako biste završili podešavanje:';
$BL['be_profile_2fa_verify_code'] = '6-cifreni kod';
$BL['be_profile_2fa_backup_title'] = 'Rezervni kodovi za oporavak';
$BL['be_profile_2fa_backup_text'] = 'Sa&ccaron;uvajte ove jednokratne kodove na sigurnom mjestu. Ako izgubite pristup uređaju, možete ih iskoristiti za prijavu:';
$BL['be_profile_2fa_backup_count'] = '%d rezervnih kodova na raspolaganju';
$BL['be_profile_2fa_backup_none'] = 'Nema dostupnih rezervnih kodova';
$BL['be_profile_label_currpass'] = 'trenutna lozinka';
$BL['be_profile_2fa_currpass_placeholder'] = 'Unesite trenutnu lozinku za onemogu&cacute;avanje';
$BL['be_profile_2fa_err_invalid_code'] = '6-cifreni kod za provjeru je nevaže&cacute;i. Pokušajte ponovo.';
$BL['be_profile_2fa_err_password'] = 'Potrebna je trenutna lozinka za promjenu 2FA postavki.';
$BL['be_profile_2fa_disabled_success'] = 'Dvofaktorska autentifikacija je onemogu&cacute;ena.';
$BL['be_profile_2fa_enabled_success'] = 'Dvofaktorska autentifikacija je uspješno omogu&cacute;ena!';
$BL['login_2fa_title'] = 'Dvofaktorska autentifikacija';
$BL['login_2fa_desc'] = 'Dvofaktorska autentifikacija je aktivna za ovaj ra&ccaron;un. Unesite 6-cifreni kod iz aplikacije ili rezervni kod.';
$BL['login_2fa_code'] = 'Kod za provjeru autenti&ccaron;nosti';
$BL['login_2fa_placeholder'] = '6-cifreni kod ili rezervni kod';
$BL['login_2fa_button'] = 'Provjeri i prijavi se';
$BL['login_2fa_back'] = 'Nazad na prijavu';
$BL['login_2fa_invalid'] = 'Nevaže&cacute;i 2FA kod ili rezervni kod. Pokušajte ponovo.';
$BL['login_2fa_backup_used'] = 'Rezervni kod je prihva&cacute;en. Molimo generišite nove kodove u svom profilu.';


// admin 2fa
$BL['be_admin_usr_2fa_reset'] = 'Resetuj / onemogu&cacute;i 2FA';
$BL['be_admin_usr_2fa_reset_confirm'] = 'Jeste li sigurni da želite onemogu&cacute;iti 2FA za ovog korisnika?';
$BL['be_admin_usr_2fa_active'] = '2FA je aktivan za ovaj ra&ccaron;un.';

// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'preuzimanje datoteka sa ftp-a';
$BL['be_ftptakeover_mark']              = 'oznaci';
$BL['be_ftptakeover_available']         = 'dostupne datoteke';
$BL['be_ftptakeover_size']              = 'velicina';
$BL['be_ftptakeover_nofile']            = 'ne postoje dostupne datoteke – morate ih ubaciti preko ftp-a';
$BL['be_ftptakeover_all']          = 'Sve';
$BL['be_ftptakeover_directory']         = 'direktorij';
$BL['be_ftptakeover_rootdir']           = 'glavi direktorij';
$BL['be_ftptakeover_needed']       = 'obavezno (morate odabrati barem jednu stavku)';
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
$BL['be_ftab_private']                  = 'privatne dat.';
$BL['be_ftab_public']                   = 'javne datoteke';
$BL['be_ftab_search']                   = 'trazi';
$BL['be_ftab_trash']                    = 'kanta za smece';
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
$BL['be_fprivedit_clockwise']           = 'okreni malu slicicu u smjeru kazaljke [original +90°]';
$BL['be_fprivedit_cclockwise']          = 'okreni malu slicicu u suprotnom smjeru kazaljke [original -90°]';
$BL['be_fprivedit_button']              = 'OK';
$BL['be_fprivedit_size']                = 'velicina';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'Ubaci datoteku';
$BL['be_fprivfunc_makenew']             = 'Napravi novi direktorij';
$BL['be_fprivfunc_paste']               = 'Umetni datoteku';
$BL['be_fprivfunc_edit']                = 'izmjeni direktorij';
$BL['be_fprivfunc_cactive']             = 'vidljivo/nevidljivo';
$BL['be_fprivfunc_cpublic']             = 'javno/privatno';
$BL['be_fprivfunc_deldir']              = 'izbrisi direktorij';
$BL['be_fprivfunc_jsdeldir']            = 'Da li stvarno zelite da\nizbrisete direktorij';
$BL['be_fprivfunc_notempty']            = 'direktorij {VAL} nije prazan!';
$BL['be_fprivfunc_notempty_short']        = 'direktorij nije prazan!';
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
$BL['be_ftrash_restore']                = 'Zelite li da vratite {VAL}\nnazad u privatnu listu?';
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
$BL['be_fsearch_or']               = 'Ili';
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
$BL['be_newsletter_add']                = 'dodaj newsletter pretplatu';
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
$BL['be_admin_usr_mailbody']            = "Dobrodosli na administracijske stranice\n\n    korisnicko ime: {LOGIN}\n    sifra: {PASSWORD}\n\n\nMozete se prijaviti ovdje: {LOGIN_PAGE}\n\nadministrator\n";
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
$BL['be_admin_usr_emailbody']           = "Informacije korisnickog racuna izmjenjene.\n\n    korisnicko ime: {LOGIN}\n    sifra: {PASSWORD}\n\n\nMozete se prijaviti ovdje: {LOGIN_PAGE}\n\nadministrator\n";
$BL['be_admin_usr_passnochange']   = '[Bez promjene - koristiti postojeću lozinku]';
$BL['be_admin_usr_ebutton']             = 'OK';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'lista korisnika';
$BL['be_admin_usr_ldel']                = 'UPOZORENJE!Ovim cete izbrisati korisnika';
$BL['be_admin_usr_create']              = 'napravi novog korisnika';
$BL['be_admin_usr_editusr']             = 'izmjeni korisnika';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'struktura stranice';
$BL['be_admin_struct_child']            = '(podanik od)';
$BL['be_admin_struct_index']            = 'index (start internet stranice)';
$BL['be_admin_struct_cat']              = 'naslov kategorije';
$BL['be_admin_struct_hide1']            = 'sakrij';
$BL['be_admin_struct_hide2']            = 'ovu kategoriju u meniju';
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
$BL['be_admin_page_pagetitle']          = 'naslov stranice';
$BL['be_admin_page_addtotitle']         = 'dodaj u naslov';
$BL['be_admin_page_category']           = 'kategorija';
$BL['be_admin_page_articlename']        = 'naslov sadrzaja';
$BL['be_admin_page_blocks']             = 'blokovi';
$BL['be_admin_page_allblocks']          = 'svi blokovi';
$BL['be_admin_page_col1']               = '3 kolone layout';
$BL['be_admin_page_col2']               = '2 kolone layout (glavni prostor - desno, navigacijski prostor lijevo)';
$BL['be_admin_page_col3']               = '2 kolone layout (glavni prostor - lijevo, navigacijski prostor desno)';
$BL['be_admin_page_col4']               = '1 kolona layout';
$BL['be_admin_page_header']             = 'header';
$BL['be_admin_page_footer']             = 'footer';
$BL['be_admin_page_topspace']           = 'razmak od vrha';
$BL['be_admin_page_bottomspace']        = 'razmak od dna';
$BL['be_admin_page_button']             = 'OK';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend postavke: css postavke';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'OK';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend postavke: predlosci';
$BL['be_admin_tmpl_default']            = 'glavni';
$BL['be_admin_tmpl_add']                = 'dodaj predloske';
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
$BL['be_cnt_maxw']                      = 'max. sirina';
$BL['be_cnt_maxh']                      = 'max. visina';
$BL['be_cnt_enlarge']                   = 'uvecanje klikom';
$BL['be_cnt_caption']                   = 'dodatak';
$BL['be_cnt_subject']                   = 'naslov';
$BL['be_cnt_recipient']                 = 'primaoc';
$BL['be_cnt_buttontext']                = 'tekst dugmeta';
$BL['be_cnt_sendas']                    = 'posalji kao';
$BL['be_cnt_text']                      = 'tekst';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'polja forme';
$BL['be_cnt_code']                      = 'kod';
$BL['be_cnt_infotext']                  = 'info tekst';
$BL['be_cnt_subscription']              = 'pretplata';
$BL['be_cnt_labelemail']                = 'oznaci email';
$BL['be_cnt_tablealign']                = 'poravnanje tabele';
$BL['be_cnt_labelname']                 = 'ime oznake';
$BL['be_cnt_labelsubsc']                = 'oznaka pretpl.';
$BL['be_cnt_allsubsc']                  = 'sve pretpl.';
$BL['be_cnt_default']                   = 'default';
$BL['be_cnt_left']                      = 'lijevo';
$BL['be_cnt_center']                    = 'centar';
$BL['be_cnt_right']                     = 'desno';
$BL['be_cnt_successtext']               = 'tekst o uspjehu';
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
$BL['be_cnt_imagespace']                = 'razmak slike';
$BL['be_cnt_directlink']                = 'direktni link';
$BL['be_cnt_target']                    = 'meta';
$BL['be_cnt_target1']                   = 'u novom prozoru';
$BL['be_cnt_target2']                   = 'u ramu trenutnog prozora';
$BL['be_cnt_target3']                   = 'u istom prozoru bez ramova';
$BL['be_cnt_target4']                   = 'u istom ramu ili prozoru';
$BL['be_cnt_bullet']                    = 'lista (tabela)';
$BL['be_cnt_ullist']                    = 'lista';
$BL['be_cnt_ullist_desc']               = '~ = prvi nivo, ~~ = drugi nivo, itd.';
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
$BL['be_cnt_results_per_page']          = 'po stranici (prazno - pokazi sve)';
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
$BL['be_cnt_guestbook_listing_all']     = 'izlistaj sve unose';
$BL['be_cnt_guestbook_list']            = 'izlistaj';
$BL['be_cnt_guestbook_perpage']         = 'po stranici';
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
$BL['be_newsletter_sendnlbutton']  = 'Pošalji newsletter';
$BL['be_newsletter_sendprocess']        = 'posalji proces';
$BL['be_newsletter_attention2']         = 'Molimo ne prekidajte proces slanja.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">test email adresa <strong>###TEST###</strong> nije validna!<br /> <br />Pokusajte ponovo!';
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
$BL['be_cnt_bid_nextbidadd']            = 'povecaj za';

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
$BL['be_ftrash_delall']                 = "Da li stvarno zelite da obrisete\nsve datoteke iz smeca?";
$BL['be_ftrash_delallfiles']            = 'izbrisi sve datoteke iz smeca';

// added: 16-08-2004
// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'ubaci CSV pretplatnika';
$BL['be_newsletter_importtitle']        = 'ubaci Newsletter pretplatnika';
$BL['be_newsletter_entriesfound']       = 'nadjenih unosa';
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
$BL['be_btn_delete']                    = 'Da li stvarno zelite da\nizbrisete ovu lokaciju?';

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
$BL['be_admin_template_jswarning']      = 'Upozorenje!!!\nPrilagodjeni blokovi se mogu promjeniti!\n\nAko odustanete\nresetujte vas izgled stranica!\n\nPromjeniti predlozak?\n\n';

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
$BL['be_cnt_cache_delete_msg']          = 'Da li stvarno zelite da izbrisete cache?\nOvo moze djelovati na pretragu.\n';

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
$BL['be_cnt_move_deleted_msg'] = 'Da li stvarno zelite da pomjerite datoteke\noznacene kao obrisane u specijalni direktorij za brisanje?\n';

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
$BL['be_article_morelink'] = '[vise…] adresa';
$BL['be_admin_tmpl_copy']               = 'kopiraj predlozak';

$BL['be_ctype_filelist1']                = 'lista datoteka';
$BL['be_cnt_fpro_usecaption']            = 'iskoristi centar datoteka &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']                = 'Kljucne rijeci';
$BL['be_admin_keywords_key']       = 'Klju&ccaron;na rije&ccaron;';
$BL['be_admin_keywords_err']            = 'Unesi jedinstvenu kljucnu rijec';
$BL['be_admin_keyword_edit']            = 'izmjeni kljucnu rijec';
$BL['be_admin_keyword_del']             = 'izbrisi kljucnu rijec';
$BL['be_admin_keyword_delmsg']          = 'Da li stvarno zelite da\nizbrisete kljucnu rijec?';
$BL['be_admin_keyword_add']             = 'dodaj kljucnu rijec';

$BL['be_cnt_transparent'] = 'Flash transparentno';


// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']   = 'datum isteka (terminacije)';
$BL['be_func_switch_contentpart'] = 'Jeste li sigurni da zelite promjeniti tip sadrzaja?\n\nBudite pazljivi!\nVazne postavke mogu biti izbrisane!\n';
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
$BL['Version_not_up_to_date']           = 'Vasa instalacija <b>nije</b> svjeza. Postoje nadogradnje. Kontaktirajte svog administratora.';
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
$BL['be_cnt_imgupload']                 = 'ubaci sliku';
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
$BL['be_cnt_newsletter_prepare2']       = 'red za slanje ce biti osvjezen…';

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
$BL['be_cnt_sortvalue']                 = 'sortiraj vrijednost';
$BL['be_dialog_warn_nosave']            = 'Ako nastavite, promjene nece biti sacuvane!\nDa li zelite da nastavite?';
$BL['be_cnt_paginate_subsection']       = 'podsekcija';
$BL['be_cnt_subsection_tite']           = 'naslov podsekcije';
$BL['be_cnt_subsection_warning']        = 'Linkovanje podsekcija (linkovanje dijelova clanaka)\nje omoguceno samo za glavnu kolonu!';

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
$BL['be_fsearch_not']              = 'Ne';
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

$BL['be_legacy'] = 'legacy';
$BL['be_default'] = 'podrazumijevano';


// Merged from lang.ext.inc.php
$BL['FOLDER_LIST']       = 'lista direktorija';
$BL['FILES']             = 'datoteke';
$BL['SHOW_FILES']        = 'prikazi datoteku unutar glavnog direktorija';
$BL['SHOW_FILES1']       = 'prikazi datoteke unutar direktorija';
$BL['TAKE_IMAGE']        = 'preuzmi ovu datoteku i dodaj…';
$BL['NO_FILE']           = 'nema datoteke';
$BL['OPEN_DIR']          = 'otvori direktorij';
$BL['CLOSE_DIR']         = 'zatvori direktorij';
$BL['FILE_TITLE']        = 'phpwcms preglednik datoteka';
$BL['IMAGE_TITLE']       = 'phpwcms preglednik slika';
$BL['MEDIA_TITLE']       = 'phpwcms preglednik medija';
$BL['IMAGE_FILES']       = 'slikovne datoteke';
$BL['MEDIA_FILES']       = 'medija datoteke';
$BL['ROOT_DIR']          = 'skladisni prostor (glavni dir)';
$BL['DOWNLOAD_ERR1']     = 'Greska (NR:{VAL}) se dogodila dok se datoteka dobavljala.';
$BL['DOWNLOAD_ERR2']     = 'Ako mislite da je to greska u sistemu kontaktirajte <a href="mailto:{VAL}"><strong>webmastera</strong></a>.';
$BL['DOWNLOAD_TITLE']    = 'Greska kod dobavljanja datoteke';
$BL['FILEINFO_TITLE']    = 'phpwcms: info datoteke';
$BL['CREATED']           = 'kreirano';
$BL['DATE_FORMAT']       = 'm-d-G S:m';
$BL['SIZE']              = 'velicina';
$BL['DOWNLOAD_FILE']     = 'dobavi datoteku';
$BL['FILE_IN_TRASH']     = 'datoteka je u kanti za smece';
$BL['KEYWORDS']          = 'kljucne rijeci';
$BL['DOWNLOAD_ERR3']     = 'greska prilikom dobavljanja informacija datoteke<br />zatvorite ovaj prozor i pokusajte ponovo...';
$BL['ADD_ALL_FILES']     = 'Dodaj sve datoteke';
$BL['ADD_ALL_CONFIRM']   = 'Sve datoteke iz direktorija »{VAL}« su uzete!\n\nKliknite na [OK] da zatvorite ovaj prozor';


// Batch 2 complete translation for bs
$BL['be_flash_media'] = 'Flash medij';
$BL['be_html5_media'] = 'HTML5 medij';
$BL['be_html5_h264'] = 'H.264 (MP4/M4V/MOV)';
$BL['be_html5_webm'] = 'WebM (VP8/Vorbis)';
$BL['be_html5_ogg'] = 'Ogg (Theora/Vorbis)';
$BL['be_media_format'] = 'format medija';
$BL['be_media_watermark'] = 'vodeni žig';
$BL['be_skin'] = 'tema';
$BL['be_foreground_color'] = 'boja prednjeg plana';
$BL['be_background_color'] = 'boja pozadine';
$BL['be_highlight_color'] = 'boja isticanja';
$BL['be_files_select_available'] = 'odaberi dostupne fajlove';
$BL['be_hidden_for_feuser'] = 'skriveno za korisnike sajta';
$BL['be_visible_for_everybody'] = 'vidljivo svima';
$BL['be_fileuploader_typeError'] = '{file} ima nevažeću ekstenziju. Dozvoljene su samo: {extensions}.';
$BL['be_fileuploader_sizeError'] = '{file} je prevelik, maksimalna veličina je {sizeLimit}.';
$BL['be_fileuploader_minSizeError'] = '{file} je premali, minimalna veličina je {minSizeLimit}.';
$BL['be_fileuploader_emptyError'] = '{file} je prazan, molimo odaberite ponovo.';
$BL['be_fileuploader_noFilesError'] = 'Nema fajlova za učitavanje.';
$BL['be_fileuploader_onLeave'] = 'Fajlovi se učitavaju. Ako sada napustite stranicu, učitavanje će biti prekinuto.';
$BL['be_fileuploader_dragText'] = 'Prevucite fajlove ovdje za učitavanje';
$BL['be_fileuploader_dictFallbackText'] = 'Vaš preglednik ne podržava prevlačenje fajlova.';
$BL['be_fileuploader_dictCancelUploadConfirmation'] = 'Jeste li sigurni da želite prekinuti učitavanje?';
$BL['be_fileuploader_dictMaxFilesExceeded'] = 'Prekoračen maksimalan broj fajlova.';
$BL['be_delete_selected_files'] = 'obriši odabrane fajlove';
$BL['be_delete_selected_files_confirm'] = 'Jeste li sigurni da želite obrisati odabrane fajlove?';
$BL['limit_image_from_list'] = 'ograniči slike iz liste';
$BL['alt_image'] = 'alternativna slika';
$BL['alt_text'] = 'alternativni tekst';
$BL['over'] = 'preko';
$BL['js_lib'] = 'JS biblioteka';
$BL['js_lib_alwaysload'] = 'uvijek učitaj JS biblioteku';
$BL['frontendjs_load'] = 'učitaj frontend JS';
$BL['googleapi_load'] = 'učitaj Google API';
$BL['fancyupload_clear_list'] = 'očisti listu';
$BL['fancyupload_file_uploaded'] = 'Fajl učitan.';
$BL['fancyupload_file_error'] = 'Greška pri učitavanju fajla.';
$BL['fancyupload_adblock_error'] = 'Molimo onemogućite blokator reklama.';
$BL['fancyupload_flashblock_error'] = 'Molimo dozvolite Flash.';
$BL['fancyupload_required_error'] = 'Fajl je obavezan.';
$BL['fancyupload_flash_error'] = 'Flash nije dostupan.';
$BL['be_cnt_function_validate'] = 'validacija funkcije';
$BL['be_structform_selected_cp'] = 'odabrani element sadržaja';
$BL['be_structform_select_cp'] = 'odaberi element sadržaja';
$BL['source_image_not_found'] = 'izvorna slika nije pronađena';
$BL['form_force_ssl'] = 'forsiraj SSL (HTTPS)';
$BL['numerize_title'] = 'numeriši naslov';
$BL['be_article_noteaser'] = 'bez uvodnog teksta';
$BL['be_acat_disable301'] = 'onemogući 301 preusmjeravanje';
$BL['file_actions_step1'] = 'Korak 1: odabir foldera';
$BL['file_actions_step2'] = 'Korak 2: odabir fajlova';
$BL['file_actions_step3'] = 'Korak 3: odabir akcije';
$BL['file_actions_button'] = 'Izvrši akciju nad fajlovima';
$BL['file_actions_no'] = 'Nema akcije';
$BL['file_actions_delete'] = 'Obriši fajlove';
$BL['file_actions_bemuser'] = 'Dodijeli korisniku';
$BL['file_actions_bemfolder'] = 'Premjesti u folder';
$BL['file_actions_pdl_empty'] = 'očisti listu';
$BL['file_actions_pdl_delete'] = 'obriši odabrano';
$BL['file_actions_pdl_move'] = 'premjesti odabrano';
$BL['file_actions_pdl_status'] = 'promijeni status';
$BL['file_actions_pdl_user'] = 'promijeni korisnika';
$BL['file_actions_msg_move'] = 'Fajlovi su uspješno premješteni';
$BL['file_actions_msg_delete'] = 'Fajlovi su uspješno obrisani';
$BL['file_actions_msg_status'] = 'Status fajlova je uspješno promijenjen';
$BL['file_actions_msg_error'] = 'Došlo je do greške';
$BL['file_actions_msg_user'] = 'Fajlovi su uspješno dodijeljeni novom korisniku';
$BL['be_imagefiles_as_gallery'] = 'Prikaži slike kao galeriju';
$BL['be_link'] = 'link';
$BL['be_links'] = 'linkovi';
$BL['be_redirect'] = 'preusmjeravanje';
$BL['be_redirects'] = 'preusmjeravanja';
$BL['be_views'] = 'pregledi';
$BL['be_structure_id'] = 'ID strukture';
$BL['be_shortcut'] = 'prečica';
$BL['be_target_type'] = 'tip cilja';
$BL['be_http_status'] = 'HTTP status kod';
$BL['be_http_status301'] = '301 - Trajno premješteno';
$BL['be_http_status307'] = '307 - Privremeno preusmjeravanje';
$BL['be_http_status404'] = '404 - Stranica nije pronađena';
$BL['be_http_status401'] = '401 - Neautorizovano';
$BL['be_http_status503'] = '503 - Usluga nedostupna';
$BL['be_redirect_error1'] = 'Ciljni URL je obavezan';
$BL['be_redirect_error2'] = 'Alijas se već koristi';
$BL['be_redirect_error3'] = 'Nevažeći status kod';
$BL['be_new_linkredirect'] = 'novo preusmjeravanje linka';
$BL['be_ctype_accordion'] = 'harmonika / accordion';
$BL['be_ctype_number'] = 'broj';
$BL['be_inactive'] = 'neaktivno';
$BL['be_locked'] = 'zaključano';
$BL['be_n/a'] = 'n/p';
$BL['be_opengraph_support'] = 'Open Graph podrška';
$BL['be_player_volume'] = 'Jačina zvuka';
$BL['be_player_volume_muted'] = 'utišano';
$BL['be_keyword'] = 'ključna riječ';
$BL['be_tag'] = 'oznaka';
$BL['be_system_container'] = 'sistemski kontejner';
$BL['be_system_container_norender'] = 'ne iscrtavaj kontejner';
$BL['be_custom_scriptlogic'] = 'prilagođena logika skripte';
$BL['be_flush_image_cache'] = 'očisti keš slika';
$BL['be_flush_image_cache_confirm'] = 'Jeste li sigurni da želite očistiti keš slika?';
$BL['be_flush_image_cache_success'] = 'Keš slika je uspješno očišćen. Obrisano fajlova: %d.';
$BL['be_caption_alt'] = 'Alternativni tekst (Alt)';
$BL['be_caption_title'] = 'Naslov (Title)';
$BL['be_caption_file_imagesize'] = 'Dimenzije slike';
$BL['be_caption_file_title'] = 'Naslov fajla';
$BL['be_caption_descr.'] = 'Opis';
$BL['be_display_html5_only'] = 'prikaži samo kao HTML5';
$BL['be_audio_only'] = 'samo audio';
$BL['be_hide_downloadbutton'] = 'sakrij dugme za preuzimanje';
$BL['be_filter'] = 'filter';
$BL['be_filter_with_tags'] = 'filtriraj po oznakama';
$BL['be_filter_not_selected'] = 'filter nije odabran';
$BL['be_empty_search_result'] = 'Nema pronađenih rezultata';
$BL['confirm_cp_tab_warning'] = 'Promjena kartice može dovesti do gubitka nesačuvanih izmjena.

Želite li nastaviti?';
$BL['be_canonical'] = 'kanonski URL';
$BL['be_breadcrumb'] = 'putanja (breadcrumb)';
$BL['be_breadcrumb_nothidden'] = 'ne sakrivaj u putanji';
$BL['be_breadcrumb_nolink'] = 'bez linka u putanji';
$BL['be_parental_alias'] = 'roditeljski alijas';
$BL['be_fsearch_nor']              = 'Nijedan';
$BL['be_tab_toggle'] = 'otvori/zatvori karticu';
$BL['be_custom_textfield'] = 'prilagođeni tekst';
$BL['be_tab_template_toggle_warning'] = 'Promjena šablona može promijeniti prilagođena polja i uzrokovati gubitak podataka.

Jeste li sigurni da želite nastaviti?';
$BL['be_onepage_id'] = 'OnePage ID podrška (#sidro)';
$BL['be_onepage_template'] = 'obradi kao OnePage šablon';
$BL['be_yes'] = 'Da';
$BL['be_no'] = 'Ne';
$BL['be_attr_title'] = 'naslov (atribut)';
$BL['be_attr_alt'] = 'alternativni tekst';
$BL['be_ie8ignore'] = 'onemogući <a href="https://bs.wikipedia.org/wiki/Uslovni_komentari" target="_blank" class="underline">uslovne komentare</a> za IE8';
$BL['be_cookie_consent_enable'] = 'omogući Cookie Consent v2 dodatak (v3 će biti onemogućen)';
$BL['be_cookie_consent_message'] = 'poruka o pristanku';
$BL['be_cookie_consent_translatable'] = 'Ova instalacija podržava više jezika ($phpwcms[\'allowed_lang\']). Za prevođenje tekstova obavještenja o kolačićima koristite sintaksu <b>@@Tekst@@</b> i provjerite `template/template_lang` nakon prikaza.';
$BL['cookie_consent_message'] = 'Ova web stranica koristi kolačiće kako bi vam osigurala najbolje iskustvo na našoj web stranici.';
$BL['be_cookie_consent_dismiss'] = 'tekst dugmeta za prihvatanje';
$BL['cookie_consent_dismiss'] = 'Razumijem!';
$BL['be_cookie_consent_more'] = 'tekst dugmeta za više informacija';
$BL['cookie_consent_more'] = 'Više informacija';
$BL['be_cookie_consent_link'] = 'URL/alijas politike kolačića';
$BL['be_cookie_consent_theme'] = 'tema (prazno = bez CSS-a)';
$BL['be_google_analytics_enable'] = 'koristi Google Analytics';
$BL['be_google_tag_manager_enable'] = 'koristi Google Tag Manager';
$BL['be_piwik_enable'] = 'koristi Matomo/Piwik';
$BL['be_tracking_anonymize'] = 'anonimiziraj IP adresu';
$BL['be_tracking_cookie_flags'] = 'omogući <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/cookies-user-id#cookie_flags" target="_blank"><u>zastavice kolačića</u></a>';
$BL['be_tracking_custom_properties'] = 'prilagođeni <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/" target="_blank"><u>parametri konfiguracije</u></a> (prop1: val1, prop2: val2)';
$BL['be_tracking_id'] = 'ID praćenja';
$BL['be_site_id'] = 'ID sajta';
$BL['be_piwik_url'] = 'URL Matomo/Piwik';
$BL['be_filedownload_direct_blocked'] = 'blokirano putem <abbr title="%s">.htaccess</abbr>';
$BL['be_tracking_optout'] = 'podrška za kolačić odjave <i><a href="javascript:gaOptout()"></a></i>';
$BL['be_require_consent'] = 'Onemogući kod za praćenje bez pristanka';
$BL['be_consent_cookie_name'] = 'naziv kolačića pristanka';
$BL['be_consent_cookie_value'] = 'vrijednost kolačića pristanka';
$BL['be_respect_donottrack'] = 'Poštuj postavku preglednika Do-Not-Track';
$BL['placeholder_require_cookie_name'] = 'cookieconsent_dismissed';
$BL['placeholder_require_cookie_value'] = 'yes';
$BL['be_cc_v3_enable'] = 'omogući Cookie Consent v3 dodatak (v2 će biti onemogućen)';
$BL['be_cc_v3_title'] = 'naslov prozora za kolačiće';
$BL['cc_v3_title_placeholder'] = 'Cijenimo vašu privatnost';
$BL['be_cc_v3_description'] = 'opis';
$BL['cc_v3_description_placeholder'] = 'Koristimo kolačiće kako bismo poboljšali vaše iskustvo pregledanja, prikazali personalizirane oglase ili sadržaj i analizirali naš promet. Klikom na "Prihvati sve" pristajete na našu upotrebu kolačića.';
$BL['be_cc_v3_accept_all'] = 'dugme "prihvati sve"';
$BL['cc_v3_accept_all_placeholder'] = 'Prihvati sve';
$BL['be_cc_v3_accept_necessary'] = 'dugme "prihvati samo neophodne"';
$BL['cc_v3_accept_necessary_placeholder'] = 'Samo neophodni';
$BL['be_cc_v3_accept_selected'] = 'dugme "prihvati odabrano"';
$BL['cc_v3_accept_selected_placeholder'] = 'Prihvati odabrano';
$BL['be_cc_v3_reject_all'] = 'dugme "odbij sve"';
$BL['cc_v3_reject_all_placeholder'] = 'Odbij sve';
$BL['be_cc_v3_customize'] = 'dugme "prilagodi"';
$BL['cc_v3_customize_placeholder'] = 'Prilagodi';
$BL['be_cc_v3_link'] = 'URL/alijas politike kolačića';
$BL['be_cc_v3_more'] = 'tekst dodatnih informacija';
$BL['be_cc_v3_theme'] = 'tema (prazno = svijetla)';
$BL['cc_v3_more_placeholder'] = 'više informacija';
$BL['be_cc_v3_sections'] = 'sekcije kolačića';
$BL['be_cc_v3_sections_title'] = 'naslov';
$BL['be_cc_v3_sections_description'] = 'opis';
$BL['be_cc_v3_sections_active'] = 'prikaži sekciju';
$BL['be_cc_v3_section_general'] = 'općenito';
$BL['be_cc_v3_section_general_title_placeholder'] = 'Upravljanje postavkama kolačića';
$BL['be_cc_v3_section_general_description_placeholder'] = 'Koristimo kolačiće kako bismo vam pomogli da se efikasno krećete i izvršavate određene funkcije.';
$BL['be_cc_v3_section_necessary'] = 'neophodni';
$BL['be_cc_v3_section_necessary_title_placeholder'] = 'Strogo neophodni kolačići';
$BL['be_cc_v3_section_necessary_description_placeholder'] = 'Neophodni kolačići su ključni za osnovne funkcije web stranice.';
$BL['be_cc_v3_section_functional'] = 'funkcionalni';
$BL['be_cc_v3_section_functional_title_placeholder'] = 'Funkcionalni kolačići';
$BL['be_cc_v3_section_functional_description_placeholder'] = 'Funkcionalni kolačići pomažu u spremanju vaših postavki i preferencija.';
$BL['be_cc_v3_section_analytics'] = 'analitički';
$BL['be_cc_v3_section_analytics_title_placeholder'] = 'Kolačići za performanse i analitiku';
$BL['be_cc_v3_section_analytics_description_placeholder'] = 'Analitički kolačići se koriste za razumijevanje načina na koji posjetioci komuniciraju sa stranicom.';
$BL['be_cc_v3_section_marketing'] = 'marketing';
$BL['be_cc_v3_section_marketing_title_placeholder'] = 'Kolačići za oglašavanje i marketing';
$BL['be_cc_v3_section_marketing_description_placeholder'] = 'Marketinški kolačići se koriste za prikazivanje prilagođenih oglasa na osnovu vaših interesa.';
$BL['be_cc_v3_section_social'] = 'društvene mreže';
$BL['be_cc_v3_section_social_title_placeholder'] = 'Kolačići društvenih mreža';
$BL['be_cc_v3_section_social_description_placeholder'] = 'Kolačići društvenih mreža omogućavaju dijeljenje sadržaja na društvenim platformama.';
$BL['be_cc_v3_section_more'] = 'više';
$BL['be_cc_v3_section_more_title_placeholder'] = 'Dodatne informacije';
$BL['be_cc_v3_section_more_description_placeholder'] = 'Za sva pitanja u vezi s našom politikom kolačića, molimo <a class="cc__link" href="#yourdomain.com">kontaktirajte nas</a>.';
$BL['be_cc_v3_builtin'] = 'ugrađeno';
$BL['be_cc_v3_default'] = 'zadano';
$BL['be_cc_v3_btn_flip'] = 'zamijeni redoslijed dugmadi';
$BL['be_cc_v3_btn_equal'] = 'dugmad jednake širine';
$BL['be_cc_v3_consent_modal'] = 'dijalog za pristanak';
$BL['be_cc_v3_preferences_modal'] = 'dijalog za postavke';
$BL['be_cc_v3_layout'] = 'izgled';
$BL['be_cc_v3_position'] = 'pozicija';
$BL['be_cc_v3_top_left'] = 'gore lijevo';
$BL['be_cc_v3_top_center'] = 'gore u sredini';
$BL['be_cc_v3_top_right'] = 'gore desno';
$BL['be_cc_v3_middle_left'] = 'sredina lijevo';
$BL['be_cc_v3_middle_center'] = 'u centru';
$BL['be_cc_v3_middle_right'] = 'sredina desno';
$BL['be_cc_v3_bottom_left'] = 'dolje lijevo';
$BL['be_cc_v3_bottom_center'] = 'dolje u sredini';
$BL['be_cc_v3_bottom_right'] = 'dolje desno';
$BL['be_cc_v3_left'] = 'lijevo';
$BL['be_cc_v3_right'] = 'desno';
$BL['be_cc_v3_top'] = 'vrh';
$BL['be_cc_v3_bottom'] = 'dno';
$BL['be_cc_v3_reload_on_change'] = 'ponovo učitaj stranicu nakon promjene postavki kolačića';
$BL['be_cc_v3_on_change'] = 'pri promjeni';
$BL['be_iptc_data'] = 'IPTC podaci';
$BL['be_iptc_as_caption'] = 'koristi za natpis, autorska prava itd. ako nije postavljeno';
$BL['iptc_ImageDescription'] = 'opis slike';
$BL['iptc_Copyright'] = 'autorska prava';
$BL['iptc_Artist'] = 'autor / fotograf';
$BL['iptc_Keywords'] = 'ključne riječi';
$BL['iptc_CountryDest'] = 'država';
$BL['iptc_ProvinceOrStateDest'] = 'regija / država';
$BL['iptc_CityDest'] = 'grad';
$BL['iptc_SublocationDest'] = 'podlokacija';
$BL['iptc_ObjectName'] = 'naziv objekta';
$BL['iptc_SpecialInstructions'] = 'specijalne instrukcije';
$BL['iptc_Headline'] = 'naslov';
$BL['iptc_Credit'] = 'zasluge';
$BL['iptc_Source'] = 'izvor';
$BL['iptc_EditStatus'] = 'status uređivanja';
$BL['iptc_iimCategory'] = 'kategorija';
$BL['iptc_iimSupplementalCategory'] = 'dopunska kategorija';
$BL['iptc_Urgency'] = 'hitnost';
$BL['iptc_FixtureIdentifier'] = 'fiksni identifikator';
$BL['iptc_LocationDestCode'] = 'kod lokacije';
$BL['iptc_LocationDest'] = 'lokacija';
$BL['iptc_Software'] = 'softver';
$BL['iptc_SoftwareVersion'] = 'verzija softvera';
$BL['iptc_ObjectCycle'] = 'ciklus objekta';
$BL['iptc_CountryCodeDest'] = 'kod države';
$BL['iptc_OriginalTransmissionRef'] = 'originalna referenca prenosa';
$BL['iptc_Contact'] = 'kontakt';
$BL['iptc_Writer'] = 'autor teksta';
$BL['iptc_LanguageCode'] = 'kod jezika';
$BL['iptc_DateTimeOriginal'] = 'originalni datum/vrijeme';
$BL['iptc_DateTimeDigitized'] = 'datum/vrijeme digitalizacije';
$BL['iptc_DateTimeReleased'] = 'datum/vrijeme objavljivanja';
$BL['iptc_DateTimeExpires'] = 'datum/vrijeme isteka';
$BL['iptc_IntellectualGenre'] = 'intelektualni žanr';
$BL['iptc_SubjectNewsCode'] = 'kod teme vijesti';
$BL['iptc_iimVersion'] = 'verzija';
$BL['be_suppress_render_caption'] = 'ne iscrtavaj natpis';
$BL['be_cnt_attribute_class'] = 'CSS [class]';
$BL['be_cnt_attribute_id'] = 'CSS [id]';
$BL['be_cnt_avoid_duplicates'] = 'dozvoli samo jedinstvene vrijednosti';
$BL['be_not_set'] = 'nije postavljeno';
$BL['be_licensed_under_GPL'] = 'Licencirano pod GPL.';
$BL['be_extensions_copyright'] = 'Proširenja su zaštićena autorskim pravima njihovih autora.';
$BL['be_allowed_filetypes'] = 'Dozvoljene vrste fajlova';
$BL['be_imagediv_template_toggle_warning'] = 'Promjena šablona može promijeniti prilagođena polja i uzrokovati gubitak podataka.

Jeste li sigurni da želite nastaviti?';
$BL['be_password_show'] = 'Prikaži lozinku';
$BL['be_password_hide'] = 'Sakrij lozinku';
$BL['be_admin_template_choose_file'] = 'Tekstualni šablon ili odaberite fajl šablona';
$BL['be_flashplayer_marker'] = 'Marker';
$BL['be_marker_time'] = 'Vrijeme (sekunde, npr. 10.5)';
$BL['be_marker_text'] = 'Tekst';
$BL['be_marker_overlaytext'] = 'Prekrivni tekst';
$BL['copy_to_clipboard'] = 'Kopiraj u međuspremnik';
$BL['url_parameter'] = 'URL parametar';
$BL['file_extension'] = 'Ekstenzija fajla';
$BL['download_link'] = 'Link za preuzimanje';
$BL['disposition_attachment'] = 'Prilog';
$BL['disposition_attachment_description'] = 'direktno preuzimanje';
$BL['disposition_inline'] = 'U pregledniku (inline)';
$BL['disposition_inline_description'] = 'prikaži u pregledniku';
$BL['be_robots'] = 'Indeksiranje robota';
$BL['be_robots_noindex'] = 'blokiraj indeksiranje pretraživača (noindex)';
$BL['be_robots_nofollow'] = 'ne prati linkove (nofollow)';
$BL['be_cnt_form_direct_download'] = 'dozvoli preuzimanje';
$BL['be_cnt_form_direct_download_apikey'] = 'API ključ';
$BL['be_cnt_form_apikey_reset'] = 'poništi';
$BL['be_copy_link'] = 'kopiraj link';
$BL['be_articlebrowser_selector'] = 'Birač članaka';
$BL['be_about_headline'] = 'phpwcms sistem za upravljanje sadržajem';
$BL['be_about_version'] = 'Verzija';
$BL['be_about_maintainer'] = 'Održavalac';
$BL['be_about_website'] = 'Web stranica';
$BL['be_about_copyright'] = 'Autorska prava';
$BL['be_about_contributors'] = 'i saradnici';
$BL['be_about_and_contributors'] = 'i drugi saradnici (posebno Marcus Obst, Fernando Batista, KoMa, geckse, phalancs, q23 i drugi) – pogledajte <a href="https://github.com/systron-dev/phpwcms" title="GitHub izvorni kod" target="_blank">izvorni kod</a> za detalje o licencama i autorskim pravima.';
$BL['modal_confirm'] = 'Potvrdi';
$BL['modal_cancel'] = 'Otkaži';
$BL['modal_title_confirm'] = 'Potvrda';
$BL['modal_title_alert'] = 'Informacija';
$BL['modal_ok'] = 'U redu';
$BL['modal_delete'] = 'Obriši';
$BL['modal_move'] = 'Premjesti';
$BL['modal_copy'] = 'Kopiraj';
$BL['modal_flush'] = 'Očisti';
$BL['be_metadata'] = 'Metapodaci';
$BL['be_content'] = 'Sadržaj';
$BL['be_images'] = 'Slike';
$BL['be_article_show'] = 'Prikaži';
$BL['be_active'] = 'Aktivno';
$BL['be_cnt_summary_label'] = 'Sažetak';
$BL['be_cnt_max_words'] = 'maks. broj riječi';
$BL['be_dashboard_support'] = 'Kontakt & Podrška';
$BL['be_cnt_openarticlebrowser'] = 'otvori preglednik članaka';
$BL['be_nav_toggle_navigation'] = 'Uključi/isključi navigaciju';
$BL['be_ctype_custom'] = 'Custom CP';
$BL['be_cnt_custom_entry'] = 'Stavka';
$BL['be_cnt_custom_entries'] = 'Stavke';
$BL['be_cnt_title_overview'] = 'Pregled';
$BL['be_article_opposite_lang'] = 'Drugi jezici';
$BL['be_tooltip_visibility'] = 'omogući / onemogući';
$BL['be_tooltip_filter_user'] = 'Filtriraj po korisničkom imenu, imenu ili e-mailu';
$BL['be_tooltip_filter_for'] = 'Filtriraj po';
$BL['be_btn_preview'] = 'Pregled';
$BL['be_cnt_title_multiupload'] = 'Prevucite slike i fajlove u ovaj prozor';
$BL['be_input_text_tab'] = 'Unesite pojam i potvrdite tipkom Tab';
$BL['be_tt_duplicate'] = 'Dupliraj';
$BL['be_tt_edit'] = 'Uredi';
$BL['be_tt_delete'] = 'Obriši';
$BL['be_tt_delete_pagelayout'] = 'Obriši izgled stranice';
$BL['be_subnav_admin_users_overview'] = 'Pregled korisnika';
$BL['be_cnt_several'] = 'Ostalo';
$BL['be_mailinglist_new'] = 'Kreiraj novu mailing listu';
$BL['be_mailinglist_overview_subscribers'] = 'Pretplatnici';
$BL['be_mailinglist_verified'] = 'Postavi %s potvrđeno/nepotvrđeno';
$BL['be_mailinglist_delete_subscriber'] = 'Obriši pretplatnika';
$BL['be_mailinglist_delete_list'] = 'Obriši mailing listu';
$BL['be_mailinglist_cannotdelete_list'] = 'Mailing lista se ne može obrisati jer sadrži pretplatnike';
$BL['be_msg_opend'] = 'Otvoreno';
$BL['be_admin_group'] = 'Dozvole';
$BL['be_admin_group_edit'] = 'Uredi dozvole';
$BL['be_admin_group_ldel'] = 'Obriši grupu dozvola';
$BL['be_imagealias'] = 'Aliasi slika';
$BL['count'] = ' preostalih unosa aliasa za ažuriranje';
$BL['nocount'] = 'Svi unosi aliasa postoje';
$BL['counttotal'] = ' unesenih unosa aliasa';
$BL['f_alias'] = '<p><b>Polja aliasa su dodana u bazu podataka</b></p>';
$BL['be_ctptemp'] = 'Šabloni elemenata sadržaja';
$BL['file_copy'] = 'Kopiraj šablon';
$BL['file_rename'] = 'Preimenuj šablon';
$BL['file_delete'] = 'Obriši šablon';
$BL['list_files'] = 'Prikaži članke s ovim šablonom';
$BL['show_code'] = 'Prikaži izvorni kod šablona';
$BL['label_default'] = 'Zadani šablon (/inc_default)';
$BL['label_folder'] = 'Folder fajlova';
$BL['label_custom'] = 'Šabloni';
$BL['label_sample'] = 'Folder primjera';
$BL['success_msg'] = ' Šablon je uspješno kopiran. Molimo preimenujte i prilagodite šablon.';
$BL['success_msg_error'] = ' Nije uspjelo kopiranje šablona.';
$BL['deleted_msg'] = ' Šablon je uspješno uklonjen';
$BL['deleted_msg_error'] = ' Nije uspjelo brisanje šablona.';
$BL['rename_msg'] = ' Šablon je uspješno preimenovan.';
$BL['rename_msg_error'] = ' Nije uspjelo preimenovanje šablona.';
$BL['be_acat_urlalias'] = 'Alijas mape sajta';
$BL['be_acat_pagetitle'] = 'Postojeći naslov stranice';
$BL['be_acat_alias'] = 'Postojeći alijas';
$BL['be_article_description'] = 'Postojeći opis';
$BL['be_func_struct_more_action'] = 'Akcije';
$BL['be_func_open_articlebrowser'] = 'otvori preglednik članaka';
$BL['be_amount_results'] = 'Količina';
$BL['be_news_edit'] = 'uredi vijest';
$BL['be_news_copy'] = 'kopiraj vijest';
$BL['be_news_add'] = 'dodaj vijest';
$BL['be_news_list'] = 'vijesti';
$BL['be_text_full'] = 'puni tekst';
$BL['login_welcome'] = 'Dobrodošli';
$BL['be_sqlshortdate'] = '%d.%m.%y';
$BL['be_sqlshortdatetime'] = '%d.%m.%y %H:%i';
$BL['be_sqllongdatetime'] = '%d.%m.%Y %H:%i:%s';
$BL['be_fprivfunc_notrash'] = 'Nema dozvole za brisanje';
$BL['be_fprivup_err10'] = 'Učitani fajl prelazi serverski limit (post_max_size: %s). Molimo učitajte manji fajl.';
$BL['be_fprivup_err11'] = 'Fajl "%s" je prevelik (%s). Maksimalno dozvoljena veličina je %s.';
$BL['be_article_created_at'] = 'kreirano';
$BL['be_article_updated_at'] = 'ažurirano';
$BL['be_fileuploader_uploadButtonText'] = 'Odaberite fajlove ili prevucite ovdje';
$BL['be_fileuploader_dictDefaultMessage'] = '<i class="fas fa-cloud-upload-alt fa-3x mb-3 text-muted d-block"></i><span class="font-weight-bold">Odaberite fajlove ili prevucite ovdje</span><br><small class="text-muted">Kliknite ili prevucite fajlove u ovo područje</small>';
$BL['be_fileuploader_dictFallbackMessage'] = 'Vaš preglednik ne podržava prevlačenje fajlova.';
$BL['be_fileuploader_dictFileTooBig'] = 'Fajl je prevelik ({{filesize}}MiB). Maksimalna veličina: {{maxFilesize}}MiB.';
$BL['be_fileuploader_dictInvalidFileType'] = 'Fajlovi ovog tipa se ne mogu učitati u trenutnom režimu filtriranja.';
$BL['be_fileuploader_dictResponseError'] = 'Server je odgovorio kodom {{statusCode}}.';
$BL['be_fileuploader_dictCancelUpload'] = 'Otkaži učitavanje';
$BL['be_fileuploader_dictRemoveFile'] = 'Ukloni fajl';
$BL['CSRF_ERROR_TITLE'] = 'Sigurnosna provjera nije uspjela';
$BL['CSRF_POST_INVALID'] = 'Nisu pronađeni <a href="https://bs.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> POST parametri. Slanje obrasca prekinuto.';
$BL['CSRF_POST_FAILED'] = 'Provjera <a href="https://bs.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> POST parametara nije uspjela. Slanje prekinuto.';
$BL['CSRF_GET_INVALID'] = 'Nisu pronađeni <a href="https://bs.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> GET parametri. Navigacija prekinuta.';
$BL['CSRF_GET_FAILED'] = 'Provjera <a href="https://bs.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> GET parametara nije uspjela. Navigacija prekinuta.';
$BL['CSRF_BTN_BACK'] = 'Nazad';
$BL['CSRF_BTN_LOGIN'] = 'Prijava';
$BL['CSRF_BTN_DASHBOARD'] = 'Kontrolna tabla';


// Batch 2 complete translation for bs
$BL['be_subnav_file_actions'] = 'akcije nad fajlovima';
$BL['be_ftptakeover_new_folder'] = 'kreiraj folder';
$BL['be_ftptakeover_new_folder_placeholder'] = 'naziv novog foldera u korijenskom direktoriju';
$BL['be_ftabhelp_add'] = 'dodaj novi privatni direktorij';
$BL['be_ftabhelp_upload'] = 'učitaj novi fajl u privatni direktorij';
$BL['be_ftabhelp_disablethumb'] = 'onemogući minijature u listi fajlova';
$BL['be_ftabhelp_enablethumb'] = 'omogući minijature u listi fajlova';
$BL['be_ftabhelp_edit'] = 'uredi informacije o fajlu';
$BL['be_ftabhelp_cut'] = 'isijeci fajl u međuspremnik';
$BL['be_ftabhelp_cutmark'] = 'označeni fajl je u međuspremniku';
$BL['be_ftabhelp_paste'] = 'zalijepi fajl u ovaj direktorij';
$BL['be_ftabhelp_download'] = 'preuzmi fajl';
$BL['be_ftabhelp_delete'] = 'obriši direktorij ili premjesti fajl u smeće';
$BL['be_ftabhelp_cantdelete'] = 'direktorij se ne može obrisati jer sadrži fajlove ili podfoldere';
$BL['be_ftabhelp_restore'] = 'vrati fajl iz smeća';
$BL['be_ftabhelp_openfolder'] = 'otvori sve foldere i podfoldere';
$BL['be_ftabhelp_closefolder'] = 'zatvori sve foldere i podfoldere';
$BL['be_ftabhelp_inactive'] = 'fajl ili folder je neaktivan - kliknite za aktivaciju';
$BL['be_ftabhelp_active'] = 'fajl ili folder je aktivan - kliknite za deaktivaciju';
$BL['be_ftabhelp_private'] = 'fajl ili folder je privatan - kliknite za javni status';
$BL['be_ftabhelp_public'] = 'fajl ili folder je javan - kliknite za privatni status';
$BL['be_fpriv_errordir'] = 'greška: direktorij ne može biti podfolder samog sebe';
$BL['be_fprivup_err7'] = 'Iz sigurnosnih razloga, fajl %s se ne može učitati.';
$BL['be_fprivup_err8'] = 'Fajlovi s ekstenzijom %s nisu dozvoljeni. Dozvoljene ekstenzije: %s.';
$BL['be_fprivup_err9'] = 'Fajlovi bez ekstenzije nisu dozvoljeni. Dozvoljene ekstenzije: %s.';
$BL['be_fprivup_err12'] = 'Fajl <strong>%s</strong> već postoji u ciljnom direktoriju.';
$BL['be_admin_struct_alt'] = 'alternativni naslov kategorije';
$BL['be_cnt_poll_choices'] = 'opcije';
$BL['be_cnt_search_default_type'] = 'vrsta pretrage';
$BL['be_cnt_sitemap_without_parent'] = 'bez početnog nivoa';
$BL['be_cnt_pages_php_render_warning'] = 'ugrađeni PHP <code>$phpwcms[\'enable_inline_php\']</code> je onemogućen';
$BL['be_cnt_optin'] = 'Double Opt-In';
$BL['be_cnt_doubleoptin'] = 'omogući Double Opt-In u skladu sa <a href="https://bs.wikipedia.org/wiki/GDPR" target="_blank">GDPR</a>';
$BL['be_cnt_novalidate'] = 'Novalidate';
$BL['be_cnt_status'] = 'vidljivost alijasa sadržaja';
$BL['be_cnt_plugin_n.a.'] = 'dodatak nije dostupan';
$BL['gd_not_loaded'] = '<strong>GD biblioteka nije dostupna!</strong> Provjerite je li PHP GD biblioteka aktivirana.';
$BL['be_cnt_search_hidesummary'] = 'sakrij uvodni tekst pretrage';
$BL['be_cnt_search_searchnot'] = 'ne pretražuj';
$BL['be_longdatetime'] = 'd.m.Y H:i:s';
$BL['be_check_login_allow_email'] = 'Prihvati e-mail kao korisničko ime';
$BL['be_cnt_imagenocenter'] = 'ne centriraj';
$BL['be_cnt_imagecenterh'] = 'centriraj horizontalno';
$BL['be_cnt_imagecenterv'] = 'centriraj vertikalno';
$BL['be_check_against_category_alias'] = 'poveži pojedinačni članak unutar nivoa strukture sa nivoom strukture';
$BL['be_editor_fullscreen'] = 'Cijeli ekran';
$BL['be_editor_wordwrap']   = 'Prelom teksta';

// Theme selection
$BL['be_theme'] = 'Tema';
$BL['be_theme_auto'] = 'Auto (Sistem)';
$BL['be_theme_light'] = 'Svijetla';
$BL['be_theme_dark'] = 'Tamna';

// Custom Content Parts
$BL['be_admin_custom_cpt']               = 'Custom Content Parts';
$BL['be_admin_custom_cpt_add']           = 'Novi Custom Content Part';
$BL['be_admin_custom_cpt_edit']          = 'Uredi Custom Content Part';
$BL['be_admin_custom_cpt_new']           = 'Kreiraj Custom Content Part';
$BL['be_admin_custom_cpt_title']         = 'Naslov / Naziv za prikaz';
$BL['be_admin_custom_cpt_key']           = 'Identifikacijski ključ / Alias';
$BL['be_admin_custom_cpt_mode']          = 'Režim';
$BL['be_admin_custom_cpt_repeater']      = 'Ponavljač (Lista)';
$BL['be_admin_custom_cpt_single']        = 'Pojedinačna stavka';
$BL['be_admin_custom_cpt_desc']          = 'Opis';
$BL['be_admin_custom_cpt_icon']          = 'Ikona';
$BL['be_admin_custom_cpt_fields']        = 'Definicije polja';
$BL['be_admin_custom_cpt_add_field']     = 'Dodaj polje';
$BL['be_admin_custom_cpt_field_key']     = 'Ključ (Oznaka)';
$BL['be_admin_custom_cpt_field_label']   = 'Oznaka / Naslov';
$BL['be_admin_custom_cpt_field_type']    = 'Tip';
$BL['be_admin_custom_cpt_field_config']  = 'Opcije / Konfiguracija';
$BL['be_admin_custom_cpt_actions']       = 'Akcije';
$BL['be_admin_custom_cpt_registered']    = 'Konfigurisani Custom Content Parts';
$BL['be_admin_custom_cpt_import']        = 'Uvezi JSON';
$BL['be_admin_custom_cpt_export']        = 'Izvezi JSON';
$BL['be_admin_custom_cpt_upload_json']   = 'Učitaj JSON datoteku';
$BL['be_admin_custom_cpt_paste_json']    = 'Ili zalijepi JSON sadržaj';
$BL['be_admin_custom_cpt_modal_import']  = 'Uvezi Custom Content Part';
$BL['be_admin_custom_cpt_no_entries']    = 'Još nema definisanih Custom Content Parts.';
$BL['be_admin_custom_cpt_create_one']    = 'Kreiraj sada';
$BL['be_admin_custom_cpt_preset']        = 'Predefinisano';
$BL['be_admin_custom_cpt_legacy']        = 'Naslijeđeno (Legacy)';
$BL['be_admin_custom_cpt_readonly_file'] = 'Samo za čitanje (Datoteka)';
$BL['be_admin_custom_cpt_delete_confirm'] = 'Obrisati ovu definiciju Custom Content Part-a?';
$BL['be_admin_custom_cpt_err_save']      = 'Greška pri spremanju Custom Content Part-a.';
$BL['be_admin_custom_cpt_err_import']    = 'Nevažeći JSON podaci za uvoz.';
$BL['be_admin_custom_cpt_divider']       = 'Razdjelnik';
$BL['be_admin_custom_cpt_default_val']   = 'Zadana vrijednost&#8230;';
$BL['be_admin_custom_cpt_opt_placeholder'] = 'ključ:Oznaka (jedna po liniji)';
$BL['be_admin_custom_cpt_scaffold']      = 'Početni šablon';
$BL['be_admin_custom_cpt_scaffold_help'] = 'Kopirajte i sačuvajte kao datoteku šablona pod:';
$BL['be_admin_custom_cpt_copy']          = 'Kopiraj u međuspremnik';
$BL['be_admin_custom_cpt_copied']        = 'Kopirano!';
$BL['be_admin_custom_cpt_create_template'] = 'Kreiraj datoteku početnog šablona';
$BL['be_admin_custom_cpt_create_template_help'] = 'Automatski kreiraj datoteku šablona:';
$BL['be_admin_custom_cpt_template_exists'] = 'Datoteka šablona već postoji:';
$BL['be_admin_custom_cpt_template_saved'] = 'Datoteka šablona uspješno sačuvana:';
$BL['be_admin_custom_cpt_overwrite_template'] = 'Prepiši datoteku šablona:';
$BL['be_admin_custom_cpt_err_duplicate_key'] = 'Identifikacijski ključ "%s" se već koristi u drugom Custom Content Part-u.';
$BL['be_admin_custom_cpt_err_duplicate_key_simple'] = 'Ovaj identifikacijski ključ se već koristi u drugom Custom Content Part-u.';
$BL['be_admin_custom_cpt_key_in_use']    = 'Ključ je zaključan jer ga koristi %d dijelova sadržaja.';
$BL['be_admin_custom_cpt_delete_field_confirm'] = 'Zaista obrisati polje "%s"?';
$BL['be_admin_custom_cpt_delete_field_confirm_simple'] = 'Zaista obrisati ovo polje?';
$BL['be_admin_custom_cpt_delete_entry_confirm'] = 'Zaista ukloniti stavku %s?';
$BL['be_admin_custom_cpt_delete_entry_confirm_simple'] = 'Zaista ukloniti ovu stavku?';
$BL['be_admin_custom_cpt_err_reserved_field_key'] = 'Ključ polja "%s" je rezervisani standardni naziv oznake i ne može se koristiti.';
$BL['be_admin_custom_cpt_err_reserved_field_key_simple'] = 'Rezervisani standardni naziv oznake (npr. TITLE, SUBTITLE, TEXT itd.).';
