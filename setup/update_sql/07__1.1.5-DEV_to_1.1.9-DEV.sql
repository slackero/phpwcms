#####################################################
#
#  PHPWCMS SQL Update
#  
#  25-01-2005
#
#####################################################


ALTER TABLE `phpwcms_articlecontent` ADD `acontent_block` varchar(200) NOT NULL default 'CONTENT' ;
UPDATE `phpwcms_articlecontent` SET `acontent_block`='CONTENT' WHERE `acontent_block`='' OR `acontent_block`='0' ;