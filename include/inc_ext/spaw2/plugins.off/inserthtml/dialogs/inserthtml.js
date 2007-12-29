// Insert Html Dialog
function SpawInsertHtmlDialog()
{
}
SpawInsertHtmlDialog.init = function() 
{
}

SpawInsertHtmlDialog.okClick = function() {
  var result = document.getElementById("code").value;
  if (spawArgs.callback)
  {
    eval('window.opener.'+spawArgs.callback + '(spawEditor, result, spawArgs.tbi, spawArgs.sender)');
  }
  window.close();
}

SpawInsertHtmlDialog.cancelClick = function() {
  window.close();
}

if (document.attachEvent)
{
  // ie
  window.attachEvent("onload", new Function("SpawInsertHtmlDialog.init();"));
}
else
{
  window.addEventListener("load", new Function("SpawInsertHtmlDialog.init();"), false);
}
