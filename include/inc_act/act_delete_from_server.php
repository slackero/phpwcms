<?php
session_start();
$phpwcms = array();

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

//Starte Funktion nur wenn User => Admin
if($_SESSION["wcs_user_admin"] == 1) {
//User ist Admin und klickt "Dateien vom Sever löschen"
if(isset($_GET['deletedeletedfiles']) && intval($_GET['deletedeletedfiles']) == $_SESSION["wcs_user_id"]) {
	
//Funktion startet	
function deleteFilesFromDirectory($deldir){
//Existiert das Verzeichnis?
if (is_dir($deldir)) {
//Verzeichnisrechte auf 0777 setzen	
@mkdir ($deldir, 0777);		
//Verzeichnis öffnen
if ($dir = opendir($deldir)) {
//Alle Files auslesen
while (($file = readdir($dir)) !== false) {
//Standartverzeichnisse beim auslesen ignorieren
if ($file!="." AND $file !="..") {
//Files vom Server löschen
unlink("".$deldir."".$file."");
}
} 
}
//geöffnetes Verzeichnis wieder schließen
closedir($dir);
}
}
}
}

//Funktionsaufruf - Directory immer mit endendem / angeben
deleteFilesFromDirectory(PHPWCMS_ROOT.'/filearchive/can_be_deleted/');
$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string('csrftoken') : $_SESSION['REFERER_URL'];
headerRedirect($ref);
?>
