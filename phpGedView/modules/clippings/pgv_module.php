<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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
 * @package webtrees
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once(WT_ROOT."includes/classes/class_module.php");
require_once(WT_ROOT."modules/clippings/clippings.php");

class clippings_WT_Module extends WT_Module {
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_sidebar = null;
	
	public function getTitle() {
		return i18n::translate('Clippings Cart');
	}

	public function getDescription() {
		return i18n::translate('Provides a clippings cart, to copy records for export/download.');
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
		global $TEXT_DIRECTION, $WT_IMAGE_DIR, $WT_IMAGES, $GEDCOM, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			return $menu;
		}
		//-- main clippings menu item
		$menu = new Menu(i18n::translate('Family Tree Clippings Cart'), encode_url('module.php?mod=clippings&amp;ged='.$GEDCOM), "down");
		if (!empty($WT_IMAGES["clippings"]["large"]))
			$menu->addIcon($WT_IMAGE_DIR."/".$WT_IMAGES["clippings"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_clippings");

		return $menu;
	}
}
?>
