---
title: Frontend scripts
description: The frontend_init and frontend_render script hook layers, module render scripts and registration patterns.
sidebar_position: 2
---
# Frontend scripts

> Source: `include/inc_front/content.func.inc.php` (include loops), `include/inc_lib/general.inc.php` (`get_tmpl_files()`), `include/inc_module/*/frontend.init.php`, `include/inc_module/*/frontend.render.php`

Two hook directories let you run own PHP inside every page render without touching core files:

| Directory | Runs | Gate |
| --------- | ---- | ---- |
| `template/inc_script/frontend_init/` | early — after template and page layout load, before article rendering | `$phpwcms['allow_ext_init']` (default 1) |
| `template/inc_script/frontend_render/` | after the replacement cascade, before the final `render_device()` pass | `$phpwcms['allow_ext_render']` (default 1) |

Both loops use `get_tmpl_files()` to collect all `*.php` files in the directory and `include_once` them in alphabetical order. Subdirectories are ignored, which is why the shipped examples live in a `disabled/` folder — move a sample up one level to activate it. Each directory carries an `.htaccess` (`Deny from all`) to block direct HTTP access.

## What init scripts can do

At init time you have the full setup: `$phpwcms`, `$content['struct']`, the level breadcrumb in `$LEVEL_ID`/`$LEVEL_KEY`, `$block` (template settings), `$pagelayout` and `$template_default`. No page markup exists yet. Typical uses:

- **Redirection or language handling** — `lang_redirect.php`, `domaincheck.php` ship as examples.
- **Body ID injection** — `$content['body_id']` (see `frontend_init/disabled/inject_body_id.php`):
  ```php
  if (isset($LEVEL_ID[1])) {
      $content['body_id'] = $LEVEL_ID[1];
  }
  ```
- **Frontend user context** — `felogin.init.php` wires the ini based fe-login.

Content part trigger functions registered here run for every rendered content part (see registration patterns below).

## What render scripts can do

Render scripts receive the almost finished page in `$content['all']` and modify it in place:

```php
$content['all'] = str_replace('{MYTAG}', 'value', $content['all']);
```

They also run before the final device pass and the meta/OpenGraph insertion, so tags they write get processed by the remaining cascade (`render_device()`, `render_PHPcode()`, alias link rewriting).

## Content part triggers

A render or init script may register a callback that each content part passes through after rendering:

```php
function CP_CreateDate($text, $data) {
    $change_date = strtotime($data['acontent_created']);
    return str_replace('{CPDATE}', international_date_format('DE', 'd.m.Y', $change_date), $text);
}
register_cp_trigger('CP_CreateDate');
```

`trigger_cp()` calls every registered function with the content part markup and its database row — handy for tokens like `{CPDATE}` that need the CP record.

## Module provided scripts

Modules register hook scripts declaratively in their `module.default.php` and `include/inc_lib/modules.check.inc.php` collects them for active modules into `$phpwcms['modules_fe_init']` and `$phpwcms['modules_fe_render']`:

```php
$_module_fe_render = true;   // registers include/inc_module/<mod>/frontend.render.php
$_module_fe_init   = true;   // registers include/inc_module/<mod>/frontend.init.php
```

Both arrays include right after the template directory equivalents. The glossary module ships `frontend.render.php` — a concise example: it checks `isset($content['glossary'])`, defines `replace_glossary_tag()` and rewrites page markup matching `\[glossary <keyword>]…\[/glossary]` into `<abbr class="glossary" title="…">` elements, looking the keyword up in the glossary table and caching results in `$content['glossary_cache']`. Module render scripts follow the same rules and globals as `template/inc_script/frontend_render/` scripts.

## Globals cheat sheet

| Variable | Available in | Typical use |
| -------- | ------------ | ----------- |
| `$phpwcms` | both | configuration values, `USER_AGENT` |
| `$content` | both | `struct`, `main`, `all` (render only), `CB`, `articles`, `opengraph` |
| `$template_default` | both | classes, attributes, settings arrays |
| `$block` | both | template settings, `custom_htmlhead`, `js_ondomready` |
| `$pagelayout` | both | page layout values |
| `$content['globalRT']` | render | register replacement tags replaced in the final `html_parser()` pass |

Head building helpers from `front.func.inc.php`/`js.inc.php` work in both layers: `set_meta()`, `inlineJS()`, `jsOnDomReady()`, `initJSLib()` or direct `$block['custom_htmlhead']` entries (keys prefixed `head_` always stay in the head even with `js_in_body` on).

## Security notes

- Every script must check `PHPWCMS_ROOT` at its top:
  ```php
  if (!defined('PHPWCMS_ROOT')) {
      die('You Cannot Access This Script Directly, Have a Nice Day.');
  }
  ```
- Only files with a `.php` extension load; anything else in the directory is data for you, not code.
- Both layers are off when their config gate is 0 — a hardened public install can run pure frontend logic without them.
- Scripts execute in full request context: they can read the session and redirect responses. Treat the directories as code deployment, not media storage.

## Related

- [Page rendering](page-rendering.md) — where the layers sit in the pipeline
- [Layout tags](../templates/layout-tags.md)