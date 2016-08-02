<?php
/*

	Use this to overwrite the default "template_default" settings.
	It allows to set alternative behavior on a per structure level basis.
	The defaults are located here "include/config/conf.template_default.inc.php"

	Set only those values which should overwrite the defaults.
	There is no need to use the complete set of vars.
	Both arrays will be merged while frontend rendering process.

 */

// Have a look at the <body> Tag in the source view of your website
$template_default['body']['class'] = 'custom';

