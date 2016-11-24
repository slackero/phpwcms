<?php

if(defined('FELOGIN_IS_LOGGED')) {

    $FELOGIN_LOGIN = FELOGIN_IS_LOGGED ? 'felogin.logout.html' : 'felogin.login.html';
    $FELOGIN_LOGIN = file_get_contents(PHPWCMS_TEMPLATE.'inc_script/felogin/' . $FELOGIN_LOGIN);

    $FELOGIN_LOGIN = str_replace('{FELOGIN_ACTION}', 'index.php?id=' . $LEVEL_ID[FELOGIN_CHILD_LEVEL], $FELOGIN_LOGIN);


    $FELOGIN_USER_NAME   = empty($_SESSION['FELOGIN_USER_NAME']) ? '' : html_specialchars($_SESSION['FELOGIN_USER_NAME']);

    $content['all'] = str_replace('{FELOGIN}',          $FELOGIN_LOGIN, $content['all']);
    $content['all'] = str_replace('{FELOGIN_USER}',     $FELOGIN_USER_NAME, $content['all']);
    $content['all'] = str_replace('{FELOGOUT_PREFIX}',  FELOGIN_LOGOUT_LINK_PREFIX, $content['all']);
    $content['all'] = str_replace('{FELOGOUT_SUFFIX}',  FELOGIN_LOGOUT_LINK_SUFFIX, $content['all']);

    $FELOGIN_ERROR = count($FELOGIN_ERROR) ? FELOGIN_ERROR_PREFIX . implode('<br />'.LF, $FELOGIN_ERROR) . FELOGIN_ERROR_SUFFIX : '';
    $content['all'] = str_replace('{FELOGIN_ERROR}', $FELOGIN_ERROR, $content['all']);

}
