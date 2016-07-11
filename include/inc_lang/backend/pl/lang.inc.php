<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// Language: Polish, Language Code: pl UTF-8 for ver.1.8.3 (2016/04/12, 543)
//Zięba Bogusław http://www.krynica.malopolska.pl
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'


$BL['usr_online']       = 'Zalogowani użytkownicy';

// Login Page
$BL["login_text"]       = 'Podaj swoje dane aby się zalogować';
$BL['login_error']      = 'Błąd podczas logowania!';
$BL["login_username"]   = 'Użytkownik';
$BL["login_userpass"]   = 'Hasło';
$BL["login_button"]     = 'Zaloguj';
$BL["login_lang"]       = 'Wybierz język';

// phpwcms.php
$BL['be_nav_logout']    = 'WYLOGUJ SIĘ';
$BL['be_nav_articles']  = 'ARTYKUŁY';
$BL['be_nav_files']     = 'PLIKI';
$BL['be_nav_modules']   = 'MODUŁY';
$BL['be_nav_messages']  = 'WIADOMOŚCI';
$BL['be_nav_chat']      = 'CHAT';
$BL['be_nav_profile']   = 'PROFILE';
$BL['be_nav_admin']     = 'ADMINISTRACJA';
$BL['be_nav_discuss']   = 'DYSKUSJA';

$BL['be_page_title']    = 'Zaplecze phpwcms (administracja)';

$BL['be_subnav_article_center']         = 'Centrum artykułów';
$BL['be_subnav_article_new']            = 'Nowy artykuł';
$BL['be_subnav_file_center']            = 'Centrum plików';
$BL['be_subnav_file_actions']           = 'Działania plików';
$BL['be_subnav_file_ftptakeover']       = 'Wgrane przez ftp';
$BL['be_subnav_mod_artists']            = 'wykonawca, kategoria, rodzaj';
$BL['be_subnav_msg_center']             = 'Centrum wiadomości';
$BL['be_subnav_msg_new']                = 'Nowa wiadomość';
$BL['be_subnav_msg_newsletter']         = 'Subskrypcja nowości';
$BL['be_subnav_chat_main']              = 'Główna strona chatu';
$BL['be_subnav_chat_internal']          = 'Wewnętrzny chat';
$BL['be_subnav_profile_login']          = 'Informacje logowania';
$BL['be_subnav_profile_personal']       = 'Informacje osobiste';
$BL['be_subnav_admin_pagelayout']       = 'Układ strony';
$BL['be_subnav_admin_templates']        = 'Szablony';
$BL['be_subnav_admin_css']              = 'Domyślny styl css';
$BL['be_subnav_admin_sitestructure']    = 'Struktura witryny';
$BL['be_subnav_admin_users']            = 'Administracja użytkownikami';
$BL['be_subnav_admin_filecat']          = 'Kategorie plików';


// admin.functions.inc.php
$BL['be_func_struct_articleID']      = 'ID artykułu';
$BL['be_func_struct_preview']        = 'Podgląd';
$BL['be_func_struct_edit']           = 'Edytuj artykuł';
$BL['be_func_struct_sedit']          = 'Edytuj poziom struktury';
$BL['be_func_struct_cut']            = 'Wytnij artykuł';
$BL['be_func_struct_nocut']          = 'Zaniechaj wycięcia artykułu';
$BL['be_func_struct_svisible']       = 'Przełącz widoczny/niewidoczny';
$BL['be_func_struct_spublic']        = 'Przełącz publiczny/niepubliczny';
$BL['be_func_struct_sort_up']        = 'Sortuj w górę';
$BL['be_func_struct_sort_down']      = 'Sortuj w dół';
$BL['be_func_struct_del_article']    = 'Usuń artykuł';
$BL['be_func_struct_del_jsmsg']      = 'Czy na pewno chcesz \nusunąć artykuł?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']    = 'Utwórz nowy artykuł na tym poziomie struktury';
$BL['be_func_struct_paste_article']  = 'Wklej artykuł na ten poziom struktury';
$BL['be_func_struct_insert_level']   = 'Wstaw w poziom struktury';
$BL['be_func_struct_paste_level']    = 'Wklej na ten poziom struktury';
$BL['be_func_struct_cut_level']      = 'Wytnij ten poziom struktury';
$BL['be_func_struct_no_cut']         = "Nie można wyciąć głównego poziomu struktury!";
$BL['be_func_struct_no_paste1']      = "Nie można tutaj wkleić!";
$BL['be_func_struct_no_paste2']      = 'Czy potomek jest równorzędny do głównego poziomu drzewa';
$BL['be_func_struct_no_paste3']      = 'to powinno zostać wklejone tutaj';
$BL['be_func_struct_paste_cancel']   = 'anuluj zmianę poziomu struktury';
$BL['be_func_struct_del_struct']     = 'Usuń poziom struktury';
$BL['be_func_struct_del_sjsmsg']     = 'Czy naprawdę chcesz \nusunąć poziom struktury?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']           = 'Otwórz';
$BL['be_func_struct_close']          = 'Zamknij';
$BL['be_func_struct_empty']          = 'Opróżnij';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']          = 'czysty tekst';
$BL['be_ctype_html']               = 'html';
$BL['be_ctype_code']               = 'kod programistyczny';
$BL['be_ctype_textimage']          = 'tekst z grafiką';
$BL['be_ctype_images']             = 'grafika';
$BL['be_ctype_bulletlist']         = 'lista (jako tabela)';
$BL['be_ctype_ullist']            = 'lista';
$BL['be_ctype_link']               = 'odnośnik &amp; e-mail';
$BL['be_ctype_linklist']           = 'lista odnośników';
$BL['be_ctype_linkarticle']        = 'odnośniki do artykułów';
$BL['be_ctype_multimedia']         = 'multimedia';
$BL['be_ctype_filelist']           = 'lista plików';
$BL['be_ctype_emailform']          = 'generator formularza e-mail';
$BL['be_ctype_newsletter']         = 'list z nowością';

// profile.create.inc.php
$BL['be_profile_create_success']   = 'Profil został pomyślne utworzony.';
$BL['be_profile_create_error']     = 'Błąd podczas tworzenia.';

// profile.update.inc.php
$BL['be_profile_update_success']   = 'Dane profilu zostały pomyślnie zaktualizowane.';
$BL['be_profile_update_error']     = 'Błąd podczas aktualizacji.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']     = 'Użytkownik {VAL} jest nieprawidłowy';
$BL['be_profile_account_err2']     = 'Hasło jest za krótkie (tylko {VAL} znaków: musi mieć co najmniej 5 znaków)';
$BL['be_profile_account_err3']     = 'Powtórzenie hasła musi się zgadzać z hasłem';
$BL['be_profile_account_err4']     = 'Adres e-mail {VAL} jest nieprawidłowy';

// profile.data.tmpl.php
$BL['be_profile_data_title']       = 'Twoje dane osobiste';
$BL['be_profile_data_text']        = 'Dane osobiste są opcjonalne. Mogą one pomóc innym użytkownikom lub gościom witryny, dowiedzieć się więcej o Tobie.';
$BL['be_profile_label_title']      = 'Tytuł';
$BL['be_profile_label_firstname']  = 'Imię';
$BL['be_profile_label_name']       = 'Nazwisko';
$BL['be_profile_label_company']    = 'Firma';
$BL['be_profile_label_street']     = 'Ulica';
$BL['be_profile_label_city']       = 'Miejscowość';
$BL['be_profile_label_state']      = 'Województwo';
$BL['be_profile_label_zip']        = 'Kod pocztowy';
$BL['be_profile_label_country']    = 'Kraj';
$BL['be_profile_label_phone']      = 'Telefon';
$BL['be_profile_label_fax']        = 'Faks';
$BL['be_profile_label_cellphone']  = 'Tel.komórkowy';
$BL['be_profile_label_signature']  = 'Podpis';
$BL['be_profile_label_notes']      = 'Notatka';
$BL['be_profile_label_profession'] = 'Zawód';
$BL['be_profile_label_newsletter'] = 'Listy nowości';
$BL['be_profile_text_newsletter']  = 'Zgadzam się na otrzymywanie ogólnych listów nowości z serwisu.';
$BL['be_profile_label_public']     = 'publiczne';
$BL['be_profile_text_public']      = 'Każdy może widzieć moje dane osobiste.';
$BL['be_profile_label_button']     = 'Uaktualnij dane osobiste';

// profile.account.tmpl.php
$BL['be_profile_account_title']    = 'Twoje dane do logowania';
$BL['be_profile_account_text']     = 'Normalnie nie ma potrzeby zmieniać swojej nazwy użytkownika.<br />Za to powinieneś od czasu do czasu zmienić swoje hasło.';
$BL['be_profile_label_err']        = 'Proszę sprawdź';
$BL['be_profile_label_username']   = 'Nazwa użytkownika';
$BL['be_profile_label_newpass']    = 'Nowe hasło';
$BL['be_profile_label_repeatpass'] = 'Powtórz nowe hasło';
$BL['be_profile_label_email']      = 'E-mail';
$BL['be_profile_account_button']   = 'Uaktualnij';
$BL['be_profile_label_lang']       = 'Język';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']        = 'Pliki wgrane przez ftp';
$BL['be_ftptakeover_mark']         = 'Zaznacz';
$BL['be_ftptakeover_available']    = 'Dostępne pliki';
$BL['be_ftptakeover_size']         = 'Rozmiar';
$BL['be_ftptakeover_nofile']       = 'W tej chwili nie ma dostępnych plików &#8211; musisz wgrać jakiś przez ftp';
$BL['be_ftptakeover_all']          = 'WSZYSTKIE';
$BL['be_ftptakeover_directory']    = 'Katalog';
$BL['be_ftptakeover_rootdir']      = 'Główny katalog';
$BL['be_ftptakeover_needed']       = 'wymagane!!! (musisz wybrać przynajmniej jeden)';
$BL['be_ftptakeover_optional']     = 'opcjonalne';
$BL['be_ftptakeover_keywords']     = 'słowa kluczowe';
$BL['be_ftptakeover_additional']   = 'dodatkowe';
$BL['be_ftptakeover_longinfo']     = 'długie info';
$BL['be_ftptakeover_status']       = 'status';
$BL['be_ftptakeover_active']       = 'aktywny';
$BL['be_ftptakeover_public']       = 'publiczne';
$BL['be_ftptakeover_createthumb']  = 'Utwórz miniaturkę';
$BL['be_ftptakeover_button']       = 'Odbierz wybrane pliki';

// files.reiter.tmpl.php
$BL['be_ftab_title']             = 'Centrum plików';
$BL['be_ftab_createnew']         = 'Utwórz nowy katalog w głównym';
$BL['be_ftab_paste']             = 'Wklej pliki ze schowka do głównego katalogu';
$BL['be_ftab_disablethumb']      = 'Wyłącz miniaturki w liście plików';
$BL['be_ftab_enablethumb']       = 'Włącz miniaturki w liście plików';
$BL['be_ftab_private']           = 'Prywatne&nbsp;pliki';
$BL['be_ftab_public']            = 'Publiczne&nbsp;pliki';
$BL['be_ftab_search']            = 'Szukaj';
$BL['be_ftab_trash']             = 'Kosz';
$BL['be_ftab_open']              = 'Otwórz wszystkie katalogi';
$BL['be_ftab_close']             = 'Zamknij wszystkie katalogi';
$BL['be_ftab_upload']            = 'Wgraj pliki do głównego katalogu';
$BL['be_ftab_filehelp']          = 'Otwórz plik pomocy';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']          = 'Główny katalog';
$BL['be_fpriv_title']            = 'Utwórz nowy katalog';
$BL['be_fpriv_inside']           = 'Wewnątrz';
$BL['be_fpriv_error']            = 'Błąd: wypełnij pole nazwa dla katalogu';
$BL['be_fpriv_errordir']         = 'Błąd: katalog nie może być sam w sobie podfolderem';
$BL['be_fpriv_name']             = 'Nazwa';
$BL['be_fpriv_status']           = 'Status';
$BL['be_fpriv_button']           = 'Utwórz nowy katalog';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']        = 'Edycja katalogu';
$BL['be_fpriv_newname']          = 'Nowa nazwa';
$BL['be_fpriv_updatebutton']     = 'Uaktualnij dane katalogu';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']          = 'Wybierz pliki które chcesz wgrać';
$BL['be_fprivup_err2']          = 'Rozmiar plików do wgrania jest większy niż';
$BL['be_fprivup_err3']          = 'Błąd w trakcie wgrywania plików';
$BL['be_fprivup_err4']          = 'Błąd podczas tworzenia ktalogu użytkownika.';
$BL['be_fprivup_err5']          = 'miniaturki nie istnieją';
$BL['be_fprivup_err6']          = 'Proszę nie próbować ponownie - To jest błąd serwera! Skontakuj się ze swoim <a href="mailto:{VAL}">administratorem</a> tak szybko jak to możliwe!';
$BL['be_fprivup_err7']          = 'Ze względów bezpieczeństwa plik %s nie może być przeesłane.';
$BL['be_fprivup_err8']          = 'Plik bez rozszerzenia %s nie jest dozwolony do przesłania. Dopuszczalne rozszerzenia to: %s.';
$BL['be_fprivup_err9']          = 'Plik bez rozszerzenia nie jest dozwolony do przesłania. Dopuszczalne rozszerzenia to: %s.';
$BL['be_fprivup_title']         = 'wgrywanie pliki';
$BL['be_fprivup_button']        = 'wgraj pliki';
$BL['be_fprivup_upload']        = 'wgraj';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']       = 'edycja informacji o pliku';
$BL['be_fprivedit_filename']    = 'nazwa pliku';
$BL['be_fprivedit_created']     = 'utworzony';
$BL['be_fprivedit_dateformat']  = 'Y-m-d H:i';
$BL['be_fprivedit_err1']        = 'skoryguj nazwę pliku (ustaw z powrotem oryginalną)';
$BL['be_fprivedit_clockwise']   = 'obróć miniaturę zgodnie z ruchem zegara [oryginalny plik +90&deg;]';
$BL['be_fprivedit_cclockwise']  = 'obróć miniaturę nie zgodnie z ruchem zegara [oryginalny plik -90&deg;]';
$BL['be_fprivedit_button']      = 'uaktualnij informację o pliku';
$BL['be_fprivedit_size']        = 'rozmiar';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']        = 'wgraj pliki do katalogu';
$BL['be_fprivfunc_makenew']       = 'utwórz nowy katalog wewnątrz';
$BL['be_fprivfunc_paste']         = 'wklej plik ze schowka do katalogu';
$BL['be_fprivfunc_edit']          = 'edytuj katalog';
$BL['be_fprivfunc_cactive']       = 'przełącz aktywny/nieaktywny';
$BL['be_fprivfunc_cpublic']       = 'przełącz publiczny/niepubliczny';
$BL['be_fprivfunc_deldir']        = 'usuń katalog';
$BL['be_fprivfunc_jsdeldir']      = 'Czy na pewno chcesz \nusunąć katalog';
$BL['be_fprivfunc_notempty']      = 'katalog {VAL} nie jest pusty!';
$BL['be_fprivfunc_opendir']       = 'otwórz katalog';
$BL['be_fprivfunc_closedir']      = 'zamknij katalog';
$BL['be_fprivfunc_dlfile']        = 'ściągnij plik';
$BL['be_fprivfunc_clipfile']      = 'plik w schowku';
$BL['be_fprivfunc_cutfile']       = 'wytnij';
$BL['be_fprivfunc_editfile']      = 'edytuj informację o pliku';
$BL['be_fprivfunc_cactivefile']   = 'przełącz aktywny/nieaktywny';
$BL['be_fprivfunc_cpublicfile']   = 'przełącz publiczny/niepubliczny';
$BL['be_fprivfunc_movetrash']     = 'przesuń do kosza';
$BL['be_fprivfunc_jsmovetrash1']  = 'Czy napewno chcesz ten plik';
$BL['be_fprivfunc_jsmovetrash2']  = 'przesunąć do kosza?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']      = 'brak prywatnych plików lub folderów';

// files.public.list.tmpl.php
$BL['be_fpublic_user']            = 'użytkownik';
$BL['be_fpublic_nofiles']         = 'brak publicznych plików lub katalogów';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']          = 'kosz jest pusty';
$BL['be_ftrash_show']             = 'pokaż prywatne pliki';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']   = 'Czy chcesz przywrócić {VAL} \ni przenieść do prywatnej listy?';
$BL['be_ftrash_delete']    = 'Czy chcesz usunąć {VAL}?';
$BL['be_ftrash_undo']      = 'przywróć (odwróć usuwanie)';
$BL['be_ftrash_delfinal']  = 'ostateczne usunięcie';

// files.search.tmpl.php
$BL['be_fsearch_err1']          = 'brak ciągu znaków do wyszukiwania.';
$BL['be_fsearch_title']         = 'szukaj plików';
$BL['be_fsearch_infotext']      = 'To jest prosta wyszukiwarka informacji o plikach. Przeszukuje ona słowa kluczowe,<br />nazwy i długie info o plikach.Nie wspiera znaków specjalnych. <br />Wybierz I/LUB oraz typy plików: prywatne/publiczne.';
$BL['be_fsearch_nonfound']      = 'nie znaleziono plików dla twojego zapytania. Zmień swoje zapytanie!';
$BL['be_fsearch_fillin']        = 'proszę wypełnij pole wyszukiwarki ciągiem znaków do wyszukania.';
$BL['be_fsearch_searchlabel']   = 'szukaj ';
$BL['be_fsearch_startsearch']   = 'rozpocznij wyszukiwanie';
$BL['be_fsearch_and']           = 'I';
$BL['be_fsearch_or']            = 'LUB';
$BL['be_fsearch_all']           = 'wszystkie pliki';
$BL['be_fsearch_personal']      = 'prywatne';
$BL['be_fsearch_public']        = 'publiczne';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']        = 'wewnętrzny chat';
$BL['be_chat_info']         = 'Tutaj możesz porozumieć się z innymi użytkownikami swojego systemu phpwcms. To medium jest przeznaczone głównie do porozumiewania się w czasie rzeczywistym, ale możesz również zostawiać poprzez niego wiadomości dla innych.';
$BL['be_chat_start']        = 'kliknij tutaj aby uruchomić chat';
$BL['be_chat_lines']        = 'linie chata';

// message.center.tmpl.php
$BL['be_msg_title']         = 'centrum wiadomości';
$BL['be_msg_new']           = 'nowa';
$BL['be_msg_old']           = 'stara';
$BL['be_msg_senttop']       = 'wysłana';
$BL['be_msg_del']           = 'usunięta';
$BL['be_msg_from']          = 'od';
$BL['be_msg_subject']       = 'temat';
$BL['be_msg_date']          = 'data/czas';
$BL['be_msg_close']         = 'zamknij wiadomość';
$BL['be_msg_create']        = 'utwórz nową wiadomość';
$BL['be_msg_reply']         = 'odpowiedz na wiadomość';
$BL['be_msg_move']          = 'przesuń tę wiadomość do kosza';
$BL['be_msg_unread']        = 'nieprzeczytana lub nowa wiadomość';
$BL['be_msg_lastread']      = 'ostatnie {VAL} przeczytanych wiadomości';
$BL['be_msg_lastsent']      = 'ostatnie {VAL} wysłanych wiadomości';
$BL['be_msg_marked']        = 'wiadomości oznaczone do usunięcia (kosz)';
$BL['be_msg_nomsg']         = 'brak wiadomości w katalogu';

// message.send.tmpl.php
$BL['be_msg_RE']            = 'ODP';
$BL['be_msg_by']            = 'wysłana przez';
$BL['be_msg_on']            = 'w dniu';
$BL['be_msg_msg']           = 'wiadomość';
$BL['be_msg_err1']          = 'zapomniałeś udtsawić odbiorcę...';
$BL['be_msg_err2']          = 'wypełnij pole tytułu (odbiorcy będzie łatwiej czytać Twoją wiadomość)';
$BL['be_msg_err3']          = 'nie ma sensu wysyłać wiadomości bez treści ;-)';
$BL['be_msg_sent']          = 'nowa wiadomość została wysłana!';
$BL['be_msg_fwd']           = 'zostaniesz przekierowany do centrum wiadomości lub';
$BL['be_msg_newmsgtitle']   = 'napisz nową wiadomość';
$BL['be_msg_err']           = 'błąd podczas wysyłania wiadomości';
$BL['be_msg_sendto']        = 'wyślij wiadomość do';
$BL['be_msg_available']     = 'lista dostęnych odbiorców';
$BL['be_msg_all']           = 'wyślij widomość do wszystkich wybranych odbiorców';

// message.subscription.tmpl.php
$BL['be_newsletter_title']         = 'Subskrypcja wiadomości o nowościach';
$BL['be_newsletter_titleedit']     = 'Edytuj subskrypcję';
$BL['be_newsletter_new']           = 'Utwórz nowy/ą';
$BL['be_newsletter_add']           = 'Dodaj&nbsp;wiadomość&nbsp;do&nbsp;subskrypcji';
$BL['be_newsletter_name']          = 'Nazwa';
$BL['be_newsletter_info']          = 'Info';
$BL['be_newsletter_button_save']   = 'Zapisz subskrypcję';
$BL['be_newsletter_button_cancel'] = 'Anuluj';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']           = 'Nazwa użytkownika jest błędna, wybierz inną';
$BL['be_admin_usr_err2']           = 'Nazwa użytkownika nie może być pusta';
$BL['be_admin_usr_err3']           = 'Hasło użytkownika nie może być puste';
$BL['be_admin_usr_err4']           = "Adres email jest nieprawidłowy";
$BL['be_admin_usr_err']            = 'Błąd';
$BL['be_admin_usr_mailsubject']    = 'Witajcie w systemie zarządzania phpwcms';
$BL['be_admin_usr_mailbody']       = "WITAJ W SYSTEMIE ZARZĄDZANIA PHPWCMS\n\n    użytkownik: {LOGIN}\n    hasło: {PASSWORD}\n\n\nPrzez tą stronę możesz się zalogować: {SITE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']          = 'Dodaj nowe konto użytkownika';
$BL['be_admin_usr_realname']       = 'Prawdziwe imię';
$BL['be_admin_usr_setactive']      = 'Ustaw konto jako aktywne';
$BL['be_admin_usr_iflogin']        = 'jeśli włączone, użytkownik może się logować';
$BL['be_admin_usr_isadmin']        = 'Użytkownik jest administratorem';
$BL['be_admin_usr_ifadmin']        = 'jeśli włączone, użytkownik ma prawa administratora';
$BL['be_admin_usr_verify']         = 'Weryfikacja';
$BL['be_admin_usr_sendemail']      = 'wyślij email do nowego użytkownika z informacją o jego koncie';
$BL['be_admin_usr_button']         = 'Zapisz dane użytkownika';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']        = 'Edycja konta użytkownika';
$BL['be_admin_usr_emailsubject']  = 'Dane konta - phpwcms - zostały zmienione';
$BL['be_admin_usr_emailbody']     = "KONTO UŻYTKOWNIKA W PHPWCMS ZOSTAŁO ZMIENIONE \n\n    Nazwa użytkownika: {LOGIN}\n    Hasło: {PASSWORD}\n\n\nPrzez tą stronę możesz się zalogować:: {SITE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']  = '[NIE ZMIENIONO - PODAJ PRAWIDŁOW HASŁO]';
$BL['be_admin_usr_ebutton']       = 'Uaktualnij dane użytkownika';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']        = 'Lista użytkowników systemu';
$BL['be_admin_usr_ldel']          = 'UWAGA!&#13;Wybrana akcja spowoduje usunięcie użytkownika!';
$BL['be_admin_usr_create']        = 'Utwórz nowego użytkownika';
$BL['be_admin_usr_editusr']       = 'Edytuj użytkownika';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']     = 'Struktura witryny';
$BL['be_admin_struct_child']     = '(potomek)';
$BL['be_admin_struct_index']     = 'index (początek witryny)';
$BL['be_admin_struct_cat']       = 'Tytuł poziomu';
$BL['be_admin_struct_hide1']     = 'Ukryj';
$BL['be_admin_struct_hide2']     = 'ten&nbsp;poziom&nbsp;w&nbsp;menu';
$BL['be_admin_struct_info']      = 'Informacja o poziomie';
$BL['be_admin_struct_template']  = 'Szablon';
$BL['be_admin_struct_alias']     = 'Alias poziomu';
$BL['be_admin_struct_visible']   = 'Widoczny';
$BL['be_admin_struct_button']    = 'Zapisz dane poziomu';
$BL['be_admin_struct_close']     = 'Zamknij';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']       = 'Kategorie plików';
$BL['be_admin_fcat_err']         = 'Nazwa kategorii jest pusta!';
$BL['be_admin_fcat_name']        = 'Nazwa kategorii';
$BL['be_admin_fcat_needed']      = 'należy używać';
$BL['be_admin_fcat_button1']     = 'Uaktualnij';
$BL['be_admin_fcat_button2']     = 'Utwórz';
$BL['be_admin_fcat_delmsg']      = 'Czy na pewno chcesz\nskasować rozszerzenie plików?';
$BL['be_admin_fcat_fcat']        = 'Kategoria plików';
$BL['be_admin_fcat_err1']        = 'Nazwa rozszerzenia jest pusta!';
$BL['be_admin_fcat_fkeyname']    = 'Nazwa&nbsp;rozszerzenia';
$BL['be_admin_fcat_exit']        = 'Anuluj';
$BL['be_admin_fcat_addkey']      = 'Dodaj nowe rozszerzenie';
$BL['be_admin_fcat_editcat']     = 'Edytuj kategorię';
$BL['be_admin_fcat_delcatmsg']   = 'Czy na pewno chcesz\nusunąć kategorię plików?';
$BL['be_admin_fcat_delcat']      = 'Usuń kategorię plików';
$BL['be_admin_fcat_delkey']      = 'Usuń rozszerzenie plików';
$BL['be_admin_fcat_editkey']     = 'Edytuj rozszerzenie';
$BL['be_admin_fcat_addcat']      = 'Utwórz nową kategorię plików';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']        = 'Ustawienia witryny: układ witryny';
$BL['be_admin_page_align']        = 'Wyrównanie witryny';
$BL['be_admin_page_align_left']   = 'standardowe wyrównanie do lewej całej witryny';
$BL['be_admin_page_align_center'] = 'wyśrodkowanie całej witrynę';
$BL['be_admin_page_align_right']  = 'wyrównanie do prawej całej witryny';
$BL['be_admin_page_margin']       = 'Margines';
$BL['be_admin_page_top']          = 'góra';
$BL['be_admin_page_bottom']       = 'dół';
$BL['be_admin_page_left']         = 'Lewy';
$BL['be_admin_page_right']        = 'Prawy';
$BL['be_admin_page_bg']           = 'Tło';
$BL['be_admin_page_color']        = 'kolor';
$BL['be_admin_page_height']       = 'wysokość&nbsp;';
$BL['be_admin_page_width']        = 'szerokość';
$BL['be_admin_page_main']         = 'Główny';
$BL['be_admin_page_leftspace']    = 'Lewy odstęp';
$BL['be_admin_page_rightspace']   = 'Prawy odstęp';
$BL['be_admin_page_class']        = 'klasa';
$BL['be_admin_page_image']        = 'obraz';
$BL['be_admin_page_text']         = 'tekst';
$BL['be_admin_page_link']         = 'odnośn.';
$BL['be_admin_page_js']           = 'Javascript';
$BL['be_admin_page_visited']      = 'odwiedz.';
$BL['be_admin_page_pagetitle']    = 'Tytuł&nbsp;witryny';
$BL['be_admin_page_addtotitle']   = 'Dodaj&nbsp;do&nbsp;tytułu';
$BL['be_admin_page_category']     = 'nazwę poziomu';
$BL['be_admin_page_articlename']  = 'tytuł&nbsp;artykułu';
$BL['be_admin_page_blocks']       = 'Bloki';
$BL['be_admin_page_allblocks']    = 'Wszystkie bloki';
$BL['be_admin_page_col1']         = 'bloki w 3 kolumnach';
$BL['be_admin_page_col2']         = 'bloki w 2 kolumnach (główna kolumna z prawej, menu z lewej)';
$BL['be_admin_page_col3']         = 'bloki w 2 kolumnach (główna kolumna z lewej, menu z prawej)';
$BL['be_admin_page_col4']         = 'bloki w 1 kolumnie';
$BL['be_admin_page_header']       = 'Nagłówek';
$BL['be_admin_page_footer']       = 'Stopka';
$BL['be_admin_page_topspace']     = 'Górny&nbsp;odstęp';
$BL['be_admin_page_bottomspace']  = 'Dolny&nbsp;odstęp';
$BL['be_admin_page_button']       = 'Zapisz układ strony';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']         = 'Ustawienie witryny: styl css';
$BL['be_admin_css_css']           = 'CSS';
$BL['be_admin_css_button']        = 'Zapisz styl css';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']        = 'Ustawienia witryny: szablony';
$BL['be_admin_tmpl_default']      = 'domyślny';
$BL['be_admin_tmpl_add']          = 'Dodaj&nbsp;szablon';
$BL['be_admin_tmpl_edit']         = 'Edycja szablonu';
$BL['be_admin_tmpl_new']          = 'Utwórz nowy';
$BL['be_admin_tmpl_css']          = 'Plik css';
$BL['be_admin_tmpl_head']         = 'Nagłówek&nbsp; html';
$BL['be_admin_tmpl_js']           = 'Skrypt przy&nbsp; otwarciu';
$BL['be_admin_tmpl_error']        = 'Błąd';
$BL['be_admin_tmpl_button']       = 'Zapisz szablon';
$BL['be_admin_tmpl_name']         = 'Nazwa';

// article.structlist.tmpl.php
$BL['be_article_title']           = 'Struktura witryny i lista artykułów';

// article.new.tmpl.php
$BL['be_article_err1']            = 'Tytuł tego artykułu jest pusty';
$BL['be_article_err2']            = 'Data rozpoczęcia wyświetlania jest źle podana - ustaw na dziś';
$BL['be_article_err3']            = 'Data zkończenia wyświetlania jest źle podana - ustaw na dziś';
$BL['be_article_title1']          = 'Podstawowe informacje artykułu';
$BL['be_article_cat']             = 'Poziom';
$BL['be_article_atitle']          = 'Tytuł artykułu';
$BL['be_article_asubtitle']       = 'Podtytuł';
$BL['be_article_abegin']          = 'Rozp.';
$BL['be_article_aend']            = 'Zakoń.';
$BL['be_article_aredirect']       = 'Przekieruj do';
$BL['be_article_akeywords']       = 'Słowa <br/>kluczowe';
$BL['be_article_asummary']        = 'Podsumowanie';
$BL['be_article_abutton']         = 'Utwórz nowy artykuł';

// article.editcontent.inc.php
$BL['be_article_err4']            = 'Data zakończenia wyświetlania jest podana. Ale-ustaw na tydzień od dziś';

// article.editsummary.tmpl.php
$BL['be_article_estitle']         = 'Edycja podstawowych danych artykułu';
$BL['be_article_eslastedit']      = 'Aktualizowany';
$BL['be_article_esnoupdate']      = 'Formularz nie zaktualizowany';
$BL['be_article_esbutton']        = 'Zaktualizuj dane artykułu';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']       = 'Treść artykułu';
$BL['be_article_cnt_type']        = 'Typ treści';
$BL['be_article_cnt_space']       = 'odstęp';
$BL['be_article_cnt_before']      = 'przed';
$BL['be_article_cnt_after']       = 'po';
$BL['be_article_cnt_top']         = 'na górze';
$BL['be_article_cnt_toplink']     = 'wyświetl odnośnik : na górę';
$BL['be_article_cnt_anchor']      = 'kotwica';
$BL['be_article_cnt_ctitle']      = 'tytuł treści';
$BL['be_article_cnt_back']        = 'pełne dane artykułu';
$BL['be_article_cnt_button1']     = 'Zaktualizuj';
$BL['be_article_cnt_button2']     = 'Utwórz';
$BL['be_article_cnt_button3']     = 'Zapisz i zamknij';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']      = 'Dane artykułu';
$BL['be_article_cnt_ledit']       = 'Edytuj artykuł';
$BL['be_article_cnt_lvisible']    = 'Przełącz widoczny/niewidoczny';
$BL['be_article_cnt_ldel']        = 'Usuń artykuł';
$BL['be_article_cnt_ldeljs']      = 'Czy na pewno usunąć artykuł?';
$BL['be_article_cnt_redirect']    = 'Przekierowanie';
$BL['be_article_cnt_edited']      = 'Edytowane przez';
$BL['be_article_cnt_start']       = 'Data rozp.';
$BL['be_article_cnt_end']         = 'Data zakoń.';
$BL['be_article_cnt_add']         = 'Dodaj';
$BL['be_article_cnt_addtitle']    = 'Dodaj nową treść';
$BL['be_article_cnt_up']          = 'Przesuń w górę treść';
$BL['be_article_cnt_down']        = 'Przesuń w dół treść';
$BL['be_article_cnt_edit']        = 'Edytuj treść';
$BL['be_article_cnt_delpart']     = 'Usuń treść z artykułu';
$BL['be_article_cnt_delpartjs']   = 'Czy na pewno usunąć treść z artykułu?';
$BL['be_article_cnt_center']      = 'Centrum artykułów';

// content forms
$BL['be_cnt_plaintext']          = 'czysty tekst';
$BL['be_cnt_htmltext']           = 'tekst w formacie html';
$BL['be_cnt_image']              = 'obraz';
$BL['be_cnt_position']           = 'pozycja';
$BL['be_cnt_pos0']               = 'Ponad, z lewej';
$BL['be_cnt_pos1']               = 'Ponad, na środku';
$BL['be_cnt_pos2']               = 'Ponad, z prawej';
$BL['be_cnt_pos3']               = 'Pod, z lewej';
$BL['be_cnt_pos4']               = 'Pod, na środku';
$BL['be_cnt_pos5']               = 'Pod, z prawej';
$BL['be_cnt_pos6']               = 'W tekście, po lewej';
$BL['be_cnt_pos7']               = 'W tekście, po prawej';
$BL['be_cnt_pos0i']              = 'wyrównaj obraz ponad i z lewej strony tesktu';
$BL['be_cnt_pos1i']              = 'wyrównaj obraz ponad i pośrodku tesktu';
$BL['be_cnt_pos2i']              = 'wyrównaj obraz ponad i z prawej strony tekstu';
$BL['be_cnt_pos3i']              = 'wyrównaj obraz pod i z lewej strony tekstu';
$BL['be_cnt_pos4i']              = 'wyrównaj obraz pod i pośrodku tekstu';
$BL['be_cnt_pos5i']              = 'wyrównaj obraz pod i z prawej strony tekstu';
$BL['be_cnt_pos6i']              = 'umieść obraz wewnątrz tekstu i wyrównaj do lewej';
$BL['be_cnt_pos7i']              = 'umieść obraz wewnątrz tesktu i wyrównaj do prawej';
$BL['be_cnt_maxw']               = 'maks.&nbsp;szer.';
$BL['be_cnt_maxh']               = 'maks.&nbsp;wys.';
$BL['be_cnt_enlarge']            = 'włącz&nbsp;powiększanie';
$BL['be_cnt_caption']            = 'podpis';
$BL['be_cnt_subject']            = 'tytuł&nbsp;<br/>wiadomości';
$BL['be_cnt_recipient']          = 'odbiorca';
$BL['be_cnt_buttontext']         = 'tekst przycisku';
$BL['be_cnt_sendas']             = 'wyślij jako';
$BL['be_cnt_text']               = 'tekst';
$BL['be_cnt_html']               = 'html';
$BL['be_cnt_formfields']         = 'pola&nbsp;<br/> formularza';
$BL['be_cnt_code']               = 'kod programistyczny';
$BL['be_cnt_infotext']           = 'tekst&nbsp;informacji';
$BL['be_cnt_subscription']       = 'subskrypcja';
$BL['be_cnt_labelemail']         = 'etykieta&nbsp;email';
$BL['be_cnt_tablealign']         = 'wyrównanie&nbsp;tabeli';
$BL['be_cnt_labelname']          = 'nazwa&nbsp;etykiety';
$BL['be_cnt_labelsubsc']         = 'etykieta&nbspsubskr.;';
$BL['be_cnt_allsubsc']           = 'wszyscy&nbsp;subskr.';
$BL['be_cnt_default']            = 'domyślny';
$BL['be_cnt_left']               = 'lewo';
$BL['be_cnt_center']             = 'środek';
$BL['be_cnt_right']              = 'prawo';
$BL['be_cnt_buttontext']         = 'tekst&nbsp;przycisku';
$BL['be_cnt_successtext']        = 'tekst&nbsp;sukcesu';
$BL['be_cnt_regmail']            = 'zarejestr.email';
$BL['be_cnt_logoffmail']         = 'wyłącz.email';
$BL['be_cnt_changemail']         = 'zmień.email';
$BL['be_cnt_openimagebrowser']   = 'otwórz przeglądarkę grafiki';
$BL['be_cnt_openfilebrowser']    = 'otwórz przeglądarkę plików';
$BL['be_cnt_sortup']             = 'do góry';
$BL['be_cnt_sortdown']           = 'do dołu';
$BL['be_cnt_delimage']           = 'usuń wybrane grafiki';
$BL['be_cnt_delfile']            = 'usuń wybrane pliki';
$BL['be_cnt_delmedia']           = 'usuń wybrane multimedia';
$BL['be_cnt_column']             = 'kolumna(y)';
$BL['be_cnt_imagespace']         = 'odstęp&nbsp;obrazka';
$BL['be_cnt_directlink']         = 'bezpośredni odnośnik';
$BL['be_cnt_target']             = 'cel';
$BL['be_cnt_target1']            = 'w nowym oknie';
$BL['be_cnt_target2']            = 'w główenj ramce okna';
$BL['be_cnt_target3']            = 'w tym samym oknie';
$BL['be_cnt_target4']            = 'w tej samej ramce lub oknie';
$BL['be_cnt_bullet']             = 'lista (jako tablica)';
$BL['be_cnt_ullist']             = 'lista';
$BL['be_cnt_ullist_desc']        = '~ = 1szy poziom, &nbsp; ~~ = 2gi poziom, &nbsp; itd.';
$BL['be_cnt_linklist']           = 'lista odnośników';
$BL['be_cnt_plainhtml']          = 'czysty html';
$BL['be_cnt_files']              = 'pliki';
$BL['be_cnt_description']        = 'opis';
$BL['be_cnt_linkarticle']        = 'odnośniki do&nbsp;<br/>artykułów';
$BL['be_cnt_articles']           = 'artykuły';
$BL['be_cnt_movearticleto']      = 'przesuń wybrane artykuły do listy artykułów';
$BL['be_cnt_removearticleto']    = 'usuń wybrane artykuły z listy artykułów';
$BL['be_cnt_mediatype']          = 'typ medium';
$BL['be_cnt_control']            = 'kontrola';
$BL['be_cnt_showcontrol']        = 'pokaż pasek kontroli';
$BL['be_cnt_autoplay']           = 'autoodtwarzanie';
$BL['be_cnt_source']             = 'źródło';
$BL['be_cnt_internal']           = 'wewnętrzne';
$BL['be_cnt_openmediabrowser']   = 'otwórz przeglądarkę multimediów';
$BL['be_cnt_external']           = 'zewnętrzne';
$BL['be_cnt_mediapos0']          = 'po lewej (domyślnie)';
$BL['be_cnt_mediapos1']          = 'na środku';
$BL['be_cnt_mediapos2']          = 'poprawej';
$BL['be_cnt_mediapos3']          = 'w tekście, po lewej';
$BL['be_cnt_mediapos4']          = 'w tekście, po prawej';
$BL['be_cnt_mediapos0i']         = 'wyrównaj media ponad i do lewej strony tekstu';
$BL['be_cnt_mediapos1i']         = 'wyrównaj media ponad i pośrodku tesktu';
$BL['be_cnt_mediapos2i']         = 'wyrównaj media ponad i do prawej strony tekstu';
$BL['be_cnt_mediapos3i']         = 'umieść media wewnątrz tesktu i wyrównaj do lewej';
$BL['be_cnt_mediapos4i']         = 'umieść media wewnątrz tesktu i wyrównaj do prawej';
$BL['be_cnt_setsize']            = 'ustaw rozmiar';
$BL['be_cnt_set1']               = 'ustaw rozmiar na 160x120px';
$BL['be_cnt_set2']               = 'ustaw rozmiar na 240x180px';
$BL['be_cnt_set3']               = 'ustaw rozmiar na 320x240px';
$BL['be_cnt_set4']               = 'ustaw rozmiar na 480x360px';
$BL['be_cnt_set5']               = 'wyczyść wysokość i szerokość';

// added: 28-12-2003
$BL['be_admin_page_add']         = 'Utwórz nowy układ strony';
$BL['be_admin_page_name']        = 'Nazwa układu';
$BL['be_admin_page_edit']        = 'Edytuj układ';
$BL['be_admin_page_render']      = 'Renderowanie';
$BL['be_admin_page_table']       = 'tabela';
$BL['be_admin_page_div']         = 'css div';
$BL['be_admin_page_custom']      = 'własne';
$BL['be_admin_page_custominfo']  = 'z szablonu głównego bloku';
$BL['be_admin_tmpl_layout']      = 'układ strony';
$BL['be_admin_tmpl_nolayout']    = 'Brak układu!';

// added: 31-12-2003
$BL['be_ctype_search']           = 'wyszukiwarka';
$BL['be_cnt_results']            = 'rezultatów';
$BL['be_cnt_results_per_page']   = 'na&nbsp;stronę (jeśli puste, pokazuje wszystkie)';
$BL['be_cnt_opennewwin']         = 'w nowym oknie';
$BL['be_cnt_searchlabeltext']    = 'Wstaw swoje komunikaty, które pokazują się gdy jest więcej znalezionych artykułów niż mieści jedna strona.';
$BL['be_cnt_input']              = 'wybierz';
$BL['be_cnt_style']              = 'styl';
$BL['be_cnt_result']             = 'rezultat';
$BL['be_cnt_next']               = 'następny';
$BL['be_cnt_previous']           = 'poprzedni';
$BL['be_cnt_align']              = 'wyrównanie';
$BL['be_cnt_searchformtext']     = 'Wstaw swoje komunikaty gdy użytkownik otworzy strone wyszukiwarki lub gdy nie ma rezultatów.';
$BL['be_cnt_intro']              = 'nagłówek';
$BL['be_cnt_noresult']           = 'brak&nbsp; rezultatów';
$BL['be_cnt_search_default_type']       = 'domyślny typ wyszukiwania';

// added: 02-01-2004
$BL['be_admin_page_disable']    = 'wyłączone';

// added: 09-01-2004
$BL['be_article_articleowner']  = 'Właściciel artykułu';
$BL['be_article_adminuser']     = 'Administrator';
$BL['be_article_username']      = 'Autor';

// added: 10-01-2004
$BL['be_ctype_wysywig']         = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']  = 'widoczne tylko dla zalogowanych użytkowników';
$BL['be_admin_struct_status']   = 'status dostępności w menu';

// added: 15-02-2004
$BL['be_ctype_articlemenu']    = 'menu artykułów';
$BL['be_cnt_sitelevel']        = 'poziom struktury';
$BL['be_cnt_sitecurrent']    = 'obecny poziom struktury';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']    = 'Tekst na stronie startowej';
$BL['be_ctype_ecard']               = 'kartka elektroniczna';
$BL['be_ctype_blog']                = 'blog';
$BL['be_cnt_ecardtext']             = 'Tytuł/kartka elektroniczna';
$BL['be_cnt_ecardtmpl']             = 'Szablon wiadomości';
$BL['be_cnt_ecard_image']           = 'Obrazek kartki';
$BL['be_cnt_ecard_title']           = 'Tytuł kartki';
$BL['be_cnt_alignment']             = 'Wyrównanie';
$BL['be_cnt_ecardform']             = 'Szablon formularza';
$BL['be_cnt_ecardform_err']         = 'Wszystkie pola oznaczone * są wymagalne';
$BL['be_cnt_ecardform_sender']      = 'Nadawca';
$BL['be_cnt_ecardform_recipient']   = 'Obiorca';
$BL['be_cnt_ecardform_name']        = 'Nazwa';
$BL['be_cnt_ecardform_msgtext']     = 'Twoja wiadomość do odbiorcy';
$BL['be_cnt_ecardform_button']      = 'Wyślij kartkę';
$BL['be_cnt_ecardsend']             = 'Szablon wysyłki';

// added: 28-03-2004
$BL['be_admin_startup_title']       = 'Domyślny tekst na stronie startowej systemu phpwcms';
$BL['be_admin_startup_text']        = 'Treść tekstu';
$BL['be_admin_startup_button']      = 'Zapisz tekst';

// added: 17-04-2004
$BL['be_ctype_guestbook']        = 'księga gości/komentarze';
$BL['be_cnt_guestbook_listing']        = 'pokazuj';
$BL['be_cnt_guestbook_listing_all']    = 'pokaż&nbsp;wszystkie&nbsp;wpisy';
$BL['be_cnt_guestbook_list']        = 'wpisów';
$BL['be_cnt_guestbook_perpage']        = 'na&nbsp;stronę';
$BL['be_cnt_guestbook_form']        = 'formularz';
$BL['be_cnt_guestbook_signed']        = 'podpisane';
$BL['be_cnt_guestbook_nav']        = 'nawig.';
$BL['be_cnt_guestbook_before']        = 'przed';
$BL['be_cnt_guestbook_after']        = 'po';
$BL['be_cnt_guestbook_entry']        = 'wpis';
$BL['be_cnt_guestbook_edit']        = 'edycja';
$BL['be_cnt_ecardform_selector']    = 'wybierz';
$BL['be_cnt_ecardform_radiobutton'] = 'pole wyboru';
$BL['be_cnt_ecardform_javascript']  = 'funkcjonalność JavaScript';
$BL['be_cnt_ecardform_over']        = 'onMouseOver';
$BL['be_cnt_ecardform_click']       = 'onClick';
$BL['be_cnt_ecardform_out']           = 'onMouseOut';
$BL['be_admin_struct_topcount']     = 'ilość artykułów na górze';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'Nowości';
$BL['be_newsletter_addnl']              = 'Dodaj nowość';
$BL['be_newsletter_titleeditnl']        = 'Edycja nowości';
$BL['be_newsletter_newnl']              = 'Utwórz nową';
$BL['be_newsletter_button_savenl']      = 'Zapisz nowość';
$BL['be_newsletter_fromname']           = 'od nazwa';
$BL['be_newsletter_fromemail']          = 'e-mail od';
$BL['be_newsletter_replyto']            = 'e-mail odp';
$BL['be_newsletter_changed']            = 'ostanio&nbsp; zmieniono';
$BL['be_newsletter_placeholder']        = 'umieść';
$BL['be_newsletter_htmlpart']           = 'Treść nowości w HTML';
$BL['be_newsletter_textpart']           = 'Treść nowości tekstowa';
$BL['be_newsletter_allsubscriptions']   = 'Wszystkie subskrypcje';
$BL['be_newsletter_verifypage']         = 'Sprawdź odnośnik';
$BL['be_newsletter_open']               = 'pola wprowadzania treści nowości';
$BL['be_newsletter_open1']              = '(kliknij żeby otworzyć edytor)';
$BL['be_newsletter_sendnow']            = 'wyślij teraz';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Uwaga!</strong> Wysyłanie wielu nowości naraz do dużej ilość odbiorców jest niebezpieczne. Odbiorcy powinni być zweryfikowani inaczej Twoja wysyłka może zostać potraktowana jako SPAM. .Pomyśl dwa razy zanim wyślesz nowości. Sprawdź nowości poprzez wysyłkę testu.';
$BL['be_newsletter_attention1']         = 'Jeśli dokonałeś zmian w nowości, zapisz ją najpierw inaczej nie zostanie ona użyta.';
$BL['be_newsletter_testemail']          = 'Testuj email';
$BL['be_newsletter_sendnlbutton']       = 'Wyślij nowość';
$BL['be_newsletter_sendprocess']        = 'Proces wysyłania';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Uwaga!</strong> Proszę nie przerywać procesu wysyłania. Inaczej może zaistnieć możliwość wysłania tej samej nowości dwa razy do tego samego odbiorcy.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">adres testowy <strong>###TEST###</strong> jest nie poprawny!<br />&nbsp;<br />Proszę spróbuj ponownie!';
$BL['be_newsletter_to']                 = 'Odbiorcy';
$BL['be_newsletter_ready']              = 'Wysyłanie nowości: zakończono';
$BL['be_newsletter_readyfailed']        = 'Nie udało się wysłać nowości do';
$BL['be_subnav_msg_subscribers']        = 'Subskrybenci nowości';

// added: 20-04-2004
$BL['be_ctype_sitemap']            = 'Mapa witryny';
$BL['be_cnt_sitemap_catimage']          = 'ikona poziomu';
$BL['be_cnt_sitemap_articleimage']      = 'ikona artykułu';
$BL['be_cnt_sitemap_display']           = 'wyświetl';
$BL['be_cnt_sitemap_structuronly']      = 'tylko poziomy struktury';
$BL['be_cnt_sitemap_structurarticle']   = 'poziomy struktury i artykuły';
$BL['be_cnt_sitemap_catclass']          = 'klasa poziomu';
$BL['be_cnt_sitemap_articleclass']      = 'klasa artykułu';
$BL['be_cnt_sitemap_count']             = 'licznik';
$BL['be_cnt_sitemap_classcount']        = 'dodaj do nazwy klasy';
$BL['be_cnt_sitemap_noclasscount']      = 'nie dodawaj do nazwy klasy';
$BL['be_cnt_sitemap_without_parent']    = 'bez poziomu początkowego';

// added: 23-04-2004
$BL['be_ctype_bid']            = 'Oferta';
$BL['be_cnt_bid_bidtext']               = 'treść oferty';
$BL['be_cnt_bid_sendtext']              = 'tekst do&nbsp; wysłania';
$BL['be_cnt_bid_verifiedtext']          = 'tekst&nbsp; weryfikacji';
$BL['be_cnt_bid_errortext']             = 'oferta&nbsp; usunięta';
$BL['be_cnt_bid_verifyemail']           = 'weryfikacja&nbsp; emaila';
$BL['be_cnt_bid_startbid']              = 'rozpocznij od';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'zwiększ&nbsp;o';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'zewnętrzna treść';
$BL['be_cnt_pages_select']              = 'wybierz plik';
$BL['be_cnt_pages_fromfile']            = 'plik ze struktury';
$BL['be_cnt_pages_manually']            = 'własna ścieżka/plik lub adres URL';
$BL['be_cnt_pages_cust']                = 'plik/URL';
$BL['be_cnt_pages_from']                = 'źródło';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'przewijalna grafika';
$BL['be_cnt_reference_basis']           = 'wyrównanie';
$BL['be_cnt_reference_horizontal']      = 'poziomo';
$BL['be_cnt_reference_vertical']        = 'pionowo';
$BL['be_cnt_reference_aligntext']       = 'małe obrazki';
$BL['be_cnt_reference_largetext']       = 'duże obrazki';
$BL['be_cnt_reference_zoom']            = 'powiększenie';
$BL['be_cnt_reference_middle']          = 'pośrodku';
$BL['be_cnt_reference_border']          = 'ramka';
$BL['be_cnt_reference_block']           = 'blok sz x w';

// added: 31-05-2004
$BL['be_article_rendering']             = 'wyświetlanie';
$BL['be_article_nosummary']             = 'nie wyświetlaj podsumowania razem z całością artykułu';
$BL['be_article_forlist']               = 'wylistuj artykuł';
$BL['be_article_forfull']               = 'wyświetl cały artykuł';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>Uwaga!</strong> Katalog &quot;SETUP&quot; nadal istnieje! Skasuj ten katalog - może być on przyczyną potencjalnych problemów z bezpieczeństwem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'zabronione&nbsp; słowa';
$BL['be_cnt_guestbook_flooding']        = 'blokady';
$BL['be_cnt_guestbook_setcookie']       = 'ustaw cookie';
$BL['be_cnt_guestbook_allowed']         = 'zezwól ponownie po';
$BL['be_cnt_guestbook_seconds']         = 'sekundach';
$BL['be_alias_ID']                      = 'ID aliasu';
$BL['be_ftrash_delall']                 = "Czy chcesz na pewno usunąć \nWSZYSTKIE PLIKI z kosza?";
$BL['be_ftrash_delallfiles']            = 'usuń wszystkie pliki z kosza';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'import subskrybentów z pliku CSV';
$BL['be_newsletter_importtitle']        = 'Importuj Subskrybentów Nowości';
$BL['be_newsletter_entriesfound']       = 'znaleziono&nbsp;wpisów';
$BL['be_newsletter_foundinfile']        = 'w pliku';
$BL['be_newsletter_addresses']          = 'adresy';
$BL['be_newsletter_csverror']           = 'Importowany plik CSV jest niepoprawny!';
$BL['be_newsletter_addressesadded']     = 'adresy dodano.';
$BL['be_newsletter_newimport']          = 'importuj';
$BL['be_newsletter_importerror']        = 'Proszę sprawdź swój plik CSV - nie ma w nim żadnych adresów!';
$BL['be_newsletter_shouldbe1']          = 'Twój plik CSV powinien być sformatowany tak jak';
$BL['be_newsletter_shouldbe2']          = 'ale możesz wybrać swój własny znak rozdzielający';
$BL['be_newsletter_sample']             = 'przykład';
$BL['be_newsletter_selectCSV']          = 'wybierz plik CSV';
$BL['be_newsletter_delimeter']          = 'znak rozdzielający';
$BL['be_newsletter_importCSV']          = 'importuj plik';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'Kolejność przydzielona artykułom';
$BL['be_admin_struct_orderdate']        = 'data utworzenia';
$BL['be_admin_struct_orderchangedate']  = 'data zmiany';
$BL['be_admin_struct_orderstartdate']   = 'data rozp.';
$BL['be_admin_struct_orderdesc']        = 'malejąco';
$BL['be_admin_struct_orderasc']         = 'rosnąco';
$BL['be_admin_struct_ordermanual']      = 'ręcznie (w górę/dół)';
$BL['be_cnt_sitemap_startid']           = 'rozpocznij na';

// added: 20-10-2004
$BL['be_ctype_map']           = 'mapa';
$BL['be_save_btn']            = 'Zapisz';
$BL['be_cmap_location_error_notitle']   = 'wpisz tytuł dla tej lokalizacji.';
$BL['be_cnt_map_add']        = 'dodaj lokalizację';
$BL['be_cnt_map_edit']       = 'edytuj lokalizację';
$BL['be_cnt_map_title']      = 'tytuł lokalizacji';
$BL['be_cnt_map_info']       = 'wpis/informacja';
$BL['be_cnt_map_list']       = 'lista lokalizacji';
$BL['be_btn_delete']         = 'Czy na pewno chcesz\nusunąć lokalizację?';

// added: 05-11-2004
$BL['be_ctype_phpvar']       = 'Zmienne PHP';
$BL['be_cnt_vars']           = 'zmienne';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']         = 'kopiuj artykuł';
$BL['be_func_struct_nocopy']       = 'anuluj kopiowanie';
$BL['be_func_struct_copy_level']   = 'kopiuj poziom struktury';
$BL['be_func_struct_no_copy']      = "Nie można kopiować głównego poziomu struktury!";

// added: 27-11-2004
$BL['be_date_minute']   = 'minuta';
$BL['be_date_minutes']  = 'minuty';
$BL['be_date_hour']     = 'godzina';
$BL['be_date_hours']    = 'godziny';
$BL['be_date_day']      = 'dzień';
$BL['be_date_days']     = 'dni';
$BL['be_date_week']     = 'tydzień';
$BL['be_date_weeks']    = 'tygodnie';
$BL['be_date_month']    = 'miesiąc';
$BL['be_date_months']   = 'miesiące';
$BL['be_off']           = 'wyłącz';
$BL['be_on']            = 'włącz';
$BL['be_cache']         = 'pamięć podręczna';
$BL['be_cache_timeout'] = 'czas wygaśn.';

// added: 13-12-2004
$BL['be_subnav_admin_groups']    = 'użytkownicy i grupy';
$BL['be_admin_group_add']        = 'dodaj grupę';
$BL['be_admin_group_nogroup']    = 'nie znaleziono użytkownika grupy';

// added: 20-12-2004
$BL['be_ctype_forum']       = 'forum';
$BL['be_subnav_msg_forum'] = 'lista forów';
$BL['be_forum_title']       = 'tytuł forum';
$BL['be_forum_permission'] = 'uprawnienia';
$BL['be_forum_add']       = 'dodaj forum';
$BL['be_forum_titleedit']  = 'edytuj forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']   = 'własne';
$BL['be_show_content']       = 'wyświetl';
$BL['be_main_content']       = 'główna kolumna';
$BL['be_admin_template_jswarning']  = 'UWAGA!!! \nWłasne bloki mogą się zmienić! \n\nJeżeli anulujesz \nlub zresetujesz ustawienia układu! \n\nZmienić szablon?\n\n';

$BL['be_ctype_rssfeed']        = 'RSS';
$BL['be_cnt_rssfeed_url']    = 'adres url RSS';
$BL['be_cnt_rssfeed_item']    = 'elementy';
$BL['be_cnt_rssfeed_max']    = 'maks.';
$BL['be_cnt_rssfeed_cut']    = 'ukryj 1szy element';

$BL['be_ctype_simpleform']    = 'formularz email';

$BL['be_cnt_onsuccess']        = 'przy sukcesie';
$BL['be_cnt_onerror']        = 'przy błędzie';
$BL['be_cnt_onsuccess_redirect']  = 'przekieruj gdy sukces';
$BL['be_cnt_onerror_redirect']      = 'przekieruj gdy błąd';

$BL['be_cnt_form_class']    = 'klasa formularza';
$BL['be_cnt_label_wrap']    = 'wcięcie etykiety';
$BL['be_cnt_error_class']    = 'klasa błędu';
$BL['be_cnt_req_mark']        = 'oznaczenie wymagalności';
$BL['be_cnt_mark_as_req']    = 'znak jako wymagalny';
$BL['be_cnt_mark_as_del']    = 'znak elementu do usunięcia';


$BL['be_cnt_type']    = 'typ';
$BL['be_cnt_label']    = 'etykieta';
$BL['be_cnt_needed']    = 'wymagana';
$BL['be_cnt_delete']    = 'usunięta';
$BL['be_cnt_value']    = 'wartość';
$BL['be_cnt_error_text']    = 'tekst błędu';
$BL['be_cnt_css_style']        = 'styl CSS';
$BL['be_cnt_css_class']        = 'CSS class';
$BL['be_cnt_send_copy_to'] = 'Kopia do';

$BL['be_cnt_field']        = array("text"=>'text (jedno-liniowy)',
                "email"=>'e-mail',
                "textarea"=>'tekst (wielo-liniowy)',
                "hidden"=>'ukryty',
                "password"=>'hasło',
                "select"=>'wybór menu',
                "list"=>'lista menu',
                "checkbox"=>'pole wyboru',
                "checkboxcopy"=>'pole wyboru (kopia e-mail wł./wył.)',
                "radio"=>'przycisk opcji',
                "upload"=>'plik',
                "submit"=>'przycisk wysyłający',
                "reset"=>'przycisk resetujący',
                "break"=>'przerwa',
                "special"=>'tekst (specjalny)',
                "captchaimg"=>'obraz captcha',
                "captcha"=>'kod captcha',
                'newsletter'=>'biuletyn',
                'selectemail'=>'wybierz menu e-mail',
                'country'=>'wybierz menu kraju',
                'mathspam'=>'ochrona spamowa',
                'summing'=>'podsumowanie',
                'subtract'=>'odejmij',
                'divide'=>'podziel', 'multiply'=>'pomnóż',
                'calculation'=>'kalkulacja:',
                'formtracking_off'=>'wyłącz śledzenie formularza',
                'checktofrom'=>'e-mail odbiorcy musi się różnić od nadawcy',
                                'recaptcha'=>'reCAPTCHA',
                'recaptcha_signapikey'=>'Zarejestruj się na reCAPTCHA API key');

$BL['be_cnt_access']    = 'dostęp';
$BL['be_cnt_activated']    = 'aktywowany';
$BL['be_cnt_available']    = 'dostępny';
$BL['be_cnt_guests']    = 'goście';
$BL['be_cnt_admin']    = 'administrator';
$BL['be_cnt_write']    = 'zapisz';
$BL['be_cnt_read']    = 'czytaj';

$BL['be_cnt_no_wysiwyg_editor']     = 'wyłącz edytor WYSIWYG';
$BL['be_cnt_cache_update']     = 'zresetuj cache';
$BL['be_cnt_cache_delete']     = 'opróżnij cache';
$BL['be_cnt_cache_delete_msg'] = 'Czy na pewno chcesz opróżnić pamięć podręczną?';

$BL['be_admin_usr_issection']  = 'Uprawnienia do logowania';
$BL['be_admin_usr_ifsection0'] = 'witryna';
$BL['be_admin_usr_ifsection1'] = 'system wcms';
$BL['be_admin_usr_ifsection2'] = 'witryn i system wcms';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']    = 'Edytuj treść artykułu';
$BL['be_func_content_paste0']  = 'Wklej do artykułu';
$BL['be_func_content_paste']   = 'Wklej później do artykułu';
$BL['be_func_content_cut']     = 'Wytnij treść artykułu';
$BL['be_func_content_no_cut']  = "Nie można wyciąć treści artykułu!";
$BL['be_func_content_copy']    = 'Kopiuj treść artykułu';
$BL['be_func_content_no_copy'] = "Nie można skopiować treści artykułu!";
$BL['be_func_content_paste_cancel'] = 'anuluj zmiany';

$BL['be_cnt_move_deleted']       = 'Skasuj usunięte pliki';
$BL['be_cnt_move_deleted_msg'] = 'Czy na pewno chcesz przesunąć wszystkie \noznaczone pliki do specjalnego folderu?  \n';

$BL['be_admin_struct_permit']        = 'autoryzacja dostępu (pozostawione puste - dostęp dla wszystkich)';
$BL['be_admin_struct_adduser_all']  = 'dodaj wszystkich użytkowników';
$BL['be_admin_struct_adduser_this'] = 'dodaj wybranych uzytkowników';
$BL['be_admin_struct_remove_all']   = 'usuń wszystkich użytkowników';
$BL['be_admin_struct_remove_this']  = 'usuń wybranych użytkowników';


$BL['be_ctype_alias'] = 'alias treści';
$BL['be_cnt_setting'] = 'konfiguracja';
$BL['be_cnt_spaces']  = 'odstępy oryginalnej treści';
$BL['be_cnt_toplink'] = 'odnośnik na górę oryginalnej treści';
$BL['be_cnt_block']   = 'wyświetl ustawienie bloku oryginalnej treści';
$BL['be_cnt_title']   = 'tytuły oryginalnej treści';
$BL['be_cnt_status']  = 'widoczność treści aliasu';
$BL['be_cnt_plugin_n.a.'] = 'wtyczka jest niedostępna';

$BL['be_file_replace']       = 'Zastąp tytuły plików';

$BL['be_alias_articleID']  = 'ID artykułu';
$BL['be_alias_useAll']       = "użyj nagłówka tego artykułu";
$BL['be_article_morelink'] = 'odnośnik [więcej...]';
$BL['be_admin_tmpl_copy']  = 'kopiuj szablon';

$BL['be_ctype_filelist1']  = 'lista plików pro';
$BL['be_cnt_fpro_usecaption']   = 'użyj centrum plików &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']    = 'Słowa kluczowe';
$BL['be_admin_keywords_key']    = 'SŁOWO KLUCZOWE';
$BL['be_admin_keywords_err']    = 'Wstaw unikalne SŁOWO KLUCZOWE o nazwie';
$BL['be_admin_keyword_edit']    = 'edytuj SŁOWO KLUCZOWE';
$BL['be_admin_keyword_del']    = 'usuń SŁOWO KLUCZOWE';
$BL['be_admin_keyword_delmsg']    = 'Rzeczywiście chcesz\nto usunąć SŁOWO KLUCZOWE?';
$BL['be_admin_keyword_add']    = 'dodaj SŁOWO KLUCZOWE';

$BL['be_cnt_transparent'] = 'Flash przeźroczysty';


// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']   = 'daty usunięcia';
$BL['be_func_switch_contentpart'] = 'Czy naprawdę chcesz przełączyć część zawartości ? \n\nJest to bardzo ważna zmiana więc! \nIstotne parametry mogą zostać zmienione! \n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>UWAGA!</strong> Katalog &quot;CODE-SNIPPETS&quot; nadal istnieje! Usuń katalog <strong>phpwcms_code_snippets</strong> - jest on potencjalnym problemem bezpieczeństwa.';

$BL['be_ctype_poll'] = 'ankieta';
$BL['be_cnt_pos8']   = 'tabela, lewo';
$BL['be_cnt_pos9']   = 'tabela, prawo';
$BL['be_cnt_pos8i']  = 'wyrównaj obraz w lewo w tabeli';
$BL['be_cnt_pos9i']  = 'wyrównaj obraz w prawo w tabeli';


$BL['be_WYSIWYG']    = 'WYSIWYG edytor';
$BL['be_WYSIWYG_disabled']    = 'WYSIWYG edytor wyłączony';
$BL['be_admin_struct_acat_hiddenactive'] = 'widoczny kiedy aktywny';

$BL['be_login_jsinfo']    = 'Proszę włącz JavaScript który jest niezbędny wewnątrz!';

$BL['be_admin_struct_maxlist']    = 'maks. artykułów w trybie listy';

$BL['be_admin_optgroup_label']    = array(1 => 'text', 2 => 'image', 3 => 'form', 4 => 'admin', 5 => 'special');
$BL['be_cnt_articlemenu_maxchar']    = 'max. znaków';

$BL['be_cnt_sysadmin_system']        = 'system';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date']        = 'Twoja instalacja jest aktualna,brak możliwych aktualizacji tej wersji phpwcms.';
$BL['Version_not_up_to_date']        = 'Twoja instalacja <b>nie</b> wydaje się być aktualna. Dostępne są,aktualizacje tej wersji phpwcms-a. Proszę odwiedź <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a> aby poznać szczegóły.';
$BL['Latest_version_info']        = 'Ostatnia dostępna wersja to <b>phpwcms %s</b>.';
$BL['Current_version_info']        = 'Twój bieżący <b>phpwcms %s</b>.';
$BL['Connect_socket_error']        = 'Nie można otworzyć połączenia z serwerem phpwcms , zgłaszany jest błąd:<br />%s';
$BL['Socket_functions_disabled']    = 'Niemożliwe jest użycie funkcji socket';
$BL['Mailing_list_subscribe_reminder']    = 'Aby otrzymywać informacje o aktualizacjach do Twojego phpwcms,prenumeruj <a href="http://eepurl.com/bm-BrH" target="_blank">listę mailingową</a>.';
$BL['Version_information']        = 'Informacja o wersji phpwcms';

$BL['be_cnt_search_highlight']        = 'podświetlenie';
$BL['be_cnt_results_wordlimit']        = 'maks. słów dla podsumowania';
$BL['be_cnt_page_of_pages']        = 'search navi';
$BL['be_cnt_page_of_pages_descr']    = '{PREV:Wróć} strona #/##, wynik ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Nast.}';
$BL['be_cnt_search_show_top']        = 'góra';
$BL['be_cnt_search_show_bottom']    = 'dół';
$BL['be_cnt_search_show_next']        = 'następny (także jeżeli nie link)';
$BL['be_cnt_search_show_prev']        = 'poprzedni (także jeżeli nie link)';
$BL['be_cnt_search_show_forall']    = 'pokaż zawsze';
$BL['be_cnt_search_startlevel']        = 'zacznij szukać';
$BL['be_cnt_results_minchar']        = 'minimalna liczba znaków w zapytaniu';
$BL['be_cnt_search_hidesummary']    = 'ukryj wyszukiwanie tekstu zapowiedzi';
$BL['be_cnt_search_searchnot']    = 'nie szukaj';

$BL['be_cnt_pagination']    = 'paginuj elementy zawartości';
$BL['be_article_pagination']    = 'paginuj artykuły';
$BL['be_article_per_page']    = 'artykułów na stronę';
$BL['be_pagination']        = 'paginacja';


$BL['be_ctype_recipe']        = 'odbiorca';
$BL['be_ctype_faq']        = 'faq';
$BL['be_cnt_additional']    = 'dodatkowe';
$BL['be_cnt_question']        = 'pytanie';
$BL['be_cnt_answer']        = 'odpowiedź';
$BL['be_cnt_same_as_summary']    = 'użyj danych obrazu artykułu';
$BL['be_cnt_sorting']        = 'sortowanie';
$BL['be_cnt_imgupload']        = 'prześlij&nbsp;obraz';
$BL['be_cnt_filesize']        = 'rozmiar pliku';
$BL['be_cnt_captchalength']    = 'długość kodu captcha';
$BL['be_cnt_chars']        = 'znaków';
$BL['be_cnt_download']        = 'pobierz';
$BL['be_cnt_download_direct']    = 'bezpośredni';
$BL['be_cnt_database']        = 'baza danych';
$BL['be_cnt_formsave_in_db']    = 'zapisz wyniki formularza';

$BL['be_cnt_email_notify']    = 'powiadom przez e-mail';
$BL['be_cnt_notify_by_email']    = 'przez e-mail do';
$BL['be_cnt_last_edited']    = 'ostatnio zmieniane';

$BL['be_cnt_export_selection']    = 'eksportuj wybrane';
$BL['be_cnt_delete_duplicates']    = 'usuń duplikaty';
$BL['be_cnt_new_recipient']    = 'dodaj odbiorcę';


$BL['be_cnt_newsletter_prepare']    = 'biuletyn aktywny';
$BL['be_cnt_newsletter_prepare1']   = 'wszyscy odbiorcy będą przyjęci do kolejki wysyłania';
$BL['be_cnt_newsletter_prepare2']   = 'kolejka wysyłania została zaktualizowana&#8230;';

$BL['be_cnt_export']        = 'export';
$BL['be_cnt_formsave_profile']    = 'zapisz dane profilu użytkownika';
$BL['be_profile_label_add']    = 'dodatkowe';
$BL['be_profile_label_website']    = 'url';
$BL['be_profile_label_gender']    = 'rodzaj';
$BL['be_profile_label_birthday']    = 'urodziny';

$BL['be_cnt_store_in']        = 'zapisz pole';
$BL['be_aboutlink_title']    = 'Informacja o phpwcms i licencji';

$BL['be_shortdate']       = 'n/j/y';
$BL['be_shortdatetime']   = 'n/j/y G:i';
$BL['be_longdatetime']    = 'm/d/Y H:i:s';

$BL['be_confirm_sending']    = 'Potwierdź wysyłanie';
$BL['be_confirm_text']    = 'Tak, wyślij biuletyn do wszystkich odbiorców!';

$BL['be_cnt_queued']    = 'kolejkowanie';
$BL['be_last_sending']    = 'ostatnia wysyłka';
$BL['be_last_edited']    = 'ostatnio zmieniane';
$BL['be_total']        = 'łącznie';

$BL['be_settings']    = 'ustawienia';
$BL['be_ctype']        = 'zawartość';
$BL['be_selection']    = 'zaznaczenie';

$BL['be_ctype_module']    = 'moduł';
$BL['be_cnt_lightbox']    = 'galeria obrazów';
$BL['be_cnt_behavior']    = 'zachowanie';
$BL['be_cnt_imglist_nocaption']    = 'ukryj nagłówek dla miniatur';

$BL['be_ctype_felogin']        = 'Logowanie na stronie frontowej';
$BL['be_cookie_runtime']    = 'wygaśnięcie cookie';
$BL['be_locale']        = 'lokalnie';
$BL['be_date_format']        = 'format daty';

$BL['be_check_login_against']    = 'Sprawdź poprawność logowania';
$BL['be_userprofile_db']    = 'Profil użytkownika bazy danych';
$BL['be_backenduser_db']    = 'Zaplecze użytkownika bazy danych';

$BL['be_gb_post_login']        = 'Poczta tylko dla zalogowanych użytkowników';
$BL['be_gb_show_login']        = 'Pokaż tylko zarejestrowanym użytkownikom';
$BL['be_gb_urlcheck']        = 'Włącz zdalną kontrolę poprawności URL';
$BL['be_order']            = 'kolejność';

$BL['be_unique_teaser_entry']    = 'pokaż zwiastun/link artykułu tylko jeden raz na stronę';
$BL['be_allowed_tags']        = 'dopuszczalne tagi';
$BL['be_fe_login_url']        = 'FE url logowania';
$BL['be_ctype_imagesdiv']    = 'grafika &lt;div&gt;';
$BL['be_cnt_imagecenter']    = 'poziome/pionowe wyśrodkowanie';
$BL['be_cnt_imagenocenter']    = 'bez wyśrodkowania';
$BL['be_cnt_imagecenterh']    = 'wyśrodkowanie w poziomie';
$BL['be_cnt_imagecenterv']    = 'wyśrodkowanie w pionie';
$BL['be_check_against_category_alias']    = 'link jednego artykułu, wewnątrz poziom struktury z z poziomem struktury';

$BL['be_overwrite_default']    = 'Zostaną zmienione domyślne ustawienia pliku konfiguracyjnego';
$BL['be_cnt_sortvalue']        = 'sortowanie&nbsp;wartość';
$BL['be_dialog_warn_nosave']    = 'Jeżeli będziesz kontynuował zmiany nie zostaną zapisane!\nCzy naprawdę chcesz kontynuować?';
$BL['be_cnt_paginate_subsection']    = 'podrozdział';
$BL['be_cnt_subsection_tite']        = 'tytuł podrozdziału';
$BL['be_cnt_subsection_warning']    = 'Numeracja podrozdziałów (paginate content parts) jest możliwa dla\nmain column (CONTENT) tylko!';

$BL['be_no_search']        = 'nie szukaj';
$BL['be_priorize']        = 'priorytet';
$BL['be_change_articleID']    = 'zmień ID artykułu';
$BL['be_title_wrap']        = 'zawijaj tytuł artykułu';

$BL['be_no_rss']        = 'RSS';
$BL['be_article_urlalias']    = 'Alias artykułu';

$BL['be_image_crop']    = 'kadruj miniaturę';
$BL['be_image_cropit']    = 'kadruj obrazek';
$BL['be_image_align']    = 'wyrównanie obrazka';

$BL['be_ctype_flashplayer']    = 'flash odtwarzacz multimedialny';
$BL['be_flashplayer_caption']   = 'podpis';
$BL['be_flashplayer_thumbnail']    = 'miniaturka';
$BL['be_flashplayer_selectsize']    = 'Wybierz rozmiar odtwarzacza';
$BL['be_flash_media']    = 'Flash';
$BL['be_html5_media']    = 'HTML5';
$BL['be_html5_h264']    = 'H.264';
$BL['be_html5_webm']    = 'WebM';
$BL['be_html5_ogg']    = 'Ogg';
$BL['be_media_format']    = 'format';
$BL['be_media_watermark']    = 'znak wodny';
$BL['be_skin']            = 'skóra';
$BL['be_foreground_color']    = 'Kolor pierwszego planu';
$BL['be_background_color']    = 'Kolor tła';
$BL['be_highlight_color']    = 'Kolor podświetlenia';

$BL['be_check_feuser_profile']        = 'Profil użytkownika witryny';
$BL['be_check_feuser_registration']    = 'Rejestracja';
$BL['be_check_feuser_manage']        = 'Zarządzane przez użytkownika';
$BL['be_hide_active_articlelink']    = 'Ukryj aktywne artykuły w menu artykułów';

$BL['be_module_search']             = 'szukaj również';

$BL['be_ctype_imagesspecial']    = 'obrazy specjalne';
$BL['be_image_WxHpx']    = 'W x H px';
$BL['be_fx_1']        = 'efekt 1';
$BL['be_fx_2']        = 'efekt 2';
$BL['be_fx_3']        = 'efekt 3';
$BL['be_image_zoom']    = 'powiększony widok';
$BL['be_image_delete_js']    = 'Czy chcesz usunąć wybrany wpis obrazka?';

$BL['be_news']            = 'Aktualności';
$BL['be_news_create']    = 'Utwórz wpis news-a';
$BL['be_tags']            = 'tagi';
$BL['be_title']            = 'tytuł';
$BL['be_delete_dataset']    = 'Usunąć wybrane dane?';
$BL['be_action_notvalid']    = 'Twoje ostatnio wybrane działanie zostało pominięte, ponieważ nie było prawidłowe!';
$BL['be_action_deleted']    = 'Wybrany zestaw danych mający ID {ID} został usunięty.';
$BL['be_action_status']        = 'Stan wybranego zestawu danych mającego ID {ID} został zmieniony.';
$BL['be_data_select_failed']    = 'Dostęp do wybranych danych nie powiódł się. Proszę potwierdzić swój wybór.';
$BL['be_alias']        = 'Alias';
$BL['be_url_value']    = 'Tytuł URL';
$BL['default_date_format']    = 'DD/MM/YYYY';
$BL['default_date']        = 'd/m/Y'; // do not use something diffrent than "d, m, Y" here
$BL['default_date_delimiter']    = '/';
$BL['default_time_format']    = 'HH:MM';
$BL['default_time']        = 'H:i';  // do not use something diffrent than "H, i" here
$BL['be_place']            = 'Miejsce';
$BL['be_teasertext']    = 'Tekst zwiastuna';
$BL['be_published']        = 'Opublikuj';
$BL['be_show_archived']        = 'Dostępne po dacie zakończenia (archiwum)';
$BL['be_save_copy']        = 'Zapisz wpisy jako duplikaty';
$BL['be_read_more_link']    = 'Więcej URL/ID';
$BL['be_news_name_mandatory']    = "Wpisz tytuł newsa. Jest to obowiązkowe!";
$BL['be_successfully_saved']    = 'Wszystkie dane zostały zapisane!';
$BL['be_successfully_updated']    = 'Wszystkie dane zostały zaktualizowane!!';
$BL['be_error_while_save']    = 'Zapisywanie danych nie powiodło się.';
$BL['be_copyright']        = 'Prawa autorskie';
$BL['be_file_multiple_upload']    = 'Przesyłanie wielu plików';
$BL['be_files_select_available'] = 'Wybierz poprzednio przesłane pliki';
$BL['be_files_browse']         = 'Przeglądanie plików';
$BL['be_files_upload']    = 'Prześlij wybrane pliki';
$BL['be_archive']    = 'archiwum';
$BL['be_off']        = 'wył';
$BL['be_on']        = 'wł';
$BL['be_random']    = 'losowo';
$BL['be_sorted']    = 'posortowane';
$BL['be_granted_download'] = 'bezpieczne pobieranie tylko w witrynie';
$BL['be_granted_feuser'] = 'Tylko dla zalogowanych użytkowników serwisu';
$BL['be_fileuploader_typeError']    = "{file} ma nieprawidłowe rozszerzenie. Prawidłowe rozszerzenie(a): {extensions}.";
$BL['be_fileuploader_sizeError']    = "{file} jest zbyt duży, maksymalny rozmiar pliku to {sizeLimit}.";
$BL['be_fileuploader_minSizeError']    = "{file} jest zbyt mały, minimalny rozmiar pliku to {minSizeLimit}.";
$BL['be_fileuploader_emptyError']    = "{file} jest pusty, wybierz pliki ponownie bez niego.";
$BL['be_fileuploader_noFilesError']    = "Brak plików do wgrywania.";
$BL['be_fileuploader_onLeave']        = "Pliki zostały przesłane, jeśli zostawisz teraz wysyłanie zostanie anulowane.";
$BL['be_fileuploader_dragText']        = "Upuść pliki tutaj, aby załadować!";
$BL['be_fileuploader_uploadButtonText']    = 'Wybierz pliki lub upuść tutaj';
$BL['be_delete_selected_files']        = 'Usuń zaznaczone pliki';
$BL['be_delete_selected_files_confirm']    = 'Czy na pewno chcesz usunąć wszystkie zaznaczone pliki?';

$BL['be_ctype_tabs']    = 'karty';
$BL['be_tab_add']    = 'dodaj kartę';
$BL['be_tab_name']    = 'karta';
$BL['be_headline']    = 'nagłówek';
$BL['be_tab_delete_js']     = 'Czy chcesz usunąć wybraną kartę?';

$BL['be_pagniate_count'] = 'elementów na stronie';
$BL['be_limit_to']    = 'ogranicz do';
$BL['be_archived_items'] = 'archiwizowane elementy';
$BL['be_include']    = 'dołącz';
$BL['be_exclude']    = 'wyklucz';
$BL['be_solely']    = 'wyłącznie';
$BL['be_fsearch_not']    = 'NIE';
$BL['be_date_year']    = 'rok';
$BL['be_archive_link']    = 'link archiwum';
$BL['be_use_prio']        = 'zastosuj priorytetyzację';
$BL['be_skip_first_items']    = 'pomiń górne elementy';
$BL['be_news_detail_link']    = 'artykuł nowości';

$BL['be_gallerydownload']    = 'pozwól na pobieranie w galerii';
$BL['be_gallery_root']        = 'katalog główny galerii';
$BL['be_gallery_directory']    = 'podkatalog galerii';
$BL['be_gallery']    = 'galeria';

$BL['be_sort_date']    = 'sortuj daty';

$BL['group_superuser']    = 'superuser';
$BL['group_admin']    = 'administrator';
$BL['group_editor']    = 'redaktor';
$BL['group_newsletter']    = 'redaktor biuletynu';
$BL['group_client']    = 'klient';
$BL['group_guest']    = 'gość';

$BL['php_function']    = 'funkcja php';
$BL['article_menu_title']    = 'tytuł menu';

$BL['content_type']     = 'content-type';
$BL['automatic']        = 'automatycznie';

$BL['random_image']     = 'losowy obraz';
$BL['limit_image_from_list']    = 'Obrazów max.';

$BL['alt_image']   = 'alt. image';
$BL['alt_text']    = 'alt. text';
$BL['over']        = 'over';
$BL['js_lib']      = 'Biblioteka JS';
$BL['js_lib_alwaysload'] = 'zawsze ładuj';
$BL['frontendjs_load']   = 'ładuj frontend.js (więcej z przyczyn historycznych)';
$BL['googleapi_load']    = 'użyj CDN';

$BL['fancyupload_clear_list']       = 'Wyczyść Listę';
$BL['fancyupload_file_uploaded']    = 'Plik został przesłany';
$BL['fancyupload_file_error']       = 'Wystąpił błąd';
$BL['fancyupload_adblock_error']    = 'Aby włączyć wbudowany uploader, odblokuj kartę w swojej przeglądarce i odśwież (zobacz Adblock).';
$BL['fancyupload_flashblock_error']  = 'Aby włączyć wbudowany uploader, włącz zablokowany film Flash (zobacz Flashblock).';
$BL['fancyupload_required_error']    = 'Wymagany plik nie został znaleziony, prosimy o cierpliwość, a my to naprawimy.';
$BL['fancyupload_flash_error']       = 'Aby włączyć wbudowany uploader, należy zainstalować najnowszy plugin Adobe Flash.';

$BL['be_cnt_function_validate']  = 'Walidacja PHP';
$BL['be_structform_selected_cp'] = 'Wybór Limitu użytecznych części zawartości';
$BL['be_structform_select_cp']   = 'Wybierz elementy treści';

$BL['source_image_not_found']    = 'Błąd źródła obrazu: Obraz %s wydaje się nie istnieć.';
$BL['form_force_ssl']            = 'Wymuś wysyłanie formularzy z SSL';
$BL['numerize_title']            = 'Numerowane zamiast tytułów artykułów';
$BL['be_article_noteaser']       = 'bez zapowiedzi';
$BL['be_acat_disable301']        = 'artykuł 301 przekierowanie';

$BL['file_actions_step1']        = "Krok 1: wybierz folder";
$BL['file_actions_step2']        = "Krok 2: wybierz plik";
$BL['file_actions_step3']        = "Krok 3: wybierz działanie";
$BL['file_actions_button']       = 'Wykonaj operację';
$BL['file_actions_no']           = 'Brak plików do edycji. Wybierz inny folder ';
$BL['file_actions_delete']       = 'Czy jesteś pewien, że należy usunąć wybrane pliki?';
$BL['file_actions_bemuser']      = 'Wybrane pliki zostaną przypisane do nowego użytkownika i przeniesione do katalogu głównego.';
$BL['file_actions_bemfolder']    = 'Proszę wybrać folder docelowy. Wybrane pliki zostaną przeniesione do tego folderu. ';
$BL['file_actions_pdl_empty']    = 'wybierz działanie';
$BL['file_actions_pdl_delete']   = 'usuń pliki';
$BL['file_actions_pdl_move']     = 'przenieś pliki';
$BL['file_actions_pdl_status']   = 'zmień status';
$BL['file_actions_pdl_user']     = 'zmień właściciela';
$BL['file_actions_msg_move']     = 'Pliki zostały pomyślnie przeniesione';
$BL['file_actions_msg_delete']   = 'Pliki zostały pomyślnie usunięte';
$BL['file_actions_msg_status']   = 'Status plików pomyślnie zmienio';
$BL['file_actions_msg_error']    = 'Nie wybrano plików';
$BL['file_actions_msg_user']     = 'Pliki zostały pomyślnie przypisany do nowego użytkownika';

$BL['be_imagefiles_as_gallery']        = 'utwórz galerię z plików graficznych';

$BL['be_link']                = 'link';
$BL['be_links']               = 'linki';
$BL['be_redirect']            = 'przekierowanie';
$BL['be_redirects']           = 'przekierowania';
$BL['be_views']               = 'wywołań';
$BL['be_structure_id']        = 'ID struktury';
$BL['be_shortcut']            = 'skrót';
$BL['be_target_type']         = 'typ docelowy';
$BL['be_http_status']         = 'HTTP status';
$BL['be_http_status301']      = 'trwale przeniesiony';
$BL['be_http_status307']      = 'przekierowanie tymczasowe';
$BL['be_http_status404']      = 'nie znaleziono';
$BL['be_http_status401']      = 'nieautoryzowany';
$BL['be_http_status503']      = 'usługa niedostępna';
$BL['be_redirect_error1']     = 'Alias/Skrót, wymagana jest struktura lub ID artykułu';
$BL['be_redirect_error2']     = 'Cel jest wymagany';
$BL['be_redirect_error3']     = 'Dla docelowego typu, ID artykułu i ID struktury dozwolone są tylko liczby całkowite jako docelowe';
$BL['be_new_linkredirect']    = 'Dodaj link/przekierowanie';

$BL['be_ctype_accordion']     = 'grupa (accordion)';
$BL['be_ctype_number']        = 'numer';
$BL['be_inactive']            = 'nieaktywne';
$BL['be_locked']              = 'zablokowane';
$BL['be_n/a']                = 'n/a';
$BL['be_opengraph_support']  = 'Zezwalaj na Dzielenie się z innymi';
$BL['be_player_volume']      = 'Głośność';
$BL['be_player_volume_muted']      = 'wyciszone';
$BL['be_keyword']            = 'Słowo kluczowe';
$BL['be_tag']                = 'znacznik';

$BL['be_system_container']        = 'zasobnik systemu';
$BL['be_system_container_norender']    = 'bez regularnego renderowania serwisu';
$BL['be_custom_scriptlogic']        = 'zdefiniowany przez użytkownika (skrypt logiczny)';
$BL['be_flush_image_cache']        = 'opróżnij pamięć podręczną grafik';

$BL['be_caption_alt']             = 'poprz. atr.';
$BL['be_caption_title']           = 'tytuł atr.';
$BL['be_caption_file_imagesize']  = 'WxHxC <em>(jeżeli obraz)</em>';
$BL['be_caption_file_title']    = 'tytuł pliku';
$BL['be_caption_descr.']        = 'opis';
$BL['be_display_html5_only']    = 'tylko HTML5';
$BL['be_audio_only']            = 'tylko audio';

$BL['be_filter']		= 'filtr';
$BL['be_filter_with_tags']	= 'w/g etykiet';
$BL['be_filter_not_selected']	= 'Nie wybrano kategorii';
$BL['be_empty_search_result']	= 'Wyszukiwanie nie przyniosło wyników.';
$BL['confirm_cp_tab_warning']	= 'Podrozdział nie ma tytułu i nie jest przypisany żaden numer.Zaznaczenie zostanie utracone, podczas zapisu lub aktualizacji.';

$BL['be_canonical']		= 'link kanoniczny';
$BL['be_breadcrumb']		= 'zachowanie nawigacyjne wyświetlania';
$BL['be_breadcrumb_nothidden']	= 'widoczne, jeśli poziom jest ukryty';
$BL['be_breadcrumb_nolink']	= 'nie ma linku';

$BL['CSRF_POST_INVALID'] = 'Nie <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> znaleziono parametrów POST. Ze względów bezpieczeństwa, sesja została zakończona.';
$BL['CSRF_POST_FAILED'] = 'Weryfikacja <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> parametrów POST nie powiodła się. Ze względów bezpieczeństwa, sesja została zakończona.';
$BL['CSRF_GET_INVALID'] = 'Nie <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> znaleziono parametrów GET. Ze względów bezpieczeństwa, sesja została zakończona.';
$BL['CSRF_GET_FAILED'] = 'Weryfikacja <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> parametrów GET nie powiodła się. Ze względów bezpieczeństwa, sesja została zakończona.';

$BL['be_parental_alias'] = 'alias macierzysty';
$BL['be_fsearch_nor'] = 'BRAK';
$BL['be_tab_toggle'] = 'Przełącz zakładkę, by rozwinąć lub zamknąć';
$BL['be_custom_textfield'] = 'własny tekst';
$BL['be_tab_template_toggle_warning'] = 'Zmiana szablonu może spowodować, że niestandardowe pola ulegną zmianie i istniejące wartości utracone.\n\nCzy jesteś pewien, że chcesz kontynuować?';

$BL['be_onepage_id'] = 'OnePage ID (#zakotwicz) wsparcie';
$BL['be_onepage_template'] = 'Traktuj jako szablon OnePage';
$BL['be_yes'] = 'Tak';
$BL['be_no'] = 'Nie';
$BL['be_attr_title'] = 'Tytuł (Atrybut)';
$BL['be_attr_alt'] = 'Alternatywny Tekst';
$BL['be_ie8ignore'] = 'disable <a href="https://en.wikipedia.org/wiki/Conditional_comment" target="_blank" class="underline">conditional comments</a> for IE8';
$BL['be_cookie_consent_enable'] = 'włącz wtyczkę Zgody Cookie';
$BL['be_cookie_consent_message'] = 'komunikat zgody';
$BL['cookie_consent_message'] = 'Witryna ta wykorzystuje cookies, aby uzyskać najlepszą jakość na naszej stronie internetowej';
$BL['be_cookie_consent_dismiss'] = 'przycisk tekstu zwolenia';
$BL['cookie_consent_dismiss'] = 'Rozumiem!';
$BL['be_cookie_consent_more'] = 'tekst przycisku dowiedz się więcej';
$BL['cookie_consent_more'] = 'Więcej informacji';
$BL['be_cookie_consent_link'] = 'Polityka Cookie url/alias';
$BL['be_cookie_consent_theme'] = 'motyw (puste = brak CSS)';
