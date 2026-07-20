<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if( ! ( strpos($content["all"],'{MY_LEVEL_NAV}')===false ) ) {

    $complexNav = '';

    // equal template_defaults for all NAV_ROW
    // don't forget to use $GLOBALS when using this in function
    // like: $GLOBALS['template_default']["nav_row"]["after"]
    // and : $GLOBALS['LEVEL_ID']
    $template_default["nav_row"]["after"]               = '</ul>';
    $template_default["nav_row"]["between"]             = "\n";
    $template_default["nav_row"]["link_before"]         = '<li class="inactive">';
    $template_default["nav_row"]["link_after"]          = '</li>';
    $template_default["nav_row"]["link_before_active"]  = '<li class="active">';
    $template_default["nav_row"]["link_after_active"]   = '</li>';
    $template_default["nav_row"]["link_direct_before"]          = '';
    $template_default["nav_row"]["link_direct_after"]           = '';
    $template_default["nav_row"]["link_direct_before_active"]   = '';
    $template_default["nav_row"]["link_direct_after_active"]    = '';

    foreach($LEVEL_ID as $depth => $thisStructureID) {

        switch($depth) {

            case 0: // Top Level
                    $template_default["nav_row"]["before"]              = '<ul class="levelClass0">';
                    $complexNav .= nav_level_row($thisStructureID,0);

                    break;

            case 1: // 1st Level
                    $template_default["nav_row"]["before"]              = '<ul class="levelClass1">';
                    $complexNav .= nav_level_row($thisStructureID,0);
                    break;

            case 2: // 2nd Level
                    $template_default["nav_row"]["before"]              = '<ul class="levelClass2">';
                    $complexNav .= nav_level_row($thisStructureID,0);
                    break;

            case 3: // 3rd Level
                    $template_default["nav_row"]["before"]              = '<ul class="levelClass3">';
                    $complexNav .= nav_level_row($thisStructureID,0);
                    break;

            case 4: // 4th Level
                    $template_default["nav_row"]["before"]              = '<ul class="levelClass4">';
                    $complexNav .= nav_level_row($thisStructureID,0);
                    break;

        }
    }

    $content["all"] = str_replace('{MY_LEVEL_NAV}', $complexNav, $content["all"]);

}
