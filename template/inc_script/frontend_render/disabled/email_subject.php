<?php

// Used to replace value of hidden form field by page title
//
// use [%GLOBAL_FORM_SUBJECT%] as value for hidden form field
// in content part form

$content['all'] = str_replace('[%GLOBAL_FORM_SUBJECT%]', trim('web form: ' . html_specialchars($content['article_title'])), $content['all']);
