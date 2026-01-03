<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 402 Update Check
function cmsgo_revision_r402() {

	// do former revision check
	$r401 = '401';
	if(cmsgo_revision_check_temp($r401) !== true) {
		cmsgo_revision_check($r401);
	}

	return true;
}
