<?php

// this script tries to replace all email addresses by
// code to avoid email harvesting by spam bots
// all "mailto:" links will be replaced by combination
// of onclick javascript function  and "#" anchor

$content['all'] = replaceEmailAddress($content['all']);


function replaceEmailAddress($str) {

    preg_match_all('/<input[^>]+>/U', $str, $input_tags);
    preg_match_all('/<textarea[^>]+>.*<\/textarea>/U', $str, $textarea_tags);
    preg_match_all('/<option[^>]+>/U', $str, $option_tags);

    foreach($input_tags[0] as $key => $value) {
        $str = str_replace($value, '[%---I'.$key.'---%]', $str);
    }
    foreach($textarea_tags[0] as $key => $value) {
        $str = str_replace($value, '[%---T'.$key.'---%]', $str);
    }
    foreach($option_tags[0] as $key => $value) {
        $str = str_replace($value, '[%---O'.$key.'---%]', $str);
    }

    $regex = "([\xA1-\xFEa-z0-9_\.\-]+)(@)([\xA1-\xFEa-z0-9_\-]+\.[\xA1-\xFEa-z0-9\-\._\-]+[\.]*[a-z0-9]\??[\xA1-\xFEa-z0-9=]*)";

    $src = "/href=[\"' ]*mailto:".$regex."[\"']*([^>]+>)/i";
    $tar = 'href="#" onclick="mailtoLink(\'$1\',\'$3\');return false;" title="Email: $1 at $3##--##$4';

    $str = preg_replace($src, $tar, $str);
    $str = preg_replace_callback('/'.$regex.'/i', 'rewriteEmailText', $str);

    foreach($input_tags[0] as $key => $value) {
        $str = str_replace('[%---I'.$key.'---%]', $value, $str);
    }
    foreach($textarea_tags[0] as $key => $value) {
        $str = str_replace('[%---T'.$key.'---%]', $value, $str);
    }
    foreach($option_tags[0] as $key => $value) {
        $str = str_replace('[%---O'.$key.'---%]', $value, $str);
    }

    $str = str_replace('##--##"', '"', $str);
    $str = str_replace('##--##',  '"', $str);

    return $str;
}

function rewriteEmailText($part) {

    $part[1] = str_replace('.', '<script type="text/javascript">
//<![CDATA[
document.write("&#"+"46");
//]]>
</script><noscript>(.)</noscript>', $part[1]);
    $part[2] = '<script type="text/javascript">
//<![CDATA[
document.write("&#"+"64");
//]]>
</script><noscript>(@)</noscript>';
    $part[3] = str_replace('.', '<script type="text/javascript">
//<![CDATA[
document.write("&#"+"46");
//]]>
</script><noscript>(.)</noscript>', $part[3]);

    return $part[1].$part[2].$part[3];

}
