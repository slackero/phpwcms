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


// Revision 544 Update Check
function phpwcms_revision_r544() {

    $status = true;

    // do former revision check – fallback to r543
    if(phpwcms_revision_check_temp('543') !== true) {
        $status = phpwcms_revision_check('543');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_calendar` WHERE Field='calendar_refid'");

    if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 3) === 'int') {

        if($result = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_calendar` CHANGE `calendar_refid` `calendar_refid` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER')) {

            _dbUpdate('phpwcms_calendar', array('calendar_refid' => ''), "calendar_refid='0'");

        }

    }

    return $status;
}
