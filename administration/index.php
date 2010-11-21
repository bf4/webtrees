<?php
/**
 * Welcome page for the administration module
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
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
 * @version $Id: site_config.php 9845 2010-11-15 13:05:37Z greg $
 */

define('WT_SCRIPT_NAME', 'administration/index.php');
require '../includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';
require WT_ROOT.'administration/admin_functions.php';

if (safe_GET_bool('auto_update')) {
	// TODO - need a "simple_header".
	echo "doing the update here - be patient";
	// Check for updates
	$latest_version_txt=fetch_remote_file('webtrees.net', '/latest-version.txt'); // TODO - only fetch once/day
	if ($latest_version_txt) {
		list($latest_version, $earliest_version, $download_url)=explode('|', $latest_version_txt);
		if (version_compare(WT_VERSION, $latest_version)>=0) {
			echo '<p>You are already running the latest version of webtrees</p>';
		} else {
			if (version_compare(WT_VERSION, $earliest_version)>=0) {
				$download_name=basename($download_url);
				// Download .zip file
				// Unpack .zip files
			} else {
				echo '<p>There are special upgrade instructions for this release.  See http://webtrees.net.</p>';
			}
		}
	} else {
		echo '<p>Cannot connect to server</p>';
	}
	exit;
}

admin_header(i18n::translate(WT_WEBTREES));
// Display a series of "blocks" of general information, vary according to admin or manager.
echo '<div id="about">',include 'about_webtrees.php','</div>',
	'<div id="user_info">',include 'user_stats.php','</div>',

// Check for updates
$latest_version_txt=fetch_remote_file('webtrees.net', '/latest-version.txt'); // TODO - only fetch once/day
if ($latest_version_txt) {
	list($latest_version, $earliest_version, $download_url)=explode('|', $latest_version_txt);
	if (version_compare(WT_VERSION, $latest_version)>=0) {
		echo '<p>You are already running the latest version of webtrees</p>';
	} else {
		echo '<p>A newer version of webtrees is available: ', $latest_version, '</p>';
		echo '<p>You can download it from <a href="', $download_url, '">', $download_url, '</a></p>';
		if (version_compare(WT_VERSION, $earliest_version)>=0) {
			echo '<p>Auto-update is possible - click <a href="#" onclick="window.open(\'', WT_SERVER_NAME, WT_SCRIPT_PATH, WT_SCRIPT_NAME, '?auto_update=1\', \'_blank\', \'top=50,left=50,width=700,height=600,resizable=1,scrollbars=1\'); return false;">here</a>.</p>';
		} else {
			echo '<p>There are special upgrade instructions for this release.  See http://webtrees.net.</p>';
		}
	}
}

include 'admin_footer.php';
