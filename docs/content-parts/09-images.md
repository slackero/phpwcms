---
title: Images
description: Place a gallery of multiple images as table or div layout with captions, zoom and lightbox.
sidebar_position: 9
---
# Images (content part 2)

> Source: `include/inc_lib/content/cnt2.readform.inc.php`, `include/inc_lib/content/cnt2.sql.inc.php`, `include/inc_tmpl/content/cnt2.inc.php`, `include/inc_front/content/cnt2.article.inc.php`, `include/inc_front/img.func.inc.php` (`imagelisttable()`)

The classic image gallery content part: a list of images from the file center, arranged in columns and one of eight positions, each with its own single-line caption. Rendering goes through `imagelisttable()`, which emits either an HTML `<table>` or a `<div>` sequence depending on the Table switch. Despite its `imagetable` template names this is the "Images" content part; [Images &lt;div&gt;](10-images-div.md) and [Images special](11-images-special.md) are the structured successors.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(default)* | Template file from `template/inc_cntpart/imagetable/`. Empty uses `template/inc_default/imagetable.tmpl`. |
| Images | multi-select | — | Images picked in the file browser (`filebrowser.php?opt=1`), sortable up/down, removable. The select order is the output order. |
| Position | icon group 0–7 | 0 | 0 top left, 1 top center, 2 top right, 3 bottom left, 4 bottom center, 5 bottom right, 6 float left, 7 float right. |
| Table | switch, next to Position | off | Render the image rows as an HTML `<table>` instead of `div` wrappers. |
| Max width / Max height | number px | `imagegallery_default_width` (490) / `imagegallery_default_height` (370) | Thumbnail size. With `RESPONSIVE_MODE` off the width is capped to `(content_width − (columns − 1) × space) / columns`, divided by 1.75 additionally for positions 6/7. |
| Crop thumbnail | switch | off | Crop thumbnails to the given size instead of proportional scaling. |
| Images max. | select | All | Renders only the first N images of the list (options 1 to list count + 10). Combination with random allows "N random images" teasers. |
| Select images randomly | switch | off | Shuffles the image list before rendering. |
| Column | select 1–25 | 1 (`imagegallery_default_column`) | Images per row; capped to the number of images. |
| Image space | number px | 0 (`imagegallery_default_space`) | Spacing between image columns in table mode. |
| Click enlarge | switch | off | Link each image to a large version, by default as lightbox. |
| Gallery image | switch | off | Group images for the lightbox (`rel="lightbox[<group>]"`); implies Click enlarge. |
| Hide caption for thumbnails | switch | off | Suppress caption output. |
| Caption | textarea, one line per image | — | Line n describes image n. Format per line: `caption \| alt \| link target \| title \| copyright`. |

## Rendering

### Template selection

Order: selected file from `template/inc_cntpart/imagetable/<name>.tmpl`, else `template/inc_default/imagetable.tmpl`, else the built-in wrapper (`[ATTR_CLASS]…[IMAGETABLE]{IMAGETABLE}[/IMAGETABLE]…`). The shipped default wraps the table output:

```html
<div class="image-table images-count-{IMAGE_COUNT}" id="imgtable-id-{ID}">
    {IMAGETABLE}
</div>
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[IMAGETABLE]…[/IMAGETABLE]` | The complete `imagelisttable()` output. |
| `{IMAGE_COUNT}` | Number of stored images. |
| `{ATTR_CLASS}` / `[ATTR_CLASS]…[/ATTR_CLASS]` | Content part wrapper class (`acontent_attr_class`), `[ATTR_CLASS_ELSE]` fallback branch. |
| `{ATTR_ID}` / `[ATTR_ID]…[/ATTR_ID]` | Wrapper `id`. |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle — rendered into the template only when the template text contains the string `TITLE`; otherwise titles are appended by `headline()` relative to the image block, depending on the position. |
| `{ID}` | Content part record ID. |

### IMAGETABLE_SETTINGS

The `[IMAGETABLE_SETTINGS]` INI section (comment style `key = value`) merges over the stored backend values. Keys read by the code:

```ini
[IMAGETABLE_SETTINGS]
class_top_left         = imgtable-top-left
class_top_center       = imgtable-top-center
class_top_right        = imgtable-top-right
class_bottom_left      = imgtable-bottom-left
class_bottom_center    = imgtable-bottom-center
class_bottom_right     = imgtable-bottom-right
class_float_left       = imgtable-left
class_float_right      = imgtable-right
class_image_thumb      = image-thumb
class_image_wrapper    = image-wrapper
class_image_link       = image-link
class_image_zoom       = image-zoom
class_image_lightbox   = image-lightbox
width / height / zoom / crop / lightbox / nocaption / col / space / limit / random =
                        override the stored backend values
```

The shipped default template also lists a commented `colspace` key; the renderer reads `space`, not `colspace`.

### Output

Table mode (`Table` on): one `<table class="imagelist-table">` with an image `<td>` per column, spacer `<td>`s of `space` px, and a second caption row below each image row.

Div mode (`Table` off): one wrapper `<div class="image-table">` (or the value of the `class` setting) containing per image:

```html
<div class="image-wrapper first row-1">
    <img src="/img/…" data-image-id="12" data-image-hash="…" data-image-ext="jpg"
         alt="…" title="…" class="image-thumb" />
    <p style="width:<thumb width>px" class="caption">Caption text <span class="copyright">© …</span></p>
</div>
```

The first image of a row carries `first`, rows are numbered `row-1`, `row-2`, … . Classes come from the `class_image_*` settings (merged over `template_default['classes']`).

Zoom behavior: without Gallery image the thumbnail links to `image_zoom.php?…` (popup via `clickZoom()`); with Gallery image the link points at the enlarged image (`img_prev_width` × `img_prev_height`, default 978 × 734) and carries `rel="lightbox[<group>]"` plus the caption as `title`. If the caption itself carries a link segment, the image links to that URL instead, in both modes. The lightbox script is `initSlimbox()`.

> **Note:** Since 2.0, `initSlimbox()` initializes [GLightbox](../general/concepts.md); the `rel="lightbox[…]"` attributes are kept for compatibility.

## Storage

- Everything except the template name is serialized into `phpwcms_articlecontent.acontent_form`: `images` (list of `id,name,hash,ext,width,height,caption` tuples), `width`, `height`, `pos`, `col`, `zoom`, `space`, `lightbox`, `nocaption`, `crop`, `random`, `limit`, `usetable`
- Template file name: `acontent_template`

## Related

- Single image variant: [Text w/image (cp 1)](01-text-with-image.md)
- Successors with full template sections: [Images &lt;div&gt; (cp 29)](10-images-div.md), [Images special (cp 31)](11-images-special.md)
- Hover-swap gallery: [Rollover images (cp 50)](14-rollover-images.md)