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
define('PGV_AUTOCOMPLETE_LIMIT', 100);

//-- args
$FILTER = @$_GET["q"];
$OPTION = @$_GET["option"];
$field = @$_GET["field"];
if (!function_exists("autocomplete_{$field}"))
	die("Bad arg: field={$field}");

//-- database query
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
function autocomplete_INDI($limit=PGV_AUTOCOMPLETE_LIMIT) {
	global $TBLPREFIX, $DBTYPE, $DBCONN;
	global $FILTER;

	$sql = "SELECT i_id".
				" FROM {$TBLPREFIX}individuals".
				" WHERE (i_surname ".PGV_DB_LIKE." '".$DBCONN->escapeSimple($FILTER)."%'".
				" OR i_id ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%')".
				" AND i_file=".PGV_GED_ID.
				" LIMIT ".$limit;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Person::getInstance($row["i_id"]);
		if ($record->canDisplayName())
			$data[$row["i_id"]] =
				$record->getListName().
				" <u>".
				ltrim($record->getBirthYear(), "0").
				"-".
				ltrim($record->getDeathYear(), "0").
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
	foreach (autocomplete_INDI(PGV_AUTOCOMPLETE_LIMIT*2) as $k=>$v)
		$ids[] = "'".$DBCONN->escapeSimple($k)."'";

	if (empty($ids))
		//-- no match : search for FAM id
		$where = " WHERE f_id ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%'";
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
		$record = Family::getInstance($row["f_id"]);
		if ($record->canDisplayName())
			$data[$row["f_id"]] =
				$record->getSortName().
				" <u>".
				ltrim($record->getMarriageYear(), "0").
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
				" WHERE (s_name ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%'".
				" OR s_id ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%')".
				" AND s_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Source::getInstance($row["s_id"]);
		if ($record->canDisplayName())
			$data[$row["s_id"]] = $record->getFullName(); //$row["s_name"];
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
				" WHERE s_name ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%'".
				" AND s_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Source::getInstance($row["s_id"]);
		if ($record->canDisplayName())
			$data[] = $record->getFullName();
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

	$sid = $DBCONN->escapeSimple($OPTION);
	$pag = $DBCONN->escapeSimple($FILTER);

	$sql = "SELECT i_id".
				" FROM {$TBLPREFIX}individuals".
				" WHERE (i_gedcom ".PGV_DB_LIKE." '%1 SOUR @{$sid}@%2 PAGE %{$pag}%'".
				" OR     i_gedcom ".PGV_DB_LIKE." '%2 SOUR @{$sid}@%3 PAGE %{$pag}%')".
				" AND i_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Person::getInstance($row["i_id"]);
		if ($record->canDisplayDetails()) {
		  // a single INDI may have multiple level 1 sources
			$i = 1;
			do {
				$srec = get_sub_record("SOUR @{$sid}@", 1, $record->gedrec, $i++);
				$page = get_gedcom_value("PAGE", 2, $srec);
				if (stripos($page, $FILTER)!==false || empty($FILTER))
					$data[] = $page;
			} while ($srec);
		  // a single event may have multiple level 2 sources
			$i = 1;
			do {
				$srec = get_sub_record("SOUR @{$sid}@", 2, $record->gedrec, $i++);
				$page = get_gedcom_value("PAGE", 3, $srec);
				if (stripos($page, $FILTER)!==false || empty($FILTER))
					$data[] = $page;
			} while ($srec);
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

	$sid = $DBCONN->escapeSimple($OPTION);
	$pag = $DBCONN->escapeSimple($FILTER);

	$sql = "SELECT f_id".
				" FROM {$TBLPREFIX}families".
				" WHERE (f_gedcom ".PGV_DB_LIKE." '%1 SOUR @{$sid}@%2 PAGE %{$pag}%'".
				" OR     f_gedcom ".PGV_DB_LIKE." '%2 SOUR @{$sid}@%3 PAGE %{$pag}%')".
				" AND f_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Family::getInstance($row["f_id"]);
		if ($record->canDisplayDetails()) {
		  // a single FAM may have multiple level 1 sources
			$i = 1;
			do {
				$srec = get_sub_record("SOUR @{$sid}@", 1, $record->gedrec, $i++);
				$page = get_gedcom_value("PAGE", 2, $srec);
				if (stripos($page, $FILTER)!==false || empty($FILTER))
					$data[] = $page;
			} while ($srec);
		  // a single event may have multiple level 2 sources
			$i = 1;
			do {
				$srec = get_sub_record("SOUR @{$sid}@", 2, $record->gedrec, $i++);
				$page = get_gedcom_value("PAGE", 3, $srec);
				if (stripos($page, $FILTER)!==false || empty($FILTER))
					$data[] = $page;
			} while ($srec);
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
				" WHERE (o_gedcom ".PGV_DB_LIKE." '%1 NAME %".$DBCONN->escapeSimple($FILTER)."%\n%'".
				" OR o_id ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%')".
				" AND o_file=".PGV_GED_ID.
				" AND o_type='REPO'".
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Repository::getInstance($row["o_id"]);
		if ($record->canDisplayName())
			$data[$row["o_id"]] = $record->getFullName();
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
				" WHERE o_gedcom ".PGV_DB_LIKE." '%1 NAME %".$DBCONN->escapeSimple($FILTER)."%\n%'".
				" AND o_file=".PGV_GED_ID.
				" AND o_type='REPO'".
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Repository::getInstance($row["o_id"]);
		if ($record->canDisplayName())
			$data[] = $record->getFullName();
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
				" WHERE (m_titl ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%'".
				" OR m_media ".PGV_DB_LIKE." '".$DBCONN->escapeSimple($FILTER)."%')".
				" AND m_file=".PGV_GED_ID.
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$res = dbquery($sql);
	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Media::getInstance($row["m_media"]);
		if ($record && $record->canDisplayDetails())
			$data[$row["m_media"]] =
				"<img alt=\"".
				$record->getXref().
				"\" src=\"".
				$record->getThumbnail().
				"\" /> ".
				$record->getFullName();
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

	$sql = "SELECT i_id, i_surname".
				" FROM {$TBLPREFIX}individuals".
				" WHERE i_surname ".PGV_DB_LIKE." '".$DBCONN->escapeSimple($FILTER)."%'".
				" AND i_file=".PGV_GED_ID. // comment this line to search all Gedcoms
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT*10;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$record = Person::getInstance($row["i_id"]);
		if ($record->canDisplayName())
			$data[] = $row["i_surname"];
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
				" WHERE i_name ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($FILTER)."%/%/%'".
				" AND i_file=".PGV_GED_ID. // comment this line to search all Gedcoms
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT*5;
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		// assuming there is no privacy on GIVN
		$exp = explode("/", $row["i_name"]);
		$exp = $exp[0];
		// split multiple GIVN
		$exp = str_replace(",", " ", $exp);
		$exp = str_replace("*", " ", $exp);
		$exp = explode(" ", $exp);
		foreach ($exp as $k=>$v)
			if (stripos($v, $FILTER)!==false) $data[] = $v;
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

	$sql = "SELECT p_id, p_place, p_parent_id".
				" FROM {$TBLPREFIX}places".
				" WHERE p_file=".PGV_GED_ID. // comment this line to search all Gedcoms
				" ORDER BY p_parent_id, p_id";
	$res = dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow()) {
		if ($row[2]==0)
			$data[$row[0]] = $row[1];
		else {
			$data[$row[0]] = $row[1].", ".$data[$row[2]];
		}
	}
	$res->free();

	function place_ok($v) {
		global $FILTER;
		return (stripos($v, $FILTER)!==false);
		//return (substr_count($v, ", ")==3 && stripos($v, $FILTER)!==false);
	}
	$data = array_filter($data, "place_ok");

	//-- no match => perform a geoNames query
	if (empty($data)) {
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
		$tmp = $data;
		$data = array();
		foreach ($tmp as $k1=>$v1) {
			$exp = explode(",", $v1);
			foreach ($exp as $k=>$v)
				if (stripos($v, $FILTER)!==false) $data[] = trim($v);
		}
	}
	
	return $data;
}

?>
