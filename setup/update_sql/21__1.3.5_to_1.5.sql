#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.5 to 1.5
#
#####################################################


# 1.3.9
ALTER TABLE `phpwcms_articlecat` ADD `acat_archive` INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecat` ADD INDEX ( `acat_archive` ) ;

ALTER TABLE `phpwcms_article` ADD `article_archive_status` INT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `phpwcms_article` ADD INDEX ( `article_archive_status` ) ;

ALTER TABLE `phpwcms_articlecontent` ADD `acontent_category` VARCHAR( 255 ) NOT NULL ;
