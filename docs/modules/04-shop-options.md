---
title: Shop options
description: Product options engine of the shop module with option types, price modifiers and content part options.
sidebar_position: 4
---
# Shop options

> Source: `include/inc_module/mod_shop/inc/functions.global.inc.php`, `frontend.render.php`, `inc/cnt.form.php`

Product options of the [shop module](01-shop.md) exist in two dimensions — option 1 and option 2 — defined as plain lines in the product form, priced and rendered as dropdowns, and extended by per-action options on the shop content part.

## Defining product options

> Source: `inc/edit.products.inc.php`, `inc/processing.products.inc.php`

Option 1 stores in `shopprod_size`, option 2 in `shopprod_color` — both textareas on the product form. Each holds one option per line:

- The **first line** is the dropdown title (for example "Size").
- Every following line defines a selectable value in the format `Description|price|ADD`, described below.
- Once an option list has more than three lines, the values are sorted naturally; the title line stays first.

Both dimensions render as a single `<select>` dropdown each — no checkbox, radio or free-text option types exist. When a product defines options, the cart requires a selection (a product without option lines skips the selects).

### Option line format

> Source: `inc/functions.global.inc.php` (`get_shop_option_value()`)

```text
Description|price|ADD
```

| Part | Contents |
| ---- | -------- |
| `Description` | Option label shown in the dropdown |
| `price` | Price modifier, optional: `-50.00` subtracts from the base price, `=99.00` replaces the base price, any other value adds to it. Empty price = no modification (surcharge display controlled by the `price_option_null` template CONFIG key) |
| `ADD` | Optional string appended to the product order number for the cart position, for example `XL` |

Price parsing is lenient: commas become decimal points, thousands dots are stripped, remaining non-numeric characters removed.

### Rendered output

The cart form renders `prod_opt1` / `prod_opt2` selects; each option element carries `data-type` and `data-price` attributes, and the enclosing form holds a `data-prices` JSON (net / gross / VAT / weight per variant) that client side code uses to display the recalculated price. Relevant template CONFIG keys on the shop template (or content part custom INI):

| Key | Purpose |
| --- | ------- |
| `price_option_null` | Show options with zero price |
| `price_option_prefix` / `price_option_suffix` | Markup around the shown option surcharge (default `, ` and ` {$}` — space plus currency symbol) |
| `price_option_hide` | Never show option surcharges |
| `class_product_option_1` / `class_product_option_2` | CSS classes for the selects |
| `product_option_1_prefix` / `product_option_1_suffix` / `product_option_1_required` (same for option 2) | Wrapper markup and required flag around the option selects |
| `amount_input_position` | Position of the quantity input relative to the option blocks: `before`, `between`, `after` |
| `class_form_product_cart_option` | Cart form class |
| `class_request_form`, `class_request_button`, `class_cart_add_button` | Request/cart form and button classes |

The template marker `<!-- SHOW-AMOUNT -->` inside the `[CART_ADD]` block turns the quantity field into a visible text input instead of the default hidden input.

### Cart integration

The chosen options change the cart position:

- The option price modifier recalculates the price (option resolve happens again on the order display, so `=` still replaces the base price there).
- The `ADD` suffix appends to the order number of the cart position (`inc/cart.items.inc.php`).
- Options add to the session key structure `products[product][option1][option2]`, so each variant is its own cart position — see [Shop ordering](03-shop-ordering.md#cart-mechanics).

### On request products

A product flagged on request replaces the cart form with a request link: the link URL supports the placeholders `{PRODUCT}` (product name) and `{NUM}` (order number); `[ON_REQUEST]...[ON_REQUEST_ELSE]...[/ON_REQUEST]` and `[ON_REQUEST_LINK]` switch template sections. A product also switches to request mode when its computed net price equals the template CONFIG value `on_request_trigger` (`frontend.render.php`), a deliberate price threshold style.

## Content part options and accordion

> Source: `inc/cnt.form.php`, `inc/cnt.post.php`, `inc/cnt.sql.php`

The shop [content part](01-shop.md#shop-content-part-plug-in-type-30) form selects the action and carries per-action options, grouped in a Bootstrap accordion whose panels show or hide with the selected action (groups marked with `data-shop-action`):

> **Note:** Since 2.0, this form carries the action-specific options accordion and the custom INI overrides panel (added in commit c25dbef89).

| Action | Options in the panel |
| ------ | -------------------- |
| `categories`, `category` | `cat_all` — "all products" item label (default `@@All products@@`); `cat_all_pos` — position `bottom` (default), `top`, `none`; `cat_count_products` — enable product counts in the menu; `cat_list_products` — render a product list inside the menu |
| `category` | Category picker selecting the menu root |
| `productlist` | `cat_list_sort_by` — product sort whitelist (`shopprod_name1`, `shopprod_ordernumber ASC/DESC`, `shopprod_created DESC/ASC`, `shopprod_track_view DESC`); `image_list_width`, `image_list_height`, `image_list_crop`, `image_list_lightbox` — list image sizing |
| `order` | `order_number_style` — order number format, see [order page](03-shop-ordering.md#checkout-flow) |
| `smallcart` | `cart_url` — target link of the small cart |

Additional fields on the form:

- **Template select** — overrides the shop template file (`default.html`, `en.html`, `de.html` shipped in `include/inc_module/mod_shop/template/`).
- **Custom INI overrides** (`shop_config`) — a textarea merged over the CONFIG block of the chosen template at render time (`frontend.render.php`); the form displays the template's CONFIG block as reference.

The content part saves its settings serialized in the article content part row (`acontent_form`); the frontend content part emits only its replacement tag — see the tag table on the [overview page](01-shop.md#shop-content-part-plug-in-type-30).

## Related

- [Shop](01-shop.md) — activation, preferences, content part overview
- [Shop catalog](02-shop-catalog.md) — product form
- [Shop ordering](03-shop-ordering.md) — cart positions, price calculation