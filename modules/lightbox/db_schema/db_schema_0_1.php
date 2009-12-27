<?php
/**
 * Update the LB module database schema from version 0 to version 1
 *
 * Version 0: empty database
 * Version 1: move the configuration from lb_config.php (PGV 4.2.3 and earlier) to the pgv_site_setting table
 *
 * The script should assume that it can be interrupted at
 * any point, and be able to continue by re-running the script.
 * Fatal errors, however, should be allowed to throw exceptions,
 * which will be caught by the framework.
 * It shouldn't do anything that might take more than a few
 * seconds, for systems with low timeout values.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2009 Greg Roach
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

if (!defined('PGV_PHPGEDVIEW')) {
header('HTTP/1.0 403 Forbidden');
exit;
}

define('PGV_LB_DB_SCHEMA_0_1', '');

if (file_exists(PGV_ROOT.'modules/lightbox/lb_config.php')) {
	// Use @, in case the lb_config.php file is incomplete/corrupt
	@require_once PGV_ROOT.'modules/lightbox/lb_config.php';
  @set_site_setting('LB_ENABLED',        $mediatab);
  @set_site_setting('LB_AL_HEAD_LINKS',  $LB_AL_HEAD_LINKS);
  @set_site_setting('LB_AL_THUMB_LINKS', $LB_AL_THUMB_LINKS);
  @set_site_setting('LB_TT_BALLOON',     $LB_TT_BALLOON);
  @set_site_setting('LB_ML_THUMB_LINKS', $LB_ML_THUMB_LINKS);
  @set_site_setting('LB_MUSIC_FILE',     $LB_MUSIC_FILE);
  @set_site_setting('LB_SS_SPEED',       $LB_SS_SPEED);
  @set_site_setting('LB_TRANSITION',     $LB_TRANSITION);
	@set_site_setting('LB_URL_WIDTH',      $LB_URL_WIDTH);
	@set_site_setting('LB_URL_HEIGHT',     $LB_URL_HEIGHT);
	@unlink(PGV_ROOT.'modules/lightbox/lb_config.php');
}

// Update the version to indicate sucess
set_site_setting($schema_name, $next_version);
