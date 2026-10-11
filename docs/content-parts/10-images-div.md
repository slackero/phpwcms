---
title: Images &lt;div&gt;
description: Render an image gallery from structured header, entry and footer template sections.
sidebar_position: 10
---
# Images &lt;div&gt; (content part 29)

> Source: `include/inc_lib/content/cnt29.readform.inc.php`, `include/inc_lib/content/cnt29.sql.inc.php`, `include/inc_lib/content/cnt29.takeval.inc.php`, `include/inc_tmpl/content/cnt29.inc.php`, `include/inc_front/content/cnt29.article.inc.php`

The structured successor of [Images (cp 2)](09-images.md): images render through free `IMAGES_HEADER` / `IMAGES_ENTRY` / `IMAGES_FOOTER` template sections producing `div` wrappers, which plain templates ship as simple grids and slider templates (Bootstrap 5 carousel, Slick, Splide) as sliders. Supports image centering, a WYSIWYG intro text and per-content-part custom fields defined in the article layout template.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(default)* | Template file from `template/inc_cntpart/images/` (files starting with `list.` are hidden). Empty uses `template/inc_default/images.tmpl`. |
| Image alignment | select | Do not center | 0 no centering, 1 center horizontal/vertical, 2 center horizontal, 3 center vertical. Disabled unless max width and max height are both set. |
| Max width / Max height | number px | `imagegallery_default_width` (490) / `imagegallery_default_height` (370) | Thumbnail size. |
| Crop thumbnail | switch | off | Crop to the given size; disabled unless both sizes are set. |
| Images max. | number | All | Limit the number of rendered images. |
| Select images randomly | switch | off | Shuffle the image list. |
| Column | select 1–25 | 1 (`imagegallery_default_column`) | Images per row; drives `[FIRST]`/`[LAST]` and the row structure. |
| Image space | number px | 0 (`imagegallery_default_space`) | Value of `{SPACE}`; spacing handled by the template (entry/row spacer sections). |
| Click enlarge | switch | off | Link each image to the enlarged image (`img_prev_width` × `img_prev_height`). |
| Gallery image | switch | off | Group images for the lightbox (`rel="lightbox[…]"`); implies Click enlarge. |
| Hide caption for thumbnails | switch | off | Removes `[CAPTION]` and `[CAPTION_ELSE]` output entirely. |
| Images | multi-select | — | File center images, sortable; select order is the render order. |
| Caption | textarea, one line per image | — | Line n describes image n. Format: `caption \| alt \| link target \| title \| copyright`; alt/title/copyright fall back to the file center values of the image. |
| Custom fields | per field group | — | Fields defined in the article layout template setting `imgdiv_custom_fields`; the group is bound to the selected template. Types: `str`, `textarea`, `option`, `select`, `int`, `float`, `bool`, `file`. |
| Text | WYSIWYG / HTML | — | Intro text, rendered at `{TEXT}` in the header section. |

There is no position selector; the renderer ignores the position value stored in `acontent_form`.

## Rendering

### Template selection

Order: when the article renders in list mode, `inc_default/list.images.tmpl` or `inc_cntpart/images/list.<selected>`, else `inc_default/images.tmpl` or `inc_cntpart/images/<selected>`. With no template selected and no default file present nothing renders (no built-in skeleton).

The template consists of INI-like `IMAGE_SETTINGS` plus comment-marked sections `<!--IMAGES_HEADER_START//-->…`, `IMAGES_ENTRY`, `IMAGES_ENTRY_SPACER`, `IMAGES_ROW_SPACER`, `IMAGES_FOOTER`. Content outside these sections is ignored.

### IMAGE_SETTINGS

```ini
; settings will overwrite CP settings
;width          = 100
;height         = 100
;col            = 1
;zoom           = 1
;crop           = 0
;space          = 10
;lightbox       = 1
;nocaption      = 0
;thumb_class    = image-responsive
;random         = 0
;limit          = 0
;hide_limited   = 0    ; 1 = images beyond limit stay in the DOM, hidden
;center_image   = 0    ; 0 none, 1 h/v, 2 horizontal, 3 vertical
;image_class_center_center     = image-center-center
;image_class_center_horizontal = image-center-horizontal
;image_class_center_vertical   = image-center-vertical
```

Centering adds `image-center-*` classes and inline `margin` styles that equalize the empty space around smaller thumbnails.

### Template tags

Entry section (`IMAGES_ENTRY`), filled per image:

| Tag | Meaning |
| --- | ------- |
| `{IMAGE}` | The complete `<img>` plus, when linked, the wrapping `<a>`. |
| `[LINK]…[/LINK]` | Same wrapping link markup, independent of the image. |
| `{IMGID}` | Zero-based index of the image in the list. |
| `{ROW_COUNT}` | Same value as `{IMGID}`. |
| `{ENTRY_ID}` / `{ENTRY_NUM}` | 0-based / 1-based position of the rendered image. |
| `[FIRST]` / `[LAST]` | Present on the first / last image of a row; filled with the column number. |
| `[IMAGE_FIRST]` / `[IMAGE_LAST]` | Present on the first / last image of the whole set. |
| `[ROW]…[/ROW]` | Filled with the row number (1-based). |
| `{IMGNAME}` | HTML-escaped file name. |
| `{THUMB_NAME}` `{THUMB_REL}` `{THUMB_ABS}` `{THUMB_WIDTH}` `{THUMB_HEIGHT}` `{THUMB_COLUMNS}` | Thumbnail info; `REL` is relative to the site root, `ABS` absolute. |
| `{IMAGE_ID}` `{IMAGE_HASH}` `{IMAGE_EXT}` | File ID, hash, extension of the current image. |
| `[ZOOM]…[/ZOOM]` | Present when the image is enlarged. `{IMAGE_NAME}` `{IMAGE_REL}` `{IMAGE_ABS}` `{IMAGE_WIDTH}` `{IMAGE_HEIGHT}` describe the enlarged image (empty without zoom). |
| `[CAPTION]…[/CAPTION]` / `[CAPTION_ELSE]…[/CAPTION_ELSE]` | Caption text; `_ELSE` branch renders on images without a caption text. Both blocks vanish when "Hide caption" is set. |
| `[COPYRIGHT]…[/COPYRIGHT]` | Copyright part of the caption line. |
| `[TITLE]…[/TITLE]` / `[ALT]…[/ALT]` | Caption title / alt segments. |
| `[URL]…[/URL]` / `[URL_TARGET]…[/URL_TARGET]` | Link URL and target from the caption link segment. |
| `[HIDDEN]…[/HIDDEN]` | Filled with `style="display:hidden"` when `hide_limited = 1` and the image is beyond the limit (the shipped default template writes `style="display:none"` into this block). |
| `[LANDSCAPE]…[/LANDSCAPE]` | Filled with the letter `L` for images wider than tall (raster images are measured live, SVG by stored size); both branches are stripped when the file is not readable. |

Header / footer section, filled once:

| Tag | Meaning |
| --- | ------- |
| `{ID}` | Content part record ID. |
| `{IMAGE_COUNT}` | Number of rendered images (after the limit). |
| `{THUMB_WIDTH_MAX}` / `{THUMB_HEIGHT_MAX}` | Largest rendered thumbnail dimensions. |
| `{SPACE}` | Stored image space value. |
| `[DATA]…{DATA}…[/DATA]` | `ENTRY_DATA` sections of all entries, concatenated. Slider templates put their indicator markup here. |
| `{ATTR_CLASS}` / `[ATTR_CLASS]…[/ATTR_CLASS]`, `{ATTR_ID}` / `[ATTR_ID]…[/ATTR_ID]` | Wrapper class / id with `[…_ELSE]` branches. |
| `[TITLE]` `{TITLE}`, `[SUBTITLE]` `{SUBTITLE}`, `[TEXT]` `{TEXT}` | Content part title, subtitle and raw WYSIWYG text. |
| `[IMAGE_CLASS_CENTER]…[/IMAGE_CLASS_CENTER]` | Filled with the centering class when centering is active. |
| `[IMGDIV_<FIELD>…]` | Custom field values (uppercase field key); `bool` renders a presence marker, `option`/`select` also fill `[IMGDIV_<FIELD>_<OPTION>]` per value, `file` renders the file list renderer (cnt 7), `render` keys `html`/`markdown`/`plain`/`wysiwyg` choose the output mode. |

`ENTRY_DATA` must be nested inside the `IMAGES_ENTRY` section: the renderer cuts it out of each rendered entry and collects the pieces for `{DATA}` in the footer.

## Storage

- Image list, options, custom fields: serialized in `phpwcms_articlecontent.acontent_form` (`images`, `width`, `height`, `col`, `space`, `zoom`, `crop`, `center_image`, `lightbox`, `nocaption`, `limit`, `random`, `fieldgroup`, `custom`)
- WYSIWYG intro text: `acontent_text`
- Template name: `acontent_template`

## Related

- Table-based classic: [Images (cp 2)](09-images.md)
- Image with per-image detail tabs: [Images special (cp 31)](11-images-special.md)
- [Content part templates](../general/concepts.md#templates)