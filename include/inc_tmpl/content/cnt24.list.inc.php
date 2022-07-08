<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
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
$content["alias"] = @unserialize($row["acontent_form"]);
$content['alias_link'] = '';
$content['alias_list_title'] = array();

echo '<div class="col-sm-auto">';
if(!empty($row['acontent_title'])){
    $content['alias_list_title'][] = $row["acontent_title"];
}
if(!empty($row['acontent_subtitle'])){
    $content['alias_list_title'][] = $row["acontent_subtitle"];
}
if (count($content['alias_list_title'])) {
    echo '<a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"].'&amp;acid='.$row["acontent_id"].'">';
    echo html(implode(' / ', $content['alias_list_title']));
    echo '</a><br>';
}
echo $BL['be_alias_ID'] . ': ';
if(empty($content["alias"]['alias_ID'])) {
    $content["alias"]['alias_ID'] = '–';
} else {
    $content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
    $cntresult = _dbGet('cmsgo_articlecontent', '*', 'acontent_id=' . $content["alias"]['alias_ID'] . ' AND acontent_trash=0');

    if(isset($cntresult[0]['acontent_id'])) {
        $content['alias_link'] .= ', <a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
        $content['alias_link'] .= $cntresult[0]['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
        $content['alias_link'] .= '" target="_blank">' . $BL['be_article_cnt_edit'] . ': ';
        $content['alias_link'] .= $wcs_content_type[$cntresult[0]['acontent_type']] . '</a>';
        $content["alias"]['alias_ID'] = '<strong>' . $content["alias"]['alias_ID'] . '</strong>';
    } else {
        $content["alias"]['alias_ID'] = '–';
    }
}

echo $content["alias"]['alias_ID'] . $content['alias_link'];
echo '</div>';