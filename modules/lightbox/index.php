<?php
/**
 * Entry point for Lightbox module when changing theme on configuration page
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2010  PGV Development Team. All rights reserved.
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
 * @subpackage Lightbox
 * @author Brian Holland
 * $Id$
 */
require_once PGV_ROOT.'includes\functions\functions.php';
$pid = safe_REQUEST($_REQUEST, 'pid', PGV_REGEX_XREF);
if ($pid) {
	header("Location: module.php?mod=lightbox&pgvaction=lb_editconfig&pid=$pid");
} else {
	header("Location: module.php?mod=lightbox&pgvaction=lb_editconfig");
}
exit;
?>