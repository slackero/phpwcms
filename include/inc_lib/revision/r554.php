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
 * Revision 554 Update Check
 *
 * @return bool
 */
function phpwcms_revision_r554()
{
    // Update DATE/TIME DEFAULT NULL then update 0000-00-00 00:00:00 by NULL
    // Preserve not required if only 1 column is updated per table or
    // if there is no ON UPDATE CURRENT_TIMESTAMP column
    $updated = [
        'address' => phpwcms_revision_r554_update_datetime(
            'address',
            [
                'address_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'ads_campaign' => phpwcms_revision_r554_update_datetime(
            'ads_campaign',
            [
                'adcampaign_created' => 'DATETIME',
                'adcampaign_changed' => 'DATETIME',
                'adcampaign_datestart' => 'DATETIME',
                'adcampaign_dateend' => 'DATETIME',
            ]
        ),

        'ads_formats' => phpwcms_revision_r554_update_datetime(
            'ads_formats',
            [
                'adformat_created' => 'DATETIME',
                'adformat_changed' => 'DATETIME',
            ]
        ),

        'ads_place' => phpwcms_revision_r554_update_datetime(
            'ads_place',
            [
                'adplace_created' => 'DATETIME',
                'adplace_changed' => 'DATETIME',
            ]
        ),

        'ads_tracking' => phpwcms_revision_r554_update_datetime(
            'ads_tracking',
            [
                'adtracking_created' => 'DATETIME',
            ]
        ),

        'article' => phpwcms_revision_r554_update_datetime(
            'article',
            [
                'article_begin' => 'DATETIME',
                'article_end' => 'DATETIME',
                'article_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ],
            [
                'article_tstamp',
            ]
        ),

        'articlecat' => phpwcms_revision_r554_update_datetime(
            'articlecat',
            [
                'acat_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'articlecontent' => phpwcms_revision_r554_update_datetime(
            'articlecontent',
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

        'calendar' => phpwcms_revision_r554_update_datetime(
            'calendar',
            [
                'calendar_created' => 'DATETIME',
                'calendar_changed' => 'DATETIME',
                'calendar_start' => 'DATETIME',
                'calendar_end' => 'DATETIME',
                'calendar_range_start' => 'DATE',
                'calendar_range_end' => 'DATE',
            ]
        ),

        'categories' => phpwcms_revision_r554_update_datetime(
            'categories',
            [
                'cat_createdate' => 'DATETIME',
                'cat_changedate' => 'DATETIME',
            ]
        ),

        'chat' => phpwcms_revision_r554_update_datetime(
            'chat',
            [
                'chat_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'content' => phpwcms_revision_r554_update_datetime(
            'content',
            [
                'cnt_livedate' => 'DATETIME',
                'cnt_killdate' => 'DATETIME',
            ]
        ),

        'country' => phpwcms_revision_r554_update_datetime(
            'country',
            [
                'country_updated' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'file' => phpwcms_revision_r554_update_datetime(
            'file',
            [
                'f_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'formresult' => phpwcms_revision_r554_update_datetime(
            'formresult',
            [
                'formresult_createdate' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'formtracking' => phpwcms_revision_r554_update_datetime(
            'formtracking',
            [
                'formtracking_created' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'glossary' => phpwcms_revision_r554_update_datetime(
            'glossary',
            [
                'glossary_created' => 'DATETIME',
                'glossary_changed' => 'DATETIME',
            ]
        ),

        'keyword' => phpwcms_revision_r554_update_datetime(
            'keyword',
            [
                'keyword_updated' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'log' => phpwcms_revision_r554_update_datetime(
            'log',
            [
                'log_created' => 'DATETIME',
            ]
        ),

        'log_seo' => phpwcms_revision_r554_update_datetime(
            'log_seo',
            [
                'create_date' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'message' => phpwcms_revision_r554_update_datetime(
            'message',
            [
                'msg_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'newsletter' => phpwcms_revision_r554_update_datetime(
            'newsletter',
            [
                'newsletter_created' => 'TIMESTAMP',
                'newsletter_lastsending' => 'TIMESTAMP',
                'newsletter_changed' => 'CURRENT_TIMESTAMP_UPDATE',
            ],
            [
                'newsletter_changed',
            ]
        ),

        'newsletterqueue' => phpwcms_revision_r554_update_datetime(
            'newsletterqueue',
            [
                'queue_created' => 'TIMESTAMP',
                'queue_changed' => 'TIMESTAMP',
            ]
        ),

        'redirect' => phpwcms_revision_r554_update_datetime(
            'redirect',
            [
                'changed' => 'TIMESTAMP',
            ]
        ),

        'shop_orders' => phpwcms_revision_r554_update_datetime(
            'shop_orders',
            [
                'order_date' => 'DATETIME',
            ]
        ),

        'shop_products' => phpwcms_revision_r554_update_datetime(
            'shop_products',
            [
                'shopprod_createdate' => 'DATETIME',
                'shopprod_changedate' => 'DATETIME',
            ]
        ),

        'subscription' => phpwcms_revision_r554_update_datetime(
            'subscription',
            [
                'subscription_tstamp' => 'CURRENT_TIMESTAMP',
            ]
        ),

        'user' => phpwcms_revision_r554_update_datetime(
            'user',
            [
                'usr_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

        'userdetail' => phpwcms_revision_r554_update_datetime(
            'userdetail',
            [
                'detail_tstamp' => 'CURRENT_TIMESTAMP_UPDATE',
                'userdetail_lastlogin' => 'DATETIME',
                'detail_birthday' => 'DATE',
            ],
            [
                'detail_tstamp',
            ]
        ),

        'usergroup' => phpwcms_revision_r554_update_datetime(
            'usergroup',
            [
                'group_timestamp' => 'CURRENT_TIMESTAMP_UPDATE',
            ]
        ),

    ];

    return !in_array(false, $updated, true);
}

/**
 * @param $table
 * @param $fields
 * @param $preserve
 * @return bool
 */
function phpwcms_revision_r554_update_datetime($table, $fields, $preserve = [])
{
    if (!$table || !$fields) {
        return false;
    }

    $table = _dbNormalizeTable($table);

    // Skip if table does not exist in this installation
    if (!_dbTableExists($table)) {
        return true;
    }

    $table_name = _dbTableName($table);
    $alter_table = 'ALTER TABLE `' . _dbEscape($table_name, false) . '`';
    $drop = [];
    $update = [];

    foreach ($fields as $field => $type) {
        // check if NULL is already allowed
        $result = _dbQuery('SHOW COLUMNS FROM `' . _dbEscape($table_name, false) . '` WHERE Field=' . _dbEscape($field));
        if (!isset($result[0]['Field']) || (isset($result[0]['Null']) && strtoupper($result[0]['Null']) === 'YES')) {
            // column missing in this installation or NULL already allowed — nothing to convert
            unset($fields[$field]);
            continue;
        }

        $type = strtoupper($type);
        $field_escaped = _dbEscape($field, false);
        if ($type === 'DATETIME') {
            $drop[] = 'ALTER `' . $field_escaped . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field_escaped . '` `' . $field_escaped . '` DATETIME NULL';
        } elseif ($type === 'DATE') {
            $drop[] = 'ALTER `' . $field_escaped . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field_escaped . '` `' . $field_escaped . '` DATE NULL';
        } elseif ($type === 'TIMESTAMP') {
            $drop[] = 'ALTER `' . $field_escaped . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field_escaped . '` `' . $field_escaped . '` TIMESTAMP NULL';
        } elseif ($type === 'CURRENT_TIMESTAMP') {
            $drop[] = 'ALTER `' . $field_escaped . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field_escaped . '` `' . $field_escaped . '` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP';
        } elseif ($type === 'CURRENT_TIMESTAMP_UPDATE') {
            $drop[] = 'ALTER `' . $field_escaped . '` DROP DEFAULT';
            $update[] = 'CHANGE `' . $field_escaped . '` `' . $field_escaped . '` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
        }
    }

    // Stop if all fields are already converted
    if (empty($update)) {
        return true;
    }

    $status = false;

    // Drop default if possible (clears invalid zero-date defaults in MySQL strict mode)
    if (!empty($drop)) {
        _dbQuery($alter_table . ' ' . implode(', ', $drop), 'ALTER');
    }

    if (_dbQuery($alter_table . ' ' . implode(', ', $update), 'ALTER')) {
        $status = true;
        $preserve_fields = [];
        if (!empty($preserve)) {
            foreach ($preserve as $preserve_field) {
                $esc_pfield = '`' . _dbEscape($preserve_field, false) . '`';
                $preserve_fields[$preserve_field] = $esc_pfield . '=' . $esc_pfield;
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
            $preserve_clause = '';
            if (!empty($_preserve_fields)) {
                $preserve_clause = ', ' . implode(', ', $_preserve_fields);
            }
            $esc_field = '`' . _dbEscape($field, false) . '`';
            $query = 'UPDATE `' . _dbEscape($table_name, false) . '` SET ' . $esc_field . '=NULL';
            $query .= $preserve_clause . ' WHERE ' . $esc_field . '=' . $value;
            $result = _dbQuery($query, 'UPDATE');
            if (!isset($result['AFFECTED_ROWS'])) {
                $status = false;
            }
        }
    }

    return $status;
}
