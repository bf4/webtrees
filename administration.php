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
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'administration.php');
define('WT_THEME_DIR', 'themes/_administration/');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

print_header(i18n::translate('Administration'));

// Display a series of "blocks" of general information, vary according to admin or manager.
echo '<div id="about">';
	include WT_THEME_DIR.'about_webtrees.php';
echo '</div>';
echo '<div id="x">';
	echo '<div id="block1">';
		include WT_THEME_DIR.'user_stats.php';
	echo '</div>';
	echo '<div id="block3">';
		include WT_THEME_DIR.'recent_changes.php';
	echo '</div>';
	echo '<div id="block2">';
		include WT_THEME_DIR.'tree_stats.php';
	echo '</div>';
echo '</div>';
	
print_footer();
