<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

session_start();

$phpwcms = array();

require_once __DIR__ . '/inc/setup.func.inc.php';

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>phpwcms Setup - Licence</title>
<link href="../include/inc_css/backend.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-light">

<div class="container my-4" style="max-width: 900px;">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header text-white d-flex justify-content-between align-items-center py-3" style="background-color: #4B6F92;">
            <div class="d-flex align-items-center">
                <a href="https://www.phpwcms.org" target="_blank" class="d-inline-block">
                    <img src="../img/phpwcms-logo-white.svg" alt="phpwcms" height="38" class="align-middle">
                </a>
                <span class="badge badge-primary ml-3">SETUP</span>
            </div>
            <div class="text-right small text-white-50">
                <div>VERSION <?php echo html_specialchars($phpwcms_version) ?></div>
                <div>RELEASE <?php echo html_specialchars($phpwcms_release_date) ?></div>
            </div>
        </div>

        <div class="card-body p-4">
            <?php echo render_setup_steps('license'); ?>

            <h1 class="h3 font-weight-normal text-primary mb-3">Welcome to the setup of phpwcms</h1>

            <?php if (is_file($DOCROOT . '/include/config/conf.inc.php')): ?>
                <div class="alert alert-danger border-danger shadow-sm my-4">
                    <h5 class="alert-heading font-weight-bold mb-2"><i class="fa fa-ban"></i> Installation Blocked</h5>
                    <p class="mb-2">An existing installation was detected (<code>include/config/conf.inc.php</code> exists). Running the setup process on an active installation is blocked for security.</p>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">If you wish to re-run setup, remove <code>include/config/conf.inc.php</code> manually or go to backend login.</small>
                        <a href="../login.php" class="btn btn-danger font-weight-bold">Go to Login &rarr;</a>
                    </div>
                </div>
            <?php else: ?>
                <p class="lead">This is the setup wizard to install or configure <strong>phpwcms</strong>.</p>
                <p><strong>phpwcms</strong> is open source software released under the <a href="../include/GPL.html" target="_blank"><strong>GNU General Public License (GPL)</strong></a>. Before you continue setting up phpwcms, please read the license carefully.</p>

                <div class="card bg-white border p-3 my-4 overflow-auto" style="max-height: 350px; font-size: 0.9rem; line-height: 1.5;">
                    <h5 class="text-center font-weight-bold">The GNU General Public License (GPL)<br><small class="text-muted">Version 2, June 1991</small></h5>
                    <p class="text-center text-muted small">Copyright (C) 1989, 1991 Free Software Foundation, Inc.<br>59 Temple Place, Suite 330, Boston, MA 02111-1307 USA</p>
                    <p class="text-center text-muted small">Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.</p>

                    <h6 class="font-weight-bold mt-3">Preamble</h6>
                    <p>The licenses for most software are designed to take away your freedom to share and change it. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free software--to make sure the software is free for all its users. This General Public License applies to most of the Free Software Foundation's software and to any other program whose authors commit to using it. You can apply it to your programs, too.</p>
                    <p>When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for this service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.</p>
                    <p>To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights. These restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.</p>
                    <p>For example, if you distribute copies of such a program, whether gratis or for a fee, you must give the recipients all the rights that you have. You must make sure that they, too, receive or can get the source code. And you must show them these terms so they know their rights.</p>

                    <h6 class="font-weight-bold mt-3">NO WARRANTY</h6>
                    <p class="small text-uppercase">BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND.</p>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="../index.php" class="btn btn-secondary">Cancel</a>
                    <a href="setup.php?step=0" class="btn btn-primary">I Agree &amp; Continue &rarr;</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="card-footer bg-white text-muted text-center py-3 small">
            &copy; 2002&ndash;<?php echo date('Y') ?> <a href="https://www.phpwcms.org" target="_blank">Oliver Georgi</a>. Released under the <a href="#" data-toggle="modal" data-target="#gplModal">GNU General Public License</a>.
        </div>
    </div>
</div>

<div class="modal fade" id="gplModal" tabindex="-1" role="dialog" aria-labelledby="gplModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold" id="gplModalLabel">GNU General Public License (GPL-2.0)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="font-size: 0.9rem; line-height: 1.5;">
                <h6 class="text-center font-weight-bold mb-1">GNU GENERAL PUBLIC LICENSE</h6>
                <p class="text-center text-muted small mb-3">Version 2, June 1991</p>
                <p class="text-center text-muted small">Copyright (C) 1989, 1991 Free Software Foundation, Inc.<br>59 Temple Place, Suite 330, Boston, MA 02111-1307 USA</p>
                <hr>
                <h6 class="font-weight-bold mt-3">Preamble</h6>
                <p>The licenses for most software are designed to take away your freedom to share and change it. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free software--to make sure the software is free for all its users. This General Public License applies to most of the Free Software Foundation's software and to any other program whose authors commit to using it. You can apply it to your programs, too.</p>
                <p>When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for this service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.</p>
                <p>To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights. These restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.</p>

                <h6 class="font-weight-bold mt-3">NO WARRANTY</h6>
                <p class="small text-uppercase">BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="../include/inc_js/jquery/jquery-3.7.1.min.js"></script>
<script src="../include/inc_js/bootstrap.bundle.min.js"></script>
</body>
</html>
