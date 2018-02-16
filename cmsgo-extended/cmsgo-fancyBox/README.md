cmsGo! FancyBox
===============

**cmsGo!** jQuery FancyBox is a replacement of the default Lightbox (SlimBox) integration enhanced by with Swipe support for touch devices. Swipe support options are enabled  when more than one fancyBox item is detected only.

Copyright (c) 2012-2016 Oliver Georgi — <info@pixels-points.ch>


### Components

**fancyBox** is a tool that offers a nice and elegant way to add zooming functionality for images, html content and multi-media on your webpages. http://www.fancyapps.com/fancybox/

**TouchSwipe** is a jquery plugin to be used with jQuery on touch input devices such as iPad, iPhone etc. http://labs.rampinteractive.co.uk/touchSwipe/

---

### Installation

Download related files and place the content of the folder **template** into the template folder of your **cmsGo!** installation.


### Requires

**cmsGo!** with jQuery support Version 1.6 (recommend jQuery 1.12.4) or newer.


### Configuration

The frontend render script has a single option to enable or disable Swipe support. If set to TRUE Swipe support will be enabled, FALSE will disable Swipe support.

	$cmsgo['fancybox_swipe_support'] = true;

To set **fancyBox** related options check the JavaScript files:

	jquery.fancybox.initSwipeOff.js  
	jquery.fancybox.initSwipeOn.js

There you can change or add the options as described in the [fancyBox documentation](http://fancyapps.com/fancybox/#docs).


### Bug tracker

Have a bug? Please email the issue to us.


### Thanks

That little enhancement for cmsGo! would not be possible without:

- **[fancyBox](http://fancyapps.com/fancybox)** Copyright (c) 2012 Janis Skarnelis - <janis@fancyapps.com>
- **[TouchSwipe](http://labs.rampinteractive.co.uk/touchSwipe/)** Copyright (c) 2010-2015 Matt Bryson

### Changelog

#### 29 Nov 2016
- touchSwipe 1.6.18
- FancyBox master taken (got some fixes)
- optimized jquery.fancybox.initSwipeOn.js

#### 6 Feb 2014
- touchSwipe 1.6.5
- move touchSwipe to seperate folder

#### 2 Jul 2013
- fancyBox v2.1.5

#### 7 Feb 2013
- fancyBox v2.1.4
- touchSwipe v1.6
- changed position of fancyBox init scripts
- optimisation
