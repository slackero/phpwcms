<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

initJsCalendar();

// show newsletter form
?>

<script>
function showNewsletterTemplateData(tvar) {

  if(tvar == '' || !nltemplate[tvar]) {
    $("#newsletterTemplateInfo").html('');
    return true;
  }

  var tdata = "";
  if(nltemplate[tvar]['imgsrc'] != '') {
    tdata = '<img src="'+nltemplate[tvar]['imgsrc']+'" alt="" border="0" align="left" style="margin:2px 5px 5px 0" />';
  }
  if(nltemplate[tvar]['title'] != '') {
    tdata = tdata+'<strong>'+nltemplate[tvar]['title']+'</strong> <br />';
  }
  if(nltemplate[tvar]['description'] != '') {
    tdata = tdata+nltemplate[tvar]['description'];
  }
  $("#newsletterTemplateInfo").html(tdata);
  return true;
}
</script>

<form action="cmsgo.php?do=messages&amp;p=3&amp;s=<?php echo $newsletter["newsletter_id"] ?>&amp;edit=1" method="post" name="newsletter" target="_self" id="newsletter" onsubmit="hideLayer('newsletterButtons');enableStatusMessage('statusMessage', true, false);">
<div class="row align-items-center">
	<div class="col col-sm-auto text-center text-sm-left">
		<h1><?php echo $BL['be_subnav_msg_newslettersend'] ?></h1>
	</div>
	<div class="col-12 col-sm text-center text-sm-right mb-3">
		 <div class="form-group">
				<input name="newsletter_id" type="hidden" value="<?php echo $newsletter["newsletter_id"] ?>" />
				<input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo empty($newsletter["newsletter_id"]) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?>" />
				<input name="close" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
				<input type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='cmsgo.php?do=messages&amp;p=3';" />
		 </div>
	</div>
</div>

<div class="card">
  <div class="card-header"><h2><?php echo $BL['be_newsletter_titleeditnl'] ?></h2></div>
  <div class="card-body">

    <div class="form-group form-row align-items-center">
      <label for="newsletter_pub" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cnt_start'] ?></label>
      <div class="col-sm-auto">
        <div class="date input-group" id="datetimepicker">
          <input name="newsletter_pub" type="text" id="newsletter_pub" class="form-control form-control-sm datetimepicker" placeholder="DD-MM-YYYY" value="<?php echo cmsgo_strtotime($newsletter["newsletter_pub"], 'd.m.Y', ''); ?>" required />
          <div class="input-group-append">
            <span class="datepickerbutton input-group-text btn form-control-sm btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $(function () {
          $('#datetimepicker').datetimepicker({
            locale: 'de-ch',
            format: "DD.MM.YYYY"
          });
      });
    </script>

    <div class="form-group form-row align-items-center">
      <label for="newsletter_subject" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_msg_subject'] ?></label>
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-sm" name="newsletter_subject" id="newsletter_subject" value="<?php echo html($newsletter["newsletter_subject"]) ?>"  size="50" maxlength="250" onchange="hideLayer('messagesend');" required />
      </div>
    </div>

<?php
    if(!empty($newsletter["newsletter_created"])) {
      echo '<div class="form-group form-row align-items-center">';
      echo '  <label for="newsletter_created" class="col-sm-2 col-form-label text-right">'. $BL['be_fprivedit_created'] .'</label>';
      echo '  <div class="col-sm-10">';
      echo @date($BL['be_fprivedit_dateformat'], strtotime($newsletter["newsletter_created"]));
      echo '</div></div>';
    }
?>
    <div class="form-group form-row align-items-center">
        <label for="newsletter_date" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_changed'] ?></label>
         <div class="col-sm-auto"><?php
        if(isset($newsletter['error'])) $newsletter["newsletter_date"] = time();
        echo @date($BL['be_fprivedit_dateformat'], $newsletter["newsletter_date"]);
        ?></div>
    </div>

    <hr>

    <div class="form-group form-row align-items-center">
        <label for="newsletter_fromname" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_fromname'] ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control form-control-sm" name="newsletter_fromname" id="newsletter_fromname" value="<?php echo html($newsletter["newsletter_vars"]["from_name"]) ?>" size="50" maxlength="250"  required />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="newsletter_fromemail" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_fromemail'] ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control form-control-sm" name="newsletter_fromemail" id="newsletter_fromemail" value="<?php echo html($newsletter["newsletter_vars"]['from_email']) ?>" size="50" maxlength="250" required />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="newsletter_replyto" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_replyto'] ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control form-control-sm" name="newsletter_replyto" id="newsletter_replyto" value="<?php echo html($newsletter["newsletter_vars"]['replyto']) ?>" size="50" maxlength="250" required />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="newsletter_lang" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></label>
        <div class="col-sm-4">
           <select name="newsletter_lang" id="newsletter_lang" class="custom-select form-control form-control-sm">
            <?php
              foreach($cmsgo['allowed_lang'] as $key => $lang):
                $lang = strtolower($lang);
                echo '  <option value="'.$lang.'"';
                is_selected($lang, $newsletter["newsletter_lang"]);
                echo '>'. get_language_name($lang) .'</option>';
              endforeach;
            ?>
          </select>
        </div>
    </div>

    <div class="form-group form-row bg-grey py-2">
      <label class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_subscription'] ?></label>
      <div class="col-sm-10">
        <div class="form-check">
          <label class="form-check-label align-items-center">
            <input class="form-check-input" name="newsletter_subscription[0]" type="checkbox" id="nls0" value="0" <?php if(isset($newsletter["newsletter_vars"]["subscription"][0]) && $newsletter["newsletter_vars"]["subscription"][0] == 0) echo ' checked="checked"'; ?> />
            <?php echo $BL['be_newsletter_allsubscriptions']; ?>
          </label>
        </div>

<?php
    //retrieve available subscription lists/channels
    $sql = "SELECT subscription_id,subscription_name FROM ".DB_PREPEND."cmsgo_subscription ORDER BY subscription_name";
    $result = _dbQuery($sql);
    if(isset($result[0]['subscription_id'])) {
        foreach($result as $row):
?>
        <div class="form-check">
					<input type="checkbox"
							class="form-check-input"
							name="newsletter_subscription[<?php echo $row['subscription_id']; ?>]"
							id="nls<?php echo $row['subscription_id']; ?>"
							value="<?php echo $row['subscription_id']; ?>"<?php
									if(!empty($newsletter["newsletter_vars"]["subscription"]) && count($newsletter["newsletter_vars"]["subscription"])) {
											foreach($newsletter["newsletter_vars"]["subscription"] as $value) {
													if($value == $row['subscription_id']): ?>
							checked="checked"<?php
															break;
													endif;
											}
									}
						?> />
          <label class="form-check-label align-items-center"><?php echo html($row['subscription_name']); ?></label>
        </div>


<?php
        endforeach;
    }
?>
      </div>
    </div>

      <?php
    // newsletter templates
    $tmpllist = returnSubdirListAsArray(CMSGO_TEMPLATE.'inc_newsletter');
    $tmpldata = array('options' => array(), 'js' => array(), 'files' => array() );
    $value3   = '';
    if(is_array($tmpllist) && count($tmpllist)) {
        if(empty($newsletter["newsletter_vars"]['template'])) {
            $newsletter["newsletter_vars"]['template'] = '';
        }
        $i = 0;
        foreach($tmpllist as $value) {
            $value1 = html($value);
            $tmpldata['options'][$i]  = '<option value="'.$value1.'"';
            if($value == $newsletter["newsletter_vars"]['template']) {
                $tmpldata['options'][$i] .= ' selected="selected"';
                $value3 = $value;
            }
            $tmpldata['options'][$i] .= '>'.$value1.'</option>'.LF;
            $tmpldata['files'][$i] = returnFileListAsArray(CMSGO_TEMPLATE.'inc_newsletter/'.$value);

            // chech against tmpl file
            if(isset($tmpldata['files'][$i]['newsletter.tmpl'])) {
                $value2 = @file_get_contents(CMSGO_TEMPLATE.'inc_newsletter/'.$value.'/newsletter.tmpl');
                $value2 = get_tmpl_section('NEWSLETTER_SETTINGS', $value2);
                $value2 = parse_ini_str($value2, false);
                if(empty($value2['title']))         $value2['title'] = '';
                if(empty($value2['description']))   $value2['description'] = '';
            } else {
                $value2 = array('title'=>'', 'description'=>'');
            }

            $tmpldata['js'][] = '  nltemplate["'.$value.'"] = new Array();';
            $tmpldata['js'][] = '  nltemplate["'.$value.'"]["title"] = "'.js_singlequote($value2['title']).'";';
            $tmpldata['js'][] = '  nltemplate["'.$value.'"]["description"] = "'.js_singlequote($value2['description']).'";';

            $value2 = '';
            // set preview image
            if(isset($tmpldata['files'][$i]['preview.gif'])) {
                $value2 = 'preview.gif';
            } elseif(isset($tmpldata['files'][$i]['preview.jpg'])) {
                $value2 = 'preview.jpg';
            } elseif(isset($tmpldata['files'][$i]['preview.png'])) {
                $value2 = 'preview.png';
            }
            if($value2) {
                $tmpldata['js'][] = '  nltemplate["'.$value.'"]["imgsrc"] = "'.js_singlequote(TEMPLATE_PATH.'inc_newsletter/'.$value.'/'.$value2).'";';
            }

            $i++;
        }
    }

?>

    <div class="form-group form-row align-items-center">
        <label for="newsletter_template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template'] ?></label>
        <div class="col-sm-4">
          <select name="newsletter_template" id="newsletter_template" class="custom-select form-control form-control-sm" onchange="showNewsletterTemplateData(this.options[this.selectedIndex].value);">
            <option value=""<?php if(empty($newsletter["newsletter_vars"]['template'])) echo ' selected="selected"' ?>><?php echo $BL['be_admin_tmpl_default'].' ('.$BL['be_func_struct_empty'].')' ?></option>
            <?php echo implode($tmpldata['options']) ?>
          </select>
        </div>
    </div>

    <div class="form-group form-row justify-content-end">
        <div id="newsletterTemplateInfo" class="col-sm-10"></div>
    </div>

    <script type="text/javascript">
        var nltemplate = [];
      <?php
          echo implode(LF, $tmpldata['js']).LF;
          echo '  showNewsletterTemplateData("'.$value3.'");';
      ?>
    </script>

    <div>
        <p><strong><?php echo $BL['be_newsletter_htmlpart'] ?>:</strong></p>
        <?php

        $wysiwyg_editor = array(
            'value'     => $newsletter["newsletter_vars"]['html'],
            'field'     => 'newsletter_html',
            'height'    => '350px',
            'width'     => '100%',
            'rows'      => '20',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );
        include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

        ?>
        <p class="mt-4"><strong><?php echo $BL['be_newsletter_textpart'] ?>:</strong></p>

        <textarea name="newsletter_text" rows="25" wrap="off" class="code form-control form-control-sm autosize"><?php echo html($newsletter["newsletter_vars"]['text']) ?></textarea>

        <p class="mt-2"><?php echo $BL['be_newsletter_placeholder'] ?>:
                    ###RECIPIENT_NAME###,
                    ###RECIPIENT_EMAIL###,
                    ###VERIFY_LINK###,
                    ###DELETE_LINK###,
                    ###SITE_URL###,
                    ###OPENER###
        </p>
    </div>

    <div class="form-group form-row bg-grey py-2">
      <label class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_ftptakeover_status'] ?></label>
      <div class="col-sm-10">
        <div class="form-check">
					<input class="form-check-input" name="newsletter_active" id="newsletter_active" type="checkbox" value="1"<?php is_checked(1, $newsletter["newsletter_active"]); ?> />
					<label class="form-check-label align-items-center pt-0"><strong><?php echo $BL['be_cnt_newsletter_prepare'] ?></strong><br /><span class="v10"><?php echo $BL['be_cnt_newsletter_prepare1'] ?></span></label>
        </div>
      </div>
    </div>

    <div id="statusMessage"><img src="img/indicator/indicator_arrows_green.gif" alt="Indicator" width="16" height="16" class="icon" /><p><?php echo $BL['be_cnt_newsletter_prepare2'] ?></p></div>
  </div>
</div>

     <div class="form-group align-items-center text-center text-sm-right mt-4 mb-0">
        <input name="newsletter_id" type="hidden" value="<?php echo $newsletter["newsletter_id"] ?>" />
        <input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo empty($newsletter["newsletter_id"]) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?>" />
        <input name="close" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
        <input type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='cmsgo.php?do=messages&amp;p=3';" />
     </div>

</form>
