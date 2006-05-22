<?php
/**
 * Menu Extension
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * @subpackage Themes
 * @version $Id: sample_custom_welcome_menu.php,v 1.1.2.1 2005/12/30 14:31:30 canajun2eh Exp $
 */
 /*
 * - To make these menu extensions appear at the top of each page, this file
 *   needs to be named custom_welcome_menu.php
 *
 *   Of course, the entries in this file need to be valid, since no error checking
 *   is done before this file is used.  Use the entries in menu.php as a guide on
 *   how this file should be constructed.
 *
 * - Welcome menu customizations
 */
	$menu->addSeperator();
	$submenu = new Menu("Custom Menu Item 1", "custom link #1");
	$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
	$menu->addSubmenu($submenu);

	$menu->addSeperator();
	$submenu = new Menu("Custom Menu Item 2", "custom link #2");
	$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
	$menu->addSubmenu($submenu);
?>