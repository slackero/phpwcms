<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// user is admin
define('IS_ADMIN',			empty($_SESSION["wcs_user_admin"]) ? false : true);
define('BE_CURRENT_URL',	CMSGO_URL.'cmsgo.php?'.$_SERVER['QUERY_STRING']);
define('ACTIVE_REFERER',	$_SESSION['REFERER_URL']);

$_SESSION['REFERER_URL'] =	BE_CURRENT_URL;

// some more important constants
define('JS_START',	'<script type="text/javascript">' . LF . '<!--' . LF);
define('JS_END',	LF . '// -->' . LF . '</script>');


/**
 * If set TRUE IPTC values will be filled in on save and
 * as long the related field is empty, only the default lang
 * will be filed in
 */
$cmsgo['iptc_as_caption'] = false;

/**
 * If set TRUE IPTC values will be used for alternative languages too
 */
$cmsgo['iptc_as_caption_all_lang'] = false;

$cmsgo['iptc_keys'] = array(
    'Artist' => '',
    'CityDest' => '',
    'Contact' => '',
    'Copyright' => '',
    'CountryCodeDest' => '',
    'CountryDest' => '',
    'Credit' => '',
    'DateTimeDigitized' => '',
    'DateTimeExpires' => '',
    'DateTimeOriginal' => '',
    'DateTimeReleased' => '',
    'EditStatus' => '',
    'FixtureIdentifier' => '',
    'Headline' => '',
    'ImageDescription' => '',
    'IntellectualGenre' => '',
    'Keywords' => '',
    'LanguageCode' => '',
    'LocationDest' => '',
    'LocationDestCode' => '',
    'ObjectCycle' => '',
    'ObjectName' => '',
    'OriginalTransmissionRef' => '',
    'ProvinceOrStateDest' => '',
    'Software' => '',
    'SoftwareVersion' => '',
    'Source' => '',
    'SpecialInstructions' => '',
    'SubjectNewsCode' => '',
    'SublocationDest' => '',
    'Urgency' => '',
    'Writer' => '',
    'iimCategory' => '',
    'iimSupplementalCategory' => '',
    'iimVersion' => '',
);

/**
 * Set rules
 *
 * IPTC fields, my be unset based on related image's IPTC settings:
 * - Artist
 * - CityDest
 * - Contact
 * - Copyright
 * - CountryCodeDest
 * - CountryDest
 * - Credit
 * - DateTimeDigitized
 * - DateTimeExpires
 * - DateTimeOriginal
 * - DateTimeReleased
 * - EditStatus
 * - FixtureIdentifier
 * - Headline
 * - ImageDescription
 * - IntellectualGenre
 * - Keywords
 * - LanguageCode
 * - LocationDest
 * - LocationDestCode
 * - ObjectCycle
 * - ObjectName
 * - OriginalTransmissionRef
 * - ProvinceOrStateDest
 * - Software
 * - SoftwareVersion
 * - Source
 * - SpecialInstructions
 * - SubjectNewsCode
 * - SublocationDest
 * - Urgency
 * - Writer
 * - iimCategory
 * - iimSupplementalCategory
 * - iimVersion
 *
 * Use the fields like BBCode with ELSE tags as fallback
 */
$cmsgo['iptc_default_rules'] = array(
    'title' => '[Headline]{Headline}[/Headline][Headline_ELSE][ObjectName]{ObjectName}[/ObjectName][/Headline_ELSE]',
    'longinfo' => '[ImageDescription]{ImageDescription}[Credit], {Credit}[/Credit][/ImageDescription][ImageDescription_ELSE][Credit]{Credit}[/Credit][/ImageDescription_ELSE]',
    'copyright' => '[Copyright]{Copyright}[/Copyright]',
    'alt' => '[ObjectName]{ObjectName}[/ObjectName]',
);

$cmsgo['iptc_rules'] = $cmsgo['iptc_default_rules'];

/**
 * IPTC fields might contain multiple values (array),
 * how should these be treated?
 *
 * If set to FALSE only the first value will be taken.
 * If set to TRUE all values will be used and joined.
 */
$cmsgo['iptc_rules_multiple'] = false;

/**
 * If multiple values should be used which text separator
 * should be used to join multiple values, like:
 * - line break = "\n"
 * - comma separated = ", "
 */
$cmsgo['iptc_separator'] = ', ';

// Load custom IPTC config
if(is_file(CMSGO_ROOT.'/include/config/iptc.inc.php')) {

    include_once CMSGO_ROOT.'/include/config/iptc.inc.php';

    $cmsgo['iptc_rules'] = array_merge($cmsgo['iptc_default_rules'], $cmsgo['iptc_rules']);

}
