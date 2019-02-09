<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 543 Update Check
function cmsgo_revision_r543() {

	$status = true;

	// do former revision check – fallback to r542
	if(cmsgo_revision_check_temp('542') !== true) {
		$status = cmsgo_revision_check('542');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_file` WHERE Field='f_title'");

	if(!isset($result[0])) {

		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` ADD `f_title` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `f_sort`", 'ALTER');

		if(!$insert) {

			$status = false;

		} else {

    		$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_file` WHERE Field='f_alt'");
        	if(!isset($result[0])) {

        		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` ADD `f_alt` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `f_sort`", 'ALTER');

        		if(!$insert) {

        			$status = false;

        		} else {

                    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` CHANGE `f_keywords` `f_keywords` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
                    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` CHANGE `f_shortinfo` `f_shortinfo` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
                    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` CHANGE `f_copyright` `f_copyright` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
                    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` CHANGE `f_tags` `f_tags` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');

        		}

        	}
		}
	}

	return $status;
}
