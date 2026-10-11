---
title: Teaser/link article
description: List selected or auto-collected articles as teasers with summary, image and sorting options.
sidebar_position: 17
---
# Teaser/link article (content part 8)

> Source: `include/inc_lib/content/cnt8.readform.inc.php`, `include/inc_tmpl/content/cnt8.inc.php`, `include/inc_front/content/cnt8.article.inc.php`, `template/inc_default/teaser.tmpl`, `template/inc_default/list.teaser.tmpl`

Lists articles chosen one by one or collected automatically by sorting rule, structure level and keyword tags, each rendered from a teaser template. The same renderer also serves the news-style listing of any article selection.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default `<ul><li>`)* | File from `template/inc_cntpart/teaser/`. Files with a `list.` prefix are hidden here; they apply only in list mode (see Rendering). |
| Unique teaser entry | checkbox | off | Skip articles that an earlier teaser content part on the same page has already output. |
| Columns | text (3 chars) | *(empty)* | Teasers per row. The template's `TEASER_COLUMN_OVERWRITE` value wins when it holds an unsigned integer. |
| Check against category alias | checkbox | off | When the article sits in a structure level containing exactly one article, link to that level's URL instead of the article. |
| Wordlimit | text (5 chars) | *(empty)* | Truncate the summary. A negative value counts characters instead of words. |
| No summary | checkbox | off | Strip the summary from all entries. |
| Allowed tags | text | `<b><i><u><s><strong>` | HTML tags kept when `SUMMARY` is stripped; empty removes all tags. |
| Max width | text (4 chars, px) | *(empty)* | Overrides the list image width. |
| Max height | text (4 chars, px) | *(empty)* | Overrides the list image height. |
| Crop image | switch | off | Crop the list image to the given size instead of scaling. |
| Sorting | select | Manual | One of the values in the sorting table below. Choosing a non-manual value shows the auto fields (Max articles, Site level) and hides the manual selection lists. |
| Use prio | checkbox | off | Prepend `article_priorize DESC` to the chosen sort order. |
| Selection | two multi-select lists | — | Manual mode only: move articles between the selected list (`calink[]`, reordable with sort up/down buttons and removable) and the available list (`calinklist`). Both list non-hidden, non-deleted articles. |
| Filter | select + checkbox | — | Manual mode only: restrict the available list to a structure level, optionally also to articles carrying the CP's tags (applied via backend session). |
| Max articles | text (5 chars) | *(empty)* | Auto mode only: `LIMIT` for the collection query. |
| Site level | multi-select | *(none)* | Auto mode only: `calink_level[]`; source structure levels including Index (0). |
| Tags | text with tag autosuggest (categories) | *(empty)* | Keyword tags from `phpwcms_categories` (`cat_type='article'`); combined with the mode select below. |
| And/or | select | OR | `OR`, `AND`, `NOT`, `NOR` for the tag comparison. |

### Sorting values

Sorting applies to the auto collection; value 0 (manual) uses the hand-picked order instead.

| Value | Meaning |
| ----- | ------- |
| 0 | Manual selection, order as listed |
| 1 / 2 | Create date (`article_created`) DESC / ASC |
| 3 / 4 | Change date (`article_tstamp`) DESC / ASC |
| 5 / 6 | Live date (`article_begin`) DESC / ASC |
| 7 / 8 | Kill date (`article_end`) DESC / ASC |
| 9 | Random |
| 10–17 | Random, then create / change / live / kill date DESC / ASC |
| 18 / 19 | Article title (`article_title`) DESC / ASC |
| 20 / 21 | Random, then title DESC / ASC |
| 22 / 23 | Keywords/tags (`article_keyword`) DESC / ASC |
| 24 / 25 | Article sort (`article_sort`) DESC / ASC |
| 26 / 27 | Random, then article sort DESC / ASC |

For the random-plus-sort values (10–21, 26–27) the query runs twice as `(SELECT … ORDER BY RAND()) UNION DISTINCT (SELECT …)` deduplicated, then re-sorted by the chosen column and limited.

## Rendering

### Selection and query

- Both modes request `article_aktiv=1 AND article_deleted=0 AND article_noteaser=0`; published-visibility window (`article_begin`/`article_end`) applies unless the page runs in preview mode.
- Manual mode queries the stored article IDs (minus unique-teaser skips); auto mode queries by structure level (`article_cid IN …`) and, with tags set, LEFT JOINs `phpwcms_categories` with `cat_type='article'`.
- Tag modes against the selected tags: `OR` keeps articles carrying at least one tag, `AND` only those carrying every tag (post-filtered by matched count), `NOT` excludes articles carrying all tags, `NOR` keeps those carrying none.
- A frontend edit link is prepended per entry when `FE_EDIT_LINK` is enabled and the backend user is admin or owns the article.

### Template selection

Renderer order: in list mode (rendered through `{SHOW_CONTENT}`, flag `acontent_template_listmode`) the `list.<name>` variant inside `template/inc_cntpart/teaser/`, else the selected template; then `template/inc_default/list.teaser.tmpl` in list mode, else `template/inc_default/teaser.tmpl`; otherwise the built-in skeleton:

```html
<div>…<ul class="article-link-internal">…<li><a href="{ARTICLELINK}">{TITLE}</a></li>…</ul>…</div>
```

### Template sections

| Section | Meaning |
| ------- | ------- |
| `TEASER_HEAD` | Top of the block; wraps `TEASER` and carries `[TITLE]`/`[SUBTITLE]`. `{LINK_ARTICLE_CLASS}` resolves to the standard article link list class (`template_default['article']['link_article_class']`). |
| `TEASER_ENTRY` | Repeated per article. |
| `TEASER_ENTRY_SPACER` | Inserted between entries (columns > 1) or between all entries without columns. |
| `TEASER_ROW_SPACER` | Inserted once when a new row starts. |
| `TEASER_FOOTER` | Bottom of the block. |
| `TEASER_COLUMN_OVERWRITE` | Plain untagged value between the two markers: unsigned integer overrides Columns; `0` disables columns for this template. |
| `[TEASER]` block | Removed entirely when no article qualified. |

### Template tags

| Tag | Meaning |
| --- | ------- |
| `{ARTICLEID}` `{CATEGORYID}` `{COLUMN}` `{ROW}` | Numeric positions: article ID, structure level ID, 1-based column, current row. |
| `[MENUTITLE]`/`{MENUTITLE}`, `[MENUTITLE_ELSE]` | Article menu title with `{TITLE}` as the usual `ELSE` fallback. |
| `[TITLE]`/`{TITLE}` | Article title, HTML-escaped. |
| `[SUBTITLE]`/`{SUBTITLE}` | Article subtitle. |
| `{DATE:d/m/y lang=EN}` `{LIVEDATE:…}` `{KILLDATE:…}` | Standard date format tags. `{DATE}` shows the article change date, or the create date when sorting by create date; live/kill dates render through their own tags regardless of sorting. |
| `[PRIO]`/`{PRIO}` | `article_priorize` value. |
| `[ACTIVE]` | Holds `active` when the teasered article is the one currently displayed. |
| `[CLASS]`/`{CLASS}` | Article class from keyword CSS markers (`*CSS-myclass*`), merged with the article's meta class. |
| `[CATEGORY]`/`{CATEGORY}` | Structure level name of the article. |
| `[ARTICLELINK]`/`{ARTICLELINK}`, `{ARTICLELINK_TARGET}` | Detail URL and link target, filled only when the article's more-link option is set; with an article redirect, the redirect URL and target. Without it the block is stripped. |
| `[IMAGE]`/`{IMAGE}` | List image `<img>` built by the cached-image mechanism, with `data-image-id`/`data-image-hash`, alt and title; empty without an article image. If the template contains `cmsimage.php`, `{IMAGE}` is the raw file hash instead, for `img/cmsimage.php/<w>x<h>[x<crop>]/<hash>.<ext>` URLs. |
| `{THUMB_NAME}` `{THUMB_REL}` `{THUMB_ABS}` `{THUMB_WIDTH}` `{THUMB_HEIGHT}` `{THUMB_ID}` `{THUMB_HASH}` `{THUMB_CROP}` `{THUMB_EXT}` `{IMAGE_NAME}` | List image metadata: generated file name, relative and absolute URL, pixel size, file ID/hash, crop flag, extension, original name. |
| `[CAPTION_SUPPRESS]`, `[CAPTION]`/`{CAPTION}`, `{CAPTION_ALT}`, `{CAPTION_TITLE}`, `{CAPTION_URL}` | List image caption, alt, title and link; `[CAPTION_SUPPRESS]` stays when the article asked for caption suppression. |
| `[IMAGE_DETAIL]`/`{IMAGE_DETAIL}`, `{IMAGE_DETAIL_ID}` `{IMAGE_DETAIL_HASH}` `{IMAGE_DETAIL_EXT}` `{IMAGE_DETAIL_NAME}` | Article detail image (not the list image) rendered as `<img>` with `data-detail-id`/`data-detail-hash`, plus its metadata. |
| `[CAPTION_DETAIL_SUPPRESS]`, `[CAPTION_DETAIL]`/`{CAPTION_DETAIL}`, `{IMAGE_TITLE}`, `{ALT}`, `{URL}`, `{URL_TARGET}`, `{COPYRIGHT}` | Detail image caption parts. |
| `[SUMMARY_RAW]`/`{SUMMARY_RAW}` | Untruncated summary as stored (`strip_tags` not applied), stripped when No summary is set. |
| `[SUMMARY]`/`{SUMMARY}` | Summary stripped to allowed tags, truncated to Wordlimit (words, or characters for negative values; the article's list-image wordlimit applies when the CP leaves it empty), ellipsis from `template_default['ellipse_sign']`. |
| `[ATTR_CLASS]`/`{ATTR_CLASS}` `[ATTR_ID]`/`{ATTR_ID}` `[ATTR_CLASS_ELSE]` | Content part wrapper class and `id` (`acontent_attr_class`/`acontent_attr_id`). |
| `{ID}` | Content part record ID (`acontent_id`). |
| `[TITLE]`/`[SUBTITLE]` at block level | Content part title and subtitle. |

The list image comes from the article's summary image: when the articlesummary list-image option is off or unset, the summary image values are used (`setArticleSummaryImageData()` in `include/inc_front/front.func.inc.php`).

## Storage

Serialized array `alink` in `phpwcms_articlecontent.acontent_form` with the keys `alink_template`, `alink_allowedtags`, `alink_id` (array of article IDs), `alink_level`, `alink_type`, `alink_wordlimit`, `alink_hidesummary`, `alink_columns`, `alink_categoryalias`, `alink_max`, `alink_width`, `alink_height`, `alink_zoom` (legacy, not evaluated), `alink_unique`, `alink_crop`, `alink_prio`, `alink_andor`, `alink_category`. Older records keep their article IDs as `id:id:id` string in `phpwcms_articlecontent.acontent_alink`; the renderer falls back to it when the serialized array carries no `alink_id`.

## Related

- Other article listers: [Article menu (cp 15)](18-article-menu.md), [News (cp 33)](19-news.md)
- List image source: article summary options, see [Text w/image (cp 1)](01-text-with-image.md)