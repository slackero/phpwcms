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
<h3>Upgrade article summary image for article</h3>
<?php

// get all articles
if($all = _dbQuery("SELECT article_id, article_image, article_title, article_tstamp FROM ".DB_PREPEND."phpwcms_article")) {

	foreach($all as $key => $value) {

		$all[$key]['article_image']	= unserialize($all[$key]['article_image']);
		if(isset($all[$key]['article_image']['prev']) && !empty($all[$key]['article_image']['id'])) {

			//dumpVar($all[$key]);

			unset($all[$key]['article_image']['prev']);
			unset($all[$key]['article_image']['prev_info']);
			unset($all[$key]['article_image']['prev_make']);
			unset($all[$key]['article_image']['add']);
			unset($all[$key]['article_image']['cname']);

			$all[$key]['article_image']['id'] = intval($all[$key]['article_image']['id']);

			// retrieve image information
			$file = _dbQuery("SELECT f_id, f_hash, f_ext FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$all[$key]['article_image']['id']." LIMIT 1");

			if(!empty($file[0]['f_id']) && $file[0]['f_id'] == $all[$key]['article_image']['id']) {
				$all[$key]['article_image']['hash']				= $file[0]['f_hash'];
				$all[$key]['article_image']['ext']				= $file[0]['f_ext'];
				$all[$key]['article_image']['list_usesummary']	= 1;

				$sql  = "UPDATE ".DB_PREPEND."phpwcms_article SET ";
				$sql .= "article_image = '".aporeplace(serialize($all[$key]['article_image']))."',";
				$sql .= "article_tstamp = '".$all[$key]['article_tstamp']."' WHERE article_id = ".$all[$key]['article_id']." LIMIT 1";

				$result = _dbQuery($sql, 'UPDATE');

				echo '<pre';
				if($result === false) {
					echo ' style="color:#CC3300"';
				}
				echo '>[ID:'.sprintf('%04s', $all[$key]['article_id']).'] '.html_specialchars($all[$key]['article_title']);
				echo '</pre>'.LF;

			}

		} else {

			unset($all[$key]);

		}

	}

}
?>

<p><strong>Done!</strong> All articles not listed here are not touched.</p>
</body>
</html>