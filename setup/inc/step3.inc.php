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
<h2 class="h4 text-primary font-weight-normal mb-3">6. Internal Path &amp; Storage Settings</h2>
<p class="text-muted">Ensure path values do NOT begin or end with a leading/trailing slash. Using default values is recommended for initial installation.</p>

<form action="setup.php?step=3" method="post">
    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">System Paths &amp; Directories</div>
        <div class="card-body">

            <div class="form-group row">
                <label for="doc_root" class="col-sm-3 col-form-label font-weight-bold">Document Root</label>
                <div class="col-sm-6">
                    <input name="doc_root" type="text" class="form-control" id="doc_root" value="<?php echo html_specialchars($phpwcms["DOC_ROOT"]) ?>" placeholder="<?php echo html_specialchars($_SERVER['DOCUMENT_ROOT']) ?>" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Detected: <code><?php echo html_specialchars($_SERVER['DOCUMENT_ROOT']) ?></code></div>
            </div>

            <div class="form-group row">
                <label for="root" class="col-sm-3 col-form-label font-weight-bold">phpwcms Subdirectory</label>
                <div class="col-sm-6">
                    <input name="root" type="text" class="form-control" id="root" value="<?php echo html_specialchars($phpwcms["root"]) ?>" placeholder="" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: empty (if at domain root)</div>
            </div>

            <div class="form-group row">
                <label for="file_path" class="col-sm-3 col-form-label font-weight-bold">File Archive Directory</label>
                <div class="col-sm-6">
                    <input name="file_path" type="text" class="form-control" id="file_path" value="<?php echo html_specialchars($phpwcms["file_path"]) ?>" placeholder="filearchive" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: <code>filearchive</code></div>
            </div>

            <div class="form-group row">
                <label for="templates" class="col-sm-3 col-form-label font-weight-bold">Template Directory</label>
                <div class="col-sm-6">
                    <input name="templates" type="text" class="form-control" id="templates" value="<?php echo html_specialchars($phpwcms["templates"]) ?>" placeholder="template" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: <code>template</code></div>
            </div>

            <div class="form-group row mb-0">
                <label for="ftp_path" class="col-sm-3 col-form-label font-weight-bold">FTP Upload Directory</label>
                <div class="col-sm-6">
                    <input name="ftp_path" type="text" class="form-control" id="ftp_path" value="<?php echo html_specialchars($phpwcms["ftp_path"]) ?>" placeholder="upload" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: <code>upload</code></div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=2" class="btn btn-secondary">&larr; Previous Step</a>
        <button type="submit" class="btn btn-primary btn-lg">Save &amp; Continue &rarr;</button>
    </div>
    <input name="do" type="hidden" value="1" />
</form>
