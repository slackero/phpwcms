#####################################################
#
#  PHPWCMS SQL Update
#
#  Upgrade release 27-08-2004 to 1.2.6-DEV
#
#####################################################


ALTER TABLE `phpwcms_articlecontent` ADD `acontent_block` VARCHAR( 200 ) DEFAULT 'CONTENT' NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_anchor` INT( 1 ) NOT NULL ;

ALTER TABLE `phpwcms_userlog` ADD `logged_section` INT( 1 ) NOT NULL ;

ALTER TABLE `phpwcms_articlecat` ADD `acat_cache` VARCHAR( 10 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecat` ADD `acat_nosearch` CHAR( 1 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecat` ADD `acat_nositemap` INT( 1 ) DEFAULT '1' NOT NULL ;
ALTER TABLE `phpwcms_articlecat` ADD `acat_permit` TEXT NOT NULL ;
UPDATE `phpwcms_articlecat` SET acat_nositemap=1 ;
ALTER TABLE `phpwcms_articlecat` ADD `acat_maxlist` INT( 11) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecat` ADD `acat_cntpart` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `phpwcms_file` ADD `f_hash` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `phpwcms_file` ADD `f_dlstart` INT NOT NULL ;
ALTER TABLE `phpwcms_file` ADD `f_dlfinal` INT NOT NULL ;
ALTER TABLE `phpwcms_file` ADD `f_refid` INT NOT NULL ;

ALTER TABLE `phpwcms_article` ADD `article_cache` VARCHAR( 10 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_nosearch` CHAR( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_nositemap` INT( 1 ) DEFAULT '1' NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_aliasid` INT NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_headerdata` INT( 1 ) NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_morelink` INT( 1 ) DEFAULT '1' NOT NULL ;
UPDATE `phpwcms_article` SET article_nositemap=1 ;

ALTER TABLE `phpwcms_user` ADD `usr_wysiwyg` INT( 2 ) NOT NULL ;
ALTER TABLE `phpwcms_user` ADD `usr_fe` INT( 1 ) NOT NULL ;
ALTER TABLE `phpwcms_user` ADD `usr_vars` MEDIUMTEXT NOT NULL ;
UPDATE `phpwcms_user` SET usr_fe =2 ;


# user group

CREATE TABLE `phpwcms_usergroup` (
  `group_id` int(11) NOT NULL auto_increment,
  `group_name` varchar(200) NOT NULL default '',
  `group_member` mediumtext NOT NULL,
  `group_value` longblob NOT NULL,
  `group_timestamp` timestamp NOT NULL,
  `group_trash` int(1) NOT NULL default '0',
  `group_active` int(1) NOT NULL default '0',
  PRIMARY KEY  (`group_id`),
  KEY `group_member` (`group_member`(255))
);


# cache

CREATE TABLE `phpwcms_cache` (
  `cache_id` int(11) NOT NULL auto_increment,
  `cache_hash` varchar(50) NOT NULL default '',
  `cache_uri` text NOT NULL,
  `cache_cid` int(11) NOT NULL default '0',
  `cache_aid` int(11) NOT NULL default '0',
  `cache_timeout` varchar(20) NOT NULL default '0',
  `cache_isprint` int(1) NOT NULL default '0',
  `cache_changed` int(14) default NULL,
  `cache_use` int(1) NOT NULL default '0',
  `cache_searchable` int(1) NOT NULL default '0',
  `cache_page` longtext NOT NULL,
  `cache_stripped` longtext NOT NULL,
  PRIMARY KEY  (`cache_id`),
  KEY `cache_hash` (`cache_hash`),
  FULLTEXT KEY `cache_stripped` (`cache_stripped`)
);


# forum

CREATE TABLE `phpwcms_forum` (
  `forum_id` int(11) NOT NULL auto_increment,
  `forum_entry` tinyint(1) NOT NULL default '0',
  `forum_cid` int(11) NOT NULL default '0',
  `forum_pid` int(11) NOT NULL default '0',
  `forum_uid` int(11) NOT NULL default '0',
  `forum_ctopic` int(11) NOT NULL default '0',
  `forum_cpost` int(11) NOT NULL default '0',
  `forum_title` text NOT NULL,
  `forum_created` int(10) NOT NULL default '0',
  `forum_changed` timestamp NOT NULL,
  `forum_status` int(1) NOT NULL default '0',
  `forum_deleted` int(1) NOT NULL default '0',
  `forum_text` mediumtext NOT NULL,
  `forum_var` blob NOT NULL,
  `forum_lastpost` mediumtext NOT NULL,
  PRIMARY KEY  (`forum_id`)
);


# image cache

CREATE TABLE `phpwcms_imgcache` (
  `imgcache_id` int(11) NOT NULL auto_increment,
  `imgcache_hash` varchar(50) NOT NULL default '',
  `imgcache_imgname` varchar(255) NOT NULL default '',
  `imgcache_width` int(11) NOT NULL default '0',
  `imgcache_height` int(11) NOT NULL default '0',
  `imgcache_wh` varchar(255) NOT NULL default '',
  `imgcache_timestamp` timestamp NOT NULL,
  `imgcache_trash` int(1) NOT NULL default '0',
  PRIMARY KEY  (`imgcache_id`),
  KEY `imgcache_hash` (`imgcache_hash`)
);


# sys value

CREATE TABLE `phpwcms_sysvalue` (
  `sysvalue_key` varchar(255) NOT NULL default '',
  `sysvalue_tstamp` timestamp NOT NULL,
  `sysvalue_value` mediumblob NOT NULL,
  PRIMARY KEY  (`sysvalue_key`),
  FULLTEXT KEY `sysvalue_key` (`sysvalue_key`)
);


# module

CREATE TABLE `phpwcms_module` (
  `module_id` int(11) NOT NULL auto_increment,
  `module_timestamp` timestamp NOT NULL,
  `module_name` varchar(30) NOT NULL default '',
  `module_mode` tinyint(1) NOT NULL default '0',
  `module_title` text NOT NULL,
  `module_description` text NOT NULL,
  PRIMARY KEY  (`module_id`)
);


# map

CREATE TABLE `phpwcms_map` (
  `map_id` int(11) NOT NULL auto_increment,
  `map_cid` int(11) NOT NULL default '0',
  `map_x` int(5) NOT NULL default '0',
  `map_y` int(5) NOT NULL default '0',
  `map_title` text NOT NULL,
  `map_zip` varchar(255) NOT NULL default '',
  `map_city` text NOT NULL,
  `map_deleted` int(1) NOT NULL default '0',
  `map_entry` text NOT NULL,
  `map_vars` text NOT NULL,
  PRIMARY KEY  (`map_id`)
);


# countries

DROP TABLE IF EXISTS `phpwcms_country`;
CREATE TABLE `phpwcms_country` (
  `country_id` int(4) NOT NULL auto_increment,
  `country_iso` char(2) NOT NULL default '',
  `country_name` varchar(100) NOT NULL default '',
  `country_name_de` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`country_id`),
  UNIQUE KEY `country_iso` (`country_iso`),
  UNIQUE KEY `country_name` (`country_name`)
);

INSERT INTO `phpwcms_country` VALUES (1, 'AF', 'Afghanistan', 'Afghanistan');
INSERT INTO `phpwcms_country` VALUES (2, 'AL', 'Albania', 'Albanien');
INSERT INTO `phpwcms_country` VALUES (3, 'DZ', 'Algeria', 'Algerien');
INSERT INTO `phpwcms_country` VALUES (4, 'AS', 'American Samoa', 'Amerikanisch Samoa');
INSERT INTO `phpwcms_country` VALUES (5, 'AD', 'Andorra', 'Andorra');
INSERT INTO `phpwcms_country` VALUES (6, 'AO', 'Angola', 'Angola');
INSERT INTO `phpwcms_country` VALUES (7, 'AI', 'Anguilla', 'Anguilla');
INSERT INTO `phpwcms_country` VALUES (8, 'AQ', 'Antarctica', 'Antarktis');
INSERT INTO `phpwcms_country` VALUES (9, 'AG', 'Antigua and Barbuda', 'Antigua und Barbuda');
INSERT INTO `phpwcms_country` VALUES (10, 'AR', 'Argentina', 'Argentinien');
INSERT INTO `phpwcms_country` VALUES (11, 'AM', 'Armenia', 'Armenien');
INSERT INTO `phpwcms_country` VALUES (12, 'AW', 'Aruba', 'Aruba');
INSERT INTO `phpwcms_country` VALUES (13, 'AU', 'Australia', 'Australien');
INSERT INTO `phpwcms_country` VALUES (14, 'AT', 'Austria', 'Österreich');
INSERT INTO `phpwcms_country` VALUES (15, 'AZ', 'Azerbaijan', 'Aserbaidschan');
INSERT INTO `phpwcms_country` VALUES (16, 'BS', 'Bahamas', 'Bahamas');
INSERT INTO `phpwcms_country` VALUES (17, 'BH', 'Bahrain', 'Bahrain');
INSERT INTO `phpwcms_country` VALUES (18, 'BD', 'Bangladesh', 'Bangladesch');
INSERT INTO `phpwcms_country` VALUES (19, 'BB', 'Barbados', 'Barbados');
INSERT INTO `phpwcms_country` VALUES (20, 'BY', 'Belarus', 'Belarus');
INSERT INTO `phpwcms_country` VALUES (21, 'BE', 'Belgium', 'Belgien');
INSERT INTO `phpwcms_country` VALUES (22, 'BZ', 'Belize', 'Belize');
INSERT INTO `phpwcms_country` VALUES (23, 'BJ', 'Benin', 'Benin');
INSERT INTO `phpwcms_country` VALUES (24, 'BM', 'Bermuda', 'Bermuda');
INSERT INTO `phpwcms_country` VALUES (25, 'BT', 'Bhutan', 'Bhutan');
INSERT INTO `phpwcms_country` VALUES (26, 'BO', 'Bolivia', 'Bolivien');
INSERT INTO `phpwcms_country` VALUES (27, 'BA', 'Bosnia and Herzegovina', 'Bosnien und Herzegowina');
INSERT INTO `phpwcms_country` VALUES (28, 'BW', 'Botswana', 'Botsuana');
INSERT INTO `phpwcms_country` VALUES (29, 'BV', 'Bouvet Island', 'Bouvet-Insel');
INSERT INTO `phpwcms_country` VALUES (30, 'BR', 'Brazil', 'Brasilien');
INSERT INTO `phpwcms_country` VALUES (31, 'IO', 'British Indian Ocean Territory', 'Britisches Territorium Im Indischen Ozean');
INSERT INTO `phpwcms_country` VALUES (32, 'BN', 'Brunei Darussalam', 'Brunei Darussalam');
INSERT INTO `phpwcms_country` VALUES (33, 'BG', 'Bulgaria', 'Bulgarien');
INSERT INTO `phpwcms_country` VALUES (34, 'BF', 'Burkina Faso', 'Burkina Faso');
INSERT INTO `phpwcms_country` VALUES (35, 'BI', 'Burundi', 'Burundi');
INSERT INTO `phpwcms_country` VALUES (36, 'KH', 'Cambodia', 'Kambodscha');
INSERT INTO `phpwcms_country` VALUES (37, 'CM', 'Cameroon', 'Kamerun');
INSERT INTO `phpwcms_country` VALUES (38, 'CA', 'Canada', 'Kanada');
INSERT INTO `phpwcms_country` VALUES (39, 'CV', 'Cape Verde', 'Kap Verde');
INSERT INTO `phpwcms_country` VALUES (40, 'KY', 'Cayman Islands', 'Kaimaninseln');
INSERT INTO `phpwcms_country` VALUES (41, 'CF', 'Central African Republic', 'Zentralafrikanische Republik');
INSERT INTO `phpwcms_country` VALUES (42, 'TD', 'Chad', 'Tschad');
INSERT INTO `phpwcms_country` VALUES (43, 'CL', 'Chile', 'Chile');
INSERT INTO `phpwcms_country` VALUES (44, 'CN', 'China', 'China');
INSERT INTO `phpwcms_country` VALUES (45, 'CX', 'Christmas Island', 'Weihnachtsinsel');
INSERT INTO `phpwcms_country` VALUES (46, 'CC', 'Cocos (Keeling) Islands', 'Kokosinseln (Keelingsinseln)');
INSERT INTO `phpwcms_country` VALUES (47, 'CO', 'Colombia', 'Kolumbien');
INSERT INTO `phpwcms_country` VALUES (48, 'KM', 'Comoros', 'Komoren');
INSERT INTO `phpwcms_country` VALUES (49, 'CG', 'Congo', 'Kongo');
INSERT INTO `phpwcms_country` VALUES (50, 'CD', 'Congo, The Democratic Republic Of The', 'Kongo, Demokratische Republik');
INSERT INTO `phpwcms_country` VALUES (51, 'CK', 'Cook Islands', 'Cook-Inseln');
INSERT INTO `phpwcms_country` VALUES (52, 'CR', 'Costa Rica', 'Costa Rica');
INSERT INTO `phpwcms_country` VALUES (53, 'CI', 'Côte D''Ivoire', 'Côte D''Ivoire');
INSERT INTO `phpwcms_country` VALUES (54, 'HR', 'Croatia', 'Kroatien');
INSERT INTO `phpwcms_country` VALUES (55, 'CU', 'Cuba', 'Kuba');
INSERT INTO `phpwcms_country` VALUES (56, 'CY', 'Cyprus', 'Zypern');
INSERT INTO `phpwcms_country` VALUES (57, 'CZ', 'Czech Republic', 'Tschechische Republik');
INSERT INTO `phpwcms_country` VALUES (58, 'DK', 'Denmark', 'Dänemark');
INSERT INTO `phpwcms_country` VALUES (59, 'DJ', 'Djibouti', 'Dschibuti');
INSERT INTO `phpwcms_country` VALUES (60, 'DM', 'Dominica', 'Dominica');
INSERT INTO `phpwcms_country` VALUES (61, 'DO', 'Dominican Republic', 'Dominikanische Republik');
INSERT INTO `phpwcms_country` VALUES (62, 'TP', 'East Timor', 'Ost-Timor');
INSERT INTO `phpwcms_country` VALUES (63, 'EC', 'Ecuador', 'Ecuador');
INSERT INTO `phpwcms_country` VALUES (64, 'EG', 'Egypt', 'Ägypten');
INSERT INTO `phpwcms_country` VALUES (65, 'SV', 'El Salvador', 'El Salvador');
INSERT INTO `phpwcms_country` VALUES (66, 'GQ', 'Equatorial Guinea', 'Äquatorialguinea');
INSERT INTO `phpwcms_country` VALUES (67, 'ER', 'Eritrea', 'Eritrea');
INSERT INTO `phpwcms_country` VALUES (68, 'EE', 'Estonia', 'Estland');
INSERT INTO `phpwcms_country` VALUES (69, 'ET', 'Ethiopia', '�thiopien');
INSERT INTO `phpwcms_country` VALUES (70, 'FK', 'Falkland Islands (Malvinas)', 'Falkland-Inseln (Malvinen)');
INSERT INTO `phpwcms_country` VALUES (71, 'FO', 'Faroe Islands', 'Färöer');
INSERT INTO `phpwcms_country` VALUES (72, 'FJ', 'Fiji', 'Fidschi');
INSERT INTO `phpwcms_country` VALUES (73, 'FI', 'Finland', 'Finnland');
INSERT INTO `phpwcms_country` VALUES (74, 'FR', 'France', 'Frankreich');
INSERT INTO `phpwcms_country` VALUES (75, 'GF', 'French Guiana', 'Französisch Guayana');
INSERT INTO `phpwcms_country` VALUES (76, 'PF', 'French Polynesia', 'Französisch Polynesien');
INSERT INTO `phpwcms_country` VALUES (77, 'TF', 'French Southern Territories', 'Französische Südgebiete');
INSERT INTO `phpwcms_country` VALUES (78, 'GA', 'Gabon', 'Gabun');
INSERT INTO `phpwcms_country` VALUES (79, 'GM', 'Gambia', 'Gambia');
INSERT INTO `phpwcms_country` VALUES (80, 'GE', 'Georgia', 'Georgien');
INSERT INTO `phpwcms_country` VALUES (81, 'DE', 'Germany', 'Deutschland');
INSERT INTO `phpwcms_country` VALUES (82, 'GH', 'Ghana', 'Ghana');
INSERT INTO `phpwcms_country` VALUES (83, 'GI', 'Gibraltar', 'Gibraltar');
INSERT INTO `phpwcms_country` VALUES (84, 'GR', 'Greece', 'Griechenland');
INSERT INTO `phpwcms_country` VALUES (85, 'GL', 'Greenland', 'Grönland');
INSERT INTO `phpwcms_country` VALUES (86, 'GD', 'Grenada', 'Grenada');
INSERT INTO `phpwcms_country` VALUES (87, 'GP', 'Guadeloupe', 'Guadeloupe');
INSERT INTO `phpwcms_country` VALUES (88, 'GU', 'Guam', 'Guam');
INSERT INTO `phpwcms_country` VALUES (89, 'GT', 'Guatemala', 'Guatemala');
INSERT INTO `phpwcms_country` VALUES (90, 'GN', 'Guinea', 'Guinea');
INSERT INTO `phpwcms_country` VALUES (91, 'GW', 'Guinea-Bissau', 'Guinea-Bissau');
INSERT INTO `phpwcms_country` VALUES (92, 'GY', 'Guyana', 'Guyana');
INSERT INTO `phpwcms_country` VALUES (93, 'HT', 'Haiti', 'Haiti');
INSERT INTO `phpwcms_country` VALUES (94, 'HM', 'Heard Island and McDonald Islands', 'Heard und McDonald');
INSERT INTO `phpwcms_country` VALUES (95, 'VA', 'Holy See (Vatican City State)', 'Vatikanstadt, Staat (Heiliger Stuhl)');
INSERT INTO `phpwcms_country` VALUES (96, 'HN', 'Honduras', 'Honduras');
INSERT INTO `phpwcms_country` VALUES (97, 'HK', 'Hong Kong', 'Hongkong');
INSERT INTO `phpwcms_country` VALUES (98, 'HU', 'Hungary', 'Ungarn');
INSERT INTO `phpwcms_country` VALUES (99, 'IS', 'Iceland', 'Island');
INSERT INTO `phpwcms_country` VALUES (100, 'IN', 'India', 'Indien');
INSERT INTO `phpwcms_country` VALUES (101, 'ID', 'Indonesia', 'Indonesien');
INSERT INTO `phpwcms_country` VALUES (102, 'IR', 'Iran, Islamic Republic Of', 'Iran (Islamische Republik)');
INSERT INTO `phpwcms_country` VALUES (103, 'IQ', 'Iraq', 'Irak');
INSERT INTO `phpwcms_country` VALUES (104, 'IE', 'Ireland', 'Irland');
INSERT INTO `phpwcms_country` VALUES (105, 'IL', 'Israel', 'Israel');
INSERT INTO `phpwcms_country` VALUES (106, 'IT', 'Italy', 'Italien');
INSERT INTO `phpwcms_country` VALUES (107, 'JM', 'Jamaica', 'Jamaika');
INSERT INTO `phpwcms_country` VALUES (108, 'JP', 'Japan', 'Japan');
INSERT INTO `phpwcms_country` VALUES (109, 'JO', 'Jordan', 'Jordanien');
INSERT INTO `phpwcms_country` VALUES (110, 'KZ', 'Kazakhstan', 'Kasachstan');
INSERT INTO `phpwcms_country` VALUES (111, 'KE', 'Kenya', 'Kenia');
INSERT INTO `phpwcms_country` VALUES (112, 'KI', 'Kiribati', 'Kiribati');
INSERT INTO `phpwcms_country` VALUES (113, 'KP', 'Korea, Democratic People''s Republic Of', 'Korea, Demokratische Volksrepublik');
INSERT INTO `phpwcms_country` VALUES (114, 'KR', 'Korea, Republic Of', 'Korea, Republik');
INSERT INTO `phpwcms_country` VALUES (115, 'KW', 'Kuwait', 'Kuwait');
INSERT INTO `phpwcms_country` VALUES (116, 'KG', 'Kyrgyzstan', 'Kirgisistan');
INSERT INTO `phpwcms_country` VALUES (117, 'LA', 'Lao People''s Democratic Republic', 'Laos, Demokratische Volksrepublik');
INSERT INTO `phpwcms_country` VALUES (118, 'LV', 'Latvia', 'Lettland');
INSERT INTO `phpwcms_country` VALUES (119, 'LB', 'Lebanon', 'Libanon');
INSERT INTO `phpwcms_country` VALUES (120, 'LS', 'Lesotho', 'Lesotho');
INSERT INTO `phpwcms_country` VALUES (121, 'LR', 'Liberia', 'Liberia');
INSERT INTO `phpwcms_country` VALUES (122, 'LY', 'Libyan Arab Jamahiriya', 'Libysch-Arabische Dschamahirija');
INSERT INTO `phpwcms_country` VALUES (123, 'LI', 'Liechtenstein', 'Liechtenstein');
INSERT INTO `phpwcms_country` VALUES (124, 'LT', 'Lithuania', 'Litauen');
INSERT INTO `phpwcms_country` VALUES (125, 'LU', 'Luxembourg', 'Luxembourg');
INSERT INTO `phpwcms_country` VALUES (126, 'MO', 'Macao', 'Macau');
INSERT INTO `phpwcms_country` VALUES (127, 'MK', 'Macedonia, The Former Yugoslav Republic Of', 'Mazedonien, Ehemalige Jugoslawische Republik');
INSERT INTO `phpwcms_country` VALUES (128, 'MG', 'Madagascar', 'Madagaskar');
INSERT INTO `phpwcms_country` VALUES (129, 'MW', 'Malawi', 'Malawi');
INSERT INTO `phpwcms_country` VALUES (130, 'MY', 'Malaysia', 'Malaysia');
INSERT INTO `phpwcms_country` VALUES (131, 'MV', 'Maldives', 'Malediven');
INSERT INTO `phpwcms_country` VALUES (132, 'ML', 'Mali', 'Mali');
INSERT INTO `phpwcms_country` VALUES (133, 'MT', 'Malta', 'Malta');
INSERT INTO `phpwcms_country` VALUES (134, 'MH', 'Marshall Islands', 'Marshallinseln');
INSERT INTO `phpwcms_country` VALUES (135, 'MQ', 'Martinique', 'Martinique');
INSERT INTO `phpwcms_country` VALUES (136, 'MR', 'Mauritania', 'Mauretanien');
INSERT INTO `phpwcms_country` VALUES (137, 'MU', 'Mauritius', 'Mauritius');
INSERT INTO `phpwcms_country` VALUES (138, 'YT', 'Mayotte', 'Mayotte');
INSERT INTO `phpwcms_country` VALUES (139, 'MX', 'Mexico', 'Mexiko');
INSERT INTO `phpwcms_country` VALUES (140, 'FM', 'Micronesia, Federated States Of', 'Mikronesien, Föderierte Staaten Von');
INSERT INTO `phpwcms_country` VALUES (141, 'MD', 'Moldova, Republic Of', 'Moldau, Republik');
INSERT INTO `phpwcms_country` VALUES (142, 'MC', 'Monaco', 'Monaco');
INSERT INTO `phpwcms_country` VALUES (143, 'MN', 'Mongolia', 'Mongolei');
INSERT INTO `phpwcms_country` VALUES (144, 'MS', 'Montserrat', 'Montserrat');
INSERT INTO `phpwcms_country` VALUES (145, 'MA', 'Morocco', 'Marokko');
INSERT INTO `phpwcms_country` VALUES (146, 'MZ', 'Mozambique', 'Mosambik');
INSERT INTO `phpwcms_country` VALUES (147, 'MM', 'Myanmar', 'Myanmar');
INSERT INTO `phpwcms_country` VALUES (148, 'NA', 'Namibia', 'Namibia');
INSERT INTO `phpwcms_country` VALUES (149, 'NR', 'Nauru', 'Nauru');
INSERT INTO `phpwcms_country` VALUES (150, 'NP', 'Nepal', 'Nepal');
INSERT INTO `phpwcms_country` VALUES (151, 'NL', 'Netherlands', 'Niederlande');
INSERT INTO `phpwcms_country` VALUES (152, 'AN', 'Netherlands Antilles', 'Niederländische Antillen');
INSERT INTO `phpwcms_country` VALUES (153, 'NC', 'New Caledonia', 'Neukaledonien');
INSERT INTO `phpwcms_country` VALUES (154, 'NZ', 'New Zealand', 'Neuseeland');
INSERT INTO `phpwcms_country` VALUES (155, 'NI', 'Nicaragua', 'Nicaragua');
INSERT INTO `phpwcms_country` VALUES (156, 'NE', 'Niger', 'Niger');
INSERT INTO `phpwcms_country` VALUES (157, 'NG', 'Nigeria', 'Nigeria');
INSERT INTO `phpwcms_country` VALUES (158, 'NU', 'Niue', 'Niue');
INSERT INTO `phpwcms_country` VALUES (159, 'NF', 'Norfolk Island', 'Norfolk-Insel');
INSERT INTO `phpwcms_country` VALUES (160, 'MP', 'Northern Mariana Islands', 'Nördliche Marianen');
INSERT INTO `phpwcms_country` VALUES (161, 'NO', 'Norway', 'Norwegen');
INSERT INTO `phpwcms_country` VALUES (162, 'OM', 'Oman', 'Oman');
INSERT INTO `phpwcms_country` VALUES (163, 'PK', 'Pakistan', 'Pakistan');
INSERT INTO `phpwcms_country` VALUES (164, 'PW', 'Palau', 'Palau');
INSERT INTO `phpwcms_country` VALUES (165, 'PS', 'Palestinian Territory, Occupied', 'Palästina');
INSERT INTO `phpwcms_country` VALUES (166, 'PA', 'Panama', 'Panama');
INSERT INTO `phpwcms_country` VALUES (167, 'PG', 'Papua New Guinea', 'Papua-Neuguinea');
INSERT INTO `phpwcms_country` VALUES (168, 'PY', 'Paraguay', 'Paraguay');
INSERT INTO `phpwcms_country` VALUES (169, 'PE', 'Peru', 'Peru');
INSERT INTO `phpwcms_country` VALUES (170, 'PH', 'Philippines', 'Philippinen');
INSERT INTO `phpwcms_country` VALUES (171, 'PN', 'Pitcairn', 'Pitcairn');
INSERT INTO `phpwcms_country` VALUES (172, 'PL', 'Poland', 'Polen');
INSERT INTO `phpwcms_country` VALUES (173, 'PT', 'Portugal', 'Portugal');
INSERT INTO `phpwcms_country` VALUES (174, 'PR', 'Puerto Rico', 'Puerto Rico');
INSERT INTO `phpwcms_country` VALUES (175, 'QA', 'Qatar', 'Katar');
INSERT INTO `phpwcms_country` VALUES (176, 'RE', 'Réunion', 'Réunion');
INSERT INTO `phpwcms_country` VALUES (177, 'RO', 'Romania', 'Rumänien');
INSERT INTO `phpwcms_country` VALUES (178, 'RU', 'Russian Federation', 'Russische Föderation');
INSERT INTO `phpwcms_country` VALUES (179, 'RW', 'Rwanda', 'Ruanda');
INSERT INTO `phpwcms_country` VALUES (180, 'SH', 'Saint Helena', 'St. Helena');
INSERT INTO `phpwcms_country` VALUES (181, 'KN', 'Saint Kitts and Nevis', 'Saint Kitts und Nevis');
INSERT INTO `phpwcms_country` VALUES (182, 'LC', 'Saint Lucia', 'Santa Lucia');
INSERT INTO `phpwcms_country` VALUES (183, 'PM', 'Saint Pierre and Miquelon', 'Saint-Pierre und Miquelon');
INSERT INTO `phpwcms_country` VALUES (184, 'VC', 'Saint Vincent and The Grenadines', 'Saint Vincent und die Grenadinen');
INSERT INTO `phpwcms_country` VALUES (185, 'WS', 'Samoa', 'Samoa');
INSERT INTO `phpwcms_country` VALUES (186, 'SM', 'San Marino', 'San Marino');
INSERT INTO `phpwcms_country` VALUES (187, 'ST', 'Sao Tome and Principe', 'São Tomé und Príncipe');
INSERT INTO `phpwcms_country` VALUES (188, 'SA', 'Saudi Arabia', 'Saudi-Arabien');
INSERT INTO `phpwcms_country` VALUES (189, 'SN', 'Senegal', 'Senegal');
INSERT INTO `phpwcms_country` VALUES (190, 'SC', 'Seychelles', 'Seychellen');
INSERT INTO `phpwcms_country` VALUES (191, 'SL', 'Sierra Leone', 'Sierra Leone');
INSERT INTO `phpwcms_country` VALUES (192, 'SG', 'Singapore', 'Singapur');
INSERT INTO `phpwcms_country` VALUES (193, 'SK', 'Slovakia', 'Slowakei (Slowakische Republik)');
INSERT INTO `phpwcms_country` VALUES (194, 'SI', 'Slovenia', 'Slowenien');
INSERT INTO `phpwcms_country` VALUES (195, 'SB', 'Solomon Islands', 'Salomonen');
INSERT INTO `phpwcms_country` VALUES (196, 'SO', 'Somalia', 'Somalia');
INSERT INTO `phpwcms_country` VALUES (197, 'ZA', 'South Africa', 'S�dafrika');
INSERT INTO `phpwcms_country` VALUES (198, 'GS', 'South Georgia and The South Sandwich Islands', 'Südgeorgien und Südliche Sandwichinseln');
INSERT INTO `phpwcms_country` VALUES (199, 'ES', 'Spain', 'Spanien');
INSERT INTO `phpwcms_country` VALUES (200, 'LK', 'Sri Lanka', 'Sri Lanka');
INSERT INTO `phpwcms_country` VALUES (201, 'SD', 'Sudan', 'Sudan');
INSERT INTO `phpwcms_country` VALUES (202, 'SR', 'Suriname', 'Suriname');
INSERT INTO `phpwcms_country` VALUES (203, 'SJ', 'Svalbard and Jan Mayen', 'Svalbard und Jan Mayen');
INSERT INTO `phpwcms_country` VALUES (204, 'SZ', 'Swaziland', 'Swasiland');
INSERT INTO `phpwcms_country` VALUES (205, 'SE', 'Sweden', 'Schweden');
INSERT INTO `phpwcms_country` VALUES (206, 'CH', 'Switzerland', 'Schweiz');
INSERT INTO `phpwcms_country` VALUES (207, 'SY', 'Syrian Arab Republic', 'Syrien, Arabische Republik');
INSERT INTO `phpwcms_country` VALUES (208, 'TW', 'Taiwan, Province Of China', 'Taiwan (China)');
INSERT INTO `phpwcms_country` VALUES (209, 'TJ', 'Tajikistan', 'Tadschikistan');
INSERT INTO `phpwcms_country` VALUES (210, 'TZ', 'Tanzania, United Republic Of', 'Tansania, Vereinigte Republik');
INSERT INTO `phpwcms_country` VALUES (211, 'TH', 'Thailand', 'Thailand');
INSERT INTO `phpwcms_country` VALUES (212, 'TG', 'Togo', 'Togo');
INSERT INTO `phpwcms_country` VALUES (213, 'TK', 'Tokelau', 'Tokelau');
INSERT INTO `phpwcms_country` VALUES (214, 'TO', 'Tonga', 'Tonga');
INSERT INTO `phpwcms_country` VALUES (215, 'TT', 'Trinidad and Tobago', 'Trinidad und Tobago');
INSERT INTO `phpwcms_country` VALUES (216, 'TN', 'Tunisia', 'Tunesien');
INSERT INTO `phpwcms_country` VALUES (217, 'TR', 'Turkey', 'Türkei');
INSERT INTO `phpwcms_country` VALUES (218, 'TM', 'Turkmenistan', 'Turkmenistan');
INSERT INTO `phpwcms_country` VALUES (219, 'TC', 'Turks Aand Caicos Islands', 'Turks- und Caicosinseln');
INSERT INTO `phpwcms_country` VALUES (220, 'TV', 'Tuvalu', 'Tuvalu');
INSERT INTO `phpwcms_country` VALUES (221, 'UG', 'Uganda', 'Uganda');
INSERT INTO `phpwcms_country` VALUES (222, 'UA', 'Ukraine', 'Ukraine');
INSERT INTO `phpwcms_country` VALUES (223, 'AE', 'United Arab Emirates', 'Vereinigte Arabische Emirate');
INSERT INTO `phpwcms_country` VALUES (224, 'GB', 'United Kingdom', 'United Kingdom');
INSERT INTO `phpwcms_country` VALUES (225, 'US', 'United States', 'Vereinigte Staaten');
INSERT INTO `phpwcms_country` VALUES (226, 'UM', 'United States Minor Outlying Islands', 'Kleinere entlegene Inseln der Vereinigten Staaten');
INSERT INTO `phpwcms_country` VALUES (227, 'UY', 'Uruguay', 'Uruguay');
INSERT INTO `phpwcms_country` VALUES (228, 'UZ', 'Uzbekistan', 'Usbekistan');
INSERT INTO `phpwcms_country` VALUES (229, 'VU', 'Vanuatu', 'Vanuatu');
INSERT INTO `phpwcms_country` VALUES (230, 'VE', 'Venezuela', 'Venezuela');
INSERT INTO `phpwcms_country` VALUES (231, 'VN', 'Viet Nam', 'Vietnam');
INSERT INTO `phpwcms_country` VALUES (232, 'VG', 'Virgin Islands, British', 'Jungferninseln (Britische)');
INSERT INTO `phpwcms_country` VALUES (233, 'VI', 'Virgin Islands, U.S.', 'Jungferninseln (Amerikanische)');
INSERT INTO `phpwcms_country` VALUES (234, 'WF', 'Wallis and Futuna', 'Wallis und Futuna');
INSERT INTO `phpwcms_country` VALUES (235, 'EH', 'Western Sahara', 'Westsahara');
INSERT INTO `phpwcms_country` VALUES (236, 'YE', 'Yemen', 'Jemen');
INSERT INTO `phpwcms_country` VALUES (237, 'YU', 'Yugoslavia', 'Jugoslawien');
INSERT INTO `phpwcms_country` VALUES (238, 'ZM', 'Zambia', 'Sambia');
INSERT INTO `phpwcms_country` VALUES (239, 'ZW', 'Zimbabwe', 'Simbabwe');
INSERT INTO `phpwcms_country` VALUES (240, 'AX', 'Åland Islands', 'Åland Inseln');

#####################################################

CREATE TABLE `phpwcms_keyword` (
  `keyword_id` int(11) NOT NULL auto_increment,
  `keyword_name` varchar(255) NOT NULL default '',
  `keyword_trash` int(1) NOT NULL default '0',
  PRIMARY KEY  (`keyword_id`)
);


CREATE TABLE `phpwcms_formtracking` (
  `formtracking_id` INT NOT NULL AUTO_INCREMENT,
  `formtracking_hash` VARCHAR( 50 ) NOT NULL default '',
  `formtracking_ip` VARCHAR( 20 ) NOT NULL default '',
  `formtracking_created` TIMESTAMP NOT NULL,
  `formtracking_sentdate` VARCHAR( 20 ) NOT NULL default '',
  `formtracking_sent` INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`formtracking_id`)
);


#####################################################