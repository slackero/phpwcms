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


// Language: Russian, Country Code: ru
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'на сайте';

// Login Page
$BL["login_text"]                       = 'Введите Ваш логин';
$BL['login_error']                      = 'Ошибка!';
$BL["login_username"]                   = 'Имя';
$BL["login_userpass"]                   = 'пароль';
$BL["login_button"]                     = 'Войти';
$BL["login_lang"]                       = 'язык сообщений';

// phpwcms.php
$BL['be_nav_logout']                    = 'ВЫЙТИ';
$BL['be_nav_articles']                  = 'СТАТЬИ';
$BL['be_nav_files']                     = 'ФАЙЛЫ';
$BL['be_nav_modules']                   = 'МОДУЛИ';
$BL['be_nav_messages']                  = 'СООБЩЕНИЯ';
$BL['be_nav_chat']                      = 'ЧАТ';
$BL['be_nav_profile']                   = 'ПРОФИЛЬ';
$BL['be_nav_admin']                     = 'АДМИНИСТРАТОР';
$BL['be_nav_discuss']                   = 'ДИСКУССИЯ';

$BL['be_page_title']                    = 'phpwcms администрирование';

$BL['be_subnav_article_center']         = 'центр статей';
$BL['be_subnav_article_new']            = 'новая статья';
$BL['be_subnav_file_center']            = 'файл-центр';
$BL['be_subnav_file_ftptakeover']       = 'ftp takeover';
$BL['be_subnav_mod_artists']            = 'автор, категория, жанр';
$BL['be_subnav_msg_center']             = 'центр сообщений';
$BL['be_subnav_msg_new']                = 'новое сообщение';
$BL['be_subnav_msg_newsletter']         = 'подписка';
$BL['be_subnav_chat_main']              = 'главная страница чата';
$BL['be_subnav_chat_internal']          = 'внутренний чат';
$BL['be_subnav_profile_login']          = 'информация пользователя';
$BL['be_subnav_profile_personal']       = 'личные данные';
$BL['be_subnav_admin_pagelayout']       = 'макет страницы';
$BL['be_subnav_admin_templates']        = 'шаблоны';
$BL['be_subnav_admin_css']              = 'основной css';
$BL['be_subnav_admin_sitestructure']    = 'структура сайта';
$BL['be_subnav_admin_users']            = 'пользователи';
$BL['be_subnav_admin_filecat']          = 'категории файлов';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'номер статьи';
$BL['be_func_struct_preview']           = 'предпросмотр';
$BL['be_func_struct_edit']              = 'редактировать статью';
$BL['be_func_struct_sedit']             = 'редактировать категорию';
$BL['be_func_struct_cut']               = 'вырезать статью';
$BL['be_func_struct_nocut']             = 'отменить вырезать статью';
$BL['be_func_struct_svisible']          = 'сделать видимым/невидимым';
$BL['be_func_struct_spublic']           = 'сделать общим/личным';
$BL['be_func_struct_sort_up']           = 'поднять на уровень';
$BL['be_func_struct_sort_down']         = 'опустить на уровень';
$BL['be_func_struct_del_article']       = 'удалить стьтью';
$BL['be_func_struct_del_jsmsg']         = 'Вы действительно хотите \nудалить статью?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'создать новую статью в данной категории';
$BL['be_func_struct_paste_article']     = 'переместить статью в данную категорию';
$BL['be_func_struct_insert_level']      = 'создать категорию';
$BL['be_func_struct_paste_level']       = 'переместить в категорию';
$BL['be_func_struct_cut_level']         = 'вырезать категорию';
$BL['be_func_struct_no_cut']            = "Невозможно вырезать корневой уровень!";
$BL['be_func_struct_no_paste1']         = "Невозможно переместить!";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'that should paste in here';
$BL['be_func_struct_paste_cancel']      = 'отменить изменения структурного уровня';
$BL['be_func_struct_del_struct']        = 'удалить структурный уровень';
$BL['be_func_struct_del_sjsmsg']        = 'Вы действительно хотите \nудалить структурный уровень?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'открыть';
$BL['be_func_struct_close']             = 'закрыть';
$BL['be_func_struct_empty']             = 'очистить';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'только текст';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'код';
$BL['be_ctype_textimage']               = 'текст с рисунками';
$BL['be_ctype_images']                  = 'рисунки';
$BL['be_ctype_bulletlist']              = 'маркированный список';
$BL['be_ctype_link']                    = 'ссылки &amp; email';
$BL['be_ctype_linklist']                = 'список ссылок';
$BL['be_ctype_linkarticle']             = 'ссылки на статью';
$BL['be_ctype_multimedia']              = 'мультимедиа';
$BL['be_ctype_filelist']                = 'список файлов';
$BL['be_ctype_emailform']               = 'почтовая форма';
$BL['be_ctype_newsletter']              = 'рассылка';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Профиль успешно создан.';
$BL['be_profile_create_error']          = 'Во время создания профиля была обнаружена ошибка.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Данные профиля успешно обновлены.';
$BL['be_profile_update_error']          = 'Во время обновления была обнаружена ошибка.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'неправильное имя пользователя {VAL} ';
$BL['be_profile_account_err2']          = 'пароль слишком короткий (только {VAL} символы: не менее 5 символов)';
$BL['be_profile_account_err3']          = 'пароль должен совпадать с повторным паролем';
$BL['be_profile_account_err4']          = 'email {VAL} неправильный';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'ваши личные данные';
$BL['be_profile_data_text']             = 'Личные данные не обязательны для заполнения. Однако эта информация может быть полезна для других пользователей, если они захотят узнать о вас побольше, о ваших интересах и умениях. Если вы выбираете соответствующий флажок, пользователи могут увидеть ваши данные в общих полях или в статьях.';
$BL['be_profile_label_title']           = 'Титул';
$BL['be_profile_label_firstname']       = 'Имя';
$BL['be_profile_label_name']            = 'Фамилия';
$BL['be_profile_label_company']         = 'Компания';
$BL['be_profile_label_street']          = 'улица';
$BL['be_profile_label_city']            = 'город';
$BL['be_profile_label_state']           = 'область';
$BL['be_profile_label_zip']             = 'почтовый индекс';
$BL['be_profile_label_country']         = 'страна';
$BL['be_profile_label_phone']           = 'телефон';
$BL['be_profile_label_fax']             = 'факс';
$BL['be_profile_label_cellphone']       = 'мобильный телефон';
$BL['be_profile_label_signature']       = 'подпись';
$BL['be_profile_label_notes']           = 'прочее';
$BL['be_profile_label_profession']      = 'профессия';
$BL['be_profile_label_newsletter']      = 'рассылка';
$BL['be_profile_text_newsletter']       = 'Я хочу получать общую рассылку phpwcms.';
$BL['be_profile_label_public']          = 'открыто для всех';
$BL['be_profile_text_public']           = 'Любой может увидеть мои личные данные.';
$BL['be_profile_label_button']          = 'обновить личные данные';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Ваша информация пользователя';
$BL['be_profile_account_text']          = 'Обычно нет необходимости менять ваше имя пользователя.<br />Вам нужно только время от времени менять Ваш пароль для большей безопасности.';
$BL['be_profile_label_err']             = 'пожалуйста проверьте информацию';
$BL['be_profile_label_username']        = 'имя пользователя';
$BL['be_profile_label_newpass']         = 'новый пароль';
$BL['be_profile_label_repeatpass']      = 'повтор пароля';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'обновить данные пользователя';
$BL['be_profile_label_lang']            = 'язык';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'обработать файлы, загруженные по FTP';
$BL['be_ftptakeover_mark']              = 'обозначить';
$BL['be_ftptakeover_available']         = 'доступные файлы';
$BL['be_ftptakeover_size']              = 'размер';
$BL['be_ftptakeover_nofile']            = 'недоступен файл &#8211; Вам необходимо загрузить данный файл по FTP';
$BL['be_ftptakeover_all']               = 'Все';
$BL['be_ftptakeover_directory']         = 'директория';
$BL['be_ftptakeover_rootdir']           = 'корневая директория';
$BL['be_ftptakeover_needed']            = 'обязательно!!! (нужно выбрать хотя бы одно поле)';
$BL['be_ftptakeover_optional']          = 'необязательно';
$BL['be_ftptakeover_keywords']          = 'ключевые слова';
$BL['be_ftptakeover_additional']        = 'дополнительно';
$BL['be_ftptakeover_longinfo']          = 'информация';
$BL['be_ftptakeover_status']            = 'статус';
$BL['be_ftptakeover_active']            = 'активный';
$BL['be_ftptakeover_public']            = 'общий';
$BL['be_ftptakeover_createthumb']       = 'создать предпросмотр';
$BL['be_ftptakeover_button']            = 'обработать выбранные файлы';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'файл-центр';
$BL['be_ftab_createnew']                = 'создать новую директорию в корневом каталоге';
$BL['be_ftab_paste']                    = 'вставить из буфера обмена';
$BL['be_ftab_disablethumb']             = 'отключить предпросмотр в списке';
$BL['be_ftab_enablethumb']              = 'включить предпросмотр в списке';
$BL['be_ftab_private']                  = 'личные&nbsp;файлы';
$BL['be_ftab_public']                   = 'общие&nbsp;файлы';
$BL['be_ftab_search']                   = 'поиск';
$BL['be_ftab_trash']                    = 'корзина&nbsp;';
$BL['be_ftab_open']                     = 'открыть все директории';
$BL['be_ftab_close']                    = 'закрыть все открытые директории';
$BL['be_ftab_upload']                   = 'загрузить файл в корневой каталог';
$BL['be_ftab_filehelp']                 = 'открыть помощь';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'корневая директория';
$BL['be_fpriv_title']                   = 'создать новую директорию';
$BL['be_fpriv_inside']                  = 'зайти';
$BL['be_fpriv_error']                   = 'ошибка: назовите директорию';
$BL['be_fpriv_name']                    = 'название';
$BL['be_fpriv_status']                  = 'статус';
$BL['be_fpriv_button']                  = 'создать новую директорию';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'изменить директорию';
$BL['be_fpriv_newname']                 = 'новое название';
$BL['be_fpriv_updatebutton']            = 'обновить информацию о директории';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Выбрать файл для загрузки';
$BL['be_fprivup_err2']                  = 'Размер загружаемого файла больше чем';
$BL['be_fprivup_err3']                  = 'ошибка при загрузке файла';
$BL['be_fprivup_err4']                  = 'ошибка при создании пользовательской директории.';
$BL['be_fprivup_err5']                  = 'нет предпросмотра';
$BL['be_fprivup_err6']                  = 'Пожалуйста не пытайтесь снова - это ошибка сервера! Свяжитесь с <a href="mailto:{VAL}">вебмастером</a> как можно скорее!';
$BL['be_fprivup_title']                 = 'загрузить файлы';
$BL['be_fprivup_button']                = 'загрузить файлы';
$BL['be_fprivup_upload']                = 'загрузить';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'изменить файловую информацию';
$BL['be_fprivedit_filename']            = 'имя файла';
$BL['be_fprivedit_created']             = 'создан';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'вернуть начальное название файла';
$BL['be_fprivedit_clockwise']           = 'повернуть файл предпросмотра по часовой стрелке [начальный файл +90градусов;]';
$BL['be_fprivedit_cclockwise']          = 'повернуть файл предпросмотра против часовой стрелки [начальный файл -90градусов;]';
$BL['be_fprivedit_button']              = 'обновить информацию';
$BL['be_fprivedit_size']                = 'размер';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'загрузить файл в директорию';
$BL['be_fprivfunc_makenew']             = 'создать новую подкатегорию';
$BL['be_fprivfunc_paste']               = 'вставить файл из буфера обмена';
$BL['be_fprivfunc_edit']                = 'изменить директорию';
$BL['be_fprivfunc_cactive']             = 'включить активный/неактивный';
$BL['be_fprivfunc_cpublic']             = 'включить общий/частный';
$BL['be_fprivfunc_deldir']              = 'удалить директорию';
$BL['be_fprivfunc_jsdeldir']            = 'Вы действительно хотите \n удалить директорию?';
$BL['be_fprivfunc_notempty']            = 'директория {VAL} не пустая!';
$BL['be_fprivfunc_opendir']             = 'открыть директорию';
$BL['be_fprivfunc_closedir']            = 'закрыть директорию';
$BL['be_fprivfunc_dlfile']              = 'загрузить файл';
$BL['be_fprivfunc_clipfile']            = 'файл в буфер обмена';
$BL['be_fprivfunc_cutfile']             = 'вырезать';
$BL['be_fprivfunc_editfile']            = 'изменить файловую информацию';
$BL['be_fprivfunc_cactivefile']         = 'сделать видимым/невидимым';
$BL['be_fprivfunc_cpublicfile']         = 'сделать общим/личным';
$BL['be_fprivfunc_movetrash']           = 'отправить в корзину';
$BL['be_fprivfunc_jsmovetrash1']        = 'Вы действительно хотите отправить';
$BL['be_fprivfunc_jsmovetrash2']        = 'в корзину?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'нет личных файлов или папок';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'пользователь';
$BL['be_fpublic_nofiles']               = 'нет общих файлов или папок';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'корзина пуста';
$BL['be_ftrash_show']                   = 'показать личные файлы';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Вы хотите восстановить {VAL} \nи положить в личный список?';
$BL['be_ftrash_delete']                 = 'Вы хотите удалить {VAL}?';
$BL['be_ftrash_undo']                   = 'восстановить (не удалять)';
$BL['be_ftrash_delfinal']               = 'окончательное удаление';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'поле поиска пустое.';
$BL['be_fsearch_title']                 = 'искать файлы';
$BL['be_fsearch_infotext']              = 'Это основной поиск файловой информации. Он ищет похоже совпадения по ключевым словам,<br />названию файла и информации. Не поддерживает групповые символы. Отделяйте несколько поисковых<br />слов пробелаи. Выберите И/ИЛИ и вид файлов: личные/общие.';
$BL['be_fsearch_nonfound']              = 'по Вашему запросу ничего не найдено. исправьте данные!';
$BL['be_fsearch_fillin']                = 'пожалуйста заполните поисковое поле.';
$BL['be_fsearch_searchlabel']           = 'искать';
$BL['be_fsearch_startsearch']           = 'начать поиск';
$BL['be_fsearch_and']                   = 'И';
$BL['be_fsearch_or']                    = 'ИЛИ';
$BL['be_fsearch_all']                   = 'все файлы';
$BL['be_fsearch_personal']              = 'личные';
$BL['be_fsearch_public']                = 'общие';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'внутренний чат';
$BL['be_chat_info']                     = 'Здесь вы можете общаться с другими phpwcms пользователями о чем захотите. Эта услуга предусматривает общение в реальном времени но вы можете также оставить сообщение которое все прочитают. Если вы хотите обменяться мнениями с другими воспользуйтесь услугой дискуссия.';
$BL['be_chat_start']                    = 'нажмите сюда чтобы начать общение';
$BL['be_chat_lines']                    = 'поле ввода';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'центр сообщений';
$BL['be_msg_new']                       = 'новое';
$BL['be_msg_old']                       = 'старое';
$BL['be_msg_senttop']                   = 'отправлено';
$BL['be_msg_del']                       = 'удалено';
$BL['be_msg_from']                      = 'из';
$BL['be_msg_subject']                   = 'тема';
$BL['be_msg_date']                      = 'дата/время';
$BL['be_msg_close']                     = 'закрыть сообщение';
$BL['be_msg_create']                    = 'создать новое сообщение';
$BL['be_msg_reply']                     = 'ответить на это сообщение';
$BL['be_msg_move']                      = 'отправить соощение в корзину';
$BL['be_msg_unread']                    = 'непрочитанное или новое сообщение';
$BL['be_msg_lastread']                  = 'последнее {VAL} прочитанное сообщение';
$BL['be_msg_lastsent']                  = 'последнее {VAL} отпраленное сообщение';
$BL['be_msg_marked']                    = 'сообщения на удаление (корзина)';
$BL['be_msg_nomsg']                     = 'в этой папке нет сообщений';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'отправитель';
$BL['be_msg_on']                        = 'дата';
$BL['be_msg_msg']                       = 'сообщение';
$BL['be_msg_err1']                      = 'вы забыли указать получателя...';
$BL['be_msg_err2']                      = 'заполнить поле темы';
$BL['be_msg_err3']                      = 'бессмысленно посылать сообщение без текста самого сообщения ;-)';
$BL['be_msg_sent']                      = 'сообщение отослано!';
$BL['be_msg_fwd']                       = 'вас перенаправят в центр сообщений или';
$BL['be_msg_newmsgtitle']               = 'написать новое сообщение';
$BL['be_msg_err']                       = 'ошибка при отправке сообщения';
$BL['be_msg_sendto']                    = 'послать сообщение';
$BL['be_msg_available']                 = 'список получателей';
$BL['be_msg_all']                       = 'послать сообщение всем получателям';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'подписка на рассылку';
$BL['be_newsletter_titleedit']          = 'изменить подписку на рассылку';
$BL['be_newsletter_new']                = 'создать новую';
$BL['be_newsletter_add']                = 'добавить&nbsp;подписку&nbsp;на рассылку';
$BL['be_newsletter_name']               = 'имя';
$BL['be_newsletter_info']               = 'информация';
$BL['be_newsletter_button_save']        = 'сохранить подписку';
$BL['be_newsletter_button_cancel']      = 'отменить';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'неправильное имя пользователя, выберите другое';
$BL['be_admin_usr_err2']                = 'имя пользователя отсутствует (необходимо)';
$BL['be_admin_usr_err3']                = 'пароль отсутствует (необходимо)';
$BL['be_admin_usr_err4']                = "неправильный email";
$BL['be_admin_usr_err']                 = 'ошибка';
$BL['be_admin_usr_mailsubject']         = 'добро пожаловать в раздел администратора';
$BL['be_admin_usr_mailbody']            = "Добро пожаловать в раздел администратора\n\n    имя: {LOGIN}\n    пароль: {PASSWORD}\n\n\nВы можете войти здесь: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'добавить нового пользователя';
$BL['be_admin_usr_realname']            = 'настоящее имя';
$BL['be_admin_usr_setactive']           = 'сделать пользователя активным';
$BL['be_admin_usr_iflogin']             = 'если отмечено, пользователь может войти';
$BL['be_admin_usr_isadmin']             = 'пользователь администратор';
$BL['be_admin_usr_ifadmin']             = 'если отмечено, пользователь получает права администратора ';
$BL['be_admin_usr_verify']              = 'проверка';
$BL['be_admin_usr_sendemail']           = 'послать email новому пользователю с его информацией';
$BL['be_admin_usr_button']              = 'послать данные пользователя';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'изменить данные пользователя';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - данные пользователя изменены';
$BL['be_admin_usr_emailbody']           = "PHPWCMS ДАННЫЕ ПОЛЬЗОВАТЕЛЯ БЫЛИ ИЗМЕНЕНЫ\n\n    пользователь: {LOGIN}\n    пароль: {PASSWORD}\n\n\nВы можете зайти здесь: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[НЕ ИЗМЕНЯТЬ - ИСПОЛЬЗОВАТЬ ИЗВЕСТНЫЙ ПАРОЛЬ]';
$BL['be_admin_usr_ebutton']             = 'обновить данные пользователя';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms список пользователей';
$BL['be_admin_usr_ldel']                = 'ВНИМАНИЕ!&#13Это удалит пользователя';
$BL['be_admin_usr_create']              = 'создать нового пользователя';
$BL['be_admin_usr_editusr']             = 'изменить пользователя';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'структура сайта';
$BL['be_admin_struct_child']            = '(дочерняя)';
$BL['be_admin_struct_index']            = 'index (главная страница)';
$BL['be_admin_struct_cat']              = 'название категории';
$BL['be_admin_struct_hide1']            = 'скрыть';
$BL['be_admin_struct_hide2']            = 'эта&nbsp;категория&nbsp;в&nbsp;меню';
$BL['be_admin_struct_info']             = 'информация о категории';
$BL['be_admin_struct_template']         = 'шаблон';
$BL['be_admin_struct_alias']            = 'краткое название (alias) категории';
$BL['be_admin_struct_visible']          = 'видимый';
$BL['be_admin_struct_button']           = 'сохранить данные категории';
$BL['be_admin_struct_close']            = 'закрыть';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'категории файлов';
$BL['be_admin_fcat_err']                = 'отсутствует название категории!';
$BL['be_admin_fcat_name']               = 'название категории';
$BL['be_admin_fcat_needed']             = 'необходимо';
$BL['be_admin_fcat_button1']            = 'обновить';
$BL['be_admin_fcat_button2']            = 'создать';
$BL['be_admin_fcat_delmsg']             = 'Вы действительно хотите \n удалить ключевой файл?';
$BL['be_admin_fcat_fcat']               = 'файловая категория';
$BL['be_admin_fcat_err1']               = 'отсутствует название ключевого файла!';
$BL['be_admin_fcat_fkeyname']           = 'название ключевого файла';
$BL['be_admin_fcat_exit']               = 'выйти из режима редактирования';
$BL['be_admin_fcat_addkey']             = 'добавить новый ключ';
$BL['be_admin_fcat_editcat']            = 'изменить название категории';
$BL['be_admin_fcat_delcatmsg']          = 'Вы дйствительно хотите \n удалить файловую категорию?';
$BL['be_admin_fcat_delcat']             = 'удалить файловую категорию';
$BL['be_admin_fcat_delkey']             = 'удалить название ключевого файла';
$BL['be_admin_fcat_editkey']            = 'редактировать ключ';
$BL['be_admin_fcat_addcat']             = 'создать новую файловую категорию';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'макет страницы';
$BL['be_admin_page_align']              = 'выравнивание страницы';
$BL['be_admin_page_align_left']         = 'стандартное выравнивание страницы по левому краю';
$BL['be_admin_page_align_center']       = 'выравнивание по центру';
$BL['be_admin_page_align_right']        = 'выравнивание по правому краю';
$BL['be_admin_page_margin']             = 'граница';
$BL['be_admin_page_top']                = 'верх';
$BL['be_admin_page_bottom']             = 'низ';
$BL['be_admin_page_left']               = 'левый блок';
$BL['be_admin_page_right']              = 'правый блок';
$BL['be_admin_page_bg']                 = 'фон';
$BL['be_admin_page_color']              = 'цвет';
$BL['be_admin_page_height']             = 'высота';
$BL['be_admin_page_width']              = 'ширина';
$BL['be_admin_page_main']               = 'главный блок';
$BL['be_admin_page_leftspace']          = 'левое пространство';
$BL['be_admin_page_rightspace']         = 'правое пространство';
$BL['be_admin_page_class']              = 'класс';
$BL['be_admin_page_image']              = 'картинка';
$BL['be_admin_page_text']               = 'текст';
$BL['be_admin_page_link']               = 'ссылка';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'посещенные';
$BL['be_admin_page_pagetitle']          = 'название страницы';
$BL['be_admin_page_addtotitle']         = 'добавить&nbsp;в&nbsp;название';
$BL['be_admin_page_category']           = 'категория';
$BL['be_admin_page_articlename']        = 'название статьи';
$BL['be_admin_page_blocks']             = 'блоки';
$BL['be_admin_page_allblocks']          = 'все блоки';
$BL['be_admin_page_col1']               = '3 колонки';
$BL['be_admin_page_col2']               = '2 колонки (главное поле справа, навигационное поле слева)';
$BL['be_admin_page_col3']               = '2 колонки (главное поле справа, навигационное поле слева)';
$BL['be_admin_page_col4']               = '1 колонка';
$BL['be_admin_page_header']             = 'верхний блок';
$BL['be_admin_page_footer']             = 'нижний блок';
$BL['be_admin_page_topspace']           = 'граница&nbsp;вверху';
$BL['be_admin_page_bottomspace']        = 'граница&nbsp;внизу';
$BL['be_admin_page_button']             = 'сохранить макет страницы';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'стили css';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'сохранить стили css';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'шаблоны';
$BL['be_admin_tmpl_default']            = 'по умолчанию';
$BL['be_admin_tmpl_add']                = 'добавить&nbsp;шаблон';
$BL['be_admin_tmpl_edit']               = 'изменить шаблон';
$BL['be_admin_tmpl_new']                = 'создать новый';
$BL['be_admin_tmpl_css']                = 'css файл';
$BL['be_admin_tmpl_head']               = 'html заголовок';
$BL['be_admin_tmpl_js']                 = 'js при загрузке';
$BL['be_admin_tmpl_error']              = 'ошибка';
$BL['be_admin_tmpl_button']             = 'сохранить шаблон';
$BL['be_admin_tmpl_name']               = 'название';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'структура сайта и список статей';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'отсутствует название статьи';
$BL['be_article_err2']                  = 'начальная дата неправильная - установите "сейчас"';
$BL['be_article_err3']                  = 'конечная дата неправильная - установите "сейчас"';
$BL['be_article_title1']                = 'информация статьи';
$BL['be_article_cat']                   = 'категория';
$BL['be_article_atitle']                = 'название статьи';
$BL['be_article_asubtitle']             = 'дополнительное название';
$BL['be_article_abegin']                = 'начинается';
$BL['be_article_aend']                  = 'заканчивается';
$BL['be_article_aredirect']             = 'перенаправить';
$BL['be_article_akeywords']             = 'ключевые слова';
$BL['be_article_asummary']              = 'текст';
$BL['be_article_abutton']               = 'создать новую статью';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'конечная дата неправильная - установите "сейчас" + 1 неделя';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'редактировать данные статьи';
$BL['be_article_eslastedit']            = 'последнее редактирование';
$BL['be_article_esnoupdate']            = 'форма не обновлена';
$BL['be_article_esbutton']              = 'обновить данные статьи';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'содержание статьн';
$BL['be_article_cnt_type']              = 'вид контента';
$BL['be_article_cnt_space']             = 'пробел';
$BL['be_article_cnt_before']            = 'до';
$BL['be_article_cnt_after']             = 'после';
$BL['be_article_cnt_top']               = 'верх';
$BL['be_article_cnt_ctitle']            = 'название';
$BL['be_article_cnt_back']              = 'полная информация статьи';
$BL['be_article_cnt_button1']           = 'обновить контент';
$BL['be_article_cnt_button2']           = 'создать контент';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'информация статьи';
$BL['be_article_cnt_ledit']             = 'редактировать статью';
$BL['be_article_cnt_lvisible']          = 'включить видимый/невидимый';
$BL['be_article_cnt_ldel']              = 'удалить эту статью';
$BL['be_article_cnt_ldeljs']            = 'Удалить статью?';
$BL['be_article_cnt_redirect']          = 'перенаправление';
$BL['be_article_cnt_edited']            = 'изменено';
$BL['be_article_cnt_start']             = 'начальная дата';
$BL['be_article_cnt_end']               = 'конечная дата';
$BL['be_article_cnt_add']               = 'добавить новый контент';
$BL['be_article_cnt_up']                = 'поднять';
$BL['be_article_cnt_down']              = 'опустить';
$BL['be_article_cnt_edit']              = 'редактировать контент';
$BL['be_article_cnt_delpart']           = 'удалить контент данной статьи';
$BL['be_article_cnt_delpartjs']         = 'Удалить контент?';
$BL['be_article_cnt_center']            = 'Центр статей';

// content forms
$BL['be_cnt_plaintext']                 = 'обычный текст';
$BL['be_cnt_htmltext']                  = 'html текст';
$BL['be_cnt_image']                     = 'рисунок';
$BL['be_cnt_position']                  = 'позиция';
$BL['be_cnt_pos0']                      = 'Верх, слева';
$BL['be_cnt_pos1']                      = 'Верх, центр';
$BL['be_cnt_pos2']                      = 'Верх, справа';
$BL['be_cnt_pos3']                      = 'Низ, слева';
$BL['be_cnt_pos4']                      = 'Низ центр';
$BL['be_cnt_pos5']                      = 'Низ, справа';
$BL['be_cnt_pos6']                      = 'В тексте, слева';
$BL['be_cnt_pos7']                      = 'В тексте, справа';
$BL['be_cnt_pos0i']                     = 'выравнять рисунок по верху и левому краю текстового блока';
$BL['be_cnt_pos1i']                     = 'выравнять рисунок по верху и центру текстового блока';
$BL['be_cnt_pos2i']                     = 'выравнять рисунок по верху и правому краю текстового блока';
$BL['be_cnt_pos3i']                     = 'выравнять рисунок по низу и левому краю текстового блока';
$BL['be_cnt_pos4i']                     = 'выравнять рисунок по низу и центру текстового блока';
$BL['be_cnt_pos5i']                     = 'выравнять рисунок по низу и правому краю текстового блока';
$BL['be_cnt_pos6i']                     = 'выровнять рисунок по левому краю текстового блока';
$BL['be_cnt_pos7i']                     = 'выровнять рисунок по правому краю текстового блока';
$BL['be_cnt_maxw']                      = 'максимальная&nbsp;ширина';
$BL['be_cnt_maxh']                      = 'максимальная&nbsp;высота';
$BL['be_cnt_enlarge']                   = '&nbsp;увеличить при клике';
$BL['be_cnt_caption']                   = 'подпись под картинкой';
$BL['be_cnt_subject']                   = 'тема';
$BL['be_cnt_recipient']                 = 'получатель';
$BL['be_cnt_buttontext']                = 'текст кнопки';
$BL['be_cnt_sendas']                    = 'послать как';
$BL['be_cnt_text']                      = 'текст';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'поля формы';
$BL['be_cnt_code']                      = 'код';
$BL['be_cnt_infotext']                  = 'инфо&nbsp;текст';
$BL['be_cnt_subscription']              = 'подписка';
$BL['be_cnt_labelemail']                = 'назвать&nbsp;email';
$BL['be_cnt_tablealign']                = 'разметка&nbsp;таблицы';
$BL['be_cnt_labelname']                 = 'название&nbsp;';
$BL['be_cnt_labelsubsc']                = 'название&nbsp;подписки';
$BL['be_cnt_allsubsc']                  = 'все&nbsp;подписки';
$BL['be_cnt_default']                   = 'по умолчанию';
$BL['be_cnt_left']                      = 'слева';
$BL['be_cnt_center']                    = 'по центру';
$BL['be_cnt_right']                     = 'справа';
$BL['be_cnt_buttontext']                = 'текст&nbsp;кнопки';
$BL['be_cnt_successtext']               = 'рабочий&nbsp;текст';
$BL['be_cnt_regmail']                   = 'зарегистрировать email';
$BL['be_cnt_logoffmail']                = 'выйти';
$BL['be_cnt_changemail']                = 'изменить email';
$BL['be_cnt_openimagebrowser']          = 'открыть браузер картинок';
$BL['be_cnt_openfilebrowser']           = 'открыть браузер файлов';
$BL['be_cnt_sortup']                    = 'выше';
$BL['be_cnt_sortdown']                  = 'ниже';
$BL['be_cnt_delimage']                  = 'убрать выбранный рисунок';
$BL['be_cnt_delfile']                   = 'убрать выбранный файл';
$BL['be_cnt_delmedia']                  = 'убрать выбранный фильм';
$BL['be_cnt_column']                    = 'колонка';
$BL['be_cnt_imagespace']                = 'отступ&nbsp;картинки';
$BL['be_cnt_directlink']                = 'прямая ссылка';
$BL['be_cnt_target']                    = 'открыть';
$BL['be_cnt_target1']                   = 'в новом окне';
$BL['be_cnt_target2']                   = 'в корневом фрейме окна';
$BL['be_cnt_target3']                   = 'в том же окне без фреймов';
$BL['be_cnt_target4']                   = 'в том же фрейме или окне';
$BL['be_cnt_bullet']                    = 'список кнопок';
$BL['be_cnt_linklist']                  = 'список ссылок';
$BL['be_cnt_plainhtml']                 = 'обычный html';
$BL['be_cnt_files']                     = 'файлы';
$BL['be_cnt_description']               = 'описание';
$BL['be_cnt_linkarticle']               = 'ссылка на статью';
$BL['be_cnt_articles']                  = 'статьи';
$BL['be_cnt_movearticleto']             = 'вставить выбранную статью в список ссылок';
$BL['be_cnt_removearticleto']           = 'убрать выбранную статью из списка ссылок';
$BL['be_cnt_mediatype']                 = 'вид фильма';
$BL['be_cnt_control']                   = 'управление';
$BL['be_cnt_showcontrol']               = 'показать панель управления';
$BL['be_cnt_autoplay']                  = 'автозапуск';
$BL['be_cnt_source']                    = 'источник';
$BL['be_cnt_internal']                  = 'внутренний';
$BL['be_cnt_openmediabrowser']          = 'открыть фильм';
$BL['be_cnt_external']                  = 'внешний';
$BL['be_cnt_mediapos0']                 = 'слева (по умолчанию)';
$BL['be_cnt_mediapos1']                 = 'центр';
$BL['be_cnt_mediapos2']                 = 'справа';
$BL['be_cnt_mediapos3']                 = 'заблокировать, слева';
$BL['be_cnt_mediapos4']                 = 'заблокировать, справа';
$BL['be_cnt_mediapos0i']                = 'выровнять фильм по верху и левому краю текстового блока';
$BL['be_cnt_mediapos1i']                = 'выровнять фильм по верху и центру текстового блока';
$BL['be_cnt_mediapos2i']                = 'выровнять фильм по верху и правому краю текстового блока';
$BL['be_cnt_mediapos3i']                = 'выровнять фильм по левому краю текстового блока';
$BL['be_cnt_mediapos4i']                = 'выровнять фильм по правому краю текстового блока';
$BL['be_cnt_setsize']                   = 'установить размер';
$BL['be_cnt_set1']                      = 'установить размер 160x120px';
$BL['be_cnt_set2']                      = 'установить размер 240x180px';
$BL['be_cnt_set3']                      = 'установить размер 320x240px';
$BL['be_cnt_set4']                      = 'установить размер 480x360px';
$BL['be_cnt_set5']                      = 'начальный размер';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'создать новый макет страницы';
$BL['be_admin_page_name']               = 'название макета';
$BL['be_admin_page_edit']               = 'изменить макет страницы';
$BL['be_admin_page_render']             = 'обработка';
$BL['be_admin_page_table']              = 'таблица';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'обычный';
$BL['be_admin_page_custominfo']         = 'из главного блока шаблона';
$BL['be_admin_tmpl_layout']             = 'макет';
$BL['be_admin_tmpl_nolayout']           = 'Нет макета страницы!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'поиск';
$BL['be_cnt_results']                   = 'результаты';
$BL['be_cnt_results_per_page']          = 'на&nbsp;странице (если нет числовых данных показывать 25)';
$BL['be_cnt_opennewwin']                = 'открыть новое окно';
$BL['be_cnt_searchlabeltext']           = 'это определенные текстовые поля для поисковых форм Она показываются когда превышен лимит поиска.';
$BL['be_cnt_input']                     = 'ввод';
$BL['be_cnt_style']                     = 'стиль';
$BL['be_cnt_result']                    = 'результат';
$BL['be_cnt_next']                      = 'следующая';
$BL['be_cnt_previous']                  = 'предыдущая';
$BL['be_cnt_align']                     = 'разметка';
$BL['be_cnt_searchformtext']            = 'Этот текст показывается когда открыта поисковая форма или результаты поиска недоступны.';
$BL['be_cnt_intro']                     = 'вступление';
$BL['be_cnt_noresult']                  = 'нет результатов';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'отключить';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'автор статьи';
$BL['be_article_adminuser']             = 'администратор';
$BL['be_article_username']              = 'автор';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004

$BL['be_admin_struct_regonly']          = 'видимый только для авторизованных пользователей';
$BL['be_admin_struct_status']           = 'статус видимости';

// added: 15-02-2004
$BL['be_ctype_articlemenu']     = 'выбор статей';
$BL['be_cnt_sitelevel']         = 'категория сайта';
$BL['be_cnt_sitecurrent']       = 'текущая категория сайта';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']    = 'текст при входе';
$BL['be_ctype_ecard']           = 'открытки';
$BL['be_ctype_blog']            = 'блог';
$BL['be_cnt_ecardtext']                 = 'заголовок/открытка';
$BL['be_cnt_ecardtmpl']                 = 'шаблон отправки';
$BL['be_cnt_ecard_image']               = 'картинка открытки';
$BL['be_cnt_ecard_title']               = 'заголовок открытки';
$BL['be_cnt_alignment']                 = 'выравнивание';
$BL['be_cnt_ecardform']                 = 'шаблон формы';
$BL['be_cnt_ecardform_err']             = 'Все поля обозначенные * обязательны для заполнения';
$BL['be_cnt_ecardform_sender']          = 'Отправитель';
$BL['be_cnt_ecardform_recipient']       = 'Получатель';
$BL['be_cnt_ecardform_name']            = 'Имя';
$BL['be_cnt_ecardform_msgtext']         = 'Ваше сообщение получателю';
$BL['be_cnt_ecardform_button']          = 'отослать открытку';
$BL['be_cnt_ecardsend']                 = 'шаблон "отослано"';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Текст при входе в администрирование';
$BL['be_admin_startup_text']            = 'Текст при входе в администрирование';
$BL['be_admin_startup_button']          = 'сохранить текст';

// added: 17-04-2004
$BL['be_ctype_guestbook']           = 'гост.книга/коммент.';
$BL['be_cnt_guestbook_listing']         = 'отображение';
$BL['be_cnt_guestbook_listing_all']     = 'показать&nbsp;все&nbsp;записи';
$BL['be_cnt_guestbook_list']            = 'показать';
$BL['be_cnt_guestbook_perpage']         = 'на&nbsp;страницу';
$BL['be_cnt_guestbook_form']            = 'форма';
$BL['be_cnt_guestbook_signed']          = 'отослано';
$BL['be_cnt_guestbook_nav']         = 'навигация';
$BL['be_cnt_guestbook_before']          = 'до';
$BL['be_cnt_guestbook_after']           = 'после';
$BL['be_cnt_guestbook_entry']           = 'запись';
$BL['be_cnt_guestbook_edit']            = 'редактировать';
$BL['be_cnt_ecardform_selector']        = 'выбор';
$BL['be_cnt_ecardform_radiobutton']     = 'с помощю радио-кнопки';
$BL['be_cnt_ecardform_javascript']      = 'с помощью JavaScript';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'Кол-во статей сверху';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'рассылка';
$BL['be_newsletter_addnl']              = 'добавить рассылку';
$BL['be_newsletter_titleeditnl']        = 'редактировать рассылку';
$BL['be_newsletter_newnl']              = 'создать новую';
$BL['be_newsletter_button_savenl']      = 'сохранить рассылку';
$BL['be_newsletter_fromname']           = 'от кого';
$BL['be_newsletter_fromemail']          = 'email отправителя';
$BL['be_newsletter_replyto']            = 'email для ответа';
$BL['be_newsletter_changed']            = 'последнее изменение';
$BL['be_newsletter_placeholder']        = 'место';
$BL['be_newsletter_htmlpart']           = 'содержание в виде HTML';
$BL['be_newsletter_textpart']           = 'содержание в текстовом виде';
$BL['be_newsletter_allsubscriptions']   = 'все подписки';
$BL['be_newsletter_verifypage']         = 'проверить ссылку';
$BL['be_newsletter_open']               = 'ввод HTML и текста';
$BL['be_newsletter_open1']              = '(кликните на картинке, чтобы открыть)';
$BL['be_newsletter_sendnow']            = 'Отправить рассылку';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Внимание!</strong> Отправка рассылки многим получателям очень рискованна. Получатели должны подтвердить свое согласие, в противном случае вы посылаете потенциальный спам. Дважды подумайте перед отправкой. Проверьте вашу рассылку, отправив пробное сообщение.';
$BL['be_newsletter_attention1']         = 'Если вы изменили рассылку, пожалуйста сохраните ее, в  противном случае эти изменения не вступят в силу.';
$BL['be_newsletter_testemail']          = 'пробный email';
$BL['be_newsletter_sendnlbutton']       = 'отправить рассылку';
$BL['be_newsletter_sendprocess']        = 'процесс отправки';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Внимание!</strong> Пожалуйста не останавливайте процесс отправки. В противном случае может получиться, что вы отправите рассылку одному получателю несколько раз. Если рассылка прервется, адреса всех получателей, которым не удалось отправить будут сохранены, и будут использованы, если вы сразу отправите снова.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">Пробный адрес электронной почты <strong>###TEST###</strong> неправильный!<br />&nbsp;<br />Пожалуйста, попробуйте еще раз!';
$BL['be_newsletter_to']                 = 'Получатели';
$BL['be_newsletter_ready']              = 'Отправка рассылки прошла успешно';
$BL['be_newsletter_readyfailed']        = 'Не удалось отправить рассылку';
$BL['be_subnav_msg_subscribers']        = 'подписчики рассылки';

// added: 20-04-2004
$BL['be_ctype_sitemap']         = 'карта сайта';
$BL['be_cnt_sitemap_catimage']          = 'иконка уровня';
$BL['be_cnt_sitemap_articleimage']      = 'иконка статьи';
$BL['be_cnt_sitemap_display']           = 'показывать';
$BL['be_cnt_sitemap_structuronly']      = 'только структурные уровни';
$BL['be_cnt_sitemap_structurarticle']   = 'структурные уровни и статьи';
$BL['be_cnt_sitemap_catclass']          = 'css-класс уровня';
$BL['be_cnt_sitemap_articleclass']      = 'css-класс статьи';
$BL['be_cnt_sitemap_count']             = 'счетчик';
$BL['be_cnt_sitemap_classcount']        = 'добавить к имени css-класса';
$BL['be_cnt_sitemap_noclasscount']      = 'не добавлять';

// added: 23-04-2004
$BL['be_ctype_bid']         = 'предложения';
$BL['be_cnt_bid_bidtext']               = 'текст предложения';
$BL['be_cnt_bid_sendtext']              = 'текст "отправлено"';
$BL['be_cnt_bid_verifiedtext']          = 'текст "проверено"';
$BL['be_cnt_bid_errortext']             = 'предложение удалено';
$BL['be_cnt_bid_verifyemail']           = 'проверить email';
$BL['be_cnt_bid_startbid']              = 'начальное предложение';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'увеличить&nbsp;на';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'внешние данные';
$BL['be_cnt_pages_select']              = 'выберите файл';
$BL['be_cnt_pages_fromfile']            = 'файл из структуры';
$BL['be_cnt_pages_manually']            = 'путь до файла или URL';
$BL['be_cnt_pages_cust']                = 'файл/URL';
$BL['be_cnt_pages_from']                = 'источник';

