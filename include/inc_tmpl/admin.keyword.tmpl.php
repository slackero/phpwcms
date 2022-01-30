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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// keyword administration

include_once PHPWCMS_ROOT.'/include/inc_lib/lib.keywords.inc.php';

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
