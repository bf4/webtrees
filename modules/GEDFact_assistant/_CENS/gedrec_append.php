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

if (isset($_REQUEST['citation'])) 	$s_citation	 = $_REQUEST['citation'];
if (isset($_REQUEST['locality'])) 	$c_addr		 = $_REQUEST['locality'];
if (isset($_REQUEST['censYear'])) 	$c_yr		 = $_REQUEST['censYear'];

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
	$newlines = preg_split("/\r?\n/", $PUBL, -1, PREG_SPLIT_NO_EMPTY);
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
// DEBUG ---------------------------------------
	//	$xref_Note="Test";
// ---------------------------------------------
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
	
		// Get each entered individual's GEDREC ==============================
		if (isset($pid)) {
			$gedrec = find_updated_record($pid, PGV_GED_ID);
			if (empty($gedrec)) $gedrec = find_gedcom_record($pid, PGV_GED_ID);
		} else if (isset($famid)) {
			$gedrec = find_updated_record($famid, PGV_GED_ID);
			if (empty($gedrec)) $gedrec = find_gedcom_record($famid, PGV_GED_ID);
		}
		
		// Variables for new_cens_gedrec =====================================
		// Census Date ---------------------------
		if (isset($c_yr)) {
			$cens_DATE=$c_yr; 
		} else { 
			$cens_DATE=""; 
		}
		// Census Place --------------------------
		if (isset($c_addr)) {
			$cens_PLAC=$c_addr;
		} else { 
			$cens_PLAC=""; 
		}
		// Census Address ------------------------
		if (isset($c_addr)) {
			$addr_lines = explode(", ", $c_addr);
			$i = 0; 
			foreach ($addr_lines as $aline) {
				if ($i==0) {
					$cens_ADDR  = "2 ADDR ".$aline."\n";
				}else{
					$cens_ADDR .= "3 CONT ".$aline."\n";
				}
				$i++;
			}
		} else { 
			$cens_ADDR=""; 
		}
		// Census Indi age -----------------------
		/*
		$cens_pid_AGE="";
		*/
		// Source ID -----------------------------
		if (isset($text[0])) {
			$cens_SOUR_id=$text[0];
		} else { 
			$cens_SOUR_id=""; 
		}
		// Source Citation -----------------------
		if (isset($s_citation)) {
			$cens_SOUR_PAGE=$s_citation;
		} else { 
			$cens_SOUR_PAGE=""; 
		}
		// Source Date of Entry into original ----
		if (isset($c_yr)) {
			$cens_SOUR_DATE=$c_yr; 
		} else { 
			$cens_SOUR_DATE=""; 
		}
		// Multimedia Object ---------------------
		$cens_OBJE_level="3";
		if (isset($text[1])) {
			$cens_OBJE_id=$text[1];
		} else { 
			$cens_OBJE_id=""; 
		}
		// Shared Note ---------------------------
		$cens_NOTE_level="2";
		$cens_NOTE_id=$xref_Note;
		
		
		// New Census event to be added =========================================
		$new_cens_event_gedrec  = "\n";
		$new_cens_event_gedrec .= "1 CENS"."\n";
		$new_cens_event_gedrec .= "2 DATE ".$cens_DATE."\n";
		$new_cens_event_gedrec .= "2 PLAC ".$cens_PLAC."\n";
		// $new_cens_event_gedrec .= "2 ADDR ".$cens_ADDR."\n";
		$new_cens_event_gedrec .= $cens_ADDR;
		// $new_cens_event_gedrec .= "2 AGE  ".$cens_pid_AGE."\n";
		$new_cens_event_gedrec .= "2 SOUR @".$cens_SOUR_id."@"."\n";
		$new_cens_event_gedrec .= "3 PAGE ".$cens_SOUR_PAGE."\n";
		$new_cens_event_gedrec .= "3 DATA"."\n";
		$new_cens_event_gedrec .= "4 DATE ".$cens_SOUR_DATE."\n";
		$new_cens_event_gedrec .= $cens_OBJE_level." OBJE @".$cens_OBJE_id."@"."\n";
		$new_cens_event_gedrec .= $cens_NOTE_level." NOTE @".$cens_NOTE_id."@"."\n";

		// Append New Census event to gedrec ==================================== 
		$new_cens_event_gedrec_updated = $gedrec . $new_cens_event_gedrec . "\n";
		
		// DEBUG ================================================================
		/*
		echo "<br /><br />";
		echo "DEBUG -----------------------------------------------------------";
		echo "<br /><br />";
		// echo "CENS ADDR = ".$cens_addr;
		// echo "<br />";
		// echo "Locality = ".$s_addr;
		// echo "<br />";
		// echo "CENS Date = ".$c_yr;
		// echo "<br />";
		// echo "SOUR = ".$cens_sour_id;
		// echo "<br />";
		// echo "SOUR Citation = ".$cens_sour_citation;
		// echo "<br />";
		// echo "MEDIA ID = ".$cens_obje_id;
		// echo "<br /><br />";
		echo "<b>NEW CENS EVENT - APPENDED GEDREC:</b>".str_replace("\n", "<br />", $new_cens_event_gedrec);
		echo "<br />";
		echo "END DEBUG -------------------------------------------------------";
		echo "<br /><br />";
		echo "<br /><br />";
		echo "<br /><br />";
		*/
		// ======================================================================

		// Replace Indi GEedrec with newly added Census event =================== 
		$success = replace_gedrec($pid, $new_cens_event_gedrec_updated);

	} // end foreach $cens_pids  -------------
}
// End Part (2) ===========================================================================



?>
