---
title: Map
description: Place location points on a static uploaded map graphic with clickable areas and info entries.
sidebar_position: 15
---
# Map (content part 51)

> Source: `include/inc_lib/content/cnt51.readform.inc.php`, `include/inc_lib/content/cnt51.sql.inc.php`, `include/inc_lib/content/cnt51.takeval.inc.php`, `include/inc_tmpl/content/cnt51.inc.php`, `include/inc_front/content/cnt51.article.inc.php`, `img/mapimage.php`

A location map over a static graphic you upload to `template/inc_cntpart/map/map_img/`. Each location is a pixel coordinate pair on that graphic; rendering draws the markers (GD) and links every marker to its location entry, selected through a URL parameter. This is file-based, not a Google Maps/OpenStreetMap integration.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | — | Files from `template/inc_cntpart/map/` (`.tmpl` and `.html`). No default fallback exists — an empty selection renders the built-in output below. |
| Map image | radio list | — | JPG/GIF/PNG images in `template/inc_cntpart/map/map_img/`, with hover preview. Locations can be added only after an image is selected. |
| X / Y | number, max 4 digits px | — | Pixel position of the next location on the map image; "open map" (`cnt51.open.php`) is a click picker that fills both. |
| Location title | text | — | Required; saving without a title raises an error. |
| ZIP / City | text | — | Shown in the location info. |
| Entry/info | WYSIWYG | — | Description of the location. |
| Location list | table | — | Existing locations with X×Y, title, edit and delete links; delete is a soft delete (`map_deleted = 9`). |
| Text | textarea | — | Intro text rendered at `{TEXT}`. |

Location rows save immediately through the content part Save button (`INSERT`/`UPDATE` on `phpwcms_map`).

## Rendering

The renderer reads all locations of the content part (`phpwcms_map`, `map_deleted = 0`, ordered by ZIP and city) and builds:

- the map graphic as `<img src="img/mapimage.php?i=<name>&xy=<x,y, x,y>&v=<VAR>" usemap="#locations">`, where `mapimage.php` draws a rectangle marker per coordinate pair;
- one `<area shape="rect" coords="x-4,y-4,x+4,y+4">` per location, linking to `index.php?…&loc=<map_id>` with title and alt set to the location title;
- the location selected by `?loc=<map_id>` rendered into the `[LOCATION]` section.

Wrapper: with content part CSS class or ID set, the output is wrapped in one `<div>`; title and subtitle print through `headline()` before the content.

### Template

The template file is loaded strictly from `template/inc_cntpart/map/<selected>`; there is no `inc_default` fallback. Three sections (comment markers `<!--NAME_START//-->…`):

| Section | Tags |
| ------- | ---- |
| `VAR` | One line with five comma-separated values: `border, marker width, marker height, background HEX, marker fill HEX` — e.g. `1,9,9,FFFFFF,FF4000`. Used as the `v=` value of `mapimage.php`; without a `VAR` section the default is `1,7,7,FFFFFF,FF4000`. |
| `CONTENT` | `[MAP]` `{MAP}` map image markup, `[TEXT]` `{TEXT}` intro text (`nl2br`, HTML-escaped), `[LOCATION]` `{LOCATION}` rendered location block. |
| `LOCATION` | `{TITLE}`, `[ZIP]` `{ZIP}`, `[CITY]` `{CITY}` (HTML-escaped) and `[ENTRY]` `{ENTRY}` (the WYSIWYG entry, raw). |

Shipped files: `default_map.html` (documentation with examples inside the sections) and `BS5.tmpl`.

When no CONTENT section exists the renderer falls back to built-in output: map image floated right, text with `nl2br`, then the selected location as `<h5>` title plus ZIP (bold) and entry.

## Storage

- Template name, text, map image file name: serialized in `phpwcms_articlecontent.acontent_form` (`template`, `text`, `image`)
- Each location: one row in `phpwcms_map` — `map_cid` (content part ID), `map_x`, `map_y`, `map_title`, `map_zip`, `map_city`, `map_entry`, `map_deleted` (0 = active, 9 = deleted)

## Related

- [Images &lt;div&gt; (cp 29)](10-images-div.md) for real image galleries
- [General concepts](../general/concepts.md) for the content part model