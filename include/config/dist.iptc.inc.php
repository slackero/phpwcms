<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


/**
 * Configuration of how to handle IPTC fields and values
 * Will overwrite system's defaults
 */


/**
 * If set TRUE IPTC values will be filled in on save and
 * as long the related field is empty, only the default lang
 * will be filed in
 */
$phpwcms['iptc_as_caption'] = false;


/**
 * If set TRUE IPTC values will be used for alternative languages too
 */
$phpwcms['iptc_as_caption_all_lang'] = true;


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
$phpwcms['iptc_rules'] = array(
    'title' => '[Headline]{Headline}[/Headline][Headline_ELSE][ObjectName]{ObjectName}[/ObjectName][/Headline_ELSE]',
    'longinfo' => '[ImageDescription]{ImageDescription}[Credit], {Credit}[/Credit][/ImageDescription][ImageDescription_ELSE][Credit]{Credit}[/Credit][/ImageDescription_ELSE]',
    'copyright' => '[Copyright]{Copyright}[/Copyright]',
    'alt' => '[ObjectName]{ObjectName}[/ObjectName]',
);


/**
 * IPTC fields might contain multiple values (array),
 * how should these be treated?
 *
 * If set to FALSE only the first value will be taken.
 * If set to TRUE all values will be used and joined.
 */
$phpwcms['iptc_rules_multiple'] = false;


/**
 * If multiple values should be used which text separator
 * should be used to join multiple values, like:
 * - line break = "\n"
 * - comma separated = ", "
 */
$phpwcms['iptc_separator'] = ', ';
