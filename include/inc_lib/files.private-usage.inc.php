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
    die("You Cannot Access This Script Directly, Have a Nice Day.");
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
 * Ensures the `f_used` usage-tracking column exists on the file table.
 *
 * Deliberately NOT wired into the numbered build/revision system
 * (include/inc_lib/revision/rNNN.php + PHPWCMS_REVISION) - those revision
 * numbers are how the developer marks their own official releases, and a
 * custom schema change parked at a specific rNNN slot risks colliding with
 * a number they later assign to an actual release (duplicate/conflicting
 * rNNN.php file). Instead this check is completely self-contained: it runs
 * itself, independent of PHPWCMS_REVISION, the first time any File Center
 * usage-tracking code below executes, and remembers that it already ran via
 * its own sysvalue flag (`filecenter_schema_f_used`, group `sys_filecenter`)
 * rather than the revision-checked marker - so on every later request it's
 * a single cheap $GLOBALS/sysvalue lookup, not a fresh SHOW COLUMNS query.
 *
 * @return bool
 */
function phpwcms_filecenter_ensure_schema()
{
    static $checked = false;
    if ($checked) {
        return true;
    }

    if (empty($GLOBALS['db'])) {
        return false;
    }

    // Two independent sysvalue flags, checked separately, so that an install
    // where one already ran under an older version of this function (or -
    // for the column specifically - under the now-removed revision/rNNN.php
    // migration) doesn't skip the other. A single combined flag would let an
    // already-set schema flag short-circuit before the backfill ever runs.
    $schema_done = function_exists('_getConfig') && _getConfig('filecenter_schema_f_used');

    if (!$schema_done) {
        $ok = true;
        if (!_dbColumnExists('file', 'f_used')) {
            $ok = (bool) _dbQuery('ALTER TABLE `' . DB_PREPEND . "file` ADD `f_used` TINYINT(1) NOT NULL DEFAULT '0'", 'ALTER');
        }

        if (!$ok) {
            return false;
        }

        if (function_exists('_setConfig')) {
            _setConfig('filecenter_schema_f_used', 1, 'sys_filecenter');
        }
    }

    // One-time backfill (see phpwcms_filecenter_backfill_used_flag() below),
    // flagged and checked independently of the column-creation flag above.
    if (!(function_exists('_getConfig') && _getConfig('filecenter_backfill_f_used'))) {
        phpwcms_filecenter_backfill_used_flag();
        if (function_exists('_setConfig')) {
            _setConfig('filecenter_backfill_f_used', 1, 'sys_filecenter');
        }
    }

    $checked = true;

    return true;
}

/**
 * One-time backfill for files that already existed before this feature was
 * deployed: flags every one of them with f_used=1 right away, instead of
 * only the ones a live content scan happens to catch.
 *
 * A pre-existing file that's currently referenced by article content stays
 * red either way (the in-use check always wins over f_used), so this makes
 * no visible difference for those. The real target is every pre-existing
 * file that is NOT currently referenced: without this, such a file only
 * gets f_used set - and so only turns black once removed from use - if a
 * live content scan happens to have caught it in use at some point after
 * deployment. A file that was already sitting unused at deployment time
 * (or whose one-time use already ended before deployment) would otherwise
 * be indistinguishable from a genuinely brand new upload and show yellow
 * (<24h) or green, when in reality it's an old, orphaned file ("Dateileiche")
 * that predates this feature entirely - not a fresh file with no usage
 * history yet.
 *
 * So rather than trying to reconstruct actual historical usage (which is
 * impossible - past content edits leave no record to scan), every file that
 * already exists at deployment time is simply treated as "previously used":
 * still-referenced files stay red as always, and every other pre-existing
 * file becomes black (not yellow/green) from the moment this runs. Files
 * uploaded after deployment start with f_used=0 as normal and get their
 * usage tracked for real from then on.
 *
 * @return void
 */
function phpwcms_filecenter_backfill_used_flag()
{
    _dbQuery(
        "UPDATE " . DB_PREPEND . "file SET f_used=1 WHERE f_used=0 AND f_kid=1 AND f_trash=0",
        'UPDATE'
    );
}

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
 * @return array<int, array{acontent_id: int, article_id: int, article_title: string, file_ids: array<int,int>, text: string}>
 */
function phpwcms_get_content_file_usage()
{
    static $rows = null;

    if ($rows !== null) {
        return $rows;
    }

    $rows = array();

    $sql  = "SELECT arc.acontent_id, arc.acontent_aid, arc.acontent_files, arc.acontent_image, ";
    $sql .= "arc.acontent_text, arc.acontent_html, arc.acontent_media, arc.acontent_form, ";
    $sql .= "ar.article_id, ar.article_title ";
    $sql .= "FROM " . DB_PREPEND . "articlecontent AS arc ";
    $sql .= "INNER JOIN " . DB_PREPEND . "article AS ar ON ar.article_id = arc.acontent_aid ";
    $sql .= "WHERE arc.acontent_trash=0 AND ar.article_deleted=0";

    $result = _dbQuery($sql);

    if (is_array($result)) {

        foreach ($result as $row) {

            $file_ids = array();
            foreach (array('acontent_files', 'acontent_image') as $id_field) {
                if (!empty($row[$id_field])) {
                    foreach (explode(':', $row[$id_field]) as $ref_id) {
                        $ref_id = intval($ref_id);
                        if ($ref_id > 0) {
                            $file_ids[] = $ref_id;
                        }
                    }
                }
            }

            $text_parts = array();
            foreach (array('acontent_text', 'acontent_html', 'acontent_media', 'acontent_form') as $text_field) {
                if (!empty($row[$text_field])) {
                    $text_parts[] = $row[$text_field];
                }
            }

            $rows[] = array(
                'type'          => 'article',
                'acontent_id'   => intval($row['acontent_id']),
                'article_id'    => intval($row['article_id']),
                'article_title' => $row['article_title'],
                'file_ids'      => $file_ids,
                'text'          => implode("\n", $text_parts),
            );
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
 * and phpwcms_render_file_traffic_light() can build the right kind of
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

    $rows = array();

    // --- Kalender/Termine ---
    if (_dbTableExists('calendar')) {
        $cal_rows = _dbQuery(
            "SELECT calendar_id, calendar_title, calendar_object, calendar_text, calendar_teaser FROM " .
            _dbTableName('calendar') . " WHERE calendar_status != 9"
        );
        if (is_array($cal_rows)) {
            foreach ($cal_rows as $row) {

                $file_ids = array();
                if (!empty($row['calendar_object'])) {
                    $object = @unserialize($row['calendar_object'], array('allowed_classes' => false));
                    if (is_array($object) && !empty($object['image']['id'])) {
                        $ref = intval($object['image']['id']);
                        if ($ref > 0) {
                            $file_ids[] = $ref;
                        }
                    }
                }

                $text = '';
                foreach (array('calendar_text', 'calendar_teaser') as $field) {
                    if (!empty($row[$field])) {
                        $text .= "\n" . $row[$field];
                    }
                }

                $rows[] = array(
                    'type'     => 'calendar',
                    'entry_id' => intval($row['calendar_id']),
                    'title'    => isset($row['calendar_title']) ? $row['calendar_title'] : '',
                    'file_ids' => $file_ids,
                    'text'     => $text,
                );
            }
        }
    }

    // --- Glossar ---
    if (_dbTableExists('glossary')) {
        $gl_rows = _dbQuery(
            "SELECT glossary_id, glossary_title, glossary_text FROM " .
            _dbTableName('glossary') . " WHERE glossary_status != 9"
        );
        if (is_array($gl_rows)) {
            foreach ($gl_rows as $row) {
                $rows[] = array(
                    'type'     => 'glossary',
                    'entry_id' => intval($row['glossary_id']),
                    'title'    => isset($row['glossary_title']) ? $row['glossary_title'] : '',
                    'file_ids' => array(),
                    'text'     => !empty($row['glossary_text']) ? "\n" . $row['glossary_text'] : '',
                );
            }
        }
    }

    // --- Shop / Produkte ---
    if (_dbTableExists('shop_products')) {
        $shop_fields = 'shopprod_id, shopprod_ordernumber, shopprod_name1, shopprod_var, ' .
            'shopprod_description0, shopprod_description1, shopprod_description2, shopprod_description3';
        $shop_rows = _dbQuery(
            "SELECT " . $shop_fields . " FROM " . _dbTableName('shop_products') . " WHERE shopprod_status != 9"
        );
        if (is_array($shop_rows)) {
            foreach ($shop_rows as $row) {

                $file_ids = array();
                if (!empty($row['shopprod_var'])) {
                    $var = @unserialize($row['shopprod_var'], array('allowed_classes' => false));
                    if (is_array($var)) {
                        foreach (array('images', 'files') as $group) {
                            if (!empty($var[$group]) && is_array($var[$group])) {
                                foreach ($var[$group] as $item) {
                                    if (is_array($item) && !empty($item['f_id'])) {
                                        $ref = intval($item['f_id']);
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
                $desc_fields = array(
                    'shopprod_description0', 'shopprod_description1',
                    'shopprod_description2', 'shopprod_description3',
                );
                foreach ($desc_fields as $field) {
                    if (!empty($row[$field])) {
                        $text .= "\n" . $row[$field];
                    }
                }

                $title = trim(
                    (isset($row['shopprod_ordernumber']) ? $row['shopprod_ordernumber'] : '') .
                    (!empty($row['shopprod_ordernumber']) && !empty($row['shopprod_name1']) ? ' / ' : '') .
                    (isset($row['shopprod_name1']) ? $row['shopprod_name1'] : '')
                );

                $rows[] = array(
                    'type'     => 'shop',
                    'entry_id' => intval($row['shopprod_id']),
                    'title'    => $title,
                    'file_ids' => $file_ids,
                    'text'     => $text,
                );
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
    $file_id = intval($file_id);
    $locations = array();

    if ($file_id <= 0) {
        return $locations;
    }

    if ($file_hash === null) {
        $row = _dbQuery("SELECT f_hash FROM " . DB_PREPEND . "file WHERE f_id=" . $file_id . " LIMIT 1");
        $file_hash = isset($row[0]['f_hash']) ? $row[0]['f_hash'] : '';
    }

    foreach (phpwcms_get_content_file_usage() as $row) {

        $matched = in_array($file_id, $row['file_ids'], true);

        if (!$matched && $file_hash && $row['text'] !== '' && strpos($row['text'], $file_hash) !== false) {
            $matched = true;
        }

        if (!$matched) {
            continue;
        }

        if ($row['type'] === 'article') {
            $locations[] = array(
                'type'          => 'article',
                'acontent_id'   => $row['acontent_id'],
                'article_id'    => $row['article_id'],
                'article_title' => $row['article_title'],
            );
        } else {
            $locations[] = array(
                'type'     => $row['type'],
                'entry_id' => $row['entry_id'],
                'title'    => $row['title'],
            );
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
    $acontent_id = intval($acontent_id);
    if ($acontent_id <= 0) {
        return;
    }

    phpwcms_filecenter_ensure_schema();

    $row = _dbQuery(
        "SELECT acontent_files, acontent_image, acontent_text, acontent_html, acontent_media, acontent_form " .
        "FROM " . DB_PREPEND . "articlecontent WHERE acontent_id=" . $acontent_id . " LIMIT 1"
    );

    if (empty($row[0])) {
        return;
    }
    $row = $row[0];

    // Direct ID references (image field, file-list field) - cheap, exact.
    $file_ids = array();
    foreach (array('acontent_files', 'acontent_image') as $id_field) {
        if (!empty($row[$id_field])) {
            foreach (explode(':', $row[$id_field]) as $ref_id) {
                $ref_id = intval($ref_id);
                if ($ref_id > 0) {
                    $file_ids[] = $ref_id;
                }
            }
        }
    }

    if ($file_ids) {
        $file_ids = array_unique($file_ids);
        _dbQuery(
            "UPDATE " . DB_PREPEND . "file SET f_used=1 WHERE f_used=0 AND f_id IN (" . implode(',', $file_ids) . ")",
            'UPDATE'
        );
    }

    // Fallback for richtext/HTML/media/form content, which embeds files by
    // hash (e.g. download.php?f=HASH or a resized image path) rather than a
    // clean file ID list - mirrors the hash fallback already used in
    // phpwcms_get_file_usage_locations(). One query against the whole file
    // table, only run at save time (not on every page view).
    $text = '';
    foreach (array('acontent_text', 'acontent_html', 'acontent_media', 'acontent_form') as $text_field) {
        if (!empty($row[$text_field])) {
            $text .= "\n" . $row[$text_field];
        }
    }

    if ($text !== '') {
        _dbQuery(
            "UPDATE " . DB_PREPEND . "file SET f_used=1 WHERE f_used=0 AND f_hash <> '' AND INSTR(" . _dbEscape($text) . ", f_hash) > 0",
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
 *                          f_used, f_created).
 * @return array{status: string, color: string, label: string, inuse: bool, locations: array}
 */
function phpwcms_get_file_traffic_light($file_row)
{
    phpwcms_filecenter_ensure_schema();

    $file_id   = intval($file_row['f_id']);
    $locations = phpwcms_get_file_usage_locations($file_id, isset($file_row['f_hash']) ? $file_row['f_hash'] : null);
    $in_use    = !empty($locations);

    if ($in_use && empty($file_row['f_used'])) {
        _dbQuery("UPDATE " . DB_PREPEND . "file SET f_used=1 WHERE f_id=" . $file_id . " AND f_used=0", 'UPDATE');
        $file_row['f_used'] = 1; // keep in sync for the rest of this request
    }

    if ($in_use) {
        $status = 'red';
    } elseif (!empty($file_row['f_used'])) {
        $status = 'black';
    } elseif ((time() - intval($file_row['f_created'])) < 86400) {
        $status = 'yellow';
    } else {
        $status = 'green';
    }

    $meta = array(
        'red'    => array('color' => '#dc3545', 'label' => isset($GLOBALS['BL']['be_fusage_red'])    ? $GLOBALS['BL']['be_fusage_red']    : 'In Benutzung'),
        'yellow' => array('color' => '#ffc107', 'label' => isset($GLOBALS['BL']['be_fusage_yellow']) ? $GLOBALS['BL']['be_fusage_yellow'] : 'Neu hochgeladen'),
        'green'  => array('color' => '#28a745', 'label' => isset($GLOBALS['BL']['be_fusage_green'])  ? $GLOBALS['BL']['be_fusage_green']  : 'Neu, unbenutzt'),
        'black'  => array('color' => '#343a40', 'label' => isset($GLOBALS['BL']['be_fusage_black'])  ? $GLOBALS['BL']['be_fusage_black']  : 'Wird nicht mehr genutzt'),
    );

    return array(
        'status'    => $status,
        'color'     => $meta[$status]['color'],
        'label'     => $meta[$status]['label'],
        'inuse'     => $in_use,
        'locations' => $locations,
    );
}

/**
 * Small colored dot badge markup for a file's traffic light status. Pass an
 * already-computed $tl (from phpwcms_get_file_traffic_light()) to avoid
 * recomputing it when the caller also needs the status for something else
 * (e.g. disabling the trash button).
 *
 * Files with usage locations (red status) render as a focusable button that
 * opens a Bootstrap Popover with a link to every content section
 * referencing the file, instead of a plain hover tooltip: a tooltip's hide
 * delay can't reliably keep it open long enough to move the pointer onto a
 * link inside it, while a popover (opened on click/focus, dismissed on
 * blur - see the init in files.private.additions.inc.php) stays open until
 * the user is done with it. Files without locations keep the lightweight
 * hover tooltip.
 *
 * @param array      $file_row
 * @param array|null $tl
 * @return string
 */
function phpwcms_render_file_traffic_light($file_row, $tl = null)
{
    if ($tl === null) {
        $tl = phpwcms_get_file_traffic_light($file_row);
    }

    $dot_style = 'display:inline-block;width:10px;height:10px;border-radius:50%;vertical-align:middle;' .
        'background-color:' . $tl['color'] . ';margin-right:6px;';

    if (!empty($tl['locations'])) {
        $links = array();
        $module_edit_url = array(
            'calendar' => 'phpwcms.php?do=modules&module=calendar&edit=',
            'glossary' => 'phpwcms.php?do=modules&module=glossary&edit=',
            'shop'     => 'phpwcms.php?do=modules&module=shop&controller=prod&edit=',
        );
        $module_fallback_label = array(
            'calendar' => 'Kalender-Eintrag',
            'glossary' => 'Glossar-Eintrag',
            'shop'     => 'Shop-Produkt',
        );
        $module_tag = array(
            'calendar' => 'Kalender',
            'glossary' => 'Glossar',
            'shop'     => 'Shop',
        );
        foreach ($tl['locations'] as $loc) {
            if ($loc['type'] === 'article') {
                $url = 'phpwcms.php?do=articles&p=2&s=1&aktion=2&id=' . $loc['article_id'] . '&acid=' . $loc['acontent_id'];
                $links[] = '<a href="' . html($url) . '" target="_blank">' . html($loc['article_title']) .
                    ' <span class="text-muted">[ID: ' . intval($loc['acontent_id']) . ']</span></a>';
            } elseif (isset($module_edit_url[$loc['type']])) {
                $type  = $loc['type'];
                $url   = $module_edit_url[$type] . intval($loc['entry_id']);
                $label = ($loc['title'] !== '') ? $loc['title'] : ($module_fallback_label[$type] . ' #' . intval($loc['entry_id']));
                $links[] = '<a href="' . html($url) . '" target="_blank">' . html($label) .
                    ' <span class="text-muted">[' . $module_tag[$type] . ']</span></a>';
            }
        }
        $popover_content = implode('<br>', $links);

        return '<button type="button" class="file-usage-dot file-usage-' . $tl['status'] . ' btn p-0 border-0 line-height-1" ' .
            'style="' . $dot_style . 'line-height:0;" ' .
            'data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" ' .
            'data-bs-title="' . html($tl['label']) . '" data-bs-content="' . html($popover_content) . '" ' .
            'aria-label="' . html($tl['label']) . '"></button>';
    }

    return '<span class="file-usage-dot file-usage-' . $tl['status'] . '" data-bs-toggle="tooltip" title="' .
        html($tl['label']) . '" style="' . $dot_style . '" aria-hidden="true"></span>';
}

/**
 * Small legend explaining the four traffic light colors, meant to be shown
 * once above the file listing - the colored dots alone aren't self
 * explanatory.
 *
 * @return string
 */
function phpwcms_render_file_usage_legend()
{
    $order = array('red', 'yellow', 'green', 'black');
    $dummy = array('f_id' => 0, 'f_hash' => '', 'f_used' => 0, 'f_created' => time());
    $parts = array();

    foreach ($order as $status) {
        $tl = array(
            'status' => $status,
            'color'  => array('red' => '#dc3545', 'yellow' => '#ffc107', 'green' => '#28a745', 'black' => '#343a40')[$status],
            'label'  => isset($GLOBALS['BL']['be_fusage_' . $status]) ? $GLOBALS['BL']['be_fusage_' . $status] : $status,
        );
        $parts[] = phpwcms_render_file_traffic_light($dummy, $tl) . html($tl['label']);
    }

    return implode('&nbsp;&nbsp;&nbsp;', $parts);
}

/**
 * Every non-trashed file that is currently "black" (was referenced by
 * covered content - article content or a covered module - at some point,
 * per f_used, but isn't anymore) - used by the "search unused files" tool
 * in Dateiaktionen
 * (include/inc_tmpl/files.actions.tmpl.php) so files that have quietly
 * fallen out of use can be found and bulk-trashed in one place instead of
 * having to notice the black dot while browsing folder by folder.
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
    phpwcms_filecenter_ensure_schema();

    $sql  = "SELECT f.*, d.f_name AS f_dirname FROM " . DB_PREPEND . "file AS f ";
    $sql .= "LEFT JOIN " . DB_PREPEND . "file AS d ON d.f_id = f.f_pid AND d.f_kid = 0 AND d.f_trash = 0 ";
    $sql .= "WHERE f.f_kid = 1 AND f.f_trash = 0 AND f.f_used = 1 ";
    if (empty($_SESSION["wcs_user_admin"])) {
        $sql .= "AND f.f_uid = " . intval($_SESSION["wcs_user_id"]) . " ";
    }
    $sql .= "ORDER BY f.f_name";

    $result = _dbQuery($sql);
    $unused = array();

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
