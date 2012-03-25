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
);

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

#2009-07-29
ALTER TABLE `phpwcms_shop_products` ADD `shopprod_track_view` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_shop_products` ADD INDEX ( `shopprod_track_view` );

CREATE TABLE IF NOT EXISTS `phpwcms_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `log_type` varchar(50) NOT NULL DEFAULT '',
  `log_ip` varchar(30) NOT NULL DEFAULT '',
  `log_user_agent` varchar(255) NOT NULL,
  `log_user_id` int(11) NOT NULL DEFAULT '0',
  `log_user_name` varchar(255) NOT NULL,
  `log_referrer_id` int(11) NOT NULL DEFAULT '0',
  `log_referrer_url` text NOT NULL,
  `log_data1` varchar(255) NOT NULL DEFAULT '',
  `log_data2` varchar(255) NOT NULL DEFAULT '',
  `log_data3` varchar(255) NOT NULL DEFAULT '',
  `log_msg` text NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_referrer_id` (`log_referrer_id`),
  KEY `log_type` (`log_type`)
);

#2009-08-19
ALTER TABLE `phpwcms_calendar` CHANGE `calendar_refid` `calendar_refid` VARCHAR( 255 ) NOT NULL DEFAULT '';
