<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


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
