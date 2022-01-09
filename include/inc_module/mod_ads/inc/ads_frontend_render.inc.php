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

// some mod ADS functions only needed in frontend

function renderAds($match) {
	return empty($match[1]) ? '' : '<p>Ad banner '.$match[1].'</p>';
}
