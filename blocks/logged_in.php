<?php
/**
 * Logged In Users Block
 *
 * This block will print a list of the users who are currently logged in
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["print_logged_in_users"]["name"]		= $pgv_lang["logged_in_users_block"];
$PGV_BLOCKS["print_logged_in_users"]["descr"]		= "logged_in_users_descr";
$PGV_BLOCKS["print_logged_in_users"]["canconfig"]	= false;
$PGV_BLOCKS["print_logged_in_users"]["config"]		= array("cache"=>0);

/**
 * logged in users
 *
 * prints a list of other users who are logged in
 */
/**
 * logged in users
 *
 * prints a list of other users who are logged in
 */
function print_logged_in_users($block = true, $config = "", $side, $index) {
	global $pgv_lang, $PGV_SESSION_TIME, $TEXT_DIRECTION;

	$block = true; // Always restrict this block's height

	$cusername = getUserName();

	// Log out inactive users
	foreach (get_idle_users(time()-$PGV_SESSION_TIME) as $user) {
		if ($user!=$cusername) {
			userLogout($user);
		}
	}

	// List active users
	$NumAnonymous = 0;
	$loggedusers = array ();
	foreach (get_logged_in_users() as $user) {
		if (userIsAdmin() || get_user_setting($user, 'visibleonline')=='Y') {
			$loggedusers[]=$user;
		} else {
					$NumAnonymous++;
			}
		}

	$id = "logged_in_users";
	$title = print_help_link("index_loggedin_help", "qm", "", false, true);
	$title .= $pgv_lang["users_logged_in"];
	$content = "<table width=\"90%\">";
	$LoginUsers = count($loggedusers);
	if (($LoginUsers == 0) and ($NumAnonymous == 0)) {
		$content .= "<tr><td><b>" . $pgv_lang["no_login_users"] . "</b></td></tr>";
	}
	$Advisory = "anon_user";
	if ($NumAnonymous > 1) {
		$Advisory .= "s";
	}
	if ($NumAnonymous > 0) {
		$pgv_lang["global_num1"] = $NumAnonymous; // Make it visible
		$content .= "<tr><td><b>" . print_text($Advisory, 0, 1) . "</b></td></tr>";
	}
	$Advisory = "login_user";
	if ($LoginUsers > 1) {
		$Advisory .= "s";
	}
	if ($LoginUsers > 0) {
		$pgv_lang["global_num1"] = $LoginUsers; // Make it visible
		$content .= "<tr><td><b>" . print_text($Advisory, 0, 1) . "</b></td></tr>";
	}
	foreach ($loggedusers as $user) {
		$userName=getUserFullName($user);
		$content .= "<tr><td>";
		$content .= "<br />" . PrintReady($userName);
		$content .= " - " . $user["username"];
		if ($cusername!=$user && get_user_setting($user, 'contactmethod')!="none") {
			$content .= "<br /><a href=\"javascript:;\" onclick=\"return message('" . $user . "');\">" . $pgv_lang["message"] . "</a>";
		}
		$content .= "</td></tr>";
	}
	$content .= "</table>";
	
	global $THEME_DIR;
	if ($block) include($THEME_DIR."/templates/block_small_temp.php");
	else include($THEME_DIR."/templates/block_main_temp.php");
}
?>
