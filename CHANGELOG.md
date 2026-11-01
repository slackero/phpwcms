# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Fixed
- **Skipped Revisions on Upgrade:** `phpwcms_revision_check()` only executed the newest available revision file and relied on every revision calling its predecessor, so an upgrade could silently skip pending revisions — an upgrade from the legacy 1.9.50 release skipped the r554 date conversion and left all articles hidden behind the `0000-00-00` end date. It now iterates every revision up to the target revision, and the checked markers are persisted in `phpwcms_sysvalue`, so a cleaned `content/tmp/` folder no longer loses upgrade state. Fixes #389.
- **Obsolete `sysvalue_tstamp` Column and Bad Behavior Table:** `r554` no longer aborts when an optional module table such as `phpwcms_ads_campaign` does not exist; missing tables and columns are skipped and per-table failures are reported. The revision engine also drops the leftover `sysvalue_tstamp` column (`NOT NULL DEFAULT '0000-00-00 00:00:00'`, which made marker INSERTs fail under strict `NO_ZERO_DATE`) and the `phpwcms_bad_behavior` table left from the Bad Behavior library removed in 1.5. Fixes #390.
- **PHPStan Report Clean:** `phpwcms_revision_r554()` no longer checks `$status` in its final return — the value is guaranteed `true` at that point because the function returns early otherwise. Five stale suppression entries (variables `$file_durchlauf`, `$_h`, `$_w`, `$_search_max_pages` and `$_tmpl` in `mod_shop/frontend.render.php`) were removed from `phpstan-baseline.neon`; the analysis now reports no errors against the updated dependency set.

### Dependency Updates
- **Dependency Refresh:** `league/commonmark` 2.10.4 → 2.10.7, `tinymce/tinymce` 8.10.0 → 8.10.1, `phpstan/phpstan` 2.3.3 → 2.3.4, plus the current `simplepie/simplepie` and `roave/security-advisories` snapshots. Constraints state the installed versions as caret ranges.


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
