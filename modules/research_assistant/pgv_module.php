<?php
require_once("includes/classes/class_module.php");
require_once("modules/research_assistant/research_assistant.php");

// Load PGV embeding language file
global $language_settings, $LANGUAGE, $pgv_lang;
require_once 'modules/research_assistant/languages/lang.en.php';
// Load other language file if needed
if (isset($LANGUAGE))
if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/research_assistant/languages/lang.{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/research_assistant/languages/lang.{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

class research_assistant_PGVModule extends PGVModule {
	protected $name = 'research_assistant';
	protected $description = 'Provides help and guidance for genealogical research.  Adds a tab to the individual page and a menu item to the menubar.';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	protected $_tab = null;

	/**
	 * get the tab for this
	 * @return Tab
	 */
	public function &getTab() {
		if ($this->_tab==null) {
			$this->_tab = new research_assistant_Tab();
			$this->_tab->setName($this->getName());
		}
		return $this->_tab;
	}

	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return true; }
	/**
	 * does this module implement a tab
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasTab() { return true; }
	/**
	 * get the menu for this tab
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $SHOW_RESEARCH_ASSISTANT, $PRIV_USER, $PRIV_PUBLIC;
		global $SHOW_MY_TASKS, $SHOW_ADD_TASK, $SHOW_VIEW_FOLDERS;
		if (!file_exists("modules/research_assistant.php")) return null;
		if ($SHOW_RESEARCH_ASSISTANT<PGV_USER_ACCESS_LEVEL) return null;

		if (!file_exists('modules/research_assistant/languages/lang.en.php')) return null;

		loadLangFile("research_assistant:lang");

		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		//-- main search menu item
		$menu = new Menu($pgv_lang["research_assistant"], "module.php?mod=research_assistant", "down");
		if(!empty($PGV_IMAGES['menu_research']['large'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['menu_research']['large']}");}
		else $menu->addIcon("images/source.gif");
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_menu_research");

		//'My Tasks' ddl menu item
		if (PGV_USER_ACCESS_LEVEL<= $SHOW_MY_TASKS)
		{
			$submenu= new Menu($pgv_lang["my_tasks"], "module.php?mod=research_assistant&amp;action=mytasks");
			if(!empty($PGV_IMAGES['ra_mytasks']['small'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['ra_mytasks']['small']}");}
			else $submenu->addIcon('modules/research_assistant/images/folder_blue_icon.gif');
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_ra_mytasks");
			$menu->addSubmenu($submenu);
		}

		//'Add Task' ddl menu item
		if (PGV_USER_ACCESS_LEVEL<= $SHOW_ADD_TASK)
		{
			$submenu = new Menu($pgv_lang["add_task"], "module.php?mod=research_assistant&amp;action=addtask");
			if(!empty($PGV_IMAGES['ra_addtask']['small'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['ra_addtask']['small']}");}
			else $submenu->addIcon('modules/research_assistant/images/add_task.gif');
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_ra_addtask");
			$menu->addSubmenu($submenu);
		}

		//'View Folders' ddl menu item
		if (PGV_USER_ACCESS_LEVEL<= $SHOW_VIEW_FOLDERS)
		{
			$submenu = new Menu($pgv_lang["view_folders"], "module.php?mod=research_assistant&amp;action=view_folders");
			if(!empty($PGV_IMAGES['ra_folder']['small'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['ra_folders']['small']}");}
			else $submenu->addIcon('modules/research_assistant/images/folder_blue_icon.gif');
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_ra_folder");
			$menu->addSubmenu($submenu);
		}

		return $menu;
	}
}
?>