<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 514 Update Check
function cmsgo_revision_r514() {

	$status = true;

	// do former revision check – fallback to r509
	if(cmsgo_revision_check_temp('509') !== true) {
		$status = cmsgo_revision_check('509');
	}

	// Delete

	// empty temp images table
	_dbQuery('DROP TABLE IF EXISTS '.DB_PREPEND.'cmsgo_imgcache', 'DROP');

	// empty temp images directory
	$thumbnails = returnFileListAsArray(CMSGO_THUMB, 'jpg,jpeg,gif,png');
	if(is_array($thumbnails) && count($thumbnails)) {

		foreach($thumbnails as $thumbnail) {

			@unlink(CMSGO_THUMB.$thumbnail['filename']);

		}
	}

	// Set file hash
	$result = _dbQuery("SHOW FIELDS FROM ".DB_PREPEND."cmsgo_file WHERE Field='f_hash'");

	if($status && !empty($result[0])) {

		// Remove unused fields but only when file storage upgrade from earlier update is done
		$count = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_file LIKE 'f_thumb_%'" , 'COUNT_SHOW');

		if($count === 2) {

			$count = _dbCount("SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_thumb_list != '' OR f_thumb_preview != ''");

			if($count === 0) {
				_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_file DROP f_thumb_list", 'ALTER');
				_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_file DROP f_thumb_preview", 'ALTER');

				$status = true;

			} else {

				$status = false;

			}
		}

		$result = array_change_key_case($result[0], CASE_LOWER);

		if($status && $result['type'] == 'varchar(50)') {

			$status = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_file CHANGE f_hash f_hash VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

			// ensure all went well
			if($status) {

				$result = _dbQuery("SHOW FIELDS FROM ".DB_PREPEND."cmsgo_file WHERE Field='f_hash'");
				$status = false;

				if(!empty($result[0])) {

					$result = array_change_key_case($result[0], CASE_LOWER);

					if($result['type'] == 'varchar(255)') {
						$status = true;
					}
				}
			}
		}

		// Rename Hash and files
		if($status) {

			// Cleanup first — Check all files trashed or deleted and not yet physical accessible anymore
			$files = _dbGet('cmsgo_file', '*', 'f_trash IN (8,9) AND f_kid=1');

			if(isset($files[0]['f_id'])) {

				write_textfile(CMSGO_STORAGE.'cmsgo-filestorage.log', date('Y-m-d H:i:s').': Prepare removing non-existing, trashed or deleted files from cmsgo_file database table'.LF, 'a');
				$file_id = array();

				foreach($files as $file) {

					$file['storage_name'] = $file['f_hash'];
					if($file['f_ext']) {
						$file['storage_name'] .= '.' . $file['f_ext'];
					}

					if(!is_file(CMSGO_STORAGE.$file['storage_name'])) {

						write_textfile(CMSGO_STORAGE.'cmsgo-filestorage.log', date('Y-m-d H:i:s').': '.json_encode($file).LF, 'a');
						$file_id[] = $file['f_id'];

					}

				}

				$file_id = implode(',', $file_id);
				write_textfile(CMSGO_STORAGE.'cmsgo-filestorage.log', date('Y-m-d H:i:s').': File IDs to be deleted ('.$file_id.')', 'a');

				if($file_id) {
					$result = _dbQuery('DELETE FROM '.DB_PREPEND.'cmsgo_file WHERE f_trash IN (8,9) AND f_kid=1 AND f_id IN ('.$file_id.')', 'DELETE');
					if(!empty($result['AFFECTED_ROWS'])) {
						write_textfile(CMSGO_STORAGE.'cmsgo-filestorage.log', ' // DONE: ' . $result['AFFECTED_ROWS'] . ' deleted', 'a');
					} else {
						write_textfile(CMSGO_STORAGE.'cmsgo-filestorage.log', ' // DONE: None deleted', 'a');
					}
				}

				write_textfile(CMSGO_STORAGE.'cmsgo-filestorage.log', LF.LF.'---'.LF.LF, 'a');

			}

			// ToDo: implement new naming

		}


	} else {

		$status = false;

	}


	return $status;
}
