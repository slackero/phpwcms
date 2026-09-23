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

if(isset($_GET["all"])) { // Hide/Show

        $_SESSION["klapp"] = array();

    if($_GET["all"] == "open") { // All

        $sql = "SELECT f_id FROM ".DB_PREPEND."file WHERE f_kid=0 AND f_trash=0";
        if(empty($_SESSION["wcs_user_admin"])) {
            $sql .= " AND f_uid=".$_SESSION["wcs_user_id"];
        }

        $result = _dbQuery($sql);

        if(isset($result[0]['f_id'])) {
            foreach($result as $row) {
                $_SESSION["klapp"][intval($row['f_id'])] = 1;
            }
        }
    }

    _dbQuery("UPDATE ".DB_PREPEND."user SET usr_var_privatefile="._dbEscape(serialize($_SESSION["klapp"]))." WHERE usr_id=".intval($_SESSION["wcs_user_id"]), 'UPDATE');

} elseif(!isset($_SESSION["klapp"])) {

    $_SESSION["klapp"] = array();

}

if(isset($_GET["klapp"])) {

    list($klapp_id, $klapp_value) = explode("|", $_GET["klapp"]);
    $klapp_id = intval($klapp_id);

    if(intval($klapp_value)) {
        $_SESSION["klapp"][$klapp_id] = 1;
    } else {
        unset($_SESSION["klapp"][$klapp_id]);
    }

    foreach($_SESSION["klapp"] as $klapp_id => $klapp_value) {
        if(!$klapp_value) {
            unset($_SESSION["klapp"][$klapp_id]);
        }
    }

    _dbQuery("UPDATE ".DB_PREPEND."user SET usr_var_privatefile="._dbEscape(serialize($_SESSION["klapp"]))." WHERE usr_id=".intval($_SESSION["wcs_user_id"]), 'UPDATE');
}

// Set counter for listing
$_SESSION["list_zaehler"] = 0;

// Are there any files or folders
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."file WHERE f_trash=0";
if(empty($_SESSION["wcs_user_admin"])) {
    $sql .= " AND f_uid=".$_SESSION["wcs_user_id"];
}
$sql .= " LIMIT 1";
$count_user_files = _dbCount($sql);

// Does the user have files to list
if($count_user_files) {

    // Bulk action bar - hidden until at least one file checkbox is ticked
    echo '<div id="filecenter-bulkbar" class="alert alert-secondary py-2 px-3 mb-2 d-flex align-items-center justify-content-between" hidden>';
    echo '<span id="filecenter-bulkbar-count"></span>';
    echo '<div>';
    echo '<button type="button" id="filecenter-bulk-trash" class="btn btn-sm btn-warning me-2"><i class="fa-regular fa-trash-alt fa-fw" aria-hidden="true"></i> '.html($BL['be_fprivfunc_movetrash']).'</button>';
    echo '<button type="button" id="filecenter-bulk-clear" class="btn btn-sm btn-outline-secondary">'.html($BL['be_fusage_bulk_clear']).'</button>';
    echo '</div>';
    echo '</div>'.LF;

    echo '<table class="table table-sm table-valign-middle" id="filecenter-list">';

    // Legend for the file usage traffic light dots shown next to each file
    echo '<tr class="filecenter-legend"><td colspan="2" class="text-muted small">';
    echo phpwcms_render_file_usage_legend();
    echo '</td></tr>'.LF;

    // Root drop zone: lets a dragged file be moved back to the top level (f_pid=0)
    echo '<tr class="filecenter-root-row" data-folder-drop="0">';
    echo '<td colspan="2" class="text-muted"><i class="fa-solid fa-desktop fa-fw me-1" aria-hidden="true"></i>'.html($BL['ROOT_DIR']).'</td>';
    echo '</tr>'.LF;

    list_private(0, 0, "phpwcms.php?do=files&amp;f=0", $_SESSION["wcs_user_id"], 0, $phpwcms);
    include_once PHPWCMS_ROOT."/include/inc_lib/files.private-filelist.inc.php";
    echo "</table>";
    ?>
    <style>
        /* Checkbox and handle are stacked with a small gap between them so
           the handle's hover tooltip never lands on the checkbox. Two
           earlier attempts didn't work: percentage-based centering
           (position:absolute; top:50%) placed the handle right on/against
           the checkbox because this cell's own row is often just one text
           line tall (a thumbnail preview, if shown, lives in a separate
           <tr> below); and display:flex on the <td> itself made the whole
           table cell balloon in height, pushing that thumbnail row far
           down. Plain block-level stacking with a fixed margin avoids both:
           the cell only ever grows by that exact margin, nothing more. */
        #filecenter-list .file-select-checkbox { display: block; margin: 0 auto; }
        #filecenter-list .file-select-cell .handle { display: block; margin: .5rem auto 0; width: fit-content; }
        #filecenter-list .handle { cursor: grab; }
        #filecenter-list tr[data-file-drag].filecenter-dragging { opacity: .4; }
        #filecenter-list tr.filecenter-root-row td { border-top: 1px solid #dee2e6; }
        #filecenter-list tr.filecenter-drop-hover td { background-color: #d7ecff !important; outline: 2px dashed #2b7de9; outline-offset: -2px; }
        #filecenter-list table.filecenter-drop-hover { background-color: #d7ecff; outline: 2px dashed #2b7de9; outline-offset: -2px; }
        #filecenter-list tr[data-file-drag].filecenter-row-selected td { background-color: #eef6ff; }
        #filecenter-list .file-select-checkbox { cursor: pointer; }
        /* Bootstrap's "d-flex" utility uses !important, which otherwise wins
           over the plain [hidden] user-agent style - this reasserts it. */
        #filecenter-bulkbar[hidden] { display: none !important; }
    </style>
    <script>
    (function($) {
        var dragFileIds = [];

        // Files in use (red dot) render as a focusable button carrying
        // data-bs-toggle="popover" instead of a plain hover tooltip - a
        // tooltip's hide delay can't reliably keep it open long enough to
        // move the pointer onto a link inside it, while a popover opened on
        // click/focus stays open until the user clicks/tabs away.
        // Bootstrap doesn't auto-initialize popovers site-wide (only
        // tooltips, in phpwcms.js), so that has to happen here.
        //
        // This whole block runs immediately as the page is parsed (this
        // <script> tag sits inline in the middle of the admin page), which
        // is *before* the bootstrap.bundle script further down/at the end of
        // the page has necessarily executed - unlike the tooltip case, there
        // is no later, global fallback init for [data-bs-toggle="popover"]
        // to fall back on, so an early "bootstrap is undefined" here means
        // the button stays completely inert (no hover, no click). Deferring
        // to jQuery's DOM-ready callback guarantees this runs after every
        // script on the page - including bootstrap.bundle - has loaded, the
        // same way phpwcms.js's own global tooltip init does.
        $(function () {
            if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
                document.querySelectorAll('#filecenter-list [data-bs-toggle="popover"]').forEach(function (el) {
                    bootstrap.Popover.getOrCreateInstance(el, {
                        html: true,
                        sanitize: false,
                        container: 'body'
                    });
                });
            }
        });

        // Dragging only starts from the grip handle (same idea as the content
        // section handle): mousedown on it arms the row for native HTML5 drag,
        // so clicking links/buttons elsewhere in the row still works normally.
        $(document).on('mousedown', '#filecenter-list .handle', function() {
            $(this).closest('tr[data-file-drag]').attr('draggable', 'true');
        });

        // Safety net: disarm again on mouseup, whether or not a drag actually
        // happened (a real drag already got what it needed by then).
        $(document).on('mouseup', function() {
            $('#filecenter-list tr[data-file-drag]').removeAttr('draggable');
        });

        // --- Multi-select (checkboxes) -----------------------------------
        var selectedFileIds = new Set();

        function updateBulkBar() {
            var n = selectedFileIds.size;
            var countLabel = (<?php echo json_encode($BL['be_fusage_bulk_selected']); ?>).replace('{VAL}', n);
            $('#filecenter-bulkbar-count').text(countLabel);
            $('#filecenter-bulkbar').prop('hidden', n === 0);
        }

        $(document).on('change', '#filecenter-list .file-select-checkbox', function() {
            var fid = String($(this).attr('data-file-select'));
            var $row = $(this).closest('tr[data-file-drag]');
            if (this.checked) {
                selectedFileIds.add(fid);
                $row.addClass('filecenter-row-selected');
            } else {
                selectedFileIds.delete(fid);
                $row.removeClass('filecenter-row-selected');
            }
            updateBulkBar();
        });

        $(document).on('click', '#filecenter-bulk-clear', function() {
            selectedFileIds.clear();
            $('#filecenter-list .file-select-checkbox').prop('checked', false);
            $('#filecenter-list tr[data-file-drag]').removeClass('filecenter-row-selected');
            updateBulkBar();
        });

        $(document).on('click', '#filecenter-bulk-trash', function() {
            if (!selectedFileIds.size) { return; }
            var ids = Array.from(selectedFileIds);
            var confirmMsg = (<?php echo json_encode($BL['be_fusage_bulk_trash_confirm']); ?>).replace('{VAL}', ids.length);

            bsConfirm('danger', confirmMsg, function() {

                var csrfToken = (typeof CSRF_GET_TOKEN !== 'undefined' && CSRF_GET_TOKEN) ? CSRF_GET_TOKEN : '';

                $.ajax({
                    url: 'include/inc_act/act_file.php' + (csrfToken ? '?' + csrfToken : ''),
                    method: 'GET',
                    data: { trash: ids.join(':') + '|1' },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    xhrFields: { withCredentials: true },
                    dataType: 'json'
                }).done(function(response) {
                    if (response && response.blocked > 0) {
                        var skippedMsg = (<?php echo json_encode($BL['be_fusage_bulk_skipped']); ?>).replace('{VAL}', response.blocked);
                        bsAlert(skippedMsg, function() { document.location.reload(); });
                        return;
                    }
                    if (response && response.success === false) {
                        bsAlert(<?php echo json_encode($BL['be_error_while_save'] ?? 'Storing data failed.'); ?>);
                        return;
                    }
                    document.location.reload();
                }).fail(function() {
                    document.location.reload();
                });
            });
        });

        $(document).on('dragstart', '#filecenter-list tr[data-file-drag]', function(e) {
            var rowId = $(this).attr('data-file-drag');
            var dt = e.originalEvent.dataTransfer;
            dt.effectAllowed = 'move';

            // If the dragged row is part of the current multi-selection, drag
            // the whole selection along with it; otherwise just this one file
            // (same convention as common desktop file managers).
            if (selectedFileIds.size > 1 && selectedFileIds.has(rowId)) {
                dragFileIds = Array.from(selectedFileIds);
            } else {
                dragFileIds = [rowId];
            }

            try { dt.setData('text/plain', dragFileIds.join(':')); } catch (err) {}
            $('#filecenter-list tr[data-file-drag]').each(function() {
                if (dragFileIds.indexOf($(this).attr('data-file-drag')) !== -1) {
                    $(this).addClass('filecenter-dragging');
                }
            });
        });

        $(document).on('dragend', '#filecenter-list tr[data-file-drag]', function() {
            $('#filecenter-list tr[data-file-drag]').removeClass('filecenter-dragging').removeAttr('draggable');
            $('#filecenter-list [data-folder-drop]').removeData('dragDepth').removeClass('filecenter-drop-hover');
            dragFileIds = [];
        });

        // ...and dropped onto anything with data-folder-drop: a folder row, the
        // root row, or the table listing a folder's (or root's) already-visible
        // files — so dropping among existing files works just as well as
        // dropping on the folder icon itself.
        // A dragenter/dragleave depth counter avoids the highlight flickering
        // as the pointer crosses the many nested rows inside a file list.
        $(document).on('dragenter', '#filecenter-list [data-folder-drop]', function(e) {
            if (!dragFileIds.length) { return; }
            e.preventDefault();
            var depth = ($(this).data('dragDepth') || 0) + 1;
            $(this).data('dragDepth', depth).addClass('filecenter-drop-hover');
        });

        $(document).on('dragover', '#filecenter-list [data-folder-drop]', function(e) {
            if (!dragFileIds.length) { return; }
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';
        });

        $(document).on('dragleave', '#filecenter-list [data-folder-drop]', function() {
            var depth = ($(this).data('dragDepth') || 1) - 1;
            $(this).data('dragDepth', depth);
            if (depth <= 0) {
                $(this).removeClass('filecenter-drop-hover');
            }
        });

        $(document).on('drop', '#filecenter-list [data-folder-drop]', function(e) {
            e.preventDefault();
            $(this).data('dragDepth', 0).removeClass('filecenter-drop-hover');

            var targetDir = $(this).attr('data-folder-drop');
            var fileIds = dragFileIds.length ? dragFileIds.slice() :
                (e.originalEvent.dataTransfer ? (e.originalEvent.dataTransfer.getData('text/plain') || '').split(':').filter(Boolean) : []);
            dragFileIds = [];
            if (!fileIds.length) { return; }

            // Drop only files that aren't already in this exact folder
            fileIds = fileIds.filter(function(fid) {
                var $file = $('#filecenter-list tr[data-file-drag="' + fid + '"]');
                return !($file.length && $file.attr('data-file-pid') === targetDir);
            });
            if (!fileIds.length) { return; }

            var csrfToken = (typeof CSRF_GET_TOKEN !== 'undefined' && CSRF_GET_TOKEN) ? CSRF_GET_TOKEN : '';

            $.ajax({
                url: 'include/inc_act/act_file.php' + (csrfToken ? '?' + csrfToken : ''),
                method: 'GET',
                data: { paste: fileIds.join(':') + '|' + targetDir },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                xhrFields: { withCredentials: true },
                dataType: 'json'
            }).done(function(response) {
                if (response && response.success === false) {
                    bsAlert(<?php echo json_encode($BL['be_error_while_save'] ?? 'Storing data failed.'); ?>);
                    return;
                }
                document.location.reload();
            }).fail(function() {
                document.location.reload();
            });
        });
    })(jQuery);
    </script>
    <?php
} else {
    // Nothing to list
    echo $BL['be_fprivadd_nofolders']."&nbsp;&nbsp;";
    echo "[<a href=\"phpwcms.php?do=files&amp;f=0&amp;mkdir=0\">".$BL['be_fpriv_button']."</a>]";

}
