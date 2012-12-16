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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Alias Content

$content['alias_link'] = '<td id="aliasLink1" class="chatlist">&nbsp;</td><td id="aliasLink2" class="f10">&nbsp;</td>';
$ca_aid = 0;
if(empty($content["alias"]['alias_ID'])) {
	$content["alias"]['alias_ID'] = '';
} else {
	$content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
	$sql_cnt  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
	if($cntresult = mysql_query($sql_cnt, $db)) {
		if($cntrow = mysql_fetch_assoc($cntresult)) {
			//http://sommerschule.webverbund.info/
      $ca_aid = $cntrow["acontent_aid"];
			$content['alias_link']  = '<td id="aliasLink1" class="chatlist">&nbsp;&nbsp;&nbsp;'.$BL['be_article_cnt_edit'].':&nbsp;</td><td id="aliasLink2" class="f10">';
			$content['alias_link'] .= '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
			$content['alias_link'] .= $cntrow['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
			$content['alias_link'] .= '" target="_blank" style="text-decoration:underline;color:#000000;">';
			$content['alias_link'] .= $wcs_content_type[$cntrow['acontent_type']].'</a>';
			$content['alias_link'] .= '</td>';
		} else {
			$content["alias"]['alias_ID'] = '';
		}
		mysql_free_result($cntresult);
	} else {
		$content["alias"]['alias_ID'] = '';
	}
}
$content["alias"]['alias_block']	= empty($content["alias"]['alias_block']) ? 0 : 1;
$content["alias"]['alias_spaces']	= empty($content["alias"]['alias_spaces']) ? 0 : 1;
$content["alias"]['alias_title']	= empty($content["alias"]['alias_title']) ? 0 : 1;
$content["alias"]['alias_toplink']	= empty($content["alias"]['alias_toplink']) ? 0 : 1;

?><tr>
<td align="right" class="chatlist"><?php echo $BL['be_alias_ID'] ?>:&nbsp;</td>
<td><table border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td><input name="calias" type="text" class="f11b" id="calias" style="width: 50px" value="<?php echo $content["alias"]['alias_ID'] ?>"></td>
<?php echo $content['alias_link'] ?>
</tr>
</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt=""></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_article_cat']; ?>&nbsp;</td>
  <td><div style="">
<script type="text/javascript">
   function sendRequestCat() {
        new Ajax("include/inc_act/ajax_cnt24.php", {
            method: 'post',
            update: $("cont_article"),
            data: {idstruct:$("ca_structid").value},
            onComplete: function(text) {
                //this.options.data;  // here is your data
                //document.getElementById("googlemaps_articleid").innerHTML = data
            }
        }).request();
    }

   function sendRequestArt() {
        new Ajax("include/inc_act/ajax_cnt24.php", {
            method: 'post',
            update: $("cont_cps"),
            data: {idart:$("ca_articleid").value, calias:<?php if ( isset($content['alias']['alias_ID']) && $content['alias']['alias_ID'] > 0 ) echo $content['alias']['alias_ID'];  else  echo '0'; ?>},
            onComplete: function(text) {
                //this.options.data;  // here is your data
                //document.getElementById("googlemaps_articleid").innerHTML = data
            }
        }).request();
    }

    function bckcol(act_cp_id) {
      $$('.cont_cp').setStyle("background-color", '#FFF');
      $('cont_cp_' + act_cp_id + '').setStyle("background-color", '#F3F5F8');
      $('aliasLink1').innerHTML = '';
      $('aliasLink2').innerHTML = '';
    }
</script>

<?php

  function buildtreeart($structid) {
  	//Auslesen der kompletten Public Artikel pro Struktur
	  $sql  = "SELECT ".DB_PREPEND."phpwcms_article.article_id, ".DB_PREPEND."phpwcms_article.article_title ";
		$sql .= "FROM ".DB_PREPEND."phpwcms_article ";
		$sql .= "WHERE ".DB_PREPEND."phpwcms_article.article_public = 1 AND ".DB_PREPEND."phpwcms_article.article_deleted = 0 AND ".DB_PREPEND."phpwcms_article.article_cid = ".$structid." ";
		$sql .= "ORDER BY ".DB_PREPEND."phpwcms_article.article_title;";
	  $result = mysql_query($sql)
      or die("error while reading complete article list");
    $i=0;
    $catList = array();
    while ($row = mysql_fetch_array($result)) {
			$catList['id'][$i] = $row['article_id'];
			$catList['title'][$i] = $row['article_title'];
      $i++;
		}
  return $catList;
  }

  //Baut das Level Struktur Auswahlmenü
$structid=0;
$articleid=NULL;

if (isset($content["alias"]['alias_ID'])) {
  $sql  = "SELECT article_cid FROM phpwcms_article WHERE article_id=".$ca_aid;
  $data = _dbQuery($sql, 'SELECT');
  if (isset($data[0])) {
    $structid = $data[0]['article_cid'];
  }
}



  echo '<select name="ca_structid" id="ca_structid" onChange="sendRequestCat()" class="f11b width300">';
  echo "<option value='0'".(($structid==0)?" selected='selected'":"").">none</option>\n";
  struct_select_menu(0, 0, $structid);
  echo '</select>';




  $catListArt = buildtreeart($structid);
  $outputArt = '<div id="cont_article"><select name="ca_articleid" id="ca_articleid" onchange="sendRequestArt()" class="f11b width300"><option value="0">none</option>';
	if(isset($catListArt['id'])) {
    for ($i=0;$i<count($catListArt['id']);$i++) {
      $outputArt.='<option value="'.$catListArt['id'][$i].'"';
        if ($catListArt['id'][$i]==$ca_aid) { $outputArt.=' selected="selected" >'; }
        else { $outputArt.=' >'; }
      $outputArt.=$catListArt['title'][$i].'</option>';
    }
  }
  $outputArt.='</select></div>';

?>
    </div></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt=""></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_article_atitle']; ?>&nbsp;</td>
  <td><?php echo $outputArt ?></td>
</tr>
<tr>
  <td></td><td><div id="cont_cps"></div>
<script type="text/javascript">
sendRequestArt();
</script></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt=""></td></tr>
<tr>
<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_setting'] ?>:&nbsp;</td>
<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
<tr>
	<td><input type="checkbox" name="cablock" id="cablock" value="1" <?php is_checked(1, $content["alias"]['alias_block']); ?>></td>
	<td><label for="cablock">&nbsp;<?php echo $BL['be_cnt_block'] ?></label></td>
</tr>
<tr>
	<td><input type="checkbox" name="caspaces" id="caspaces" value="1" <?php is_checked(1, $content["alias"]['alias_spaces']); ?>></td>
	<td><label for="caspaces">&nbsp;<?php echo $BL['be_cnt_spaces'] ?></label></td>
</tr>
<tr>
	<td><input type="checkbox" name="catitle" id="catitle" value="1" <?php is_checked(1, $content["alias"]['alias_title']); ?>></td>
	<td><label for="catitle">&nbsp;<?php echo $BL['be_cnt_title'] ?></label></td>
</tr>
<tr>
	<td><input type="checkbox" name="catop" id="catop" value="1" <?php is_checked(1, $content["alias"]['alias_toplink']); ?>></td>
	<td><label for="catop">&nbsp;<?php echo $BL['be_cnt_toplink'] ?></label></td>
</tr>
</table></td>
<tr><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt=""></td></tr>