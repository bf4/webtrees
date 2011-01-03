<?php
/**
 * Update the database schema from version 6 to 7
 * - remove tables/columns relating to remote linking
 *
 * The script should assume that it can be interrupted at
 * any point, and be able to continue by re-running the script.
 * Fatal errors, however, should be allowed to throw exceptions,
 * which will be caught by the framework.
 * It shouldn't do anything that might take more than a few
 * seconds, for systems with low timeout values.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 Greg Roach
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
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_DB_SCHEMA_6_7', '');

try {
	self::exec(
		"DROP TABLE `##remotelinks`"
	);
} catch (PDOException $ex) {
	// already been done?
}

try {
	self::exec(
		"ALTER TABLE `##sources` DROP INDEX ix2"
	);
} catch (PDOException $ex) {
	// already been done?
}

try {
	self::exec(
		"ALTER TABLE `##sources` DROP COLUMN s_dbid"
	);
} catch (PDOException $ex) {
	// already been done?
}

// Update the version to indicate success
set_site_setting($schema_name, $next_version);

