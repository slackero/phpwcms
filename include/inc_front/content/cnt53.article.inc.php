<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



// Content Type Forum

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$content['forum'] = unserialize($crow["acontent_form"]);
$content['forum']["tmpl"] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/forum/'.$content['forum']["template"]);

// get forum sections
//$content['forum']['form'] = get_tmpl_section('FORM', $content['forum']["tmpl"]);


$content['forum']['ACTION']		= 0;
$content['forum']['GET']		= 0;
$content['forum']['ARTICLE']	= 'index.php?id='.implode(',', $aktion);
$content['forum']['MODE']		= '';

if(!empty($_GET['forum'])) {
	// define topic listing for selected forum
	$content['forum']['ACTION']	= 1;
	$content['forum']['GET']	= intval($_GET['forum']);
}
if(!empty($_GET['topic'])) {
	// define posts listing for current topic
	$content['forum']['ACTION']	= 2;
	$content['forum']['GET']	= intval($_GET['topic']);
}
if(!empty($_GET['post'])) {
	// define which post is selected
	$content['forum']['ACTION']	= 3;
	$content['forum']['GET']	= intval($_GET['post']);
}
if(!empty($_GET['mode'])) {
	// define what action
	$content['forum']['MODE']	= $_GET['mode'];
}


/**
 * FORUM
 */
if($content['forum']['ACTION'] === 0) {

	// List forums

	$content['forum']['sel'] = '';
	$content['forum']['sel'] = trim(implode(' OR forum_id=', $content['forum']['selection']));

	$CNT_TMP .= '<table width="100%" cellpadding="2" cellspacing="1" border="0" class="tableForum">';
	$CNT_TMP .= "\n<tr>\n";
	$CNT_TMP .= '<th colspan="2" nowrap="nowrap" class="thForum">&nbsp;Forum&nbsp;</th>'."\n";
	$CNT_TMP .= '<th width="50" class="thTopic" nowrap="nowrap">&nbsp;Topics&nbsp;</th>'."\n";
	$CNT_TMP .= '<th width="50" class="thPost" nowrap="nowrap">&nbsp;Posts&nbsp;</th>'."\n";
	$CNT_TMP .= '<th class="thLastPost" nowrap="nowrap" align="center">&nbsp;Last&nbsp;</th>';
	$CNT_TMP .= "\n</tr>\n";

	if($content['forum']['sel'] != '') {
		$content['forum']['sel'] = 'AND (forum_id='.$content['forum']['sel'].')';
	}

	$content["forum"]['selected'] = array();
	foreach($content["forum"]['selection'] as $content["forum"]['selected_value']) {
		$content["forum"]['selected'][intval($content["forum"]['selected_value'])] = '';
	}

	$sql_f = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ".$content['forum']['sel'];
	if($result_f = mysql_query($sql_f, $db) or die("error while listing forums")) {
		while($row_f = mysql_fetch_assoc($result_f)) {
			if(isset($content["forum"]['selected'][$row_f["forum_id"]]) && $row_f['forum_title']) {
		
		
				$CNT_TMP .= "<tr>\n";
				$CNT_TMP .= '<td width="19" class="rowIcon" align="center" valign="middle"><img src="img/forum/silver/folder.gif" alt=""></td>'."\n";
				$CNT_TMP .= '<td width="100%" class="rowForum"><span class="rowTextForumlink">';
				$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;forum='.$row_f["forum_id"].'">';
				$CNT_TMP .= html_specialchars($row_f["forum_title"]).'</a></span>';
				if($row_f['forum_text'] != '') {
					$CNT_TMP .= '<br /><span class="rowTextMed">'.html_specialchars($row_f['forum_text']).'</span>';
				}
				$CNT_TMP .= "</td>\n";
			
				$CNT_TMP .= '<td width="50" class="rowTopic" align="center" valign="middle"><span class="rowTextSmall">';
				$CNT_TMP .= $row_f['forum_ctopic'].'</span></td>'."\n";
			
				$CNT_TMP .= '<td width="50" class="rowPost" align="center" valign="middle"><span class="rowTextSmall">';
				$CNT_TMP .= $row_f['forum_cpost'].'</span></td>'."\n";
			
				$CNT_TMP .= '<td class="rowLastPost" align="center" valign="middle" nowrap="nowrap"><span class="rowTextSmall">&nbsp;';
				
				if(!empty($row_f['forum_lastpost'])) {
						$content["forum"]['lastpost'] = explode(':', $row_f['forum_lastpost']);
						$CNT_TMP .= date('Y/m/d H:i', $content["forum"]['lastpost'][0]);
						
				} else {
					$CNT_TMP .= date('Y/m/d H:i', $row_f['forum_created']);
				}
				
				$CNT_TMP .= '&nbsp;';
				$CNT_TMP .= '</span></td>'."\n";
			
				$CNT_TMP .= "\n</tr>\n";

			}
		}
		mysql_free_result($result_f);
	}

	$CNT_TMP .= '</table>';
	// end List forums


/**
 * FORUM
 */
} elseif($content['forum']['ACTION'] === 1) {
	// list topics
		
	$row_f['forum_id']		= $content['forum']['GET'];
	$row_f["forum_title"]	= 'Current';
	$sql_f  = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ";
	$sql_f .= "AND forum_id=".$content['forum']['GET']." LIMIT 1";
	if($result_f = mysql_query($sql_f, $db) or die("error while retrieving forum info")) {
		$row_f = mysql_fetch_assoc($result_f);
		mysql_free_result($result_f);
	}
			
	
	switch($content['forum']['MODE']) {
	
		case 'newtopic':
		
		$content['forum']['topic'] = array('subject' => '', 'message' => '', 'notify' => 1, 'error' => '');
		
		// Process newtopic POST values
		if(isset($_POST['forum_subject'])) {
		
			$content['forum']['topic']['subject'] = remove_unsecure_rptags(clean_slweg($_POST['forum_subject']));
			$content['forum']['topic']['message'] = remove_unsecure_rptags(clean_slweg($_POST['forum_message']));
			$content['forum']['topic']['notify']  = isset($_POST['forum_notify']) ? 1 : 0;
			
			if(empty($content['forum']['topic']['message'])) {
				$content['forum']['topic']['error'] = 'There has to be a message for your topic';
			}
		
			// if no error fill in or update topic
			if(empty($content['forum']['topic']['error'])) {
				
				$sql_topic  = "INSERT INTO ".DB_PREPEND."phpwcms_forum SET ";
				$sql_topic .= "forum_entry = '1', "; // a topic
				$sql_topic .= "forum_cid = '".$row_f["forum_id"]."', ";
				$sql_topic .= "forum_title = '".aporeplace($content['forum']['topic']['subject'])."', ";
				$sql_topic .= "forum_created = '".time()."', ";
				$sql_topic .= "forum_text = '".aporeplace($content['forum']['topic']['message'])."'";
				
				// save new topic
				if(@mysql_query($sql_topic, $db)) {
					
					//update forum info
					$sql_topic  = "UPDATE ".DB_PREPEND."phpwcms_forum SET ";
					$sql_topic .= "forum_ctopic='".($row_f["forum_ctopic"]+1)."', ";
					$sql_topic .= "forum_cpost='".($row_f["forum_cpost"]+1)."', ";
					$sql_topic .= "forum_lastpost='".time().':'.intval(mysql_insert_id()).":2' ";
					$sql_topic .= "WHERE forum_entry=0 AND forum_id=".$row_f["forum_id"]." LIMIT 1";
					@mysql_query($sql_topic, $db);
					
					headerRedirect($content['forum']['ARTICLE'].'&forum='.$row_f["forum_id"]);
									
				} else {
					$content['forum']['topic']['error'] = 'Error while creating new topic.';
				}
			
			}
		
		
		}
		
		// Topic new form
		$CNT_TMP .= '<form name="newtopic" action="'.$content['forum']['ARTICLE'].'&amp;mode=newtopic';
		$CNT_TMP .= '&amp;forum='.$row_f["forum_id"].'" method="post">';

		$CNT_TMP .= '<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">';
		$CNT_TMP .= "\n<tr>\n".'<td align="left" class="navForum">';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'">Forum overview</a> / ';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;forum='.$row_f["forum_id"].'">';
		$CNT_TMP .= html_specialchars($row_f["forum_title"])."</a></td>\n</tr>\n</table>";
		
		
		$CNT_TMP .= '<table width="100%" cellpadding="3" cellspacing="1" border="0" class="tableForum">'."\n<tr>\n";
		$CNT_TMP .= '<th colspan="2" nowrap="nowrap" class="thForum"><strong>Write New Topic</strong></th>'."\n</tr>\n";
		
		// Topic message Error
		if(!empty($content['forum']['topic']['error'])) {
			$CNT_TMP .= "<tr>\n";
			$CNT_TMP .= '<td class="rowError" width="20%">&nbsp;</td>'."\n";
			$CNT_TMP .= '<td class="rowError" width="80%">'.html_specialchars($content['forum']['topic']['error']);
			$CNT_TMP .= "</td>\n</tr>\n";
		}
		
		// Topic Subject
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td class="rowLabel" width="20%"><span class="rowTextLabel">title</span></td>'."\n";
		$CNT_TMP .= '<td class="rowPost" width="80%"><input type="text" name="forum_subject" size="45" maxlength="60" ';
		$CNT_TMP .= 'style="width:425px" class="forumInputText" value="'.html_specialchars($content['forum']['topic']['subject']).'" /></td>';
		$CNT_TMP .= "\n</tr>\n";
		
		// Topic Message
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td class="rowLabel" width="20%" valign="top"><span class="rowTextLabel" style="line-height:23px">message</span></td>'."\n";
		$CNT_TMP .= '<td class="rowPost" width="80%"><textarea name="forum_message" wrap="VIRTUAL" cols="35" rows="15" ';
		$CNT_TMP .= 'style="width:425px" class="forumTextareaText">'.html_specialchars($content['forum']['topic']['message']).'</textarea></td>';
		$CNT_TMP .= "\n</tr>\n";
		
		// Topic Options
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td class="rowLabel" width="20%"><span class="rowTextLabel">options</span></td>'."\n";
		$CNT_TMP .= '<td class="rowPost" width="80%"><table cellspacing="0" cellpadding="1" border="0">';
		$CNT_TMP .= '<tr><td><input type="checkbox" name="forum_notify" id="forum_notify" value="1"'.is_checked(1, $content['forum']['topic']['notify'], 1, 0);
		$CNT_TMP .= ' /></td><td><label for="forum_notify"><span class="rowText">Notify me on reply</span></label>';
		$CNT_TMP .= "</td></tr></table></td>\n</tr>\n";
		
		// Topic Send Button
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td colspan="2" class="catBottom">';
		$CNT_TMP .= '<input type="submit" name="forum_topic_submit" value="Send" class="forumButton" />';
		$CNT_TMP .= "</td>\n</tr>\n";
		
		
		$CNT_TMP .= '</table>';
		$CNT_TMP .= '</form>';
		
	
		break;
	
	
		default:
		$CNT_TMP .= '<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">'."\n<tr>\n";
		$CNT_TMP .= '<td width="50" valign="middle"><a href="'.$content['forum']['ARTICLE'].'&amp;mode=newtopic';
		$CNT_TMP .= '&amp;forum='.$row_f["forum_id"].'">';
		$CNT_TMP .= '<img src="img/forum/silver/post.gif" alt="Post new topic" border="0"></a></td>'."\n";
		$CNT_TMP .= '<td width="95%" align="left" valign="middle"><span class="navForum">&nbsp;&nbsp;&nbsp;';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'">Forum overview</a> / ';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;forum='.$row_f["forum_id"].'">';
		$CNT_TMP .= html_specialchars($row_f["forum_title"])."</a></span></td>\n";
		$CNT_TMP .= '<td align="left" nowrap="nowrap" valign="middle">&nbsp;</td>';
		$CNT_TMP .= "\n</tr>\n</table>";
	
		$CNT_TMP .= '<table width="100%" cellpadding="2" cellspacing="1" border="0" class="tableForum">';
		$CNT_TMP .= "\n<tr>\n";
		$CNT_TMP .= '<th colspan="2" nowrap="nowrap" class="thForum">&nbsp;Topics&nbsp;</th>'."\n";
		$CNT_TMP .= '<th width="50" class="thTopic" nowrap="nowrap">&nbsp;Replies&nbsp;</th>'."\n";
		$CNT_TMP .= '<th width="50" class="thPost" nowrap="nowrap">&nbsp;Author&nbsp;</th>'."\n";
		$CNT_TMP .= '<th class="thLastPost" nowrap="nowrap" align="center">&nbsp;Last&nbsp;</th>';
		$CNT_TMP .= "\n</tr>\n";
		
		
		$sql_topic  = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=1 AND forum_deleted=0 ";
		$sql_topic .= "AND forum_cid=".$row_f["forum_id"]." ORDER BY forum_changed DESC";
		
		if($result_t = mysql_query($sql_topic, $db) or die("error while listing topic for current forum")) {
			while($row_t = mysql_fetch_assoc($result_t)) {
			
				get_fe_userinfo($row_t['forum_uid']);
						
				$CNT_TMP .= "<tr>\n";
				$CNT_TMP .= '<td width="19" class="rowIcon" align="center" valign="middle"><img src="img/forum/silver/folder.gif" alt=""></td>'."\n";
				
				$CNT_TMP .= '<td width="100%" class="rowForum"><span class="rowTextForumlink">';
				$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;topic='.$row_t["forum_id"].'">';
				$CNT_TMP .= html_specialchars($row_t["forum_title"])."</a></span></td>\n";
			
				$CNT_TMP .= '<td width="50" class="rowTopic" align="center" valign="middle"><span class="rowTextSmall">';
				$CNT_TMP .= $row_t['forum_cpost'].'</span></td>'."\n";
			
				$CNT_TMP .= '<td width="50" class="rowPost" align="center" valign="middle"><span class="rowTextSmall">&nbsp;';
				$CNT_TMP .= html_specialchars($GLOBALS['FE_USER'][$row_t['forum_uid']]['login']).'&nbsp;</span></td>'."\n";
			
				$CNT_TMP .= '<td class="rowLastPost" align="center" valign="middle" nowrap="nowrap"><span class="rowTextSmall">&nbsp;';
				
				if(!empty($row_t['forum_lastpost'])) {
						$content["forum"]['lastpost'] = explode(':', $row_t['forum_lastpost']);
						$CNT_TMP .= date('Y/m/d H:i', $content["forum"]['lastpost'][0]);
				} else {
					$CNT_TMP .= date('Y/m/d H:i', $row_t["forum_created"]);
				}
				
				$CNT_TMP .= '&nbsp;</span></td>'."\n";
			
				$CNT_TMP .= "\n</tr>\n";

			}
			mysql_free_result($result_t);
		}

		$CNT_TMP .= '</table>';
	
	}

} elseif($content['forum']['ACTION'] === 2) {
	// show topic and related posts
		
	$row_t['forum_id']		= $content['forum']['GET'];
	$row_t["forum_title"]	= 'Current';
	$sql_t  = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=1 AND forum_deleted=0 ";
	$sql_t .= "AND forum_id=".$content['forum']['GET']." LIMIT 1";
	if($result_t = mysql_query($sql_t, $db) or die("error while retrieving topic info")) {
		$row_t = mysql_fetch_assoc($result_t);
		mysql_free_result($result_t);
		
		$sql_f  = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ";
		$sql_f .= "AND forum_id=".$row_t['forum_cid']." LIMIT 1";
		if($result_f = mysql_query($sql_f, $db) or die("error while retrieving related forum info")) {
			$row_f = mysql_fetch_assoc($result_f);
			mysql_free_result($result_f);
		}
	}
			
	
	switch($content['forum']['MODE']) {
	
	
		case 'reply':
		
		$content['forum']['reply'] = array('subject' => '', 'message' => '', 'notify' => 1, 'error' => '');
		
		// Process newtopic POST values
		if(isset($_POST['forum_subject'])) {
		
			$content['forum']['reply']['subject'] = remove_unsecure_rptags(clean_slweg($_POST['forum_subject']));
			$content['forum']['reply']['message'] = remove_unsecure_rptags(clean_slweg($_POST['forum_message']));
			$content['forum']['reply']['notify']  = isset($_POST['forum_notify']) ? 1 : 0;
			
			if(empty($content['forum']['reply']['message'])) {
				$content['forum']['reply']['error'] = 'There has to be a message for your reply';
			}
		
			// if no error fill in or update topic
			if(empty($content['forum']['reply']['error'])) {
				
				$sql_reply  = "INSERT INTO ".DB_PREPEND."phpwcms_forum SET ";
				$sql_reply .= "forum_entry = '2', "; // a reply
				$sql_reply .= "forum_cid = '".$row_t["forum_id"]."', ";
				$sql_reply .= "forum_title = '".aporeplace($content['forum']['reply']['subject'])."', ";
				$sql_reply .= "forum_created = '".time()."', ";
				$sql_reply .= "forum_text = '".aporeplace($content['forum']['reply']['message'])."'";
				
				// save new topic
				if(@mysql_query($sql_reply, $db)) {
					
					//update forum info
					$sql_reply  = "UPDATE ".DB_PREPEND."phpwcms_forum SET ";
					$sql_reply .= "forum_cpost='".($row_f["forum_cpost"]+1)."', ";
					$sql_reply .= "forum_lastpost='".time().':'.intval(mysql_insert_id()).":2' ";
					$sql_reply .= "WHERE forum_entry=0 AND forum_id=".$row_f["forum_id"]." LIMIT 1";
					@mysql_query($sql_reply, $db);
					
					//update topic info
					$sql_reply  = "UPDATE ".DB_PREPEND."phpwcms_forum SET ";
					$sql_reply .= "forum_cpost='".($row_t["forum_cpost"]+1)."', ";
					$sql_reply .= "forum_lastpost='".time().':'.intval(mysql_insert_id()).":2' ";
					$sql_reply .= "WHERE forum_entry=1 AND forum_id=".$row_t["forum_id"]." LIMIT 1";
					@mysql_query($sql_reply, $db);
					
					headerRedirect($content['forum']['ARTICLE'].'&topic='.$row_t["forum_id"]);
									
				} else {
					$content['forum']['topic']['error'] = 'Error while creating new reply.';
				}
			
			}
		
		
		}
		
		// reply new form
		$CNT_TMP .= '<form name="newreply" action="'.$content['forum']['ARTICLE'].'&amp;mode=reply';
		$CNT_TMP .= '&amp;topic='.$row_t["forum_id"].'" method="post">';

		$CNT_TMP .= '<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">';
		$CNT_TMP .= "\n<tr>\n".'<td align="left" class="navForum">';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'">Forum overview</a> / ';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;forum='.$row_f["forum_id"].'">';
		$CNT_TMP .= html_specialchars($row_f["forum_title"])."</a></td>\n</tr>\n</table>";
		
		
		$CNT_TMP .= '<table width="100%" cellpadding="3" cellspacing="1" border="0" class="tableForum">'."\n<tr>\n";
		$CNT_TMP .= '<th colspan="2" nowrap="nowrap" class="thForum"><strong>Write Reply</strong></th>'."\n</tr>\n";
		
		// Reply message Error
		if(!empty($content['forum']['reply']['error'])) {
			$CNT_TMP .= "<tr>\n";
			$CNT_TMP .= '<td class="rowError" width="20%">&nbsp;</td>'."\n";
			$CNT_TMP .= '<td class="rowError" width="80%">'.html_specialchars($content['forum']['reply']['error']);
			$CNT_TMP .= "</td>\n</tr>\n";
		}
		
		// reply Subject
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td class="rowLabel" width="20%"><span class="rowTextLabel">title</span></td>'."\n";
		$CNT_TMP .= '<td class="rowPost" width="80%"><input type="text" name="forum_subject" size="45" maxlength="60" ';
		$CNT_TMP .= 'style="width:425px" class="forumInputText" value="'.html_specialchars($content['forum']['reply']['subject']).'" /></td>';
		$CNT_TMP .= "\n</tr>\n";
		
		// Reply Message
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td class="rowLabel" width="20%" valign="top"><span class="rowTextLabel" style="line-height:23px">message</span></td>'."\n";
		$CNT_TMP .= '<td class="rowPost" width="80%"><textarea name="forum_message" wrap="VIRTUAL" cols="35" rows="15" ';
		$CNT_TMP .= 'style="width:425px" class="forumTextareaText">'.html_specialchars($content['forum']['reply']['message']).'</textarea></td>';
		$CNT_TMP .= "\n</tr>\n";
		
		// Reply Options
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td class="rowLabel" width="20%"><span class="rowTextLabel">options</span></td>'."\n";
		$CNT_TMP .= '<td class="rowPost" width="80%"><table cellspacing="0" cellpadding="1" border="0">';
		$CNT_TMP .= '<tr><td><input type="checkbox" name="forum_notify" id="forum_notify" value="1"'.is_checked(1, $content['forum']['reply']['notify'], 1, 0);
		$CNT_TMP .= ' /></td><td><label for="forum_notify"><span class="rowText">Notify me on reply</span></label>';
		$CNT_TMP .= "</td></tr></table></td>\n</tr>\n";
		
		// Reply Send Button
		$CNT_TMP .= "<tr>\n";
		$CNT_TMP .= '<td colspan="2" class="catBottom">';
		$CNT_TMP .= '<input type="submit" name="forum_reply_submit" value="Send" class="forumButton" />';
		$CNT_TMP .= "</td>\n</tr>\n";
		
		
		$CNT_TMP .= '</table>';
		$CNT_TMP .= '</form>';
	
		break;
		
		
		
		default:
		$CNT_TMP .= '<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">'."\n<tr>\n";
		$CNT_TMP .= '<td valign="middle"><a href="'.$content['forum']['ARTICLE'].'&amp;mode=newtopic';
		$CNT_TMP .= '&amp;forum='.$row_f["forum_id"].'">';
		$CNT_TMP .= '<img src="img/forum/silver/post.gif" alt="Post new topic" border="0"></a>';
		$CNT_TMP .= '&nbsp;&nbsp;';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;mode=reply';
		$CNT_TMP .= '&amp;topic='.$row_t["forum_id"].'">';
		$CNT_TMP .= '<img src="img/forum/silver/reply.gif" alt="Post reply" border="0"></a>';		
		
		$CNT_TMP .= '</td>'."\n";
		$CNT_TMP .= '<td width="100%" align="left" valign="middle"><span class="navForum">&nbsp;&nbsp;';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'">Forum overview</a> / ';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;forum='.$row_f["forum_id"].'">';
		$CNT_TMP .= html_specialchars($row_f["forum_title"])."</a></span></td>\n";
		$CNT_TMP .= '<td align="left" nowrap="nowrap" valign="middle">&nbsp;</td>';
		$CNT_TMP .= "\n</tr>\n</table>";
		
		// list posts
		$CNT_TMP .= '<table width="100%" cellpadding="2" cellspacing="1" border="0" class="tableForum">';
		$CNT_TMP .= "\n<tr>\n";
		$CNT_TMP .= '<th width="150" class="thPost" nowrap="nowrap" align="center">&nbsp;Author&nbsp;</th>'."\n";
		$CNT_TMP .= '<th width="100%" class="thPost" nowrap="nowrap" align="center">&nbsp;Message&nbsp;</th>'."\n";
		$CNT_TMP .= "\n</tr>\n";
		
		$CNT_TMP .= "\n<tr>\n";
		$CNT_TMP .= '<td width="150" class="rowReply" align="left" valign="top"><a name="'.$row_t["forum_id"].'"></a>';
		
		get_fe_userinfo($row_t["forum_uid"]);
		
		$CNT_TMP .= html_specialchars($GLOBALS['FE_USER'][$row_t['forum_uid']]['login'])."</td>\n";
		$CNT_TMP .= '<td width="100%" class="rowReply"><div class="postdetails">';
		$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;topic='.$row_t["forum_id"].'#'.$row_t["forum_id"].'">';
		$CNT_TMP .= '<img src="img/forum/silver/icon_minipost.gif" alt="Post" title="Post" border="0"></a>composed: ';
		$CNT_TMP .= international_date_format($phpwcms['default_lang'], 'j F Y H:i', $row_t["forum_created"]);
		$CNT_TMP .= '&nbsp; title: '.html_specialchars($row_t["forum_title"]);
		$CNT_TMP .= '<hr /></div><div class="postbody">'.html_specialchars($row_t["forum_text"]);
		$CNT_TMP .= "</div></td>\n</tr>\n";
		
		
		
		$sql_post  = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=2 AND forum_deleted=0 ";
		$sql_post .= "AND forum_cid=".$row_t["forum_id"]." ORDER BY forum_created ASC";
		
		if($result_p = mysql_query($sql_post, $db) or die("error while listing topic for current forum")) {
			$row_p_alter = 0;
			while($row_p = mysql_fetch_assoc($result_p)) {
				
				$CNT_TMP .= '<tr><td colspan="2" class="row3"><img src="img/leer.gif" alt="" height="1" width="1" border="0" /></td>'."</tr>\n";
				
				$row_p_alter_class = ($row_p_alter % 2) ? 'rowReply' : 'rowReplyA';
				
				get_fe_userinfo($row_p["forum_uid"]);
				
				$CNT_TMP .= "\n<tr>\n";
				$CNT_TMP .= '<td width="150" class="'.$row_p_alter_class.'" align="left" valign="top"><a name="'.$row_p["forum_id"].'"></a>';
				$CNT_TMP .= html_specialchars($GLOBALS['FE_USER'][$row_p['forum_uid']]['login'])."</td>\n";
				$CNT_TMP .= '<td width="100%" class="'.$row_p_alter_class.'"><div class="postdetails">';
				$CNT_TMP .= '<a href="'.$content['forum']['ARTICLE'].'&amp;topic='.$row_t["forum_id"].'#'.$row_p["forum_id"].'">';
				$CNT_TMP .= '<img src="img/forum/silver/icon_minipost.gif" alt="Post" title="Post" border="0"></a>composed: ';
				$CNT_TMP .= international_date_format($phpwcms['default_lang'], 'j F Y H:i', $row_p["forum_created"]);
				$CNT_TMP .= '&nbsp; title: '.html_specialchars($row_p["forum_title"]);
				$CNT_TMP .= '<hr /></div><div class="postbody">'.html_specialchars($row_p["forum_text"]);
				$CNT_TMP .= "</div></td>\n</tr>\n";
				
				$row_p_alter++;

			}
			mysql_free_result($result_p);
		}
		
		$CNT_TMP .= '<tr><td colspan="2" class="row3"><img src="img/leer.gif" alt="" height="1" width="1" border="0" /></td>'."</tr>\n";
		$CNT_TMP .= '</table>';
		
	
	}
	
}

unset($content['forum']);

?>