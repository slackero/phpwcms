<?php
$lang->setModule("core");
$lang->setBlock("table_cell_prop");
$theme_path = SpawConfig::getStaticConfigValue('SPAW_DIR').'plugins/core/lib/theme/'.$theme->getName().'/';

$spaw_table_cell_styles = $config->getConfigValue("table_cell_styles");
?>
<script type="text/javascript" src="<?php echo SpawConfig::getStaticConfigValue('SPAW_DIR') ?>plugins/core/dialogs/table_cell_prop.js"></script>

<script type="text/javascript">
<!--
var spawErrorMessages = new Array();
<?php
echo 'spawErrorMessages["error_width_nan"] = "' . $lang->m('error_width_nan') . '";' . "\n";
echo 'spawErrorMessages["error_height_nan"] = "' . $lang->m('error_height_nan') . '";' . "\n";
?>
//-->
</script>
<form name="td_prop" id="td_prop" onsubmit="return false;" style="margin:0;padding:0;">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<tr>
  <td nowrap="nowrap"><?php echo $lang->m('css_class')?>:</td>
  <td nowrap="nowrap" colspan="3">
    <select id="ccssclass" name="ccssclass" size="1" class="input" onchange="SpawTableCellPropDialog.cssClassChanged();">
	<?php
	foreach($spaw_table_cell_styles as $key => $text)
	{
		echo '<option value="'.$key.'">'.$text.'</option>'."\n";
	}
	?>
    </select>
  </td>
</tr>
<tr>
  <td colspan="2"><?php echo $lang->m('horizontal_align')?>:</td>
  <td colspan="2" align="right"><input type="hidden" name="chalign" id="chalign" />
  <img id="ha_left" src="<?php echo $theme_path.'img/'?>tb_justifyleft.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setHAlign('left');" alt="<?php echo $lang->m('left')?>" />
  <img id="ha_center" src="<?php echo $theme_path.'img/'?>tb_justifycenter.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setHAlign('center');" alt="<?php echo $lang->m('center')?>" />
  <img id="ha_right" src="<?php echo $theme_path.'img/'?>tb_justifyright.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setHAlign('right');" alt="<?php echo $lang->m('right')?>" />  </td>
</tr>
<tr>
  <td colspan="2"><?php echo $lang->m('vertical_align')?>:</td>
  <td colspan="2" align="right"><input type="hidden" name="cvalign" id="cvalign" />
  <img id="ha_top" src="<?php echo $theme_path.'img/'?>tb_top.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setVAlign('top');" alt="<?php echo $lang->m('top')?>" />
  <img id="ha_middle" src="<?php echo $theme_path.'img/'?>tb_middle.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setVAlign('middle');" alt="<?php echo $lang->m('middle')?>" />
  <img id="ha_bottom" src="<?php echo $theme_path.'img/'?>tb_bottom.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setVAlign('bottom');" alt="<?php echo $lang->m('bottom')?>" />
  <img id="ha_baseline" src="<?php echo $theme_path.'img/'?>tb_baseline.gif" class="buttonOff" onclick="SpawTableCellPropDialog.setVAlign('baseline');" alt="<?php echo $lang->m('baseline')?>" />  </td>
</tr>
<tr>
  <td><?php echo $lang->m('width')?>:</td>
  <td nowrap="nowrap">
    <input type="text" name="cwidth" id="cwidth" size="3" maxlength="3" class="input3chars" />
    <select size="1" name="cwunits" id="cwunits" class="input">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
  <td><?php echo $lang->m('height')?>:</td>
  <td nowrap="nowrap">
    <input type="text" name="cheight" id="cheight" size="3" maxlength="3" class="input3chars" />
    <select size="1" name="chunits" id="chunits" class="input">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
</tr>
<tr>
  <td nowrap="nowrap"><?php echo $lang->m('no_wrap')?>:</td>
  <td nowrap="nowrap">
    <input type="checkbox" name="cnowrap" id="cnowrap" />
  </td>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="4"><?php echo $lang->m('bg_color')?>: <img src="img/spacer.gif" id="color_sample" border="1" width="30" height="18" align="absbottom" />&nbsp;
    <input type="text" name="cbgcolor" id="cbgcolor" size="7" maxlength="7" class="input7chars" onkeyup="SpawTableCellPropDialog.setSample()" />
    &nbsp;
    <input type="button" id="ccolorpicker" onclick="SpawTableCellPropDialog.showColorPicker(cbgcolor.value)" align="absbottom" value="..." class="bt" />
  </td>
</tr>
<tr>
  <td colspan="4">
	<?php echo $lang->m('background')?>: <input type="text" name="cbackground" id="cbackground" size="20" class="input">
	&nbsp;
	<input type="button" id="cimg_picker" onclick="SpawTableCellPropDialog.showImgPicker();" align="absbottom" class="bt" value="..." />	
  </td>
</tr>
<tr>
<td colspan="4" nowrap="nowrap">
<hr width="100%" />
</td>
</tr>
<tr>
<td colspan="4" align="right" valign="bottom" nowrap="nowrap">
  <input type="submit" value="<?php echo $lang->m('ok')?>" onclick="SpawTableCellPropDialog.okClick()" class="bt" />
  <input type="button" value="<?php echo $lang->m('cancel')?>" onclick="SpawTableCellPropDialog.cancelClick()" class="bt" />
</td>
</tr>
</table>
</form>