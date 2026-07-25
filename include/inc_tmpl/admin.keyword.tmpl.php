<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
if (!IS_ADMIN) {

    echo '<div class="alert alert-danger mb-4">Sorry, you have no rights to edit keywords.</div>';

// new/edit keyword form
} elseif (!empty($_POST['keyword_action']) && $_POST['keyword_action'] === 'edit') {

    echo backend_edit_keywords();

// delete keyword
} elseif (!empty($_POST['keyword_action']) && ($_POST['keyword_action'] === 'delete' || $_POST['keyword_action'] === 'delete_single')) {

    backend_delete_keywords();

// list keywords (default)
} else {

    echo backend_list_keywords();

}
