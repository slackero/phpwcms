<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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


// get filter and paginating form values
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
        $_userInfo['filter_array'][] = "CONCAT(usr_name, usr_login, usr_email) LIKE '%".aporeplace($_userInfo['filter'])."%'";
    }
    if(count($_userInfo['filter_array'])) {

        $_userInfo['where_query'] .= ' AND ('.implode('OR', $_userInfo['filter_array']).')';

    }

}

// paginating values
$_userInfo['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_user ".$_userInfo['where_query'], 'COUNT');
$_userInfo['pages_total'] = ceil($_userInfo['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['list_user_page'] > $_userInfo['pages_total']) {
    $_SESSION['list_user_page'] = empty($_userInfo['pages_total']) ? 1 : $_userInfo['pages_total'];
}
?>

<div class="row align-items-center">
	<div class="col col-sm-auto text-center text-sm-left">
    <h1><?php echo $BL['be_subnav_admin_users_overview']; ?></h1>
  </div>
	<div class="col-12 col-sm text-center text-sm-right mb-3">
    <form action="cmsgo.php?do=admin&amp;s=1" method="post">
    	<div class="form-group">
      <input type="submit" value="<?php echo $BL['be_admin_usr_create'] ?>" class="btn btn-sm btn-blue" title="<?php echo $BL['be_admin_usr_create'] ?>">
      </div>
    </form>
  </div>
</div>



<div class="card">
  <div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_admin_usr_ltitle'] ?></h2></div>
  <div class="card-body">
<form action="cmsgo.php?do=admin" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />

  <div class="row align-items-center mb-3">
    <div class="col-12 col-sm">
      <div class="form-row">
      	<div class="col-sm-auto form-check form-check-inline">
					<input class="form-check-input" name="showadmin" id="showadmin" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_admin'], 1) ?>>
					<label for="showadmin" class="form-check-label"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-info"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span></label>
				</div>
				<div class="col-sm-auto form-check form-check-inline">
					<input class="form-check-input" name="showbefe" id="showbefe" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_befe'], 1) ?>>
					<label for="showbefe" class="form-check-label"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-success"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span></label>
				</div>
				<div class="col-sm-auto form-check form-check-inline">	
					<input class="form-check-input" name="shownorm" id="shownorm" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_norm'], 1) ?>>
					<label for="shownorm" class="form-check-label"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-primary"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span></label>
				</div>
				<div class="col-sm-auto form-check form-check-inline">
					<input class="form-check-input" name="showfe" id="showfe" value="1" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_fe'], 1) ?>>
					<label for="showfe" class="form-check-label"> <span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-warning"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span></label>
				</div>
      </div>
    </div>

  	<div class="col-12 col-sm-auto">
      <div class="input-group my-3 my-sm-0">
        <input type="search" name="filter" id="filter" size="15" data-toggle="tooltip" title="<?php echo $BL['be_tooltip_filter_user'] ?>" class="form-control form-control-sm" value="<?php
      if(isset($_SESSION['filter_results']) && count($_SESSION['filter_results']) ) {
          echo html(implode(' ', $_SESSION['filter_results']));
      }
      ?>">
				<div class="input-group-append">
					<input class="btn btn-sm btn-secondary" name="gofilter" type="button" value="<?php echo $BL['be_filter'] ?>">
				</div>
      </div>
  	</div>

    <?php
      if($_userInfo['pages_total'] > 1) {
        echo '<div class="col-sm-auto text-right">';
        echo '<table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td>';
        if($_SESSION['list_user_page'] > 1) {
            echo '<a class="btn btn-sm btn-blue" href="cmsgo.php?do=admin&amp;page='.($_SESSION['list_user_page']-1).'">';
            echo '<i class="fa fa-angle-left"></i></a>';
         } else {
            echo '<a class="btn btn-sm btn-blue disabled" href="cmsgo.php?do=admin&amp;page='.($_SESSION['list_user_page']-1).'">';
            echo '<i class="fa fa-angle-left"></i></a>';
        }
        echo '</td>';
        echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['list_user_page'];
        echo '"  class="form-control form-control-sm" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
        echo '<td>/'.$_userInfo['pages_total'].'&nbsp;</td>';
        echo '<td>';
        if($_SESSION['list_user_page'] < $_userInfo['pages_total']) {
            echo '<a class="btn btn-sm btn-blue" href="cmsgo.php?do=admin&amp;page='.($_SESSION['list_user_page']+1).'">';
            echo '<i class="fa fa-angle-right"></i></a>';
        } else {
          echo '<a class="btn btn-sm btn-blue disabled" href="cmsgo.php?do=admin&amp;page='.($_SESSION['list_user_page']+1).'">';
          echo '<i class="fa fa-angle-right"></i></a>';
        }
        echo '</td></tr></table></div>';
      } else {
        echo '<input type="hidden" name="page" id="page" value="1" />';
      }
      ?>

    <div class="col-12 col-sm-auto text-right">
    <select class="form-control form-control-sm custom-select">
      <option <?php echo ($_SESSION['list_user_count'] == '') ? 'selected ' : ''; ?>><?php echo $BL['be_amount_results'] ?></option>
        <option <?php echo ($_SESSION['list_user_count'] == '5') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=admin&amp;c=5'">5</option>
        <option <?php echo ($_SESSION['list_user_count'] == '10') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=admin&amp;c=10'">10</option>
        <option <?php echo ($_SESSION['list_user_count'] == '25') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=admin&amp;c=25'">25</option>
        <option <?php echo ($_SESSION['list_user_count'] == '50') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=admin&amp;c=50'">50</option>
        <option <?php echo ($_SESSION['list_user_count'] == '100') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=admin&amp;c=100'">100</option>
        <option <?php echo ($_SESSION['list_user_count'] == '99999') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=admin&amp;c=all'"><?php echo $BL['be_ftptakeover_all'].' '.$_entry['count_total'] ?></option>
    </select>
  	</div>
</div>

</form>

    <table class="table table-sm">
    <?php
    $bg_color1 = "#FFFFFF";
    $bg_color2 = "#f5f5f5";
    $zaehler = 0;
    if(!isset($new_user_id)) $new_user_id = 0;
    //Liste aller User erzeugen
    $sql  = "SELECT * FROM ".DB_PREPEND."cmsgo_user ".$_userInfo['where_query'].' ';
    $sql .= "ORDER BY usr_aktiv DESC, usr_fe DESC, usr_admin DESC, usr_name ASC ";
    $sql .= "LIMIT ".(($_SESSION['list_user_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
    $result = _dbQuery($sql);
    if($result[0]['usr_id']) {
        foreach($result as $userlist) {
            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            if($userlist["usr_id"] == $new_user_id) {
                $bg_color = "#FFF4CB";
            }
            $goto = "cmsgo.php?do=admin&amp;s=2&amp;u=".$userlist["usr_id"];
?>

        <tr bgcolor="<?php echo  $bg_color ?>" onmouseover="bgColor='#f5f5f5'" onmouseout="bgColor='<?php echo  $bg_color ?>'">
      <td width="30" class="align-middle"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-<?php

            if($userlist["usr_aktiv"] == 1) {
                if(!$userlist["usr_admin"]) {
                    switch($userlist["usr_fe"]) {
                        case 0: echo 'warning'; break;
                        case 1: echo 'primary'; break;
                        case 2: echo 'success'; break;
                    }
                } else {
                    echo "info";
                }
            } else {
                echo "inaktiv";
            }

          ?>"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span></td>
          <td class="align-middle" <?php if($userlist["usr_aktiv"]==1) {echo "class=\"dir\"";} else {echo "class=\"inaktiv\"";} ?>><a href="<?php echo $goto ?>"><?php

            if($userlist["usr_name"]) {
                $userlist["usr_name"] = html($userlist["usr_name"]." (".$userlist["usr_login"].")");
            } else {
                $userlist["usr_name"] = html($userlist["usr_login"]);
            }
            echo $userlist["usr_name"];

          ?></a></td>
          <td class="align-middle text-nowrap text-right">
          <?php

          echo '<button id="abtnuser'.$userlist['usr_id'].'" class="btn fa btn-sm visible '.($userlist["usr_aktiv"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$userlist['usr_id'].'" data-type="user" data-table="user" data-field="usr_aktiv" data-fieldid="usr_id" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';
          echo '<a class="btn btn-sm btn-blue '.($userlist["usr_aktiv"]==0 ? "btn-info" : "btn-success").' mr-1" role="button" aria-disabled="true" title="'.$BL['be_admin_usr_editusr'].": ".html($userlist["usr_login"]).'" data-toggle="tooltip" href="'.$goto .'"><i class="fa fa-pencil"></i></a>';
          echo '<a class="btn btn-sm btn-danger" data-toggle="tooltip" href="include/inc_act/act_user.php?del='. urlencode($userlist["usr_id"].":".$userlist["usr_email"]).'" title="'.$BL['be_admin_usr_ldel'].' '.html($userlist['usr_login']).'" onclick="return confirm(\''. $BL['be_admin_usr_ldel'].' \n'.js_singlequote($userlist['usr_login']).'\');"><i class="fa fa-trash"></i></a>';
            ?>

          </td>
        </tr>
        <?php
            $zaehler++;
        }
    } //Ende Schleife Anzeige User
?>
</table>
<hr />
<div class="alert alert-secondary mb-0">
  <div class="row">
    <div class="col-sm-auto"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-info"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span> Admin User</div>
    <div class="col-sm-auto"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-success"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span> Frontend & Backend User</div>
    <div class="col-sm-auto"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-primary"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span> Backend User</div>
    <div class="col-sm-auto"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x text-warning"></i><i class="fa fa-user fa-stack-1x fa-inverse"></i></span> Frontend User</div>
  </div>
</div>


</div>
</div>
