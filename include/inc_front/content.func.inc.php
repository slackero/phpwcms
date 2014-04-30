<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//predefine values
$content['cat']					= '';
$content['metakey']				= '';
$content['struct']				= get_struct_data(); //reads the complete structure as array
$content['article_date']		= time();
$content['redirect']			= array('code' => '');
$content['all_keywords']		= '';
$content['globalRT']			= array();
$content['aId_CpPage']			= 0; // set default content part pagination page (0 and 1) are the same
$content['CpTrigger']			= array(); // array to hold content part trigger functions
$content['404error']			= array('status' => false, 'id' => '', 'aid' => '', 'alias' => '');
$content['set_canonical']		= false;
$content['cptab']				= array(); // array to hold content part based tabs
$content['images']				= array();
$content['opengraph']			= array('support' => true, 'type' => 'website'); // will hold all relevant open graph information
$pagelayout						= array();
$no_content_for_this_page		= false;
$alias							= '';
$PERMIT_ACCESS					= true; // by default set all content without permissions
$CUSTOM							= array(); // var that holds result of content part "php var"

// reset force redirect in case it is a POST
if(!empty($_POST)) {
	$phpwcms['force301_id2alias']	= false;
	$phpwcms['force301_2struct']	= false;
} elseif(isset($_GET['phpwcms-preview'])) {
	$phpwcms['force301_id2alias']	= false;
	$phpwcms['force301_2struct']	= false;
	unset($_GET['phpwcms-preview'], $_getVar['phpwcms-preview']);
	checkLoginCount();
	if(empty($_SESSION["wcs_user"])) {
		headerRedirect(abs_url());
	}
}

//method to get the right action values
//if there is only the ?alias try to find the right category
if(isset($_GET["id"])) {

	$aktion = explode(',', $_GET["id"], 6);
	$aktion[0] = intval($aktion[0]);
	$aktion[1] = isset($aktion[1]) ? intval($aktion[1]) : 0;
	$aktion[2] = isset($aktion[2]) ? intval($aktion[2]) : 0;
	$aktion[3] = isset($aktion[3]) ? intval($aktion[3]) : 1;
	$aktion[4] = isset($aktion[4]) ? intval($aktion[4]) : 0;
	$aktion[5] = isset($aktion[5]) ? intval($aktion[5]) : 0;

	// check if article category is given and available
	if(!isset($content['struct'][ $aktion[0] ])) {

		$content['404error']['id']		= $aktion[0];
		$content['404error']['aid']		= $aktion[1];
		$content['404error']['status']	= true;

		$aktion[0] = 0;

		// OK in case not we should check if given article ID is correct
		if($aktion[1]) {
			$sql  =	'SELECT article_id, article_cid FROM '.DB_PREPEND.'phpwcms_article WHERE ';
			$sql .= 'article_deleted=0 AND article_aktiv=1 AND article_id='.$aktion[1].' LIMIT 1';
			$aktion[1] = 0; //reset
			if($result = mysql_query($sql, $db)) {
				if($row = mysql_fetch_row($result)) {
					$aktion[0] = $row[1];
					$aktion[1] = $row[0];
					$content['404error']['status'] = false;
				}
				mysql_free_result($result);
			}
		}

		if($content['404error']['status'] === false) {
			$GLOBALS['_getVar']['id'] = implode(',', $aktion);
			headerRedirect(abs_url( array(), array(), '', 'urlencode'), 404);
		}
	}

	// Force 301 Redirect when alias is available
	if($content['404error']['status'] === false && !empty($phpwcms['force301_id2alias']) && !empty($content['struct'][ $aktion[0] ]['acat_alias'])) {
		headerRedirect(abs_url(array(), array(), $content['struct'][ $aktion[0] ]['acat_alias'], 'urlencode'), 301);
	}

} elseif(isset($_GET['aid'])) {
	// try to find correct structure
	$aktion = array(0,0,0,0,1,0);

	$_GET['aid']			= explode('-', $_GET['aid'], 2); // now check for cp pagination
	$content['aId_CpPage']	= isset($_GET['aid'][1]) ? intval($_GET['aid'][1]) : 0; // set cp paginate page
	$_GET['aid']			= intval($_GET['aid'][0]);
	if($_GET['aid']) {

		$sql  =	'SELECT article_cid, article_alias FROM '.DB_PREPEND.'phpwcms_article WHERE ';
		$sql .= 'article_deleted=0 AND article_id='.$_GET['aid'].' ';
		if(VISIBLE_MODE !== 2) {
			$sql .= 'AND article_aktiv=1 ';
		} elseif(VISIBLE_MODE === 1) {
			$sql .= 'AND (article_aktiv=1 OR article_uid='.intval($_SESSION["wcs_user_id"]).') ';
		}
		$sql .= 'LIMIT 1';
		if($result = mysql_query($sql, $db)) {
			if($row = mysql_fetch_row($result)) {
				$aktion[0] = $row[0];
				$aktion[1] = $_GET['aid'];

				// Force 301 Redirect when alias is available
				if(!empty($phpwcms['force301_id2alias']) && !$content['aId_CpPage'] && !empty($row[1])) {
					headerRedirect(abs_url(array(), array(), $row[1], 'urlencode'), 301);
				}

			} else {

				$content['404error']['status'] = true;

			}
			mysql_free_result($result);

		} else {

			$content['404error']['status'] = true;

		}

		$content['404error']['id']	= $aktion[0];
		$content['404error']['aid']	= $aktion[1];
	}

	if(!$aktion[1]) {
		$content['aId_CpPage'] = 0; // no article = no pagination
	}

} else {
	// check the alias
	$aktion = array(0,0,0,1,0,0);

	if(count($GLOBALS['_getVar'])) {
		reset($GLOBALS['_getVar']);
		$alias = trim(key($GLOBALS['_getVar']));
		if($alias && $GLOBALS['_getVar'][$alias] === '') { // alias must be empty ""

			$where_alias = aporeplace($alias);

			// we have to check against MySQL < 4.0 -> UNION unknown
			// so use a workaround

			if(PHPWCMS_DB_VERSION < 40000) {

				$sql  = "SELECT acat_id, (0) AS article_id, 1 AS aktion3, 0 AS aktion4 FROM " . DB_PREPEND . "phpwcms_articlecat ";
				$sql .= "WHERE acat_trash=0 AND acat_aktiv=1 AND acat_alias='" . $where_alias . "' LIMIT 1";

				$row = _dbQuery($sql);

				if(!isset($row[0]['acat_id'])) {

					$sql  = "SELECT article_cid AS acat_id, article_id, 0 AS aktion3, 1 AS aktion4 FROM " . DB_PREPEND . "phpwcms_article ";
					$sql .= "WHERE article_deleted=0 AND article_aktiv=1 AND article_alias='" . $where_alias . "' LIMIT 1";

					$row = _dbQuery($sql);

				}

			} else {

				$sql  = "(SELECT acat_id, (0) AS article_id, 1 AS aktion3, 0 AS aktion4 FROM " . DB_PREPEND . "phpwcms_articlecat ";
				$sql .= "WHERE acat_trash=0 AND acat_aktiv=1 AND acat_alias='" . $where_alias . "')";
				$sql .= " UNION ";
				$sql .= "(SELECT article_cid AS acat_id, article_id, 0 AS aktion3, 1 AS aktion4 FROM " . DB_PREPEND . "phpwcms_article ";
				$sql .= "WHERE article_deleted=0 AND article_aktiv=1 AND article_alias='" . $where_alias . "') ";
				$sql .= "LIMIT 1";

				$row = _dbQuery($sql);

			}

			if(isset($row[0]['acat_id'])) {

				$aktion[0] = $row[0]['acat_id'];
				$aktion[1] = $row[0]['article_id'];
				$aktion[3] = $row[0]['aktion3'];
				$aktion[4] = $row[0]['aktion4'];

				define('PHPWCMS_ALIAS', $alias);

			} elseif($alias == $indexpage['acat_alias']) {

				define('PHPWCMS_ALIAS', $alias);

			} else {

				$content['404error']['status'] = true;

			}

			$content['404error']['id']		= $aktion[0];
			$content['404error']['aid']		= $aktion[1];
			$content['404error']['alias']	= $alias;

		}
	}

}
if(isset($_GET['print'])) {

	$aktion[2] = 1;
	define('PRINT_PDF', $_GET['print'] == 2 && !empty($phpwcms['wkhtmltopdf_path']) ? true : false);
	unset($_getVar['print'], $_GET['print']);

}

// in case of detected 404 error
if($content['404error']['status'] === true) {

	// Check if it is a 404 error redirect
	$content['404error']['redirect_url'] = '';

	if($content['404error']['alias'] == 'r404' || (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] == 404)) {

		// test against $_SERVER[REDIRECT_URL] and check original
		// try to detect and remove $phpwcms[root]
		if(!empty($_SERVER['REDIRECT_URL'])) {

			$content['404error']['redirect_url'] = trim($_SERVER['REDIRECT_URL'], '/');

			if($phpwcms['root'] && $content['404error']['redirect_url']) {
				$content['404error']['redirect_url'] = substr_replace($content['404error']['redirect_url'], '', 0, strlen($phpwcms['root']));
			}
		}
	}

	if($content['404error']['redirect_url']) {

		// ToDo: maybe Check against structure/article alias and redirect
		$content['404error']['where'] = sprintf('alias LIKE %s', _dbEscape($content['404error']['redirect_url']));
		$content['404error']['alias'] = $content['404error']['redirect_url'];

	} else {

		$content['404error']['where'] = sprintf('id=%d AND aid=%d AND alias LIKE %s', $content['404error']['id'], $content['404error']['aid'], _dbEscape($content['404error']['alias']));

	}

	// does the combination still exists in the database
	$content['404error']['result'] = _dbGet('phpwcms_redirect', '*', $content['404error']['where']);


	if(isset($content['404error']['result'][0])) {

		$content['404error']['result'] = $content['404error']['result'][0];

		_dbUpdate('phpwcms_redirect', array('views'	=> intval($content['404error']['result']['views']) + 1), 'rid='.$content['404error']['result']['rid']);

		// Test for redirect
		if($content['404error']['result']['active'] == 1) {

			// HTTP Status
			// 301, 302 (default), 307, 401, 404, 503
			$content['404error']['result']['code'] = empty($content['404error']['result']['code']) ? 302 : intval($content['404error']['result']['code']);

			// Redirect to Home
			// home (empty), alias, id, aid, link
			if(empty($content['404error']['result']['type'])) {

				$content['404error']['result']['target'] = getStructureChildEntryHref($content['struct'][0]);
				$content['404error']['result']['target'] = PHPWCMS_URL . $content['404error']['result']['target']['link'];
				headerRedirect($content['404error']['result']['target'], $content['404error']['result']['code']);

			} elseif($content['404error']['result']['target']) {

				switch($content['404error']['result']['type']) {

					case 'alias':
						$content['404error']['result']['target'] = abs_url(array(), array(), $content['404error']['result']['target'], 'rawurlencode');
						headerRedirect($content['404error']['result']['target'], $content['404error']['result']['code']);
						break;

					case 'id':
						$content['404error']['result']['target'] = abs_url(array(), array(), 'id='.$content['404error']['result']['target'], 'rawurlencode');
						headerRedirect($content['404error']['result']['target'], $content['404error']['result']['code']);
						break;

					case 'aid':
						$content['404error']['result']['target'] = abs_url(array(), array(), 'aid='.$content['404error']['result']['target'], 'rawurlencode');
						headerRedirect($content['404error']['result']['target'], $content['404error']['result']['code']);
						break;

					case 'link':
						headerRedirect($content['404error']['result']['target'], $content['404error']['result']['code']);
						break;

				}

			}
		}

		$content['404error']['result'] = NULL;

	} elseif(!empty($phpwcms['log_404error'])) {

		// Store failed page access
		_dbInsert('phpwcms_redirect', array(
			'id'	=> $content['404error']['id'],
			'aid'	=> $content['404error']['aid'],
			'alias'	=> $content['404error']['alias'],
			'views'	=> 1
		));
	}
}

// define special OUTPUT format/action
$phpwcms['output_action'] = false;
if(!empty($_GET['phpwcms_output_action']) || !empty($_POST['phpwcms_output_action'])) {

	// split by function - value: F-function1|function2|function3--S-SECT1|SECT2|SECT3
	$phpwcms['output_action']	= explode('--', clean_slweg( empty($_GET['phpwcms_output_action']) ? $_POST['phpwcms_output_action'] : $_GET['phpwcms_output_action'] ));
	unset(
		$_GET['phpwcms_output_action'],
		$_POST['phpwcms_output_action'],
		$_getVar['phpwcms_output_action']
	);

	if(is_array($phpwcms['output_action'])) {

		$phpwcms['output_function']	= array();
		$phpwcms['output_section']	= array();

		foreach($phpwcms['output_action'] as $value) {

			$value = trim($value);
			if($value{0} == 'F') {
				$value = explode('|', substr($value, 2));
				$output_key = 'output_function';
			} elseif($value{0} == 'S') {
				$value = explode('|', substr($value, 2));
				$output_key = 'output_section';
			} else {
				continue;
			}

			if(is_array($value)) {

				foreach($value as $_value) {

					$_value = trim($_value);

					if($_value != '') {
						$phpwcms[$output_key][$_value] = $_value;
					}
				}
			}
		}

		$phpwcms['output_action'] = count($phpwcms['output_function']) || count($phpwcms['output_section']) ? true : false;

	} else {
		$phpwcms['output_action'] = false;
	}
}

//define the current article category ID
$content["cat_id"]	= $aktion[0];
$content['body_id']	= $aktion[0];

// check if current level is a redirect level
if(!empty($content['struct'][ $content["cat_id"] ]['acat_redirect'])) {
	$redirect = get_redirect_link( $content['struct'][ $content["cat_id"] ]['acat_redirect'] );
	headerRedirect($redirect['link'], 301);
}
// Check if curret level is forced for SSL
if(!PHPWCMS_SSL && (!empty($phpwcms['site_ssl_mode']) || !empty($content['struct'][ $content["cat_id"] ]['acat_ssl']))) {
	headerRedirect($phpwcms['site_ssl_url'] . (count($_getVar) ? rel_url() : ''), 301);
}

//try to find current tree depth
$LEVEL_ID  		= array();
$LEVEL_KEY 		= array();
$LEVEL_STRUCT	= array();
$level_ID_array	= get_breadcrumb($content["cat_id"], $content['struct']);
$level_count	= 0;
foreach($level_ID_array as $key => $value) {
	$LEVEL_ID[$level_count]		= $key;
	$LEVEL_KEY[$key] 			= $level_count;
	$LEVEL_STRUCT[$level_count]	= $content['struct'][$key]['acat_name'];
	if($PERMIT_ACCESS && $content['struct'][$key]['acat_regonly']) {
		$PERMIT_ACCESS			= false; // only users have been logged in get access
	}
	$level_count++;
}

define('PERMIT_ACCESS', $PERMIT_ACCESS);
// frontend login check
_checkFrontendUserAutoLogin();

// read the template information for page based on structure
if(!empty($content["struct"][ $content["cat_id"] ]["acat_template"])) {
	//if there is a template defined for this structure level
	//then choose the template information based on this ID
	$sql  = "SELECT template_var FROM ".DB_PREPEND."phpwcms_template WHERE template_trash=0 AND ";
	$sql .= "template_id=".$content["struct"][ $content["cat_id"] ]["acat_template"]." LIMIT 1;";
	if($result = mysql_query($sql, $db)) {
		if($row = mysql_fetch_row($result)) {
			$block = unserialize($row[0]);
		}
		mysql_free_result($result);
	}
}
if(!isset($block)) {
	// if template ID is not defined or the were a problem with level's template ID then
	// choose the default template or if no default template defined choose the next one
	$sql  = "SELECT template_var FROM ".DB_PREPEND."phpwcms_template ";
	$sql .= "WHERE template_trash=0 ORDER BY template_default DESC LIMIT 1;";
	if($result = mysql_query($sql, $db)) {
		if($row = mysql_fetch_row($result)) {
			$block = unserialize($row[0]);
		}
		mysql_free_result($result);
	}
}

$block['bodyjs'] = array();

// compatibility for older releases where only
// 1 css file could be stored per template
if(is_string($block['css'])) {
	$block['css'] = array($block['css']);
}

// template defaults
$template_default['classes'] = isset($template_default['classes']) ? array_merge($phpwcms['default_template_classes'], $template_default['classes']) : $phpwcms['default_template_classes'];

// check if template_defaults should be overwritten
if(!empty($block['overwrite'])) {
	$block['overwrite'] = str_replace('/', '', $block['overwrite']);
	@include(PHPWCMS_TEMPLATE.'inc_settings/template_default/'.$block['overwrite']);
}
if(!empty($content['struct'][ $content['cat_id'] ]['acat_overwrite'])) {
	$block['overwrite'] = str_replace('/', '', $content['struct'][ $content['cat_id'] ]['acat_overwrite']);
	@include(PHPWCMS_TEMPLATE.'inc_settings/template_default/'.$block['overwrite']);
}

// search highlight prefix/suffix
if(isset($template_default['search_highlight']['prefix'])) {
	$phpwcms['search_highlight'] = array_merge($phpwcms['search_highlight'], $template_default['search_highlight']);
}

// load frontend JavaScript lib file
require PHPWCMS_ROOT.'/include/inc_front/js.inc.php';

// retrieve pagelayout info
// check how the content should be rendered based on pagelayout render value
$block["layout"] = intval($block["layout"]);
$sql  = "SELECT pagelayout_var FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_trash=0 ";
$sql .= $block["layout"] ? "AND pagelayout_id=".$block["layout"] : "ORDER BY pagelayout_default DESC";
$sql .= " LIMIT 1";
$result = _dbQuery($sql);
if(isset($result[0]['pagelayout_var'])) {
	$pagelayout = @unserialize($result[0]['pagelayout_var']);
	// if print action
	if($aktion[2] === 1) {
		$pagelayout = array(
			'layout_title' => $pagelayout['layout_title'],
			'layout_customblocks' => $pagelayout['layout_customblocks'],
			'layout_noborder' => $pagelayout['layout_noborder']
		);
	}
}
if(empty($pagelayout)) {
	// if no pagelayout could be found
	die('There is no pagelayout available. Please <a href="'.
		PHPWCMS_URL.get_login_file().'">login</a> to the admin section and <a href="'.
		PHPWCMS_URL.'phpwcms.php?do=admin&p=8">create one here</a>!');
}
// Pagetitle
if(empty($pagelayout["layout_title"])) {
	$content["pagetitle"] = '';
} else {
	$content["pagetitle"] = $pagelayout["layout_title"];
	$content['opengraph']['title'] = $pagelayout["layout_title"];
}

//generate the colspan attribute
$colspan = get_colspan($pagelayout);

// now initialize content blocks like CONTENT, HEADER, LEFT, RIGHT, FOOTER
$content['main']			= ''; // {CONTENT}
$content['CB']['LEFT']		= ''; // {LEFT}
$content['CB']['RIGHT']		= ''; // {RIGHT}
$content['CB']['HEADER']	= ''; // {HEADER}
$content['CB']['FOOTER']	= ''; // {FOOTER}
// and try to add and initialize custom blocks
if(!empty($pagelayout['layout_customblocks'])) {
	$custom_blocks = explode(', ', $pagelayout['layout_customblocks']);
	foreach($custom_blocks as $value) {
		if($value !== '') {
			$content['CB'][$value] = '';
		}
	}
	unset($custom_blocks);
}

// try to include custom functions or what ever you want to do at this point of the script
// default dir: "phpwcms_template/inc_script/frontend_init"; only *.php files are allowed there
if($phpwcms["allow_ext_init"]) {
	if(count($custom_includes = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_script/frontend_init', 'php'))) {
		foreach($custom_includes as $value) {
			include_once(PHPWCMS_TEMPLATE.'inc_script/frontend_init/'.$value);
		}
	}
}
// include custom frontend init scripts based on module definitions
if(count($phpwcms['modules_fe_init'])) {
	foreach($phpwcms['modules_fe_init'] as $value) {
		include_once($value);
	}
}

// redirect to login form if user is not logged in and has no permission to access level
if(!PERMIT_ACCESS && !_getFeUserLoginStatus()) {

	if(!empty($block['feloginurl'])) {
		$template_default['login_form_url'] = str_replace('{SITE}', PHPWCMS_URL, $block['feloginurl']);
	} elseif(empty($template_default['login_form_url'])) {
		$template_default['login_form_url'] = PHPWCMS_URL;
	}

	// store current URL
	$_SESSION['LOGIN_REDIRECT'] = decode_entities(FE_CURRENT_URL);

	// redirect to login form
	headerRedirect($template_default['login_form_url'], 401);
}

//reads all articles for active cat into array
$content["articles"]			= get_actcat_articles_data($content["cat_id"]);
$content["article_list_count"]	= count($content["articles"]);

// generating a list of articles inside the current article category
if(!$aktion[4]) {

	if($content['404error']['status'] === false && ($content["article_list_count"] || $content['struct'][ $content['cat_id'] ]['acat_topcount'] == -1)) {

		$content['opengraph']['type'] = 'article';

		if($content["article_list_count"] == 1 || $content['struct'][ $content['cat_id'] ]['acat_topcount'] == -1) {
		    // if($temp_counter == 1) {
			// if only 1 article for this category available
			// then show this article directly
			// sets article ID to this only 1 article
			foreach($content["articles"] as $key => $value) {
				$aktion[1] = intval($key);
				break;
			}
			$aktion[4] = 1; // this needs to be set to 1 for showing the article

			// enable canonical <link> tag
			$content['set_canonical'] = true;

		} else {

			// there is more than 1 article inside this category

			//enym enable structure fe link for listing mode
      		if(FE_EDIT_LINK && $_SESSION["wcs_user_admin"]) {
				$content["main"] .= getFrontendEditLink('structure', $content['cat_id']);
			}

			// -> list all - the 1st will be shown with summary and such stuff
			$content["main"] .= list_articles_summary();

		}

	} else {

		$no_content_for_this_page = true;

	}

} elseif($content["article_list_count"] === 1) {

	// enable canonical <link> tag
	$content['set_canonical'] = true;

}

// Force 301 Redirect to structure alias
if($content['set_canonical'] && !empty($phpwcms['force301_2struct']) && !$content['aId_CpPage'] && empty($content['struct'][ $aktion[0] ]['acat_disable301']) && !empty($content['struct'][ $aktion[0] ]['acat_alias']) && (!defined('PHPWCMS_ALIAS') || PHPWCMS_ALIAS != $content['struct'][ $aktion[0] ]['acat_alias'])) {
	headerRedirect(abs_url(array(), array(), $content['struct'][ $aktion[0] ]['acat_alias'], 'urlencode'), 301);
} elseif(count($_getVar) > 1) {
	$content['set_canonical'] = false;
}

// Include depricated functionality if needed
if(!empty($phpwcms['enable_deprecated'])) {
	include_once(PHPWCMS_ROOT."/include/inc_front/deprecated.inc.php");
} else {
	$phpwcms['enable_deprecated'] = false;
}

// check if current category should be cached
if($content['struct'][$content['cat_id']]['acat_timeout'] != '') {
	$phpwcms['cache_timeout'] = $content['struct'][$content['cat_id']]['acat_timeout'];
}
// check if social sharing is allowed for current category
if(!$content['struct'][$content['cat_id']]['acat_opengraph']) {
	$content['opengraph']['support'] = false;
}
// set search status for current category
$cache_searchable = $content['struct'][$content['cat_id']]['acat_nosearch'];

$content['list_mode'] = true;

if($aktion[1]) {

	// render page based on article
	include_once(PHPWCMS_ROOT."/include/inc_front/content.article.inc.php");
	$content['list_mode'] = false;

} elseif(!empty($content['struct'][$content['cat_id']]['acat_pagetitle'])) {

	// a custom pagetitle for structure level exists
	$content["pagetitle"] = $content['struct'][$content['cat_id']]['acat_pagetitle'];
	$content['opengraph']['title'] = $content["pagetitle"];

} else {

	$content["pagetitle"] = setPageTitle($content["pagetitle"], $content['struct'][$content['cat_id']]['acat_name'], '');
	$content['opengraph']['title'] = $content['struct'][$content['cat_id']]['acat_name'];

}

//check for no content error
$content["main"] = trim($content["main"]);
if($content['404error']['status'] === true || $no_content_for_this_page || $content["main"] == '') {
	// Show 404 error page
	header('HTTP/1.0 404 Not Found');
	$content["main"] .= $block["errortext"];
}

//check if one of needed block texts and values are empty and if then fill with content
if(!$block["maintext"]) {
	$block["maintext"] = $content["main"];
}

//normal page operation
if($aktion[2] == 0) {

	switch($pagelayout["layout_render"]) {

		case 0:	//create the page layout table (header, left, content, right, footer)
				$content["all"]  = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"";	//start main table
				$content["all"] .= td_attributes($pagelayout, "all", 0);
				$content["all"] .= align_base_layout($pagelayout["layout_align"])." summary=\"\">".LF;		// align table

				//header
				$content["all"] .= colspan_table_row($pagelayout, "header", $colspan, $block["headertext"]); //header row
				if($pagelayout["layout_topspace_height"]) { //header space
					$content["all"] .= colspan_table_row($pagelayout, "topspace", $colspan, spacer(1, $pagelayout["layout_topspace_height"]));
				}

				//returns the main blocks: left column, content column, right column
				$content["all"] .= get_table_block($pagelayout, $block["maintext"], $block["lefttext"], $block["righttext"]);

				//footer
				if($pagelayout["layout_bottomspace_height"]) { //bottom space
					$content["all"] .= colspan_table_row($pagelayout, "bottomspace", $colspan, spacer(1, $pagelayout["layout_bottomspace_height"]));
				}
				$content["all"] .= colspan_table_row($pagelayout, "footer", $colspan, $block["footertext"]); //footer row
				$content["all"] .= '</table>'.LF; //end main table

				break;


		case 1:	//create the page layout based on DIV (layer)

				//contentContainer DIV start
				$content["all"] = '';
				$pagelayout['additional_wrap_div'] = false;
				switch($pagelayout["layout_align"]) {
					case 1:		$content["all"] .= '<div align="center" style="margin:0;padding:0;">';
								$pagelayout['additional_wrap_div'] = true;
								break;
					case 2:		$content["all"] .= '<div align="right" style="margin:0;padding:0;">';
								$pagelayout['additional_wrap_div'] = true;
								break;
				}
				$content["all"] .= '<div id="container">'.LF;

				//header DIV
				if($block["headertext"] || $pagelayout['layout_header_height']) {
					$content["all"] .= '	<div id="headerBlock">'.$block["headertext"]."</div>\n";
				}
				//left DIV if 3column or 2column (with left block)
				if($pagelayout["layout_type"] == 0 || $pagelayout["layout_type"] == 1) {
					$content["all"] .= '	<div id="leftBlock">'.$block["lefttext"]."</div>\n";
				}
				//right DIV if 3column or 2column (with right block)
				if($pagelayout["layout_type"] == 0 || $pagelayout["layout_type"] == 2) {
					$content["all"] .= '	<div id="rightBlock">'.$block["righttext"]."</div>\n";
				}
				//main block
				$content["all"] .= '<div id="mainBlock">'.$block["maintext"]."</div>\n";
				//footer DIV
				if($block["footertext"] || $pagelayout['layout_footer_height']) {
					$content["all"] .= '	<div id="footerBlock">'.$block["footertext"]."</div>\n";
				}
				//contentContainer DIV end
				if($pagelayout['additional_wrap_div']) {
					$content["all"] .= "</div>";
				}
				$content["all"] .= "</div>\n";

				break;


		case 2: //create the page layout based only on the content of main block
				$content["all"]	= $block["maintext"];

				break;

	}

} elseif ($aktion[2] == 1) {

	//if print layout should be shown
	$_print_tmpl = PRINT_PDF ? 'pdf' : 'print';
	$content['all'] = is_file(PHPWCMS_TEMPLATE.'inc_default/'.$_print_tmpl.'.tmpl') ? @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/'.$_print_tmpl.'.tmpl') : '{CONTENT}<hr />{CURRENT_URL}';
	if(PRINT_PDF) {
		$_print_settings	= get_tmpl_section('PDF_SETTINGS', $content['all']);
		$content['all']		= replace_tmpl_section('PDF_SETTINGS', $content['all']);
		$_print_settings	= parse_ini_str($_print_settings, false);
	}

	if($content['all']) {
		$content["all"]	= str_replace('{CURRENT_URL}', abs_url(), $content["all"]);
		$content["all"]	= str_replace('{CONTENT}', $block["maintext"], $content["all"]);
	} else {
		$content['all']	= $block["maintext"];
	}

}

// Render possible PHP Values in category or article keyword field
$content["struct"][$aktion[0]]["acat_info"] = render_PHPcode($content["struct"][$aktion[0]]["acat_info"]);
if(!empty($content["articles"][$aktion[1]]["article_keyword"]) && strpos($content["articles"][$aktion[1]]["article_keyword"], 'PHP') !== FALSE) {
	$content["articles"][$aktion[1]]["article_keyword"]	= render_PHPcode($content["articles"][$aktion[1]]["article_keyword"]);
}

// put in the complete rendered content
$content["all"] = str_replace('{CONTENT}', $content["main"], $content["all"]);
// put in custom rendered content
foreach($content['CB'] as $key => $value) {
	//first check content of custom block in current template
	if(isset($block['customblock_'.$key]) && $block['customblock_'.$key] !== '' && $value !== '') {
		$value = str_replace('{'.$key.'}', $value, $block['customblock_'.$key]);
	}
	//$content["all"] = str_replace('{'.$key.'}', $value, $content["all"]);
	// Blocks should render now as [BLOCK] and [BLOCK_ELSE] if no content
	$content["all"] = render_cnt_template($content["all"], $key, $value);
}

// render Tab replacement code
if(count($content['cptab'])) {
	foreach($content['cptab'] as $CNT_TAB => $trow) {
		$content['all'] = str_replace('<!-- ' . $CNT_TAB . ' -->', $trow, $content['all']);
	}
}

// check layout for list mode sections or detail view
if(strpos($content['all'], '_LIST_MODE')) {
	$content['all'] = replace_tmpl_section( ($content['list_mode'] ? 'ELSE_LIST_MODE' : 'IF_LIST_MODE') , $content['all']);
	$content['all'] = str_replace(array('<!--ELSE_LIST_MODE_START//-->', '<!--ELSE_LIST_MODE_END//-->', '<!--IF_LIST_MODE_START//-->', '<!--IF_LIST_MODE_END//-->'), '', $content['all']);
}

// Initial Render Device
$content['all'] = render_device($content['all']);

// search for specific article ID and or category ID and replace it
$content['all'] = str_replace('{CURRENT_ARTICLEID}', $aktion[1], $content['all']);
$content['all'] = str_replace('{CURRENT_CATEGORYID}', $aktion[0], $content['all']);

// search for level related replacement tags and replace it, sample: {LEVEL2_ID}
$content['all'] = preg_replace_callback('/\{LEVEL(\d+)_ID\}/', 'replace_level_id', $content['all']);

// {SHOW_CONTENT:MODE,id[,id[,...]]}
if( ! ( strpos($content["all"],'{SHOW_CONTENT:')===false ) ) {
	$content["all"] = preg_replace('/\{SHOW_CONTENT:(.*?)\}/e', 'showSelectedContent("$1");', $content["all"]);
}

// include external PHP script (also normal HTML snippets) or return PHP var value
if(strpos($content["all"],'PHP') !== false) {
	$content["all"] = render_PHPcode($content["all"]);
}

//breadcrumb replacement
if(strpos($content["all"],'{BREADCRUMB') !== false) {
	$content['all'] = preg_replace_callback('/\{BREADCRUMB:?(\-?\d+){0,1}\}/', 'breadcrumb_wrapper', $content['all']);
}

// ul/li based navigation, the default one
if(strpos($content["all"],'{NAV_LIST_UL') !== false) {

	// build complete menu structure starting at a specific ID
	// {NAV_LIST_UL:Parameter} Parameter: "menu_type, start_id, class_path, class_active, ul_id_name"
	$content["all"] = preg_replace('/\{NAV_LIST_UL:(.*?)\}/e', 'buildCascadingMenu("$1");', $content["all"]);

}

// some more navigations, do not use - not recommend any longer, need deprecated config enabled
if($phpwcms['enable_deprecated']) {

	if(strpos($content["all"],'{NAV_') !== false) {

		// Simple row based navigation
		$content["all"] = str_replace('{NAV_ROW}', nav_level_row(0), $content["all"]);
		$content["all"] = preg_replace('/\{NAV_ROW:(\w+|\d+):(0|1)\}/e',"nav_level_row('$1',$2);",$content["all"]);

		//reads all active category IDs beginning with the current cat ID - without HOME
		$content["cat_path"] = get_active_categories($content["struct"], $content["cat_id"]);

		// some general list replacements first
		$content["all"] = str_replace('{NAV_LIST}', '{NAV_LIST:0}', $content["all"]);
		$content["all"] = str_replace('{NAV_LIST_TOP}', css_level_list($content["struct"], $content["cat_path"], 0, '', 1), $content["all"]);
		$content["all"] = str_replace('{NAV_LIST_CURRENT}', css_level_list($content["struct"], $content["cat_path"], $content["cat_id"]), $content["all"]);

		// list based navigation starting at given level
		$replace = 'nav_list_struct($content["struct"],$content["cat_id"],"$1", "$2");';
		$content["all"] = preg_replace('/\{NAV_LIST:(\d+):{0,1}(.*){0,1}\}/e', $replace, $content["all"]);

		// List based navigation with Top Level - default settings
		// creates a list styled top nav menu, + optional Home | {NAV_LIST_TOP:home_name:class_name} | default class name = list_top
		$content["all"] = preg_replace('/\{NAV_LIST_TOP:(.*?):(.*?)\}/e', 'css_level_list($content["struct"], $content["cat_path"], 0, "$1", 1, "$2")', $content["all"]);

		// List based navigation with Top Level - default settings
		// creates a list styled nav menu of current level {NAV_LIST_CURRENT:1:back_name:class_name} | default class name = list_top
		$content["all"] = preg_replace('/\{NAV_LIST_CURRENT:(\d+):(.*?):(.*?)\}/e', 'css_level_list($content["struct"],$content["cat_path"],$content["cat_id"],"$2","$1","$3")', $content["all"]);

		// Table based navigation, outdated
		if(strpos($content["all"],'{NAV_TABLE') !== false) {

			$replace = nav_table_simple_struct($content["struct"], $content["cat_id"]);
			$content["all"] = str_replace('{NAV_TABLE_SIMPLE}', $replace, $content["all"]);

			$content["all"] = str_replace('{NAV_TABLE_COLUMN}', '{NAV_TABLE_COLUMN:0}', $content["all"]);
			$replace = 'nav_table_struct($content["struct"], $content["cat_id"], "$1", $template_default["nav_table_struct"]);';
			$content["all"] = preg_replace('/\{NAV_TABLE_COLUMN:(\d+)\}/e', $replace, $content["all"]);

		}

	}

	$content["all"] = html_parser_deprecated($content["all"]);
}

// date replacement
if(strpos($content["all"],'{DATE_') !== false) {
	$content["all"] = str_replace('{DATE_LONG}',    international_date_format($template_default["date"]["language"], $template_default["date"]["long"]),   $content["all"]);
	$content["all"] = str_replace('{DATE_MEDIUM}',  international_date_format($template_default["date"]["language"], $template_default["date"]["medium"]), $content["all"]);
	$content["all"] = str_replace('{DATE_SHORT}',   international_date_format($template_default["date"]["language"], $template_default["date"]["short"]),  $content["all"]);
	$content["all"] = str_replace('{DATE_ARTICLE}', international_date_format($template_default["date"]["language"], $template_default["date"]["article"],   $content["article_date"]),  $content["all"]);
}

// time replacement
if(strpos($content["all"],'{TIME_') !== false) {
	$content["all"] = str_replace('{TIME_LONG}',    date($template_default["time"]["long"]) ,                            $content["all"] );
	$content["all"] = str_replace('{TIME_SHORT}',   date($template_default["time"]["short"]),                            $content["all"] );
	$content["all"] = str_replace('{TIME_ARTICLE}', date($template_default["time"]["short"] , $content["article_date"]), $content["all"] );
}

// replace custom search form input field and action with right target
if(strpos($content["all"],'###search_input_') !== false) {
	$content["all"] = str_replace('###search_input_field###', 'search_input_field', $content["all"]);
	$content["all"] = str_replace('###search_input_value###', (empty($content["search_word"]) ? '' : $content["search_word"]), $content["all"]);
	// create serahc form action
	if(strpos($content["all"],'###search_input_action:') !== false) {
		$content["all"] = preg_replace('/###search_input_action:(\d+)###/e','get_search_action("$1", $db);', $content["all"]);
	}
}

// related articles based on keywords, inspired by Magnar Stav Johanssen
if(strpos($content["all"],'{RELATED:') !== false) {
	if ($no_content_for_this_page === false && !empty($content["articles"][$aktion[1]]["article_keyword"])) {
		$related_keywords = $content["articles"][$aktion[1]]["article_keyword"];
	} else {
		$related_keywords = '';
	}
	$content["all"] = preg_replace('/\{RELATED:(\d+)\}/e','get_related_articles($related_keywords,$aktion[1],$template_default["related"],"$1",$db);',$content["all"]);
	$content["all"] = preg_replace('/\{RELATED:(\d+):(.*?)\}/e','get_related_articles("$2",$aktion[1],$template_default["related"],"$1",$db);',$content["all"]);
}

// all new article list sorted by date
if(strpos($content["all"],'{NEW:') !== false) {
	$content["all"] = preg_replace('/\{NEW:(\d+):{0,1}(\d+){0,1}\}/e','get_new_articles($template_default["news"],"$1","$2",$db);',$content["all"]);
}

// some more general parsing
$content["all"]	= str_replace('{SITE}', PHPWCMS_URL, $content["all"]);
$content["all"] = str_replace('{RSSIMG}', $template_default["rss"]["image"], $content["all"]);

$content["all"] = html_parser($content["all"]);

$content["all"] = preg_replace_callback('/\[img=(\d+)(.*?){0,1}\](.*?)\[\/img\]/i', 'parse_images', $content["all"]);
$content["all"] = preg_replace_callback('/\[img=(\d+)(.*?){0,1}\]/i', 'parse_images', $content["all"]);
$content["all"] = preg_replace_callback('/\[download=([0-9, ]+?)( template=.*?){0,1}\/\]/i', 'parse_downloads', $content["all"]);
$content["all"] = preg_replace_callback('/\[download=([0-9, ]+?)( template=.*?){0,1}\](.*?)\[\/download\]/is', 'parse_downloads', $content["all"]);

// create link to articles for found keywords
$content["all"] = preg_replace('/\{KEYWORD:(.*?)\}/e', 'get_keyword_link("$1", $db);', $content["all"]);
//}

// include external HTML page but only part between <body></body>
$content["all"] = preg_replace_callback('/\{URL:(.*?)\}/i', 'include_url', $content["all"]);

// special browse the content links: UP, NEXT, PREVIOUS
// echo get_index_link_up('UP')." | ".get_index_link_prev('PREV',1).' | '.get_index_link_next('NEXT',1);
if(strpos($content["all"],'{BROWSE:') !== false) {
	$content["all"] = preg_replace('/\{BROWSE:UP:(.*?)\}/e','get_index_link_up("$1");',$content["all"]);
	$content["all"] = preg_replace('/\{BROWSE:NEXT:(.*?):(0|1)\}/e','get_index_link_next("$1",$2);',$content["all"]);
	$content["all"] = preg_replace('/\{BROWSE:PREV:(.*?):(0|1)\}/e','get_index_link_prev("$1",$2);',$content["all"]);
}

// replace all "hardcoded" global replacement tags
if(count($content['globalRT'])) {
	foreach($content['globalRT'] as $key => $value) {
		if($key != '') {
			$content["all"] = str_replace($key, $value, $content["all"]);
		}
	}
}

// add possible redirection code (article summary) to $block["htmlhead"];
$block["htmlhead"] = $content["redirect"]["code"] . render_PHPcode($block["htmlhead"]) . LF;

if(!defined('PHPWCMS_ALIAS')) {
	define('PHPWCMS_ALIAS', empty($content['struct'][ $content["cat_id"] ]['acat_alias']) ? '' : $content['struct'][ $content["cat_id"] ]['acat_alias'] );
}

// try to include custom functions and replacement tags or what you want to do at this point of the script
// default dir: "phpwcms_template/inc_script/frontend_render"; only *.php files are allowed there
if($phpwcms["allow_ext_render"]) {
	if(count($custom_includes = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_script/frontend_render', 'php'))) {
		foreach($custom_includes as $value) {
			include_once(PHPWCMS_TEMPLATE.'inc_script/frontend_render/'.$value);
		}
	}
}
if(count($phpwcms['modules_fe_render'])) {
	foreach($phpwcms['modules_fe_render'] as $value) {
		include_once($value);
	}
}

// Final Render Device
$content['all'] = render_device($content['all']);

// render frontend edit related content and JavaScript
if(FE_EDIT_LINK) {

	init_frontend_edit_js();
	$content['all'] .= LF . '<div id="fe-link" class="disabled"></div>' . LF;

}

// insert description meta tag if not definied
if(empty($block['custom_htmlhead']['meta.description']) && !empty($content["struct"][$aktion[0]]["acat_info"]) && !stristr($block["htmlhead"], '"description"')) {
	$content['opengraph']['description'] = $content["struct"][$aktion[0]]["acat_info"];
	set_meta('description', $content["struct"][$aktion[0]]["acat_info"]);
}
// add structure level keywords
if(!empty($content['struct'][ $content["cat_id"] ]['acat_keywords'])) {
	$content['all_keywords'] .= ', ' . $content['struct'][ $content["cat_id"] ]['acat_keywords'];
}
// insert keywords meta tag if not yet definied
if(empty($block['custom_htmlhead']['meta.keywords']) && !empty($content['all_keywords']) && !stristr($block["htmlhead"], '"keywords"')) {
	$content['all_keywords'] = convertStringToArray($content['all_keywords']);
	if(count($content['all_keywords'])) {
		set_meta('keywords', implode(', ', $content['all_keywords']));
	}
}

// Built-in Open Graph rendering
if($content['opengraph']['support']) {
	set_meta('og:type', $content['opengraph']['type'], 'property');
	set_meta('og:title', sanitize_replacement_tags($content['opengraph']['title']), 'property');
	if(empty($content['opengraph']['url'])) {
		set_meta('og:url', abs_url(array(), array('phpwcms_output_action', 'print', 'phpwcms-preview', 'unsubscribe', 'subscribe')), 'property');
	} else {
		set_meta('og:url', $content['opengraph']['url'], 'property');
	}

	if(!empty($content['opengraph']['description'])) {
		set_meta('og:description', sanitize_replacement_tags($content['opengraph']['description']), 'property');
	}
	$content['opengraph']['has_image'] = false;
	if(isset($content['images']['shop']) && count($content['images']['shop'])) {
		foreach($content['images']['shop'] as $og_img) {
				$content['opengraph']['has_image'] = true;
				set_meta('og:image', PHPWCMS_URL . 'img/cmsimage.php/500x500x0/'.$og_img['hash'].'.'.$og_img['ext'], 'property', false, true);
		}
	}
	if(isset($content['images']['news']) && count($content['images']['news'])) {
		foreach($content['images']['news'] as $og_img) {
				$content['opengraph']['has_image'] = true;
				set_meta('og:image', PHPWCMS_URL . 'img/cmsimage.php/500x500x0/'.$og_img['id'].'.'.$og_img['ext'], 'property', false, true);
		}
	}
	if(isset($content['images']['article']['image'])) {
		$content['opengraph']['has_image'] = true;
		set_meta('og:image', PHPWCMS_URL . 'img/cmsimage.php/500x500x0/'.$content['images']['article']['hash'].'.'.$content['images']['article']['ext'], 'property');
	}
	if(!$content['opengraph']['has_image'] && is_file(PHPWCMS_TEMPLATE.'img/opengraph-default.png')) {
		set_meta('og:image', PHPWCMS_URL.TEMPLATE_PATH.'img/opengraph-default.png', 'property');
	}
}

set_meta('generator', 'phpwcms '.PHPWCMS_VERSION);

// replace Print URL
if(strpos($content["all"], '[PRINT]') !== false) {
	$content["all"] = str_replace('[PRINT]', '<a href="'.rel_url(array('print'=>1),array(), PHPWCMS_ALIAS).'" class="'.$template_default['classes']['link-print'].'" target="_blank" rel="nofollow">', $content["all"]);
	$content["all"] = str_replace('[/PRINT]', '</a>', $content["all"]);
}
if(strpos($content["all"], '[PRINT_PDF]') !== false) {
	$content["all"] = str_replace('[PRINT_PDF]', '<a href="'.rel_url(array('print'=>2),array(), PHPWCMS_ALIAS).'" class="'.$template_default['classes']['link-print-pdf'].'" target="_blank" rel="nofollow">', $content["all"]);
	$content["all"] = str_replace('[/PRINT_PDF]', '</a>', $content["all"]);
}

// some article related "global" replacement tags
if(isset($content['article_livedate'])) {

	$content['all'] = render_cnt_template($content['all'], 'AUTHOR', html_specialchars($content['article_username']));
	$content['all'] = render_cnt_date($content['all'], $content["article_date"], $content['article_livedate'], $content['article_killdate']);
	$content['all'] = render_cnt_template($content['all'], 'CATEGORY', $content['cat']);

} else {

	$content['all'] = render_cnt_template($content['all'], 'AUTHOR', '');
	$content['all'] = render_cnt_date($content['all'], now(), now(), now());
	$content['all'] = render_cnt_template($content['all'], 'CATEGORY', html_specialchars($content['struct'][ $content['cat_id'] ]['acat_name']));

}

// render JavaScript Plugins and/or JavaScript scripts that should be loaded in <head>
$content['all'] = preg_replace_callback('/<!--\s+JS:(.*?)\s+-->/s', 'renderHeadJS', $content['all']);
$content['all'] = preg_replace_callback('/<!--\s+CSS:(.*?)\s+-->/s', 'renderHeadCSS', $content['all']);

// test for frontend.js
if(!isset($GLOBALS['block']['custom_htmlhead']['frontend.js']) && preg_match('/MM_swapImage|BookMark_Page|clickZoom|mailtoLink/', $content['all'])) {
	initFrontendJS();
}

//check for additional template based onLoad JavaScript Code
if($block["jsonload"]) {
	if(empty($pagelayout["layout_jsonload"])) {
		$pagelayout["layout_jsonload"]  = '';
	} else {
		$pagelayout["layout_jsonload"] .= ';';
	}
	$pagelayout["layout_jsonload"]	= convertStringToArray($pagelayout["layout_jsonload"] . $block["jsonload"], ';');
	$block['js_ondomready'][]		= '		' . implode(';'.LF.'	', $pagelayout["layout_jsonload"]) . ';';
	$pagelayout["layout_jsonload"]	= '';
}

// set OnLoad (DomReady) JavaScript
if(count($block['js_ondomready'])) {
	jsOnDomReady(implode(LF, $block['js_ondomready']));
}
// set OnUnLoad JavaScript
if(count($block['js_onunload'])) {
	jsOnUnLoad(implode(LF, $block['js_onunload']));
}
// set Inline JS
if(count($block['js_inline'])) {
	$block['custom_htmlhead']['inline']  = '  <script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
	$block['custom_htmlhead']['inline'] .= implode(LF, $block['js_inline']);
	$block['custom_htmlhead']['inline'] .= LF.SCRIPT_CDATA_END.LF.'  </script>';
}

if(!empty($_GET['highlight'])) {
	$highlight_words = explode(' ', clean_slweg(rawurldecode($_GET['highlight'])));
	$content['all'] = preg_replace_callback("/<!--SEARCH_HIGHLIGHT_START\/\/-->(.*?)<!--SEARCH_HIGHLIGHT_END\/\/-->/si", "pregReplaceHighlightWrapper", $content['all']);
}
$content['all'] = str_replace(array('<!--SEARCH_HIGHLIGHT_START//-->', '<!--SEARCH_HIGHLIGHT_END//-->'), '', $content['all']);

// render content part pagination
if(!empty($_CpPaginate)) {

	$content['all'] = str_replace(array('<!--CP_PAGINATE_START//-->', '<!--CP_PAGINATE_END//-->'), '', $content['all']);

	unset($_getVar['aid'], $_getVar['id']);
	$content['CpPaginateNaviGET']	= returnGlobalGET_QueryString('htmlentities', array(), defined('PHPWCMS_ALIAS') ? array(PHPWCMS_ALIAS) : array());
	if(!empty($content['CpPaginateNaviGET']) && $content['CpPaginateNaviGET']{0} == '?') {
		$content['CpPaginateNaviGET'] = '&amp;'.substr($content['CpPaginateNaviGET'], 1);
	}

	// first build [1][2][3] paginate pages
	if(strpos($content['all'], '{CP_PAGINATE}')) {
		$content['CpPaginateNavi'] = array();
		foreach($content['CpPages'] as $key => $value) {

			$content['CpPaginateNavi'][ $key ]  = '	<a href="index.php?aid='.$aktion[1];
			if($key) {
				$content['CpPaginateNavi'][ $key ] .= '-'.$key;
			}
			$content['CpPaginateNavi'][ $key ] .= $content['CpPaginateNaviGET'].'" class="';
			$content['CpPaginateNavi'][ $key ] .= ($key == $content['aId_CpPage']) ? 'cpPaginateActive' : 'cpPaginate';
			$content['CpPaginateNavi'][ $key ] .= '">'.$value.'</a>';

		}
		$content['all'] = render_cnt_template($content['all'], 'CP_PAGINATE', implode(LF, $content['CpPaginateNavi']));
	}

	// is there PREV
	if(in_array($content['CpPages'][ $content['aId_CpPage'] ] - 1, $content['CpPages'])) {

		$key = array_search($content['CpPages'][ $content['aId_CpPage'] ] - 1, $content['CpPages']);
		$value = 'index.php?aid='.$aktion[1];
		if($key) { $value .= '-'.$key; }
		$content['all'] = render_cnt_template($content['all'], 'CP_PAGINATE_PREV', $value);

	} else {
		$content['all'] = render_cnt_template($content['all'], 'CP_PAGINATE_PREV');
	}

	// is there NEXT
	if(in_array($content['CpPages'][ $content['aId_CpPage'] ] + 1, $content['CpPages'])) {

		$key = array_search($content['CpPages'][ $content['aId_CpPage'] ] + 1, $content['CpPages']);
		$value = 'index.php?aid='.$aktion[1];
		if($key) { $value .= '-'.$key; }
		$content['all'] = render_cnt_template($content['all'], 'CP_PAGINATE_NEXT', $value);

	} else {
		$content['all'] = render_cnt_template($content['all'], 'CP_PAGINATE_NEXT');
	}

	// search for content part pagination title menu
	if(strpos($content['all'], '[CP_PAGINATE_MENU')) {

		/**
		 * search for custom cp menu parameters
		 *
		 * [0] => item_prefix
		 * [1] => item_suffix
		 * [2] => active_class
		 * [3] => hide_active
		 * [4] => menu_prefix
		 * [5] => menu_suffix
		 */
		if( preg_match('/\[CP_PAGINATE_MENU:(.*?)\]/', $content['all'], $match) ) {

			$content['all']					= str_replace($match[0], '[CP_PAGINATE_MENU]', $content['all']);
			$content['CpTitleParams']		= explode('|', $match[1]);
			if(!isset($content['CpTitleParams'][1])) {
				$content['CpTitleParams'][1] = '';
			}
			$content['CpTitleParams'][2] = empty($content['CpTitleParams'][2]) ? '' : trim($content['CpTitleParams'][2]);
			$content['CpTitleParams'][3] = empty($content['CpTitleParams'][3]) ? 0 : 1 ;
			$content['CpTitleParams'][4]	= '';
			$content['CpTitleParams'][5]	= '';

		} else {

			$content['CpTitleParams'][0]	= '<li>';
			$content['CpTitleParams'][1]	= '</li>';
			$content['CpTitleParams'][2]	= 'active';
			$content['CpTitleParams'][3]	= 0;
			$content['CpTitleParams'][4]	= '<ul class="cpmenu">';
			$content['CpTitleParams'][5]	= '</ul>';

		}

		$content['CpTitleMenu'] = array();

		// cp menu items
		foreach($content['CpPageTitles'] as $key => $value) {

			$content['CpItem']  = '<a href="index.php?aid='.$aktion[1];

			if($key) {
				$content['CpItem'] .= '-'.$key;
			}

			$content['CpItem'] .= $content['CpPaginateNaviGET'].'"';

			if($key == $content['aId_CpPage']) {

				if(!empty($content['CpTitleParams'][3])) {
					continue;
				}

				if(!empty($content['CpTitleParams'][2])) {
					$content['CpItem'] .= ' class="'.$content['CpTitleParams'][2].'"';
				}

			}

			$content['CpItem'] .= '>'.html_specialchars($value).'</a>';
			$content['CpTitleMenu'][] = $content['CpTitleParams'][0] . $content['CpItem'] . $content['CpTitleParams'][1];
		}

		// cp menu prefix/suffix
		if(count($content['CpTitleMenu'])) {
			$content['CpTitleMenu'][] = $content['CpTitleParams'][5];
			array_unshift($content['CpTitleMenu'], $content['CpTitleParams'][4]);
		}

		$content['all'] = render_cnt_template($content['all'], 'CP_PAGINATE_MENU', implode(LF, $content['CpTitleMenu']));
	}


} elseif(strpos($content['all'], 'CP_PAGINATE')) {

	// remove CP_paginate block
	$content['all'] = replace_tmpl_section('CP_PAGINATE', $content['all']);

}

// check if print mode - then try to replace "no-print" sections from source
if(strpos($content['all'], '--NO_PRINT')) {
	if($aktion[2] == 1) {

		$content['all'] = replace_tmpl_section('NO_PRINT', $content['all']);
		$block['css']	= array('print_layout.css');

	} else {

		$content['all'] = str_replace(array('<!--NO_PRINT_START//-->', '<!--NO_PRINT_END//-->'), '', $content['all']);

	}
}

// now clean up special sections in case user is logged in OR not
if(strpos($content['all'], '--LOGGED_')) {

	if( _getFeUserLoginStatus() ) {
		// if user IS logged in
		$content['all'] = str_replace(array('<!--LOGGED_IN_START//-->', '<!--LOGGED_IN_END//-->'), '', $content['all']);
		$content['all'] = replace_tmpl_section('LOGGED_OUT', $content['all']);
	} else {
		// user is NOT logged
		$content['all'] = str_replace(array('<!--LOGGED_OUT_START//-->', '<!--LOGGED_OUT_END//-->'), '', $content['all']);
		$content['all'] = replace_tmpl_section('LOGGED_IN', $content['all']);
	}

}

$content['all'] = preg_replace_callback('/\[HTML\](.*?)\[\/HTML\]/s', 'convert2html', $content['all'] );
$content['all'] = preg_replace_callback('/\[HTML_SPECIAL\](.*?)\[\/HTML_SPECIAL\]/s', 'convert2htmlspecialchars' , $content['all'] );

parse_CKEDitor_resized_images();

// cleanup document to enhance XHTML Strict compatibility
if($phpwcms['mode_XHTML'] === 3) {

	$phpwcms['html5shiv_disabled'] = empty($phpwcms['html5shiv_disabled']) ? false : true;
	$phpwcms['respondjs_disabled'] = empty($phpwcms['respondjs_disabled']) ? false : true;

	// put it as first item
	if(!$phpwcms['html5shiv_disabled'] && !$phpwcms['respondjs_disabled']) {
		$block['custom_htmlhead']['html5shiv'] = '  <!--[if lt IE 9]>
	<script src="'.PHPWCMS_URL.TEMPLATE_PATH.'lib/html5shiv/html5shiv.js"></script>
	<script src="'.PHPWCMS_URL.TEMPLATE_PATH.'lib/respond/respond.min.js"></script>
  <![endif]-->';
	} elseif(!$phpwcms['html5shiv_disabled']) {
		$block['custom_htmlhead']['html5shiv'] = '  <!--[if lt IE 9]><script src="'.PHPWCMS_URL.TEMPLATE_PATH.'lib/html5shiv/html5shiv.js"></script><![endif]-->';
	} elseif(!$phpwcms['respondjs_disabled']) {
		$block['custom_htmlhead']['respondjs'] = '  <!--[if lt IE 9]><script src="'.PHPWCMS_URL.TEMPLATE_PATH.'lib/respond/respond.min.js"></script><![endif]-->';
	}

} elseif($phpwcms['mode_XHTML'] === 2) {

	$content['all'] = preg_replace(array('/ border="[0-9]+?"/', '/ target=".+?"/'), '', $content['all'] );

}

// PixelRatio Check based on JavaScript and Cookie
if(!empty($GLOBALS['phpwcms']['detect_pixelratio']) && $phpwcms['USER_AGENT']['pixelratio'] == 1 && empty($_COOKIE['phpwcms_pixelratio'])) {
	$block['custom_htmlhead']['pixelratio']  = '  <script'.SCRIPT_ATTRIBUTE_TYPE.'>' . LF;
	$block['custom_htmlhead']['pixelratio'] .= '	var phpwcms_pixelratio = 1;' . LF;
	$block['custom_htmlhead']['pixelratio'] .= '	if(window.devicePixelRatio !== undefined) phpwcms_pixelratio = window.devicePixelRatio;' . LF;
	$block['custom_htmlhead']['pixelratio'] .= '	document.cookie = "phpwcms_pixelratio="+phpwcms_pixelratio+";expires='.date('r', now()+2592000).'");' . LF; // 30 days
	$block['custom_htmlhead']['pixelratio'] .= '  </script>';
}

// new $block['custom_htmlhead'] var (array) for usage in own rendering stuff.
// you will be able to use $GLOBALS['block']['custom_htmlhead']['myheadname']
// always check if you want to use same head code only once
if(count($block['custom_htmlhead'])) {

	if(!empty($phpwcms['js_in_body'])) {

		$block['bodyjs_temp'] = '';

		// Ensure jQuery is put as first item
		if(isset($block['custom_htmlhead']['jquery.js'])) {
			$block['bodyjs_temp'] .= $block['custom_htmlhead']['jquery.js'] . LF;
			unset($block['custom_htmlhead']['jquery.js']);
		}

		foreach($block['custom_htmlhead'] as $key => $value) {

			$value = trim($value);

			if(substr($value, 0, 7) == '<script') {
				$block['bodyjs_temp'] .= '  ' . $value . LF;
			} else {
				$block['htmlhead'] .= '  ' . $value . LF;
			}

		}

		array_unshift($block['bodyjs'], $block['bodyjs_temp']);

		unset($block['bodyjs_temp']);

	} else {

		$block["htmlhead"] .= implode(LF, $block['custom_htmlhead']).LF;

	}

	unset($block['custom_htmlhead']);

}

// remove all useless replacement tags
$content["pagetitle"] = sanitize_replacement_tags($content["pagetitle"]);

// now we should search all ?aid=123 and/or ?id=123 and replace'em by the real alias if available
if(strpos($content['all'], 'index.php?aid=') || strpos($content['all'], 'index.php?id=')) {

	if(strpos($content['all'], PHPWCMS_URL.'index.php?')) {
		$all_reset_url = true;
		$content['all'] = str_replace(PHPWCMS_URL.'index.php?', '###PHPWCMS_URL###index.php?', $content['all']);
	} else {
		$all_reset_url = false;
	}

	$match = array();
	preg_match_all('/[^\/]index.php\?(aid|id)=([\d\,]+)(["|&])/', $content['all'], $match);

	if( isset($match[1]) && isset($match[2]) ) {

		$all_id		= array();
		$sql_id		= '';

		$all_aid	= array();
		$sql_aid	= '';

		$all_close	= array();

		$old_style	= false;

		foreach($match[2] as $key => $value) {

			$all_close[ $match[1][$key].$match[2][$key] ] = $match[3][$key];

			if($match[1][$key] == 'id') {

				if(strpos($match[2][$key], ',')) {

					$old_style	= true;

					$this_id	= 0;
					$this_aid	= 0;

					list($this_id, $this_aid) = explode(',', $match[2][$key]);

					if( $this_aid = intval($this_aid) ) {

						$all_aid[ $this_aid ]	= $this_aid;

					} elseif( $this_id = intval($this_id) ) {

						$all_id[ $this_id ]		= $this_id;

					}

				} else {

					$all_id[ $match[2][$key] ]	= $match[2][$key];

				}

			} else {

				$all_aid[ $match[2][$key] ]	= $match[2][$key];

			}

		}

		if(count($all_id)) {
			$sql_id   = "SELECT 'id' AS alias_type, acat_id AS id, 0 AS aid, acat_alias AS alias FROM ".DB_PREPEND.'phpwcms_articlecat ';
			$sql_id  .= 'WHERE acat_id IN (' . implode(',', $all_id) . ") AND acat_alias != ''";
		}

		if(count($all_aid)) {
			$sql_aid  = "SELECT 'aid' AS alias_type, article_cid AS id, article_id AS aid, article_alias AS alias FROM ".DB_PREPEND.'phpwcms_article ';
			$sql_aid .= 'WHERE article_id IN (' . implode(',', $all_aid) . ") AND article_alias != ''";
		}

		if($sql_id && $sql_aid) {

			$sql = '(' . $sql_id . ') UNION (' . $sql_aid . ')';

		} else {

			$sql = $sql_id . $sql_aid ;

		}

		$match = _dbQuery($sql);

		if(isset($match[0])) {

			foreach($match as $value) {

				$value['alias'] = html_specialchars($value['alias']);

				if($value['alias_type'] == 'id') {

					$content['all'] = str_replace('index.php?id=' . $value['id'] . $all_close['id'.$value['id']], 'index.php?' . $value['alias'] . $all_close['id'.$value['id']], $content['all']);

				} else {

					$content['all'] = str_replace('index.php?aid=' . $value['aid'] . $all_close['aid'.$value['aid']], 'index.php?' . $value['alias'] . $all_close['aid'.$value['aid']], $content['all']);

				}

				// search also for id=0,0,...
				if( $old_style == true ) {

					$value['id'] = $value['id'] . ',' . $value['aid'] . ',0,1,0,0';

					$content['all'] = str_replace('index.php?id=' . $value['id'] . $all_close['id'.$value['id']], 'index.php?' . $value['alias'] . $all_close['id'.$value['id']], $content['all']);

				}
			}
		}
	}

	if($all_reset_url) {
		$content['all'] = str_replace('###PHPWCMS_URL###index.php?', PHPWCMS_URL.'index.php?', $content['all']);
	}
}

// Global parsing for i18 @@Text@@ replacements
if(!empty($phpwcms['i18n_parse'])) {
	$content['all']			= i18n_substitute_text($content['all']);
	$content["pagetitle"]	= i18n_substitute_text($content['pagetitle']);
}

// Replace all deprecated GT Mod tags
if(!empty($phpwcms['gt_mod']) && strpos($content["all"], '{GT') !== false) {

	function deprecated_get_gt_by_style($matches) {
		return '<span class="gt-'.trim($matches[1]).'">' . $matches[2] . '</span>';
	}

	$content["all"] = preg_replace_callback('/\{GT:(.+?)\}(.*?)\{\/GT\}/is', 'deprecated_get_gt_by_style', $content["all"]);
}

?>