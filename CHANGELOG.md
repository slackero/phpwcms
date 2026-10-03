# Changelog

All notable changes to this project will be documented in this file.

## [1.12.3] - 2026-07-19

### Fixed
- **WYSIWYG Editor Config Override:** Fixed a bug in `login.php` where initializing the session incorrectly overrode the configured default editor (e.g. TinyMCE 8 / value 2) to CKEditor (value 1), causing wrong options to be loaded and stored in the user profile.

## [1.12.2] - 2026-07-19

### Fixed
- **WYSIWYG Editor Selection Persistence:** Resolved a bug where selecting CKEditor as the default editor in the user profile was not correctly persisted across login sessions, always reverting to TinyMCE.

## [1.12.1] - 2026-07-19

### Added
- **Prism.js Highlighting Support:** Added modern code highlighting templates (`JavaScript-Prism.tmpl` and `PHP-Prism.tmpl`) under `template/inc_cntpart/code/example/` using Prism.js.
- **CodeQL Configuration:** Added `.github/codeql/codeql-config.yml` to exclude third-party vendor directories (e.g. `tinymce`, `mootools`, `jquery`, etc.) from security scans.

### Changed
- **CDN Loading for Highlighters:** Modified SyntaxHighlighter and Prism.js templates to load libraries via CDN by default.
  - Added comments inside templates explaining how to host libraries locally in `template/lib/` if desired.
- **CDN Fallback for IE Polyfills:** Updated legacy IE polyfills (`html5shiv` and `respond`) to load from CDN. 
  - The loader now checks for the existence of local files at `template/lib/html5shiv/html5shiv.min.js` and `template/lib/respond/respond.min.js`. If they are missing, it automatically falls back to secure public CDN hosting.
- **CDN Fallback for SWFObject:** Updated `swfobject` loading in `js.inc.php` to fall back to CDN if local files at `template/lib/swfobject/swfobject.js` are missing.
- **Dependencies Upgrade:** Ran Composer updates on production vendor packages including TinyMCE 8.8.0, PhpSpreadsheet 5.9.0, CommonMark 2.8.3, and Symfony polyfill updates.

### Removed
- **Unused Local Polyfills and Libraries:** Deleted local library directories to reduce repository bloat:
  - Removed local SyntaxHighlighter files (`template/lib/syntaxhighlighter/*`)
  - Removed local html5shiv files (`template/lib/html5shiv/*`)
  - Removed local respond files (`template/lib/respond/*`)
  - Removed local swfobject files (`template/lib/swfobject/*`)
  - Removed local ie7-js files (`template/lib/ie7-js/*`)
  - Removed local NonverBlaster Flash player (`template/lib/nonverblaster/*`)

### Security Fixes
- **PHP Code Injection in Index Page Config Writer (Critical, CWE-94):** `act_structure.php` wrote the fields `acat_permit`, `acat_cntpart` and `acat_timeout` unescaped into `include/config/conf.indexpage.inc.php`, a PHP file required on every frontend request. A crafted value produced persistent remote code execution triggered by anonymous visitors. All three values are now escaped with the existing `sanitize_quote_backslash()` helper, and `acat_cp[]` is validated as integer content part IDs.
- **Path Traversal / Local File Inclusion in Content Template Fields (High, CWE-22/CWE-73/CWE-98):** Template and file name fields of content parts accepted `../` sequences and absolute paths, and `include_ext_php()` skipped its `realpath()` containment check whenever the caller passed a truthy flag — which `cnt21.article.inc.php` did. Containment for local files is now unconditional, new helpers `sanitize_template_name()` and `path_is_within()` were added, and they are applied to all template/file name fields of the content part forms as well as the `cnt51` GET sinks.
- **Stored XSS in Backend Guestbook via Spoofable Client-IP Header (High, CWE-79/CWE-113):** `getRemoteIP()` trusted `HTTP_CLIENT_IP` and `HTTP_X_FORWARDED_FOR` without validation, so an unauthenticated guestbook submission could store arbitrary markup that executed in the backend moderation view. IP values are now validated with `filter_var(FILTER_VALIDATE_IP)` (a public `REMOTE_ADDR` is authoritative, forwarded headers only count behind a private or reserved peer), and the guestbook IP output is escaped and URL-encoded.
- **SQL Injection in Structure Category INSERT (Medium, CWE-89):** `acat_permit` and `acat_cache` were interpolated into the `phpwcms_articlecat` INSERT without escaping while neighbouring values used `_dbEscape()`. Both values are escaped now, and `acat_access[]` is validated as integer user group IDs.
- **Missing CSRF Token Regeneration on Multi-Form Pages (High, regression in 1.12.0):** All forms of a backend page shared the session key `csrf_form_token`, and each form regenerated it, so only the last form of a page could be submitted — creating or editing backend users was impossible. The token is now generated once and reused for all forms of a page. Refs #381.
- **Session Fixation (Medium, CWE-384):** `login.php` did not regenerate the session ID on successful authentication and `session.use_strict_mode` was left at PHP's default. The session ID is regenerated at the auth boundary and strict mode is enabled.
- **Unauthenticated Password Reminder for Inactive Accounts (Medium, CWE-620):** The public password reminder form matched accounts without checking their active state. Both the user detail and backend user lookups now require the account to be active.
- **Object Injection Candidates (Low, CWE-502):** Three `unserialize()` calls omitted `['allowed_classes' => false]`, unlike the rest of the codebase. Fixed in `cnt14`, `cnt50` and the shop frontend search.
- **Email Regex ReDoS (High):** Optimized the email validation pattern in `include/inc_js/phpwcms.js` to prevent potential Regular Expression Denial of Service (ReDoS) backtracking attacks. Added support for plus-addressing (e.g., `user+tag@domain.com`).
- **DOM XSS in Ads Module (High):** Cast input dimension fields (`width` and `height`) to integers using `parseInt()` in `include/inc_module/mod_ads/template/ads.js` before writing them to the document context via `document.write()`, preventing potential DOM XSS.
- **Password Hashing Hardening:** Upgraded backend password updates and setup installations to use secure PHP `password_hash()` instead of legacy MD5, and disabled client-side MD5 pre-hashing on login.
- **Email Replacements XSS in verify.php:** Escaped subscriber email address templates with the `html()` helper to prevent stored XSS (migrated from cmsgo).
- **Scheme Validation Bypass in image_zoom.php:** Resolved scheme validation bypass by checking `scheme` instead of `schema` via `parse_url` results.
- **Version Check Hardening:** Refactored `phpwcmsversionCheck()` to enforce HTTPS connections, set proper request timeouts, and strictly validate HTTP response status codes.
- **WebP PHP Notice Warning:** Added validation to verify the `webp` key exists in the `USER_AGENT` global array before defining the `PHPWCMS_WEBP` constant.

### Dependency Updates
- **league/commonmark 2.8.3 → 2.10.3:** Fixes CVE-2026-71488 (quadratic-time DoS when parsing crafted Markdown) and CVE-2026-71478 (AttributesExtension unsafe-link filter bypass).
- **enshrined/svg-sanitize 0.22.0 → 1.0.0:** Major upgrade of the SVG upload sanitizer; the API used by `class.svg-reader.php` is unchanged.
- **js-cookie 2.2.1 → 3.0.8:** Frontend library update, same file name and `Cookies` global.
- **symfony/polyfill-* → v1.43.0** (PHP 7.3 and 8.1 polyfills stay at their final releases), **phpspreadsheet 5.10.0**, **phpstan 2.2.16**, **tinymce 8.9.2**, **htmlpurifier 4.19.1** and other minor updates within existing constraints.


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
