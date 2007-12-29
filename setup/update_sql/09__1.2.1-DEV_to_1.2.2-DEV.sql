#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.2.1-DEV to 1.2.2-DEV
#  2005.03.05
#
#####################################################

ALTER TABLE `phpwcms_user` ADD `usr_wysiwyg` INT( 2 ) NOT NULL ;
ALTER TABLE `phpwcms_user` ADD `usr_fe` INT( 1 ) NOT NULL ;
UPDATE `phpwcms_user` SET usr_fe =2 ;
ALTER TABLE `phpwcms_userlog` ADD `logged_section` INT( 1 ) NOT NULL ;
CREATE TABLE `phpwcms_sysvalue` (
	`sysvalue_key` VARCHAR( 255 ) NOT NULL ,
	`sysvalue_tstamp` TIMESTAMP NOT NULL ,
	`sysvalue_value` MEDIUMBLOB NOT NULL ,
	PRIMARY KEY ( `sysvalue_key` ) ,
	FULLTEXT ( `sysvalue_key` )
);
ALTER TABLE `phpwcms_article` ADD `article_nositemap` INT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_articlecat` ADD `acat_nositemap` INT( 1 ) DEFAULT '0' NOT NULL ;
