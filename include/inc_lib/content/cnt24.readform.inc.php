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

// Content Type Alias
$content['alias'] = array(
    'alias_ID' => isset($_POST["calias"]) ? intval($_POST["calias"]) : '',
    'alias_block' => empty($_POST["cablock"]) ? 0 : 1,
    'alias_spaces' => empty($_POST["caspaces"]) ? 0 : 1,
    'alias_title' => empty($_POST["catitle"]) ? 0 : 1,
    'alias_toplink' => empty($_POST["catop"]) ? 0 : 1,
    'alias_status' => empty($_POST["castatus"]) ? 0 : 1
);

if(empty($content['alias']['alias_ID'])) {

    $content['alias']['alias_ID'] = '';

} else {

    // check if alias ID has valid counter part
    $cresult = _dbGet('cmsgo_articlecontent', 'acontent_id', 'acontent_id='.$content['alias']['alias_ID'].' AND acontent_trash=0');
    if(empty($cresult[0]['acontent_id'])) {
        $content['alias']['alias_ID'] = '';
    }

}
