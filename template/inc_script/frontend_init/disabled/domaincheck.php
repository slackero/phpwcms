<?php

// compare against current domain and redirect to correct if neccessary

//check active Domain
if(isset($LEVEL_ID[1]) && $LEVEL_ID[1] == 1 && strpos(PHPWCMS_URL, 'mydomain1.com') === false) {

    headerRedirect('http://www.mydomain1.com/'.rel_url( array(), array(), '', 'urlencode'));

} elseif(isset($LEVEL_ID[1]) && $LEVEL_ID[1] == 2 && strpos(PHPWCMS_URL, 'mydomain2.com') === false) {

    headerRedirect('http://www.mydomain2.com/'.rel_url( array(), array(), '', 'urlencode'));

}
