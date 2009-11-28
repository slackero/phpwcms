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
if (thefield.defaultValue==thefield.value) thefield.value = "";
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function int_only(value) {
	value = parseInt(value);
	if(value<0) value = value * -1;
	return (value) ? value+"" : "";
}




function BookMark_Page(alerttext) {
	var title = document.title;
	var url = this.location;
	if (window.sidebar) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(title, url,"");
	} else if( window.external ) { // IE Favorite
		window.external.AddFavorite( url, title);
	} else {
		if(!alerttext) alerttext = "To bookmark this page use [Ctrl+D]";
		alert(alerttext);	
	}
	return false;
}

var ie4 = document.all ? 1 : 0;
var ns4 = document.layers ? 1 : 0;
var ns6 = window.netscape ? 1 : 0;
function addText(id,text) {
	
	menuobj = (ie4) ? document.all[id] : (ns6 ? document.getElementById(id) : (ns4 ? document.layers[id] : ''));
	
	if(ie4 || ns6) {
		menuobj.innerHTML=text;
	} else {
		menuobj.document.open();
		menuobj.document.write(text);
		menuobj.document.close();
	}
	
}

function MM_displayStatusMsg(msgStr) { //v1.0
  status=msgStr;
  document.MM_returnValue = true;
}

var clickZoomImage;
function clickZoom(url,imgname,windowstatus) {
	clickZoomImage=window.open(url,imgname,windowstatus);
	if (window.focus) {
		clickZoomImage.focus();
	}
}
function checkClickZoom() {
	if (clickZoomImage) {
		clickZoomImage.close();
	}
}

var layerDisplayStatus = new Array;
// switch layer visibility
function toggleLayerDisplay(whichLayer, status) {
	// store current layer status
	layerDisplayStatus[whichLayer] = status;
	// status: 'none', 'block'	
	if (document.getElementById) {
		// this is the way the standards work
		document.getElementById(whichLayer).style.display = status;
	}
	else if (document.all) {
		// this is the way old msie versions work
		document.all[whichLayer].style.display = status;
	}
	else if (document.layers) {
		// this is the way nn4 works
		document.layers[whichLayer].display = status;
	}
}

function toggleClassName(whichLayer, newClassName) {
	if (document.getElementById) {
		// this is the way the standards work
		document.getElementById(whichLayer).className = newClassName;
	}
	else if (document.all) {
		// this is the way old msie versions work
		document.all[whichLayer].className = newClassName;
	}
	else if (document.layers) {
		// this is the way nn4 works
		document.layers[whichLayer].className = newClassName;
	}
}

function mailtoLink(part1, part2) {	
	if(part1 && part2) {
		window.location.href="mailto:"+part1+"@"+part2;
		return true;
	}
	return false;		
}

function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			oldonload();
			func();
		}
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
