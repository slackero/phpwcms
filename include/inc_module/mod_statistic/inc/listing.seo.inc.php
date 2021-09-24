<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
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
$_controller_link =  statistic_url('controller=seo');

// create pagination
if(isset($_GET['c'])) {
    $_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['seolog_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
    $_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

    $_SESSION['list_active']  = empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;

    $_SESSION['seo_filter']     = clean_slweg($_POST['filter']);
    if(empty($_SESSION['seo_filter'])) {
        unset($_SESSION['seo_filter']);
    } else {
        $_SESSION['seo_filter'] = convertStringToArray($_SESSION['seo_filter'], ' ');
        $_POST['filter']  = $_SESSION['seo_filter'];
    }

    $_SESSION['seolog_page'] = intval($_POST['page']);

}

if(empty($_SESSION['seolog_page'])) {
    $_SESSION['seolog_page'] = 1;
}

$_entry['list_active']    = isset($_SESSION['list_active']) ? $_SESSION['list_active']    : 1;
$_entry['list_inactive']  = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive']  : 1;


$_entry['query'] = '1=1';

if(isset($_SESSION['seo_filter']) && is_array($_SESSION['seo_filter']) && count($_SESSION['seo_filter'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['seo_filter'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = "CONCAT(domain,query) LIKE '%".aporeplace($_entry['filter'])."%'";
    }
    if(count($_entry['filter_array'])) {

        $_SESSION['seo_filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['seo_filter'];

    }

} elseif(isset($_SESSION['seo_filter']) && is_string($_SESSION['seo_filter'])) {

    $_entry['query'] .= $_SESSION['seo_filter'];

}


// paginating values
$_entry['count_total'] = _dbQuery('SELECT * FROM '.DB_PREPEND.'cmsgo_log_seo WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['seolog_page'] > $_entry['pages_total']) {
    $_SESSION['seolog_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_seo'] ?></h2></div>
  <div class="card-body">

    <form action="<?php echo $_controller_link ?>" method="post" name="paginate" id="paginate">
      <input type="hidden" name="do_pagination" value="1" />
      <div class="row align-items-center mb-4">

          <?php
          if($_entry['pages_total'] > 1) {
            echo '<div class="col-sm-auto text-right">';
            echo '<table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td>';
            if($_SESSION['seolog_page'] > 1) {
                echo '<a class="btn btn-sm btn-blue" href="'.$_controller_link.'&amp;page='.($_SESSION['seolog_page']-1).'">';
                echo '<i class="fa fa-angle-left"></i></a>';
            } else {
                echo '<a class="btn btn-sm btn-blue disabled" href="'.$_controller_link.'&amp;page='.($_SESSION['seolog_page']-1).'">';
                echo '<i class="fa fa-angle-left"></i></a>';
            }
            echo '</td>';
            echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['seolog_page'];
            echo '" class="form-control form-control-sm" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
            echo '<td>/'.$_entry['pages_total'].'&nbsp;</td>';
            echo '<td>';
            if($_SESSION['seolog_page'] < $_entry['pages_total']) {
                echo '<a class="btn btn-sm btn-blue" href="'.$_controller_link.'&amp;page='.($_SESSION['seolog_page']+1).'">';
                echo '<i class="fa fa-angle-right"></i></a>';
            } else {
                echo '<a class="btn btn-sm btn-blue disabled" href="'.$_controller_link.'&amp;page='.($_SESSION['seolog_page']+1).'">';
                echo '<i class="fa fa-angle-right"></i></a>';
            }
            echo '</td></tr></table></div>';
          } else {
            echo '<input type="hidden" name="page" id="page" value="1" />';
          }
          ?>

          <div class="col-sm-auto">
              <div class="input-group">
                  <input name="filter" id="filter" size="15" data-toggle="tooltip" title="Filtern" class="form-control form-control-sm" value="<?php
                  if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
                    echo htmlentities(implode(' ', $_POST['filter']));
                  }
                  ?>" type="search">
                  <span class="input-group-append">
                      <input class="btn btn-sm btn-secondary" name="gofilter" value="Filter" type="submit">
                  </span>
              </div>
          </div>

          <div class="col-sm-auto text-right">
              <select class="custom-select">
                  <option selected>Anzeige</option>
                  <option onClick="window.location = '<?php echo statistic_url('controller=seo') ?>&amp;c=5'">5</option>
                  <option onClick="window.location = '<?php echo statistic_url('controller=seo') ?>&amp;c=10'">10</option>
                  <option onClick="window.location = '<?php echo statistic_url('controller=seo') ?>&amp;c=25'">25</option>
                  <option onClick="window.location = '<?php echo statistic_url('controller=seo') ?>&amp;c=50'">50</option>
                  <option onClick="window.location = '<?php echo statistic_url('controller=seo') ?>&amp;c=100'">100</option>
                  <option onClick="window.location = '<?php echo statistic_url('controller=seo') ?>&amp;c=all'"><?php echo $BL['be_ftptakeover_all'] ?></option>
              </select>
          </div>
      </div>
    </form>

    <table class="table table-sm">
<?php
// loop listing available seo entries
$row_count = 0;

$sql  = 'SELECT * FROM '.DB_PREPEND.'cmsgo_log_seo WHERE '.$_entry['query'].' ORDER BY create_date DESC ';
$sql .= 'LIMIT '.(($_SESSION['seolog_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$data = _dbQuery($sql);

#print_r($sql);

foreach($data as $row) {

  echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).'>';
  echo '<td class="tdbottom3 tdtop3" nowrap="nowrap">'.$row['create_date'].'&nbsp;</td>';
  echo '<td class="tdbottom3 tdtop3"><a href="';
  echo html_specialchars($row['referrer']).'" target="_blank">'.html_specialchars($row['domain']);
  echo '</a></td>';
   echo '<td class="tdbottom3 tdtop3" align="center">&nbsp;'.$row['pos'].'&nbsp;</td>';
  echo '<td class="tdbottom3 tdtop3">';
  echo html_specialchars(CMSGO_CHARSET != 'utf-8' && cmsgo_seems_utf8($row['query']) ? makeCharsetConversion($row['query'], 'utf-8', CMSGO_CHARSET, false) : $row['query']);
  echo '</td>';
  echo "</tr>\n";

  $row_count++;
}
?>
    </table>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_seo_top'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm">

<?php
$sql  = 'SELECT Count(query) AS Anzahl, query FROM '.DB_PREPEND.'cmsgo_log_seo GROUP BY query ORDER BY Anzahl DESC LIMIT 0,20';
$data = _dbQuery($sql);

$row_count = 0;

foreach($data as $row) {

  echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).'>';
  echo '<td class="tdbottom3 tdtop3">'.$row['Anzahl'].'</td>';
  echo '<td class="tdbottom3 tdtop3">';
  echo html_specialchars(CMSGO_CHARSET != 'utf-8' && cmsgo_seems_utf8($row['query']) ? makeCharsetConversion($row['query'], 'utf-8', CMSGO_CHARSET, false) : $row['query']);
  echo '</td>';
  echo "</tr>\n";

  $row_count++;
}
?>
    </table>
  </div>
</div>

</div>
</div>