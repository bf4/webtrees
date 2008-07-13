<?php
/**
 * Menu for punBB Module
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
 * @subpackage Modules, punBB
 * @version $Id: menu.php 507 2006-10-17 21:07:54Z canajun2eh $
 * @author Patrick Kellum
 */
//-- security check, only allow access from module.php
if(strstr($_SERVER['SCRIPT_NAME'], 'menu.php')){print "Now, why would you want to do that.  You're not hacking are you?";exit;}

// Load PGV embeding language file
global $language_settings, $LANGUAGE, $pgv_lang;
require_once 'modules/punbb/language/mod_en.php';
// Load other language file if needed
if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/punbb/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/punbb/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

class punbb_ModuleMenu
{
	/**
	 * get the punbb menu
	 * @todo	create a way to abstract menus for plugins
	 * @return Menu 	the menu item
	 */
	function &getMenu()
	{
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $gallery;
		if(!file_exists('modules/punbb.php')){return null;}

		if($TEXT_DIRECTION == 'rtl'){$ff = '_rtl';}else{$ff = '';}

		// punBB
		$menu = new Menu($pgv_lang['mod_punbb'], 'index.php?mod=punbb', 'down');
		if(!empty($PGV_IMAGES['menu_punbb']['large'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['menu_punbb']['large']}");}
		$menu->addClass("menuitem{$ff}", "menuitem_hover{$ff}", "submenu{$ff}");

		// Search
		$submenu = new Menu($pgv_lang['mod_punbb_search'], 'index.php?mod=punbb&amp;pgvaction=search');
		$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['search']['small']}");
		$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}");
		$menu->addSubmenu($submenu);

		// Site Admin
		if(userIsAdmin(getUserName()))
		{
			$submenu = new Menu();$submenu->isSeperator();$menu->addSubmenu($submenu);

			$submenu = new Menu($pgv_lang['mod_punbb_admin'], 'index.php?mod=punbb&amp;pgvaction=admin_index');
			$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}");
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}");
			$menu->addSubmenu($submenu);
		}

		return $menu;
	}
}
?>
