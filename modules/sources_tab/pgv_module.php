<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
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
 * @package PhpGedView
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once(PGV_ROOT."includes/classes/class_module.php");
require_once(PGV_ROOT."modules/sources_tab/sources_tab.php");

class sources_tab_PGVModule extends PGVModule {
	protected $name = 'sources_tab';
	protected $description = 'Adds a tab to the individual page which displays the sources linked to an individual.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getTab() {
		if ($this->_tab==null) {
			$this->_tab = new sources_tab_Tab();
			$this->_tab->setName($this->getName());
		}
		return $this->_tab;
	}

	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return false; }
	/**
	 * does this module implement a tab
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasTab() { return true; }

}
?>