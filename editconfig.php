<?php
/**
 * Online UI for editing config.php site configuration variables
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * This Page Is Valid XHTML 1.0 Transitional! > 17 September 2005
 *
 * @package PhpGedView
 * @subpackage Admin
 * @see config.php
 * @version $Id$
 */

require_once "config.php";
loadLangFile("pgv_confighelp, pgv_help");

require_once "sanity_check.php";

if (!defined("DB_ERROR")) require_once('DB.php');

$action=safe_POST('action', PGV_REGEX_ALPHA);

// Lists to validate input and generate forms
$ALL_DBTYPE=array('mssql', 'mysql', 'mysqli', 'pgsql', 'sqlite');
$ALL_COMMIT_COMMAND=array(''=>$pgv_lang['none'], 'cvs'=>'CVS', 'svn'=>'SVN');
$ALL_LOGFILE_CREATE=array('none'=>$pgv_lang['no_logs'], 'daily'=>$pgv_lang['daily'], 'weekly'=>$pgv_lang['weekly'], 'monthly'=>$pgv_lang['monthly'], 'yearly'=>$pgv_lang['yearly']);

if ($CONFIGURED) {
	if (check_db(true)) {
		//-- check if no users have been defined and create the main admin user
		if (!adminUserExists()) {
			print_header($pgv_lang["configure_head"]);
			print "<span class=\"subheaders\">".$pgv_lang["configure"]."</span><br />";
			print $pgv_lang["welcome_new"]."<br />";
			if ($action=="createadminuser") {
				$username =safe_POST('username');
				$firstname=safe_POST('firstname');
				$lastname =safe_POST('lastname');
				$pass1    =safe_POST('pass1', PGV_REGEX_PASSWORD);
				$pass2    =safe_POST('pass2', PGV_REGEX_PASSWORD);
				$email    =safe_POST('email', PGV_REGEX_EMAIL);
				if ($pass1 && $pass1==$pass2) {
					if ($user_id=create_user($username, crypt($pass1))) {
						set_user_setting($user_id, 'firstname',            $firstname);
						set_user_setting($user_id, 'lastname',             $lastname);
						set_user_setting($user_id, 'canadmin',             'Y');
						set_user_setting($user_id, 'email',                $email);
						set_user_setting($user_id, 'verified',             'yes');
						set_user_setting($user_id, 'verified_by_admin',    'yes');
						set_user_setting($user_id, 'language',             $LANGUAGE);
						set_user_setting($user_id, 'reg_timestamp',        date('U'));
						set_user_setting($user_id, 'loggedin',             'Y');
						set_user_setting($user_id, 'sessiontime',          time());
						set_user_setting($user_id, 'contactmethod',        'messaging2');
						set_user_setting($user_id, 'visibleonline',        'Y');
						set_user_setting($user_id, 'editaccount',          'Y');
						set_user_setting($user_id, 'defaulttab',           '0');
						set_user_setting($user_id, 'sync_gedcom',          'N');
						set_user_setting($user_id, 'relationship_privacy', 'N');
						set_user_setting($user_id, 'max_relation_path',    '2');
						set_user_setting($user_id, 'auto_accept',          'N');
						AddToLog("added user ->{$username}<-");
						print $pgv_lang["user_created"];
						print "<br />";
						print "<a href=\"editgedcoms.php\">";
						print $pgv_lang["click_here_to_continue"];
						print "</a><br />";
						$_SESSION["pgv_user"]=$user_id;
						print_footer();
						exit;
					} else {
						print "<span class=\"error\">";
						print $pgv_lang["user_create_error"];
						print "<br /></span>";
						print_footer();
						exit;
					}
				} else {
					print "<span class=\"error\">";
					print $pgv_lang["password_mismatch"];
					print "<br /></span>";
					print_footer();
					exit;
				}
			} else {
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
						return true;
					}
				</script>
				<form method="post" onsubmit="return checkform(this);">
				<input type="hidden" name="action" value="createadminuser" />
				<b><?php print $pgv_lang["default_user"]; ?></b><br />
				<?php print $pgv_lang["about_user"]; ?><br /><br />
				<table>
					<tr><td align="right"><?php print $pgv_lang["username"]; ?></td><td><input type="text" name="username" /></td></tr>
					<tr><td align="right"><?php print $pgv_lang["firstname"]; ?></td><td><input type="text" name="firstname" /></td></tr>
					<tr><td align="right"><?php print $pgv_lang["lastname"]; ?></td><td><input type="text" name="lastname" /></td></tr>
					<tr><td align="right"><?php print $pgv_lang["password"]; ?></td><td><input type="password" name="pass1" /></td></tr>
					<tr><td align="right"><?php print $pgv_lang["confirm"]; ?></td><td><input type="password" name="pass2" /></td></tr>
					<tr><td align="right"><?php print $pgv_lang["emailadress"]; ?></td><td><input type="text" name="email" size="45" /></td></tr>
				</table>
				<input type="submit" value="<?php print $pgv_lang["create_user"]; ?>" />
				</form>
				<?php
				print_footer();
				exit;
			}
		}
		if (!PGV_USER_IS_ADMIN) {
			header("Location: login.php?url=editconfig.php");
			exit;
		}
	}
}
else {
	//-- set the default to sqlite for php 5+
	if (empty($action) && !function_exists('mysql_connect')) {
		if (PHP_VERSION>='5') {
			$DBTYPE="sqlite";
			$DBNAME="index/phpgedview.db";
		}
	}
}


print_header($pgv_lang["configure_head"]);
//Prints warnings
if (count($warnings)>0 || count($errors)>0) {
	print_sanity_errors();
	if (count($errors)>0) exit;
}
if ($action=="update" && (!isset($security_user)||$security_user!=$_POST['NEW_DBUSER'])) {
	print $pgv_lang["performing_update"];
	print "<br />";
	$configtext = implode('', file("config.php"));
	print $pgv_lang["config_file_read"];
	print "<br />";

	$NEW_SERVER_URL=trim(safe_POST('NEW_SERVER_URL'));
	if (!empty($NEW_SERVER_URL)) {
		if (!isFileExternal($NEW_SERVER_URL)) $NEW_SERVER_URL = "http://".$NEW_SERVER_URL;
		$NEW_SERVER_URL = rtrim($NEW_SERVER_URL, '/').'/';		// Make sure that trailing "/" is present
	}
	update_config($configtext, 'SERVER_URL', $NEW_SERVER_URL);

	$NEW_INDEX_DIRECTORY=preg_replace('/\\\/','/', trim($_POST["NEW_INDEX_DIRECTORY"]));
	if (empty($NEW_INDEX_DIRECTORY)) $NEW_INDEX_DIRECTORY = "./index/";
	$NEW_INDEX_DIRECTORY = rtrim($NEW_INDEX_DIRECTORY, '/').'/';	// Make sure that trailing "/" is present
	update_config($configtext, 'INDEX_DIRECTORY', $NEW_INDEX_DIRECTORY);

	update_config($configtext, 'CONFIG_VERSION', $CONFIG_VERSION); // No longer used?
	update_config($configtext, 'TBLPREFIX',             safe_POST('NEW_TBLPREFIX'));
	update_config($configtext, 'LOGFILE_CREATE',        safe_POST('NEW_LOGFILE_CREATE'), array_keys($ALL_LOGFILE_CREATE), 'none');
	update_config($configtext, 'PGV_SESSION_SAVE_PATH', safe_POST('NEW_PGV_SESSION_SAVE_PATH'));
	update_config($configtext, 'PGV_SESSION_TIME',      safe_POST('NEW_PGV_SESSION_TIME'));
	update_config($configtext, 'MAX_VIEWS',             safe_POST('NEW_MAX_VIEWS'));
	update_config($configtext, 'MAX_VIEW_TIME',         safe_POST('NEW_MAX_VIEW_TIME'));
	update_config($configtext, 'COMMIT_COMMAND',        safe_POST('NEW_COMMIT_COMMAND', array_keys($ALL_COMMIT_COMMAND)));
	update_config($configtext, 'LOGIN_URL',             safe_POST('NEW_LOGIN_URL'));
	update_config($configtext, 'PGV_MEMORY_LIMIT',      safe_POST('NEW_PGV_MEMORY_LIMIT'));
	update_config($configtext, 'DBPERSIST',                       safe_POST_bool('NEW_DBPERSIST'));
	update_config($configtext, 'ALLOW_CHANGE_GEDCOM',             safe_POST_bool('NEW_ALLOW_CHANGE_GEDCOM'));
	update_config($configtext, 'USE_REGISTRATION_MODULE',         safe_POST_bool('NEW_USE_REGISTRATION_MODULE'));
	update_config($configtext, 'REQUIRE_ADMIN_AUTH_REGISTRATION', safe_POST_bool('NEW_REQUIRE_ADMIN_AUTH_REGISTRATION'));
	update_config($configtext, 'PGV_SIMPLE_MAIL',                 safe_POST_bool('NEW_PGV_SIMPLE_MAIL'));
	update_config($configtext, 'PGV_STORE_MESSAGES',              safe_POST_bool('NEW_PGV_STORE_MESSAGES'));
	update_config($configtext, 'ALLOW_USER_THEMES',               safe_POST_bool('NEW_ALLOW_USER_THEMES'));
	update_config($configtext, 'ALLOW_REMEMBER_ME',               safe_POST_bool('NEW_ALLOW_REMEMBER_ME'));

	$DBTYPE = safe_POST('NEW_DBTYPE', $ALL_DBTYPE, $DBTYPE);
	$DBHOST = safe_POST('NEW_DBHOST');
	$DBNAME = safe_POST('NEW_DBNAME');
	$DBUSER = safe_POST('NEW_DBUSER');
	// Passwords can contain otherwise dangerous characters.
	// Therefore we must never echo it back to the screen.
	$DBPASS = safe_POST('NEW_DBPASS', '.*', $DBPASS);

	//-- make sure the database configuration is set properly
	if (check_db(true)) {
		$CONFIGURED = true;
		update_config($configtext, 'CONFIGURED', $CONFIGURED);
		update_config($configtext, 'DBTYPE', $DBTYPE);
		update_config($configtext, 'DBHOST', $DBHOST);
		update_config($configtext, 'DBNAME', $DBNAME);
		update_config($configtext, 'DBUSER', $DBUSER);
		update_config($configtext, 'DBPASS', $DBPASS);
	}

	// Save the languages the user has chosen to have active on the website
	$Filename = $INDEX_DIRECTORY . "lang_settings.php";
	if (!file_exists($Filename)) copy("includes/lang_settings_std.php", $Filename);

	if (isset($NEW_LANGS)) {
		// Set the chosen languages to active
		foreach ($NEW_LANGS as $key => $name) {
			$pgv_lang_use[$name] = true;
		}
	
		// Set the other languages to non-active
		foreach ($pgv_lang_use as $name => $value) {
			if (!isset($NEW_LANGS[$name])) $pgv_lang_use[$name] = false;
		}
		$error = "";
		if ($file_array = file($Filename)) {
			@copy($Filename, $Filename . ".old");
			if ($fp = @fopen($Filename, "w")) {
				for ($x = 0; $x < count($file_array); $x++) {
					fwrite($fp, $file_array[$x]);
					$dDummy00 = trim($file_array[$x]);
					if ($dDummy00 == "//-- NEVER manually delete or edit this entry and every line below this entry! --START--//") break;
				}
				fwrite($fp, "\r\n");
				fwrite($fp, "// Array definition of language_settings\r\n");
				fwrite($fp, "\$language_settings = array();\r\n");
				foreach ($language_settings as $key => $value) {
					fwrite($fp, "\r\n");
					fwrite($fp, "//-- settings for " . $languages[$key] . "\r\n");
					fwrite($fp, "\$lang = array();\r\n");
					fwrite($fp, "\$lang[\"pgv_langname\"]    = \"" . $languages[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"pgv_lang_use\"]    = ");
					if ($pgv_lang_use[$key]) fwrite($fp, "true"); else fwrite($fp, "false");
					fwrite($fp, ";\r\n");
					fwrite($fp, "\$lang[\"pgv_lang\"]    = \"" . $pgv_lang[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"lang_short_cut\"]    = \"" . $lang_short_cut[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"langcode\"]    = \"" . $lang_langcode[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"pgv_language\"]    = \"" . $pgv_language[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"confighelpfile\"]    = \"" . $confighelpfile[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"helptextfile\"]    = \"" . $helptextfile[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"flagsfile\"]    = \"" . $flagsfile[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"factsfile\"]    = \"" . $factsfile[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"DATE_FORMAT\"]    = \"" . $DATE_FORMAT_array[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"TIME_FORMAT\"]    = \"" . $TIME_FORMAT_array[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"WEEK_START\"]    = \"" . $WEEK_START_array[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"TEXT_DIRECTION\"]    = \"" . $TEXT_DIRECTION_array[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"NAME_REVERSE\"]    = ");
					if ($NAME_REVERSE_array[$key]) fwrite($fp, "true"); else fwrite($fp, "false");
					fwrite($fp, ";\r\n");
					fwrite($fp, "\$lang[\"ALPHABET_upper\"]    = \"" . $ALPHABET_upper[$key] . "\";\r\n");
					fwrite($fp, "\$lang[\"ALPHABET_lower\"]    = \"" . $ALPHABET_lower[$key] . "\";\r\n");
					fwrite($fp, "\$language_settings[\"" . $languages[$key] . "\"]  = \$lang;\r\n");
				}
				$end_found = false;
				for ($x = 0; $x < count($file_array); $x++) {
					$dDummy00 = trim($file_array[$x]);
					if ($dDummy00 == "//-- NEVER manually delete or edit this entry and every line above this entry! --END--//"){fwrite($fp, "\r\n"); $end_found = true;}
					if ($end_found) fwrite($fp, $file_array[$x]);
				}
				fclose($fp);
				$logline = AddToLog("Language settings file, lang_settings.php, updated");
				check_in($logline, $Filename, $INDEX_DIRECTORY);	
			}
			else $error = "lang_config_write_error";
		}
		else $error = "lang_set_file_read_error";
	}

	if (!empty($error)) {
		print "<span class=\"error\">" . $pgv_lang[$error] . "</span><br /><br />";
	}

	if (!isset($download)) {
		$res = @eval($configtext);
		if ($res===false) {
			$fp = fopen("config.php", "wb");
			if (!$fp) {
				print "<span class=\"error\">";
				print $pgv_lang["pgv_config_write_error"];
				print "<br /></span>\n";
			} else {
				fwrite($fp, $configtext);
				fclose($fp);
				$logline = AddToLog("config.php updated");
				check_in($logline, "config.php", "");	
				if ($CONFIGURED) {
					print "<script language=\"JavaScript\" type=\"text/javascript\">\nwindow.location = 'editconfig.php';\n</script>\n";
				}
			}
		} else {
			print "<span class=\"error\">There was an error in the generated config.php.</span>".htmlentities($configtext,ENT_COMPAT,'UTF-8');
		}
	} else {
		$_SESSION["config.php"]=$configtext;
		print "<br /><br /><a href=\"config_download.php?file=config.php\">";
		print $pgv_lang["download_here"];
		print "</a><br /><br />\n";
	}
}

?>
<script language="JavaScript" type="text/javascript">
<!--
	var helpWin;
	function helpPopup(which) {
		if ((!helpWin)||(helpWin.closed)) helpWin = window.open('editconfig_help.php?help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
		else helpWin.location = 'editconfig_help.php?help='+which;
		return false;
	}
	function getHelp(which) {
		if ((helpWin)&&(!helpWin.closed)) helpWin.location='editconfig_help.php?help='+which;
	}
	function closeHelp() {
		if (helpWin) helpWin.close();
	}
	function changeDBtype(dbselect) {
		if (dbselect.options[dbselect.selectedIndex].value=='sqlite') {
			document.configform.NEW_DBNAME.value='./index/phpgedview.db';
		}
		else {
			document.configform.NEW_DBNAME.value='phpgedview';
		}
	}
	//-->
</script>
<form method="post" name="configform" action="editconfig.php">
<input type="hidden" name="action" value="update" />
<?php if (isset($_POST['security_check'])) { ?><input type="hidden" name="security_check" value="<?php print $_POST['security_check']; ?>" /><?php } ?>
<?php if (isset($_POST['security_user'])) { ?><input type="hidden" name="security_user" value="<?php print $_POST['security_user']; ?>" /><?php } ?>
<?php
	
	print "<table class=\"facts_table\">";
	print "<tr><td class=\"topbottombar\" colspan=\"2\">";
	print "<span class=\"subheaders\">";
	print $pgv_lang["configure"];
	print "</span><br /><br />";
	print "<div class=\"ltr\">".$pgv_lang["welcome"];
	print "<br />";
	print $pgv_lang["review_readme"];
	print_text("return_editconfig");
	if ($CONFIGURED) {
		print "<a href=\"editgedcoms.php\"><b>";
		print $pgv_lang["admin_gedcoms"];
		print "</b></a><br /><br />\n";
	}
	$tab = 0;
	print "</div></td></tr></table>";
?>
	<table class="facts_table">
	<tr>
		<td class="topbottombar" colspan="2"><input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php print $pgv_lang["save_config"]; ?>" onclick="closeHelp();" />
		&nbsp;&nbsp;
		<input type="reset" tabindex="<?php echo ++$tab; ?>" value="<?php print $pgv_lang["reset"]; ?>" />
		</td>
	</tr>
	<tr>
		<td class="descriptionbox width20 wrap"><?php print_help_link("DBTYPE_help", "qm", "DBTYPE"); print $pgv_lang["DBTYPE"]; ?></td>
		<td class="optionbox"><select name="NEW_DBTYPE" dir="ltr" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('DBTYPE_help');" onchange="changeDBtype(this);">
		<?php
		foreach ($ALL_DBTYPE as $type) {
			echo '<option value="', $type, '"';
			if ($type==$DBTYPE) {
				echo ' selected="selected"';
			}
			echo '>', $pgv_lang[$type], '</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("DBHOST_help", "qm", "DBHOST"); print $pgv_lang["DBHOST"]; ?></td>
		<td class="optionbox"><input type="text" dir="ltr" name="NEW_DBHOST" value="<?php print $DBHOST; ?>" size="40" tabindex="<?php echo ++$tab;; ?>" onfocus="getHelp('DBHOST_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("DBUSER_help", "qm", "DBUSER"); print $pgv_lang["DBUSER"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_DBUSER" value="<?php print $DBUSER; ?>" size="40" tabindex="<?php echo ++$tab;; ?>" onfocus="getHelp('DBUSER_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("DBPASS_help", "qm", "DBPASS"); print $pgv_lang["DBPASS"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_DBPASS" value="" tabindex="<?php echo ++$tab;; ?>" onfocus="getHelp('DBPASS_help');" /><!-- <br /><span style="color: red;"><?php print_text("enter_db_pass"); ?></span> --></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("DBNAME_help", "qm", "DBNAME"); print $pgv_lang["DBNAME"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_DBNAME" value="<?php print $DBNAME; ?>" size="40" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('DBNAME_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox width20 wrap"><?php print_help_link("DBPERSIST_help", "qm", "DBPERSIST"); print $pgv_lang["DBPERSIST"]; ?></td>
		<td class="optionbox"><select name="NEW_DBPERSIST" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('DBPERSIST_help');">
				<option value="yes" <?php if ($DBPERSIST) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$DBPERSIST) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("TBLPREFIX_help", "qm", "TBLPREFIX"); print $pgv_lang["TBLPREFIX"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_TBLPREFIX" value="<?php print $TBLPREFIX; ?>" size="40" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('TBLPREFIX_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox width20 wrap"><?php print_help_link("ALLOW_CHANGE_GEDCOM_help", "qm", "ALLOW_CHANGE_GEDCOM"); print $pgv_lang["ALLOW_CHANGE_GEDCOM"]; ?></td>
		<td class="optionbox"><select name="NEW_ALLOW_CHANGE_GEDCOM" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('ALLOW_CHANGE_GEDCOM_help');">
				<option value="yes" <?php if ($ALLOW_CHANGE_GEDCOM) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$ALLOW_CHANGE_GEDCOM) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("INDEX_DIRECTORY_help", "qm", "INDEX_DIRECTORY"); print $pgv_lang["INDEX_DIRECTORY"]; ?></td>
		<td class="optionbox"><input type="text" size="50" name="NEW_INDEX_DIRECTORY" value="<?php print $INDEX_DIRECTORY; ?>" dir="ltr" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('INDEX_DIRECTORY_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("PGV_STORE_MESSAGES_help", "qm", "PGV_STORE_MESSAGES"); print $pgv_lang["PGV_STORE_MESSAGES"]; ?></td>
		<td class="optionbox"><select name="NEW_PGV_STORE_MESSAGES" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('PGV_STORE_MESSAGES_help');">
				<option value="yes" <?php if ($PGV_STORE_MESSAGES) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$PGV_STORE_MESSAGES) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("USE_REGISTRATION_MODULE_help", "qm", "USE_REGISTRATION_MODULE"); print $pgv_lang["USE_REGISTRATION_MODULE"]; ?></td>
		<td class="optionbox"><select name="NEW_USE_REGISTRATION_MODULE" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('USE_REGISTRATION_MODULE_help');">
				<option value="yes" <?php if ($USE_REGISTRATION_MODULE) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$USE_REGISTRATION_MODULE) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("REQUIRE_ADMIN_AUTH_REGISTRATION_help", "qm", "REQUIRE_ADMIN_AUTH_REGISTRATION"); print $pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"]; ?></td>
		<td class="optionbox"><select name="NEW_REQUIRE_ADMIN_AUTH_REGISTRATION" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('REQUIRE_ADMIN_AUTH_REGISTRATION_help');">
				<option value="yes" <?php if ($REQUIRE_ADMIN_AUTH_REGISTRATION) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$REQUIRE_ADMIN_AUTH_REGISTRATION) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("PGV_SIMPLE_MAIL_help", "qm", "PGV_SIMPLE_MAIL"); print $pgv_lang["PGV_SIMPLE_MAIL"]; ?></td>
		<td class="optionbox"><select name="NEW_PGV_SIMPLE_MAIL" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('PGV_SIMPLE_MAIL_help');">
				<option value="yes" <?php if ($PGV_SIMPLE_MAIL) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$PGV_SIMPLE_MAIL) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("ALLOW_USER_THEMES_help", "qm", "ALLOW_USER_THEMES"); print $pgv_lang["ALLOW_USER_THEMES"]; ?></td>
		<td class="optionbox"><select name="NEW_ALLOW_USER_THEMES" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('ALLOW_USER_THEMES_help');">
				<option value="yes" <?php if ($ALLOW_USER_THEMES) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$ALLOW_USER_THEMES) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("ALLOW_REMEMBER_ME_help", "qm", "ALLOW_REMEMBER_ME"); print $pgv_lang["ALLOW_REMEMBER_ME"]; ?></td>
		<td class="optionbox"><select name="NEW_ALLOW_REMEMBER_ME" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('ALLOW_REMEMBER_ME_help');">
				<option value="yes" <?php if ($ALLOW_REMEMBER_ME) print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
				<option value="no" <?php if (!$ALLOW_REMEMBER_ME) print "selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("LANG_SELECTION_help", "qm", "LANG_SELECTION"); print $pgv_lang["LANG_SELECTION"]; ?></td>
		<td class="optionbox">
			<table class="facts_table">
			<?php
			// Build a sorted list of language names in the currently active language
			foreach ($pgv_language as $key => $value){
				$d_LangName = "lang_name_".$key;
				$SortedLangs[$key] = $pgv_lang[$d_LangName];
			}
			asort($SortedLangs);

			// Build sorted list of languages, using numeric index
			// If necessary, insert one blank filler at the end of the 2nd column
			// Always insert a blank filler at the end of the 3rd column
			$lines = ceil(count($pgv_language) / 3);
			$BlankHere = 0;
			if (($lines * 3) != count($pgv_language)) {
				$BlankHere = $lines + $lines;
			}
			$i = 1;
			$LangsList = array();
			foreach ($SortedLangs as $key => $value) {
				$LangsList[$i] = $SortedLangs[$key];
				$i++;
				if ($i == $BlankHere) {
					$LangsList[$i] = "";
					$i++;
				}
			}
			$LangsList[$i] = "";

			// Print the languages in three columns
			$curline = 1;
			$SortedLangs = array_flip($SortedLangs);

			while ($curline <= $lines) {
				// Start each table row
				print "<tr>";
				$curcol = 0;
				// Print each column
				while ($curcol < 3) {
					$j = $curline + $lines * $curcol;
					$LocalName = $LangsList[$j];
					if ($LocalName != "") {
						$LangName = $SortedLangs[$LocalName];
						print "<td class=\"optionbox\"><input type=\"checkbox\" name=\"NEW_LANGS[".$LangName."]\" value=\"".$LangName."\"";
						if ($pgv_lang_use[$LangName] == true) {
							print " checked=\"checked\"";
						}
						print "/></td>";
						print "<td class=\"descriptionbox width30\">".$LocalName."</td>\n";
					} else {
						print "<td class=\"optionbox\">&nbsp;</td>";
						print "<td class=\"descriptionbox width30\">&nbsp;</td>\n";
					}
					$curcol++;
				}
				// Finish the table row
				print "</tr>";
				$curline++;
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("LOGFILE_CREATE_help", "qm", "LOGFILE_CREATE"); print $pgv_lang["LOGFILE_CREATE"]; ?></td>
		<td class="optionbox"><select name="NEW_LOGFILE_CREATE" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('LOGFILE_CREATE_help');">
		<?php
		foreach ($ALL_LOGFILE_CREATE as $key=>$value) {
			echo '<option value="', $key, '"';
			if ($key==$LOGFILE_CREATE) {
				echo ' selected="selected"';
			}
			echo '>', $value, '</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("SERVER_URL_help", "qm", "SERVER_URL"); print $pgv_lang["SERVER_URL"]; ?></td>
		<td class="optionbox wrap"><input type="text" name="NEW_SERVER_URL" value="<?php print $SERVER_URL; ?>" dir="ltr" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('SERVER_URL_help');" size="100" />
		<br /><?php
			$GUESS_URL = stripslashes("http://".$_SERVER["SERVER_NAME"].dirname($SCRIPT_NAME)."/");
			print_text("server_url_note");
			?>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("LOGIN_URL_help", "qm", "LOGIN_URL"); print $pgv_lang["LOGIN_URL"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_LOGIN_URL" value="<?php print $LOGIN_URL; ?>" dir="ltr" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('LOGIN_URL_help');" size="100" />
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("PGV_SESSION_SAVE_PATH_help", "qm", "PGV_SESSION_SAVE_PATH"); print $pgv_lang["PGV_SESSION_SAVE_PATH"]; ?></td>
		<td class="optionbox"><input type="text" dir="ltr" size="50" name="NEW_PGV_SESSION_SAVE_PATH" value="<?php print $PGV_SESSION_SAVE_PATH; ?>" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('PGV_SESSION_SAVE_PATH_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("PGV_SESSION_TIME_help", "qm", "PGV_SESSION_TIME"); print $pgv_lang["PGV_SESSION_TIME"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_PGV_SESSION_TIME" value="<?php print $PGV_SESSION_TIME; ?>" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('PGV_SESSION_TIME_help');" /></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("MAX_VIEW_RATE_help", "qm", "MAX_VIEW_RATE"); print $pgv_lang["MAX_VIEW_RATE"]; ?></td>
		<td class="optionbox">
			<input type="text" name="NEW_MAX_VIEWS" value="<?php print $MAX_VIEWS; ?>" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('MAX_VIEW_RATE_help');" />
			<?php
				if ($TEXT_DIRECTION == "ltr") print $pgv_lang["page_views"];
				else print $pgv_lang["seconds"];
			?>
			<input type="text" name="NEW_MAX_VIEW_TIME" value="<?php print $MAX_VIEW_TIME; ?>" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('MAX_VIEW_RATE_help');" />
			<?php
				if ($TEXT_DIRECTION == "ltr") print $pgv_lang["seconds"];
				else print $pgv_lang["page_views"];
			?>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("COMMIT_COMMAND_help", "qm", "COMMIT_COMMAND"); print $pgv_lang['COMMIT_COMMAND']; ?></td>
		<td class="optionbox"><select name="NEW_COMMIT_COMMAND" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('COMMIT_COMMAND_help');">
		<?php
		foreach ($ALL_COMMIT_COMMAND as $key=>$value) {
			echo '<option value="', $key, '"';
			if ($key==$COMMIT_COMMAND) {
				echo ' selected="selected"';
			}
			echo '>', $value, '</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap"><?php print_help_link("PGV_MEMORY_LIMIT_help", "qm", "PGV_MEMORY_LIMIT"); print $pgv_lang["PGV_MEMORY_LIMIT"]; ?></td>
		<td class="optionbox"><input type="text" name="NEW_PGV_MEMORY_LIMIT" value="<?php print $PGV_MEMORY_LIMIT; ?>" tabindex="<?php echo ++$tab; ?>" onfocus="getHelp('PGV_MEMORY_LIMIT_help');" /></td>
	</tr>
	<tr>
		<td class="topbottombar" colspan="2"><input type="submit" tabindex="<?php echo ++$tab; ?>" value="<?php print $pgv_lang["save_config"]; ?>" onclick="closeHelp();" />
		&nbsp;&nbsp;
		<input type="reset" tabindex="<?php echo ++$tab; ?>" value="<?php print $pgv_lang["reset"]; ?>" />
		</td>
	</tr>
</table>
</form>
<?php if (!$CONFIGURED) { ?>
<script language="JavaScript" type="text/javascript">
	helpPopup('welcome_new_help');
</script>
<?php
}
?>
<script language="JavaScript" type="text/javascript">
	document.configform.NEW_DBHOST.focus();
</script>
<?php
print_footer();

?>
