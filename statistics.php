<?php
/**
 * Creates some statistics out of the GEDCOM information.
 * We will start with the following possibilities
 * number of persons -> periodes of 50 years from 1700-2000
 * age -> periodes of 10 years (different for 0-1,1-5,5-10,10-20 etc)
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Lists
 */

require './config.php';
require_once 'includes/class_stats.php';
require_once 'includes/functions_places.php';

function get_person() {
	global $nrmale, $nrfemale, $nrpers, $persgeg, $persgeg1, $key2ind;
	global $match1, $match2;

	$myindilist = array();
	$keys = array();
	$values = array();
	$dates = array();
	$families = array();

	$myindilist = get_indi_list();
	$keys = array_keys($myindilist);
	$values = array_values($myindilist);
	$nrpers = count($myindilist);

	for($i=0; $i<$nrpers; $i++) {
		$value = $values[$i];
		$key = $keys[$i];
		$deathdate = "";
		$deathplace = "";
		$birthdate = "";
		$birthplace = "";
		$sex = "";
		$indirec= find_person_record($key);
		if (dateplace($indirec,"1 BIRT")!== false) {
			$birthdate = $match1[1];
			$birthplace = $match2[1];
		}
		if (dateplace($indirec,"1 DEAT")!==false) {
			$deathdate = $match1[1];
			$deathplace = $match2[1];
		}
		if (stringinfo($indirec,"1 SEX")!==false) {	
			$sex = 0;
			if ($match1[1] == "M") {
				$sex = 1;
				$nrmale++;
			}
			if ($match1[1] == "F") {
				$sex= 2;
				$nrfemale++;
			}
		}
		//-- get the marriage date of (the first) marriage.
		$ybirth = -1;
		$mbirth = -1;
		$pbirth = -1;
		$ydeath = -1;
		$mdeath = -1;
		$pdeath = -1;
		if ($birthdate !== "") {
			$dates = new GedcomDate($birthdate);
			if ($dates->qual1 == "") {
				$date	= $dates->MinDate();
				$date	= $date->convert_to_cal('gregorian');
				$ybirth = $date->y;
				$mbirth = $date->m;
			}
		}
		if ($birthplace !== "") {
			$pbirth = getPlaceCountry($birthplace);
		}
		if ($deathdate !== "")
		{
			$dates = new GedcomDate($deathdate);
			if ($dates->qual1 == "") {
				$date	= $dates->MinDate();
				$date	= $date->convert_to_cal('gregorian');
				$ydeath = $date->y;
				$mdeath = $date->m;
			}
		}
		if ($deathplace !== "") {
			$pdeath = getPlaceCountry($deathplace);
		}
		$families = find_sfamily_ids($key); //-- get the number of marriages of this person.
		$persgeg[$i]["key"] = $key;
		$key2ind[$key] = $i;
		$persgeg[$i]["ybirth"] = $ybirth;
		$persgeg[$i]["mbirth"] = $mbirth;
		$persgeg[$i]["pbirth"] = $pbirth;
		$persgeg[$i]["ydeath"] = $ydeath;
		$persgeg[$i]["mdeath"] = $mdeath;
		$persgeg[$i]["pdeath"] = $pdeath;
		$persgeg1[$i]["arfams"] = $families;
		$persgeg[$i]["sex"] = $sex;
	}
}

function get_family() {
	global $nrfam, $famgeg, $famgeg1, $persgeg, $key2ind;
	global $match1, $match2;

	$myfamlist = array();
	$keys = array();
	$values = array();
	$parents = array();
	$dates = array();

	$myfamlist = get_fam_list();
	$nrfam = count($myfamlist);
	$keys = array_keys($myfamlist);
	$values = array_values($myfamlist);

	for($i=0; $i<$nrfam; $i++) {
		$nrchmale = 0;
		$nrchfemale = 0;
		$value = $values[$i];
		$key = $keys[$i];
		$marriagedate = "";
		$ymarr = -1;
		$mmarr = -1;
		$divorcedate = "";
		$ydiv = -1;
		$mdiv = -1;
		$indirec = find_family_record($key);
		if (dateplace($indirec,"1 MARR")!==false) {
			$marriagedate = $match1[1];
			$marriageplace = $match2[1];
			$sex = 1;
		}
		else if (dateplace($indirec,"1 MARS")!==false) {
			$marriagedate = $match1[1];
			$marriageplace = $match2[1];
			$sex = 0;
		}
		if (dateplace($indirec,"1 DIV")!==false) {
			$divorcedate = $match1[1];
			$divorceplace = $match2[1];
		}
		if ($marriagedate !== "") {
			$dates = new GedcomDate($marriagedate);
			if ($dates->qual1 == "") {
				$date = $dates->MinDate();
				$date = $date->convert_to_cal('gregorian');
				$ymarr = $date->y;
				$mmarr = $date->m;
			}
		}
		if ($divorcedate !== "") {
			$dates = new GedcomDate($divorcedate);
			if ($dates->qual1 == "") {
				$date = $dates->MinDate();
				$date = $date->convert_to_cal('gregorian');
				$ydiv = $date->y;
				$mdiv = $date->m;
			}
		}
		$parents = find_parents($key);
		$xfather = $parents["HUSB"];
		$xmother = $parents["WIFE"];
		//--	check if divorcedate exists otherwise get deadthdate from husband or wife
		if ($ydiv !== "") {
			$ydeathf= "";
			$ydeathm= "";
			if ($xfather !== "") {
				$indf = $key2ind[$xfather];
				$ydeathf = $persgeg[$indf]["ydeath"];
			}
			if ($xmother !== "") {
				$indm = $key2ind[$xmother];
				$ydeathm = $persgeg[$indm]["ydeath"];
			}
			if (($ydeathf !== "") && ($ydeathm !== "")) {
				if ($ydeathf > $ydeathm) {
					$ydiv = $ydeathf;
					$mdiv = $persgeg[$indf]["mdeath"];
				}
				else {
					$ydiv = $ydeathm;
					$mdiv = $persgeg[$indm]["mdeath"];
				}
			}
		}
		$childs = preg_match_all("/1\s*CHIL\s*@(.*)@/", $indirec, $match1, PREG_SET_ORDER);
		$children = array();
		for($k=0; $k<$childs; $k++) {
			$children[$k] = $match1[$k][1];
			$childrec = find_person_record($children[$k]);
			$chinfo = chstringinfo($childrec,"1 SEX");
			if ($chinfo!==false) {
				if ($chinfo == "M") {
					$nrchmale++;
				}
				if ($chinfo == "F") {
					$nrchfemale++;
				}
			}
		}
		$famgeg[$i]["key"]		= $key;
		$key2ind[$key]			= $i;
		$famgeg[$i]["ymarr"]	= $ymarr;
		$famgeg[$i]["mmarr"]	= $mmarr;
		$famgeg[$i]["ydiv"]		= $ydiv;
		$famgeg[$i]["mdiv"]		= $mdiv;
		$famgeg[$i]["childs"]	= $childs;
		$famgeg[$i]["childs_m"]	= $nrchmale;
		$famgeg[$i]["childs_f"]	= $nrchfemale;
		$famgeg1[$i]["arfamc"]	= $children;
		$famgeg[$i]["male"]		= $xfather;
		$famgeg[$i]["female"]	= $xmother;
	}
}

function complete_data() {
	// fill in the first marriages instead of the keys.
	global $nrfam, $famgeg, $famgeg1, $nrpers, $persgeg, $persgeg1, $key2ind;

	$childs = array();
	$families = array();

	//look in the persgeg array for marriages that occurred
	for($i=0; $i<$nrpers; $i++){
		$families = $persgeg1[$i]["arfams"];
		$ctc = count($families);
		$marrmonth = -1;
		$marryear = -1;
		$kb = 0;
		$first = true;
		for($j=0; $j<$ctc; $j++) {
			$keyf = $families[$j];
			if (isset($key2ind[$keyf])) {
				$k = $key2ind[$keyf]; //get the family array and month/date of marriage
				$mm = $famgeg[$k]["mmarr"];
				$my = $famgeg[$k]["ymarr"];
				if ($first) {
					$marryear = $my;
					$marrmonth = $mm;
					$marrkey = $keyf;
					if ($k!="") $kb = $k;
					$first = false;
				}
				if (($marryear < 0) || (($my < $marryear) && ($my > 0))) {
					$marryear = $my;
					$marrmonth = $mm;
					$marrkey = $keyf;
					if ($k!="") $kb = $k;
					$first= false;
				}
			}
		}
		$persgeg[$i]["ymarr1"] = $marryear;
		$persgeg[$i]["mmarr1"] = $marrmonth;
		$famgeg[$kb]["ymarr1"] = $marryear;
		$famgeg[$kb]["mmarr1"] = $marrmonth;
	}
	for($i=0; $i<$nrfam; $i++) {
		$childs = $famgeg1[$i]["arfamc"];
		$ctc = count($childs);
		$birthmonth = -1;
		$birthyear = -1;
		$sex1 = 3;
		$first = true;
		for($j=0; $j<$ctc; $j++) {
			$key = $childs[$j];
			$k = $key2ind[$key];
			$bm = $persgeg[$k]["mbirth"];
			$by = $persgeg[$k]["ybirth"];
			$sex = $persgeg[$k]["sex"];
			if ($first) {
				$birthyear = $by;
				$birthmonth = $bm;
				$childkey = $key;
				$sex1 = $sex;
				$first = false;
			}
			if (($birthyear < 0) || (($by < $birthyear) && ($by > 0))) {
				$birthyear = $by;
				$birthmonth = $bm;
				$childkey = $key;
				$sex1 = $sex;
				$first= false;
			}
		}
		$famgeg[$i]["sex1"] = $sex1;
		$famgeg[$i]["ybirth1"] = $birthyear;
		$famgeg[$i]["mbirth1"] = $birthmonth;
		$persgeg[$k]["ybirth1"] = $birthyear;
		$persgeg[$k]["mbirth1"] = $birthmonth;
	}
}

function stringinfo($indirec, $lookfor) {
	//look for a starting string in the gedcom record of a person
	//then take the stripped comment
	global $match1, $match2;

	$birthrec = get_sub_record(1, $lookfor, $indirec);
	$match1[1] = "";
	$match2[1] = "";

	if ($birthrec!==false) {
		$dct = preg_match("/".$lookfor." (.*)/", $birthrec, $match1);
		if ($dct < 1) {
			$match1[1]="";
		}
		$match1[1] = trim($match1[1]);
		return true;
	}
	else {
		return false;
	}
}

function chstringinfo($childrec, $lookfor) {
	//look for a starting string in the gedcom record of a child
	//then take the stripped comment
	$rec = get_sub_record(1, $lookfor, $childrec);
	$match[1] = "";

	if ($rec!==false) {
		$dct = preg_match("/".$lookfor." (.*)/", $rec, $match);
		if ($dct < 1) {
			$match[1]="";
		}
		$match[1] = trim($match[1]);
		return $match[1];
	}
	else {
		return false;
	}
}


function dateplace($indirec, $lookfor) {
	//--look for a starting string in the gedcom record of a person or family
	//--then find the DATE and PLACE variables
	global $match1,$match2;

	$birthrec = get_sub_record(1, $lookfor, $indirec);
	//-- You need to get the subrecord in order not to mistaken by another key with same subkeys.
	$match1[1] = "";
	$match2[1] = "";

	if ($birthrec!== "") {
		$dct = preg_match("/2 DATE (.*)/", $birthrec, $match1);
		if ($dct > 0) {
			$match1[1] = trim($match1[1]);
		} else {
			$match1[1] = "";
		}
		$dct = preg_match("/2 PLAC (.*)/", $birthrec, $match2);
		if ($dct > 0) {
			$match2[1] = trim($match2[1]);
		} else {
			$match2[1] = "";
		}
		return true;
	}
	else {
		return false;
	}
}

function put_plot_data() {
	global $GEDCOM, $INDEX_DIRECTORY;
	global $famgeg, $persgeg, $key2ind;
	global $pgv_lang;

	$indexfile = $INDEX_DIRECTORY.$GEDCOM."_statistiek.php";
	$FP = fopen($indexfile, "wb");
	if (!$FP) {
		echo "<font class=\"error\">" . $pgv_lang["statutci"] . "</font>";
		exit;
	}

	/*$lists = array("famgeg"=>$famgeg, "persgeg"=>$persgeg, "key2ind"=>$key2ind);
	fwrite($FP, serialize($lists));
	*/
	fwrite($FP, 'a:3:{s:6:"famgeg";');
	fwrite($FP, serialize($famgeg));
	fwrite($FP, 's:7:"persgeg";');
	fwrite($FP, serialize($persgeg));
	fwrite($FP, 's:7:"key2ind";');
	fwrite($FP, serialize($key2ind));
	fwrite($FP, '}');
	fclose($FP);
	$logline = AddToLog($GEDCOM."_statistiek.php updated");
 	check_in($logline, $GEDCOM."_statistiek.php", $INDEX_DIRECTORY);
}

//--	========= start of main program =========

$famgeg = array();
$persgeg = array();
$famgeg1 = array();
$persgeg1 = array();
$key2ind = array();
$match1 = array();
$match2 = array();
$nrmale = 0;
$nrfemale = 0;

/*
 * Initiate the stats object.
 */
$stats = new stats($GEDCOM);

print_header($pgv_lang["statistics"]);
require 'js/autocomplete.js.htm';
?>
<script language="JavaScript" type="text/javascript">
<!--
	function statusHide(sel) {
		var box = document.getElementById(sel);
		box.style.display = "none";
		var box_m = document.getElementById(sel+"_m");
		if (box_m) box_m.style.display = "none";
		if (sel=="map_opt") {
			var box_axes = document.getElementById("axes");
			if (box_axes) box_axes.style.display = "";
			var box_zyaxes = document.getElementById("zyaxes");
			if (box_zyaxes) box_zyaxes.style.display = "";
		}
	}
	function statusShow(sel) {
		var box = document.getElementById(sel);
		box.style.display = "";
		var box_m = document.getElementById(sel+"_m");
		if (box_m) box_m.style.display = "none";
		if (sel=="map_opt") {
			var box_axes = document.getElementById("axes");
			if (box_axes) box_axes.style.display = "none";
			var box_zyaxes = document.getElementById("zyaxes");
			if (box_zyaxes) box_zyaxes.style.display = "none";
		}
	}
	function statusShowSurname(x) {
	    if (x.value == "surname_distribution_chart") {
			var box = document.getElementById("surname_opt");
			box.style.display = "";
		}
		else if (x.value !== "surname_distribution_chart") {
			var box = document.getElementById("surname_opt");
			box.style.display = "none";
		}
	}
//-->
</script>
<?php
if (!isset($_SESSION[$GEDCOM."nrpers"])) {
	$nrpers = 0;
}
else {
	$nrpers = $_SESSION[$GEDCOM."nrpers"];
	$nrfam = $_SESSION[$GEDCOM."nrfam"];
	$nrmale = $_SESSION[$GEDCOM."nrmale"];
	$nrfemale = $_SESSION[$GEDCOM."nrfemale"];
}
//-- if nrpers<1 means there is no intermediate file yet set in this session
if ($nrpers < 1) {
	get_person();
	get_family();
	complete_data();
	put_plot_data();
}

$_SESSION[$GEDCOM."nrpers"] = $nrpers;
$_SESSION[$GEDCOM."nrfam"] = $nrfam;
$_SESSION[$GEDCOM."nrmale"] = $nrmale;
$_SESSION[$GEDCOM."nrfemale"] = $nrfemale;

$params[1] = "ffffff";
$params[2] = "84beff";
echo '<h2 class="center">', $pgv_lang['statistics'], '</h2>';
echo "<table><tr><td class=\"facts_label\">".$pgv_lang["statnmale"]."</td><td class=\"facts_value\">".$nrmale."</td>";
echo "<td class=\"facts_label\">".$pgv_lang["statnnames"]."</td><td class=\"facts_value\">".$nrpers."</td></tr>";
echo "<tr><td class=\"facts_label\">".$pgv_lang["statnfemale"]."</td><td class=\"facts_value\">".$nrfemale."</td>";
echo "<td class=\"facts_label\">".$pgv_lang["statnfam"]."</td><td class=\"facts_value\">".$nrfam."</td></tr>";
echo "<tr><td class=\"facts_label\">".$pgv_lang["statnnames"]."</td><td class=\"facts_value\">".$stats->chartSex()."</td>";
echo "<td class=\"facts_label\">".$pgv_lang["statnnames"]."</td><td class=\"facts_value\">".$stats->chartMortality()."</td></tr>";
echo "<tr><td class=\"facts_label\">".$pgv_lang["stat_surnames"]."</td><td class=\"facts_value\">".$stats->chartCommonSurnames($params)."</td>";
echo "<td class=\"facts_label\">".$pgv_lang["stat_media"]."</td><td class=\"facts_value\">".$stats->chartMedia($params)."</td></tr>";
echo "<tr><td class=\"facts_label\">".$pgv_lang["stat_21_nok"]."</td><td class=\"facts_value\" colspan=\"3\">".$stats->chartLargestFamilies($params)."</td></tr>";
echo "</table>";

if (!isset($plottype)) $plottype = 11;
if (!isset($charttype)) $charttype = 1;
if (!isset($plotshow)) $plotshow = 302;
if (!isset($plotnp)) $plotnp = 201;

if (isset($_SESSION[$GEDCOM."statTicks"])) {
	$xasGrLeeftijden = $_SESSION[$GEDCOM."statTicks"]["xasGrLeeftijden"];
	$xasGrMaanden = $_SESSION[$GEDCOM."statTicks"]["xasGrMaanden"];
	$xasGrAantallen = $_SESSION[$GEDCOM."statTicks"]["xasGrAantallen"];
	$zasGrPeriode = $_SESSION[$GEDCOM."statTicks"]["zasGrPeriode"];
}
else {
	$xasGrLeeftijden = "1,5,10,20,30,40,50,60,70,80,90,100";
	$xasGrMaanden = "-24,-12,0,8,12,18,24,48";
	$xasGrAantallen = "1,2,3,4,5,6,7,8,9,10";
	$zasGrPeriode = "1700,1750,1800,1850,1900,1950,2000";
}
if (isset($_SESSION[$GEDCOM."statTicks1"])) {
	$chart_shows = $_SESSION[$GEDCOM."statTicks1"]["chart_shows"];
	$chart_type = $_SESSION[$GEDCOM."statTicks1"]["chart_type"];
	$surname = $_SESSION[$GEDCOM."statTicks1"]["surname"];
}
else {
	$chart_shows = "world";
	$chart_type = "indi_distribution_chart";
	$surname = $stats->getCommonSurname();
}

?>
	<h3><?php print_help_link("stat_help","qm"); ?> <?php echo $pgv_lang["statvars"]; ?></h3>
	<form method="post" name="form" action="statisticsplot.php?action=newform">
	<input type="hidden" name="action" value="update" />

	<table class="facts_table">
	<tr>
	<td class="descriptionbox width10 wrap"><?php print_help_link("stat_help_x","qm"); ?> <?php echo $pgv_lang["statlxa"]; ?> </td>
	<td class="optionbox">
	<input type="radio" id="stat_11" name="x-as" value="11"
	<?php
	if ($plottype == "11") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_11\">".$pgv_lang["stat_11_mb"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_12\" name=\"x-as\" value=\"12\"";
	if ($plottype == "12") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_12\">".$pgv_lang["stat_12_md"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_13\" name=\"x-as\" value=\"13\"";
	if ($plottype == "13") echo " checked=\"checked\"";
	echo " onclick=\"{statusChecked('z_none'); statusDisable('z_sex'); statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_13\">".$pgv_lang["stat_13_mm"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_15\" name=\"x-as\" value=\"15\"";
	if ($plottype == "15") echo " checked=\"checked\"";
	echo " onclick=\"{statusChecked('z_none'); statusDisable('z_sex'); statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_15\">".$pgv_lang["stat_15_mm1"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_14\" name=\"x-as\" value=\"14\"";
	if ($plottype == "14") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_14\">".$pgv_lang["stat_14_mb1"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_16\" name=\"x-as\" value=\"16\"";
	if ($plottype == "16") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusShow('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_16\">".$pgv_lang["stat_16_mmb"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_17\" name=\"x-as\" value=\"17\"";
	if ($plottype == "17") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusShow('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_17\">".$pgv_lang["stat_17_arb"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_18\" name=\"x-as\" value=\"18\"";
	if ($plottype == "18") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusShow('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_18\">".$pgv_lang["stat_18_ard"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_19\" name=\"x-as\" value=\"19\"";
	if ($plottype == "19") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusShow('x_years_m'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_19\">".$pgv_lang["stat_19_arm"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_20\" name=\"x-as\" value=\"20\"";
	if ($plottype == "20") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusShow('x_years_m'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_20\">".$pgv_lang["stat_20_arm1"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_21\" name=\"x-as\" value=\"21\"";
	if ($plottype == "21") echo " checked=\"checked\"";
	echo " onclick=\"{statusEnable('z_sex'); statusHide('x_years'); statusHide('x_months'); statusShow('x_numbers'); statusHide('map_opt');}";
	echo "\" /><label for=\"stat_21\">".$pgv_lang["stat_21_nok"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_1\" name=\"x-as\" value=\"1\"";
	if ($plottype == "1") echo " checked=\"checked\"";
	echo " onclick=\"{statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusShow('map_opt'); statusShow('chart_type'); statusHide('axes');}";
	echo "\" /><label for=\"stat_1\">".$pgv_lang["stat_1_map"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_2\" name=\"x-as\" value=\"2\"";
	if ($plottype == "2") echo " checked=\"checked\"";
	echo " onclick=\"{statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusShow('map_opt'); statusHide('chart_type'); statusHide('surname_opt');}";
	echo "\" /><label for=\"stat_2\">".$pgv_lang["stat_2_map"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_3\" name=\"x-as\" value=\"3\"";
	if ($plottype == "3") echo " checked=\"checked\"";
	echo " onclick=\"{statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusShow('map_opt'); statusHide('chart_type'); statusHide('surname_opt');}";
	echo "\" /><label for=\"stat_3\">".$pgv_lang["stat_3_map"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_9\" name=\"x-as\" value=\"9\"";
	if ($plottype == "9") echo " checked=\"checked\"";
	echo " onclick=\"{statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt'); statusHide('axes'); statusHide('zyaxes');}";
	echo "\" /><label for=\"stat_9\">".$pgv_lang["stat_9_indi"]."</label><br />";
	echo "<input type=\"radio\" id=\"stat_8\" name=\"x-as\" value=\"8\"";
	if ($plottype == "8") echo " checked=\"checked\"";
	echo " onclick=\"{statusHide('x_years'); statusHide('x_months'); statusHide('x_numbers'); statusHide('map_opt'); statusHide('axes'); statusHide('zyaxes');}";
	echo "\" /><label for=\"stat_8\">".$pgv_lang["stat_8_fam"]."</label><br />";
	?>
	<br />
	<div id="x_years" style="display:none;">
	<?php
	print_help_link("stat_help_gwx","qm");
	echo $pgv_lang["statar_xgl"];
	?>
	<br /><select id="xas-grenzen-leeftijden" name="xas-grenzen-leeftijden">
		<option value="1,5,10,20,30,40,50,60,70,80,90,100" selected="selected">1, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100</option>
		<option value="5,40,60,75,80,85,90">5, 40, 60, 75, 80, 85, 90</option>
		<option value="10,25,50,75,100">10, 25, 50, 75, 100</option>
	</select><br />
	</div>
	<div id="x_years_m" style="display:none;">
	<?php
	print_help_link("stat_help_gwx","qm");
	echo $pgv_lang["statar_xgl"];
	?>
	<br /><select id="xas-grenzen-leeftijden_m" name="xas-grenzen-leeftijden_m">
		<option value="16,18,20,22,24,26,28,30,32,35,40,50" selected="selected">16, 18, 20, 22, 24, 26, 28, 30, 32, 35, 40, 50</option>
		<option value="20,25,30,35,40,45,50">20, 25, 30, 35, 40, 45, 50</option>
	</select><br />
	</div>
	<div id="x_months" style="display:none;">
	<?php
	print_help_link("stat_help_gwx","qm");
	echo $pgv_lang["statar_xgm"];
	?>
	<br /><select id="xas-grenzen-maanden" name="xas-grenzen-maanden">
		<option value="0,8,12,15,18,24,48" selected="selected">0, 8, 12, 15, 18, 24, 48</option>
		<option value="-24,-12,0,8,12,18,24,48">-24, -12, 0, 8, 12, 18, 24, 48</option>
		<option value="0,6,9,12,15,18,21,24">0, 6, 9, 12, 15, 18, 21, 24 - <?php echo $pgv_lang["quarters"];?></option>
		<option value="0,6,12,18,24">0, 6, 12, 18, 24 - <?php echo $pgv_lang["half_year"];?></option>
	</select><br />
	</div>
	<div id="x_numbers" style="display:none;">
	<?php 
	print_help_link("stat_help_gwx","qm");
	echo $pgv_lang["statar_xga"];
	?>
	<br /><select id="xas-grenzen-aantallen" name="xas-grenzen-aantallen">
		<option value="1,2,3,4,5,6,7,8,9,10" selected="selected">1, 2, 3, 4, 5, 6, 7, 8, 9, 10</option>
		<option value="2,4,6,8,10,12">2, 4, 6, 8, 10, 12</option>
	</select>
	<br />
	</div>
	<div id="map_opt" style="display:none;">
	<div id="chart_type">
	<?php
	print_help_link('chart_type_help', 'qm');
	echo $pgv_lang["map_type"]
	?>
	<br /><select name="chart_type" onchange="statusShowSurname(this)";>
		<option value="indi_distribution_chart" selected="selected">
			<?php echo $pgv_lang["indi_distribution_chart"]; ?></option>
		<option value="surname_distribution_chart">
			<?php echo $pgv_lang["surname_distribution_chart"]; ?></option>
	</select>
	<br />
	</div>
	<div id="surname_opt" style="display:none;">
	<?php
	print_help_link('google_chart_surname_help', 'qm');
	echo $factarray['SURN'], '<br /><input type="text" name="SURN" size="20" />';
	?>
	<br />
	</div>
	<?php
	print_help_link('chart_area_help', 'qm');
	echo $pgv_lang["area_chart"]
	?>
	<br /><select id="chart_shows" name="chart_shows">
		<option value="world" selected="selected"><?php echo $pgv_lang["world_chart"]; ?></option>
		<option value="europe"><?php echo $pgv_lang["europe_chart"]; ?></option>
		<option value="south_america"><?php echo $pgv_lang["s_america_chart"]; ?></option>
		<option value="asia"><?php echo $pgv_lang["asia_chart"]; ?></option>
		<option value="middle_east"><?php echo $pgv_lang["middle_east_chart"]; ?></option>
		<option value="africa"><?php echo $pgv_lang["africa_chart"]; ?></option>
	</select>
	</div>
	
	<td class="descriptionbox width10 wrap" id="axes"><?php print_help_link("stat_help_z","qm"); ?> <?php echo $pgv_lang["statlza"]; ?>  </td>
	<td class="optionbox" id="zyaxes">
	<input type="radio" id="z_none" name="z-as" value="300"
	<?php
	if ($plotshow == "300") echo " checked=\"checked\"";
	echo " onclick=\"statusDisable('zas-grenzen-periode');";
	echo "\" /><label for=\"z_none\">".$pgv_lang["stat_300_none"]."</label><br />";
	echo "<input type=\"radio\" id=\"z_sex\" name=\"z-as\" value=\"301\"";
	if ($plotshow == "301") echo " checked=\"checked\"";
	echo " onclick=\"statusDisable('zas-grenzen-periode');";
	echo "\" /><label for=\"z_sex\">".$pgv_lang["stat_301_mf"]."</label><br />";
	echo "<input type=\"radio\" id=\"z_time\" name=\"z-as\" value=\"302\"";
	if ($plotshow == "302") echo " checked=\"checked\"";
	echo " onclick=\"statusEnable('zas-grenzen-periode');";
	echo "\" /><label for=\"z_time\">".$pgv_lang["stat_302_cgp"]."</label><br /><br />";
	print_help_link("stat_help_gwz","qm");
	echo $pgv_lang["statar_zgp"]."<br />";
	?>
	<select id="zas-grenzen-periode" name="zas-grenzen-periode">
		<option value="1700,1750,1800,1850,1900,1950,2000" selected="selected">1700,1750,1800,1850,1900,1950,2000</option>
		<option value="1800,1840,1880,1920,1950,1970,2000">1800,1840,1880,1920,1950,1970,2000</option>
		<option value="1800,1850,1900,1950,2000">1800,1850,1900,1950,2000</option>
		<option value="1800,1900,1950,2000">1800,1900,1950,2000</option>
		<option value="1900,1920,1940,1960,1980,1990,2000">1900,1920,1940,1960,1980,1990,2000</option>
		<option value="1900,1925,1950,1975,2000">1900,1925,1950,1975,2000</option>
		<option value="1940,1950,1960,1970,1980,1990,2000">1940,1950,1960,1970,1980,1990,2000</option>
	</select>
	<br /><br />
	<?php
	print_help_link("stat_help_y","qm"); 
	echo $pgv_lang["statlya"]."<br />";
	?>
	<input type="radio" id="y_num" name="y-as" value="201"
	<?php
	if ($plotnp == "201") echo " checked=\"checked\"";
	echo "\" /><label for=\"y_num\">".$pgv_lang["stat_201_num"]."</label><br />";
	echo "<input type=\"radio\" id=\"y_perc\" name=\"y-as\" value=\"202\"";
	if ($plotnp == "202") echo " checked=\"checked\"";
	echo "\" /><label for=\"y_perc\">".$pgv_lang["stat_202_perc"]."</label><br />";
	?>
	</td>
	</tr>
	<td></td><td>
	<br/>
	<input type="submit" value="<?php echo $pgv_lang["statsubmit"]; ?> " onclick="closeHelp();" />
	<input type="reset"  value=" <?php echo $pgv_lang["statreset"]; ?> " /><br/>
	</td>
	</table>
	</form>

<?php //print_help_link("stat_help","qm"); 

$_SESSION["plottype"]=$plottype;
$_SESSION["plotshow"]=$plotshow;
$_SESSION["plotnp"]=$plotnp;

echo "<br/>";
print_footer();
?>