<?php
require_once("includes/classes/class_module.php");

// Load PGV embeding language file
global $language_settings, $LANGUAGE, $pgv_lang;
require_once 'modules/gallery2/language/mod_en.php';
// Load other language file if needed
if (isset($LANGUAGE))
if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/gallery2/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/gallery2/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

class gallery2_PGVModule extends PGVModule {
	protected $name = 'gallery2';
	protected $description = 'Gallery2 PGV Module';
	protected $version = '4.2.2';
	protected $pgvVersion = '4.2.2';
	
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
	public function hasTab() { return false; }
	/**
	 * get the menu for this tab
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() { 
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $gallery;
		if(!file_exists('modules/gallery2.php') || !file_exists('modules/gallery2/embed.php')){return null;}

		if($TEXT_DIRECTION == 'rtl'){$ff = '_rtl';}else{$ff = '';}

		// Gallery
		$menu = new Menu($pgv_lang['mod_gallery2'], 'module.php?mod=gallery2', 'down');
		if(!empty($PGV_IMAGES['menu_gallery']['large'])){$menu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['menu_gallery']['large']}");}
		$menu->addClass("menuitem{$ff}", "menuitem_hover{$ff}", "submenu{$ff}", "icon_large_menu_gallery");

		// Advanced Search
		$submenu = new Menu($pgv_lang['mod_gallery2_advsearch'], 'module.php?mod=gallery2&amp;g2_view=search.SearchScan');
		$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['search']['small']}");
		$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "", "icon_small_search");
		$menu->addSubmenu($submenu);

		// User Album
/*
		list($ret, $pluginStatus) = GalleryCoreApi::fetchPluginStatus('module');
		if(!$ret)
		{
			if(!empty($pluginStatus['useralbum']))
			{
				if($pluginStatus['useralbum']['active'] == true)
				{
					$username = getUserName();
					list($ret, $idMap) = GalleryEmbed::getExternalIdMap('externalId');
					if($username && isset($idMap[$username]) && !$ret)
					{
						list($ret, $params) = GalleryCoreApi::fetchAllPluginParameters('module', 'useralbum');
						if(!$ret && count($params))
						{
							if(!$ret)
							{
								list($ret, $albumId) = GalleryCoreApi::getPluginParameter('module', 'useralbum', 'albumId', $idMap[$username]['entityId']);
								if(!$ret)
								{
									if(empty($albumId))
									{
										$submenu = new Menu($pgv_lang['mod_gallery2_useralbum'], 'module.php?mod=gallery2&amp;g2_controller=useralbum.UserAlbum');
									}
									else
									{
										$submenu = new Menu($pgv_lang['mod_gallery2_useralbum'], "module.php?mod=gallery2&amp;g2_controller=useralbum.UserAlbum&amp;g2_view=core.ShowItem&amp;g2_itemId={$albumId}");
									}
									$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['indis']['small']}");
									$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}");
									$menu->addSubmenu($submenu);
								}
							}
						}
					}
				}
			}
		}
*/

		// Site Admin
		if(userIsAdmin(getUserName()))
		{
			$submenu = new Menu($pgv_lang['mod_gallery2_siteadmin'], 'module.php?mod=gallery2&amp;g2_view=core.SiteAdmin');
			$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}");
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "", "icon_small_admin");
			$menu->addSubmenu($submenu);
		}

		return $menu;
	}
}
?>