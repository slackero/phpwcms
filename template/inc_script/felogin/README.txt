Frontend Login workaround
=========================

This is a very basic set of some scripts to setup frontend login for 
multiple users and also multiple users for different site levels.

Keep the folder structure as is. Check name of your template folder. 
The default is "template" but maybe different for older releases.

The script might be compatibe with all releases of phpwcms are 
having support for built-in frontend users because hiding
levels is based on same techniques. But it's untested.

All setup is done in "felogin.ini.php" which is commented very well.

To enable login/logout form put the replacement tag {FELOGIN} in the
article. Best is to use HTML content part.

Other replacement tags are:
- {FELOGIN_USER}     - login name of the user currently logged-in
- {FELOGIN_ERROR}    - login form eror messages
- {FELOGOUT_PREFIX}
- {FELOGOUT_SUFFIX}

If you want to display additional information on a page about login
status (logged in/logged out) use a simple script which checks against
the PHP constant FELOGIN_IS_LOGGED.

Sample inline PHP script:
[PHP]
if(defined('FELOGIN_IS_LOGGED') && FELOGIN_IS_LOGGED) {
	echo 'Hello {FELOGIN_USER}<br />';
	echo '<a href="index.php?id='.FELOGIN_LEVEL_ID.'&amp;logout='.FELOGIN_LOGOUT_GET_VALUE.'">Logout</a>';
} else {
	echo 'You are not logged in.';
}
[/PHP]

--
Copyright (c) 2008 Oliver Georgi <og@phpwcms.org>