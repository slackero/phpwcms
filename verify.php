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

// redirect verify to correct newsletter action

$phpwcms = array();
require_once 'include/config/conf.inc.php';
require_once 'include/inc_lib/default.inc.php';

$type   = '';
$email  = 'n.a.';

if(!empty($_GET['s']) || !empty($_GET['u'])) {

    require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
    require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
    require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

    if(isset($_GET['s'])) {

        $hash = clean_slweg($_GET['s']);
        $type = 'subscribe';

    } else {

        $hash = clean_slweg($_GET['u']);
        $type = 'unsubscribe';

    }

    $data = _dbQuery('SELECT * FROM '.DB_PREPEND."phpwcms_address WHERE address_key='".aporeplace($hash)."' LIMIT 1");

    if(isset($data[0])) {

        // fix old hash where containing "+" char might result in an invalid hash key
        $hash = str_replace(' ', '+', $hash);

        $email = $data[0]['address_email'];
        switch($type) {

            case 'subscribe':       $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_address ';
                                    $sql .= 'SET address_verified=1, address_tstamp=NOW() ';
                                    $sql .= "WHERE address_key='".aporeplace($hash)."'";
                                    if(isset($data[0]['address_verified'])) {
                                        $result = _dbQuery($sql, 'UPDATE');
                                    }
                                    if(!empty($data[0]['address_url1'])) {
                                        headerRedirect($data[0]['address_url1']);
                                    }

                                    if(!($page = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/subscribe.tmpl'))) {

                                        $page = "The email address <strong>{EMAIL}</strong> was verified.";

                                    }
                                    break;


            case 'unsubscribe':     $sql  = 'DELETE FROM '.DB_PREPEND.'phpwcms_address ';
                                    $sql .= "WHERE address_key='".aporeplace($hash)."'";
                                    $result = _dbQuery($sql, 'DELETE');
                                    if(!empty($data[0]['address_url2'])) {
                                        headerRedirect($data[0]['address_url2']);
                                    }

                                    if(!($page = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/unsubscribe.tmpl'))) {

                                        $page = "All Subscriptions for <strong>{EMAIL}</strong> canceled.";

                                    }

                                    break;

        }


    } else {

        headerRedirect(PHPWCMS_URL);

    }

} else {

    headerRedirect(PHPWCMS_URL);

}

// some replacements
$page = replaceGlobalRT($page);
$page = str_replace('{EMAIL}', $email, $page);

// send non caching page header
headerAvoidPageCaching();
echo $page;
