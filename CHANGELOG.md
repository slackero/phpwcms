# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- **Prism.js Highlighting Support:** Added modern code highlighting templates (`JavaScript-Prism.tmpl` and `PHP-Prism.tmpl`) under `template/inc_cntpart/code/example/` using Prism.js.
- **CodeQL Configuration:** Added `.github/codeql/codeql-config.yml` to exclude third-party vendor directories (e.g. `tinymce`, `mootools`, `jquery`, etc.) from security scans.

### Changed
- **CDN Loading for Highlighters:** Modified SyntaxHighlighter and Prism.js templates to load libraries via CDN by default.
  - Added comments inside templates explaining how to host libraries locally in `template/lib/` if desired.
- **CDN Fallback for IE Polyfills:** Updated legacy IE polyfills (`html5shiv` and `respond`) to load from CDN.
  - The loader now checks for the existence of local files at `template/lib/html5shiv/html5shiv.min.js` and `template/lib/respond/respond.min.js`. If they are missing, it automatically falls back to secure public CDN hosting.
- **CDN Fallback for SWFObject:** Updated `swfobject` loading in `js.inc.php` to fall back to CDN if local files at `template/lib/swfobject/swfobject.js` are missing.

### Removed
- **Unused Local Polyfills and Libraries:** Deleted local library directories to reduce repository bloat:
  - Removed local SyntaxHighlighter files (`template/lib/syntaxhighlighter/*`)
  - Removed local html5shiv files (`template/lib/html5shiv/*`)
  - Removed local respond files (`template/lib/respond/*`)
  - Removed local swfobject files (`template/lib/swfobject/*`)
  - Removed local ie7-js files (`template/lib/ie7-js/*`)
  - Removed local NonverBlaster Flash player (`template/lib/nonverblaster/*`)

### Security Fixes
- **Email Regex ReDoS (High):** Optimized the email validation pattern in `include/inc_js/phpwcms.js` to prevent potential Regular Expression Denial of Service (ReDoS) backtracking attacks. Added support for plus-addressing (e.g., `user+tag@domain.com`).
- **DOM XSS in Ads Module (High):** Cast input dimension fields (`width` and `height`) to integers using `parseInt()` in `include/inc_module/mod_ads/template/ads.js` before writing them to the document context via `document.write()`, preventing potential DOM XSS.

---

## [2.0.0-dev] – 2026/07/20

### Added
- **v2-dev branch**: New development branch `v2-dev` tracking the next major release of phpwcms.
- **Backend dashboard support section**: Added language-specific heading via `$BL['be_dashboard_support']` across all 7 backend language packs (de, en, es, fr, it, nl, pl) replacing a hardcoded PHP ternary.
- **Installer config support settings**: Added `$phpwcms['support']` variable to setup config templates (`setup.conf.inc.php`, `setup.func.inc.php`).

### Changed
- **Branding – login page**: Removed company name suffix from the copyright footer in `login.php`; now reads `Oliver Georgi.`.
- **Branding – HTML header comment**: Restored the standard open-source attribution comment block (`PHPWCMS_HEADER_COMMENT`) from `v1.10-dev` in `include/inc_lib/default.inc.php`.
- **Branding – README maintainer badge**: Updated badge from "pixels & points" to "Oliver Georgi".
- **Backend logo**: Replaced logo asset (`img/phpwcms-logo.svg`) with the official phpwcms SVG brand mark.
- **Backend header**: Restored backend header bar to white background (`#FFFFFF`) with standard gray link styling.
- **Backend sidebar**: Applied deep slate-blue (`#2b3e51`) background to the sidebar/left column with matching hover and border tones.
- **FontAwesome stylesheets**: Merged `fontawesome.brands.min.css`, `fontawesome.regular.min.css`, `fontawesome.solid.min.css`, and `fontawesome.min.css` directly into `phpwcms-fontawesome.css` (and its `.min.css` counterpart) to eliminate nested `@import` rules causing out-of-order glyph loading.

### Fixed
- **CSS icon glyphs**: Replaced raw UTF-8 FontAwesome private-use characters in `phpwcmsspecial.min.css` with safe backslash-escaped hex CSS values (e.g. `\f073`) to prevent corruption under non-UTF-8 served stylesheets.
- **MIME types and charset**: Enabled font/media MIME type definitions and `AddCharset UTF-8` directives for `.css`/`.js` in both `.htaccess` and `_.htaccess`.
- **German umlaut corruption**: Replaced literal `ü` in `include/inc_tmpl/admin.groups.tmpl.php` with the HTML entity `&uuml;` to prevent UTF-8 → ISO-8859-1 garbling in success messages.
---

## CDN-Replaced Libraries (How to host locally)

The following libraries have been migrated to CDN loading by default to reduce repository bloat. To run them locally, download the assets and place them in the following paths:

### 1. Prism.js
* **Download:** [PrismJS Download](https://prismjs.com/download.html)
* **Local Paths:**
  * `template/lib/prism/prism.css`
  * `template/lib/prism/prism.js`
  * `template/lib/prism/prism-autoloader.min.js`
* **Configuration:** Update Prism templates in `template/inc_cntpart/code/example/` to replace CDN links with local files.

### 2. SyntaxHighlighter
* **Local Paths:**
  * `template/lib/syntaxhighlighter/styles/shCoreDefault.css`
  * `template/lib/syntaxhighlighter/shCore.js`
  * `template/lib/syntaxhighlighter/shBrushJScript.js` (and other language brushes)
* **Configuration:** Update SyntaxHighlighter templates in `template/inc_cntpart/code/example/` to replace CDN links with local files.

### 3. SWFObject
* **Local Path:** `template/lib/swfobject/swfobject.js`
* **Configuration:** The loader in `include/inc_front/js.inc.php` automatically detects if the file exists at this path. If not found, it automatically falls back to CDN.

### 4. HTML5shiv & Respond.js
* **Local Paths:**
  * `template/lib/html5shiv/html5shiv.min.js`
  * `template/lib/respond/respond.min.js`
* **Configuration:** The loader in `include/inc_front/content.func.inc.php` automatically detects if the files exist at these paths. If not found, it automatically falls back to CDN.
