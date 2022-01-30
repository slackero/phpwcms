<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

if (!defined('PHP8')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

?><p class="title">phpwcms content values</p>
<form action="setup.php?step=4" method="post"><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">this is the max size of files can
              be uploaded</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">upload file size:&nbsp;</td>
            <td><input name="file_maxsize" type="text" class="f11b" id="file_maxsize" style="width:125px" value="<?php echo $phpwcms["file_maxsize"] ?>" size="30" maxlength="100"></td>
            <td class="chatlist"><em>&nbsp;default: 2 x 1024 x 1024 = 2097152
                Bytes </em></td>
          </tr><tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">the width of the frontend main content
              block<br />
              base value of creating image lists</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">content width:&nbsp;</td>
            <td><input name="content_width" type="text" class="f11b" id="content_width" style="width:125px" value="<?php echo $phpwcms["content_width"] ?>" size="30" maxlength="100"></td>
            <td class="chatlist"><em>&nbsp;default: 538</em></td>
          </tr>
          <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">width and height of list image thumbnails</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">list image:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
              <tr>
                <td><input name="img_list_width" type="text" class="f11b" id="img_list_width" style="width:55px" value="<?php echo $phpwcms["img_list_width"] ?>" size="30" maxlength="100"></td>
                <td class="v10">&nbsp;x&nbsp;</td>
                <td><input name="img_list_height" type="text" class="f11b" id="img_list_height" style="width:55px" value="<?php echo $phpwcms["img_list_height"] ?>" size="30" maxlength="100"></td>
              </tr>
            </table></td>
            <td class="chatlist"><em>&nbsp;default: width 100 x height 75</em></td>
          </tr><tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">width and height of preview images<br />
              recommend is to use the same width as main<br />
              content block - for better quality use larger<br />
              if the source image is smaller it will not be<br />
              sized to this larger values</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">preview image:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
              <tr>
                <td><input name="img_prev_width" type="text" class="f11b" id="img_prev_width" style="width:55px" value="<?php echo $phpwcms["img_prev_width"] ?>" size="30" maxlength="100">                </td>
                <td class="v10">&nbsp;x&nbsp;</td>
                <td><input name="img_prev_height" type="text" class="f11b" id="img_prev_height" style="width:55px" value="<?php echo $phpwcms["img_prev_height"] ?>" size="30" maxlength="100">                </td>
              </tr>
            </table></td>
            <td class="chatlist"><em>&nbsp;default: width 538 x height 400</em></td>
          </tr>
          <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">if there is no activities within
              the backend<br />
              you will be automaticly logged out after<br />
              that time (seconds)</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">logout time:&nbsp;</td>
            <td><input name="max_time" type="text" class="f11b" id="max_time" style="width:125px" value="<?php echo $phpwcms["max_time"] ?>" size="30" maxlength="100"></td>
            <td class="chatlist"><em>&nbsp;default: 1800 seconds</em></td>
          </tr>
 		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="20"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2"><input name="Submit" type="submit" class="button" value="send content values"></td>
          </tr>
</table>
<input name="do" type="hidden" value="1"></form>