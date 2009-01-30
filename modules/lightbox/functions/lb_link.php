<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PHPGedView Development Team
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

	// Set Link
	/**
	 * Generate link flyout menu
	 *
	 * @param string $mediaid
	 */
//	function print_link_menu2($mediaid) {
        $mediaid=$media["XREF"];
		global $pgv_lang, $TEXT_DIRECTION;

		$classSuffix = "";
		if ($TEXT_DIRECTION=="rtl") $classSuffix = "_rtl";

		// main link displayed on page
		$menu = array();
		if ($LB_ML_THUMB_LINKS == "icon" || $LB_ML_THUMB_LINKS == "both") {
			$menu["icon"] = "modules/lightbox/images/image_link.gif";
		}
		if ($LB_ML_THUMB_LINKS == "both") {
			$menu["label"] = $pgv_lang["set_link"];
			$menu["labelpos"] = "down";
		}
		if ($LB_ML_THUMB_LINKS == "text") {
			$menu["label"] = $pgv_lang["set_link"];
		}

		$menu["link"] = "#";
		$menu["onclick"] = "return ilinkitem('$mediaid','person')";
//		$menu["class"] = "thememenuitem";
		$menu["class"] = "";
		$menu["hoverclass"] = "";
		$menu["submenuclass"] = "submenu";
		$menu["flyout"] = "left";
		$menu["items"] = array();

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_person"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','person')";
		$menu["items"][] = $submenu;

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_family"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','family')";
		$menu["items"][] = $submenu;

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_source"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','source')";
		$menu["items"][] = $submenu;

		print_menu($menu);
?>
<script language="JavaScript" type="text/javascript">
  function ilinkitem(mediaid, type) {
		window.open('inverselink.php?mediaid='+mediaid+'&linkto='+type+'&'+sessionname+'='+sessionid, '_blank', 'top=50,left=50,width=400,height=300,resizable=1,scrollbars=1');
		return false;
	}
</script>
<?php

//	}

	// Only set link on media that is in the DB
//	if ($media["XREF"] != "") {
//		print_link_menu($media2["XREF"]);
//	}

?>
