// formatting related methods
function SpawPGcore()
{
}

// performs standard (browser built-in) command
SpawPGcore.standardFunctionClick = function(editor, tbi, sender)
{
  if (tbi.is_enabled)
  {
    var pdoc = editor.getPageDoc(editor.active_page.name);
    try // throws exception under gecko in some cases
    {
      pdoc.execCommand(tbi.name, false, null);
    }
    catch(e)
    {}
    editor.updateToolbar();
    editor.focus();
  }
}

// performs standard (browser built-in) command initiated by dropdown value
SpawPGcore.standardFunctionChange = function(editor, tbi, sender)
{
  if (tbi.is_enabled)
  {
    var pdoc = editor.getPageDoc(editor.active_page.name);
    var val = sender.options[sender.selectedIndex].value;
    try // throws exception under gecko in some cases
    {
      pdoc.execCommand(tbi.name, false, val);
    }
    catch(e)
    {}
    sender.selectedIndex = 0;
    editor.updateToolbar();
    editor.focus();
  }
}

// checks if built in command can be executed in current context
SpawPGcore.isStandardFunctionEnabled = function(editor, tbi)
{
  // enabled in design mode only
  if (editor.getActivePage().editing_mode == "design")
  {
    if (SpawPGcore.isStandardFunctionPushed(editor, tbi)) // workaround for opera 
      return true;
    else
      return editor.getPageDoc(editor.getActivePage().name).queryCommandEnabled(tbi.name);
  }
  else
  {
    return false;
  }
}

// returns built in command status in current context
SpawPGcore.isStandardFunctionPushed = function(editor, tbi)
{
  if (editor.getActivePage().editing_mode == "design")
  {
    try // throws exception under gecko in some cases
    {
      return editor.getPageDoc(editor.getActivePage().name).queryCommandState(tbi.name);
    }
    catch(e)
    {
      return false;
    }
  }
  else
  {
    return false;
  }
}

// returns status of the current function
SpawPGcore.standardFunctionStatusCheck = function(editor, tbi)
{
  var pdoc = editor.getActivePageDoc();
  try // throws exception under gecko in some cases
  {
    return pdoc.queryCommandValue(tbi.name);
  }
  catch(e)
  {
    return '';
  }
  
}

// sets foreground color
SpawPGcore.foreColorClick = function(editor, tbi, sender)
{
  if (tbi.is_enabled)
  {
    var cl = editor.getPageDoc(editor.getActivePage().name).queryCommandValue("forecolor"); 
    if (cl == null)
      cl = '#000000';
    SpawEngine.openDialog('core', 'colorpicker', editor, SpawColor.parseRGB(cl), '', 'SpawPGcore.foreColorClickCallback', tbi, sender);
  }
}
SpawPGcore.foreColorClickCallback = function(editor, result, tbi, sender)
{
  var pdoc = editor.getPageDoc(editor.active_page.name);
  //editor.getPageIframe(editor.active_page.name).focus();
  try // throws exception under gecko in some cases
  {
    pdoc.execCommand('forecolor', false, result);
  }
  catch(e)
  {}
}
SpawPGcore.isForeColorEnabled = function(editor, tbi)
{
  if(editor.isInDesignMode())
  {
    try // throws exception under gecko in some cases
    {
      return editor.getActivePageDoc().queryCommandEnabled("forecolor");
    }
    catch(e)
    {
      return false;
    }
  }
  else
  {
    return false;
  }
}

// bg color differs between browsers


// applies style
SpawPGcore.styleChange = function(editor, tbi, sender)
{
  if (tbi.is_enabled)
  {
    var cls = sender.options[sender.selectedIndex].value;
    if (cls != '')
    {
      // apply class
      var sel = editor.getNodeAtSelection();
      if (sel)
      {
        if (sel.nodeType == 1) // element
        {
          sel.className = cls;
          editor.insertNodeAtSelection(sel);
        }
        else
        {
          var pdoc = editor.getActivePageDoc();
          var spn = pdoc.createElement("SPAN");
          spn.className = cls;
          spn.appendChild(sel);
          editor.insertNodeAtSelection(spn);
        }
      }
      else
      {
      }
    }
    else
    {
      // remove class
      var pnode = editor.getSelectionParent();
      while(pnode && pnode.tagName.toLowerCase() != "body" && (!pnode.className || pnode.className == ""))
      {
        pnode = pnode.parentNode;
      }
        
      if (pnode && pnode.tagName.toLowerCase() != "body")
      {
        pnode.removeAttribute("class");
        pnode.removeAttribute("className");
      }
    }
    sender.selectedIndex = 0;
    editor.updateToolbar();
    editor.focus();
  }
}
SpawPGcore.isStyleEnabled = function(editor, tbi)
{
  if(editor.isInDesignMode())
    // use formatBlock because it should be enabled in same situations
    return editor.getActivePageDoc().queryCommandEnabled("formatBlock");
  else
    return false;
}
// returns currently applied class
SpawPGcore.styleStatusCheck = function(editor, tbi)
{
  var pnode = editor.getSelectionParent();
  while(pnode && pnode.tagName && pnode.tagName.toLowerCase() != "body" && (!pnode.className || pnode.className == ""))
  {
    pnode = pnode.parentNode;
  }
    
  if (pnode && pnode.tagName && pnode.tagName.toLowerCase() != "body")
  {
    return pnode.className;
  }
  else
  {
    return null;
  }
}

