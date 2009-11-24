<?php
/**
* Include for GEDFact Assistant - Census.
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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


// PART (1) SHARED NOTE CREATION ==========================================================
if (PGV_DEBUG) {
	phpinfo(INFO_VARIABLES);
}

$new_Notegedrec  = "0 @XREF@ NOTE\n";

if (isset($_REQUEST['EVEN'])) $EVEN = $_REQUEST['EVEN'];
if (!empty($EVEN) && count($EVEN)>0) {
	$new_Notegedrec .= "1 DATA\n";
	$new_Notegedrec .= "2 EVEN ".implode(",", $EVEN)."\n";
	if (!empty($EVEN_DATE)) $new_Notegedrec .= "3 DATE ".check_input_date($EVEN_DATE)."\n";
	if (!empty($EVEN_PLAC)) $new_Notegedrec .= "3 PLAC ".$EVEN_PLAC."\n";
	if (!empty($AGNC))      $new_Notegedrec .= "2 AGNC ".$AGNC."\n";
}
if (isset($_REQUEST['ABBR'])) $ABBR = $_REQUEST['ABBR'];
if (isset($_REQUEST['TITL'])) $TITL = $_REQUEST['TITL'];
if (isset($_REQUEST['DATE'])) $DATE = $_REQUEST['DATE'];
if (isset($_REQUEST['NOTE'])) $NOTE = $_REQUEST['NOTE'];
if (isset($_REQUEST['_HEB'])) $_HEB = $_REQUEST['_HEB'];
if (isset($_REQUEST['ROMN'])) $ROMN = $_REQUEST['ROMN'];
if (isset($_REQUEST['AUTH'])) $AUTH = $_REQUEST['AUTH'];
if (isset($_REQUEST['PUBL'])) $PUBL = $_REQUEST['PUBL'];
if (isset($_REQUEST['REPO'])) $REPO = $_REQUEST['REPO'];
if (isset($_REQUEST['CALN'])) $CALN = $_REQUEST['CALN'];

if (isset($_REQUEST['pid_array'])) 	$pid_array	 = $_REQUEST['pid_array'];
if (isset($_REQUEST['pid'])) 		$pid		 = $_REQUEST['pid'];

if (!empty($NOTE)) {
	$newlines = preg_split("/\r?\n/",$NOTE,-1);
	for($k=0; $k<count($newlines); $k++) {
		if ( $k==0 && count($newlines)>1) {
			$new_Notegedrec = "0 @XREF@ NOTE $newlines[$k]\n";
		}else if ( $k==0 ) {
			$new_Notegedrec = "0 @XREF@ NOTE $newlines[$k]\n1 CONT\n";
		} else {
			$new_Notegedrec .= "1 CONT $newlines[$k]\n";
		}
	}
}

if (!empty($ABBR)) $new_Notegedrec .= "1 ABBR $ABBR\n";
if (!empty($TITL)) {
	// $newgedrec .= "1 TITL $TITL\n";
	// $newgedrec .= "2 DATE $DATE\n";
	if (!empty($_HEB)) $new_Notegedrec .= "2 _HEB $_HEB\n";
	if (!empty($ROMN)) $new_Notegedrec .= "2 ROMN $ROMN\n";
}
if (!empty($AUTH)) $new_Notegedrec .= "1 AUTH $AUTH\n";
if (!empty($PUBL)) {
	$newlines = preg_split("/\r?\n/",$PUBL,-1,PREG_SPLIT_NO_EMPTY);
	for($k=0; $k<count($newlines); $k++) {
		if ( $k==0 ) $new_Notegedrec .= "1 PUBL $newlines[$k]\n";
		else $new_Notegedrec .= "2 CONT $newlines[$k]\n";
	}
}
if (!empty($NOTE)) {
	//$new_Notegedrec .= "1 NOTE @$NOTE@\n";
	if (!empty($CALN)) {
		$new_Notegedrec .= "2 CALN $CALN\n";
	}
}
if (PGV_DEBUG) {
	echo "<pre>$new_Notegedrec</pre>";
}
if (!$pid_array) {
	echo "<br /><br /><br />&nbsp;&nbsp;&nbsp;";
	echo "No Individuals Selected <a href=\"javascript://Close Window\" onclick=\"window.close();\">&nbsp;&nbsp; Close Window and try again </a><br /><br /><br />\n";
}else{
	$xref_Note = append_gedrec($new_Notegedrec);
}
if (isset($xref_Note)) {
	$closeparent="yes";
	$link = "individual.php?pid=$pid&show_changes=yes";
}
// End Part (1) ===========================================================================


// PART (2) COPY CENSUS EVENT TO THE EXTRACTED PID's CREATION =============================
if (!$pid_array){
	$cens_pids = array($pid);
	$idnums="none";
}else{
	$cens_pids = explode(", ", $pid_array);
	$idnums="multi";
}
// Cycle through each individual concerned defined by $cens_pids array. ======
if ($idnums=="multi") {
	foreach ($cens_pids as $pid) {
		if (isset($pid)) {
			$gedrec = find_updated_record($pid, PGV_GED_ID);
			if (empty($gedrec)) $gedrec = find_gedcom_record($pid, PGV_GED_ID);
		} else if (isset($famid)) {
			$gedrec = find_updated_record($famid, PGV_GED_ID);
			if (empty($gedrec)) $gedrec = find_gedcom_record($famid, PGV_GED_ID);
		}

		// Variables for new_cens_gedrec =====================================
		$cens_date="05 APR 1960";
		$cens_plac="Withnell, Lancashire";
		$cens_addr="Brinscall Station, School Lane";
		$cens_pid_age="3y";
		$cens_sour="S2";
		$cens_sour_citation="RG12/3419/131/16";
		$cens_note_level="2";
		$cens_obje_level="3";
		$cens_obje_id="M457";
		$cens_note_id=$xref_Note;

		// New Census event ==================================================
		$new_cens_event_gedrec  = "\n";
		$new_cens_event_gedrec .= "1 CENS"."\n";
		$new_cens_event_gedrec .= "2 DATE ".$cens_date."\n";
		$new_cens_event_gedrec .= "2 PLAC ".$cens_plac."\n";
		$new_cens_event_gedrec .= "2 ADDR ".$cens_addr."\n";
		$new_cens_event_gedrec .= "2 AGE  ".$cens_pid_age."\n";
		$new_cens_event_gedrec .= "2 SOUR @".$cens_sour."@"."\n";
		$new_cens_event_gedrec .= "3 PAGE ".$cens_sour_citation."\n";
		$new_cens_event_gedrec .= "3 DATA"."\n";
		$new_cens_event_gedrec .= "4 DATE ".$cens_date."\n";
		$new_cens_event_gedrec .= $cens_obje_level." OBJE @".$cens_obje_id."@"."\n";
		$new_cens_event_gedrec .= $cens_note_level." NOTE @".$cens_note_id."@"."\n";

		// Append New Census event to gedrec =================================== 
		$new_cens_event_gedrec_updated = $gedrec . $new_cens_event_gedrec . "\n";

		// Replace Indi GEedrec with newly added Census event ================== 
		$success = replace_gedrec($pid, $new_cens_event_gedrec_updated);

	} // end foreach $cens_pids  -------------
}
// End Part (2) ===========================================================================

?>
