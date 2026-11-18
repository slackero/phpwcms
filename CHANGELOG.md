# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Security
- **Form Upload Extension Check:** the public Form upload validated `which_ext()` of the raw multipart name but stored `sanitize_filename()` of it, so a name such as `marker.php.` had no extension for the deny list, and the cleaning removed the trailing dot, storing it as `<time>_marker.php` under `content/form/`. The PHP opening tag scan read only the first 8192 bytes, and the folder's `.htaccess` is inert when the web server runs with `AllowOverride None` (the stock Debian Apache default). The extension check and both accept/exclude regexps now run against the stored file name, so the trailing-dot name is denied as `.php`. The deny list also covers `php2`, `php6`, `php9`, `shtml` and `shtm`, the PHP opening tag scan reads the whole file in chunks instead of the first 8192 bytes, and the stored name carries a random component so it is no longer derivable from the response time. Revision `r563` quarantines existing uploads with an executable extension: it moves them, renamed with a `.quarantine` suffix, from `content/form` into `content/tmp/.form-upload-quarantine/<timestamp>/` and reports the list, so an administrator can review and restore them instead of losing them to a silent delete.

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
