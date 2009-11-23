<?php
/**
* PopUp Window to provide editing features.
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
	if (!empty($CALN)) $new_Notegedrec .= "2 CALN $CALN\n";
}
if (PGV_DEBUG) {
	echo "<pre>$new_Notegedrec</pre>";
}
// $xref_Note = "Test";
$xref_Note = append_gedrec($new_Notegedrec);
$link = "note.php?nid=$xref_Note&show_changes=yes";

if ($xref_Note) {
	$closeparent="yes";
	echo "<br /><br />\n".$pgv_lang["new_shared_note_created"]." (".$xref_Note.")<br /><br />";
	echo "<a href=\"javascript://NOTE $xref_Note\" onclick=\"openerpasteid('$xref_Note'); return false;\">".$pgv_lang["paste_id_into_field"]." <b>$xref_Note</b></a>\n";
	echo "<br /><br />";
	echo "<br /><br />";
	
	// DEBUG ==========================================================
	// echo "Census event now linked to Indi id's: <br />". $pid_array;
	// echo "<br /><br />";
	// ================================================================
}
// ========================================================================================





// PART (2) COPY CENSUS EVENT TO THE EXTRACTED PID's CREATION =============================
// Still working on this for auto update of census event into affected Indi Id's ==========

if (!isset($pid_array)){
	$cens_pids = array($pid);
	$idnums="";
}else{
	$cens_pids = explode(", ", $pid_array);
	$idnums="multi";
}

// DEBUG ==============
//echo '<b>PIDs: </b>';
//	print_r($cens_pids);
//echo '<br /><br />';
// =====================

// Cycle through each individual concerned defined by $cens_pids array.
foreach ($cens_pids as $pid) {
	if (isset($pid)) {
		$gedrec = find_updated_record($pid, PGV_GED_ID);
		if (empty($gedrec)) $gedrec = find_gedcom_record($pid, PGV_GED_ID);			
	} else if (isset($famid)) {
		$gedrec = find_updated_record($famid, PGV_GED_ID);
		if (empty($gedrec)) $gedrec = find_gedcom_record($famid, PGV_GED_ID);			
	}


	$lines = explode("\n", $gedrec);
	$newgedrec = "";
	for($i=0; $i<count($lines); $i++) {
		if (strpos($lines[$i], "1 _PGV_OBJS")===false) {
			$newgedrec .= $lines[$i];
		}
	}


	// variables for new_cens_gedrec
	$cens_date="05 APR 1891";
	$cens_plac="Withnell, Lancashire";
	$cens_addr="Brinscall Station, School Lane";
	$cens_pid_age="3y";
	$cens_sour="S2";
	$cens_sour_level="2";
	$cens_sour_citation="RG12/3419/131/16";
	$cens_note_id=$xref_Note;
	$cens_obje="M457";

	$new_cens_event_gedrec  = "";

	$new_cens_event_gedrec .= $newgedrec."\n";

	$new_cens_event_gedrec .= "1 CENS"."\n";
	$new_cens_event_gedrec .= "2 DATE ".$cens_date."\n";
	$new_cens_event_gedrec .= "2 PLAC ".$cens_plac."\n";
	$new_cens_event_gedrec .= "2 ADDR ".$cens_addr."\n";
	$new_cens_event_gedrec .= "2 AGE  ".$cens_pid_age."\n";
	$new_cens_event_gedrec .= $cens_sour_level." SOUR @".$cens_sour."@"."\n";
	$new_cens_event_gedrec .= "3 PAGE ".$cens_sour_citation."\n";
	$new_cens_event_gedrec .= "3 DATA"."\n";
	$new_cens_event_gedrec .= "4 DATE ".$cens_date."\n";
	$new_cens_event_gedrec .= "3 NOTE @".$cens_note_id."@"."\n";
	$new_cens_event_gedrec .= "3 OBJE @".$cens_obje."@"."\n";

// Still working on this for auto update of census event into affected Indi Id's ===============================
	// $newgedrec = trim($new_cens_event_gedrec);
	// $success2 = (!empty($newgedrec)&&(replace_gedrec($pid, $newgedrec, $update_CHAN)));

	// $success2 = replace_gedrec($pid, $new_cens_event_gedrec) ; // && append_gedrec($new_cens_event_gedrec);

	//	$xref = append_gedrec($new_cens_event_gedrec);
	//	$link = "individual.php?pid=$xref&show_changes=yes";

	//	$new_cens_event_gedrec2 = str_replace("\n", "<br />", $new_cens_event_gedrec); 
	//	echo '<b>NEW GEDREC: '.$pid.'</b><br />', $new_cens_event_gedrec2;
	//	echo '<br /><br />';
// =============================================================================================================



} // end foreach $cens_pids  -------------


?>
