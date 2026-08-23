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
$BL['BN']    = 'বাংলা';
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
$BL['EU']    = 'Euskara';
$BL['FI']    = 'Suomi';
$BL['FR']    = 'Français';
$BL['GL']    = 'Galego';
$BL['GR']    = 'Ελληνικά';
$BL['HI']    = 'हिन्दी';
$BL['HR']    = 'Hrvatski';
$BL['HU']    = 'Magyar';
$BL['ID']    = 'Bahasa Indonesia';
$BL['IS']    = 'Íslenska';
$BL['IT']    = 'Italiano';
$BL['JA']    = '日本語';
$BL['LT']    = 'Lietuvių';
$BL['LV']    = 'Latviešu';
$BL['MK']    = 'Македонски';
$BL['NL']    = 'Nederlands';
$BL['NO']    = 'Norsk';
$BL['PA']    = 'ਪੰਜਾਬੀ';
$BL['PL']    = 'Polski';
$BL['PT']    = 'Português';
$BL['RO']    = 'Română';
$BL['RU']    = 'Русский';
$BL['SE']    = 'Svenska';
$BL['SK']    = 'Slovenčina';
$BL['SL']    = 'Slovenščina';
$BL['SQ']    = 'Shqip';
$BL['SR']    = 'Srpski';
$BL['SV']    = 'Svenska';
$BL['TA']    = 'தமிழ்';
$BL['TR']    = 'Türkçe';
$BL['UA']    = 'Українська';
$BL['UK']    = 'Українська';
$BL['UR']    = 'اردو';
$BL['VI']    = 'Tiếng Việt';
$BL['VN']    = 'Tiếng Việt';
$BL['ZH-CN'] = '简体中文';
$BL['ZH']    = '简体中文';

if (defined('PHPWCMS_CHARSET') && PHPWCMS_CHARSET !== 'utf-8' && function_exists('mb_encode_numericentity')) {
    $_lang_keys = [
        'AR', 'BG', 'BN', 'BS', 'CA', 'CS', 'CZ', 'DA', 'DE', 'DE-CH', 'EL', 'EN', 'ES',
        'ET', 'EU', 'FI', 'FR', 'GL', 'GR', 'HI', 'HR', 'HU', 'ID', 'IS', 'IT', 'JA',
        'LT', 'LV', 'MK', 'NL', 'NO', 'PA', 'PL', 'PT', 'RO', 'RU', 'SE', 'SK', 'SL',
        'SQ', 'SR', 'SV', 'TA', 'TR', 'UA', 'UK', 'UR', 'VI', 'VN', 'ZH', 'ZH-CN'
    ];
    foreach ($_lang_keys as $_lang_key) {
        if (isset($BL[$_lang_key])) {
            $BL[$_lang_key] = mb_encode_numericentity($BL[$_lang_key], [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8');
        }
    }
    unset($_lang_keys, $_lang_key);
}
