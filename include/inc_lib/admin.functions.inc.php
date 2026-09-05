<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// get article details
function get_article_data($aid) {
    $sql  = "SELECT article_id, article_cid, article_title, article_subtitle, article_alias, article_aktiv, article_public, article_uid, article_lang, ";
    $sql .= "date_format(article_tstamp, '".$GLOBALS['BL']['be_sqlshortdatetime']."') AS article_date ";
    $sql .= 'FROM '.DB_PREPEND.'phpwcms_article WHERE article_deleted=0 AND   article_id = ' . intval($aid) . ' LIMIT 1';

    $data = _dbQuery($sql);
    return $data[0];
}

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

    $page_val       = $listmode ? "do=articles&amp;p=0" : "do=articles";
    $child_count    = get_root_childcount($struct[$key]["acat_id"]);
    $child_sort     = (($child_count+1)*10);

    $forbid_cut     = $struct[$key]["acat_struct"] == $cut_id || $forbid_cut ? 1 : 0;
    $forbid_copy    = $struct[$key]["acat_struct"] == $copy_id || $forbid_copy ? 1 : 0;

    $an = html($struct[$key]["acat_name"]);
    $a  = "<tr class=\"hover-success bg-row-grey-medium scroll-anchor\" id=\"struct_" . $struct[$key]["acat_id"] . "\">\n";
    $a .= '<td class="w-80">'.LF;
    $a .= '<table class="table-borderless"'.">\n<tr>\n";
    $a .= '<td class="text-end here text-nowrap">'.LF;
    $a .= ($child_count) ? "<a href=\"phpwcms.php?".$page_val."&amp;open=".rawurlencode($struct[$key]["acat_id"].":".((!empty($_SESSION["structure"][$struct[$key]["acat_id"]]))?0:1))."#struct_".$struct[$key]["acat_id"]."\">" : "";

    $a .= '<i class="fa-solid fa-caret-'.(($child_count) ? (empty($_SESSION["structure"][$struct[$key]["acat_id"]]) ? "right" : "down") : "right").' fa-fw alist-'.$counter.'" aria-hidden="true"></i>'.(($child_count) ? "</a>" : "");

    $info  = '<table class="text-start">';
    $info .= '<tr><td>ID:</td><td><b>'.$struct[$key]["acat_id"].'</b></td></tr>';
    $info .= '<tr><td>'.$BL['be_alias'].':</td><td>'.$struct[$key]["acat_alias"].'</td></tr>';
    $info .= '<tr><td>'.$BL['be_cnt_sortvalue'].':</td><td>'.$struct[$key]["acat_sort"].'</td></tr>';
    $info .= '<tr><td>'.$BL['be_admin_struct_template'].':</td><td>';
    if(empty($struct[$key]['template_trash'])) {
        $info .= $struct[$key]["template_name"];
        if($struct[$key]["template_default"]) {
            $info .= ' ('.$BL['be_admin_tmpl_default'].')';
        }
    } else {
        $info .= $BL['be_admin_tmpl_default'];
    }
    $info .= '</td></tr>';
    $info .= '<tr><td>'.$BL['be_onepage_id'].':</td><td>'.($struct[$key]["acat_onepage"] ? $BL['be_yes'] : $BL['be_no']).'</td></tr>';
    $info .= '</table>';

    $a .= '<i class="fa-solid fa-folder';
    if($struct[$key]["acat_regonly"]) {
        $a .= '-lock';
    }
    $a .= ' fa-fw" aria-hidden="true" data-bs-toggle="tooltip" data-bs-html="true" title="'.html($info).'"></i>';

    $a .= '</td>'.LF;
    $a .= '<td class="dir" width="95%"><strong><a href="';
    $a .= rel_url(array('phpwcms-preview'=>1), array(), empty($struct[$key]["acat_alias"]) ? 'id='.$struct[$key]["acat_id"] : $struct[$key]["acat_alias"]);
    $a .= '" target="_blank" data-bs-toggle="tooltip" title="'.$BL['be_func_struct_preview'].': '.$an.'">';
    $a .= $an . '</a></strong></td></tr></table></td><td class="text-nowrap text-end">'.LF;
    if (!empty($struct[$key]['acat_lang'])) {
        $a .= '<span class="me-3 flag-icon flag-icon-' . $struct[$key]['acat_lang'] . '" data-bs-toggle="tooltip" title="' . $struct[$key]['acat_lang'] . '"></span>';
    }

    $a .= listmode_edits($listmode, $struct, $key, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, $forbid_cut, $forbid_copy, $count_row, $child_sort);

    $a .= "</td></tr>\n";
    echo $a;

    if(isset($_SESSION["structure"][$struct[$key]["acat_id"]]) && $_SESSION["structure"][$struct[$key]["acat_id"]]) {

        if(!$listmode) {
            struct_articlelist($struct[$key]["acat_id"], $counter+1, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $struct[$key]["acat_order"]);
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

    $article            = [];  // empty article array
    $sort_array         = [];  // empty array to store all sort values for the category
    $article_order      = intval($article_order);
    $max_article_count  = 0;
    $show_sort          = !$article_order || $article_order == 1 ? 1 : 0;
    $ao                 = get_order_sort($article_order);
    $count_article      = 0;
    $sbutton_string     = [];

    $sql  = "SELECT *, ";
    $sql .= "DATE_FORMAT(article_tstamp, '%Y-%m-%d %H:%i:%s') AS article_date ";
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
            $sort_up = $show_sort;
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
            $sort_down = $show_sort;
        }

        $at = html($article[$akey]["article_title"]);

        if($cut_article == $article[$akey]["article_id"] ) {
            $a = "<tr class=\"bg-row-success-light scroll-anchor\" id=\"article_" . $article[$akey]["article_id"] . "\">\n";
        } elseif($copy_article == $article[$akey]["article_id"]){
            $a = "<tr class=\"bg-row-success-light scroll-anchor\" id=\"article_" . $article[$akey]["article_id"] . "\">\n";
        } else {
            $a = "<tr class=\"hover-success bg-row-alt-grey scroll-anchor\" id=\"article_" . $article[$akey]["article_id"] . "\">\n";
        }

        $a .= '<td class="w-80">'.LF;
        $a .= '<table class="table-borderless">'.LF.'<tr>'.LF;

        $acontent_count = get_article_content_count($article[$akey]["article_id"]);
        $a .= '<td class="text-nowrap">';
        if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
            $a .= ($acontent_count) ? "<a href=\"phpwcms.php?do=articles&amp;opena=".rawurlencode($article[$akey]["article_id"].":".((!empty($_SESSION["structure"]["article"][$article[$akey]["article_id"]]))?0:1))."#article_".$article[$akey]["article_id"]."\">" : "";
            $a .= "<i class=\"fa-solid fa-caret-".(($acontent_count) ? ((!empty($_SESSION["structure"]["article"][ $article[$akey]["article_id"] ])) ? "down" : "right") : "right");
            $a .= ' fa-fw alist-'.($counter).'" aria-hidden="true"></i>'.(($acontent_count) ? "</a>" : "");
        }else{
            $a .= '<div class="alist-'.($counter).'" ></div>';
        }

        $info = '<table class="text-start">';
        $info .= '<tr><td>'.$BL['be_func_struct_articleID'].':</td><td><b>'.$article[$akey]["article_id"].'</b></td></tr>';
        if(!empty($article[$akey]["article_alias"])) {
            $info .= '<tr><td>ALIAS:</td><td><b>'.$article[$akey]["article_alias"].'</b></td></tr>';
        }
        if(!empty($article[$akey]["article_begin"])) {
            $info .= '<tr><td>'.$BL['be_article_cnt_start'].':</td><td><b>';
            $info .= empty($article[$akey]["article_begin"]) ? $BL['be_not_set'] : phpwcms_strtotime($article[$akey]["article_begin"], $BL['be_longdatetime'], '&nbsp;');
            $info .= '</b></td></tr>';
        }
        if(!empty($article[$akey]["article_end"])) {
            $info .= '<tr><td>'.$BL['be_article_cnt_end'].':</td><td><b>';
            $info .= empty($article[$akey]["article_end"]) ? $BL['be_not_set'] : phpwcms_strtotime($article[$akey]["article_end"], $BL['be_longdatetime'], '&nbsp;');
            $info .= '</b></td></tr>';
        }
        $info .= '<tr><td>'.$BL['be_cnt_sortvalue'].':</td><td>'.$article[$akey]["article_sort"].'</td></tr>';
        if(isset($article[$akey]["article_end"])) {
            $info .= '<tr><td>'.$BL['be_priorize'].':</td><td>'.$article[$akey]["article_priorize"].'</td></tr>';
        }
        $info .= '</table>';

        $a .= '<i class="fa-solid fa-file fa-fw" aria-hidden="true" data-bs-html="true" data-bs-toggle="tooltip" title="'.html($info).'" ></i></td>'.LF;
        $a .= '<td class="dir" width="95%"><a href="';
        $a .= rel_url(array('phpwcms-preview'=>1), array(), empty($article[$akey]["article_alias"]) ? 'aid='.$article[$akey]["article_id"] : $article[$akey]["article_alias"]);
        $a .= '" target="_blank" data-bs-toggle="tooltip" title="'.$BL['be_func_struct_preview'].': '.$at.'">';
        $a .= $at.'</a></td></tr></table></td><td class="text-nowrap text-end">';
        if (!empty($article[$akey]["article_lang"])) {
            $a .= '<span class="me-3 flag-icon flag-icon-' . $article[$akey]["article_lang"] . '" data-bs-toggle="tooltip" title="' . $article[$akey]["article_lang"] . '"></span>';
        }
        if($cut_article_content) {
            $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do='.rawurlencode('7|'.$cut_article_content.'|'.$article[$akey]["article_id"].'|-10');
            $a .= '" data-bs-toggle="tooltip" title="'.$BL['be_func_content_paste0'];
            $a .= "\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
        } elseif($copy_article_content) {
            $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do='.rawurlencode('8|'.$copy_article_content.'|'.$article[$akey]["article_id"].'|-10');
            $a .= '" data-bs-toggle="tooltip" title="'.$BL['be_func_content_paste0'];
            $a .= "\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
        }

        $a .= '<div class="btn-group" role="group" aria-label="group'.$article[$akey]["article_id"].'">';
        //edit article
        if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
          $a .= '<a class="btn btn-xs btn-blue" role="button" data-bs-toggle="tooltip" title="'.$BL['be_func_struct_edit'].' ['.$at.']" href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$article[$akey]["article_id"].'"><i class="fa-solid fa-pencil-alt fa-fw"></i></a>';
        }
        $a .= '<div class="btn-group" role="group">';
        $a .= '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" href="#" id="dropdownAcontentLink'.$article[$akey]["article_id"].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';

        $a .= '<div class="dropdown-menu" aria-labelledby="dropdownAcontentLink'.$article[$akey]["article_id"].'">';
        $a .= '<h6 class="dropdown-header">'.$at.'</h6>';
        //copy article
        $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;acopy='.$article[$akey]["article_id"].'">';
        $a .= '<i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_copy'].'</a>';
        //cut article
        $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;acut='.$article[$akey]["article_id"].'">';
        $a .= '<i class="fa-solid fa-cut fa-fw disabled" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_cut'].'</a>';
        //sort article up
        if($sort_up) {
          $a .= '<a class="dropdown-item" href="include/inc_act/act_structure.php?do=4%7C'.$article[$akey]["article_id"] . '%7C' . $article[$akey]['sort_up'] . '%7C'.$article[$akey-1]["article_id"] . '%7C' . $article[$akey]['article_sort'].'"><i class="fa-solid fa-caret-up fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_sort_up'].'</a>';
        }
        //sort article up
        if($sort_down) {
          $a .= '<a class="dropdown-item" href="include/inc_act/act_structure.php?do=4%7C'.$article[$akey]["article_id"] . '%7C' . $article[$akey]['sort_down'] . '%7C'.$article[$akey+1]["article_id"] . '%7C' . $article[$akey]['article_sort'].'"><i class="fa-solid fa-caret-down fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_sort_down'].'</a>';
        }
        //delete article
        if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
          $a .= '<a class="dropdown-item confirm-link" href="include/inc_act/act_articlecontent.php?do=1,'.$article[$akey]["article_id"].'"';
          $a .= " data-confirm-type=\"danger\" data-confirm-action=\"".html($GLOBALS['BL']['modal_delete'])."\" data-confirm=\"".html($GLOBALS['BL']['be_func_struct_del_jsmsg']." [".$at."]")."\">";
          $a .= '<i class="fa-regular fa-trash-alt fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_article_cnt_delpart'].'</a>';
        }
        $a .= '</div></div>';
        $a .= '<button id="abtnarticle'.$article[$akey]["article_id"].'" class="btn fa btn-xs visible '.($article[$akey]["article_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$article[$akey]["article_id"].'" data-type="article" data-table="article" data-field="article_aktiv" data-fieldid="article_id" data-bs-toggle="tooltip" title="'.$BL['be_fprivfunc_cactivefile'].'"><i class="fa-solid '.($article[$akey]["article_aktiv"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';
        $a .= '</div></td></tr>'.LF;
        echo $a;

        $sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."phpwcms_articlecontent ";
        $sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." ORDER BY acontent_block, acontent_sorting, acontent_id";

        $result = _dbQuery($sql);
        //Sort counter
        $sc = 0;
        $scc = 0;

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

            $info = '<table class="text-start"><tr><td>ID:</td><td>'.$article_content["acontent_id"].'</td></tr>';
            if($article_content['acontent_title']) {
                $info .= '<tr><td>' . $GLOBALS['BL']['be_article_cnt_ctitle'].':</td><td>'.$article_content['acontent_title'].'</td></tr>';
            }
            if($article_content['acontent_subtitle']) {
                $info .= '<tr><td>' . $GLOBALS['BL']['be_article_asubtitle'].':</td><td>'.$article_content['acontent_subtitle'].'</td></tr>';
            }
            if($article_content["acontent_comment"]) {
                $info .= '<tr><td colspan="2">' . nl2br($article_content["acontent_comment"]) . '</td></tr>';
            }
            $info .= '</table>';

            if($cut_article_content == $article_content["acontent_id"] ) {
                $a .= "<tr class=\"bg-row-pink\">\n";
            } elseif($copy_article_content == $article_content["acontent_id"]) {
                $a .= "<tr class=\"bg-row-pink\">\n";
            } else {
                $a .= "<tr class=\"hover-amber border-bottom-light bg-row-white\">\n";
            }

            $cntpart_type = $GLOBALS['wcs_content_type'][$article_content['acontent_type']];
            if (!empty($article_content['acontent_module'])) {
                if ($article_content['acontent_type'] == 30 && isset($GLOBALS['BL']['modules'][$article_content['acontent_module']]['listing_title'])) {
                    $cntpart_type .= ': ' . $GLOBALS['BL']['modules'][$article_content['acontent_module']]['listing_title'];
                } elseif ($article_content['acontent_type'] == 60 && function_exists('get_custom_contentpart_title')) {
                    $cntpart_type .= ': ' . get_custom_contentpart_title($article_content['acontent_module']);
                }
            }

            $block = empty($article_content['acontent_block']) ? 'CONTENT' : $article_content['acontent_block'];
            switch ($block) {
                case 'CONTENT':
                    $block_class = 'cp-block-content';
                    break;
                case 'LEFT':
                    $block_class = 'cp-block-left';
                    break;
                case 'RIGHT':
                    $block_class = 'cp-block-right';
                    break;
                case 'HEADER':
                    $block_class = 'cp-block-header';
                    break;
                case 'FOOTER':
                    $block_class = 'cp-block-footer';
                    break;
                case 'CPSET':
                    $block_class = 'cp-block-cpset';
                    break;
                case 'SYSTEM':
                    $block_class = 'cp-block-system';
                    break;
                default:
                    $block_class = 'cp-block-default';
                    break;
            }

            $a .= '<td class="w-80"><i class="fa-solid fa-list-alt fa-fw me-1 aclist-'.($counter).'" aria-hidden="true" data-bs-toggle="tooltip" data-bs-html="true" title="'.html($info).'"></i>';
            $a .= '<span class="badge ' . $block_class . ' fw-normal badge-align me-1">{' . html($block) . '}</span>';
            $a .= '<span class="badge bg-secondary fw-normal badge-align me-1">' . html($cntpart_type) . '</span>';
            if (!empty($article_content['acontent_title'])) {
                $a .= html($article_content['acontent_title']);
            }
            $a .= '</td>';
            $a .= '<td class="text-nowrap text-end">';
            $at = ' ' . $cntpart_type . (!empty($article_content['acontent_title']) ? ': ' . $article_content['acontent_title'] : '') . ' {' . $block . '} ';

            if($cut_article_content) {
                if($cut_article_content != $article_content["acontent_id"]) {
                    $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do='.rawurlencode('7|'.$cut_article_content."|".$article_content["acontent_aid"]."|".$article_content["acontent_sorting"]);
                    $a .= '" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_content_paste'];
                    $a .= " [".$at."]\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
                } else {
                    $a .= "<a class=\"btn btn-xs btn-danger me-1\" href=\"phpwcms.php?do=articles\" title=\"";
                    $a .= $GLOBALS['BL']['be_func_content_paste_cancel']." [".$at."]";
                    $a .= "\"><i class=\"fa-solid fa-times fa-fw\"></i></a>";
                }
            }
            if($copy_article_content) {
                if($copy_article_content != $article_content["acontent_id"]) {
                    $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do='.rawurlencode('8|'.$copy_article_content.'|'.$article_content["acontent_aid"].'|'.$article_content["acontent_sorting"]);
                    $a .= '" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_content_paste'];
                    $a .= " [".$at."]\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
                } else {
                    $a .= "<a class=\"btn btn-xs btn-danger me-1\" href=\"phpwcms.php?do=articles\" title=\"";
                    $a .= $GLOBALS['BL']['be_func_content_paste_cancel']." [".$at."]";
                    $a .= "\"><i class=\"fa-solid fa-times fa-fw\"></i></a>";
                }
            }

            $a .= '<div class="btn-group" role="group" aria-label="group'.$article_content["acontent_id"].'">';
            //edit content part
            if($article[$akey]["article_uid"] == $_SESSION["wcs_user_id"] || $_SESSION["wcs_user_admin"]) {
              $a .= '<a class="btn btn-xs btn-blue" role="button" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_content_edit'].' ['.$at.']" href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article[$akey]["article_id"].'&amp;acid='.$article_content["acontent_id"].'"><i class="fa-solid fa-pencil-alt fa-fw"></i></a>';
            }
            $a .= '<div class="btn-group" role="group">';
            $a .= '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" href="#" id="dropdownAcontentLink'.$article_content["acontent_id"].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';

            $a .= '<div class="dropdown-menu" aria-labelledby="dropdownAcontentLink'.$article_content["acontent_id"].'">';
            $a .= '<h6 class="dropdown-header">'.$at.'</h6>';
            //copy content part
            $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;accopy='.$article_content["acontent_id"].'"><i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_content_copy'].'</a>';
            //cut content part
            $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;accut='.$article_content["acontent_id"].'"><i class="fa-solid fa-cut fa-fw disabled" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_content_cut'].'</a>';
            $a .= '<a class="dropdown-item" href="include/inc_act/act_articlecontent.php?do=9,'.$article_content["acontent_aid"].','.$article_content["acontent_id"].'"';
            $a .= " onclick=\"return confirm('".$GLOBALS['BL']['be_article_cnt_delpart']." \\n[".js_singlequote($at)."] ?')\">";
            $a .= '<i class="fa-regular fa-trash-alt fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_article_cnt_delpart'].'</a>';
            $a .= '</div></div>';
            $a .= '<button id="abtnarticlecontent'.$article_content["acontent_id"].'" class="btn fa btn-xs visible '.($article_content["acontent_visible"]==0 ? "btn-danger" : "btn-success").'" data-id="'.$article_content["acontent_id"].'" data-type="articlecontent" data-table="articlecontent" data-field="acontent_visible" data-fieldid="acontent_id" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cactivefile'].'"><i class="fa-solid '.($article_content["acontent_visible"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';

            $a .= "</div></td></tr>".LF;
        }

        if($a) {
            echo $a;
        }
    }
}

function listmode_edits($listmode, $struct, $key, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, $forbid_cut, $forbid_copy, $count_row, $child_sort) {

    // Decide which action available
    $a = '';
    switch($listmode) {

    case 0:     //cut article
                if($cut_article) {
                    $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do=3'.'%7C'.$cut_article.'%7C';
                    $a .= $struct[$key]["acat_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_struct_paste_article']." \n[".$an;
                    $a .= "]\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
                }
                //copy article
                if($copy_article) {
                    $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do=5'.'%7C'.$copy_article.'%7C';
                    $a .= $struct[$key]["acat_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_struct_paste_article']." \n[".$an;
                    $a .= "]\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
                }
                //cut structure
                if($cut_id) {
                    if($cut_id != $struct[$key]["acat_id"] && !$forbid_cut) {
                        $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do=1'.'%7C'.$cut_id.'%7C'.$struct[$key]["acat_id"].'%7C';
                        $a .= $child_sort.'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_struct_paste_level'];
                        $a .= " [".$an."]\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
                    } elseif($cut_id == !$forbid_cut) {
                        $a .= "<a class=\"btn btn-xs btn-danger me-1\" href=\"phpwcms.php?do=articles\" title=\"";
                        $a .= $GLOBALS['BL']['be_func_content_paste_cancel']; //." [".$at."]";
                        $a .= "\"><i class=\"fa-solid fa-times fa-fw\"></i></a>";
                    }
                }
                //copy structure
                if($copy_id) {
                    if($copy_id != $struct[$key]["acat_id"] && !$forbid_copy) {
                        $a .= '<a class="btn btn-xs btn-warning me-1" href="include/inc_act/act_structure.php?do=6'.'%7C'.$copy_id.'%7C'.$struct[$key]["acat_id"].'%7C';
                        $a .= $child_sort.'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_struct_paste_level'];
                        $a .= " [".$an."]\"><i class=\"fa-solid fa-arrow-down\" aria-hidden=\"true\"></i></a>";
                    } elseif($copy_id == !$forbid_copy) {
                        $a .= "<a class=\"btn btn-xs btn-danger me-1\" href=\"phpwcms.php?do=articles\" title=\"";
                        $a .= $GLOBALS['BL']['be_func_content_paste_cancel']; //." [".$at."]";
                        $a .= "\"><i class=\"fa-solid fa-times fa-fw\"></i></a>";
                    }
                }

                //Sortierungslink
                $sort_up        = (($count_row>1 && $key)?1:0);
                $sort_down      = (($count_row>1 && $key+1<$count_row)?1:0);

                $a .= '<div class="btn-group" role="group" aria-label="group'.$struct[$key]["acat_id"].'">';

                $a .= '<a class="btn btn-xs btn-blue" role="button" title="'.$GLOBALS['BL']['be_func_struct_sedit'].' ['.$an.']" data-bs-toggle="tooltip" href="phpwcms.php?do=articles&amp;p=6&amp;struct=';
                if($struct[$key]["acat_id"]) {
                    $a .= $struct[$key]["acat_struct"]."&amp;cat=".$struct[$key]["acat_id"];
                } else {
                    $a .= 'index';
                }
                $a .= '"><i class="fa-solid fa-pencil-alt fa-fw"></i></a>';

                $a .= '<div class="btn-group" role="group">';
                $a .= '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" href="#" id="dropdownStrucLink'.$struct[$key]["acat_id"].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';

                $a .= '<div class="dropdown-menu" aria-labelledby="dropdownStrucLink'.$struct[$key]["acat_id"].'">';
                $a .= '<h6 class="dropdown-header">'.$an.'</h6>';
                $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;p=6&amp;struct='.$struct[$key]["acat_id"].'&amp;sort='.$child_sort.'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_struct_insert_level'].' ['.$an.']"><i class="fa-solid fa-plus fa-fw" aria-hidden="true"></i> <i class="fa-solid fa-folder fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_insert_level_short'].'</a>';
                 $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;p=1&amp;struct='.$struct[$key]["acat_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_func_struct_new_article'].'"><i class="fa-solid fa-plus fa-fw" aria-hidden="true"></i> <i class="fa-solid fa-file fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_new_article_short'].'</a>';
                //copy structur
                if($struct[$key]["acat_id"]) {
                  $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;cop='.$struct[$key]["acat_id"].'"><i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_copy_level'].'</a>';
                }
                //cut structur
                if($struct[$key]["acat_id"]) {
                  $a .= '<a class="dropdown-item" href="phpwcms.php?do=articles&amp;cut='.$struct[$key]["acat_id"].'"><i class="fa-solid fa-cut disabled fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_cut_level'].'</a>';
                }
                //sort structur up
                if($sort_up) {
                  $a .= '<a class="dropdown-item" href="include/inc_act/act_structure.php?do=2'.'%7C'.$struct[$key]["acat_id"].'%7C'.($key*10).'%7C'.$struct[$key-1]["acat_id"].
                '%7C'.(($key+1)*10).'"><i class="fa-solid fa-caret-up fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_sort_up'].'</a>';
                }
                //sort structur down
                if($sort_down) {
                  $a .= '<a class="dropdown-item" href="include/inc_act/act_structure.php?do=2'.'%7C'.$struct[$key]["acat_id"].'%7C'.(($key+2)*10).'%7C'.$struct[$key+1]["acat_id"].
                '%7C'.(($key+1)*10).'"><i class="fa-solid fa-caret-down fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_sort_down'].'</a>';
                }
                //delete structur
                if($struct[$key]["acat_id"]) {
                  $a .= '<a class="dropdown-item" href="include/inc_act/act_structure.php?do=9'.'%7C'.$struct[$key]["acat_id"].'"';
                  $a .= " onclick=\"return confirm('".$GLOBALS['BL']['be_func_struct_del_struct']." \\n[".js_singlequote($an)."] ?')\">";
                  $a .= '<i class="fa-regular fa-trash-alt fa-fw" aria-hidden="true"></i> '.$GLOBALS['BL']['be_func_struct_del_struct'].'</a>';
                }

                $a .= '</div></div>'.LF;

                $a .= '<button id="abtnstruct'.$struct[$key]["acat_id"].'" class="btn fa btn-xs visible '.($struct[$key]["acat_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$struct[$key]["acat_id"].'" data-type="struct" data-table="articlecat" data-field="acat_aktiv" data-fieldid="acat_id" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cactivefile'].'"><i class="fa-solid '.($struct[$key]["acat_aktiv"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';

                $a .= '</div>'.LF;
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

    if(!$data['data']['aid'] && !$data['data']['alias'] && $data['data']['id'] == '' && !isset($_POST['delete_'.md5((string) $data['data']['rid'])])) {
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
            if(isset($_POST['delete_'.md5((string) $rid)])) {
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
