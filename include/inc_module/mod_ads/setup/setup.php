<?php

// do everything in here which is neccessary for module setup

// ceate neccessary db table
$sql = array();

$sql[0] = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_ads_formats` (
		`adformat_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`adformat_created` DATETIME NOT NULL ,
		`adformat_changed` DATETIME NOT NULL ,
		`adformat_status` INT( 1 ) NOT NULL ,
		`adformat_title` VARCHAR( 25 ) NOT NULL ,
		`adformat_width` INT( 5 ) NOT NULL ,
		`adformat_height` INT( 5 ) NOT NULL ,
		`adformat_comment` TEXT NOT NULL ,
		INDEX ( `adformat_status` )
		) TYPE=MyISAM"._dbGetCreateCharsetCollation();

$sql[1] = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_ads_campaign` (
		`adcampaign_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`adcampaign_created` DATETIME NOT NULL ,
		`adcampaign_changed` DATETIME NOT NULL ,
		`adcampaign_status` INT( 1 ) NOT NULL ,
		`adcampaign_title` VARCHAR( 255 ) NOT NULL ,
		`adcampaign_comment` TEXT NOT NULL ,
		`adcampaign_datestart` DATETIME NOT NULL ,
		`adcampaign_dateend` DATETIME NOT NULL ,
		`adcampaign_maxview` INT NOT NULL,
		`adcampaign_maxclick` INT NOT NULL,
		`adcampaign_maxviewuser` INT NOT NULL,
		`adcampaign_curview` INT NOT NULL,
		`adcampaign_curclick` INT NOT NULL,
		`adcampaign_curviewuser` INT NOT NULL,
		`adcampaign_type` INT NOT NULL ,
		`adcampaign_place` INT NOT NULL ,
		`adcampaign_data` MEDIUMTEXT NOT NULL ,
		INDEX ( `adcampaign_status` , `adcampaign_datestart` , `adcampaign_dateend` , `adcampaign_type`, `adcampaign_place`, `adcampaign_maxview`, `adcampaign_maxclick`, `adcampaign_maxviewuser`, `adcampaign_curview`, `adcampaign_curclick`, `adcampaign_curviewuser` )
		) TYPE=MyISAM"._dbGetCreateCharsetCollation();
		
$sql[2] = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_ads_tracking` (
		`adtracking_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`adtracking_created` DATETIME NOT NULL ,
		`adtracking_campaignid` INT NOT NULL ,
		`adtracking_ip` VARCHAR( 30 ) NOT NULL ,
		`adtracking_cookieid` varchar(50) NOT NULL ,
		`adtracking_countclick` INT NOT NULL ,
		`adtracking_countview` INT NOT NULL ,
		`adtracking_useragent` VARCHAR( 255 ) NOT NULL ,
		`adtracking_ref` TEXT NOT NULL ,
		`adtracking_catid` INT NOT NULL ,
		`adtracking_articleid` INT NOT NULL ,
		INDEX ( `adtracking_campaignid` , `adtracking_ip` , `adtracking_cookieid` , `adtracking_countclick` , `adtracking_countview` )
		) TYPE=MyISAM"._dbGetCreateCharsetCollation();
		
$sql[3] = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_ads_place` (
		`adplace_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`adplace_created` DATETIME NOT NULL ,
		`adplace_changed` DATETIME NOT NULL ,
		`adplace_status` INT( 1 ) NOT NULL ,
		`adplace_title` VARCHAR( 255 ) NOT NULL ,
		`adplace_format` INT NOT NULL ,
		`adplace_width` INT NOT NULL ,
		`adplace_height` INT NOT NULL ,
		`adplace_prefix` VARCHAR( 255 ) NOT NULL ,
		`adplace_suffix` VARCHAR( 255 ) NOT NULL ,
		INDEX ( `adplace_status` )
		) TYPE=MyISAM"._dbGetCreateCharsetCollation();
		
$sql[4] = "INSERT INTO `".DB_PREPEND."phpwcms_ads_formats` VALUES 
		(1, NOW(), NOW(), 1, 'Leaderboard', 728, 90, ''),
		(2, NOW(), NOW(), 1, 'Banner', 468, 60, ''),
		(3, NOW(), NOW(), 1, 'Small Square', 200, 200, ''),
		(4, NOW(), NOW(), 1, 'Square', 250, 250, ''),
		(5, NOW(), NOW(), 1, 'Medium Rectangle', 300, 250, ''),
		(6, NOW(), NOW(), 1, 'Large Rectangle', 336, 280, ''),
		(7, NOW(), NOW(), 1, 'Skyscraper', 120, 600, ''),
		(8, NOW(), NOW(), 1, 'Wide Skyscraper', 160, 600, ''),
		(10, NOW(), NOW(), 1, 'Half Banner', 234, 60, ''),
		(11, NOW(), NOW(), 1, 'Square Button', 125, 125, ''),
		(12, NOW(), NOW(), 1, 'Small Rectangle', 180, 150, ''),
		(13, NOW(), NOW(), 1, 'Vertical Banner', 120, 240, ''),
		(14, NOW(), NOW(), 1, 'Mini Square', 120, 120, ''),
		(15, NOW(), NOW(), 1, 'Medium Scyscraper', 120, 450, ''),
		(16, NOW(), NOW(), 1, 'Micro Bar', 88, 31, ''),
		(17, NOW(), NOW(), 1, 'Vertical Rectangle', 240, 400, ''),
		(18, NOW(), NOW(), 1, 'Vertical Button', 120, 90, ''),
		(19, NOW(), NOW(), 1, 'Half Mini Square', 120, 60, ''),
		(20, NOW(), NOW(), 1, 'Half Page Ad', 300, 600, ''),
		(21, NOW(), NOW(), 1, 'Universal Flash Layer', 400, 400, ''),
		(22, NOW(), NOW(), 1, 'PopUp', 250, 300, ''),
		(23, NOW(), NOW(), 1, 'Target Button', 120, 150, '')";


// setup inital ad formats
$sql_error = array();

// first create db tables
if(!_dbQuery($sql[0], 'CREATE')) {

	$sql_error[0] = '<p class="error">Error creating <b>banner ads formats</b> initial database table: '.html_entities(@mysql_error()).'</p>';

}
if(!_dbQuery($sql[1], 'CREATE')) {

	$sql_error[1] = '<p class="error">Error creating <b>banner ads campaign</b> initial database table: '.html_entities(@mysql_error()).'</p>';

}
if(!_dbQuery($sql[2], 'CREATE')) {

	$sql_error[2] = '<p class="error">Error creating <b>banner ads tracking</b> initial database table: '.html_entities(@mysql_error()).'</p>';

}
if(!_dbQuery($sql[3], 'CREATE')) {

	$sql_error[3] = '<p class="error">Error creating <b>banner ad place</b> initial database table: '.html_entities(@mysql_error()).'</p>';

}

// insert default settings
if(!isset($sql_error[0]) && !_dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_ads_formats", 'COUNT')) {

	@_dbQuery($sql[4], 'INSERT');
	if(@mysql_error()) {
		$sql_error[4] = '<p class="error">Error inserting default <b>banner ads formats</b> entries: '.html_entities(@mysql_error()).'</p>';	
	}
}

if(!is_dir(PHPWCMS_CONTENT.'ads')) {
	// try to create the ads directory
	if(!@is_writable(PHPWCMS_CONTENT)) {
		@chmod(PHPWCMS_CONTENT, 0777);
	}
	umask(0);
	if(!@mkdir(PHPWCMS_CONTENT.'ads', 0777)) {
		$sql_error[5]  = '<p class="error">The necessary folder &quot;ads&quot; does not exist.<br />';
		$sql_error[5] .= 'Script was not able to create it here &quot;<strong>'.PHPWCMS_BASEPATH.$phpwcms["content_path"].'</strong>ads&quot; inside of web root directory.</p>';
		$sql_error[5] .= '<p class="error"><strong>Check permissions or create missing folder &quot;ads&quot; using FTP client.<br />Set permissions to 777.</strong></p>';
	} else {	
		// thanks wordpress ;-)
		$stat = @stat(dirname(PHPWCMS_CONTENT.'ads'));
		@chmod(PHPWCMS_CONTENT.'ads', $stat['mode'] & 0007777);
	}
}
if(is_dir(PHPWCMS_CONTENT.'ads') && !file_exists(PHPWCMS_CONTENT.'ads/adtracking.php')) {
	if(!copy($phpwcms['modules'][$module]['path'].'setup/adtracking.php', PHPWCMS_CONTENT.'ads/adtracking.php')) {
		$sql_error[6]  = '<p class="error">Adtracking file &quot;adtracking.php&quot; could not be written to ';
		$sql_error[6] .= '<strong>&quot;'.PHPWCMS_BASEPATH.$phpwcms["content_path"].'ads&quot;</strong>.</p>';
	}
}


		
echo '<p class="title">Banner ads setup</p>';

if(!count($sql_error)) {

	echo '<p>All inital db tables and values were created.</p>';
	echo '<p>Please delete folder <b>setup</b> which can be found inside the module folder here:<br />';
	echo str_replace(PHPWCMS_ROOT, '', $phpwcms['modules'][$module]['path']).'</p>';
	if(!is_writable(PHPWCMS_CONTENT.'ads')) {
		echo '<p class="error">Before you continue please check permissions of <strong>&quot;'.PHPWCMS_BASEPATH.$phpwcms["content_path"].'ads&quot;</strong>. It has to b set to chmod 777.</p>';
	}
	
} else {

	echo implode(LF, $sql_error);

}


?>