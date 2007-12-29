// Insert HTML plugin
function SpawPGinserthtml()
{
}

SpawPGinserthtml.insertHtmlClick = function(editor, tbi, sender)
{
  if (tbi.is_enabled)
  {
    SpawEngine.openDialog('inserthtml', 'inserthtml', editor, '', '', 'SpawPGinserthtml.insertHtmlClickCallback', tbi, sender);
  }
}
SpawPGinserthtml.insertHtmlClickCallback = function(editor, result, tbi, sender)
{
  if (result)
  {
    editor.insertHtmlAtSelection(result);
  }
  editor.updateToolbar();
}
SpawPGinserthtml.isInsertHtmlEnabled =  function(editor, tbi)
{
  return editor.isInDesignMode();
}
