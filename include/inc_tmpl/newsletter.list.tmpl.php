<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 */

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if(isset($_GET["s"])) {

    include_once PHPWCMS_ROOT.'/include/inc_lib/newsletter.form.inc.php';

    if(isset($_GET['edit'])) {
        include_once PHPWCMS_ROOT.'/include/inc_tmpl/newsletter.form.tmpl.php';
    }

    if(isset($_GET['send']) && $show_nl_send) {

        include_once PHPWCMS_ROOT.'/include/inc_tmpl/newsletter.send.tmpl.php';

    }

} else {

    if(isset($_GET['duplicate_nl'])) {
        @_dbDuplicateRow(   'phpwcms_newsletter', 'newsletter_id', intval($_GET['duplicate_nl']),
                            array('newsletter_active' => 0, 'newsletter_changed' => 'SQL:NOW()',
                            'newsletter_lastsending' => NULL, 'newsletter_created' => 'SQL:NOW()',
                            'newsletter_subject' => '--SELF-- (copy)'));
    }

// check if subscription should be edited


// create paginating for newsletter
if(isset($_GET['c'])) {
    $_SESSION['list_newsletter_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
// set default values for paginating
if(empty($_SESSION['list_newsletter_count'])) {
    $_SESSION['list_newsletter_count'] = 10;
}
// set page
if(isset($_GET['page'])) {
    $_SESSION['newsletter_page'] = intval($_GET['page']);
}

$_newsletter['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_newsletter WHERE newsletter_trashed=0", 'COUNT');
$_newsletter['pages_total'] = ceil($_newsletter['count_total'] / $_SESSION['list_newsletter_count']);
if(empty($_SESSION['newsletter_page'])) {
    $_SESSION['newsletter_page'] = 1;
}
if($_SESSION['newsletter_page'] > $_newsletter['pages_total']) {
    $_SESSION['newsletter_page'] = $_newsletter['pages_total'];
}
if($_SESSION['newsletter_page'] < 1) {
    $_SESSION['newsletter_page'] = 1;
}

?>

<div class="row align-items-center">
  <div class="col col-sm-auto text-center text-sm-start">
    <h1><?php echo $BL['be_subnav_msg_newslettersend'] ?></h1>
  </div>
  <div class="col-12 col-sm text-center text-sm-end mb-3">
    <div class="form-group align-items-center">
      <a class="btn btn-sm btn-blue me-1" role="button" aria-disabled="true" href="phpwcms.php?do=messages&amp;p=3&amp;s=0&amp;edit=1"><i class="fa fa-plus"></i> <?php echo $BL['be_newsletter_new'] ?></a>
    </div>
  </div>
</div>

<div class="card">
    <div class="card-header"><h2><i class="fa fa-list" aria-hidden="true"></i> <?php echo $BL['be_cnt_title_overview'] ?> <?php echo $BL['be_subnav_msg_newslettersend'] ?></h2></div>
    <div class="card-body">

    <div class="row align-items-center">

			<?php
			if($_newsletter['pages_total'] > 1) {
				echo '<div class="col-sm-auto">';
				echo '<div class="input-group">';
				if($_SESSION['newsletter_page'] > 1) {
						echo '';
						echo '<a class="btn btn-blue" href="phpwcms.php?do=messages&amp;p=3&amp;page='.($_SESSION['newsletter_page']-1).'">';
						echo '<i class="fa fa-angle-left fa-fw"></i></a>';
						echo '';
				} else {
						echo '';
						echo '<a class="btn btn-blue disabled" href="phpwcms.php?do=messages&amp;p=3&amp;page='.($_SESSION['newsletter_page']-1).'">';
						echo '<i class="fa fa-angle-left fa-fw"></i></a>';
						echo '';
				}
				echo '<input type="number" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['newsletter_page'];
				echo '"  class="form-control fw-bold w-25" />';
				echo '';
				echo '<label class="input-group-text" for="page">/'.$_newsletter['pages_total'].'&nbsp;</label>';
				if($_SESSION['newsletter_page'] < $_newsletter['pages_total']) {
						echo '<a class="btn btn-blue" href="phpwcms.php?do=messages&amp;p=3&amp;page='.($_SESSION['newsletter_page']+1).'">';
						echo '<i class="fa fa-angle-right fa-fw"></i></a>';
				} else {
						echo '<a class="btn btn-blue disabled" href="phpwcms.php?do=messages&amp;p=3&amp;page='.($_SESSION['newsletter_page']+1).'">';
						echo '<i class="fa fa-angle-right fa-fw"></i></a>';
				}
				echo '</div></div>';
			} else {
				echo '<input type="hidden" name="page" id="page" value="1" />';
			}
			?>

      <div class="col"></div>

      <div class="col-12 col-sm-auto text-end">
          <select class="form-select form-select-sm">
              <option <?php echo ($_SESSION['list_newsletter_count'] == '') ? 'selected ' : ''; ?>><?php echo $BL['be_article_rendering'] ?></option>
              <option <?php echo ($_SESSION['list_newsletter_count'] == '5') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=3&amp;c=5'">5</option>
              <option <?php echo ($_SESSION['list_newsletter_count'] == '10') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=3&amp;c=10'">10</option>
              <option <?php echo ($_SESSION['list_newsletter_count'] == '25') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=3&amp;c=25'">25</option>
              <option <?php echo ($_SESSION['list_newsletter_count'] == '50') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=3&amp;c=50'">50</option>
              <option <?php echo ($_SESSION['list_newsletter_count'] == '100') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=3&amp;c=100'">100</option>
              <option <?php echo ($_SESSION['list_newsletter_count'] == '99999') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=3&amp;c=all'"><?php echo $BL['be_ftptakeover_all'] ?></option>
          </select>
      </div>
    </div>

		<div class="table-responsive">
    <table class="table table-sm table-valign-middle mt-3 mb-0">
    <thead>
      <tr>
        <th>&nbsp;</th>
        <th style="text-align:left"><?php echo $BL['be_msg_subject'] ?></th>
        <th style="text-align:left"><?php echo $BL['be_profile_label_lang'] ?></th>
        <th><?php echo $BL['be_article_cnt_start'] ?></th>
        <th><?php echo str_replace(' ', '<br />', $BL['be_last_sending']) ?></th>
        <th><?php echo $BL['be_total'].'/<br />'.$BL['be_cnt_queued'].'/<br />'.$BL['be_msg_senttop'].'/<br />'.$BL['be_msg_opend'] ?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <?php

      // loop listing available newsletters
      $sql  = "SELECT *, UNIX_TIMESTAMP(newsletter_pub) AS cdate, UNIX_TIMESTAMP(newsletter_lastsending) AS lastsend FROM ".DB_PREPEND."phpwcms_newsletter WHERE newsletter_trashed=0 ORDER BY newsletter_pub DESC, newsletter_changed DESC";
      $sql .= " LIMIT ".(($_SESSION['newsletter_page']-1) * $_SESSION['list_newsletter_count']).','.$_SESSION['list_newsletter_count'];

      $result = _dbQuery($sql);

      if(isset($result[0]['newsletter_id'])) {

        $row_count = 0;

        foreach($result as $row) {

          $row['newsletter_vars'] = unserialize($row['newsletter_vars'], ['allowed_classes' => false]);

          echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow">'.LF;
          echo '<td>';

          // sent/queue status
          $count_sent       = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=1 AND queue_pid='.$row["newsletter_id"], 'COUNT');
          $count_queue      = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=0 AND queue_pid='.$row["newsletter_id"], 'COUNT');
          $count_recipient  = countNewsletterRecipients($row['newsletter_vars']['subscription']);
          $count_opener     = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=1 AND queue_opener=1 AND queue_pid='.$row["newsletter_id"], 'COUNT');

          if(empty($row["newsletter_active"]) || !$count_queue) {
            echo '<i class="far fa-newspaper fa-fw" aria-hidden="true"></i>';
          } else {
            echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"];
            echo '&amp;send=1"><i class="fas fa-paper-plane fa-fw" aria-hidden="true"></i></a>';
          }

          echo '</td>'.LF;

          echo '<td class="dir"><strong>'.html($row["newsletter_subject"])."</strong></td>\n";
          echo '<td class="dir">'.(!$row["newsletter_lang"] ? '' : '<span class="flag-icon flag-icon-'.$row["newsletter_lang"].'"></span>')."</td>\n";
          // create date
          echo '<td class="v10 text-nowrap">&nbsp;';
          if($row['cdate']) {
            echo @date($BL['be_shortdate'], $row['cdate']);
          }
          echo '&nbsp;</td>';
          // last sending
          echo '<td class="text-nowrap">&nbsp;';
          if($row['lastsend']) {
            @date($BL['be_shortdate'], $row['lastsend']);
          }
          echo '&nbsp;</td>';

          echo '<td class="v10 text-nowrap" align="center">'.$count_recipient.'/'.$count_queue.'/'.$count_sent.'/'.$count_opener;
          if($count_sent && !$count_queue && $row["newsletter_active"]) {
            echo '<i class="fas fa-check-circle text-success ms-1" title="valid"></i>';
          }
          echo '&nbsp;</td>';

          // buttons
          echo '<td class="text-end text-nowrap">';
          echo '<div class="btn-group btn-group-sm" role="group" aria-label="nl-actions-'.$row["newsletter_id"].'">';

          // edit
          echo '<a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="'.$BL['be_tt_edit'].'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"].'&amp;edit=1"><i class="fa fa-pencil-alt fa-fw"></i></a>';

          // duplicate
          echo '<a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="'.$BL['be_tt_duplicate'].'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=3&amp;duplicate_nl='.$row["newsletter_id"].'"><i class="fa fa-copy fa-fw"></i></a>';
          echo '</div>';

          // delete
          echo '<a class="btn btn-sm btn-danger ms-1" role="button" aria-disabled="true" title="'.$BL['be_tt_delete'].' '.html_specialchars($row["newsletter_subject"]).'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"].'&amp;del='.$row["newsletter_id"].'" onclick="return confirm(\''.$BL['be_delete_dataset'].' '.js_singlequote($row["newsletter_subject"]).'\');"><i class="far fa-trash-alt fa-fw"></i></a>';

          echo "</td>\n</tr>\n";

          $row_count++;

        }
      }

    ?>
    </table>
    </div>
  </div>
</div>

<div class="form-group text-center text-sm-end mt-4">
  <a class="btn btn-sm btn-blue me-1" role="button" aria-disabled="true" href="phpwcms.php?do=messages&amp;p=3&amp;s=0&amp;edit=1"><i class="fa fa-plus"></i> <?php echo $BL['be_newsletter_new'] ?></a>
</div>
<?php

}
