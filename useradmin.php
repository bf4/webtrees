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

define('PGV_SCRIPT_NAME', 'useradmin.php');
require './config.php';
require_once PGV_ROOT.'includes/functions/functions_edit.php';

loadLangFile('pgv_confighelp');

// Only admin users can access this page
if (!PGV_USER_IS_ADMIN) {
	$loginURL = "$LOGIN_URL?url=".urlencode(PGV_SCRIPT_NAME."?".$QUERY_STRING);
	header("Location: $loginURL");
	exit;
}

// Valid values for form variables
$ALL_ACTIONS=array('cleanup', 'cleanup2', 'createform', 'createuser', 'deleteuser', 'edituser', 'edituser2', 'listusers');
$ALL_CONTACT_METHODS=array('messaging', 'messaging2', 'messaging3', 'mailto', 'none');
$ALL_DEFAULT_TABS=array(0=>'personal_facts', 1=>'notes', 2=>'ssourcess', 3=>'media', 4=>'relatives', -1=>'all', -2=>'lasttab');
$ALL_THEMES_DIRS=array();
foreach (get_theme_names() as $themename=>$themedir) {
	$ALL_THEME_DIRS[]=$themedir;
}
$ALL_EDIT_OPTIONS=array('none', 'access', 'edit', 'accept', 'admin');

// Extract form actions (GET overrides POST if both set)
$action                  =safe_POST('action',  $ALL_ACTIONS);
$usrlang                 =safe_POST('usrlang', array_keys($pgv_language));
$username                =safe_POST('username', PGV_REGEX_USERNAME);
$filter                  =safe_POST('filter'   );
$sort                    =safe_POST('sort'     );
$ged                     =safe_POST('ged'      );

$action                  =safe_GET('action',   $ALL_ACTIONS,              $action);
$usrlang                 =safe_GET('usrlang',  array_keys($pgv_language), $usrlang);
$username                =safe_GET('username', PGV_REGEX_USERNAME,        $username);
$filter                  =safe_GET('filter',   PGV_REGEX_NOSCRIPT,        $filter);
$sort                    =safe_GET('sort',     PGV_REGEX_NOSCRIPT,        $sort);
$ged                     =safe_GET('ged',      PGV_REGEX_NOSCRIPT,        $ged);

// Extract form variables
$oldusername             =safe_POST('oldusername',  PGV_REGEX_USERNAME);
$firstname               =safe_POST('firstname'  );
$lastname                =safe_POST('lastname'   );
$pass1                   =safe_POST('pass1',        PGV_REGEX_PASSWORD);
$pass2                   =safe_POST('pass2',        PGV_REGEX_PASSWORD);
$emailaddress            =safe_POST('emailaddress', PGV_REGEX_EMAIL);
$user_theme              =safe_POST('user_theme',               $ALL_THEME_DIRS);
$user_language           =safe_POST('user_language',            array_keys($pgv_language), $LANGUAGE);
$new_contact_method      =safe_POST('new_contact_method',       $ALL_CONTACT_METHODS, $CONTACT_METHOD);
$new_default_tab         =safe_POST('new_default_tab',          array_keys($ALL_DEFAULT_TABS), $GEDCOM_DEFAULT_TAB);
$new_comment             =safe_POST('new_comment',              PGV_REGEX_UNSAFE);
$new_comment_exp         =safe_POST('new_comment_exp'           );
$new_max_relation_path   =safe_POST_integer('new_max_relation_path', 1, $MAX_RELATION_PATH_LENGTH, 2);
$new_relationship_privacy=safe_POST('new_relationship_privacy', 'Y',   'N');
$new_auto_accept         =safe_POST('new_auto_accept',          'Y',   'N');
$canadmin                =safe_POST('canadmin',                 'Y',   'N');
$visibleonline           =safe_POST('visibleonline',            'Y',   'N');
$editaccount             =safe_POST('editaccount',              'Y',   'N');
$verified                =safe_POST('verified',                 'yes', 'no');
$verified_by_admin       =safe_POST('verified_by_admin',        'yes', 'no');

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
	if ($user_id!=PGV_USER_ID) {
		delete_user($user_id);
		AddToLog("deleted user ->{$username}<-");
	}
	// User data is cached, so reload the page to ensure we're up to date
	header("Location: useradmin.php");
	exit;
}

// Save new user info to the database
if ($action=='createuser' || $action=='edituser2') {
	if (($action=='createuser' || $action=='edituser2' && $username!=$oldusername) && get_user_id($username)) {
		print_header(i18n::translate('User administration'));
		echo "<span class=\"error\">", i18n::translate('Duplicate user name.  A user with that user name already exists.  Please choose another user name.'), "</span><br />";
	} else {
		if ($pass1!=$pass2) {
			print_header(i18n::translate('User administration'));
			echo "<span class=\"error\">", i18n::translate('Passwords do not match.'), "</span><br />";
		} else {
			$alphabet=getAlphabet()."_-. ";
			$i=1;
			$pass=true;
			while (strlen($username) > $i) {
				if (stristr($alphabet, $username{$i})===false) {
					$pass=false;
					break;
				}
				$i++;
			}
			if (!$pass) {
				print_header(i18n::translate('User administration'));
				echo "<span class=\"error\">", i18n::translate('User name contains invalid characters'), "</span><br />";
			} else {
				// New user
				if ($action=='createuser') {
					if ($user_id=create_user($username, crypt($pass1))) {
						set_user_setting($user_id, 'reg_timestamp', date('U'));
						set_user_setting($user_id, 'sessiontime', '0');
						AddToLog("User ->{$username}<- created");
					} else {
						AddToLog("User ->{$username}<- was not created");
						$user_id=get_user_id($username);
					}
				} else {
					$user_id=get_user_id($username);
				}
				// Change password
				if ($action=='edituser2' && !empty($pass1)) {
					set_user_password($user_id, crypt($pass1));
					AddToLog("User ->{$oldusername}<- had password changed");
				}
				// Change username
				if ($action=='edituser2' && $username!=$oldusername) {
					rename_user($oldusername, $username);
					AddToLog("User ->{$oldusername}<- renamed to ->{$username}<-");
				}
				// Create/change settings that can be updated in the user's gedcom record?
				$email_changed=($emailaddress!=get_user_setting($user_id, 'email'));
				$newly_verified=($verified_by_admin=='yes' && get_user_setting($user_id, 'verified_by_admin')!='yes');
				// Create/change other settings
				set_user_setting($user_id, 'firstname',            $firstname);
				set_user_setting($user_id, 'lastname',             $lastname);
				set_user_setting($user_id, 'email',                $emailaddress);
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
					set_user_gedcom_setting($user_id, $ged_id, 'canedit',  safe_POST('canedit'.$ged_id,  $ALL_EDIT_OPTIONS));
				}
				// If we're verifying a new user, send them a message to let them know
				if ($newly_verified && $action=='edituser2') {
					if ($LANGUAGE != $user_language) {
						loadLanguage($user_language);
					}
					$serverURL = rtrim($SERVER_URL, '/');
					$message=array();
					$message["to"]=$username;
					$headers="From: ".$PHPGEDVIEW_EMAIL;
					$message["from"]=PGV_USER_NAME;
					$message["subject"]=i18n::translate('Approval of account at %s', $serverURL);
					$message["body"]=i18n::translate('The administrator at the PhpGedView site %s has approved your application for an account.  You may now login by accessing the following link: %s#', $serverURL, $serverURL);
					$message["created"]="";
					$message["method"]="messaging2";
					addMessage($message);
					// and send a copy to the admin
/*					$message=array();
					$message["to"]=PGV_USER_NAME;
					$headers="From: ".$PHPGEDVIEW_EMAIL;
					$message["from"]=$username; // fake the from address - so the admin can "reply" to it.
					$message["subject"]=i18n::translate('Approval of account at %s', $serverURL));
					$message["body"]=i18n::translate('The administrator at the PhpGedView site %s has approved your application for an account.  You may now login by accessing the following link: %s', $serverURL, $serverURL));
					$message["created"]="";
					$message["method"]="messaging2";
					addMessage($message); */
				}
				// Reload the form cleanly, to allow the user to verify their changes
				header("Location: ".encode_url("useradmin.php?action=edituser&username={$username}&ged={$ged}", false));
				exit;
			}
		}
	}
} else {
	print_header(i18n::translate('User administration'));

	if ($ENABLE_AUTOCOMPLETE) require PGV_ROOT.'js/autocomplete.js.htm';
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
		if (frm.firstname.value=="") {
			alert("<?php echo i18n::translate('You must enter a first and last name.'); ?>");
			frm.firstname.focus();
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
	<form name="editform" method="post" action="useradmin.php" onsubmit="return checkform(this);">
	<input type="hidden" name="action" value="edituser2" />
	<input type="hidden" name="filter" value="<?php echo $filter; ?>" />
	<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
	<input type="hidden" name="usrlang" value="<?php echo $usrlang; ?>" />
	<input type="hidden" name="oldusername" value="<?php echo $username; ?>" />
	<?php $tab=0; ?>
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr><td colspan="2" class="facts_label"><?php
	echo "<h2>", i18n::translate('Update User Account'), "</h2>";
	?>
	</td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Update User Account'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='<?php echo encode_url("useradmin.php?action=listusers&sort={$sort}&filter={$filter}&usrlang={$usrlang}"); ?>';"/>
	</td></tr>
	<tr>
	<td class="descriptionbox width20 wrap"><?php print_help_link("useradmin_username", "qm", "username"); echo i18n::translate('User name'); ?></td>
	<td class="optionbox wrap"><input type="text" name="username" tabindex="<?php echo ++$tab; ?>" value="<?php echo $username; ?>" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_firstname", "qm", "firstname"); echo i18n::translate('First Name'); ?></td>
	<td class="optionbox wrap"><input type="text" name="firstname" tabindex="<?php echo ++$tab; ?>" value="<?php echo PrintReady(get_user_setting($user_id, 'firstname')); ?>" size="50" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_lastname", "qm", "lastname");echo i18n::translate('Last Name'); ?></td>
	<td class="optionbox wrap"><input type="text" name="lastname" tabindex="<?php echo ++$tab; ?>" value="<?php echo PrintReady(get_user_setting($user_id, 'lastname')); ?>" size="50" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_password", "qm", "password"); echo i18n::translate('Password'); ?></td>
	<td class="optionbox wrap"><input type="password" name="pass1" tabindex="<?php echo ++$tab; ?>" /><br /><?php echo i18n::translate('Leave password blank if you want to keep the current password.'); ?></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_conf_password", "qm", "confirm"); echo i18n::translate('Confirm Password'); ?></td>
	<td class="optionbox wrap"><input type="password" name="pass2" tabindex="<?php echo ++$tab; ?>" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_gedcomid", "qm", "gedcomid"); echo i18n::translate('GEDCOM INDI record ID'); ?></td>
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
			echo ' <span class="list_item"><a href="', encode_url("individual.php?pid={$pid}&ged={$ged_name}"), '">', PrintReady($person->getFullName()), '</a>', $person->format_first_major_fact(PGV_EVENTS_BIRT, 1), $person->format_first_major_fact(PGV_EVENTS_DEAT, 1), '</span>';
		}
		echo "</td></tr>";
	}
	?>
	</table></td></tr><tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_rootid", "qm", "rootid"); echo i18n::translate('Pedigree Chart Root Person'); ?></td>
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
			echo ' <span class="list_item"><a href="', encode_url("individual.php?pid={$pid}&ged={$ged_name}"), '">', PrintReady($person->getFullName()), '</a>', $person->format_first_major_fact(PGV_EVENTS_BIRT, 1), $person->format_first_major_fact(PGV_EVENTS_DEAT, 1), '</span>';
		}
		?>
		</td></tr>
		<?php
	} ?></table>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_can_admin", "qm", "can_admin"); echo i18n::translate('User can administer'); ?></td>
	<?php
	// Forms won't send the value of checkboxes if they are disabled :-(  Instead, create a hidden field
	if ($user_id==PGV_USER_ID) {
		?>
		<td class="optionbox wrap"><input type="checkbox" name="dummy" <?php if (get_user_setting($user_id, 'canadmin')=='Y') echo "checked=\"checked\""; ?> disabled="disabled" /></td>
		<input type="hidden" name="canadmin" value="<?php echo get_user_setting($user_id, 'canadmin'); ?>" />
		<?php
	} else {
		?>
		<td class="optionbox wrap"><input type="checkbox" name="canadmin" tabindex="<?php echo ++$tab; ?>" value="Y" <?php if (get_user_setting($user_id, 'canadmin')=='Y') echo "checked=\"checked\""; ?> /></td>
		<?php
	}
	?>

	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_can_edit", "qm", "can_edit"); echo i18n::translate('Access level'); ?></td>
	<td class="optionbox wrap">
	<table class="<?php echo $TEXT_DIRECTION; ?>">
	<?php
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$varname = 'canedit'.$ged_id;
		echo "<tr><td>$ged_name:&nbsp;&nbsp;</td><td>";
		$tab++;
		echo "<select name=\"{$varname}\" id=\"{$varname}\" tabindex=\"{$tab}\">\n";
		foreach ($ALL_EDIT_OPTIONS as $EDIT_OPTION) {
			echo '<option value="', $EDIT_OPTION, '" ';
			if (get_user_gedcom_setting($user_id, $ged_id, 'canedit')==$EDIT_OPTION) {
				echo 'selected="selected" ';
			}
			echo '>';
			if ($EDIT_OPTION=='admin') {
				echo $pgv_lang[$EDIT_OPTION.'_gedcom'];
			} else {
				echo $pgv_lang[$EDIT_OPTION];
			}
			echo '</option>';
		}
		echo "</select></td></tr>";
	}
	?>
	</table>
	</td>
	</tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_auto_accept", "qm", "user_auto_accept"); echo i18n::translate('Automatically accept changes made by this user'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" tabindex="<?php echo ++$tab; ?>" value="Y" <?php if (get_user_setting($user_id, 'auto_accept')=='Y') echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_relation_priv", "qm", "user_relationship_priv"); echo i18n::translate('Limit access to related people'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_relationship_privacy" tabindex="<?php echo ++$tab; ?>" value="Y" <?php if (get_user_setting($user_id, 'relationship_privacy')=="Y") echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_path_length", "qm", "user_path_length"); echo i18n::translate('Max relationship privacy path length'); ?></td>
	<td class="optionbox wrap"><input type="text" name="new_max_relation_path" tabindex="<?php echo ++$tab; ?>" value="<?php echo get_user_setting($user_id, 'max_relation_path'); ?>" size="5" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_email", "qm", "emailadress"); echo i18n::translate('Email Address'); ?></td><td class="optionbox wrap"><input type="text" name="emailaddress" tabindex="<?php echo ++$tab; ?>" dir="ltr" value="<?php echo get_user_setting($user_id, 'email'); ?>" size="50" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verified", "qm", "verified"); echo i18n::translate('User verified himself'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified" tabindex="<?php echo ++$tab; ?>" value="yes" <?php if (get_user_setting($user_id, 'verified')=="yes") echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verbyadmin", "qm", "verified_by_admin"); echo i18n::translate('User approved by Admin'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" tabindex="<?php echo ++$tab; ?>" value="yes" <?php if (get_user_setting($user_id, 'verified_by_admin')=="yes") echo "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_change_lang", "qm", "change_lang");echo i18n::translate('Change Language'); ?></td><td class="optionbox wrap" valign="top"><?php
	if ($ENABLE_MULTI_LANGUAGE) {
		$tab++;
		echo "<select name=\"user_language\" tabindex=\"", $tab, "\" dir=\"ltr\" style=\"{ font-size: 9pt; }\">";
		foreach ($pgv_language as $key => $value) {
			if ($language_settings[$key]["pgv_lang_use"]) {
				echo "\n\t\t\t<option value=\"$key\"";
				if ($key == get_user_setting($user_id, 'language')) echo " selected=\"selected\"";
				echo ">" . $pgv_lang[$key] . "</option>";
			}
		}
		echo "</select>\n\t\t";
	}
	else echo "&nbsp;";
	?></td></tr>
	<?php
	if ($ALLOW_USER_THEMES) {
		?>
		<tr><td class="descriptionbox wrap" valign="top" align="left"><?php print_help_link("useradmin_user_theme", "qm", "user_theme"); echo i18n::translate('My Theme'); ?></td><td class="optionbox wrap" valign="top">
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
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_contact", "qm", "user_contact_method"); echo i18n::translate('Preferred Contact Method'); ?></td>
	<td class="optionbox wrap"><select name="new_contact_method" tabindex="<?php echo ++$tab; ?>">
	<?php
	if ($PGV_STORE_MESSAGES) {
		?>
		<option value="messaging" <?php if (get_user_setting($user_id, 'contactmethod')=='messaging') echo "selected=\"selected\""; ?>><?php echo i18n::translate('PhpGedView internal messaging'); ?></option>
		<option value="messaging2" <?php if (get_user_setting($user_id, 'contactmethod')=='messaging2') echo "selected=\"selected\""; ?>><?php echo i18n::translate('Internal messaging with emails'); ?></option>
		<?php
	} else {
		?>
		<option value="messaging3" <?php if (get_user_setting($user_id, 'contactmethod')=='messaging3') echo "selected=\"selected\""; ?>><?php echo i18n::translate('PhpGedView sends emails with no storage'); ?></option>
		<?php
	}
	?>
	<option value="mailto" <?php if (get_user_setting($user_id, 'contactmethod')=='mailto') echo "selected=\"selected\""; ?>><?php echo i18n::translate('Mailto link'); ?></option>
	<option value="none" <?php if (get_user_setting($user_id, 'contactmethod')=='none') echo "selected=\"selected\""; ?>><?php echo i18n::translate('No contact method'); ?></option>
	</select>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_visibleonline", "qm", "visibleonline"); echo i18n::translate('Visible to other users when online'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="visibleonline" tabindex="<?php echo ++$tab; ?>" value="Y" <?php if (get_user_setting($user_id, 'visibleonline')=='Y') echo "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_editaccount", "qm", "editaccount"); echo i18n::translate('Allow this user to edit his account information'); ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="editaccount" tabindex="<?php echo ++$tab; ?>" value="Y" <?php if (get_user_setting($user_id, 'editaccount')=='Y') echo "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_default_tab", "qm", "user_default_tab"); echo i18n::translate('Default Tab to show on Individual Information page'); ?></td>
	<td class="optionbox wrap"><select name="new_default_tab" tabindex="<?php echo ++$tab; ?>">
	<?php
	foreach ($ALL_DEFAULT_TABS as $key=>$value) {
		echo '<option value="', $key, '"';
		if (get_user_setting($user_id, 'defaulttab')==$key) {
			echo ' selected="selected"';
		}
		echo '>', $pgv_lang[$value], '</option>';
	}
	?>
	</select>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment", "qm", "comment"); echo i18n::translate('Admin comments on user'); ?></td>
	<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment" tabindex="<?php echo ++$tab; ?>" ><?php $tmp = PrintReady(get_user_setting($user_id, 'comment')); echo $tmp; ?></textarea></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment_exp", "qm", "comment_exp"); echo i18n::translate('Admin warning at date'); ?></td>
	<td class="optionbox wrap"><input type="text" name="new_comment_exp" id="new_comment_exp" tabindex="<?php echo ++$tab; ?>" value="<?php echo get_user_setting($user_id, 'comment_exp'); ?>" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Update User Account'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='<?php echo encode_url("useradmin.php?action=listusers&sort={$sort}&filter={$filter}&usrlang={$usrlang}"); ?>';"/>
	</td></tr>
	</table>
	</form>
	<?php
	print_footer();
	exit;
}

//-- echo out a list of the current users
if ($action == "listusers") {
	$showprivs=($view=="preview"); // expand gedcom privs by default in print-preview

	switch ($sort) {
		case "sortfname":
			$users = get_all_users("asc", "firstname", "lastname");
			break;
		case "sortllgn":
			$users = get_all_users("desc", "sessiontime");
			break;
		case "sortreg":
			$users = get_all_users("desc", "reg_timestamp");
			break;
		case "sortver":
			$users = get_all_users("asc", "verified");
			break;
		case "sortveradmin":
			$users = get_all_users("asc", "verified_by_admin");
			break;
		case "sortusername":
			$users = get_all_users("asc", "username");
			break;
		case "sortlname":
		default:
			$users = get_all_users("asc", "lastname", "firstname");
			break;
	}

	// First filter the users, otherwise the javascript to unfold priviledges gets disturbed
	foreach($users as $user_id=>$user_name) {
		if (!isset($language_settings[get_user_setting($user_id, 'language')]))
			set_user_setting($user_id, 'language', $LANGUAGE);
		if ($filter == "warnings") {
			if (get_user_setting($user_id, 'comment_exp')) {
				if ((strtotime(get_user_setting($user_id, 'comment_exp')) == "-1") || (strtotime(get_user_setting($user_id, 'comment_exp')) >= time("U"))) unset($users[$user_id]);
			}
			else if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) <= 604800) || (get_user_setting($user_id, 'verified')=="yes")) unset($users[$user_id]);
		}
		else if ($filter == "adminusers") {
			if (get_user_setting($user_id, 'canadmin')!='Y') unset($users[$user_id]);
		}
		else if ($filter == "usunver") {
			if (get_user_setting($user_id, 'verified') == "yes") unset($users[$user_id]);
		}
		else if ($filter == "admunver") {
			if ((get_user_setting($user_id, 'verified_by_admin') == "yes") || (get_user_setting($user_id, 'verified') != "yes")) {
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

	// Then show the users
	?>
	<table class="center list_table width80 <?php echo $TEXT_DIRECTION; ?>">
	<tr><td colspan="<?php if ($view == "preview") echo "8"; else echo "11"; ?>" class="facts_label"><?php
		echo "<h2>", i18n::translate('User List'), "</h2>";
	?>
	</td></tr>
	<tr>
	<?php if ($view!="preview") { ?>
	<td colspan="5" class="topbottombar rtl"><a href="useradmin.php?action=createform"><?php echo i18n::translate('Add a new user'); ?></a></td>
	<?php } ?>
	<td colspan="<?php if ($view == "preview") echo "8"; else echo "5"; ?>" class="topbottombar rtl"><a href="useradmin.php"><?php if ($view != "preview") echo i18n::translate('Back to User Administration'); else echo "&nbsp;"; ?></a></td>
	</tr>
	<tr>
	<?php if ($view != "preview") {
	echo "<td class=\"descriptionbox wrap\">";
	echo i18n::translate('Send Message'), "</td>";
	} ?>
	<td class="descriptionbox wrap"><a href="<?php echo encode_url("useradmin.php?action=listusers&sort=sortlname&filter={$filter}&usrlang={$usrlang}&ged={$ged}"); ?>"><?php echo i18n::translate('Full Name'); ?></a></td>
	<td class="descriptionbox wrap"><a href="<?php echo encode_url("useradmin.php?action=listusers&sort=sortusername&filter={$filter}&usrlang={$usrlang}&ged={$ged}"); ?>"><?php echo i18n::translate('User name'); ?></a></td>
	<td class="descriptionbox wrap"><?php echo i18n::translate(' Languages'); ?></td>
	<td class="descriptionbox" style="padding-left:2px"><a href="javascript: <?php echo i18n::translate('Privileges'); ?>" onclick="<?php
	$k = 1;
	for ($i=1, $max=count($users)+1; $i<=$max; $i++) echo "expand_layer('user-geds", $i, "'); ";
	echo " return false;\"><img id=\"user-geds", $k, "_img\" src=\"", $PGV_IMAGE_DIR, "/";
	if ($showprivs == false) echo $PGV_IMAGES["plus"]["other"];
	else echo $PGV_IMAGES["minus"]["other"];
	echo "\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" /></a>";
	echo "<div id=\"user-geds", $k, "\" style=\"display: ";
	if ($showprivs == false) echo "none\">";
	else echo "block\">";
	echo "</div>&nbsp;";
	echo i18n::translate('Privileges'); ?>
	</td>
	<td class="descriptionbox wrap width10"><a href="<?php echo encode_url("useradmin.php?action=listusers&sort=sortreg&filter={$filter}&usrlang={$usrlang}&ged={$ged}"); ?>"><?php echo i18n::translate('Date registered'); ?></a></td>
	<td class="descriptionbox wrap width20"><a href="<?php echo encode_url("useradmin.php?action=listusers&sort=sortllgn&filter={$filter}&usrlang={$usrlang}&ged={$ged}"); ?>"><?php echo i18n::translate('Last logged in'); ?></a></td>
	<td class="descriptionbox wrap"><a href="<?php echo encode_url("useradmin.php?action=listusers&sort=sortver&filter={$filter}&usrlang={$usrlang}&ged={$ged}"); ?>"><?php echo i18n::translate('User verified himself'); ?></a></td>
	<td class="descriptionbox wrap"><a href="<?php echo encode_url("useradmin.php?action=listusers&sort=sortveradmin&filter={$filter}&usrlang={$usrlang}&ged={$ged}"); ?>"><?php echo i18n::translate('User approved by Admin'); ?></a></td>
	<?php if ($view != "preview") {
	echo "<td class=\"descriptionbox wrap\">";
	echo i18n::translate('Delete'), "</td>";
	} ?>
	</tr>
	<?php
	$k++;
	foreach($users as $user_id=>$user_name) {
		echo "<tr>\n";
		if ($view != "preview") {
			echo "\t<td class=\"optionbox wrap\">";
			if ($user_id!=PGV_USER_ID && get_user_setting($user_id, 'contactmethod')!='none') {
				echo "<a href=\"javascript:;\" onclick=\"return message('", $user_name, "');\">", i18n::translate('Send Message'), "</a>";
			} else {
				echo '&nbsp;';
			}
			echo '</td>';
		}
		$userName = getUserFullName($user_id);
		echo "\t<td class=\"optionbox wrap\"><a href=\"", encode_url("useradmin.php?action=edituser&username={$user_name}&sort={$sort}&filter={$filter}&usrlang={$usrlang}&ged={$ged}"), "\" title=\"", i18n::translate('Edit'), "\">", $userName;
		if ($TEXT_DIRECTION=="ltr") echo getLRM();
		else                        echo getRLM();
		echo "</a></td>\n";
		if (get_user_setting($user_id, "comment_exp")) {
			if ((strtotime(get_user_setting($user_id, "comment_exp")) != "-1") && (strtotime(get_user_setting($user_id, "comment_exp")) < time("U"))) echo "\t<td class=\"optionbox red\">", $user_name;
			else echo "\t<td class=\"optionbox wrap\">", $user_name;
		}
		else echo "\t<td class=\"optionbox wrap\">", $user_name;
		if (get_user_setting($user_id, "comment")) {
			$tempTitle = PrintReady(get_user_setting($user_id, "comment"));
			echo "<br /><img class=\"adminicon\" align=\"top\" alt=\"{$tempTitle}\" title=\"{$tempTitle}\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['notes']['small']}\" />";
		}
		echo "</td>\n";
		echo "\t<td class=\"optionbox wrap\">", $pgv_lang["lang_name_".get_user_setting($user_id, 'language')], "<br /><img src=\"", $language_settings[get_user_setting($user_id, 'language')]["flagsfile"], "\" class=\"brightflag\" alt=\"", $pgv_lang["lang_name_".get_user_setting($user_id, 'language')], "\" title=\"", $pgv_lang["lang_name_".get_user_setting($user_id, 'language')], "\" /></td>\n";
		echo "\t<td class=\"optionbox\">";
		echo "<a href=\"javascript: ", i18n::translate('Privileges'), "\" onclick=\"expand_layer('user-geds", $k, "'); return false;\"><img id=\"user-geds", $k, "_img\" src=\"", $PGV_IMAGE_DIR, "/";
		if ($showprivs == false) echo $PGV_IMAGES["plus"]["other"];
		else echo $PGV_IMAGES["minus"]["other"];
		echo "\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" />";
		echo "</a>";
		echo "<div id=\"user-geds", $k, "\" style=\"display: ";
		if ($showprivs == false) echo "none\">";
		else echo "block\">";
		echo "<ul>";
		if (get_user_setting($user_id, 'canadmin')=='Y') {
			echo "<li class=\"warning\">", i18n::translate('User can administer'), "</li>\n";
		}
		foreach ($all_gedcoms as $ged_id=>$ged_name) {
			$vval = get_user_gedcom_setting($user_id, $ged_id, 'canedit');
			if ($vval == "") $vval = "none";
			$uged = get_user_gedcom_setting($user_id, $ged_id, 'gedcomid');
			if ($vval=="accept" || $vval=="admin") echo "<li class=\"warning\">";
			else echo "<li>";
			echo $pgv_lang[$vval], " ";
			if ($uged != "") echo "<a href=\"", encode_url("individual.php?pid={$uged}&ged={$ged_name}"), "\">", $ged_name, "</a></li>\n";
			else echo $ged_name, "</li>\n";
		}
		echo "</ul>";
		echo "</div>";
		$k++;
		echo "</td>\n";
		if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && (get_user_setting($user_id, 'verified')!="yes")) echo "\t<td class=\"optionbox red\">";
		else echo "\t<td class=\"optionbox wrap\">";
		echo format_timestamp((int)get_user_setting($user_id, 'reg_timestamp'));
		echo "</td>\n";
		echo "\t<td class=\"optionbox wrap\">";
		if ((int)get_user_setting($user_id, 'reg_timestamp') > (int)get_user_setting($user_id, 'sessiontime')) {
			echo i18n::translate('Never');
			$pgv_lang["global_string1"] = formatElapsedTime(time() - (int)get_user_setting($user_id, 'reg_timestamp'));
			echo '<br />', getLRM(), '(', print_text('elapsedAgo', 0, 1), getLRM(), ')';
		} else {
			$pgv_lang["global_string1"] = formatElapsedTime(time() - (int)get_user_setting($user_id, 'sessiontime'));
			echo format_timestamp((int)get_user_setting($user_id, 'sessiontime'));
			echo '<br />', getLRM(), '(', print_text('elapsedAgo', 0, 1), getLRM(), ')';
		}
		echo "</td>\n";
		echo "\t<td class=\"optionbox wrap\">";
		if (get_user_setting($user_id, 'verified')=="yes") echo i18n::translate('Yes');
		else echo i18n::translate('No');
		echo "</td>\n";
		echo "\t<td class=\"optionbox wrap\">";
		if (get_user_setting($user_id, 'verified_by_admin')=="yes") echo i18n::translate('Yes');
		else echo i18n::translate('No');
		echo "</td>\n";
		if ($view != "preview") {
			echo "\t<td class=\"optionbox wrap\">";
			if (PGV_USER_ID!=$user_id) echo "<a href=\"", encode_url("useradmin.php?action=deleteuser&username={$user_name}&sort={$sort}&filter={$filter}&usrlang={$usrlang}&ged={$ged}"), "\" onclick=\"return confirm('", i18n::translate('Are you sure you want to delete the user'), " $user_name');\">", i18n::translate('Delete'), "</a>";
			echo "</td>\n";
		}
		echo "</tr>\n";
	}
	?>
	<tr>
		<?php if ($view!="preview") { ?>
		<td colspan="6" class="topbottombar rtl"><a href="useradmin.php?action=createform"><?php echo i18n::translate('Add a new user'); ?></a></td>
		<?php } ?>
		<td colspan="<?php if ($view == "preview") echo "8"; else echo "5"; ?>" class="topbottombar rtl"><a href="useradmin.php"><?php  if ($view != "preview") echo i18n::translate('Back to User Administration'); else echo "&nbsp;"; ?></a></td>
	</tr>
	</table>
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
			if (frm.firstname.value=="") {
				alert("<?php echo i18n::translate('You must enter a first and last name.'); ?>");
				frm.firstname.focus();
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

	<form name="newform" method="post" action="useradmin.php" onsubmit="return checkform(this);">
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
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='useradmin.php';"/>
	</td></tr>
		<tr><td class="descriptionbox wrap width20"><?php print_help_link("useradmin_username", "qm", "username"); echo i18n::translate('User name'); ?></td><td class="optionbox wrap"><input type="text" name="username" tabindex="<?php echo ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_firstname", "qm", "firstname"); echo i18n::translate('First Name'); ?></td><td class="optionbox wrap"><input type="text" name="firstname" tabindex="<?php echo ++$tab; ?>" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_lastname", "qm", "lastname"); echo i18n::translate('Last Name'); ?></td><td class="optionbox wrap"><input type="text" name="lastname" tabindex="<?php echo ++$tab; ?>" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_password", "qm", "password"); echo i18n::translate('Password'); ?></td><td class="optionbox wrap"><input type="password" name="pass1" tabindex="<?php echo ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_conf_password", "qm", "confirm"); echo i18n::translate('Confirm Password'); ?></td><td class="optionbox wrap"><input type="password" name="pass2" tabindex="<?php echo ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_gedcomid", "qm", "gedcomid"); echo i18n::translate('GEDCOM INDI record ID'); ?></td><td class="optionbox wrap">

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
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_rootid", "qm", "rootid"); echo i18n::translate('Pedigree Chart Root Person'); ?></td><td class="optionbox wrap">
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
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_can_admin", "qm", "can_admin"); echo i18n::translate('User can administer'); ?></td><td class="optionbox wrap"><input type="checkbox" name="canadmin" tabindex="<?php echo ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_can_edit", "qm", "can_edit");echo i18n::translate('Access level'); ?></td><td class="optionbox wrap">
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
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_auto_accept", "qm", "user_auto_accept");echo i18n::translate('Automatically accept changes made by this user'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" tabindex="<?php echo ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_relation_priv", "qm", "user_relationship_priv");echo i18n::translate('Limit access to related people'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_relationship_privacy" tabindex="<?php echo ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_path_length", "qm", "user_path_length"); echo i18n::translate('Max relationship privacy path length'); ?></td>
			<td class="optionbox wrap"><input type="text" name="new_max_relation_path" tabindex="<?php echo ++$tab; ?>" value="0" size="5" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_email", "qm", "emailadress"); echo i18n::translate('Email Address'); ?></td><td class="optionbox wrap"><input type="text" name="emailaddress" tabindex="<?php echo ++$tab; ?>" value="" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verified", "qm", "verified"); echo i18n::translate('User verified himself'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified" tabindex="<?php echo ++$tab; ?>" value="yes" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verbyadmin", "qm", "verified_by_admin"); echo i18n::translate('User approved by Admin'); ?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" tabindex="<?php echo ++$tab; ?>" value="yes" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_change_lang", "qm", "change_lang");echo i18n::translate('Change Language'); ?></td><td class="optionbox wrap" valign="top"><?php

		$user_lang = get_user_setting(PGV_USER_ID, 'language');
		if ($ENABLE_MULTI_LANGUAGE) {
			$tab++;
			echo "<select name=\"user_language\" tabindex=\"", $tab, "\" style=\"{ font-size: 9pt; }\">";
			foreach ($pgv_language as $key => $value) {
				if ($language_settings[$key]["pgv_lang_use"]) {
					echo "\n\t\t\t<option value=\"$key\"";
					if ($key == $user_lang) {
						echo " selected=\"selected\"";
					}
					echo ">" . $pgv_lang[$key] . "</option>";
				}
			}
			echo "</select>\n\t\t";
		}
		else echo "&nbsp;";
		?></td></tr>
		<?php if ($ALLOW_USER_THEMES) { ?>
			<tr><td class="descriptionbox wrap" valign="top" align="left"><?php print_help_link("useradmin_user_theme", "qm", "user_theme"); echo i18n::translate('My Theme'); ?></td><td class="optionbox wrap" valign="top">
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
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_contact", "qm", "user_contact_method"); echo i18n::translate('Preferred Contact Method'); ?></td>
			<td class="optionbox wrap"><select name="new_contact_method" tabindex="<?php echo ++$tab; ?>">
			<?php if ($PGV_STORE_MESSAGES) { ?>
				<option value="messaging"><?php echo i18n::translate('PhpGedView internal messaging'); ?></option>
				<option value="messaging2" selected="selected"><?php echo i18n::translate('Internal messaging with emails'); ?></option>
			<?php } else { ?>
				<option value="messaging3" selected="selected"><?php echo i18n::translate('PhpGedView sends emails with no storage'); ?></option>
			<?php } ?>
				<option value="mailto"><?php echo i18n::translate('Mailto link'); ?></option>
				<option value="none"><?php echo i18n::translate('No contact method'); ?></option>
			</select>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_visibleonline", "qm", "visibleonline"); echo i18n::translate('Visible to other users when online'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="visibleonline" tabindex="<?php echo ++$tab; ?>" value="Y" <?php echo "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_editaccount", "qm", "editaccount"); echo i18n::translate('Allow this user to edit his account information'); ?></td>
			<td class="optionbox wrap"><input type="checkbox" name="editaccount" tabindex="<?php echo ++$tab; ?>" value="Y" <?php echo "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_default_tab", "qm", "user_default_tab"); echo i18n::translate('Default Tab to show on Individual Information page'); ?></td>
			<td class="optionbox wrap"><select name="new_default_tab" tabindex="<?php echo ++$tab; ?>">
			<?php
			foreach ($ALL_DEFAULT_TABS as $key=>$value) {
				echo '<option value="', $key, '"';
				if ($GEDCOM_DEFAULT_TAB==$key) {
					echo ' selected="selected"';
				}
				echo '>', $pgv_lang[$value], '</option>';
			}
			?>
			</select>
			</td>
		</tr>
		<?php if (PGV_USER_IS_ADMIN) { ?>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment", "qm", "comment"); echo i18n::translate('Admin comments on user'); ?></td>
			<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment" tabindex="<?php echo ++$tab; ?>" ></textarea></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment_exp", "qm", "comment_exp"); echo i18n::translate('Admin warning at date'); ?></td>
			<td class="optionbox wrap"><input type="text" name="new_comment_exp" tabindex="<?php echo ++$tab; ?>" id="new_comment_exp" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
		</tr>
		<?php } ?>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Create User'); ?>" />
	<input type="button" tabindex="<?php echo ++$tab; ?>" value="<?php echo i18n::translate('Back'); ?>" onclick="window.location='useradmin.php';"/>
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
//	if (!isset($month)) $month = 1;
	$month = safe_GET_integer('month', 1, 12, 6);
	echo "<tr><td class=\"descriptionbox\">", i18n::translate('Number of months since the last login for a user\'s account to be considered inactive: '), "</td>";
	echo "<td class=\"optionbox\"><select onchange=\"document.location=options[selectedIndex].value;\">";
	for($i=1; $i<=12; $i++) {
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
	foreach(get_all_users() as $user_id=>$user_name) {
		$userName = getUserFullName($user_id);
		if ((int)get_user_setting($user_id, 'sessiontime') == "0")
			$datelogin = (int)get_user_setting($user_id, 'reg_timestamp');
		else
			$datelogin = (int)get_user_setting($user_id, 'sessiontime');
		if ((mktime(0, 0, 0, (int)date("m")-$month, (int)date("d"), (int)date("Y")) > $datelogin) && (get_user_setting($user_id, 'verified') == "yes") && (get_user_setting($user_id, 'verified_by_admin') == "yes")) {
			?><tr><td class="descriptionbox"><?php echo $user_name, " - ", $userName, ":&nbsp;&nbsp;", i18n::translate('User\'s account has been inactive too long: ');
			echo timestamp_to_gedcom_date($datelogin)->Display(false);
			$ucnt++;
			?></td><td class="optionbox"><input type="checkbox" name="<?php echo "del_", str_replace(array(".", "-", " "), array("_", "_", "_"), $user_name); ?>" value="yes" /></td></tr><?php
		}
	}

	// Check unverified users
	foreach(get_all_users() as $user_id=>$user_name) {
		if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && (get_user_setting($user_id, 'verified')!="yes")) {
			$userName = getUserFullName($user_id);
			?><tr><td class="descriptionbox"><?php echo $user_name, " - ", $userName, ":&nbsp;&nbsp;", i18n::translate('User didn\'t verify within 7 days.');
			$ucnt++;
			?></td><td class="optionbox"><input type="checkbox" checked="checked" name="<?php echo "del_", str_replace(array(".", "-", " "), array("_",  "_", "_"), $user_name); ?>" value="yes" /></td></tr><?php
		}
	}

	// Check users not verified by admin
	foreach(get_all_users() as $user_id=>$user_name) {
		if ((get_user_setting($user_id, 'verified_by_admin')!="yes") && (get_user_setting($user_id, 'verified') == "yes")) {
			$userName = getUserFullName($user_id);
			?><tr><td  class="descriptionbox"><?php echo $user_name, " - ", $userName, ":&nbsp;&nbsp;", i18n::translate('User not verified by administrator.');
			?></td><td class="optionbox"><input type="checkbox" name="<?php echo "del_", str_replace(array(".", "-", " "), array("_", "_", "_"), $user_name); ?>" value="yes" /></td></tr><?php
			$ucnt++;
		}
	}

	// Then check obsolete gedcom rights
	$gedrights = array();
	foreach(get_all_users() as $user_id=>$user_name) {
		if (get_user_setting($user_id, 'verified_by_admin')=="yes") {
			$tempArray = unserialize(get_user_setting($user_id, 'canedit'));
			if (is_array($tempArray)) {
				foreach($tempArray as $gedid=>$data) {
					if (!get_id_from_gedcom($gedid) && !in_array($gedid, $gedrights)) $gedrights[] = $gedid;
				}
			}
			$tempArray = unserialize(get_user_setting($user_id, 'gedcomid'));
			if (is_array($tempArray)) {
				foreach($tempArray as $gedid=>$data) {
					if (!get_id_from_gedcom($gedid) && !in_array($gedid, $gedrights)) $gedrights[] = $gedid;
				}
			}
			$tempArray = unserialize(get_user_setting($user_id, 'rootid'));
			if (is_array($tempArray)) {
				foreach($tempArray as $gedid=>$data) {
					if (!get_id_from_gedcom($gedid) && !in_array($gedid, $gedrights)) $gedrights[] = $gedid;
				}
			}
		}
	}
	ksort($gedrights);
	foreach($gedrights as $key=>$ged) {
		?><tr><td class="descriptionbox"><?php echo $ged, ":&nbsp;&nbsp;", i18n::translate('GEDCOM no longer active, remove user references.');
		?></td><td class="optionbox"><input type="checkbox" checked="checked" name="<?php echo "delg_", str_replace(array(".", "-", " "), array("_", "_", "_"), $ged); ?>" value="yes" /></td></tr><?php
		$ucnt++;
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
	foreach(get_all_users() as $user_id=>$user_name) {
		$var = "del_".str_replace(array(".", "-", " "), array("_", "_", "_"), $user_name);
		if (safe_POST($var)=='yes') {
			delete_user($user_id);
			AddToLog("deleted user ->{$user_name}<-");
			echo i18n::translate('Deleted user: '); echo $user_name, "<br />";
		} else {
			$tempArray = unserialize(get_user_setting($user_id, 'canedit'));
			if (is_array($tempArray)) {
				foreach ($tempArray as $gedid=>$data) {
					$var = "delg_".str_replace(array(".", "-", " "), "_", $gedid);
					if (safe_POST($var)=='yes' && get_user_gedcom_setting($user_id, $gedid, 'canedit')) {
						set_user_gedcom_setting($user_id, $gedid, 'canedit', null);
						echo $gedid, ":&nbsp;&nbsp;", i18n::translate('Unset GEDCOM rights for '), $user_name, "<br />";
					}
				}
			}
			$tempArray = unserialize(get_user_setting($user_id, 'rootid'));
			if (is_array($tempArray)) {
				foreach ($tempArray as $gedid=>$data) {
					$var = "delg_".str_replace(array(".", "-", " "), "_", $gedid);
					if (safe_POST($var)=='yes' && get_user_gedcom_setting($user_id, $gedid, 'rootid')) {
						set_user_gedcom_setting($user_id, $gedid, 'rootid', null);
						echo $gedid, ":&nbsp;&nbsp;", i18n::translate('Unset root ID for '), $user_name, "<br />";
					}
				}
			}
			$tempArray = unserialize(get_user_setting($user_id, 'gedcomid'));
			if (is_array($tempArray)) {
				foreach ($tempArray as $gedid=>$data) {
					$var = "delg_".str_replace(array(".", "-", " "), "_", $gedid);
					if (safe_POST($var)=='yes' && get_user_gedcom_setting($user_id, $gedid, 'gedcomid')) {
						set_user_gedcom_setting($user_id, $gedid, 'gedcomid', null);
						echo $gedid, ":&nbsp;&nbsp;", i18n::translate('Unset GEDCOM ID for '), $user_name, "<br />";
					}
				}
			}
		}
	}
	echo "<br />";
}

// Print main menu
// NOTE: WORKING
?>
<table class="center list_table width40 <?php echo $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="3">
		<h2><?php echo i18n::translate('User administration'); ?></h2>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="topbottombar"><?php echo i18n::translate('Select an option below:'); ?></td>
	</tr>
	<tr>
		<td class="optionbox"><a href="useradmin.php?action=listusers"><?php echo i18n::translate('User List'); ?></a></td>
		<td class="optionbox" colspan="2" ><a href="useradmin.php?action=createform"><?php echo i18n::translate('Add a new user'); ?></a></td>
	</tr>
	<tr>
		<td class="optionbox"><a href="useradmin.php?action=cleanup"><?php echo i18n::translate('Cleanup users'); ?></a></td>
		<td class="optionbox" colspan="2" >
			<a href="javascript: <?php echo i18n::translate('Send message to all users'); ?>" onclick="message('all', 'messaging2', '', ''); return false;"><?php echo i18n::translate('Send message to all users'); ?></a><br />
			<a href="javascript: <?php echo i18n::translate('Send message to users who have never logged in'); ?>" onclick="message('never_logged', 'messaging2', '', ''); return false;"><?php echo i18n::translate('Send message to users who have never logged in'); ?></a><br />
			<a href="javascript: <?php echo i18n::translate('Send message to users who have not logged in for 6 months'); ?>" onclick="message('last_6mo', 'messaging2', '', ''); return false;"><?php echo i18n::translate('Send message to users who have not logged in for 6 months'); ?></a><br />
		</td>
	</tr>
	<tr>
		<td class="topbottombar" colspan="3" align="center" ><a href="admin.php"><?php echo i18n::translate('Return to the Admin menu'); ?></a></td>
	</tr>
	<tr>
		<td colspan="3" class="topbottombar"><?php echo i18n::translate('Informational'); ?></td>
	</tr>
	<tr>
	<td class="optionbox" colspan="3">
	<?php
	$totusers = 0;			// Total number of users
	$warnusers = 0;			// Users with warning
	$applusers = 0;			// Users who have not verified themselves
	$nverusers = 0;			// Users not verified by admin but verified themselves
	$adminusers = 0;		// Administrators
	$userlang = array();	// Array for user languages
	$gedadmin = array();	// Array for gedcom admins
	foreach(get_all_users() as $user_id=>$user_name) {
		$totusers = $totusers + 1;
		if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && (get_user_setting($user_id, 'verified')!="yes")) $warnusers++;
		else {
			if (get_user_setting($user_id, 'comment_exp')) {
				if ((strtotime(get_user_setting($user_id, 'comment_exp')) != "-1") && (strtotime(get_user_setting($user_id, 'comment_exp')) < time("U"))) $warnusers++;
			}
		}
		if ((get_user_setting($user_id, 'verified_by_admin') != "yes") && (get_user_setting($user_id, 'verified') == "yes")) {
			$nverusers++;
		}
		if (get_user_setting($user_id, 'verified') != "yes") {
			$applusers++;
		}
		if (get_user_setting($user_id, 'canadmin')=='Y') {
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
			if (isset($userlang[$pgv_lang["lang_name_".$user_lang]]))
				$userlang[$pgv_lang["lang_name_".$user_lang]]["number"]++;
			else {
				$userlang[$pgv_lang["lang_name_".$user_lang]]["langname"] = $user_lang;
				$userlang[$pgv_lang["lang_name_".$user_lang]]["number"] = 1;
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

	asort($userlang);
	echo "<tr valign=\"middle\"><td class=\"font11\">", i18n::translate('Users\' languages'), "</td>";
	foreach ($userlang as $key=>$ulang) {
		echo "\t<td class=\"font11\"><img src=\"", $language_settings[$ulang["langname"]]["flagsfile"], "\" class=\"brightflag\" alt=\"", $key, "\" title=\"", $key, "\" /></td><td>&nbsp;<a href=\"", encode_url("useradmin.php?action=listusers&filter=language&usrlang=".$ulang["langname"]), "\">", $key, "</a></td><td>", $ulang["number"], "</td></tr><tr class=\"vmiddle\"><td></td>\n";
	}
	echo "</tr></table>";
	echo "</td></tr></table>";
	?>
<?php
print_footer();
?>
