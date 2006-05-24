<?php
/**
 * Google map module for phpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @subpackage Module
 * @version $Id: googlemap.php,v$
 * @author Johan Borkhuis
 */

require('modules/googlemap/config.php');

require( "modules/googlemap/".$pgv_language["english"]);
if (file_exists( "modules/googlemap/".$pgv_language[$LANGUAGE])) require  "modules/googlemap/".$pgv_language[$LANGUAGE];

// function copied from print_fact_place
function print_fact_place_map($factrec) {
    global $SHOW_PEDIGREE_PLACES, $TEMPLE_CODES, $pgv_lang, $factarray;

    $out = false;
    $ct = preg_match("/2 PLAC (.*)/", $factrec, $match);
    if ($ct>0) {
        print " ";
        $levels = preg_split("/,/", $match[1]);
        $place = trim($match[1]);
        // reverse the array so that we get the top level first
        $levels = array_reverse($levels);
        print "<a href=\\\"placelist.php?action=show&amp;";
        foreach($levels as $pindex=>$ppart) {
             // routine for replacing ampersands
             $ppart = preg_replace("/amp\%3B/", "", trim($ppart));
             print "parent[$pindex]=".PrintReady($ppart)."&amp;";
        }
        print "level=".count($levels);
        print "\\\"> ".PrintReady($place)."</a>";
    }
}

function build_indiv_map($indifacts, $famids) {
    global $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GOOGLEMAP_MIN_ZOON, $GOOGLEMAP_MAX_ZOON, $GEDCOM, $SERVER_URL;
    global $GOOGLEMAP_XSIZE, $GOOGLEMAP_YSIZE, $pgv_lang, $factarray, $SHOW_LIVING_NAMES, $PRIV_PUBLIC;
    global $GOOGLEMAP_MAX_ZOOM, $GOOGLEMAP_MIN_ZOOM;

    $mapdata             = array();
    $mapdata["show"]     = array();         // Show this location on the map
    $mapdata["fact"]     = array();
    $mapdata["placerec"] = array();
    $mapdata["lati"]     = array();
    $mapdata["lng"]      = array();
    $mapdata["date"]     = array();
    $mapdata["name"]     = array();
    $mapdata["sex"]      = array();
    $marker              = array();
    $marker["name"]      = array();
    $marker["index"]     = array();
    $marker["tabindex"]  = array();
    $marker["placed"]    = array();

    //-- sort the facts
    usort($indifacts, "compare_facts");
    $i = 0;
    foreach ($indifacts as $key => $value) {
        $ft = preg_match("/1 (\w+)(.*)/", $value[1], $match);
        if ($ft>0) {
            $fact = trim($match[1]);
            $ct = preg_match("/2 PLAC (.*)/", $value[1], $match);
            if ($ct>0) {
                $placerec = get_sub_record(2, "2 PLAC", $value[1]);
                if (!empty($placerec)) {
                    $ctla = preg_match("/\d LATI (.*)/", $placerec, $match1);
                    $ctlo = preg_match("/\d LONG (.*)/", $placerec, $match2);

                    if (($ctla>0) && ($ctlo>0)) {
                        $i = $i + 1;
                        $mapdata["fact"][$i] = $factarray[$fact];
                        $mapdata["show"][$i] = "yes";
                        $marker["name"][$i] = "Marker".$i;
                        $marker["placed"][$i] = "no";
                        $mapdata["placerec"][$i] = $placerec;
                        $mapdata["lati"][$i] = str_replace(array('N', 'S'), array('', '-') , $match1[1]); 
                        $mapdata["lng"][$i] = str_replace(array('E', 'W'), array('', '-') , $match2[1]); 
                        $ctd = preg_match("/2 DATE (.+)/", $value[1], $match);
                        if ($ctd>0) {
                            $mapdata["date"][$i] = $match[1];
                        }
                        else {
                            $mapdata["date"][$i] = "";
                        }
                        $mapdata["name"][$i]="";
                        $ct = preg_match("/PGV_SPOUSE: (.*)/", $value[1], $match);
                        if ($ct>0) {
                            $mapdata["name"][$i]=$match[1];
                        }
                        $mapdata["sex"][$i]  = "-";
                    }
                }
            }
        }
    }

    // Add children to the list
    if (count($famids)>0) {
        $hparents=false;
        for($f=0; $f<count($famids); $f++) {
            if (!empty($famids[$f])) {
                $famrec = find_family_record($famids[$f]);
                if (empty($famrec)) $famrec = find_record_in_file($famids[$f]);
                if ($famrec) {
                    $num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch,PREG_SET_ORDER);
                    for($j=0; $j<$num; $j++) {
                        $srec = find_person_record($smatch[$j][1]);
                        if (empty($srec)) $srec = find_record_in_file($smatch[$j][1]);
                        $birthrec = get_sub_record(1, "2 BIRT", $srec);
                        $placerec = get_sub_record(2, "2 PLAC", $srec);
                        if (!empty($placerec)) {
                            $ctd = preg_match("/\d DATE (.*)/", $srec, $matchd);
                            $ctp = preg_match("/\d PLAC (.*)/", $birthrec, $matchp);
                            $ctla = preg_match("/\d LATI (.*)/", $srec, $match1);
                            $ctlo = preg_match("/\d LONG (.*)/", $srec, $match2);
                            if (($ctla>0) && ($ctlo>0)) {
                                if (displayDetailsByID($smatch[$j][1])) {
                                    $i = $i + 1;
                                    $mapdata["show"][$i]     = "yes";
                                    $mapdata["fact"][$i]     = $factarray["CHIL"];
                                    $mapdata["sex"][$i]      = "NN";
                                    if (preg_match("/1 SEX F/", $srec)>0) {
                                        $mapdata["fact"][$i] = $pgv_lang["daughter"];
                                        $mapdata["sex"][$i]  = "F";
                                    }
                                    if (preg_match("/1 SEX M/", $srec)>0) {
                                        $mapdata["fact"][$i] = $pgv_lang["son"];
                                        $mapdata["sex"][$i]  = "M";
                                    }
                                    $marker["name"][$i]      = "Marker".$i;
                                    $marker["placed"][$i]    = "no";
                                    $mapdata["placerec"][$i] = $placerec;
                                    $mapdata["lati"][$i]     = str_replace(array('N', 'S'), array('', '-') , $match1[1]); 
                                    $mapdata["lng"][$i]      = str_replace(array('E', 'W'), array('', '-') , $match2[1]); 
                                    $mapdata["date"][$i]     = $matchd[1];
                                    $mapdata["name"][$i]     = $smatch[$j][1];
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    print "<table class=\"facts_table\">\n";
    if ($i == 0) {
        print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_gmtab"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[5]='tab_cell_inactive_empty'; document.getElementById('pagetab5').className='tab_cell_inactive_empty';</script></td></tr>\n";
        print "<script type=\"text/javascript\">\n";
        print "function ResizeMap ()\n{\n}\n</script>\n";
    }
    else {
        print "<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$GOOGLEMAP_API_KEY."\" type=\"text/javascript\"></script>\n";
        print "<script src=\"modules/googlemap/pgvGoogleMap.js\"type=\"text/javascript\"></script>\n";
        print "<script type=\"text/javascript\">\n";
        print "    if (window.attachEvent) {\n";
        print "        window.attachEvent(\"onload\", function() {\n";
        print "            loadMap(".$GOOGLEMAP_MAP_TYPE.");      // Internet Explorer\n";
        print "        });\n";
        print "        window.attachEvent(\"onunload\", function() {\n";
        print "            GUnload();      // Internet Explorer\n";
        print "        });\n";
        print "    } else {\n";
        print "        window.addEventListener(\"load\", function() {\n";
        print "            loadMap(".$GOOGLEMAP_MAP_TYPE."); // Firefox and standard browsers\n";
        print "        }, false);\n";
        print "        window.addEventListener(\"unload\", function() {\n";
        print "            GUnload(); // Firefox and standard browsers\n";
        print "        }, false);\n";
        print "    }\n\n";
        print "var minZoomLevel = ".$GOOGLEMAP_MIN_ZOOM.";\n";
        print "var maxZoomLevel = ".$GOOGLEMAP_MAX_ZOOM.";\n";
        print "function SetMarkersAndBounds ()\n{\n";
        print "    var bounds = new GLatLngBounds();\n";

        for($j=1; $j<=$i; $j++) {
            if ($mapdata["show"][$j] == "yes") {
                print "    bounds.extend(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."));\n";
            }
        }
        print "    SetBoundaries(bounds);\n";

        $indexcounter = 0;
        for($j=1; $j<=$i; $j++) {
            if($marker["placed"][$j] == "no") {
                $multimarker = -1;
                // Count nr of locations where the long/lati is identical
                for($k=$j; $k<=$i; $k++) {
                    if (($mapdata["lati"][$j] == $mapdata["lati"][$k]) && ($mapdata["lng"][$j] == $mapdata["lng"][$k])) {
                        $multimarker = $multimarker + 1;
                    }
                }

                if ($multimarker == 0) {        // Only one location with this long/lati combination
                    $marker["placed"][$j] = "yes";
                    if ($mapdata["show"][$j] == "yes") {
                        print "    var ".$marker["name"][$j]." = new GMarker(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."));\n";
                        print "    GEvent.addListener(".$marker["name"][$j].", \"click\", function() {\n";
                        print "        ".$marker["name"][$j].".openInfoWindowHtml(\"";
                        print $mapdata["fact"][$j].":<br>";
                        if ($mapdata["name"][$j] != "") {
                            print "<a href=\\\"individual.php?pid=".$mapdata["name"][$j]."&amp;ged=$GEDCOM\\\">";
                            if (displayDetailsById($mapdata["name"][$j])||showLivingNameById($mapdata["name"][$j])) print PrintReady(get_person_name($mapdata["name"][$j]));
                            else print $pgv_lang["private"];
                            print "</a><br>";
                        }
                        print_fact_place_map($mapdata["placerec"][$j]);
                        print "<br>".get_changed_date($mapdata["date"][$j]);
                        print "\");\n";
                        print "    });\n";
                        print "    markers.push(".$marker["name"][$j].");\n";
                        print "    map.addOverlay(".$marker["name"][$j].");\n";
                        $marker["index"][$j] = $indexcounter;
                        $marker["tabindex"][$j] = -1;
                        $indexcounter = $indexcounter + 1;
                    }
                }
                if ($multimarker > 0) {
                    $tabcounter = 0;
                    $markerindex = 0;
                    $marker["placed"][$j] = "yes";
                    if ($mapdata["show"][$j] == "yes") {
                        print "    var ".$marker["name"][$j]."_".$markerindex." = new GMarker(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."));\n";
                        print "    var ".$marker["name"][$j]."_".$markerindex."Info = [\n";
                        $marker["index"][$j] = $indexcounter;
                        $marker["tabindex"][$j] = $tabcounter;
                        $tabcounter = $tabcounter + 1;
                        print "       new GInfoWindowTab(\"".$mapdata["fact"][$j]."\", \"<div style=\'width:360px\'>".$mapdata["fact"][$j].":<br>";
                        if ($mapdata["name"][$j] != "") {
                            print "<a href=\\\"individual.php?pid=".$mapdata["name"][$j]."&amp;ged=$GEDCOM\\\">";
                            if (displayDetailsById($mapdata["name"][$j])||showLivingNameById($mapdata["name"][$j])) print PrintReady(get_person_name($mapdata["name"][$j]));
                            else print $pgv_lang["private"];
                            print "</a><br>";
                        }
                        print_fact_place_map($mapdata["placerec"][$j]);
                        print "<br>".get_changed_date($mapdata["date"][$j]);
                        print "\")";
                    }
                    for($k=$j+1; $k<=$i; $k++) {
                        if (($mapdata["lati"][$j] == $mapdata["lati"][$k]) && ($mapdata["lng"][$j] == $mapdata["lng"][$k])) {
                            $marker["placed"][$k] = "yes";
                            $marker["index"][$k] = $indexcounter;
                            if ($mapdata["show"][$k] == "yes") {
                                if ($tabcounter == 4) {
                                    print "\n";
                                    print "    ];\n";
                                    print "    GEvent.addListener(".$marker["name"][$j]."_".$markerindex.", \"click\", function(tabToSelect) {\n";
                                    print "            ".$marker["name"][$j]."_".$markerindex.".openInfoWindowTabsHtml(".$marker["name"][$j]."_".$markerindex."Info, {selectedTab: tabToSelect});\n";
                                    print "    });\n";
                                    print "    markers.push(".$marker["name"][$j]."_".$markerindex.");\n";
                                    print "    map.addOverlay(".$marker["name"][$j]."_".$markerindex.");\n";
                                    $indexcounter = $indexcounter + 1;
                                    $tabcounter = 0;
                                    $markerindex = $markerindex + 1;
                                    print "    var ".$marker["name"][$j]."_".$markerindex." = new GMarker(new GLatLng(".($mapdata["lati"][$j]-(0.0015*$markerindex)).", ".($mapdata["lng"][$j]+(0.0025*$markerindex))."));\n";
                                    print "    var ".$marker["name"][$j]."_".$markerindex."Info = [\n";
                                }
                                else
                                {
                                    print ",\n";
                                }
                                $marker["index"][$k] = $indexcounter;
                                $marker["tabindex"][$k] = $tabcounter;
                                $tabcounter = $tabcounter + 1;
                                print "       new GInfoWindowTab(\"".$mapdata["fact"][$k]."\", \"<div style=\'width:360px\'>".$mapdata["fact"][$k].":<br>";
                                if ($mapdata["name"][$k] != "") {
                                    print "<a href=\\\"individual.php?pid=".$mapdata["name"][$k]."&amp;ged=$GEDCOM\\\">";
                                    if (displayDetailsById($mapdata["name"][$k])||showLivingNameById($mapdata["name"][$k])) print PrintReady(get_person_name($mapdata["name"][$k]));
                                    else print $pgv_lang["private"];
                                    print "</a><br>";
                                }
                                print_fact_place_map($mapdata["placerec"][$k]);
                                print "<br>".get_changed_date($mapdata["date"][$k]);
                                print "\")";
                            }
                        }
                    }
                    print "\n";
                    print "    ];\n";
                    print "    GEvent.addListener(".$marker["name"][$j]."_".$markerindex.", \"click\", function(tabToSelect) {\n";
                    print "            ".$marker["name"][$j]."_".$markerindex.".openInfoWindowTabsHtml(".$marker["name"][$j]."_".$markerindex."Info, {selectedTab: tabToSelect});\n";
                    print "    });\n";
                    print "    markers.push(".$marker["name"][$j]."_".$markerindex.");\n";
                    print "    map.addOverlay(".$marker["name"][$j]."_".$markerindex.");\n";
                    $indexcounter = $indexcounter + 1;
                }
            }
        }
        print "}\n";
        print "</script>\n";
        print "<table width=\"95%\">\n";
        print "<tr><td valign=\"top\" width=\"".$GOOGLEMAP_XSIZE."px\">\n";
        print "<div id=\"map_pane\" style=\"width: ".$GOOGLEMAP_XSIZE."px; height: ".$GOOGLEMAP_YSIZE."px\"></div>\n";
        print "<table width=100%><tr>\n";
        print "<td align=\"left\"><a href=\"javascript:ResizeMap()\">".$pgv_lang["gm_redraw_map"]."</a></td>\n";
        print "<td align=\"right\">\n";
        print "<a href=\"javascript:map.setMapType(G_NORMAL_MAP)\">".$pgv_lang["gm_map"]."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        print "<a href=\"javascript:map.setMapType(G_SATELLITE_MAP)\">".$pgv_lang["gm_satellite"]."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        print "<a href=\"javascript:map.setMapType(G_HYBRID_MAP)\">".$pgv_lang["gm_hybrid"]."</a>\n";
        print "</td></tr>\n";
        if (userIsAdmin(getUserName())) {
            print "<tr><td align=\"center\" colspan=\"2\">\n";
            print "<a href=\"".$SERVER_URL."/module.php?mod=googlemap&pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
            print "</td></tr>\n";
        }
        print "</table>\n";
        print "<td valign=\"top\">\n";
        print "\t<table class=\"facts_table\">";
    }
    if ($i>0) {
        for($j=1; $j<=$i; $j++) {
            if ($mapdata["show"][$j] == "yes") {
                print "<tr><td class=\"facts_label\">\n";
                print "<a href=\"javascript:highlight(".$marker["index"][$j].", ".$marker["tabindex"][$j].")\">".$mapdata["fact"][$j]."</a>:</td>\n";
                print "<td class=\"facts_value, person_box";
                if ($mapdata["sex"][$j] == "F") {
                    print "F";
                }
                if ($mapdata["sex"][$j] == "NN") {
                    print "NN";
                }
                print "\" colspan=\"2\">\n";
                if ($mapdata["name"][$j] != "") {
                    print "<a href=\"individual.php?pid=".$mapdata["name"][$j]."&amp;ged=$GEDCOM\">\n";
                    if (displayDetailsById($mapdata["name"][$j])||showLivingNameById($mapdata["name"][$j])) print PrintReady(get_person_name($mapdata["name"][$j]));
                    else print $pgv_lang["private"];
                    print "</a><br>\n";
                }
                print_fact_place($mapdata["placerec"][$j], true, false, true);
                print "<br>\n";
                print get_date_url($mapdata["date"][$j])."<br>\n";
                print "</td></tr>\n";
            }
        }
        print "\n\t</table>\n<br />";
    }
    print "\n\t</td></tr>";
    print "\n\t</table>\n<br />";

    return $i;
}

?>
