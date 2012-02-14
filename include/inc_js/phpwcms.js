function login(fval) {
	if(fval.json.value == 2 && fval.form_password.value=='' && fval.form_loginname.value=='') {
		fval.submit();
		return true;
	} else {
		fval.json.value == 0;
	}
	if(fval.form_password.value && fval.form_loginname.value) {
		fval.md5pass.value = hex_md5(fval.form_password.value);
		fval.form_password.value = '';
		fval.json.value = 1;
		return true;
	} else {
		showLayer('jserr');
		return false;
	}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function clearText(thefield){
if (thefield.defaultValue==thefield.value)
thefield.value = ""
}

function confirmGoUrl(confirmtext, jumpurl) {
	if(confirm(confirmtext)) {location.href=jumpurl;}
}

function wordcount(s) {
	var formcontent=Trim(s);
	if(formcontent == "") {
		return 0;
	} else {
		formcontent=formcontent.split(" ");
		return formcontent.length;
	}
}

function LTrim(str) {
	var whitespace = new String(" \t\n\r");
	var s = new String(str);
	if (whitespace.indexOf(s.charAt(0)) != -1) {
		var j=0, i = s.length;
		while (j < i && whitespace.indexOf(s.charAt(j)) != -1) {
		j++; s = s.substring(j, i);
		}
	}
	return s;
}

function RTrim(str) {
	var whitespace = new String(" \t\n\r");
	var s = new String(str);
	if (whitespace.indexOf(s.charAt(s.length-1)) != -1) {
		var i = s.length - 1;
		while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1) {
			i--; s = s.substring(0, i+1);
		}
	}
	return s;
}

function Trim(str) {
	return RTrim(LTrim(str));
}

function set_chatlist(lines) {
	document.sendchatmessage.chatlist.value = lines;
	document.sendchatmessage.submit();
}

function flevPopupLink(){// v1.2
var v1=arguments,v2=window.open(v1[0],v1[1],v1[2]), v3=(v1.length>3)?v1[3]:false;if (v3){v2.focus();}document.MM_returnValue=false;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function tmt_winOpen(u,id,f,df){
	if(eval(id)==null||eval(id+".closed")){
	eval(id+"=window.open('"+u+"','"+id+"','"+f+"')");eval(id+".focus()");}
	else if(df){eval(id+".focus()");}
	else{eval(id+"=window.open('"+u+"','"+id+"','"+f+"')");eval(id+".focus()");}
}

function tmt_winControl(id,c){ 
	var d=eval(id)==null||eval(id+".closed"); if(!d){eval(id+"."+c);}
}

function get_cookie(Name) {
	var search = Name + "=";
	var returnvalue = "";
	if (document.cookie.length > 0) {
		offset = document.cookie.indexOf(search);
		// if cookie exists
		if (offset != -1) { 
			offset += search.length; // set index of beginning of value
			end = document.cookie.indexOf(";", offset); // set index of end of cookie value
			if (end == -1) end = document.cookie.length;
			returnvalue=unescape(document.cookie.substring(offset, end));
		}
	}
	return returnvalue;
}

function write_cookie(wert) {
	if(wert) {
		window.document.cookie="chatstring="+window.document.sendchatmessage.chatmsg.value;
	} else {
		window.document.cookie="chatstring=";
	}
}

function cut(objekt, laenge) {
	if(objekt.value.length > laenge) objekt.value=objekt.value.substr(0,laenge);
}

function changeImagePos(x,f) {
	if(!f) {
		document.articlecontent.cimage_pos.selectedIndex = x;
	} else {
		document.article.cimage_pos.selectedIndex = x;
	}
	for(i=0; i<=9; i++)	{
		if(i==x) {
			MM_swapImage('imgpos'+i,'','img/symbole/content_selected.gif',0);
		} else {
			MM_swapImage('imgpos'+i,'','img/leer.gif',0);
		}
	}
}

function changeImagePosMenu(f) {
	if(!f) {
		var x = document.articlecontent.cimage_pos.selectedIndex;
	} else {
		var x = document.article.cimage_pos.selectedIndex;
	}
	for(i=0; i<=9; i++)	{
		if(i==x) {
			MM_swapImage('imgpos'+i,'','img/symbole/content_selected.gif',0);
		} else {
			MM_swapImage('imgpos'+i,'','img/leer.gif',0);
		}
	}
	if(!f) {
		document.articlecontent.cimage_pos.focus();
	} else {
		document.article.cimage_pos.focus();
	}
}

function switchToggleFTP(field) {
	if(field.value=='0') {
		field.value='1';
	} else {
		field.value='0';
	}
	return parseInt(field.value);
}

function toggleAllFTP(field, proof) {
	for (i = 0; i < field.length; i++) {
		if(proof) {
			 field[i].checked = true;
		} else {
    		field[i].checked = false ;
		}
	}
}

function int_only(value) {
	value = parseInt(value);
	if(value<0) value = value * -1;
	return (value) ? value+"" : "";
}

function hideLayer(whichLayer) {
	toggleDisplayById(whichLayer, 'none');
}

function showLayer(whichLayer) {
	toggleDisplayById(whichLayer, '');
}

function toggleDisplayById(whichLayer, status) {
	if (document.getElementById) {
		document.getElementById(whichLayer).style.display = status;
	} else if (document.all) {
		document.all[whichLayer].style.display = status;
	} else if (document.layers) {
		document.layers[whichLayer].display = status;
	}
}

function doMapChange() {
	document.articlecontent.cmap_location_edited.value='1';
}

function subrstr(str,nbr) {
   return str.substr(str.length-nbr);
}

// for placing text at position
function setCursorPos (textObj) {
	if (textObj.createTextRange) {
		textObj.cursorPos = document.selection.createRange().duplicate();
	}
}
function insertAtCursorPos (textObj, textFieldValue) {
	textObj.focus();
	if(document.all){ 
		if (textObj.createTextRange && textObj.cursorPos) {
			var cursorPos = textObj.cursorPos;
			cursorPos.text = cursorPos.text.charAt(cursorPos.text.length - 1) == ' ' ? textFieldValue + ' ' : textFieldValue;
		} else {
			textObj.value = textObj.value+textFieldValue;
		}
	} else {
		if(textObj.setSelectionRange){
			var rangeStart = textObj.selectionStart;
			var rangeEnd = textObj.selectionEnd;
			var tempStr1 = textObj.value.substring(0,rangeStart);
			var tempStr2 = textObj.value.substring(rangeEnd);
			textObj.value = tempStr1 + textFieldValue + tempStr2;
		} else {
			alert("This version of Mozilla based browser does not support setSelectionRange");
		}
	}
}

//tmtC_winOpen
var imageBrowser;
var uploadWin;

// code copied from Solmetra
function growField (id, dir) {
  fld = getFieldById(id);
  if (dir == 'H') {
    fld.cols = fld.cols + 3;
  }
  else if (dir == 'V') {
    fld.rows = fld.rows + 3;
  }
  else if (dir == 'HV' || dir == 'VH') {
    fld.cols = fld.cols + 3;
    fld.rows = fld.rows + 3;
  }
  updateDimensions(fld);
  return true;
}

function contractField (id, dir) {
  fld = getFieldById(id);
  if (dir == 'H') {
    if (fld.cols > 3) {
      fld.cols = fld.cols - 3;
    }
  }
  else if (dir == 'V') {
    if (fld.rows > 3) {
      fld.rows = fld.rows - 3;
    }
  }
  else if (dir == 'HV' || dir == 'VH') {
    if (fld.cols > 3) {
      fld.cols = fld.cols - 3;
    }
    if (fld.rows > 3) {
      fld.rows = fld.rows - 3;
    }
  }
  updateDimensions(fld);
  return true;
}

function getFieldById (fld) {
  var thisdetail;
  if (document.getElementById && document.getElementById(fld) != null) {
    return document.getElementById(fld);
  }
  else if (document.layers && document.layers[fld] != null) {
    return document.layers[fld];
  }
  else if (document.all) {
    return document.all(fld);
  }
  else {
    return true;
  }
}

function getObjectById(fld) {
	if (document.getElementById && document.getElementById(fld) != null) {
		return document.getElementById(fld);
	} else if (document.layers && document.layers[fld] != null) {
		return document.layers[fld];
	} else if (document.all) {
		return document.all(fld);
	} else {
		return false;
	}
}

function updateDimensions (fld) {
  frm = fld.form;
  if(frm.elements[fld.name+'_cols']) {
 	frm.elements[fld.name+'_cols'].value = fld.cols;
  }
  if(frm.elements[fld.name+'_rows']) {
  	frm.elements[fld.name+'_rows'].value = fld.rows;
  }
  return true;
}

// -- end solmetra --

function switchDisabled(fld) {
	if(fld.disabled) {
		fld.disabled = false;
	} else {
		fld.disabled = true;
	}
	return false;
}

function enableStatusMessage(fld, showHide, text) {
	if (document.getElementById && document.getElementById(fld) != null) {
		obj = document.getElementById(fld);
	} else if (document.layers && document.layers[fld] != null) {
		obj = document.layers[fld];
	} else {
		return true;
	}
	if(text) {
		obj.innerHTML = text;
	}
	obj.style.display = (showHide == true) ? 'block' : 'none';
	return true;
}

function create_alias(str,encoding,ucfirst)
{
	var str = str.toUpperCase();
	str = str.toLowerCase();
	
	str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E5]/g,'a');
	str = str.replace(/[\u00E7]/g,'c');
	str = str.replace(/[\u00E8\u00E9\u00EA\u00EB]/g,'e');
	str = str.replace(/[\u00EC\u00ED\u00EE\u00EF]/g,'i');
	str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F8]/g,'o');
	str = str.replace(/[\u00F9\u00FA\u00FB]/g,'u');
	str = str.replace(/[\u00FD\u00FF]/g,'y');
	str = str.replace(/[\u00F1]/g,'n');
	str = str.replace(/[\u0153\u00F6]/g,'oe');
	str = str.replace(/[\u00E6\u00E4]/g,'ae');
	str = str.replace(/[\u00DF]/g,'ss');
	str = str.replace(/[\u00FC]/g,'ue');

	str = str.replace(/\s+/g,'-');
	str = str.replace(/-+\/+-+/g,'/');
	if(aliasAllowSlashes) {
		str = str.replace(/[^a-z0-9_\-\/]+/g,'');
	} else {
		str = str.replace(/[^a-z0-9_\-]+/g,'');
	}
	str = str.replace(/\-+/g,'-');
	str = str.replace(/\/+/g,'/');
	str = str.replace(/_+/g,'_');
	str = str.replace(/^-+|-+$/g, '');
	str = str.replace(/^\/+|\/+$/g, '');
	str = str.replace(/^-+|-+$/g, '');

	if (ucfirst == 1) {
		c = str.charAt(0);
		str = c.toUpperCase()+str.slice(1);
	}

	return str;
}
var temp_url = '';
function openFileBrowser(url) {
	if(url != null && url != '') {
		if(window.imageBrowser && temp_url != url) {
			tmt_winControl('imageBrowser','close()');	
		}
		tmt_winOpen(url,'imageBrowser','width=450,height=450,scrollbars=yes,resizable=yes',1);
		temp_url = url;
	}
}

function set_article_alias(onempty_only, alias_type) {
	if(alias_type == 'struct') {
		var alias_basis		= 'acat_name';
		var alias_target	= 'acat_alias';
	} else {
		var alias_basis		= 'article_title';
		var alias_target	= 'article_alias';
	}
	var aalias = getObjectById(alias_target);
	if(onempty_only && aalias.value != '') return false;
	var atitle = getObjectById(alias_basis)
	aalias.value = create_alias(atitle.value);
	return false;
}