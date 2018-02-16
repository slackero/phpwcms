#####################################################
#
#  CMSGO SQL Update
#  
#  25.08.2004
#
#####################################################

ALTER TABLE `cmsgo_articlecat` ADD `acat_order` INT( 2 ) NOT NULL ;
ALTER TABLE `cmsgo_article` ADD `article_created` VARCHAR( 14 ) NOT NULL ;
UPDATE `cmsgo_article` SET `article_created` = UNIX_TIMESTAMP( `article_tstamp` ) WHERE `article_created` = '';

