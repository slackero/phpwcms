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

$_SESSION['admin_set'] = false;
$setup_recommend = true;

?>
<h2 class="h4 text-primary fw-normal mb-3">2. System Requirements &amp; Environment Check</h2>
<p class="text-muted">Please review the system requirements and environment checks below before proceeding with the installation (PHP 8.2+, MySQL 5.6+ recommended).</p>

<div class="card mb-4 border">
    <div class="card-header bg-light fw-bold">Core Environment</div>
    <div class="list-group list-group-flush">
        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div>
                <h6 class="mb-0 fw-bold">Web Server</h6>
                <small class="text-muted"><?php echo empty($_SERVER['SERVER_SOFTWARE']) ? 'Unavailable' : html_specialchars($_SERVER['SERVER_SOFTWARE']) ?></small>
            </div>
            <span class="badge text-bg-success rounded-pill">OK</span>
        </div>

        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div>
                <h6 class="mb-0 fw-bold">PHP Version</h6>
                <small class="text-muted"><?php echo html_specialchars(phpversion()) ?></small>
            </div>
            <?php if (version_compare(phpversion(), '8.2.0', '>=')): ?>
                <span class="badge text-bg-success rounded-pill">OK (<?php echo html_specialchars(phpversion()) ?>)</span>
            <?php else: $setup_recommend = false; ?>
                <span class="badge text-bg-danger rounded-pill">Requires PHP 8.2+</span>
            <?php endif; ?>
        </div>

        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div>
                <h6 class="mb-0 fw-bold">MySQLi Extension</h6>
                <small class="text-muted">PHP MySQLi database driver</small>
            </div>
            <?php if (function_exists('mysqli_connect')): ?>
                <span class="badge text-bg-success rounded-pill">Installed</span>
            <?php else: $setup_recommend = false; ?>
                <span class="badge text-bg-danger rounded-pill">Not Installed</span>
            <?php endif; ?>
        </div>

        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div>
                <?php
                $mysql_version = function_exists('mysqli_get_client_info') ? mysqli_get_client_info() : 'Unavailable';
                ?>
                <h6 class="mb-0 fw-bold">MySQL Client Driver</h6>
                <small class="text-muted"><?php echo html_specialchars($mysql_version) ?></small>
            </div>
            <?php if ($mysql_version !== 'Unavailable'): ?>
                <span class="badge text-bg-success rounded-pill">OK</span>
            <?php else: ?>
                <span class="badge text-bg-danger rounded-pill">Not Installed</span>
            <?php endif; ?>
        </div>

        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div>
                <h6 class="mb-0 fw-bold">Sodium Library</h6>
                <small class="text-muted">for encryption, decryption, signatures, hashing</small>
            </div>
            <?php if (function_exists('sodium_crypto_sign_verify_detached')): ?>
                <span class="badge text-bg-success rounded-pill">Installed</span>
            <?php else: ?>
                <span class="badge text-bg-info rounded-pill">Not Installed</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$img_tools = detect_system_image_tools();
if (!$img_tools['gd']['installed'] && !$img_tools['imagick']['installed'] && !$img_tools['imagemagick']['installed'] && !$img_tools['graphicsmagick']['installed'] && !$img_tools['netpbm']['installed']) {
    $setup_recommend = false;
}
?>
<div class="card mb-4 border">
    <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
        <span>Image Processing &amp; Graphics Tools</span>
        <small class="text-muted fw-normal">At least one graphics library is required for thumbnail generation</small>
    </div>
    <div class="list-group list-group-flush">
        <!-- Imagick PECL Extension -->
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">Imagick (PHP Extension)</h6>
                    <small class="text-muted">
                        <?php if ($img_tools['imagick']['installed']): ?>
                            v<?php echo html_specialchars($img_tools['imagick']['version']) ?>
                            <?php if (!empty($img_tools['imagick']['path'])): ?>
                                &bull; <?php echo html_specialchars($img_tools['imagick']['path']) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            PHP PECL extension (<code>\Imagick</code>)
                        <?php endif; ?>
                    </small>
                </div>
                <?php if ($img_tools['imagick']['installed']): ?>
                    <span class="badge text-bg-success rounded-pill">Installed</span>
                <?php else: ?>
                    <span class="badge text-bg-light text-muted border rounded-pill">Not Installed</span>
                <?php endif; ?>
            </div>
            <?php if ($img_tools['imagick']['installed']): ?>
                <div class="mt-2 pt-2 border-top small">
                    <strong>Supported Formats: </strong>
                    <?php echo render_format_badges($img_tools['imagick']['details'], array('JPEG', 'PNG', 'GIF', 'WebP', 'AVIF', 'PDF', 'EPS', 'PS', 'AI', 'SVG', 'TIFF')) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ImageMagick CLI -->
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">ImageMagick (CLI)</h6>
                    <small class="text-muted">
                        <?php if ($img_tools['imagemagick']['installed']): ?>
                            <code><?php echo html_specialchars($img_tools['imagemagick']['path']) ?></code>
                            <?php if (!empty($img_tools['imagemagick']['version'])): ?>
                                <br><span class="text-secondary"><?php echo html_specialchars($img_tools['imagemagick']['version']) ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            Standalone command line utility (<code>convert</code> / <code>magick</code>)
                        <?php endif; ?>
                    </small>
                </div>
                <?php if ($img_tools['imagemagick']['installed']): ?>
                    <span class="badge text-bg-success rounded-pill">Available</span>
                <?php else: ?>
                    <span class="badge text-bg-light text-muted border rounded-pill">Not Found</span>
                <?php endif; ?>
            </div>
            <?php if ($img_tools['imagemagick']['installed']): ?>
                <div class="mt-2 pt-2 border-top small">
                    <strong>Supported Formats: </strong>
                    <?php echo render_format_badges($img_tools['imagemagick']['details'], array('JPEG', 'PNG', 'GIF', 'WebP', 'AVIF', 'PDF', 'EPS', 'PS', 'AI', 'SVG', 'TIFF')) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- GraphicsMagick CLI -->
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">GraphicsMagick (CLI)</h6>
                    <small class="text-muted">
                        <?php if ($img_tools['graphicsmagick']['installed']): ?>
                            <code><?php echo html_specialchars($img_tools['graphicsmagick']['path']) ?></code>
                            <?php if (!empty($img_tools['graphicsmagick']['version'])): ?>
                                <br><span class="text-secondary"><?php echo html_specialchars($img_tools['graphicsmagick']['version']) ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            High-performance ImageMagick fork (<code>gm</code>)
                        <?php endif; ?>
                    </small>
                </div>
                <?php if ($img_tools['graphicsmagick']['installed']): ?>
                    <span class="badge text-bg-success rounded-pill">Available</span>
                <?php else: ?>
                    <span class="badge text-bg-light text-muted border rounded-pill">Not Found</span>
                <?php endif; ?>
            </div>
            <?php if ($img_tools['graphicsmagick']['installed']): ?>
                <div class="mt-2 pt-2 border-top small">
                    <strong>Supported Formats: </strong>
                    <?php echo render_format_badges($img_tools['graphicsmagick']['details'], array('JPEG', 'PNG', 'GIF', 'WebP', 'PDF', 'EPS', 'PS', 'SVG', 'TIFF')) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- GD Library -->
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">GD Graphics Library</h6>
                    <small class="text-muted"><?php echo html_specialchars($img_tools['gd']['version'] ?: 'PHP Extension') ?></small>
                </div>
                <?php if ($img_tools['gd']['installed']): ?>
                    <span class="badge text-bg-success rounded-pill">Installed</span>
                <?php else: ?>
                    <span class="badge text-bg-warning rounded-pill">Not Installed</span>
                <?php endif; ?>
            </div>
            <?php if ($img_tools['gd']['installed']): ?>
                <div class="mt-2 pt-2 border-top small">
                    <strong>Supported Formats: </strong>
                    <?php echo render_format_badges($img_tools['gd']['details'], array('GIF', 'JPEG', 'PNG', 'WebP', 'AVIF', 'BMP', 'FreeType (TTF)')) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ghostscript -->
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">Ghostscript (PDF &amp; EPS Engine)</h6>
                    <small class="text-muted">
                        <?php if ($img_tools['ghostscript']['installed']): ?>
                            <code><?php echo html_specialchars($img_tools['ghostscript']['path']) ?></code>
                            <?php if (!empty($img_tools['ghostscript']['version'])): ?>
                                &bull; <?php echo html_specialchars($img_tools['ghostscript']['version']) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            PostScript and PDF interpreter for ImageMagick / GraphicsMagick (<code>gs</code>)
                        <?php endif; ?>
                    </small>
                </div>
                <?php if ($img_tools['ghostscript']['installed']): ?>
                    <span class="badge text-bg-success rounded-pill">Available (PDF &amp; EPS Enabled)</span>
                <?php else: ?>
                    <span class="badge text-bg-light text-muted border rounded-pill">Not Found (Optional for PDF/EPS)</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- NetPBM Tools -->
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">NetPBM Tools (CLI)</h6>
                    <small class="text-muted">
                        <?php if ($img_tools['netpbm']['installed']): ?>
                            <code><?php echo html_specialchars($img_tools['netpbm']['path']) ?></code>
                            <?php if (!empty($img_tools['netpbm']['version'])): ?>
                                &bull; <?php echo html_specialchars($img_tools['netpbm']['version']) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            Lightweight toolkit for image manipulation (<code>pnmscale</code>)
                        <?php endif; ?>
                    </small>
                </div>
                <?php if ($img_tools['netpbm']['installed']): ?>
                    <span class="badge text-bg-success rounded-pill">Available</span>
                <?php else: ?>
                    <span class="badge text-bg-light text-muted border rounded-pill">Not Found</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border">
    <div class="card-header bg-light fw-bold">PHP Extensions &amp; Environment Settings</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h6 class="fw-bold text-muted small text-uppercase mb-2">Recommended Extensions</h6>
                <ul class="list-unstyled mb-0 small">
                    <?php
                    $exts = array(
                        'curl'      => 'cURL (Remote requests)',
                        'mbstring'  => 'Multibyte String (mbstring)',
                        'intl'      => 'Internationalization (intl)',
                        'openssl'   => 'OpenSSL (Secure comms)',
                        'zip'       => 'Zip Archive Support',
                        'fileinfo'  => 'Fileinfo (MIME detection)'
                    );
                    foreach ($exts as $ext => $label) {
                        $loaded = extension_loaded($ext);
                        echo '<li class="d-flex justify-content-between align-items-center py-1">';
                        echo '<span>' . html_specialchars($label) . '</span>';
                        echo $loaded ? '<span class="badge text-bg-success">OK</span>' : '<span class="badge text-bg-light text-muted border">Optional</span>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="col-sm-6">
                <h6 class="fw-bold text-muted small text-uppercase mb-2">PHP Configuration Directives</h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="d-flex justify-content-between align-items-center py-1">
                        <span>Memory Limit</span>
                        <code><?php echo html_specialchars(ini_get('memory_limit') ?: 'N/A') ?></code>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1">
                        <span>Upload Max Filesize</span>
                        <code><?php echo html_specialchars(ini_get('upload_max_filesize') ?: 'N/A') ?></code>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1">
                        <span>Post Max Size</span>
                        <code><?php echo html_specialchars(ini_get('post_max_size') ?: 'N/A') ?></code>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1">
                        <span>Max Execution Time</span>
                        <code><?php echo html_specialchars(ini_get('max_execution_time') ?: '0') ?>s</code>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
if (!is_writable($DOCROOT . '/setup/setup.conf.inc.php')) {
    if (!@chmod($DOCROOT . '/setup/setup.conf.inc.php', 0666)) {
        echo '<div class="alert alert-danger mb-4">';
        echo '<h5 class="alert-heading"><i class="fa fa-exclamation-circle"></i> File Not Writable</h5>';
        echo '<p class="mb-0">The setup configuration file <code>setup/setup.conf.inc.php</code> is not writable. Please set permissions to <code>chmod 666</code> via FTP before continuing.</p>';
        echo '</div>';
    }
} else {
    if (!$setup_recommend) {
        echo '<div class="alert alert-warning mb-4">';
        echo '<strong><i class="fa fa-warning"></i> Warning:</strong> Some recommended system requirements are not met. Review warnings before continuing.';
        echo '</div>';
    }

    // Validate any submitted / already-configured whitelabel key
    $_wl_key_submitted = trim($_POST['whitelabel_key'] ?? $phpwcms['whitelabel_key'] ?? '');
    $_wl_payload        = false;
    $_wl_key_error      = false;

    if ($_wl_key_submitted !== '') {
        $_wl_payload = setup_validate_whitelabel_key($_wl_key_submitted);
        if ($_wl_payload === false) {
            $_wl_key_error = true;
        }
    }

    // Determine brand_table_prefix display value — only when license is valid.
    // Preserve the saved value only if it's a real custom value (not '' and not the default 'phpwcms').
    // Fall back to the payload suggestion if it's also a custom value; otherwise leave blank
    // so the placeholder ('phpwcms') signals the implicit default without pre-filling it.
    if ($_wl_payload !== false) {
        $_saved_prefix = $phpwcms['brand_table_prefix'] ?? '';
        if ($_saved_prefix !== '' && $_saved_prefix !== 'phpwcms') {
            // Real custom value saved — restore it for the user
            $_wl_brand_prefix_val = $_saved_prefix;
        } elseif (!empty($_wl_payload['brand_table_prefix']) && $_wl_payload['brand_table_prefix'] !== 'phpwcms') {
            // License payload carries a custom suggestion — pre-fill with it
            $_wl_brand_prefix_val = $_wl_payload['brand_table_prefix'];
        } else {
            $_wl_brand_prefix_val = '';
        }
    }
?>

    <form action="setup.php?step=0" method="post">
        <div class="card mb-4 border">
            <div class="card-header bg-light fw-bold">
                Whitelabel License <span class="fw-normal text-muted small">(optional)</span>
            </div>
            <div class="card-body">

                <?php if (!function_exists('sodium_crypto_sign_verify_detached')): ?>
                    <div class="alert alert-warning mb-0">
                        <i class="fa fa-exclamation-triangle"></i>
                        The PHP <code>sodium</code> extension is not available on this server.
                        Whitelabel license validation requires libsodium (PHP 7.2+, usually bundled).
                        Please enable the extension to use this feature.
                    </div>
                <?php else: ?>

                    <div class="form-group row<?php echo $_wl_key_error ? ' has-error' : '' ?>">
                        <label for="whitelabel_key" class="col-sm-3 col-form-label fw-bold">License Key</label>
                        <div class="col-sm-9">
                            <textarea name="whitelabel_key" id="whitelabel_key" class="form-control font-monospace<?php echo $_wl_key_error ? ' is-invalid' : ($_wl_payload !== false ? ' is-valid' : '') ?>" rows="3" placeholder="Paste your signed whitelabel license key here &hellip;"><?php echo html_specialchars($_wl_key_submitted) ?></textarea>
                            <?php if ($_wl_key_error): ?>
                                <div class="invalid-feedback">
                                    The license key is invalid, expired, or the signature could not be verified.
                                </div>
                            <?php elseif ($_wl_payload !== false): ?>
                                <div class="valid-feedback d-block text-success small mt-1">
                                    <i class="fa fa-check-circle"></i>
                                    License valid
                                    <?php
                                    $wl_info = [];
                                    if (!empty($_wl_payload['licensee'])) {
                                        $wl_info[] = 'Licensee: <strong>' . html_specialchars($_wl_payload['licensee']) . '</strong>';
                                    }
                                    if (!empty($_wl_payload['domain'])) {
                                        $wl_info[] = 'Domain: <strong>' . html_specialchars($_wl_payload['domain']) . '</strong>';
                                    }
                                    if (!empty($_wl_payload['valid_until']) && (int)$_wl_payload['valid_until'] > 0) {
                                        $wl_info[] = 'Expires: <strong>' . date('Y-m-d', (int)$_wl_payload['valid_until']) . '</strong>';
                                    }
                                    if ($wl_info) {
                                        echo ' &mdash; ' . implode(' &bull; ', $wl_info);
                                    }
                                    ?>
                                </div>
                            <?php else: ?>
                                <div class="form-text text-muted small mt-1">
                                    Leave empty for a standard open-source installation.
                                    The key must be set <strong>before</strong> the Database setup!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($_wl_payload !== false): ?>
                    <div class="form-group row mb-0 mt-3">
                        <label for="brand_table_prefix" class="col-sm-3 col-form-label fw-bold">Brand Table Prefix</label>
                        <div class="col-sm-6">
                            <input name="brand_table_prefix" type="text" class="form-control" id="brand_table_prefix" value="<?php echo html_specialchars($_wl_brand_prefix_val) ?>" placeholder="phpwcms" maxlength="64" pattern="[a-zA-Z0-9_]*" />
                        </div>
                        <div class="col-sm-3 form-text text-muted small align-self-center">
                            Replaces <code>phpwcms</code> in all DB table names.
                            <?php if (!empty($_wl_payload['brand_table_prefix'])): ?>
                                License suggests: <code><?php echo html_specialchars($_wl_payload['brand_table_prefix']) ?></code>
                            <?php endif; ?>
                            Only <code>a-z A-Z 0-9 _</code> allowed.
                        </div>
                    </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="index.php" class="btn btn-secondary">&larr; Back to Licence</a>
            <button type="submit" name="do" value="1" class="btn btn-primary">Save &amp; Start Setup &rarr;</button>
        </div>
    </form>
<?php
}
