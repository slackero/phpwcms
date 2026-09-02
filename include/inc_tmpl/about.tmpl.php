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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>
<div class="about-header mb-3">
    <h1 class="title"><?php echo html($BL['be_about_headline']); ?></h1>
<?php if (defined('PHPWCMS_WHITELABEL') && PHPWCMS_WHITELABEL): ?>
    <div class="alert alert-info py-2 px-3 mb-3">
        <i class="fa-solid fa-certificate me-1"></i>
        <strong>White Label Licensed:</strong> <?php echo html(get_brand_name()); ?>
        <?php if (!empty($phpwcms['whitelabel']['licensee'])): ?>
            &bull; Licensed to <strong><?php echo html($phpwcms['whitelabel']['licensee']); ?></strong>
        <?php endif; ?>
    </div>
<?php endif; ?>
    <p>
        <strong><?php echo html($BL['be_about_version']); ?>:</strong> <?php echo html(PHPWCMS_VERSION); ?> (<?php echo html(PHPWCMS_RELEASE_DATE); ?>, r<?php echo html(PHPWCMS_REVISION); ?>)<br>
<?php if (defined('PHPWCMS_WHITELABEL') && PHPWCMS_WHITELABEL): ?>
        <?php if ($brand_url = get_brand_url()): ?>
        <strong><?php echo html($BL['be_about_website']); ?>:</strong> <a href="<?php echo htmlspecialchars($brand_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo htmlspecialchars($brand_url, ENT_QUOTES, 'UTF-8'); ?></a><br>
        <?php endif; ?>
        <strong><?php echo html($BL['be_about_copyright']); ?>:</strong> <?php echo get_brand_copyright(); ?>
<?php else: ?>
        <strong><?php echo html($BL['be_about_maintainer']); ?>:</strong> <a href="mailto:og@phpwcms.org">Oliver Georgi</a><br>
        <strong><?php echo html($BL['be_about_website']); ?>:</strong> <a href="https://www.phpwcms.org" target="_blank">https://www.phpwcms.org</a><br>
        <strong><?php echo html($BL['be_about_copyright']); ?>:</strong> &copy; 2002&ndash;<?php echo date('Y'); ?> Oliver Georgi <?php echo $BL['be_about_contributors']; ?>
<?php endif; ?>
    </p>
</div>

<!--
    it is not allowed to remove or change any part of license information
//-->
<div class="copyrightInfo code mb-3 p-3">
    <p class="mt-0">
        <strong><?php echo html(get_brand_name()); ?></strong> is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published
        by the Free Software Foundation; either version 2 of the License,
        or (at your option) any later version.
    </p>
    <p>
        <strong><?php echo html(get_brand_name()); ?></strong> is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
        <a href="https://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GNU General Public License</a>
        for more details.
    </p>
    <p>
        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the:
    </p>
    <p class="ms-4 fst-italic">
        Free Software Foundation, Inc.<br/>
        59 Temple Place, Suite 330<br/>
        Boston, MA 02111-1307, USA
    </p>
</div>
<div id="licenseExtensions">
  <p><strong><?php echo $BL['be_extensions_copyright']; ?></strong></p>
  <ul>
    <li>
        <a href="https://getbootstrap.com/" target="_blank"><strong>Bootstrap</strong></a>,
        Copyright &copy; Twitter / The Bootstrap Authors,
        <a href="https://github.com/twbs/bootstrap/blob/main/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://www.ckeditor.com" target="_blank"><strong>CKEditor</strong></a>,
        Copyright &copy; 2003-2017 Frederico Caldeira Knabben,
        <a href="http://ckeditor.com/terms-of-use#licenses" target="_blank">License</a>
    </li>
    <li>
        <a href="https://commonmark.thephpleague.com/" target="_blank"><strong>CommonMark</strong></a>,
        Copyright &copy; Colin O'Dell,
        <a href="https://github.com/thephpleague/commonmark/blob/main/LICENSE" target="_blank">BSD 3-Clause License</a>
    </li>
    <li>
        <a href="http://mikolajj.republika.pl" target="_blank"><strong>ConvertCharset</strong></a>,
        Copyright &copy; 2003-2004 Mikolaj Jedrzejak
    </li>
    <li>
        <a href="https://www.mediawiki.org" target="_blank"><strong>ConvertibleTimestamp &amp; IPTC</strong></a>,
        Copyright &copy; MediaWiki.org,
        <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GNU GPL</a>
    </li>
    <li>
        <a href="https://day.js.org/" target="_blank"><strong>Day.js</strong></a>,
        Copyright &copy; IAMMULI,
        <a href="https://github.com/iamkun/dayjs/blob/dev/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://www.dropzone.dev/" target="_blank"><strong>Dropzone</strong></a>,
        Copyright &copy; Matias Meno,
        <a href="https://github.com/dropzone/dropzone/blob/main/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/exif-js/exif-js" target="_blank"><strong>exif-js</strong></a>,
        Copyright &copy; Jacob Seidelin,
        <a href="https://github.com/exif-js/exif-js/blob/master/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://www.famfamfam.com/" target="_blank"><strong>FamFamFam</strong></a>,
        Copyright &copy; Mark James,
        <a href="http://creativecommons.org/licenses/by/2.5/" target="_blank">CC-Attribution</a>
    </li>
    <li>
        <a href="http://www.bitfolge.de" target="_blank"><strong>FeedCreator</strong></a>,
        originally &copy; Kai Blankenhorn,
        <a href="http://www.fsf.org/licensing/licenses/lgpl.html" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <a href="https://flagicons.lipis.dev/" target="_blank"><strong>Flag Icons</strong></a>,
        Copyright &copy; Panayiotis Lipiridis,
        <a href="https://github.com/lipis/flag-icons/blob/main/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://flatpickr.js.org/" target="_blank"><strong>Flatpickr</strong></a>,
        Copyright &copy; Gregory Pfister,
        <a href="https://github.com/flatpickr/flatpickr/blob/master/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://fontawesome.com" target="_blank"><strong>Font Awesome Free</strong></a>,
        Copyright &copy; Fonticons, Inc.,
        <a href="https://fontawesome.com/license/free" target="_blank">CC BY 4.0 / SIL OFL 1.1 / MIT License</a>
    </li>
    <li>
        <a href="https://htmlpurifier.org/" target="_blank"><strong>HTML Purifier</strong></a>,
        Copyright &copy; Edward Z. Yang,
        <a href="https://www.gnu.org/licenses/lgpl-3.0" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <a href="https://github.com/mtibben/html2text" target="_blank"><strong>Html2Text</strong></a>,
        Copyright &copy; Mark Tibben,
        <a href="https://github.com/mtibben/html2text/blob/master/LICENSE.md" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://linux.duke.edu/projects/mini/htmlfilter/" target="_blank"><strong>Htmlfilter</strong></a>,
        Copyright &copy; 2002-2005 Duke University/<a href="http://www.mricon.com/">Konstantin Riabitsev</a>,
        <a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <a href="https://github.com/algo26-matthias/idna-convert" target="_blank"><strong>IDNA Convert</strong></a>,
        Copyright &copy; Matthias Fischer / phlyLabs,
        <a href="http://www.fsf.org/licensing/licenses/lgpl.html" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <a href="https://jquery.com/" target="_blank"><strong>jQuery</strong></a>,
        Copyright &copy; OpenJS Foundation and jQuery contributors,
        <a href="https://jquery.org/license/" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/thephpleague/oauth2-client" target="_blank"><strong>OAuth2 Client &amp; Providers (Google, Azure)</strong></a>,
        Copyright &copy; The League of Extraordinary Packages / greew,
        <a href="https://github.com/thephpleague/oauth2-client/blob/master/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://keithdevens.com/software/php_calendar" target="_blank"><strong>PHP Calendar</strong></a>,
        Copyright &copy; Keith Devens
    </li>
    <li>
        <a href="https://github.com/PHPMailer/PHPMailer" target="_blank"><strong>PHPMailer</strong></a>,
        <a href="http://www.fsf.org/licensing/licenses/lgpl.html" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <a href="https://github.com/PHPOffice/PhpSpreadsheet" target="_blank"><strong>PhpSpreadsheet</strong></a>,
        Copyright &copy; PHPOffice,
        <a href="https://github.com/PHPOffice/PhpSpreadsheet/blob/master/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://code.iamcal.com/php/rfc822/" target="_blank"><strong>RFC 822/2822/5322 Email Parser</strong></a>,
        Copyright &copy; Cal Henderson,
        <a href="http://creativecommons.org/licenses/by/2.5/" target="_blank">CC-Attribution</a>,
        <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL3</a>
    </li>
    <li>
        <a href="http://simplepie.org/" target="_blank"><strong>SimplePie</strong></a>,
        Copyright &copy; 2004-2016 Ryan Parman, Geoffrey Sneddon, Ryan McCue,
        <a href="http://www.opensource.org/licenses/bsd-license.php" target="_blank">BSD License</a>
    </li>
    <li>
        <a href="http://www.solmetra.com" target="_blank"><strong>Solmetra FormValidator (SPAF)</strong></a>,
        Copyright &copy; Martynas / UAB Solmetra,
        <a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0" target="_blank">GNU GPL</a>
    </li>
    <li>
        <a href="https://github.com/php81-bc/strftime" target="_blank"><strong>strftime Polyfill</strong></a>,
        Copyright &copy; PHP81_BC,
        <a href="https://github.com/php81-bc/strftime/blob/main/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/darylldoyle/svg-sanitizer" target="_blank"><strong>SVG Sanitize</strong></a>,
        Copyright &copy; Daryll Doyle,
        <a href="https://github.com/darylldoyle/svg-sanitizer/blob/master/LICENSE" target="_blank">GPL-2.0-or-later / LGPL-3.0-or-later</a>
    </li>
    <li>
        <a href="https://github.com/symfony/polyfill" target="_blank"><strong>Symfony Polyfills</strong></a>,
        Copyright &copy; Fabien Potencier,
        <a href="https://github.com/symfony/polyfill/blob/main/LICENSE" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/textile/php-textile" target="_blank"><strong>Textile Parser</strong></a>,
        Copyright &copy; Netcarver, Dean Allen,
        <a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0" target="_blank">GNU GPL</a>
    </li>
    <li>
        <a href="https://www.tiny.cloud/" target="_blank"><strong>TinyMCE</strong></a>,
        Copyright &copy; Tiny Technologies, Inc.,
        <a href="https://github.com/tinymce/tinymce/blob/master/LICENSE.md" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="https://tom-select.js.org/" target="_blank"><strong>Tom Select</strong></a>,
        Copyright &copy; Brian Reavis / Orchid Software,
        <a href="https://github.com/tom-select/tom-select/blob/master/LICENSE" target="_blank">Apache-2.0 License</a>
    </li>
    <li>
        <a href="https://videojs.com/" target="_blank"><strong>Video.js</strong></a>,
        Copyright &copy; Brightcove, Inc.,
        <a href="https://github.com/videojs/video.js/blob/main/LICENSE" target="_blank">Apache-2.0 License</a>
    </li>
    <li>
        <?php echo $BL['be_about_and_contributors']; ?>
    </li>
  </ul>
</div>
