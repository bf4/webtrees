<?php
/**
 * Logged In Users Block
 *
 * This block will print a list of the users who are currently logged in
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage Blocks
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_LOGGED_IN_PHP', '');

$PGV_BLOCKS["print_logged_in_users"]["name"]		= i18n::translate('Logged In Users');
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

	// Log out inactive users
	foreach (get_idle_users(time()-$PGV_SESSION_TIME) as $user_id=>$user_name) {
		if ($user_id!=PGV_USER_ID) {
			userLogout($user_id);
		}
	}

	// List active users
	$NumAnonymous = 0;
	$loggedusers = array ();
	foreach (get_logged_in_users() as $user_id=>$user_name) {
		if (PGV_USER_IS_ADMIN || get_user_setting($user_id, 'visibleonline')=='Y') {
			$loggedusers[$user_id]=$user_name;
		} else {
			$NumAnonymous++;
		}
	}

	$id = "logged_in_users";
	$title = print_help_link("index_loggedin", "qm", "", false, true);
	$title.=i18n::translate('Users Logged In');
	$content='<table width="90%">';
	$LoginUsers=count($loggedusers);
	if ($LoginUsers==0 && $NumAnonymous==0) {
		$content.='<tr><td><b>' . i18n::translate('No logged-in and no anonymous users') . '</b></td></tr>';
	}
	if ($NumAnonymous>0) {
		$content.='<tr><td><b>' . i18n::plural('%d anonymous logged-in user', '%d anonymous logged-in users', $NumAnonymous, $NumAnonymous) . '</b></td></tr>';
	}
	if ($LoginUsers>0) {
		$content.='<tr><td><b>' . i18n::plural('%d logged-in user', '%d logged-in users', $LoginUsers, $LoginUsers) . '</b></td></tr>';
	}
	if (PGV_USER_ID) {
		foreach ($loggedusers as $user_id=>$user_name) {
			$content .= "<tr><td><br />".PrintReady(getUserFullName($user_id))." - ".$user_name;
			if (PGV_USER_ID!=$user_id && get_user_setting($user_id, 'contactmethod')!="none") {
				$content .= "<br /><a href=\"javascript:;\" onclick=\"return message('" . $user_id . "');\">" . i18n::translate('Send Message') . "</a>";
			}
			$content .= "</td></tr>";
		}
	}
	$content .= "</table>";

	global $THEME_DIR;
	if ($block) {
		require $THEME_DIR.'templates/block_small_temp.php';
	} else {
		require $THEME_DIR.'templates/block_main_temp.php';
	}
}
?>
