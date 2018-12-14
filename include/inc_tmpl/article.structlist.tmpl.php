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


//19-11-2004 Fernando Batista -> Copy article, Copy strutures http://fernandobatista.net
//31-03-2005 Fernando Batista -> Copy/Cut Article Content http://fernandobatista.net

?>
<h1 class="text-center text-sm-left"><?php echo $BL['be_subnav_article_center'] ?></h1>
<div class="card">
<div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_article_title'] ?></h2></div>
<div class="table-responsive" style="overflow-x: inherit">
<table class="table table-sm mb-0" width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
<?php

$listmode = 0;
$cut_id = (isset($_GET["cut"])) ? intval($_GET["cut"]) : 0;
$cut_article = (isset($_GET["acut"])) ? intval($_GET["acut"]) : 0;

$copy_id = (isset($_GET["cop"])) ? intval($_GET["cop"]) : 0;
$copy_article = (isset($_GET["acopy"])) ? intval($_GET["acopy"]) : 0;
$cut_article_content	= empty($_GET["accut"]) ? 0 : intval($_GET["accut"]);
$copy_article_content	= empty($_GET["accopy"]) ? 0 : intval($_GET["accopy"]);

if(isset($_GET["open"])) {
    list($open_id, $open_value) = explode(":", $_GET["open"]);
    $open_id = intval($open_id);
    if(empty($open_value)) {
        unset($_SESSION["structure"][$open_id]);
    }
    $_SESSION["structure"][$open_id] = $open_value;
     _dbQuery("UPDATE ".DB_PREPEND."cmsgo_user SET usr_var_structure="._dbEscape(serialize($_SESSION["structure"]))." WHERE usr_id=".aporeplace($_SESSION["wcs_user_id"]), 'UPDATE');
}

//31-03-2005 Fernando Batista  start---------------------------------------------------------------------------
$cut_article_content = (isset($_GET["accut"])) ? intval($_GET["accut"]) : 0;
$copy_article_content = (isset($_GET["accopy"])) ? intval($_GET["accopy"]) : 0;
if(isset($_GET["opena"])) {
    list($open_id, $open_value) = explode(":", $_GET["opena"]);
    $open_id = intval($open_id);
    if(empty($open_value)) {
        unset($_SESSION["structure"]["article"][$open_id]);
    } else {
        $_SESSION["structure"]["article"][$open_id] = $open_value;
    }
    _dbQuery("UPDATE ".DB_PREPEND."cmsgo_user SET usr_var_structure="._dbEscape(serialize($_SESSION["structure"]))." WHERE usr_id=".aporeplace($_SESSION["wcs_user_id"]), 'UPDATE');
}
//31-03-2005 Fernando Batista  end-------------------

$child_count = get_root_childcount(0);
//$an = $BL['be_admin_struct_index'];
$an = $indexpage['acat_name'];

$a  = "<tr bgcolor=\"#e8e8e8\" onMouseOver=\"this.bgColor='#D2EED9';\" onMouseOut=\"this.bgColor='#e8e8e8';\">\n";
$a .= '<td>';
$a .= "<table class=\"table-no-border\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
$a .= '<td nowrap="nowrap">';
$a .= ($child_count) ? "<a href=\"cmsgo.php?do=articles&amp;open=0:".(($_SESSION["structure"][0])?0:1)."\">" : "";

$a .= '<i class="fa fa-caret-'.(($child_count) ? (($_SESSION["structure"][0]==0) ? "right" : "down") : "right");
$a .= ' fa-fw" aria-hidden="true"></i>'.(($child_count) ? "</a>" : "");

$info  = 'ID: <b>0</b><br />';
$info .= 'ALIAS: '.html($indexpage["acat_alias"]);

$a .= '<i class="fa fa-folder fa-fw" aria-hidden="true" data-toggle="tooltip" data-html="true" title="'.$info.'"></i>';

$a .= "</td>\n";
$a .= '<td width="97%"><strong>'.$an."</strong></td>\n</tr>\n</table></td>\n";

echo $a;
echo '<td nowrap="nowrap" class="text-right">';

$struct[0]["acat_id"]       = 0;
$struct[0]["acat_aktiv"]    = 1;
$struct[0]["acat_struct"]   = 0;

echo listmode_edits($listmode, $struct, 0, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, 0, 0, 0, 0);

echo "</td>\n</tr>\n";

if($_SESSION["structure"][0]) {
    struct_articlelist(0, 0, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $indexpage['acat_order']);//$template_default["article_order"]
    struct_list(0, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode);
}
?>
</table>
</div>
</div>
