<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 547 Update Check
function cmsgo_revision_r547() {

    $status = true;

    // do former revision check – fallback to r546
    if(cmsgo_revision_check_temp('546') !== true) {
        $status = cmsgo_revision_check('546');
    }

    if($status) {
        // Update file image dimensions
        //$sql = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_kid=1 AND f_image_width='' AND f_image_height=''";
        $result = _dbGet('cmsgo_file', 'f_id,f_name,f_hash,f_ext', "f_kid=1 AND f_image_width='' AND f_image_height=''");

        if(isset($result[0]['f_id'])) {

            $GLOBALS['cmsgo']['revision_return'] = '';

            foreach($result as $file) {

                $filename = $file['f_hash'].'.'.$file['f_ext'];

                if(is_file(CMSGO_STORAGE.$filename)) {

                    $imageinfo = @getimagesize(CMSGO_STORAGE.$filename);

                    $GLOBALS['cmsgo']['revision_return'] .= 'File: '.CMSGO_STORAGE.$filename;

                    if(!empty($imageinfo[0]) && !empty($imageinfo[1])) {

                        $data = array(
                            'f_image_width' => $imageinfo[0],
                            'f_image_height' => $imageinfo[1]
                        );

                        if(_dbUpdate('cmsgo_file', $data, 'f_id='.$file['f_id'])) {

                            $GLOBALS['cmsgo']['revision_return'] .= '> Image Updated: ';

                        } else {

                            $GLOBALS['cmsgo']['revision_return'] .= '> Image Update failed: ';

                        }

                        $GLOBALS['cmsgo']['revision_return'] .= $file['f_name'].' > '.$imageinfo[0].'x'.$imageinfo[1]."px\n";

                    } else {

                        $GLOBALS['cmsgo']['revision_return'] .= ' > No Image detected: ' . $file['f_name'] . "\n";

                    }

                } else {

                    $GLOBALS['cmsgo']['revision_return'] .= 'File Not Found: ' . CMSGO_STORAGE . $filename . "\n";

                }
            }
        }
    }

    return $status;
}
