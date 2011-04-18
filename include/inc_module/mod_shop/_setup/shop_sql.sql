-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Erstellungszeit: 03. August 2007 um 07:57
-- Server Version: 4.1.13
-- PHP-Version: 5.0.5
-- 
-- Datenbank: `usr_web2_1`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `phpwcms_categories`
-- 

CREATE TABLE IF NOT EXISTS `phpwcms_categories` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_type` varchar(255) NOT NULL default '',
  `cat_pid` int(11) NOT NULL default '0',
  `cat_status` int(1) NOT NULL default '0',
  `cat_createdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_changedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_name` varchar(255) NOT NULL default '',
  `cat_info` text NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `cat_type` (`cat_type`,`cat_status`),
  KEY `cat_pid` (`cat_pid`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `phpwcms_shop_orders`
-- 

CREATE TABLE IF NOT EXISTS `phpwcms_shop_orders` (
  `order_id` int(10) unsigned NOT NULL auto_increment,
  `order_number` varchar(20) NOT NULL default '',
  `order_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `order_name` varchar(255) NOT NULL default '',
  `order_firstname` varchar(255) NOT NULL default '',
  `order_email` varchar(255) NOT NULL default '',
  `order_net` float NOT NULL default '0',
  `order_gross` float NOT NULL default '0',
  `order_payment` varchar(255) NOT NULL default '',
  `order_data` mediumtext NOT NULL,
  `order_status` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`order_id`),
  KEY `order_number` (`order_number`,`order_status`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `phpwcms_shop_products`
-- 

CREATE TABLE IF NOT EXISTS `phpwcms_shop_products` (
  `shopprod_id` int(10) unsigned NOT NULL auto_increment,
  `shopprod_createdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `shopprod_changedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `shopprod_status` int(1) unsigned NOT NULL default '0',
  `shopprod_uid` int(10) unsigned NOT NULL default '0',
  `shopprod_ordernumber` varchar(255) NOT NULL default '',
  `shopprod_model` varchar(255) NOT NULL default '',
  `shopprod_name1` varchar(255) NOT NULL default '',
  `shopprod_name2` varchar(255) NOT NULL default '',
  `shopprod_tag` varchar(255) NOT NULL default '',
  `shopprod_vat` float unsigned NOT NULL default '0',
  `shopprod_netgross` int(1) unsigned NOT NULL default '0',
  `shopprod_price` float NOT NULL default '0',
  `shopprod_maxrebate` float NOT NULL default '0',
  `shopprod_description0` text NOT NULL,
  `shopprod_description1` text NOT NULL,
  `shopprod_description2` text NOT NULL,
  `shopprod_description3` text NOT NULL,
  `shopprod_var` mediumtext NOT NULL,
  `shopprod_category` varchar(255) NOT NULL default '',
  `shopprod_weight` float NOT NULL default '0',
  `shopprod_color` varchar(255) NOT NULL default '',
  `shopprod_size` varchar(255) NOT NULL default '',
  `shopprod_listall` int(1) unsigned default '0',
  `shopprod_special_price` text NOT NULL,
  `shopprod_track_view` int(11) NOT NULL default '0',
  `shopprod_lang` varchar(255) NOT NULL default '',
  PRIMARY KEY (`shopprod_id`),
  KEY `shopprod_status` (`shopprod_status`),
  KEY `category` (`shopprod_category`),
  KEY `tag` (`shopprod_tag`),
  KEY `all` (`shopprod_listall`),
  KEY `shopprod_track_view` (`shopprod_track_view`),
  KEY `shopprod_lang` (`shopprod_lang`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `phpwcms_sysvalue`
-- 

CREATE TABLE IF NOT EXISTS `phpwcms_sysvalue` (
  `sysvalue_key` varchar(255) NOT NULL default '',
  `sysvalue_group` varchar(255) NOT NULL default '',
  `sysvalue_lastchange` int(11) NOT NULL default '0',
  `sysvalue_status` int(1) NOT NULL default '0',
  `sysvalue_vartype` varchar(100) NOT NULL default '',
  `sysvalue_value` text NOT NULL,
  PRIMARY KEY  (`sysvalue_key`),
  KEY `sysvalue_group` (`sysvalue_group`),
  KEY `sysvalue_status` (`sysvalue_status`)
) TYPE=MyISAM;
