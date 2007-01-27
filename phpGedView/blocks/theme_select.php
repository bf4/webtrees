<?php
/**
 * Theme Select Block
 *
 * This block will print a form that allows the visitor to change the theme
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["print_block_theme_select"]["name"]			= $pgv_lang["theme_select_block"];
$PGV_BLOCKS["print_block_theme_select"]["descr"]		= "theme_select_descr";
$PGV_BLOCKS["print_block_theme_select"]["type"]			= "gedcom";
$PGV_BLOCKS["print_block_theme_select"]["canconfig"]	= false;
$PGV_BLOCKS["print_block_theme_select"]["config"]		= array("cache"=>-1);

function print_block_theme_select($style=0, $config="", $side, $index) {
	global $pgv_lang;
	print "<div id=\"theme_select\" class=\"block\">\n";
	print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
	print "<td class=\"blockh1\" >&nbsp;</td>";
	print "<td class=\"blockh2\" ><div class=\"blockhc\">";
	print "<b>".$pgv_lang["change_theme"]."</b>";
	print_help_link("change_theme", "qm");
	print "</div></td>";
	print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
	print "</table>";
	print "<div class=\"blockcontent\"><div class=\"center\"><br />";
			global $ALLOW_THEME_DROPDOWN, $ALLOW_USER_THEMES, $THEME_DIR, $pgv_lang, $themeformcount;

	if (!isset($themeformcount)) $themeformcount = 0;
	$themeformcount++;
	$uname = getUserName();
	$user = getUser($uname);
	isset($_SERVER["QUERY_STRING"]) == true?$tqstring = "?".$_SERVER["QUERY_STRING"]:$tqstring = "";
	$frompage = $_SERVER["SCRIPT_NAME"].$tqstring;
	
	$themes = get_theme_names();
	print "<div class=\"theme_form\">\n";
	$style=0;
	switch ($style) {
			case 0:
			print "<form action=\"themechange.php\" name=\"themeform$themeformcount\" method=\"post\">";
			print "<input type=\"hidden\" name=\"frompage\" value=\"".urlencode($frompage)."\" />";
			print "<select name=\"mytheme\" class=\"header_select\" onchange=\"document.themeform$themeformcount.submit();\">";
			print "<option value=\"\">".$pgv_lang["change_theme"]."</option>\n";
			foreach($themes as $indexval => $themedir) {
					print "<option value=\"".$themedir["dir"]."\"";
					if ($uname) {
							if ($themedir["dir"] == $user["theme"]) print " class=\"selected-option\"";
					}
					else {
							 if ($themedir["dir"] == $THEME_DIR) print " class=\"selected-option\"";
					}
					print ">".$themedir["name"]."</option>\n";
			}
			print "</select></form>";
			break;
			case 1:
					$menu = array();
					$menu["label"] = $pgv_lang["change_theme"];
					$menu["labelpos"] = "left";
					$menu["link"] = "#";
					$menu["class"] = "thememenuitem";
					$menu["hoverclass"] = "thememenuitem_hover";
					$menu["flyout"] = "down";
					$menu["submenuclass"] = "favsubmenu";
					$menu["items"] = array();
					foreach($themes as $indexval => $themedir) {
							$submenu = array();
							$submenu["label"] = $themedir["name"];
							$submenu["labelpos"] = "right";
							$submenu["link"] = "themechange.php?frompage=".urlencode($frompage)."&amp;mytheme=".$themedir["dir"];
							$submenu["class"] = "favsubmenuitem";
							$submenu["hoverclass"] = "favsubmenuitem_hover";
							$menu["items"][] = $submenu;
					}
					print_menu($menu);
			break;
	}
	print "</div>\n";

	print "<br /></div></div>";
	print "</div>";
}
?>
