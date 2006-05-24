<?php
/**
 * User Favorites Block
 *
 * This block will print a users favorites
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * $Id$
 * @package PhpGedView
 * @subpackage Blocks
 */
require_once("includes/functions_print_lists.php");
$PGV_BLOCKS["print_user_favorites"]["name"]        = $pgv_lang["user_favorites_block"];
$PGV_BLOCKS["print_user_favorites"]["descr"]        = "user_favorites_descr";
$PGV_BLOCKS["print_user_favorites"]["type"]        = "user";
$PGV_BLOCKS["print_user_favorites"]["canconfig"]        = false;

//-- print user favorites
function print_user_favorites($block=true, $config="", $side, $index) {
		global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $TEXT_DIRECTION, $INDEX_DIRECTORY, $MEDIA_DIRECTORY, $MULTI_MEDIA, $MEDIA_DIRECTORY_LEVELS, $command, $indilist, $sourcelist;

		$userfavs = getUserFavorites(getUserName());
		if (!is_array($userfavs)) $userfavs = array();
		print "<div id=\"user_favorites\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		print_help_link("mygedview_favorites_help", "qm");
		print "<b>".$pgv_lang["my_favorites"]."&nbsp;&nbsp;";
		if ($TEXT_DIRECTION=="rtl") print "&rlm;";
		print "(".count($userfavs).")";
		if ($TEXT_DIRECTION=="rtl") print "&rlm;";
		print "</b>";
		print "</div></td>";
		print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
		print "</table>";
		print "<div class=\"blockcontent\">";
		if ($block) print "<div class=\"small_inner_block\">\n";
		if (count($userfavs)==0) {
		print_text("no_favorites");
		print "\n";
		} 
		else {
			print "<table width=\"100%\" class=\"$TEXT_DIRECTION\">";
			$mygedcom = $GEDCOM;
			$current_gedcom = $GEDCOM;
			if ($block) $style = 1;
			else $style = 2;
			foreach($userfavs as $key=>$favorite) {
				if (isset($favorite["id"])) $key=$favorite["id"];
				$current_gedcom = $GEDCOM;
				$GEDCOM = $favorite["file"];
				if ($favorite["type"]=="URL") {
					print "<tr><td>";
					print "<div id=\"boxurl".$key.".0\" class=\"person_box";
					print "\"><ul>\n";
					print "<li><a href=\"".$favorite["url"]."\">".PrintReady($favorite["title"])."</a></li>";
					print "</ul>";
					print PrintReady($favorite["note"]);
					print "</div>\n";
				}
				else {
					require $INDEX_DIRECTORY.$GEDCOM."_conf.php";
					$indirec = find_gedcom_record($favorite["gid"]);
					if ($favorite["type"]=="INDI") {
						print "<tr><td>";
						print "<div id=\"box".$favorite["gid"].".0\" class=\"person_box";
						if (preg_match("/1 SEX F/", $indirec)>0) print "F";
						else if (preg_match("/1 SEX M/", $indirec)>0) print "";
						else print "NN";
						print "\">\n";
						print_pedigree_person($favorite["gid"], $style, 1, $key);
						print PrintReady($favorite["note"]);
						print "</div>\n";
					}
					if ($favorite["type"]=="FAM") {
						print "<tr><td>";
						print "<div id=\"box".$favorite["gid"].".0\" class=\"person_box";
						print "\"><ul>\n";
						print_list_family($favorite["gid"], array(get_family_descriptor($favorite["gid"]), $favorite["file"]));
						print "</ul>";
						print PrintReady($favorite["note"]);
						print "</div>\n";
					}
					if ($favorite["type"]=="SOUR") {
						print "<tr><td>";
						print "<div id=\"box".$favorite["gid"].".0\" class=\"person_box";
						print "\"><ul>\n";
						print_list_source($favorite["gid"], $sourcelist[$favorite["gid"]]);
						print "</ul>";
						print PrintReady($favorite["note"]);
						print "</div>\n";
					}
				}
				if ($command=="user" || userIsAdmin(getUserName())) print "<a class=\"font9\" href=\"index.php?command=$command&amp;action=deletefav&amp;fv_id=".$key."\" onclick=\"return confirm('".$pgv_lang["confirm_fav_remove"]."');\">".$pgv_lang["remove"]."</a><br />\n";
					print "</td></tr>\n";
				$GEDCOM = $mygedcom;
				require $INDEX_DIRECTORY.$GEDCOM."_conf.php";
			}
			print "</table>\n";
		}
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
	var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
	//-->
	</script>
	<br />
	<?php
		print_help_link("index_add_favorites_help", "qm");
		print "<b><a href=\"javascript: ".$pgv_lang["add_favorite"]." \" onclick=\"expand_layer('add_user_fav'); return false;\"><img id=\"add_user_fav_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" alt=\"\" />&nbsp;".$pgv_lang["add_favorite"]."</a></b>";
		print "<br /><div id=\"add_user_fav\" style=\"display: none;\">\n";
		print "<form name=\"addufavform\" method=\"get\" action=\"index.php\">\n";
		print "<input type=\"hidden\" name=\"action\" value=\"addfav\" />\n";
		print "<input type=\"hidden\" name=\"command\" value=\"$command\" />\n";
		print "<input type=\"hidden\" name=\"favtype\" value=\"user\" />\n";
		print "<table border=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>".$pgv_lang["add_fav_enter_id"]." <br />";
		print "<input class=\"pedigree_form\" type=\"text\" name=\"gid\" id=\"gid\" size=\"3\" value=\"\" />";
		print_findindi_link("gid","");
		print_findfamily_link("gid");
		print_findsource_link("gid");
		print "\n<br />".$pgv_lang["add_fav_or_enter_url"];
		print "\n<br />".$pgv_lang["url"]."<input type=\"text\" name=\"url\" size=\"40\" value=\"\" />";
		print "\n<br />".$pgv_lang["title"]." <input type=\"text\" name=\"favtitle\" size=\"40\" value=\"\" />";
		print "\n</td><td>";
		print "\n".$pgv_lang["add_fav_enter_note"];
		print "\n<br /><textarea name=\"favnote\" rows=\"6\" cols=\"40\"></textarea>";
		print "</td></tr></table>\n";
		print "\n<br /><input type=\"submit\" value=\"".$pgv_lang["add"]."\" style=\"font-size: 8pt; \" />";
		print "\n</form></div>\n";
		if ($block) print "</div>\n";
		print "</div>\n"; // content
		print "</div>";   // block
}
?>
