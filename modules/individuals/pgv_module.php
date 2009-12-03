<?php
require_once("includes/classes/class_module.php");
require_once("modules/individuals/individuals.php");

class individuals_PGVModule extends PGVModule {
	protected $name = 'individuals';
	protected $description = 'Adds a sidebar which allows for easy navigation of indiviuals in a list format.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_sidebar = null;

	public function hasSidebar() { return true; }
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getSidebar() {
		
		if ($this->_sidebar==null) {
			$this->_sidebar = new individuals_Sidebar();
			$this->_sidebar->setName($this->getName());
		}
		return $this->_sidebar; 
	}
}
?>