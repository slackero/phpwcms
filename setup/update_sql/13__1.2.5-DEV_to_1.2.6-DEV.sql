#####################################################
#
#  CMSGO SQL Update
#  
#  Upgrade release 1.2.5-DEV to 1.2.6-DEV
#  2005.11.05
#
#####################################################

ALTER TABLE `cmsgo_user` ADD `usr_vars` MEDIUMTEXT NOT NULL ;
ALTER TABLE `cmsgo_usergroup` ADD `group_name` VARCHAR( 200 ) NOT NULL AFTER `group_id` ;
ALTER TABLE `cmsgo_usergroup` ADD `group_member` MEDIUMTEXT NOT NULL AFTER `group_name` ;
ALTER TABLE `cmsgo_usergroup` ADD `group_timestamp` TIMESTAMP NOT NULL ;
ALTER TABLE `cmsgo_usergroup` ADD `group_trash` INT( 1 ) NOT NULL ;
ALTER TABLE `cmsgo_usergroup` ADD `group_active` INT( 1 ) NOT NULL ;

ALTER TABLE `cmsgo_articlecontent` ADD `acontent_anchor` INT( 1 ) NOT NULL ;

CREATE TABLE `cmsgo_keyword` (
  `keyword_id` int(11) NOT NULL auto_increment,
  `keyword_name` varchar(255) NOT NULL default '',
  `keyword_trash` int(1) NOT NULL default '0',
  PRIMARY KEY  (`keyword_id`)
);
