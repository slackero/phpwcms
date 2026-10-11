---
title: Articles
description: Article metadata, the content part stack, ordering and related actions in the backend.
sidebar_position: 3
---
# Articles

> Source: `include/inc_lib/article.editcontent.inc.php`, `include/inc_tmpl/article.editsummary.tmpl.php`, `include/inc_tmpl/articlecontent.list.tmpl.php`, `include/inc_lib/article.readform.inc.php`, `include/inc_act/act_articlecontent.php`, `include/inc_act/act_structure.php`, `include/inc_act/act_articlesort.php`

An [article](../general/concepts.md#the-content-hierarchy) is edited in two views, both under `phpwcms.php?do=articles&p=2&s=1&id=<article_id>`:

- the **basis information** form (`aktion=1`) for metadata, summary and list images, and
- the **content part stack** (`aktion` unset), which also opens the content part editor with `aktion=2&acid=<cp_id>`.

Non-admin users see and edit only articles they own (`article_uid` = their account ID); copying an article reassigns ownership to the copying user.

## Basis information form

> Source: `include/inc_tmpl/article.editsummary.tmpl.php`, `include/inc_lib/article.editcontent.inc.php` (POST handling, lines 205-458)

The form has four tabs — Content, Images, Meta data, Settings — and three submit buttons: *Update* (submits and stays, `updatesubmit`), *Save* (submits and closes) and *Cancel*. Cancel compares the serialized form against the initial state and asks for confirmation before leaving unsaved changes. Saving requires the title to be non-empty and keeps `article_tstamp` (the "updated at" stamp) driven by content part changes via `change_articledate()`.

### Content tab

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Category (`article_cid`) | select | preselected structural level | Destination level of the article; "Home" = level 0. |
| Article title (`article_title`) | text | — | Required, up to 5000 chars; the level name link copies the category name into the field. Side switch hides the title in frontend output (`article_notitle`). |
| Subtitle (`article_subtitle`) | text | — | Up to 5000 chars. |
| Article alias (`article_alias`) | text | generated from title | Up to 1000 chars; buttons propose title-only, parent-path + title or category-path + title aliases; `alias_allow_slash` decides whether `/` or `-` joins the segments. |
| Language (`article_lang`, `article_lang_type`, `article_lang_id`) | select + radio + picker | default language | Shown only with more than one language; links this article to the default-language article (`article`) or level (`category`) whose ID is picked via the article browser. Below the field, sibling translations of the linked article are listed as links. |
| Alias ID (`article_aliasid`) | text | — | Optional numeric ID to reuse another article's alias context; *Use this article's header data* (`article_headerdata`) adopts that article's header information. |
| Summary (`article_summary`) | WYSIWYG | — | Rich text summary used by listings, teasers and RSS; rendered with the user's [WYSIWYG editor](01-overview.md#wysiwyg-editor-settings). |

### Images tab

Two independent images each combine a file picker (`filebrowser.php?opt=0`), size fields and a caption:

| Field | Applies to | Notes |
| ----- | ---------- | ----- |
| Image / max width / max height (`cimage_*`) | article detail | Defaults come from `template_default.article.image_default_*`; a preview thumbnail renders while editing. |
| Enlarge (`cimage_zoom`), Lightbox (`cimage_lightbox`) | article detail | Lightbox force-checks Enlarge. |
| Caption (`cimage_caption`) + suppress switch | article detail | Pipe-separated parts: caption, alt, link plus target, title, copyright. |
| Same as summary image (`cimage_usesummary`) | article listing | Reuses the detail image in lists. |
| Image / size / caption (`cimage_list_*`) | article listing | Independent listing image with its own zoom/lightbox/caption; defaults from `template_default.article.imagelist_default_*`. |

### Meta data tab

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Page title (`article_pagetitle`) | text | — | Overwrites the frontend `<title>`. |
| Menu title (`article_menutitle`) | text | — | Short title for menus/breadcrumbs. |
| Redirect to (`article_redirect`) | text | — | Sends the frontend visitor to this URL. |
| Canonical link (`article_canonical`) | text | — | Canonical URL for the article. |
| Keywords (`article_keyword`) | tag input | — | Autosuggest over existing categories; stored comma separated and written to the keyword table via `_dbSaveCategories()`. |
| Description (`article_description`) | textarea | — | Meta description. |
| Author (`article_username`) | text | current user name | Free-form author line, up to 200 chars. |
| Robots (`article_meta_noindex`, `article_meta_nofollow`) | switches | 0 | Emits `noindex` / `nofollow`; stored in the `article_meta` JSON column. |
| Created at / Updated at | display | — | Read-only stamps; *Updated at* shows the frozen time when saving without updating the stamp. |

### Settings tab

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Active (`article_aktiv`) | switch | `set_article_active` config | Publishes the article; also togglable from every list. |
| Start date / Starts (`set_begin` + `article_begin`) | datetime (flatpickr) | off | Scheduled start; checked with seconds precision. Empty or cleared with the checkbox. |
| End date / Ends (`set_end` + `article_end`) | datetime (flatpickr) | off | Scheduled end. |
| Sort value (`article_sort`) | number | 0 | Manual position; drag-drop or arrow sorting rewrites these values. |
| Prioritization (`article_priorize`) | select −30…+30 | 0 | Weight used by teasers ordering. |
| Article listing template (`article_tmpllist`) | select | default | Template from `template/inc_cntpart/articlesummary/list/`. |
| Article detail template (`article_tmplfull`) | select | default | Template from `template/inc_cntpart/articlesummary/article/`. |
| CSS class (`article_meta_class`) | text | — | Class added to frontend output, max 250 chars. |
| Summary: max words (`article_listmaxwords`) | number | — | Truncates the summary shown in listings. |
| Hide summary text (`article_hidesummary`) | checkbox | 0 | Summary block is not output on the detail page. |
| [More…] link (`article_morelink`) | checkbox | 1 | Shows the more link under the summary in listings. |
| No teaser (`article_noteaser`) | checkbox | 0 | Excludes the article from teasers. |
| Paginate articles (`article_paginate`) | checkbox | 0 | Splits content parts into paginated subsections. |
| Article owner (`article_uid`) | select | current user | Settable by admins only; non-admin saves keep their own ownership. |
| Cache off + Cache timeout (`article_cacheoff`, `article_timeout`) | switch + select | Default | Per-article frontend cache timeout; same value list as the struct form. |
| Sitemap (`article_nositemap`) | checkbox | on by default only as level default | Include the article in the sitemap. |
| No search (`article_nosearch`) | checkbox | off | Excludes from backend/frontend search. |
| RSS (`article_norss`) | checkbox | 1 on new articles | RSS feed inclusion. |
| Allow Social Sharing (`article_opengraph`) | checkbox | `set_sociallink.article` config | OpenGraph tags for this article. |
| Available after end date (archive) (`article_archive`) | checkbox | 1 on new articles | Article stays listed after its end date when archived. |

## Content part stack

> Source: `include/inc_tmpl/articlecontent.list.tmpl.php`, `include/inc_act/act_articlecontent.php`, `include/inc_act/act_structure.php`, `include/inc_act/act_articlesort.php`

The stack view shows article metadata (alias, category, keywords, flags, dates, owner), then one card per template block — CONTENT (main, paginated when the article paginates), LEFT, RIGHT, HEADER, FOOTER, CPSET and SYSTEM ("no render" containers) — with content parts ordered inside each block.

### Managing content parts

| Action | How | Notes |
| ------ | --- | ----- |
| Add | Type drop-down (new content part) button | The drop-down lists the level's allowed types (`acat_cntpart`), further restricted by the user's `wcs_allowed_cp`; submitting with `ctype` opens the new part's editor. |
| Edit | Pencil on the row | `aktion=2&acid=<id>`; type may be changed to another type/module, which merges the stored values through the target type's readform. |
| Copy within article | Copy icon on the row | `act_structure.php?do=8|<cp>|<aid>|<sort>`; asks for confirmation, inserts the duplicate after the source at `sorting + 5`. |
| Move to another article | Cut/copy in one article → paste arrow on the target article row in the structure tree | `do=7` (move, reindexes both stacks) / `do=8` (copy) in `act_structure.php`. |
| Sort | Drag with the grip handle, or chevron up/down links | Drag posts the complete new order to `act_articlesort.php`; arrows swap neighbor values via `act_articlecontent.php?sort=` and never cross block boundaries. |
| Show/hide | Eye icon | Toggles `acontent_visible` (also on the dashboard's content part list). |
| Delete | Trash icon | Sets `acontent_trash = 9` after a confirmation dialog. Ownership: a part is deletable/toggleable by its owner or an admin. |
| Preview | Button above the header | Opens the article in the frontend with the `phpwcms-preview` flag in a dedicated window. |

Each row shows the content part label, ID badge, last edit date, the block-paginate page number when pagination is on, and a per-type summary rendered by `include/inc_tmpl/content/cnt<N>.list.inc.php`.

Every save through the content part editor touches `article_tstamp`, clears the frontend cache and marks referenced files as in-use for the file center traffic light (`phpwcms_mark_content_files_used()`).

## Revisions

There is no per-article revision history in 2.x. The `include/inc_lib/revision/` directory holds database migration scripts (`r401.php` …) executed automatically at login by `phpwcms_get_available_revisions()` — they upgrade the installation schema, not article content.

## Related

- [Structure tree](02-structure-tree.md)
- [Content parts reference](../content-parts/01-text-with-image.md)