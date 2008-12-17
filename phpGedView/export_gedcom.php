<?php
/**
 * Exports data from the database to a gedcom file
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008 Greg Roach.
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
 * @subpackage Charts
 * @version $Id$
 */

require './config.php';
require './includes/functions/functions_export.php';

// Which gedcoms do we have permission to export?
$gedcoms=array();
foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
	if (userGedcomAdmin(PGV_USER_ID, $ged_id)) {
		$gedcoms[$ged_id]=$gedcom;
	}
}

// If we don't have permission to administer any gedcoms, redirect to
// this page, which will force a login and provide a list.
if (empty($gedcoms)) {
	header('Location: editgedcoms.php');
}

// Which gedcom have we requested to export
$export=safe_GET('export', $gedcoms);

print_simple_header($pgv_lang['ged_export']);

if ($export) {
	$ged_id=get_id_from_gedcom($export);
	$filename=get_gedcom_setting($ged_id, 'path');
	echo '<h1>', $pgv_lang['ged_export'], '</h1>';
	echo '<p>', htmlspecialchars(filename_decode($export)), ' => ', $filename, '</p>';
	flush();
	$fp=fopen($filename, 'w');
	if ($fp) {
		$start=microtime(true);
		// Yuck - this function requres a global rather than a parameter
		$GEDCOM=$export;
		print_gedcom('no', null, 'no', 'no', $fp);
		$end=microtime(true);
		fclose($fp);
		$stat=stat($filename);
		echo sprintf('<p>%d bytes, %0.3f seconds</p>', $stat['size'], $end-$start);
	} else {
		echo '<p>Error: could not open file for writing</p>';
	}
} else {
	echo '<h1>Export data from database to gedcom file</h1>';
	echo '<ul>';
	foreach ($gedcoms as $ged_id=>$gedcom) {
		echo '<li><a href="?export=', urlencode($gedcom), '">', $gedcom, ' => ', htmlspecialchars(filename_decode(realpath(get_gedcom_setting($ged_id, 'path')))), '</a></li>';
	}
	echo '</ul>';
}

echo '<p><a href="javascript: ', $pgv_lang['close_window'], '" onclick="window.close();">', $pgv_lang['close_window'], '</a></p>';
print_simple_footer();
