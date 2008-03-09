#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.3.5 to 1.5
#
#####################################################


# 2008-02-24
ALTER TABLE `phpwcms_articlecat` ADD `acat_archive` INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecat` ADD INDEX ( `acat_archive` ) ;
ALTER TABLE `phpwcms_article` ADD `article_archive_status` INT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `phpwcms_article` ADD INDEX ( `article_archive_status` ) ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_category` VARCHAR( 255 ) NOT NULL;


# 2008-02-25
ALTER TABLE `phpwcms_file` CHANGE `f_cat` `f_cat` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `phpwcms_file` CHANGE `f_changed` `f_changed` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` CHANGE `f_created` `f_created` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` DROP `f_log`;
ALTER TABLE `phpwcms_file` CHANGE `f_longinfo` `f_longinfo` TEXT NOT NULL;
ALTER TABLE `phpwcms_file` ADD `f_copyright` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `phpwcms_file` ADD `f_tags` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `phpwcms_file` CHANGE `f_size` `f_size` INT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

# 2008-03-03
ALTER TABLE `phpwcms_calendar` ADD `calendar_lang` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_calendar` ADD INDEX ( `calendar_lang` ) ;

# 2008-03-06
ALTER TABLE `phpwcms_file` ADD `f_granted` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_file` ADD INDEX ( `f_granted` ) ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_granted` INT NOT NULL DEFAULT '0';
ALTER TABLE `phpwcms_articlecontent` ADD INDEX ( `acontent_granted` ) ;

# 2008-03-09
ALTER TABLE `phpwcms_calendar` ADD `calendar_teaser` TEXT NOT NULL AFTER `calendar_where`;