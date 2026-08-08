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
<h2 class="h4 text-primary font-weight-normal mb-3">7. Content &amp; Media Defaults</h2>

<form action="setup.php?step=4" method="post">
    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Content &amp; Image Dimension Limits</div>
        <div class="card-body">

            <div class="form-group row">
                <label for="file_maxsize" class="col-sm-3 col-form-label font-weight-bold">Max File Upload Size</label>
                <div class="col-sm-6">
                    <input name="file_maxsize" type="text" class="form-control" id="file_maxsize" value="<?php echo (int)$phpwcms["file_maxsize"] ?>" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">In Bytes (e.g. 52428800 = 50 MB)</div>
            </div>

            <div class="form-group row">
                <label for="content_width" class="col-sm-3 col-form-label font-weight-bold">Content Column Width</label>
                <div class="col-sm-6">
                    <input name="content_width" type="text" class="form-control" id="content_width" value="<?php echo (int)$phpwcms["content_width"] ?>" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Base width in pixels (Default: 538)</div>
            </div>

            <div class="form-group row">
                <label for="img_list_width" class="col-sm-3 col-form-label font-weight-bold">Thumbnail Dimensions</label>
                <div class="col-sm-6 d-flex align-items-center">
                    <input name="img_list_width" type="text" class="form-control mr-2" id="img_list_width" value="<?php echo (int)$phpwcms["img_list_width"] ?>" placeholder="Width" />
                    <span class="mr-2 text-muted">&times;</span>
                    <input name="img_list_height" type="text" class="form-control" id="img_list_height" value="<?php echo (int)$phpwcms["img_list_height"] ?>" placeholder="Height" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Width x Height (Default: 100 x 75)</div>
            </div>

            <div class="form-group row">
                <label for="img_prev_width" class="col-sm-3 col-form-label font-weight-bold">Preview Dimensions</label>
                <div class="col-sm-6 d-flex align-items-center">
                    <input name="img_prev_width" type="text" class="form-control mr-2" id="img_prev_width" value="<?php echo (int)$phpwcms["img_prev_width"] ?>" placeholder="Width" />
                    <span class="mr-2 text-muted">&times;</span>
                    <input name="img_prev_height" type="text" class="form-control" id="img_prev_height" value="<?php echo (int)$phpwcms["img_prev_height"] ?>" placeholder="Height" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Width x Height (Default: 538 x 400)</div>
            </div>

            <div class="form-group row mb-0">
                <label for="max_time" class="col-sm-3 col-form-label font-weight-bold">Session Timeout</label>
                <div class="col-sm-6">
                    <input name="max_time" type="text" class="form-control" id="max_time" value="<?php echo (int)$phpwcms["max_time"] ?>" maxlength="100" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">In Seconds (Default: 1800)</div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=3" class="btn btn-secondary">&larr; Previous Step</a>
        <button type="submit" class="btn btn-primary btn-lg">Save &amp; Continue &rarr;</button>
    </div>
    <input name="do" type="hidden" value="1" />
</form>
