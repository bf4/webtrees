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
 * @author Łukasz Wileński <wooc@users.sourceforge.net>
 * $Id$
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

function create_map() {
	global $GOOGLEMAP_PH_XSIZE, $GOOGLEMAP_PH_YSIZE, $GOOGLEMAP_MAP_TYPE, $TEXT_DIRECTION, $pgv_lang;
	// create the map
	//<!-- start of map display -->
	print "\n<br /><br />\n";
	print "<table class=\"width80\"><tr valign=\"top\"><td class=\"center\">";
	print "<div id=\"place_map\" style=\"border: 1px solid gray; width: ".$GOOGLEMAP_PH_XSIZE."px; height: ".$GOOGLEMAP_PH_YSIZE."px; ";
	print "background-image: url('images/loading.gif'); background-position: center; background-repeat: no-repeat; overflow: hidden;\"></div>";
	print "<table style=\"width: ".$GOOGLEMAP_PH_XSIZE."px\">";
	if (userIsAdmin(getUserName())) {
		print "<tr><td align=\"left\">\n";
		print "<a href=\"module.php?mod=googlemap&amp;pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
		print "</td>\n";
		print "<td align=\"center\">\n";
		print "<a href=\"module.php?mod=googlemap&pgvaction=places\">".$pgv_lang["edit_place_locations"]."</a>";
		print "</td>\n";
		print "<td align=\"right\">\n";
		print "<a href=\"module.php?mod=googlemap&pgvaction=placecheck\">".$pgv_lang["placecheck"]."</a>";
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

function print_how_many_people($level, $parent) {
	global $pgv_lang, $positions;
	$place_count_indi = 0;
	$place_count_fam = 0;
	$positions = array();
	if (!isset($parent[$level-1])) $parent[$level-1]="";
	$positions = get_place_positions($parent, $level);
	for($i=0; $i<count($positions); $i++) {
		$gid = $positions[$i];
		$indirec=find_gedcom_record($gid);
		$ct = preg_match("/0 @(.*)@ (.*)/", $indirec, $match);
		if ($ct>0) {
			$type = trim($match[2]);
			if ($type == "INDI") {
				$place_count_indi ++;
			}
			else if ($type == "FAM") {
				$place_count_fam ++;
			}
		}
	}
	echo "<br /><br />".$pgv_lang["stat_individuals"].": ".$place_count_indi.", ".$pgv_lang["stat_families"].": ".$place_count_fam;
}

function print_gm_markers($place2, $level, $parent, $levelm, $linklevels, $placelevels, $lastlevel=false){
	global $GOOGLEMAP_COORD, $GOOGLEMAP_PH_MARKER, $GM_DISP_SHORT_PLACE, $GM_DISP_COUNT, $pgv_lang;
	if (($place2['lati'] == NULL) || ($place2['long'] == NULL) || (($place2['lati'] == "0") && ($place2['long'] == "0"))) {
		echo "var point = new GLatLng(0,0);\n";
		if ($lastlevel)
			echo "var marker = createMarker(point, \"<td width='100%'><div class='iwstyle' style='width: 250px;'><a href='?level=".$level.$linklevels."'><br />";
		else {
			echo "var marker = createMarker(point, \"<td width='100%'><div class='iwstyle' style='width: 250px;'><a href='?level=".($level+1).$linklevels."&amp;parent[{$level}]=";
			if ($place2['place'] == "Unknown") echo "'><br />";
			else echo urlencode($place2['place'])."'><br />";
		}
		if (($place2["icon"] != NULL) && ($place2['icon'] != "")) {
			echo "<img src=\'".$place2['icon']."'>&nbsp;&nbsp;";
		}
		if ($lastlevel) {
			$placename = substr($placelevels,2);
			if ($place2['place'] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo addslashes(substr($placelevels,2));
				else echo $pgv_lang["unknown"];
			else
				if ($GM_DISP_SHORT_PLACE == "false") echo addslashes(substr($placelevels,2));
				else echo PrintReady(addslashes($place2['place']));
		}
		else {
			$placename = $place2['place'].$placelevels;
			if ($place2['place'] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo PrintReady(addslashes($pgv_lang["unknown"].$placelevels));
				else echo $pgv_lang["unknown"];
			else
				if ($GM_DISP_SHORT_PLACE == "false") echo PrintReady(addslashes($place2['place'].$placelevels));
				else echo PrintReady(addslashes($place2['place']));
		}
		echo "</a>";
		if ($GM_DISP_COUNT != "false") 
			if ($lastlevel)
				print_how_many_people($level, $parent);
			else {
				$parent[$level]=$place2['place'];
				print_how_many_people($level+1, $parent);
			}
		echo "<br />".$pgv_lang["gm_no_coord"];
		if (userIsAdmin(getUserName()))
			echo "<br /><a href='module.php?mod=googlemap&pgvaction=places&parent=".$levelm."&display=inactive'>".$pgv_lang["pl_edit"]."</a>";
		echo "</div></td>\");\n";
	}
	else {
		$lati = str_replace(array('N', 'S', ','), array('', '-', '.'), $place2['lati']);
		$long = str_replace(array('E', 'W', ','), array('', '-', '.'), $place2['long']);
		// flags by kiwi_pgv
		if (($place2["icon"] == NULL) || ($place2['icon'] == "") || ($GOOGLEMAP_PH_MARKER != "G_FLAG")) {
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
			echo "var marker = createMarker(point, \"<td width='100%'><div class='iwstyle' style='width: 250px;'><a href='?level=".$level.$linklevels."'><br />";
		else {
			echo "var marker = createMarker(point, \"<td width='100%'><div class='iwstyle' style='width: 250px;'><a href='?level=".($level+1).$linklevels."&amp;parent[{$level}]=";
			if ($place2['place'] == "Unknown") echo "'><br />";
			else echo urlencode($place2['place'])."'><br />";
		}
		if (($place2['icon'] != NULL) && ($place2['icon'] != "")) {
			echo "<img src=\'".$place2['icon']."'>&nbsp;&nbsp;";
		}
		if ($lastlevel) {
			$placename = substr($placelevels,2);
			if ($place2['place'] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo addslashes(substr($placelevels,2));
				else echo $pgv_lang["unknown"];
			else
				if ($GM_DISP_SHORT_PLACE == "false") echo addslashes(substr($placelevels,2));
				else echo PrintReady(addslashes($place2['place']));
		}
		else {
			$placename = $place2['place'].$placelevels;
			if ($place2['place'] == "Unknown")
				if ($GM_DISP_SHORT_PLACE == "false") echo PrintReady(addslashes($pgv_lang["unknown"].$placelevels));
				else echo $pgv_lang["unknown"];
			else
				if ($GM_DISP_SHORT_PLACE == "false") echo PrintReady(addslashes($place2['place'].$placelevels));
				else echo PrintReady(addslashes($place2['place']));
		}
		echo "</a>";
		if ($GM_DISP_COUNT != "false") 
			if ($lastlevel)
				print_how_many_people($level, $parent);
			else {
				$parent[$level]=$place2['place'];
				print_how_many_people($level+1, $parent);
			}
		if ($GOOGLEMAP_COORD == "false"){
			echo "<br /><br /></div></td>\", icon_type);\n";
		}
		else {
			echo "<br /><br />".$place2['lati'].", ".$place2['long']."</div></td>\", icon_type);\n";
		}
	}
	echo "place_map.addOverlay(marker);\n";
	echo "bounds.extend(point);\n";
}

function create_buttons($numfound, $level) {
	global $pgv_lang;
	?>
	</script>
	<style type="text/css">
	#map_type
	{
		margin: 0;
		padding: 0;
		font-family: Arial;
		font-size: 10px;
		list-style: none;
	}
	#map_type li
	{
		display: block;
		width: 70px;
		text-align: center;
		padding: 2px;
		border: 1px solid black;
		cursor: pointer;
		float: left;
		margin-left: 2px;
	}
	#map_type li.non_active
	{
		background: white;
		color: black;
		font-weight: normal;
	}
	#map_type li.active
	{
		background: gray;
		color: white;
		font-weight: bold;
	}
	#map_type li:hover
	{
		background: #ddd;
	}
	</style>
	<script type='text/javascript'>
	<!--
	function Map_type() {}
	Map_type.prototype = new GControl();

	Map_type.prototype.refresh = function()
	{
		this.button1.className = 'non_active';
		if(this.place_map.getCurrentMapType() != G_NORMAL_MAP)
			this.button2.className = 'non_active';
		else
			this.button2.className = 'active';
		if(this.place_map.getCurrentMapType() != G_SATELLITE_MAP)
			this.button3.className = 'non_active';
		else
			this.button3.className = 'active';
		if(this.place_map.getCurrentMapType() != G_HYBRID_MAP)
			this.button4.className = 'non_active';
		else
			this.button4.className = 'active';
		if(this.place_map.getCurrentMapType() != G_PHYSICAL_MAP)
			this.button5.className = 'non_active';
		else
			this.button5.className = 'active';
	}

	Map_type.prototype.initialize = function(place_map)
	{
		var list 	= document.createElement("ul");
		list.id	= 'map_type';

		var button1 = document.createElement('li');
		var button2 = document.createElement('li');
		var button3 = document.createElement('li');
		var button4 = document.createElement('li');
		var button5 = document.createElement('li');

		button1.innerHTML = '<?php echo $pgv_lang["gm_redraw_map"]?>';
		button2.innerHTML = '<?php echo $pgv_lang["gm_map"]?>';
		button3.innerHTML = '<?php echo $pgv_lang["gm_satellite"]?>';
		button4.innerHTML = '<?php echo $pgv_lang["gm_hybrid"]?>';
		button5.innerHTML = '<?php echo $pgv_lang["gm_physical"]?>';

		button1.onclick = function() { <?php
			if ($numfound>1)
				echo "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds));";
			else if ($level==1)
				echo "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds)-8);";
			else if ($level==2)
				echo "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds)-5);";
			else
				echo "place_map.setCenter(bounds.getCenter(),place_map.getBoundsZoomLevel(bounds)-3);";
			?>; return false; };
		button2.onclick = function() { place_map.setMapType(G_NORMAL_MAP); return false; };
		button3.onclick = function() { place_map.setMapType(G_SATELLITE_MAP); return false; };
		button4.onclick = function() { place_map.setMapType(G_HYBRID_MAP); return false; };
		button5.onclick = function() { place_map.setMapType(G_PHYSICAL_MAP); return false; };

		list.appendChild(button1);
		list.appendChild(button2);
		list.appendChild(button3);
		list.appendChild(button4);
		list.appendChild(button5);

		this.button1 = button1;
		this.button2 = button2;
		this.button3 = button3;
		this.button4 = button4;
		this.button5 = button5;
		this.place_map = place_map;
		place_map.getContainer().appendChild(list);
		return list;
	}

	Map_type.prototype.getDefaultPosition = function()
	{
		return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(2, 2));
	}
	var map_type;
	<?php
}

function map_scripts($numfound, $level, $parent, $linklevels, $placelevels, $place_names) {
	global $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GM_MAX_NOF_LEVELS, $GOOGLEMAP_PH_WHEEL, $GOOGLEMAP_PH_CONTROLS, $pgv_lang;
	$levelm = set_levelm($level, $parent);
	if (isset($levelo[0])) $levelo[0]=0;
	$numls = count($parent)-1;
	$levelo=check_were_am_i($numls, $levelm);
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
	<?php echo create_buttons($numfound, $level);?>
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
	map_type = new Map_type();
	place_map.addControl(map_type);
	GEvent.addListener(place_map,'maptypechanged',function()
	{
		map_type.refresh();
	});
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
	if ($GOOGLEMAP_PH_WHEEL != "false") echo "place_map.enableScrollWheelZoom();\n";
	echo "	place_map.setMapType($GOOGLEMAP_MAP_TYPE);\n";
	?>
	place_map.addControl(new GSmallMapControl());
	place_map.addControl(new GScaleControl());
	var mini = new GOverviewMapControl();
	place_map.addControl(mini);
	mini.hide();
	//create markers
	<?php
	if ($numfound==0 && $level>0) {
		if (isset($levelo[($level-1)])) {
			$placelist2=get_place_list_loc($levelo[($level-1)]);
			if (!empty($placelist2)) {
				//lastlevel place
				foreach ($placelist2 as $place2) {
					if (isset($levelo[$level])) {
						if ($place2['place_id']==$levelo[$level])
							print_gm_markers($place2, $level, $parent, $levelo[($level-1)], $linklevels, $placelevels, true);
					}
				}
			}
			else {
				//lastlevel place not in table
				$placelist2=get_place_list_loc($levelo[($level-1)], true);
				foreach ($placelist2 as $place2) {
					if (isset($levelo[$level])) {
						if ($place2['place_id']==$levelo[$level])
							print_gm_markers($place2, $level, $parent, $levelo[$level], $linklevels, $placelevels, true);
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
				if (check_place($place_names, $place2['place']))
					print_gm_markers($place2, $level, $parent, $levelm, $linklevels, $placelevels);
			}
		}
		else if ($level>0){ //if unknown place display the upper level place
			$placelevels = ", ".$pgv_lang["unknown"].$placelevels;
			$linklevels .= "&amp;parent[".$level."]=";
			for ($i=1;$i<=$GM_MAX_NOF_LEVELS;$i++)
			if (($level-$i)>=0 && isset($levelo[($level-$i)])) {
				$placelist2=get_place_list_loc($levelo[($level-$i)], true);
				foreach ($placelist2 as $place2) {
					if ($place2['place']!="Unknown" || (($place2['lati'] != NULL) && ($place2['long'] != NULL))) {
						if (isset ($levelo[$level-$i+1]) && $place2['place_id']==$levelo[$level-$i+1])
							print_gm_markers($place2, ($level+1), $parent, $levelm, $linklevels, $placelevels, true);
					}
				}
			}
		}
	}
	?>
	//end markers
	place_map.setCenter(bounds.getCenter());
	<?php if ($GOOGLEMAP_PH_CONTROLS != "false") {?>
	// hide controls
	GEvent.addListener(place_map,'mouseout',function()
	{
		place_map.hideControls();
	});
	// show controls
	GEvent.addListener(place_map,'mouseover',function()
	{
		place_map.showControls();
	});
	GEvent.trigger(place_map,'mouseout');
	<?php
	}
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
	//version 1.0
	</script>
	<?php
}
?>