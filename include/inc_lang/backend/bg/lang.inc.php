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
// normal line break:    '&#13', JavaScript Linebreak: '\n'
// Translated by : Kiril Jovchev
// Last change: 24 Aug 2004

$BL['usr_online']                       = 'посетители';

// Login Page
$BL["login_text"]                       = 'Потребителско име';
$BL['login_error']                      = 'Грешен потребител/парола!';
$BL["login_username"]                   = 'Потребителско име';
$BL["login_userpass"]                   = 'Парола';
$BL["login_button"]                     = 'Влез';
$BL["login_lang"]                       = 'Език';

// phpwcms.php
$BL['be_nav_logout']                    = 'ИЗХОД';
$BL['be_nav_articles']                  = 'СТАТИИ';
$BL['be_nav_files']                     = 'ФАЙЛОВЕ';
$BL['be_nav_modules']                   = 'МОДУЛИ';
$BL['be_nav_messages']                  = 'СЪОБЩЕНИЯ';
$BL['be_nav_chat']                      = 'ЧАТ';
$BL['be_nav_profile']                   = 'ПРОФИЛ';
$BL['be_nav_admin']                     = 'АДМИНИСТРАЦИЯ';
$BL['be_nav_discuss']                   = 'ДИСКУСИИ';

$BL['be_page_title']                    = 'phpwcms backend (администрация)';

$BL['be_subnav_article_center']         = 'прес-център';
$BL['be_subnav_article_new']            = 'нова статия';
$BL['be_subnav_file_center']            = 'файл-център';
$BL['be_subnav_file_ftptakeover']       = 'ftp takeover';
$BL['be_subnav_mod_artists']            = 'автор, категория, жанр';
$BL['be_subnav_msg_center']             = 'център за съобщения';
$BL['be_subnav_msg_new']                = 'ново съобщение';
$BL['be_subnav_msg_newsletter']         = 'бюлетин';
$BL['be_subnav_chat_main']              = 'главна страница на чата';
$BL['be_subnav_chat_internal']          = 'вътрешен чат';
$BL['be_subnav_profile_login']          = 'информация за потребителя';
$BL['be_subnav_profile_personal']       = 'лични данни';
$BL['be_subnav_admin_pagelayout']       = 'параметри на страницата';
$BL['be_subnav_admin_templates']        = 'шаблони';
$BL['be_subnav_admin_css']              = 'css по подразбиране';
$BL['be_subnav_admin_sitestructure']    = 'структура на сайта';
$BL['be_subnav_admin_users']            = 'администриране на потребители';
$BL['be_subnav_admin_filecat']          = 'категории на файлове';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'статия номер';
$BL['be_func_struct_preview']           = 'преглед';
$BL['be_func_struct_edit']              = 'редактиране на статията';
$BL['be_func_struct_sedit']             = 'редактиране на нивото на структурата';
$BL['be_func_struct_cut']               = 'изрежи статия';
$BL['be_func_struct_nocut']             = 'отмени изрязване на статия';
$BL['be_func_struct_svisible']          = 'видима/невидима';
$BL['be_func_struct_spublic']           = 'публична/частна';
$BL['be_func_struct_sort_up']           = 'нагоре';
$BL['be_func_struct_sort_down']         = 'надолу';
$BL['be_func_struct_del_article']       = 'изтрии статията';
$BL['be_func_struct_del_jsmsg']         = 'Наистина ли искате \nда изтриете статията?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'създаване на нова статия в нивото';
$BL['be_func_struct_paste_article']     = 'вмъкване на статията в нивото';
$BL['be_func_struct_insert_level']      = 'създаване на категория';
$BL['be_func_struct_paste_level']       = 'вмъкване на категория';
$BL['be_func_struct_cut_level']         = 'изрязване на категория';
$BL['be_func_struct_no_cut']            = "Не може да изрежете главното ниво!";
$BL['be_func_struct_no_paste1']         = "Не може да вмъкнете тука!";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'that should paste in here';
$BL['be_func_struct_paste_cancel']      = 'отказ от промяната на структурата';
$BL['be_func_struct_del_struct']        = 'изтриване на нивото';
$BL['be_func_struct_del_sjsmsg']        = 'Наистина ли искате \nда изтриете нивото?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'отвори';
$BL['be_func_struct_close']             = 'затвори';
$BL['be_func_struct_empty']             = 'изпразни';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'само текст';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'код';
$BL['be_ctype_textimage']               = 'тект с картинка';
$BL['be_ctype_images']                  = 'картинки';
$BL['be_ctype_bulletlist']              = 'списък (таблица)';
$BL['be_ctype_ullist']                  = 'списък';
$BL['be_ctype_link']                    = 'препратки &amp; email';
$BL['be_ctype_linklist']                = 'списък от препратки';
$BL['be_ctype_linkarticle']             = 'връзка към статия';
$BL['be_ctype_multimedia']              = 'мултимедия';
$BL['be_ctype_filelist']                = 'списък от файлове';
$BL['be_ctype_emailform']               = 'email форма';
$BL['be_ctype_newsletter']              = 'бюлетин';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Профилът е създаден успешно.';
$BL['be_profile_create_error']          = 'Грешка при създаване.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Профилът е обновен успешно.';
$BL['be_profile_update_error']          = 'Грешка при обновяване.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'потребителското име {VAL} е невалидно';
$BL['be_profile_account_err2']          = 'паролата е много къса (only {VAL} символа: необходими са поне 5)';
$BL['be_profile_account_err3']          = 'паролата трябва да бъде еднаква с повторната парола';
$BL['be_profile_account_err4']          = 'email {VAL} е невалиден';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'вашите лични данни';
$BL['be_profile_data_text']             = 'личните данни не са задължителни. Те помагат на потребителите и посетителите на сайта да получат повече информация за Вас, Вашите интереси и умения. Ако изберете съответните отметки потребителите могат да виждат или не виждат информация за Вас в публичните страници на сайта.';
$BL['be_profile_label_title']           = 'титла';
$BL['be_profile_label_firstname']       = 'име';
$BL['be_profile_label_name']            = 'фамилия';
$BL['be_profile_label_company']         = 'фирма';
$BL['be_profile_label_street']          = 'улица';
$BL['be_profile_label_city']            = 'град';
$BL['be_profile_label_state']           = 'провинция, щат';
$BL['be_profile_label_zip']             = 'пощенски код';
$BL['be_profile_label_country']         = 'държава';
$BL['be_profile_label_phone']           = 'телефон';
$BL['be_profile_label_fax']             = 'факс';
$BL['be_profile_label_cellphone']       = 'мобилен';
$BL['be_profile_label_signature']       = 'подпис';
$BL['be_profile_label_notes']           = 'забележки';
$BL['be_profile_label_profession']      = 'професия';
$BL['be_profile_label_newsletter']      = 'блюетин';
$BL['be_profile_text_newsletter']       = 'Искам да получавам общият бюлетин на phpwcms.';
$BL['be_profile_label_public']          = 'публичен';
$BL['be_profile_text_public']           = 'Всеки може да види профилът ми.';
$BL['be_profile_label_button']          = 'обнови личните ми данни';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'вашата информация';
$BL['be_profile_account_text']          = 'Обикновенно не е необходимо да сменяте потребителското си име.<br />Би трябвало да сменяте паролата си от време на време за да имате по-високо ниво на сигурност.';
$BL['be_profile_label_err']             = 'моля проверете информацията';
$BL['be_profile_label_username']        = 'потребителско име';
$BL['be_profile_label_newpass']         = 'нова парола';
$BL['be_profile_label_repeatpass']      = 'повторно парола';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'обнови информацията';
$BL['be_profile_label_lang']            = 'език';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'обработи файловете получени по ftp';
$BL['be_ftptakeover_mark']              = 'маркирай';
$BL['be_ftptakeover_available']         = 'достъпните файлове';
$BL['be_ftptakeover_size']              = 'размер';
$BL['be_ftptakeover_nofile']            = 'няма файлове &#8211; качете поне един по ftp';
$BL['be_ftptakeover_all']               = 'Всички';
$BL['be_ftptakeover_directory']         = 'папка';
$BL['be_ftptakeover_rootdir']           = 'главна директория';
$BL['be_ftptakeover_needed']            = 'задължително!!! (трябва да изберете поне едно поле)';
$BL['be_ftptakeover_optional']          = 'не задължително';
$BL['be_ftptakeover_keywords']          = 'ключови думи';
$BL['be_ftptakeover_additional']        = 'допълнително';
$BL['be_ftptakeover_longinfo']          = 'информация';
$BL['be_ftptakeover_status']            = 'статус';
$BL['be_ftptakeover_active']            = 'активен';
$BL['be_ftptakeover_public']            = 'публичен';
$BL['be_ftptakeover_createthumb']       = 'създай миниатюра';
$BL['be_ftptakeover_button']            = 'обработи избраните файлове';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'файл-център';
$BL['be_ftab_createnew']                = 'създай нова папка в главната';
$BL['be_ftab_paste']                    = 'вмъкни файла в главната папка';
$BL['be_ftab_disablethumb']             = 'не показвай миниатюри в списъците';
$BL['be_ftab_enablethumb']              = 'показвай миниатюри в списъците';
$BL['be_ftab_private']                  = 'лични&nbsp;файлове';
$BL['be_ftab_public']                   = 'публични&nbsp;файлове';
$BL['be_ftab_search']                   = 'търсене';
$BL['be_ftab_trash']                    = 'кошче';
$BL['be_ftab_open']                     = 'отваря всички папки';
$BL['be_ftab_close']                    = 'затваря всички отворени папки';
$BL['be_ftab_upload']                   = 'качване на файл в главната папка';
$BL['be_ftab_filehelp']                 = 'отваря помощ';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'главна папка';
$BL['be_fpriv_title']                   = 'създава нова папка';
$BL['be_fpriv_inside']                  = 'влиза';
$BL['be_fpriv_error']                   = 'грешка: напишете името на папката';
$BL['be_fpriv_name']                    = 'име';
$BL['be_fpriv_status']                  = 'статус';
$BL['be_fpriv_button']                  = 'създай нова папка';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'редактирай папката';
$BL['be_fpriv_newname']                 = 'ново име';
$BL['be_fpriv_updatebutton']            = 'обнови информацията за папката';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Изберете файл който искате да качите';
$BL['be_fprivup_err2']                  = 'Размера на каченият файл е по-голям от ';
$BL['be_fprivup_err3']                  = 'Грешка при качването на файла';
$BL['be_fprivup_err4']                  = 'Грешка при създаване на потребителска папка.';
$BL['be_fprivup_err5']                  = 'няма миниатюри';
$BL['be_fprivup_err6']                  = 'Не опитвайте пак - това е грешка на сървъра! Свържете се с <a href="mailto:{VAL}">администратора</a> колкото е възможно по-бързо!';
$BL['be_fprivup_title']                 = 'качване на файл';
$BL['be_fprivup_button']                = 'качване на файл';
$BL['be_fprivup_upload']                = 'качване';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'реактиране на информацията за файла';
$BL['be_fprivedit_filename']            = 'име на файла';
$BL['be_fprivedit_created']             = 'създаден';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'върни оригиналното име';
$BL['be_fprivedit_clockwise']           = 'завърти миниатюрата по часовниковата стрелка [+90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'завърти миниатюрата обратно на часовниковата стрелка [-90&deg;]';
$BL['be_fprivedit_button']              = 'обнови информацията за файла';
$BL['be_fprivedit_size']                = 'размер';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'качване файла в папка';
$BL['be_fprivfunc_makenew']             = 'създаване на нова папка вътре';
$BL['be_fprivfunc_paste']               = 'вмъкване файл от Обмена в папката';
$BL['be_fprivfunc_edit']                = 'редактиране на папка';
$BL['be_fprivfunc_cactive']             = 'активен/неактивен';
$BL['be_fprivfunc_cpublic']             = 'публичен/личен';
$BL['be_fprivfunc_deldir']              = 'изтриване на папка';
$BL['be_fprivfunc_jsdeldir']            = 'Наистина ли искате \nда изтриете папката';
$BL['be_fprivfunc_notempty']            = 'папката {VAL} не е празна!';
$BL['be_fprivfunc_opendir']             = 'отваряне на папка';
$BL['be_fprivfunc_closedir']            = 'затваряне на папка';
$BL['be_fprivfunc_dlfile']              = 'сваляне на файл';
$BL['be_fprivfunc_clipfile']            = 'в Обмена';
$BL['be_fprivfunc_cutfile']             = 'изрязва';
$BL['be_fprivfunc_editfile']            = 'редактира информацията за файла';
$BL['be_fprivfunc_cactivefile']         = 'активен/неактивен';
$BL['be_fprivfunc_cpublicfile']         = 'публичен/личен';
$BL['be_fprivfunc_movetrash']           = 'в боклука';
$BL['be_fprivfunc_jsmovetrash1']        = 'Наистина ли искате да сложите';
$BL['be_fprivfunc_jsmovetrash2']        = 'в боклука?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'няма лични файлове или папки';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'потребител';
$BL['be_fpublic_nofiles']               = 'няма публични файлове или папки';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'боклукът е празен';
$BL['be_ftrash_show']                   = 'покажи личните файлове';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Искате да въстановите {VAL} \nи да го преместите в личният списък?';
$BL['be_ftrash_delete']                 = 'Искате да изтриете {VAL}?';
$BL['be_ftrash_undo']                   = 'въстановаване (undo trash)';
$BL['be_ftrash_delfinal']               = 'окончателно изтриване';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'полето за търсене е празно.';
$BL['be_fsearch_title']                 = 'търсене на файлове';
$BL['be_fsearch_infotext']              = 'Това е търсене на информация за файла. Търсенето търси в ключови думи, ,<br />имена на файлове и информация за файловете. No support for wildcards. Separate multiple search<br />words with a blank. Select AND/OR and what files to search for: personal/public.';
$BL['be_fsearch_nonfound']              = 'не са намерени файлове. коригирайте критериите си!';
$BL['be_fsearch_fillin']                = 'моля запълнете по-гоpното поле.';
$BL['be_fsearch_searchlabel']           = 'търси за';
$BL['be_fsearch_startsearch']           = 'търсене';
$BL['be_fsearch_and']                   = 'И';
$BL['be_fsearch_or']                    = 'ИЛИ';
$BL['be_fsearch_all']                   = 'всички файлове';
$BL['be_fsearch_personal']              = 'лични';
$BL['be_fsearch_public']                = 'публични';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'вътрешен чат';
$BL['be_chat_info']                     = 'Here you can chat with other phpwcms backend users about everything you want. This medium is for realtime speaking but you can also let a message that everybody can read. If you want to exchange ideas with others use the discussion please (later phpwcms version).';
$BL['be_chat_start']                    = 'натисни тук за да започне чата';
$BL['be_chat_lines']                    = 'chat lines';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'център за съобщения';
$BL['be_msg_new']                       = 'нови';
$BL['be_msg_old']                       = 'стари';
$BL['be_msg_senttop']                   = 'изпратени';
$BL['be_msg_del']                       = 'изтрити';
$BL['be_msg_from']                      = 'от';
$BL['be_msg_subject']                   = 'относно';
$BL['be_msg_date']                      = 'дата/час';
$BL['be_msg_close']                     = 'затвори съобщението';
$BL['be_msg_create']                    = 'създай ново съобщение';
$BL['be_msg_reply']                     = 'отговори на това съобщение';
$BL['be_msg_move']                      = 'премести това съобщение в боклука';
$BL['be_msg_unread']                    = 'непрочетени или нови съобщения';
$BL['be_msg_lastread']                  = 'последни {VAL} прочетени съобщения';
$BL['be_msg_lastsent']                  = 'последни {VAL} изпратени съобщения';
$BL['be_msg_marked']                    = 'съобщения маркиране за изтриване (в боклука)';
$BL['be_msg_nomsg']                     = 'няма съобщения в тази папка';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'изпращач';
$BL['be_msg_on']                        = 'на';
$BL['be_msg_msg']                       = 'съобщения';
$BL['be_msg_err1']                      = 'забравили сте да укажете получател...';
$BL['be_msg_err2']                      = 'запълнете полето относно (получателят може по-лесно да се оправи с вашето съобщение)';
$BL['be_msg_err3']                      = 'няма смисъл да се изпраща без самото съобщение ;-)';
$BL['be_msg_sent']                      = 'съобщението бе изпратено!';
$BL['be_msg_fwd']                       = 'ще бъдете пренасочени към центъра за съобщения или';
$BL['be_msg_newmsgtitle']               = 'писане на ново съобщение';
$BL['be_msg_err']                       = 'грешка при изпращането';
$BL['be_msg_sendto']                    = 'изпрати съобщението';
$BL['be_msg_available']                 = 'списък с получатели';
$BL['be_msg_all']                       = 'изпрати съобщението на всички избрани получатели';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'абониране за бюлетин';
$BL['be_newsletter_titleedit']          = 'редактиране на абонамента за бюлетина';
$BL['be_newsletter_new']                = 'създай ново';
$BL['be_newsletter_add']                = 'добави&nbsp;абонамент&nbsp;за&nbsp;бюлетин';
$BL['be_newsletter_name']               = 'име';
$BL['be_newsletter_info']               = 'информация';
$BL['be_newsletter_button_save']        = 'запази бюлетина';
$BL['be_newsletter_button_cancel']      = 'отказ';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'грешно потребителско име, изберете друго';
$BL['be_admin_usr_err2']                = 'потребителското име е празно (задължително)';
$BL['be_admin_usr_err3']                = 'паролата е празна (задължитела)';
$BL['be_admin_usr_err4']                = "email е невалиден";
$BL['be_admin_usr_err']                 = 'грешка';
$BL['be_admin_usr_mailsubject']         = 'Добре дошли в phpwcms backend';
$BL['be_admin_usr_mailbody']            = "ДОБРЕ ДОШЛИ PHPWCMS BACKEND\n\n    потребителско име: {LOGIN}\n    парола: {PASSWORD}\n\n\nможе да влезето тука: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'добавяне на нов потребител';
$BL['be_admin_usr_realname']            = 'истинско име';
$BL['be_admin_usr_setactive']           = 'активирай потребителят';
$BL['be_admin_usr_iflogin']             = 'ако е отбелязано потребителя може да влиза';
$BL['be_admin_usr_isadmin']             = 'потребителя е администратор';
$BL['be_admin_usr_ifadmin']             = 'ако отбележите, потребителя ще получи права на администратор';
$BL['be_admin_usr_verify']              = 'проверка';
$BL['be_admin_usr_sendemail']           = 'изпрати email на новият потребител с информация';
$BL['be_admin_usr_button']              = 'изпрати данните на потребителя';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'редактирай потребителя';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - данните за потребитеят са променени';
$BL['be_admin_usr_emailbody']           = "PHPWCMS USER ACCOUNT INFORMATION CHANGED\n\n    потребителско име: {LOGIN}\n    парола: {PASSWORD}\n\n\nМоже да влезнете от тука: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[НЯМА ПРОМЯНА - ИЗПОЛЗВАЙТЕ ПАРОЛАТА СИ]';
$BL['be_admin_usr_ebutton']             = 'обнови данните за потребителя';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms списък с потребители';
$BL['be_admin_usr_ldel']                = 'Внимание!&#13Потребителят ще бъде изтрит';
$BL['be_admin_usr_create']              = 'създай нов потребител';
$BL['be_admin_usr_editusr']             = 'редактирай потребителят';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'структура на сайта';
$BL['be_admin_struct_child']            = '(подкатегория на)';
$BL['be_admin_struct_index']            = 'index (начало на сайта)';
$BL['be_admin_struct_cat']              = 'заглавие на категорията';
$BL['be_admin_struct_hide1']            = 'скриване';
$BL['be_admin_struct_hide2']            = 'тази&nbsp;категория&nbsp;в&nbsp;меню';
$BL['be_admin_struct_info']             = 'информация за категорията';
$BL['be_admin_struct_template']         = 'шаблон';
$BL['be_admin_struct_alias']            = 'пзевдоним на категорията';
$BL['be_admin_struct_visible']          = 'видима';
$BL['be_admin_struct_button']           = 'изпрати данните за категорията';
$BL['be_admin_struct_close']            = 'затвори';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'категории на файлове';
$BL['be_admin_fcat_err']                = 'името на категорията е празно!';
$BL['be_admin_fcat_name']               = 'име на категорията';
$BL['be_admin_fcat_needed']             = 'нужно';
$BL['be_admin_fcat_button1']            = 'обнови';
$BL['be_admin_fcat_button2']            = 'създай';
$BL['be_admin_fcat_delmsg']             = 'Наистина ли искате \nда изтриете ключовият файл?';
$BL['be_admin_fcat_fcat']               = 'Категория на файла';
$BL['be_admin_fcat_err1']               = 'името на ключовият файл е празно!';
$BL['be_admin_fcat_fkeyname']           = 'име на ключов файл';
$BL['be_admin_fcat_exit']               = 'exit editing';
$BL['be_admin_fcat_addkey']             = 'add new key';
$BL['be_admin_fcat_editcat']            = 'edit category name';
$BL['be_admin_fcat_delcatmsg']          = 'Do you really want\nto delete file category?';
$BL['be_admin_fcat_delcat']             = 'delete file category';
$BL['be_admin_fcat_delkey']             = 'delete file key name';
$BL['be_admin_fcat_editkey']            = 'edit key';
$BL['be_admin_fcat_addcat']             = 'create new file category';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend setup: разположение на страницата';
$BL['be_admin_page_align']              = 'подравняване';
$BL['be_admin_page_align_left']         = 'стандартно подравняване (ляво) на цялото съдържание ан страницата';
$BL['be_admin_page_align_center']       = 'центриране на цялата страница';
$BL['be_admin_page_align_right']        = 'подравняване от дясно на цялата страница';
$BL['be_admin_page_margin']             = 'граница';
$BL['be_admin_page_top']                = 'горе';
$BL['be_admin_page_bottom']             = 'долу';
$BL['be_admin_page_left']               = 'ляво';
$BL['be_admin_page_right']              = 'дясно';
$BL['be_admin_page_bg']                 = 'фон';
$BL['be_admin_page_color']              = 'цвят';
$BL['be_admin_page_height']             = 'височина';
$BL['be_admin_page_width']              = 'широчина';
$BL['be_admin_page_main']               = 'основен';
$BL['be_admin_page_leftspace']          = 'ляво растояние';
$BL['be_admin_page_rightspace']         = 'дясно растояние';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'картинка';
$BL['be_admin_page_text']               = 'текст';
$BL['be_admin_page_link']               = 'връзки';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'посетена';
$BL['be_admin_page_pagetitle']          = 'заглавие&nbsp;на&nbsp;страницата';
$BL['be_admin_page_addtotitle']         = 'добави&nbsp;към&nbsp;заглавието';
$BL['be_admin_page_category']           = 'категорията';
$BL['be_admin_page_articlename']        = 'името&nbsp;на&nbsp;статията';
$BL['be_admin_page_blocks']             = 'блокове';
$BL['be_admin_page_allblocks']          = 'всички блокове';
$BL['be_admin_page_col1']               = '3 колонно разположение';
$BL['be_admin_page_col2']               = '2 колонно разположение (главна колона в дясно, навигация в ляво)';
$BL['be_admin_page_col3']               = '2 колонно разположение (главна колона в ляво, навигация в дясно)';
$BL['be_admin_page_col4']               = '1 колонно разположение';
$BL['be_admin_page_header']             = 'заглавие';
$BL['be_admin_page_footer']             = 'footer';
$BL['be_admin_page_topspace']           = 'горен&nbsp;отстъп';
$BL['be_admin_page_bottomspace']        = 'долен&nbsp;отстъп';
$BL['be_admin_page_button']             = 'запази разположението на страницата';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend setup: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'save css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend setup: шаблони';
$BL['be_admin_tmpl_default']            = 'по подразбиране';
$BL['be_admin_tmpl_add']                = 'добави&nbsp;шаблон';
$BL['be_admin_tmpl_edit']               = 'редактиране на шаблон';
$BL['be_admin_tmpl_new']                = 'създаване на нов шаблон';
$BL['be_admin_tmpl_css']                = 'css файл';
$BL['be_admin_tmpl_head']               = 'html глава';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'грешка';
$BL['be_admin_tmpl_button']             = 'запази шаблона';
$BL['be_admin_tmpl_name']               = 'име';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'структура на сайта и списък със статии';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'заглавието на статията е празно';
$BL['be_article_err2']                  = 'началната дата е грешна - избрано "сега"';
$BL['be_article_err3']                  = 'крайната дата е грешна - избрано "сега"';
$BL['be_article_title1']                = 'информация за статията';
$BL['be_article_cat']                   = 'катеогрия';
$BL['be_article_atitle']                = 'заглавие';
$BL['be_article_asubtitle']             = 'подзаглавие';
$BL['be_article_abegin']                = 'започва';
$BL['be_article_aend']                  = 'свършва';
$BL['be_article_aredirect']             = 'препраща към';
$BL['be_article_akeywords']             = 'ключови думи';
$BL['be_article_asummary']              = 'накратко';
$BL['be_article_abutton']               = 'създаване на статията';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'крайната дата е грешна - избрано "сега" + 1 седмица';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'редактиране на данните за статията';
$BL['be_article_eslastedit']            = 'последна редакция';
$BL['be_article_esnoupdate']            = 'формата не е променена';
$BL['be_article_esbutton']              = 'обнови статията';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'раздел';
$BL['be_article_cnt_type']              = 'content type';
$BL['be_article_cnt_space']             = 'място';
$BL['be_article_cnt_before']            = 'преди';
$BL['be_article_cnt_after']             = 'след';
$BL['be_article_cnt_top']               = 'отгоре';
$BL['be_article_cnt_ctitle']            = 'заглавие на раздела';
$BL['be_article_cnt_back']              = 'пълна информация за статията';
$BL['be_article_cnt_button1']           = 'обнови раздела';
$BL['be_article_cnt_button2']           = 'създай раздел';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'информация за статията';
$BL['be_article_cnt_ledit']             = 'редактирай статията';
$BL['be_article_cnt_lvisible']          = 'видима/невидима';
$BL['be_article_cnt_ldel']              = 'изтриване на статията';
$BL['be_article_cnt_ldeljs']            = 'Искате да изтриете статията?';
$BL['be_article_cnt_redirect']          = 'препратка';
$BL['be_article_cnt_edited']            = 'редактирана от';
$BL['be_article_cnt_start']             = 'начална дата';
$BL['be_article_cnt_end']               = 'крайна дата';
$BL['be_article_cnt_add']               = 'добавяне на нов раздел';
$BL['be_article_cnt_up']                = 'местене на раздела нагоре';
$BL['be_article_cnt_down']              = 'местене на раздела надолу';
$BL['be_article_cnt_edit']              = 'редактиране на раздела';
$BL['be_article_cnt_delpart']           = 'изтриване на раздела';
$BL['be_article_cnt_delpartjs']         = 'Наистина ли искате да изтриете раздела?';
$BL['be_article_cnt_center']            = 'прес център';

// content forms
$BL['be_cnt_plaintext']                 = 'само текст';
$BL['be_cnt_htmltext']                  = 'html текст';
$BL['be_cnt_image']                     = 'картинка';
$BL['be_cnt_position']                  = 'позиция';
$BL['be_cnt_pos0']                      = 'Горе, ляво';
$BL['be_cnt_pos1']                      = 'Горе, център';
$BL['be_cnt_pos2']                      = 'Горе, дясно';
$BL['be_cnt_pos3']                      = 'Долу, ляво';
$BL['be_cnt_pos4']                      = 'Долу, център';
$BL['be_cnt_pos5']                      = 'Долу, дясно';
$BL['be_cnt_pos6']                      = 'В текста, ляво';
$BL['be_cnt_pos7']                      = 'В текста, дясно';
$BL['be_cnt_pos0i']                     = 'подравнява картинката от ляво и над текста';
$BL['be_cnt_pos1i']                     = 'центрира картинката над текста';
$BL['be_cnt_pos2i']                     = 'подравнява картинката от дясно и над текста';
$BL['be_cnt_pos3i']                     = 'подравнява картинката от ляво и под текста';
$BL['be_cnt_pos4i']                     = 'центрира картинката под текста';
$BL['be_cnt_pos5i']                     = 'подравнява картинката от дясно и под текста';
$BL['be_cnt_pos6i']                     = 'подравнява картинката от ляво и в текста';
$BL['be_cnt_pos7i']                     = 'подравнява картинката от дясно и в текста';
$BL['be_cnt_maxw']                      = 'max.&nbsp;широчина';
$BL['be_cnt_maxh']                      = 'max.&nbsp;височина';
$BL['be_cnt_enlarge']                   = 'щракане&nbsp;увеличава';
$BL['be_cnt_caption']                   = 'заглавие';
$BL['be_cnt_subject']                   = 'относно';
$BL['be_cnt_recipient']                 = 'получател';
$BL['be_cnt_buttontext']                = 'текст на бутона';
$BL['be_cnt_sendas']                    = 'изпрати като';
$BL['be_cnt_text']                      = 'текст';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'form fields';
$BL['be_cnt_code']                      = 'код';
$BL['be_cnt_infotext']                  = 'info&nbsp;text';
$BL['be_cnt_subscription']              = 'абонамент';
$BL['be_cnt_labelemail']                = 'label&nbsp;email';
$BL['be_cnt_tablealign']                = 'table&nbsp;align';
$BL['be_cnt_labelname']                 = 'label&nbsp;name';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;subscr.';
$BL['be_cnt_allsubsc']                  = 'all&nbsp;subscr.';
$BL['be_cnt_default']                   = 'default';
$BL['be_cnt_left']                      = 'ляво';
$BL['be_cnt_center']                    = 'център';
$BL['be_cnt_right']                     = 'дясно';
$BL['be_cnt_buttontext']                = 'button&nbsp;text';
$BL['be_cnt_successtext']               = 'success&nbsp;text';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'change.email';
$BL['be_cnt_openimagebrowser']          = 'отвори браузърът за картинки';
$BL['be_cnt_openfilebrowser']           = 'отвори браузърът за файлове';
$BL['be_cnt_sortup']                    = 'премести нагоре';
$BL['be_cnt_sortdown']                  = 'премести надолу';
$BL['be_cnt_delimage']                  = 'махни избраната картинка';
$BL['be_cnt_delfile']                   = 'махни избраният файл';
$BL['be_cnt_delmedia']                  = 'махни избраната медия';
$BL['be_cnt_column']                    = 'колона';
$BL['be_cnt_imagespace']                = 'image&nbsp;space';
$BL['be_cnt_directlink']                = 'директна връзка';
$BL['be_cnt_target']                    = 'цел';
$BL['be_cnt_target1']                   = 'в нов прозорец';
$BL['be_cnt_target2']                   = 'in parent frame of the window';
$BL['be_cnt_target3']                   = 'in same window without frames';
$BL['be_cnt_target4']                   = 'in the same frame or window';
$BL['be_cnt_bullet']                    = 'списък (таблица)';
$BL['be_cnt_ullist']                    = 'списък';
$BL['be_cnt_ullist_desc']               = '~ = 1во ниво, &nbsp; ~~ = 2ро ниво, &nbsp; т.н.';
$BL['be_cnt_linklist']                  = 'списък линкове';
$BL['be_cnt_plainhtml']                 = 'чист html';
$BL['be_cnt_files']                     = 'файлове';
$BL['be_cnt_description']               = 'описание';
$BL['be_cnt_linkarticle']               = 'link article';
$BL['be_cnt_articles']                  = 'статии';
$BL['be_cnt_movearticleto']             = 'премести избраната статия в списъка със статии';
$BL['be_cnt_removearticleto']           = 'махни избраната статия от списъка със статии';
$BL['be_cnt_mediatype']                 = 'тип медия';
$BL['be_cnt_control']                   = 'контрол';
$BL['be_cnt_showcontrol']               = 'показвай панела с контроли';
$BL['be_cnt_autoplay']                  = 'автостарт';
$BL['be_cnt_source']                    = 'източник';
$BL['be_cnt_internal']                  = 'вътрешен';
$BL['be_cnt_openmediabrowser']          = 'отвори броузера за медия';
$BL['be_cnt_external']                  = 'външен';
$BL['be_cnt_mediapos0']                 = 'ляво (по подразбиране)';
$BL['be_cnt_mediapos1']                 = 'централно';
$BL['be_cnt_mediapos2']                 = 'дясно';
$BL['be_cnt_mediapos3']                 = 'block, left';
$BL['be_cnt_mediapos4']                 = 'block, right';
$BL['be_cnt_mediapos0i']                = 'подравни медията отляво и над текста';
$BL['be_cnt_mediapos1i']                = 'центрирай медията над текста';
$BL['be_cnt_mediapos2i']                = 'подравни медията отдясно и над текста';
$BL['be_cnt_mediapos3i']                = 'подравни медията отляво и в текста';
$BL['be_cnt_mediapos4i']                = 'подравни медията отдясно и в текста';
$BL['be_cnt_setsize']                   = 'размер';
$BL['be_cnt_set1']                      = '160x120px';
$BL['be_cnt_set2']                      = '240x180px';
$BL['be_cnt_set3']                      = '320x240px';
$BL['be_cnt_set4']                      = '480x360px';
$BL['be_cnt_set5']                      = 'махни размера';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'създай ново разположение на страница';
$BL['be_admin_page_name']               = 'име';
$BL['be_admin_page_edit']               = 'редактирай разположението';
$BL['be_admin_page_render']             = 'rendering';
$BL['be_admin_page_table']              = 'таблица';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'ръчно';
$BL['be_admin_page_custominfo']         = 'от main блока на шаблона';
$BL['be_admin_tmpl_layout']             = 'разположение';
$BL['be_admin_tmpl_nolayout']           = 'Няма разположения!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'търсене';
$BL['be_cnt_results']                   = 'резултати';
$BL['be_cnt_results_per_page']          = 'на&nbsp;страница (ако е празно показва всички)';
$BL['be_cnt_opennewwin']                = 'отваря в ное прозорец';
$BL['be_cnt_searchlabeltext']           = 'това са предефинирани текстове и стойности на формата за търсене и резултата от търсенето ще бъдат показвани когато има повече от зададените "резултати на страница".';
$BL['be_cnt_input']                     = 'input';
$BL['be_cnt_style']                     = 'style';
$BL['be_cnt_result']                    = 'резултат';
$BL['be_cnt_next']                      = 'следващ';
$BL['be_cnt_previous']                  = 'предишен';
$BL['be_cnt_align']                     = 'подравняване';
$BL['be_cnt_searchformtext']            = 'следните текстове ще са показвани като се отвори формата за търсене или няма резултати от търсенето.';
$BL['be_cnt_intro']                     = 'въведение';
$BL['be_cnt_noresult']                  = 'няма резултати';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'извади от строя';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'собственик на статията';
$BL['be_article_adminuser']             = 'административен потребител';
$BL['be_article_username']              = 'автор';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'видим само за влезналите потребители';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'меню за статии';
$BL['be_cnt_sitelevel']                 = 'ниво на сайта';
$BL['be_cnt_sitecurrent']               = 'current site level';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'backend default text';
$BL['be_ctype_ecard']                   = 'e-card';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'title/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail tmpl';
$BL['be_cnt_ecard_image']               = 'e-card image';
$BL['be_cnt_ecard_title']               = 'e-card title';
$BL['be_cnt_alignment']                 = 'alignment';
$BL['be_cnt_ecardform']                 = 'form tmpl';
$BL['be_cnt_ecardform_err']             = 'All fields marked * are obligatory';
$BL['be_cnt_ecardform_sender']          = 'Sender';
$BL['be_cnt_ecardform_recipient']       = 'Recipient';
$BL['be_cnt_ecardform_name']            = 'Name';
$BL['be_cnt_ecardform_msgtext']         = 'Your message to recipient';
$BL['be_cnt_ecardform_button']          = 'send e-card';
$BL['be_cnt_ecardsend']                 = 'sent tmpl';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Backend default startup text';
$BL['be_admin_startup_text']            = 'startup text';
$BL['be_admin_startup_button']          = 'save stratup text';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'guestbook/comm.';
$BL['be_cnt_guestbook_listing']         = 'listing';
$BL['be_cnt_guestbook_listing_all']     = 'list&nbsp;all&nbsp;entries';
$BL['be_cnt_guestbook_list']            = 'list';
$BL['be_cnt_guestbook_perpage']         = 'per&nbsp;page';
$BL['be_cnt_guestbook_form']            = 'form';
$BL['be_cnt_guestbook_signed']          = 'signed';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'before';
$BL['be_cnt_guestbook_after']           = 'after';
$BL['be_cnt_guestbook_entry']           = 'entry';
$BL['be_cnt_guestbook_edit']            = 'edit';
$BL['be_cnt_ecardform_selector']        = 'selector';
$BL['be_cnt_ecardform_radiobutton']     = 'radio button';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript functionality';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'top article count';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'newsletter';
$BL['be_newsletter_addnl']              = 'add newsletter';
$BL['be_newsletter_titleeditnl']        = 'edit newsletter';
$BL['be_newsletter_newnl']              = 'create new';
$BL['be_newsletter_button_savenl']      = 'save newsletter';
$BL['be_newsletter_fromname']           = 'from name';
$BL['be_newsletter_fromemail']          = 'from email';
$BL['be_newsletter_replyto']            = 'reply email';
$BL['be_newsletter_changed']            = 'last change';
$BL['be_newsletter_placeholder']        = 'placeholder';
$BL['be_newsletter_htmlpart']           = 'HTML newletter content';
$BL['be_newsletter_textpart']           = 'TEXT newletter content';
$BL['be_newsletter_allsubscriptions']   = 'all subscriptions';
$BL['be_newsletter_verifypage']         = 'verify link';
$BL['be_newsletter_open']               = 'HTML and TEXT input';
$BL['be_newsletter_open1']              = '(click on image to open)';
$BL['be_newsletter_sendnow']            = 'Send newsletter';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Attention!</strong> Sending a newsletter to multiple recipients is very hazardous. Recipients should have been verified otherwise you will send potential spam. Think twice before you send the newsletter. Check your newsletter by sending a test.';
$BL['be_newsletter_attention1']         = 'If you have made changes in above newsletter datas please save it first otherwise these changes will not be used.';
$BL['be_newsletter_testemail']          = 'test email';
$BL['be_newsletter_sendnlbutton']       = 'send newsletter';
$BL['be_newsletter_sendprocess']        = 'send process';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Attention!</strong> Please do not stop the send process. Otherwise it is possible that you will send the newsletter more than twice to a recipient. When sending fails all non achieved recipient are stored in a session array and will be used if you send again immediately.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">the test email address <strong>###TEST###</strong> is NOT valid!<br />&nbsp;<br />Try again please!';
$BL['be_newsletter_to']                 = 'Recipients';
$BL['be_newsletter_ready']              = 'sending newsletter: DONE';
$BL['be_newsletter_readyfailed']        = 'Failed newsletter sending to';
$BL['be_subnav_msg_subscribers']        = 'newsletter subscribers';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'карта на стайта';
$BL['be_cnt_sitemap_catimage']          = 'икона за нивото';
$BL['be_cnt_sitemap_articleimage']      = 'икона за статия';
$BL['be_cnt_sitemap_display']           = 'показвай';
$BL['be_cnt_sitemap_structuronly']      = 'само нива от структурата';
$BL['be_cnt_sitemap_structurarticle']   = 'структура и статии';
$BL['be_cnt_sitemap_catclass']          = 'class на ниво';
$BL['be_cnt_sitemap_articleclass']      = 'class на статия';
$BL['be_cnt_sitemap_count']             = 'брояч';
$BL['be_cnt_sitemap_classcount']        = 'добавяй към името на class-а';
$BL['be_cnt_sitemap_noclasscount']      = 'не добавяй към името на class-а';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'bid';
$BL['be_cnt_bid_bidtext']               = 'bid text';
$BL['be_cnt_bid_sendtext']              = 'sent text';
$BL['be_cnt_bid_verifiedtext']          = 'verified text';
$BL['be_cnt_bid_errortext']             = 'bid deleted';
$BL['be_cnt_bid_verifyemail']           = 'verify email';
$BL['be_cnt_bid_startbid']              = 'start bid';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'increase&nbsp;by';

