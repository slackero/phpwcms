<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Alias ID
echo "<div class=\"col-sm-auto\">".$BL['be_alias_ID'].': ';
$content["alias"] = @unserialize($row["acontent_form"]);
$content['alias_link'] = '';
if(empty($content["alias"]['alias_ID'])) {
    $content["alias"]['alias_ID'] = '';
} else {
    $content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
    $sql_cnt = "SELECT * FROM ".DB_PREPEND."cmsgo_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
    $cntresult = _dbQuery($sql_cnt);

    if(isset($cntresult[0]['acontent_id'])) {
        $content['alias_link'] .= ', <a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
        $content['alias_link'] .= $cntresult[0]['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
        $content['alias_link'] .= '" target="_blank">'.$BL['be_article_cnt_edit'].': ';
        $content['alias_link'] .= $wcs_content_type[$cntresult[0]['acontent_type']].'</a>';
        $content["alias"]['alias_ID'] = '<strong>'.$content["alias"]['alias_ID'].'</strong>';
    } else {
        $content["alias"]['alias_ID'] = '';
    }
}

echo $content["alias"]['alias_ID'].$content['alias_link'];
echo "</div>";
