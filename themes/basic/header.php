<?php global $SEARCH_SPIDER, $TEXT_DIRECTION, $GEDCOM; ?>
<?php 
	$menubar = new MenuBar();
	$username = getUserName();
	$user = getUser($username);
?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
	<div id="titlediv" class="title nowrap">
		<div id="gedcommenu">
			<?php 		
				$menu = $menubar->getGedcomMenu(); 
				$menu->label="";
				if($menu->link != "") {
					$menu->printMenu(); 
				}
			?>
		</div>
		<?php print_gedcom_title_link(TRUE); ?>
	</div>
	<div id="searchform">
		<?php if(empty($SEARCH_SPIDER)) { ?>
			<form action="search.php" method="get">
				<input type="hidden" name="action" value="general" />
				<input type="hidden" name="topsearch" value="yes" />
				<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="12" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
				<input type="submit" name="search" value="&gt;" />
			</form>
			<a href="search.php?action=general">Advanced Search</a>
		<?php } ?>
	</div>

	<div id="langform">
		<?php if(empty($SEARCH_SPIDER)) { print_lang_form(); } ?>
	</div>
	<div id="themeform">
		<?php if(empty($SEARCH_SPIDER)) { print_theme_dropdown(); } ?>
	</div>
	<div id="navmenus">
		<table>
		<tr>
		<td>
			<div id="navmenu" class="center">
				<?php
				$menu = new Menu('Navigate', 'index.php?command=gedcom', 'right');
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES['gedcom']['small']);
				$menu->addClass('menuitem', 'menuitem_hover', 'submenu');
				
				$submenu = $menubar->getHomeMenu();
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES['place']['small']);
				$submenu->addFlyout('right');
				$submenu->labelpos = 'right';
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'subsubmenu');
				$menu->addSubMenu($submenu);
				
				$submenu = $menubar->getListsMenu();
				$submenu->addLabel($submenu->label." &nbsp;<strong>&gt;&gt;</strong>");
				$submenu->addFlyout('right');
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'subsubmenu');
				$menu->addSubMenu($submenu);
				
				$submenu = $menubar->getCalendarMenu();
				$submenu->addLabel($submenu->label." &nbsp;<strong>&gt;&gt;</strong>");
				$submenu->addFlyout('right');
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'subsubmenu');
				$menu->addSubMenu($submenu);
				
				$submenu = $menubar->getClippingsMenu(); 
				if ((!is_null($menu)) && ($menu->link != "") && $submenu->subCount()>0) {
					$submenu->addLabel($submenu->label." &nbsp;<strong>&gt;&gt;</strong>");
					$submenu->addFlyout('right');
					$submenu->addClass('submenuitem', 'submenuitem_hover', 'subsubmenu');
					$menu->addSubMenu($submenu);
				} 
				
				$submenu = $menubar->getReportsMenu(); 
				if ((!is_null($menu)) && ($menu->link != "") && $submenu->subCount()>0) {
					$submenu->addLabel($submenu->label." &nbsp;<strong>&gt;&gt;</strong>");
					$submenu->addFlyout('right');
					$submenu->addClass('submenuitem', 'submenuitem_hover', 'subsubmenu');
					$menu->addSubMenu($submenu);
				} 
				
				$menu->addSeperator();
				
				$menus = $menubar->getModuleMenus(); 
				foreach($menus as $m=>$submenu) { 
					$submenu->addLabel($submenu->label." &nbsp;<strong>&gt;&gt;</strong>");
					$submenu->addFlyout('right');
					$submenu->addClass('submenuitem', 'submenuitem_hover', 'subsubmenu');
					$menu->addSubMenu($submenu);
				}
				
				$menu->printMenu();
				?>
			</div>
		</td>
		<td>
			<div id="mygedviewmenu" class="center">
				<?php
					$menu = $menubar->getMygedviewMenu(); 
					if($menu->link != "") {
						if (empty($username)){
							$menu->label = $pgv_lang['login'];
							$menu->link = "login.php?url=individual.php";
						}
						else $menu->label = "User Options";
						$menu->labelpos = 'right';
						$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES['mygedview']['small']);
						$menu->printMenu(); 
					}
				?>	
			</div>
		</td>
		<td class="center">
			<div id="favorites_form">
			<?php 
				$favmenu = print_favorite_selector(2);
				if (count($favmenu['items'])>0) {
					$favmenuobj = Menu::convertMenu($favmenu);
					$favmenuobj->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES['sfamily']['small']);
					$favmenuobj->labelpos = 'right';
					$favmenuobj->addClass('menuitem', 'menuitem_hover', 'submenu');
					$favmenuobj->printMenu();
				}
			?>
			</div>
		</td>
		<td class="center">
			<?php 
				$menu = $menubar->getHelpMenu();
				$menu->labelpos = 'right';
				if($menu->link != "") {
					$menu->printMenu();
				}
			?>
		</td>
		</tr>
		</table>
	</div>
