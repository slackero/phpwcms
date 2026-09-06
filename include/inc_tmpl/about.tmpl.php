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
<?php if (is_whitelabel()): ?>
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
<?php if (is_whitelabel()): ?>
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
<div class="copyrightInfo font-monospace mb-3 p-4">
    <p class="mt-0">
        <strong><?php echo html(get_brand_name()); ?></strong> is free software;
        you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published
        by the Free Software Foundation; either version 2 of the License,
        or (at your option) any later version.
    </p>
    <p>
        <strong><?php echo html(get_brand_name()); ?></strong> is distributed
        in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
        <a href="https://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GNU General Public License</a>
        for more details.
    </p>
    <p>
        You should have received a copy of the GNU General Public License
        along with this program;
        <span class="text-nowrap">if not,</span>
        <span class="text-nowrap">write to the:</span>
    </p>
    <p class="ms-4 fst-italic mb-1">
        Free Software Foundation, Inc.<br/>
        59 Temple Place, Suite 330<br/>
        Boston, MA 02111-1307, USA
    </p>
</div>
<div id="licenseExtensions">
  <p><strong><?php echo $BL['be_extensions_copyright']; ?></strong></p>
  <ul>
    <li>
        <a href="https://ace.c9.io/" target="_blank" rel="noopener noreferrer"><strong>Ace Editor</strong></a>,
        Copyright &copy; Ajax.org B.V.,
        <a href="https://github.com/ajaxorg/ace/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">BSD 3-Clause License</a>
    </li>
    <li>
        <a href="https://github.com/Bacon/BaconQrCode" target="_blank" rel="noopener noreferrer"><strong>BaconQrCode</strong></a>,
        Copyright &copy; Ben Scholzen,
        <a href="https://github.com/Bacon/BaconQrCode/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">BSD 2-Clause License</a>
    </li>
    <li>
        <a href="https://getbootstrap.com/" target="_blank" rel="noopener noreferrer"><strong>Bootstrap</strong></a>,
        Copyright &copy; Twitter / The Bootstrap Authors,
        <a href="https://github.com/twbs/bootstrap/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://ckeditor.com" target="_blank" rel="noopener noreferrer"><strong>CKEditor</strong></a>,
        Copyright &copy; CKSource Holding sp. z o.o.,
        <a href="https://ckeditor.com/legal/ckeditor-oss-license/" target="_blank" rel="noopener noreferrer">GPL / LGPL / MPL</a>
    </li>
    <li>
        <a href="https://commonmark.thephpleague.com/" target="_blank" rel="noopener noreferrer"><strong>CommonMark</strong></a>,
        Copyright &copy; Colin O'Dell / The PHP League,
        <a href="https://github.com/thephpleague/commonmark/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">BSD 3-Clause License</a>
    </li>
    <li>
        <strong>ConvertCharset</strong>,
        Copyright &copy; 2003&ndash;2004 Mikolaj Jedrzejak
    </li>
    <li>
        <a href="https://cookieconsent.orestbida.com/" target="_blank" rel="noopener noreferrer"><strong>CookieConsent</strong></a>,
        Copyright &copy; Orest Bida / Insites,
        <a href="https://github.com/orestbida/cookieconsent/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://day.js.org/" target="_blank" rel="noopener noreferrer"><strong>Day.js</strong></a>,
        Copyright &copy; iamkun,
        <a href="https://github.com/iamkun/dayjs/blob/dev/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://www.dropzone.dev/" target="_blank" rel="noopener noreferrer"><strong>Dropzone</strong></a>,
        Copyright &copy; Matias Meno,
        <a href="https://github.com/dropzone/dropzone/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/exif-js/exif-js" target="_blank" rel="noopener noreferrer"><strong>exif-js</strong></a>,
        Copyright &copy; Jacob Seidelin,
        <a href="https://github.com/exif-js/exif-js/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://flagicons.lipis.dev/" target="_blank" rel="noopener noreferrer"><strong>Flag Icons</strong></a>,
        Copyright &copy; Panayiotis Lipiridis,
        <a href="https://github.com/lipis/flag-icons/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://flatpickr.js.org/" target="_blank" rel="noopener noreferrer"><strong>Flatpickr</strong></a>,
        Copyright &copy; Gregory Pfister,
        <a href="https://github.com/flatpickr/flatpickr/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://fontawesome.com" target="_blank" rel="noopener noreferrer"><strong>Font Awesome Free</strong></a>,
        Copyright &copy; Fonticons, Inc.,
        <a href="https://fontawesome.com/license/free" target="_blank" rel="noopener noreferrer">CC BY 4.0 / SIL OFL 1.1 / MIT License</a>
    </li>
    <li>
        <a href="https://biati-digital.github.io/glightbox/" target="_blank" rel="noopener noreferrer"><strong>GLightbox</strong></a>,
        Copyright &copy; Biar Demirovic,
        <a href="https://github.com/biati-digital/glightbox/blob/master/LICENSE.md" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/antonioribeiro/google2fa" target="_blank" rel="noopener noreferrer"><strong>Google2FA</strong></a>,
        Copyright &copy; Antonio Carlos Ribeiro,
        <a href="https://github.com/antonioribeiro/google2fa/blob/master/LICENSE.md" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/guzzle/guzzle" target="_blank" rel="noopener noreferrer"><strong>Guzzle HTTP</strong></a>,
        Copyright &copy; Michael Dowling, Graham Campbell, Guzzle contributors,
        <a href="https://github.com/guzzle/guzzle/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://htmlpurifier.org/" target="_blank" rel="noopener noreferrer"><strong>HTML Purifier</strong></a>,
        Copyright &copy; Edward Z. Yang,
        <a href="https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html" target="_blank" rel="noopener noreferrer">GNU LGPL</a>
    </li>
    <li>
        <a href="https://github.com/mtibben/html2text" target="_blank" rel="noopener noreferrer"><strong>Html2Text</strong></a>,
        Copyright &copy; Mark Tibben,
        <a href="https://github.com/mtibben/html2text/blob/master/LICENSE.md" target="_blank" rel="noopener noreferrer">GPL-2.0 License</a>
    </li>
    <li>
        <strong>Htmlfilter</strong>,
        Copyright &copy; 2002&ndash;2005 Duke University / <a href="https://github.com/mricon" target="_blank" rel="noopener noreferrer">Konstantin Riabitsev</a>,
        <a href="https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html" target="_blank" rel="noopener noreferrer">GNU LGPL</a>
    </li>
    <li>
        <a href="https://github.com/algo26-matthias/idna-convert" target="_blank" rel="noopener noreferrer"><strong>IDNA Convert</strong></a>,
        Copyright &copy; Matthias Sommerfeld / phlyLabs,
        <a href="https://github.com/algo26-matthias/idna-convert/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">GNU LGPL</a>
    </li>
    <li>
        <a href="https://jquery.com/" target="_blank" rel="noopener noreferrer"><strong>jQuery</strong></a>,
        Copyright &copy; OpenJS Foundation and jQuery contributors,
        <a href="https://jquery.org/license/" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://www.mediawiki.org" target="_blank" rel="noopener noreferrer"><strong>MediaWiki Components</strong></a> (ConvertibleTimestamp, IPTC, SVG Reader),
        Copyright &copy; MediaWiki.org contributors,
        <a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank" rel="noopener noreferrer">GNU GPL</a>
    </li>
    <li>
        <a href="https://github.com/thephpleague/oauth2-client" target="_blank" rel="noopener noreferrer"><strong>OAuth2 Client &amp; Providers (Google, Azure)</strong></a>,
        Copyright &copy; The League of Extraordinary Packages / greew,
        <a href="https://github.com/thephpleague/oauth2-client/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT / GPL-3.0 License</a>
    </li>
    <li>
        <strong>PHP Calendar</strong>,
        Copyright &copy; Keith Devens
    </li>
    <li>
        <a href="https://github.com/PHPMailer/PHPMailer" target="_blank" rel="noopener noreferrer"><strong>PHPMailer</strong></a>,
        Copyright &copy; Brent R. Matzelle, Marcus Bointon, Andy Prevost,
        <a href="https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">GNU LGPL</a>
    </li>
    <li>
        <a href="https://github.com/PHPOffice/PhpSpreadsheet" target="_blank" rel="noopener noreferrer"><strong>PhpSpreadsheet</strong></a>,
        Copyright &copy; PHPOffice,
        <a href="https://github.com/PHPOffice/PhpSpreadsheet/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/SubZane/simplegmaps" target="_blank" rel="noopener noreferrer"><strong>SimpleGMaps</strong></a>,
        Copyright &copy; Patrik B&aring;nkestad,
        <a href="https://github.com/SubZane/simplegmaps/blob/master/LICENSE-MIT" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="http://simplepie.org/" target="_blank" rel="noopener noreferrer"><strong>SimplePie</strong></a>,
        Copyright &copy; Ryan Parman, Geoffrey Sneddon, Ryan McCue, Malcolm Blaney,
        <a href="http://www.opensource.org/licenses/bsd-license.php" target="_blank" rel="noopener noreferrer">BSD License</a>
    </li>
    <li>
        <strong>Solmetra FormValidator (SPAF)</strong>,
        Copyright &copy; Martynas / UAB Solmetra,
        <a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank" rel="noopener noreferrer">GNU GPL</a>
    </li>
    <li>
        <a href="https://sortablejs.github.io/Sortable/" target="_blank" rel="noopener noreferrer"><strong>SortableJS</strong></a>,
        Copyright &copy; Lebedev Konstantin,
        <a href="https://github.com/SortableJS/Sortable/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://splidejs.com/" target="_blank" rel="noopener noreferrer"><strong>Splide</strong></a>,
        Copyright &copy; Naotoshi Fujita,
        <a href="https://github.com/Splidejs/splide/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/php81-bc/strftime" target="_blank" rel="noopener noreferrer"><strong>strftime Polyfill</strong></a>,
        Copyright &copy; PHP81_BC,
        <a href="https://github.com/php81-bc/strftime/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/darylldoyle/svg-sanitizer" target="_blank" rel="noopener noreferrer"><strong>SVG Sanitize</strong></a>,
        Copyright &copy; Daryll Doyle,
        <a href="https://github.com/darylldoyle/svg-sanitizer/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">GPL-2.0-or-later</a>
    </li>
    <li>
        <a href="https://github.com/symfony/polyfill" target="_blank" rel="noopener noreferrer"><strong>Symfony Polyfills</strong></a>,
        Copyright &copy; Fabien Potencier / Symfony,
        <a href="https://github.com/symfony/polyfill/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/textile/php-textile" target="_blank" rel="noopener noreferrer"><strong>Textile Parser</strong></a>,
        Copyright &copy; Netcarver, Dean Allen,
        <a href="https://github.com/textile/php-textile/blob/master/LICENSE.txt" target="_blank" rel="noopener noreferrer">BSD 3-Clause License</a>
    </li>
    <li>
        <a href="https://www.tiny.cloud/" target="_blank" rel="noopener noreferrer"><strong>TinyMCE</strong></a>,
        Copyright &copy; Tiny Technologies, Inc.,
        <a href="https://github.com/tinymce/tinymce/blob/master/LICENSE.md" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <a href="https://tom-select.js.org/" target="_blank" rel="noopener noreferrer"><strong>Tom Select</strong></a>,
        Copyright &copy; Brian Reavis / Orchid Software,
        <a href="https://github.com/tom-select/tom-select/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">Apache-2.0 License</a>
    </li>
    <li>
        <a href="https://github.com/flack/UniversalFeedCreator" target="_blank" rel="noopener noreferrer"><strong>UniversalFeedCreator</strong></a>,
        originally &copy; Kai Blankenhorn, maintained by OpenPSA,
        <a href="https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html" target="_blank" rel="noopener noreferrer">GNU LGPL</a>
    </li>
    <li>
        <a href="https://videojs.com/" target="_blank" rel="noopener noreferrer"><strong>Video.js</strong></a>,
        Copyright &copy; Brightcove, Inc.,
        <a href="https://github.com/videojs/video.js/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">Apache-2.0 License</a>
    </li>
    <li>
        <a href="https://github.com/maennchen/ZipStream-PHP" target="_blank" rel="noopener noreferrer"><strong>ZipStream-PHP</strong></a>,
        Copyright &copy; Jonatan M&auml;nnchen,
        <a href="https://github.com/maennchen/ZipStream-PHP/blob/master/LICENSE" target="_blank" rel="noopener noreferrer">MIT License</a>
    </li>
    <li>
        <?php echo $BL['be_about_and_contributors']; ?>
    </li>
  </ul>
</div>

<hr class="mb-0">
