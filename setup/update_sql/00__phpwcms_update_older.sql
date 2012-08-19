#
# PHPWCMS database update script
# ==============================
# 19-04-2004

# added new field for different kind of article listing
ALTER TABLE `phpwcms_articlecat` ADD `acat_topcount` INT NOT NULL ;

#
# guestbook table `phpwcms_guestbook`
#

CREATE TABLE `phpwcms_guestbook` (
  `guestbook_id` int(11) NOT NULL auto_increment,
  `guestbook_cid` int(11) NOT NULL default '0',
  `guestbook_msg` text NOT NULL,
  `guestbook_name` varchar(255) NOT NULL default '',
  `guestbook_email` varchar(255) NOT NULL default '',
  `guestbook_created` varchar(14) NOT NULL default '',
  `guestbook_trashed` int(1) NOT NULL default '0',
  `guestbook_url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`guestbook_id`)
);

DROP TABLE IF EXISTS `phpwcms_newsletter`;

CREATE TABLE `phpwcms_newsletter` (
  `newsletter_id` int(11) NOT NULL auto_increment,
  `newsletter_subject` text NOT NULL,
  `newsletter_changed` timestamp NOT NULL,
  `newsletter_vars` mediumblob NOT NULL,
  `newsletter_trashed` int(1) NOT NULL default '0',
  PRIMARY KEY  (`newsletter_id`)
);


# 24-03-2004

# change or add field definitions

ALTER TABLE `phpwcms_articlecat` ADD `acat_ssl` INT( 1 ) NOT NULL ;

ALTER TABLE `phpwcms_articlecat` ADD `acat_regonly` INT( 1 ) NOT NULL ;

ALTER TABLE `phpwcms_articlecat` CHANGE `acat_name` `acat_name` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `phpwcms_article` ADD `article_notitle` INT( 1 ) NOT NULL ;

ALTER TABLE `phpwcms_user` CHANGE `usr_pass` `usr_pass` VARCHAR( 255 ) NOT NULL ;

# new for storing UTF-8 datas = many chars
# article
ALTER TABLE `phpwcms_article` CHANGE `article_subtitle` `article_subtitle` TEXT NOT NULL;
ALTER TABLE `phpwcms_article` CHANGE `article_title` `article_title` TEXT NOT NULL;
ALTER TABLE `phpwcms_article` CHANGE `article_keyword` `article_keyword` TEXT NOT NULL;
ALTER TABLE `phpwcms_article` CHANGE `article_summary` `article_summary` TEXT NOT NULL;
ALTER TABLE `phpwcms_article` CHANGE `article_redirect` `article_redirect` TEXT NOT NULL;

# article content
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_title` `acontent_title` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_text` `acontent_text` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_alink` `acontent_alink` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_html` `acontent_html` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_image` `acontent_image` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_files` `acontent_files` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_subtitle` `acontent_subtitle` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_redirect` `acontent_redirect` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_media` `acontent_media` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_form` `acontent_form` MEDIUMTEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_newsletter` `acontent_newsletter` MEDIUMTEXT NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_before` `acontent_before` VARCHAR( 10 ) NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_after` `acontent_after` VARCHAR( 10 ) NOT NULL;
ALTER TABLE `phpwcms_articlecontent` CHANGE `acontent_type` `acontent_type` INT( 10 ) DEFAULT '0' NOT NULL;

# article category (structure)
ALTER TABLE `phpwcms_articlecat` CHANGE `acat_name` `acat_name` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecat` CHANGE `acat_info` `acat_info` TEXT NOT NULL;
ALTER TABLE `phpwcms_articlecat` CHANGE `acat_alias` `acat_alias` TEXT NOT NULL;

# subscription
ALTER TABLE `phpwcms_subscription` CHANGE `subscription_name` `subscription_name` TEXT NOT NULL;

# newsletter
ALTER TABLE `phpwcms_newsletter` CHANGE `newsletter_name` `newsletter_name` TEXT NOT NULL;
ALTER TABLE `phpwcms_newsletter` CHANGE `newsletter_subject` `newsletter_subject` TEXT NOT NULL;

# addresses
ALTER TABLE `phpwcms_address` CHANGE `address_name` `address_name` TEXT NOT NULL;

# blog
CREATE TABLE `phpwcms_blog` (
  `blog_id` int(11) NOT NULL auto_increment,
  `blog_cid` int(11) NOT NULL default '0',
  `blog_created` varchar(14) NOT NULL default '',
  `blog_changed` varchar(14) NOT NULL default '',
  `blog_editor` varchar(255) NOT NULL default '',
  `blog_var` mediumtext NOT NULL,
  `blog_active` int(1) NOT NULL default '0',
  `blog_trashed` int(1) NOT NULL default '0',
  PRIMARY KEY  (`blog_id`)
);