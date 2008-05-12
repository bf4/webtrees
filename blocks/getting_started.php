<?php
/**
 * Getting Started Block
 *
 * Prints out a helpful menu to help users get started
 * 
 * Admins
 *  - upload a gedcom
 *  - start entering data
 *  - Add a gedcom on the server
 *  - Manage GEDCOMs
 *  - Admin Users
 * Editors
 * Users
 * Visitors
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @version $Id: gedcom_block.php 2564 2008-02-17 10:13:23Z fisharebest $
 * @package PhpGedView
 * @subpackage Blocks
 */
$pgv_lang["install_step_8"] = "Get Started";

$PGV_BLOCKS["getting_started_block"]["name"]		= $pgv_lang["install_step_8"];
$PGV_BLOCKS["getting_started_block"]["descr"]		= "gedcom_descr";
$PGV_BLOCKS["getting_started_block"]["type"]		= "gedcom";
$PGV_BLOCKS["getting_started_block"]["canconfig"]	= false;
$PGV_BLOCKS["getting_started_block"]["config"]		= array("cache"=>5);

//-- function to print the gedcom block
function getting_started_block($block = true, $config="", $side, $index) {
	global $hits, $pgv_lang, $GEDCOM, $GEDCOMS, $SHOW_COUNTER;

	$id = "getting_started";
	$title = $pgv_lang["install_step_8"];
	$content = "<ul>";
	$content .= '<li style="padding: 5px;"><a class="imenu" href="uploadgedcom.php">Upload a GEDCOM</a></li>';
	$content .= '<li style="padding: 5px;"><a class="imenu" href="#">Start entering data</a></li>';
	$content .= '<li style="padding: 5px;"><a class="imenu" href="#">Add a GEDCOM from a file location</a></li>';
	$content .= '<li style="padding: 5px;"><a class="imenu" href="editgedcoms.php">Manage GEDCOMs</a></li>';
	$content .= '<li style="padding: 5px;"><a class="imenu" href="useradmin.php">Administer Users</a></li>';
	$content .= "</ul>\n";
	
	global $THEME_DIR;
	if ($block) include($THEME_DIR."/templates/block_small_temp.php");
	else include($THEME_DIR."/templates/block_main_temp.php");
}
?>
