---
name: phpwcms-code-and-frontend
description: PHP coding standards, helper functions, content part renderers (cnt29/cnt31/cnt60), global template replacers ({LAZY_LOADING}, {PHPWCMS_RESIZE_IMAGE}), CSS/JS asset build rules, and icon conventions in phpwcms. Use when writing or reviewing PHP code, content part templates, CSS, or frontend assets.
---

# phpwcms Code & Frontend Architecture Skill

Use this skill when writing or refactoring PHP code, editing content part renderers or `.tmpl` files, or modifying CSS/JS assets.

## 1. PHP Coding Conventions
- **Product Name**: Always write **phpwcms** in all lowercase letters (in comments, docs, commits, UI text). Never write `phpWCMS`.
- **PSR-12 for new/touched code**: Use 4-space indentation, opening braces on the same line for control structures and next line for classes/functions, and lowercase `true`/`false`/`null`. Do not wholesale-reformat untouched legacy files.
- **Quotes**:
  - Always use **single quotes** `'...'` for PHP strings unless variable interpolation is required.
  - Always use **double quotes** `"..."` for HTML tag attributes.
- **Type Casts**: Use native PHP casts `(int)$var`, `(string)$var`, `(float)$var`, `(bool)$var`, `(array)$var` instead of `intval()`, `strval()`, `floatval()`, `boolval()` (unless `intval($var, $base)` with a non-10 base is needed).
- **Built-in Helpers**: Prefer phpwcms helpers over raw PHP equivalents (e.g., use `html()` instead of `htmlspecialchars()` or `htmlescape()`).
- **Path Resolution**: Use `dirname(__DIR__, N) . '/path'` instead of `__DIR__ . '/../../path'`.
- **Analysis Exclusions**: Ignore `/include/vendor` and `node_modules/` during code searches and analysis.

## 2. Content Part Renderers & Template Tokens
- **Renderer Mapping (`include/inc_front/content/`)**:
  - `cnt29.article.inc.php` → **images (`<a>`)** gallery (`template/inc_cntpart/images/`)
  - `cnt31.article.inc.php` → **imagespecial** (`template/inc_cntpart/imagespecial/`)
  - `cnt60.article.inc.php` → **custom** content parts (`template/inc_cntpart/custom/`)
  - `cnt27.article.inc.php` → **faq** (`template/inc_cntpart/faq/` — flat single-question template, no loop sections)
  - Always check the `inc_cntpart/<dir>` path inside a `cnt*.article.inc.php` file before editing to avoid mixing up `cnt29` and `cnt31`.
- **Template Section Nesting**:
  - In `cnt29` / `cnt31`, `ENTRY_DATA` sections are extracted from *inside* the rendered `ENTRY` section (`get_tmpl_section('ENTRY_DATA', $img_a)`), never at the top level.
  - First/last tags: `[FIRST]` (row-first in `cnt29`, first-overall in `cnt31`), `[IMAGE_FIRST]` (first-overall in `cnt29`/`cnt31`), `[ITEM_FIRST]`/`[ITEM_LAST]` (`cnt60`).
- **Global Replacers vs. Per-Renderer Tokens**:
  - `render_cnt_template()` (`include/inc_front/front.func.inc.php`) handles both scalar `{TAG}` and conditional `[TAG]...[/TAG]` / `[TAG_ELSE]...[/TAG_ELSE]` blocks.
  - **Global Replacers** run *after* all `cntN.article.inc.php` renderers in two places:
    1. `include/inc_front/content.func.inc.php`: Replaces `{LAZY_LOADING}` globally with `PHPWCMS_LAZY_LOADING` (` loading="..." decoding="async"` or empty string based on `$phpwcms['lazy_loading']`) and runs the `$content['globalRT']` loop. Never hardcode `loading="lazy"` or `decoding="async"` in templates — always use `{LAZY_LOADING}`.
    2. `index.php` (final assembly): Replaces `{PHPWCMS_RESIZE_IMAGE}` with `PHPWCMS_RESIZE_IMAGE` and rewrites `img/cmsimage.php` when `PHPWCMS_REWRITE` is active.

## 3. Backend CSS & Font Awesome Icons
- **Icon Markup Convention**:
  - Icon-only action buttons and links must contain an explicit Font Awesome `<i>` element (e.g., `<i class="fa-solid fa-folder-open" aria-hidden="true"></i>`), not rely on CSS `:empty:before` glyph rules.
  - Exception: `.visible` and `.public` state-toggle buttons keep CSS `:before` icons because their glyph changes dynamically with `btn-success`/`btn-warning`/`btn-danger`.
- **Verifying CSS Before Removal (`include/inc_css/phpwcms.css`)**:
  - Never delete a CSS selector without grepping every individual selector variant across `include/`, `template/`, and `setup/` AND checking for dynamic class construction in PHP (e.g., `'btn ' . $cls`, `badge-align-*`).
  - Whenever `include/inc_css/phpwcms.css` is modified, rebuild `include/inc_css/backend.min.css` via `make css-minify` (or `make minify-sync`).

## 4. Frontend Libraries & Build Artifacts (`template/lib/`, `include/inc_ext/`)
- Before removing any file under `template/lib/` or `template/inc_css/`, check all four usage channels:
  1. `<!-- JS: ... -->` and `<!-- CSS: ... -->` head comments inside `.tmpl` files.
  2. `include/build/*.js` (`copyMap` entries — managed npm build artifacts recreated by `npm run build`).
  3. Selectable library loaders (e.g., `$phpwcms['js_lib']` jQuery variants).
  4. Database-stored user templates (which can link shipped files like `frontend.css` directly without appearing in a repository grep).
- **CKEditor 4 (`include/inc_ext/ckeditor`)**: Locked at **4.22.1** (the final open-source GPL/LGPL/MPL release; 4.23+ LTS is commercial-only). Do not attempt to bump CKEditor 4 to 4.23+.
- **TinyMCE (`include/vendor/tinymce/tinymce`)**: Served directly from `include/vendor/tinymce/tinymce/tinymce.min.js` with translations in `include/vendor/mklkj/tinymce-i18n/langs/`. Do not copy TinyMCE assets outside `include/vendor/`.
