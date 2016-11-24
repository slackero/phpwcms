<?php
/*

    a simple way to handle tracing pixel of VG Wort

    1) define the tracking code
       Fill in the unique text ID in article keyword like
       VGW:e2531725f43b35656065
    2) put the replacement tag {VGWort}
       inside your template source code

*/

if(is_array($content['all_keywords']) && count($content['all_keywords'])) {
    foreach($content['all_keywords'] as $vgwort) {
        if(strpos($vgwort, 'VGW:') === 0) {
            $vgwort = str_replace('VGW:', '', $vgwort);
            $vgwort = '<img src="http://vg00.met.vgwort.de/na/'.$vgwort.'?timestamp='.time().'" width="0" height="0" alt="" />';
            $content['all'] = str_replace('{VGWort}', $vgwort, $content['all']);
            break;
        }
    }
}
$content['all'] = str_replace('{VGWort}', '', $content['all']);
