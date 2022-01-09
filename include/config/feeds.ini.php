; <?php /*

; phpwcms content management system
;
; @author Oliver Georgi <og@phpwcms.org>
; @copyright Copyright (c) 2002-2022, Oliver Georgi
; @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
; @link http://www.phpwcms.org

; Here are the basic settings for feeds like default feeds format and so on

[default]

title               = "Daily RSS feed"
description         = "This is the default RSS feeds of my site"
link                = "http://www.mysite.tld/"
syndicationURL      = ""                ; let empty to set it to SELF

imagesrc            = ""
imagetitle          = ""
imagelink           = ""                ; if not set will be the same as 'link'
imagedescription    = ""

useauthor           = 1                 ; 0 - no author tag, 1 - author tag
feedAuthor          = "editor@mysite.tld (Feed Editor)"
feedEmail           = "feed@mysite.tld"

timeZone            = "+01:00"          ; your local timezone, set to "" to disable or for GMT
cacheTTL            = 3600              ; if 0 no caching will be used otherwise these are seconds

structureID         = ""                    ; if empty it will return the list of all articles sort by date, or use an phpwcms alias as starting point
                                        ; you can also use structureID there seperated by ','
maxentries          = 10
encoding            = UTF-8     ;ISO-8859-1
defaultFormat       = RSS2.0            ; available: 0.91/RSS0.91, 1.0/RSS1.0, 2.0/RSS2.0, ATOM/ATOM0.3
filename            = "default_feed.xml"
orderBy             = "livedate"        ; possible: livedate, killdate, createdate, changedate
order               = DESC                  ; order ascending ASC, descending DESC (default) or random RAND


; */ echo 'Sorry this is no public file. Good bye!';
