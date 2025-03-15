<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if(isset($_POST['rid']) && !isset($_POST['donotsubmit'])) {

  $data_result = update_404redirect();

} elseif(isset($_GET['rid']) && intval($_GET['rid']) && isset($_GET['active'])) {

  _dbUpdate('cmsgo_redirect', array('active'=>empty($_GET['active']) ? 1 : 0), 'rid='.intval($_GET['rid']));

} else {

  $data_result = array(
    'error' => NULL,
    'data'  => array()
  );
}

// List Redirects
if(!isset($_GET['rid']) || isset($_GET['active'])) {

  $_entry = array('query' => '');

  // Pagination
  if(isset($_GET['c'])) {
    $_SESSION['redirect_list_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
  }
  if(isset($_GET['page'])) {
    $_SESSION['redirect_detail_page'] = intval($_GET['page']);
  }

  // set default values for paginating
  if(empty($_SESSION['redirect_list_count'])) {
    $_SESSION['redirect_list_count'] = 25;
  }

  // paginate and search form processing
  if(isset($_POST['do_pagination'])) {

    $_SESSION['redirect_list_active'] = empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['redirect_list_inactive'] = empty($_POST['showinactive']) ? 0 : 1;

    $_SESSION['redirect_filter']    = clean_slweg($_POST['filter']);
    if(empty($_SESSION['redirect_filter'])) {
      unset($_SESSION['redirect_filter']);
    } else {
      $_SESSION['redirect_filter']  = convertStringToArray($_SESSION['redirect_filter'], ' ');
      $_POST['filter']        = $_SESSION['redirect_filter'];
    }

    $_SESSION['redirect_detail_page'] = intval($_POST['page']);
  }

  if(empty($_SESSION['redirect_detail_page'])) {
    $_SESSION['redirect_detail_page'] = 1;
  }

  $_entry['list_active']    = isset($_SESSION['redirect_list_active']) ? $_SESSION['redirect_list_active'] : 1;
  $_entry['list_inactive']  = isset($_SESSION['redirect_list_inactive']) ? $_SESSION['redirect_list_inactive'] : 1;

  // set correct status query
  if($_entry['list_active'] != $_entry['list_inactive']) {

    if(!$_entry['list_active']) {
      $_entry['query'] .= 'active=0';
    }
    if(!$_entry['list_inactive']) {
      $_entry['query'] .= 'active=1';
    }

  } else {
    $_entry['query'] .= 'active!=9';
  }

  if(isset($_SESSION['redirect_filter']) && is_array($_SESSION['redirect_filter']) && count($_SESSION['redirect_filter'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['redirect_filter'] as $_entry['filter']) {
      // search in alias/target fields
      $_entry['filter_array'][] = "CONCAT(alias, target) LIKE '%"._dbEscape($_entry['filter'], false)."%'";
    }
    if(count($_entry['filter_array'])) {
      $_SESSION['redirect_filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
      $_entry['query'] .= $_SESSION['redirect_filter'];
    }

  } elseif(isset($_SESSION['redirect_filter']) && is_string($_SESSION['redirect_filter'])) {

    $_entry['query'] .= $_SESSION['redirect_filter'];

  }

  // paginating values
  $_entry['count_total'] = _dbCount('SELECT COUNT(rid) FROM '.DB_PREPEND.'cmsgo_redirect WHERE '.$_entry['query']);
  $_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['redirect_list_count']);
  if($_SESSION['redirect_detail_page'] > $_entry['pages_total']) {
    $_SESSION['redirect_detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
  }

  $_entry['limit'] = $_entry['pages_total'] > 1 ? (($_SESSION['redirect_detail_page']-1) * $_SESSION['redirect_list_count']).','.$_SESSION['redirect_list_count'] : '';

  // now retrieve all articles
  $result = _dbGet('cmsgo_redirect', '*, UNIX_TIMESTAMP(changed) AS timestamp', $_entry['query'], '', 'changed DESC, views DESC', $_entry['limit']);

?>
<div class="row align-items-center">
  <div class="col col-sm-auto text-center text-sm-left">
    <h1><?php echo $BL['be_links'] . ' &amp; ' . $BL['be_redirects']; ?></h1>
  </div>
  <div class="col-12 col-sm text-center text-sm-right mb-3">
  	<div class="form-group">
      <a class="btn btn-sm btn-blue mr-3" href="cmsgo.php?do=admin&amp;p=14&amp;rid=0" title="<?php echo $BL['be_new_linkredirect'] ?>"><?php echo $BL['be_new_linkredirect'] ?></a>
    </div>
  </div>
</div>

<div class="card">
<div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_cnt_title_overview'] ?></h2></div>
  <div class="card-body">
    <form action="cmsgo.php?do=admin&amp;p=14" method="post">
    <input type="hidden" name="do_pagination" value="1" />
    <?php if($_entry['pages_total'] <= 1): ?><input type="hidden" name="page" id="page" value="1" /><?php endif; ?>
    <div class="form-row align-items-center mb-4">

      <div class="col-12 col-sm">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text bg-success border-0">
              <input name="showactive" id="showactive" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> />
            </div>
            <div class="input-group-text bg-danger border-0">
          	   <input name="showinactive" id="showinactive" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> />
            </div>
          </div>
          <div class="input-group-append">
            <span class="input-group-text border-0" id="basic-addon2"><i class="fas fa-eye"></i></span>
          </div>
        </div>
      </div>

      <?php
        if($_entry['pages_total'] > 1) {
          echo '<div class="col-sm-auto text-right">';
          echo '<table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td>';
          if($_SESSION['redirect_detail_page'] > 1) {
              echo '<a class="btn btn-sm btn-blue" href="cmsgo.php?do=admin&amp;p=14&amp;page='.($_SESSION['redirect_detail_page']-1).'">';
              echo '<i class="fa fa-angle-left"></i></a>';
           } else {
              echo '<a class="btn btn-sm btn-blue disabled" href="cmsgo.php?do=admin&amp;p=14&amp;page='.($_SESSION['redirect_detail_page']-1).'">';
              echo '<i class="fa fa-angle-left"></i></a>';
          }
          echo '</td>';
          echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['redirect_detail_page'];
          echo '"  class="form-control form-control-sm" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
          echo '<td>/'.$_entry['pages_total'].'&nbsp;</td>';
          echo '<td>';
          if($_SESSION['redirect_detail_page'] < $_entry['pages_total']) {
              echo '<a class="btn btn-sm btn-blue" href="cmsgo.php?do=admin&amp;p=14&amp;page='.($_SESSION['redirect_detail_page']+1).'">';
              echo '<i class="fa fa-angle-right"></i></a>';
          } else {
            echo '<a class="btn btn-sm btn-blue disabled" href="cmsgo.php?do=admin&amp;p=14&amp;page='.($_SESSION['redirect_detail_page']+1).'">';
            echo '<i class="fa fa-angle-right"></i></a>';
          }
          echo '</td></tr></table></div>';
        } else {
          echo '<input type="hidden" name="page" id="page" value="1" />';
        }
        ?>

      <div class="col-12 col-sm-auto">
        <div class="input-group my-3 my-sm-0">
          <input name="filter" id="filter" size="15"  class="form-control form-control-sm" value="<?php if(isset($_POST['filter']) && is_array($_POST['filter']) ) echo html(implode(' ', $_POST['filter'])); ?>" type="search">
            <span class="input-group-append">
                <input class="btn btn-sm btn-secondary" name="gofilter" value="Filter" type="button">
            </span>
        </div>
      </div>

      <div class="col-12 col-sm-auto text-right">
        <select class="form-control form-control-sm custom-select">
          <option selected><?php echo $BL['be_article_rendering'] ?></option>
            <option onClick="window.location = 'cmsgo.php?do=admin&amp;p=14&amp;c=10'">10</option>
            <option onClick="window.location = 'cmsgo.php?do=admin&amp;p=14&amp;c=25'">25</option>
            <option onClick="window.location = 'cmsgo.php?do=admin&amp;p=14&amp;c=50'">50</option>
            <option onClick="window.location = 'cmsgo.php?do=admin&amp;p=14&amp;c=100'">100</option>
            <option onClick="window.location = 'cmsgo.php?do=admin&amp;p=14&amp;c=250'">250</option>
            <option onClick="window.location = 'cmsgo.php?do=admin&amp;p=14&amp;c=all'"><?php echo $BL['be_ftptakeover_all'] ?></option>
        </select>
      </div>
    </div>
    </form>

	<div class="table-responsive">
  <table class="table table-sm listing" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr class="header">
      <th class="column news"><?php echo $BL['be_cnt_source'] ?></th>
      <th class="column"><?php echo $BL['be_cnt_target'] ?></th>
      <th class="column"><?php echo $BL['be_views'] ?></th>
      <th class="column"><?php echo $BL['be_newsletter_changed'] ?></th>
      <th class="column collast"> </th>
    </tr>

<?php

  $x = 0;

  $target_types = array(
    'alias' => $BL['be_alias'],
    'id' => $BL['be_structure_id'],
    'aid' => $BL['be_func_struct_articleID'],
    'link' => $BL['be_profile_label_website'].'/'.$BL['be_link']
  );

  foreach($result as $data) {

    $data['source'] = array();
    if($data['alias']) {
      $data['source'][] = $BL['be_alias'].':&nbsp;'.html($data['alias']);
    }
    if($data['aid']) {
      $data['source'][] = $BL['be_func_struct_articleID'].':&nbsp;'.html($data['aid']);
    }
    if($data['id']) {
      $data['source'][] = $BL['be_structure_id'].':&nbsp;'.html($data['id']);
    }

    $data['source'] = implode(', ', $data['source']);
    if(!$data['source']) {
      $data['source'] = '&#8212;';
    }

    //if($data["target"]) {
      //$data["target"] = $data["type"] ? ($target_types[$data["type"]] . ': '.html($data["target"])) : $BL['be_admin_struct_index'];
    /*} else {
      $data['enable_switch_prefix']  = '<span style="padding:0 1px">';
      $data['enable_switch_suffix']  = '</span>';
      $data["target"] = $BL['be_admin_struct_index'];
    }*/

    // now add article URL
    echo '  <tr class="'.($x%2?' alt': '').'" title="' . $data['source'] . ' &gt; ' . $data["target"].'">';
    echo '    <td>' . $data["source"] . "</td>" . LF;
    echo '    <td>' . $data["target"] . "</td>" . LF;
    echo '    <td>' . $data["views"] . "</td>" . LF;
    echo '    <td>'.date($BL['default_date'], $data["timestamp"])."</td>" . LF;
    echo '    <td class="text-right text-nowrap">';

    echo '<a class="btn btn-sm btn-blue mr-1" role="button" aria-disabled="true" title="'.$BL['be_tt_edit'].'" data-toggle="tooltip" href="cmsgo.php?do=admin&amp;p=14&amp;rid='.$data["rid"].'"><i class="fa fa-pencil-alt"></i></a>';

    echo '<button id="abtnredirect'.$data['aid'].'" class="btn fa btn-sm visible '.($data['active']==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$data['aid'].'" data-type="redirect" data-table="redirect" data-field="active" data-fieldid="aid" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_fprivfunc_cactive'].'"></button>';
    $x++;
  }

?>
  </table>
</div>

</div>
</div>

<?php

// Edit Redirects
} else {

  $rid = empty($_GET['rid']) ? 0 : intval($_GET['rid']);

  // now retrieve selected item
  if($rid) {
    $data = _dbGet('cmsgo_redirect', '*, UNIX_TIMESTAMP(changed) AS timestamp', 'rid='.$rid, '', 'changed DESC, views DESC');
  }

  if(isset($data[0])) {

    $data = $data[0];

  } else {

    $data = array(
      'rid'   => 0,
      'alias'   => '',
      'id'    => '',
      'aid'   => '',
      'type'    => '',
      'active'  => 0,
      'shortcut'  => 0,
      'views'   => 0,
      'timestamp' => now(),
      'target'  => '',
      'code'    => ''
    );
  }

  if(count($data_result['data'])) {
    $data = array_merge($data, $data_result['data']);
  }
?>

<form action="cmsgo.php?do=admin&amp;p=14&amp;rid=<?php echo $data['rid'] ?>" method="post">
<h1 class="text-center text-sm-left"><?php echo $BL['be_links'] . ' &amp; ' . $BL['be_redirects']; ?></h1>
<div class="card">
<div class="card-header"><h2><?php echo ($data['rid'] ? $BL['be_cnt_guestbook_edit'] : $BL['be_article_cnt_button2']) . ': ' . $BL['be_link'] . ' &amp; ' . $BL['be_redirect'] ?></h2></div>
  <div class="card-body">

  <div class="form-group form-row align-items-center">
    <label for="be_cnt_type" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_type'] ?></label>
    <div class="col-sm-5">
    <select name="shortcut" type="text" class="custom-select form-control form-control-sm" id="shortcut">
      <option value="0"<?php if($data['shortcut'] != 1): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_redirect'].'/'.$BL['be_article_cnt_redirect'] ?></option>
      <option value="1"<?php echo is_selected(1, $data['shortcut']) ?>><?php echo $BL['be_shortcut'] ?></option>
    </select>
    </div>
    <div class="col-sm-5 text-sm-right mt-3 mt-sm-0">
      <?php echo $BL['be_views'] ?>: <strong><?php echo $data['views'] ?></strong>
    </div>
    </div>

    <div class="form-group form-row align-items-center">
    <label for="be_alias_be_shortcut" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_alias'].'/'.$BL['be_shortcut'] ?></label>
    <div class="col-sm-5">
      <input class="form-control form-control-sm" name="alias" id="alias" value="<?php echo html($data['alias']) ?>" type="text">
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label for="be_func_struct_articleID" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_func_struct_articleID'] ?></label>
    <div class="col-sm-5">
      <input class="form-control form-control-sm col" name="aid" id="aid" value="<?php echo empty($data['aid']) ? '' : $data['aid'] ?>" type="text">
    </div>
    <div class="col-sm-5">
      <div class="form-row align-items-center">
        <label for="be_structure_id" class="col-sm-4 col-form-label text-right"><?php echo $BL['be_structure_id'] ?></label>
        <div class="col">
          <input class="form-control form-control-sm col" name="id" id="id" value="<?php echo empty($data['id']) ? '' : $data['id'] ?>" type="text">
        </div>
      </div>
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label for="be_target_type" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_target_type'] ?></label>
    <div class="col-sm-5">
    <select name="type" type="text" class="form-control form-control-sm" id="type">
      <option value=""<?php if(empty($data['type'])): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_admin_struct_index'] ?></option>
      <option value="alias"<?php echo is_selected('alias', $data['type']) ?>><?php echo $BL['be_alias'] ?></option>
      <option value="id"<?php echo is_selected('id', $data['type']) ?>><?php echo $BL['be_structure_id'] ?></option>
      <option value="aid"<?php echo is_selected('aid', $data['type']) ?>><?php echo $BL['be_func_struct_articleID'] ?></option>
      <option value="link"<?php echo is_selected('link', $data['type']) ?>><?php echo $BL['be_profile_label_website'].'/'.$BL['be_link'] ?></option>
    </select>
    </div>
    <div class="col-sm-5">
      <div class="form-row align-items-center">
        <label for="be_http_status" class="col-sm-4 col-form-label text-right"><?php echo $BL['be_http_status'] ?></label>
        <div class="col">
          <select name="code" type="text" class="form-control form-control-sm" id="code">
            <option value=""<?php if(empty($data['code'])): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_admin_tmpl_default'] ?> (302)</option>
            <option value="301"<?php echo is_selected('301', $data['code']) ?>><?php echo $BL['be_http_status301'] ?> (301)</option>
            <option value="307"<?php echo is_selected('307', $data['code']) ?>><?php echo $BL['be_http_status307'] ?> (301)</option>
            <option value="404"<?php echo is_selected('404', $data['code']) ?>><?php echo $BL['be_http_status404'] ?> (404)</option>
            <option value="401"<?php echo is_selected('401', $data['code']) ?>><?php echo $BL['be_http_status401'] ?> (401)</option>
            <option value="503"<?php echo is_selected('503', $data['code']) ?>><?php echo $BL['be_http_status503'] ?> (503)</option>
          </select>
        </div>
      </div>
    </div>
  </div>

	<div class="form-group form-row align-items-center">
		<label for="be_cnt_target" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_target'] ?></label>
		<div class="col-sm-5">
			<input class="form-control form-control-sm" name="target" id="target" value="<?php echo html($data['target']) ?>" type="text">
		</div>
	</div>

	<div class="form-group form-row align-items-center">
		<div class="col-sm-2"></div>
		<div class="col-sm">
			<div class="form-check">
				<label for="be_ftptakeover_active" class="form-check-label">
				<input class="form-check-input" type="checkbox" name="active" id="template_onepage" value="1"<?php is_checked(1, $data['active']) ?> /> <?php echo $BL['be_ftptakeover_active'] ?></label>
			</div>
		</div>
		<div class="col-sm text-sm-right mt-3 mt-sm-0">
			<?php echo $BL['be_newsletter_changed'] ?>: <?php echo date($BL['be_longdatetime'], $data["timestamp"]) ?>
		</div>
	</div>

</div>
</div>

    <div class="form-group form-row mt-3 mb-0">
      <div class="col-12 col-sm text-center text-sm-right">
        <input type="submit" class="btn btn-sm btn-blue mb-1" value="<?php echo $rid ? $BL['be_article_cnt_button3'] : $BL['be_article_cnt_button2'] ?>" />
        <input type="reset" class="btn btn-sm btn-blue mb-1" value="<?php echo $BL['be_cnt_field']['reset'] ?>" />
        <input name="donotsubmit" type="button" class="btn btn-sm btn-blue mb-1" value="<?php echo  $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='cmsgo.php?do=admin&p=14'" />
        <input type="hidden" name="rid" value="<?php echo $data['rid'] ?>" />
        <?php if($rid): ?><input type="submit" class="btn btn-sm btn-danger mb-1" name="delete_<?php echo md5($rid) ?>" value="<?php echo $BL['be_cnt_delete'] ?>" onclick="return confirm('<?php echo $BL['be_delete_dataset'].' [ID:'.$rid.']' ?>');" /><?php endif; ?>
      </div>
    </div>
</form>



<?php
}
?>
