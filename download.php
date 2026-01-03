<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array();

require_once 'include/config/conf.inc.php';

if( !empty($cmsgo['SESSION_FEinit']) ) {
    $cmsgo['SESSION_START'] = true;
}

require_once 'include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
if(empty($cmsgo['sanitize_dlname'])) {
    $cmsgo['sanitize_dlname'] = false;
} else {
    $cmsgo['sanitize_dlname'] = true;
    require_once CMSGO_ROOT.'/include/inc_lib/charset_helper.inc.php';
}

// try to get hash for file download
$success    = false;
$hash       = false;
$countonly  = empty($_GET['countonly']) ? false : true;
$hash       = empty($_GET['f']) ? '' : clean_slweg($_GET['f']);

if(isset($_GET['target'])) {
    $cmsgo["inline_download"] = empty($_GET['target']) ? 0 : 1;
} elseif(!isset($cmsgo["inline_download"])) {
    $cmsgo["inline_download"] = 0;
}

if(!empty($hash) && strlen($hash) === 32) {

    require_once CMSGO_ROOT.'/include/inc_lib/functions.file.inc.php';
    require_once CMSGO_ROOT.'/include/inc_front/front.func.inc.php';

    _checkFrontendUserAutoLogin();

    // get file info - limit 1 entry
    $download = _getFileInfo($hash, 1);

    if(is_array($download) && count($download)) {
        // all we need is the first array value

        $download = current($download);

        // ok fine - we have download information
        // then count up download try for this file
        $sql  = "UPDATE ".DB_PREPEND."cmsgo_file SET f_dlstart=f_dlstart+1 ";
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

            $fileinfo['path']       = CMSGO_ROOT.$cmsgo["file_path"];
            $fileinfo['filesize']   = $download['f_size'];
            $fileinfo['method']     = empty($cmsgo["inline_download"]) ? 'attachment' : 'inline';
            $fileinfo['mimetype']   = $download["f_type"];
            $fileinfo['file']       = $fileinfo['path'].$fileinfo['filename'];
            $fileinfo['extension']  = $download["f_ext"];
            $fileinfo['realfname']  = $cmsgo['sanitize_dlname'] ? cmsgo_remove_accents($download["f_name"]) : $download["f_name"];

            // start download
            $success = dl_file_resume($fileinfo['file'], $fileinfo, true);

        }
    }

// we hack in the stream.php here
} elseif( ($file = isset($_GET['file']) ? clean_slweg($_GET['file'], 40) : '') ) {

    $filename   = basename($file);
    $file       = CMSGO_ROOT.'/'.CMSGO_FILES . $filename;

    if(is_file($file)) {

        $mime = empty($_GET['type']) ? '' : clean_slweg($_GET['type'], 100);

        if(!is_mimetype_format($mime)) {
            $mime = get_mimetype_by_extension( which_ext($file) );
        }

        header('Content-Type: ' . $mime);

        if(BROWSER_OS == 'iOS') {

            require_once CMSGO_ROOT.'/include/inc_lib/functions.file.inc.php';

            rangeDownload($file);

        } else {

            header('Content-Transfer-Encoding: binary');
            if(!isset($_GET['ios'])) {
                header('Content-Disposition: inline; filename="'.($cmsgo['sanitize_dlname'] ? cmsgo_remove_accents($filename) : $filename).'"');
            }
            header('Content-Length: ' . filesize($file));

            readfile($file);

        }

        $success = true;

    }

}

if($success) {

    if(!empty($download["f_hash"])) {
        $sql  = "UPDATE ".DB_PREPEND."cmsgo_file SET f_dlfinal=f_dlfinal+1 ";
        $sql .= "WHERE f_hash="._dbEscape($download["f_hash"])." LIMIT 1";
        _dbQuery($sql, 'UPDATE');
    }

    if($countonly) {

        headerRedirect(CMSGO_URL . CMSGO_FILES . $fileinfo['filename']);

    }

} else {

    headerRedirect('', 404);
    echo '<h1>404 File Not Found</h1>';

}

exit();
