<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 John Finlay
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
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once(PGV_ROOT."includes/classes/class_module.php");
require_once(PGV_ROOT."modules/clippings/clippings.php");

class clippings_PGVModule extends PGVModule {
	protected $name = 'clippings';
	protected $description = 'Clippings Cart PGV Module';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_sidebar = null;
	
	public function getName() {
		return i18n::translate('Clippings');
	}

	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return true; }
	public function hasSidebar() { return true; }
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getSidebar() {
		
		if ($this->_sidebar==null) {
			$this->_sidebar = new clippings_Sidebar();
			$this->_sidebar->setName($this->getName());
		}
		return $this->_sidebar; 
	}
	
	/**
	 * get the menu for this tab
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() { 
		global $ENABLE_CLIPPINGS_CART;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			return $menu;
		}
		//-- main clippings menu item
		$menu = new Menu(i18n::translate('Family Tree Clippings Cart'), encode_url('module.php?mod=clippings&amp;ged='.$GEDCOM), "down");
		if (!empty($PGV_IMAGES["clippings"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_clippings");

		return $menu;
	}
}
?>
