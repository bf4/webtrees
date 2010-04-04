<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2009 John Finlay
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

require_once WT_ROOT.'includes/classes/class_module.php';
require_once WT_ROOT.'modules/family_nav/family_nav.php';

class family_nav_WT_Module extends WT_Module implements WT_Module_Sidebar {
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;

	// Extend WT_Module
	public function getTitle() {
		return i18n::translate('Family Navigator');
	}

	// Extend WT_Module
	public function getDescription() {
		return i18n::translate('Adds a tab to the individual page which displays a family navigator on the individual page.');
	}

	// Implement WT_Module_Sidebar
	public function defaultSidebarAccessLevel() {
		return WT_PRIV_PUBLIC;
	}

	// Implement WT_Module_Sidebar
	public function defaultSidebarOrder() {
		return 99;
	}
	
	// Implement WT_Module_Sidebar
	public function hasSidebarContent() {
		return true;
	}
	
	// Implement WT_Module_Sidebar
	public function getSidebarContent() {
		global $WT_IMAGE_DIR, $WT_IMAGES;

		$out = '<div id="sb_family_nav_content">';

		if ($this->controller) {
			$root = null;
			if ($this->controller->pid) {
				$root = Person::getInstance($this->controller->pid);
			}
			else if ($this->controller->famid) {
				$fam = Family::getInstance($this->controller->famid);
				if ($fam) $root = $fam->getHusband();
				if (!$root) $root = $fam->getWife(); 
			}
			if ($root!=null) {
				$tab = new family_nav_Tab();
				$this->controller = new IndividualController();
				$this->controller->indi=$root;
				$this->controller->pid=$root->getXref();
				$tab->setController($this->controller);
				$out .= $tab->getTabContent();
			}
		}
		$out .= '</div>';
		return $out;
	}

	// Implement WT_Module_Sidebar
	public function getSidebarAjaxContent() {
		return "";
	}

	/**
	 * does this module implement a tab
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasTab() { return true; }
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getTab() { 
		if ($this->_tab==null) {
			$this->_tab = new family_nav_Tab();
			$this->_tab->setName($this->getName());
		}
		return $this->_tab; 
	}
}
