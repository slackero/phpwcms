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
?>

<h1 class="text-center text-sm-start"><?php echo $BL['be_alias'] ?></h1>
<div class="card mb-2">
  <div class="card-header"><h2><?php echo $BL['be_article_urlalias'] ?> <?php echo $BL['be_ftptakeover_active'] ?></h2></div>
  <div class="card-body">

<?php
// now retrieve all structur items
$sql  = "SELECT *, DATE_FORMAT(acat_tstamp, '%Y-%m-%d') AS acat_timestamp ";
$sql .= "FROM ".DB_PREPEND."articlecat WHERE ";
$sql .= "acat_public=1 AND acat_aktiv=1 AND acat_trash=0 ";
$sql .= "ORDER BY acat_alias";

$result = _dbQuery($sql);
if(isset($result[0]['acat_id'])) {
  $x = 0;
  echo '<form action="" method="post" name="editstructur">';
   echo '<table class="table table-sm table-hover table-valign-middle mb-0">';

  foreach($result as $data) {

    // now add article URL
    echo '<tr>';
    echo '<td>';
    echo '<span class="badge '.(empty($data["acat_alias"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</span>';
    echo '<span class="badge '.(empty($data["acat_pagetitle"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_pagetitle'].'">T</span>';

    $sql = "SELECT * FROM ".DB_PREPEND."template WHERE template_trash=0 AND template_id = " . $data["acat_template"];
    $content['current_template'] = _dbGet('template', '*', 'template_trash=0 AND template_id='._dbEscape($data["acat_template"]), '', '', 1);
    echo '<span class="ms-2">' . $content['current_template'][0]['template_name'] . ' | ' . '</span>';
    echo '<a href="phpwcms.php?do=articles&p=6&struct=0&cat='.$data["acat_id"].'">'.(empty($data["acat_alias"]) ? 'no alias' : html_specialchars($data["acat_alias"]) ).'</a>';
	  echo '</td >';

		echo '<td class="text-end">';
    echo '<a href="phpwcms.php?do=articles&p=6&struct=0&cat='.$data["acat_id"].'" class="btn btn-sm btn-blue float-end" title="'.$BL['be_func_struct_sedit'].'" data-bs-toggle="tooltip"><i class="fa-solid fa-pencil-alt"></i></a>';
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
$sql .= "FROM ".DB_PREPEND."article WHERE ";
$sql .= "article_public=1 AND article_aktiv=1 AND article_deleted=0 ";
$sql .= "ORDER BY article_alias";

$result = _dbQuery($sql);
if(isset($result[0]['article_id'])) {
  $x = 0;
  echo '<form action="" method="post" name="editartikel">';
  echo '<table class="table table-sm table-hover table-valign-middle mb-0">';
  foreach($result as $data) {

    // now add article URL
    echo '<tr>';
    echo '<td>';
    echo '<span class="badge '.(empty($data["article_alias"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</span>';
    echo '<span class="badge '.(empty($data["article_description"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_article_description'].'">D</span>';
    echo '<a class="ms-2" href="phpwcms.php?do=articles&p=2&s=1&id='.$data["article_id"].'">'.(empty($data["article_alias"]) ? 'no alias' : html_specialchars($data["article_alias"]) ).'</a>';
    echo '</td >';

		echo '<td class="text-end">';
    echo '<a href="phpwcms.php?do=articles&p=2&s=1&id='.$data["article_id"].'" class="btn btn-sm btn-blue" title="'.$BL['be_func_struct_edit'].'" data-bs-toggle="tooltip"><i class="fa-solid fa-pencil-alt"></i></a>';
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

