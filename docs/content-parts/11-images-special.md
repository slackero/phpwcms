---
title: Images special
description: Gallery with separate thumbnail and zoom image, info text and per-image tabs.
sidebar_position: 11
---
# Images special (content part 31)

> Source: `include/inc_lib/content/cnt31.readform.inc.php`, `include/inc_lib/content/cnt31.sql.inc.php`, `include/inc_lib/content/cnt31.takeval.inc.php`, `include/inc_tmpl/content/cnt31.inc.php`, `include/inc_front/content/cnt31.article.inc.php`

A gallery where every entry is an accordion tab with its own thumbnail image, zoom image, caption, info text, URL and custom fields. Thumbnail and zoom version are picked independently, so the small view and the enlarged view can be different files. Renders through the same section-based template scheme as [Images &lt;div&gt;](10-images-div.md) but with its own template folder and tag set.

## Backend form

Global settings, top to bottom:

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(default)* | Template file from `template/inc_cntpart/imagespecial/` (files starting with `list.` are hidden). Empty uses `template/inc_default/imagespecial.tmpl`. |
| Image alignment | select | Do not center | 0 none, 1 center h/v, 2 center horizontal, 3 center vertical; disabled without thumbnail size. |
| Column / Image space | select 1–25 / number px | 1 / 0 (`imagegallery_default_*`) | Images per row and `{SPACE}` value. |
| Thumbnail: Width / Height / Crop | number px, switch | `imagegallery_default_width` (490) / `imagegallery_default_height` (370) / off | Thumbnail size and crop. |
| Zoom: Width / Height / Crop image | number px, switch | `img_prev_width` (978) / `img_prev_height` (734) / off | Size of the enlarged image and crop switch. |
| Click enlarge | switch | off | Enable the zoom link. |
| Gallery image | switch | off | Group images for the lightbox (`rel="lightbox[…]"`); implies Click enlarge. |
| Hide caption for thumbnails | switch | off | Suppress caption output. |
| Effect 1 / 2 / 3 | switches | off | Fill the `[EFFECT_1]` / `[EFFECT_2]` / `[EFFECT_3]` conditional template blocks. |

Per-image entries (sortable accordion, add top/bottom, per-entry on/off toggle):

| Field | Type | Description |
| ----- | ---- | ----------- |
| Thumbnail | file select | Image shown small. An entry without one is skipped in rendering. |
| Zoomed view | file select | Image shown enlarged; falls back to the thumbnail when empty. |
| Caption | textarea | Pipe format `caption \| alt \| link target \| title \| copyright`; missing values fall back to the file center metadata of the thumbnail file. |
| Info text | textarea | Per-image text, rendered at `[INFOTEXT]` with the mode from `text_render`. |
| URL | text | Rendered at `[URL]` / `[URL_TARGET]`; second word is the target. |
| Custom fields | per field group | Fields from `imagespecial_custom_fields` in the article layout template, bound to the selected template; same types as [Images &lt;div&gt;](10-images-div.md#backend-form). |
| Active | toggle | Off-entries stay editable but are not rendered. |

A WYSIWYG HTML editor (field `image_html`) sits below the entry list.

## Rendering

### Template selection

List mode: `inc_default/list.imagespecial.tmpl` or `inc_cntpart/imagespecial/list.<selected>`; article page: `inc_default/imagespecial.tmpl` or `inc_cntpart/imagespecial/<selected>`. Sections and INI handling are identical to [Images &lt;div&gt;](10-images-div.md#rendering) (`IMAGE_SETTINGS`, `IMAGES_HEADER`, `IMAGES_ENTRY`, `IMAGES_ENTRY_SPACER`, `IMAGES_ROW_SPACER`, `IMAGES_FOOTER`; content outside sections is ignored).

### IMAGE_SETTINGS

Same keys as Images &lt;div&gt;, plus the special variants:

```ini
;width_zoom     = 600   ; zoom image width
;height_zoom    = 600   ; zoom image height
;center         = 2     ; 0 none, 1 h/v, 2 horizontal, 3 vertical
;crop_zoom      = 0     ; crop the zoom image
;fx1 / fx2 / fx3 = 0    ; effect switches
;text_render = plain    ; info text mode: plain, text, html, markdown, textile
```

`text_render` defaults to `plain` (HTML-encoded, line breaks kept as entered).

### Template tags

Entry section, per image:

| Tag | Meaning |
| --- | ------- |
| `{IMAGE}` | The complete `<img>` plus, when linked, the wrapping `<a>`. |
| `{IMGID}` / `{ROW_COUNT}` | List index of the entry (0-based). |
| `{IMGNAME}` | File name of the thumbnail entry. |
| `{ENTRY_ID}` / `{ENTRY_NUM}` | 0-based / 1-based rendered position. |
| `{THUMB_ID}` `{THUMB_HASH}` `{THUMB_EXT}` `{THUMB_FILENAME}` `{THUMB_NAME}` `{THUMB_REL}` `{THUMB_ABS}` `{THUMB_WIDTH}` `{THUMB_HEIGHT}` | Thumbnail file data and resized name. |
| `{IMAGE_ID}` `{IMAGE_HASH}` `{IMAGE_EXT}` `{IMAGE_FILENAME}` `{IMAGE_NAME}` `{IMAGE_REL}` `{IMAGE_ABS}` `{IMAGE_WIDTH}` `{IMAGE_HEIGHT}` | Zoom image data; empty without zoom. |
| `[ZOOM]…[/ZOOM]` | Present when the image is enlarged. |
| `{LIGHTBOX}` | `rel="lightbox[…]"` attribute string when Gallery image is on; empty otherwise. |
| `{LIGHTBOX_CAPTION}` | `title="…"` attribute for the lightbox link. |
| `[CAPTION]` / `[CAPTION_ELSE]`, `[COPYRIGHT]`, `[TITLE]`, `[ALT]` | Caption parts, as in Images &lt;div&gt;. |
| `[LINK]…[/LINK]` | The wrapping link markup around the image. |
| `{IMAGE_URL}` / `{IMAGE_TARGET}` | Caption link URL and raw target. |
| `[INFOTEXT]…[/INFOTEXT]` | Info text in the `text_render` mode. |
| `[URL]…[/URL]` / `[URL_TARGET]…[/URL_TARGET]` | Website URL field of the entry. |
| `[FIRST]` / `[LAST]` / `[IMAGE_FIRST]` / `[IMAGE_LAST]` / `[ROW]` | Row and set position markers, values as in Images &lt;div&gt;. |
| `[EFFECT_1]` / `[EFFECT_2]` / `[EFFECT_3]` | Present when the matching effect switch is on. |
| `[IMGSPCL_<FIELD>…]` | Custom field values (uppercase key), option-conditional variants included, `file` fields render the file list renderer. |

Centering emits inline `margin` styles that pad the thumbnail to the configured size.

The caption link segment (third pipe segment) links the image; the entry's own URL field only fills `[URL]` and does not wrap the image.

## Storage

- Whole CP: serialized in `phpwcms_articlecontent.acontent_form` (`width`, `height`, `width_zoom`, `height_zoom`, `col`, `space`, `zoom`, `lightbox`, `nocaption`, `center`, `crop`, `crop_zoom`, `fx1`–`fx3`, `fieldgroup`, `images` list with `thumb_id`, `zoom_id`, `thumb_name`, `zoom_name`, `sort`, `caption`, `freetext`, `url`, `active`, `thumb_hash`, `thumb_ext`, `zoom_hash`, `zoom_ext`, `custom_fields`)
- WYSIWYG HTML: `acontent_html` (fills `{TEXT}`)
- Template name: `acontent_template`

## Related

- Base gallery: [Images (cp 2)](09-images.md), [Images &lt;div&gt; (cp 29)](10-images-div.md)
- [Content part templates](../general/concepts.md#templates)