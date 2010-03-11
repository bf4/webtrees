<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once("includes/classes/class_module.php");
require_once("modules/notes/notes.php");

class page_menu_PGVModule extends PGVModule {
	protected $name = 'page_menu';
	protected $description = 'Adds a menu to the menu bar which provides page specific options.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';

	public function getName() {
		// TODO what is this module?
		return 'page_menu';
	}

	public function getTitle() {
		// TODO what is this module?
		return 'Unknown';
	}

	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return true; }
	/**
	 * does this module implement a tab
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasTab() { return false; }
	
	/**
	 * get the menu for this tab
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() { 
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		global $controller;

		$menu = null;
		if (empty($controller)) return null;

		if($TEXT_DIRECTION == 'rtl'){$ff = '_rtl';}else{$ff = '';}

		if (method_exists($controller, 'getOtherMenu')) {	
			$menu = $controller->getOtherMenu();
			$menu->addClass('menuitem'.$ff, 'menuitem_hover'.$ff, 'submenu'.$ff, 'icon_large_gedcom');
			$menu->addLabel($menu->label, 'down');
		}
		if (PGV_USER_CAN_EDIT && method_exists($controller, 'getEditMenu')) {
			$editmenu = $controller->getEditMenu();
			if ($menu==null) $menu = $editmenu;
			else {
				$menu->addLabel($editmenu->label, 'down');
				$menu->addIcon($editmenu->icon);
				$menu->addSeparator();
				foreach($editmenu->submenus as $sub) {
					$menu->addSubMenu($sub);
				}
			}
		}

		return $menu;
	}
}
?>
