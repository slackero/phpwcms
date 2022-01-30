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

$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_type=2 and acontent_image != ''";
$result = _dbQuery($sql);
$total = isset($result[0]['acontent_id']) ? count($result) : 0;

echo 'TOTAL: '.$total." ENTRIES\n";
echo '=================================================================

If last line number is  < '.$total.'  <a href="upgrade_articleimagelist.php">click here</a> (will run again)'."\n\n";

if($total) {
    $linenumber = 1;

    foreach($result as $row) {

        $error = false;
        $imglist = array();

        $image = explode("\n", $row['acontent_image']);

        if(is_array($image) && count($image)) {

            $g = 0;

            foreach($image as $key => $value) {

                $ival  = explode(":", chop($value));

                $fsql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id='".intval($ival[0])."' LIMIT 1";
                $fresult = _dbQuery($fsql);

                if(isset($fresult[0]['fid'])) {

                    $imglist['images'][$g][0] = $fresult[0]['f_id'];
                    $imglist['images'][$g][1] = $fresult[0]['f_name'];
                    $imglist['images'][$g][2] = $fresult[0]['f_hash'];
                    $imglist['images'][$g][3] = $fresult[0]['f_ext'];
                    if(count($ival) > 9) {
                        $imglist['images'][$g][4] = $ival[3];
                        $imglist['images'][$g][5] = $ival[4];
                        $imglist['images'][$g][6] = trim(base64_decode($ival[9]));

                        $imglist['width']  = $ival[3];
                        $imglist['height'] = $ival[4];
                        $imglist['pos']    = $ival[5];
                        $imglist['space']  = $ival[6];
                        $imglist['col']    = $ival[7];
                        $imglist['zoom']   = $ival[10];
                    } else {
                        $imglist['images'][$g][4] = '';
                        $imglist['images'][$g][5] = '';
                        $imglist['images'][$g][6] = '';

                        $imglist['width']  = '';
                        $imglist['height'] = '';
                        $imglist['pos']    = 1;
                        $imglist['space']  = 0;
                        $imglist['col']    = 1;
                        $imglist['zoom']   = 0;
                    }

                    $g++;

                }
            }

            $usql  = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET ";
            $usql .= "acontent_image='', acontent_form='".((count($imglist)) ? aporeplace(serialize($imglist)) : '')."' ";
            $usql .= "WHERE acontent_id=".$row['acontent_id']." LIMIT 1";
            $done = _dbQuery($usql, 'UPDATE');

        }

        echo sprintf('%05d: ', $linenumber).' CP-ID: '.$row['acontent_id']."\n";
        flush();
        $linenumber++;

    }
}

echo '</pre></body></html>';
