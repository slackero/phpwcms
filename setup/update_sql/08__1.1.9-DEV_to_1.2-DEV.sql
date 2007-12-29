#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.1.9-DEV to 1.2.1-DEV
#  2005.02.20
#
#####################################################



CREATE TABLE `phpwcms_module` (
`module_id` INT NOT NULL AUTO_INCREMENT ,
`module_timestamp` TIMESTAMP NOT NULL ,
`module_name` VARCHAR( 30 ) NOT NULL ,
`module_mode` TINYINT( 1 ) NOT NULL ,
`module_title` TEXT NOT NULL ,
`module_description` TEXT NOT NULL ,
PRIMARY KEY ( `module_id` ) 
);

ALTER TABLE `phpwcms_cache` DROP `cache_content` ;
ALTER TABLE `phpwcms_cache` ADD `cache_uri` TEXT NOT NULL AFTER `cache_hash` ;
ALTER TABLE `phpwcms_cache` ADD `cache_stripped` LONGTEXT NOT NULL ;
ALTER TABLE `phpwcms_cache` ADD FULLTEXT (`cache_stripped`);
ALTER TABLE `phpwcms_cache` ADD `cache_isprint` INT( 1 ) NOT NULL AFTER `cache_timeout` ;
ALTER TABLE `phpwcms_forum` ADD `forum_ctopic` INT NOT NULL AFTER `forum_uid` ;
ALTER TABLE `phpwcms_forum` ADD `forum_cpost` INT NOT NULL AFTER `forum_ctopic` ;
ALTER TABLE `phpwcms_forum` ADD `forum_lastpost` MEDIUMTEXT NOT NULL ;
