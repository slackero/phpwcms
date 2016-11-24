<?php

if(function_exists('memory_get_usage')) {
    $content['all'] .= '<div>Memory: '.round(memory_get_usage()/1024/1024, 2).'MB</div>';
}
