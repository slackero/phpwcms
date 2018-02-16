<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// THE FOLLOWIN FUNCTION IS RELEASED UNDER THE FOLLOWING OPEN SOUCE LICENCE
// http://keithdevens.com/software/license

function returnDayNameArray() {
	// taken from PHP Calendar (version 2.3), written by Keith Devens
	// http://keithdevens.com/software/php_calendar
	$day_names = array();
	for($n=0, $t=3*86400; $n<7; $n++, $t+=86400) {
		$day_names[$n] = ucfirst(gmstrftime('%A',$t));
	}
	return $day_names;
}
