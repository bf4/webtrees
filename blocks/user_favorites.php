<?php
/**
 * User Favorites Block
 *
 * This block will print a users favorites
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_USER_FAVORITES_PHP', '');

require_once("includes/functions/functions_print_lists.php");

$PGV_BLOCKS["print_user_favorites"]["name"]			= $pgv_lang["user_favorites_block"];
$PGV_BLOCKS["print_user_favorites"]["descr"]		= "user_favorites_descr";
$PGV_BLOCKS["print_user_favorites"]["type"]			= "user";
$PGV_BLOCKS["print_user_favorites"]["canconfig"]	= false;
$PGV_BLOCKS["print_user_favorites"]["config"]		= array("cache"=>0);

//-- print user favorites
function print_user_favorites($block=true, $config="", $side, $index) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $TEXT_DIRECTION, $INDEX_DIRECTORY, $MEDIA_DIRECTORY, $MULTI_MEDIA, $MEDIA_DIRECTORY_LEVELS, $ctype;
	global $show_full, $PEDIGREE_FULL_DETAILS, $BROWSERTYPE;

	// Override GEDCOM configuration temporarily
	if (isset($show_full)) $saveShowFull = $show_full;
	$savePedigreeFullDetails = $PEDIGREE_FULL_DETAILS;
	$show_full = 1;
	$PEDIGREE_FULL_DETAILS = 1;

	$userfavs = getUserFavorites(PGV_USER_NAME);
	if (!is_array($userfavs)) $userfavs = array();

	$id="user_favorites";
	$title = print_help_link("mygedview_favorites_help", "qm","",false, true);
	$title .= $pgv_lang["my_favorites"]."&nbsp;&nbsp;";
	if ($TEXT_DIRECTION=="rtl") $title .= getRLM();
	$title .= "(".count($userfavs).")";
	if ($TEXT_DIRECTION=="rtl") $title .= getRLM();

	$content = '<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.ajaxQueue.js"></script>
	<script type="text/javascript">
	jQuery.noConflict(); // @see http://docs.jquery.com/Using_jQuery_with_Other_Libraries/
	jQuery(document).ready(function($){
		$("input[name^=gid]").autocomplete("autocomplete.php", {
			extraParams: {field:"IFSRO"},
			formatItem: function(row, i) {
				return row[0] + " (" + row[1] + ")";
			},
			formatResult: function(row) {
				return row[1];
			},
			width: 400,
			minChars: 2
		});
	}); 
	</script>';
	if ($block) {
		$style = 2;		// 1 means "regular box", 2 means "wide box"
		$tableWidth = ($BROWSERTYPE=="msie") ? "95%" : "99%";	// IE needs to have room for vertical scroll bar inside the box
		$cellSpacing = "1px";
	} else {
		$style = 2;
		$tableWidth = "99%";
		$cellSpacing = "3px";
	}
	if (count($userfavs)==0) {
		$content .= print_text("no_favorites",0,1);
	} else {
		$mygedcom = $GEDCOM;
		$current_gedcom = $GEDCOM;
		$content .= "<table width=\"{$tableWidth}\" style=\"border:none\" cellspacing=\"{$cellSpacing}\" class=\"center $TEXT_DIRECTION\">";
		foreach($userfavs as $key=>$favorite) {
			if (isset($favorite["id"])) $key=$favorite["id"];
			$removeFavourite = "<a class=\"font9\" href=\"".encode_url("index.php?ctype={$ctype}&action=deletefav&fv_id={$key}")."\" onclick=\"return confirm('".$pgv_lang["confirm_fav_remove"]."');\">".$pgv_lang["remove"]."</a><br />";
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
					if ($ctype=="user" || PGV_USER_IS_ADMIN) $content .= $removeFavourite;
					ob_start();
					print_pedigree_person($favorite["gid"], $style, 1, $key);
					$content .= ob_get_clean();
					$content .= PrintReady($favorite["note"]);
				} else {
					$record=GedcomRecord::getInstance($favorite['gid']);
					$content .= "<div id=\"box".$favorite["gid"].".0\" class=\"person_box\">";
					if ($ctype=="user" || PGV_USER_IS_ADMIN) $content .= $removeFavourite;
					if ($record) {
						$content.=$record->format_list('span');
					} else {
						$content.=$pgv_lang['invalid_id'];
					}
					$content .= "<br />".PrintReady($favorite["note"]);
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
	<br />
	';
	$uniqueID = floor(microtime() * 1000000);
	$content .= print_help_link("index_add_favorites_help", "qm","",false,true);
	$content .= "<b><a href=\"javascript: ".$pgv_lang["add_favorite"]." \" onclick=\"expand_layer('add_user_fav'); return false;\"><img id=\"add_user_fav_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" alt=\"\" />&nbsp;".$pgv_lang["add_favorite"]."</a></b>";
	$content .= "<br /><div id=\"add_user_fav\" style=\"display: none;\">";
	$content .= "<form name=\"addufavform\" method=\"post\" action=\"index.php\">";
	$content .= "<input type=\"hidden\" name=\"action\" value=\"addfav\" />";
	$content .= "<input type=\"hidden\" name=\"ctype\" value=\"$ctype\" />";
	$content .= "<input type=\"hidden\" name=\"favtype\" value=\"user\" />";
	$content .= "<input type=\"hidden\" name=\"ged\" value=\"$GEDCOM\" />";
	$content .= "<table width=\"{$tableWidth}\" style=\"border:none\" cellspacing=\"{$cellSpacing}\" class=\"center $TEXT_DIRECTION\">";
	$content .= "<tr><td>".$pgv_lang["add_fav_enter_id"]." <br />";
	$content .= "<input class=\"pedigree_form\" type=\"text\" name=\"gid\" id=\"gid{$uniqueID}\" size=\"5\" value=\"\" />";

	$content .= print_findindi_link("gid{$uniqueID}",'',true)."\n";
	$content .= print_findfamily_link("gid{$uniqueID}",'',true)."\n";
	$content .= print_findsource_link("gid{$uniqueID}",'',true)."\n";
	$content .= print_findrepository_link("gid{$uniqueID}",'',true)."\n";
	$content .= print_findnote_link("gid{$uniqueID}",'',true)."\n";
	$content .= print_findmedia_link("gid{$uniqueID}",'1','',true)."\n";

	$content .= "<br />".$pgv_lang["add_fav_or_enter_url"];
	$content .= "<table><tr><td>".$pgv_lang["url"]."</td><td><input type=\"text\" name=\"url\" size=\"40\" value=\"\" /></td></tr>";
	$content .= "<tr><td>".$pgv_lang["title"]."</td><td><input type=\"text\" name=\"favtitle\" size=\"40\" value=\"\" /></td></tr></table>";
	if ($block) $content .= "\n</td></tr><tr><td><br />";
	else $content .= "\n</td><td>";
	$content .= $pgv_lang["add_fav_enter_note"];
	$content .= "<br /><textarea name=\"favnote\" rows=\"6\" cols=\"50\"></textarea>";
	$content .= "</td></tr></table>";
	$content .= "<br /><input type=\"submit\" value=\"".$pgv_lang["add"]."\" style=\"font-size: 8pt; \" />";
	$content .= "</form></div>";

	global $THEME_DIR;
	if ($block) {
		include($THEME_DIR."templates/block_small_temp.php");
	} else {
		include($THEME_DIR."templates/block_main_temp.php");
	}
	// Restore GEDCOM configuration
	unset($show_full);
	if (isset($saveShowFull)) $show_full = $saveShowFull;
	$PEDIGREE_FULL_DETAILS = $savePedigreeFullDetails;
}
?>
