<?php
/**
 *
 * Import specific functions
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
 * @subpackage DB
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_FUNCTIONS_IMPORT_PHP', '');

require_once 'includes/class_media.php';
include_once 'includes/functions_lang.php';
require_once 'includes/class_mutex.php';
require_once 'includes/index_cache.php';

// Programs such as FTM use the "tag formal names" instead of the actual tags.  This list lets us convert.
$TRANSLATE_TAGS=array(
	// These are standard tags, as defined in the 5.5.1 gedcom spec.
	'ABBREVIATION'=>'ABBR', 'ADDRESS'=>'ADDR', 'ADDRESS1'=>'ADR1', 'ADDRESS2'=>'ADR2', 'ADDRESS3'=>'ADR3',
	'ADOPTION'=>'ADOP', 'ADULT_CHRISTENING'=>'CHRA', 'AGENCY'=>'AGNC', 'ALIAS'=>'ALIA', 'ANCESTORS'=>'ANCE',
	'ANCES_INTEREST'=>'ANCI', 'ANNULMENT'=>'ANUL', 'ASSOCIATES'=>'ASSO', 'AUTHOR'=>'AUTH', 'BAPTISM'=>'BAPM',
	'BAPTISM_LDS'=>'BAPL', 'BAR_MITZVAH'=>'BARM', 'BAS_MITZVAH'=>'BASM', 'BIRTH'=>'BIRT', 'BLESSING'=>'BLES',
	'BURIAL'=>'BURI', 'CALL_NUMBER'=>'CALN', 'CASTE'=>'CAST', 'CAUSE'=>'CAUS', 'CENSUS'=>'CENS',
	'CHANGE'=>'CHAN', 'CHARACTER'=>'CHAR', 'CHILD'=>'CHIL', 'CHILDREN_COUNT'=>'NCHI', 'CHRISTENING'=>'CHR',
	'CONCATENATION'=>'CONC', 'CONFIRMATION'=>'CONF', 'CONFIRMATION_LDS'=>'CONL', 'CONTINUED'=>'CONT',
	'COPYRIGHT'=>'COPR', 'CORPORATE'=>'CORP', 'COUNTRY'=>'CTRY', 'CREMATION'=>'CREM', 'DEATH'=>'DEAT',
	'_DEGREE'=>'_DEG', 'DESCENDANTS'=>'DESC', 'DESCENDANT_INT'=>'DESI', 'DESTINATION'=>'DEST',
	'DIVORCE'=>'DIV', 'DIVORCE_FILED'=>'DIVF', 'EDUCATION'=>'EDUC', 'EMIGRATION'=>'EMIG', 'ENDOWMENT'=>'ENDL',
	'ENGAGEMENT'=>'ENGA', 'EVENT'=>'EVEN', 'FACSIMILE'=>'FAX', 'FAMILY'=>'FAM', 'FAMILY_CHILD'=>'FAMC',
	'FAMILY_FILE'=>'FAMF', 'FAMILY_SPOUSE'=>'FAMS', 'FIRST_COMMUNION'=>'FCOM', 'FORMAT'=>'FORM',
	'GEDCOM'=>'GEDC', 'GIVEN_NAME'=>'GIVN', 'GRADUATION'=>'GRAD', 'HEADER'=>'HEAD', 'HUSBAND'=>'HUSB',
	'IDENT_NUMBER'=>'IDNO', 'IMMIGRATION'=>'IMMI', 'INDIVIDUAL'=>'INDI', 'LANGUAGE'=>'LANG',
	'LATITUDE'=>'LATI', 'LONGITUDE'=>'LONG', 'MARRIAGE'=>'MARR', 'MARRIAGE_BANN'=>'MARB',
	'MARRIAGE_COUNT'=>'NMR', 'MARR_CONTRACT'=>'MARC', 'MARR_LICENSE'=>'MARL', 'MARR_SETTLEMENT'=>'MARS',
	'MEDIA'=>'MEDI', '_MEDICAL'=>'_MDCL', '_MILITARY_SERVICE'=>'_MILT', 'NAME_PREFIX'=>'NPFX',
	'NAME_SUFFIX'=>'NSFX', 'NATIONALITY'=>'NATI', 'NATURALIZATION'=>'NATU', 'NICKNAME'=>'NICK',
	'OBJECT'=>'OBJE', 'OCCUPATION'=>'OCCU', 'ORDINANCE'=>'ORDI', 'ORDINATION'=>'ORDN', 'PEDIGREE'=>'PEDI',
	'PHONE'=>'PHON', 'PHONETIC'=>'FONE', 'PHY_DESCRIPTION'=>'DSCR', 'PLACE'=>'PLAC', 'POSTAL_CODE'=>'POST',
	'PROBATE'=>'PROB', 'PROPERTY'=>'PROP', 'PUBLICATION'=>'PUBL', 'QUALITY_OF_DATA'=>'QUAL',
	'REC_FILE_NUMBER'=>'RFN', 'REC_ID_NUMBER'=>'RIN', 'REFERENCE'=>'REFN', 'RELATIONSHIP'=>'RELA',
	'RELIGION'=>'RELI', 'REPOSITORY'=>'REPO', 'RESIDENCE'=>'RESI', 'RESTRICTION'=>'RESN', 'RETIREMENT'=>'RETI',
	'ROMANIZED'=>'ROMN', 'SEALING_CHILD'=>'SLGC', 'SEALING_SPOUSE'=>'SLGS', 'SOC_SEC_NUMBER'=>'SSN',
	'SOURCE'=>'SOUR', 'STATE'=>'STAE', 'STATUS'=>'STAT', 'SUBMISSION'=>'SUBN', 'SUBMITTER'=>'SUBM',
	'SURNAME'=>'SURN', 'SURN_PREFIX'=>'SPFX', 'TEMPLE'=>'TEMP', 'TITLE'=>'TITL', 'TRAILER'=>'TRLR',
	'VERSION'=>'VERS', 'WEB'=>'WWW'
	// TODO: FTM uses the follow tags.  What are their full forms?
	// _DETS, _SEPR, CITN, _HEIG, _WEIG, _EXCM, _ELEC, _NAME, _MISN, _UNKN
);

/**
 * import record into database
 *
 * this function will parse the given gedcom record and add it to the database
 * @param string $gedrec the raw gedcom record to parse
 * @param boolean $update whether or not this is an updated record that has been accepted
 */
function import_record($gedrec, $update) {
	global $DBCONN, $gid, $type, $TOTAL_QUERIES, $prepared_statement;
	global $TBLPREFIX, $GEDCOM_FILE, $FILE, $pgv_lang, $USE_RIN, $gdfp, $placecache;
	global $ALPHABET_upper, $ALPHABET_lower, $place_id, $WORD_WRAPPED_NOTES, $GEDCOMS;
	global $MAX_IDS, $fpnewged, $GEDCOM, $USE_RTL_FUNCTIONS, $GENERATE_UIDS;
	global $TRANSLATE_TAGS;

	$FILE = $GEDCOM;

	// Clean input record
	$gedrec=preg_replace('/[\x00-\x09\x0B-\x0C\x0E-\x1F\x7F]+/', ' ', $gedrec); // Illegal control characters
	$gedrec=preg_replace('/[\r\n]+/', "\n", $gedrec); // Standardise line endings
	// EEK! We only need to remove repeated spaces in certain circumstances.
	// This global replace breaks various other things, so take it out
	//$gedrec=preg_replace('/ {2,}/', ' ', $gedrec); // Repeated spaces
	$gedrec=preg_replace('/(^ +| +$)/m', '', $gedrec); // Leading/trailing space
	$gedrec=preg_replace('/\n{2,}/', "\n", $gedrec); // Blank lines
	if (!$update) {
		$gedrec=str_replace('@@', '@', $gedrec); // Escaped @ signs (only if importing from file)
	}

	// Replace TAG_FORMAL_NAME (as sometimes created by FTM) with TAG
	foreach ($TRANSLATE_TAGS as $tag_full=>$tag_abbr) {
		$gedrec=preg_replace("/^(\d+ (@[^@]+@ )?){$tag_full}\b/m", '$1'.$tag_abbr, $gedrec);
	}

	//-- import different types of records
	$ct = preg_match("/0 @(.*)@ ([a-zA-Z_]+)/", $gedrec, $match);
	if ($ct > 0) {
		$gid = $match[1];
		$type = trim($match[2]);
	} else {
		$ct = preg_match("/0 (.*)/", $gedrec, $match);
		if ($ct > 0) {
			$gid = trim($match[1]);
			$type = trim($match[1]);
		} else {
			print $pgv_lang["invalid_gedformat"] . "<br /><pre>$gedrec</pre>\n";
		}
	}

	//-- check for a _UID, if the record doesn't have one, add one
	if ($GENERATE_UIDS && $type != "HEAD" && $type != "TRLR" && preg_match("/1 _UID /", $gedrec) == 0) {
		$gedrec = trim($gedrec) . "\r\n1 _UID " . uuid();
	}

	//-- keep track of the max id for each type as they are imported
	if (!isset ($MAX_IDS))
		$MAX_IDS = array ();
	$idnum = 0;
	$ct = preg_match("/(\d+)/", $gid, $match);
	if ($ct > 0)
		$idnum = $match[1];
	if (!isset ($MAX_IDS[$type]))
		$MAX_IDS[$type] = $idnum;
	else
		if ($MAX_IDS[$type] < $idnum)
			$MAX_IDS[$type] = $idnum;

	//-- replace any added ltr processing codes
	$gedrec=str_replace(array(PGV_UTF8_LRM, PGV_UTF8_RLM), "", $gedrec);
	switch ($type) {
	case 'INDI':
		$record=new Person($gedrec);
		break;
	case 'FAM':
		$record=new Family($gedrec);
		break;
	case 'SOUR':
		$record=new Source($gedrec);
		break;
	case 'REPO':
		$record=new Repository($gedrec);
		break;
	case 'OBJE':
		$record=new Media($gedrec);
		break;
	default:
		$record=new GedcomRecord($gedrec);
		break;
	}
	$ged_id=(int)($GEDCOMS[$GEDCOM]["id"]);
	$xref=$DBCONN->escapeSimple($gid);

	// Update the cross-reference/index tables.
	update_places($gid, $gedrec);
	update_dates ($gid, PGV_GED_ID, $gedrec);
	update_links ($gid, PGV_GED_ID, $gedrec);
	update_names ($gid, PGV_GED_ID, $record);

	$newrec = update_media($gid, $gedrec, $update);
	if ($newrec != $gedrec) {
		$gedrec = $newrec;
		//-- make sure we have the correct media id
		$ct = preg_match("/0 @(.*)@ ([a-zA-Z_]+)/", $gedrec, $match);
		if ($ct > 0) {
			$gid = $match[1];
			$type = trim($match[2]);
		} else
			$gid = '';
	}

	//-- set all remote link ids
	$ct = preg_match("/1 RFN (.*)/", $gedrec, $rmatch);
	if ($ct) {
		$rfn = trim($rmatch[1]);
		$sql = "INSERT INTO {$TBLPREFIX}remotelinks VALUES ('" . $DBCONN->escapeSimple($gid) . "','" . $DBCONN->escapeSimple($rfn) . "','" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "')";
		$res = dbquery($sql);
	}

	switch ($type) {
	case 'INDI':
		cleanup_tags_y($gedrec);
		$person=new Person($gedrec);
		$person->dispname=true; // Just in case the admin has blocked themself from seeing names!
		$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
		$sfams = "";
		for ($j = 0; $j < $ct; $j++) {
			$sfams .= $match[$j][1] . ";";
		}
		$ct = preg_match_all("/1 FAMC @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
		$cfams = "";
		for ($j = 0; $j < $ct; $j++) {
			$cfams .= $match[$j][1] . ";";
		}
		$isdead = -1;
		$indi = array ();
		$names=$person->getAllNames();
		foreach ($names as $n=>$name) {
			list($surn, $givn)=explode(',', $name['sort']);
			$initial=get_first_letter($name['sort']);
			if ($n) {
				// TODO: we store the first (main) name in the indi table, and the others in
				// the names table.  This means we need to search *both* tables for the indi list
				// etc.  It would be more efficient to store all the names in the names table
				// and just search that one.  This would require users to reimport their gedcoms,
				// so this change will have to wait until a major version change.
				$sql = "INSERT INTO {$TBLPREFIX}names (n_gid, n_file, n_name, n_letter, n_surname, n_type) VALUES('".$DBCONN->escapeSimple($gid)."',".$DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]).",'".$DBCONN->escapeSimple($name['full'])."','".$DBCONN->escapeSimple($initial)."','".$DBCONN->escapeSimple($surn)."','".($name['type']=='_MARNM'?'C':'A')."')";
				$res = dbquery($sql);
			}

			// Calculate Soundex Values and insert them into the database.

			// Start building the SQL Insert
			$sql = "INSERT INTO {$TBLPREFIX}soundex VALUES(" .
					"'".$DBCONN->escapeSimple($gid)."'," .
					"'".$DBCONN->escapeSimple($n)."'," .
					"'".$DBCONN->escapeSimple($GEDCOMS[$FILE]["id"])."',";

			// Ensure there is a firstname or lastname.
			// If there is no name in a field, it will contain the string "@N.N."
			if ($givn!='@P.N.') {
				Character_Substitute($givn);
				// Split the first name array
				$fnames = explode(" ", $givn);

				$std_array = array();
				$firstName_std_soundex = "";
				$firstName_dm_soundex = "";
				$dm_array = array();

				$combined = "";
				foreach($fnames as $fn) {
					if (!empty($fn)) {
						$std_array[] = soundex($fn);
						$dm_array = array_merge($dm_array, DMSoundex($fn));
					}
				}
				$fn_nospaces = strtr($givn, " ", "");
				$dm_array = array_merge($dm_array, DMSoundex($fn_nospaces));

				$std_array[] = soundex($fn_nospaces);
				$firstName_dm_soundex .= ":" . implode(":", array_unique($dm_array));

				$std_array = array_unique($std_array);
				$firstName_std_soundex = implode(":", $std_array);
				$sql .= "'".$DBCONN->escapeSimple(trim($firstName_std_soundex, ":"))."'," .
						"'".$DBCONN->escapeSimple(trim($firstName_dm_soundex,":"))."',";
			} else {
				$sql .= "NULL,NULL,";
			}

			if ($surn!='@N.N.') {
				Character_Substitute($surn);
				$lnames = explode(" ", $surn);
				$lastName_std_soundex = "";
				$lastName_dm_soundex = "";
				$dm_array = array();
				$std_array = array();

				foreach($lnames as $ln) {
					$std_array[] = soundex($ln);
					$dm_array = array_merge($dm_array, DMSoundex($ln));
				}

				$ln_nospaces = strtr($surn, " ", "");

				$std_array[] =  soundex($ln_nospaces);
				$dm_array = array_merge($dm_array, DMSoundex($ln_nospaces));
				$lastName_std_soundex .= ":" . implode(":", array_unique($std_array));
				$lastName_dm_soundex .= ":" . implode(":", array_unique($dm_array));

				$sql .= "'".$DBCONN->escapeSimple(trim($lastName_std_soundex,":"))."'," .
							"'".$DBCONN->escapeSimple(trim($lastName_dm_soundex,":"))."'";
			} else {
				$sql .= "NULL,NULL";
			}

			$sql .= ");";

			$res = dbquery($sql);

		}
		$indi["isdead"] = $isdead;
		$indi["gedcom"] = $gedrec;
		$indi["gedfile"] = $GEDCOMS[$FILE]["id"];
		if ($USE_RIN) {
			$ct = preg_match("/1 RIN (.*)/", $gedrec, $match);
			if ($ct > 0)
				$rin = trim($match[1]);
			else
				$rin = $gid;
			$indi["rin"] = $rin;
		} else
			$indi["rin"] = $gid;

		$isdead = (int)is_dead($gedrec, '', true);
		list($surn, $givn)=explode(',', $names[0]['sort']);
		$initial=get_first_letter($names[0]['sort']);
		$sql = "INSERT INTO {$TBLPREFIX}individuals (i_id, i_file, i_rin, i_name, i_isdead, i_gedcom, i_letter, i_surname) VALUES ('".$DBCONN->escapeSimple($gid)."','".$DBCONN->escapeSimple($indi["gedfile"])."','".$DBCONN->escapeSimple($indi["rin"])."','".$DBCONN->escapeSimple($names[0]['full'])."',".$DBCONN->escapeSimple($isdead).",'".$DBCONN->escapeSimple($indi["gedcom"])."','".$DBCONN->escapeSimple($initial)."','".$DBCONN->escapeSimple($surn)."')";
		$res = dbquery($sql);

		//-- PEAR supports prepared statements in mysqli we will use this code instead of the code above
		//if (!isset($prepared_statement)) $prepared_statement = $DBCONN->prepare("INSERT INTO {$TBLPREFIX}individuals VALUES (?,?,?,?,?,?,?,?)");
		//$data = array($DBCONN->escapeSimple($gid), $DBCONN->escapeSimple($indi["file"]), $indi["rin"], $names[0][0], -1, $indi["gedcom"], $DBCONN->escapeSimple($names[0][1]), $names[0][2]);
		//$res =& $DBCONN->execute($prepared_statement, $data);
		//$TOTAL_QUERIES++;
		if (DB::isError($res)) {
			// die(__LINE__." ".__FILE__."  ".$res->getMessage());
		}
		break;
	case 'FAM':
		cleanup_tags_y($gedrec);
		$parents = array ();
		$ct = preg_match("/1 HUSB @(.*)@/", $gedrec, $match);
		if ($ct > 0)
			$parents["HUSB"] = $match[1];
		else
			$parents["HUSB"] = false;
		$ct = preg_match("/1 WIFE @(.*)@/", $gedrec, $match);
		if ($ct > 0)
			$parents["WIFE"] = $match[1];
		else
			$parents["WIFE"] = false;
		$ct = preg_match_all("/\d CHIL @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
		$chil = "";
		for ($j = 0; $j < $ct; $j++) {
			$chil .= $match[$j][1] . ";";
		}
		$nchi = get_gedcom_value("NCHI", 1, $gedrec);
		if (!empty($nchi)) $ct = $nchi;
		$fam = array ();
		$fam["HUSB"] = $parents["HUSB"];
		$fam["WIFE"] = $parents["WIFE"];
		$fam["CHIL"] = $chil;
		$fam["gedcom"] = $gedrec;
		$fam["gedfile"] = $GEDCOMS[$FILE]["id"];
		$sql = "INSERT INTO {$TBLPREFIX}families (f_id, f_file, f_husb, f_wife, f_chil, f_gedcom, f_numchil) VALUES ('" . $DBCONN->escapeSimple($gid) . "','" . $DBCONN->escapeSimple($fam["gedfile"]) . "','" . $DBCONN->escapeSimple($fam["HUSB"]) . "','" . $DBCONN->escapeSimple($fam["WIFE"]) . "','" . $DBCONN->escapeSimple($fam["CHIL"]) . "','" . $DBCONN->escapeSimple($fam["gedcom"]) . "','" . $DBCONN->escapeSimple($ct) . "')";
		$res = dbquery($sql);
		break;
	case 'SOUR':
		$et = preg_match("/1 ABBR (.*)/", $gedrec, $smatch);
		if ($et > 0)
			$name = $smatch[1];
		$tt = preg_match("/1 TITL (.*)/", $gedrec, $smatch);
		if ($tt > 0)
			$name = $smatch[1];
		if (empty ($name))
			$name = $gid;
		$subindi = explode("1 TITL ", $gedrec);
		if (count($subindi) > 1) {
			$pos = strpos($subindi[1], "\n1", 0);
			if ($pos)
				$subindi[1] = substr($subindi[1], 0, $pos);
			$ct = preg_match_all("/2 CON[C|T] (.*)/", $subindi[1], $match, PREG_SET_ORDER);
			for ($i = 0; $i < $ct; $i++) {
				$name = trim($name);
				if ($WORD_WRAPPED_NOTES)
					$name .= " " . $match[$i][1];
				else
					$name .= $match[$i][1];
			}
		}
		$sql = "INSERT INTO {$TBLPREFIX}sources VALUES ('" . $DBCONN->escapeSimple($gid) . "','" . $DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]) . "','" . $DBCONN->escapeSimple($name) . "','" . $DBCONN->escapeSimple($gedrec) . "')";
		$res = dbquery($sql);
		break;
	case 'OBJE':
					//-- don't duplicate OBJE records
					//-- OBJE records are imported by update_media function
		break;
	default:
		if (preg_match("/_/", $type) == 0) {
			if ($type == "HEAD") {
				$ct = preg_match("/1 DATE (.*)/", $gedrec, $match);
				if ($ct == 0) {
					$gedrec = trim($gedrec);
					$gedrec .= "\r\n1 DATE " . date("d") . " " . date("M") . " " . date("Y");
				}
			}
			if ($gid=="") $gid = $type;
			$sql = "INSERT INTO {$TBLPREFIX}other VALUES ('" . $DBCONN->escapeSimple($gid) . "','" . $DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]) . "','" . $DBCONN->escapeSimple($type) . "','" . $DBCONN->escapeSimple($gedrec) . "')";
			$res = dbquery($sql);
		}
		break;
	} // switch

	//-- if this is not an update then write it to the new gedcom file
	if (!$update && !empty ($fpnewged) && !(empty ($gedrec)))
		fwrite($fpnewged, trim($gedrec) . "\r\n");
}

function soundex_std($text) {
	Character_Substitute($text);
	$words=explode(' ', $text);
	$soundex_array=array();
	foreach ($words as $word) {
		if ($word) {
			$soundex_array[]=soundex($word);
		}
	}
	if (count($words)>1) {
		$soundex_array[]=soundex(strtr($text, ' ', ''));
	}
	return implode(':', array_unique($soundex_array));
}

function soundex_dm($text) {
	Character_Substitute($text);
	$words=explode(' ', $text);
	$soundex_array=array();
	$combined = "";
	foreach ($words as $word) {
		if ($word) {
			$soundex_array=array_merge($soundex_array, DMSoundex($word));
		}
	}
	if (count($words)>1) {
		$soundex_array=array_merge($soundex_array, DMSoundex(strtr($text, ' ', '')));
	}
	return implode(":", array_unique($soundex_array));
}

/**
 * extract all places from the given record and insert them
 * into the places table
 * @param string $gedrec
 */
function update_places($gid, $gedrec) {
	global $FILE, $placecache, $TBLPREFIX, $DBCONN, $GEDCOMS;

	if (!isset($placecache)) $placecache = array();
	$personplace = array();
	// import all place locations, but not control info such as
	// 0 HEAD/1 PLAC or 0 _EVDEF/1 PLAC
	$pt = preg_match_all("/^[2-9] PLAC (.+)/m", $gedrec, $match, PREG_SET_ORDER);
	for ($i = 0; $i < $pt; $i++) {
		$place = trim($match[$i][1]);
		$lowplace = UTF8_strtolower($place);
		//-- if we have already visited this place for this person then we don't need to again
		if (isset($personplace[$lowplace])) continue;
		$personplace[$lowplace] = 1;
		$places = explode(',', $place);
		//-- reverse the array to start at the highest level
		$secalp = array_reverse($places);
		$parent_id = 0;
		$level = 0;
		$search = true;

		foreach ($secalp as $indexval => $place) {
			$place = trim($place);
			$place=preg_replace('/\\\"/', "", $place);
			$place=preg_replace("/[\><]/", "", $place);
			$key = strtolower($place."_".$level."_".$parent_id);
			//-- if this place has already been added then we don't need to add it again
			if (isset($placecache[$key])) {
				$parent_id = $placecache[$key];
				if (!isset($personplace[$key])) {
					$personplace[$key]=1;
					$sql = 'INSERT INTO ' . $TBLPREFIX . 'placelinks VALUES('.$parent_id.', \'' . $DBCONN->escapeSimple($gid) . '\', ' . $DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]) . ')';
					$res2 = dbquery($sql);
				}
				$level++;
				continue;
			}

			//-- only search the database while we are finding places in it
			if ($search) {
				//-- check if this place and level has already been added
				$sql = 'SELECT p_id FROM '.$TBLPREFIX.'places WHERE p_level='.$level.' AND p_file='.$GEDCOMS[$FILE]['id'].' AND p_parent_id='.$parent_id.' AND p_place LIKE \''.$DBCONN->escapeSimple($place).'\'';
				$res = dbquery($sql);
				if ($res->numRows()>0) {
					$row = $res->fetchRow();
					$p_id = $row[0];
				}
				else $search = false;
				$res->free();
			}

			//-- if we are not searching then we have to insert the place into the db
			if (!$search) {
				$std_soundex = soundex($place);
				$dm_soundex = DMSoundex($place);
				$p_id = get_next_id("places", "p_id");
				$sql = 'INSERT INTO ' . $TBLPREFIX . 'places VALUES(' . $p_id . ',  \''.$DBCONN->escapeSimple($place) . '\', '.$level.', '.$parent_id.', '.$DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]).', \''.$DBCONN->escapeSimple($std_soundex).'\', \''.$DBCONN->escapeSimple(implode(":",$dm_soundex)).'\')';
				$res2 = dbquery($sql);
			}

			$sql = 'INSERT INTO ' . $TBLPREFIX . 'placelinks VALUES('.$p_id.', \'' . $DBCONN->escapeSimple($gid) . '\', ' . $DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]) . ')';
			$res2 = dbquery($sql);
			//-- increment the level and assign the parent id for the next place level
			$parent_id = $p_id;
			$placecache[$key] = $p_id;
			$personplace[$key]=1;
			$level++;
		}
	}
}

// extract all the dates from the given record and insert them into the database
function update_dates($xref, $ged_id, $gedrec) {
	global $DBTYPE, $DBCONN, $TBLPREFIX, $factarray;

	if (strpos($gedrec, '2 DATE ') && preg_match_all("/\n1 (\w+).*(?:\n[2-9].*)*(?:\n2 DATE (.+))(?:\n[2-9].*)*/", $gedrec, $matches, PREG_SET_ORDER)) {
		$data=array();
		$xref=$DBCONN->escapeSimple($xref);
		foreach ($matches as $match) {
				$fact=$match[1];
			if (($fact=='FACT' || $fact=='EVEN') && preg_match("/\n2 TYPE (\w+)/", $match[0], $tmatch) && array_key_exists($tmatch[1], $factarray)) {
				$fact=$tmatch[1];
			}
			$date=new GedcomDate($match[2]);
			$fact=$DBCONN->escapeSimple($fact);
			$data[]="({$date->date1->d},'".$date->date1->Format('O')."',{$date->date1->m},{$date->date1->y},{$date->date1->minJD},{$date->date1->maxJD},'{$fact}','{$xref}',{$ged_id},'".$date->date1->CALENDAR_ESCAPE()."')";
			if ($date->date2) {
				$data[]="({$date->date2->d},'".$date->date2->Format('O')."',{$date->date2->m},{$date->date2->y},{$date->date2->minJD},{$date->date2->maxJD},'{$fact}','{$xref}',{$ged_id},'".$date->date2->CALENDAR_ESCAPE()."')";
		}
	}

		switch ($DBTYPE) {
		case 'mysql':
			// MySQL can insert multiple rows in one statement
			dbquery("INSERT INTO {$TBLPREFIX}dates (d_day,d_month,d_mon,d_year,d_julianday1,d_julianday2,d_fact,d_gid,d_file,d_type) VALUES ".implode(',', $data));
			break;
		default:
			foreach ($data as $datum) {
				dbquery("INSERT INTO {$TBLPREFIX}dates (d_day,d_month,d_mon,d_year,d_julianday1,d_julianday2,d_fact,d_gid,d_file,d_type) VALUES ".$datum);
}
			break;
		}
	}
	return;
}

// extract all the links from the given record and insert them into the database
function update_links($xref, $ged_id, $gedrec) {
	global $DBTYPE, $DBCONN, $TBLPREFIX;

	if (preg_match_all("/^\d+ ([A-Z0-9_]+) @([^@#\r\n][^@\r\n]*)@/m", $gedrec, $matches, PREG_SET_ORDER)) {
		$data=array();
		$xref=$DBCONN->escapeSimple($xref);
		foreach ($matches as $match) {
			$match[2]=$DBCONN->escapeSimple($match[2]);
			$sql="('{$xref}','{$match[2]}','{$match[1]}',{$ged_id})";
			// Include each link once only.
			if (!in_array($sql, $data)) {
				$data[]=$sql;
				}
			}

		switch ($DBTYPE) {
		case 'mysql':
			// MySQL can insert multiple rows in one statement
			dbquery("INSERT INTO {$TBLPREFIX}link (l_from,l_to,l_type,l_file) VALUES ".implode(',', $data));
			break;
		default:
			foreach ($data as $datum) {
				dbquery("INSERT INTO {$TBLPREFIX}link (l_from,l_to,l_type,l_file) VALUES ".$datum);
		}
			break;
		}
	}
}

// extract all the names from the given record and insert them into the database
function update_names($xref, $ged_id, $record) {
	global $DBTYPE, $DBCONN, $TBLPREFIX;

	if ($record->getType()!='FAM' && $record->getXref()) {
		$data=array();

		foreach ($record->getAllNames() as $n=>$name) {
			$tmp="({$ged_id},'".$DBCONN->escapeSimple($record->getXref())."',{$n},'{$name['type']}','".$DBCONN->escapeSimple($name['sort'])."',";
			if ($record->getType()=='INDI') {
				if ($name['givn']=='@P.N.') {
					$soundex_givn_std="NULL";
					$soundex_givn_dm="NULL";
				} else {
					$soundex_givn_std="'".soundex_std($name['givn'])."'";
					$soundex_givn_dm="'".soundex_dm($name['givn'])."'";
				}
				if ($name['surn']=='@N.N.') {
					$soundex_surn_std="NULL";
					$soundex_surn_dm="NULL";
				} else {
					$soundex_surn_std="'".soundex_std($name['surname'])."'";
					$soundex_surn_dm="'".soundex_dm($name['surname'])."'";
				}
				$data[]=$tmp."'".$DBCONN->escapeSimple($name['fullNN'])."','".$DBCONN->escapeSimple($name['listNN'])."','".$DBCONN->escapeSimple($name['surname'])."','".$DBCONN->escapeSimple($name['surn'])."','".$DBCONN->escapeSimple($name['givn'])."',{$soundex_givn_std},{$soundex_surn_std},{$soundex_givn_dm},{$soundex_surn_dm})";
			} else {
				$data[]=$tmp."'".$DBCONN->escapeSimple($name['full'])."','".$DBCONN->escapeSimple($name['list'])."')";
			}
		}

		switch ($DBTYPE) {
		case 'mysql':
			// MySQL can insert multiple rows in one statement
			if ($record->getType()=='INDI') {
				dbquery("INSERT INTO {$TBLPREFIX}name (n_file,n_id,n_num,n_type,n_sort,n_full,n_list,n_surname,n_surn,n_givn,n_soundex_givn_std,n_soundex_surn_std,n_soundex_givn_dm,n_soundex_surn_dm) VALUES ".implode(',', $data));
			} else {
				dbquery("INSERT INTO {$TBLPREFIX}name (n_file,n_id,n_num,n_type,n_sort,n_full,n_list) VALUES ".implode(',', $data));
			}
			break;
		default:
			foreach ($data as $datum) {
				if ($record->getType=='INDI') {
					dbquery("INSERT INTO {$TBLPREFIX}name (n_file,n_id,n_num,n_type,n_sort,n_full,n_list,n_surname,n_surn,n_givn,n_soundex_givn_std,n_soundex_surn_std,n_soundex_givn_dm,n_soundex_surn_dm) VALUES ".$datum);
				} else {
					dbquery("INSERT INTO {$TBLPREFIX}name (n_file,n_id,n_num,n_type,n_sort,n_full,n_list) VALUES ".$datum);
				}
			}
			break;
		}
	}
}
/**
 * Insert media items into the database
 * This method is used in conjuction with the gedcom import/update routines
 * @param string $objrec	The OBJE subrecord
 * @param int $objlevel		The original level of this OBJE
 * @param boolean $update	Whether or not this is an update or an import
 * @param string $gid		The XREF ID of the record this OBJE is related to
 * @param int $count		The count of OBJE records in the parent record
 */
function insert_media($objrec, $objlevel, $update, $gid, $count) {
	global $TBLPREFIX, $media_count, $GEDCOMS, $FILE, $DBCONN, $found_ids, $fpnewged;

	//-- check for linked OBJE records
	//-- linked records don't need to insert to media table
	$ct = preg_match("/OBJE @(.*)@/", $objrec, $match);
	if ($ct>0) {
		//-- get the old id
		$old_m_media = $match[1];
		$objref = $objrec;
		/**
		 * Hiding some code in order to fix a very annoying bug
		 * [ 1579889 ] Upgrading breaks Media links
		 *
		 * Don't understand the logic of renumbering media objects ??
		 *
		//-- if this is an import not an update get the updated ID
		if (!$update) {
			if (isset ($found_ids[$old_m_media])) {
				$new_m_media = $found_ids[$old_m_media]["new_id"];
			} else {
				$new_m_media = get_new_xref("OBJE");
				$found_ids[$old_m_media]["old_id"] = $old_m_media;
				$found_ids[$old_m_media]["new_id"] = $new_m_media;
			}
		}
		//-- an update so the ID won't change
		else $new_m_media = $old_m_media;
		**/
		$new_m_media = $old_m_media;
		$m_media = $new_m_media;
		//print "LINK: old $old_m_media new $new_m_media $objref<br />";
		if ($m_media != $old_m_media) $objref = preg_replace("/@$old_m_media@/", "@$m_media@", $objref);
	}
	//-- handle embedded OBJE records
	else {
		$m_media = get_new_xref("OBJE", true);
		$objref = subrecord_createobjectref($objrec, $objlevel, $m_media);

		//-- restructure the record to be a linked record
		$objrec = preg_replace("/ OBJE/", " @" . $m_media . "@ OBJE", $objrec);
		//-- renumber the lines
		$objrec = preg_replace("/^(\d+) /me", "($1-$objlevel).' '", $objrec);

		//-- check if another picture with the same file and title was previously imported
		$media = new Media($objrec);
		$new_media = Media::in_obje_list($media);
		if ($new_media === false) {
			//-- add it to the media database table
			$m_id = get_next_id("media", "m_id");
			$sql = "INSERT INTO {$TBLPREFIX}media (m_id, m_media, m_ext, m_titl, m_file, m_gedfile, m_gedrec)";
			$sql .= " VALUES('" . $DBCONN->escapeSimple($m_id) . "', '" . $DBCONN->escapeSimple($m_media) . "', '" . $DBCONN->escapeSimple($media->ext) . "', '" . $DBCONN->escapeSimple($media->title) . "', '" . $DBCONN->escapeSimple($media->file) . "', '" . $DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]) . "', '" . $DBCONN->escapeSimple($objrec) . "')";
			$res = dbquery($sql);
			$media_count++;
			//-- if this is not an update then write it to the new gedcom file
			if (!$update && !empty ($fpnewged))
				fwrite($fpnewged, trim($objrec) . "\r\n");
			//print "LINE ".__LINE__;
		} else {
			//-- already added so update the local id
			$objref = preg_replace("/@$m_media@/", "@$new_media@", $objref);
			$m_media = $new_media;
		}
	}
	if (isset($m_media)) {
	//-- add the entry to the media_mapping table
	$mm_id = get_next_id("media_mapping", "mm_id");
	$sql = "INSERT INTO {$TBLPREFIX}media_mapping (mm_id, mm_media, mm_gid, mm_order, mm_gedfile, mm_gedrec)";
	$sql .= " VALUES ('" . $DBCONN->escapeSimple($mm_id) . "', '" . $DBCONN->escapeSimple($m_media) . "', '" . $DBCONN->escapeSimple($gid) . "', '" . $DBCONN->escapeSimple($count) . "', '" . $DBCONN->escapeSimple($GEDCOMS[$FILE]['id']) . "', '" . $DBCONN->escapeSimple($objref) . "')";
	$res = dbquery($sql);
	return $objref;
	}
	else {
		print "Media reference error ".$objrec;
		return "";
	}
}
/**
 * import media items from record
 * @todo Decide whether or not to update the original gedcom file
 * @return string	an updated record
 */
function update_media($gid, $gedrec, $update = false) {
	global $GEDCOMS, $FILE, $TBLPREFIX, $DBCONN, $media_count, $found_ids;
	global $zero_level_media, $fpnewged, $MAX_IDS, $keepmedia;

	if (!isset ($media_count))
		$media_count = 0;
	if (!isset ($found_ids))
		$found_ids = array ();
	if (!isset ($zero_level_media))
		$zero_level_media = false;
	if (!$update && !isset ($MAX_IDS["OBJE"])) {
		if (!$keepmedia) $MAX_IDS["OBJE"] = 1;
		else {
			$sql = "SELECT ni_id FROM {$TBLPREFIX}nextid WHERE ni_type='OBJE' AND ni_gedfile='".$GEDCOMS[$FILE]['id']."'";
			$res = dbquery($sql);
			$row =& $res->fetchRow();
			$MAX_IDS["OBJE"] = $row[0];
			$res->free();
		}
	}

//		print substr($gedrec, 0, 15)."<br />\n";
	//-- handle level 0 media OBJE seperately
	$ct = preg_match("/0 @(.*)@ OBJE/", $gedrec, $match);
	if ($ct > 0) {
		$old_m_media = $match[1];
		$m_id = get_next_id("media", "m_id");
		/**
		 * Hiding some code in order to fix a very annoying bug
		 * [ 1579889 ] Upgrading breaks Media links
		 *
		 * Don't understand the logic of renumbering media objects ??
		 *
		if ($update) {
			$new_m_media = $old_m_media;
		} else {
			if (isset ($found_ids[$old_m_media])) {
				$new_m_media = $found_ids[$old_m_media]["new_id"];
			} else {
				$new_m_media = get_new_xref("OBJE");
				$found_ids[$old_m_media]["old_id"] = $old_m_media;
				$found_ids[$old_m_media]["new_id"] = $new_m_media;
			}
		}
		**/
		$new_m_media = $old_m_media;
		//print "RECORD: old $old_m_media new $new_m_media<br />";
		$gedrec = preg_replace("/@" . $old_m_media . "@/", "@" . $new_m_media . "@", $gedrec);
		$media = new Media($gedrec);
		//--check if we already have a similar object
		$new_media = Media::in_obje_list($media);
		if ($new_media === false) {
			$sql = "INSERT INTO {$TBLPREFIX}media (m_id, m_media, m_ext, m_titl, m_file, m_gedfile, m_gedrec)";
			$sql .= " VALUES('" . $DBCONN->escapeSimple($m_id) . "', '" . $DBCONN->escapeSimple($new_m_media) . "', '" . $DBCONN->escapeSimple($media->ext) . "', '" . $DBCONN->escapeSimple($media->title) . "', '" . $DBCONN->escapeSimple($media->file) . "', '" . $DBCONN->escapeSimple($GEDCOMS[$FILE]["id"]) . "', '" . $DBCONN->escapeSimple($gedrec) . "')";
			$res = dbquery($sql);
			$media_count++;
		} else {
			$new_m_media = $new_media;
			$found_ids[$old_m_media]["old_id"] = $old_m_media;
			$found_ids[$old_m_media]["new_id"] = $new_media;
			//$gedrec = preg_replace("/0 @(.*)@ OBJE/", "0 @$new_media@ OBJE", $gedrec);
			//-- record was replaced by a duplicate record so leave it out.
			return '';
		}
		return $gedrec;
	}

	if ($keepmedia) {
		$sql = "SELECT mm_media, mm_gedrec FROM {$TBLPREFIX}media_mapping WHERE mm_gid='".$DBCONN->escapeSimple($gid)."' AND mm_gedfile='".$GEDCOMS[$FILE]['id']."'";
		$res = dbquery($sql);
		$old_linked_media = array();
		while($row =& $res->fetchRow()) {
			$old_linked_media[] = $row;
		}
		$res->free();
	}

	//-- check to see if there are any media records
	//-- if there aren't any media records then don't look for them just return
	$pt = preg_match("/\d OBJE/", $gedrec, $match);
	if ($pt > 0) {
	//-- go through all of the lines and replace any local
	//--- OBJE to referenced OBJEs
	$newrec = "";
	$lines = preg_split("/[\r\n]+/", trim($gedrec));
	$ct_lines = count($lines);
	$inobj = false;
	$processed = false;
	$objlevel = 0;
	$objrec = "";
	$count = 1;
	foreach ($lines as $key => $line) {
		if (!empty ($line)) {
			// NOTE: Match lines that resemble n OBJE @0000@
			// NOTE: Renumber the old ID to a new ID and save the old ID
			// NOTE: in case there are more references to it
			$level = $line{0};
			//-- putting this code back since $objlevel, $objrec, etc vars will be
			//-- reset in sections after this
			if ($objlevel>0 && ($level<=$objlevel)) {
				$objref = insert_media($objrec, $objlevel, $update, $gid, $count);
				$count++;
				// NOTE: Add the new media object to the record
				//$newrec .= $objlevel . " OBJE @" . $m_media . "@\r\n";
				$newrec .= $objref;

				// NOTE: Set the details for the next media record
				$objlevel = 0;
				$inobj = false;
			}
			if (preg_match("/[1-9]\sOBJE\s@(.*)@/", $line, $match) != 0) {
					// NOTE: Set object level
					$objlevel = $level;
					$inobj = true;
					$objrec = $line . "\r\n";
			}
			else if (preg_match("/[1-9]\sOBJE/", $line, $match)) {
				// NOTE: Set the details for the next media record
				$objlevel = $level;
				$inobj = true;
				$objrec = $line . "\r\n";
			} else {
				$ct = preg_match("/(\d+)\s(\w+)(.*)/", $line, $match);
				if ($ct > 0) {
					if ($inobj)
						$objrec .= $line . "\r\n";
					else $newrec .= $line . "\r\n";
				}
				else $newrec .= $line . "\r\n";
			}
		}
	}
	//-- make sure the last line gets handled
	if ($inobj) {
		$objref = insert_media($objrec, $objlevel, $update, $gid, $count);
		$count++;
		$newrec .= $objref;

		// NOTE: Set the details for the next media record
		$objlevel = 0;
		$inobj = false;
	}
	}
	else $newrec = $gedrec;

	if ($keepmedia) {
		$newrec = trim($newrec)."\r\n";
		foreach($old_linked_media as $i=>$row) {
			$newrec .= trim($row[1])."\r\n";
		}
	}

	return $newrec;
}
/**
 * Create database schema
 *
 * function that checks if the database exists and creates tables
 * automatically handles version updates
 * - postgres does not support fields of type int(11) and
 * with a similar construct
 * - postgres does not like strings to be inserted into
 * the db surrounded by double quotes - it tries to treat
 * it as if it was the name of another column; the proper
 * way is to surround it by single quotes.
 */
function setup_database() {
	global $TBLPREFIX, $pgv_lang, $DBCONN, $DBTYPE;

	//---------- Check if tables exist
	$has_individuals = false;
	$has_individuals_rin = false;
	$has_individuals_letter = false;
	$has_individuals_surname = false;
	$has_families = false;
	$has_families_name = false;
	$has_families_numchil = false;
	$has_places = false;
	$has_places_gid = false;
	$has_places_std_soundex = false;
	$has_places_dm_soundex = false;
	$has_link = false;
	$has_name = false;
	$has_names = false;
	$has_names_surname = false;
	$has_names_type = false;
	$has_placelinks = false;
	$has_dates = false;
	$has_dates_mon = false;
	$has_dates_datestamp = false;
	$has_dates_juliandays = false;
	$has_media = false;
	$has_media_mapping = false;
	$has_nextid = false;
	$has_remotelinks = false;
	$has_other = false;
	$has_sources = false;
	$has_soundex = false;

	$sqlite = ($DBTYPE == "sqlite");

	$data = $DBCONN->getListOf('tables');
	foreach ($data as $indexval => $table) {
		if (empty($TBLPREFIX) || strpos($table, $TBLPREFIX) === 0) {
			switch (substr($table, strlen($TBLPREFIX))) {
				case "individuals" :
					$has_individuals = true;
					$info = $DBCONN->tableInfo($TBLPREFIX . "individuals");
					foreach ($info as $indexval => $field) {
						switch ($field["name"]) {
							case "i_rin" :
								$has_individuals_rin = true;
								break;
							case "i_letter" :
								$has_individuals_letter = true;
								break;
							case "i_surname" :
								$has_individuals_surname = true;
								break;
						}
					}
					break;
				case "places" :
					$has_places = true;
					$info = $DBCONN->tableInfo($TBLPREFIX . "places");
					foreach ($info as $indexval => $field) {
						switch ($field["name"]) {
							case "p_gid" :
								$has_places_gid = true;
								$has_places = !$sqlite;
								break;
							case "p_std_soundex":
								$has_places_std_soundex = true;
								break;
							case "p_dm_soundex":
								$has_places_dm_soundex = true;
								break;
						}
					}
					break;
				case "families" :
					$has_families = true;
					$info = $DBCONN->tableInfo($TBLPREFIX . "families");
					foreach ($info as $indexval => $field) {
						switch ($field["name"]) {
							case "f_name" :
								$has_families_name = true;
								break;
							case "f_numchil" :
								$has_families_numchil = true;
								break;
						}
					}
					break;
				case "link" :
					$has_link = true;
					break;
				case "name" :
					$has_name = true;
					break;
				case "names" :
					$has_names = true;
					$info = $DBCONN->tableInfo($TBLPREFIX . "names");
					foreach ($info as $indexval => $field) {
						switch ($field["name"]) {
							case "n_surname" :
								$has_names_surname = true;
					break;
							case "n_type" :
								$has_names_type = true;
								break;
						}
					}
					break;
				case "placelinks" :
					$has_placelinks = true;
					break;
				case "dates" :
					$has_dates = true;
					$info = $DBCONN->tableInfo($TBLPREFIX . "dates");
					foreach ($info as $indexval => $field) {
						switch ($field["name"]) {
							case "d_mon" :
								$has_dates_mon = true;
								break;
							case "d_datestamp" :
								$has_dates_datestamp = true;
								break;
							case "d_julianday1" : // d_julianday1 and d_julianday2 added together
								$has_dates_juliandays = true;
								break;
						}
					}
					break;
				case "media" :
					$has_media = true;
					break;
				case "media_mapping" :
					$has_media_mapping = true;
					break;
				case "nextid" :
					$has_nextid = true;
					break;
				case "remotelinks" :
					$has_remotelinks = true;
					break;
				case "other" :
					$has_other = true;
					break;
				case "sources" :
					$has_sources = true;
					break;
				case "soundex":
					$has_soundex = true;
					break;
			}
		}
	}

	//---------- Upgrade the database
	if (!$has_individuals || $sqlite && (!$has_individuals_rin || !$has_individuals_letter || !$has_individuals_surname)) {
		create_individuals_table();
	} else { // check columns in the table
		if (!$has_individuals_rin) {
			dbquery("ALTER TABLE {$TBLPREFIX}individuals ADD i_rin VARCHAR(255) NULL");
		}
		if (!$has_individuals_letter) {
			dbquery("ALTER TABLE {$TBLPREFIX}individuals ADD i_letter VARCHAR(5) NULL");
			dbquery("CREATE INDEX indi_letter ON {$TBLPREFIX}individuals (i_letter)");
		}
		if (!$has_individuals_surname) {
			dbquery("ALTER TABLE {$TBLPREFIX}individuals ADD i_surname VARCHAR(100) NULL");
			dbquery("CREATE INDEX indi_surn ON {$TBLPREFIX}individuals (i_surname)");
		}
		}
	if (!$has_families || $sqlite && ($has_families_name || !$has_families_numchil)) {
		create_families_table();
	} else { // check columns in the table
		if ($has_families_name) {
			dbquery("ALTER TABLE {$TBLPREFIX}families DROP COLUMN f_name");
		}
		if (!$has_families_numchil) {
			dbquery("ALTER TABLE {$TBLPREFIX}families ADD f_numchil INT NULL");
		}
	}
	if (!$has_places || $sqlite && ($has_places_gid || !$has_places_std_soundex || !$has_places_dm_soundex)) {
		create_places_table();
	} else {
		if ($has_places_gid) {
			dbquery("ALTER TABLE {$TBLPREFIX}places DROP COLUMN p_gid");
		}
		if (!$has_places_std_soundex) {
			dbquery("ALTER TABLE {$TBLPREFIX}places ADD p_std_soundex TEXT NULL");
		}
		if (!$has_places_dm_soundex) {
			dbquery("ALTER TABLE {$TBLPREFIX}places ADD p_dm_soundex TEXT NULL");
		}
	}
	if (!$has_placelinks) {
		create_placelinks_table();
	}
	if (!$has_names || $sqlite && (!$has_names_surname || !$has_names_type)) {
		create_names_table();
	} else {
		if (!$has_names_surname) {
			dbquery("ALTER TABLE {$TBLPREFIX}names ADD n_surname VARCHAR(100) NULL");
			dbquery("CREATE INDEX name_surn ON {$TBLPREFIX}names (n_surname)");
	}
	if (!$has_names_type) {
		dbquery("ALTER TABLE {$TBLPREFIX}names ADD n_type VARCHAR(10) NULL");
	}
	}
	if (!$has_dates || $sqlite && (!$has_dates_mon || !$has_dates_datestamp || !$has_dates_juliandays)) {
		create_dates_table();
	} else {
		if (!$has_dates_mon) {
			dbquery("ALTER TABLE {$TBLPREFIX}dates ADD d_mon INT NULL");
			dbquery("CREATE INDEX date_mon ON {$TBLPREFIX}dates (d_mon)");
		}
		if (!$has_dates_datestamp) {
			dbquery("ALTER TABLE {$TBLPREFIX}dates ADD d_datestamp INT NULL");
			dbquery("CREATE INDEX date_datestamp ON {$TBLPREFIX}dates (d_datestamp)");
		}
		if (!$has_dates_juliandays) {
			dbquery("ALTER TABLE {$TBLPREFIX}dates ADD d_julianday1 INT NULL");
			dbquery("ALTER TABLE {$TBLPREFIX}dates ADD d_julianday2 INT NULL");
			dbquery("CREATE INDEX date_julianday1 ON {$TBLPREFIX}dates (d_julianday1)");
			dbquery("CREATE INDEX date_julianday2 ON {$TBLPREFIX}dates (d_julianday2)");
		}
	}
	if (!$has_media) {
		create_media_table();
	}
	if (!$has_remotelinks) {
		create_remotelinks_table();
	}
	if (!$has_media_mapping) {
		create_media_mapping_table();
	}
	//-- table for keeping the next ID to store
	if (!$has_nextid) {
		create_nextid_table();
	}
	if (!$has_other) {
		create_other_table();
	}
	if (!$has_sources) {
		create_sources_table();
	}
	if(!$has_soundex) {
		create_soundex_table();
	}
	if (!$has_link) {
		create_link_table();
	}
	if (!$has_name) {
		create_name_table();
	}
	/*-- commenting out as it seems to cause more problems than it helps
	$sql = "LOCK TABLE {$TBLPREFIX}individuals WRITE, {$TBLPREFIX}families WRITE, {$TBLPREFIX}sources WRITE, {$TBLPREFIX}other WRITE, {$TBLPREFIX}places WRITE, {$TBLPREFIX}users WRITE";
	$res = dbquery($sql); */
	if (preg_match("/mysql|pgsql/", $DBTYPE) > 0)
		$DBCONN->autoCommit(false);
	//-- start a transaction
	if ($DBTYPE == 'mssql')
		$sql = "BEGIN TRANSACTION";
	else
		$sql = "BEGIN";
	$res = dbquery($sql);
}
/**
 * Create the individuals table
 */
function create_individuals_table() {
	global $TBLPREFIX, $pgv_lang, $DBCONN, $DBTYPE, $DEBUG;

	dbquery("DROP TABLE {$TBLPREFIX}individuals", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}individuals (".
		" i_id     ".PGV_DB_COL_XREF."      NOT NULL,".
		" i_file   ".PGV_DB_COL_FILE."      NOT NULL,".
		" i_rin      VARCHAR(255)           NULL,".
		" i_name     VARCHAR(255)           NULL,".
		" i_isdead   INT DEFAULT 1          NULL,".
		" i_gedcom ".PGV_DB_LONGTEXT_TYPE." NULL,".
		" i_letter   VARCHAR(5)             NULL,".
		" i_surname  VARCHAR(100)           NULL,".
		" PRIMARY KEY (i_id, i_file)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}indi_id   ON {$TBLPREFIX}individuals (i_id     )");
	dbquery("CREATE INDEX {$TBLPREFIX}indi_name   ON {$TBLPREFIX}individuals (i_name   )");
	dbquery("CREATE INDEX {$TBLPREFIX}indi_letter ON {$TBLPREFIX}individuals (i_letter )");
	dbquery("CREATE INDEX {$TBLPREFIX}indi_file ON {$TBLPREFIX}individuals (i_file   )");
	dbquery("CREATE INDEX {$TBLPREFIX}indi_surn   ON {$TBLPREFIX}individuals (i_surname)");
}
/**
 * Create the families table
 */
function create_families_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}families", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}families (".
		" f_id     ".PGV_DB_COL_XREF."      NOT NULL,".
		" f_file   ".PGV_DB_COL_FILE."      NOT NULL,".
		" f_husb   ".PGV_DB_COL_XREF."      NULL,".
		" f_wife   ".PGV_DB_COL_XREF."      NULL,".
		" f_chil     TEXT                   NULL,".
		" f_gedcom ".PGV_DB_LONGTEXT_TYPE." NULL,".
		" f_numchil  INT                    NULL,".
		" PRIMARY KEY (f_id, f_file)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}fam_id   ON {$TBLPREFIX}families (f_id  )");
	dbquery("CREATE INDEX {$TBLPREFIX}fam_file ON {$TBLPREFIX}families (f_file)");
	dbquery("CREATE INDEX {$TBLPREFIX}fam_husb ON {$TBLPREFIX}families (f_husb)");
	dbquery("CREATE INDEX {$TBLPREFIX}fam_wife ON {$TBLPREFIX}families (f_wife)");
}
/**
 * Create the sources table
 */
function create_sources_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}sources", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}sources (".
		" s_id     ".PGV_DB_COL_XREF."      NOT NULL,".
		" s_file   ".PGV_DB_COL_FILE."      NULL,".
		" s_name     VARCHAR(255)           NULL,".
		" s_gedcom ".PGV_DB_LONGTEXT_TYPE." NULL,".
		" PRIMARY KEY (s_id, s_file)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}sour_id   ON {$TBLPREFIX}sources (s_id  )");
	dbquery("CREATE INDEX {$TBLPREFIX}sour_name ON {$TBLPREFIX}sources (s_name)");
	dbquery("CREATE INDEX {$TBLPREFIX}sour_file ON {$TBLPREFIX}sources (s_file)");
}
/**
 * Create the other table
 */
function create_other_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}other", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}other (".
		" o_id     ".PGV_DB_COL_XREF."      NOT NULL,".
		" o_file   ".PGV_DB_COL_FILE."      NOT NULL,".
	 	" o_type   ".PGV_DB_COL_TAG."       NULL,".
		" o_gedcom ".PGV_DB_LONGTEXT_TYPE." NULL,".
		" PRIMARY KEY (o_id, o_file)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}other_id   ON {$TBLPREFIX}other (o_id  )");
	dbquery("CREATE INDEX {$TBLPREFIX}other_file ON {$TBLPREFIX}other (o_file)");
}
/**
 * Create the placelinks table
 */
function create_placelinks_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}placelinks", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}placelinks (".
		" pl_p_id   INT               NOT NULL,".
		" pl_gid  ".PGV_DB_COL_XREF." NOT NULL,".
		" pl_file ".PGV_DB_COL_FILE." NOT NULL,".
		" PRIMARY KEY (pl_p_id, pl_gid, pl_file)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}plindex_place ON {$TBLPREFIX}placelinks (pl_p_id)");
	dbquery("CREATE INDEX {$TBLPREFIX}plindex_gid   ON {$TBLPREFIX}placelinks (pl_gid )");
	dbquery("CREATE INDEX {$TBLPREFIX}plindex_file  ON {$TBLPREFIX}placelinks (pl_file)");
}
/**
 * Create the places table
 */
function create_places_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}places", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}places (".
		" p_id          INT          NOT NULL,".
		" p_place       VARCHAR(150)     NULL,".
		" p_level       INT              NULL,".
		" p_parent_id   INT              NULL,".
		" p_file        INT              NULL,".
		" p_std_soundex TEXT             NULL,".
		" p_dm_soundex  TEXT             NULL,".
		" PRIMARY KEY (p_id)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}place_place  ON {$TBLPREFIX}places (p_place    )");
	dbquery("CREATE INDEX {$TBLPREFIX}place_level  ON {$TBLPREFIX}places (p_level    )");
	dbquery("CREATE INDEX {$TBLPREFIX}place_parent ON {$TBLPREFIX}places (p_parent_id)");
	dbquery("CREATE INDEX {$TBLPREFIX}place_file   ON {$TBLPREFIX}places (p_file     )");
}
/**
 * Create the names table
 */
function create_names_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}names", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}names (".
		" n_gid   ".PGV_DB_COL_XREF." NOT NULL,".
		" n_file  ".PGV_DB_COL_FILE." NOT NULL,".
		" n_name    VARCHAR(255)      NULL,".
		" n_letter  VARCHAR(5)        NULL,".
		" n_surname VARCHAR(100)      NULL,".
		" n_type    VARCHAR(10)       NULL".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}name_gid    ON {$TBLPREFIX}names (n_gid    )");
	dbquery("CREATE INDEX {$TBLPREFIX}name_name   ON {$TBLPREFIX}names (n_name   )");
	dbquery("CREATE INDEX {$TBLPREFIX}name_letter ON {$TBLPREFIX}names (n_letter )");
	dbquery("CREATE INDEX {$TBLPREFIX}name_type   ON {$TBLPREFIX}names (n_type   )");
	dbquery("CREATE INDEX {$TBLPREFIX}name_surn   ON {$TBLPREFIX}names (n_surname)");
	dbquery("CREATE INDEX {$TBLPREFIX}name_file   ON {$TBLPREFIX}names (n_file   )");
}
/**
 * Create the name table
 */
function create_name_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}name", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}name (".
		" n_file           ".PGV_DB_COL_FILE." NOT NULL,".
		" n_id             ".PGV_DB_COL_XREF." NOT NULL,".
		" n_num              INT               NOT NULL,".
		" n_type             VARCHAR(15)       NOT NULL,".
		" n_sort             VARCHAR(255)      NOT NULL,". // e.g. "GOGH,VINCENT WILLEM"
		" n_full             VARCHAR(255)      NOT NULL,". // e.g. "Vincent Willem van GOGH"
		" n_list             VARCHAR(255)      NOT NULL,". // e.g. "van GOGH, Vincent Willem"
		// These fields are only used for INDI records
		" n_surname          VARCHAR(255)          NULL,". // e.g. "van GOGH"
		" n_surn             VARCHAR(255)          NULL,". // e.g. "GOGH"
		" n_givn             VARCHAR(255)          NULL,". // e.g. "Vincent Willem"
		" n_soundex_givn_std VARCHAR(255)          NULL,".
		" n_soundex_surn_std VARCHAR(255)          NULL,".
		" n_soundex_givn_dm  VARCHAR(255)          NULL,".
		" n_soundex_surn_dm  VARCHAR(255)          NULL,".
		" PRIMARY KEY (n_id, n_file, n_num)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}name_file   ON {$TBLPREFIX}name (n_file   )");
}
/**
 * Create the link table
 */
function create_link_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}link", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}link (".
		" l_file    ".PGV_DB_COL_FILE." NOT NULL,".
		" l_from    ".PGV_DB_COL_XREF." NOT NULL,".
		" l_type      VARCHAR(15)       NOT NULL,".
		" l_to      ".PGV_DB_COL_XREF." NOT NULL,".
		" PRIMARY KEY (l_from, l_file, l_type, l_to)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}ix1 ON {$TBLPREFIX}link (l_to, l_file, l_type, l_from)");
}
/**
 * Create the remotelinks table
 */
function create_remotelinks_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}remotelinks", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}remotelinks (".
		" r_gid  ".PGV_DB_COL_XREF." NOT NULL,".
		" r_linkid VARCHAR(255)      NULL,".
		" r_file ".PGV_DB_COL_FILE." NOT NULL".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}r_gid     ON {$TBLPREFIX}remotelinks (r_gid   )");
	dbquery("CREATE INDEX {$TBLPREFIX}r_link_id ON {$TBLPREFIX}remotelinks (r_linkid)");
	dbquery("CREATE INDEX {$TBLPREFIX}r_file    ON {$TBLPREFIX}remotelinks (r_file  )");
}
/**
 * Create the soundex table
 */
function create_soundex_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}soundex", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}soundex (".
		" sx_i_id      ".PGV_DB_COL_XREF." NOT NULL,".
		" sx_n_id        VARCHAR(255)      NOT NULL,".
		" sx_file      ".PGV_DB_COL_FILE." NOT NULL,".
		" sx_fn_std_code TEXT              NULL,".
		" sx_fn_dm_code  TEXT              NULL,".
		" sx_ln_std_code TEXT              NULL,".
		" sx_ln_dm_code  TEXT              NULL".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}sx_i_id_ix ON {$TBLPREFIX}soundex (sx_i_id)");
	dbquery("CREATE INDEX {$TBLPREFIX}sx_file_ix ON {$TBLPREFIX}soundex (sx_file)");
}
/**
 * Create the media table
 */
function create_media_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}media", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}media (".
		" m_id        INT                    NOT NULL,".
		" m_media   ".PGV_DB_COL_XREF."      NULL,".
		" m_ext       VARCHAR(6)             NULL,".
		" m_titl      VARCHAR(255)           NULL,".
		" m_file      VARCHAR(255)           NULL,".
		" m_gedfile ".PGV_DB_COL_FILE."      NULL,".
		" m_gedrec  ".PGV_DB_LONGTEXT_TYPE." NULL,".
		" PRIMARY KEY (m_id)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}m_media      ON {$TBLPREFIX}media (m_media           )");
	dbquery("CREATE INDEX {$TBLPREFIX}m_media_file ON {$TBLPREFIX}media (m_media, m_gedfile)");
}
/**
 * Create the dates table
 */
function create_dates_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}dates", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}dates (".
		" d_day        INT          NULL,".
		" d_month      VARCHAR(5)   NULL,".
		" d_mon        INT          NULL,".
		" d_year       INT          NULL,".
		" d_datestamp  INT               NULL,".
		" d_julianday1 INT          NULL,".
		" d_julianday2 INT          NULL,".
		" d_fact     ".PGV_DB_COL_TAG."  NULL,".
		" d_gid      ".PGV_DB_COL_XREF." NULL,".
		" d_file     ".PGV_DB_COL_FILE." NULL,".
		" d_type       VARCHAR(13)  NULL".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}date_day        ON {$TBLPREFIX}dates (d_day        )") ;
	dbquery("CREATE INDEX {$TBLPREFIX}date_month      ON {$TBLPREFIX}dates (d_month      )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_mon        ON {$TBLPREFIX}dates (d_mon        )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_year       ON {$TBLPREFIX}dates (d_year       )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_datestamp  ON {$TBLPREFIX}dates (d_datestamp  )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_julianday1 ON {$TBLPREFIX}dates (d_julianday1 )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_julianday2 ON {$TBLPREFIX}dates (d_julianday2 )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_gid        ON {$TBLPREFIX}dates (d_gid        )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_file       ON {$TBLPREFIX}dates (d_file       )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_type       ON {$TBLPREFIX}dates (d_type       )");
	dbquery("CREATE INDEX {$TBLPREFIX}date_fact_gid   ON {$TBLPREFIX}dates (d_fact, d_gid)");
}

/**
 * Create the media_mapping table
 */
function create_media_mapping_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}media_mapping", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}media_mapping (".
		" mm_id        INT                    NOT NULL,".
		" mm_media   ".PGV_DB_COL_XREF."      NOT NULL DEFAULT '',".
		" mm_gid     ".PGV_DB_COL_XREF."      NOT NULL DEFAULT '',".
		" mm_order     INT                    NOT NULL DEFAULT '0',".
		" mm_gedfile ".PGV_DB_COL_FILE."      NULL,".
		" mm_gedrec  ".PGV_DB_LONGTEXT_TYPE." NULL,".
		" PRIMARY KEY (mm_id)".
		") ".PGV_DB_UTF8_TABLE
	);
	dbquery("CREATE INDEX {$TBLPREFIX}mm_media_id      ON {$TBLPREFIX}media_mapping (mm_media, mm_gedfile)");
	dbquery("CREATE INDEX {$TBLPREFIX}mm_media_gid     ON {$TBLPREFIX}media_mapping (mm_gid, mm_gedfile  )");
	dbquery("CREATE INDEX {$TBLPREFIX}mm_media_gedfile ON {$TBLPREFIX}media_mapping (mm_gedfile          )");
}
/**
 * Create the nextid table
 */
function create_nextid_table() {
	global $TBLPREFIX;

	dbquery("DROP TABLE {$TBLPREFIX}nextid ", false);
	dbquery(
		"CREATE TABLE {$TBLPREFIX}nextid (".
		" ni_id        INT               NOT NULL,".
		" ni_type    ".PGV_DB_COL_TAG."  NOT NULL,".
		" ni_gedfile ".PGV_DB_COL_FILE." NOT NULL,".
		" PRIMARY KEY (ni_type, ni_gedfile)".
		") ".PGV_DB_UTF8_TABLE
	);
}
/**
 * delete a gedcom from the database
 *
 * deletes all of the imported data about a gedcom from the database
 * @param string $FILE	the gedcom to remove from the database
 * @param boolean $keepmedia	Whether or not to keep media and media links in the tables
 */
function empty_database($FILE, $keepmedia=false) {
	global $TBLPREFIX;

	$FILE=get_id_from_gedcom($FILE);
	dbquery("DELETE FROM {$TBLPREFIX}individuals WHERE i_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}families    WHERE f_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}sources     WHERE s_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}other       WHERE o_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}places      WHERE p_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}placelinks  WHERE pl_file={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}name        WHERE n_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}link        WHERE l_file ={$FILE}");
	dbquery("DELETE FROM {$TBLPREFIX}dates       WHERE d_file ={$FILE}");

	if (!$keepmedia) {
		dbquery("DELETE FROM {$TBLPREFIX}media         WHERE m_gedfile ={$FILE}");
		dbquery("DELETE FROM {$TBLPREFIX}media_mapping WHERE mm_gedfile={$FILE}");
	} else {
		//-- make sure that we keep the correct IDs for media
    $sql = "SELECT ni_id FROM {$TBLPREFIX}nextid WHERE ni_type='OBJE' AND ni_gedfile='{$FILE}'";
		$res =& dbquery($sql);
		if ($res->numRows() > 0) {
			$row =& $res->fetchRow();
			$num = $row[0];
		}
		$res->free();
	}

	dbquery("DELETE FROM {$TBLPREFIX}nextid WHERE ni_gedfile={$FILE}");
	if ($keepmedia && isset($num)) {
		dbquery(
			"INSERT INTO {$TBLPREFIX}nextid (".
			" ni_id, ni_type, ni_gedfile".
			") VALUES (".
			(int)($num-1).", 'OBJE', {$FILE}".
			")"
		);
	}

	dbquery("DELETE FROM {$TBLPREFIX}soundex WHERE sx_file={$FILE}");

	//-- clear all of the cache files for this gedcom
	clearCache();
}

/**
 * perform any database cleanup
 *
 * during the import process it might be necessary to cleanup some database values.  In index mode
 * the file handles need to be closed.  For database mode we probably don't need to do anything in
 * this funciton.
 */
function cleanup_database() {
	global $DBTYPE, $DBCONN, $TBLPREFIX, $MAX_IDS, $GEDCOMS, $FILE;
	/*-- commenting out as it seems to cause more problems than it helps
	$sql = "UNLOCK TABLES";
	$res = dbquery($sql); */
	//-- end the transaction
	if (isset ($MAX_IDS)) {
		$sql = "DELETE FROM {$TBLPREFIX}nextid WHERE ni_gedfile='" . $DBCONN->escapeSimple($GEDCOMS[$FILE]['id']) . "'";
		$res = dbquery($sql);
		foreach ($MAX_IDS as $type => $id) {
			$sql = "INSERT INTO {$TBLPREFIX}nextid (ni_id, ni_type, ni_gedfile) VALUES('" . $DBCONN->escapeSimple($id +1) . "', '" . $DBCONN->escapeSimple($type) . "', '" . $GEDCOMS[$FILE]["id"] . "')";
			$res = dbquery($sql);
		}
	}
	if ($DBTYPE == 'mssql')
		$sql = "COMMIT TRANSACTION";
	else
		$sql = "COMMIT";
	$res = dbquery($sql);

	//if (preg_match("/mysql|pgsql/", $DBTYPE)>0) $DBCONN->autoCommit(false);
	return;
}

/**
 * read the contents of a gedcom file
 *
 * opens a gedcom file and reads the contents into the <var>$fcontents</var> global string
 */
function read_gedcom_file() {
	global $fcontents;
	global $GEDCOM, $GEDCOMS;
	global $pgv_lang;
	$fcontents = "";
	if (isset($GEDCOMS[$GEDCOM])) {
		//-- only allow one thread to write the file at a time
		$mutex = new Mutex($GEDCOM);
		$mutex->Wait();
		$fp = fopen($GEDCOMS[$GEDCOM]["path"], "r");
		$fcontents = fread($fp, filesize($GEDCOMS[$GEDCOM]["path"]));
		fclose($fp);
		$mutex->Release();
	}
}

//-------------------------------------------- write_file
//-- this function writes the $fcontents back to the
//-- gedcom file
function write_file() {
	global $fcontents, $GEDCOMS, $GEDCOM, $INDEX_DIRECTORY;

	if (empty($fcontents)) return;
	if (preg_match("/0 TRLR/", $fcontents)==0) $fcontents.="0 TRLR\n";
	//-- write the gedcom file
	if (!is_writable($GEDCOMS[$GEDCOM]["path"])) {
		print "ERROR 5: GEDCOM file is not writable.  Unable to complete request.\n";
		AddToChangeLog("ERROR 5: GEDCOM file is not writable.  Unable to complete request. ->" . PGV_USER_NAME ."<-");
		return false;
	}
	//-- only allow one thread to write the file at a time
	$mutex = new Mutex($GEDCOM);
	$mutex->Wait();
	//-- what to do if file changed while waiting

	$fp = fopen($GEDCOMS[$GEDCOM]["path"], "wb");
	if ($fp===false) {
		print "ERROR 6: Unable to open GEDCOM file resource.  Unable to complete request.\n";
		AddToChangeLog("ERROR 6: Unable to open GEDCOM file resource.  Unable to complete request. ->" . PGV_USER_NAME ."<-");
		return false;
	}
	$fl = @flock($fp, LOCK_EX);
	if (!$fl) {
//		print "ERROR 7: Unable to obtain file lock.\n";
		AddToChangeLog("ERROR 7: Unable to obtain file lock. ->" . PGV_USER_NAME ."<-");
//		fclose($fp);
//		return false;
	}
	$fw = fwrite($fp, $fcontents);
	if ($fw===false) {
		print "ERROR 7: Unable to write to GEDCOM file.\n";
		AddToChangeLog("ERROR 7: Unable to write to GEDCOM file. ->" . PGV_USER_NAME ."<-");
		$fl = @flock($fp, LOCK_UN);
		fclose($fp);
		return false;
	}
	$fl = @flock($fp, LOCK_UN);
	fclose($fp);
	//-- always release the mutex
	$mutex->Release();
	$logline = AddToLog($GEDCOMS[$GEDCOM]["path"]." updated");
 	check_in($logline, basename($GEDCOMS[$GEDCOM]["path"]), dirname($GEDCOMS[$GEDCOM]["path"]));

	return true;;
}
/**
 * Accpet changed gedcom record into database
 *
 * This function gets an updated record from the gedcom file and replaces it in the database
 * @author John Finlay
 * @param string $cid The change id of the record to accept
 */
function accept_changes($cid) {
	global $pgv_changes, $GEDCOM, $TBLPREFIX, $FILE, $DBCONN, $GEDCOMS;
	global $INDEX_DIRECTORY, $SYNC_GEDCOM_FILE, $fcontents, $manual_save;

	if (isset ($pgv_changes[$cid])) {
		$changes = $pgv_changes[$cid];
		$change = $changes[count($changes) - 1];
		if ($GEDCOM != $change["gedcom"]) {
			$GEDCOM = $change["gedcom"];
		}
		$FILE = $GEDCOM;
		$gid = $change["gid"];
		$gedrec = $change["undo"];
		if (empty($gedrec)) {
			$gedrec = find_gedcom_record($gid);
		}

		update_record($gedrec, $change["type"]=="delete");

		//-- write the changes back to the gedcom file
		if ($SYNC_GEDCOM_FILE) {
			if (!isset($manual_save) || $manual_save==false) {
				//-- only allow one thread to accept changes at a time
				$mutex = new Mutex("accept_changes");
				$mutex->Wait();
			}

			if (empty($fcontents)) read_gedcom_file();
			if ($change["type"]=="delete") {
				$pos1 = strpos($fcontents, "\n0 @".$gid."@");
				if ($pos1!==false) {
					$pos2 = strpos($fcontents, "\n0", $pos1+5);
					if ($pos2===false) {
						$fcontents = substr($fcontents, 0, $pos1+1)."0 TRLR";
						AddToLog("Corruption found in GEDCOM $GEDCOM Attempted to correct");
					}
					else $fcontents = substr($fcontents, 0, $pos1+1).substr($fcontents, $pos2+1);
				}
				else {
					AddToLog("Corruption found in GEDCOM $GEDCOM Attempted to correct.  Deleted gedcom record $gid was not found in the gedcom file.");
				}
			}
			else if ($change["type"]=="append") {
				$pos1 = strpos($fcontents, "\n0 TRLR");
				$fcontents = substr($fcontents, 0, $pos1+1).trim($gedrec)."\r\n0 TRLR";
			}
			else if ($change["type"]=="replace") {
				$pos1 = strpos($fcontents, "\n0 @".$gid."@");
				if ($pos1!==false) {
					$pos2 = strpos($fcontents, "\n0", $pos1+5);
					if ($pos2===false) {
						$fcontents = substr($fcontents, 0, $pos1+1)."0 TRLR";
						AddToLog("Corruption found in GEDCOM $GEDCOM Attempted to correct");
					}
					else $fcontents = substr($fcontents, 0, $pos1+1).trim($gedrec)."\r\n".substr($fcontents, $pos2+1);
				}
				else {
					//-- attempted to replace a record that doesn't exist
					AddToLog("Corruption found in GEDCOM $GEDCOM Attempted to correct.  Replaced gedcom record $gid was not found in the gedcom file.");
					$pos1 = strpos($fcontents, "\n0 TRLR");
					$fcontents = substr($fcontents, 0, $pos1+1).trim($gedrec)."\r\n0 TRLR";
					AddToLog("Gedcom record $gid was appended back to the GEDCOM file.");
				}
			}
			if (!isset($manual_save) || $manual_save==false) {
				write_file();
				$mutex->Release();
			}
		}

		if ($change["type"] != "delete") {
			//-- synchronize the gedcom record with any user account
			$username = get_user_from_gedcom_xref($GEDCOM, $gid);
			if ($username && get_user_setting($username, 'sync_gedcom')=='Y') {
				$firstname = get_gedcom_value("GIVN", 2, $gedrec);
				$lastname = get_gedcom_value("SURN", 2, $gedrec);
				if (empty ($lastname)) {
					$fullname = get_gedcom_value("NAME", 1, $gedrec, "", false);
					$ct = preg_match("~(.*)/(.*)/~", $fullname, $match);
					if ($ct > 0) {
						$firstname = $match[1];
						$lastname = $match[2];
					} else
						$firstname = $fullname;
				}
				//-- SEE [ 1753047 ] Email/sync with account
				$email = get_gedcom_value("EMAIL", 1, $gedrec);
				if (empty($email)) {
					$email = get_gedcom_value("_EMAIL", 1, $gedrec);
				}
				if (!empty($email)) {
					set_user_setting($username, 'email', $email);
				}
				set_user_setting($username, 'firstname', $firstname);
				set_user_setting($username, 'lastname',  $lastname);
			}
		}

		unset ($pgv_changes[$cid]);
		if (!isset($manual_save) || $manual_save==false) write_changes();
		if (isset ($_SESSION["recent_changes"]["user"][$GEDCOM]))
			unset ($_SESSION["recent_changes"]["user"][$GEDCOM]);
		if (isset ($_SESSION["recent_changes"]["gedcom"][$GEDCOM]))
			unset ($_SESSION["recent_changes"]["gedcom"][$GEDCOM]);
		$logline = AddToLog("Accepted change $cid " . $change["type"] . " into database");
		check_in($logline, $GEDCOM, dirname($GEDCOMS[$GEDCOM]['path']));
		if (isset ($change["linkpid"]))
			accept_changes($change["linkpid"] . "_" . $GEDCOM);
		return true;
	}
	return false;
}

/**
 * update a record in the database
 * @param string $gedrec
 */
function update_record($gedrec, $delete = false) {
	global $TBLPREFIX, $GEDCOM, $DBCONN, $GEDCOMS, $FILE;

	if (empty ($FILE))
		$FILE = $GEDCOM;

	$tt = preg_match("/0 @(.+)@ (.+)/", $gedrec, $match);
	if ($tt > 0) {
		$gid = trim($match[1]);
		$type = trim($match[2]);
	} else {
		print "ERROR: Invalid gedcom record.";
		return false;
	}

	$sql = "SELECT pl_p_id FROM {$TBLPREFIX}placelinks WHERE pl_gid='" . $DBCONN->escapeSimple($gid) . "' AND pl_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	$placeids = array ();
	while ($row = & $res->fetchRow()) {
		$placeids[] = $row[0];
	}
	$sql = "DELETE FROM {$TBLPREFIX}placelinks WHERE pl_gid='" . $DBCONN->escapeSimple($gid) . "' AND pl_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	$sql = "DELETE FROM {$TBLPREFIX}dates WHERE d_gid='" . $DBCONN->escapeSimple($gid) . "' AND d_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	//-- delete any unlinked places
	foreach ($placeids as $indexval => $p_id) {
		$sql = "SELECT count(pl_p_id) FROM {$TBLPREFIX}placelinks WHERE pl_p_id=$p_id AND pl_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
		$res = dbquery($sql);

		$row = & $res->fetchRow();
		if ($row[0] == 0) {
			$sql = "DELETE FROM {$TBLPREFIX}places WHERE p_id=$p_id AND p_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
			$res = dbquery($sql);

		}
	}

	//-- delete any media mapping references
	$sql = "DELETE FROM {$TBLPREFIX}media_mapping WHERE mm_gid LIKE '" . $DBCONN->escapeSimple($gid) . "' AND mm_gedfile='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	$sql = "DELETE FROM {$TBLPREFIX}remotelinks WHERE r_gid='" . $DBCONN->escapeSimple($gid) . "' AND r_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	$sql = "DELETE FROM {$TBLPREFIX}name WHERE n_id LIKE '" . $DBCONN->escapeSimple($gid) . "' AND n_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	$sql = "DELETE FROM {$TBLPREFIX}link WHERE l_from LIKE '" . $DBCONN->escapeSimple($gid) . "' AND l_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
	$res = dbquery($sql);

	if ($type == "INDI") {
		$sql = "DELETE FROM {$TBLPREFIX}individuals WHERE i_id LIKE '" . $DBCONN->escapeSimple($gid) . "' AND i_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
		$res = dbquery($sql);

		$sql = "DELETE FROM {$TBLPREFIX}names WHERE n_gid LIKE '" . $DBCONN->escapeSimple($gid) . "' AND n_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
		$res = dbquery($sql);

		$sql = "DELETE FROM {$TBLPREFIX}soundex WHERE sx_i_id LIKE '".$DBCONN->escapeSimple($gid)."' AND sx_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])."'";
		$res = dbquery($sql);

	} else
		if ($type == "FAM") {
			$sql = "DELETE FROM {$TBLPREFIX}families WHERE f_id LIKE '" . $DBCONN->escapeSimple($gid) . "' AND f_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
			$res = dbquery($sql);

		} else
			if ($type == "SOUR") {
				$sql = "DELETE FROM {$TBLPREFIX}sources WHERE s_id LIKE '" . $DBCONN->escapeSimple($gid) . "' AND s_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
				$res = dbquery($sql);

			} else
				if ($type == "OBJE") {
					$sql = "DELETE FROM {$TBLPREFIX}media WHERE m_media LIKE '" . $DBCONN->escapeSimple($gid) . "' AND m_gedfile='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
					$res = dbquery($sql);
				} else {
					$sql = "DELETE FROM {$TBLPREFIX}other WHERE o_id LIKE '" . $DBCONN->escapeSimple($gid) . "' AND o_file='" . $DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]) . "'";
					$res = dbquery($sql);

				}
	if (!$delete) {
		import_record($gedrec, true);
	}
}

function cleanup_tags_y(& $irec) {
	$cleanup_facts = array ("ANUL","CENS","DIV","DIVF","ENGA","MARR","MARB",
		"MARC","MARL","MARS","BIRT","CHR","DEAT","BURI","CREM","ADOP","DSCR",
		"BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG",
		"IMMI","CENS","PROB","WILL","GRAD","RETI");
	$irec .= "\r\n1";
	$ft = preg_match_all("/1\s(\w+)\s/", $irec, $match);
	for ($i = 0; $i < $ft; $i++) {
		$sfact = $match[1][$i];
		$sfact = trim($sfact);
		if (in_array($sfact, $cleanup_facts)) {
			$srchstr = "/1\s" . $sfact . "\sY\r\n2/";
			$replstr = "1 " . $sfact . "\r\n2";
			$srchstr2 = "/1\s" . $sfact . "(.{0,1})\r\n2/";
			$srchstr = "/1\s" . $sfact . "\sY\r\n2/";
			$srchstr3 = "/1\s" . $sfact . "\sY\r\n1/";
			$irec = preg_replace($srchstr, $replstr, $irec);
			if (preg_match($srchstr2, $irec)) {
				$irec = preg_replace($srchstr3, "1", $irec);
			}
		}
	}
	$irec = substr($irec, 0, -3);
}

// Create a pseudo-random UUID
function uuid() {
	if (defined('PGV_USE_RFC4122')) {
		// Standards purists want this format (RFC4122)
		$fmt='%02X%02X%02X%02X-%02X%02X-%02X%02X-%02X%02X-%02X%02X%02X%02X%02X%02X';
	} else {
		// Most users want this format (for compatibility with PAF)
		$fmt='%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X%02X';
	}
	return sprintf(
		$fmt,
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255)&0x3f|0x80, // Set the version to random (10xxxxxx)
		rand(0, 255),
		rand(0, 255)&0x0f|0x40, // Set the variant to RFC4122 (0100xxxx)
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255),
		rand(0, 255)
	);
}

/**
 * parse out specific subrecords (NOTE, _PRIM, _THUM) from a given OBJE record
 *
 * @author Joseph King
 * @param string $objrec the OBJE record to retrieve the subrecords from
 * @param int $objlevel the level of the OBJE record
 * @param string $m_media that media id of the OBJE record
 * @return string containing NOTE, _PRIM, and _THUM subrecords parsed from the passed object record
 */
function subrecord_createobjectref($objrec, $objlevel, $m_media){

	//- level of subrecords is object record level + 1
	$level = $objlevel + 1;

	//- get and concatenate NOTE subrecords
	$n = 1;
	$nt = "";
	$note = "";
	do
	{
		$nt = get_sub_record($level, $level . " NOTE", $objrec, $n);
		if($nt != "") $note = $note . trim($nt)."\r\n";
		$n++;
	}while($nt != "");
	//- get and concatenate PRIM subrecords
	$n = 1;
	$pm = "";
	$prim = "";
	do
	{
		$pm = get_sub_record($level, $level . " _PRIM", $objrec, $n);
		if($pm != "") $prim = $prim . trim($pm)."\r\n";
		$n++;
	}while($pm != "");
	//- get and concatenate THUM subrecords
	$n = 1;
	$tm = "";
	$thum = "";
	do
	{
		$tm = get_sub_record($level, $level . " _THUM", $objrec, $n);
		if($tm != ""){
			//- call image cropping function ($tm contains thum data)
			$thum = $thum . trim($tm)."\r\n";
		}
		$n++;
	}while($tm != "");
	//- add object reference
	$objmed = addslashes($objlevel . ' OBJE @' . $m_media . "@\r\n" . $note . $prim . $thum);

	//- return the object media reference
	return $objmed;
}
