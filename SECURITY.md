# Security Policy

## Supported Versions

I try to support all versions of **phpwcms** with security fixes if it can be easily backported, though it is
recommended to update to a current version whenever possible.

On a regular basis, the following versions of **phpwcms** are supported:

| Version            | Supported          |
|--------------------|--------------------|
| &gt;= 2.0.0-beta.1 | :white_check_mark: |
| &gt;= 1.12.4       | :white_check_mark: |
| &gt;= 1.9.50       | :white_check_mark: |
| &lt; 1.9.50        | :x:                |

`1.9.x` is the legacy line and receives security fixes only. `2.0.x` is a pre-release line; treat the
supported version above as the minimum, and always run the latest available `2.0.0-beta` release.

## Scope: Serious Vulnerabilities Only

Only **real, demonstrable, and exploitable** security vulnerabilities are accepted as security reports, specifically issues that enable:

- **Denial of Service (DoS / DDoS)**: Unauthenticated resource exhaustion, amplification, or crash vectors that meaningfully degrade or take down **phpwcms**.
- **Account Takeover & Access Control Bypass**: Authentication bypass, session hijacking, 2FA bypass, privilege escalation, or unauthorized access to backend or protected frontend accounts.
- **Breaking Into the System**: Remote Code Execution (RCE), SQL Injection, arbitrary file read/write/upload/deletion, Server-Side Request Forgery (SSRF), or bypassing private storage and filesystem protections.
- **Abuse / Misuse of phpwcms**: Flaws that allow attackers to misuse **phpwcms** itself (for example: unauthenticated mail/spam relay, unauthorized data exfiltration, unauthenticated/public-input stored XSS leading to backend/session compromise, or CSRF against critical state-changing actions with demonstrated impact).

### Trust Model & Expected CMS Behavior

**phpwcms** is a Content Management System where authenticated backend users are trusted within their configured permissions. Backend editors and administrators are intentionally allowed to author raw HTML, JavaScript, templates, and other powerful content or configuration options that may look dangerous to automated scanners. That is **expected behavior** and acceptable in many areas of **phpwcms** — it is **not** a vulnerability unless an unprivileged user or attacker can bypass permission boundaries or break into the system.

## Out of Scope & Automated / AI-Generated Noise

To protect maintainer time, low-signal reports and automated noise will be **closed immediately without response**:

- **AI-Generated & Automated Scanner Reports**: Reports drafted by LLMs/AI agents, generic boilerplate write-ups, or unverified SAST/DAST/scanner dumps without manual verification and a working Proof of Concept (PoC).
- **Intended Backend User Capabilities**: Reports claiming "XSS", "HTML/JS injection", or "arbitrary content/file changes" performed by an authenticated backend user who already has permission to edit HTML, JavaScript, templates, content parts, or files.
- **Theoretical or Non-Exploitable Findings**: Speculative "could potentially" claims, missing HTTP security headers, clickjacking on non-sensitive pages, self-XSS, version/path/stack-trace disclosures without proven exploitability, or missing rate-limiting/password-policy preferences without a demonstrated takeover or DoS.
- **Host & Web Server Misconfiguration**: Issues resulting from improper server setup (for example, Apache running with `AllowOverride None` so `.htaccess` rules are ignored, or insecure filesystem permissions).
- **Third-Party Dependency Alerts**: Generic CVE notifications for Composer or npm packages unless you demonstrate an actual, reachable exploit path within **phpwcms**.
- **Unsupported Versions**: Findings that only affect versions marked `:x:` above or outdated betas superseded by newer releases.

## Reporting a Vulnerability

Please report qualifying vulnerabilities **privately** — do not open a public issue for an exploitable security problem.

- **Preferred**: [private vulnerability report](https://github.com/slackero/phpwcms/security/advisories/new)
- **Alternative**: email to <og@phpwcms.org>

### Required Information

Every report must be written clearly and concisely by the researcher and include **all** of the following:

1. **Version & Component**: The exact supported **phpwcms** version (or current `v2-dev` commit), PHP version, and affected endpoint, module, or content part.
2. **Realistic Preconditions**: The exact setup and privilege level required (e.g., default installation with an unauthenticated visitor or a specific publicly reachable form).
3. **Verified Proof of Concept (PoC)**: Minimal, deterministic, step-by-step reproduction steps (exact HTTP requests, payloads, or minimal test script) verified by you against a clean installation.
4. **Demonstrated Impact**: Concrete evidence of the resulting DoS/DDoS, account takeover, system intrusion, or misuse of **phpwcms** — not hypothetical impact scenarios.

Reports missing a working PoC or consisting of unverified AI-generated analysis will be rejected without triage.

Please note that **phpwcms** is an independent open-source project and does **not** offer a bug bounty program or financial rewards.

Published advisories are listed at <https://github.com/slackero/phpwcms/security/advisories>.

For non-exploitable hardening ideas, missing defense-in-depth checks, or general bugs, please open a regular [GitHub issue](https://github.com/slackero/phpwcms/issues) or pull request instead of a security advisory.
