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
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once WT_ROOT.'includes/classes/class_module.php';
require_once WT_ROOT.'modules/googlemap/googlemap.php';

class googlemap_WT_Module extends WT_Module implements WT_Module_Config {
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;

	// Extend WT_Module
	public function getTitle() {
		return i18n::translate('Googlemap');
	}

	// Extend WT_Module
	public function getDescription() {
		return i18n::translate('Adds a tab to the individual page which maps the events of an individual and their close relatives on a Google map.');
	}

	// Implement WT_Module_Config
	public function getConfigLink() {
		return 'module.php?mod=googlemap&pgvaction=admin-config';
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
			$this->_tab = new googlemap_Tab();
			$this->_tab->setName($this->getName());
		}
		return $this->_tab; 
	}
}
?>
