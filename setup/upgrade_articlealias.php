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

//used to convert old style file uploads

$phpwcms = array();

require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

?><html>
<body>
<h3>Upgrade article alias</h3>
<?php

// get all articles
if($all = _dbQuery("SELECT article_id, article_alias, article_title FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted != 9")) {

	foreach($all as $key => $value) {

		$alias = empty($value['article_alias']) ? $value['article_title'] : $value['article_alias'];
		$alias = proof_alias($value['article_id'], $alias, 'ARTICLE');

		if($alias != $value['article_alias']) {

			$sql  = "UPDATE ".DB_PREPEND."phpwcms_article SET ";
			$sql .= "article_alias = '".aporeplace($alias)."' ";
			$sql .= "WHERE article_id = ".$value['article_id']." LIMIT 1";

			$result = _dbQuery($sql, 'UPDATE');

			echo '<pre';
			if($result === false) {
				echo ' style="color:#CC3300"';
			}
			echo '>[ID:'.sprintf('%04s', $all[$key]['article_id']).'] ';
			if(!empty($value['article_alias'])) {
				echo ' OLD ALIAS: '.html_specialchars($value['article_alias']).' ';
			}
			echo html_specialchars('-> NEW ALIAS: ' . $alias . ', TITLE: ' . $value['article_title']);
			echo '</pre>'.LF;
		}

	}

}
?>
<p><strong>Done!</strong> All articles not listed here are not touched.</p>
</body>
</html>