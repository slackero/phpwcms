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


// Language: English, Country Code: en
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'المستخدمين المتصلين حالياً';

// Login Page
$BL["login_text"]                       = 'أدخل بيانات تسجيل الدخول';
$BL['login_error']                      = 'حدثت أخطاء أثناء تسجيل الدخول';
$BL["login_username"]                   = 'اسم المستخدم';
$BL["login_userpass"]                   = 'كلمة المرور';
$BL["login_button"]                     = 'تسجيل الدخول';
$BL["login_lang"]                       = 'خيارات اللغة';

// phpwcms.php
$BL['be_nav_logout']                    = 'تسجيل خروج';
$BL['be_nav_articles']                  = 'تحرير المقالات';
$BL['be_nav_files']                     = 'إدارة الملفات';
$BL['be_nav_modules']                   = 'الوحدات';
$BL['be_nav_messages']                  = 'إدارة رسائل الموقع الإعلامية';
$BL['be_nav_chat']                      = 'إدارة المحادثة';
$BL['be_nav_profile']                   = 'البيانات الشخصية';
$BL['be_nav_admin']                     = 'إدارة الموقع';
$BL['be_nav_discuss']                   = 'مناقشات';

$BL['be_page_title']                    = 'إدارة المحتويات (إدارة الموقع)';

$BL['be_subnav_article_center']         = 'مركز تحرير المقالات';
$BL['be_subnav_article_new']            = 'مقال جديد';
$BL['be_subnav_file_center']            = 'مركز الملفات';
$BL['be_subnav_file_ftptakeover']       = 'ftp نقل الملفات';
$BL['be_subnav_mod_artists']            = 'الفنان, الفئة, التصنيف';
$BL['be_subnav_msg_center']             = 'مركز رسائل التبليغ';
$BL['be_subnav_msg_new']                = 'رسالة تبليغ جديدة';
$BL['be_subnav_msg_newsletter']         = 'إشتراكات رسائل التبليغ';
$BL['be_subnav_chat_main']              = 'صفحة المحادثات الرئيسة';
$BL['be_subnav_chat_internal']          = 'محادثات فردية';
$BL['be_subnav_profile_login']          = 'معلومات تسجيل الدخول';
$BL['be_subnav_profile_personal']       = 'المعلومات الشخصية';
$BL['be_subnav_admin_pagelayout']       = 'تنسيق الصفحة';
$BL['be_subnav_admin_templates']        = 'نماذج';
$BL['be_subnav_admin_css']              = 'صفحة التنسيق الإفتراضية';
$BL['be_subnav_admin_sitestructure']    = 'بنية تركيب الموقع';
$BL['be_subnav_admin_users']            = 'إدارة المستخدمين';
$BL['be_subnav_admin_filecat']          = 'تصنيف الملفات';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'رقم تعريف المقال';
$BL['be_func_struct_preview']           = 'معاينة';
$BL['be_func_struct_edit']              = 'تحرير مقال';
$BL['be_func_struct_sedit']             = 'تحرير التركيب بنية الموقع';
$BL['be_func_struct_cut']               = 'قص مقال';
$BL['be_func_struct_nocut']             = 'تعطيل قص مقال';
$BL['be_func_struct_svisible']          = 'تحويل مرئي/مخفي';
$BL['be_func_struct_spublic']           = 'تحويل عام/خاص';
$BL['be_func_struct_sort_up']           = 'ترتيب تصاعدي';
$BL['be_func_struct_sort_down']         = 'ترتيب تنازلي';
$BL['be_func_struct_del_article']       = 'مسح مقال';
$BL['be_func_struct_del_jsmsg']         = 'هل ترغب حقاً  \nفي مسح المقال؟'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'إنشاء مقال جديد في بنية الموقع';
$BL['be_func_struct_paste_article']     = 'لصق مقال في بنية الموقع ';
$BL['be_func_struct_insert_level']      = ' إدراج تركيب بنية الموقع في';
$BL['be_func_struct_paste_level']       = 'لصق بنية الموقع في';
$BL['be_func_struct_cut_level']         = 'قص بنية الموقع من';
$BL['be_func_struct_no_cut']            = "من المستحيل قص التركيب البنيوي الجذر";
$BL['be_func_struct_no_paste1']         = "من المستحيل القيام بعملية اللصق هنا";
$BL['be_func_struct_no_paste2']         = 'is child in root line of the tree level';
$BL['be_func_struct_no_paste3']         = 'يفترض القيام بعملية اللصق هنا';
$BL['be_func_struct_paste_cancel']      = 'إلغاء عملية تغيير بنية الموقع';
$BL['be_func_struct_del_struct']        = 'مسح بنية الموقع';
$BL['be_func_struct_del_sjsmsg']        = 'هل ترغب حقاً في \n مسح بنية الموقع؟'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'فتح';
$BL['be_func_struct_close']             = 'إغلاق';
$BL['be_func_struct_empty']             = 'فارغ';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'نصوص بسيطة';
$BL['be_ctype_html']                    = 'htmlترميز';
$BL['be_ctype_code']                    = 'رموز برمجية';
$BL['be_ctype_textimage']               = 'نصوص وصور';
$BL['be_ctype_images']                  = 'صور';
$BL['be_ctype_bulletlist']              = 'قائمة بنود';
$BL['be_ctype_link']                    = 'روابط وعناوين بريدية';
$BL['be_ctype_linklist']                = 'قائمة الروابط';
$BL['be_ctype_linkarticle']             = 'روابط المقالات';
$BL['be_ctype_multimedia']              = 'وسائط متعددة';
$BL['be_ctype_filelist']                = 'قائمة ملفات';
$BL['be_ctype_emailform']               = 'إستمارة بيانات بريد إلكتروني';
$BL['be_ctype_newsletter']              = 'رسالة إعلامية من إدارة الموقع';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'تم إنشاء بيانات المستخدم بنجاح';
$BL['be_profile_create_error']          = 'حدث خطاء أثناء الإنشاء';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'تم تحديث بيانات المستخدم بنجاح';
$BL['be_profile_update_error']          = 'حدث خطاء أثناء التحديث';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'اسم المستخدم {VAL} غير صحيح';
$BL['be_profile_account_err2']          = 'كلمة المرور قصيرة (فقط {VAL} حروف: إستخدم ٥ حروف كحد أدنى)';
$BL['be_profile_account_err3']          = 'كلمة المرور غير متطابقة مع كلمة المرور المكررة';
$BL['be_profile_account_err4']          = 'العنوان البريدي {VAL} غير صحيح';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'معلوماتك الشخصية';
$BL['be_profile_data_text']             = 'إن المعلومات الشخصية هنا  إختيارية. فمن خلالها تمكن المستخدمين وزوار الموقع من التعرف عليك بشكل افضل، ضع إهتماماتك وخبراتك. بناء على إختياراتك مع مربعات التأشير يتمكن الزوار من قراءة معلوماتك داخل صفحات الموقع (على كل حال يمكنك تعطيل ذلك(.';
$BL['be_profile_label_title']           = 'الاسم الحركي';
$BL['be_profile_label_firstname']       = 'الاسم الأول';
$BL['be_profile_label_name']            = 'اللقب';
$BL['be_profile_label_company']         = 'الشركة/المؤسسة';
$BL['be_profile_label_street']          = 'اسم الشارع';
$BL['be_profile_label_city']            = 'اسم المدينة';
$BL['be_profile_label_state']           = 'القطاع، المنطقة';
$BL['be_profile_label_zip']             = 'العنوان البريدي، الرمز البريدي';
$BL['be_profile_label_country']         = 'الدولة';
$BL['be_profile_label_phone']           = 'رقم الهاتف';
$BL['be_profile_label_fax']             = 'رقم الفاكس';
$BL['be_profile_label_cellphone']       = 'رقم الهاتف المحمول';
$BL['be_profile_label_signature']       = 'التوقيع';
$BL['be_profile_label_notes']           = 'ملاحظات';
$BL['be_profile_label_profession']      = 'المهنة';
$BL['be_profile_label_newsletter']      = 'إشترك بنشرات الموقع الإعلامية';
$BL['be_profile_text_newsletter']       = 'أرغب بالإنضمام للنشرات الإعلامية للموقع';
$BL['be_profile_label_public']          = 'إظهار البيانات';
$BL['be_profile_text_public']           = 'المعلومات المدرجة متاحة للمشاهدة من قبل الزوار';
$BL['be_profile_label_button']          = 'تحديث البيانات الشخصية';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'معلومات تسجيل الدخول';
$BL['be_profile_account_text']          = 'ليس من الضروري عادة تغيير اسم المستخدم<br />ولكن من الضروري تغيير كلمة المرور من فترة لأخرى لزيادة الأمان.';
$BL['be_profile_label_err']             = 'الرجاء تأكد من..';
$BL['be_profile_label_username']        = 'اسم المستخدم';
$BL['be_profile_label_newpass']         = 'كلمة المرور الجديدة';
$BL['be_profile_label_repeatpass']      = 'إعادة كتابة كلمة المرور المكررة';
$BL['be_profile_label_email']           = 'العنوان البريدي';
$BL['be_profile_account_button']        = 'تحديث بيانات تسجيل الدخول';
$BL['be_profile_label_lang']            = 'اللغة المستخدمة';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'ftp نقل الملفات من خلال نسق';
$BL['be_ftptakeover_mark']              = 'تحديد';
$BL['be_ftptakeover_available']         = 'الملفات المتوفرة';
$BL['be_ftptakeover_size']              = 'الحجم';
$BL['be_ftptakeover_nofile']            = 'ftpلا يوجد ملفات حالياً &#8211; ينبغي تحميل ملف واحد على الأقل من خلال';
$BL['be_ftptakeover_all']               = 'جميع المحتويات';
$BL['be_ftptakeover_directory']         = 'مجلد';
$BL['be_ftptakeover_rootdir']           = 'المجلد الجذر';
$BL['be_ftptakeover_needed']            = 'يجب تحديد عنصر واحد على الأقل';
$BL['be_ftptakeover_optional']          = 'إختياري';
$BL['be_ftptakeover_keywords']          = 'كلمات البحث المفتاحية';
$BL['be_ftptakeover_additional']        = 'إضافي';
$BL['be_ftptakeover_longinfo']          = 'المعلومات الشاملة';
$BL['be_ftptakeover_status']            = 'الحالة';
$BL['be_ftptakeover_active']            = 'نشط';
$BL['be_ftptakeover_public']            = 'عام للجمهور';
$BL['be_ftptakeover_createthumb']       = 'إنشاء صور مصغرة';
$BL['be_ftptakeover_button']            = 'تحميل جميع الملفات المحددة';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'مركز الملفات';
$BL['be_ftab_createnew']                = 'إنشاء مجلد جديد داخل مجلد الجذر';
$BL['be_ftab_paste']                    = 'لصق الملف الموجود في الحافظة داخل المجلد الجذر';
$BL['be_ftab_disablethumb']             = 'تعطيل الصور المصغرة من القائمة';
$BL['be_ftab_enablethumb']              = 'تمكين الصور المصغرة من القائمة';
$BL['be_ftab_private']                  = 'ملفات&nbsp; خاصة';
$BL['be_ftab_public']                   = 'ملفات&nbsp; عامة';
$BL['be_ftab_search']                   = 'بحث';
$BL['be_ftab_trash']                    = 'سلة&nbsp; المهملات';
$BL['be_ftab_open']                     = 'فتح جميع المجلدات';
$BL['be_ftab_close']                    = 'إغلاق جميع المجلدات المفتوحة';
$BL['be_ftab_upload']                   = 'تحميل الملف إلي المجلد الجذر';
$BL['be_ftab_filehelp']                 = 'فتح نافذة مساعدة الملفات';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'المجلد الجذر';
$BL['be_fpriv_title']                   = 'إنشاء مجلد جديد';
$BL['be_fpriv_inside']                  = 'بالداخل';
$BL['be_fpriv_error']                   = 'حدث خطاء: قم بكتابة اسم للمجلد';
$BL['be_fpriv_name']                    = 'الاسم';
$BL['be_fpriv_status']                  = 'الحالة';
$BL['be_fpriv_button']                  = 'إنشاء مجلد جديد';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'تحرير مجلد';
$BL['be_fpriv_newname']                 = 'اسم جديد';
$BL['be_fpriv_updatebutton']            = 'تحديث بيانات المجلد';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'حدد الملف الذي ترغب  برفعه للموقع';
$BL['be_fprivup_err2']                  = 'حجم الملف المراد رفعة للموقع يتجاوز';
$BL['be_fprivup_err3']                  = 'خطاء أثناء حفظ الملف في المخزن';
$BL['be_fprivup_err4']                  = 'خطاء أثناء إنشاء مجلد المستخدم.';
$BL['be_fprivup_err5']                  = 'لا توجد صور مصغرة';
$BL['be_fprivup_err6']                  = 'الرجاء عدم معاودة المحاولة-هذا الخطاء من قبل الخادم الرئيسي! إتصل بـ <a href="mailto:{VAL}">webmaster</a> في أسرع وقت ممكن';
$BL['be_fprivup_title']                 = 'رفع ملفات للموقع';
$BL['be_fprivup_button']                = 'رفع ملفات للموقع';
$BL['be_fprivup_upload']                = 'رفع للموقع';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'تحرير معلومات الملف';
$BL['be_fprivedit_filename']            = 'اسم الملف';
$BL['be_fprivedit_created']             = 'تاريخ الإنشاء';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'proof name of file (set back to original)';
$BL['be_fprivedit_clockwise']           = 'تدوير الصورة المصغرة حسب إتجاه عقارب الساعة [الملف الأصلي +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'تدوير الصورة المصغرة عكس إتجاه عقارب الساعة [الملف الأصلي -90&deg;]';
$BL['be_fprivedit_button']              = 'تحديث معلومات الملف';
$BL['be_fprivedit_size']                = 'الحجم';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'تحميل الملف للمجلد';
$BL['be_fprivfunc_makenew']             = 'إنشاء مجلد جديد داخل';
$BL['be_fprivfunc_paste']               = 'لصق ملف من الحافظة داخل المجلد';
$BL['be_fprivfunc_edit']                = 'تحرير مجلد';
$BL['be_fprivfunc_cactive']             = 'تحويل نشط/غير نشط';
$BL['be_fprivfunc_cpublic']             = 'تحويل عام/غير عام';
$BL['be_fprivfunc_deldir']              = 'مسح مجلد';
$BL['be_fprivfunc_jsdeldir']            = 'هل ترغب حقاً  \nبحذف المجلد';
$BL['be_fprivfunc_notempty']            = 'المجلد {VAL}غير فارغ!';
$BL['be_fprivfunc_opendir']             = 'فتح مجلد';
$BL['be_fprivfunc_closedir']            = 'إغلاق مجلد';
$BL['be_fprivfunc_dlfile']              = 'تحميل ملف';
$BL['be_fprivfunc_clipfile']            = 'ملف محفوظ في الحافظة';
$BL['be_fprivfunc_cutfile']             = 'قص';
$BL['be_fprivfunc_editfile']            = 'تحرير معلومات ملف';
$BL['be_fprivfunc_cactivefile']         = 'تحويل نشط/غير نشط';
$BL['be_fprivfunc_cpublicfile']         = 'تحويل عام/غير عام';
$BL['be_fprivfunc_movetrash']           = 'ضع التحديد في سلة المهملات';
$BL['be_fprivfunc_jsmovetrash1']        = 'هل ترغب فعلاً في وضع التحديد';
$BL['be_fprivfunc_jsmovetrash2']        = 'داخل مجلد سلة المهملات؟';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'لا يوجد ملفات أو مجلدات خاصة';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'المستخدم';
$BL['be_fpublic_nofiles']               = 'لا يوجد ملفات أو مجلدات عامة';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'سلة المهملات فارغة';
$BL['be_ftrash_show']                   = 'إظهار الملفات الخاصة';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'هل ترغب بإسترجاع {VAL} \nووضع مرة أخرى داخل القائمة الغير عامة';
$BL['be_ftrash_delete']                 = 'هل ترغب بحذف {VAL}?';
$BL['be_ftrash_undo']                   = 'إسترجاع (تراجع عن مسح)';
$BL['be_ftrash_delfinal']               = 'مسح نهائي';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'جملة البحث مفرغة';
$BL['be_fsearch_title']                 = 'بحث عن ملفات';
$BL['be_fsearch_infotext']              = 'محرك البحث يبحث في المعلومات الأساسية للملف، بناء على كلمات البحث المفتاحية،<br />كأسم الملف والمعلومات العامة. حيث لا يوجد دعم لإمكانيات بحث متخصصة لعدد متراكم من عناصر البحث حول حقل فارغ<br />. يمكنك الإختيار ما بين (و/أو) للملفات مع تعيين جميع الملفات أو تحديد الملفات الخاصة أو العامة';
$BL['be_fsearch_nonfound']              = 'لا يوجد ملفات تطابق متطلبات البحث،تحقق من معطياتك';
$BL['be_fsearch_fillin']                = 'الرجاء تعبئة الشريط الكتابي بمعطيات البحث';
$BL['be_fsearch_searchlabel']           = 'إبحث عن';
$BL['be_fsearch_startsearch']           = 'إبدأ البحث';
$BL['be_fsearch_and']                   = 'و';
$BL['be_fsearch_or']                    = 'أو';
$BL['be_fsearch_all']                   = 'جميع الملفات';
$BL['be_fsearch_personal']              = 'خاص';
$BL['be_fsearch_public']                = 'عام';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'محادثة داخلية';
$BL['be_chat_info']                     =' هنا تستطيع المحادثة عن كل ما تحتاجة مع مستخدمي مدير المحتوى في النطاق الإداري الخلفي. هذه الوسيلة للمحادثة بشكل مباشر بالوقت الحقيقي، كما تستطيع تعيين رسائل مقروءة للجميع. وفي حال رغبتك بمشاركة الأفكار مع الأخرين فقم بإستخدام مجمع المناقشات ';
$BL['be_chat_start']                    = 'إصغط هنا للبدء بالمحادثة';
$BL['be_chat_lines']                    = 'أسطر المحادثة';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'مركز الرسائل';
$BL['be_msg_new']                       = 'رسائل جديدة';
$BL['be_msg_old']                       = 'رسائل قديمة';
$BL['be_msg_senttop']                   = 'رسائل تم إرسالها';
$BL['be_msg_del']                       = 'رسائل محذوفة';
$BL['be_msg_from']                      = 'رسالة من قبل';
$BL['be_msg_subject']                   = 'العنوان';
$BL['be_msg_date']                      = 'التاريخ/الوقت';
$BL['be_msg_close']                     = 'أغلق الرسالة';
$BL['be_msg_create']                    = 'إنشاء رسالة جديدة';
$BL['be_msg_reply']                     = 'الرد على هذه الرسالة';
$BL['be_msg_move']                      = 'أنقل هذه الرسالة إلى سلة المهملات';
$BL['be_msg_unread']                    = 'رسائل جديدة أو غير مقروءة';
$BL['be_msg_lastread']                  = 'آخر {VAL} الرسائل المقروءة';
$BL['be_msg_lastsent']                  = 'آخر {VAL} الرسائل المرسلة';
$BL['be_msg_marked']                    = 'رسائل محددة للحذف (سلة المهملات)';
$BL['be_msg_nomsg']                     = 'لا يوجد رسائل في هذا المجلد';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'رداً على الرسالة';
$BL['be_msg_by']                        = 'المرسلة من قبل';
$BL['be_msg_on']                        = 'حول موضوع';
$BL['be_msg_msg']                       = 'الرسالة';
$BL['be_msg_err1']                      = 'لقد نسيت إدراج عنوان متلقي الرسالة..';
$BL['be_msg_err2']                      = 'fill in the subject field (the recipient can better handle your message)';
$BL['be_msg_err3']                      = 'محتوى الرسالة فارغ';
$BL['be_msg_sent']                      = 'تم إرسال الرسالة الجديدة';
$BL['be_msg_fwd']                       = 'you will be forwarded to the message center or';
$BL['be_msg_newmsgtitle']               = 'كتابة رسالة جديدة';
$BL['be_msg_err']                       = 'خطاء أثناء الإرسال';
$BL['be_msg_sendto']                    = 'إرسال إلى';
$BL['be_msg_available']                 = 'قائمة متلقي الرسالة';
$BL['be_msg_all']                       = 'ارسال الرسالة لجميع المتلقين المحددين';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'إشتراكات رسائل الموقع الإعلامية';
$BL['be_newsletter_titleedit']          = 'تحرير إشتراكات رسائل الموقع الإعلامية';
$BL['be_newsletter_new']                = 'إنشاء جديد';
$BL['be_newsletter_add']                = 'إضافة&nbsp; رسائل الموقع الإعلامية&nbsp; إشتراكات';
$BL['be_newsletter_name']               = 'الاسم';
$BL['be_newsletter_info']               = 'معلومات';
$BL['be_newsletter_button_save']        = 'حفظ الإشتراك';
$BL['be_newsletter_button_cancel']      = 'إلغاء';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'اسم المستخدم غير صحيح، إستخدم إسماً أخر.';
$BL['be_admin_usr_err2']                = 'اسم المستخدم فارغ (متطلب أساسي)';
$BL['be_admin_usr_err3']                = 'كلمة المرور فارغة (متطلب أساسي)';
$BL['be_admin_usr_err4']                = "العنوان البريدي غير صالح";
$BL['be_admin_usr_err']                 = 'خطاء';
$BL['be_admin_usr_mailsubject']         = 'مرحباِ بك في الباحة الخلفية من مدير المحتويات';
$BL['be_admin_usr_mailbody']            = "أهلاً وسهلاً بك مستخدماً لمدير المحتويات\n\n    اسم المستخدم: {LOGIN}\n    كلمة المرور: {PASSWORD}\n\n\nيمكنك التسجيل هنا : {LOGIN_PAGE}\n\nكإداري لمدير المحتويات\n ";
$BL['be_admin_usr_title']               = 'أضف حساب مستخدم جديد';
$BL['be_admin_usr_realname']            = 'الاسم الحقيقي';
$BL['be_admin_usr_setactive']           = 'تنشيط حساب المستخدم';
$BL['be_admin_usr_iflogin']             = 'عند التعيين يمكن لهذا المستخدم التسجيل للدخول';
$BL['be_admin_usr_isadmin']             = 'المستخدم مصنف كإداري';
$BL['be_admin_usr_ifadmin']             = 'عند التحديد يؤهل هذا المستخدم لحقوق الإداريين';
$BL['be_admin_usr_verify']              = 'المصادقة والوثوقية';
$BL['be_admin_usr_sendemail']           = 'أرسل بريد الكتروني للمستخدم الجديد مع معلومات عن الحساب';
$BL['be_admin_usr_button']              = 'ارسل معلومات المستخدم';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'تحديث حساب المستخدم';
$BL['be_admin_usr_emailsubject']        = 'تم تحديث معلومات الحساب';
$BL['be_admin_usr_emailbody']           = "تم تغيير معلومات المستخدم من قبل مدير المتحوى\n\n    اسم المستخدم: {LOGIN}\n    كلمة المرور: {PASSWORD}\n\n\nتستطيع تسجيل الدخول من هنا: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[لم يتم عمل تغيير - استخدم كلمة المرور السابقة]';
$BL['be_admin_usr_ebutton']             = 'تحديث معلومات المستخدمين';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'قائمة مستخدمي الموقع';
$BL['be_admin_usr_ldel']                = 'إنتباه!&#13هذا الأمر سيؤدي لمسح المستخدم';
$BL['be_admin_usr_create']              = 'إنشاء مستخدم جديد';
$BL['be_admin_usr_editusr']             = 'تحرير المستخدمين';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'بنية تركيب الموقع';
$BL['be_admin_struct_child']            = '(إبن)';
$BL['be_admin_struct_index']            = 'فهرس بداية الموقع';
$BL['be_admin_struct_cat']              = 'عناوين المصنفات';
$BL['be_admin_struct_hide1']            = 'إخفاء';
$BL['be_admin_struct_hide2']            = 'هذا&nbsp;التصنيف&nbsp;في&nbsp;القائمة';
$BL['be_admin_struct_info']             = 'ملخص معلومات المصنفات';
$BL['be_admin_struct_template']         = 'template';
$BL['be_admin_struct_alias']            = 'alias this category';
$BL['be_admin_struct_visible']          = 'مرئي';
$BL['be_admin_struct_button']           = 'ارسل معلومات المصنف';
$BL['be_admin_struct_close']            = 'إغلاق';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'تصنيف الملفات';
$BL['be_admin_fcat_err']                = 'اسماء التصنيف فارغة!';
$BL['be_admin_fcat_name']               = 'اسم النصنيف';
$BL['be_admin_fcat_needed']             = 'مطلوب';
$BL['be_admin_fcat_button1']            = 'تحديث';
$BL['be_admin_fcat_button2']            = 'إنشاء';
$BL['be_admin_fcat_delmsg']             = 'هل ترغب حقاً \nبحذف المفتاح الدليلي للملف؟';
$BL['be_admin_fcat_fcat']               = 'تصنيف الملفات';
$BL['be_admin_fcat_err1']               = 'المفتاح الدليلي للملف فارغ!';
$BL['be_admin_fcat_fkeyname']           = 'المفتاح الدليلي للملف';
$BL['be_admin_fcat_exit']               = 'إنهاء التحرير';
$BL['be_admin_fcat_addkey']             = 'إضافة كلمة حبث جديدة';
$BL['be_admin_fcat_editcat']            = 'تحرير اسماء المصنفات';
$BL['be_admin_fcat_delcatmsg']          = 'هل ترغب حقاً \n بحذف تصنيف للملف';
$BL['be_admin_fcat_delcat']             = 'حذف اسماء تصنبف الملفات';
$BL['be_admin_fcat_delkey']             = 'حذف اسم المفتاح الدليلي للملف';
$BL['be_admin_fcat_editkey']            = 'تحرير كلمة البحث';
$BL['be_admin_fcat_addcat']             = ' إنشاء اسماء تصنبف ملفات جديد';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'إعدادات واجهة زوار الموقع: تنسيق الصفحة';
$BL['be_admin_page_align']              = 'محاذاة الصفحة';
$BL['be_admin_page_align_left']         = 'محاذاة قياسية (يسار) لمجمل محتوى الصفحة';
$BL['be_admin_page_align_center']       = 'توسيط مجمل محتوى الصفحة';
$BL['be_admin_page_align_right']        = 'محاذاة يمين لمجمل محتوى الصفحة';
$BL['be_admin_page_margin']             = 'الهوامش';
$BL['be_admin_page_top']                = 'من الأعلى';
$BL['be_admin_page_bottom']             = 'من الأسفل';
$BL['be_admin_page_left']               = 'من اليسار';
$BL['be_admin_page_right']              = 'من اليمين';
$BL['be_admin_page_bg']                 = 'صورة الخلفية';
$BL['be_admin_page_color']              = 'اللون';
$BL['be_admin_page_height']             = 'الارتفاع';
$BL['be_admin_page_width']              = 'العرض';
$BL['be_admin_page_main']               = 'المساحة الرئيسة';
$BL['be_admin_page_leftspace']          = 'الفاصل الإيسر';
$BL['be_admin_page_rightspace']         = 'الفاصل الأيمن';
$BL['be_admin_page_class']              = 'class';
$BL['be_admin_page_image']              = 'صورة';
$BL['be_admin_page_text']               = 'نص';
$BL['be_admin_page_link']               = 'رابط';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'تم زيارته';
$BL['be_admin_page_pagetitle']          = 'الصفحة&nbsp;عنوان';
$BL['be_admin_page_addtotitle']         = 'إضافة&nbsp;إلى&nbsp;العنوان';
$BL['be_admin_page_category']           = 'التصنيف';
$BL['be_admin_page_articlename']        = 'المقال&nbsp;اسم';
$BL['be_admin_page_blocks']             = 'تنظيم المساحات المحجوزة';
$BL['be_admin_page_allblocks']          = 'كل المساحات المحجوزة';
$BL['be_admin_page_col1']               = 'تنسيق يعتمد عدد٣ أعمدة';
$BL['be_admin_page_col2']               = '(تنسيق يعتمد عدد عمودين (المحتوى الرئيس يميناً، عناصر التوجية يساراً';
$BL['be_admin_page_col3']               = '(تنسيق يعتمد عدد عمودين (المحتوى الرئيس يساراً، عناصر التوجية يميناً';
$BL['be_admin_page_col4']               = 'تنسيق يعتمد عدد عمود واحد';
$BL['be_admin_page_header']             = 'أعلى الصفحة';
$BL['be_admin_page_footer']             = 'أسفل الصفحة';
$BL['be_admin_page_topspace']           = 'الفاصل&nbsp; الأعلى';
$BL['be_admin_page_bottomspace']        = 'الفاصل&nbsp; الأدنى';
$BL['be_admin_page_button']             = 'حفظ تنسيق الصفحة';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               ='إعدادات واجهة زوار الموقع: css معلومات أوراق الأنماط المتعددة';
$BL['be_admin_css_css']                 = 'أوراق الأنماط';
$BL['be_admin_css_button']              = 'حفظ أوراق الأنماط';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'templates:إعدادات واجهة زوار الموقع';
$BL['be_admin_tmpl_default']            = 'الإفتراضي';
$BL['be_admin_tmpl_add']                = 'template&nbsp;إضافة';
$BL['be_admin_tmpl_edit']               = 'تحرير template';
$BL['be_admin_tmpl_new']                = 'إنشاء جديد';
$BL['be_admin_tmpl_css']                = 'ملف أوراق الأنماط';
$BL['be_admin_tmpl_head']               = 'html ترويسة رأس';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'الأخطاء';
$BL['be_admin_tmpl_button']             = 'حفظ النموذج';
$BL['be_admin_tmpl_name']               = 'الاسم';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'بنية الموقع وسرد قائمة المقالات';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'عنوان هذا المقال مفرغ';
$BL['be_article_err2']                  = 'خطاء بتاريخ البداية، حدد التاريخ الحالي';
$BL['be_article_err3']                  = 'خطاء بتاريخ النهاية، حدد التاريخ الحالي';
$BL['be_article_title1']                = 'معلومات المقالات الأساسية';
$BL['be_article_cat']                   = 'تصنيف المقال';
$BL['be_article_atitle']                = 'عنوان المقال';
$BL['be_article_asubtitle']             = 'العنوان الفرعي';
$BL['be_article_abegin']                = 'يبدأ';
$BL['be_article_aend']                  = 'ينتهي';
$BL['be_article_aredirect']             = 'بالعودة إلى';
$BL['be_article_akeywords']             = 'كلمات البحث المفتاحية';
$BL['be_article_asummary']              = 'الملخص';
$BL['be_article_abutton']               = 'إنشاء مقال جديد';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'خطاء بتاريخ النهاية، حدد التاريخ الحالي بالإضافة إلى أسبوع إضافي';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'تحرير المعلومات الأساسية للمقال';
$BL['be_article_eslastedit']            = 'أخر تحرير';
$BL['be_article_esnoupdate']            = 'الإستمارة غير محدثة';
$BL['be_article_esbutton']              = 'تحديث بيانات المقال';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'محتوى المقال';
$BL['be_article_cnt_type']              = 'نوع المحتوى';
$BL['be_article_cnt_space']             = 'فراغ';
$BL['be_article_cnt_before']            = 'قبل';
$BL['be_article_cnt_after']             = 'بعد';
$BL['be_article_cnt_top']               = 'أعلى';
$BL['be_article_cnt_ctitle']            = 'عنوان المحتوى';
$BL['be_article_cnt_back']              = 'معلومات المقال الشاملة';
$BL['be_article_cnt_button1']           = 'تحديث المحتوى';
$BL['be_article_cnt_button2']           = 'إنشاء المحتوي';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'معلومات المقال';
$BL['be_article_cnt_ledit']             = 'تحرير المقال';
$BL['be_article_cnt_lvisible']          = 'تحويل مرئي/مخفي';
$BL['be_article_cnt_ldel']              = 'مسح هذا المقال';
$BL['be_article_cnt_ldeljs']            = 'مسح المقال؟';
$BL['be_article_cnt_redirect']          = 'إعادة التوجية';
$BL['be_article_cnt_edited']            = 'حُرر من قبل';
$BL['be_article_cnt_start']             = 'تاريخ البداية';
$BL['be_article_cnt_end']               = 'تاريخ الإنتهاء';
$BL['be_article_cnt_add']               = 'إضافة محتوي جزئي';
$BL['be_article_cnt_up']                = 'نقل المحتوي للأعلى';
$BL['be_article_cnt_down']              = 'نقل المحتوي للأسفل';
$BL['be_article_cnt_edit']              = 'تحرير المحتوي الجزئي';
$BL['be_article_cnt_delpart']           = 'تحرير هذا المحتوي الجزئي';
$BL['be_article_cnt_delpartjs']         = 'مسح المحتوي الجزئي';
$BL['be_article_cnt_center']            = 'مركز المقالات';

// content forms
$BL['be_cnt_plaintext']                 = 'نص بسيط';
$BL['be_cnt_htmltext']                  = 'html نص لغة';
$BL['be_cnt_image']                     = 'صورة';
$BL['be_cnt_position']                  = 'موقع';
$BL['be_cnt_pos0']                      = 'أعلى ، يسار';
$BL['be_cnt_pos1']                      = 'أعلى ، وسط';
$BL['be_cnt_pos2']                      = 'أعلى ، يمين';
$BL['be_cnt_pos3']                      = 'أسفل ، يسار';
$BL['be_cnt_pos4']                      = 'أسفل ، وسط';
$BL['be_cnt_pos5']                      = 'أسفل ، يمين';
$BL['be_cnt_pos6']                      = 'داخل النص ، يسار';
$BL['be_cnt_pos7']                      = 'داخل النص ، يمين';
$BL['be_cnt_pos0i']                     = 'محاذاة الصورة أعلى يسار مساحات النص المحجوزة';
$BL['be_cnt_pos1i']                     = 'محاذاة الصورة أعلى وسط مساحات النص المحجوزة';
$BL['be_cnt_pos2i']                     = 'محاذاة الصورة أعلى يمين مساحات النص المحجوزة';
$BL['be_cnt_pos3i']                     = 'محاذاة الصورة أدنى يسار مساحات النص المحجوزة';
$BL['be_cnt_pos4i']                     = 'محاذاة الصورة أدنى وسط مساحات النص المحجوزة';
$BL['be_cnt_pos5i']                     = 'محاذاة الصورة أدنى يمين مساحات النص المحجوزة';
$BL['be_cnt_pos6i']                     = 'محاذاة الصورة وسط مساحات النص المحجوزة يساراً';
$BL['be_cnt_pos7i']                     = 'محاذاة الصورة وطسط مساحات النص المحجوزة يميناً';
$BL['be_cnt_maxw']                      = 'أقصى.&nbsp;عرض ممكن';
$BL['be_cnt_maxh']                      = 'أقصى.&nbsp;إرتفاع ممكن';
$BL['be_cnt_enlarge']                   = 'أنقر&nbsp;للتكبير';
$BL['be_cnt_caption']                   = 'التعليق';
$BL['be_cnt_subject']                   = 'العنوان';
$BL['be_cnt_recipient']                 = 'المتلقي';
$BL['be_cnt_buttontext']                = 'إزرار النصوص';
$BL['be_cnt_sendas']                    = 'إرسال بإسم..';
$BL['be_cnt_text']                      = 'نصوص';
$BL['be_cnt_html']                      = 'htmlلغة ';
$BL['be_cnt_formfields']                = 'حقول إستمارة الإستبيان';
$BL['be_cnt_code']                      = 'رموز برميجة';
$BL['be_cnt_infotext']                  = 'معلومات&nbsp;النص';
$BL['be_cnt_subscription']              = 'إشتراك';
$BL['be_cnt_labelemail']                = 'label&nbsp;email';
$BL['be_cnt_tablealign']                = 'table&nbsp;align';
$BL['be_cnt_labelname']                 = 'label&nbsp;name';
$BL['be_cnt_labelsubsc']                = 'label&nbsp;subscr.';
$BL['be_cnt_allsubsc']                  = 'جميع&nbsp;المشتركين.';
$BL['be_cnt_default']                   = 'إفتراضي';
$BL['be_cnt_left']                      = 'يسار';
$BL['be_cnt_center']                    = 'وسط';
$BL['be_cnt_right']                     = 'يمين';
$BL['be_cnt_buttontext']                = 'إزرار&nbsp;النص';
$BL['be_cnt_successtext']               = 'ناجح&nbsp;نص';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'change.email';
$BL['be_cnt_openimagebrowser']          = 'فتح متصفح الصور';
$BL['be_cnt_openfilebrowser']           = 'فتح متصفح الملفات';
$BL['be_cnt_sortup']                    = 'نقل للأعلى';
$BL['be_cnt_sortdown']                  = 'نقل للأدنى';
$BL['be_cnt_delimage']                  = 'إزالة الصورة المحددة';
$BL['be_cnt_delfile']                   = 'إزالة الملف المحدد';
$BL['be_cnt_delmedia']                  = 'إزالة الوسائط المتعددة المحددة';
$BL['be_cnt_column']                    = 'عمود';
$BL['be_cnt_imagespace']                = 'صورة&nbsp;مسافة';
$BL['be_cnt_directlink']                = 'رابط مباشر';
$BL['be_cnt_target']                    = 'الهدف';
$BL['be_cnt_target1']                   = 'نافذة جديدة';
$BL['be_cnt_target2']                   = 'داخل الإطار الأب بالنافذة';
$BL['be_cnt_target3']                   = 'نفس النافذة، بدون إطارات';
$BL['be_cnt_target4']                   = 'داخل نفس الإطار أو النافذة';
$BL['be_cnt_bullet']                    = 'قائمة بنود';
$BL['be_cnt_linklist']                  = 'قائمة روابط';
$BL['be_cnt_plainhtml']                 = 'مجرد html';
$BL['be_cnt_files']                     = 'ملفات';
$BL['be_cnt_description']               = 'الوصف';
$BL['be_cnt_linkarticle']               = 'روابط مقالات';
$BL['be_cnt_articles']                  = 'مقالات';
$BL['be_cnt_movearticleto']             = 'أنقل المقال المحدد لقائمة روابط المقالات';
$BL['be_cnt_removearticleto']           = 'إزالة المقال المحدد من قائمة روابط المقالات';
$BL['be_cnt_mediatype']                 = 'نوع الوسائط المتعددة';
$BL['be_cnt_control']                   = 'عناصر التحكم';
$BL['be_cnt_showcontrol']               = 'إظهار شريط التحكم';
$BL['be_cnt_autoplay']                  = 'تشغيل آلي';
$BL['be_cnt_source']                    = 'مصدر';
$BL['be_cnt_internal']                  = 'داخلي';
$BL['be_cnt_openmediabrowser']          = 'فتح مربع حوار محتويات الوسائط المتعددة';
$BL['be_cnt_external']                  = 'مصدر خارجي';
$BL['be_cnt_mediapos0']                 = 'يسار (إفتراضي)';
$BL['be_cnt_mediapos1']                 = 'وسط';
$BL['be_cnt_mediapos2']                 = 'يمين';
$BL['be_cnt_mediapos3']                 = 'مساحات محجوزة، يساراً';
$BL['be_cnt_mediapos4']                 = 'مساحات محجوزة، يميناً';
$BL['be_cnt_mediapos0i']                = 'محاذاة الوسائط أعلى يسار مساحات النص المحجوزة';
$BL['be_cnt_mediapos1i']                = 'محاذاة الوسائط أعلى وسط مساحات النص المحجوزة';
$BL['be_cnt_mediapos2i']                = 'محاذاة الوسائط أعلى يمين مساحات النص المحجوزة';
$BL['be_cnt_mediapos3i']                = 'محاذاة الوسائط يسار مساحات النص المحجوزة';
$BL['be_cnt_mediapos4i']                = 'محاذاة الوسائط يمين مساحات النص المحجوزة';
$BL['be_cnt_setsize']                   = 'حدد المقاس';
$BL['be_cnt_set1']                      = 'حدد الوسائط المتعددة بحجم إلى ١٢٠نقطة*١٦٠نقطة';
$BL['be_cnt_set2']                      = 'حدد الوسائط المتعددة بحجم إلى ١٨٠نقطة*٢٤٠نقطة';
$BL['be_cnt_set3']                      = 'حدد الوسائط المتعددة بحجم إلى ٢٤٠نقطة*٣٢٠نقطة';
$BL['be_cnt_set4']                      = 'حدد الوسائط المتعددة بحجم إلى ٣٦٠نقطة*٤٨٠نقطة';
$BL['be_cnt_set5']                      = ' مسح تحديد الطول والعرض للوسائط المتعددة';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'إنشاء تنسيق صفحة جديد';
$BL['be_admin_page_name']               = 'اسم تنسيق الصفحة';
$BL['be_admin_page_edit']               = 'تحرير تنسيق الصفحة';
$BL['be_admin_page_render']             = 'إظهار التنسيق';
$BL['be_admin_page_table']              = 'جدول';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'مخصص';
$BL['be_admin_page_custominfo']         = 'from template main block';
$BL['be_admin_tmpl_layout']             = 'التنسيق';
$BL['be_admin_tmpl_nolayout']           = 'لا يوجد تنسيق صفحة';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'بحث';
$BL['be_cnt_results']                   = 'نتائج';
$BL['be_cnt_results_per_page']          = 'لكل&nbsp;صفحة(في حال الفراغ أظهر الكل)';
$BL['be_cnt_opennewwin']                = 'فتح نافذة جديدة';
$BL['be_cnt_searchlabeltext']           = 'these are predefined texts and values for the search form and search result page and texts are shown when more than the given count of results per page should be shown.';
$BL['be_cnt_input']                     = 'الدخل';
$BL['be_cnt_style']                     = 'النمط';
$BL['be_cnt_result']                    = 'النتيجة';
$BL['be_cnt_next']                      = 'التالي';
$BL['be_cnt_previous']                  = 'السابق';
$BL['be_cnt_align']                     = 'محاذاة';
$BL['be_cnt_searchformtext']            = 'النصوص التالية تظهر عند عدم توفر نتائج البحث';
$BL['be_cnt_intro']                     = 'المقدمة';
$BL['be_cnt_noresult']                  = 'لايوجد نتائج';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'تعطيل';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'مؤلف المقال';
$BL['be_article_adminuser']             = 'المستخدم الإداري';
$BL['be_article_username']              = 'المحرر';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTMLترميز لغة ';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'مرئي للزوار الذين قاموا بتسجيل الدخول للموقع فقط';
$BL['be_admin_struct_status']           = 'حالة قائمة إعدادات واجهة زوار الموقع';

