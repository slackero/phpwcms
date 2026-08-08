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
<h2 class="h4 text-primary font-weight-normal mb-3">1. Server Environment Check</h2>
<p>Please review the system requirements and environment checks below before proceeding with the installation (PHP 8.2+, MySQL 5.6+ recommended).</p>

<div class="list-group mb-4">
    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
        <div>
            <h6 class="mb-0 font-weight-bold">Web Server</h6>
            <small class="text-muted"><?php echo empty($_SERVER['SERVER_SOFTWARE']) ? 'Unavailable' : html_specialchars($_SERVER['SERVER_SOFTWARE']) ?></small>
        </div>
        <span class="badge badge-success badge-pill">OK</span>
    </div>

    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
        <div>
            <h6 class="mb-0 font-weight-bold">PHP Version</h6>
            <small class="text-muted"><?php echo html_specialchars(phpversion()) ?></small>
        </div>
        <?php if (version_compare(phpversion(), '8.2.0', '>=')): ?>
            <span class="badge badge-success badge-pill">OK (<?php echo html_specialchars(phpversion()) ?>)</span>
        <?php else: $setup_recommend = false; ?>
            <span class="badge badge-danger badge-pill">Requires PHP 8.2+</span>
        <?php endif; ?>
    </div>

    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
        <div>
            <h6 class="mb-0 font-weight-bold">MySQLi Extension</h6>
            <small class="text-muted">PHP MySQLi database driver</small>
        </div>
        <?php if (function_exists('mysqli_connect')): ?>
            <span class="badge badge-success badge-pill">Installed</span>
        <?php else: $setup_recommend = false; ?>
            <span class="badge badge-danger badge-pill">Not Installed</span>
        <?php endif; ?>
    </div>

    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
        <div>
            <?php
            $mysqlnd = false;
            $mysql_version = mysqli_get_client_info();
            if (strpos($mysql_version, 'mysqlnd') !== false) {
                $mysqlnd = true;
            }
            ?>
            <h6 class="mb-0 font-weight-bold">MySQL Client Driver</h6>
            <small class="text-muted"><?php echo html_specialchars($mysql_version) ?></small>
        </div>
        <span class="badge badge-success badge-pill">OK</span>
    </div>

    <div class="list-group-item py-3">
        <?php
        $_phpinfo = parsePHPModules();
        $gd_version = (isset($_phpinfo['gd']['GD Support']) && $_phpinfo['gd']['GD Support'] === 'enabled' && isset($_phpinfo['gd']['GD Version']))
            ? html_specialchars($_phpinfo['gd']['GD Version'])
            : 'N/A';
        $is_gd = function_exists('imagegd2') || function_exists('imagegd');
        ?>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 font-weight-bold">GD Graphics Library</h6>
                <small class="text-muted"><?php echo $gd_version ?></small>
            </div>
            <?php if ($is_gd): ?>
                <span class="badge badge-success badge-pill">Installed</span>
            <?php else: ?>
                <span class="badge badge-warning badge-pill">Not Installed</span>
            <?php endif; ?>
        </div>

        <?php if ($is_gd): ?>
            <div class="mt-2 pt-2 border-top small">
                <strong>Formats Supported: </strong>
                <?php if (imagetypes() & IMG_GIF): ?><span class="badge badge-light border">GIF</span> <?php endif; ?>
                <?php if (imagetypes() & IMG_PNG): ?><span class="badge badge-light border">PNG</span> <?php endif; ?>
                <?php if (imagetypes() & IMG_JPG): ?><span class="badge badge-light border">JPG</span> <?php endif; ?>
                <?php if (defined('IMG_WEBP') && (imagetypes() & IMG_WEBP)): ?><span class="badge badge-light border">WebP</span> <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
if (!is_writable($DOCROOT . '/setup/setup.conf.inc.php')) {
    if (!@chmod($DOCROOT . '/setup/setup.conf.inc.php', 0666)) {
        echo '<div class="alert alert-danger mb-4">';
        echo '<h5 class="alert-heading"><i class="fa fa-exclamation-circle"></i> File Not Writable</h5>';
        echo '<p class="mb-0">The setup configuration file <code>setup/setup.conf.inc.php</code> is not writable. Please set permissions to <code>chmod 777</code> or <code>chmod 666</code> via FTP before continuing.</p>';
        echo '</div>';
    }
} else {
    if (!$setup_recommend) {
        echo '<div class="alert alert-warning mb-4">';
        echo '<strong><i class="fa fa-warning"></i> Warning:</strong> Some recommended system requirements are not met. Review warnings before continuing.';
        echo '</div>';
    }
    ?>
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="index.php" class="btn btn-secondary">&larr; Back to Licence</a>
        <div>
            <a href="setup.php?step=1" class="btn btn-primary btn-lg">Start Setup &rarr;</a>
        </div>
    </div>
    <?php
}
