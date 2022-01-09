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

$phpwcms = array();

require_once 'include/config/conf.inc.php';

if( !empty($phpwcms['SESSION_FEinit']) ) {
    $phpwcms['SESSION_START'] = true;
}

require_once 'include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
if(empty($phpwcms['sanitize_dlname'])) {
    $phpwcms['sanitize_dlname'] = false;
} else {
    $phpwcms['sanitize_dlname'] = true;
    require_once PHPWCMS_ROOT.'/include/inc_lib/charset_helper.inc.php';
}

// try to get hash for file download
$success    = false;
$hash       = false;
$countonly  = empty($_GET['countonly']) ? false : true;
$hash       = empty($_GET['f']) ? '' : clean_slweg($_GET['f']);

if(isset($_GET['target'])) {
    $phpwcms["inline_download"] = empty($_GET['target']) ? 0 : 1;
} elseif(!isset($phpwcms["inline_download"])) {
    $phpwcms["inline_download"] = 0;
}

if(!empty($hash) && strlen($hash) === 32) {

    require_once PHPWCMS_ROOT.'/include/inc_lib/functions.file.inc.php';
    require_once PHPWCMS_ROOT.'/include/inc_front/front.func.inc.php';

    _checkFrontendUserAutoLogin();

    // get file info - limit 1 entry
    $download = _getFileInfo($hash, 1);

    if(is_array($download) && count($download)) {
        // all we need is the first array value

        $download = current($download);

        // ok fine - we have download information
        // then count up download try for this file
        $sql  = "UPDATE ".DB_PREPEND."phpwcms_file SET f_dlstart=f_dlstart+1 ";
        $sql .= "WHERE f_hash="._dbEscape($download["f_hash"])." LIMIT 1";
        _dbQuery($sql, 'UPDATE');

        $fileinfo = array();

        $fileinfo['filename'] = $download["f_hash"];
        if($download["f_ext"]) {
            $fileinfo['filename'] .= '.'.$download["f_ext"];
        }

        // just count up a download
        if($countonly) {

            $success = true;

        // just use built-in download
        } else {

            $fileinfo['path']       = PHPWCMS_ROOT.$phpwcms["file_path"];
            $fileinfo['filesize']   = $download['f_size'];
            $fileinfo['method']     = empty($phpwcms["inline_download"]) ? 'attachment' : 'inline';
            $fileinfo['mimetype']   = $download["f_type"];
            $fileinfo['file']       = $fileinfo['path'].$fileinfo['filename'];
            $fileinfo['extension']  = $download["f_ext"];
            $fileinfo['realfname']  = $phpwcms['sanitize_dlname'] ? phpwcms_remove_accents($download["f_name"]) : $download["f_name"];

            // start download
            $success = dl_file_resume($fileinfo['file'], $fileinfo, true);

        }
    }

// we hack in the stream.php here
} elseif( ($file = isset($_GET['file']) ? clean_slweg($_GET['file'], 40) : '') ) {

    $filename   = basename($file);
    $file       = PHPWCMS_ROOT.'/'.PHPWCMS_FILES . $filename;

    if(is_file($file)) {

        $mime = empty($_GET['type']) ? '' : clean_slweg($_GET['type'], 100);

        if(!is_mimetype_format($mime)) {
            $mime = get_mimetype_by_extension( which_ext($file) );
        }

        header('Content-Type: ' . $mime);

        if(BROWSER_OS == 'iOS') {

            require_once PHPWCMS_ROOT.'/include/inc_lib/functions.file.inc.php';

            rangeDownload($file);

        } else {

            header('Content-Transfer-Encoding: binary');
            if(!isset($_GET['ios'])) {
                header('Content-Disposition: inline; filename="'.($phpwcms['sanitize_dlname'] ? phpwcms_remove_accents($filename) : $filename).'"');
            }
            header('Content-Length: ' . filesize($file));

            readfile($file);

        }

        $success = true;

    }

}

if($success) {

    if(!empty($download["f_hash"])) {
        $sql  = "UPDATE ".DB_PREPEND."phpwcms_file SET f_dlfinal=f_dlfinal+1 ";
        $sql .= "WHERE f_hash="._dbEscape($download["f_hash"])." LIMIT 1";
        _dbQuery($sql, 'UPDATE');
    }

    if($countonly) {

        headerRedirect(PHPWCMS_URL . PHPWCMS_FILES . $fileinfo['filename']);

    }

} else {

    headerRedirect('', 404);
    echo '<h1>404 File Not Found</h1>';

}

exit();
