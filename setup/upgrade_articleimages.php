<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

//used to convert old style file uploads

$phpwcms = array();

require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

echo '<html><body><pre>';

$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_type=1 and acontent_image != ''";
$result = _dbQuery($sql);
$total = isset($result[0]['acontent_id']) ? count($result) : 0;
$usql = '';

echo 'TOTAL: '.$total." ENTRIES | image with text\n";
echo '================================================================='."\n\n";

if($total)
    $linenumber = 1;

    foreach($result as $row) {

        $error = false;
        $image = explode(':', $row['acontent_image']);

        $fsql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id='".intval($image[0])."' LIMIT 1";
        $fresult = _dbQuery($fsql);
        if(isset($fresult[0]['f_id'])) {

            // dbid:filename:hash:extension:width:height:caption:position:zoom
            $newimage  = $fresult[0]['f_id'];
            $newimage .= ':';
            $newimage .= $fresult[0]['f_name'];
            $newimage .= ':';
            $newimage .= $fresult[0]['f_hash'];
            $newimage .= ':';
            $newimage .= $fresult[0]['f_ext'];
            $newimage .= ':';
            $newimage .= $image[3];
            $newimage .= ':';
            $newimage .= $image[4];
            $newimage .= ':';
            $newimage .= $image[7];
            $newimage .= ':';
            $newimage .= $image[5];
            $newimage .= ':';
            $newimage .= (isset($image[8]) && intval($image[8])) ? 1 : 0;

            // check if this is an updated content part
            if($image[2] != $fresult[0]['f_hash'] && $image[3] != $fresult[0]['f_ext']) {

                $usql  = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET ";
                $usql .= "acontent_image='".aporeplace($newimage)."' ";
                $usql .= "WHERE acontent_id=".$row['acontent_id']." LIMIT 1";
                _dbQuery($usql, 'UPDATE');

                echo 'Image '. sprintf('%05d: ', $linenumber) . html_specialchars($fresult[0]['f_name']) ."\n";
            }

        }

        flush();
        $linenumber++;

    }

if($usql === '') {
    echo 'None of the content parts &quot;image with text&quot; needs to be upgraded.';
}

echo '</pre></body></html>';
