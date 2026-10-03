# Changelog

All notable changes to the phpwcms legacy line (PHP 7.4 and older installations) are documented in this file.
New features land on the v1.10-dev branch instead.

## [1.9.50] - 2026-10-03

### Security Fixes
- **PHP Code Injection in Index Page Config Writer (Critical, CWE-94):** `act_structure.php` wrote the fields `acat_permit`, `acat_cntpart` and `acat_timeout` unescaped into `include/config/conf.indexpage.inc.php`, a PHP file required on every frontend request. A crafted value produced persistent remote code execution triggered by anonymous visitors. All three values are now escaped with the existing `sanitize_quote_backslash()` helper, and `acat_cp[]` / `acat_access[]` are validated as integer IDs.
- **Path Traversal / Local File Inclusion in Content Template Fields (High, CWE-22/CWE-73/CWE-98):** Template and file name fields of content parts accepted `../` sequences and absolute paths, and `include_ext_php()` skipped its `realpath()` containment check whenever the caller passed a truthy flag — which `cnt21.article.inc.php` did. Containment for local files is now unconditional, the new helpers `sanitize_template_name()` and `path_is_within()` were added (without PHP 8 only functions, so PHP 7.4 keeps working), and they are applied to all template/file name fields of the content part forms as well as the `cnt51` GET sinks.
- **Stored XSS in Backend Guestbook via Spoofable Client-IP Header (High, CWE-79/CWE-113):** `getRemoteIP()` trusted `HTTP_CLIENT_IP` and `HTTP_X_FORWARDED_FOR` without validation, so an unauthenticated guestbook submission could store arbitrary markup that executed in the backend moderation view. IP values are now validated with `filter_var(FILTER_VALIDATE_IP)` (a public `REMOTE_ADDR` is authoritative, forwarded headers only count behind a private or reserved peer), and the guestbook IP output is escaped and URL-encoded.
- **SQL Injection in Structure Category INSERT (Medium, CWE-89):** `acat_permit` and `acat_cache` were interpolated into the `phpwcms_articlecat` INSERT without escaping while neighbouring values used `_dbEscape()`. Both values are escaped now.
- **Session Fixation (Medium, CWE-384):** `login.php` did not regenerate the session ID on successful authentication and `session.use_strict_mode` was left at PHP's default. The session ID is regenerated at the auth boundary and strict mode is enabled.
- **Unauthenticated Password Reminder for Inactive Accounts (Medium, CWE-620):** The public password reminder form matched accounts without checking their active state. Both the user detail and backend user lookups now require the account to be active.
- **Object Injection Candidates (Low, CWE-502):** Two `unserialize()` calls omitted `['allowed_classes' => false]` — in `cnt50.article.inc.php` and the shop frontend search. `cnt14.article.inc.php` already passed the option on this branch.

### Dependency Updates
- **league/commonmark 2.8.3 → 2.10.3:** Fixes CVE-2026-71488 (quadratic-time DoS when parsing crafted Markdown) and CVE-2026-71478 (AttributesExtension unsafe-link filter bypass).
- **enshrined/svg-sanitize 0.22.0 → 1.0.0:** Major upgrade of the SVG upload sanitizer; the API used by `class.svg-reader.php` is unchanged.
- **js-cookie 2.2.1 → 3.0.8:** Frontend library update, same file name and `Cookies` global.
- **symfony/polyfill-* → v1.43.0** (PHP 7.3 and 8.1 polyfills stay on their final releases), **phpspreadsheet 1.30.7**, **htmlpurifier 4.19.1**, **textile 4.1.5**, **http_build_url 1.0.2** and other minor updates within existing constraints.
