<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

/**
 * Sept. 2009
 * enhancement to enable phpwcms filebrowser support in FCK Editor
 * based on concept and work of Markus Köhl <www.pagewerkstatt.ch>
 *
 * April 2011
 * - enhancement to enable phpwcms filebrowser support in CKEditor
 *   based on concept and work of Markus Köhl <www.leanux.ch>
 * - Issue 265 based on TB's post
 */

session_start();

$phpwcms			= array();
$phpwcms_root		= rtrim(str_replace('\\', '/', dirname(__FILE__)), '/');
$js_files_all		= array();
$js_files_select	= array();

require_once $phpwcms_root.'/include/config/conf.inc.php';
require_once $phpwcms_root.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';

if( empty($_SESSION["wcs_user_lang"]) ) {

	session_destroy();
	headerRedirect( PHPWCMS_URL );

} else {

	require PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
	require PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.ext.inc.php';
	$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
	if(is_file($cust_lang)) {
		include $cust_lang;
	}
	$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.ext.inc.php';
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

switch($js_aktion) {
	case 1:  $js  = "window.opener.document.newsform.cnt_link.value";
			  break;

	case 2:  $js  = "window.opener.document.article.article_lang_id.value";
			  break;
			  
	case 3:  $js  = "window.opener.document.editsitestructure.acat_lang_id.value";
			  break;

	case 4:  $js  = "window.opener.document.articlecontent." . $field . ".value";
			  break;
}

require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

checkLogin();

require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title><?php echo $titel ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />

	<link href="include/inc_css/phpwcms.min.css" rel="stylesheet" type="text/css" />
	<script src="include/inc_js/phpwcms.js" type="text/javascript"></script>
	<script src="include/inc_js/jquery/jquery.min.js" type="text/javascript"></script>
</head>
<body class="filebrowser">

<table summary="" border="0" cellspacing="0" cellpadding="0" style="margin:4px;">
  <tr>
	<td bgcolor="#7C98A2"><img src="img/leer.gif" alt="" width="1" height="1" border="0" /></td>
  </tr>
  <tr>
    <td bgcolor="#7C98A2" class="msgreiter">&nbsp;<?php echo $BL['be_article_title'] ?>&nbsp;</td>
  </tr>
  <tr>
		<td bgcolor="#7C98A2"><img src="img/leer.gif" alt="" width="1" height="1" border="0" /></td>
  </tr>
  <tr>
    <td valign="top"><table summary="" bgColor="#FCFDFD" border="0" cellspacing="0" cellpadding="0"><?php
struct_list(0, $db, 0, 0, 0, 0, 0, 0, $listmode=0, $forbid_cut=0, $forbid_copy=0, $counter=0, $js);
	?></table></td>

  </tr>
</table>

</body>
</html>
<?php

function struct_list($id, $dbcon, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode=1, $forbid_cut=0, $forbid_copy=0, $counter=0, $js) {

	$counter++;
	$sql  = "SELECT t1.*, t2.template_default, t2.template_name, t2.template_trash FROM ".DB_PREPEND."phpwcms_articlecat t1 ";
	$sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_template t2 ON t1.acat_template=t2.template_id ";
	$sql .= "WHERE acat_trash=0 AND acat_struct=".intval($id)." ORDER BY acat_sort";
	if($result = mysql_query($sql, $dbcon) or die("error while browsing structure".$sql)) {
		$count_row = 0;
		while($row = mysql_fetch_assoc($result)) {
			$struct[$count_row] = $row;
			$count_row++;
		}
		mysql_free_result($result);

		if(isset($struct[0])) {
			foreach($struct as $key => $value) {
				struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row, $dbcon, $js);
			}
		}
	}
}

function struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $forbid_cut, $forbid_copy, $listmode, $cut_article, $count_row, $dbcon, $js) {

	global $BL;

	$page_val		= ($listmode) ? "do=admin&amp;p=6" : "do=articles";
	$child_count	= get_root_childcount($struct[$key]["acat_id"], $dbcon);
	$child_sort		= (($child_count+1)*10);

	$forbid_cut		= ($struct[$key]["acat_struct"] == $cut_id || $forbid_cut) ? 1 : 0;
	$forbid_copy	= ($struct[$key]["acat_struct"] == $copy_id || $forbid_copy) ? 1 : 0;

	$an = html($struct[$key]["acat_name"]);
	$a  = "<tr onmouseover=\"this.bgColor='#CCFF00';\" onmouseout=\"this.bgColor='#FFFFFF';\">\n";
	$a .= '<td width="461">';
	$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
	$a .= '<td nowrap="nowrap"><img src="img/leer.gif" width="'.(14+(14*($counter-1)))."\" height=\"1\" alt=\"\" />"; //14
	$a .= ($child_count) ? "<a href=\"articlebrowser.php?".$page_val."&amp;open=".rawurlencode($struct[$key]["acat_id"].":".((!empty($_SESSION["structure"][$struct[$key]["acat_id"]]))?0:1))."\">" : "";
	$a .= "<img src=\"img/symbole/plus_".(($child_count) ? ((!empty($_SESSION["structure"][ $struct[$key]["acat_id"] ])) ? "close" : "open") : "empty");
	$a .= ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" />".(($child_count) ? "</a>" : "");
	$a .= "</td>\n";
	$a .= "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>\n";
	$a .= '<td class="dir" width="95%"><strong><a href="#" onclick="' . $js . '='.$struct[$key]["acat_id"].';window.close()" title="'.$BL['be_func_struct_preview'].': '.$an.'">';
	$a .= $an . "</a></strong></td>\n</tr>\n</table></td>\n</tr>\n";
	echo $a;

	if(isset($_SESSION["structure"][$struct[$key]["acat_id"]]) && $_SESSION["structure"][$struct[$key]["acat_id"]]) {

		if(!$listmode) {
			struct_articlelist($struct[$key]["acat_id"], $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $struct[$key]["acat_order"], $js);
		}

		struct_list($struct[$key]["acat_id"], $dbcon, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode, $forbid_cut, $forbid_copy, $counter, $js);

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

function struct_articlelist($struct_id, $counter, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $article_order=0, $js) {

	global $BL;

	$article			= array();	// empty article array
	$sort_array			= array();	// empty array to store all sort values for the category
	$article_order		= intval($article_order);
	$max_article_count	= 0;
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

		// count up for article array index
		$count_article++;

		$at = html($article[$akey]["article_title"]);
		$a = "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
		$a .= '<td width="461">';
		$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
		$acontent_count =  get_article_content_count($article[$akey]["article_id"], $GLOBALS['db']);
		$a .= "<td nowrap=\"nowrap\"><img src=\"img/leer.gif\" width=\"".(14+29+(14*($counter-1)))."\" height=\"1\" alt=\"\" /></td>\n";
		$a .= "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>\n";
		$a .= '<td class="dir" width="95%"><img src="img/symbole/text_1.gif" width="11" height="15" alt="" /><a href="#" onclick="' . $js . '='.$article[$akey]["article_id"].';window.close()" title="'.$BL['be_func_struct_preview'].': '.$at.'">';
		$a .= $at."</a></td>\n</tr>\n</table></td>\n</tr>\n";
		echo $a;

		$sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."phpwcms_articlecontent ";
		$sql .= "WHERE acontent_aid=".$article[$akey]["article_id"]." ORDER BY acontent_block, acontent_sorting, acontent_id";
		if($result = mysql_query($sql, $GLOBALS['db']) or die("error while listing contents for this article")) {
			$sc = 0; $scc = 0; //Sort-Zwischenzaehler
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
		

		if(isset($_SESSION["structure"]["article"][$article[$akey]["article_id"]]) && $_SESSION["structure"]["article"][$article[$akey]["article_id"]]) {
			struct_articlecontentlist ($article, $akey, $copy_article_content, $cut_article_content, $counter, $sbutton_string, $GLOBALS['db']);
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
			$a .= "<td width=\"".$gk."\"><img src=\"img/leer.gif\" width=\"".$gk."\" height=\"1\" alt=\"\" /></td>";	//$counter-1
			$a .= "<td width=\"13\"><img src=\"img/symbole/content_9x11.gif\" width=\"9\" height=\"11\" border=\"0\" alt=\"\" onmouseover=\"".$info."\" /></td>";
			$a .= "<td class=\"v09\" style=\"color:#727889;padding:1px 0 1px 0;width:".(538-$gk-14-15-77-98)."px;\" onmouseover=\"".$info."\">";

			$ab  = '[ID:'.$article_content["acontent_id"].'] ';
			$ab .= $GLOBALS["wcs_content_type"][$article_content["acontent_type"]];
			if($article_content["acontent_type"] == 30) {
				$ab .= ': '.$GLOBALS['BL']['modules'][$article_content["acontent_module"]]['listing_title'];
			}

			$a .= $ab;

			$a .= "</td>";
			$a .= "<td width=\"16\"><img src=\"img/symbole/block.gif\" width=\"9\" height=\"11\" border=\"0\" alt=\"\" style=\"margin:0 3px 0 3px;\" /></td>";
			$a .= "<td class=\"v09\" style=\"color:#727889;\" width=\"102\">".html(' {'.$article_content['acontent_block'].'} ')."</td>";
			$a .= '<td nowrap="nowrap" style="padding:1px 0 1px 0;width:77px;white-space:nowrap;" onmouseover="'.$info.'">';
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

function on_off($wert, $string, $art = 1) {
	//Erzeugt das Status-Zeichen für Klapp-Auf/Zu
	//Wenn Art = 1 dann als Zeichen, ansonsten als Bild
	if($wert) {
		if($art == 1) {
			return "+";
		} else {
			return "<img src=\"img/symbols/klapp_zu.gif\" border=\"0\" alt=\"\" title=\"".$GLOBALS['BL']['OPEN_DIR'].": ".$string."\" />";
		}
	} else {
		if($art == 1) {
			return "-";
		} else {
			return "<img src=\"img/symbols/klapp_auf.gif\" border=\"0\" alt=\"\" title=\"".$GLOBALS['BL']['CLOSE_DIR'].": ".$string."\" />";
		}
	}
}

function true_false($wert) {
	//Wechselt den Wahr/Falsch wert zum Gegenteil: 1=>0 und 0=>1
	if(intval($wert)) { return 0; } else { return 1; }
}
