<?php
/*************************************************************************************
   Copyright notice

   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.

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

//26-04-2004 Oliver Georgi -> $level seems to be unused
//19-11-2004 Fernando Batista -> Copy article, Copy strutures :http://fernandobatista.web.pt
//31-03-2005 Fernando Batista -> copy&cut Article Content :http://fernandobatista.web.pt

function struct_list ($id, $dbcon, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode=1, $forbid_cut=0, $forbid_copy=0, $counter=0) {

	$counter++;
	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".intval($id)." ORDER BY acat_sort";
	if($result = mysql_query($sql, $dbcon) or die("error while browsing structure".$sql)) {
		$count_row = 0;
		while($row = mysql_fetch_assoc($result)) {
			$struct[$count_row] = $row;
			$count_row++;
		}
		mysql_free_result($result);

		if(isset($struct[0])) {
			foreach($struct as $key => $value) {
				struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row, $dbcon);
			}
		}
	}
}

function struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row, $dbcon) {

	global $BL;

	$page_val		= ($listmode) ? "do=admin&amp;p=6" : "do=articles";
	$child_count	= get_root_childcount($struct[$key]["acat_id"], $dbcon);
	$child_sort		= (($child_count+1)*10);
	
	$forbid_cut		= ($struct[$key]["acat_struct"] == $cut_id || $forbid_cut) ? 1 : 0;
	$forbid_copy	= ($struct[$key]["acat_struct"] == $copy_id || $forbid_copy) ? 1 : 0;
	
	$an = html_specialchars($struct[$key]["acat_name"]);
	$a  = "<tr onmouseover=\"this.bgColor='#CCFF00';\" onmouseout=\"this.bgColor='#FFFFFF';\">\n";
	$a .= "<td width=\"428\">";
	$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
	$a .= '<td nowrap="nowrap"><img src="img/leer.gif" width="'.(14+(14*($counter-1)))."\" height=\"1\" alt=\"\" />"; //14
	$a .= ($child_count) ? "<a href=\"phpwcms.php?".$page_val."&amp;open=".rawurlencode($struct[$key]["acat_id"].":".((!empty($_SESSION["structure"][$struct[$key]["acat_id"]]))?0:1))."\">" : "";
	$a .= "<img src=\"img/symbole/plus_".(($child_count) ? ((!empty($_SESSION["structure"][ $struct[$key]["acat_id"] ])) ? "close" : "open") : "empty");
	$a .= ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" />".(($child_count) ? "</a>" : "");
	$a .= "<img src=\"img/symbole/page_".(!$struct[$key]["acat_hidden"]?1:7);
	if($struct[$key]["acat_regonly"]) {
		$a .= '_locked';
	}
	$a .= ".gif\" width=\"11\" height=\"15\" ";

	$info  = 'ID: <b>'.$struct[$key]["acat_id"].'</b><br />';
	$info .= 'ALIAS: '.html_specialchars($struct[$key]["acat_alias"]).'<br />';
	$info .= $BL['be_cnt_sortvalue'].': '.$struct[$key]["acat_sort"];
	
	$a .= 'onmouseover="Tip(\''.$info.'\');" onmouseout="UnTip()" alt=""';	
	
	$a .= "></td>\n";
	$a .= "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>\n";
	$a .= "<td class=\"dir\"><strong>".$an."</strong></td>\n</tr>\n</table></td>\n<td width=\"110\" nowrap=\"nowrap\">";
	
	$a .= listmode_edits ($listmode, $struct, $key, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, $forbid_cut, $forbid_copy, $count_row, $child_sort);
	
	$a .= "</td>\n</tr>\n";
	echo $a;
	
	if(isset($_SESSION["structure"][$struct[$key]["acat_id"]]) && $_SESSION["structure"][$struct[$key]["acat_id"]]) {
	
		if(!$listmode) {
			struct_articlelist ($struct[$key]["acat_id"], $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $struct[$key]["acat_order"]);
		}
		
		struct_list ($struct[$key]["acat_id"], $dbcon, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode, $forbid_cut, $forbid_copy, $counter);
	
	}

}

function get_root_childcount($id, $dbcon) {
//Ermittelt Anzahl bereits vorhandener aktiver Unterlevels
	$p1_count = $p2_count = 0;
	$id = intval($id);
	if($p_result = mysql_query("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$id, $dbcon)) {
		if($p_row = mysql_fetch_row($p_result)) $p1_count = $p_row[0];
		mysql_free_result($p_result);
	}
	if($p_result = mysql_query("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_cid=".$id, $dbcon)) {
		if($p_row = mysql_fetch_row($p_result)) $p2_count = $p_row[0];
		mysql_free_result($p_result);
	}
	return $p1_count + $p2_count;
}

function get_article_content_count($id, $dbcon) {
        $p_count = 0;
        $id = intval($id);
        if($p_result = mysql_query("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_aid=".$id, $dbcon)) {
                if($p_row = mysql_fetch_row($p_result)) $p_count = $p_row[0];
                mysql_free_result($p_result);
        }
        return $p_count;
}

function struct_articlelist ($struct_id, $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $article_order=0) {

	global $BL;

	$article			= array();	// empty article array
	$sort_array			= array();	// empty array to store all sort values for the category
	$article_order		= intval($article_order);
	$max_article_count	= 0;
	$show_sort			= (!$article_order || $article_order == 1) ? 1 : 0;
	$ao 				= get_order_sort($article_order);
	$count_article		= 0;
	$sbutton_string		= array();
	
	$sql  = "SELECT *, ";
	$sql .= "DATE_FORMAT(article_tstamp, '%Y-%m-%d %H:%i:%s') AS article_date "; //, article_deleted
	$sql .= "FROM ".DB_PREPEND."phpwcms_article ";
	$sql .= "WHERE article_cid='".$struct_id."' AND article_deleted=0 ORDER BY ".$ao[2];
	
	if($result = mysql_query($sql, $GLOBALS['db']) or die ("error while browsing related articles")) {
	
		// 1st get max count to know the last index ($max_article_count - 1)
		$max_article_count = mysql_num_rows($result);
	
		// take all entryies and build new array with it
		while($row = mysql_fetch_assoc($result)) {

			$article[$count_article]	= $row;
			if($row['article_sort'] > 0) {
				$sort_array[$count_article]	= $row['article_sort'];
			}

			// count up for article array index
			$count_article++;

		}
		mysql_free_result($result);
	}
	
	// now check if all sort values are unique
	// if not do a re-sort for all articles
	
	if($max_article_count > count(array_unique($sort_array)) ) {
		$article = getArticleReSorted($struct_id, $article_order);
	}
	
	// reset article counter
	$count_article = 0;
	
	/*
	 * now we know ALL articles and can run array index +/-
	 * to set correct sorting UP and DOWN based on article
	 * listing -> so the correct sort value is used
	 */
	foreach($article as $akey => $avalue) {
	
		// set up correct article sorting
		
		// can be sorted UP
		if($count_article == 0) {
			// this is the first in list -> so no sorting up possible
			// the sort up icon will be invisible
			$sort_up = false;
		} else {
			// this article has a pre entry
			// so use these by setting (current index - 1)
			$article[$akey]['sort_up'] = $article[$akey-1]['article_sort'];
			$sort_up = $show_sort ? true : false;
		}
		
		// count up for article array index
		$count_article++;
		
		// can be sorted DOWN
		if($count_article == $max_article_count) {
			// this is the last in list -> so no sorting down possible
			// the sort up icon will be invisible
			$sort_down = false;
		} else {
			// this article has a follower
			// so use these by setting (current index + 1)
			$article[$akey]['sort_down'] = $article[$akey+1]['article_sort'];
			$sort_down = $show_sort ? true : false;
		}
		
		// now do some checks to be sure that all sort values
		// are different to have it sorting in the correct way
		
		// sort up
		if($sort_up) {
			
			$sort_up  = '<a href="include/inc_act/act_structure.php?do=4%7C';
			$sort_up .= $article[$akey]["article_id"] . '%7C' . $article[$akey]['sort_up'] . '%7C';
			$sort_up .= $article[$akey-1]["article_id"] . '%7C' . $article[$akey]['article_sort'];
			$sort_up .= '" title="'.$BL['be_func_struct_sort_up'].'">';
			$sort_up .= '<img src="img/button/sort_1_1.gif" width="11" height="11" border="0" alt="" /></a>';
		
		} else {
		
			$sort_up  = '<img src="img/button/sort_1_0.gif" width="11" height="11" border="0" alt="" />';
		
		}
		// sort down
		if($sort_down) {
			
			$sort_down  = '<a href="include/inc_act/act_structure.php?do=4%7C';
			$sort_down .= $article[$akey]["article_id"] . '%7C' . $article[$akey]['sort_down'] . '%7C';
			$sort_down .= $article[$akey+1]["article_id"] . '%7C' . $article[$akey]['article_sort'];
			$sort_down .= '" title="'.$BL['be_func_struct_sort_down'].'">';
			$sort_down .= '<img src="img/button/sort_2_1.gif" width="11" height="11" border="0" alt="" /></a>';
		
		} else {
		
			$sort_down  = '<img src="img/button/sort_2_0.gif" width="11" height="11" border="0" alt="" />';
		
		}
		
		$at = html_specialchars($article[$akey]["article_title"]); 
								
		if($cut_article == $article[$akey]["article_id"] ) {
			$a = "<tr bgColor='#B4E101'>\n";
		} elseif($copy_article == $article[$akey]["article_id"]){  
			$a = "<tr bgColor='#B4E101'>\n";  
		} else {
			$a = "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
		}	

		$a .= "<td width=\"428\">";
		$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";

		$acontent_count =  get_article_content_count($article[$akey]["article_id"], $GLOBALS['db']);

		if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
			$a .= "<td nowrap=\"nowrap\"><img src=\"img/leer.gif\" width=\"".(14+14+(14*($counter-1)))."\" height=\"1\" alt=\"\" />";
			$a .= ($acontent_count) ? "<a href=\"phpwcms.php?do=articles&amp;opena=".rawurlencode($article[$akey]["article_id"].":".((!empty($_SESSION["structure"]["article"][$article[$akey]["article_id"]]))?0:1))."\">" : "";
			$a .= "<img src=\"img/symbole/plus_".(($acontent_count) ? ((!empty($_SESSION["structure"]["article"][ $article[$akey]["article_id"] ])) ? "close" : "open") : "empty");
			$a .= ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" />".(($acontent_count) ? "</a>" : "");
		}else{
			$a .= "<td nowrap=\"nowrap\"><img src=\"img/leer.gif\" width=\"".(14+29+(14*($counter-1)))."\" height=\"1\" alt=\"\" />";
		}

		$a .= "<img src=\"img/symbole/text_1.gif\" width=\"11\" height=\"15\" ";
		
		$info  = '<table cellspacing=0 cellpadding=1 border=0>';
		$info .= '<tr><td>'.$BL['be_func_struct_articleID'].':</td><td><b>'.$article[$akey]["article_id"].'</b></td></tr>';
		if(!empty($article[$akey]["article_alias"])) {
			$info .= '<tr><td>ALIAS:</td><td><b>'.$article[$akey]["article_alias"].'</b></td></tr>';
		}
		if(!empty($article[$akey]["article_begin"])) {
			$info .= '<tr><td>'.$BL['be_article_cnt_start'].':</td><td><b>'.phpwcms_strtotime($article[$akey]["article_begin"], $BL['be_longdatetime'], '&nbsp;').'</b></td></tr>';
		}
		if(!empty($article[$akey]["article_end"])) {
			$info .= '<tr><td>'.$BL['be_article_cnt_end'].':</td><td><b>'.phpwcms_strtotime($article[$akey]["article_end"], $BL['be_longdatetime'], '&nbsp;').'</b></td></tr>';
		}
		$info .= '<tr><td>'.$BL['be_cnt_sortvalue'].':</td><td>'.$article[$akey]["article_sort"].'</td></tr>';
		if(isset($article[$akey]["article_end"])) {
			$info .= '<tr><td>'.$BL['be_priorize'].':</td><td>'.$article[$akey]["article_priorize"].'</td></tr>';
		}
		$info .= '</table>';
		
		$a .= 'onmouseover="Tip(\''. $info .'\');" onmouseout="UnTip()" alt=""';
		//$a .= getAltTitle($info);
		$a .= " /></td>\n";
		$a .= "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>\n";
		$a .= "<td class=\"dir\"><a href=\"index.php?aid=".$article[$akey]["article_id"].'"';
		$a .= "target=\"_blank\" title=\"".$BL['be_func_struct_preview'].": ".$at."\">";
		$a .= $at."</a></td>\n</tr>\n</table></td>\n<td width=\"110\" nowrap=\"nowrap\">";

		if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
			$a .= "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id=".$article[$akey]["article_id"];
			$a .= "\" title=\"".$BL['be_func_struct_edit']." \n[".$at."]\">";
			$a .= "<img src=\"img/button/edit_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\"></a>";
		} else {
			$a .= "<img src=\"img/button/edit_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
		}

		if($cut_article != $article[$akey]["article_id"] && !$cut_article_content) {
			$a .= "<a href=\"phpwcms.php?do=articles&amp;acut=".$article[$akey]["article_id"];
			$a .= "\" title=\"".$BL['be_func_struct_cut']." \n[".$at."]\">";
			$a .= "<img src=\"img/button/cut_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
	   } elseif($cut_article_content) {
			$a .= '<a href="include/inc_act/act_structure.php?do='.rawurlencode('7|'.$cut_article_content.'|'.$article[$akey]["article_id"].'|-10');
			$a .= '" title="'.$BL['be_func_content_paste0'];
			$a .= " [".$at."]\"><img src=\"img/button/cut_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";                    
		} else {
			$a .= "<a href=\"phpwcms.php?do=articles\" title=\"".$BL['be_func_struct_nocut'].'">';
			$a .= "<img src=\"img/button/cut_11x11_3.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		}

		if($copy_article != $article[$akey]["article_id"] && !$copy_article_content) {
			$a .= "<a href=\"phpwcms.php?do=articles&amp;acopy=".$article[$akey]["article_id"];
			$a .= "\" title=\"".$BL['be_func_struct_copy']." \n[".$at."]\">";
			$a .= "<img src=\"img/button/copy_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		} elseif($copy_article_content) {
			$a .= '<a href="include/inc_act/act_structure.php?do='.rawurlencode('8|'.$copy_article_content.'|'.$article[$akey]["article_id"].'|-10');
			$a .= "\" title=\"".$BL['be_func_content_paste0'];
			$a .= " [".$at."]\"><img src=\"img/button/copy_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";                    
		} else {
			$a .= "<a href=\"phpwcms.php?do=articles\" title=\"".$BL['be_func_struct_nocopy'].'">';
			$a .= "<img src=\"img/button/copy_11x11_3.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		}

		//Sortierungslink
		
		//davor sortieren
		$a .= $sort_up;
		//dahinter sortieren
		$a .= $sort_down;

		//active und visible Status wechseln
		$a .= "<a href=\"include/inc_act/act_articlecontent.php?do=3,".$article[$akey]["article_id"].",,".(($article[$akey]["article_aktiv"])?0:1);
		$a .= '" title="'.$BL['be_func_struct_svisible'].'">';
		$a .= "<img src=\"img/button/visible_11x11_".$article[$akey]["article_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		$a .= "<a href=\"include/inc_act/act_articlecontent.php?do=4,".$article[$akey]["article_id"].",,".(($article[$akey]["article_public"])?0:1);
		$a .= '" title="'.$BL['be_func_struct_spublic'].'">';
		$a .= "<img src=\"img/button/public_11x11_".$article[$akey]["article_public"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";

		//Article Löschen
		if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
			$a .= "<a href=\"include/inc_act/act_articlecontent.php?do=1,".$article[$akey]["article_id"];
			$a .= "\" title=\"".$BL['be_func_struct_del_article']." \n[".$at."]\" ";
			$a .= "onclick=\"return confirm('".$BL['be_func_struct_del_jsmsg']." \\n[".js_singlequote($at)."] ');\">";
			$a .= "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		} else {
			$a .= "<img src=\"img/button/del_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
		}
		$a .= "</td>\n</tr>\n";
		echo $a;

		$sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."phpwcms_articlecontent ";
		$sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." ORDER BY acontent_block, acontent_sorting, acontent_id";
		if($result = mysql_query($sql, $GLOBALS['db']) or die("error while listing contents for this article")) {
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
					$sbutton[$key]["top"] = "<img src=\"img/button/sort_1_0.gif\" border=\"0\" alt=\"\" />";
					
				} elseif(isset($sbutton[$key-1]["block"]) && $sbutton[$key-1]["block"] != $sbutton[$key]["block"]) {
					// if this content part is selected for different block than previous
					$sbutton[$key]["top"] = "<img src=\"img/button/sort_1_0.gif\" border=\"0\" alt=\"\" />";
					
				} else {
					$sbutton[$key]["top"] = "<a href=\"include/inc_act/act_articlecontent.php?sort=".
					rawurlencode($sbutton[$key]["id"].":".$sbutton[$key-1]["sort"]."|".
					$sbutton[$key-1]["id"].":".$sbutton[$key]["sort"]).
					"\" title=\"".$BL['be_article_cnt_up']."\"><img src=\"img/button/sort_1_1.gif\" border=\"0\" alt=\"\" /></a>";
	
				}
				if($key == $sc) {
					// if this is the last content part in list
					$sbutton[$key]["bottom"] = "<img src=\"img/button/sort_2_0.gif\" border=\"0\" alt=\"\" />";
				
				} elseif(isset($sbutton[$key+1]["block"]) && $sbutton[$key+1]["block"] != $sbutton[$key]["block"]) {
					// if this is the last content part in current block and next is different
					$sbutton[$key]["bottom"] = "<img src=\"img/button/sort_2_0.gif\" border=\"0\" alt=\"\" />";
						
				} else {
					$sbutton[$key]["bottom"] = "<a href=\"include/inc_act/act_articlecontent.php?sort=".
					rawurlencode($sbutton[$key]["id"].":".$sbutton[$key+1]["sort"]."|".
					$sbutton[$key+1]["id"].":".$sbutton[$key]["sort"]).
					"\" title=\"".$BL['be_article_cnt_down']."\"><img src=\"img/button/sort_2_1.gif\" border=\"0\" alt=\"\" /></a>";
				}
				$sbutton_string[$sbutton[$key]["id"]] = $sbutton[$key]["top"].
				$sbutton[$key]["bottom"];
			}
			unset($sbutton);
		}

		if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
			if(isset($_SESSION["structure"]["article"][$article[$akey]["article_id"]]) && $_SESSION["structure"]["article"][$article[$akey]["article_id"]]) {
				struct_articlecontentlist ($article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string, $GLOBALS['db']);
			}
		}

	}
}


function struct_articlecontentlist(& $article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string, $db){

	$a    = '';
	
	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent ";
	$sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." AND acontent_trash=0 ";
	$sql .= "ORDER BY acontent_block, acontent_sorting, acontent_id";
	
	if($result = mysql_query($sql, $db) or die("error while listing contents for this article")) {
	
		while($article_content = mysql_fetch_assoc($result)) {
		
			// if type of content part not enabled available 
			if(!isset($GLOBALS['wcs_content_type'][ $article_content["acontent_type"] ]) || ($article_content['acontent_type'] == 30 && !isset($GLOBALS['phpwcms']['modules'][$article_content["acontent_module"]]))) {
				continue;
			}
			
			$info  = "Tip('";
			$info .= 'ID: '.$article_content["acontent_id"];
			if($article_content['acontent_title']) {
				$info .= '<br /><nobr>' . $GLOBALS['BL']['be_article_cnt_ctitle'].': '.html_specialchars(js_singlequote($article_content['acontent_title'])).'</nobr>';
			}
			if($article_content['acontent_title']) {
				$info .= '<br /><nobr>' . $GLOBALS['BL']['be_article_asubtitle'].': '.html_specialchars(js_singlequote($article_content['acontent_subtitle'])).'</nobr>';
			}
			if($article_content["acontent_comment"]) {
				$info .= '<br />' . nl2br( html_specialchars(js_singlequote($article_content["acontent_comment"])) );
			}
			$info .= "');";
					
			if($cut_article_content == $article_content["acontent_id"] ) {
				$a .= "<tr bgcolor=\"#FFA801\">\n";
			} elseif($copy_article_content == $article_content["acontent_id"]) {       
				$a .= "<tr bgcolor=\"#FFA801\">\n";
			} else {
				$a .= "<tr onmouseover=\"this.bgColor='#FFDE01';\" onmouseout=\"this.bgColor='#FFFFFF';UnTip();\">\n";						
			}
			$gk = 14+14+29+(14*($counter-1));
			$a .= "<td width=\"".$gk."\"><img src=\"img/leer.gif\" width=\"".$gk."\" height=\"1\" alt=\"\" /></td>";	//$counter-1           
			$a .= "<td width=\"13\"><img src=\"img/symbole/content_9x11.gif\" width=\"9\" height=\"11\" border=\"0\" alt=\"\" onmouseover=\"".$info."\" /></td>";
			$a .= "<td class=\"v09\" style=\"color:#727889;padding:1px 0 1px 0;\" width=\"".(538-$gk-14-15-110-98)."\" onmouseover=\"".$info."\">";
			
			$ab  = '[ID:'.$article_content["acontent_id"].'] ';
			$ab .= $GLOBALS["wcs_content_type"][$article_content["acontent_type"]];    
			if($article_content["acontent_type"] == 30) {
				$ab .= ': '.$GLOBALS['BL']['modules'][$article_content["acontent_module"]]['listing_title'];
			}                   
			
			$a .= $ab;
			
			$a .= "</td>";                                                   
			$a .= "<td width=\"16\"><img src=\"img/symbole/block.gif\" width=\"9\" height=\"11\" border=\"0\" alt=\"\" style=\"margin:0 3px 0 3px;\" /></td>";  
			$a .= "<td class=\"v09\" style=\"color:#727889;\" width=\"102\">".html_specialchars(' {'.$article_content['acontent_block'].'} ')."</td>";                     
			$a .= '<td nowrap="nowrap" style="padding:1px 0 1px 0;" onmouseover="'.$info.'">'; //width="110"
			
			$at  = ' '.$ab.' ';                       
			
			if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
				$a .= "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article[$akey]["article_id"]."&amp;acid=".$article_content["acontent_id"];
				$a .= "\" title=\"".$GLOBALS["BL"]['be_func_content_edit']." [".$at."]\">";
				$a .= "<img src=\"img/button/edit_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
			} else {
				$a .= "<img src=\"img/button/edit_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
			}
																	
			if($cut_article_content) {
				if($cut_article_content != $article_content["acontent_id"]) {
					$a .= '<a href="include/inc_act/act_structure.php?do='.rawurlencode('7|'.$cut_article_content."|".$article_content["acontent_aid"]."|".$article_content["acontent_sorting"]);
					$a .= "\" title=\"".$GLOBALS['BL']['be_func_content_paste'];
					$a .= " [".$at."]\"><img src=\"img/button/cut_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				} else {
					$a .= "<a href=\"phpwcms.php?do=articles\" title=\"";
					$a .= $GLOBALS['BL']['be_func_content_paste_cancel']." [".$at."]";
					$a .= "\"><img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				}
			} else {
				if($article_content["acontent_id"]) {
						$a .= "<a href=\"phpwcms.php?do=articles&amp;accut=".$article_content["acontent_id"]."\" title=\"";
						$a .= $GLOBALS['BL']['be_func_content_cut']." [".$at;
						$a .= "]\"><img src=\"img/button/cut_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				} else {
						$a .= "<a href=\"phpwcms.php?do=articles\" title=\"".$GLOBALS['BL']['be_func_content_no_cut']."\">";
						$a .= "<img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				}
			}
			if($copy_article_content) {
				if($copy_article_content != $article_content["acontent_id"]) {
					$a .= '<a href="include/inc_act/act_structure.php?do='.rawurlencode('8|'.$copy_article_content.'|'.$article_content["acontent_aid"].'|'.$article_content["acontent_sorting"]);
					$a .= "\" title=\"".$GLOBALS['BL']['be_func_content_paste'];
					$a .= " [".$at."]\"><img src=\"img/button/copy_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				} else {
					$a .= "<a href=\"phpwcms.php?do=articles\" title=\"";
					$a .= $GLOBALS['BL']['be_func_content_paste_cancel']." [".$at."]";
					$a .= "\"><img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				}
			} else {
				if($article_content["acontent_id"]) {
					$a .= "<a href=\"phpwcms.php?do=articles&amp;accopy=".$article_content["acontent_id"]."\" title=\"";
					$a .= $GLOBALS['BL']['be_func_content_copy']." [".$at;
					$a .= "]\"><img src=\"img/button/copy_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				} else {
					$a .= "<a href=\"phpwcms.php?do=articles\" title=\"".$GLOBALS['BL']['be_func_content_no_copy']."\">";
					$a .= "<img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				}
			} 
			
			$a .= $sbutton_string[$article_content["acontent_id"]];
	 
			$a .= "<a href=\"include/inc_act/act_articlecontent.php?do=2,".$article_content["acontent_aid"].",".$article_content["acontent_id"].",".(($article_content["acontent_visible"])?0:1);
			$a .= '" title="'.$GLOBALS["BL"]['be_func_struct_svisible'].'">';
			$a .= "<img src=\"img/button/visible_11x11_".$article_content["acontent_visible"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
			
			// added space between visible icon and delete icon
			$a .= '<img src="img/leer.gif" width="11" height="1" border="0" alt="" />';
	
			if($article_content["acontent_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
				$a .= "<a href=\"include/inc_act/act_articlecontent.php?do=9,".$article_content["acontent_aid"].",".$article_content["acontent_id"];
				$a .= "\" title=\"".$GLOBALS['BL']['be_article_cnt_delpart']." [".$at."]\" ";
				$a .= "onclick=\"return confirm('".$GLOBALS['BL']['be_article_cnt_delpart']." \\n[".js_singlequote($at)."] ');\">";
				$a .= "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
			} else {
				$a .= "<img src=\"img/button/del_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
			}
														 
			$a .= "</td>\n</tr>";
		}
		
		if($a) {
			$aa  = "<tr>\n<td colspan=\"2\">";
			$aa .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\" width=\"538\">\n";
			$aa .= $a;	
			$aa .= "</table></td></tr>";	
			echo $aa;
		}                                                       
	}
}

function listmode_edits ($listmode, $struct, $key, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, $forbid_cut, $forbid_copy, $count_row, $child_sort) {

	// Decide which action available
	$a = ''; 
	switch($listmode) {
	
		case 0:	$a .= "<a href=\"phpwcms.php?do=articles&amp;p=1&amp;struct=".$struct[$key]["acat_id"]."\" ";
				$a .= "title=\"".$GLOBALS['BL']['be_func_struct_new_article']." \n[".$an."]\">";
				$a .= "<img src=\"img/button/add_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				if($cut_article) { // Cut
					$a .= '<a href="include/inc_act/act_structure.php?do=3'.'%7C'.$cut_article.'%7C';
					$a .= $struct[$key]["acat_id"]."\" title=\"".$GLOBALS['BL']['be_func_struct_paste_article']." \n[".$an;
					$a .= "]\"><img src=\"img/button/cut_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				} else {
					$a .= "<img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				}
				if($copy_article) {
					$a .= '<a href="include/inc_act/act_structure.php?do=5'.'%7C'.$copy_article.'%7C';
					$a .= $struct[$key]["acat_id"]."\" title=\"".$GLOBALS['BL']['be_func_struct_paste_article']." \n[".$an;
					$a .= "]\"><img src=\"img/button/copy_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				} else {
					$a .= "<img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				}
		
				$a .= "<img src=\"img/button/sort_1_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				$a .= "<img src=\"img/button/sort_2_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				$a .= "<img src=\"img/button/visible_11x11a_".$struct[$key]["acat_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				$a .= "<img src=\"img/button/public_11x11a_".$struct[$key]["acat_public"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				
				break;

		case 1:	$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;struct=".$struct[$key]["acat_id"]."&amp;sort=".$child_sort."\" title=\"";
				$a .= $GLOBALS['BL']['be_func_struct_insert_level']." [".$an."]\"><img src=\"img/button/add_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		
				if($cut_id) {
					if($cut_id != $struct[$key]["acat_id"] && !$forbid_cut) {
						$a .= '<a href="include/inc_act/act_structure.php?do=1'.'%7C'.$cut_id.'%7C'.$struct[$key]["acat_id"].'%7C';
						$a .= $child_sort."\" title=\"".$GLOBALS['BL']['be_func_struct_paste_level'];
						$a .= " [".$an."]\"><img src=\"img/button/cut_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					} else {
						$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6\" title=\"";
						$a .= ($forbid_cut) ? $GLOBALS['BL']['be_func_struct_no_paste1']."\n[".$an."]\n".
						$GLOBALS['BL']['be_func_struct_no_paste2']."\n".
						$GLOBALS['BL']['be_func_struct_no_paste3'] :
						$GLOBALS['BL']['be_func_struct_paste_cancel']." [".$an."]";
						$a .= "\"><img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					}
				} else {
					if($struct[$key]["acat_id"]) {
						$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;cut=".$struct[$key]["acat_id"]."\" title=\"";
						$a .= $GLOBALS['BL']['be_func_struct_cut_level']." [".$an;
						$a .= "]\"><img src=\"img/button/cut_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					} else {
						$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6\" title=\"".$GLOBALS['BL']['be_func_struct_no_cut']."\">";
						$a .= "<img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					}
				}
				if($copy_id) {
					if($copy_id != $struct[$key]["acat_id"] && !$forbid_copy) {
						$a .= '<a href="include/inc_act/act_structure.php?do=6'.'%7C'.$copy_id.'%7C'.$struct[$key]["acat_id"].'%7C';
						$a .= $child_sort."\" title=\"".$GLOBALS['BL']['be_func_struct_paste_level'];
						$a .= " [".$an."]\"><img src=\"img/button/copy_11x11_1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					} else {
						$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6\" title=\"";
						$a .= ($forbid_copy) ? $GLOBALS['BL']['be_func_struct_no_paste1']."\n[".$an."]\n".
						$GLOBALS['BL']['be_func_struct_no_paste2']."\n".
						$GLOBALS['BL']['be_func_struct_no_paste3'] :
						$GLOBALS['BL']['be_func_struct_paste_cancel']." [".$an."]";
						$a .= "\"><img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					}
				} else {
					if($struct[$key]["acat_id"]) {
						$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;cop=".$struct[$key]["acat_id"]."\" title=\"";
						$a .= $GLOBALS['BL']['be_func_struct_copy_level']." [".$an;
						$a .= "]\"><img src=\"img/button/copy_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					} else {
						$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6\" title=\"".$GLOBALS['BL']['be_func_struct_no_copy']."\">";
						$a .= "<img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
					}
				}
				$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;struct=";
				if($struct[$key]["acat_id"]) {
					$a .= $struct[$key]["acat_struct"]."&amp;cat=".$struct[$key]["acat_id"];
				} else {
					$a .= 'index';
				}
				$a .= '" title="'.$GLOBALS['BL']['be_func_struct_sedit'].' ['.$an.']">';
				$a .= "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a>";
		
				//Sortierungslink
				$sort_up		= (($count_row>1 && $key)?1:0);
				$sort_down		= (($count_row>1 && $key+1<$count_row)?1:0);
		
				//davor sortieren
				$a .= ($sort_up)? '<a href="include/inc_act/act_structure.php?do=2'.'%7C'.$struct[$key]["acat_id"].'%7C'.($key*10).'%7C'.$struct[$key-1]["acat_id"].
				'%7C'.(($key+1)*10).'" title="'.$GLOBALS['BL']['be_func_struct_sort_up'].'">':'';
				$a .= "<img src=\"img/button/sort_1_".$sort_up.".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />".(($sort_up)?"</a>":"");
				//dahinter sortieren
				$a .= ($sort_down)? '<a href="include/inc_act/act_structure.php?do=2'.'%7C'.$struct[$key]["acat_id"].'%7C'.(($key+2)*10).'%7C'.$struct[$key+1]["acat_id"].
				'%7C'.(($key+1)*10).'" title="'.$GLOBALS['BL']['be_func_struct_sort_down'].'">':'';
				$a .= "<img src=\"img/button/sort_2_".$sort_down.".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />".(($sort_down)?"</a>":"");
				
				
				$a .= "<img src=\"img/button/visible_11x11_".$struct[$key]["acat_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				$a .= "<img src=\"img/button/public_11x11_".$struct[$key]["acat_public"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";
				
				
		
				if($struct[$key]["acat_id"]) {
					$a .= '<a href="include/inc_act/act_structure.php?do=9'.'%7C'.$struct[$key]["acat_id"];
					$a .= "\" title=\"".$GLOBALS['BL']['be_func_struct_del_struct']." [".$an."]\" ";
					$a .= "onclick=\"return confirm('".$GLOBALS['BL']['be_func_struct_del_sjsmsg']." \\n\\n[".js_singlequote($an)."] ');\">";
					$a .= "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
				}
				break;

		default: $a .= "&nbsp;";
	
	}
	return $a;
}

?>