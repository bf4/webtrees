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
 * @version $Id: user_info.php 9190 2010-07-28 02:50:49Z nigel $
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// Check for updates
$latest_version_txt=fetch_latest_version();
if ($latest_version_txt) {
	list($latest_version, $earliest_version, $download_url)=explode('|', $latest_version_txt);
	// If the latest version is newer than this version, show a download link.
	if (version_compare(WT_VERSION, $latest_version)<=0) {
		// A newer version is available.  Make a link to it
		$latest_version='<a href="'.$download_url.'" style="font-weight:bold; color:red;">'.$latest_version.'</a>';
	}
} else {
	// Cannot determine the latest version
	$latest_version='-';
}

echo '<h2>', i18n::translate('About webtrees'), '</h2>',
	'<p>', i18n::translate('Welcome to the <b>webtrees</b> administration page. This page provides access to all the site and family tree configuration settings as well as providing some useful information blocks. Administrators can upgrade to the lastest version with a single click, whenever the page reports that a new version is available.'), '</p>',
	'<p>' ,i18n::translate('Your installed  version of <b>webtrees</b> is: %s', WT_VERSION_TEXT),'</p>';
	if (version_compare(WT_VERSION, $latest_version)>0) {
		echo '<p>' ,i18n::translate('The latest stable <b>webtrees</b> version is: %s', $latest_version), '&nbsp;<span class="accepted">' ,i18n::translate('No upgrade required.'), '</span></p>';
	} else {
		echo '<p class="warning">' ,i18n::translate('We recommend you click here to upgrade to the latest stable webtrees version: %s', $latest_version), '</p>';
	}