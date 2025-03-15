<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 532 Update Check
function cmsgo_revision_r532() {

	$status = true;

	// do former revision check – fallback to r529
	if(cmsgo_revision_check_temp('529') !== true) {
		$status = cmsgo_revision_check('529');
	}

	$result = _dbQuery('SHOW TABLES LIKE '._dbEscape(DB_PREPEND.'cmsgo_redirect'));

	if(!isset($result[0])) {

		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREPEND."cmsgo_redirect` (
					`rid` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`changed` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
				)";
		if(!empty($GLOBALS['cmsgo']['db_charset'])) {
			$sql .= ' DEFAULT CHARSET='.$GLOBALS['cmsgo']['db_charset'];
		}
		if(!empty($GLOBALS['cmsgo']['db_collation'])) {
			$sql .= ' COLLATE='.$GLOBALS['cmsgo']['db_collation'];
		}

		$result = _dbQuery($sql, 'CREATE');
		if(!$result) {
			$status = false;
		}
	}

	return $status;
}
