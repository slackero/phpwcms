#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.2.4-DEV to 1.2.5-DEV
#  2005.30.07
#
#####################################################

ALTER TABLE `phpwcms_file` ADD `f_refid` INT NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_aliasid` INT NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_headerdata` INT( 1 ) NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_morelink` INT( 1 ) DEFAULT '1' NOT NULL ;

CREATE TABLE `phpwcms_imgcache` (
	`imgcache_id` int(11) NOT NULL auto_increment,
	`imgcache_hash` varchar(50) NOT NULL default '',
	`imgcache_imgname` varchar(255) NOT NULL default '',
	`imgcache_width` int(11) NOT NULL default '0',
	`imgcache_height` int(11) NOT NULL default '0',
	`imgcache_wh` varchar(255) NOT NULL default '',
	`imgcache_timestamp` timestamp NOT NULL,
	`imgcache_trash` int(1) NOT NULL default '0',
	PRIMARY KEY  (`imgcache_id`),
	KEY `imgcache_hash` (`imgcache_hash`)
);
