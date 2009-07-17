<?php
require_once("includes/classes/class_module.php");

// Load PGV embeding language file
global $language_settings, $LANGUAGE, $pgv_lang;
require_once 'modules/punbb/language/mod_en.php';
// Load other language file if needed
if (isset($LANGUAGE))
	if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/punbb/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/punbb/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

class punbb_PGVModule extends PGVModule {
	protected $name = 'punbb';
	protected $description = 'Pun BB forums PGV Module';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $configLink = 'module.php?mod=punbb&pgvaction=install';
	
	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return true; }
	/**
	 * get the menu for this tab
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() { 
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $gallery;
		if(!file_exists('modules/punbb.php') || !file_exists('modules/punbb/config.php')){return null;}

		if($TEXT_DIRECTION == 'rtl'){$ff = '_rtl';}else{$ff = '';}

		// punBB
		$menu = new Menu($pgv_lang['mod_punbb'], 'module.php?mod=punbb', 'down');
		if(!empty($PGV_IMAGES['menu_punbb']['large'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['menu_punbb']['large']}");}
		$menu->addClass("menuitem{$ff}", "menuitem_hover{$ff}", "submenu{$ff}", "icon_large_menu_punbb");

		// Search
		$submenu = new Menu($pgv_lang['mod_punbb_search'], 'module.php?mod=punbb&amp;pgvaction=search');
		$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['search']['small']}");
		$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "", "icon_small_search");
		$menu->addSubmenu($submenu);

		// Site Admin
		if(userIsAdmin(getUserName()))
		{
			$submenu = new Menu();$submenu->isSeparator();$menu->addSubmenu($submenu);

			$submenu = new Menu($pgv_lang['mod_punbb_admin'], 'module.php?mod=punbb&amp;pgvaction=admin_index');
			$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}");
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "", "icon_small_admin");
			$menu->addSubmenu($submenu);
		}

		return $menu;
	}
}
?>