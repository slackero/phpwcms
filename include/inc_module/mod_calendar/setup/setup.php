<?php

// do everything in here which is neccessary for module setup

// ceate neccessary db table
$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_calendar` (
  `calendar_id` int(11) NOT NULL auto_increment,
  `calendar_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_status` int(1) NOT NULL default '0',
  `calendar_start` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_end` datetime NOT NULL default '0000-00-00 00:00:00',
  `calendar_allday` int(1) NOT NULL default '0',
  `calendar_range` int(1) NOT NULL default '0',
  `calendar_range_start` date NOT NULL default '0000-00-00',
  `calendar_range_end` date NOT NULL default '0000-00-00',
  `calendar_title` varchar(255) NOT NULL default '',
  `calendar_where` varchar(255) NOT NULL default '',
  `calendar_text` mediumtext NOT NULL,
  `calendar_tag` varchar(255) NOT NULL default '',
  `calendar_object` longtext NOT NULL,
  `calendar_refid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`calendar_id`),
  KEY `calendar_status` (`calendar_status`),
  KEY `calendar_start` (`calendar_start`),
  KEY `calendar_end` (`calendar_end`),
  KEY `calendar_tag` (`calendar_tag`),
  KEY `calendar_refid` (`calendar_refid`),
  KEY `calendar_range` (`calendar_range`)
) ENGINE=MyISAM"._dbGetCreateCharsetCollation();

if(_dbQuery($sql, 'CREATE')) {

	echo '<p class="title">Calendar setup</p>';
	echo '<p>Please delete folder <b>setup</b> which can be found inside the module folder here:<br />';
	echo str_replace(PHPWCMS_ROOT, '', $phpwcms['modules'][$module]['path']).'</p>';

} else {

	echo '<p class="title">Calendar setup</p>';
	echo '<p class="error">Error creating <b>calendar</b> initial database table:</p>';
	echo '<p>'.html_entities(@mysql_error()).'</p>';

}


?>