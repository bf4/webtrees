<?php
/**
 * Toplinks for Colors theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (c) 2002 to 2009  John Finlay and others.  All rights reserved.
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
 * @subpackage Themes
 * @version $Id: toplinks.php 3.0709 2009-07-09 petersra $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$menubar = new MenuBar();
?>
<table id="toplinks">
	<tr>
		<td class="toplinks_left">
		<table align="<?php print $TEXT_DIRECTION=="ltr"?"left":"right" ?>">
			<tr> 
<?php
	$menu = $menubar->getHomeMenu();
	if (!isset($_COOKIE['pgv_embedded'])) {
		$_COOKIE['pgv_embedded'] = 0;
	}
	$menu = $menubar->getGedcomMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getMygedviewMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getChartsMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getListsMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getCalendarMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getReportsMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getSearchMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menu = $menubar->getOptionalMenu(); 
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
	$menus = $menubar->getModuleMenus();
		foreach($menus as $m=>$menu) { 
			if($menu->link != "") {
				echo "<td>";
				$menu->addLabel("", "none");
				$menu->printMenu();
				echo "</td>";
			}
		}
	$menu = $menubar->getHelpMenu();
	if($menu->link != "") {
		echo "<td>";
		$menu->addLabel("", "none");
		$menu->printMenu();
		echo "</td>";
	}
?>
			</tr>
		</table>
		</td>
<?php if(empty($SEARCH_SPIDER)) { ?>
		<td class="toplinks_right">
		<div align="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right" ?>" >
<?php print_lang_form(1); ?>
		</div>
		</td>
<?php } ?>
	</tr>
	</table>
<?php require './includes/accesskeyHeaders.php'; ?>
<?php require './sidebar.php'; ?>
<!-- close div for div id="header" -->
<div id="content">
