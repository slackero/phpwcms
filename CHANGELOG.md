# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Security
- **Host Header Poisoning of Mailed Links:** `get_url_origin()` believes `X-Forwarded-Host` only when the request comes from a `trusted_proxies` entry, validates the resulting host (RFC 3986 reg-name, label and port limits) and honours the new `$phpwcms['trusted_hosts']` allowlist. Every mailer that embeds an absolute link — password reminder, form double opt-in, newsletter verification, guestbook notification, e-card, profile notifications, feed import status and the backend newsletter send — now uses `phpwcms_mail_site_url()` and skips the mail when the site URL was derived from an unverified request host. The setup wizard now stores the detected URL in `site` when the field is left empty, so a fresh install has a canonical host; a URL entered by the operator must be an absolute `http(s)` URL without credentials, query or fragment and is stored with a normalised trailing slash. Closes GHSA-x5fx-f274-39p8.
- **Local File Disclosure via Form Attachments:** only a field declared as `upload` may carry `folder`, `name` and `attachment` metadata. The resolved file must exist and pass `realpath()` containment inside `PHPWCMS_ROOT`. Array POST values keep their values but never their keys, and the same gate protects the error-path file deletion. Closes GHSA-9mvh-hxj5-9fgg.
- **Inline Download of Uploaded Files:** Inline rendering is now limited by an extension allowlist (raster images, PDF, plain text/CSV, audio and video); every other extension is delivered as attachment. The stored MIME type, which is client controlled at upload through `X-Mime-Type` and `$_FILES`, no longer decides the disposition: `dl_file_resume()` re-detects the type with `fileinfo` and falls back to attachment when the detected type can execute scripts. `download.php?file=` ignores the `?type=` parameter for inline responses, sends `Content-Disposition` on the iOS range download path, and both delivery paths send `X-Content-Type-Options: nosniff`. Closes GHSA-rmf4-vf3j-hq8g.

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
