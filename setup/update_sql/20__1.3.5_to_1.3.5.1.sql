#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.5 to 1.3.5.1
#
#####################################################

DROP TABLE IF EXISTS `phpwcms_log` ;
CREATE TABLE `phpwcms_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `log_type` varchar(255) NOT NULL,
  `log_timestamp` timestamp NOT NULL,
  `log_message` text NOT NULL,
  `log_ip` varchar(50) NOT NULL,
  `log_userid` varchar(255) NOT NULL,
  PRIMARY KEY  (`log_id`)
);

ALTER TABLE `phpwcms_guestbook` CHANGE `guestbook_created` `guestbook_created` INT(11) NOT NULL ;
ALTER TABLE `phpwcms_userlog` CHANGE `logged_change` `logged_change` INT(11) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_userlog` CHANGE `logged_start` `logged_start` INT(11) DEFAULT '0' NOT NULL ;

ALTER TABLE `phpwcms_articlecat` CHANGE `acat_alias` `acat_alias` VARCHAR(255) NOT NULL ;
ALTER TABLE `phpwcms_articlecat` DROP INDEX `acat_alias`;
ALTER TABLE `phpwcms_articlecat` ADD INDEX `acat_alias` (`acat_alias`);

ALTER TABLE `phpwcms_file` DROP INDEX `f_name`;
ALTER TABLE `phpwcms_file` ADD INDEX `f_name` (`f_name`);
ALTER TABLE `phpwcms_file` DROP INDEX `f_shortinfo`;
ALTER TABLE `phpwcms_file` ADD INDEX `f_shortinfo` (`f_shortinfo`);
