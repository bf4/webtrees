<?php
require_once("includes/classes/class_module.php");
require_once("modules/lightbox/lightbox.php");

class lightbox_WT_Module extends WT_Module {
	protected $name = 'lightbox';
	protected $description = 'Adds a tab (Album) to the individual page which an alternate way to view and work with media.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $configLink = 'module.php?mod=lightbox&pgvaction=lb_editconfig';
	protected $_tab = null;

	public function getName() {
		return 'lightbox';
	}

	public function getTitle() {
		return i18n::translate('Album');
	}

	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getTab() {
		if ($this->_tab==null) {
			$this->_tab = new lightbox_Tab();
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
