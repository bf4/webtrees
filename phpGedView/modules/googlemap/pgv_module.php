<?php
require_once("includes/classes/class_module.php");
require_once("modules/googlemap/googlemap.php");

class googlemap_WTModule extends WTModule {
	protected $name = 'googlemap';
	protected $description = 'Adds a tab to the individual page which maps the events of an individual and their close relatives on a Google map.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $configLink = 'module.php?mod=googlemap&pgvaction=admin-config';
	protected $_tab = null;

	public function getName() {
		return 'googlemap';
	}

	public function getTitle() {
		return i18n::translate('Googlemap');
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
			$this->_tab = new googlemap_Tab();
			$this->_tab->setName($this->getName());
		}
		return $this->_tab; 
	}
}
?>
