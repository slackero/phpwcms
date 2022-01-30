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

// Social Bookmarking = bookmark buttons
// [LINK_IT]<tag>{WONG} {DIGG} {DEL}</tag>[/LINK_IT]

$content['all'] = preg_replace_callback('/\[LINK_IT\](.*?)\[\/LINK_IT\]/is', 'createSocialBookmark', $content['all']);

function createSocialBookmark($matches) {

    if(empty($matches[1])) return '';

    if(strpos($matches[1], '<!--DETAIL_ONLY-->') === false) {

        if(empty($GLOBALS['aktion'][1])) {
            $bm['url']  = PHPWCMS_URL.'index.php?id='.$GLOBALS['aktion'][0];
        } else {
            $bm['url']  = PHPWCMS_URL.'index.php?aid='.$GLOBALS['aktion'][1];
        }

    } else {

        if(empty($GLOBALS['aktion'][1])) return '';
        $bm['url']      = PHPWCMS_URL.'index.php?aid='.$GLOBALS['aktion'][1];
        $matches[1]     = str_replace('<!--DETAIL_ONLY-->', '', $matches[1]);

    }

    $bm['uurl']     = $bm['url'];
    $bm['url']      = rawurlencode($bm['url']);

    $bm['title']    = clean_replacement_tags(empty($GLOBALS['content']['article_title']) ? $GLOBALS['content']["pagetitle"] : $GLOBALS['content']['article_title'], '');
    $bm['title']    = cleanUpSpecialHtmlEntities($bm['title']);
    $bm['title']    = rawurlencode($bm['title']);

    $bm['bodytext'] = empty($GLOBALS['content']['article_summary']) ? '' : clean_replacement_tags($GLOBALS['content']['article_summary'], '');
    $bm['bodytext'] = cleanUpSpecialHtmlEntities($bm['bodytext']);
    $bm['bodytext'] = getCleanSubString($bm['bodytext'], 25, '�', 'word');
    $bm['bodytext'] = rawurlencode($bm['bodytext']);

    $bm['topic']    = rawurlencode($GLOBALS['content']['struct'][ $GLOBALS['aktion'][0] ]['acat_name']);

    // now set all sepcific data
    $bm['service']['wong']['url']        = 'http://www.mister-wong.de/index.php?action=addurl';
    $bm['service']['wong']['url']       .= '&amp;bm_url='.$bm['url'];
    $bm['service']['wong']['url']       .= '&amp;bm_description='.$bm['title'];
    $bm['service']['wong']['alt']        = 'Wong It!';

    $bm['service']['furl']['url']        = 'http://www.furl.net/savedialog.jsp?p=1&amp;t='.$bm['title'].'&amp;u='.$bm['url'].'&amp;c=&amp;r=';
    $bm['service']['furl']['alt']        = 'Furl It';

    $bm['service']['spurl']['url']       = 'http://www.spurl.net/spurl.php?title='.$bm['title'].'&amp;url='.$bm['url']; //&blocked=__tags__s';
    $bm['service']['spurl']['alt']       = 'Spurl!';

    $bm['service']['technorati']['url']  = 'http://technorati.com/faves?add='.rawurlencode(PHPWCMS_URL);
    $bm['service']['technorati']['alt']  = 'TechnoratiFaves';

    $bm['service']['delicious']['url']   = 'http://del.icio.us/post?url='.$bm['url'].'&amp;title='.$bm['title'].'&amp;jump=no';
    $bm['service']['delicious']['alt']   = 'Del.icio.us';

    $bm['service']['digg']['url']        = 'http://digg.com/submit?phase=2&amp;url='.$bm['url'].'&amp;title='.$bm['title'];
    $bm['service']['digg']['url']       .= '&amp;bodytext='.$bm['bodytext'].'&amp;topic=';
    $bm['service']['digg']['alt']        = 'DiggIt!';

    $bm['service']['yahoo']['url']       = 'http://myweb2.search.yahoo.com/myresults/bookmarklet?t='.$bm['title'].'&amp;u='.$bm['url'].'&amp;d='.$bm['bodytext'].'&amp;ei='.PHPWCMS_CHARSET;
    $bm['service']['yahoo']['alt']       = 'Save to Yahoo! My Web';

    $bm['service']['google']['url']      = 'http://www.google.com/bookmarks/mark?op=add&amp;bkmk='.$bm['url'].'&amp;title='.$bm['title'].'&amp;annotation='.$bm['bodytext'];
    $bm['service']['google']['alt']      = 'Google Bookmark';

    $bm['service']['magnolia']['url']    = 'http://ma.gnolia.com/bookmarklet/add?url='.$bm['url'].'&amp;title='.$bm['title'].'&amp;description='.$bm['bodytext'];
    $bm['service']['magnolia']['alt']    = 'Ma.gnolia';

    $bm['service']['newsvine']['url']    = 'http://www.newsvine.com/_tools/seed&amp;save?url='.$bm['url'].'&amp;title='.$bm['title'];
    $bm['service']['newsvine']['alt']    = 'Newsvine';

    $bm['service']['reddit']['url']      = 'http://reddit.com/submit?url='.$bm['url'].'&amp;title='.$bm['title'];
    $bm['service']['reddit']['alt']      = 'Reddit';

    $bm['service']['webnews']['url']     = 'http://www.webnews.de/einstellen?url='.$bm['url'].'&amp;title='.$bm['title'].'&amp;desc='.$bm['bodytext'];
    $bm['service']['webnews']['alt']     = 'Diese Nachricht bei Webnews einstellen';

    $bm['service']['wikio']['url']       = 'http://www.wikio.com/vote?url='.$bm['url'];
    $bm['service']['wikio']['alt']       = 'Wikio';

    $bm['service']['yigg']['url']        = 'http://yigg.de/neu?exturl='.$bm['url'].'&amp;exttitle='.$bm['title'].'&amp;extdesc='.$bm['bodytext'];
    $bm['service']['yigg']['alt']        = 'YiGG it';

    $bm['service']['facebook']['url']    = 'http://www.facebook.com/sharer.php?u='.$bm['url'].'&amp;t='.$bm['title'];
    $bm['service']['facebook']['alt']    = 'Share on Facebook';

    $bm['service']['folkd']['url']       = 'http://www.folkd.com/submit/'.$bm['uurl'];
    $bm['service']['folkd']['alt']       = 'folk it!';

    $bm['service']['oneview']['url']     = 'http://www.oneview.de/quickadd/neu/addBookmark.jsf?URL='.$bm['url'].'&amp;title='.$bm['title'];
    $bm['service']['oneview']['alt']     = 'oneview - das merk ich mir!';


    foreach($bm['service'] as $key => $value) {

        $bmt  = '<a href="'.$bm['service'][$key]['url'].'" title="'.$bm['service'][$key]['alt'].'" ';
        $bmt .= 'target="_blank" rel="nofollow"><img src="'.TEMPLATE_PATH.'img/bookmarklets/'.$key.'.gif" ';
        $bmt .= 'alt="'.$bm['service'][$key]['alt'].'" border="0" /></a>';
        $matches[1] = str_replace('{'.strtoupper($key).'}', $bmt, $matches[1]);

    }

    return $matches[1];

}
