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


// Revision 547 Update Check
function phpwcms_revision_r547() {

    $status = true;

    // do former revision check – fallback to r546
    if(phpwcms_revision_check_temp('546') !== true) {
        $status = phpwcms_revision_check('546');
    }

    if($status) {
        // Update file image dimensions
        //$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_kid=1 AND f_image_width='' AND f_image_height=''";
        $result = _dbGet('phpwcms_file', 'f_id,f_name,f_hash,f_ext', "f_kid=1 AND f_image_width='' AND f_image_height=''");

        if(isset($result[0]['f_id'])) {

            $GLOBALS['phpwcms']['revision_return'] = '';

            foreach($result as $file) {

                $filename = $file['f_hash'].'.'.$file['f_ext'];

                if(is_file(PHPWCMS_STORAGE.$filename)) {

                    $imageinfo = @getimagesize(PHPWCMS_STORAGE.$filename);

                    $GLOBALS['phpwcms']['revision_return'] .= 'File: '.PHPWCMS_STORAGE.$filename;

                    if(!empty($imageinfo[0]) && !empty($imageinfo[1])) {

                        $data = array(
                            'f_image_width' => $imageinfo[0],
                            'f_image_height' => $imageinfo[1]
                        );

                        if(_dbUpdate('phpwcms_file', $data, 'f_id='.$file['f_id'])) {

                            $GLOBALS['phpwcms']['revision_return'] .= '> Image Updated: ';

                        } else {

                            $GLOBALS['phpwcms']['revision_return'] .= '> Image Update failed: ';

                        }

                        $GLOBALS['phpwcms']['revision_return'] .= $file['f_name'].' > '.$imageinfo[0].'x'.$imageinfo[1]."px\n";

                    } else {

                        $GLOBALS['phpwcms']['revision_return'] .= ' > No Image detected: ' . $file['f_name'] . "\n";

                    }

                } else {

                    $GLOBALS['phpwcms']['revision_return'] .= 'File Not Found: ' . PHPWCMS_STORAGE . $filename . "\n";

                }
            }
        }
    }

    return $status;
}
