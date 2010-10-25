<?php
/**
 * Administrative User Interface.
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
 * @package webtrees
 * @subpackage Admin
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'useradmin.php');
require './includes/session.php';
require_once WT_ROOT.'includes/functions/functions_edit.php';

// Only admin users can access this page
if (!WT_USER_IS_ADMIN) {
	$LOGIN_URL=get_site_setting('LOGIN_URL');
	$loginURL = "$LOGIN_URL?url=".urlencode(WT_SCRIPT_NAME."?".$QUERY_STRING);
	header('Location: '.$loginURL);
	exit;
}

// Valid values for form variables
$ALL_ACTIONS=array('cleanup', 'cleanup2', 'createform', 'createuser', 'deleteuser', 'edituser', 'edituser2', 'listusers');
$ALL_THEMES_DIRS=array();
foreach (get_theme_names() as $themename=>$themedir) {
	$ALL_THEME_DIRS[]=$themedir;
}
$ALL_EDIT_OPTIONS=array(
	'none'=>i18n::translate('None'),
	'access'=>i18n::translate('Access'),
	'edit'=>i18n::translate('Edit'),
	'accept'=>i18n::translate('Accept'),
	'admin'=>i18n::translate('Admin GEDCOM')
);

// Extract form actions (GET overrides POST if both set)
$action                  =safe_POST('action',  $ALL_ACTIONS);
$usrlang                 =safe_POST('usrlang', array_keys(i18n::installed_languages()));
$username                =safe_POST('username', WT_REGEX_USERNAME);
$filter                  =safe_POST('filter'   );
$ged                     =safe_POST('ged'      );

$action                  =safe_GET('action',   $ALL_ACTIONS,                            $action);
$usrlang                 =safe_GET('usrlang',  array_keys(i18n::installed_languages()), $usrlang);
$username                =safe_GET('username', WT_REGEX_USERNAME,                      $username);
$filter                  =safe_GET('filter',   WT_REGEX_NOSCRIPT,                      $filter);
$ged                     =safe_GET('ged',      WT_REGEX_NOSCRIPT,                      $ged);

// Extract form variables
$oldusername             =safe_POST('oldusername',     WT_REGEX_USERNAME);
$oldemailaddress         =safe_POST('oldemailaddress', WT_REGEX_EMAIL);
$realname                =safe_POST('realname'   );
$pass1                   =safe_POST('pass1',        WT_REGEX_PASSWORD);
$pass2                   =safe_POST('pass2',        WT_REGEX_PASSWORD);
$emailaddress            =safe_POST('emailaddress', WT_REGEX_EMAIL);
$user_theme              =safe_POST('user_theme',               $ALL_THEME_DIRS);
$user_language           =safe_POST('user_language',            array_keys(i18n::installed_languages()), WT_LOCALE);
$new_contact_method      =safe_POST('new_contact_method');
$new_default_tab         =safe_POST('new_default_tab',          array_keys(WT_Module::getActiveTabs()), get_gedcom_setting(WT_GED_ID, 'GEDCOM_DEFAULT_TAB'));
$new_comment             =safe_POST('new_comment',              WT_REGEX_UNSAFE);
$new_comment_exp         =safe_POST('new_comment_exp'           );
$new_auto_accept         =safe_POST_bool('new_auto_accept');
$canadmin                =safe_POST_bool('canadmin');
$visibleonline           =safe_POST_bool('visibleonline');
$editaccount             =safe_POST_bool('editaccount');
$verified                =safe_POST_bool('verified');
$verified_by_admin       =safe_POST_bool('verified_by_admin');

if (empty($ged)) {
	$ged=$GEDCOM;
}

// Load all available gedcoms
$all_gedcoms = get_all_gedcoms();
//-- sorting by gedcom filename
asort($all_gedcoms);

// Delete a user
if ($action=='deleteuser') {
	// don't delete ourselves
	$user_id=get_user_id($username);
	if ($user_id!=WT_USER_ID) {
		delete_user($user_id);
		AddToLog("deleted user ->{$username}<-", 'auth');
	}
	// User data is cached, so reload the page to ensure we're up to date
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.WT_SCRIPT_NAME);
	exit;
}

// Save new user info to the database
if ($action=='createuser' || $action=='edituser2') {
	if (($action=='createuser' || $action=='edituser2' && $username!=$oldusername) && get_user_id($username)) {
		print_header(i18n::translate('User administration'));
		echo "<span class=\"error\">", i18n::translate('Duplicate user name.  A user with that user name already exists.  Please choose another user name.'), "</span><br />";
	} elseif (($action=='createuser' || $action=='edituser2' && $emailaddress!=$oldemailaddress) && get_user_by_email($emailaddress)) {
		print_header(i18n::translate('User administration'));
		echo "<span class=\"error\">", i18n::translate('Duplicate email address.  A user with that email already exists.'), "</span><br />";
	} else {
		if ($pass1!=$pass2) {
			print_header(i18n::translate('User administration'));
			echo "<span class=\"error\">", i18n::translate('Passwords do not match.'), "</span><br />";
		} else {
			// New user
			if ($action=='createuser') {
				if ($user_id=create_user($username, $realname, $emailaddress, crypt($pass1))) {
					set_user_setting($user_id, 'reg_timestamp', date('U'));
					set_user_setting($user_id, 'sessiontime', '0');
					AddToLog("User ->{$username}<- created", 'auth');
				} else {
					AddToLog("User ->{$username}<- was not created", 'auth');
					$user_id=get_user_id($username);
				}
			} else {
				$user_id=get_user_id($oldusername);
			}
			// Change password
			if ($action=='edituser2' && !empty($pass1)) {
				set_user_password($user_id, crypt($pass1));
				AddToLog("User ->{$oldusername}<- had password changed", 'auth');
			}
			// Change username
			if ($action=='edituser2' && $username!=$oldusername) {
				rename_user($oldusername, $username);
				AddToLog("User ->{$oldusername}<- renamed to ->{$username}<-", 'auth');
			}
				// Create/change settings that can be updated in the user's gedcom record?
			$email_changed=($emailaddress!=getUserEmail($user_id));
			$newly_verified=($verified_by_admin && !get_user_setting($user_id, 'verified_by_admin'));
			// Create/change other settings
			setUserFullName ($user_id, $realname);
			setUserEmail    ($user_id, $emailaddress);
			set_user_setting($user_id, 'theme',                $user_theme);
			set_user_setting($user_id, 'language',             $user_language);
			set_user_setting($user_id, 'contactmethod',        $new_contact_method);
			set_user_setting($user_id, 'defaulttab',           $new_default_tab);
			set_user_setting($user_id, 'comment',              $new_comment);
			set_user_setting($user_id, 'comment_exp',          $new_comment_exp);
			set_user_setting($user_id, 'auto_accept',          $new_auto_accept);
			set_user_setting($user_id, 'canadmin',             $canadmin);
			set_user_setting($user_id, 'visibleonline',        $visibleonline);
			set_user_setting($user_id, 'editaccount',          $editaccount);
			set_user_setting($user_id, 'verified',             $verified);
			set_user_setting($user_id, 'verified_by_admin',    $verified_by_admin);
			foreach ($all_gedcoms as $ged_id=>$ged_name) {
				set_user_gedcom_setting($user_id, $ged_id, 'gedcomid', safe_POST_xref('gedcomid'.$ged_id));
				set_user_gedcom_setting($user_id, $ged_id, 'rootid',   safe_POST_xref('rootid'.$ged_id));
				set_user_gedcom_setting($user_id, $ged_id, 'canedit',  safe_POST('canedit'.$ged_id, array_keys($ALL_EDIT_OPTIONS)));
				if (safe_POST_xref('gedcomid'.$ged_id)) {
					set_user_gedcom_setting($user_id, $ged_id, 'RELATIONSHIP_PATH_LENGTH',  safe_POST_integer('RELATIONSHIP_PATH_LENGTH'.$ged_id, 0, 10, 0));
				} else {
					// Do not allow a path length to be set if the individual ID is not
					set_user_gedcom_setting($user_id, $ged_id, null);
				}
			}
			// If we're verifying a new user, send them a message to let them know
			if ($newly_verified && $action=='edituser2') {
				i18n::init($user_language);
				$message=array();
				$message["to"]=$username;
				$headers="From: ".$WEBTREES_EMAIL;
				$message["from"]=WT_USER_NAME;
				$message["subject"]=i18n::translate('Approval of account at %s', WT_SERVER_NAME.WT_SCRIPT_PATH);
				$message["body"]=i18n::translate('The administrator at the webtrees site %s has approved your application for an account.  You may now login by accessing the following link: %s', WT_SERVER_NAME.WT_SCRIPT_PATH, WT_SERVER_NAME.WT_SCRIPT_PATH);
				$message["created"]="";
				$message["method"]="messaging2";
				addMessage($message);
				// and send a copy to the admin
				/*
				$message=array();
				$message["to"]=WT_USER_NAME;
				$headers="From: ".$WEBTREES_EMAIL;
				$message["from"]=$username; // fake the from address - so the admin can "reply" to it.
				$message["subject"]=i18n::translate('Approval of account at %s', WT_SERVER_NAME.WT_SCRIPT_PATH));
				$message["body"]=i18n::translate('The administrator at the webtrees site %s has approved your application for an account.  You may now login by accessing the following link: %s', WT_SERVER_NAME.WT_SCRIPT_PATH, WT_SERVER_NAME.WT_SCRIPT_PATH));
				$message["created"]="";
				$message["method"]="messaging2";
				addMessage($message); */
			}
			// Reload the form cleanly, to allow the user to verify their changes
			header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.encode_url("useradmin.php?action=edituser&username={$username}&ged={$ged}", false));
			exit;
		}
	}
} else {
	print_header(i18n::translate('User administration'));

// if ($ENABLE_AUTOCOMPLETE) require WT_ROOT.'js/autocomplete.js.htm'; Removed becasue it doesn't work here for multiple GEDCOMs. Can be reinstated when fixed (https://bugs.launchpad.net/webtrees/+bug/613235)
}

// Print the form to edit a user
if ($action=="edituser") {
	$user_id=get_user_id($username);
	init_calendar_popup();
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
	function checkform(frm) {
		if (frm.username.value=="") {
			alert("<?php echo i18n::translate('You must enter a user name.'); ?>");
			frm.username.focus();
			return false;
		}
		if (frm.realname.value=="") {
			alert("<?php echo i18n::translate('You must enter a real name.'); ?>");
			frm.realname.focus();
			return false;
		}
		if ((frm.pass1.value!="")&&(frm.pass1.value.length < 6)) {
			alert("<?php echo i18n::translate('Passwords must contain at least 6 characters.'); ?>");
			frm.pass1.value = "";
			frm.pass2.value = "";
			frm.pass1.focus();
			return false;
		}
		if ((frm.emailaddress.value!="")&&(frm.emailaddress.value.indexOf("@")==-1)) {
			alert("<?php echo i18n::translate('You must enter an email address.'); ?>");
			frm.emailaddress.focus();
			return false;
		}
		return true;
	}
	var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
	//-->
	</script>
	<?php
	echo '<h2 class="center">', i18n::translate('Update user account'), '</h2>';
	?>

	<form name="editform" method="post" action="useradmin.php" onsubmit="return checkform(this);" autocomplete="off">
		<input type="hidden" name="action" value="edituser2" />
		<input type="hidden" name="filter" value="<?php echo $filter; ?>" />
		<input type="hidden" name="usrlang" value="<?php echo $usrlang; ?>" />
		<input type="hidden" name="oldusername" value="<?php echo $username; ?>" />
		<input type="hidden" name="oldemailaddress" value="<?php echo getUserEmail($user_id); ?>" />
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" value="<?php echo i18n::translate('Update user account'); ?>" />
	<input type="button" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='<?php echo encode_url("useradmin.php?action=listusers&filter={$filter}&usrlang={$usrlang}"); ?>';"/>
	</td></tr>
	<tr>
	<td class="descriptionbox width20 wrap"><?php echo i18n::translate('User name'), help_link('useradmin_username'); ?></td>
	<td class="optionbox wrap"><input type="text" name="username" value="<?php echo $username; ?>" autofocus /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Real name'), help_link('useradmin_realname'); ?></td>
	<td class="optionbox wrap"><input type="text" name="realname" value="<?php echo getUserFullName($user_id); ?>" size="50" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Password'), help_link('useradmin_password'); ?></td>
	<td class="optionbox wrap"><input type="password" name="pass1" /><br /><?php echo i18n::translate('Leave password blank if you want to keep the current password.'); ?></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Confirm password'), help_link('useradmin_conf_password'); ?></td>
	<td class="optionbox wrap"><input type="password" name="pass2" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('GEDCOM INDI record ID'), help_link('useradmin_gedcomid'); ?></td>
	<td class="optionbox wrap">
	<table>
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname='gedcomid'.$ged_id;
		?>
		<tr valign="top">
		<td><?php echo $ged_name; ?></td>
		<td><input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" value="<?php
		$pid=get_user_gedcom_setting($user_id, $ged_id, 'gedcomid');
		echo $pid, '" />';
		print_findindi_link($varname, "", false, false, $ged_name);
		$GEDCOM=$ged_name; // library functions use global variable instead of parameter.
		$person=Person::getInstance($pid);
		if ($person) {
			echo ' <span class="list_item"><a href="', encode_url("individual.php?pid={$pid}&ged={$ged_name}"), '">', PrintReady($person->getFullName()), '</a>', $person->format_first_major_fact(WT_EVENTS_BIRT, 1), $person->format_first_major_fact(WT_EVENTS_DEAT, 1), '</span>';
		}
		echo "</td></tr>";
	}
	?>
	</table></td></tr><tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Pedigree chart root person'), help_link('useradmin_rootid'); ?></td>
	<td class="optionbox wrap">
	<table>
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname='rootid'.$ged_id;
		?>
		<tr valign="top">
		<td><?php echo $ged_name; ?></td>
		<td> <input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" value="<?php
		$pid=get_user_gedcom_setting($user_id, $ged_id, 'rootid');
		echo $pid, "\" />";
		print_findindi_link($varname, "", false, false, $ged_name);
		$GEDCOM=$ged_name; // library functions use global variable instead of parameter.
		$person=Person::getInstance($pid);
		if ($person) {
			echo ' <span class="list_item"><a href="', encode_url("individual.php?pid={$pid}&ged={$ged_name}"), '">', PrintReady($person->getFullName()), '</a>', $person->format_first_major_fact(WT_EVENTS_BIRT, 1), $person->format_first_major_fact(WT_EVENTS_DEAT, 1), '</span>';
		}
		?>
		</td></tr>
		<?php
	} ?></table>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('User can administer'), help_link('useradmin_can_admin'); ?></td>
	<?php
		// Forms won't send the value of checkboxes if they are disabled, so use a hidden field
		echo '<td class="optionbox wrap">';
		echo two_state_checkbox('canadmin', get_user_setting($user_id, 'canadmin'), ($user_id==WT_USER_ID) ? 'disabled="disabled"' : '');
		echo '</td>';
	?>

	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Access level'), help_link('useradmin_can_edit'); ?></td>
	<td class="optionbox wrap">
	<table class="<?php echo $TEXT_DIRECTION; ?>">
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname = 'canedit'.$ged_id;
		echo "<tr><td>$ged_name</td><td>";
		echo "<select name=\"{$varname}\" id=\"{$varname}\">";
		foreach ($ALL_EDIT_OPTIONS as $EDIT_OPTION=>$desc) {
			echo '<option value="', $EDIT_OPTION, '" ';
			if (get_user_gedcom_setting($user_id, $ged_id, 'canedit')==$EDIT_OPTION) {
				echo 'selected="selected" ';
			}
			echo '>', $desc, '</option>';
		}
		echo "</select></td></tr>";
	}
	?>
	</table>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Maximum relationship path length'), help_link('RELATIONSHIP_PATH_LENGTH'); ?></td>
	<td class="optionbox wrap">
	<table>
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname = 'RELATIONSHIP_PATH_LENGTH'.$ged_id;
		echo
			'<tr><td>', $ged_name, '</td><td>',
			'<select name="', $varname, '" id="', $varname, '"',
			// Commented out, until we can get this dynamically updated when the INDI-ID field is edited
			//get_user_gedcom_setting($user_id, $ged_id, 'gedcomid') ? '' : ' disabled="disabled"',
			'>';
		for ($n=0; $n<=10; ++$n) {
			echo
				'<option value="', $n, '"',
				get_user_gedcom_setting($user_id, $ged_id, 'RELATIONSHIP_PATH_LENGTH')==$n ? ' selected="selected"' : '',				
				'>',
				$n ? $n : '',
				'</option>';
		}
		echo "</select></td></tr>";
	}
	?>
	</table>
	</td>
	</tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Automatically accept changes made by this user'), help_link('useradmin_auto_accept'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" value="1" <?php if (get_user_setting($user_id, 'auto_accept')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Email address'), help_link('useradmin_email'); ?></td><td class="optionbox wrap"><input type="text" name="emailaddress" dir="ltr" value="<?php echo getUserEmail($user_id); ?>" size="50" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User verified himself'), help_link('useradmin_verified'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified" value="1" <?php if (get_user_setting($user_id, 'verified')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User approved by admin'), help_link('useradmin_verbyadmin'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" value="1" <?php if (get_user_setting($user_id, 'verified_by_admin')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Change language'), help_link('edituser_change_lang'); ?></td><td class="optionbox wrap" valign="top">
	<?php
		echo edit_field_language('user_language', get_user_setting($user_id, 'language'));

	?>
	</td></tr>
	<?php
	if (get_site_setting('ALLOW_USER_THEMES')) {
		?>
		<tr><td class="descriptionbox wrap" valign="top" align="left"><?php echo i18n::translate('Theme'), help_link('THEME'); ?></td><td class="optionbox wrap" valign="top">
		<select name="user_theme" dir="ltr">
		<option value=""><?php echo i18n::translate('&lt;default theme&gt;'); ?></option>
		<?php
		foreach (get_theme_names() as $themename=>$themedir) {
		echo "<option value=\"", $themedir, "\"";
		if ($themedir == get_user_setting($user_id, 'theme')) echo " selected=\"selected\"";
		echo ">", $themename, "</option>";
		}
		?></select>
		</td>
		</tr>
		<?php
	}
	?>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Preferred contact method'), help_link('useradmin_user_contact'); ?></td>
	<td class="optionbox wrap">
	<?php
		echo edit_field_contact('new_contact_method', get_user_setting(WT_USER_ID, 'contactmethod'));
	?>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Visible to other users when online'), help_link('useradmin_visibleonline'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="visibleonline" value="1" <?php if (get_user_setting($user_id, 'visibleonline')) echo "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Allow this user to edit his account information'), help_link('useradmin_editaccount'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="editaccount" value="1" <?php if (get_user_setting($user_id, 'editaccount')) echo "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Default Tab to show on Individual Information page'), help_link('useradmin_user_default_tab'); ?></td>
	<td class="optionbox wrap">
	<?php echo edit_field_default_tab('new_default_tab', get_user_setting($user_id, 'defaulttab')); ?>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Admin comments on user'), help_link('useradmin_comment'); ?></td>
	<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment"><?php $tmp = PrintReady(get_user_setting($user_id, 'comment')); echo $tmp; ?></textarea></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Admin warning at date'), help_link('useradmin_comment_exp'); ?></td>
	<td class="optionbox wrap"><input type="text" name="new_comment_exp" id="new_comment_exp" value="<?php echo get_user_setting($user_id, 'comment_exp'); ?>" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" value="<?php echo i18n::translate('Update user account'); ?>" />
	<input type="button" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='<?php echo encode_url("useradmin.php?action=listusers&filter={$filter}&usrlang={$usrlang}"); ?>';"/>
	</td></tr>
	</table>
	</form>
	<?php
	print_footer();
	exit;
}

//-- echo out a list of the current users
if ($action == "listusers") {
	$users = get_all_users("asc", "realname");

	// First filter the users, otherwise the javascript to unfold priviledges gets disturbed
	foreach ($users as $user_id=>$user_name) {
		if ($filter == "warnings") {
			if (get_user_setting($user_id, 'comment_exp')) {
				if ((strtotime(get_user_setting($user_id, 'comment_exp')) == "-1") || (strtotime(get_user_setting($user_id, 'comment_exp')) >= time("U"))) unset($users[$user_id]);
			}
			else if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) <= 604800) || get_user_setting($user_id, 'verified')) unset($users[$user_id]);
		}
		else if ($filter == "adminusers") {
			if (!get_user_setting($user_id, 'canadmin')) unset($users[$user_id]);
		}
		else if ($filter == "usunver") {
			if (get_user_setting($user_id, 'verified')) unset($users[$user_id]);
		}
		else if ($filter == "admunver") {
			if ((get_user_setting($user_id, 'verified_by_admin')) || (!get_user_setting($user_id, 'verified'))) {
				unset($users[$user_id]);
			}
		}
		else if ($filter == "language") {
			if (get_user_setting($user_id, 'language') != $usrlang) {
				unset($users[$user_id]);
			}
		}
		else if ($filter == "gedadmin") {
			if (get_user_gedcom_setting($user_id, $ged, 'canedit') != "admin") {
				unset($users[$user_id]);
			}
		}
	}
?>
<script type="text/javascript" src="js/jquery/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function(){
// Table pageing
	jQuery("#user_table")
		.tablesorter({
			sortList: [[2,0],[1,0]], widgets: ['zebra'],
			headers: { 0: { sorter: false }, 9: { sorter: false }}
		})
		.tablesorterPager({
			container: jQuery("#pager"),
			positionFixed: false,
			size: 15
		});
});
//]]>
</script>
<?php
	// Then show the users

	echo '<p class="center"><input TYPE="button" VALUE="', i18n::translate('Return to Administration page'), '" onclick="javascript:window.location=\'admin.php\'" /></p>',
		'<h2 class="center">', i18n::translate('User List'), '</h2>';
	?>
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr>
	<td class="descriptionbox"><a href="useradmin.php?action=createform"><?php echo i18n::translate('Add a new user'); ?></a></td>
	<td class="descriptionbox"><a href="useradmin.php"><?php echo i18n::translate('Back to User Administration'); ?></a></td>
	</tr>
	</table>
	<div class="width80 list_table">
	<table id="user_table" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
	<thead><tr>
	<th><?php echo i18n::translate('Send Message'); ?></th>
	<th><?php echo i18n::translate('Real name'); ?></th>
	<th><?php echo i18n::translate('User name'); ?></th>
	<th><?php echo i18n::translate('Language'); ?></th>
	<th><a href="javascript: <?php echo i18n::translate('Privileges'); ?>" onclick="<?php
	$k = 1;
	for ($i=1, $max=count($users)+1; $i<=$max; $i++) echo "expand_layer('user-geds", $i, "'); ";
	echo " return false;\"><img id=\"user-geds", $k, "_img\" src=\"";
	echo $WT_IMAGES["plus"];
	echo "\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" /></a>";
	echo "<div id=\"user-geds", $k, "\" style=\"display:none\">";
	echo "</div>&nbsp;";
	echo i18n::translate('Privileges'); ?></th>
	<th><?php echo i18n::translate('Date registered'); ?></th>
	<th><?php echo i18n::translate('Last logged in'); ?></th>
	<th><?php echo i18n::translate('User verified himself'); ?></th>
	<th><?php echo i18n::translate('User approved by admin'); ?></th>
	<th><?php echo i18n::translate('Delete'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$k++;
	foreach ($users as $user_id=>$user_name) {
		echo "<tr>";
		echo "<td class=\"optionbox wrap\">";
		if ($user_id!=WT_USER_ID && get_user_setting($user_id, 'contactmethod')!='none') {
			echo "<a href=\"javascript:;\" onclick=\"return message('", $user_name, "');\">", i18n::translate('Send Message'), "</a>";
		} else {
			echo '&nbsp;';
		}
		echo '</td>';
		$userName = getUserFullName($user_id);
		echo "<td class=\"optionbox\"><a class=\"edit_link\" href=\"", encode_url("useradmin.php?action=edituser&username={$user_name}&filter={$filter}&usrlang={$usrlang}&ged={$ged}"), "\" title=\"", i18n::translate('Edit'), "\">", $userName;
		if ($TEXT_DIRECTION=="ltr") echo getLRM();
		else                        echo getRLM();
		echo "</a></td>";
		if (get_user_setting($user_id, "comment_exp")) {
			if ((strtotime(get_user_setting($user_id, "comment_exp")) != "-1") && (strtotime(get_user_setting($user_id, "comment_exp")) < time("U"))) echo "<td class=\"optionbox red\">", $user_name;
			else echo "<td class=\"optionbox wrap\">", $user_name;
		}
		else echo "<td class=\"optionbox wrap\">", $user_name;
		if (get_user_setting($user_id, "comment")) {
			$tempTitle = PrintReady(get_user_setting($user_id, "comment"));
			echo "<br /><img class=\"adminicon\" align=\"top\" alt=\"{$tempTitle}\" title=\"{$tempTitle}\" src=\"{$WT_IMAGES['notes']}\" />";
		}
		echo "</td>";
		echo "<td class=\"optionbox wrap\">", Zend_Locale::getTranslation(get_user_setting($user_id, 'language'), 'language', WT_LOCALE), "</td>";
		echo "<td class=\"optionbox\">";
		echo "<a href=\"javascript: ", i18n::translate('Privileges'), "\" onclick=\"expand_layer('user-geds", $k, "'); return false;\"><img id=\"user-geds", $k, "_img\" src=\"";
		echo $WT_IMAGES["plus"];
		echo "\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" />";
		echo "</a>";
		echo "<div id=\"user-geds", $k, "\" style=\"display:none\">";
		echo "<ul>";
		if (get_user_setting($user_id, 'canadmin')) {
			echo "<li class=\"warning\">", i18n::translate('User can administer'), "</li>";
		}
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			switch (get_user_gedcom_setting($user_id, $ged_id, 'canedit')) {
			case 'admin':  echo '<li class="warning">', i18n::translate('Admin GEDCOM'); break;
			case 'accept': echo '<li class="warning">', i18n::translate('Accept'); break;
			case 'edit':   echo '<li>', i18n::translate('Edit'); break;
			case 'access': echo '<li>', i18n::translate('Access'); break;
			case 'none':
			default:       echo '<li>', i18n::translate('None'); break;
			}
			$uged = get_user_gedcom_setting($user_id, $ged_id, 'gedcomid');
			if ($uged) {
				echo ' <a href="individual.php?pid=', $uged, '&amp;ged=', urlencode($ged_name), '">', $ged_name, '</a></li>';
			} else {
				echo ' ', $ged_name, '</li>';
			}
		}
		echo "</ul>";
		echo "</div>";
		$k++;
		echo "</td>";
		if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && !get_user_setting($user_id, 'verified')) echo "<td class=\"optionbox red\">";
		else echo "<td class=\"optionbox wrap\">";
		echo '<div style="display:none">', (int)get_user_setting($user_id, 'reg_timestamp'), '</div>';
		echo format_timestamp((int)get_user_setting($user_id, 'reg_timestamp'));
		echo "</td>";
		echo "<td class=\"optionbox wrap\">";
		if ((int)get_user_setting($user_id, 'reg_timestamp') > (int)get_user_setting($user_id, 'sessiontime')) {
			echo '<div style="display:none">', (int)get_user_setting($user_id, 'reg_timestamp') - time(), '</div>';
			echo i18n::translate('Never');
		} else {
			echo '<div style="display:none">', (int)get_user_setting($user_id, 'sessiontime'), '</div>';
			echo format_timestamp((int)get_user_setting($user_id, 'sessiontime')), '<br />', i18n::time_ago(time() - (int)get_user_setting($user_id, 'sessiontime'));
		}
		echo '</td><td class="optionbox wrap">';
		echo get_user_setting($user_id, 'verified') ? i18n::translate('Yes') : i18n::translate('No');
		echo '</td><td class="optionbox wrap">';
		echo get_user_setting($user_id, 'verified_by_admin') ? i18n::translate('Yes') : i18n::translate('No');
		echo '</td><td class="optionbox wrap">';
		if (WT_USER_ID!=$user_id) { // You cannot delete yourself
			echo '<a href="useradmin.php?action=deleteuser&amp;username=', urlencode($user_name), '&amp;filter=', urlencode($filter), '&amp;usrlang=', urlencode($usrlang), '&amp;ged=', urlencode($ged), '" onclick="return confirm(\'', i18n::translate('Are you sure you want to delete the user'), ' ', htmlspecialchars($user_name), '\');">', i18n::translate('Delete'), '</a>';
		} else {
			echo '&nbsp;';
		}
		echo '</td></tr>';
		}
	?>

	</tbody>
	</table><br />
	<div id="pager" class="pager">
		<form>
			<img src="<?php echo WT_THEME_DIR; ?>images/jquery/first.png" class="first"/>
			<img src="<?php echo WT_THEME_DIR; ?>images/jquery/prev.png" class="prev"/>
			<input type="text" class="pagedisplay"/>
			<img src="<?php echo WT_THEME_DIR; ?>images/jquery/next.png" class="next"/>
			<img src="<?php echo WT_THEME_DIR; ?>images/jquery/last.png" class="last"/>
			<select class="pagesize">
				<option value="10">10</option>
				<option selected="selected"  value="15">15</option>
				<option value="30">30</option>
				<option value="40">40</option>
				<option  value="50">50</option>
				<option  value="100">100</option>
			</select>
		</form>
	</div>
	</div>
	<?php
	print_footer();
	exit;
}

// -- echo out the form to add a new user
// NOTE: WORKING
if ($action == "createform") {
	init_calendar_popup();
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
		function checkform(frm) {
			if (frm.username.value=="") {
				alert("<?php echo i18n::translate('You must enter a user name.'); ?>");
				frm.username.focus();
				return false;
			}
			if (frm.realname.value=="") {
				alert("<?php echo i18n::translate('You must enter a real name.'); ?>");
				frm.realname.focus();
				return false;
			}
			if (frm.pass1.value=="") {
				alert("<?php echo i18n::translate('You must enter a password.'); ?>");
				frm.pass1.focus();
				return false;
			}
			if (frm.pass2.value=="") {
				alert("<?php echo i18n::translate('You must confirm the password.'); ?>");
				frm.pass2.focus();
				return false;
			}
			if (frm.pass1.value.length < 6) {
				alert("<?php echo i18n::translate('Passwords must contain at least 6 characters.'); ?>");
				frm.pass1.value = "";
				frm.pass2.value = "";
				frm.pass1.focus();
				return false;
			}
			if (frm.emailaddress.value.indexOf("@")==-1) {
				alert("<?php echo i18n::translate('You must enter an email address.'); ?>");
				frm.emailaddress.focus();
				return false;
			}
			return true;
		}
		var pastefield;
		function paste_id(value) {
			pastefield.value=value;
		}
	//-->
	</script>

	<form name="newform" method="post" action="useradmin.php" onsubmit="return checkform(this);" autocomplete="off">
	<input type="hidden" name="action" value="createuser" />
	<!--table-->
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="2">
		<h2><?php echo i18n::translate('Add a new user'); ?></h2>
		</td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" value="<?php echo i18n::translate('Create User'); ?>" />
	<input type="button" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='useradmin.php';"/>
	</td></tr>
		<tr><td class="descriptionbox wrap width20"><?php echo i18n::translate('User name'), help_link('useradmin_username'); ?></td><td class="optionbox wrap"><input type="text" name="username" autofocus /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Real name'), help_link('useradmin_realname'); ?></td><td class="optionbox wrap"><input type="text" name="realname" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Password'), help_link('useradmin_password'); ?></td><td class="optionbox wrap"><input type="password" name="pass1" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Confirm password'), help_link('useradmin_conf_password'); ?></td><td class="optionbox wrap"><input type="password" name="pass2" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('GEDCOM INDI record ID'), help_link('useradmin_gedcomid'); ?></td><td class="optionbox wrap">

		<table class="<?php echo $TEXT_DIRECTION; ?>">
		<?php
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$varname='gedcomid'.$ged_id;
			?>
			<tr>
			<td><?php echo $ged_name; ?></td>
			<td><input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" value="<?php
			echo "\" />";
			print_findindi_link($varname, "", false, false, $ged_name);
			echo "</td></tr>";
		}
		?>
		</table>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Pedigree chart root person'), help_link('useradmin_rootid'); ?></td><td class="optionbox wrap">
		<table>
		<?php
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$varname='rootid'.$ged_id;
			?>
			<tr>
			<td><?php echo $ged_name; ?></td>
			<td><input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" value="<?php
			echo "\" />";
			print_findindi_link($varname, "", false, false, $ged_name);
			echo "</td></tr>";
		}
		echo "</table>";
		?>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User can administer'), help_link('useradmin_can_admin'); ?></td><td class="optionbox wrap"><input type="checkbox" name="canadmin" value="1" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Access level'), help_link('useradmin_can_edit'); ?></td><td class="optionbox wrap">
		<table>
		<?php
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$varname='canedit'.$ged_id;
			echo "<tr><td>{$ged_name}</td><td>";
			echo "<select name=\"$varname\">";
			echo "<option value=\"none\" selected=\"selected\"";
			echo ">", i18n::translate('None'), "</option>";
			echo "<option value=\"access\"";
			echo ">", i18n::translate('Access'), "</option>";
			echo "<option value=\"edit\"";
			echo ">", i18n::translate('Edit'), "</option>";
			echo "<option value=\"accept\"";
			echo ">", i18n::translate('Accept'), "</option>";
			echo "<option value=\"admin\"";
			echo ">", i18n::translate('Admin GEDCOM'), "</option>";
			echo "</select></td></tr>";
		}
		?>
		</table>
		</td></tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Maximum relationship path length'), help_link('RELATIONSHIP_PATH_LENGTH'); ?></td>
	<td class="optionbox wrap">
	<table>
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname = 'RELATIONSHIP_PATH_LENGTH'.$ged_id;
		echo "<tr><td>$ged_name</td><td>";
		echo "<select name=\"{$varname}\" id=\"{$varname}\">";
		for ($n=0; $n<=10; ++$n) {
			echo
				'<option value="', $n, '">',
				$n ? $n : '',
				'</option>';
		}
		echo "</select></td></tr>";
	}
	?>
	</table>
	</td>
	</tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Automatically accept changes made by this user'), help_link('useradmin_auto_accept'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" value="1" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Email address'), help_link('useradmin_email');  ?></td><td class="optionbox wrap"><input type="text" name="emailaddress" value="" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User verified himself'), help_link('useradmin_verified'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified" value="1" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User approved by admin'), help_link('useradmin_verbyadmin');  ?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" value="1" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Change language'), help_link('useradmin_change_lang'); ?></td><td class="optionbox wrap" valign="top"><?php

		echo edit_field_language('user_language', get_user_setting(WT_USER_ID, 'language'));
		?></td></tr>
		<?php if (get_site_setting('ALLOW_USER_THEMES')) { ?>
			<tr><td class="descriptionbox wrap" valign="top" align="left"><?php echo i18n::translate('Theme'), help_link('THEME'); ?></td><td class="optionbox wrap" valign="top">
			<select name="new_user_theme">
			<option value="" selected="selected"><?php echo i18n::translate('Site Default'); ?></option>
			<?php
			foreach (get_theme_names() as $themename=>$themedir) {
				echo "<option value=\"", $themedir, "\"";
				echo ">", $themename, "</option>";
			}
			?>
			</select>
			</td></tr>
		<?php } ?>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Preferred contact method'), help_link('useradmin_user_contact');  ?></td>
			<td class="optionbox wrap">
	<?php
		echo edit_field_contact('new_contact_method', get_site_setting('STORE_MESSAGES') ? 'messaging2' : 'messaging3');
	?>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Visible to other users when online'), help_link('useradmin_visibleonline'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="visibleonline" value="1" <?php echo "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Allow this user to edit his account information'), help_link('useradmin_editaccount'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="editaccount" value="1" <?php echo "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Default Tab to show on Individual Information page'), help_link('useradmin_user_default_tab'); ?></td>
			<td class="optionbox wrap">
			<?php echo edit_field_default_tab('new_default_tab', get_gedcom_setting(WT_GED_ID, 'GEDCOM_DEFAULT_TAB')); ?>
			</td>
		</tr>
		<?php if (WT_USER_IS_ADMIN) { ?>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Admin comments on user'), help_link('useradmin_comment'); ?></td>
			<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment"></textarea></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Admin warning at date'), help_link('useradmin_comment_exp'); ?></td>
			<td class="optionbox wrap"><input type="text" name="new_comment_exp" id="new_comment_exp" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
		</tr>
		<?php } ?>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" value="<?php echo i18n::translate('Create User'); ?>" />
	<input type="button" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='useradmin.php';"/>
	</td></tr></table>
	</form>
	<?php
	print_footer();
	exit;
}

// Cleanup users and user rights
//NOTE: WORKING
if ($action == "cleanup") {
	?>
	<form name="cleanupform" method="post" action="useradmin.php">
	<input type="hidden" name="action" value="cleanup2" />
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="2">
		<h2><?php echo i18n::translate('Cleanup users'); ?></h2>
		</td>
	</tr>
	<?php
	// Check for idle users
	//if (!isset($month)) $month = 1;
	$month = safe_GET_integer('month', 1, 12, 6);
	echo "<tr><td class=\"descriptionbox\">", i18n::translate('Number of months since the last login for a user\'s account to be considered inactive: '), "</td>";
	echo "<td class=\"optionbox\"><select onchange=\"document.location=options[selectedIndex].value;\">";
	for ($i=1; $i<=12; $i++) {
		echo "<option value=\"useradmin.php?action=cleanup&amp;month=$i\"";
		if ($i == $month) echo " selected=\"selected\"";
		echo " >", $i, "</option>";
	}
	echo "</select></td></tr>";
	?>
	<tr><td class="topbottombar" colspan="2"><?php echo i18n::translate('Options:'); ?></td></tr>
	<?php
	// Check users not logged in too long
	$ucnt = 0;
	foreach (get_all_users() as $user_id=>$user_name) {
		$userName = getUserFullName($user_id);
		if ((int)get_user_setting($user_id, 'sessiontime') == "0")
			$datelogin = (int)get_user_setting($user_id, 'reg_timestamp');
		else
			$datelogin = (int)get_user_setting($user_id, 'sessiontime');
		if ((mktime(0, 0, 0, (int)date("m")-$month, (int)date("d"), (int)date("Y")) > $datelogin) && get_user_setting($user_id, 'verified') && get_user_setting($user_id, 'verified_by_admin')) {
			?><tr><td class="descriptionbox"><?php echo $user_name, " - ", $userName, ":&nbsp;&nbsp;", i18n::translate('User\'s account has been inactive too long: ');
			echo timestamp_to_gedcom_date($datelogin)->Display(false);
			$ucnt++;
			?></td><td class="optionbox"><input type="checkbox" name="<?php echo "del_", str_replace(array(".", "-", " "), array("_", "_", "_"), $user_name); ?>" value="1" /></td></tr><?php
		}
	}

	// Check unverified users
	foreach (get_all_users() as $user_id=>$user_name) {
		if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && !get_user_setting($user_id, 'verified')) {
			$userName = getUserFullName($user_id);
			?><tr><td class="descriptionbox"><?php echo $user_name, " - ", $userName, ":&nbsp;&nbsp;", i18n::translate('User didn\'t verify within 7 days.');
			$ucnt++;
			?></td><td class="optionbox"><input type="checkbox" checked="checked" name="<?php echo "del_", str_replace(array(".", "-", " "), array("_",  "_", "_"), $user_name); ?>" value="1" /></td></tr><?php
		}
	}

	// Check users not verified by admin
	foreach (get_all_users() as $user_id=>$user_name) {
		if (!get_user_setting($user_id, 'verified_by_admin') && get_user_setting($user_id, 'verified')) {
			$userName = getUserFullName($user_id);
			?><tr><td  class="descriptionbox"><?php echo $user_name, " - ", $userName, ":&nbsp;&nbsp;", i18n::translate('User not verified by administrator.');
			?></td><td class="optionbox"><input type="checkbox" name="<?php echo "del_", str_replace(array(".", "-", " "), array("_", "_", "_"), $user_name); ?>" value="1" /></td></tr><?php
			$ucnt++;
		}
	}
	if ($ucnt == 0) {
		echo "<tr><td class=\"warning\">";
		echo i18n::translate('Nothing found to cleanup'), "</td></tr>";
	} ?>

	<tr><td class="topbottombar" colspan="2">
	<?php
	if ($ucnt >0) {
		?><input type="submit" value="<?php echo i18n::translate('Continue'); ?>" />&nbsp;<?php
	} ?>
	<input type="button" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='useradmin.php';"/>
	</td></tr></table>
	</form><?php
	print_footer();
	exit;
}
// NOTE: No table parts
if ($action == "cleanup2") {
	foreach (get_all_users() as $user_id=>$user_name) {
		$var = "del_".str_replace(array(".", "-", " "), array("_", "_", "_"), $user_name);
		if (safe_POST($var)=='1') {
			delete_user($user_id);
			AddToLog("deleted user ->{$user_name}<-", 'auth');
			echo i18n::translate('Deleted user: '); echo $user_name, "<br />";
		} else {
			$tempArray = unserialize(get_user_setting($user_id, 'canedit'));
			if (is_array($tempArray)) {
				foreach ($tempArray as $gedid=>$data) {
					$var = "delg_".str_replace(array(".", "-", " "), "_", $gedid);
					if (safe_POST($var)=='1' && get_user_gedcom_setting($user_id, $gedid, 'canedit')) {
						set_user_gedcom_setting($user_id, $gedid, 'canedit', null);
						echo $gedid, ":&nbsp;&nbsp;", i18n::translate('Unset GEDCOM rights for '), $user_name, "<br />";
					}
				}
			}
			$tempArray = unserialize(get_user_setting($user_id, 'rootid'));
			if (is_array($tempArray)) {
				foreach ($tempArray as $gedid=>$data) {
					$var = "delg_".str_replace(array(".", "-", " "), "_", $gedid);
					if (safe_POST($var)=='1' && get_user_gedcom_setting($user_id, $gedid, 'rootid')) {
						set_user_gedcom_setting($user_id, $gedid, 'rootid', null);
						echo $gedid, ":&nbsp;&nbsp;", i18n::translate('Unset root ID for '), $user_name, "<br />";
					}
				}
			}
			$tempArray = unserialize(get_user_setting($user_id, 'gedcomid'));
			if (is_array($tempArray)) {
				foreach ($tempArray as $gedid=>$data) {
					$var = "delg_".str_replace(array(".", "-", " "), "_", $gedid);
					if (safe_POST($var)=='1' && get_user_gedcom_setting($user_id, $gedid, 'gedcomid')) {
						set_user_gedcom_setting($user_id, $gedid, 'gedcomid', null);
						echo $gedid, ":&nbsp;&nbsp;", i18n::translate('Unset GEDCOM ID for '), $user_name, "<br />";
					}
				}
			}
		}
	}
	echo "<br />";
}

echo '<p class="center"><input TYPE="button" VALUE="', i18n::translate('Return to Administration page'), '" onclick="javascript:window.location=\'admin.php\'" /></p>',
	'<h2 class="center">', i18n::translate('User administration'), '</h2>';
?>
<table class="center list_table width40 <?php echo $TEXT_DIRECTION; ?>">
	<tr>
		<td colspan="3" class="topbottombar"><?php echo i18n::translate('Select an option below:'); ?></td>
	</tr>
	<tr>
		<td class="optionbox"><a href="useradmin.php?action=listusers"><?php echo i18n::translate('User List'); ?></a></td>
		<td class="optionbox width50" colspan="2" ><a href="useradmin.php?action=createform"><?php echo i18n::translate('Add a new user'); ?></a></td>
	</tr>
	<tr>
		<td class="optionbox"><a href="useradmin.php?action=cleanup"><?php echo i18n::translate('Cleanup users'); ?></a></td>
		<td class="optionbox" colspan="2" ><a href="javascript: <?php echo i18n::translate('Send message to all users'); ?>" onclick="message('all', 'messaging2', '', ''); return false;"><?php echo i18n::translate('Send message to all users'); ?></a></td>
	</tr>
		<td class="optionbox" colspan="3"><a href="javascript: <?php echo i18n::translate('Send message to users who have never logged in'); ?>" onclick="message('never_logged', 'messaging2', '', ''); return false;"><?php echo i18n::translate('Send message to users who have never logged in'); ?></a></td>
	</tr>
	<tr>
		<td class="optionbox" colspan="3"><a href="javascript: <?php echo i18n::translate('Send message to users who have not logged in for 6 months'); ?>" onclick="message('last_6mo', 'messaging2', '', ''); return false;"><?php echo i18n::translate('Send message to users who have not logged in for 6 months'); ?></a></td>
	</tr>
	<tr>
		<td colspan="3" class="topbottombar"><?php echo i18n::translate('Informational'); ?></td>
	</tr>
	<tr>
	<td class="optionbox" colspan="3">
	<?php
	$totusers = 0;       // Total number of users
	$warnusers = 0;      // Users with warning
	$applusers = 0;      // Users who have not verified themselves
	$nverusers = 0;      // Users not verified by admin but verified themselves
	$adminusers = 0;     // Administrators
	$userlang = array(); // Array for user languages
	$gedadmin = array(); // Array for gedcom admins
	foreach (get_all_users() as $user_id=>$user_name) {
		$totusers = $totusers + 1;
		if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && !get_user_setting($user_id, 'verified')) $warnusers++;
		else {
			if (get_user_setting($user_id, 'comment_exp')) {
				if ((strtotime(get_user_setting($user_id, 'comment_exp')) != "-1") && (strtotime(get_user_setting($user_id, 'comment_exp')) < time("U"))) $warnusers++;
			}
		}
		if (!get_user_setting($user_id, 'verified_by_admin') && get_user_setting($user_id, 'verified')) {
			$nverusers++;
		}
		if (!get_user_setting($user_id, 'verified')) {
			$applusers++;
		}
		if (get_user_setting($user_id, 'canadmin')) {
			$adminusers++;
		}
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			if (get_user_gedcom_setting($user_id, $ged_id, 'canedit')=='admin') {
				$title=PrintReady(strip_tags(get_gedcom_setting($ged_id, 'title')));
				if (isset($gedadmin[$title])) {
					$gedadmin[$title]["number"]++;
				} else {
					$gedadmin[$title]["name"] = $title;
					$gedadmin[$title]["number"] = 1;
					$gedadmin[$title]["ged"] = $ged_name;
				}
			}
		}
		if ($user_lang=get_user_setting($user_id, 'language')) {
			if (isset($userlang[$user_lang]))
				$userlang[$user_lang]["number"]++;
			else {
				$userlang[$user_lang]["langname"] = Zend_Locale::getTranslation($user_lang, 'language', WT_LOCALE);
				$userlang[$user_lang]["number"] = 1;
			}
		}
	}
	echo "<table class=\"width100 $TEXT_DIRECTION\">";
	echo "<tr><td class=\"font11\">", i18n::translate('Total number of users'), "</td><td class=\"font11\">", $totusers, "</td></tr>";

	echo "<tr><td class=\"font11\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	if ($adminusers == 0) echo i18n::translate('Site Administrators');
	else echo "<a href=\"useradmin.php?action=listusers&amp;filter=adminusers\">", i18n::translate('Site Administrators'), "</a>";
	echo "</td><td class=\"font11\">", $adminusers, "</td></tr>";

	echo "<tr><td class=\"font11\">", i18n::translate('GEDCOM Administrators'), "</td></tr>";
	asort($gedadmin);
	$ind = 0;
	foreach ($gedadmin as $key=>$geds) {
		if ($ind !=0) echo "<td class=\"font11\"></td>";
		$ind = 1;
		echo "<tr><td class=\"font11\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($geds["number"] == 0) echo $geds["name"];
		else echo "<a href=\"", encode_url("useradmin.php?action=listusers&filter=gedadmin&ged=".$geds["ged"]), "\">", $geds["name"], "</a>";
		echo "</td><td class=\"font11\">", $geds["number"], "</td></tr>";
	}
	echo "<tr><td class=\"font11\"></td></tr><tr><td class=\"font11\">";
	if ($warnusers == 0) echo i18n::translate('Users with warnings');
	else echo "<a href=\"useradmin.php?action=listusers&amp;filter=warnings\">", i18n::translate('Users with warnings'), "</a>";
	echo "</td><td class=\"font11\">", $warnusers, "</td></tr>";

	echo "<tr><td class=\"font11\">";
	if ($applusers == 0) echo i18n::translate('Unverified by User');
	else echo "<a href=\"useradmin.php?action=listusers&amp;filter=usunver\">", i18n::translate('Unverified by User'), "</a>";
	echo "</td><td class=\"font11\">", $applusers, "</td></tr>";

	echo "<tr><td class=\"font11\">";
	if ($nverusers == 0) echo i18n::translate('Unverified by Administrator');
	else echo "<a href=\"useradmin.php?action=listusers&amp;filter=admunver\">", i18n::translate('Unverified by Administrator'), "</a>";
	echo "</td><td class=\"font11\">", $nverusers, "</td></tr>";

	echo "<tr valign=\"middle\"><td class=\"font11\">", i18n::translate('Users\' languages'), "</td>";
	foreach ($userlang as $key=>$ulang) {
		echo '<td><a href="useradmin.php?action=listusers&amp;filter=language&amp;usrlang=', $key, '">', $ulang['langname'], '</a></td><td>', $ulang['number'], '</td></tr><tr class="vmiddle"><td></td>';
	}
	echo "</tr></table>";
	echo "</td></tr></table>";
	?>
<?php
print_footer();
