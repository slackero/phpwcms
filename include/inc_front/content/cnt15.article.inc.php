<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
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


//article menu

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$alinkmenu 					= unserialize($crow["acontent_form"]);
$alinkmenu["catid"] 		= ($alinkmenu["cat"]) ? $alinkmenu["catid"] : $content["cat_id"];
$alinkmenu['headertext']	= empty($alinkmenu["headertext"]) ? 0 : 1;
$alinkmenu['ul']			= empty($alinkmenu["ul"]) ? 0 : $alinkmenu["ul"];
$alinkmenu['ul_tag']		= array(1 => array('ul', 'li', 'li'), 2 => array('div', 'div', 'div'), 3 => array('dl', 'dd', 'dt'), 4 => array('div', 'span', 'b'));
$alinkmenu['titlewrap'] 	= empty($alinkmenu["titlewrap"]) ? array('', '') : array('<'.$alinkmenu["titlewrap"].'>', '</'.$alinkmenu["titlewrap"].'>');
$alinkmenu['link'] 			= '';

$ao 						= get_order_sort($content['struct'][ $alinkmenu["catid"] ]['acat_order']);

$alink_sql  = "SELECT article_id, article_title, article_cid, article_summary, article_alias, article_menutitle FROM ";
$alink_sql .= DB_PREPEND."phpwcms_article WHERE article_aktiv=1 AND article_deleted=0 AND article_cid=";
$alink_sql .= intval($alinkmenu["catid"]);
if(!PREVIEW_MODE) {
	$alink_sql .= ' AND article_begin<NOW()';
	$alink_sql .= ' AND article_end>NOW()';
}
if(!empty($alinkmenu['hideactive'])) {
	$alink_sql .= ' AND article_id != '. $aktion[1];
}
$alink_sql .= ' ORDER BY ' . $ao[2] ;

if($result = mysql_query($alink_sql, $db) or die("error while getting link article list: ".$alink_sql)) {

	$alinkmenu['count'] = 0;

	while($row = mysql_fetch_row($result)) {

		$tempRowSpan				= '';
		$row[3]						= preg_replace('/<br[^>]*?>$/i', '', $row[3]);
		$row['article_id']			= $row[0];
		$row['article_alias']		= $row[4];
		$row['article_title']		= html_specialchars($row[1]);
		$alinkmenu['count']++;
		$row['article_menutitle']	= empty($alinkmenu["titleasnumber"]) ? html_specialchars(empty($row[5]) ? $row[1] : $row[5]) : $alinkmenu['count'];

		if($alinkmenu['headertext'] && !empty($row[3])) {

			$alinkmenu['sum']	= $row[3];

			if(!empty($alinkmenu['maxchar'])) {

				$alinkmenu['sum'] 		= clean_replacement_tags($alinkmenu['sum']);
				$alinkmenu['sum'] 		= remove_unsecure_rptags($alinkmenu['sum']);
				$alinkmenu['sum'] 		= preg_replace('/\s/i', ' ', $alinkmenu['sum']);
				$alinkmenu['sum'] 		= preg_replace('/\s{2,}/i', ' ', $alinkmenu['sum']);
				$alinkmenu['sum'] 		= trim(decode_entities($alinkmenu['sum']));
				$alinkmenu['sum']		= wordwrap($alinkmenu['sum'], $alinkmenu['maxchar'], "\n");
				list($alinkmenu['sum'])	= explode("\n", $alinkmenu['sum']);
				$alinkmenu['sum']		= trim($alinkmenu['sum']);
				$alinkmenu['sum']		= html_specialchars($alinkmenu['sum']);

				if(!empty($alinkmenu['morelink'])) {

					$alinkmenu['sum']  .= '<a href="index.php?'.setGetArticleAid($row).'" title="'.$row['article_title'].'">';
					$alinkmenu['sum']  .= $alinkmenu['morelink'];
					$alinkmenu['sum']  .= '</a>';

				}

			}

		} else {

			$alinkmenu['sum']	= false;

		}

		$alinkmenu['active_class'] = ($aktion[1] == $row[0]) ? ' class="'.(empty($alinkmenu['class']) ? 'alink-active' : $alinkmenu['class'].'-active').'"' : '';


		if(!$alinkmenu['ul']) {

			// render as table

			if($alinkmenu['sum'] !== false) {
				$tempRowSpan		= ' rowspan="2"';
				$alinkmenu['sum']	= "<tr>\n\t<td>" . $alinkmenu['sum'] . "</td>\n</tr>\n";
			}

			$alinkmenu['link'] .= "<tr>\n\t<td valign=\"top\"".$tempRowSpan." nowrap=\"nowrap\">".$template_default["article"]["link_article_sign"]."</td>\n\t";
			$alinkmenu['link'] .= '<td'.$alinkmenu['active_class'].'>'.$alinkmenu['titlewrap'][0].'<a href="index.php?'.setGetArticleAid($row).'" ';
			$alinkmenu['link'] .= get_class_attrib($template_default["article"]["link_article_class"]).' title="'.$row['article_title'].'">';
			$alinkmenu['link'] .= $row['article_menutitle'].'</a>'.$alinkmenu['titlewrap'][1]."</td>\n</tr>\n";
			$alinkmenu['link'] .= $alinkmenu['sum'];

		} else {

			if(!empty($alinkmenu["break"]) && $alinkmenu['count'] > 1) {
				$alinkmenu['link'] .= '	<'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][1].' class="break">';
				$alinkmenu['link'] .= $alinkmenu["break"];
				$alinkmenu['link'] .= '</'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][1].'>' . LF;
			}

			$alinkmenu['link'] .= '	<'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][1];
			$alinkmenu['link'] .= $alinkmenu['active_class'].'>'.$alinkmenu['titlewrap'][0];
			$alinkmenu['link'] .= '<a href="index.php?'.setGetArticleAid($row).'" title="'.$row['article_title'].'">';
			$alinkmenu['link'] .= $row['article_menutitle'];
			$alinkmenu['link'] .= '</a>'.$alinkmenu['titlewrap'][1];

			if($alinkmenu['sum'] !== false) {
				$alinkmenu['link'] .= LF . $alinkmenu['sum'];
			}

			$alinkmenu['link'] .= '</' . $alinkmenu['ul_tag'][ $alinkmenu['ul'] ][1] . '>' . LF;

		}

	}
	mysql_free_result($result);

}

if($alinkmenu['link']) {

	//$content["alist"]["label"]

	if(!$alinkmenu['ul']) {

		$alinkmenu['link'] = '<table border="0" cellspacing="0" cellpadding="0">' . LF . $alinkmenu['link'] . "</table>" . LF;

		if(!empty($alinkmenu['class'])) {
			$alinkmenu['link'] = '<div class="' . html_specialchars($alinkmenu['class']) . "\">\n" . $alinkmenu['link'] . "</div>\n";
		}

	} else {

		$alinkmenu['class']	= empty($alinkmenu['class']) ? '' : ' class="' . $alinkmenu['class'] . '"';

		if(empty($alinkmenu['label'])) {

			$alinkmenu['label'] = '';

		} else {

			$alinkmenu['label']  = '	<'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][2].' class="label">' . $alinkmenu['label'];
			$alinkmenu['label'] .= '</'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][2].'>' . LF;

		}

		$alinkmenu['link']	= '<'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][0].$alinkmenu['class'].'>' . LF . $alinkmenu['label'] . $alinkmenu['link'];
		$alinkmenu['link'] .= '</'.$alinkmenu['ul_tag'][ $alinkmenu['ul'] ][0].'>' . LF;
	}

	$CNT_TMP .= $alinkmenu['link'];

}

unset($alinkmenu);
