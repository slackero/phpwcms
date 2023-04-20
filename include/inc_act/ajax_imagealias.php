<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// general wrapper for ajax based queries
$cmsgo = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/imagick.convert.inc.php';
require_once CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
if($_SESSION["wcs_user_lang_custom"]) { //use custom lang if available -> was set in edit.php
  include(CMSGO_ROOT.'/include/inc_lang/backend/'.substr($_SESSION["wcs_user_lang"],0,2).'/lang.inc.php');
  //Adding specific language files
  include CMSGO_ROOT.'/include/inc_lang/backend/'. substr($_SESSION["wcs_user_lang"],0,2) .'/lang.pp.inc.php';
}

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
    die('Sorry, access forbidden');
}

$file_alias   = isset($_GET['file_alias']) ? $_GET['file_alias'] : '';
$file_id    = isset($_GET['file_id']) ? intval($_GET['file_id']) : '';
$value    = isset($_POST['value']) ? $_POST['value'] : 'json';

// do charset conversions for value
if(CMSGO_CHARSET != 'utf-8') {
    if(function_exists('mb_convert_encoding')) {
        $value = mb_convert_encoding( $value, CMSGO_CHARSET, 'utf-8' );
    }
}

if($file_id && !$file_alias) {
    $sql = 'SELECT * FROM '.DB_PREPEND.'cmsgo_file WHERE f_id='.$file_id.' AND f_trash=0 AND f_kid=1 LIMIT 1;';
    $result = _dbQuery($sql);
    if(isset($result[0]['f_name'])) {
        $file_oldname = html_specialchars($result[0]["f_name"]);
        $file_created = intval($result[0]["f_created"]);
        $file_size    = intval($result[0]["f_size"]);
        $file_id    = $result[0]["f_id"];
        $file_ext   = $result[0]["f_ext"];
        $file_pid       = $result[0]["f_pid"];
        $file_name        = $result[0]["f_name"];
        //f_alias hinzugefügt
        $f_alias       = $result[0]["f_alias"];
        //trunk end
        $file_aktiv       = $result[0]["f_aktiv"];
        $file_public      = $result[0]["f_public"];
        $file_shortinfo     = $result[0]["f_shortinfo"];
        $file_longinfo      = $result[0]["f_longinfo"];
        $file_keys        = $result[0]["f_keywords"];
        $file_copyright     = $result[0]["f_copyright"];
        $file_tags        = $result[0]["f_tags"];
        $file_granted     = $result[0]["f_granted"];
        $file_gallerydownload = $result[0]["f_gallerystatus"];
        $file_sort        = $result[0]["f_sort"];

        if($file_keys) {
            $file_keys_temp = explode(":", $file_keys);
            if(count($file_keys_temp)) {
                if(isset($file_keywords)) unset($file_keywords);
                foreach($file_keys_temp as $value) {
                    list($k1, $k2) = explode("_", $value);
                    $file_keywords[intval($k1)] = intval($k2);
                }
            }
        }

        if(isset($result[0]["f_hash"])) {
            $thumb_image = get_cached_image(
                    array(  "target_ext"  =>  $result[0]["f_ext"],
                            "image_name"  =>  $result[0]["f_hash"] . '.' . $result[0]["f_ext"],
                            "thumb_name"  =>  md5($result[0]["f_hash"].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"])
                    )
                    );
            if($thumb_image != false) {
                $imagesrc = '<img class="mb-2" src="'.CMSGO_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' >';
            }
        }
    }
    ?>

    <div class="col-sm-3"><?php echo $imagesrc ?></div>
    <div class="col-sm-9 mb-2 mb-sm-0"><b><?php echo $BL['be_fprivedit_filename'] ?>: <?php echo html_specialchars($file_name) ?></b></div>

		<div class="col col-sm-6 input-group my-2">
			<input name="file_alias" type="text" class="form-control form-control-sm" id="file_alias<?php echo html_specialchars($file_id) ?>" value="<?php echo html_specialchars($f_alias) ?>"  maxlength="230" onfocus="set_file_alias(true);" onchange="this.value=create_alias(this.value);" />
			<div class="input-group-append">
				<input name="senden" type="button" onClick="AjaxSubmit(<?php echo "'#alias-".$file_id."', '".$file_id."', document.editfileinfo.file_alias".$file_id.".value"; ?>)" value="<?php echo $BL['be_save_btn'] ?>" class="btn btn-blue btn-sm" /></div>
			</div>
		</div>

<?php
}

if($file_id && $file_alias) {
  $file_alias = clean_slweg(strtolower($file_alias), 150);
  $file_alias = cmsgo_remove_accents($file_alias);
  $file_alias = get_alnum_dashes($file_alias, true);
  $file_alias = trim($file_alias);
  if($file_alias != '') {
    $file_alias = trim( preg_replace('/\-\-+/', '-', $file_alias), '-' );
    $file_alias = trim( preg_replace('/__+/', '_', $file_alias), '_' );
  }

  $f_count = _dbCount("SELECT COUNT(f_alias) FROM ".DB_PREPEND."cmsgo_file WHERE f_alias='".aporeplace($file_alias)."'");
  if ($f_count > 0) {
    $file_alias = $file_alias."-".$f_count;
  }

  $sql_alias =  "UPDATE ".DB_PREPEND."cmsgo_file SET f_alias = '".$file_alias."' WHERE f_id = ".$file_id;
   _dbQuery($sql_alias, 'UPDATE');

  echo '<div class="col">'.$file_alias;
  echo '</div><div class="col-sm-auto"><a class="btn btn-sm btn-blue" href="#" onClick="'."AjaxLink('#alias-".$file_id."', '".$file_id."');".'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a></div>';
}
?>
