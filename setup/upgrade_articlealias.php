<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

$phpwcms = array();

require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

?>
<html>
<body>
<h3>Upgrade article alias</h3>
<?php

// get all articles
if($all = _dbQuery("SELECT article_id, article_alias, article_title FROM ".DB_PREPEND."phpwcms_article")) {

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
			echo '>[ID:'.sprintf('%04s', $all[$key]['article_id']).'] '.html_specialchars($value['article_title'].' -> new: ' . $alias);
			echo '</pre>'.LF;
		}

	}

}
?>

<p><strong>Done!</strong> All articles not listed here are not touched.</p>
</body>
</html>