#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.5 to 1.3.5.1
#
#####################################################


ALTER TABLE `phpwcms_categories` ADD `cat_pid` INT( 11 ) NOT NULL AFTER `cat_type` ;
ALTER TABLE `phpwcms_categories` ADD INDEX ( `cat_pid` ) ;

CREATE TABLE `phpwcms_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `log_type` varchar(255) NOT NULL,
  `log_timestamp` timestamp NOT NULL,
  `log_message` text NOT NULL,
  `log_ip` varchar(50) NOT NULL,
  `log_userid` varchar(255) NOT NULL,
  PRIMARY KEY  (`log_id`)
) TYPE=MyISAM ;

ALTER TABLE `phpwcms_guestbook` CHANGE `guestbook_created` `guestbook_created` INT(11) NOT NULL ;
ALTER TABLE `phpwcms_userlog` CHANGE `logged_change` `logged_change` INT(11) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_userlog` CHANGE `logged_start` `logged_start` INT(11) DEFAULT '0' NOT NULL ;