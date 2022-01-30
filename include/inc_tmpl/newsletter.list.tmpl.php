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


if(isset($_GET["s"])) {

	include_once PHPWCMS_ROOT.'/include/inc_lib/newsletter.form.inc.php';

	if(isset($_GET['edit'])) {
		include_once PHPWCMS_ROOT.'/include/inc_tmpl/newsletter.form.tmpl.php';
	}

	if(isset($_GET['send']) && $show_nl_send) {

		include_once PHPWCMS_ROOT.'/include/inc_tmpl/newsletter.send.tmpl.php';

	}

} else {

	if(isset($_GET['duplicate_nl'])) {
		@_dbDuplicateRow(	'phpwcms_newsletter', 'newsletter_id', intval($_GET['duplicate_nl']),
							array('newsletter_active' => 0, 'newsletter_changed' => 'SQL:NOW()',
							'newsletter_lastsending' => '0000-00-00 00:00:00', 'newsletter_created' => 'SQL:NOW()',
							'newsletter_subject' => '--SELF-- (copy)'));
	}

// check if subscription should be edited


// create paginating for newsletter
if(isset($_GET['c'])) {
	$_SESSION['list_newsletter_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
// set default values for paginating
if(empty($_SESSION['list_newsletter_count'])) {
	$_SESSION['list_newsletter_count'] = 10;
}
// set page
if(isset($_GET['page'])) {
	$_SESSION['newsletter_page'] = intval($_GET['page']);
}

$_newsletter['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_newsletter WHERE newsletter_trashed=0", 'COUNT');
$_newsletter['pages_total'] = ceil($_newsletter['count_total'] / $_SESSION['list_newsletter_count']);
if(empty($_SESSION['newsletter_page'])) {
	$_SESSION['newsletter_page'] = 1;
}
if($_SESSION['newsletter_page'] > $_newsletter['pages_total']) {
	$_SESSION['newsletter_page'] = $_newsletter['pages_total'];
}
if($_SESSION['newsletter_page'] < 1) {
	$_SESSION['newsletter_page'] = 1;
}

?>

<div class="title" style="margin-bottom:10px"><?php echo $BL['be_subnav_msg_newslettersend'] ?></div>

<div class="navBarLeft imgButton chatlist">
	&nbsp;&nbsp;
	<a href="phpwcms.php?do=messages&amp;p=3&amp;s=0&amp;edit=1"><img src="img/famfamfam/email_add.gif" alt="Add" border="0" /><span><?php echo $BL['be_newsletter_new'] ?></span></a>
</div>



<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><?php
if($_newsletter['pages_total'] > 1) {

	echo '<table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td>';
	if($_SESSION['newsletter_page'] > 1) {
		echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;page='.($_SESSION['newsletter_page']-1).'">';
		echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['newsletter_page'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td class="chatlist">/'.$_newsletter['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['newsletter_page'] < $_newsletter['pages_total']) {
		echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;page='.($_SESSION['newsletter_page']+1).'">';
		echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td></tr></table>';
} else {
	echo '&nbsp;';
}
?>

	</td>

	<td class="chatlist" align="right">
		<a href="phpwcms.php?do=messages&amp;p=3&amp;c=5">5</a>
		<a href="phpwcms.php?do=messages&amp;p=3&amp;c=10">10</a>
		<a href="phpwcms.php?do=messages&amp;p=3&amp;c=25">25</a>
		<a href="phpwcms.php?do=messages&amp;p=3&amp;c=50">50</a>
		<a href="phpwcms.php?do=messages&amp;p=3&amp;c=100">100</a>
		<a href="phpwcms.php?do=messages&amp;p=3&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
	</td>

	</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

	<tr class="tableHeadRow">
		<th>&nbsp;</th>
		<th style="text-align:left"><?php echo $BL['be_msg_subject'] ?></th>
		<th><?php echo $BL['be_newsletter_changed'] ?></th>
		<th><?php echo str_replace(' ', '<br />', $BL['be_last_sending']) ?></th>
		<th><?php echo $BL['be_total'].'/<br />'.$BL['be_cnt_queued'].'/<br />'.$BL['be_msg_senttop'] ?></th>
		<th>&nbsp;</th>
	</tr>

	<tr><td colspan="6" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<?php

	// loop listing available newsletters
	$sql  = "SELECT *, UNIX_TIMESTAMP(newsletter_changed) AS cdate, UNIX_TIMESTAMP(newsletter_lastsending) AS lastsend FROM ".DB_PREPEND."phpwcms_newsletter WHERE newsletter_trashed=0 ORDER BY newsletter_changed DESC";
	$sql .= " LIMIT ".(($_SESSION['newsletter_page']-1) * $_SESSION['list_newsletter_count']).','.$_SESSION['list_newsletter_count'];

	$result = _dbQuery($sql);

	if(isset($result[0]['newsletter_id'])) {

		$row_count = 0;

		foreach($result as $row) {

			$row['newsletter_vars'] = unserialize($row['newsletter_vars']);

			echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow">'.LF;
			echo '<td width="2%" style="padding:2px 5px 2px 4px;">';

			// sent/queue status
			$count_sent  		= _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=1 AND queue_pid='.$row["newsletter_id"], 'COUNT');
			$count_queue 		= _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=0 AND queue_pid='.$row["newsletter_id"], 'COUNT');
			$count_recipient	= countNewsletterRecipients($row['newsletter_vars']['subscription']);

			if(empty($row["newsletter_active"]) || !$count_queue) {
				echo '<img src="img/famfamfam/email.gif" alt="NL" title="ID:'.$row["newsletter_id"].'" />';
			} else {
				echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"];
				echo '&amp;send=1"><img src="img/famfamfam/email_go.gif" alt="Send" border="0" title="ID:'.$row["newsletter_id"].'"></a>';
			}

			echo '</td>'.LF;

			echo '<td class="dir"><strong>'.html($row["newsletter_subject"])."</strong></td>\n";

			// create date
			echo '<td nowrap="nowrap" class="v10 nowrap" align="center">&nbsp;';
			if($row['cdate']) {
				echo @date($BL['be_shortdatetime'], $row['cdate']);
			}
			echo '&nbsp;</td>';
			// last sending
			echo '<td nowrap="nowrap" class="v10 nowrap" align="center">&nbsp;';
			if($row['lastsend']) {
				@date($BL['be_shortdatetime'], $row['lastsend']);
			}
			echo '&nbsp;</td>';

			echo '<td nowrap="nowrap" class="v10 nowrap" align="center">'.$count_recipient.'/'.$count_queue.'/'.$count_sent;
			if($count_sent && !$count_queue && $row["newsletter_active"]) {
				echo '<img src="img/symbole/valid.gif" border="0" alt="valid" style="margin: 0 0 0 3px" />';
			}
			echo '&nbsp;</td>';

			// buttons
			echo '<td align="right" nowrap="nowrap" class="button_td nowrap">';

			// duplicate
			echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;duplicate_nl='.$row["newsletter_id"];
			echo '"><img src="img/button/copy_11x11_0.gif" alt="Duplicate" border="0" style="margin:1px 3px 1px 0" /></a>';

			// edit
			echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"];
			echo '&amp;edit=1"><img src="img/button/edit_22x13.gif" alt="Edit" border="0" /></a>';

			// delete
			echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"].'&amp;del='.$row["newsletter_id"];
			echo '" title="delete: '.html($row["newsletter_subject"]);
			echo '" onclick="return confirm(\'Delete newsletter: '.js_singlequote(html($row["newsletter_subject"])).'\');">';
			echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="Delete" /></a>';

			echo "</td>\n</tr>\n";

			$row_count++;

		}

	} else {

		echo '<tr><td colspan="6">&nbsp;no newsletter available</td></tr>';

	}

?>
	<tr><td colspan="6" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
</table>
<?php

}
