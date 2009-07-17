<?php
require_once("includes/classes/class_module.php");
require_once("modules/relatives/relatives.php");

class relatives_PGVModule extends PGVModule {
	protected $name = 'relatives';
	protected $description = 'Adds a tab to the individual page which displays the families and close relatives of an individual.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;

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
			$this->_tab = new relatives_Tab();
			$this->_tab->setName($this->getName());
		}
		return $this->_tab; 
	}
}
?>