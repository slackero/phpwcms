<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if(isset($_GET["s"])) {

	include_once(PHPWCMS_ROOT.'/include/inc_lib/newsletter.form.inc.php');
	
	if(isset($_GET['edit'])) {
		include_once(PHPWCMS_ROOT.'/include/inc_tmpl/newsletter.form.tmpl.php');
	}

	if(isset($_GET['send']) && $show_nl_send) {

		include_once(PHPWCMS_ROOT.'/include/inc_tmpl/newsletter.send.tmpl.php');

	}

} else {

	if(isset($_GET['duplicate_nl'])) {
		@_dbDuplicateRow(	'phpwcms_newsletter', 'newsletter_id', intval($_GET['duplicate_nl']), 
							array('newsletter_active' => 0, 'newsletter_changed' => 'SQL:NOW()', 
							'newsletter_lastsending' => '0000-00-00 00:00:00', 'newsletter_created' => 'SQL:NOW()', 
							'newsletter_subject' => '--SELF-- (copy)'));
	}

// check if subscription should be edited
?>

<div class="title" style="margin-bottom:10px"><?php echo $BL['be_subnav_msg_newslettersend'] ?></div>

<div class="navBarLeft imgButton chatlist">
	&nbsp;&nbsp;
	<a href="phpwcms.php?do=messages&amp;p=3&amp;s=0&amp;edit=1"><img src="img/famfamfam/email_add.gif" alt="Add" border="0" /><span><?php echo $BL['be_newsletter_new'] ?></span></a>
</div>

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
                                 
	$sql	= "SELECT * FROM ".DB_PREPEND."phpwcms_newsletter WHERE newsletter_trashed=0 ORDER BY newsletter_changed DESC";
	$result	= _dbQuery($sql);
	
	if($result) {
		
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
						
			echo '<td class="dir"><strong>'.html_specialchars($row["newsletter_subject"])."</strong></td>\n";
			
			// create date
			echo '<td nowrap="nowrap" class="v10" align="center">&nbsp;'.@date($BL['be_shortdatetime'], strtotime($row['newsletter_changed'])).'&nbsp;</td>';
			// last sending
			echo '<td nowrap="nowrap" class="v10" align="center">&nbsp;'.@date($BL['be_shortdatetime'], strtotime($row['newsletter_lastsending'])).'&nbsp;</td>';
			
			echo '<td nowrap="nowrap" class="v10" align="center">'.$count_recipient.'/'.$count_queue.'/'.$count_sent;
			if($count_sent && !$count_queue && $row["newsletter_active"]) {
				echo '<img src="img/symbole/valid.gif" border="0" alt="valid" style="margin: 0 0 0 3px" />';
			}
			echo '&nbsp;</td>';
			
			// buttons
			echo '<td align="right" nowrap="nowrap" class="button_td">';
			
			// duplicate
			echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;duplicate_nl='.$row["newsletter_id"];
			echo '"><img src="img/button/copy_11x11_0.gif" alt="Duplicate" border="0" style="margin:1px 3px 1px 0" /></a>';
			
			// edit
			echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"];
			echo '&amp;edit=1"><img src="img/button/edit_22x13.gif" alt="Edit" border="0" /></a>';
			
			// delete
			echo '<a href="phpwcms.php?do=messages&amp;p=3&amp;s='.$row["newsletter_id"].'&amp;del='.$row["newsletter_id"];
			echo '" title="delete: '.html_specialchars($row["newsletter_subject"]);			
			echo '" onclick="return confirm(\'Delete newsletter: '.js_singlequote(html_specialchars($row["newsletter_subject"])).'\');">';
			echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="Delete" /></a>';
			
			echo "</td>\n</tr>\n";

			$row_count++;
			
		}
		
	} else {
	
		echo '<tr><td colspan="4">&nbsp;no newsletter available</td></tr>';
	
	}
		
?>
	<tr><td colspan="6" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
</table>
<?php

}


?>