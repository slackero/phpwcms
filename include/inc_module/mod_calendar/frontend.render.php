<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Module/Plug-in Calendar frontend_render script
// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to make global replacements

if(strpos($content['all'], '{CALENDAR:')) {

    require_once __DIR__ . '/inc/calendar.class.php';

    $cmsgo_calendar_module = new cmsgoCalendar();
    $cmsgo_calendar_module->parse($content['all']);

}
