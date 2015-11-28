<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

//19-11-2004 Fernando Batista -> Copy article, Copy strutures http://fernandobatista.net
//31-03-2005 Fernando Batista -> Copy/Cut Article Content http://fernandobatista.net

session_start();
$phpwcms = array();
require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION['REFERER_URL'])) {
	die('Goood bye.');
} else {
	$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string('csrftoken') : $_SESSION['REFERER_URL'];
}

if($_SESSION["wcs_user_admin"] == 1) { //Wenn Benutzer Admin-Rechte hat

	if(isset($_POST["acat_access"]) && is_array($_POST["acat_access"]) && count($_POST["acat_access"])) {
		$acat_permit = implode(',', $_POST["acat_access"]);

		// enym, limited access requires some default settings
		$_POST["acat_regonly"] = 1;
		unset($_POST["acat_nositemap"]);
		$_POST["acat_nosearch"] = 1;

	} else {
		$acat_permit = '';
	}

	$acat_hidden = 0;
	if(isset($_POST["acat_hidden"])) {
		$acat_hidden = 1;
		if(isset($_POST["acat_hiddenactive"])) {
			$acat_hidden = 2;
		}
	}

	$acat_cntpart = '';
	if(isset($_POST['acat_cp']) && is_array($_POST['acat_cp'])) {

		$acat_cntpart = $_POST['acat_cp'];
		$acat_cntpart = array_unique($acat_cntpart);
		$acat_cntpart = implode(',', $acat_cntpart);

	}

	$acat_class = empty($_POST["acat_class"]) ? '' : preg_replace('/[^a-zA-Z0-9_\- ]/', '', clean_slweg($_POST["acat_class"], 150));

	if(empty($_POST["acat_keywords"])) {
		$acat_keywords = '';
	} else {
		$acat_keywords = substr( implode(', ', convertStringToArray( clean_slweg($_POST["acat_keywords"], 255) ) ), 0, 255);
	}

	$acat_breadcrumb = 0;
	if(!empty($_POST["acat_breadcrumb_nothidden"])) {
		$acat_breadcrumb = 1;
	}
	if(!empty($_POST["acat_breadcrumb_nolink"])) {
		$acat_breadcrumb += 2;
	}

	if(isset($_POST["acat_id"]) && $_POST["acat_id"] === 'index') {
		// write index page config into flat file
		$sql  = "<?php\n";
		$sql .= "\$indexpage['acat_name']		= '".	str_replace("''", "\\'", clean_slweg($_POST["acat_name"]))."';\n";
		$sql .= "\$indexpage['acat_info']		= '".	str_replace("''", "\\'", clean_slweg($_POST["acat_info"], 32000))."';\n";
		$sql .= "\$indexpage['acat_alias']		= '".	proof_alias($_POST["acat_id"], $_POST["acat_alias"])."';\n";
		$sql .= "\$indexpage['acat_aktiv']		= ".	(isset($_POST["acat_aktiv"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_template']	= ".	intval($_POST["acat_template"]).";\n";
		$sql .= "\$indexpage['acat_hidden']		= ".	$acat_hidden.";\n";
		$sql .= "\$indexpage['acat_ssl']		= ".	(isset($_POST["acat_ssl"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_regonly']	= ".	(isset($_POST["acat_regonly"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_topcount']	= ".	intval($_POST["acat_topcount"]).";\n";
		$sql .= "\$indexpage['acat_maxlist']	= ".	intval($_POST["acat_maxlist"]).";\n";
		$sql .= "\$indexpage['acat_redirect']	= '".	str_replace("''", "\\'", clean_slweg($_POST["acat_redirect"]))."';\n";
		$cache_timeout = clean_slweg($_POST["acat_timeout"]);
		if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) $cache_timeout = 0; //check if cache = Off
		$sql .= "\$indexpage['acat_timeout']	= '".	$cache_timeout."';\n";
		$sql .= "\$indexpage['acat_nosearch']	= '".	((isset($_POST['acat_nosearch']) && intval($_POST['acat_nosearch'])) ? '1' : '')."';\n";
		$sql .= "\$indexpage['acat_nositemap']	= ".	(isset($_POST["acat_nositemap"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_order']		= ". 	set_correct_ordersort() .";\n";
		$sql .= "\$indexpage['acat_permit']		= '".	$acat_permit."';\n";
		$sql .= "\$indexpage['acat_cntpart']	= '".	$acat_cntpart."';\n";
		$sql .= "\$indexpage['acat_pagetitle']	= '".	str_replace("''", "\\'", clean_slweg($_POST["acat_pagetitle"]))."';\n";
		$sql .= "\$indexpage['acat_paginate']	= ".	(isset($_POST["acat_paginate"]) ? 1 : 0).";\n";
		$sql .= "\$indexpage['acat_overwrite']	= '".	str_replace("''", "\\'", clean_slweg($_POST["acat_overwrite"]))."';\n";
		$sql .= "\$indexpage['acat_archive']	= ".	(empty($_POST["acat_archive"]) ? 0 : 1) .";\n";
		$sql .= "\$indexpage['acat_class']		= '".	str_replace("'", "\\'", $acat_class)."';\n";
		$sql .= "\$indexpage['acat_keywords']	= '".	str_replace("'", "\\'", $acat_keywords)."';\n";
		$sql .= "\$indexpage['acat_cpdefault']	= ".	intval($_POST["acat_cpdefault"]).";\n";
		$sql .= "\$indexpage['acat_disable301']	= ".	(empty($_POST["acat_disable301"]) ? 0 : 1).";\n";
		$sql .= "\$indexpage['acat_opengraph']	= ".	(empty($_POST["acat_opengraph"]) ? 0 : 1).";\n";
		$sql .= "\$indexpage['acat_canonical']	= '".	str_replace("'", "\\'", clean_slweg($_POST["acat_canonical"], 2000))."';\n";
		$sql .= "\$indexpage['acat_breadcrumb']	= ".	$acat_breadcrumb .";\n";

		write_textfile(PHPWCMS_ROOT.'/include/config/conf.indexpage.inc.php', $sql);
	}

	$acat_sort_fallback	= isset($_POST["acat_sort"]) ? intval(trim($_POST["acat_sort"])) : 0;
	$acat_sort_temp		= isset($_POST["acat_sort_temp"]) ? intval($_POST["acat_sort_temp"]) : 0;
	$acat_lang			= empty($_POST["acat_lang"]) ? '' : clean_slweg($_POST["acat_lang"]);
	$acat_lang_type		= $acat_lang == '' || empty($_POST["acat_lang_type"]) ? '' : in_array($_POST["acat_lang_type"], array('category', 'article')) ? $_POST["acat_lang_type"] : '';
	$acat_lang_id		= $acat_lang_type == '' || empty($_POST["acat_lang_id"]) ? 0 : intval($_POST["acat_lang_id"]);

	if($acat_sort_fallback === 0 && $acat_sort_temp > 0) {
		$acat_sort_fallback = $acat_sort_temp;
	}

	if(isset($_POST["acat_new"]) && intval($_POST["acat_new"]) == 1 && intval($_POST["acat_id"]) == 0 && $_POST["acat_id"] != 'index') {
		if(trim($_POST["acat_name"])) {

			$cache_timeout = clean_slweg($_POST["acat_timeout"]);
			if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) $cache_timeout = 0; //check if cache = Off

			$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_articlecat (acat_name, acat_info, acat_aktiv, acat_ssl, acat_regonly, ".
			"acat_struct, acat_template, acat_sort, acat_uid, acat_alias, acat_hidden, acat_topcount, ".
			"acat_redirect, acat_order, acat_cache, acat_nosearch, acat_nositemap, acat_permit, acat_maxlist, ".
			"acat_cntpart, acat_pagetitle, acat_paginate, acat_overwrite, acat_archive, acat_class, acat_keywords, ".
			"acat_cpdefault, acat_lang, acat_lang_type, acat_lang_id, acat_disable301, acat_opengraph, acat_canonical, acat_breadcrumb) VALUES ('".
			getpostvar($_POST["acat_name"])."','".
			getpostvar($_POST["acat_info"], 32000)."',".
			(isset($_POST["acat_aktiv"]) ? 1 : 0).",".
			(isset($_POST["acat_ssl"]) ? 1 : 0).",".
			(isset($_POST["acat_regonly"]) ? 1 : 0).",".
			intval($_POST["acat_struct"]).",".
			intval($_POST["acat_template"]).",".
			$acat_sort_fallback.",".
			$_SESSION["wcs_user_id"].",'".
			proof_alias($_POST["acat_id"], $_POST["acat_alias"])."',".
			$acat_hidden.", ".
			intval($_POST["acat_topcount"]).",'".
			getpostvar($_POST["acat_redirect"])."', ".
			set_correct_ordersort().",'".
			$cache_timeout."', '".(isset($_POST['acat_nosearch']) ? 1 : '')."',".
			(isset($_POST["acat_nositemap"]) ? 1 : 0).",".
			"'".$acat_permit."', ".intval($_POST["acat_maxlist"]).", "._dbEscape($acat_cntpart).",'".
			getpostvar($_POST["acat_pagetitle"])."', ".(isset($_POST["acat_paginate"]) ? 1 : 0).", '".getpostvar($_POST["acat_overwrite"])."',".
			(empty($_POST["acat_archive"]) ? 0 : 1).", "._dbEscape($acat_class).", "._dbEscape($acat_keywords).", ".intval($_POST["acat_cpdefault"]).",".
			_dbEscape($acat_lang).','._dbEscape($acat_lang_type).','._dbEscape($acat_lang_id).','.(empty($_POST["acat_disable301"]) ? '0' : '1').','.
			(empty($_POST["acat_opengraph"]) ? 0 : 1).', '._dbEscape(clean_slweg($_POST["acat_canonical"], 2000)).','.
			$acat_breadcrumb.')';
			if($result = mysql_query($sql, $db) or die("MySQL Error: ".mysql_error())) {
				$ref .= "&cat=".mysql_insert_id($db);
			}
		}
	}

	if(isset($_POST["acat_new"]) && isset($_POST["acat_id"]) && intval($_POST["acat_new"]) == 0 && intval($_POST["acat_id"])) {
		if(trim($_POST["acat_name"])) {

			$cache_timeout = clean_slweg($_POST["acat_timeout"]);
			if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) {
				$cache_timeout = 0; //check if cache = Off
			}

			$sql =	"UPDATE ".DB_PREPEND."phpwcms_articlecat SET ".
				"acat_name='".getpostvar($_POST["acat_name"])."', ".
				"acat_info='".getpostvar($_POST["acat_info"], 32000)."', ".
				"acat_alias="._dbEscape(proof_alias($_POST["acat_id"], $_POST["acat_alias"])).", ".
				"acat_aktiv=".(isset($_POST["acat_aktiv"]) ? 1 : 0).", ".
				"acat_struct=".intval($_POST["acat_struct"]).", ".
				"acat_template=".intval($_POST["acat_template"]).", ".
				"acat_sort=".$acat_sort_fallback.", ".
				"acat_uid=".$_SESSION["wcs_user_id"].", ".
				"acat_hidden=".$acat_hidden.", ".
				"acat_ssl=".(isset($_POST["acat_ssl"]) ? 1 : 0).", ".
				"acat_regonly=".(isset($_POST["acat_regonly"]) ? 1 : 0).", ".
				"acat_topcount=".intval($_POST["acat_topcount"]).", ".
				"acat_redirect='".getpostvar($_POST["acat_redirect"])."',".
				"acat_order=".set_correct_ordersort().", ".
				"acat_cache="._dbEscape($cache_timeout).", ".
				"acat_nosearch='".(isset($_POST['acat_nosearch']) ? 1 : '')."', ".
				"acat_nositemap=".(isset($_POST["acat_nositemap"]) ? 1 : 0).", ".
				"acat_permit="._dbEscape($acat_permit).", ".
				"acat_maxlist=".intval($_POST["acat_maxlist"]).", ".
				"acat_cntpart="._dbEscape($acat_cntpart).", ".
				"acat_pagetitle='".getpostvar($_POST["acat_pagetitle"])."', ".
				"acat_paginate=".(isset($_POST["acat_paginate"]) ? 1 : 0).", ".
				"acat_overwrite='".getpostvar($_POST["acat_overwrite"])."', ".
				"acat_archive=".(empty($_POST["acat_archive"]) ? 0 : 1).", ".
				"acat_class="._dbEscape($acat_class).", ".
				"acat_keywords="._dbEscape($acat_keywords).",".
				"acat_cpdefault=".intval($_POST["acat_cpdefault"]).','.
				"acat_lang="._dbEscape($acat_lang).','.
				"acat_lang_type="._dbEscape($acat_lang_type).','.
				"acat_lang_id="._dbEscape($acat_lang_id).','.
				"acat_disable301=".(empty($_POST["acat_disable301"]) ? '0' : '1').','.
				"acat_opengraph=".(empty($_POST["acat_opengraph"]) ? '0' : '1').','.
				"acat_canonical="._dbEscape(clean_slweg($_POST["acat_canonical"], 2000)).','.
				"acat_breadcrumb=".$acat_breadcrumb.
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
							$value1s .= ", "._dbEscape($value);
						}
					}
					$sql2 =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecontent (".$key1s.") VALUES (".$value1s.")";
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
				$sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_deleted=9, article_alias=CONCAT(article_alias,'_del-','".date('YmdHis')."') WHERE (".$a_del.")";
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
				$sql = "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_trash=9, acat_alias=CONCAT(acat_alias,'_del-','".date('YmdHis')."') WHERE (".$s_del.")";
				mysql_query($sql, $db) or die("error while deleting structures");
			}
		}

	}
	break;

}

} //Ende Abarbeiten Aktion

update_cache();

// empty pre-rendered frontend structure for all visible modes
// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
_setConfig('structure_array_vmode_all', false, 'frontend_render', 1);
_setConfig('structure_array_vmode_editor', false, 'frontend_render', 1);
_setConfig('structure_array_vmode_admin', false, 'frontend_render', 1);

if(isset($_POST['SubmitClose'])) {
	headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string('csrftoken').'&do=admin&p=6&'.get_token_get_string('csrftoken'));
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
	if($result = mysql_query($sql, $dbcon) or die("error while connecting to database: <pre>".$sql."</pre>")) {

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
					$values .= ", "._dbEscape($value);
				}
			}
		}
		mysql_free_result($result);

		$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_article (".$keys.") VALUES (".$values.")";

		if($result = mysql_query($sql, $dbcon) or die("error while copy article <br>error while connecting to database: <pre>".$sql."</pre>")) {

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
							$value1s .= ", "._dbEscape($value1);
						}
					}
					$sql2 =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecontent (".$key1s.") VALUES (".$value1s.")";
					$result = mysql_query($sql2, $dbcon) or die("error while copy article content <br>error while connecting to database: <pre>".$sql2."</pre>");
				}
				mysql_free_result($result1);
			}

			if(empty($GLOBALS['phpwcms']['disallow_open_copied_article']) && isset($do[3]) && $do[3] == 'open' && $article_insert_id) {

				headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string('csrftoken').'&do=articles&p=2&s=1&id='.$article_insert_id);

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
					$values .= ", "._dbEscape($value);
				}
			}
		}
		mysql_free_result($result);

		$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecat (".$keys.") VALUES (".$values.")";
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
			$do_struct[2] = $acat_insert_id;
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

	// but why not - should be possible too based on new sorting
	$val = intval($_POST["acat_order"]) + intval($_POST["acat_ordersort"]);

	return $val;
}
