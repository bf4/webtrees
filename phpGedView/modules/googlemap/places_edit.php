<?php
/**
 * Interface to edit place locations
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
 * @package PhpGedView
 * @subpackage Edit
 * @version $Id$
 */

require "config.php";
require "modules/googlemap/defaultconfig.php";
require "modules/googlemap/config.php";
require "includes/functions_edit.php";
require "includes/functions_import.php";
require $INDEX_DIRECTORY."pgv_changes.php";
require($factsfile["english"]);
require( "modules/googlemap/".$pgv_language["english"]);
if (file_exists( "modules/googlemap/".$pgv_language[$LANGUAGE])) require  "modules/googlemap/".$pgv_language[$LANGUAGE];
require( "modules/googlemap/".$helptextfile["english"]);
if (file_exists("modules/googlemap/".$helptextfile[$LANGUAGE])) require "modules/googlemap/".$helptextfile[$LANGUAGE];

if (file_exists( $factsfile[$LANGUAGE])) require  $factsfile[$LANGUAGE];

print_simple_header($pgv_lang["edit_place_locations"]);

if (!userIsAdmin(getUserName())) {
    print "<table class=\"facts_table\">\n";
    print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_admin_error"];
    print "</td></tr></table>\n";
    print "<br><br><br>\n";
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

function getHighestIndex() {
    global $TBLPREFIX;
    $sql = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE 1";
    $res = dbquery($sql);
    $i = 0;
    while ($row =& $res->fetchRow()) {
        if ($row[0] > $i) $i  = $row[0];
    }
    $res->free();
    return $i;
}
 
if (!isset($level)) {
    $level=0;
}

if ($action=="addrecord") {
    if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
    getHighestIndex();
    $sql = "INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".(getHighestIndex()+1).", $placeid, ".$level.", \"".$_POST["NEW_PLACE_NAME"]."\", \"".$_POST["LONG_CONTROL"][3].$_POST["NEW_PLACE_LONG"]."\" , \"".$_POST["LATI_CONTROL"][3].$_POST["NEW_PLACE_LATI"]."\", ".$_POST["NEW_ZOOM_FACTOR"].", \"".$_POST["icon"]."\");";
    if (userIsAdmin(getUserName())) {
        $res = dbquery($sql);
    }
    if ($EDIT_AUTOCLOSE and !$GLOBALS["DEBUG"]) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";
    print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";
    print_simple_footer();
    exit;
}

if ($action=="updaterecord") {
    if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
    $sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_place=\"".$_POST["NEW_PLACE_NAME"]."\",pl_lati=\"".$_POST["LATI_CONTROL"][3].$_POST["NEW_PLACE_LATI"]."\",pl_long=\"".$_POST["LONG_CONTROL"][3].$_POST["NEW_PLACE_LONG"]."\",pl_zoom=\"".$_POST["NEW_ZOOM_FACTOR"]."\",pl_icon=\"".$_POST["icon"]."\" where pl_id=$placeid LIMIT 1";
    if (userIsAdmin(getUserName())) {
        $res = dbquery($sql);
    }
    if ($EDIT_AUTOCLOSE and !$GLOBALS["DEBUG"]) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";
    print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";
    print_simple_footer();
    exit;
}


if (!isset($parent)) $parent=array();
else {
    if (!is_array($parent)) $parent = array();
    else $parent = array_values($parent);
}
if ($level>count($parent)) $level = count($parent);
if ($level<count($parent)) $level = 0;

if ($action=="update") {
    // --- find the place in the file
    $sql = "SELECT pl_place,pl_lati,pl_long,pl_icon,pl_parent_id,pl_level,pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_id=$placeid ORDER BY pl_place";
    $res = dbquery($sql);

    $row =& $res->fetchRow();
    $place_name = $row[0];
    $place_icon = $row[3];
    $parent_id  = $row[4];
    $level      = $row[5];
    $zoomfactor = $row[6];
    if(($row[1] != NULL) && ($row[2] != NULL)) {
        $place_lati = str_replace(array('N', 'S'), array('', '-') , $row[1]);
        $place_long = str_replace(array('E', 'W'), array('', '-') , $row[2]);
        $show_marker = true;
        $res->free();
    }
    else {
        $res->free();
        $place_lati = 0;
        $place_long = 0;
        $zoomfactor = 1;
        $show_marker = false;
        do {
            $sql = "SELECT pl_lati,pl_long,pl_parent_id,pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_id=$parent_id ORDER BY pl_place";
            $res = dbquery($sql);
            $row =& $res->fetchRow();
            if(($row[0] != NULL) && ($row[1] != NULL)) {
                $place_lati = str_replace(array('N', 'S'), array('', '-') , $row[0]);
                $place_long = str_replace(array('E', 'W'), array('', '-') , $row[1]);
                $zoomfactor = $row[3];
            }
            $parent_id = $row[2];
            $res->free();
        } while (($row[2] != 0) && ($row[0] == NULL) && ($row[1] == NULL));
    }

    $success = false;

    print "<b>".$place_name;
    for ($i = count($parent)-1; $i >= 0; $i--){
        print ", ".$parent[$i];
    }
    print "</b><br />";
    $i = 0;
}

if ($action=="add") {
    // --- find the place in the file
    if ($placeid <> 0) {
        $sql = "SELECT pl_place,pl_lati,pl_long,pl_icon,pl_parent_id,pl_level,pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_id=$placeid ORDER BY pl_place";
        $res = dbquery($sql);

        $row =& $res->fetchRow();
        $place_name = "";
        $zoomfactor = $row[6];
        if ($row[1] != null) {
            $place_lati = str_replace(array('N', 'S'), array('', '-') , $row[1]);
        }
        else {
            $place_lati = "0.0";
            $zoomfactor = 1;
        }
        if ($row[2] != null) {
            $place_long = str_replace(array('E', 'W'), array('', '-') , $row[2]);
        }
        else {
            $place_long = "0.0";
            $zoomfactor = 1;
        }
        $place_icon = $row[3];
        $parent_id  = $row[4];
        $level      = $row[5]+1;
        $res->free();
    }
    else {
        $place_name = "";
        $place_lati = "0.0";
        $place_long = "0.0";
        $place_icon = "";
        $parent_id  = 0;
        $level      = 0;
        $zoomfactor = 1;
    }
    $show_marker = false;

    $success = false;

    print "<b>".$pgv_lang["unknown"];
    for ($i = count($parent)-1; $i >= 0; $i--){
        print ", ".$parent[$i];
    }
    print "</b><br />";
    $i = 0;
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
        var lati;
        var long;
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
        lati = parseFloat(document.editplaces.NEW_PLACE_LATI.value).toFixed(prec);
        long = parseFloat(document.editplaces.NEW_PLACE_LONG.value).toFixed(prec);
        document.editplaces.NEW_PLACE_LATI.value = lati;
        document.editplaces.NEW_PLACE_LONG.value = long;

        if (lati < 0.0) {
            lati = lati * -1;
            document.editplaces.NEW_PLACE_LATI.value = lati;
        }
        if (long < 0.0) {
            long = long * -1;
            document.editplaces.NEW_PLACE_LONG.value = long;
        }
        if(document.editplaces.LATI_CONTROL.value == "PL_S") {
            lati = lati * -1;
        }
        if(document.editplaces.LONG_CONTROL.value == "PL_W") {
            long = long * -1;
        }

        point = new GLatLng (lati, long);

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
            map.setCenter(new GLatLng( <?php print $place_lati.", ".$place_long."), ".$zoomfactor;?>, G_NORMAL_MAP );

<?php   if ($level < 3) { ?>
            var childicon = new GIcon();
            childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
            childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
            childicon.iconSize = new GSize(12, 20);
            childicon.shadowSize = new GSize(22, 20);
            childicon.iconAnchor = new GPoint(6, 20);
            childicon.infoWindowAnchor = new GPoint(5, 1);
<?php
            $sql = "SELECT pl_place,pl_lati,pl_long,pl_icon FROM ".$TBLPREFIX."placelocation WHERE pl_parent_id=".$placeid;
            $res = dbquery($sql);
            $i = 0;
            while ($row =& $res->fetchRow()) {
                if (($row[1] != null) && ($row[2] != null)) {
                    if (($row[3] == null) || ($row[3] == "")) {
                        print "            childplaces.push(new GMarker(new GLatLng(".str_replace(array('N', 'S'), array('', '-') , $row[1]).", ".str_replace(array('E', 'W'), array('', '-') ,$row[2])."), childicon));\n";
                    }
                    else {
                        print "            var flagicon = new GIcon();\n";
                        print "            flagicon.image = \"".$row[3]."\";\n";
                        print "            flagicon.shadow = \"modules/googlemap/flag_shadow.png\";\n";
                        print "            flagicon.iconSize = new GSize(25, 15);\n";
                        print "            flagicon.shadowSize = new GSize(35, 45);\n";
                        print "            flagicon.iconAnchor = new GPoint(1, 45);\n";
                        print "            flagicon.infoWindowAnchor = new GPoint(5, 1);\n";
                        print "            childplaces.push(new GMarker(new GLatLng(".str_replace(array('N', 'S'), array('', '-') , $row[1]).", ".str_replace(array('E', 'W'), array('', '-') ,$row[2])."), flagicon));\n";
                    }
                    print "            map.addOverlay(childplaces[".$i."]);\n";
                    $i = $i + 1;
                }
            }
            $res->free();
        }
?> 
<?php   if ($show_marker == true) {
            if (($place_icon == NULL) || ($place_icon == "")) { ?>
            map.addOverlay(new GMarker(new GLatLng(<?php print $place_lati.", ".$place_long;?>)));
<?php       }
            else { ?>
            var flagicon = new GIcon();
            flagicon.image = "<?php print $place_icon;?>";
            flagicon.shadow = "modules/googlemap/flag_shadow.png";
            flagicon.iconSize = new GSize(25, 15);
            flagicon.shadowSize = new GSize(35, 45);
            flagicon.iconAnchor = new GPoint(12, 7);
            flagicon.infoWindowAnchor = new GPoint(5, 1);
            map.addOverlay(new GMarker(new GLatLng(<?php print $place_lati.", ".$place_long;?>), flagicon));
<?php       }
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
        marker.openInfoWindowHtml(name.name + "(" + name.countryCode + ")<br><a href=\"javascript:setLoc(" + name.lat + ", " + name.lng + ");\"><?php print $pgv_lang["pl_use_this_value"]?></a>");
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
<?php if ($level == 3) { ?>
            if (name.adminName1 == "<?php print $parent[1];?>") {
<?php } ?>
                bounds.extend(new GLatLng(name.lat, name.lng));
                var point = new GLatLng(name.lat, name.lng);
                map.addOverlay(createMarker(point, name));
                count++;
                html = html + name.name + "(" + name.countryCode + "): <a href=\"javascript:setLoc(" + name.lat + ", " + name.lng + ");\"><?php print $pgv_lang["pl_use_this_value"]?></a><br>";
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
<?php if ($level == 0) { ?>
        request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&fclass=A&style=FULL&callback=getLocation';
<?php } ?>
<?php if ($level == 1) { ?>
        request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&country=<?php print $parent[0];?>&fclass=P&fclass=A&style=FULL&callback=getLocation';
<?php } ?>
<?php if ($level == 2) { ?>
        request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&country=<?php print $parent[0];?>&fclass=P&fclass=A&style=FULL&callback=getLocation';
<?php } ?>
<?php if ($level == 3) { ?>
        request = 'http://ws.geonames.org/searchJSON?name=' +  encodeURIComponent(document.editplaces.NEW_PLACE_NAME.value)  + '&country=<?php print $parent[0];?>&fclass=P&style=FULL&callback=getLocation';
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

    //-->
</script>


<form method="post" id="editplaces" name="editplaces" action="module.php?mod=googlemap&pgvaction=places_edit">
    <input type="hidden" name="action" value="<?php print $action;?>record" />
    <input type="hidden" name="placeid" value="<?php print $placeid;?>" />
    <input type="hidden" name="level" value="<?php print $level;?>" />
    <input type="hidden" name="icon" value="<?php print $place_icon;?>" />
    <input id="savebutton" name="save1" type="submit" value="<?php print $pgv_lang["save"];?>" /><br />

    <table class="facts_table">
    <tr>
        <td class="optionbox" colspan="2">
        <center><div id="map_pane" style="width: 550px; height: 300px"></div></center>
    </tr>
    <tr>
        <td class="optionbox" colspan="2">
        <div id="resultDiv"></div>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("PLE_PLACES_help", "qm", "PLE_PLACES");?><?php print $factarray["PLAC"];?></td>
        <td class="optionbox"><input type="text" dir="ltr" name="NEW_PLACE_NAME" value="<?php print $place_name;?>" size="20" tabindex="<?php $i++; print $i?>" />
        <a href="javascript:;" onclick="search_loc();return false;"><?php print $pgv_lang["search"]?></a>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("PLE_PRECISION_help", "qm", "PLE_PRECISION");?><?php print $pgv_lang["pl_precision"];?></td>
        <td class="optionbox">
            <input type="radio" dir="ltr" name="NEW_PRECISION" onchange="updateMap();" <?php if($level==0) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_0;?>"><?php print $pgv_lang["pl_country"];?></input>
            <input type="radio" dir="ltr" name="NEW_PRECISION" onchange="updateMap();" <?php if($level==1) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_1;?>"><?php print $pgv_lang["pl_state"];?></input>
            <input type="radio" dir="ltr" name="NEW_PRECISION" onchange="updateMap();" <?php if(($level==2)||($level==3)) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_2;?>"><?php print $pgv_lang["pl_city"];?></input>
            <input type="radio" dir="ltr" name="NEW_PRECISION" onchange="updateMap();" <?php if($level==4) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_3;?>"><?php print $pgv_lang["pl_neighborhood"];?></input>
            <input type="radio" dir="ltr" name="NEW_PRECISION" onchange="updateMap();"<?php if($level==5) print "checked "?>value="<?php print $GOOGLEMAP_PRECISION_4;?>"><?php print $pgv_lang["pl_house"];?></input>
            <input type="radio" dir="ltr" name="NEW_PRECISION" onchange="updateMap();" value="<?php print $GOOGLEMAP_PRECISION_5;?>"><?php print $pgv_lang["pl_max"];?></input>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("PLE_LATLON_CTRL_help", "qm", "PLE_LATLON_CTRL");?><?php print $factarray["LATI"];?></td>
        <td class="optionbox">
            <select name="LATI_CONTROL" dir="ltr" tabindex="<?php $i++; print $i?>" onchange="updateMap();">
                <option value="PL_N" <?php if ($place_lati >= 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_north_short"]; ?></option>
                <option value="PL_S" <?php if ($place_lati < 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_south_short"]; ?></option>
            </select>
            <input type="text" dir="ltr" name="NEW_PLACE_LATI" value="<?php print abs($place_lati);?>" size="20" tabindex="<?php $i++; print $i?>" onchange="updateMap();" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("PLE_LATLON_CTRL_help", "qm", "PLE_LATLON_CTRL");?><?php print $factarray["LONG"];?></td>
        <td class="optionbox">
            <select name="LONG_CONTROL" dir="ltr" tabindex="<?php $i++; print $i?>" onchange="updateMap();">
                <option value="PL_E" <?php if ($place_long >= 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_east_short"]; ?></option>
                <option value="PL_W" <?php if ($place_long < 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_west_short"]; ?></option>
            </select>
            <input type="text" dir="ltr" name="NEW_PLACE_LONG" value="<?php print abs($place_long);?>" size="20" tabindex="<?php $i++; print $i?>" onchange="updateMap();" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("PLE_ZOOM_help", "qm", "PLE_ZOOM");?><?php print $pgv_lang["pl_zoom_factor"];?></td>
        <td class="optionbox">
            <input type="text" dir="ltr" name="NEW_ZOOM_FACTOR" value="<?php print $zoomfactor;?>" size="20" tabindex="<?php $i++; print $i?>" onchange="updateMap();" /></td>
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
print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";
        
print_simple_footer();
?>
