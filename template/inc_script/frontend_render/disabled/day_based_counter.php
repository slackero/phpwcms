<?php

$_startDate     = '2006/09/01';
$_startValue    = 0;
$_countInterval = 29.667;
$_countValue    = 2;

$content['all'] = str_replace('{DAY_BASED_COUNTER}', $_startValue + $_countValue * ceil( (time() - strtotime($_startDate)) / ($_countInterval * 24 * 3600)), $content['all']);
