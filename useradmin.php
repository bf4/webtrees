<?php
/**
 * Administrative User Interface.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team
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
 * This Page Is Valid XHTML 1.0 Transitional! > 30 August 2005
 *
 * @package PhpGedView
 * @subpackage Admin
 * @version $Id$
 */

require_once 'config.php';
require_once 'includes/functions_edit.php';

loadLangFile('pgv_confighelp');

// Only admin users can access this page
if (!userIsAdmin()) {
	$loginURL = "$LOGIN_URL?url=".urlencode(basename($SCRIPT_NAME)."?".$QUERY_STRING);
	header("Location: $loginURL");
	exit;
}

// Extract GET/POST variables
foreach (array('action', 'filter', 'sort', 'ged', 'usrlang', 'oldusername', 'username', 'lastname', 'pass1', 'pass2','emailaddress', 'user_theme', 'user_language', 'new_contact_method','new_default_tab', 'new_comment', 'new_comment_exp', 'new_max_relation_path', 'new_sync_gedcom', 'new_relationship_privacy', 'new_auto_accept', 'canadmin', 'visibleonline', 'editaccount', 'verified', 'verified_by_admin') as $var) {
	if (isset($_REQUEST[$var])) {
		$$var=$_REQUEST[$var];
	} else {
		$$var='';
	}
}

if (empty($ged)) {
	$ged=$GEDCOM;
}

// Delete a user
if ($action=='deleteuser') {
	// don't delete ourselves
	if ($username!=getUserName()) {
		delete_user($username);
		AddToLog(getUserName()." deleted user -> ".$username." <-");
	}
	// User data is cached, so reload the page to ensure we're up to date
	header("Location: useradmin.php");
	exit;
}

// Save new user info to the database
if ($action=='createuser' || $action=='edituser2') {
	if (($action=='createuser' || $action=='edituser2' && $username!=$oldusername) && get_user_id($username)) {
		print_header("PhpGedView ".$pgv_lang["user_admin"]);
		print "<span class=\"error\">".$pgv_lang["duplicate_username"]."</span><br />";
	} else {
		if ($pass1!=$pass2) {
			print_header("PhpGedView ".$pgv_lang["user_admin"]);
			print "<span class=\"error\">".$pgv_lang["password_mismatch"]."</span><br />";
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
				print_header("PhpGedView ".$pgv_lang["user_admin"]);
				print "<span class=\"error\">".$pgv_lang["invalid_username"]."</span><br />";
			} else {
				// New user
				if ($action=='createuser') {
					if ($user_id=create_user($username, crypt($pass1))) {
						set_user_setting($user_id, 'reg_timestamp', date('U'));
						set_user_setting($user_id, 'sessiontime', '0');
						AddToLog("User -> {$username} <- created by ".getUserName());
					} else {
						AddToLog("User -> {$username} <- was not created by ".getUserName());
						$user_id=$username;
					}
				} else {
					$user_id=$username;
				}
				// Change password
				if ($action=='edituser2' && !empty($pass1)) {
					set_user_password($user_id, 'password', crypt($pass1));
					AddToLog("User -> {$oldusername} <- had password changed by ".getUserName());
				}
				// Change username
				if ($action=='edituser2' && $username!=$oldusername) {
					rename_user($oldusername, $username);
					AddToLog("User -> {$oldusername} <-  renamed to -> {$username} <- by ".getUserName());
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
				set_user_setting($user_id, 'sync_gedcom',          ($new_sync_gedcom=='Y') ? 'Y' : 'N');
				set_user_setting($user_id, 'relationship_privacy', ($new_relationship_privacy=='Y') ? 'Y' : 'N');
				set_user_setting($user_id, 'auto_accept',          ($new_auto_accept=='Y') ? 'Y' : 'N');
				set_user_setting($user_id, 'canadmin',             ($canadmin=='Y') ? 'Y' : 'N');
				set_user_setting($user_id, 'visibleonline',        ($visibleonline=='Y') ? 'Y' : 'N');
				set_user_setting($user_id, 'editaccount',          ($editaccount=='Y') ? 'Y' : 'N');
				set_user_setting($user_id, 'verified',             ($verified=='yes') ? 'yes' : 'no');
				set_user_setting($user_id, 'verified_by_admin',    ($verified_by_admin=='yes') ? 'yes' : 'no');
				foreach ($GEDCOMS as $GED_ID=>$ARRAY) {
					foreach (array('gedcomid', 'rootid', 'canedit') as $var) {
						set_user_gedcom_setting($user_id, $GED_ID, $var, $_REQUEST[$var.$ARRAY['id']]);
					}
				}
				// If we're verifying a new user, send them a message to let them know
				if ($newly_verified) {
					if ($verified_by_admin=='Y' && $action=='edituser2') {
						if ($LANGUAGE != $user_language) {
							loadLanguage($user_language);
						}
						if (substr($SERVER_URL, -1)=="/") {
							$serverURL=substr($SERVER_URL,0,-1);
						} else {
							$serverURL=$SERVER_URL;
						}
						$message=array();
						$message["to"]=$username;
						$headers="From: ".$PHPGEDVIEW_EMAIL;
						$message["from"]=getUserName();
						$message["subject"]=str_replace("#SERVER_NAME#", $serverURL, $pgv_lang["admin_OK_subject"]);
						$message["body"]=str_replace("#SERVER_NAME#", $serverURL, $pgv_lang["admin_OK_message"]);
						$message["created"]="";
						$message["method"]="messaging2";
						addMessage($message);
					}
				}
				//-- update Gedcom record with new email address
				if ($email_changed && $new_sync_gedcom=='Y') {
					foreach($GEDCOMS as $GED_ID=>$ARRAY) {
						$varname = 'gedcomid'.$ARRAY['id'];
						if ($_REQUEST[$varname]) {
							include_once("includes/functions_edit.php");
							$indirec=find_person_record($_REQUEST[$varname]);
							if ($indirec) {
								if (preg_match("/\d _?EMAIL/", $indirec)) {
									$indirec= preg_replace("/(\d _?EMAIL)[^\r\n]*/", "$1 ".$user_email, $indirec);
									replace_gedrec($GED_ID, $indirec);
								} else {
									$indirec.="\r\n1 EMAIL ".$user_email;
									replace_gedrec($GED_ID, $indirec);
								}
							}
						}
					}
				}
				// Reload the form cleanly, to allow the user to verify their changes
				header("Location: useradmin.php?action=edituser&username={$user_id}");
				exit;
			}
		}
	}
} else {
	print_header("PhpGedView ".$pgv_lang["user_admin"]);
}

// Print the form to edit a user
if ($action=="edituser") {
	init_calendar_popup();
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
	function checkform(frm) {
		if (frm.username.value=="") {
			alert("<?php print $pgv_lang["enter_username"]; ?>");
			frm.username.focus();
			return false;
		}
		if (frm.firstname.value=="") {
			alert("<?php print $pgv_lang["enter_fullname"]; ?>");
			frm.firstname.focus();
			return false;
		}
		if ((frm.pass1.value!="")&&(frm.pass1.value.length < 6)) {
			alert("<?php print $pgv_lang["passwordlength"]; ?>");
			frm.pass1.value = "";
			frm.pass2.value = "";
			frm.pass1.focus();
			return false;
		}
		if ((frm.emailaddress.value!="")&&(frm.emailaddress.value.indexOf("@")==-1)) {
			alert("<?php print $pgv_lang["enter_email"]; ?>");
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

	if (!isset($user['contactmethod'])) $user['contactmethod'] = "none";
	?>
	<form name="editform" method="post" action="useradmin.php" onsubmit="return checkform(this);">
	<input type="hidden" name="action" value="edituser2" />
	<input type="hidden" name="filter" value="<?php print $filter; ?>" />
	<input type="hidden" name="sort" value="<?php print $sort; ?>" />
	<input type="hidden" name="usrlang" value="<?php print $usrlang; ?>" />
	<input type="hidden" name="oldusername" value="<?php print $username; ?>" />
	<?php $tab=0; ?>
	<table class="center list_table width80 <?php print $TEXT_DIRECTION; ?>">
	<tr><td colspan="2" class="facts_label"><?php
	print "<h2>".$pgv_lang["update_user"]."</h2>";
	?>
	</td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["update_user"]; ?>" />
	<input type="button" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["back"];?>" onclick="window.location='useradmin.php?action=listusers&amp;sort=<?php print $sort;?>&amp;filter=<?php print $filter;?>&amp;usrlang=<?php print $usrlang;?>';"/>
	</td></tr>
	<tr>
	<td class="descriptionbox width20 wrap"><?php print_help_link("useradmin_username_help", "qm","username"); print $pgv_lang["username"];?></td>
	<td class="optionbox wrap"><input type="text" name="username" tabindex="<?php print ++$tab; ?>" value="<?php print $username?>" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_firstname_help", "qm", "firstname"); print $pgv_lang["firstname"];?></td>
	<td class="optionbox wrap"><input type="text" name="firstname" tabindex="<?php print ++$tab; ?>" value="<?php print PrintReady(get_user_setting($username, 'firstname'))?>" size="50" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_lastname_help", "qm","lastname");print $pgv_lang["lastname"];?></td>
	<td class="optionbox wrap"><input type="text" name="lastname" tabindex="<?php print ++$tab; ?>" value="<?php print PrintReady(get_user_setting($username, 'lastname'))?>" size="50" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_password_help", "qm","password"); print $pgv_lang["password"];?></td>
	<td class="optionbox wrap"><input type="password" name="pass1" tabindex="<?php print ++$tab; ?>" /><br /><?php print $pgv_lang["leave_blank"];?></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_conf_password_help", "qm","confirm"); print $pgv_lang["confirm"];?></td>
	<td class="optionbox wrap"><input type="password" name="pass2" tabindex="<?php print ++$tab; ?>" /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_gedcomid_help", "qm","gedcomid"); print $pgv_lang["gedcomid"];?></td>
	<td class="optionbox wrap">
	<table class="<?php print $TEXT_DIRECTION; ?>">
	<?php
	foreach($GEDCOMS as $GED_ID=>$ARRAY) {
		$varname='gedcomid'.$ARRAY['id'];
		?>
		<tr>
		<td><?php print $GED_ID;?>:&nbsp;&nbsp;</td>
		<td><input type="text" name="<?php print $varname; ?>" id="<?php print $varname; ?>" tabindex="<?php print ++$tab; ?>" value="
		<?php
		$pid=get_user_gedcom_setting($username, $GED_ID, 'gedcomid');
		print $pid."\" />";
		print_findindi_link($varname, "", false, false, $GED_ID);
		if ($pid) {
			print "\n<span class=\"list_item\"><a href=\"individual.php?pid={$pid}&ged={$GED_ID}\">".PrintReady(get_person_name($pid))."</a>";
			print_first_major_fact($pid);
			print "</span>\n";
		}
		print "</td></tr>";
	}
	?>
	</table></td></tr><tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_rootid_help", "qm", "rootid"); print $pgv_lang["rootid"];?></td>
	<td class="optionbox wrap">
	<table class="<?php print $TEXT_DIRECTION;?>">
	<?php
	foreach($GEDCOMS as $GED_ID=>$ARRAY) {
		$varname='rootid'.$ARRAY['id'];
		?>
		<tr>
		<td><?php print $GED_ID;?>:&nbsp;&nbsp;</td>
		<td> <input type="text" name="<?php print $varname; ?>" id="<?php print $varname; ?>" tabindex="<?php print ++$tab; ?>" value="
		<?php
		$pid=get_user_gedcom_setting($username, $GED_ID, 'rootid');
		print $pid."\" />";
		print_findindi_link($varname, "", false, false, $GED_ID);
		if ($pid) {
			print "\n<span class=\"list_item\"><a href=\"individual.php?pid={$pid}&ged={$GED_ID}\">".PrintReady(get_person_name($pid))."</a>";
			print_first_major_fact($pid);
			print "</span>\n";
		}
		?>
		</td></tr>
		<?php
	} ?></table>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_sync_gedcom_help", "qm", "sync_gedcom"); print $pgv_lang["sync_gedcom"];?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_sync_gedcom" tabindex="<?php print ++$tab; ?>" value="Y" <?php if (get_user_setting($username, 'sync_gedcom')=="Y") print "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_can_admin_help", "qm", "can_admin"); print $pgv_lang["can_admin"];?></td>
	<?php
	// Forms won't send the value of checkboxes if they are disabled :-(  Instead, create a hidden field
	if ($username==getUserName()) {
		?>
		<td class="optionbox wrap"><input type="checkbox" name="dummy" <?php if (get_user_setting($username, 'canadmin')=='Y') print "checked=\"checked\""; ?> disabled="disabled" /></td>
		<input type="hidden" name="canadmin" value="<?php print get_user_setting($username, 'canadmin'); ?>" />
		<?php
	} else {
		?>
		<td class="optionbox wrap"><input type="checkbox" name="canadmin" tabindex="<?php print ++$tab; ?>" value="Y" <?php if (get_user_setting($username, 'canadmin')=='Y') print "checked=\"checked\""; ?> /></td>
		<?php
	}
	?>

	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_can_edit_help", "qm","can_edit"); print $pgv_lang["can_edit"];?></td>
	<td class="optionbox wrap">
	<table class="<?php print $TEXT_DIRECTION; ?>">
	<?php
	foreach($GEDCOMS as $GED_ID=>$ARRAY) {
		$varname = 'canedit'.$ARRAY['id'];
		print "<tr><td>$GED_ID:&nbsp;&nbsp;</td><td>";
		$tab++;
		print "<select name=\"{$varname}\" id=\"{$varname}\" tabindex=\"{$tab}\">\n";
		print "<option value=\"none\"";
		if (get_user_gedcom_setting($username, $GED_ID, 'canedit')=="none") print " selected=\"selected\"";
		print ">".$pgv_lang["none"]."</option>\n";
		print "<option value=\"access\"";
		if (get_user_gedcom_setting($username, $GED_ID, 'canedit')=="access") print " selected=\"selected\"";
		print ">".$pgv_lang["access"]."</option>\n";
		print "<option value=\"edit\"";
		if (get_user_gedcom_setting($username, $GED_ID, 'canedit')=="edit") print " selected=\"selected\"";
		print ">".$pgv_lang["edit"]."</option>\n";
		print "<option value=\"accept\"";
		if (get_user_gedcom_setting($username, $GED_ID, 'canedit')=="accept") print " selected=\"selected\"";
		print ">".$pgv_lang["accept"]."</option>\n";
		print "<option value=\"admin\"";
		if (get_user_gedcom_setting($username, $GED_ID, 'canedit')=="admin") print " selected=\"selected\"";
		print ">".$pgv_lang["admin_gedcom"]."</option>\n";
		print "</select>\n";
		print "</td></tr>";
	}
	?>
	</table>
	</td>
	</tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_auto_accept_help", "qm", "user_auto_accept"); print $pgv_lang["user_auto_accept"];?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" tabindex="<?php print ++$tab; ?>" value="Y" <?php if (get_user_setting($username, 'auto_accept')=='Y') print "checked=\"checked\"";?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_relation_priv_help", "qm", "user_relationship_priv"); print $pgv_lang["user_relationship_priv"];?></td>
	<td class="optionbox wrap"><input type="checkbox" name="new_relationship_privacy" tabindex="<?php print ++$tab; ?>" value="Y" <?php if (get_user_setting($username, 'relationship_privacy')=="Y") print "checked=\"checked\"";?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_path_length_help", "qm", "user_path_length"); print $pgv_lang["user_path_length"];?></td>
	<td class="optionbox wrap"><input type="text" name="new_max_relation_path" tabindex="<?php print ++$tab; ?>" value="<?php print get_user_setting($username, 'max_relation_path'); ?>" size="5" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_email_help", "qm", "emailadress"); print $pgv_lang["emailadress"];?></td><td class="optionbox wrap"><input type="text" name="emailaddress" tabindex="<?php print ++$tab; ?>" dir="ltr" value="<?php print get_user_setting($username, 'email')?>" size="50" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verified_help", "qm", "verified"); print $pgv_lang["verified"];?></td><td class="optionbox wrap"><input type="checkbox" name="verified" tabindex="<?php print ++$tab; ?>" value="yes" <?php if (get_user_setting($username, 'verified')=="yes") print "checked=\"checked\"";?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verbyadmin_help", "qm", "verified_by_admin"); print $pgv_lang["verified_by_admin"];?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" tabindex="<?php print ++$tab; ?>" value="yes" <?php if (get_user_setting($username, 'verified_by_admin')=="yes") print "checked=\"checked\""; ?> /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_change_lang_help", "qm", "change_lang");print $pgv_lang["change_lang"];?></td><td class="optionbox wrap" valign="top"><?php
	if ($ENABLE_MULTI_LANGUAGE) {
		$tab++;
		print "<select name=\"user_language\" tabindex=\"".$tab."\" dir=\"ltr\" style=\"{ font-size: 9pt; }\">";
		foreach ($pgv_language as $key => $value) {
			if ($language_settings[$key]["pgv_lang_use"]) {
				print "\n\t\t\t<option value=\"$key\"";
				if ($key == get_user_setting($username, 'language')) print " selected=\"selected\"";
				print ">" . $pgv_lang[$key] . "</option>";
			}
		}
		print "</select>\n\t\t";
	}
	else print "&nbsp;";
	?></td></tr>
	<?php
	if ($ALLOW_USER_THEMES) {
		?>
		<tr><td class="descriptionbox wrap" valign="top" align="left"><?php print_help_link("useradmin_user_theme_help", "qm", "user_theme"); print $pgv_lang["user_theme"];?></td><td class="optionbox wrap" valign="top">
		<select name="user_theme" tabindex="<?php print ++$tab; ?>" dir="ltr">
		<option value=""><?php print $pgv_lang["site_default"]; ?></option>
		<?php
		$themes = get_theme_names();
		foreach($themes as $indexval => $themedir)
		{
		print "<option value=\"".$themedir["dir"]."\"";
		if ($themedir["dir"] == get_user_setting($username, 'theme')) print " selected=\"selected\"";
		print ">".$themedir["name"]."</option>\n";
		}
		?></select>
		</td>
		</tr>
		<?php
	}
	?>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_contact_help", "qm", "user_contact_method"); print $pgv_lang["user_contact_method"];?></td>
	<td class="optionbox wrap"><select name="new_contact_method" tabindex="<?php print ++$tab; ?>">
	<?php
	if ($PGV_STORE_MESSAGES) {
		?>
		<option value="messaging" <?php if (get_user_setting($username, 'contactmethod')=='messaging') print "selected=\"selected\""; ?>><?php print $pgv_lang["messaging"];?></option>
		<option value="messaging2" <?php if (get_user_setting($username, 'contactmethod')=='messaging2') print "selected=\"selected\""; ?>><?php print $pgv_lang["messaging2"];?></option>
		<?php
	} else {
		?>
		<option value="messaging3" <?php if (get_user_setting($username, 'contactmethod')=='messaging3') print "selected=\"selected\""; ?>><?php print $pgv_lang["messaging3"];?></option>
		<?php
	}
	?>
	<option value="mailto" <?php if (get_user_setting($username, 'contactmethod')=='mailto') print "selected=\"selected\""; ?>><?php print $pgv_lang["mailto"];?></option>
	<option value="none" <?php if (get_user_setting($username, 'contactmethod')=='none') print "selected=\"selected\""; ?>><?php print $pgv_lang["no_messaging"];?></option>
	</select>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_visibleonline_help", "qm", "visibleonline"); print $pgv_lang["visibleonline"];?></td>
	<td class="optionbox wrap"><input type="checkbox" name="visibleonline" tabindex="<?php print ++$tab; ?>" value="Y" <?php if (get_user_setting($username, 'visibleonline')=='Y') print "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_editaccount_help", "qm", "editaccount"); print $pgv_lang["editaccount"];?></td>
	<td class="optionbox wrap"><input type="checkbox" name="editaccount" tabindex="<?php print ++$tab; ?>" value="Y" <?php if (get_user_setting($username, 'editaccount')=='Y') print "checked=\"checked\""; ?> /></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_default_tab_help", "qm", "user_default_tab"); print $pgv_lang["user_default_tab"];?></td>
	<td class="optionbox wrap"><select name="new_default_tab" tabindex="<?php print ++$tab; ?>">
	<option value="0" <?php if (get_user_setting($username, 'defaulttab')==0) print "selected=\"selected\""; ?>><?php print $pgv_lang["personal_facts"];?></option>
	<option value="1" <?php if (get_user_setting($username, 'defaulttab')==1) print "selected=\"selected\""; ?>><?php print $pgv_lang["notes"];?></option>
	<option value="2" <?php if (get_user_setting($username, 'defaulttab')==2) print "selected=\"selected\""; ?>><?php print $pgv_lang["ssourcess"];?></option>
	<option value="3" <?php if (get_user_setting($username, 'defaulttab')==3) print "selected=\"selected\""; ?>><?php print $pgv_lang["media"];?></option>
	<option value="4" <?php if (get_user_setting($username, 'defaulttab')==4) print "selected=\"selected\""; ?>><?php print $pgv_lang["relatives"];?></option>
	<option value="-1" <?php if (get_user_setting($username, 'defaulttab')==-1) print "selected=\"selected\""; ?>><?php print $pgv_lang["all"];?></option>
	<option value="-2" <?php if (get_user_setting($username, 'defaulttab')==-2) print "selected=\"selected\""; ?>><?php print $pgv_lang["lasttab"];?></option>
	</select>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment_help", "qm", "comment"); print $pgv_lang["comment"];?></td>
	<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment" tabindex="<?php print ++$tab; ?>" ><?php $tmp = stripslashes(PrintReady(get_user_setting($username, 'comment'))); print $tmp; ?></textarea></td>
	</tr>
	<tr>
	<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment_exp_help", "qm", "comment_exp"); print $pgv_lang["comment_exp"];?></td>
	<td class="optionbox wrap"><input type="text" name="new_comment_exp" id="new_comment_exp" tabindex="<?php print ++$tab; ?>" value="<?php print get_user_setting($username, 'comment_exp'); ?>" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["update_user"]; ?>" />
	<input type="button" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["back"];?>" onclick="window.location='useradmin.php?action=listusers&amp;sort=<?php print $sort;?>&amp;filter=<?php print $filter;?>&amp;usrlang=<?php print $usrlang;?>';"/>
	</td></tr>
	</table>
	</form>
	<?php
	print_footer();
	exit;
}

//-- print out a list of the current users
if ($action == "listusers") {
	$showprivs=($view=="preview"); // expand gedcom privs by default in print-preview

	switch ($sort) {
		case "sortfname":
			$users = get_all_users("asc", "firstname","lastname");
			break;
		case "sortlname":
			$users = get_all_users("asc", "lastname","firstname");
			break;
		case "sortllgn":
			$users = get_all_users("desc","sessiontime");
			break;
		case "sortusername":
			$users = get_all_users("asc","username");
			break;
		case "sortreg":
			$users = get_all_users("desc","reg_timestamp");
			break;
		case "sortver":
			$users = get_all_users("asc","verified");
			break;
		case "sortveradm":
			$users = get_all_users("asc","verified_by_admin");
			break;
		default:
			$users = get_all_users("asc","username");
			break;
	}

	// First filter the users, otherwise the javascript to unfold priviledges gets disturbed
	foreach($users as $user_id=>$user) {
		if (!isset($language_settings[get_user_setting($user_id, 'language')]))
			set_user_setting($user_id, 'language', $LANGUAGE);
		if ($filter == "warnings") {
			if (get_user_setting($user_id, 'comment_exp')) {
				if ((strtotime(get_user_setting($user_id, 'comment_exp')) == "-1") || (strtotime(get_user_setting($user_id, 'comment_exp')) >= time("U"))) unset($users[$user_id]);
			}
			else if (((date("U") - get_user_setting($user_id, 'reg_timestamp')) <= 604800) || (get_user_setting($user, 'verified')=="yes")) unset($users[$user_id]);
		}
		else if ($filter == "adminusers") {
			if (get_user_setting($user_id, 'canadmin')!='Y') unset($users[$user_id]);
		}
		else if ($filter == "usunver") {
			if (get_user_setting($user_id,'verified') == "yes") unset($users[$user_id]);
		}
		else if ($filter == "admunver") {
			if ((get_user_setting($user_id,'verified_by_admin') == "yes") || (get_user_setting($user_id,'verified') != "yes")) {
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
	<table class="center list_table width80 <?php print $TEXT_DIRECTION; ?>">
	<tr><td colspan="<?php if ($view == "preview") print "8"; else print "11"; ?>" class="facts_label"><?php
		print "<h2>".$pgv_lang["current_users"]."</h2>";
	?>
	</td></tr>
	<tr>
	<?php if ($view!="preview") { ?>
	<td colspan="6" class="topbottombar rtl"><a href="useradmin.php?action=createform"><?php print $pgv_lang["add_user"];?></a></td>
	<?php } ?>
	<td colspan="<?php if ($view == "preview") print "8"; else print "5"; ?>" class="topbottombar rtl"><a href="useradmin.php"><?php if ($view != "preview") print $pgv_lang["back_useradmin"]; else print "&nbsp;";?></a></td>
	</tr>
	<tr>
	<?php if ($view != "preview") {
	print "<td class=\"descriptionbox wrap\">";
	print $pgv_lang["edit"]."</td>";
	print "<td class=\"descriptionbox wrap\">";
	print $pgv_lang["message"]."</td>";
	} ?>
	<td class="descriptionbox wrap"><?php print "<a href=\"useradmin.php?action=listusers&amp;sort=sortusername&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">"; ?><?php print $pgv_lang["username"]; ?></a></td>
	<td class="descriptionbox wrap"><?php print "<a href=\"useradmin.php?action=listusers&amp;sort=sortlname&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">"; ?><?php print $pgv_lang["full_name"]; ?></a></td>
	<td class="descriptionbox wrap"><?php print $pgv_lang["inc_languages"]; ?></td>
	<td class="descriptionbox" style="padding-left:2px"><a href="javascript: <?php print $pgv_lang["privileges"];?>" onclick="<?php
	$k = 1;
	for ($i=1, $max=count($users)+1; $i<=$max; $i++) print "expand_layer('user-geds".$i."'); ";
	print " return false;\"><img id=\"user-geds".$k."_img\" src=\"".$PGV_IMAGE_DIR."/";
	if ($showprivs == false) print $PGV_IMAGES["plus"]["other"];
	else print $PGV_IMAGES["minus"]["other"];
	print "\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" /></a>";
	print "<div id=\"user-geds".$k."\" style=\"display: ";
	if ($showprivs == false) print "none\">";
	else print "block\">";
	print "</div>&nbsp;";
	print $pgv_lang["privileges"];?>
	</td>
	<td class="descriptionbox wrap"><?php print "<a href=\"useradmin.php?action=listusers&amp;sort=sortreg&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">"; ?><?php print $pgv_lang["date_registered"]; ?></a></td>
	<td class="descriptionbox wrap"><?php print "<a href=\"useradmin.php?action=listusers&amp;sort=sortllgn&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">"; ?><?php print $pgv_lang["last_login"]; ?></a></td>
	<td class="descriptionbox wrap"><?php print "<a href=\"useradmin.php?action=listusers&amp;sort=sortver&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">"; ?><?php print $pgv_lang["verified"]; ?></a></td>
	<td class="descriptionbox wrap"><?php print "<a href=\"useradmin.php?action=listusers&amp;sort=sortveradm&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">"; ?><?php print $pgv_lang["verified_by_admin"]; ?></a></td>
	<?php if ($view != "preview") {
	print "<td class=\"descriptionbox wrap\">";
	print $pgv_lang["delete"]."</td>";
	} ?>
	</tr>
	<?php
	$k++;
	foreach($users as $user) {
		print "<tr>\n";
		if ($view != "preview") {
			print "\t<td class=\"optionbox wrap\">";
			print "<a href=\"useradmin.php?action=edituser&amp;username=".urlencode($user)."&amp;sort=".$sort."&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\">".$pgv_lang["edit"]."</a></td>\n";
			print "\t<td class=\"optionbox wrap\">";
			if ($user!=getUserName()) print "<a href=\"javascript:;\" onclick=\"return message('".$user."');\">".$pgv_lang["message"]."</a>";
			print "</td>\n";
		}
		if (get_user_setting($user, "comment_exp")) {
			if ((strtotime(get_user_setting($user, "comment_exp")) != "-1") && (strtotime(get_user_setting($user, "comment_exp")) < time("U"))) print "\t<td class=\"optionbox red\">".$user;
			else print "\t<td class=\"optionbox wrap\">".$user;
		}
		else print "\t<td class=\"optionbox wrap\">".$user;
		if (get_user_setting($user, "comment")) print "<br /><img class=\"adminicon\" align=\"top\" alt=\"".PrintReady(stripslashes(get_user_setting($user, "comment")))."\"  title=\"".PrintReady(stripslashes(get_user_setting($user, "comment")))."\"  src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["notes"]["small"]."\">";
		print "</td>\n";
		$userName = getUserFullName($user);
		if ($TEXT_DIRECTION=="ltr") print "\t<td class=\"optionbox wrap\">".$userName. getLRM() . "</td>\n";
		else                        print "\t<td class=\"optionbox wrap\">".$userName. getRLM() . "</td>\n";
		print "\t<td class=\"optionbox wrap\">".$pgv_lang["lang_name_".get_user_setting($user, 'language')]."<br /><img src=\"".$language_settings[get_user_setting($user, 'language')]["flagsfile"]."\" class=\"brightflag\" alt=\"".$pgv_lang["lang_name_".get_user_setting($user, 'language')]."\" title=\"".$pgv_lang["lang_name_".get_user_setting($user, 'language')]."\" /></td>\n";
		print "\t<td class=\"optionbox\">";
		print "<a href=\"javascript: ".$pgv_lang["privileges"]."\" onclick=\"expand_layer('user-geds".$k."'); return false;\"><img id=\"user-geds".$k."_img\" src=\"".$PGV_IMAGE_DIR."/";
		if ($showprivs == false) print $PGV_IMAGES["plus"]["other"];
		else print $PGV_IMAGES["minus"]["other"];
		print "\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" />";
		print "</a>";
		print "<div id=\"user-geds".$k."\" style=\"display: ";
		if ($showprivs == false) print "none\">";
		else print "block\">";
		print "<ul>";
		if (get_user_setting($user, 'canadmin')=='Y') {
			print "<li class=\"warning\">".$pgv_lang["can_admin"]."</li>\n";
		}
		uksort($GEDCOMS, "strnatcasecmp");
		reset($GEDCOMS);
		foreach($GEDCOMS as $gedid=>$gedcom) {
			$vval = get_user_gedcom_setting($user, $gedid, 'canedit');
			if ($vval == "") $vval = "none";
			$uged = get_user_gedcom_setting($user, $gedid, 'gedcomid');
			if ($vval=="accept" || $vval=="admin") print "<li class=\"warning\">";
			else print "<li>";
			print $pgv_lang[$vval]." ";
			if ($uged != "") print "<a href=\"individual.php?pid=".$uged."&amp;ged=".$gedid."\">".$gedid."</a></li>\n";
			else print $gedid."</li>\n";
		}
		print "</ul>";
		print "</div>";
		$k++;
		print "</td>\n";
		if (((date("U") - get_user_setting($user, 'reg_timestamp')) > 604800) && (get_user_setting($user, 'verified')!="yes")) print "\t<td class=\"optionbox red\">";
		else print "\t<td class=\"optionbox wrap\">";
		print format_timestamp(get_user_setting($user,'reg_timestamp'));
		print "</td>\n";
		print "\t<td class=\"optionbox wrap\">";
		if (get_user_setting($user,'reg_timestamp') > get_user_setting($user,'sessiontime')) {
			print $pgv_lang["never"];
		} else {
			print format_timestamp(get_user_setting($user,'sessiontime'));
		}
		print "</td>\n";
		print "\t<td class=\"optionbox wrap\">";
		if (get_user_setting($user, 'verified')=="yes") print $pgv_lang["yes"];
		else print $pgv_lang["no"];
		print "</td>\n";
		print "\t<td class=\"optionbox wrap\">";
		if (get_user_setting($user, 'verified_by_admin')=="yes") print $pgv_lang["yes"];
		else print $pgv_lang["no"];
		print "</td>\n";
		if ($view != "preview") {
			print "\t<td class=\"optionbox wrap\">";
			if (getUserName()!=$user) print "<a href=\"useradmin.php?action=deleteuser&amp;username=".urlencode($user)."&amp;sort=".$sort."&amp;filter=".$filter."&amp;usrlang=".$usrlang."&amp;ged=".$ged."\" onclick=\"return confirm('".$pgv_lang["confirm_user_delete"]." $user');\">".$pgv_lang["delete"]."</a>";
			print "</td>\n";
		}
		print "</tr>\n";
	}
	?>
	<tr>
		<?php if ($view!="preview") { ?>
		<td colspan="6" class="topbottombar rtl"><a href="useradmin.php?action=createform"><?php print $pgv_lang["add_user"];?></a></td>
		<?php } ?>
		<td colspan="<?php if ($view == "preview") print "8"; else print "5"; ?>" class="topbottombar rtl"><a href="useradmin.php"><?php  if ($view != "preview") print $pgv_lang["back_useradmin"]; else print "&nbsp;"; ?></a></td>
	</tr>
	</table>
	<?php
	print_footer();
	exit;
}

// -- print out the form to add a new user
// NOTE: WORKING
if ($action == "createform") {
	init_calendar_popup();
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
		function checkform(frm) {
			if (frm.username.value=="") {
				alert("<?php print $pgv_lang["enter_username"]; ?>");
				frm.username.focus();
				return false;
			}
			if (frm.firstname.value=="") {
				alert("<?php print $pgv_lang["enter_fullname"]; ?>");
				frm.firstname.focus();
				return false;
			}
			if (frm.pass1.value=="") {
				alert("<?php print $pgv_lang["enter_password"]; ?>");
				frm.pass1.focus();
				return false;
			}
			if (frm.pass2.value=="") {
				alert("<?php print $pgv_lang["confirm_password"]; ?>");
				frm.pass2.focus();
				return false;
			}
			if (frm.pass1.value.length < 6) {
				alert("<?php print $pgv_lang["passwordlength"]; ?>");
				frm.pass1.value = "";
				frm.pass2.value = "";
				frm.pass1.focus();
				return false;
			}
			if ((frm.emailaddress.value!="")&&(frm.emailaddress.value.indexOf("@")==-1)) {
				alert("<?php print $pgv_lang["enter_email"]; ?>");
				frm.emailaddress.focus();
				return false;
			}
			return true;
		}
	//-->
	</script>

	<form name="newform" method="post" action="useradmin.php" onsubmit="return checkform(this);">
	<input type="hidden" name="action" value="createuser" />
	<!--table-->
	<?php $tab = 0; ?>
	<table class="center list_table width80 <?php print $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="2">
		<h2><?php print $pgv_lang["add_user"];?></h2>
		</td>
	</tr>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["create_user"]; ?>" />
	<input type="button" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["back"];?>" onclick="window.location='useradmin.php';"/>
	</td></tr>
		<tr><td class="descriptionbox wrap width20"><?php print_help_link("useradmin_username_help", "qm", "username"); print $pgv_lang["username"];?></td><td class="optionbox wrap"><input type="text" name="username" tabindex="<?php print ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_firstname_help", "qm","firstname"); print $pgv_lang["firstname"];?></td><td class="optionbox wrap"><input type="text" name="firstname" tabindex="<?php print ++$tab; ?>" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_lastname_help", "qm", "lastname"); print $pgv_lang["lastname"];?></td><td class="optionbox wrap"><input type="text" name="lastname" tabindex="<?php print ++$tab; ?>" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_password_help", "qm", "password"); print $pgv_lang["password"];?></td><td class="optionbox wrap"><input type="password" name="pass1" tabindex="<?php print ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_conf_password_help", "qm", "confirm"); print $pgv_lang["confirm"];?></td><td class="optionbox wrap"><input type="password" name="pass2" tabindex="<?php print ++$tab; ?>" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_gedcomid_help", "qm","gedcomid"); print $pgv_lang["gedcomid"];?></td><td class="optionbox wrap">

		<table class="<?php print $TEXT_DIRECTION; ?>">
		<?php
		foreach($GEDCOMS as $GED_ID=>$ARRAY) {
			$varname='gedcomid'.$ARRAY['id'];
			?>
			<tr>
			<td><?php print $GED_ID;?>:&nbsp;&nbsp;</td>
			<td><input type="text" name="<?php print $varname;?>" id="<?php print $varname;?>" tabindex="<?php print ++$tab; ?>" value="
			<?php
			print "\" />\n";
			print_findindi_link($varname, "", false, false, $GED_ID);
			print "</td></tr>\n";
		}
		?>
		</table>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_rootid_help", "qm","rootid"); print $pgv_lang["rootid"];?></td><td class="optionbox wrap">
		<table class="<?php print $TEXT_DIRECTION; ?>">
		<?php
		foreach($GEDCOMS as $GED_ID=>$ARRAY) {
			$varname='rootid'.$ARRAY['id'];
			?>
			<tr>
			<td><?php print $GED_ID;?>:&nbsp;&nbsp;</td>
			<td><input type="text" name="<?php print $varname;?>" id="<?php print $varname;?>" tabindex="<?php print ++$tab; ?>" value="
			<?php
			print "\" />\n";
			print_findindi_link($varname, "", false, false, $GED_ID);
			print "</td></tr>\n";
		}
		print "</table>";
		?>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_sync_gedcom_help", "qm","sync_gedcom"); print $pgv_lang["sync_gedcom"];?></td>
		<td class="optionbox wrap"><input type="checkbox" name="new_sync_gedcom" tabindex="<?php print ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_can_admin_help", "qm","can_admin"); print $pgv_lang["can_admin"];?></td><td class="optionbox wrap"><input type="checkbox" name="canadmin" tabindex="<?php print ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_can_edit_help", "qm","can_edit");print $pgv_lang["can_edit"];?></td><td class="optionbox wrap">
		<?php
		foreach($GEDCOMS as $GED_ID=>$ARRAY) {
			$varname='canedit'.$ARRAY['id'];
			$tab++;
			print "<select name=\"$varname\" tabindex=\"".$tab."\">\n";
			print "<option value=\"none\"";
			print ">".$pgv_lang["none"]."</option>\n";
			print "<option value=\"access\" selected=\"selected\"";
			print ">".$pgv_lang["access"]."</option>\n";
			print "<option value=\"edit\"";
			print ">".$pgv_lang["edit"]."</option>\n";
			print "<option value=\"accept\"";
			print ">".$pgv_lang["accept"]."</option>\n";
			print "<option value=\"admin\"";
			print ">".$pgv_lang["admin_gedcom"]."</option>\n";
			print "</select> $GED_ID<br />\n";
		}
		?>
		</td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_auto_accept_help", "qm", "user_auto_accept");print $pgv_lang["user_auto_accept"];?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_auto_accept" tabindex="<?php print ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_relation_priv_help", "qm", "user_relationship_priv");print $pgv_lang["user_relationship_priv"];?></td>
			<td class="optionbox wrap"><input type="checkbox" name="new_relationship_privacy" tabindex="<?php print ++$tab; ?>" value="Y" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_path_length_help", "qm", "user_path_length"); print $pgv_lang["user_path_length"];?></td>
			<td class="optionbox wrap"><input type="text" name="new_max_relation_path" tabindex="<?php print ++$tab; ?>" value="0" size="5" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_email_help", "qm", "emailadress"); print $pgv_lang["emailadress"];?></td><td class="optionbox wrap"><input type="text" name="emailaddress" tabindex="<?php print ++$tab; ?>" value="" size="50" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verified_help", "qm", "verified"); print $pgv_lang["verified"];?></td><td class="optionbox wrap"><input type="checkbox" name="verified" tabindex="<?php print ++$tab; ?>" value="yes" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_verbyadmin_help", "qm", "verified_by_admin"); print $pgv_lang["verified_by_admin"];?></td><td class="optionbox wrap"><input type="checkbox" name="verified_by_admin" tabindex="<?php print ++$tab; ?>" value="yes" checked="checked" /></td></tr>
		<tr><td class="descriptionbox wrap"><?php print_help_link("useradmin_change_lang_help", "qm", "change_lang");print $pgv_lang["change_lang"];?></td><td class="optionbox wrap" valign="top"><?php

		$user_lang = get_user_setting(GetUserName(), 'language');
		if ($ENABLE_MULTI_LANGUAGE) {
			$tab++;
			print "<select name=\"user_language\" tabindex=\"".$tab."\" style=\"{ font-size: 9pt; }\">";
			foreach ($pgv_language as $key => $value) {
				if ($language_settings[$key]["pgv_lang_use"]) {
					print "\n\t\t\t<option value=\"$key\"";
					if ($key == $user_lang) {
						print " selected=\"selected\"";
					}
					print ">" . $pgv_lang[$key] . "</option>";
				}
			}
			print "</select>\n\t\t";
		}
		else print "&nbsp;";
		?></td></tr>
		<?php if ($ALLOW_USER_THEMES) { ?>
			<tr><td class="descriptionbox wrap" valign="top" align="left"><?php print_help_link("useradmin_user_theme_help", "qm", "user_theme"); print $pgv_lang["user_theme"];?></td><td class="optionbox wrap" valign="top">
			<select name="new_user_theme" tabindex="<?php print ++$tab; ?>">
			<option value="" selected="selected"><?php print $pgv_lang["site_default"]; ?></option>
			<?php
			$themes = get_theme_names();
			foreach($themes as $indexval => $themedir) {
				print "<option value=\"".$themedir["dir"]."\"";
				print ">".$themedir["name"]."</option>\n";
			}
			?>
			</select>
			</td></tr>
		<?php } ?>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_contact_help", "qm", "user_contact_method"); print $pgv_lang["user_contact_method"];?></td>
			<td class="optionbox wrap"><select name="new_contact_method" tabindex="<?php print ++$tab; ?>">
			<?php if ($PGV_STORE_MESSAGES) { ?>
				<option value="messaging"><?php print $pgv_lang["messaging"];?></option>
				<option value="messaging2" selected="selected"><?php print $pgv_lang["messaging2"];?></option>
			<?php } else { ?>
				<option value="messaging3" selected="selected"><?php print $pgv_lang["messaging3"];?></option>
			<?php } ?>
				<option value="mailto"><?php print $pgv_lang["mailto"];?></option>
				<option value="none"><?php print $pgv_lang["no_messaging"];?></option>
			</select>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_visibleonline_help", "qm", "visibleonline"); print $pgv_lang["visibleonline"];?></td>
			<td class="optionbox wrap"><input type="checkbox" name="visibleonline" tabindex="<?php print ++$tab; ?>" value="Y" <?php print "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_editaccount_help", "qm", "editaccount"); print $pgv_lang["editaccount"];?></td>
			<td class="optionbox wrap"><input type="checkbox" name="editaccount" tabindex="<?php print ++$tab; ?>" value="Y" <?php print "checked=\"checked\""; ?> /></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_user_default_tab_help", "qm", "user_default_tab"); print $pgv_lang["user_default_tab"];?></td>
			<td class="optionbox wrap"><select name="new_default_tab" tabindex="<?php print ++$tab; ?>">
				<option value="0" <?php if ($GEDCOM_DEFAULT_TAB==0) print "selected=\"selected\""; ?>><?php print $pgv_lang["personal_facts"];?></option>
				<option value="1" <?php if ($GEDCOM_DEFAULT_TAB==1) print "selected=\"selected\""; ?>><?php print $pgv_lang["notes"];?></option>
				<option value="2" <?php if ($GEDCOM_DEFAULT_TAB==2) print "selected=\"selected\""; ?>><?php print $pgv_lang["ssourcess"];?></option>
				<option value="3" <?php if ($GEDCOM_DEFAULT_TAB==3) print "selected=\"selected\""; ?>><?php print $pgv_lang["media"];?></option>
				<option value="4" <?php if ($GEDCOM_DEFAULT_TAB==4) print "selected=\"selected\""; ?>><?php print $pgv_lang["relatives"];?></option>
				<option value="-1" <?php if ($GEDCOM_DEFAULT_TAB==-1) print "selected=\"selected\""; ?>><?php print $pgv_lang["all"];?></option>
				<option value="-2" <?php if ($GEDCOM_DEFAULT_TAB==-2) print "selected=\"selected\""; ?>><?php print $pgv_lang["lasttab"];?></option>
				</select>
			</td>
		</tr>
		<?php if (userIsAdmin()) { ?>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment_help", "qm", "comment"); print $pgv_lang["comment"];?></td>
			<td class="optionbox wrap"><textarea cols="50" rows="5" name="new_comment" tabindex="<?php print ++$tab; ?>" ></textarea></td>
		</tr>
		<tr>
			<td class="descriptionbox wrap"><?php print_help_link("useradmin_comment_exp_help", "qm", "comment_exp"); print $pgv_lang["comment_exp"];?></td>
			<td class="optionbox wrap"><input type="text" name="new_comment_exp" tabindex="<?php print ++$tab; ?>" id="new_comment_exp" />&nbsp;&nbsp;<?php print_calendar_popup("new_comment_exp"); ?></td>
		</tr>
		<?php } ?>
	<tr><td class="topbottombar" colspan="2">
	<input type="submit" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["create_user"]; ?>" />
	<input type="button" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["back"];?>" onclick="window.location='useradmin.php';"/>
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
	<table class="center list_table width80 <?php print $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="2">
		<h2><?php print $pgv_lang["cleanup_users"];?></h2>
		</td>
	</tr>
	<?php
	// Check for idle users
	if (!isset($month)) $month = 1;
	print "<tr><td class=\"descriptionbox\">".$pgv_lang["usr_idle"]."</td>";
	print "<td class=\"optionbox\"><select onchange=\"document.location=options[selectedIndex].value;\">";
	for($i=1; $i<=12; $i++) {
		print "<option value=\"useradmin.php?action=cleanup&amp;month=$i\"";
		if ($i == $month) print " selected=\"selected\"";
		print " >".$i."</option>";
	}
	print "</select></td></tr>";
	?>
	<tr><td class="topbottombar" colspan="2"><?php print $pgv_lang["options"]; ?></td></tr>
	<?php
	// Check users not logged in too long
	$ucnt = 0;
	foreach(get_all_users() as $user) {
		$userName = getUserFullName($user);
		if (get_user_setting($user,'sessiontime') == "0")
			$datelogin = get_user_setting($user, 'reg_timestamp');
		else
			$datelogin = get_user_setting($user, 'sessiontime');
		if ((mktime(0, 0, 0, (int)date("m")-$month, (int)date("d"), (int)date("Y")) > $datelogin) && (get_user_setting($user,'verified') == "yes") && (get_user_setting($user, 'verified_by_admin') == "yes")) {
			?><tr><td class="descriptionbox"><?php print $user." - ".$userName.":&nbsp;&nbsp;".$pgv_lang["usr_idle_toolong"];
			$date=new GedcomDate(date("d M Y", $datelogin));
			print $date->Display(false);
			$ucnt++;
			?></td><td class="optionbox"><input type="checkbox" name="<?php print "del_".str_replace(array(".","-"," "), array("_","_","_"), $user); ?>" value="yes" /></td></tr><?php
		}
	}

	// Check unverified users
	foreach(get_all_users() as $user) {
		if (((date("U") - get_user_setting($user,'reg_timestamp')) > 604800) && (get_user_setting($user,'verified')!="yes")) {
			$userName = getUserFullName($user);
			?><tr><td class="descriptionbox"><?php print $user." - ".$userName.":&nbsp;&nbsp;".$pgv_lang["del_unveru"];
			$ucnt++;
			?></td><td class="optionbox"><input type="checkbox" checked="checked" name="<?php print "del_".str_replace(array(".","-"," "), array("_","_","_"), $user); ?>" value="yes" /></td></tr><?php
		}
	}

	// Check users not verified by admin
	foreach(get_all_users() as $user) {
		if ((get_user_setting($user,'verified_by_admin')!="yes") && (get_user_setting($user,'verified') == "yes")) {
			$userName = getUserFullName($user);
			?><tr><td  class="descriptionbox"><?php print $user." - ".$userName.":&nbsp;&nbsp;".$pgv_lang["del_unvera"];
			?></td><td class="optionbox"><input type="checkbox" name="<?php print "del_".str_replace(array(".","-"," "), array("_","_","_"), $user); ?>" value="yes" /></td></tr><?php
			$ucnt++;
		}
	}

	// Then check obsolete gedcom rights
	$gedrights = array();
	foreach(get_all_users() as $user) {
		foreach(unserialize(get_user_setting($user, 'canedit')) as $gedid=>$data) {
			if ((!isset($GEDCOMS[$gedid])) && (!in_array($gedid, $gedrights))) $gedrights[] = $gedid;
		}
		foreach(unserialize(get_user_setting($user, 'gedcomid')) as $gedid=>$data) {
			if ((!isset($GEDCOMS[$gedid])) && (!in_array($gedid, $gedrights))) $gedrights[] = $gedid;
		}
		foreach(unserialize(get_user_setting($user, 'rootid')) as $gedid=>$data) {
			if ((!isset($GEDCOMS[$gedid])) && (!in_array($gedid, $gedrights))) $gedrights[] = $gedid;
		}
	}
	ksort($gedrights);
	foreach($gedrights as $key=>$ged) {
		?><tr><td class="descriptionbox"><?php print $ged.":&nbsp;&nbsp;".$pgv_lang["del_gedrights"];
		?></td><td class="optionbox"><input type="checkbox" checked="checked" name="<?php print "delg_".str_replace(array(".","-"," "), array("_","_","_"), $ged); ?>" value="yes" /></td></tr><?php
		$ucnt++;
	}
	if ($ucnt == 0) {
		print "<tr><td class=\"warning\">";
		print $pgv_lang["usr_no_cleanup"]."</td></tr>";
	}?>
	<tr><td class="topbottombar" colspan="2">
	<?php
	if ($ucnt >0) {
		?><input type="submit" value="<?php print $pgv_lang["del_proceed"]; ?>" />&nbsp;<?php
	}?>
	<input type="button" value="<?php print $pgv_lang["back"];?>" onclick="window.location='useradmin.php';"/>
	</td></tr></table>
	</form><?php
	print_footer();
	exit;
}
// NOTE: No table parts
if ($action == "cleanup2") {
	foreach(get_all_users() as $user) {
		$var = "del_".str_replace(array(".","-"," "), array("_","_","_"), $user);
		if (isset($$var)) {
			delete_user($user);
			AddToLog(getUserName()." deleted user -> ".$user." <-");
			print $pgv_lang["usr_deleted"]; print $user."<br />";
		} else {
			foreach(unserialize(get_user_setting($user,'canedit')) as $gedid=>$data) {
				$var = "delg_".str_replace(array(".","-"," "), array("_","_","_"), $gedid);
				if (isset($$var) && get_user_gedcom_setting($user, $gedid, 'canedit')) {
					set_user_gedcom_setting($user, $gedid, 'canedit', 'none');
					print $gedid.":&nbsp;&nbsp;".$pgv_lang["usr_unset_rights"].$user."<br />";
				}
			}
			foreach(unserialize(get_user_setting($user,'rootid')) as $gedid=>$data) {
				$var = "delg_".str_replace(array(".","-"," "), array("_","_","_"), $gedid);
				if (isset($$var) && get_user_gedcom_setting($user, $gedid, 'rootid')) {
					set_user_gedcom_setting($user, $gedid, 'rootid', null);
					print $gedid.":&nbsp;&nbsp;".$pgv_lang["usr_unset_rootid"].$user."<br />";
				}
			}
			foreach(unserialize(get_user_setting($user,'gedcomid')) as $gedid=>$data) {
				$var = "delg_".str_replace(array(".","-"," "), array("_","_","_"), $gedid);
				if (isset($$var) && get_user_gedcom_setting($user, $gedid, 'gedcomid')) {
					set_user_gedcom_setting($user, $gedid, 'gedcomid', null);
					print $gedid.":&nbsp;&nbsp;".$pgv_lang["usr_unset_gedcomid"].$user."<br />";
				}
			}
		}
	}
	print "<br />";
}

// Print main menu
// NOTE: WORKING
?>
<table class="center list_table width40 <?php print $TEXT_DIRECTION; ?>">
	<tr>
		<td class="facts_label" colspan="3">
		<h2><?php print $pgv_lang["user_admin"];?></h2>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="topbottombar"><?php print $pgv_lang["select_an_option"]; ?></td>
	</tr>
	<tr>
		<td class="optionbox"><a href="useradmin.php?action=listusers"><?php print $pgv_lang["current_users"];?></a></td>
		<td class="optionbox"><a href="useradmin.php?action=createform"><?php print $pgv_lang["add_user"];?></a></td>
	</tr>
	<tr>
		<td class="optionbox"><a href="useradmin.php?action=cleanup"><?php print $pgv_lang["cleanup_users"];?></a></td>
		<td class="optionbox">
			<a href="javascript: <?php print $pgv_lang["message_to_all"]; ?>" onclick="message('all', 'messaging2', '', ''); return false;"><?php print $pgv_lang["message_to_all"]; ?></a><br />
			<a href="javascript: <?php print $pgv_lang["broadcast_never_logged_in"]; ?>" onclick="message('never_logged', 'messaging2', '', ''); return false;"><?php print $pgv_lang["broadcast_never_logged_in"]; ?></a><br />
			<a href="javascript: <?php print $pgv_lang["broadcast_not_logged_6mo"]; ?>" onclick="message('last_6mo', 'messaging2', '', ''); return false;"><?php print $pgv_lang["broadcast_not_logged_6mo"]; ?></a><br />
		</td>
	</tr>
	<tr>
		<td class="topbottombar" colspan="2" align="center" ><a href="admin.php"><?php print $pgv_lang["lang_back_admin"]; ?></a></td>
	</tr>
	<tr>
		<td colspan="3" class="topbottombar"><?php print $pgv_lang["admin_info"]; ?></td>
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
	foreach(get_all_users() as $user) {
		$totusers = $totusers + 1;
		if (((date("U") - get_user_setting($user,'reg_timestamp')) > 604800) && (get_user_setting($user,'verified')!="yes")) $warnusers++;
		else {
			if (get_user_setting($user,'comment_exp')) {
				if ((strtotime(get_user_setting($user,'comment_exp')) != "-1") && (strtotime(get_user_setting($user,'comment_exp')) < time("U"))) $warnusers++;
			}
		}
		if ((get_user_setting($user,'verified_by_admin') != "yes") && (get_user_setting($user,'verified') == "yes")) {
			$nverusers++;
		}
		if (get_user_setting($user,'verified') != "yes") {
			$applusers++;
		}
		if (get_user_setting($user,'canadmin')=='Y') {
			$adminusers++;
		}
		if (get_user_setting($user,'canedit')) {
			foreach(unserialize(get_user_setting($user,'canedit')) as $gedid=>$rights) {
				if ($rights == "admin") {
					if (isset($GEDCOMS[$gedid])) {
						if (isset($gedadmin[$GEDCOMS[$gedid]["title"]])) $gedadmin[$GEDCOMS[$gedid]["title"]]["number"]++;
						else {
							$gedadmin[$GEDCOMS[$gedid]["title"]]["name"] = $GEDCOMS[$gedid]["title"];
							$gedadmin[$GEDCOMS[$gedid]["title"]]["number"] = 1;
							$gedadmin[$GEDCOMS[$gedid]["title"]]["ged"] = $gedid;
						}
					}
				}
			}
		}
		if (isset($userlang[$pgv_lang["lang_name_".get_user_setting($user,'language')]]))
			$userlang[$pgv_lang["lang_name_".get_user_setting($user,'language')]]["number"]++;
		else {
			$userlang[$pgv_lang["lang_name_".get_user_setting($user,'language')]]["langname"] = get_user_setting($user,'language');
			$userlang[$pgv_lang["lang_name_".get_user_setting($user,'language')]]["number"] = 1;
		}
	}
	print "<table class=\"width100 $TEXT_DIRECTION\">";
	print "<tr><td class=\"font11\">".$pgv_lang["users_total"]."</td><td class=\"font11\">".$totusers."</td></tr>";

	print "<tr><td class=\"font11\">";
	if ($adminusers == 0) print $pgv_lang["users_admin"];
	else print "<a href=\"useradmin.php?action=listusers&amp;filter=adminusers\">".$pgv_lang["users_admin"]."</a></td>";
	print "<td class=\"font11\">".$adminusers."</td></tr>";

	print "<tr><td class=\"font11\">".$pgv_lang["users_gedadmin"]."</td></tr>";
	asort($gedadmin);
	$ind = 0;
	foreach ($gedadmin as $key=>$geds) {
		if ($ind !=0) print "<td class=\"font11\"></td>";
		$ind = 1;
		print "<td class=\"font11\">";
		if ($geds["number"] == 0) print $geds["name"];
		else print "<a href=\"useradmin.php?action=listusers&amp;filter=gedadmin&amp;ged=".$geds["ged"]."\">".$geds["name"]."</a>";
		print "</td><td class=\"font11\">".$geds["number"]."</td>";
	}
	print "<tr><td class=\"font11\"></td></tr><tr><td class=\"font11\">";
	if ($warnusers == 0) print $pgv_lang["warn_users"];
	else print "<a href=\"useradmin.php?action=listusers&amp;filter=warnings\">".$pgv_lang["warn_users"]."</a>";
	print "</td><td class=\"font11\">".$warnusers."</td></tr>";

	print "<tr><td class=\"font11\">";
	if ($applusers == 0) print $pgv_lang["users_unver"];
	else print "<a href=\"useradmin.php?action=listusers&amp;filter=usunver\">".$pgv_lang["users_unver"]."</a>";
	print "</td><td class=\"font11\">".$applusers."</td></tr>";

	print "<tr><td class=\"font11\">";
	if ($nverusers == 0) print $pgv_lang["users_unver_admin"];
	else print "<a href=\"useradmin.php?action=listusers&amp;filter=admunver\">".$pgv_lang["users_unver_admin"]."</a>";
	print "</td><td class=\"font11\">".$nverusers."</td></tr>";

	asort($userlang);
	print "<tr valign=\"middle\"><td class=\"font11\">".$pgv_lang["users_langs"]."</td>";
	foreach ($userlang as $key=>$ulang) {
		print "\t<td class=\"font11\"><img src=\"".$language_settings[$ulang["langname"]]["flagsfile"]."\" class=\"brightflag\" alt=\"".$key."\" title=\"".$key."\" /></td><td>&nbsp;<a href=\"useradmin.php?action=listusers&amp;filter=language&amp;usrlang=".$ulang["langname"]."\">".$key."</a></td><td>".$ulang["number"]."</td></tr><tr class=\"vmiddle\"><td></td>\n";
	}
	print "</tr></table>";
	print "</td></tr></table>";
	?>
<?php
print_footer();
?>
