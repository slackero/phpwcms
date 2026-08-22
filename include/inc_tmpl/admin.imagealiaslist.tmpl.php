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

echo '<h1 class="text-center text-sm-left">'.$BL['be_imagealias'].'</h1>';

// check if file alias field exists
$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_file LIKE 'f_alias'", 'COUNT_SHOW');

if(empty($result)) {
    _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_file ADD f_alias VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    echo $BL['f_alias'];
}

$sql =  "SELECT f_name, f_id FROM ".DB_PREPEND."phpwcms_file WHERE f_alias = '' AND f_hash <> '' AND f_trash=0 AND (f_ext like 'jpg' OR f_ext like 'gif' OR f_ext like 'png' OR f_ext like 'svg') ";

$emptyalias = _dbCount($sql);
$countstr = ($emptyalias > 0) ? $emptyalias.$BL['count'] : $BL['nocount'];

if(isset($_POST['keyword'])) {
    echo '<table class="table table-sm table-valign-middle mb-2">';
    $i=0;

    // update f-alias if no entry
    if(isset($_POST['limit'])) {
        $sqlquery = $sql." LIMIT ".intval($_POST['limit']);
    }

    $result = _dbQuery($sqlquery);
    if(isset($result[0]['f_id'])) {
    echo $BL['f_alias'];
        foreach($result as $files) {
            $filename = explode('.',$files['f_name']);
            //add additional Keyword
            if(isset($_POST['keyword']) AND trim($_POST['keyword']) <> "") {
                $filename[0] = clean_slweg($_POST['keyword'])."-".trim($filename[0]);
            }
            $filename[0] = clean_slweg(strtolower($filename[0]), 150);
            $filename[0] = phpwcms_remove_accents($filename[0]);
            $filename[0] = get_alnum_dashes($filename[0], true);
            $filename[0] = trim($filename[0]);
            if($filename[0] != '') {
                $filename[0] = trim( preg_replace('/_+/', '-', $filename[0]), '-' );
                $filename[0] = trim( preg_replace('/\-\-+/', '-', $filename[0]), '-' );
                $filename[0] = trim( preg_replace('/__+/', '_', $filename[0]), '_' );
            }

            $sql_count  = "SELECT COUNT(f_alias) FROM ".DB_PREPEND."phpwcms_file WHERE ";
            $sql_count .= "f_alias="._dbEscape(aporeplace($filename[0]));
            $f_count = _dbCount($sql_count);
            if ($f_count > 0) {
                $filename[0] = $filename[0]."-".$f_count;
            }

            $sql_alias =  "UPDATE ".DB_PREPEND."phpwcms_file SET f_alias = "._dbEscape($filename[0])." WHERE f_id = ".intval($files['f_id']);

						//anzeige Datenbanmeldung
            //echo  $sql_alias;
            _dbQuery($sql_alias, 'UPDATE');

            echo '<tr><td>';
            echo $filename[0];
            echo "</td></tr>";
        }
    }
    echo "</table>";
}
?>

<div class="card">
  <div class="card-header"><h2><?php echo $countstr ?></h2></div>
  <div class="card-body">
  <?php if ($emptyalias > 0) { ?>
  <form action="phpwcms.php?do=admin&amp;p=12" method="post" name="aliasform">
    <div class="input-group input-group-sm">
      <input class="form-control" type="text" name="keyword" id="keyword" size="30" value="<?php echo (isset($_POST['keyword']) ? htmlentities($_POST['keyword']) : ''); ?>" />
       <select name="limit" size="1" class="custom-select">
            <?php foreach (array(10,25,50,75,100,150) as $x): ?>
          <option value="<?php echo $x ?>"><?php echo $x ?></option>
          <?php endforeach; ?>
          <option value="99999"><?php echo $BL['be_ftptakeover_all'] ?></option>
       </select>
       <span class="input-group-append">
          <input class="btn btn-secondary" name="gofilter" value="Verarbeiten" type="submit">
      </span>
    </div>
  </form>

  <hr />
  <?php } ?>
  <form action="" method="post" name="editfileinfo">

<?php

$sql =  "SELECT f_alias, f_id, f_name, f_hash FROM ".DB_PREPEND."phpwcms_file WHERE f_alias <> '' AND f_hash <> '' AND f_trash=0 AND (f_ext like 'jpg' OR f_ext like 'gif' OR f_ext like 'png')";

$result = _dbQuery($sql);
if(isset($result[0]['f_id'])) {
  foreach($result as $files) {
    echo '<div id="alias-'.$files['f_id'].'" class="row align-items-center"><div class="col">';
    echo $files['f_alias'];
    $sql_count  = "SELECT COUNT(acontent_form) FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_form LIKE '%".$files['f_hash']."%'";
    $f_count1 = _dbCount($sql_count);
    $sql_count  = "SELECT COUNT(acontent_form) FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_image LIKE '%".$files['f_hash']."%'";
    $f_count2 = _dbCount($sql_count);
    $sql_count  = "SELECT COUNT(cnt_object) FROM ".DB_PREPEND."phpwcms_content WHERE cnt_object LIKE '%".$files['f_hash']."%'";
    $f_count3 = _dbCount($sql_count);
    echo '</div><div class="col-sm-auto"><a class="btn btn-sm btn-blue" href="#" onClick="'."AjaxLink('#alias-".$files['f_id']."', '".$files['f_id']."');".'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a></div>';
    echo "</div><hr class=\"my-1\">";
  }
}
echo "<div class=\"mt-3\"><strong>";
echo _dbCount($sql).$BL['counttotal']."";
echo "</strong></div>";
?>

  </form>
  </div>
</div>


<script type="text/javascript">

    function AjaxLink(contentId, file_id) {
        $.ajax({
            url: "include/inc_act/ajax_imagealias.php?<?php echo get_token_get_string(); ?>",
            xhrFields: {
                withCredentials: true
            },
            data: {
                action: 'form',
                'file_id': file_id
            },
            success: function (data) {
                $(contentId).html(data).show();
            },
            error: function () {
                $(contentId).html('The request failed.').show();
            }
        })
    }

    function AjaxSubmit(contentId, file_id, file_alias) {
        $.ajax({
            url: "include/inc_act/ajax_imagealias.php?<?php echo get_token_get_string(); ?>",
            xhrFields: {
                withCredentials: true
            },
            data: {
                action: 'form',
                'file_id': file_id,
                'file_alias': file_alias
            },
            success: function (data) {
                $(contentId).html(data).show();
            },
            error: function () {
                $(contentId).html('The request failed.').show();
            }
        })
    }

</script>
