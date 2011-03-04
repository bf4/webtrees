<?php
/**
 * Displays a place hierachy
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2011 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team. All rights reserved.
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
 * @package webtrees
 * @subpackage Lists
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'placelist.php');
require './includes/session.php';
require_once WT_ROOT.'includes/functions/functions_print_lists.php';

$use_googlemap = false;

if (file_exists(WT_ROOT.WT_MODULES_DIR.'googlemap/placehierarchy.php')) {
	require WT_ROOT.WT_MODULES_DIR.'googlemap/placehierarchy.php';
	if (isset($GOOGLEMAP_ENABLED) && $GOOGLEMAP_ENABLED && isset($GOOGLEMAP_PLACE_HIERARCHY) && $GOOGLEMAP_PLACE_HIERARCHY) {
		$use_googlemap = true;
	}
}

function case_in_array($value, $array) {
	foreach ($array as $key=>$val) {
		if (strcasecmp($value, $val)==0) return true;
	}
	return false;
}
$action = safe_GET('action');
$display = safe_GET('display');
$parent = safe_GET('parent', WT_REGEX_UNSAFE);
$level = safe_GET('level');

if (empty($action)) $action = "find";
if (empty($display)) $display = "hierarchy";

if ($display=="hierarchy") print_header(WT_I18N::translate('Place hierarchy'));
else print_header(WT_I18N::translate('Place List'));

echo "\n\t<div class=\"center\">";
if ($display=="hierarchy" && $level == 0)  {
	echo "<h2>", WT_I18N::translate('Place hierarchy'), " ", $parent[$level-1],"</h2>\n\t";
} else if ($display=="hierarchy" && $level > 0) {
	echo "<h2>", WT_I18N::translate('Place hierarchy'), " - ", $parent[$level-1],"</h2>\n\t";
} else {
	echo "<h2>", WT_I18N::translate('Place List'), "</h2>\n\t";
}

// Set original place name found (used later)
$base_parent = $parent[$level-1];

// Make sure the "parent" array has no holes
if (isset($parent) && is_array($parent)) {
	$parentKeys = array_keys($parent);
	$highKey = max($parentKeys);
	
	for ($j=0; $j<=$highKey; $j++) {
		if (!isset($parent[$j])) {
			$parent[$j] = "";
		}
	}
	ksort($parent, SORT_NUMERIC);
}


if (!isset($parent)) $parent=array();
else {
	if (!is_array($parent)) $parent = array();
	else $parent = array_values($parent);
}
// Remove slashes
foreach ($parent as $p => $child) {
	$parent[$p] = stripLRMRLM($child);
}

if (!isset($level)) {
	$level=0;
}

if ($level>count($parent)) $level = count($parent);
if ($level<count($parent)) $level = 0;

//-- extract the place form encoded in the gedcom
$header = find_gedcom_record("HEAD", WT_GED_ID);
$hasplaceform = strpos($header, "1 PLAC");

//-- hierarchical display
if ($display=="hierarchy") {
	$placelist=get_place_list($parent, $level);
	$numfound=count($placelist);
	// -- sort the array
	$placelist = array_unique($placelist);
	uasort($placelist, "utf8_strcasecmp");

	//-- create a query string for passing to search page
	$tempparent = array_reverse($parent);
	if (count($tempparent)>0) $squery = "&query=".urlencode($tempparent[0]);
	else $squery="";
	for ($i=1; $i<$level; $i++) {
		$squery.=", ".urlencode($tempparent[$i]);
	}

	//-- if the number of places found is 0 then automatically redirect to search page
	if ($numfound==0) {
		$action="show";
	}
	
	echo '<link type="text/css" href="', WT_MODULES_DIR, 'googlemap/css/wt_v3_googlemap.css" rel="stylesheet" />';

	// -- echo the breadcrumb hierarchy
	$numls=0;
	if ($level>0) {
		//-- link to search results
		if ((($level>1)||($parent[0]!=""))&&($numfound>0)) {
			echo $numfound, " ", WT_I18N::translate('Place connections found'), ": ";
		}
		//-- breadcrumb
		$numls = count($parent)-1;
		$num_place="";
		//-- place and page text orientation is opposite -> top level added at the beginning of the place text
		echo "<a href=\"?level=0\">";
		if ($numls>=0 && (($TEXT_DIRECTION=="ltr" && hasRtLText($parent[$numls])) || ($TEXT_DIRECTION=="rtl" && !hasRtLText($parent[$numls])))) { 
			echo WT_I18N::translate('Top Level'), ", ";
		}
		echo "</a>";
		for ($i=$numls; $i>=0; $i--) {
			echo "<a href=\"?level=", ($i+1);
			for ($j=0; $j<=$i; $j++) {
				$levels = explode(', ', trim($parent[$j]));
				// Routine for replacing ampersands
				foreach ($levels as $pindex=>$ppart) {
					if ($j==$numls) {
						$ppart = rawurlencode($base_parent);
					} else {
						$ppart = rawurlencode($ppart);
					}
					$ppart = preg_replace("/amp\%3B/", "", trim($ppart));
						echo "&amp;parent[$j]=", $ppart;
				}
			}
			echo "\">";
			if (trim($parent[$i])=="") {
				echo WT_I18N::translate('unknown');
			} else if ($i == $numls) {
				echo $base_parent; 
			} else {
				echo PrintReady($parent[$i]);
			}
			echo "</a>";
			if ($i>0) {
				echo ", ";
			} elseif (($TEXT_DIRECTION=="rtl" && hasRtLText($parent[$i])) || ($TEXT_DIRECTION=="ltr" &&  !hasRtLText($parent[$i]))) {
				echo ", ";
			}
			if (empty($num_place)) {
				$num_place=$parent[$i];
			}
		}
	}
	echo "<a href=\"?level=0\">";
	//-- place and page text orientation is the same -> top level added at the end of the place text
	if ($level==0 || ($numls>=0 && (($TEXT_DIRECTION=="rtl" && hasRtLText($parent[$numls])) || ($TEXT_DIRECTION=="ltr" && !hasRtLText($parent[$numls]))))) {
		echo WT_I18N::translate('Top Level');
	}
	echo '</a>', help_link('ppp_levels');


	//-- create a string to hold the variable links and place names
	$linklevels="";
	if ($use_googlemap) {
		$placelevels="";
		$place_names=array();
	}
	for ($j=0; $j<$level; $j++) {
		$linklevels .= "&amp;parent[$j]=".urlencode($parent[$j]);
		if ($use_googlemap) {
			if (trim($parent[$j])=="") {
				$placelevels = ", ".WT_I18N::translate('unknown').$placelevels;
			} else {
				$placelevels = ", ".$parent[$j].$placelevels;
			}
		}
	}

	if ($use_googlemap) {
		create_map($placelevels);
	}
	else {
		echo "<br /><br />";
		if (array_key_exists('places_assistant', WT_Module::getActiveModules())) {
			// show clickable map if found
			places_assistant_WT_Module::display_map($level, $parent);
		}
	}

	$i=0;
	$ct1=count($placelist);

	// -- echo the array
	foreach ($placelist as $key => $value) {
		if ($i==0) {
			echo "\n\t<table id=\"place_hierarchy\" class=\"list_table $TEXT_DIRECTION\" ";
			if ($TEXT_DIRECTION=="rtl") {
				echo " dir=\"rtl\"";
			}
			echo ">\n\t\t<tr>\n\t\t";
			echo"<td class=\"list_label\" ";
			if ($ct1 > 20) {
				echo "colspan=\"3\"";
			} elseif ($ct1 > 4) {
				echo "colspan=\"2\"";
			}
			echo ">&nbsp;";
			echo "<img src=\"", $WT_IMAGES["place"], "\" border=\"0\" title=\"", WT_I18N::translate('Place'), "\" alt=\"", WT_I18N::translate('Place'), "\" />&nbsp;&nbsp;";
			if ($level>0) {
				echo /* I18N: %s is a country or region */WT_I18N::translate('Places in %s', $num_place);
			} else {
				echo WT_I18N::translate('Place hierarchy');
			}
			echo "</td></tr><tr><td class=\"list_value\"><ul>";
		}

		echo "<li type=\"square\">\n<a href=\"?action=", $action, "&amp;level=", $level+1, $linklevels;
		echo "&amp;parent[", $level, "]=", urlencode($value), "\" class=\"list_item\">";

		if (trim($value)=="") echo WT_I18N::translate('unknown');
		else echo PrintReady($value);
		if ($use_googlemap) $place_names[$i]=trim($value);
		echo "</a></li>\n";
		if ($ct1 > 20) {
			if ($i == floor($ct1 / 3)) {
				echo "\n\t\t</ul></td>\n\t\t<td class=\"list_value\"><ul>";
			}
			if ($i == floor(($ct1 / 3) * 2)) {
				echo "\n\t\t</ul></td>\n\t\t<td class=\"list_value\"><ul>";
			}
		} elseif ($ct1 > 4 && $i == floor($ct1 / 2)) {
			echo "\n\t\t</ul></td>\n\t\t<td class=\"list_value\"><ul>";
		}
		$i++;
	}
	if ($i>0) {
		echo "\n\t\t</ul></td></tr>";
		if (($action!="show")&&($level>0)) {
			echo "<tr>\n\t\t<td class=\"list_label\" ";
			if ($ct1 > 20) {
				echo "colspan=\"3\"";
			} elseif ($ct1 > 4) {
				echo "colspan=\"2\"";
			}
			echo ">\n\t";
			echo WT_I18N::translate('View all records found in this place');
			echo help_link('ppp_view_records');
			echo "</td></tr><tr><td class=\"list_value\" ";
			if ($ct1 > 20) {
				echo "colspan=\"3\"";
			} elseif ($ct1 > 4) {
				echo "colspan=\"2\"";
			}
			echo " style=\"text-align: center;\">";
			echo "<a href=\"?action=show&amp;level=", $level, "";
			foreach ($parent as $key=>$value) {
				echo "&amp;parent[", $key, "]=", urlencode(trim($value));
			}
			echo "\"><span class=\"formField\">";
			if (trim($value)=="") {
				echo WT_I18N::translate('unknown');
			} else {
				echo PrintReady($value);
			}
			echo "</span></a> ";
			echo "</td></tr>";
		}
		echo "</table>";
	}
	echo "</td></tr></table>";
}

$positions = get_place_positions($parent, $level);
if ($level > 0) {
	if ($action=="show") {
		// -- array of names
		$myindilist = array();
		$mysourcelist = array();
		$myfamlist = array();
		foreach ($positions as $position) {
			$record=WT_GedcomRecord::getInstance($position);
			switch ($record->getType()) {
			case 'INDI':
				$myindilist[]=$record;
				break;
			case 'SOUR':
				$mysourcelist[]=$record;
				break;
			case 'FAM':
				$myfamlist[]=$record;
				break;
			}
		}
		echo "<br />";
		$title = ""; foreach ($parent as $k=>$v) $title = $v.", ".$title;
		$title = PrintReady(substr($title, 0, -2))." ";
		// Sort each of the tables by Name
		usort($myindilist,   array('WT_GedcomRecord', 'Compare'));
		usort($myfamlist,    array('WT_GedcomRecord', 'Compare'));
		usort($mysourcelist, array('WT_GedcomRecord', 'Compare'));
		// echo each of the tables
		print_indi_table($myindilist,   WT_I18N::translate('Individuals').' @ '.$title);
		print_fam_table ($myfamlist,    WT_I18N::translate('Families'   ).' @ '.$title);
		print_sour_table($mysourcelist, WT_I18N::translate('Sources'    ).' @ '.$title);
	}
}

//-- list type display
if ($display=="list") {
	$placelist = array();

	$placelist=find_place_list("");
	$placelist = array_unique($placelist);
	uasort($placelist, "utf8_strcasecmp");
	if (count($placelist)==0) {
		echo "<b>", WT_I18N::translate('No results found.'), "</b><br />";
	} else {
		echo "\n\t<table class=\"list_table $TEXT_DIRECTION\"";
		if ($TEXT_DIRECTION=="rtl") echo " dir=\"rtl\"";
		echo ">\n\t\t<tr>\n\t\t<td class=\"list_label\" ";
		$ct = count($placelist);
		echo " colspan=\"", $ct>20 ? "3" : "2", "\">&nbsp;";
		echo "<img src=\"", $WT_IMAGES["place"], "\" border=\"0\" title=\"", WT_I18N::translate('Place'), "\" alt=\"", WT_I18N::translate('Place'), "\" />&nbsp;&nbsp;";
		echo WT_I18N::translate('Place List');
		echo help_link('ppp_placelist');
		echo "</td></tr><tr><td class=\"list_value_wrap\"><ul>";
		$i=0;
		foreach ($placelist as $indexval => $revplace) {
			$linklevels = "";
			$levels = explode(',', $revplace); // -- split the place into comma seperated values
			$level=0;
			$revplace = "";
			foreach ($levels as $indexval => $place) {
				$place = trim($place);
				$linklevels .= "&amp;parent[$level]=".urlencode($place);
				$level++;
				if ($level>1) $revplace .= ", ";
				if ($place=="") $revplace .= WT_I18N::translate('unknown');
				else $revplace .= $place;
			}
			echo "<li type=\"square\"><a href=\"?action=show&amp;display=hierarchy&amp;level=", $level, $linklevels, "\">";
			echo PrintReady($revplace), "</a></li>\n";
			$i++;
			if ($ct > 20) {
				if ($i == floor($ct / 3)) echo "\n\t\t</ul></td>\n\t\t<td class=\"list_value_wrap\"><ul>";
				if ($i == floor(($ct / 3) * 2)) echo "\n\t\t</ul></td>\n\t\t<td class=\"list_value_wrap\"><ul>";
			}
			else if ($i == floor($ct/2)) echo "</ul></td><td class=\"list_value_wrap\"><ul>\n\t\t\t";
		}
		echo "\n\t\t</ul></td></tr>\n\t\t";
		if ($i>1) {
			echo "<tr><td>";
			if ($i>0) echo WT_I18N::translate('Total unique places'), " ", $i;
			echo "</td></tr>\n";
		}
		echo "\n\t\t</table>";
	}
}

echo "<br /><a href=\"?display=";
if ($display=="list") echo "hierarchy\">", WT_I18N::translate('Show Places in Hierarchy');
else echo "list\">", WT_I18N::translate('Show All Places in a List');
echo "</a><br /><br />\n";
if ($hasplaceform) {
	$placeheader = substr($header, $hasplaceform);
	$ct = preg_match("/2 FORM (.*)/", $placeheader, $match);
	if ($ct>0) {
		echo WT_I18N::translate('Places are encoded in the form: ').$match[1];
		echo help_link('ppp_match_one');
	}
}
else {
	echo WT_I18N::translate('Places are encoded in the form: '), WT_I18N::translate('City, County, State/Province, Country'), "  ", WT_I18N::translate('(Default)'), help_link('ppp_default_form');
}
echo "<br /><br /></div>";

if ($use_googlemap && $display=="hierarchy") map_scripts($numfound, $level, $parent, $linklevels, $placelevels, $place_names);
print_footer();
