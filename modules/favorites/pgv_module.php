<?php
require_once("includes/classes/class_module.php");
require_once("modules/favorites/favorites.php");

// Load PGV embeding language file
global $pgv_lang;


class favorites_PGVModule extends PGVModule {
	protected $name = 'favorites';
	protected $description = 'Favorites modules that provides a sidebar';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_sidebar = null;
	
	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return false; }
	public function hasSidebar() { return true; }
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getSidebar() {
		
		if ($this->_sidebar==null) {
			$this->_sidebar = new favorites_Sidebar();
			$this->_sidebar->setName($this->getName());
		}
		return $this->_sidebar; 
	}
}
?>