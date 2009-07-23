<?php
require_once("includes/classes/class_module.php");
require_once("modules/all_tab/all_tab.php");

class all_tab_PGVModule extends PGVModule {
	protected $name = 'all_tab';
	protected $description = 'Adds a tab to the individual page which displays the contents of all other active tabs.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getTab() {
		if ($this->_tab==null) {
			$this->_tab = new all_tab_Tab();
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
