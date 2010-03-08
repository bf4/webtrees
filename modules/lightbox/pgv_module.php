<?php
require_once("includes/classes/class_module.php");
require_once("modules/lightbox/lightbox.php");

// Load PGV embeding language file
global $language_settings, $LANGUAGE;

// Load other language file if needed
if (isset($LANGUAGE)) {
	require_once 'modules/lightbox/languages/lang.en.php';
	if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/lightbox/languages/lang.{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/lightbox/languages/lang.{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}
}

class lightbox_PGVModule extends PGVModule {
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
		return i18n::translate('Lightbox');
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
