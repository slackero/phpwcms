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

# 2008-03-03
ALTER TABLE `phpwcms_calendar` ADD `calendar_lang` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_calendar` ADD INDEX ( `calendar_lang` ) ;

# 2008-03-06
ALTER TABLE `phpwcms_file` ADD `f_granted` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` ADD INDEX ( `f_granted` ) ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_granted` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecontent` ADD INDEX ( `acontent_granted` ) ;

# 2008-03-09
ALTER TABLE `phpwcms_calendar` ADD `calendar_teaser` TEXT NOT NULL AFTER `calendar_where`;

# 2008-05-09
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