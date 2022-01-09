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
<h3>Upgrade article end date 2010-12-31 23:59:59 to 2030-12-31 23:59:59</h3>
<?php

// get all articles
if($all = _dbQuery("SELECT article_id, article_alias, article_title FROM ".DB_PREPEND."phpwcms_article WHERE article_end='2010-12-31 23:59:59' AND article_deleted=0")) {

	if(isset($all[0])) {

		$sql  = "UPDATE ".DB_PREPEND."phpwcms_article SET ";
		$sql .= "article_end='2099-12-31 23:59:59'";
		$sql .= "WHERE article_end='2010-12-31 23:59:59' AND article_deleted=0";

		$result = _dbQuery($sql, 'UPDATE');
	}

	foreach($all as $key => $value) {

			echo '<pre';
			echo '>[ID:'.sprintf('%0'.(strlen(strval(count($all)))).'s', $value['article_id']).'] '.html_specialchars($value['article_title'].' (' . $value['article_alias'].')');
			echo '</pre>'.LF;

	}

}
?>

<p><strong>Done!</strong> All articles not listed here are not touched.</p>
</body>
</html>
