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

$_SERVER['DOCUMENT_ROOT'] = $phpwcms['DOC_ROOT'];
$phpwcms["root"] = !empty($phpwcms["root"]) ? "/" . $phpwcms["root"] : "";

?>
<h2 class="h4 text-primary font-weight-normal mb-3">8. Finalization &amp; Permissions Check</h2>
<p class="text-muted">Review directory permissions and finalize installation.</p>

<div class="card mb-4 border">
    <div class="card-header bg-light font-weight-bold">Directory Permissions</div>
    <div class="list-group list-group-flush">
        <?php
        $dir_checks = [
            'Filestorage' => $phpwcms["root"] . "/" . $phpwcms["file_path"],
            'Deleted Files' => $phpwcms["root"] . "/" . $phpwcms["file_path"] . '/can_be_deleted',
            'Templates' => $phpwcms["root"] . "/" . $phpwcms["templates"],
            'Template Languages' => $phpwcms["root"] . "/" . trim($phpwcms["templates"], '/') . '/template_lang',
            'FTP Upload' => $phpwcms["root"] . "/" . $phpwcms["ftp_path"],
            'Frontend Content' => $phpwcms["root"] . "/" . $phpwcms["content_path"],
            'Frontend Images' => $phpwcms["root"] . "/" . $phpwcms["content_path"] . "/images",
            'Frontend Forms' => $phpwcms["root"] . "/" . $phpwcms["content_path"] . "/form",
            'Frontend Tmp' => $phpwcms["root"] . "/" . $phpwcms["content_path"] . "/tmp",
            'Frontend RSS' => $phpwcms["root"] . "/" . $phpwcms["content_path"] . "/rss",
            'Frontend Pages' => $phpwcms["root"] . "/" . $phpwcms["content_path"] . "/pages",
        ];

        foreach ($dir_checks as $label => $path):
            $status = check_path_status($path);
            if ($status !== 2) {
                $status = set_chmod($path, 0777, $status);
            }
            $is_ok = ($status === 2 || $status === 1);
            ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong><?php echo html_specialchars($label) ?>:</strong> <code><?php echo html_specialchars($path) ?></code>
                </div>
                <?php if ($is_ok): ?>
                    <span class="badge badge-success badge-pill">Writable (OK)</span>
                <?php else: ?>
                    <span class="badge badge-danger badge-pill">Not Writable</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$this_root = dirname(dirname(dirname(__FILE__)));
$config_setup = read_textfile($this_root . '/setup/setup.conf.inc.php');
$config_setup = str_replace('?>', "\$NO_ACCESS = true;\n\n", $config_setup);
$result = false;

if (!is_file($this_root . '/include/config/conf.inc.php')) {
    if (!write_textfile($this_root . '/include/config/conf.inc.php', $config_setup)) {
        if (!@copy($this_root . '/setup/setup.conf.inc.php', $this_root . '/include/config/conf.inc.php')) {
            if (@rename($this_root . '/setup/setup.conf.inc.php', $this_root . '/include/config/conf.inc.php')) {
                $result = true;
            }
        } else {
            $result = true;
        }
    } else {
        $result = true;
    }
}

@write_textfile($this_root . '/setup/.htaccess', 'Deny from all');
?>

<div class="card mb-4 border">
    <div class="card-header bg-light font-weight-bold">Configuration Status</div>
    <div class="card-body">
        <?php if ($result): ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> The configuration file <code>conf.inc.php</code> was successfully written to <code>include/config/</code>.</div>
        <?php else: ?>
            <div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Automated configuration file writing failed. Download <a href="get_conf_file.php" class="alert-link">conf.inc.php</a> manually and place it into <code>include/config/conf.inc.php</code>.</div>
        <?php endif; ?>

        <?php
        if (is_file($this_root . '/.htaccess')):
            ?>
            <div class="alert alert-info">A <code>.htaccess</code> file exists in document root. Compare against <a href="../_.htaccess" target="_blank" class="alert-link">_.htaccess</a> if using rewrite URLs.</div>
        <?php
        else:
            $ht_result = false;
            if (@copy($this_root . '/_.htaccess', $this_root . '/.htaccess') || @rename($this_root . '/_.htaccess', $this_root . '/.htaccess')) {
                $ht_result = true;
            }
            if ($ht_result):
                if ($phpwcms["root"] && $htaccess = @read_textfile($this_root . '/.htaccess')) {
                    $htaccess = str_replace('RewriteBase /', '#RewriteBase /', $htaccess);
                    $htaccess = str_replace('#RewriteBase /subfolder/', 'RewriteBase /' . trim($phpwcms["root"], '/') . '/', $htaccess);
                    write_textfile($this_root . '/.htaccess', $htaccess);
                }
                ?>
                <div class="alert alert-success mb-0"><i class="fa fa-check-circle"></i> Default <code>.htaccess</code> file successfully created in document root.</div>
            <?php else: ?>
                <div class="alert alert-warning mb-0">Failed to create <code>.htaccess</code> automatically. If using rewrite URLs, copy <code>_.htaccess</code> to <code>.htaccess</code> manually.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="alert alert-danger font-weight-bold py-3 my-4">
    <i class="fa fa-exclamation-triangle"></i> ATTENTION: Delete the <code>setup</code> directory immediately to secure your installation!
</div>

<div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
    <a href="setup.php?step=4" class="btn btn-secondary">&larr; Previous Step</a>
    <a href="../<?php echo $phpwcms['login.php'] ?>" class="btn btn-success btn-lg">Go to Login &rarr;</a>
</div>
