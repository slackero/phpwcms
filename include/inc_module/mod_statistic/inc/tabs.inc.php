<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$_entry['query']      = '';

$sql  = "SELECT ar.article_id, ar.article_title ";
$sql .= "FROM ".DB_PREPEND."cmsgo_article ar LEFT JOIN ".DB_PREPEND."cmsgo_articlecontent ac ON ";
$sql .= "ar.article_id = ac.acontent_aid WHERE ";
$sql .= "ar.article_public=1 AND  ar.article_aktiv=1 AND ";
$sql .= "ar.article_deleted=0 AND ";
$sql .= "(ar.article_begin IS NULL OR ar.article_begin<NOW()) AND ";
$sql .= "(ar.article_end IS NULL OR ar.article_end>NOW()) AND ";
$sql .= "ac.acontent_type = ";
?>

<h1><?php echo $BLM['listing_title'] ?></h1>

<div class="card mb-2">
  <div class="card-body">
      <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link<?php if($controller == 'overview') echo ' active'; ?>" href="<?php echo statistic_url() ?>"><?php echo $BLM['tab_overview'] ?></a></li>
     <?php
        $counter = _dbQuery("SELECT f_id FROM ".DB_PREPEND."cmsgo_file WHERE f_trash = 0 AND f_dlstart > 0", "COUNT");
      if ($counter > 0 ) {
    ?>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'downloads') echo ' active'; ?>" href="<?php echo statistic_url('controller=downloads') ?>"><?php echo $BLM['tab_downloads'] ?></a></li>
    <?php
      }
      $counter = _dbQuery("SELECT address_id FROM ".DB_PREPEND."cmsgo_address", "COUNT");
      if ($counter > 0 ) {
    ?>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'subscriptions') echo ' class="activeTab"'; ?>" href="<?php echo statistic_url('controller=subscriptions') ?>"><?php echo $BLM['tab_subscriptions'] ?></a></li>
    <?php }  ?>

        <li class="nav-item"><a class="nav-link<?php if($controller == 'seo') echo ' active'; ?>" href="<?php echo statistic_url('controller=seo') ?>"><?php echo $BLM['tab_seo'] ?></a></li>
    <?php
      $counter = _dbQuery($sql."89", "COUNT");
      if ($counter > 0 ) {
    ?>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'polls') echo ' active'; ?>" href="<?php echo statistic_url('controller=polls') ?>"><?php echo $BLM['tab_polls'] ?></a></li>
    <?php }
      $counter = _dbQuery($sql."18", "COUNT");
      if ($counter > 0 ) {
     ?>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'guestbook') echo ' active'; ?>" href="<?php echo statistic_url('controller=guestbook') ?>"><?php echo $BLM['tab_guestbook'] ?></a></li>
    <?php } ?>

    <?php if(isset($_SESSION["wcs_user_admin"]) && $_SESSION["wcs_user_admin"] == 1) { ?>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'user') echo ' active'; ?>" href="<?php echo statistic_url('controller=user') ?>"><?php echo $BLM['tab_user'] ?></a></li>
    <?php }  ?>
      </ul>
