<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

function auto_link($str) { 
  # don't use target if tail is follow 
  $regex['file'] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov"; 
  $regex['file'] = "(\.($regex[file])\") TARGET=\"_blank\""; 

  # define URL ( include korean character set ) 
  $regex['http'] = "(http|https|ftp|telnet|news|mms):\/\/(([\xA1-\xFEa-z0-9:_\-]+\.[\xA1-\xFEa-z0-9:;&#=_~%\[\]\?\/\.\,\+\-]+)([\.]*[\/a-z0-9\[\]]|=[\xA1-\xFE]+))"; 

  # define E-mail address ( include korean character set ) 
  $regex['mail'] = "([\xA1-\xFEa-z0-9_\.\-]+)@([\xA1-\xFEa-z0-9_\-]+\.[\xA1-\xFEa-z0-9\-\._\-]+[\.]*[a-z0-9]\??[\xA1-\xFEa-z0-9=]*)"; 

  # If use "wrap=hard" option in TEXTAREA tag, 
  # connected link tag that devided sevral lines 
  $src[] = "/<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>/i"; 
  $tar[] = "<\\1\\2\\3>"; 
  $src[] = "/<([^<>\n]*)\n([^\n<>]*)>/i"; 
  $tar[] = "<\\1\\2>"; 
  $src[] = "/<(A|IMG)[^>]*(HREF|SRC)[^=]*=[ '\"\n]*($regex[http]|mailto:$regex[mail])[^>]*>/i"; 
  $tar[] = "<\\1 \\2=\"\\3\">"; 

  # replaceed @ charactor include email form in URL 
  $src[] = "/(http|https|ftp|telnet|news|mms):\/\/([^ \n@]+)@/i"; 
  $tar[] = "\\1://\\2_HTTPAT_\\3"; 

  # replaced special char and delete target 
  # and protected link when use html link code 
  $src[] = "/&(quot|gt|lt)/i"; 
  $tar[] = "!\\1"; 
  $src[] = "/<a([^>]*)href=[\"' ]*($regex[http])[\"']*[^>]*>/i"; 
  $tar[] = "<A\\1HREF=\"\\3_orig://\\4\" TARGET=\"_blank\">"; 
  $src[] = "/href=[\"' ]*mailto:($regex[mail])[\"']*>/i"; 
  $tar[] = "HREF=\"mailto:\\2#-#\\3\">"; 
  $src[] = "/<([^>]*)(background|codebase|src)[ \n]*=[\n\"' ]*($regex[http])[\"']*/i"; 
  $tar[] = "<\\1\\2=\"\\4_orig://\\5\""; 

  # auto linked url and email address that unlinked 
  $src[] = "/((SRC|HREF|BASE|GROUND)[ ]*=[ ]*|[^=]|^)($regex[http])/i"; 
  $tar[] = "\\1<A HREF=\"\\3\" TARGET=\"_blank\">\\3</a>"; 
  $src[] = "/($regex[mail])/i"; 
  $tar[] = "<A HREF=\"mailto:\\1\">\\1</a>"; 
  $src[] = "/<A HREF=[^>]+>(<A HREF=[^>]+>)/i"; 
  $tar[] = "\\1"; 
  $src[] = "/<\/A><\/A>/i"; 
  $tar[] = "</A>"; 

  # restored code that replaced for protection 
  $src[] = "/!(quot|gt|lt)/i"; 
  $tar[] = "&\\1"; 
  $src[] = "/(http|https|ftp|telnet|news|mms)_orig/i"; 
  $tar[] = "\\1"; 
  $src[] = "'#-#'"; 
  $tar[] = "@"; 
  $src[] = "/$regex[file]/i"; 
  $tar[] = "\\1"; 

  # restored @ charactor include Email form in URL 
  $src[] = "/_HTTPAT_/"; 
  $tar[] = "@"; 

  # put border value 0 in IMG tag 
  $src[] = "/<(IMG SRC=\"[^\"]+\")>/i"; 
  $tar[] = "<\\1 BORDER=0>"; 

  # If not MSIE, disable embed tag 
  if(!ereg("MSIE", $_SERVER['HTTP_USER_AGENT'])) {
    $src[] = "/<embed/i"; 
    $tar[] = "&lt;embed"; 
  } 
   
  $str = preg_replace($src,$tar,$str); 
  return $str; 
}

?>