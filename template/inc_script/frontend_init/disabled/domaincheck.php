<?php

// compare against current domain and redirect to correct if neccessary

if(isset($LEVEL_ID[1])) {
 //check active Domain
 if($LEVEL_ID[1] == 1 && strpos(PHPWCMS_URL, 'mydomain1.com') === false) {
 
 	headerRedirect('http://www.mydomain1.com/index.php'.returnGlobalGET_QueryString());
 
 }
 
 if($LEVEL_ID[1] == 2 && strpos(PHPWCMS_URL, 'mydomain2.com') === false) {
 
 	headerRedirect('http://www.mydomain2.com/index.php'.returnGlobalGET_QueryString());
 
 }


}

?>