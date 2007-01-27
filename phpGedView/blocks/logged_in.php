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
	$thisuser = getUser($cusername);
	$NumAnonymous = 0;
	$users = getUsers();
	$loggedusers = array ();
	foreach ($users as $indexval => $user) {
		if ($user["loggedin"] == "Y") {
			if (time() - $user["sessiontime"] > $PGV_SESSION_TIME && $user["username"] != $cusername)
				userLogout($user["username"]);
			else {
				if ((userIsAdmin($cusername)) || (($user['visibleonline']) && ($thisuser['visibleonline'])))
					$loggedusers[] = $user;
				else
					$NumAnonymous++;
			}
		}
	}

	print "<div id=\"logged_in_users\" class=\"block\">\n";
	print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
	print "<td class=\"blockh1\" >&nbsp;</td>";
	print "<td class=\"blockh2\" ><div class=\"blockhc\">";
	print_help_link("index_loggedin_help", "qm");
	print "<b>" . $pgv_lang["users_logged_in"] . "</b>";
	print "</div></td>";
	print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
	print "</table>";
	print "<div class=\"blockcontent\">";
	if ($block)
		print "<div class=\"small_inner_block\">\n";
	print "<table width=\"90%\">";
	$LoginUsers = count($loggedusers);
	if (($LoginUsers == 0) and ($NumAnonymous == 0)) {
		print "<tr><td><b>" . $pgv_lang["no_login_users"] . "</b></td></tr>";
	}
	$Advisory = "anon_user";
	if ($NumAnonymous > 1)
		$Advisory .= "s";
	if ($NumAnonymous > 0) {
		$pgv_lang["global_num1"] = $NumAnonymous; // Make it visible
		print "<tr><td><b>" . print_text($Advisory, 0, 1) . "</b></td></tr>";
	}
	$Advisory = "login_user";
	if ($LoginUsers > 1)
		$Advisory .= "s";
	if ($LoginUsers > 0) {
		$pgv_lang["global_num1"] = $LoginUsers; // Make it visible
		print "<tr><td><b>" . print_text($Advisory, 0, 1) . "</b></td></tr>";
	}
	uasort($loggedusers, "usersort");
	foreach ($loggedusers as $indexval => $user) {
		print "<tr><td>";
		print "<br />" . PrintReady($user["firstname"] . " " . $user["lastname"]);
		print " - " . $user["username"];
		if (($cusername != $user["username"]) and ($user["contactmethod"] != "none")) {
			print "<br /><a href=\"javascript:;\" onclick=\"return message('" . $user["username"] . "');\">" . $pgv_lang["message"] . "</a>";
		}
		print "</td></tr>";
	}
	print "</table>";
	if ($block)
		print "</div>\n";
	print "</div>"; // blockcontent
	print "</div>"; // block
}
?>
