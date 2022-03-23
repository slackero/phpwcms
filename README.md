[![phpwcms](https://www.phpwcms.org/indeximg/phpwcms-logo.svg)](https://www.phpwcms.org)
=========

**phpwcms** is a very flexible, fast, robust, customer and developer friendly
but yet powerful web based content management system and cms framework running
under PHP and MySQL/MariaDB. phpwcms is created and maintained by
[Oliver Georgi](http://twitter.com/slackero).

To get started, checkout [phpwcms.org](https://www.phpwcms.org) or the community driven
[HowTo Wiki](https://wiki.phpwcms.org/) (snapshot). Most questions are yet
answered on the [phpwcms support forum](https://forum.phpwcms.org).


Quick start
-----------

Stable releases can be used by cloning the repository, `git clone git://github.com/slackero/phpwcms.git` or
[download the archive](https://github.com/slackero/phpwcms/releases).
To start with the latest development version use `git clone -b v1.9-dev git://github.com/slackero/phpwcms.git` or
[download the archive](https://github.com/slackero/phpwcms/archive/refs/heads/v1.9-dev.zip).
If downloaded instead of `git clone`, unarchive and copy the files to your web document root or sub folder.
Link your browser to the related URL and follow the install instructions.


Server system requirements
--------------------------

**phpwcms** version 1.9.33 requires a web server with PHP (minimum version 5.5 up to 8.x)
and a MySQL/MariaDB database (minimum version 5.1, recommend 5.5+).


Known problems
--------------

Because of the project history there are several probable problems regarding the database. 
MySQL changed the time and date related default values over the last years. Check to setup
the related config values to connect to the database in a more compatible way. MySQL Strict
is no good option. I work on this to [solve the problems](https://github.com/slackero/phpwcms/issues/275)
soon.


Bug tracker
-----------

Did you find a bug? Please create an **[issue here](https://github.com/slackero/phpwcms/issues)** on GitHub
that conforms with [necolas's guidelines](https://github.com/necolas/issue-guidelines).

In case it's a vulnerability maybe [huntr.dev](https://huntr.dev) is an option:  
[![huntr](https://cdn.huntr.dev/huntr_security_badge_mono.svg)](https://huntr.dev).


Share with us
-------------

Keep up to date on announcements and more by following **phpwcms** on Twitter
[@phpwcms](https://twitter.com/phpwcms) or on
[Facebook](https://www.facebook.com/pages/phpwcms/162275020999).
*(We are no longer active there, better you stay in touch with **phpwcms** on
[Github](https://github.com/slackero/phpwcms))*


Creator
-------

**Oliver Georgi**

- <https://github.com/slackero>
- <https://webverbund.de>
- <https://www.linkedin.com/in/olivergeorgi>
- <https://twitter.com/slackero>


Copyright and license
---------------------

Copyright 2002-2022 [Oliver Georgi](mailto:og@phpwcms.org?subject=phpwcms)

Licensed under the GNU General Public License, Version 2 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:

   <https://opensource.org/licenses/GPL-2.0>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the
    Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston,
    MA 02110-1301, USA.
