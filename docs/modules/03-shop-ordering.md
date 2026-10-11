---
title: Shop ordering
description: Cart, checkout, shipping, payment, order mails, order backend and order API of the shop module.
sidebar_position: 3
---
# Shop ordering

> Source: `include/inc_module/mod_shop/frontend.render.php`, `frontend.init.php`, `inc/cart.*.inc.php`, `inc/shipping.parse.inc.php`, `inc/processing.orders.inc.php`

Cart and checkout of the [shop module](01-shop.md).

## Frontend entry points

`frontend.render.php` engages the shop engine when the page contains any of the tags `{SHOP_CATEGORIES...}`, `{SHOP_PRODUCTLIST}`, `{CART_SMALL}` or `{SHOP_ORDER_PROCESS}`. Request parameters drive the views: `shop_cat` (category, optional `_sub` level via `5_12` style), `shop_detail` (product detail id) and `shop_cart=show` (display cart, clears cart errors). Category and product links point to the pages configured by `shop_pref_id_shop` / `shop_pref_id_cart`.

The small cart `{CART_SMALL}` renders wherever `{CART_SMALL}` appears — with `{CART_LINK}` to the cart page and `{COUNT}` for the item count. The count sums all positions of the session cart.

## Cart mechanics

> Source: `frontend.render.php`, `inc/cart.items.inc.php`

The cart lives in the session only — there is no cart table. Key `shopping_cart` (+ language suffix when `shop_pref_felang` is on) holds:

| Session entry | Contents |
| ------------- | -------- |
| `products[product][option1][option2]` | Quantity per product and option combination |
| `options1`, `options2` | Resolved option data per product |
| `total[product.option1.option2]` | Quantity per combination for the small cart count |
| `amount[product]` | Total quantity per product |
| `step1` | Invoice address data of the checkout |
| `payby`, `error`, `delivery_address`, `distance`, `distance_details`, `selfpickup` | Checkout state (see below) |

Adding runs through POST `shop_action=add` with `shop_prod_id` and `shop_prod_amount` plus the chosen `prod_opt1` / `prod_opt2` values. When the product defines options in both dimensions, an option selection is required before the cart accepts the product. Updating uses array POST `shop_prod_amount[product][option1][option2]`; `shop_cart_delete` empties the cart.

The cart template drives the buttons: `[UPDATE]...[/UPDATE]`, `[CHECKOUT]`, `[DELETE]` render as `<button>` submit controls for the respective actions (a body that already contains a form control stays untouched). `{COUNT}` inside the cart becomes the quantity input of the position.

## Checkout flow

The `{SHOP_ORDER_PROCESS}` page runs the flow in `frontend.render.php`:

1. Empty cart renders the `CART_NONE` section; otherwise the cart (step 0).
2. POST `shop_cart_checkout` opens the invoice address form (step 1): first and last name, street address data, zip, city, valid email, phone and the payment choice are validated; additional required fields `shop_field_N` (text fields, textareas, checkboxes) come from the template CONFIG.
3. POST `shop_order_step1` moves to the terms step (step 2): the terms text from `shop_pref_terms` (text or HTML per `shop_pref_terms_format`) shows and requires the `shop_terms_agree` confirmation.
4. POST `shop_order_submit` creates the order. The order number is either random (`generic_string(8,2)` in the `RANDOM` style) or a counter formatted by the configured sprintf pattern (template CONFIG `order_number_style`, sample `EN%05s`).
5. Success renders the `ORDER_DONE` section and subtracts inventory unless `shop_pref_autosubtract_off` is set; on failure the `ORDER_NOT_DONE` section shows with a mailto fallback.

Both order mails (below) are serialized into the order record and remain readable in the backend.

## Shipping

Three modes exist, selected by `shop_pref_shipping_calc`; each holds five tier rows edited on the preferences page — see [Shop](01-shop.md#configuration):

> Source: `inc/cart.items.inc.php`

| Mode | Threshold column | Fee columns | Behavior |
| ---- | ---------------- | ----------- | -------- |
| 0 — weight | `weight` (kg) | `net`, `vat` | Shipping fee by cart weight tier |
| 1 — price | `price` | `price_net`, `price_vat` | Fee by order subtotal tier |
| 2 — distance | `zone` (km) | `zone_net`, `zone_vat`, `zone_label` | Shipping by distance from the base address to the delivery address |

Distance mode calls the Google Maps Directions and Geocoding APIs via `file_get_contents` — PHP needs `allow_url_fopen` (the preferences form warns about this); foreign delivery countries are detected and affect the result. Template tags: `[SHIPPING_DISTANCE]`, `[SHIPPING_DISTANCE_ADDRESS]`, `{SHIPPING_DISTANCE:KM|M|MI|CITY|POSTCODE|COUNTRY|LABEL}`, and the conditional `[SHIPPING_DISTANCE:FOREIGN]`.

With `shop_pref_shipping_selfpickup` enabled, the `[SELFPICKUP]` checkbox lets visitors pick up the order; `shop_pref_discount.freeshipping_pickup` (default on) zeroes shipping costs when selected.

## Payment

> Source: `frontend.init.php` (`get_payment_options()`)

The checkout radio list offers exactly the four methods whitelisted in code: `prepay` (prepayment), `pod` (cash on delivery), `onbill` (on account), `cash`. Conditionals `[PAYBY_PREPAY]`, `[PAYBY_POD]`, `[PAYBY_ONBILL]`, `[PAYBY_CASH]` switch template sections; `{PAYMENT}` renders the chosen label.

The preferences store a PayPal email and a credit-card flag with accepted card types, and mails can be addressed with `shop_pref_email_paypal` — but no PayPal or credit-card gateway processing exists in `include/inc_module/mod_shop/`: the checkout accepts these data as stored preferences only. Any payment processing beyond prepayment/cash-on-delivery/on-account/cash would be integration work (webhook, IPN or external provider) outside this code.

## Pricing, VAT and discounts

> Source: `inc/cart.parse.inc.php`, `frontend.render.php`

- Product VAT uses `shopprod_vat`; when `shopprod_netgross` is 1 the entered price is gross and the net value derives from price ÷ (1 + VAT / 100).
- Three discount tiers (`shop_pref_discount`, `..._1`, `..._2`) each define percent, minimum order value and free shipping; the highest qualifying tier wins.
- A low order surcharge (`shop_pref_loworder`: threshold `under`, charge, VAT) applies below the threshold.

Summary tags in cart and checkouts templates:

| Tag | Contents |
| --- | -------- |
| `{SUBTOTAL_NET}`, `{SUBTOTAL_VAT}`, `{SUBTOTAL_GROSS}`, `{SUBTOTAL_WEIGHT}` | Cart position sums |
| `{SHIPPING_NET}`, `{SHIPPING_VAT}`, `{SHIPPING_GROSS}` | Shipping costs |
| `{DISCOUNT_NET}`, `{DISCOUNT_VAT}`, `{DISCOUNT_GROSS}` | Discount |
| `{LOWORDER_NET}`, `{LOWORDER_VAT}`, `{LOWORDER_GROSS}` | Low order surcharge |
| `{TOTAL_NET}`, `{TOTAL_VAT}`, `{TOTAL_GROSS}` | Final amounts (net + shipping + low order − discount) |
| `{CURRENCY_SYMBOL}` / `{$}`, `{WEIGHT_UNIT}` | Units |

Conditionals: `[DISCOUNT]`, `[LOWORDER]`, `[SHIPPING]`, `[SELFPICKUP]`, `[SELFPICKUP_FREE]`. Option price display and number formatting follow the template CONFIG (`price_option_null`, `price_option_prefix/suffix/hide`, `price/vat/weight_decimals`, `dec_point`, `thousands_sep`).

## Order mails

> Source: `frontend.render.php`, sections in `template/default.html`

Two mail sections ship in the shop template: `MAIL_CUSTOMER` (to the customer) and `MAIL_NEWORDER` (to the shop). Both render with the invoice address tags `{INV_*}`, `{EMAIL}`, `{PHONE}`, custom fields `{shop_field_N}`, the item markup `{ITEMS}` (built per position from the `MAIL_ITEM` section) and all summary tags above; payment conditionals switch method-specific paragraphs.

| Aspect | Customer mail | Shop mail |
| ------ | ------------- | --------- |
| Subject template CONFIG | `mail_customer_subject`, default `[#{ORDER}] Your order at MyShop` | `mail_neworder_subject`, default `[#{ORDER}] New order` |
| Recipient | Cart step 1 email address | First valid address of `shop_pref_email_to`; further addresses get copies with sender = customer name/email |
| Sender | `shop_pref_email_from`, fallback `$phpwcms['SMTP_FROM_EMAIL']` | Same |

## Order backend

> Source: `inc/listing.orders.inc.php`, `inc/show.orders.inc.php`, `inc/processing.orders.inc.php`

The Orders tab (`...&controller=order`) lists orders — the status values `ARCHIVED` and `CLOSED` are excluded — sorted by date descending. Columns: order number (with language flag when language support is on), date, customer, net, gross, payment. Actions: open detail, mailto the customer (subject from language key plus order number), delete. Delete only sets the status to `CLOSED` — order rows are never physically removed.

The order detail shows the invoice address including custom fields, the position table with option-resolved prices (a `=` price modifier replaces the base price again at display time), the sum rows for subtotal, shipping, discount and low order surcharge, and both stored mails. Status buttons switch flags `PAYED`, `SENT`, `RETURN`, `COMPLETED`; the status is stored as a dash-joined combination of these flags, initially `NEW-ORDER`.

## Order API

> Source: `api.shop.php` (site root)

The JSON order API activates with `shop_pref_api_access`; a request must pass GET/POST `api` matching `shop_pref_api_key` (auto-generated from URL + database password when enabled empty). Action `getorders` returns all orders with status `NEW-ORDER` as JSON — order ids obfuscated with `md5`, positions carrying option-resolved prices and the option/order-number basis split, plus custom fields — and converts output to UTF-8. The action `setstatus` is listed in the API grammar but not implemented.

## Storage

> Source: `setup/default_sql/phpwcms_init.sql` line 717

Orders live in `phpwcms_shop_orders`:

| Column | Type | Contents |
| ------ | ---- | -------- |
| `order_id` | int | Row id |
| `order_number` | varchar(20) | Order number, indexed with status |
| `order_date` | datetime | Order time |
| `order_name`, `order_firstname`, `order_email` | varchar(255) | Customer identity |
| `order_net`, `order_gross` | float | Totals |
| `order_payment` | varchar(255) | Payment method and data |
| `order_data` | mediumtext | Serialized order payload: positions, options, address, both mails |
| `order_status` | varchar(100) | Status combination (see above) |

## Related

- [Shop](01-shop.md) — activation, preferences, content part
- [Shop catalog](02-shop-catalog.md) — products, categories, tables
- [Shop product options](04-shop-options.md) — option engine, price modifiers