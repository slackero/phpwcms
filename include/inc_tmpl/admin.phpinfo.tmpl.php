<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

// Capture phpinfo output
ob_start();
phpinfo();
$phpinfo_raw = ob_get_clean();

$style = '';
if (preg_match('/<style type="text\/css">(.*?)<\/style>/s', $phpinfo_raw, $matches)) {
    $style = $matches[1];
}

$body = '';
if (preg_match('/<body>(.*?)<\/body>/s', $phpinfo_raw, $matches)) {
    $body = $matches[1];
} else {
    $body = '<div class="alert alert-warning">Could not parse phpinfo() output body.</div>';
}
?>

<div class="row align-items-center">
  <div class="col col-sm-auto text-center text-sm-start">
    <h1>phpinfo()</h1>
  </div>
</div>

<style>
.phpinfo-wrapper {
    font-family: sans-serif;
    color: #333;
    overflow-x: auto;
    font-size: 1.1rem;
}
.phpinfo-wrapper td, .phpinfo-wrapper th {
    font-size: 0.95rem !important;
}
.phpinfo-wrapper h1 {
    font-size: 2.2rem !important;
}
.phpinfo-wrapper h2 {
    font-size: 1.6rem !important;
}
.phpinfo-wrapper {
    <?php echo $style; ?>
}
/* Override potential global leaks */
.phpinfo-wrapper body {
    background-color: transparent;
}
.phpinfo-wrapper a:link {
    color: #000099;
    text-decoration: none;
    background-color: transparent;
}
.phpinfo-wrapper hr {
    width: 100%;
    background-color: #cccccc;
    border: 0px;
    height: 1px;
    color: #333333;
}
.phpinfo-wrapper table {
    width: 100%;
    table-layout: fixed;
    word-wrap: break-word;
}
</style>

<div class="phpinfo-wrapper">
    <?php echo $body; ?>
</div>
