<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = ['SESSION_START' => true];

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT . '/include/inc_lang/backend/en/lang.inc.php';

//use custom lang if available -> was set in login.php
if (!empty($_SESSION['wcs_user_lang_custom']) && ($temp_lang = substr($_SESSION['wcs_user_lang'], 0, 2)) && is_file(PHPWCMS_ROOT . '/include/inc_lang/backend/' . $temp_lang . '/lang.inc.php')) {
    include PHPWCMS_ROOT . '/include/inc_lang/backend/' . $temp_lang . '/lang.inc.php';
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $temp_lang; ?>">
<head>
    <title>phpwcms: Send Newsletter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />
    <style type="text/css">
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
            background-color: #F1F3F5;
            margin: 0;
            padding: 8px;
            color: #000000;
        }
        a {
            text-decoration: none;
            color: #CC3300;
        }
        a:hover {
            text-decoration: underline;
            color: #CC3300;
        }
    </style>
</head>
<body>
<?php

$newsletter_id = empty($_GET['newsletter_id']) ? 0 : (int)$_GET['newsletter_id'];

if ($newsletter_id) {
    // read the given subscription datas from db
    $sql = 'SELECT *FROM ' . DB_PREPEND . 'phpwcms_newsletter WHERE newsletter_id=' . $newsletter_id . ' LIMIT 1';
    $newsletter = _dbQuery($sql);
    if (isset($newsletter[0]['newsletter_vars'])) {
        $newsletter[0]['newsletter_vars'] = unserialize($newsletter[0]['newsletter_vars'], ['allowed_classes' => false]);
        $newsletter = $newsletter[0];
    } else {
        $newsletter = false;
    }
} else {
    $newsletter = false;
}

if (!$newsletter) {

    echo 'No valid newsletter ID given.';

} elseif ($_SESSION['wcs_user_admin'] == 1) {

    $notest = 1;
    $recipient = [];
    $loop = isset($_GET['loop']) ? (int)$_GET['loop'] : 0;
    if (!$loop) {
        $loop = 25;
    }
    $pause = isset($_GET['pause']) ? (int)$_GET['pause'] : 1;

    //check if a test email should be send
    if (!empty($_GET['send_testemail'])) {

        $notest = 0;

        $test_email_error = [];
        $test_email = clean_slweg($_GET['send_testemail']);
        $test_email = str_replace([' ', ','], ';', $test_email);
        $test_email = convertStringToArray($test_email, ';');

        foreach ($test_email as $test_email_value) {
            if (is_valid_email($test_email_value)) {

                $recipient[] = [
                    'address_name' => 'Newsletter test recipient',
                    'address_email' => $test_email_value,
                    'address_key' => '',
                    'queue_id' => 0
                ];

                echo '<p><strong>' . $BL['be_newsletter_testemail'] . '</strong></p>';

            } else {
                $test_email_error[] = $test_email_value;
            }
        }

        if (count($test_email_error)) {
            echo str_replace('###TEST###', '&nbsp;&#8226; ' . implode('&nbsp;&#8226; ', $test_email_error), $BL['be_newsletter_testerror']);
        }


    } elseif (isset($_GET['send_confirm']) && $_GET['send_confirm'] === 'confirmed') {

        // retrieve all recipients now

        // disable time limit
        if (!$loop) {
            set_time_limit(0);
        }

        // retrieve recipients for current loop
        $sql = 'SELECT address_key, address_email, address_name, queue_id ';
        $sql .= 'FROM ' . DB_PREPEND . 'phpwcms_address ';
        $sql .= 'LEFT JOIN ' . DB_PREPEND . 'phpwcms_newsletterqueue ';
        $sql .= 'ON ' . DB_PREPEND . 'phpwcms_address.address_id = ' . DB_PREPEND . 'phpwcms_newsletterqueue.queue_rid ';
        $sql .= 'WHERE ' . DB_PREPEND . 'phpwcms_newsletterqueue.queue_status=0 AND ';
        $sql .= DB_PREPEND . 'phpwcms_newsletterqueue.queue_pid=' . $newsletter['newsletter_id'];
        if ($loop) {
            $sql .= ' LIMIT ' . $loop;
        }
        $recipient = _dbQuery($sql);

    }

    if (count($recipient)) {

        echo '<p><strong>' . $BL['be_newsletter_to'] . ': </strong><p>';

        // check for newsletter template
        if (!empty($newsletter['newsletter_vars']['template']) && ($template = @file_get_contents(PHPWCMS_TEMPLATE . 'inc_newsletter/' . $newsletter['newsletter_vars']['template'] . '/newsletter.tmpl'))) {
            $template_html = trim(get_tmpl_section('HTML', $template));
            $template_text = trim(get_tmpl_section('TEXT', $template));
            if ($template_html) {
                $newsletter['newsletter_vars']['html'] = str_replace('{CONTENT}', $newsletter['newsletter_vars']['html'], $template_html);
            }
            if ($template_text) {
                $newsletter['newsletter_vars']['text'] = str_replace('{CONTENT}', $newsletter['newsletter_vars']['text'], $template_text);
            }
        }

        if (!empty($newsletter['newsletter_vars']['html'])) {
            $newsletter['newsletter_vars']['html'] = convert_rel2abs($newsletter['newsletter_vars']['html'], PHPWCMS_URL);
        }

        $mail = new PhpwcmsMailer($phpwcms);
        $mail->setFrom($newsletter['newsletter_vars']['from_email'], $newsletter['newsletter_vars']['from_name']);
        $mail->addReplyTo($newsletter['newsletter_vars']['replyto']);
        $mail->Subject = $newsletter['newsletter_subject'];
        $mail->SMTPKeepAlive = true;

        $x = 0;

        foreach ($recipient as $value) {

            if ($x === 20) {
                $mail->smtpClose(); // Manually close the SMTP connection
                $mail->SMTPKeepAlive = true;
            }

            $mail->addAddress($value['address_email'], $value['address_name']);

            if ($newsletter['newsletter_vars']['html'] && $newsletter['newsletter_vars']['text']) {
                //send both TEXT and HTML part
                $mail->Body = build_email_text($newsletter['newsletter_vars']['html'], $value);
                $mail->AltBody = build_email_text($newsletter['newsletter_vars']['text'], $value);
                $mail->isHTML();
            }

            if ($newsletter['newsletter_vars']['html'] && !$newsletter['newsletter_vars']['text']) {
                //send HTML part
                $mailBody = build_email_text($newsletter['newsletter_vars']['html'], $value);
                $mail->Body = $mailBody;
                $mail->isHTML();
                $altBody = new \Html2Text\Html2Text($mailBody);
                $mail->AltBody = $altBody->getText();
            }

            if (!$newsletter['newsletter_vars']['html'] && $newsletter['newsletter_vars']['text']) {
                //send TEXT part
                $mail->Body = build_email_text($newsletter['newsletter_vars']['text'], $value);
                $mail->isHTML(false);
            }

            // update newsletter queue
            $sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_newsletterqueue SET ';
            $sql .= 'queue_changed=NOW(), ';
            if (!($mailresult = $mail->send())) {
                // save error information
                $sql .= 'queue_status=2, ';
                $sql .= 'queue_errormsg=' . _dbEscape($mail->ErrorInfo) . ' ';
            } else {
                // save success
                $sql .= 'queue_status=1 ';
            }
            $sql .= 'WHERE queue_id=' . $value['queue_id'];
            @_dbQuery($sql, 'UPDATE');

            if ($mailresult === false) {
                echo '<p style="color:#CC3300">' . $value['address_email'] . ' (' . $mail->ErrorInfo . ')</p>';
            } else {
                echo '. ';
            }
            flush();

            $mail->clearAddresses();
            $x++;

            if ($loop && $loop === $x) {
                $mail->smtpClose();
                updateSentDate($newsletter['newsletter_id']);
                echo '<script type="text/javascript">' . LF . SCRIPT_CDATA_START . LF;
                echo 'function loopIt() { self.location.href="act_sendnewsletter.php?';
                echo 'newsletter_id=' . $newsletter['newsletter_id'] . '&' . get_token_get_string() . '&';
                echo 'send_confirm=confirmed&loop=' . $loop . '&pause=' . $pause . '"; }' . LF;
                echo 'window.setTimeout("loopIt()", ' . ($pause * 1000) . ')' . LF;
                echo LF . SCRIPT_CDATA_END . LF . '</script></body></html>';

                flush();
                exit();
            }

        }

        $mail->smtpClose();
        updateSentDate($newsletter['newsletter_id']);
        echo '<br /><br />';
        echo $BL['be_newsletter_ready'];

    }

} else {
    echo 'no permission';
}

function build_email_text($text, $value)
{

    //build right message part
    $refkey = rawurlencode($value['address_key']);

    return str_replace(
        [
            '###RECIPIENT_NAME###',
            '###RECIPIENT_EMAIL###',
            '###SITE_URL###',
            '###VERIFY_LINK###',
            '###DELETE_LINK###',
            '###OPENER###',
            'href="download.php',
            'src="img/cmsimage.php',
            'href="http://download.php',
            'src="http://img/cmsimage.php',
        ],
        [
            $value['address_name'],
            $value['address_email'],
            PHPWCMS_URL,
            PHPWCMS_URL . 'verify.php?s=' . $refkey,
            PHPWCMS_URL . 'verify.php?u=' . $refkey,
            '<img src="' . PHPWCMS_URL . 'verify.php?o=' . (int)$value['queue_id'] . '" alt="" />',
            'href="' . PHPWCMS_URL . 'download.php',
            'src="' . PHPWCMS_URL . 'img/cmsimage.php',
            'href="' . PHPWCMS_URL . 'download.php',
            'src="' . PHPWCMS_URL . 'img/cmsimage.php',
        ],
        $text
    );
}

function updateSentDate($id = 0)
{

    $sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_newsletter SET ';
    $sql .= 'newsletter_lastsending=NOW(), ';
    $sql .= 'newsletter_changed=newsletter_changed ';
    $sql .= 'WHERE newsletter_id=' . $id . ' LIMIT 1';
    _dbQuery($sql, 'UPDATE');

}

?>
</body>
</html>
