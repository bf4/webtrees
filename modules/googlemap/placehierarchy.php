<?php
/**
 * Displays a place hierachy
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
 * @package PhpGedView
 * @subpackage Googlemap
 * $Id: placehierarchy.php 2972 2008-05-02 14:56:16Z wooc $
 * @author £ukasz Wileñski Apr 2008
 */

if (file_exists('modules/googlemap/defaultconfig.php')) {
	require("modules/googlemap/defaultconfig.php");
	require "modules/googlemap/googlemap.php";
}

function get_place_list_loc($parent_id, $inactive=false) {
	global $TBLPREFIX, $DBCONN;
	if ($inactive)
		$sql="SELECT pl_id,pl_place,pl_lati,pl_long,pl_icon FROM {$TBLPREFIX}placelocation WHERE pl_parent_id=".$DBCONN->escapeSimple($parent_id)." ORDER BY pl_place";
	else
		$sql="SELECT DISTINCT pl_id,pl_place,pl_lati,pl_long,pl_icon FROM {$TBLPREFIX}placelocation INNER JOIN {$TBLPREFIX}places ON {$TBLPREFIX}placelocation.pl_place={$TBLPREFIX}places.p_place AND {$TBLPREFIX}placelocation.pl_level=".$TBLPREFIX."places.p_level WHERE pl_parent_id=".$DBCONN->escapeSimple($parent_id)." ORDER BY pl_place";
	
	$res=dbquery($sql);
	$placelist2=array();
	while ($row=&$res->fetchRow())
		$placelist2[]=array("place_id"=>$row[0], "place"=>$row[1], "lati"=>$row[2], "long"=>$row[3], "icon"=>$row[4]);
	$res->free();
	return $placelist2;
}

function place_id_to_hierarchy($id) {
	global $DBCONN, $TBLPREFIX, $pgv_lang;
	$arr=array();
	while ($id!=0) {
		$sql="SELECT pl_parent_id, pl_place FROM {$TBLPREFIX}placelocation WHERE pl_id=".$DBCONN->escapeSimple($id);
		$res=dbquery($sql);
		$row=&$res->fetchRow();
		$res->free();
		$arr=array($id=>$row[1])+$arr;
		$id=$row[0];
	}
	return $arr;
}

function get_placeid($place) {
	global $DBCONN, $TBLPREFIX;
	$par = explode (",", $place);
	$par = array_reverse($par);
	$place_id = 0;
	for($i=0; $i<count($par); $i++) {
		$par[$i] = trim($par[$i]);
		if (empty($par[$i])) $par[$i]="unknown";
		$placelist = create_possible_place_names($par[$i], $i+1);
		foreach ($placelist as $key => $placename) {
			$escparent=preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($placename));
			$psql = "SELECT pl_id FROM {$TBLPREFIX}placelocation WHERE pl_level={$i} AND pl_parent_id={$place_id} AND pl_place LIKE '{$escparent}' ORDER BY pl_place";
			$res = dbquery($psql);
			$row =& $res->fetchRow();
			$res->free();
			if (!empty($row[0])) break;
		}
		if (empty($row[0])) break;
		$place_id = $row[0];
	}
	return $place_id;
}

function set_levelm($level, $parent) {
	if (!isset($levelm)) {
		$levelm=0;
	}
	$fullplace = "";
	for ($i=1; $i<=$level; $i++) {
		if ($parent[$level-$i]!="")
			$fullplace .= $parent[$level-$i].", ";
		else 
			$fullplace .= "Unknown, ";
	}
	$fullplace = substr($fullplace,0,-2);
	$levelm = get_placeid($fullplace);
	return $levelm;
}

function create_map($numfound, $level, $levelm) {
	global $GOOGLEMAP_PH_XSIZE, $GOOGLEMAP_PH_YSIZE, $GOOGLEMAP_MAP_TYPE, $TEXT_DIRECTION, $pgv_lang;
	// create the map
	//<!-- start of map display -->
	print "\n<br /><br />\n";
	print "<table class=\"width80\"><tr valign=\"top\"><td class=\"center\"><div id=\"place_map\" style=\"width: ".$GOOGLEMAP_PH_XSIZE."px; height: ".$GOOGLEMAP_PH_YSIZE."px;\"></div>";
	print "<table style=\"width: ".$GOOGLEMAP_PH_XSIZE."px\">";
	if ($TEXT_DIRECTION=="ltr") print "<td align=\"left\">";
	else print "<td align=\"right\">";
	print "<a href=\"javascript:";
	if ($numfound>1)
		print "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds));";
	else if ($level==1)
		print "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds)-8);";
	else if ($level==2)
		print "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds)-5);";
	else
		print "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds)-3);";
	print "\">".$pgv_lang["gm_redraw_map"]."</a></td>\n";
	print "<td></td>\n";
	if ($TEXT_DIRECTION=="ltr") print "<td align=\"right\">\n";
	else print "<td align=\"left\">\n";
	print "<a href=\"javascript:place_map.setMapType(G_NORMAL_MAP)\">".$pgv_lang["gm_map"]."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	print "<a href=\"javascript:place_map.setMapType(G_SATELLITE_MAP)\">".$pgv_lang["gm_satellite"]."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	print "<a href=\"javascript:place_map.setMapType(G_HYBRID_MAP)\">".$pgv_lang["gm_hybrid"]."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	print "<a href=\"javascript:place_map.setMapType(G_PHYSICAL_MAP)\">".$pgv_lang["gm_physical"]."</a>\n";
	print "</td>\n";
	if (userIsAdmin(getUserName())) {
		print "<tr><td align=\"left\">\n";
		print "<a href=\"module.php?mod=googlemap&pgvaction=placecheck\">".$pgv_lang["placecheck"]."</a>";
		print "</td>\n";
		print "<td align=\"center\">\n";
		print "<a href=\"module.php?mod=googlemap&amp;pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
		print "</td>\n";
		print "<td align=\"right\">\n";
		print "<a href=\"module.php?mod=googlemap&pgvaction=places\">".$pgv_lang["edit_place_locations"]."</a>";
		print "</td></tr>\n";
	}
	print "</table>\n";
}

function check_were_am_i($numls, $levelm) {
	$where_am_i=place_id_to_hierarchy($levelm);
	$i=$numls+1;
	if (!isset($levelo)) {
		$levelo[0]=0;
	}
	foreach (array_reverse($where_am_i, true) as $id=>$place2) {
		$levelo[$i]=$id;
		$i--;
	}
	return $levelo;
}

function check_place($place_names, $place) {
	if ($place == "Unknown") $place="";
	if (in_array($place, $place_names)) {
		return true;
	}
}

function get_i_list() {
	global $indilist, $TBLPREFIX, $INDILIST_RETRIEVED;
	if ($INDILIST_RETRIEVED)
		return $indilist;
	else {
		$indilist = array();
		$sql = "SELECT i_id FROM {$TBLPREFIX}individuals WHERE i_file=".PGV_GED_ID;
		$res = dbquery($sql);
		while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$indi = array();
			$indilist[$row["i_id"]] = $indi;
		}
		$res->free();
		$INDILIST_RETRIEVED = true;
		return $indilist;
	}
}

function get_f_list() {
	global $famlist, $TBLPREFIX, $FAMLIST_RETRIEVED;
	if ($FAMLIST_RETRIEVED)
		return $famlist;
	else {
		$famlist = array();
		$sql = "SELECT f_id FROM {$TBLPREFIX}families WHERE f_file=".PGV_GED_ID;
		$res = dbquery($sql);
		while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$fam = array();
			$famlist[$row["f_id"]] = $fam;
		}
		$res->free();
		$FAMLIST_RETRIEVED = true;
		return $famlist;
	}
}

function print_how_many_people($place_name) {
	global $pgv_lang;
	$place_name = str_replace(array(', ', $pgv_lang["unknown"], ' ,'), array(',', '', ','), $place_name);
	$place_count=array();
	$place_count_indi = 0;
	$place_count_fam = 0;
	foreach (array_keys(get_i_list()) as $id) {
		$gedrec = find_person_record($id);
		if (preg_match_all("/2 PLAC (.*)/", $gedrec, $matches)) {
			foreach ($matches[1] as $nazwa) {
				$nazwa = str_replace(array(', ', ' ,'), array(',', ','), $nazwa);
				if (substr_count ($nazwa, $place_name)) {
					$place_count_indi ++;
					break;
				}
			}
		}
	}
	foreach (array_keys(get_f_list()) as $id) {
		$gedrec = find_family_record($id);
		if (preg_match_all("/2 PLAC (.*)/", $gedrec, $matches)) {
			foreach ($matches[1] as $nazwa) {
				$nazwa = str_replace(array(', ', ' ,'), array(',', ','), $nazwa);
				if (substr_count ($nazwa, $place_name)) {
					$place_count_fam ++;
					break;
				}
			}
		}
	}
	echo "<br /><br />".$pgv_lang["stat_individuals"].": ".$place_count_indi.", ".$pgv_lang["stat_families"].": ".$place_count_fam;
}

function print_gm_markers($place2, $level, $levelm, $linklevels, $placelevels, $lastlevel=false){
	global $GOOGLEMAP_COORD, $GOOGLEMAP_PH_MARKER, $GM_DISP_SHORT_PLACE, $GM_DISP_COUNT, $pgv_lang;
	if (($place2['lati'] == NULL) || ($place2['long'] == NULL) || (($place2['lati'] == "0") && ($place2['long'] == "0"))) {
		echo "var point = new GLatLng(0,0);\n";
		if ($lastlevel)
			echo "var marker = createMarker(point, \"<div class='iwstyle' style='width: 95%;'><a href='?level=".$level.$linklevels."'><br />";
		else {
			echo "var marker = createMarker(point, \"<div class='iwstyle' style='width: 95%;'><a href='?level=".($level+1).$linklevels."&amp;parent[{$level}]=";
			if ($place2["place"] == "Unknown") echo "'><br />";
			else echo urlencode($place2["place"])."'><br />";
		}
		if (($place2["icon"] != NULL) && ($place2["icon"] != "")) {
			echo "<img src=\'".$place2["icon"]."'>&nbsp;&nbsp;";
		}
		if ($lastlevel) {
			$placename = substr($placelevels,2);
			if ($place2["place"] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo substr($placelevels,2);
				else echo $pgv_lang["unknown"];
			else 
				if ($GM_DISP_SHORT_PLACE == "false") echo substr($placelevels,2);
				else echo PrintReady($place2["place"]);
		}
		else {
			$placename = $place2["place"].$placelevels;
			if ($place2["place"] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo $pgv_lang["unknown"].$placelevels;
				else echo $pgv_lang["unknown"];
			else 
				if ($GM_DISP_SHORT_PLACE == "false") echo PrintReady($place2["place"]).$placelevels;
				else echo PrintReady($place2["place"]);
		}
		echo "</a>";
		if ($GM_DISP_COUNT != "false") print_how_many_people($placename);
		echo "<br /><br />".$pgv_lang["gm_no_coord"];
		if (userIsAdmin(getUserName())) 
			echo "<br /><a href='module.php?mod=googlemap&pgvaction=places&parent=".$levelm."&display=inactive'>".$pgv_lang["pl_edit"]."</a>";
		echo "</div>\");\n";
	}
	else {
		$lati = str_replace(array('N', 'S', ','), array('', '-', '.'), $place2['lati']);
		$long = str_replace(array('E', 'W', ','), array('', '-', '.'), $place2['long']);
		// flags by kiwi_pgv
		if (($place2["icon"] == NULL) || ($place2["icon"] == "") || ($GOOGLEMAP_PH_MARKER != "G_FLAG")) {
			echo "var icon_type = new GIcon(G_DEFAULT_ICON);\n";
		} else {
			echo "var icon_type = new GIcon();\n";
			echo "    icon_type.image = \"".$place2['icon']."\";\n";
			echo "    icon_type.shadow = \"modules/googlemap/flag_shadow.png\";\n";
			echo "    icon_type.iconSize = new GSize(25, 15);\n";
			echo "    icon_type.shadowSize = new GSize(35, 45);\n";
			echo "    icon_type.iconAnchor = new GPoint(1, 45);\n";
			echo "    icon_type.infoWindowAnchor = new GPoint(5, 1);\n";
		}
		echo "var point = new GLatLng({$lati},{$long});\n";
		if ($lastlevel)
			echo "var marker = createMarker(point, \"<div class='iwstyle' style='width: 95%;'><a href='?level=".$level.$linklevels."'><br />";
		else {
			echo "var marker = createMarker(point, \"<div class='iwstyle' style='width: 95%;'><a href='?level=".($level+1).$linklevels."&amp;parent[{$level}]=";
			if ($place2["place"] == "Unknown") echo "'><br />";
			else echo urlencode($place2["place"])."'><br />";
		}
		if (($place2["icon"] != NULL) && ($place2["icon"] != "")) {
			echo "<img src=\'".$place2["icon"]."'>&nbsp;&nbsp;";
		}
		if ($lastlevel) {
			$placename = substr($placelevels,2);
			if ($place2["place"] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo substr($placelevels,2);
				else echo $pgv_lang["unknown"];
			else 
				if ($GM_DISP_SHORT_PLACE == "false") echo substr($placelevels,2);
				else echo PrintReady($place2["place"]);
		}
		else {
			$placename = $place2["place"].$placelevels;
			if ($place2["place"] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo $pgv_lang["unknown"].$placelevels;
				else echo $pgv_lang["unknown"];
			else 
				if ($GM_DISP_SHORT_PLACE == "false") echo PrintReady($place2["place"]).$placelevels;
				else echo PrintReady($place2["place"]);
		}
		echo "<br /></a><br />";
		if ($GM_DISP_COUNT != "false") print_how_many_people($placename);
		if ($GOOGLEMAP_COORD == "false"){
			echo "</div>\", icon_type);\n";
		}
		else {
			echo "<br /><br />".$place2['lati'].", ".$place2['long']."</div>\", icon_type);\n";
		}
	}
	echo "place_map.addOverlay(marker);\n";
	echo "bounds.extend(point);\n";
}

function map_scripts($numfound, $level, $levelm, $levelo, $linklevels, $placelevels, $place_names) {
	global $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GM_MAX_NOF_LEVELS, $GOOGLEMAP_PH_WHEEL, $pgv_lang;
	?>
	<!-- Start of map scripts -->
	<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php print $GOOGLEMAP_API_KEY; ?>" type="text/javascript"></script>
	<script src="modules/googlemap/pgvGoogleMap.js" type="text/javascript"></script>
	<script type="text/javascript">
	// <![CDATA[
	if (window.attachEvent) {
		window.attachEvent("onunload", function() {
			GUnload();      // Internet Explorer
		});
	} else {
		window.addEventListener("unload", function() {
			GUnload(); // Firefox and standard browsers
		}, false);
	}
	if (GBrowserIsCompatible()) {
	// Creates a marker whose info window displays the given name
	function createMarker(point, name, icon)
	{
		var marker = new GMarker(point, icon);
		// Show this markers name in the info window when it is clicked
		var html = name;
		GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
		return marker;
	};

	// create the map
	var place_map = new GMap2(document.getElementById("place_map"));
	var bounds = new GLatLngBounds();
	<?php
	if ($numfound<2 && ($level==1 || !(isset($levelo[($level-1)])))){
		echo "zoomlevel = place_map.getBoundsZoomLevel(bounds);\n";
		echo "	place_map.setCenter(new GLatLng(0,0),zoomlevel+5);\n";
	}
	else if ($numfound<2 && !isset($levelo[($level-2)])){
		echo "zoomlevel = place_map.getBoundsZoomLevel(bounds);\n";
		echo "	place_map.setCenter(new GLatLng(0,0),zoomlevel+6);\n";
	}
	else if ($level==2){
		echo "zoomlevel = place_map.getBoundsZoomLevel(bounds);\n";
		echo "	place_map.setCenter(new GLatLng(0,0),zoomlevel+8);\n";
	}
	else if ($numfound<2 && $level>1){
		echo "zoomlevel = place_map.getBoundsZoomLevel(bounds);\n";
		echo "	place_map.setCenter(new GLatLng(0,0),zoomlevel+10);\n";
	}
	else 
		echo "place_map.setCenter(new GLatLng(0,0),1);\n";
	?>
	place_map.addControl(new GSmallMapControl());
	//create markers
	<?php
	if ($GOOGLEMAP_PH_WHEEL != "false") echo "place_map.enableScrollWheelZoom();\n";
	echo "place_map.setMapType($GOOGLEMAP_MAP_TYPE);\n";
	if ($numfound==0 && $level>0) {
		if (isset($levelo[($level-1)])) {
			$placelist2=get_place_list_loc($levelo[($level-1)]);
			if (!empty($placelist2)) {
				//lastlevel place
				foreach ($placelist2 as $place2) {
					if (isset($levelo[$level])) {
						if ($place2['place_id']==$levelo[$level])
							print_gm_markers($place2, $level, $levelo[($level-1)], $linklevels, $placelevels, true);
					}
				}
			}
			else {
				//lastlevel place not in table
				$placelist2=get_place_list_loc($levelo[($level-1)], true);
				foreach ($placelist2 as $place2) {
					if (isset($levelo[$level])) {
						if ($place2['place_id']==$levelo[$level])
							print_gm_markers($place2, $level, $levelo[$level], $linklevels, $placelevels, true);
					}
				}
			}
		}
	}
	else {
		//place from table
		$placelist2=get_place_list_loc($levelm);
		if (!empty($placelist2)) {
			foreach ($placelist2 as $place2) {
				if (check_place($place_names, $place2["place"]))
					print_gm_markers($place2, $level, $levelm, $linklevels, $placelevels);
			}
		}
		else if ($level>0){ //if unknown place display the upper level place
			$placelevels = ", ".$pgv_lang["unknown"].$placelevels;
			$linklevels .= "&amp;parent[".$level."]=";
			for ($i=1;$i<=$GM_MAX_NOF_LEVELS;$i++)
			if (($level-$i)>=0 && isset($levelo[($level-$i)])) {
				$placelist2=get_place_list_loc($levelo[($level-$i)], true);
				foreach ($placelist2 as $place2) {
					if ($place2["place"]!="Unknown" || (($place2['lati'] != NULL) && ($place2['long'] != NULL))) {
						if (isset ($levelo[$level-$i+1]) && $place2['place_id']==$levelo[$level-$i+1])
							print_gm_markers($place2, ($level+1), $levelm, $linklevels, $placelevels, true);
					}
				}
			}
		}
	}
	?>
	//end markers
	place_map.setCenter(bounds.getCenter());
	<?php
	if ($numfound>1)
		echo "place_map.setZoom(place_map.getBoundsZoomLevel(bounds));\n";
	?>
	} else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
	}
    // This Javascript is based on code provided by the
    // Blackpool Community Church Javascript Team
    // http://www.commchurch.freeserve.co.uk/   
    // http://econym.googlepages.com/index.htm
	//]]>
	//version 0.9.9
	</script>
	<?php
}
?>