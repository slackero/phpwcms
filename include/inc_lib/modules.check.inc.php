<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// make all necessary module checks

$cmsgo_modules = returnSubdirListAsArray(CMSGO_ROOT.'/include/inc_module');
$value = '';

foreach($cmsgo_modules as $value) {

  // set default vars
  $_module_name       = '';
  $_module_type       = 0;
  $_module_contentpart  = false;
  $_module_fe_render    = false;
  $_module_fe_init      = false;
  $_module_fe_search    = false;
  $_module_fe_setting   = false;

  if(is_file(CMSGO_ROOT.'/include/inc_module/'.$value.'/module.default.php')) {

    // main settings
    require CMSGO_ROOT.'/include/inc_module/'.$value.'/module.default.php';

    // define as module - use default name
    if($_module_name !== '') {

      $cmsgo['modules'][$_module_name]        = array();
      $cmsgo['modules'][$_module_name]['name']    = $_module_name;
      $cmsgo['modules'][$_module_name]['type']    = $_module_type;
      $cmsgo['modules'][$_module_name]['cntp']    = $_module_contentpart;
      $cmsgo['modules'][$_module_name]['path']    = CMSGO_ROOT.'/include/inc_module/'.$value.'/';
      $cmsgo['modules'][$_module_name]['dir']   = 'include/inc_module/'.$value.'/';
      $cmsgo['modules'][$_module_name]['search']  = $_module_fe_search;
      $cmsgo['modules'][$_module_name]['setting'] = $_module_fe_setting;

      // main module language include -> english is always neccessary
      // but not necessary in frontend
      if(!isset($IS_A_BOT)) {

        if(is_file($cmsgo['modules'][$_module_name]['path'].'lang/en.lang.php')) {

          $BLM = array();
          include_once $cmsgo['modules'][$_module_name]['path'].'lang/en.lang.php';

          // try to find right language - will be merged with default english
          if(is_file($cmsgo['modules'][$_module_name]['path'].'lang/'.$BE['LANG'].'.lang.php')) {
            include_once $cmsgo['modules'][$_module_name]['path'].'lang/'.$BE['LANG'].'.lang.php';
          }

          // put mdule language setting into global language array
          $BL['modules'][$_module_name] = $BLM;

        } else {
          unset($cmsgo['modules'][$_module_name]);
        }
      }

      if($_module_fe_render && is_file(CMSGO_ROOT.'/include/inc_module/'.$value.'/frontend.render.php')) {
        $cmsgo['modules_fe_render'][] = CMSGO_ROOT.'/include/inc_module/'.$value.'/frontend.render.php';
      }
      if($_module_fe_init && is_file(CMSGO_ROOT.'/include/inc_module/'.$value.'/frontend.init.php')) {
        $cmsgo['modules_fe_init'][] = CMSGO_ROOT.'/include/inc_module/'.$value.'/frontend.init.php';
      }

    }

  }

}

unset($cmsgo_modules, $BLM);