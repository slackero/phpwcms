---
title: Shop
description: Shop and products module with activation, backend area map, preferences and the shop content part.
sidebar_position: 1
---
# Shop

> Source: `include/inc_module/mod_shop/`

The shop module turns phpwcms into a small online store: products with categories and product options, a session based cart, checkout, order emails and an order backend. Module registration (`module.default.php`): `$_module_name = 'shop'`, `$_module_type = 0` (backend and frontend), content part `true`, frontend init, render and search hooks.

- Catalog: [Shop – catalog management](02-shop-catalog.md)
- Ordering: [Shop – ordering and checkout](03-shop-ordering.md)
- Product options: [Shop – product options](04-shop-options.md)

## Installation / activation

The module ships enabled in discovery — no install step exists; see [Modules overview](index.md). On the first backend visit the module proves the shop tables exist and adds missing columns to `phpwcms_shop_products` in one session pass (`backend.default.php` line 31): `shopprod_special_price`, `shopprod_track_view`, `shopprod_lang`, `shopprod_overwrite_meta` and `shopprod_opengraph`. After 1.x upgrades this migration fills the newer columns automatically.

## Backend

URL: `phpwcms.php?do=modules&module=shop`. The controller and action live in GET parameters (`backend.default.php`): `controller` selects the area, `edit`, `status`, `delete` and `show` select the action. Unknown controllers fall back to orders.

| Area | Controller value | Contents |
| ---- | ---------------- | -------- |
| Orders | `order` (default) | Order list, order detail, status switches — see [ordering](03-shop-ordering.md) |
| Products | `prod` | Product list and product form — see [catalog](02-shop-catalog.md) |
| Categories | `cat` | Product category tree — see [catalog](02-shop-catalog.md) |
| Preferences | `pref` | All module settings — see below |

Tabs render from `inc/tabs.inc.php`; backend helpers live in `inc/functions.backend.inc.php` and `inc/functions.global.inc.php`.

## Configuration

All shop settings are stored per key via `_setConfig(key, value, 'module_shop')` in `phpwcms_sysvalue` and edited on the Preferences tab (`inc/edit.preferences.inc.php`, processing `inc/processing.preferences.inc.php`):

| Setting / key | Type | Default | Description |
| ------------- | ---- | ------- | ----------- |
| `shop_pref_felang` | checkbox | off | Frontend language support — adds per-language product records and cart isolation |
| `shop_pref_currency` | text, max 10 | – | Currency symbol used in output |
| `shop_pref_unit_weight` | text, max 10 | `kg` | Weight unit suffix |
| `shop_pref_vat` | textarea | 0.00 / 7.00 / 19.00, one rate per line | VAT rate choices for product prices |
| `shop_pref_email_to` | email list | – | Recipients of new-order mails, multiple addresses separated by `;` |
| `shop_pref_email_from` | email | – | Sender address of order mails |
| `shop_pref_email_paypal` | email | – | Stored preference, no gateway code uses it (see [payment](03-shop-ordering.md#payment)) |
| `shop_pref_id_shop` | text | `0` | Page id (article id) or URL of the shop start, used for category and product links |
| `shop_pref_id_cart` | text | `0` | Page id (article id) or URL of the cart page |
| `shop_pref_shipping_calc` | radio | – | Shipping calculation: 0 = weight, 1 = price, 2 = distance (see [shipping](03-shop-ordering.md#shipping)) |
| `shop_pref_shipping_selfpickup` | checkbox | off | Enables self pickup at checkout |
| `shop_pref_shipping` | floats | – | Five tier rows for the selected shipping mode |
| `shop_pref_zone_base` | text, max 200 | – | Base address for distance calculation |
| `shop_pref_loworder` | checkbox + floats | off | Low order value surcharge: threshold, charge, VAT |
| `shop_pref_autosubtract_off` | checkbox | off | Disables inventory subtraction after an order |
| `shop_pref_discount` | checkbox + floats | off | Three discount tiers with percent, minimum order value, free shipping toggle |
| `shop_pref_payment` | checkboxes | on each | Enabled payment methods; accepted credit cards (default American Express, Mastercard, Visa) |
| `shop_pref_terms` + `shop_pref_terms_format` | textarea + radio | – | Terms and conditions text; format 0 = text, 1 = HTML |
| `shop_pref_api_access` + `shop_pref_api_key` | checkbox + text | off | JSON order API, see [ordering](03-shop-ordering.md#order-api) |

The only `phpwcms` configuration key related to the shop defaults the OpenGraph flag of new products: `$phpwcms['set_sociallink']['shop']` (default false, `include/config/dist.conf.inc.php` line 144).

## Shop content part (Plug-in, type 30)

The module provides content parts through the generic Plug-in content part 30 — see the bridging table on [Modules overview](index.md). A shop content part placement does not render content itself; it emits exactly one of the shop engine tags into the page, which `frontend.render.php` then executes:

| Content part action | Emits | Purpose |
| ------------------- | ----- | ------- |
| `categories` (default) | `{SHOP_CATEGORIES}` | Category menu of the whole tree |
| `category` | `{SHOP_CATEGORY:<id>}` | Category menu rooted at one category |
| `productlist` | `{SHOP_PRODUCTLIST}` | Product list or product detail |
| `order` | `{SHOP_ORDER_PROCESS}` | Cart and checkout page |
| `smallcart` | `{CART_SMALL}` | Compact cart summary |

The content part form offers the shop template selection (`template/default.html`, `en.html`, `de.html`), per-action options in an accordion, and a custom INI overrides textarea — documented on [Shop – product options](04-shop-options.md#content-part-options-and-accordion). When several shop content parts sit on one page, the first one defines the template and settings — the engine renders one template per page (`inc/cnt.article.php`).

## Related

- [Modules overview](index.md) — architecture, activation, groups
- [Shop – catalog management](02-shop-catalog.md) — products, categories, tables
- [Statistics](11-statistic.md) — module-independent reporting