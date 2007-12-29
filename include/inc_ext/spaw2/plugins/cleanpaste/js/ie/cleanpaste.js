// Clean Paste plugin
function SpawPGcleanpaste()
{
}

SpawPGcleanpaste.init = function(editor, event)
{
  SpawEngine.addEventHandler("paste", "SpawPGcleanpaste.paste", "page_body");
}

SpawPGcleanpaste.paste = function(editor, event)
{
  var tmp_sub_holder = document.getElementById("__spaw_paste_sub_holder");
  if (tmp_sub_holder)
  {
    // if cleanup placeholder is already on the page just change it's content to a single dot
    tmp_sub_holder.innerHTML = ".";
  }
  else
  {
    // add placeholders to store pasted content
    var tmp_holder = document.createElement("DIV");
    tmp_holder.id = "__spaw_paste_holder";
    tmp_holder.style.height = "1px";
    tmp_holder.style.overflow = "hidden";
    tmp_holder.innerHTML = ".";
    tmp_sub_holder = document.createElement("DIV");
    tmp_sub_holder.id = "__spaw_paste_sub_holder";
    tmp_sub_holder.style.padding = "10px";
    tmp_sub_holder.innerHTML = ".";
    tmp_holder.appendChild(tmp_sub_holder);
    document.body.appendChild(tmp_holder);
  }
  var rng = document.body.createTextRange();
  rng.moveToElementText(tmp_sub_holder);
  rng.execCommand("paste");
  
  var clean_when = editor.getConfigValue("PG_CLEANPASTE_CLEAN").toLowerCase();
  var clean_pattern = new RegExp(editor.getConfigValue("PG_CLEANPASTE_PATTERN"), "gim");
  if (clean_when == "always" || (clean_when == "selective" && tmp_sub_holder.innerHTML.match(clean_pattern)!=null))
  {
    var cleaned_code = editor.getCleanCode(tmp_sub_holder, null);
    
    editor.insertHtmlAtSelection(cleaned_code);
    
    event.returnValue = false;
  }
}

SpawEngine.addEventHandler("spawallinit", "SpawPGcleanpaste.init");