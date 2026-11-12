<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

/**
 * Revision 562:
 * - The site index page is now stored as a regular article category row
 *   with acat_id=0 in phpwcms_articlecat, like every other category.
 *   include/config/conf.indexpage.inc.php stays as fallback and readable
 *   mirror.
 * - Seed that row for existing installs from the current config file.
 * - MySQL would assign a new AUTO_INCREMENT value for an explicit 0, so the
 *   insert runs with NO_AUTO_VALUE_ON_ZERO for this session.
 *
 * @return bool
 */
function phpwcms_revision_r562() {

    $exists = _dbQuery('SELECT acat_id FROM ' . DB_PREPEND . 'articlecat WHERE acat_id=0 LIMIT 1');

    if (isset($exists[0]['acat_id'])) {
        return true;
    }

    $indexpage = array();
    $indexpage_file = PHPWCMS_ROOT . '/include/config/conf.indexpage.inc.php';

    if (is_file($indexpage_file)) {
        include $indexpage_file;
    }

    $defaults = array(
        'acat_name'       => 'Home',
        'acat_title'      => '',
        'acat_info'       => '',
        'acat_alias'      => 'index',
        'acat_aktiv'      => 1,
        'acat_template'   => 0,
        'acat_hidden'     => 0,
        'acat_ssl'        => 0,
        'acat_regonly'    => 0,
        'acat_topcount'   => -1,
        'acat_maxlist'    => 0,
        'acat_redirect'   => '',
        'acat_order'      => 0,
        'acat_timeout'    => '',
        'acat_nosearch'   => '',
        'acat_nositemap'  => 1,
        'acat_permit'     => '',
        'acat_cntpart'    => '',
        'acat_pagetitle'  => '',
        'acat_paginate'   => 0,
        'acat_overwrite'  => '',
        'acat_archive'    => 0,
        'acat_class'      => '',
        'acat_keywords'   => '',
        'acat_cpdefault'  => 0,
        'acat_disable301' => 0,
        'acat_opengraph'  => 1,
        'acat_canonical'  => '',
        'acat_breadcrumb' => 0,
        'acat_onepage'    => 0,
    );

    $indexpage = array_merge($defaults, $indexpage);

    $values = array(
        'acat_name'       => $indexpage['acat_name'],
        'acat_title'      => $indexpage['acat_title'],
        'acat_info'       => $indexpage['acat_info'],
        'acat_alias'      => $indexpage['acat_alias'],
        'acat_aktiv'      => (int)$indexpage['acat_aktiv'],
        'acat_template'   => (int)$indexpage['acat_template'],
        'acat_hidden'     => (int)$indexpage['acat_hidden'],
        'acat_ssl'        => (int)$indexpage['acat_ssl'],
        'acat_regonly'    => (int)$indexpage['acat_regonly'],
        'acat_topcount'   => (int)$indexpage['acat_topcount'],
        'acat_maxlist'    => (int)$indexpage['acat_maxlist'],
        'acat_redirect'   => $indexpage['acat_redirect'],
        'acat_order'      => (int)$indexpage['acat_order'],
        'acat_cache'      => $indexpage['acat_timeout'],
        'acat_nosearch'   => $indexpage['acat_nosearch'],
        'acat_nositemap'  => (int)$indexpage['acat_nositemap'],
        'acat_permit'     => is_array($indexpage['acat_permit']) ? implode(',', $indexpage['acat_permit']) : $indexpage['acat_permit'],
        'acat_cntpart'    => $indexpage['acat_cntpart'],
        'acat_pagetitle'  => $indexpage['acat_pagetitle'],
        'acat_paginate'   => (int)$indexpage['acat_paginate'],
        'acat_overwrite'  => $indexpage['acat_overwrite'],
        'acat_archive'    => (int)$indexpage['acat_archive'],
        'acat_class'      => $indexpage['acat_class'],
        'acat_keywords'   => $indexpage['acat_keywords'],
        'acat_cpdefault'  => (int)$indexpage['acat_cpdefault'],
        'acat_disable301' => (int)$indexpage['acat_disable301'],
        'acat_opengraph'  => (int)$indexpage['acat_opengraph'],
        'acat_canonical'  => $indexpage['acat_canonical'],
        'acat_breadcrumb' => (int)$indexpage['acat_breadcrumb'],
        'acat_onepage'    => (int)$indexpage['acat_onepage'],
        'acat_struct'     => 0,
        'acat_sort'       => 0,
        'acat_trash'      => 0,
    );

    return set_indexpage_data($values);
}
