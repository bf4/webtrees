<?php
/**
 * Google map module for phpGedView
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
 *
 * Modifications Copyright (c) 2010 Greg Roach
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
 * @subpackage Module
 * $Id$
 *
 * @author Brian Holland
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

/*
?>
<script type="text/javascript">
jQuery(function(){
	// Tabs
	jQuery('#tabs').tabs();
	jQuery('#gmtabs').tabs();
	jQuery('#SV').tabs();
});
</script>
<?php
*/

global $SESSION_HIDE_GOOGLEMAP;
$SESSION_HIDE_GOOGLEMAP = "empty";
if ((isset($_REQUEST["HIDE_GOOGLEMAP"])) && (empty($SEARCH_SPIDER))) {
	if (stristr("true", $_REQUEST["HIDE_GOOGLEMAP"])) {
		$SESSION_HIDE_GOOGLEMAP = "true";
	}
	if (stristr("false", $_REQUEST["HIDE_GOOGLEMAP"])) {
		$SESSION_HIDE_GOOGLEMAP = "false";
	}
}

// change the session values and store if needed.
if ($SESSION_HIDE_GOOGLEMAP == "true") $_SESSION['hide_googlemap'] = true;
if ($SESSION_HIDE_GOOGLEMAP == "false") $_SESSION['hide_googlemap'] = false;
if ($SESSION_HIDE_GOOGLEMAP == "empty") {
	if ((isset($_SESSION['hide_googlemap'])) && ($_SESSION['hide_googlemap'] == true))
		$SESSION_HIDE_GOOGLEMAP = "true";
	else
		$SESSION_HIDE_GOOGLEMAP = "false";
}

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
		foreach ($levels as $pindex=>$ppart) {
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
	global $WORD_WRAPPED_NOTES;
	global $POSTAL_CODE;

	//  $POSTAL_CODE = 'false' - before city, 'true' - after city and/or state
	//-- define per gedcom till can do per address countries in address languages
	//-- then this will be the default when country not recognized or does not exist
	//-- both Finland and Suomi are valid for Finland etc.
	//-- see http://www.bitboost.com/ref/international-address-formats.html

	$nlevel = $level+1;
	$ct = preg_match_all("/$level ADDR(.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		$arec = get_sub_record($level, "$level ADDR", $factrec, $i+1);
		$resultText = "";
		$cn = preg_match("/$nlevel _NAME (.*)/", $arec, $cmatch);
		if ($cn>0) $resultText .= str_replace("/", "", $cmatch[1])."<br />";
		$resultText .= PrintReady(trim($omatch[$i][1]));
		$cont = get_cont($nlevel, $arec);
		if (!empty($cont)) $resultText .= str_replace(array(" ", "<br&nbsp;"), array("&nbsp;", "<br "), PrintReady($cont));
		else {
			if (strlen(trim($omatch[$i][1])) > 0) echo "<br />";
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
		echo str_replace(chr(10), ' ' , $resultText);
	}
	$resultText = "<table>";
	$ct = preg_match_all("/$level PHON (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".translate_fact('PHON').": </b></span></td><td><span class=\"field\">";
		$resultText .= getLRM() . $omatch[$i][1]. getLRM();
		$resultText .= "</span></td></tr>";
	}
	$ct = preg_match_all("/$level FAX (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".translate_fact('FAX').": </b></span></td><td><span class=\"field\">";
		$resultText .= getLRM() . $omatch[$i][1] . getLRM();
		$resultText .= "</span></td></tr>";
	}
	$ct = preg_match_all("/$level EMAIL (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".translate_fact('EMAIL').": </b></span></td><td><span class=\"field\">";
		$resultText .= "<a href=\"mailto:".$omatch[$i][1]."\">".$omatch[$i][1]."</a>";
		$resultText .= "</span></td></tr>";
	}
	$ct = preg_match_all("/$level (WWW|URL) (.*)/", $factrec, $omatch, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		$resultText .= "<tr><td><span class=\"label\"><b>".translate_fact('URL').": </b></span></td><td><span class=\"field\">";
		$resultText .= "<a href=\"".$omatch[$i][2]."\" target=\"_blank\">".$omatch[$i][2]."</a>";
		$resultText .= "</span></td></tr>";
	}
	$resultText .= "</table>";
	if ($resultText!="<table></table>") echo str_replace(chr(10), ' ' , $resultText);
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

function abbreviate($text) {
	if (utf8_strlen($text)>13) {
		if (trim(utf8_substr($text, 10, 1))!="")
			$desc = utf8_substr($text, 0, 11).".";
		else $desc = trim(utf8_substr($text, 0, 11));
	}
	else $desc = $text;
	return $desc;
}

function get_lati_long_placelocation ($place) {
	$parent = explode (",", $place);
	$parent = array_reverse($parent);
	$place_id = 0;
	for ($i=0; $i<count($parent); $i++) {
		$parent[$i] = trim($parent[$i]);
		if (empty($parent[$i])) $parent[$i]="unknown";// GoogleMap module uses "unknown" while GEDCOM uses , ,
		$placelist = create_possible_place_names($parent[$i], $i+1);
		foreach ($placelist as $key => $placename) {
			$pl_id=
				WT_DB::prepare("SELECT pl_id FROM `##placelocation` WHERE pl_level=? AND pl_parent_id=? AND pl_place LIKE ? ORDER BY pl_place")
				->execute(array($i, $place_id, $placename))
				->fetchOne();
			if (!empty($pl_id)) break;
		}
		if (empty($pl_id)) break;
		$place_id = $pl_id;
	}

	$row=
		// WT_DB::prepare("SELECT pl_lati, pl_long, pl_zoom, pl_icon, pl_level FROM `##placelocation` WHERE pl_id=? ORDER BY pl_place")
		WT_DB::prepare("SELECT pl_media, sv_lati, sv_long, sv_bearing, sv_elevation, sv_zoom, pl_lati, pl_long, pl_zoom, pl_icon, pl_level FROM `##placelocation` WHERE pl_id=? ORDER BY pl_place")
		->execute(array($place_id))
		->fetchOneRow();
	if ($row) {
		return array('media'=>$row->pl_media, 'sv_lati'=>$row->sv_lati, 'sv_long'=>$row->sv_long, 'sv_bearing'=>$row->sv_bearing, 'sv_elevation'=>$row->sv_elevation, 'sv_zoom'=>$row->sv_zoom, 'lati'=>$row->pl_lati, 'long'=>$row->pl_long, 'zoom'=>$row->pl_zoom, 'icon'=>$row->pl_icon, 'level'=>$row->pl_level);
	} else {
		return array();
	}
}

function setup_map() {
	global $GOOGLEMAP_ENABLED, $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GOOGLEMAP_MIN_ZOOM, $GOOGLEMAP_MAX_ZOOM;
	if (!$GOOGLEMAP_ENABLED) {
		return;
	}
	?>
	
	<!--  V3 ============ -->
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<!--  V3 ============ -->
	
	<script type="text/javascript">
		var minZoomLevel = <?php echo $GOOGLEMAP_MIN_ZOOM;?>;
		var maxZoomLevel = <?php echo $GOOGLEMAP_MAX_ZOOM;?>;
		var startZoomLevel = <?php echo $GOOGLEMAP_MAX_ZOOM;?>;
	</script>
	<?php
}

function build_indiv_map($indifacts, $famids) {
	global $GOOGLEMAP_API_KEY, $GOOGLEMAP_MAP_TYPE, $GOOGLEMAP_MIN_ZOOM, $GOOGLEMAP_MAX_ZOOM, $GEDCOM;
	global $GOOGLEMAP_XSIZE, $GOOGLEMAP_YSIZE, $SHOW_LIVING_NAMES;
	global $TEXT_DIRECTION, $GM_DEFAULT_TOP_VALUE, $GOOGLEMAP_COORD;
	
	$zoomLevel = $GOOGLEMAP_MAX_ZOOM;
	
	// Create the markers list array ===============================================================
	$markers=array();
	
	// Add the events to the markers list array=====================================================
	$placelocation=WT_DB::table_exists("##placelocation");
	//-- sort the facts into date order
	sort_facts($indifacts); 
	$i = 0;
	foreach ($indifacts as $key => $value) {
		$fact = $value->getTag();
		$fact_data=$value->getDetail();
		$factrec = $value->getGedComRecord();
		$placerec = null;
		
		if ($value->getPlace()!=null) {
			$placerec = get_sub_record(2, "2 PLAC", $factrec);

			$addrFound = false;
		} else {
			if (preg_match("/\d ADDR (.*)/", $factrec, $match)) {
				$placerec = get_sub_record(1, "\d ADDR", $factrec);
				$addrFound = true;
			}
		}
		if (!empty($placerec)) {
			$ctla = preg_match("/\d LATI (.*)/", $placerec, $match1);
			$ctlo = preg_match("/\d LONG (.*)/", $placerec, $match2);
			$spouserec = get_sub_record(2, "2 _WTS", $factrec);
			$ctlp = preg_match("/\d _WTS @(.*)@/", $spouserec, $spouseid);
			if ($ctlp>0) {
				$useThisItem = canDisplayRecord(WT_GED_ID, find_family_record($spouseid[1], WT_GED_ID));
			} else {
				$useThisItem = true;
			}
			if (($ctla>0) && ($ctlo>0) && ($useThisItem==true)) {
				$i = $i + 1;
				$markers[$i]=array('class'=>'optionbox', 'index'=>'', 'tabindex'=>'', 'placed'=>'no');
				if ($fact == "EVEN" || $fact=="FACT") {
					$eventrec = get_sub_record(1, "2 TYPE", $factrec);
					if (preg_match("/\d TYPE (.*)/", $eventrec, $match3)) {
						$markers[$i]["fact"]=$match3[1];
					} else {
						$markers[$i]["fact"]=translate_fact($fact);
					}
				} else {
					$markers[$i]["fact"]=translate_fact($fact);
				}
				if (!empty($fact_data) && $fact_data!='Y') {
						$markers[$i]["info"] = $fact_data;
				}
				$markers[$i]["placerec"] = $placerec;
				$match1[1] = trim($match1[1]);
				$match2[1] = trim($match2[1]);
				$markers[$i]["lati"] = str_replace(array('N', 'S', ','), array('', '-', '.') , $match1[1]);
				$markers[$i]["lng"] = str_replace(array('E', 'W', ','), array('', '-', '.') , $match2[1]);
				
				$ctd = preg_match("/2 DATE (.+)/", $factrec, $match);
				if ($ctd>0) {
					$markers[$i]["date"] = $match[1];
				}
				if ($ctlp>0) {
					$markers[$i]["name"]=$spouseid[1];
				}
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
							$eventrec = get_sub_record(1, "2 TYPE", $factrec);
							if (preg_match("/\d TYPE (.*)/", $eventrec, $match3)) {
								$markers[$i]["fact"]=$match3[1];
							} else {
								$markers[$i]["fact"]=translate_fact($fact);
							}
						} else {
							$markers[$i]["fact"]=translate_fact($fact);
						}
						if (!empty($fact_data) && $fact_data!='Y') {
							$markers[$i]["info"] = $fact_data;
						}
						$markers[$i]["icon"] = $latlongval["icon"];
						$markers[$i]["placerec"] = $placerec;
						if ($zoomLevel > $latlongval["zoom"]) {
							$zoomLevel = $latlongval["zoom"];
						}
						$markers[$i]["lati"] = str_replace(array('N', 'S', ','), array('', '-', '.') , $latlongval["lati"]);
						$markers[$i]["lng"] = str_replace(array('E', 'W', ','), array('', '-', '.') , $latlongval["long"]);
						
						$markers[$i]["media"] = $latlongval["media"];
						$markers[$i]["sv_lati"] = $latlongval["sv_lati"];
						$markers[$i]["sv_long"] = $latlongval["sv_long"];
						$markers[$i]["sv_bearing"] = $latlongval["sv_bearing"];
						$markers[$i]["sv_elevation"] = $latlongval["sv_elevation"];
						$markers[$i]["sv_zoom"] = $latlongval["sv_zoom"];

						$ctd = preg_match("/2 DATE (.+)/", $factrec, $match);
						if ($ctd>0) {
							$markers[$i]["date"] = $match[1];
						}
						if ($ctlp>0) {
							$markers[$i]["name"]=$spouseid[1];
						}
					}
				}
			}
		}	
	}

	// Add children to the markers list array ======================================================
	if (count($famids)>0) {
		$hparents=false;
		for ($f=0; $f<count($famids); $f++) {
			if (!empty($famids[$f])) {
				$famrec = find_gedcom_record($famids[$f], WT_GED_ID, true);
				if ($famrec) {
					$num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch, PREG_SET_ORDER);
					for ($j=0; $j<$num; $j++) {
						$person=Person::getInstance($smatch[$j][1]);
						if ($person->canDisplayDetails()) {
							$srec = find_person_record($smatch[$j][1], WT_GED_ID);
							$birthrec = '';
							$placerec = '';
							foreach ($person->getAllFactsByType('BIRT') as $sEvent) {
								$birthrec = $sEvent->getGedcomRecord();
								$placerec = get_sub_record(2, "2 PLAC", $birthrec);
								if (!empty($placerec)) {
									$ctd = preg_match("/\d DATE (.*)/", $birthrec, $matchd);
									$ctla = preg_match("/\d LATI (.*)/", $placerec, $match1);
									$ctlo = preg_match("/\d LONG (.*)/", $placerec, $match2);
									if (($ctla>0) && ($ctlo>0)) {
										$i = $i + 1;
										$markers[$i]=array('index'=>'', 'tabindex'=>'', 'placed'=>'no');
										if (strpos($srec, "\n1 SEX F")!==false) {
											$markers[$i]["fact"] = i18n::translate('Daughter');
											$markers[$i]["class"]  = "person_boxF";
										} else
											if (strpos($srec, "\n1 SEX M")!==false) {
												$markers[$i]["fact"] = i18n::translate('Son');
												$markers[$i]["class"]  = "person_box";
											} else {
												$markers[$i]["fact"]     = translate_fact('CHIL');
												$markers[$i]["class"]    = "person_boxNN";
											}
										$markers[$i]["placerec"] = $placerec;
										$match1[1] = trim($match1[1]);
										$match2[1] = trim($match2[1]);
										$markers[$i]["lati"] = str_replace(array('N', 'S', ','), array('', '-', '.'), $match1[1]);
										$markers[$i]["lng"]  = str_replace(array('E', 'W', ','), array('', '-', '.'), $match2[1]);
										if ($ctd > 0) {
											$markers[$i]["date"] = $matchd[1];
										}
										$markers[$i]["name"] = $smatch[$j][1];
						
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
												$i = $i + 1;
												$markers[$i]=array('index'=>'', 'tabindex'=>'', 'placed'=>'no');
												$markers[$i]["fact"]     = translate_fact('CHIL');
												$markers[$i]["class"]    = "option_boxNN";
												if (strpos($srec, "\n1 SEX F")!==false) {
													$markers[$i]["fact"] = i18n::translate('Daughter');
													$markers[$i]["class"]  = "person_boxF";
												}
												if (strpos($srec, "\n1 SEX M")!==false) {
													$markers[$i]["fact"] = i18n::translate('Son');
													$markers[$i]["class"]  = "person_box";
												}
												$markers[$i]["icon"] = $latlongval["icon"];
												$markers[$i]["placerec"] = $placerec;
												if ($zoomLevel > $latlongval["zoom"]) {
													$zoomLevel = $latlongval["zoom"];
												}
												$markers[$i]["lati"]     = str_replace(array('N', 'S', ','), array('', '-', '.'), $latlongval["lati"]);
												$markers[$i]["lng"]      = str_replace(array('E', 'W', ','), array('', '-', '.'), $latlongval["long"]);
												if ($ctd > 0) {
													$markers[$i]["date"] = $matchd[1];
												}
												$markers[$i]["name"]   = $smatch[$j][1];
												
												$markers[$i]["media"] = $latlongval["media"];
												$markers[$i]["sv_lati"] = $latlongval["sv_lati"];
												$markers[$i]["sv_long"] = $latlongval["sv_long"];
												$markers[$i]["sv_bearing"] = $latlongval["sv_bearing"];
												$markers[$i]["sv_elevation"] = $latlongval["sv_elevation"];
												$markers[$i]["sv_zoom"] = $latlongval["sv_zoom"];

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
	}
	
	// Prepare the $markers array for use by the following "required" file/files ===================
	if ($i == 0) {
		echo "<table class=\"facts_table\">";
		echo "<tr><td colspan=\"2\" class=\"facts_value\">".i18n::translate('No map data for this person');
		echo "</td></tr>";
		if (WT_USER_IS_ADMIN) {
			echo "<tr><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"module.php?mod=googlemap&mod_action=editconfig\">", i18n::translate('Manage GoogleMap configuration'), "</a>";
			echo "</td></tr>";
		}

	} else {

		$indexcounter = 0;
		for ($j=1; $j<=$i; $j++) {

			if ($markers[$j]["placed"] == "no") {
				$multimarker = -1;
				// Count nr of locations where the long/lati is identical
				for ($k=$j; $k<=$i; $k++)
					if (($markers[$j]["lati"] == $markers[$k]["lati"]) && ($markers[$j]["lng"] == $markers[$k]["lng"]))
						$multimarker = $multimarker + 1;
						
				// If only one location with this long/lati combination
				if ($multimarker == 0) {
					// --- NOTE for V3 api, following line is changed from "yes" to "no" ----------- 
					// --- This aids in identifying multi-event locations 
					$markers[$j]["placed"] = "no";
					// -----------------------------------------------------------------------------
					$markers[$j]["index"] = $indexcounter;
					$markers[$j]["tabindex"] = 0;
					$indexcounter = $indexcounter + 1;
				} else {
					$tabcounter = 0;
					$markersindex = 0;
					$markers[$j]["placed"] = "yes";
					$markers[$j]["index"] = $indexcounter;
					$markers[$j]["tabindex"] = $tabcounter;
					$tabcounter = $tabcounter + 1;
					for ($k=$j+1; $k<=$i; $k++) {
						if (($markers[$j]["lati"] == $markers[$k]["lati"]) && ($markers[$j]["lng"] == $markers[$k]["lng"])) {
							$markers[$k]["placed"] = "yes";
							$markers[$k]["index"] = $indexcounter;							
							// if ($tabcounter == 4) {
							// V3 ==============================
							if ($tabcounter == 30) {  
							// V3 ==============================

								$indexcounter = $indexcounter + 1;
								$tabcounter = 0;
								$markersindex = $markersindex + 1;
							}
							$markers[$k]["index"] = $indexcounter;
							$markers[$k]["tabindex"] = $tabcounter;
							$tabcounter = $tabcounter + 1;
						}
					}
					$indexcounter = $indexcounter + 1;
				}
			}
		}

		// === add $gmarks array to the required V3_GM_googlemap.js.php ============================		
		$gmarks = $markers;

		// Convert $gmarks array to xml file =======================================================
		require_once WT_ROOT.'modules/googlemap/Array-XML.php';		
		$xml = generate_valid_xml_from_array($gmarks, "markers", "marker");
		$xml = str_replace('&lt;', '<', $xml);
		$xml = str_replace('&gt;', '>', $xml);
		$xml = str_replace('<br /><br />', '', $xml);
		$xml = str_replace('<br />', '', $xml);
		$xml = str_replace(' "', '" ', $xml);
		$xml = str_replace('2 PLAC ', '', $xml);		
		$temp_xml_filename = WT_ROOT.'modules/googlemap/wt_temp.xml';
		if (file_exists($temp_xml_filename)) {
			unlink($temp_xml_filename);
		}
		$Content = $xml; 
		$handle = fopen($temp_xml_filename, 'x+');
		fwrite($handle, $Content);
		fclose($handle);		
		
		// === Include css and js files ============================================================
		echo '<link type="text/css" href="modules/googlemap/css/v3_googlemap.css" rel="stylesheet" />';
		require_once WT_ROOT.'modules/googlemap/wt_v3_googlemap.js.php';
	
		// === Create the normal googlemap sidebar of events and children ==========================
		echo "<div style=\"overflow: auto; overflow-x: hidden; overflow-y: auto; height: {$GOOGLEMAP_YSIZE}px;\"><table class=\"facts_table\">";
		$z=0;
		foreach($markers as $marker) {
			echo "<tr>";
			echo "<td class=\"facts_label\">";
			echo "<a href=\"javascript:myclick({$z}, {$marker["index"]}, {$marker["tabindex"]})\">{$marker["fact"]}</a></td>";
				$z++;						
			echo "<td class=\"{$marker['class']}\" style=\"white-space: normal\">";
			if (!empty($marker["info"])) {
				echo "<span class=\"field\">{$marker["info"]}</span><br />";
			}
			if (!empty($marker["name"])) {
				$person=Person::getInstance($marker['name']);
				if ($person) {
					echo '<a href="', $person->getHtmlUrl(), '">', $person->canDisplayName() ? PrintReady($person->getFullName()) : i18n::translate('Private'), '</a>';
				}
				echo '<br />';
			}
			if (preg_match("/2 PLAC (.*)/", $marker["placerec"]) == 0) {
				print_address_structure_map($marker["placerec"], 1);
			} else {
				echo print_fact_place_map($marker["placerec"]), "<br />";
			}
			if (!empty($marker['date'])) {
				$date=new GedcomDate($marker['date']);
				echo $date->Display(true), "<br />";
			}
			echo "</td>";	
			echo "</tr>";
		}
		echo "</table></div><br />";
		
	} // end prepare markers array =================================================================
	
	
	// ======= More V3 api stuff which will be sorted later ========================================	
	?>
   	<table id="s_bar" style="display:none;">
   	  	<tr>
   	    	<td valign="top" style="padding-left:5px; width:360px; text-decoration:none; color:#4444ff; background:#aabbd8;">
       	   		<div id="side_bar"></div>
       		</td>
     		</tr>
   	</table>
   	<table style="display:none;">   
   		<tr>
   			<td style="width: 360px; text-align:center;">
   				<form style="width: 360px; id="form1" action="#">
   				
      				<!-- Event Map:<input 	name= "radio1" type="checkbox" id="theatrebox" onclick="boxclick(this,'theatre')" checked /> &nbsp; -->
					Street View Only:<input name= "radio2" type="checkbox" id="golfbox" onclick="boxclick(this,'golf')" /> &nbsp;     				
      				<!-- Other Map:<input type="checkbox" id="infobox" onclick="boxclick(this,'info')" /> -->

					<?php
					// --------- Maybe for later use ---------------
					/*					
					 Other Map:<input type="checkbox" id="infobox" onclick="boxclick(this,'info')" /> 					
      				<b>Pedigree Map:</b><input id="sel2" name="select" type=radio  />
					&nbsp;&nbsp;
      				Parents: <input type="checkbox" id="parentsbox" onclick="boxclick(this,'gen1')" /> &nbsp;&nbsp;
      				Grandparents: <input type="checkbox" id="gparentsbox" onclick="boxclick(this,'gen2')" /> &nbsp;&nbsp;
      				Great Grandparents: <input type="checkbox" id="ggparentsbox" onclick="boxclick(this,'gen3')" /><br />
      				*/
					?>

 		   		</form> 
    		</td>
    		<td style="width: 200px;">
    		</td>
    	</tr>
   	</table>  
	<?php
	// =====================================================================
	
	echo "<br />";
	return $i;

} // end build_indiv_map function
