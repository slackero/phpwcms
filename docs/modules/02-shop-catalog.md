---
title: Shop catalog
description: Product and category management of the shop module with product fields, image handling and shop database tables.
sidebar_position: 2
---
# Shop catalog

> Source: `include/inc_module/mod_shop/inc/`, `setup/default_sql/phpwcms_init.sql`

Product and category management of the [shop module](01-shop.md). The backend areas run under `phpwcms.php?do=modules&module=shop&controller=prod` and `...&controller=cat`; actions (`edit`, `status`, `delete`) ride the same URL (`backend.default.php`).

## Products

`inc/listing.products.inc.php` lists products with pagination (rows per page via GET `c`, default 250, or `all`), active/inactive filter and a word filter over order number, model, names, tag, price and descriptions. Columns: visibility, order number, model, name, price, inventory. Visibility toggles via `status`; delete is a soft delete setting `shopprod_status = 9`.

### Product form

> Source: `inc/edit.products.inc.php`, save in `inc/processing.products.inc.php`

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Order number | text, max 20 | – | Required; unique among products of the same language |
| Model | text, max 200 | – | Free model designation |
| Language | radio | all | Only when `shop_pref_felang` is on: record applies to all languages or one of the allowed frontend languages |
| Product name | text, max 200 | – | Required — shown in listings, page title, mails |
| Additional name | text, max 200 | – | Secondary title line |
| Weight | text | – | Number with unit suffix from `shop_pref_unit_weight` |
| Inventory | number | – | Stock count; decremented per order unless `shop_pref_autosubtract_off` |
| Unit | text, max 100 | – | Sales unit shown with prices |
| Price | text | – | Price; max magnitude 10^10 |
| Price is gross | checkbox | off | Off = price entered as net, on = price entered as gross; the other value derives from the VAT rate |
| VAT | select | – | Rates from `shop_pref_vat`; a manually entered new rate is appended to the preferences list |
| Option 1 | textarea | – | Product options, see [Shop – product options](04-shop-options.md) |
| Option 2 | textarea | – | Second option dimension, same engine |
| Short description | textarea | – | `shopprod_description0`; product list teaser, meta description fallback |
| Description (WYSIWYG) | editor | – | `shopprod_description1`; full product text, editor from the session WYSIWYG setting |
| Product info 2 / 3 | textarea | plain | `shopprod_description2`, `shopprod_description3` |
| Images | multi-select + captions | – | Chosen from the file browser (`filebrowser.php?opt=5`); per-image caption lines; first image is the list image |
| Files | multi-select | – | Chosen from the file browser (`filebrowser.php?opt=9`) with optional captions |
| External link | text, max 250 | – | Product URL stored in `shopprod_var` |
| Category | multi-select | – | Shop categories, see below |
| Tags | comma list | – | Lowercased, reduced to `0-9a-z`, comma, space, `-`, `_` |
| On request | checkbox + URL | off | Product without cart — replaced by a request link, see options page |
| Status | checkbox | on | Active state (`shopprod_status` 0/1) |
| Show in all categories | checkbox | off | `shopprod_listall` — the product lists without an active category filter |
| Overwrite meta | checkbox | on | Product data wins over article meta on the detail page (`shopprod_overwrite_meta`) |
| OpenGraph | checkbox | `set_sociallink.shop` | Emits `og:product` meta (`shopprod_opengraph`) |
| Save as duplicate | checkbox | – | Only shown while editing; stores a copy |

The `shopprod_maxrebate` column exists in the schema but the form forces it to `0` on insert and no code reads it. The `shopprod_special_price` column likewise exists only after the migration — no code path sets or uses it, so it documents the schema, not a feature.

### Image sizing

No per-product size settings exist. Image sizes come from the template CONFIG (template file or content part custom INI — see [options page](04-shop-options.md)): `image_list_width/height/crop`, `image_detail_*` and `image_detail_more_*` keys. `shop_image_tag()` (`frontend.init.php`) renders through the cached thumbnail generator, cache key derived from file hash, size, sharpening and crop mode; lightbox output wraps the full-size image with the resize-image URL and a `lightbox[product_...]` relation. List views render the first image only; the detail view renders all images, applying the "more" sizes from the configured start index. Product files render through the file list renderer of content part 7 (`include/inc_front/content/cnt7.article.inc.php`), honoring its direct-download and template settings.

## Categories

Shop categories live in the shared category table `phpwcms_categories` with `cat_type = 'module_shop'`. Structure supports one nesting level: the parent select lists top-level categories only (`cat_pid = 0`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Name | text | – | Required; unique among shop categories regardless of nesting |
| Parent | select | 0 (top level) | Top-level categories only |
| Info | textarea | – | Category description |
| Sort | number | – | Ordering value |
| Status | switch | on | Active (`cat_status` 0/1) |

Deleting a category sets `cat_status = 9` for the category and its children (`processing.categories.inc.php`); deactivated and deleted categories disappear from product selection and frontend lists.

## Storage

> Source: `setup/default_sql/phpwcms_init.sql` line 733

Products live in `phpwcms_shop_products`:

| Column | Type | Contents |
| ------ | ---- | -------- |
| `shopprod_id` | int | Row id |
| `shopprod_createdate` / `shopprod_changedate` | datetime | Timestamps |
| `shopprod_status` | int(1) | 0 inactive, 1 active, 9 deleted |
| `shopprod_uid` | int | Backend user id of the creator |
| `shopprod_ordernumber` | varchar(255) | Order number, unique per language |
| `shopprod_model` | varchar(255) | Model designation |
| `shopprod_name1`, `shopprod_name2` | varchar(255) | Name lines |
| `shopprod_tag` | varchar(255) | Comma separated tags |
| `shopprod_vat` | float | VAT rate in percent |
| `shopprod_netgross` | int(1) | 0 = price is net, 1 = price is gross |
| `shopprod_price` | float | Price as entered |
| `shopprod_maxrebate` | float | Unused (forced 0) |
| `shopprod_description0` – `shopprod_description3` | text | Description fields |
| `shopprod_var` | mediumtext | Serialized: images, files, external URL, request flag and URL |
| `shopprod_category` | varchar(255) | Comma list of `phpwcms_categories.cat_id` |
| `shopprod_weight` | float | Weight |
| `shopprod_size`, `shopprod_color` | varchar(255) | Option definitions — see [options](04-shop-options.md) |
| `shopprod_listall` | int(1) | Show without category filter |
| `shopprod_special_price` | text | Unused (migration only) |
| `shopprod_track_view` | int | Detail view counter, indexed |
| `shopprod_lang` | varchar(255) | Language code when language support is on |
| `shopprod_overwrite_meta` | int(1) | Meta override flag, default 1 |
| `shopprod_opengraph` | int(1) | OpenGraph flag, default 1, indexed |
| `shopprod_unit` | varchar(100) | Sales unit |
| `shopprod_inventory` | int(11) | Stock count |

Further involved tables: `phpwcms_categories` (`cat_type = 'module_shop'` rows hold categories), `phpwcms_file` (product images and files), `phpwcms_sysvalue` (preference keys of the [overview page](01-shop.md)). Orders live in `phpwcms_shop_orders`, documented on the [ordering page](03-shop-ordering.md#storage). The cart is session only — no cart table exists.

## Manufacturer management

The module has none — no manufacturer table, field or backend page exists in `include/inc_module/mod_shop/`.

## Related

- [Shop](01-shop.md) — activation, preferences, content part
- [Shop – ordering and checkout](03-shop-ordering.md) — cart, payment, orders
- [Shop – product options](04-shop-options.md) — option engine and content part options