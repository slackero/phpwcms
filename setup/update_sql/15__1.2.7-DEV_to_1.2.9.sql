#####################################################
#
#  PHPWCMS SQL Update
#  
#  Upgrade release 1.2.7-DEV to 1.2.8
#
#####################################################


ALTER TABLE `phpwcms_articlecat` ADD `acat_pagetitle` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecat` ADD `acat_paginate` INT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_pagetitle` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_paginate` INT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_article` ADD `article_serialized` BLOB NOT NULL ;

ALTER TABLE `phpwcms_keyword` ADD `keyword_updated` TIMESTAMP NOT NULL ;
ALTER TABLE `phpwcms_keyword` ADD `keyword_created` VARCHAR( 14 ) NOT NULL AFTER `keyword_name` ;
ALTER TABLE `phpwcms_keyword` ADD `keyword_description` TEXT NOT NULL ;
ALTER TABLE `phpwcms_keyword` ADD `keyword_link` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_keyword` ADD `keyword_sort` INT DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_keyword` ADD `keyword_important` INT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_keyword` ADD `keyword_abbr` CHAR( 10 ) NOT NULL ;
ALTER TABLE `phpwcms_keyword` ADD INDEX ( `keyword_abbr` ) ;

ALTER TABLE `phpwcms_guestbook` CHANGE `guestbook_useragent` `guestbook_useragent` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_guestbook` ADD `guestbook_image` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_guestbook` ADD `guestbook_imagename` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_template` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_spacer` INT( 1 ) DEFAULT '0' NOT NULL ;

ALTER TABLE `phpwcms_address` CHANGE `address_email` `address_email` TEXT ;

ALTER TABLE `phpwcms_subscription` ADD `subscription_active` INT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_subscription` ADD `subscription_lang` VARCHAR( 100 ) NOT NULL ;
ALTER TABLE `phpwcms_subscription` ADD `subscription_tstamp` TIMESTAMP NOT NULL ;
UPDATE `phpwcms_subscription` SET `subscription_active` = 1 ; 

ALTER TABLE `phpwcms_newsletter` ADD `newsletter_created` TIMESTAMP NOT NULL ;
ALTER TABLE `phpwcms_newsletter` ADD `newsletter_lastsending` TIMESTAMP NOT NULL ;
ALTER TABLE `phpwcms_newsletter` ADD `newsletter_active` INT( 1 ) DEFAULT '0' NOT NULL ;

ALTER TABLE `phpwcms_address` ADD `address_iddetail` INT( 11 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_address` ADD `address_url1` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_address` ADD `address_url2` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `phpwcms_userdetail` CHANGE `detail_newsletter` `detail_newsletter` INT( 11 ) NOT NULL DEFAULT '0' ;


ALTER TABLE `phpwcms_userdetail` ADD `detail_website` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_userimage` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_varchar1` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_varchar2` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_varchar3` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_varchar4` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_varchar5` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_text1` TEXT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_text2` TEXT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_text3` TEXT NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_formid` INT( 11 ) NOT NULL DEFAULT '0' AFTER `detail_pid` ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_gender` VARCHAR( 255 ) NOT NULL AFTER `detail_userimage` ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_birthday` DATE NOT NULL AFTER `detail_gender` ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_email` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `phpwcms_userdetail` ADD `detail_password` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `phpwcms_userdetail` ADD INDEX ( `detail_formid` ) ;
ALTER TABLE `phpwcms_userdetail` ADD INDEX ( `detail_pid` ) ;

ALTER TABLE `phpwcms_userdetail` CHANGE `detail_title` `detail_title` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_firstname` `detail_firstname` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_lastname` `detail_lastname` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_company` `detail_company` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_street` `detail_street` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_add` `detail_add` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_city` `detail_city` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_zip` `detail_zip` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_region` `detail_region` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_country` `detail_country` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_fon` `detail_fon` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_fax` `detail_fax` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_mobile` `detail_mobile` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_fax` `detail_fax` VARCHAR( 255 ) NOT NULL ; 
ALTER TABLE `phpwcms_userdetail` CHANGE `detail_prof` `detail_prof` VARCHAR( 255 ) NOT NULL ; 

ALTER TABLE `phpwcms_template` ADD `template_type` INT( 11 ) NOT NULL DEFAULT '0' AFTER `template_id` ;

ALTER TABLE `phpwcms_articlecontent` ADD `acontent_tid` INT( 11 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_livedate` DATETIME NOT NULL ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_killdate` DATETIME NOT NULL;
ALTER TABLE `phpwcms_articlecontent` ADD INDEX ( `acontent_livedate` , `acontent_killdate` ) ;
ALTER TABLE `phpwcms_articlecontent` ADD `acontent_created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `acontent_uid` ;


DROP TABLE `phpwcms_results` ;
DROP TABLE `phpwcms_resultscat` ;
DROP TABLE `phpwcms_schedule` ;
DROP TABLE `phpwcms_schedulecat` ;

CREATE TABLE `phpwcms_formresult` (
  `formresult_id` int(11) NOT NULL auto_increment,
  `formresult_pid` int(11) NOT NULL default '0',
  `formresult_createdate` timestamp NULL,
  `formresult_ip` varchar(50) NOT NULL default '',
  `formresult_content` mediumblob NOT NULL,
  PRIMARY KEY  (`formresult_id`),
  KEY `formresult_pid` (`formresult_pid`)
);

CREATE TABLE `phpwcms_newsletterqueue` (
  `queue_id` int(11) NOT NULL auto_increment,
  `queue_created` timestamp NOT NULL,
  `queue_changed` timestamp NOT NULL,
  `queue_status` int(11) NOT NULL default '0',
  `queue_pid` int(11) NOT NULL default '0',
  `queue_rid` int(11) NOT NULL default '0',
  `queue_errormsg` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`queue_id`)
);
