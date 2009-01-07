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
  `domain` varchar(255) NOT NULL,
  `query` varchar(255) NOT NULL,
  `pos` int(11) NOT NULL,
  `referrer` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#2008-12-22
ALTER TABLE `phpwcms_article` ADD `article_menutitle` VARCHAR( 255 ) NOT NULL ;
