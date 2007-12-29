<?php
/**
 * SPAW Editor v.2 Main include file
 * 
 * Include this file in your script
 *   
 * @package spaw2
 * @author Alan Mendelevich <alan@solmetra.lt> 
 * @copyright UAB Solmetra   
 */

$spaw_include_path = str_replace('/include/inc_ext/spaw2', '', str_replace('\\', '/', dirname(__FILE__)));
require_once($spaw_include_path.'/include/inc_ext/spaw2/config/config.php');
require_once(PHPWCMS_ROOT.'/include/inc_ext/spaw2/class/editor.class.php');

// load plugin configs
$spaw_pgdir = SpawConfig::getStaticConfigValue('SPAW_ROOT').'plugins/';
if (is_dir($spaw_pgdir)) 
{
  if ($spaw_dh = opendir($spaw_pgdir)) 
  {
    while (($spaw_pg = readdir($spaw_dh)) != false) 
    {
      if ($spaw_pg != '.' && $spaw_pg != '..')
      {
        if (is_dir($spaw_pgdir.$spaw_pg.'/config'))
        {
          if ($spaw_pgdh = opendir($spaw_pgdir.$spaw_pg.'/config')) 
          {
            while (($spaw_fn = readdir($spaw_pgdh)) !== false) 
            {
              if ($spaw_fn != '.' && $spaw_fn != '..' && !is_dir($spaw_pgdir.$spaw_pg.'/config/'.$spaw_fn))
                include($spaw_pgdir.$spaw_pg.'/config/'.$spaw_fn);
            }
            closedir($spaw_pgdh);
          }
        }
      }
    }
    closedir($spaw_dh);
  }
}    
?>