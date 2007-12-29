#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.2.9 to 1.3.0
#
#####################################################


ALTER TABLE `phpwcms_articlecontent` ADD `acontent_module` VARCHAR( 255 ) NOT NULL ;
