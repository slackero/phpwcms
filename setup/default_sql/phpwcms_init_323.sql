-- phpMyAdmin SQL Dump
-- version 2.10.0-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 25. Februar 2007 um 18:13
-- Server Version: 4.1.13
-- PHP-Version: 4.4.0

CREATE TABLE `phpwcms_address` (
  `address_id` int(11) NOT NULL auto_increment,
  `address_key` varchar(255) NOT NULL default '',
  `address_email` text NOT NULL,
  `address_name` text NOT NULL,
  `address_verified` int(1) NOT NULL default '0',
  `address_tstamp` timestamp NOT NULL,
  `address_subscription` blob NOT NULL,
  `address_iddetail` int(11) NOT NULL default '0',
  `address_url1` varchar(255) NOT NULL default '',
  `address_url2` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`address_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_article` (
  `article_id` int(11) NOT NULL auto_increment,
  `article_cid` int(11) NOT NULL default '0',
  `article_tid` int(11) NOT NULL default '0',
  `article_uid` int(11) NOT NULL default '0',
  `article_tstamp` timestamp NOT NULL,
  `article_username` varchar(255) NOT NULL default '',
  `article_title` text NOT NULL,
  `article_alias` varchar(255) NOT NULL default '',
  `article_keyword` text NOT NULL,
  `article_public` int(1) NOT NULL default '0',
  `article_deleted` int(1) NOT NULL default '0',
  `article_begin` datetime NOT NULL,
  `article_end` datetime NOT NULL,
  `article_aktiv` int(1) NOT NULL default '0',
  `article_subtitle` text NOT NULL,
  `article_summary` text NOT NULL,
  `article_redirect` text NOT NULL,
  `article_sort` int(11) NOT NULL default '0',
  `article_notitle` int(1) NOT NULL default '0',
  `article_hidesummary` int(1) NOT NULL default '0',
  `article_image` blob NOT NULL,
  `article_created` varchar(14) NOT NULL default '',
  `article_cache` varchar(10) NOT NULL default '0',
  `article_nosearch` char(1) NOT NULL default '0',
  `article_nositemap` int(1) NOT NULL default '0',
  `article_aliasid` int(11) NOT NULL default '0',
  `article_headerdata` int(1) NOT NULL default '0',
  `article_morelink` int(1) NOT NULL default '1',
  `article_pagetitle` varchar(255) NOT NULL default '',
  `article_paginate` int(1) NOT NULL default '0',
  `article_serialized` blob NOT NULL,
  `article_priorize` int(5) NOT NULL default '0',
  `article_norss` int(1) NOT NULL default '1',
  PRIMARY KEY  (`article_id`),
  KEY `article_aktiv` (`article_aktiv`),
  KEY `article_public` (`article_public`),
  KEY `article_deleted` (`article_deleted`),
  KEY `article_nosearch` (`article_nosearch`),
  KEY `article_begin` (`article_begin`),
  KEY `article_end` (`article_end`),
  KEY `article_cid` (`article_cid`),
  KEY `article_tstamp` (`article_tstamp`),
  KEY `article_priorize` (`article_priorize`),
  KEY `article_sort` (`article_sort`),
  KEY `article_alias` (`article_alias`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_articlecat` (
  `acat_id` int(11) NOT NULL auto_increment,
  `acat_name` text NOT NULL,
  `acat_info` text NOT NULL,
  `acat_public` int(1) NOT NULL default '0',
  `acat_tstamp` timestamp NOT NULL,
  `acat_aktiv` int(1) NOT NULL default '0',
  `acat_uid` int(11) NOT NULL default '0',
  `acat_trash` int(1) NOT NULL default '0',
  `acat_struct` int(11) NOT NULL default '0',
  `acat_sort` int(11) NOT NULL default '0',
  `acat_alias` text NOT NULL,
  `acat_hidden` int(1) NOT NULL default '0',
  `acat_template` int(11) NOT NULL default '0',
  `acat_ssl` int(1) NOT NULL default '0',
  `acat_regonly` int(1) NOT NULL default '0',
  `acat_topcount` int(11) NOT NULL default '0',
  `acat_redirect` text NOT NULL,
  `acat_order` int(2) NOT NULL default '0',
  `acat_cache` varchar(10) NOT NULL default '',
  `acat_nosearch` char(1) NOT NULL default '',
  `acat_nositemap` int(1) NOT NULL default '0',
  `acat_permit` text NOT NULL,
  `acat_maxlist` int(11) NOT NULL default '0',
  `acat_cntpart` varchar(255) NOT NULL default '',
  `acat_pagetitle` varchar(255) NOT NULL default '',
  `acat_paginate` int(1) NOT NULL default '0',
  `acat_overwrite` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`acat_id`),
  KEY `acat_struct` (`acat_struct`),
  KEY `acat_sort` (`acat_sort`),
  FULLTEXT KEY `acat_alias` (`acat_alias`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_articlecontent` (
  `acontent_id` int(11) NOT NULL auto_increment,
  `acontent_aid` int(11) NOT NULL default '0',
  `acontent_uid` int(11) NOT NULL default '0',
  `acontent_created` timestamp NOT NULL,
  `acontent_tstamp` timestamp NOT NULL,
  `acontent_title` text NOT NULL,
  `acontent_text` text NOT NULL,
  `acontent_type` int(10) NOT NULL default '0',
  `acontent_sorting` int(11) NOT NULL default '0',
  `acontent_image` text NOT NULL,
  `acontent_files` text NOT NULL,
  `acontent_visible` int(1) NOT NULL default '0',
  `acontent_subtitle` text NOT NULL,
  `acontent_before` varchar(10) NOT NULL default '',
  `acontent_after` varchar(10) NOT NULL default '',
  `acontent_top` int(1) NOT NULL default '0',
  `acontent_redirect` text NOT NULL,
  `acontent_html` text NOT NULL,
  `acontent_trash` int(1) NOT NULL default '0',
  `acontent_alink` text NOT NULL,
  `acontent_media` text NOT NULL,
  `acontent_form` mediumtext NOT NULL,
  `acontent_newsletter` mediumtext NOT NULL,
  `acontent_block` varchar(200) NOT NULL default '0',
  `acontent_anchor` int(1) NOT NULL default '0',
  `acontent_template` varchar(255) NOT NULL default '',
  `acontent_spacer` int(1) NOT NULL default '0',
  `acontent_tid` int(11) NOT NULL default '0',
  `acontent_livedate` datetime NOT NULL,
  `acontent_killdate` datetime NOT NULL,
  `acontent_module` varchar(255) NOT NULL default '',
  `acontent_comment` text NOT NULL,
  `acontent_paginate_page` int(5) NOT NULL default '0',
  `acontent_paginate_title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`acontent_id`),
  KEY `acontent_aid` (`acontent_aid`),
  KEY `acontent_sorting` (`acontent_sorting`),
  KEY `acontent_type` (`acontent_type`),
  KEY `acontent_livedate` (`acontent_livedate`,`acontent_killdate`),
  KEY `acontent_paginate` (`acontent_paginate_page`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_bad_behavior` (
  `id` int(11) NOT NULL auto_increment,
  `ip` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `request_method` text NOT NULL,
  `request_uri` text NOT NULL,
  `server_protocol` text NOT NULL,
  `http_headers` text NOT NULL,
  `user_agent` text NOT NULL,
  `request_entity` text NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`(15)),
  KEY `user_agent` (`user_agent`(10))
) TYPE=MyISAM;

CREATE TABLE `phpwcms_bid` (
  `bid_id` int(11) NOT NULL auto_increment,
  `bid_cid` int(11) NOT NULL default '0',
  `bid_email` text NOT NULL,
  `bid_hash` varchar(255) NOT NULL default '',
  `bid_amount` float NOT NULL default '0',
  `bid_created` timestamp NOT NULL,
  `bid_verified` int(1) NOT NULL default '0',
  `bid_trashed` int(1) NOT NULL default '0',
  `bid_vars` mediumblob NOT NULL,
  PRIMARY KEY  (`bid_id`)
) TYPE=MyISAM;

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
) TYPE=MyISAM;

CREATE TABLE `phpwcms_cache` (
  `cache_id` int(11) NOT NULL auto_increment,
  `cache_hash` varchar(50) NOT NULL default '',
  `cache_uri` text NOT NULL,
  `cache_cid` int(11) NOT NULL default '0',
  `cache_aid` int(11) NOT NULL default '0',
  `cache_timeout` varchar(20) NOT NULL default '0',
  `cache_isprint` int(1) NOT NULL default '0',
  `cache_changed` int(14) default NULL,
  `cache_use` int(1) NOT NULL default '0',
  `cache_searchable` int(1) NOT NULL default '0',
  `cache_page` longtext NOT NULL,
  `cache_stripped` longtext NOT NULL,
  PRIMARY KEY  (`cache_id`),
  KEY `cache_hash` (`cache_hash`),
  FULLTEXT KEY `cache_stripped` (`cache_stripped`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_chat` (
  `chat_id` int(11) NOT NULL auto_increment,
  `chat_uid` int(11) NOT NULL default '0',
  `chat_name` varchar(30) NOT NULL default '',
  `chat_tstamp` timestamp NOT NULL,
  `chat_text` varchar(255) NOT NULL default '',
  `chat_cat` int(5) NOT NULL default '0',
  PRIMARY KEY  (`chat_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_country` (
  `country_id` int(4) NOT NULL auto_increment,
  `country_iso` char(2) NOT NULL default '',
  `country_name` varchar(100) NOT NULL default '',
  `country_name_de` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`country_id`),
  UNIQUE KEY `country_iso` (`country_iso`),
  UNIQUE KEY `country_name` (`country_name`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_crossreference` (
  `cref_id` int(11) NOT NULL auto_increment,
  `cref_type` int(11) NOT NULL default '0',
  `cref_rid` int(11) NOT NULL default '0',
  `cref_int` int(11) NOT NULL default '0',
  `cref_str` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cref_id`),
  KEY `cref_type` (`cref_type`,`cref_rid`,`cref_int`,`cref_str`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_file` (
  `f_id` int(11) NOT NULL auto_increment,
  `f_pid` int(11) NOT NULL default '0',
  `f_uid` int(11) NOT NULL default '0',
  `f_kid` int(2) NOT NULL default '0',
  `f_order` int(11) NOT NULL default '0',
  `f_trash` int(1) NOT NULL default '0',
  `f_aktiv` int(1) NOT NULL default '0',
  `f_public` int(1) NOT NULL default '0',
  `f_tstamp` timestamp NOT NULL,
  `f_name` varchar(255) NOT NULL default '',
  `f_cat` varchar(200) NOT NULL default '',
  `f_created` varchar(10) NOT NULL default '',
  `f_changed` longblob NOT NULL,
  `f_size` varchar(15) NOT NULL default '',
  `f_type` varchar(200) NOT NULL default '',
  `f_ext` varchar(50) NOT NULL default '',
  `f_shortinfo` varchar(255) NOT NULL default '',
  `f_longinfo` blob NOT NULL,
  `f_log` longblob NOT NULL,
  `f_thumb_list` varchar(255) NOT NULL default '',
  `f_thumb_preview` varchar(255) NOT NULL default '',
  `f_keywords` varchar(255) NOT NULL default '',
  `f_hash` varchar(50) NOT NULL default '',
  `f_dlstart` int(11) NOT NULL default '0',
  `f_dlfinal` int(11) NOT NULL default '0',
  `f_refid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`f_id`),
  FULLTEXT KEY `f_name` (`f_name`),
  FULLTEXT KEY `f_shortinfo` (`f_shortinfo`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_filecat` (
  `fcat_id` int(11) NOT NULL auto_increment,
  `fcat_name` varchar(255) NOT NULL default '',
  `fcat_aktiv` int(1) NOT NULL default '0',
  `fcat_deleted` int(1) NOT NULL default '0',
  `fcat_needed` int(1) NOT NULL default '0',
  PRIMARY KEY  (`fcat_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_filekey` (
  `fkey_id` int(11) NOT NULL auto_increment,
  `fkey_cid` int(11) NOT NULL default '0',
  `fkey_name` varchar(255) NOT NULL default '',
  `fkey_aktiv` int(1) NOT NULL default '0',
  `fkey_deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`fkey_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_fonts` (
  `font_id` int(11) NOT NULL auto_increment,
  `font_name` text NOT NULL,
  `font_shortname` text NOT NULL,
  `font_filename` text NOT NULL,
  PRIMARY KEY  (`font_id`)
) TYPE=MyISAM PACK_KEYS=0;

CREATE TABLE `phpwcms_fonts_colors` (
  `color_id` int(11) NOT NULL auto_increment,
  `color_name` text NOT NULL,
  `color_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`color_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_fonts_styles` (
  `style_id` int(11) NOT NULL auto_increment,
  `style_name` text NOT NULL,
  `style_info` text NOT NULL,
  PRIMARY KEY  (`style_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_formresult` (
  `formresult_id` int(11) NOT NULL auto_increment,
  `formresult_pid` int(11) NOT NULL default '0',
  `formresult_createdate` timestamp NULL,
  `formresult_ip` varchar(50) NOT NULL default '',
  `formresult_content` mediumblob NOT NULL,
  PRIMARY KEY  (`formresult_id`),
  KEY `formresult_pid` (`formresult_pid`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_formtracking` (
  `formtracking_id` int(11) NOT NULL auto_increment,
  `formtracking_hash` varchar(50) NOT NULL default '',
  `formtracking_ip` varchar(20) NOT NULL default '',
  `formtracking_created` timestamp NOT NULL,
  `formtracking_sentdate` varchar(20) NOT NULL default '',
  `formtracking_sent` int(1) NOT NULL default '0',
  PRIMARY KEY  (`formtracking_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_forum` (
  `forum_id` int(11) NOT NULL auto_increment,
  `forum_entry` tinyint(1) NOT NULL default '0',
  `forum_cid` int(11) NOT NULL default '0',
  `forum_pid` int(11) NOT NULL default '0',
  `forum_uid` int(11) NOT NULL default '0',
  `forum_ctopic` int(11) NOT NULL default '0',
  `forum_cpost` int(11) NOT NULL default '0',
  `forum_title` text NOT NULL,
  `forum_created` int(10) NOT NULL default '0',
  `forum_changed` timestamp NOT NULL,
  `forum_status` int(1) NOT NULL default '0',
  `forum_deleted` int(1) NOT NULL default '0',
  `forum_text` mediumtext NOT NULL,
  `forum_var` blob NOT NULL,
  `forum_lastpost` mediumtext NOT NULL,
  PRIMARY KEY  (`forum_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_guestbook` (
  `guestbook_id` int(11) NOT NULL auto_increment,
  `guestbook_cid` int(11) NOT NULL default '0',
  `guestbook_msg` text NOT NULL,
  `guestbook_name` text NOT NULL,
  `guestbook_email` text NOT NULL,
  `guestbook_created` int(11) NOT NULL default '0',
  `guestbook_trashed` int(1) NOT NULL default '0',
  `guestbook_url` text NOT NULL,
  `guestbook_show` int(1) NOT NULL default '0',
  `guestbook_ip` varchar(20) NOT NULL default '',
  `guestbook_useragent` varchar(255) NOT NULL default '',
  `guestbook_image` varchar(255) NOT NULL default '',
  `guestbook_imagename` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`guestbook_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_imgcache` (
  `imgcache_id` int(11) NOT NULL auto_increment,
  `imgcache_hash` varchar(50) NOT NULL default '',
  `imgcache_imgname` varchar(255) NOT NULL default '',
  `imgcache_width` int(11) NOT NULL default '0',
  `imgcache_height` int(11) NOT NULL default '0',
  `imgcache_wh` varchar(255) NOT NULL default '',
  `imgcache_timestamp` timestamp NOT NULL,
  `imgcache_trash` int(1) NOT NULL default '0',
  PRIMARY KEY  (`imgcache_id`),
  KEY `imgcache_hash` (`imgcache_hash`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_keyword` (
  `keyword_id` int(11) NOT NULL auto_increment,
  `keyword_name` varchar(255) NOT NULL default '',
  `keyword_created` varchar(14) NOT NULL default '',
  `keyword_trash` int(1) NOT NULL default '0',
  `keyword_updated` timestamp NOT NULL,
  `keyword_description` text NOT NULL,
  `keyword_link` varchar(255) NOT NULL default '',
  `keyword_sort` int(11) NOT NULL default '0',
  `keyword_important` int(1) NOT NULL default '0',
  `keyword_abbr` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`keyword_id`),
  KEY `keyword_abbr` (`keyword_abbr`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_language` (
  `lang_id` varchar(255) NOT NULL default '',
  `lang_html` int(1) NOT NULL default '1',
  `lang_type` int(1) NOT NULL default '0',
  `EN` text NOT NULL,
  `DE` text NOT NULL,
  `BG` text NOT NULL,
  `CA` text NOT NULL,
  `CZ` text NOT NULL,
  `DA` text NOT NULL,
  `EE` text NOT NULL,
  `ES` text NOT NULL,
  `FI` text NOT NULL,
  `FR` text NOT NULL,
  `GR` text NOT NULL,
  `HU` text NOT NULL,
  `IT` text NOT NULL,
  `LT` text NOT NULL,
  `NL` text NOT NULL,
  `NO` text NOT NULL,
  `PL` text NOT NULL,
  `PT` text NOT NULL,
  `RO` text NOT NULL,
  `SE` text NOT NULL,
  `SK` text NOT NULL,
  `VN` text NOT NULL,
  PRIMARY KEY  (`lang_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_map` (
  `map_id` int(11) NOT NULL auto_increment,
  `map_cid` int(11) NOT NULL default '0',
  `map_x` int(5) NOT NULL default '0',
  `map_y` int(5) NOT NULL default '0',
  `map_title` text NOT NULL,
  `map_zip` varchar(255) NOT NULL default '',
  `map_city` text NOT NULL,
  `map_deleted` int(1) NOT NULL default '0',
  `map_entry` text NOT NULL,
  `map_vars` text NOT NULL,
  PRIMARY KEY  (`map_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_message` (
  `msg_id` int(11) NOT NULL auto_increment,
  `msg_pid` int(11) NOT NULL default '0',
  `msg_uid` int(11) NOT NULL default '0',
  `msg_tstamp` timestamp NOT NULL,
  `msg_subject` varchar(150) NOT NULL default '',
  `msg_text` blob NOT NULL,
  `msg_deleted` tinyint(1) NOT NULL default '0',
  `msg_read` tinyint(1) NOT NULL default '0',
  `msg_to` blob NOT NULL,
  `msg_from` int(11) NOT NULL default '0',
  `msg_from_del` int(1) NOT NULL default '0',
  PRIMARY KEY  (`msg_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_newsletter` (
  `newsletter_id` int(11) NOT NULL auto_increment,
  `newsletter_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `newsletter_lastsending` timestamp NOT NULL default '0000-00-00 00:00:00',
  `newsletter_subject` text NOT NULL,
  `newsletter_changed` timestamp NOT NULL,
  `newsletter_vars` mediumblob NOT NULL,
  `newsletter_trashed` int(1) NOT NULL default '0',
  `newsletter_active` int(1) NOT NULL default '0',
  PRIMARY KEY  (`newsletter_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_newsletterqueue` (
  `queue_id` int(11) NOT NULL auto_increment,
  `queue_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `queue_changed` timestamp NOT NULL default '0000-00-00 00:00:00',
  `queue_status` int(11) NOT NULL default '0',
  `queue_pid` int(11) NOT NULL default '0',
  `queue_rid` int(11) NOT NULL default '0',
  `queue_errormsg` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`queue_id`),
  KEY `nlqueue` (`queue_pid`,`queue_status`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_pagelayout` (
  `pagelayout_id` int(11) NOT NULL auto_increment,
  `pagelayout_name` varchar(255) NOT NULL default '',
  `pagelayout_default` int(1) NOT NULL default '0',
  `pagelayout_var` mediumblob NOT NULL,
  `pagelayout_trash` int(1) NOT NULL default '0',
  PRIMARY KEY  (`pagelayout_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_profession` (
  `prof_id` int(4) NOT NULL auto_increment,
  `prof_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`prof_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_subscription` (
  `subscription_id` int(11) NOT NULL auto_increment,
  `subscription_name` text NOT NULL,
  `subscription_info` blob NOT NULL,
  `subscription_active` int(1) NOT NULL default '0',
  `subscription_lang` varchar(100) NOT NULL default '',
  `subscription_tstamp` timestamp NOT NULL,
  PRIMARY KEY  (`subscription_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_sysvalue` (
  `sysvalue_key` varchar(255) NOT NULL default '',
  `sysvalue_tstamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `sysvalue_type` varchar(255) NOT NULL default '',
  `sysvalue_value` mediumblob NOT NULL,
  `sysvalue_status` int(1) NOT NULL default '0',
  PRIMARY KEY  (`sysvalue_key`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_template` (
  `template_id` int(11) NOT NULL auto_increment,
  `template_type` int(11) NOT NULL default '1',
  `template_name` varchar(255) NOT NULL default '',
  `template_default` int(1) NOT NULL default '0',
  `template_var` mediumblob NOT NULL,
  `template_trash` int(1) NOT NULL default '0',
  PRIMARY KEY  (`template_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_user` (
  `usr_id` int(11) NOT NULL auto_increment,
  `usr_login` varchar(30) NOT NULL default '',
  `usr_pass` varchar(255) NOT NULL default '',
  `usr_email` varchar(150) NOT NULL default '',
  `usr_tstamp` timestamp NOT NULL,
  `usr_rechte` tinyint(4) NOT NULL default '0',
  `usr_admin` tinyint(1) NOT NULL default '0',
  `usr_avatar` varchar(50) NOT NULL default '',
  `usr_aktiv` int(1) NOT NULL default '0',
  `usr_name` varchar(100) NOT NULL default '',
  `usr_var_structure` blob NOT NULL,
  `usr_var_publicfile` blob NOT NULL,
  `usr_var_privatefile` blob NOT NULL,
  `usr_lang` varchar(50) NOT NULL default '',
  `usr_wysiwyg` int(2) NOT NULL default '0',
  `usr_fe` int(1) NOT NULL default '0',
  `usr_vars` mediumtext NOT NULL,
  PRIMARY KEY  (`usr_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_userdetail` (
  `detail_id` int(11) NOT NULL auto_increment,
  `detail_pid` int(11) NOT NULL default '0',
  `detail_formid` int(11) NOT NULL default '0',
  `detail_tstamp` timestamp NOT NULL,
  `detail_title` varchar(255) NOT NULL default '',
  `detail_firstname` varchar(255) NOT NULL default '',
  `detail_lastname` varchar(255) NOT NULL default '',
  `detail_company` varchar(255) NOT NULL default '',
  `detail_street` varchar(255) NOT NULL default '',
  `detail_add` varchar(255) NOT NULL default '',
  `detail_city` varchar(255) NOT NULL default '',
  `detail_zip` varchar(255) NOT NULL default '',
  `detail_region` varchar(255) NOT NULL default '',
  `detail_country` varchar(255) NOT NULL default '',
  `detail_fon` varchar(255) NOT NULL default '',
  `detail_fax` varchar(255) NOT NULL default '',
  `detail_mobile` varchar(255) NOT NULL default '',
  `detail_signature` varchar(255) NOT NULL default '',
  `detail_prof` varchar(255) NOT NULL default '',
  `detail_notes` blob NOT NULL,
  `detail_public` int(1) NOT NULL default '1',
  `detail_aktiv` int(1) NOT NULL default '1',
  `detail_newsletter` int(11) NOT NULL default '0',
  `detail_website` varchar(255) NOT NULL default '',
  `detail_userimage` varchar(255) NOT NULL default '',
  `detail_gender` varchar(255) NOT NULL default '',
  `detail_birthday` date NOT NULL default '0000-00-00',
  `detail_varchar1` varchar(255) NOT NULL default '',
  `detail_varchar2` varchar(255) NOT NULL default '',
  `detail_varchar3` varchar(255) NOT NULL default '',
  `detail_varchar4` varchar(255) NOT NULL default '',
  `detail_varchar5` varchar(255) NOT NULL default '',
  `detail_text1` text NOT NULL,
  `detail_text2` text NOT NULL,
  `detail_text3` text NOT NULL,
  `detail_text4` text NOT NULL,
  `detail_text5` text NOT NULL,
  `detail_email` varchar(255) NOT NULL default '',
  `detail_login` varchar(255) NOT NULL default '',
  `detail_password` varchar(255) NOT NULL default '',
  `userdetail_lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `detail_int1` bigint(20) NOT NULL default '0',
  `detail_int2` bigint(20) NOT NULL default '0',
  `detail_int3` bigint(20) NOT NULL default '0',
  `detail_int4` bigint(20) NOT NULL default '0',
  `detail_int5` bigint(20) NOT NULL default '0',
  `detail_float1` float NOT NULL default '0',
  `detail_float2` float NOT NULL default '0',
  `detail_float3` float NOT NULL default '0',
  `detail_float4` float NOT NULL default '0',
  `detail_float5` float NOT NULL default '0',
  PRIMARY KEY  (`detail_id`),
  UNIQUE KEY `detail_login` (`detail_login`),
  KEY `detail_pid` (`detail_pid`),
  KEY `detail_formid` (`detail_formid`),
  KEY `detail_password` (`detail_password`),
  KEY `detail_aktiv` (`detail_aktiv`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_usergroup` (
  `group_id` int(11) NOT NULL auto_increment,
  `group_name` varchar(200) NOT NULL default '',
  `group_member` mediumtext NOT NULL,
  `group_value` longblob NOT NULL,
  `group_timestamp` timestamp NOT NULL,
  `group_trash` int(1) NOT NULL default '0',
  `group_active` int(1) NOT NULL default '0',
  PRIMARY KEY  (`group_id`),
  KEY `group_member` (`group_member`(255))
) TYPE=MyISAM;

CREATE TABLE `phpwcms_userlog` (
  `userlog_id` int(11) NOT NULL auto_increment,
  `logged_user` varchar(30) NOT NULL default '',
  `logged_username` varchar(100) NOT NULL default '',
  `logged_start` int(11) NOT NULL default '0',
  `logged_change` int(11) NOT NULL default '0',
  `logged_in` int(1) NOT NULL default '0',
  `logged_ip` varchar(24) NOT NULL default '',
  `logged_section` int(1) NOT NULL default '0',
  PRIMARY KEY  (`userlog_id`)
) TYPE=MyISAM;

CREATE TABLE `phpwcms_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `log_type` varchar(255) NOT NULL,
  `log_timestamp` timestamp NOT NULL,
  `log_message` text NOT NULL,
  `log_ip` varchar(50) NOT NULL,
  `log_userid` varchar(255) NOT NULL,
  PRIMARY KEY  (`log_id`)
) TYPE=MyISAM ;
