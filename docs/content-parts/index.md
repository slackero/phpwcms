---
title: Content parts
description: Every content part type, how the stack orders and renders them, and where the code lives.
sidebar_position: -1
---

# Content parts

> Source: `include/inc_lib/article.contenttype.inc.php`, `include/inc_front/content.article.inc.php`, `include/inc_lib/modules.check.inc.php`

A [content part](../general/concepts.md) is one functional block inside an article: a text with image, an image gallery, a teaser list, a form, a search and so on. Every article is a stack of content parts; the stack is what the frontend renders into the `{CONTENT}` slot of the page layout. Editors add, order, group and hide content parts in the article detail view of the backend.

## How the stack renders

The frontend dispatch lives in `include/inc_front/content.article.inc.php`. For the current article it selects the content part rows from `phpwcms_articlecontent`:

```sql
SELECT * FROM phpwcms_articlecontent
WHERE acontent_aid = <article id>
  AND acontent_visible = 1 AND acontent_trash = 0
  AND (acontent_livedate IS NULL OR acontent_livedate < NOW())
  AND (acontent_killdate IS NULL OR acontent_killdate > NOW())
  AND acontent_granted = 0        -- anonymous: = 0, logged in: != 2
ORDER BY acontent_sorting, acontent_id
```

So the order is the sort value `acontent_sorting`, set per content part in the backend (the up/down arrows swap two rows' sort values), then the row ID for ties. A content part renders only when it is visible, not trashed, inside its live/kill date window and its frontend permission (`acontent_granted`) matches the login state of the visitor.

Each row is then rendered by its own renderer (see [file layout](#file-layout)) into a temporary buffer. The dispatcher wraps every part with optional pieces before adding it to the output:

| Feature | Behavior |
| ------- | -------- |
| Anchor | `acontent_anchor` emits `<a id="cpid<N>"></a>` with the row's ID as `N` so the part is link-targetable. |
| Spacing | `acontent_before` / `acontent_after` emit spacer CSS classes or `div` wrappers. |
| Content block | `acontent_block` = `CONTENT` (default) appends to the main output; any other value renders into a block slot defined by the layout template instead. |
| Tabs / groups | `acontent_tab` folds the part into a tab box or accordion group assembled after the loop. |
| Pagination | With article pagination enabled, `acontent_paginate_page` selects which article page the part belongs to. |
| Alias | Type 24 replaces its own row with the referenced content part before rendering. |
| `[%CPID%]` / `{CPID}` | Replaced with the DB row ID in the rendered output of every part. |
| Top link | `acontent_top` adds a back-to-top link. |

> **Note:** The layout template can additionally fill `[SYSTEM]` blocks with content parts assigned to the `SYSTEM` block; those parts are only rendered when the layout template requests them.

## Content part types

Types are identified by `acontent_type` and mapped to backend labels in `$wcs_content_type` (`include/inc_lib/article.contenttype.inc.php`); English labels come from `include/inc_lang/backend/en/lang.inc.php`.

| ID | Backend label | Reference |
| -- | ------------- | --------- |
| 0 | Plain text | [Plain text](00-plaintext.md) |
| 1 | Text w/image | [Text w/image](01-text-with-image.md) |
| 2 | Images | [Images](09-images.md) |
| 3 | Link &amp; email | [Link &amp; email](07-link-email.md) |
| 4 | List | [List](05-list.md) |
| 5 | Link list | [Link list](08-linklist.md) |
| 6 | HTML | [HTML](02-html.md) |
| 7 | File list | [File list](16-filelist.md) |
| 8 | Teaser/link article | [Teaser/link article](17-teaser-link-article.md) |
| 9 | Multimedia | [Multimedia](12-multimedia.md) |
| 10 | Email form generator [old] | [Email form generator (old)](26-email-form-old.md) |
| 11 | Code | [Code](04-code.md) |
| 12 | Newsletter | [Newsletter](27-newsletter-cp.md) |
| 13 | Search | [Search](20-search.md) |
| 14 | WYSIWYG HTML | [WYSIWYG HTML](03-wysiwyg-html.md) |
| 15 | Article menu | [Article menu](18-article-menu.md) |
| 16 | E-card | [E-card](24-ecard.md) |
| 18 | Guestbook/comment | [Guestbook](28-guestbook.md) |
| 19 | Sitemap | [Sitemap](21-sitemap.md) |
| 21 | Ext. content | [Ext. content](23-ext-content.md) |
| 22 | RSS feed | [RSS feed](22-rss-feed.md) |
| 23 | Form | [Form](25-form.md) |
| 24 | Contentpart alias | [Contentpart alias](29-contentpart-alias.md) |
| 25 | HTML5/Flash media player | [HTML5/Flash media player](13-flashplayer.md) |
| 26 | Recipe | [Recipe](32-recipe.md) |
| 27 | FAQ | [FAQ](33-faq.md) |
| 28 | Frontend login | [Frontend login](34-frontend-login.md) |
| 29 | Images &lt;div&gt; | [Images &lt;div&gt;](10-images-div.md) |
| 31 | Images special | [Images special](11-images-special.md) |
| 32 | Tabs | [Tabs](35-tabs.md) |
| 33 | News | [News](19-news.md) |
| 50 | Rollover images | [Rollover images](14-rollover-images.md) |
| 51 | Map | [Map](15-map.md) |
| 52 | PHP variables | [PHP variables](30-php-variables.md) |
| 60 | Custom CP | [Custom CP](36-custom-cp.md) |
| 89 | Poll | [Poll](31-poll.md) |
| 100 | List | [ul-list](06-list-ul.md) |

- IDs are historical and not consecutive; unlisted IDs belong to removed types. The table itself is ordered by ID, but the backend select lists types alphabetically by label by default (`$phpwcms['cnt_sort'] = 'a-z'` in `include/config/dist.conf.inc.php`; `z-a` reverses it, empty keeps the definition order of the array).
- Labels 4 and 100 are both "List": ID 4 converts lines to a simple `<ul>` (template driven), ID 100 builds nested `<ul>`/`<ol>`/`<dl>` markup from `~` indent markers.
- Type 30 is reserved for modules; see below.

## Module content parts (type 30)

Modules can provide their own content part. A module advertises this by setting `$_module_contentpart = true` in its `module.default.php`; registration happens in `include/inc_lib/modules.check.inc.php`, which stores the flag as `$phpwcms['modules'][<module>]['cntp']`.

When at least one active module registers a content part, the content part select gains the entry **Plug-in** (ID 30). A row of type 30 stores the module name in `acontent_module` and renders through the module instead of the standard renderer:

- Frontend render: `<module dir>/inc/cnt.article.php`
- Backend dialog: `<module dir>/inc/cnt.form.php`
- Submitted values: `<module dir>/inc/cnt.post.php`

The row is only rendered while its module stays enabled. Module-provided types are documented with their module, see the [modules reference](../modules/index.md).

## File layout

Every content part type consists of a small, predictable set of files, numbered by its ID:

| File | Role |
| ---- | ---- |
| `include/inc_lib/content/cnt<N>.readform.inc.php` | Reads the submitted dialog fields from `$_POST` into `$content`. Shared fields (title, spacing, dates, block, …) are handled first in `include/inc_lib/article.readform.inc.php`. |
| `include/inc_lib/content/cnt<N>.sql.inc.php` | Builds the `UPDATE phpwcms_articlecontent …` column assignments. |
| `include/inc_lib/content/cnt<N>.takeval.inc.php` | Repopulates `$content` from the DB row when the dialog opens for editing. |
| `include/inc_tmpl/content/cnt<N>.inc.php` | The type-specific section of the backend dialog; included by `include/inc_tmpl/articlecontent.edit.tmpl.php`. |
| `include/inc_tmpl/content/cnt<N>.list.inc.php` | The one-line summary shown for this part in the article's content part list. |
| `include/inc_front/content/cnt<N>.article.inc.php` | The frontend renderer. |

A type without one of these files falls back: the dialog uses `cnt0.inc.php`/`cnt0.list.inc.php` and the submit handler `cnt0.readform.inc.php`.

### Template resolution

Where a content part supports templates, its renderer resolves in this order:

1. The file selected in the dialog, from `template/inc_cntpart/<type>/<name>.tmpl`.
2. The shipped default from `template/inc_default/<name>.tmpl`.
3. A built-in skeleton hard-coded in the renderer.

Custom templates live under `template/inc_cntpart/`; each backend dialog lists the files of the matching subdirectory. Available shipped default templates:

```text
template/inc_default/
article_summary.tmpl          article_summary_list.tmpl     article_summary_paginate.tmpl
bulletlist.tmpl               code.tmpl                     custom.tmpl
filelist.tmpl                 filelist_inline.tmpl          flashplayer.tmpl
guestbook.tmpl                html.tmpl                     images.tmpl
imagespecial.tmpl             imagetable.tmpl               imagetext.tmpl
linklist.tmpl                 list.images.tmpl              list.imagespecial.tmpl
list.teaser.tmpl              multimedia.tmpl               news.tmpl
pdf.tmpl                      plaintext.tmpl                print.tmpl
reference.tmpl                rssfeed.tmpl                  search.tmpl
startup.php                   subscribe.tmpl                tabs.tmpl
teaser.tmpl                   unsubscribe.tmpl              wysiwyg.tmpl
```

Available custom template directories:

```text
template/inc_cntpart/
articlesummary/   bulletlist/   code/         custom/       faq/
felogin/          filelist/     flashplayer/  guestbook/    html/
images/           imagespecial/ imagetable/   imagetext/    linkemail/
linklist/         map/          multimedia/   news/         newsletter/
plaintext/        recipe/       reference/    rssfeed/      search/
tabs/             teaser/       template-sections/  wysiwyg/
```

> **Note:** `articlesummary/` and `template-sections/` do not belong to a content part type; they hold article summary and layout section templates.

## Reference

Every type has its own page with backend form, rendering, storage and related links. The table under [Content part types](#content-part-types) links them all.