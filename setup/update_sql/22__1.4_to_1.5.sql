#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.4 to 1.5
#
#####################################################


# 2008-12-15
CREATE TABLE IF NOT EXISTS `phpwcms_log_seo` (
  `id` int(11) NOT NULL auto_increment,
  `create_date` timestamp NOT NULL,
  `domain` varchar(255) NOT NULL DEFAULT '',
  `query` varchar(255) NOT NULL DEFAULT '',
  `pos` int(11) NOT NULL DEFAULT 0,
  `referrer` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#2008-12-22
ALTER TABLE `phpwcms_article` ADD `article_menutitle` VARCHAR( 255 ) NOT NULL DEFAULT '' ;

#2009-01-18
ALTER TABLE `phpwcms_crossreference` ADD `cref_module` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `cref_type`;
ALTER TABLE `phpwcms_crossreference` ADD INDEX ( `cref_module` );

#2009-01-30
ALTER TABLE `phpwcms_file` ADD `f_sort` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` ADD INDEX ( f_sort );

#2009-06-26
ALTER TABLE `phpwcms_userdetail` ADD `detail_regkey` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `detail_id`;
ALTER TABLE `phpwcms_userdetail` ADD `detail_salutation` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `detail_title`;
ALTER TABLE `phpwcms_userdetail` ADD INDEX ( detail_regkey );