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

?>
<p class="title">phpwcms path settings </p>
<p>Please check that any path value does NOT begin or end with a slash. I recommend
  to use the default values. It's the best way for 1st time
  usage.</p>
<form action="setup.php?step=3" method="post"><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr>
    <td align="right" class="v10">&nbsp;</td>
    <td colspan="2" class="chatlist">document root of your web account; empty
      for default value<br><?php echo  html_specialchars($_SERVER['DOCUMENT_ROOT']) ?></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
  </tr>
  <tr>
    <td align="right" class="v10">root path :&nbsp;</td>
    <td><input name="doc_root" type="text" class="f11b" id="doc_root" style="width:300px" value="<?php echo html_specialchars($phpwcms["DOC_ROOT"]) ?>" size="30" maxlength="100"></td>
    <td class="chatlist"><em>&nbsp;default: $_SERVER['DOCUMENT_ROOT'] </em></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td>
  </tr>
  <tr>
    <td align="right" class="v10">&nbsp;</td>
    <td colspan="2" class="chatlist">if you install in another directory than
      your base webserver root<br />
      insert the directory name here - most times this is the part after<br>
      your
      base URL like http://mysite.com/<strong>phpwcms_root</strong></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
  </tr>
  <tr>
    <td align="right" class="v10">phpwcms root:&nbsp;</td>
    <td><input name="root" type="text" class="f11b" id="root" style="width:300px" value="<?php echo html_specialchars($phpwcms["root"]) ?>" size="30" maxlength="100">
    </td>
    <td class="chatlist"><em>&nbsp;default: (empty)</em></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td>
  </tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">it's recommend to
              put this directory outside a folder<br />
              available via web. you can use it like this ../filearchive</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">filestorage:&nbsp;</td>
            <td><input name="file_path" type="text" class="f11b" id="file_path" style="width:300px" value="<?php echo html_specialchars($phpwcms["file_path"]) ?>" size="30" maxlength="100"></td>
            <td class="chatlist"><em>&nbsp;default: filearchive </em></td>
          </tr><tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">here are the template directories located where all<br />template stuff is stored</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">templates:&nbsp;</td>
            <td><input name="templates" type="text" class="f11b" id="templates" style="width:300px" value="<?php echo html_specialchars($phpwcms["templates"]) ?>" size="30" maxlength="100"></td>
            <td class="chatlist"><em>&nbsp;default: template</em></td>
          </tr><tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="4"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2" class="chatlist">this is the directory in which you
              can upload files for ftp takeover<br />
              functionality - maybe this
              can be extended for special user ftp directories.<br />
              you can also use it like this: ../../myftpdir
            </td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td></tr>
          <tr>
            <td align="right" class="v10">ftp&nbsp;takeover:&nbsp;</td>
            <td><input name="ftp_path" type="text" class="f11b" id="ftp_path" style="width:300px" value="<?php echo html_specialchars($phpwcms["ftp_path"]) ?>" size="30" maxlength="100"></td>
            <td class="chatlist"><em>&nbsp;default: upload </em></td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="15"></td></tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2"><input name="Submit" type="submit" class="button" value="send path values"></td>
          </tr>
</table>
  <input name="do" type="hidden" value="1"></form>