<?php
require_once("includes/classes/class_module.php");
require_once("modules/googlemap/googlemap.php");

// Load PGV embeding language file
global $language_settings, $LANGUAGE, $pgv_lang;
require_once 'modules/googlemap/languages/lang.en.php';
// Load other language file if needed
if (isset($LANGUAGE))
if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/googlemap/languages/lang.{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/googlemap/languages/lang.{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

class googlemap_PGVModule extends PGVModule {
	protected $name = 'googlemap';
	protected $description = 'Adds a tab to the individual page which maps the events of an individual and their close relatives on a Google map.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $configLink = 'module.php?mod=googlemap&pgvaction=admin-config';
	protected $_tab = null;

	public function getName() {
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
