<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//user group
$GLOBALS['BE']['HEADER']['optionselect.js'] = getJavaScriptSourceLink('include/inc_js/optionselect.js');

// Optimization: Load all group modkeys at once to avoid querying inside a loop
$existing_groups = array();
$sql = "SELECT group_modkey FROM ".DB_PREPEND."cmsgo_usergroup WHERE group_modkey != '' AND group_trash = 0";
$result = _dbQuery($sql);
if ($result) {
    foreach ($result as $row) {
        $existing_groups[$row['group_modkey']] = true;
    }
}

foreach($cmsgo['modules'] as $value) {
  if(!isset($existing_groups[$value["name"]])) {
    $data = [
        'group_name'    => $BL['modules'][$value['name']]['backend_menu'],
        'group_member'  => '1', // Fix: Use '1' string instead of array ['1'] to avoid array serialization in DB
        'group_value'   => 'Modul '.$BL['modules'][$value['name']]['backend_menu'],
        'group_trash'   => 0,
        'group_active'  => 1,
        'group_modkey'  => $value["name"]
    ];

    $insert_result = _dbInsert('cmsgo_usergroup', $data);
    if(isset($insert_result['INSERT_ID'])) {
      echo '<div class="alert alert-success">Module '.$BL['modules'][$value['name']]['backend_menu'].' erfolgreich hinzugefügt</div>';
    }
  }
}
?>

<div class="row align-items-center">
  <div class="col col-sm-auto text-center text-sm-left">
    <h1><?php echo $BL['be_subnav_admin_groups'] ;?></h1>
  </div>
  <div class="col-12 col-sm text-center text-sm-right mb-3">
    <div class="form-group">
      <form action="cmsgo.php?do=admin&amp;p=1&amp;create_group=1" method="post"><input type="submit" value="<?php echo $BL['be_admin_group_add'] ?>" class="btn btn-sm btn-blue" data-toggle="tooltip" title="<?php echo $BL['be_admin_group_add'] ?>"></form>
    </div>
  </div>
</div>

<!-- CREATE GROUP -->
<?php
//enym was groupid
if(isset($_GET["create_group"]) || isset($_GET["u"])) {
?>
<div class="card">
  <div class="card-header"><h2><i class="fa fa-users"></i> <?php echo isset($_GET["create_group"]) ? $BL['be_admin_group_add'] : $BL['be_subnav_admin_groups'] ." ". $BL['be_cnt_guestbook_edit']; ?></h2></div>
  <div class="card-body">

<?php
  $group["id"]        = empty($_GET['u']) ? 0 : intval($_GET['u']);
  $group["name"]      = '';
  $group["member"]    = array();
  $group["value"]     = '';
  $group["trash"]     = 0;
  $group["active"]    = 1;

  if($group["id"]) {

      $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_usergroup WHERE group_id=".$group["id"]." LIMIT 1";
      $result = _dbQuery($sql);
      if(isset($result[0]['group_id'])) {
          $group["name"]      = ($result[0]['group_syskey']) ? $groupnames[$result[0]["group_syskey"]] : $result[0]["group_name"];
          $group["member"]    = empty($result[0]["group_member"]) ? array() : explode(',', $result[0]["group_member"]);
          $group["value"]     = $result[0]["group_value"];
          $group["trash"]     = $result[0]["group_trash"];
          $group["active"]    = $result[0]["group_active"];
          $group["syskey"]    = $result[0]["group_syskey"];
      }

      $sendbutton = $BL['be_admin_fcat_button1']; //UPDATE GROUP

  } else {

      $sendbutton = $BL['be_admin_fcat_button2'];   //create group
  }

  if(!empty($_POST["group_aktion"])) {

      $group["id"]        = intval($_POST["group_id"]);
      $group["name"]      = clean_slweg($_POST["group_name"], 250);
      $group["member"]    = isset($_POST["acat_access"]) && is_array($_POST["acat_access"]) ? implode(',', $_POST["acat_access"]) : '';
      $group["value"]     = clean_slweg($_POST["group_value"]);
      $group["trash"]     = empty($_POST["group_trash"]) ? 0 : intval($_POST["group_trash"]);
      $group["active"]    = empty($_POST["group_active"]) ? 0 : 1;
      $group["active"]    = (strlen ($_POST["group_syskey"])> 1) ? 1 : $group["active"];

      if(empty($group["name"])) {

          $group["error"] = 1;

      } else {

          $data = array(
              'group_name'    => $group["name"],
              'group_member'  => $group["member"],
              'group_value'   => $group["value"],
              'group_trash'   => $group["trash"],
              'group_active'  => $group["active"]
          );

          $result = $group["id"] ? _dbUpdate('cmsgo_usergroup', $data, 'group_id='.$group["id"]) : _dbInsert('cmsgo_usergroup', $data);

          if(isset($result['AFFECTED_ROWS']) || isset($result['INSERT_ID'])) {
              headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string().'&do=admin&p=1');
          } else {
              echo _dbError();
          }

      }

      $group["member"] = convertStringToArray($group["member"]);
  }
?>

<form action="cmsgo.php?do=admin&amp;p=1&amp;create_group=1" method="post" name="editsitestructure" id="editsitestructure" onsubmit="selectAllOptions(this.acat_access);selectAllOptions(this.acat_cp);var x = wordcount(this.acat_name.value);if(x&lt;1) {alert('Fill in a category title! \n\n('+x+' words total)');this.acat_name.focus();return false;}">

	<?php if(!empty($group["error"])) { ?>
	<div class="alert alert-danger"><?php echo $BL['be_admin_usr_err'] ?>: <?php echo $BL['be_fpriv_name'] ?></div>
	<?php } ?>

	<fieldset>
		<div class="form-group align-items-center form-row" >
			<label for="be_fpriv_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_fpriv_name'] ?></label>
			<div class="col">
					<input name="group_name" class="form-control form-control-sm" id="group_name" value="<?php echo empty($group["name"]) ? '' : html($group["name"]) ?>" size="40" maxlength="250" type="text" <?php echo ($group["syskey"] ? 'readonly' : '') ?> />
			</div>
		</div>
	</fieldset>

	<fieldset>
		<div class="form-group form-row">
				<label for="be_cnt_description" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
				<div class="col">
					<textarea name="group_value" rows="4" class="form-control form-control-sm" id="group_value" <?php echo ($group["syskey"] ? 'readonly' : '') ?>><?php echo html($group["value"]) ?></textarea>
				</div>
		</div>
	</fieldset>

    <!-- USER RIGHTS enym.com   -->
    <div class="form-group form-row" >
      <label for="be_selection" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_selection'] ?></label>
        <div class="col">
            <?php
            // list all available frontend users and put into temp array
            $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_user WHERE usr_aktiv != 9 ORDER BY usr_fe, usr_name, usr_login";
            $result = _dbQuery($sql);
            $_temp_usr = array();
            if(isset($result[0]['usr_id'])) {
                foreach($result as $row) {
                    $_temp_usr[$row['usr_id']]['name'] = html($row['usr_name']);
                    $_temp_usr[$row['usr_id']]['login'] = html($row['usr_login']);
                    $_temp_usr[$row['usr_id']]['fe'] = $row['usr_fe'];
                    $_temp_usr[$row['usr_id']]['active'] = $row['usr_aktiv'];
                    $_temp_usr[$row['usr_id']]['admin'] = $row['usr_admin'];
                }
            }
            ?>
            <select name="acat_access[]" id="acat_access" size="12" multiple="multiple" class="custom-select form-control form-control-sm" onDblClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);">
                <?php
                if (count($_temp_usr)) {
                    // list all fe_users
                    foreach ($_temp_usr as $key => $value) {
                        if (isset($group['member']) && empty($group['error'])) {
                            if (in_array($key, $group["member"])) {
                                echo '<option value="' . $key . '"';
                                if (!$value['active']) {
                                    echo ' style="color:#999999;"';
                                } elseif ($value['admin']) {
                                    echo ' style="color:#3F61BF;"';
                                }
                                echo '>' . trim($_temp_usr[$key]['name'] . ' (' . $_temp_usr[$key]['login'] . ')') . "</option>\n";
                                unset($_temp_usr[$key]);
                            }
                        }
                    }
                }
                ?>
            </select>
        </div>
      <div class="col-sm-auto">
          <button type="button" class="btn btn-sm btn-blue mt-2" data-toggle="tooltip" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onClick="moveAllOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access);selectAllOptions(document.editsitestructure.acat_access);"><i class="fa fa-angle-double-left fa-fw" aria-hidden="true"></i></button><br />
          <button type="button" class="btn btn-sm btn-blue mt-2" data-toggle="tooltip" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"><i class="fa fa-angle-left fa-fw" aria-hidden="true"></i></button><br />
          <button type="button" class="btn btn-sm btn-blue mt-2" data-toggle="tooltip" title="<?php echo $BL['be_admin_struct_remove_this']?>" onClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);"><i class="fa fa-angle-right fa-fw" aria-hidden="true"></i></button><br />
          <button type="button" class="btn btn-sm btn-blue mt-2" data-toggle="tooltip" title="<?php echo $BL['be_admin_struct_remove_all']?>" onClick="moveAllOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers);"><i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i></button>
      </div>
      <div class="col">
        <select name="acat_feusers" size="12" multiple="multiple" id="acat_feusers" class="custom-select form-control form-control-sm" onDblClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);">
            <?php
                // list all available fe_users
            if (count($_temp_usr)) {
                foreach ($_temp_usr as $key => $value) {
                    echo '<option value="' . $key . '"';
                    if (!$value['active']) {
                        echo ' style="color:#999999"';
                    } elseif ($value['admin']) {
                        echo ' style="color:#3F61BF"';
                    }
                    echo '>' . trim($_temp_usr[$key]['name'] . ' (' . $_temp_usr[$key]['login'] . ')') . "</option>\n";
                }
            }
            ?>
        </select>
      </div>
      </div>
      <!-- USER RIGHTS -->
      <div class="form-group form-row align-items-center">
				<label for="be_ftptakeover_status" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>
				<div class="col">
					<div class="form-check">
						<input class="form-check-input" name="group_active" type="checkbox" id="group_active" value="1" <?php is_checked(1, empty($group["active"]) ? 0 : $group["active"]); ?> <?php echo ($group["syskey"] ? 'disabled="disabled"' : '') ?> />
						<label for="group_active" class="form-check-label"><?php echo $BL['be_ftptakeover_active'] ?></label>
					</div>
				</div>
      </div>

	<div class="form-group form-row align-items-center">
		<div class="col-sm-2"></div>
		<div class="col">
      <div class="mb-4 text-center text-sm-left">
          <input name="group_id" type="hidden" id="group_id" value="<?php echo $group["id"] ?>" />
          <input name="group_aktion" type="hidden" id="group_aktion" value="1" />
          <input name="Submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $sendbutton ?>" />
          <input name="donotsubmit" type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='cmsgo.php?do=admin&amp;p=1';" />
          <input type="hidden" value="<?php echo $group["syskey"] ?>" name="group_syskey"  id="group_syskey" />
      </div>
    </div>
  </div>

    </form>
<?php
}
?>

<div class="card">
<div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_cnt_title_overview'] ;?></h2></div>
	<div class="card-body">
	<div class="table-responsive">
	<table class="table table-sm table-hover mb-0">
	<?php

    $bg_color1 = "#FFFFFF";
    $bg_color2 = "#F3F5F8";
    $zaehler = 0;
    if(empty($new_group_id)) {
        $new_group_id = 0;
    }
    //Liste aller Gruppen erzeugen
    $result = _dbGet('cmsgo_usergroup', '*', 'group_active != 9', '', 'group_syskey, group_name');
    if(isset($result[0]['group_id'])) {

        foreach($result as $grouplist) {

            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            if($grouplist["group_id"] == $new_group_id) {
                $bg_color = "#FFCC00";
            }
            $goto = "cmsgo.php?do=admin&amp;p=1&amp;s=2&amp;u=".$grouplist["group_id"];
            $grouplist["group_name"] = ($grouplist['group_syskey']) ? $groupnames[$grouplist["group_syskey"]] : $grouplist["group_name"];
?>
        <tr bgcolor="<?php echo  $bg_color ?>" onmouseover="bgColor='#DBFF48'" onmouseout="bgColor='<?php echo $bg_color ?>'">
          <td width="25" align="center"><i class="fa fa-users <?php echo $grouplist["group_active"] == 1 ? 'text-blue' : 'text-muted'; ?>" aria-hidden="true"></i></td>
          <td class="<?php echo $grouplist["group_active"] ? 'dir' : 'inaktiv'; ?>"><a href="<?php echo $goto ?>"><?php

            $grouparray = convertStringToArray($grouplist["group_member"]);
            $total_member = empty($grouparray[0]) ? 0 : count($grouparray);

            echo $grouplist["group_name"] ? html($grouplist["group_name"]).' <span style="color:#999999;">('.$total_member.' '.$BL['be_cnt_rssfeed_item'].')</span>' : 'n.a.';

          ?></a></td>
          <td class="text-right text-nowrap">
            <?php if ($grouplist["group_syskey"]) {
              echo '';
              } else {
                echo '<button id="abtngroup'.$grouplist["group_id"].'" class="btn fa btn-sm visible '.($grouplist["group_active"]==0 ? "btn-danger" : "btn-success").' " data-id="'.$grouplist["group_id"].'" data-type="group" data-table="usergroup" data-field="group_active" data-fieldid="group_id" aria-disabled="true" data-toggle="tooltip" title="aktivieren/deaktivieren"></button>';
              }
            ?>


            <a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="<?php
                echo $BL['be_admin_group_edit'].": ".html($grouplist["group_name"])
            ?>" data-toggle="tooltip" href="<?php echo $goto ?>"><i class="fa fa-pencil-alt fa-fw"></i></a>

            <?php if ($grouplist["group_syskey"]) {
            echo '<span class="btn btn-sm btn-light"><i class="fa fa-fw"></i></span>';
            } else {
            ?>
            <a class="btn btn-sm btn-danger" role="button" aria-disabled="true" title="<?php echo $BL['be_admin_group_ldel']." ".html($grouplist["group_name"]); ?>" data-toggle="tooltip" href="include/inc_act/act_usergroup.php?del=<?php
                echo urlencode($grouplist["group_id"].":".$grouplist["group_name"]);
            ?>" onclick="return confirm('Delete group <?php echo js_singlequote($grouplist["group_name"]) ?>');"><i class="far fa-trash-alt fa-fw"></i></a>

            <?php }  ?></td>
        </tr>
        <?php

        }

    } else {
            echo '<tr><td colspan="3">'.$BL['be_admin_group_nogroup'].': <a href="cmsgo.php?do=admin&amp;p=1&amp;create_group=1">'.$BL['be_admin_group_add'].'</a></td></tr>';
    }
        ?>
      </table>
      </div>
  </div>
</div>

<div class="form-group text-center text-sm-right mt-3 mb-0">
  <form action="cmsgo.php?do=admin&amp;p=1&amp;create_group=1" method="post"><input type="submit" value="<?php echo $BL['be_admin_group_add'] ?>" class="btn btn-sm btn-blue" data-toggle="tooltip" title="<?php echo $BL['be_admin_group_add'] ?>"></form>
</div>
