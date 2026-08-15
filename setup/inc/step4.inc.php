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
<h2 class="h4 text-primary font-weight-normal mb-3">5. Content &amp; Media Defaults</h2>

<form action="setup.php?step=4" method="post">
    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Content &amp; Image Dimension Limits</div>
        <div class="card-body">

            <div class="form-group row">
                <label for="file_maxsize" class="col-sm-3 col-form-label font-weight-bold">Max File Upload Size</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="file_maxsize" type="number" min="0" step="1024" class="form-control" id="file_maxsize" value="<?php echo (int)$phpwcms["file_maxsize"] ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text">Bytes</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">52428800 = 50 MB</div>
            </div>

            <div class="form-group row">
                <label for="content_width" class="col-sm-3 col-form-label font-weight-bold">Content Column Width</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="content_width" type="number" min="100" class="form-control" id="content_width" value="<?php echo (int)$phpwcms["content_width"] ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text">px</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Base width (Default: 538)</div>
            </div>

            <div class="form-group row">
                <label for="img_list_width" class="col-sm-3 col-form-label font-weight-bold">Thumbnail Dimensions</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="img_list_width" type="number" min="10" class="form-control" id="img_list_width" value="<?php echo (int)$phpwcms["img_list_width"] ?>" placeholder="Width" />
                        <div class="input-group-prepend input-group-append">
                            <span class="input-group-text">&times;</span>
                        </div>
                        <input name="img_list_height" type="number" min="10" class="form-control" id="img_list_height" value="<?php echo (int)$phpwcms["img_list_height"] ?>" placeholder="Height" />
                        <div class="input-group-append">
                            <span class="input-group-text">px</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: 100 &times; 75</div>
            </div>

            <div class="form-group row">
                <label for="img_prev_width" class="col-sm-3 col-form-label font-weight-bold">Preview Dimensions</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="img_prev_width" type="number" min="10" class="form-control" id="img_prev_width" value="<?php echo (int)$phpwcms["img_prev_width"] ?>" placeholder="Width" />
                        <div class="input-group-prepend input-group-append">
                            <span class="input-group-text">&times;</span>
                        </div>
                        <input name="img_prev_height" type="number" min="10" class="form-control" id="img_prev_height" value="<?php echo (int)$phpwcms["img_prev_height"] ?>" placeholder="Height" />
                        <div class="input-group-append">
                            <span class="input-group-text">px</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: 538 &times; 400</div>
            </div>

            <div class="form-group row mb-0">
                <label for="max_time" class="col-sm-3 col-form-label font-weight-bold">Session Timeout</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="max_time" type="number" min="60" class="form-control" id="max_time" value="<?php echo (int)$phpwcms["max_time"] ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text">seconds</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: 1800 (30 min)</div>
            </div>

        </div>
    </div>

<?php
$img_tools = detect_system_image_tools();

// Recommended priority: Imagick > ImageMagick CLI > GraphicsMagick CLI > GD
$recommended_engine = 'GD2';
if ($img_tools['imagick']['installed']) {
    $recommended_engine = 'IMAGICK';
} elseif ($img_tools['imagemagick']['installed']) {
    $recommended_engine = 'IMAGEMAGICK';
} elseif ($img_tools['graphicsmagick']['installed']) {
    $recommended_engine = 'GRAPHICSMAGICK';
} elseif ($img_tools['gd']['installed']) {
    $recommended_engine = 'GD2';
}

$current_img_lib = !empty($phpwcms['image_library']) ? strtoupper($phpwcms['image_library']) : $recommended_engine;
$suggested_path = $phpwcms['library_path'] ?? '';
if (empty($suggested_path)) {
    if (!empty($img_tools['imagemagick']['path'])) {
        $suggested_path = dirname($img_tools['imagemagick']['path']);
    } elseif (!empty($img_tools['graphicsmagick']['path'])) {
        $suggested_path = dirname($img_tools['graphicsmagick']['path']);
    } elseif (!empty($img_tools['netpbm']['path'])) {
        $suggested_path = $img_tools['netpbm']['path'];
    }
}
?>
    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Image Processing &amp; Graphics Library</div>
        <div class="card-body">

            <div class="form-group row">
                <label for="image_library" class="col-sm-3 col-form-label font-weight-bold">Graphics Engine</label>
                <div class="col-sm-6">
                    <select name="image_library" class="custom-select" id="image_library">
                        <option value="Imagick"<?php echo ($current_img_lib === 'IMAGICK') ? ' selected="selected"' : '' ?>>Imagick &ndash; PHP Extension (PECL) <?php echo $img_tools['imagick']['installed'] ? ($recommended_engine === 'IMAGICK' ? '&bull; Recommended' : '&bull; Installed') : '' ?></option>
                        <option value="ImageMagick"<?php echo ($current_img_lib === 'IMAGEMAGICK') ? ' selected="selected"' : '' ?>>ImageMagick CLI &ndash; convert / magick <?php echo $img_tools['imagemagick']['installed'] ? ($recommended_engine === 'IMAGEMAGICK' ? '&bull; Recommended' : '&bull; Available') : '' ?></option>
                        <option value="GraphicsMagick"<?php echo ($current_img_lib === 'GRAPHICSMAGICK' || $current_img_lib === 'GM') ? ' selected="selected"' : '' ?>>GraphicsMagick CLI &ndash; gm <?php echo $img_tools['graphicsmagick']['installed'] ? ($recommended_engine === 'GRAPHICSMAGICK' ? '&bull; Recommended' : '&bull; Available') : '' ?></option>
                        <option value="GD2"<?php echo ($current_img_lib === 'GD2' || empty($current_img_lib)) ? ' selected="selected"' : '' ?>>GD2 &ndash; PHP GD Library 2.x <?php echo $img_tools['gd']['installed'] ? ($recommended_engine === 'GD2' ? '&bull; Recommended / Default' : '&bull; Installed') : '' ?></option>
                        <option value="NetPBM"<?php echo ($current_img_lib === 'NETPBM') ? ' selected="selected"' : '' ?>>NetPBM CLI Tools <?php echo $img_tools['netpbm']['installed'] ? '&bull; Available' : '' ?></option>
                        <option value="GD"<?php echo ($current_img_lib === 'GD') ? ' selected="selected"' : '' ?>>GD &ndash; PHP GD Library 1.x (Legacy)</option>
                    </select>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Priority: Imagick &gt; ImageMagick &gt; GraphicsMagick &gt; GD</div>
            </div>

            <div class="form-group row">
                <label for="library_path" class="col-sm-3 col-form-label font-weight-bold">CLI Library Path</label>
                <div class="col-sm-6">
                    <input name="library_path" type="text" class="form-control" id="library_path" value="<?php echo html_specialchars($suggested_path) ?>" placeholder="/usr/local/bin" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Path to binary directory (only for ImageMagick, GM or NetPBM)</div>
            </div>

            <div class="form-group row">
                <label for="jpg_quality" class="col-sm-3 col-form-label font-weight-bold">JPEG Quality</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="jpg_quality" type="number" min="10" max="100" class="form-control" id="jpg_quality" value="<?php echo isset($phpwcms['jpg_quality']) ? (int)$phpwcms['jpg_quality'] : 85 ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Range 25&ndash;100 (Default: 85)</div>
            </div>

            <div class="form-group row">
                <label for="webp_quality" class="col-sm-3 col-form-label font-weight-bold">WebP Quality</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input name="webp_quality" type="number" min="10" max="100" class="form-control" id="webp_quality" value="<?php echo isset($phpwcms['webp_quality']) ? (int)$phpwcms['webp_quality'] : 85 ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Range 25&ndash;100 (Default: 85)</div>
            </div>

            <div class="form-group row mb-0">
                <label for="sharpen_level" class="col-sm-3 col-form-label font-weight-bold">Sharpen Level</label>
                <div class="col-sm-6">
                    <select name="sharpen_level" class="custom-select" id="sharpen_level">
                        <option value="0"<?php echo (empty($phpwcms['sharpen_level'])) ? ' selected="selected"' : '' ?>>0 &ndash; No sharpening</option>
                        <option value="1"<?php echo (!isset($phpwcms['sharpen_level']) || (int)$phpwcms['sharpen_level'] === 1) ? ' selected="selected"' : '' ?>>1 &ndash; Subtle (Default)</option>
                        <option value="2"<?php echo ((int)($phpwcms['sharpen_level'] ?? 0) === 2) ? ' selected="selected"' : '' ?>>2 &ndash; Medium</option>
                        <option value="3"<?php echo ((int)($phpwcms['sharpen_level'] ?? 0) === 3) ? ' selected="selected"' : '' ?>>3 &ndash; High</option>
                        <option value="4"<?php echo ((int)($phpwcms['sharpen_level'] ?? 0) === 4) ? ' selected="selected"' : '' ?>>4 &ndash; Very High</option>
                        <option value="5"<?php echo ((int)($phpwcms['sharpen_level'] ?? 0) === 5) ? ' selected="selected"' : '' ?>>5 &ndash; Extra Sharp</option>
                    </select>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Applies to ImageMagick &amp; GraphicsMagick</div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=3" class="btn btn-secondary">&larr; Previous Step</a>
        <button type="submit" class="btn btn-primary">Save &amp; Continue &rarr;</button>
    </div>
    <input name="do" type="hidden" value="1" />
</form>
