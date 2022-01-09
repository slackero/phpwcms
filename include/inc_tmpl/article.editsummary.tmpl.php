<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Be more modern here — we start switch to jQuery and overwrite non-used MooTools with jQuery call
initJsAutocompleter();

unset($_SESSION['filebrowser_image_target']);

$template_default['article']['image_default_width']      = isset($template_default['article']['image_default_width']) ? $template_default['article']['image_default_width'] : '';
$template_default['article']['image_default_height']     = isset($template_default['article']['image_default_height']) ? $template_default['article']['image_default_height'] : '';
$template_default['article']['imagelist_default_width']  = isset($template_default['article']['imagelist_default_width']) ? $template_default['article']['imagelist_default_width'] : '';
$template_default['article']['imagelist_default_height'] = isset($template_default['article']['imagelist_default_height']) ? $template_default['article']['imagelist_default_height'] : '';

?>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>" method="post" name="article" id="article">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
            <tr><td colspan="2" class="title"><?php echo $BL['be_article_estitle'] ?></td></tr>
            <tr>
                <td width="88"><img src="img/leer.gif" alt="" width="88" height="4" /></td>
                <td width="450"><img src="img/leer.gif" alt="" width="450" height="1" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

            <tr>
                <td align="right" class="chatlist"><?php echo $BL['be_article_cat'] ?>:&nbsp;</td>
                <td><select name="article_cid" id="article_cid" class="width325">
                <?php
                    //keine definierte Kategorie = allgemeine Artikelkategorie
                    echo '<option value="0"'.((!$article["article_catid"])?' selected="selected"':'').">".$BL['be_admin_struct_index']."</option>\n";
                    struct_select_menu(0, 0, $article["article_catid"]);
                ?>
                </select></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

            <tr>
                <td align="right" class="chatlist"><a href="#" id="cat-as-articletitle"><?php echo $BL['be_article_atitle'] ?></a>:&nbsp;</td>
                <td style="padding:2px 0 3px 0;"><table border="0" cellpadding="0" cellspacing="0" summary="">
                 <tr>
                    <td><input name="article_title" type="text" class="bold width325" id="article_title" value="<?php echo html($article["article_title"]) ?>" size="40" maxlength="5000" /></td>
                    <td>&nbsp;&nbsp;</td>
                    <td><input name="article_notitle" id="article_notitle" type="checkbox" value="1" <?php is_checked($article["article_notitle"],1) ?> /></td>
                    <td class="v10"><label for="article_notitle"><?php echo $BL['be_admin_struct_hide1'] ?></label></td>
                 </tr>
              </table></td>
            </tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
              <td><input name="article_subtitle" type="text" class="bold width440" id="article_subtitle" value="<?php echo html($article["article_subtitle"]) ?>" size="40" maxlength="5000" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>
                    <tr>
                <td align="right" bgcolor="#FFFFFF" class="chatlist">&nbsp;<br /><?php echo $BL['be_article_abegin'] ?>:&nbsp;</td>
                <td>
                  <table border="0" cellpadding="2" cellspacing="0" summary="">
                    <tr bgcolor="#E7E8EB">
                        <td class="chatlist">&nbsp;<br />
                            <input name="set_begin" type="checkbox" id="set_begin" value="1"<?php is_checked(1, $set_begin) ?> onclick="document.article.article_begin.value = this.checked ? '<?php echo $article["article_begin"] ?>' : '';" />
                        </td>
                        <td class="chatlist tdbottom3 nowrap" nowrap="nowrap">YYYY-MM-DD HH:MM:SS<br />
                            <input name="article_begin" type="text" id="article_begin" class="width150" value="<?php echo $article["article_begin"]; ?>" />
                        </td>
                        <td class="chatlist tdbottom3">&nbsp;<br />
                            <script type="text/javascript">
                                var currentDateBegin = 0,
                                    currentDateEnd = 0;

                                function aBegin(day, month, year) {

                                    month = subrstr('00' + month, 2);
                                    day = subrstr('00' + day, 2);

                                    currentDateBegin = parseInt(year + month + day, 10);

                                    if(currentDateEnd > 0 && currentDateBegin > currentDateEnd) {
                                        document.article.article_end.value = '';
                                        document.article.set_end.checked = false;
                                    }

                                    document.article.article_begin.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + day, 2) + ' 00:00:00';
    document.article.set_begin.checked = true;
}

calBegin = new dynCalendar('calBegin', 'aBegin', 'img/dynCal/');
calBegin.setMonthCombo(false);
calBegin.setYearCombo(false);

                            </script>&nbsp;
                        </td>
                        <td align="right" bgcolor="#FFFFFF" class="chatlist">&nbsp;<br />&nbsp;&nbsp;<?php echo $BL['be_article_aend'] ?>:</td>
                        <td class="chatlist">&nbsp;<br />
                            <input name="set_end" type="checkbox" id="set_end" value="1"<?php is_checked(1, $set_end) ?> onclick="document.article.article_end.value = this.checked ? '<?php echo $article["article_end"] ?>' : '';" />
                        </td>
                        <td class="chatlist tdbottom3 nowrap" nowrap="nowrap">YYYY-MM-DD HH:MM:SS<br />
                            <input name="article_end" type="text" id="article_end" class="width150" value="<?php echo $article["article_end"] ?>" />
                        </td>
                        <td class="chatlist tdbottom3">&nbsp;<br />
                            <script type="text/javascript">

                                function aEnd(day, month, year) {

                                    month = subrstr('00' + month, 2);
                                    day = subrstr('00' + day, 2);

                                    currentDateEnd = parseInt(year + month + day, 10);

                                    if(currentDateBegin > 0 && currentDateBegin > currentDateEnd) {
                                        document.article.article_begin.value = '';
                                        document.article.set_begin.checked = false;
                                    }

                                    document.article.article_end.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + day, 2) + ' 23:59:59';
    document.article.set_end.checked = true;

}

calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
calEnd.setMonthCombo(false);
calEnd.setYearCombo(false);

                            </script>&nbsp;
                        </td>
                    </tr>
                </table></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

            <tr>
                <td align="right" class="chatlist"><?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
                <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                 <tr>
                    <td><input name="article_sort" type="text" id="article_sort" value="<?php echo empty($article["article_sort"]) ? 0 : intval($article["article_sort"]) ?>" class="v11 width75" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" /></td>
                    <td align="right" class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_priorize'] ?>:&nbsp;</td>
                    <td><select name="article_priorize" id="article_priorize" class="v11">
<?php

    for($x=30; $x>=-30; $x--) {

        echo '  <option value="'.$x.'"';
        is_selected($x, $article["article_priorize"]);
        echo '>'. ( $x==0 ? $BL['be_cnt_default'] : $x ) .'</option>';

    }

?>
                    </select></td>
                 </tr>
              </table></td>
            </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_alias_articleID'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
         <tr>
            <td><input name="article_aliasid" type="text" class="width75" id="article_aliasid" value="<?php echo $article["article_aliasid"] ? $article["article_aliasid"] : ''; ?>" size="11" maxlength="11" /></td>
            <td>&nbsp;&nbsp;</td>
            <td><input name="article_headerdata" id="article_headerdata" type="checkbox" value="1" <?php is_checked($article["article_headerdata"],1) ?> /></td>
            <td class="v10"><label for="article_headerdata">&nbsp;<?php echo $BL['be_alias_useAll'] ?></label></td>
         </tr>
      </table></td>
    </tr>


<?php   if(count($phpwcms['allowed_lang']) > 1):    ?>

        <tr><td><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

        <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
            <td>

            <div style="margin:0;border:1px solid #D9DEE3;padding:5px;float:left;" class="lang-select">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>

                        <td><label><input type="radio" name="article_lang" class="lang-default" value=""<?php is_checked('', $article['article_lang']); ?> />
                                <img src="img/famfamfam/lang/<?php echo $phpwcms['default_lang'] ?>.png" title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" /><?php echo ' ('.$BL['be_admin_tmpl_default'].')' ?>
                                &nbsp;
                            </label>
                        </td>


<?php       foreach($phpwcms['allowed_lang'] as $key => $lang):

                    $lang = strtolower($lang);

                    if($lang == $phpwcms['default_lang']) {
                        continue;
                    }

?>
                        <td><label><input type="radio" name="article_lang" value="<?php echo $lang ?>"<?php is_checked($lang, $article['article_lang']) ?> class="lang-opt" />
                                <img src="img/famfamfam/lang/<?php echo $lang ?>.png" title="<?php echo get_language_name($lang) ?>" />
                                &nbsp;
                            </label>
                        </td>

<?php       endforeach; ?>

                    </tr>
                </table>

                <div style="margin:5px 0 0 0;border-top:1px solid #D9DEE3;padding-top:5px;<?php if($article['article_lang'] == ''): ?>display:none;<?php endif; ?>" id="lang-id-select">
                    <label><input type="radio" name="article_lang_type" value="category"<?php is_checked('category', $article['article_lang_type']); ?> /> <?php echo $BL['be_article_cat'] ?> ID</label>
                    &nbsp;
                    <label><input type="radio" name="article_lang_type" value="article"<?php is_checked('article', $article['article_lang_type']); ?> /><?php echo $BL['be_cnt_articles'] ?> ID</label>
                    &nbsp;
                    <img src="img/famfamfam/lang/<?php echo $phpwcms['default_lang'] ?>.png" title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" />&nbsp;
                    <input name="article_lang_id" type="text" class="bold width75" value="<?php echo $article['article_lang_id'] ? $article['article_lang_id'] : ''; ?>" size="11" maxlength="11" />
                </div>

            </div></td>
    </tr>

<?php   endif;

    $struct_alias = get_struct_alias($article["article_catid"]);
    $struct_parental = get_struct_alias($article["article_catid"], true);

?>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
    <tr>
        <td>&nbsp;</td>
        <td class="chatlist">
            <a href="#" onclick="return set_article_alias();"><?php echo $BL['be_article_urlalias'] ?></a>,
            +<a href="#" id="parent_alias" title="<?php echo $struct_parental; ?>" data-alias="<?php echo $struct_parental; ?>"><?php echo $BL['be_parental_alias'] ?></a>,
            +<a href="#" id="struct_alias" title="<?php echo $struct_alias; ?>" data-alias="<?php echo $struct_alias; ?>"><?php echo $BL['be_admin_struct_title'] ?></a>
        </td>
    </tr>
    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_article_urlalias'] ?>:&nbsp;</td>
        <td><input name="article_alias" type="text" class="bold width440" id="article_alias" value="<?php echo html($article["article_alias"]) ?>" size="40" maxlength="1000"<?php
            if(empty($phpwcms['allow_empty_alias'])): ?> onfocus="set_article_alias(true);"<?php endif; ?> onchange="this.value=create_alias(this.value);" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_admin_page_pagetitle'] ?>:&nbsp;</td>
        <td><input name="article_pagetitle" type="text" id="article_pagetitle" class="width440" value="<?php echo html($article['article_pagetitle']) ?>" size="40" maxlength="2000" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr>
      <td align="right" class="chatlist"><?php echo $BL['article_menu_title'] ?>:&nbsp;</td>
      <td><input name="article_menutitle" type="text" id="article_menutitle" class="width440" value="<?php echo html($article["article_menutitle"]) ?>" size="40" /></td>
    </tr>


    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_article_aredirect'] ?>:&nbsp;</td>
      <td><input name="article_redirect" type="text" id="article_redirect" class="width440" value="<?php echo html($article["article_redirect"]) ?>" size="40" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_canonical'] ?>:&nbsp;</td>
      <td><input name="article_canonical" type="text" id="article_canonical" class="width440" value="<?php echo html($article["article_canonical"]) ?>" size="40" maxlength="2000" /></td>
    </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
                <td align="right" class="chatlist tdtop3"><?php echo $BL['be_article_akeywords'] ?>:&nbsp;</td>
                <td><input type="text" id="article_keyword_autosuggest" /><input type="hidden" name="article_keyword" id="article_keyword" value="<?php echo html($article["article_keyword"]) ?>" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
                <td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
                <td><textarea name="article_description" rows="4" class="width440 autosize" id="article_description"><?php echo html($article["article_description"]) ?></textarea></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
                <td align="right" class="chatlist" valign="top"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
                <td valign="top"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                    <td width="215" class="chatlist"><?php echo $BL['be_article_forlist'] ?>:&nbsp;</td>
                    <td width="10"><img src="img/leer.gif" alt="" width="10" height="1" /></td>
                    <td width="215" class="chatlist"><?php echo $BL['be_article_forfull'] ?>:&nbsp;</td></tr>
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
                <tr>
                  <td><select name="article_tmpllist" id="article_tmpllist" class="width215">
<?php
// templates for article listing
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list');
if($article['image']['tmpllist'] == 'default') {
    $vals= ' selected="selected"';
} else {
    $vals = '';
}
echo '<option value="default"'.$vals.'>'.$BL['be_cnt_default']."</option>\n";
if(count($tmpllist)) {
    foreach($tmpllist as $val) {
        $vals = '';
        if($val == $article['image']['tmpllist']) $vals= ' selected="selected"';
        $val = htmlspecialchars($val);
        echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
    }
}

?>
                  </select></td>
                  <td>&nbsp;</td>
                  <td><select name="article_tmplfull" id="article_tmplfull" class="width215">
<?php
// templates for full article
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article');
if($article['image']['tmplfull'] == 'default') $vals= ' selected="selected"';
echo '<option value="default"'.$vals.'>'.$BL['be_cnt_default']."</option>\n";
if(count($tmpllist)) {
    foreach($tmpllist as $val) {
        $vals = '';
        if($val == $article['image']['tmplfull']) $vals= ' selected="selected"';
        $val = htmlspecialchars($val);
        echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
    }
}

?>
                  </select></td>
                  </tr>

    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
    <tr>
        <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input name="article_listmaxwords" type="text" id="article_listmaxwords" style="width: 25px;" value="<?php echo empty($article['image']['list_maxwords']) ? '' : intval($article['image']['list_maxwords']) ?>" size="10" maxlength="6" /></td>
                <td class="v10">&nbsp;<?php echo $BL['be_cnt_results_wordlimit'] ?></td>
            </tr>
        </table></td>
        <td>&nbsp;</td>
        <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td><input name="article_hidesummary" type="checkbox" id="article_hidesummary" value="1"<?php is_checked(1, $article["article_hidesummary"]); ?> /></td>
            <td class="v10"><label for="article_hidesummary">&nbsp;<?php echo $BL['be_article_nosummary'] ?></label></td>
          </tr>
        </table></td>
    </tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
    </tr>
    <tr>
      <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input name="article_morelink" type="checkbox" id="article_morelink" value="1"<?php is_checked(1, $article['article_morelink']); ?> /></td>
                <td class="v10"><label for="article_morelink">&nbsp;<?php echo $BL['be_article_morelink'] ?></label>&nbsp;&nbsp;&nbsp;</td>
                <td><input name="article_noteaser" type="checkbox" id="article_noteaser" value="1"<?php is_checked(1, $article['article_noteaser']); ?> /></td>
                <td class="v10"><label for="article_noteaser">&nbsp;<?php echo $BL['be_article_noteaser'] ?></label></td>
            </tr>
            </table></td>
      <td>&nbsp;</td>
      <td><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td><input name="article_paginate" type="checkbox" id="article_paginate" value="1"<?php is_checked(1, $article["article_paginate"]); ?> /></td>
            <td class="v10"><label for="article_paginate">&nbsp;<?php echo $BL['be_cnt_pagination'] ?></label></td>
          </tr>
        </table></td>
    </tr>

                </table></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
            <tr>
                <td align="right" class="chatlist"><?php echo $BL['be_cnt_css_class']; ?>:&nbsp;</td>
                <td><input name="article_meta_class" type="text" id="article_meta_class" class="width440" value="<?php echo html($article["article_meta"]['class']) ?>" size="40" maxlength="250" /></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
            </tr>

            <tr>

              <td colspan="2"><?php

$wysiwyg_editor = array(
    'value' => $article["article_summary"],
    'field' => 'article_summary',
    'height' => '450px',
    'width' => '100%',
    'rows' => '15',
    'editor' => $_SESSION["WYSIWYG_EDITOR"],
    'lang' => 'en'
);
include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?></td>
    </tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
<tr bgcolor="#F3F6F9">
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
    <td class="chatlist"><?php echo $BL['be_article_forfull'] ?></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<tr bgcolor="#F3F6F9">
    <td align="right" class="chatlist">&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo html($article['image']['name']) ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
                                                                                                                                                          <!-- browser_image.php //-->
            <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=summary');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
            <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="document.article.cimage_name.value='';document.article.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
              <input name="cimage_id" type="hidden" value="<?php echo $article['image']['id'] ?>" /></td>
        </tr>
    </table></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr bgcolor="#F3F6F9">
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['width']) ? $template_default['article']['image_default_width'] : $article['image']['width']; ?>" /></td>
                  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
                  <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['height']) ? $template_default['article']['image_default_height'] : $article['image']['height']; ?>" /></td>
                  <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

                  <td>&nbsp;</td>
                  <td><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $article['image']['zoom']); ?> /></td>
                  <td class="v10"><label for="cimage_zoom">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?></label>&nbsp;</td>

                  <td>&nbsp;</td>
                  <td><input name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, empty($article['image']['lightbox']) ? 0 : 1); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
                  <td class="v10">&nbsp;<label for="cimage_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>&nbsp;</td>

                </tr>
              </table></td>
    </tr>
            <tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
            </tr>
            <tr bgcolor="#F3F6F9">
              <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td valign="top">
                        <textarea name="cimage_caption" cols="30" rows="3" class="width300 autosize" id="cimage_caption"><?php echo html($article['image']['caption']) ?></textarea>
                        <div class="caption width300">
                            <?php echo $BL['be_cnt_caption']; ?>
                            |
                            <?php echo $BL['be_caption_alt']; ?>
                            |
                            <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
                            |
                            <?php echo $BL['be_caption_title']; ?>
                            |
                            <?php echo $BL['be_copyright']; ?>
                        </div>
                        <div class="checkbox tdtop3">
                            <label>
                                <input type="checkbox" name="cimage_caption_suppress" value="1" <?php is_checked(1, empty($article['image']['caption_suppress']) ? 0 : 1); ?> />
                                <?php echo $BL['be_suppress_render_caption']; ?>
                            </label>
                        </div>
                    </td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="15" height="1" /></td>
                    <td valign="top"><?php

                    $_SESSION['image_browser_article'] = 1;

                    $thumb_image = false;
                    if(!empty($article["image"]["hash"])) {
                        $thumb_image = get_cached_image(array(
                            "target_ext" => $article['image']['ext'],
                            "image_name" => $article['image']['hash'] . '.' . $article['image']['ext'],
                            "thumb_name" => md5($article['image']['hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                        ));
                    }
                    echo $thumb_image ? '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" />' : '&nbsp;';

                    ?></td>
                  </tr>
              </table></td>
            </tr>

            <tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td></tr>
            <tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
<tr bgcolor="#F3F6F9">
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
    <td class="chatlist"><?php echo $BL['be_article_forlist'] ?></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>
<tr bgcolor="#F3F6F9">
    <td align="right" class="chatlist">&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
<?php

// set default list values
if(!isset($article['image']['list_usesummary'])) {
    $article['image']['list_usesummary'] = 0;
    $article['image']['list_name'] = '';
    $article['image']['list_id'] = 0;
    $article['image']['list_width'] = '';
    $article['image']['list_height'] = '';
    $article['image']['list_zoom'] = 0;
    $article['image']['list_caption'] = '';
}

?>
        <td><input name="cimage_usesummary" type="checkbox" id="cimage_usesummary" value="1" <?php is_checked(1, $article['image']['list_usesummary']); ?> /></td>
        <td class="v10"><label for="cimage_usesummary">&nbsp;<?php echo $BL['be_cnt_same_as_summary'] ?></label>&nbsp;</td>
        </tr>
    </table></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>
<tr bgcolor="#F3F6F9">
    <td align="right" class="chatlist">&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><input name="cimage_list_name" type="text" id="cimage_list_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo html($article['image']['list_name']) ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
            <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=list');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
            <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="document.article.cimage_list_name.value='';document.article.cimage_list_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
              <input name="cimage_list_id" type="hidden" value="<?php echo $article['image']['list_id'] ?>" /></td>
        </tr>
    </table></td>
</tr>

<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>

<tr bgcolor="#F3F6F9">
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
    <td><input name="cimage_list_width" type="text" class="f11b" id="cimage_list_width" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['list_width']) ? $template_default['article']['imagelist_default_width'] : $article['image']['list_width']; ?>" /></td>
    <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
    <td><input name="cimage_list_height" type="text" class="f11b" id="cimage_list_height" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['list_height']) ? $template_default['article']['imagelist_default_height'] : $article['image']['list_height']; ?>" /></td>
    <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="cimage_list_zoom" type="checkbox" id="cimage_list_zoom" value="1" <?php is_checked(1, $article['image']['list_zoom']); ?> /></td>
    <td class="v10"><label for="cimage_list_zoom">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?></label>&nbsp;</td>

    <td>&nbsp;</td>
    <td><input name="cimage_list_lightbox" type="checkbox" id="cimage_list_lightbox" value="1" <?php is_checked(1, empty($article['image']['list_lightbox']) ? 0 : 1); ?> onchange="if(this.checked){getObjectById('cimage_list_zoom').checked=true;}" /></td>
    <td class="v10"><label for="cimage_list_lightbox">&nbsp;<?php echo $BL['be_cnt_lightbox'] ?></label>&nbsp;</td>

    </tr>
    </table></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
</tr>
<tr bgcolor="#F3F6F9">
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
    <td valign="top">
        <textarea name="cimage_list_caption" cols="30" rows="3" class="width300 autosize" id="cimage_list_caption"><?php echo html($article['image']['list_caption']) ?></textarea>
            <div class="caption width300">
            <?php echo $BL['be_cnt_caption']; ?>
            |
            <?php echo $BL['be_caption_alt']; ?>
            |
            <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
            |
            <?php echo $BL['be_caption_title']; ?>
            |
            <?php echo $BL['be_copyright']; ?>
            </div>
            <div class="checkbox tdtop3">
                <label>
                    <input type="checkbox" name="cimage_list_caption_suppress" value="1" <?php is_checked(1, empty($article['image']['list_caption_suppress']) ? 0 : 1); ?> />
                    <?php echo $BL['be_suppress_render_caption']; ?>
                </label>
            </div>
    </td>
    <td valign="top"><img src="img/leer.gif" alt="" width="15" height="1" /></td>
    <td valign="top"><?php

    $_SESSION['image_browser_article'] = 1;

    $thumb_image = false;
    if(!empty($article["image"]["list_hash"])) {
        $thumb_image = get_cached_image(array(
            "target_ext" => $article['image']['list_ext'],
            "image_name" => $article['image']['list_hash'] . '.' . $article['image']['list_ext'],
            "thumb_name" => md5($article['image']['list_hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));
    }
    echo $thumb_image ? '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" />' : '&nbsp;';

    ?></td>
    </tr>
    </table></td>
</tr>


            <tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
            </tr>
            <?php
            if($_SESSION["wcs_user_admin"]) {

            ?>
            <tr>
                <td align="right" class="chatlist"><?php echo $BL['be_article_articleowner'] ?>:&nbsp;</td>
                <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                    <td><select name="article_uid" id="article_uid" class="width300">
<?php
                $u_sql = "SELECT usr_id, usr_name, usr_login, usr_admin FROM ".DB_PREPEND."phpwcms_user WHERE usr_aktiv=1 ORDER BY usr_admin DESC, usr_name";
                $u_result = _dbQuery($u_sql);
                if(isset($u_result[0]['usr_id'])) {
                    foreach($u_result as $u_row) {
                        echo '<option value="'.$u_row['usr_id'].'"';
                        if($u_row['usr_id'] == $article["article_uid"]) {
                            echo ' selected="selected"';
                        }
                        if(intval($u_row['usr_admin'])) {
                            echo ' style="background-color: #FFC299;"';
                        }
                        echo '>'.html(($u_row['usr_name']) ? $u_row['usr_name'] : $u_row['usr_login']).'</option>';
                    }

                }
?>
                    </select></td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td bgcolor="#FFC299"><img src="img/leer.gif" alt="" width="15" height="10" /></td>
                <td class="chatlist">&nbsp;<?php echo $BL['be_article_adminuser'] ?></td>
                </tr></table></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
            </tr>
            <?php
            }
            ?>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_article_username'] ?>:&nbsp;</td>
              <td><input name="article_username" type="text" id="article_username" class="f11" style="width: 300px" value="<?php echo html($article["article_username"]) ?>" size="40" maxlength="200" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
            </tr>


            <tr>
                <td align="right" class="chatlist inactive"><?php echo $BL['be_cache'] ?>:&nbsp;</td>
                <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="" class="inactive">
                    <tr>
                        <td><input name="article_cacheoff" type="checkbox" id="article_cacheoff" value="1" <?php if($article["article_timeout"] === '0') echo "checked"; ?> /></td>
                        <td><label for="article_cacheoff">&nbsp;<?php echo $BL['be_off'] ?></label>&nbsp;&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><select name="article_timeout" style="margin:1px;" onchange="document.article.article_cacheoff.checked=false;">
<?php
echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
echo '<option value="60"'.is_selected($article["article_timeout"], '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
echo '<option value="300"'.is_selected($article["article_timeout"], '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="900"'.is_selected($article["article_timeout"], '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="1800"'.is_selected($article["article_timeout"], '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="3600"'.is_selected($article["article_timeout"], '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
echo '<option value="14400"'.is_selected($article["article_timeout"], '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
echo '<option value="43200"'.is_selected($article["article_timeout"], '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
echo '<option value="86400"'.is_selected($article["article_timeout"], '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
echo '<option value="172800"'.is_selected($article["article_timeout"], '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
echo '<option value="604800"'.is_selected($article["article_timeout"], '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
echo '<option value="1209600"'.is_selected($article["article_timeout"], '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
echo '<option value="2592000"'.is_selected($article["article_timeout"], '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";

?>
                        </select></td>
                  <td>&nbsp;<?php echo $BL['be_cache_timeout'] ?>&nbsp;&nbsp;</td>

                    </tr>
                </table></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

            <tr>
                <td align="right" class="chatlist tdtop4"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
                <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="" class="nowrap">

                    <tr>
                        <td style="padding:1px 0;white-space:nowrap;">
                            <label for="article_nositemap"><input name="article_nositemap" type="checkbox" id="article_nositemap" value="1"<?php is_checked(1, $article["article_nositemap"]); ?> />&nbsp;<?php echo  $BL['be_ctype_sitemap'] ?></label>
                            &nbsp;
                            <label for="article_nosearch"><input name="article_nosearch" type="checkbox" id="article_nosearch" value="1" <?php is_checked(1, $article['article_nosearch']); ?> />&nbsp;<?php echo $BL['be_no_search'] ?></label>
                            &nbsp;
                            <label for="article_norss"><input name="article_norss" type="checkbox" id="article_norss" value="1" <?php is_checked(1, $article['article_norss']); ?> />&nbsp;<?php echo $BL['be_no_rss'] ?></label>
                            &nbsp;
<?php
                            // Opengraph fallback when creating a new article
                            if(!isset($_POST['article_title']) && empty($article["article_id"]) && defined('ACAT_OPENGRAPH_STATUS') && ACAT_OPENGRAPH_STATUS === false) {
                                $article['article_opengraph'] = 0;
                            }
?>
                            <label for="article_opengraph"><input name="article_opengraph" type="checkbox" id="article_opengraph" value="1" <?php is_checked(1, $article['article_opengraph']); ?> />&nbsp;<?php echo $BL['be_opengraph_support'] ?></label>
                            &nbsp;
                        </td>
                    </tr>

                    <tr><td style="background-color:#FFFFFF"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

                    <tr>
                        <td style="padding:1px 0;white-space:nowrap;">
                            <label for="article_aktiv"><input name="article_aktiv" type="checkbox" id="article_aktiv" value="1"<?php is_checked(1, $article["article_aktiv"]); ?> />&nbsp;<?php echo $BL['be_admin_struct_visible'] ?></label>
                            &nbsp;
                            <label for="article_archive"><input name="article_archive" type="checkbox" id="article_archive" value="1" <?php is_checked(1, $article['article_archive_status']); ?> />&nbsp;<?php echo $BL['be_show_archived'] ?></label>
                            &nbsp;
                        </td>
                    </tr>

                </table></td>
            </tr>

<?php if(isset($article["article_date"])) { ?>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

            <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_article_eslastedit'] ?>:&nbsp;</td>
            <td><?php
            echo (empty($_POST["article_update"]) || !intval($_POST["article_update"])) ? $article["article_date"] : $BL['be_article_esnoupdate'];
            ?></td>
            </tr>
<?php } ?>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
            </tr>
            <tr>
                <td><input name="article_update" type="hidden" id="article_update" value="1" /></td>
                <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                    <tr>
                    <td><input name="updatesubmit" type="submit" class="button" value="<?php echo $article["article_id"] ? $BL['be_article_cnt_button1'] : $BL['be_article_cnt_button2'] ?>" /></td>
                    <td>&nbsp;</td>
                    <td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" /></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><input name="donotsubmit" type="submit" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="return cancelEdit();" /></td>
                    </tr></table></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
            <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
</table>
</form>
<script type="text/javascript">
$(function(){

    $("#article_keyword_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "cat_name",
        selectedValuesProp: 'cat_name',
        searchObjProps: "cat_name",
        queryParam: 'value',
        extraParams: '&method=json&action=category',
        startText: '',
        preFill: $("#article_keyword").val(),
        neverSubmit: true,
        asHtmlID: 'keyword-autosuggest'
    });

    $('#article').submit(function(){
        $("#article_keyword").val($('#as-values-keyword-autosuggest').val());
    });

    // Handle language switch click
    var langIdSelect = $('#lang-id-select');

    $('input.lang-opt').change(function(){
        langIdSelect.show();
    });

    $('input.lang-default').change(function(){
        langIdSelect.hide();
    });

    $('#struct_alias,#parent_alias').click(function() {

        var struct = $(this).data('alias') || $('#article_cid option:selected').text();
        var title = $.trim($('#article_title').val());

        if(struct.length) {
            struct = struct.replace(/^-+/gi, '').trim();

            if(title) {
                struct += '<?php if($phpwcms['alias_allow_slash']): ?>/<?php else: ?>-<?php endif; ?>'+title;
            }
        } else {
            struct = title;
        }

        $('#article_alias').val( create_alias(struct) );
    });

    $('#cat-as-articletitle').click(function(evnt){
        evnt.preventDefault();
        var currentCat = $('#article_cid option:selected').text();
        if(currentCat) {
            $('#article_title').val(currentCat.replace(/^-+ /, ''));
        }
    });
});

function cancelEdit() {
    document.location.href='phpwcms.php'+'?<?php echo CSRF_GET_TOKEN; ?>&do=articles<?php echo $article["article_id"] ? '&p=2&s=1&id='.$article["article_id"] : '' ?>';
    return false;
}

</script>
