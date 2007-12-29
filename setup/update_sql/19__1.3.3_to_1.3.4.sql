#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.3 to 1.3.4
#
#####################################################


ALTER TABLE `phpwcms_article` ADD `article_priorize` INT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_article` ADD INDEX ( `article_priorize` ) ;
ALTER TABLE `phpwcms_article` ADD INDEX ( `article_sort` ) ;

UPDATE `phpwcms_articlecontent` SET acontent_html='', acontent_tstamp=acontent_tstamp WHERE acontent_type=7 ;

ALTER TABLE `phpwcms_article` ADD `article_norss` INT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `phpwcms_article` ADD `article_alias` VARCHAR(255) NOT NULL AFTER `article_title`;
ALTER TABLE `phpwcms_article` CHANGE `article_username` `article_username` VARCHAR(255) NOT NULL;
ALTER TABLE `phpwcms_article` ADD INDEX `article_alias` (`article_alias`);