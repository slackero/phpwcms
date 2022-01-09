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

$newsletter                                     = array();

// should show newsletter form
$newsletter["newsletter_id"]                    = intval($_GET["s"]);
$newsletter["newsletter_subject"]               = '';
$newsletter["newsletter_date"]                  = time();
if(!isset($newsletter["newsletter_vars"])) {
    $newsletter["newsletter_vars"]              = array();
}
$newsletter["newsletter_vars"]['from_name']     = '';
$newsletter["newsletter_vars"]['from_email']    = '';
$newsletter["newsletter_vars"]['replyto']       = '';
$newsletter["newsletter_vars"]['html']          = '';
$newsletter["newsletter_vars"]['text']          = '';
$newsletter["newsletter_active"]                = 0;

if(!empty($_GET["del"]) && intval($_GET["del"]) == $newsletter["newsletter_id"]) {

    //delete newsletter now
    $sql  = "UPDATE ".DB_PREPEND."phpwcms_newsletter SET newsletter_trashed=9 ";
    $sql .= "WHERE newsletter_id=".intval($_GET["del"])." LIMIT 1";
    _dbQuery($sql, 'UPDATE');
    headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=messages&p=3');

}

if(isset($_POST["newsletter_id"])) {
    // read the create or edit subscription form data
    $newsletter["newsletter_id"]                    = intval($_POST["newsletter_id"]);
    $newsletter["newsletter_subject"]               = clean_slweg($_POST["newsletter_subject"]);
    if(!$newsletter["newsletter_subject"])  $newsletter['error']['subject'] = 1;

    $newsletter['newsletter_vars']['from_name']     = clean_slweg($_POST["newsletter_fromname"]);
    $newsletter['newsletter_vars']['from_email']    = clean_slweg($_POST["newsletter_fromemail"]);
    if(!is_valid_email($newsletter['newsletter_vars']['from_email'])) {
        $newsletter['error']['from_email']          = 1;
    }
    $newsletter['newsletter_vars']['replyto']       = clean_slweg($_POST["newsletter_replyto"]);
    if(!is_valid_email($newsletter['newsletter_vars']['replyto'])) {
        $newsletter['error']['replyto']             = 1;
    }

    $newsletter['newsletter_vars']['html']          = slweg($_POST["newsletter_html"]);
    $newsletter['newsletter_vars']['text']          = clean_slweg($_POST["newsletter_text"]);
    $newsletter["newsletter_vars"]['template']      = clean_slweg($_POST["newsletter_template"]);

    $newsletter['newsletter_active']                = empty($_POST['newsletter_active']) ? 0 : 1;

    if(!empty($_POST['newsletter_subscription']) && count($_POST['newsletter_subscription'])) {
        foreach($_POST['newsletter_subscription'] as $value) {
            $value = intval($value);
            $newsletter['newsletter_vars']['subscription'][$value] = $value;
        }
    } else {
        $newsletter['newsletter_vars']['subscription'][0] = 0;
    }

    $sql  = "newsletter_subject="._dbEscape($newsletter["newsletter_subject"]).", ";
    $sql .= "newsletter_vars="._dbEscape(serialize($newsletter['newsletter_vars']))." ";

    if($newsletter["newsletter_id"]) {
        $query_mode = 'UPDATE';
        $sql  = "UPDATE ".DB_PREPEND."phpwcms_newsletter SET ".$sql;
        $sql .= "WHERE newsletter_id=".$newsletter["newsletter_id"]." LIMIT 1";
    } else {
        $query_mode = 'INSERT';
        $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_newsletter SET newsletter_created=NOW(), ".$sql;
    }

    if(!isset($newsletter['error'])) {

        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);
        if($query_mode === 'INSERT' && isset($result['INSERT_ID'])) {
            $newsletter["newsletter_id"] = $result['INSERT_ID'];
        }

        // check recipients and subscriptions for building newsletter sending queue
        if($newsletter['newsletter_active']) {

            @set_time_limit(0);

            if($recipients = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_address WHERE address_verified=1')) {

                $queue = array();

                foreach($recipients as $value) {

                    // check which subscription and compare with recipient

                    // check against "all"
                    if(empty($value['address_subscription'])) {

                        $queue[$value['address_id']] = '(NOW(), NOW(), 0, '.$newsletter["newsletter_id"].', '.$value['address_id'].')';

                    } else {

                        $value['address_subscription'] = @unserialize($value['address_subscription']);

                        if(is_array($value['address_subscription']) && count($value['address_subscription'])) {

                        // run all
                        foreach($value['address_subscription'] as $subscr) {

                                if(isset($newsletter['newsletter_vars']['subscription'][intval($subscr)])) {

                                    $queue[$value['address_id']] = '(NOW(), NOW(), 0, '.$newsletter["newsletter_id"].', '.$value['address_id'].')';

                                break;
                            }

                        }

                        // Fallback
                        } else {

                            $queue[$value['address_id']] = '(NOW(), NOW(), 0, '.$newsletter["newsletter_id"].', '.$value['address_id'].')';

                        }

                    }

                    unset($recipients);

                }

                // create entries in the sending queue

                /* queue_status:
                   [0] = unsent
                   [1] = sent
                   [2] = error
                   [3] = reset, will never be sent
                */
                // first reset all unsent queue entries

                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_newsletterqueue SET ';
                $sql .= 'queue_changed=NOW(), queue_status=3 ';
                $sql .= 'WHERE queue_pid='.$newsletter["newsletter_id"].' AND queue_status=0';
                _dbQuery($sql, 'UPDATE');

                // now insert queue entries into db
                $queue = array_chunk($queue, 100, true);

                foreach($queue as $value) {

                    $sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_newsletterqueue ';
                    $sql .= '(queue_created, queue_changed, queue_status, queue_pid, queue_rid) VALUES ';
                    $sql .= implode(', ', $value);

                    _dbQuery($sql, 'INSERT');

                }

            }

        } else {

            // if unmarked -> first remove all unset recipients from queue for same newsletter
            $sql  = 'DELETE FROM '.DB_PREPEND.'phpwcms_newsletterqueue ';
            $sql .= 'WHERE queue_pid='.$newsletter["newsletter_id"].' AND queue_status=0';
            _dbQuery($sql, 'DELETE');

        }

        // update active status
        $sql  = "UPDATE ".DB_PREPEND.'phpwcms_newsletter SET ';
        $sql .= 'newsletter_active='.$newsletter['newsletter_active'].' ';
        $sql .= "WHERE newsletter_id=".$newsletter["newsletter_id"];
        @_dbQuery($sql, 'UPDATE');

        if(isset($_POST['close'])) {
            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=messages&p=3');
        } else {
            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=messages&p=3&s='.$newsletter["newsletter_id"].'&edit=1');
        }
    }
}

if($newsletter["newsletter_id"] && !isset($_POST["newsletter_id"])) {
// read the given subscription datas from db
    $sql  = "SELECT *, UNIX_TIMESTAMP(newsletter_changed) AS newsletter_date FROM ";
    $sql .= DB_PREPEND."phpwcms_newsletter WHERE newsletter_id=".$newsletter["newsletter_id"]." LIMIT 1";
    $result = _dbQuery($sql);
    if(isset($result[0]['newsletter_id'])) {
        $newsletter = $result[0];
        $newsletter['newsletter_vars'] = unserialize($newsletter['newsletter_vars']);
    }
}

if($newsletter["newsletter_id"] && ($newsletter["newsletter_vars"]['html'] || $newsletter["newsletter_vars"]['text']) && !isset($newsletter['error'])) {
    $show_nl_send = 1;
} else {
    $show_nl_send = 0;
}
