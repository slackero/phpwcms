<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$BL['AR']    = 'العربية';
$BL['BG']    = 'Български';
$BL['BS']    = 'Bosanski';
$BL['CA']    = 'Català';
$BL['CS']    = 'Čeština';
$BL['CZ']    = 'Česky (CZ)';
$BL['DA']    = 'Dansk';
$BL['DE']    = 'Deutsch';
$BL['DE-CH'] = 'Deutsch (CH)';
$BL['EL']    = 'Ελληνικά';
$BL['EN']    = 'English';
$BL['ES']    = 'Español';
$BL['ET']    = 'Eesti';
$BL['FI']    = 'Suomi';
$BL['FR']    = 'Français';
$BL['GR']    = 'Ελληνικά';
$BL['HU']    = 'Magyar';
$BL['IT']    = 'Italiano';
$BL['LT']    = 'Lietuvių';
$BL['NL']    = 'Nederlands';
$BL['NO']    = 'Norsk';
$BL['PL']    = 'Polski';
$BL['PT']    = 'Português';
$BL['RO']    = 'Română';
$BL['RU']    = 'Русский';
$BL['SE']    = 'Svenska';
$BL['SK']    = 'Slovenčina';
$BL['SL']    = 'Slovenščina';
$BL['SV']    = 'Svenska';
$BL['TR']    = 'Türkçe';
$BL['UA']    = 'Українська';
$BL['UK']    = 'Українська';
$BL['VI']    = 'Tiếng Việt';
$BL['VN']    = 'Tiếng Việt';

if (defined('PHPWCMS_CHARSET') && PHPWCMS_CHARSET !== 'utf-8' && function_exists('mb_encode_numericentity')) {
    $_lang_keys = [
        'AR', 'BG', 'BS', 'CA', 'CS', 'CZ', 'DA', 'DE', 'DE-CH', 'EL', 'EN', 'ES',
        'ET', 'FI', 'FR', 'GR', 'HU', 'IT', 'LT', 'NL', 'NO', 'PL', 'PT', 'RO',
        'RU', 'SE', 'SK', 'SL', 'SV', 'TR', 'UA', 'UK', 'VI', 'VN'
    ];
    foreach ($_lang_keys as $_lang_key) {
        if (isset($BL[$_lang_key])) {
            $BL[$_lang_key] = mb_encode_numericentity($BL[$_lang_key], [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8');
        }
    }
    unset($_lang_keys, $_lang_key);
}
