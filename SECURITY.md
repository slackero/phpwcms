# Security Policy

## Supported Versions

I try to support all versions of **phpwcms** with security fixes if it can be easily backported. But it's
recommend to update to a current version whenever possible.

On regular basis the following versions of **phpwcms** are supported:

| Version            | Supported          |
|--------------------| ------------------ |
| &gt;= 2.0.0-beta.1 | :white_check_mark: |
| &gt;= 1.12.4       | :white_check_mark: |
| &gt;= 1.9.50       | :white_check_mark: |
| &lt; 1.9.50        | :x:                |

`1.9.x` is the legacy line and receives security fixes only. `2.0.x` is a pre-release line; treat the
supported version above as the minimum, and always run the latest available `2.0.0-beta` release.

## Reporting a Vulnerability

Please report vulnerabilities privately — do not open a public issue for a security problem.

- Preferred: [private vulnerability report](https://github.com/slackero/phpwcms/security/advisories/new)
- Alternative: email to <og@phpwcms.org>

A useful report includes the affected **phpwcms** version, the component or content part involved, the
preconditions (for example "publicly reachable form with an upload field"), steps to reproduce, and the
impact you were able to demonstrate.

Published advisories are listed at
<https://github.com/slackero/phpwcms/security/advisories>.

Opening an [issue](https://github.com/slackero/phpwcms/issues) is fine for hardening ideas, missing
checks or other non-exploitable findings.
