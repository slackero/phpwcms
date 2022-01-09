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


// Alias ID
echo "<tr><td>&nbsp;</td><td class=\"v10\">".$BL['be_alias_ID'].': ';
$content["alias"] = @unserialize($row["acontent_form"]);
$content['alias_link'] = '';
if(empty($content["alias"]['alias_ID'])) {
    $content["alias"]['alias_ID'] = '';
} else {
    $content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
    $sql_cnt = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
    $cntresult = _dbQuery($sql_cnt);

    if(isset($cntresult[0]['acontent_id'])) {
        $content['alias_link'] .= ', <a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
        $content['alias_link'] .= $cntresult[0]['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
        $content['alias_link'] .= '" target="_blank">'.$BL['be_article_cnt_edit'].': ';
        $content['alias_link'] .= $wcs_content_type[$cntresult[0]['acontent_type']].'</a>';
        $content["alias"]['alias_ID'] = '<strong>'.$content["alias"]['alias_ID'].'</strong>';
    } else {
        $content["alias"]['alias_ID'] = '';
    }
}

echo $content["alias"]['alias_ID'].$content['alias_link'];
echo "</td><td>&nbsp;</td></tr>";
