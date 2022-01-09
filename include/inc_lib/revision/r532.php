<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// Revision 532 Update Check
function phpwcms_revision_r532() {

	$status = true;

	// do former revision check – fallback to r529
	if(phpwcms_revision_check_temp('529') !== true) {
		$status = phpwcms_revision_check('529');
	}

	$result = _dbQuery('SHOW TABLES LIKE '._dbEscape(DB_PREPEND.'phpwcms_redirect'));

	if(!isset($result[0])) {

		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."phpwcms_redirect` (
					`rid` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`id` bigint(20) unsigned NOT NULL DEFAULT '0',
					`aid` bigint(20) unsigned NOT NULL DEFAULT '0',
					`alias` varchar(255) NOT NULL DEFAULT '',
					`link` varchar(255) NOT NULL DEFAULT '',
					`views` bigint(20) unsigned NOT NULL DEFAULT '0',
					`active` int(1) unsigned NOT NULL DEFAULT '0',
					`shortcut` int(1) unsigned NOT NULL DEFAULT '0',
					`type` varchar(255) NOT NULL DEFAULT '',
					`code` varchar(255) NOT NULL DEFAULT '',
					`target` varchar(255) NOT NULL DEFAULT '',
					PRIMARY KEY (`rid`),
					KEY `id` (`id`,`aid`,`alias`),
					KEY `active` (`active`),
					KEY `link` (`link`)
				) ENGINE=MyISAM";
		if(!empty($GLOBALS['phpwcms']['db_charset'])) {
			$sql .= ' DEFAULT CHARSET='.$GLOBALS['phpwcms']['db_charset'];
		}
		if(!empty($GLOBALS['phpwcms']['db_collation'])) {
			$sql .= ' COLLATE='.$GLOBALS['phpwcms']['db_collation'];
		}

		$result = _dbQuery($sql, 'CREATE');
		if(!$result) {
			$status = false;
		}
	}

	return $status;
}
