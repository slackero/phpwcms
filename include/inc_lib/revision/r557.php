<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

/**
 * Revision 557 Update Check:
 * - Ensure phpwcms_usergroup columns group_modkey and group_syskey are present and VARCHAR(255) (introduced in r544, updated r555, r556)
 * - Seed any missing system and module permission groups
 * - Ensure phpwcms_country column country_name_native is present
 * - Modernize and update all ISO 3166-1 country records in phpwcms_country with English, German, and native country names
 *
 * @return bool
 */
function phpwcms_revision_r557() {

    $status = true;

    // 1. Self-healing check and update for phpwcms_usergroup columns
    if (!_dbColumnExists('usergroup', 'group_modkey')) {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "usergroup` ADD `group_modkey` VARCHAR(255) NOT NULL DEFAULT '' AFTER `group_active`", 'ALTER');
    } else {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "usergroup` CHANGE `group_modkey` `group_modkey` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }

    if (!_dbColumnExists('usergroup', 'group_syskey')) {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "usergroup` ADD `group_syskey` VARCHAR(255) NOT NULL DEFAULT '' AFTER `group_active`", 'ALTER');
    } else {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "usergroup` CHANGE `group_syskey` `group_syskey` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }

    _dbQuery("ALTER TABLE `" . DB_PREPEND . "usergroup` CHANGE `group_name` `group_name` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

    // 2. Seed missing system & module groups
    $adminusers = _dbQuery('SELECT `usr_id` FROM `' . DB_PREPEND . 'user` WHERE `usr_admin` = 1');
    $adminids = [];
    if (!empty($adminusers)) {
        foreach ($adminusers as $admins) {
            $adminids[] = $admins['usr_id'];
        }
    }
    $admin_members = implode(',', $adminids);

    $sysgroups = [
        'artcent'      => 'SYSGROUP',
        'artnew'       => 'SYSGROUP',
        'artstruc'     => 'SYSGROUP',
        'artnews'      => 'SYSGROUP',
        'module'       => 'SYSGROUP',
        'adm'          => 'SYSGROUP',
        'admlayout'    => 'SYSGROUP',
        'admtempl'     => 'SYSGROUP',
        'admuser'      => 'SYSGROUP',
        'admugroup'    => 'SYSGROUP',
        'admfc'        => 'SYSGROUP',
        'admalias'     => 'SYSGROUP',
        'admlink'      => 'SYSGROUP',
        'profile'      => 'SYSGROUP',
        'file'         => 'SYSGROUP',
        'filecent'     => 'SYSGROUP',
        'fileaction'   => 'SYSGROUP',
        'fileupload'   => 'SYSGROUP',
        'nl'           => 'SYSGROUP',
        'nllist'       => 'SYSGROUP',
        'nlrecip'      => 'SYSGROUP',
        'nlabo'        => 'SYSGROUP',
        'admialias'    => 'SYSGROUP',
        'admctptemp'   => 'SYSGROUP',
        'filedelete'   => 'SYSGROUP',
        'admfilecat'   => 'SYSGROUP',
    ];

    foreach ($sysgroups as $syskey => $group_name) {
        $count = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . 'usergroup WHERE group_syskey=' . _dbEscape($syskey) . ' AND group_trash=0');
        if ($count === 0) {
            _dbInsert('usergroup', [
                'group_name'   => $group_name,
                'group_member' => $admin_members,
                'group_value'  => '',
                'group_active' => 1,
                'group_trash'  => 0,
                'group_modkey' => '',
                'group_syskey' => $syskey,
            ]);
        } else {
            _dbUpdate('usergroup', ['group_name' => $group_name], 'group_syskey=' . _dbEscape($syskey) . " AND group_name IN ('Einstellungen', 'Einstellungen - Vorlagen', 'Einstellungen - Dateikategorien', 'ADMIN - Alias', 'File management - delete file')");
        }
    }

    $modgroups = [
        'imagealias'     => 'Image Alias',
        'seo_log'        => 'SEO Log',
        'templateeditor' => 'Template Manager',
        'calendar'       => 'Calendar / Events',
        'statistics'     => 'Statistics',
        'ads'            => 'Banners / Ads',
        'glossary'       => 'Glossary',
        'feedimport'     => 'Feed to Article',
        'shop'           => 'Shop / Products',
        'modulpromo'     => 'phpwcms Modules',
    ];

    foreach ($modgroups as $modkey => $group_name) {
        $count = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . 'usergroup WHERE group_modkey=' . _dbEscape($modkey) . ' AND group_trash=0');
        if ($count === 0) {
            _dbInsert('usergroup', [
                'group_name'   => $group_name,
                'group_member' => $admin_members,
                'group_value'  => '',
                'group_active' => 1,
                'group_trash'  => 0,
                'group_modkey' => $modkey,
                'group_syskey' => '',
            ]);
        } else {
            _dbUpdate('usergroup', ['group_name' => $group_name], 'group_modkey=' . _dbEscape($modkey));
        }
    }

    // 3. Ensure country_name_native column exists
    if (!_dbColumnExists('country', 'country_name_native')) {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "country` ADD `country_name_native` VARCHAR(255) NOT NULL DEFAULT '' AFTER `country_name_de`", 'ALTER');
    }

    // 4. Modernize and update all ISO 3166-1 country records
    $countries = [
        ['AF', 'AFG', 4, 'AS', 'Afghanistan', 'Afghanistan', 'افغانستان', 'Asia', 'Asien'],
        ['AX', 'ALA', 248, 'EU', 'Åland Islands', 'Åland', 'Åland', 'Europe', 'Europa'],
        ['AL', 'ALB', 8, 'EU', 'Albania', 'Albanien', 'Shqipëria', 'Europe', 'Europa'],
        ['DZ', 'DZA', 12, 'AF', 'Algeria', 'Algerien', 'الجزائر', 'Africa', 'Afrika'],
        ['AS', 'ASM', 16, 'OC', 'American Samoa', 'Amerikanisch-Samoa', 'Amerika Sāmoa', 'Oceania', 'Ozeanien'],
        ['AD', 'AND', 20, 'EU', 'Andorra', 'Andorra', 'Andorra', 'Europe', 'Europa'],
        ['AO', 'AGO', 24, 'AF', 'Angola', 'Angola', 'Angola', 'Africa', 'Afrika'],
        ['AI', 'AIA', 660, 'NA', 'Anguilla', 'Anguilla', 'Anguilla', 'North America', 'Nordamerika'],
        ['AQ', 'ATA', 10, 'AN', 'Antarctica', 'Antarktis', 'Antarctica', 'Antarctica', 'Antarktis'],
        ['AG', 'ATG', 28, 'NA', 'Antigua and Barbuda', 'Antigua und Barbuda', 'Antigua and Barbuda', 'North America', 'Nordamerika'],
        ['AR', 'ARG', 32, 'SA', 'Argentina', 'Argentinien', 'Argentina', 'South America', 'Südamerika'],
        ['AM', 'ARM', 51, 'AS', 'Armenia', 'Armenien', 'Հայաստան', 'Asia', 'Asien'],
        ['AW', 'ABW', 533, 'NA', 'Aruba', 'Aruba', 'Aruba', 'North America', 'Nordamerika'],
        ['AU', 'AUS', 36, 'OC', 'Australia', 'Australien', 'Australia', 'Oceania', 'Ozeanien'],
        ['AT', 'AUT', 40, 'EU', 'Austria', 'Österreich', 'Österreich', 'Europe', 'Europa'],
        ['AZ', 'AZE', 31, 'AS', 'Azerbaijan', 'Aserbaidschan', 'Azərbaycan', 'Asia', 'Asien'],
        ['BS', 'BHS', 44, 'NA', 'Bahamas', 'Bahamas', 'Bahamas', 'North America', 'Nordamerika'],
        ['BH', 'BHR', 48, 'AS', 'Bahrain', 'Bahrain', 'البحرين', 'Asia', 'Asien'],
        ['BD', 'BGD', 50, 'AS', 'Bangladesh', 'Bangladesch', 'বাংলাদেশ', 'Asia', 'Asien'],
        ['BB', 'BRB', 52, 'NA', 'Barbados', 'Barbados', 'Barbados', 'North America', 'Nordamerika'],
        ['BY', 'BLR', 112, 'EU', 'Belarus', 'Belarus', 'Беларусь', 'Europe', 'Europa'],
        ['BE', 'BEL', 56, 'EU', 'Belgium', 'Belgien', 'België / Belgique / Belgien', 'Europe', 'Europa'],
        ['BZ', 'BLZ', 84, 'NA', 'Belize', 'Belize', 'Belize', 'North America', 'Nordamerika'],
        ['BJ', 'BEN', 204, 'AF', 'Benin', 'Benin', 'Bénin', 'Africa', 'Afrika'],
        ['BM', 'BMU', 60, 'NA', 'Bermuda', 'Bermuda', 'Bermuda', 'North America', 'Nordamerika'],
        ['BT', 'BTN', 64, 'AS', 'Bhutan', 'Bhutan', 'འབྲུག', 'Asia', 'Asien'],
        ['BO', 'BOL', 68, 'SA', 'Bolivia, Plurinational State of', 'Bolivien', 'Bolivia', 'South America', 'Südamerika'],
        ['BQ', 'BES', 535, 'NA', 'Bonaire, Sint Eustatius and Saba', 'Bonaire, Sint Eustatius und Saba', 'Bonaire, Sint Eustatius en Saba', 'North America', 'Nordamerika'],
        ['BA', 'BIH', 70, 'EU', 'Bosnia and Herzegovina', 'Bosnien und Herzegowina', 'Bosna i Hercegovina', 'Europe', 'Europa'],
        ['BW', 'BWA', 72, 'AF', 'Botswana', 'Botsuana', 'Botswana', 'Africa', 'Afrika'],
        ['BV', 'BVT', 74, 'AN', 'Bouvet Island', 'Bouvetinsel', 'Bouvetøya', 'Antarctica', 'Antarktis'],
        ['BR', 'BRA', 76, 'SA', 'Brazil', 'Brasilien', 'Brasil', 'South America', 'Südamerika'],
        ['IO', 'IOT', 86, 'AS', 'British Indian Ocean Territory', 'Britisches Territorium im Indischen Ozean', 'British Indian Ocean Territory', 'Asia', 'Asien'],
        ['BN', 'BRN', 96, 'AS', 'Brunei Darussalam', 'Brunei Darussalam', 'Brunei Darussalam', 'Asia', 'Asien'],
        ['BG', 'BGR', 100, 'EU', 'Bulgaria', 'Bulgarien', 'България', 'Europe', 'Europa'],
        ['BF', 'BFA', 854, 'AF', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Africa', 'Afrika'],
        ['BI', 'BDI', 108, 'AF', 'Burundi', 'Burundi', 'Uburundi', 'Africa', 'Afrika'],
        ['CV', 'CPV', 132, 'AF', 'Cabo Verde', 'Cabo Verde', 'Cabo Verde', 'Africa', 'Afrika'],
        ['KH', 'KHM', 116, 'AS', 'Cambodia', 'Kambodscha', 'កម្ពុជា', 'Asia', 'Asien'],
        ['CM', 'CMR', 120, 'AF', 'Cameroon', 'Kamerun', 'Cameroun', 'Africa', 'Afrika'],
        ['CA', 'CAN', 124, 'NA', 'Canada', 'Kanada', 'Canada', 'North America', 'Nordamerika'],
        ['KY', 'CYM', 136, 'NA', 'Cayman Islands', 'Kaimaninseln', 'Cayman Islands', 'North America', 'Nordamerika'],
        ['CF', 'CAF', 140, 'AF', 'Central African Republic', 'Zentralafrikanische Republik', 'République Centrafricaine', 'Africa', 'Afrika'],
        ['TD', 'TCD', 148, 'AF', 'Chad', 'Tschad', 'Tchad / تشاد', 'Africa', 'Afrika'],
        ['CL', 'CHL', 152, 'SA', 'Chile', 'Chile', 'Chile', 'South America', 'Südamerika'],
        ['CN', 'CHN', 156, 'AS', 'China', 'China', '中国', 'Asia', 'Asien'],
        ['CX', 'CXR', 162, 'AS', 'Christmas Island', 'Weihnachtsinsel', 'Christmas Island', 'Asia', 'Asien'],
        ['CC', 'CCK', 166, 'AS', 'Cocos (Keeling) Islands', 'Kokosinseln', 'Cocos (Keeling) Islands', 'Asia', 'Asien'],
        ['CO', 'COL', 170, 'SA', 'Colombia', 'Kolumbien', 'Colombia', 'South America', 'Südamerika'],
        ['KM', 'COM', 174, 'AF', 'Comoros', 'Komoren', 'Komori / جزر القمر', 'Africa', 'Afrika'],
        ['CG', 'COG', 178, 'AF', 'Congo', 'Kongo, Republik', 'Congo', 'Africa', 'Afrika'],
        ['CD', 'COD', 180, 'AF', 'Congo, Democratic Republic of the', 'Kongo, Demokratische Republik', 'République Démocratique du Congo', 'Africa', 'Afrika'],
        ['CK', 'COK', 184, 'OC', 'Cook Islands', 'Cookinseln', 'Cook Islands', 'Oceania', 'Ozeanien'],
        ['CR', 'CRI', 188, 'NA', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'North America', 'Nordamerika'],
        ['CI', 'CIV', 384, 'AF', 'Côte d\'Ivoire', 'Côte d\'Ivoire', 'Côte d\'Ivoire', 'Africa', 'Afrika'],
        ['HR', 'HRV', 191, 'EU', 'Croatia', 'Kroatien', 'Hrvatska', 'Europe', 'Europa'],
        ['CU', 'CUB', 192, 'NA', 'Cuba', 'Kuba', 'Cuba', 'North America', 'Nordamerika'],
        ['CW', 'CUW', 531, 'NA', 'Curaçao', 'Curaçao', 'Kòrsou', 'North America', 'Nordamerika'],
        ['CY', 'CYP', 196, 'AS', 'Cyprus', 'Zypern', 'Κύπρος / Kıbrıs', 'Asia', 'Asien'],
        ['CZ', 'CZE', 203, 'EU', 'Czechia', 'Tschechien', 'Česká republika', 'Europe', 'Europa'],
        ['DK', 'DNK', 208, 'EU', 'Denmark', 'Dänemark', 'Danmark', 'Europe', 'Europa'],
        ['DJ', 'DJI', 262, 'AF', 'Djibouti', 'Dschibuti', 'Djibouti / جيبوتي', 'Africa', 'Afrika'],
        ['DM', 'DMA', 212, 'NA', 'Dominica', 'Dominica', 'Dominica', 'North America', 'Nordamerika'],
        ['DO', 'DOM', 214, 'NA', 'Dominican Republic', 'Dominikanische Republik', 'República Dominicana', 'North America', 'Nordamerika'],
        ['EC', 'ECU', 218, 'SA', 'Ecuador', 'Ecuador', 'Ecuador', 'South America', 'Südamerika'],
        ['EG', 'EGY', 818, 'AF', 'Egypt', 'Ägypten', 'مصر', 'Africa', 'Afrika'],
        ['SV', 'SLV', 222, 'NA', 'El Salvador', 'El Salvador', 'El Salvador', 'North America', 'Nordamerika'],
        ['GQ', 'GNQ', 226, 'AF', 'Equatorial Guinea', 'Äquatorialguinea', 'Guinea Ecuatorial', 'Africa', 'Afrika'],
        ['ER', 'ERI', 232, 'AF', 'Eritrea', 'Eritrea', 'ኤርትራ / إرتريا', 'Africa', 'Afrika'],
        ['EE', 'EST', 233, 'EU', 'Estonia', 'Estland', 'Eesti', 'Europe', 'Europa'],
        ['SZ', 'SWZ', 748, 'AF', 'Eswatini', 'Eswatini', 'eSwatini', 'Africa', 'Afrika'],
        ['ET', 'ETH', 231, 'AF', 'Ethiopia', 'Äthiopien', 'ኢትዮጵያ', 'Africa', 'Afrika'],
        ['FK', 'FLK', 238, 'SA', 'Falkland Islands (Malvinas)', 'Falklandinseln', 'Falkland Islands', 'South America', 'Südamerika'],
        ['FO', 'FRO', 234, 'EU', 'Faroe Islands', 'Färöer', 'Føroyar', 'Europe', 'Europa'],
        ['FJ', 'FJI', 242, 'OC', 'Fiji', 'Fidschi', 'Viti / Fiji', 'Oceania', 'Ozeanien'],
        ['FI', 'FIN', 246, 'EU', 'Finland', 'Finnland', 'Suomi', 'Europe', 'Europa'],
        ['FR', 'FRA', 250, 'EU', 'France', 'Frankreich', 'France', 'Europe', 'Europa'],
        ['GF', 'GUF', 254, 'SA', 'French Guiana', 'Französisch-Guayana', 'Guyane française', 'South America', 'Südamerika'],
        ['PF', 'PYF', 258, 'OC', 'French Polynesia', 'Französisch-Polynesien', 'Polynésie française', 'Oceania', 'Ozeanien'],
        ['TF', 'ATF', 260, 'AN', 'French Southern Territories', 'Französische Süd- und Antarktisgebiete', 'Terres australes et antarctiques françaises', 'Antarctica', 'Antarktis'],
        ['GA', 'GAB', 266, 'AF', 'Gabon', 'Gabun', 'Gabon', 'Africa', 'Afrika'],
        ['GM', 'GMB', 270, 'AF', 'Gambia', 'Gambia', 'The Gambia', 'Africa', 'Afrika'],
        ['GE', 'GEO', 268, 'AS', 'Georgia', 'Georgien', 'საქართველო', 'Asia', 'Asien'],
        ['DE', 'DEU', 276, 'EU', 'Germany', 'Deutschland', 'Deutschland', 'Europe', 'Europa'],
        ['GH', 'GHA', 288, 'AF', 'Ghana', 'Ghana', 'Ghana', 'Africa', 'Afrika'],
        ['GI', 'GIB', 292, 'EU', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Europe', 'Europa'],
        ['GR', 'GRC', 300, 'EU', 'Greece', 'Griechenland', 'Ελλάδα', 'Europe', 'Europa'],
        ['GL', 'GRL', 304, 'NA', 'Greenland', 'Grönland', 'Kalaallit Nunaat', 'North America', 'Nordamerika'],
        ['GD', 'GRD', 308, 'NA', 'Grenada', 'Grenada', 'Grenada', 'North America', 'Nordamerika'],
        ['GP', 'GLP', 312, 'NA', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'North America', 'Nordamerika'],
        ['GU', 'GUM', 316, 'OC', 'Guam', 'Guam', 'Guåhan', 'Oceania', 'Ozeanien'],
        ['GT', 'GTM', 320, 'NA', 'Guatemala', 'Guatemala', 'Guatemala', 'North America', 'Nordamerika'],
        ['GG', 'GGY', 831, 'EU', 'Guernsey', 'Guernsey', 'Guernsey', 'Europe', 'Europa'],
        ['GN', 'GIN', 324, 'AF', 'Guinea', 'Guinea', 'Guinée', 'Africa', 'Afrika'],
        ['GW', 'GNB', 624, 'AF', 'Guinea-Bissau', 'Guinea-Bissau', 'Guiné-Bissau', 'Africa', 'Afrika'],
        ['GY', 'GUY', 328, 'SA', 'Guyana', 'Guyana', 'Guyana', 'South America', 'Südamerika'],
        ['HT', 'HTI', 332, 'NA', 'Haiti', 'Haiti', 'Ayiti', 'North America', 'Nordamerika'],
        ['HM', 'HMD', 334, 'AN', 'Heard Island and McDonald Islands', 'Heard und McDonaldinseln', 'Heard Island and McDonald Islands', 'Antarctica', 'Antarktis'],
        ['VA', 'VAT', 336, 'EU', 'Holy See (Vatican City State)', 'Vatikanstadt', 'Civitas Vaticana', 'Europe', 'Europa'],
        ['HN', 'HND', 340, 'NA', 'Honduras', 'Honduras', 'Honduras', 'North America', 'Nordamerika'],
        ['HK', 'HKG', 344, 'AS', 'Hong Kong', 'Hongkong', '香港', 'Asia', 'Asien'],
        ['HU', 'HUN', 348, 'EU', 'Hungary', 'Ungarn', 'Magyarország', 'Europe', 'Europa'],
        ['IS', 'ISL', 352, 'EU', 'Iceland', 'Island', 'Ísland', 'Europe', 'Europa'],
        ['IN', 'IND', 356, 'AS', 'India', 'Indien', 'भारत / India', 'Asia', 'Asien'],
        ['ID', 'IDN', 360, 'AS', 'Indonesia', 'Indonesien', 'Indonesia', 'Asia', 'Asien'],
        ['IR', 'IRN', 364, 'AS', 'Iran, Islamic Republic of', 'Iran', 'ایران', 'Asia', 'Asien'],
        ['IQ', 'IRQ', 368, 'AS', 'Iraq', 'Irak', 'العراق', 'Asia', 'Asien'],
        ['IE', 'IRL', 372, 'EU', 'Ireland', 'Irland', 'Éire / Ireland', 'Europe', 'Europa'],
        ['IM', 'IMN', 833, 'EU', 'Isle of Man', 'Insel Man', 'Mannin / Isle of Man', 'Europe', 'Europa'],
        ['IL', 'ISR', 376, 'AS', 'Israel', 'Israel', 'ישראל', 'Asia', 'Asien'],
        ['IT', 'ITA', 380, 'EU', 'Italy', 'Italien', 'Italia', 'Europe', 'Europa'],
        ['JM', 'JAM', 388, 'NA', 'Jamaica', 'Jamaika', 'Jamaica', 'North America', 'Nordamerika'],
        ['JP', 'JPN', 392, 'AS', 'Japan', 'Japan', '日本', 'Asia', 'Asien'],
        ['JE', 'JEY', 832, 'EU', 'Jersey', 'Jersey', 'Jersey', 'Europe', 'Europa'],
        ['JO', 'JOR', 400, 'AS', 'Jordan', 'Jordanien', 'الأردن', 'Asia', 'Asien'],
        ['KZ', 'KAZ', 398, 'AS', 'Kazakhstan', 'Kasachstan', 'Қазақстан / Казахстан', 'Asia', 'Asien'],
        ['KE', 'KEN', 404, 'AF', 'Kenya', 'Kenia', 'Kenya', 'Africa', 'Afrika'],
        ['KI', 'KIR', 296, 'OC', 'Kiribati', 'Kiribati', 'Kiribati', 'Oceania', 'Ozeanien'],
        ['KP', 'PRK', 408, 'AS', 'Korea, Democratic People\'s Republic of', 'Nordkorea', '조선민주주의인민공화국', 'Asia', 'Asien'],
        ['KR', 'KOR', 410, 'AS', 'Korea, Republic of', 'Südkorea', '대한민국', 'Asia', 'Asien'],
        ['XK', 'XKX', 0, 'EU', 'Kosovo', 'Kosovo', 'Kosovë / Косово', 'Europe', 'Europa'],
        ['KW', 'KWT', 414, 'AS', 'Kuwait', 'Kuwait', 'الكويت', 'Asia', 'Asien'],
        ['KG', 'KGZ', 417, 'AS', 'Kyrgyzstan', 'Kirgisistan', 'Кыргызстан', 'Asia', 'Asien'],
        ['LA', 'LAO', 418, 'AS', 'Lao People\'s Democratic Republic', 'Laos', 'ສປປ ລາວ', 'Asia', 'Asien'],
        ['LV', 'LVA', 428, 'EU', 'Latvia', 'Lettland', 'Latvija', 'Europe', 'Europa'],
        ['LB', 'LBN', 422, 'AS', 'Lebanon', 'Libanon', 'لبنان', 'Asia', 'Asien'],
        ['LS', 'LSO', 426, 'AF', 'Lesotho', 'Lesotho', 'Lesotho', 'Africa', 'Afrika'],
        ['LR', 'LBR', 430, 'AF', 'Liberia', 'Liberia', 'Liberia', 'Africa', 'Afrika'],
        ['LY', 'LBY', 434, 'AF', 'Libya', 'Libyen', 'ليبيا', 'Africa', 'Afrika'],
        ['LI', 'LIE', 438, 'EU', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Europe', 'Europa'],
        ['LT', 'LTU', 440, 'EU', 'Lithuania', 'Litauen', 'Lietuva', 'Europe', 'Europa'],
        ['LU', 'LUX', 442, 'EU', 'Luxembourg', 'Luxemburg', 'Lëtzebuerg / Luxembourg / Luxemburg', 'Europe', 'Europa'],
        ['MO', 'MAC', 446, 'AS', 'Macao', 'Macau', '澳門 / Macau', 'Asia', 'Asien'],
        ['MG', 'MDG', 450, 'AF', 'Madagascar', 'Madagaskar', 'Madagasikara / Madagascar', 'Africa', 'Afrika'],
        ['MW', 'MWI', 454, 'AF', 'Malawi', 'Malawi', 'Malawi', 'Africa', 'Afrika'],
        ['MY', 'MYS', 458, 'AS', 'Malaysia', 'Malaysia', 'Malaysia', 'Asia', 'Asien'],
        ['MV', 'MDV', 462, 'AS', 'Maldives', 'Malediven', 'ދިވެހިރާއްޖެ', 'Asia', 'Asien'],
        ['ML', 'MLI', 466, 'AF', 'Mali', 'Mali', 'Mali', 'Africa', 'Afrika'],
        ['MT', 'MLT', 470, 'EU', 'Malta', 'Malta', 'Malta', 'Europe', 'Europa'],
        ['MH', 'MHL', 584, 'OC', 'Marshall Islands', 'Marshallinseln', 'M̧ajeļ', 'Oceania', 'Ozeanien'],
        ['MQ', 'MTQ', 474, 'NA', 'Martinique', 'Martinique', 'Martinique', 'North America', 'Nordamerika'],
        ['MR', 'MRT', 478, 'AF', 'Mauritania', 'Mauretanien', 'موريتانيا', 'Africa', 'Afrika'],
        ['MU', 'MUS', 480, 'AF', 'Mauritius', 'Mauritius', 'Maurice / Mauritius', 'Africa', 'Afrika'],
        ['YT', 'MYT', 175, 'AF', 'Mayotte', 'Mayotte', 'Mayotte', 'Africa', 'Afrika'],
        ['MX', 'MEX', 484, 'NA', 'Mexico', 'Mexiko', 'México', 'North America', 'Nordamerika'],
        ['FM', 'FSM', 583, 'OC', 'Micronesia, Federated States of', 'Mikronesien', 'Micronesia', 'Oceania', 'Ozeanien'],
        ['MD', 'MDA', 498, 'EU', 'Moldova, Republic of', 'Moldau', 'Moldova', 'Europe', 'Europa'],
        ['MC', 'MCO', 492, 'EU', 'Monaco', 'Monaco', 'Monaco', 'Europe', 'Europa'],
        ['MN', 'MNG', 496, 'AS', 'Mongolia', 'Mongolei', 'Монгол улс', 'Asia', 'Asien'],
        ['ME', 'MNE', 499, 'EU', 'Montenegro', 'Montenegro', 'Crna Gora', 'Europe', 'Europa'],
        ['MS', 'MSR', 500, 'NA', 'Montserrat', 'Montserrat', 'Montserrat', 'North America', 'Nordamerika'],
        ['MA', 'MAR', 504, 'AF', 'Morocco', 'Marokko', 'المغرب', 'Africa', 'Afrika'],
        ['MZ', 'MOZ', 508, 'AF', 'Mozambique', 'Mosambik', 'Moçambique', 'Africa', 'Afrika'],
        ['MM', 'MMR', 104, 'AS', 'Myanmar', 'Myanmar', 'မြန်မာ', 'Asia', 'Asien'],
        ['NA', 'NAM', 516, 'AF', 'Namibia', 'Namibia', 'Namibia', 'Africa', 'Afrika'],
        ['NR', 'NRU', 520, 'OC', 'Nauru', 'Nauru', 'Naoero / Nauru', 'Oceania', 'Ozeanien'],
        ['NP', 'NPL', 524, 'AS', 'Nepal', 'Nepal', 'नेपाल', 'Asia', 'Asien'],
        ['NL', 'NLD', 528, 'EU', 'Netherlands', 'Niederlande', 'Nederland', 'Europe', 'Europa'],
        ['NC', 'NCL', 540, 'OC', 'New Caledonia', 'Neukaledonien', 'Nouvelle-Calédonie', 'Oceania', 'Ozeanien'],
        ['NZ', 'NZL', 554, 'OC', 'New Zealand', 'Neuseeland', 'New Zealand / Aotearoa', 'Oceania', 'Ozeanien'],
        ['NI', 'NIC', 558, 'NA', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'North America', 'Nordamerika'],
        ['NE', 'NER', 562, 'AF', 'Niger', 'Niger', 'Niger', 'Africa', 'Afrika'],
        ['NG', 'NGA', 566, 'AF', 'Nigeria', 'Nigeria', 'Nigeria', 'Africa', 'Afrika'],
        ['NU', 'NIU', 570, 'OC', 'Niue', 'Niue', 'Niuē', 'Oceania', 'Ozeanien'],
        ['NF', 'NFK', 574, 'OC', 'Norfolk Island', 'Norfolkinsel', 'Norfolk Island', 'Oceania', 'Ozeanien'],
        ['MK', 'MKD', 807, 'EU', 'North Macedonia', 'Nordmazedonien', 'Северна Македонија', 'Europe', 'Europa'],
        ['MP', 'MNP', 580, 'OC', 'Northern Mariana Islands', 'Nördliche Marianen', 'Northern Mariana Islands', 'Oceania', 'Ozeanien'],
        ['NO', 'NOR', 578, 'EU', 'Norway', 'Norwegen', 'Norge', 'Europe', 'Europa'],
        ['OM', 'OMN', 512, 'AS', 'Oman', 'Oman', 'عُمان', 'Asia', 'Asien'],
        ['PK', 'PAK', 586, 'AS', 'Pakistan', 'Pakistan', 'پاکستان', 'Asia', 'Asien'],
        ['PW', 'PLW', 585, 'OC', 'Palau', 'Palau', 'Belau / Palau', 'Oceania', 'Ozeanien'],
        ['PS', 'PSE', 275, 'AS', 'Palestine, State of', 'Palästina', 'فلسطين', 'Asia', 'Asien'],
        ['PA', 'PAN', 591, 'NA', 'Panama', 'Panama', 'Panamá', 'North America', 'Nordamerika'],
        ['PG', 'PNG', 598, 'OC', 'Papua New Guinea', 'Papua-Neuguinea', 'Papua Niugini', 'Oceania', 'Ozeanien'],
        ['PY', 'PRY', 600, 'SA', 'Paraguay', 'Paraguay', 'Paraguay', 'South America', 'Südamerika'],
        ['PE', 'PER', 604, 'SA', 'Peru', 'Peru', 'Perú', 'South America', 'Südamerika'],
        ['PH', 'PHL', 608, 'AS', 'Philippines', 'Philippinen', 'Pilipinas / Philippines', 'Asia', 'Asien'],
        ['PN', 'PCN', 612, 'OC', 'Pitcairn', 'Pitcairninseln', 'Pitcairn Islands', 'Oceania', 'Ozeanien'],
        ['PL', 'POL', 616, 'EU', 'Poland', 'Polen', 'Polska', 'Europe', 'Europa'],
        ['PT', 'PRT', 620, 'EU', 'Portugal', 'Portugal', 'Portugal', 'Europe', 'Europa'],
        ['PR', 'PRI', 630, 'NA', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'North America', 'Nordamerika'],
        ['QA', 'QAT', 634, 'AS', 'Qatar', 'Katar', 'قطر', 'Asia', 'Asien'],
        ['RE', 'REU', 638, 'AF', 'Réunion', 'Réunion', 'La Réunion', 'Africa', 'Afrika'],
        ['RO', 'ROU', 642, 'EU', 'Romania', 'Rumänien', 'România', 'Europe', 'Europa'],
        ['RU', 'RUS', 643, 'EU', 'Russian Federation', 'Russische Föderation', 'Россия', 'Europe', 'Europa'],
        ['RW', 'RWA', 646, 'AF', 'Rwanda', 'Ruanda', 'u Rwanda', 'Africa', 'Afrika'],
        ['BL', 'BLM', 652, 'NA', 'Saint Barthélemy', 'Saint-Barthélemy', 'Saint-Barthélemy', 'North America', 'Nordamerika'],
        ['SH', 'SHN', 654, 'AF', 'Saint Helena, Ascension and Tristan da Cunha', 'St. Helena, Ascension und Tristan da Cunha', 'Saint Helena', 'Africa', 'Afrika'],
        ['KN', 'KNA', 659, 'NA', 'Saint Kitts and Nevis', 'St. Kitts und Nevis', 'Saint Kitts and Nevis', 'North America', 'Nordamerika'],
        ['LC', 'LCA', 662, 'NA', 'Saint Lucia', 'St. Lucia', 'Saint Lucia', 'North America', 'Nordamerika'],
        ['MF', 'MAF', 663, 'NA', 'Saint Martin (French part)', 'Saint-Martin (französischer Teil)', 'Saint-Martin', 'North America', 'Nordamerika'],
        ['PM', 'SPM', 666, 'NA', 'Saint Pierre and Miquelon', 'Saint-Pierre und Miquelon', 'Saint-Pierre-et-Miquelon', 'North America', 'Nordamerika'],
        ['VC', 'VCT', 670, 'NA', 'Saint Vincent and the Grenadines', 'St. Vincent und die Grenadinen', 'Saint Vincent and the Grenadines', 'North America', 'Nordamerika'],
        ['WS', 'WSM', 882, 'OC', 'Samoa', 'Samoa', 'Sāmoa', 'Oceania', 'Ozeanien'],
        ['SM', 'SMR', 674, 'EU', 'San Marino', 'San Marino', 'San Marino', 'Europe', 'Europa'],
        ['ST', 'STP', 678, 'AF', 'Sao Tome and Principe', 'São Tomé und Príncipe', 'São Tomé e Príncipe', 'Africa', 'Afrika'],
        ['SA', 'SAU', 682, 'AS', 'Saudi Arabia', 'Saudi-Arabien', 'المملكة العربية السعودية', 'Asia', 'Asien'],
        ['SN', 'SEN', 686, 'AF', 'Senegal', 'Senegal', 'Sénégal', 'Africa', 'Afrika'],
        ['RS', 'SRB', 688, 'EU', 'Serbia', 'Serbien', 'Srbija / Србија', 'Europe', 'Europa'],
        ['SC', 'SYC', 690, 'AF', 'Seychelles', 'Seychellen', 'Seychelles / Sesel', 'Africa', 'Afrika'],
        ['SL', 'SLE', 694, 'AF', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Africa', 'Afrika'],
        ['SG', 'SGP', 702, 'AS', 'Singapore', 'Singapur', 'Singapore / 新加坡 / Singapura / சிங்கப்பூர்', 'Asia', 'Asien'],
        ['SX', 'SXM', 534, 'NA', 'Sint Maarten (Dutch part)', 'Sint Maarten (niederländischer Teil)', 'Sint Maarten', 'North America', 'Nordamerika'],
        ['SK', 'SVK', 703, 'EU', 'Slovakia', 'Slowakei', 'Slovensko', 'Europe', 'Europa'],
        ['SI', 'SVN', 705, 'EU', 'Slovenia', 'Slowenien', 'Slovenija', 'Europe', 'Europa'],
        ['SB', 'SLB', 90, 'OC', 'Solomon Islands', 'Salomonen', 'Solomon Islands', 'Oceania', 'Ozeanien'],
        ['SO', 'SOM', 706, 'AF', 'Somalia', 'Somalia', 'Soomaaliya / الصومال', 'Africa', 'Afrika'],
        ['ZA', 'ZAF', 710, 'AF', 'South Africa', 'Südafrika', 'South Africa / Suid-Afrika', 'Africa', 'Afrika'],
        ['GS', 'SGS', 239, 'AN', 'South Georgia and the South Sandwich Islands', 'Südgeorgien und die Südlichen Sandwichinseln', 'South Georgia and the South Sandwich Islands', 'Antarctica', 'Antarktis'],
        ['SS', 'SSD', 728, 'AF', 'South Sudan', 'Südsudan', 'South Sudan', 'Africa', 'Afrika'],
        ['ES', 'ESP', 724, 'EU', 'Spain', 'Spanien', 'España', 'Europe', 'Europa'],
        ['LK', 'LKA', 144, 'AS', 'Sri Lanka', 'Sri Lanka', 'ශ්‍රී ලංකා / இலங்கை', 'Asia', 'Asien'],
        ['SD', 'SDN', 729, 'AF', 'Sudan', 'Sudan', 'السودان', 'Africa', 'Afrika'],
        ['SR', 'SUR', 740, 'SA', 'Suriname', 'Suriname', 'Suriname', 'South America', 'Südamerika'],
        ['SJ', 'SJM', 744, 'EU', 'Svalbard and Jan Mayen', 'Spitzbergen und Jan Mayen', 'Svalbard og Jan Mayen', 'Europe', 'Europa'],
        ['SE', 'SWE', 752, 'EU', 'Sweden', 'Schweden', 'Sverige', 'Europe', 'Europa'],
        ['CH', 'CHE', 756, 'EU', 'Switzerland', 'Schweiz', 'Schweiz / Suisse / Svizzera / Svizra', 'Europe', 'Europa'],
        ['SY', 'SYR', 760, 'AS', 'Syrian Arab Republic', 'Syrien', 'سوريا', 'Asia', 'Asien'],
        ['TW', 'TWN', 158, 'AS', 'Taiwan, Province of China', 'Taiwan', '臺灣', 'Asia', 'Asien'],
        ['TJ', 'TJK', 762, 'AS', 'Tajikistan', 'Tadschikistan', 'Тоҷикистон', 'Asia', 'Asien'],
        ['TZ', 'TZA', 834, 'AF', 'Tanzania, United Republic of', 'Tansania', 'Tanzania', 'Africa', 'Afrika'],
        ['TH', 'THA', 764, 'AS', 'Thailand', 'Thailand', 'ประเทศไทย', 'Asia', 'Asien'],
        ['TL', 'TLS', 626, 'AS', 'Timor-Leste', 'Timor-Leste', 'Timor-Leste', 'Asia', 'Asien'],
        ['TG', 'TGO', 768, 'AF', 'Togo', 'Togo', 'Togo', 'Africa', 'Afrika'],
        ['TK', 'TKL', 772, 'OC', 'Tokelau', 'Tokelau', 'Tokelau', 'Oceania', 'Ozeanien'],
        ['TO', 'TON', 776, 'OC', 'Tonga', 'Tonga', 'Tonga', 'Oceania', 'Ozeanien'],
        ['TT', 'TTO', 780, 'NA', 'Trinidad and Tobago', 'Trinidad und Tobago', 'Trinidad and Tobago', 'North America', 'Nordamerika'],
        ['TN', 'TUN', 788, 'AF', 'Tunisia', 'Tunesien', 'تونس', 'Africa', 'Afrika'],
        ['TR', 'TUR', 792, 'AS', 'Türkiye', 'Türkei', 'Türkiye', 'Asia', 'Asien'],
        ['TM', 'TKM', 795, 'AS', 'Turkmenistan', 'Turkmenistan', 'Türkmenistan', 'Asia', 'Asien'],
        ['TC', 'TCA', 796, 'NA', 'Turks and Caicos Islands', 'Turks- und Caicosinseln', 'Turks and Caicos Islands', 'North America', 'Nordamerika'],
        ['TV', 'TUV', 798, 'OC', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Oceania', 'Ozeanien'],
        ['UG', 'UGA', 800, 'AF', 'Uganda', 'Uganda', 'Uganda', 'Africa', 'Afrika'],
        ['UA', 'UKR', 804, 'EU', 'Ukraine', 'Ukraine', 'Україна', 'Europe', 'Europa'],
        ['AE', 'ARE', 784, 'AS', 'United Arab Emirates', 'Vereinigte Arabische Emirate', 'الإمارات العربية المتحدة', 'Asia', 'Asien'],
        ['GB', 'GBR', 826, 'EU', 'United Kingdom', 'Vereinigtes Königreich', 'United Kingdom', 'Europe', 'Europa'],
        ['US', 'USA', 840, 'NA', 'United States of America', 'Vereinigte Staaten von Amerika', 'United States', 'North America', 'Nordamerika'],
        ['UM', 'UMI', 581, 'OC', 'United States Minor Outlying Islands', 'Kleinere Inselbesitzungen der Vereinigten Staaten', 'United States Minor Outlying Islands', 'Oceania', 'Ozeanien'],
        ['UY', 'URY', 858, 'SA', 'Uruguay', 'Uruguay', 'Uruguay', 'South America', 'Südamerika'],
        ['UZ', 'UZB', 860, 'AS', 'Uzbekistan', 'Usbekistan', 'O‘zbekiston', 'Asia', 'Asien'],
        ['VU', 'VUT', 548, 'OC', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Oceania', 'Ozeanien'],
        ['VE', 'VEN', 862, 'SA', 'Venezuela, Bolivarian Republic of', 'Venezuela', 'Venezuela', 'South America', 'Südamerika'],
        ['VN', 'VNM', 704, 'AS', 'Viet Nam', 'Vietnam', 'Việt Nam', 'Asia', 'Asien'],
        ['VG', 'VGB', 92, 'NA', 'Virgin Islands, British', 'Britische Jungferninseln', 'British Virgin Islands', 'North America', 'Nordamerika'],
        ['VI', 'VIR', 850, 'NA', 'Virgin Islands, U.S.', 'Amerikanische Jungferninseln', 'U.S. Virgin Islands', 'North America', 'Nordamerika'],
        ['WF', 'WLF', 876, 'OC', 'Wallis and Futuna', 'Wallis und Futuna', 'Wallis-et-Futuna', 'Oceania', 'Ozeanien'],
        ['EH', 'ESH', 732, 'AF', 'Western Sahara', 'Westsahara', 'الصحراء الغربية', 'Africa', 'Afrika'],
        ['YE', 'YEM', 887, 'AS', 'Yemen', 'Jemen', 'اليمن', 'Asia', 'Asien'],
        ['ZM', 'ZMB', 894, 'AF', 'Zambia', 'Sambia', 'Zambia', 'Africa', 'Afrika'],
        ['ZW', 'ZWE', 716, 'AF', 'Zimbabwe', 'Simbabwe', 'Zimbabwe', 'Africa', 'Afrika'],
    ];

    $is_utf8 = !defined('PHPWCMS_CHARSET') || PHPWCMS_CHARSET === 'utf-8';
    $conv = static function ($str) use ($is_utf8) {
        if (!$is_utf8 && !empty($str) && function_exists('mb_encode_numericentity')) {
            return mb_encode_numericentity($str, [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8');
        }
        return $str;
    };

    foreach ($countries as $c) {
        $count = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . 'country WHERE country_iso=' . _dbEscape($c[0]));
        $data = [
            'country_iso3'           => $c[1],
            'country_isonum'         => $c[2],
            'country_continent_code' => $c[3],
            'country_name'           => $conv($c[4]),
            'country_name_de'        => $conv($c[5]),
            'country_name_native'    => $conv($c[6]),
            'country_continent'      => $conv($c[7]),
            'country_continent_de'   => $conv($c[8]),
        ];
        if ($count > 0) {
            _dbUpdate('country', $data, 'country_iso=' . _dbEscape($c[0]));
        } else {
            $data['country_iso'] = $c[0];
            $data['country_region'] = '';
            $data['country_region_de'] = '';
            _dbInsert('country', $data);
        }
    }

    // Clean up obsolete / dissolved country codes
    _dbQuery('DELETE FROM ' . DB_PREPEND . "country WHERE country_iso IN ('AN', 'YU')", 'DELETE');

    return $status;
}
