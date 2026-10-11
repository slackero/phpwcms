---
title: Assets and libs
description: The template/lib inventory, include/inc_js, the JS:CSS head comment convention and build managed assets.
sidebar_position: 5
---
# Assets and libs

> Source: `template/lib/`, `include/inc_js/`, `include/inc_front/js.inc.php`, `include/inc_front/lib/js.jquery.default.php`, `include/build/build-css.js`, `include/build/build-js.js`

## template/lib/

Libraries a template's markup or settings pull in. Everything under `template/lib/` belongs to the template distribution, not the core.

| Directory | Purpose | Loaded by |
| --------- | ------- | --------- |
| `bootstrap5/` | Bootstrap CSS and bundle JS | `<!-- JS: … -->`/`<!-- CSS: … -->` comments or htmlhead markup |
| `bootstrap-icons/` | Icon font CSS plus fonts | template markup |
| `cookieconsent2/` | Silktide consent scripts and themes | Template setting *Cookie consent v2* — the theme field names a CSS file in this directory, loaded with CDN fallback to cdnjs 1.0.10 | 
| `cookieconsent3/` | orestbida `CookieConsent.run()` assets, components CSS and `lang/<lang>.json` translations | Template setting *Cookie consent v3*; CDN variant pulls jsdelivr 3.1.0 |
| `glightbox/` | Lightbox | `initGlightbox()` (article summary and image lightboxes), options via `$phpwcms['glightbox_options']` |
| `jquery/` | One minified jQuery per version, 1.3.2 through 4.0.0 plus migrate builds and `plugin/` | Selected by the template's JS library setting |
| `jquery/plugin/` | Standalone jQuery plugins: cycle, dimensions, easing, fancybox, mousewheel, switchClass, zebra_accordion | `<!-- JS: <name> -->` for any `jquery.<name>.js` file |
| `simplegmaps/` | Google Maps wrapper | Content part templates load it via a `<!-- JS:{TEMPLATE}lib/simplegmaps/simplegmaps.min.js -->` comment, e.g. `template/inc_cntpart/tabs/simplegmaps.tmpl` |
| `splide/` | Slider | Content part templates reference it through `<!-- CSS: template/lib/splide/splide.min.css -->` and `<!-- JS: template/lib/splide/splide.min.js -->`, e.g. `template/inc_cntpart/images/Splide-Slider-Single-Image.tmpl` |
| `video-js/` | video.js player | `initVideoJs()` for multimedia content parts; falls back to the CDN `vjs.zencdn.net/8.24.0/` unless `template/lib/video-js/video.min.js` exists or `$phpwcms['video-js']` sets a path |

## include/inc_js/

Core frontend scripts served from the include tree:

| File | Purpose |
| ---- | ------- |
| `phpwcms.js` / `phpwcms.min.js` | phpwcms own frontend helper code (minified via build) |
| `bootstrap.bundle.min.js` | Backend-side copy of the Bootstrap bundle |
| `dropzone.min.js`, `exif.min.js`, `flatpickr.min.css/.js` | Upload and date picker assets for backend forms |
| `dayjs.min.js` + `dayjs-locale/` | Date handling in the backend |
| `jquery/jquery-3.7.1.min.js` | Backend jQuery |
| `ace/` | Ace code editor used by the template and code editors in the backend |

`template/inc_js/` is the template-side counterpart: `frontend.js` (loaded by `initFrontendJS()`, the template *frontend.js* switch or the legacy `frontend.js` detection) and `imagezoom.js` for the click-zoom image popup.

## The <!-- JS: --> and <!-- CSS: --> convention

`renderHeadJS()` and `renderHeadCSS()` handle both comment markers, wherever they appear in template, block or content markup:

| Argument | Result |
| -------- | ------ |
| `<name>` without extension | `initJSLib()` plus `initJSPlugin('<name>')` → loads `template/lib/jquery/plugin/jquery.<name>.js` |
| `initJSLib` | Loads only the selected base library |
| `file.js` | External `<script>` from the template path; `{TEMPLATE}` inside the path is resolved first |
| `https://…`, `//…` | External `<script>` from the remote URL |
| `ready|<code>`, `domready|<code>`, `onload|<code>` | Inline script executed on DOM ready; jQuery wrapped when the code uses `$()` or `jQuery()` |
| `<code>` | Inline script emitted once per unique code into `<head>` |
| CSS argument ending `.css` | External stylesheet link, relative to the template path |
| CSS argument with a `.` but no `.css` or plain rule text | Inline `<style>` block, emitted once per unique code |

All entries land in the shared array `$block['custom_htmlhead']` keyed by MD5, so repeated tags emit one tag only.

## JS library wrappers

The template's JS library setting selects one wrapper file, loaded as `include/inc_front/lib/js.` + name + `.inc.php`. `$phpwcms['js_lib_default']` merged in `default.inc.php` defines jQuery 1.4 through 4.0, each with `-migrate`, `-migrate-1` and `-slim` variants; `$phpwcms['js_lib_deprecated']` extends the list when deprecated rendering is on. `js.jquery.inc.php` (a legacy alias) and `js.jquery.default.php` (shared plugin/loader functions, e.g. `initJSPlugin()` and the `initSlimbox()` alias for `initGlightbox()`) are included by every wrapper.

An invalid library name falls back to the `jquery-3.7-migrate-1` wrapper. `PHPWCMS_USE_CDN` (template checkbox plus `$block['jslib']` wrapper) switches local copies to jquery.com/googleapis URLs.

## Build managed files

`npm run build` (scripts `build:css`, `build:js` in `package.json`) regenerates shipped third party assets from `node_modules`:

- `include/build/build-js.js` — terser-minifies `include/inc_js/phpwcms.js`, copies Bootstrap bundle, Dropzone, Flatpickr, dayjs, jQuery 3.7.1, video.js, GLightbox and vanilla-cookieconsent into their destinations, syncs the Ace editor subset into `include/inc_js/ace/` and applies patched minification to the legacy cookieconsent2 script.
- `include/build/build-css.js` — copies Bootstrap, Bootstrap Icons, Dropzone, Flatpickr, Tom Select, Font Awesome solid, video.js, GLightbox and cookieconsent3 CSS.

Destinations include both `include/inc_js/` (backend) and `template/lib/` (frontend). Do not hand-edit these copies — edit the npm dependency set and rebuild. `cookieconsent2/cookieconsent.min.js` is pinned by the build script rather than synced from a current node_modules package.

## Editor libraries

The backend WYSIWYG editors load per `$phpwcms['wysiwyg_editor']` from `include/inc_lib/wysiwyg.editor.inc.php`:

| Value | Editor | Asset location |
| ----- | ------ | -------------- |
| 0 | none | textarea only |
| 1 | CKEditor (legacy) | `include/inc_ext/ckeditor/` (CKEditor 4.22.1 LTS line), configs in `template/config/ckeditor/` |
| 2 | TinyMCE 8 | `include/vendor/tinymce/tinymce/`, translations from `include/vendor/mklkj/tinymce-i18n/langs/` |

They are backend-only; frontend pages never include them. The legacy swfobject loader (`initSwfObject()`) still exists for old multimedia content parts and looks for `template/lib/swfobject/swfobject.js`.

## Related

- [Template system](template-system.md)
- [Layout tags](layout-tags.md) — the `<!-- JS: … -->`/`<!-- CSS: … -->` syntax