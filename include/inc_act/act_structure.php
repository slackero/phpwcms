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

//19-11-2004 Fernando Batista -> Copy article, Copy strutures :http://fernandobatista.web.pt
//31-03-2005 Fernando Batista -> copy&cut Article Content :http://fernandobatista.web.pt

// session_name('hashID');
session_start();
$phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if(empty($_SESSION['REFERER_URL'])) {
	die('Goood bye.');
} else {
	$ref = $_SESSION['REFERER_URL'];
}

if($_SESSION["wcs_user_admin"] == 1) { //Wenn Benutzer Admin-Rechte hat

	if(isset($_POST["acat_access"]) && is_array($_POST["acat_access"]) && count($_POST["acat_access"])) {
		$acat_permit = implode(',', $_POST["acat_access"]);
	} else {
		$acat_permit = '';
	}
	
	$acat_hidden = 0;
	if(isset($_POST["acat_hidden"])) {
		$acat_hidden	= 1;
		if(isset($_POST["acat_hiddenactive"])) {
			$acat_hidden = 2;
		}
	}
	
	$acat_cntpart = '';
	if(isset($_POST['acat_cp']) && is_array($_POST['acat_cp'])) {

		$acat_cntpart = $_POST['acat_cp'];
		$acat_cntpart = array_unique($acat_cntpart);
		/*
		foreach($acat_cntpart as $key => $value) {
		
			if(!is_numeric($value)) {
				unset($acat_cntpart[$key]);
			}
		
		}
		*/
		
		$acat_cntpart = implode(',', $acat_cntpart);
	
	}

	if(isset($_POST["acat_id"]) && $_POST["acat_id"] === 'index') {
		// write index page config into flat file
		$sql  = "<?php\n";
		$sql .= "\$indexpage['acat_name']		= '".	str_replace("''", "\\'", getpostvar($_POST["acat_name"]))."';\n";
		$sql .= "\$indexpage['acat_info']		= '".	str_replace("''", "\\'", getpostvar($_POST["acat_info"], 32000))."';\n";
		$sql .= "\$indexpage['acat_alias']	= '".		proof_alias($_POST["acat_id"], $_POST["acat_alias"])."';\n";
		$sql .= "\$indexpage['acat_aktiv']	= ".		(isset($_POST["acat_aktiv"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_public']	= ".		(isset($_POST["acat_public"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_template']	= ".	intval($_POST["acat_template"]).";\n";
	
		$sql .= "\$indexpage['acat_hidden']	= ".		$acat_hidden.";\n";
		$sql .= "\$indexpage['acat_ssl']		= ".	(isset($_POST["acat_ssl"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_regonly']	= ".	(isset($_POST["acat_regonly"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_topcount']	= ".	intval($_POST["acat_topcount"]).";\n";
		$sql .= "\$indexpage['acat_maxlist']	= ".	intval($_POST["acat_maxlist"]).";\n";
		$sql .= "\$indexpage['acat_redirect']	= '';\n";
		$cache_timeout = clean_slweg($_POST["acat_timeout"]);
		if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) $cache_timeout = 0; //check if cache = Off
		$sql .= "\$indexpage['acat_timeout']	= '".	$cache_timeout."';\n";
		$sql .= "\$indexpage['acat_nosearch']	= '".	((isset($_POST['acat_nosearch']) && intval($_POST['acat_nosearch'])) ? '1' : '')."';\n";
		$sql .= "\$indexpage['acat_nositemap']	= ".	(isset($_POST["acat_nositemap"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_order']	= ". set_correct_ordersort() .";\n";
		$sql .= "\$indexpage['acat_permit']	= '".$acat_permit."';\n";
		$sql .= "\$indexpage['acat_cntpart']	= '".$acat_cntpart."';\n";
		$sql .= "\$indexpage['acat_pagetitle']	= '".	str_replace("''", "\\'", getpostvar($_POST["acat_pagetitle"]))."';\n";
		$sql .= "\$indexpage['acat_paginate']	= ".	(isset($_POST["acat_paginate"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_overwrite']	= '".	str_replace("''", "\\'", getpostvar($_POST["acat_overwrite"]))."';\n";
		$sql .= "\$indexpage['acat_archive']	= ".	(empty($_POST["acat_archive"]) ? 0 : 1) .";\n";
		$sql .= "?>";
		write_textfile(PHPWCMS_ROOT.'/config/phpwcms/conf.indexpage.inc.php', $sql);
	}


	if(isset($_POST["acat_new"]) && intval($_POST["acat_new"]) == 1 && intval($_POST["acat_id"]) == 0 && $_POST["acat_id"] != 'index') {
		if(trim($_POST["acat_name"])) {
		
			$cache_timeout = clean_slweg($_POST["acat_timeout"]);
			if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) $cache_timeout = 0; //check if cache = Off
		
			$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_articlecat (acat_name, acat_info, acat_aktiv, acat_ssl, acat_regonly, ".
			"acat_public, acat_struct, acat_template, acat_sort, acat_uid, acat_alias, acat_hidden, acat_topcount, ".
			"acat_redirect, acat_order, acat_cache, acat_nosearch, acat_nositemap, acat_permit, acat_maxlist, ".
			"acat_cntpart, acat_pagetitle, acat_paginate, acat_overwrite, acat_archive) VALUES ('".
			getpostvar($_POST["acat_name"])."','".
			getpostvar($_POST["acat_info"], 32000)."',".
			(isset($_POST["acat_aktiv"]) ? 1 : 0).",".
			(isset($_POST["acat_ssl"]) ? 1 : 0).",".
			(isset($_POST["acat_regonly"]) ? 1 : 0).",".
			(isset($_POST["acat_public"]) ? 1 : 0).",".
			intval($_POST["acat_struct"]).",".
			intval($_POST["acat_template"]).",".
			intval($_POST["acat_sort"]).",".
			$_SESSION["wcs_user_id"].",'".
			proof_alias($_POST["acat_id"], $_POST["acat_alias"])."',".
			$acat_hidden.", ".
			intval($_POST["acat_topcount"]).",'".
			getpostvar($_POST["acat_redirect"])."', ".
			set_correct_ordersort().",'".
			$cache_timeout."', '".(isset($_POST['acat_nosearch']) ? 1 : '')."',".
			(isset($_POST["acat_nositemap"]) ? 1 : 0).",".
			"'".$acat_permit."', ".intval($_POST["acat_maxlist"]).", '".aporeplace($acat_cntpart)."','".
			getpostvar($_POST["acat_pagetitle"])."', ".(isset($_POST["acat_paginate"]) ? 1 : 0).", '".getpostvar($_POST["acat_overwrite"])."',".
			(empty($_POST["acat_archive"]) ? 0 : 1).")";
			if($result = mysql_query($sql, $db) or die("error")) {
				$ref .= "&cat=".mysql_insert_id($db);
			}
		}
	}

	if(isset($_POST["acat_new"]) && isset($_POST["acat_id"]) && intval($_POST["acat_new"]) == 0 && intval($_POST["acat_id"])) {
		if(trim($_POST["acat_name"])) {
		
			$cache_timeout = clean_slweg($_POST["acat_timeout"]);
			if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) $cache_timeout = 0; //check if cache = Off
		
			$sql =	"UPDATE ".DB_PREPEND."phpwcms_articlecat SET ".
			"acat_name='".getpostvar($_POST["acat_name"])."', ".
			"acat_info='".getpostvar($_POST["acat_info"], 32000)."', ".
			"acat_alias='".aporeplace(proof_alias($_POST["acat_id"], $_POST["acat_alias"]))."', ".
			"acat_aktiv=".(isset($_POST["acat_aktiv"]) ? 1 : 0).", ".
			"acat_public=".(isset($_POST["acat_public"]) ? 1 : 0).", ".
			"acat_struct=".intval($_POST["acat_struct"]).", ".
			"acat_template=".intval($_POST["acat_template"]).", ".
			"acat_sort=".intval($_POST["acat_sort"]).", ".
			"acat_uid=".$_SESSION["wcs_user_id"].", ".
			"acat_hidden=".$acat_hidden.", ".
			"acat_ssl=".(isset($_POST["acat_ssl"]) ? 1 : 0).", ".
			"acat_regonly=".(isset($_POST["acat_regonly"]) ? 1 : 0).", ".
			"acat_topcount=".intval($_POST["acat_topcount"]).", ".
			"acat_redirect='".getpostvar($_POST["acat_redirect"])."',".
			"acat_order=".set_correct_ordersort().", ".
			"acat_cache='".aporeplace($cache_timeout)."', ".
			"acat_nosearch='".(isset($_POST['acat_nosearch']) ? 1 : '')."', ".
			"acat_nositemap=".(isset($_POST["acat_nositemap"]) ? 1 : 0).", ".
			"acat_permit='".aporeplace($acat_permit)."', ".
			"acat_maxlist=".intval($_POST["acat_maxlist"]).", ".
			"acat_cntpart='".aporeplace($acat_cntpart)."', ".
			"acat_pagetitle='".getpostvar($_POST["acat_pagetitle"])."', ".
			"acat_paginate=".(isset($_POST["acat_paginate"]) ? 1 : 0).", ".
			"acat_overwrite='".getpostvar($_POST["acat_overwrite"])."', ".
			"acat_archive=".(empty($_POST["acat_archive"]) ? 0 : 1).
			" WHERE acat_id=".intval($_POST["acat_id"]);
		
			mysql_query($sql, $db) or die(_report_error('DB', $sql));
		}
	}

//diverse Aktionen
$do = explode("|", isset($_GET["do"]) ? $_GET["do"] : '');


switch(intval($do[0])) {

	case 1:	//Einfügen in
	$do[1] = intval($do[1]); //cut ID
	$do[2] = intval($do[2]); //paste ID
	$do[3] = intval($do[3]); //sort Number
	if($do[1]) { // && $do[2] = 0 für Root
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_struct=".$do[2].", acat_sort=".$do[3]." WHERE acat_id=".$do[1];
	mysql_query($sql, $db) or die("error while updating structure level");
	}
	break;

	case 2:	//Sortierung ändern
	$do[1] = intval($do[1]); //sort ID1
	$do[2] = intval($do[2]); //sort NR1
	$do[3] = intval($do[3]); //sort ID2
	$do[4] = intval($do[4]); //sort NR2
	if($do[1] && $do[2]>=10 && $do[3] && $do[4]>=10) {
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_sort=".$do[2]." WHERE acat_id=".$do[1];
		mysql_query($sql, $db) or die("error while updating sorting ID1");
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_sort=".$do[4]." WHERE acat_id=".$do[3];
		mysql_query($sql, $db) or die("error while updating sorting ID1");
	}
	break;

	case 3:	//Artikel einfügen in
	$do[1] = intval($do[1]); //cut Article ID
	$do[2] = intval($do[2]); //paste level ID
	if($do[1]) { // && $do[2] = 0 für Root
		$new_sort = getArticleSortValue($do[2]);
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_article SET article_cid=".$do[2].", article_sort=".$new_sort." WHERE article_id=".$do[1];
		mysql_query($sql, $db) or die("error while updating article level");
	}
	break;

	case 4:	//Sortierung Artikel ändern
	$do[1] = intval($do[1]); //article sort ID1
	$do[2] = intval($do[2]); //article sort NR1
	$do[3] = intval($do[3]); //article sort ID2
	$do[4] = intval($do[4]); //article sort NR2
	if($do[1] && $do[2]>=10 && $do[3] && $do[4]>=10) {
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_article SET article_sort=".$do[2].", article_tstamp=article_tstamp WHERE article_id=".$do[1];
		mysql_query($sql, $db) or die("error while updating sorting article ID1");
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_article SET article_sort=".$do[4].", article_tstamp=article_tstamp WHERE article_id=".$do[3];
		mysql_query($sql, $db) or die("error while updating sorting article ID1");
	}
	break;

//19-11-2004  Fernando Batista start-----------------------------------------------------------------------------------------------------------
	case 5:	// COPY Article
	$do[1] = intval($do[1]); //copy Article ID
	$do[2] = intval($do[2]); //paste level ID
	$do[3] = isset($do[3]) && $do[3] == 'open' ? 'open' : 0; // special link to copy an existing article and open the new
	if($do[1]) { //also allowed for pasting in root structure
		copy_article_to_level($do, $db);
	}
	break;

	case 6:	//COPY Structure
	$do[1] = intval($do[1]); //copy level ID
	$do[2] = intval($do[2]); //paste level ID
	$do[3] = intval($do[3]); //sort Number
	if($do[1]) { // && $do[2] = 0 für Root
		copy_level_to_level($do, $db);
	}
	break;
//19-11-2004  Fernando Batista  end-------------------

//31-03-2005 Fernando Batista start-----------------------------------------------------------------------------------------------------------
	case 7:	//
	$do[1] = intval($do[1]); //cut Article Content ID
	$do[2] = intval($do[2]); //paste Article ID
	$do[3] = intval($do[3]); //sort Number
	if($do[1]) { 
		
	$sql = "SELECT acontent_aid, acontent_sorting FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$do[1];	
	$result = mysql_query($sql, $db) or die("error while updating Article Content");
	$row = mysql_fetch_assoc($result);

	$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=acontent_sorting-10 WHERE acontent_aid=".$row['acontent_aid']." AND acontent_sorting >= ".$row['acontent_sorting']."+10";
	mysql_query($sql, $db) or die("error while updating Article Content");	
		
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=acontent_sorting+10 WHERE acontent_aid=".$do[2]." AND acontent_sorting >= ".$do[3]."+10";
	mysql_query($sql, $db) or die("error while updating Article Content");	
			
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_aid=".$do[2].", acontent_sorting=".$do[3]."+10 WHERE acontent_id=".$do[1];
	mysql_query($sql, $db) or die("error while updating Article Content");

	}
	break;	
//-------------
	case 8:	//
	$do[1] = intval($do[1]); //copy Article Content ID
	$do[2] = intval($do[2]); //paste Article ID
	$do[3] = intval($do[3]); //sort Number
	if($do[1]) { 
			
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=acontent_sorting+10 WHERE acontent_aid=".$do[2]." AND acontent_sorting >= ".$do[3]."+10";
	mysql_query($sql, $db) or die("error while updating Article Content");	

	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$do[1];
			if($result = mysql_query($sql, $db) or die("error sql")) {
				  $row = mysql_fetch_assoc($result); 
					foreach($row as $key => $value) {
						if($key == "acontent_created") {
							$key1s   .= ", ".$key;
							$value1s .= ", NOW()";
						}elseif($key == "acontent_id" ){
							$key1s   = $key;
							$value1s = "''";
						}elseif($key == "acontent_aid" ){
							$key1s   .= ", ".$key;
							$value1s .= ", '".$do[2]."'";								
						}elseif($key == "acontent_sorting" ){
							$key1s   .= ", ".$key;
							$do[3] = $do[3] + 10;
							$value1s .= ", '".$do[3]."'";								
						}else{
							$key1s   .= ", ".$key;
							$value1s .= ", '".aporeplace($value)."'";
						}
					}
					$sql2 =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecontent (".$key1s.") VALUES (".$value1s.")" ;
					$result = mysql_query($sql2, $db) or die("error while copy article content <br>error while connecting to database: <br><pre>".$sql2."</pre>");
				
			}
	

	}
	break;		
//31-03-2005 Fernando Batista  end-------------------
	

	case 9: //Löschen des Levels
	$do[1] = intval($do[1]); //delete ID
	if($do[1]) {
		// extend deleting of structure levels also for deleting of all related child
		// structure levels and articles on structure level
		// this is necessary to be sure that such deleted articles/structures are
		// not available anymore

		// 1.) get all structure level IDs and put into an array
		$struct_del = array();
		$article_del = array();

		$struct_del[] = $do[1]; //start

		get_struct_del_id($do[1], $db);

		// create SQL query to set articles deleted
		if(count($article_del)) {

			$a_del = "";
			foreach($article_del as $value) {
				//delete cached articles
				$sql = "DELETE FROM ".DB_PREPEND."phpwcms_cache WHERE cache_aid=".intval($value);
				mysql_query($sql, $db) or die("error while deleting cached article ID:".$value);
				
				$a_del .= ($a_del) ? " OR article_id=".$value : "article_id=".$value;
			}

			if($a_del) {
				$sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_deleted=9 WHERE (".$a_del.")";
				mysql_query($sql, $db) or die("error while deleting articles while deleting structures");
			}
		}

		// create SQL query to set structure levels deleted
		if(count($struct_del)) {

			$s_del = "";
			foreach($struct_del as $value) {
				//delete cached categories
				$sql = "DELETE FROM ".DB_PREPEND."phpwcms_cache WHERE cache_cid=".intval($value);
				mysql_query($sql, $db) or die("error while deleting cached category ID:".$value);
			
				$s_del .= ($s_del) ? " OR acat_id=".$value : "acat_id=".$value;
			}

			if($s_del) {
				$sql = "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_trash=9, acat_sort=9999999 WHERE (".$s_del.")";
				mysql_query($sql, $db) or die("error while deleting structures");
			}
		}

	}
	break;

}

} //Ende Abarbeiten Aktion

//first delete cache
//$sql = "UPDATE ".DB_PREPEND."phpwcms_cache SET cache_timeout='0';";
//mysql_query($sql, $db) or die("error while deleting cache");
update_cache();

if(isset($_POST['SubmitClose'])) {
	headerRedirect(PHPWCMS_URL.'phpwcms.php?do=admin&p=6');
} else {
	headerRedirect($ref);
}

function get_struct_del_id($s_id=0, $dbcon) {

	$s_id = intval($s_id);

	//retrieve article ID list that should be deleted
	$sql = "SELECT article_id FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_cid=".$s_id;
	if($result = mysql_query($sql, $dbcon)) {
		while($row = mysql_fetch_row($result)) {
			$GLOBALS["article_del"][] = $row[0];
		}
		mysql_free_result($result);
	}

	// retrieve structure ID list that should be deleted
	$sql = "SELECT acat_id FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$s_id;
	if($result = mysql_query($sql, $dbcon)) {
		while($row = mysql_fetch_row($result)) {
			$GLOBALS["struct_del"][] = $row[0];
			get_struct_del_id($row[0], $dbcon);
		}
		mysql_free_result($result);
	}
}

//19-11-2004  Fernando Batista start-----------------------------------------------------------------------------------------------------------
function copy_article_to_level($do, $dbcon) {

	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_id=".$do[1];
	if($result = mysql_query($sql, $dbcon) or die("error while connecting to database: <br><pre>".$sql."</pre>")) {

		if($row = mysql_fetch_assoc($result)) {
			$row["article_cid"] 	= $do[2];
			$row["article_created"]	= now();
			$row["article_tstamp"]	= date('Y-m-d H:i:s', now() );
			$row["article_sort"]	= getArticleSortValue($row["article_cid"]);
			$row["article_alias"]	= proof_alias(0, empty($row["article_alias"]) ? $row['article_title'] : $row["article_alias"], 'ARTICLE');

			foreach($row as $key => $value) {
				if($key == "article_id" ){
					$keys   = $key;
					$values = "''";
				} else {
					$keys   .= ", ".$key;
					$values .= ", '".aporeplace($value)."'";
				}
			}
		}
		mysql_free_result($result);

		$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_article (".$keys.") VALUES (".$values.")" ;

		if($result = mysql_query($sql, $dbcon) or die("error while copy article <br>error while connecting to database: <br><pre>".$sql."</pre>")) {

			$article_insert_id = mysql_insert_id($dbcon);

			$sql1  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_aid=".$do[1];
			if($result1 = mysql_query($sql1, $dbcon) or die("error sql")) {
				while ($row1 = mysql_fetch_assoc($result1)) {
					$row1["acontent_aid"] = $article_insert_id;
					foreach($row1 as $key1 => $value1) {
						if($key1 == "acontent_id" ){
							$key1s   = $key1;
							$value1s = "''";
						} else {
							$key1s   .= ", ".$key1;
							$value1s .= ", '".aporeplace($value1)."'";
						}
					}
					$sql2 =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecontent (".$key1s.") VALUES (".$value1s.")" ;
					$result = mysql_query($sql2, $dbcon) or die("error while copy article content <br>error while connecting to database: <br><pre>".$sql2."</pre>");
				}
				mysql_free_result($result1);
			}
			
			if($do[3] == 'open' && $article_insert_id) {
			
				headerRedirect(PHPWCMS_URL.'phpwcms.php?do=articles&p=2&s=1&id='.$article_insert_id);
			
			} 

		}

	}
}

function copy_level_to_level($do, $dbcon) {
	// $do[1] -- copy level
	// $do[2] -- paste level
	// $do[3] -- sort Number

	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_id=".$do[1];
	if($result = mysql_query($sql, $dbcon) or die("error while connecting to database: <br><pre>".$sql."</pre>")) {
		if($row = mysql_fetch_assoc($result)) {
			$row["acat_struct"] = $do[2];
			$row["acat_sort"]   = $do[3];
			$row["acat_alias"]  = proof_alias(0, empty($row["acat_alias"]) ? $row['acat_name'] : $row["acat_alias"], 'CATEGORY');;

			foreach($row as $key => $value) {
				if($key == "acat_id" ) {
					$keys   = $key;
					$values = "''";
				} else {
					$keys   .= ", ".$key;
					$values .= ", '".aporeplace($value)."'";
				}
			}
		}
		mysql_free_result($result);

		$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecat (".$keys.") VALUES (".$values.")" ;
		mysql_query($sql, $dbcon);
		$acat_insert_id = mysql_insert_id($dbcon);
	}


	$sql = "SELECT article_id FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_cid=".$do[1];
	if($result = mysql_query($sql, $dbcon)) {
		while($row = mysql_fetch_row($result)) {
			$do_article[1] = $row[0];
			$do_article[2] = $acat_insert_id;  
			copy_article_to_level($do_article, $dbcon);
		}
		mysql_free_result($result);
	}


	$sql = "SELECT acat_id,acat_sort FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$do[1];
	if($result = mysql_query($sql, $dbcon)) {
		while($row = mysql_fetch_row($result)) {
			$do_struct[1] = $row[0];
			$do_struct[2] = $acat_insert_id ;  
			$do_struct[3] = $row[1];
			copy_level_to_level($do_struct, $dbcon);
		}
		mysql_free_result($result);
	}
}
//19-11-2004  Fernando Batista end-------------------

function set_correct_ordersort() {
	// 0 = manual, 2 = creation date, 4 = start date -> + 0 = ASC, + 1 = DESC
	$val = 0;
	
	//if(intval($_POST["acat_order"]) == 0) {
		// manual can never be sorted DESC
	//	$val = 0;
	//} else {
	
	// but why not - should be possible too based on new sorting
	$val = intval($_POST["acat_order"]) + intval($_POST["acat_ordersort"]);
	//}
	return $val;
}

?>