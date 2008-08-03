<?php

// do everything in here which is neccessary for module setup

// ceate neccessary db table
$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_glossary` (
  `glossary_id` int(11) NOT NULL auto_increment,
  `glossary_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `glossary_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `glossary_title` text NOT NULL,
  `glossary_tag` varchar(255) NOT NULL default '',
  `glossary_keyword` varchar(255) NOT NULL default '',
  `glossary_text` mediumtext NOT NULL,
  `glossary_highlight` int(1) NOT NULL default '0',
  `glossary_object` mediumtext NOT NULL,
  `glossary_status` int(1) NOT NULL default '0',
  PRIMARY KEY  (`glossary_id`),
  KEY `glossary_status` (`glossary_status`),
  KEY `glossary_tag` (`glossary_tag`),
  KEY `glossary_keyword` (`glossary_keyword`),
  KEY `glossary_highlight` (`glossary_highlight`)
) TYPE=MyISAM"._dbGetCreateCharsetCollation();

if(_dbQuery($sql, 'CREATE')) {

	echo '<p class="title">Glossary setup</p>';
	echo '<p>Please delete folder <b>setup</b> which can be found inside the module folder here:<br />';
	echo str_replace(PHPWCMS_ROOT, '', $phpwcms['modules'][$module]['path']).'</p>';

} else {

	echo '<p class="title">Glossary setup</p>';
	echo '<p class="error">Error creating <b>glossary</b> initial database table:</p>';
	echo '<p>'.html_entities(@mysql_error()).'</p>';

}


?>