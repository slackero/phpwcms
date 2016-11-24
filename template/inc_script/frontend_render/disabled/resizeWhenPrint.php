<?php

//put a little JS Snippet
//and resize the window

$_print_win_h = 400;
$_print_win_w = 500;

if($GLOBALS['aktion'][2] == 1) { //wenn Print Modus

   $GLOBALS['block']['custom_htmlhead']['resizeJS']  = '<script type="text/javascript">' . LF;
   $GLOBALS['block']['custom_htmlhead']['resizeJS'] .= SCRIPT_CDATA_START . LF;
   $GLOBALS['block']['custom_htmlhead']['resizeJS'] .=   'window.resizeTo('.$_print_win_w.','.$_print_win_h.');' . LF;
   $GLOBALS['block']['custom_htmlhead']['resizeJS'] .= SCRIPT_CDATA_END . LF;
   $GLOBALS['block']['custom_htmlhead']['resizeJS'] .= '</script>' . LF;

}
