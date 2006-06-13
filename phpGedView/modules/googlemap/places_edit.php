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
        
if ($action=="addrecord") {
    if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
    getHighestIndex();
    $sql = "INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".(getHighestIndex()+1).", $placeid, ".($level+1).", \"".$_POST["NEW_PLACE_NAME"]."\", \"".$_POST["LONG_CONTROL"][3].$_POST["NEW_PLACE_LONG"]."\" , \"".$_POST["LATI_CONTROL"][3].$_POST["NEW_PLACE_LATI"]."\", ".$_POST["NEW_ZOOM_FACTOR"].", NULL);";
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
    $sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_place=\"".$_POST["NEW_PLACE_NAME"]."\",pl_lati=\"".$_POST["LATI_CONTROL"][3].$_POST["NEW_PLACE_LATI"]."\",pl_long=\"".$_POST["LONG_CONTROL"][3].$_POST["NEW_PLACE_LONG"]."\",pl_zoom=\"".$_POST["NEW_ZOOM_FACTOR"]."\" where pl_id=$placeid LIMIT 1";
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
if (!isset($level)) {
	$level=0;
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
        $place_lati = str_replace(array('N', 'S'), array('', '-') , $row[1]);
        $place_long = str_replace(array('E', 'W'), array('', '-') , $row[2]);
        $place_icon = $row[3];
        $parent_id  = $row[4];
        $level      = $row[5];
        $zoomfactor = $row[6];
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

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAuoJDxk-bXegvgyoxHyA6kxTIov89Ze_GEizuvnvOWTKQGM7qlBSoXruhOfebtgBxlpnpAJQcHHEQbQ" type="text/javascript"></script>

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

    function updateMap() {
        var point;
        var zoom;
        var lati;
        var long;

        zoom = parseInt(document.editplaces.NEW_ZOOM_FACTOR.value);
        lati = document.editplaces.NEW_PLACE_LATI.value;
        long = document.editplaces.NEW_PLACE_LONG.value;
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
        map.addOverlay(new GMarker(point));
        map.setCenter(point, zoom);
    }
    
    function loadMap() {
        var pointArray = [];
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
                    map.addOverlay(new GMarker(point));
                    map.panTo(point); 
                    if (point.y < 0.0) {
                        document.editplaces.NEW_PLACE_LATI.value = (point.y * -1);
                        document.editplaces.LATI_CONTROL.value = "PL_S";
                    } else {
                        document.editplaces.NEW_PLACE_LATI.value = point.y;
                        document.editplaces.LATI_CONTROL.value = "PL_N";
                    }
                    if (point.x < 0.0) {
                        document.editplaces.NEW_PLACE_LONG.value = (point.x * -1);
                        document.editplaces.LONG_CONTROL.value = "PL_W";
                    } else {
                        document.editplaces.NEW_PLACE_LONG.value = point.x;
                        document.editplaces.LONG_CONTROL.value = "PL_E";
                    }
            }});
            GEvent.addListener(map, "moveend",function() {
                document.editplaces.NEW_ZOOM_FACTOR.value = map.getZoom();
            }); 

            map.setCenter(new GLatLng( <?php print $place_lati.", ".$place_long."), ".$zoomfactor;?>, G_NORMAL_MAP );
<?php       if ($show_marker == true) { ?>
            map.addOverlay(new GMarker(new GLatLng(<?php print $place_lati.", ".$place_long;?>)));
<?php       } ?>
            // Our info window content
      }
    }

	function edit_close() {
		if (window.opener.showchanges) window.opener.showchanges();
		window.close();
	}

    //-->
</script>

<form method="post" id="editplaces" name="editplaces" action="module.php?mod=googlemap&pgvaction=places_edit">
    <input type="hidden" name="action" value="<?php print $action;?>record" />
    <input type="hidden" name="placeid" value="<?php print $placeid;?>" />
    <input type="hidden" name="level" value="<?php print $level;?>" />
    <input id="savebutton" type="submit" value="<?php print $pgv_lang["save"];?>" /><br />

    <table class="facts_table">
    <tr>
        <td class="optionbox" colspan="2">
        <center><div id="map_pane" style="width: 550px; height: 300px"></div></center>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print $factarray["PLAC"];?></td>
        <td class="optionbox"><input type="text" dir="ltr" name="NEW_PLACE_NAME" value="<?php print $place_name;?>" size="20" tabindex="<?php $i++; print $i?>" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print $factarray["LATI"];?></td>
        <td class="optionbox">
            <select name="LATI_CONTROL" dir="ltr" tabindex="<?php $i++; print $i?>" onchange="updateMap();">
                <option value="PL_N" <?php if ($place_lati >= 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_north_short"]; ?></option>
                <option value="PL_S" <?php if ($place_lati < 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_south_short"]; ?></option>
            </select>
            <input type="text" dir="ltr" name="NEW_PLACE_LATI" value="<?php print abs($place_lati);?>" size="20" tabindex="<?php $i++; print $i?>" onchange="updateMap();" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print $factarray["LONG"];?></td>
        <td class="optionbox">
            <select name="LONG_CONTROL" dir="ltr" tabindex="<?php $i++; print $i?>" onchange="updateMap();">
                <option value="PL_E" <?php if ($place_long >= 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_east_short"]; ?></option>
                <option value="PL_W" <?php if ($place_long < 0) print " selected=\"selected\""; print ">".$pgv_lang["pl_west_short"]; ?></option>
            </select>
            <input type="text" dir="ltr" name="NEW_PLACE_LONG" value="<?php print abs($place_long);?>" size="20" tabindex="<?php $i++; print $i?>" onchange="updateMap();" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print $pgv_lang["pl_zoom_factor"];?></td>
        <td class="optionbox">
            <input type="text" dir="ltr" name="NEW_ZOOM_FACTOR" value="<?php print $zoomfactor;?>" size="20" tabindex="<?php $i++; print $i?>" onchange="updateMap();" /></td>
    </tr>
    </table>
    <input id="savebutton" type="submit" value="<?php print $pgv_lang["save"];?>" /><br />
</form>
<?php
print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";
        
print_simple_footer();
?>
