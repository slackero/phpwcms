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
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}

$_wl_key_submitted = isset($_POST['whitelabel_key']) ? trim($_POST['whitelabel_key']) : ($phpwcms['whitelabel_key'] ?? '');
if (empty($_wl_key_submitted) && !empty($_SESSION['whitelabel_key'])) {
    $_wl_key_submitted = $_SESSION['whitelabel_key'];
}
$_wl_payload       = setup_validate_whitelabel_key($_wl_key_submitted);
$_wl_key_error     = !empty($err) && $err == 1;
$_has_sodium       = function_exists('sodium_crypto_sign_verify_detached');

?>
<h2 class="h4 text-primary fw-normal mb-3">3. Edition &amp; Whitelabel License</h2>
<p class="text-muted">Choose your installation edition. Standard open-source phpwcms is completely free under the GNU General Public License (GPL-2.0). If you have a signed whitelabel license, enter your key below to unlock custom branding and whitelabeling options.</p>

<?php if ($_wl_key_error): ?>
    <div class="alert alert-danger mb-4">
        <i class="fa fa-exclamation-triangle"></i> The license key is invalid, expired, or the signature could not be verified. Please check your key or leave the field empty to use the standard edition.
    </div>
<?php endif; ?>

<form action="setup.php?step=1" method="post">

    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card h-100 border<?php echo $_wl_payload === false ? ' border-primary shadow-sm' : '' ?>">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Standard Edition</span>
                    <span class="badge text-bg-success">GPL-2.0</span>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted small">The free, full-featured open-source edition of phpwcms for personal and commercial projects.</p>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><i class="fa fa-check text-success me-1"></i> Full CMS functionality</li>
                        <li class="mb-1"><i class="fa fa-check text-success me-1"></i> GNU General Public License (GPL)</li>
                        <li class="mb-1"><i class="fa fa-check text-success me-1"></i> No license key required</li>
                        <li class="text-muted"><i class="fa fa-info-circle text-muted me-1"></i> Standard phpwcms branding &amp; copyright</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border<?php echo $_wl_payload !== false ? ' border-primary shadow-sm' : '' ?>">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Whitelabel Edition</span>
                    <span class="badge text-bg-primary">Commercial</span>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted small">For agencies and businesses requiring custom backend branding, proprietary table prefixes, and copyright replacement.</p>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><i class="fa fa-check text-primary me-1"></i> Custom brand name and logo</li>
                        <li class="mb-1"><i class="fa fa-check text-primary me-1"></i> Custom copyright and URLs</li>
                        <li class="mb-1"><i class="fa fa-check text-primary me-1"></i> Custom database table prefix</li>
                        <li><i class="fa fa-key text-primary me-1"></i> Requires cryptographically signed key</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border">
        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span>Whitelabel License Key</span>
            <span class="text-muted fw-normal small">Optional</span>
        </div>
        <div class="card-body">

            <?php if (!$_has_sodium): ?>
                <div class="alert alert-warning mb-0">
                    <i class="fa fa-exclamation-triangle"></i>
                    The PHP <code>sodium</code> extension is not available on this server.
                    Whitelabel license validation requires libsodium (PHP 7.2+, usually bundled).
                    Please enable the extension to use this feature.
                </div>
            <?php else: ?>

                <div class="form-group row mb-0<?php echo $_wl_key_error ? ' has-error' : '' ?>">
                    <label for="whitelabel_key" class="col-sm-3 col-form-label fw-bold">License Key</label>
                    <div class="col-sm-9">
                        <textarea name="whitelabel_key" id="whitelabel_key" class="form-control font-monospace<?php echo $_wl_key_error ? ' is-invalid' : ($_wl_payload !== false ? ' is-valid' : '') ?>" rows="4" placeholder="Paste your signed whitelabel license key here &hellip;"><?php echo html_specialchars($_wl_key_submitted) ?></textarea>
                        <?php if ($_wl_key_error): ?>
                            <div class="invalid-feedback">
                                The license key is invalid, expired, or the signature could not be verified.
                            </div>
                        <?php elseif ($_wl_payload !== false): ?>
                            <div class="valid-feedback d-block text-success small mt-2">
                                <i class="fa fa-check-circle"></i>
                                <strong>License Valid:</strong>
                                <?php
                                $wl_info = array();
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
                                    echo ' ' . implode(' &bull; ', $wl_info);
                                }
                                ?>
                            </div>
                            <div class="form-text text-muted small mt-1">
                                To revert to the standard edition, clear the license key field and click Continue.
                            </div>
                        <?php else: ?>
                            <div class="form-text text-muted small mt-1">
                                Leave this field empty to proceed with the standard open-source edition under GPL-2.0.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=0" class="btn btn-secondary">&larr; Back to System Check</a>
        <button type="submit" name="do" value="1" class="btn btn-primary">Start Setup &rarr;</button>
    </div>
</form>
