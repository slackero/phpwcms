=== Bad Behavior ===
Tags: comment,trackback,referrer,spam,robot,antispam
Contributors: MichaelHampton,MarkJaquith,FirasDurri,AndySkelton

Bad Behavior is a set of PHP scripts which prevents spambots and other
malicious accesses to your PHP-based Web site. It prevents comment spam,
trackback spam, guestbook spam, wiki spam, referrer spam, and some types
of malicious Web site hacking.

== Installation ==

Bad Behavior has been designed to install on each host software in the
manner most appropriate to each platform. It's usually sufficient to
follow the generic instructions for installing any plugin or extension
for your host software.

For complete documentation and installation instructions, please visit
http://www.homelandstupidity.us/software/bad-behavior/

== Release Notes ==

= Bad Behavior 2.0 Known Issues =

Bad Behavior does not work with GoDaddy hosting services. This is due to a
misconfigured reverse proxy which GoDaddy uses. GoDaddy has been notified
of the problem but refuses to fix it. Your best bet is to not use GoDaddy
web hosting services for any reason. For more information on this issue see
http://error.wordpress.com/2006/01/01/godaddy-sucks/

On WordPress when WP-Cache 2 is enabled, Bad Behavior requires a patch to
WP-Cache 2 in order to protect cached pages. The patch is available in
the installation instructions.

Bad Behavior may be unable to protect cached pages on MediaWiki.
