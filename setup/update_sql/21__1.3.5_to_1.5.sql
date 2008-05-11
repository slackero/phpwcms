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
