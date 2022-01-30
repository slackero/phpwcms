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

$phpwcms = array('SESSION_START' => true);

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

$dl = isset($_GET["dl"]) ? intval($_GET["dl"]) : 0;
$pl = isset($_GET["pl"]) ? intval($_GET["pl"]) : 0;

if($dl) {
    $err = 0;

    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_trash=0 AND f_kid=1 AND f_id=".$dl." ";

    if($pl === 0) {
        if(empty($_SESSION["wcs_user_admin"])) {
            $sql .= "AND f_uid=".intval($_SESSION["wcs_user_id"]).' ';
        }
    } else {
        $sql .= "AND f_aktiv=1 AND (f_public=1";
        if(empty($_SESSION["wcs_user_admin"])) {
            $sql .= " OR f_uid=".intval($_SESSION["wcs_user_id"]);
        }
        $sql .= ") ";
    }

    $sql .= "LIMIT 1";

    $result = _dbQuery($sql);

    if(isset($result[0]['f_id'])) {

        $download = $result[0];

        $dl_filename = $download["f_hash"];
        if($download["f_ext"]) {
            $dl_filename .= '.'.$download["f_ext"];
        }

        $dl_path = PHPWCMS_ROOT.$phpwcms["file_path"];

        if(is_file($dl_path.$dl_filename)) {

            if(!is_mimetype_format($download["f_type"])) {
                $download["f_type"] = get_mimetype_by_extension($download["f_ext"]);
            }

            header("Content-type: ".$download["f_type"]);
            header('Content-Disposition: attachment; filename="'.$download["f_name"].'"');
            header("Content-Length: " . filesize($dl_path.$dl_filename));

            if(readfile($dl_path.$dl_filename)) {
                exit();
            } else {
                $err = 'Error reading file (4)';
            }

        } else {
            $err = 'File does not exist (1)';
        }

    } else {
        $err = 'File not found in database (2)';
    }

} else {

    $err = 'False ID given (3)';

}

if($err):

    $_SESSION = array();
    @session_destroy();

?><html>
<head>
<title>phpwcms File Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>">
<link href="../inc_css/phpwcms.min.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>Download Error</h1>
    <p><strong><?php echo $err ?></strong> occured while trying to download a file of your directory.</p>
    <p>Please <a href="<?php echo PHPWCMS_URL.get_login_file() ?>"><strong>login</strong></a> again and try another file.</p>
    <p>If you think that this might be a technical problem send an email to the webmaster.</p>
</body>
</html>
<?php

    endif;