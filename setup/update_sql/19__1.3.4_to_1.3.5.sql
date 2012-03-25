#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.4 to 1.3.5
#
#####################################################


DROP TABLE IF EXISTS `phpwcms_categories` ;
CREATE TABLE `phpwcms_categories` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_type` varchar(255) NOT NULL default '',
  `cat_pid` int(11) NOT NULL default '0',
  `cat_status` int(1) NOT NULL default '0',
  `cat_createdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_changedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_name` varchar(255) NOT NULL default '',
  `cat_info` text NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `cat_type` (`cat_type`,`cat_status`),
  KEY `cat_pid` (`cat_pid`)
);
