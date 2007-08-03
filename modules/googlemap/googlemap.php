<?php
/**
 * Google map module for phpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * $Id$
 * @author Johan Borkhuis
 */

require('modules/googlemap/defaultconfig.php');
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');

loadLangFile("gm_lang");

// functions copied from print_fact_place
function print_fact_place_map($factrec) {
	$ct = preg_match("/2 PLAC (.*)/", $factrec, $match);
	if ($ct>0) {
		$retStr = " ";
		$levels = explode(",", $match[1]);
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

			if ($POSTAL_CODE) {
				if (preg_match("/$nlevel CITY (.*)/", $arec, $cmatch))
					$resultText.=" ".PrintReady($cmatch[1]);
				if (preg_match("/$nlevel STAE (.*)/", $arec, $cmatch))
					$resultText.=", ".PrintReady($cmatch[1]);
				if (preg_match("/$nlevel POST (.*)/", $arec, $cmatch))
					$resultText.="<br />".PrintReady($cmatch[1]);
			} else {
				if (preg_match("/$nlevel POST (.*)/", $arec, $cmatch))
					$resultText.="<br />".PrintReady($cmatch[1]);
				if (preg_match("/$nlevel CITY (.*)/", $arec, $cmatch))
					$resultText.=" ".PrintReady($cmatch[1]);
				if (preg_match("/$nlevel STAE (.*)/", $arec, $cmatch))
					$resultText.=", ".PrintReady($cmatch[1]);
			}
		}
		if (preg_match("/$nlevel CTRY (.*)/", $arec, $cmatch))
			$resultText.="<br />".PrintReady($cmatch[1]);
		$resultText.= "<br />";
		// Here we can examine the resultant text and remove empty tags
		print str_replace(chr(10), ' ' , $resultText);
	}
	$resultText = "<table>";
	$ct = preg_match_all("/$level PHON (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".$factarray["PHON"].": </b></span></td><td><span class=\"field\">";
		$resultText .= getLRM() . $omatch[$i][1]. getLRM();
		$resultText .= "</span></td></tr>";
	}
	$ct = preg_match_all("/$level FAX (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".$factarray["FAX"].": </b></span></td><td><span class=\"field\">";
		$resultText .= getLRM() . $omatch[$i][1] . getLRM();
		$resultText .= "</span></td></tr>";
	}
	$ct = preg_match_all("/$level EMAIL (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".$factarray["EMAIL"].": </b></span></td><td><span class=\"field\">";
		$resultText .= "<a href=\"mailto:".$omatch[$i][1]."\">".$omatch[$i][1]."</a>";
		$resultText .= "</span></td></tr>";
	}
	$ct = preg_match_all("/$level (WWW|URL) (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".$factarray["URL"].": </b></span></td><td><span class=\"field\">";
		$resultText .= "<a href=\"".$omatch[$i][2]."\" target=\"_blank\">".$omatch[$i][2]."</a>";
		$resultText .= "</span></td></tr>";
	}
	$resultText .= "</table>";
	if ($resultText!="<table></table>") print str_replace(chr(10), ' ' , $resultText);
}

function rem_prefix_from_placename($prefix_list, $place, $placelist) {
	$prefix_split = explode(";", $prefix_list);
	foreach ($prefix_split as $prefix) {
		if (!empty($prefix)) {
			if (preg_match('/^'.$prefix.' (.*)/', $place, $matches) != 0) {
				$placelist[] = $matches[1];
			}
		}
	}
	return $placelist;
}

function rem_postfix_from_placename($postfix_list, $place, $placelist) {
	$postfix_split = explode (";", $postfix_list);
	foreach ($postfix_split as $postfix) {
		if (!empty($postfix)) {
			if (preg_match('/^(.*) '.$postfix.'$/', $place, $matches) != 0) {
				$placelist[] = $matches[1];
			}
		}
	}
	return $placelist;
}

function rem_prefix_postfix_from_placename($prefix_list, $postfix_list, $place, $placelist) {
	$prefix_split = explode (";", $prefix_list);
	$postfix_split = explode (";", $postfix_list);
	foreach ($prefix_split as $prefix) {
		if (!empty($prefix)) {
			foreach ($postfix_split as $postfix) {
				if (!empty($postfix)) {
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

	switch (@$GM_PRE_POST_MODE[$level]) {
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
	$parent = explode (",", $place);
	$parent = array_reverse($parent);
	$place_id = 0;
	for($i=0; $i<count($parent); $i++) {
		$parent[$i] = trim($parent[$i]);
		if (empty($parent[$i])) $parent[$i]="unknown";// GoogleMap module uses "unknown" while GEDCOM uses , ,
		$placelist = create_possible_place_names($parent[$i], $i+1);
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

	$retval = array();
	if ($place_id > 0) {
		$psql = "SELECT pl_lati,pl_long,pl_zoom,pl_icon,pl_level FROM {$TBLPREFIX}placelocation WHERE pl_id={$place_id} ORDER BY pl_place";
		$res = dbquery($psql);
		$row =& $res->fetchRow();
		$res->free();
		$retval["lati"] = trim($row[0]);
		$retval["long"] = trim($row[1]);
		$retval["zoom"] = trim($row[2]);
		$retval["icon"] = trim($row[3]);
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
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print $GOOGLEMAP_API_KEY; ?>" type="text/javascript"></script>
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
	var GOOGLEMAP_MAP_TYPE =<?php print $GOOGLEMAP_MAP_TYPE;?>;
	var minZoomLevel = <?php print $GOOGLEMAP_MIN_ZOOM;?>;
	var maxZoomLevel = <?php print $GOOGLEMAP_MAX_ZOOM;?>;
	var startZoomLevel = <?php print $GOOGLEMAP_MAX_ZOOM;?>;
	//]]>
	</script>
	<?php
}

function tool_tip_text($marker) {
	$tool_tip=$marker['fact'];
	if (!empty($marker['info']))
		$tool_tip.=": {$marker['info']}";
	if (!empty($marker['name']) && (displayDetailsById($marker['name']) || showLivingNameById($marker['name'])))
		$tool_tip.=": ".PrintReady(get_person_name($marker['name']));
	if (!empty($marker['date']))
		$tool_tip.=" - ".get_changed_date($marker['date']);
	return $tool_tip;
// dates & RTL is not OK - adding PrintReady does not solve it
}

function build_indiv_map($indifacts, $famids) {
	global $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GOOGLEMAP_MIN_ZOOM, $GOOGLEMAP_MAX_ZOOM, $GEDCOM;
	global $GOOGLEMAP_XSIZE, $GOOGLEMAP_YSIZE, $pgv_lang, $factarray, $SHOW_LIVING_NAMES, $PRIV_PUBLIC;
	global $GOOGLEMAP_ENABLED, $TBLPREFIX, $DBCONN, $TEXT_DIRECTION, $GM_DEFAULT_TOP_VALUE, $GOOGLEMAP_COORD;

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

	$markers=array();

	$zoomLevel = $GOOGLEMAP_MAX_ZOOM;
	$tables = $DBCONN->getListOf('tables');
	$placelocation=in_array($TBLPREFIX."placelocation", $tables);
	//-- sort the facts
	sort_facts($indifacts);
	$i = 0;
	foreach ($indifacts as $key => $value) {
		if (preg_match("/1 (\w+)(.*)/", $value[1], $match)) {
			$fact = $match[1];
			$fact_data=trim($match[2]);
			$placerec = null;
			if (preg_match("/2 PLAC (.*)/", $value[1], $match)) {
				$placerec = get_sub_record(2, "2 PLAC", $value[1]);
				$addrFound = false;
			} else {
				if (preg_match("/\d ADDR (.*)/", $value[1], $match)) {
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
					$useThisItem = displayDetailsByID($spouseid[1]);
				} else {
					$useThisItem = true;
				}
				if (($ctla>0) && ($ctlo>0) && ($useThisItem==true)) {
					$i = $i + 1;
					$markers[$i]=array('class'=>'optionbox', 'index'=>'', 'tabindex'=>'', 'placed'=>'no');
					if ($fact == "EVEN" || $fact=="FACT") {
						$eventrec = get_sub_record(1, "2 TYPE", $value[1]);
						if (preg_match("/\d TYPE (.*)/", $eventrec, $match3))
							if (isset($factarray[$match3[1]]))
								$markers[$i]["fact"]=$factarray[$match3[1]];
							else
								$markers[$i]["fact"]=$match3[1];
						else
							$markers[$i]["fact"]=$factarray[$fact];
					} else {
						$markers[$i]["fact"]=$factarray[$fact];
					}
					if (!empty($fact_data) && $fact_data!='Y')
						$markers[$i]["info"] = $fact_data;
					$markers[$i]["placerec"] = $placerec;
					$match1[1] = trim($match1[1]);
					$match2[1] = trim($match2[1]);
					$markers[$i]["lati"] = str_replace(array('N', 'S', ','), array('', '-', '.') , $match1[1]);
					$markers[$i]["lng"] = str_replace(array('E', 'W', ','), array('', '-', '.') , $match2[1]);
					$ctd = preg_match("/2 DATE (.+)/", $value[1], $match);
					if ($ctd>0)
						$markers[$i]["date"] = $match[1];
					if ($ctlp>0)
						$markers[$i]["name"]=$spouseid[1];
				} else {
					if (($placelocation == true) && ($useThisItem==true) && ($addrFound==false)) {
						$ctpl = preg_match("/\d PLAC (.*)/", $placerec, $match1);
						$latlongval = get_lati_long_placelocation($match1[1]);
						if ((count($latlongval) == 0) && (!empty($GM_DEFAULT_TOP_VALUE))) {
							$latlongval = get_lati_long_placelocation($match1[1].", ".$GM_DEFAULT_TOP_VALUE);
							if ((count($latlongval) != 0) && ($latlongval["level"] == 0)) {
								$latlongval["lati"] = NULL;
								$latlongval["long"] = NULL;
							}
						}
						if ((count($latlongval) != 0) && ($latlongval["lati"] != NULL) && ($latlongval["long"] != NULL)) {
							$i = $i + 1;
							$markers[$i]=array('class'=>'optionbox', 'index'=>'', 'tabindex'=>'', 'placed'=>'no');
							if ($fact == "EVEN" || $fact=="FACT") {
								$eventrec = get_sub_record(1, "2 TYPE", $value[1]);
								if (preg_match("/\d TYPE (.*)/", $eventrec, $match3))
									if (isset($factarray[$match3[1]]))
										$markers[$i]["fact"]=$factarray[$match3[1]];
									else
										$markers[$i]["fact"]=$match3[1];
								else
									$markers[$i]["fact"]=$factarray[$fact];
							} else {
								$markers[$i]["fact"]=$factarray[$fact];
							}
							if (!empty($fact_data) && $fact_data!='Y')
								$markers[$i]["info"] = $fact_data;
							$markers[$i]["icon"] = $latlongval["icon"];
							$markers[$i]["placerec"] = $placerec;
							if ($zoomLevel > $latlongval["zoom"]) $zoomLevel = $latlongval["zoom"];
							$markers[$i]["lati"] = str_replace(array('N', 'S', ','), array('', '-', '.') , $latlongval["lati"]);
							$markers[$i]["lng"] = str_replace(array('E', 'W', ','), array('', '-', '.') , $latlongval["long"]);
							$ctd = preg_match("/2 DATE (.+)/", $value[1], $match);
							if ($ctd>0)
								$markers[$i]["date"] = $match[1];
							if ($ctlp>0)
								$markers[$i]["name"]=$spouseid[1];
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
					$num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch, PREG_SET_ORDER);
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
									$markers[$i]=array('index'=>'', 'tabindex'=>'', 'placed'=>'no');
									if (strpos($srec, "1 SEX F")!==false) {
										$markers[$i]["fact"] = $pgv_lang["daughter"];
										$markers[$i]["class"]  = "person_boxF";
									} else
										if (strpos($srec, "1 SEX M")!==false) {
											$markers[$i]["fact"] = $pgv_lang["son"];
											$markers[$i]["class"]  = "person_box";
										} else {
											$markers[$i]["fact"]     = $factarray["CHIL"];
											$markers[$i]["class"]    = "person_boxNN";
										}
									$markers[$i]["placerec"] = $placerec;
									$match1[1] = trim($match1[1]);
									$match2[1] = trim($match2[1]);
									$markers[$i]["lati"] = str_replace(array('N', 'S', ','), array('', '-', '.'), $match1[1]);
									$markers[$i]["lng"]  = str_replace(array('E', 'W', ','), array('', '-', '.'), $match2[1]);
									if ($ctd > 0)
										$markers[$i]["date"] = $matchd[1];
									$markers[$i]["name"] = $smatch[$j][1];
								}
							} else {
								if ($placelocation == true) {
									$ctpl = preg_match("/\d PLAC (.*)/", $placerec, $match1);
									$latlongval = get_lati_long_placelocation($match1[1]);
									if ((count($latlongval) == 0) && (!empty($GM_DEFAULT_TOP_VALUE))) {
										$latlongval = get_lati_long_placelocation($match1[1].", ".$GM_DEFAULT_TOP_VALUE);
										if ((count($latlongval) != 0) && ($latlongval["level"] == 0)) {
											$latlongval["lati"] = NULL;
											$latlongval["long"] = NULL;
										}
									}
									if ((count($latlongval) != 0) && ($latlongval["lati"] != NULL) && ($latlongval["long"] != NULL)) {
										if (displayDetailsByID($smatch[$j][1])) {
											$i = $i + 1;
											$markers[$i]=array('index'=>'', 'tabindex'=>'', 'placed'=>'no');
											$markers[$i]["fact"]     = $factarray["CHIL"];
											$markers[$i]["class"]    = "option_boxNN";
											if (preg_match("/1 SEX F/", $srec)>0) {
												$markers[$i]["fact"] = $pgv_lang["daughter"];
												$markers[$i]["class"]  = "person_boxF";
											}
											if (preg_match("/1 SEX M/", $srec)>0) {
												$markers[$i]["fact"] = $pgv_lang["son"];
												$markers[$i]["class"]  = "person_box";
											}
											$markers[$i]["icon"] = $latlongval["icon"];
											$markers[$i]["placerec"] = $placerec;
											if ($zoomLevel > $latlongval["zoom"]) $zoomLevel = $latlongval["zoom"];
											$markers[$i]["lati"]     = str_replace(array('N', 'S', ','), array('', '-', '.'), $latlongval["lati"]);
											$markers[$i]["lng"]      = str_replace(array('E', 'W', ','), array('', '-', '.'), $latlongval["long"]);
											if ($ctd > 0)
												$markers[$i]["date"] = $matchd[1];
											$markers[$i]["name"]   = $smatch[$j][1];
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
	} else {
		?>
		<script type="text/javascript">
		function SetMarkersAndBounds () {
			var bounds = new GLatLngBounds();
		<?php
		foreach ($markers as $marker)
			print "bounds.extend(new GLatLng({$marker["lati"]}, {$marker["lng"]}));\n";
		print "SetBoundaries(bounds);\n";

		$indexcounter = 0;
		for ($j=1; $j<=$i; $j++) {
			$tooltip=addslashes(tool_tip_text($markers[$j]));
			if (useRTLFunctions())
				$tooltip = str_replace(array("&lrm;", "&rlm;"), array("", ""), $tooltip);
			if ($markers[$j]["placed"] == "no") {
				$multimarker = -1;
				// Count nr of locations where the long/lati is identical
				for($k=$j; $k<=$i; $k++)
					if (($markers[$j]["lati"] == $markers[$k]["lati"]) && ($markers[$j]["lng"] == $markers[$k]["lng"]))
						$multimarker = $multimarker + 1;

				if ($multimarker == 0) {        // Only one location with this long/lati combination
					$markers[$j]["placed"] = "yes";
					if (empty($markers[$j]["icon"])) {
						print "var Marker{$j} = new GMarker(new GLatLng({$markers[$j]["lati"]}, {$markers[$j]["lng"]}), {title:\"{$tooltip}\"});\n";
					} else {
						print "var Marker{$j}_flag = new GIcon();\n";
						print "    Marker{$j}_flag.image = \"".$markers[$j]["icon"]."\";\n";
						print "    Marker{$j}_flag.shadow = \"modules/googlemap/flag_shadow.png\";\n";
						print "    Marker{$j}_flag.iconSize = new GSize(25, 15);\n";
						print "    Marker{$j}_flag.shadowSize = new GSize(35, 45);\n";
						print "    Marker{$j}_flag.iconAnchor = new GPoint(1, 45);\n";
						print "    Marker{$j}_flag.infoWindowAnchor = new GPoint(5, 1);\n";
						print "var Marker{$j} = new GMarker(new GLatLng(".$markers[$j]["lati"].", ".$markers[$j]["lng"]."), {icon:Marker{$j}_flag, title:\"".$tooltip."\"});\n";
					}
					print "GEvent.addListener(Marker{$j}, \"click\", function() {\n";
					print "Marker{$j}.openInfoWindowHtml(\"<div class='iwstyle'>";
					print PrintReady($markers[$j]["fact"]);
					if (!empty($markers[$j]['info']))
						print ": {$markers[$j]['info']}";
					if (!empty($markers[$j]["name"])) {
						print ": <a href=\\\"individual.php?pid=".$markers[$j]["name"]."&amp;ged=$GEDCOM\\\">";
						if (displayDetailsById($markers[$j]["name"])||showLivingNameById($markers[$j]["name"]))
							print PrintReady(preg_replace("/\"/", "\\\"", get_person_name($markers[$j]["name"])));
						else
							print $pgv_lang["private"];
						print "</a>";
					}
					print "<br/>";
					if (preg_match("/2 PLAC (.*)/", $markers[$j]["placerec"]) == 0) {
						print_address_structure_map($markers[$j]["placerec"], 1);
					} else {
						print preg_replace("/\"/", "\\\"", print_fact_place_map($markers[$j]["placerec"]));
					}
					if (!empty($markers[$j]["date"])) {
						print "<br/>".addslashes(get_date_url($markers[$j]["date"]));
					}
					if ($GOOGLEMAP_COORD == "false"){
						print "\");\n";
					} else {
						print "<br/><br/>Lati: ";
						if ($markers[$j]["lati"]>='0'){print "N".str_replace('-', '', $markers[$j]["lati"]);}else{ print str_replace('-', 'S', $markers[$j]["lati"]);}
						print ", Long: ";
						if ($markers[$j]["lng"]>='0'){print "E".str_replace('-', '', $markers[$j]["lng"]);}else{ print str_replace('-', 'W', $markers[$j]["lng"]);}
						print "\");\n";
					}
					print "});\n";
					print "markers.push(Marker{$j});\n";
					print "map.addOverlay(Marker{$j});\n";
					$markers[$j]["index"] = $indexcounter;
					$markers[$j]["tabindex"] = -1;
					$indexcounter = $indexcounter + 1;
				} else {
					$tabcounter = 0;
					$markersindex = 0;
					$markers[$j]["placed"] = "yes";
					if (empty($markers[$j]["icon"])) {
						print "var Marker{$j}_{$markersindex} = new GMarker(new GLatLng(".$markers[$j]["lati"].", ".$markers[$j]["lng"]."), {title:\"{$tooltip}\"});\n";
					} else {
						print "var Marker{$j}_{$markersindex}_flag = new GIcon();\n";
						print "    Marker{$j}_{$markersindex}_flag.image = \"".$markers[$j]["icon"]."\";\n";
						print "    Marker{$j}_{$markersindex}_flag.shadow = \"modules/googlemap/flag_shadow.png\";\n";
						print "    Marker{$j}_{$markersindex}_flag.iconSize = new GSize(25, 15);\n";
						print "    Marker{$j}_{$markersindex}_flag.shadowSize = new GSize(35, 45);\n";
						print "    Marker{$j}_{$markersindex}_flag.iconAnchor = new GPoint(1, 45);\n";
						print "    Marker{$j}_{$markersindex}_flag.infoWindowAnchor = new GPoint(5, 1);\n";
						print "var Marker{$j}_{$markersindex} = new GMarker(new GLatLng(".$markers[$j]["lati"].", ".$markers[$j]["lng"]."), {icon:Marker{$j}_{$markersindex}_flag, title:\"{$tooltip}\"});\n";
					}
					print "var Marker{$j}_{$markersindex}Info = [\n";
					$markers[$j]["index"] = $indexcounter;
					$markers[$j]["tabindex"] = $tabcounter;
					$tabcounter = $tabcounter + 1;
					print "new GInfoWindowTab(\"".$markers[$j]["fact"]."\", \"<div class='iwstyle'>".PrintReady($markers[$j]["fact"]);
					if (!empty($markers[$j]['info']))
						print ": {$markers[$j]['info']}";
					if (!empty($markers[$j]["name"])) {
						print ": <a href=\\\"individual.php?pid=".$markers[$j]["name"]."&amp;ged=$GEDCOM\\\">";
						if (displayDetailsById($markers[$j]["name"])||showLivingNameById($markers[$j]["name"]))
							print PrintReady(preg_replace("/\"/", "\\\"", get_person_name($markers[$j]["name"])));
						else
							print $pgv_lang["private"];
						print "</a>";
					}
					print "<br/>";
					if (preg_match("/2 PLAC (.*)/", $markers[$j]["placerec"]) == 0) {
						print_address_structure_map($markers[$j]["placerec"], 1);
					} else {
						print preg_replace("/\"/", "\\\"", print_fact_place_map($markers[$j]["placerec"]));
					}
					if (!empty($markers[$j]["date"])) {
						print "<br/>".addslashes(get_date_url($markers[$j]["date"]));
					}
					if ($GOOGLEMAP_COORD == "false"){
						print "\")";
					} else {
						print "<br/><br/>Lati: ";
						if ($markers[$j]["lati"]>='0'){print "N".str_replace('-', '', $markers[$j]["lati"]);}else{ print str_replace('-', 'S', $markers[$j]["lati"]);}
						print ", Long: ";
						if ($markers[$j]["lng"]>='0'){print "E".str_replace('-', '', $markers[$j]["lng"]);}else{ print str_replace('-', 'W', $markers[$j]["lng"]);}
						print "\")";
					}
					for($k=$j+1; $k<=$i; $k++) {
						if (($markers[$j]["lati"] == $markers[$k]["lati"]) && ($markers[$j]["lng"] == $markers[$k]["lng"])) {
							$markers[$k]["placed"] = "yes";
							$markers[$k]["index"] = $indexcounter;
							if ($tabcounter == 4) {
								$tooltip=addslashes(tool_tip_text($markers[$k]));
								if (useRTLFunctions())
									$tooltip = str_replace(array("&lrm;", "&rlm;"), array("", ""), $tooltip);
								print "\n";
								print "];\n";
								print "GEvent.addListener(Marker{$j}_{$markersindex}, \"click\", function(tabToSelect) {\n";
								print "Marker{$j}_{$markersindex}.openInfoWindowTabsHtml(Marker{$j}_{$markersindex}Info, {selectedTab: tabToSelect});\n";
								print "});\n";
								print "markers.push(Marker{$j}_{$markersindex});\n";
								print "map.addOverlay(Marker{$j}_{$markersindex});\n";
								$indexcounter = $indexcounter + 1;
								$tabcounter = 0;
								$markersindex = $markersindex + 1;

								if (empty($markers[$j]["icon"])) {
									print "var Marker{$j}_{$markersindex} = new GMarker(new GLatLng(".($markers[$j]["lati"]-(0.0015*$markersindex)).", ".($markers[$j]["lng"]+(0.0025*$markersindex))."), {title:\"{$tooltip}\"});\n";
								} else {
									print "var Marker{$j}_{$markersindex}_flag = new GIcon();\n";
									print "    Marker{$j}_{$markersindex}_flag.image = \"".$markers[$j]["icon"]."\";\n";
									print "    Marker{$j}_{$markersindex}_flag.shadow = \"modules/googlemap/flag_shadow.png\";\n";
									print "    Marker{$j}_{$markersindex}_flag.iconSize = new GSize(25, 15);\n";
									print "    Marker{$j}_{$markersindex}_flag.shadowSize = new GSize(35, 45);\n";
									print "    Marker{$j}_{$markersindex}_flag.iconAnchor = new GPoint(1, 45);\n";
									print "    Marker{$j}_{$markersindex}_flag.infoWindowAnchor = new GPoint(5, 1);\n";
									print "var Marker{$j}_{$markersindex} = new GMarker(new GLatLng(".($markers[$j]["lati"]-(0.0015*$markersindex)).", ".($markers[$j]["lng"]+(0.0025*$markersindex))."), {icon:Marker{$j}_{$markersindex}_flag, title:\"{$tooltip}\"});\n";
								}
								print "var Marker{$j}_{$markersindex}Info = [\n";
							} else {
								print ",\n";
							}
							$markers[$k]["index"] = $indexcounter;
							$markers[$k]["tabindex"] = $tabcounter;
							$tabcounter = $tabcounter + 1;
							print "new GInfoWindowTab(\"".$markers[$k]["fact"]."\", \"<div class='iwstyle'>".$markers[$k]["fact"];
							if (!empty($markers[$k]['info']))
								print ": {$markers[$k]['info']}";
							if (!empty($markers[$k]["name"])) {
								print ": <a href=\\\"individual.php?pid=".$markers[$k]["name"]."&amp;ged=$GEDCOM\\\">";
								if (displayDetailsById($markers[$k]["name"])||showLivingNameById($markers[$k]["name"]))
									print PrintReady(preg_replace("/\"/", "\\\"", get_person_name($markers[$k]["name"])));
								else
									print $pgv_lang["private"];
								print "</a>";
							}
							print "<br/>";
							if (preg_match("/2 PLAC (.*)/", $markers[$k]["placerec"]) == 0) {
								print_address_structure_map($markers[$k]["placerec"], 1);
							} else {
								print preg_replace("/\"/", "\\\"", print_fact_place_map($markers[$k]["placerec"]));
							}
							if (!empty($markers[$k]["date"])) {
								print "<br/>".addslashes(get_date_url($markers[$k]["date"]));
							}
							if ($GOOGLEMAP_COORD == "false"){
								print "\")";
							} else {
								print "<br/><br/>Lati: ";
								if ($markers[$j]["lati"]>='0'){print "N".str_replace('-', '', $markers[$j]["lati"]);}else{ print str_replace('-', 'S', $markers[$j]["lati"]);}
								print ", Long: ";
								if ($markers[$j]["lng"]>='0'){print "E".str_replace('-', '', $markers[$j]["lng"]);}else{ print str_replace('-', 'W', $markers[$j]["lng"]);}
								print "\")";
							}
						}
					}
					print "\n";
					print "];\n";
					print "GEvent.addListener(Marker{$j}_{$markersindex}, \"click\", function(tabToSelect) {\n";
					print "Marker{$j}_{$markersindex}.openInfoWindowTabsHtml(Marker{$j}_{$markersindex}Info, {selectedTab: tabToSelect});\n";
					print "});\n";
					print "markers.push(Marker{$j}_{$markersindex});\n";
					print "map.addOverlay(Marker{$j}_{$markersindex});\n";
					$indexcounter = $indexcounter + 1;
				}
			}
		}
		print "}</script>";
		print "<div style=\"overflow-x: hidden; overflow-y: auto; height:{$GOOGLEMAP_YSIZE}px;\"><table class=\"facts_table\">";
		foreach($markers as $marker) {
			print "<tr><td class=\"facts_label\">";
			print "<a href=\"javascript:highlight({$marker["index"]}, {$marker["tabindex"]})\">{$marker["fact"]}</a></td>";
			print "<td class=\"{$marker['class']}\">";
			if (!empty($marker["info"]))
				print "<span class=\"field\">{$marker["info"]}</span><br/>";
			if (!empty($marker["name"])) {
				print "<a href=\"individual.php?pid={$marker["name"]}&amp;ged=$GEDCOM\">";
				if (displayDetailsById($marker["name"])||showLivingNameById($marker["name"]))
					print PrintReady(get_person_name($marker["name"]));
				else
					print $pgv_lang["private"];
				print "</a><br/>";
			}
			if (preg_match("/2 PLAC (.*)/", $marker["placerec"]) == 0) {
				print_address_structure_map($marker["placerec"], 1);
			} else {
				print print_fact_place_map($marker["placerec"])."<br/>";
			}
			if (!empty($marker['date']))
				print get_date_url($marker['date'])."<br/>";
			print "</td></tr>";
		}
		print "</table></div><br/>";
	}
	print "\n<br />";

	return $i;
}

?>
