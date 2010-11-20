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

define('WT_SCRIPT_NAME', 'administration/user_admin.php');
require '../includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';
require WT_ROOT.'administration/admin_functions.php';

// Only admin users can access this page
//if (!WT_USER_IS_ADMIN) {
//	$LOGIN_URL=get_site_setting('LOGIN_URL');
//	$loginURL = "$LOGIN_URL?url=".rawurlencode(WT_SCRIPT_NAME."?".$QUERY_STRING);
//	header("Location: $loginURL");
//	exit;
//}

// Valid values for form variables
$ALL_ACTIONS=array('cleanup', 'cleanup2', 'createform', 'createuser', 'deleteuser', 'edituser', 'edituser2', 'listusers');
$ALL_THEMES_DIRS=array();
foreach (admin_get_theme_names() as $themename=>$themedir) {
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
$sort                    =safe_POST('sort'     );
$ged                     =safe_POST('ged'      );

$action                  =safe_GET('action',   $ALL_ACTIONS,                            $action);
$usrlang                 =safe_GET('usrlang',  array_keys(i18n::installed_languages()), $usrlang);
$username                =safe_GET('username', WT_REGEX_USERNAME,                      $username);
$filter                  =safe_GET('filter',   WT_REGEX_NOSCRIPT,                      $filter);
$sort                    =safe_GET('sort',     WT_REGEX_NOSCRIPT,                      $sort);
$ged                     =safe_GET('ged',      WT_REGEX_NOSCRIPT,                      $ged);

// Extract form variables
$oldusername             =safe_POST('oldusername',  WT_REGEX_USERNAME);
$realname                =safe_POST('realname'   );
$pass1                   =safe_POST('pass1',        WT_REGEX_PASSWORD);
$pass2                   =safe_POST('pass2',        WT_REGEX_PASSWORD);
$emailaddress            =safe_POST('emailaddress', WT_REGEX_EMAIL);
$user_theme              =safe_POST('user_theme',               $ALL_THEME_DIRS);
$user_language           =safe_POST('user_language',            array_keys(i18n::installed_languages()), WT_LOCALE);
$new_contact_method      =safe_POST('new_contact_method');
$new_default_tab         =safe_POST('new_default_tab',          array_keys(WT_Module::getActiveTabs()), $GEDCOM_DEFAULT_TAB);
$new_comment             =safe_POST('new_comment',              WT_REGEX_UNSAFE);
$new_comment_exp         =safe_POST('new_comment_exp'           );
$new_max_relation_path   =safe_POST_integer('new_max_relation_path', 1, $MAX_RELATION_PATH_LENGTH, 2);
$new_relationship_privacy=safe_POST_bool('new_relationship_privacy');
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

// Save new user info to the database
if ($action=='createuser' || $action=='edituser2') {
	if (($action=='createuser' || $action=='edituser2' && $username!=$oldusername) && get_user_id($username)) {
		admin_header(i18n::translate('User administration'));
		echo "<span class=\"error\">", i18n::translate('Duplicate user name.  A user with that user name already exists.  Please choose another user name.'), "</span><br />";
	} else {
		if ($pass1!=$pass2) {
			admin_header(i18n::translate('User administration'));
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
			set_user_setting($user_id, 'max_relation_path',    $new_max_relation_path);
			set_user_setting($user_id, 'relationship_privacy', $new_relationship_privacy);
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
/*				$message=array();
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
			header("Location: user_admin.php?action=edituser&username=".rawurlencode($username)."&ged=".rawurlencode($ged));
			exit;
		}
	}
} else {
	admin_header(i18n::translate('User administration'));

	if ($ENABLE_AUTOCOMPLETE) require '../js/autocomplete.js.htm';
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

	<form name="editform" method="post" action="user_admin.php" onsubmit="return checkform(this);" autocomplete="off">
		<input type="hidden" name="action" value="edituser2" />
		<input type="hidden" name="filter" value="<?php echo $filter; ?>" />
		<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
		<input type="hidden" name="usrlang" value="<?php echo $usrlang; ?>" />
		<input type="hidden" name="oldusername" value="<?php echo $username; ?>" />
		<?php $tab=0; ?>
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Update user account'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='<?php echo "user_admin.php?action=listusers&amp;sort={$sort}&amp;filter={$filter}&amp;usrlang={$usrlang}"; ?>';"/>
	</td></tr>
	<tr>
	<td class="descriptionbox width20 wrap"><?php echo i18n::translate('User name'), help_link('useradmin_username'); ?></td>
	<td class="optionbox wrap"><input type="text" name="username" tabindex="<?php echo ++$tab; ?>" value="<?php echo $username; ?>" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Real name'), help_link('useradmin_realname'); ?></td>
	<td class="optionbox wrap"><input type="text" name="realname" tabindex="<?php echo ++$tab; ?>" value="<?php echo getUserFullName($user_id); ?>" size="50" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Password'), help_link('useradmin_password'); ?></td>
	<td class="optionbox wrap"><input type="password" name="pass1" tabindex="<?php echo ++$tab; ?>" /><br /><?php echo i18n::translate('Leave password blank if you want to keep the current password.'); ?></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Confirm password'), help_link('useradmin_conf_password'); ?></td>
	<td class="optionbox wrap"><input type="password" name="pass2" tabindex="<?php echo ++$tab; ?>" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('GEDCOM INDI record ID'), help_link('useradmin_gedcomid'); ?></td>
	<td class="optionbox wrap">
	<table class="<?php echo $TEXT_DIRECTION; ?>">
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname='gedcomid'.$ged_id;
		?>
		<tr valign="top">
		<td><?php echo $ged_name; ?>:&nbsp;&nbsp;</td>
		<td><input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" tabindex="<?php echo ++$tab; ?>" value="<?php
		$pid=get_user_gedcom_setting($user_id, $ged_id, 'gedcomid');
		echo $pid, "\" />";
		print_findindi_link($varname, "", false, false, $ged_name);
		$GEDCOM=$ged_name; // library functions use global variable instead of parameter.
		$person=Person::getInstance($pid);
		if ($person) {
			echo ' <span class="list_item"><a href="', $person->getHtmlUrl(), '">', PrintReady($person->getFullName()), '</a>', $person->format_first_major_fact(WT_EVENTS_BIRT, 1), $person->format_first_major_fact(WT_EVENTS_DEAT, 1), '</span>';
		}
		echo "</td></tr>";
		} ?> 
	</table></td></tr><tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Pedigree chart root person'), help_link('useradmin_rootid'); ?></td>
	<td class="optionbox wrap">
	<table class="<?php echo $TEXT_DIRECTION; ?>">
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname='rootid'.$ged_id;
		?>
		<tr valign="top">
		<td><?php echo $ged_name; ?>:&nbsp;&nbsp;</td>
		<td> <input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" tabindex="<?php echo ++$tab; ?>" value="<?php
		$pid=get_user_gedcom_setting($user_id, $ged_id, 'rootid');
		echo $pid, "\" />";
		print_findindi_link($varname, "", false, false, $ged_name);
		$GEDCOM=$ged_name; // library functions use global variable instead of parameter.
		$person=Person::getInstance($pid);
		if ($person) {
			echo ' <span class="list_item"><a href="', $person->getHtmlUrl(), '">', PrintReady($person->getFullName()), '</a>', $person->format_first_major_fact(WT_EVENTS_BIRT, 1), $person->format_first_major_fact(WT_EVENTS_DEAT, 1), '</span>';
		}
		?>
		</td></tr>
		<?php } ?>
	</table>
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
		echo "<tr><td>$ged_name:&nbsp;&nbsp;</td><td>";
		$tab++;
		echo "<select name=\"{$varname}\" id=\"{$varname}\" tabindex=\"{$tab}\">\n";
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
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Automatically accept changes made by this user'), help_link('useradmin_auto_accept'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" tabindex="<?php echo ++$tab; ?>" value="1" <?php if (get_user_setting($user_id, 'auto_accept')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Limit access to related people'), help_link('useradmin_relation_priv'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_relationship_privacy" tabindex="<?php echo ++$tab; ?>" value="1" <?php if (get_user_setting($user_id, 'relationship_privacy')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Max relationship privacy path length'), help_link('useradmin_path_length'); ?></td>
	<td class="optionbox wrap"><input type="text" name="new_max_relation_path" tabindex="<?php echo ++$tab; ?>" value="<?php echo get_user_setting($user_id, 'max_relation_path'); ?>" size="5" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Email address'), help_link('useradmin_email'); ?></td><td class="optionbox wrap"><input type="text" name="emailaddress" tabindex="<?php echo ++$tab; ?>" dir="ltr" value="<?php echo getUserEmail($user_id); ?>" size="50" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User verified himself'), help_link('useradmin_verified'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified" tabindex="<?php echo ++$tab; ?>" value="1" <?php if (get_user_setting($user_id, 'verified')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User approved by admin'), help_link('useradmin_verbyadmin'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" tabindex="<?php echo ++$tab; ?>" value="1" <?php if (get_user_setting($user_id, 'verified_by_admin')) echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Change language'), help_link('edituser_change_lang'); ?></td><td class="optionbox wrap" valign="top">
	<?php
		echo edit_field_language('user_language', get_user_setting($user_id, 'language'), 'tabindex="'.(++$tab).'"');

	?>
	</td></tr>
	<?php
	if (get_site_setting('ALLOW_USER_THEMES')) {
		?>
		<tr><td class="descriptionbox wrap" valign="top" align="left"><?php echo i18n::translate('My Theme'), help_link('useradmin_user_theme'); ?></td><td class="optionbox wrap" valign="top">
		<select name="user_theme" tabindex="<?php echo ++$tab; ?>" dir="ltr">
		<option value=""><?php echo i18n::translate('Site Default'); ?></option>
		<?php
		foreach(get_theme_names() as $themename=>$themedir) {
		echo "<option value=\"", $themedir, "\"";
		if ($themedir == get_user_setting($user_id, 'theme')) echo " selected=\"selected\"";
		echo ">", $themename, "</option>\n";
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
		echo edit_field_contact('new_contact_method', get_user_setting(WT_USER_ID, 'contactmethod'), 'tabindex="'.(++$tab).'"');
	?>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Visible to other users when online'), help_link('useradmin_visibleonline'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="visibleonline" tabindex="<?php echo ++$tab; ?>" value="1" <?php if (get_user_setting($user_id, 'visibleonline')) echo "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Allow this user to edit his account information'), help_link('useradmin_editaccount'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="editaccount" tabindex="<?php echo ++$tab; ?>" value="1" <?php if (get_user_setting($user_id, 'editaccount')) echo "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Default Tab to show on Individual Information page'), help_link('useradmin_user_default_tab'); ?></td>
	<td class="optionbox wrap">
	<?php echo edit_field_default_tab('new_default_tab', get_user_setting($user_id, 'defaulttab'), 'tabindex="'.(++$tab).'"'); ?>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Admin comments on user'), help_link('useradmin_comment'); ?></td>
	<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment" tabindex="<?php echo ++$tab; ?>" ><?php $tmp = PrintReady(get_user_setting($user_id, 'comment')); echo $tmp; ?></textarea></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php echo i18n::translate('Admin warning at date'), help_link('useradmin_comment_exp'); ?></td>
	<td class="optionbox wrap"><input type="text" name="new_comment_exp" id="new_comment_exp" tabindex="<?php echo ++$tab; ?>" value="<?php echo get_user_setting($user_id, 'comment_exp'); ?>" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Update user account'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='<?php echo "user_admin.php?action=listusers&amp;sort={$sort}&amp;filter={$filter}&amp;usrlang={$usrlang}"; ?>';"/>
	</td></tr>
	</table>
	</form>
	<?php
	include 'admin_footer.php';
	exit;
}

// -- echo out the form to add a new user
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

	<form name="newform" method="post" action="user_admin.php" onsubmit="return checkform(this);" autocomplete="off">
	<input type="hidden" name="action" value="createuser" />
	<!--table-->
	<?php $tab = 0; ?>
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="2">
		<h2><?php echo i18n::translate('Add a new user'); ?></h2>
		</td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Create User'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='user_admin.php';"/>
	</td></tr>
		<tr><td class="descriptionbox wrap width20"><?php echo i18n::translate('User name'), help_link('useradmin_username'); ?></td><td class="optionbox wrap"><input type="text" name="username" tabindex="<?php echo ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Real name'), help_link('useradmin_realname'); ?></td><td class="optionbox wrap"><input type="text" name="realname" tabindex="<?php echo ++$tab; ?>" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Password'), help_link('useradmin_password'); ?></td><td class="optionbox wrap"><input type="password" name="pass1" tabindex="<?php echo ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Confirm password'), help_link('useradmin_conf_password'); ?></td><td class="optionbox wrap"><input type="password" name="pass2" tabindex="<?php echo ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('GEDCOM INDI record ID'), help_link('useradmin_gedcomid'); ?></td><td class="optionbox wrap">

		<table class="<?php echo $TEXT_DIRECTION; ?>">
		<?php
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$varname='gedcomid'.$ged_id;
			?>
			<tr>
			<td><?php echo $ged_name; ?>:&nbsp;&nbsp;</td>
			<td><input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" tabindex="<?php echo ++$tab; ?>" value="<?php
			echo "\" />";
			print_findindi_link($varname, "", false, false, $ged_name);
			echo "</td></tr>";
		}
		?>
		</table>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Pedigree chart root person'), help_link('useradmin_rootid'); ?></td><td class="optionbox wrap">
		<table class="<?php echo $TEXT_DIRECTION; ?>">
		<?php
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$varname='rootid'.$ged_id;
			?>
			<tr>
			<td><?php echo $ged_name; ?>:&nbsp;&nbsp;</td>
			<td><input type="text" name="<?php echo $varname; ?>" id="<?php echo $varname; ?>" tabindex="<?php echo ++$tab; ?>" value="<?php
			echo "\" />\n";
			print_findindi_link($varname, "", false, false, $ged_name);
			echo "</td></tr>\n";
		}
		echo "</table>";
		?>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User can administer'), help_link('useradmin_can_admin'); ?></td><td class="optionbox wrap"><input type="checkbox" name="canadmin" tabindex="<?php echo ++$tab; ?>" value="1" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Access level'), help_link('useradmin_can_edit'); ?></td><td class="optionbox wrap">
		<table class="<?php echo $TEXT_DIRECTION; ?>">
		<?php
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$varname='canedit'.$ged_id;
			$tab++;
			echo "<tr><td>{$ged_name}:&nbsp;&nbsp;</td><td>";
			echo "<select name=\"$varname\" tabindex=\"", $tab, "\">\n";
			echo "<option value=\"none\" selected=\"selected\"";
			echo ">", i18n::translate('None'), "</option>\n";
			echo "<option value=\"access\"";
			echo ">", i18n::translate('Access'), "</option>\n";
			echo "<option value=\"edit\"";
			echo ">", i18n::translate('Edit'), "</option>\n";
			echo "<option value=\"accept\"";
			echo ">", i18n::translate('Accept'), "</option>\n";
			echo "<option value=\"admin\"";
			echo ">", i18n::translate('Admin GEDCOM'), "</option>\n";
			echo "</select></td></tr>\n";
		}
		?>
		</table>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Automatically accept changes made by this user'), help_link('useradmin_auto_accept'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" tabindex="<?php echo ++$tab; ?>" value="1" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Limit access to related people'), help_link('useradmin_relation_priv'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_relationship_privacy" tabindex="<?php echo ++$tab; ?>" value="1" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Max relationship privacy path length'), help_link('useradmin_path_length');  ?></td>
			<td class="optionbox wrap"><input type="text" name="new_max_relation_path" tabindex="<?php echo ++$tab; ?>" value="0" size="5" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Email address'), help_link('useradmin_email');  ?></td><td class="optionbox wrap"><input type="text" name="emailaddress" tabindex="<?php echo ++$tab; ?>" value="" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User verified himself'), help_link('useradmin_verified'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified" tabindex="<?php echo ++$tab; ?>" value="1" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('User approved by admin'), help_link('useradmin_verbyadmin');  ?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" tabindex="<?php echo ++$tab; ?>" value="1" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php echo i18n::translate('Change language'), help_link('useradmin_change_lang'); ?></td><td class="optionbox wrap" valign="top"><?php

		$tab++;
		echo edit_field_language('user_language', get_user_setting(WT_USER_ID, 'language'), 'tabindex="'.$tab.'"');
		?></td></tr>
		<?php if (get_site_setting('ALLOW_USER_THEMES')) { ?>
			<tr><td class="descriptionbox wrap" valign="top" align="left"><?php echo i18n::translate('My Theme'), help_link('useradmin_user_theme'); ?></td><td class="optionbox wrap" valign="top">
			<select name="new_user_theme" tabindex="<?php echo ++$tab; ?>">
			<option value="" selected="selected"><?php echo i18n::translate('Site Default'); ?></option>
			<?php
			foreach(get_theme_names() as $themename=>$themedir) {
				echo "<option value=\"", $themedir, "\"";
				echo ">", $themename, "</option>\n";
			}
			?>
			</select>
			</td></tr>
		<?php } ?>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Preferred contact method'), help_link('useradmin_user_contact');  ?></td>
			<td class="optionbox wrap">
	<?php
		echo edit_field_contact('new_contact_method', get_site_setting('STORE_MESSAGES') ? 'messaging2' : 'messaging3', 'tabindex="'.(++$tab).'"');
	?>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Visible to other users when online'), help_link('useradmin_visibleonline'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="visibleonline" tabindex="<?php echo ++$tab; ?>" value="1" <?php echo "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Allow this user to edit his account information'), help_link('useradmin_editaccount'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="editaccount" tabindex="<?php echo ++$tab; ?>" value="1" <?php echo "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Default Tab to show on Individual Information page'), help_link('useradmin_user_default_tab'); ?></td>
			<td class="optionbox wrap">
			<?php echo edit_field_default_tab('new_default_tab', $GEDCOM_DEFAULT_TAB, 'tabindex="'.(++$tab).'"'); ?>
			</td>
		</tr>
		<?php if (WT_USER_IS_ADMIN) { ?>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Admin comments on user'), help_link('useradmin_comment'); ?></td>
			<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment" tabindex="<?php echo ++$tab; ?>" ></textarea></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php echo i18n::translate('Admin warning at date'), help_link('useradmin_comment_exp'); ?></td>
			<td class="optionbox wrap"><input type="text" name="new_comment_exp" tabindex="<?php echo ++$tab; ?>" id="new_comment_exp" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
		</tr>
		<?php } ?>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Create User'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='user_admin.php';"/>
	</td></tr></table>
	</form>
	<?php
//	print_footer();
	exit;
}