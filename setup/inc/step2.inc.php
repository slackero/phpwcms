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
<h2 class="h4 text-primary font-weight-normal mb-3">3. Site URL &amp; Email Transport</h2>

<?php if ($err): ?>
    <div class="alert alert-danger mb-4"><i class="fa fa-exclamation-triangle"></i> Please check your site and email settings.</div>
<?php endif; ?>

<form action="setup.php?step=2" method="post">

    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Site URL Basis</div>
        <div class="card-body">
            <div class="form-group row mb-0">
                <label for="site" class="col-sm-3 col-form-label font-weight-bold">Site Basis URL</label>
                <div class="col-sm-6">
                    <input name="site" type="url" class="form-control" id="site" value="<?php echo html_specialchars($phpwcms["site"]) ?>" placeholder="<?php echo get_url_origin(true); ?>" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Detected: <code><?php echo get_url_origin(true); ?></code></div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Email &amp; SMTP Transport</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="smtp_from_email" class="col-sm-3 col-form-label font-weight-bold">From / Reply-To Email</label>
                <div class="col-sm-6">
                    <input name="smtp_from_email" type="email" class="form-control" id="smtp_from_email" value="<?php echo ($phpwcms['SMTP_FROM_EMAIL']) ? html_specialchars($phpwcms['SMTP_FROM_EMAIL']) : html_specialchars($phpwcms["admin_email"]) ?>" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Sender email address</div>
            </div>

            <div class="form-group row">
                <label for="smtp_from_name" class="col-sm-3 col-form-label font-weight-bold">From / Reply-To Name</label>
                <div class="col-sm-6">
                    <input name="smtp_from_name" type="text" class="form-control" id="smtp_from_name" value="<?php echo ($phpwcms['SMTP_FROM_NAME']) ? html_specialchars($phpwcms['SMTP_FROM_NAME']) : 'webmaster' ?>" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Sender display name</div>
            </div>

            <div class="form-group row">
                <label for="smtp_mailer" class="col-sm-3 col-form-label font-weight-bold">Mail Transport</label>
                <div class="col-sm-6">
                    <select name="smtp_mailer" id="smtp_mailer" class="custom-select">
                        <option value="mail"<?php if (strtolower($phpwcms['SMTP_MAILER']) === 'mail') echo ' selected="selected"'; ?>>PHP mail()</option>
                        <option value="smtp"<?php if (strtolower($phpwcms['SMTP_MAILER']) === 'smtp') echo ' selected="selected"'; ?>>SMTP Server</option>
                        <option value="sendmail"<?php if (strtolower($phpwcms['SMTP_MAILER']) === 'sendmail') echo ' selected="selected"'; ?>>UNIX sendmail</option>
                    </select>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: PHP mail()</div>
            </div>

            <div class="form-group row">
                <label for="smtp_host" class="col-sm-3 col-form-label font-weight-bold">SMTP Host &amp; Port</label>
                <div class="col-sm-6 d-flex">
                    <input name="smtp_host" type="text" class="form-control mr-2" id="smtp_host" value="<?php echo ($phpwcms['SMTP_HOST']) ? html_specialchars($phpwcms['SMTP_HOST']) : 'localhost' ?>" placeholder="localhost" />
                    <input name="smtp_port" type="number" min="1" max="65535" class="form-control" id="smtp_port" style="max-width: 90px;" value="<?php echo ($phpwcms['SMTP_PORT']) ? (int)$phpwcms['SMTP_PORT'] : '25'; ?>" placeholder="25" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: localhost / 25</div>
            </div>

            <div class="form-group row">
                <label for="smtp_secure" class="col-sm-3 col-form-label font-weight-bold">Encryption</label>
                <div class="col-sm-6">
                    <select name="smtp_secure" id="smtp_secure" class="custom-select">
                        <option value=""<?php if (empty($phpwcms['SMTP_SECURE'])) echo ' selected="selected"'; ?>>None (Plain Text)</option>
                        <option value="tls"<?php if (strtolower($phpwcms['SMTP_SECURE']) === 'tls') echo ' selected="selected"'; ?>>STARTTLS (TLS)</option>
                        <option value="ssl"<?php if (strtolower($phpwcms['SMTP_SECURE']) === 'ssl') echo ' selected="selected"'; ?>>SMTPS (SSL)</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <div class="custom-control custom-checkbox">
                        <input name="smtp_auth" type="checkbox" class="custom-control-input" id="smtp_auth" value="1" <?php if ((int)$phpwcms['SMTP_AUTH'] === 1) echo 'checked="checked"'; ?> />
                        <label class="custom-control-label font-weight-bold" for="smtp_auth">Use SMTP Authentication</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="smtp_user" class="col-sm-3 col-form-label">SMTP Username</label>
                <div class="col-sm-6">
                    <input name="smtp_user" type="text" class="form-control" id="smtp_user" value="<?php echo html_specialchars($phpwcms["SMTP_USER"] ?? '') ?>" autocomplete="off" />
                </div>
            </div>

            <div class="form-group row mb-0">
                <label for="smtp_pass" class="col-sm-3 col-form-label">SMTP Password</label>
                <div class="col-sm-6">
                    <input name="smtp_pass" type="password" class="form-control" id="smtp_pass" value="<?php echo html_specialchars($phpwcms["SMTP_PASS"] ?? '') ?>" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=1" class="btn btn-secondary">&larr; Previous Step</a>
        <button type="submit" class="btn btn-primary">Save &amp; Continue &rarr;</button>
    </div>
    <input name="do" type="hidden" value="1" />
</form>
