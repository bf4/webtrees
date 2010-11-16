<?php
/**
 * Administrative User Interface.
 *
 * Provides links for administrators to get to other administrative areas of the site
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team
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
 * @subpackage Administration
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'administration/administration.php');
require '../includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';
require WT_ROOT.'includes/classes/class_stats.php';
require WT_ROOT.'administration/admin_functions.php';

$INDEX_DIRECTORY=get_site_setting('INDEX_DIRECTORY');

$stats=new stats(WT_GEDCOM);

admin_header(i18n::translate('User information'));

// USER INFORMATION
	// Load all available gedcoms
	$all_gedcoms = get_all_gedcoms();
	//-- sorting by gedcom filename 
	asort($all_gedcoms);
	$totusers = 0;			// Total number of users
	$warnusers = 0;			// Users with warning
	$applusers = 0;			// Users who have not verified themselves
	$nverusers = 0;			// Users not verified by admin but verified themselves
	$adminusers = 0;		// Administrators
	$userlang = array();	// Array for user languages
	$gedadmin = array();	// Array for gedcom admins

echo '<h1>', i18n::translate('User information'), '</h1>',
'<div id="user_info">',
	'<table class="">',
		'<tr>',
			'<td class="">';
				foreach(get_all_users() as $user_id=>$user_name) {
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
		
				echo i18n::translate('Total number of users'),
			'</td>',
			'<td class="" colspan="2">', $totusers, '</td>',
		'</tr>',
		'<tr>',
			'<td class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				if ($adminusers == 0) echo i18n::translate('Site Administrators');
				else echo "<a href=\"user_admin.php?action=listusers&amp;filter=adminusers\">", i18n::translate('Site Administrators'), "</a>";
			echo '</td>',
			'<td class="" colspan="2">', $adminusers, '</td>',
		'</tr>',
		'<tr>',
			'<td class="" colspan="3">', i18n::translate('GEDCOM Administrators'), '</td>',
		'</tr>';
			asort($gedadmin);
			$ind = 0;
			foreach ($gedadmin as $key=>$geds) {
				//if ($ind !=0) echo '<td class=""></td>';
				$ind = 1;
				echo '<tr><td class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				if ($geds["number"] == 0) echo $geds["name"];
				else echo "<a href=\"", "user_admin.php?action=listusers&amp;filter=gedadmin&amp;ged=".rawurlencode($geds["ged"]), "\">", $geds["name"], "</a>";
				echo '</td><td class="" colspan="2">', $geds["number"], '</td></tr>';
			}
		echo '<tr>',
			'<td class="\>';
				if ($warnusers == 0) echo i18n::translate('Users with warnings');
				else echo "<a href=\"user_admin.php?action=listusers&amp;filter=warnings\">", i18n::translate('Users with warnings'), "</a>";
			echo '</td>',
			'<td class="" colspan="2">', $warnusers, '</td>',
		'</tr>',
		'<tr>',
			'<td class="">';
				if ($applusers == 0) echo i18n::translate('Unverified by User');
				else echo "<a href=\"user_admin.php?action=listusers&amp;filter=usunver\">", i18n::translate('Unverified by User'), "</a>";
			echo '</td>',
			'<td class="" colspan="2">', $applusers, '</td>',
		'</tr>',
		'<tr>',
			'<td class="">';
				if ($nverusers == 0) echo i18n::translate('Unverified by Administrator');
				else echo "<a href=\"user_admin.php?action=listusers&amp;filter=admunver\">", i18n::translate('Unverified by Administrator'), "</a>";
			echo '</td>',
			'<td class="" colspan="2">', $nverusers, '</td>',
		'</tr>',
		'<tr valign="middle">',
			'<td class="">', i18n::translate('Users\' languages'), '</td>';
			foreach ($userlang as $key=>$ulang) {
				echo '<td><a href="user_admin.php?action=listusers&amp;filter=language&amp;usrlang=', $key, '">', $ulang['langname'], '</a></td><td>', $ulang['number'], '</td></tr>';
			}
		echo '</tr>',
		'<tr>',
			'<td class="">', i18n::translate('Users logged in'), '</td>',
			'<td class="" colspan="2">', $stats->_usersLoggedIn(), '</td>',
		'</tr>',
	'</table>',
'</div>',
'<br />';
include 'admin_footer.php';
