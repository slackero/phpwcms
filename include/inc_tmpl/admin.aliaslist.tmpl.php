<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
?>

<h1 class="text-center text-sm-left"><?php echo $BL['be_alias'] ?></h1>
<div class="card mb-2">
  <div class="card-header"><h2><?php echo $BL['be_article_urlalias'] ?> <?php echo $BL['be_ftptakeover_active'] ?></h2></div>
  <div class="card-body">

<?php
// now retrieve all structur items
$sql  = "SELECT *, DATE_FORMAT(acat_tstamp, '%Y-%m-%d') AS acat_timestamp ";
$sql .= "FROM ".DB_PREPEND."cmsgo_articlecat WHERE ";
$sql .= "acat_public=1 AND acat_aktiv=1 AND acat_trash=0 ";
$sql .= "ORDER BY acat_alias";

$result = _dbQuery($sql);
if(isset($result[0]['acat_id'])) {
  $x = 0;
  echo '<form action="" method="post" name="editstructur">';
   echo '<table class="table table-sm table-hover mb-0" id="catalias-'.$data["acat_id"].'">';

  foreach($result as $data) {

    // now add article URL
    echo '<tr>';
    echo '<td>';
    echo '<div class="btn btn-sm '.(empty($data["acat_alias"]) ? "btn-danger" : "btn-success").' mr-1 py-0" data-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</div>';
    echo '<div class="btn btn-sm '.(empty($data["acat_pagetitle"]) ? "btn-danger" : "btn-success").' mr-1 py-0" data-toggle="tooltip" title="'.$BL['be_acat_pagetitle'].'">T</div>';

    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_template WHERE template_trash=0 AND template_id = " . $data["acat_template"];
    $content['current_template'] = _dbGet('cmsgo_template', '*', 'template_trash=0 AND template_id='._dbEscape($data["acat_template"]), '', '', 1);
    echo '<span class="ml-2">' . $content['current_template'][0]['template_name'] . ' | ' . '</span>';
    echo '<a href="cmsgo.php?do=articles&p=6&struct=0&cat='.$data["acat_id"].'">'.(empty($data["acat_alias"]) ? 'no alias' : html_specialchars($data["acat_alias"]) ).'</a>';
	  echo '</td >';

		echo '<td class="text-right">';
    echo '<a href="cmsgo.php?do=articles&p=6&struct=0&cat='.$data["acat_id"].'" class="btn btn-sm btn-blue float-right" title="'.$BL['be_func_struct_sedit'].'" data-toggle="tooltip" onClick="'."AjaxLink('catalias-".$data["acat_id"]."','".$data["acat_id"]."','','" . $data["acat_template"] . "');".'"><i class="fa fa-pencil"></i></a>';
    echo "</td>" . LF;
    echo '</tr>';
    $x++;
  }
	echo '</table>';
  echo '</form>';
}
?>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BL['be_acat_urlalias'] ?> <?php echo $BL['be_ftptakeover_active'] ?></h2></div>
  <div class="card-body">
  <?php
// now retrieve all articles
$sql  = "SELECT *, DATE_FORMAT(article_tstamp, '%Y-%m-%d') AS article_timestamp ";
$sql .= "FROM ".DB_PREPEND."cmsgo_article WHERE ";
$sql .= "article_public=1 AND article_aktiv=1 AND article_deleted=0 ";
$sql .= "ORDER BY article_alias";

$result = _dbQuery($sql);
if(isset($result[0]['article_id'])) {
  $x = 0;
  echo '<form action="" method="post" name="editartikel">';
  echo '<table class="table table-sm table-hover mb-0" id="alias-'.$data["article_id"].'">';
  foreach($result as $data) {

    // now add article URL
    echo '<tr>';
    echo '<td>';
    echo '<div class="btn btn-sm '.(empty($data["article_alias"]) ? "btn-danger" : "btn-success").' mr-1 py-0" data-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</div>';
    echo '<div class="btn btn-sm '.(empty($data["article_description"]) ? "btn-danger" : "btn-success").' mr-1 py-0" data-toggle="tooltip" title="'.$BL['be_article_description'].'">D</div>';
    echo '<a class="ml-2" href="cmsgo.php?do=articles&p=2&s=1&id='.$data["article_id"].'">'.(empty($data["article_alias"]) ? 'no alias' : html_specialchars($data["article_alias"]) ).'</a>';
    echo '</td >';

		echo '<td class="text-right">';
    echo '<a href="cmsgo.php?do=articles&p=2&s=1&id='.$data["article_id"].'" class="btn btn-sm btn-blue" title="'.$BL['be_func_struct_edit'].'" data-toggle="tooltip" onClick="'."AjaxLink('alias-".$data["article_id"]."','','".$data["article_id"]."','');".'"><i class="fa fa-pencil"></i></a>';
    echo "</td>" . LF;
    echo '</tr>';
    $x++;
  }
  echo '</table>';
  echo '</form>';
}
?>

  </div>
</div>

<script type="text/javascript">

function AjaxLink(contentId, acat_id, article_id, template){
  $('[data-toggle="tooltip"], .tooltip').tooltip("hide");
  var url = 'include/inc_act/ajax_alias.php?action=form&acat_id=' + acat_id + '&article_id=' + article_id + '&template=' + template;
  $.ajax({ url: url }).done(function(data) {
    $("#"+contentId).html(data);
  });
}

function AjaxSubmit(contentId, article_id, article_alias, article_description){
  $.ajax({
    url:'include/inc_act/ajax_alias.php',
    data: {
      action: 'update',
      'article_alias': article_alias,
      'article_id': article_id,
      'article_description': article_description
    },
    success: function(data) {
      $("#"+contentId).html(data);
      $("#"+contentId).show();
    },
    error: function() {
      $("#"+contentId).html('The request failed.');
      $("#"+contentId).show();
    }
  })
}

function AjaxSubmitCat(contentId, acat_id, acat_alias, acat_pagetitle, template){
  $.ajax({
    url:'include/inc_act/ajax_alias.php',
    data: {
      action: 'updatecat',
      'acat_id': acat_id,
      'acat_alias': acat_alias,
      'acat_pagetitle': acat_pagetitle,
      'template': template
    },
    success: function(data) {
      $("#"+contentId).html(data);
      $("#"+contentId).show();
    },
    error: function() {
      $("#"+contentId).html('The request failed.');
      $("#"+contentId).show();
    }
  })
}

function AjaxClose(contentId, acat_id, article_id, template){
  var url = 'include/inc_act/ajax_alias.php?action=close&acat_id=' + acat_id + '&article_id=' + article_id + '&template=' + template;
  $.ajax({ url: url }).done(function(data) {
    $("#"+contentId).html(data);
  });
}

</script>
