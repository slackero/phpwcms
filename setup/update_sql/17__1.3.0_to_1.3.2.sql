#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.0 to 1.3.2
#
#####################################################


ALTER TABLE `phpwcms_userdetail` ADD `detail_login` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_text4` TEXT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_text5` TEXT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `userdetail_lastlogin` DATETIME NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD UNIQUE (`detail_login`) ; 
ALTER TABLE `phpwcms_userdetail` ADD INDEX ( `detail_aktiv` ) ;
ALTER TABLE `phpwcms_userdetail` ADD INDEX ( `detail_password` ) ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_int1` BIGINT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_int2` BIGINT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_int3` BIGINT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_int4` BIGINT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_int5` BIGINT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_float1` FLOAT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_float2` FLOAT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_float3` FLOAT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_float4` FLOAT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_float5` FLOAT NOT NULL ;

ALTER TABLE `phpwcms_articlecontent` ADD `acontent_comment` TEXT NOT NULL ;

CREATE TABLE `phpwcms_crossreference` (
  `cref_id` int(11) NOT NULL auto_increment,
  `cref_type` varchar(255) NOT NULL default '',
  `cref_rid` int(11) NOT NULL default '0',
  `cref_int` int(11) NOT NULL default '0',
  `cref_str` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cref_id`),
  KEY `cref_type` (`cref_type`),
  KEY `cref_rid` (`cref_rid`),
  KEY `cref_int` (`cref_int`),
  KEY `cref_str` (`cref_str`)
);