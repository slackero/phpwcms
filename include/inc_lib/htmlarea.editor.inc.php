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


//WYSIWIG HTML EDITOR: HTMLAREA
//compatible: IE 5.5+ (Windows)/Mozilla 1.3 (all OS) cross platform

header('Content-Type: text/javascript');

?>
HTMLArea.loadPlugin("ContextMenu");
HTMLArea.loadPlugin("TableOperations");
HTMLArea.loadPlugin("CharacterMap");
//HTMLArea.loadPlugin("FullPage");
//HTMLArea.loadPlugin("ListType");

var editor = null;

function initEditor (edit_field, cw, ch) {
	var config = new HTMLArea.Config(); // create a new configuration object having all the default values
	if(cw == "") { cw = "440px"; }
	if(ch == "") { ch = "550px"; }
	config.width = cw;
	config.height = ch;
	
	// [	"fontname", "space", "fontsize", "space", "formatblock", "space" ],
	config.toolbar = [
		[	"fontname", "space", "fontsize", "space", "formatblock"],
		[	"bold", "italic", "underline", "separator", "strikethrough", "subscript", "superscript", "separator", 
			"copy", "cut", "paste", "space", "undo", "redo", "separator", "htmlmode", "separator",  "about" ],
		[	"lefttoright", "righttoleft", "separator", "justifyleft", "justifycenter", "justifyright", "justifyfull", "separator",
			"orderedlist", "unorderedlist", "outdent", "indent", "separator",
			"forecolor", "hilitecolor", "textindicator", "separator", "inserthorizontalrule", 
			"createlink", "insertimage", "inserttable" ]
	];
	
	editor = new HTMLArea(edit_field, config); // create an editor for the "ta" textbox
    editor.registerPlugin(ContextMenu);
    editor.registerPlugin(TableOperations);
	editor.registerPlugin(CharacterMap);
	//editor.config.pageStyle = "@import url(htmlarea_phpwcms.css);";
	//editor.registerPlugin(FullPage);
	//editor.registerPlugin(ListType);
	
	editor.generate();
	return false;
}
