<?php
// Lightbox Album module for webtrees
//
// Display media Items using Lightbox
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2007  PHPGedView Development Team
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

	// Set Link
	/**
	 * Generate link flyout menu
	 *
	 * @param string $mediaid
	 */
// function print_link_menu2($mediaid) {
    $mediaid=$media["XREF"];
		global $TEXT_DIRECTION, $WT_IMAGES;
		if (!isset($WT_IMAGES['image_link'])) {
			$WT_IMAGES['image_link']='modules/lightbox/images/image_link.gif';
		}

		$classSuffix = "";
		if ($TEXT_DIRECTION=="rtl") $classSuffix = "_rtl";

		// main link displayed on page
		$menu = new WT_Menu();
		if ($LB_ML_THUMB_LINKS == "icon" || $LB_ML_THUMB_LINKS == "both") {
			$menu->addIcon('image_link');
		}
		if ($LB_ML_THUMB_LINKS == "both") {
			$menu->addLabel(WT_I18N::translate('Set link'), "down");
		}
		if ($LB_ML_THUMB_LINKS == "text") {
			$menu->addLabel(WT_I18N::translate('Set link'));
		}
		$menu->addOnclick("return ilinkitem('$mediaid','person')");
		$menu->addClass("", "", "submenu");
		$menu->addFlyout("left");

		$submenu = new WT_Menu(WT_I18N::translate('To Person'), "#");
		$submenu->addOnclick("return ilinkitem('$mediaid','person')");
		$submenu->addClass("submenuitem".$classSuffix, "submenuitem".$classSuffix);
		$menu->addSubMenu($submenu);

		$submenu = new WT_Menu(WT_I18N::translate('To Family'), "#");
		$submenu->addOnclick("return ilinkitem('$mediaid','family')");
		$submenu->addClass("submenuitem".$classSuffix, "submenuitem".$classSuffix);
		$menu->addSubMenu($submenu);

		$submenu = new WT_Menu(WT_I18N::translate('To Source'), "#");
		$submenu->addOnclick("return ilinkitem('$mediaid','source')");
		$submenu->addClass("submenuitem".$classSuffix, "submenuitem".$classSuffix);
		$menu->addSubMenu($submenu);

		echo $menu->getMenu();
?>
<script type="text/javascript">
  function ilinkitem(mediaid, type) {
		window.open('inverselink.php?mediaid='+mediaid+'&linkto='+type+'&'+sessionname+'='+sessionid, '_blank', 'top=50,left=50,width=400,height=300,resizable=1,scrollbars=1');
		return false;
	}
</script>
<?php

// }

	// Only set link on media that is in the DB
// if ($media["XREF"] != "") {
// print_link_menu($media2["XREF"]);
// }
