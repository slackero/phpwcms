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

// List available files
$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 ";
if(empty($_SESSION["wcs_user_admin"])) {
    $file_sql .= "AND f_uid=".$_SESSION["wcs_user_id"].' ';
}
$file_sql .= "AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {

    $file_durchlauf = 0;
    $bg_toggle = false;

    $zieldatei = "phpwcms.php?do=files&amp;f=0";

    foreach($file_result as $file_row) {
        $filename = html($file_row["f_name"]);
        $bg_toggle = !$bg_toggle;
        $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';

        $file_row['edit'] = '<a href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'" data-bs-toggle="tooltip" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'">';

        if(!$file_durchlauf) {
            echo '<tr><td colspan="2" class="p-0"><table class="table-borderless w-100">'."\n";
        }
        echo '<tr'.$row_class.">\n";
        echo "<td width=30>";
        echo "<span class=\"admin-slist \" data-bs-toggle=\"tooltip\" data-bs-html=\"true\" ";

        echo 'title="ID: '.$file_row["f_id"].' <br>Sort: . '.$file_row["f_sort"];
        echo '<br>Name: '.html($file_row["f_name"]);
        if($file_row["f_copyright"]) {
            echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
        }
        echo '">';
        echo "<i class=\"fa fa-".extimg($file_row["f_ext"])."\"";
        echo "></i></span></td>\n<td>";
        echo $file_row['edit'] . $filename."</a></td>\n";

        // Build button bar for file
        echo '<td class="text-end text-nowrap px-0">'.LF;
        echo '<div class="btn-group btn-group-sm" role="group">'.LF;

        // Edit file info button
        echo '<a class="btn btn-xs btn-blue" role="button" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'" data-bs-toggle="tooltip" href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'"><i class="fa fa-pencil-alt fa-fw mt-1"></i></a>';

        echo '<div class="btn-group btn-group-sm" role="group">';
        echo '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" href="#" id="dropdownFcontentLink'.$file_row["f_id"].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';
        echo '<div class="dropdown-menu" aria-labelledby="dropdownFcontentLink'.$file_row["f_id"].'">';
        echo '<h6 class="dropdown-header">'.$filename.'</h6>';

        // Download file button
        echo '<a class="dropdown-item" href="include/inc_act/act_download.php?dl='.$file_row["f_id"].
             '"  target="_blank" data-bs-toggle="tooltip" title="'.$BL['be_fprivfunc_dlfile'].': '.$filename.'">'.
             '<i class="ms-1 fa fa-fw fa-download" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_dlfile'].'</a>'; //target='_blank'
        // Cut / clipboard file button
        if($cutID == $file_row["f_id"]) {
            echo '<i class="fa fa-cut disabled" aria-hidden="true" data-bs-toggle="tooltip" title="'.$BL['be_fprivfunc_clipfile'].': '.$filename.'"></i>';
        } else {
            echo '<a class="dropdown-item" href="'.$zieldatei.'&amp;cut='.$file_row["f_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'">';
            echo '<i class="fa-fw ms-1 fa fa-cut" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_cutfile'].'</a>';
        }
        // Delete / move to trash button
        if ($file_row["f_uid"] == intval($_SESSION["wcs_user_id"])) {
            //if user is owner then delete button is active
            $confirm_msg = $GLOBALS['BL']['be_fprivfunc_jsmovetrash1'] . "\n[" . $filename . "]\n" . $GLOBALS['BL']['be_fprivfunc_jsmovetrash2'];
            echo '<a class="dropdown-item" href="include/inc_act/act_file.php?trash=' . $file_row["f_id"] . '%7C' . '1' .
                 '" data-bs-toggle="tooltip" title="' . $GLOBALS['BL']['be_fprivfunc_movetrash'] . ': ' . $filename . '" data-confirm-danger="' . html_specialchars($confirm_msg) . '">' .
                 '<i class="fa-fw ms-1 far fa-trash-alt" aria-hidden="true"></i> ' . $GLOBALS['BL']['be_fprivfunc_movetrash'] . '</a>';
        } else {
            echo '<div class="dropdown-item disabled text-muted"><i class="fa-fw ms-1 far fa-trash-alt text-muted" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_notrash'].'</div>';
        }
        echo '</div></div>'; // Close dropdown-menu & inner btn-group

        // Toggle active/inactive button
        echo '<button id="abtnfileaktiv'.$file_row["f_id"].'" class="btn fa fa-fw btn-xs visible '.($file_row["f_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$file_row["f_id"].'" data-type="fileaktiv" data-table="file" data-field="f_aktiv" data-fieldid="f_id" data-bs-toggle="tooltip" title="'.$BL['be_fprivfunc_cactivefile'].': '.$filename.'"></button>';
        // Toggle public/private button
        echo '<button id="abtnfilepublic'.$file_row["f_id"].'" class="btn fa fa-fw btn-xs public '.($file_row["f_public"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$file_row["f_id"].'" data-type="filepublic" data-table="file" data-field="f_public" data-fieldid="f_id" data-bs-toggle="tooltip" title="'.$BL['be_fprivfunc_cpublicfile'].': '.$filename.'"></button>';
        echo '</div>'; // Close outer btn-group

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
                    "thumb_name"    =>  md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));

                if($thumb_image != false) {
                    echo '<tr'.$row_class.">\n";
                    echo '<td></td>'."\n".'<td colspan="2" class="pt-0 pb-2">';
                    echo $file_row['edit'];
                    echo '<img src="' . $thumb_image['src'] .'" border="0" style="max-height:'.$phpwcms["img_list_height"].'px;max-width:100%;width:auto;height:auto;" '.$thumb_image[3].'></a></td>'."\n";
                    echo "\n</tr>\n";
                }

            } else {
                echo '<tr'.$row_class.">\n";
                echo '<td></td>'."\n".'<td colspan="2" class="pt-0 pb-2">';
                echo $file_row['edit'];
                echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-height:'.$phpwcms["img_list_height"].'px;max-width:100%;width:auto;height:auto;"></a></td>';
                echo "\n</tr>\n";
            }

        }
        $file_durchlauf++;
    }
    if($file_durchlauf) { // close file list tables
        echo "</table>\n";
    }
} // end listing files
