<?php
/**
 * Top 10 Surnames Block
 *
 * This block will show the top 10 surnames that occur most frequently in the active gedcom
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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

$PGV_BLOCKS["print_block_name_top10"]["name"]		= $pgv_lang["block_top10"];
$PGV_BLOCKS["print_block_name_top10"]["descr"]		= "block_top10_descr";
$PGV_BLOCKS["print_block_name_top10"]["canconfig"]	= true;
$PGV_BLOCKS["print_block_name_top10"]["config"]		= array(
	"cache"=>7,
	"num"=>10, 
	);

function top_surname_sort($a, $b) {
	return $b["match"] - $a["match"];
}

function print_block_name_top10($block=true, $config="", $side, $index) {
	global $pgv_lang, $GEDCOM, $DEBUG, $TEXT_DIRECTION;
	global $COMMON_NAMES_ADD, $COMMON_NAMES_REMOVE, $COMMON_NAMES_THRESHOLD, $PGV_BLOCKS, $ctype, $PGV_IMAGES, $PGV_IMAGE_DIR, $SURNAME_LIST_STYLE;

	if (empty($config)) $config = $PGV_BLOCKS["print_block_name_top10"]["config"];

	//-- cache the result in the session so that subsequent calls do not have to
	//-- perform the calculation all over again.
	if (isset($_SESSION["top10"][$GEDCOM])&&(!isset($DEBUG)||($DEBUG==false))) {
		$surnames = $_SESSION["top10"][$GEDCOM];
	} else {
		$surnames = get_top_surnames($config["num"]);

		// Insert from the "Add Names" list if not already in there
		if ($COMMON_NAMES_ADD != "") {
			$addnames = preg_split("/[,;] /", $COMMON_NAMES_ADD);
			if (count($addnames)==0) $addnames[] = $COMMON_NAMES_ADD;
			foreach($addnames as $indexval => $name) {
				$surname = UTF8_strtoupper($name);
				if (!isset($surnames[$surname])) {
					$surnames[$surname]["name"] = $name;
					$surnames[$surname]["match"] = $COMMON_NAMES_THRESHOLD;
				}
			}
		}

		// Remove names found in the "Remove Names" list
		if ($COMMON_NAMES_REMOVE != "") {
			$delnames = preg_split("/[,;] /", $COMMON_NAMES_REMOVE);
			if (count($delnames)==0) $delnames[] = $COMMON_NAMES_REMOVE;
			foreach($delnames as $indexval => $name) {
				$surname = UTF8_strtoupper($name);
				unset($surnames[$surname]);
			}
		}
		unset($surnames["UNKNOWN"]);
		unset($surnames["@N.N."]);
		unset($surnames["?"]);

		// Sort the list and save for future reference
		uasort($surnames, "top_surname_sort");
		$_SESSION["top10"][$GEDCOM] = $surnames;
	}
	if (count($surnames)>0) {
		$id="top10surnames";
		$title = print_help_link("index_common_names_help", "qm","",false,true);
		if ($PGV_BLOCKS["print_block_name_top10"]["canconfig"]) {
			if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
				if ($ctype=="gedcom") {
					$name = preg_replace("/'/", "\'", $GEDCOM);
				} else {
					$name = PGV_USER_NAME;
				}
				$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('".encode_url("index_edit.php?name={$name}&ctype={$ctype}&action=configure&side={$side}&index={$index}")."', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
				$title .= "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>";
			}
		}
		$title .= str_replace("10", $config["num"], $pgv_lang["block_top10_title"]);

		$all_surnames=array();
		foreach (array_keys($surnames) as $n=>$surname) {
			if ($n>=$config["num"]) {
				break;
			}
			foreach (array_keys(get_surname_indis($surname)) as $pid) {
				$person=Person::getInstance($pid);
				foreach ($person->getAllNames() as $name) {
					$surn=reset(explode(',', $name['sort']));
					if ($surn && $surn!='@N.N.' && $surname==$surn) {
						$spfxsurn=reset(explode(',', $name['list']));
						if (! array_key_exists($surn, $all_surnames)) {
							$all_surnames[$surn]=array();
						}
						if (! array_key_exists($spfxsurn, $all_surnames[$surn])) {
							$all_surnames[$surn][$spfxsurn]=array();
						}
						// $surn is the base surname, e.g. GOGH
						// $spfxsurn is the full surname, e.g. van GOGH
						// $pid allows us to count indis as well as surnames, for indis that
						// appear twice in this list.
						$all_surnames[$surn][$spfxsurn][$pid]=true;
					}
				}
			}
		}
		switch ($SURNAME_LIST_STYLE) {
		case 'style3':
			$content=format_surname_tagcloud($all_surnames, 'indilist', true);
			break;
		case 'style2':
		default:
			$content=format_surname_table($all_surnames, 'indilist');
			break;
		}
	}

	global $THEME_DIR;
	if ($block) {
		include($THEME_DIR."templates/block_small_temp.php");
	} else {
		include($THEME_DIR."templates/block_main_temp.php");
	}
}

function print_block_name_top10_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_block_name_top10"]["config"];
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["print_block_name_top10"]["config"]["cache"];
?>
	<tr>
		<td class="descriptionbox wrap width33"><?php print $pgv_lang["num_to_show"] ?></td>
	<td class="optionbox">
		<input type="text" name="num" size="2" value="<?php print $config["num"]; ?>" />
	</td></tr>

	<?php

	// Cache file life
	if ($ctype=="gedcom") {
  		print "<tr><td class=\"descriptionbox wrap width33\">";
			print_help_link("cache_life_help", "qm");
			print $pgv_lang["cache_life"];
		print "</td><td class=\"optionbox\">";
			print "<input type=\"text\" name=\"cache\" size=\"2\" value=\"".$config["cache"]."\" />";
		print "</td></tr>";
	}
}
?>
