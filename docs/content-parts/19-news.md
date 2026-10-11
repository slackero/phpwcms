---
title: News
description: List and show news records as paginated teasers or detail pages.
sidebar_position: 19
---
# News (content part 33)

> Source: `include/inc_lib/content/cnt33.readform.inc.php`, `include/inc_tmpl/content/cnt33.inc.php`, `include/inc_front/content/cnt33.article.inc.php`, `include/inc_lib/news.inc.php`, `template/inc_default/news.tmpl`

Renders news records — rows in `phpwcms_content` with `cnt_module='news'`, edited in the backend news area (`include/inc_lib/news.inc.php`). The content part works without any module activation; it reads the news table directly. Each content part instance acts as list and detail renderer: when the URL carries a `newsdetail` parameter the matching record is shown, otherwise the list.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/news/`. Empty uses `template/inc_default/news.tmpl`. |
| Sorting | select | Live date, DESC (`5` in the dialog; empty/invalid values are saved as `9`) | Sort value per table below. |
| Use prio | checkbox | off | Prepend `cnt_prio DESC` to the sort order. |
| Tags | text with tag autosuggest (news tags) | *(empty)* | `news_category`: values compared against `phpwcms_categories` rows with `cat_type='news'`, combined by And/or. |
| And/or | select | OR | `OR`, `AND`, `NOT` for the tag comparison. |
| Language | checkboxes | *(empty = all)* | `news_lang`: restrict to `cnt_lang` values; shown only when the installation allows more than one backend language. |
| Show | select | Archived items: exclude (`1` in the dialog) | Archive behavior per table below. |
| Item count | text (5 chars) | *(empty)* | `news_limit`: last value of `LIMIT` for the list. |
| Skip first items | text (5 chars) | *(empty)* | `news_skip`: `LIMIT` offset — the first N matching records are ignored. |
| Pagination | checkbox | off | `news_paginate`: enable `?newspage=N` navigation. |
| Pagination basis | select | Count (0) | `news_paginate_basis`: 0 count, 1 day, 2 ISO week, 3 month, 4 year. Higher values are reset to 0 when invalid. |
| Items per page | text (5 chars) | 10 when Pagination is on else empty | `news_paginate_count`; only used with basis Count. |
| Archive link | text (250 chars) + article browser | *(empty)* | `news_archive_link`: article alias or `aid=1`/`id=3` target. Stored; not evaluated by the current renderer. |
| News article | text (250 chars) + article browser | *(empty)* | `news_detail_link`: target article for detail links — numeric value becomes `aid=<n>`, otherwise it is a URL alias. Empty opens details on the article containing the content part. |

### Sorting values

| Value | Meaning |
| ----- | ------- |
| 9 / 10 | Sort date (`cnt_ts_sortdate` = custom `cnt_sort`, else live date, else create date) DESC / ASC |
| 1 / 2 | Create date (`cnt_created`) DESC / ASC |
| 3 / 4 | Change date (`cnt_changed`) DESC / ASC |
| 5 / 6 | Live date (`cnt_livedate`, falling back to `cnt_created`) DESC / ASC |
| 7 / 8 | Kill date (`cnt_killdate`) DESC / ASC |
| 17 / 18 | Title (`cnt_title`) DESC / ASC |
| 11 / 12 | Editor (`cnt_editor`) then sort date DESC / ASC |
| 13 / 14 | Internal name (`cnt_name`) DESC / ASC |
| 15 / 16 | Place (`cnt_place`) then sort date DESC / ASC |

### Show (archive) values

| Value | Meaning |
| ----- | ------- |
| 0 | Include archived: live date passed and (kill date not passed or `cnt_archive_status=1`) |
| 1 | Exclude archived: live date passed and kill date not passed |
| 2 | Archived only: kill date passed and `cnt_archive_status=1` |
| 3 | All items with live date passed, including expired ones |

The list compares against `cnt_ts_livedate` (live date, falling back to `cnt_created`) and `cnt_ts_killdate` (kill date, falling to the current time + 1 hour when empty).

## Rendering

### List and detail

- Detail request: `newsdetail` must match `YYYYMMDD-<aid>_<id|alias>`; the number after `_` selects by `cnt_id` (numeric) or `cnt_alias`. Detail mode disables the canonical `<link>`, sets the OpenGraph type to `article`, and promotes the record title to the page title plus meta description from the teaser text.
- List mode groups SQL by `cnt_id` when tag filtering, applies `news_skip`/`news_limit` as `LIMIT`, and in pagination mode computes page counts (count basis: `ceil(total / per page)`; date bases bucket by `DATE_FORMAT(FROM_UNIXTIME(cnt_ts_livedate), …)` per day/week/month/year, ordered like the list). Page navigation uses the `newspage` GET parameter.
- A frontend edit link is prepended per entry when `FE_EDIT_LINK` is enabled.

### Template sections

| Section | Meaning |
| ------- | ------- |
| `NEWS_SETTINGS` | INI-style settings (see table below). |
| `NEWS_LIST` | List wrapper; removed in detail mode. |
| `NEWS_LIST_ENTRY` | Repeated per entry in list mode. |
| `NEWS_LIST_ENTRY_SPACE` / `NEWS_LIST_ROW_SPACE` | Spacers between entries/rows per `news_per_row`. |
| `NEWS_DETAIL` | Entry markup in detail mode. |
| `GALLERY_ITEM` | Sub-section of the entry template, repeated per gallery image. |

`NEWS_SETTINGS` keys, merged over renderer defaults (the shipped template overrides the gallery extension default):

| Key | Default | Meaning |
| --- | ------- | ------- |
| `news_per_row` | 1 | Entries per row; drives entry/row spacers. |
| `news_teaser_limit_chars` / `news_teaser_limit_words` | 0 | Truncate the teaser text by characters or words (`getCleanSubString`, characters win when both are set). |
| `news_teaser_limit_ellipse` | `template_default['ellipse_sign']` | Ellipsis mark for truncation. |
| `files_template_list` / `files_template_detail` | `default` | File list template passed to the files content part renderer per mode. |
| `files_direct_download` | 0 | Forwarded to the files renderer. |
| `gallery_allowed_ext` | `jpg,jpeg,png,svg` | Image extensions eligible for the gallery. |
| `gallery_filecenter_info` | 1 | Use description/copyright from the file center; 0 parses the per-file caption lines instead. |

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[NEWS_TITLE]`/`{NEWS_TITLE}`, `[NEWS_SUBTITLE]`/`{NEWS_SUBTITLE}`, `[NEWS_TOPIC]`/`{NEWS_TOPIC}` | Record title, subtitle and internal name (`cnt_title`, `cnt_subtitle`, `cnt_name`). |
| `[NEWS_TEASER]`/`{NEWS_TEASER}` | Teaser text (`cnt_teasertext`), format per record setting: plain text default (HTML-encoded with breaks preserved by `plaintext_htmlencode`), also supports `br`, `markdown`, `textile`, raw HTML — each HTML-safe before output. |
| `[NEWS_TEXT]`/`{NEWS_TEXT}` | Body text (`cnt_text`), output as stored. |
| `[NEWS_TAGS]`/`{NEWS_TAGS}` | The record's tag string (`cnt_object['cnt_category']`). |
| `[AUTHOR]`/`{AUTHOR}`, `[PLACE]`/`{PLACE}` | `cnt_editor`, `cnt_place`. |
| `[PRIO]`/`{PRIO}` | `cnt_prio`. |
| `[FIRST]` / `[LAST]` | Present on the first and last entry of the listing. |
| `[IS_ARCHIVE]` / `[IS_ARCHIVE_ELSE]` | Present when the kill date has passed (detail: archive/live wrapper classes). |
| `[NEWS_DETAIL_LINK]`/`{NEWS_DETAIL_LINK}` | Detail URL `base?newsdetail=YYYYMMDD-<aid>_<alias|id>`; only filled when the record allows read-more (`cnt_object['cnt_readmore']`). |
| `[NEWS_LIST_LINK]`/`{NEWS_LIST_LINK}` | Back link to the list URL (detail mode only). |
| `{ID}` | Record ID (`cnt_id`), e.g. for `id="news-{ID}"`. |
| `[IMAGE]`/`{IMAGE}`, `{IMAGE_ID}` `{IMAGE_EXT}` `{IMAGE_HASH}` `{IMAGE_WIDTH}` `{IMAGE_HEIGHT}` | Record image (`cnt_object['cnt_image']['id']`); `{IMAGE}` is the image name, width/height resolve from the file storage, hash/ext from the file record. |
| `[ZOOM]`/`[ZOOM_ELSE]` | Present when detail zoom is set for the record image. |
| `{LIGHTBOX}` | `rel="lightbox"` plus gallery attributes; initializes slimbox when present. |
| `[CAPTION]` `{CAPTION}`, `[COPYRIGHT]` `{COPYRIGHT}`, `[ALT]` `{ALT}`, `[TITLE]` `{TITLE}`, `[CAPTION_LINK]` `{CAPTION_LINK}`, `{CAPTION_TARGET}`, `[LIGHTBOX_CAPTION]` `{LIGHTBOX_CAPTION}`, `[LIGHTBOX_COPYRIGHT]` `{LIGHTBOX_COPYRIGHT}` | Image caption parts from `cnt_object['cnt_image']['caption']`. |
| `[IMAGE_URL]`/`{IMAGE_URL}` `{IMAGE_URL_TARGET}` | Image link from `cnt_object['cnt_image']['link']` with target. |
| `[URL]` `{URL}`, `{URL_TARGET}`, `[URL_TEXT]`/`{URL_TEXT}` | Record link (`cnt_object['cnt_link']` — numeric values become an `aid=` URL) and link text (`cnt_object['cnt_linktext']`). |
| `{DATE:… lang=EN}` `{LIVEDATE:…}` `{KILLDATE:…}` `{SORTDATE:…}` | Change date (`cnt_changed`), live date, kill date, sort date. |
| `[GALLERY]`/`{GALLERY}` with `{IMAGE_HASH}.{IMAGE_EXT}` `{IMAGE_ID}` `{IMAGE_NAME}` `{IMAGE_WIDTH}` `{IMAGE_HEIGHT}` `[CAPTION]` `[COPYRIGHT]` `[FIRST]` | Image gallery from the record's file list when gallery mode is on and the files are public images. |
| `[FILES]`/`{FILES}` | File list rendered through the files content part renderer (`cnt7.article.inc.php`) in a news context, using the settings' list/detail template. |
| `[PAGINATE]`/`[PAGINATE_ELSE]`, `{PAGE_PREV}`/`[PAGE_PREV]`/`[PAGE_PREV_ELSE]`, `{PAGE_NEXT}`/`[PAGE_NEXT]`/`[PAGE_NEXT_ELSE]`, `{PAGE_CURRENT}` `{PAGE_TOTAL}`, `[PAGE_PERIOD]` | Pagination: filled only when pagination is on and in list mode; `PAGE_PERIOD` shows the bucketed day/week/month/year value on date bases. |
| `[ATTR_CLASS]`/`{ATTR_CLASS}`, `[ATTR_ID]`/`{ATTR_ID}`, `[TITLE]` `{TITLE}`, `[SUBTITLE]` `{SUBTITLE}` | Content part wrapper class/id and title/subtitle; `[NEWS_ENTRIES]` holds the assembled entries. |

## Storage

- Content part: template file name in `acontent_template`, serialized array in `acontent_form` with the keys `news_lang`, `news_category`, `news_sort`, `news_paginate`, `news_prio`, `news_paginate_count`, `news_paginate_basis`, `news_limit`, `news_skip`, `news_archive`, `news_andor`, `news_archive_link`, `news_detail_link`.
- News records: `phpwcms_content` rows with `cnt_module='news'` — `cnt_title`, `cnt_subtitle`, `cnt_name`, `cnt_alias`, `cnt_teasertext`, `cnt_text`, `cnt_editor`, `cnt_place`, `cnt_livedate`, `cnt_killdate`, `cnt_sort`, `cnt_prio`, `cnt_lang`, `cnt_status`, `cnt_archive_status`, `cnt_opengraph`, and the serialized `cnt_object` (image, files, link, text format, read-more, tags, search switch); tags additionally normalized in `phpwcms_categories` with `cat_type='news'`.

## Related

- Article teasers: [Teaser/link article (cp 8)](17-teaser-link-article.md)
- Searching news: [Search (cp 13)](20-search.md)