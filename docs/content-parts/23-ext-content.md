---
title: Ext. content
description: Pull the contents of a local page file or a remote URL into the article.
sidebar_position: 23
---
# Ext. content (content part 21)

> Source: `include/inc_lib/content/cnt21.readform.inc.php`, `include/inc_tmpl/content/cnt21.inc.php`, `include/inc_front/content/cnt21.article.inc.php`, `include/inc_front/front.func.inc.php` (`include_url()`, `include_ext_php()`)

Includes the content of a page file from `content/pages/` or — in manual mode — any other local file or remote URL. No templates, no template tags.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Source | radio in input group | From file | `cpage_source`: 0 selects a file from the page browser, 1 takes what is typed into the text field (manual/URL). |
| Custom source field | text | *(empty)* | `cpage_custom`: in manual mode a local path (must exist as file at save time) or a URL — the URL must pass URL validation, and its base URL (without query string) must be fetchable at save time, otherwise the field is cleared. |
| Page browser | radio file list | — | Lists `content/pages/` recursively; accepted extensions are `html`, `htm`, `txt`, `php`, `inc`, `tmpl`; selecting a radio fills the include path (`cpage_file`). Shown regardless of the chosen source. |

A warning shows below the browser while `phpwcms['enable_inline_php']` is off: local PHP files are read as text, not executed.

File paths are normalized and must resolve inside `content/pages/` (`path_is_within()` check); anything else is dropped on save.

## Rendering

- Content part wrapper: `<div class="…" id="…">` when an attribute class or ID is set; content part title/subtitle render through the standard `headline()` function.
- Manual/URL mode (`source = 1`): runs `include_url()` in `include/inc_front/front.func.inc.php`:
  - Fetches the URL via `file_get_contents`; a trailing `CACHE=<seconds>` fragment caches the fetched markup in `content/tmp/` for that many seconds.
  - Charset detection from the meta tag; the body converts into the installation charset.
  - Content between `<body>` and `</body>` is extracted; PHP delimiters are neutralized; relative `href`/`src`/`action` attributes become absolute URLs; output is sanitized (`htmlfilter_sanitize`).
- File mode (`source = 0`): the stored path is read from disk relative to the installation root — or executed through `include_ext_php()` when `phpwcms['enable_inline_php']` is on, so local PHP can generate the output. The result is emitted as: body inner markup when a full HTML document with `<body>`; the markup as-is when it looks like HTML; otherwise HTML-escaped plain text.
- Remote URLs are only honored when `allow_remote_URL` is enabled in `conf.inc.php` (checked inside `include_ext_php`; `include_url()` itself fetches regardless — the save-time validation gates entry).

## Storage

Serialized array `page_file` in `phpwcms_articlecontent.acontent_form` with the keys `source` (0 = file from `content/pages/`, 1 = manual/URL) and `pfile` (path, file path, or URL). No `acontent_template` value is used.

## Related

- Local records instead of remote content: [News (cp 33)](19-news.md)
- Output pipeline for included markup: [General concepts](../general/concepts.md)