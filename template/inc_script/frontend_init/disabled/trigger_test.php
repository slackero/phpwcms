<?php

function CP_CreateDate($text, $data) {
    $change_date = strtotime($data['acontent_created']);
    return str_replace('{CPDATE}', international_date_format('DE', 'd.m.Y', $change_date), $text);
}
register_cp_trigger('CP_CreateDate');
