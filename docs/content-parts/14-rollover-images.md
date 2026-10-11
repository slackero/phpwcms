---
title: Rollover images
description: Swap one large image on hovering a row of small reference thumbnails.
sidebar_position: 14
---
# Rollover images (content part 50)

> Source: `include/inc_lib/content/cnt50.readform.inc.php`, `include/inc_lib/content/cnt50.sql.inc.php`, `include/inc_lib/content/cnt50.takeval.inc.php`, `include/inc_tmpl/content/cnt50.inc.php`, `include/inc_front/content/cnt50.article.inc.php`

Legacy hover gallery: the first image of the list renders as the large reference image; every image — the first one included — also renders as a small thumbnail that swaps the large image on mouseover and updates a caption layer. Table-based output, popup zoom, no lightbox, and more than one thumbnail is required for the thumbnail row to appear.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(default)* | Template file from `template/inc_cntpart/reference/`. Empty uses `template/inc_default/reference.tmpl`. |
| Plain text | textarea | — | Text right of the images; HTML-special-chars on save, rendered with `nl2br`. |
| Image | multi-select | — | File center images (`filebrowser.php?opt=3`), sortable. First image = large view. |
| Caption | textarea, one line per image | — | Line n belongs to image n; used as `alt`/`title` and as text for the caption layer. |
| Zoom | switch | off | Every thumbnail additionally links to a `image_zoom.php` popup (`window.open`). |
| Large reference image — Max width / Max height | number px | `content_width` (978) | Size of the large image; width is capped to `content_width`. Border pixels subtract from both sizes. |
| Large reference image — Border | number px | 0 | `border` attribute of the large image. |
| Small reference images — Alignment | radio | Horizontal | Horizontal = all thumbnails in one table row, vertical = one thumbnail per row. |
| Small reference images — Cell position | select | Default | Alignment of each thumbnail cell: combinations of left/center/right and top/middle/bottom (values 1–9, 0 = default). |
| Small reference images — Block w × h | number px | `content_width` / `img_prev_height` (734) | Available space for the thumbnail row; only used with more than one thumbnail. |
| Small reference images — Image space | number px | 0 | Spacer pixels between thumbnails (`img/leer.gif`). |
| Small reference images — Border | number px | 0 | `border` attribute of the thumbnails. |

Thumbnail size is computed from the block: `(block size − (n − 1) × space) / n − 2 × listborder`, at least 10 px.

## Rendering

Output is a set of nested `<table>` elements (defined in the template). The large image carries `id`/`name` `refimgid<cp id>`; each thumbnail carries `refimgid<cp id>a<index>` and an inline `onmouseover` handler from `template/inc_js/frontend.js` (`initFrontendJS()`):

```html
<table>
  <tr>
    <td><table>
      <tr><td><img src="… large …" id="refimgid12" /></td></tr>
      <tr><td><div id="refcaptid12">First caption line</div></td></tr>
    </table>
    <table><tr>…thumbnail <td>s with onmouseover="swapImage(…);addText(…);return false;"…</tr></table></td>
    <td width="98%"><h3>Title</h3><p>Text</p></td>
  </tr>
</table>
```

`swapImage()` replaces the `src` of the large image with the thumbnail rendered at large size; `addText('refcaptid<cp id>', '<caption>')` only fires when the template contains a `[CAPTION]` block. Zoom popups open `image_zoom.php?…` in a `previewpic` window when Zoom is on and the enlarged version exists.

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[REF]…[/REF]` / `{REF}` | Large image `<img>`; contains the placeholder text `[NO&nbsp;IMAGE]` without images. |
| `[CAPTION]…[/CAPTION]` / `{CAPTION}` | Caption layer (`div#refcaptid<cp id>`), shows caption line 1. |
| `[LIST]…[/LIST]` / `{LIST}` | Thumbnail `<table>`; empty with only one image. |
| `[TITLE]` `{TITLE}`, `[SUB]` `{SUB}` / `[TEXT]` `{TEXT}` | Content part title, subtitle, and the plain text with `nl2br`. |
| `{ATTR_CLASS}` / `[ATTR_CLASS]…[/ATTR_CLASS]`, `{ATTR_ID}` / `[ATTR_ID]…[/ATTR_ID]` | Wrapper class / id with `_ELSE` branches. |
| `{ID}` | Content part record ID. |

## Storage

- Everything — including the template name under key `tmpl` — is stored in the single serialized `phpwcms_articlecontent.acontent_form` value: `text`, `tmpl`, `list` (image tuples with rendered sizes), `caption`, `zoom`, `width`, `height`, `border`, `pos`, `basis`, `blockwidth`, `blockheight`, `space`, `listborder`, plus computed `col`, `showlist`, `temp_list_width`, `temp_list_height`

## Related

- Modern gallery alternatives: [Images (cp 2)](09-images.md), [Images &lt;div&gt; (cp 29)](10-images-div.md)
- Popup zoom helper shared with [Images (cp 2)](09-images.md)