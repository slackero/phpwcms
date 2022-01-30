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


$BL['usr_online']                       = 'users online';

// Login Page
$BL["login_text"]                       = 'Insert your login data';
$BL['login_error']                      = 'Errors during login!';
$BL["login_username"]                   = 'username';
$BL["login_userpass"]                   = 'password';
$BL["login_button"]                     = 'Login';
$BL["login_lang"]                       = 'backend language';

// phpwcms.php
$BL['be_nav_logout']                    = 'LOGOUT';
$BL['be_nav_articles']                  = 'ARTICLE';
$BL['be_nav_files']                     = 'FILE';
$BL['be_nav_modules']                   = 'MODULES';
$BL['be_nav_messages']                  = 'COMMUNICATION';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFILE';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISCUSS';

$BL['be_page_title']                    = 'phpwcms backend (administration)';

$BL['be_subnav_article_center']         = 'article center';
$BL['be_subnav_article_new']            = 'new article';
$BL['be_subnav_file_center']            = 'file center';
$BL['be_subnav_file_actions']           = 'file actions';
$BL['be_subnav_file_ftptakeover']       = 'ftp takeover';
$BL['be_subnav_mod_artists']            = 'artist, category, genre';
$BL['be_subnav_msg_center']             = 'message center';
$BL['be_subnav_msg_new']                = 'new message';
$BL['be_subnav_msg_newsletter']         = 'newsletter subscriptions';
$BL['be_subnav_chat_main']              = 'chat main page';
$BL['be_subnav_chat_internal']          = 'internal chat';
$BL['be_subnav_profile_login']          = 'login information';
$BL['be_subnav_profile_personal']       = 'personal data';
$BL['be_subnav_admin_pagelayout']       = 'page layout';
$BL['be_subnav_admin_templates']        = 'templates';
$BL['be_subnav_admin_css']              = 'default css';
$BL['be_subnav_admin_sitestructure']    = 'site structure';
$BL['be_subnav_admin_users']            = 'user administration';
$BL['be_subnav_admin_filecat']          = 'file categories';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'article ID';
$BL['be_func_struct_preview']           = 'preview';
$BL['be_func_struct_edit']              = 'edit article';
$BL['be_func_struct_sedit']             = 'edit structure level';
$BL['be_func_struct_cut']               = 'cut article';
$BL['be_func_struct_nocut']             = 'disable cut article';
$BL['be_func_struct_svisible']          = 'switch visible/invisible';
$BL['be_func_struct_spublic']           = 'switch public/non public';
$BL['be_func_struct_sort_up']           = 'sort up';
$BL['be_func_struct_sort_down']         = 'sort down';
$BL['be_func_struct_del_article']       = 'delete article';
$BL['be_func_struct_del_jsmsg']         = 'Do you really want \nto delete article?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'create new article in structure level';
$BL['be_func_struct_paste_article']     = 'paste article in structure level';
$BL['be_func_struct_insert_level']      = 'insert structure level in';
$BL['be_func_struct_paste_level']       = 'paste in structure level';
$BL['be_func_struct_cut_level']         = 'cut structure level';
$BL['be_func_struct_no_cut']            = "It's not possible to cut the root level!";
$BL['be_func_struct_no_paste1']         = "It's not possible to paste in here!";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'that should paste in here';
$BL['be_func_struct_paste_cancel']      = 'cancel structure level change';
$BL['be_func_struct_del_struct']        = 'delete structure level';
$BL['be_func_struct_del_sjsmsg']        = 'Do you really want \nto delete structure level?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'open';
$BL['be_func_struct_close']             = 'close';
$BL['be_func_struct_empty']             = 'empty';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'plain text';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'code';
$BL['be_ctype_textimage']               = 'text w/image';
$BL['be_ctype_images']                  = 'images';
$BL['be_ctype_bulletlist']              = 'list';
$BL['be_ctype_ullist']                  = 'list';
$BL['be_ctype_link']                    = 'link &amp; email';
$BL['be_ctype_linklist']                = 'link list';
$BL['be_ctype_linkarticle']             = 'teaser/link article';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'file list';
$BL['be_ctype_emailform']               = 'email form generator';
$BL['be_ctype_newsletter']              = 'newsletter';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profile successfully created.';
$BL['be_profile_create_error']          = 'An error occured while creating.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profile data successful updated.';
$BL['be_profile_update_error']          = 'An error occured while updating.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'username {VAL} is invalid';
$BL['be_profile_account_err2']          = 'password to short (only {VAL} chars: at least 5 needed)';
$BL['be_profile_account_err3']          = 'password must be identically equal to repeat password';
$BL['be_profile_account_err4']          = 'email {VAL} is invalid';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'your personal data';
$BL['be_profile_data_text']             = 'personal data are optional. This can help other users or site visitors to get to know more about you, your interests and skills. If you select the proper checkbox users can see your profile information in the public area or on article pages (or even not).';
$BL['be_profile_label_title']           = 'title';
$BL['be_profile_label_firstname']       = 'first name';
$BL['be_profile_label_name']            = 'surname';
$BL['be_profile_label_company']         = 'company';
$BL['be_profile_label_street']          = 'street';
$BL['be_profile_label_city']            = 'city';
$BL['be_profile_label_state']           = 'province, state';
$BL['be_profile_label_zip']             = 'zip, postal code';
$BL['be_profile_label_country']         = 'country';
$BL['be_profile_label_phone']           = 'phone';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'mobile';
$BL['be_profile_label_signature']       = 'signature';
$BL['be_profile_label_notes']           = 'notes';
$BL['be_profile_label_profession']      = 'profession';
$BL['be_profile_label_newsletter']      = 'newsletter';
$BL['be_profile_text_newsletter']       = 'I want to receive the general phpwcms newsletter.';
$BL['be_profile_label_public']          = 'public';
$BL['be_profile_text_public']           = 'Anybody should be able to see my personal profile.';
$BL['be_profile_label_button']          = 'update personal data';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'your login information';
$BL['be_profile_account_text']          = 'Normally it is not necessary to change your username.<br />You should change your password from time to time to increase security.';
$BL['be_profile_label_err']             = 'please check';
$BL['be_profile_label_username']        = 'username';
$BL['be_profile_label_newpass']         = 'new password';
$BL['be_profile_label_repeatpass']      = 'repeat new pwd';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'update';
$BL['be_profile_label_lang']            = 'language';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'take over files uploaded via ftp';
$BL['be_ftptakeover_mark']              = 'mark';
$BL['be_ftptakeover_available']         = 'available files';
$BL['be_ftptakeover_size']              = 'size';
$BL['be_ftptakeover_nofile']            = 'There are no files available &#8211; you have to upload one by ftp or the the multiple file upload.';
$BL['be_ftptakeover_all']               = 'ALL';
$BL['be_ftptakeover_directory']         = 'directory';
$BL['be_ftptakeover_rootdir']           = 'root directory';
$BL['be_ftptakeover_needed']            = 'needed!!! (you have to select one)';
$BL['be_ftptakeover_optional']          = 'optional';
$BL['be_ftptakeover_keywords']          = 'keywords';
$BL['be_ftptakeover_additional']        = 'additional';
$BL['be_ftptakeover_longinfo']          = 'long info';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'active';
$BL['be_ftptakeover_public']            = 'public';
$BL['be_ftptakeover_createthumb']       = 'create thumbnail';
$BL['be_ftptakeover_button']            = 'take over selected files';
$BL['be_ftptakeover_new_folder']        = 'create folder';
$BL['be_ftptakeover_new_folder_placeholder'] = 'name of the new folder in the root directory';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'file center';
$BL['be_ftab_createnew']                = 'create new dir in root';
$BL['be_ftab_paste']                    = 'paste clipboard file into root directory';
$BL['be_ftab_disablethumb']             = 'disable thumbnails in list';
$BL['be_ftab_enablethumb']              = 'enable thumbnails in list';
$BL['be_ftab_private']                  = 'private&nbsp;files';
$BL['be_ftab_public']                   = 'public&nbsp;files';
$BL['be_ftab_search']                   = 'search';
$BL['be_ftab_trash']                    = 'trash&nbsp;can';
$BL['be_ftab_open']                     = 'open all directories';
$BL['be_ftab_close']                    = 'close all open directories';
$BL['be_ftab_upload']                   = 'upload file to root directory';
$BL['be_ftab_filehelp']                 = 'open file help';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'root directory';
$BL['be_fpriv_title']                   = 'create new directory';
$BL['be_fpriv_inside']                  = 'inside';
$BL['be_fpriv_error']                   = 'error: fill in name for directory';
$BL['be_fpriv_errordir']                = 'error: directory cannot be subfolder of itself';
$BL['be_fpriv_name']                    = 'name';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'create new dir';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'edit directory';
$BL['be_fpriv_newname']                 = 'new name';
$BL['be_fpriv_updatebutton']            = 'update directory info';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Select a file you wish to upload';
$BL['be_fprivup_err2']                  = 'Size of uploaded file is larger than';
$BL['be_fprivup_err3']                  = 'Error while writing file to storage';
$BL['be_fprivup_err4']                  = 'Error while creating user directory.';
$BL['be_fprivup_err5']                  = 'no thumbnail exists';
$BL['be_fprivup_err6']                  = 'Please dont try again - this is an server error! Contact your <a href="mailto:{VAL}">webmaster</a> as soon as possible!';
$BL['be_fprivup_err7']                  = 'For security reasons the file %s cannot be uploaded.';
$BL['be_fprivup_err8']                  = 'File with extension %s is not allowed for upload. Allowed extensions are: %s.';
$BL['be_fprivup_err9']                  = 'File without extension is not allowed for upload. Allowed extensions are: %s.';
$BL['be_fprivup_title']                 = 'upload files';
$BL['be_fprivup_button']                = 'upload files';
$BL['be_fprivup_upload']                = 'upload';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'edit file information';
$BL['be_fprivedit_filename']            = 'filename';
$BL['be_fprivedit_created']             = 'created';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'proof name of file (set back to original)';
$BL['be_fprivedit_clockwise']           = 'rotate thumbnail clockwise [original file +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'rotate thumbnail counter clockwise [original file -90&deg;]';
$BL['be_fprivedit_button']              = 'update file info';
$BL['be_fprivedit_size']                = 'size';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'upload file to directory';
$BL['be_fprivfunc_makenew']             = 'make new dir inside';
$BL['be_fprivfunc_paste']               = 'paste clipboard file into dir';
$BL['be_fprivfunc_edit']                = 'edit dir';
$BL['be_fprivfunc_cactive']             = 'switch active/inactive';
$BL['be_fprivfunc_cpublic']             = 'switch public/nonpublic';
$BL['be_fprivfunc_deldir']              = 'delete dir';
$BL['be_fprivfunc_jsdeldir']            = 'Do you really want \nto delete directory';
$BL['be_fprivfunc_notempty']            = 'dir {VAL} not empty!';
$BL['be_fprivfunc_opendir']             = 'open directory';
$BL['be_fprivfunc_closedir']            = 'close directory';
$BL['be_fprivfunc_dlfile']              = 'download file';
$BL['be_fprivfunc_clipfile']            = 'clipboard file';
$BL['be_fprivfunc_cutfile']             = 'cut';
$BL['be_fprivfunc_editfile']            = 'edit file info';
$BL['be_fprivfunc_cactivefile']         = 'switch active/inactive';
$BL['be_fprivfunc_cpublicfile']         = 'switch public/nonpublic';
$BL['be_fprivfunc_movetrash']           = 'put into trash';
$BL['be_fprivfunc_jsmovetrash1']        = 'Do you really want to put';
$BL['be_fprivfunc_jsmovetrash2']        = 'into trash can folder?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'no private files or folders';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'user';
$BL['be_fpublic_nofiles']               = 'no public files or folders';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'the trash can is empty';
$BL['be_ftrash_show']                   = 'show private files';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Do you want to restore {VAL} \nand put it back to private list?';
$BL['be_ftrash_delete']                 = 'Do you want to delete {VAL}?';
$BL['be_ftrash_undo']                   = 'restore (undo trash)';
$BL['be_ftrash_delfinal']               = 'final deletion';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'search string is empty.';
$BL['be_fsearch_title']                 = 'search files';
$BL['be_fsearch_infotext']              = 'This is a basic search for file information. It searches for matches in keywords,<br />filename and long file info. No support for wildcards. Separate multiple search<br />words with a blank. Select AND/OR and what files to search for: personal/public.';
$BL['be_fsearch_nonfound']              = 'no files for your search were found. correct your search values!';
$BL['be_fsearch_fillin']                = 'please fill in a search string in the above field.';
$BL['be_fsearch_searchlabel']           = 'search for';
$BL['be_fsearch_startsearch']           = 'start search';
$BL['be_fsearch_and']                   = 'AND';
$BL['be_fsearch_or']                    = 'OR';
$BL['be_fsearch_all']                   = 'all files';
$BL['be_fsearch_personal']              = 'private';
$BL['be_fsearch_public']                = 'public';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'internal chat';
$BL['be_chat_info']                     = 'Here you can chat with other phpwcms backend users about everything you want. This medium is for realtime speaking but you can also let a message that everybody can read. If you want to exchange ideas with others use the discussion please (later phpwcms version).';
$BL['be_chat_start']                    = 'click here to start the chat';
$BL['be_chat_lines']                    = 'chat lines';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'message center';
$BL['be_msg_new']                       = 'new';
$BL['be_msg_old']                       = 'old';
$BL['be_msg_senttop']                   = 'sent';
$BL['be_msg_del']                       = 'deleted';
$BL['be_msg_from']                      = 'from';
$BL['be_msg_subject']                   = 'subject';
$BL['be_msg_date']                      = 'date/time';
$BL['be_msg_close']                     = 'close message';
$BL['be_msg_create']                    = 'create a new message';
$BL['be_msg_reply']                     = 'reply to this message';
$BL['be_msg_move']                      = 'move this message to trash';
$BL['be_msg_unread']                    = 'unread or new messages';
$BL['be_msg_lastread']                  = 'last {VAL} read messages';
$BL['be_msg_lastsent']                  = 'last {VAL} sent messages';
$BL['be_msg_marked']                    = 'messages marked for deletion (trash)';
$BL['be_msg_nomsg']                     = 'no message found inside this folder';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'sent by';
$BL['be_msg_on']                        = 'on';
$BL['be_msg_msg']                       = 'message';
$BL['be_msg_err1']                      = 'you have forgotten to set a recipient...';
$BL['be_msg_err2']                      = 'fill in the subject field (the recipient can better handle your message)';
$BL['be_msg_err3']                      = 'it makes no sense to send a message without message itself ;-)';
$BL['be_msg_sent']                      = 'new message was sent!';
$BL['be_msg_fwd']                       = 'you will be forwarded to the message center or';
$BL['be_msg_newmsgtitle']               = 'write new message';
$BL['be_msg_err']                       = 'error while sending message';
$BL['be_msg_sendto']                    = 'send message to';
$BL['be_msg_available']                 = 'list of available recipients';
$BL['be_msg_all']                       = 'send message to all selected recipients';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'newsletter subscriptions';
$BL['be_newsletter_titleedit']          = 'edit newsletter subscription';
$BL['be_newsletter_new']                = 'create new';
$BL['be_newsletter_add']                = 'add&nbsp;newsletter&nbsp;subscription';
$BL['be_newsletter_name']               = 'name';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'Save subscription';
$BL['be_newsletter_button_cancel']      = 'Cancel';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'username is invalid, choose a different one';
$BL['be_admin_usr_err2']                = 'username is empty (required)';
$BL['be_admin_usr_err3']                = 'password is empty (required)';
$BL['be_admin_usr_err4']                = "email isn't valid";
$BL['be_admin_usr_err']                 = 'error';
$BL['be_admin_usr_mailsubject']         = 'welcome to phpwcms backend';
$BL['be_admin_usr_mailbody']            = "WELCOME TO THE PHPWCMS BACKEND\n\n    username: {LOGIN}\n    password: {PASSWORD}\n\n\nYou can login here: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'add new user account';
$BL['be_admin_usr_realname']            = 'real name';
$BL['be_admin_usr_setactive']           = 'set user active';
$BL['be_admin_usr_iflogin']             = 'if set the user can login';
$BL['be_admin_usr_isadmin']             = 'user is admin';
$BL['be_admin_usr_ifadmin']             = 'if set the user gets admin rights';
$BL['be_admin_usr_verify']              = 'verification';
$BL['be_admin_usr_sendemail']           = 'send an email to new user with the account information';
$BL['be_admin_usr_button']              = 'send user data';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'edit user account';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - account data changed';
$BL['be_admin_usr_emailbody']           = "PHPWCMS USER ACCOUNT INFORMATION CHANGED\n\n    username: {LOGIN}\n    password: {PASSWORD}\n\n\nYou can login here: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[NO CHANGE - USE THE KNOWN PASSWORD]';
$BL['be_admin_usr_ebutton']             = 'update user data';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'phpwcms user list';
$BL['be_admin_usr_ldel']                = 'ATTENTION!&#13;This will delete user';
$BL['be_admin_usr_create']              = 'create new user';
$BL['be_admin_usr_editusr']             = 'edit user';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'site structure';
$BL['be_admin_struct_child']            = '(child of)';
$BL['be_admin_struct_index']            = 'index (website start)';
$BL['be_admin_struct_cat']              = 'category title';
$BL['be_admin_struct_alt']              = 'category alternative title';
$BL['be_admin_struct_hide1']            = 'hide';
$BL['be_admin_struct_hide2']            = 'this&nbsp;category&nbsp;in&nbsp;menu';
$BL['be_admin_struct_info']             = 'category infotext';
$BL['be_admin_struct_template']         = 'template';
$BL['be_admin_struct_alias']            = 'alias this category';
$BL['be_admin_struct_visible']          = 'visible';
$BL['be_admin_struct_button']           = 'send category data';
$BL['be_admin_struct_close']            = 'close';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'file categories';
$BL['be_admin_fcat_err']                = 'category name is empty!';
$BL['be_admin_fcat_name']               = 'category name';
$BL['be_admin_fcat_needed']             = 'needed';
$BL['be_admin_fcat_button1']            = 'update';
$BL['be_admin_fcat_button2']            = 'create';
$BL['be_admin_fcat_delmsg']             = 'Do you really want\nto delete file key?';
$BL['be_admin_fcat_fcat']               = 'file category';
$BL['be_admin_fcat_err1']               = 'file key name is empty!';
$BL['be_admin_fcat_fkeyname']           = 'file key name';
$BL['be_admin_fcat_exit']               = 'exit editing';
$BL['be_admin_fcat_addkey']             = 'add new key';
$BL['be_admin_fcat_editcat']            = 'edit category name';
$BL['be_admin_fcat_delcatmsg']          = 'Do you really want\nto delete file category?';
$BL['be_admin_fcat_delcat']             = 'delete file category';
$BL['be_admin_fcat_delkey']             = 'delete file key name';
$BL['be_admin_fcat_editkey']            = 'edit key';
$BL['be_admin_fcat_addcat']             = 'create new file category';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'frontend setup: page layout';
$BL['be_admin_page_align']              = 'page align';
$BL['be_admin_page_align_left']         = 'standard align (left) of the whole page content';
$BL['be_admin_page_align_center']       = 'center the whole page content';
$BL['be_admin_page_align_right']        = 'align right of the whole page content';
$BL['be_admin_page_margin']             = 'margin';
$BL['be_admin_page_top']                = 'top';
$BL['be_admin_page_bottom']             = 'bottom';
$BL['be_admin_page_left']               = 'left';
$BL['be_admin_page_right']              = 'right';
$BL['be_admin_page_bg']                 = 'background';
$BL['be_admin_page_color']              = 'color';
$BL['be_admin_page_height']             = 'height';
$BL['be_admin_page_width']              = 'width';
$BL['be_admin_page_main']               = 'main';
$BL['be_admin_page_leftspace']          = 'left space';
$BL['be_admin_page_rightspace']         = 'right space';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'image';
$BL['be_admin_page_text']               = 'text';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'visited';
$BL['be_admin_page_pagetitle']          = 'page&nbsp;title';
$BL['be_admin_page_addtotitle']         = 'add&nbsp;to&nbsp;title';
$BL['be_admin_page_category']           = 'category';
$BL['be_admin_page_articlename']        = 'article&nbsp;name';
$BL['be_admin_page_blocks']             = 'blocks';
$BL['be_admin_page_allblocks']          = 'all blocks';
$BL['be_admin_page_col1']               = '3 column layout';
$BL['be_admin_page_col2']               = '2 column layout (main column right, nav column left)';
$BL['be_admin_page_col3']               = '2 column layout (main column left, nav column right)';
$BL['be_admin_page_col4']               = '1 column layout';
$BL['be_admin_page_header']             = 'header';
$BL['be_admin_page_footer']             = 'footer';
$BL['be_admin_page_topspace']           = 'top&nbsp;space';
$BL['be_admin_page_bottomspace']        = 'bottom&nbsp;space';
$BL['be_admin_page_button']             = 'save page layout';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'frontend setup: css data';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'save css data';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'frontend setup: templates';
$BL['be_admin_tmpl_default']            = 'default';
$BL['be_admin_tmpl_add']                = 'add&nbsp;template';
$BL['be_admin_tmpl_edit']               = 'edit template';
$BL['be_admin_tmpl_new']                = 'create new';
$BL['be_admin_tmpl_css']                = 'css file';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'error';
$BL['be_admin_tmpl_button']             = 'save template';
$BL['be_admin_tmpl_name']               = 'name';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'site structure and article list';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'title for this article is empty';
$BL['be_article_err2']                  = 'begin date given was wrong - set to now';
$BL['be_article_err3']                  = 'end date given was wrong - set to now';
$BL['be_article_title1']                = 'article basis information';
$BL['be_article_cat']                   = 'category';
$BL['be_article_atitle']                = 'article title';
$BL['be_article_asubtitle']             = 'subtitle';
$BL['be_article_abegin']                = 'begins';
$BL['be_article_aend']                  = 'ends';
$BL['be_article_aredirect']             = 'redirect to';
$BL['be_article_akeywords']             = 'keywords';
$BL['be_article_asummary']              = 'summary';
$BL['be_article_abutton']               = 'create new article';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'end date given was wrong - set to now + 1 week';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'edit article basis information';
$BL['be_article_eslastedit']            = 'last edit';
$BL['be_article_esnoupdate']            = 'form not updated';
$BL['be_article_esbutton']              = 'update article data';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'article content';
$BL['be_article_cnt_type']              = 'content type';
$BL['be_article_cnt_space']             = 'space';
$BL['be_article_cnt_before']            = 'before';
$BL['be_article_cnt_after']             = 'after';
$BL['be_article_cnt_top']               = 'top';
$BL['be_article_cnt_toplink']           = 'top link';
$BL['be_article_cnt_anchor']            = 'anchor';
$BL['be_article_cnt_ctitle']            = 'content title';
$BL['be_article_cnt_back']              = 'complete article info';
$BL['be_article_cnt_button1']           = 'Update';
$BL['be_article_cnt_button2']           = 'Create';
$BL['be_article_cnt_button3']           = 'Save &amp; close';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'article information';
$BL['be_article_cnt_ledit']             = 'edit article';
$BL['be_article_cnt_lvisible']          = 'switch visible/invisible';
$BL['be_article_cnt_ldel']              = 'delete this article';
$BL['be_article_cnt_ldeljs']            = 'Delete article?';
$BL['be_article_cnt_redirect']          = 'redirection';
$BL['be_article_cnt_edited']            = 'edited by';
$BL['be_article_cnt_start']             = 'start date';
$BL['be_article_cnt_end']               = 'end date';
$BL['be_article_cnt_add']               = 'add';
$BL['be_article_cnt_addtitle']          = 'add new content part';
$BL['be_article_cnt_up']                = 'move content up';
$BL['be_article_cnt_down']              = 'move content down';
$BL['be_article_cnt_edit']              = 'edit content part';
$BL['be_article_cnt_delpart']           = 'delete this article content part';
$BL['be_article_cnt_delpartjs']         = 'Delete content part?';
$BL['be_article_cnt_center']            = 'article center';

// content forms
$BL['be_cnt_plaintext']                 = 'plain text';
$BL['be_cnt_htmltext']                  = 'html text';
$BL['be_cnt_image']                     = 'image';
$BL['be_cnt_position']                  = 'position';
$BL['be_cnt_pos0']                      = 'Above, left';
$BL['be_cnt_pos1']                      = 'Above, center';
$BL['be_cnt_pos2']                      = 'Above, right';
$BL['be_cnt_pos3']                      = 'Bottom, left';
$BL['be_cnt_pos4']                      = 'Bottom, center';
$BL['be_cnt_pos5']                      = 'Bottom, right';
$BL['be_cnt_pos6']                      = 'In text, left';
$BL['be_cnt_pos7']                      = 'In text, right';
$BL['be_cnt_pos0i']                     = 'align the image above and left of the text block';
$BL['be_cnt_pos1i']                     = 'align the image above and centered of the text block';
$BL['be_cnt_pos2i']                     = 'align the image above and right of the text block';
$BL['be_cnt_pos3i']                     = 'align the image below and left of the text block';
$BL['be_cnt_pos4i']                     = 'align the image below and centered of the text block';
$BL['be_cnt_pos5i']                     = 'align the image above and right of the text block';
$BL['be_cnt_pos6i']                     = 'align the image left within the text block';
$BL['be_cnt_pos7i']                     = 'align the image right within the text block';
$BL['be_cnt_maxw']                      = 'max.&nbsp;width';
$BL['be_cnt_maxh']                      = 'max.&nbsp;height';
$BL['be_cnt_enlarge']                   = 'click&nbsp;enlarge';
$BL['be_cnt_caption']                   = 'caption';
$BL['be_cnt_subject']                   = 'subject';
$BL['be_cnt_recipient']                 = 'recipient';
$BL['be_cnt_buttontext']                = 'button text';
$BL['be_cnt_sendas']                    = 'send as';
$BL['be_cnt_text']                      = 'text';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'form fields';
$BL['be_cnt_code']                      = 'code';
$BL['be_cnt_infotext']                  = 'info&nbsp;text';
$BL['be_cnt_subscription']              = 'subscription';
$BL['be_cnt_labelemail']                = 'label&nbsp;email';
$BL['be_cnt_tablealign']                = 'table&nbsp;align';
$BL['be_cnt_labelname']                 = 'label&nbsp;name';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;subscr.';
$BL['be_cnt_allsubsc']                  = 'all&nbsp;subscr.';
$BL['be_cnt_default']                   = 'default';
$BL['be_cnt_left']                      = 'left';
$BL['be_cnt_center']                    = 'center';
$BL['be_cnt_right']                     = 'right';
$BL['be_cnt_buttontext']                = 'button&nbsp;text';
$BL['be_cnt_successtext']               = 'success&nbsp;text';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'change.email';
$BL['be_cnt_openimagebrowser']          = 'open image browser';
$BL['be_cnt_openfilebrowser']           = 'open file browser';
$BL['be_cnt_sortup']                    = 'move up';
$BL['be_cnt_sortdown']                  = 'move down';
$BL['be_cnt_delimage']                  = 'remove selected image';
$BL['be_cnt_delfile']                   = 'remove selected file';
$BL['be_cnt_delmedia']                  = 'remove selected media';
$BL['be_cnt_column']                    = 'column';
$BL['be_cnt_imagespace']                = 'image&nbsp;space';
$BL['be_cnt_directlink']                = 'direct link';
$BL['be_cnt_target']                    = 'target';
$BL['be_cnt_target1']                   = 'in a new window';
$BL['be_cnt_target2']                   = 'in parent frame of the window';
$BL['be_cnt_target3']                   = 'in same window without frames';
$BL['be_cnt_target4']                   = 'in the same frame or window';
$BL['be_cnt_bullet']                    = 'list (table)';
$BL['be_cnt_ullist']                    = 'list';
$BL['be_cnt_ullist_desc']               = '~ = 1st Level, &nbsp; ~~ = 2nd level, &nbsp; etc.';
$BL['be_cnt_linklist']                  = 'link list';
$BL['be_cnt_plainhtml']                 = 'plain html';
$BL['be_cnt_files']                     = 'files';
$BL['be_cnt_description']               = 'description';
$BL['be_cnt_linkarticle']               = 'link article';
$BL['be_cnt_articles']                  = 'articles';
$BL['be_cnt_movearticleto']             = 'move selected article to link article list';
$BL['be_cnt_removearticleto']           = 'remove selected article from link article list';
$BL['be_cnt_mediatype']                 = 'media type';
$BL['be_cnt_control']                   = 'control';
$BL['be_cnt_showcontrol']               = 'show control bar';
$BL['be_cnt_autoplay']                  = 'autoplay';
$BL['be_cnt_source']                    = 'source';
$BL['be_cnt_internal']                  = 'internal';
$BL['be_cnt_openmediabrowser']          = 'open media browser';
$BL['be_cnt_external']                  = 'external';
$BL['be_cnt_mediapos0']                 = 'left (default)';
$BL['be_cnt_mediapos1']                 = 'center';
$BL['be_cnt_mediapos2']                 = 'right';
$BL['be_cnt_mediapos3']                 = 'block, left';
$BL['be_cnt_mediapos4']                 = 'block, right';
$BL['be_cnt_mediapos0i']                = 'align media above and left of the text block';
$BL['be_cnt_mediapos1i']                = 'align media above and centered of the text block';
$BL['be_cnt_mediapos2i']                = 'align media above and right of the text block';
$BL['be_cnt_mediapos3i']                = 'align media left within the text block';
$BL['be_cnt_mediapos4i']                = 'align media right within the text block';
$BL['be_cnt_setsize']                   = 'set size';
$BL['be_cnt_set1']                      = 'set media size to 160x120px';
$BL['be_cnt_set2']                      = 'set media size to 240x180px';
$BL['be_cnt_set3']                      = 'set media size to 320x240px';
$BL['be_cnt_set4']                      = 'set media size to 480x360px';
$BL['be_cnt_set5']                      = 'clear media width and height';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'create new pagelayout';
$BL['be_admin_page_name']               = 'layout name';
$BL['be_admin_page_edit']               = 'edit pagelayout';
$BL['be_admin_page_render']             = 'rendering';
$BL['be_admin_page_table']              = 'table';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'custom';
$BL['be_admin_page_custominfo']         = 'from template main block';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'No page layout available!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'search';
$BL['be_cnt_results']                   = 'results';
$BL['be_cnt_results_per_page']          = 'per&nbsp;page (if empty show max. 25)';
$BL['be_cnt_opennewwin']                = 'open new window';
$BL['be_cnt_searchlabeltext']           = 'these are predefined texts and values for the search form and search result page and texts are shown when more than the given count of results per page should be shown.';
$BL['be_cnt_input']                     = 'input';
$BL['be_cnt_style']                     = 'style';
$BL['be_cnt_result']                    = 'result';
$BL['be_cnt_next']                      = 'next';
$BL['be_cnt_previous']                  = 'previous';
$BL['be_cnt_align']                     = 'align';
$BL['be_cnt_searchformtext']            = 'the following texts are listed when the search form is opened or results for given search are (not) available.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'no result';
$BL['be_cnt_search_default_type']       = 'search type';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'disable';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'article owner';
$BL['be_article_adminuser']             = 'admin user';
$BL['be_article_username']              = 'author';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible for users logged on only';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
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
$BL['be_admin_startup_button']          = 'save startup text';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'guestbook/comment';
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
$BL['be_newsletter_testerror']          = '<p style="color:#CC3300;">the test email address</p><blockquote>###TEST###</blockquote><p style="color:#CC3300;">is NOT valid!<br />&nbsp;<br />Try again please!</p>';
$BL['be_newsletter_to']                 = 'Recipients';
$BL['be_newsletter_ready']              = 'sending newsletter: DONE';
$BL['be_newsletter_readyfailed']        = 'Failed newsletter sending to';
$BL['be_subnav_msg_subscribers']        = 'newsletter subscribers';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'sitemap';
$BL['be_cnt_sitemap_catimage']          = 'level icon';
$BL['be_cnt_sitemap_articleimage']      = 'article icon';
$BL['be_cnt_sitemap_display']           = 'display';
$BL['be_cnt_sitemap_structuronly']      = 'structure levels only';
$BL['be_cnt_sitemap_structurarticle']   = 'structure levels + articles';
$BL['be_cnt_sitemap_catclass']          = 'level class';
$BL['be_cnt_sitemap_articleclass']      = 'article class';
$BL['be_cnt_sitemap_count']             = 'counter';
$BL['be_cnt_sitemap_classcount']        = 'add to class name';
$BL['be_cnt_sitemap_noclasscount']      = 'don\'t add to class name';
$BL['be_cnt_sitemap_without_parent']    = 'without start level';

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

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'ext. content';
$BL['be_cnt_pages_select']              = 'select file';
$BL['be_cnt_pages_fromfile']            = 'file from structure';
$BL['be_cnt_pages_manually']            = 'custom path/file or URL';
$BL['be_cnt_pages_cust']                = 'file/URL';
$BL['be_cnt_pages_from']                = 'source';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'rollover images';
$BL['be_cnt_reference_basis']           = 'alignment';
$BL['be_cnt_reference_horizontal']      = 'horizontal';
$BL['be_cnt_reference_vertical']        = 'vertical';
$BL['be_cnt_reference_aligntext']       = 'small reference images';
$BL['be_cnt_reference_largetext']       = 'large reference image';
$BL['be_cnt_reference_zoom']            = 'zoom';
$BL['be_cnt_reference_middle']          = 'middle';
$BL['be_cnt_reference_border']          = 'border';
$BL['be_cnt_reference_block']           = 'block w x h';

// added: 31-05-2004
$BL['be_article_rendering']             = 'rendring';
$BL['be_article_nosummary']             = 'hide summary text';
$BL['be_article_forlist']               = 'article listing';
$BL['be_article_forfull']               = 'article detail';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ATTENTION!</strong> The &quot;SETUP&quot; directory still exists! Delete that directory - it\'s potential security problem.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'banned words';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'set cookie';
$BL['be_cnt_guestbook_allowed']         = 'allowed again after';
$BL['be_cnt_guestbook_seconds']         = 'seconds';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Do you really want to delete \nALL FILES in trash?";
$BL['be_ftrash_delallfiles']            = 'delete all files in trash';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'CSV subscribers import';
$BL['be_newsletter_importtitle']        = 'import of newsletter recipients';
$BL['be_newsletter_entriesfound']       = 'entries found';
$BL['be_newsletter_foundinfile']        = 'in file';
$BL['be_newsletter_addresses']          = 'addresses';
$BL['be_newsletter_csverror']           = 'Imported CSV file seems to be incorrect! Check delimeter!';
$BL['be_newsletter_addressesadded']     = 'addresses added';
$BL['be_newsletter_newimport']          = 'import';
$BL['be_newsletter_importerror']        = 'the following datas are invalid:';
$BL['be_newsletter_shouldbe1']          = 'CSV/TXT file should be formatted like this:';
$BL['be_newsletter_shouldbe2']          = 'default = <b>;</b>';
$BL['be_newsletter_sample']             = 'sample';
$BL['be_newsletter_selectCSV']          = 'select CSV file';
$BL['be_newsletter_delimeter']          = 'delimeter';
$BL['be_newsletter_importCSV']          = 'import CSV file';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'ordering of assigned articles';
$BL['be_admin_struct_orderdate']        = 'creation date';
$BL['be_admin_struct_orderchangedate']  = 'change date';
$BL['be_admin_struct_orderstartdate']   = 'start date';
$BL['be_admin_struct_orderdesc']        = 'descending';
$BL['be_admin_struct_orderasc']         = 'ascending';
$BL['be_admin_struct_ordermanual']      = 'manual (arrow up/down)';
$BL['be_cnt_sitemap_startid']           = 'start at';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'map';
$BL['be_save_btn']                      = 'Save';
$BL['be_cmap_location_error_notitle']   = 'fill in a title for this location.';
$BL['be_cnt_map_add']                   = 'add location';
$BL['be_cnt_map_edit']                  = 'edit location';
$BL['be_cnt_map_title']                 = 'location title';
$BL['be_cnt_map_info']                  = 'entry/info';
$BL['be_cnt_map_list']                  = 'location list';
$BL['be_btn_delete']                    = 'Do you really want to \ndelete this location?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP variables';
$BL['be_cnt_vars']                      = 'variables';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'copy article';
$BL['be_func_struct_nocopy']            = 'disable copy article';
$BL['be_func_struct_copy_level']        = 'copy structure level';
$BL['be_func_struct_no_copy']           = "It's not possible to copy the root level!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minute';
$BL['be_date_minutes']                  = 'minutes';
$BL['be_date_hour']                     = 'hour';
$BL['be_date_hours']                    = 'hours';
$BL['be_date_day']                      = 'day';
$BL['be_date_days']                     = 'days';
$BL['be_date_week']                     = 'week';
$BL['be_date_weeks']                    = 'weeks';
$BL['be_date_month']                    = 'month';
$BL['be_date_months']                   = 'months';
$BL['be_off']                           = 'Off';
$BL['be_on']                            = 'On';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'timeout';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'user groups';
$BL['be_admin_group_add']               = 'add group';
$BL['be_admin_group_nogroup']           = 'no user group found';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'forums list';
$BL['be_forum_title']                   = 'forum title';
$BL['be_forum_permission']              = 'permissions';
$BL['be_forum_add']                     = 'add forum';
$BL['be_forum_titleedit']               = 'edit forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'custom';
$BL['be_show_content']                  = 'display';
$BL['be_main_content']                  = 'main column';
$BL['be_admin_template_jswarning']      = 'Warning!!! \nCustom blocks may change! \n\nIf you cancel \nreset your pagelayout setting! \n\nChange template?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS feed';
$BL['be_cnt_rssfeed_url']               = 'RSS url';
$BL['be_cnt_rssfeed_item']              = 'items';
$BL['be_cnt_rssfeed_max']               = 'max.';
$BL['be_cnt_rssfeed_cut']               = 'hide 1st item';

$BL['be_ctype_simpleform']              = 'form';

$BL['be_cnt_onsuccess']                 = 'on success';
$BL['be_cnt_onerror']                   = 'on error';
$BL['be_cnt_onsuccess_redirect']        = 'redirect on success';
$BL['be_cnt_onerror_redirect']          = 'redirect on error';

$BL['be_cnt_form_class']                = 'form class';
$BL['be_cnt_label_wrap']                = 'label wrap';
$BL['be_cnt_error_class']               = 'error class';
$BL['be_cnt_req_mark']                  = 'required mark';
$BL['be_cnt_mark_as_req']               = 'mark as required';
$BL['be_cnt_mark_as_del']               = 'mark item for deletion';


$BL['be_cnt_type']                      = 'type';
$BL['be_cnt_label']                     = 'label';
$BL['be_cnt_needed']                    = 'required';
$BL['be_cnt_delete']                    = 'delete';
$BL['be_cnt_value']                     = 'value';
$BL['be_cnt_error_text']                = 'error text';
$BL['be_cnt_css_style']                 = 'CSS style';
$BL['be_cnt_css_class']                 = 'CSS class';
$BL['be_cnt_send_copy_to']              = 'copy to';

$BL['be_cnt_field']                     = array(
    "text"=>'text (single-line)',
    "email"=>'email',
    "textarea"=>'text (multi-line)',
    "hidden"=>'hidden',
    "password"=>'password',
    "select"=>'select menu',
    "list"=>'list menu',
    "checkbox"=>'checkbox',
    "checkboxcopy"=>'checkbox (email copy on/off)',
    "radio"=>'radio button',
    "upload"=>'file',
    "submit"=>'send button',
    "reset"=>'reset button',
    "break"=>'break', "breaktext"=>'break text',
    "special"=>'text (spezial)',
    "captchaimg"=>'captcha image',
    "captcha"=>'captcha code',
    'newsletter'=>'newsletter',
    'selectemail'=>'select email menu',
    'country'=>'select country menu',
    'mathspam'=>'math spam protect',
    'summing'=>'summing',
    'subtract'=>'subtract',
    'divide'=>'divide', 'multiply'=>'multiply',
    'calculation'=>'calculation:',
    'formtracking_off'=>'disable form tracking',
    'checktofrom'=>'email of recipient must be different from sender',
    'recaptcha'=>'reCAPTCHA',
    'recaptcha_signapikey'=>'Sign up for a reCAPTCHA API key',
    'recaptchainv' => 'Invisible reCAPTCHA',
);

$BL['be_cnt_optin']                     = 'Double Opt-In';
$BL['be_cnt_doubleoptin']               = 'activate Double Opt-In according to <a href="https://en.wikipedia.org/wiki/General_Data_Protection_Regulation" target="_blank">General Data Protection Regulation</a> (GDPR)';

$BL['be_cnt_access']                    = 'access';
$BL['be_cnt_activated']                 = 'activated';
$BL['be_cnt_available']                 = 'available';
$BL['be_cnt_guests']                    = 'guests';
$BL['be_cnt_admin']                     = 'admin';
$BL['be_cnt_write']                     = 'write';
$BL['be_cnt_read']                      = 'read';

$BL['be_cnt_no_wysiwyg_editor']         = 'disable WYSIWYG editor';
$BL['be_cnt_cache_update']              = 'reset cache';
$BL['be_cnt_cache_delete']              = 'delete cache';
$BL['be_cnt_cache_delete_msg']          = 'Do you really want to delete cache?  \nThis can affect search too.  \n';

$BL['be_admin_usr_issection']           = 'login section';
$BL['be_admin_usr_ifsection0']          = 'frontend';
$BL['be_admin_usr_ifsection1']          = 'backend';
$BL['be_admin_usr_ifsection2']          = 'frontend and backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = 'edit this article content part';
$BL['be_func_content_paste0']            = 'paste in article';
$BL['be_func_content_paste']             = 'paste later article content part';
$BL['be_func_content_cut']               = 'cut this article content part';
$BL['be_func_content_no_cut']            = "It's not possible to cut the article content part!";
$BL['be_func_content_copy']              = 'copy this article content part part';
$BL['be_func_content_no_copy']           = "It's not possible to copy the article content part!";
$BL['be_func_content_paste_cancel']      = 'cancel article content part change';

$BL['be_cnt_move_deleted'] = 'remove delete files';
$BL['be_cnt_move_deleted_msg'] = 'Do you really want to move all files  \nmarked as deleted into special deletion folder?  \n';

$BL['be_admin_struct_permit'] = 'authorized to access (let empty for everybody)';
$BL['be_admin_struct_adduser_all']   = 'take over all users';
$BL['be_admin_struct_adduser_this']  = 'take over selected user';
$BL['be_admin_struct_remove_all']    = 'remove all users';
$BL['be_admin_struct_remove_this']   = 'remove selected user';

$BL['be_ctype_alias'] = 'contentpart alias';
$BL['be_cnt_setting'] = 'take over';
$BL['be_cnt_spaces'] = 'spaces of contentpart alias';
$BL['be_cnt_toplink'] = 'top link setting of contentpart alias';
$BL['be_cnt_block'] = 'display (block) setting of contentpart alias';
$BL['be_cnt_title'] = 'titles of contentpart alias';
$BL['be_cnt_status'] = 'visibility of contentpart alias';
$BL['be_cnt_plugin_n.a.'] = 'plugin not available';

$BL['be_file_replace'] = 'Replace eponymous files';

$BL['be_alias_articleID'] = 'alias ID';
$BL['be_alias_useAll'] = "use this article&#8217;s header data";
$BL['be_article_morelink'] = '[more&#8230;] link';
$BL['be_admin_tmpl_copy']               = 'copy template';

$BL['be_ctype_filelist1']                = 'file list pro';
$BL['be_cnt_fpro_usecaption']            = 'use file center &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']                = 'Keywords';
$BL['be_admin_keywords_key']            = 'KEYWORD';
$BL['be_admin_keywords_err']            = 'Insert a unique KEYWORD name';
$BL['be_admin_keyword_edit']            = 'edit KEYWORD';
$BL['be_admin_keyword_del']             = 'delete KEYWORD';
$BL['be_admin_keyword_delmsg']          = 'Do you really want\nto delete KEYWORD?';
$BL['be_admin_keyword_add']             = 'add KEYWORD';

$BL['be_cnt_transparent'] = 'Flash transparent';

// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']   = 'kill date';
$BL['be_func_switch_contentpart'] = 'Do you really want to switch content part? \n\nBe very careful doing so! \nImportant settings might be overwritten! \n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>ATTENTION!</strong> The &quot;CODE-SNIPPETS&quot; directory still exists! Delete directory <strong>phpwcms_code_snippets</strong> - this is a potential security problem.';
$BL['gd_not_loaded'] = '<strong>No GD functionality available!</strong> Please make sure that the PHP GD library is activated, otherwise the processing of images will not work reliably.';

$BL['be_ctype_poll'] = 'poll';
$BL['be_cnt_pos8']                      = 'table, left';
$BL['be_cnt_pos9']                      = 'table, right';
$BL['be_cnt_pos8i']                     = 'align image left in table';
$BL['be_cnt_pos9i']                     = 'align image right in table';


$BL['be_WYSIWYG']                       = 'WYSIWYG editor';
$BL['be_WYSIWYG_disabled']              = 'WYSIWYG editor disabled';
$BL['be_admin_struct_acat_hiddenactive'] = 'visible when active';

$BL['be_login_jsinfo']                  = 'Please enable JavaScript which is neccessary in the backend!';

$BL['be_admin_struct_maxlist']          = 'max. articles in list mode';

$BL['be_admin_optgroup_label']          = array(1 => 'text', 2 => 'image', 3 => 'form', 4 => 'admin', 5 => 'special');
$BL['be_cnt_articlemenu_maxchar']       = 'max. Chars';

$BL['be_cnt_sysadmin_system']           = 'system';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date']               = 'Your installation is up to date, no updates are available for this version of phpwcms.';
$BL['Version_not_up_to_date']           = 'Your installation does <b>not</b> seem to be up to date. Updates are available for this version of phpwcms, please visit <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a> to obtain the latest version.';
$BL['Latest_version_info']              = 'The latest official version is <b>phpwcms %s</b>.';
$BL['Current_version_info']             = 'You are running <b>phpwcms %s</b>.';
$BL['Connect_socket_error']             = 'Unable to open connection to phpwcms Server, reported error is:<br />%s';
$BL['Socket_functions_disabled']        = 'Unable to use socket functions.';
$BL['Mailing_list_subscribe_reminder']  = 'For the latest information on updates to phpwcms, why not subscribe to our <a href="http://eepurl.com/bm-BrH" target="_blank">mailing list</a>.';
$BL['Version_information']              = 'phpwcms Version Information';

$BL['be_cnt_search_highlight']          = 'highlight';
$BL['be_cnt_results_wordlimit']         = 'max. words for summary';
$BL['be_cnt_page_of_pages']             = 'search navi';
$BL['be_cnt_page_of_pages_descr']       = '{PREV:Back} page #/##, result ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Next}';
$BL['be_cnt_search_show_top']           = 'top';
$BL['be_cnt_search_show_bottom']        = 'bottom';
$BL['be_cnt_search_show_next']          = 'next (also if no link)';
$BL['be_cnt_search_show_prev']          = 'previous (also if no link)';
$BL['be_cnt_search_show_forall']        = 'show always';
$BL['be_cnt_search_startlevel']         = 'search start';
$BL['be_cnt_results_minchar']           = 'minimum number of chars of search input';
$BL['be_cnt_search_hidesummary']        = 'hide search teaser text';
$BL['be_cnt_search_searchnot']          = 'no search for';

$BL['be_cnt_pagination']                = 'paginate content parts';
$BL['be_article_pagination']            = 'paginate articles';
$BL['be_article_per_page']              = 'articles per page';
$BL['be_pagination']                    = 'pagination';

$BL['be_ctype_recipe']                  = 'recipe';
$BL['be_ctype_faq']                     = 'faq';
$BL['be_cnt_additional']                = 'addition';
$BL['be_cnt_question']                  = 'question';
$BL['be_cnt_answer']                    = 'answer';
$BL['be_cnt_same_as_summary']           = 'use article image data';
$BL['be_cnt_sorting']                   = 'sorting';
$BL['be_cnt_imgupload']                 = 'image&nbsp;upload';
$BL['be_cnt_filesize']                  = 'filesize';
$BL['be_cnt_captchalength']             = 'captcha code length';
$BL['be_cnt_chars']                     = 'chars';
$BL['be_cnt_download']                  = 'download';
$BL['be_cnt_download_direct']           = 'direct (not recommend!)';
$BL['be_cnt_database']                  = 'database';
$BL['be_cnt_formsave_in_db']            = 'save form results';

$BL['be_cnt_email_notify']              = 'notify by email';
$BL['be_cnt_notify_by_email']           = 'by email to';
$BL['be_cnt_last_edited']               = 'last change';

$BL['be_cnt_export_selection']          = 'export selection';
$BL['be_cnt_delete_duplicates']         = 'delete duplicates';
$BL['be_cnt_new_recipient']             = 'add recipient';

$BL['be_cnt_newsletter_prepare']        = 'newsletter active';
$BL['be_cnt_newsletter_prepare1']       = 'all recipients will be taken over to sending queue';
$BL['be_cnt_newsletter_prepare2']       = 'sending queue will be updated&#8230;';

$BL['be_cnt_export']                    = 'export';
$BL['be_cnt_formsave_profile']          = 'save user profile data';
$BL['be_profile_label_add']             = 'addition';
$BL['be_profile_label_website']         = 'url';
$BL['be_profile_label_gender']          = 'gender';
$BL['be_profile_label_birthday']        = 'birthday';

$BL['be_cnt_store_in']                  = 'save to field';
$BL['be_aboutlink_title']               = 'information about phpwcms and license';

$BL['be_shortdate']                     = 'n/j/y';
$BL['be_shortdatetime']                 = 'n/j/y G:i';
$BL['be_longdatetime']                  = 'm/d/Y H:i:s';

$BL['be_confirm_sending']               = 'confirm sending';
$BL['be_confirm_text']                  = 'Yes, send newsletter to all recipients!';

$BL['be_cnt_queued']                    = 'queuing';
$BL['be_last_sending']                  = 'last sending';
$BL['be_last_edited']                   = 'last edited';
$BL['be_total']                         = 'total';

$BL['be_settings']                      = 'settings';
$BL['be_ctype']                         = 'contentpart';
$BL['be_selection']                     = 'selection';

$BL['be_ctype_module']                  = 'plug-in';
$BL['be_cnt_lightbox']                  = 'gallery image';
$BL['be_cnt_behavior']                  = 'behavior';
$BL['be_cnt_imglist_nocaption']         = 'hide caption for thumbnails';

$BL['be_ctype_felogin']                 = 'frontend login';
$BL['be_cookie_runtime']                = 'cookie expire';
$BL['be_locale']                        = 'locale';
$BL['be_date_format']                   = 'date format';

$BL['be_check_login_against']           = 'validate login against';
$BL['be_userprofile_db']                = 'user profile database';
$BL['be_backenduser_db']                = 'backend user database';
$BL['be_check_login_allow_email']       = 'Accept email as login';

$BL['be_gb_post_login']                 = 'post for users logged in only';
$BL['be_gb_show_login']                 = 'show for users logged in only';
$BL['be_gb_urlcheck']                   = 'enable remote URL validation';
$BL['be_order']                         = 'order';

$BL['be_unique_teaser_entry']           = 'show teaser/link article only once per page';
$BL['be_allowed_tags']                  = 'allowed tags';
$BL['be_fe_login_url']                  = 'FE login url';
$BL['be_ctype_imagesdiv']               = 'images &lt;div&gt;';
$BL['be_cnt_imagecenter']               = 'center horizontal/vertical';
$BL['be_cnt_imagenocenter']             = 'do not center';
$BL['be_cnt_imagecenterh']              = 'center horizontal';
$BL['be_cnt_imagecenterv']              = 'center vertical';
$BL['be_check_against_category_alias']  = 'link single article inside structure level with structure level';

$BL['be_overwrite_default']             = 'Will overwrite default settings of config file';
$BL['be_cnt_sortvalue']                 = 'sort&nbsp;value';
$BL['be_dialog_warn_nosave']            = 'If you continue no change will be saved!\nAre you sure you want to continue?';
$BL['be_cnt_paginate_subsection']       = 'subsection';
$BL['be_cnt_subsection_tite']           = 'subsection title';
$BL['be_cnt_subsection_warning']        = 'Numbering subsections (paginate content parts) is available for\nmain column (CONTENT) only!';

$BL['be_no_search']                     = 'no search';
$BL['be_priorize']                      = 'prioritization';
$BL['be_change_articleID']              = 'change article ID';
$BL['be_title_wrap']                    = 'wrap article title';

$BL['be_no_rss']                        = 'RSS';
$BL['be_article_urlalias']              = 'article alias';

$BL['be_image_crop']                    = 'crop thumbnail';
$BL['be_image_cropit']                  = 'crop image';
$BL['be_image_align']                   = 'image alignment';

$BL['be_ctype_flashplayer']             = 'HTML5/Flash media player';
$BL['be_flashplayer_caption']           = 'caption';
$BL['be_flashplayer_thumbnail']         = 'thumbnail';
$BL['be_flashplayer_selectsize']        = 'Select player size';
$BL['be_flash_media']                   = 'Flash';
$BL['be_html5_media']                   = 'HTML5';
$BL['be_html5_h264']                    = 'MPEG/H.264';
$BL['be_html5_webm']                    = 'WebM';
$BL['be_html5_ogg']                     = 'Ogg';
$BL['be_media_format']                  = 'format';
$BL['be_media_watermark']               = 'watermark';
$BL['be_skin']                          = 'skin';
$BL['be_foreground_color']              = 'foreground color';
$BL['be_background_color']              = 'background color';
$BL['be_highlight_color']               = 'highlight color';

$BL['be_check_feuser_profile']          = 'frontend user profile';
$BL['be_check_feuser_registration']     = 'registration';
$BL['be_check_feuser_manage']           = 'managed by user';
$BL['be_hide_active_articlelink']       = 'hide active article in article menu';

$BL['be_module_search']                 = 'search also';

$BL['be_ctype_imagesspecial']           = 'images special';
$BL['be_image_WxHpx']                   = 'W x H px';
$BL['be_fx_1']                          = 'effect 1';
$BL['be_fx_2']                          = 'effect 2';
$BL['be_fx_3']                          = 'effect 3';
$BL['be_image_zoom']                    = 'zoomed view';
$BL['be_image_delete_js']               = 'Do you want to delete selected image entry?';

$BL['be_news']                          = 'News';
$BL['be_news_create']                   = 'Create news entry';
$BL['be_tags']                          = 'tags';
$BL['be_title']                         = 'title';
$BL['be_delete_dataset']                = 'Delete selected dataset?';
$BL['be_action_notvalid']               = 'Your last selected action was dropped because it was not valid!';
$BL['be_action_deleted']                = 'The selected dataset having ID {ID} was deleted.';
$BL['be_action_status']                 = 'The status of the selected dataset having ID {ID} was changed.';
$BL['be_data_select_failed']            = 'Accessing the selected data has failed. Please proof your selection.';
$BL['be_alias']                         = 'alias';
$BL['be_url_value']                     = 'URL title';
$BL['default_date_format']              = 'DD/MM/YYYY';
$BL['default_date']                     = 'd/m/Y'; // do not use something diffrent than "d, m, Y" here
$BL['default_date_delimiter']           = '/';
$BL['default_time_format']              = 'HH:MM';
$BL['default_time']                     = 'H:i';  // do not use something diffrent than "H, i" here
$BL['be_place']                         = 'place';
$BL['be_teasertext']                    = 'teaser text';
$BL['be_published']                     = 'publish';
$BL['be_show_archived']                 = 'available after end date (archive)';
$BL['be_save_copy']                     = 'save entry as duplicate';
$BL['be_read_more_link']                = 'more URL/ID';
$BL['be_news_name_mandatory']           = "Fill in a news title. It's mandatory!";
$BL['be_successfully_saved']            = 'All data were saved successfully!';
$BL['be_successfully_updated']          = 'All data were updated successfully!';
$BL['be_error_while_save']              = 'Storing data failed.';
$BL['be_copyright']                     = 'copyright';
$BL['be_file_multiple_upload']          = 'multiple file upload';
$BL['be_files_select_available']        = 'Select previously uploaded files';
$BL['be_files_browse']                  = 'Browse files';
$BL['be_files_upload']                  = 'Upload selected files';
$BL['be_archive']                       = 'archive';
$BL['be_off']                           = 'off';
$BL['be_on']                            = 'on';
$BL['be_random']                        = 'random';
$BL['be_sorted']                        = 'sorted';
$BL['be_granted_download']              = 'secured frontend download only';
$BL['be_granted_feuser']                = 'Only visible for logged-in frontend users';
$BL['be_hidden_for_feuser']             = 'Hidden for logged-in frontend users';
$BL['be_visible_for_everybody']         = 'Visible for everybody (default)';
$BL['be_fileuploader_typeError']        = "{file} has an invalid extension. Valid extension(s): {extensions}.";
$BL['be_fileuploader_sizeError']        = "{file} is too large, maximum file size is {sizeLimit}.";
$BL['be_fileuploader_minSizeError']     = "{file} is too small, minimum file size is {minSizeLimit}.";
$BL['be_fileuploader_emptyError']       = "{file} is empty, please select files again without it.";
$BL['be_fileuploader_noFilesError']     = "No files to upload.";
$BL['be_fileuploader_onLeave']          = "The files are being uploaded, if you leave now the upload will be cancelled.";
$BL['be_fileuploader_dragText']         = "Drop files here to upload!";
$BL['be_fileuploader_uploadButtonText'] = 'Select files or drop here';
$BL['be_delete_selected_files']         = 'Delete selected files';
$BL['be_delete_selected_files_confirm'] = 'Do you really want to delete all selected files?';

$BL['be_ctype_tabs']                    = 'tabs';
$BL['be_tab_add']                       = 'add tab';
$BL['be_tab_name']                      = 'tab';
$BL['be_headline']                      = 'headline';
$BL['be_tab_delete_js']                 = 'Do you want to delete the selected tab?';

$BL['be_pagniate_count']                = 'items per page';
$BL['be_limit_to']                      = 'limit to';
$BL['be_archived_items']                = 'archived items';
$BL['be_include']                       = 'include';
$BL['be_exclude']                       = 'exclude';
$BL['be_solely']                        = 'solely';
$BL['be_fsearch_not']                   = 'NOT';
$BL['be_date_year']                     = 'year';
$BL['be_archive_link']                  = 'archive link';
$BL['be_use_prio']                      = 'apply priorization';
$BL['be_skip_first_items']              = 'skip top items';
$BL['be_news_detail_link']              = 'news article';

$BL['be_gallerydownload']               = 'allow download in gallery';
$BL['be_gallery_root']                  = 'gallery root directory';
$BL['be_gallery_directory']             = 'gallery subdirectory';
$BL['be_gallery']                       = 'gallery';

$BL['be_sort_date']                     = 'sort date';

$BL['group_superuser']                  = 'superuser';
$BL['group_admin']                      = 'administrator';
$BL['group_editor']                     = 'editor';
$BL['group_newsletter']                 = 'newsletter editor';
$BL['group_client']                     = 'client';
$BL['group_guest']                      = 'guest';

$BL['php_function']                     = 'php function';
$BL['article_menu_title']               = 'menu title';

$BL['content_type']                     = 'content-type';
$BL['automatic']                        = 'automatic';

$BL['random_image']                     = 'select images randomly';
$BL['limit_image_from_list']            = 'Images max.';

$BL['alt_image']                        = 'alt. image';
$BL['alt_text']                         = 'alt. text';
$BL['over']                             = 'over';
$BL['js_lib']                           = 'JS Library';
$BL['js_lib_alwaysload']                = 'always load';
$BL['frontendjs_load']                  = 'load frontend.js (more for historical reasons)';
$BL['googleapi_load']                   = 'use CDN';

$BL['fancyupload_clear_list']           = 'Clear List';
$BL['fancyupload_file_uploaded']        = 'File was uploaded';
$BL['fancyupload_file_error']           = 'An error occured';
$BL['fancyupload_adblock_error']        = 'To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).';
$BL['fancyupload_flashblock_error']     = 'To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).';
$BL['fancyupload_required_error']       = 'A required file was not found, please be patient and we fix this.';
$BL['fancyupload_flash_error']          = 'To enable the embedded uploader, install the latest Adobe Flash plugin.';

$BL['be_cnt_function_validate']         = 'PHP validation';
$BL['be_structform_selected_cp']        = 'Limit selection of usable content parts';
$BL['be_structform_select_cp']          = 'Select content parts';

$BL['source_image_not_found']           = 'Source image error: The image %s seems not to exist';
$BL['form_force_ssl']                   = 'Force sending forms with SSL';
$BL['numerize_title']                   = 'Numbered instead of article titles';
$BL['be_article_noteaser']              = 'no teaser';
$BL['be_acat_disable301']               = 'article 301 redirect';

$BL['file_actions_step1']               = "Step 1: select folder";
$BL['file_actions_step2']               = "Step 2: select file";
$BL['file_actions_step3']               = "Step 3: select action";
$BL['file_actions_button']              = 'Perform action';
$BL['file_actions_no']                  = 'No files for editing. Please select another folder ';
$BL['file_actions_delete']              = 'Are you sure that the selected files should be deleted?';
$BL['file_actions_bemuser']             = 'The selected files will be assigned to the new user and moved to its root.';
$BL['file_actions_bemfolder']           = 'Please select the destination folder. The selected files are moved to this folder. ';
$BL['file_actions_pdl_empty']           = 'select action';
$BL['file_actions_pdl_delete']          = 'delete files';
$BL['file_actions_pdl_move']            = 'move files';
$BL['file_actions_pdl_status']          = 'change status';
$BL['file_actions_pdl_user']            = 'change owner';
$BL['file_actions_msg_move']            = 'Files were moved successfully';
$BL['file_actions_msg_delete']          = 'Files were deleted successfully';
$BL['file_actions_msg_status']          = 'The status of files successfully changed';
$BL['file_actions_msg_error']           = 'There are no files selected';
$BL['file_actions_msg_user']            = 'Files were successfully assigned to the new user';

$BL['be_imagefiles_as_gallery']         = 'create gallery from image files';

$BL['be_link']                          = 'link';
$BL['be_links']                         = 'links';
$BL['be_redirect']                      = 'redirect';
$BL['be_redirects']                     = 'redirects';
$BL['be_views']                         = 'views';
$BL['be_structure_id']                  = 'structure ID';
$BL['be_shortcut']                      = 'shortcut';
$BL['be_target_type']                   = 'target type';
$BL['be_http_status']                   = 'HTTP status';
$BL['be_http_status301']                = 'moved permanently';
$BL['be_http_status307']                = 'temporary redirect';
$BL['be_http_status404']                = 'not found';
$BL['be_http_status401']                = 'unauthorized';
$BL['be_http_status503']                = 'service unavailable';
$BL['be_redirect_error1']               = 'Alias/Shortcut, structure or article ID is required';
$BL['be_redirect_error2']               = 'Target is required';
$BL['be_redirect_error3']               = 'For target type article ID and structure ID only integers are allowed as target';
$BL['be_new_linkredirect']              = 'Add link/redirect';

$BL['be_ctype_accordion']               = 'group (accordion)';
$BL['be_ctype_number']                  = 'number';
$BL['be_inactive']                      = 'inactive';
$BL['be_locked']                        = 'locked';
$BL['be_n/a']                           = 'n/a';
$BL['be_opengraph_support']             = 'Allow Social Sharing';
$BL['be_player_volume']                 = 'Volume';
$BL['be_player_volume_muted']           = 'muted';
$BL['be_keyword']                       = 'Keyword';
$BL['be_tag']                           = 'tag';

$BL['be_system_container']              = 'system container';
$BL['be_system_container_norender']     = 'no regular frontend rendering';
$BL['be_custom_scriptlogic']            = 'custom (script logic)';
$BL['be_flush_image_cache']             = 'flush image cache';

$BL['be_caption_alt']                   = 'alt attr.';
$BL['be_caption_title']                 = 'title attr.';
$BL['be_caption_file_imagesize']        = 'WxHxC <em>(if image)</em>';
$BL['be_caption_file_title']            = 'file title';
$BL['be_caption_descr.']                = 'descr.';
$BL['be_display_html5_only']            = 'HTML5 only';
$BL['be_audio_only']                    = 'audio only';
$BL['be_hide_downloadbutton']           = 'hide HTML5 download button';

$BL['be_filter']                        = 'filter';
$BL['be_filter_with_tags']              = 'by tag';
$BL['be_filter_not_selected']           = 'no category selected';
$BL['be_empty_search_result']           = 'The search returned no results.';
$BL['confirm_cp_tab_warning']           = 'The subsection has no title and no number is assigned. The selection will get lost on save or update.';

$BL['be_canonical']                     = 'canonical link';
$BL['be_breadcrumb']                    = 'breadcrumb display behavior';
$BL['be_breadcrumb_nothidden']          = 'visible if level is hidden';
$BL['be_breadcrumb_nolink']             = 'do not link';

$BL['CSRF_POST_INVALID'] = 'No <a href="https://de.wikipedia.org/wiki/Cross-Site-Request-Forgery">CSRF</a> POST parameters found. For security reasons, the session was ended.';
$BL['CSRF_POST_FAILED'] = 'Validating <a href="https://de.wikipedia.org/wiki/Cross-Site-Request-Forgery">CSRF</a> POST parameters failed. For security reasons, the session was ended.';
$BL['CSRF_GET_INVALID'] = 'No <a href="https://de.wikipedia.org/wiki/Cross-Site-Request-Forgery">CSRF</a> GET parameters found. For security reasons, the session was ended.';
$BL['CSRF_GET_FAILED'] = 'Validating <a href="https://de.wikipedia.org/wiki/Cross-Site-Request-Forgery">CSRF</a> GET parameters failed. For security reasons, the session was ended.';

$BL['be_parental_alias'] = 'parental alias';
$BL['be_fsearch_nor'] = 'NONE';
$BL['be_tab_toggle'] = 'Toggle tab to expanded or closed';
$BL['be_custom_textfield'] = 'custom text';
$BL['be_tab_template_toggle_warning'] = 'Changing the template can have the effect that custom fields get changed too and existing values get lost.\n\nAre you really sure to continue?';

$BL['be_onepage_id'] = 'OnePage ID (#anchor) support';
$BL['be_onepage_template'] = 'treat as OnePage template';
$BL['be_yes'] = 'Yes';
$BL['be_no'] = 'No';
$BL['be_attr_title'] = 'title (attribute)';
$BL['be_attr_alt'] = 'alternative text';
$BL['be_ie8ignore'] = 'disable <a href="https://en.wikipedia.org/wiki/Conditional_comment" target="_blank" class="underline">conditional comments</a> for IE8';
$BL['be_cookie_consent_enable'] = 'enable Cookie Consent plugin';
$BL['be_cookie_consent_message'] = 'consent message';
$BL['be_cookie_consent_translatable'] = 'This installation has support for multiple languages (&#36;phpwcms[&#39;allowed_lang&#39;]) enabled. For translated cookie consent texts use the <b>@@Text@@</b> syntax and check `template/template_lang` after rendering.';
$BL['cookie_consent_message'] = 'This website uses cookies to ensure you get the best experience on our website';
$BL['be_cookie_consent_dismiss'] = 'dismiss button text';
$BL['cookie_consent_dismiss'] = 'Got it!';
$BL['be_cookie_consent_more'] = 'learn more button text';
$BL['cookie_consent_more'] = 'More info';
$BL['be_cookie_consent_link'] = 'cookie policy url/alias';
$BL['be_cookie_consent_theme'] = 'theme (empty = no CSS)';
$BL['be_google_analytics_enable'] = 'use Google Analytics';
$BL['be_google_tag_manager_enable'] = 'use Google Tag Manager';
$BL['be_piwik_enable'] = 'use Matomo/Piwik';
$BL['be_tracking_anonymize'] = 'anonymize the IP';
$BL['be_tracking_cookie_flags'] = 'enable <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/cookies-user-id#cookie_flags" target="_blank"><u>cookie flags</u> (generated automatically)</a>';
$BL['be_tracking_custom_properties'] = 'custom <a href="https://developers.google.com/analytics/devguides/collection/gtagjs/" target="_blank"><u>config parameters</u></a> (prop1: val1, prop2, val2)';
$BL['be_tracking_id'] = 'tracking ID';
$BL['be_site_id'] = 'site ID';
$BL['be_piwik_url'] = 'Matomo/Piwik URL';
$BL['be_filedownload_direct_blocked'] = 'blocked by <abbr title="%s">.htaccess</abbr>';
$BL['be_tracking_optout'] = 'support for Opt-Out cookie <i>&lt;a href=&quot;javascript:gaOptout()&quot;&gt;&lt;/a&gt;</i>';
$BL['be_require_consent'] = 'Deactivate tracking code widthout consent';
$BL['be_consent_cookie_name'] = 'Consent cookie name';
$BL['be_consent_cookie_value'] = 'Consent cookie value';
$BL['be_respect_donottrack'] = 'Respect the Do-Not-Track browser setting';
$BL['placeholder_require_cookie_name'] = 'cookieconsent_dismissed';
$BL['placeholder_require_cookie_value'] = 'yes';

$BL['be_iptc_data'] = 'IPTC data';
$BL['be_iptc_as_caption'] = 'use for caption, copyright etc. as long yet unset';
$BL['iptc_ImageDescription'] = 'image description';
$BL['iptc_Copyright'] = 'copyright';
$BL['iptc_Artist'] = 'artist';
$BL['iptc_Keywords'] = 'keywords';
$BL['iptc_CountryDest'] = 'country';
$BL['iptc_ProvinceOrStateDest'] = 'region';
$BL['iptc_CityDest'] = 'city';
$BL['iptc_SublocationDest'] = 'sublocation';
$BL['iptc_ObjectName'] = 'object name';
$BL['iptc_SpecialInstructions'] = 'special instructions';
$BL['iptc_Headline'] = 'headline';
$BL['iptc_Credit'] = 'credit';
$BL['iptc_Source'] = 'source';
$BL['iptc_EditStatus'] = 'edit status';
$BL['iptc_iimCategory'] = 'category';
$BL['iptc_iimSupplementalCategory'] = 'supplemental category';
$BL['iptc_Urgency'] = 'urgency';
$BL['iptc_FixtureIdentifier'] = 'fixture identifier';
$BL['iptc_LocationDestCode'] = 'location code';
$BL['iptc_LocationDest'] = 'location';
$BL['iptc_Software'] = 'software';
$BL['iptc_SoftwareVersion'] = 'software version';
$BL['iptc_ObjectCycle'] = 'object cycle';
$BL['iptc_CountryCodeDest'] = 'country code';
$BL['iptc_OriginalTransmissionRef'] = 'original transmission';
$BL['iptc_Contact'] = 'contact';
$BL['iptc_Writer'] = 'writer';
$BL['iptc_LanguageCode'] = 'language code';
$BL['iptc_DateTimeOriginal'] = 'date/time original';
$BL['iptc_DateTimeDigitized'] = 'date/time digitized';
$BL['iptc_DateTimeReleased'] = 'date/time released';
$BL['iptc_DateTimeExpires'] = 'date/time expires';
$BL['iptc_IntellectualGenre'] = 'intellectual genre';
$BL['iptc_SubjectNewsCode'] = 'subject news code';
$BL['iptc_iimVersion'] = 'version';

$BL['be_suppress_render_caption'] = 'suppress rendering of the caption';
$BL['be_cnt_attribute_class'] = 'CSS class';
$BL['be_cnt_attribute_id'] = 'CSS id';
$BL['be_cnt_avoid_duplicates'] = 'allow unique values only';
$BL['be_not_set'] = 'not set';
$BL['be_licensed_under_GPL'] = 'Licensed under GPL.';
$BL['be_extensions_copyright'] = 'Extensions are copyright of their respective owners.';

$BL['be_password_show'] = 'Show password';
$BL['be_password_hide'] = 'Hide password';

$BL['be_admin_template_choose_file'] = 'Text template, alternatively select file template';

$BL['be_flashplayer_marker'] = 'Marker';
$BL['be_marker_time'] = 'Time (seconds, i.e. 10.5)';
$BL['be_marker_text'] = 'Text';
$BL['be_marker_overlaytext'] = 'Overlay text';

$BL['copy_to_clipboard'] = 'Copy to Clipboard';
$BL['url_parameter'] = 'URL parameter';
$BL['file_extension'] = 'Extension';
$BL['download_link'] = 'Download link';
$BL['disposition_attachment'] = 'Attachment';
$BL['disposition_attachment_description'] = 'direct download';
$BL['disposition_inline'] = 'Inline';
$BL['disposition_inline_description'] = 'display in browser';
