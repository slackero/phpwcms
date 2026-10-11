---
title: HTML5/Flash media player
description: Play video or audio through Video.js with format sources and a Flash fallback.
sidebar_position: 13
---
# HTML5/Flash media player (content part 25)

> Source: `include/inc_lib/content/cnt25.readform.inc.php`, `include/inc_lib/content/cnt25.sql.inc.php`, `include/inc_lib/content/cnt25.takeval.inc.php`, `include/inc_tmpl/content/cnt25.inc.php`, `include/inc_front/content/cnt25.article.inc.php`

A single media player with per-format sources (H.264, WebM, Ogg/MP3), rendered as a [Video.js](https://videojs.com) `<video>`/`<audio>` element. When "HTML5 only" is off the player initializes through Video.js and keeps a Flash fallback object (NonverBlaster via SWFObject) for legacy media.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(default)* | Template file from `template/inc_cntpart/flashplayer/`. Empty uses `template/inc_default/flashplayer.tmpl`. |
| Player size | select + Width/Height px | 320 × 240 | Presets include 240p…2160p (4k), 21:9 variants and fixed sizes (`template_default['settings']['html5_player']['sizes']`). |
| HTML5 H.264 | internal/external | Internal | `mp4, m4v, mov, m4p, m4a, mp3, aac, mpeg`; media browser filter `opt=12`. |
| HTML5 WebM | internal/external | Internal | `webm`; `opt=13`. |
| HTML5 Ogg | internal/external | Internal | `ogg, ogv, oga, ogx`; `opt=14`. |
| Flash | internal/external | Internal | Fallback media: `mp4, mp3, flv, mov, swf, f4v, m4v, jpg, png`; `opt=6`. With no HTML5 source the player renders as pure SWFObject embed. |
| Caption | textarea | — | Rendered at `[CAPTION]`. |
| Link | text | — | `URL target`; the URL also becomes the Flash `onClick` target. |
| Thumbnail | file select | — | Poster/teaser image, rendered cropped (`/<w>x<h>x1x85/`) via the image resize endpoint. |
| Markers | textarea | — | One per line: `time \| text \| overlay text \| css class`; time as seconds (`10.5`). Passed as JSON to the Video.js instance. |
| HTML5 only | switch | off | Raw `<video>`/`<audio>` tag without Video.js and without Flash fallback. |
| Audio only | switch | off | Renders `<audio>` and maps sources to `audio/*` MIME types. |
| Autostart | switch | off | `autoplay="autoplay"`. |
| Loop | switch | off | `loop="loop"`. |
| Hide HTML5 download button | switch | off | Adds `controlsList="nodownload"`. |
| Preload | select | auto | `auto`, `metadata`, `none` — emitted as `preload` attribute. |
| Skin HTML5 | select | default | CSS from `template/lib/video-js/skins/<name>.css`; adds a stylesheet link and the skin class. |
| Controlbar | select | bottom | `bottom` (default) or `over` add `controls="controls"`, `none` drops it; `none` also disables the Flash `controlsEnabled`. |
| Volume | select 0–100, step 5 | 80 | Set through `this.volume(…)` on the Video.js instance; 0 adds `muted` to the tag. |
| Background color / Foreground color (HEX) | text | 000000 / FFFFFF | Flash control bar colors (`controlBackColor`, `controlColor`, `bgcolor`). |
| Watermark (URL) | text | — | Flash-only `indentImageURL` logo overlay. |

Internal media plays through `download.php?file=<hash>[.<ext>]&type=<mime>`; on iOS an `&ios=/` parameter is appended. Files with image extensions (`jpeg, jpg, png, gif, swf`) cancel the poster image.

## Rendering

### Video.js element

The leading tag collects `id="video-js-fmp<cp id>"`, class `video-js <skin> vjs-default-skin`, `width`/`height` (video only), `poster` (video only), `autoplay`, `loop`, `controls` unless Controlbar is `none`, `muted` at volume 0 and `preload`. Each configured source becomes `<source src="…" type="…">`:

| Source kind | Video | Audio |
| ----------- | ----- | ----- |
| H.264 | `video/mp4` | `audio/mpeg` (m4a file: `audio/mp4`) |
| WebM | `video/webm` | `audio/webm` |
| Ogg | `video/ogg` | `audio/ogg` |

Video.js loads locally from `template/lib/video-js/video.min.js`, else from the `vjs.zencdn.net/8.24.0` CDN (`initVideoJs()`); the init script registers the instance in `videoJsInstances`, applies markers, volume and deep links, and honors the `js_in_body` config for head vs. body placement. A URL parameter `?fmp=<cp id>-<seconds>` (or `-m<marker number>`) seeks the matching player instance and scrolls to it.

### Flash fallback

The fallback `<object class="vjs-flash-fallback">` carries SWFObject flashvars: `mediaURL`, `loop`, `showScalingButton`, `scaleIfFullScreen`, `crop`, `defaultVolume`, `buffer: 6`, `allowSmoothing`, `controlsEnabled`, `autoPlay`, `controlBackColor`, `controlColor`, plus `teaserURL` (poster) and `indentImageURL` (watermark) when set. Player SWF: `template/lib/nonverblaster/NonverBlaster.swf`, params `allowfullscreen`, `menu: false`, `wmode: opaque`, `allowScriptAccess: always`. Without HTML5 sources the CP embeds the SWF directly via `swfobject.embedSWF` using the Flash version stored in `fmp_set_flashversion` (default 10, fallback 11).

### Template

Order: `template/inc_default/flashplayer.tmpl`, else `template/inc_cntpart/flashplayer/<selected>`, else built-in wrapper. Tags:

| Tag | Meaning |
| --- | ------- |
| `{ATTR_CLASS}` / `[ATTR_CLASS]…[/ATTR_CLASS]`, `{ATTR_ID}` / `[ATTR_ID]…[/ATTR_ID]` | Wrapper class / id with `_ELSE` branches. |
| `[TITLE]` `{TITLE}`, `[SUBTITLE]` `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[PLAYER]…[/PLAYER]` / `{PLAYER}` | The complete player markup; block dropped when no media is configured. |
| `[CAPTION]…[/CAPTION]` / `{CAPTION}` | Caption text, HTML-escaped. |
| `[LINK]…[/LINK]` / `{LINK}`, `[TARGET]…[/TARGET]` / `{TARGET}` | Link URL and target. |
| `{ID}` | Player id `fmp<cp id>` (also used by the `video-js-` element id). |

## Storage

- The whole `fmp_data` array serialized in `phpwcms_articlecontent.acontent_form` (template name, size, per-format internal/external values, caption, link, markers, thumbnail, all `fmp_set_*` switches; legacy keys `fmp_set_flashversion` and `fmp_player` persist from the defaults)
- Template name: `acontent_template`

## Related

- Simple HTML5 embed: [Multimedia (cp 9)](12-multimedia.md)
- [Images &lt;div&gt; (cp 29)](10-images-div.md) for lightbox-based galleries