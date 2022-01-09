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
// Language: Polish, Language Code: pl UTF-8 for ver.1.9.0-beta.8 (2017/10/18, 549)
//Zięba Bogusław http://www.krynica.malopolska.pl
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13;', JavaScript Linebreak: '\n'

$BL['usr_online'] = 'Zalogowani użytkownicy';

// Login Page
$BL["login_text"] = 'Podaj swoje dane aby się zalogować';
$BL['login_error'] = 'Błąd podczas logowania!';
$BL["login_username"] = 'Użytkownik';
$BL["login_userpass"] = 'Hasło';
$BL["login_button"] = 'Zaloguj';
$BL["login_lang"] = 'Wybierz język';

// phpwcms.php
$BL['be_nav_logout'] = 'WYLOGUJ SIĘ';
$BL['be_nav_articles'] = 'ARTYKUŁY';
$BL['be_nav_files'] = 'PLIKI';
$BL['be_nav_modules'] = 'MODUŁY';
$BL['be_nav_messages'] = 'WIADOMOŚCI';
$BL['be_nav_chat'] = 'CHAT';
$BL['be_nav_profile'] = 'PROFILE';
$BL['be_nav_admin'] = 'ADMINISTRACJA';
$BL['be_nav_discuss'] = 'DYSKUSJA';

$BL['be_page_title'] = 'Zaplecze phpwcms (administracja)';

$BL['be_subnav_article_center'] = 'Centrum artykułów';
$BL['be_subnav_article_new'] = 'Nowy artykuł';
$BL['be_subnav_file_center'] = 'Centrum plików';
$BL['be_subnav_file_actions'] = 'Działania plików';
$BL['be_subnav_file_ftptakeover'] = 'Wgrane przez ftp';
$BL['be_subnav_mod_artists'] = 'Wykonawca, kategoria, rodzaj';
$BL['be_subnav_msg_center'] = 'Centrum wiadomości';
$BL['be_subnav_msg_new'] = 'Nowa wiadomość';
$BL['be_subnav_msg_newsletter'] = 'Subskrypcja nowości';
$BL['be_subnav_chat_main'] = 'Główna strona chatu';
$BL['be_subnav_chat_internal'] = 'Wewnętrzny chat';
$BL['be_subnav_profile_login'] = 'Informacje logowania';
$BL['be_subnav_profile_personal'] = 'Informacje osobiste';
$BL['be_subnav_admin_pagelayout'] = 'Układ strony';
$BL['be_subnav_admin_templates'] = 'Szablony';
$BL['be_subnav_admin_css'] = 'Domyślny styl css';
$BL['be_subnav_admin_sitestructure'] = 'Struktura witryny';
$BL['be_subnav_admin_users'] = 'Administracja użytkownikami';
$BL['be_subnav_admin_filecat'] = 'Kategorie plików';

// admin.functions.inc.php
$BL['be_func_struct_articleID'] = 'ID artykułu';
$BL['be_func_struct_preview'] = 'Podgląd';
$BL['be_func_struct_edit'] = 'Edytuj artykuł';
$BL['be_func_struct_sedit'] = 'Edytuj poziom struktury';
$BL['be_func_struct_cut'] = 'Wytnij artykuł';
$BL['be_func_struct_nocut'] = 'Zaniechaj wycięcia artykułu';
$BL['be_func_struct_svisible'] = 'Przełącz widoczny/niewidoczny';
$BL['be_func_struct_spublic'] = 'Przełącz publiczny/niepubliczny';
$BL['be_func_struct_sort_up'] = 'Sortuj w górę';
$BL['be_func_struct_sort_down'] = 'Sortuj w dół';
$BL['be_func_struct_del_article'] = 'Usuń artykuł';
$BL['be_func_struct_del_jsmsg'] = 'Czy na pewno chcesz \nusunąć artykuł?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article'] = 'Utwórz nowy artykuł na tym poziomie struktury';
$BL['be_func_struct_paste_article'] = 'Wklej artykuł na ten poziom struktury';
$BL['be_func_struct_insert_level'] = 'Wstaw w poziom struktury';
$BL['be_func_struct_paste_level'] = 'Wklej na ten poziom struktury';
$BL['be_func_struct_cut_level'] = 'Wytnij ten poziom struktury';
$BL['be_func_struct_no_cut'] = "Nie można wyciąć głównego poziomu struktury!";
$BL['be_func_struct_no_paste1'] = "Nie można tutaj wkleić!";
$BL['be_func_struct_no_paste2'] = 'Czy potomek jest równorzędny do głównego poziomu drzewa';
$BL['be_func_struct_no_paste3'] = 'To powinno zostać wklejone tutaj';
$BL['be_func_struct_paste_cancel'] = 'Anuluj zmianę poziomu struktury';
$BL['be_func_struct_del_struct'] = 'Usuń poziom struktury';
$BL['be_func_struct_del_sjsmsg'] = 'Czy naprawdę chcesz \nusunąć poziom struktury?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open'] = 'Otwórz';
$BL['be_func_struct_close'] = 'Zamknij';
$BL['be_func_struct_empty'] = 'Opróżnij';

// article.contenttype.inc.php
$BL['be_ctype_plaintext'] = 'Czysty Tekst';
$BL['be_ctype_html'] = 'HTML';
$BL['be_ctype_code'] = 'Kod programistyczny';
$BL['be_ctype_textimage'] = 'Tekst z Grafiką';
$BL['be_ctype_images'] = 'Grafika';
$BL['be_ctype_bulletlist'] = 'Lista (jako tabela)';
$BL['be_ctype_ullist'] = 'Lista';
$BL['be_ctype_link'] = 'Odnośnik &amp; e-mail';
$BL['be_ctype_linklist'] = 'Lista odnośników';
$BL['be_ctype_linkarticle'] = 'Odnośniki do artykułów';
$BL['be_ctype_multimedia'] = 'Multimedia';
$BL['be_ctype_filelist'] = 'Lista plików';
$BL['be_ctype_emailform'] = 'E-mail generator formularza e-mail';
$BL['be_ctype_newsletter'] = 'List z nowością';

// profile.create.inc.php
$BL['be_profile_create_success'] = 'Profil został pomyślne utworzony.';
$BL['be_profile_create_error'] = 'Błąd podczas tworzenia.';

// profile.update.inc.php
$BL['be_profile_update_success'] = 'Dane profilu zostały pomyślnie zaktualizowane.';
$BL['be_profile_update_error'] = 'Błąd podczas aktualizacji.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1'] = 'Użytkownik {VAL} jest nieprawidłowy';
$BL['be_profile_account_err2'] = 'Hasło jest za krótkie (tylko {VAL} znaków: musi mieć co najmniej 5 znaków)';
$BL['be_profile_account_err3'] = 'Powtórzenie hasła musi się zgadzać z hasłem';
$BL['be_profile_account_err4'] = 'Adres e-mail {VAL} jest nieprawidłowy';

// profile.data.tmpl.php
$BL['be_profile_data_title'] = 'Twoje dane osobiste';
$BL['be_profile_data_text'] = 'Dane osobiste są opcjonalne. Mogą one pomóc innym użytkownikom lub gościom witryny, dowiedzieć się więcej o Tobie.';
$BL['be_profile_label_title'] = 'Tytuł';
$BL['be_profile_label_firstname'] = 'Imię';
$BL['be_profile_label_name'] = 'Nazwisko';
$BL['be_profile_label_company'] = 'Firma';
$BL['be_profile_label_street'] = 'Ulica';
$BL['be_profile_label_city'] = 'Miejscowość';
$BL['be_profile_label_state'] = 'Województwo';
$BL['be_profile_label_zip'] = 'Kod pocztowy';
$BL['be_profile_label_country'] = 'Kraj';
$BL['be_profile_label_phone'] = 'Telefon';
$BL['be_profile_label_fax'] = 'Faks';
$BL['be_profile_label_cellphone'] = 'Tel.komórkowy';
$BL['be_profile_label_signature'] = 'Podpis';
$BL['be_profile_label_notes'] = 'Notatka';
$BL['be_profile_label_profession'] = 'Zawód';
$BL['be_profile_label_newsletter'] = 'Listy nowości';
$BL['be_profile_text_newsletter'] = 'Zgadzam się na otrzymywanie ogólnych listów nowości z serwisu.';
$BL['be_profile_label_public'] = 'publiczne';
$BL['be_profile_text_public'] = 'Każdy może widzieć moje dane osobiste.';
$BL['be_profile_label_button'] = 'Uaktualnij dane osobiste';

// profile.account.tmpl.php
$BL['be_profile_account_title'] = 'Twoje dane do logowania';
$BL['be_profile_account_text'] = 'Normalnie nie ma potrzeby zmieniać swojej nazwy użytkownika.<br />Za to powinieneś od czasu do czasu zmienić swoje hasło.';
$BL['be_profile_label_err'] = 'Proszę sprawdź';
$BL['be_profile_label_username'] = 'Nazwa użytkownika';
$BL['be_profile_label_newpass'] = 'Nowe hasło';
$BL['be_profile_label_repeatpass'] = 'Powtórz nowe hasło';
$BL['be_profile_label_email'] = 'E-mail';
$BL['be_profile_account_button'] = 'Uaktualnij';
$BL['be_profile_label_lang'] = 'Język';

// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title'] = 'Pliki wgrane przez ftp';
$BL['be_ftptakeover_mark'] = 'Zaznacz';
$BL['be_ftptakeover_available'] = 'Dostępne pliki';
$BL['be_ftptakeover_size'] = 'Rozmiar';
$BL['be_ftptakeover_nofile'] = 'W tej chwili nie ma dostępnych plików &#8211; musisz wgrać jakiś przez ftp';
$BL['be_ftptakeover_all'] = 'WSZYSTKIE';
$BL['be_ftptakeover_directory'] = 'Katalog';
$BL['be_ftptakeover_rootdir'] = 'Główny katalog';
$BL['be_ftptakeover_needed'] = 'Wymagane!!! (musisz wybrać przynajmniej jeden)';
$BL['be_ftptakeover_optional'] = 'Opcjonalne';
$BL['be_ftptakeover_keywords'] = 'Słowa kluczowe';
$BL['be_ftptakeover_additional'] = 'Dodatkowe';
$BL['be_ftptakeover_longinfo'] = 'Długie info';
$BL['be_ftptakeover_status'] = 'Status';
$BL['be_ftptakeover_active'] = 'Aktywny';
$BL['be_ftptakeover_public'] = 'Publiczne';
$BL['be_ftptakeover_createthumb'] = 'Utwórz miniaturkę';
$BL['be_ftptakeover_button'] = 'Odbierz wybrane pliki';

// files.reiter.tmpl.php
$BL['be_ftab_title'] = 'Centrum plików';
$BL['be_ftab_createnew'] = 'Utwórz nowy katalog w głównym';
$BL['be_ftab_paste'] = 'Wklej pliki ze schowka do głównego katalogu';
$BL['be_ftab_disablethumb'] = 'Wyłącz miniaturki w liście plików';
$BL['be_ftab_enablethumb'] = 'Włącz miniaturki w liście plików';
$BL['be_ftab_private'] = 'Prywatne&nbsp;pliki';
$BL['be_ftab_public'] = 'Publiczne&nbsp;pliki';
$BL['be_ftab_search'] = 'Szukaj';
$BL['be_ftab_trash'] = 'Kosz';
$BL['be_ftab_open'] = 'Otwórz wszystkie katalogi';
$BL['be_ftab_close'] = 'Zamknij wszystkie katalogi';
$BL['be_ftab_upload'] = 'Wgraj pliki do głównego katalogu';
$BL['be_ftab_filehelp'] = 'Otwórz plik pomocy';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir'] = 'Główny katalog';
$BL['be_fpriv_title'] = 'Utwórz nowy katalog';
$BL['be_fpriv_inside'] = 'Wewnątrz';
$BL['be_fpriv_error'] = 'Błąd: wypełnij pole nazwa dla katalogu';
$BL['be_fpriv_errordir'] = 'Błąd: katalog nie może być sam w sobie podfolderem';
$BL['be_fpriv_name'] = 'Nazwa';
$BL['be_fpriv_status'] = 'Status';
$BL['be_fpriv_button'] = 'Utwórz nowy katalog';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle'] = 'Edycja katalogu';
$BL['be_fpriv_newname'] = 'Nowa nazwa';
$BL['be_fpriv_updatebutton'] = 'Uaktualnij dane katalogu';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1'] = 'Wybierz pliki które chcesz wgrać';
$BL['be_fprivup_err2'] = 'Rozmiar plików do wgrania jest większy niż';
$BL['be_fprivup_err3'] = 'Błąd w trakcie wgrywania plików';
$BL['be_fprivup_err4'] = 'Błąd podczas tworzenia ktalogu użytkownika.';
$BL['be_fprivup_err5'] = 'miniaturki nie istnieją';
$BL['be_fprivup_err6'] = 'Proszę nie próbować ponownie - To jest błąd serwera! Skontakuj się ze swoim <a href="mailto:{VAL}">administratorem</a> tak szybko jak to możliwe!';
$BL['be_fprivup_err7'] = 'Ze względów bezpieczeństwa plik %s nie może być przeesłane.';
$BL['be_fprivup_err8'] = 'Plik bez rozszerzenia %s nie jest dozwolony do przesłania. Dopuszczalne rozszerzenia to: %s.';
$BL['be_fprivup_err9'] = 'Plik bez rozszerzenia nie jest dozwolony do przesłania. Dopuszczalne rozszerzenia to: %s.';
$BL['be_fprivup_title'] = 'Wgrywanie plików';
$BL['be_fprivup_button'] = 'Wgraj pliki';
$BL['be_fprivup_upload'] = 'Wgraj';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title'] = 'Edycja Informacji o pliku';
$BL['be_fprivedit_filename'] = 'Nazwa pliku';
$BL['be_fprivedit_created'] = 'utworzony';
$BL['be_fprivedit_dateformat'] = 'd.m.Y, H:i';
$BL['be_fprivedit_err1'] = 'Skoryguj nazwę pliku (ustaw z powrotem oryginalną)';
$BL['be_fprivedit_clockwise'] = 'Obróć miniaturę zgodnie z ruchem zegara [oryginalny plik +90&deg;]';
$BL['be_fprivedit_cclockwise'] = 'Obróć miniaturę nie zgodnie z ruchem zegara [oryginalny plik -90&deg;]';
$BL['be_fprivedit_button'] = 'Uaktualnij informację o pliku';
$BL['be_fprivedit_size'] = 'Rozmiar';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload'] = 'Wgraj pliki do katalogu';
$BL['be_fprivfunc_makenew'] = 'Utwórz nowy katalog wewnątrz';
$BL['be_fprivfunc_paste'] = 'Wklej plik ze schowka do katalogu';
$BL['be_fprivfunc_edit'] = 'Edytuj katalog';
$BL['be_fprivfunc_cactive'] = 'Przełącz aktywny/nieaktywny';
$BL['be_fprivfunc_cpublic'] = 'Przełącz publiczny/niepubliczny';
$BL['be_fprivfunc_deldir'] = 'Usuń katalog';
$BL['be_fprivfunc_jsdeldir'] = 'Czy na pewno chcesz \nusunąć katalog';
$BL['be_fprivfunc_notempty'] = 'katalog {VAL} nie jest pusty!';
$BL['be_fprivfunc_opendir'] = 'Otwórz katalog';
$BL['be_fprivfunc_closedir'] = 'Zamknij katalog';
$BL['be_fprivfunc_dlfile'] = 'Ściągnij plik';
$BL['be_fprivfunc_clipfile'] = 'Plik w schowku';
$BL['be_fprivfunc_cutfile'] = 'Wytnij';
$BL['be_fprivfunc_editfile'] = 'Edytuj informację o pliku';
$BL['be_fprivfunc_cactivefile'] = 'Przełącz aktywny/nieaktywny';
$BL['be_fprivfunc_cpublicfile'] = 'Przełącz publiczny/niepubliczny';
$BL['be_fprivfunc_movetrash'] = 'Przesuń do kosza';
$BL['be_fprivfunc_jsmovetrash1'] = 'Czy napewno chcesz ten plik';
$BL['be_fprivfunc_jsmovetrash2'] = 'Przesunąć do kosza?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders'] = 'Brak prywatnych plików lub folderów';

// files.public.list.tmpl.php
$BL['be_fpublic_user'] = 'Użytkownik';
$BL['be_fpublic_nofiles'] = 'Brak publicznych plików lub katalogów';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles'] = 'Kosz jest pusty';
$BL['be_ftrash_show'] = 'Pokaż prywatne pliki';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore'] = 'Czy chcesz przywrócić {VAL} \ni przenieść do prywatnej listy?';
$BL['be_ftrash_delete'] = 'Czy chcesz usunąć {VAL}?';
$BL['be_ftrash_undo'] = 'Przywróć (odwróć usuwanie)';
$BL['be_ftrash_delfinal'] = 'Ostateczne usunięcie';

// files.search.tmpl.php
$BL['be_fsearch_err1'] = 'Brak ciągu znaków do wyszukiwania.';
$BL['be_fsearch_title'] = 'Szukaj plików';
$BL['be_fsearch_infotext'] = 'To jest prosta wyszukiwarka informacji o plikach. Przeszukuje ona słowa kluczowe,<br />nazwy i długie info o plikach.Nie wspiera znaków specjalnych. <br />Wybierz I/LUB oraz typy plików: prywatne/publiczne.';
$BL['be_fsearch_nonfound'] = 'nie znaleziono plików dla twojego zapytania. Zmień swoje zapytanie!';
$BL['be_fsearch_fillin'] = 'Proszę wypełnij pole wyszukiwarki ciągiem znaków do wyszukania.';
$BL['be_fsearch_searchlabel'] = 'Szukaj ';
$BL['be_fsearch_startsearch'] = 'Rozpocznij wyszukiwanie';
$BL['be_fsearch_and'] = 'I';
$BL['be_fsearch_or'] = 'LUB';
$BL['be_fsearch_all'] = 'wszystkie pliki';
$BL['be_fsearch_personal'] = 'prywatne';
$BL['be_fsearch_public'] = 'publiczne';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title'] = 'Wewnętrzny Chat';
$BL['be_chat_info'] = 'Tutaj możesz porozumieć się z innymi użytkownikami swojego systemu phpwcms. To medium jest przeznaczone głównie do porozumiewania się w czasie rzeczywistym, ale możesz również zostawiać poprzez niego wiadomości dla innych.';
$BL['be_chat_start'] = 'Kliknij tutaj aby uruchomić chat';
$BL['be_chat_lines'] = 'Linie chata';

// message.center.tmpl.php
$BL['be_msg_title'] = 'Centrum wiadomości';
$BL['be_msg_new'] = 'nowa';
$BL['be_msg_old'] = 'stara';
$BL['be_msg_senttop'] = 'wysłana';
$BL['be_msg_del'] = 'usunięta';
$BL['be_msg_from'] = 'od';
$BL['be_msg_subject'] = 'Temat';
$BL['be_msg_date'] = 'Data/czas';
$BL['be_msg_close'] = 'Zamknij wiadomość';
$BL['be_msg_create'] = 'Utwórz nową wiadomość';
$BL['be_msg_reply'] = 'Odpowiedz na wiadomość';
$BL['be_msg_move'] = 'Przesuń tę wiadomość do kosza';
$BL['be_msg_unread'] = 'nieprzeczytana lub nowa wiadomość';
$BL['be_msg_lastread'] = 'ostatnie {VAL} przeczytanych wiadomości';
$BL['be_msg_lastsent'] = 'ostatnie {VAL} wysłanych wiadomości';
$BL['be_msg_marked'] = 'Wiadomości oznaczone do usunięcia (kosz)';
$BL['be_msg_nomsg'] = 'Brak wiadomości w katalogu';

// message.send.tmpl.php
$BL['be_msg_RE'] = 'ODP';
$BL['be_msg_by'] = 'wysłana przez';
$BL['be_msg_on'] = 'w dniu';
$BL['be_msg_msg'] = 'Wiadomość';
$BL['be_msg_err1'] = 'Zapomniałeś udtsawić odbiorcę...';
$BL['be_msg_err2'] = 'Wypełnij pole tytułu (odbiorcy będzie łatwiej czytać Twoją wiadomość)';
$BL['be_msg_err3'] = 'nie ma sensu wysyłać wiadomości bez treści ;-)';
$BL['be_msg_sent'] = 'Nowa Wiadomość została wysłana!';
$BL['be_msg_fwd'] = 'Zostaniesz przekierowany do centrum wiadomości lub';
$BL['be_msg_newmsgtitle'] = 'napisz nową wiadomość';
$BL['be_msg_err'] = 'błąd podczas wysyłania wiadomości';
$BL['be_msg_sendto'] = 'wyślij wiadomość do';
$BL['be_msg_available'] = 'lista dostęnych odbiorców';
$BL['be_msg_all'] = 'Wyślij widomość do wszystkich wybranych Odbiorców';

// message.subscription.tmpl.php
$BL['be_newsletter_title'] = 'Subskrypcja wiadomości o nowościach';
$BL['be_newsletter_titleedit'] = 'Edytuj subskrypcję';
$BL['be_newsletter_new'] = 'Utwórz nowy/ą';
$BL['be_newsletter_add'] = 'Dodaj&nbsp;wiadomość&nbsp;do&nbsp;subskrypcji';
$BL['be_newsletter_name'] = 'Nazwa';
$BL['be_newsletter_info'] = 'Info';
$BL['be_newsletter_button_save'] = 'Zapisz subskrypcję';
$BL['be_newsletter_button_cancel'] = 'Anuluj';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1'] = 'Nazwa użytkownika jest błędna, wybierz inną';
$BL['be_admin_usr_err2'] = 'Nazwa użytkownika nie może być pusta';
$BL['be_admin_usr_err3'] = 'Hasło użytkownika nie może być puste';
$BL['be_admin_usr_err4'] = "Adres email jest nieprawidłowy";
$BL['be_admin_usr_err'] = 'Błąd';
$BL['be_admin_usr_mailsubject'] = 'Witajcie w systemie zarządzania phpwcms';
$BL['be_admin_usr_mailbody'] = "WITAJ W SYSTEMIE ZARZĄDZANIA PHPWCMS\n\n    użytkownik: {LOGIN}\n    hasło: {PASSWORD}\n\n\nPrzez tą stronę możesz się zalogować: {SITE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title'] = 'Dodaj nowe konto użytkownika';
$BL['be_admin_usr_realname'] = 'Prawdziwe imię';
$BL['be_admin_usr_setactive'] = 'Ustaw konto jako aktywne';
$BL['be_admin_usr_iflogin'] = 'Jeśli włączone, użytkownik może się logować';
$BL['be_admin_usr_isadmin'] = 'Użytkownik jest administratorem';
$BL['be_admin_usr_ifadmin'] = 'Jeśli włączone, użytkownik ma prawa administratora';
$BL['be_admin_usr_verify'] = 'Weryfikacja';
$BL['be_admin_usr_sendemail'] = 'Wyślij email do nowego użytkownika z informacją o jego koncie';
$BL['be_admin_usr_button'] = 'Zapisz dane użytkownika';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle'] = 'Edycja konta użytkownika';
$BL['be_admin_usr_emailsubject'] = 'Dane konta - phpwcms - zostały zmienione';
$BL['be_admin_usr_emailbody'] = "KONTO UŻYTKOWNIKA W PHPWCMS ZOSTAŁO ZMIENIONE \n\n    Nazwa użytkownika: {LOGIN}\n    Hasło: {PASSWORD}\n\n\nPrzez tą stronę możesz się zalogować:: {SITE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange'] = '[NIE ZMIENIONO - PODAJ PRAWIDŁOW HASŁO]';
$BL['be_admin_usr_ebutton'] = 'Uaktualnij dane użytkownika';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle'] = 'Lista użytkowników systemu';
$BL['be_admin_usr_ldel'] = 'UWAGA!&#13;Wybrana akcja spowoduje usunięcie użytkownika!';
$BL['be_admin_usr_create'] = 'Utwórz nowego użytkownika';
$BL['be_admin_usr_editusr'] = 'Edytuj użytkownika';

// admin.structform.tmpl.php
$BL['be_admin_struct_title'] = 'Struktura witryny';
$BL['be_admin_struct_child'] = '(potomek)';
$BL['be_admin_struct_index'] = 'index (początek witryny)';
$BL['be_admin_struct_cat'] = 'Tytuł poziomu';
$BL['be_admin_struct_alt'] = 'tytuł alternatywny kategorii';
$BL['be_admin_struct_hide1'] = 'ukryj';
$BL['be_admin_struct_hide2'] = 'ten&nbsp;poziom&nbsp;w&nbsp;menu';
$BL['be_admin_struct_info'] = 'Informacja o poziomie';
$BL['be_admin_struct_template'] = 'Szablon';
$BL['be_admin_struct_alias'] = 'Alias poziomu';
$BL['be_admin_struct_visible'] = 'Widoczny';
$BL['be_admin_struct_button'] = 'Zapisz dane poziomu';
$BL['be_admin_struct_close'] = 'Zamknij';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title'] = 'Kategorie plików';
$BL['be_admin_fcat_err'] = 'Nazwa kategorii jest pusta!';
$BL['be_admin_fcat_name'] = 'Nazwa kategorii';
$BL['be_admin_fcat_needed'] = 'należy używać';
$BL['be_admin_fcat_button1'] = 'Uaktualnij';
$BL['be_admin_fcat_button2'] = 'Utwórz';
$BL['be_admin_fcat_delmsg'] = 'Czy na pewno chcesz\nskasować rozszerzenie plików?';
$BL['be_admin_fcat_fcat'] = 'Kategoria plików';
$BL['be_admin_fcat_err1'] = 'Nazwa rozszerzenia jest pusta!';
$BL['be_admin_fcat_fkeyname'] = 'Nazwa&nbsp;rozszerzenia';
$BL['be_admin_fcat_exit'] = 'Anuluj';
$BL['be_admin_fcat_addkey'] = 'Dodaj nowe rozszerzenie';
$BL['be_admin_fcat_editcat'] = 'Edytuj kategorię';
$BL['be_admin_fcat_delcatmsg'] = 'Czy na pewno chcesz\nusunąć kategorię plików?';
$BL['be_admin_fcat_delcat'] = 'Usuń kategorię plików';
$BL['be_admin_fcat_delkey'] = 'Usuń rozszerzenie plików';
$BL['be_admin_fcat_editkey'] = 'Edytuj rozszerzenie';
$BL['be_admin_fcat_addcat'] = 'Utwórz nową kategorię plików';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title'] = 'Ustawienia witryny: układ witryny';
$BL['be_admin_page_align'] = 'Wyrównanie witryny';
$BL['be_admin_page_align_left'] = 'Standardowe wyrównanie do lewej całej witryny';
$BL['be_admin_page_align_center'] = 'Wyśrodkowanie całej witrynę';
$BL['be_admin_page_align_right'] = 'Wyrównanie do prawej całej witryny';
$BL['be_admin_page_margin'] = 'Margines';
$BL['be_admin_page_top'] = 'góra';
$BL['be_admin_page_bottom'] = 'dół';
$BL['be_admin_page_left'] = 'Lewy';
$BL['be_admin_page_right'] = 'Prawy';
$BL['be_admin_page_bg'] = 'Tło';
$BL['be_admin_page_color'] = 'kolor';
$BL['be_admin_page_height'] = 'wysokość&nbsp;';
$BL['be_admin_page_width'] = 'szerokość';
$BL['be_admin_page_main'] = 'Główny';
$BL['be_admin_page_leftspace'] = 'Lewy odstęp';
$BL['be_admin_page_rightspace'] = 'Prawy odstęp';
$BL['be_admin_page_class'] = 'Klasa';
$BL['be_admin_page_image'] = 'Obraz';
$BL['be_admin_page_text'] = 'Tekst';
$BL['be_admin_page_link'] = 'Odnośn.';
$BL['be_admin_page_js'] = 'Javascript';
$BL['be_admin_page_visited'] = 'odwiedz.';
$BL['be_admin_page_pagetitle'] = 'Tytuł&nbsp;witryny';
$BL['be_admin_page_addtotitle'] = 'Dodaj&nbsp;do&nbsp;tytułu';
$BL['be_admin_page_category'] = 'Nazwę poziomu';
$BL['be_admin_page_articlename'] = 'Tytuł&nbsp;artykułu';
$BL['be_admin_page_blocks'] = 'Bloki';
$BL['be_admin_page_allblocks'] = 'Wszystkie bloki';
$BL['be_admin_page_col1'] = 'Bloki w 3 kolumnach';
$BL['be_admin_page_col2'] = 'Bloki w 2 kolumnach (główna kolumna z prawej, menu z lewej)';
$BL['be_admin_page_col3'] = 'Bloki w 2 kolumnach (główna kolumna z lewej, menu z prawej)';
$BL['be_admin_page_col4'] = 'Bloki w 1 kolumnie';
$BL['be_admin_page_header'] = 'Nagłówek';
$BL['be_admin_page_footer'] = 'Stopka';
$BL['be_admin_page_topspace'] = 'Górny&nbsp;odstęp';
$BL['be_admin_page_bottomspace'] = 'Dolny&nbsp;odstęp';
$BL['be_admin_page_button'] = 'Zapisz układ strony';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title'] = 'Ustawienie witryny: styl css';
$BL['be_admin_css_css'] = 'CSS';
$BL['be_admin_css_button'] = 'Zapisz styl css';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title'] = 'Ustawienia witryny: szablony';
$BL['be_admin_tmpl_default'] = 'Domyślny';
$BL['be_admin_tmpl_add'] = 'Dodaj&nbsp;szablon';
$BL['be_admin_tmpl_edit'] = 'Edycja szablonu';
$BL['be_admin_tmpl_new'] = 'Utwórz nowy';
$BL['be_admin_tmpl_css'] = 'Plik css';
$BL['be_admin_tmpl_head'] = 'Nagłówek&nbsp; html';
$BL['be_admin_tmpl_js'] = 'Skrypt przy&nbsp; otwarciu';
$BL['be_admin_tmpl_error'] = 'Błąd';
$BL['be_admin_tmpl_button'] = 'Zapisz szablon';
$BL['be_admin_tmpl_name'] = 'Nazwa';

// article.structlist.tmpl.php
$BL['be_article_title'] = 'Struktura witryny i lista artykułów';

// article.new.tmpl.php
$BL['be_article_err1'] = 'Tytuł tego artykułu jest pusty';
$BL['be_article_err2'] = 'Data rozpoczęcia wyświetlania jest źle podana - ustaw na dziś';
$BL['be_article_err3'] = 'Data zkończenia wyświetlania jest źle podana - ustaw na dziś';
$BL['be_article_title1'] = 'Podstawowe informacje artykułu';
$BL['be_article_cat'] = 'Poziom';
$BL['be_article_atitle'] = 'Tytuł artykułu';
$BL['be_article_asubtitle'] = 'Podtytuł';
$BL['be_article_abegin'] = 'Rozp.';
$BL['be_article_aend'] = 'Zakoń.';
$BL['be_article_aredirect'] = 'Przekieruj do';
$BL['be_article_akeywords'] = 'Słowa <br/>kluczowe';
$BL['be_article_asummary'] = 'Podsumowanie';
$BL['be_article_abutton'] = 'Utwórz nowy artykuł';

// article.editcontent.inc.php
$BL['be_article_err4'] = 'Data zakończenia wyświetlania jest podana. Ale-ustaw na tydzień od dziś';

// article.editsummary.tmpl.php
$BL['be_article_estitle'] = 'Edycja podstawowych danych artykułu';
$BL['be_article_eslastedit'] = 'Aktualizowany';
$BL['be_article_esnoupdate'] = 'Formularz nie zaktualizowany';
$BL['be_article_esbutton'] = 'Zaktualizuj dane artykułu';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title'] = 'Treść artykułu';
$BL['be_article_cnt_type'] = 'Typ treści';
$BL['be_article_cnt_space'] = 'Odstęp';
$BL['be_article_cnt_before'] = 'przed';
$BL['be_article_cnt_after'] = 'po';
$BL['be_article_cnt_top'] = 'na górze';
$BL['be_article_cnt_toplink'] = 'Wyświetl odnośnik : na górę';
$BL['be_article_cnt_anchor'] = 'Kotwica';
$BL['be_article_cnt_ctitle'] = 'Tytuł treści';
$BL['be_article_cnt_back'] = 'Pełne dane artykułu';
$BL['be_article_cnt_button1'] = 'Zaktualizuj';
$BL['be_article_cnt_button2'] = 'Utwórz';
$BL['be_article_cnt_button3'] = 'Zapisz i zamknij';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle'] = 'Dane artykułu';
$BL['be_article_cnt_ledit'] = 'Edytuj artykuł';
$BL['be_article_cnt_lvisible'] = 'Przełącz widoczny/niewidoczny';
$BL['be_article_cnt_ldel'] = 'Usuń artykuł';
$BL['be_article_cnt_ldeljs'] = 'Czy na pewno usunąć artykuł?';
$BL['be_article_cnt_redirect'] = 'Przekierowanie';
$BL['be_article_cnt_edited'] = 'Edytowane przez';
$BL['be_article_cnt_start'] = 'Data rozp.';
$BL['be_article_cnt_end'] = 'Data zakoń.';
$BL['be_article_cnt_add'] = 'Dodaj';
$BL['be_article_cnt_addtitle'] = 'Dodaj nową treść';
$BL['be_article_cnt_up'] = 'Przesuń w górę treść';
$BL['be_article_cnt_down'] = 'Przesuń w dół treść';
$BL['be_article_cnt_edit'] = 'Edytuj treść';
$BL['be_article_cnt_delpart'] = 'Usuń treść z artykułu';
$BL['be_article_cnt_delpartjs'] = 'Czy na pewno usunąć treść z artykułu?';
$BL['be_article_cnt_center'] = 'Centrum artykułów';

// content forms
$BL['be_cnt_plaintext'] = 'Czysty tekst';
$BL['be_cnt_htmltext'] = 'Tekst w HTML';
$BL['be_cnt_image'] = 'Obraz';
$BL['be_cnt_position'] = 'Pozycja';
$BL['be_cnt_pos0'] = 'Ponad, z lewej';
$BL['be_cnt_pos1'] = 'Ponad, na środku';
$BL['be_cnt_pos2'] = 'Ponad, z prawej';
$BL['be_cnt_pos3'] = 'Pod, z lewej';
$BL['be_cnt_pos4'] = 'Pod, na środku';
$BL['be_cnt_pos5'] = 'Pod, z prawej';
$BL['be_cnt_pos6'] = 'W tekście, po lewej';
$BL['be_cnt_pos7'] = 'W tekście, po prawej';
$BL['be_cnt_pos0i'] = 'Wyrównaj obraz ponad i z lewej strony tesktu';
$BL['be_cnt_pos1i'] = 'Wyrównaj obraz ponad i pośrodku tesktu';
$BL['be_cnt_pos2i'] = 'Wyrównaj obraz ponad i z prawej strony tekstu';
$BL['be_cnt_pos3i'] = 'Wyrównaj obraz pod i z lewej strony tekstu';
$BL['be_cnt_pos4i'] = 'Wyrównaj obraz pod i pośrodku tekstu';
$BL['be_cnt_pos5i'] = 'Wyrównaj obraz pod i z prawej strony tekstu';
$BL['be_cnt_pos6i'] = 'Umieść obraz wewnątrz tekstu i wyrównaj do lewej';
$BL['be_cnt_pos7i'] = 'Umieść obraz wewnątrz tesktu i wyrównaj do prawej';
$BL['be_cnt_maxw'] = 'maks.&nbsp;Szer.';
$BL['be_cnt_maxh'] = 'maks.&nbsp;Wys.';
$BL['be_cnt_enlarge'] = 'włącz&nbsp;powiększanie';
$BL['be_cnt_caption'] = 'Podpis';
$BL['be_cnt_subject'] = 'Tytuł&nbsp;<br/>wiadomości';
$BL['be_cnt_recipient'] = 'Odbiorca';
$BL['be_cnt_buttontext'] = 'Tekst przycisku';
$BL['be_cnt_sendas'] = 'Wyślij jako';
$BL['be_cnt_text'] = 'Tekst';
$BL['be_cnt_html'] = 'HTML';
$BL['be_cnt_formfields'] = 'Pola&nbsp;<br/> formularza';
$BL['be_cnt_code'] = 'Kod programistyczny';
$BL['be_cnt_infotext'] = 'Tekst&nbsp;informacji';
$BL['be_cnt_subscription'] = 'Subskrypcja/Kanał';
$BL['be_cnt_labelemail'] = 'Etykieta&nbsp;email';
$BL['be_cnt_tablealign'] = 'Wyrównanie&nbsp;tabeli';
$BL['be_cnt_labelname'] = 'Nazwa&nbsp;etykiety';
$BL['be_cnt_labelsubsc'] = 'Etykieta&nbspsubskr.;';
$BL['be_cnt_allsubsc'] = 'Wszyscy&nbsp;subskr.';
$BL['be_cnt_default'] = 'Domyślny';
$BL['be_cnt_left'] = 'lewo';
$BL['be_cnt_center'] = 'środek';
$BL['be_cnt_right'] = 'prawo';
$BL['be_cnt_successtext'] = 'Tekst&nbsp;sukcesu';
$BL['be_cnt_regmail'] = 'Zarejestr.email';
$BL['be_cnt_logoffmail'] = 'Wyłącz.email';
$BL['be_cnt_changemail'] = 'Zmień.email';
$BL['be_cnt_openimagebrowser'] = 'Otwórz przeglądarkę grafiki';
$BL['be_cnt_openfilebrowser'] = 'Otwórz przeglądarkę plików';
$BL['be_cnt_sortup'] = 'do góry';
$BL['be_cnt_sortdown'] = 'do dołu';
$BL['be_cnt_delimage'] = 'usuń wybrane grafiki';
$BL['be_cnt_delfile'] = 'usuń wybrane pliki';
$BL['be_cnt_delmedia'] = 'usuń wybrane multimedia';
$BL['be_cnt_column'] = 'kolumna(y)';
$BL['be_cnt_imagespace'] = 'odstęp&nbsp;obrazka';
$BL['be_cnt_directlink'] = 'Bezpośredni Odnośnik';
$BL['be_cnt_target'] = 'Cel';
$BL['be_cnt_target1'] = 'w nowym oknie';
$BL['be_cnt_target2'] = 'w główenj ramce okna';
$BL['be_cnt_target3'] = 'w tym samym oknie';
$BL['be_cnt_target4'] = 'w tej samej ramce lub oknie';
$BL['be_cnt_bullet'] = 'Lista (jako tablica)';
$BL['be_cnt_ullist'] = 'Lista';
$BL['be_cnt_ullist_desc'] = '~ = 1szy poziom, &nbsp; ~~ = 2gi poziom, &nbsp; itd.';
$BL['be_cnt_linklist'] = 'Lista odnośników';
$BL['be_cnt_plainhtml'] = 'czysty HTML';
$BL['be_cnt_files'] = 'Pliki';
$BL['be_cnt_description'] = 'Opis';
$BL['be_cnt_linkarticle'] = 'Odnośniki do&nbsp;<br/>artykułów';
$BL['be_cnt_articles'] = 'Artykuły';
$BL['be_cnt_movearticleto'] = 'Przesuń wybrane Artykuły do Listy Artykułów';
$BL['be_cnt_removearticleto'] = 'usuń wybrane artykuły z listy artykułów';
$BL['be_cnt_mediatype'] = 'Typ medium';
$BL['be_cnt_control'] = 'Kontrola';
$BL['be_cnt_showcontrol'] = 'Pokaż pasek kontroli';
$BL['be_cnt_autoplay'] = 'Autoodtwarzanie';
$BL['be_cnt_source'] = 'Źródło';
$BL['be_cnt_internal'] = 'wewnętrzne';
$BL['be_cnt_openmediabrowser'] = 'Otwórz przeglądarkę multimediów';
$BL['be_cnt_external'] = 'zewnętrzne';
$BL['be_cnt_mediapos0'] = 'po lewej (domyślnie)';
$BL['be_cnt_mediapos1'] = 'na środku';
$BL['be_cnt_mediapos2'] = 'poprawej';
$BL['be_cnt_mediapos3'] = 'W tekście, po lewej';
$BL['be_cnt_mediapos4'] = 'W tekście, po prawej';
$BL['be_cnt_mediapos0i'] = 'Wyrównaj media ponad i do lewej strony tekstu';
$BL['be_cnt_mediapos1i'] = 'Wyrównaj media ponad i pośrodku tesktu';
$BL['be_cnt_mediapos2i'] = 'Wyrównaj media ponad i do prawej strony tekstu';
$BL['be_cnt_mediapos3i'] = 'Umieść media wewnątrz tesktu i wyrównaj do lewej';
$BL['be_cnt_mediapos4i'] = 'Umieść media wewnątrz tesktu i wyrównaj do prawej';
$BL['be_cnt_setsize'] = 'Ustaw rozmiar';
$BL['be_cnt_set1'] = 'Ustaw rozmiar na 160x120px';
$BL['be_cnt_set2'] = 'Ustaw rozmiar na 240x180px';
$BL['be_cnt_set3'] = 'Ustaw rozmiar na 320x240px';
$BL['be_cnt_set4'] = 'Ustaw rozmiar na 480x360px';
$BL['be_cnt_set5'] = 'Wyczyść wysokość i szerokość';

// added: 28-12-2003
$BL['be_admin_page_add'] = 'Utwórz nowy układ strony';
$BL['be_admin_page_name'] = 'Nazwa układu';
$BL['be_admin_page_edit'] = 'Edytuj układ';
$BL['be_admin_page_render'] = 'Renderowanie';
$BL['be_admin_page_table'] = 'Tabela';
$BL['be_admin_page_div'] = 'CSS DIV';
$BL['be_admin_page_custom'] = 'własne';
$BL['be_admin_page_custominfo'] = 'z Szablonu Głównego Bloku';
$BL['be_admin_tmpl_layout'] = 'Układ strony';
$BL['be_admin_tmpl_nolayout'] = 'Brak układu!';

// added: 31-12-2003
$BL['be_ctype_search'] = 'wyszukiwarka';
$BL['be_cnt_results'] = 'rezultatów';
$BL['be_cnt_results_per_page'] = 'na&nbsp;stronę (jeśli puste, pokazuje wszystkie)';
$BL['be_cnt_opennewwin'] = 'W nowym oknie';
$BL['be_cnt_searchlabeltext'] = 'Wstaw swoje komunikaty, które pokazują się gdy jest więcej znalezionych artykułów niż mieści jedna strona.';
$BL['be_cnt_input'] = 'Wybierz';
$BL['be_cnt_style'] = 'Styl';
$BL['be_cnt_result'] = 'Rezultat';
$BL['be_cnt_next'] = 'Następny';
$BL['be_cnt_previous'] = 'Poprzedni';
$BL['be_cnt_align'] = 'Wyrównanie';
$BL['be_cnt_searchformtext'] = 'Wstaw swoje komunikaty gdy użytkownik otworzy strone wyszukiwarki lub gdy nie ma rezultatów.';
$BL['be_cnt_intro'] = 'Nagłówek';
$BL['be_cnt_noresult'] = 'Brak&nbsp; rezultatów';
$BL['be_cnt_search_default_type'] = 'Domyślny typ wyszukiwania';

// added: 02-01-2004
$BL['be_admin_page_disable'] = 'wyłączone';

// added: 09-01-2004
$BL['be_article_articleowner'] = 'Właściciel artykułu';
$BL['be_article_adminuser'] = 'Administrator';
$BL['be_article_username'] = 'Autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg'] = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly'] = 'widoczne tylko dla zalogowanych użytkowników';
$BL['be_admin_struct_status'] = 'Status Dostępności w menu';

// added: 15-02-2004
$BL['be_ctype_articlemenu'] = 'Menu artykułów';
$BL['be_cnt_sitelevel'] = 'Poziom struktury';
$BL['be_cnt_sitecurrent'] = 'Obecny poziom struktury';

// added: 24-03-2004
$BL['be_subnav_admin_starttext'] = 'Tekst na stronie startowej';
$BL['be_ctype_ecard'] = 'E-kartka elektroniczna';
$BL['be_ctype_blog'] = 'Blog';
$BL['be_cnt_ecardtext'] = 'Tytuł/kartka elektroniczna';
$BL['be_cnt_ecardtmpl'] = 'Szablon wiadomości';
$BL['be_cnt_ecard_image'] = 'Obrazek kartki';
$BL['be_cnt_ecard_title'] = 'Tytuł kartki';
$BL['be_cnt_alignment'] = 'Wyrównanie';
$BL['be_cnt_ecardform'] = 'Szablon formularza';
$BL['be_cnt_ecardform_err'] = 'Wszystkie pola oznaczone * są wymagalne';
$BL['be_cnt_ecardform_sender'] = 'Nadawca';
$BL['be_cnt_ecardform_recipient'] = 'Obiorca';
$BL['be_cnt_ecardform_name'] = 'Nazwa';
$BL['be_cnt_ecardform_msgtext'] = 'Twoja wiadomość do odbiorcy';
$BL['be_cnt_ecardform_button'] = 'Wyślij kartkę';
$BL['be_cnt_ecardsend'] = 'Szablon wysyłki';

// added: 28-03-2004
$BL['be_admin_startup_title'] = 'Domyślny tekst na stronie startowej systemu phpwcms';
$BL['be_admin_startup_text'] = 'Treść tekstu';
$BL['be_admin_startup_button'] = 'Zapisz tekst';

// added: 17-04-2004
$BL['be_ctype_guestbook'] = 'Księga gości/komentarze';
$BL['be_cnt_guestbook_listing'] = 'Pokazuj';
$BL['be_cnt_guestbook_listing_all'] = 'Pokaż&nbsp;wszystkie&nbsp;wpisy';
$BL['be_cnt_guestbook_list'] = 'Wpisów';
$BL['be_cnt_guestbook_perpage'] = 'Na&nbsp;stronę';
$BL['be_cnt_guestbook_form'] = 'Formularz';
$BL['be_cnt_guestbook_signed'] = 'Podpisane';
$BL['be_cnt_guestbook_nav'] = 'Nawig.';
$BL['be_cnt_guestbook_before'] = 'Przed';
$BL['be_cnt_guestbook_after'] = 'Po';
$BL['be_cnt_guestbook_entry'] = 'Wpis';
$BL['be_cnt_guestbook_edit'] = 'Edycja';
$BL['be_cnt_ecardform_selector'] = 'Wybierz';
$BL['be_cnt_ecardform_radiobutton'] = 'Pole wyboru';
$BL['be_cnt_ecardform_javascript'] = 'Funkcjonalność JavaScript';
$BL['be_cnt_ecardform_over'] = 'onMouseOver';
$BL['be_cnt_ecardform_click'] = 'onClick';
$BL['be_cnt_ecardform_out'] = 'onMouseOut';
$BL['be_admin_struct_topcount'] = 'Ilość artykułów na górze';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend'] = 'Nowości';
$BL['be_newsletter_addnl'] = 'Dodaj nowość';
$BL['be_newsletter_titleeditnl'] = 'Edycja nowości';
$BL['be_newsletter_newnl'] = 'Utwórz nową';
$BL['be_newsletter_button_savenl'] = 'Zapisz nowość';
$BL['be_newsletter_fromname'] = 'Od nazwa';
$BL['be_newsletter_fromemail'] = 'E-mail od';
$BL['be_newsletter_replyto'] = 'E-mail odp';
$BL['be_newsletter_changed'] = 'Ostanio&nbsp; zmieniono';
$BL['be_newsletter_placeholder'] = 'Umieść';
$BL['be_newsletter_htmlpart'] = 'Treść nowości w HTML';
$BL['be_newsletter_textpart'] = 'Treść nowości tekstowa';
$BL['be_newsletter_allsubscriptions'] = 'Wszystkie subskrypcje';
$BL['be_newsletter_verifypage'] = 'Sprawdź odnośnik';
$BL['be_newsletter_open'] = 'pola wprowadzania treści nowości';
$BL['be_newsletter_open1'] = '(kliknij żeby otworzyć edytor)';
$BL['be_newsletter_sendnow'] = 'wyślij teraz';
$BL['be_newsletter_attention'] = '<strong style="color:#CC3300;">Uwaga!</strong> Wysyłanie wielu nowości naraz do dużej ilość odbiorców jest niebezpieczne. Odbiorcy powinni być zweryfikowani inaczej Twoja wysyłka może zostać potraktowana jako SPAM. .Pomyśl dwa razy zanim wyślesz nowości. Sprawdź nowości poprzez wysyłkę testu.';
$BL['be_newsletter_attention1'] = 'Jeśli dokonałeś zmian w nowości, zapisz ją najpierw inaczej nie zostanie ona użyta.';
$BL['be_newsletter_testemail'] = 'Testuj email';
$BL['be_newsletter_sendnlbutton'] = 'Wyślij nowość';
$BL['be_newsletter_sendprocess'] = 'Proces wysyłania';
$BL['be_newsletter_attention2'] = '<strong style="color:#CC3300;">Uwaga!</strong> Proszę nie przerywać procesu wysyłania. Inaczej może zaistnieć możliwość wysłania tej samej nowości dwa razy do tego samego odbiorcy.';
$BL['be_newsletter_testerror'] = '<span style="color:#CC3300;font-size:11px;">adres testowy <strong>###TEST###</strong> jest nie poprawny!<br />&nbsp;<br />Proszę spróbuj ponownie!';
$BL['be_newsletter_to'] = 'Odbiorcy';
$BL['be_newsletter_ready'] = 'Wysyłanie nowości: zakończono';
$BL['be_newsletter_readyfailed'] = 'Nie udało się wysłać nowości do';
$BL['be_subnav_msg_subscribers'] = 'Subskrybenci nowości';

// added: 20-04-2004
$BL['be_ctype_sitemap'] = 'Mapa witryny';
$BL['be_cnt_sitemap_catimage'] = 'Ikona poziomu';
$BL['be_cnt_sitemap_articleimage'] = 'Ikona artykułu';
$BL['be_cnt_sitemap_display'] = 'Wyświetl';
$BL['be_cnt_sitemap_structuronly'] = 'Tylko Poziomy Struktury';
$BL['be_cnt_sitemap_structurarticle'] = 'Poziomy Ptruktury i Artykuły';
$BL['be_cnt_sitemap_catclass'] = 'Klasa CSS Poziomu';
$BL['be_cnt_sitemap_articleclass'] = 'Klasa CSS Artykułu';
$BL['be_cnt_sitemap_count'] = 'Licznik';
$BL['be_cnt_sitemap_classcount'] = 'dodaj do nazwy klasy';
$BL['be_cnt_sitemap_noclasscount'] = 'nie dodawaj do nazwy klasy CSS';
$BL['be_cnt_sitemap_without_parent'] = 'bez poziomu początkowego';

// added: 23-04-2004
$BL['be_ctype_bid'] = 'Oferta';
$BL['be_cnt_bid_bidtext'] = 'Treść oferty';
$BL['be_cnt_bid_sendtext'] = 'Tekst do&nbsp; wysłania';
$BL['be_cnt_bid_verifiedtext'] = 'Tekst&nbsp; weryfikacji';
$BL['be_cnt_bid_errortext'] = 'Oferta&nbsp; usunięta';
$BL['be_cnt_bid_verifyemail'] = 'Weryfikacja&nbsp; emaila';
$BL['be_cnt_bid_startbid'] = 'Rozpocznij od';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd'] = 'zwiększ&nbsp;o';

// added: 10-05-2004
$BL['be_ctype_pages'] = 'Zewnętrzna treść';
$BL['be_cnt_pages_select'] = 'Wybierz plik';
$BL['be_cnt_pages_fromfile'] = 'Plik ze struktury';
$BL['be_cnt_pages_manually'] = 'Własna ścieżka/plik lub adres URL';
$BL['be_cnt_pages_cust'] = 'Plik/URL';
$BL['be_cnt_pages_from'] = 'Źródło';

// added: 24-05-2004
$BL['be_ctype_reference'] = 'Przewijalna grafika';
$BL['be_cnt_reference_basis'] = 'Wyrównanie';
$BL['be_cnt_reference_horizontal'] = 'poziomo';
$BL['be_cnt_reference_vertical'] = 'pionowo';
$BL['be_cnt_reference_aligntext'] = 'Małe obrazki';
$BL['be_cnt_reference_largetext'] = 'Duże obrazki';
$BL['be_cnt_reference_zoom'] = 'Powiększenie';
$BL['be_cnt_reference_middle'] = 'Pośrodku';
$BL['be_cnt_reference_border'] = 'Ramka';
$BL['be_cnt_reference_block'] = 'Blok sz x w';

// added: 31-05-2004
$BL['be_article_rendering'] = 'Wyświetlanie';
$BL['be_article_nosummary'] = 'Nie wyświetlaj podsumowania razem z całością artykułu';
$BL['be_article_forlist'] = 'Wylistuj artykuł';
$BL['be_article_forfull'] = 'Wyświetl cały artykuł';

// added: 08-07-2004
$BL["setup_dir_exists"] = '<strong>Uwaga!</strong> Katalog &quot;SETUP&quot; nadal istnieje! Skasuj ten katalog - może być on przyczyną potencjalnych problemów z bezpieczeństwem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned'] = 'Zabronione&nbsp; słowa';
$BL['be_cnt_guestbook_flooding'] = 'Blokady';
$BL['be_cnt_guestbook_setcookie'] = 'Ustaw cookie';
$BL['be_cnt_guestbook_allowed'] = 'Zezwól ponownie po';
$BL['be_cnt_guestbook_seconds'] = 'Sekundach';
$BL['be_alias_ID'] = 'ID aliasu';
$BL['be_ftrash_delall'] = "Czy chcesz na pewno usunąć \nWSZYSTKIE PLIKI z kosza?";
$BL['be_ftrash_delallfiles'] = 'Usuń wszystkie pliki z kosza';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers'] = 'Import subskrybentów z pliku CSV';
$BL['be_newsletter_importtitle'] = 'Importuj Subskrybentów Nowości';
$BL['be_newsletter_addresses'] = 'Adresy';
$BL['be_newsletter_csverror'] = 'Importowany plik CSV jest niepoprawny!';
$BL['be_newsletter_addressesadded'] = 'Adresy dodano.';
$BL['be_newsletter_newimport'] = 'Importuj';
$BL['be_newsletter_importerror'] = 'Proszę sprawdź swój plik CSV - nie ma w nim żadnych adresów!';
$BL['be_newsletter_shouldbe1'] = 'Twój plik CSV powinien być sformatowany tak jak';
$BL['be_newsletter_shouldbe2'] = 'Ale możesz wybrać swój własny znak rozdzielający';
$BL['be_newsletter_sample'] = 'Przykład';
$BL['be_newsletter_selectCSV'] = 'Wybierz plik CSV';
$BL['be_newsletter_delimeter'] = 'Znak rozdzielający';
$BL['be_newsletter_importCSV'] = 'Importuj plik';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle'] = 'Kolejność przydzielona artykułom';
$BL['be_admin_struct_orderdate'] = 'Data utworzenia';
$BL['be_admin_struct_orderchangedate'] = 'Data zmiany';
$BL['be_admin_struct_orderstartdate'] = 'Data rozp.';
$BL['be_admin_struct_orderdesc'] = 'malejąco';
$BL['be_admin_struct_orderasc'] = 'rosnąco';
$BL['be_admin_struct_ordermanual'] = 'Ręcznie (w górę/dół)';
$BL['be_cnt_sitemap_startid'] = 'Rozpocznij na';

// added: 20-10-2004 TU ZACZNIJ
$BL['be_ctype_map'] = 'Mapa';
$BL['be_save_btn'] = 'Zapisz';
$BL['be_cmap_location_error_notitle'] = 'Wpisz tytuł dla tej lokalizacji.';
$BL['be_cnt_map_add'] = 'Dodaj lokalizację';
$BL['be_cnt_map_edit'] = 'Edytuj lokalizację';
$BL['be_cnt_map_title'] = 'Tytuł lokalizacji';
$BL['be_cnt_map_info'] = 'Wpis/informacja';
$BL['be_cnt_map_list'] = 'Lista lokalizacji';
$BL['be_btn_delete'] = 'Czy na pewno chcesz\nusunąć lokalizację?';

// added: 05-11-2004
$BL['be_ctype_phpvar'] = 'Zmienne PHP';
$BL['be_cnt_vars'] = 'Zmienne';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy'] = 'Kopiuj artykuł';
$BL['be_func_struct_nocopy'] = 'Anuluj kopiowanie';
$BL['be_func_struct_copy_level'] = 'Kopiuj poziom struktury';
$BL['be_func_struct_no_copy'] = "Nie można kopiować głównego poziomu struktury!";

// added: 27-11-2004
$BL['be_date_minute'] = 'Minuta';
$BL['be_date_minutes'] = 'Minuty';
$BL['be_date_hour'] = 'Godzina';
$BL['be_date_hours'] = 'Godziny';
$BL['be_date_day'] = 'Dzień';
$BL['be_date_days'] = 'Dni';
$BL['be_date_week'] = 'Tydzień';
$BL['be_date_weeks'] = 'Tygodnie';
$BL['be_date_month'] = 'Miesiąc';
$BL['be_date_months'] = 'Miesiące';
$BL['be_cache'] = 'Pamięć podręczna';
$BL['be_cache_timeout'] = 'Czas wygaśn.';

// added: 13-12-2004
$BL['be_subnav_admin_groups'] = 'Użytkownicy i grupy';
$BL['be_admin_group_add'] = 'Dodaj grupę';
$BL['be_admin_group_nogroup'] = 'Nie znaleziono użytkownika grupy';

// added: 20-12-2004
$BL['be_ctype_forum'] = 'Forum';
$BL['be_subnav_msg_forum'] = 'Lista forów';
$BL['be_forum_title'] = 'Tytuł forum';
$BL['be_forum_permission'] = 'Uprawnienia';
$BL['be_forum_add'] = 'Dodaj forum';
$BL['be_forum_titleedit'] = 'Edytuj forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks'] = 'własne';
$BL['be_show_content'] = 'wyświetl';
$BL['be_main_content'] = 'główna kolumna';
$BL['be_admin_template_jswarning'] = 'UWAGA!!! \nWłasne bloki mogą się zmienić! \n\nJeżeli anulujesz \nlub zresetujesz ustawienia układu! \n\nZmienić szablon?\n\n';

$BL['be_ctype_rssfeed'] = 'RSS';
$BL['be_cnt_rssfeed_url'] = 'adres url RSS';
$BL['be_cnt_rssfeed_item'] = 'elementy';
$BL['be_cnt_rssfeed_max'] = 'maks.';
$BL['be_cnt_rssfeed_cut'] = 'ukryj 1szy element';

$BL['be_ctype_simpleform'] = 'Formularz';

$BL['be_cnt_onsuccess'] = 'przy sukcesie';
$BL['be_cnt_onerror'] = 'przy błędzie';
$BL['be_cnt_onsuccess_redirect'] = 'Przekieruj gdy sukces';
$BL['be_cnt_onerror_redirect'] = 'Przekieruj gdy błąd';

$BL['be_cnt_form_class'] = 'Klasa CSS formularza';
$BL['be_cnt_label_wrap'] = 'Wcięcie etykiety';
$BL['be_cnt_error_class'] = 'Klasa CSS błędu';
$BL['be_cnt_req_mark'] = 'Oznaczenie wymagalności';
$BL['be_cnt_mark_as_req'] = 'Znak jako wymagalny';
$BL['be_cnt_mark_as_del'] = 'Znak elementu do usunięcia';

$BL['be_cnt_type'] = 'Typ';
$BL['be_cnt_label'] = 'Etykieta';
$BL['be_cnt_needed'] = 'Wymagana';
$BL['be_cnt_delete'] = 'Usunięta';
$BL['be_cnt_value'] = 'Wartość';
$BL['be_cnt_error_text'] = 'tekst błędu';
$BL['be_cnt_css_style'] = 'Styl CSS';
$BL['be_cnt_css_class'] = 'CSS Klasa';
$BL['be_cnt_send_copy_to'] = 'Kopia do';

$BL['be_cnt_field'] = array(
                "text"=>'Text (jedno-liniowy)',
                "email"=>'E-mail',
                "textarea"=>'Tekst (wielo-liniowy)',
                "hidden"=>'Ukryty',
                "password"=>'Hasło',
                "select"=>'Wybór menu',
                "list"=>'Lista menu',
                "checkbox"=>'Pole wyboru',
                "checkboxcopy"=>'Pole wyboru (kopia e-mail wł./wył.)',
                "radio"=>'Przycisk opcji',
                "upload"=>'Plik',
                "submit"=>'Przycisk wysyłający',
                "reset"=>'Przycisk resetujący',
                "break"=>'Przerwa',
                "breaktext"=> 'Tekst pomiędzy',
                "special"=>'Tekst (specjalny)',
                "captchaimg"=>'Obraz captcha',
                "captcha"=>'Kod captcha',
                'newsletter'=>'Biuletyn',
                'selectemail'=>'Wybierz menu e-mail',
                'country'=>'Wybierz menu kraju',
                'mathspam'=>'Ochrona spamowa',
                'summing'=>'Podsumowanie',
                'subtract'=>'Odejmij',
                'divide'=>'Podziel',
                'multiply'=>'Pomnóż',
                'calculation'=>'Kalkulacja:',
                'formtracking_off'=>'Wyłącz śledzenie formularza',
                'checktofrom'=>'E-mail odbiorcy musi się różnić od nadawcy',
                'recaptcha'=>'reCAPTCHA',
                'recaptcha_signapikey'=>'Zarejestruj się na reCAPTCHA API key',
    		'recaptchainv' => 'reCAPTCHA niewidoczny',
);

$BL['be_cnt_access'] = 'Dostęp';
$BL['be_cnt_activated'] = 'Aktywowany';
$BL['be_cnt_available'] = 'Dostępny';
$BL['be_cnt_guests'] = 'Goście';
$BL['be_cnt_admin'] = 'Administrator';
$BL['be_cnt_write'] = 'Zapisz';
$BL['be_cnt_read'] = 'Czytaj';

$BL['be_cnt_no_wysiwyg_editor'] = 'Wyłącz edytor WYSIWYG';
$BL['be_cnt_cache_update'] = 'Zresetuj cache';
$BL['be_cnt_cache_delete'] = 'Opróżnij cache';
$BL['be_cnt_cache_delete_msg'] = 'Czy na pewno chcesz opróżnić pamięć podręczną?';

$BL['be_admin_usr_issection'] = 'Uprawnienia do logowania';
$BL['be_admin_usr_ifsection0'] = 'Witryna';
$BL['be_admin_usr_ifsection1'] = 'System wcms';
$BL['be_admin_usr_ifsection2'] = 'Witryna i system wcms';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit'] = 'Edytuj treść artykułu';
$BL['be_func_content_paste0'] = 'Wklej do artykułu';
$BL['be_func_content_paste'] = 'Wklej później do artykułu';
$BL['be_func_content_cut'] = 'Wytnij treść artykułu';
$BL['be_func_content_no_cut'] = "Nie można wyciąć treści artykułu!";
$BL['be_func_content_copy'] = 'Kopiuj treść artykułu';
$BL['be_func_content_no_copy'] = "Nie można skopiować treści artykułu!";
$BL['be_func_content_paste_cancel'] = 'Anuluj zmiany';

$BL['be_cnt_move_deleted'] = 'Skasuj usunięte pliki';
$BL['be_cnt_move_deleted_msg'] = 'Czy na pewno chcesz przesunąć wszystkie \noznaczone pliki do specjalnego folderu?  \n';

$BL['be_admin_struct_permit'] = 'Autoryzacja dostępu (pozostawione puste - dostęp dla wszystkich)';
$BL['be_admin_struct_adduser_all'] = 'Dodaj wszystkich użytkowników';
$BL['be_admin_struct_adduser_this'] = 'Dodaj wybranych uzytkowników';
$BL['be_admin_struct_remove_all'] = 'Usuń wszystkich użytkowników';
$BL['be_admin_struct_remove_this'] = 'Usuń wybranych użytkowników';

$BL['be_ctype_alias'] = 'Alias treści';
$BL['be_cnt_setting'] = 'Konfiguracja';
$BL['be_cnt_spaces'] = 'Odstępy oryginalnej treści';
$BL['be_cnt_toplink'] = 'Odnośnik na górę oryginalnej treści';
$BL['be_cnt_block'] = 'Wyświetl ustawienie bloku oryginalnej treści';
$BL['be_cnt_title'] = 'Tytuły oryginalnej treści';
$BL['be_cnt_status'] = 'Widoczność treści aliasu';
$BL['be_cnt_plugin_n.a.'] = 'Wtyczka jest niedostępna';

$BL['be_file_replace'] = 'Zastąp tytuły plików';
$BL['be_admin_tmpl_copy'] = 'Skopiuj szablon';
$BL['be_alias_articleID'] = 'ID artykułu';
$BL['be_alias_useAll'] = "Użyj nagłówka tego artykułu";
$BL['be_article_morelink'] = 'Odnośnik [więcej...]';

$BL['be_ctype_filelist1'] = 'Lista plików pro';

$BL['be_admin_keywords'] = 'Słowa kluczowe';
$BL['be_admin_keywords_key'] = 'SŁOWO KLUCZOWE';
$BL['be_admin_keywords_err'] = 'Wstaw unikalne SŁOWO KLUCZOWE o nazwie';
$BL['be_admin_keyword_edit'] = 'Edytuj SŁOWO KLUCZOWE';
$BL['be_admin_keyword_del'] = 'Usuń SŁOWO KLUCZOWE';
$BL['be_admin_keyword_delmsg'] = 'Rzeczywiście chcesz\nto usunąć SŁOWO KLUCZOWE?';
$BL['be_admin_keyword_add'] = 'Dodaj SŁOWO KLUCZOWE';

$BL['be_cnt_transparent'] = 'Flash przeźroczysty';

// added: 02-04-2006
$BL['be_admin_struct_orderkilldate'] = 'Daty usunięcia';
$BL['be_func_switch_contentpart'] = 'Czy naprawdę chcesz przełączyć część zawartości ? \n\nJest to bardzo ważna zmiana więc! \nIstotne parametry mogą zostać zmienione! \n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>UWAGA!</strong> Katalog &quot;CODE-SNIPPETS&quot; nadal istnieje! Usuń katalog <strong>phpwcms_code_snippets</strong> - jest on potencjalnym problemem bezpieczeństwa.';

$BL['be_ctype_poll'] = 'Ankieta';
$BL['be_cnt_pos8'] = 'Tabela, lewo';
$BL['be_cnt_pos9'] = 'Tabela, prawo';
$BL['be_cnt_pos8i'] = 'Wyrównaj obraz w lewo w tabeli';
$BL['be_cnt_pos9i'] = 'Wyrównaj obraz w prawo w tabeli';

$BL['be_WYSIWYG'] = 'WYSIWYG edytor';
$BL['be_WYSIWYG_disabled'] = 'WYSIWYG edytor wyłączony';

$BL['be_admin_struct_acat_hiddenactive'] = 'widoczny kiedy aktywny';

$BL['be_login_jsinfo'] = 'Proszę włącz JavaScript który jest niezbędny wewnątrz!';

$BL['be_admin_struct_maxlist'] = 'maks. Artykułów w trybie listy';
$BL['be_admin_optgroup_label'] = array(1 => 'tekst', 2 => 'Obraz', 3 => 'Formularz', 4 => 'Admin', 5 => 'Specjalny');
$BL['be_cnt_articlemenu_maxchar'] = 'max. znaków';

$BL['be_cnt_sysadmin_system'] = 'System';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date'] = 'Twoja instalacja jest aktualna,brak możliwych aktualizacji tej wersji phpwcms.';
$BL['Version_not_up_to_date'] = 'Twoja instalacja <b>nie</b> wydaje się być aktualna. Dostępne są,aktualizacje tej wersji phpwcms-a. Proszę odwiedź <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a> aby poznać szczegóły.';
$BL['Latest_version_info'] = 'Ostatnia dostępna wersja to <b>phpwcms %s</b>.';
$BL['Current_version_info'] = 'Twój bieżący <b>phpwcms %s</b>.';
$BL['Connect_socket_error'] = 'Nie można otworzyć połączenia z serwerem phpwcms , zgłaszany jest błąd:<br />%s';
$BL['Socket_functions_disabled'] = 'Niemożliwe jest użycie funkcji socket';
$BL['Mailing_list_subscribe_reminder'] = 'Aby otrzymywać informacje o aktualizacjach do Twojego phpwcms,prenumeruj <a href="http://eepurl.com/bm-BrH" target="_blank">listę mailingową</a>.';
$BL['Version_information'] = 'Informacja o wersji phpwcms';

$BL['be_cnt_search_highlight'] = 'Podświetlenie';
$BL['be_cnt_results_wordlimit'] = 'maks. Słów dla podsumowania';
$BL['be_cnt_page_of_pages'] = 'Nawig. wyszukiwania';
$BL['be_cnt_page_of_pages_descr'] = '{PREV:Wróć} strona #/##, wynik ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Nast.}';
$BL['be_cnt_search_show_top'] = 'góra';
$BL['be_cnt_search_show_bottom'] = 'dół';
$BL['be_cnt_search_show_next'] = 'następny (także jeżeli nie link)';
$BL['be_cnt_search_show_prev'] = 'poprzedni (także jeżeli nie link)';
$BL['be_cnt_search_show_forall'] = 'pokaż zawsze';
$BL['be_cnt_search_startlevel'] = 'Zacznij szukać';
$BL['be_cnt_search_searchnot'] = 'Nie szukaj';
$BL['be_cnt_results_minchar'] = 'minimalna liczba znaków w zapytaniu';
$BL['be_cnt_search_hidesummary'] = 'ukryj wyszukiwanie tekstu zapowiedzi';

$BL['be_cnt_pagination'] = 'Paginuj elementy zawartości';
$BL['be_article_pagination'] = 'Paginuj artykuły';
$BL['be_article_per_page'] = 'Artykułów na stronę';
$BL['be_pagination'] = 'Paginacja';

$BL['be_ctype_recipe'] = 'Przepis';
$BL['be_ctype_faq'] = 'FAQ';
$BL['be_cnt_additional'] = 'Dodatkowe';
$BL['be_cnt_question'] = 'Pytanie';
$BL['be_cnt_answer'] = 'Odpowiedź';
$BL['be_cnt_same_as_summary'] = 'Użyj danych obrazu artykułu';
$BL['be_cnt_sorting'] = 'Sortowanie';
$BL['be_cnt_imgupload'] = 'Prześlij&nbsp;obraz';
$BL['be_cnt_filesize'] = 'Rozmiar pliku';
$BL['be_cnt_captchalength'] = 'Długość kodu captcha';
$BL['be_cnt_chars'] = 'Znaków';
$BL['be_cnt_download'] = 'Pobierz';
$BL['be_cnt_download_direct'] = 'Bezpośrednio (nie zalecane!)';
$BL['be_cnt_database'] = 'Baza danych';
$BL['be_cnt_formsave_in_db'] = 'Zapisz wyniki formularza';

$BL['be_cnt_email_notify'] = 'Powiadom przez e-mail';
$BL['be_cnt_notify_by_email'] = 'Przez e-mail do';
$BL['be_cnt_last_edited'] = 'Ostatnio zmieniane';
$BL['be_cnt_export_selection'] = 'Eksportuj wybrane';
$BL['be_cnt_delete_duplicates'] = 'Usuń duplikaty';
$BL['be_cnt_new_recipient'] = 'Dodaj odbiorcę';

$BL['be_cnt_newsletter_prepare'] = 'Biuletyn aktywny';
$BL['be_cnt_queued'] = 'Oczekających';
$BL['be_cnt_newsletter_prepare1'] = 'Wszyscy odbiorcy będą przyjęci do kolejki wysyłania';
$BL['be_cnt_newsletter_prepare2'] = 'Kolejka wysyłania została zaktualizowana&#8230;';

$BL['be_cnt_export'] = 'Eksport';

$BL['be_cnt_formsave_profile'] = 'Zapisz dane profilu użytkownika';
$BL['be_profile_label_add'] = 'Dodatkowe';
$BL['be_profile_label_website'] = 'Url';
$BL['be_profile_label_gender'] = 'Rodzaj';
$BL['be_profile_label_birthday'] = 'Urodziny';

$BL['be_cnt_store_in'] = 'zapisz pole';
$BL['be_aboutlink_title'] = 'Informacja o phpwcms i licencji';

$BL['be_shortdate'] = 'd.m.y';
$BL['be_shortdatetime'] = 'd.m.y H:i';
$BL['be_longdatetime'] = 'd.m.Y H:i:s';

$BL['be_confirm_sending'] = 'Potwierdź wysyłanie';
$BL['be_confirm_text'] = 'Tak, wyślij biuletyn do wszystkich odbiorców!';

$BL['be_last_sending'] = 'ostatnia wysyłka';
$BL['be_last_edited'] = 'ostatnio zmieniane';
$BL['be_total'] = 'łącznie';

$BL['be_settings'] = 'Ustawienia';
$BL['be_ctype'] = 'Zawartość';
$BL['be_selection'] = 'Zaznaczenie';

$BL['be_ctype_module'] = 'Wtyczki';
$BL['be_cnt_lightbox'] = 'Galeria obrazów';
$BL['be_cnt_behavior'] = 'Zachowanie';
$BL['be_cnt_imglist_nocaption'] = 'Ukryj nagłówek dla miniatur';

$BL['be_ctype_felogin'] = 'Logowanie na stronie frontowej';
$BL['be_cookie_runtime'] = 'Wygaśnięcie cookie';
$BL['be_locale'] = 'lokalnie';
$BL['be_date_format'] = 'Format daty';

$BL['be_check_login_against'] = 'Sprawdź poprawność logowania';
$BL['be_userprofile_db'] = 'Profil użytkownika bazy danych';
$BL['be_backenduser_db'] = 'Zaplecze użytkownika bazy danych';

$BL['be_gb_post_login'] = 'Poczta tylko dla zalogowanych użytkowników';
$BL['be_gb_show_login'] = 'Pokaż tylko zarejestrowanym użytkownikom';
$BL['be_gb_urlcheck'] = 'Włącz zdalną kontrolę poprawności URL';
$BL['be_order'] = 'Kolejność';
$BL['be_unique_teaser_entry'] = 'Pokaż zwiastun/link artykułu tylko jeden raz na stronę';
$BL['be_check_against_category_alias'] = 'Link jednego artykułu, wewnątrz poziom struktury z poziomem struktury';

$BL['be_allowed_tags'] = 'Dopuszczalne tagi';
$BL['be_fe_login_url'] = 'FE url logowania';
$BL['be_ctype_imagesdiv'] = 'Grafika &lt;div&gt;';
$BL['be_cnt_imagecenter'] = 'poziome/pionowe wyśrodkowanie';
$BL['be_cnt_imagenocenter'] = 'bez wyśrodkowania';
$BL['be_cnt_imagecenterh'] = 'wyśrodkowanie w poziomie';
$BL['be_cnt_imagecenterv'] = 'wyśrodkowanie w pionie';

$BL['be_overwrite_default'] = 'Zostaną zmienione domyślne ustawienia pliku konfiguracyjnego';
$BL['be_cnt_sortvalue'] = 'sortowanie&nbsp;wartość';
$BL['be_dialog_warn_nosave'] = 'Jeżeli będziesz kontynuował zmiany nie zostaną zapisane!\nCzy naprawdę chcesz kontynuować?';
$BL['be_cnt_paginate_subsection'] = 'Podrozdział';
$BL['be_cnt_subsection_tite'] = 'Tytuł podrozdziału';
$BL['be_cnt_subsection_warning'] = 'Numeracja podrozdziałów (paginate content parts) jest możliwa dla\nmain column (CONTENT) tylko!';

$BL['be_no_search'] = 'Nie szukaj';
$BL['be_priorize'] = 'Priorytet';
$BL['be_change_articleID'] = 'Zmień ID artykułu';
$BL['be_title_wrap'] = 'Zawijaj tytuł artykułu';

$BL['be_no_rss'] = 'RSS';
$BL['be_article_urlalias'] = 'Alias artykułu';

$BL['be_image_crop'] = 'Kadruj miniaturę';
$BL['be_image_cropit'] = 'Kadruj obrazek';
$BL['be_image_align'] = 'Wyrównanie obrazka';

$BL['be_ctype_flashplayer'] = 'HTML5/Flash odtwarzacz multimedialny';
$BL['be_flashplayer_caption'] = 'Podpis';
$BL['be_flashplayer_thumbnail'] = 'Miniaturka';
$BL['be_flashplayer_selectsize'] = 'Wybierz rozmiar odtwarzacza';
$BL['be_flash_media'] = 'Flash';
$BL['be_html5_media'] = 'HTML5';
$BL['be_html5_h264'] = 'H.264';
$BL['be_html5_webm'] = 'WebM';
$BL['be_html5_ogg'] = 'Ogg';
$BL['be_media_format'] = 'Format';
$BL['be_media_watermark'] = 'Znak wodny';
$BL['be_skin'] = 'Skóra';
$BL['be_foreground_color'] = 'Kolor pierwszego planu';
$BL['be_background_color'] = 'Kolor tła';
$BL['be_highlight_color'] = 'Kolor podświetlenia';

$BL['be_check_feuser_profile'] = 'Profil użytkownika witryny';
$BL['be_check_feuser_registration'] = 'Rejestracja';
$BL['be_check_feuser_manage'] = 'Zarządzane przez użytkownika';
$BL['be_hide_active_articlelink'] = 'Ukryj aktywne artykuły w menu artykułów';

$BL['be_module_search'] = 'szukaj również';

$BL['be_ctype_imagesspecial'] = 'Obrazy specjalne';
$BL['be_image_WxHpx'] = 'B x H px';
$BL['be_fx_1'] = 'Efekt 1';
$BL['be_fx_2'] = 'Efekt 2';
$BL['be_fx_3'] = 'Efekt 3';
$BL['be_image_zoom'] = 'Powiększony widok';
$BL['be_image_delete_js'] = 'Czy chcesz usunąć wybrany wpis obrazka?';

$BL['be_news'] = 'Aktualności';
$BL['be_news_create'] = 'Utwórz wpis news-a';
$BL['be_tags'] = 'Tag/Hasło';
$BL['be_title'] = 'Tytuł';
$BL['be_delete_dataset'] = 'Usunąć wybrane dane?';
$BL['be_action_notvalid'] = 'Twoje ostatnio wybrane działanie zostało pominięte, ponieważ nie było prawidłowe!';
$BL['be_action_deleted'] = 'Wybrany zestaw danych mający ID {ID} został usunięty.';
$BL['be_action_status'] = 'Stan wybranego zestawu danych mającego ID {ID} został zmieniony.';
$BL['be_data_select_failed'] = 'Dostęp do wybranych danych nie powiódł się. Proszę potwierdzić swój wybór.';
$BL['be_alias'] = 'Alias';
$BL['be_url_value'] = 'Tytuł URL';
$BL['default_date_format'] = 'TT.MM.JJJJ';
$BL['default_date_delimiter'] = '.';
$BL['default_time_format'] = 'HH:MM';
$BL['default_date'] = 'd.m.Y';
$BL['default_time'] = 'H:i';
$BL['be_place'] = 'Miejsce';
$BL['be_teasertext'] = 'Tekst zwiastuna';
$BL['be_published'] = 'Opublikuj';
$BL['be_show_archived'] = 'Dostępne po dacie zakończenia (archiwum)';
$BL['be_save_copy'] = 'Zapisz wpisy jako duplikaty';
$BL['be_read_more_link'] = 'Więcej URL/ID';
$BL['be_news_name_mandatory'] = "Wpisz tytuł newsa. Jest to obowiązkowe!";
$BL['be_successfully_saved'] = 'Wszystkie dane zostały zapisane!';
$BL['be_successfully_updated'] = 'Wszystkie dane zostały zaktualizowane!!';
$BL['be_error_while_save'] = 'Zapisywanie danych nie powiodło się.';
$BL['be_copyright'] = 'Prawa autorskie';
$BL['be_file_multiple_upload'] = 'Przesyłanie wielu plików';
$BL['be_files_upload'] = 'Prześlij wybrane pliki';
$BL['be_files_browse'] = 'Przeglądanie plików';
$BL['be_files_select_available'] = 'Wybierz już przesłane pliki';
$BL['be_archive'] = 'Archiwum';
$BL['be_off'] = 'wył';
$BL['be_on'] = 'wł';
$BL['be_random'] = 'losowo';
$BL['be_sorted'] = 'posortowane';
$BL['be_granted_download'] = 'bezpieczne pobieranie tylko w witrynie';
$BL['be_granted_feuser'] = 'Tylko dla zalogowanych użytkowników serwisu';
$BL['be_fileuploader_typeError'] = "{file} ma nieprawidłowe rozszerzenie. Prawidłowe rozszerzenie(a): {extensions}.";
$BL['be_fileuploader_sizeError'] = "{file} jest zbyt duży, maksymalny rozmiar pliku to {sizeLimit}.";
$BL['be_fileuploader_minSizeError'] = "{file} jest zbyt mały, minimalny rozmiar pliku to {minSizeLimit}.";
$BL['be_fileuploader_emptyError'] = "{file} jest pusty, wybierz pliki ponownie bez niego.";
$BL['be_fileuploader_noFilesError'] = "Brak plików do wgrywania.";
$BL['be_fileuploader_onLeave'] = "Pliki zostały przesłane, jeśli zostawisz teraz wysyłanie zostanie anulowane.";
$BL['be_fileuploader_dragText'] = "Upuść pliki tutaj, aby załadować!";
$BL['be_fileuploader_uploadButtonText'] = 'Wybierz pliki lub upuść tutaj';
$BL['be_delete_selected_files'] = 'Usuń zaznaczone pliki';
$BL['be_delete_selected_files_confirm'] = 'Czy na pewno chcesz usunąć wszystkie zaznaczone pliki?';

$BL['be_ctype_tabs'] = 'Karty';
$BL['be_tab_add'] = 'Dodaj kartę';
$BL['be_tab_name'] = 'Karta';
$BL['be_headline'] = 'Nagłówek';
$BL['be_tab_delete_js'] = 'Czy chcesz usunąć wybraną kartę?';

$BL['be_pagniate_count'] = 'Elementów na stronie';
$BL['be_limit_to'] = 'Ogranicz do';
$BL['be_archived_items'] = 'Archiwizowane elementy';
$BL['be_include'] = 'dołącz';
$BL['be_exclude'] = 'wyklucz';
$BL['be_solely'] = 'wyłącznie';
$BL['be_fsearch_not'] = 'NIE';
$BL['be_date_year'] = 'Rok';
$BL['be_archive_link'] = 'Link archiwum';
$BL['be_use_prio'] = 'Zastosuj priorytetyzację';
$BL['be_skip_first_items'] = 'Pomiń górne elementy';
$BL['be_news_detail_link'] = 'Artykuł nowości';

$BL['be_gallerydownload'] = 'Pozwól na pobieranie w galerii';
$BL['be_gallery_root'] = 'Katalog główny galerii';
$BL['be_gallery_directory'] = 'Podkatalog galerii';
$BL['be_gallery'] = 'Galeria';

$BL['be_sort_date'] = 'Sortuj daty';

$BL['group_superuser'] = 'Superuser';
$BL['group_admin'] = 'Administrator';
$BL['group_editor'] = 'Redaktor';
$BL['group_newsletter'] = 'Redaktor biuletynu';
$BL['group_client'] = 'Klient';
$BL['group_guest'] = 'Gość';

$BL['php_function'] = 'Funkcja PHP';
$BL['article_menu_title'] = 'Tytuł menu';

$BL['content_type'] = 'Content-Type';
$BL['automatic'] = 'automatycznie';

$BL['random_image'] = 'Losowy obraz';
$BL['limit_image_from_list'] = 'Obrazów max.';

$BL['alt_image'] = 'Alt. image';
$BL['alt_text'] = 'Alt. text';
$BL['over'] = 'powyżej';
$BL['js_lib'] = 'Biblioteka JS';
$BL['js_lib_alwaysload'] = 'zawsze ładuj';
$BL['frontendjs_load'] = 'ładuj frontend.js (więcej z przyczyn historycznych)';
$BL['googleapi_load'] = 'użyj CDN';

$BL['fancyupload_clear_list'] = 'Wyczyść Listę';
$BL['fancyupload_file_uploaded'] = 'Plik został przesłany';
$BL['fancyupload_file_error'] = 'Wystąpił błąd';
$BL['fancyupload_adblock_error'] = 'Aby włączyć wbudowany uploader, odblokuj kartę w swojej przeglądarce i odśwież (zobacz Adblock).';
$BL['fancyupload_flashblock_error'] = 'Aby włączyć wbudowany uploader, włącz zablokowany film Flash (zobacz Flashblock).';
$BL['fancyupload_required_error'] = 'Wymagany plik nie został znaleziony, prosimy o cierpliwość, a my to naprawimy.';
$BL['fancyupload_flash_error'] = 'Aby włączyć wbudowany uploader, należy zainstalować najnowszy plugin Adobe Flash.';

$BL['be_cnt_function_validate'] = 'Walidacja PHP';
$BL['be_structform_selected_cp'] = 'Wybór Limitu użytecznych części zawartości';
$BL['be_structform_select_cp'] = 'Wybierz elementy treści';

$BL['source_image_not_found'] = 'Błąd źródła obrazu: Obraz %s wydaje się nie istnieć.';
$BL['form_force_ssl'] = 'Wymuś wysyłanie formularzy z SSL';
$BL['numerize_title'] = 'Numerowane zamiast tytułów artykułów';
$BL['be_article_noteaser'] = 'bez zapowiedzi';
$BL['be_acat_disable301'] = 'artykuł 301 przekierowanie';

$BL['file_actions_step1'] = "Krok 1: wybierz folder";
$BL['file_actions_step2'] = "Krok 2: wybierz plik";
$BL['file_actions_step3'] = "Krok 3: wybierz działanie";
$BL['file_actions_button'] = 'Wykonaj operację';
$BL['file_actions_no'] = 'Brak plików do edycji. Wybierz inny folder ';
$BL['file_actions_delete'] = 'Czy jesteś pewien, że należy usunąć wybrane pliki?';
$BL['file_actions_bemuser'] = 'Wybrane pliki zostaną przypisane do nowego użytkownika i przeniesione do katalogu głównego.';
$BL['file_actions_bemfolder'] = 'Proszę wybrać folder docelowy. Wybrane pliki zostaną przeniesione do tego folderu. ';
$BL['file_actions_pdl_empty'] = 'Wybierz działanie';
$BL['file_actions_pdl_delete'] = 'Usuń pliki';
$BL['file_actions_pdl_move'] = 'Przenieś pliki';
$BL['file_actions_pdl_status'] = 'Zmień status';
$BL['file_actions_pdl_user'] = 'Zmień właściciela';
$BL['file_actions_msg_move'] = 'Pliki zostały pomyślnie przeniesione';
$BL['file_actions_msg_delete'] = 'Pliki zostały pomyślnie usunięte';
$BL['file_actions_msg_status'] = 'Status plików pomyślnie zmienio';
$BL['file_actions_msg_error'] = 'Nie wybrano plików';
$BL['file_actions_msg_user'] = 'Pliki zostały pomyślnie przypisany do nowego użytkownika';

$BL['be_imagefiles_as_gallery'] = 'utwórz galerię z plików graficznych';

$BL['be_link'] = 'Link';
$BL['be_links'] = 'Linki';
$BL['be_redirect'] = 'Przekierowanie';
$BL['be_redirects'] = 'Przekierowania';
$BL['be_views'] = 'Wywołań';
$BL['be_structure_id'] = 'ID struktury';
$BL['be_shortcut'] = 'Skrót';
$BL['be_target_type'] = 'Typ docelowy';
$BL['be_http_status'] = 'HTTP status';
$BL['be_http_status301'] = 'Trwale przeniesiony';
$BL['be_http_status307'] = 'Przekierowanie tymczasowe';
$BL['be_http_status404'] = 'Nie znaleziono';
$BL['be_http_status401'] = 'Nieautoryzowany';
$BL['be_http_status503'] = 'Usługa niedostępna';
$BL['be_redirect_error1'] = 'Alias/Skrót, wymagana jest struktura lub ID artykułu';
$BL['be_redirect_error2'] = 'Cel jest wymagany';
$BL['be_redirect_error3'] = 'Dla docelowego typu, ID artykułu i ID struktury dozwolone są tylko liczby całkowite jako docelowe';
$BL['be_new_linkredirect'] = 'Dodaj link/przekierowanie';

$BL['be_ctype_accordion'] = 'Grupa (accordion)';
$BL['be_ctype_number'] = 'Numer';
$BL['be_inactive'] = 'nieaktywne';
$BL['be_locked'] = 'zablokowane';
$BL['be_n/a'] = 'n. a.';
$BL['be_opengraph_support'] = 'Zezwalaj na Dzielenie się z innymi';
$BL['be_player_volume'] = 'Głośność';
$BL['be_player_volume_muted'] = 'wyciszone';
$BL['be_keyword'] = 'Słowo kluczowe';
$BL['be_tag'] = 'Znacznik';

$BL['be_system_container'] = 'Zasobnik systemu';
$BL['be_system_container_norender'] = 'bez regularnego renderowania serwisu';
$BL['be_custom_scriptlogic'] = 'zdefiniowany przez użytkownika (skrypt logiczny)';
$BL['be_flush_image_cache'] = 'Opróżnij pamięć podręczną grafik';

$BL['be_caption_alt'] = 'poprz. atr.';
$BL['be_caption_title'] = 'tytuł atr.';
$BL['be_caption_file_imagesize'] = 'BxHxC <em>(jeżeli obraz)</em>';
$BL['be_caption_file_title'] = 'Tytuł pliku';
$BL['be_caption_descr.'] = 'Opis';
$BL['be_display_html5_only'] = 'Tylko HTML5';
$BL['be_audio_only'] = 'Tylko audio';

$BL['be_filter'] = 'Filtr';
$BL['be_filter_with_tags'] = 'w/g etykiet';
$BL['be_filter_not_selected'] = 'Nie wybrano kategorii';
$BL['be_empty_search_result'] = 'Wyszukiwanie nie przyniosło wyników.';
$BL['confirm_cp_tab_warning'] = 'Podrozdział nie ma tytułu i nie jest przypisany żaden numer.Zaznaczenie zostanie utracone, podczas zapisu lub aktualizacji.';

$BL['be_canonical'] = 'Link kanoniczny';
$BL['be_breadcrumb'] = 'Zachowanie nawigacyjne wyświetlania';
$BL['be_breadcrumb_nothidden'] = 'Widoczne, jeśli poziom jest ukryty';
$BL['be_breadcrumb_nolink'] = 'Nie ma linku';

$BL['CSRF_POST_INVALID'] = 'Nie <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> znaleziono parametrów POST. Ze względów bezpieczeństwa, sesja została zakończona.';
$BL['CSRF_POST_FAILED'] = 'Weryfikacja <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> parametrów POST nie powiodła się. Ze względów bezpieczeństwa, sesja została zakończona.';
$BL['CSRF_GET_INVALID'] = 'Nie <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> znaleziono parametrów GET. Ze względów bezpieczeństwa, sesja została zakończona.';
$BL['CSRF_GET_FAILED'] = 'Weryfikacja <a href="https://pl.wikipedia.org/wiki/Cross-site_request_forgery">CSRF</a> parametrów GET nie powiodła się. Ze względów bezpieczeństwa, sesja została zakończona.';

$BL['be_parental_alias'] = 'Alias macierzysty';
$BL['be_fsearch_nor'] = 'BRAK';
$BL['be_tab_toggle'] = 'Przełącz zakładkę, by rozwinąć lub zamknąć';
$BL['be_custom_textfield'] = 'Własny tekst';
$BL['be_tab_template_toggle_warning'] = 'Zmiana szablonu może spowodować, że niestandardowe pola ulegną zmianie i istniejące wartości utracone.\n\nCzy jesteś pewien, że chcesz kontynuować?';

$BL['be_onepage_id'] = 'OnePage ID (#zakotwicz) wsparcie';
$BL['be_onepage_template'] = 'Traktuj jako szablon OnePage';
$BL['be_yes'] = 'Tak';
$BL['be_no'] = 'Nie';
$BL['be_attr_title'] = 'Tytuł (Atrybut)';
$BL['be_attr_alt'] = 'Alternatywny Tekst';
$BL['be_ie8ignore'] = 'WYŁĄCZ <a href="https://en.wikipedia.org/wiki/Conditional_comment" target="_blank" class="underline">komentarze warunkowe</a> dla IE8';
$BL['be_cookie_consent_enable'] = 'Włącz wtyczkę Zgody Cookie';
$BL['be_cookie_consent_message'] = 'Komunikat zgody';
$BL['cookie_consent_message'] = 'Witryna ta wykorzystuje cookies, aby uzyskać najlepszą jakość na naszej stronie internetowej';
$BL['be_cookie_consent_dismiss'] = 'Przycisk tekstu zwolenia';
$BL['cookie_consent_dismiss'] = 'Rozumiem!';
$BL['be_cookie_consent_more'] = 'Tekst przycisku dowiedz się więcej';
$BL['cookie_consent_more'] = 'Więcej informacji';
$BL['be_cookie_consent_link'] = 'Polityka Cookie url/alias';
$BL['be_cookie_consent_theme'] = 'Motyw (puste = brak CSS)';
$BL['be_google_analytics_enable'] = 'Użyj Google Analytics';
$BL['be_piwik_enable'] = 'Użyj Matomo/Piwik';
$BL['be_tracking_anonymize'] = 'Utajnij IP';
$BL['be_tracking_id'] = 'Śledzenie ID';
$BL['be_site_id'] = 'ID witryny';
$BL['be_piwik_url'] = 'Matomo/Piwik URL';
$BL['be_filedownload_direct_blocked'] = 'blokowany przez <abbr title="%s">.htaccess</abbr>';
$BL['be_tracking_optout'] = 'wsparcie dla pliku Opt-Out cookie <i>&lt;a href=&quot;javascript:gaOptout()&quot;&gt;&lt;/a&gt;</i>';

$BL['be_iptc_data'] = 'Dane IPTC';
$BL['be_iptc_as_caption'] = 'Użyj do podpisu, Praw autorskich itp. korzytaj , gdy nie jest ustawiony';
$BL['iptc_ImageDescription'] = 'Opis obrazu';
$BL['iptc_Copyright'] = 'Prawa autorskie';
$BL['iptc_Artist'] = 'Wykonawca';
$BL['iptc_Keywords'] = 'Słowa kluczowe';
$BL['iptc_CountryDest'] = 'Kraj';
$BL['iptc_ProvinceOrStateDest'] = 'Region';
$BL['iptc_CityDest'] = 'Miejscowość';
$BL['iptc_SublocationDest'] = 'Lokalizacja';
$BL['iptc_ObjectName'] = 'Nazwa obiektu';
$BL['iptc_SpecialInstructions'] = 'Instrukcje specjalne';
$BL['iptc_Headline'] = 'Nagłówek';
$BL['iptc_Credit'] = 'Kredyt';
$BL['iptc_Source'] = 'Źródło';
$BL['iptc_EditStatus'] = 'Edytuj status';
$BL['iptc_iimCategory'] = 'Kategoria';
$BL['iptc_iimSupplementalCategory'] = 'Kategoria uzupełniająca';
$BL['iptc_Urgency'] = 'Pilność';
$BL['iptc_FixtureIdentifier'] = 'Identyfikator';
$BL['iptc_LocationDestCode'] = 'Kod lokalizacji';
$BL['iptc_LocationDest'] = 'Lokalizacja';
$BL['iptc_Software'] = 'Oprogramowanie';
$BL['iptc_SoftwareVersion'] = 'Wersja oprogramowania';
$BL['iptc_ObjectCycle'] = 'Cykl obiektu';
$BL['iptc_CountryCodeDest'] = 'Kod kraju';
$BL['iptc_OriginalTransmissionRef'] = 'Oryginalne pochodzenie';
$BL['iptc_Contact'] = 'Kontakt';
$BL['iptc_Writer'] = 'Piszący';
$BL['iptc_LanguageCode'] = 'Kod języka';
$BL['iptc_DateTimeOriginal'] = 'Data/czas oryginalna';
$BL['iptc_DateTimeDigitized'] = 'Data/czas zdigitalizowane';
$BL['iptc_DateTimeReleased'] = 'Data/czas wydania';
$BL['iptc_DateTimeExpires'] = 'Data/czas wygaśnięcia';
$BL['iptc_IntellectualGenre'] = 'Gatunek';
$BL['iptc_SubjectNewsCode'] = 'ID tematu';
$BL['iptc_iimVersion'] = 'Wersja';

$BL['be_suppress_render_caption'] = 'Tłumienie renderowania podpisu';
$BL['be_cnt_attribute_class'] = 'Klasa CSS';
$BL['be_cnt_attribute_id'] = 'CSS id';
$BL['be_cnt_avoid_duplicates'] = 'Pozwalaj tylko na unikalne wartości';
$BL['be_not_set'] = 'Nie ustawiony';
$BL['be_licensed_under_GPL'] = 'Na licencji GPL.';
$BL['be_extensions_copyright'] = 'Rozszerzenia są chronione prawami autorskimi ich właścicieli.';