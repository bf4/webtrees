<?php
/**
 * Top 10 Pageviews Block
 *
 * This block will show the top 10 records from the Gedcom that have been viewed the most
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

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_TOP10_PAGEVIEWS_PHP', '');

$PGV_BLOCKS["top10_pageviews"]["name"]		= $pgv_lang["top10_pageviews"];
$PGV_BLOCKS["top10_pageviews"]["descr"]		= "top10_pageviews_descr";
$PGV_BLOCKS["top10_pageviews"]["canconfig"]	= true;
$PGV_BLOCKS["top10_pageviews"]["config"]	= array(
	"cache"=>1,
	"num"=>10,
	"count_placement"=>"left"
	);

function top10_pageviews($block=true, $config="", $side, $index) {
	global $pgv_lang, $GEDCOM, $INDEX_DIRECTORY, $PGV_BLOCKS, $ctype, $PGV_IMAGES, $PGV_IMAGE_DIR, $SHOW_SOURCES, $TEXT_DIRECTION;

	if (empty($config)) {
		$config = $PGV_BLOCKS["top10_pageviews"]["config"];
	}

	if (isset($config["count_placement"])) {
		$CountSide = $config["count_placement"];
	} else {
		$CountSide = "left";
	}

	$PGV_COUNTER_FILENAME = $INDEX_DIRECTORY.$GEDCOM."pgv_counters.txt";

	$id = "top10hits";
	$title = print_help_link("index_top10_pageviews_help", "qm", "", false, true);
	if ($PGV_BLOCKS["top10_pageviews"]["canconfig"]) {
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
	$title .= $pgv_lang["top10_pageviews"];
	$content = "";

	// if the counter file does not exist then don't do anything
	if (!file_exists($PGV_COUNTER_FILENAME)) {
		if (PGV_USER_IS_ADMIN) {
			$content .= "<span class=\"error\">".$pgv_lang["top10_pageviews_msg"]."</span>";
		}
	} else {
		// load the lines from the file
		$lines = file($PGV_COUNTER_FILENAME);
		$ids = array();
		// loop through the lines and create an array of ids
		foreach ($lines as $indexval => $line) {
			$ct = preg_match("/@(.+)@\s*(\d+)/", $line, $match);
			if ($ct>0) {
				$id = trim($match[1]);
				$count = trim($match[2]);
				if (!empty($id)) $ids[$id] = $count;
			}
		}

		if (count($ids)>0) {
			arsort($ids);
			if ($block) {
				$content .= "<table width=\"90%\">";
			} else {
				$content .= "<table>";
			}
			$i=0;
			foreach($ids as $id=>$count) {
				$record=GedcomRecord::getInstance($id);
				if ($record && $record->canDisplayDetails()) {
					$content .= '<tr valign="top">';
					if ($CountSide=='left') {
						$content .= '<td dir="ltr" align="right">['.$count.']</td>';
					}
					$content .= '<td class="name2" ><a href="'.encode_url($record->getLinkUrl()).'">'.PrintReady($record->getFullName()).'</a></td>';
					if ($CountSide=='right') {
						$content .= '<td dir="ltr" align="right">['.$count.']</td>';
					}
					$content .= '</tr>';
					$i++;
				}
				if ($i>=$config['num']) break;
			}
			$content .= "</table>";
		} else {
			$content .= "<b>".$pgv_lang["top10_pageviews_nohits"]."</b>";
		}
	}

	global $THEME_DIR;
	if ($block) {
		include($THEME_DIR."templates/block_small_temp.php");
	} else {
		include($THEME_DIR."templates/block_main_temp.php");
	}
}

function top10_pageviews_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS;
	if (empty($config)) $config = $PGV_BLOCKS["top10_pageviews"]["config"];
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["top10_pageviews"]["config"]["cache"];

	// Number of items to show
	print "<tr><td class=\"descriptionbox wrap width33\">";
	// print_help_link("num_to_show_help", "qm");
	print $pgv_lang["num_to_show"];
	print "</td><td class=\"optionbox\">";
	print "<input type=\"text\" name=\"num\" size=\"2\" value=\"".$config["num"]."\" />";
	print "</td></tr>";

	// Count position
	print "<tr><td class=\"descriptionbox wrap width33\">";
	// print_help_link("before_or_after_help", "qm");
	print $pgv_lang["before_or_after"];
	print "</td><td class=\"optionbox\">";
	print "<select name=\"count_placement\">";
	print "<option value=\"left\"";
	if ($config["count_placement"]=="left") print " selected=\"selected\"";
	print ">".$pgv_lang["before"]."</option>";
	print "<option value=\"right\"";
	if ($config["count_placement"]=="right") print " selected=\"selected\"";
	print ">".$pgv_lang["after"]."</option>";
	print "</select>";
	print "</td></tr>";

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
