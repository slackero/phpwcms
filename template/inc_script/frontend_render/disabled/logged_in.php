<?php

if(strpos($content['all'], '[LOGGED_IN')) {
    $content['all'] = render_cnt_template($content['all'], 'LOGGED_IN', _getFeUserLoginStatus() ? '<!-- //-->' : '');
}
