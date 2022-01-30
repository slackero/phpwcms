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


// Language: English, Language Code: en
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'
// translated in romana by culda_a   http://www.wd-media.com

$BL['usr_online']                       = 'utilizator online';

// Login Page
$BL["login_text"]                       = 'Introduceti datele de logare';
$BL['login_error']                      = 'Error la login!';
$BL["login_username"]                   = 'utilizator';
$BL["login_userpass"]                   = 'parola';
$BL["login_button"]                     = 'Logare';
$BL["login_lang"]                       = 'Selecteaza limba';

// phpwcms.php
$BL['be_nav_logout']                    = 'LOGOUT';
$BL['be_nav_articles']                  = 'ARTICOLE';
$BL['be_nav_files']                     = 'FISIER';
$BL['be_nav_modules']                   = 'MODULE';
$BL['be_nav_messages']                  = 'MESAJE';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISCUTII';

$BL['be_page_title']                    = 'phpwcms backend (administrare)';

$BL['be_subnav_article_center']         = 'centru articole';
$BL['be_subnav_article_new']            = 'articol nou';
$BL['be_subnav_file_center']            = 'centru fisiere';
$BL['be_subnav_file_ftptakeover']       = 'preluare ftp';
$BL['be_subnav_mod_artists']            = 'artist, categorie, stil';
$BL['be_subnav_msg_center']             = 'centru mesaje';
$BL['be_subnav_msg_new']                = 'Mesaj nou';
$BL['be_subnav_msg_newsletter']         = 'newsletter abonati';
$BL['be_subnav_chat_main']              = 'chat pagina principala';
$BL['be_subnav_chat_internal']          = 'chat intern';
$BL['be_subnav_profile_login']          = 'login informatii';
$BL['be_subnav_profile_personal']       = 'Date personale';
$BL['be_subnav_admin_pagelayout']       = 'aranjament pagina';
$BL['be_subnav_admin_templates']        = 'templates';
$BL['be_subnav_admin_css']              = 'standard css';
$BL['be_subnav_admin_sitestructure']    = 'structura site';
$BL['be_subnav_admin_users']            = 'administrare user';
$BL['be_subnav_admin_filecat']          = 'categorii fisiere';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'articol ID';
$BL['be_func_struct_preview']           = 'previzualizare';
$BL['be_func_struct_edit']              = 'editeaza articol';
$BL['be_func_struct_sedit']             = 'editeaza nivel structura';
$BL['be_func_struct_cut']               = 'Taie articol';
$BL['be_func_struct_nocut']             = 'Dezactiveaza taiere articol';
$BL['be_func_struct_svisible']          = 'schimba vizibil/invizibl';
$BL['be_func_struct_spublic']           = 'schimba public/privat';
$BL['be_func_struct_sort_up']           = 'sorteaza sus';
$BL['be_func_struct_sort_down']         = 'sorteaza jos';
$BL['be_func_struct_del_article']       = 'sterge articol';
$BL['be_func_struct_del_jsmsg']         = 'Doresti sa  \nto stergi articolul?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'creaza articol nou in nivelul de structura';
$BL['be_func_struct_paste_article']     = 'paste articol in nivelul de structura';
$BL['be_func_struct_insert_level']      = 'introdu nivelul de structura in';
$BL['be_func_struct_paste_level']       = 'paste nivelul de structura';
$BL['be_func_struct_cut_level']         = 'Taie nivelul de structura';
$BL['be_func_struct_no_cut']            = "Nu este posibil sa tai nivelul root!";
$BL['be_func_struct_no_paste1']         = "Nu este posibil sa faci paste aici!";
$BL['be_func_struct_no_paste2']         = 'este "copilul" in linia root a nivelului tree';
$BL['be_func_struct_no_paste3']         = 'asta ar trebui facut paste aici';
$BL['be_func_struct_paste_cancel']      = 'anuleaza modificarea nivelului de structura';
$BL['be_func_struct_del_struct']        = 'sterge nivelul de structura';
$BL['be_func_struct_del_sjsmsg']        = 'Doresti sa \nto stergi nivelul de structura?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'deschide';
$BL['be_func_struct_close']             = 'inchide';
$BL['be_func_struct_empty']             = 'goleste';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'text plin';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'code';
$BL['be_ctype_textimage']               = 'text w/image';
$BL['be_ctype_images']                  = 'imagini';
$BL['be_ctype_bulletlist']              = 'lista (tabele)';
$BL['be_ctype_ullist']                  = 'lista';
$BL['be_ctype_link']                    = 'link &amp; email';
$BL['be_ctype_linklist']                = 'lista link';
$BL['be_ctype_linkarticle']             = 'link articol';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'lista fisiere';
$BL['be_ctype_emailform']               = 'generator formular email';
$BL['be_ctype_newsletter']              = 'newsletter';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profil creat cu succes.';
$BL['be_profile_create_error']          = 'O erroare a aparut in tipul creerii.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Datele profilului actualizate cu succes.';
$BL['be_profile_update_error']          = 'A aparut o erroare la actualizare.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'utilizator {VAL} incorect';
$BL['be_profile_account_err2']          = 'parola prea scurta (doar {VAL} caractere: cel putin 5 caractere)';
$BL['be_profile_account_err3']          = 'parola trebuie sa fie identica';
$BL['be_profile_account_err4']          = 'email {VAL} invalid';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'datele tale personale';
$BL['be_profile_data_text']             = 'datele personale sunt optionale. Aceasta poate ajuta utilizatorii sau vizitatorii sa afle mai multe despre tine, interese aptitudini. Daca selectezi checkbox corespunzator utilizatorii pot sa vada profilul tau in zona publica sau in paginile articolelor(sau Nu ).';
$BL['be_profile_label_title']           = 'titlu';
$BL['be_profile_label_firstname']       = 'Nume';
$BL['be_profile_label_name']            = 'Prenume';
$BL['be_profile_label_company']         = 'companie';
$BL['be_profile_label_street']          = 'strada';
$BL['be_profile_label_city']            = 'oras';
$BL['be_profile_label_state']           = 'judet';
$BL['be_profile_label_zip']             = 'cod postal';
$BL['be_profile_label_country']         = 'tara';
$BL['be_profile_label_phone']           = 'telefon';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobil';
$BL['be_profile_label_signature']       = 'semnatura';
$BL['be_profile_label_notes']           = 'nota';
$BL['be_profile_label_profession']      = 'profesia';
$BL['be_profile_label_newsletter']      = 'newsletter';
$BL['be_profile_text_newsletter']       = 'Doresc sa primesc newsletter.';
$BL['be_profile_label_public']          = 'public';
$BL['be_profile_text_public']           = 'Tata lumea poata sa vada profilul meu personal.';
$BL['be_profile_label_button']          = 'actulizeaza profiul persona';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'informatiile tale de logare';
$BL['be_profile_account_text']          = 'In mod normal nu este necesar sa schimbi utilitorul.<br />Este indicat sa schimbi parola din cand in cand pentru a asigura o securitate ridicata.';
$BL['be_profile_label_err']             = 'te rog bifeaza';
$BL['be_profile_label_username']        = 'utilizator';
$BL['be_profile_label_newpass']         = 'noua parola';
$BL['be_profile_label_repeatpass']      = 'repeta noua parola';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'actualizeaza';
$BL['be_profile_label_lang']            = 'limba';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'preaia fisierele incarcare via ftp';
$BL['be_ftptakeover_mark']              = 'marcheaza';
$BL['be_ftptakeover_available']         = 'fisiere disponibile';
$BL['be_ftptakeover_size']              = 'marime';
$BL['be_ftptakeover_nofile']            = 'nu sunt fisiere disponibile &#8211; trebuie incarcate via ftp';
$BL['be_ftptakeover_all']               = 'INCARCA';
$BL['be_ftptakeover_directory']         = 'director';
$BL['be_ftptakeover_rootdir']           = 'root director';
$BL['be_ftptakeover_needed']            = 'necesar!!! (trebuie sa selectezi unul)';
$BL['be_ftptakeover_optional']          = 'optional';
$BL['be_ftptakeover_keywords']          = 'cuvinte cheie';
$BL['be_ftptakeover_additional']        = 'aditional';
$BL['be_ftptakeover_longinfo']          = 'info detaliate';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'activ';
$BL['be_ftptakeover_public']            = 'public';
$BL['be_ftptakeover_createthumb']       = 'creaza previzualizare';
$BL['be_ftptakeover_button']            = 'preia fisierele selectate';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'centru fisiere';
$BL['be_ftab_createnew']                = 'creaza director nou in root';
$BL['be_ftab_paste']                    = 'paste fisier clipboard in directorul root';
$BL['be_ftab_disablethumb']             = 'dezactivare previzualizare in lista';
$BL['be_ftab_enablethumb']              = 'activeaza previzualizare in lista';
$BL['be_ftab_private']                  = 'fisiere&nbsp;private';
$BL['be_ftab_public']                   = 'fisiere&nbsp;publice';
$BL['be_ftab_search']                   = 'Cauta';
$BL['be_ftab_trash']                    = 'cos&nbsp;gunoi';
$BL['be_ftab_open']                     = 'deschide toate directoarele';
$BL['be_ftab_close']                    = 'inchide toate directoarele';
$BL['be_ftab_upload']                   = 'incarca fisier in directorul root';
$BL['be_ftab_filehelp']                 = 'deschide fisier Ajutor';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'director root';
$BL['be_fpriv_title']                   = 'creaza director nou';
$BL['be_fpriv_inside']                  = 'interior';
$BL['be_fpriv_error']                   = 'error: completeaza numele directorului';
$BL['be_fpriv_name']                    = 'nume';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'creaza director nou';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'editeaza director';
$BL['be_fpriv_newname']                 = 'nume nou';
$BL['be_fpriv_updatebutton']            = 'actualizeaza info director';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Selecteaza fisier pentru a incarca';
$BL['be_fprivup_err2']                  = 'Marimea fisierului incarcat este mai mare de';
$BL['be_fprivup_err3']                  = 'Erroare la scrierea fisierului';
$BL['be_fprivup_err4']                  = 'Erroare la crearea directorului.';
$BL['be_fprivup_err5']                  = 'nu exista previzualizare';
$BL['be_fprivup_err6']                  = 'Va rugam NU incercati din nou - este o erroare de serverr! Contacteaza  <a href="mailto:{VAL}">webmasterul</a> cat mai repede posibil!';
$BL['be_fprivup_title']                 = 'incarca fisiere';
$BL['be_fprivup_button']                = 'Incarca fisier';
$BL['be_fprivup_upload']                = 'Incarca';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'editeaza informatiile fisierului';
$BL['be_fprivedit_filename']            = 'nume fisier';
$BL['be_fprivedit_created']             = 'creat';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'verifica numele fisierului (seteaza inapoi la original)';
$BL['be_fprivedit_clockwise']           = 'roteste previzualizarea in sensul acelor de ceasornic [fisier original +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'roteste previzualizarea invers acelor de ceasornic [original file -90&deg;]';
$BL['be_fprivedit_button']              = 'actualizeaza info fisier';
$BL['be_fprivedit_size']                = 'marime';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'incarca fisier in director';
$BL['be_fprivfunc_makenew']             = 'creaza director nou';
$BL['be_fprivfunc_paste']               = 'paste fisier clipboard in director';
$BL['be_fprivfunc_edit']                = 'editeaza director';
$BL['be_fprivfunc_cactive']             = 'schimba activ/inactiv';
$BL['be_fprivfunc_cpublic']             = 'schimba public/privat';
$BL['be_fprivfunc_deldir']              = 'sterge director';
$BL['be_fprivfunc_jsdeldir']            = 'Doresti sa \nto stergi directorul';
$BL['be_fprivfunc_notempty']            = 'dir {VAL} nu este gol!';
$BL['be_fprivfunc_opendir']             = 'deschide director';
$BL['be_fprivfunc_closedir']            = 'inchide director';
$BL['be_fprivfunc_dlfile']              = 'download fisier';
$BL['be_fprivfunc_clipfile']            = 'fisier clipboard';
$BL['be_fprivfunc_cutfile']             = 'taie';
$BL['be_fprivfunc_editfile']            = 'editeaza info fisier';
$BL['be_fprivfunc_cactivefile']         = 'schimba activ/inactiv';
$BL['be_fprivfunc_cpublicfile']         = 'schimba public/privat';
$BL['be_fprivfunc_movetrash']           = 'pune la cosul de gunoi';
$BL['be_fprivfunc_jsmovetrash1']        = 'Doresti sa pui';
$BL['be_fprivfunc_jsmovetrash2']        = 'iin directorul Cos de gunoi?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'nu sunt fisere sau directoare private';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'utilizator';
$BL['be_fpublic_nofiles']               = 'nu sunt fisere sau directoare private';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'cosul de gunoi este gol';
$BL['be_ftrash_show']                   = 'arata fisierele private';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Vrei sa restaurezi {VAL} \nand sa le pui inapoi in lista de privat?';
$BL['be_ftrash_delete']                 = 'Doresti sa stergi {VAL}?';
$BL['be_ftrash_undo']                   = 'restaureaza (undo cos gunoi)';
$BL['be_ftrash_delfinal']               = 'stergere finala';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'lista de cautari e goala.';
$BL['be_fsearch_title']                 = 'cauta fisiere';
$BL['be_fsearch_infotext']              = 'Cautare de baza pentru info fisiere. Cauta rezulatate in cuvinte cheie,<br />nume fisier si info fisier. Nu suporta "joker"(simbol care tine loc unei litere). Separa cautari multiple <br />a cuvintelor cu un spatiu gol. Selecteaza Si/Sau dupa ce fel de fisiere sa caute: personal/public.';
$BL['be_fsearch_nonfound']              = 'nici un fisier gasit pentru cautarea ta. corecteaza valorile cautarii (introdu alte criterii)!';
$BL['be_fsearch_fillin']                = 'te rog completeaza intr-un camp de cautare, campul de mai sus.';
$BL['be_fsearch_searchlabel']           = 'cauta dupa';
$BL['be_fsearch_startsearch']           = 'incepe cautarea';
$BL['be_fsearch_and']                   = 'Si';
$BL['be_fsearch_or']                    = 'Sau';
$BL['be_fsearch_all']                   = 'toate fisierele';
$BL['be_fsearch_personal']              = 'privat';
$BL['be_fsearch_public']                = 'public';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'chat intern';
$BL['be_chat_info']                     = 'Aici poti discuta cu alti utilizatori despre ceea ce doresti. Aceasta sectiune este pentru chat in direct dar poti lasa mesaj pentru oricine care vrea sa citeasca.';
$BL['be_chat_start']                    = 'click aici pentru a incepe un chat';
$BL['be_chat_lines']                    = 'linii de chat';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'centru mesaje';
$BL['be_msg_new']                       = 'nou';
$BL['be_msg_old']                       = 'vechi';
$BL['be_msg_senttop']                   = 'trimis';
$BL['be_msg_del']                       = 'sters';
$BL['be_msg_from']                      = 'de la';
$BL['be_msg_subject']                   = 'subiect';
$BL['be_msg_date']                      = 'data/timp';
$BL['be_msg_close']                     = 'inchide mesaj';
$BL['be_msg_create']                    = 'creaza un nou mesaj';
$BL['be_msg_reply']                     = 'raspunde la acest mesaj';
$BL['be_msg_move']                      = 'muta acest mesaj la gunoi';
$BL['be_msg_unread']                    = 'necitit sau mesaj nou';
$BL['be_msg_lastread']                  = 'ultimele {VAL} mesaje citite';
$BL['be_msg_lastsent']                  = 'ultimele {VAL} mesaje trimise';
$BL['be_msg_marked']                    = 'mesaj marcat pentru stergere (cos gunoi)';
$BL['be_msg_nomsg']                     = 'nu au fost gasite mesaje in acest director';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'trimis de';
$BL['be_msg_on']                        = 'in';
$BL['be_msg_msg']                       = 'mesaj';
$BL['be_msg_err1']                      = 'ai uitat sa treci un destinatar...';
$BL['be_msg_err2']                      = 'completeaza campul de subiect (pentru o buna manevrare a mesajului)';
$BL['be_msg_err3']                      = 'nu are sens sa trimiti un mesaj fara mesaj in el ;-)';
$BL['be_msg_sent']                      = 'noul mesaj a fost trimis!';
$BL['be_msg_fwd']                       = 'vei fi directionat la centru de mesaje sau';
$BL['be_msg_newmsgtitle']               = 'scrie mesaj nou';
$BL['be_msg_err']                       = 'error la trimitere mesaj';
$BL['be_msg_sendto']                    = 'trimite mesaj la';
$BL['be_msg_available']                 = 'lista cu destinatari disponibili';
$BL['be_msg_all']                       = 'trimite mesaj la toti destinatarii selectati';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'newsletter abonati';
$BL['be_newsletter_titleedit']          = 'editeaza newsletter pt abonati';
$BL['be_newsletter_new']                = 'creaza newsletter nou';
$BL['be_newsletter_add']                = 'adauga&nbsp;newsletter&nbsp;abonati';
$BL['be_newsletter_name']               = 'nume';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'Saalveaza abonati';
$BL['be_newsletter_button_cancel']      = 'Anuleaza';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'utilizator invalid, alege altul';
$BL['be_admin_usr_err2']                = 'camp utilizator este gol (necesar)';
$BL['be_admin_usr_err3']                = 'camp parola e gol (necesar)';
$BL['be_admin_usr_err4']                = "emailul nu este valid ";
$BL['be_admin_usr_err']                 = 'error';
$BL['be_admin_usr_mailsubject']         = 'Bine ai venit la phpwcms "backend"';
$BL['be_admin_usr_mailbody']            = "Bine ai venit la phpwcms BACKEND\n\n    utilizator: {LOGIN}\n    parola: {PASSWORD}\n\n\nTe poti loga aici: {LOGIN_PAGE}\n\nwdmedia admin\n ";
$BL['be_admin_usr_title']               = 'adauga un nou cont de utilizator';
$BL['be_admin_usr_realname']            = 'nume real';
$BL['be_admin_usr_setactive']           = 'seteaza utilizator activ';
$BL['be_admin_usr_iflogin']             = 'daca utilizatorul e setat se poate loga';
$BL['be_admin_usr_isadmin']             = 'utilizatorul este admin';
$BL['be_admin_usr_ifadmin']             = 'daca il setezi utilizatorul va primi drepturi de admin';
$BL['be_admin_usr_verify']              = 'verificare';
$BL['be_admin_usr_sendemail']           = 'trimite un email la noul utilizator cu datele contului sau';
$BL['be_admin_usr_button']              = 'trimite datele utilizatorului';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'editeaza contul utilizatorului';
$BL['be_admin_usr_emailsubject']        = 'wdmedia - date cont modificate';
$BL['be_admin_usr_emailbody']           = "Wdmedia date utilizator schimbate\n\n    utilizator: {LOGIN}\n    parola: {PASSWORD}\n\n\nTe poti loga: {LOGIN_PAGE}\n\nwdmedia admin\n ";
$BL['be_admin_usr_passnochange']        = '[NICI O SCHIMBARE - UTILIZEAZA PAROLA DEJA CUNOSCUTA]';
$BL['be_admin_usr_ebutton']             = 'actualizeaza date utilizator';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'wdmedia lista utilizatori';
$BL['be_admin_usr_ldel']                = 'ATENTIE!&#13;Aceasta va sterge utilizatorul';
$BL['be_admin_usr_create']              = 'creaza utilizator nou';
$BL['be_admin_usr_editusr']             = 'editeaza utilizator';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'structura site';
$BL['be_admin_struct_child']            = '(copilul lui)';
$BL['be_admin_struct_index']            = 'index (website start)';
$BL['be_admin_struct_cat']              = 'Titlu categorie';
$BL['be_admin_struct_hide1']            = 'ascunde';
$BL['be_admin_struct_hide2']            = 'aceasta&nbsp;categorie&nbsp;in&nbsp;meniu';
$BL['be_admin_struct_info']             = 'descriere categorie';
$BL['be_admin_struct_template']         = 'template';
$BL['be_admin_struct_alias']            = 'aliasul acestei categorii';
$BL['be_admin_struct_visible']          = 'vizibil';
$BL['be_admin_struct_button']           = 'trimite datele categoriei';
$BL['be_admin_struct_close']            = 'inchide';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'fisier categorii';
$BL['be_admin_fcat_err']                = 'numele categoriei este gol!';
$BL['be_admin_fcat_name']               = 'nume categorie';
$BL['be_admin_fcat_needed']             = 'necesar';
$BL['be_admin_fcat_button1']            = 'actualizeaza';
$BL['be_admin_fcat_button2']            = 'creaza';
$BL['be_admin_fcat_delmsg']             = 'Doresti sa\nto sterfi cheia fisierului?';
$BL['be_admin_fcat_fcat']               = 'fisier categorie';
$BL['be_admin_fcat_err1']               = 'numele cheie a fisierului este gol!';
$BL['be_admin_fcat_fkeyname']           = 'nume cheie a fisierului';
$BL['be_admin_fcat_exit']               = 'exit editare';
$BL['be_admin_fcat_addkey']             = 'adauga cheie noua';
$BL['be_admin_fcat_editcat']            = 'editeaza nume categorie';
$BL['be_admin_fcat_delcatmsg']          = 'Doresti sa\nto stergi fisierul categorie?';
$BL['be_admin_fcat_delcat']             = 'sterge fisier categorie';
$BL['be_admin_fcat_delkey']             = 'sterge numele cheie a fisierului';
$BL['be_admin_fcat_editkey']            = 'editeaza cheie';
$BL['be_admin_fcat_addcat']             = 'creaza fisier categorie nou';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'setup accesare fata: aranjament pagina (layout)';
$BL['be_admin_page_align']              = 'aliniere pagina';
$BL['be_admin_page_align_left']         = 'aliniere standard (stanga) a intregului continut';
$BL['be_admin_page_align_center']       = 'centru, intregul continul';
$BL['be_admin_page_align_right']        = 'aliniere la dreapta a intregului continut';
$BL['be_admin_page_margin']             = 'margini';
$BL['be_admin_page_top']                = 'Sus';
$BL['be_admin_page_bottom']             = 'jos';
$BL['be_admin_page_left']               = 'stanga';
$BL['be_admin_page_right']              = 'dreapta';
$BL['be_admin_page_bg']                 = 'fundal';
$BL['be_admin_page_color']              = 'culoare';
$BL['be_admin_page_height']             = 'inaltime';
$BL['be_admin_page_width']              = 'latime';
$BL['be_admin_page_main']               = 'principal';
$BL['be_admin_page_leftspace']          = 'spatiu la stanga';
$BL['be_admin_page_rightspace']         = 'spatiu la dreapta';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'imagine';
$BL['be_admin_page_text']               = 'text';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'vizitat';
$BL['be_admin_page_pagetitle']          = 'titlu&nbsp;pagina';
$BL['be_admin_page_addtotitle']         = 'add&nbsp;to&nbsp;title';
$BL['be_admin_page_category']           = 'categorie';
$BL['be_admin_page_articlename']        = 'nume&nbsp;articol';
$BL['be_admin_page_blocks']             = 'blocuri';
$BL['be_admin_page_allblocks']          = 'toate blocurile';
$BL['be_admin_page_col1']               = '3 coloane layout';
$BL['be_admin_page_col2']               = '2 coloane layout (coloana principala la dreapta, coloana de navigare la stanga)';
$BL['be_admin_page_col3']               = '2 coloane layout (coloana principala la stanga, oloana de navigare la dreapta)';
$BL['be_admin_page_col4']               = '1 coloana layout';
$BL['be_admin_page_header']             = 'header';
$BL['be_admin_page_footer']             = 'footer';
$BL['be_admin_page_topspace']           = 'spatiu&nbsp;sus';
$BL['be_admin_page_bottomspace']        = 'spatiu&nbsp;jos';
$BL['be_admin_page_button']             = 'salveaza layout pagina';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'setup acces fata: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'salveaza css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'setup acces fata: templates';
$BL['be_admin_tmpl_default']            = 'standard';
$BL['be_admin_tmpl_add']                = 'adauga&nbsp;template';
$BL['be_admin_tmpl_edit']               = 'editeaza template';
$BL['be_admin_tmpl_new']                = 'creaza nou';
$BL['be_admin_tmpl_css']                = 'fisier css';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'error';
$BL['be_admin_tmpl_button']             = 'salveaza template';
$BL['be_admin_tmpl_name']               = 'nume';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'structura site si lista articole';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'titlul pentru acest articol nu exista';
$BL['be_article_err2']                  = 'data de incepere este gresita - seteaza acum';
$BL['be_article_err3']                  = 'data de terminare e gresita - seteaza acum';
$BL['be_article_title1']                = 'informatii de baza despre articol';
$BL['be_article_cat']                   = 'categorie';
$BL['be_article_atitle']                = 'titlu articol';
$BL['be_article_asubtitle']             = 'subtitlu';
$BL['be_article_abegin']                = 'incepe';

$BL['be_article_aend']                  = 'termina';
$BL['be_article_aredirect']             = 'redirectioneaza la';
$BL['be_article_akeywords']             = 'cuvinte cheie';
$BL['be_article_asummary']              = 'sumar';
$BL['be_article_abutton']               = 'creaza articol nou';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'data expirarii este gresita - seteaza acum + 1 saptamana';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'editeaza info de baza la articol';
$BL['be_article_eslastedit']            = 'ultima editare';
$BL['be_article_esnoupdate']            = 'formular neactualizat';
$BL['be_article_esbutton']              = 'actualizeaza datele articolului';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'continut articol';
$BL['be_article_cnt_type']              = 'tipul continutului';
$BL['be_article_cnt_space']             = 'spatiu';
$BL['be_article_cnt_before']            = 'inainte';
$BL['be_article_cnt_after']             = 'dupa';
$BL['be_article_cnt_top']               = 'sus';
$BL['be_article_cnt_toplink']           = 'arata sus link';
$BL['be_article_cnt_ctitle']            = 'titlu continut';
$BL['be_article_cnt_back']              = 'info articol complet';
$BL['be_article_cnt_button1']           = 'Actualizeaza';
$BL['be_article_cnt_button2']           = 'Creaza';
$BL['be_article_cnt_button3']           = 'Salveaza &amp; inchide';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'informatii articol';
$BL['be_article_cnt_ledit']             = 'editeaza articol';
$BL['be_article_cnt_lvisible']          = 'schimba vizibl/invizibl';
$BL['be_article_cnt_ldel']              = 'sterge acest articol';
$BL['be_article_cnt_ldeljs']            = 'Sterge articol?';
$BL['be_article_cnt_redirect']          = 'redirectie';
$BL['be_article_cnt_edited']            = 'editat de';
$BL['be_article_cnt_start']             = 'data start';
$BL['be_article_cnt_end']               = 'data incheiere';
$BL['be_article_cnt_add']               = 'adauga';
$BL['be_article_cnt_addtitle']          = 'adauga noua parte de continut';
$BL['be_article_cnt_up']                = 'muta continut sus';
$BL['be_article_cnt_down']              = 'muta continut jos';
$BL['be_article_cnt_edit']              = 'editeaza parte continut';
$BL['be_article_cnt_delpart']           = 'sterge partea de continut a acestui articol';
$BL['be_article_cnt_delpartjs']         = 'Stergi partea de continut?';
$BL['be_article_cnt_center']            = 'Centru articole';

// content forms
$BL['be_cnt_plaintext']                 = 'text plin';
$BL['be_cnt_htmltext']                  = 'html text';
$BL['be_cnt_image']                     = 'imagine';
$BL['be_cnt_position']                  = 'pozitie';
$BL['be_cnt_pos0']                      = 'Deasupra, stanga';
$BL['be_cnt_pos1']                      = 'Deasupra, centru';
$BL['be_cnt_pos2']                      = 'Deasupra, dreapta';
$BL['be_cnt_pos3']                      = 'Jos, stanga';
$BL['be_cnt_pos4']                      = 'Jos, centru';
$BL['be_cnt_pos5']                      = 'Jos, dreapta';
$BL['be_cnt_pos6']                      = 'In text, stanga';
$BL['be_cnt_pos7']                      = 'In text, dreapta';
$BL['be_cnt_pos0i']                     = 'aliniaza imaginea deasupra si in stanga blocului de text';
$BL['be_cnt_pos1i']                     = 'aliniaza imaginea deasupra si centrata fata de blocul de text';
$BL['be_cnt_pos2i']                     = 'aliniaza imaginea deasupra si in dreapta blocului de text';
$BL['be_cnt_pos3i']                     = 'aliniaza imaginea dedesupt si in stanga blocului de text';
$BL['be_cnt_pos4i']                     = 'aliniaza imaginea dedesupt si centrata fata de blocul de text';
$BL['be_cnt_pos5i']                     = 'aliniaza imaginea dedesupt si in dreapta blocului de text';
$BL['be_cnt_pos6i']                     = 'aliniaza imaginea in stanga in blocul de text';
$BL['be_cnt_pos7i']                     = 'aliniaza imaginea in dreapta in blocul de text';
$BL['be_cnt_maxw']                      = 'max.&nbsp;latime';
$BL['be_cnt_maxh']                      = 'max.&nbsp;inaltime';
$BL['be_cnt_enlarge']                   = 'click&nbsp;mareste';
$BL['be_cnt_caption']                   = 'captare';
$BL['be_cnt_subject']                   = 'subiect';
$BL['be_cnt_recipient']                 = 'destinatar';
$BL['be_cnt_buttontext']                = 'buton text';
$BL['be_cnt_sendas']                    = 'trimite ca';
$BL['be_cnt_text']                      = 'text';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'campuri formular';
$BL['be_cnt_code']                      = 'code';
$BL['be_cnt_infotext']                  = 'info&nbsp;text';
$BL['be_cnt_subscription']              = 'abonare';
$BL['be_cnt_labelemail']                = 'eticheta&nbsp;email';
$BL['be_cnt_tablealign']                = 'aliniere&nbsp;tabel';
$BL['be_cnt_labelname']                 = 'nume&nbsp;eticheta';
$BL['be_cnt_labelsubsc']                = 'eticheta&nbsp;abonat';
$BL['be_cnt_allsubsc']                  = 'toti&nbsp;abonatii';
$BL['be_cnt_default']                   = 'standard';
$BL['be_cnt_left']                      = 'stanga';
$BL['be_cnt_center']                    = 'centru';
$BL['be_cnt_right']                     = 'dreapta';
$BL['be_cnt_buttontext']                = 'buton&nbsp;text';
$BL['be_cnt_successtext']               = 'success&nbsp;text';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'schimba.email';
$BL['be_cnt_openimagebrowser']          = 'deschide browser imagine';
$BL['be_cnt_openfilebrowser']           = 'deschide browser fisier';
$BL['be_cnt_sortup']                    = 'muta sus';
$BL['be_cnt_sortdown']                  = 'muta jos';
$BL['be_cnt_delimage']                  = 'sterge imaginea selectata';
$BL['be_cnt_delfile']                   = 'sterge fisierul selectat';
$BL['be_cnt_delmedia']                  = 'sterge fisierul media selectat';
$BL['be_cnt_column']                    = 'colana';
$BL['be_cnt_imagespace']                = 'spatiu&nbsp;imagine';
$BL['be_cnt_directlink']                = 'direct link';
$BL['be_cnt_target']                    = 'tinta';
$BL['be_cnt_target1']                   = 'in fereastra noua';
$BL['be_cnt_target2']                   = 'in farme-ul parinte a ferestrei';
$BL['be_cnt_target3']                   = 'in aceeasi fereastra fara frame-uri';
$BL['be_cnt_target4']                   = 'in aceeasi fereastra sau frame';
$BL['be_cnt_bullet']                    = 'lista (tabel)';
$BL['be_cnt_ullist']                    = 'lista';
$BL['be_cnt_ullist_desc']               = '~ = primul Nivel, &nbsp; ~~ = al 2-lea nivel, &nbsp; etc.';
$BL['be_cnt_linklist']                  = 'lista link-uri';
$BL['be_cnt_plainhtml']                 = 'html plin';
$BL['be_cnt_files']                     = 'fisiere';
$BL['be_cnt_description']               = 'descriere';
$BL['be_cnt_linkarticle']               = 'link articol';
$BL['be_cnt_articles']                  = 'articole';
$BL['be_cnt_movearticleto']             = 'muta articolele selectate la link lista articole';
$BL['be_cnt_removearticleto']           = 'sterge articolele selectate de la link lista articole';
$BL['be_cnt_mediatype']                 = 'tip media';
$BL['be_cnt_control']                   = 'control';
$BL['be_cnt_showcontrol']               = 'arata bara control';
$BL['be_cnt_autoplay']                  = 'autoplay';
$BL['be_cnt_source']                    = 'sursa';
$BL['be_cnt_internal']                  = 'intern';
$BL['be_cnt_openmediabrowser']          = 'deschide browser media';
$BL['be_cnt_external']                  = 'extern';
$BL['be_cnt_mediapos0']                 = 'stanga (standard)';
$BL['be_cnt_mediapos1']                 = 'centru';
$BL['be_cnt_mediapos2']                 = 'dreapta';
$BL['be_cnt_mediapos3']                 = 'bloc, stanga';
$BL['be_cnt_mediapos4']                 = 'bloc, dreapta';
$BL['be_cnt_mediapos0i']                = 'aliniaza media deasupra si in stanga blocului de text';
$BL['be_cnt_mediapos1i']                = 'aliniaza media deasupra si centrat fata de blocul de text';
$BL['be_cnt_mediapos2i']                = 'aliniaza media deasupra si in dreapta blocului de text';
$BL['be_cnt_mediapos3i']                = 'aliniaza media in stanga in cadrul blocului de text';
$BL['be_cnt_mediapos4i']                = 'aliniaza media in dreapta in cadrul blocului de text';
$BL['be_cnt_setsize']                   = 'seteaza marime';
$BL['be_cnt_set1']                      = 'seteaza marime media la 160x120px';
$BL['be_cnt_set2']                      = 'seteaza marime media la 240x180px';
$BL['be_cnt_set3']                      = 'seteaza marime media la 320x240px';
$BL['be_cnt_set4']                      = 'seteaza marime media la 480x360px';
$BL['be_cnt_set5']                      = 'anuleaza valori media latime si inaltime';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'creaza pagelayout nou';
$BL['be_admin_page_name']               = 'nume layout';
$BL['be_admin_page_edit']               = 'editeaza pagelayout';
$BL['be_admin_page_render']             = 'afisare';
$BL['be_admin_page_table']              = 'tabel';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'custom';
$BL['be_admin_page_custominfo']         = 'fromular template bloc principal';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'Nu exista layout disponibil!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'Cauta';
$BL['be_cnt_results']                   = 'rezultate';
$BL['be_cnt_results_per_page']          = 'pe&nbsp;pagina (daca e gol arata tot)';
$BL['be_cnt_opennewwin']                = 'deschide in fereastra noua';
$BL['be_cnt_searchlabeltext']           = 'aceste sunt texte si valori predefinite pt formularul de cautare si pagina de zu rezultatele cautarii.';
$BL['be_cnt_input']                     = 'introduceti';
$BL['be_cnt_style']                     = 'style';
$BL['be_cnt_result']                    = 'rezultate';
$BL['be_cnt_next']                      = 'urmatorul';
$BL['be_cnt_previous']                  = 'precedent';
$BL['be_cnt_align']                     = 'aliniere';
$BL['be_cnt_searchformtext']            = 'urmatoarele texte sunt listate cand formularul de cautare e deschis sau nu sunt rezultate pentru cautarea data.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'nu sunt rezultate';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'dezactiveaza';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'posesor articol';
$BL['be_article_adminuser']             = 'utilizator admin';
$BL['be_article_username']              = 'autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'vizibil doar pt utilizatori logiati ';
$BL['be_admin_struct_status']           = 'status meniu fatada';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'meniu articol';
$BL['be_cnt_sitelevel']                 = 'nivel site';
$BL['be_cnt_sitecurrent']               = 'nivelul curent al site-ului';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'text standard intrare spate';
$BL['be_ctype_ecard']                   = 'e-card';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'title/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail tmpl';
$BL['be_cnt_ecard_image']               = 'e-card imagine';
$BL['be_cnt_ecard_title']               = 'e-card titlu';
$BL['be_cnt_alignment']                 = 'aliniament';
$BL['be_cnt_ecardform']                 = 'form tmpl';
$BL['be_cnt_ecardform_err']             = 'Toate campurile marcate * sunt necesare';
$BL['be_cnt_ecardform_sender']          = 'Expeditor';
$BL['be_cnt_ecardform_recipient']       = 'Destinatar';
$BL['be_cnt_ecardform_name']            = 'Nume';
$BL['be_cnt_ecardform_msgtext']         = 'Mesajul tau catre destinatar';
$BL['be_cnt_ecardform_button']          = 'Trimite e-card';
$BL['be_cnt_ecardsend']                 = 'Trimite tmpl';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'text standard de incepul la intrare spate';
$BL['be_admin_startup_text']            = 'text de inceput';
$BL['be_admin_startup_button']          = 'salveaza text de inceput';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'impresii/comentarii';
$BL['be_cnt_guestbook_listing']         = 'listare';
$BL['be_cnt_guestbook_listing_all']     = 'listeaza&nbsp;toate&nbsp;intrarile';
$BL['be_cnt_guestbook_list']            = 'lista';
$BL['be_cnt_guestbook_perpage']         = 'pe&nbsp;pagina';
$BL['be_cnt_guestbook_form']            = 'formular';
$BL['be_cnt_guestbook_signed']          = 'semnat';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'inainte';
$BL['be_cnt_guestbook_after']           = 'dupa';
$BL['be_cnt_guestbook_entry']           = 'intrare';
$BL['be_cnt_guestbook_edit']            = 'editeaza';
$BL['be_cnt_ecardform_selector']        = 'selector';
$BL['be_cnt_ecardform_radiobutton']     = 'radio buton';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript functionalitate';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'numar articol sus';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'newsletter';
$BL['be_newsletter_addnl']              = 'adauga newsletter';
$BL['be_newsletter_titleeditnl']        = 'editeaza newsletter';
$BL['be_newsletter_newnl']              = 'creaza nou';
$BL['be_newsletter_button_savenl']      = 'salveaza newsletter';
$BL['be_newsletter_fromname']           = 'nume fromular';
$BL['be_newsletter_fromemail']          = 'fromular email';
$BL['be_newsletter_replyto']            = 'raspunde email';
$BL['be_newsletter_changed']            = 'ultima modificare';
$BL['be_newsletter_placeholder']        = 'recipient';
$BL['be_newsletter_htmlpart']           = 'continut newletter HTML';
$BL['be_newsletter_textpart']           = 'continut newletter TEXT';
$BL['be_newsletter_allsubscriptions']   = 'toti abonatii';
$BL['be_newsletter_verifypage']         = 'verifica link';
$BL['be_newsletter_open']               = 'intrare HTML si TEXT';
$BL['be_newsletter_open1']              = '(click pe imagine pt a deschide)';
$BL['be_newsletter_sendnow']            = 'Trimite newsletter';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Atentie!</strong> Trimiterea newsletter-ului la mai multi destinatari e periculos. Destinatarii trebuie verificati in caz contrar va pot acuza de spam. Gandestete de 2-ori inainte de a trimite newsletter. Verifica newsletter-ul prin trimiterea unui test.';
$BL['be_newsletter_attention1']         = 'Daca ai facut modificari in newsletterul de mai sus te rugam sa il salvezi inainte altfel modificarile nu vor fi luate in considerare.';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'trimite newsletter';
$BL['be_newsletter_sendprocess']        = 'proces trimitere';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Atentie!</strong> Va rugam nu opriti procesul de trimitere. In caz contrar e posibil sa trimiti newsletterul de el putin 2-ori la un destinatar. Cand trimiterea esueaza toti distribuitorii neprocesati sunt stocati intr-o sesiune si vor fi folositi la o o noua trimitere.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">the test email address <strong>###TEST###</strong> is NOT valid!<br />&nbsp;<br />Try again please!';
$BL['be_newsletter_to']                 = 'Destinatarii';
$BL['be_newsletter_ready']              = 'trimiterea de newsletter: INCHEIATA';
$BL['be_newsletter_readyfailed']        = 'TRIMITERE ESUATA CATRE';
$BL['be_subnav_msg_subscribers']        = 'Abonati newsletter';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'sitemap';
$BL['be_cnt_sitemap_catimage']          = 'icoana nivel';
$BL['be_cnt_sitemap_articleimage']      = 'icoana articol';
$BL['be_cnt_sitemap_display']           = 'arata';
$BL['be_cnt_sitemap_structuronly']      = 'doar structura nivelului';
$BL['be_cnt_sitemap_structurarticle']   = 'structura nivel + articlole';
$BL['be_cnt_sitemap_catclass']          = 'clasa nivele';
$BL['be_cnt_sitemap_articleclass']      = 'clasa articole';
$BL['be_cnt_sitemap_count']             = 'contor';
$BL['be_cnt_sitemap_classcount']        = 'adauga la nume clasa';
$BL['be_cnt_sitemap_noclasscount']      = 'Nu adauga la nume clasa';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'licitatie';
$BL['be_cnt_bid_bidtext']               = 'text licitatie';
$BL['be_cnt_bid_sendtext']              = 'text trimis';
$BL['be_cnt_bid_verifiedtext']          = 'text verificat';
$BL['be_cnt_bid_errortext']             = 'licitatie stearsa';
$BL['be_cnt_bid_verifyemail']           = 'verifica mail';
$BL['be_cnt_bid_startbid']              = 'incepe licitatie';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'creste&nbsp;cu';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'ext. continut';
$BL['be_cnt_pages_select']              = 'selecteaza fisier';
$BL['be_cnt_pages_fromfile']            = 'fisier din structura';
$BL['be_cnt_pages_manually']            = 'alta cale/fisier sau URL';
$BL['be_cnt_pages_cust']                = 'fisier/URL';
$BL['be_cnt_pages_from']                = 'sursa';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'imagine rollover';
$BL['be_cnt_reference_basis']           = 'aliniament';
$BL['be_cnt_reference_horizontal']      = 'orizontal';
$BL['be_cnt_reference_vertical']        = 'vertical';
$BL['be_cnt_reference_aligntext']       = 'imagini mici de referinta';
$BL['be_cnt_reference_largetext']       = 'imagini mari de referinta';
$BL['be_cnt_reference_zoom']            = 'mareste (lupa)';
$BL['be_cnt_reference_middle']          = 'mijloc';
$BL['be_cnt_reference_border']          = 'bordura';
$BL['be_cnt_reference_block']           = 'bloc L x I (latime x inaltime)';

// added: 31-05-2004
$BL['be_article_rendering']             = 'previzualizare';
$BL['be_article_nosummary']             = 'NU\ afisa sumarul in tot articolul';
$BL['be_article_forlist']               = 'listare articol';
$BL['be_article_forfull']               = 'arata tot articolul';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ATENTIE!</strong> Directorul &quot;SETUP&quot; inca exista! Stergeti acest director - pentru a preveni orice risc care poate compromite site-ul.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'cuvinte interzise';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'seteaza cookie';
$BL['be_cnt_guestbook_allowed']         = 'permite din nou dupa';
$BL['be_cnt_guestbook_seconds']         = 'secunde';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Doresti sa stergi \nToate fisierele din cos?";
$BL['be_ftrash_delallfiles']            = 'Sterge toate fisierele din cos';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'Importa baza CSV cu abonati';
$BL['be_newsletter_importtitle']        = 'Importa Newsletter abonati';
$BL['be_newsletter_entriesfound']       = 'intrari&nbsp;gasite';
$BL['be_newsletter_foundinfile']        = 'in fisier';
$BL['be_newsletter_addresses']          = 'adrese';
$BL['be_newsletter_csverror']           = 'Fisierul CSV importat pare a fi incorect! Verifica separatorii!';
$BL['be_newsletter_importall']          = 'importa toate intrarile';
$BL['be_newsletter_addressesadded']     = 'adresa adaugata.';
$BL['be_newsletter_newimport']          = 'import nou';
$BL['be_newsletter_importerror']        = 'Va rugam verificati fisierul CSV - nici o adresa nu poate fi adaugata!';
$BL['be_newsletter_shouldbe1']          = 'Fisierul CSV ar trebui formatat asa';
$BL['be_newsletter_shouldbe2']          = 'dar poti alege separatori diferiti';
$BL['be_newsletter_sample']             = 'exemplu';
$BL['be_newsletter_selectCSV']          = 'selecteaza fisier CSV';
$BL['be_newsletter_delimeter']          = 'separatori';
$BL['be_newsletter_importCSV']          = 'importa fisier CSV';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'ordonare dupa articolele desemnate';
$BL['be_admin_struct_orderdate']        = 'data de creare';
$BL['be_admin_struct_orderchangedate']  = 'data modificarii';
$BL['be_admin_struct_orderstartdate']   = 'data de incepere';
$BL['be_admin_struct_orderdesc']        = 'descrescator';
$BL['be_admin_struct_orderasc']         = 'crescator';
$BL['be_admin_struct_ordermanual']      = 'manual (sageata sus/jos)';
$BL['be_cnt_sitemap_startid']           = 'incepe la';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'Harta';
$BL['be_save_btn']                      = 'Salveaza';
$BL['be_cmap_location_error_notitle']   = 'completeaza un titlu pt aceasta locatie.';
$BL['be_cnt_map_add']                   = 'adauga locatie';
$BL['be_cnt_map_edit']                  = 'editeaza locatie';
$BL['be_cnt_map_title']                 = 'titlu locatie';
$BL['be_cnt_map_info']                  = 'intrare/info';
$BL['be_cnt_map_list']                  = 'lista locatie';
$BL['be_btn_delete']                    = 'Doresti sa \nstergi aceasta locatie?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'variabile PHP';
$BL['be_cnt_vars']                      = 'variabile';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'copiaza articol';
$BL['be_func_struct_nocopy']            = 'dezactiveaza copiere articol';
$BL['be_func_struct_copy_level']        = 'copiaza nivel structura';
$BL['be_func_struct_no_copy']           = "Nu este posibil sa copiezi nivelul root!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minut';
$BL['be_date_minutes']                  = 'minute';
$BL['be_date_hour']                     = 'ora';
$BL['be_date_hours']                    = 'ore';
$BL['be_date_day']                      = 'zi';
$BL['be_date_days']                     = 'zile';
$BL['be_date_week']                     = 'saptamana';
$BL['be_date_weeks']                    = 'saptamani';
$BL['be_date_month']                    = 'luna';
$BL['be_date_months']                   = 'luni';
$BL['be_off']                           = 'oprit';
$BL['be_on']                            = 'pornit';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'timp expirat';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'utilizatori &amp; grupuri';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'lista forumuri';
$BL['be_forum_title']                   = 'titlu forum';
$BL['be_forum_permission']              = 'permisii';
$BL['be_forum_add']                     = 'adauga forum';
$BL['be_forum_titleedit']               = 'editeaza forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'special';
$BL['be_show_content']                  = 'arata';
$BL['be_main_content']                  = 'coloana principala';
$BL['be_admin_template_jswarning']      = 'Avertizare!!! \nBlocurile speciale se pot modificae! \n\nDaca anulezi \nreseteaza setarile aranjamentului pagini (pagelayout)! \n\nSchimba template?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS feed';
$BL['be_cnt_rssfeed_url']               = 'RSS url';
$BL['be_cnt_rssfeed_item']              = 'obiect';
$BL['be_cnt_rssfeed_max']               = 'max.';
$BL['be_cnt_rssfeed_cut']               = 'ascunde primul obiect';

$BL['be_ctype_simpleform']              = 'email contact form';

$BL['be_cnt_onsuccess']                 = 'la reusita';
$BL['be_cnt_onerror']                   = 'la erroare';
$BL['be_cnt_onsuccess_redirect']        = 'redirectioneaza la reusita';
$BL['be_cnt_onerror_redirect']          = 'redirectioneaza la erroare';

$BL['be_cnt_form_class']                = 'form class';
$BL['be_cnt_label_wrap']                = 'intinde eticheta';
$BL['be_cnt_error_class']               = 'erroare class';
$BL['be_cnt_req_mark']                  = 'obligatoriu cele marcate';
$BL['be_cnt_mark_as_req']               = 'marcate ca obligatoriu';
$BL['be_cnt_mark_as_del']               = 'marcheaza obiect pentru stergere';


$BL['be_cnt_type']                      = 'tip';
$BL['be_cnt_label']                     = 'eticheta';
$BL['be_cnt_needed']                    = 'necesar';
$BL['be_cnt_delete']                    = 'sterge';
$BL['be_cnt_value']                     = 'valoare';
$BL['be_cnt_error_text']                = 'erroare text';
$BL['be_cnt_css_style']                 = 'CSS style';
$BL['be_cnt_send_copy_to']              = 'CC la';

$BL['be_cnt_field']                     = array("text"=>'text (single-line)', "email"=>'email', "textarea"=>'text (multi-line)',
                                                "hidden"=>'hidden', "password"=>'password', "select"=>'select menu',
                                                "list"=>'list menu', "checkbox"=>'checkbox', "radio"=>'radio button',
                                                "upload"=>'file', "submit"=>'send button', "reset"=>'reset button',
                                                "break"=>'break', "breaktext"=>'break text', "special"=>'text (spezial)');

$BL['be_cnt_access']                    = 'acces';
$BL['be_cnt_activated']                 = 'activat';
$BL['be_cnt_available']                 = 'disponibil';
$BL['be_cnt_guests']                    = 'vizitatori';
$BL['be_cnt_admin']                     = 'admin';
$BL['be_cnt_write']                     = 'scrie';
$BL['be_cnt_read']                      = 'citeste';

$BL['be_cnt_no_wysiwyg_editor']         = 'dezactiveaza editor WYSIWYG';
$BL['be_cnt_cache_update']              = 'reseteaza cache';
$BL['be_cnt_cache_delete']              = 'sterge cache';
$BL['be_cnt_cache_delete_msg']          = 'Doresti sa stergi cache-ul?  \nPoate afecta cautarea.  \n';

$BL['be_admin_usr_issection']           = 'sectiune de logare';
$BL['be_admin_usr_ifsection0']          = 'fatada';
$BL['be_admin_usr_ifsection1']          = 'intrare spate';
$BL['be_admin_usr_ifsection2']          = 'fatada si intrare spate';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'editeaza continulul acestui articol';
$BL['be_func_content_paste0']            = 'paste in articol';
$BL['be_func_content_paste']             = 'paste continut articol mai tarziu';
$BL['be_func_content_cut']               = 'taie continutul acestui articol';
$BL['be_func_content_no_cut']            = "Nu este posibil sa tai continutul acestui articol!";
$BL['be_func_content_copy']              = 'copiaza continutul acestui articol';
$BL['be_func_content_no_copy']           = "Nu este posibil sa copiezi continutul acestui articol!";
$BL['be_func_content_paste_cancel']      = 'anuleaza modificarile la continutul articolului';

$BL['be_cnt_move_deleted'] = 'sterge fisiere';
$BL['be_cnt_move_deleted_msg'] = 'Doresti sa muti toate fisierele  \nmarate cu stergere intr-un director special creat?  \n';

$BL['be_admin_struct_permit'] = 'autorizeaza sa acceseze (lasa spatiu gol pt a avea acces toti)';
$BL['be_admin_struct_adduser_all']   = 'preaia controlul asupra tuturor utilizatorilor';
$BL['be_admin_struct_adduser_this']  = 'preia controlul asupra utilizatorului selectat';
$BL['be_admin_struct_remove_all']    = 'sterge toti utilizatorii';
$BL['be_admin_struct_remove_this']   = 'sterge utilizator selectat';


$BL['be_ctype_alias'] = 'alias continut';
$BL['be_cnt_setting'] = 'preia';
$BL['be_cnt_spaces'] = 'spatii ale aliasului de continut';
$BL['be_cnt_toplink'] = 'top link setarea aliasului de continut';
$BL['be_cnt_block'] = 'arata (blocheaza) setarea aliasului de continut';
$BL['be_cnt_title'] = 'titlurile aliasului de continut';

$BL['be_file_replace'] = 'inlocuieste fisiere';

$BL['be_alias_articleID'] = 'alias ID';
$BL['be_alias_useAll'] = "utilizeaza acest articlol&#8217;s date header";
$BL['be_article_morelink'] = '[more&#8230;] link';

