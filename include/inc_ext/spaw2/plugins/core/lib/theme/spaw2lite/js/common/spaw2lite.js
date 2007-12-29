// theme methods
function SpawThemespaw2lite()
{
}
SpawThemespaw2lite.prefix = "spaw2lite";

// preload button images
SpawThemespaw2lite.preloadImages = function(tbi)
{
}

// button events
SpawThemespaw2lite.buttonOver = function(tbi, sender)
{
  if (!sender.disabled && sender.disabled != "true" && !tbi.is_pushed) // for gecko
  {
    sender.className = SpawThemespaw2lite.getBaseClassName(sender.className) + "_Over";
  } 
  tbi.editor.getTargetEditor().showStatus(sender.title);
}
SpawThemespaw2lite.buttonOut = function(tbi, sender)
{
  if (!sender.disabled && sender.disabled != "true") // for gecko
  {
    if (!tbi.is_pushed)
      sender.className = SpawThemespaw2lite.getBaseClassName(sender.className);
    else
      sender.className = SpawThemespaw2lite.getBaseClassName(sender.className) + "_Down";
    
    tbi.editor.getTargetEditor().showStatus('');
  } 
}
SpawThemespaw2lite.buttonDown = function(tbi, sender)
{
  if (!sender.disabled && sender.disabled != "true") // for gecko
    sender.className = SpawThemespaw2lite.getBaseClassName(sender.className) + "_Down";
}
SpawThemespaw2lite.buttonUp = function(tbi, sender)
{
  if (!sender.disabled && sender.disabled != "true") // for gecko
    sender.className = SpawThemespaw2lite.getBaseClassName(sender.className) + "_Over";
}
SpawThemespaw2lite.buttonOff = function(tbi, sender)
{
    sender.className = SpawThemespaw2lite.getBaseClassName(sender.className) + "_Off";
}

// dropdown events
SpawThemespaw2lite.dropdownOver = function(tbi, sender)
{
}
SpawThemespaw2lite.dropdownOut = function(tbi, sender)
{
}
SpawThemespaw2lite.dropdownDown = function(tbi, sender)
{
}
SpawThemespaw2lite.dropdownUp = function(tbi, sender)
{
}
SpawThemespaw2lite.dropdownOff = function(tbi, sender)
{
}

SpawThemespaw2lite.getBaseClassName = function(className)
{
  if (className.indexOf("_") != -1)
    return className.substring(0, className.indexOf("_"));
  else 
    return className;
}
