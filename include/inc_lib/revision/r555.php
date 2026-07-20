<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Revision 555 Update Check
function phpwcms_revision_r555() {

	$status = true;

	// do former revision check – fallback to r554
	if(phpwcms_revision_check_temp('554') !== true) {
		$status = phpwcms_revision_check('554');
	}

	// Self-healing check for group_syskey and group_modkey columns
	$check_syskey = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_usergroup` LIKE 'group_syskey'");
	if (empty($check_syskey)) {
		_dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_usergroup` ADD `group_syskey` VARCHAR(255) NOT NULL DEFAULT '' AFTER `group_active`", 'ALTER');
	}
	$check_modkey = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_usergroup` LIKE 'group_modkey'");
	if (empty($check_modkey)) {
		_dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_usergroup` ADD `group_modkey` VARCHAR(20) NOT NULL AFTER `group_active`", 'ALTER');
	}

    $result = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . "phpwcms_usergroup WHERE group_syskey='filedelete' AND group_trash=0");

    if($result > 0) {
        return $status;
    }

    $result = _dbInsert('phpwcms_usergroup', [
        'group_name' => 'File management - delete file',
        'group_member' => '1',
        'group_value' => '',
        'group_trash' => 0,
        'group_active' => 1,
        'group_modkey' => '',
        'group_syskey' => 'filedelete'
    ]);

    if (empty($result['INSERT_ID'])) {
        $status = false;
    }

	return $status;
}
