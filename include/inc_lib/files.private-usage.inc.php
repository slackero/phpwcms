<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * File usage / "traffic light" status for the private Dateizentrale.
 *
 * Scope: article content sections/pages (phpwcms_articlecontent) plus the
 * native Kalender/Termine, Glossar and Shop/Produkte modules are checked
 * (each module only if its table actually exists in this installation -
 * see phpwcms_get_module_file_usage() below). Deliberately still NOT
 * covered: the Bannerwerbung module (it never references Dateizentrale
 * files - campaign media is uploaded directly into its own ads directory,
 * not selected from the Filecenter), the newsletter/mail-template module,
 * file paths hardcoded directly inside page templates, and any third-party
 * modules/extensions outside the phpwcms core (their data structure is
 * unknown and can't automatically become known). See CHANGELOG.md for
 * details.
 */

/**
 * Collects every non-deleted, non-trashed article content part once per
 * request, each reduced to: the ids it directly references
 * (acontent_files / acontent_image) plus one combined text blob of the
 * free-text columns (text/html/media/form) that a file hash is searched in
 * - basically every rendered reference to a file embeds its unique f_hash
 * somewhere in the markup (e.g. download.php?f=HASH, or a resized image
 * path) - alongside where that content part lives (article + acontent id),
 * so a match can be turned into a direct edit link.
 *
 * Cached per request (static) since this is potentially called once for
 * every file row in the listing.
 *
 * @return array<int, array{type: string, acontent_id: int, article_id: int, article_title: string, file_ids: array<int,int>, text: string}|array{type: string, entry_id: int, title: string, file_ids: array<int,int>, text: string}>
 */
function phpwcms_get_content_file_usage()
{
    static $rows = null;

    if ($rows !== null) {
        return $rows;
    }

    $rows = [];

    $sql  = 'SELECT arc.acontent_id, arc.acontent_aid, arc.acontent_files, arc.acontent_image, ';
    $sql .= 'arc.acontent_text, arc.acontent_html, arc.acontent_media, arc.acontent_form, ';
    $sql .= 'ar.article_id, ar.article_title ';
    $sql .= 'FROM ' . DB_PREPEND . 'articlecontent AS arc ';
    $sql .= 'INNER JOIN ' . DB_PREPEND . 'article AS ar ON ar.article_id = arc.acontent_aid ';
    $sql .= 'WHERE arc.acontent_trash=0 AND ar.article_deleted=0';

    $result = _dbQuery($sql);

    if (is_array($result)) {

        foreach ($result as $row) {

            $file_ids = [];
            foreach (['acontent_files', 'acontent_image'] as $id_field) {
                if (!empty($row[$id_field])) {
                    foreach (explode(':', $row[$id_field]) as $ref_id) {
                        $ref_id = (int)$ref_id;
                        if ($ref_id > 0) {
                            $file_ids[] = $ref_id;
                        }
                    }
                }
            }

            $text_parts = [];
            foreach (['acontent_text', 'acontent_html', 'acontent_media', 'acontent_form'] as $text_field) {
                if (!empty($row[$text_field])) {
                    $text_parts[] = $row[$text_field];
                }
            }
            $text = $text_parts ? implode("\n", $text_parts) : '';

            // Content parts that reference no file and carry no text cannot
            // match anything - keep them out of the per-request cache.
            if (!$file_ids && $text === '') {
                continue;
            }

            $rows[] = [
                'type'          => 'article',
                'acontent_id'   => (int)$row['acontent_id'],
                'article_id'    => (int)$row['article_id'],
                'article_title' => $row['article_title'],
                'file_ids'      => $file_ids,
                'text'          => $text,
            ];
        }
    }

    $rows = array_merge($rows, phpwcms_get_module_file_usage());

    return $rows;
}

/**
 * Collects file usage from the native Kalender/Termine, Glossar and
 * Shop/Produkte modules - each one only if its table actually exists in
 * this installation (the modules are optional). Produces the same row
 * shape as phpwcms_get_content_file_usage() above (file_ids + a combined
 * text blob, plus a 'type' discriminator so phpwcms_get_file_usage_locations()
 * and phpwcms_render_file_usage_links() can build the right kind of
 * edit link), so both sources can simply be merged.
 *
 * - Kalender: calendar_object (serialized) -> image.id is a real f_id,
 *   plus calendar_text/calendar_teaser as free text.
 * - Glossar: glossary_text as free text only (the glossary module
 *   currently has no image/file picker of its own).
 * - Shop/Produkte: shopprod_var (serialized) -> images[]/files[] each
 *   contain real f_id values, plus the four description fields as free
 *   text.
 *
 * Status 9 means "deleted" in all three modules (the backend soft-deletes
 * via UPDATE ... SET xxx_status=9 rather than an actual DELETE, so the row
 * stays in the table), so those rows are excluded here - from the
 * backend's point of view they no longer exist. Every other status (e.g.
 * inactive/draft) is deliberately left unfiltered, since a draft-status
 * entry can still keep its file in use.
 *
 * Cached per request (static), same reasoning as
 * phpwcms_get_content_file_usage().
 *
 * @return array<int, array{type: string, entry_id: int, title: string, file_ids: array<int,int>, text: string}>
 */
function phpwcms_get_module_file_usage()
{
    static $rows = null;

    if ($rows !== null) {
        return $rows;
    }

    $rows = [];

    // --- Kalender/Termine ---
    if (_dbTableExists('calendar')) {
        $cal_rows = _dbQuery(
            'SELECT calendar_id, calendar_title, calendar_object, calendar_text, calendar_teaser FROM ' .
            _dbTableName('calendar') . ' WHERE calendar_status != 9'
        );
        if (is_array($cal_rows)) {
            foreach ($cal_rows as $row) {

                $file_ids = [];
                if (!empty($row['calendar_object'])) {
                    $object = @unserialize($row['calendar_object'], ['allowed_classes' => false]);
                    if (is_array($object) && !empty($object['image']['id'])) {
                        $ref = (int)$object['image']['id'];
                        if ($ref > 0) {
                            $file_ids[] = $ref;
                        }
                    }
                }

                $text = '';
                foreach (['calendar_text', 'calendar_teaser'] as $field) {
                    if (!empty($row[$field])) {
                        $text .= "\n" . $row[$field];
                    }
                }

                $rows[] = [
                    'type'     => 'calendar',
                    'entry_id' => (int)$row['calendar_id'],
                    'title'    => $row['calendar_title'] ?? '',
                    'file_ids' => $file_ids,
                    'text'     => $text,
                ];
            }
        }
    }

    // --- Glossar ---
    if (_dbTableExists('glossary')) {
        $gl_rows = _dbQuery(
            'SELECT glossary_id, glossary_title, glossary_text FROM ' .
            _dbTableName('glossary') . ' WHERE glossary_status != 9'
        );
        if (is_array($gl_rows)) {
            foreach ($gl_rows as $row) {
                $rows[] = [
                    'type'     => 'glossary',
                    'entry_id' => (int)$row['glossary_id'],
                    'title'    => $row['glossary_title'] ?? '',
                    'file_ids' => [],
                    'text'     => !empty($row['glossary_text']) ? "\n" . $row['glossary_text'] : '',
                ];
            }
        }
    }

    // --- Shop / Produkte ---
    if (_dbTableExists('shop_products')) {
        $shop_fields = 'shopprod_id, shopprod_ordernumber, shopprod_name1, shopprod_var, ' .
            'shopprod_description0, shopprod_description1, shopprod_description2, shopprod_description3';
        $shop_rows = _dbQuery(
            'SELECT ' . $shop_fields . ' FROM ' . _dbTableName('shop_products') . ' WHERE shopprod_status != 9'
        );
        if (is_array($shop_rows)) {
            foreach ($shop_rows as $row) {

                $file_ids = [];
                if (!empty($row['shopprod_var'])) {
                    $var = @unserialize($row['shopprod_var'], ['allowed_classes' => false]);
                    if (is_array($var)) {
                        foreach (['images', 'files'] as $group) {
                            if (!empty($var[$group]) && is_array($var[$group])) {
                                foreach ($var[$group] as $item) {
                                    if (is_array($item) && !empty($item['f_id'])) {
                                        $ref = (int)$item['f_id'];
                                        if ($ref > 0) {
                                            $file_ids[] = $ref;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $text = '';
                $desc_fields = [
                    'shopprod_description0', 'shopprod_description1',
                    'shopprod_description2', 'shopprod_description3',
                ];
                foreach ($desc_fields as $field) {
                    if (!empty($row[$field])) {
                        $text .= "\n" . $row[$field];
                    }
                }

                $title = trim(
                    ($row['shopprod_ordernumber'] ?? '') .
                    (!empty($row['shopprod_ordernumber']) && !empty($row['shopprod_name1']) ? ' / ' : '') .
                    ($row['shopprod_name1'] ?? '')
                );

                $rows[] = [
                    'type'     => 'shop',
                    'entry_id' => (int)$row['shopprod_id'],
                    'title'    => $title,
                    'file_ids' => $file_ids,
                    'text'     => $text,
                ];
            }
        }
    }

    return $rows;
}

/**
 * Every content part or module entry (non-deleted, non-trashed) that
 * currently references this file, matched by file ID and, as a fallback
 * for free-text fields, by searching for the file's unique hash. Used both
 * to decide the red usage status and to link to where the file is
 * actually used.
 *
 * @param int         $file_id
 * @param string|null $file_hash  Pass the already-known f_hash to avoid an
 *                                 extra lookup query; looked up otherwise.
 * @return array<int, array{type: string, acontent_id?: int, article_id?: int, article_title?: string, entry_id?: int, title?: string}>
 */
function phpwcms_get_file_usage_locations($file_id, $file_hash = null)
{
    $file_id = (int)$file_id;
    $locations = [];

    if ($file_id <= 0) {
        return $locations;
    }

    if ($file_hash === null) {
        $row = _dbQuery('SELECT f_hash FROM ' . DB_PREPEND . 'file WHERE f_id=' . $file_id . ' LIMIT 1');
        $file_hash = $row[0]['f_hash'] ?? '';
    }

    // A hash only counts as a reference when it stands alone in the text -
    // not when it is part of a longer hex string (another file's hash, a
    // checksum, ...). Built once, reused for every row.
    $hash_pattern = $file_hash !== '' ? '/(?<![a-f0-9])' . preg_quote($file_hash, '/') . '(?![a-f0-9])/i' : null;

    foreach (phpwcms_get_content_file_usage() as $row) {

        $matched = in_array($file_id, $row['file_ids'], true);

        if (!$matched && $hash_pattern !== null && $row['text'] !== '' && preg_match($hash_pattern, $row['text'])) {
            $matched = true;
        }

        if (!$matched) {
            continue;
        }

        if ($row['type'] === 'article') {
            $locations[] = [
                'type'          => 'article',
                'acontent_id'   => $row['acontent_id'],
                'article_id'    => $row['article_id'],
                'article_title' => $row['article_title'],
            ];
        } else {
            $locations[] = [
                'type'     => $row['type'],
                'entry_id' => $row['entry_id'],
                'title'    => $row['title'],
            ];
        }
    }

    return $locations;
}

/**
 * Is this file currently referenced by any (non-deleted, non-trashed)
 * article content part? Convenience wrapper around
 * phpwcms_get_file_usage_locations() for callers that only need the bool.
 *
 * @param int         $file_id
 * @param string|null $file_hash
 * @return bool
 */
function phpwcms_file_in_use($file_id, $file_hash = null)
{
    return !empty(phpwcms_get_file_usage_locations($file_id, $file_hash));
}

/**
 * Immediately flags every file referenced by one content part as "has been
 * used at least once" (f_used), right at the moment that content part is
 * saved - instead of relying solely on the lazy detection in
 * phpwcms_get_file_traffic_light(), which only catches usage if someone
 * happens to open the Dateizentrale while the file is still referenced.
 *
 * Without this, a file attached to a content part and detached again
 * before anyone opens the file center in between never gets its f_used
 * flag set, so it incorrectly keeps showing yellow (or green) instead of
 * black once it falls out of use, even though it clearly *was* used.
 *
 * Called right after article.editcontent.inc.php successfully
 * inserts/updates an articlecontent row.
 *
 * @param int $acontent_id
 * @return void
 */
function phpwcms_mark_content_files_used($acontent_id)
{
    $acontent_id = (int)$acontent_id;
    if ($acontent_id <= 0) {
        return;
    }

    $row = _dbQuery(
        'SELECT acontent_files, acontent_image, acontent_text, acontent_html, acontent_media, acontent_form ' .
        'FROM ' . DB_PREPEND . 'articlecontent WHERE acontent_id=' . $acontent_id . ' LIMIT 1'
    );

    if (empty($row[0])) {
        return;
    }
    $row = $row[0];

    // Direct ID references (image field, file-list field) - cheap, exact.
    $file_ids = [];
    foreach (['acontent_files', 'acontent_image'] as $id_field) {
        if (!empty($row[$id_field])) {
            foreach (explode(':', $row[$id_field]) as $ref_id) {
                $ref_id = (int)$ref_id;
                if ($ref_id > 0) {
                    $file_ids[] = $ref_id;
                }
            }
        }
    }

    if ($file_ids) {
        $file_ids = array_unique($file_ids);
        _dbQuery(
            'UPDATE ' . DB_PREPEND . 'file SET f_used=1 WHERE f_used=0 AND f_id IN (' . implode(',', $file_ids) . ')',
            'UPDATE'
        );
    }

    // Fallback for richtext/HTML/media/form content, which embeds files by
    // hash (e.g. download.php?f=HASH or a resized image path) rather than a
    // clean file ID list - mirrors the hash fallback already used in
    // phpwcms_get_file_usage_locations(). Candidate hashes are extracted in
    // PHP and matched with an indexed f_hash IN (...) lookup, so this stays a
    // bounded query instead of a whole-table INSTR() scan with an arbitrarily
    // large text literal.
    $text = '';
    foreach (['acontent_text', 'acontent_html', 'acontent_media', 'acontent_form'] as $text_field) {
        if (!empty($row[$text_field])) {
            $text .= "\n" . $row[$text_field];
        }
    }

    if ($text !== '' && preg_match_all('/(?<![a-f0-9])[a-f0-9]{32}(?![a-f0-9])/i', $text, $matches)) {
        $hashes = array_values(array_unique(array_map('strtolower', $matches[0])));
        $escaped = [];
        foreach ($hashes as $hash) {
            $escaped[] = _dbEscape($hash);
        }
        _dbQuery(
            'UPDATE ' . DB_PREPEND . 'file SET f_used=1 WHERE f_used=0 AND f_hash IN (' . implode(',', $escaped) . ')',
            'UPDATE'
        );
    }
}

/**
 * Traffic light status for a file row:
 *   red    - currently in use, not deletable
 *   yellow - newly uploaded, less than 24h old, never used
 *   green  - new, unused, 24h or older
 *   black  - was in use before, not anymore
 *
 * Also lazily flags the file as "has been used at least once" (f_used) the
 * first time it's seen in use, so it can later turn black once it falls
 * out of use again. This history only starts counting from the point this
 * feature was deployed - files used and abandoned before that cannot be
 * retroactively detected as black. phpwcms_mark_content_files_used() above
 * additionally flags files right at content-save time, so in practice this
 * lazy path is now mostly a safety net.
 *
 * @param array $file_row  A row from the file table (needs f_id, f_hash,
 *                          f_used, f_pre_install, f_created).
 * @return array{status: string, text_class: string, label: string, inuse: bool, locations: array}
 */
function phpwcms_get_file_traffic_light($file_row)
{

    $file_id   = (int)$file_row['f_id'];
    $locations = phpwcms_get_file_usage_locations($file_id, $file_row['f_hash'] ?? null);
    $in_use    = !empty($locations);

    if ($in_use && empty($file_row['f_used'])) {
        _dbQuery('UPDATE ' . DB_PREPEND . 'file SET f_used=1 WHERE f_id=' . $file_id . ' AND f_used=0', 'UPDATE');
        $file_row['f_used'] = 1; // keep in sync for the rest of this request
    }

    if ($in_use) {
        $status = 'red';
    } elseif (!empty($file_row['f_used'])) {
        // verified: it was referenced in content at some point
        $status = 'black';
    } elseif (!empty($file_row['f_pre_install'])) {
        // existed before this feature, so its history is unknown - same
        // "not a fresh upload" idea as black, but without claiming it was used
        $status = 'pre_install';
    } elseif ((time() - (int)$file_row['f_created']) < 86400) {
        $status = 'yellow';
    } else {
        $status = 'green';
    }

    // Status colors are plain Bootstrap text utilities: the status shows up as
    // a small colored dot next to the file name (or as the info icon when the
    // file is in use) and in the legend above the listing.
    $meta = [
        'red'         => ['text_class' => 'text-danger',    'label' => $GLOBALS['BL']['be_fusage_red'] ?? 'File is in use'],
        'yellow'      => ['text_class' => 'text-warning',   'label' => $GLOBALS['BL']['be_fusage_yellow'] ?? 'Newly uploaded'],
        'green'       => ['text_class' => 'text-success',   'label' => $GLOBALS['BL']['be_fusage_green'] ?? 'New, unused'],
        'black'       => ['text_class' => 'text-body',      'label' => $GLOBALS['BL']['be_fusage_black'] ?? 'Not used anymore'],
        'pre_install' => ['text_class' => 'text-secondary', 'label' => $GLOBALS['BL']['be_fusage_pre_install'] ?? 'Pre-existing'],
    ];

    return [
        'status'     => $status,
        'text_class' => $meta[$status]['text_class'],
        'label'      => $meta[$status]['label'],
        'inuse'      => $in_use,
        'locations'  => $locations,
    ];
}

/**
 * Status indicator shown behind a file name:
 *
 * - File in use (red): a focusable info icon. Hovering it shows the status as
 *   tooltip; clicking it opens a Bootstrap popover listing every content
 *   section referencing the file (a tooltip could not keep a link inside it
 *   clickable, the popover stays open until the user is done with it). The
 *   icon itself already carries the red status color, so no additional status
 *   dot is added.
 * - Otherwise: a small colored dot with the status as tooltip.
 *
 * @param array|null $tl
 * @return string
 */
function phpwcms_render_file_usage_indicator($tl = null)
{
    if (empty($tl['locations'])) {
        return '<span class="ms-1 ' . $tl['text_class'] . '" data-bs-toggle="tooltip" title="' .
            html($tl['label']) . '"><i class="fa-solid fa-circle" aria-hidden="true"></i></span>';
    }

    $links = [];
    $module_edit_url = [
        'calendar' => 'phpwcms.php?do=modules&module=calendar&edit=',
        'glossary' => 'phpwcms.php?do=modules&module=glossary&edit=',
        'shop'     => 'phpwcms.php?do=modules&module=shop&controller=prod&edit=',
    ];
    // English fallbacks: EN is the backend's default language and is
    // always loaded, so missing keys must not fall back to German.
    $module_fallback_label = [
        'calendar' => 'Calendar entry',
        'glossary' => 'Glossary entry',
        'shop'     => 'Shop product',
    ];
    $module_tag = [
        'calendar' => 'Calendar',
        'glossary' => 'Glossary',
        'shop'     => 'Shop',
    ];
    foreach ($tl['locations'] as $loc) {
        if ($loc['type'] === 'article') {
            $url = 'phpwcms.php?do=articles&p=2&s=1&aktion=2&id=' . $loc['article_id'] . '&acid=' . $loc['acontent_id'];
            $links[] = '<a href="' . html($url) . '" target="_blank">' . html($loc['article_title']) .
                ' <span class="text-muted">[ID: ' . (int)$loc['acontent_id'] . ']</span></a>';
        } elseif (isset($module_edit_url[$loc['type']])) {
            $type  = $loc['type'];
            $url   = $module_edit_url[$type] . (int)$loc['entry_id'];
            $label = ($loc['title'] !== '') ? $loc['title'] : ($module_fallback_label[$type] . ' #' . (int)$loc['entry_id']);
            $links[] = '<a href="' . html($url) . '" target="_blank">' . html($label) .
                ' <span class="text-muted">[' . $module_tag[$type] . ']</span></a>';
        }
    }
    $popover_content = implode('<br>', $links);

    // The wrapper carries the status tooltip (hover), the button the popover
    // (click/focus) - one Bootstrap toggle per element.
    return '<span class="ms-1 ' . $tl['text_class'] . '" data-bs-toggle="tooltip" title="' . html($tl['label']) . '">' .
        '<button type="button" class="btn btn-sm p-0 lh-1 align-baseline ' . $tl['text_class'] . '" ' .
        'data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" ' .
        'data-bs-title="' . html($tl['label']) . '" data-bs-content="' . html($popover_content) . '" ' .
        'aria-label="' . html($tl['label']) . '"><i class="fa-solid fa-circle-info" aria-hidden="true"></i></button></span>';
}

/**
 * Legend explaining the four traffic light colors, shown once above the file
 * listing - the colored dots alone aren't self explanatory.
 *
 * @return string
 */
function phpwcms_render_file_usage_legend()
{
    $text_classes = [
        'red'         => 'text-danger',
        'yellow'      => 'text-warning',
        'green'       => 'text-success',
        'black'       => 'text-body',
        'pre_install' => 'text-secondary',
    ];
    $parts = [];

    foreach ($text_classes as $status => $text_class) {
        $be_fusage_status = 'be_fusage_' . $status;
        $label = $GLOBALS['BL'][$be_fusage_status] ?? $status;
        $parts[] = '<i class="fa-solid fa-circle ' . $text_class . '" aria-hidden="true"></i>' .
            '<span class="mx-1">' . html($label) . '</span>';
    }

    return implode(' ', $parts);
}

/**
 * Every non-trashed file that is currently "black" because it is *verified*
 * as formerly used (f_used=1) but is referenced nowhere any more - used by
 * the "search unused files" tool in Dateiaktionen
 * (include/inc_tmpl/files.actions.tmpl.php) so files that have quietly
 * fallen out of use can be found and bulk-trashed in one place instead of
 * having to notice the black dot while browsing folder by folder.
 *
 * Deliberately limited to f_used=1: files with only f_pre_install=1 show the
 * "pre-existing" status instead, but their history is unknown, so they are
 * never offered for bulk deletion (a file referenced only by a hardcoded
 * template path would look exactly like a genuine orphan).
 *
 * Scoped like the regular file listing: a non-admin only ever sees their
 * own files. f_used=1 is a cheap first filter (indexed column on the file
 * table); phpwcms_get_file_traffic_light() is still run per candidate to
 * confirm the file isn't back in use since that flag was last set - it
 * reuses the request-cached content scan from
 * phpwcms_get_content_file_usage(), so this stays a single content query
 * however many candidate files there are.
 *
 * @return array<int, array> file rows (full phpwcms_file columns) plus
 *                            'f_dirname' (containing folder name, or '' for
 *                            root) and 'f_traffic_light' (the already
 *                            computed status, status === 'black' for every
 *                            row here) for each.
 */
function phpwcms_get_unused_files()
{

    $sql  = 'SELECT f.*, d.f_name AS f_dirname FROM ' . DB_PREPEND . 'file AS f ';
    $sql .= 'LEFT JOIN ' . DB_PREPEND . 'file AS d ON d.f_id = f.f_pid AND d.f_kid = 0 AND d.f_trash = 0 ';
    $sql .= 'WHERE f.f_kid = 1 AND f.f_trash = 0 AND f.f_used = 1 ';
    if (empty($_SESSION['wcs_user_admin'])) {
        $sql .= 'AND f.f_uid = ' . (int)$_SESSION['wcs_user_id'] . ' ';
    }
    $sql .= 'ORDER BY f.f_name';

    $result = _dbQuery($sql);
    $unused = [];

    if (is_array($result)) {
        foreach ($result as $row) {
            $tl = phpwcms_get_file_traffic_light($row);
            if ($tl['status'] === 'black') {
                $row['f_traffic_light'] = $tl;
                $unused[] = $row;
            }
        }
    }

    return $unused;
}
