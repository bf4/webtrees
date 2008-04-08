<?php
/**
 * Administrative User Interface.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * This Page Is Valid XHTML 1.0 Transitional! > 19 August 2005
 *
 * @package PhpGedView
 * @subpackage Admin
 * @version $Id$
 */

require "config.php";
require_once("includes/functions_print_lists.php");

loadLangFile("pgv_confighelp, pgv_admin, pgv_editor");	// Load language variables
	
if (!isset($action)) {
	$action="";
}

// cannot edit account using a cookie login - login with password first
if (!PGV_USER_ID || $_SESSION["cookie_login"]) {
	header("Location: login.php?url=edituser.php");
	exit;
}

// prevent users with editing account disabled from being able to edit their account
if (get_user_setting(PGV_USER_ID, 'editaccount')!='Y') {
	header("Location: index.php?ctype=user");
	exit;
}

// section to update a user
if ($action=="edituser2") {
	if ($username!=PGV_USER_NAME && get_user_id($username)) {
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
				// Change password
				if (!empty($pass1)) {
					AddToLog("User changed password");
					set_user_password(PGV_USER_ID, crypt($pass1));
				}
				// Change username
				if ($username!=PGV_USER_NAME) {
					AddToLog("User renamed to ->{$username}<-");
					rename_user(PGV_USER_NAME, $username);
					$_SESSION['pgv_user']=$username;
				}
				// Change other settings
				set_user_setting($username, 'firstname', stripslashes($firstname));
				set_user_setting($username, 'lastname', stripslashes($lastname));
				$sync_data_changed=($user_email!=get_user_setting($username, 'email'));
				set_user_setting($username, 'email', $user_email);
				set_user_setting($username, 'theme', $user_theme);
				set_user_setting($username, 'language', $user_language);
				set_user_setting($username, 'contactmethod', $new_contact_method);
				$new_visibleonline=isset($new_visibleonline) ? 'Y' : 'N';
				set_user_setting($username, 'visibleonline', $new_visibleonline);
				set_user_setting($username, 'defaulttab', $new_default_tab);
				set_user_gedcom_setting($username, $GEDCOM, 'rootid', $rootid);

				//-- update Gedcom record with new email address
				if ($sync_data_changed && get_user_setting($username, 'sync_gedcom')=='Y') {
					foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
						$myid=get_user_gedcom_setting($username, $ged_id, 'gedcomid');
						if ($myid) {
							include_once "includes/functions_edit.php";
							$indirec=find_updated_record($myid, $ged_name);
							if (!$indirec) {
								$indirec=find_person_record($myid, $ged_name);
							}
							if ($indirec) {
								$OLDGEDCOM=$GEDCOM;
								$GEDCOM=$ged_name;
								if (preg_match("/\d _?EMAIL/", $indirec)) {
									$indirec= preg_replace("/(\d _?EMAIL)[^\r\n]*/", "$1 ".$user_email, $indirec);
									replace_gedrec($myid, $indirec);
								} else {
									$indirec.="\r\n1 EMAIL ".$user_email;
									replace_gedrec($myid, $indirec);
								}
								$GEDCOM=$OLDGEDCOM;
							}
						}
					}
				}
				// Refresh the page rather than dropping through to the form, otherwise we
				// still may have old data in the user_settings cache.
				header("Location: edituser.php");
				exit;
			}
		}
	}
} else {
	print_header("PhpGedView ".$pgv_lang["user_admin"]);
}

print "<div class=\"center\">\n";
//-- print the form to edit a user
?>
<script language="JavaScript" type="text/javascript">
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
		if (frm.lastname.value=="") {
			alert("<?php print $pgv_lang["enter_fullname"]; ?>");
			frm.lastname.focus();
			return false;
		}
		if ((frm.user_email.value=="")||(frm.user_email.value.indexOf("@")==-1)) {
			alert("<?php print $pgv_lang["enter_email"]; ?>");
			frm.user_email.focus();
			return false;
		}
		if (frm.pass1.value !=frm.pass2.value) {
	      alert("<?php print $pgv_lang["password_mismatch"]; ?>");
	      frm.pass1.value="";
	      frm.pass2.value= "";
	      frm.pass1.focus();
	      return false;
	    }
	    if (frm.pass1.value.length > 0 && frm.pass1.value.length < 6) {
	      alert("<?php print $pgv_lang["passwordlength"]; ?>");
	      frm.pass1.value="";
	      frm.pass2.value="";
	      frm.pass1.focus();
	      return false;
	    }
		return true;
	}
	var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
</script>
<form name="editform" method="post" action="" onsubmit="return checkform(this);">
<input type="hidden" name="action" value="edituser2" />
<?php $tab=0; ?>
<table class="list_table <?php print $TEXT_DIRECTION; ?>">
	<tr><td class="topbottombar" colspan="2"><h2><?php print $pgv_lang["editowndata"];?></h2></td></tr>
	<tr><td class="topbottombar" colspan="2"><input type="submit" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["update_myaccount"]; ?>" /></td></tr>
	<tr><td class="descriptionbox width20 wrap"><?php print_help_link("edituser_username_help", "qm");print $pgv_lang["username"];?></td><td class="optionbox"><input type="text" name="username" tabindex="<?php print ++$tab; ?>" value="<?php print PGV_USER_NAME;?>" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_firstname_help", "qm");print $pgv_lang["firstname"];?></td><td class="optionbox"><input type="text" name="firstname" tabindex="<?php print ++$tab; ?>" value="<?php print get_user_setting(PGV_USER_ID, 'firstname');?>" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_lastname_help", "qm");print $pgv_lang["lastname"];?></td><td class="optionbox"><input type="text" name="lastname" tabindex="<?php print ++$tab; ?>" value="<?php print get_user_setting(PGV_USER_ID, 'lastname');?>" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_gedcomid_help", "qm");print $pgv_lang["gedcomid"];?></td><td class="optionbox">
		<?php
			if (PGV_USER_GEDCOM_ID) {
				print "<ul>";
				print_list_person(PGV_USER_GEDCOM_ID, array(get_person_name(PGV_USER_GEDCOM_ID), $GEDCOM));
				print "</ul>";
			} else {
				print "&nbsp;";
			}
		?>
	</td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_rootid_help", "qm"); print $pgv_lang["rootid"];?></td><td class="optionbox"><input type="text" name="rootid" id="rootid" tabindex="<?php print ++$tab; ?>" value="<?php print PGV_USER_ROOT_ID; ?>" />
	<?php print_findindi_link("rootid",""); ?>
	</td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_password_help", "qm"); print $pgv_lang["password"];?></td><td class="optionbox"><input type="password" name="pass1" tabindex="<?php print ++$tab; ?>" /><br /><?php print $pgv_lang["leave_blank"];?></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_conf_password_help", "qm"); print $pgv_lang["confirm"];?></td><td class="optionbox"><input type="password" name="pass2" tabindex="<?php print ++$tab; ?>" /></td></tr>
	<tr><td class="descriptionbox wrap"><?php print_help_link("edituser_change_lang_help", "qm"); print $pgv_lang["change_lang"];?></td><td class="optionbox" valign="top"><?php
	if ($ENABLE_MULTI_LANGUAGE) {
		$tab++;
		print "<select name=\"user_language\" tabindex=\"".$tab."\" style=\"{ font-size: 9pt; }\">";
		$my_lang=get_user_setting(PGV_USER_ID, 'language');
		foreach ($pgv_language as $key=> $value) {
			if ($language_settings[$key]["pgv_lang_use"]) {
				print "<option value=\"$key\"";
				if ($key==$my_lang) {
					print " selected=\"selected\"";
				}
				print ">" . $pgv_lang[$key] . "</option>";
			}
		}
		print "</select>";
	}
	else print "&nbsp;";
    ?></td></tr>
    <tr><td class="descriptionbox wrap"><?php print_help_link("edituser_email_help", "qm"); print $pgv_lang["emailadress"];?></td><td class="optionbox" valign="top"><input type="text" name="user_email" tabindex="<?php print ++$tab; ?>" value="<?php print get_user_setting(PGV_USER_ID, 'email'); ?>" size="50" /></td></tr>
    <?php if ($ALLOW_USER_THEMES) { ?>
    <tr><td class="descriptionbox wrap"><?php print_help_link("edituser_user_theme_help", "qm"); print $pgv_lang["user_theme"];?></td><td class="optionbox" valign="top">
    	<select name="user_theme" tabindex="<?php print ++$tab; ?>">
    	<option value=""><?php print $pgv_lang["site_default"]; ?></option>
				<?php
					$themes=get_theme_names();
					$my_theme=get_user_setting(PGV_USER_ID, 'theme');
					foreach($themes as $indexval=> $themedir) {
						print "<option value=\"".$themedir["dir"]."\"";
						if ($themedir["dir"]==$my_theme) {
							print " selected=\"selected\"";
						}
						print ">".$themedir["name"]."</option>";
					}
				?>
			</select>
	</td></tr>
	<?php } ?>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("edituser_user_contact_help", "qm"); print $pgv_lang["user_contact_method"];?></td>
		<td class="optionbox"><select name="new_contact_method" tabindex="<?php print ++$tab; ?>">
		<?php if ($PGV_STORE_MESSAGES) { ?>
				<option value="messaging" <?php if (get_user_setting(PGV_USER_ID, 'contactmethod')=='messaging') print "selected=\"selected\""; ?>><?php print $pgv_lang["messaging"];?></option>
				<option value="messaging2" <?php if (get_user_setting(PGV_USER_ID, 'contactmethod')=='messaging2') print "selected=\"selected\""; ?>><?php print $pgv_lang["messaging2"];?></option>
		<?php } else { ?>
				<option value="messaging3" <?php if (get_user_setting(PGV_USER_ID, 'contactmethod')=='messaging3') print "selected=\"selected\""; ?>><?php print $pgv_lang["messaging3"];?></option>
		<?php } ?>
				<option value="mailto" <?php if (get_user_setting(PGV_USER_ID, 'contactmethod')=='mailto') print "selected=\"selected\""; ?>><?php print $pgv_lang["mailto"];?></option>
				<option value="none" <?php if (get_user_setting(PGV_USER_ID, 'contactmethod')=='none') print "selected=\"selected\""; ?>><?php print $pgv_lang["no_messaging"];?></option>
			</select>
		</td>
	</tr>
	<tr>
      <td class="descriptionbox wrap"><?php print_help_link("useradmin_visibleonline_help", "qm"); print $pgv_lang["visibleonline"];?></td>
      <td class="optionbox"><input type="checkbox" name="new_visibleonline" tabindex="<?php print ++$tab; ?>" value="yes" <?php if (get_user_setting(PGV_USER_ID, 'visibleonline')=='Y') print "checked=\"checked\""; ?> /></td>
    </tr>
    <tr>
		<td class="descriptionbox wrap"><?php print_help_link("edituser_user_default_tab_help", "qm"); print $pgv_lang["user_default_tab"];?></td>
		<td class="optionbox"><select name="new_default_tab" tabindex="<?php print ++$tab; ?>">
				<option value="0" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==0) print "selected=\"selected\""; ?>><?php print $pgv_lang["personal_facts"];?></option>
				<option value="1" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==1) print "selected=\"selected\""; ?>><?php print $pgv_lang["notes"];?></option>
				<option value="2" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==2) print "selected=\"selected\""; ?>><?php print $pgv_lang["ssourcess"];?></option>
				<option value="3" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==3) print "selected=\"selected\""; ?>><?php print $pgv_lang["media"];?></option>
				<option value="4" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==4) print "selected=\"selected\""; ?>><?php print $pgv_lang["relatives"];?></option>
				<option value="-1" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==-1) print "selected=\"selected\""; ?>><?php print $pgv_lang["all"];?></option>
				<option value="-2" <?php if (get_user_setting(PGV_USER_ID, 'defaulttab')==-2) print "selected=\"selected\""; ?>><?php print $pgv_lang["lasttab"];?></option>
			</select>
		</td>
	</tr>
	<tr><td class="topbottombar" colspan="2"><input type="submit" tabindex="<?php print ++$tab; ?>" value="<?php print $pgv_lang["update_myaccount"]; ?>" /></td></tr>
</table>
</form><br />
</div>
<?php
print_footer();
?>
