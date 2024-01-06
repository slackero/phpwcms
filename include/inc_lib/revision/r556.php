<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Revision 556 Update Check
function cmsgo_revision_r556() {

	$status = true;

	// do former revision check – fallback to r555
	if(cmsgo_revision_check_temp('555') !== true) {
		$status = cmsgo_revision_check('555');
	}

    // Alter cmsgo_usergroup varchar field lengths to 255
    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_usergroup` CHANGE `group_name` `group_name` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_usergroup` CHANGE `group_modkey` `group_modkey` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_usergroup` CHANGE `group_syskey` `group_syskey` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

    // Add file categories permission to the admin section if not exists
    $result = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . "cmsgo_usergroup WHERE group_syskey='admfilecat' AND group_trash=0");

    if($result > 0) {
        return $status;
    }

    $result = _dbInsert('cmsgo_usergroup', [
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
