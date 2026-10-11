---
title: Search
description: Render a frontend search form and ranked results over articles, news records and module data.
sidebar_position: 20
---
# Search (content part 13)

> Source: `include/inc_lib/content/cnt13.readform.inc.php`, `include/inc_tmpl/content/cnt13.inc.php`, `include/inc_front/content/cnt13.article.inc.php`, `include/inc_front/content/cnt_functions/cnt13.func.inc.php`, `template/inc_default/search.tmpl`

Searches article content plus optional news records and frontend-searchable modules, ranks matches by occurrence count and paginates the result list. The same content part renders the search form and the result listing.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/search/`. Empty uses `template/inc_default/search.tmpl`. |
| Results per page | text (5 chars) | 25 at runtime when empty | `result_per_page`; a negative value means "all" (`-1` is treated as 100000). |
| Open in new window | checkbox | off | `newwin`: result links get `target="_blank"`. |
| Wordlimit | text (5 chars) | 35 words at runtime when empty | Truncation of each result summary; a negative value counts characters. |
| Highlight | checkbox | off | `highlight_result`: wrap hits and attach `highlight=<words>` to result URLs. |
| Min char | text (5 chars) | 3 | `minchar`: minimum word length; words shorter than this are not highlighted (saved as 3 when empty). |
| Hide summary | checkbox | off | `hide_summary`: drop the `[TEXT]` summary from results. |
| Start at | multi-select | *(empty = all)* | `start_at`: restrict the search to selected structure levels (Index = 0). |
| Default type | select | OR | `type`: `AND` requires every search word to occur. |
| Not search | checkboxes — file names / user name / caption / keywords | off (all searched) | `no_filenames`, `no_username`, `no_caption`, `no_keyword`: exclude the corresponding data from the search corpus. |
| News | checkbox | off | `search_news`: also search news records. |
| Language | text with autosuggest | *(empty = all)* | `news_lang`: comma list restricting news search languages. |
| Tags | text with autosuggest + select | *(empty = all), OR | `news_category` and `news_andor` (`OR`/`AND`/`NOT`) against news tags. |
| Target | text (article alias / `aid=1` / `id=3`) + article browser | *(empty)* | `news_url`: article that hosts the news content part opening news details. |
| Modules | checkboxes per module | off | `module`: include modules providing `frontend.search.php`. |
| Input label / CSS class | text | *(empty)* | `label_input` / `style_input` for the form's input cell/field. |
| Button text / CSS class | text | *(empty = backend word for Search)* | `label_button` / `style_button`. |
| Result label / CSS class | text | *(empty)* | `label_result` / `style_result`: stored, not evaluated by the current renderer. |
| Page of pages | 5 checkboxes | on (except where noted) | `show_always` show pagination on all pages, otherwise only with more than one page; `show_top`/`show_bottom` place it above/below; `show_prev`/`show_next` render disabled prev/next links instead of nothing. |
| Page-of-pages template | textarea | *(empty; built-in default)* | `label_pages`: custom pagination line; empty uses the template's `SEARCH_PAGINATE` section. |
| Align | radio | left | `align`: alignment of the built-in table form (left/right/center). |
| Text/HTML | radio | Text | `text_html`: intro/result/no-result texts output HTML or as plain text with HTML encoding. |
| Intro text | textarea | *(empty)* | `text_intro` shown before any search. |
| Result text | textarea | *(empty)* | `text_result` on successful search. |
| No result text | textarea | *(empty)* | `text_noresult` when nothing matched. |

## Rendering

### Request handling

- The form posts `search_input_field`; the renderer immediately redirects to a `?searchwords=<words>&searchstart=1` URL so the browser history stays clean. Results are always driven by the GET parameter `searchwords`, the page by `searchstart`.
- Words are split at spaces, cleaned of replacement tags and entities, deduplicated; from every word of at least `minchar` length the highlight list is built, with `?` and `*` usable as wildcards.

### Search corpus

- Articles: title, subtitle, summary, keywords, user name, plus the text content of visible content parts of types 0, 1, 2, 4, 5, 6, 7, 11, 14, 26, 27, 29, 100, 31, 32. With the "search" switches enabled, file names (file lists and image parts), image captions and file-list captions and the recipe's preparation/ingredients fields are included; alias articles (`article_aliasid`) fall back to the referenced article's data unless the alias keeps its own header data.
- News records (`cnt_module='news'`): text, teaser text, place, subtitle, title, optional editor, tags, image and file captions; a record with `cnt_object['cnt_searchoff']` set is skipped. Result detail links use the configured Target article or an absolute URL containing `___NEWSDETAIL___`/`___HIGHLIGHT___`.
- Modules: `frontend.search.php` of every enabled frontend-searchable module appends its results to the same list.
- Excluded from search: articles flagged "no search" (`article_nosearch`), structure levels flagged `acat_nosearch`, registration-only levels for guests, and expired content part records.

### Ranking and result list

- Each match is ranked by the total number of occurrences; with `type=AND` only results containing every distinct word survive; the list is sorted descending by rank.
- Every result item fills the `SEARCH_ITEM` section with `{LINK}` (target URL; with Highlight enabled the link embeds `highlight=<encoded words>`), `{LINK_TARGET}`, `{RANKING}`, `[TITLE]`, `[SUBTITLE]`, `[TEXT]` (truncated summary, HTML-encoded, highlighted) and, when the item template contains `{IMAGE…}` and the article has a list image, `{IMAGE_HASH}` `{IMAGE_ID}` `{IMAGE_NAME}` `{IMAGE_EXT}` inside `[IMAGE]` (the `{PHPWCMS_RESIZE_IMAGE}` prefix builds thumbnails).
- Results are paginated over the ranked list in PHP: the page from `searchstart` slices `result_per_page` items.

### Template sections and tags

| Section / tag | Meaning |
| ------------- | ------- |
| `SEARCH_HEADER` / `SEARCH_FOOTER` | Wrapper; also hold `[RESULTS]` and the `[TEXT]` status line, `[ATTR_CLASS]`/`[ATTR_ID]`, `[TITLE]`, `[SUBTITLE]`. |
| `SEARCH_ITEM` | Per-result markup. |
| `SEARCH_ITEM_SPACER` | Between result items. |
| `SEARCH_PAGINATE` | Pagination markup, overridden by the backend textarea when filled. |
| `{FORM}` / `[SEARCH_INPUT_LABEL]` | When the template contains a literal `{FORM}`, the built-in table form (aligned per `align`) replaces it; otherwise the modern form inside `[FORM]` is used with `{SEARCH_ACTION}`, `{SEARCH_INPUT}` (the input's name and ID `search_input_field`), `{SEARCH_BUTTON}`, `{SEARCH_VALUE}`, plus the optional label `[SEARCH_INPUT_LABEL]`/`{SEARCH_INPUT_LABEL}`. |
| `[RESULTS]` / `{RESULTS}` | The joined result items; empty when nothing matched. |
| `[PAGINATE_TOP]` / `[PAGINATE_BOTTOM]` | Rendered pagination (same markup both places) per the show switches. |
| `{TEXT}` | Intro, result, or no-result text depending on state. |
| `[TITLE]` / `[SUBTITLE]` | Content part title/subtitle. |

### Pagination markup

Built-in default: `[PREV]{PREV:&laquo; Previous} | [/PREV]Page #/##[NEXT] | {NEXT:Next &raquo;}[/NEXT]`.

| Token | Meaning |
| ----- | ------- |
| `{PREV:…}` / `{NEXT:…}` | Link text, replaced by a link to the previous/next page (or a disabled link per the show switches). |
| `#` | Current page. |
| `##` | Total pages. |
| `###` | First result number on the page. |
| `####` | Last result number on the page. |
| `#####` | Total result count. |
| `{NAVI:123}` | Page number links 1…N. |
| `{NAVI:1-3}` | Result-range links like `1–25`. Both accept `,separator|link prefix|link suffix` options, e.g. `{NAVI:1-3, &middot;|<li>|</li>}`. |

Link classes come from `template_default['classes']`: `search-paginate-link`, `search-paginate-link-active`, `search-paginate-link-disabled`; custom pagination output is wrapped in `<div class="…search-nextprev">`.

## Storage

Serialized array `search` in `phpwcms_articlecontent.acontent_form` with the keys `result_per_page`, `wordlimit`, `newwin`, `highlight_result`, `label_input`, `style_input`, `label_button`, `style_button`, `label_result` (legacy, unused), `style_result` (legacy, unused), `align`, `text_intro`, `text_result`, `text_noresult`, `template` (legacy duplicate of `acontent_template`), `text_html`, `label_pages`, `minchar`, `start_at`, `show_always`, `show_top`, `show_bottom`, `show_next`, `show_prev`, `module`, `search_news`, `news_lang`, `news_category`, `news_andor`, `news_url`, `no_filenames`, `no_username`, `no_caption`, `no_keyword`, `hide_summary`, `type`. The template file name is additionally stored in `acontent_template`.

## Related

- News records searched here: [News (cp 33)](19-news.md)
- Article listing content parts: [Teaser/link article (cp 8)](17-teaser-link-article.md), [Article menu (cp 15)](18-article-menu.md)