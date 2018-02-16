<?php
// just to solve possible problems with
// cmimage.php and PHP installation beeing
// not able to handle "cmsimage.php/values..."

$content['all'] = str_replace('cmsimage.php/', 'cmsimage.php?', $content['all']);
