<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);
$js_files_all = array();
$js_files_select = array();

require_once __DIR__ . '/include/config/conf.inc.php';
require_once __DIR__ . '/include/config/conf.indexpage.inc.php';
require_once __DIR__ . '/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.session.php';

if (empty($_SESSION['wcs_user_lang'])) {

    $_SESSION = array();
    @session_destroy();
    headerRedirect(PHPWCMS_URL, 401);

} else {

    $user_lang = strtolower(substr($_SESSION['wcs_user_lang'], 0, 2));

    require PHPWCMS_ROOT . '/include/inc_lang/backend/en/lang.inc.php';
    $cust_lang = PHPWCMS_ROOT . '/include/inc_lang/backend/' . $user_lang . '/lang.inc.php';
    if (is_file($cust_lang)) {
        include $cust_lang;
    }

}

if (isset($_GET['open'])) {
    list($open_id, $open_value) = explode(':', $_GET['open']);
    $open_id = intval($open_id);
    if (empty($open_value)) {
        unset($_SESSION['structure'][$open_id]);
    } else {
        $_SESSION['structure'][$open_id] = $open_value;
    }
}

$js_aktion = isset($_GET['opt']) ? intval($_GET['opt']) : 1;
$field = isset($_GET['field']) ? clean_slweg($_GET['field']) : 'id';
if (isset($_GET['CKEditorFuncNum'])) {
    $ckeditor_action = intval($_GET['CKEditorFuncNum']);
    $_SESSION['CKEditorFuncNum'] = $ckeditor_action;
} elseif (!empty($_SESSION['CKEditorFuncNum'])) {
    $ckeditor_action = $_SESSION['CKEditorFuncNum'];
} else {
    $ckeditor_action = 0;
}

switch ($js_aktion) {
    case 1:
        $js = 'parent.document.newsform.cnt_link.value';
        break;

    case 2:
        $js = 'parent.document.article.article_lang_id.value';
        break;

    case 3:
        $js = 'parent.document.editsitestructure.acat_lang_id.value';
        break;

    case 4:
    case 6:
        $js = 'parent.document.articlecontent.' . $field . '.value';
        break;

    case 5:
        $js = 'parent.document.articlecontent.calias.value';
        break;

    //TinyMCE / CKEditor
    case 16:
        $js = "if(window.opener && window.opener.activeTinyMceCallback){window.opener.activeTinyMceCallback('index.php?%s');window.close();}else{window.opener.CKEDITOR.tools.callFunction(" . $ckeditor_action . ", 'index.php?%s');window.close();}";
        break;

    default:
        $js = '';
}

require_once PHPWCMS_ROOT . '/include/inc_lib/article.contenttype.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';

checkLogin();
validate_csrf_tokens();
define('CSRF_GET_TOKEN', get_token_get_string());

require_once PHPWCMS_ROOT . '/include/inc_lib/backend.functions.inc.php';

?><!DOCTYPE html>
<html lang="<?php echo $user_lang; ?>" data-theme="<?php echo html(get_backend_theme()); ?>">
<head>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <title><?php echo $BL['be_articlebrowser_selector']; ?></title>
    <script>
    (function() {
        var storedTheme = localStorage.getItem('phpwcms_theme');
        var theme = storedTheme || '<?php echo html(get_backend_theme()); ?>' || 'auto';
        document.documentElement.setAttribute('data-theme', theme);
    })();
    </script>
    <link href="include/inc_css/backend.min.css" rel="stylesheet" type="text/css">
    <style>
        tr.struct:hover {
            background-color: #CCFF00;
            cursor: default;
        }

        tr.structarticle:hover {
            background-color: #CCFF00;
            cursor: default;
        }

        tr.structarticlecontent:hover {
            background-color: #FFDE01;
            cursor: pointer;
        }
    </style>

    <script src="include/inc_js/jquery/jquery-3.7.1.min.js"></script>
    <?php echo getJavaScriptTranslations(); ?>
    <script src="include/inc_js/phpwcms.min.js"></script>
    <script src="include/inc_js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_GET_TOKEN = '<?php echo CSRF_GET_TOKEN; ?>';
    </script>


    <?php if ($js_aktion == 16): ?>
        <script type="text/javascript">
            if (window.opener && window.opener.CKEDITOR) {
                const dialog = window.opener.CKEDITOR.dialog.getCurrent();
                const docIdField = dialog.getContentElement('info', 'protocol');
                docIdField.setValue('');
            }
        </script>
    <?php endif; ?>
</head>
<body class="filebrowser">
<ul class="nav nav-tabs border-0 my-2">
    <li role="presentation" class="nav-item">
        <a href="#" class="btn btn-blue me-2">
            <?php echo $BL['be_article_title'] ?>
        </a>
    </li>
    <?php if ($js_aktion == 16): ?>
        <li role="presentation" class="nav-item">
            <a href="filebrowser.php?<?php echo CSRF_GET_TOKEN; ?>&amp;opt=16" class="btn btn-blue">
                <?php echo $BL['FILE_TITLE'] ?>
            </a>
        </li>
    <?php endif; ?>
</ul>

<table class="table table-sm">
    <?php

    $child_count = get_root_childcount(0);
    $an = $indexpage['acat_name'];

    $a = '<tr bgcolor="#e8e8e8" class="struct">';
    $a .= '<td>';
    $a .= '<table class="table-borderless w-100"><tr>';
    $a .= '<td class="text-nowrap">';
    $a .= $child_count ? '<a href="phpwcms.php?' . CSRF_GET_TOKEN . '&amp;do=articles&amp;open=0:' . (($_SESSION['structure'][0]) ? 0 : 1) . '">' : '';

    $a .= '<i class="fa fa-caret-' . (($child_count) ? (($_SESSION['structure'][0] == 0) ? 'right' : 'down') : 'right');
    $a .= ' fa-fw" aria-hidden="true"></i>' . (($child_count) ? '</a>' : '');

    $info = '<table class="text-start"><tr><td>ID:</td><td><b>0</b></td></tr>';
    $info .= '<tr><td>ALIAS:</td><td>' . $indexpage['acat_alias'] . '</td></tr></table>';

    $a .= '<i class="far fa-folder fa-fw" aria-hidden="true" data-bs-toggle="tooltip" data-html="true" title="' . html($info) . '"></i>';

    $a .= '</td>';
    $a .= '<td width="97%"><strong>' . $an . '</strong></td></tr></table></td>';

    echo $a;

    $listmode = 0;
    $counter = 0;

    struct_articlelist(0, 0, $indexpage['acat_order'], $js, $js_aktion);
    struct_list(0, 0, 0, 0, 0, 0, 0, $listmode, $counter, $js, $js_aktion);
    ?></table>

<script>
    $(function() {
        $('<?php if ($js_aktion == 5): ?>'r.structarticleconten'<?php else: ?>a.structarticle<?php endif; ?>').
        on('click', function() {
            <?php
            echo $js . "=$(this).attr('data-aid');";
            if ($js_aktion == 6) {
                echo 'parent.$("#browserModal").modal("hide");';
            } elseif ($js_aktion == 2) {
                echo "parent.$('input:radio[name=\"article_lang_type\"][value=\"'+$(this).attr('data-idtype')+'\"]').prop('checked',true).trigger('change');";
                echo "parent.$('#browserModal').modal('hide');";
            } elseif ($js_aktion != 16) {
                echo "parent.$('input:radio[name=\"acat_lang_type\"][value=\"'+$(this).attr('data-idtype')+'\"]').prop('checked',true).trigger('change');";
                echo "parent.$('#browserModal').modal('hide');";
            }
            ?>
        });
    });
</script>
</body>
</html>
<?php

function struct_list($id, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode = 1, $counter = 0, $js = '', $js_aktion = 0)
{

    $counter++;
    $sql = 'SELECT t1.*, t2.template_default, t2.template_name, t2.template_trash FROM ' . DB_PREPEND . 'phpwcms_articlecat t1 ';
    $sql .= 'LEFT JOIN ' . DB_PREPEND . 'phpwcms_template t2 ON t1.acat_template=t2.template_id ';
    $sql .= 'WHERE acat_trash=0 AND acat_struct=' . intval($id) . ' ORDER BY acat_sort';
    $result = _dbQuery($sql);

    if (isset($result[0]['acat_struct'])) {
        $count_row = 0;
        foreach ($result as $row) {
            $struct[$count_row] = $row;
            $count_row++;
        }

        if (isset($struct[0])) {
            foreach ($struct as $key => $value) {
                struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $listmode, $cut_article, $js, $js_aktion);
            }
        }
    }
}

function struct_levellist($struct, $key, $counter, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $listmode, $cut_article, $js, $js_aktion) {

    global $BL;

    $page_val = ($listmode) ? 'do=articles&amp;p=6' : 'do=articles';
    $child_count = get_root_childcount($struct[$key]['acat_id']);

    $an = html($struct[$key]['acat_name']);
    $a = '<tr class="structarticle">';
    $a .= '<td width="80%">';
    $a .= '<table class="table-borderless"' . '><tr>';
    $a .= '<td class="text-end text-nowrap">';
    $a .= ($child_count) ? '<a href="articlebrowser.php?' . CSRF_GET_TOKEN . '&amp;opt=' . $js_aktion . '&amp;' . $page_val . '&amp;open=' . rawurlencode($struct[$key]['acat_id'] . ':' . (!empty($_SESSION['structure'][$struct[$key]['acat_id']]) ? 0 : 1)) . '">' : '';
    $a .= '<i class="fa fa-caret-' . ($child_count ? (empty($_SESSION['structure'][$struct[$key]['acat_id']]) ? 'right' : 'down') : 'right') . ' fa-fw slist-' . $counter . '" aria-hidden="true"></i>' . ($child_count ? '</a>' : '');

    $info = '<table class="text-start">';
    $info .= '<tr><td>ID:</td><td><b>' . $struct[$key]['acat_id'] . '</b></td></tr>';
    $info .= '<tr><td>' . $BL['be_alias'] . ':</td><td>' . $struct[$key]['acat_alias'] . '</td></tr>';
    $info .= '<tr><td>' . $BL['be_cnt_sortvalue'] . ':</td><td>' . $struct[$key]['acat_sort'] . '</td></tr>';
    $info .= '<tr><td>' . $BL['be_admin_struct_template'] . ':</td><td>';
    if (empty($struct[$key]['template_trash'])) {
        $info .= $struct[$key]['template_name'];
        if ($struct[$key]['template_default']) {
            $info .= ' (' . $BL['be_admin_tmpl_default'] . ')';
        }
    } else {
        $info .= $BL['be_admin_tmpl_default'];
    }
    $info .= '</td></tr>';
    $info .= '<tr><td>' . $BL['be_onepage_id'] . ':</td><td>' . ($struct[$key]['acat_onepage'] ? $BL['be_yes'] : $BL['be_no']) . '</td></tr></table>';

    $a .= '<i class="far fa-folder';
    if ($struct[$key]['acat_regonly']) {
        $a .= '-open';
    }
    $a .= ' fa-fw" aria-hidden="true" data-bs-toggle="tooltip" data-html="true" title="' . html($info) . '"></i>';
    $a .= '</td>';
    $a .= '<td width="95%"><strong>';
    if ($js_aktion == 5) {
        $a .= $an;
    } elseif ($js_aktion == 16) {
        $a .= '<a href="#" onclick="' . str_replace('%s', 'id=' . $struct[$key]['acat_id'], $js) . '" title="">' . $an . '</a>';
    } else {
        $a .= '<a href="#" class="structarticle" data-aid="' . ($js_aktion == 6 ? 'id=' : '') . $struct[$key]['acat_id'] . '" data-idtype="category" title="">' . $an;
        $a .= '<span class="ms-3">' . $struct[$key]['acat_lang'] . '</span></a>';
    }
    $a .= '</strong></td></tr></table></td></tr>';
    echo $a;

    if (!empty($_SESSION['structure'][$struct[$key]['acat_id']])) {

        if (!$listmode) {
            struct_articlelist($struct[$key]['acat_id'], $counter, $struct[$key]['acat_order'], $js, $js_aktion);
        }
        struct_list($struct[$key]['acat_id'], $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode, $counter, $js, $js_aktion);

    }
}

function get_root_childcount($id) {
    // get amount of active child levels
    $id = intval($id);

    $p1_count = _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=' . $id, 'COUNT');
    $p2_count = _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_article WHERE article_deleted=0 AND article_cid=' . $id, 'COUNT');

    return $p1_count + $p2_count;
}

function get_article_content_count($id) {
    return _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_aid=' . intval($id), 'COUNT');
}

function struct_articlelist($struct_id, $counter, $article_order, $js, $js_aktion) {

    global $BL;

    $article = array();  // empty article array
    $sort_array = [];  // empty array to store all sort values for the category
    $article_order = intval($article_order);
    $max_article_count = 0;
    $ao = get_order_sort($article_order);
    $count_article = 0;

    $sql = 'SELECT *, ';
    $sql .= "DATE_FORMAT(article_tstamp, '%Y-%m-%d %H:%i:%s') AS article_date "; //, article_deleted
    $sql .= 'FROM ' . DB_PREPEND . 'phpwcms_article ';
    $sql .= "WHERE article_cid='" . $struct_id . "' AND article_deleted=0 ORDER BY " . $ao[2];
    $result = _dbQuery($sql);
    if (isset($result[0]['article_date'])) {

        // 1st get max count to know the last index ($max_article_count - 1)
        $max_article_count = count($result);

        // take all entryies and build new array with it
        foreach ($result as $row) {
            $article[$count_article] = $row;
            if ($row['article_sort'] > 0) {
                $sort_array[$count_article] = $row['article_sort'];
            }
            // count up for article array index
            $count_article++;
        }
    }

    // now check if all sort values are unique
    // if not do a re-sort for all articles

    if ($max_article_count > count(array_unique($sort_array))) {
        $article = getArticleReSorted($struct_id, $article_order);
    }

    /*
     * now we know ALL articles and can run array index +/-
     * to set correct sorting UP and DOWN based on article
     * listing -> so the correct sort value is used
     */
    foreach ($article as $akey => $avalue) {

        $at = html($avalue['article_title']);
        $acontent_count = get_article_content_count($avalue['article_id']);
        $a = '<tr class="struct">';
        $a .= '<td width="100%">';
        $a .= '<table class="table-borderless"><tr>';
        $a .= '<td class="text-nowrap">';
        $a .= '<i class="fa fa-caret-' . ($acontent_count ? (!empty($_SESSION['structure']['article'][$avalue['article_id']]) ? 'down' : 'right') : 'right');
        $a .= ' fa-fw alist-' . $counter . '" aria-hidden="true"></i>';

        $info = '<table class="text-start">';
        $info .= '<tr><td>' . $BL['be_func_struct_articleID'] . ':</td><td><b>' . $avalue['article_id'] . '</b></td></tr>';
        if (!empty($avalue['article_alias'])) {
            $info .= '<tr><td>ALIAS:</td><td><b>' . $avalue['article_alias'] . '</b></td></tr>';
        }
        if (!empty($avalue['article_begin'])) {
            $info .= '<tr><td>' . $BL['be_article_cnt_start'] . ':</td><td><b>';
            $info .= phpwcms_strtotime($avalue['article_begin'], $BL['be_longdatetime'], '&nbsp;');
            $info .= '</b></td></tr>';
        }
        if (!empty($avalue['article_end'])) {
            $info .= '<tr><td>' . $BL['be_article_cnt_end'] . ':</td><td><b>';
            $info .= phpwcms_strtotime($avalue['article_end'], $BL['be_longdatetime'], '&nbsp;');
            $info .= '</b></td></tr>';
        }
        $info .= '<tr><td>' . $BL['be_cnt_sortvalue'] . ':</td><td>' . $avalue['article_sort'] . '</td></tr>';
        if (isset($avalue['article_end'])) {
            $info .= '<tr><td>' . $BL['be_priorize'] . ':</td><td>' . $avalue['article_priorize'] . '</td></tr>';
        }
        $info .= '</table>';

        $a .= '<i class="far fa-file fa-fw" aria-hidden="true" data-html="true" data-bs-toggle="tooltip" title="' . html($info) . '"></i> ';

        if ($js_aktion == 5) {
            $a .= $at;
        } elseif ($js_aktion == 16) {
            $a .= '<a href="#" onclick="' . str_replace('%s', 'aid=' . $avalue['article_id'], $js) . '" title="">' . $at . '</a>';
        } else {
            $a .= '<a href="#" class="structarticle" data-aid="' . ($js_aktion == 6 ? 'aid=' : '') . $avalue['article_id'] . '" data-idtype="article" title="">';
            $a .= $at;
            $a .= '<span class="ms-3">' . $avalue['article_lang'] . '</span></a>';
        }
        $a .= '</td></tr></table></td></tr>';
        echo $a;

        if ($js_aktion == 5) {
            struct_articlecontentlist($article, $akey, $counter);
        }
    }
}

function struct_articlecontentlist($article, $akey, $counter) {

    $a = '';

    $sql = 'SELECT * FROM ' . DB_PREPEND . 'phpwcms_articlecontent ';
    $sql .= 'WHERE acontent_aid=' . $article[$akey]['article_id'] . ' AND acontent_trash=0 ';
    $sql .= 'ORDER BY acontent_block, acontent_sorting, acontent_id';

    $result = _dbQuery($sql);
    if (isset($result[0]['acontent_aid'])) {

        foreach ($result as $article_content) {

            $info = '<table class="text-start">';
            $info .= '<tr><td>ID:</td><td>' . $article_content['acontent_id'] . '</td></tr>';
            if ($article_content['acontent_title']) {
                $info .= '<tr><td>' . $GLOBALS['BL']['be_article_cnt_ctitle'] . ':</td><td>' . $article_content['acontent_title'] . '</td></tr>';
            }
            if ($article_content['acontent_title']) {
                $info .= '<tr><td>' . $GLOBALS['BL']['be_article_asubtitle'] . ':</td><td>' . $article_content['acontent_subtitle'] . '</td></tr>';
            }
            if ($article_content['acontent_comment']) {
                $info .= '<tr><td colspan="2">' . nl2br($article_content['acontent_comment']) . '</td></tr>';
            }
            $info .= '</table>';

            $a .= '<tr class="structarticlecontent" data-aid="' . $article_content['acontent_id'] . '" data-idtype="acontent">';
            $a .= '<td data-bs-toggle="tooltip" data-html="true" title="' . html($info) . '"><i class="far fa-list-alt fa-fw aclist-' . $counter . '" aria-hidden="true"></i></td>';
            $a .= '<td width="90%" class="text-secondary">';
            $a .= '[ID:' . $article_content['acontent_id'] . '] ';
            $a .= html($article_content['acontent_title']) . ' – ';
            $a .= html($GLOBALS['wcs_content_type'][$article_content['acontent_type']]);
            if ($article_content['acontent_type'] == 30) {
                $a .= ': ' . html($GLOBALS['BL']['modules'][$article_content['acontent_module']]['listing_title']);
            }
            $a .= '</td>';
            $a .= '<td class="text-secondary text-end text-nowrap">{' . html($article_content['acontent_block']) . '}</td>';
            $a .= '</tr>';
        }

        if ($a) {
            echo '<tr><td colspan="2" class="p-0">';
            echo '<table class="table-borderless w-100">';
            echo $a;
            echo '</table></td></tr>';
        }
    }
}
