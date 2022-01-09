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


// Revision 543 Update Check
function phpwcms_revision_r543() {

	$status = true;

	// do former revision check – fallback to r542
	if(phpwcms_revision_check_temp('542') !== true) {
		$status = phpwcms_revision_check('542');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_file` WHERE Field='f_title'");

	if(!isset($result[0])) {

		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` ADD `f_title` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `f_sort`", 'ALTER');

		if(!$insert) {

			$status = false;

		} else {

    		$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_file` WHERE Field='f_alt'");
        	if(!isset($result[0])) {

        		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` ADD `f_alt` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `f_sort`", 'ALTER');

        		if(!$insert) {

        			$status = false;

        		} else {

                    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` CHANGE `f_keywords` `f_keywords` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
                    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` CHANGE `f_shortinfo` `f_shortinfo` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
                    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` CHANGE `f_copyright` `f_copyright` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
                    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` CHANGE `f_tags` `f_tags` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');

        		}

        	}
		}
	}

	return $status;
}
