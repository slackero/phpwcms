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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


echo '<h1 class="title">', $BL['be_alias'], '</h1>'; //' ('.$BL['be_ftptakeover_active'].

// now retrieve all articles
$sql  = '(';
$sql .=	"	SELECT article_id AS id, article_title AS title, article_alias AS alias, ";
$sql .= "	UNIX_TIMESTAMP(article_tstamp) AS timestamp, 'article' AS type, article_aktiv AS active, ";
$sql .= "	IF(article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()), 0, 1) AS hidden, '' AS struct";
$sql .= "	FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0";
$sql .= ') UNION (';
$sql .=	"	SELECT acat_id AS id, acat_name AS title, acat_alias AS alias, ";
$sql .= "	UNIX_TIMESTAMP(acat_tstamp) AS timestamp, 'category' AS type, acat_aktiv AS active, ";
$sql .= "	acat_hidden AS hidden, acat_struct AS struct";
$sql .= "	FROM ".DB_PREPEND."phpwcms_articlecat WHERE ";
$sql .= "	acat_trash=0";
$sql .= ') ';
$sql .= "ORDER BY alias";

$result = _dbQuery($sql);

?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listing" summary="">

	<tr class="header">
		<th class="column news"><?php echo $BL['be_alias'] ?></th>
		<th class="column">&nbsp;&nbsp;&nbsp;ID&nbsp;</th>
		<th class="column"><?php echo $BL['be_newsletter_changed'] ?></th>
		<th class="column collast"> </th>
	</tr>

<?php

foreach($result as $key => $data) {

	echo '<tr class="row', ($key%2?' alt': ''), '" title="';
	if($data["type"] === 'article') {
		echo $BL['be_cnt_articles'];
		$data['link'] = 'articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id='.$data["id"];
		$data['icon'] = empty($data["hidden"]) ? 'page_6.gif' : 'page_3.gif';
	} else { // category
		echo $BL['be_cnt_sitelevel'];
		$data['link'] = 'admin&amp;p=6&amp;struct='.$data["struct"].'&amp;cat='.$data["id"];
		$data['icon'] = empty($data["hidden"]) ? 'page_1.gif' : 'page_7.gif';
	}
	echo ': ', html($data["title"]), ' [ID:', $data["id"], ']">';
   	echo '<td width="80%" class="colfirst" style="background-image:url(img/symbole/', $data['icon'], ')">';
   	echo (empty($data["alias"]) ? '-' : html($data["alias"])), "&nbsp;</td>";
	echo '<td align="right">', $data["id"], "&nbsp;</td>";
	echo '<td class="nowrap">', date($BL['default_date'], $data["timestamp"]), "</td>";
	echo '<td align="right" width="30"><a href="phpwcms.php?do=', $data['link'], '">';
	echo '<img src="img/button/edit_22x13.gif" alt="" border="0" height="13" width="22" /></a></td></tr>';

}

?>

</table>
