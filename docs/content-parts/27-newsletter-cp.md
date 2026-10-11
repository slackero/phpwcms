---
title: Newsletter
description: Subscription form that registers visitors for newsletter lists with a double opt-in verification mail.
sidebar_position: 27
---
# Newsletter (content part 12)

> Source: `include/inc_lib/content/cnt12.readform.inc.php`, `include/inc_front/content/cnt12.article.inc.php`, `include/inc_tmpl/content/cnt12.inc.php`, `verify.php`

The subscription form of the newsletter system, labeled `Newsletter`. Visitors pick one or more lists, phpwcms writes or updates the address record with a new random key, mails a verification link and prints a success text. Verified addresses are consumed by the newsletter module; this CP only handles subscribing and address updates.

## Purpose

Double opt-in subscription. Posting an already known address resets `address_verified` to 0 and re-sends the verification mail, so the subscriber confirms lists again after a change. Verification and deletion endpoints live in the site root `verify.php`.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt12.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Subscription | option transfer | — | Left list = offered subscriptions, right = available ones: every active entry of `phpwcms_subscription` plus entry `0` = all subscriptions. Stored as subscription ID list. |
| Label email | text, max 100 | `@@email:@@` | Form label for the email input. |
| Alignment | select | Default | `0` default, `1` left float, `2` center, `3` right float; class comes from `template_default['classes']['newsletter-table']`. |
| Label name | text, max 100 | `@@name:@@` | Form label for the optional name input. |
| Button text | text, max 50 | `@@Subscribe@@` | Submit label. |
| Label subscriptions | text, max 100 | `@@subscribe to:@@` | Fieldset legend for the subscription checkboxes. |
| All subscriptions | text, max 50 | `@@subscribe to all@@` | Label of the "all" checkbox. |
| Info text | textarea | — | Intro paragraph above the form. |
| Success text | textarea | — | Shown after a valid post; `{NEWSLETTER_EMAIL}` is replaced. Without it a generic confirmation is printed. |
| URL 1 | text | — | Redirect target after the subscriber verifies the address (`verify.php?s=…`). Stored per address. |
| URL 2 | text | — | Redirect target after the subscriber deletes the address (`verify.php?u=…`). |
| reCAPTCHA | select | Off | `Off`, `Invisible reCAPTCHA`, `reCAPTCHA v2`. |
| Settings | textarea, INI | `site_key = ` etc. | `site_key=`, `secret_key=`, `type=image\|audio`, `lang=`; v2 adds `theme=dark\|light` and `size=normal\|compact`, invisible adds `badge=bottomright\|bottomleft\|inline`. Invalid values fall back to the defaults. |
| Registration mail | textarea | built-in | Verification mail for new addresses. |
| Logoff mail | textarea | built-in | Verification mail for existing addresses (updates). |
| Change mail | textarea | built-in | Same as registration mail fallback. |

Empty mail texts fall back to a built-in template that asks the subscriber to verify `{NEWSLETTER_EMAIL}` and links `{NEWSLETTER_VERIFY}` / `{NEWSLETTER_DELETE}`, signed with `SMTP_FROM_NAME` and `admin_email`.

## Rendering

The form is hard-coded, id `subscribe-newsletter-form<CP id>`, posts `newsletter_email`, `newsletter_name` and `email_subscription[<id>]` checkboxes. With exactly one subscription the CP emits one hidden field instead of checkboxes. Validation failures print `formError` paragraphs: subscription not chosen, reCAPTCHA error (`@@recaptcha-error:<code>@@`) or mail send error. Success prints the success text with `{NEWSLETTER_EMAIL}` replaced.

### Verification mail tags

| Tag | Meaning |
| --- | ------- |
| `{NEWSLETTER_NAME}` | Subscriber name. |
| `{NEWSLETTER_EMAIL}` | Subscriber address. |
| `{NEWSLETTER_VERIFY}` | `verify.php?s=<key>` — sets `address_verified=1` and redirects to URL 1 when set. |
| `{NEWSLETTER_DELETE}` | `verify.php?u=<key>` — deletes the address row and redirects to URL 2 when set. |
| `{IP}` `{DATE:<format>}` | Global tags from `replaceGlobalRT()`. |
| `[SUBJECT]…[/SUBJECT]` | Subject inside the mail text; default `Newsletter verification for <site>`. |

Verification pages render `template/inc_default/subscribe.tmpl` and `unsubscribe.tmpl` with `{EMAIL}` and `{PHPWCMS_URL}` replaced; both pages set `robots noindex` and refresh back to the homepage after 10 seconds.

## Storage

- Configuration: `acontent_newsletter`, serialized.
- Subscribers: `phpwcms_address` (`address_key` = 32 hex chars from `random_bytes(16)`, `address_email`, `address_name`, `address_verified`, `address_subscription` serialized ID array, `address_url1`, `address_url2`). Updates log `log_message()` entries 1–4.
- Lists: `phpwcms_subscription` maintained in the newsletter module backend.

## Related

- Newsletter field inside a form: [Form (cp 23)](25-form.md).
- Address and subscription management: Modules section, newsletter module. See [Glossary](../modules/13-glossary.md#backend) for backend terms.