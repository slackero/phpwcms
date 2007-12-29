<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Arabic language file
// Traslated: Mohammed Ahmed
// Gaza, Palestine
// http://www.maaking.com
// Email/MSN: m@maaking.com
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'قص'
  ),
  'copy' => array(
    'title' => 'نسخ'
  ),
  'paste' => array(
    'title' => 'لصق'
  ),
  'undo' => array(
    'title' => 'تراجع'
  ),
  'redo' => array(
    'title' => 'إعادة التراجع'
  ),
  'image_insert' => array(
    'title' => 'إدراج صورة',
    'select' => 'إختر',
	'delete' => 'حذف', // new 1.0.5
    'cancel' => 'إلغاء الأمر',
    'library' => 'المكتبة',
    'preview' => 'معاينة',
    'images' => 'الصور',
    'upload' => 'اختر صورة للتحميل',
    'upload_button' => 'إرفع الصورة',
    'error' => 'خطأ',
    'error_no_image' => 'من فضلك إختر صورة',
    'error_uploading' => 'Aحدث خطأ أثناء معالجة الملف، الرجاء المحاولة فيما بعد.',
    'error_wrong_type' => 'نوع ملف الصورة خاطء.',
    'error_no_dir' => 'مجلد مكتبات الصور غير موجود؟',
	'error_cant_delete' => 'خطأ: فشلت عملية الحذف', // new 1.0.5
  ),
  'image_prop' => array(
    'title' => 'خصائص الصورة',
    'ok' => '   موافق   ',
    'cancel' => 'إلغاء',
    'source' => 'المصدر',
    'alt' => 'نص بديل',
    'align' => 'محاذاه',
    'justifyleft' => 'يسار',
    'justifyright' => 'يمين',
    'top' => 'أعلى',
    'middle' => 'وسط',
    'bottom' => 'أسفل',
    'absmiddle' => 'وسط السطر',
    'texttop' => 'النص بالأعلى',
    'baseline' => 'مع الخط',
    'width' => 'العرض',
    'height' => 'الطول',
    'border' => 'سمك الحدود',
    'hspace' => 'الفراغ عموديا',
    'vspace' => 'الفراغ أفقيا',
    'error' => 'خطأ',
    'error_width_nan' => 'العرض ليس برقم',
    'error_height_nan' => 'الطول ليس برقم',
    'error_border_nan' => 'سمك الحدود ليس برقم',
    'error_hspace_nan' => 'حقل الفراغ الأفقي ليس رقم',
    'error_vspace_nan' => 'حقل الفراغ العمودي ليس رقم',
  ),
  'inserthorizontalrule' => array(
    'title' => 'خط أفقي'
  ),
  'table_create' => array(
    'title' => 'إنشاء جدول'
  ),
  'table_prop' => array(
    'title' => 'خصائص الجدول',
    'ok' => '   موافق   ',
    'cancel' => 'إلغاء',
    'rows' => 'صفوف',
    'columns' => 'أعمدة',
    'css_class' => 'CSS دالة', // <=== new 1.0.6
    'width' => 'العرض',
    'height' => 'الطول',
    'border' => 'الحد',
    'pixels' => 'بيكسل',
    'cellpadding' => 'نطاق الخلية',
    'cellspacing' => 'المسافة بين الخلايا',
    'bg_color' => 'لون الخلفية',
    'background' => 'صورة الخلفية', // <=== new 1.0.6
    'error' => 'حطأ',
    'error_rows_nan' => 'الصف ليس برقم',
    'error_columns_nan' => 'العمود ليس برقم',
    'error_width_nan' => 'العرض ليس برقم',
    'error_height_nan' => 'الطول ليس برقم',
    'error_border_nan' => 'الحد ليس برقم',
    'error_cellpadding_nan' => 'نطاق الخلية ليس برقم',
    'error_cellspacing_nan' => 'المسافة بين الخلاايا ليس برقم',
  ),
  'table_cell_prop' => array(
    'title' => 'خصائص الخلية',
    'horizontal_align' => 'محاذاه عمودية',
    'vertical_align' => 'محاذاه أفقية',
    'width' => 'العرض',
    'height' => 'الطول',
    'css_class' => 'CSS دالة',
    'no_wrap' => 'بلا التفاف',
    'bg_color' => 'لون الخلفية',
    'background' => 'صورة الخلفية', // <=== new 1.0.6
    'ok' => '   موافق   ',
    'cancel' => 'إلغاء',
    'justifyleft' => 'يسار',
    'justifycenter' => 'وسط',
    'justifyright' => 'يمين',
    'top' => 'أعلى',
    'middle' => 'وسط',
    'bottom' => 'أسفل',
    'baseline' => 'خط أساسي',
    'error' => 'خطأ',
    'error_width_nan' => 'العرض ليس برقم',
    'error_height_nan' => 'الطول ليس برقم',
  ),
  'table_row_insert' => array(
    'title' => 'إدراج صف'
  ),
  'table_column_insert' => array(
    'title' => 'إدراج عمود'
  ),
  'table_row_delete' => array(
    'title' => 'حذف صف'
  ),
  'table_column_delete' => array(
    'title' => 'حذف عمود'
  ),
  'table_cell_merge_right' => array(
    'title' => 'دمج يمين'
  ),
  'table_cell_merge_down' => array(
    'title' => 'دمج يسار'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'تقسيم الخلاايا عنودي'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'تقسيم الخلاايا أفقي'
  ),
  'style' => array(
    'title' => 'التنسيق'
  ),
  'fontname' => array(
    'title' => 'الخط'
  ),
  'fontsize' => array(
    'title' => 'الحجم'
  ),
  'formatBlock' => array(
    'title' => 'الفقرة'
  ),
  'bold' => array(
    'title' => 'أسود عريض'
  ),
  'italic' => array(
    'title' => 'مائل'
  ),
  'underline' => array(
    'title' => 'تحته خط'
  ),
  'insertorderedlist' => array(
    'title' => 'تعداد رقمي'
  ),
  'insertunorderedlist' => array(
    'title' => 'تعداد نقطي'
  ),
  'indent' => array(
    'title' => 'زيادة المسافة البادئة'
  ),
  'outdent' => array(
    'title' => 'إنقاص المسافة الزائدة'
  ),
  'justifyleft' => array(
    'title' => 'يسار'
  ),
  'justifycenter' => array(
    'title' => 'وسط'
  ),
  'justifyright' => array(
    'title' => 'يمين'
  ),
  'fore_color' => array(
    'title' => 'لون النص'
  ),
  'bg_color' => array(
    'title' => 'لون الخلفية'
  ),
  'design' => array(
    'title' => 'عرض التصميم'
  ),
  'html' => array(
    'title' => 'عرض كود html'
  ),
  'colorpicker' => array(
    'title' => 'إنتقاء اللون',
    'ok' => '   موافق   ',
    'cancel' => 'إلغاء الأمر',
  ),
  'cleanup' => array(
    'title' => 'مسح كافة التنسيقات',
    'confirm' => 'سيتم مسح كافة التنسيقات و الأكواد التي لا تلزم، وبعضها قد يبقى.',
    'ok' => '   موافق   ',
    'cancel' => 'إلغاء',
  ),
  'toggle_borders' => array(
    'title' => 'حدود السياق',
  ),
  'hyperlink' => array(
    'title' => 'رابط تشعبي',
    'url' => 'عنوان ال URL',
    'name' => 'الاسم',
    'target' => 'الإطار الهدف',
    'title_attr' => 'العنوان',
	'a_type' => 'النوع', // <=== new 1.0.6
	'type_link' => 'رابط', // <=== new 1.0.6
	'type_anchor' => 'معلمة', // <=== new 1.0.6
	'type_link2anchor' => 'ربط بمعلمة', // <=== new 1.0.6
	'anchors' => 'المعلمات', // <=== new 1.0.6
    'ok' => '   موافق   ',
    'cancel' => 'الغاء',
  ),
  'hyperlink_targets' => array( // <=== new 1.0.5
  	'_self' => 'نفس الاطار (_self)',
	'_blank' => 'صفحة جديدة (_blank)',
	'_top' => 'أعلى (_top)',
	'_parent' => 'إطار قرين (_parent)'
  ),
  'table_row_prop' => array(
    'title' => 'خصائص الصف',
    'horizontal_align' => 'محاذاة عمودية',
    'vertical_align' => 'محاذاه أفقية',
    'css_class' => 'CSS دالة',
    'no_wrap' => 'بلا التفاف',
    'bg_color' => 'لون الخلفية',
    'ok' => '   موافق   ',
    'cancel' => 'الغاء',
    'justifyleft' => 'يسار',
    'justifycenter' => 'توسيط',
    'justifyright' => 'يمين',
    'top' => 'أعلى',
    'middle' => 'وسط',
    'bottom' => 'أسفل',
    'baseline' => 'خط أساسي',
  ),
  'symbols' => array(
    'title' => 'رموز خاصة',
    'ok' => '   موافق   ',
    'cancel' => 'الغاء',
  ),
  'templates' => array(
    'title' => 'أشكال جاهزة Templates',
  ),
  'page_prop' => array(
    'title' => 'خصائص الصفحة',
    'title_tag' => 'العنوان',
    'charset' => 'ترميز',
    'background' => 'صورة الخلفية',
    'bgcolor' => 'لون الخلفية',
    'text' => 'لون النص',
    'link' => 'لون الرابط',
    'vlink' => 'لون الرابط الذي تم زيارته',
    'alink' => 'لون الرابط الفعال',
    'leftmargin' => 'الحد الأيسر',
    'topmargin' => 'الحد العلوي',
    'css_class' => 'CSS دالة',
    'ok' => '   موافق   ',
    'cancel' => 'إلغاء',
  ),
  'preview' => array(
    'title' => 'معاينة',
  ),
  'image_popup' => array(
    'title' => 'إدراج صورة وجعل رابط لها تظهر في نافذة عند الضغط عليها أو على الرابط',
  ),
  'zoom' => array(
    'title' => 'تكبير/تصغير',
  ),
  'subscript' => array( // <=== new 1.0.7
    'title' => 'رفع النص',
  ),
  'superscript' => array( // <=== new 1.0.7
    'title' => 'رفع النص2',
  ),
);
?>
