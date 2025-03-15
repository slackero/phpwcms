<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 548 Update Check
function cmsgo_revision_r548() {

    $status = true;

    // do former revision check – fallback to r547
    if(cmsgo_revision_check_temp('547') !== true) {
        $status = cmsgo_revision_check('547');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_file` WHERE Field='f_svg'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` ADD `f_svg` INT(1) unsigned NOT NULL DEFAULT '0' AFTER `f_ext`", 'ALTER');

        if(!$insert) {
            $status = false;
        } else {

            // Search existing SVG files and try to set width and height
            $result = _dbGet('cmsgo_file', 'f_id,f_name,f_hash,f_ext', "f_kid=1 AND f_ext='svg' AND f_image_width='' AND f_image_height=''");

            if(isset($result[0]['f_id'])) {

                require_once CMSGO_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

                $GLOBALS['cmsgo']['revision_return'] = '';

                foreach($result as $file) {

                    $filename = $file['f_hash'].'.'.$file['f_ext'];

                    if(is_file(CMSGO_STORAGE.$filename) && !@getimagesize(CMSGO_STORAGE.$filename)) {

                        $GLOBALS['cmsgo']['revision_return'] .= 'File: '.CMSGO_STORAGE.$filename;

                        if($file_svg = @SVGMetadataExtractor::getMetadata(CMSGO_STORAGE.$filename)) {

                            $data = array(
                                'f_type' => 'image/svg+xml',
                                'f_svg' => 1,
                                'f_image_width' => $file_svg['width'],
                                'f_image_height' => $file_svg['height']
                            );

                            if(_dbUpdate('cmsgo_file', $data, 'f_id='.$file['f_id'])) {

                                $GLOBALS['cmsgo']['revision_return'] .= '> SVG Image Updated: ';

                            } else {

                                $GLOBALS['cmsgo']['revision_return'] .= '> SVG Image Update failed: ';

                            }

                            $GLOBALS['cmsgo']['revision_return'] .= $file['f_name'].' > '.$file_svg['width'].'x'.$file_svg['height']."px\n";

                        } else {

                            $GLOBALS['cmsgo']['revision_return'] .= ' > No SVG Image data detected: ' . $file['f_name'] . "\n";

                        }

                    } else {

                        $GLOBALS['cmsgo']['revision_return'] .= 'File Not Found: ' . CMSGO_STORAGE . $filename . "\n";

                    }
                }
            }
        }
    }

    return $status;
}
