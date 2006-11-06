<?php
/**
 * Various functions used by the Edit interface
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  John Finlay and Others
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
 * @see functions_places.php
 * @version $Id$
 */

if (strstr($_SERVER["SCRIPT_NAME"],"functions")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}

/**
 * The DEBUG variable allows you to turn on debugging
 * which will write all communication output to the pgv log files
 * in the index directory and print other information to the screen.
 * Set this to true to enable debugging,
 * but be sure to set it back to false when you are done debugging.
 * @global boolean $DEBUG
 */
$DEBUG = false;

$NPFX_accept = array("Adm", "Amb", "Brig", "Can", "Capt", "Chan", "Chapln", "Cmdr", "Col", "Cpl", "Cpt", "Dr", "Gen", "Gov", "Hon", "Lady", "Lt", "Mr", "Mrs", "Ms", "Msgr", "Pfc", "Pres", "Prof", "Pvt", "Rep", "Rev", "Sen", "Sgt", "Sir", "Sr", "Sra", "Srta", "Ven");
$SPFX_accept = array("al", "da", "de", "den", "dem", "der", "di", "du", "el", "la", "van", "von");
$NSFX_accept = array("Jr", "Sr", "I", "II", "III", "IV", "MD", "PhD");
$FILE_FORM_accept = array("avi", "bmp", "gif", "jpeg", "mp3", "ole", "pcx", "png", "tiff", "wav");
$emptyfacts = array("_HOL", "_NMR", "_SEPR", "ADOP", "ANUL", "BAPL", "BAPM", "BARM", "BASM",
"BIRT", "BLES", "BURI", "CENS", "CHAN", "CHR", "CHRA", "CONF", "CONL", "CREM",
"DATA", "DEAT", "DIV", "DIVF", "EMIG", "ENDL", "ENGA", "EVEN", "FCOM", "GRAD",
"HUSB", "IMMI", "MAP", "MARB", "MARC", "MARL", "MARR", "MARS", "NATU", "ORDN",
"PROB", "RESI", "RETI", "SLGC", "SLGS", "WIFE", "WILL");
$templefacts = array("SLGC","SLGS","BAPL","ENDL","CONL");
$nonplacfacts = array("ENDL","NCHI","SLGC","SLGS");
$nondatefacts = array("ABBR","ADDR","AFN","AUTH","EMAIL","FAX","NAME","NCHI","NOTE","OBJE",
"PHON","PUBL","REFN","REPO","SEX","SOUR","SSN","TEXT","TITL","WWW","_EMAIL");
$typefacts = array();	//-- special facts that go on 2 TYPE lines

// Next two vars used by insert_missing_subtags()
$date_and_time=array("BIRT","DEAT"); // Tags with date and time
$level2_tags=array( // The order of the $keys is significant
	"TEMP" =>array("BAPL","CONL","ENDL","SLGC","SLGS"),
	"STAT" =>array("BAPL","CONL","ENDL","SLGC","SLGS"),
	"_HEB" =>array("NAME","TITL"),
	"ROMN" =>array("NAME","TITL"),
	"TYPE" =>array("GRAD","EVEN","FACT","IDNO","MARR","ORDN"),
	"AGNC" =>array("EDUC","GRAD","OCCU","RETI","ORDN"),
	"CAUS" =>array("DEAT"),
	"CALN" =>array("REPO"),
	"CEME" =>array("BURI"), // CEME is NOT a valid 5.5.1 tag; use _CEME ??
	"DATE" =>array("ANUL","CENS","DIV","DIVF","ENGA","MARB","MARC","MARR","MARL", "MARS","RESI","EVEN","EDUC","OCCU","PROP","RELI","RESI","BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","EVEN"),
	"PLAC" =>array("ANUL","CENS","DIV","DIVF","ENGA","MARB","MARC","MARR","MARL", "MARS","RESI","EVEN","EDUC","OCCU","PROP","RELI","RESI","BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","EVEN"),
	"ADDR" =>array("BIRT","CHR","CHRA","DEAT","CREM","BURI","MARR","CENS","EDUC","GRAD","OCCU","PROP","ORDN","RESI","EVEN"),
	"PHON" =>array("OCCU","RESI"),
	"FAX"  =>array("OCCU","RESI"),
	"URL"  =>array("OCCU","RESI"),
	"EMAIL"=>array("OCCU","RESI"),
	"AGE"  =>array("CENS","DEAT"),
	"HUSB" =>array("MARR"),
	"WIFE" =>array("MARR"),
	"FAMC" =>array("ADOP"),
	"FILE" =>array("OBJE"),
	"_PRIM"=>array("OBJE"),
);
//-------------------------------------------- newConnection
//-- this function creates a new unique connection
//-- and adds it to the connections file
//-- it returns the connection identifier
function newConnection() {
	return session_name()."\t".session_id()."\n";
}

//-------------------------------------------- get_next_record
//-- gets the next person in the gedcom, if we reach the end then
//-- returns false
function get_next_xref($gid, $type='INDI') {
	global $GEDCOM, $myindilist, $pgv_changes;

	if (!isset($myindilist[$gid])) {
		print "ERROR 4: Could not find gedcom record with xref:$gid\n";
		AddToChangeLog("ERROR 4: Could not find gedcom record with xref:$gid ->" . getUserName() ."<-");
		return false;
	}
	$found = false;
	foreach($myindilist as $key=>$value) {
		if ($found) {
			return $key;
		}
		if ($key==$gid) $found=true;
	}
	//print "ERROR 14: Reached the end of the list\n";
	return "";
}

//-------------------------------------------- get_prev_record
//-- gets the previous person in the gedcom, if we reach the start then
//-- returns the last record
function get_prev_xref($gid, $type='INDI') {
	global $GEDCOM, $myindilist, $pgv_changes;

	if (!isset($myindilist[$gid])) {
		print "ERROR 4: Could not find gedcom record with xref:$gid\n";
		AddToChangeLog("ERROR 4: Could not find gedcom record with xref:$gid ->" . getUserName() ."<-");
		return false;
	}
	$found = false;
	$prevkey = "";
	foreach($myindilist as $key=>$value) {
		if ($key==$gid) $found=true;
		if ($found) {
			if (isset($prev)) {
				return $prevkey;
			}
			else {
				//print "ERROR 15: Reached the beginning of the list\n";
				return "";
			}
		}
		$prev = $value;
		$prevkey = $key;
	}
	//print "ERROR 14: Reached the end of the list\n";
	return "";
}

//-------------------------------------------- replace_gedrec
/**
 * This function will replace a gedcom record with
 * the id $gid with the $gedrec
 * @param string $gid	The XREF id of the record to replace
 * @param string $gedrec	The new gedcom record to replace with
 * @param boolean $chan		Whether or not to update/add the CHAN record
 * @param string $linkpid	Tells whether or not this record change is linked with the record change of another record identified by $linkpid
 */
function replace_gedrec($gid, $gedrec, $chan=true, $linkpid='') {
	global $fcontents, $GEDCOM, $pgv_changes, $manual_save, $pgv_private_records;

	$gid = strtoupper($gid);
	//-- restore any data that was hidden during privatizing
	if (isset($pgv_private_records[$gid])) $gedrec = trim($gedrec)."\r\n".trim(get_last_private_data($gid));

	if (($gedrec = check_gedcom($gedrec, $chan))!==false) {
		//-- the following block of code checks if the XREF was changed in this record.
		//-- if it was changed we add a warning to the change log
		$ct = preg_match("/0 @(.*)@/", $gedrec, $match);
		if ($ct>0) {
			$oldgid = $gid;
			$gid = trim($match[1]);
			if ($oldgid!=$gid) {
				if ($gid=="REF" || $gid=="new" || $gid=="NEW") {
					$gedrec = preg_replace("/0 @(.*)@/", "0 @".$oldgid."@", $gedrec);
					$gid = $oldgid;
				}
				else {
					AddToChangeLog("Warning: $oldgid was changed to $gid");
					if (isset($pgv_changes[$oldgid."_".$GEDCOM])) unset($pgv_changes[$oldgid."_".$GEDCOM]);
				}
			}
		}
		if (userAutoAccept()) {
			require_once("includes/functions_import.php");
			update_record($gedrec);
		}
		else {
			$change = array();
			$change["gid"] = $gid;
			$change["gedcom"] = $GEDCOM;
			$change["type"] = "replace";
			$change["status"] = "submitted";
			$change["user"] = getUserName();
			$change["time"] = time();
			if (!empty($linkpid)) $change["linkpid"] = $linkpid;
			$change["undo"] = $gedrec;
			if (!isset($pgv_changes[$gid."_".$GEDCOM])) $pgv_changes[$gid."_".$GEDCOM] = array();
			$pgv_changes[$gid."_".$GEDCOM][] = $change;
			write_changes();
		}
			AddToChangeLog("Replacing gedcom record $gid ->" . getUserName() ."<-");
		return true;
	}
	return false;
}

//-------------------------------------------- append_gedrec
//-- this function will append a new gedcom record at
//-- the end of the gedcom file.
function append_gedrec($gedrec, $chan=true, $linkpid='') {
	global $fcontents, $GEDCOM, $pgv_changes, $manual_save;

	if (($gedrec = check_gedcom($gedrec, $chan))!==false) {
		$ct = preg_match("/0 @(.*)@ (.*)/", $gedrec, $match);
		$gid = $match[1];
		$type = trim($match[2]);

		if (preg_match("/\d+/", $gid)==0) $xref = get_new_xref($type);
		else $xref = $gid;
		$gedrec = preg_replace("/0 @(.*)@/", "0 @$xref@", $gedrec);
		if (userAutoAccept()) {
			require_once("includes/functions_import.php");
			update_record($gedrec);
		}
		else {
			$change = array();
			$change["gid"] = $xref;
			$change["gedcom"] = $GEDCOM;
			$change["type"] = "append";
			$change["status"] = "submitted";
			$change["user"] = getUserName();
			$change["time"] = time();
			if (!empty($linkpid)) $change["linkpid"] = $linkpid;
			$change["undo"] = $gedrec;
			if (!isset($pgv_changes[$xref."_".$GEDCOM])) $pgv_changes[$xref."_".$GEDCOM] = array();
			$pgv_changes[$xref."_".$GEDCOM][] = $change;
			write_changes();
		}
		AddToChangeLog("Appending new $type record $xref ->" . getUserName() ."<-");
		return $xref;
	}
	return false;
}

//-------------------------------------------- delete_gedrec
//-- this function will delete the gedcom record with
//-- the given $gid
function delete_gedrec($gid, $linkpid='') {
	global $fcontents, $GEDCOM, $pgv_changes, $manual_save;

		//-- first check if the record is not already deleted
		if (isset($pgv_changes[$gid."_".$GEDCOM])) {
			$change = end($pgv_changes[$gid."_".$GEDCOM]);
			if ($change["type"]=="delete") return true;
		}

	$undo = find_gedcom_record($gid);
	if (empty($undo)) return false;
	if (userAutoAccept()) {
		require_once("includes/functions_import.php");
		update_record($undo, true);
	}
	else {
		$change = array();
		$change["gid"] = $gid;
		$change["gedcom"] = $GEDCOM;
		$change["type"] = "delete";
		$change["status"] = "submitted";
		$change["user"] = getUserName();
		$change["time"] = time();
		if (!empty($linkpid)) $change["linkpid"] = $linkpid;
		$change["undo"] = "";
		if (!isset($pgv_changes[$gid."_".$GEDCOM])) $pgv_changes[$gid."_".$GEDCOM] = array();
		$pgv_changes[$gid."_".$GEDCOM][] = $change;
		write_changes();
	}
	AddToChangeLog("Deleting gedcom record $gid ->" . getUserName() ."<-");
	return true;
}

//-------------------------------------------- check_gedcom
//-- this function will check a GEDCOM record for valid gedcom format
function check_gedcom($gedrec, $chan=true) {
	global $pgv_lang, $DEBUG, $USE_RTL_FUNCTIONS;

	$gedrec = trim(stripslashes($gedrec));

	if ($USE_RTL_FUNCTIONS) {
		//-- replace any added ltr processing codes
//		$gedrec = preg_replace(array("/".html_entity_decode("&rlm;",ENT_COMPAT,"UTF-8")."/", "/".html_entity_decode("&lrm;",ENT_COMPAT,"UTF-8")."/"), array("",""), $gedrec);
		// Because of a bug in PHP 4, the above generates a run-time error message and does nothing.
		// see:  http://bugs.php.net/bug.php?id=25670
		// HTML entity &rlm; is the 3-byte UTF8 character 0xE2808F
		// HTML entity &lrm; is the 3-byte UTF8 character 0xE2808E
		$gedrec = str_replace(array(chr(0xE2).chr(0x80).chr(0x8F), chr(0xE2).chr(0x80).chr(0x8E)), "", $gedrec);
	}
	$ct = preg_match("/0 @(.*)@ (.*)/", $gedrec, $match);
	if ($ct==0) {
		print "ERROR 20: Invalid GEDCOM 5.5 format.\n";
		AddToChangeLog("ERROR 20: Invalid GEDCOM 5.5 format.->" . getUserName() ."<-");
		if ($GLOBALS["DEBUG"]) {
			print "<pre>$gedrec</pre>\n";
			print debug_print_backtrace();
		}
		return false;
	}
	$gedrec = trim($gedrec);
	if ($chan) {
		$pos1 = strpos($gedrec, "1 CHAN");
		if ($pos1!==false) {
			$pos2 = strpos($gedrec, "\n1", $pos1+4);
			if ($pos2===false) $pos2 = strlen($gedrec);
			$newgedrec = substr($gedrec, 0, $pos1);
			$newgedrec .= "1 CHAN\r\n2 DATE ".strtoupper(date("d M Y"))."\r\n";
			$newgedrec .= "3 TIME ".date("H:i:s")."\r\n";
			$newgedrec .= "2 _PGVU ".getUserName()."\r\n";
			$newgedrec .= substr($gedrec, $pos2);
			$gedrec = $newgedrec;
		}
		else {
			$newgedrec = "\r\n1 CHAN\r\n2 DATE ".strtoupper(date("d M Y"))."\r\n";
			$newgedrec .= "3 TIME ".date("H:i:s")."\r\n";
			$newgedrec .= "2 _PGVU ".getUserName();
			$gedrec .= $newgedrec;
		}
	}
	$gedrec = preg_replace('/\\\+/', "\\", $gedrec);

	//-- remove any empty lines
	$lines = preg_split("/\r?\n/", $gedrec);
	$newrec = "";
	foreach($lines as $ind=>$line) {
		//-- remove any whitespace
		$line = trim($line);
		if (!empty($line)) $newrec .= $line."\r\n";
	}

	return $newrec;
}

/**
 * Undo a change
 * this function will undo a change in the gedcom file
 * @param string $cid	the change id of the form gid_gedcom
 * @param int $index	the index of the change to undo
 * @return boolean	true if undo successful
 */
function undo_change($cid, $index) {
	global $fcontents, $pgv_changes, $GEDCOMS, $GEDCOM, $manual_save;

	if (isset($pgv_changes[$cid])) {
		$changes = $pgv_changes[$cid];
		$change = $changes[$index];
		if ($GEDCOM != $change["gedcom"]) {
			$GEDCOM = $change["gedcom"];
		}
		/*
		if ($change["type"]=="delete") {
			$pos1 = strrpos($fcontents, "0");
			$fcontents = substr($fcontents, 0, $pos1).trim($change["undo"])."\r\n".substr($fcontents, $pos1);
		}
		else if ($change["type"]=="append") {
			$pos1 = strpos($fcontents, "0 @".$change["gid"]."@");
			if ($pos1===false) {
				print "ERROR 4: Could not find gedcom record with gid:".$change["gid"]."\n";
				AddToChangeLog("ERROR 4: Could not find gedcom record with gid:".$change["gid"]." ->" . getUserName() ."<-");
				return false;
			}
			$pos2 = strpos($fcontents, "\n0", $pos1+1);
			if ($pos2===false) $pos2=strpos($fcontents, "0 TRLR", $pos1+1);
			else $pos2++;
			if ($pos2!==false) $fcontents = substr($fcontents, 0,$pos1).substr($fcontents, $pos2);
		}
		else if ($change["type"]=="replace") {
			$pos1 = strpos($fcontents, "0 @".$change["gid"]."@");
			if ($pos1===false) {
				$ct = preg_match("/0 @(.*)@/", $change["undo"], $match);
				if ($ct>0) {
					$gid = trim($match[1]);
					$pos1 = strpos($fcontents, "0 @".$gid."@");
				}
			}
			if ($pos1===false) {
				//print "ERROR 4: Could not find gedcom record with gid:".$change["gid"]."\n";
				//return false;
				if (!empty($change["undo"])) {
					$fcontents .= "\r\n".$change["undo"];
				}
			}
			else {
				$pos2 = strpos($fcontents, "\n0", $pos1+1);
				if ($pos2===false) $pos2=strpos($fcontents, "0 TRLR", $pos1+1);
				else $pos2++;
				$fcontents = substr($fcontents, 0,$pos1).trim($change["undo"])."\r\n".substr($fcontents, $pos2);
			}
		}
		*/
		if ($index==0) unset($pgv_changes[$cid]);
		else {
			for($i=$index; $i<count($pgv_changes[$cid]); $i++) {
				unset($pgv_changes[$cid][$i]);
			}
			if (count($pgv_changes[$cid])==0) unset($pgv_changes[$cid]);
		}
		AddToChangeLog("Undoing change $cid - $index ".$change["type"]." ->" . getUserName() ."<-");
		if (!isset($manual_save) || $manual_save==false) write_changes();
		return true;
	}
	return false;
}

/**
 * prints a form to add an individual or edit an individual's name
 *
 * @param string $nextaction	the next action the edit_interface.php file should take after the form is submitted
 * @param string $famid			the family that the new person should be added to
 * @param string $namerec		the name subrecord when editing a name
 * @param string $famtag		how the new person is added to the family
 */
function print_indi_form($nextaction, $famid, $linenum="", $namerec="", $famtag="CHIL", $sextag="") {
	global $pgv_lang, $factarray, $pid, $PGV_IMAGE_DIR, $PGV_IMAGES, $monthtonum, $WORD_WRAPPED_NOTES;
	global $NPFX_accept, $SPFX_accept, $NSFX_accept, $FILE_FORM_accept, $USE_RTL_FUNCTIONS, $GEDCOM;
	global $bdm, $TEXT_DIRECTION;

	$bdm = ""; // used to copy '1 SOUR' to '2 SOUR' for BIRT DEAT MARR
	init_calendar_popup();
	print "<form method=\"post\" name=\"addchildform\" onsubmit=\"return checkform();\">\n";
	print "<input type=\"hidden\" name=\"action\" value=\"$nextaction\" />\n";
	print "<input type=\"hidden\" name=\"linenum\" value=\"$linenum\" />\n";
	print "<input type=\"hidden\" name=\"famid\" value=\"$famid\" />\n";
	print "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />\n";
	print "<input type=\"hidden\" name=\"famtag\" value=\"$famtag\" />\n";

	print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" /><br />\n";

	print "<table class=\"facts_table\">";

	// preset child/father SURN
	$surn = "";
	if (empty($namerec)) {
		$indirec = "";
		if ($famtag=="CHIL" and $nextaction=="addchildaction") {
			if (isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_updated_record($famid);
			else $famrec = find_family_record($famid);
			if (empty($famrec)) $famrec = find_record_in_file($famid);
			$parents = find_parents_in_record($famrec);
			$indirec = find_person_record($parents["HUSB"]);
		}
		if ($famtag=="HUSB" and $nextaction=="addnewparentaction") {
			$indirec = find_person_record($pid);
		}
		$nt = preg_match("/\d SURN (.*)/", $indirec, $ntmatch);
		if ($nt) $surn = $ntmatch[1];
		else {
			$nt = preg_match("/1 NAME (.*)[\/](.*)[\/]/", $indirec, $ntmatch);
			if ($nt) $surn = $ntmatch[2];
		}
		if ($surn) $namerec = "1 NAME  /".trim($surn,"\r\n")."/";
	}
	// TODO make these tags a configuration setting
	$default_name_fields = array("NPFX"=>"","NAME"=>"","GIVN"=>"","SPFX"=>"","SURN"=>"","NSFX"=>"");
	$advanced_name_fields = array("NICK"=>"", "_MARNM"=>"", "ROMN"=>"");
	//-- if they are using an RTL language then they probably want _HEB by default
	if ($TEXT_DIRECTION=="rtl")  $default_name_fields["_HEB"] = "";
	else $advanced_name_fields["_HEB"] = "";
	
	//-- iterate over the name tags and find the values from the name record.
	foreach($default_name_fields as $tag=>$value) {
		$value = get_gedcom_value($tag, 0, $namerec);
	// handle PAF extra NPFX [ 961860 ]
		if ($tag=="NAME") {
	// 1 NAME = NPFX GIVN /SURN/ NSFX
			if (!empty($default_name_fields["NPFX"]) && strpos($value, $default_name_fields["NPFX"])===false) 
					$value = $default_name_fields." ".$value;
		}
		$default_name_fields[$tag]= $value;
		add_simple_tag("0 ".$tag." ".$value);
	}
	//-- advanced name fields
	print "<tr id=\"advanced_name\"><td class=\"descriptionbox\" colspan=\"2\">";
	print "<a href=\"javascript:;\" onclick=\"toggleAdvancedName(); return false;\"><img id=\"advanced_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /> ".$pgv_lang["advanced_name_fields"]."</a>";
	print "</td></tr>\n";
	foreach($advanced_name_fields as $tag=>$value) {
		$value = get_gedcom_value($tag, 0, $namerec);
		$default_name_fields[$tag]= $value;
		add_simple_tag("0 ".$tag." ".$value, "", "", "", "", false);
	}

	if ($surn) $namerec = ""; // reset if modified

	if (empty($namerec)) {
		// 2 _MARNM -- handled by advanced name fields
		// add_simple_tag("0 _MARNM");
		// 1 SEX
		if ($famtag=="HUSB" or $sextag=="M") add_simple_tag("0 SEX M");
		else if ($famtag=="WIFE" or $sextag=="F") add_simple_tag("0 SEX F");
		else add_simple_tag("0 SEX");
		// 1 BIRT
		// 2 DATE
		// 2 PLAC
		// 3 MAP
		// 4 LATI
		// 4 LONG
		add_simple_tag("0 BIRT");
		add_simple_tag("0 DATE", "BIRT");
		add_simple_tag("0 PLAC", "BIRT");
		add_simple_tag("0 MAP", "BIRT");
		add_simple_tag("0 LATI", "BIRT");
		add_simple_tag("0 LONG", "BIRT");
		// 1 DEAT
		// 2 DATE
		// 2 PLAC
		// 3 MAP
		// 4 LATI
		// 4 LONG
		add_simple_tag("0 DEAT");
		add_simple_tag("0 DATE", "DEAT");
		add_simple_tag("0 PLAC", "DEAT");
		add_simple_tag("0 MAP", "DEAT");
		add_simple_tag("0 LATI", "DEAT");
		add_simple_tag("0 LONG", "DEAT");
		$bdm = "BD";
		print "</table>\n";
		//-- if adding a spouse add the option to add a marriage fact to the new family
		if ($nextaction=='addspouseaction' || ($nextaction=='addnewparentaction' && $famid!='new')) {
			print "<br />\n";
			print "<table class=\"facts_table\">";
			// 1 MARR
			// 2 DATE
			// 2 PLAC
			// 3 MAP
			// 4 LATI
			// 4 LONG
			add_simple_tag("0 MARR");
			add_simple_tag("0 DATE", "MARR");
			add_simple_tag("0 PLAC", "MARR");
			add_simple_tag("0 MAP", "MARR");
			add_simple_tag("0 LATI", "MARR");
			add_simple_tag("0 LONG", "MARR");
			$bdm .= "M";
			print "</table>\n";
		}
		print_add_layer("SOUR", 1);
		print_add_layer("NOTE", 1);
		print_add_layer("OBJE", 1);
		print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" /><br />\n";
	}
	else {
		if ($namerec!="NEW") {
			$gedlines = split("\n", $namerec);	// -- find the number of lines in the record
			$fields = preg_split("/\s/", $gedlines[0]);
			$glevel = $fields[0];
			$level = $glevel;
			$type = trim($fields[1]);
			$level1type = $type;
			$tags=array();
			$i = 0;
			$namefacts = array("NPFX", "GIVN", "NICK", "SPFX", "SURN", "NSFX", "NAME", "_HEB", "ROMN", "_MARNM");
			do {
				if (!in_array($type, $namefacts)) {
					$text = "";
					for($j=2; $j<count($fields); $j++) {
						if ($j>2) $text .= " ";
						$text .= $fields[$j];
					}
					$iscont = false;
					while(($i+1<count($gedlines))&&(preg_match("/".($level+1)." (CON[CT])\s?(.*)/", $gedlines[$i+1], $cmatch)>0)) {
						$iscont=true;
						if ($cmatch[1]=="CONT") $text.="\r\n";
						if ($WORD_WRAPPED_NOTES) $text .= " ";
						$text .= $cmatch[2];
						$i++;
					}
					add_simple_tag($level." ".$type." ".$text);
				}
				$tags[]=$type;
				$i++;
				if (isset($gedlines[$i])) {
					$fields = preg_split("/\s/", $gedlines[$i]);
					$level = $fields[0];
					if (isset($fields[1])) $type = trim($fields[1]);
				}
			} while (($level>$glevel)&&($i<count($gedlines)));
		}
		// 2 _MARNM
//		add_simple_tag("0 _MARNM");
		print "</tr>\n";
		print "</table>\n";
		print_add_layer("SOUR");
		print_add_layer("NOTE");
		print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" /><br />\n";
	}
	print "</form>\n";
	?>
	<script type="text/javascript" src="autocomplete.js"></script>
	<script type="text/javascript">
	<!--
	//	copy php arrays into js arrays
	var npfx_accept = new Array(<?php foreach ($NPFX_accept as $indexval => $npfx) print "'".$npfx."',"; print "''";?>);
	var spfx_accept = new Array(<?php foreach ($SPFX_accept as $indexval => $spfx) print "'".$spfx."',"; print "''";?>);
	Array.prototype.in_array = function(val) {
		for (var i in this) {
			if (this[i] == val) return true;
		}
		return false;
	}
	function trim(str) {
		return str.replace(/(^\s*)|(\s*$)/g,'');
	}
	function updatewholename() {
		frm = document.forms[0];
		var npfx=trim(frm.NPFX.value);
		if (npfx) npfx+=" ";
		var givn=trim(frm.GIVN.value);
		var spfx=trim(frm.SPFX.value);
		if (spfx) spfx+=" ";
		var surn=trim(frm.SURN.value);
		var nsfx=trim(frm.NSFX.value);
		frm.NAME.value = npfx + givn + " /" + spfx + surn + "/ " + nsfx;
		frm.NAME.value = frm.NAME.value.replace(/,/g," ");
		frm.NAME.value = frm.NAME.value.replace(/  +/g," ");
	}
	
	function toggleAdvancedName() {
		var img = document.getElementById('advanced_img');
		if (img) {
			if (img.src.indexOf('plus')>=0) {
				img.src = '<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["minus"]["other"]; ?>';
				var disp = 'table-row';
			}
			else {
				img.src = '<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]; ?>';
				var disp = 'none';
			}
			<?php foreach($advanced_name_fields as $tag=>$value) { ?>
				document.getElementById("<?php print $tag; ?>_tr").style.display=disp;
			<?php } ?>
		}
	}
	
	function togglename() {
		frm = document.forms[0];

		// show/hide NAME
		var ronly = frm.NAME.readOnly;
		if (ronly) {
			updatewholename();
			frm.NAME.readOnly=false;
			if (frm.NAME_spec) frm.NAME_spec.style.display="inline";
			if (frm.NAME_plus) frm.NAME_plus.style.display="inline";
			if (frm.NAME_minus) frm.NAME_minus.style.display="none";
			disp="none";
		}
		else {
			// split NAME = (NPFX) GIVN / (SPFX) SURN / (NSFX)
			var name=frm.NAME.value+'//';
			var name_array=name.split("/");
			var givn=trim(name_array[0]);
			var givn_array=givn.split(" ");
			var surn=trim(name_array[1]);
			var surn_array=surn.split(" ");
			var nsfx=trim(name_array[2]);

			// NPFX
			var npfx='';
			do {
				search=givn_array[0]; // first word
				search=search.replace(/(\.*$)/g,''); // remove trailing '.'
				if (npfx_accept.in_array(search)) npfx+=givn_array.shift()+' ';
				else break;
			} while (givn_array.length>0);
			frm.NPFX.value=trim(npfx);

			// GIVN
			frm.GIVN.value=trim(givn_array.join(' '));

			// SPFX
			var spfx='';
			do {
				search=surn_array[0]; // first word
				search=search.replace(/(\.*$)/g,''); // remove trailing '.'
				if (spfx_accept.in_array(search)) spfx+=surn_array.shift()+' ';
				else break;
			} while (surn_array.length>0);
			frm.SPFX.value=trim(spfx);

			// SURN
			frm.SURN.value=trim(surn_array.join(' '));

			// NSFX
			frm.NSFX.value=trim(nsfx);

			// NAME
			frm.NAME.readOnly=true;
			if (frm.NAME_spec) frm.NAME_spec.style.display="none";
			if (frm.NAME_plus) frm.NAME_plus.style.display="none";
			if (frm.NAME_minus) frm.NAME_minus.style.display="inline";
			disp="table-row";
			if (document.all) disp="inline"; // IE
		}
		// show/hide
		document.getElementById("NPFX_tr").style.display=disp;
		document.getElementById("GIVN_tr").style.display=disp;
		// document.getElementById("NICK_tr").style.display=disp;
		document.getElementById("SPFX_tr").style.display=disp;
		document.getElementById("SURN_tr").style.display=disp;
		document.getElementById("NSFX_tr").style.display=disp;
	}
	function checkform() {
		frm = document.addchildform;
		var fname=frm.NAME.value;
		fname=fname.replace(/ /g,'');
		fname=fname.replace(/\//g,'');
		if (fname=="") {
			alert('<?php print $pgv_lang["must_provide"]; print " ".$factarray["NAME"]; ?>');
			frm.NAME.focus();
			return false;
		}
		return true;
	}
	//-->
	</script>
	<?php
	// force name expand on form load (maybe optional in a further release...)
	print "<script type='text/javascript'>togglename();</script>";
}

/**
 * generates javascript code for calendar popup in user's language
 *
 * @param string id		form text element id where to return date value
 * @param boolean $asString	Whether or not to return this text as a string or print it
 * @see init_calendar_popup()
 */
function print_calendar_popup($id, $asString=false) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;

	// calendar button
	$text = $pgv_lang["select_date"];
	if (isset($PGV_IMAGES["calendar"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["button"]."\" name=\"img".$id."\" id=\"img".$id."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = "";
	$out .= "<a href=\"javascript: ".$text."\" onclick=\"cal_toggleDate('caldiv".$id."', '".$id."'); return false;\">";
	$out .= $Link;
	$out .= "</a>\n";
	$out .= "<div id=\"caldiv".$id."\" style=\"position:absolute;visibility:hidden;background-color:white;layer-background-color:white;\"></div>\n";
	if ($asString) return $out;
	else print $out;
}
/**
 * @todo add comments
 */
function print_addnewrepository_link($element_id) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;

	$text = $pgv_lang["create_repository"];
	if (isset($PGV_IMAGES["addrepository"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["addrepository"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	print "&nbsp;&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"addnewrepository(document.getElementById('".$element_id."')); return false;\">";
	print $Link;
	print "</a>";
}

/**
 * @todo add comments
 */
function print_addnewsource_link($element_id) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;

	$text = $pgv_lang["create_source"];
	if (isset($PGV_IMAGES["addsource"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["addsource"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	print "&nbsp;&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"addnewsource(document.getElementById('".$element_id."')); return false;\">";
	print $Link;
	print "</a>";
}

/**
 * add a new tag input field
 *
 * called for each fact to be edited on a form.
 * Fact level=0 means a new empty form : data are POSTed by name
 * else data are POSTed using arrays :
 * glevels[] : tag level
 *  islink[] : tag is a link
 *     tag[] : tag name
 *    text[] : tag value
 *
 * @param string $tag			fact record to edit (eg 2 DATE xxxxx)
 * @param string $upperlevel	optional upper level tag (eg BIRT)
 * @param string $label			An optional label to print instead of the default from the $factarray
 * @param string $readOnly		optional, when "READONLY", fact data can't be changed
 * @param string $noClose		optional, when "NOCLOSE", final "</td></tr>" won't be printed
 *								(so that additional text can be printed in the box)
 * @param boolean $rowDisplay	True to have the row displayed by default, false to hide it by default
 */
function add_simple_tag($tag, $upperlevel="", $label="", $readOnly="", $noClose="", $rowDisplay=true) {
	global $factarray, $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $MEDIA_DIRECTORY, $TEMPLE_CODES;
	global $assorela, $tags, $emptyfacts, $TEXT_DIRECTION, $confighelpfile;
	global $NPFX_accept, $SPFX_accept, $NSFX_accept, $FILE_FORM_accept, $upload_count;
	global $tabkey, $STATUS_CODES, $REPO_ID_PREFIX, $SPLIT_PLACES, $pid, $linkToID;
	global $bdm;

	if (!isset($noClose) && isset($readOnly) && $readOnly=="NOCLOSE") {
		$noClose = "NOCLOSE";
		$readOnly = "";
	}

	if (!isset($noClose) || $noClose!="NOCLOSE") $noClose = "";
	if (!isset($readOnly) || $readOnly!="READONLY") $readOnly = "";

	if (!isset($tabkey)) $tabkey = 1;

	if (empty($linkToID)) $linkToID = $pid;

	$largetextfacts = array("TEXT","PUBL","NOTE");
	$subnamefacts = array("NPFX", "GIVN", "SPFX", "SURN", "NSFX");

	@list($level, $fact, $value) = explode(" ", $tag);

	if ($fact=="LATI" || $fact=="LONG") {
	?>
	<script type="text/javascript">
	<!--
	function valid_lati_long(field, pos, neg) {
		// valid LATI or LONG according to Gedcom standard
		// pos (+) : N or E
		// neg (-) : S or W
		txt=field.value.toUpperCase();
		txt=txt.replace(/(^\s*)|(\s*$)/g,''); // trim
		txt=txt.replace(/ /g,':'); // N12 34 ==> N12:34
		txt=txt.replace(/\+/g,''); // +17.1234 ==> 17.1234
		txt=txt.replace(/-/g,neg);	// -0.5698 ==> W0.5698
		txt=txt.replace(/,/g,'.');	// 0,5698 ==> 0.5698
		// 0°34'11 ==> 0:34:11
		txt=txt.replace(/\uB0/g,':'); // °
		txt=txt.replace(/\u27/g,':'); // '
		// 0:34:11.2W ==> W0.5698
		txt=txt.replace(/^([0-9]+):([0-9]+):([0-9.]+)(.*)/g, function($0, $1, $2, $3, $4) { var n=parseFloat($1); n+=($2/60); n+=($3/3600); n=Math.round(n*1E4)/1E4; return $4+n; });
		// 0:34W ==> W0.5667
		txt=txt.replace(/^([0-9]+):([0-9]+)(.*)/g, function($0, $1, $2, $3) { var n=parseFloat($1); n+=($2/60); n=Math.round(n*1E4)/1E4; return $3+n; });
		// 0.5698W ==> W0.5698
		txt=txt.replace(/(.*)([N|S|E|W]+)$/g,'$2$1');
		// 17.1234 ==> N17.1234
		if (txt!='' && txt.charAt(0)!=neg && txt.charAt(0)!=pos) txt=pos+txt;
		field.value = txt;
	}
	function toggle_lati_long() {
		tr = document.getElementsByTagName('tr');
		for (var i=0; i<tr.length; i++) {
			if (tr[i].id.indexOf("LATI")>=0 || tr[i].id.indexOf("LONG")>=0) {
				var disp = tr[i].style.display;
				if (disp=="none") {
					disp="table-row";
					if (document.all) disp="inline"; // IE
				}
				else disp="none";
				tr[i].style.display=disp;
			}
		}
	}
	//-->
	</script>
	<?php
	}

	// element name : used to POST data
	if ($level==0) {
		if ($upperlevel) $element_name=$upperlevel."_".$fact; // ex: BIRT_DATE | DEAT_DATE | ...
		else $element_name=$fact; // ex: OCCU
	} else $element_name="text[]";


	// element id : used by javascript functions
	if ($level==0) $element_id=$fact; // ex: NPFX | GIVN ...
	else $element_id=$fact.floor(microtime()*1000000); // ex: SOUR56402
	if ($upperlevel) $element_id=$upperlevel."_".$fact; // ex: BIRT_DATE | DEAT_DATE ...

	// field value
	$islink = (substr($value,0,1)=="@" and substr($value,0,2)!="@#");
	if ($islink) $value=trim($value, " @");
	else $value=trim(substr($tag, strlen($fact)+3));
	if ($fact=="REPO") $islink = true;
	if ($fact=="SOUR") $islink = true;
	if ($fact=="OBJE") $islink = true;
	if ($fact=="FAMC") $islink = true;

	// rows & cols
	$rows=1;
	$cols=40;
	if ($islink) $cols=10;
	if ($fact=="FORM") $cols=5;
	if ($fact=="DATE" or $fact=="TIME" or $fact=="TYPE") $cols=20;
	if ($fact=="LATI" or $fact=="LONG") $cols=12;
	if (in_array($fact, $subnamefacts)) $cols=25;
	if ($fact=="GIVN" or $fact=="SURN") $cols=25;
	if ($fact=="NPFX" or $fact=="SPFX" or $fact=="NSFX") $cols=12;
	if (in_array($fact, $largetextfacts)) { $rows=10; $cols=70; }
	if ($fact=="ADDR") $rows=4;
	if ($fact=="REPO") $cols = strlen($REPO_ID_PREFIX) + 4;

	// label
	$style="";
	print "<tr id=\"".$element_id."_tr\" ";
	if (!$rowDisplay || in_array($fact, $subnamefacts)) print " style=\"display:none;\""; // hide subname facts
	if ($fact=="MAP" || $fact=="LATI" || $fact=="LONG") print " style=\"display:none;\"";
	print " >\n";
	if (in_array($fact, $subnamefacts) || $fact=="LATI" || $fact=="LONG")
			print "<td class=\"optionbox $TEXT_DIRECTION wrap width25\">";
	else	print "<td class=\"descriptionbox $TEXT_DIRECTION wrap width25\">";

	// help link
	if (!in_array($fact, $emptyfacts)) {
		if ($fact=="DATE") print_help_link("def_gedcom_date_help", "qm", "date");
		else if ($fact=="RESN") print_help_link($fact."_help", "qm");
		else print_help_link("edit_".$fact."_help", "qm");
	}
	if ($GLOBALS["DEBUG"]) print $element_name."<br />\n";
	if (!empty($label)) print $label;
	else {
		if (isset($pgv_lang[$fact])) print $pgv_lang[$fact];
		else if (isset($factarray[$fact])) print $factarray[$fact];
		else print $fact;
	}
	print "\n";

	// tag level
	if ($level>0) {
		if ($fact=="TEXT" and $level>1) {
			print "<input type=\"hidden\" name=\"glevels[]\" value=\"".($level-1)."\" />";
			print "<input type=\"hidden\" name=\"islink[]\" value=\"0\" />";
			print "<input type=\"hidden\" name=\"tag[]\" value=\"DATA\" />";
			//-- leave data text[] value empty because the following TEXT line will
			//--- cause the DATA to be added
			print "<input type=\"hidden\" name=\"text[]\" value=\"\" />";
		}
		print "<input type=\"hidden\" name=\"glevels[]\" value=\"".$level."\" />\n";
		print "<input type=\"hidden\" name=\"islink[]\" value=\"".($islink)."\" />\n";
		print "<input type=\"hidden\" name=\"tag[]\" value=\"".$fact."\" />\n";
	}
	print "\n</td>";

	// value
	print "<td class=\"optionbox wrap\">\n";
	if ($GLOBALS["DEBUG"]) print $tag."<br />\n";

	// retrieve linked NOTE
	if ($fact=="NOTE" and $islink) {
		$noteid = $value;
		print "<input type=\"hidden\" name=\"text[]\" value=\"".$noteid."\" />\n";
		$noterec = find_gedcom_record($noteid);
		$n1match = array();
		$nt = preg_match("/0 @$value@ NOTE (.*)/", $noterec, $n1match);
		if ($nt!==false) $value=trim(strip_tags(@$n1match[1].get_cont(1, $noterec)));
		$element_name="NOTE[".$noteid."]";
	}

	if (in_array($fact, $emptyfacts)&& (empty($value) or $value=="y" or $value=="Y")) {
		$value = strtoupper($value);
		if ($fact=="BIRT" or $fact=="MARR") $value="Y"; // default YES
		else if ($level==1) $value="Y"; // default YES
		print "<input type=\"hidden\" id=\"".$element_id."\" name=\"".$element_name."\" value=\"".$value."\" />";
		if ($level<=1) {
			print "<input type=\"checkbox\" ";
			if ($value=="Y") print " checked=\"checked\"";
			print " onClick=\"if (this.checked) ".$element_id.".value='Y'; else ".$element_id.".value=''; \" />";
			print $pgv_lang["yes"];
		}
	}
	else if ($fact=="TEMP") {
		print "<select tabindex=\"".$tabkey."\" name=\"".$element_name."\" >\n";
		print "<option value=''>".$pgv_lang["no_temple"]."</option>\n";
		foreach($TEMPLE_CODES as $code=>$temple) {
			print "<option value=\"$code\"";
			if ($code==$value) print " selected=\"selected\"";
			print ">$temple</option>\n";
		}
		print "</select>\n";
	}
	else if ($fact=="ADOP") {
		print "<select tabindex=\"".$tabkey."\" name=\"".$element_name."\" >";
		foreach (array("BOTH"=>$factarray["HUSB"]."+".$factarray["WIFE"],
		               "HUSB"=>$factarray["HUSB"],
		               "WIFE"=>$factarray["WIFE"]) as $k=>$v) {
			print "<option value='$k'";
			if ($value==$k)
				print " selected";
			print ">$v</option>";
		}
		print "</select>\n";
	}
	else if ($fact=="STAT") {
		print "<select tabindex=\"".$tabkey."\" name=\"".$element_name."\" >\n";
		print "<option value=''>No special status</option>\n";
		foreach($STATUS_CODES as $code=>$status) {
			print "<option value=\"$code\"";
			if ($code==$value) print " selected=\"selected\"";
			print ">$status</option>\n";
		}
		print "</select>\n";
	}
	else if ($fact=="RELA") {
		$text=strtolower($value);
		// add current relationship if not found in default list
		if (!array_key_exists($text, $assorela)) $assorela[$text]=$text;
		print "<select tabindex=\"".$tabkey."\" id=\"".$element_id."\" name=\"".$element_name."\" >\n";
		foreach ($assorela as $key=>$value) {
			print "<option value=\"". $key . "\"";
			if ($key==$text) print " selected=\"selected\"";
			print ">" . $assorela["$key"] . "</option>\n";
		}
		print "</select>\n";
	}
	else if ($fact=="RESN") {
		?>
		<script type="text/javascript">
		<!--
		function update_RESN_img(resn_val) {
			document.getElementById("RESN_none").style.display="none";
			document.getElementById("RESN_locked").style.display="none";
			document.getElementById("RESN_privacy").style.display="none";
			document.getElementById("RESN_confidential").style.display="none";
			document.getElementById("RESN_"+resn_val).style.display="inline";
			if (resn_val=='none') resn_val='';
			document.getElementById("<?php print $element_id?>").value=resn_val;
		}
		//-->
		</script>
		<?php
		print "<input type=\"hidden\" id=\"".$element_id."\" name=\"".$element_name."\" />\n";
		print "<table><tr valign=\"top\">\n";
		foreach (array("none", "locked", "privacy", "confidential") as $resn_index => $resn_val) {
			if ($resn_val=="none") $resnv=""; else $resnv=$resn_val;
			print "<td><input tabindex=\"".$tabkey."\" type=\"radio\" name=\"RESN_radio\" onclick=\"update_RESN_img('".$resn_val."')\"";
			print " value=\"".$resnv."\"";
			if ($value==$resnv) print " checked=\"checked\"";
			print " /><small>".$pgv_lang[$resn_val]."</small>";
			print "<br />&nbsp;<img id=\"RESN_".$resn_val."\" src=\"images/RESN_".$resn_val.".gif\"  alt=\"".$pgv_lang[$resn_val]."\" title=\"".$pgv_lang[$resn_val]."\" border=\"0\"";
			if ($value==$resnv) print " style=\"display:inline\""; else print " style=\"display:none\"";
			print " /></td>\n";
		}
		print "</tr></table>\n";
	}
	else if ($fact=="_PRIM" or $fact=="_THUM") {
		print "<select tabindex=\"".$tabkey."\" id=\"".$element_id."\" name=\"".$element_name."\" >\n";
		print "<option value=\"\"></option>\n";
		print "<option value=\"Y\"";
		if ($value=="Y") print " selected=\"selected\"";
		print ">".$pgv_lang["yes"]."</option>\n";
		print "<option value=\"N\"";
		if ($value=="N") print " selected=\"selected\"";
		print ">".$pgv_lang["no"]."</option>\n";
		print "</select>\n";
	}
	else if ($fact=="SEX") {
		print "<select tabindex=\"".$tabkey."\" id=\"".$element_id."\" name=\"".$element_name."\">\n<option value=\"M\"";
		if ($value=="M") print " selected=\"selected\"";
		print ">".$pgv_lang["male"]."</option>\n<option value=\"F\"";
		if ($value=="F") print " selected=\"selected\"";
		print ">".$pgv_lang["female"]."</option>\n<option value=\"U\"";
		if ($value=="U" || empty($value)) print " selected=\"selected\"";
		print ">".$pgv_lang["unknown"]."</option>\n</select>\n";
	}
	else if ($fact == "TYPE" && $level == '3') {
		//-- Build array of currently defined values for this Media Fact
		foreach ($pgv_lang as $varname => $typeValue) {
			if (substr($varname, 0, 6) == "TYPE__") {
				$type[strtolower(substr($varname, 6))] = $typeValue;
			}
		}
		//-- Sort the array into a meaningful order
		array_flip($type);
		asort($type);
		array_flip($type);
		//-- Build the selector for the Media "TYPE" Fact
		print "<select name=\"text[]\">";
		print "<option selected=\"selected\" value=\"\"> ".$pgv_lang["choose"]." </option>";
		$selectedValue = strtolower($value);
		foreach ($type as $typeName => $typeValue) {
			print "<option value=\"".$typeName."\"";
			if ($selectedValue == $typeName) print "selected=\"selected\"";
			print "> ".$typeValue." </option>";
		}
		print "</select>";
	}
	else {
		// textarea
		if ($rows>1) print "<textarea tabindex=\"".$tabkey."\" id=\"".$element_id."\" name=\"".$element_name."\" rows=\"".$rows."\" cols=\"".$cols."\">".PrintReady(htmlspecialchars($value))."</textarea><br />\n";
		// text
		else {
			print "<input tabindex=\"".$tabkey."\" type=\"text\" id=\"".$element_id."\" name=\"".$element_name."\" value=\"".PrintReady(htmlspecialchars($value))."\" size=\"".$cols."\" dir=\"ltr\"";
			if ($fact=="NPFX") print " onkeyup=\"wactjavascript_autoComplete(npfx_accept,this,event)\" autocomplete=\"off\" ";
			if (in_array($fact, $subnamefacts)) print " onblur=\"updatewholename();\" onmouseout=\"updatewholename();\"";
			if ($fact=="DATE") print " onblur=\"valid_date(this);\" onmouseout=\"valid_date(this);\"";
			if ($fact=="LATI") print " onblur=\"valid_lati_long(this, 'N', 'S');\" onmouseout=\"valid_lati_long(this, 'N', 'S');\"";
			if ($fact=="LONG") print " onblur=\"valid_lati_long(this, 'E', 'W');\" onmouseout=\"valid_lati_long(this, 'E', 'W');\"";
			//if ($fact=="FILE") print " onchange=\"if (updateFormat) updateFormat(this.value);\"";
			print " ".$readOnly." />\n";
		}
		// split PLAC
		if ($fact=="PLAC" && $readOnly=="") {
			print "<div id=\"".$element_id."_pop\" style=\"display: inline;\">\n";
			print_specialchar_link($element_id, false);
			print_findplace_link($element_id);
			print "</div>\n";
			print "<a href=\"javascript:;\" onclick=\"toggle_lati_long();\"><img src=\"images/buttons/target.gif\" border=\"0\" align=\"middle\" alt=\"".$factarray["LATI"]." / ".$factarray["LONG"]."\" title=\"".$factarray["LATI"]." / ".$factarray["LONG"]."\" /></a>";
			if ($SPLIT_PLACES) {
				if (!function_exists("print_place_subfields")) require("includes/functions_places.php");
				print_place_subfields($element_id);
			}
		}
		else if ($cols>20 and $fact!="NPFX" && $readOnly=="") print_specialchar_link($element_id, false);
	}
	// MARRiage TYPE : hide text field and show a selection list
	if ($fact=="TYPE" and $tags[0]=="MARR") {
		print "<script type='text/javascript'>";
		print "document.getElementById('".$element_id."').style.display='none'";
		print "</script>";
		print "<select tabindex=\"".$tabkey."\" id=\"".$element_id."_sel\" onchange=\"document.getElementById('".$element_id."').value=this.value;\" >\n";
		foreach (array("Unknown", "Civil", "Religious", "Partners") as $indexval => $key) {
			if ($key=="Unknown") print "<option value=\"\"";
			else print "<option value=\"".$key."\"";
			$a=strtolower($key);
			$b=strtolower($value);
			if (@strpos($a, $b)!==false or @strpos($b, $a)!==false) print " selected=\"selected\"";
			print ">".$factarray["MARR_".strtoupper($key)]."</option>\n";
		}
		print "</select>";
	}

	// popup links
	if ($readOnly=="") {
		if ($fact=="DATE") print_calendar_popup($element_id);
		if ($fact=="FAMC") print_findfamily_link($element_id, "");
		if ($fact=="FAMS") print_findfamily_link($element_id, "");
		if ($fact=="ASSO") print_findindi_link($element_id, get_person_name($value));
		if ($fact=="FILE") print_findmedia_link($element_id, "0file");
		if ($fact=="SOUR") {
			print_findsource_link($element_id);
			print_addnewsource_link($element_id);
			//print_autopaste_link($element_id, array("S1", "S2"), false, false, true);
			//-- checkboxes to apply '1 SOUR' to BIRT/MARR/DEAT as '2 SOUR'
			if ($level==1) {
				echo "<br />";
				echo "<input type=\"hidden\" id=\"SOUR_BIRT\" name=\"SOUR_BIRT\" value=\"Y\" />";
				echo "<input type=\"hidden\" id=\"SOUR_MARR\" name=\"SOUR_MARR\" value=\"Y\" />";
				echo "<input type=\"hidden\" id=\"SOUR_DEAT\" name=\"SOUR_DEAT\" value=\"Y\" />";
				if (strpos($bdm, "B")!==false) {
					echo "&nbsp;<input type=\"checkbox\" checked=\"checked\" onclick=\"if (this.checked) SOUR_BIRT.value='Y'; else SOUR_BIRT.value='';\" />";
					echo $factarray["BIRT"];
				}
				if (strpos($bdm, "M")!==false) {
					echo "&nbsp;<input type=\"checkbox\" checked=\"checked\" onclick=\"if (this.checked) SOUR_MARR.value='Y'; else SOUR_MARR.value='';\" />";
					echo $factarray["MARR"];
				}
				if (strpos($bdm, "D")!==false) {
					echo "&nbsp;<input type=\"checkbox\" checked=\"checked\" onclick=\"if (this.checked) SOUR_DEAT.value='Y'; else SOUR_DEAT.value='';\" />";
					echo $factarray["DEAT"];
				}
			}
		}
		if ($fact=="REPO") {
			print_findrepository_link($element_id);
			print_addnewrepository_link($element_id);
		}
		if ($fact=="OBJE") print_findmedia_link($element_id, "1media");
		if ($fact=="OBJE" && !$value) {
			print '<br /><a href="javascript:;" onclick="pastefield=document.getElementById(\''.$element_id.'\'); window.open(\'addmedia.php?action=showmediaform&amp;linktoid='.$linkToID.'&amp;level='.$level.'\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang["add_media"].'</a>';
			$value = "new";
		}
	}

	// current value
	if ($TEXT_DIRECTION=="ltr") {
		if ($fact=="DATE") print get_changed_date($value);
		if ($fact=="ASSO" and $value) print " ".PrintReady(get_person_name($value))." (".$value.")";
		if ($fact=="SOUR" and $value) print " ".PrintReady(get_source_descriptor($value))." (".$value.")";
	} else {
		if ($fact=="DATE") print "&rlm;".get_changed_date($value)."&rlm;";
		if ($fact=="ASSO" and $value) print " &rlm;".PrintReady(get_person_name($value))." (".$value.")&rlm;";
		if ($fact=="SOUR" and $value) print " &rlm;".PrintReady(get_source_descriptor($value))."&rlm;&nbsp;&nbsp;&lrm(".$value.")&lrm;";
	}

	// pastable values
	if ($readOnly=="") {
		if ($fact=="NPFX") {
			$text = $pgv_lang["autocomplete"];
			if (isset($PGV_IMAGES["autocomplete"]["button"])) $Link = "<img id=\"".$element_id."_spec\" name=\"".$element_id."_spec\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["autocomplete"]["button"]."\"  alt=\"".$text."\"  title=\"".$text."\" border=\"0\" align=\"middle\" />";
			else $Link = $text;
			print "&nbsp;".$Link;
		}
		if ($fact=="SPFX") print_autopaste_link($element_id, $SPFX_accept);
		if ($fact=="NSFX") print_autopaste_link($element_id, $NSFX_accept);
		if ($fact=="FORM") print_autopaste_link($element_id, $FILE_FORM_accept, false, false);

		// split NAME
		// Do this only for real names.  REPO uses "NAME" instead of "TITL".
		if ($fact=="NAME" && $upperlevel!="REPO") {
			print "&nbsp;<a href=\"javascript: ".$pgv_lang["show_details"]."\" onclick=\"togglename(); return false;\"><img id=\"".$element_id."_plus\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /></a>\n";
			print "<a href=\"javascript: ".$pgv_lang["show_details"]."\" onclick=\"togglename(); return false;\"><img style=\"display:none;\" id=\"".$element_id."_minus\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["minus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /></a>\n";
		}
	}

	if ($noClose != "NOCLOSE") print "</td></tr>\n";

	$tabkey++;
	return $element_id;
}

/**
 * prints collapsable fields to add ASSO/RELA, SOUR, OBJE ...
 *
 * @param string $tag		Gedcom tag name
 */
function print_add_layer($tag, $level=2, $printSaveButton=true) {
	global $factarray, $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;
	global $MEDIA_DIRECTORY, $TEXT_DIRECTION;
	global $gedrec;
	if ($tag=="SOUR") {
		//-- Add new source to fact
		print "<a href=\"javascript:;\" onclick=\"return expand_layer('newsource');\"><img id=\"newsource_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /> ".$pgv_lang["add_source"]."</a>";
		print_help_link("edit_add_SOUR_help", "qm");
		print "<br />";
		print "<div id=\"newsource\" style=\"display: none;\">\n";
		if ($printSaveButton) print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" />";
		print "<table class=\"facts_table center $TEXT_DIRECTION\">\n";
		// 2 SOUR
		$source = "SOUR @";
		add_simple_tag("$level $source");
		// 3 PAGE
		$page = "PAGE";
		add_simple_tag(($level+1)." $page");
		// 3 DATA
		// 4 TEXT
		$text = "TEXT";
		add_simple_tag(($level+2)." $text");
		add_simple_tag(($level+2)." DATE", "", $pgv_lang["date_of_entry"]);
		// 3 OBJE
		add_simple_tag(($level+1)." OBJE @@");
		// 3 QUAY
		add_simple_tag(($level+1)." QUAY");
		print "</table></div>";
	}
	if ($tag=="ASSO") {
		//-- Add a new ASSOciate
		print "<a href=\"javascript:;\" onclick=\"return expand_layer('newasso');\"><img id=\"newasso_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /> ".$pgv_lang["add_asso"]."</a>";
		print_help_link("edit_add_ASSO_help", "qm");
		print "<br />";
		print "<div id=\"newasso\" style=\"display: none;\">\n";
		if ($printSaveButton) print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" />";
		print "<table class=\"facts_table center $TEXT_DIRECTION\">\n";
		// 2 ASSO
		add_simple_tag(($level)." ASSO @");
		// 3 RELA
		add_simple_tag(($level+1)." RELA");
		// 3 NOTE
		add_simple_tag(($level+1)." NOTE");
		print "</table></div>";
	}
	if ($tag=="NOTE") {
		//-- Retrieve existing note or add new note to fact
		$text = "";
		print "<a href=\"javascript:;\" onclick=\"return expand_layer('newnote');\"><img id=\"newnote_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /> ".$pgv_lang["add_note"]."</a>";
		print_help_link("edit_add_NOTE_help", "qm");
		print "<br />\n";
		print "<div id=\"newnote\" style=\"display: none;\">\n";
		if ($printSaveButton) print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" />";
		print "<table class=\"facts_table center $TEXT_DIRECTION\">\n";
		// 2 NOTE
		add_simple_tag(($level)." NOTE ".$text);
		print "</table></div>";
	}
	if ($tag=="OBJE") {
		//-- Add new obje to fact
		print "<a href=\"javascript:;\" onclick=\"return expand_layer('newobje');\"><img id=\"newobje_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"\" title=\"\" /> ".$pgv_lang["add_obje"]."</a>";
		print_help_link("add_media_help", "qm");
		print "<br />";
		print "<div id=\"newobje\" style=\"display: none;\">\n";
		if ($printSaveButton) print "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" />";
		print "<table class=\"facts_table center $TEXT_DIRECTION\">\n";
		add_simple_tag($level." OBJE @@");
		print "</table></div>";
	}
}
/**
 * Add Debug Log
 *
 * This function checks the if the global $DEBUG
 * variable is true and adds debugging information
 * to the log file
 * @param string $logstr	the string to add to the log
 */
function addDebugLog($logstr) {
	global $DEBUG;
	if ($DEBUG) AddToChangeLog($logstr);
}

/**
 * Add new gedcom lines from interface update arrays
 * The edit_interface and add_simple_tag function produce the following
 * arrays incoming from the $_POST form
 * - $glevels[] - an array of the gedcom level for each line that was edited
 * - $tag[] - an array of the tags for each gedcom line that was edited
 * - $islink[] - an array of 1 or 0 values to tell whether the text is a link element and should be surrounded by @@
 * - $text[] - an array of the text data for each line
 * With these arrays you can recreate the gedcom lines like this
 * <code>$glevel[0]." ".$tag[0]." ".$text[0]</code>
 * There will be an index in each of these arrays for each line of the gedcom
 * fact that is being edited.
 * If the $text[] array is empty for the given line, then it means that the
 * user removed that line during editing or that the line is supposed to be
 * empty (1 DEAT, 1 BIRT) for example.  To know if the line should be removed
 * there is a section of code that looks ahead to the next lines to see if there
 * are sub lines.  For example we don't want to remove the 1 DEAT line if it has
 * a 2 PLAC or 2 DATE line following it.  If there are no sub lines, then the line
 * can be safely removed.
 * @param string $newged	the new gedcom record to add the lines to
 * @return string	The updated gedcom record
 */
function handle_updates($newged) {
	global $glevels, $islink, $tag, $uploaded_files, $text, $NOTE;

	for($j=0; $j<count($glevels); $j++) {
		//-- update external note records first
		if (($islink[$j])&&($tag[$j]=="NOTE")) {
			if (empty($NOTE[$text[$j]])) {
				delete_gedrec($text[$j]);
				$text[$j] = "";
			}
			else {
				$noterec = find_gedcom_record($text[$j]);
				$newnote = "0 @$text[$j]@ NOTE\r\n";
				$newline = "1 CONC ".$NOTE[$text[$j]];
				$newlines = preg_split("/\r?\n/", $newline);
				for($k=0; $k<count($newlines); $k++) {
					if ($k>0) $newlines[$k] = "1 CONT ".$newlines[$k];
					if (strlen($newlines[$k])>255) {
						while(strlen($newlines[$k])>255) {
							// Make sure this piece doesn't end on a blank
							// (Blanks belong at the start of the next piece)
							$thisPiece = rtrim(substr($newlines[$k], 0, 255));
							$newnote .= $thisPiece."\r\n";
							$newlines[$k] = substr($newlines[$k], strlen($thisPiece));
							$newlines[$k] = "1 CONC ".$newlines[$k];
						}
						$newnote .= trim($newlines[$k])."\r\n";
					}
					else {
						$newnote .= trim($newlines[$k])."\r\n";
					}
				}
				$notelines = preg_split("/\r?\n/", $noterec);
				for($k=1; $k<count($notelines); $k++) {
					if (preg_match("/1 CON[CT] /", $notelines[$k])==0) $newnote .= trim($notelines[$k])."\r\n";
				}
				if ($GLOBALS["DEBUG"]) print "<pre>$newnote</pre>";
				replace_gedrec($text[$j], $newnote);
			}
		} //-- end of external note handling code

		//print $glevels[$j]." ".$tag[$j];

		// Look for empty SOUR reference with non-empty sub-records.
		// This can happen when the SOUR entry is deleted but its sub-records
		// were incorrectly left intact.
		// The sub-records should be deleted.
		if ($tag[$j]=="SOUR" && ($text[$j]=="@@" || $text[$j]=="")) {
			$text[$j] = "";
			$k = $j+1;
			while(($k<count($glevels))&&($glevels[$k]>$glevels[$j])) {
				$text[$k] = "";
				$k++;
			}
		}

//		if (!empty($text[$j])) {
		if (trim($text[$j])!='') {
			$pass = true;
		}
		else {
			//-- for facts with empty values they must have sub records
			//-- this section checks if they have subrecords
			$k=$j+1;
			$pass=false;
			while(($k<count($glevels))&&($glevels[$k]>$glevels[$j])) {
				if (!empty($text[$k])) {
					if (($tag[$j]!="OBJE")||($tag[$k]=="FILE")) {
						$pass=true;
						break;
					}
				}
				if (($tag[$k]=="FILE")&&(count($uploaded_files)>0)) {
					$filename = array_shift($uploaded_files);
					if (!empty($filename)) {
						$text[$k] = $filename;
						$pass=true;
						break;
					}
				}
				$k++;
			}
		}

		//-- if the value is not empty or it has sub lines
		//--- then write the line to the gedcom record
		//if ((($text[trim($j)]!="")||($pass==true)) && (strlen($text[$j]) > 0)) {
		//-- we have to let some emtpy text lines pass through... (DEAT, BIRT, etc)
		if ($pass==true) {
			if ($islink[$j]) $text[$j]="@".$text[$j]."@";
			$newline = $glevels[$j]." ".$tag[$j];
			//-- check and translate the incoming dates
			if ($tag[$j]=="DATE" && !empty($text[$j])) {
				$text[$j] = check_input_date($text[$j]);
			}
			// print $newline;
//			if (!empty($text[$j])) $newline .= " ".$text[$j];
			if ($text[$j]!="") $newline .= " ".$text[$j];
			$newged .= breakConts($newline, $glevels[$j]+1);
		}
	}

	return $newged;
}

/**
 * break up a line of gedcom text into multiple CONT/CONC lines
 * @param string $newline	the line of text to break
 * @param int $level		the GEDCOM level that new lines should have
 * @return string			returns the updated gedcom record
 */
function breakConts($newline, $level) {
	$newged = "";
	//-- convert returns to CONT lines and break up lines longer than 255 chars
	$newlines = preg_split("/\r?\n/", $newline);
	for($k=0; $k<count($newlines); $k++) {
		if ($k>0) $newlines[$k] = $level." CONT ".$newlines[$k];
		if (strlen($newlines[$k])>255) {
			while(strlen($newlines[$k])>255) {
				// Make sure this piece doesn't end on a blank
				// (Blanks belong at the start of the next piece)
				$thisPiece = rtrim(substr($newlines[$k], 0, 255));
				$newged .= $thisPiece."\r\n";
				$newlines[$k] = substr($newlines[$k], strlen($thisPiece));
				$newlines[$k] = $level." CONC ".$newlines[$k];
			}
			$newged .= trim($newlines[$k])."\r\n";
		}
		else {
			$newged .= trim($newlines[$k])."\r\n";
		}
	}
	return $newged;
}

/**
 * check the given date that was input by a user and convert it
 * to proper gedcom date if possible
 * @author John Finlay
 * @param string $datestr	the date input by the user
 * @return string	the converted date string
 */
function check_input_date($datestr) {
	if (preg_match("/^\d+ \w\w\w \d\d\d\d$/", $datestr)>0) return $datestr;
	$date = parse_date($datestr);
	//print_r($date);
	if ((count($date)==1)&&empty($date[0]['ext'])&&!empty($date[0]['month'])&&!empty($date[0]['year'])) {
		$datestr = strtoupper($date[0]['day']." ".$date[0]['month']." ".$date[0]['year']);
	}
	return $datestr;
}

function print_quick_resn($name) {
	global $SHOW_QUICK_RESN, $align, $factarray, $pgv_lang, $tabkey;

	if ($SHOW_QUICK_RESN) {
		print "<tr><td class=\"descriptionbox\">";
		print_help_link("RESN_help", "qm");
		print $factarray["RESN"];
		print "</td>\n";
		print "<td class=\"optionbox\" colspan=\"3\">\n";
		print "<select name=\"$name\" tabindex=\"".$tabkey."\" ><option value=\"\"></option><option value=\"confidential\"";
		$tabkey++;
		print ">".$pgv_lang["confidential"]."</option><option value=\"locked\"";
		print ">".$pgv_lang["locked"]."</option><option value=\"privacy\"";
		print ">".$pgv_lang["privacy"]."</option>";
		print "</select>\n";
		print "</td>\n";
		print "</tr>\n";
	}
}


/**
 * Link Media ID to Indi, Family, or Source ID
 *
 * Code was removed from inverselink.php to become a callable function
 *
 * @param 	string 	$mediaid	Media ID to be linked
 * @param	string	$linktoid	Indi, Family, or Source ID that the Media ID should link to
 * @param	int		$level		Level where the Media Object reference should be created
 * @return 	bool				success or failure
 */
function linkMedia($mediaid, $linktoid, $level=1) {
	global $GEDCOM, $pgv_lang, $pgv_changes;

	if (empty($level)) $level = 1;
	//-- Make sure we only add new links to the media object
	if (exists_db_link($mediaid, $linktoid, $GEDCOM)) return false;
	if ($level!=1) return false;		// Level 2 items get linked elsewhere
	// find Indi, Family, or Source record to link to
	if (isset($pgv_changes[$linktoid."_".$GEDCOM])) {
		$gedrec = find_updated_record($linktoid);
	} else {
		$gedrec = find_gedcom_record($linktoid);
	}

	//-- check if we are re-editing an unaccepted link that is not already in the DB
	$ct = preg_match("/1 OBJE @$mediaid@/", $gedrec);
	if ($ct>0) return false;
	
	if ($gedrec) {
		// Changed to match format of all other data adds.
		//$mediarec = "1 OBJE @".$mediaid."@\r\n";
		//$newrec = trim($gedrec."\r\n".$mediarec);
		$newrec = $gedrec."\r\n1 OBJE @".$mediaid."@";

		replace_gedrec($linktoid, $newrec);

		return true;
	} else {
		print "<br /><center>".$pgv_lang["invalid_id"]."</center>";
		return false;
	}
}

/**
 * builds the form for adding new facts
 * @param string $fact	the new fact we are adding
 */
function create_add_form($fact) {
	global $tags;

	$tags = array();

	// handle  MARRiage TYPE
	if (substr($fact,0,5)=="MARR_") {
		$tags[0] = "MARR";
		add_simple_tag("1 MARR");
		insert_missing_subtags($fact);
	}
	else {
		$tags[0] = $fact;
		add_simple_tag("1 ".$fact);
		insert_missing_subtags($fact);
	}
}

/**
 * creates the form for editing the fact within the given gedcom record at the
 * given line number
 * @param string $gedrec	the level 0 gedcom record
 * @param int $linenum		the line number of the fact to edit within $gedrec
 * @param string $level0type	the type of the level 0 gedcom record
 */
function create_edit_form($gedrec, $linenum, $level0type) {
	global $WORD_WRAPPED_NOTES, $pgv_lang;
	global $tags;

	$gedlines = split("\n", $gedrec);	// -- find the number of lines in the record
	$fields = preg_split("/\s/", $gedlines[$linenum]);
	$glevel = $fields[0];
	$level = $glevel;
	$type = trim($fields[1]);
	$level1type = $type;
	if (count($fields)>2) {
		$ct = preg_match("/@.*@/",$fields[2]);
		$levellink = $ct > 0;
	}
	else $levellink = false;
	$tags=array();
	$i = $linenum;
	$inSource = false;
	$levelSource = 0;
	// Loop on existing tags :
	while (true) {
		$text = "";
		for($j=2; $j<count($fields); $j++) {
			if ($j>2) $text .= " ";
			$text .= $fields[$j];
		}
		while(($i+1<count($gedlines))&&(preg_match("/".($level+1)." (CON[CT])\s?(.*)/", $gedlines[$i+1], $cmatch)>0)) {
			if ($cmatch[1]=="CONT") $text.="\n";
			else if ($WORD_WRAPPED_NOTES) $text .= " ";
			$conctxt = $cmatch[2];
			$conctxt = preg_replace("/[\r\n]/","",$conctxt);
			$text.=$conctxt;
			$i++;
		}

		if ($type!="DATA" && $type!="CONC" && $type!="CONT") {
			$tags[]=$type;
			$subrecord = $level." ".$type." ".$text;
			if ($inSource && $type=="DATE") add_simple_tag($subrecord, "", $pgv_lang["date_of_entry"]);
			else add_simple_tag($subrecord, $level0type);
		}

		if ($type=="SOUR") {
			$inSource = true;
			$levelSource = $level;
			$haveSourcePage = false;
			$haveSourceText = false;
			$haveSourceDate = false;
			$haveSourceQuay = false;
		}

		$i++;
		if (isset($gedlines[$i])) {
			$fields = preg_split("/\s/", $gedlines[$i]);
			$level = $fields[0];
			if (isset($fields[1])) $type = trim($fields[1]);
			else $level = 0;
		} else $level = 0;

		// Check for, and add, missing tags subordinate to SOUR
		// The logic here is complicated because the missing tags MUST
		// be in the right order.
		if ($inSource) {
			if ($levelSource < $level) {
				if ($type=="PAGE") $haveSourcePage = true;
				if ($type=="TEXT") {
					if (!$haveSourcePage) {
						add_simple_tag(($levelSource+1)." PAGE");
						$haveSourcePage = true;
					}
					$haveSourceText = true;
				}
				if ($type=="DATE") {
					if (!$haveSourceText) {
						if (!$haveSourcePage) {
							add_simple_tag(($levelSource+1)." PAGE");
							$haveSourcePage = true;
						}
						add_simple_tag($levelSource." TEXT");
						$haveSourceText = true;
					}
				}
				if ($type=="DATE") $haveSourceDate = true;
				if ($type=="QUAY") $haveSourceQuay = true;
			} else {
				if (!$haveSourcePage) add_simple_tag(($levelSource+1)." PAGE");
				if (!$haveSourceText) add_simple_tag(($levelSource+2)." TEXT");
				if (!$haveSourceDate) add_simple_tag(($levelSource+2)." DATE", "", $pgv_lang["date_of_entry"]);
				if (!$haveSourceQuay) add_simple_tag(($levelSource+1)." QUAY");
				$inSource = false;
			}
		}

		if ($level<=$glevel) break;

	}

	insert_missing_subtags($level1type);
	return $level1type;
}

/**
 * Populates the global $tags array with any missing sub-tags.
 * @param string $level1tag	the type of the level 1 gedcom record
 */
function insert_missing_subtags($level1tag)
{
	global $tags, $date_and_time, $level2_tags;

	// handle  MARRiage TYPE
	$type_val = "";
	if (substr($level1tag,0,5)=="MARR_") {
		$type_val = substr($level1tag,5);
		$level1tag = "MARR";
	}

	foreach ($level2_tags as $key=>$value) {
		if (in_array($level1tag, $value) && !in_array($key, $tags)) {
			if ($key=="TYPE") add_simple_tag("2 TYPE ".$type_val);
			else add_simple_tag("2 ".$key);
			switch ($key) { // Add level 3/4 tags as appropriate
				case "PLAC":
					add_simple_tag("3 MAP");
					add_simple_tag("4 LATI");
					add_simple_tag("4 LONG");
					break;
				case "FILE":
					add_simple_tag("3 FORM");
					break;
				case "STAT":
					add_simple_tag("3 DATE");
					break;
				case "DATE":
					if (in_array($level1tag, $date_and_time))
						add_simple_tag("3 TIME"); // TIME is NOT a valid 5.5.1 tag; use _TIME ??
					break;
				case "HUSB":
				case "WIFE":
					add_simple_tag("3 AGE");
					break;
				case "FAMC":
					add_simple_tag("3 ADOP BOTH");
					break;
			}
		}
	}
	// Do something (anything!) with unrecognised custom tags
	if (preg_match('/^_/', $level1tag) && count($tags)==1) {
		add_simple_tag("2 DATE");
		add_simple_tag("2 PLAC");
		add_simple_tag("2 ADDR");
		add_simple_tag("2 AGNC");
		add_simple_tag("2 TYPE");
		add_simple_tag("2 AGE");
	}
}
?>
