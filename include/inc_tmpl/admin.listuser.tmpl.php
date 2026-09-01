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


// create paginating for users
if(isset($_GET['c'])) {
    $_SESSION['list_user_count'] = (trim($_GET['c']) == 'all') ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['list_user_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
    $_SESSION['list_user_count'] = 25;
}

$_userInfo = array();

// Get filter and paginating form values
if(isset($_POST['do_pagination'])) {

    $_SESSION['list_admin']     = empty($_POST['showadmin']) ? 0 : 1;
    $_SESSION['list_befe']      = empty($_POST['showbefe']) ? 0 : 1;
    $_SESSION['list_norm']      = empty($_POST['shownorm']) ? 0 : 1;
    $_SESSION['list_fe']        = empty($_POST['showfe']) ? 0 : 1;

    $_SESSION['list_user_page'] = intval($_POST['page']);
    $_SESSION['filter_results'] = clean_slweg($_POST['filter']);
    if(empty($_SESSION['filter_results'])) {
        unset($_SESSION['filter_results']);
    } else {
        $_SESSION['filter_results'] = convertStringToArray($_SESSION['filter_results'], ' ');
    }
}

if(empty($_SESSION['list_user_page'])) {
    $_SESSION['list_user_page'] = 1;
}

$_userInfo['list_admin']    = isset($_SESSION['list_admin']) ? $_SESSION['list_admin'] : 0;
$_userInfo['list_befe']     = isset($_SESSION['list_befe']) ? $_SESSION['list_befe'] : 0;
$_userInfo['list_norm']     = isset($_SESSION['list_norm']) ? $_SESSION['list_norm'] : 0;
$_userInfo['list_fe']       = isset($_SESSION['list_fe']) ? $_SESSION['list_fe'] : 0;

$_userInfo['list']          = array();
// if admin user should be listed
$_userInfo['where_query']    = ' WHERE usr_aktiv != 9';
if($_userInfo['list_admin']) {
    $_userInfo['where_query']   .= ' AND usr_admin=1';
}
if($_userInfo['list_befe']) {
    $_userInfo['list'][]    = ' usr_fe=2 ';
}
if($_userInfo['list_norm']) {
    $_userInfo['list'][]    = ' usr_fe=1 ';
}
if($_userInfo['list_fe']) {
    $_userInfo['list'][]    = ' usr_fe=0 ';
}
$_userInfo['list']          = trim(implode('OR', $_userInfo['list']));
if($_userInfo['list']) {
    $_userInfo['where_query'] .= ' AND ('.$_userInfo['list'].')';
}
if(isset($_SESSION['filter_results']) && count($_SESSION['filter_results'])) {

    $_userInfo['filter_array'] = array();

    foreach($_SESSION['filter_results'] as $_userInfo['filter']) {
        //usr_name, usr_login, usr_email
        $_userInfo['filter_array'][] = 'CONCAT(usr_name, usr_login, usr_email) LIKE ' . _dbEscapeLike($_userInfo['filter']);
    }
    if(count($_userInfo['filter_array'])) {
        $_userInfo['where_query'] .= ' AND (' . implode(' OR ', $_userInfo['filter_array']) . ')';
    }
}

// paginating values
$_userInfo['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_user ".$_userInfo['where_query'], 'COUNT');
$_userInfo['pages_total'] = ceil($_userInfo['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['list_user_page'] > $_userInfo['pages_total']) {
    $_SESSION['list_user_page'] = empty($_userInfo['pages_total']) ? 1 : $_userInfo['pages_total'];
}
?>

<div class="row align-items-center">
	<div class="col col-sm-auto text-center text-sm-start">
    <h1><?php echo $BL['be_subnav_admin_users_overview']; ?></h1>
  </div>
	<div class="col-12 col-sm text-center text-sm-end mb-3">
    <a class="btn btn-sm btn-blue" href="phpwcms.php?do=admin&amp;s=1" title="<?php echo $BL['be_admin_usr_create'] ?>"><i class="fa-solid fa-plus me-1"></i> <?php echo $BL['be_admin_usr_create'] ?></a>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2><i class="fa-solid fa-list"></i> <?php echo $BL['be_admin_usr_ltitle'] ?></h2></div>
  <div class="card-body">
<form action="phpwcms.php?do=admin" method="post" name="paginate" id="paginate">
<input type="hidden" name="do_pagination" value="1" />

  <div class="row align-items-center mb-3">
      <div class="col-12 col-xl mb-3 mb-xl-0">
          <div class="row g-2">
              <div class="col-sm-auto mb-2 mb-xl-0">
                  <div class="input-group input-group-sm">
                      
                          <span class="input-group-text px-2 text-info" title="<?php echo $BL['be_article_adminuser'] ?? 'Admin User'; ?>"><i class="fa-solid fa-user-shield"></i></span>
                          <div class="input-group-text">
                              <input name="showadmin" id="showadmin" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_admin'], 1) ?> />
                          
                      </div>
                      <label for="showadmin" class="form-control form-control-sm mb-0 cursor-pointer bg-light"><?php echo $BL['be_article_adminuser'] ?? 'Admin User'; ?></label>
                  </div>
              </div>
              <div class="col-sm-auto mb-2 mb-xl-0">
                  <div class="input-group input-group-sm">
                      
                          <span class="input-group-text px-2 text-success" title="<?php echo $BL['be_admin_usr_ifsection2'] ?? 'Frontend & Backend'; ?>"><i class="fa-solid fa-user-check"></i></span>
                          <div class="input-group-text">
                              <input name="showbefe" id="showbefe" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_befe'], 1) ?> />
                          
                      </div>
                      <label for="showbefe" class="form-control form-control-sm mb-0 cursor-pointer bg-light"><?php echo $BL['be_admin_usr_ifsection2'] ?? 'Frontend & Backend'; ?></label>
                  </div>
              </div>
              <div class="col-sm-auto mb-2 mb-xl-0">
                  <div class="input-group input-group-sm">
                      
                          <span class="input-group-text px-2 text-primary" title="<?php echo $BL['be_admin_usr_ifsection1'] ?? 'Backend'; ?>"><i class="fa-solid fa-user-cog"></i></span>
                          <div class="input-group-text">
                              <input name="shownorm" id="shownorm" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_norm'], 1) ?> />
                          
                      </div>
                      <label for="shownorm" class="form-control form-control-sm mb-0 cursor-pointer bg-light"><?php echo $BL['be_admin_usr_ifsection1'] ?? 'Backend'; ?></label>
                  </div>
              </div>
              <div class="col-sm-auto mb-2 mb-xl-0">
                  <div class="input-group input-group-sm">
                      
                          <span class="input-group-text px-2 text-warning" title="<?php echo $BL['be_admin_usr_ifsection0'] ?? 'Frontend'; ?>"><i class="fa-solid fa-user"></i></span>
                          <div class="input-group-text">
                              <input name="showfe" id="showfe" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_fe'], 1) ?> />
                          
                      </div>
                      <label for="showfe" class="form-control form-control-sm mb-0 cursor-pointer bg-light"><?php echo $BL['be_admin_usr_ifsection0'] ?? 'Frontend'; ?></label>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-12 col-sm col-xl-auto mb-2 mb-sm-0">
          <div class="input-group input-group-sm">
              <input type="search" name="filter" id="filter" style="min-width: 200px;" data-bs-toggle="tooltip" title="<?php echo $BL['be_tooltip_filter_user'] ?>" class="form-control form-control-sm" value="<?php
              if(isset($_SESSION['filter_results']) && count($_SESSION['filter_results']) ) {
                  echo html(implode(' ', $_SESSION['filter_results']));
              }
              ?>">
              
                  <button class="btn btn-sm btn-secondary" name="gofilter" type="button" onclick="this.form.submit();"><i class="fa-solid fa-filter me-1"></i> <?php echo $BL['be_filter'] ?></button>
              
          </div>
      </div>

    <?php if($_userInfo['pages_total'] > 1): ?>
        <div class="col-12 col-sm-auto mb-2 mb-sm-0">
            <div class="input-group input-group-sm">
                
                    <?php if($_SESSION['list_user_page'] > 1): ?>
                        <a class="btn btn-blue" href="phpwcms.php?do=admin&amp;page=<?php echo ($_SESSION['list_user_page']-1); ?>"><i class="fa-solid fa-angle-left"></i></a>
                    <?php else: ?>
                        <button class="btn btn-blue" disabled type="button"><i class="fa-solid fa-angle-left"></i></button>
                    <?php endif; ?>
                
                <input type="number" name="page" id="page" maxlength="4" size="4" value="<?php echo $_SESSION['list_user_page']; ?>" class="form-control form-control-sm fw-bold text-center" style="width: 55px;" />
                
                    <span class="input-group-text">/ <?php echo $_userInfo['pages_total']; ?></span>
                    <?php if($_SESSION['list_user_page'] < $_userInfo['pages_total']): ?>
                        <a class="btn btn-blue" href="phpwcms.php?do=admin&amp;page=<?php echo ($_SESSION['list_user_page']+1); ?>"><i class="fa-solid fa-angle-right"></i></a>
                    <?php else: ?>
                        <button class="btn btn-blue" disabled type="button"><i class="fa-solid fa-angle-right"></i></button>
                    <?php endif; ?>
                
            </div>
        </div>
    <?php else: ?>
        <input type="hidden" name="page" id="page" value="1" />
    <?php endif; ?>

    <div class="col-12 col-sm-auto text-end">
    <select class="form-select form-select-sm" onchange="if(this.value) window.location = 'phpwcms.php?do=admin&amp;c=' + this.value;">
      <option value=""><?php echo $BL['be_amount_results'] ?></option>
        <option value="5"<?php echo ($_SESSION['list_user_count'] == '5') ? ' selected' : ''; ?>>5</option>
        <option value="10"<?php echo ($_SESSION['list_user_count'] == '10') ? ' selected' : ''; ?>>10</option>
        <option value="25"<?php echo ($_SESSION['list_user_count'] == '25') ? ' selected' : ''; ?>>25</option>
        <option value="50"<?php echo ($_SESSION['list_user_count'] == '50') ? ' selected' : ''; ?>>50</option>
        <option value="100"<?php echo ($_SESSION['list_user_count'] == '100') ? ' selected' : ''; ?>>100</option>
        <option value="all"<?php echo ($_SESSION['list_user_count'] == '99999') ? ' selected' : ''; ?>><?php echo $BL['be_ftptakeover_all']; ?></option>
    </select>
  	</div>
</div>

</form>

    <table class="table table-sm table-valign-middle border-top mb-0">
    <?php
    $zaehler = 0;
    if(!isset($new_user_id)) {
        $new_user_id = 0;
    }
    // Generate list of all users
    $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_user ".$_userInfo['where_query'].' ';
    $sql .= "ORDER BY usr_aktiv DESC, usr_fe DESC, usr_admin DESC, usr_name ASC ";
    $sql .= "LIMIT ".(($_SESSION['list_user_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
    $result = _dbQuery($sql);
    if(isset($result[0]['usr_id'])) {
        foreach($result as $userlist) {
            $bg_class = ($zaehler % 2) ? 'bg-row-alt-grey' : 'bg-row-white';
            if($userlist["usr_id"] == $new_user_id) {
                $bg_class = "bg-row-highlight-amber";
            }
            $goto = "phpwcms.php?do=admin&amp;s=2&amp;u=".$userlist["usr_id"];

            if ($userlist["usr_aktiv"] == 1) {
                if ($userlist["usr_admin"]) {
                    $u_icon = 'fa-user-shield text-info';
                    $u_title = $BL['be_article_adminuser'] ?? 'Admin User';
                } else {
                    switch ($userlist["usr_fe"]) {
                        case 0:
                            $u_icon = 'fa-user text-warning';
                            $u_title = $BL['be_admin_usr_ifsection0'] ?? 'Frontend';
                            break;
                        case 1:
                            $u_icon = 'fa-user-cog text-primary';
                            $u_title = $BL['be_admin_usr_ifsection1'] ?? 'Backend';
                            break;
                        case 2:
                            $u_icon = 'fa-user-check text-success';
                            $u_title = $BL['be_admin_usr_ifsection2'] ?? 'Frontend & Backend';
                            break;
                    }
                }
            } else {
                $u_icon = 'fa-user-slash text-muted';
                $u_title = $BL['be_admin_usr_inactiv'] ?? 'Inactive';
            }
?>

      <tr class="hover-light <?php echo $bg_class ?>">
      <td width="30" class="text-center"><i class="fa-solid fa-fw fa-lg <?php echo $u_icon; ?>" title="<?php echo html($u_title); ?>" data-bs-toggle="tooltip"></i></td>
          <td <?php if($userlist["usr_aktiv"]==1) {echo "class=\"dir\"";} else {echo "class=\"text-muted\"";} ?>><a href="<?php echo $goto ?>"><?php

            if($userlist["usr_name"]) {
                $userlist["usr_name"] = html($userlist["usr_name"]." (".$userlist["usr_login"].")");
            } else {
                $userlist["usr_name"] = html($userlist["usr_login"]);
            }
            echo $userlist["usr_name"];

          ?></a></td>
          <td class="text-nowrap text-end">
          <?php
          echo '<div class="btn-group btn-group-sm" role="group" aria-label="user-actions-'.$userlist['usr_id'].'">';
          echo '<button id="abtnuser'.$userlist['usr_id'].'" class="btn fa btn-sm visible '.($userlist["usr_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$userlist['usr_id'].'" data-type="user" data-table="user" data-field="usr_aktiv" data-fieldid="usr_id" data-bs-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"><i class="fa-solid '.($userlist["usr_aktiv"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';
          echo '<a class="btn btn-sm btn-blue" role="button" title="'.$BL['be_admin_usr_editusr'].": ".html($userlist["usr_login"]).'" data-bs-toggle="tooltip" href="'.$goto .'"><i class="fa-solid fa-pencil-alt"></i></a>';
          echo '</div>';
          $confirm_usr = $BL['be_admin_usr_ldel'] . "\n[" . $userlist['usr_login'] . "]";
          echo '<a class="btn btn-sm btn-danger ms-1" data-bs-toggle="tooltip" href="include/inc_act/act_user.php?del='. urlencode($userlist["usr_id"].":".$userlist["usr_email"]).'" title="'.$BL['be_admin_usr_ldel'].' '.html($userlist['usr_login']).'" data-confirm-danger="'.html_specialchars($confirm_usr).'"><i class="fa-regular fa-trash-alt"></i></a>';
          ?>

          </td>
        </tr>
        <?php
            $zaehler++;
        }
    } //Ende Schleife Anzeige User
?>
</table>

</div>
</div>
