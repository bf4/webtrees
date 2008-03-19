<?php
/**
 * User Favorites Block
 *
 * This block will print a users favorites
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  John Finlay and Others
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
$PGV_BLOCKS["print_user_favorites"]["name"]			= $pgv_lang["user_favorites_block"];
$PGV_BLOCKS["print_user_favorites"]["descr"]		= "user_favorites_descr";
$PGV_BLOCKS["print_user_favorites"]["type"]			= "user";
$PGV_BLOCKS["print_user_favorites"]["canconfig"]	= false;
$PGV_BLOCKS["print_user_favorites"]["config"]		= array("cache"=>0);

//-- print user favorites
function print_user_favorites($block=true, $config="", $side, $index) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $TEXT_DIRECTION, $INDEX_DIRECTORY, $MEDIA_DIRECTORY, $MULTI_MEDIA, $MEDIA_DIRECTORY_LEVELS, $ctype, $indilist, $sourcelist;

	$userfavs = getUserFavorites(PGV_USER_NAME);
	if (!is_array($userfavs)) $userfavs = array();

	$id="user_favorites";
	$title = print_help_link("mygedview_favorites_help", "qm","",false, true);
	$title .= $pgv_lang["my_favorites"]."&nbsp;&nbsp;";
	if ($TEXT_DIRECTION=="rtl") $title .= getRLM();
	$title .= "(".count($userfavs).")";
	if ($TEXT_DIRECTION=="rtl") $title .= getRLM();

	$content = "";
	if (count($userfavs)==0) {
		$content .= print_text("no_favorites",0,1);
	}
	else {
		$content .= "<table width=\"100%\" class=\"$TEXT_DIRECTION\">";
		$mygedcom = $GEDCOM;
		$current_gedcom = $GEDCOM;
		if ($block) $style = 1;
		else $style = 2;
		foreach($userfavs as $key=>$favorite) {
			if (isset($favorite["id"])) $key=$favorite["id"];
			$removeFavourite = "<a class=\"font9\" href=\"index.php?ctype=$ctype&amp;action=deletefav&amp;fv_id=".$key."\" onclick=\"return confirm('".$pgv_lang["confirm_fav_remove"]."');\">".$pgv_lang["remove"]."</a><br />";
			$current_gedcom = $GEDCOM;
			$GEDCOM = $favorite["file"];
			$content .= "<tr><td>";
			if ($favorite["type"]=="URL") {
				$content .= "<div id=\"boxurl".$key.".0\" class=\"person_box\">";
				if ($ctype=="user" || PGV_USER_IS_ADMIN) $content .= $removeFavourite;
				$content .= "<a href=\"".$favorite["url"]."\">".PrintReady($favorite["title"])."</a>";
				$content .= "<br />".PrintReady($favorite["note"]);
			} else {
				require $INDEX_DIRECTORY.$GEDCOM."_conf.php";
				$indirec = find_gedcom_record($favorite["gid"]);
				if ($favorite["type"]=="INDI") {
					$content .= "<div id=\"box".$favorite["gid"].".0\" class=\"person_box";
					if (preg_match("/1 SEX F/", $indirec)>0) $content .= "F";
					else if (preg_match("/1 SEX M/", $indirec)>0) $content .= "";
					else $content .= "NN";
					$content .= "\">";
					if ($ctype=="user" || userIsAdmin()) $content .= $removeFavourite;
					ob_start();
					print_pedigree_person($favorite["gid"], $style, 1, $key);
					$content .= ob_get_clean();
					$content .= PrintReady($favorite["note"]);
				}
				if ($favorite["type"]=="FAM") {
					$content .= "<div id=\"box".$favorite["gid"].".0\" class=\"person_box\">";
					if ($ctype=="user" || userIsAdmin()) $content .= $removeFavourite;
					ob_start();
					print_list_family($favorite["gid"], array(get_family_descriptor($favorite["gid"]), $favorite["file"]), false, "", false);
					$content .= ob_get_clean();
					$content .= PrintReady($favorite["note"]);
				}
				if ($favorite["type"]=="SOUR") {
					$content .= "<div id=\"box".$favorite["gid"].".0\" class=\"person_box\">";
					if ($ctype=="user" || userIsAdmin()) $content .= $removeFavourite;
					ob_start();
					print_list_source($favorite["gid"], $sourcelist[$favorite["gid"]], false);
					$content .= ob_get_clean();
					$content .= PrintReady($favorite["note"]);
				}
				if ($favorite["type"]=="OBJE") {
					$content .= "<div id=\"box".$favorite["gid"].".0\">";
					if ($ctype=="user" || userIsAdmin()) $content .= $removeFavourite;
					ob_start();
					print_media_links("1 OBJE @".$favorite["gid"]."@", 1, $favorite["gid"]);
					$content .= ob_get_clean();
					$content .= PrintReady($favorite["note"]);
				}
			}
			$content .= "</div>";
			$content .= "</td></tr>";
			$GEDCOM = $mygedcom;
			require $INDEX_DIRECTORY.$GEDCOM."_conf.php";
		}
		$content .= "</table>";
	}
	$content .= '
	<script language="JavaScript" type="text/javascript">
	var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
	</script>
	<br />';
	$content .= print_help_link("index_add_favorites_help", "qm","",false,true);
	$content .= "<b><a href=\"javascript: ".$pgv_lang["add_favorite"]." \" onclick=\"expand_layer('add_user_fav'); return false;\"><img id=\"add_user_fav_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" alt=\"\" />&nbsp;".$pgv_lang["add_favorite"]."</a></b>";
	$content .= "<br /><div id=\"add_user_fav\" style=\"display: none;\">";
	$content .= "<form name=\"addufavform\" method=\"post\" action=\"index.php\">";
	$content .= "<input type=\"hidden\" name=\"action\" value=\"addfav\" />";
	$content .= "<input type=\"hidden\" name=\"ctype\" value=\"$ctype\" />";
	$content .= "<input type=\"hidden\" name=\"favtype\" value=\"user\" />";
	$content .= "<input type=\"hidden\" name=\"ged\" value=\"$GEDCOM\" />";
	$content .= "<table border=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>".$pgv_lang["add_fav_enter_id"]." <br />";
	$content .= "<input class=\"pedigree_form\" type=\"text\" name=\"gid\" id=\"gid\" size=\"3\" value=\"\" />";

	$content .= print_findindi_link("gid","",true);
	$content .= print_findfamily_link("gid",'',true);
	$content .= print_findsource_link("gid",'',true);
	$content .= "<br />".$pgv_lang["add_fav_or_enter_url"];
	$content .= "<br />".$pgv_lang["url"]."<input type=\"text\" name=\"url\" size=\"40\" value=\"\" />";
	$content .= "<br />".$pgv_lang["title"]." <input type=\"text\" name=\"favtitle\" size=\"40\" value=\"\" />";
	$content .= "</td><td>";
	$content .= "".$pgv_lang["add_fav_enter_note"];
	$content .= "<br /><textarea name=\"favnote\" rows=\"6\" cols=\"40\"></textarea>";
	$content .= "</td></tr></table>";
	$content .= "<br /><input type=\"submit\" value=\"".$pgv_lang["add"]."\" style=\"font-size: 8pt; \" />";
	$content .= "</form></div>";

	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) {
		print '<div class="small_inner_block">'.$content.'</div>';
	} else {
		print $content;
	}
	print '</div></div>';
}
?>
