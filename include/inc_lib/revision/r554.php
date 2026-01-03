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

/**
 * Revision 554 Update Check
 *
 * @return bool
 */
function phpwcms_revision_r554() {

    $status = true;

    // do former revision check – fallback to r553
    if (phpwcms_revision_check_temp('553') !== true) {
        $status = phpwcms_revision_check('553');
    }

    if (!$status) {
        return false;
    }

    // Update DATE/TIME DEFAULT NULL then update 0000-00-00 00:00:00 by NULL
    // Preserve not required if only 1 column is updated per table or
    // if there is no ON UPDATE CURRENT_TIMESTAMP column
    $updated = [

        'phpwcms_address' => phpwcms_revision_r554_update_datetime(
            'phpwcms_address',
            [
                'address_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_ads_campaign' => phpwcms_revision_r554_update_datetime(
            'phpwcms_ads_campaign',
            [
                'adcampaign_created' => 'DATETIME',
                'adcampaign_changed' => 'DATETIME',
                'adcampaign_datestart' => 'DATETIME',
                'adcampaign_dateend' => 'DATETIME',
            ]
        ),

        'phpwcms_ads_formats' => phpwcms_revision_r554_update_datetime(
            'phpwcms_ads_formats',
            [
                'adformat_created' => 'DATETIME',
                'adformat_changed' => 'DATETIME',
            ]
        ),

        'phpwcms_ads_place' => phpwcms_revision_r554_update_datetime(
            'phpwcms_ads_place',
            [
                'adplace_created' => 'DATETIME',
                'adplace_changed' => 'DATETIME',
            ]
        ),

        'phpwcms_ads_tracking' => phpwcms_revision_r554_update_datetime(
            'phpwcms_ads_tracking',
            [
                'adtracking_created' => 'DATETIME',
            ]
        ),

        'phpwcms_article' => phpwcms_revision_r554_update_datetime(
            'phpwcms_article',
            [
                'article_begin' => 'DATETIME',
                'article_end' => 'DATETIME',
                'article_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ],
            [
                'article_tstamp',
            ]
        ),

        'phpwcms_articlecat' => phpwcms_revision_r554_update_datetime(
            'phpwcms_articlecat',
            [
                'acat_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_articlecontent' => phpwcms_revision_r554_update_datetime(
            'phpwcms_articlecontent',
            [
                'acontent_created' => 'TIMESTAMP',
                'acontent_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
                'acontent_livedate' => 'DATETIME',
                'acontent_killdate' => 'DATETIME',
            ],
            [
                'acontent_tstamp',
            ]
        ),

        'phpwcms_calendar' => phpwcms_revision_r554_update_datetime(
            'phpwcms_calendar',
            [
                'calendar_created' => 'DATETIME',
                'calendar_changed' => 'DATETIME',
                'calendar_start' => 'DATETIME',
                'calendar_end' => 'DATETIME',
                'calendar_range_start' => 'DATE',
                'calendar_range_end' => 'DATE',
            ]
        ),

        'phpwcms_categories' => phpwcms_revision_r554_update_datetime(
            'phpwcms_categories',
            [
                'cat_createdate' => 'DATETIME',
                'cat_changedate' => 'DATETIME',
            ]
        ),

        'phpwcms_chat' => phpwcms_revision_r554_update_datetime(
            'phpwcms_chat',
            [
                'chat_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_content' => phpwcms_revision_r554_update_datetime(
            'phpwcms_content',
            [
                'cnt_livedate' => 'DATETIME',
                'cnt_killdate' => 'DATETIME',
            ]
        ),

        'phpwcms_country' => phpwcms_revision_r554_update_datetime(
            'phpwcms_country',
            [
                'country_updated' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_file' => phpwcms_revision_r554_update_datetime(
            'phpwcms_file',
            [
                'f_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_formresult' => phpwcms_revision_r554_update_datetime(
            'phpwcms_formresult',
            [
                'formresult_createdate' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'phpwcms_formtracking' => phpwcms_revision_r554_update_datetime(
            'phpwcms_formtracking',
            [
                'formtracking_created' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'phpwcms_glossary' => phpwcms_revision_r554_update_datetime(
            'phpwcms_glossary',
            [
                'glossary_created' => 'DATETIME',
                'glossary_changed' => 'DATETIME',
            ]
        ),

        'phpwcms_keyword' => phpwcms_revision_r554_update_datetime(
            'phpwcms_keyword',
            [
                'keyword_updated' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_log' => phpwcms_revision_r554_update_datetime(
            'phpwcms_log',
            [
                'log_created' => 'DATETIME',
            ]
        ),

        'phpwcms_log_seo' => phpwcms_revision_r554_update_datetime(
            'phpwcms_log_seo',
            [
                'create_date' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'phpwcms_message' => phpwcms_revision_r554_update_datetime(
            'phpwcms_message',
            [
                'msg_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_newsletter' => phpwcms_revision_r554_update_datetime(
            'phpwcms_newsletter',
            [
                'newsletter_created' => 'TIMESTAMP',
                'newsletter_lastsending' => 'TIMESTAMP',
                'newsletter_changed' => 'CURRENT_TIMESTAMP_UPDATE',
            ],
            [
                'newsletter_changed',
            ]
        ),

        'phpwcms_newsletterqueue' => phpwcms_revision_r554_update_datetime(
            'phpwcms_newsletterqueue',
            [
                'queue_created' => 'TIMESTAMP',
                'queue_changed' => 'TIMESTAMP',
            ]
        ),

        'phpwcms_redirect' => phpwcms_revision_r554_update_datetime(
            'phpwcms_redirect',
            [
                'changed' => 'TIMESTAMP',
            ]
        ),

        'phpwcms_shop_orders' => phpwcms_revision_r554_update_datetime(
            'phpwcms_shop_orders',
            [
                'order_date' => 'DATETIME',
            ]
        ),

        'phpwcms_shop_products' => phpwcms_revision_r554_update_datetime(
            'phpwcms_shop_products',
            [
                'shopprod_createdate' => 'DATETIME',
                'shopprod_changedate' => 'DATETIME',
            ]
        ),

        'phpwcms_subscription' => phpwcms_revision_r554_update_datetime(
            'phpwcms_subscription',
            [
                'subscription_tstamp' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'phpwcms_user' => phpwcms_revision_r554_update_datetime(
            'phpwcms_user',
            [
                'usr_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'phpwcms_userdetail' => phpwcms_revision_r554_update_datetime(
            'phpwcms_userdetail',
            [
                'detail_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
                'userdetail_lastlogin' => 'DATETIME',
                'detail_birthday' => 'DATE',
            ],
            [
                'detail_tstamp',
            ]
        ),

        'phpwcms_usergroup' => phpwcms_revision_r554_update_datetime(
            'phpwcms_usergroup',
            [
                'group_timestamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

    ];

    return $status;
}

/**
 * @param $table
 * @param $fields
 * @param $preserve
 * @return bool
 */
function phpwcms_revision_r554_update_datetime($table, $fields, $preserve = []) {

    if (!$table || !$fields) {
        return false;
    }

    $table = _dbEscape($table, false);

    $alter_table = 'ALTER TABLE `' . DB_PREPEND . $table . '`';
    $drop = [];
    $update = [];

    foreach ($fields as $field => $type) {
        // check if NULL is already allowed
        $result = _dbQuery('SHOW COLUMNS FROM `' . DB_PREPEND . $table . '` WHERE Field=' . _dbEscape($field));
        if (isset($result[0]['Null']) && strtoupper($result[0]['Null']) === 'YES') {
            unset($fields[$field]);
            continue;
        }

        $type = strtoupper($type);
        $field = _dbEscape($field, false);
        if ($type === 'DATETIME') {
            $drop[] = 'ALTER `' . $field . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field . '` `' . $field . '` DATETIME NULL';
        } elseif ($type === 'DATE') {
            $drop[] = 'ALTER `' . $field . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field . '` `' . $field . '` DATE NULL';
        } elseif ($type === 'TIMESTAMP') {
            $drop[] = 'ALTER `' . $field . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field . '` `' . $field . '` TIMESTAMP NULL';
        } elseif ($type === 'CURRENT_TIMESTAMP') {
            $drop[] = 'ALTER `' . $field . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field . '` `' . $field . '` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP';
        } elseif ($type === 'CURRENT_TIMESTAMP_UPDATE') {
            $drop[] = 'ALTER `' . $field . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field . '` `' . $field . '` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
        }
    }

    // Stop if all fields are already converted
    if (count($drop) === 0) {
        return true;
    }

    $status = false;

    // Drop default
    if (_dbQuery($alter_table . ' ' . implode(', ', $drop), 'ALTER')) {
        if (_dbQuery($alter_table . ' ' . implode(', ', $update), 'ALTER')) {
            $status = true;
            $preserve_fields = [];
            if ($preserve && count($preserve) > 0) {
                foreach ($preserve as $preserve_field) {
                    $preserve_fields[$preserve_field] = _dbEscape($preserve_field, false) . '=';
                    $preserve_fields[$preserve_field] .= _dbEscape($preserve_field, false);
                }
            }
            foreach ($fields as $field => $type) {
                $type = strtoupper($type);
                if (in_array($type, ['DATETIME', 'TIMESTAMP', 'CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP_UPDATE'])) {
                    $value = "'0000-00-00 00:00:00'";
                } elseif ($type === 'DATE') {
                    $value = "'0000-00-00'";
                } else {
                    continue;
                }

                $_preserve_fields = $preserve_fields;
                unset($_preserve_fields[$field]); // preserve if different from current field
                $preserve = '';
                if (count($_preserve_fields)) {
                    $preserve = ', ' . implode(', ', $_preserve_fields);
                }
                $field = _dbEscape($field, false);
                $query = 'UPDATE `' . DB_PREPEND . $table . '` SET ' . $field . '=NULL';
                $query .= $preserve . ' WHERE ' . $field . '=' . $value;
                $result = _dbQuery($query, 'UPDATE');
                if (!isset($result['AFFECTED_ROWS'])) {
                    $status = false;
                }
            }
        }
    }

    return $status;
}
