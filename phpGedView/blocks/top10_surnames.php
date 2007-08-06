<?php
/**
 * Top 10 Surnames Block
 *
 * This block will show the top 10 surnames that occur most frequently in the active gedcom
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  John Finlay and Others
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
	"count_placement"=>"left"
	);

function print_block_name_top10($block=true, $config="", $side, $index) {
	global $pgv_lang, $GEDCOM, $DEBUG, $TEXT_DIRECTION;
	global $COMMON_NAMES_ADD, $COMMON_NAMES_REMOVE, $COMMON_NAMES_THRESHOLD, $PGV_BLOCKS, $ctype, $PGV_IMAGES, $PGV_IMAGE_DIR;

	function top_surname_sort($a, $b) {
		return $b["match"] - $a["match"];
	}

	if (empty($config)) $config = $PGV_BLOCKS["print_block_name_top10"]["config"];
	if (isset($config["count_placement"])) $CountSide = $config["count_placement"];
	else $CountSide = "left";

	//-- cache the result in the session so that subsequent calls do not have to
	//-- perform the calculation all over again.
	if (isset($_SESSION["top10"][$GEDCOM])&&(!isset($DEBUG)||($DEBUG==false))) {
		$surnames = $_SESSION["top10"][$GEDCOM];
	}
	else {
		$surnames = get_top_surnames($config["num"]);

// Insert from the "Add Names" list if not already in there
		if ($COMMON_NAMES_ADD != "") {
			$addnames = preg_split("/[,;] /", $COMMON_NAMES_ADD);
			if (count($addnames)==0) $addnames[] = $COMMON_NAMES_ADD;
			foreach($addnames as $indexval => $name) {
				$surname = str2upper($name);
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
				$surname = str2upper($name);
				unset($surnames[$surname]);
			}
		}

// Sort the list and save for future reference
		uasort($surnames, "top_surname_sort");
		$_SESSION["top10"][$GEDCOM] = $surnames;
	}
	if (count($surnames)>0) {
		print "<div id=\"top10surnames\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		print_help_link("index_common_names_help", "qm");
		if ($PGV_BLOCKS["print_block_name_top10"]["canconfig"]) {
			$username = getUserName();
			if ((($ctype=="gedcom")&&(userGedcomAdmin($username))) || (($ctype=="user")&&(!empty($username)))) {
				if ($ctype=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
				else $name = $username;
				print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
				print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
			}
		}
		print "<b>".str_replace("10", $config["num"], $pgv_lang["block_top10_title"])."</b>";
		print "</div></td>";
		print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
		print "</table>";
		print "<div class=\"blockcontent\">\n";
		if ($block) print "<div class=\"small_inner_block\">\n";
		
		if (array_key_exists("UNKNOWN", $surnames)) unset($surnames["UNKNOWN"]);
		if (array_key_exists("@N.N.", $surnames)) unset($surnames["@N.N."]);
		print_surn_table(array_slice($surnames, 0, $config["num"]));
		/** DEPRECATED
		print "<table>";
		$i=0;
		foreach($surnames as $indexval => $surname) {
			if (stristr($surname["name"], "@N.N")===false) {
				print "<tr valign=\"top\">";
				if ($CountSide=="left") {
					print "<td dir=\"ltr\" align=\"right\">";
					if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
					print "[".$surname["match"]."]";
					if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
					print "</td>";
				}
				print "<td class=\"name2\" ";
				if ($block) print "width=\"86%\"";
				print "><a href=\"indilist.php?ged=".$GEDCOM."&amp;surname=".urlencode($surname["name"])."\">".PrintReady($surname["name"])."</a></td>";
				if ($CountSide=="right") {
					print "<td dir=\"ltr\" align=\"right\">";
					if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
					print "[".$surname["match"]."]";
					if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
					print "</td>";
				}
				print "</tr>";
				$i++;
				if ($i>=$config["num"]) break;
			}
		}
		print "</table>";
		**/
		if ($block) print "</div>\n";
		print "</div>";
		print "</div>";
	}
}

function print_block_name_top10_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_block_name_top10"]["config"];
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["print_block_name_top10"]["config"]["cache"];

	print "<tr><td class=\"descriptionbox wrap width33\">".$pgv_lang["num_to_show"]."</td>";?>
	<td class="optionbox">
		<input type="text" name="num" size="2" value="<?php print $config["num"]; ?>" />
	</td></tr>

	<?php
	/** DEPRECATED
  	print "<tr><td class=\"descriptionbox wrap width33\">".$pgv_lang["before_or_after"]."</td>";?>
	<td class="optionbox">
	<select name="count_placement">
		<option value="left"<?php if ($config["count_placement"]=="left") print " selected=\"selected\"";?>><?php print $pgv_lang["before"]; ?></option>
		<option value="right"<?php if ($config["count_placement"]=="right") print " selected=\"selected\"";?>><?php print $pgv_lang["after"]; ?></option>
	</select>
	</td></tr>
	<?php
	**/

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