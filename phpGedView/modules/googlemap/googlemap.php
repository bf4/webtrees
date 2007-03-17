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

require('modules/googlemap/defaultconfig.php');
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');

require( "modules/googlemap/".$pgv_language["english"]);
if (file_exists( "modules/googlemap/".$pgv_language[$LANGUAGE])) require  "modules/googlemap/".$pgv_language[$LANGUAGE];

// functions copied from print_fact_place
function print_fact_place_map($factrec) {
    global $SHOW_PEDIGREE_PLACES, $TEMPLE_CODES, $pgv_lang, $factarray;

    $retStr = "";
    $out = false;
    $ct = preg_match("/2 PLAC (.*)/", $factrec, $match);
    if ($ct>0) {
        $retStr = " ";
        $levels = preg_split("/,/", $match[1]);
        $place = trim($match[1]);
        // reverse the array so that we get the top level first
        $levels = array_reverse($levels);
        $retStr .= "<a href=\"placelist.php?action=show&amp;";
        foreach($levels as $pindex=>$ppart) {
             // routine for replacing ampersands
             $ppart = preg_replace("/amp\%3B/", "", trim($ppart));
             $retStr .= "parent[$pindex]=".PrintReady($ppart)."&amp;";
        }
        $retStr .= "level=".count($levels);
        $retStr .= "\"> ".PrintReady($place)."</a>";
        return $retStr;
    }
    return "";
}


function print_address_structure_map($factrec, $level) {
     global $pgv_lang;
     global $factarray;
     global $WORD_WRAPPED_NOTES;
     global $POSTAL_CODE;

     //  $POSTAL_CODE = 'false' - before city, 'true' - after city and/or state
     //-- define per gedcom till can do per address countries in address languages
     //-- then this will be the default when country not recognized or does not exist
     //-- both Finland and Suomi are valid for Finland etc.
     //-- see http://www.bitboost.com/ref/international-address-formats.html

     $nlevel = $level+1;
     $ct = preg_match_all("/$level ADDR(.*)/", $factrec, $omatch, PREG_SET_ORDER);
     for($i=0; $i<$ct; $i++) {
          $arec = get_sub_record($level, "$level ADDR", $factrec, $i+1);
          $resultText = "";
          $cn = preg_match("/$nlevel _NAME (.*)/", $arec, $cmatch);
          if ($cn>0) $resultText .= str_replace("/", "", $cmatch[1])."<br />";
          $resultText .= PrintReady(trim($omatch[$i][1]));
          $cont = get_cont($nlevel, $arec);
          if (!empty($cont)) $resultText .= str_replace(array(" ", "<br&nbsp;"), array("&nbsp;", "<br "), PrintReady($cont));
          else {
              if (strlen(trim($omatch[$i][1])) > 0) print "<br />";
              $cs = preg_match("/$nlevel ADR1 (.*)/", $arec, $cmatch);
              if ($cs>0) {
                  if ($cn==0) {
                      $resultText .= "<br />";
                      $cn=0;
                  }
                  $resultText .= PrintReady($cmatch[1]);
              }
              $cs = preg_match("/$nlevel ADR2 (.*)/", $arec, $cmatch);
              if ($cs>0) {
                  if ($cn==0) {
                      $resultText .= "<br />";
                      $cn=0;
                  }
                  $resultText .= PrintReady($cmatch[1]);
              }

              if (!$POSTAL_CODE) {
                  $cs = preg_match("/$nlevel POST (.*)/", $arec, $cmatch);
                  if ($cs>0) {
                      $resultText .= "<br />".PrintReady($cmatch[1]);
                  }
                  $cs = preg_match("/$nlevel CITY (.*)/", $arec, $cmatch);
                  if ($cs>0) {
                      $resultText .= " ".PrintReady($cmatch[1]);
                  }
                  $cs = preg_match("/$nlevel STAE (.*)/", $arec, $cmatch);
                  if ($cs>0) {
                      $resultText .= ", ".PrintReady($cmatch[1]);
                  }
              }
              else {
                  $cs = preg_match("/$nlevel CITY (.*)/", $arec, $cmatch);
                  if ($cs>0) {
                      $resultText .= "<br />".PrintReady($cmatch[1]);
                  }
                  $cs = preg_match("/$nlevel STAE (.*)/", $arec, $cmatch);
                  if ($cs>0) {
                      $resultText .= ", ".PrintReady($cmatch[1]);
                  }
                  $cs = preg_match("/$nlevel POST (.*)/", $arec, $cmatch);
                  if ($cs>0) {
                      $resultText .= " ".PrintReady($cmatch[1]);
                  }
             }

             $cs = preg_match("/$nlevel CTRY (.*)/", $arec, $cmatch);
              if ($cs>0) {
                  $resultText .= "<br />".PrintReady($cmatch[1]);
              }
          }
          $resultText .= "<br />";
          // Here we can examine the resultant text and remove empty tags
          print str_replace(chr(10), ' ' , $resultText);
     }
     $resultText = "";
     $resultText .= "<table>";
     $ct = preg_match_all("/$level PHON (.*)/", $factrec, $omatch, PREG_SET_ORDER);
     if ($ct>0) {
          for($i=0; $i<$ct; $i++) {
               $resultText .= "<tr>";
               $resultText .= "\t\t<td><span class=\"label\"><b>".$factarray["PHON"].": </b></span></td><td><span class=\"field\">";
               $resultText .= "&lrm;".$omatch[$i][1]."&lrm;";
               $resultText .= "</span></td></tr>";
          }
     }
     $ct = preg_match_all("/$level FAX (.*)/", $factrec, $omatch, PREG_SET_ORDER);
     if ($ct>0) {
          for($i=0; $i<$ct; $i++) {
               $resultText .= "<tr>";
               $resultText .= "\t\t<td><span class=\"label\"><b>".$factarray["FAX"].": </b></span></td><td><span class=\"field\">";
               $resultText .= "&lrm;".$omatch[$i][1]."&lrm;";
               $resultText .= "</span></td></tr>";
          }
     }
     $ct = preg_match_all("/$level EMAIL (.*)/", $factrec, $omatch, PREG_SET_ORDER);
     if ($ct>0) {
          for($i=0; $i<$ct; $i++) {
               $resultText .= "<tr>";
               $resultText .= "\t\t<td><span class=\"label\"><b>".$factarray["EMAIL"].": </b></span></td><td><span class=\"field\">";
               $resultText .= "<a href=\"mailto:".$omatch[$i][1]."\">".$omatch[$i][1]."</a>";
               $resultText .= "</span></td></tr>";
          }
     }
     $ct = preg_match_all("/$level (WWW|URL) (.*)/", $factrec, $omatch, PREG_SET_ORDER);
     if ($ct>0) {
          for($i=0; $i<$ct; $i++) {
               $resultText .= "<tr>";
               $resultText .= "\t\t<td><span class=\"label\"><b>".$factarray["URL"].": </b></span></td><td><span class=\"field\">";
               $resultText .= "<a href=\"".$omatch[$i][2]."\" target=\"_blank\">".$omatch[$i][2]."</a>";
               $resultText .= "</span></td></tr>";
          }
     }
     $resultText .= "</table>";
     if ($resultText!="<table></table>") print str_replace(chr(10), ' ' , $resultText);
}

function rem_prefix_from_placename($prefix_list, $place, $placelist) {
    $prefix_split = preg_split ("/;/", $prefix_list);
    foreach ($prefix_split as $key2 => $prefix) {
        if ($prefix != "") {
            if (preg_match('/^'.$prefix.' (.*)/', $place, $matches) != 0) {
                $placelist[] = $matches[1];
            }
        }
    }
    return $placelist;
}

function rem_postfix_from_placename($postfix_list, $place, $placelist) {
    $postfix_split = preg_split ("/;/", $postfix_list);
    foreach ($postfix_split as $key3 => $postfix) {
        if($postfix != "") {
            if (preg_match('/^(.*) '.$postfix.'$/', $place, $matches) != 0) {
                $placelist[] = $matches[1];
            }
        }
    }
    return $placelist;
}

function rem_prefix_postfix_from_placename($prefix_list, $postfix_list, $place, $placelist) {
    $prefix_split = preg_split ("/;/", $prefix_list);
    $postfix_split = preg_split ("/;/", $postfix_list);
    foreach ($prefix_split as $key2 => $prefix) {
        if ($prefix != "") {
            foreach ($postfix_split as $key3 => $postfix) {
                if ($postfix != "") {
                    if (preg_match('/^'.$prefix.' (.*) '.$postfix.'$/', $place, $matches) != 0) {
                        $placelist[] = $matches[1];
                    }
                }
            }
        }
    }
    return $placelist;
}

function create_possible_place_names ($placename, $level) {
    global $GM_PREFIX, $GM_POSTFIX, $GM_PRE_POST_MODE;

    $retlist = array();

    switch ($GM_PRE_POST_MODE[$level]) {
    case 0:     // 0: no pre/postfix
        $retlist[] = $placename;
        break;
    case 1:     // 1 = Normal name, Prefix, Postfix, Both
        $retlist[] = $placename;
        $retlist = rem_prefix_from_placename($GM_PREFIX[$level], $placename, $retlist);
        $retlist = rem_postfix_from_placename($GM_POSTFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_postfix_from_placename($GM_PREFIX[$level], $GM_POSTFIX[$level], $placename, $retlist);
        break;
    case 2:     // 2 = Normal name, Postfix, Prefxi, Both
        $retlist[] = $placename;
        $retlist = rem_postfix_from_placename($GM_POSTFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_from_placename($GM_PREFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_postfix_from_placename($GM_PREFIX[$level], $GM_POSTFIX[$level], $placename, $retlist);
        break;
    case 3:     // 3 = Prefix, Postfix, Both, Normal name
        $retlist = rem_prefix_from_placename($GM_PREFIX[$level], $placename, $retlist);
        $retlist = rem_postfix_from_placename($GM_POSTFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_postfix_from_placename($GM_PREFIX[$level], $GM_POSTFIX[$level], $placename, $retlist);
        $retlist[] = $placename;
        break;
    case 4:     // 4 = Postfix, Prefix, Both, Normal name
        $retlist = rem_postfix_from_placename($GM_POSTFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_from_placename($GM_PREFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_postfix_from_placename($GM_PREFIX[$level], $GM_POSTFIX[$level], $placename, $retlist);
        $retlist[] = $placename;
        break;
    case 5:     // 5 = Prefix, Postfix, Normal name, Both
        $retlist = rem_prefix_from_placename($GM_PREFIX[$level], $placename, $retlist);
        $retlist = rem_postfix_from_placename($GM_POSTFIX[$level], $placename, $retlist);
        $retlist[] = $placename;
        $retlist = rem_prefix_postfix_from_placename($GM_PREFIX[$level], $GM_POSTFIX[$level], $placename, $retlist);
        break;
    case 6:     // 6 = Postfix, Prefix, Normal name, Both
        $retlist = rem_postfix_from_placename($GM_POSTFIX[$level], $placename, $retlist);
        $retlist = rem_prefix_from_placename($GM_PREFIX[$level], $placename, $retlist);
        $retlist[] = $placename;
        $retlist = rem_prefix_postfix_from_placename($GM_PREFIX[$level], $GM_POSTFIX[$level], $placename, $retlist);
        break;
    }
    return $retlist;
}

function get_lati_long_placelocation ($place) {
    global $DBCONN, $TBLPREFIX;
    $parent = preg_split ("/,/", $place);
    $parent = array_reverse($parent);
    $place_id = 0;
    for($i=0; $i<count($parent); $i++) {
        $parent[$i] = rtrim(ltrim($parent[$i]));
        if($parent[$i] != "") {
            $placelist = create_possible_place_names($parent[$i], $i+1);
            foreach ($placelist as $key => $placename) {
                $escparent=preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($placename));
                $psql = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_level=".$i." AND pl_parent_id=$place_id AND pl_place LIKE '".$escparent."' ORDER BY pl_place";
                $res = dbquery($psql);
                $row =& $res->fetchRow();
                $res->free();
                if (!empty($row[0])) break;
            }
            if (empty($row[0])) break;
            $place_id = $row[0];
        } else {
            break;
        }
    }

    $retval = array();
    if ($place_id > 0) {
        $psql = "SELECT pl_lati,pl_long,pl_zoom,pl_icon,pl_level FROM ".$TBLPREFIX."placelocation WHERE pl_id=$place_id ORDER BY pl_place";
        $res = dbquery($psql);
        $row =& $res->fetchRow();
        $res->free();
        $retval["lati"] = rtrim(ltrim($row[0]));
        $retval["long"] = rtrim(ltrim($row[1]));
        $retval["zoom"] = rtrim(ltrim($row[2]));
        $retval["icon"] = rtrim(ltrim($row[3]));
        $retval["level"] = $row[4];
    }
    return $retval;
}

function setup_map() {
	global $GOOGLEMAP_ENABLED, $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GOOGLEMAP_MIN_ZOOM, $GOOGLEMAP_MAX_ZOOM, $pgv_lang;
	if ($GOOGLEMAP_ENABLED == "false") {
		return;
	}
    ?>
    <script src="http://maps.google.com/maps?file=api&v=2&key=<?php print $GOOGLEMAP_API_KEY; ?>" type="text/javascript"></script>
    <script src="modules/googlemap/pgvGoogleMap.js" type="text/javascript"></script>
    <script type="text/javascript">
        if (window.attachEvent) {
            window.attachEvent("onload", function() {
                loadMap(<?php print $GOOGLEMAP_MAP_TYPE;?>);      // Internet Explorer
                if (loadedTabs[7]) SetMarkersAndBounds();
            });
            window.attachEvent("onunload", function() {
                GUnload();      // Internet Explorer
            });
        } else {
            window.addEventListener("load", function() {
                loadMap(<?php print $GOOGLEMAP_MAP_TYPE;?>);      // Firefox and standard browsers
                if (loadedTabs[7]) SetMarkersAndBounds();
            }, false);
            window.addEventListener("unload", function() {
                GUnload(); // Firefox and standard browsers
            }, false);
        }
    var minZoomLevel = <?php print $GOOGLEMAP_MIN_ZOOM;?>;
    var maxZoomLevel = <?php print $GOOGLEMAP_MAX_ZOOM;?>;
    var startZoomLevel = <?php print $GOOGLEMAP_MAX_ZOOM;?>;
    </script>
    <?php
}

function build_indiv_map($indifacts, $famids) {
    global $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GOOGLEMAP_MIN_ZOON, $GOOGLEMAP_MAX_ZOON, $GEDCOM;
    global $GOOGLEMAP_XSIZE, $GOOGLEMAP_YSIZE, $pgv_lang, $factarray, $SHOW_LIVING_NAMES, $PRIV_PUBLIC;
    global $GOOGLEMAP_MAX_ZOOM, $GOOGLEMAP_MIN_ZOOM, $GOOGLEMAP_ENABLED, $TBLPREFIX, $DBCONN;
    global $TEXT_DIRECTION, $GM_DEFAULT_TOP_VALUE;

    if ($GOOGLEMAP_ENABLED == "false") {
        print "<table class=\"facts_table\">\n";
        print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_disabled"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[5]='tab_cell_inactive_empty'; document.getElementById('pagetab5').className='tab_cell_inactive_empty';</script></td></tr>\n";
        print "<script type=\"text/javascript\">\n";
        print "function ResizeMap ()\n{\n}\nfunction SetMarkersAndBounds ()\n{\n}\n</script>\n";
        if (userIsAdmin(getUserName())) {
            print "<tr><td align=\"center\" colspan=\"2\">\n";
            print "<a href=\"module.php?mod=googlemap&pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
            print "</td></tr>\n";
        }
        print "\n\t</table>\n<br />";
        ?>
        <script type="text/javascript">
        	document.getElementById("googlemap_left").innerHTML = document.getElementById("googlemap_content").innerHTML;
        	document.getElementById("googlemap_content").innerHTML = "";
        </script>
        <?php
        return;
    }

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

    $zoomLevel = $GOOGLEMAP_MAX_ZOOM;
    $tables = $DBCONN->getListOf('tables');
    if (in_array($TBLPREFIX."placelocation", $tables)) $placelocation = true;
    else $placelocation = false;
    //-- sort the facts
    usort($indifacts, "compare_facts");
    $i = 0;
    foreach ($indifacts as $key => $value) {
        $ft = preg_match("/1 (\w+)(.*)/", $value[1], $match);
        if ($ft>0) {
            $fact = trim($match[1]);
            $placerec = null;
            $ct = preg_match("/2 PLAC (.*)/", $value[1], $match);
            if ($ct>0) {
                $placerec = get_sub_record(2, "2 PLAC", $value[1]);
                $addrFound = false;
            }
            else {
                $ct = preg_match("/\d ADDR (.*)/", $value[1], $match);
                if ($ct>0) {
                    $placerec = get_sub_record(1, "\d ADDR", $value[1]);
                    $addrFound = true;
                }
            }
            if (!empty($placerec)) {
                $ctla = preg_match("/\d LATI (.*)/", $placerec, $match1);
                $ctlo = preg_match("/\d LONG (.*)/", $placerec, $match2);
                $spouserec = get_sub_record(1, "1 _PGVS", $value[1]);
                $ctlp = preg_match("/\d _PGVS @(.*)@/", $spouserec, $spouseid);
                if ($ctlp>0) {
                    if (displayDetailsByID($spouseid[1])) {
                        $useThisItem = true;
                    }
                    else {
                        $useThisItem = false;
                    }
                }
                else {
                    $useThisItem = true;
                }
                if (($ctla>0) && ($ctlo>0) && ($useThisItem==true)) {
                    $i = $i + 1;
										if ($fact == "EVEN" || $fact=="FACT") {
											$eventrec = get_sub_record(1, "2 TYPE", $value[1]);
											if (preg_match("/\d TYPE (.*)/", $eventrec, $match3))
												if (isset($factarray[$match3[1]]))
													$mapdata["fact"][$i]=$factarray[$match3[1]];
												else
													$mapdata["fact"][$i]=$match3[1];
											else
												$mapdata["fact"][$i]=$factarray[$fact];
										} else {
											$mapdata["fact"][$i]=$factarray[$fact];
										}
                    $mapdata["show"][$i] = "yes";
                    $marker["name"][$i] = "Marker".$i;
                    $marker["placed"][$i] = "no";
                    $marker["icon"][$i]      = "";
                    $mapdata["placerec"][$i] = $placerec;
                    $match1[1] = ltrim(rtrim($match1[1]));
                    $match2[1] = ltrim(rtrim($match2[1]));
                    $mapdata["lati"][$i] = str_replace(array('N', 'S', ','), array('', '-', '.') , $match1[1]);
                    $mapdata["lng"][$i] = str_replace(array('E', 'W', ','), array('', '-', '.') , $match2[1]);
                    $ctd = preg_match("/2 DATE (.+)/", $value[1], $match);
                    if ($ctd>0) {
                        $mapdata["date"][$i] = $match[1];
                    }
                    else {
                        $mapdata["date"][$i] = "";
                    }
                    $mapdata["name"][$i]="";
                    if ($ctlp>0) {
                        $mapdata["name"][$i]=$spouseid[1];
                    }
                    $mapdata["sex"][$i]  = "-";
                }
                else {
                    if (($placelocation == true) && ($useThisItem==true) && ($addrFound==false)) {
                        $ctpl = preg_match("/\d PLAC (.*)/", $placerec, $match1);
                        $latlongval = get_lati_long_placelocation($match1[1]);
                        if ((count($latlongval) == 0) && ($GM_DEFAULT_TOP_VALUE != "")) {
                            $latlongval = get_lati_long_placelocation($match1[1].", ".$GM_DEFAULT_TOP_VALUE);
                            if ((count($latlongval) != 0) && ($latlongval["level"] == 0)) {
                                $latlongval["lati"] = NULL;
                                $latlongval["long"] = NULL;
                            }
                        }
                        if ((count($latlongval) != 0) && ($latlongval["lati"] != NULL) && ($latlongval["long"] != NULL)) {
                            $i = $i + 1;
														if ($fact == "EVEN" || $fact=="FACT") {
															$eventrec = get_sub_record(1, "2 TYPE", $value[1]);
															if (preg_match("/\d TYPE (.*)/", $eventrec, $match3))
																if (isset($factarray[$match3[1]]))
																	$mapdata["fact"][$i]=$factarray[$match3[1]];
																else
																	$mapdata["fact"][$i]=$match3[1];
															else
																$mapdata["fact"][$i]=$factarray[$fact];
														} else {
															$mapdata["fact"][$i]=$factarray[$fact];
														}
                            $mapdata["show"][$i] = "yes";
                            $marker["name"][$i] = "Marker".$i;
                            $marker["placed"][$i] = "no";
                            $marker["icon"][$i] = $latlongval["icon"];
                            $mapdata["placerec"][$i] = $placerec;
                            if ($zoomLevel > $latlongval["zoom"]) $zoomLevel = $latlongval["zoom"];
                            $mapdata["lati"][$i] = str_replace(array('N', 'S', ','), array('', '-', '.') , $latlongval["lati"]);
                            $mapdata["lng"][$i] = str_replace(array('E', 'W', ','), array('', '-', '.') , $latlongval["long"]);
                            $ctd = preg_match("/2 DATE (.+)/", $value[1], $match);
                            if ($ctd>0) {
                                $mapdata["date"][$i] = $match[1];
                            }
                            else {
                                $mapdata["date"][$i] = "";
                            }
                            $mapdata["name"][$i]="";
                            if ($ctlp>0) {
                                $mapdata["name"][$i]=$spouseid[1];
                            }
                            $mapdata["sex"][$i]  = "-";
                        }
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
                if (empty($famrec)) $famrec = find_updated_record($famids[$f]);
                if ($famrec) {
                    $num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch,PREG_SET_ORDER);
                    for($j=0; $j<$num; $j++) {
                        $srec = find_person_record($smatch[$j][1]);
                        if (empty($srec)) $srec = find_updated_record($smatch[$j][1]);
                        $birthrec = get_sub_record(1, "1 BIRT", $srec);
                        $placerec = get_sub_record(2, "2 PLAC", $birthrec);
                        if (!empty($placerec)) {
                            $ctd = preg_match("/\d DATE (.*)/", $birthrec, $matchd);
                            $ctla = preg_match("/\d LATI (.*)/", $placerec, $match1);
                            $ctlo = preg_match("/\d LONG (.*)/", $placerec, $match2);
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
                                    $marker["icon"][$i]      = "";
                                    $mapdata["placerec"][$i] = $placerec;
                                    $match1[1] = ltrim(rtrim($match1[1]));
                                    $match2[1] = ltrim(rtrim($match2[1]));
                                    $mapdata["lati"][$i]     = str_replace(array('N', 'S', ','), array('', '-', '.'), $match1[1]);
                                    $mapdata["lng"][$i]      = str_replace(array('E', 'W', ','), array('', '-', '.'), $match2[1]);
                                    if ($ctd > 0) {
                                        $mapdata["date"][$i] = $matchd[1];
                                    } else {
                                        $mapdata["date"][$i] = "";
                                    }
                                    $mapdata["name"][$i]     = $smatch[$j][1];
                                }
                            }
                            else {
                                if ($placelocation == true) {
                                    $ctpl = preg_match("/\d PLAC (.*)/", $placerec, $match1);
                                    $latlongval = get_lati_long_placelocation($match1[1]);
                                    if ((count($latlongval) == 0) && ($GM_DEFAULT_TOP_VALUE != "")) {
                                        $latlongval = get_lati_long_placelocation($match1[1].", ".$GM_DEFAULT_TOP_VALUE);
                                        if ((count($latlongval) != 0) && ($latlongval["level"] == 0)) {
                                            $latlongval["lati"] = NULL;
                                            $latlongval["long"] = NULL;
                                        }
                                    }
                                    if ((count($latlongval) != 0) && ($latlongval["lati"] != NULL) && ($latlongval["long"] != NULL)) {
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
                                            $marker["icon"][$i] = $latlongval["icon"];
                                            $mapdata["placerec"][$i] = $placerec;
                                            if ($zoomLevel > $latlongval["zoom"]) $zoomLevel = $latlongval["zoom"];
                                            $mapdata["lati"][$i]     = str_replace(array('N', 'S', ','), array('', '-', '.'), $latlongval["lati"]);
                                            $mapdata["lng"][$i]      = str_replace(array('E', 'W', ','), array('', '-', '.'), $latlongval["long"]);
                                            if ($ctd > 0) {
                                                $mapdata["date"][$i] = $matchd[1];
                                            } else {
                                                $mapdata["date"][$i] = "";
                                            }
                                            $mapdata["name"][$i]     = $smatch[$j][1];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if ($i == 0) {
        print "<table class=\"facts_table\">\n";
        print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_gmtab"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[5]='tab_cell_inactive_empty'; document.getElementById('pagetab5').className='tab_cell_inactive_empty';</script></td></tr>\n";
        print "<script type=\"text/javascript\">\n";
        print "function ResizeMap ()\n{\n}\n</script>\n";
        if (userIsAdmin(getUserName())) {
            print "<tr><td align=\"center\" colspan=\"2\">\n";
            print "<a href=\"module.php?mod=googlemap&pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
            print "</td></tr>\n";
        }
    }
    else {
        ?>
        <script type="text/javascript">
        function SetMarkersAndBounds (){
            var bounds = new GLatLngBounds();
        <?php
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
                        if ($marker["icon"][$j] == "") {
                            print "    var ".$marker["name"][$j]." = new GMarker(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."));\n";
                        }
                        else {
                            print "    var ".$marker["name"][$j]."_flag = new GIcon();\n";
                            print "    ".$marker["name"][$j]."_flag.image = \"".$marker["icon"][$j]."\";\n";
                            print "    ".$marker["name"][$j]."_flag.shadow = \"modules/googlemap/flag_shadow.png\";\n";
                            print "    ".$marker["name"][$j]."_flag.iconSize = new GSize(25, 15);\n";
                            print "    ".$marker["name"][$j]."_flag.shadowSize = new GSize(35, 45);\n";
                            print "    ".$marker["name"][$j]."_flag.iconAnchor = new GPoint(1, 45);\n";
                            print "    ".$marker["name"][$j]."_flag.infoWindowAnchor = new GPoint(5, 1);\n";
                            print "    var ".$marker["name"][$j]." = new GMarker(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."), ".$marker["name"][$j]."_flag);\n";
                        }
                        print "    GEvent.addListener(".$marker["name"][$j].", \"click\", function() {\n";
                        print "        ".$marker["name"][$j].".openInfoWindowHtml(\"";
                        print PrintReady($mapdata["fact"][$j]).":<br/>";
                        if ($mapdata["name"][$j] != "") {
                            if (displayDetailsById($mapdata["name"][$j])||showLivingNameById($mapdata["name"][$j]))
                                print PrintReady(preg_replace("/\"/", "\\\"", get_person_name($mapdata["name"][$j])));
                            else
                                print $pgv_lang["private"];
                            print "</a><br/>";
                        }
                        if(preg_match("/2 PLAC (.*)/", $mapdata["placerec"][$j]) == 0) {
                            print_address_structure_map($mapdata["placerec"][$j], 1);
                        } else {
                            print preg_replace("/\"/", "\\\"", print_fact_place_map($mapdata["placerec"][$j]));
                            print "<br/>";
                        }
                        if ($mapdata["date"][$j] != "") {
                            print get_changed_date($mapdata["date"][$j]);
                        }
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
                        if ($marker["icon"][$j] == "") {
                            print "    var ".$marker["name"][$j]."_".$markerindex." = new GMarker(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."));\n";
                        }
                        else {
                            print "    var ".$marker["name"][$j]."_".$markerindex."_flag = new GIcon();\n";
                            print "    ".$marker["name"][$j]."_".$markerindex."_flag.image = \"".$marker["icon"][$j]."\";\n";
                            print "    ".$marker["name"][$j]."_".$markerindex."_flag.shadow = \"modules/googlemap/flag_shadow.png\";\n";
                            print "    ".$marker["name"][$j]."_".$markerindex."_flag.iconSize = new GSize(25, 15);\n";
                            print "    ".$marker["name"][$j]."_".$markerindex."_flag.shadowSize = new GSize(35, 45);\n";
                            print "    ".$marker["name"][$j]."_".$markerindex."_flag.iconAnchor = new GPoint(1, 45);\n";
                            print "    ".$marker["name"][$j]."_".$markerindex."_flag.infoWindowAnchor = new GPoint(5, 1);\n";
                            print "    var ".$marker["name"][$j]."_".$markerindex." = new GMarker(new GLatLng(".$mapdata["lati"][$j].", ".$mapdata["lng"][$j]."), ".$marker["name"][$j]."_".$markerindex."_flag);\n";
                        }
                        print "    var ".$marker["name"][$j]."_".$markerindex."Info = [\n";
                        $marker["index"][$j] = $indexcounter;
                        $marker["tabindex"][$j] = $tabcounter;
                        $tabcounter = $tabcounter + 1;
                        print "       new GInfoWindowTab(\"".$mapdata["fact"][$j]."\", \"<div style='width:360px'>".PrintReady($mapdata["fact"][$j]).":<br/>";
                        if ($mapdata["name"][$j] != "") {
                            print "<a href=\\\"individual.php?pid=".$mapdata["name"][$j]."&amp;ged=$GEDCOM\\\">";
                            if (displayDetailsById($mapdata["name"][$j])||showLivingNameById($mapdata["name"][$j]))
                                print PrintReady(preg_replace("/\"/", "\\\"", get_person_name($mapdata["name"][$j])));
                            else
                                print $pgv_lang["private"];
                            print "</a><br/>";
                        }
                        if(preg_match("/2 PLAC (.*)/", $mapdata["placerec"][$j]) == 0) {
                            print_address_structure_map($mapdata["placerec"][$j], 1);
                        } else {
                            print preg_replace("/\"/", "\\\"", print_fact_place_map($mapdata["placerec"][$j]));
                            print "<br/>";
                        }
                        if ($mapdata["date"][$j] != "") {
                            print get_changed_date($mapdata["date"][$j]);
                        }
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

                                    if ($marker["icon"][$j] == "") {
                                        print "    var ".$marker["name"][$j]."_".$markerindex." = new GMarker(new GLatLng(".($mapdata["lati"][$j]-(0.0015*$markerindex)).", ".($mapdata["lng"][$j]+(0.0025*$markerindex))."));\n";
                                    }
                                    else {
                                        print "    var ".$marker["name"][$j]."_".$markerindex."_flag = new GIcon();\n";
                                        print "    ".$marker["name"][$j]."_".$markerindex."_flag.image = \"".$marker["icon"][$j]."\";\n";
                                        print "    ".$marker["name"][$j]."_".$markerindex."_flag.shadow = \"modules/googlemap/flag_shadow.png\";\n";
                                        print "    ".$marker["name"][$j]."_".$markerindex."_flag.iconSize = new GSize(25, 15);\n";
                                        print "    ".$marker["name"][$j]."_".$markerindex."_flag.shadowSize = new GSize(35, 45);\n";
                                        print "    ".$marker["name"][$j]."_".$markerindex."_flag.iconAnchor = new GPoint(1, 45);\n";
                                        print "    ".$marker["name"][$j]."_".$markerindex."_flag.infoWindowAnchor = new GPoint(5, 1);\n";
                                        print "    var ".$marker["name"][$j]."_".$markerindex." = new GMarker(new GLatLng(".($mapdata["lati"][$j]-(0.0015*$markerindex)).", ".($mapdata["lng"][$j]+(0.0025*$markerindex))."), ".$marker["name"][$j]."_".$markerindex."_flag);\n";
                                    }
                                    print "    var ".$marker["name"][$j]."_".$markerindex."Info = [\n";
                                }
                                else
                                {
                                    print ",\n";
                                }
                                $marker["index"][$k] = $indexcounter;
                                $marker["tabindex"][$k] = $tabcounter;
                                $tabcounter = $tabcounter + 1;
                                print "       new GInfoWindowTab(\"".$mapdata["fact"][$k]."\", \"<div style='width:360px'>".$mapdata["fact"][$k].":<br/>";
                                if ($mapdata["name"][$k] != "") {
                                    print "<a href=\\\"individual.php?pid=".$mapdata["name"][$k]."&amp;ged=$GEDCOM\\\">";
                                    if (displayDetailsById($mapdata["name"][$k])||showLivingNameById($mapdata["name"][$k]))
                                        print PrintReady(preg_replace("/\"/", "\\\"", get_person_name($mapdata["name"][$k])));
                                    else
                                        print $pgv_lang["private"];
                                    print "</a><br/>";
                                }
                                if(preg_match("/2 PLAC (.*)/", $mapdata["placerec"][$k]) == 0) {
                                    print_address_structure_map($mapdata["placerec"][$k], 1);
                                } else {
                                    print preg_replace("/\"/", "\\\"", print_fact_place_map($mapdata["placerec"][$k]));
                                    print "<br/>";
                                }
                                if ($mapdata["date"][$j] != "") {
                                    print get_changed_date($mapdata["date"][$k]);
                                }
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
        print "\t<table class=\"facts_table\">";
    }
    if ($i>0) {
        for($j=1; $j<=$i; $j++) {
            if ($mapdata["show"][$j] == "yes") {
                print "<tr><td class=\"facts_label\">\n";
                print "<a href=\"javascript:highlight(".$marker["index"][$j].", ".$marker["tabindex"][$j].")\">".$mapdata["fact"][$j]."</a></td>\n";
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
                    print "</a><br/>\n";
                }
                if(preg_match("/2 PLAC (.*)/", $mapdata["placerec"][$j]) == 0) {
                    print_address_structure_map($mapdata["placerec"][$j], 1);
                } else {
                    print print_fact_place_map($mapdata["placerec"][$j]);
                    print "<br/>";
                }
                if ($mapdata["date"][$j] != "") {
                    print get_date_url($mapdata["date"][$j])."<br/>\n";
                }
                print "</td></tr>\n";
            }
        }
        print "\n\t</table>\n<br />";
    }
    print "\n<br />";

    return $i;
}

?>
