# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Security
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
