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


//Create new profile data if not existing
$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_userdetail (".
        "detail_pid, detail_title, detail_firstname, detail_lastname, ".
        "detail_company, detail_street, detail_add, detail_city, ".
        "detail_zip, detail_region, detail_country, detail_fon, detail_fax, ".
        "detail_mobile, detail_signature, detail_prof, detail_notes, ".
        "detail_public, detail_newsletter) VALUES (".
        $_SESSION["wcs_user_id"].", '".
        getpostvar($_POST["form_title"])."', '".
        getpostvar($_POST["form_firstname"])."', '".
        getpostvar($_POST["form_lastname"])."', '".
        getpostvar($_POST["form_company"])."', '".
        getpostvar($_POST["form_street"])."', '".
        getpostvar($_POST["form_add"])."', '".
        getpostvar($_POST["form_city"])."', '".
        getpostvar($_POST["form_zip"])."', '".
        getpostvar($_POST["form_region"])."', '".
        getpostvar($_POST["form_country"])."', '".
        getpostvar($_POST["form_fon"])."', '".
        getpostvar($_POST["form_fax"])."', '".
        getpostvar($_POST["form_mobile"])."', '".
        getpostvar(mb_substr($_POST["form_signature"],0,250))."', '".
        getpostvar($_POST["form_prof"])."', '".
        getpostvar(mb_substr($_POST["form_notes"],0,3000))."', ".
        check_checkbox($_POST["form_public"]).", ".
        check_checkbox($_POST["form_newsletter"]).
        ")";

$result = _dbQuery($sql, 'INSERT');

if(empty($result['INSERT_ID'])) {
    $detail_updated = $BL['be_profile_create_success'];
} else {
    $detail_updated = $BL['be_profile_create_error'];
}
