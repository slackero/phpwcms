// Opera specific code

// initialize
SpawEditor.prototype.initialize = function()
{
  this.document = document;
  if (!this.document)
  {
    setTimeout(this.name+'_obj.initialize();',50);
    return;
  }
  for(var i=0; i<this.pages.length; i++)
  {
    // execute only once
    if (!this.pages[i].initialized)
    {
      var pta = this.getPageInput(this.pages[i].name);
      var pdoc = this.getPageDoc(this.pages[i].name);
      
      try
      {
        if(pdoc.designMode != 'on')
          pdoc.designMode = 'on';
      }
      catch(e)
      {
  	    setTimeout(function(){try{this.getPageDoc(this.pages[i].name).designMode = 'on';}catch(e){}}, 20);
        setTimeout(this.name+'_obj.initialize();',50);
        return;
      }

      // utf-8 charset
      var c_set = pdoc.createElement("meta");
      c_set.setAttribute("http-equiv","Content-Type");
      c_set.setAttribute("content","text/html; charset=utf-8");
      // stylesheet
      var s_sheet = pdoc.createElement("link");
      s_sheet.setAttribute("rel","stylesheet");
      s_sheet.setAttribute("type","text/css");
      s_sheet.setAttribute("href",this.stylesheet);
      var head = pdoc.getElementsByTagName("head");
      if (!head || head.length == 0)
      {
        head = pdoc.createElement("head");
        pdoc.childNodes[0].insertBefore(head, pdoc.body);
      }
      else
      {
        head = head[0];
      }
      head.appendChild(c_set);
      head.appendChild(s_sheet);

      // direction
      pdoc.body.dir = this.pages[i].direction;

      // mark page as initialized
      this.pages[i].initialized = true;
      SpawEngine.setActiveEditor(this);

      this.updatePageDoc(this.pages[i]);
    }
  }

  // raise init event
  SpawEngine.handleEvent("spawinit", null, null, this.name);

  if (SpawEngine.isInitialized())
  {
    // add event handlers
    // context
    SpawEngine.addEventHandler("keyup",'SpawEditor.checkContext');
    SpawEngine.addEventHandler("mouseup",'SpawEditor.checkContext');
  
    // form submit
    SpawEngine.addEventHandler("submit",'SpawEngine.onSubmit', "form");

    // raise allinit event
    SpawEngine.handleEvent("spawallinit", null, null, null);
  }

  var frm = this.getPageInput(this.pages[0].name).form; 
  if (!frm.formSubmit)
  {
    frm.formSubmit = frm.submit;
    frm.submit = new Function(this.name+'_obj.spawSubmit();');
  }

  this.updateToolbar();
}

// returns reference to editors page iframe same as getPageIframeObject under Gecko
SpawEditor.prototype.getPageIframe = function(page_name)
{
  return this.getPageIframeObject(page_name);
}

// returns reference to content document of editors page
SpawEditor.prototype.getPageDoc = function(page_name)
{
  if (this.getPageIframe(page_name))
    return this.getPageIframe(page_name).contentDocument;
}

// insert node at selection
SpawEditor.prototype.insertNodeAtSelection = function(newNode)
{
  var pif = this.getPageIframe(this.getActivePage().name);
  var pdoc = this.getPageDoc(this.getActivePage().name);
  
  var sel = pif.contentWindow.getSelection();
  var rng = sel.getRangeAt(0);
  
  var container = rng.startContainer;
  var startpos = rng.startOffset;
  if (container && container.nodeType == 1 && container.tagName.toLowerCase() == 'html')
  {
    // workaround when inserting in the end of doc in opera
    container = pdoc.body;
    startpos = container.childNodes.length;
  }
  else
  {
    rng.deleteContents();
  }

  rng = pdoc.createRange();
  
  switch(container.nodeType)
  {
    case 3: // text node
      var txt = container.nodeValue;
      var afterTxt = txt.substring(startpos);
      container.nodeValue = txt.substring(0, startpos);
      if (container.nextSibling == null)
      {
        container.parentNode.appendChild(newNode);
        container.parentNode.appendChild(pdoc.createTextNode(afterTxt));
      }
      else
      {
        var afterNode = pdoc.createTextNode(afterTxt);
        container.parentNode.insertBefore(afterNode, container.nextSibling);
        container.parentNode.insertBefore(newNode, afterNode);
      }
      rng.setStart(container.parentNode.childNodes[1], 0);
      rng.setEnd(container.parentNode.childNodes[2], 0);
      break;
    default: // element node
      container.insertBefore(newNode, container.childNodes[startpos]);
      rng.setEnd(container.childNodes[startpos], 0);
      rng.setStart(container.childNodes[startpos], 0);
      break;
  }
  sel.removeAllRanges();
  //sel.addRange(rng);

  this.addGlyphs(pdoc.body);
}

// returns currently selected node
SpawEditor.prototype.getNodeAtSelection = function()
{
  var pif = this.getPageIframe(this.getActivePage().name);
  
  var sel = pif.contentWindow.getSelection();
  var rng = sel.getRangeAt(0);
  
  return rng.cloneContents();
}

// returns selection's parent element (closest current element)
SpawEditor.prototype.getSelectionParent = function()
{
  var result;

  var pif = this.getPageIframe(this.getActivePage().name);
  
  var sel = pif.contentWindow.getSelection();
  var rng = sel.getRangeAt(0);

  var container = rng.commonAncestorContainer;
  result = container;
  
  if (container.nodeType == 3) // text node
  {
    result = container.parentNode;
  }
  else if (rng.startContainer.nodeType == 1 && rng.startContainer == rng.endContainer && (rng.endOffset-rng.startOffset)<=1)
  {
    // single object selected
    result = rng.startContainer.childNodes[rng.startOffset];
  }

  return result;
}

// borders on borderless objects
SpawEditor.prototype.addGlyphs = function(root)
{
  if (this.show_glyphs)
  {
    if (root.nodeType == 1) // element
    {
      if (root.tagName.toLowerCase() == 'table' 
          && (!root.border || root.border == "0" || root.border == "")
          && (!root.style.borderWidth || root.style.borderWidth == "0" || root.style.borderWidth == "")
          && (!root.getAttribute("__spawglyphed")) 
         )
      {
        root.style.border = "1px dashed #aaaaaa";
        root.setAttribute("__spawglyphed", true);
        var cls = root.getElementsByTagName("td");
        for (var i=0; i<cls.length; i++)
        {
          cls[i].style.border = "1px dashed #aaaaaa";
          cls[i].setAttribute("__spawglyphed", true);
        }
        cls = root.getElementsByTagName("th");
        for (var i=0; i<cls.length; i++)
        {
          cls[i].style.border = "1px dashed #aaaaaa";
          cls[i].setAttribute("__spawglyphed", true);
        }
      }  
    }
    if (root.hasChildNodes())
    {
      for(var i=0; i<root.childNodes.length; i++)
        this.addGlyphs(root.childNodes[i]);
    }
  }
}
SpawEditor.prototype.removeGlyphs = function(root)
{
  if (root.nodeType == 1 && root.getAttribute("__spawglyphed")) // element
  {
    root.style.border = "";
    root.removeAttribute("__spawglyphed");
  }
  if (root.hasChildNodes())
  {
    for(var i=0; i<root.childNodes.length; i++)
      this.removeGlyphs(root.childNodes[i]);
  }
}
SpawEditor.prototype.selectionWalk = function(func)
{
  var pif = this.getPageIframe(this.getActivePage().name);
  
  var sel = pif.contentWindow.getSelection();
  if (sel && sel.rangeCount>0)
  {
    var rng = sel.getRangeAt(0);
    var ancestor = rng.commonAncestorContainer;

    this._in_selection = false;
    this.selectionNodeWalk(ancestor, rng, func);
  }
}
SpawEditor.prototype._in_selection;
SpawEditor.prototype.selectionNodeWalk = function(node, rng, func)
{
    if (this._in_selection || rng.startContainer == node || rng.endContainer == node)
    {
      func(node, (rng.startContainer == node)?rng.startOffset:null, (rng.endContainer == node)?rng.endOffset:null);
      this._in_selection = (rng.endContainer != node); 
    }
    if (node.childNodes && node.childNodes.length>0)
    {
      for (var i=0; i<node.childNodes.length; i++)
      {
        var cnode = node.childNodes[i];
        this.selectionNodeWalk(cnode, rng, func);
      }
    }
}
SpawEditor.prototype.insertHtmlAtSelection = function(source)
{
  var pdoc = this.getPageDoc(this.getActivePage().name);
  var elm = pdoc.createElement("span");
  var frag = pdoc.createDocumentFragment();
  elm.innerHTML = source;
  while(elm.hasChildNodes())
    frag.appendChild(elm.childNodes[0]);
  this.insertNodeAtSelection(frag);
}
