<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// set backend listing values
$_cmsgo_home['homeMaxArticles'] = empty($_COOKIE['homeMaxArticles']) ? 10 : intval($_COOKIE['homeMaxArticles']);
$_cmsgo_home['homeMaxCntParts'] = empty($_COOKIE['homeMaxCntParts']) ? 10 : intval($_COOKIE['homeMaxCntParts']);
$_cmsgo_home['homeCntType'] = empty($_COOKIE['homeCntType']) ? '' : $_COOKIE['homeCntType'];

if (isset($_POST['homeMaxArticles'])) {
    if ($_cmsgo_home['homeMaxArticles'] = intval($_POST['homeMaxArticles'])) {
        @setcookie('homeMaxArticles', strval($_cmsgo_home['homeMaxArticles']), time()+31536000); // store cookie for 1 year
    }
}
if (isset($_POST['homeMaxCntParts'])) {
    if ($_cmsgo_home['homeMaxCntParts'] = intval($_POST['homeMaxCntParts'])) {
        @setcookie('homeMaxCntParts', strval($_cmsgo_home['homeMaxCntParts']), time()+31536000); // store cookie for 1 year
    }
    $_cmsgo_home['homeCntType'] = clean_slweg($_POST['homeCntType']);
    @setcookie('homeCntType', $_cmsgo_home['homeCntType'], time()+31536000); // store cookie for 1 year
    $_SESSION['cmsgo_backend_search'] = '';
}

// set if user has admin rights
$_usql = $_SESSION["wcs_user_admin"] ? '' : 'AND article_uid='.intval($_SESSION["wcs_user_id"]).' ';

// first list last edited articles
$_asql_1  = "SELECT *, DATE_FORMAT(acontent_tstamp, '".$BL['be_sqlshortdatetime']."') AS acontent_changed FROM ".DB_PREPEND."cmsgo_articlecontent t1 ";
$_asql_1 .= "LEFT JOIN ".DB_PREPEND."cmsgo_article t2 ON ";
$_asql_1 .= "t1.acontent_aid = t2.article_id ";
$_asql_1 .= 'WHERE t1.acontent_trash=0 AND t2.article_deleted=0 ';
$_asql_1 .= $_usql;
if (is_intval($_cmsgo_home['homeCntType'])) {
    $_asql_1 .= ' AND t1.acontent_type=' . _dbEscape($_cmsgo_home['homeCntType']);
}
if (!empty($_SESSION['cmsgo_backend_search'])) {
    $_asql_1 .= " AND (";
    $_asql_1 .= " CONCAT(t1.acontent_title,t1.acontent_subtitle,t1.acontent_text,t1.acontent_html) LIKE '%"._dbEscape($_SESSION['cmsgo_backend_search'], false)."%'";
    $_asql_1 .= " OR ";
    $_asql_1 .= " CONCAT(t2.article_title,t2.article_subtitle,t2.article_summary) LIKE '%"._dbEscape($_SESSION['cmsgo_backend_search'], false)."%'";
    $_asql_1 .= " ) ";

    $_be_search = $BL['be_ctype_search'].': ' . html($_SESSION['cmsgo_backend_search']) ;
} else {
    $_be_search = $BL['be_last_edited'];
}
$_asql_1 .= ' ORDER BY acontent_tstamp DESC LIMIT '.$_cmsgo_home['homeMaxCntParts'];
$_last10_articlecontent = _dbQuery($_asql_1);

$_asql_1  = "SELECT article_id, article_cid, article_title, article_subtitle, article_aktiv, article_uid, article_lang, ";
$_asql_1 .= "date_format(article_tstamp, '".$BL['be_sqlshortdatetime']."') AS article_date ";
$_asql_1 .= 'FROM '.DB_PREPEND.'cmsgo_article WHERE article_deleted=0 ';
$_asql_1 .= $_usql;
if (!empty($_SESSION['cmsgo_backend_search'])) {
    $_asql_1 .= " AND CONCAT(article_title,article_subtitle,article_summary) LIKE '%"._dbEscape($_SESSION['cmsgo_backend_search'], false)."%' ";
}
$_asql_1 .= 'ORDER BY article_tstamp DESC LIMIT '.$_cmsgo_home['homeMaxArticles'];
$_last10_article = _dbQuery($_asql_1);

?>

<div class="dashboard card mb-4">
<div class="card-header">
	<div class="row align-items-center">
		<div class="col">
			<h2><?php echo $BL['be_cnt_articles'] .' <span class="smalltext">('. $_be_search . ')</span>' ?></h2>
		</div>
		<div class="col-sm-auto text-right">
			<form class="formRightInput" action="cmsgo.php" id="setHomeMaxArticles" name="setHomeMaxArticles" method="post">
				<select class="custom-select form-control form-control-sm" name="homeMaxArticles" onchange="this.form.submit();">
					<?php foreach (array(5,10,15,25,50,75,100,150) as $x): ?>
					<option value="<?php echo $x ?>"<?php is_selected($_cmsgo_home['homeMaxArticles'], $x) ?>><?php echo $x ?></option>
					<?php endforeach; ?>
					<option value="99999"<?php is_selected(99999, $_cmsgo_home['homeMaxArticles']) ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
				</select>
			</form>
		</div>
	</div>
</div>
<div class="card-body">
<table class="table table-sm" border="0" cellpadding="0" cellspacing="0" summary="">
  <thead class="thead-default">
  <tr class="bg-grey">
    <th class="text-left" width="90%"><?php echo $BL['be_article_atitle'] ?></th>
    <th class="nowrap"><?php echo $BL['be_cnt_last_edited'] ?></th>
    <th>&nbsp;</th>
  </tr>
  </thead>
<?php
  if (count($_last10_article)) {
      $row_count = 0;
      foreach ($_last10_article as $value) {
          echo '<tr>';
          echo '  <td class="overflow-ellipsis home-article">'.html($value['article_title']);
          if ($value['article_subtitle']) {
              echo ' / ' . html($value['article_subtitle']);
          }
          echo '</td>';
          echo '  <td class="nowrap">&nbsp;'.$value['article_date'].'&nbsp;</td>';
          echo '  <td class="text-right text-nowrap">';
          if(count($cmsgo['allowed_lang'])) {
              echo '<span class="mr-3 flag-icon flag-icon-' . ($lang = strtolower(empty($value["article_lang"]) ? $cmsgo['default_lang'] : $value["article_lang"])) . '" title="' . get_language_name($lang) . '"></span>';
          }
          echo '<button id="abtnarticle'.$value['article_id'].'" class="btn fa btn-sm visible '.($value["article_aktiv"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$value['article_id'].'" data-type="article" data-table="article" data-field="article_aktiv" data-fieldid="article_id" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';
          echo '<a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="'.$BL['be_func_struct_edit'].'" data-toggle="tooltip" href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'];
          echo '"><i class="fa fa-pencil-alt"></i></a>';
          echo '</td>';
          echo '</tr>';
          $row_count++;
      }
  }
?>
</table>
    <input type="button" value="<?php echo $BL['be_subnav_article_center'] ?>" class="btn btn-sm btn-blue" onclick="document.location.href='cmsgo.php?do=articles'" />
    <input type="button" value="<?php echo $BL['be_subnav_article_new'] ?>" class="btn btn-sm btn-blue" onclick="document.location.href='cmsgo.php?do=articles&amp;p=1&amp;struct=0'" />
  </div>
</div>

<div class="dashboard card mb-4">
<div class="card-header">
	<div class="row align-items-center">
		<div class="col">
  			<h2><?php echo $BL['be_ctype'] .' <span class="smalltext">('. $_be_search .')</span>' ?></h2>
  		</div>
  		<div class="col">
		<form class="formRightInput float-right" action="cmsgo.php" id="setHomeMaxCntParts" name="setHomeMaxCntParts" method="post">
		<div class="form-row">
			<div class="col">
			<select class="custom-select form-control form-control-sm" name="homeCntType" onChange="this.form.submit();">
				<option value="">&#8211;</option>
				<?php foreach ($wcs_content_type as $key => $value): ?>
				<option value="<?php echo $key ?>"<?php is_selected($_cmsgo_home['homeCntType'], $key) ?>><?php echo $value ?></option>
				<?php endforeach; ?>
			</select>
			</div>
			<div class="col">
			<select class="custom-select form-control form-control-sm" name="homeMaxCntParts" onchange="this.form.submit();">
				<?php foreach (array(5,10,15,25,50,75,100,150,200,250) as $x): ?>
				<option value="<?php echo $x ?>"<?php is_selected($_cmsgo_home['homeMaxCntParts'], $x) ?>><?php echo $x ?></option>
				<?php endforeach; ?>
				<option value="99999"<?php is_selected(99999, $_cmsgo_home['homeMaxCntParts']) ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
			</select>
			</div>
		</div>
		</form>
	</div>
</div>
</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-sm mb-0" border="0" cellpadding="0" cellspacing="0" summary="">
  <thead class="thead-default">
  <tr class="bg-grey">
    <th style="text-align:left" class="nowrap"><?php echo $BL['be_cnt_type'] ?>&nbsp;</th>
    <th style="text-align:left"><?php echo $BL['be_article_atitle'].'/'.$BL['be_profile_label_notes'] ?></th>
    <th class="nowrap"><?php echo $BL['be_cnt_last_edited'] ?>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  </thead>
<?php

  if (count($_last10_articlecontent)) {
      $row_count = 0;

      foreach ($_last10_articlecontent as $value) {
          if (($value["acontent_type"] == 30 && !isset($cmsgo['modules'][$value["acontent_module"] ])) || !isset($wcs_content_type[$value["acontent_type"]])) {
              continue;
          }

          if ($row_count) {
          }

          echo '<tr>'.LF;

          echo '  <td class="overflow-ellipsis home-type nowrap">'.$wcs_content_type[$value["acontent_type"]];
          if ($value["acontent_type"] == 30) {
              echo ': '.$BL['modules'][$value["acontent_module"]]['listing_title'];
          }
          echo '&nbsp;</td>'.LF;

          $value['notice'] = str_replace('###', ', ', trim($value['acontent_title'].'###'.$value['acontent_subtitle'].'###'.$value['acontent_comment'], '#'));
          if ($value['notice']) {
              $value['notice_long'] = $value['article_title'] . ' > ' . $value['notice'];
              $value['notice'] = getCleanSubString($value['article_title'], 15, '.') . ' > ' . $value['notice'];
          } else {
              $value['notice_long'] = $value['notice'] = $value['article_title'];
          }

          $value['notice'] = html(preg_replace('/\s+/', ' ', $value['notice'], false));

          echo '  <td class="overflow-ellipsis home-cp" style="font-weight:normal" width="90%">'.$value['notice'].'</td>'.LF;
          echo '  <td class="nowrap">&nbsp;'.$value['acontent_changed'].'&nbsp;</td>'.LF;
          echo '  <td class="text-right text-nowrap">';
          echo '<button id="abtncontent'.$value['acontent_id'].'" class="btn fa btn-sm visible '.($value["acontent_visible"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$value['acontent_id'].'" data-type="content" data-table="articlecontent" data-field="acontent_visible" data-fieldid="acontent_id" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';
          echo '<a class="btn btn-sm btn-blue" title="'.$BL['be_func_content_edit'].'" data-toggle="tooltip" href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;';
          echo 'id='.$value['acontent_aid'].'&amp;acid='.$value['acontent_id'];
          echo '"><i class="fa fa-pencil-alt"></i></a>';
          echo '</td>'.LF;
          echo '</tr>'.LF;
          $row_count++;
      }
  }

?>
</table>
</div>
</div>
</div>
