<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 529 Update Check
function cmsgo_revision_r529() {
		
	$status = true;
	
	// do former revision check – fallback to r528
	if(cmsgo_revision_check_temp('528') !== true) {
		$status = cmsgo_revision_check('528');
	}
	
	// reset article and file manager status (open/close)
	_dbUpdate('cmsgo_user', array(
		'usr_var_structure' => '',
		'usr_var_publicfile' => '',
		'usr_var_privatefile' => ''
	));
	
	return $status;
}
