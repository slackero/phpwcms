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

$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_hash='' AND f_kid=1";
$result = _dbQuery($sql);
$total = isset($result[0]['f_id']) ? count($result) : 0;

echo 'TOTAL: '.$total." ENTRIES\n";
echo '=================================================================

If last line number is  < '.$total.'  <a href="upgrade_filestorage.php">click here</a>'."\n\n";

if($total) {
    $linenumber = 1;
    $format     = '%0'.(strlen(strval($total))).'d: ';

    foreach($result as $row) {

        $error = false;

        echo sprintf($format, $linenumber);

        $fileHash = md5( $row['f_name'] . microtime() );
        $fileOld  = PHPWCMS_ROOT.$phpwcms["file_path"].$row['f_uid'].'/'.$row['f_uid'].'_'.$row['f_id'];
        $fileNew  = PHPWCMS_ROOT.$phpwcms["file_path"].$fileHash;
        if($row['f_ext'] != '') {
            $fileOld .=  '.'.$row['f_ext'];
            $fileNew .=  '.'.$row['f_ext'];
        }
        if(file_exists($fileOld)) {
            if(@copy($fileOld, $fileNew)) {
                $update  = "UPDATE ".DB_PREPEND."phpwcms_file SET ";
                $update .= "f_thumb_list = '', ";
                $update .= "f_thumb_preview = '', ";
                $update .= "f_hash = '".$fileHash."' ";
                $update .= "WHERE f_id=".$row['f_id'];

                $result = _dbQuery($update, 'UPDATE');

                if(isset($result['AFFECTED_ROWS'])) {
                    unlink($fileOld);
                } else {
                    $error = 1;
                }
            } else {
                $error = 2;
            }

            if($error) {
                echo 'ERROR('.$error.'): ' .$fileOld .' -> ' . $fileNew ."\n";
            } else {
                echo 'SUCCESS: ' .$fileOld .' -> ' . $fileNew ."\n";
            }

        } else {

            _dbQuery("DELETE FROM ".DB_PREPEND."phpwcms_file WHERE f_kid=1 AND f_id=".$row['f_id']." LIMIT 1", 'DELETE');
            echo 'DELETED: ' . $fileOld ."\n";

        }

        flush();
        $linenumber++;

    }
}

echo '</pre></body></html>';
