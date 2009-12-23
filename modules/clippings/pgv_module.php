<?php
require_once("includes/classes/class_module.php");
require_once("modules/clippings/clippings.php");

// Load PGV embeding language file
global $pgv_lang;


class clippings_PGVModule extends PGVModule {
	protected $name = 'clippings';
	protected $description = 'Clippings Cart PGV Module';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_sidebar = null;
	
	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return true; }
	public function hasSidebar() { return true; }
	
	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getSidebar() {
		
		if ($this->_sidebar==null) {
			$this->_sidebar = new clippings_Sidebar();
			$this->_sidebar->setName($this->getName());
		}
		return $this->_sidebar; 
	}
	
	/**
	 * get the menu for this tab
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() { 
		global $ENABLE_CLIPPINGS_CART;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			return $menu;
		}
		//-- main clippings menu item
		$menu = new Menu($pgv_lang["clippings_cart"], encode_url('module.php?mod=clippings&amp;ged='.$GEDCOM), "down");
		if (!empty($PGV_IMAGES["clippings"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_clippings");

		return $menu;
	}
}
?>