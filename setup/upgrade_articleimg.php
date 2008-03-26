<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

//used to convert old style file uploads

require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

?>
<html>
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