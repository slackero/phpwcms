<?php

/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// some mod ADS functions only needed in frontend

function renderAds($match) {
	return empty($match[1]) ? '' : '<p>Ad banner '.$match[1].'</p>';
}
