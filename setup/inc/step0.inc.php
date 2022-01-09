<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

if (!defined('PHP8')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

$_SESSION['admin_set'] = false;
$setup_recommend = true;

?>
    <h1><span class="number">1.</span> Thanks! You have agreed to the GPL.</h1>
    <p>Now that you know the <a href="http://www.gnu.org/licenses/licenses.html#GPL"
                                target="_blank"><strong>licence</strong></a> under
        which <strong>phpwcms</strong> is released you can continue to install or upgrade <strong>phpwcms</strong>.</p>

    <h1><span class="number">2.</span> Now lets check your server settings</h1>
    <p>Please proof all information about your system (recommend PHP 7.1+, MySQL 5.5+).</p>
    <ol>
        <li>WWW server:
            <strong><?php echo empty($_SERVER['SERVER_SOFTWARE']) ? 'unavailable' : html_specialchars($_SERVER['SERVER_SOFTWARE']) ?></strong>
        </li>
        <li>PHP version: <?php

            echo '<strong>' . html_specialchars(phpversion()) . '</strong>';

            switch (version_compare('5.6', phpversion())) {

                case -1:    // current used PHP is > OK
                    echo '<img src="../img/famfamfam/icon_accept.gif" alt="OK" class="icon1" />';
                    break;

                case  0:    // the same version - HM not recommend
                    echo '<img src="../img/famfamfam/icon_alert.gif" alt="OK" class="icon1" />';
                    echo ' (your version of PHP is OK but update to 7.x is recommend)';
                    $setup_recommend = false;
                    break;

                case  1:    // false it's older
                    echo '<img src="../img/famfamfam/action_stop.gif" alt="Stop" class="icon1" />';
                    echo ' (your version of PHP is too old - it is not recommend to continue)';
                    $setup_recommend = false;
                    break;
            }

            ?></li>
        <li>MySQLi extension: <?php
            if (function_exists('mysqli_connect')) {
                echo '<strong>installed</strong> <img src="../img/famfamfam/icon_accept.gif" alt="OK" class="icon1" />';
            } else {
                echo '<strong class="error">not installed</strong> <img src="../img/famfamfam/action_stop.gif" alt="Stop" class="icon1" />';
                $setup_recommend = false;
            }
            ?></li>
        <li>MySQLi client information: <?php

            $mysqlnd = false;
            $mysql_version = mysqli_get_client_info();

            if (strpos($mysql_version, 'mysqlnd') !== false) {
                $mysql_version = preg_replace('/^.*?\s(\d.+?)\s.*?$/', '$1', $mysql_version) . ' (client lib)';
                $mysqlnd = true;
            }

            echo '<strong>' . $mysql_version . '</strong>';

            $mysql_version = explode('.', preg_replace('/[^0-9.]/', '', str_replace(array('mysqlnd ', ' (client lib)'), '', strtolower($mysql_version))));
            $mysql_version[0] = (int)$mysql_version[0];
            $mysql_version[1] = empty($mysql_version[1]) ? 0 : (int)$mysql_version[1];

            if ($mysql_version[0] >= 5 && ($mysql_version[1] >= 1 || $mysqlnd)) {

                // current MySQL isOK
                echo '<img src="../img/famfamfam/icon_accept.gif" alt="OK" class="icon1" />';
            } else {

                // the same version or older
                echo '<img src="../img/famfamfam/action_stop.gif" alt="Stop" class="icon1" />';
                echo ' (not supported any longer)';

                $setup_recommend = false;
            }

            ?></li>
        <li>PHP settings<a href="http://www.php.net/manual/en/security.php" target="_blank" title="PHP Security"><img
                    src="../img/famfamfam/icon_info.gif" alt="Security risks" class="icon1" border="0"/></a>
            <ul>
                <li><strong>register_globals </strong><?php

                    if (ini_get('register_globals')) {
                        echo '<strong>On</strong>';
                        echo '<img src="../img/famfamfam/icon_alert.gif" alt="OK" class="icon1" />';
                        echo ' (should always be set Off';
                        echo '<a href="http://phpsec.org/projects/guide/1.html#1.3" target="_blank">';
                        echo '<img src="../img/famfamfam/icon_info.gif" alt="Security risks" class="icon1" border="0" />';
                        echo '</a>)';
                    } else {
                        echo '<strong>Off</strong>';
                        echo '<img src="../img/famfamfam/icon_accept.gif" alt="OK" class="icon1" />';
                    }

                    ?>
                </li>
                <?php if (version_compare(phpversion(), '5.6.0', '<')): ?>
                    <li><strong>safe_mode </strong><?php

                        if (ini_get('safe_mode')) {
                            echo '<strong>On</strong>';
                            echo '<img src="../img/famfamfam/icon_accept.gif" alt="OK" class="icon1" />';
                            echo ' (limited permissions, but recommend)';
                        } else {
                            echo '<strong>Off</strong>';
                            echo '<img src="../img/famfamfam/icon_alert.gif" alt="Warning" class="icon1" />';
                            echo ' (check information about security risks';
                            echo '<a href="http://www.php.net/features.safe-mode" target="_blank">';
                            echo '<img src="../img/famfamfam/icon_info.gif" alt="Security risks" class="icon1" border="0" />';
                            echo '</a>)';
                        }

                        ?></li>
                <?php endif; ?>

                <li><?php

                    $_phpinfo = parsePHPModules();
                    if (isset($_phpinfo['gd']['GD Support']) && $_phpinfo['gd']['GD Support'] == 'enabled' && isset($_phpinfo['gd']['GD Version'])) {
                        $_phpinfo['gd_version'] = html_specialchars($_phpinfo['gd']['GD Version']);
                    } else {
                        $_phpinfo['gd_version'] = 'n.a.';
                    }

                    echo '<strong>GD';

                    if (function_exists('imagegd2')) {
                        echo '2</strong> ' . $_phpinfo['gd_version'];
                        echo '<img src="../img/famfamfam/icon_accept.gif" alt="GD2" class="icon1" />';
                        $is_gd = true;
                    } elseif (function_exists('imagegd')) {
                        echo '1</strong> ' . $_phpinfo['gd_version'];
                        echo '<img src="../img/famfamfam/icon_alert.gif" alt="GD1" class="icon1" />';
                        echo ' (GD2 is recommend)';
                        $is_gd = true;
                    } else {
                        echo ' not available</strong>';
                        echo '<img src="../img/famfamfam/action_stop.gif" alt="GD not present" class="icon1" />';
                        $is_gd = false;
                    }

                    if ($is_gd) {

                        $is_gd = array();

                        if (imagetypes() & IMG_GIF) {
                            $is_gd[] = 'GIF<img src="../img/famfamfam/icon_accept.gif" alt="GIF supported" class="icon1" />';
                        } else {
                            $is_gd[] = 'GIF<img src="../img/famfamfam/action_stop.gif" alt="GIF not supported" class="icon1" />';
                        }
                        if (imagetypes() & IMG_PNG) {
                            $is_gd[] = 'PNG<img src="../img/famfamfam/icon_accept.gif" alt="PNG supported" class="icon1" />';
                        } else {
                            $is_gd[] = 'PNG<img src="../img/famfamfam/action_stop.gif" alt="PNG not supported" class="icon1" />';
                        }
                        if (imagetypes() & IMG_JPG) {
                            $is_gd[] = 'JPG<img src="../img/famfamfam/icon_accept.gif" alt="JPG supported" class="icon1" />';
                        } else {
                            $is_gd[] = 'JPG<img src="../img/famfamfam/action_stop.gif" alt="JPG not supported" class="icon1" />';
                        }

                        echo '<br />Image types supported: ' . implode('/ ', $is_gd);
                    }

                    ?></li>
            </ul>
        </li>
    </ol>
    <p><strong>phpwcms</strong> has
        automatic image resizing capabilities. This works very well for standard graphics
        file formats like JPEG, GIF and PNG as long as your PHP installation has built-in
        support for <a href="http://en.wikipedia.org/wiki/GD_Graphics_Library" target="_blank">GD</a> &#8212; <strong>GD2</strong>
        with freetype support is always recommend. <strong>Note:</strong> It
        is not neccessary but recommend that you have installed <a href="http://www.imagemagick.org" target="_blank">ImageMagick</a>
        and <a href="http://www.ghostscript.com/" target="_blank">GhostScript</a> on
        your server system which enables image resizing for nearly every
        graphics file format.</p>
<?php

// is the setup config file writable
if (!is_writable($DOCROOT . '/setup/setup.conf.inc.php')) {

    if (!@chmod($DOCROOT . '/setup/setup.conf.inc.php', 0666)) {

        echo errorWarning('The file <i>setup.conf.inc.php</i> in which all values are stored is NOT writable.');

        ?><p>Please correct this problem before you can continue (connect to your account by FTP and permissions to
            chmod 777).</p>
        <?php

    }
} else {

    if (!$setup_recommend) {

        echo '<p class="error">
		    <img src="../img/famfamfam/icon_alert.gif" alt="" class="icon1" />
		    <strong>It is not recommend to continue with setup. See the warnings!</strong>
		</p>';
    }

    ?>
    <form action="setup.php?step=1" method="post">
        <input name="Submit" type="submit" value="Start setup of phpwcms"/>
        &nbsp;&nbsp;
        <input name="Submit" type="submit" value="Upgrade existing installation"
               onclick="window.location.href='upgrade.php';return false;"/>
    </form>
    <?php
}
