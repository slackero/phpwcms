<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Revision 556 Update Check
function phpwcms_revision_r556() {

	$status = true;

	// do former revision check – fallback to r555
	if(phpwcms_revision_check_temp('555') !== true) {
		$status = phpwcms_revision_check('555');
	}

    // Alter phpwcms_usergroup varchar field lengths to 255
    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_usergroup` CHANGE `group_name` `group_name` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_usergroup` CHANGE `group_modkey` `group_modkey` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_usergroup` CHANGE `group_syskey` `group_syskey` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

    // Add file categories permission to the admin section if not exists
    $result = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . "phpwcms_usergroup WHERE group_syskey='admfilecat' AND group_trash=0");

    if($result > 0) {
        return $status;
    }

    $result = _dbInsert('phpwcms_usergroup', [
        'group_name' => 'SYSGROUP',
        'group_member' => '1',
        'group_value' => '',
        'group_trash' => 0,
        'group_active' => 1,
        'group_modkey' => '',
        'group_syskey' => 'admfilecat'
    ]);

    if (empty($result['INSERT_ID'])) {
        $status = false;
    }

	return $status;
}
