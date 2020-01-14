<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 * working Script to edit keywords in table cmsgo_keyword
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// keyword administration

include_once CMSGO_ROOT.'/include/inc_lib/lib.keywords.inc.php';

echo '<h3 class="title">'.$BL['be_admin_keywords'].'</h3>'.LF;

// check if rights to edit keywords
if(!IS_ADMIN) {

    echo '<p>Sorry, you have no rights to edit keywords</p>';

// list keywords
} elseif(empty($_POST['keyword_action'])) {

    echo backend_list_keywords();

// new keyword
} elseif($_POST['keyword_action'] == 'update') {


// update keyword
} elseif($_POST['keyword_action'] == 'edit') {

    echo backend_edit_keywords();

// delete keyword
}  elseif($_POST['keyword_action'] == 'delete') {


// error
} else {

    echo '<p>There seems to be a problem editing keywords. Contact admin.</p>';

}

// old
$keyword["id"] = 0;
