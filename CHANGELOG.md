# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- **Prism.js Highlighting Support:** Added modern code highlighting templates (`JavaScript-Prism.tmpl` and `PHP-Prism.tmpl`) under `template/inc_cntpart/code/example/` using Prism.js.
- **CodeQL Configuration:** Added `.github/codeql/codeql-config.yml` to exclude third-party vendor directories (e.g. `tinymce`, `mootools`, `jquery`, etc.) from security scans.

### Fixed
- **PHPStan Report Clean:** `phpwcms_revision_r554()` no longer checks `$status` in its final return — the value is guaranteed `true` at that point because the function returns early otherwise. Five stale suppression entries (variables `$file_durchlauf`, `$_h`, `$_w`, `$_search_max_pages` and `$_tmpl` in `mod_shop/frontend.render.php`) were removed from `phpstan-baseline.neon`; the analysis now reports no errors against the updated dependency set.

### Changed
- **Composer Dependencies:** `enshrined/svg-sanitize` 1.0.0 → 1.1.1, `netcarver/textile` 4.1.4 → 4.1.6 (constraints lifted to caret ranges), `tinymce/tinymce` 8.9.2 → 8.10.0, `league/commonmark` 2.10.3 → 2.10.4, `php81_bc/strftime` 0.7.6 → 0.7.7, `phpstan/phpstan` 2.2.16 → 2.3.3, plus transitive `guzzlehttp/*`, `nette/*` and the `mklkj/tinymce-i18n`, `simplepie/simplepie`, `roave/security-advisories` dev snapshots.
- **Redundant Polyfills Dropped:** Removed `symfony/polyfill-php73`, `php74`, `php80`, `php81`, `php82` and `polyfill-mbstring` — all superseded by the PHP 8.2 minimum and the required `mbstring` extension. `polyfill-php83` and `polyfill-php84` stay for installs below those branches; `polyfill-php80` also remains as a transitive dependency of Guzzle, PSR-7 and CommonMark.
- **Composer Platform:** `config.platform.php` now `8.2.35` (was `8.2.0`) so the current `roave/security-advisories` snapshot resolves; the declared minimum stays `>=8.2`.
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
- **Unauthenticated `.phtml` Upload Leading to Remote Code Execution (Critical, CWE-434, CVE-2026-110025):** The public Form content part (`cnt23.article.inc.php`) validated uploads against a configurable extension denylist that did not cover `.phtml`, `.pht`, `.phar` or `.phps`, while the shipped `content/form/.htaccess` denied only `*.php`. An unauthenticated visitor could therefore upload a `.phtml` file into the web-accessible `content/form/` folder and execute it on any server that maps that extension to the PHP handler. The default denylist now covers the script and executable extensions, it is enforced unconditionally regardless of a form's `accept`/`exclude` settings, every upload is additionally verified by MIME type (`fileinfo`) and PHP opening-tag detection, and `content/form/.htaccess` denies all script and executable extensions, disables the PHP engines and sets `X-Content-Type-Options: nosniff`. Reported by capivara-research. See [GHSA-2c7r-324f-fxm4](https://github.com/slackero/phpwcms/security/advisories/GHSA-2c7r-324f-fxm4) and [VulDB 417752](https://vuldb.com/vuln/417752).
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
