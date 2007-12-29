<?php
$lang->setModule("inserthtml");
$lang->setBlock("inserthtml");
?>
<script type="text/javascript" src="<?php echo SpawConfig::getStaticConfigValue('SPAW_DIR') ?>plugins/inserthtml/dialogs/inserthtml.js"></script>
<form name="inserthtml" id="inserthtml" onsubmit="return false;">
<table border="0" cellspacing="0" cellpadding="2" width="500">
<tr><td><?php echo $lang->m('code')?>:</td></tr>
<tr><td nowrap><textarea name="code" id="code" cols="80" rows="10" class="input"></textarea></td></tr>
<tr><td nowrap><hr width="100%"></td></tr>
<tr>
	<td colspan="2" align="right" valign="bottom" nowrap="nowrap">
		<input type="submit" value="<?php echo $lang->m('ok')?>" onClick="SpawInsertHtmlDialog.okClick()" class="bt" />
		<input type="button" value="<?php echo $lang->m('cancel')?>" onClick="SpawInsertHtmlDialog.cancelClick()" class="bt" />
	</td>
</tr>
</table>
</form>