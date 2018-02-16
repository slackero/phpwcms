<?php

/**
 * Create menu where Top Level is <h3>Level</h3> and all submenu as <ul></ul>
 * good for having footer link blocks
 */

$h3         = array();
$start_id   = 0;
foreach($content['struct'] as $key => $item) {

    if( $item['acat_struct'] == 0 && _getStructureLevelDisplayStatus($key, $start_id) ) {

        $h3[] = '<h3>' . get_level_ahref($key) . html_specialchars($item['acat_name']) . '</a></h3>';
        $h3[] = buildCascadingMenu(','.$key); // the same as used behind {NAV_LIST_UL:...} - $key is the ID to start with

    }
}

$content['all'] = str_replace('{H3_MENU}', implode(LF, $h3), $content['all']);
