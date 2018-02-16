#####################################################
#
#  CMSGO SQL Update
#  
#  25.08.2004
#
#####################################################

ALTER TABLE `cmsgo_article` ADD `article_cache` INT( 14 ) NOT NULL ;
ALTER TABLE `cmsgo_article` ADD `article_searchable` INT( 1 ) NOT NULL ;

CREATE TABLE `cmsgo_cache` (
`cache_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`cache_hash` VARCHAR( 50 ) NOT NULL ,
`cache_changed` TIMESTAMP NOT NULL ,
`cache_searchable` INT( 1 ) NOT NULL ,
`cache_content` MEDIUMTEXT NOT NULL ,
PRIMARY KEY ( `cache_id` ) ,
INDEX ( `cache_hash` ) 
);

CREATE TABLE `cmsgo_map` (
  `map_id` int(11) NOT NULL auto_increment,
  `map_cid` int(11) NOT NULL default '0',
  `map_x` int(5) NOT NULL default '0',
  `map_y` int(5) NOT NULL default '0',
  `map_title` text NOT NULL,
  `map_deleted` int(1) NOT NULL default '0',
  `map_vars` mediumblob NOT NULL,
  PRIMARY KEY  (`map_id`)
);
