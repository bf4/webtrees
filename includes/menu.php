<?php
/**
 * System for generating menus.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008 John Finlay and Others.  All rights reserved.
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
 * @version $Id$
 */
if (stristr($_SERVER["SCRIPT_NAME"], "/".basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

class Menu
{
	var $seperator = false;
	var $label = ' ';
	var $labelpos = 'right';
	var $link = '#';
	var $onclick = null;
	var $icon = null;
	var $hovericon = null;
	var $flyout = 'down';
	var $class = '';
	var $hoverclass = '';
	var $submenuclass = '';
	var $accesskey = null;
	var $target = null;
	var $parentmenu = null;
	var $submenus;

	/**
	 * Constructor for the menu class
	 * @param string $label 	the label for the menu item (usually a pgv_lang variable)
	 * @param string $link		The link that the user should be taken to when clicking on the menuitem
	 * @param string $pos 	The position of the label relative to the icon (right, left, top, bottom)
	 * @param string $flyout	The direction where any submenus should appear relative to the menu item (right, down)
	 */
	function Menu($label=' ', $link='#', $pos='right', $flyout='down')
	{
		$this->submenus = array();
		$this->addLink($link);
		$this->addLabel($label, $pos);
		$this->addFlyout($flyout);
	}

	function isSeperator()
	{
		$this->seperator = true;
	}

	function addLabel($label=' ', $pos='right')
	{
		if ($label) $this->label = $label;
		$this->labelpos = $pos;
	}

	function addLink($link='#')
	{
		$this->link = $link;
	}

	function addOnclick($onclick)
	{
		$this->onclick = $onclick;
	}

	function addIcon($icon, $hovericon=null)
	{
		if (file_exists($icon)) $this->icon = $icon;
		else $this->icon = null;
		if (file_exists($hovericon)) $this->hovericon = $hovericon;
		else $this->hovericon = null;
	}

	function addFlyout($flyout='down')
	{
		$this->flyout = $flyout;
	}

	function addClass($class, $hoverclass='', $submenuclass='')
	{
		$this->class = $class;
		$this->hoverclass = $hoverclass;
		$this->submenuclass = $submenuclass;
	}

	function addAccesskey($accesskey)
	{
		$this->accesskey = $accesskey;
	}

	function addTarget($target)
	{
		$this->target = $target;
	}

	function addSubMenu($obj)
	{
		$this->submenus[] = $obj;
	}

	function addSeperator() {
		$submenu = new Menu();
		$submenu->isSeperator();
		$this->submenus[] = $submenu;
	}

	// Get the menu as a simple list - for accessible interfaces, search engines and CSS menus
	function getMenuAsList() {
		if ($this->seperator) {
			return '<div class="hr"></div>'; // The <hr/> tag is difficult to style
		}
		if ($this->link) {
			if ($this->link=='#') {
				$html=$this->label;
			} else {
				$html='<a href="'.$this->link.'">'.$this->label.'</a>';
			}
		} else {
			return '';
		}
		if ($this->submenus) {
			$html.='<ul>';
			foreach ($this->submenus as $submenu) {
				$html.=$submenu->getMenuAsList();
			}
			$html.='</ul>';
		}

		return '<li>'.$html.'</li>';
	}

	// Get the menu as a dropdown form element
	function getMenuAsDropdown() {
		if ($this->seperator || !$this->link && !$this->submenus) {
			return '';
		}
		if ($this->submenus) {
			$options='<option value="'.$this->link.'">'.$this->label.'</option>';
			foreach ($this->submenus as $submenu) {
				$options.=$submenu->getMenuAsDropdown();
			}
			return '<select onchange="document.location=this.value;">'.$options.'</select>';
		} else {
			return '<option value="'.$this->link.'">'.$this->label.'</option>';
		}
	}

	// Get the menu as a list of icons
	function getMenuAsIcons() {
		if ($this->seperator || !$this->link && !$this->submenus) {
			return '';
		}
		$icons=array();
		if ($this->icon) {
			$icons[]='<a href="'.$this->link.'"><img onmouseover="this.className=\''.$this->hoverclass.'\'" onmouseout="this.className=\''.$this->class.'\'" class="'.$this->class.'" src="'.$this->icon.'" alt="'.$this->label.'" title="'.$this->label.'"></a>';
		}
		if ($this->submenus) {
			foreach ($this->submenus as $submenu) {
				$icons[]=$submenu->getMenuAsIcons();
			}
		}
		return join(' ', $icons);
	}

	function getMenu()
	{
		global
			$menucount,
			$TEXT_DIRECTION,
			$PGV_IMAGE_DIR,
			$PGV_IMAGES
		;

		if (!isset($menucount))
		{
			$menucount = 0;
		}
		else
		{
			$menucount++;
		}
		$id = $menucount.rand();
		if ($this->seperator)
		{
			$output = "<div id=\"menu{$id}\" class=\"menu_seperator\" style=\"clear: both;\">"
			."<img src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['hline']['other']}\" style=\"width:8em;height:3px\" alt=\"\" /></div>";
			return $output;
		}
		$c = count($this->submenus);
		$output = "<div id=\"menu{$id}\" style=\"clear: both;\" class=\"{$this->class}\">\n";
		if ($this->link=="#") $this->link = "javascript:;";
		$link = "<a href=\"{$this->link}\" onmouseover=\"";
		if ($c >= 0)
		{
			$link .= "show_submenu('menu{$id}_subs', 'menu{$id}', '{$this->flyout}'); ";
		}
		if ($this->hoverclass !== null)
		{
			$link .= "change_class('menu{$id}', '{$this->hoverclass}'); ";
		}
		if ($this->hovericon !== null)
		{
			$link .= "change_icon('menu{$id}_icon', '{$this->hovericon}'); ";
		}
		$link .= '" onmouseout="';
		if ($c >= 0)
		{
			$link .= "timeout_submenu('menu{$id}_subs'); ";
		}
		if ($this->hoverclass !== null)
		{
			$link .= "change_class('menu{$id}', '{$this->class}'); ";
		}
		if ($this->hovericon !== null)
		{
			$link .= "change_icon('menu{$id}_icon', '{$this->icon}'); ";
		}
		if ($this->onclick !== null)
		{
			$link .= "\" onclick=\"{$this->onclick}";
		}
		if ($this->accesskey !== null)
		{
			$link .= '" accesskey="'.$this->accesskey;
		}
		if ($this->target !== null)
		{
			$link .= '" target="'.$this->target;
		}
		$link .= "\">";
		if ($this->icon !== null)
		{
			$MenuIcon = "<img id=\"menu{$id}_icon\" src=\"{$this->icon}\" class=\"icon\" alt=\"".preg_replace("/\"/", '', $this->label).'" title="'.preg_replace("/\"/", '', $this->label).'" '." />";
			switch ($this->labelpos) {
			case "right":
				$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
				$output .= "<tr>";
				$output .= "<td valign=\"middle\">";
				$output .= $link;
				$output .= $MenuIcon;
				$output .= "</a>";
				$output .= "</td>";
				$output .= "<td align=\"";
				if ($TEXT_DIRECTION=="rtl") $output .= "right";
				else $output .= "left";
				$output .= "\" valign=\"middle\" style=\"white-space: nowrap;\">";
				$output .= $link;
				$output .= $this->label;
				$output .= "</a></td>";
				$output .= "</tr></table>";
				break;
			case "left":
				$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
				$output .= "<tr>";
				$output .= "<td align=\"";
				if ($TEXT_DIRECTION=="rtl") $output .= "left";
				else $output .= "right";
				$output .= "\" valign=\"middle\" style=\"white-space: nowrap;\">";
				$output .= $link;
				$output .= $this->label;
				$output .= "</a></td>";
				$output .= "<td valign=\"middle\">";
				$output .= $link;
				$output .= $MenuIcon;
				$output .= "</a>";
				$output .= "</td>";
				$output .= "</tr></table>";
				break;
			case "down":
				$output .= $link;
				$output .= $MenuIcon;
				$output .= "<br />";
				$output .= $this->label;
				$output .= "</a>";
				break;
			case "up":
				$output .= $link;
				$output .= $this->label;
				$output .= "<br />";
				$output .= $MenuIcon;
				$output .= "</a>";
				break;
			default:
				$output .= $link;
				$output .= $MenuIcon;
				$output .= "</a>";
			}
		}
		else
		{
			$output .= $link;
			$output .= $this->label;
			$output .= "</a>";
		}

		if ($c > 0)
		{
			$submenuid = "menu{$id}_subs";
			if ($TEXT_DIRECTION == 'ltr')
			{
				$output .= '<div style="text-align: left;">';
			}
			else
			{
				$output .= '<div style="text-align: right;">';
			}
			$output .= "<div id=\"menu{$id}_subs\" class=\"{$this->submenuclass}\" style=\"position: absolute; visibility: hidden; z-index: 100;";
			if ($this->flyout == 'right')
			{
				if ($TEXT_DIRECTION == 'ltr')
				{
					$output .= ' left: 80px;';
				}
				else
				{
					$output .= ' right: 50px;';
				}
			}
			$output .= "\" onmouseover=\"show_submenu('{$this->parentmenu}'); show_submenu('{$submenuid}');\" onmouseout=\"timeout_submenu('menu{$id}_subs');\">\n";
			foreach($this->submenus as $submenu)
			{
				$submenu->parentmenu = $submenuid;
				$output .= $submenu->getMenu();
			}
			$output .= "</div></div>\n";
		}
		$output .= "</div>\n";
		return $output;
	}

	function printMenu()
	{
		print $this->getMenu();
	}
}

class MenuBar
{

	/**
	 * get the home menu
	 * @return Menu 	the menu item
	 */
	function &getHomeMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $HOME_SITE_URL, $HOME_SITE_TEXT, $pgv_lang;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		//-- main home menu item
		$menu = new Menu($HOME_SITE_TEXT, $HOME_SITE_URL, "down");
		if (!empty($PGV_IMAGES["home"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["home"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		return $menu;
	}

	/**
	 * get the menu with links to the gedcom portals
	 * @return Menu 	the menu item
	 */
	function &getGedcomMenu() {
		global $GEDCOMS, $ALLOW_CHANGE_GEDCOM;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		//-- main menu
		$menu = new Menu($pgv_lang["welcome_page"], "index.php?ctype=gedcom", "down");
		if (!empty($PGV_IMAGES["gedcom"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		$menu->addAccesskey($pgv_lang["accesskey_home_page"]);
		//-- gedcom list
		if ($ALLOW_CHANGE_GEDCOM && count($GEDCOMS)>1) {
			foreach($GEDCOMS as $ged=>$gedarray) {
				$submenu = new Menu(PrintReady($gedarray["title"]), "index.php?ctype=gedcom&amp;ged=$ged");
				if (!empty($PGV_IMAGES["gedcom"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}

		//-- Welcome Menu customization
		$filename = "includes/extras/custom_welcome_menu.php";
		if (file_exists($filename)) {
			include $filename;
		}

		return $menu;
	}

	/**
	 * get the mygedview menu
	 * @return Menu 	the menu item
	 */
	function &getMygedviewMenu() {
		global $GEDCOMS, $MEDIA_DIRECTORY, $MULTI_MEDIA;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $PEDIGREE_FULL_DETAILS, $PEDIGREE_LAYOUT;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
		$showLayout = ($PEDIGREE_LAYOUT) ? 1 : 0;

		$username = PGV_USER_NAME;
		if (!$username) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
		}
		
		//-- main menu
		$menu = new Menu($pgv_lang["mygedview"], "index.php?ctype=user", "down");
		if (!empty($PGV_IMAGES["mygedview"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["large"]);
		else if (!empty($PGV_IMAGES["gedcom"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		$menu->addAccesskey($pgv_lang["accesskey_home_page"]);

		//-- mygedview submenu
		$submenu = new Menu($pgv_lang["mgv"], "index.php?ctype=user");
		if (!empty($PGV_IMAGES["mygedview"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		//-- editaccount submenu
		if (get_user_setting($username, 'editaccount')) {
			$submenu = new Menu($pgv_lang["editowndata"], "edituser.php");
			if (!empty($PGV_IMAGES["mygedview"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		if (PGV_USER_GEDCOM_ID) {
			//-- quick_update submenu
			$submenu = new Menu($pgv_lang["quick_update_title"], "#");
			$submenu->addOnclick("return quickEdit('".PGV_USER_GEDCOM_ID."');");
			if (!empty($PGV_IMAGES["indis"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- my_pedigree submenu
			$submenu = new Menu($pgv_lang["my_pedigree"], "pedigree.php?rootid=".PGV_USER_GEDCOM_ID."&show_full={$showFull}&talloffset={$showLayout}");
			if (!empty($PGV_IMAGES["pedigree"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]);
			//$submenu->addIcon($PGV_IMAGE_DIR."/small/pedigree.gif");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- my_indi submenu
			$submenu = new Menu($pgv_lang["my_indi"], "individual.php?pid=".PGV_USER_GEDCOM_ID);
			if (!empty($PGV_IMAGES["indis"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		if (PGV_USER_GEDCOM_ADMIN){
			$menu->addSeperator();
			//-- admin submenu
			$submenu = new Menu($pgv_lang["admin"], "admin.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- manage_gedcoms submenu
			$submenu = new Menu($pgv_lang["manage_gedcoms"], "editgedcoms.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- user_admin submenu
			$submenu = new Menu($pgv_lang["user_admin"], "useradmin.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- manage_media submenu
			 if (is_writable($MEDIA_DIRECTORY) && $MULTI_MEDIA) {
				$submenu = new Menu($pgv_lang["manage_media"], "media.php");
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}
		if (PGV_USER_CAN_EDIT) {
			//-- upload_media submenu
			 if (is_writable($MEDIA_DIRECTORY) && $MULTI_MEDIA) {
				$menu->addSeperator();
				$submenu = new Menu($pgv_lang["upload_media"], "uploadmedia.php");
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}

	/**
	 * get the menu for the charts
	 * @return Menu 	the menu item
	 */
	function &getChartsMenu($rootid='',$myid='') {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $SEARCH_SPIDER;
		global $PEDIGREE_FULL_DETAILS, $PEDIGREE_LAYOUT;

		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
		}

		$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
		$showLayout = ($PEDIGREE_LAYOUT) ? 1 : 0;

		//-- main charts menu item
		$link = "pedigree.php?&show_full={$showFull}&talloffset={$showLayout}";
		if ($rootid) {
			$link .= "&rootid={$rootid}";
			$menu = new Menu($pgv_lang["charts"], $link);
			if (!empty($PGV_IMAGES["pedigree"]["small"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]);
			$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff");
		}
		else {
			// top menubar
			$menu = new Menu($pgv_lang["charts"], $link, "down");
			if (!empty($PGV_IMAGES["pedigree"]["large"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["large"]);
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		}
		
		// Build a sortable list of submenu items and then sort it in localized name order
		$menuList = array();
		$menuList["pedigree"] = $pgv_lang["pedigree_chart"];
		if (file_exists("descendancy.php")) $menuList["descendancy"] = $pgv_lang["descend_chart"];
		if (file_exists("ancestry.php")) $menuList["ancestry"] = $pgv_lang["ancestry_chart"];
		if (file_exists("compact.php")) $menuList["compact"] = $pgv_lang["compact_chart"];
		if (file_exists("fanchart.php") and function_exists("imagettftext")) $menuList["fanchart"] = $pgv_lang["fan_chart"];
		if (file_exists("hourglass.php")) $menuList["hourglass"] = $pgv_lang["hourglass_chart"];
		if (file_exists("familybook.php")) $menuList["familybook"] = $pgv_lang["familybook_chart"];
		if (file_exists("timeline.php")) $menuList["timeline"] = $pgv_lang["timeline_chart"];
		if (file_exists("lifespan.php")) $menuList["lifespan"] = $pgv_lang["lifespan_chart"];
		if (file_exists("relationship.php")) $menuList["relationship"] = $pgv_lang["relationship_chart"];
		if (file_exists("statistics.php") && file_exists("jpgraph")) $menuList["statistics"] = $pgv_lang["statistics"];
		asort($menuList);

		// Produce the submenus in localized name order
		foreach($menuList as $menuType => $menuName) {
			switch ($menuType) {
			case "pedigree":
				//-- pedigree
				$link = "pedigree.php?show_full={$showFull}&talloffset={$showLayout}";
				if ($rootid) $link .= "&rootid={$rootid}";
				$submenu = new Menu($pgv_lang["pedigree_chart"], $link);
				if (!empty($PGV_IMAGES["pedigree"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "descendancy":
				//-- descendancy
				$link = "descendancy.php";
				if ($rootid) $link .= "?pid={$rootid}&show_full={$showFull}";
				$submenu = new Menu($pgv_lang["descend_chart"], $link);
				if (!empty($PGV_IMAGES["descendant"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["descendant"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "ancestry":
				//-- ancestry
				$link = "ancestry.php";
				if ($rootid) $link .= "?rootid={$rootid}&show_full={$showFull}";
				$submenu = new Menu($pgv_lang["ancestry_chart"], $link);
				if (!empty($PGV_IMAGES["ancestry"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["ancestry"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "compact":
				//-- compact
				$link = "compact.php";
				if ($rootid) $link .= "?rootid=".$rootid;
				$submenu = new Menu($pgv_lang["compact_chart"], $link);
				if (!empty($PGV_IMAGES["ancestry"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["ancestry"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "fanchart":
				//-- fan chart
				$link = "fanchart.php";
				if ($rootid) $link .= "?rootid=".$rootid;
				$submenu = new Menu($pgv_lang["fan_chart"], $link);
				if (!empty($PGV_IMAGES["fanchart"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["fanchart"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "hourglass":
				//-- hourglass
				$link = "hourglass.php";
				if ($rootid) $link .= "?pid={$rootid}&show_full={$showFull}";
				$submenu = new Menu($pgv_lang["hourglass_chart"], $link);
				if (!empty($PGV_IMAGES["hourglass"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["hourglass"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "familybook":
				//-- familybook
				$link = "familybook.php";
				if ($rootid) $link .= "?pid={$rootid}&show_full={$showFull}";
				$submenu = new Menu($pgv_lang["familybook_chart"], $link);
				if (!empty($PGV_IMAGES["fambook"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["fambook"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "timeline":
				//-- timeline
				$link = "timeline.php";
				if ($rootid) $link .= "?pids[]=".$rootid;
				$submenu = new Menu($pgv_lang["timeline_chart"], $link);
				if (!empty($PGV_IMAGES["timeline"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["timeline"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "lifespan":
				//-- lifespan
				$link = "lifespan.php";
				if ($rootid) $link .= "?pids[]=".$rootid."&amp;addFamily=1";
				$submenu = new Menu($pgv_lang["lifespan_chart"], $link);
				if (!empty($PGV_IMAGES["timeline"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["timeline"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "relationship":
				//-- relationship
				$pids[] = $myid;
				if ($rootid && empty($myid)) {
					if (PGV_USER_ID) {
						$pids[] = PGV_USER_GEDCOM_ID;
						$pids[] = PGV_USER_ROOT_ID;
					}
				}
				if ($rootid) {
					foreach (getUserFavorites(PGV_USER_NAME) as $key=>$favorite) {
						$pid = $favorite["gid"];
						if (displayDetailsById($pid, $favorite["type"])) {
							if ($favorite["type"]=="INDI" && $favorite["file"]==$GEDCOM) $pids[]=$pid;
						}
					}
				}
				$pids = array_unique($pids);
				foreach ($pids as $key=>$pid) {
					if (($pid and $pid!=$rootid) or empty($rootid)) {
						$link = "relationship.php";
						if ($rootid) {
							$link .= "?pid1=".$pid."&amp;pid2=".$rootid;
							$label = $pgv_lang["relationship_chart"].": ".PrintReady(get_person_name($pid));
							$submenu = new Menu($label, $link);
						} else {
							$submenu = new Menu($pgv_lang["relationship_chart"], $link);
						}
						if (!empty($PGV_IMAGES["relationship"]["small"]))
							$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["relationship"]["small"]);
						$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
						$menu->addSubmenu($submenu);
					}
				}
				break;

			case "statistics":
				//-- statistics plot
				$submenu = new Menu($pgv_lang["statistics"], "statistics.php");
				if (!empty($PGV_IMAGES["statistic"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["statistic"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			}
		}
		return $menu;
	}

	/**
	 * get the menu for the lists
	 * @return Menu 	the menu item
	 */
	function &getListsMenu($surname="") {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $SHOW_SOURCES, $MULTI_MEDIA, $SEARCH_SPIDER;
		global $GEDCOMS, $ALLOW_CHANGE_GEDCOM, $DEFAULT_GEDCOM;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		if (!empty($SEARCH_SPIDER)) { // Only want the indi list for search engines.
			//-- main lists menu item
			$link = "indilist.php?ged=$GEDCOM";
			if ($surname) {
				$link .= "&amp;surname=".$surname;
				$menu = new Menu($pgv_lang["lists"], $link);
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
			}
			else {
				$menu = new Menu($pgv_lang["lists"], $link, "down");
				if (!empty($PGV_IMAGES["indis"]["large"]))
					$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["large"]);
				$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
			}

			//-- gedcom list
			if ($ALLOW_CHANGE_GEDCOM && count($GEDCOMS)>1) {
				foreach($GEDCOMS as $ged=>$gedarray) {
					$submenu = new Menu(($pgv_lang["individual_list"]." - ".PrintReady($gedarray["title"])), "indilist.php?ged=$ged");
					if (!empty($PGV_IMAGES["gedcom"]["small"]))
						$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
					$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
					$menu->addSubmenu($submenu);
				}
			}
			return $menu;
		}
		//-- main lists menu item
		$link = "indilist.php";
		if ($surname) {
			$link .= "?surname=".$surname;
			$menu = new Menu($pgv_lang["lists"], $link);
			if (!empty($PGV_IMAGES["indis"]["small"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
			$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff");
		}
		else {
			$menu = new Menu($pgv_lang["lists"], $link, "down");
			if (!empty($PGV_IMAGES["indis"]["large"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["large"]);
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		}
		
		// Build a sortable list of submenu items and then sort it in localized name order
		$menuList = array();
		$menuList["individual"] = $pgv_lang["individual_list"];
		if (file_exists("famlist.php")) $menuList["family"] = $pgv_lang["family_list"];
		if (!$surname and file_exists("sourcelist.php") and $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) $menuList["source"] = $pgv_lang["source_list"];
		if (!$surname and file_exists("repolist.php")) $menuList["repository"] = $pgv_lang["repo_list"];
		if (!$surname and file_exists("placelist.php")) $menuList["places"] = $pgv_lang["place_list"];
		if (!$surname and file_exists("medialist.php") and $MULTI_MEDIA) $menuList["media"] = $pgv_lang["media_list"];
		// if (file_exists("patriarchlist.php")) $menuList["patriarch"] = $pgv_lang["patriarch_list"];
		// if (!$surname and file_exists("aliveinyear.php")) $menuList["aliveinyear"] = $pgv_lang["alive_in_year"];
		asort($menuList);

		// Produce the submenus in localized name order
		
		foreach($menuList as $menuType => $menuName) {
			switch ($menuType) {
			case "individual":
				//-- indi list sub menu
				$link = "indilist.php";
				if ($surname) $link .= "?surname=".$surname;
				$submenu = new Menu($pgv_lang["individual_list"], $link);
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "family":
				//-- famlist sub menu
				$link = "famlist.php";
				if ($surname) $link .= "?surname=".$surname;
				$submenu = new Menu($pgv_lang["family_list"], $link);
				if (!empty($PGV_IMAGES["cfamily"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["cfamily"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "source":
				//-- source
				$submenu = new Menu($pgv_lang["source_list"], "sourcelist.php");
				if (!empty($PGV_IMAGES["menu_source"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_source"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "repository":
				//-- repository
				$submenu = new Menu($pgv_lang["repo_list"], "repolist.php");
				if (!empty($PGV_IMAGES["menu_repository"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_repository"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "places":
				//-- places
				$submenu = new Menu($pgv_lang["place_list"], "placelist.php");
				if (!empty($PGV_IMAGES["place"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["place"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "media":
				//-- medialist
				$submenu = new Menu($pgv_lang["media_list"], "medialist.php");
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;
/*
			case "patriarch":
				//-- list most ancient parent of a family
				$link = "patriarchlist.php";
				if ($surname) $link .= "?surname=".$surname;
				$submenu = new Menu($pgv_lang["patriarch_list"], $link);
				if (!empty($PGV_IMAGES["patriarch"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["patriarch"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;

			case "aliveinyear":
				//-- aliveinyear
				$submenu = new Menu($pgv_lang["alive_in_year"], "aliveinyear.php");
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;
*/
			}
		}

		return $menu;
	}

	/**
	 * get the menu for the calendar
	 * @return Menu 	the menu item
	 */
	function &getCalendarMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists("calendar.php")) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
			}
		//-- main calendar menu item
		$menu = new Menu($pgv_lang["anniversary_calendar"], "calendar.php", "down");
		if (!empty($PGV_IMAGES["calendar"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		//-- viewday sub menu
		$submenu = new Menu($pgv_lang["viewday"], "calendar.php");
		if (!empty($PGV_IMAGES["calendar"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		//-- viewmonth sub menu
		$submenu = new Menu($pgv_lang["viewmonth"], "calendar.php?action=calendar");
		if (!empty($PGV_IMAGES["calendar"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		//-- viewyear sub menu
		$submenu = new Menu($pgv_lang["viewyear"], "calendar.php?action=year");
		if (!empty($PGV_IMAGES["calendar"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		return $menu;
	}

	/**
	 * get the reports menu
	 * @return Menu 	the menu item
	 */
	function &getReportsMenu($pid="", $famid="") {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOMS, $GEDCOM, $pgv_lang;
		global $LANGUAGE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE, $PRIV_HIDE, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists("reportengine.php")) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
			}

		//-- main reports menu item
		if ($pid or $famid) {
			$menu = new Menu($pgv_lang["reports"], "#");
			if (!empty($PGV_IMAGES["reports"]["small"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["reports"]["small"]);
			$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff");
		}
		else {
			// top menubar
			$menu = new Menu($pgv_lang["reports"], "reportengine.php", "down");
			if (!empty($PGV_IMAGES["reports"]["large"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["reports"]["large"]);
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		}

		// Build a list of reports and sort that list into localized title order
		$reports = get_report_list();
		$menuList = array();
		foreach ($reports as $file=>$report) {
			if (!empty($report["title"][$LANGUAGE])) $label = $report["title"][$LANGUAGE];
			else $label = implode("", $report["title"]);
			$menuList[$file] = trim($label);
		}
		asort($menuList);

		// Produce those submenus in localized name order

		//print_r($reports);
		$username = PGV_USER_NAME;
		foreach($menuList as $file=>$label) {
			$report = $reports[$file];
			if (!isset($report["access"])) $report["access"] = $PRIV_PUBLIC;
			if ($report["access"]>=PGV_USER_ACCESS_LEVEL) {
				// indi report
				if ($pid) $submenu = new Menu($label, "reportengine.php?action=setup&amp;report=".$report["file"]."&amp;pid=".$pid);
				// family report
				else if ($famid) $submenu = new Menu($label, "reportengine.php?action=setup&amp;report=".$report["file"]."&amp;famid=".$famid);
				// default
				else $submenu = new Menu($label, "reportengine.php?action=setup&amp;report=".$report["file"]);
				if (isset($PGV_IMAGES["reports"]["small"]) and isset($PGV_IMAGES[$report["icon"]]["small"])) $iconfile=$PGV_IMAGE_DIR."/".$PGV_IMAGES[$report["icon"]]["small"];
				if (isset($iconfile) && file_exists($iconfile)) $submenu->addIcon($iconfile);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				// indi report
				if ($pid and $report["icon"]!="sfamily" and $report["icon"]!="place") $menu->addSubmenu($submenu);
				// family report
				else if ($famid and $report["icon"]=="sfamily") $menu->addSubmenu($submenu);
				// default
				else if (empty($pid) and empty($famid)) $menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}

	/**
	 * get the clipping menu
	 * @return Menu 	the menu item
	 */
	function &getClippingsMenu() {
		global $ENABLE_CLIPPINGS_CART;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists("clippings.php")) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
		}
		if ($ENABLE_CLIPPINGS_CART <PGV_USER_ACCESS_LEVEL) return null;
		//-- main clippings menu item
		$menu = new Menu($pgv_lang["clippings_cart"], "clippings.php", "down");
		if (!empty($PGV_IMAGES["clippings"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		return $menu;
	}

	/**
	 * get the optional site-specific menu
	 * @return Menu 	the menu item
	 */
	function &getOptionalMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!file_exists("includes/extras/optional_menu.php") || !empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
		}
		require "includes/extras/optional_menu.php";
		return $menu;
	}

	/**
	 * get the print_preview menu
	 * @return Menu 	the menu item
	 */
	function &getPreviewMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $SCRIPT_NAME, $QUERY_STRING, $pgv_lang;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
			}
		//-- main print_preview menu item
		$menu = new Menu($pgv_lang["print_preview"], $SCRIPT_NAME.normalize_query_string($QUERY_STRING."&amp;view=preview"), "down");
		if (!empty($PGV_IMAGES["printer"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["printer"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		return $menu;
	}

	/**
	 * get the search menu
	 * @return Menu 	the menu item
	 */
	function &getSearchMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang;
		global $SHOW_MULTISITE_SEARCH, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists("search.php")) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
			}
		//-- main search menu item
		$menu = new Menu($pgv_lang["search"], "search.php", "down");
		if (!empty($PGV_IMAGES["search"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		//-- search_general sub menu
		$submenu = new Menu($pgv_lang["search_general"], "search.php?action=general");
		if (!empty($PGV_IMAGES["search"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		//-- search_soundex sub menu
		$submenu = new Menu($pgv_lang["search_soundex"], "search.php?action=soundex");
		if (!empty($PGV_IMAGES["search"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		//-- search_replace sub menu
		if(PGV_USER_CAN_EDIT)
		{
		$submenu = new Menu($pgv_lang["search_replace"], "search.php?action=replace");
		if (!empty($PGV_IMAGES["search"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		}

		//-- search_multisite sub menu
		if ($SHOW_MULTISITE_SEARCH >= PGV_USER_ACCESS_LEVEL) {
			$sitelist = get_server_list();
			if (count($sitelist)>0) {
				$submenu = new Menu($pgv_lang["multi_site_search"], "search.php?action=multisite");
				if (!empty($PGV_IMAGES["search"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}

	/**
	 * get an array of module menu objects
	 * @return array
	 */
	function getModuleMenus() {
		$menus = array();
		if (!file_exists("modules")) return $menus;
		$d = dir("modules");
		while (false !== ($entry = $d->read())) {
			if ($entry{0}!="." && $entry!="CVS" && is_dir("modules/$entry")) {
				if (file_exists("modules/$entry/menu.php")) {
					include_once("modules/$entry/menu.php");
					$menu_class = $entry."_ModuleMenu";
					$obj = new $menu_class();
					if (method_exists($obj, "getMenu")) {
						$menu = $obj->getMenu();
						if (is_object($menu)) $menus[] = $menu;
					}
				}
			}
		}
		$d->close();

		return $menus;
	}

	/**
	 * get the help menu
	 * @return Menu 	the menu item
	 */
	function &getHelpMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $SEARCH_SPIDER;
		global $SHOW_CONTEXT_HELP, $SCRIPT_NAME, $QUERY_STRING, $helpindex, $action;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			$menu->print_menu = null;
			return $menu;
			}
		//-- main help menu item
		$menu = new Menu($pgv_lang["page_help"], "#", "down");
		if (!empty($PGV_IMAGES["help"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["help"]["large"]);
		if (empty($helpindex))
			$menu->addOnclick("return helpPopup('help_".basename($SCRIPT_NAME)."&amp;action=".$action."');");
		else
			$menu->addOnclick("return helpPopup('".$helpindex."');");
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");

		//-- help_for_this_page sub menu
		$submenu = new Menu($pgv_lang["help_for_this_page"], "#");
		if (!empty($PGV_IMAGES["menu_help"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_help"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		if (empty($helpindex))
			$submenu->addOnclick("return helpPopup('help_".basename($SCRIPT_NAME)."&amp;action=".$action."');");
		else
			$submenu->addOnclick("return helpPopup('".$helpindex."');");
		$menu->addSubmenu($submenu);
		//-- help_contents sub menu
		$submenu = new Menu($pgv_lang["help_contents"], "#");
		if (!empty($PGV_IMAGES["menu_help"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_help"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$submenu->addOnclick("return helpPopup('help_contents_help');");
		$menu->addSubmenu($submenu);
		//-- faq sub menu
		if (file_exists("faq.php")) {
			$submenu = new Menu($pgv_lang["faq_list"], "faq.php");
			if (!empty($PGV_IMAGES["menu_help"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_help"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		//-- searchhelp sub menu
		if (file_exists("searchhelp.php")) {
			$submenu = new Menu($pgv_lang["hs_title"], "#");
			if (!empty($PGV_IMAGES["search"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$submenu->addOnclick("window.open('searchhelp.php', '_blank', 'top=50,left=10,width=600,height=500,scrollbars=1,resizable=1');");
			$menu->addSubmenu($submenu);
		}

		//-- add wiki links
		$menu->addSeperator();
		$submenu = new Menu($pgv_lang["wiki_main_page"], PGV_PHPGEDVIEW_WIKI.'/en/index.php?title=Main_Page" target="_blank');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);

		$submenu = new Menu($pgv_lang["wiki_users_guide"], PGV_PHPGEDVIEW_WIKI.'/en/index.php?title=Users_Guide" target="_blank');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);

		if (PGV_USER_GEDCOM_ADMIN) {
			$submenu = new Menu($pgv_lang["wiki_admin_guide"],  PGV_PHPGEDVIEW_WIKI.'/en/index.php?title=Administrators_Guide" target="_blank');
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}

		//-- add contact links to help menu
		$menu->addSeperator();
		$menuitems = contact_menus();
		foreach($menuitems as $menuitem) {
			$submenu = new Menu($menuitem["label"], $menuitem["link"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			if (!empty($menuitem["onclick"])) $submenu->addOnclick($menuitem["onclick"]);
			$menu->addSubmenu($submenu);
		}
		//-- add show/hide context_help
		$menu->addSeperator();
		if ($_SESSION["show_context_help"])
			$submenu = new Menu($pgv_lang["hide_context_help"], "$SCRIPT_NAME".normalize_query_string($QUERY_STRING."&amp;show_context_help=no"));
		else
			$submenu = new Menu($pgv_lang["show_context_help"], "$SCRIPT_NAME".normalize_query_string($QUERY_STRING."&amp;show_context_help=yes"));
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		return $menu;
	}
	
	/**
	 * get the menu with links change to each theme
	 * @return Menu 	the menu item
	 */
	function &getThemeMenu() {
		global $SEARCH_SPIDER, $ALLOW_THEME_DROPDOWN, $ALLOW_USER_THEMES, $THEME, $pgv_lang;

		$current=$THEME;
		$themes=get_theme_names();
		foreach ($themes as $theme) {
			if ($theme['dir']==get_user_setting(PGV_USER_ID, 'theme')) {
				$current=$theme['name'];
			}
		}

		if ($ALLOW_THEME_DROPDOWN && $ALLOW_USER_THEMES && !$SEARCH_SPIDER) {
			isset($_SERVER["QUERY_STRING"]) == true?$tqstring = "?".$_SERVER["QUERY_STRING"]:$tqstring = "";
			$frompage = $_SERVER["SCRIPT_NAME"].$tqstring;
			if(isset($_REQUEST['mod'])){
				if(!strstr("?", $frompage))
				{
						if(!strstr("%3F", $frompage)) ;
						else $frompage.="?";
				}
				if(!strstr("&mod",$frompage))$frompage.="&mod=".$_REQUEST['mod'];
			}
			$menu=new Menu($pgv_lang['change_theme']);
			$menu->addClass('thememenuitem', 'thememenuitem_hover', 'themesubmenu');
			$menu->print_menu = null;
			foreach ($themes as $theme) {
				$submenu=new Menu($theme['name'], 'themechange.php?frompage='.urlencode($frompage).'&amp;mytheme='.urlencode($theme['dir']));
				if ($theme['name']==$current) {
					$submenu->addClass('favsubmenuitem_selected', 'favsubmenuitem_hover');
				} else {
					$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
				}
				$menu->addSubMenu($submenu);
			}
			return $menu;
		} else {
			return new Menu('', '');
		}
	}
	/**
	 * get the menu with links to change language
	 * @return Menu 	the menu item
	 */
	function &getLanguageMenu() {
		global $ENABLE_MULTI_LANGUAGE, $LANGUAGE, $pgv_lang, $language_settings, $flagsfile, $QUERY_STRING, $SCRIPT_NAME, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;

		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		if (PGV_USER_ID) {
			$current=$LANGUAGE;
		} else {
			$current=get_user_setting(PGV_USER_ID, 'language');
		}

		if ($ENABLE_MULTI_LANGUAGE) {
			$menu=new Menu($pgv_lang['change_lang'], '#', 'down');
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");

			$menu->print_menu = null;
			foreach ($language_settings as $lang=>$language) {
				if ($language['pgv_lang_use']) {
					$submenu=new Menu($language['pgv_lang'], $SCRIPT_NAME.normalize_query_string($QUERY_STRING.'&amp;changelanguage=yes&amp;NEWLANGUAGE='.$lang));
					if ($lang==$LANGUAGE) {
						$submenu->addClass('activeflag', 'brightflag');
					} else {
						$submenu->addClass('dimflag', 'brightflag');
					}
					$submenu->addIcon($flagsfile[$lang]);
					$menu->addSubMenu($submenu);
				}
			}
			if (count($menu->submenus)>1) {
				return $menu;
			} else {
				return new Menu('', '');
			}
		} else {
			return new Menu('', '');
		}
	}
	/**
	 * get the menu with links to the user/gedcom favourites
	 * @return Menu 	the menu item
	 */
	function &getFavouritesMenu() {
		global $REQUIRE_AUTHENTICATION, $pgv_lang, $GEDCOM, $QUERY_STRING, $SCRIPT_NAME, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;
		global $controller; // Pages with a controller can be added to the favourites
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		if (PGV_USER_ID || !$REQUIRE_AUTHENTICATION) {
			$menu=new Menu($pgv_lang['favorites'], '#', 'down');
			if (!empty($PGV_IMAGES['gedcom']['large'])) {
				$menu->addIcon($PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedcom']['large']);
			}
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
			$menu->print_menu = null;
			// User favourites
			$userfavs=getUserFavorites(PGV_USER_ID);
			if ($userfavs || PGV_USER_ID) {
				$submenu=new Menu('<strong>'.$pgv_lang['my_favorites'].'</strong>');
				$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
				$menu->addSubMenu($submenu);
				if (PGV_USER_ID && isset($controller)) {
					$submenu=new Menu('<em>'.$pgv_lang['add_to_my_favorites'].'</em>', $SCRIPT_NAME.normalize_query_string($QUERY_STRING.'&amp;action=addfav'));
					$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
					$menu->addSubMenu($submenu);
				}
				foreach ($userfavs as $fav) {
					$OLD_GEDCOM=$GEDCOM;
					$GEDCOM=$fav['file'];
					switch($fav['type']) {
					case 'URL':
						$submenu=new Menu(PrintReady($fav['title']), $fav['url']);
						$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
						$menu->addSubMenu($submenu);
						break;
					case 'INDI':
					case 'FAM':
					case 'SOUR':
					case 'OBJE':
						if (displayDetailsById($fav['gid'], $fav['type'])) {
							$obj=GedcomRecord::getInstance($fav['gid']);
							if ($obj) {
								$submenu=new Menu(PrintReady($obj->getName()), $obj->getLinkUrl());
								$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
								$menu->addSubMenu($submenu);
							}
						}
						break;
					}
					$GEDCOM=$OLD_GEDCOM;
				}
			}
			// Gedcom favourites
			$gedfavs=getUserFavorites($GEDCOM);
			if ($gedfavs) {
				$submenu=new Menu('<strong>'.$pgv_lang['gedcom_favorites'].'</strong>');
				$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
				$menu->addSubMenu($submenu);
				foreach ($gedfavs as $fav) {
					$OLD_GEDCOM=$GEDCOM;
					$GEDCOM=$fav['file'];
					switch($fav['type']) {
					case 'URL':
						$submenu=new Menu(PrintReady($fav['title']), $fav['url']);
						$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
						$menu->addSubMenu($submenu);
						break;
					case 'INDI':
					case 'FAM':
					case 'SOUR':
					case 'OBJE':
						if (displayDetailsById($fav['gid'], $fav['type'])) {
							$obj=GedcomRecord::getInstance($fav['gid']);
							if ($obj) {
								$submenu=new Menu(PrintReady($obj->getName()), $obj->getLinkUrl());
								$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
								$menu->addSubMenu($submenu);
							}
						}
						break;
					}
					$GEDCOM=$OLD_GEDCOM;
				}
			}
		}
		return $menu;
	}
}

?>
