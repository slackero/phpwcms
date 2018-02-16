<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// List available files
$file_sql = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_pid=0 ";
if(empty($_SESSION["wcs_user_admin"])) {
    $file_sql .= "AND f_uid=".$_SESSION["wcs_user_id"].' ';
}
$file_sql .= "AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {

    $file_durchlauf = 0;

    $zieldatei = "cmsgo.php?do=files&amp;f=0";

    foreach($file_result as $file_row) {
        $filename = html($file_row["f_name"]);

        $file_row['edit'] = '<a href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'">';

        if(!$file_durchlauf) {
            echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table class=\"table-no-border\" width=\"100%\">\n";
        } else {
            echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"3\"></td></tr>\n";
        }
        echo "<tr>\n";
        echo "<td width=30>";
        echo "<span class=\"admin-slist \" data-toggle=\"tooltip\" data-html=\"true\" ";

        echo 'title="ID: '.$file_row["f_id"].' <br>Sort: . '.$file_row["f_sort"];
        echo '<br>Name: '.html($file_row["f_name"]);
        if($file_row["f_copyright"]) {
            echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
        }
        echo '">';
        echo "<i class=\"fa fa-".extimg($file_row["f_ext"])."\"";
        echo "></i></span></td>\n<td>";
        echo $file_row['edit'] . $filename."</a></td>\n";

        //Aufbauen Buttonleiste für jeweilige Datei
        echo '</td><td class="text-right px-0" nowrap="nowrap">'.LF;
        echo '<div class="btn-group" role="group">'.LF;

        //Button zum Bearbeiten der Dateiinformationn
        echo '<a class="btn btn-xs btn-blue" role="button" aria-disabled="true" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'" href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'"><i class="fa fa-pencil fa-fw mt-1"></i></a>';

        echo '<div class="btn-group" role="group">';
        echo '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" type="button" href="#" id="dropdownFcontentLink'.$file_row["f_id"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';
        echo '<div class="dropdown-menu" aria-labelledby="dropdownFcontentLink'.$file_row["f_id"].'">';

        //Button zum Downloaden der Datei
        echo '<a class="dropdown-item" href="include/inc_act/act_download.php?dl='.$file_row["f_id"].
             '"  target="_blank" data-toggle="tooltip" title="'.$BL['be_fprivfunc_dlfile'].': '.$filename.'">'.
             '<i class="ml-1 fa fa-fw fa-download" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_dlfile'].': '.$filename.'</a>'; //target='_blank'
        //Button zum Erzeugen eines Neuen Unterverzeichnisses
        if($cutID == $file_row["f_id"]) {
            echo '<i class="fa fa-cut disabled" aria-hidden="true" data-toggle="tooltip" title="'.$BL['be_fprivfunc_clipfile'].': '.$filename.'"></i>';
        } else {
            echo '<a class="dropdown-item" href="'.$zieldatei.'&amp;cut='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'">';
            echo '<i class="fa-fw ml-1 fa fa-cut" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'</a>';
        }
        //Button zum Löschen der Datei
        if ($file_row["f_uid"] == intval($_SESSION["wcs_user_id"])) {
            //if user is owner then delete button is active
            echo '<a class="dropdown-item" href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'%7C'.'1'.
             '" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_movetrash'].': '.$filename."\" onclick=\"return confirm('".
             $GLOBALS['BL']['be_fprivfunc_jsmovetrash1']."\\n[".$filename."]\\n".$GLOBALS['BL']['be_fprivfunc_jsmovetrash2'].
             "');\">".
             '<i class="fa-fw ml-1 fa fa-trash" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_movetrash'].': '.$filename.'</a>';
        } else {
            echo '<div class="dropdown-item"><i class="fa-fw ml-1 fa fa-trash disabled" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_notrash'].'</div>';
        }
        echo "</div></div></div>";

        //Button zum Umschalten zwischen Aktiv/Inaktiv
        echo '<a href="include/inc_act/act_file.php?aktiv='.$file_row["f_id"].'%7C'.true_false($file_row["f_aktiv"]).
             '" data-toggle="tooltip" title="'.$BL['be_fprivfunc_cactivefile'].': '.$filename.'">';
        echo '<div class="btn fa btn-sm ml-1 visible '.($file_row["f_aktiv"]==0 ? "btn-danger" : "btn-success").' ml-1"></div></a>';
        //Button zum Umschalten zwischen Public/Non-Public
        echo '<a href="include/inc_act/act_file.php?public='.$file_row["f_id"].'%7C'.true_false($file_row["f_public"]).
             '" data-toggle="tooltip" title="'.$BL['be_fprivfunc_cpublicfile'].': '.$filename.'">';
        echo '<div class="btn fa btn-sm ml-1 public '.($file_row["f_public"]==0 ? "btn-danger" : "btn-success").'"></div></a>';

        // end
        echo "</tr>\n";

        if(!empty($_SESSION["wcs_user_thumb"])) {

            // now try to get existing thumbnails or if not exists
            // build new based on default thumbnail listing sizes

            if(empty($file_row["f_svg"])) {

                // build thumbnail image name
                $thumb_image = get_cached_image(array(
                    "target_ext"    =>  $file_row["f_ext"],
                    "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                    "thumb_name"    =>  md5($file_row["f_hash"].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                ));

                if($thumb_image != false) {
                    echo "<tr>\n";
                    echo "<td></td>\n<td colspan=\"2\">";
                    echo $file_row['edit'];
                    echo '<img src="' . $thumb_image['src'] .'" border="0" '.$thumb_image[3].'></a></td>'."\n";
                    echo "\n</tr>\n";
                }

            } else {
                echo "<tr>\n";
                echo "<td></td>\n<td colspan=\"2\">";
                echo $file_row['edit'];
                echo '<img src="'.CMSGO_RESIZE_IMAGE.'/'.$cmsgo["img_list_width"].'x'.$cmsgo["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-width:'.$cmsgo["img_list_width"].'px;height:auto;"></a></td>';
                echo "\n</tr>\n";
            }

        }
        $file_durchlauf++;
    }
    if($file_durchlauf) { // close file list tables
        echo "</table>\n";
    }
} // end listing files
