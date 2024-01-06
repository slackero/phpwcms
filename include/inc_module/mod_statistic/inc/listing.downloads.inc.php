<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// create pagination
if(isset($_GET['c'])) {
    $_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['downloads_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
    $_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

    $_SESSION['download_filter']      = clean_slweg($_POST['filter']);
    if(empty($_SESSION['download_filter'])) {
        unset($_SESSION['download_filter']);
    } else {
        $_SESSION['download_filter']  = convertStringToArray($_SESSION['download_filter'], ' ');
        $_POST['filter']  = $_SESSION['filter'];
    }
    $_SESSION['list_search']      = clean_slweg($_POST['list_search']);
    if(empty($_SESSION['list_search'])) {
        unset($_SESSION['list_search']);
    }

    $_SESSION['downloads_page'] = intval($_POST['page']);
}

if(empty($_SESSION['downloads_page'])) {
    $_SESSION['downloads_page'] = 1;
}

$_entry['query'] = 'f_dlstart > 0 AND f_trash = 0';

if(isset($_SESSION['download_filter']) && is_array($_SESSION['download_filter']) && count($_SESSION['download_filter'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['download_filter'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = "CONCAT(f_name) LIKE '%".aporeplace($_entry['filter'])."%'";
    }
    if(count($_entry['filter_array'])) {
        $_SESSION['download_filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['download_filter'];
    }

} elseif(isset($_SESSION['download_filter']) && is_string($_SESSION['download_filter'])) {

    $_entry['query'] .= $_SESSION['download_filter'];

}
if(isset($_SESSION['list_search'])) {
    $_entry['sort'] = empty($_SESSION['list_search']) ? 'f_dlstart' : $_SESSION['list_search'];
} else {
    $_SESSION['list_search'] = 'f_dlstart';
    $_entry['sort'] = 'f_dlstart';
}

// paginating values
$_entry['count_total'] = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'cmsgo_file WHERE '.$_entry['query']);
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['downloads_page'] > $_entry['pages_total']) {
    $_SESSION['downloads_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}

// now retrieve all downloads
$sql  = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE ".$_entry['query']." ORDER BY ".$_entry['sort']." DESC";
$sql .= ' LIMIT '.(($_SESSION['downloads_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$result = _dbQuery($sql);
?>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_title'] ?></h2></div>
    <div class="card-body">
      <form action="<?php echo statistic_url('controller=downloads') ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
       <table class="table table-sm table-striped mb-0" summary="">
        <tr>
          <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
      <?php
      if($_entry['pages_total'] > 1) {
        echo '<td>';
        if($_SESSION['downloads_page'] > 1) {
          echo '<a href="'.statistic_url('controller=downloads').'&amp;page='.($_SESSION['downloads_page']-1).'">';
          echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
        } else {
          echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
        }
        echo '</td>';
        echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['downloads_page'];
        echo '" class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
        echo '<td>/'.$_entry['pages_total'].'&nbsp;</td>';
        echo '<td>';
        if($_SESSION['downloads_page'] < $_entry['pages_total']) {
          echo '<a href="'.statistic_url('controller=downloads').'&amp;page='.($_SESSION['downloads_page']+1).'">';
          echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" /></a>';
        } else {
          echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" class="inactive" />';
        }
        echo '</td><td>&nbsp;|&nbsp;</td>';
      } else {
        echo '<td"><input type="hidden" name="page" id="page" value="1" /></td>';
      }
      ?>
              <td><input type="text" name="filter" id="filter" size="10" value="<?php
              if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
                echo html_specialchars(implode(' ', $_POST['filter']));
              }
              ?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results" /></td>
              <td><select name="list_search" class="custom-select textinput" id="list_search" style="margin:0 2px 2px 0;text-align:left;">
                    <option value="">-- Sortierung --</option>';
                    <option value="f_name" <?php echo ($_SESSION['list_search'] == 'f_name' ? ' selected' : '') ?>><?php echo $BLM['filename'] ?></option>
                    <option value="f_dlstart" <?php echo ($_SESSION['list_search'] == 'f_dlstart' ? ' selected' : '') ?>><?php echo $BLM['downloads_start'] ?></option>
                    <option value="f_dlfinal" <?php echo ($_SESSION['list_search'] == 'f_dlfinal' ? ' selected' : '') ?>><?php echo $BLM['downloads_end'] ?></option>
                    <option value="f_created" <?php echo ($_SESSION['list_search'] == 'f_created' ? ' selected' : '') ?>><?php echo $BLM['erstellt'] ?></option>
                  </select></td>
              <td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="margin-right:3px;" /></td>
            </tr>
          </table></td>

        <td align="right">
          <a href="<?php echo statistic_url('controller=downloads') ?>&amp;c=10">10</a>
          <a href="<?php echo statistic_url('controller=downloads') ?>&amp;c=25">25</a>
          <a href="<?php echo statistic_url('controller=downloads') ?>&amp;c=50">50</a>
          <a href="<?php echo statistic_url('controller=downloads') ?>&amp;c=100">100</a>
          <a href="<?php echo statistic_url('controller=downloads') ?>&amp;c=250">250</a>
          <a href="<?php echo statistic_url('controller=downloads') ?>&amp;c=all"><?php echo $BL['be_ftptakeover_all'].' '.$_entry['count_total'] ?></a>
        </td>

        </tr>
      </table>
      </form>

      <table class="table table-sm table-striped mb-0">
        <tr>
          <th><?php echo $BLM['filename'] ?></th>
          <th><?php echo $BLM['downloads_start'] ?></th>
          <th><?php echo $BLM['downloads_end'] ?></th>
          <th><?php echo $BLM['erstellt'] ?></th>
        </tr>
        <?php

      $x = 0;
      if(isset($result[0]['f_name'])) {
        foreach($result as $data) {
          // now add article URL
          echo '  <tr title="'.html_specialchars($data["f_name"]).'">';
            echo '    <td><a href="fileinfo.php?public&fid='.$data["f_id"].'" target="_blank">' . (empty($data["f_name"]) ? '-' : html_specialchars($data["f_name"]) ) . "</a>&nbsp;</td>" . LF;
          echo '    <td>'.$data["f_dlstart"]."&nbsp;</td>" . LF;
          echo '    <td>'.$data["f_dlfinal"]."&nbsp;</td>" . LF;
          echo '    <td nowrap>&nbsp;'.@date($BL['be_fprivedit_dateformat'], $data["f_created"])."</td>" . LF;
          echo '  </tr>' . LF;

          $x++;
        }
      }
      ?>
      </table>
  </div>
</div>

  </div>
</div>
