<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

if (!defined('PHPWCMS_SETUP')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

?>
<h2 class="h4 text-primary fw-normal mb-3">6. Internal Paths &amp; Storage Settings</h2>
<p class="text-muted">Ensure path values do NOT begin or end with a leading/trailing slash. Using default values is recommended for initial installation.</p>

<?php if (!empty($err) && !empty($storage_err)): ?>
    <div class="alert alert-danger mb-4">
        <i class="fa fa-exclamation-triangle"></i> The private storage directory could not be created or is not writable:
        <code><?php echo html_specialchars($storage_err) ?></code>.
        Create it with mode 0700 for the PHP user, or set an absolute path that the PHP user can write to.
    </div>
<?php endif; ?>

<form action="setup.php?step=4" method="post">
    <div class="card mb-4 border">
        <div class="card-header bg-light fw-bold">System Paths &amp; Directories</div>
        <div class="card-body">

            <div class="form-group row">
                <label for="doc_root" class="col-sm-3 col-form-label fw-bold">Document Root</label>
                <div class="col-sm-6">
                    <input name="doc_root" type="text" class="form-control" id="doc_root" value="<?php echo html_specialchars($phpwcms['DOC_ROOT']) ?>" placeholder="<?php echo html_specialchars($_SERVER['DOCUMENT_ROOT']) ?>" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Detected: <code><?php echo html_specialchars($_SERVER['DOCUMENT_ROOT']) ?></code></div>
            </div>

            <div class="form-group row">
                <label for="root" class="col-sm-3 col-form-label fw-bold">phpwcms Subdirectory</label>
                <div class="col-sm-6">
                    <input name="root" type="text" class="form-control" id="root" value="<?php echo html_specialchars($phpwcms['root']) ?>" placeholder="" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: empty (if at domain root)</div>
            </div>

            <div class="form-group row">
                <label for="file_path" class="col-sm-3 col-form-label fw-bold">File Archive Directory</label>
                <div class="col-sm-6">
                    <input name="file_path" type="text" class="form-control" id="file_path" value="<?php echo html_specialchars($phpwcms['file_path']) ?>" placeholder="filearchive" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: <code>filearchive</code></div>
            </div>

            <div class="form-group row">
                <label for="templates" class="col-sm-3 col-form-label fw-bold">Template Directory</label>
                <div class="col-sm-6">
                    <input name="templates" type="text" class="form-control" id="templates" value="<?php echo html_specialchars($phpwcms['templates']) ?>" placeholder="template" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: <code>template</code></div>
            </div>

            <div class="form-group row">
                <label for="ftp_path" class="col-sm-3 col-form-label fw-bold">FTP Upload Directory</label>
                <div class="col-sm-6">
                    <input name="ftp_path" type="text" class="form-control" id="ftp_path" value="<?php echo html_specialchars($phpwcms['ftp_path']) ?>" placeholder="upload" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: <code>upload</code></div>
            </div>

            <div class="form-group row">
                <label for="storage_path" class="col-sm-3 col-form-label fw-bold">Private Storage Directory</label>
                <div class="col-sm-6">
                    <input name="storage_path" type="text" class="form-control" id="storage_path" value="<?php echo html_specialchars($phpwcms['storage_path'] ?? '../phpwcms-storage') ?>" placeholder="../phpwcms-storage" maxlength="255" />
                    <?php if (!empty($storage_err)): ?>
                        <div class="form-text text-danger small">Could not create or write <code><?php echo html_specialchars($storage_err) ?></code>. Use an absolute path the PHP user can write to.</div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Outside the document root, relative to PHPWCMS_ROOT or absolute</div>
            </div>

            <div class="form-group row mb-0">
                <label for="cdn_image_url" class="col-sm-3 col-form-label fw-bold">CDN Image URL</label>
                <div class="col-sm-6">
                    <input name="cdn_image_url" type="url" class="form-control" id="cdn_image_url" value="<?php echo html_specialchars($phpwcms['cdn_image_url'] ?? '') ?>" placeholder="https://cdn.example.com" maxlength="255" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Optional CDN domain for rendered images</div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=3" class="btn btn-secondary">&larr; Previous Step</a>
        <button type="submit" class="btn btn-primary">Save &amp; Continue &rarr;</button>
    </div>
    <input name="do" type="hidden" value="1" />
</form>
