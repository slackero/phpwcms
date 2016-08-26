<?php
/*************************************************************************************
	 Copyright notice
	 
	 (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
	 This script is part of PHPWCMS. The PHPWCMS web content management system is
	 free software; you can redistribute it and/or modify it under the terms of
	 the GNU General Public License as published by the Free Software Foundation;
	 either version 2 of the License, or (at your option) any later version.
	
	 The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
	 A copy is found in the textfile GPL.txt and important notices to the license 
	 from the author is found in LICENSE.txt distributed with these scripts.
	
	 This script is distributed in the hope that it will be useful, but WITHOUT ANY 
	 WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
	 PARTICULAR PURPOSE.	See the GNU General Public License for more details.
 
	 This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	 die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//replace mootools with jquery
$GLOBALS['BE']['HEADER']['mootools.js'] = getJavaScriptSourceLink('include/inc_js/jquery/jquery.min.js');

//foldername in inc_cntpart 
$wcs_content_templates = array(
     0 => 'plaintext',
     6 => 'html',
    14 => 'wysywig',
    11 => 'code',
     1 => 'imagetext',
    29 => 'images',
    31 => 'imagespecial',
    32 => 'tabs',
     2 => '',
     4 => 'bulletlist',
   100 => '',
     3 => 'linkemail',
     5 => 'linklist',
     8 => 'teaser',
    33 => 'news',
    15 => '',
     9 => 'multimedia',
     7 => 'filelist',
    16 => '',
    23 => '',
    10 => '',
    12 => 'newsletter',
    13 => 'search',
    18 => 'guestbook',
    19 => '',
    21 => '',
    22 => 'rssfeed',
    50 => 'reference',
    51 => 'map',
    52 => '',
    24 => '',
    89 => '',
    26 => 'recipe',
    27 => 'faq',
    28 => 'felogin',
    25 => 'flashplayer'
);

//default templates in inc_default 
$wcs_content_templates_default = array(
     0 => 'plaintext.tmpl',
     6 => 'html.tmpl',
    14 => 'wysywig.tmpl',
    11 => 'code.tmpl',
     1 => 'imagetext.tmpl',
    29 => 'images.tmpl',
    31 => 'imagespecial.tmpl',
    32 => 'tabs.tmpl',
     2 => 'imagetable.tmpl',
     4 => 'bulletlist.tmpl',
   100 => '',
     3 => '',
     5 => 'linklist.tmpl',
     8 => 'teaser.tmpl',
    33 => 'news.tmpl',
    15 => '',
     9 => 'multimedia.tmpl',
     7 => 'filelist.tmpl',
    16 => '',
    23 => '',
    10 => '',
    12 => '',
    13 => 'search.tmpl',
    18 => 'guestbook.tmpl',
    19 => '',
    21 => '',
    22 => 'rssfeed.tmpl',
    50 => 'reference.tmpl',
    51 => '',
    52 => '',
    24 => '',
    89 => '',
    26 => '',
    27 => '',
    28 => 'felogin.tmpl',
    25 => 'flashplayer.tmpl'
);

//read variables
$action = isset($_GET["action"]) ? $_GET["action"]: '';
$fcopy["src"] = isset($_GET["src"]) ? $_GET["src"]: '';
$fcopy["target"] = isset($_GET["target"]) ? $_GET["target"]: '';
$fdelete["src"] = isset($_GET["delete"]) ? $_GET["delete"]: '';
$fname["filename"] = isset($_GET["filename"]) ? $_GET["filename"]: '';
$fname["filenamenew"] = isset($_POST["filenamenew"]) ? $_POST["filenamenew"]: '';
$fname["srcfolder"] = isset($_GET["srcfolder"]) ? $_GET["srcfolder"]: '';
$fname["contenttype"] = isset($_GET["contenttype"]) ? $_GET["contenttype"]: 0;

//copy template
if ($fcopy["src"] && $fcopy["target"]) { 
  if (copy(PHPWCMS_TEMPLATE.$fcopy["src"], PHPWCMS_TEMPLATE.$fcopy["target"])) {
    echo '<div class="alert-success" id="msgbox">'.$fcopy["target"] .$BLM['success_msg'].'</div>';
  } else {
    echo '<div class="alert-danger" id="msgbox">'.$fdelete["src"] .$BLM['success_msg_error'].'</div>';
  }
}

//delete template
if ($fdelete["src"] && $action == 'delete') { 
  if (unlink (PHPWCMS_TEMPLATE.$fdelete["src"])) {
    echo '<div class="alert-success" id="msgbox">'.$fdelete["src"] .$BLM['deleted_msg'].'</div>';
  } else {
    echo '<div class="alert-danger" id="msgbox">'.$fdelete["src"] .$BLM['deleted_msg_error'].'</div>';
  }
}
//rename template and update records using this template
if ($fname["filename"] !='' && $fname["filenamenew"] !='' && $fname["srcfolder"] !='' && $action == 'rename' && intval($fname["contenttype"]) >0) {
  if (rename (PHPWCMS_TEMPLATE.$fname["srcfolder"].'/'.$fname["filename"], PHPWCMS_TEMPLATE.$fname["srcfolder"].'/'.$fname["filenamenew"])) {
    _dbUpdate('phpwcms_articlecontent', array('acontent_template'=>$fname["filenamenew"]), 'acontent_type='.intval($fname["contenttype"]).' AND acontent_template = ' . _dbEscape($fname["filename"]));
    echo '<div class="alert-success" id="msgbox">'.$fname["filename"] .$BLM['rename_msg'].'</div>';
  } else {
    echo '<div class="alert-danger" id="msgbox">'.$fname["filename"] .$BLM['rename_msg_error'].'</div>';
  }
}

?>

<script type=text/javascript>
<!--
function showHtml(template) {
  $.ajax({
    url: "<?php echo $phpwcms['modules'][$module]['dir']; ?>ajax_template.php",
    data: {
      action: 'form',
      'template': template
    },
    success: function(data) {
      $('#codebox').html(data);
      $('#codebox').show();
      $('#listarticles').hide();
      $('#msgbox').hide();
    }
  })
}

function listctn(template, id) {
  $.ajax({
    url: "<?php echo $phpwcms['modules'][$module]['dir']; ?>ajax_template.php",
    data: {
      action: 'list',
      'template': template,
      'id': id
    },
    success: function(data) {
      $('#listarticles').html(data);
      $('#listarticles').show();
      $('#codebox').hide();
      $('#msgbox').hide();
    }
  })
}

$( function() {
  $( document ).tooltip({
    items: "img, [title]",
    show: "fold",
    close: function(event, ui) {
      ui.tooltip.hover(function() {
         $(this).stop(true).fadeTo(200, 1); 
      },
      function() {
         $(this).fadeOut(200, function() {
            $(this).remove();
         });
      });
    },
    content: function () {
      return $(this).prop('title');
    }
  });
});
  
function showMenu(imgid, divid) {
  $(imgid).attr('title', $(divid).remove().html());
}
//-->
</script> 

<h1><?php echo $BLM['listing_title']?></h1>

<textarea name="codebox" rows="30" class="width540" id="codebox" style="display: none;"></textarea>

<div id="listarticles"></div>

<? if (intval($fname["contenttype"]) >0 && $fname["filename"] !='' && $fname["srcfolder"] !='' && $action == 'renameform') { ?>
<h1><?php echo $BLM['file_rename']?></h1>
<form action="<?php echo TEMPLATE_HREF.'&contenttype='.$fname["contenttype"].'&filename='.$fname["filename"].'&srcfolder='.$fname["srcfolder"].'&action=rename';?>" method="post" name="renamefile" id="renamefile" style="margin-top:3px">
	<input name="filenamenew" type="text" value="<?php echo $fname["filename"] ?>" class="v11 width400" />
  <input name="Submit" type="submit" class="button" value="<?php echo $BLM['file_rename'] ?>" />
</form>
<?php } ?>

<div class="tmpl_menu">
<?php 
//get allfiles
//$rootDir = PHPWCMS_TEMPLATE.'inc_cntpart';
//echo '<ul>';
//scandir_rec($rootDir,'');
//echo '</ul>';

foreach($wcs_content_type as $key => $value):

	// count used CPs so it is easier to decide if needed or not
	$used_count = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_type='._dbEscape($key));
          
  if ($wcs_content_templates[$key]) {
    echo '<h4>'.html($value).' ('.$used_count.'), [ID:'.$key.'], '.$BLM['label_folder'].': /' . $wcs_content_templates[$key] .'</h4>';
    
    //lets get custom templates for this content part
    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/'.$wcs_content_templates[$key]);
    $path =  PHPWCMS_TEMPLATE.'inc_cntpart/'.$wcs_content_templates[$key];
    $output = '';
    $output2 = '';
    if ($wcs_content_templates_default[$key] || (is_array($tmpllist) && count($tmpllist))) { 
      $output = '<ul>';
      //first get default template
      $used_template = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_type='._dbEscape($key)." AND acontent_template =''");
      $output .= '<li><img border="0" alt="" src="img/icons/folder_zu.gif"> <label>'.$BLM['label_default'].'</label><ul><li><img border="0" alt="" src="'.$phpwcms['modules'][$module]['dir'].'template/img/page_white_wrench.png" id="menudefaultimg'.$key.'" onmouseover="'."showMenu('#menudefaultimg".$key."','#menudefault".$key."')".'"> '.$wcs_content_templates_default[$key].'('.$used_template.')';
      //default menu
      $output .=  '<div id="menudefault'.$key.'" class="p_menu"><ul><li><img onclick="showHtml('."'".PHPWCMS_TEMPLATE.'inc_default/'.$wcs_content_templates_default[$key]."'".')" border="0" alt="'.$BLM['show_code'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/script_code.png" class="hoverimg">'.$BLM['show_code'].'</li>';
      //if template used show menu row to list articles
      if ($used_template > 0) {
        $output .= '<li><img onclick="listctn('."'',".$key.')" border="0" halign="center" alt="'.$BLM['list_files'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/application_view_list.png" class="hoverimg">'.$BLM['list_files'].'</li>';
      }
      //menu row to copy this default template
      $output .= '<li><a href="'.TEMPLATE_HREF.'&src=inc_default/'.$wcs_content_templates_default[$key].'&target='. 'inc_cntpart/'.$wcs_content_templates[$key].'/custom.'.$wcs_content_templates_default[$key].'"><img border="0" alt="'.$BLM['file_copy'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/page_copy.png"></a>'.$BLM['file_copy'].'</li></div></li></ul></li>';
      
      //if templates are found in custom folder list them
      //rename for teaser (ID:8) excluded. Template is not stored in acontent_template
      if(is_array($tmpllist) && count($tmpllist)) {
        $output .= '<li><img border="0" alt="" src="img/icons/folder_galleryroot.gif"> <label>'.$BLM['label_custom'].' (/inc_cntpart/'.$wcs_content_templates[$key].')</label><ul>';
        $i=0;
        foreach($tmpllist as $val) {
          $used_template = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_type='._dbEscape($key)." AND acontent_template ='".$val."'");
          if ($key == '8') {
            $used_template = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_type='._dbEscape($key)." AND acontent_form like '%".$val."%'");
          }
          $output .=  '<li><img border="0" alt="" src="'.$phpwcms['modules'][$module]['dir'].'template/img/page_white_wrench.png" id="menucustomimg'.$key.'-'.$i.'" onmouseover="'."showMenu('#menucustomimg".$key.'-'.$i."','#menucustom".$key.'-'.$i."')".'">'.html($val).' ('.$used_template.')';
          //custom menu
          $output .=  '<div id="menucustom'.$key.'-'.$i.'" class="p_menu"><ul><li><img onclick="showHtml('."'".$path.'/'.$val."'".')" border="0" alt="'.$BLM['show_code'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/script_code.png" class="hoverimg">'.$BLM['show_code'].'</li>';
          //if template used show menu row to list articles
          if ($used_template > 0) {
            $output .=  '<li><img onclick="listctn('."'".$val."',".$key.')" border="0" alt="'.$BLM['list_files'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/application_view_list.png" class="hoverimg">'.$BLM['list_files'].'</li>';
          }
          if ($key != '8') {
            //menu row to rename this custom template
            $output .= '<li><a href="'.TEMPLATE_HREF.'&contenttype='.$key.'&srcfolder=inc_cntpart/'.$wcs_content_templates[$key].'&action=renameform&filename='.$val.'"><img  border="0" alt="'.$BLM['file_rename'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/textfield_rename.png"></a>'.$BLM['file_rename'].'</li>';
          }
          //menu row to copy this custom template
          $output .= '<li><a href="'.TEMPLATE_HREF.'&src=inc_cntpart/'.$wcs_content_templates[$key].'/'.$val.'&target='. 'inc_cntpart/'.$wcs_content_templates[$key].'/custom.'.$val.'"><img  border="0" alt="'.$BLM['file_copy'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/page_copy.png"></a>'.$BLM['file_copy'].'</li>';

          //menu row to delete this custom template
          if ($used_template == 0) {
            $output .=  '<li><a href="'.TEMPLATE_HREF.'&delete=inc_cntpart/'.$wcs_content_templates[$key].'/'.$val.'&action=delete"><img  border="0" alt="'.$BLM['file_delete'].'" src="img/button/trash_13x13_1.gif"></a>'.$BLM['file_delete'].'</li>';
          }
          $output .= '</div></li>';
          $i++;
        }
        $output .=  '</ul></li>';
      }
    }
    //now lets build list for templates in sample folder
    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/'.$wcs_content_templates[$key].'/sample');
    if(is_array($tmpllist) && count($tmpllist)) {
      $output2 .= '<ul>';
      $i=0;
      foreach($tmpllist as $val) {
        $output2 .= '<li><img border="0" alt="" src="'.$phpwcms['modules'][$module]['dir'].'template/img/page_white_wrench.png" id="menusampleimg'.$key.'-'.$i.'" onmouseover="'."showMenu('#menusampleimg".$key.'-'.$i."','#menusample".$key.'-'.$i."')".'">'.html($val);
        //sample menu. show code and copy function
        $output2 .= '<div id="menusample'.$key.'-'.$i.'" class="p_menu"><ul><li><img onclick="showHtml('."'".$path.'/sample/'.$val."'".')" border="0" alt="'.$BLM['show_code'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/script_code.png" class="hoverimg">'.$BLM['show_code'].'</li><li><a href="'.TEMPLATE_HREF.'&src=inc_cntpart/'.$wcs_content_templates[$key].'/sample/'.$val.'&target='. 'inc_cntpart/'.$wcs_content_templates[$key].'/custom.'.$val.'"><img border="0" alt="'.$BLM['file_copy'].'" src="'.$phpwcms['modules'][$module]['dir'].'template/img/page_copy.png" title=""></a> '.$BLM['file_copy'].'</li></div></li>';
        $i++;
      }
      $output2 .= '</ul>';
    }
    //build ul list
    if ($output != "" && $output2 != "") {
      echo $output .'<li><img border="0" alt="" src="img/icons/folder_zu.gif"> <label>'.$BLM['label_sample'].'</label>'.$output2.'</li></ul>';
    } elseif ($output != "") {
      echo $output .'</ul>';
    } elseif ($output2 != "") {
      echo'<ul><li><img border="0" alt="" src="img/icons/folder_zu.gif"> <label>'.$BLM['label_sample'].'</label>'.$output2.'</li></ul>';
    }
  }
endforeach;	
?>
</div>
