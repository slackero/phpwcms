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
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if($_GET['struct'] === 'index') {

	$acat_title			= $indexpage['acat_name'];
	$acat_info			= $indexpage['acat_info'];
	$acat_id			= 'index';
	$acat_new			= 0;
	$acat_aktiv			= $indexpage['acat_aktiv'];
	$acat_public		= $indexpage['acat_public'];
	$acat_sort			= '';
	$acat_alias			= $indexpage['acat_alias'];
	$acat_hidden		= $indexpage['acat_hidden'];
	$acat_template		= $indexpage['acat_template'];
	$acat_ssl			= $indexpage['acat_ssl'];
	$acat_regonly		= $indexpage['acat_regonly'];
	$acat_topcount		= $indexpage['acat_topcount'];
	$acat_maxlist		= $indexpage['acat_maxlist'];
	$acat_redirect		= $indexpage['acat_redirect'];
	$acat_timeout		= strval($indexpage['acat_timeout']);
	$acat_nosearch		= strval($indexpage['acat_nosearch']);
	$acat_nositemap		= strval($indexpage['acat_nositemap']);
	$acat_order			= get_order_sort($indexpage['acat_order']);
	$acat_permit		= empty($indexpage['acat_permit']) ? array() : explode(',', $indexpage['acat_permit']) ;
	$acat_cntpart		= (isset($indexpage['acat_cntpart']) && $indexpage['acat_cntpart'] != '') ? explode(',', $indexpage['acat_cntpart']) : array();
	$acat_pagetitle		= empty($indexpage['acat_pagetitle']) ? '' : $indexpage['acat_pagetitle'];
	$acat_paginate		= empty($indexpage['acat_paginate']) ? 0 : 1;
	$acat_overwrite		= empty($indexpage['acat_overwrite']) ? '' : $indexpage['acat_overwrite'];
	
} else {

	if(!isset($acat_title)) {
	
		$parentStructData = getParentStructArray($_GET["struct"]);
	
		$acat_title			= '';
		$acat_info			= '';
		$acat_aktiv			= 1;
		$acat_public		= 1;
		$acat_sort			= '';
		$acat_alias			= '';
		$acat_hidden		= 0;
		$acat_hiddenactive	= 0;
		$acat_template		= $parentStructData['acat_template'];
		$acat_ssl			= 0;
		$acat_regonly		= 0;
		$acat_redirect		= '';
		$acat_nositemap		= 1;
		$acat_maxlist		= 0;
		$acat_permit		= array();
		$acat_cntpart		= array();
		$acat_pagetitle		= '';
		$acat_paginate		= 0;
		$acat_overwrite		= '';
	}
}

switch($acat_hidden) {

	case 1:		$acat_hidden 		= 1;
				$acat_hiddenactive	= 0;
				break;
				
	case 2:		$acat_hidden 		= 1;
				$acat_hiddenactive	= 1;
				break;
	
	default:	$acat_hidden 		= 0;
				$acat_hiddenactive	= 0;

}

// this -> document.editsitestructure
?>
<form action="include/inc_act/act_structure.php" method="post" name="editsitestructure" id="editsitestructure" onsubmit="selectAllOptions(this.acat_access);selectAllOptions(this.acat_cp);var x = wordcount(this.acat_name.value);if(x&lt;1) {alert('Fill in a category title! \n\n('+x+' words total)');this.acat_name.focus();return false;}">
	<table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" summary="">
		
          <tr><td width="538" class="title"><?php echo $BL['be_admin_struct_title'] ?> <span style="font-weight: normal;"><?php echo $BL['be_admin_struct_child'] ?></span>: <strong style="color: #FF3300"><?php
		  	//Anzeigen des Kategorienamens (Menüpunkt)
		  	$acat_struct = intval($_GET["struct"]);
		  	if($acat_struct) {
			
				$parentStructData = getParentStructArray($acat_struct);
				echo html_specialchars($parentStructData["acat_name"]);

			} else {
				echo $BL['be_admin_struct_index'];
			}		  
		  ?></strong></td>
          </tr>
		  <tr><td><img src="img/leer.gif" width="1" height="4" alt="" /></td>
		  </tr>
          <tr><td><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
          </tr>
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		  </tr>
          <tr><td class="v09"><?php echo $BL['be_admin_struct_cat'] ?>:</td></tr>
          <tr><td><input name="acat_name" type="text" id="acat_name" class="f11b" style="width: 450px" onchange="this.value=Trim(this.value);" value="<?php echo html_specialchars($acat_title) ?>" size="50" maxlength="95" /></td></tr>

		  <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		  </tr>
  
		  <tr><td class="v09"><a href="#" onclick="return set_article_alias(false, 'struct');"><?php echo $BL['be_admin_struct_alias'] ?></a>:</td></tr>
		  <tr><td><input name="acat_alias" type="text" id="acat_alias" class="f11b" style="width: 450px" value="<?php echo html_specialchars($acat_alias) ?>" size="50" maxlength="150" onfocus="set_article_alias(true, 'struct');" onchange="this.value=create_alias(this.value);" /></td></tr>
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		  </tr>
		  
		  <tr><td class="v09"><?php echo $BL['be_admin_page_pagetitle'] ?>:</td></tr>
		  <tr><td><input name="acat_pagetitle" type="text" id="acat_pagetitle" class="f11b" style="width: 450px" value="<?php echo html_specialchars($acat_pagetitle) ?>" size="50" maxlength="150" /></td></tr>
 
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		  </tr>
<?php
if($acat_id != 'index' || strval($acat_id) == '0') {
?>  
		  <tr><td class="v09"><?php echo $BL['be_article_aredirect'] ?>:</td></tr>
		  <tr><td><input name="acat_redirect" type="text" id="acat_redirect" class="f11b" style="width: 450px" value="<?php echo html_specialchars($acat_redirect) ?>" size="50" maxlength="255" /></td></tr>
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		  </tr>
<?php
}
?>
		  <tr><td class="v09"><?php echo $BL['be_admin_struct_info'] ?>:</td></tr>
          <tr><td><textarea name="acat_info" cols="50" rows="6" id="acat_info" class="f11" style="width: 536px"><?php echo html_specialchars($acat_info) ?></textarea></td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
          </tr>
		  <tr>
		  	<td class="v09"><?php echo $BL['be_admin_struct_template'] ?>:</td>
		  </tr>
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
		  </tr>
		  <tr>
		  	<td><select name="acat_template" id="acat_template" class="f11b width300">
<?php

$_temp_cat = '';

// list available 
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_trash=0 ORDER BY template_default DESC";
if($result = mysql_query($sql, $db) or die("error while listing templates")) {
	while($row = mysql_fetch_assoc($result)) {
		echo "<option value=\"".$row["template_id"]."\"";
		if($row["template_id"] == $acat_template) {
			echo " selected";
			$_temp_cat = @unserialize($row['template_var']);
			$_temp_cat = empty($_temp_cat['overwrite']) ? '' : $_temp_cat['overwrite'];
		}
		echo ">".html_specialchars($row["template_name"]).( ($row["template_default"])?" (default)":" ");
		echo "</option>\n";
	}
	mysql_free_result($result);
}

?>
		  	</select></td>
		  </tr>
		  
		  
		  
          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
          </tr>
		  <tr>
		  	<td class="v09"><?php echo $BL['be_settings'] ?>:&nbsp;<i><?php echo $BL['be_overwrite_default'] ?></i></td>
		  </tr>
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="2" /></td>
		  </tr>
		  <tr>
		  	<td><select name="acat_overwrite" id="acat_overwrite" class="f11b width300">
			<option value="" style="font-weight:normal;font-style:italic;"><?php echo $BL['be_admin_tmpl_default']; ?></option>
<?php
	
// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_settings/template_default', 'php');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($acat_overwrite) && $val == $acat_overwrite) ? ' selected="selected"' : '';
		$val = html_specialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . ($_temp_cat==$val ? ' ('.$BL['be_admin_struct_template'].')' : '') . '</option>' . LF;
	}
}

?>				  
		</select></td>
		  </tr>
		  
		  
          
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		  </tr>
		  
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		 <tr>
		  	<td class="v09"><?php echo  $BL['be_admin_struct_topcount'] ?>:</td>
			<td class="v09">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			
			<td class="v09" colspan="2"><?php echo  $BL['be_pagination'] ?>:</td>
			
			<td class="v09">&nbsp;&nbsp;</td>
			<td class="v09"><?php echo $BL['be_article_per_page'] //$BL['be_admin_struct_maxlist'] ?>:</td>
	  	  </tr>
		  <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
		  </tr>
		  <tr>
		  	<td><input name="acat_topcount" type="text" id="acat_topcount" class="f11b" style="width:135px" value="<?php echo  intval($acat_topcount) ?>" size="10" maxlength="10" /></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			
			<td bgcolor="#D9DEE3"><input name="acat_paginate" type="checkbox" id="acat_paginate" value="1" <?php if($acat_paginate == 1) echo "checked"; ?> /></td>
			<td bgcolor="#D9DEE3">&nbsp;<label for="acat_paginate"><?php echo $BL['be_article_pagination'] ?></label>&nbsp;&nbsp;</td>
			
			<td>&nbsp;&nbsp;</td>
			<td><input name="acat_maxlist" type="text" id="acat_maxlist" class="f11b" style="width:80px" value="<?php echo empty($acat_maxlist) ? '' : intval($acat_maxlist); ?>" size="10" maxlength="10" /></td>
		  </tr>
		  </table></td>
	</tr>
		  
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		  </tr>
		  
		  <tr>
		  	<td class="v09"><?php echo  $BL['be_admin_struct_orderarticle'] ?>:</td>
	      </tr>
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="2" /></td>
		  </tr>
          <tr>
            <td valign="top">
			<div style="margin:0;border:1px solid #D9DEE3;padding:5px;float:left;">
			<table border="0" cellpadding="0" cellspacing="0" summary=""><!-- seems not neccessary anymore: onclick="noDESC()" --> 
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order0" value="0"<?php is_checked(0, intval($acat_order[0])) ?> /></td>
                  <td>&nbsp;<label for="acat_order0"><?php echo  $BL['be_admin_struct_ordermanual'] ?></label>&nbsp;&nbsp;</td>
                  <td rowspan="6">&nbsp;</td>
                  <td><input type="radio" name="acat_ordersort" id="acat_ordersort0" value="0"<?php is_checked(0, intval($acat_order[1])) ?> /></td>
                  <td>&nbsp;<label for="acat_ordersort0"><?php echo  $BL['be_admin_struct_orderasc'] ?></label>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order1" value="2"<?php is_checked(2, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order1"><?php echo  $BL['be_admin_struct_orderdate'] ?></label>&nbsp;&nbsp;</td>
                  <td><input type="radio" name="acat_ordersort" id="acat_ordersort1" value="1"<?php is_checked(1, $acat_order[1]) ?> /></td>
                  <td>&nbsp;<label for="acat_ordersort1"><?php echo  $BL['be_admin_struct_orderdesc'] ?></label>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order2" value="4"<?php is_checked(4, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order2"><?php echo  $BL['be_admin_struct_orderchangedate'] ?></label>&nbsp;&nbsp;</td>
                  <td colspan="2" rowspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order3" value="6"<?php is_checked(6, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order3"><?php echo  $BL['be_admin_struct_orderstartdate'] ?></label>&nbsp;&nbsp;</td>
                </tr>
				
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order5" value="10"<?php is_checked(10, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order5"><?php echo  $BL['be_admin_struct_orderkilldate'] ?></label>&nbsp;&nbsp;</td>
                </tr>
				
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order4" value="8"<?php is_checked(8, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order4"><?php echo  $BL['be_article_atitle'] ?></label>&nbsp;&nbsp;</td>
                </tr>
            </table>
            </div>			</td>
          </tr>
		  
		  
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		  </tr>
		  
		  
<!-- Content Part Selection -->
		  
		  <tr><td class="v09">Content Part selection:</td></tr>
		  <tr><td><img src="img/leer.gif" width="1" height="2" alt="" /></td>
		  </tr>
          <tr>
            <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
              <tr>
                <td><select name="acat_cp[]" id="acat_cp" size="9" multiple="multiple" style="width: 255px" class="f10" ondblclick="moveSelectedOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa,false);">

<?php

// check which content part is available
$temp_count = 0;
foreach($acat_cntpart as $value) {
	if(isset($wcs_content_type[$value])) {
	/*
	if(isContentPartSet($value)) {
		
		list($value_id, $value_module) = explode(':', $value);
		echo '<option value="'.$value.'">'.$wcs_content_type[$value_id];
		if(isset($phpwcms['modules'][$value_module])) {
			echo ': '.$BL['modules'][ $value_module ]['listing_title'];
		}
		echo "</option>\n";
		*/
		echo '<option value="'.$value.'">'.$wcs_content_type[$value]."</option>\n";;
		unset($wcs_content_type[$value]);
	}
	$value1 = $value * (-1);
	if(isset($BL['be_admin_optgroup_label'][$value1])) {
		echo '<option value="'.$value.'">[optgroup] '.$BL['be_admin_optgroup_label'][$value1]."</option>\n";
		unset($BL['be_admin_optgroup_label'][$value1]);
	}
}

?>
						</select></td>
<td valign="top" style="padding-left:5px;padding-right:5px;">
<img src="img/button/put_left.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp);" alt="" /><br />
<img src="img/leer.gif" width="1" height="3" alt="" /><br />
<img src="img/button/put_left_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp,false);" alt="" /><br />
<img src="img/leer.gif" width="1" height="6" alt="" /><br />
<img src="img/button/put_right_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa,false);" alt="" /><br />
<img src="img/leer.gif" width="1" height="3" alt="" /><br />
<img src="img/button/put_right.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_all']?>" alt="" onclick="moveAllOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa);" /><br />
<img src="img/leer.gif" alt="" width="1" height="6" /><br />
<img src="img/button/list_pos_up.gif" alt="" width="15" height="15" border="0" onclick="moveOptionUp(document.editsitestructure.acat_cp);" /><br />
<img src="img/leer.gif" width="1" height="3" alt="" /><br />
<img src="img/button/list_pos_down.gif" alt="" width="15" height="15" border="0" onclick="moveOptionDown(document.editsitestructure.acat_cp);" /></td>
<td><select name="acat_cpa" size="9" multiple="multiple" id="acat_cpa" style="width: 255px" class="f10" ondblclick="moveSelectedOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp,false);">

<?php
//Menü mit Content Typen erstellen
foreach($wcs_content_type as $key => $value) {
	//echo getContentPartOptionTag($key, $value);
	echo '<option value="'.$key.'">'.$value."</option>\n";
}
foreach($BL['be_admin_optgroup_label'] as $key => $value) {
	echo '<option value="-'.$key.'">[optgroup] '.$value."</option>\n";
}
?>

						</select></td>
              </tr>
            </table></td>
          </tr>
	  
		  
          <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
          </tr>
          <tr><td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr><td colspan="4" class="v09"><?php echo $BL['be_admin_struct_status'] ?>:</td></tr>
			<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
            <tr>
              <td bgcolor="#D9DEE3"><input name="acat_hidden" type="checkbox" id="acat_hidden" value="1" <?php is_checked($acat_hidden, 1); ?> /></td>
              <td bgcolor="#D9DEE3">&nbsp;<label for="acat_hidden"><?php echo $BL['be_admin_struct_hide1'] ?></label>&nbsp;&nbsp;</td>
			  
              <td bgcolor="#D9DEE3"><input name="acat_hiddenactive" type="checkbox" id="acat_hiddenactive" value="1" <?php is_checked($acat_hiddenactive, 1); ?> /></td>
              <td bgcolor="#D9DEE3">&nbsp;<label for="acat_hiddenactive"><?php echo $BL['be_admin_struct_acat_hiddenactive'] ?></label>&nbsp;&nbsp;</td>
			  
			  <td>&nbsp;&nbsp;</td>
			  
			  <td bgcolor="#D9DEE3"><input name="acat_regonly" type="checkbox" id="acat_regonly" value="1" <?php is_checked($acat_regonly, 1); ?> /></td>
              <td bgcolor="#D9DEE3" colspan="3">&nbsp;<label for="acat_regonly"><?php echo $BL['be_admin_struct_regonly'] ?></label>&nbsp;&nbsp;</td>
			</tr>
			
          </table></td></tr>		  
		  
		 <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		 </tr>
		 
		 <!-- 

		  <tr><td class="v09"><?php echo  $BL['be_admin_struct_permit'] ?>:</td></tr>
		  <tr><td><img src="img/leer.gif" width="1" height="2"></td></tr>
          <tr>
            <td valign="top">
<?php

// list all available frontend users and put into temp array
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_aktiv != 9 ORDER BY usr_fe, usr_name, usr_login";
if($result = mysql_query($sql, $db) or die("error while listing frontend users")) {
	$_temp_usr = array();
	while($row = mysql_fetch_assoc($result)) {
		$_temp_usr[$row['usr_id']]['name']   = html_specialchars($row['usr_name']);
		$_temp_usr[$row['usr_id']]['login']  = html_specialchars($row['usr_login']);
		$_temp_usr[$row['usr_id']]['fe']     = $row['usr_fe'];
		$_temp_usr[$row['usr_id']]['active'] = $row['usr_aktiv'];
	}
	mysql_free_result($result);
}

?>
			<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><select name="acat_access[]" id="acat_access" size="7" multiple style="width: 255px" class="f10" onDblClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);">
<?php
// list all fe_users
foreach($_temp_usr as $key => $value) {
	if(in_array($key, $acat_permit)) {
		echo '<option value="'.$key.'"';
		if(!$_temp_usr[$key]['active']) {
			echo ' style="color:#999999;"';
		}
		echo '>'.trim($_temp_usr[$key]['name']. ' ('.$_temp_usr[$key]['login'].')')."</option>\n";
		unset($_temp_usr[$key]);
	}
}
?>
		</select></td>
                <td valign="top" style="padding-left:5px;padding-right:5px;"><img src="img/button/put_left.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access);selectAllOptions(document.editsitestructure.acat_access);"><br><img src="img/leer.gif" width="1" height="3"><br><img src="img/button/put_left_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"><br><img src="img/leer.gif" width="1" height="6"><br><img src="img/button/put_right_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);"><br><img src="img/leer.gif" width="1" height="3"><br><img src="img/button/put_right.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers);"></td>
                <td><select name="acat_feusers" size="7" multiple id="acat_feusers" style="width: 255px" class="f10" onDblClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);">
<?php
// list all available fe_users
if(isset($_temp_usr) && is_array($_temp_usr)) {
	foreach($_temp_usr as $key => $value) {
		echo '<option value="'.$key.'"';
		if(!$_temp_usr[$key]['active']) {
			echo ' style="color:#999999;"';
		}
		echo '>'.trim($_temp_usr[$key]['name']. ' ('.$_temp_usr[$key]['login'].')')."</option>\n";
	}
}
?>
		</select></td>
              </tr>
            </table></td>
          </tr>
		  
		 <tr><td><img src="img/leer.gif" width="1" height="10"></td></tr>
		 
		 //-->

          <tr>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
			
				<tr>
				  <td class="v09 inactive" colspan="5"><?php echo  $BL['be_cache'] ?>:</td>
				  <td class="v09" colspan="2"><?php echo  $BL['be_ctype_search'] ?>:</td>
				</tr>
		 		<tr><td colspan="7"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
		 		</tr>
			
			
                <tr bgcolor="#D9DEE3">
                  <td class="inactive"><input name="acat_cacheoff" type="checkbox" id="acat_cacheoff" value="1"<?php if($acat_timeout === '0') echo "checked"; ?> /></td>
				  <td class="inactive">&nbsp;<label for="acat_cacheoff"><?php echo $BL['be_off'] ?></label>&nbsp;</td>
				  <td class="inactive"><select name="acat_timeout" class="f11" style="margin:2px;width:85px;" onchange="document.editsitestructure.acat_cacheoff.checked=false;">
<?php
echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
echo '<option value="60"'.is_selected($acat_timeout, '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
echo '<option value="300"'.is_selected($acat_timeout, '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="900"'.is_selected($acat_timeout, '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="1800"'.is_selected($acat_timeout, '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="3600"'.is_selected($acat_timeout, '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
echo '<option value="14400"'.is_selected($acat_timeout, '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
echo '<option value="43200"'.is_selected($acat_timeout, '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
echo '<option value="86400"'.is_selected($acat_timeout, '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
echo '<option value="172800"'.is_selected($acat_timeout, '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
echo '<option value="604800"'.is_selected($acat_timeout, '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
echo '<option value="1209600"'.is_selected($acat_timeout, '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
echo '<option value="2592000"'.is_selected($acat_timeout, '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
?>
				  </select></td>
				  <td class="inactive">&nbsp;<?php echo $BL['be_cache_timeout'] ?>&nbsp;&nbsp;</td>
				  <td bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
				  <td><input name="acat_nosearch" type="checkbox" id="acat_nosearch" value="1" <?php if($acat_nosearch === '1') echo "checked"; ?> /></td>
				  <td>&nbsp;<label for="acat_nosearch"><?php echo $BL['be_off'] ?></label>&nbsp;&nbsp;</td>
                </tr>
              </table></td>
          </tr>
		  
		  <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		  </tr>
		  
          <tr>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
			
				<tr>
				  <td class="v09" colspan="8"><?php echo  $BL['be_ftptakeover_status'] ?>:</td>
			    </tr>
		 		<tr><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
		 		</tr>
			
			
                <tr bgcolor="#D9DEE3">
                  <td><input name="acat_aktiv" type="checkbox" id="acat_aktiv" value="1" <?php if($acat_aktiv == 1) echo "checked"; ?> /></td>
                  <td>&nbsp;<label for="acat_aktiv"><?php echo $BL['be_admin_struct_visible'] ?></label>&nbsp;&nbsp;</td>
                  <td><input name="acat_public" type="checkbox" id="acat_public" value="1" <?php if($acat_public == 1) echo "checked"; ?> /></td>
                  <td>&nbsp;<label for="acat_public"><?php echo $BL['be_ftptakeover_public'] ?></label>&nbsp;&nbsp;</td>
				  <td><input name="acat_ssl" type="checkbox" id="acat_ssl" value="1"<?php 
				  	if(intval($phpwcms["site_ssl_mode"])) {
						if($acat_ssl == 1) { 
							echo " checked"; 
							$ssl_style='';
						}
					} else { 
						echo " disabled";
						$ssl_style=' style="color:#ADB2BE;"'; 
					}
					
					?> /></td>
                  <td<?php echo $ssl_style; ?>>&nbsp;
                  	<label for="acat_ssl">SSL</label>&nbsp;&nbsp;</td>
				  
			     <td><input name="acat_nositemap" type="checkbox" id="acat_nositemap" value="1"<?php is_checked(1, $acat_nositemap); ?> /></td>
				<td>&nbsp;<label for="acat_nositemap"><?php echo  $BL['be_ctype_sitemap'] ?></label>&nbsp;&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="20" />
		  		<input name="acat_sort" type="hidden" id="acat_sort" value="<?php echo $acat_sort; ?>" />
				<input name="acat_struct" type="hidden" id="acat_struct" value="<?php echo $acat_struct; ?>" />
				<input name="acat_new" type="hidden" id="acat_new" value="<?php echo $acat_new; ?>" />
				<input name="acat_id" type="hidden" id="acat_id" value="<?php echo $acat_id; ?>" /></td>
          </tr>
		  <tr><td><input name="submit" type="submit" class="button10" value="<?php echo empty($acat_id) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?>" />
		  		<input name="SubmitClose" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
		  &nbsp;&nbsp;&nbsp;&nbsp;
		  <input name="donotsubmit" type="button" class="button10" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=6';" /></td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="15" /></td>
          </tr>
		  <tr><td><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		  </tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="15" /></td>
          </tr>
</table>
</form>
<?php
/*
<script language="javascript" type="text/javascript">
<!-- 
function noDESC() {
	if(document.editsitestructure.acat_order0.checked) {
		document.editsitestructure.acat_ordersort0.checked = true;
		document.editsitestructure.acat_ordersort1.checked = false;
	}
}
noDESC();
//-->
</script>
*/
?>