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

$_userInfo = array();

// delete all duplicate addresses
if(isset($_GET['duplicate']) && $_GET['duplicate'] == 'remove') {
  $data = _dbQuery('SELECT COUNT(*) AS address_count, address_email FROM '.DB_PREPEND.'address GROUP BY address_email');
  if($data) {

    foreach($data as $value) {

      // check for multiple entries
      if($value['address_count'] > 1) {

        $sql  = 'SELECT address_id FROM '.DB_PREPEND.'address ';
        $sql .= "WHERE address_email='".aporeplace($value['address_email'])."' ";
        $sql .= 'ORDER BY address_verified DESC, address_name DESC LIMIT 1';
        $dataID = _dbQuery($sql);

        if(!empty($dataID[0]['address_id'])) {
          $sql  = 'DELETE FROM '.DB_PREPEND.'address ';
          $sql .= "WHERE address_email='".aporeplace($value['address_email'])."' ";
          $sql .= "AND address_id != ".intval($dataID[0]['address_id']);
          @_dbQuery($sql, 'DELETE');
        }

      }
    }
  }
  headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=messages&p=4');
}

// delete susbcriber
if(isset($_GET["del"]) && isset($_GET["s"]) && $_GET["del"] == $_GET["s"]) {
  _dbQuery("DELETE FROM ".DB_PREPEND."address WHERE address_id=".intval($_GET["del"])." LIMIT 1", 'DELETE');
}
// change verification
if(isset($_GET["verify"]) && isset($_GET["s"])) {
  $sql  = "UPDATE ".DB_PREPEND."address SET address_verified=";
  $sql .= intval($_GET["verify"]) ? 1 : 0;
  $sql .= " WHERE address_id=".intval($_GET["s"])." LIMIT 1";
  _dbQuery($sql, 'UPDATE');
}

echo '<h1 class="text-center text-sm-start">'.$BL['be_subnav_msg_subscribers'].'</h1>';
?>



<?php
// recipient edit form
if(isset($_GET["s"]) && isset($_GET["edit"])) {

  $_userInfo['subscriber_id'] = intval($_GET["s"]);

  if($_userInfo['subscriber_id'] === 0) {

    $_userInfo['subscriber_data']['address_email']      = '';
    $_userInfo['subscriber_data']['address_name']     = '';
    $_userInfo['subscriber_data']['address_id']       = 0;
    $_userInfo['subscriber_data']['address_subscription'] = '';
    $_userInfo['subscriber_data']['address_tstamp']     = date('Y-m-d H:i:s');
    $_userInfo['subscriber_data']['address_verified']   = 0;

  } else {
    $_userInfo['subscriber_data'] = _dbQuery("SELECT * FROM ".DB_PREPEND."address WHERE address_id=".$_userInfo['subscriber_id']." LIMIT 1");
    if($_userInfo['subscriber_data']) {
      $_userInfo['subscriber_data']  = $_userInfo['subscriber_data'][0];
    }
  }

  if(isset($_POST['subscribe_email'])) {
    include_once PHPWCMS_ROOT.'/include/inc_lib/subscriber.form.inc.php';
  }

  if($_userInfo['subscriber_data']) {
    include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriber.form.tmpl.php';
  }
}

// import form
if(isset($_GET['import']) && $_GET['import'] === '1') {

  $_userInfo['delimeter']     = ';';
  $_userInfo['subscribe_active']  = 1;
  $_userInfo['subscribe_all']   = 1;
  $_userInfo['subscribe_select']  = array();

  if(isset($_POST['delimeter'])) {
    include_once PHPWCMS_ROOT.'/include/inc_lib/subscriberimport.form.inc.php';

    if(isset($_userInfo['csvError'])) {
      include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriberimport.form.tmpl.php';
    } else {
      include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriberimport.result.tmpl.php';
    }
  } else {
    include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriberimport.form.tmpl.php';
  }
}

// create paginating for users
if(isset($_GET['c'])) {
  $_SESSION['list_user_count'] = (trim($_GET['c']) == 'all') ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
  $_SESSION['subscriber_page'] = intval($_GET['page']);
}


// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
  $_SESSION['list_user_count'] = 25;
}

// get filter and paginating form values
if(isset($_POST['do_pagination'])) {

  $_SESSION['list_active']  = empty($_POST['showactive']) ? 0 : 1;
  $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;
  $_SESSION['list_channel'] = empty($_POST['showchannel']) ? 0 : 1;

  $_SESSION['subscriber_page']  = intval($_POST['page']);
  $_SESSION['filter_subscriber']  = clean_slweg($_POST['filter']);
  if(empty($_SESSION['filter_subscriber'])) {
    unset($_SESSION['filter_subscriber']);
  } else {
    $_SESSION['filter_subscriber']  = convertStringToArray($_SESSION['filter_subscriber'], ' ');
  }
}

if(empty($_SESSION['subscriber_page'])) {
  $_SESSION['subscriber_page'] = 1;
}

// default settings for listing selected users
$_userInfo['list_active']   = isset($_SESSION['list_active']) ? $_SESSION['list_active']    : 1;
$_userInfo['list_inactive']   = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive']  : 1;
$_userInfo['list_channel']    = isset($_SESSION['list_channel'])  ? $_SESSION['list_channel']   : 0;

if($_userInfo['list_channel'] && isset($_POST['showchannel'])) {
  $_userInfo['channel'] = empty($_POST['subscribe_select']) ? false : $_POST['subscribe_select'];
  $_SESSION['channel'] = $_userInfo['channel'];
} elseif($_userInfo['list_channel'] && isset($_SESSION['channel'])) {
  $_userInfo['channel'] = $_SESSION['channel'];
} else {
  $_userInfo['channel'] = false;
}

$_userInfo['list']      = array();
// if admin user should be listed
$_userInfo['where_query'] = '';
if($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_active']) {
  $_userInfo['where_query'] = ' WHERE address_verified=1';
} elseif($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_inactive']) {
  $_userInfo['where_query'] = ' WHERE address_verified=0';
}

if(isset($_SESSION['filter_subscriber']) && count($_SESSION['filter_subscriber'])) {

  $_userInfo['filter_array'] = array();

  foreach($_SESSION['filter_subscriber'] as $_userInfo['filter']) {
    //usr_name, usr_login, usr_email
    $_userInfo['filter_array'][] = 'CONCAT(address_email, address_name) LIKE ' . _dbEscapeLike($_userInfo['filter']);
  }
  if(count($_userInfo['filter_array'])) {

    $_userInfo['where_query'] .= $_userInfo['where_query'] ? ' AND ' : ' WHERE ';
    $_userInfo['where_query'] .= '('.implode(' OR ', $_userInfo['filter_array']).')';

  }

}

// paginating values
$_userInfo['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."address".$_userInfo['where_query'], 'COUNT');
$_userInfo['pages_total'] = ceil($_userInfo['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['subscriber_page'] > $_userInfo['pages_total']) {
  $_SESSION['subscriber_page'] = empty($_userInfo['pages_total']) ? 1 : $_userInfo['pages_total'];
}

?>
<form action="phpwcms.php?do=messages&amp;p=4" method="post" name="paginate" id="paginate" class="form"><input type="hidden" name="do_pagination" value="1" />
<div class="card">
    <div class="card-header"><h2><i class="fa-solid fa-list" aria-hidden="true"></i> <?php echo $BL['be_cnt_title_overview'] ?> <?php echo $BL['be_mailinglist_overview_subscribers'] ?></h2></div>
    <div class="card-body">

    <div class="mb-3 text-center text-sm-start">
        <a class="btn btn-sm btn-blue my-1 my-md-0" role="button" href="phpwcms.php?do=messages&amp;p=4&amp;s=0&amp;edit=1"><i class="fa-solid fa-user-plus"></i> <?php echo $BL['be_cnt_new_recipient'] ?></a>
        <a class="btn btn-sm btn-blue my-1 my-md-0" role="button" href="phpwcms.php?do=messages&amp;p=4&amp;duplicate=remove" data-confirm-type="danger" data-confirm-action="<?php echo $BL['be_cnt_delete_duplicates'] ?>" data-confirm="<?php echo $BL['be_cnt_delete_duplicates'] ?>"><i class="fa-regular fa-trash-alt"></i> <?php echo $BL['be_cnt_delete_duplicates'] ?></a>
        <a class="btn btn-sm btn-blue my-1 my-md-0" role="button" href="phpwcms.php?do=messages&amp;p=4&amp;import=1" target="_blank"><i class="fa-solid fa-download" aria-hidden="true"></i> <?php echo $BL['be_newsletter_newimport'] ?></a>
        <a class="btn btn-sm btn-blue my-1 my-md-0" role="button" href="include/inc_act/act_export.php?<?php echo CSRF_GET_TOKEN; ?>&amp;action=exportsubscriber" target="_blank" ><i class="fa-solid fa-upload" aria-hidden="true"></i> <?php echo $BL['be_cnt_export_selection'] ?></a>
    </div>

    <hr />

    <div class="row g-2 align-items-center mb-3">
      <input type="hidden" name="showactive" id="showactive_input" value="<?php echo $_entry['list_active'] ?>" />
      <input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo $_entry['list_inactive'] ?>" />
      <div class="col-12 col-sm-auto">
        <div class="btn-group btn-group-sm" role="group" aria-label="subscribers-filter">
          <button type="button" class="btn btn-sm <?php echo $_entry['list_active'] ? 'btn-success' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showactive_input').value = (document.getElementById('showactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Active">
            <i class="fa-solid fa-eye"></i>
          </button>
          <button type="button" class="btn btn-sm <?php echo $_entry['list_inactive'] ? 'btn-warning' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showinactive_input').value = (document.getElementById('showinactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Inactive">
            <i class="fa-solid fa-eye-slash"></i>
          </button>
        </div>
      </div>

      <div class="col-12 col-sm-auto">
        <div class="input-group input-group-sm my-2 my-sm-0">
          <input name="filter" id="filter" size="15" data-bs-toggle="tooltip" title="Filtern nach Benutzername, Name oder E-Mail" class="form-control" value="<?php
  if(isset($_SESSION['filter_subscriber']) && count($_SESSION['filter_subscriber']) ) {
    echo html(implode(' ', $_SESSION['filter_subscriber']));
  }
  ?>" type="search">
          
            <button class="btn btn-secondary" name="gofilter" type="button"><i class="fa-solid fa-filter me-1"></i> <?php echo $BL['be_filter'] ?></button>
          
        </div>
      </div>

      <?php if($_userInfo['pages_total'] > 1): ?>
        <div class="col-12 col-sm-auto">
          <div class="input-group input-group-sm">
            
              <?php if($_SESSION['subscriber_page'] > 1): ?>
                <a class="btn btn-blue" href="phpwcms.php?do=messages&amp;p=4&amp;page=<?php echo ($_SESSION['subscriber_page']-1) ?>"><i class="fa-solid fa-angle-left"></i></a>
              <?php else: ?>
                <button class="btn btn-blue" disabled type="button"><i class="fa-solid fa-angle-left"></i></button>
              <?php endif; ?>
            
            <input type="number" name="page" id="page" value="<?php echo $_SESSION['subscriber_page'] ?>" class="form-control text-center fw-bold" style="width: 60px;" />
            
              <span class="input-group-text">/ <?php echo $_userInfo['pages_total'] ?></span>
              <?php if($_SESSION['subscriber_page'] < $_userInfo['pages_total']): ?>
                <a class="btn btn-blue" href="phpwcms.php?do=messages&amp;p=4&amp;page=<?php echo ($_SESSION['subscriber_page']+1) ?>"><i class="fa-solid fa-angle-right"></i></a>
              <?php else: ?>
                <button class="btn btn-blue" disabled type="button"><i class="fa-solid fa-angle-right"></i></button>
              <?php endif; ?>
            
          </div>
        </div>
      <?php else: ?>
        <input type="hidden" name="page" id="page" value="1" />
      <?php endif; ?>

    <div class="col-12 col-sm-auto text-end">
        <select class="form-select form-select-sm">
            <option <?php echo ($_SESSION['list_user_count'] == '') ? 'selected ' : ''; ?>><?php echo $BL['be_article_rendering'] ?></option>
            <option <?php echo ($_SESSION['list_user_count'] == '5') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=4&amp;c=5'">5</option>
            <option <?php echo ($_SESSION['list_user_count'] == '10') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=4&amp;c=10'">10</option>
            <option <?php echo ($_SESSION['list_user_count'] == '25') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=4&amp;c=25'">25</option>
            <option <?php echo ($_SESSION['list_user_count'] == '50') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=4&amp;c=50'">50</option>
            <option <?php echo ($_SESSION['list_user_count'] == '100') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=4&amp;c=100'">100</option>
            <option <?php echo ($_SESSION['list_user_count'] == '99999') ? 'selected ' : ''; ?>onClick="window.location = 'phpwcms.php?do=messages&amp;p=4&amp;c=all'"><?php echo $BL['be_ftptakeover_all'] ?></option>
        </select>
    </div>
</div>
<?php

// set filter select by channel
if($_userInfo['list_channel']) {
  $_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."subscription ORDER BY subscription_name");

  if($_userInfo['subscriptions']) {

    $_userInfo['select_subscr'] = '';

    foreach($_userInfo['subscriptions'] as $value) {

      $_userInfo['select_subscr'] .= '    <tr>
        <td><input type="checkbox" name="subscribe_select['.$value['subscription_id'].
        ']" id="subscribe_select'.$value['subscription_id'].'" value="'.$value['subscription_id'].'"';

      if(!empty($_userInfo['channel'][$value['subscription_id']]) && $_userInfo['channel'][$value['subscription_id']]==$value['subscription_id']) {
        $_userInfo['select_subscr'] .= ' checked="checked"';
      }

      $_userInfo['select_subscr'] .= ' /></td>
        <td><label for="subscribe_select'.$value['subscription_id'].'">'.
        html($value['subscription_name']).
        '</label></td>
      </tr>
      ';
    }

    if($_userInfo['select_subscr']) {
      echo '<div id="channelSelect">'.LF;
      echo '<table class="table table-sm mb-0">'.LF;
      echo $_userInfo['select_subscr'];
      echo '</table>'.LF;
      echo '</div>';

    }
  }
}

?>
</form>

<div class="table-responsive">
	<table class="table table-sm table-valign-middle mt-3 mb-0">
		<tr bgcolor="#f3f3f3">
			<th>&nbsp;</th>
			<th><?php echo $BL['be_profile_label_email'] ?></th>
			<th><?php echo $BL['be_fpriv_name'] ?></th>
			<th>&nbsp;</th>
		</tr>

	<?php
	// loop listing available newsletters
	$row_count = 0;

	$sql  = "SELECT * FROM ".DB_PREPEND."address".$_userInfo['where_query']." ";
	$sql .= "LIMIT ".(($_SESSION['subscriber_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
	$data = _dbQuery($sql);

	foreach($data as $row) {

		// mark selected channel
		if($_userInfo['channel'] !== false) {
			$_userInfo['channel_select'] = ' class="inactive"';

			$row['channel'] = unserialize($row['address_subscription'], ['allowed_classes' => false]);
			if(is_array($row['channel']) && count($row['channel'])) {

				foreach($row['channel'] as $channel) {
					if(isset($_userInfo['channel'][$channel])) {
						$_userInfo['channel_select'] = '';
						break;
					}
				}
			}

		} else {
			$_userInfo['channel_select'] = '';
		}

		$row["address_email"] = html($row["address_email"]);
		echo '<tr'.( ($row_count % 2) ? ' bgcolor="#f4f4f4"' : '' ).$_userInfo['channel_select']."><td>".LF;
		echo '<i class="fa-solid fa-user" aria-hidden="true"></i></td>'."\n";
		echo '<td width="1%" class="dir text-nowrap">'.$row["address_email"]."</td>".LF;
		echo '<td class="dir" width="95%">'.html($row["address_name"])."</td>".LF;
		echo '<td align="right" class="button_td text-nowrap">'.LF;
		echo '<div class="btn-group btn-group-sm" role="group" aria-label="subscriber-actions-'.$row["address_id"].'">';
		echo '<a class="btn btn-sm btn-blue" role="button" title="'.$BL['be_tt_edit'].'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=4&amp;s='.$row["address_id"].'&amp;edit=1"><i class="fa-solid fa-pencil-alt"></i></a>';

		echo '<button id="abtnaddress'.$row["address_id"].'" class="btn fa btn-sm visible '.($row["address_verified"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$row["address_id"].'" data-type="address" data-table="address" data-field="address_verified" data-fieldid="address_id" data-bs-toggle="tooltip" title="'.sprintf($BL['be_mailinglist_verified'], $row["address_email"]).' "><i class="fa-solid '.($row["address_verified"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';
		echo '</div>';

		echo '<a class="btn btn-sm btn-danger ms-1" role="button" title="'.$BL['be_mailinglist_delete_subscriber'].': '.html_specialchars($row["address_email"]).'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=4&amp;s='.$row["address_id"].'&amp;del='.$row["address_id"].'" data-confirm-type="danger" data-confirm-action="'.$BL['be_mailinglist_delete_subscriber'].'" data-confirm="'.$BL['be_mailinglist_delete_subscriber'].' '.js_singlequote($row["address_email"]).'"><i class="fa-regular fa-trash-alt"></i></a>'.LF;

		echo "</td>\n</tr>".LF;

		$row_count++;
	}
	?>
	</table>
</div>
</div>
</div>
