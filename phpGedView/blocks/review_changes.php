<?php
/**
 * Review Changes Block
 *
 * This block prints the changes that still need to be reviewed and accepted by an administrator
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
 *
 * Modifications Copyright (c) 2010 Greg Roach
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @ersion $Id$
 * @package webtrees
 * @subpackage Blocks
 * @todo add a time configuration option
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_REVIEW_CHANGES_PHP', '');

$PGV_BLOCKS["review_changes_block"]["name"]			= i18n::translate('Pending Changes');
$PGV_BLOCKS["review_changes_block"]["descr"]		= i18n::translate('The Pending Changes block will give users with Edit rights a list of the records that have been changed online and that still need to be reviewed and accepted.  These changes are pending acceptance or rejection.<br /><br />If this block is enabled, users with Accept rights will receive an email once a day notifying them that changes need to be reviewed.');
$PGV_BLOCKS["review_changes_block"]["canconfig"]	= false;
$PGV_BLOCKS["review_changes_block"]["config"]		= array(
	"cache"=>0,
	"days"=>1,
	"sendmail"=>"yes"
	);

/**
 * Print Review Changes Block
 *
 * Prints a block allowing the user review all changes pending approval
 */
function review_changes_block($block = true, $config="", $side, $index) {
	global $ctype, $QUERY_STRING, $PGV_IMAGE_DIR, $PGV_IMAGES;
	global $pgv_changes, $TEXT_DIRECTION, $SHOW_SOURCES, $PGV_BLOCKS;
	global $PHPGEDVIEW_EMAIL;

	if (empty($config)) $config = $PGV_BLOCKS["review_changes_block"]["config"];

	if ($pgv_changes) {
		//-- if the time difference from the last email is greater than 24 hours then send out another email
		$LAST_CHANGE_EMAIL=get_site_setting('LAST_CHANGE_EMAIL');
		if (time()-$LAST_CHANGE_EMAIL > (60*60*24*$config["days"])) {
			$LAST_CHANGE_EMAIL = time();
			set_site_setting('LAST_CHANGE_EMAIL', $LAST_CHANGE_EMAIL);
			write_changes();
			if ($config["sendmail"]=="yes") {
				// Which users have pending changes?
				$users_with_changes=array();
				foreach (get_all_users() as $user_id=>$user_name) {
					foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
						if (exists_pending_change($user_id, $ged_id)) {
							$users_with_changes[$user_id]=$user_name;
							break;
						}
					}
				}
				foreach ($users_with_changes as $user_id=>$user_name) {
					//-- send message
					$message = array();
					$message["to"]=$user_name;
					$message["from"] = $PHPGEDVIEW_EMAIL;
					$message["subject"] = i18n::translate('PhpGedView - Review changes');
					$message["body"] = i18n::translate('Online changes have been made to a genealogical database.  These changes need to be reviewed and accepted before they will appear to all users.  Please use the URL below to enter that PhpGedView site and login to review the changes.');
					$message["method"] = get_user_setting($user_id, 'contactmethod');
					$message["url"] = PGV_SERVER_NAME.PGV_SCRIPT_PATH;
					$message["no_from"] = true;
					addMessage($message);
				}
			}
		}
		if (PGV_USER_CAN_EDIT) {
			$id="review_changes_block";
			$title='';
			if ($PGV_BLOCKS["review_changes_block"]["canconfig"]) {
				if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
					if ($ctype=="gedcom") {
						$name = PGV_GEDCOM;
					} else {
						$name = PGV_USER_NAME;
					}
					$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('".encode_url("index_edit.php?name={$name}&ctype={$ctype}&action=configure&side={$side}&index={$index}")."', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
					$title .= "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".i18n::translate('Configure')."\" /></a>";
				}
			}
			$title.=i18n::translate('Review GEDCOM Changes').help_link('review_changes');
			$content = "";
			if (PGV_USER_CAN_ACCEPT) {
				$content .= "<a href=\"javascript:;\" onclick=\"window.open('edit_changes.php','_blank','width=600,height=500,resizable=1,scrollbars=1'); return false;\">".i18n::translate('Accept / Reject Changes')."</a><br />";
			}
			if ($config["sendmail"]=="yes") {
				$content .= i18n::translate('Last email reminder was sent ').format_timestamp($LAST_CHANGE_EMAIL)."<br />";
				$content .= i18n::translate('Next email reminder will be sent after ').format_timestamp($LAST_CHANGE_EMAIL+(60*60*24*$config["days"]))."<br /><br />";
			}
			foreach ($pgv_changes as $cid=>$changes) {
				$change = $changes[count($changes)-1];
				if ($change["gedcom"]==PGV_GEDCOM) {
					$record=GedcomRecord::getInstance($change['gid']);
					if ($record->getType()!='SOUR' || $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) {
						$content.='<b>'.PrintReady($record->getFullName()).'</b> '.getLRM().'('.$record->getXref().')'.getLRM();
						switch ($record->getType()) {
						case 'INDI':
						case 'FAM':
						case 'SOUR':
						case 'OBJE':
							$content.=$block ? '<br />' : ' ';
							$content.='<a href="'.encode_url($record->getLinkUrl().'&show_changes=yes').'">'.i18n::translate('View Change Diff').'</a>';
							break;
						}
						$content.='<br />';
					}
				}
			}

			global $THEME_DIR;
			if ($block) {
				require $THEME_DIR.'templates/block_small_temp.php';
			} else {
				require $THEME_DIR.'templates/block_main_temp.php';
			}
		}
	}
}

function review_changes_block_config($config) {
	global $PGV_BLOCKS;
	if (empty($config)) $config = $PGV_BLOCKS["review_changes_block"]["config"];
	print i18n::translate('Send out reminder emails?');
	print "&nbsp;<select name='sendmail'>";
	print "<option value='yes'";
	if ($config["sendmail"]=="yes") print " selected='selected'";
	print ">".i18n::translate('Yes')."</option>";
	print "<option value='no'";
	if ($config["sendmail"]=="no") print " selected='selected'";
	print ">".i18n::translate('No')."</option>";
	print "</select><br /><br />";
	print i18n::translate('Reminder email frequency (days)')."&nbsp;<input type='text' name='days' value='".$config["days"]."' size='2' />";
	// Cache file life
	if ($ctype=="gedcom") {
		echo "<tr><td class=\"descriptionbox wrap width33\">";
		echo i18n::translate('Cache file life'), help_link('cache_life');
		echo "</td><td class=\"optionbox\">";
		echo "<input type=\"text\" name=\"cache\" size=\"2\" value=\"".$config["cache"]."\" />";
		echo "</td></tr>";
	}
	// Cache file life is not configurable by user:  anything other than "no cache" doesn't make sense
	print "<input type=\"hidden\" name=\"cache\" value=\"0\" />";
}

?>
