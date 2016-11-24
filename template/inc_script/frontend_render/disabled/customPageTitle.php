<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$my_title = array(

    'cat_name' => 'my default cat name',
    'page_title' => 'my default page name',
    'article_title' => 'my default article title'

                );

if(!empty($content['struct'][$aktion[0]]['acat_name'])) {
    $my_title['cat_name'] = $content['struct'][$aktion[0]]['acat_name'];
}
if(!empty($pagelayout['layout_title'])) {
    $my_title['page_title'] = $pagelayout['layout_title'];
}
if(!empty($row["article_title"])) {
    $my_title['article_title'] = $row["article_title"];
}

$content["pagetitle"] = implode(' / ', $my_title);
