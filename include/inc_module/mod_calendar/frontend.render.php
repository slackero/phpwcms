<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Module/Plug-in Calendar frontend_render script
// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to make global replacements

if(strpos($content['all'], '{CALENDAR:')) {

    require_once __DIR__ . '/inc/calendar.class.php';

    $phpwcms_calendar_module = new phpwcmsCalendar();
    $phpwcms_calendar_module->parse($content['all']);

}
