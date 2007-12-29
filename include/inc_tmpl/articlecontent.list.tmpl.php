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


$buttonAction  = '<table cellpadding="0" cellspacing="0" border="0"><tr>'."\n";
// Article List
$buttonAction .= '<td><input type="button" value="'.$BL['be_article_cnt_center'];
$buttonAction .= '" class="button10" title="'.$BL['be_article_cnt_center'].'" onclick="';
$buttonAction .= "location.href='phpwcms.php?do=articles';return false;\"></td>\n<td>&nbsp;</td>\n";
// Article Preview (new window)
$buttonActionLink = 'index.php?id='.$article["article_catid"].','.$article["article_id"].',0,0,1,0';
$buttonAction .= '<td>';
$buttonAction .= '<input type="button" value="'.$BL['be_func_struct_preview'];
$buttonAction .= '" class="button10" title="'.$BL['be_func_struct_preview'].'" onclick="';
$buttonAction .= "window.open('".$buttonActionLink."', 'articlePreviewWindows');return false;\"></td>\n";
$buttonAction .= '</tr></table>';


?>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $article["article_id"] ?>" method="post" name="addcontent" id="addcontent">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="3" class="title"><?php echo $BL['be_article_cnt_ltitle'] ?></td></tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
	</tr>
	<tr bgcolor="#92A1AF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	</tr>
	<tr bgcolor="#F3F5F8"><td><img src="img/leer.gif" alt="" width="23" height="4" /></td>
		<td><img src="img/leer.gif" alt="" width="453" height="1" /></td>
		<td><img src="img/leer.gif" alt="" width="62" height="1" /></td>
	</tr>
	<tr bgcolor="#F3F5F8">
		<td width="23" align="right"><img src="img/symbole/article_text.gif" alt="" width="9" height="11" border="0" /><img src="img/leer.gif" alt="" width="1" height="1" /><img src="img/leer.gif" alt="" width="4" height="1" /></td>
		<td width="453" class="dir"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>"><strong><?php echo html_specialchars($article["article_title"]) ?></strong></a></td>
		<td width="62" align="right" class="h13" style="padding-right:1px"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>"><img src="img/button/edit_22x13.gif" alt="<?php echo $BL['be_article_cnt_ledit'] ?>" width="22" height="13" border="0" /></a><a href="include/inc_act/act_articlecontent.php?do=<?php echo "3,".$article["article_id"].",0,".switch_on_off($article["article_aktiv"]) ?>" title="<?php echo $BL['be_article_cnt_lvisible'] ?>"><img src="img/button/visible_12x13_<?php echo $article["article_aktiv"] ?>.gif" alt="" width="12" height="13" border="0" /></a><a href="include/inc_act/act_articlecontent.php?do=<?php echo "1,".$article["article_id"]; ?>" title="<?php echo $BL['be_article_cnt_ldel'] ?>" onclick="return confirm('<?php echo $BL['be_article_cnt_ldeljs'].'\n'.html_specialchars($article["article_title"]); ?>  \n ');"><img src="img/button/trash_13x13_1.gif" alt="" width="13" height="13" border="0" /></a></td>
	</tr>
	<tr bgcolor="#F3F5F8"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
		<tr bgcolor="#F3F5F8">
		  <td><img src="img/leer.gif" alt="" width="23" height="1" /></td>
		  <td><table border="0" cellpadding="0" cellspacing="0" summary="" class="tdMorepace">
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
			</tr>
			<?php	if($article["article_subtitle"]) { ?>
			<tr>
			  <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
			  <td valign="top" class="v10"><strong><?php echo html_specialchars($article["article_subtitle"]); ?></strong></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
			<?php } ?>
			
			<?php if(!empty($article["article_summary"])) { ?>
			<tr>
			  <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_asummary'] ?>:&nbsp;</td>
			  <td valign="top" class="v10"><?php echo html_specialchars(getCleanSubString(strip_tags($article["article_summary"]), 250, '&#8230;')); ?></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
			<?php 	} ?>			

			<tr>
			  <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_cat'] ?>:&nbsp;</td>
			  <td valign="top" class="v10"><?php echo html_specialchars($article["article_cat"]) ?></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
			<tr>
			  <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_akeywords'] ?>:&nbsp;</td>
			  <td valign="top" class="v10"><?php if($article["article_keyword"]) {echo html_specialchars($article["article_keyword"]);}else{echo "not defined/completed";} ?></td>
			</tr>
			<?php 

			if($article["article_redirect"]) {
			?>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
			<tr>
			  <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_cnt_redirect'] ?>:&nbsp;</td>
			  <td valign="top" class="v10"><?php echo html_specialchars($article["article_redirect"]); ?></td>
			</tr>
			<?php
			}


			$thumb_image = false;
			if(!empty($article["image"]["hash"])) {
				$thumb_image = get_cached_image(
					array(	"target_ext"	=>	$article['image']['ext'],
					"image_name"	=>	$article['image']['hash'] . '.' . $article['image']['ext'],
					"thumb_name"	=>	md5($article['image']['hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
					)
				);
			}
			
			$thumb_list_image = false;
			if(!empty($article["image"]["list_hash"])) {
				$thumb_list_image = get_cached_image(
					array(	"target_ext"	=>	$article['image']['list_ext'],
					"image_name"	=>	$article['image']['list_hash'] . '.' . $article['image']['list_ext'],
					"thumb_name"	=>	md5($article['image']['list_hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
					)
				);
			}

			if($thumb_image != false || $thumb_list_image != false) {

			?>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
			<tr>
			  <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
			  <td valign="top" class="v10"><?php 

		if($thumb_image != false) {
		  	echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' alt="" style="margin-right:5px;" />';
		}
		if($thumb_list_image != false) {
		  	echo '<img src="'.PHPWCMS_IMAGES . $thumb_list_image[0] .'" border="0" '.$thumb_list_image[3].' alt=""';
			if(!empty($article['image']['list_usesummary'])) {
				echo ' class="inactive"';
			}
			echo ' />';
		}

			  ?></td>
			</tr>
			<?php 

			}

			?>
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
			<tr>
			  <td class="v10" style="color:#727889"><?php echo $BL['be_article_username']; ?>:&nbsp;</td>
			  <td class="v10"><?php echo $article["article_username"] ?></td>
			</tr>
			<tr>
			  <td class="v10" style="color:#727889"><?php echo $BL['be_article_eslastedit'] ?>:&nbsp;</td>
			  <td class="v10"><?php echo date($BL['be_longdatetime'], strtotime($article["article_date"])) ?>&nbsp;&nbsp;<span style="color:#727889"><?php echo $BL['be_fprivedit_created'] ?>:</span>&nbsp;<?php echo date($BL['be_longdatetime'], $article["article_created"]) ?></td>
			</tr>
			<tr>
			  <td class="v10" style="color:#727889" nowrap="nowrap"><?php echo $BL['be_article_cnt_start'] ?>:&nbsp;</td>
			  <td class="v10"><?php echo date($BL['be_longdatetime'], strtotime($article["article_begin"])) ?>&nbsp;&nbsp;<span style="color:#727889"><?php echo $BL['be_article_cnt_end'] ?>:</span>&nbsp;<?php echo date($BL['be_longdatetime'], strtotime($article["article_end"])) ?></td>
			</tr>
			
			<tr>
			  <td class="v10" style="color:#727889" nowrap="nowrap"><?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
			  <td class="v10"><?php echo $article["article_sort"] ?>&nbsp;&nbsp;<span style="color:#727889"><?php echo $BL['be_priorize'] ?>:</span>&nbsp;<?php echo $article["article_priorize"] ?></td>
			</tr>		
			
			
			
		  </table></td>
			<td>&nbsp;</td>
		</tr>
		<tr bgcolor="#F3F5F8"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
        <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
		<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

		<tr><td colspan="3"><?php echo $buttonAction; ?></td></tr>
			
			<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
			
			<tr bgcolor="#92A1AF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
			<tr bgcolor="#D9DEE3"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
			<tr bgcolor="#D9DEE3"><td colspan="3"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
				  <td><img src="img/leer.gif" alt="" width="7" height="1" /></td>
                  <td><img src="img/symbole/add_content.gif" alt="" width="11" height="9" /><img src="img/leer.gif" alt="" width="5" height="1" /></td>
                  <td><select name="ctype" class="v12" id="ctype" onchange="this.form.submit();">
                    <?php

$temp_count    = 0;

if(is_array($article["article_cntpart"]) && count($article["article_cntpart"])) {

	// list all content parts usable for this article category
	foreach($article["article_cntpart"] as $value) {

		if(isset($wcs_content_type[$value])) {
		
			echo getContentPartOptionTag($value, $wcs_content_type[$value]);
			$temp_count++;

		}
		$value1 = $value * (-1);
		if(isset($BL['be_admin_optgroup_label'][$value1]) && $value) {
			echo '<optgroup label="[ '.$BL['be_admin_optgroup_label'][$value1].' ]" class="cntOptGroup"></optgroup>'."\n";
		}
	}

}
if(!$temp_count) {
	//list all available content parts
	foreach($wcs_content_type as $key => $value) {
		//echo "<option value=\"".$key."\">".$value."</option>";
		echo getContentPartOptionTag($key, $value);
	}
}
					?>
                    </select></td>
                  <td><img src="img/leer.gif" alt="" width="5" height="1" /></td>
				  <td><input type="submit" name="image" value="<?php echo  $BL['be_article_cnt_add'] ?>" class="v12" title="<?php echo  $BL['be_article_cnt_addtitle'] ?>" /></td>
                </tr>
              </table></td></tr>
			<tr bgcolor="#D9DEE3"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			<tr bgcolor="#92A1AF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
			<?php 
			//Auslesen der Content Daten zum Zusammenstellen der Sortier-Informationen

			$sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."phpwcms_articlecontent ";
			$sql .= "WHERE acontent_aid=".$article["article_id"]." ORDER BY acontent_block, ";
			/*if($article["article_paginate"]) {
				$sql .= 'acontent_paginate_page, ';
			}*/
			$sql .= "acontent_sorting, acontent_id";
			if($result = mysql_query($sql, $db) or die("error while listing contents for this article")) {
				$sc = 0; $scc = 0; //Sort-Zwischenzähler
				while($row = mysql_fetch_row($result)) {
					$scc++;
					if($row[2] == 0) {
						$sc++;
						$sbutton[$sc]["id"]    = $row[0];
						$sbutton[$sc]["sort"]  = $row[1];
						$sbutton[$sc]["block"] = $row[3];
					}
				}
			}
			if($sc) {
				//Jetzt aufbauen der Sortieranweisung
				foreach($sbutton as $key => $value) {
					if($key == 1) {
						// if 1st content part in list
						$sbutton[$key]["top"] = '<img src="img/button/sort_top_0.gif" border="0" alt="" />';
						
					} elseif(isset($sbutton[$key-1]["block"]) && $sbutton[$key-1]["block"] != $sbutton[$key]["block"]) {
						// if this content part is selected for different block than previous
						$sbutton[$key]["top"] = '<img src="img/button/sort_top_0.gif" border="0" alt="" />';
						
					} else {
						$sbutton[$key]["top"] = "<a href=\"include/inc_act/act_articlecontent.php?sort=".
						$sbutton[$key]["id"].":".$sbutton[$key-1]["sort"]."|".
						$sbutton[$key-1]["id"].":".$sbutton[$key]["sort"].
						"\" title=\"".$BL['be_article_cnt_up']."\"><img src=\"img/button/sort_top_1.gif\" border=\"0\" alt=\"\" /></a>";
					}
					if($key == $sc) {
						// if this is the last content part in list
						$sbutton[$key]["bottom"] = "<img src=\"img/button/sort_bottom_0.gif\" border=\"0\" alt=\"\" />";
					
					} elseif(isset($sbutton[$key+1]["block"]) && $sbutton[$key+1]["block"] != $sbutton[$key]["block"]) {
						// if this is the last content part in current block and next is different
						$sbutton[$key]["bottom"] = "<img src=\"img/button/sort_bottom_0.gif\" border=\"0\" alt=\"\" />";
					
					} else {
						$sbutton[$key]["bottom"] = "<a href=\"include/inc_act/act_articlecontent.php?sort=".
						$sbutton[$key]["id"].":".$sbutton[$key+1]["sort"]."|".
						$sbutton[$key+1]["id"].":".$sbutton[$key]["sort"].
						"\" title=\"".$BL['be_article_cnt_down']."\"><img src=\"img/button/sort_bottom_1.gif\" border=\"0\" alt=\"\" /></a>";
					}
					$sbutton_string[$sbutton[$key]["id"]] = $sbutton[$key]["top"].
					"<img src=\"img/leer.gif\" width=\"1\" height=\"1\" alt=\"\" />".
					$sbutton[$key]["bottom"];
				}
				unset($sbutton);
			}

			//Listing zugehöriger Artikel Content Teile
			$sql = 	"SELECT *, UNIX_TIMESTAMP(acontent_tstamp) as acontent_date FROM ".DB_PREPEND."phpwcms_articlecontent ".
					"WHERE acontent_aid=".$article["article_id"]." AND acontent_trash=0 ".
					"ORDER BY acontent_block, acontent_sorting, acontent_id;";

			if($result = mysql_query($sql, $db) or die("error while listing contents for this article")) {
				$sortierwert=1;
				$contentpart_block = ' ';
				$contentpart_block_name = '';
				while($row = mysql_fetch_assoc($result)) {
				
					// if type of content part not enabled available 
					if(!isset($wcs_content_type[ $row["acontent_type"] ]) || ($row["acontent_type"] == 30 && !isset($phpwcms['modules'][$row["acontent_module"]]))) {
						continue;
					}
				
					// now show current block name
					if($contentpart_block != $row['acontent_block']) {
						$contentpart_block = $row['acontent_block'];
						$contentpart_block_name = html_specialchars(' {'.$row['acontent_block'].'}');
						$contentpart_block_color = ' bgcolor="#E0D6EB"';
						
						switch($contentpart_block) {
							case ''			:	$contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
												$contentpart_block_color = ' bgcolor="#F5CCCC"';
												break;
							case 'CONTENT'	:	$contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
												if($article['article_paginate']) {
													$contentpart_block_name .= ' / <img src="img/symbole/content_cppaginate.gif" alt="" style="margin-right:2px;" />';
													$contentpart_block_name .= $BL['be_cnt_pagination'];
												}
												$contentpart_block_color = ' bgcolor="#F5CCCC"';
												break;
							case 'LEFT'		:	$contentpart_block_name = $BL['be_cnt_left'].$contentpart_block_name;
												$contentpart_block_color = ' bgcolor="#E0EBD6"';
												break;
							case 'RIGHT'	:	$contentpart_block_name = $BL['be_cnt_right'].$contentpart_block_name;
												$contentpart_block_color = ' bgcolor="#FFF5CC"';
												break;
							case 'HEADER'	:	$contentpart_block_name = $BL['be_admin_page_header'].$contentpart_block_name;
												$contentpart_block_color = ' bgcolor="#EBEBD6"';
												break;
							case 'FOOTER'	:	$contentpart_block_name = $BL['be_admin_page_footer'].$contentpart_block_name;
												$contentpart_block_color = ' bgcolor="#E1E8F7"';
												break;
						}
				
			?>
			<tr<?php echo $contentpart_block_color ?>>
				<td align="right" style="padding-right:5px;"><img src="img/symbole/block.gif" alt="" width="9" height="11" border="0" /></td>
				<td style="font-size:9px;"><?php echo  $contentpart_block_name ?></td>
				<td><img src="img/leer.gif" alt="" width="1" height="15" /></td>
			</tr>
			<tr><td colspan="3" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
			<?php
					}
			
			
			?>
			<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			<tr>
			  <td align="right" style="padding-right:5px;"><img src="img/symbole/content_9x11.gif" alt="" width="9" height="11" border="0" /></td>
	          <td><table border="0" cellpadding="0" cellspacing="0" summary="" width="100%">
	            <tr>
	              <td width="180" style="font-size:9px;font-weight:bold;text-transform:uppercase;"><?php 				  
				  
				$cntpart_title = $wcs_content_type[$row["acontent_type"]];
				if(!empty($row["acontent_module"])) {
				
					$cntpart_title .= ': '.$BL['modules'][$row["acontent_module"]]['listing_title'];
				
				}
				echo $cntpart_title;
				  
				  
				  ?></td>
				  <td width="23" nowrap="nowrap"><?php echo $sbutton_string[$row["acontent_id"]]; ?></td>
				  <td class="v09" style="color:#727889;padding:0 4px 0 5px" width="60" nowrap="nowrap">[ID:<?php echo $row["acontent_id"] ?>]</td>
	              <td class="v09" nowrap="nowrap"><?php 
				  
				  echo date($BL['be_shortdatetime'], $row["acontent_date"]).'&nbsp;';
				  
				  //Display cp paginate page number
				  if($article["article_paginate"]) {

					echo '<img src="img/symbole/content_cppaginate.gif" alt="subsection" title="subsection" />';
					echo $row["acontent_paginate_page"] == 0 ? 1 : $row["acontent_paginate_page"];
				  }
				  
				  				  
				  //Anzeigen der Space Before/After Info
				  if(intval($row["acontent_before"])) {
				  	//echo "<td><img src=\"img/symbole/content_space_before.gif\" width=\"12\" height=\"6\"></td>";
				  	//echo "<td class=\"v09\">".$row["acontent_before"]."</td>";
					echo '<img src="img/symbole/content_space_before.gif" alt="" />'.$row["acontent_before"];
				  }
				  if(intval($row["acontent_after"])) {
				  	//echo "<td><img src=\"img/symbole/content_space_after.gif\" width=\"12\" height=\"6\"></td>";
				  	//echo "<td class=\"v09\">".$row["acontent_after"]."</td>";
					echo '<img src="img/symbole/content_space_after.gif" alt="" />'.$row["acontent_after"];
				  }
				  if($row["acontent_top"]) {
				  	echo '<img src="img/symbole/content_top.gif" alt="TOP" title="TOP" />';
				  }
		 		 if($row["acontent_anchor"]) {
				  	echo '<img src="img/symbole/content_anchor.gif" alt="Anchor" title="Anchor" />';
				  }
				  ?></td>
                </tr>
              </table></td>
	          <td align="right" style="padding-right:1px;"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php 
	          echo $article["article_id"]."&amp;acid=".$row["acontent_id"];
	          ?>" title="<?php echo $BL['be_article_cnt_edit'] ?>"><img src="img/button/edit_22x13.gif" alt="" border="0" /></a><?php
				// duplicate content part
			  	echo '<a href="include/inc_act/act_structure.php?do=8%7C'.$row["acontent_id"].'%7C'.$article["article_id"].'%7C'.($row["acontent_sorting"]+5).'" ';
				echo 'title="'.$BL['be_func_content_copy'].' [ID:'.$row["acontent_id"].']" ';
				echo 'onclick="return confirm(\''.js_singlequote($BL['be_func_content_copy']).': \n'.js_singlequote($cntpart_title.' [ID:'.$row["acontent_id"].']').'\');">';
				echo '<img src="img/button/copy_13x13.gif" border="0" alt="" width="13" height="13" /></a>';
			  
			  ?><a href="include/inc_act/act_articlecontent.php?do=<?php
	          echo "2,".$article["article_id"].",".$row["acontent_id"].",".switch_on_off($row["acontent_visible"])
	          ?>" title="<?php
	          echo $BL['be_article_cnt_lvisible']
	          ?>"><img src="img/button/visible_12x13_<?php
	          echo $row["acontent_visible"]
	          ?>.gif" alt="" width="12" height="13" border="0" /></a><a href="include/inc_act/act_articlecontent.php?do=<?php
	          echo "9,".$article["article_id"].",".$row["acontent_id"]
	          ?>" title="<?php echo $BL['be_article_cnt_delpart'] ?>" onclick="return confirm('<?php
	          echo $BL['be_article_cnt_delpartjs'] ?> \n[ID: <?php echo $row["acontent_id"]
			  ?>]\n ');"><img src="img/button/trash_13x13_1.gif" alt="" width="13" height="13" border="0" /></a></td>
			</tr>
<?php

	// list content type overview
	$cinfo = NULL;
	
	//$row["acontent_type"] = intval($row["acontent_type"]); -> it is always INT because coming from db INT field
	
	// check default content parts (system internals
	if($row['acontent_type'] != 30 && file_exists('include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php')) {
	
		include(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php');
	
	} elseif($row['acontent_type'] == 30 && file_exists($phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php')) {
	
		// custom module
		include($phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php');
	
	} else {
	
		// default fallback
		include(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.list.inc.php');
	
	}
	// end list

?>
			<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
			<tr><td colspan="3" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php
	}
} //Ende Listing Artikel Content Teile
?>
			<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
			
</table>
<input name="csorting" type="hidden" id="csorting" value="<?php echo ($scc*10); ?>" />
</form>
<?php echo $buttonAction; ?>