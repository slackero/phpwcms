# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added
- **In-App Self-Update Engine:** Added a comprehensive automated update management engine in the administration backend (**Admin -> System Update** / `phpwcms.php?do=admin&p=update`).
  - Queries latest release packages directly from GitHub releases via the GitHub API.
  - Performs automated pre-update database dumps and file backups into `content/backup/`.
  - Supports staged, atomic file replacement with automated maintenance mode during the update process and automatic rollback on failure.
  - Verifies package integrity using `.update-manifest` files while preserving user configurations (`conf.inc.php`), `.htaccess`, `robots.txt`, and file archives.
- **Standalone Upgrade Tool (`setup/upgrade.php`):** Added an interactive CLI and web-based updater script capable of running database migrations (up through revision r559) and system upgrades independently of the main application. Supports non-interactive automated deployment via `--yes`.
- **Database Revision r559:** Created `phpwcms_update_log` table for auditing update operations, and added `phpwcms_mailtemplates` table with group permissions (`admmailtpl`).
- **Master Email Layout & System Templates:** Added visual template management for system notification emails (**Admin -> Email Templates**).
  - Features modular sub-templates (`mail_layout`, `mail_header`, `mail_footer`) for custom branding.
  - Standardized modern responsive HTML layouts with localized greetings, notes, and signature footers.
  - Multi-charset support ensuring JSON template data encodes cleanly to `PHPWCMS_CHARSET`.
- **Cryptographic Whitelabel Branding:** Built-in whitelabel branding engine with cryptographic key verification, allowing customizable brand names, logos, links, copyright, and admin interface theming.
- **OAuth2 Refresh Token Generator CLI (`bin/get-oauth-token.php`):** Added a standalone CLI utility to obtain Google and Microsoft Azure XOAUTH2 refresh tokens with automated local callback capture and manual fallback.
- **AI Crawler Policy & LLM Context Standards:**
  - Added root-level `ai.txt` and `example.ai.txt` with bot-specific crawling rules, internal path protection (`/login.php`, `/filearchive/`, `/include/`, `/setup/`), and an EU TDM Article 4(3) reservation notice.
  - Added `llms.txt` and `example.llms.txt` adhering to the llmstxt.org specification, providing LLMs with project overviews, architectural maps, technical specifications, and development guidelines.
- **Full Multilingual Localization Parity:** Achieved 100% translation parity across all 35 supported European, regional, and Asian language packs, covering User Management, Messaging, Cookie Consent v3, Custom Content Parts, and the File Archive.
- **Bootstrap 5 UI Modernization:**
  - Implemented a centralized `data-confirm` modal dialog system replacing native browser `confirm()`.
  - Converted checkbox settings to accessible toggle switches across backend forms.
  - Added password visibility toggles with password manager attribute overrides (`data-1p-ignore`, `autocomplete="off"`).
  - Upgraded legacy FontAwesome markup across all backend templates to native FontAwesome 7 syntax.
  - Standardized list action button groups and table controls.
- **Backend Color Theme (Auto / Light / Dark):** Added color theme switching with automatic system detection, light, and dark modes, persisted in user profile variables (`usr_vars`), cookie, and localStorage.
- **Base Font Scaling:** Scaled backend root font size to `.875rem` for improved UI compactness across all interface components.
- **Docker Development Environment:** Added root-level `Dockerfile`, `docker-compose.yml`, and `.dockerignore` providing a complete local PHP 8.2 Apache environment with MariaDB 10.11, phpMyAdmin, Mailpit email capture (`:8026` Web UI, `:1026` SMTP), and the full graphics conversion toolchain (ImageMagick, GraphicsMagick, NetPBM, Ghostscript, Imagick, and GD).
- **Setup Database Auto-Creation & Auto-Detection:** Added automatic interactive database creation in setup when connecting to a server where the target database does not exist yet, alongside socket/port detection, timezone detection, browser language auto-detection, and auto SSL support.
- **Prism.js Highlighting Support:** Added modern code highlighting templates (`JavaScript-Prism.tmpl` and `PHP-Prism.tmpl`) under `template/inc_cntpart/code/example/` using Prism.js.
- **CodeQL Configuration:** Added `.github/codeql/codeql-config.yml` to exclude third-party vendor directories from security scans.

### Changed
- **PHP 8.2+ Architecture & Type Safety:** Comprehensive static analysis pass using PHPStan Level 5 across the entire codebase (1,157 files passing with 0 errors).
- **Hardened Asset Streaming:** Refactored updater asset downloads to use `stream_copy_to_stream` with strict HTTP 200 validation to prevent corrupt or partial file writes on non-200 responses.
- **Isolated Browser Functions:** Scoped local tree-rendering functions in `articlebrowser.php` with `articlebrowser_` prefix to eliminate global symbol table collisions with `admin.functions.inc.php`.
- **Typographic Language Standards:** Enforced typographic single quotes (`’` U+2019) for single-quoted strings and language-specific typographic double quotes (`„ “` German, etc.) across translation dictionaries.
- **Setup Navigation & Stepper Alignment:** Aligned all setup step headlines and card headers with the 1–7 numbered sequence of the stepped navigation wizard.
- **Setup Table Creation Workflow:** Improved setup database initialization to execute table creation immediately upon form submission and pre-fill the creation checkbox on empty databases.
- **Enhanced PHPMailer OAuth2 Integration:** Intercepted constructor validation errors to prevent error erasure during `preSend()`, supported exception options in `PhpwcmsMailer`, and preserved custom port and encryption settings for Google and Azure.
- **CDN Loading for Highlighters & Polyfills:** Modified SyntaxHighlighter, Prism.js, and legacy IE polyfills to load from CDN by default with local file fallbacks.

### Fixed
- **Migration Concurrency & Authentication:** Gated database migrations behind validated admin login and session checks, preventing concurrency race conditions and unauthorized schema adjustments.
- **Email Regex ReDoS (High):** Optimized the email validation pattern in `include/inc_js/phpwcms.js` to prevent potential Regular Expression Denial of Service (ReDoS) backtracking attacks. Added support for plus-addressing (`user+tag@domain.com`).
- **DOM XSS in Ads Module (High):** Cast input dimension fields (`width` and `height`) to integers using `parseInt()` in `include/inc_module/mod_ads/template/ads.js` before writing them to the document context via `document.write()`.
- **PHP 8.2 Database Exceptions in Setup:** Guarded setup table creation and seed data inserts with `CREATE TABLE IF NOT EXISTS`, `INSERT IGNORE INTO`, and exception handling to prevent uncaught `mysqli_sql_exception` fatal errors on existing tables or duplicate entries.
- **Input Group Element Heights:** Removed custom `.input-group-text` padding override in `phpwcms.css` that caused input group addons and buttons to render taller than standard text inputs.
- **Revision r554 Migration on Missing Tables:** Added table existence check in `phpwcms_revision_r554_update_datetime()` so migrations safely skip deprecated or optional tables without throwing missing table errors.

### Removed
- **GD 1.x Legacy Support:** Removed deprecated GD 1.x option from setup media configuration and core image library fallback lists in favor of GD2.
- **Obsolete Language Fragments:** Removed redundant `lang.pp.inc.php` and `lang.ext.inc.php` includes in `login.php`, `phpwcms.php`, `articlebrowser.php`, and `fileinfo.php` (already merged into main `lang.inc.php` language packs).
- **Unused Local Polyfills and Libraries:** Deleted legacy library directories to reduce repository bloat (`syntaxhighlighter`, `html5shiv`, `respond`, `swfobject`, `ie7-js`, and `nonverblaster`).

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
