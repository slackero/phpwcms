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

// This is a menu generator helping to create correct array values
// for the excellent JavaScript menu at http://deluxe-menu.com


if(strpos($content["all"],'{DELUXE_MENU') !== false) {


    // now lets define some default array values
    $DeluxeMenuParam = array(); // <---- do not edit this

    /*
    var menuItems = [
            [text, link, iconNormal, iconOver, tip, target, itemStyleInd, submenuStyleInd, jsFilename],
            [text, link, iconNormal, iconOver, tip, target, itemStyleInd, submenuStyleInd, jsFilename],
            ...
    ];
    */

    // base path - recommend phpwcms_template/inc_js/deluxe-menu
    $DeluxeMenuParam['base_path'] = TEMPLATE_PATH.'inc_js/deluxe-menu/';

    // create new array for each level - 0 = root, > 0 is sub menu
    $DeluxeMenuParam['icon'][0] = array('iconNormal' => '', 'iconOver' => '');
    $DeluxeMenuParam['icon'][1] = array('iconNormal' => '', 'iconOver' => '');
    $DeluxeMenuParam['icon'][2] = array('iconNormal' => '', 'iconOver' => '');
    $DeluxeMenuParam['icon'][3] = array('iconNormal' => '', 'iconOver' => '');
    $DeluxeMenuParam['icon'][4] = array('iconNormal' => '', 'iconOver' => '');


    // change general values for menu here (Hint: do not use any ' here or escape it)
    $DeluxeMenuParam['js'] = '
    //--- Common
    var isHorizontal=1;
    var smColumns=1;
    var smOrientation=0;
    var smViewType=0;
    var dmRTL=0;
    var pressedItem=-2;
    var itemCursor="pointer";
    var itemTarget="_self";
    var statusString="link";
    var blankImage="img/leer.gif";

    //--- Dimensions
    var menuWidth="400px";
    var menuHeight="";
    var smWidth="";
    var smHeight="";

    //--- Positioning
    var absolutePos=0;
    var posX="0";
    var posY="0";
    var topDX=0;
    var topDY=0;
    var DX=-2;
    var DY=0;

    //--- Font
    var fontStyle="normal 11px Tahoma, Arial";
    var fontColor=["#000000","#FFFFFF"];
    var fontDecoration=["none","none"];
    var fontColorDisabled="#AAAAAA";

    //--- Appearance
    var menuBackColor="#FCEEB0";
    var menuBackImage="";
    var menuBackRepeat="repeat";
    var menuBorderColor="#C0AF62";
    var menuBorderWidth=1;
    var menuBorderStyle="solid";

    //--- Item Appearance
    var itemBackColor=["#FCEEB0","#65BDDC"];
    var itemBackImage=["",""];
    var itemBorderWidth=1;
    var itemBorderColor=["#FCEEB0","#4C99AB"];
    var itemBorderStyle=["solid","solid"];
    var itemSpacing=2;
    var itemPadding="3px";
    var itemAlignTop="left";
    var itemAlign="left";
    var subMenuAlign="left";

    //--- Icons
    var iconTopWidth=16;
    var iconTopHeight=16;
    var iconWidth=16;
    var iconHeight=16;
    var arrowWidth=8;
    var arrowHeight=16;
    var arrowImageMain=["images/arrowmain.gif","images/arrowmaino.gif"];
    var arrowImageSub=["images/arrowsub.gif","images/arrowsubo.gif"];

    //--- Separators
    var separatorImage="images/sep.gif";
    var separatorWidth="100%";
    var separatorHeight="3";
    var separatorAlignment="left";
    var separatorVImage="images/sep2.gif";
    var separatorVWidth="2";
    var separatorVHeight="100%";
    var separatorPadding="2px";

    //--- Floatable Menu
    var floatable=0;
    var floatIterations=6;
    var floatableX=1;
    var floatableY=1;

    //--- Movable Menu
    var movable=0;
    var moveWidth=12;
    var moveHeight=20;
    var moveColor="#DECA9A";
    var moveImage="";
    var moveCursor="move";
    var smMovable=0;
    var closeBtnW=15;
    var closeBtnH=15;
    var closeBtn="";

    //--- Transitional Effects & Filters
    var transparency="80";
    var transition=24;
    var transOptions="";
    var transDuration=350;
    var transDuration2=200;
    var shadowLen=3;
    var shadowColor="#B1B1B1";
    var shadowTop=0;

    //--- CSS Support (CSS-based Menu)
    var cssStyle=0;
    var cssSubmenu="";
    var cssItem=["",""];
    var cssItemText=["",""];

    //--- Advanced
    var dmObjectsCheck=0;
    var saveNavigationPath=1;
    var showByClick=0;
    var noWrap=1;
    var pathPrefix_img="";
    var pathPrefix_link="";
    var smShowPause=200;
    var smHidePause=1000;
    var smSmartScroll=1;
    var smHideOnClick=1;
    var dm_writeAll=1;

    //--- AJAX-like Technology
    var dmAJAX=0;
    var dmAJAXCount=0;

    //--- Dynamic Menu
    var dynamic=0;

    //--- Keystrokes Support
    var keystrokes=0;
    var dm_focus=1;
    var dm_actKey=113;

    var menuItems = [
    ';


    // stop editing here





    /*******************************************************************************/
    function createDeluxeMenuJSCode($start_id=0, $counter=0, $param=array()) {

        $li             = '';
        $TAB            = str_repeat('  ', $counter);

        foreach($GLOBALS['content']['struct'] as $key => $value) {

            // ["Product Info","", "default.files/icon1.gif", "default.files/icon1o.gif", , , , , , ],

            if($GLOBALS['content']['struct'][$key]["acat_struct"] == $start_id && $key  && (!$GLOBALS['content']['struct'][$key]['acat_hidden'] || ($GLOBALS['content']['struct'][$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {

                $li .= $TAB.'  ["'.str_repeat('|', $counter);
                $link = '';
                if(!$GLOBALS['content']['struct'][$key]["acat_redirect"]) {
                    $link .= 'index.php?';
                    if($GLOBALS['content']['struct'][$key]['acat_alias']) {
                        $link .= $GLOBALS['content']['struct'][$key]['acat_alias'];
                    } else {
                        $link .= 'id='.$key.',0,0,1,0,0';
                    }
                    $target = '';
                } else {
                    $link   = explode(' ', $GLOBALS['content']['struct'][$key]["acat_redirect"]);
                    $link   = empty($link[0]) ? '#' : $link[0];
                    $target = empty($link[1]) ? ''  :strtolower($link[1]);
                }
                $li .= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);

                $inorm = empty($param['icon'][$counter]['iconNormal']) ? '' : trim($param['icon'][$counter]['iconNormal']);
                $iover = empty($param['icon'][$counter]['iconOver']) ? '' : trim($param['icon'][$counter]['iconOver']);

                $li .= '", "'.$link.'", "'.$inorm.'", "'.$iover.'", , "'.$target.'", , , , ],'.LF;
                $li .= createDeluxeMenuJSCode($key, $counter+1, $param);

            }
        }

        return $li;
    }
    /*******************************************************************************/

    $GLOBALS['DeluxeMenuParam']['start_at_ID'] = 0;
    $content['all'] = str_replace('{DELUXE_MENU}', '{DELUXE_MENU:0}', $content['all']);
    $content['all'] = preg_replace_callback(
        '/\{DELUXE_MENU:(.*?)\}/',
        function($matches) {
            $GLOBALS["DeluxeMenuParam"]["start_at_ID"] = $matches[1];
            return "{DELUXE_MENU}";
        },
        $content['all']
    );
    $DeluxeMenuParam['start_at_ID'] = intval($GLOBALS['DeluxeMenuParam']['start_at_ID']);

    $DeluxeMenuParam['js'] .= createDeluxeMenuJSCode($DeluxeMenuParam['start_at_ID'], $counter=0, $DeluxeMenuParam);
    $DeluxeMenuParam['js'] .= LF.'   ]'.LF;

    $DeluxeMenuParam['text']  = '<script type="text/javascript">'.LF.SCRIPT_CDATA_START;
    $DeluxeMenuParam['text'] .= LF.'dm_init();'.LF;
    $DeluxeMenuParam['text'] .= SCRIPT_CDATA_END.LF.'</script><noscript>';
    $DeluxeMenuParam['text'] .= buildCascadingMenu( ',' . $DeluxeMenuParam['start_at_ID'] );
    $DeluxeMenuParam['text'] .= '</noscript>';

    $block['custom_htmlhead']['DeluxeMenu']  = '  <script type="text/javascript">'.LF.'  '.SCRIPT_CDATA_START.LF;
    $block['custom_htmlhead']['DeluxeMenu'] .= '    var dmWorkPath="'.$DeluxeMenuParam['base_path'].'";';
    $block['custom_htmlhead']['DeluxeMenu'] .= LF.'  '.SCRIPT_CDATA_END.LF.'  </script>'.LF;
    $block['custom_htmlhead']['DeluxeMenu'] .= '  <script type="text/javascript" src="'.$DeluxeMenuParam['base_path'].'dmenu.js"></script>';
    $block['custom_htmlhead']['DeluxeMenu'] .= LF.'  <script type="text/javascript">'.LF.'  '.SCRIPT_CDATA_START.LF;
    $block['custom_htmlhead']['DeluxeMenu'] .= $DeluxeMenuParam['js'];
    $block['custom_htmlhead']['DeluxeMenu'] .= LF.'  '.SCRIPT_CDATA_END.LF.'  </script>'.LF;

    $content['all'] = str_replace('{DELUXE_MENU}', $DeluxeMenuParam['text'], $content['all']);

}
