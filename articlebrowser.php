<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo            = array('SESSION_START' => true);
$cmsgo_root       = rtrim(str_replace('\\', '/', dirname(__FILE__)), '/');
$js_files_all       = array();
$js_files_select    = array();

require_once $cmsgo_root.'/include/config/conf.inc.php';
require_once $cmsgo_root.'/include/config/conf.indexpage.inc.php';
require_once $cmsgo_root.'/include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';

if( empty($_SESSION["wcs_user_lang"]) ) {

    $_SESSION = array();
    @session_destroy();
    headerRedirect(CMSGO_URL, 401);

} else {

    require CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
    require CMSGO_ROOT.'/include/inc_lang/backend/en/lang.ext.inc.php';
    $cust_lang = CMSGO_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
    if(is_file($cust_lang)) {
        include $cust_lang;
    }
    $cust_lang = CMSGO_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.ext.inc.php';
    if(is_file($cust_lang)) {
        include $cust_lang;
    }

}

if(isset($_GET["open"])) {
    list($open_id, $open_value) = explode(":", $_GET["open"]);
    $open_id = intval($open_id);
    if(empty($open_value)) {
        unset($_SESSION["structure"][$open_id]);
    } else {
        $_SESSION["structure"][$open_id] = $open_value;
    }
}

$js_aktion = (isset($_GET["opt"])) ? intval($_GET["opt"]) : 1;
$field = (isset($_GET["field"])) ? $_GET["field"] : 'id';
if(isset($_GET['CKEditorFuncNum'])) {
    $ckeditor_action = intval($_GET['CKEditorFuncNum']);
    $_SESSION['CKEditorFuncNum'] = $ckeditor_action;
} elseif (!empty($_SESSION['CKEditorFuncNum'])) {
    $ckeditor_action = $_SESSION['CKEditorFuncNum'];
} else {
    $ckeditor_action = 0;
}

switch($js_aktion) {
    case 1:  $js  = "parent.document.newsform.cnt_link.value";
    break;

    case 2:  $js  = "parent.document.article.article_lang_id.value";
    break;

    case 3:  $js  = "parent.document.editsitestructure.acat_lang_id.value";
    break;

    case 4:  $js  = "parent.document.articlecontent." . $field . ".value";
    break;

    case 5:  $js  = "parent.document.articlecontent.calias.value";
    break;

    case 6:  $js  = "parent.document.articlecontent." . $field . ".value";
    break;

    //CKEditor
    case 16: $js  = "window.opener.CKEDITOR.tools.callFunction(".$ckeditor_action.", 'index.php?%s');window.close();";
    break;
}

require_once CMSGO_ROOT.'/include/inc_lib/article.contenttype.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';

checkLogin();

require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <title><?php echo $titel ?></title>

  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CMSGO_CHARSET ?>" />

  <link href="include/inc_css/cmsgo.min.css" rel="stylesheet" type="text/css" />
  <link href="include/inc_css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="include/inc_css/cmsgo-fontawesome.css" rel="stylesheet" type="text/css">
  <link href="include/inc_css/cmsgospecial.min.css" rel="stylesheet" type="text/css">

  <script src="include/inc_js/jquery/jquery.min.js"></script>
  <script src="include/inc_js/autosize.min.js"></script>
  <script src="include/inc_js/cmsgo.js"></script>
  <script src="include/inc_js/bootstrap.bundle.min.js"></script>
  <script src="include/inc_js/cmsgo-addons.js"></script>

  <?php if ($js_aktion == 16) { ?>
  <script type="text/javascript">
  var dialog = window.opener.CKEDITOR.dialog.getCurrent();
  var docIdField = dialog.getContentElement( 'info', 'protocol' );
  docIdField.setValue( '' );
  </script>
    <?php } ?>
</head>
<body class="filebrowser">
<ul class="nav nav-tabs border-0 my-2">
  <li role="presentation" class="nav-item">
      <a href="#" class="btn btn-blue mr-2"><?php echo $BL['be_article_title'] ?></a>
  </li>
<?php if ($js_aktion == 16) { ?>
  <li role="presentation" class="nav-item">
      <a href="filebrowser.php?opt=16" class="btn btn-blue"><?php echo $BL['FILE_TITLE'] ?></a>
  </li><?php } ?>
</ul>

<table summary="" class="table table-sm" border="0" cellspacing="0" cellpadding="0">
<?php

$child_count = get_root_childcount(0);
$an = $indexpage['acat_name'];

$a  = "<tr bgcolor=\"#e8e8e8\" onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#e8e8e8';\">\n";
$a .= '<td>';
$a .= "<table class=\"table-no-border\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
$a .= '<td nowrap="nowrap">';
$a .= ($child_count) ? "<a href=\"cmsgo.php?do=articles&amp;open=0:".(($_SESSION["structure"][0])?0:1)."\">" : "";

$a .= '<i class="fa fa-caret-'.(($child_count) ? (($_SESSION["structure"][0]==0) ? "right" : "down") : "right");
$a .= ' fa-fw" aria-hidden="true"></i>'.(($child_count) ? "</a>" : "");

$info  = '<table class="text-left"><tr><td>ID:</td><td><b>0</b></td></tr>';
$info .= '<tr><td>ALIAS:</td><td>'.$indexpage["acat_alias"].'</td></tr></table>';

$a .= '<i class="far fa-folder fa-fw" aria-hidden="true" data-toggle="tooltip" data-html="true" title="'.html($info).'"></i>';

$a .= "</td>\n";
$a .= '<td width="97%"><strong>'.$an."</strong></td>\n</tr>\n</table></td>\n";

echo $a;

struct_articlelist(0, 0, 0, 0, 0, 0, $indexpage['acat_order'], $js, $js_aktion);
struct_list(0, 0, 0, 0, 0, 0, 0, $listmode=0, $forbid_cut=0, $forbid_copy=0, $counter=0, $js, $js_aktion);
?></table>

<script>
$(document).ready(function(){
  $('.structarticle').click(function() {

    <?php
    echo $js ."=$(this).attr('data-aid');";
    if ($js_aktion == 6) {
      echo "parent.$('#browserModal').modal('hide');";
     } elseif ($js_aktion == 2) {
      echo "parent.$('input:radio[name=\"article_lang_type\"][value=\"'+$(this).attr('data-idtype')+'\"]').attr('checked',true);";
      echo "parent.$('#browserModal').modal('hide');";
    } elseif ($js_aktion != 16) {
      echo "parent.$('input:radio[name=\"acat_lang_type\"][value=\"'+$(this).attr('data-idtype')+'\"]').attr('checked',true);";
      echo "parent.$('#browserModal').modal('hide');";
    }
    ?>
   });
});
</script>
</body>
</html>
<?php

function struct_list($id, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode=1, $forbid_cut=0, $forbid_copy=0, $counter=0, $js, $js_aktion) {

  $counter++;
  $sql  = "SELECT t1.*, t2.template_default, t2.template_name, t2.template_trash FROM ".DB_PREPEND."cmsgo_articlecat t1 ";
  $sql .= "LEFT JOIN ".DB_PREPEND."cmsgo_template t2 ON t1.acat_template=t2.template_id ";
  $sql .= "WHERE acat_trash=0 AND acat_struct=".intval($id)." ORDER BY acat_sort";
  $result = _dbQuery($sql);

  if(isset($result[0]['acat_struct'])) {
    $count_row = 0;
    foreach($result as $row) {
      $struct[$count_row] = $row;
      $count_row++;
    }

    if(isset($struct[0])) {
      foreach($struct as $key => $value) {
        struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row, $js, $js_aktion);
      }
    }
  }
}

function struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row, $js, $js_aktion) {

  global $BL;

  $page_val   = ($listmode) ? "do=articles&amp;p=6" : "do=articles";
  $child_count  = get_root_childcount($struct[$key]["acat_id"]);
  $child_sort   = (($child_count+1)*10);

  $forbid_cut   = 0;
  $forbid_copy  = 0;

  $an = html($struct[$key]["acat_name"]);
  $a  = "<tr onmouseover=\"this.bgColor='#CCFF00';\" onmouseout=\"this.bgColor='#FFFFFF';\">\n";
  $a .= '<td width="80%">';
  $a .= '<table class="table-no-border"'.">\n<tr>\n";
  $a .= '<td nowrap="nowrap" class="text-right">';
  $a .= ($child_count) ? "<a href=\"articlebrowser.php?opt=".$js_aktion."&amp;".$page_val."&amp;open=".rawurlencode($struct[$key]["acat_id"].":".((!empty($_SESSION["structure"][$struct[$key]["acat_id"]]))?0:1))."\">" : "";
  $a .= '<i class="fa fa-caret-'.(($child_count) ? ($_SESSION["structure"][ $struct[$key]["acat_id"] ]==0 ? "right" : "down") : "right").' fa-fw slist-'.$counter.'" aria-hidden="true"></i>'.(($child_count) ? "</a>" : "");

  $info  = '<table class="text-left">';
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
  $info .= '<tr><td>'.$BL['be_onepage_id'].':</td><td>'.($struct[$key]["acat_onepage"] ? $BL['be_yes'] : $BL['be_no']) . '</td></tr></table>';

  $a .= '<i class="far fa-folder';
  if($struct[$key]["acat_regonly"]) {
      $a .= '-open';
  }
  $a .= ' fa-fw" aria-hidden="true" data-toggle="tooltip" data-html="true" title="'.html($info).'"></i>';
  $a .= "</td>\n";
  $a .= '<td width="95%"><strong>';
  if ($js_aktion == 5) {
      $a .= $an;
  } elseif ($js_aktion == 16) {
    $a .= '<a href="#" onclick="'.str_replace('%s','id='.$struct[$key]["acat_id"],$js).'" title="">'.$an . '</a>';
  } else {
    $a .= '<a href="#" class="structarticle" data-aid="'.($js_aktion == 6 ? 'id=' : '').$struct[$key]["acat_id"].'" data-idtype="category" title="">'.$an ;
    $a .= '<span class="ml-3">'.$struct[$key]['acat_lang'].'</span></a>';
  }
  $a .= "</strong></td>\n</tr>\n</table></td>\n</tr>\n";
  echo $a;

  if(isset($_SESSION["structure"][$struct[$key]["acat_id"]]) && $_SESSION["structure"][$struct[$key]["acat_id"]]) {

    if(!$listmode) {
      struct_articlelist($struct[$key]["acat_id"], $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $struct[$key]["acat_order"], $js, $js_aktion);
    }
    struct_list($struct[$key]["acat_id"], $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode, $forbid_cut, $forbid_copy, $counter, $js, $js_aktion);

  }
}

function get_root_childcount($id) {

    // get amount of active child levels
    $id = intval($id);

    $p1_count = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_articlecat WHERE acat_trash=0 AND acat_struct=".$id, 'COUNT');
    $p2_count = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_article WHERE article_deleted=0 AND article_cid=".$id, 'COUNT');

    return $p1_count + $p2_count;

}

function get_article_content_count($id) {

    return _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_articlecontent WHERE acontent_trash=0 AND acontent_aid=".intval($id), 'COUNT');

}

function struct_articlelist($struct_id, $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $article_order=0, $js, $js_aktion) {

  global $BL;

  $article      = array();  // empty article array
  $sort_array     = array();  // empty array to store all sort values for the category
  $article_order    = intval($article_order);
  $max_article_count  = 0;
  $ao         = get_order_sort($article_order);
  $count_article    = 0;
  $sbutton_string   = array();

  $sql  = "SELECT *, ";
  $sql .= "DATE_FORMAT(article_tstamp, '%Y-%m-%d %H:%i:%s') AS article_date "; //, article_deleted
  $sql .= "FROM ".DB_PREPEND."cmsgo_article ";
  $sql .= "WHERE article_cid='".$struct_id."' AND article_deleted=0 ORDER BY ".$ao[2];
  $result = _dbQuery($sql);
  if(isset($result[0]['article_date'])) {

    // 1st get max count to know the last index ($max_article_count - 1)
    $max_article_count = count($result);

    // take all entryies and build new array with it
    foreach($result as $row) {
      $article[$count_article]  = $row;
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

    // count up for article array index
    $count_article++;

    $at = html($article[$akey]["article_title"]);
    $a = "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
    $a .= '<td width="100%">';
    $a .= "<table class=\"table-no-border\" summary=\"\">\n<tr>\n";
    $acontent_count =  get_article_content_count($article[$akey]["article_id"]);
    $a .= "<td nowrap=\"nowrap\">\n";
    $a .= "<i class=\"fa fa-caret-".(($acontent_count) ? ((!empty($_SESSION["structure"]["article"][ $article[$akey]["article_id"] ])) ? "down" : "right") : "right");
    $a .= ' fa-fw alist-'.($counter).'" aria-hidden="true"></i>'.(($acontent_count) ? "</a>" : "");

    $info  = '<table class="text-left">';
    $info .= '<tr><td>'.$BL['be_func_struct_articleID'].':</td><td><b>'.$article[$akey]["article_id"].'</b></td></tr>';
    if(!empty($article[$akey]["article_alias"])) {
        $info .= '<tr><td>ALIAS:</td><td><b>'.$article[$akey]["article_alias"].'</b></td></tr>';
    }
    if(!empty($article[$akey]["article_begin"])) {
        $info .= '<tr><td>'.$BL['be_article_cnt_start'].':</td><td><b>';
        $info .= $article[$akey]["article_begin"] === '0000-00-00 00:00:00' ? $BL['be_not_set'] : cmsgo_strtotime($article[$akey]["article_begin"], $BL['be_longdatetime'], '&nbsp;');
        $info .= '</b></td></tr>';
    }
    if(!empty($article[$akey]["article_end"])) {
        $info .= '<tr><td>'.$BL['be_article_cnt_end'].':</td><td><b>';
        $info .= $article[$akey]["article_end"] === '0000-00-00 00:00:00' ? $BL['be_not_set'] : cmsgo_strtotime($article[$akey]["article_end"], $BL['be_longdatetime'], '&nbsp;');
        $info .= '</b></td></tr>';
    }
    $info .= '<tr><td>'.$BL['be_cnt_sortvalue'].':</td><td>'.$article[$akey]["article_sort"].'</td></tr>';
    if(isset($article[$akey]["article_end"])) {
        $info .= '<tr><td>'.$BL['be_priorize'].':</td><td>'.$article[$akey]["article_priorize"].'</td></tr>';
    }
    $info .= '</table>';

    $a .= '<i class="far fa-file fa-fw" aria-hidden="true" data-html="true" data-toggle="tooltip" title="'.html($info).'"></i>'."\n";

    if ($js_aktion == 5) {
      $a .= $at;
    } elseif ($js_aktion == 16) {
      $a .= '<a href="#" onclick="' . str_replace('%s','aid='.$article[$akey]["article_id"],$js).'" title="">'.$at.'</a>';
    } else {
      $a .= '<a href="#"  class="structarticle" data-aid="'.($js_aktion == 6 ? 'aid=' : '').$article[$akey]["article_id"].'" data-idtype="article" title="">'.$at;
      $a .= '<span class="ml-3">'.$article[$akey]["article_lang"].'</span></a>';
    }
    $a .= "</td>\n</tr>\n</table></td>\n</tr>\n";
    echo $a;

    $sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."cmsgo_articlecontent ";
    $sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." ORDER BY acontent_block, acontent_sorting, acontent_id";
    $result = _dbQuery($sql);
    //Sort counter
    $sc = 0;
    $scc = 0;

    if(isset($result[0]['acontent_id'])) {
      foreach($result as $row) {
        $scc++;
        if($row[2] == 0) {
          $sc++;
          $sbutton[$sc]["id"]    = $row[0];
          $sbutton[$sc]["sort"]  = $row[1];
          $sbutton[$sc]["block"] = $row[3];
        }
      }
    }

    if($js_aktion == 5) {
      struct_articlecontentlist ($article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string);
    }
  }
}

function struct_articlecontentlist(& $article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string){

  $a    = '';

  $sql  = "SELECT * FROM ".DB_PREPEND."cmsgo_articlecontent ";
  $sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." AND acontent_trash=0 ";
  $sql .= "ORDER BY acontent_block, acontent_sorting, acontent_id";

  $result = _dbQuery($sql);
  if(isset($result[0]['acontent_aid'])) {

    foreach($result as $article_content) {
      // if type of content part not enabled available
      if(!isset($wcs_content_type[ $article_content["acontent_type"] ]) || ($article_content['acontent_type'] == 30 && !isset($GLOBALS['cmsgo']['modules'][$article_content["acontent_module"]]))) {
        //continue;
      }

      $info = '<table class="text-left">';
      $info .= '<tr><td>ID:</td><td>'.$article_content["acontent_id"].'</td></tr>';
      if($article_content['acontent_title']) {
        $info .= '<tr><td>' . $GLOBALS['BL']['be_article_cnt_ctitle'].':</td><td>'.$article_content['acontent_title'].'</td></tr>';
      }
      if($article_content['acontent_title']) {
        $info .= '<tr><td>' . $GLOBALS['BL']['be_article_asubtitle'].':</td><td>'.$article_content['acontent_subtitle'].'</td></tr>';
      }
      if($article_content["acontent_comment"]) {
        $info .= '<tr><td colspan="2">' . nl2br($article_content["acontent_comment"]) . '</td></tr>';
      }
      $info .= '</table>';

      $a .= "<tr onmouseover=\"this.bgColor='#FFDE01';\" onmouseout=\"this.bgColor='#FFFFFF';\"  class=\"structarticle\" data-aid=\"".$article_content['acontent_id']."\" data-idtype=\"acontent\">\n";

      $a .= '<td width="30"><i class="far fa-list-alt fa-fw aclist-'.($counter).'" aria-hidden="true" data-toggle="tooltip" data-html="true" title="'.html($info).'" /></td>';
      $a .= '<td class="" style="color:#727889;width: 60%">';
      $ab  = '[ID:'.$article_content["acontent_id"].'] ';
      $ab .= $article_content["acontent_title"].' - ';
      $ab .= $GLOBALS["wcs_content_type"][$article_content["acontent_type"]];
      if($article_content["acontent_type"] == 30) {
        $ab .= ': '.$GLOBALS['BL']['modules'][$article_content["acontent_module"]]['listing_title'];
      }

      $a .= $ab;
      $a .= "</td>";

      $a .= "<td style=\"color:#727889;\" width=\"102\">".html(' {'.$article_content['acontent_block'].'} ')."</td>";
      $a .= '<td nowrap="nowrap" style="padding:1px 0 1px 0;width:77px;white-space:nowrap;" onmouseover="'.$info.'">';
      $a .= "</td>\n</tr>";
    }

    if($a) {
      $aa  = "<tr>\n<td colspan=\"2\" class=\"p-0\" >";
            $aa .= "<table class=\"table-no-border\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\" width=\"100%\">\n";
      $aa .= $a;
      $aa .= "</table></td></tr>";
      echo $aa;
    }
  }
}
