<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

global $BL;

echo cmsgoversionCheck();

?>
<!--
    it is not allowed to remove or change any part of license information
//-->
<div class="copyrightInfo code">
    <p>
        <strong>cmsGO!</strong> Copyright &copy; 2002&#8212;<?php echo date('Y') ?>
        <a title="send email to info@pixels-points.ch" href="mailto:info@pixels-points.ch">pixels & points GmbH</a>.
    </p>
    <p>
        Extensions are copyright of their respective owners.<br>
        Visit <a href="http://www.pixels-points.ch" target="_blank">http://www.pixels-points.ch</a>
        for details. <br>
        Obstructing the appearance of this notice is prohibited by law.
    </p>
    <hr>
    <p class="text-monospace">
        cmsGO! is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published
        by the Free Software Foundation; either version 2 of the License,
        or (at your option) any later version.
    </p>
    <p class="text-monospace">
        cmsGO! is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
        <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GNU General Public License</a>
        for more details.
    </p>
    <p class="text-monospace">
        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the
    </p>
    <p class="text-monospace ml-4">
        Free Software Foundation, Inc.,<br/>
        59 Temple Place,<br/>
        Suite 330,<br/>
        Boston, MA 02111-1307<br/>
        USA
    </p>
    <hr>
</div>
<div id="licenseExtensions">
  <p><strong>Extensions are copyright of their respective owners:</strong></p>
  <ul>
    <li>
        <a href="http://www.ckeditor.com" target="_blank"><strong>CKEditor</strong></a>,
        Copyright &copy; 2003-2017 Frederico Caldeira Knabben,
        <a href="http://ckeditor.com/terms-of-use#licenses" target="_blank">License</a>
    </li>
    <li>
        <a href="https://https://commonmark.thephpleague.com/2.5/" target="_blank"><strong>CommonMark</strong></a>,
        Copyright &copy; Colin O'Dell,
        <a href="https://github.com/thephpleague/commonmark/blob/2.5/LICENSE" target="_blank">BSD 3-Clause &quot;New&quot; or &quot;Revised&quot; License</a>
    </li>
    <li>
        <a href="http://mikolajj.republika.pl" target="_blank"><strong>ConvertCharset</strong></a>,
        Copyright &copy; 2003-2004 Mikolaj Jedrzejak
    </li>
    <li>
        <a href="https://www.mediawiki.org" target="_blank"><strong>ConvertibleTimestamp &amp; IPTC</strong></a>,
        Copyright &copy; MediaWiki.org
        <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GNU GPL</a>
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
        <a href="https://github.com/mtibben/html2text" target="_blank"><strong>Html2Text</strong></a>,
        Copyright &copy; Mark Tibben,
        <a href="https://github.com/mtibben/html2text/blob/master/LICENSE.md" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://linux.duke.edu/projects/mini/htmlfilter/" target="_blank"><strong>Htmlfilter</strong></a>,
        Copyright &copy; 2002-2005 Duke University/<a href="http://www.mricon.com/">Konstantin Riabitsev</a>,
        <a href="http://www.gnu.org/licenses/lgpl.html" target="_blank"> GNU LGPL</a>
    </li>
    <li>
        <a href="http://idnaconv.phlymail.de" target="_blank"><strong>IDNA Convert</strong></a>
        Copyright &copy; 2004-2011 phlyLabs Berlin,
        <a href="http://www.fsf.org/licensing/licenses/lgpl.html" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <a href="http://www.JavascriptToolbox.com" target="_blank"><strong>JavascriptToolbox.com</strong></a> JavaScripts,
        Matt Kruse &lt;matt@mattkruse.com&gt;
    </li>
    <li>
        <a href="http://jquery.com/" target="_blank"><strong>jQuery</strong></a>,
        Copyright &copy; 2011 John Resig,
        <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>,
        <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GNU GPL</a>
    </li>
    <li>
        <a href="http://mootools.net/" target="_blank"><strong>MooTools</strong></a>,
        Copyright &copy; 2006-2009 Valerio Proietti,
        <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>
    </li>
    <li>
        <a href="https://github.com/thephpleague/oauth2-client" target="_blank"><strong>OAuth2 Client &amp; Providers (Google, Azure)</strong></a>,
        Copyright &copy; The League of Extraordinary Packages / greew,
        <a href="https://github.com/thephpleague/oauth2-client/blob/master/LICENSE" target="_blank">MIT License</a>
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
        <a href="https://developers.google.com/recaptcha/docs/php" target="_blank"><strong>reCAPTCHA</strong></a>
        Copyright &copy; 2007 Mike Crawford, Ben Maurer,
        <a href="http://www.opensource.org/licenses/mit-license.php" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://code.iamcal.com/php/rfc822/" target="_blank"><strong>RFC 822/2822/5322 Email Parser</strong></a>
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
        <a href="http://www.digitalia.be/software/slimbox" target="_blank"><strong>Slimbox</strong></a>,
        Copyright &copy; Christophe Beyls,
        <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>
    </li>
    <li>
        <a href="http://www.solmetra.com" target="_blank"><strong>Solmetra FormValidator</strong></a>,
        Copyright &copy; UAB Solmetra,
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
        <a href="http://textpattern.googlecode.com/svn/development/4.x/textpattern/lib/classTextile.php" target="_blank"><strong>Textile Parser</strong></a>,
        Copyright &copy; 2003-2004, Dean Allen,
        <a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0" target="_blank">GNU GPL</a>
    </li>
    <li>
        <a href="https://www.tiny.cloud/" target="_blank"><strong>TinyMCE</strong></a>,
        Copyright &copy; Tiny Technologies, Inc.,
        <a href="https://github.com/tinymce/tinymce/blob/master/LICENSE.md" target="_blank">MIT License</a>
    </li>
    <li>
        <a href="http://www.walterzorn.com/" target="_blank"><strong>wz_js Scripts</strong></a>,
        Copyright &copy; 2002-2009 Walter Zorn,
        <a href="http://www.fsf.org/licensing/licenses/lgpl.html" target="_blank">GNU LGPL</a>
    </li>
    <li>
        <?php echo $BL['be_about_and_contributors']; ?>
    </li>
  </ul>
</div>
