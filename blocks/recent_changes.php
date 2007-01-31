<?php
/**
 * Recent Changes Block
 *
 * This block will print a list of recent changes
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

$PGV_BLOCKS["print_recent_changes"]["name"]			= $pgv_lang["recent_changes_block"];
$PGV_BLOCKS["print_recent_changes"]["descr"]		= "recent_changes_descr";
$PGV_BLOCKS["print_recent_changes"]["canconfig"]	= true;
$PGV_BLOCKS["print_recent_changes"]["config"]		= array(
	"cache"=>1,
	"days"=>30, 
	"hide_empty"=>"no"
	);

//-- Recent Changes block
//-- this block prints a list of changes that have occurred recently in your gedcom
function print_recent_changes($block=true, $config="", $side, $index) {
	global $pgv_lang, $factarray, $month, $year, $day, $monthtonum, $HIDE_LIVE_PEOPLE, $SHOW_ID_NUMBERS, $command, $TEXT_DIRECTION;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $REGEXP_DB, $DEBUG, $ASC, $IGNORE_FACTS, $IGNORE_YEAR, $TOTAL_QUERIES, $LAST_QUERY, $PGV_BLOCKS, $SHOW_SOURCES;
    global $objectlist;

	$block = true;			// Always restrict this block's height

	if ($command=="user") $filter = "living";
	else $filter = "all";

	if (empty($config)) $config = $PGV_BLOCKS["print_recent_changes"]["config"];
	if ($config["days"]<1) $config["days"] = 30;
	if (isset($config["hide_empty"])) $HideEmpty = $config["hide_empty"];
	else $HideEmpty = "no";

	$daytext = "";
	$action = "today";
	$found_facts = array();
	$monthstart = mktime(1,0,0,$monthtonum[strtolower($month)],$day,$year);
	$mmon2 = date("m", $monthstart-(60*60*24*$config["days"]));
	$mday2 = date("d", $monthstart-(60*60*24*$config["days"]));
	$myear2 = date("Y", $monthstart-(60*60*24*$config["days"]));
	$changes = get_recent_changes($mday2, $mmon2, $myear2);

	if (count($changes)>0) {
		$found_facts = array();
		$last_total = $TOTAL_QUERIES;
		foreach($changes as $id=>$change) {
			$gid = $change['d_gid'];
			$gedrec = find_gedcom_record($change['d_gid']);
			if (empty($gedrec)) $gedrec = find_updated_record($change['d_gid']);

			if (empty($gedrec)) {
				if ($DEBUG) print "Record ".$change['d_gid']." not found ";
			} else {
				$type = "INDI";
				$match = array();
				$ct = preg_match("/0 @.*@ (\w*)/", $gedrec, $match);
				if ($ct>0) $type = trim($match[1]);
				$disp = true;
				switch($type) {
					case 'INDI':
						if (($filter=="living")&&(is_dead_id($gid)==1)) $disp = false;
						else if ($HIDE_LIVE_PEOPLE) $disp = displayDetailsByID($gid);
						break;
					case 'FAM':
						if ($filter=="living") {
							$parents = find_parents_in_record($gedrec);
							if (is_dead_id($parents["HUSB"])==1) $disp = false;
							else if ($HIDE_LIVE_PEOPLE) $disp = displayDetailsByID($parents["HUSB"]);
							if ($disp) {
								if (is_dead_id($parents["WIFE"])==1) $disp = false;
								else if ($HIDE_LIVE_PEOPLE) $disp = displayDetailsByID($parents["WIFE"]);
							}
						}
						else if ($HIDE_LIVE_PEOPLE) $disp = displayDetailsByID($gid, "FAM");
						break;
					default:
						$disp = displayDetailsByID($gid, $type);
						break;
				}
				if ($disp) {
					$factrec = get_sub_record(1, "1 CHAN", $gedrec);
					$found_facts[] = array($gid, $factrec, $type);
				}
			}
		}
	}

// Start output
	if (count($found_facts)==0 and $HideEmpty=="yes") return false;
//		Print block header
	print "<div id=\"recent_changes\" class=\"block\">";
	print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
	print "<td class=\"blockh1\" >&nbsp;</td>";
	print "<td class=\"blockh2\" ><div class=\"blockhc\">";
	print_help_link("recent_changes_help", "qm");
	if ($PGV_BLOCKS["print_recent_changes"]["canconfig"]) {
		$username = getUserName();
		if ((($command=="gedcom")&&(userGedcomAdmin($username))) || (($command=="user")&&(!empty($username)))) {
			if ($command=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
			else $name = $username;
			print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;command=$command&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
		}
	}
	print "<b>".$pgv_lang["recent_changes"]."</b>";
	print "</div></td>";
	print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
	print "</table>";
	print "<div class=\"blockcontent\" >";
	if ($block) print "<div class=\"small_inner_block\">\n";

//		Print block content
	$pgv_lang["global_num1"] = $config["days"];		// Make this visible
	if (count($found_facts)==0) {
		print_text("recent_changes_none");
	} else {
		print_text("recent_changes_some");

	// sortable table
	require_once("functions_print_lists.php");
	print_changes_table($found_facts);

			}

	if ($block) print "</div>\n"; //small_inner_block
	print "</div>"; // blockcontent
	print "</div>"; // block

}

function print_recent_changes_config($config) {
	global $pgv_lang, $command, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_recent_changes"]["config"];
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["print_recent_changes"]["config"]["cache"];

	print "<tr><td class=\"descriptionbox wrap width33\">".$pgv_lang["days_to_show"]."</td>";?>
	<td class="optionbox">
		<input type="text" name="days" size="2" value="<?php print $config["days"]; ?>" />
	</td></tr>

	<?php
  	print "<tr><td class=\"descriptionbox wrap width33\">".$pgv_lang["show_empty_block"]."</td>";?>
	<td class="optionbox">
	<select name="hide_empty">
		<option value="no"<?php if ($config["hide_empty"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
		<option value="yes"<?php if ($config["hide_empty"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
	</select>
	</td></tr>
	<tr><td colspan="2" class="optionbox wrap">
		<span class="error"><?php print $pgv_lang["hide_block_warn"]; ?></span>
	</td></tr>
	<?php

	// Cache file life
	if ($command=="gedcom") {
  		print "<tr><td class=\"descriptionbox wrap width33\">";
			print_help_link("cache_life_help", "qm");
			print $pgv_lang["cache_life"];
		print "</td><td class=\"optionbox\">";
			print "<input type=\"text\" name=\"cache\" size=\"2\" value=\"".$config["cache"]."\" />";
		print "</td></tr>";
	}
}
?>
