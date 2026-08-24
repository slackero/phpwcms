<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * Create menu where Top Level is <h3>Level</h3> and all submenu as <ul></ul>
 * good for having footer link blocks
 */

if (strpos($content['all'], '{H3_MENU}') !== false && !empty($content['struct']) && is_array($content['struct'])) {

    $h3       = [];
    $start_id = 0;

    foreach ($content['struct'] as $key => $item) {
        if ($item['acat_struct'] === 0 && _getStructureLevelDisplayStatus($key, $start_id)) {
            $h3[] = '<h3>' . get_level_ahref($key) . html_specialchars($item['acat_name']) . '</a></h3>';
            $h3[] = buildCascadingMenu(',' . $key); // Same as used behind {NAV_LIST_UL:...} - $key is start ID
        }
    }

    $content['all'] = str_replace('{H3_MENU}', implode(LF, $h3), $content['all']);

}

