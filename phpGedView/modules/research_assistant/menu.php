<?php
/**
 * Menu for research assistant
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005	John Finlay and Others
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package PhpGedView
 * @subpackage Charts
 * @version $Id: menu.php,v 1.2 2006/05/22 20:18:20 yalnifj Exp $
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"menu.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}

class research_assistant_ModuleMenu {
	/**
	 * get the research assistant menu
	 * @todo	create a way to abstract menus for plugins
	 * @return Menu 	the menu item
	 */
	function &getMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $SHOW_RESEARCH_ASSISTANT, $PRIV_USER, $PRIV_PUBLIC;
		if (!file_exists("modules/research_assistant.php")) return null;
		if ($SHOW_RESEARCH_ASSISTANT<getUserAccessLevel()) return null;

		if (!file_exists('modules/research_assistant/languages/ra_lang.en.php')) return null;

		require_once 'modules/research_assistant/languages/ra_lang.en.php';
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		//-- main search menu item
		$menu = new Menu($pgv_lang["research_assistant"], "module.php?mod=research_assistant", "down");
		if (!empty($PGV_IMAGES["gedcom"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");

		if(GetUserName() != "")
		{
			$submenu= new Menu($pgv_lang["my_tasks"], "module.php?mod=research_assistant&amp;action=mytasks");
			$submenu->addIcon('modules/research_assistant/images/folder_blue_icon.gif');
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		
		$submenu = new Menu($pgv_lang["view_folders"], "module.php?mod=research_assistant");
		$submenu->addIcon('modules/research_assistant/images/folder_blue_icon.gif');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);

		$submenu = new Menu($pgv_lang["add_task"], "module.php?mod=research_assistant&amp;action=addtask");
		$submenu->addIcon('modules/research_assistant/images/add_task.gif');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);

		$submenu = new Menu($pgv_lang["add_folder"], "module.php?mod=research_assistant&amp;action=addfolder");
		$submenu->addIcon('modules/research_assistant/images/folder_blue_icon.gif');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		
		$submenu = new Menu($pgv_lang["gen_tasks"], "module.php?mod=research_assistant&amp;action=genTasks");
		$submenu->addIcon('modules/research_assistant/images/add_task.gif');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);

		return $menu;
	}
}
?>