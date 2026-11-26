# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Security
- **Inline JavaScript escaping in the backend:** article and content-part titles reached the article structure list through `js_singlequote()`, which only HTML-encodes quotes. The HTML parser decodes `&#39;` before the inline handler is compiled, so a backend user below administrator could store `X'+((document.title='…'),'')+'` as an article title, content-part title, subtitle or comment and run script in an administrator's session when the list was opened, a row was hovered, or a delete action was confirmed. Inline handler values now use `js_inline_string()`, `Tip()` tooltip values use `js_tooltip_string()` so tooltip markup is shown as text, and `js_string()` covers `<script>` blocks, where it also neutralises `</script>`, U+2028 and U+2029. The private file confirms, the file category, newsletter, subscriber, template, page layout, module (ads, calendar, feed import, glossary, shop) and front-end link handlers were converted in the same pass, and `js_singlequote()` is deprecated. A regression test in `tests/core/test-tooltip-escaping.php` now fails when a new output path uses it.

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
