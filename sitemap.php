<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// build Google Sitemap based on available articles and news

$phpwcms = [];
require_once __DIR__ . '/include/config/conf.inc.php';

// set necessary charset
$phpwcms['charset'] = 'utf-8';
define('CUSTOM_CONTENT_TYPE', 'Content-Type: text/xml');

require_once __DIR__ . '/include/inc_lib/default.inc.php';

// Caching logic using PHPWCMS_CONTENT to prevent DB/server overload
$cache_dir = PHPWCMS_CONTENT . 'cache';
$cache_file = $cache_dir . '/sitemap.cache.xml';
$cache_time = 12 * 3600; // 12 hours in seconds

if (is_file($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    readfile($cache_file);
    echo '<!-- Created: ' . date('Y-m-d H:i:s', filemtime($cache_file)) . ' -->' . LF;
    exit;
}

ob_start();

define('VISIBLE_MODE', 0);
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/config/conf.indexpage.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_front/front.func.inc.php';

// start XML
echo '<?xml version="1.0" encoding="utf-8"?>' . LF;
echo '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" ';
echo 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
echo 'xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 ';
echo 'http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">' . LF . LF;
echo '  <!-- Google Sitemap, https://www.google.com/webmasters/sitemaps/ -->' . LF . LF;

// reads complete structure as array
$struct = get_struct_data();

// fallback value when no article available
$phpwcms['sitemap_set_base'] = true;
$phpwcms['sitemap_set_default'] = true;

if (is_file(PHPWCMS_ROOT . '/include/config/sitemap.custom.php')) {
    include PHPWCMS_ROOT . '/include/config/sitemap.custom.php';
}

if (function_exists('phpwcms_getCustomSitemap')) {
    $_addURL = phpwcms_getCustomSitemap($struct);
} else {
    $_addURL = [];
}

if ($phpwcms['sitemap_set_default']) {

    // now retrieve all articles
    $sql = "SELECT article_id, article_cid, DATE_FORMAT(article_tstamp, '%Y-%m-%d') AS article_tstamp, ";
    $sql .= 'article_title, article_redirect, article_aliasid, article_alias ';
    $sql .= 'FROM ' . DB_PREPEND . 'phpwcms_article WHERE ';
    $sql .= "article_aktiv=1 AND article_deleted=0 AND article_nosearch!='1' AND ";
    $sql .= 'article_nositemap=1 AND (article_begin IS NULL OR article_begin < NOW()) AND ';
    $sql .= '(article_end IS NULL OR article_end > NOW()) ';
    $sql .= 'ORDER BY article_tstamp DESC';

    $result = _dbQuery($sql);

    if (isset($result[0]['article_id'])) {

        foreach ($result as $data) {

            // first proof if this article is within a "public" structure section
            if (isset($struct[$data['article_cid']])) {
                $_CAT = $struct[$data['article_cid']];
                if ($_CAT['acat_regonly'] || $_CAT['acat_nosearch'] || !$_CAT['acat_nositemap']) {
                    // no it is no public article - so jump to next entry
                    continue;
                }

                // now add article URL to Google sitemap
                echo '  <!-- Article -->' . LF;
                echo '  <url>' . LF;
                echo '      <loc>' . abs_url([], [], empty($data['article_alias']) ? 'aid=' . $data['article_id'] : $data['article_alias']) . '</loc>' . LF;
                echo '      <lastmod>' . $data['article_tstamp'] . '</lastmod>' . LF;
                echo '  </url>' . LF;

                // yes we have a minimum of 1 article link
                $phpwcms['sitemap_set_base'] = false;
            }

        }
    }

    // Generate Sitemap entries for active news
    // Find host articles containing active News content part (type 33)
    $sql_host = 'SELECT ac.acontent_aid, a.article_alias, a.article_cid ';
    $sql_host .= 'FROM ' . DB_PREPEND . 'phpwcms_articlecontent ac ';
    $sql_host .= 'INNER JOIN ' . DB_PREPEND . 'phpwcms_article a ON ac.acontent_aid = a.article_id ';
    $sql_host .= 'WHERE ac.acontent_type = 33 AND ac.acontent_visible = 1 AND ac.acontent_trash = 0 ';
    $sql_host .= 'AND a.article_aktiv = 1 AND a.article_deleted = 0 ';
    $sql_host .= 'ORDER BY ac.acontent_sorting ASC';
    $host_res = _dbQuery($sql_host);

    $news_host_aid = 0;
    $news_host_alias = '';

    if (isset($host_res[0]['acontent_aid'])) {
        foreach ($host_res as $h_data) {
            $cid = $h_data['article_cid'];
            if (isset($struct[$cid])) {
                $_CAT = $struct[$cid];
                if (!($_CAT['acat_regonly'] || $_CAT['acat_nosearch'] || !$_CAT['acat_nositemap'])) {
                    $news_host_aid = $h_data['acontent_aid'];
                    $news_host_alias = empty($h_data['article_alias']) ? 'aid=' . $news_host_aid : $h_data['article_alias'];
                    break; // Found a valid public host article
                }
            }
        }
    }

    if ($news_host_aid > 0) {
        // Retrieve all active news entries
        $sql_news = 'SELECT cnt_id, cnt_alias, ';
        $sql_news .= "DATE_FORMAT(IF(cnt_livedate IS NULL OR cnt_livedate = '0000-00-00 00:00:00', FROM_UNIXTIME(cnt_created), cnt_livedate), '%Y-%m-%d') AS news_date, ";
        $sql_news .= "DATE_FORMAT(IF(cnt_changed > 0, FROM_UNIXTIME(cnt_changed), FROM_UNIXTIME(cnt_created)), '%Y-%m-%d') AS news_tstamp, ";
        $sql_news .= 'IF(UNIX_TIMESTAMP(cnt_livedate) > 0, UNIX_TIMESTAMP(cnt_livedate), cnt_created) AS cnt_ts_livedate ';
        $sql_news .= 'FROM ' . DB_PREPEND . 'phpwcms_content WHERE ';
        $sql_news .= "cnt_module='news' AND cnt_status=1 AND ";
        $sql_news .= "(cnt_livedate IS NULL OR cnt_livedate = '0000-00-00 00:00:00' OR cnt_livedate < NOW()) AND ";
        $sql_news .= "(cnt_killdate IS NULL OR cnt_killdate = '0000-00-00 00:00:00' OR cnt_killdate > NOW() OR cnt_archive_status = 1) ";
        $sql_news .= 'ORDER BY cnt_livedate DESC, cnt_created DESC';

        $news_result = _dbQuery($sql_news);

        if (isset($news_result[0]['cnt_id'])) {
            foreach ($news_result as $n_data) {
                $news_detail_link = date('Ymd', $n_data['cnt_ts_livedate']) . '-' . $news_host_aid . '_';
                $news_detail_link .= empty($n_data['cnt_alias']) ? $n_data['cnt_id'] : urlencode($n_data['cnt_alias']);

                echo '  <!-- News -->' . LF;
                echo '  <url>' . LF;
                echo '      <loc>' . abs_url(['newsdetail' => $news_detail_link], [], $news_host_alias) . '</loc>' . LF;
                echo '      <lastmod>' . $n_data['news_tstamp'] . '</lastmod>' . LF;
                echo '  </url>' . LF;

                $phpwcms['sitemap_set_base'] = false;
            }
        }
    }
}

if (is_file(PHPWCMS_ROOT . '/include/config/sitemap.custom.ini')) {
    $customSitemapLinks = parse_ini_file(PHPWCMS_ROOT . '/include/config/sitemap.custom.ini', true);
    if (is_array($customSitemapLinks) && count($customSitemapLinks)) {
        $_addURL += $customSitemapLinks;
    }
}

if (count($_addURL)) {
    foreach ($_addURL as $value) {

        $_link = empty($value['url']) ? '' : trim($value['url']);
        if (empty($_link)) {
            continue;
        }
        $_lastmod = empty($value['date']) ? '' : trim($value['date']);
        if (empty($value['date'])) {
            $_lastmod = date('Y-m-d');
        }
        echo '  <!-- Custom -->' . LF;
        echo '  <url>' . LF;
        echo '      <loc>' . $_link . '</loc>' . LF;
        echo '      <lastmod>' . $_lastmod . '</lastmod>' . LF;
        echo '  </url>' . LF;

    }
}

if ($phpwcms['sitemap_set_base']) {
    // just return the main URL
    echo '  <url>' . LF;
    echo '      <loc>' . PHPWCMS_URL . '</loc>' . LF;
    echo '      <lastmod>' . date('Y-m-d') . '</lastmod>' . LF;
    echo '  </url>' . LF;
}

echo '</urlset>';

// Save generated output to cache file
$xml_output = ob_get_clean();
if (!is_dir($cache_dir) && !mkdir($cache_dir, 0777, true) && !is_dir($cache_dir)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', $cache_dir));
}
@file_put_contents($cache_file, $xml_output);
echo $xml_output;
