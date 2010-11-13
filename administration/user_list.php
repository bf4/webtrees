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

define('WT_SCRIPT_NAME', 'user_admin.php');
require '../includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';
require 'admin_functions.php';

// Only admin users can access this page
//if (!WT_USER_IS_ADMIN) {
//	$LOGIN_URL=get_site_setting('LOGIN_URL');
//	$loginURL = "$LOGIN_URL?url=".urlencode(WT_SCRIPT_NAME."?".$QUERY_STRING);
//	header("Location: $loginURL");
//	exit;
//}

admin_header(i18n::translate('User administration'));

if ($ENABLE_AUTOCOMPLETE) require '../js/autocomplete.js.htm';

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

// Delete a user
if ($action=='deleteuser') {
	// don't delete ourselves
	$user_id=get_user_id($username);
	if ($user_id!=WT_USER_ID) {
		delete_user($user_id);
		AddToLog("deleted user ->{$username}<-", 'auth');
	}
	// User data is cached, so reload the page to ensure we're up to date
	header("Location: user_list.php");
	exit;
}

//-- echo out a list of the current users
ob_start();
	$users = get_all_users();
	
	// First filter the users, otherwise the javascript to unfold priviledges gets disturbed
	foreach($users as $user_id=>$user_name) {
		if ($filter == "warnings") {
			if (get_user_setting($user_id, 'comment_exp')) {
				if ((strtotime(get_user_setting($user_id, 'comment_exp')) == "-1") || (strtotime(get_user_setting($user_id, 'comment_exp')) >= time("U"))) unset($users[$user_id]);
			}
			else if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) <= 604800) || get_user_setting($user_id, 'verified')) unset($users[$user_id]);
		}
		else if ($filter == "adminusers") {
			if (!get_user_setting($user_id, 'canadmin')) unset($users[$user_id]);
		}
		else if ($filter == "usunver") {
			if (get_user_setting($user_id, 'verified')) unset($users[$user_id]);
		}
		else if ($filter == "admunver") {
			if ((get_user_setting($user_id, 'verified_by_admin')) || (!get_user_setting($user_id, 'verified'))) {
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
	echo '<h2>', i18n::translate('Manage users'), '</h2>',
		'<table id="list">',
			'<thead>',
				'<tr>',
					'<th style="width:60px;">', i18n::translate('Message'), '</th>',
					'<th style="width:100px;">', i18n::translate('Real name'), '</th>',
					'<th style="width:80px;">', i18n::translate('User name'), '</th>',
					'<th style="width:80px;">', i18n::translate('Languages'), '</th>',
					'<th style="width:90px;">', i18n::translate('Privileges'), '</th>',
					'<th style="width:100px;">', i18n::translate('Date registered'), '</th>',
					'<th style="width:100px;">', i18n::translate('Last logged in'), '</th>',
					'<th style="width:65px;">', i18n::translate('Verified '), '</th>',
					'<th style="width:65px;">', i18n::translate('Approved'), '</th>',
					'<th style="width:50px;">', i18n::translate('Delete'), '</th>',
					'<th style="width:50px;">', i18n::translate('More...'), '</th>',
				'</tr>',
			'</thead>',
			'<tbody>';
				foreach($users as $user_id=>$user_name) {
					echo "<div id=\"user\"><tr>\n";
					echo "\t<td>";
					if ($user_id!=WT_USER_ID && get_user_setting($user_id, 'contactmethod')!='none') {
						echo "<a href=\"javascript:;\" onclick=\"return message('", $user_name, "');\"><img src=\"images/email.png\" \"alt=\"", i18n::translate('Send Message'), "\" title=\"", i18n::translate('Send Message'), "\" /></a>";
					} else {
						echo '&nbsp;';
					}
					echo '</td>';
					$userName = getUserFullName($user_id);
					echo "\t<td><a class=\"edit_link\" href=\"", encode_url("user_edit.php?action=edituser&username={$user_name}&sort={$sort}&filter={$filter}&usrlang={$usrlang}&ged={$ged}"), "\" title=\"", i18n::translate('Edit'), "\">", $userName;
					if ($TEXT_DIRECTION=="ltr") echo getLRM();
					else                        echo getRLM();
					echo "</a></td>\n";
					if (get_user_setting($user_id, "comment_exp")) {
						if ((strtotime(get_user_setting($user_id, "comment_exp")) != "-1") && (strtotime(get_user_setting($user_id, "comment_exp")) < time("U")))
						echo '<td class="red">', $user_name;
						else echo '<td>', $user_name;
					}
					else echo '<td>', $user_name;
						if (get_user_setting($user_id, "comment")) {
							$tempTitle = PrintReady(get_user_setting($user_id, "comment"));
							echo '<img class="adminicon" align="top" alt="', $tempTitle, '" title="', $tempTitle, '" src="images/notes.png" />';
					}
					echo "</td>\n";
					echo "\t<td>", Zend_Locale::getTranslation(get_user_setting($user_id, 'language'), 'language', WT_LOCALE), "</td>\n";
					echo "\t<td>";
					echo '<div id="privileges">';
						if (get_user_setting($user_id, 'canadmin')) {
							echo "<span class=\"warning\">", i18n::translate('User can administer'), "</span>\n";
						}
						foreach ($all_gedcoms as $ged_id=>$ged_name) {
							switch (get_user_gedcom_setting($user_id, $ged_id, 'canedit')) {
							case 'admin':  echo '<span class="warning">', i18n::translate('Admin GEDCOM'); break;
							case 'accept': echo '<span class="warning">', i18n::translate('Accept'); break;
							case 'edit':   echo '<span>', i18n::translate('Edit'); break;
							case 'access': echo '<span>', i18n::translate('Access'); break;
							case 'none':
							default:       echo '<span>', i18n::translate('None'); break;
							}
							$uged = get_user_gedcom_setting($user_id, $ged_id, 'gedcomid');
							if ($uged) {
								echo ': <a href="individual.php?pid=', $uged, '&amp;ged=', urlencode($ged_name), '">', $ged_name, '</a></span><br />';
							} else {
								echo ': ', $ged_name, '</span><br />';
							}
						}
					echo '</div>';
					//echo "</div>";
					//$k++;
					echo '</td>';
						if (((date("U") - (int)get_user_setting($user_id, 'reg_timestamp')) > 604800) && !get_user_setting($user_id, 'verified'))
							echo '<td class="red">';
					else echo '<td>';
						echo format_timestamp((int)get_user_setting($user_id, 'reg_timestamp'));
					echo '</td>';
					echo '<td>';
						if ((int)get_user_setting($user_id, 'reg_timestamp') > (int)get_user_setting($user_id, 'sessiontime')) {
							echo i18n::translate('Never'), '<br />', i18n::time_ago(time() - (int)get_user_setting($user_id, 'reg_timestamp'));
						} else {
							echo format_timestamp((int)get_user_setting($user_id, 'sessiontime')), '<br />', i18n::time_ago(time() - (int)get_user_setting($user_id, 'sessiontime'));
						}
					echo '</td>',
					'<td class="center">';
						if (get_user_setting($user_id, 'verified')) echo i18n::translate('Yes');
						else echo i18n::translate('No');
					echo '</td>',
					'<td class="center">';
						if (get_user_setting($user_id, 'verified_by_admin')) echo i18n::translate('Yes');
						else echo i18n::translate('No');
					echo '</td>',
					'<td>';
						if (WT_USER_ID!=$user_id)
							echo "<a href=\"", encode_url("user_admin.php?action=deleteuser&username={$user_name}&sort={$sort}&filter={$filter}&usrlang={$usrlang}&ged={$ged}"), "\" onclick=\"return confirm('", i18n::translate('Are you sure you want to delete the user'), " $user_name');\"><img src=\"images/delete.png\" alt=\"", i18n::translate('Delete'), "\" title=\"", i18n::translate('Delete'), "\" /></a>";
					echo '</td>',
					'<td>',
						'<button>Toggle</button',
					'</td>',
				'</tr>',
				'</div>',
				'<div id="slide1">',
					'This is the paragraph to end all paragraphs.  You should feel <em>lucky</em> to have seen such a paragraph in your life.  Congratulations!',
				'</div>';
				}
			echo '</tbody>',
		'</table>';
	include 'admin_footer.php';
ob_flush();
?>