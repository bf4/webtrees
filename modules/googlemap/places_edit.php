<?php
/**
 * Interface to edit place locations
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
 * @subpackage Edit
 * @version $Id$
 */

require_once "config.php";
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');
require "includes/functions_edit.php";
require $INDEX_DIRECTORY."pgv_changes.php";

loadLangFile("pgv_facts, gm_lang, gm_help");

print_simple_header($pgv_lang["edit_place_locations"]);

if (!PGV_USER_IS_ADMIN) {
	print "<table class=\"facts_table\">\n";
	print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_admin_error"];
	print "</td></tr></table>\n";
	print "<br/><br/><br/>\n";
	print_simple_footer();
	exit;
}

?>
<script type="text/javascript">
<!--
	function edit_close() {
		if (window.opener.showchanges) window.opener.showchanges();
		window.close();
	}

function showchanges() {
	updateMap();
}
//-->
</script>
<?php

// Take a place id and find its place in the hierarchy
// Input: place ID
// Output: ordered array of id=>name values, starting with the Top Level
// e.g. array(0=>"Top Level", 16=>"England", 19=>"London", 217=>"Westminster");
// NB This function exists in both places.php and places_edit.php
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

// NB This function exists in both places.php and places_edit.php
function getHighestIndex() {
	global $TBLPREFIX;
	$sql="SELECT MAX(pl_id) FROM {$TBLPREFIX}placelocation WHERE 1=1";
	$res=dbquery($sql);
	$row = $res->fetchRow();
	$res->free();
	if (empty($row[0]))
		return 0;
	else
		return $row[0];
}

$where_am_i=place_id_to_hierarchy($placeid);
$level=count($where_am_i);

if ($action=='addrecord') {
	if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
		// $_POST[] is already escaped by the framework, so no need to escapeSimple()
	if (($_POST['LONG_CONTROL'] == '') || ($_POST['NEW_PLACE_LONG'] == '') || ($_POST['NEW_PLACE_LATI'] == '')) {
		$sql = "INSERT INTO {$TBLPREFIX}placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".(getHighestIndex()+1).", {$placeid}, {$level}, '".$DBCONN->escapeSimple($_POST['NEW_PLACE_NAME'])."', '' , '', {$_POST['NEW_ZOOM_FACTOR']}, '{$_POST['icon']}');";
	} else {
		$sql = "INSERT INTO {$TBLPREFIX}placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".(getHighestIndex()+1).", {$placeid}, {$level}, '".$DBCONN->escapeSimple($_POST['NEW_PLACE_NAME'])."', '{$_POST['LONG_CONTROL'][3]}{$_POST['NEW_PLACE_LONG']}', '{$_POST['LATI_CONTROL'][3]}{$_POST['NEW_PLACE_LATI']}', {$_POST['NEW_ZOOM_FACTOR']}, '{$_POST['icon']}');";
	}
	if (PGV_USER_IS_ADMIN) {
		$res = dbquery($sql);
	}
	if ($EDIT_AUTOCLOSE and !$GLOBALS['DEBUG']) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";
	print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();return false;\">".$pgv_lang["close_window"]."</a></div><br />\n";
	print_simple_footer();
	exit;
}

if ($action=='updaterecord') {
	if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
	if (($_POST['LONG_CONTROL'] == '') || ($_POST['NEW_PLACE_LONG'] == '') || ($_POST['NEW_PLACE_LATI'] == '')) {
		$sql = "UPDATE {$TBLPREFIX}placelocation SET pl_place='{$_POST['NEW_PLACE_NAME']}', pl_lati='', pl_long='', pl_zoom={$_POST['NEW_ZOOM_FACTOR']}, pl_icon='{$_POST['icon']}' WHERE pl_id={$placeid}";
	} else {
		$sql = "UPDATE {$TBLPREFIX}placelocation SET pl_place='{$_POST['NEW_PLACE_NAME']}',pl_lati='{$_POST['LATI_CONTROL'][3]}{$_POST['NEW_PLACE_LATI']}', pl_long='{$_POST['LONG_CONTROL'][3]}{$_POST['NEW_PLACE_LONG']}',pl_zoom={$_POST['NEW_ZOOM_FACTOR']}, pl_icon='{$_POST['icon']}' WHERE pl_id={$placeid}";
	}
	if (PGV_USER_IS_ADMIN) {
		$res = dbquery($sql);
	}
	if ($EDIT_AUTOCLOSE and !$GLOBALS["DEBUG"]) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";
	print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();return false;\">".$pgv_lang["close_window"]."</a></div><br />\n";
	print_simple_footer();
	exit;
}

if ($action=="update") {
	// --- find the place in the file
	$sql="SELECT pl_place,pl_lati,pl_long,pl_icon,pl_parent_id,pl_level,pl_zoom FROM {$TBLPREFIX}placelocation WHERE pl_id={$placeid}";
	$res =dbquery($sql);
	$row=&$res->fetchRow();
	$res->free();
	$place_name = $row[0];
	$place_icon = $row[3];
	$parent_id  = $row[4];
	$level      = $row[5];
	$zoomfactor = $row[6];
	$parent_lati = "0.0";
	$parent_long = "0.0";
	if(($row[1] != NULL) && ($row[2] != NULL)) {
		$place_lati = (float)(str_replace(array('N', 'S', ','), array('', '-', '.') , $row[1]));
		$place_long = (float)(str_replace(array('E', 'W', ','), array('', '-', '.') , $row[2]));
		$show_marker = true;
	} else {
		$place_lati = null;
		$place_long = null;
		$zoomfactor = 1;
		$show_marker = false;
	}

	do {
		$sql="SELECT pl_lati,pl_long,pl_parent_id,pl_zoom FROM {$TBLPREFIX}placelocation WHERE pl_id={$parent_id}";
		$res=dbquery($sql);
		$row=&$res->fetchRow();
		$res->free();
		if(($row[0] != NULL) && ($row[1] != NULL)) {
			$parent_lati = (float)(str_replace(array('N', 'S', ','), array('', '-', '.') , $row[0]));
			$parent_long = (float)(str_replace(array('E', 'W', ','), array('', '-', '.') , $row[1]));
			if ($zoomfactor == 1) {
				$zoomfactor = $row[3];
			}
		}
		$parent_id = $row[2];
	} while ($row[2]!=0 && $row[0]==NULL && $row[1]==NULL);

	$success = false;

	print "<b>".PrintReady(implode(', ', array_reverse($where_am_i, true)))."</b><br />";
}

if ($action=="add") {
	// --- find the parent place in the file
	if ($placeid <> 0) {
		$place_name = "";
		$place_lati = null;
		$place_long = null;
		$zoomfactor = 1;
		$parent_lati = "0.0";
		$parent_long = "0.0";
		$place_icon = "";
		$parent_id=$placeid;
		do {
			$sql = "SELECT pl_lati,pl_long,pl_parent_id,pl_zoom,pl_level FROM {$TBLPREFIX}placelocation WHERE pl_id={$parent_id}";
			$res = dbquery($sql);
			$row =& $res->fetchRow();
			if(($row[0] != NULL) && ($row[1] != NULL)) {
				$parent_lati = str_replace(array('N', 'S', ','), array('', '-', '.') , $row[0]);
				$parent_long = str_replace(array('E', 'W', ','), array('', '-', '.') , $row[1]);
				$zoomfactor = $row[3]+2;
				$level      = $row[4]+1;
			}
			$parent_id = $row[2];
			$res->free();
		} while (($row[2] != 0) && ($row[0] == NULL) && ($row[1] == NULL));
	}
	else {
		$place_name  = "";
		$place_lati  = null;
		$place_long  = null;
		$parent_lati = "0.0";
		$parent_long = "0.0";
		$place_icon  = "";
		$parent_id   = 0;
		$level       = 0;
		$zoomfactor  = 1;
	}

	$show_marker = false;
	$success = false;

	print "<b>{$pgv_lang['unknown']}";
	if (count($where_am_i)>0)
		print ", ".PrintReady(implode(', ', array_reverse($where_am_i, true)));
	print "</b><br />";
}

?>

<script type="text/javascript" src="http://ws.geonames.org/export/jsr_class.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php print $GOOGLEMAP_API_KEY?>" type="text/javascript"></script>

<script type="text/javascript">
<!--
	if (window.attachEvent) {
		window.attachEvent("onload", function() {
			loadMap();      // Internet Explorer
		});
		window.attachEvent("onunload", function() {
			GUnload();      // Internet Explorer
		});
	} else {
		window.addEventListener("load", function() {
			loadMap(); // Firefox and standard browsers
		}, false);
		window.addEventListener("unload", function() {
			GUnload(); // Firefox and standard browsers
		}, false);
	}
	var childplaces = [];

	function updateMap() {
		var point;
		var zoom;
		var latitude;
		var longitude;
		var i;

		document.editplaces.save1.disabled = "";
		document.editplaces.save2.disabled = "";
		zoom = parseInt(document.editplaces.NEW_ZOOM_FACTOR.value);

		prec = 20;
		for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
			if (document.editplaces.NEW_PRECISION[i].checked) {
				prec = document.editplaces.NEW_PRECISION[i].value;
			}
		}
		if ((document.editplaces.NEW_PLACE_LATI.value == "") ||
			(document.editplaces.NEW_PLACE_LONG.value == "")) {
			latitude = parseFloat(document.editplaces.parent_lati.value).toFixed(prec);
			longitude = parseFloat(document.editplaces.parent_long.value).toFixed(prec);
			point = new GLatLng (latitude, longitude);
			map.clearOverlays();
		} else {
			latitude = parseFloat(document.editplaces.NEW_PLACE_LATI.value).toFixed(prec);
			longitude = parseFloat(document.editplaces.NEW_PLACE_LONG.value).toFixed(prec);
			document.editplaces.NEW_PLACE_LATI.value = latitude;
			document.editplaces.NEW_PLACE_LONG.value = longitude;

			if (latitude < 0.0) {
				latitude = latitude * -1;
				document.editplaces.NEW_PLACE_LATI.value = latitude;
			}
			if (longitude < 0.0) {
				longitude = longitude * -1;
				document.editplaces.NEW_PLACE_LONG.value = longitude;
			}
			if(document.editplaces.LATI_CONTROL.value == "PL_S") {
				latitude = latitude * -1;
			}
			if(document.editplaces.LONG_CONTROL.value == "PL_W") {
				longitude = longitude * -1;
			}

			point = new GLatLng (latitude, longitude);

			map.clearOverlays();

			if (document.editplaces.icon.value == "") {
				map.addOverlay(new GMarker(point));
			}
			else {
				var flagicon = new GIcon();
				flagicon.image = document.editplaces.icon.value;
				flagicon.shadow = "modules/googlemap/flag_shadow.png";
				flagicon.iconSize = new GSize(25, 15);
				flagicon.shadowSize = new GSize(35, 45);
				flagicon.iconAnchor = new GPoint(1, 45);
				flagicon.infoWindowAnchor = new GPoint(5, 1);
				map.addOverlay(new GMarker(point, flagicon));
			}
		}

		map.setCenter(point, zoom);
		document.getElementById('resultDiv').innerHTML = "";

		var childicon = new GIcon();
		childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
		childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
		childicon.iconSize = new GSize(12, 20);
		childicon.shadowSize = new GSize(22, 20);
		childicon.iconAnchor = new GPoint(6, 20);
		childicon.infoWindowAnchor = new GPoint(5, 1);
		for (i=0; i < childplaces.length; i++) {
			map.addOverlay(childplaces[i]);
		}
	}

	function loadMap() {
		var zoom;
		if (GBrowserIsCompatible()) {
			map = new GMap2(document.getElementById("map_pane"));
			map.addControl(new GSmallMapControl());
			map.addControl(new GScaleControl()) ;
			GEvent.addListener(map, 'click', function(overlay, point) {
				if (overlay) {  //probably not needed in this case
								//map.removeOverlay(overlay);
				} else if (point) {
					map.clearOverlays();
					// Create our "tiny" yellow marker icon where the user clicked,
					// The full size red marker is at the stored coordinates.
					var smicon = new GIcon();
					smicon.image = "http://labs.google.com/ridefinder/images/mm_20_yellow.png";
					smicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
					smicon.iconSize = new GSize(12, 20);
					smicon.shadowSize = new GSize(22, 20);
					smicon.iconAnchor = new GPoint(6, 20);
					smicon.infoWindowAnchor = new GPoint(5, 1);

					map.panTo(point);
					prec = 20;
					for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
						if (document.editplaces.NEW_PRECISION[i].checked) {
							prec = document.editplaces.NEW_PRECISION[i].value;
						}
					}

					if (point.y < 0.0) {
						document.editplaces.NEW_PLACE_LATI.value = (point.y.toFixed(prec) * -1);
						document.editplaces.LATI_CONTROL.value = "PL_S";
					} else {
						document.editplaces.NEW_PLACE_LATI.value = point.y.toFixed(prec);
						document.editplaces.LATI_CONTROL.value = "PL_N";
					}
					if (point.x < 0.0) {
						document.editplaces.NEW_PLACE_LONG.value = (point.x.toFixed(prec) * -1);
						document.editplaces.LONG_CONTROL.value = "PL_W";
					} else {
						document.editplaces.NEW_PLACE_LONG.value = point.x.toFixed(prec);
						document.editplaces.LONG_CONTROL.value = "PL_E";
					}
					newval = new GLatLng (point.y.toFixed(prec), point.x.toFixed(prec));
					if (document.editplaces.icon.value == "") {
						map.addOverlay(new GMarker(newval));
					}
					else {
						var flagicon = new GIcon();
						flagicon.image = document.editplaces.icon.value;
						flagicon.shadow = "modules/googlemap/flag_shadow.png";
						flagicon.iconSize = new GSize(25, 15);
						flagicon.shadowSize = new GSize(35, 45);
						flagicon.iconAnchor = new GPoint(1, 45);
						flagicon.infoWindowAnchor = new GPoint(5, 1);
						map.addOverlay(new GMarker(newval, flagicon));
					}
					// Trying to get the smaller yellow icon drawn in front.
					map.addOverlay(new GMarker(point, smicon));
					document.getElementById('resultDiv').innerHTML = "";
					document.editplaces.save1.disabled = "";
					document.editplaces.save2.disabled = "";
					var childicon = new GIcon();
					childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
					childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
					childicon.iconSize = new GSize(12, 20);
					childicon.shadowSize = new GSize(22, 20);
					childicon.iconAnchor = new GPoint(6, 20);
					childicon.infoWindowAnchor = new GPoint(5, 1);
					for (i=0; i < childplaces.length; i++) {
						map.addOverlay(childplaces[i]);
					}
			}});
			GEvent.addListener(map, "moveend",function() {
				document.editplaces.NEW_ZOOM_FACTOR.value = map.getZoom();
			});
<?php if(($place_long == null) || ($place_lati == null)) { ?>
			map.setCenter(new GLatLng( <?php print $parent_lati.", ".$parent_long."), ".$zoomfactor;?>, G_NORMAL_MAP );
<?php }else { ?>
			map.setCenter(new GLatLng( <?php print $place_lati.", ".$place_long."), ".$zoomfactor;?>, G_NORMAL_MAP );
<?php } ?>

<?php   if ($level < 3) { ?>
			var childicon = new GIcon();
			childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
			childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
			childicon.iconSize = new GSize(12, 20);
			childicon.shadowSize = new GSize(22, 20);
			childicon.iconAnchor = new GPoint(6, 20);
			childicon.infoWindowAnchor = new GPoint(5, 1);
<?php
			$sql = "SELECT pl_place,pl_lati,pl_long,pl_icon FROM {$TBLPREFIX}placelocation WHERE pl_parent_id={$placeid}";
			$res = dbquery($sql);
			$i = 0;
			while ($row =& $res->fetchRow()) {
				if (($row[1] != null) && ($row[2] != null)) {
					if (($row[3] == null) || ($row[3] == "")) {
						print "            childplaces.push(new GMarker(new GLatLng(".str_replace(array('N', 'S', ','), array('', '-', '.') , $row[1]).", ".str_replace(array('E', 'W', ','), array('', '-', '.') ,$row[2])."), childicon));\n";
					}
					else {
						print "            var flagicon = new GIcon();\n";
						print "            flagicon.image = \"".$row[3]."\";\n";
						print "            flagicon.shadow = \"modules/googlemap/flag_shadow.png\";\n";
						print "            flagicon.iconSize = new GSize(25, 15);\n";
						print "            flagicon.shadowSize = new GSize(35, 45);\n";
						print "            flagicon.iconAnchor = new GPoint(1, 45);\n";
						print "            flagicon.infoWindowAnchor = new GPoint(5, 1);\n";
						print "            childplaces.push(new GMarker(new GLatLng(".str_replace(array('N', 'S', ','), array('', '-') , $row[1]).", ".str_replace(array('E', 'W', ','), array('', '-', '.') ,$row[2])."), flagicon));\n";
					}
					print "            GEvent.addListener(childplaces[".$i."], \"click\", function() {\n";
					print "                childplaces[".$i."].openInfoWindowHtml(\"".$row[0]."\")});\n";
					print "            map.addOverlay(childplaces[".$i."]);\n";
					$i = $i + 1;
				}
			}
			$res->free();
		}
?>
<?php   if ($show_marker == true) {
			if (($place_icon == NULL) || ($place_icon == "")) {
				if (($place_lati == null) || ($place_long == null)) {?>
			map.addOverlay(new GMarker(new GLatLng(<?php print $parent_lati.", ".$parent_long;?>)));
<?php           } else { ?>
			map.addOverlay(new GMarker(new GLatLng(<?php print $place_lati.", ".$place_long;?>)));
<?php           }
			}
			else { ?>
			var flagicon = new GIcon();
			flagicon.image = "<?php print $place_icon;?>";
			flagicon.shadow = "modules/googlemap/flag_shadow.png";
			flagicon.iconSize = new GSize(25, 15);
			flagicon.shadowSize = new GSize(35, 45);
			flagicon.iconAnchor = new GPoint(1, 45);
			flagicon.infoWindowAnchor = new GPoint(5, 1);
<?php           if (($place_lati == null) || ($place_long == null)) {?>
			map.addOverlay(new GMarker(new GLatLng(<?php print $parent_lati.", ".$parent_long;?>), flagicon));
<?php           } else { ?>
			map.addOverlay(new GMarker(new GLatLng(<?php print $place_lati.", ".$place_long;?>), flagicon));
<?php           }
			}
		} ?>
			// Our info window content
		}
	}

	function edit_close() {
		if (window.opener.showchanges) window.opener.showchanges();
		GUnload();
		window.close();
	}

	function setLoc(lat, lng) {
		prec = 20;
		for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
			if (document.editplaces.NEW_PRECISION[i].checked) {
				prec = document.editplaces.NEW_PRECISION[i].value;
			}
		}

		if (lat < 0.0) {
			document.editplaces.NEW_PLACE_LATI.value = (lat.toFixed(prec) * -1);
			document.editplaces.LATI_CONTROL.value = "PL_S";
		} else {
			document.editplaces.NEW_PLACE_LATI.value = lat.toFixed(prec);
			document.editplaces.LATI_CONTROL.value = "PL_N";
		}
		if (lng < 0.0) {
			document.editplaces.NEW_PLACE_LONG.value = (lng.toFixed(prec) * -1);
			document.editplaces.LONG_CONTROL.value = "PL_W";
		} else {
			document.editplaces.NEW_PLACE_LONG.value = lng.toFixed(prec);
			document.editplaces.LONG_CONTROL.value = "PL_E";
		}
		newval = new GLatLng (lat.toFixed(prec), lng.toFixed(prec));
		updateMap();
	}

	function createMarker(point, name) {
		var icon = new GIcon();
		icon.image = "modules/googlemap/marker_yellow.png";
		icon.shadow = "modules/googlemap/shadow50.png";
		icon.iconSize = new GSize(20, 34);
		icon.shadowSize = new GSize(37, 34);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);

		var marker = new GMarker(point, icon);
		GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml(name.name + "(" + name.countryCode + ")<br/><a href=\"javascript:setLoc(" + name.lat + ", " + name.lng + ");\"><?php print PrintReady($pgv_lang["pl_use_this_value"])?></a>");
		});
		return marker;
	}

	function getLocation(jData) {
		if (jData == null) {
			// There was a problem parsing search results
			return;
		}

		var html = '';
		var markers = [];
		var geonames = jData.geonames;
		var bounds = new GLatLngBounds();
		var count = 0;
		map.clearOverlays();

		var childicon = new GIcon();
		childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
		childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
		childicon.iconSize = new GSize(12, 20);
		childicon.shadowSize = new GSize(22, 20);
		childicon.iconAnchor = new GPoint(6, 20);
		childicon.infoWindowAnchor = new GPoint(5, 1);
		for (i=0; i < childplaces.length; i++) {
			map.addOverlay(childplaces[i]);
		}

		for (i=0;i< geonames.length;i++) {
			var name = geonames[i];
<?php if ($level == 3) { ?> // Not sure what this bit of code is for?
			if (name.adminName1 == "<?php print end($where_am_i);?>") {
<?php } ?>
				bounds.extend(new GLatLng(name.lat, name.lng));
				var point = new GLatLng(name.lat, name.lng);
				map.addOverlay(createMarker(point, name));
				count++;
				html = html + name.name + "(" + name.countryCode + "): <a href=\"javascript:setLoc(" + name.lat + ", " + name.lng + ");\"><?php print $pgv_lang["pl_use_this_value"]?></a><br/>";
<?php if ($level == 3) { ?>
			}
<?php } ?>
		}
		if (count == 0) {
			alert("<?php print $pgv_lang["pl_no_places_found"];?>");
			return;
		}
		else {
			clat = (bounds.getNorthEast().lat() + bounds.getSouthWest().lat())/2;
			clng = (bounds.getNorthEast().lng() + bounds.getSouthWest().lng())/2;
			zoomlevel = map.getBoundsZoomLevel(bounds);
			for(i = 0; ((i < 10) && (zoomlevel == 1)); i++) {
				zoomlevel = map.getBoundsZoomLevel(bounds);
			}
			if (zoomlevel < <?php print $GOOGLEMAP_MIN_ZOOM;?>) zoomlevel = <?php print $GOOGLEMAP_MIN_ZOOM;?>;
			if (zoomlevel > <?php print $GOOGLEMAP_MAX_ZOOM;?>) zoomlevel = <?php print $GOOGLEMAP_MAX_ZOOM;?>;
			map.setCenter(new GLatLng(clat, clng), zoomlevel-1);
			document.getElementById('resultDiv').innerHTML = html;
			document.editplaces.save1.disabled = "true";
			document.editplaces.save2.disabled = "true";
		}
	}

	function search_loc() {
		var whereAmI = encodeURIComponent('<?php print addslashes(current($where_am_i));?>');
<?php if ($level == 0) { ?>
		request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&fclass=A&style=FULL&callback=getLocation';
<?php } ?>
<?php if ($level == 1) { ?>
		request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&country=' + whereAmI + '&fclass=P&fclass=A&style=FULL&callback=getLocation';
<?php } ?>
<?php if ($level == 2) { ?>
		request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&country=' + whereAmI + '&fclass=P&fclass=A&style=FULL&callback=getLocation';
<?php } ?>
<?php if ($level == 3) { ?>
		request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&country=' + whereAmI + '&fclass=P&style=FULL&callback=getLocation';
<?php } ?>
		// Create a new script object
		aObj = new JSONscriptRequest(request);
		// Build the script tag
		aObj.buildScriptTag();
		// Execute (add) the script tag
		aObj.addScriptTag();
	}

function change_icon() {
	window.open('module.php?mod=googlemap&pgvaction=flags', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}

function remove_icon() {
	document.editplaces.icon.value = "";
	document.getElementById('flagsDiv').innerHTML = "<a href=\"javascript:;\" onclick=\"change_icon();return false;\"><?php print $pgv_lang["pl_change_flag"]?></a>";
}

var helpWin;
function helpPopup(which) {
	if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
	else helpWin.location = 'modules/googlemap/editconfig_help.php?help='+which;
	return false;
}

function getHelp(which) {
	if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
}

function updateSearchLink() {
	if (document.editplaces.NEW_PLACE_NAME.value == "") {
		document.getElementById("searchDir").innerHTML = "<?php print $pgv_lang["search"]?>";
	} else {
		document.getElementById("searchDir").innerHTML = "<a href=\"javascript:;\" onclick=\"search_loc();return false;\"><?php print $pgv_lang["search"]?></a>";
	}
}

function updatewholename() {
}

function paste_char(value,lang,mag) {
	document.editplaces.NEW_PLACE_NAME.value += value;
	language_filter = lang;
	magnify = mag;
}

	//-->
</script>


<form method="post" id="editplaces" name="editplaces" action="module.php?mod=googlemap&pgvaction=places_edit">
	<input type="hidden" name="action" value="<?php print $action;?>record" />
	<input type="hidden" name="placeid" value="<?php print $placeid;?>" />
	<input type="hidden" name="level" value="<?php print $level;?>" />
	<input type="hidden" name="icon" value="<?php print $place_icon;?>" />
	<input type="hidden" name="parent_id" value="<?php print $parent_id;?>" />
	<input type="hidden" name="place_long" value="<?php print $place_long;?>" />
	<input type="hidden" name="place_lati" value="<?php print $place_lati;?>" />
	<input type="hidden" name="parent_long" value="<?php print $parent_long;?>" />
	<input type="hidden" name="parent_lati" value="<?php print $parent_lati;?>" />
	<input id="savebutton" name="save1" type="submit" value="<?php print $pgv_lang["save"];?>" /><br />

	<table class="facts_table">
	<tr>
		<td class="optionbox" colspan="2">
		<center><div id="map_pane" style="width: 100%; height: 300px"></div></center>
	</tr>
	<tr>
		<td class="optionbox" colspan="2">
		<div id="resultDiv"></div>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("PLE_PLACES_help", "qm", "PLE_PLACES");?><?php print $factarray["PLAC"];?></td>
		<td class="optionbox"><input type="text" name="NEW_PLACE_NAME" value="<?php print htmlspecialchars(PrintReady($place_name));?>" size="20" tabindex="<?php print ++$i;?>" onchange="updateSearchLink(); return false" />
		<div id="INDI_PLAC_pop" style="display: inline;">
		<?php print_specialchar_link("NEW_PLACE_NAME", false);?></div>

<?php if ($place_name == "") { ?>
		<div id="searchDir" style="display:inline"><?php print $pgv_lang["search"]?></div>
<?php } else { ?>
		<div id="searchDir" style="display:inline"><a href="javascript:;" onclick="search_loc();return false;"><?php print $pgv_lang["search"]?></a></div>
<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("PLE_PRECISION_help", "qm", "PLE_PRECISION");?><?php print $pgv_lang["pl_precision"];?></td>
		<td class="optionbox">
			<input type="radio" name="NEW_PRECISION" onchange="updateMap();" <?php if($level==0) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_0;?>" tabindex="<?php print ++$i;?>" ><?php print $pgv_lang["pl_country"];?></input>
			<input type="radio" name="NEW_PRECISION" onchange="updateMap();" <?php if($level==1) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_1;?>" tabindex="<?php print ++$i;?>" ><?php print $pgv_lang["pl_state"];?></input>
			<input type="radio" name="NEW_PRECISION" onchange="updateMap();" <?php if(($level==2)||($level==3)) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_2;?>" tabindex="<?php print ++$i;?>" ><?php print $pgv_lang["pl_city"];?></input>
			<input type="radio" name="NEW_PRECISION" onchange="updateMap();" <?php if($level==4) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_3;?>" tabindex="<?php print ++$i;?>" ><?php print $pgv_lang["pl_neighborhood"];?></input>
			<input type="radio" name="NEW_PRECISION" onchange="updateMap();"<?php if($level==5) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_4;?>" tabindex="<?php print ++$i;?>" ><?php print $pgv_lang["pl_house"];?></input>
			<input type="radio" name="NEW_PRECISION" onchange="updateMap();" value="<?php print $GOOGLEMAP_PRECISION_5;?>"><?php print $pgv_lang["pl_max"];?></input>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("PLE_LATLON_CTRL_help", "qm", "PLE_LATLON_CTRL");?><?php print $factarray["LATI"];?></td>
		<td class="optionbox">
			<select name="LATI_CONTROL" tabindex="<?php print ++$i;?>" onchange="updateMap();">
				<option value="" <?php if ($place_lati == null) print " selected=\"selected\"";?>></option>
				<option value="PL_N" <?php if ($place_lati > 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_north_short"]; ?></option>
				<option value="PL_S" <?php if ($place_lati < 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_south_short"]; ?></option>
			</select>
			<input type="text" name="NEW_PLACE_LATI" value="<?php if ($place_lati != null) print abs($place_lati);?>" size="20" tabindex="<?php print ++$i;?>" onchange="updateMap();" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("PLE_LATLON_CTRL_help", "qm", "PLE_LATLON_CTRL");?><?php print $factarray["LONG"];?></td>
		<td class="optionbox">
			<select name="LONG_CONTROL" tabindex="<?php print ++$i;?>" onchange="updateMap();">
				<option value="" <?php if ($place_long == null) print " selected=\"selected\"";?>></option>
				<option value="PL_E" <?php if ($place_long > 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_east_short"]; ?></option>
				<option value="PL_W" <?php if ($place_long < 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_west_short"]; ?></option>
			</select>
			<input type="text" name="NEW_PLACE_LONG" value="<?php if ($place_long != null) print abs($place_long);?>" size="20" tabindex="<?php print ++$i;?>" onchange="updateMap();" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("PLE_ZOOM_help", "qm", "PLE_ZOOM");?><?php print $pgv_lang["pl_zoom_factor"];?></td>
		<td class="optionbox">
			<input type="text" name="NEW_ZOOM_FACTOR" value="<?php print $zoomfactor;?>" size="20" tabindex="<?php print ++$i;?>" onchange="updateMap();" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("PLE_ICON_help", "qm", "PLE_ICON");?><?php print $pgv_lang["pl_flag"];?></td>
		<td class="optionbox">
			<div id="flagsDiv">
<?php
		if (($place_icon == NULL) || ($place_icon == "")) { ?>
				<a href="javascript:;" onclick="change_icon();return false;"><?php print $pgv_lang["pl_change_flag"]?></a>
<?php   }
		else { ?>
				<img src="<?php print $place_icon;?>">&nbsp;&nbsp;
				<a href="javascript:;" onclick="change_icon();return false;"><?php print $pgv_lang["pl_change_flag"]?></a>&nbsp;&nbsp;
				<a href="javascript:;" onclick="remove_icon();return false;"><?php print $pgv_lang["pl_remove_flag"]?></a>
<?php   } ?>
			</div>
	</tr>
	</table>
	<input id="savebutton" name="save2" type="submit" value="<?php print $pgv_lang["save"];?>" /><br />
</form>
<?php
print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();return false;\">".$pgv_lang["close_window"]."</a></div><br />\n";

print_simple_footer();
?>
