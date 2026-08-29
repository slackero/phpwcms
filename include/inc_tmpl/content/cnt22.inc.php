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

// RSS feed
if(!isset($content['rssfeed'])) {
	$content['rssfeed']["rssurl"]		= '';
	$content["rssfeed"]["item"]			= '';
	$content['rssfeed']["cut1st"]		= 0;
	$content['rssfeed']["cacheoff"]		= 0;
	$content['rssfeed']["timeout"]		= 0;
	$content["rssfeed"]['template'] 	= '';
	$content["rssfeed"]['content_type'] = '';
}
?>

<div class="form-group align-items-center row g-2">
  <label for="crss_template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</label>
  <div class="col-sm-4">
    <select name="crss_template" id="crss_template" class="form-select form-select-sm">
  <?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for RSS feed
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$vals = '';
		if($val == $content["rssfeed"]['template']) $vals= ' selected="selected"';
		$val = htmlspecialchars($val);
		echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
	}
}
?>
    </select>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="crss_url" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_rssfeed_url'] ?></label>
  <div class="col">
    <input name="crss_url" type="text" id="crss_url" class="form-control form-control-sm" value="<?php echo html($content['rssfeed']["rssurl"]) ?>">
  </div>
</div>

<div class="form-group align-items-center row g-2">
	<label for="crss_item" class="col-sm-2 col-form-label text-end"><?php echo  $BL['be_cnt_rssfeed_item'] ?></label>
	<div class="col-sm-4">
        <div class="input-group input-group-sm">
            <input name="crss_item" type="text" class="form-control form-control-sm" id="crss_item" maxlength="10" onKeyUp="if(!parseInt(this.value,10)){this.value='';}" value="<?php echo $content["rssfeed"]["item"] ?>">
            
                <span class="input-group-text"><?php echo $BL['be_cnt_rssfeed_max'] ?></span>
            
        </div>
	</div>
	<label for="crss_contenttype" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_source'].' '.$BL['content_type'] ?></label>
	<div class="col-sm-4">
		<select name="crss_contenttype" id="crss_contenttype" class="form-select form-select-sm">
            <option value=""<?php
                if(empty($content["rssfeed"]['content_type'])) {
                    echo ' selected="selected"';
                }
            ?>><?php echo $BL['automatic'] ?></option>
            <?php
                foreach($phpwcms['charsets'] as $value) {
                    echo '		<option value="'.$value.'"';
                    if(!empty($content["rssfeed"]['content_type']) && $value == $content["rssfeed"]['content_type']) {
                        echo ' selected="selected"';
                    }
                    echo '>'.$value.'</option>' . LF;
                }
            ?>
        </select>
	</div>
</div>

<div class="form-group align-items-center row g-2">
    <label for="crss_cacheoff" class="col-sm-2 col-form-label text-end"><?php echo  $BL['be_cache'] ?></label>
    <div class="col-sm-4">
  	    <div class="form-check form-check-inline">
			<input class="form-check-input" name="crss_cacheoff" type="checkbox" id="crss_cacheoff" value="1"<?php echo  is_checked(1, $content['rssfeed']["cacheoff"]) ?>>
			<label class="form-check-label" for="crss_cacheoff"><?php echo $BL['be_off'] ?></label>
        </div>
    </div>
	<label for="crss_cachetimeout" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cache_timeout'] ?></label>
	<div class="col-sm-4">
		<select name="crss_timeout" id="crss_cachetimeout" class="form-select form-select-sm" onChange="document.articlecontent.crss_cacheoff.checked=false;">
            <?php
            echo '<option value="0"'.is_selected($content['rssfeed']["timeout"], '0', 0, 0).'>'.$BL['be_admin_tmpl_default']."</option>\n";
            echo '<option value="60"'.is_selected($content['rssfeed']["timeout"], '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
            echo '<option value="300"'.is_selected($content['rssfeed']["timeout"], '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
            echo '<option value="900"'.is_selected($content['rssfeed']["timeout"], '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
            echo '<option value="1800"'.is_selected($content['rssfeed']["timeout"], '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
            echo '<option value="3600"'.is_selected($content['rssfeed']["timeout"], '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
            echo '<option value="14400"'.is_selected($content['rssfeed']["timeout"], '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
            echo '<option value="43200"'.is_selected($content['rssfeed']["timeout"], '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
            echo '<option value="86400"'.is_selected($content['rssfeed']["timeout"], '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
            echo '<option value="172800"'.is_selected($content['rssfeed']["timeout"], '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
            echo '<option value="604800"'.is_selected($content['rssfeed']["timeout"], '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
            echo '<option value="1209600"'.is_selected($content['rssfeed']["timeout"], '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
            echo '<option value="2592000"'.is_selected($content['rssfeed']["timeout"], '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
            ?>
        </select>
	</div>
</div>
