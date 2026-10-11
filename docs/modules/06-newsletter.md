---
title: Newsletter
description: Core newsletter administration with subscriber lists, double opt-in, queue based sending and the subscription content part.
sidebar_position: 6
---
# Newsletter

> Source: `include/inc_act/`, `include/inc_front/content/cnt12.article.inc.php`, `include/inc_lib/backend.functions.inc.php`

The newsletter is not a module — it belongs to the core. It provides backend subscription management, newsletter creation and queue based sending (backend menu **Communication**), plus the self-registering subscribe form for the frontend as content part type 12 ("Newsletter"). Backend visibility requires `$phpwcms['enable_backend_newsletter']` (default true, `include/config/dist.conf.inc.php` line 165) and the usergroup `nl` group membership; its pages are admin-only (`$_SESSION['wcs_user_admin']`), and the send action additionally requires the `nl` or `adm` admin permission (`act_sendnewsletter.php`). There is no `{SUBSCRIBE}` replacement tag — subscription happens only through content part 12.

See [Modules overview](index.md) for how the menu is gated; the module group mechanism does not apply here.

## Backend

URL root: `phpwcms.php?do=messages`, pages by `p` parameter:

| Page | Contents |
| ---- | -------- |
| `p=2` Newsletter subscriptions | Subscription lists (`phpwcms_subscription`): add, edit, delete, active toggle; shows per-list recipient count via `countNewsletterRecipients()` |
| `p=3` Newsletter | Newsletter overview with paging; duplicate an entry; edit form; sending view |
| `p=4` Newsletter subscribers | Subscriber list from `phpwcms_address`: verify toggle, delete (removes duplicate emails by that address), edit form, CSV import |

### Newsletter form

> Source: `include/inc_tmpl/newsletter.form.tmpl.php` and the form processing

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Subject | text | – | Required |
| Publication date | date | – | Shown in the overview |
| Language | text | – | Newsletter language |
| Sender name | text | – | `from_name` |
| Sender email | text | – | Validated |
| Reply-to | text | – | Validated |
| HTML body | textarea | – | HTML email content |
| Text body | textarea | – | Plain text content |
| Template name | text | – | Optional template file, see [Sending](#sending) |
| Active | checkbox | off | Activation triggers queue building |
| Subscriptions | list select | all | Recipients limited to chosen subscription lists (0 = all) |

Saving serializes the form into `phpwcms_newsletter.newsletter_vars`.

### Sending

> Source: `include/inc_act/act_sendnewsletter.php`, `include/inc_tmpl/newsletter.send.tmpl.php`

Activating a newsletter builds the send queue: it takes verified addresses (`address_verified = 1`) matching the chosen subscription lists and inserts rows into `phpwcms_newsletterqueue` in chunks of 100. Queue states: 0 = unsent, 1 = sent, 2 = error (message stored in `queue_errormsg`), 3 = reset (never send; re-activating an existing newsletter resets old unsent rows to this state). Deactivating deletes unsent rows.

Actual sending is browser driven — there is no cron. The send view loads `act_sendnewsletter.php` in an iframe loop with `loop`/`pause` GET parameters; each batch marks `newsletter_lastsending`. A test-email mode (`send_testemail`, comma/semicolon separated list) sends without touching the queue. Confirmed sends join `phpwcms_address` with `phpwcms_newsletterqueue` (`queue_status = 0`, `queue_pid` = newsletter id) in batches, default loop size 25 or 500, using the `PhpwcmsMailer` with SMTP keep-alive.

Placeholders the sender resolves per recipient:

| Placeholder | Contents |
| ----------- | -------- |
| `###RECIPIENT_NAME###` / `###RECIPIENT_EMAIL###` | Subscriber data |
| `###SITE_URL###` | Site URL |
| `###VERIFY_LINK###` / `###DELETE_LINK###` | Verify / delete links of the subscription |
| `###OPENER###` | Tracking image `verify.php?o=<queue_id>` — increments `queue_opener` |

When the newsletter carries a template name, `template/inc_newsletter/<name>/newsletter.tmpl` is loaded. Its `<!--HTML-->` and `<!--TEXT-->` sections wrap `{CONTENT}` with the layout; relative URLs in the HTML body are made absolute. When a recipient has no HTML mail set up, the HTML body is converted to text.

### Subscribers and double opt-in

Subscribers are stored per email in `phpwcms_address` along with the serialization of their subscription choices. The opt-in flow:

1. The [content part 12](#content-part-12-newsletter-subscription) form posts email, name and per-list checkboxes.
2. The address row is inserted or updated with `address_verified = 0` and a random verification key; the registration mail links `{NEWSLETTER_VERIFY}` and `{NEWSLETTER_DELETE}`.
3. `verify.php` — the same script in the site root — processes the key: `s=` sets `address_verified = 1`, `u=` deletes the address row, `o=` counts the open.

Backend edits can set the verified state directly; the subscriber CSV import expects the email address in the first column.

## Content part 12 (Newsletter subscription)

> Source: `include/inc_tmpl/content/cnt12.inc.php`, `include/inc_front/content/cnt12.article.inc.php`

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Subscriptions | list select | all | Which lists the visitor can pick (0 = all) |
| Label overrides | text | – | Custom labels for email, name, subscription group and "all" entry |
| Button text | text | – | Submit button label |
| Table align | select | 0–3 | Positioning of the form table |
| Info text | textarea | – | Shown above the form |
| Success text | textarea | – | Shown after successful registration |
| URL 1 / URL 2 | text | – | Stored as address `url1` / `url2` |
| reCAPTCHA | select | off | 0 = off, 1 = checkbox, 2 = invisible; with config textarea `site_key = `, `secret_key = `, `type = image` |
| Mail texts | textareas | – | Custom registration / change of subscription mail body |
| Logoff text | textarea | – | Unsubscribe mail text |

The public form renders name and email inputs, the subscription checkboxes and the submit button. Mail texts accept the parser tags `{NEWSLETTER_NAME}`, `{NEWSLETTER_EMAIL}`, `{NEWSLETTER_VERIFY}`, `{NEWSLETTER_DELETE}`, `{IP}`, `{DATE:m/d/Y}` and the `[SUBJECT]...[/SUBJECT]` block. The verify/delete links point at the site root `verify.php` handled as described above.

## Storage

> Source: `setup/default_sql/phpwcms_init.sql` lines 4, 656, 670, 775

| Table | Columns | Contents |
| ----- | ------- | -------- |
| `phpwcms_address` | `address_id`, `address_key`, `address_email`, `address_name`, `address_verified`, `address_tstamp`, `address_subscription` (serialized, blob), `address_iddetail`, `address_url1`, `address_url2` | Subscribers |
| `phpwcms_newsletter` | `newsletter_id`, created, `newsletter_lastsending`, `newsletter_pub`, lang, subject, changed, `newsletter_vars` (mediumblob), trashed, active | Newsletter entries |
| `phpwcms_newsletterqueue` | `queue_id`, created, changed, `queue_status`, `queue_pid` (newsletter), `queue_rid` (address), `queue_errormsg`, `queue_opener` | Send queue |
| `phpwcms_subscription` | `subscription_id`, name, info (blob), active, lang, tstamp | Subscription lists |

Subscriber rows share the `phpwcms_address` table with the frontend login's user-details relationship only through `address_iddetail` — contact records of the [Addresses](09-addresses.md) module are unrelated.

## Configuration

| Key | Type | Default | Description |
| ---- | ---- | ------- | ----------- |
| `$phpwcms['enable_backend_newsletter']` | bool | true | Enables the Communication menu (`include/config/dist.conf.inc.php` line 165) |
| `$phpwcms['SMTP_*']` keys | text | – | Mail server settings used by the sender (same block, lines 170–185) |

## Related

- [Modules overview](index.md) — module architecture (newsletter is core, gated like a module menu)
- [Addresses](09-addresses.md) — distinct contact module with its own table
- [Statistics](11-statistic.md) — subscription and log overviews