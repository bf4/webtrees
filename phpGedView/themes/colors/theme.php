<?php
/**
 * Ash theme
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2010  PGV Development Team.  All rights reserved.
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
 * @subpackage Themes/Colors
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
        header('HTTP/1.0 403 Forbidden');
        exit;
}
/**
 * Complete list of selectable color sub-types for the Colors theme
 *
 */

$subColor="ash";

$theme_name       = "Default Color";
$SHARED_THEME_DIR = "themes/colors/";
define('SHARED_THEME_DIR', $SHARED_THEME_DIR);
$stylesheet       = $SHARED_THEME_DIR . "css/" . $subColor . ".css";
$print_stylesheet = $SHARED_THEME_DIR . "css/" . $subColor . ".css";
include('themes/colors/theme.inc');

