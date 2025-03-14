<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// Revision 552 Update Check
function phpwcms_revision_r552() {

	$status = true;

	// do former revision check – fallback to r551
	if(phpwcms_revision_check_temp('551') !== true) {
		$status = phpwcms_revision_check('551');
	}

    // Try to fix SVG again
    // Search existing SVG files and try to set width and height
    $result = _dbGet('phpwcms_file', 'f_id,f_name,f_hash,f_ext', "f_trash=0 AND f_kid=1 AND f_ext='svg' AND f_image_width='' AND f_image_height=''");

    if(isset($result[0]['f_id'])) {

        require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

        $GLOBALS['phpwcms']['revision_return'] = '';

        foreach($result as $file) {

            $filename = $file['f_hash'].'.'.$file['f_ext'];

            if(is_file(PHPWCMS_STORAGE.$filename) && !@getimagesize(PHPWCMS_STORAGE.$filename)) {

                $GLOBALS['phpwcms']['revision_return'] .= 'File: '.PHPWCMS_STORAGE.$filename;

                if($file_svg = @SVGMetadataExtractor::getMetadata(PHPWCMS_STORAGE.$filename)) {

                    $data = array(
                        'f_type' => 'image/svg+xml',
                        'f_svg' => 1,
                        'f_image_width' => $file_svg['width'],
                        'f_image_height' => $file_svg['height']
                    );

                    if(_dbUpdate('phpwcms_file', $data, 'f_id='.$file['f_id'])) {

                        $GLOBALS['phpwcms']['revision_return'] .= '> SVG Image Updated: ';

                    } else {

                        $GLOBALS['phpwcms']['revision_return'] .= '> SVG Image Update failed: ';

                    }

                    $GLOBALS['phpwcms']['revision_return'] .= $file['f_name'].' > '.$file_svg['width'].'x'.$file_svg['height']."px\n";

                } else {

                    $GLOBALS['phpwcms']['revision_return'] .= ' > No SVG Image data detected: ' . $file['f_name'] . "\n";

                }

            } else {

                $GLOBALS['phpwcms']['revision_return'] .= 'File Not Found: ' . PHPWCMS_STORAGE . $filename . "\n";

            }
        }
    }

	return $status;
}
