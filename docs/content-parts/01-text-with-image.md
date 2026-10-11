---
title: Text w/image
description: Insert text with one image, choosing position, size, caption and zoom behavior.
sidebar_position: 1
---
# Text w/image (content part 1)

> Source: `include/inc_lib/content/cnt1.readform.inc.php`, `include/inc_front/content/cnt1.article.inc.php`, `include/inc_tmpl/content/cnt1.inc.php`

The basic building block for editorial pages: a WYSIWYG text plus one image, arranged in one of ten predefined positions. With no image selected it renders as plain rich text.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/imagetext/`. Empty uses the shipped default. |
| Text | WYSIWYG / HTML | — | Main content. Edits with the configured rich text editor or source mode, per user preference. |
| Image | file select + delete | none | Image from the file center via image browser. Stored as file ID. |
| Position | icon group | 0 | One of 0–9: 0 top left, 1 top center, 2 top right, 3 bottom left, 4 bottom center, 5 bottom right, 6 float left, 7 float right, 8 column left, 9 column right. |
| Max width | number | *`image_default_width`* or content width | Image width in px. Capped at `content_width` (positions 6/7: `content_width / 1.75`) when `RESPONSIVE_MODE` is off. |
| Max height | number | *`image_default_height`* | Image height in px. Both 0 = proportional fit. |
| Crop | switch | off | Crop the image to the given size instead of proportional scaling. |
| Zoom | switch | off | Wrap image in a link to the original; requires an enabled lightbox on the page. |
| Lightbox | switch | off | Force the slimbox/lightbox JS; implies Zoom. |
| No caption | switch | off | Suppress caption output in rendering. |
| Caption title | textarea | — | First caption paragraph. |
| Description (alt) | textarea | — | Used as `alt` attribute and second caption line. |
| Link | text | — | URL wrapped around the caption. |
| Target | select | *(blank)* | `_blank`, `_top`, `_self`, `_parent` for the caption link. |

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/imagetext/<name>.tmpl`, else `template/inc_default/imagetext.tmpl`, else this built-in skeleton:

```html
<div class="image-with-text">{IMAGETEXT}</div>
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[IMAGETEXT]…[/IMAGETEXT]` | Block holding the combined image + text; removed when the part is not rendered as image+text column pair. |
| `{IMAGETEXT}` | The combined output (or plain text without an image). |
| `{ATTR_CLASS}` / `[ATTR_CLASS]…[/ATTR_CLASS]` | Content part wrapper class (`acontent_attr_class`), `[ATTR_CLASS_ELSE]` fallback branch. |
| `{ATTR_ID}` / `[ATTR_ID]…[/ATTR_ID]` | Wrapper `id`. |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[TOP_LEFT]` `[TOP_CENTER]` `[TOP_RIGHT]` `[BOTTOM_LEFT]` `[BOTTOM_CENTER]` `[BOTTOM_RIGHT]` `[FLOAT_LEFT]` `[FLOAT_RIGHT]` `[COLUMN_LEFT]` `[COLUMN_RIGHT]` | Conditional blocks; the active position block stays, all others are stripped. |
| `[ZOOM]` | Present when zoom is active. |
| `{IMAGE}` | Full `<img>` tag pointing to `/img/resize.php`-style URL: `/img/<width>x<height>x<crop>/<hash>.<ext>/<name>`, `alt`, optional `title`, lazy loading attribute included. |
| `{IMAGE_ID}` `{IMAGE_HASH}` `{IMAGE_NAME}` `{IMAGE_EXT}` | Raw image properties. Without an image: `empty-image-<cp id>`, empty hash, name `empty`, empty ext. |
| `{IMAGE_WIDTH}` `{IMAGE_HEIGHT}` | Effective pixel size. |
| `[CAPTION]` `[ALT]` `[IMAGE_TITLE]` `[COPYRIGHT]` | Caption parts; stripped when "No caption" is set. |
| `{CLASS}` | CSS class assigned to the rendered position (see settings). |
| `{ID}` | Content part record ID. |
| `{TEXT}` | The raw text field. |

### Position classes

Each position prints a class that you define per template via the `IMAGETEXT_SETTINGS` section (INI style). Defaults map to `template_default['classes']…`:

```ini
[IMAGETEXT_SETTINGS]
class_top_left     = imgtxt-top-left
class_top_center   = imgtxt-top-center
class_top_right    = imgtxt-top-right
class_bottom_left  = imgtxt-bottom-left
class_bottom_center= imgtxt-bottom-center
class_bottom_right = imgtxt-bottom-right
class_float_left   = imgtxt-left
class_float_right  = imgtxt-right
class_column_left   = imgtxt-column-left
class_column_right  = imgtxt-column-right
class_column_left_image  = imgtxt-column-left-image
class_column_left_text   = imgtxt-column-left-text
class_column_right_image = imgtxt-column-right-image
class_column_right_text  = imgtxt-column-right-text
width     = 0
height    = 0
zoom      = 0
lightbox  = 0
nocaption = 0
crop      = 0
```

Settings values win over per-part backend entries (`width`/`height` from settings replace the stored ones; `lightbox` implies `zoom`; `nocaption` and `crop` act as backend overrides). Column positions (8/9) emit a wrapper `div` containing `imagediv()` output plus `<div class="<class_column_left_text>">{text}</div>`, skipping the whole block when both rendered empty.

> **Note:** Positions 6/7 float: the width is additionally capped at `content_width / 1.75` when `RESPONSIVE_MODE` is off.

## Storage

- Text: `phpwcms_articlecontent.acontent_text`
- Template: `acontent_template`
- Caption parts joined as `title|alt|url| target`, base64 in `acontent_image` field segment 6
- Image string in `acontent_image`: `id:name:hash:ext:width:height:caption(b64):position:zoom`
- Lightbox / nocaption / crop flags: serialized in `acontent_form`

## Related

- Images-only variants: [Images (cp 2)](09-images.md), [Images &lt;div&gt; (cp 29)](10-images-div.md), [Images special (cp 31)](11-images-special.md)
- Image caption model: file system table `phpwcms_file`