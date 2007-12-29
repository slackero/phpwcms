<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Bulgarian language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Translated: Atanas Tchobanov, atanas@webdressy.com
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-04-10
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Отрежи'
  ),
  'copy' => array(
    'title' => 'Копирай'
  ),
  'paste' => array(
    'title' => 'Вмъкни'
  ),
  'undo' => array(
    'title' => 'Отмени'
  ),
  'redo' => array(
    'title' => 'Повтори'
  ),
  'hyperlink' => array(
    'title' => 'Линк'
  ),
  'image_insert' => array(
    'title' => 'Вмъкни картинка',
    'select' => 'Вмъкни',
    'cancel' => 'Отмени',
    'library' => 'Библиотека',
    'preview' => 'Преглед',
    'images' => 'Картинки',
    'upload' => 'Изпрати картинка',
    'upload_button' => 'Изпрати',
    'error' => 'Грешка',
    'error_no_image' => 'Изберете картинка',
    'error_uploading' => 'Грешка при изпращането. Пробвайте пак.',
    'error_wrong_type' => 'Неправилен тип картинка',
    'error_no_dir' => 'Библиотеката не съществува',
  ),
  'image_prop' => array(
    'title' => 'Параметри на картинката',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
    'source' => 'Източник',
    'alt' => 'Кратко описание',
    'align' => 'Подравняване',
    'justifyleft' => 'наляво (left)',
    'justifyright' => 'надясно (right)',
    'top' => 'горе (top)',
    'middle' => 'в центъра (middle)',
    'bottom' => 'долу (bottom)',
    'absmiddle' => 'абс. център (absmiddle)',
    'texttop' => 'отгоре (texttop)',
    'baseline' => 'отдолу (baseline)',
    'width' => 'Ширина',
    'height' => 'Височина',
    'border' => 'Рамка',
    'hspace' => 'Гор. разстояние',
    'vspace' => 'Верт. разстояние',
    'error' => 'Грешка',
    'error_width_nan' => 'Ширината трябва да е числена стойност',
    'error_height_nan' => 'Височината трябва да е числена стойност',
    'error_border_nan' => 'Рамката трябва да е числена стойност',
    'error_hspace_nan' => 'Хоризонталните полета трябва да са числена стойност',
    'error_vspace_nan' => 'Вертикалните полета трябва да са числена стойност',
  ),
  'inserthorizontalrule' => array(
    'title' => 'Хоризонтална линия'
  ),
  'table_create' => array(
    'title' => 'Създай таблица'
  ),
  'table_prop' => array(
    'title' => 'Параметри на таблицата',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
    'rows' => 'Редове',
    'columns' => 'Колони',
    'width' => 'Ширина',
    'height' => 'Височина',
    'border' => 'Рамка',
    'pixels' => 'пикс.',
    'cellpadding' => 'Отстъп от рамката',
    'cellspacing' => 'Разстояние между клетките',
    'bg_color' => 'Цвят на фона',
    'error' => 'Грешка',
    'error_rows_nan' => 'Редовете трябва да са числена стойност',
    'error_columns_nan' => 'Колоните трябва да са числена стойност',
    'error_width_nan' => 'Ширината трябва да е числена стойност',
    'error_height_nan' => 'Височината трябва да е числена стойност',
    'error_border_nan' => 'Рамката трябва да е числена стойност',
    'error_cellpadding_nan' => 'Отстъпът от рамката трябва да е числена стойност',
    'error_cellspacing_nan' => 'Разстоянието между клетките трябва да е числена стойност',
  ),
  'table_cell_prop' => array(
    'title' => 'Параметри на клетката',
    'horizontal_align' => 'Хоризонтално подравняване',
    'vertical_align' => 'Вертикално подравняване',
    'width' => 'Ширина',
    'height' => 'Височина',
    'css_class' => 'Стил',
    'no_wrap' => 'Без преноси',
    'bg_color' => 'Цвят на фона',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
    'justifyleft' => 'Наляво',
    'justifycenter' => 'В центъра',
    'justifyright' => 'Надясно',
    'top' => 'Отгоре',
    'middle' => 'В центъра',
    'bottom' => 'Отдолу',
    'baseline' => 'На базовата линия на текста',
    'error' => 'Грешка',
    'error_width_nan' => 'Ширината трябва да е числена стойност',
    'error_height_nan' => 'Височината трябва да е числена стойност',
    
  ),
  'table_row_insert' => array(
    'title' => 'Вмъкни ред'
  ),
  'table_column_insert' => array(
    'title' => 'Вмъкни колона'
  ),
  'table_row_delete' => array(
    'title' => 'Премахни ред'
  ),
  'table_column_delete' => array(
    'title' => 'Премахни колона'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Обедини надясно'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Обедини наляво'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Раздели хоризонтално'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Раздели вертикално'
  ),
  'style' => array(
    'title' => 'Стил'
  ),
  'fontname' => array(
    'title' => 'Шрифт'
  ),
  'fontsize' => array(
    'title' => 'Размер'
  ),
  'formatBlock' => array(
    'title' => 'параграф'
  ),
  'bold' => array(
    'title' => 'Получер'
  ),
  'italic' => array(
    'title' => 'Курсив'
  ),
  'underline' => array(
    'title' => 'Подчертан'
  ),
  'insertorderedlist' => array(
    'title' => 'Пронумерован списък'
  ),
  'insertunorderedlist' => array(
    'title' => 'Обикновен списък'
  ),
  'indent' => array(
    'title' => 'Увеличи отстъпа'
  ),
  'outdent' => array(
    'title' => 'Намали отстъпа'
  ),
  'justifyleft' => array(
    'title' => 'Подравняване наляво'
  ),
  'justifycenter' => array(
    'title' => 'Подравняване по центъра'
  ),
  'justifyright' => array(
    'title' => 'Подравняване надясно'
  ),
  'fore_color' => array(
    'title' => 'Цвят на текста'
  ),
  'bg_color' => array(
    'title' => 'Цвят на фона'
  ),
  'design' => array(
    'title' => 'Превключи в режим на макетиране (WYSIWYG)'
  ),
  'html' => array(
    'title' => 'Превключи в режим на редактиране на кода (HTML)'
  ),
  'colorpicker' => array(
    'title' => 'Избор на цвят',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
  ),
  'cleanup' => array(
    'title' => 'Очистване на HTML',
    'confirm' => 'Тази операция премахва всички стилове, шрифтове и ненужни тагове от съдържанието в редактора. Форматирането може да бъде загубено частично или изцяло.',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
  ),
  'toggle_borders' => array(
    'title' => 'Включи рамката',
  ),
  'hyperlink' => array(
    'title' => 'Линк',
    'url' => 'Адрес',
    'name' => 'Име',
    'target' => 'Цел',
    'title_attr' => 'Название',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
  ),
  'table_row_prop' => array(
    'title' => 'Параметри на реда',
    'horizontal_align' => 'Хоризонтално подравняване',
    'vertical_align' => 'Вертикално подравняване',
    'css_class' => 'Стил',
    'no_wrap' => 'Без преноси',
    'bg_color' => 'Цвят на фона',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
    'justifyleft' => 'Отляво',
    'justifycenter' => 'В центъра',
    'justifyright' => 'Отдясно',
    'top' => 'Отгоре',
    'middle' => 'В центъра',
    'bottom' => 'Отдолу',
    'baseline' => 'По базовата линия на текста',
  ),
  'symbols' => array(
    'title' => 'Спец. символи',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
  ),
  'templates' => array(
    'title' => 'Графични модели',
  ),
  'page_prop' => array(
    'title' => 'Параметри на страницата',
    'title_tag' => 'Заглавие',
    'charset' => 'Кодова таблица',
    'background' => 'Фонова картинка',
    'bgcolor' => 'Цвят на фона',
    'text' => 'Цвят на текста',
    'link' => 'Цвят на линка',
    'vlink' => 'Цвят на посетените линкове',
    'alink' => 'Цвят на активните линкове',
    'leftmargin' => 'Отстъп отляво',
    'topmargin' => 'Отстъп отгоре',
    'css_class' => 'Стил',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отмени',
  ),
  'preview' => array(
    'title' => 'Предварителен преглед',
  ),
  'image_popup' => array(
    'title' => 'Popup картинка',
  ),
  'zoom' => array(
    'title' => 'Увеличение',
  ),
);
?>

