#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.2.6-DEV to 1.2.7
#
#####################################################


CREATE TABLE `phpwcms_formtracking` (
  `formtracking_id` INT NOT NULL AUTO_INCREMENT,
  `formtracking_hash` VARCHAR( 50 ) NOT NULL default '',
  `formtracking_ip` VARCHAR( 20 ) NOT NULL default '',
  `formtracking_created` TIMESTAMP NOT NULL,
  `formtracking_sentdate` VARCHAR( 20 ) NOT NULL default '',
  `formtracking_sent` INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`formtracking_id`)
);

ALTER TABLE `phpwcms_articlecat` ADD `acat_maxlist` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecat` ADD `acat_cntpart` VARCHAR( 255 ) NOT NULL ;
