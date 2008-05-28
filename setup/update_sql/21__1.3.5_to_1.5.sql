#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.5 to 1.5
#
#####################################################


# 2008-02-24
ALTER TABLE `phpwcms_articlecat` ADD `acat_archive` INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecat` ADD INDEX ( `acat_archive` ) ;
ALTER TABLE `phpwcms_article` ADD `article_archive_status` INT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `phpwcms_article` ADD INDEX ( `article_archive_status` ) ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_category` VARCHAR( 255 ) NOT NULL;


# 2008-02-25
ALTER TABLE `phpwcms_file` CHANGE `f_cat` `f_cat` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `phpwcms_file` CHANGE `f_changed` `f_changed` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` CHANGE `f_created` `f_created` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` DROP `f_log`;
ALTER TABLE `phpwcms_file` CHANGE `f_longinfo` `f_longinfo` TEXT NOT NULL;
ALTER TABLE `phpwcms_file` ADD `f_copyright` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `phpwcms_file` ADD `f_tags` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `phpwcms_file` CHANGE `f_size` `f_size` INT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

# 2008-03-06
ALTER TABLE `phpwcms_file` ADD `f_granted` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` ADD INDEX ( `f_granted` ) ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_granted` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecontent` ADD INDEX ( `acontent_granted` ) ;

# 2008-03-09
DROP TABLE IF EXISTS `phpwcms_calendar` ;
CREATE TABLE `phpwcms_calendar` (
  `calendar_id` int(11) NOT NULL auto_increment,
  `calendar_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_status` int(1) NOT NULL default '0',
  `calendar_start` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_end` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_allday` int(1) NOT NULL default '0',
  `calendar_range` int(1) NOT NULL default '0',
  `calendar_range_start` date NOT NULL default '0000-00-00',
  `calendar_range_end` date NOT NULL default '0000-00-00',
  `calendar_title` varchar(255) NOT NULL default '',
  `calendar_where` varchar(255) NOT NULL default '',
  `calendar_teaser` text NOT NULL,
  `calendar_text` mediumtext NOT NULL,
  `calendar_tag` varchar(255) NOT NULL default '',
  `calendar_object` longtext NOT NULL,
  `calendar_refid` int(11) NOT NULL default '0',
  `calendar_lang` varchar(255) NOT NULL,
  PRIMARY KEY  (`calendar_id`),
  KEY `calendar_status` (`calendar_status`),
  KEY `calendar_start` (`calendar_start`),
  KEY `calendar_end` (`calendar_end`),
  KEY `calendar_tag` (`calendar_tag`),
  KEY `calendar_refid` (`calendar_refid`),
  KEY `calendar_range` (`calendar_range`),
  KEY `calendar_lang` (`calendar_lang`)
) TYPE=MyISAM ;

# 2008-05-09
DROP TABLE IF EXISTS `phpwcms_content` ;
CREATE TABLE IF NOT EXISTS `phpwcms_content` (
  `cnt_id` int(11) NOT NULL auto_increment,
  `cnt_pid` int(11) NOT NULL default '0',
  `cnt_created` int(11) NOT NULL default '0',
  `cnt_changed` int(11) NOT NULL default '0',
  `cnt_status` int(1) NOT NULL default '0',
  `cnt_type` varchar(255) NOT NULL,
  `cnt_module` varchar(255) NOT NULL,
  `cnt_group` int(11) NOT NULL default '0',
  `cnt_owner` int(11) NOT NULL default '0',
  `cnt_livedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cnt_killdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cnt_archive_status` int(11) NOT NULL default '0',
  `cnt_alias` varchar(255) NOT NULL,
  `cnt_name` varchar(255) NOT NULL default '',
  `cnt_title` varchar(255) NOT NULL default '',
  `cnt_subtitle` varchar(255) NOT NULL default '',
  `cnt_editor` varchar(255) NOT NULL,
  `cnt_place` varchar(255) NOT NULL,
  `cnt_teasertext` text NOT NULL,
  `cnt_text` text NOT NULL,
  `cnt_lang` varchar(10) NOT NULL default '',
  `cnt_object` text NOT NULL,
  PRIMARY KEY  (`cnt_id`),
  KEY `cnt_livedate` (`cnt_livedate`),
  KEY `cnt_killdate` (`cnt_killdate`),
  KEY `cnt_module` (`cnt_module`),
  KEY `cnt_type` (`cnt_type`),
  KEY `cnt_group` (`cnt_group`),
  KEY `cnt_owner` (`cnt_owner`),
  KEY `cnt_alias` (`cnt_alias`),
  KEY `cnt_pid` (`cnt_pid`)
) TYPE=MyISAM ;

ALTER TABLE `phpwcms_content` ADD `cnt_sort` INT NOT NULL DEFAULT '0' AFTER `cnt_archive_status`;
ALTER TABLE `phpwcms_content` ADD `cnt_prio` INT NOT NULL DEFAULT '0' AFTER `cnt_sort`;
ALTER TABLE `phpwcms_content` ADD INDEX ( `cnt_sort` );
ALTER TABLE `phpwcms_content` ADD INDEX ( `cnt_prio` );

# Add shop tables by default
CREATE TABLE IF NOT EXISTS `phpwcms_categories` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_type` varchar(255) NOT NULL default '',
  `cat_pid` int(11) NOT NULL default '0',
  `cat_status` int(1) NOT NULL default '0',
  `cat_createdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_changedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_name` varchar(255) NOT NULL default '',
  `cat_info` text NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `cat_type` (`cat_type`,`cat_status`),
  KEY `cat_pid` (`cat_pid`)
) TYPE=MyISAM ;

CREATE TABLE IF NOT EXISTS `phpwcms_shop_orders` (
  `order_id` int(10) unsigned NOT NULL auto_increment,
  `order_number` varchar(20) NOT NULL default '',
  `order_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `order_name` varchar(255) NOT NULL default '',
  `order_firstname` varchar(255) NOT NULL default '',
  `order_email` varchar(255) NOT NULL default '',
  `order_net` float NOT NULL default '0',
  `order_gross` float NOT NULL default '0',
  `order_payment` varchar(255) NOT NULL default '',
  `order_data` mediumtext NOT NULL,
  `order_status` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`order_id`),
  KEY `order_number` (`order_number`,`order_status`)
) TYPE=MyISAM ;

CREATE TABLE IF NOT EXISTS `phpwcms_shop_products` (
  `shopprod_id` int(10) unsigned NOT NULL auto_increment,
  `shopprod_createdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `shopprod_changedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `shopprod_status` int(1) unsigned NOT NULL default '0',
  `shopprod_uid` int(10) unsigned NOT NULL default '0',
  `shopprod_ordernumber` varchar(255) NOT NULL default '',
  `shopprod_model` varchar(255) NOT NULL default '',
  `shopprod_name1` varchar(255) NOT NULL default '',
  `shopprod_name2` varchar(255) NOT NULL default '',
  `shopprod_tag` varchar(255) NOT NULL default '',
  `shopprod_vat` float unsigned NOT NULL default '0',
  `shopprod_netgross` int(1) unsigned NOT NULL default '0',
  `shopprod_price` float NOT NULL default '0',
  `shopprod_maxrebate` float NOT NULL default '0',
  `shopprod_description0` text NOT NULL,
  `shopprod_description1` text NOT NULL,
  `shopprod_description2` text NOT NULL,
  `shopprod_description3` text NOT NULL,
  `shopprod_var` mediumtext NOT NULL,
  `shopprod_category` varchar(255) NOT NULL default '',
  `shopprod_weight` float NOT NULL default '0',
  `shopprod_color` varchar(255) NOT NULL default '',
  `shopprod_size` varchar(255) NOT NULL default '',
  `shopprod_listall` int(1) unsigned default '0',
  PRIMARY KEY  (`shopprod_id`),
  KEY `shopprod_status` (`shopprod_status`),
  KEY `category` (`shopprod_category`),
  KEY `tag` (`shopprod_tag`),
  KEY `all` (`shopprod_listall`)
) TYPE=MyISAM ;

CREATE TABLE IF NOT EXISTS `phpwcms_sysvalue` (
  `sysvalue_key` varchar(255) NOT NULL default '',
  `sysvalue_group` varchar(255) NOT NULL default '',
  `sysvalue_lastchange` int(11) NOT NULL default '0',
  `sysvalue_status` int(1) NOT NULL default '0',
  `sysvalue_vartype` varchar(100) NOT NULL default '',
  `sysvalue_value` text NOT NULL,
  PRIMARY KEY  (`sysvalue_key`),
  KEY `sysvalue_group` (`sysvalue_group`),
  KEY `sysvalue_status` (`sysvalue_status`)
) TYPE=MyISAM ;


# Add country continents and regions
ALTER TABLE `phpwcms_country` ADD `country_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP AFTER `country_id` ;
ALTER TABLE `phpwcms_country` ADD `country_iso3` CHAR( 3 ) NOT NULL default '' AFTER `country_iso`;
ALTER TABLE `phpwcms_country` ADD `country_isonum` INT NOT NULL default '0' AFTER `country_iso3`;
ALTER TABLE `phpwcms_country` ADD `country_continent_code` CHAR( 2 ) NOT NULL default '' AFTER `country_isonum`;
ALTER TABLE `phpwcms_country` ADD `country_continent` VARCHAR( 255 ) NOT NULL default '';
ALTER TABLE `phpwcms_country` ADD `country_continent_de` VARCHAR( 255 ) NOT NULL default '';
ALTER TABLE `phpwcms_country` ADD `country_region` VARCHAR( 255 ) NOT NULL default '';
ALTER TABLE `phpwcms_country` ADD `country_region_de` VARCHAR( 255 ) NOT NULL default '';

ALTER TABLE `phpwcms_userdetail` DROP INDEX `detail_login`;

# 2008-05-28
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_float1` `detail_float1` DOUBLE NOT NULL DEFAULT '0' ;
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_float2` `detail_float2` DOUBLE NOT NULL DEFAULT '0' ;
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_float3` `detail_float3` DOUBLE NOT NULL DEFAULT '0' ;
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_float4` `detail_float4` DOUBLE NOT NULL DEFAULT '0' ;
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_float5` `detail_float5` DOUBLE NOT NULL DEFAULT '0' ;

TRUNCATE TABLE `phpwcms_country`;

INSERT INTO `phpwcms_country` VALUES(1, '0000-00-00 00:00:00', 'AF', 'AFG', 4, 'AS', 'Afghanistan, Islamic Republic of\r', 'Afghanistan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(2, '0000-00-00 00:00:00', 'AL', 'ALB', 8, 'EU', 'Albania, Republic of\r', 'Albanien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(3, '0000-00-00 00:00:00', 'DZ', 'DZA', 12, 'AF', 'Algeria, People''s Democratic Republic of\r', 'Algerien', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(4, '0000-00-00 00:00:00', 'AS', 'ASM', 16, 'OC', 'American Samoa\r', 'Amerikanisch Samoa', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(5, '0000-00-00 00:00:00', 'AD', 'AND', 20, 'EU', 'Andorra, Principality of\r', 'Andorra', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(6, '0000-00-00 00:00:00', 'AO', 'AGO', 24, 'AF', 'Angola, Republic of\r', 'Angola', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(7, '0000-00-00 00:00:00', 'AI', 'AIA', 660, 'NA', 'Anguilla\r', 'Anguilla', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(8, '0000-00-00 00:00:00', 'AQ', 'ATA', 10, 'AN', 'Antarctica (the territory South of 60 deg S)\r', 'Antarktis', 'Antarctica', 'Antarktis', '', '');
INSERT INTO `phpwcms_country` VALUES(9, '0000-00-00 00:00:00', 'AG', 'ATG', 28, 'NA', 'Antigua and Barbuda\r', 'Antigua und Barbuda', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(10, '0000-00-00 00:00:00', 'AR', 'ARG', 32, 'SA', 'Argentina, Argentine Republic\r', 'Argentinien', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(11, '0000-00-00 00:00:00', 'AM', 'ARM', 51, 'AS', 'Armenia, Republic of\r', 'Armenien', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(12, '0000-00-00 00:00:00', 'AW', 'ABW', 533, 'NA', 'Aruba\r', 'Aruba', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(13, '0000-00-00 00:00:00', 'AU', 'AUS', 36, 'OC', 'Australia, Commonwealth of\r', 'Australien', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(14, '0000-00-00 00:00:00', 'AT', 'AUT', 40, 'EU', 'Austria, Republic of\r', 'Österreich', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(15, '0000-00-00 00:00:00', 'AZ', 'AZE', 31, 'AS', 'Azerbaijan, Republic of\r', 'Aserbaidschan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(16, '0000-00-00 00:00:00', 'BS', 'BHS', 44, 'NA', 'Bahamas, Commonwealth of the\r', 'Bahamas', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(17, '0000-00-00 00:00:00', 'BH', 'BHR', 48, 'AS', 'Bahrain, Kingdom of\r', 'Bahrain', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(18, '0000-00-00 00:00:00', 'BD', 'BGD', 50, 'AS', 'Bangladesh, People''s Republic of\r', 'Bangladesch', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(19, '0000-00-00 00:00:00', 'BB', 'BRB', 52, 'NA', 'Barbados\r', 'Barbados', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(20, '0000-00-00 00:00:00', 'BY', 'BLR', 112, 'EU', 'Belarus, Republic of\r', 'Belarus', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(21, '0000-00-00 00:00:00', 'BE', 'BEL', 56, 'EU', 'Belgium, Kingdom of\r', 'Belgien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(22, '0000-00-00 00:00:00', 'BZ', 'BLZ', 84, 'NA', 'Belize\r', 'Belize', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(23, '0000-00-00 00:00:00', 'BJ', 'BEN', 204, 'AF', 'Benin, Republic of\r', 'Benin', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(24, '0000-00-00 00:00:00', 'BM', 'BMU', 60, 'NA', 'Bermuda\r', 'Bermuda', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(25, '0000-00-00 00:00:00', 'BT', 'BTN', 64, 'AS', 'Bhutan, Kingdom of\r', 'Bhutan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(26, '0000-00-00 00:00:00', 'BO', 'BOL', 68, 'SA', 'Bolivia, Republic of\r', 'Bolivien', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(27, '0000-00-00 00:00:00', 'BA', 'BIH', 70, 'EU', 'Bosnia and Herzegovina\r', 'Bosnien und Herzegowina', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(28, '0000-00-00 00:00:00', 'BW', 'BWA', 72, 'AF', 'Botswana, Republic of\r', 'Botsuana', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(29, '0000-00-00 00:00:00', 'BV', 'BVT', 74, 'AN', 'Bouvet Island (Bouvetoya)\r', 'Bouvet-Insel', 'Antarctica', 'Antarktis', '', '');
INSERT INTO `phpwcms_country` VALUES(30, '0000-00-00 00:00:00', 'BR', 'BRA', 76, 'SA', 'Brazil, Federative Republic of\r', 'Brasilien', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(31, '0000-00-00 00:00:00', 'IO', 'IOT', 86, 'AS', 'British Indian Ocean Territory (Chagos Archipelago)\r', 'Britisches Territorium Im Indischen Ozean', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(32, '0000-00-00 00:00:00', 'BN', 'BRN', 96, 'AS', 'Brunei Darussalam\r', 'Brunei Darussalam', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(33, '0000-00-00 00:00:00', 'BG', 'BGR', 100, 'EU', 'Bulgaria, Republic of\r', 'Bulgarien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(34, '0000-00-00 00:00:00', 'BF', 'BFA', 854, 'AF', 'Burkina Faso\r', 'Burkina Faso', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(35, '0000-00-00 00:00:00', 'BI', 'BDI', 108, 'AF', 'Burundi, Republic of\r', 'Burundi', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(36, '0000-00-00 00:00:00', 'KH', 'KHM', 116, 'AS', 'Cambodia, Kingdom of\r', 'Kambodscha', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(37, '0000-00-00 00:00:00', 'CM', 'CMR', 120, 'AF', 'Cameroon, Republic of\r', 'Kamerun', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(38, '0000-00-00 00:00:00', 'CA', 'CAN', 124, 'NA', 'Canada\r', 'Kanada', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(39, '0000-00-00 00:00:00', 'CV', 'CPV', 132, 'AF', 'Cape Verde, Republic of\r', 'Kap Verde', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(40, '0000-00-00 00:00:00', 'KY', 'CYM', 136, 'NA', 'Cayman Islands\r', 'Kaimaninseln', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(41, '0000-00-00 00:00:00', 'CF', 'CAF', 140, 'AF', 'Central African Republic\r', 'Zentralafrikanische Republik', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(42, '0000-00-00 00:00:00', 'TD', 'TCD', 148, 'AF', 'Chad, Republic of\r', 'Tschad', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(43, '0000-00-00 00:00:00', 'CL', 'CHL', 152, 'SA', 'Chile, Republic of\r', 'Chile', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(44, '0000-00-00 00:00:00', 'CN', 'CHN', 156, 'AS', 'China, People''s Republic of\r', 'China', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(45, '0000-00-00 00:00:00', 'CX', 'CXR', 162, 'AS', 'Christmas Island\r', 'Weihnachtsinsel', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(46, '0000-00-00 00:00:00', 'CC', 'CCK', 166, 'AS', 'Cocos (Keeling) Islands\r', 'Kokosinseln (Keelingsinseln)', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(47, '0000-00-00 00:00:00', 'CO', 'COL', 170, 'SA', 'Colombia, Republic of\r', 'Kolumbien', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(48, '0000-00-00 00:00:00', 'KM', 'COM', 174, 'AF', 'Comoros, Union of the\r', 'Komoren', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(49, '0000-00-00 00:00:00', 'CG', 'COG', 178, 'AF', 'Congo, Republic of the\r', 'Kongo', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(50, '0000-00-00 00:00:00', 'CD', 'COD', 180, 'AF', 'Congo, Democratic Republic of the\r', 'Kongo, Demokratische Republik', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(51, '0000-00-00 00:00:00', 'CK', 'COK', 184, 'OC', 'Cook Islands\r', 'Cook-Inseln', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(52, '0000-00-00 00:00:00', 'CR', 'CRI', 188, 'NA', 'Costa Rica, Republic of\r', 'Costa Rica', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(53, '0000-00-00 00:00:00', 'CI', 'CIV', 384, 'AF', 'Cote d''Ivoire, Republic of\r', 'Côte D''Ivoire', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(54, '0000-00-00 00:00:00', 'HR', 'HRV', 191, 'EU', 'Croatia, Republic of\r', 'Kroatien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(55, '0000-00-00 00:00:00', 'CU', 'CUB', 192, 'NA', 'Cuba, Republic of\r', 'Kuba', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(56, '0000-00-00 00:00:00', 'CY', 'CYP', 196, 'AS', 'Cyprus, Republic of\r', 'Zypern', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(57, '0000-00-00 00:00:00', 'CZ', 'CZE', 203, 'EU', 'Czech Republic\r', 'Tschechische Republik', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(58, '0000-00-00 00:00:00', 'DK', 'DNK', 208, 'EU', 'Denmark, Kingdom of\r', 'Dänemark', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(59, '0000-00-00 00:00:00', 'DJ', 'DJI', 262, 'AF', 'Djibouti, Republic of\r', 'Dschibuti', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(60, '0000-00-00 00:00:00', 'DM', 'DMA', 212, 'NA', 'Dominica, Commonwealth of\r', 'Dominica', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(61, '0000-00-00 00:00:00', 'DO', 'DOM', 214, 'NA', 'Dominican Republic\r', 'Dominikanische Republik', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(63, '0000-00-00 00:00:00', 'EC', 'ECU', 218, 'SA', 'Ecuador, Republic of\r', 'Ecuador', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(64, '0000-00-00 00:00:00', 'EG', 'EGY', 818, 'AF', 'Egypt, Arab Republic of\r', 'Ägypten', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(65, '0000-00-00 00:00:00', 'SV', 'SLV', 222, 'NA', 'El Salvador, Republic of\r', 'El Salvador', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(66, '0000-00-00 00:00:00', 'GQ', 'GNQ', 226, 'AF', 'Equatorial Guinea, Republic of\r', 'Äquatorialguinea', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(67, '0000-00-00 00:00:00', 'ER', 'ERI', 232, 'AF', 'Eritrea, State of\r', 'Eritrea', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(68, '0000-00-00 00:00:00', 'EE', 'EST', 233, 'EU', 'Estonia, Republic of\r', 'Estland', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(69, '0000-00-00 00:00:00', 'ET', 'ETH', 231, 'AF', 'Ethiopia, Federal Democratic Republic of\r', 'Äthiopien', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(70, '0000-00-00 00:00:00', 'FK', 'FLK', 238, 'SA', 'Falkland Islands (Malvinas)\r', 'Falkland-Inseln (Malvinen)', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(71, '0000-00-00 00:00:00', 'FO', 'FRO', 234, 'EU', 'Faroe Islands\r', 'Färöer', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(72, '0000-00-00 00:00:00', 'FJ', 'FJI', 242, 'OC', 'Fiji, Republic of the Fiji Islands\r', 'Fidschi', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(73, '0000-00-00 00:00:00', 'FI', 'FIN', 246, 'EU', 'Finland, Republic of\r', 'Finnland', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(74, '0000-00-00 00:00:00', 'FR', 'FRA', 250, 'EU', 'France, French Republic\r', 'Frankreich', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(75, '0000-00-00 00:00:00', 'GF', 'GUF', 254, 'SA', 'French Guiana\r', 'Französisch Guayana', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(76, '0000-00-00 00:00:00', 'PF', 'PYF', 258, 'OC', 'French Polynesia\r', 'Französisch Polynesien', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(77, '0000-00-00 00:00:00', 'TF', 'ATF', 260, 'AN', 'French Southern Territories\r', 'Französische Südgebiete', 'Antarctica', 'Antarktis', '', '');
INSERT INTO `phpwcms_country` VALUES(78, '0000-00-00 00:00:00', 'GA', 'GAB', 266, 'AF', 'Gabon, Gabonese Republic\r', 'Gabun', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(79, '0000-00-00 00:00:00', 'GM', 'GMB', 270, 'AF', 'Gambia, Republic of the\r', 'Gambia', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(80, '0000-00-00 00:00:00', 'GE', 'GEO', 268, 'AS', 'Georgia\r', 'Georgien', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(81, '0000-00-00 00:00:00', 'DE', 'DEU', 276, 'EU', 'Germany, Federal Republic of\r', 'Deutschland', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(82, '0000-00-00 00:00:00', 'GH', 'GHA', 288, 'AF', 'Ghana, Republic of\r', 'Ghana', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(83, '0000-00-00 00:00:00', 'GI', 'GIB', 292, 'EU', 'Gibraltar\r', 'Gibraltar', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(84, '0000-00-00 00:00:00', 'GR', 'GRC', 300, 'EU', 'Greece, Hellenic Republic\r', 'Griechenland', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(85, '0000-00-00 00:00:00', 'GL', 'GRL', 304, 'NA', 'Greenland\r', 'Grönland', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(86, '0000-00-00 00:00:00', 'GD', 'GRD', 308, 'NA', 'Grenada\r', 'Grenada', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(87, '0000-00-00 00:00:00', 'GP', 'GLP', 312, 'NA', 'Guadeloupe\r', 'Guadeloupe', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(88, '0000-00-00 00:00:00', 'GU', 'GUM', 316, 'OC', 'Guam\r', 'Guam', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(89, '0000-00-00 00:00:00', 'GT', 'GTM', 320, 'NA', 'Guatemala, Republic of\r', 'Guatemala', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(90, '0000-00-00 00:00:00', 'GN', 'GIN', 324, 'AF', 'Guinea, Republic of\r', 'Guinea', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(91, '0000-00-00 00:00:00', 'GW', 'GNB', 624, 'AF', 'Guinea-Bissau, Republic of\r', 'Guinea-Bissau', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(92, '0000-00-00 00:00:00', 'GY', 'GUY', 328, 'SA', 'Guyana, Co-operative Republic of\r', 'Guyana', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(93, '0000-00-00 00:00:00', 'HT', 'HTI', 332, 'NA', 'Haiti, Republic of\r', 'Haiti', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(94, '0000-00-00 00:00:00', 'HM', 'HMD', 334, 'AN', 'Heard Island and McDonald Islands\r', 'Heard und McDonald', 'Antarctica', 'Antarktis', '', '');
INSERT INTO `phpwcms_country` VALUES(95, '0000-00-00 00:00:00', 'VA', 'VAT', 336, 'EU', 'Holy See (Vatican City State)\r', 'Vatikanstadt, Staat (Heiliger Stuhl)', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(96, '0000-00-00 00:00:00', 'HN', 'HND', 340, 'NA', 'Honduras, Republic of\r', 'Honduras', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(97, '0000-00-00 00:00:00', 'HK', 'HKG', 344, 'AS', 'Hong Kong, Special Administrative Region of China\r', 'Hongkong', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(98, '0000-00-00 00:00:00', 'HU', 'HUN', 348, 'EU', 'Hungary, Republic of\r', 'Ungarn', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(99, '0000-00-00 00:00:00', 'IS', 'ISL', 352, 'EU', 'Iceland, Republic of\r', 'Island', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(100, '0000-00-00 00:00:00', 'IN', 'IND', 356, 'AS', 'India, Republic of\r', 'Indien', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(101, '0000-00-00 00:00:00', 'ID', 'IDN', 360, 'AS', 'Indonesia, Republic of\r', 'Indonesien', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(102, '0000-00-00 00:00:00', 'IR', 'IRN', 364, 'AS', 'Iran, Islamic Republic of\r', 'Iran (Islamische Republik)', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(103, '0000-00-00 00:00:00', 'IQ', 'IRQ', 368, 'AS', 'Iraq, Republic of\r', 'Irak', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(104, '0000-00-00 00:00:00', 'IE', 'IRL', 372, 'EU', 'Ireland\r', 'Irland', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(105, '0000-00-00 00:00:00', 'IL', 'ISR', 376, 'AS', 'Israel, State of\r', 'Israel', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(106, '0000-00-00 00:00:00', 'IT', 'ITA', 380, 'EU', 'Italy, Italian Republic\r', 'Italien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(107, '0000-00-00 00:00:00', 'JM', 'JAM', 388, 'NA', 'Jamaica\r', 'Jamaika', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(108, '0000-00-00 00:00:00', 'JP', 'JPN', 392, 'AS', 'Japan\r', 'Japan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(109, '0000-00-00 00:00:00', 'JO', 'JOR', 400, 'AS', 'Jordan, Hashemite Kingdom of\r', 'Jordanien', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(110, '0000-00-00 00:00:00', 'KZ', 'KAZ', 398, 'AS', 'Kazakhstan, Republic of\r', 'Kasachstan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(111, '0000-00-00 00:00:00', 'KE', 'KEN', 404, 'AF', 'Kenya, Republic of\r', 'Kenia', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(112, '0000-00-00 00:00:00', 'KI', 'KIR', 296, 'OC', 'Kiribati, Republic of\r', 'Kiribati', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(113, '0000-00-00 00:00:00', 'KP', 'PRK', 408, 'AS', 'Korea, Democratic People''s Republic of\r', 'Korea, Demokratische Volksrepublik', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(114, '0000-00-00 00:00:00', 'KR', 'KOR', 410, 'AS', 'Korea, Republic of\r', 'Korea, Republik', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(115, '0000-00-00 00:00:00', 'KW', 'KWT', 414, 'AS', 'Kuwait, State of\r', 'Kuwait', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(116, '0000-00-00 00:00:00', 'KG', 'KGZ', 417, 'AS', 'Kyrgyz Republic\r', 'Kirgisistan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(117, '0000-00-00 00:00:00', 'LA', 'LAO', 418, 'AS', 'Lao People''s Democratic Republic\r', 'Laos, Demokratische Volksrepublik', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(118, '0000-00-00 00:00:00', 'LV', 'LVA', 428, 'EU', 'Latvia, Republic of\r', 'Lettland', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(119, '0000-00-00 00:00:00', 'LB', 'LBN', 422, 'AS', 'Lebanon, Lebanese Republic\r', 'Libanon', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(120, '0000-00-00 00:00:00', 'LS', 'LSO', 426, 'AF', 'Lesotho, Kingdom of\r', 'Lesotho', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(121, '0000-00-00 00:00:00', 'LR', 'LBR', 430, 'AF', 'Liberia, Republic of\r', 'Liberia', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(122, '0000-00-00 00:00:00', 'LY', 'LBY', 434, 'AF', 'Libyan Arab Jamahiriya\r', 'Libysch-Arabische Dschamahirija', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(123, '0000-00-00 00:00:00', 'LI', 'LIE', 438, 'EU', 'Liechtenstein, Principality of\r', 'Liechtenstein', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(124, '0000-00-00 00:00:00', 'LT', 'LTU', 440, 'EU', 'Lithuania, Republic of\r', 'Litauen', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(125, '0000-00-00 00:00:00', 'LU', 'LUX', 442, 'EU', 'Luxembourg, Grand Duchy of\r', 'Luxembourg', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(126, '0000-00-00 00:00:00', 'MO', 'MAC', 446, 'AS', 'Macao, Special Administrative Region of China\r', 'Macau', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(127, '0000-00-00 00:00:00', 'MK', 'MKD', 807, 'EU', 'Macedonia, the former Yugoslav Republic of\r', 'Mazedonien, Ehemalige Jugoslawische Republik', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(128, '0000-00-00 00:00:00', 'MG', 'MDG', 450, 'AF', 'Madagascar, Republic of\r', 'Madagaskar', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(129, '0000-00-00 00:00:00', 'MW', 'MWI', 454, 'AF', 'Malawi, Republic of\r', 'Malawi', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(130, '0000-00-00 00:00:00', 'MY', 'MYS', 458, 'AS', 'Malaysia\r', 'Malaysia', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(131, '0000-00-00 00:00:00', 'MV', 'MDV', 462, 'AS', 'Maldives, Republic of\r', 'Malediven', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(132, '0000-00-00 00:00:00', 'ML', 'MLI', 466, 'AF', 'Mali, Republic of\r', 'Mali', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(133, '0000-00-00 00:00:00', 'MT', 'MLT', 470, 'EU', 'Malta, Republic of\r', 'Malta', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(134, '0000-00-00 00:00:00', 'MH', 'MHL', 584, 'OC', 'Marshall Islands, Republic of the\r', 'Marshallinseln', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(135, '0000-00-00 00:00:00', 'MQ', 'MTQ', 474, 'NA', 'Martinique\r', 'Martinique', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(136, '0000-00-00 00:00:00', 'MR', 'MRT', 478, 'AF', 'Mauritania, Islamic Republic of\r', 'Mauretanien', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(137, '0000-00-00 00:00:00', 'MU', 'MUS', 480, 'AF', 'Mauritius, Republic of\r', 'Mauritius', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(138, '0000-00-00 00:00:00', 'YT', 'MYT', 175, 'AF', 'Mayotte\r', 'Mayotte', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(139, '0000-00-00 00:00:00', 'MX', 'MEX', 484, 'NA', 'Mexico, United Mexican States\r', 'Mexiko', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(140, '0000-00-00 00:00:00', 'FM', 'FSM', 583, 'OC', 'Micronesia, Federated States of\r', 'Mikronesien, Föderierte Staaten Von', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(141, '0000-00-00 00:00:00', 'MD', 'MDA', 498, 'EU', 'Moldova, Republic of\r', 'Moldau, Republik', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(142, '0000-00-00 00:00:00', 'MC', 'MCO', 492, 'EU', 'Monaco, Principality of\r', 'Monaco', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(143, '0000-00-00 00:00:00', 'MN', 'MNG', 496, 'AS', 'Mongolia\r', 'Mongolei', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(144, '0000-00-00 00:00:00', 'MS', 'MSR', 500, 'NA', 'Montserrat\r', 'Montserrat', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(145, '0000-00-00 00:00:00', 'MA', 'MAR', 504, 'AF', 'Morocco, Kingdom of\r', 'Marokko', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(146, '0000-00-00 00:00:00', 'MZ', 'MOZ', 508, 'AF', 'Mozambique, Republic of\r', 'Mosambik', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(147, '0000-00-00 00:00:00', 'MM', 'MMR', 104, 'AS', 'Myanmar, Union of\r', 'Myanmar', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(148, '0000-00-00 00:00:00', 'NA', 'NAM', 516, 'AF', 'Namibia, Republic of\r', 'Namibia', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(149, '0000-00-00 00:00:00', 'NR', 'NRU', 520, 'OC', 'Nauru, Republic of\r', 'Nauru', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(150, '0000-00-00 00:00:00', 'NP', 'NPL', 524, 'AS', 'Nepal, State of\r', 'Nepal', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(151, '0000-00-00 00:00:00', 'NL', 'NLD', 528, 'EU', 'Netherlands, Kingdom of the\r', 'Niederlande', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(152, '0000-00-00 00:00:00', 'AN', 'ANT', 530, 'NA', 'Netherlands Antilles\r', 'Niederländische Antillen', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(153, '0000-00-00 00:00:00', 'NC', 'NCL', 540, 'OC', 'New Caledonia\r', 'Neukaledonien', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(154, '0000-00-00 00:00:00', 'NZ', 'NZL', 554, 'OC', 'New Zealand\r', 'Neuseeland', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(155, '0000-00-00 00:00:00', 'NI', 'NIC', 558, 'NA', 'Nicaragua, Republic of\r', 'Nicaragua', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(156, '0000-00-00 00:00:00', 'NE', 'NER', 562, 'AF', 'Niger, Republic of\r', 'Niger', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(157, '0000-00-00 00:00:00', 'NG', 'NGA', 566, 'AF', 'Nigeria, Federal Republic of\r', 'Nigeria', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(158, '0000-00-00 00:00:00', 'NU', 'NIU', 570, 'OC', 'Niue\r', 'Niue', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(159, '0000-00-00 00:00:00', 'NF', 'NFK', 574, 'OC', 'Norfolk Island\r', 'Norfolk-Insel', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(160, '0000-00-00 00:00:00', 'MP', 'MNP', 580, 'OC', 'Northern Mariana Islands, Commonwealth of the\r', 'Nördliche Marianen', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(161, '0000-00-00 00:00:00', 'NO', 'NOR', 578, 'EU', 'Norway, Kingdom of\r', 'Norwegen', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(162, '0000-00-00 00:00:00', 'OM', 'OMN', 512, 'AS', 'Oman, Sultanate of\r', 'Oman', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(163, '0000-00-00 00:00:00', 'PK', 'PAK', 586, 'AS', 'Pakistan, Islamic Republic of\r', 'Pakistan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(164, '0000-00-00 00:00:00', 'PW', 'PLW', 585, 'OC', 'Palau, Republic of\r', 'Palau', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(165, '0000-00-00 00:00:00', 'PS', 'PSE', 275, 'AS', 'Palestinian Territory, Occupied\r', 'Palästina', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(166, '0000-00-00 00:00:00', 'PA', 'PAN', 591, 'NA', 'Panama, Republic of\r', 'Panama', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(167, '0000-00-00 00:00:00', 'PG', 'PNG', 598, 'OC', 'Papua New Guinea, Independent State of\r', 'Papua-Neuguinea', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(168, '0000-00-00 00:00:00', 'PY', 'PRY', 600, 'SA', 'Paraguay, Republic of\r', 'Paraguay', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(169, '0000-00-00 00:00:00', 'PE', 'PER', 604, 'SA', 'Peru, Republic of\r', 'Peru', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(170, '0000-00-00 00:00:00', 'PH', 'PHL', 608, 'AS', 'Philippines, Republic of the\r', 'Philippinen', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(171, '0000-00-00 00:00:00', 'PN', 'PCN', 612, 'OC', 'Pitcairn Islands\r', 'Pitcairn', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(172, '0000-00-00 00:00:00', 'PL', 'POL', 616, 'EU', 'Poland, Republic of\r', 'Polen', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(173, '0000-00-00 00:00:00', 'PT', 'PRT', 620, 'EU', 'Portugal, Portuguese Republic\r', 'Portugal', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(174, '0000-00-00 00:00:00', 'PR', 'PRI', 630, 'NA', 'Puerto Rico, Commonwealth of\r', 'Puerto Rico', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(175, '0000-00-00 00:00:00', 'QA', 'QAT', 634, 'AS', 'Qatar, State of\r', 'Katar', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(176, '0000-00-00 00:00:00', 'RE', 'REU', 638, 'AF', 'Reunion\r', 'Réunion', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(177, '0000-00-00 00:00:00', 'RO', 'ROU', 642, 'EU', 'Romania\r', 'Rumänien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(178, '0000-00-00 00:00:00', 'RU', 'RUS', 643, 'EU', 'Russian Federation\r', 'Russische Föderation', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(179, '0000-00-00 00:00:00', 'RW', 'RWA', 646, 'AF', 'Rwanda, Republic of\r', 'Ruanda', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(180, '0000-00-00 00:00:00', 'SH', 'SHN', 654, 'AF', 'Saint Helena\r', 'St. Helena', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(181, '0000-00-00 00:00:00', 'KN', 'KNA', 659, 'NA', 'Saint Kitts and Nevis, Federation of\r', 'Saint Kitts und Nevis', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(182, '0000-00-00 00:00:00', 'LC', 'LCA', 662, 'NA', 'Saint Lucia\r', 'Santa Lucia', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(183, '0000-00-00 00:00:00', 'PM', 'SPM', 666, 'NA', 'Saint Pierre and Miquelon\r', 'Saint-Pierre und Miquelon', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(184, '0000-00-00 00:00:00', 'VC', 'VCT', 670, 'NA', 'Saint Vincent and the Grenadines\r', 'Saint Vincent und die Grenadinen', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(185, '0000-00-00 00:00:00', 'WS', 'WSM', 882, 'OC', 'Samoa, Independent State of\r', 'Samoa', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(186, '0000-00-00 00:00:00', 'SM', 'SMR', 674, 'EU', 'San Marino, Republic of\r', 'San Marino', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(187, '0000-00-00 00:00:00', 'ST', 'STP', 678, 'AF', 'Sao Tome and Principe, Democratic Republic of\r', 'São Tomé und Príncipe', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(188, '0000-00-00 00:00:00', 'SA', 'SAU', 682, 'AS', 'Saudi Arabia, Kingdom of\r', 'Saudi-Arabien', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(189, '0000-00-00 00:00:00', 'SN', 'SEN', 686, 'AF', 'Senegal, Republic of\r', 'Senegal', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(190, '0000-00-00 00:00:00', 'SC', 'SYC', 690, 'AF', 'Seychelles, Republic of\r', 'Seychellen', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(191, '0000-00-00 00:00:00', 'SL', 'SLE', 694, 'AF', 'Sierra Leone, Republic of\r', 'Sierra Leone', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(192, '0000-00-00 00:00:00', 'SG', 'SGP', 702, 'AS', 'Singapore, Republic of\r', 'Singapur', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(193, '0000-00-00 00:00:00', 'SK', 'SVK', 703, 'EU', 'Slovakia (Slovak Republic)\r', 'Slowakei (Slowakische Republik)', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(194, '0000-00-00 00:00:00', 'SI', 'SVN', 705, 'EU', 'Slovenia, Republic of\r', 'Slowenien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(195, '0000-00-00 00:00:00', 'SB', 'SLB', 90, 'OC', 'Solomon Islands\r', 'Salomonen', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(196, '0000-00-00 00:00:00', 'SO', 'SOM', 706, 'AF', 'Somalia, Somali Republic\r', 'Somalia', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(197, '0000-00-00 00:00:00', 'ZA', 'ZAF', 710, 'AF', 'South Africa, Republic of\r', 'Südafrika', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(198, '0000-00-00 00:00:00', 'GS', 'SGS', 239, 'AN', 'South Georgia and the South Sandwich Islands\r', 'Südgeorgien und Südliche Sandwichinseln', 'Antarctica', 'Antarktis', '', '');
INSERT INTO `phpwcms_country` VALUES(199, '0000-00-00 00:00:00', 'ES', 'ESP', 724, 'EU', 'Spain, Kingdom of\r', 'Spanien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(200, '0000-00-00 00:00:00', 'LK', 'LKA', 144, 'AS', 'Sri Lanka, Democratic Socialist Republic of\r', 'Sri Lanka', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(201, '0000-00-00 00:00:00', 'SD', 'SDN', 736, 'AF', 'Sudan, Republic of\r', 'Sudan', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(202, '0000-00-00 00:00:00', 'SR', 'SUR', 740, 'AF', 'Suriname, Republic of\r', 'Suriname', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(203, '0000-00-00 00:00:00', 'SJ', 'SJM', 744, 'EU', 'Svalbard & Jan Mayen Islands\r', 'Svalbard und Jan Mayen', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(204, '0000-00-00 00:00:00', 'SZ', 'SWZ', 748, 'AF', 'Swaziland, Kingdom of\r', 'Swasiland', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(205, '0000-00-00 00:00:00', 'SE', 'SWE', 752, 'EU', 'Sweden, Kingdom of\r', 'Schweden', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(206, '0000-00-00 00:00:00', 'CH', 'CHE', 756, 'EU', 'Switzerland, Swiss Confederation\r', 'Schweiz', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(207, '0000-00-00 00:00:00', 'SY', 'SYR', 760, 'AS', 'Syrian Arab Republic\r', 'Syrien, Arabische Republik', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(208, '0000-00-00 00:00:00', 'TW', 'TWN', 158, 'AS', 'Taiwan\r', 'Taiwan (China)', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(209, '0000-00-00 00:00:00', 'TJ', 'TJK', 762, 'AS', 'Tajikistan, Republic of\r', 'Tadschikistan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(210, '0000-00-00 00:00:00', 'TZ', 'TZA', 834, 'AF', 'Tanzania, United Republic of\r', 'Tansania, Vereinigte Republik', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(211, '0000-00-00 00:00:00', 'TH', 'THA', 764, 'AS', 'Thailand, Kingdom of\r', 'Thailand', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(212, '0000-00-00 00:00:00', 'TG', 'TGO', 768, 'AF', 'Togo, Togolese Republic\r', 'Togo', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(213, '0000-00-00 00:00:00', 'TK', 'TKL', 772, 'OC', 'Tokelau\r', 'Tokelau', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(214, '0000-00-00 00:00:00', 'TO', 'TON', 776, 'OC', 'Tonga, Kingdom of\r', 'Tonga', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(215, '0000-00-00 00:00:00', 'TT', 'TTO', 780, 'NA', 'Trinidad and Tobago, Republic of\r', 'Trinidad und Tobago', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(216, '0000-00-00 00:00:00', 'TN', 'TUN', 788, 'AF', 'Tunisia, Tunisian Republic\r', 'Tunesien', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(217, '0000-00-00 00:00:00', 'TR', 'TUR', 792, 'AS', 'Turkey, Republic of\r', 'Türkei', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(218, '0000-00-00 00:00:00', 'TM', 'TKM', 795, 'AS', 'Turkmenistan\r', 'Turkmenistan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(219, '0000-00-00 00:00:00', 'TC', 'TCA', 796, 'NA', 'Turks and Caicos Islands\r', 'Turks- und Caicosinseln', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(220, '0000-00-00 00:00:00', 'TV', 'TUV', 798, 'OC', 'Tuvalu\r', 'Tuvalu', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(221, '0000-00-00 00:00:00', 'UG', 'UGA', 800, 'AF', 'Uganda, Republic of\r', 'Uganda', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(222, '0000-00-00 00:00:00', 'UA', 'UKR', 804, 'EU', 'Ukraine\r', 'Ukraine', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(223, '0000-00-00 00:00:00', 'AE', 'ARE', 784, 'AS', 'United Arab Emirates\r', 'Vereinigte Arabische Emirate', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(224, '0000-00-00 00:00:00', 'GB', 'GBR', 826, 'EU', 'United Kingdom of Great Britain & Northern Ireland\r', 'United Kingdom', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(225, '0000-00-00 00:00:00', 'US', 'USA', 840, 'NA', 'United States of America\r', 'Vereinigte Staaten', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(226, '0000-00-00 00:00:00', 'UM', 'UMI', 581, 'OC', 'United States Minor Outlying Islands\r', 'Kleinere entlegene Inseln der Vereinigten Staaten', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(227, '0000-00-00 00:00:00', 'UY', 'URY', 858, 'SA', 'Uruguay, Eastern Republic of\r', 'Uruguay', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(228, '0000-00-00 00:00:00', 'UZ', 'UZB', 860, 'AS', 'Uzbekistan, Republic of\r', 'Usbekistan', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(229, '0000-00-00 00:00:00', 'VU', 'VUT', 548, 'OC', 'Vanuatu, Republic of\r', 'Vanuatu', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(230, '0000-00-00 00:00:00', 'VE', 'VEN', 862, 'SA', 'Venezuela, Bolivarian Republic of\r', 'Venezuela', 'South America', 'Südamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(231, '0000-00-00 00:00:00', 'VN', 'VNM', 704, 'AS', 'Vietnam, Socialist Republic of\r', 'Vietnam', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(232, '0000-00-00 00:00:00', 'VG', 'VGB', 92, 'NA', 'British Virgin Islands\r', 'Jungferninseln (Britische)', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(233, '0000-00-00 00:00:00', 'VI', 'VIR', 850, 'NA', 'United States Virgin Islands\r', 'Jungferninseln (Amerikanische)', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(234, '0000-00-00 00:00:00', 'WF', 'WLF', 876, 'OC', 'Wallis and Futuna\r', 'Wallis und Futuna', 'Oceania', 'Ozeanien', '', '');
INSERT INTO `phpwcms_country` VALUES(235, '0000-00-00 00:00:00', 'EH', 'ESH', 732, 'AF', 'Western Sahara\r', 'Westsahara', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(236, '0000-00-00 00:00:00', 'YE', 'YEM', 887, 'AS', 'Yemen\r', 'Jemen', 'Asia', 'Asien', '', '');
INSERT INTO `phpwcms_country` VALUES(237, '0000-00-00 00:00:00', 'YU', 'YUG', 891, 'EU', 'Yugoslavia', 'Jugoslawien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(238, '0000-00-00 00:00:00', 'ZM', 'ZMB', 894, 'AF', 'Zambia, Republic of\r', 'Sambia', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(239, '0000-00-00 00:00:00', 'ZW', 'ZWE', 716, 'AF', 'Zimbabwe, Republic of', 'Simbabwe', 'Africa', 'Afrika', '', '');
INSERT INTO `phpwcms_country` VALUES(240, '0000-00-00 00:00:00', 'AX', 'ALA', 248, 'EU', 'Åland Islands\r', 'Åland Inseln', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(241, '0000-00-00 00:00:00', 'GG', 'GGY', 831, 'EU', 'Guernsey, Bailiwick of', 'Guernsey, Vogtei', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(242, '0000-00-00 00:00:00', 'IM', 'IMN', 833, 'EU', 'Isle of Man', 'Insel Man', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(243, '0000-00-00 00:00:00', 'JE', 'JEY', 832, 'EU', 'Jersey, Bailiwick of', 'Jersey, Vogtei', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(244, '0000-00-00 00:00:00', 'ME', 'MNE', 499, 'EU', 'Montenegro, Republic of', 'Montenegro', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(245, '0000-00-00 00:00:00', 'BL', 'BLM', 652, 'NA', 'Saint Barthelemy', 'Sankt Bartholomäus', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(246, '0000-00-00 00:00:00', 'MF', 'MAF', 663, 'NA', 'Saint Martin', 'Saint-Martin', 'North America', 'Nordamerika', '', '');
INSERT INTO `phpwcms_country` VALUES(247, '0000-00-00 00:00:00', 'RS', 'SRB', 688, 'EU', 'Serbia, Republic of', 'Serbien', 'Europe', 'Europa', '', '');
INSERT INTO `phpwcms_country` VALUES(248, '0000-00-00 00:00:00', 'TL', 'TLS', 626, 'AS', 'Timor-Leste, Democratic Republic of', 'Osttimor (Timor-Leste)', 'Asia', 'Asien', '', '');

