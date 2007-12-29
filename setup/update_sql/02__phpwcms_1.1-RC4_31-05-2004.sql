#####################################################
#
#  PHPWCMS SQL Update
#  
#  31.05.2004
#
#####################################################


ALTER TABLE `phpwcms_articlecat` CHANGE `acat_redirect` `acat_redirect` TEXT NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_hidesummary` INT( 1 ) NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_image` BLOB NOT NULL ;


