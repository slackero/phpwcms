Clean Paste Plugin for SPAW Editor PHP Edition v.2
--------------------------------------------------
Hooks up to the paste event in Internet Explorer and (depending on cofiguration)
cleans content of the clipboard before pasting it into editing area. Uses the
same cleanup function as "Clean HTML" button in the core.
Note: works in MS Internet Explorer only, requires SPAW 2.0.4 or newer

Installation
------------
Just copy "cleanpaste" directory into "plugins" subdir of your SPAW v.2 
installation. SPAW Editor PHP Edition version 2.0.4 or higher required.

Configuration
-------------
Plugin could be configured to always perform cleanup, perform it selectively or
never cleanup pasted code (disables plugin). To set one of these options set
config setting PG_CLEANPASTE_CLEAN to 'always', 'selective' or 'never'.

'selective' mode is controlled by (javascript) regular expression stored in 
PG_CLEANPASTE_PATTERN config setting. Default regex tries to determine content
pasted from MS Word. You can modify it according to your needs. If pasted content
matches the regex then cleanup is executed, otherwise content is pasted unchanged.

Copyright
---------
This plugin is (c)2007 by UAB Solmetra.
It is released under terms of GNU General Public License (see license.txt) in
"docs" subdirectory

Commercial SPAW license owners can use this plugin free of charge under the terms
of their respective license.
