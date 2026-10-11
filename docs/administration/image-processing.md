---
title: Image processing
description: Graphics engine selection, source formats, sharpening, colorspace, quality and the dynamic image endpoint.
sidebar_position: 6
---

# Image processing

> Source: `include/config/dist.conf.inc.php`, `include/inc_lib/helper.image.php` (`Phpwcms_Image_lib`), `include/inc_lib/imagick.convert.inc.php` (`image_manipulate()`, `get_cached_image()`), `include/inc_lib/general.inc.php` (`is_ext_true()`), `img/cmsimage.php`, `include/inc_tmpl/admin.imagealiaslist.tmpl.php`

## Graphics engine

`image_library` selects the engine; `library_path` holds the binary directory when a CLI tool is used.

| Value | Engine | Notes |
| ----- | ------ | ----- |
| `GD2` | PHP GD extension | Default. Handles jpg, gif, png, webp; no sharpening, no EPS/AI/PS/PDF source rendering. |
| `Imagick` | PHP Imagick (PECL) extension | Highest priority recommendation when installed. |
| `ImageMagick` | ImageMagick CLI (`convert`/`magick`) | Needs `library_path`. |
| `GraphicsMagick` or `GM` | GraphicsMagick CLI (`gm`) | Needs `library_path`. |
| `NetPBM` | NetPBM CLI tools (`pnmscale`) | Needs `library_path`. |

The setup wizard detects installed tools (`detect_system_image_tools()`) and recommends Imagick > ImageMagick > GraphicsMagick > GD2. GD 1.x support was dropped in 2.x.

## Source formats

Thumbnail sources depend on the engine — `is_ext_true()` maps an upload extension to the engine's working format:

| Source extensions | GD2 | ImageMagick / GraphicsMagick / Imagick |
| ----------------- | --- | -------------------------------------- |
| jpg, jpeg | yes (jpg) | yes (jpg) |
| png, gif, webp, svg | yes | yes |
| tif, tiff, psd, bmp, pic, wmf, tga, jp2, ico, ... | no | converted, target jpg |
| **eps, ps, ai, pdf** | no | rendered to png; PostScript/PDF requires Ghostscript (`gs`) plus an ImageMagick `policy.xml` that grants PDF/EPS/PS read|write rights |

`imgext_disabled` (comma-separated string, default empty) excludes types from image handling — e.g. `'pdf,ps'`. Image extensions are also filtered at the SQL level when GD2 is active: the file query restricts to `jpg, jpeg, png, gif, bmp, svg, webp`.

The Docker image ships ImageMagick, GraphicsMagick, NetPBM, Ghostscript and the Imagick PHP extension with the PDF/EPS/PS/XPS policy entries enabled; see [Docker installation](../installation/docker.md).

## Quality, sharpening, colorspace

| Key | Default | Applies to | Effect |
| --- | ------- | ---------- | ------ |
| `jpg_quality` | `85` | all engines | JPEG output quality 25–100. |
| `webp_enable` | `1` | all engines | Delivers resized images as WebP when the client supports it; source stays intact. |
| `webp_quality` | `85` | all engines | WebP output quality. |
| `sharpen_level` | `1` | ImageMagick/GM/Imagick | 0 = off … 5 = extra sharp. Ignored by GD. |
| `resize_animated_gif` | `true` | all engines | Attempts resizing animated GIF frames; can increase file size. |
| `im_fix_colorspace` | `'RGB'` | ImageMagick-based | Colorspace written into converted images; switch to `SRGB` when colors render wrong on newer ImageMagick builds. The `xGS` parameter in the [image endpoint](#dynamic-image-endpoint) converts to gray instead. |
| `gd_memcheck_off` | `0` | GD | `1` skips the automatic memory-limit adjustment before resizing. |

## Default image dimensions

| Key | Default | Effect |
| --- | ------- | ------ |
| `content_width` | `978` | Maximum article content column width; base for multi-column image rendering. |
| `img_list_width` / `img_list_height` | `160` / `100` | File list thumbnail size. |
| `img_prev_width` / `img_prev_height` | `978` / `734` | Large preview size; also the fallback dimension when only one target dimension is given. |
| `responsive` | `1` | `1` keeps given image sizes, `0` caps width at `content_width`. |
| `preserve_image_name` | `1` | Keeps the original file name for resized versions. |

## Dynamic image endpoint

Resized/cropped images are requested through one URL. With rewriting enabled the path is `/im/...`, otherwise `img/cmsimage.php?...`:

```
/im/WIDTH[[xHEIGHT][xCROP][xQUALITY][xGS]]/HASH.EXT
```

- `WIDTH`/`HEIGHT` in px, either may be `0`.
- `CROP`: `1` crops centered; `2`-`19` divide the target into a grid; a position suffix `-tl`, `-tc`, `-tr`, `-cl`, `-cr`, `-bl`, `-bc`, `-br` moves the crop origin.
- `QUALITY` 10–100 overrides `jpg_quality`/`webp_quality`.
- `GS` delivers a grayscale conversion.
- The file part is the md5 storage hash (or file ID) plus extension; SVG files are passed through as XML with recalculated dimensions instead of raster resizing.
- `cmsimage_redirect` (`false`) redirects to the static generated file in `content/images/` instead of streaming it through the endpoint.

`cmsimage_settings` (array, default empty) prevents endpoint flooding: list every allowed size string, e.g. `array('500x500x0', 'default' => '1280x800x1')`. The `default` entry (or the first entry) applies when a request uses an unlisted size; `'empty'` returns a transparent GIF instead.

## Image aliases

Admin → Image aliases (`p=12`) manages the `f_alias` column of the `phpwcms_file` table. The page lists image files that have no alias yet and can batch-generate aliases from their file names, optionally prefixed with a keyword; aliases are sanitized for uniqueness. Alias values stay editable per file in the file center.

## Related

- [Admin panel](admin-panel.md) — image cache flushing
- [Cache and performance](cache-performance.md) — image cache location and invalidation
- [Configuration file](../installation/config-file.md) — full key reference
- [Text w/image](../content-parts/01-text-with-image.md) — content part using the pipeline