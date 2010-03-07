<?php
/**
 * User Account Edit Interface.
 *
 * phpGedView: Genealogy Viewer
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
 * @package PhpGedView
 * @subpackage Admin
 * @version $Id$
 */

define('PGV_SCRIPT_NAME', 'edituser.php');
require './config.php';
require PGV_ROOT.'includes/functions/functions_print_lists.php';
require PGV_ROOT.'includes/functions/functions_edit.php';

// prevent users with editing account disabled from being able to edit their account
if (get_user_setting(PGV_USER_ID, 'editaccount')!='Y') {
	header('Location: index.php?ctype=user');
	exit;
}

// Load language variables
loadLangFile('pgv_confighelp, pgv_admin, pgv_editor');

// Valid values for form variables
$ALL_ACTIONS=array('update');
$ALL_CONTACT_METHODS=array('messaging', 'messaging2', 'messaging3', 'mailto', 'none');
$ALL_DEFAULT_TABS=array(0=>'personal_facts', 1=>'notes', 2=>'ssourcess', 3=>'media', 4=>'relatives', -1=>'all', -2=>'lasttab');
$ALL_THEMES_DIRS=array();
foreach (get_theme_names() as $themename=>$themedir) {
	$ALL_THEME_DIRS[]=$themedir;
}

// Extract form variables
$form_action        =safe_POST('form_action'   );
$form_username      =safe_POST('form_username',       PGV_REGEX_USERNAME);
$form_firstname     =safe_POST('form_firstname');
$form_lastname      =safe_POST('form_lastname' );
$form_pass1         =safe_POST('form_pass1',          PGV_REGEX_PASSWORD);
$form_pass2         =safe_POST('form_pass2',          PGV_REGEX_PASSWORD);
$form_email         =safe_POST('form_email',          PGV_REGEX_EMAIL,               'email@example.com');
$form_rootid        =safe_POST('form_rootid',         PGV_REGEX_XREF,                PGV_USER_ROOT_ID   );
$form_theme         =safe_POST('form_theme',          $ALL_THEME_DIRS,               $THEME_DIR         );
$form_language      =safe_POST('form_language',       array_keys($pgv_language),     $LANGUAGE          );
$form_contact_method=safe_POST('form_contact_method', $ALL_CONTACT_METHODS,          $CONTACT_METHOD    );
$form_default_tab   =safe_POST('form_default_tab',    array_keys($ALL_DEFAULT_TABS), $GEDCOM_DEFAULT_TAB);
$form_visible_online=safe_POST('form_visible_online', 'Y', 'N');

// Respond to form action
if ($form_action=='update') {
	if ($form_username!=PGV_USER_NAME && get_user_id($form_username)) {
		print_header(i18n::translate('User administration'));
		echo '<span class="error">', i18n::translate('Duplicate user name.  A user with that user name already exists.  Please choose another user name.'), '</span><br />';
	} else {
		$alphabet=getAlphabet().'_-. ';
		$i=1;
		$pass=true;
		while (strlen($form_username) > $i) {
			if (stristr($alphabet, $form_username{$i})===false) {
				$pass=false;
				break;
			}
			$i++;
		}
		if (!$pass) {
			print_header(i18n::translate('User administration'));
			echo '<span class="error">', i18n::translate('User name contains invalid characters'), '</span><br />';
		} else {
			// Change password
			if (!empty($form_pass1)) {
				AddToLog('User changed password');
				set_user_password(PGV_USER_ID, crypt($form_pass1));
			}
			$old_firstname=get_user_setting(PGV_USER_ID, 'firstname');
			$old_lastname =get_user_setting(PGV_USER_ID, 'lastname');
			$old_email    =get_user_setting(PGV_USER_ID, 'email');
			// Change other settings
			set_user_setting(PGV_USER_ID, 'firstname',     $form_firstname);
			set_user_setting(PGV_USER_ID, 'lastname',      $form_lastname);
			set_user_setting(PGV_USER_ID, 'email',         $form_email);
			set_user_setting(PGV_USER_ID, 'theme',         $form_theme);
			set_user_setting(PGV_USER_ID, 'language',      $form_language);
			set_user_setting(PGV_USER_ID, 'contactmethod', $form_contact_method);
			set_user_setting(PGV_USER_ID, 'visibleonline', $form_visible_online);
			set_user_setting(PGV_USER_ID, 'defaulttab',    $form_default_tab);
			set_user_gedcom_setting(PGV_USER_ID, PGV_GED_ID, 'rootid', $form_rootid);

			// Change username
			if ($form_username!=PGV_USER_NAME) {
				AddToLog('User renamed to ->'.$form_username.'<-');
				rename_user(PGV_USER_ID, $form_username);
				$_SESSION['pgv_user']=$form_username;
			}
			// Reload page to pick up changes such as theme and user_id
			header('Location: edituser.php');
			exit;
		}
	}
} else {
	print_header(i18n::translate('User administration'));

	if ($ENABLE_AUTOCOMPLETE) require PGV_ROOT.'js/autocomplete.js.htm';
}

// Form validation
?>
<script language="JavaScript" type="text/javascript">
<!--
function checkform(frm) {
	if (frm.form_username.value=="") {
		alert("<?php print i18n::translate('You must enter a user name.');?>");
		frm.form_username.focus();
		return false;
	}
	if (frm.form_firstname.value=="") {
		alert("<?php print i18n::translate('You must enter a first and last name.');?>");
		frm.form_firstname.focus();
		return false;
	}
	if (frm.form_lastname.value=="") {
		alert("<?php print i18n::translate('You must enter a first and last name.');?>");
		frm.form_lastname.focus();
		return false;
	}
	if (frm.form_email.value.indexOf("@")==-1) {
		alert("<?php print i18n::translate('You must enter an email address.');?>");
		frm.user_email.focus();
		return false;
	}
	if (frm.form_pass1.value!=frm.form_pass2.value) {
		alert("<?php print i18n::translate('Passwords do not match.');?>");
		frm.form_pass1.focus();
		return false;
	}
	if (frm.form_pass1.value.length > 0 && frm.form_pass1.value.length < 6) {
		alert("<?php print i18n::translate('Passwords must contain at least 6 characters.');?>");
		frm.form_pass1.focus();
		return false;
	}
	return true;
}
var pastefield;
function paste_id(value) {
	pastefield.value=value;
}
-->
</script>
<?php

// show the form to edit a user account details
$tab=0;
echo '<form name="editform" method="post" action="" onsubmit="return checkform(this);">';
echo '<input type="hidden" name="form_action" value="update" />';
echo '<table class="list_table center ', $TEXT_DIRECTION, '">';

echo '<tr><td class="topbottombar" colspan="2"><h2>', i18n::translate('My Account'), '</h2></td></tr>';

echo '<tr><td class="topbottombar" colspan="2"><input type="submit" tabindex="', ++$tab, '" value="', i18n::translate('Update MyAccount'), '" /></td></tr>';

echo '<tr><td class="descriptionbox width20 wrap">';
echo print_help_link('edituser_username', 'qm', '', false, true);
echo i18n::translate('User name'), '</td><td class="optionbox">';
echo '<input type="text" name="form_username" tabindex="', ++$tab, '" value="', PGV_USER_NAME, '" />';
echo '</td></tr>';

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_firstname', 'qm', '', false, true);
echo i18n::translate('First Name'), '</td><td class="optionbox">';
echo '<input type="text" name="form_firstname" tabindex="', ++$tab, '" value="', get_user_setting(PGV_USER_ID, 'firstname'), '" />';
echo '</td></tr>';

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_lastname', 'qm', '', false, true);
echo i18n::translate('Last Name'), '</td><td class="optionbox">';
echo '<input type="text" name="form_lastname" tabindex="', ++$tab, '" value="', get_user_setting(PGV_USER_ID, 'lastname'), '" />';
echo '</td></tr>';

$person=Person::getInstance(PGV_USER_GEDCOM_ID);
if ($person) {
	echo '<tr><td class="descriptionbox wrap">';
	echo print_help_link("edituser_gedcomid", "qm", '', false, true);
	echo i18n::translate('GEDCOM INDI record ID'), '</td><td class="optionbox">';
	echo $person->format_list('span');
	echo '</td></tr>';
}

$person=Person::getInstance(PGV_USER_ROOT_ID);
echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_rootid', 'qm', '', false, true);
echo i18n::translate('Pedigree Chart Root Person'), '</td><td class="optionbox">';
echo '<input type="text" name="form_rootid" id="rootid" tabindex="', ++$tab, '" value="', PGV_USER_ROOT_ID, '" />';
echo print_findindi_link('rootid', '', true), '<br/>';
if ($person) {
	echo $person->format_list('span');
}
echo '</td></tr>';

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_password', 'qm', '', false, true);
echo i18n::translate('Password'), '</td><td class="optionbox">';
echo '<input type="password" name="form_pass1" tabindex="', ++$tab, '" /> ', i18n::translate('Leave password blank if you want to keep the current password.'), '</td></tr>';

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_conf_password', 'qm', '', false, true);
echo i18n::translate('Confirm Password'), '</td><td class="optionbox">';
echo '<input type="password" name="form_pass2" tabindex="', ++$tab, '" /></td></tr>';

if ($ENABLE_MULTI_LANGUAGE) {
	echo '<tr><td class="descriptionbox wrap">';
	echo print_help_link('edituser_change_lang', 'qm', '', false, true);
	echo i18n::translate('Change Language'), '</td><td class="optionbox" valign="top">';
	echo '<select name="form_language" tabindex="', ++$tab, '">';
	foreach ($pgv_language as $key=> $value) {
		if ($language_settings[$key]["pgv_lang_use"]) {
			echo '<option value="', $key, '"';
			if ($key==get_user_setting(PGV_USER_ID, 'language')) {
				echo ' selected="selected"';
			}
			echo '>', $pgv_lang[$key], '</option>';
		}
	}
	echo '</select></td></tr>';
}

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_email', 'qm', '', false, true);
echo i18n::translate('Email Address'), '</td><td class="optionbox" valign="top">';
echo '<input type="text" name="form_email" tabindex="', ++$tab, '" value="', get_user_setting(PGV_USER_ID, 'email'), '" size="50" /></td></tr>';

if ($ALLOW_USER_THEMES) {
	echo '<tr><td class="descriptionbox wrap">';
	echo print_help_link('edituser_user_theme', 'qm', '', false, true);
	echo i18n::translate('My Theme'), '</td><td class="optionbox" valign="top">';
	echo '<select name="form_theme" tabindex="', ++$tab, '">';
		echo '<option value="">', i18n::translate('Site Default'), '</option>';
		foreach (get_theme_names() as $themename=>$themedir) {
			echo '<option value="', $themedir, '"';
			if ($themedir==get_user_setting(PGV_USER_ID, 'theme')) {
				echo ' selected="selected"';
			}
			echo '>', $themename, '</option>';
		}
		echo '</select></td></tr>';
}

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('edituser_user_contact', 'qm', '', false, true);
echo i18n::translate('Preferred Contact Method'), '</td><td class="optionbox">';
echo '<select name="form_contact_method" tabindex="', ++$tab, '">';
foreach ($ALL_CONTACT_METHODS as $key=>$value) {
	if ($PGV_STORE_MESSAGES || $key>=2) {
		echo '<option value="', $value, '"';
		if ($value==get_user_setting(PGV_USER_ID, 'contactmethod')) {
			echo ' selected="selected"';
		}
		echo '>', $pgv_lang[$value], '</option>';
	}
}
echo '</select></td></tr>';

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link('useradmin_visibleonline', 'qm', '', false, true);
echo i18n::translate('Visible to other users when online'), '</td><td class="optionbox">';
echo '<input type="checkbox" name="form_visible_online" tabindex="', ++$tab, '" value="Y"';
if (get_user_setting(PGV_USER_ID, 'visibleonline')=='Y') {
	echo ' checked="checked"';
}
echo ' /></td></tr>';

echo '<tr><td class="descriptionbox wrap">';
echo print_help_link("edituser_user_default_tab", "qm", '', false, true);
echo i18n::translate('Default Tab to show on Individual Information page'), '</td><td class="optionbox">';
echo '<select name="form_default_tab" tabindex="', ++$tab, '">';
foreach ($ALL_DEFAULT_TABS as $key=>$value) {
	echo '<option value="', $key,'"';
	if ($key==get_user_setting(PGV_USER_ID, 'defaulttab')) {
		echo ' selected="selected"';
	}
	echo '>', $pgv_lang[$value], '</option>';
}
echo '</select></td></tr>';

echo '<tr><td class="topbottombar" colspan="2"><input type="submit" tabindex="', ++$tab, '" value="', i18n::translate('Update MyAccount'), '" /></td></tr>';

echo '</table></form>';

print_footer();
?>
