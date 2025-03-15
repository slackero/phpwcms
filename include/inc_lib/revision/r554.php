<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

/**
 * Revision 554 Update Check
 *
 * @return bool
 */
function cmsgo_revision_r554() {

    $status = true;

    // do former revision check – fallback to r553
    if(cmsgo_revision_check_temp('553') !== true) {
        $status = cmsgo_revision_check('553');
    }

    if (!$status) {
        return false;
    }

    // Update DATE/TIME DEFAULT NULL then update 0000-00-00 00:00:00 by NULL
    // Preserve not required if only 1 column is updated per table or
    // if there is no ON UPDATE CURRENT_TIMESTAMP column
    $updated = [

        'cmsgo_address' => cmsgo_revision_r554_update_datetime(
            'cmsgo_address',
            [
                'address_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_ads_campaign' => cmsgo_revision_r554_update_datetime(
            'cmsgo_ads_campaign',
            [
                'adcampaign_created' => 'DATETIME',
                'adcampaign_changed' => 'DATETIME',
                'adcampaign_datestart' => 'DATETIME',
                'adcampaign_dateend' => 'DATETIME',
            ]
        ),

        'cmsgo_ads_formats' => cmsgo_revision_r554_update_datetime(
            'cmsgo_ads_formats',
            [
                'adformat_created' => 'DATETIME',
                'adformat_changed' => 'DATETIME',
            ]
        ),

        'cmsgo_ads_place' => cmsgo_revision_r554_update_datetime(
            'cmsgo_ads_place',
            [
                'adplace_created' => 'DATETIME',
                'adplace_changed' => 'DATETIME',
            ]
        ),

        'cmsgo_ads_tracking' => cmsgo_revision_r554_update_datetime(
            'cmsgo_ads_tracking',
            [
                'adtracking_created' => 'DATETIME',
            ]
        ),

        'cmsgo_article' => cmsgo_revision_r554_update_datetime(
            'cmsgo_article',
            [
                'article_begin' => 'DATETIME',
                'article_end' => 'DATETIME',
                'article_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ],
            [
                'article_tstamp',
            ]
        ),

        'cmsgo_articlecat' => cmsgo_revision_r554_update_datetime(
            'cmsgo_articlecat',
            [
                'acat_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_articlecontent' => cmsgo_revision_r554_update_datetime(
            'cmsgo_articlecontent',
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

        'cmsgo_calendar' => cmsgo_revision_r554_update_datetime(
            'cmsgo_calendar',
            [
                'calendar_created' => 'DATETIME',
                'calendar_changed' => 'DATETIME',
                'calendar_start' => 'DATETIME',
                'calendar_end' => 'DATETIME',
                'calendar_range_start' => 'DATE',
                'calendar_range_end' => 'DATE',
            ]
        ),

        'cmsgo_categories' => cmsgo_revision_r554_update_datetime(
            'cmsgo_categories',
            [
                'cat_createdate' => 'DATETIME',
                'cat_changedate' => 'DATETIME',
            ]
        ),

        'cmsgo_chat' => cmsgo_revision_r554_update_datetime(
            'cmsgo_chat',
            [
                'chat_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_content' => cmsgo_revision_r554_update_datetime(
            'cmsgo_content',
            [
                'cnt_livedate' => 'DATETIME',
                'cnt_killdate' => 'DATETIME',
            ]
        ),

        'cmsgo_country' => cmsgo_revision_r554_update_datetime(
            'cmsgo_country',
            [
                'country_updated' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_file' => cmsgo_revision_r554_update_datetime(
            'cmsgo_file',
            [
                'f_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_formresult' => cmsgo_revision_r554_update_datetime(
            'cmsgo_formresult',
            [
                'formresult_createdate' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'cmsgo_formtracking' => cmsgo_revision_r554_update_datetime(
            'cmsgo_formtracking',
            [
                'formtracking_created' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'cmsgo_glossary' => cmsgo_revision_r554_update_datetime(
            'cmsgo_glossary',
            [
                'glossary_created' => 'DATETIME',
                'glossary_changed' => 'DATETIME',
            ]
        ),

        'cmsgo_keyword' => cmsgo_revision_r554_update_datetime(
            'cmsgo_keyword',
            [
                'keyword_updated' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'cmsgo_log' => cmsgo_revision_r554_update_datetime(
            'cmsgo_log',
            [
                'log_created' => 'DATETIME',
            ]
        ),

        'cmsgo_log_seo' => cmsgo_revision_r554_update_datetime(
            'cmsgo_log_seo',
            [
                'create_date' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'cmsgo_message' => cmsgo_revision_r554_update_datetime(
            'cmsgo_message',
            [
                'msg_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_newsletter' => cmsgo_revision_r554_update_datetime(
            'cmsgo_newsletter',
            [
                'newsletter_created' => 'TIMESTAMP',
                'newsletter_lastsending' => 'TIMESTAMP',
                'newsletter_changed' => 'CURRENT_TIMESTAMP_UPDATE',
            ],
            [
                'newsletter_changed',
            ]
        ),

        'cmsgo_newsletterqueue' => cmsgo_revision_r554_update_datetime(
            'cmsgo_newsletterqueue',
            [
                'queue_created' => 'TIMESTAMP',
                'queue_changed' => 'TIMESTAMP',
            ]
        ),

        'cmsgo_redirect' => cmsgo_revision_r554_update_datetime(
            'cmsgo_redirect',
            [
                'changed' => 'TIMESTAMP',
            ]
        ),

        'cmsgo_shop_orders' => cmsgo_revision_r554_update_datetime(
            'cmsgo_shop_orders',
            [
                'order_date' => 'DATETIME',
            ]
        ),

        'cmsgo_shop_products' => cmsgo_revision_r554_update_datetime(
            'cmsgo_shop_products',
            [
                'shopprod_createdate' => 'DATETIME',
                'shopprod_changedate' => 'DATETIME',
            ]
        ),

        'cmsgo_subscription' => cmsgo_revision_r554_update_datetime(
            'cmsgo_subscription',
            [
                'subscription_tstamp' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'cmsgo_user' => cmsgo_revision_r554_update_datetime(
            'cmsgo_user',
            [
                'usr_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'cmsgo_userdetail' => cmsgo_revision_r554_update_datetime(
            'cmsgo_userdetail',
            [
                'detail_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
                'userdetail_lastlogin' => 'DATETIME',
                'detail_birthday' => 'DATE',
            ],
            [
                'detail_tstamp',
            ]
        ),

        'cmsgo_usergroup' => cmsgo_revision_r554_update_datetime(
            'cmsgo_usergroup',
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
function cmsgo_revision_r554_update_datetime($table, $fields, $preserve = []) {

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
