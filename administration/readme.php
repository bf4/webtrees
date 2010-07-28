<?php
/**
 * UI for online updating of the config file.
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
 * @version $Id: editgedcoms.php 8673 2010-06-09 06:29:03Z greg $
 */

define('WT_SCRIPT_NAME', 'readme.php');
require '../includes/session.php';
require 'admin_functions.php';

admin_header(i18n::translate('ReadMe'));
echo '<div id="readme">';
	include '../readme.html';
echo '</div>';
include 'admin_footer.php';
?>