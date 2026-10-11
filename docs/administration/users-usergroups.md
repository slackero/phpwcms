---
title: Users and usergroups
description: Backend users, frontend users, usergroups and how admin permissions are granted.
sidebar_position: 2
---
# Users and usergroups

> Source: `include/inc_tmpl/admin.newuser.tmpl.php`, `include/inc_tmpl/admin.edituser.tmpl.php`, `include/inc_tmpl/admin.groups.tmpl.php`, `include/inc_act/act_user.php`, `include/inc_act/act_usergroup.php`, `phpwcms.php` (admin navigation)

Users are accounts with login credentials; usergroups group users and serve two purposes: membership lists and permission bundles. The admin panel appears only to users with `usr_admin = 1`, and every admin sub-page is additionally gated by a named permission (`adm*` key).

## Users

### Backend vs frontend users

One user account can serve either the backend or the frontend area — the *frontend user* switch decides which login it belongs to. Frontend users authenticate against `{FELOGIN}`-protected structure levels; backend users get the backend UI. Accounts flagged both see the backend and can enter FE-protected areas.

### Creating / editing

Managed from Admin → Users. Fields (create + edit):

| Field | Description |
| ----- | ----------- |
| Login name | `usr_login`, unique. |
| Password | `usr_pass`; new password field only appears on edit. |
| Real name | `usr_name`. |
| Email | `usr_email`; edit offers "send verification email" and separate new-email handling with `form_reset_*` toggles. |
| Active | switch — inactive accounts (`usr_aktiv` != 1) cannot log in but stay listed. |
| Admin | switch — grants backend (`usr_admin = 1`). |
| Frontend user | switch — makes the account a frontend account (`usr_fe = 1`). |
| Allowed content parts | `allowed_cp[]` multi-select — restricts which content part types this user may insert. Empty = all. |

Deletion (`act_user.php`) moves an account out of circulation rather than hard-deleting rows.

## Usergroups

### Member groups (editable)

A regular group holds a name, description, active flag and a **member list** chosen from all users:

| Field | Storage | Meaning |
| ----- | ------- | ------- |
| Name | `group_name` | 1–250 chars, required. |
| Description | `group_value` | Free text. |
| Members | `group_member` | Comma-separated user IDs, picked from both BE/FE accounts. |
| Active | `group_active` | Off = ignored at runtime; toggled per-row in the overview list. |

Deactivating (eye icon) or deleting (trash icon, `act_usergroup.php`) never touches the users themselves.

Frontend login areas and structure-level access lists reference these groups: a protected level lists group IDs / user IDs under `acat_access`, and its `acat_feusers` counterpart holds the allowed accounts. See [Structure tree](../backend/02-structure-tree.md).

### Permission groups (SYSGROUP)

A second set of groups lives in `phpwcms_sysvalue` keyed as SYSGROUP entries with fixed `group_syskey` names. They are read-only in the group editor and define who may use admin panel areas. Admin users are auto-added to all SYSGROUPs at login (`phpwcms.php` ensures `usr_admin` members). The keys:

| Sysgroup key | Grants |
| ------------ | ------ |
| `admlayout` | Admin → Page layout |
| `admtempl` | Admin → Templates, Template translations |
| `admcustomcpt` | Admin → Custom content parts |
| `admmailtpl` | Admin → Email templates |
| `admuser` | Admin → Users |
| `admugroup` | Admin → Usergroups |
| `admialias` | Admin → Image aliases |
| `admfilecat` | Admin → File categories |
| `admalias` | Admin → Aliases |
| `admlink` | Admin → Links & redirects |

Super admins (`usr_admin`) additionally get System update and phpinfo. The check runs through `has_admin_permission($key)`.

### Module groups

Every installed module auto-creates a matching group on first visit to the groups page (`group_modkey = <module name>`, seeded with all superadmins as members). Backend module menu visibility is driven by group membership there; see [Modules overview](../modules/index.md).

## Admin panel reference

Nav gating shown below; page IDs (`p=`) match `phpwcms.php?do=admin&p=N`.

| Page | Permission |
| ---- | ---------- |
| Users (`p=0`) | `admuser` |
| Usergroups (`p=1`) | `admugroup` |
| File categories (`p=7`) | `admfilecat` |
| Page layout (`p=8`) | `admlayout` |
| Templates (`p=11`) | `admtempl` |
| Image aliases (`p=12`) | `admialias` |
| Aliases (`p=13`) | `admalias` |
| Links & redirects (`p=14`) | `admlink` |
| phpinfo (`p=15`) | super admin only |
| Custom content parts (`p=16`) | `admcustomcpt` |
| Template translations (`p=17`) | `admtempl` |
| System update (`p=18`) | super admin only |
| Email templates (`p=19`) | `admmailtpl` |

Plus two maintenance actions available with any admin permission: flush image cache and move deleted files.

## Related

- [Admin panel](admin-panel.md) — configuration entries and maintenance
- [Structure tree](../backend/02-structure-tree.md) — per-level access assignment