<?php
/**
  * Update the database schema from version 10 to version 11
 *
 * Version 0: empty database
 * Version 1: create the pgv_site_setting table
 * Version 2: create the user tables, as per PhpGedView 4.2.1
 * Version 3: create the genealogy tables, as per PhpGedView 4.2.1
 *   From Version 4 onwards, we make incremental changes, rather than
 *   trying to introspect/update.  It's more efficient, flexible,
 *   and allows us to update column types, indexes, etc.
 * Version 4: Performance tuning: update column definitions for pgv_dates
 * Version 5: Performance tuning: update column definitions for pgv_individuals
 * Version 6: Performance tuning: update column definitions for pgv_families
 * Version 7: Performance tuning: update column definitions for pgv_sources
 * Version 8: Performance tuning: update column definitions for pgv_media/media_mapping
 * Version 9: Performance tuning: update column definitions for pgv_favorites/nextid/other/placelinks/places/remotelinks
 * Version 10: Move the $DEFAULT_GEDCOM setting from gedcoms.php to pgv_site_setting
 * Version 11: Add the modules and module_privacy tables for module administration
 * Version 12: Move $GEDCOMS from gedcoms.php to pgv_gedcom and pgv_gedcom_setting, split pgv_users into pgv_user/pgv_user_setting/pgv_user_gedcom_setting
 * Version 13: Move the hit-counters to a database table
 * Version 14: Move the banned/search-engine IP addresses to a database table
 * Version 15: Remove user-setting "sync_gedcom".  Drop pgv_users table (finish step 12).
 * Version 16: Add index to fix very slow searches from quick-search
 * Version 16: Add index to speed up indilist/famlist
 * 
 * The script should assume that it can be interrupted at
 * any point, and be able to continue by re-running the script.
 * Fatal errors, however, should be allowed to throw exceptions,
 * which will be caught by the framework.
 * It shouldn't do anything that might take more than a few
 * seconds, for systems with low timeout values.
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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

define('WT_SCHEMA_16_17', '');

// Without this index, MySQL 5.0 databases grind to a halt.  (4.1 and 5.1 seem OK.)
self::exec("ALTER TABLE {$TBLPREFIX}name ADD INDEX {$TBLPREFIX}name_ix2 (n_file, n_surn)");
 
// Update the version to indicate sucess
set_site_setting($schema_name, $next_version);
