---
title: Default templates
description: Every shipped template in template/inc_default/ and the content part or feature that loads it.
sidebar_position: 4
---
# Default templates

> Source: `template/inc_default/`, renderers in `include/inc_front/content/`, `include/inc_front/content.article.inc.php`, `verify.php`

Directory `template/inc_default/` ships one working template per feature plus the print templates and the backend dashboard snippet. Renderers use these files as step two of the [template fallback chain](template-system.md#which-content-part-template-is-used): when a content part has no template selected, the default ships; when the selected file is missing, the hard-coded fallback in the renderer applies.

All files are plain text templates read with `file_get_contents()` and processed with section markers and content part tags — they run as data, not as code (the single `startup.php` below is the exception, included by the backend).

## Content part defaults

| File | Used by | Notes |
| ---- | ------- | ----- |
| `plaintext.tmpl` | CP 0 — Plain text | Loaded from `cnt0.article.inc.php` |
| `imagetext.tmpl` | CP 1 — Text with image | |
| `imagetable.tmpl` | CP 2 — Image table | |
| `bulletlist.tmpl` | CP 4 — Bullet list | |
| `linklist.tmpl` | CP 5 — Link list | |
| `html.tmpl` | CP 6 — HTML | |
| `multimedia.tmpl` | CP 9 — Multimedia | |
| `code.tmpl` | CP 11 — Code | |
| `search.tmpl` | CP 13 — Search | |
| `wysiwyg.tmpl` | CP 14 — WYSIWYG | |
| `guestbook.tmpl` | CP 18 — Guestbook | |
| `flashplayer.tmpl` | CP 25 — Flash player | |
| `rssfeed.tmpl` | CP 22 — RSS feed | Consumer of external feeds, template section `FEEDINFO`/`FEED` loop |
| `tabs.tmpl` | CP 32 — Tabs | |
| `teaser.tmpl` | CP 8 — Article menu (teaser) | Detail mode template |
| `list.teaser.tmpl` | CP 8 — Article menu (teaser) | List mode variant, loaded when `acontent_template_listmode` is set |
| `news.tmpl` | CP 33 — News | October 2025 reworked default |
| `reference.tmpl` | CP 50 — Reference | |

CP 29 (image div) and CP 31 (image special) share two defaults:

| File | Used by |
| ---- | ------- |
| `images.tmpl` | CP 29 and CP 31, detail mode |
| `list.images.tmpl` | CP 29 and CP 31, list mode |
| `imagespecial.tmpl` | CP 31 — Image special |
| `list.imagespecial.tmpl` | CP 31 — Image special, list mode |

## Article summary defaults

| File | Used by | Notes |
| ---- | ------- | ----- |
| `article_summary.tmpl` | Full article header on article pages | Loaded by `content.article.inc.php` when the article has no custom full template |
| `article_summary_paginate.tmpl` | Article pages with content part pagination | Used when the article is paginated and the selected template also has a paginate variant |
| `article_summary_list.tmpl` | Article list entries | Loaded by `list_articles_summary()` in `front.func.inc.php` when neither the level nor the caller provides a list template |

The summary templates carry the shared article tags `[TITLE]`/`{TITLE}`, `[SUB]`/`{SUB}`, `[EDITOR]`, `[SUMMARY]`, `[MORE]`, `[CLASS]`, `[SYSTEM]`, the `[IMAGE]` section with `{IMAGE_*}` placeholders, `[CAPTION]`, `[COPYRIGHT]`, `[ZOOMIMAGE]`, `[IMAGE_URL]`, `[IMAGE_TARGET]` and the date tags `{DATE:…}`, `{LIVEDATE:…}`, `{KILLDATE:…}`.

## Print and output defaults

| File | Used by | Notes |
| ---- | ------- | ----- |
| `print.tmpl` | `?print=1` | Wraps `{CONTENT}` plus `{CURRENT_URL}`, may hold `[NO_PRINT]` sections |
| `pdf.tmpl` | `?print=2` | Same plus `[PDF_SETTINGS]` INI section parsed before `wkhtmltopdf` runs |

## Newsletter defaults

| File | Used by | Notes |
| ---- | ------- | ----- |
| `subscribe.tmpl` | `verify.php?s=<hash>` | Double opt-in confirmation page, `{EMAIL}` and global RTs are replaced |
| `unsubscribe.tmpl` | `verify.php?u=<hash>` | Unsubscribe confirmation page, same replacements |

The subscription content part (CP 12) renders its form inline and does not load a file from this directory.

## Backend default

| File | Used by | Notes |
| ---- | ------- | ----- |
| `startup.php` | Backend dashboard (`phpwcms.php` start and news screens) | PHP template; renders the support/Impressum card configured under `$phpwcms['support']` |

## Unreferenced file

`custom.tmpl` ships in the directory but no renderer references it — customizable content parts use the templates under `template/inc_cntpart/custom/` instead (see [template system](template-system.md#which-content-part-template-is-used)).

## Related

- [Template system](template-system.md)
- [Core concepts: content part](../content-parts/index.md)