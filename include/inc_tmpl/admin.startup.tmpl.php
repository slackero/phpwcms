<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$startup_default = read_textfile(CMSGO_TEMPLATE."inc_default/startup.php"); //reads the css template
$startup_default = ($startup_default) ? html($startup_default) : "";

?><form action="include/inc_act/act_startuptext.php" method="post" name="startup" target="_self"><table width="538">

	<tr>
	  <td colspan="2" class="title"><?php echo $BL['be_admin_startup_title'] ?></td>
	</tr>
	<tr>
		<td width="35"><img src="img/leer.gif" alt="" width="35" height="4"></td>
		<td width="503"><img src="img/leer.gif" alt="" width="1" height="1"></td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
	<tr><td colspan="2" class="chatlist"><?php echo $BL['be_admin_startup_text'] ?>:&nbsp;</td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
		<td colspan="2"><textarea name="startup_default" cols="35" rows="25" wrap="OFF" class="width540" id="startup_default"><?php echo $startup_default; ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_startup_button'] ?>"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>

</table></form>
