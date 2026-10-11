---
title: Messaging
description: The Communication section — newsletter subscriptions, newsletter queue and subscribers.
sidebar_position: 5
---
# Messaging

> Source: `phpwcms.php` (do=messages dispatch), `include/inc_lib/checkmessage.inc.php`, `include/inc_tmpl/message.subscription.tmpl.php`, `include/inc_tmpl/newsletter.list.tmpl.php`, `include/inc_tmpl/message.subscribers.tmpl.php`

The sidebar item is labeled **Communication** (`be_nav_messages`). It appears only when `enable_backend_newsletter` is on in the configuration and the user belongs to the `nl` group; each of its three pages is additionally gated by the group members listed below. Opening any of these pages with the newsletter module disabled silently renders the dashboard instead.

The page dispatch additionally requires super admin rights (`usr_admin = 1`) — group membership decides visibility in the menu, but only admins see the pages themselves.

## Pages

| Page | URL | Group | Content |
| ---- | --- | ----- | ------- |
| Newsletter subscriptions | `do=messages&p=2` | `nlabo` | List of subscription lists with recipient counts; create, edit and delete lists (system lists refuse deletion). |
| Newsletter | `do=messages&p=3` | `nllist` | Overview of newsletter issues with publication date, last sending and total/queued/sent/opened recipient counts; create and edit issues, and start sending (`&send=1`, `newsletter.send.tmpl.php`) for issues that have a queue. |
| Newsletter subscribers | `do=messages&p=4` | `nlrecip` | Subscriber list; CSV import (`&import=1`, via `subscriberimport.form.tmpl.php` and a result page) and export (`include/inc_act/act_export.php?action=exportsubscriber`). |

The older *Message center* and *New message* pages (`p=0` / `p=1`, `message.center.tmpl.php`, `message.send.tmpl.php`) are commented out in `phpwcms.php` — internal messaging between backend accounts is currently not reachable through the navigation.

## Unread-message indicator

`include/inc_lib/checkmessage.inc.php` counts rows in `phpwcms_message` with `msg_read = 0` for the signed-in user and, when any exist, appends an envelope icon to the Communication nav entry. The counter renders on every backend page load; there is no message reading view behind it in the current dispatch.

## Form results

There is no backend view collecting frontend form submissions. The formmail [content part](../general/concepts.md#the-content-hierarchy) shows only its target/label summary in the content part stack (`include/inc_tmpl/content/cnt10.list.inc.php`); submitted data goes straight into the configured emails. For subscriber-driven mailings, use the newsletter pages above.