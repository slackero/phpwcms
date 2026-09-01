<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// guestbook/comments

?>

<div class="form-group row g-2">
	<label for="cguestbook_template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
	<div class="col-sm-4">
		<select name="cguestbook_template" id="cguestbook_template" class="form-select form-select-sm">
			<?php
			$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_cntpart/guestbook');
			if (is_array($tmpllist) && count($tmpllist)) {
				foreach ($tmpllist as $val) {
					$vals = '';
					if (isset($content['guestbook']['template']) && $val == $content['guestbook']['template']) {
						$vals = ' selected="selected"';
					}
					$val = htmlspecialchars($val);
					echo '			<option value="' . $val . '"' . $vals . '>' . $val . '</option>' . LF;
				}
			}
			?>
		</select>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_aliasID" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_alias_ID']; ?></label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<input name="cguestbook_aliasID" type="text" class="form-control form-control-sm me-2" id="cguestbook_aliasID" style="width: 70px;" size="10" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='';" value="<?php echo isset($content['guestbook']['aliasID']) ? $content['guestbook']['aliasID'] : ''; ?>" />
			<?php
			$_aliasID_Query  = 'SELECT acontent_id, acontent_visible, article_title, acontent_form FROM ' . DB_PREPEND . 'phpwcms_articlecontent';
			$_aliasID_Query .= ' LEFT JOIN ' . DB_PREPEND . 'phpwcms_article ON ';
			$_aliasID_Query .= ' (' . DB_PREPEND . 'phpwcms_articlecontent.acontent_aid = ' . DB_PREPEND . 'phpwcms_article.article_id)';
			$_aliasID_Query .= ' WHERE ' . DB_PREPEND . 'phpwcms_articlecontent.acontent_id != ' . $content['id'];
			$_aliasID_Query .= ' AND ' . DB_PREPEND . 'phpwcms_articlecontent.acontent_type=18';
			$_aliasID_Query .= ' AND ' . DB_PREPEND . 'phpwcms_articlecontent.acontent_trash=0';

			$_available_aliasID = _dbQuery($_aliasID_Query);

			if (count($_available_aliasID)) {
				echo '			<select name="cguestbook_aliasID_select" id="cguestbook_aliasID_select" class="form-select form-select-sm" style="width: auto;">' . LF;
				foreach ($_available_aliasID as $_aliasValue) {
					$_temp_gb_data = unserialize($_aliasValue['acontent_form'], ['allowed_classes' => false]);
					if (empty($_temp_gb_data['aliasID'])) {
						echo '				<option value="' . $_aliasValue['acontent_id'] . '">[' . $_aliasValue['acontent_id'] . '] ';
						echo html(getCleanSubString($_aliasValue['article_title'], 6, '&#8230;', 'word'));
						echo '</option>' . LF;
					}
				}
				echo '			</select>';
			}
			?>
		</div>
	</div>
</div>

<hr />

<div class="form-group row g-2">
	<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_guestbook_listing']; ?></label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<?php
			if (!isset($content['guestbook']['listing'])) {
				$content['guestbook']['listing'] = 0;
			}
			?>
			<div class="form-check form-check-inline me-3">
				<input name="cguestbook_listing" id="cguestbook_listing0" type="radio" value="0" class="form-check-input" <?php is_checked(0, $content['guestbook']['listing']); ?> />
				<label class="form-check-label" for="cguestbook_listing0"><?php echo $BL['be_cnt_guestbook_listing_all']; ?></label>
			</div>
			<div class="form-check form-check-inline me-3">
				<input name="cguestbook_listing" id="cguestbook_listing1" type="radio" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['listing']); ?> />
				<label class="form-check-label" for="cguestbook_listing1"><?php echo $BL['be_cnt_guestbook_list']; ?></label>
			</div>
			<input name="cguestbook_listcount" type="text" class="form-control form-control-sm me-2" id="cguestbook_listcount" style="width: 60px;" size="10" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='';" value="<?php echo isset($content['guestbook']['listcount']) ? $content['guestbook']['listcount'] : ''; ?>" />
			<span class="text-muted small"><?php echo $BL['be_cnt_guestbook_perpage']; ?></span>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<div class="col-sm-10 offset-sm-2">
		<?php
		if (empty($content['guestbook']['gb_login_post'])) {
			$content['guestbook']['gb_login_post'] = 0;
		}
		if (empty($content['guestbook']['gb_login_show'])) {
			$content['guestbook']['gb_login_show'] = 0;
		}
		?>
		<div class="form-check form-check-inline me-3">
			<input name="cguestbook_login_show" id="cguestbook_login_show" type="checkbox" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['gb_login_show']); ?> />
			<label class="form-check-label" for="cguestbook_login_show"><?php echo $BL['be_gb_show_login']; ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input name="cguestbook_login_post" id="cguestbook_login_post" type="checkbox" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['gb_login_post']); ?> />
			<label class="form-check-label" for="cguestbook_login_post"><?php echo $BL['be_gb_post_login']; ?></label>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_imgupload" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_imgupload']; ?></label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<?php
			if (!isset($content['guestbook']['image_upload'])) {
				$content['guestbook']['image_upload'] = 0;
			}
			if (empty($content['guestbook']['max_image_filesize'])) {
				$content['guestbook']['max_image_filesize'] = $phpwcms['file_maxsize'];
			}
			$content['guestbook']['max_image_filesize'] = return_bytes_shorten($content['guestbook']['max_image_filesize']);
			if (return_bytes($content['guestbook']['max_image_filesize']) > $phpwcms['file_maxsize']) {
				$content['guestbook']['max_image_filesize'] = return_bytes_shorten($phpwcms['file_maxsize']);
			}
			?>
			<div class="form-check form-check-inline me-3">
				<input name="cguestbook_imgupload" id="cguestbook_imgupload" type="checkbox" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['image_upload']); ?> />
				<label class="form-check-label" for="cguestbook_imgupload"><?php echo $BL['be_on']; ?></label>
			</div>
			<span class="me-2"><?php echo $BL['be_cnt_filesize']; ?>:</span>
			<input name="cguestbook_maximgsize" type="text" class="form-control form-control-sm me-2" id="cguestbook_maximgsize" style="width: 100px;" size="20" maxlength="20" value="<?php echo $content['guestbook']['max_image_filesize']; ?>" />
			<span class="text-muted small">(<?php echo return_bytes($content['guestbook']['max_image_filesize']); ?> Byte)</span>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sorting']; ?></label>
	<div class="col-sm-10">
		<?php
		if (!isset($content['guestbook']['sorting'])) {
			$content['guestbook']['sorting'] = 0;
		}
		?>
		<div class="form-check form-check-inline me-3">
			<input name="cguestbook_sorting" id="cguestbook_sorting0" type="radio" value="0" class="form-check-input" <?php is_checked(0, $content['guestbook']['sorting']); ?> />
			<label class="form-check-label" for="cguestbook_sorting0"><?php echo $BL['be_msg_date'] . ' ' . $BL['be_admin_struct_orderdesc']; ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input name="cguestbook_sorting" id="cguestbook_sorting1" type="radio" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['sorting']); ?> />
			<label class="form-check-label" for="cguestbook_sorting1"><?php echo $BL['be_msg_date'] . ' ' . $BL['be_admin_struct_orderasc']; ?></label>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_captcha" class="col-sm-2 col-form-label text-end">Captcha</label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<?php
			if (!isset($content['guestbook']['captcha'])) {
				$content['guestbook']['captcha'] = 1;
			}
			if (empty($content['guestbook']['captcha_maxchar'])) {
				$content['guestbook']['captcha_maxchar'] = 5;
			}
			?>
			<div class="form-check form-check-inline me-3">
				<input name="cguestbook_captcha" id="cguestbook_captcha" type="checkbox" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['captcha']); ?> />
				<label class="form-check-label" for="cguestbook_captcha"><?php echo $BL['be_admin_usr_verify']; ?></label>
			</div>
			<span class="me-2"><?php echo $BL['be_cnt_captchalength']; ?>:</span>
			<input name="cguestbook_captchamaxchar" type="text" class="form-control form-control-sm me-2" id="cguestbook_captchamaxchar" style="width: 50px;" size="3" maxlength="2" value="<?php echo $content['guestbook']['captcha_maxchar']; ?>" />
			<span class="text-muted small"><?php echo $BL['be_cnt_chars']; ?></span>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_urlcheck" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_website']; ?></label>
	<div class="col-sm-10">
		<?php
		if (empty($content['guestbook']['gb_urlcheck'])) {
			$content['guestbook']['gb_urlcheck'] = 0;
		}
		?>
		<div class="form-check">
			<input name="cguestbook_urlcheck" id="cguestbook_urlcheck" type="checkbox" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['gb_urlcheck']); ?> />
			<label class="form-check-label" for="cguestbook_urlcheck"><?php echo $BL['be_gb_urlcheck']; ?></label>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_banned" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_guestbook_banned']; ?></label>
	<div class="col-sm-10">
		<textarea name="cguestbook_banned" id="cguestbook_banned" rows="3" class="form-control form-control-sm field-sizing-content field-sizing-content-3"><?php echo isset($content['guestbook']['banned']) ? html($content['guestbook']['banned']) : ''; ?></textarea>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_cookie" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_guestbook_flooding']; ?></label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<div class="form-check form-check-inline me-2">
				<input name="cguestbook_cookie" id="cguestbook_cookie" type="checkbox" value="1" class="form-check-input" <?php if (!isset($content['guestbook']['cookie'])) $content['guestbook']['cookie'] = 1; is_checked(1, intval($content['guestbook']['cookie'])); ?> />
				<label class="form-check-label" for="cguestbook_cookie"><?php echo $BL['be_cnt_guestbook_setcookie']; ?></label>
			</div>
			<input name="cguestbook_time" type="text" class="form-control form-control-sm me-2" id="cguestbook_time" style="width: 70px;" size="10" maxlength="10" value="<?php if (!isset($content['guestbook']['time'])) { $content['guestbook']['time'] = 86400; } echo intval($content['guestbook']['time']); ?>" />
			<span class="text-muted small">s</span>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cguestbook_notify" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_email_notify']; ?></label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<?php
			if (!isset($content['guestbook']['notify'])) {
				$content['guestbook']['notify'] = 0;
			}
			if (!isset($content['guestbook']['notify_email'])) {
				$content['guestbook']['notify_email'] = '';
			}
			?>
			<div class="form-check form-check-inline me-2">
				<input name="cguestbook_notify" id="cguestbook_notify" type="checkbox" value="1" class="form-check-input" <?php is_checked(1, $content['guestbook']['notify']); ?> />
				<label class="form-check-label" for="cguestbook_notify">&nbsp;</label>
			</div>
			<input name="cguestbook_notify_email" type="text" class="form-control form-control-sm" id="cguestbook_notify_email" style="width: 300px;" size="10" value="<?php echo html($content['guestbook']['notify_email']); ?>" />
		</div>
	</div>
</div>

<?php if ($content['id']): ?>
	<hr />
	<div class="form-group row g-2">
		<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_guestbook_edit']; ?></label>
		<div class="col-sm-10">
			<div style="height: 350px;">
				<iframe class="w-100 h-100 border-0" src="include/inc_act/act_guestbook.php?<?php echo CSRF_GET_TOKEN; ?>&amp;cid=<?php echo empty($content['guestbook']['aliasID']) ? $content['id'] : $content['guestbook']['aliasID']; ?>"></iframe>
			</div>
		</div>
	</div>
<?php endif; ?>
