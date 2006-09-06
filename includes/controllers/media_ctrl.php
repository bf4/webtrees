<?php
/**
 * Controller for the Media Menu
 * Extends the IndividualController class and overrides the getEditMenu() function
 * Menu options are changed to apply to a media object instead of an individual
 *
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
 *
 * @package PhpGedView
 * @subpackage Charts
 * @version $Id: ancestry_ctrl.php 87 2006-06-13 19:23:14Z yalnifj $
 */
require_once('includes/controllers/individual_ctrl.php');
require_once("includes/serviceclient_class.php");
require_once("includes/person_class.php");
require_once("includes/media_class.php");

class MediaControllerRoot extends IndividualController{

	/**
	 * get the edit menu
	 * @return Menu
	 */
	function &getEditMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $TOTAL_NAMES;
		global $NAME_LINENUM, $SEX_LINENUM, $pgv_lang, $pgv_changes, $USE_QUICK_UPDATE;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl";
		else $ff="";
		//-- main edit menu
		$menu = new Menu($pgv_lang["edit"]);
		//if ($USE_QUICK_UPDATE) $link = "return quickEdit('".$this->pid."');";
		//else $link = "return edit_raw('".$this->pid."');";
		$link = "return edit_raw('".$this->pid."');";
		$menu->addOnclick($link);
		if (!empty($PGV_IMAGES["edit_indi"]["small"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["edit_indi"]["small"]);
		$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff");
		//-- quickedit sub menu
		//if ($USE_QUICK_UPDATE) {
		//	$submenu = new Menu($pgv_lang["quick_update_title"]);
		//	$submenu->addOnclick("return quickEdit('".$this->pid."');");
		//	$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		//	$menu->addSubmenu($submenu);
		//}
		if (userCanEdit($this->uname)) {
			//- plain edit option
			$submenu = new Menu($pgv_lang["edit"]);
			$click_link = "";
			$click_link .= "window.open('addmedia.php?action=";
			$click_link .= "editmedia&amp;pid=".$this->pid;
			$click_link .= "&amp;linktoid=new";
			$click_link .= "', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1')";	
			$submenu->addOnclick($click_link);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//- end plain edit option
			
			$submenu = new Menu($pgv_lang["edit_raw"]);
			$submenu->addOnclick("return edit_raw('".$this->pid."');");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			if (count($this->indi->getSpouseFamilyIds())>0) {
				$submenu = new Menu($pgv_lang["reorder_families"]);
				$submenu->addOnclick("return reorder_families('".$this->pid."');");
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
			//- remove object option
			$submenu = new Menu($pgv_lang["remove_object"]);
			$submenu->addLink("media.php?action=removeobject&amp;xref=".$this->pid);
			$submenu->addOnclick("return confirm('".$pgv_lang["confirm_remove_object"]."')");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//$menu->addSeperator();
			//if ($TOTAL_NAMES<2) {
			//	$submenu = new Menu($pgv_lang["edit_name"]);
			//	$submenu->addOnclick("return edit_name('".$this->pid."', $NAME_LINENUM);");
			//	$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			//	$menu->addSubmenu($submenu);
			//}
			//$submenu = new Menu($pgv_lang["add_name"]);
			//$submenu->addOnclick("return add_name('".$this->pid."');");
			//$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			//$menu->addSubmenu($submenu);
			//if ($this->SEX_COUNT<2) {
			//	$submenu = new Menu($pgv_lang["edit"]." ".$pgv_lang["sex"]);
			//	if ($SEX_LINENUM=="new") $submenu->addOnclick("return add_new_record('".$this->pid."', 'SEX');");
			//	else $submenu->addOnclick("return edit_record('".$this->pid."', $SEX_LINENUM);");
			//	$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			//	$menu->addSubmenu($submenu);
			//}
		}
		if (isset($pgv_changes[$this->pid."_".$GEDCOM])) {
			$menu->addSeperator();
			if ($this->show_changes=="no") {
				$label = $pgv_lang["show_changes"];
				$link = "individual.php?pid=".$this->pid."&amp;show_changes=yes";
			}
			else {
				$label = $pgv_lang["hide_changes"];
				$link = "individual.php?pid=".$this->pid."&amp;show_changes=no";
			}
			$submenu = new Menu($label, $link);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);

			if (userCanAccept($this->uname)) {
				$submenu = new Menu($pgv_lang["undo_all"], "individual.php?pid=".$this->pid."&amp;action=undo");
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				$submenu = new Menu($pgv_lang["accept_all"], "individual.php?pid=".$this->pid."&amp;action=accept");
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}
}
// -- end of class
//-- load a user extended class if one exists
if (file_exists('includes/controllers/media_ctrl_user.php'))
{
	include_once 'includes/controllers/media_ctrl_user.php';
}
else
{
	class MediaController extends MediaControllerRoot
	{
	}
}
$controller = new MediaController();
$controller->init();

?>