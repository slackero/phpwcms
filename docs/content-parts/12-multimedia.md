---
title: Multimedia
description: Embed video, audio or Flash media from the file center or an external URL.
sidebar_position: 12
---
# Multimedia (content part 9)

> Source: `include/inc_lib/content/cnt9.readform.inc.php`, `include/inc_lib/content/cnt9.sql.inc.php`, `include/inc_lib/content/cnt9.takeval.inc.php`, `include/inc_tmpl/content/cnt9.inc.php`, `include/inc_front/content/cnt9.article.inc.php`

Embeds one media file — internal from the file center or external by URL — at a chosen position in the article. Player options 0–2 render HTML5 `<video>`/`<audio>` elements; option 3 renders a Flash `<object>` with SWFObject registration. The legacy plugin names (Quicktime, RealPlayer, MediaPlayer) only switch the rendering branch to HTML5.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(default)* | Template file from `template/inc_cntpart/multimedia/`. Empty uses `template/inc_default/multimedia.tmpl`. |
| Media type | radio | Video | Video, Audio, Flash. Choosing Flash forces the Flash Plugin player, choosing Video/Audio switches it back. |
| Player | radio | Quicktime | Quicktime, RealPlayer, MediaPlayer render as HTML5; Flash Plugin renders a Flash object. |
| Show control bar | switch | off | Adds `controls` to the HTML5 element. |
| Autoplay | switch | off | Adds `autoplay`. |
| Flash transparent | switch | off | Flash only: `wmode` `transparent` instead of `opaque`. |
| Source | radio + input | Internal | Internal: file from the media browser (`filebrowser.php?opt=2`). External: full URL (`https://…`). |
| Position | icon group 0–4 | 0 | 0 top left, 1 top center, 2 top right, 3 float left, 4 float right. |
| Width / Height | number px | — | Player box size; preset buttons fill 160×120, 240×180, 320×240, 480×360; a clear button empties both. |
| Alt. image | file select | — | Image shown as fallback content inside the player element, resized to the player size. |
| Alt. text | textarea | — | Caption/fallback text rendered below the alt image. |

## Rendering

Source resolution: an external URL wins; otherwise the internal file is loaded from `phpwcms_file` (public and active; granted-restricted files need a frontend login) and served as `filearchive/<hash>.<ext>`.

### HTML5 players (Quicktime / RealPlayer / MediaPlayer)

Audio detection: media type Audio, or a file ending in `mp3, m4a, aac, wav, oga, ogg, flac, wma, ra`.

```html
<audio id="mediaID<cp id>" controls autoplay preload="metadata">
  <source src="<source>" type="audio/mpeg">
  <div class="alt-image"><img src="/img/cmsimage.php/<w>x<h>x1/<id>/<name>" alt="…" /></div>Alt. text
</audio>
```

```html
<video id="mediaID<cp id>" width="…" height="…" controls autoplay playsinline
       preload="metadata" style="max-width:100%;height:auto;">
  <source src="<source>" type="video/mp4">
  …alt content…
</video>
```

The MIME type maps from the file's stored type: `video/quicktime` and `mov|mp4|m4v` become `video/mp4`, `webm` stays `video/webm`, `ogv|ogg` becomes `video/ogg`. Without a detected type the defaults are `video/mp4` and `audio/mpeg`.

### Flash player (Flash Plugin)

Renders the nested Internet Explorer-safe `<object>` markup (classid, `param movie/quality/scale/loop/play/wmode`, inner `<object type="application/x-shockwave-flash">`) and registers it through SWFObject (`initSwfObject()`); width/height fall back to the measured media size when empty and the source is local. The alt image and text are the fallback content.

### Positions

Position 0–2 print the title/subtitle headline and wrap the code in `align` divs (`center`, `right`); positions 3/4 wrap it in a `<table align="left|right">` with title in `tableHead` and subtitle in `tableSubHead` cells. With no media source only the headline renders.

### Template

Order: `template/inc_default/multimedia.tmpl`, else `template/inc_cntpart/multimedia/<selected>`, else built-in. Tags:

| Tag | Meaning |
| --- | ------- |
| `[MULTIMEDIA]…[/MULTIMEDIA]` / `[MULTIMEDIA_ELSE]…[/MULTIMEDIA_ELSE]` | Player output block; `_ELSE` branch when nothing is rendered. |
| `{MULTIMEDIA}` | The player markup. |
| `{ATTR_CLASS}` / `[ATTR_CLASS]…[/ATTR_CLASS]`, `{ATTR_ID}` / `[ATTR_ID]…[/ATTR_ID]` | Wrapper class / id with `_ELSE` branches. |
| `{ID}` | Content part record ID. |

## Storage

- All settings serialized in `phpwcms_articlecontent.acontent_form`: `media_type`, `media_player`, `media_src`, `media_auto`, `media_transparent`, `media_control`, `media_pos`, `media_width`, `media_height`, `media_id`, `media_name`, `media_extern`, `image_name`, `image_id`, `image_caption`
- `acontent_media` is written as an empty string
- Template name: `acontent_template`

## Related

- Video/audio player with format variants: [HTML5/Flash media player (cp 25)](13-flashplayer.md)
- [Text w/image (cp 1)](01-text-with-image.md) for image positions