<?php
require_once("includes/classes/class_module.php");
require_once("modules/family_nav/family_nav.php");

global $pgv_lang;
if (isset($pgv_lang["fam_navigator"])) $pgv_lang['family_nav'] = $pgv_lang["fam_navigator"];
else $pgv_lang['family_nav'] = "Family Navigator";

class family_nav_PGVModule extends PGVModule {
	protected $name = 'family_nav';
	protected $description = 'Adds a tab to the individual page which displays a family navigator on the individual page.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;
	protected $_sidebar = null;

	public function hasSidebar() { return true; }
	
	public function &getSidebar() {
		
		if ($this->_sidebar==null) {
			$this->_sidebar = new family_nav_Sidebar();
			$this->_sidebar->setName($this->getName());
		}
		return $this->_sidebar; 
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
?>