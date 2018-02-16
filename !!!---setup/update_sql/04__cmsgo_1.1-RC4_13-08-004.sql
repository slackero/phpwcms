#####################################################
#
#  CMSGO SQL Update
#  
#  13.08.2004
#
#####################################################

ALTER TABLE `cmsgo_guestbook` ADD `guestbook_show` INT( 1 ) NOT NULL ;
ALTER TABLE `cmsgo_guestbook` CHANGE `guestbook_url` `guestbook_url` TEXT NOT NULL ;
ALTER TABLE `cmsgo_guestbook` CHANGE `guestbook_email` `guestbook_email` TEXT NOT NULL ;
ALTER TABLE `cmsgo_guestbook` CHANGE `guestbook_name` `guestbook_name` TEXT NOT NULL ;
ALTER TABLE `cmsgo_guestbook` ADD `guestbook_ip` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE `cmsgo_guestbook` ADD `guestbook_useragent` VARCHAR( 50 ) NOT NULL ;
