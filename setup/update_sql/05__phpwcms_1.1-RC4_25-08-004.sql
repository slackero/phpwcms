#####################################################
#
#  PHPWCMS SQL Update
#  
#  25.08.2004
#
#####################################################

ALTER TABLE `phpwcms_articlecat` ADD `acat_order` INT( 2 ) NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_created` VARCHAR( 14 ) NOT NULL ;
UPDATE `phpwcms_article` SET `article_created` = UNIX_TIMESTAMP( `article_tstamp` ) WHERE `article_created` = '';

