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

//26-04-2004 Oliver Georgi -> $level seems to be unused
//19-11-2004 Fernando Batista -> Copy article, Copy structures http://fernandobatista.net
//31-03-2005 Fernando Batista -> Copy/Cut Article Content http://fernandobatista.net

function struct_list($id, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode=1, $forbid_cut=0, $forbid_copy=0, $counter=0) {

    $counter++;
    $sql  = "SELECT t1.*, t2.template_default, t2.template_name, t2.template_trash FROM ".DB_PREPEND."phpwcms_articlecat t1 ";
    $sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_template t2 ON t1.acat_template=t2.template_id ";
    $sql .= "WHERE acat_trash=0 AND acat_struct=".intval($id)." ORDER BY acat_sort";

    $struct = _dbQuery($sql);

    if(isset($struct[0]['acat_id'])) {
        $count_row = count($struct);

        foreach($struct as $key => $value) {
            struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row);
        }
    }
}

function struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row) {

    global $BL;

    $page_val       = ($listmode) ? "do=admin&amp;p=6" : "do=articles";
    $child_count    = get_root_childcount($struct[$key]["acat_id"]);
    $child_sort     = (($child_count+1)*10);

    $forbid_cut     = ($struct[$key]["acat_struct"] == $cut_id || $forbid_cut) ? 1 : 0;
    $forbid_copy    = ($struct[$key]["acat_struct"] == $copy_id || $forbid_copy) ? 1 : 0;

    $an = html($struct[$key]["acat_name"]);
    $a  = "<tr onmouseover=\"this.bgColor='#CCFF00';\" onmouseout=\"this.bgColor='#FFFFFF';\">\n";
    $a .= '<td width="461">';
    $a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
    $a .= '<td nowrap="nowrap" class=""nowrap"><img src="img/leer.gif" width="'.(14+(14*($counter-1)))."\" height=\"1\" alt=\"\" />"; //14
    $a .= $child_count ? '<a href="phpwcms.php?'.$page_val.'&amp;open='.rawurlencode($struct[$key]["acat_id"].':'.(empty($_SESSION["structure"][$struct[$key]["acat_id"]]) ? 1 : 0)).'">' : '';
    $a .= "<img src=\"img/symbole/plus_".($child_count ? (empty($_SESSION["structure"][$struct[$key]["acat_id"]]) ? "open" : "close") : "empty");
    $a .= ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" />".($child_count ? "</a>" : "");
    $a .= "<img src=\"img/symbole/page_".($struct[$key]["acat_hidden"] ? 7 : 1);
    if($struct[$key]["acat_regonly"]) {
        $a .= '_locked';
    }
    $a .= ".gif\" width=\"11\" height=\"15\" ";

    $info  = 'ID: <b>'.$struct[$key]["acat_id"].'</b><br />';
    $info .= $BL['be_alias'].': '.html($struct[$key]["acat_alias"]).'<br />';
    $info .= $BL['be_cnt_sortvalue'].': '.$struct[$key]["acat_sort"];
    $info .= '<br>'.$BL['be_admin_struct_template'].': ';
    if(empty($struct[$key]['template_trash'])) {
        $info .= html($struct[$key]["template_name"]);
        if($struct[$key]["template_default"]) {
            $info .= ' ('.$BL['be_admin_tmpl_default'].')';
        }
    } else {
        $info .= $BL['be_admin_tmpl_default'];
    }
    $info .= '<br>'.$BL['be_onepage_id'].': '.($struct[$key]["acat_onepage"] ? $BL['be_yes'] : $BL['be_no']);

    $a .= 'onmouseover="Tip(\''.$info.'\');" onmouseout="UnTip()" alt=""';

    $a .= "></td>\n";
	$a .= "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>";
    $a .= '<td class="dir" width="95%"><strong><a href="';
    $a .= rel_url(array('phpwcms-preview'=>1), array(), empty($struct[$key]["acat_alias"]) ? 'id='.$struct[$key]["acat_id"] : $struct[$key]["acat_alias"]);
    $a .= '" target="_blank" title="'.$BL['be_func_struct_preview'].': '.$an.'">';
	$a .= $an . '</a></strong></td></tr></table></td><td class="nowrap" nowrap="nowrap">';

    $a .= listmode_edits($listmode, $struct, $key, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, $forbid_cut, $forbid_copy, $count_row, $child_sort);

    $a .= "</td>\n</tr>\n";
    echo $a;

    if(isset($_SESSION["structure"][$struct[$key]["acat_id"]]) && $_SESSION["structure"][$struct[$key]["acat_id"]]) {

        if(!$listmode) {
            struct_articlelist($struct[$key]["acat_id"], $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $struct[$key]["acat_order"]);
        }

        struct_list($struct[$key]["acat_id"], $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode, $forbid_cut, $forbid_copy, $counter);

    }

}

function get_root_childcount($id) {

    // get amount of active child levels
    $id = intval($id);

    $p1_count = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$id, 'COUNT');
    $p2_count = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_cid=".$id, 'COUNT');

    return $p1_count + $p2_count;

}

function get_article_content_count($id) {

    return _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_aid=".intval($id), 'COUNT');

}

function struct_articlelist($struct_id, $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $article_order=0) {

    global $BL;

    $article            = array();  // empty article array
    $sort_array         = array();  // empty array to store all sort values for the category
    $article_order      = intval($article_order);
    $max_article_count  = 0;
    $show_sort          = (!$article_order || $article_order == 1) ? 1 : 0;
    $ao                 = get_order_sort($article_order);
    $count_article      = 0;
    $sbutton_string     = array();

    $sql  = "SELECT *, ";
    $sql .= "DATE_FORMAT(article_tstamp, '%Y-%m-%d %H:%i:%s') AS article_date "; //, article_deleted
    $sql .= "FROM ".DB_PREPEND."phpwcms_article ";
    $sql .= "WHERE article_cid='".$struct_id."' AND article_deleted=0 ORDER BY ".$ao[2];

    $result = _dbQuery($sql);

    if(isset($result[0]['article_id'])) {

        // 1st get max count to know the last index ($max_article_count - 1)
        $max_article_count = count($result);

        // take all entryies and build new array with it
        foreach($result as $row) {

            $article[$count_article] = $row;
            if($row['article_sort'] > 0) {
                $sort_array[$count_article] = $row['article_sort'];
            }

            // count up for article array index
            $count_article++;

        }

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

        $at = html($article[$akey]["article_title"]);

        if($cut_article == $article[$akey]["article_id"] ) {
            $a = "<tr bgColor='#B4E101'>\n";
        } elseif($copy_article == $article[$akey]["article_id"]){
            $a = "<tr bgColor='#B4E101'>\n";
        } else {
            $a = "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
        }

        $a .= '<td width="461">';
        $a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";

        $acontent_count = get_article_content_count($article[$akey]["article_id"]);

        if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
            $a .= "<td class=\"nowrap\"><img src=\"img/leer.gif\" width=\"".(14+14+(14*($counter-1)))."\" height=\"1\" alt=\"\" />";
            $a .= ($acontent_count) ? "<a href=\"phpwcms.php?do=articles&amp;opena=".rawurlencode($article[$akey]["article_id"].":".((!empty($_SESSION["structure"]["article"][$article[$akey]["article_id"]]))?0:1))."\">" : "";
            $a .= "<img src=\"img/symbole/plus_".(($acontent_count) ? ((!empty($_SESSION["structure"]["article"][ $article[$akey]["article_id"] ])) ? "close" : "open") : "empty");
            $a .= ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" />".(($acontent_count) ? "</a>" : "");
        }else{
            $a .= "<td class=\"nowrap\"><img src=\"img/leer.gif\" width=\"".(14+29+(14*($counter-1)))."\" height=\"1\" alt=\"\" />";
        }

        $a .= "<img src=\"img/symbole/text_1.gif\" width=\"11\" height=\"15\" ";

        $info  = '<table cellspacing=0 cellpadding=1 border=0>';
        $info .= '<tr><td>'.$BL['be_func_struct_articleID'].':</td><td><b>'.$article[$akey]["article_id"].'</b></td></tr>';
        if(!empty($article[$akey]["article_alias"])) {
            $info .= '<tr><td>ALIAS:</td><td><b>'.$article[$akey]["article_alias"].'</b></td></tr>';
        }
        if(!empty($article[$akey]["article_begin"])) {
			$info .= '<tr><td>'.$BL['be_article_cnt_start'].':</td><td><b>';
			$info .= $article[$akey]["article_begin"] === '0000-00-00 00:00:00' ? $BL['be_not_set'] : phpwcms_strtotime($article[$akey]["article_begin"], $BL['be_longdatetime'], '&nbsp;');
			$info .= '</b></td></tr>';
        }
        if(!empty($article[$akey]["article_end"])) {
			$info .= '<tr><td>'.$BL['be_article_cnt_end'].':</td><td><b>';
			$info .= $article[$akey]["article_end"] === '0000-00-00 00:00:00' ? $BL['be_not_set'] : phpwcms_strtotime($article[$akey]["article_end"], $BL['be_longdatetime'], '&nbsp;');
			$info .= '</b></td></tr>';
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
        $a .= '<td class="dir" width="95%"><a href="';
        $a .= rel_url(array('phpwcms-preview'=>1), array(), empty($article[$akey]["article_alias"]) ? 'aid='.$article[$akey]["article_id"] : $article[$akey]["article_alias"]);
        $a .= '" target="_blank" title="'.$BL['be_func_struct_preview'].': '.$at.'">';
		$a .= $at.'</a></td></tr></table></td><td width="88" nowrap="nowrap" class="nowrap">';

        if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
            $a .= "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id=".$article[$akey]["article_id"];
            $a .= "\" title=\"".$BL['be_func_struct_edit']." \n[".$at."]\">";
			$a .= "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\"></a>";
        } else {
			$a .= "<img src=\"img/button/edit_22x11_0.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" />";
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

        //switch active status
        $a .= "<a href=\"include/inc_act/act_articlecontent.php?do=3,".$article[$akey]["article_id"].",,".(($article[$akey]["article_aktiv"])?0:1);
        $a .= '" title="'.$BL['be_func_struct_svisible'].'">';
        $a .= "<img src=\"img/button/visible_11x11_".$article[$akey]["article_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";

        //delete article
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

        $result = _dbQuery($sql);
        //Sort counter
        $sc = 0;
        $scc = 0;
        $sbutton = array();

        if(isset($result[0]['acontent_id'])) {
            foreach($result as $row) {
                $scc++;
                if($row['acontent_trash'] == 0) {
                    $sc++;
                    $sbutton[$sc]["id"]    = $row['acontent_id'];
                    $sbutton[$sc]["sort"]  = $row['acontent_sorting'];
                    $sbutton[$sc]["block"] = $row['acontent_block'];
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
                struct_articlecontentlist($article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string);
            }
        }

    }
}

function struct_articlecontentlist($article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string){

    $a    = '';

    $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent ";
    $sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." AND acontent_trash=0 ";
    $sql .= "ORDER BY acontent_block, acontent_sorting, acontent_id";

    $result = _dbQuery($sql);

    if(isset($result[0]['acontent_id'])) {

        foreach($result as $article_content) {

            // if type of content part not enabled available
            if(!isset($GLOBALS['wcs_content_type'][ $article_content["acontent_type"] ]) || ($article_content['acontent_type'] == 30 && !isset($GLOBALS['phpwcms']['modules'][$article_content["acontent_module"]]))) {
                continue;
            }

            $info  = "Tip('";
            $info .= 'ID: '.$article_content["acontent_id"];
            if($article_content['acontent_title']) {
                $info .= '<br /><nobr>' . $GLOBALS['BL']['be_article_cnt_ctitle'].': '.html(js_singlequote($article_content['acontent_title'])).'</nobr>';
            }
            if($article_content['acontent_title']) {
                $info .= '<br /><nobr>' . $GLOBALS['BL']['be_article_asubtitle'].': '.html(js_singlequote($article_content['acontent_subtitle'])).'</nobr>';
            }
            if($article_content["acontent_comment"]) {
                $info .= '<br />' . nl2br( html(js_singlequote($article_content["acontent_comment"])) );
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
            $a .= "<td width=\"".$gk."\"><img src=\"img/leer.gif\" width=\"".$gk."\" height=\"1\" alt=\"\" /></td>";    //$counter-1
            $a .= "<td width=\"13\"><img src=\"img/symbole/content_9x11.gif\" width=\"9\" height=\"11\" border=\"0\" alt=\"\" onmouseover=\"".$info."\" /></td>";
			$a .= "<td class=\"v09\" style=\"color:#727889;padding:1px 0 1px 0;\" onmouseover=\"".$info."\">";

            $ab  = '[ID:'.$article_content["acontent_id"].'] ';
            $ab .= $GLOBALS["wcs_content_type"][$article_content["acontent_type"]];
            if($article_content["acontent_type"] == 30) {
                $ab .= ': '.$GLOBALS['BL']['modules'][$article_content["acontent_module"]]['listing_title'];
            }

            $a .= $ab;

            $a .= "</td>";
            $a .= "<td width=\"16\"><img src=\"img/symbole/block.gif\" width=\"9\" height=\"11\" border=\"0\" alt=\"\" style=\"margin:0 3px 0 3px;\" /></td>";
            $a .= "<td class=\"v09\" style=\"color:#727889;\" width=\"102\">".html(' {'.$article_content['acontent_block'].'} ')."</td>";
			$a .= '<td class="nowrap" style="padding:1px 0 1px 0;width:94px;white-space:nowrap;" onmouseover="'.$info.'">';

            $at  = ' '.$ab.' ';

            if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
                $a .= "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article[$akey]["article_id"]."&amp;acid=".$article_content["acontent_id"];
                $a .= "\" title=\"".$GLOBALS["BL"]['be_func_content_edit']." [".$at."]\">";
				$a .= "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a>";
            } else {
				$a .= "<img src=\"img/button/edit_22x11_0.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" />";
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
            } elseif($article_content["acontent_id"]) {
                $a .= "<a href=\"phpwcms.php?do=articles&amp;accut=".$article_content["acontent_id"]."\" title=\"";
                $a .= $GLOBALS['BL']['be_func_content_cut']." [".$at;
                $a .= "]\"><img src=\"img/button/cut_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
            } else {
                $a .= "<a href=\"phpwcms.php?do=articles\" title=\"".$GLOBALS['BL']['be_func_content_no_cut']."\">";
                $a .= "<img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
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
            } elseif($article_content["acontent_id"]) {
                $a .= "<a href=\"phpwcms.php?do=articles&amp;accopy=".$article_content["acontent_id"]."\" title=\"";
                $a .= $GLOBALS['BL']['be_func_content_copy']." [".$at;
                $a .= "]\"><img src=\"img/button/copy_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
            } else {
                $a .= "<a href=\"phpwcms.php?do=articles\" title=\"".$GLOBALS['BL']['be_func_content_no_copy']."\">";
                $a .= "<img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
            }

            $a .= $sbutton_string[$article_content["acontent_id"]];

            $a .= "<a href=\"include/inc_act/act_articlecontent.php?do=2,".$article_content["acontent_aid"].",".$article_content["acontent_id"].",".(($article_content["acontent_visible"])?0:1);
            $a .= '" title="'.$GLOBALS["BL"]['be_func_struct_svisible'].'">';
            $a .= "<img src=\"img/button/visible_11x11_".$article_content["acontent_visible"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";

            // added space between visible icon and delete icon
            //$a .= '<img src="img/leer.gif" width="11" height="1" border="0" alt="" />';

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
            $aa .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\" width=\"100%\">\n";
            $aa .= $a;
            $aa .= "</table></td></tr>";
            echo $aa;
        }
    }
}

function listmode_edits($listmode, $struct, $key, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, $forbid_cut, $forbid_copy, $count_row, $child_sort) {

    // Decide which action available
    $a = '';
    switch($listmode) {

		case 0:	$a .= "<a href=\"phpwcms.php?do=articles&amp;p=1&amp;struct=".$struct[$key]["acat_id"]."\" ";
                $a .= "title=\"".$GLOBALS['BL']['be_func_struct_new_article']." \n[".$an."]\">";
				$a .= "<img src=\"img/button/add_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a>";
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

                break;

		case 1:	$a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;struct=".$struct[$key]["acat_id"]."&amp;sort=".$child_sort."\" title=\"";
				$a .= $GLOBALS['BL']['be_func_struct_insert_level']." [".$an."]\"><img src=\"img/button/add_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a>";

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
                } elseif($struct[$key]["acat_id"]) {
                    $a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;cut=".$struct[$key]["acat_id"]."\" title=\"";
                    $a .= $GLOBALS['BL']['be_func_struct_cut_level']." [".$an;
                    $a .= "]\"><img src=\"img/button/cut_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
                } else {
                    $a .= "<a href=\"phpwcms.php?do=admin&amp;p=6\" title=\"".$GLOBALS['BL']['be_func_struct_no_cut']."\">";
                    $a .= "<img src=\"img/button/cut_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
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
                } elseif($struct[$key]["acat_id"]) {
                    $a .= "<a href=\"phpwcms.php?do=admin&amp;p=6&amp;cop=".$struct[$key]["acat_id"]."\" title=\"";
                    $a .= $GLOBALS['BL']['be_func_struct_copy_level']." [".$an;
                    $a .= "]\"><img src=\"img/button/copy_11x11_0.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
                } else {
                    $a .= "<a href=\"phpwcms.php?do=admin&amp;p=6\" title=\"".$GLOBALS['BL']['be_func_struct_no_copy']."\">";
                    $a .= "<img src=\"img/button/copy_11x11_9.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>";
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
                $sort_up        = (($count_row>1 && $key)?1:0);
                $sort_down      = (($count_row>1 && $key+1<$count_row)?1:0);

                //davor sortieren
                $a .= ($sort_up)? '<a href="include/inc_act/act_structure.php?do=2'.'%7C'.$struct[$key]["acat_id"].'%7C'.($key*10).'%7C'.$struct[$key-1]["acat_id"].
                '%7C'.(($key+1)*10).'" title="'.$GLOBALS['BL']['be_func_struct_sort_up'].'">':'';
                $a .= "<img src=\"img/button/sort_1_".$sort_up.".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />".(($sort_up)?"</a>":"");
                //dahinter sortieren
                $a .= ($sort_down)? '<a href="include/inc_act/act_structure.php?do=2'.'%7C'.$struct[$key]["acat_id"].'%7C'.(($key+2)*10).'%7C'.$struct[$key+1]["acat_id"].
                '%7C'.(($key+1)*10).'" title="'.$GLOBALS['BL']['be_func_struct_sort_down'].'">':'';
                $a .= "<img src=\"img/button/sort_2_".$sort_down.".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />".(($sort_down)?"</a>":"");
                $a .= "<img src=\"img/button/visible_11x11_".$struct[$key]["acat_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" />";

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

function update_404redirect() {

    $data = array(
        'error' => array(),
        'data'  => array(
            'rid'       => intval($_POST['rid']),
            'alias'     => clean_slweg($_POST['alias']),
            'id'        => trim($_POST['id']) === '' ? '' : intval($_POST['id']),
            'aid'       => trim($_POST['aid']) === '' || !intval($_POST['aid']) ? '' : intval($_POST['aid']),
            'type'      => empty($_POST['type']) || !in_array($_POST['type'], array('alias', 'id', 'aid', 'link')) ? '' : clean_slweg($_POST['type']),
            'active'    => empty($_POST['active']) ? 0 : 1,
            'shortcut'  => empty($_POST['shortcut']) ? 0 : 1,
            'code'      => empty($_POST['code']) || !in_array($_POST['code'], array('301', '307', '404', '401', '503')) ? '' : clean_slweg($_POST['code']),
            'target'    => clean_slweg($_POST['target']),
            'changed'   => date('Y-m-d H:i:s')
        )
    );

    if(!$data['data']['aid'] && !$data['data']['alias'] && $data['data']['id'] == '' && !isset($_POST['delete_'.md5($data['data']['rid'])])) {
        $data['error'][] = $GLOBALS['BL']['be_redirect_error1'];
    }
    if($data['data']['type'] && $data['data']['target'] === '') {
        $data['error'][] = $GLOBALS['BL']['be_redirect_error2'];
    } elseif(($data['data']['type'] == 'id' || $data['data']['type'] == 'aid') && !is_intval($data['data']['target'])) {
        $data['error'][] = $GLOBALS['BL']['be_redirect_error3'];
    }

    if(count($data['error'])) {
        $data['data']['active'] = 0;
        set_status_message(implode('<br />', $data['error']), 'error');
    } else {
        $data['error'] = NULL;
        $rid = $data['data']['rid'];
        unset($data['data']['rid']);
        if($rid) {
            // Mark for deletion
            if(isset($_POST['delete_'.md5($rid)])) {
                $data['data']['active'] = 9;
                $result = _dbQuery('DELETE FROM '.DB_PREPEND.'phpwcms_redirect WHERE rid='.$rid, 'DELETE');
            } else {
                $result = _dbUpdate('phpwcms_redirect', $data['data'], 'rid='.$rid);
            }
        } else {
            $result = _dbInsert('phpwcms_redirect', $data['data']);
            if(isset($result['INSERT_ID'])) {
                $rid = $result['INSERT_ID'];
            }
        }
        $data['data']['rid'] = $rid;

        if($result) {
            if($data['data']['active'] == 9) {
                set_status_message(str_replace('{ID}', $data['data']['rid'], $GLOBALS['BL']['be_action_deleted']), 'success');
                headerRedirect('phpwcms.php?'.get_token_get_string().'&do=admin&p=14');
            } else {
                set_status_message($GLOBALS['BL']['be_successfully_saved'], 'success');
            }
        } else {
            set_status_message($GLOBALS['BL']['be_error_while_save'], 'error');
        }
    }

    return $data;
}
