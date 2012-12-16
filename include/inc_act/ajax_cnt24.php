<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// general wrapper for ajax based queries, cnt24

session_start();
$phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../../include/inc_lib/default.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/admin.functions.inc.php');

require_once PHPWCMS_ROOT.'/config/phpwcms/conf.indexpage.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php';

// check against user's language
if(!empty($_SESSION["wcs_user_lang"]) && preg_match('/[a-z]{2}/i', $_SESSION["wcs_user_lang"])) {
	$BE['LANG'] = $_SESSION["wcs_user_lang"];
}
//load default language EN
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
include_once PHPWCMS_ROOT."/include/inc_lang/code.lang.inc.php";

$BL['modules']				= array();

if(!empty($_SESSION["wcs_user_lang_custom"])) {
	//use custom lang if available -> was set in login.php
	$BL['merge_lang_array'][0]		= $BL['be_admin_optgroup_label'];
	$BL['merge_lang_array'][1]		= $BL['be_cnt_field'];
	include PHPWCMS_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.inc.php';
	$BL['be_admin_optgroup_label']	= array_merge($BL['merge_lang_array'][0], $BL['be_admin_optgroup_label']);
	$BL['be_cnt_field']				= array_merge($BL['merge_lang_array'][1], $BL['be_cnt_field']);
	unset($BL['merge_lang_array']);
}
// check modules
require_once PHPWCMS_ROOT.'/include/inc_lib/modules.check.inc.php';
// load array with actual content types
include PHPWCMS_ROOT.'/include/inc_lib/article.contenttype.inc.php';


if(empty($_SESSION["wcs_user"])) {
	die('Sorry, access forbidden');
}


//output article listing for given structid
$output_article_listing = "";
//do we have a var?
if (isset($_POST['idstruct'])) {

    $structid = intval($_POST['idstruct']);

  	//Auslesen der kompletten Public Artikel pro Struktur s
	  $sql  = "SELECT ".DB_PREPEND."phpwcms_article.article_id, ".DB_PREPEND."phpwcms_article.article_title ";
		$sql .= "FROM ".DB_PREPEND."phpwcms_article ";
		$sql .= "WHERE ".DB_PREPEND."phpwcms_article.article_public = 1 AND ".DB_PREPEND."phpwcms_article.article_deleted = 0 AND ".DB_PREPEND."phpwcms_article.article_cid = ".$structid." ";
		$sql .= "ORDER BY ".DB_PREPEND."phpwcms_article.article_title;";
	  $result = mysql_query($sql)
      or die("error while reading complete article/articlecategory list");
    $i=0;
    while ($row = mysql_fetch_array($result)) {
			$catList['id'][$i] = $row['article_id'];
			$catList['title'][$i] = $row['article_title'];
      $i++;
		}
    //start output
    $output_article_listing = '<select name="ca_articleid" id="ca_articleid" onchange="sendRequestArt()" class="f11b width300">';
    $output_article_listing .= '<option value="0">none</option>'."\n";
      for ($i=0;$i<count($catList['id']);$i++) {
        $output_article_listing.='<option value="'.$catList['id'][$i].'">';
          //no selected because list changes on every event
        $output_article_listing.=$catList['title'][$i].'</option>'."\n";
      }
    $output_article_listing.='</select>'."\n";

  echo $output_article_listing;

} elseif (isset($_POST['idart'])) {
//list all cp's for a given articleid

  $artid = intval($_POST['idart']);
  $calias = intval($_POST['calias']);

  //Auslesen der kompletten Public Artikel pro Struktur s
	  $sql  = "SELECT * FROM phpwcms_article WHERE article_id=".$artid;
    $data = _dbQuery($sql, 'SELECT');
    if (isset($data[0])) {
      $article = $data[0];
    }

		//Listing zugehöriger Artikel Content Teile
		$sql = 	"SELECT *, UNIX_TIMESTAMP(acontent_tstamp) as acontent_date FROM ".DB_PREPEND."phpwcms_articlecontent ".
				"WHERE acontent_aid=".$artid." AND acontent_trash=0 ".
				"ORDER BY acontent_block, acontent_sorting, acontent_tab, acontent_id;";

		if($result = mysql_query($sql, $db) or die("error while listing contents for this article")) {
			$sortierwert			= 1;
			$contentpart_block		= ' ';
			$contentpart_block_name	= '';
			$contentpart_tab		= '';
	?>
<div class="" style=""><?php

      while($row = mysql_fetch_assoc($result)) {

					// if type of content part not enabled available
					if(!isset($wcs_content_type[ $row["acontent_type"] ]) || ($row["acontent_type"] == 30 && !isset($phpwcms['modules'][$row["acontent_module"]]))) {
						continue;
					}

					// now show current block name
					if($contentpart_block != $row['acontent_block']) {
						$contentpart_block = $row['acontent_block'];
						$contentpart_block_name = html_specialchars(' {'.$row['acontent_block'].'}');
						$contentpart_block_color = ' #E0D6EB';

						switch($contentpart_block) {
							case ''			:	$contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
												$contentpart_block_color = ' #F5CCCC';
												break;
							case 'CONTENT'	:	$contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
												if($article['article_paginate']) {
													$contentpart_block_name .= ' / <img src="img/symbole/content_cppaginate.gif" alt="" style="margin-right:2px;" />';
													$contentpart_block_name .= $BL['be_cnt_pagination'];
												}
												$contentpart_block_color = ' #F5CCCC';
												break;
							case 'LEFT'		:	$contentpart_block_name = $BL['be_cnt_left'].$contentpart_block_name;
												$contentpart_block_color = ' #E0EBD6';
												break;
							case 'RIGHT'	:	$contentpart_block_name = $BL['be_cnt_right'].$contentpart_block_name;
												$contentpart_block_color = ' #FFF5CC';
												break;
							case 'HEADER'	:	$contentpart_block_name = $BL['be_admin_page_header'].$contentpart_block_name;
												$contentpart_block_color = ' #EBEBD6';
												break;
							case 'FOOTER'	:	$contentpart_block_name = $BL['be_admin_page_footer'].$contentpart_block_name;
												$contentpart_block_color = ' #E1E8F7';
												break;
						}
			?>

<div style="width:100%;background-color:<?php echo $contentpart_block_color ?>;">
<img src="img/symbole/block.gif" alt="" width="9" height="11" border="0" />
<span style="font-size:9px;font-weight:bold;"><?php echo  $contentpart_block_name ?></span>
</div><?php

          }

					// now check if content part is tabbed
					if($row['acontent_tab'] && $contentpart_tab != $row['acontent_tab']) {

						$contentpart_tab		= $row['acontent_tab'];

						$contentpart_tabbed		= explode('_', $contentpart_tab, 2);
						$contentpart_tab_title	= empty($contentpart_tabbed[1]) ? '' : $contentpart_tabbed[1];
						$contentpart_tab_number	= empty($contentpart_tabbed[0]) ? 0 : intval($contentpart_tabbed[0]);
						$contentpart_tab_number++;

			?>
<div style="width:100%;background-color:<?php echo $contentpart_block_color ?>;border-bottom:1px solid #D9DEE3;">
<img src="img/symbole/tabbed.gif" alt="" width="9" height="11" border="0" />
<span style="font-size:9px;"><?php
					echo html_specialchars($contentpart_tab_title);
					if(empty($contentpart_tab_title)) {
						echo ' [' . $contentpart_tab_number . ']';
					}
				 ?>&nbsp;</span>
</div><?php

					} elseif($contentpart_tab && empty($row['acontent_tab'])) {

					// not the same tab but following cp is not tabbed
					$contentpart_tab = '';

					}
			?>

<div id="cont_cp_<?php echo $row["acontent_id"] ?>" class="cont_cp" style="<?php if ($calias == $row["acontent_id"]) echo 'background-color:#F3F5F8;'; ?>cursor:pointer;position:relative;margin:3px 0 3px 0;padding:3px 0 3px 0;border-bottom:1px solid #cdcdcd;" onmouseover="this.style.backgroundColor='#F3F5F8';" onmouseout="if ($('calias').value == '<?php echo $row["acontent_id"] ?>' ) { this.style.backgroundColor='#F3F5F8';} else {this.style.backgroundColor='#FFF';}" onclick="bckcol(<?php echo $row["acontent_id"] ?>);$('calias').value = <?php echo intval($row["acontent_id"]) ?>;">
  <div style="position:relative;float:left;">
    <span style="float:left;margin-right:5px;width:12px;"><img src="img/symbole/content_9x11<?php if($row["acontent_granted"]) echo '_granted'; ?>.gif" alt="" width="9" height="11" border="0" /></span>
	  <span style="float:left;width:443px;"><table border="0" cellpadding="0" cellspacing="0" summary="" width="100%">
	            <tr>
	              <td width="150" style="font-size:9px;font-weight:bold;text-transform:uppercase;"><?php

				$cntpart_title = $wcs_content_type[$row["acontent_type"]];
				if(!empty($row["acontent_module"])) {

					$cntpart_title .= ': '.$BL['modules'][$row["acontent_module"]]['listing_title'];

				}
				echo $cntpart_title;


				  ?></td>
	              <td width="23" nowrap="nowrap"></td>
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
	              <td align="right" style="padding-right:1px;">
	               <img src="img/button/visible_11x11a_<?php
	          echo $row["acontent_visible"]
	          ?>.gif" alt="" width="11" height="11" border="0" /></td>


	            </tr>
	  </table></span>
  </div>

  <div style="position:relative;clear:both;">
    <span style=""><table border="0" cellpadding="0" cellspacing="0" summary="" width=""><?php

	// list content type overview
	$cinfo = NULL;
  $string = "";
	//$row["acontent_type"] = intval($row["acontent_type"]); -> it is always INT because coming from db INT field
	// check default content parts (system internals
	if($row['acontent_type'] != 24 && $row['acontent_type'] != 30 && file_exists(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php')) {
  //include(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php');
    $string = get_include_contents(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php');
	} elseif($row['acontent_type'] == 30 && file_exists($phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php')) {

		// custom module
  //include($phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php');
    $string = get_include_contents($phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php');
	} else {

		// default fallback
  //include(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.list.inc.php');
    $string = get_include_contents(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.list.inc.php');
	}
	// end list

  //strip all links
  $pattern = '/\<a\s[^\>]+\>(.*)<\/a\>/i';
  $replacement = '$1';
  echo preg_replace($pattern, $replacement, $string);

?>
    </table></span>
  </div>
</div>
<?php
      } //end while
?>
</div>
<?php
		} //Ende Listing Artikel Content Teile

}


function get_include_contents($filename) {
global $row, $article, $phpwcms, $BL, $db;

    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}

?>