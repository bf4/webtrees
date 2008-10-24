<?php
/**
 * Returns data for autocompletion
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
 * @package PhpGedView
 * @subpackage Edit
 * @version $Id$
 */
require './config.php';

//-- args
$FILTER = @$_GET["q"];
if (has_utf8($FILTER))
	$FILTER = UTF8_strtoupper($FILTER);
$FILTER = $DBCONN->escapeSimple($FILTER);

$OPTION = @$_GET["option"];

$field = @$_GET["field"];
if (!function_exists("autocomplete_{$field}"))
	die("Bad arg: field={$field}");

//-- database query
define('PGV_AUTOCOMPLETE_LIMIT', 200+100*strlen($FILTER));
eval("\$data = autocomplete_".$field."();");
if (empty($data))
	die();

//-- sort
$data = array_unique($data);
uasort($data, "stringsort");

//-- output
foreach ($data as $k=>$v)
	echo "$v|$k\n";
exit;

/**
 * returns INDIviduals matching filter
 * @return Array of string
 */
function autocomplete_INDI() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER, $OPTION;
	global $pgv_lang, $MAX_ALIVE_AGE;

	// when adding ASSOciate $OPTION may contain :
	// current INDI/FAM [, current event date]
	if ($OPTION) {
		list($pid, $event_date) = explode("|", $OPTION."|");
		$record = GedcomRecord::getInstance($pid); // INDI or FAM
		$event = new Event("1 EVEN\n2 DATE ".$event_date);
		$event_date = $event->getDate();
		$event_year = $event_date->date1->y;
		// INDI
		$indi_birth_year = 0;
		if ($record && $record->getType()=="INDI")
			$indi_birth_year = $record->getBirthYear();
		// HUSB & WIFE
		$husb_birth_year = 0;
		$wife_birth_year = 0;
		if ($record && $record->getType()=="FAM") {
			$husb = $record->getHusband();
			if ($husb)
				$husb_birth_year = $husb->getBirthYear();
			$wife = $record->getWife();
			if ($wife)
				$wife_birth_year = $wife->getBirthYear();
		}
	}

	$sql = "SELECT i_id".
				" FROM {$TBLPREFIX}individuals".
				" WHERE (i_surname ".PGV_DB_LIKE." '".$FILTER."%'".
				" OR i_id ".PGV_DB_LIKE." '%".$FILTER."%')".
				" AND i_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$person = Person::getInstance($row["i_id"]);
		// check privacy
		if (!$person->canDisplayName())
		  continue;
		// filter ASSOciate
		if ($OPTION) {
			// no self-ASSOciate
			if ($pid && $row["i_id"]==$pid)
				continue;
			// filter by birth date
			$person_birth_year = $person->getBirthYear();
			if (!$person_birth_year) {
				$estim_date = $person->getEstimatedBirthDate();
				$person_birth_year = $estim_date->date1->y;
			}
			if ($person_birth_year) {
				// born after event
				if ($event_year && $person_birth_year>$event_year)
					continue;
				// not a contemporary
				if ($indi_birth_year
				&& abs($indi_birth_year-$person_birth_year)>$MAX_ALIVE_AGE)
					continue;
				if ($husb_birth_year && $wife_birth_year
				&& abs($husb_birth_year-$person_birth_year)>$MAX_ALIVE_AGE
				&& abs($wife_birth_year-$person_birth_year)>$MAX_ALIVE_AGE)
					continue;
				elseif ($husb_birth_year
				&& abs($husb_birth_year-$person_birth_year)>$MAX_ALIVE_AGE)
					continue;
				elseif ($wife_birth_year
				&& abs($wife_birth_year-$person_birth_year)>$MAX_ALIVE_AGE)
					continue;
			}
			// filter by death date
			$person_death_year = $person->getDeathYear();
			if (!$person_death_year) {
				$estim_date = $person->getEstimatedDeathDate();
				$person_death_year = $estim_date->date1->y;
			}
			if ($person_death_year) {
				// dead before event
				if ($event_year && $person_death_year<$event_year)
					continue;
				// not a contemporary
				if ($indi_birth_year && $person_death_year<$indi_birth_year)
					continue;
				if ($husb_birth_year && $wife_birth_year
				&& $person_death_year<$husb_birth_year
				&& $person_death_year<$wife_birth_year)
					continue;
				elseif ($husb_birth_year && $person_death_year<$husb_birth_year)
					continue;
				elseif ($wife_birth_year && $person_death_year<$wife_birth_year)
					continue;
			}
		}
		// display
		$data[$row["i_id"]] = $person->getListName();
		if ($OPTION && $event_date && $person->getBirthDate())
			$data[$row["i_id"]] .= " <span class=\"age\">(".$pgv_lang["age"]." ".
														GedcomDate::GetAgeYears($person->getBirthDate(), $event_date).
														")</span>";
		else
			$data[$row["i_id"]] .= " <u>".
														ltrim($person->getBirthYear(), "0").
														"-".
														ltrim($person->getDeathYear(), "0").
														"</u>";
	}
	$res->free();
	return $data;
}

/**
 * returns FAMilies matching filter
 * @return Array of string
 */
function autocomplete_FAM() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	//-- search for INDI names
	$ids = array();
	foreach (autocomplete_INDI() as $k=>$v)
		$ids[] = "'".$DBCONN->escapeSimple($k)."'";

	if (empty($ids))
		//-- no match : search for FAM id
		$where = " WHERE f_id ".PGV_DB_LIKE." '%".$FILTER."%'";
	else
		//-- search for spouses
		$where = " WHERE (f_husb IN (".join(',', $ids).") OR f_wife IN (".join(',', $ids).") )";
	$sql = "SELECT f_id".
				" FROM {$TBLPREFIX}families".
				$where.
				" AND f_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$family = Family::getInstance($row["f_id"]);
		if ($family->canDisplayName())
			$data[$row["f_id"]] =
				$family->getSortName().
				" <u>".
				ltrim($family->getMarriageYear(), "0").
				"</u>";
	}
	$res->free();
	return $data;
}

/**
 * returns SOURces matching filter
 * @return Array of string
 */
function autocomplete_SOUR() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT s_id".
				" FROM {$TBLPREFIX}sources".
				" WHERE (s_name ".PGV_DB_LIKE." '%".$FILTER."%'".
				" OR s_id ".PGV_DB_LIKE." '%".$FILTER."%')".
				" AND s_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$source = Source::getInstance($row["s_id"]);
		if ($source->canDisplayName())
			$data[$row["s_id"]] = $source->getFullName(); //$row["s_name"];
	}
	$res->free();
	return $data;
}

/**
 * returns SOUR:TITL matching filter
 * @return Array of string
 */
function autocomplete_SOUR_TITL() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT s_id".
				" FROM {$TBLPREFIX}sources".
				" WHERE s_name ".PGV_DB_LIKE." '%".$FILTER."%'".
				" AND s_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$source = Source::getInstance($row["s_id"]);
		if ($source->canDisplayName())
			$data[] = $source->getFullName();
	}
	$res->free();
	return $data;
}

/**
 * returns INDI:SOUR:PAGE matching filter
 * @return Array of string
 */
function autocomplete_INDI_SOUR_PAGE() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER, $OPTION;

	$sql = "SELECT i_id".
				" FROM {$TBLPREFIX}individuals".
				" WHERE (i_gedcom ".PGV_DB_LIKE." '%1 SOUR @{$OPTION}@%2 PAGE %{$FILTER}%'".
				" OR     i_gedcom ".PGV_DB_LIKE." '%2 SOUR @{$OPTION}@%3 PAGE %{$FILTER}%')".
				" AND i_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$person = Person::getInstance($row["i_id"]);
		if ($person->canDisplayDetails()) {
			// a single INDI may have multiple level 1 and level 2 sources
			for ($level=1; $level<=2; $level++) {
				$i = 1;
				do {
					$srec = get_sub_record("SOUR @{$OPTION}@", $level, $person->gedrec, $i++);
					$page = get_gedcom_value("PAGE", $level+1, $srec);
					if (stripos($page, $FILTER)!==false || empty($FILTER))
						$data[] = $page;
				} while ($srec);
			}
		}
	}
	$res->free();
	return $data;
}

/**
 * returns FAM:SOUR:PAGE matching filter
 * @return Array of string
 */
function autocomplete_FAM_SOUR_PAGE() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER, $OPTION;

	$sql = "SELECT f_id".
				" FROM {$TBLPREFIX}families".
				" WHERE (f_gedcom ".PGV_DB_LIKE." '%1 SOUR @{$OPTION}@%2 PAGE %{$FILTER}%'".
				" OR     f_gedcom ".PGV_DB_LIKE." '%2 SOUR @{$OPTION}@%3 PAGE %{$FILTER}%')".
				" AND f_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$family = Family::getInstance($row["f_id"]);
		if ($family->canDisplayDetails()) {
			// a single FAM may have multiple level 1 and level 2 sources
			for ($level=1; $level<=2; $level++) {
				$i = 1;
				do {
					$srec = get_sub_record("SOUR @{$OPTION}@", $level, $family->gedrec, $i++);
					$page = get_gedcom_value("PAGE", $level+1, $srec);
					if (stripos($page, $FILTER)!==false || empty($FILTER))
						$data[] = $page;
				} while ($srec);
			}
		}
	}
	$res->free();
	return $data;
}

/**
 * returns SOUR:PAGE matching filter
 * @return Array of string
 */
function autocomplete_SOUR_PAGE() {
	return array_merge(
		autocomplete_INDI_SOUR_PAGE(),
		autocomplete_FAM_SOUR_PAGE());
}

/**
 * returns REPOsitories matching filter
 * @return Array of string
 */
function autocomplete_REPO() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT o_id".
				" FROM {$TBLPREFIX}other".
				" WHERE (o_gedcom ".PGV_DB_LIKE." '%1 NAME %".$FILTER."%\n%'".
				" OR o_id ".PGV_DB_LIKE." '%".$FILTER."%')".
				" AND o_file=".PGV_GED_ID.
				" AND o_type='REPO'".
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$repository = Repository::getInstance($row["o_id"]);
		if ($repository->canDisplayName())
			$data[$row["o_id"]] = $repository->getFullName();
	}
	$res->free();
	return $data;
}

/**
 * returns REPO:NAME matching filter
 * @return Array of string
 */
function autocomplete_REPO_NAME() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT o_id".
				" FROM {$TBLPREFIX}other".
				" WHERE o_gedcom ".PGV_DB_LIKE." '%1 NAME %".$FILTER."%\n%'".
				" AND o_file=".PGV_GED_ID.
				" AND o_type='REPO'".
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$repository = Repository::getInstance($row["o_id"]);
		if ($repository->canDisplayName())
			$data[] = $repository->getFullName();
	}
	$res->free();
	return $data;
}

/**
 * returns OBJEcts matching filter
 * @return Array of string
 */
function autocomplete_OBJE() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT m_media".
				" FROM {$TBLPREFIX}media".
				" WHERE (m_titl ".PGV_DB_LIKE." '%".$FILTER."%'".
				" OR m_media ".PGV_DB_LIKE." '".$FILTER."%')".
				" AND m_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);
	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$media = Media::getInstance($row["m_media"]);
		if ($media && $media->canDisplayDetails())
			$data[$row["m_media"]] =
				"<img alt=\"".
				$media->getXref().
				"\" src=\"".
				$media->getThumbnail().
				"\" /> ".
				$media->getFullName();
	}
	$res->free();
	return $data;
}

/**
 * returns INDI FAM SOUR REPO OBJE matching filter
 * @return Array of string
 */
function autocomplete_IFSRO() {
	global $GEDCOM_ID_PREFIX, $FAM_ID_PREFIX, $SOURCE_ID_PREFIX, $REPO_ID_PREFIX, $MEDIA_ID_PREFIX;
	global $FILTER;

	// is input text a gedcom xref ?
	$prefix = strtoupper(substr($FILTER, 0, 1));
	if (ctype_digit(substr($FILTER, 1))) {
		if ($prefix == $GEDCOM_ID_PREFIX)
			return autocomplete_INDI();
		if ($prefix == $FAM_ID_PREFIX)
			return autocomplete_FAM();
		if ($prefix == $SOURCE_ID_PREFIX)
			return autocomplete_SOUR();
		if ($prefix == $REPO_ID_PREFIX)
			return autocomplete_REPO();
		if ($prefix == $MEDIA_ID_PREFIX)
			return autocomplete_OBJE();
	}
	return array_merge(
		autocomplete_INDI(),
		autocomplete_FAM(),
		autocomplete_SOUR(),
		autocomplete_REPO(),
		autocomplete_OBJE());
}

/**
 * returns SURNames matching filter
 * @return Array of string
 */
function autocomplete_SURN() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT i_id".
				" FROM {$TBLPREFIX}individuals".
				" WHERE i_surname ".PGV_DB_LIKE." '".$FILTER."%'".
				" AND i_file=".PGV_GED_ID. // comment this line to search all Gedcoms
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$person = Person::getInstance($row["i_id"]);
		if ($person->canDisplayName()) {
			// get exact surn (i_surname db field is capitalized)
			$names = $person->getAllNames();
			$data[] = $names[$person->getPrimaryName()]['surn'];
		}
	}
	$res->free();
	return $data;
}

/**
 * returns GIVenNames matching filter
 * @return Array of string
 */
function autocomplete_GIVN() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT i_name".
				" FROM {$TBLPREFIX}individuals".
				" WHERE i_name ".PGV_DB_LIKE." '".$FILTER."%'".
				" AND i_file=".PGV_GED_ID. // comment this line to search all Gedcoms
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		// assuming there is no privacy on GIVN
		list($givn) = explode("/", $row["i_name"]);
		list($givn) = explode(",", $givn);
		list($givn) = explode("*", $givn);
		list($givn) = explode(" ", $givn);
		if ($givn)
			$data[] = $givn;
	}
	$res->free();
	return $data;
}

/**
 * returns PLACes matching filter
 * @return Array of string City, County, State/Province, Country
 */
function autocomplete_PLAC() {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $lang_short_cut, $LANGUAGE;
	global $FILTER, $OPTION;

	//-- search for place elements matching filter
	$sql = "SELECT p_id, p_place, p_parent_id".
				" FROM {$TBLPREFIX}places".
				" WHERE p_place ".PGV_DB_LIKE." '%".$FILTER."%'".
				" AND p_file=".PGV_GED_ID. // comment this line to search all Gedcoms
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$place = array();
	$parent = array();
	do {
		while ($row =& $res->fetchRow()) {
			$place[$row[0]] = $row[1];
			$parent[$row[0]] = $row[2];
		}
		$res->free();
		//-- search for missing parents
		$missing = array();
		foreach($parent as $k=>$v) {
			if ($v && !isset($place[$v]))
				$missing[] = $v;
		}
		if (count($missing)==0)
			break;
		$sql = "SELECT p_id, p_place, p_parent_id".
					" FROM {$TBLPREFIX}places".
					" WHERE p_id IN (".join(',', $missing).")".
					" AND p_file=".PGV_GED_ID. // comment this line to search all Gedcoms
					" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
		$res = dbquery($sql);
	} while (true);

	//-- build place list
	$place = array_reverse($place, true);
	$data = array();
	do {
		$repeat = false;
		foreach($place as $k=>$v) {
			if ($parent[$k]==0)
				$data[$k] = $v;
			else {
				if (isset($data[$parent[$k]]))
					$data[$k] = $v.", ".$data[$parent[$k]];
				else
					$repeat = true;
			}
		}
	} while ($repeat);
	
	//-- filter
	function place_ok($v) {
		global $FILTER;
		return (stripos($v, $FILTER)!==false);
	}
	$data = array_filter($data, "place_ok");

	//-- no match => perform a geoNames query
	if (empty($data) && strtolower(ini_get('allow_url_fopen'))=='on') {
		$url = "http://ws.geonames.org/searchJSON".
					"?name_startsWith=".urlencode($FILTER).
					"&lang=".$lang_short_cut[$LANGUAGE].
					"&fcode=CMTY&fcode=ADM4&fcode=PPL&fcode=PPLA&fcode=PPLC".
					"&style=full";
		$json = file_get_contents($url);
		// json_decode is PHP5 only !
		if (!function_exists('json_decode')){
			function json_decode($content, $assoc=false){
				require_once 'Services/JSON.php';
				if ( $assoc ){
					$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
				} else {
					$json = new Services_JSON;
				}
				return $json->decode($content);
			}
		}
		$places = json_decode($json, true);
		if ($places["geonames"])
			foreach ($places["geonames"] as $k => $place)
				$data[] = $place["name"].", ".
									$place["adminName2"].", ".
									$place["adminName1"].", ".
									$place["countryName"];
	}

	// split ?
	if ($OPTION=="split") {
		foreach ($data as $k=>$v)
			list($data[$k]) = explode(",", $v);
		$data = array_filter($data, "place_ok");
	}
	
	return $data;
}
?>
