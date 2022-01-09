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
 * - Artist (IPTC Tag 2#080)
 * - CityDest (IPTC Tag 2#090)
 * - Contact (IPTC Tag 2#118)
 * - Copyright (IPTC Tag 2#120)
 * - CountryCodeDest (IPTC Tag 2#100)
 * - CountryDest (IPTC Tag 2#101)
 * - Credit (IPTC Tag 2#110)
 * - DateTimeDigitized (IPTC Tag 2#062)
 * - DateTimeExpires (IPTC Tag 2#037)
 * - DateTimeOriginal (IPTC Tag 2#055)
 * - DateTimeReleased (IPTC Tag 2#030)
 * - EditStatus (IPTC Tag 2#007)
 * - FixtureIdentifier (IPTC Tag 2#022)
 * - Headline (IPTC Tag 2#105)
 * - ImageDescription (IPTC Tag 2#120)
 * - IntellectualGenre (IPTC Tag 2#004)
 * - Keywords (IPTC Tag 2#025)
 * - LanguageCode (IPTC Tag 2#135)
 * - LocationDest (IPTC Tag 2#027)
 * - LocationDestCode (IPTC Tag 2#026)
 * - ObjectCycle (IPTC Tag 2#075)
 * - ObjectName (IPTC Tag 2#005)
 * - OriginalTransmissionRef (IPTC Tag 2#103)
 * - ProvinceOrStateDest (IPTC Tag 2#095)
 * - Software (IPTC Tag 2#065)
 * - SoftwareVersion (IPTC Tag 2#070)
 * - Source (IPTC Tag 2#115)
 * - SpecialInstructions (IPTC Tag 2#040)
 * - SubjectNewsCode (IPTC Tag 2#012)
 * - SublocationDest (IPTC Tag 2#092)
 * - Urgency (IPTC Tag 2#010)
 * - Writer (IPTC Tag 2#122)
 * - iimCategory (IPTC Tag 2#015, deprecated)
 * - iimSupplementalCategory (IPTC Tag 2#020, deprecated)
 * - iimVersion (IPTC Tag 2#000, deprecated)
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
