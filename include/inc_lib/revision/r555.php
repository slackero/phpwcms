<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Revision 555 Update Check
function cmsgo_revision_r555() {

	$status = true;

	// do former revision check – fallback to r554
	if(cmsgo_revision_check_temp('554') !== true) {
		$status = cmsgo_revision_check('554');
	}

    $result = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . "cmsgo_usergroup WHERE group_syskey='filedelete' AND group_trash=0");

    if($result > 0) {
        return $status;
    }

    $result = _dbInsert('cmsgo_usergroup', [
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
