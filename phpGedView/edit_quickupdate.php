<?php
/**
 * PopUp Window to provide users with a simple quick update form.
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
 * This Page Is Valid XHTML 1.0 Transitional! > 19 August 2005
 *
 * @package PhpGedView
 * @subpackage Edit
 * @version $Id$
 */

require './config.php';

require_once("includes/functions/functions_edit.php");

loadLangFile("pgv_editor");

if ($_SESSION["cookie_login"]) {
	header("Location: login.php?type=simple&url=edit_interface.php");
	exit;
}
if ((isset($_POST["preserve_last_changed"])) && ($_POST["preserve_last_changed"] == "on"))
	$update_CHAN = false;
else
	$update_CHAN = true;

//-- @TODO make list a configurable list
$addfacts = preg_split("/[,; ]/", $QUICK_ADD_FACTS);
usort($addfacts, "factsort");

$reqdfacts = preg_split("/[,; ]/", $QUICK_REQUIRED_FACTS);

//-- @TODO make list a configurable list
$famaddfacts = preg_split("/[,; ]/", $QUICK_ADD_FAMFACTS);
usort($famaddfacts, "factsort");
$famreqdfacts = preg_split("/[,; ]/", $QUICK_REQUIRED_FAMFACTS);

$align="right";
if ($TEXT_DIRECTION=="rtl") $align="left";

print_simple_header($pgv_lang["quick_update_title"]);
require 'js/autocomplete.js.htm';

//-- only allow logged in users to access this page
if (!$ALLOW_EDIT_GEDCOM || !$USE_QUICK_UPDATE || !PGV_USER_ID) {
	print $pgv_lang["access_denied"];
	print_simple_footer();
	exit;
}

if (!isset($closewin)) {
	$closewin=0;
}

// TODO Decide whether to use GET/POST and appropriate validation
$pid     =safe_REQUEST($_REQUEST, 'pid', PGV_REGEX_XREF, PGV_USER_GEDCOM_ID);
$action  =safe_REQUEST($_REQUEST, 'action');
$closewin=safe_REQUEST($_REQUEST, 'closewin', '1', '0');

//-- only allow editors or users who are editing their own individual or their immediate relatives
if (!PGV_USER_CAN_EDIT) {
	$famids = pgv_array_merge(find_sfamily_ids(PGV_USER_GEDCOM_ID), find_family_ids(PGV_USER_GEDCOM_ID));
	$related=false;
	foreach ($famids as $famid) {
		if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid);
		else $famrec = find_updated_record($famid);
		if (preg_match("/1 (HUSB|WIFE|CHIL) @$pid@/", $famrec)) {
			$related=true;
			break;
		}
	}
	if (!$related) {
		print $pgv_lang["access_denied"];
		print_simple_footer();
		exit;
	}
}

//-- find the latest gedrec for the individual
if (!isset($pgv_changes[$pid."_".$GEDCOM])) $gedrec = find_gedcom_record($pid);
else $gedrec = find_updated_record($pid);

// Don't allow edits if the record has changed since the edit-link was created
checkChangeTime($pid, $gedrec, safe_GET('accesstime', PGV_REGEX_INTEGER));


//-- only allow edit of individual records
$disp = true;
$ct = preg_match("/0 @$pid@ (.*)/", $gedrec, $match);
if ($ct>0) {
	$type = trim($match[1]);
	if ($type=="INDI") {
		$disp = displayDetailsById($pid);
	}
	else {
		print $pgv_lang["access_denied"];
		print_simple_footer();
		exit;
	}
}

if ((!$disp)||(!$ALLOW_EDIT_GEDCOM)) {

	print $pgv_lang["access_denied"];
	//-- display messages as to why the editing access was denied
	if (!PGV_USER_CAN_EDIT) print "<br />".$pgv_lang["user_cannot_edit"];
	if (!$ALLOW_EDIT_GEDCOM) print "<br />".$pgv_lang["gedcom_editing_disabled"];
	if (!$disp) {
		print "<br />".$pgv_lang["privacy_prevented_editing"];
		if (!empty($pid)) print "<br />".$pgv_lang["privacy_not_granted"]." pid $pid.";
	}
	print_simple_footer();
	exit;
}

//-- privatize the record so that line numbers etc. match what was in the display
//-- data that is hidden because of privacy is stored in the $pgv_private_records array
//-- any private data will be restored when the record is replaced
$gedrec = privatize_gedcom($gedrec);

//-- put the updates into the gedcom record
if ($action=="update") {
	function check_updated_facts($i, &$famrec, $TAGS, $prefix){
		global $typefacts, $pid, $pgv_lang, $factarray;

		$famrec = trim($famrec);
		$famupdate = false;
		$repeat_tags = array();
		$var = $prefix.$i."DATES";
		if (!empty($_POST[$var])) $DATES = $_POST[$var];
		else $DATES = array();
		$var = $prefix.$i."PLACS";
		if (!empty($_POST[$var])) $PLACS = $_POST[$var];
		else $PLACS = array();
		$var = $prefix.$i."TEMPS";
		if (!empty($_POST[$var])) $TEMPS = $_POST[$var];
		else $TEMPS = array();
		$var = $prefix.$i."RESNS";
		if (!empty($_POST[$var])) $RESNS = $_POST[$var];
		else $RESNS = array();
		$var = $prefix.$i."REMS";
		if (!empty($_POST[$var])) $REMS = $_POST[$var];
		else $REMS = array();

		for($j=0; $j<count($TAGS); $j++) {
			if (!empty($TAGS[$j])) {
				$fact = $TAGS[$j];
//				print $fact;
				if (!isset($repeat_tags[$fact])) $repeat_tags[$fact] = 1;
				else $repeat_tags[$fact]++;

				$DATES[$j] = check_input_date($DATES[$j]);
				if (!isset($REMS[$j])) $REMS[$j] = 0;
				if ($REMS[$j]==1) {
					$DATES[$j]="";
					$PLACS[$j]="";
					$TEMPS[$j]="";
					$RESNS[$j]="";
				}
				if ((empty($DATES[$j]))&&(empty($PLACS[$j]))&&(empty($TEMPS[$j]))&&(empty($RESNS[$j]))) {
					$factrec="";
				}
				else {
					if (!in_array($fact, $typefacts)) $factrec = "1 $fact\r\n";
					else $factrec = "1 EVEN\r\n2 TYPE $fact\r\n";
					if (!empty($DATES[$j])) $factrec .= "2 DATE $DATES[$j]\r\n";
					if (!empty($PLACS[$j])) $factrec .= "2 PLAC $PLACS[$j]\r\n";
					if (!empty($TEMPS[$j])) $factrec .= "2 TEMP $TEMPS[$j]\r\n";
					if (!empty($RESNS[$j])) $factrec .= "2 RESN $RESNS[$j]\r\n";
				}
				if (!in_array($fact, $typefacts)) $lookup = "1 $fact";
				else $lookup = "1 EVEN\r\n2 TYPE $fact\r\n";
				$pos1 = strpos($famrec, $lookup);
//				print $pos1."=pos1";
				$k=1;
				//-- make sure we are working with the correct fact
				while($k<$repeat_tags[$fact]) {
					$pos1 = strpos($famrec, $lookup, $pos1+5);
					$k++;
					if ($pos1===false) break;
				}
//				print $pos1."=pos1";
				$noupdfact = false;
				if ($pos1!==false) {
					$pos2 = strpos($famrec, "\n1 ", $pos1+5);
					if ($pos2===false) $pos2 = strlen($famrec);
					$oldfac = trim(substr($famrec, $pos1, $pos2-$pos1));
					$noupdfact = FactEditRestricted($pid, $oldfac);
					if ($noupdfact) {
						print "<br />".$pgv_lang["update_fact_restricted"]." ".$factarray[$fact]."<br /><br />";
					}
					else {
						//-- delete the fact
						if ($REMS[$j]==1) {
							$famupdate = true;
							$famrec = substr($famrec, 0, $pos1) . "\r\n". substr($famrec, $pos2);
//							print "sfamupdate_del [".$factrec."]{".$oldfac."}";
						}
						else if (!empty($oldfac) && !empty($factrec)) {
							$factrec = $oldfac;
							if (!empty($DATES[$j])) {
								if (strstr($factrec, "\n2 DATE")) $factrec = preg_replace("/2 DATE.*/", "2 DATE $DATES[$j]", $factrec);
								else $factrec = $factrec."\r\n2 DATE $DATES[$j]";
							}
							if (!empty($PLACS[$j])) {
								if (strstr($factrec, "\n2 PLAC")) $factrec = preg_replace("/2 PLAC.*/", "2 PLAC $PLACS[$j]", $factrec);
								else $factrec = $factrec."\r\n2 PLAC $PLACS[$j]";
							}
							if (!empty($TEMPS[$j])) {
								if (strstr($factrec, "\n2 TEMP")) $factrec = preg_replace("/2 TEMP.*/", "2 TEMP $TEMPS[$j]", $factrec);
								else $factrec = $factrec."\r\n2 TEMP $TEMPS[$j]";
							}
							if (!empty($RESNS[$j])) {
								if (strstr($factrec, "\n2 RESN")) $factrec = preg_replace("/2 RESN.*/", "2 RESN $RESNS[$j]", $factrec);
								else $factrec = $factrec."\r\n2 RESN $RESNS[$j]";
							}

							$factrec = preg_replace("/[\r\n]+/", "\r\n", $factrec);
							$oldfac = preg_replace("/[\r\n]+/", "\r\n", $oldfac);
//			print "<table><tr><th>new</th><th>old</th></tr><tr><td><pre>$factrec</pre></td><td><pre>$oldfac</pre></td></tr></table>";
							if (trim($factrec) != trim($oldfac)) {
								$famupdate = true;
								$famrec = substr($famrec, 0, $pos1) . trim($factrec)."\r\n" . substr($famrec, $pos2);
//								print "sfamupdate3 [".$factrec."]{".$oldfac."}";
							}
						}
					}
				}
				else if (!empty($factrec)) {
					$famrec .= "\r\n".$factrec;
					$famupdate = true;
//						print "sfamupdate2";
				}
			}
		}
		return $famupdate;
	}

	$person=Person::getInstance($pid);
	print "<h2>".$pgv_lang["quick_update_title"]."</h2>\n";
	print "<b>".PrintReady($person->getFullName())."</b><br /><br />";

	AddToChangeLog("Quick update attempted for $pid by >".PGV_USER_NAME."<");

	$updated = false;
	$error = "";
	$oldgedrec = $gedrec;
	//-- check for name update
	if (isset($_REQUEST['GIVN'])) $GIVN = $_REQUEST['GIVN'];
	if (isset($_REQUEST['SURN'])) $SURN = $_REQUEST['SURN'];
	if (isset($_REQUEST['MSURN'])) $MSURN = $_REQUEST['MSURN'];
	if (isset($GIVN) || isset($SURN) || isset($MSURN)) {
		$namerec = trim(get_sub_record(1, "1 NAME", $gedrec));
		if (!empty($namerec)) {
			if (isset($GIVN)) {
				//-- check if name line has a GIVN and a SURN
				if (preg_match("~1 NAME.+/.*/~", $namerec)>0) {
					$namerec = preg_replace("/1 NAME.+\/(.*)\//", "1 NAME $GIVN /$1/", $namerec);
				}
				else {
					$namerec = preg_replace("/1 NAME.+/", "1 NAME $GIVN", $namerec);
				}
				if (preg_match("/2 GIVN/", $namerec)>0) $namerec = preg_replace("/2 GIVN.*/", "2 GIVN $GIVN\r\n", $namerec);
				else $namerec.="\r\n2 GIVN $GIVN";
			}
			if (isset($SURN)) {
				//-- check if name line has a GIVN and a SURN
				if (preg_match("~1 NAME.+/.*/~", $namerec)>0) {
					$namerec = preg_replace("/1 NAME(.+)\/.*\//", "1 NAME$1/$SURN/", $namerec);
				}
				else {
					$namerec = preg_replace("/1 NAME ([\w.\ -_]+)/", "1 NAME $1 /$SURN/\r\n", $namerec);
				}
				if (preg_match("/2 SURN/", $namerec)>0) $namerec = preg_replace("/2 SURN.*/", "2 SURN $SURN\r\n", $namerec);
				else $namerec.="\r\n2 SURN $SURN";
			}
			//-- update the married surname
			if (!isset($SURN) || isset($MSURN) && $MSURN!=$SURN) {
				if (preg_match("/2 _MARNM/", $namerec)>0) $namerec = preg_replace("/2 _MARNM.*/", "2 _MARNM $MSURN\r\n", $namerec);
				else $namerec.="\r\n2 _MARNM $MSURN";
			}
			$pos1 = strpos($gedrec, "1 NAME");
			if ($pos1!==false) {
				$pos2 = strpos($gedrec, "\n1", $pos1+5);
				if ($pos2===false) {
					$gedrec = substr($gedrec, 0, $pos1).$namerec;
				}
				else {
					$gedrec = substr($gedrec, 0, $pos1).$namerec."\r\n".substr($gedrec, $pos2+1);
				}
			}
		}
		else $gedrec .= "\r\n1 NAME $GIVN /$SURN/\r\n2 GIVN $GIVN\r\n2 SURN $SURN\r\n2 _MARNM $MSURN";
		$updated = true;
//		print "<pre>NAME\n".$gedrec."</pre>\n";
	}

	//-- update the person's gender
	if (isset($_REQUEST['GENDER'])) $GENDER = $_REQUEST['GENDER'];
	if (!empty($GENDER)) {
		if (preg_match("/1 SEX (\w*)/", $gedrec, $match)>0) {
			if ($match[1] != $GENDER) {
				$gedrec = preg_replace("/1 SEX (\w*)/", "1 SEX $GENDER", $gedrec);
				$updated = true;
			}
		}
		else {
			$gedrec .= "\r\n1 SEX $GENDER";
			$updated = true;
		}
	}
	//-- rtl name update
	if (isset($_REQUEST['HSURN'])) $HSURN = $_REQUEST['HSURN'];
	if (isset($_REQUEST['HGIVN'])) $HGIVN = $_REQUEST['HGIVN'];
	if (!empty($HSURN) || !empty($HGIVN)) {
		if (preg_match("/2 _HEB/", $gedrec)>0) {
			if (!empty($HGIVN)) {
				$gedrec = preg_replace("/2 _HEB.+\/(.*)\//", "2 _HEB $HGIVN /$1/", $gedrec);
			}
			if (!empty($HSURN)) {
				$gedrec = preg_replace("/2 _HEB(.+)\/.*\//", "2 _HEB$1/$HSURN/", $gedrec);
			}
		}
		else {
			$pos1 = strpos($gedrec, "1 NAME");
			if ($pos1!==false) {
				$pos1 = strpos($gedrec, "\n1", $pos1+5);
				if ($pos1===false) $pos1 = strlen($gedrec)-1;
				$gedrec = substr($gedrec, 0, $pos1)."\r\n2 _HEB $HGIVN /$HSURN/\r\n".substr($gedrec, $pos1+1);
			}
			else $gedrec .= "\r\n1 NAME $HGIVN /$HSURN/\r\n2 _HEB $HGIVN /$HSURN/\r\n";
		}
		$updated = true;
	}
	if (isset($_REQUEST['RSURN'])) $RSURN = $_REQUEST['RSURN'];
	if (isset($_REQUEST['RGIVN'])) $RGIVN = $_REQUEST['RGIVN'];
	if (!empty($RSURN) || !empty($RGIVN)) {
		if (preg_match("/2 ROMN/", $gedrec)>0) {
			if (!empty($RGIVN)) {
				$gedrec = preg_replace("/2 ROMN.+\/(.*)\//", "2 ROMN $RGIVN /$1/", $gedrec);
			}
			if (!empty($RSURN)) {
				$gedrec = preg_replace("/2 ROMN(.+)\/.*\//", "2 ROMN$1/$RSURN/", $gedrec);
			}
		}
		else {
			$pos1 = strpos($gedrec, "1 NAME");
			if ($pos1!==false) {
				$pos1 = strpos($gedrec, "\n1", $pos1+5);
				if ($pos1===false) $pos1 = strlen($gedrec)-1;
				$gedrec = substr($gedrec, 0, $pos1)."\r\n2 ROMN $RGIVN /$RSURN/\r\n".substr($gedrec, $pos1+1);
			}
			else $gedrec .= "\r\n1 NAME $RGIVN /$RSURN/\r\n2 ROMN $RGIVN /$RSURN/\r\n";
		}
		$updated = true;
	}

	//-- check for updated facts
	if (isset($_REQUEST['TAGS'])) $TAGS = $_REQUEST['TAGS'];
	if (count($TAGS)>0) {
		$updated |= check_updated_facts("", $gedrec, $TAGS, "");
//		print "FACTS <pre>$gedrec</pre>";
	}

	//-- check for new fact
	if (isset($_REQUEST['newfact'])) $newfact = $_REQUEST['newfact'];
	if (isset($_REQUEST['DATE'])) $DATE = $_REQUEST['DATE'];
	if (isset($_REQUEST['PLAC'])) $PLAC = $_REQUEST['PLAC'];
	if (isset($_REQUEST['TEMP'])) $TEMP = $_REQUEST['TEMP'];
	if (isset($_REQUEST['RESN'])) $RESN = $_REQUEST['RESN'];
	if (!empty($newfact)) {
		if (!in_array($newfact, $typefacts)) $factrec = "1 $newfact\r\n";
		else $factrec = "1 EVEN\r\n2 TYPE $newfact\r\n";
		if (!empty($DATE)) {
			$DATE = check_input_date($DATE);
			$factrec .= "2 DATE $DATE\r\n";
		}
		if (!empty($PLAC)) $factrec .= "2 PLAC $PLAC\r\n";
		if (!empty($TEMP)) $factrec .= "2 TEMP $TEMP\r\n";
		if (!empty($RESN)) $factrec .= "2 RESN $RESN\r\n";
		//-- make sure that there is at least a Y
		if (preg_match("/\n2 \w*/", $factrec)==0) $factrec = "1 $newfact Y\r\n";
		$gedrec .= "\r\n".$factrec;
		$updated = true;
	}

	//-- check for photo update
	if (!empty($_FILES["FILE"]['tmp_name'])) {
		if (!move_uploaded_file($_FILES['FILE']['tmp_name'], $MEDIA_DIRECTORY.basename($_FILES['FILE']['name']))) {
			$error .= "<br />".$pgv_lang["upload_error"]."<br />".file_upload_error_text($_FILES['FILE']['error']);
		}
		else {
			$filename = $MEDIA_DIRECTORY.basename($_FILES['FILE']['name']);
			$thumbnail = $MEDIA_DIRECTORY."thumbs/".basename($_FILES['FILE']['name']);
			generate_thumbnail($filename, $thumbnail);

			if (isset($_REQUEST['TITL'])) $TITL = $_REQUEST['TITL'];
			$objrec = "0 @new@ OBJE\r\n";
			$objrec .= "1 FILE ".$filename."\r\n";
			if (!empty($TITL)) $objrec .= "2 TITL $TITL\r\n";
			$objid = append_gedrec($objrec);

//			$factrec = "1 OBJE @".$objid."@\r\n1 _PRIM Y\r\n"; //@@ MA  _PRIM should either not exist or we should write it as 2 _PRIM here or as 1 _PRIM for the 0 OBJE
			$factrec = "1 OBJE @".$objid."@\r\n";
			if (empty($replace)) $gedrec .= "\r\n".$factrec;
			else {
				$fact = "OBJE";
				$pos1 = strpos($gedrec, "1 $fact");
				if ($pos1!==false) {
					$pos2 = strpos($gedrec, "\n1 ", $pos1+1);
					if ($pos2===false) $pos2 = strlen($gedrec)-1;
					$gedrec = substr($gedrec, 0, $pos1) . "\r\n".$factrec . substr($gedrec, $pos2);
				}
				else $gedrec .= "\r\n".$factrec;
			}
			$updated = true;
		}
	}

	if (isset($_REQUEST['ADDR'])) $ADDR = $_REQUEST['ADDR'];
	if (isset($_REQUEST['ADR1'])) $ADR1 = $_REQUEST['ADR1'];
	if (isset($_REQUEST['POST'])) $POST = $_REQUEST['POST'];
	if (isset($_REQUEST['ADR2'])) $ADR2 = $_REQUEST['ADR2'];
	if (isset($_REQUEST['CITY'])) $CITY = $_REQUEST['CITY'];
	if (isset($_REQUEST['STAE'])) $STAE = $_REQUEST['STAE'];
	if (isset($_REQUEST['CTRY'])) $CTRY = $_REQUEST['CTRY'];
	//--address phone email
	$factrec = "";
	if (!empty($ADDR)) {
		if (!empty($ADR1)||!empty($CITY)||!empty($POST)) {
			$factrec = "1 ADDR $ADDR\r\n";
			if (!empty($_NAME)) $factrec.="2 _NAME ".$_NAME."\r\n";
			if (!empty($ADR1)) $factrec.="2 ADR1 ".$ADR1."\r\n";
			if (!empty($ADR2)) $factrec.="2 ADR2 ".$ADR2."\r\n";
			if (!empty($CITY)) $factrec.="2 CITY ".$CITY."\r\n";
			if (!empty($STAE)) $factrec.="2 STAE ".$STAE."\r\n";
			if (!empty($POST)) $factrec.="2 POST ".$POST."\r\n";
			if (!empty($CTRY)) $factrec.="2 CTRY ".$CTRY."\r\n";
		}
		else {
			$factrec = "1 ADDR ";
			$lines = preg_split("/\r*\n/", $ADDR);
			$factrec .= $lines[0]."\r\n";
			for($i=1; $i<count($lines); $i++) $factrec .= "2 CONT ".$lines[$i]."\r\n";
		}
	}
	$pos1 = strpos($gedrec, "1 ADDR");
	if ($pos1!==false) {
		$pos2 = strpos($gedrec, "\n1 ", $pos1+1);
		if ($pos2===false) $pos2 = strlen($gedrec)-1;
		$gedrec = substr($gedrec, 0, $pos1) . "\r\n".$factrec . substr($gedrec, $pos2);
		$updated = true;
	}
	else if (!empty($factrec)) {
		$gedrec .= "\r\n".$factrec;
		$updated = true;
	}

	if (isset($_REQUEST['PHON'])) $PHON = $_REQUEST['PHON'];
	$factrec = "";
	if (!empty($PHON)) $factrec = "1 PHON $PHON\r\n";
	$pos1 = strpos($gedrec, "1 PHON");
	if ($pos1!==false) {
		$pos2 = strpos($gedrec, "\n1 ", $pos1+1);
		if ($pos2===false) $pos2 = strlen($gedrec)-1;
		$gedrec = substr($gedrec, 0, $pos1) . "\r\n".$factrec . substr($gedrec, $pos2);
		$updated = true;
	}
	else if (!empty($factrec)) {
		$gedrec .= "\r\n".$factrec;
		$updated = true;
	}

	if (isset($_REQUEST['FAX'])) $FAX = $_REQUEST['FAX'];
	$factrec = "";
	if (!empty($FAX)) $factrec = "1 FAX $FAX\r\n";
	$pos1 = strpos($gedrec, "1 FAX");
	if ($pos1!==false) {
		$pos2 = strpos($gedrec, "\n1 ", $pos1+1);
		if ($pos2===false) $pos2 = strlen($gedrec)-1;
		$gedrec = substr($gedrec, 0, $pos1) . "\r\n".$factrec . substr($gedrec, $pos2);
		$updated = true;
	}
	else if (!empty($factrec)) {
		$gedrec .= "\r\n".$factrec;
		$updated = true;
	}

	if (isset($_REQUEST['EMAIL'])) $EMAIL = $_REQUEST['EMAIL'];
	$factrec = "";
	if (!empty($EMAIL)) $factrec = "1 EMAIL $EMAIL\r\n";
	$pos1 = strpos($gedrec, "1 EMAIL");
	if ($pos1!==false) {
		$pos2 = strpos($gedrec, "\n1 ", $pos1+1);
		if ($pos2===false) $pos2 = strlen($gedrec)-1;
		$gedrec = substr($gedrec, 0, $pos1) . "\r\n".$factrec . substr($gedrec, $pos2);
		$updated = true;
	}
	else if (!empty($factrec)) {
		$gedrec .= "\r\n".$factrec;
		$updated = true;
	}

	//-- spouse family tabs
	$sfams = find_families_in_record($gedrec, "FAMS");
	for($i=1; $i<=count($sfams); $i++) {
		$famupdate = false;
		$famid = $sfams[$i-1];
		if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid);
		else $famrec = find_updated_record($famid);
		$oldfamrec = $famrec;
		$parents = find_parents($famid);
		//-- update the spouse
		$spid = "";
		if($parents) {
			if($pid!=$parents["HUSB"]) {
				$tag="HUSB";
				$spid = $parents['HUSB'];
			}
			else {
				$tag = "WIFE";
				$spid = $parents['WIFE'];
			}
		}

		if (isset($_REQUEST['SGIVN'.$i])) $sgivn = $_REQUEST['SGIVN'.$i];
		if (isset($_REQUEST['SSURN'.$i])) $ssurn = $_REQUEST['SSURN'.$i];
		if (isset($_REQUEST['MSSURN'.$i])) $mssurn = $_REQUEST['MSSURN'.$i];
		//--add new spouse name, birth
		if (!empty($sgivn) || !empty($ssurn)) {
			//-- first add the new spouse
			$spouserec = "0 @REF@ INDI\r\n";
			$spouserec .= "1 NAME ".$sgivn." /".$ssurn."/\r\n";
			if (!empty($sgivn)) $spouserec .= "2 GIVN ".$sgivn."\r\n";
			if (!empty($ssurn)) $spouserec .= "2 SURN ".$ssurn."\r\n";
			if (!empty($mssurn)) $spouserec .= "2 _MARNM ".$mssurn."\r\n";

			if (isset($_REQUEST['HSGIVN'.$i])) $hsgivn = $_REQUEST['HSGIVN'.$i];
			if (isset($_REQUEST['HSSURN'.$i])) $hssurn = $_REQUEST['HSSURN'.$i];
			if (!empty($hsgivn) || !empty($hssurn)) {
				$spouserec .= "2 _HEB ".$hsgivn." /".$hssurn."/\r\n";
			}
			if (isset($_REQUEST['RSGIVN'.$i])) $rsgivn = $_REQUEST['RSGIVN'.$i];
			if (isset($_REQUEST['RSSURN'.$i])) $rssurn = $_REQUEST['RSSURN'.$i];
			if (!empty($rsgivn) || !empty($rssurn)) {
				$spouserec .= "2 ROMN ".$rsgivn." /".$rssurn."/\r\n";
			}
			if (isset($_REQUEST['SSEX'.$i])) $ssex = $_REQUEST['SSEX'.$i];
			if (!empty($ssex)) $spouserec .= "1 SEX ".$ssex."\r\n";

			if (isset($_REQUEST['BDATE'.$i])) $bdate = $_REQUEST['BDATE'.$i];
			if (isset($_REQUEST['BPLAC'.$i])) $bplac = $_REQUEST['BPLAC'.$i];
			if (!empty($bdate)||!empty($bplac)) {
				$spouserec .= "1 BIRT\r\n";
				if (!empty($bdate)) {
					$bdate = check_input_date($bdate);
					$spouserec .= "2 DATE $bdate\r\n";
				}
				if (!empty($bplac)) $spouserec .= "2 PLAC ".$bplac."\r\n";
				if (isset($_REQUEST['BRESN'.$i])) $bresn = $_REQUEST['BRESN'.$i];
				if (!empty($bresn)) $spouserec .= "2 RESN ".$bresn."\r\n";
			}
			if (isset($_REQUEST['DDATE'.$i])) $bdate = $_REQUEST['DDATE'.$i];
			if (isset($_REQUEST['DPLAC'.$i])) $bplac = $_REQUEST['DPLAC'.$i];
			if (!empty($bdate)||!empty($bplac)) {
				$spouserec .= "1 DEAT\r\n";
				if (!empty($bdate)) {
					$bdate = check_input_date($bdate);
					$spouserec .= "2 DATE $bdate\r\n";
				}
				if (!empty($bplac)) $spouserec .= "2 PLAC ".$bplac."\r\n";
				if (isset($_REQUEST['DRESN'.$i])) $bresn = $_REQUEST['DRESN'.$i];
				if (!empty($bresn)) $spouserec .= "2 RESN ".$bresn."\r\n";
			}
			$spouserec .= "\r\n1 FAMS @$famid@\r\n";
			$SPID[$i] = append_gedrec($spouserec);
		}

		if (!empty($SPID[$i]) && $spid!=$SPID[$i]) {
			if (strstr($famrec, "1 $tag")!==false) $famrec = preg_replace("/1 $tag @.*@/", "1 $tag @$SPID[$i]@", $famrec);
			else $famrec .= "\r\n1 $tag @$SPID[$i]@";
			$famupdate = true;
//			print "sfamupdate1";
		}

		//-- check for updated facts
		$var = "F".$i."TAGS";
		if (isset($_REQUEST[$var])) $TAGS = $_REQUEST[$var];
		else $TAGS = array();
		if (count($TAGS)>0) {
			$famupdate |= check_updated_facts($i, $famrec, $TAGS, "F");
		}

		//-- check for new fact
		$var = "F".$i."newfact";
		if (!empty($_REQUEST[$var])) $newfact = $_REQUEST[$var];
		else $newfact = "";
		if (!empty($newfact)) {
			if (!in_array($newfact, $typefacts)) $factrec = "1 $newfact\r\n";
			else $factrec = "1 EVEN\r\n2 TYPE $newfact\r\n";
			$var = "F".$i."DATE";
			if (!empty($_REQUEST[$var])) $FDATE = $_REQUEST[$var];
			else $FDATE = "";
			if (!empty($FDATE)) {
				$FDATE = check_input_date($FDATE);
				$factrec .= "2 DATE $FDATE\r\n";
			}
			$var = "F".$i."PLAC";
			if (!empty($_REQUEST[$var])) $FPLAC = $_REQUEST[$var];
			else $FPLAC = "";
			if (!empty($FPLAC)) $factrec .= "2 PLAC $FPLAC\r\n";
			$var = "F".$i."TEMP";
			if (!empty($_REQUEST[$var])) $FTEMP = $_REQUEST[$var];
			else $FTEMP = "";
			if (!empty($FTEMP)) $factrec .= "2 TEMP $FTEMP\r\n";
			$var = "F".$i."RESN";
			if (!empty($_REQUEST[$var])) $FRESN = $_REQUEST[$var];
			else $FRESN = "";
			if (!empty($FRESN)) $factrec .= "2 RESN $FRESN\r\n";
			//-- make sure that there is at least a Y
			if (preg_match("/\n2 \w*/", $factrec)==0) $factrec = "1 $newfact Y\r\n";
			$famrec .= "\r\n".$factrec;
			$famupdate = true;
//			print "sfamupdate4";
		}

		if (isset($_REQUEST['CHIL'])) $CHIL = $_REQUEST['CHIL'];
		if (!empty($CHIL[$i])) {
			$famupdate = true;
//			print "sfamupdate5";
			$famrec .= "\r\n1 CHIL @".$CHIL[$i]."@";
			if (!isset($pgv_changes[$CHIL[$i]."_".$GEDCOM])) $childrec = find_person_record($CHIL[$i]);
			else $childrec = find_updated_record($CHIL[$i]);
			if (preg_match("/1 FAMC @$famid@/", $childrec)==0) {
				$childrec = "\r\n1 FAMC @$famid@";
				replace_gedrec($CHIL[$i], $childrec, $update_CHAN);
			}
		}

		$var = "F".$i."CDEL";
		if (!empty($_REQUEST[$var])) $fcdel = $_REQUEST[$var];
		else $fcdel = "";
		if (!empty($fcdel)) {
			$famrec = preg_replace("/1 CHIL @$fcdel@/", "", $famrec);
			$famupdate = true;
//			print "sfamupdate6";
		}

		//--add new child, name, birth
		$cgivn = "";
		$var = "C".$i."GIVN";
		if (!empty($_REQUEST[$var])) $cgivn = $_REQUEST[$var];
		else $cgivn = "";
		$csurn = "";
		$var = "C".$i."SURN";
		if (!empty($_REQUEST[$var])) $csurn = $_REQUEST[$var];
		else $csurn = "";
		if (!empty($cgivn) || !empty($csurn)) {
			//-- first add the new child
			$childrec = "0 @REF@ INDI\r\n";
			$childrec .= "1 NAME $cgivn /$csurn/\r\n";
			if (!empty($cgivn)) $childrec .= "2 GIVN $cgivn\r\n";
			if (!empty($csurn)) $childrec .= "2 SURN $csurn\r\n";
			if (isset($_REQUEST["HC{$i}GIVN"])) $hsgivn = $_REQUEST["HC{$i}GIVN"];
			if (isset($_REQUEST["HC{$i}SURN"])) $hssurn = $_REQUEST["HC{$i}SURN"];
			if (!empty($hsgivn) || !empty($hssurn)) {
				$childrec .= "2 _HEB ".$hsgivn." /".$hssurn."/\r\n";
			}
			if (isset($_REQUEST["RC{$i}GIVN"])) $rsgivn = $_REQUEST["RC{$i}GIVN"];
			if (isset($_REQUEST["RC{$i}SURN"])) $rssurn = $_REQUEST["RC{$i}SURN"];
			if (!empty($rsgivn) || !empty($rssurn)) {
				$childrec .= "2 ROMN ".$rsgivn." /".$rssurn."/\r\n";
			}
			$var = "C".$i."SEX";
			$csex = "";
			if (!empty($_REQUEST[$var])) $csex = $_REQUEST[$var];
			if (!empty($csex)) $childrec .= "1 SEX $csex\r\n";
			//--child birth
			$var = "C".$i."DATE";
			$cdate = "";
			if (!empty($_REQUEST[$var])) $cdate = $_REQUEST[$var];
			$var = "C".$i."PLAC";
			$cplac = "";
			if (!empty($_REQUEST[$var])) $cplac = $_REQUEST[$var];
			if (!empty($cdate)||!empty($cplac)) {
				$childrec .= "1 BIRT\r\n";
				$cdate = check_input_date($cdate);
				if (!empty($cdate)) $childrec .= "2 DATE $cdate\r\n";
				if (!empty($cplac)) $childrec .= "2 PLAC $cplac\r\n";
				$var = "C".$i."RESN";
				$cresn = "";
				if (!empty($_REQUEST[$var])) $cresn = $_REQUEST[$var];
				if (!empty($cresn)) $childrec .= "2 RESN $cresn\r\n";
			}
			//--child death
			$var = "C".$i."DDATE";
			$cdate = "";
			if (!empty($_REQUEST[$var])) $cdate = $_REQUEST[$var];
			$var = "C".$i."DPLAC";
			$cplac = "";
			if (!empty($_REQUEST[$var])) $cplac = $_REQUEST[$var];
			if (!empty($cdate)||!empty($cplac)) {
				$childrec .= "1 DEAT\r\n";
				$cdate = check_input_date($cdate);
				if (!empty($cdate)) $childrec .= "2 DATE $cdate\r\n";
				if (!empty($cplac)) $childrec .= "2 PLAC $cplac\r\n";
				$var = "C".$i."DRESN";
				$cresn = "";
				if (!empty($_REQUEST[$var])) $cresn = $_REQUEST[$var];
				if (!empty($cresn)) $childrec .= "2 RESN $cresn\r\n";
			}
			$childrec .= "1 FAMC @$famid@\r\n";
			$cxref = append_gedrec($childrec);
			$famrec .= "\r\n1 CHIL @$cxref@";
			$famupdate = true;
//			print "sfamupdate7";
		}

		if ($famupdate && ($famrec!=$oldfamrec)) replace_gedrec($famid, $famrec, $update_CHAN);
	}

	//--add new spouse name, birth, marriage
	if (isset($_REQUEST['SGIVN'])) $SGIVN = $_REQUEST['SGIVN'];
	if (isset($_REQUEST['SSURN'])) $SSURN = $_REQUEST['SSURN'];
	if (isset($_REQUEST['MSSURN'])) $MSSURN = $_REQUEST['MSSURN'];
	if (isset($_REQUEST['SSEX'])) $SSEX = $_REQUEST['SSEX'];
	if (!empty($SGIVN) || !empty($SSURN)) {
		//-- first add the new spouse
		$spouserec = "0 @REF@ INDI\r\n";
		$spouserec .= "1 NAME $SGIVN /$SSURN/\r\n";
		if (!empty($SGIVN)) $spouserec .= "2 GIVN $SGIVN\r\n";
		if (!empty($SSURN)) $spouserec .= "2 SURN $SSURN\r\n";
		if (!empty($MSSURN)) $spouserec .= "2 _MARNM $MSSURN\r\n";
		if (!empty($SSEX)) $spouserec .= "1 SEX $SSEX\r\n";
		if (isset($_REQUEST['BDATE'])) $BDATE = $_REQUEST['BDATE'];
		if (isset($_REQUEST['BPLAC'])) $BPLAC = $_REQUEST['BPLAC'];
		if (isset($_REQUEST['BRESN'])) $BRESN = $_REQUEST['BRESN'];
		if (!empty($BDATE)||!empty($BPLAC)) {
			$spouserec .= "1 BIRT\r\n";
			if (!empty($BDATE)) $spouserec .= "2 DATE $BDATE\r\n";
			if (!empty($BPLAC)) $spouserec .= "2 PLAC $BPLAC\r\n";
			if (!empty($BRESN)) $spouserec .= "2 RESN $BRESN\r\n";
		}
		if (isset($_REQUEST['DDATE'])) $DDATE = $_REQUEST['DDATE'];
		if (isset($_REQUEST['DPLAC'])) $DPLAC = $_REQUEST['DPLAC'];
		if (isset($_REQUEST['DRESN'])) $DRESN = $_REQUEST['DRESN'];
		if (!empty($DDATE)||!empty($DPLAC)) {
			$spouserec .= "1 DEAT\r\n";
			if (!empty($DDATE)) $spouserec .= "2 DATE $DDATE\r\n";
			if (!empty($DPLAC)) $spouserec .= "2 PLAC $DPLAC\r\n";
			if (!empty($DRESN)) $spouserec .= "2 RESN $DRESN\r\n";
		}
		$xref = append_gedrec($spouserec);

		//-- next add the new family record
		$famrec = "0 @REF@ FAM\r\n";
		if ($SSEX=="M") $famrec .= "1 HUSB @$xref@\r\n1 WIFE @$pid@\r\n";
		else $famrec .= "1 HUSB @$pid@\r\n1 WIFE @$xref@\r\n";
		$newfamid = append_gedrec($famrec);

		//-- add the new family id to the new spouse record
		$spouserec = find_updated_record($xref);
		if (empty($spouserec)) $spouserec = find_person_record($xref);
		$spouserec .= "\r\n1 FAMS @$newfamid@\r\n";
		replace_gedrec($xref, $spouserec, $update_CHAN);

		//-- last add the new family id to the persons record
		$gedrec .= "\r\n1 FAMS @$newfamid@\r\n";
		$updated = true;
	}
	if (isset($_REQUEST['MARRY'])) $MARRY = $_REQUEST['MARRY'];
	if (isset($_REQUEST['MDATE'])) $MDATE = $_REQUEST['MDATE'];
	if (isset($_REQUEST['MPLAC'])) $MPLAC = $_REQUEST['MPLAC'];
	if (isset($_REQUEST['MRESN'])) $MRESN = $_REQUEST['MRESN'];
	if (!empty($MDATE)||!empty($MPLAC)||!empty($MARRY)) {
		if (empty($newfamid)) {
			$famrec = "0 @REF@ FAM\r\n";
			if (preg_match("/1 SEX M/", $gedrec)>0) $famrec .= "1 HUSB @$pid@\r\n";
			else $famrec .= "1 WIFE @$pid@";
			$newfamid = append_gedrec($famrec);
			$gedrec .= "\r\n1 FAMS @$newfamid@";
			$updated = true;
		}
		if (!empty($MDATE)||!empty($MPLAC)) {
			$factrec = "1 MARR\r\n";
		}
		else if (!empty($MARRY)) {
			$factrec = "1 MARR Y\r\n";
		}
		$MDATE = check_input_date($MDATE);
		if (!empty($MDATE)) $factrec .= "2 DATE $MDATE\r\n";
		if (!empty($MPLAC)) $factrec .= "2 PLAC $MPLAC\r\n";
		if (!empty($MRESN)) $factrec .= "2 RESN $MRESN\r\n";
		$famrec .= "\r\n".$factrec;
	}

	//--add new child, name, birth
	if (isset($_REQUEST['CGIVN'])) $CGIVN = $_REQUEST['CGIVN'];
	if (isset($_REQUEST['CSURN'])) $CSURN = $_REQUEST['CSURN'];
	if (isset($_REQUEST['CSEX'])) $CSEX = $_REQUEST['CSEX'];
	if (isset($_REQUEST['HCGIVN'])) $HCGIVN = $_REQUEST['HCGIVN'];
	if (isset($_REQUEST['HCSURN'])) $HCSURN = $_REQUEST['HCSURN'];
	if (!empty($CGIVN) || !empty($CSURN)) {
		//-- first add the new child
		$childrec = "0 @REF@ INDI\r\n";
		$childrec .= "1 NAME $CGIVN /$CSURN/\r\n";
		if (!empty($CGIVN)) $childrec .= "2 GIVN $CGIVN\r\n";
		if (!empty($CSURN)) $childrec .= "2 SURN $CSURN\r\n";
		if (!empty($HCGIVN) || !empty($HCSURN)) {
			$childrec .= "2 _HEB $HCGIVN /$HCSURN/\r\n";
		}
		if (!empty($CSEX)) $childrec .= "1 SEX $CSEX\r\n";
		if (isset($_REQUEST['CDATE'])) $CDATE = $_REQUEST['CDATE'];
		if (isset($_REQUEST['CPLAC'])) $CPLAC = $_REQUEST['CPLAC'];
		if (isset($_REQUEST['CRESN'])) $CRESN = $_REQUEST['CRESN'];
		if (!empty($CDATE)||!empty($CPLAC)) {
			$childrec .= "1 BIRT\r\n";
			$CDATE = check_input_date($CDATE);
			if (!empty($CDATE)) $childrec .= "2 DATE $CDATE\r\n";
			if (!empty($CPLAC)) $childrec .= "2 PLAC $CPLAC\r\n";
			if (!empty($CRESN)) $childrec .= "2 RESN $CRESN\r\n";
		}
		if (isset($_REQUEST['CDDATE'])) $CDDATE = $_REQUEST['CDDATE'];
		if (isset($_REQUEST['CDPLAC'])) $CDPLAC = $_REQUEST['CDPLAC'];
		if (isset($_REQUEST['CDRESN'])) $CDRESN = $_REQUEST['CDRESN'];
		if (!empty($CDDATE)||!empty($CDPLAC)) {
			$childrec .= "1 DEAT\r\n";
			$CDDATE = check_input_date($CDDATE);
			if (!empty($CDDATE)) $childrec .= "2 DATE $CDDATE\r\n";
			if (!empty($CDPLAC)) $childrec .= "2 PLAC $CDPLAC\r\n";
			if (!empty($CDRESN)) $childrec .= "2 RESN $CDRESN\r\n";
		}
		$cxref = append_gedrec($childrec);

		//-- if a new family was already made by adding a spouse or a marriage
		//-- then use that id, otherwise create a new family
		if (empty($newfamid)) {
			$famrec = "0 @REF@ FAM\r\n";
			if (preg_match("/1 SEX M/", $gedrec)>0) $famrec .= "1 HUSB @$pid@\r\n";
			else $famrec .= "1 WIFE @$pid@\r\n";
			$famrec .= "1 CHIL @$cxref@\r\n";
			$newfamid = append_gedrec($famrec);

			//-- add the new family to the new child
			$childrec = find_updated_record($cxref);
			if (empty($childrec)) $childrec = find_person_record($cxref);
			$childrec .= "\r\n1 FAMC @$newfamid@\r\n";
			replace_gedrec($cxref, $childrec, $update_CHAN);

			//-- add the new family to the original person
			$gedrec .= "\r\n1 FAMS @$newfamid@";
			$updated = true;
		}
		else {
			$famrec .= "\r\n1 CHIL @$cxref@\r\n";

			//-- add the family to the new child
			$childrec = find_updated_record($cxref);
			if (empty($childrec)) $childrec = find_person_record($cxref);
			$childrec .= "\r\n1 FAMC @$newfamid@\r\n";
			replace_gedrec($cxref, $childrec, $update_CHAN);
		}
		print $pgv_lang["update_successful"]."<br />\n";;
	}
	if (!empty($newfamid)) {
		$famrec = preg_replace("/0 @(.*)@/", "0 @".$newfamid."@", $famrec);
		replace_gedrec($newfamid, $famrec, $update_CHAN);
	}

	//------------------------------------------- updates for family with parents
	$cfams = find_families_in_record($gedrec, "FAMC");
	if (count($cfams)==0) $cfams[] = "";
	$i++;
	for($j=1; $j<=count($cfams); $j++) {
		$famid = $cfams[$j-1];
//		print $famid;
		$famupdate = false;
		if (!empty($famid)) {
			if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid);
			else $famrec = find_updated_record($famid);
			$oldfamrec = $famrec;
		}
		else {
			$famrec = "0 @REF@ FAM\r\n1 CHIL @$pid@";
			$oldfamrec = "";
		}

		if (isset($_REQUEST['FATHER'])) $FATHER = $_REQUEST['FATHER'];
		if (empty($FATHER[$i])) {
			//-- update the parents
			$sgivn = "";
			$ssurn = "";
			//--add new spouse name, birth
			if (isset($_REQUEST["FGIVN$i"])) $sgivn = $_REQUEST["FGIVN$i"];
			if (isset($_REQUEST["FSURN$i"])) $ssurn = $_REQUEST["FSURN$i"];
			if (!empty($sgivn) || !empty($ssurn)) {
				//-- first add the new spouse
				$spouserec = "0 @REF@ INDI\r\n";
				$spouserec .= "1 NAME ".$sgivn." /".$ssurn."/\r\n";
				if (!empty($sgivn)) $spouserec .= "2 GIVN ".$sgivn."\r\n";
				if (!empty($ssurn)) $spouserec .= "2 SURN ".$ssurn."\r\n";
				$hsgivn = "";
				$hssurn = "";
				if (isset($_REQUEST["HFGIVN$i"])) $hsgivn = $_REQUEST["HFGIVN$i"];
				if (isset($_REQUEST["HFSURN$i"])) $hssurn = $_REQUEST["HFSURN$i"];
				if (!empty($hsgivn) || !empty($hssurn)) {
					$spouserec .= "2 _HEB ".$hsgivn." /".$hssurn."/\r\n";
				}
				$rsgivn = "";
				$rssurn = "";
				if (isset($_REQUEST["RFGIVN$i"])) $rsgivn = $_REQUEST["RFGIVN$i"];
				if (isset($_REQUEST["RFSURN$i"])) $rssurn = $_REQUEST["RFSURN$i"];
				if (!empty($rsgivn) || !empty($rssurn)) {
					$spouserec .= "2 ROMN ".$rsgivn." /".$rssurn."/\r\n";
				}
				$ssex = "";
				if (isset($_REQUEST["FSEX$i"])) $ssex = $_REQUEST["FSEX$i"];
				if (!empty($ssex)) $spouserec .= "1 SEX ".$ssex."\r\n";
				$bdate = "";
				$bplac = "";
				if (isset($_REQUEST["FBDATE$i"])) $bdate = $_REQUEST["FBDATE$i"];
				if (isset($_REQUEST["FBPLAC$i"])) $bplac = $_REQUEST["FBPLAC$i"];
				if (!empty($bdate)||!empty($bplac)) {
					$spouserec .= "1 BIRT\r\n";
					$bdate = check_input_date($bdate);
					if (!empty($bdate)) $spouserec .= "2 DATE $bdate\r\n";
					if (!empty($bplac)) $spouserec .= "2 PLAC ".$bplac."\r\n";
					$bresn = "";
					if (isset($_REQUEST["FBRESN$i"])) $bresn = $_REQUEST["FBRESN$i"];
					if (!empty($bresn)) $spouserec .= "2 RESN ".$bresn."\r\n";
				}
				$bdate = "";
				$bplac = "";
				if (isset($_REQUEST["FDDATE$i"])) $bdate = $_REQUEST["FDDATE$i"];
				if (isset($_REQUEST["FDPLAC$i"])) $bplac = $_REQUEST["FDPLAC$i"];
				if (!empty($bdate)||!empty($bplac)) {
					$spouserec .= "1 DEAT\r\n";
					$bdate = check_input_date($bdate);
					if (!empty($bdate)) $spouserec .= "2 DATE $bdate\r\n";
					if (!empty($bplac)) $spouserec .= "2 PLAC ".$bplac."\r\n";
					$bresn = "";
					if (isset($_REQUEST["FDRESN$i"])) $bresn = $_REQUEST["FDRESN$i"];
					if (!empty($bresn)) $spouserec .= "2 RESN ".$bresn."\r\n";
				}
				if (empty($famid)) {
					//print "HERE 1";
					$famid = append_gedrec($famrec);
					//print "<pre>$famrec</pre>";
					$gedrec .= "\r\n1 FAMC @$famid@\r\n";
					$updated = true;
				}
				$spouserec .= "\r\n1 FAMS @$famid@\r\n";
				$FATHER[$i] = append_gedrec($spouserec);
			}
		}
		else {
			if (empty($famid)) {
				//print "HERE 2";
				$famid = append_gedrec($famrec);
				$gedrec .= "\r\n1 FAMC @$famid@\r\n";
				$updated = true;
			}
			if (empty($oldfamrec)) {
				$spouserec = find_updated_record($FATHER[$i]);
				if (empty($spouserec)) $spouserec = find_person_record($FATHER[$i]);
				$spouserec .= "\r\n1 FAMS @$famid@";
				replace_gedrec($FATHER[$i], $spouserec, $update_CHAN);
			}
		}

		$parents = find_parents_in_record($famrec);
		if (!empty($FATHER[$i]) && $parents['HUSB']!=$FATHER[$i]) {
			if (strstr($famrec, "1 HUSB")!==false) $famrec = preg_replace("/1 HUSB @.*@/", "1 HUSB @$FATHER[$i]@", $famrec);
			else $famrec .= "\r\n1 HUSB @$FATHER[$i]@";
			$famupdate = true;
//			print "famupdate1";
		}

		if (isset($_REQUEST['MOTHER'])) $MOTHER = $_REQUEST['MOTHER'];
		if (empty($MOTHER[$i])) {
			//-- update the parents
			$sgivn = "";
			$ssurn = "";
			if (isset($_REQUEST["MGIVN$i"])) $sgivn = $_REQUEST["MGIVN$i"];
			if (isset($_REQUEST["MSURN$i"])) $ssurn = $_REQUEST["MSURN$i"];
			//--add new spouse name, birth
			if (!empty($sgivn) || !empty($ssurn)) {
				//-- first add the new spouse
				$spouserec = "0 @REF@ INDI\r\n";
				$spouserec .= "1 NAME ".$sgivn." /".$ssurn."/\r\n";
				if (!empty($sgivn)) $spouserec .= "2 GIVN ".$sgivn."\r\n";
				if (!empty($ssurn)) $spouserec .= "2 SURN ".$ssurn."\r\n";
				$hsgivn = "";
				$hssurn = "";
				if (isset($_REQUEST["HMGIVN$i"])) $hsgivn = $_REQUEST["HMGIVN$i"];
				if (isset($_REQUEST["HMSURN$i"])) $hssurn = $_REQUEST["HMSURN$i"];
				if (!empty($hsgivn) || !empty($hssurn)) {
					$spouserec .= "2 _HEB ".$hsgivn." /".$hssurn."/\r\n";
				}
				$rsgivn = "";
				$rssurn = "";
				if (isset($_REQUEST["RMGIVN$i"])) $rsgivn = $_REQUEST["RMGIVN$i"];
				if (isset($_REQUEST["RMSURN$i"])) $rssurn = $_REQUEST["RMSURN$i"];
				if (!empty($rsgivn) || !empty($rssurn)) {
					$spouserec .= "2 ROMN ".$rsgivn." /".$rssurn."/\r\n";
				}
				$ssex = "";
				if (isset($_REQUEST["MSEX$i"])) $ssex = $_REQUEST["MSEX$i"];
				if (!empty($ssex)) $spouserec .= "1 SEX ".$ssex."\r\n";
				$bdate = "";
				$bplac = "";
				if (isset($_REQUEST["MBDATE$i"])) $bdate = $_REQUEST["MBDATE$i"];
				if (isset($_REQUEST["MBPLAC$i"])) $bplac = $_REQUEST["MBPLAC$i"];
				if (!empty($bdate)||!empty($bplac)) {
					$spouserec .= "1 BIRT\r\n";
					$bdate = check_input_date($bdate);
					if (!empty($bdate)) $spouserec .= "2 DATE $bdate\r\n";
					if (!empty($bplac)) $spouserec .= "2 PLAC ".$bplac."\r\n";
					$bresn = "";
					if (isset($_REQUEST["MBRESN$i"])) $bplac = $_REQUEST["MBRESN$i"];
					if (!empty($bresn)) $spouserec .= "2 RESN ".$bresn."\r\n";
				}
				$bdate = "";
				$bplac = "";
				if (isset($_REQUEST["MDDATE$i"])) $bdate = $_REQUEST["MDDATE$i"];
				if (isset($_REQUEST["MDPLAC$i"])) $bplac = $_REQUEST["MDPLAC$i"];
				if (!empty($bdate)||!empty($bplac)) {
					$spouserec .= "1 DEAT\r\n";
					$bdate = check_input_date($bdate);
					if (!empty($bdate)) $spouserec .= "2 DATE $bdate\r\n";
					if (!empty($bplac)) $spouserec .= "2 PLAC ".$bplac."\r\n";
					$bresn = "";
					if (isset($_REQUEST["MDRESN$i"])) $bplac = $_REQUEST["MDRESN$i"];
					if (!empty($bresn)) $spouserec .= "2 RESN ".$bresn."\r\n";
				}
				if (empty($famid)) {
					//print "HERE 3";
					$famid = append_gedrec($famrec);
					$gedrec .= "\r\n1 FAMC @$famid@\r\n";
					$updated = true;
				}
				$spouserec .= "\r\n1 FAMS @$famid@\r\n";
				$MOTHER[$i] = append_gedrec($spouserec);
			}
		}
		else {
			if (empty($famid)) {
// 				print "HERE 4";
				$famid = append_gedrec($famrec);
				$gedrec .= "\r\n1 FAMC @$famid@\r\n";
				$updated = true;
			}
			if (empty($oldfamrec)) {
				$spouserec = find_updated_record($MOTHER[$i]);
				if (empty($spouserec)) $spouserec = find_person_record($MOTHER[$i]);
				$spouserec .= "\r\n1 FAMS @$famid@";
				replace_gedrec($MOTHER[$i], $spouserec, $update_CHAN);
			}
		}
		if (!empty($MOTHER[$i]) && $parents['WIFE']!=$MOTHER[$i]) {
			if (strstr($famrec, "1 WIFE")!==false) $famrec = preg_replace("/1 WIFE @.*@/", "1 WIFE @$MOTHER[$i]@", $famrec);
			else $famrec .= "\r\n1 WIFE @$MOTHER[$i]@";
			$famupdate = true;
//			print "famupdate2";
		}

		//-- check for updated facts
		$var = "F".$i."TAGS";
		if (isset($_REQUEST[$var])) $TAGS = $_REQUEST[$var];
		else $TAGS = array();
		if (count($TAGS)>0) {
			$famupdate |= check_updated_facts($i, $famrec, $TAGS, "F");
		}

		//-- check for new fact
		$var = "F".$i."newfact";
		$newfact = "";
		if (isset($_REQUEST[$var])) $newfact = $_REQUEST[$var];
		if (!empty($newfact)) {
			if (empty($famid)) {
				//print "HERE 6";
				$famid = append_gedrec($famrec);
				$gedrec .= "\r\n1 FAMC @$famid@\r\n";
				$updated = true;
			}
			if (!in_array($newfact, $typefacts)) $factrec = "1 $newfact\r\n";
			else $factrec = "1 EVEN\r\n2 TYPE $newfact\r\n";
			$var = "F".$i."DATE";
			if (isset($_REQUEST[$var])) $FDATE = $_REQUEST[$var];
			else $FDATE = "";
			$FDATE = check_input_date($FDATE);
			if (!empty($FDATE)) $factrec .= "2 DATE $FDATE\r\n";
			$var = "F".$i."PLAC";
			if (isset($_REQUEST[$var])) $FPLAC = $_REQUEST[$var];
			else $FPLAC = "";
			if (!empty($FPLAC)) $factrec .= "2 PLAC $FPLAC\r\n";
			$var = "F".$i."TEMP";
			if (isset($_REQUEST[$var])) $FTEMP = $_REQUEST[$var];
			else $FTEMP = "";
			if (!empty($FTEMP)) $factrec .= "2 TEMP $FTEMP\r\n";
			$var = "F".$i."RESN";
			if (isset($_REQUEST[$var])) $FRESN = $_REQUEST[$var];
			else $FRESN;
			if (!empty($FRESN)) $factrec .= "2 RESN $FRESN\r\n";
			//-- make sure that there is at least a Y
			if (preg_match("/\n2 \w*/", $factrec)==0) $factrec = "1 $newfact Y\r\n";
			$famrec .= "\r\n".$factrec;
			$famupdate = true;
//			print "famupdate5";
		}

		if (isset($_REQUEST['CHIL'])) $CHIL = $_REQUEST['CHIL'];
		if (!empty($CHIL[$i])) {
			if (empty($famid)) {
				//print "HERE 7";
				$famid = append_gedrec($famrec);
				$gedrec .= "\r\n1 FAMC @$famid@\r\n";
				$updated = true;
			}
			$famrec .= "\r\n1 CHIL @".$CHIL[$i]."@";
			if (!isset($pgv_changes[$CHIL[$i]."_".$GEDCOM])) $childrec = find_person_record($CHIL[$i]);
			else $childrec = find_updated_record($CHIL[$i]);
			if (preg_match("/1 FAMC @$famid@/", $childrec)==0) {
				$childrec = "\r\n1 FAMC @$famid@";
				replace_gedrec($CHIL[$i], $childrec, $update_CHAN);
			}
			$famupdate = true;
//			print "famupdate6";
		}

		$var = "F".$i."CDEL";
		if (isset($_REQUEST[$var])) $fcdel = $_REQUEST[$var];
		else $fcdel = "";
		if (!empty($fcdel)) {
			$famrec = preg_replace("/1 CHIL @$fcdel@/", "", $famrec);
			$famupdate = true;
//			print "famupdate7";
		}

		//--add new child, name, birth
		$cgivn = "";
		$csurn = "";
		if (isset($_REQUEST["C".$i."GIVN"])) $cgivn = $_REQUEST["C".$i."GIVN"];
		if (isset($_REQUEST["C".$i."SURN"])) $csurn = $_REQUEST["C".$i."SURN"];
		if (!empty($cgivn) || !empty($csurn)) {
			if (empty($famid)) {
				//print "HERE 8";
				$famid = append_gedrec($famrec);
				$gedrec .= "\r\n1 FAMC @$famid@\r\n";
				$updated = true;
			}
			//-- first add the new child
			$childrec = "0 @REF@ INDI\r\n";
			$childrec .= "1 NAME ".$cgivn." /".$csurn."/\r\n";
			if (!empty($cgivn)) $childrec .= "2 GIVN ".$cgivn."\r\n";
			if (!empty($csurn)) $childrec .= "2 SURN ".$csurn."\r\n";
			$hcgivn = "";
			$hcsurn = "";
			if (isset($_REQUEST["HC".$i."GIVN"])) $hcgivn = $_REQUEST["HC".$i."GIVN"];
			if (isset($_REQUEST["HC".$i."SURN"])) $hcsurn = $_REQUEST["HC".$i."SURN"];
			if (!empty($hcgivn) || !empty($hcsurn)) {
				$childrec .= "2 _HEB ".$hcgivn." /".$hcsurn."/\r\n";
			}
			$rsgivn = "";
			$rssurn = "";
			if (isset($_REQUEST["RC".$i."GIVN"])) $rsgivn = $_REQUEST["RC".$i."GIVN"];
			if (isset($_REQUEST["RC".$i."SURN"])) $rssurn = $_REQUEST["RC".$i."SURN"];
			if (!empty($rsgivn) || !empty($rssurn)) {
				$childrec .= "2 ROMN ".$rsgivn." /".$rssurn."/\r\n";
			}
			if (isset($_REQUEST["C".$i."SEX"])) $csex = $_REQUEST["C".$i."SEX"];
			else $csex = "";
			if (!empty($csex)) $childrec .= "1 SEX $csex\r\n";
			//-- child birth
			if (isset($_REQUEST["C".$i."DATE"])) $cdate = $_REQUEST["C".$i."DATE"];
			else $cdate = "";
			$var = "C".$i."PLAC";
			if (isset($_REQUEST[$var])) $cplac = $_REQUEST[$var];
			else $cplac = "";
			if (!empty($cdate)||!empty($cplac)) {
				$childrec .= "1 BIRT\r\n";
				$cdate = check_input_date($cdate);
				if (!empty($cdate)) $childrec .= "2 DATE $cdate\r\n";
				if (!empty($cplac)) $childrec .= "2 PLAC $cplac\r\n";
				$var = "C".$i."RESN";
				if (isset($_REQUEST[$var])) $cresn = $_REQUEST[$var];
				else $cresn = "";
				if (!empty($cresn)) $childrec .= "2 RESN $cresn\r\n";
			}
			//-- child death
			$var = "C".$i."DDATE";
			if (isset($_REQUEST[$var])) $cdate = $_REQUEST[$var];
			else $cdate = "";
			$var = "C".$i."DPLAC";
			if (isset($_REQUEST[$var])) $cplac = $_REQUEST[$var];
			else $cplac = "";
			if (!empty($cdate)||!empty($cplac)) {
				$childrec .= "1 DEAT\r\n";
				$cdate = check_input_date($cdate);
				if (!empty($cdate)) $childrec .= "2 DATE $cdate\r\n";
				if (!empty($cplac)) $childrec .= "2 PLAC $cplac\r\n";
				$var = "C".$i."DRESN";
				if (isset($_REQUEST[$var])) $cresn = $_REQUEST[$var];
				else $cresn = "";
				if (!empty($cresn)) $childrec .= "2 RESN $cresn\r\n";
			}
			$childrec .= "1 FAMC @$famid@\r\n";
			$cxref = append_gedrec($childrec);
			$famrec .= "\r\n1 CHIL @$cxref@";
			$famupdate = true;
//			print "famupdate8";
		}
		if ($famupdate &&($oldfamrec!=$famrec)) {
			$famrec = preg_replace("/0 @(.*)@/", "0 @".$famid."@", $famrec);
//			print $famrec;
			replace_gedrec($famid, $famrec, $update_CHAN);
		}
		$i++;
	}

	if ($updated && empty($error)) {
		print $pgv_lang["update_successful"]."<br />";
		AddToChangeLog("Quick update for $pid by >".PGV_USER_NAME."<");
		//print "<pre>$gedrec</pre>";
		if ($oldgedrec!=$gedrec) replace_gedrec($pid, $gedrec, $update_CHAN);
	}
	if (!empty($error)) {
		print "<span class=\"error\">".$error."</span>";
	}

	if ($closewin) {
		// autoclose window when update successful
		if ($EDIT_AUTOCLOSE && !PGV_DEBUG) {
			print "\n<script type=\"text/javascript\">\n<!--\nif (window.opener.showchanges) window.opener.showchanges(); window.close();\n//-->\n</script>";
		}
		print "<center><br /><br /><br />";
		print "<a href=\"#\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a><br /></center>\n";
		print_simple_footer();
		exit;
	}
}

if ($action!="update") print "<h2>".$pgv_lang["quick_update_title"]."</h2>\n";
print $pgv_lang["quick_update_instructions"]."<br /><br />";

init_calendar_popup();
?>
<script language="JavaScript" type="text/javascript">
<!--
var pastefield;
function paste_id(value) {
	pastefield.value = value;
}

var helpWin;
function helpPopup(which) {
	if ((!helpWin)||(helpWin.closed)) helpWin = window.open('help_text.php?help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
	else helpWin.location = 'help_text.php?help='+which;
	return false;
}
//-->
</script>
<?php
if ($action=="choosepid") {
	?>
	<form method="post" action="edit_quickupdate.php?pid=<?php print $pid;?>" name="quickupdate" enctype="multipart/form-data">
	<input type="hidden" name="action" value="" />
	<table>
	<tr>
		<td><?php print $pgv_lang["enter_pid"]; ?></td>
		<td><input type="text" size="6" name="pid" id="pid" />
		<?php print_findindi_link("pid","");?>
		</td>
	</tr>
	</table>
	<input type="submit" value="<?php print $pgv_lang["continue"]; ?>" />
	</form>
		<?php
	}
	else {
		$SEX = get_gedcom_value("SEX", 1, $gedrec, '', false);
		$child_surname = "";
		//if ($SEX=="M") {
		//	$ct = preg_match("~1 NAME.*/(.*)/~", $gedrec, $match);
		//	if ($ct>0) $child_surname = $match[1];
		//}
	$GIVN = "";
	$SURN = "";
	$MSURN = "";
	$subrec = get_sub_record(1, "1 NAME", $gedrec);
	if (!empty($subrec)) {
		$ct = preg_match("/2 GIVN (.*)/", $subrec, $match);
		if ($ct>0) $GIVN = trim($match[1]);
		else {
			$ct = preg_match("/1 NAME (.*)/", $subrec, $match);
			if ($ct>0) {
				$GIVN = preg_replace("~/.*/~", "", trim($match[1]));
			}
		}
		$ct = preg_match("/2 SURN (.*)/", $subrec, $match);
		if ($ct>0) $SURN = trim($match[1]);
		else {
			$ct = preg_match("/1 NAME (.*)/", $subrec, $match);
			if ($ct>0) {
				$st = preg_match("~/(.*)/~", $match[1], $smatch);
				if ($st>0) $SURN = $smatch[1];
			}
		}
		$ct = preg_match("/2 _MARNM (.*)/", $subrec, $match);
		if ($ct>0) $MSURN = trim($match[1]);
		else {
			$ct = preg_match("/1 NAME (.*)/", $subrec, $match);
			if ($ct>0) {
				$st = preg_match("~/(.*)/~", $match[1], $smatch);
				if ($st>0) $MSURN = $smatch[1];
			}
		}
		$HGIVN = "";
		$HSURN = "";
		$RGIVN = "";
		$RSURN = "";
		$hname = get_gedcom_value("_HEB", 2, $subrec, '', false);
		if (!empty($hname)) {
			$ct = preg_match("~(.*)/(.*)/(.*)~", $hname, $matches);
			if ($ct>0) {
				$HSURN = $matches[2];
				$HGIVN = trim($matches[1]).trim($matches[3]);
			}
			else $HGIVN = $hname;
		}
		$rname = get_gedcom_value("ROMN", 2, $subrec, '', false);
		if (!empty($rname)) {
			$ct = preg_match("~(.*)/(.*)/(.*)~", $rname, $matches);
			if ($ct>0) {
				$RSURN = $matches[2];
				$RGIVN = trim($matches[1]).trim($matches[3]);
			}
			else $RGIVN = $rname;
		}
	}
	$ADDR = "";
	$subrec = get_sub_record(1, "1 ADDR", $gedrec);
	if (!empty($subrec)) {
		$ct = preg_match("/1 ADDR (.*)/", $subrec, $match);
		if ($ct>0) $ADDR = trim($match[1]);
		$ADDR_CONT = get_cont(2, $subrec);
		if (!empty($ADDR_CONT)) $ADDR .= $ADDR_CONT;
		else {
			$_NAME = get_gedcom_value("_NAME", 2, $subrec);
			if (!empty($_NAME)) $ADDR .= "\r\n". $_NAME;
			$ADR1 = get_gedcom_value("ADR1", 2, $subrec);
			if (!empty($ADR1)) $ADDR .= "\r\n". $ADR1;
			$ADR2 = get_gedcom_value("ADR2", 2, $subrec);
			if (!empty($ADR2)) $ADDR .= "\r\n". $ADR2;
			$cityspace = "\r\n";
			if (!$POSTAL_CODE) {
				$POST = get_gedcom_value("POST", 2, $subrec);
				if (!empty($POST)) $ADDR .= "\r\n". $POST;
				else $ADDR .= "\r\n";
				$cityspace = " ";
			}
			$CITY = get_gedcom_value("CITY", 2, $subrec);
			if (!empty($CITY)) $ADDR .= $cityspace. $CITY;
			else $ADDR .= $cityspace;
			$STAE = get_gedcom_value("STAE", 2, $subrec);
			if (!empty($STAE)) $ADDR .= ", ". $STAE;
			if ($POSTAL_CODE) {
				$POST = get_gedcom_value("POST", 2, $subrec);
				if (!empty($POST)) $ADDR .= "  ". $POST;
			}
			$CTRY = get_gedcom_value("CTRY", 2, $subrec);
			if (!empty($CTRY)) $ADDR .= "\r\n". $CTRY;
		}
		/**
		 * @todo add support for ADDR subtags ADR1, CITY, STAE etc
		 */
	}
	$PHON = "";
	$subrec = get_sub_record(1, "1 PHON", $gedrec);
	if (!empty($subrec)) {
		$ct = preg_match("/1 PHON (.*)/", $subrec, $match);
		if ($ct>0) $PHON = trim($match[1]);
		$PHON .= get_cont(2, $subrec);
	}
	$EMAIL = "";
	$ct = preg_match("/1 (_?EMAIL) (.*)/", $gedrec, $match);
	if ($ct>0) {
		$EMAIL = trim($match[2]);
		$subrec = get_sub_record(1, "1 ".$match[1], $gedrec);
		$EMAIL .= get_cont(2, $subrec);
	}
	$FAX = "";
	$subrec = get_sub_record(1, "1 FAX", $gedrec);
	if (!empty($subrec)) {
			$ct = preg_match("/1 FAX (.*)/", $subrec, $match);
			if ($ct>0) $FAX = trim($match[1]);
			$FAX .= get_cont(2, $subrec);
	}

	$indifacts = array();
	$person = Person::getInstance($pid);
    $facts = $person->getIndiFacts();
	$repeat_tags = array();

    foreach($facts as $event) {
    	$fact = $event->getTag();
		if ($fact=="EVEN" || $fact=="FACT") $fact = $event->getType();
		if (in_array($fact, $addfacts)) {
			if (!isset($repeat_tags[$fact])) $repeat_tags[$fact]=1;
			else $repeat_tags[$fact]++;
			$newreqd = array();
			foreach($reqdfacts as $r=>$rfact) {
				if ($rfact!=$fact) $newreqd[] = $rfact;
			}
			$reqdfacts = $newreqd;
            $indifacts[] = $event;
		}
	}
	foreach($reqdfacts as $ind=>$fact) {
		$e = new Event("1 $fact\r\n");
        $e->temp = true;
        $indifacts[] = $e;
	}

	sort_facts($indifacts);
	$sfams = find_families_in_record($gedrec, "FAMS");
	$cfams = find_families_in_record($gedrec, "FAMC");
	if (count($cfams)==0) $cfams[] = "";

	$tabkey = 1;
	$person=Person::getInstance($pid);
	echo '<b>', PrintReady($person->getFullName());
	if ($SHOW_ID_NUMBERS) {
		echo PrintReady("&nbsp;&nbsp;(".$pid.")");
	}
	echo '</b><br />';
?>
<script language="JavaScript" type="text/javascript">
<!--
var tab_count = <?php print (count($sfams)+count($cfams)); ?>;
function switch_tab(tab) {
	for(i=0; i<=tab_count+1; i++) {
		var pagetab = document.getElementById('pagetab'+i);
		var pagetabbottom = document.getElementById('pagetab'+i+'bottom');
		var tabdiv = document.getElementById('tab'+i);
		if (i==tab) {
			pagetab.className='tab_cell_active';
			tabdiv.style.display = 'block';
			pagetabbottom.className='tab_active_bottom';
		}
		else {
			pagetab.className='tab_cell_inactive';
			tabdiv.style.display = 'none';
			pagetabbottom.className='tab_inactive_bottom';
		}
	}
}

function checkform(frm) {
	if (frm.EMAIL) {
		if ((frm.EMAIL.value!="") &&
			((frm.EMAIL.value.indexOf("@")==-1) ||
			(frm.EMAIL.value.indexOf("<")!=-1) ||
			(frm.EMAIL.value.indexOf(">")!=-1))) {
			alert("<?php print $pgv_lang["enter_email"]; ?>");
			frm.EMAIL.focus();
			return false;
		}
	}
	return true;
}
//-->
</script>
<form method="post" action="edit_quickupdate.php?pid=<?php print $pid;?>" name="quickupdate" enctype="multipart/form-data" onsubmit="return checkform(this);">
<input type="hidden" name="action" value="update" />
<input type="hidden" name="closewin" value="1" />
<br /><input type="submit" value="<?php print $pgv_lang["save"]; ?>" /><br /><br />
<table class="tabs_table">
	<tr>
		<td id="pagetab0" class="tab_cell_active"><a href="javascript: <?php print $pgv_lang["personal_facts"];?>" onclick="switch_tab(0); return false;"><?php print $pgv_lang["personal_facts"]?></a></td>
		<?php
		for($i=1; $i<=count($sfams); $i++) {
			$famid = $sfams[$i-1];
			if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid);
			else $famrec = find_updated_record($famid);
			$parents = find_parents_in_record($famrec);
			$spid = "";
			if($parents) {
				if($pid!=$parents["HUSB"]) $spid=$parents["HUSB"];
				else $spid=$parents["WIFE"];
			}
			print "<td id=\"pagetab$i\" class=\"tab_cell_inactive\" onclick=\"switch_tab($i); return false;\"><a href=\"javascript: ".$pgv_lang["family_with"]."&nbsp;";
			$person=Person::getInstance($spid);
			if ($person) {
				print PrintReady(strip_tags($person->getFullName()));
				print "\" onclick=\"switch_tab($i); return false;\">".$pgv_lang["family_with"]." ";
				print PrintReady($person->getFullName());
			} else {
				print "\" onclick=\"switch_tab($i); return false;\">".$pgv_lang["family_with"]." ".$pgv_lang["unknown"];
			}
			print "</a></td>\n";
		}
		?>
		<td id="pagetab<?php echo $i; ?>" class="tab_cell_inactive" onclick="switch_tab(<?php echo $i; ?>); return false;"><a href="javascript: <?php print $pgv_lang["add_new_wife"];?>" onclick="switch_tab(<?php echo $i; ?>); return false;">
		<?php if (preg_match("/1 SEX M/", $gedrec)>0) print $pgv_lang["add_new_wife"]; else print $pgv_lang["add_new_husb"]; ?></a></td>
		<?php
		$i++;
		for($j=1; $j<=count($cfams); $j++) {
			print "<td id=\"pagetab$i\" class=\"tab_cell_inactive\" onclick=\"switch_tab($i); return false;\"><a href=\"#\" onclick=\"switch_tab($i); return false;\">".$pgv_lang["as_child"];
			print "</a></td>\n";
			$i++;
		}
		?>
		</tr>
		<tr>
			<td id="pagetab0bottom" class="tab_active_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" alt="" /></td>
			<?php
		for($i=1; $i<=count($sfams); $i++) {
			print "<td id=\"pagetab{$i}bottom\" class=\"tab_inactive_bottom\"><img src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["spacer"]["other"]."\" width=\"1\" height=\"1\" alt=\"\" /></td>\n";
		}
		for($j=1; $j<=count($cfams); $j++) {
			print "<td id=\"pagetab{$i}bottom\" class=\"tab_inactive_bottom\"><img src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["spacer"]["other"]."\" width=\"1\" height=\"1\" alt=\"\" /></td>\n";
			$i++;
		}
		?>
			<td id="pagetab<?php echo $i; ?>bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" alt="" /></td>
			<td class="tab_inactive_bottom_right" style="width:10%;"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" alt="" /></td>
	</tr>
</table>
<div id="tab0">
<table class="<?php print $TEXT_DIRECTION; ?> width80">
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_name_help", "qm"); ?><?php print $pgv_lang["update_name"]; ?></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SURN" value="<?php print PrintReady(htmlspecialchars($SURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="GIVN" value="<?php print PrintReady(htmlspecialchars($GIVN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SURN" value="<?php print PrintReady(htmlspecialchars($SURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["_MARNM"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="MSURN" value="<?php print PrintReady(htmlspecialchars($MSURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSURN" value="<?php print PrintReady(htmlspecialchars($HSURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HGIVN" value="<?php print PrintReady(htmlspecialchars($HGIVN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSURN" value="<?php print PrintReady(htmlspecialchars($HSURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSURN" value="<?php print PrintReady(htmlspecialchars($RSURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RGIVN" value="<?php print PrintReady(htmlspecialchars($RGIVN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSURN" value="<?php print PrintReady(htmlspecialchars($RSURN,ENT_COMPAT,'UTF-8')); ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="GENDER" tabindex="<?php print $tabkey; ?>">
			<option value="M"<?php if ($SEX=="M") print " selected=\"selected\""; ?>><?php print $pgv_lang["male"]; ?></option>
			<option value="F"<?php if ($SEX=="F") print " selected=\"selected\""; ?>><?php print $pgv_lang["female"]; ?></option>
			<option value="U"<?php if ($SEX=="U") print " selected=\"selected\""; ?>><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php
// NOTE: Update fact
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_fact_help", "qm"); print $pgv_lang["update_fact"]; ?></td></tr>
<tr>
	<td class="descriptionbox">&nbsp;</td>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"]; ?></td>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"]; ?></td>
	<td class="descriptionbox"><?php print $pgv_lang["delete"]; ?></td>
</tr>
<?php
foreach($indifacts as $f=>$fact) {
	$fact_tag = $fact->getTag();
	$fact_num = $fact->getLineNumber();
    $date = $fact->getValue("DATE");
    $plac = $fact->getPlace();
    $temp = $fact->getValue("TEMP");
    $desc = $fact->getDetail();
	?>
<tr>
	<td class="descriptionbox">
		<?php print $fact->getLabel();
		?>
		<input type="hidden" name="TAGS[]" value="<?php echo $fact_tag; ?>" />
		<input type="hidden" name="NUMS[]" value="<?php echo $fact_num; ?>" />
	</td>
	<?php if (!in_array($fact_tag, $emptyfacts)) { ?>
	<td class="optionbox" colspan="2">
		<input type="text" name="DESCS[]" size="40" value="<?php print PrintReady(htmlspecialchars($desc,ENT_COMPAT,'UTF-8')); ?>" />
		<input type="hidden" name="DATES[]" value="<?php print htmlspecialchars($date,ENT_COMPAT,'UTF-8'); ?>" />
		<input type="hidden" name="PLACS[]" value="<?php print htmlspecialchars($plac,ENT_COMPAT,'UTF-8'); ?>" />
		<input type="hidden" name="TEMPS[]" value="<?php print htmlspecialchars($temp,ENT_COMPAT,'UTF-8'); ?>" />
	</td>
	<?php }	else {
		if (!in_array($fact_tag, $nondatefacts)) { ?>
			<td class="optionbox">
				<input type="hidden" name="DESCS[]" value="<?php print htmlspecialchars($desc,ENT_COMPAT,'UTF-8'); ?>" />
				<input type="text" dir="ltr" tabindex="<?php print $tabkey; $tabkey++;?>" size="15" name="DATES[]" id="DATE<?php echo $f; ?>" onblur="valid_date(this);" value="<?php echo PrintReady(htmlspecialchars($date,ENT_COMPAT,'UTF-8')); ?>" />&nbsp;<?php print_calendar_popup("DATE$f");?>
			</td>
		<?php }
		if (empty($temp) && (!in_array($fact_tag, $nonplacfacts))) { ?>
			<td class="optionbox">
				<input type="text" size="30" tabindex="<?php print $tabkey; $tabkey++; ?>" name="PLACS[]" id="place<?php echo $f; ?>" value="<?php print PrintReady(htmlspecialchars($plac,ENT_COMPAT,'UTF-8')); ?>" />
				<?php print_findplace_link("place$f"); ?>
				<input type="hidden" name="TEMPS[]" value="" />
			</td>
		<?php
		}
		else {
			print "<td class=\"optionbox\"><select tabindex=\"".$tabkey."\" name=\"TEMPS[]\" >\n";
			print "<option value=''>".$pgv_lang["no_temple"]."</option>\n";
			foreach($TEMPLE_CODES as $code=>$temple) {
				print "<option value=\"$code\"";
				if ($code==$temp) print " selected=\"selected\"";
				print ">$temple</option>\n";
			}
			print "</select>\n";
			print "<input type=\"hidden\" name=\"PLACS[]\" value=\"\" />\n";
			print "</td>\n";
			$tabkey++;
		}
	}
	if (!$fact->temp) { ?>
		<td class="optionbox center">
			<input type="hidden" name="REMS[<?php echo $f; ?>]" id="REM<?php echo $f; ?>" value="0" />
			<a href="javascript: <?php print $pgv_lang["delete"]; ?>" onclick="if (confirm('<?php print $pgv_lang["check_delete"]; ?>')) { document.quickupdate.closewin.value='0'; document.quickupdate.REM<?php echo $f; ?>.value='1'; document.quickupdate.submit(); } return false;">
				<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"]; ?>" border="0" alt="<?php print $pgv_lang["delete"]; ?>" />
			</a>
		</td>
	</tr>
	<?php }
	else {?>
		<td class="optionbox">&nbsp;</td>
	</tr>
	<?php }
	if ($SHOW_QUICK_RESN) {
		print_quick_resn("RESNS[]");
	}
}

// NOTE: Add fact
if (count($addfacts)>0) { ?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_fact_help", "qm"); print $pgv_lang["add_fact"]; ?></td></tr>
<tr>
	<td class="descriptionbox">&nbsp;</td>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"]; ?></td>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"]; ?></td>
	<td class="descriptionbox">&nbsp;</td>
	</tr>
<tr><td class="optionbox">
	<script language="JavaScript" type="text/javascript">
	<!--
	function checkDesc(newfactSelect) {
		if (newfactSelect.selectedIndex==0) return;
		var fact = newfactSelect.options[newfactSelect.selectedIndex].value;
		var emptyfacts = "<?php foreach($emptyfacts as $ind=>$efact) print $efact.","; ?>";
		descFact = document.getElementById('descFact');
		if (!descFact) return;
		if (emptyfacts.indexOf(fact)!=-1) {
			descFact.style.display='none';
		}
		else {
			descFact.style.display='block';
		}
	}
	//-->
	</script>
	<select name="newfact" tabindex="<?php print $tabkey; ?>" onchange="checkDesc(this);">
		<option value=""><?php print $pgv_lang["select_fact"]; ?></option>
	<?php $tabkey++; ?>
	<?php
	foreach($addfacts as $indexval => $fact) {
		$found = false;
		foreach($indifacts as $ind=>$value) {
			if ($fact==$value->getTag()) {
				$found=true;
				break;
			}
		}
		if (!$found) print "\t\t<option value=\"$fact\">".$factarray[$fact]."</option>\n";
	}
	?>
		</select>
		<div id="descFact" style="display:none;"><br />
			<?php print $pgv_lang["description"]; ?><input type="text" size="35" name="DESC" />
		</div>
	</td>
	<td class="optionbox"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="DATE" id="DATE" onblur="valid_date(this);" />&nbsp;<?php print_calendar_popup("DATE");?></td>
	<?php $tabkey++; ?>
	<td class="optionbox"><input type="text" tabindex="<?php print $tabkey; ?>" name="PLAC" id="place" />
	<?php print_findplace_link("place"); ?>
	</td>
	<td class="optionbox">&nbsp;</td></tr>
	<?php $tabkey++; ?>
	<?php print_quick_resn("RESN"); ?>
<?php }

// NOTE: Add photo
if ($MULTI_MEDIA && (is_writable($MEDIA_DIRECTORY))) { ?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><b><?php print_help_link("quick_update_photo_help", "qm"); print $pgv_lang["update_photo"]; ?></b></td></tr>
<tr>
	<td class="descriptionbox">
		<?php print $factarray["TITL"]; ?>
	</td>
	<td class="optionbox" colspan="3">
		<input type="text" tabindex="<?php print $tabkey; ?>" name="TITL" size="40" />
	</td>
	<?php $tabkey++; ?>
</tr>
<tr>
	<td class="descriptionbox">
		<?php print $factarray["FILE"]; ?>
	</td>
	<td class="optionbox" colspan="3">
		<input type="file" tabindex="<?php print $tabkey; ?>" name="FILE" size="40" />
	</td>
	<?php $tabkey++; ?>
</tr>
<?php if (preg_match("/1 OBJE/", $gedrec)>0) { ?>
<tr>
	<td class="descriptionbox">&nbsp;</td>
	<td class="optionbox" colspan="3">
		<input type="checkbox" tabindex="<?php print $tabkey; ?>" name="replace" value="yes" /> <?php print $pgv_lang["photo_replace"]; ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<?php } ?>
<?php }

// Address update
if ($person && !$person->isDead() || !empty($ADDR) || !empty($PHON) || !empty($FAX) || !empty($EMAIL)) { //-- don't show address for dead people
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_address_help", "qm"); print $pgv_lang["update_address"]; ?></td></tr>
<tr>
	<td class="descriptionbox">
		<?php print $factarray["ADDR"]; ?>
	</td>
	<td class="optionbox" colspan="3">
		<?php if (!empty($CITY)&&!empty($POST)) { ?>
			<?php  if (empty($ADDR)) { ?><input type="hidden" name="ADDR" value="<?php print PrintReady(htmlspecialchars(strip_tags($ADDR),ENT_COMPAT,'UTF-8')); ?>" /><?php } ?>
			<table>
			<?php if (!empty($_NAME)) { ?><tr><td><?php print $factarray["NAME"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($_NAME)) print "dir=\"ltr\""; ?> name="_NAME" size="35" value="<?php print PrintReady(htmlspecialchars(strip_tags($_NAME),ENT_COMPAT,'UTF-8')); ?>" /></td></tr><?php } ?>
			<?php  if (!empty($ADDR)) { ?><tr><td><?php print $factarray["ADDR"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($ADR1)) print "dir=\"ltr\""; ?> name="ADDR" size="35" value="<?php print PrintReady(htmlspecialchars(strip_tags($ADDR),ENT_COMPAT,'UTF-8')); ?>" /></td></tr><?php } ?>
			<tr><td><?php print $factarray["ADR1"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($ADR1)) print "dir=\"ltr\""; ?> name="ADR1" size="35" value="<?php print PrintReady(htmlspecialchars(strip_tags($ADR1),ENT_COMPAT,'UTF-8')); ?>" /></td></tr>
			<tr><td><?php print $factarray["ADR2"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($ADR2)) print "dir=\"ltr\""; ?> name="ADR2" size="35" value="<?php print PrintReady(htmlspecialchars(strip_tags($ADR2),ENT_COMPAT,'UTF-8')); ?>" /></td></tr>
			<tr><td><?php print $factarray["CITY"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($CITY)) print "dir=\"ltr\""; ?> name="CITY" value="<?php print PrintReady(htmlspecialchars(strip_tags($CITY),ENT_COMPAT,'UTF-8')); ?>" />
			<?php print $factarray["STAE"]; ?> <input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($STAE)) print "dir=\"ltr\""; ?> name="STAE" value="<?php print PrintReady(htmlspecialchars(strip_tags($STAE),ENT_COMPAT,'UTF-8')); ?>" /></td></tr>
			<tr><td><?php print $factarray["POST"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($POST)) print "dir=\"ltr\""; ?> name="POST" value="<?php print PrintReady(htmlspecialchars(strip_tags($POST),ENT_COMPAT,'UTF-8')); ?>" /></td></tr>
			<tr><td><?php print $factarray["CTRY"]; ?></td><td><input type="text" <?php if ($TEXT_DIRECTION=="rtl" && !hasRTLText($CTRY)) print "dir=\"ltr\""; ?> name="CTRY" value="<?php print PrintReady(htmlspecialchars(strip_tags($CTRY),ENT_COMPAT,'UTF-8')); ?>" /></td></tr>
			</table>

		<?php } else { ?>
		<textarea name="ADDR" tabindex="<?php print $tabkey; ?>" cols="35" rows="4"><?php print PrintReady(htmlspecialchars(strip_tags($ADDR),ENT_COMPAT,'UTF-8')); ?></textarea>
		<?php } ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<tr>
	<td class="descriptionbox">
		<?php print $factarray["PHON"]; ?>
	</td>
	<td class="optionbox" colspan="3">
		<input type="text" dir="ltr" tabindex="<?php print $tabkey; $tabkey++; ?>" name="PHON" size="20" value="<?php print PrintReady(htmlspecialchars($PHON,ENT_COMPAT,'UTF-8')); ?>" />
	</td>
</tr>
<tr>
		<td class="descriptionbox">
				<?php print $factarray["FAX"]; ?>
		</td>
		<td class="optionbox" colspan="3">
				<input type="text" dir="ltr" tabindex="<?php print $tabkey; $tabkey++; ?>" name="FAX" size="20" value="<?php print PrintReady(htmlspecialchars($FAX,ENT_COMPAT,'UTF-8')); ?>" />
	</td>
</tr>
<tr>
	<td class="descriptionbox">
		<?php print $factarray["EMAIL"]; ?>
	</td>
	<td class="optionbox" colspan="3">
		<input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" name="EMAIL" size="40" value="<?php print PrintReady(htmlspecialchars($EMAIL,ENT_COMPAT,'UTF-8')); ?>" />
	</td>
	<?php $tabkey++; ?>
</tr>
<tr><td colspan="4"><br /></td></tr>
<?php } ?>
</table>
</div>

<?php
//------------------------------------------- FAMILY WITH SPOUSE TABS ------------------------
for($i=1; $i<=count($sfams); $i++) {
	?>
<div id="tab<?php echo $i; ?>" style="display: none;">
<table class="<?php print $TEXT_DIRECTION; ?> width80">
<tr><td class="topbottombar" colspan="4">
<?php
	$famreqdfacts = preg_split("/[,; ]/", $QUICK_REQUIRED_FAMFACTS);
	$famid = $sfams[$i-1];
	$family=Family::getInstance($famid);
	$famrec = $family->getGedcomRecord();
	if (isset($pgv_changes[$famid."_".$GEDCOM])) {
		$famrec = find_updated_record($famid);
		$family = new Family($famrec);
	}
	print $pgv_lang["family_with"]." ";
	$parents = find_parents_in_record($famrec);
	$spid = "";
	if($parents) {
		if($pid!=$parents["HUSB"]) $spid=$parents["HUSB"];
		else $spid=$parents["WIFE"];
	}
	$person=Person::getInstance($spid);
	if ($person) {
		print "<a href=\"#\" onclick=\"return quickEdit('".$person->getXref()."','','{$GEDCOM}');\">";
		$name = PrintReady($person->getFullName());
		if ($SHOW_ID_NUMBERS) $name .= " (".$person->getXref().")";
		$name .= " [".$pgv_lang["edit"]."]";
		print $name."</a>\n";
	}
	else print $pgv_lang["unknown"];
    $subrecords = $family->getFacts(array("HUSB","WIFE","CHIL"));
	$famfacts = array();
	foreach($subrecords as $ind=>$eventObj) {
        $fact = $eventObj->getTag();
        $event = $eventObj->getDetail();
		if ($fact=="EVEN" || $fact=="FACT") $fact = $eventObj->getValue("TYPE");
		if (in_array($fact, $famaddfacts)) {
			$newreqd = array();
			foreach($famreqdfacts as $rfact) {
				if ($rfact!=$fact) $newreqd[] = $rfact;
			}
			$famreqdfacts = $newreqd;
			$famfacts[] = $eventObj;
		}
	}

//	foreach($reqdfacts as $ind=>$fact) {
	foreach($famreqdfacts as $fact) {
    	$e = new Event("1 $fact\r\n");
        $e->temp = true;
        $famfacts[] = $e;
	}
	sort_facts($famfacts);
?>
</td></tr>
<tr>
	<td class="descriptionbox"><?php print $pgv_lang["enter_pid"]; ?></td>
	<td class="optionbox" colspan="3"><input type="text" size="10" name="SPID[<?php echo $i; ?>]" id="SPID<?php echo $i; ?>" value="<?php echo $spid; ?>" />
		<?php print_findindi_link("SPID$i","");?>
		</td>
	</tr>
<?php if (empty($spid)) { ?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_spouse_help", "qm"); if (preg_match("/1 SEX M/", $gedrec)>0) print $pgv_lang["add_new_wife"]; else print $pgv_lang["add_new_husb"];?></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["_MARNM"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="MSSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="SSEX<?php echo $i; ?>" tabindex="<?php print $tabkey; ?>">
			<option value="M"<?php if (preg_match("/1 SEX F/", $gedrec)>0) print " selected=\"selected\""; ?>><?php print $pgv_lang["male"]; ?></option>
			<option value="F"<?php if (preg_match("/1 SEX M/", $gedrec)>0) print " selected=\"selected\""; ?>><?php print $pgv_lang["female"]; ?></option>
			<option value="U"<?php if (preg_match("/1 SEX U/", $gedrec)>0) print " selected=\"selected\""; ?>><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	<?php $tabkey++; ?>
	</td>
</tr>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?><?php print $factarray["DATE"];?></td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="BDATE<?php echo $i; ?>" id="BDATE<?php echo $i; ?>" onblur="valid_date(this);" /><?php print_calendar_popup("BDATE$i");?></td>
	</tr>
	<?php $tabkey++; ?>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="BPLAC<?php echo $i; ?>" id="bplace<?php echo $i; ?>" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("bplace$i"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?><?php print $factarray["DATE"];?></td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="DDATE<?php echo $i; ?>" id="DDATE<?php echo $i; ?>" onblur="valid_date(this);" /><?php print_calendar_popup("DDATE$i");?></td>
	</tr>
	<?php $tabkey++; ?>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="DPLAC<?php echo $i; ?>" id="dplace<?php echo $i; ?>" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("dplace$i"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php print_quick_resn("BRESN".$i);
}
//NOTE: Update fact
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_fact_help", "qm"); print $pgv_lang["update_fact"]; ?></td></tr>
<tr>
	<td class="descriptionbox">&nbsp;</td>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"]; ?></td>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"]; ?></td>
	<td class="descriptionbox"><?php print $pgv_lang["delete"]; ?></td>
	</tr>
<?php
foreach($famfacts as $f=>$eventObj) {
        $fact_tag = $eventObj->getTag();
        $date = $eventObj->getValue("DATE");
        $plac = $eventObj->getValue("PLAC");
        $temp = $eventObj->getValue("TEMP");
	?>
			<tr>
				<td class="descriptionbox">
				<?php if (isset($factarray[$fact_tag])) print $factarray[$fact_tag];
					else if (isset($pgv_lang[$fact_tag])) print $pgv_lang[$fact_tag];
					else print $fact_tag;
				?>
					<input type="hidden" name="F<?php echo $i; ?>TAGS[]" value="<?php echo $fact_tag; ?>" />
				</td>
				<td class="optionbox"><input type="text" dir="ltr" tabindex="<?php print $tabkey; $tabkey++;?>" size="15" name="F<?php echo $i; ?>DATES[]" id="F<?php echo $i; ?>DATE<?php echo $f; ?>" onblur="valid_date(this);" value="<?php echo PrintReady(htmlspecialchars($date,ENT_COMPAT,'UTF-8')); ?>" /><?php print_calendar_popup("F{$i}DATE{$f}");?></td>
				<?php if (empty($temp) && (!in_array($fact_tag, $nonplacfacts))) { ?>
					<td class="optionbox"><input type="text" size="30" tabindex="<?php print $tabkey; $tabkey++; ?>" name="F<?php echo $i; ?>PLACS[]" id="F<?php echo $i; ?>place<?php echo $f; ?>" value="<?php print PrintReady(htmlspecialchars($plac,ENT_COMPAT,'UTF-8')); ?>" />
					<?php print_findplace_link("F{$i}place{$f}"); ?>
					</td>
				<?php }
				else {
					print "<td class=\"optionbox\"><select tabindex=\"".$tabkey."\" name=\"F".$i."TEMP[]\" >\n";
					print "<option value=''>".$pgv_lang["no_temple"]."</option>\n";
					foreach($TEMPLE_CODES as $code=>$temple) {
						print "<option value=\"$code\"";
						if ($code==$temp) print " selected=\"selected\"";
						print ">$temple</option>\n";
					}
					print "</select>\n</td>\n";
					$tabkey++;
				}
				?>
				<td class="optionbox center">
					<input type="hidden" name="F<?php echo $i; ?>REMS[<?php echo $f; ?>]" id="F<?php echo $i; ?>REM<?php echo $f; ?>" value="0" />
					<?php if ($date!='' || $plac!='' || $temp!='') { ?>
					<a href="javascript: <?php print $pgv_lang["delete"]; ?>" onclick="if (confirm('<?php print $pgv_lang["check_delete"]; ?>')) { document.quickupdate.closewin.value='0'; document.quickupdate.F<?php echo $i; ?>REM<?php echo $f; ?>.value='1'; document.quickupdate.submit(); } return false;">
						<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"]; ?>" border="0" alt="<?php print $pgv_lang["delete"]; ?>" />
					</a>
					<?php } ?>
				</td>
			</tr>
			<?php if ($SHOW_QUICK_RESN) {
				print_quick_resn("F".$i."RESNS[]");
			} ?>
	<?php
}
// Note: add fact
if (count($famaddfacts)>0) { ?>
	<tr><td>&nbsp;</td></tr>
	<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_fact_help", "qm"); print $pgv_lang["add_fact"]; ?></td></tr>
	<tr>
	<td class="descriptionbox">&nbsp;</td>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"]; ?></td>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"]; ?></td>
	<td class="descriptionbox">&nbsp;</td>
	</tr>
	<tr>
	<td class="optionbox"><select name="F<?php echo $i; ?>newfact" tabindex="<?php print $tabkey; ?>">
		<option value=""><?php print $pgv_lang["select_fact"]; ?></option>
	<?php $tabkey++; ?>
	<?php
	foreach($famaddfacts as $indexval => $fact) {
		$found = false;
		foreach($famfacts as $ind=>$value) {
			if ($fact==$value->getTag()) {
				$found=true;
				break;
			}
		}
		if (!$found) print "\t\t<option value=\"$fact\">".$factarray[$fact]."</option>\n";
	}
	?>
		</select>
	</td>
	<td class="optionbox"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="F<?php echo $i; ?>DATE" id="F<?php echo $i; ?>DATE" onblur="valid_date(this);" /><?php print_calendar_popup("F".$i."DATE");?></td>
	<?php $tabkey++; ?>
	<td class="optionbox"><input type="text" tabindex="<?php print $tabkey; ?>" name="F<?php echo $i; ?>PLAC" id="F<?php echo $i; ?>place" />
	<?php print_findplace_link("F".$i."place"); ?>
	</td>
	<?php $tabkey++; ?>
	<td class="optionbox">&nbsp;</td>
	</tr>
	<?php print_quick_resn("F".$i."RESN"); ?>
<?php }
// NOTE: Children
$chil = find_children_in_record($famrec);
	?>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="topbottombar" colspan="4"><?php print $pgv_lang["children"]; ?></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print $pgv_lang["name"]; ?></td>
		<td class="descriptionbox center"><?php print $factarray["SEX"]; ?></td>
		<td class="descriptionbox"><?php print $factarray["BIRT"]; ?></td>
		<td class="descriptionbox"><input type="hidden" name="F<?php echo $i; ?>CDEL" value="" /><?php print $pgv_lang["remove"]; ?></td>
	</tr>
			<?php
				foreach($chil as $c=>$child) {
					$person=Person::getInstance($child);
					print "<tr><td class=\"optionbox\">";
					$name = $person->getFullName();
					if ($SHOW_ID_NUMBERS) $name .= " (".$child.")";
					$name .= " [".$pgv_lang["edit"]."]";
					print "<a href=\"#\" onclick=\"return quickEdit('".$child."','','{$GEDCOM}');\">";
					print PrintReady($name);
					print "</a>";
					$childrec = find_person_record($child);
					print "</td>\n<td class=\"optionbox center\">";
					if ($disp) {
						print get_gedcom_value("SEX", 1, $childrec);
					}
					print "</td>\n<td class=\"optionbox\">";
					if ($disp) {
						$birtrec = get_sub_record(1, "1 BIRT", $childrec);
						if (!empty($birtrec)) {
							if (showFact("BIRT", $child) && !FactViewRestricted($child, $birtrec)) {
								print get_gedcom_value("DATE", 2, $birtrec);
								print " -- ";
								print get_gedcom_value("PLAC", 2, $birtrec);
							}
						}
					}
					print "</td>\n";
					?>
					<td class="optionbox center">
						<a href="javascript: <?php print $pgv_lang["remove_child"]; ?>" onclick="if (confirm('<?php print $pgv_lang["confirm_remove"]; ?>')) { document.quickupdate.closewin.value='0'; document.quickupdate.F<?php echo $i; ?>CDEL.value='<?php echo $child; ?>'; document.quickupdate.submit(); } return false;">
							<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"]; ?>" border="0" alt="<?php print $pgv_lang["remove_child"]; ?>" />
						</a>
					</td>
					<?php
					print "</tr>\n";
				}
			?>
			<tr>
				<td class="descriptionbox"><?php print $pgv_lang["add_new_chil"]; ?></td>
				<td class="optionbox" colspan="3"><input type="text" size="10" name="CHIL[]" id="CHIL<?php echo $i; ?>" />
						<?php print_findindi_link("CHIL$i","");?>
				</td>
			</tr>
<?php
// NOTE: Add a child
if (empty($child_surname)) $child_surname = "";
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><b><?php print_help_link("quick_update_child_help", "qm"); print $pgv_lang["add_new_chil"]; ?></b></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>SURN" value="<?php if (!empty($child_surname)) print $child_surname; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>GIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>SURN" value="<?php if (!empty($child_surname)) print $child_surname; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HC<?php echo $i; ?>GIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RC<?php echo $i; ?>GIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="C<?php echo $i; ?>SEX" tabindex="<?php print $tabkey; ?>">
			<option value="M"><?php print $pgv_lang["male"]; ?></option>
			<option value="F"><?php print $pgv_lang["female"]; ?></option>
			<option value="U"><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	</td></tr>
	<?php $tabkey++; ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="C<?php echo $i; ?>DATE" id="C<?php echo $i; ?>DATE" onblur="valid_date(this);" /><?php print_calendar_popup("C{$i}DATE");?></td>
	<?php $tabkey++; ?>
	</tr>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>PLAC" id="c<?php echo $i; ?>place" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("c".$i."place"); ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="C<?php echo $i; ?>DDATE" id="C<?php echo $i; ?>DDATE" onblur="valid_date(this);" /><?php print_calendar_popup("C{$i}DDATE");?></td>
	<?php $tabkey++; ?>
	</tr>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>DPLAC" id="c<?php echo $i; ?>dplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("c".$i."dplace"); ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<?php print_quick_resn("C".$i."RESN"); ?>
<tr><td colspan="4"><br /></td></tr>
</table>
</div>
	<?php
}

//------------------------------------------- NEW SPOUSE TAB ------------------------
?>
<div id="tab<?php echo $i; ?>" style="display: none;">
<table class="<?php print $TEXT_DIRECTION;?> width80">
<?php
// NOTE: New wife
?>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_spouse_help", "qm"); if (preg_match("/1 SEX M/", $gedrec)>0) print $pgv_lang["add_new_wife"]; else print $pgv_lang["add_new_husb"]; ?></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SGIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="SSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["_MARNM"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="MSSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSGIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HSSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSGIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RSSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="SSEX" tabindex="<?php print $tabkey; ?>">
			<option value="M"<?php if (preg_match("/1 SEX F/", $gedrec)>0) print " selected=\"selected\""; ?>><?php print $pgv_lang["male"]; ?></option>
			<option value="F"<?php if (preg_match("/1 SEX M/", $gedrec)>0) print " selected=\"selected\""; ?>><?php print $pgv_lang["female"]; ?></option>
			<option value="U"<?php if (preg_match("/1 SEX U/", $gedrec)>0) print " selected=\"selected\""; ?>><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	<?php $tabkey++; ?>
	</td>
</tr>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="BDATE" id="BDATE" onblur="valid_date(this);" /><?php print_calendar_popup("BDATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="BPLAC" id="bplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("bplace"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php print_quick_resn("BRESN"); ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="DDATE" id="DDATE" onblur="valid_date(this);" /><?php print_calendar_popup("DDATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="DPLAC" id="dplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("dplace"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php print_quick_resn("DRESN");

// NOTE: Marriage
?>
<tr><td>&nbsp;</td></tr>
<tr>
	<td class="topbottombar" colspan="4"><?php print_help_link("quick_update_marriage_help", "qm"); print $factarray["MARR"]; ?></td>
</tr>
<tr><td class="descriptionbox">
	<?php print_help_link("quick_update_marriage_help", "qm"); print $factarray["MARR"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="checkbox" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="MARRY" id="MARRY">
		<label for="MARRY"><?php print $pgv_lang["yes"]; ?></label></td>
	</tr>
	<?php $tabkey++; ?>
	<tr><td class="descriptionbox">
		<?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="MDATE" id="MDATE" onblur="valid_date(this);" /><?php print_calendar_popup("MDATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="MPLAC" id="mplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" name="manchor1x" id="manchor1x" alt="" />
	<?php print_findplace_link("mplace"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php print_quick_resn("MRESN");

// NOTE: New child
if (empty($child_surname)) $child_surname = "";
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><b><?php print_help_link("quick_update_child_help", "qm"); print $pgv_lang["add_new_chil"]; ?></b></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="CSURN" value="<?php if (!empty($child_surname)) print $child_surname; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="CGIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="CSURN" value="<?php if (!empty($child_surname)) print $child_surname; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HCSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HCGIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HCSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RCSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_name_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RCGIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RCSURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="CSEX" tabindex="<?php print $tabkey; ?>">
			<option value="M"><?php print $pgv_lang["male"]; ?></option>
			<option value="F"><?php print $pgv_lang["female"]; ?></option>
			<option value="U"><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	</td></tr>
	<?php $tabkey++; ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="CDATE" id="CDATE" onblur="valid_date(this);" /><?php print_calendar_popup("CDATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="CPLAC" id="cplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("cplace"); ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<?php print_quick_resn("CRESN"); ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="CDDATE" id="CDDATE" onblur="valid_date(this);" /><?php print_calendar_popup("CDDATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="CDPLAC" id="cdplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("cdplace"); ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<?php print_quick_resn("CDRESN"); ?>
</table>
</div>

<?php //------------------------------------------- FAMILY AS CHILD TABS ------------------------
$i++;
for($j=1; $j<=count($cfams); $j++) {
	?>
<div id="tab<?php echo $i; ?>" style="display: none;">
<table class="<?php print $TEXT_DIRECTION; ?> width80">
<?php
	$famreqdfacts = preg_split("/[,; ]/", $QUICK_REQUIRED_FAMFACTS);
	$parents = find_parents($cfams[$j-1]);
	$famid = $cfams[$j-1];
	$family=Family::getInstance($famid);
	if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid);
	else $famrec = find_updated_record($famid);

	if ($family) $subrecords = $family->getFacts(array("HUSB","WIFE","CHIL"));
	else $subrecords=array();
	$famfacts = array();
	foreach($subrecords as $ind=>$eventObj) {
		$fact = $eventObj->getTag();
		$event = $eventObj->getDetail();
		if ($fact=="EVEN" || $fact=="FACT") $fact = $eventObj->getValue("TYPE");

		if (in_array($fact, $famaddfacts)) {
			$newreqd = array();
			foreach($famreqdfacts as $r=>$rfact) {
				if ($rfact!=$fact) $newreqd[] = $rfact;
			}
			$famreqdfacts = $newreqd;
			$famfacts[] = $eventObj;
		}
	}
	foreach($famreqdfacts as $ind=>$fact) {
		$newEvent = new Event("1 $fact\r\n");
		$famfacts[] = $newEvent;
	}
	sort_facts($famfacts);
	$spid = "";
	if($parents) {
		if($pid!=$parents["HUSB"]) $spid=$parents["HUSB"];
		else $spid=$parents["WIFE"];
	}

// NOTE: Father
?>
	<tr><td class="topbottombar" colspan="4">
<?php
	echo $pgv_lang['father'], ' ';
	$person=Person::getInstance($parents['HUSB']);
	if ($person) {
		$fatherrec = $person->getGedcomRecord();
		$child_surname = "";
		$ct = preg_match("~1 NAME.*/(.*)/~", $fatherrec, $match);
		if ($ct>0) $child_surname = $match[1];
		if ($person->getSex()=="F") $label = $pgv_lang["mother"];
		echo "<a href=\"#\" onclick=\"return quickEdit('".$parents["HUSB"]."','','{$GEDCOM}');\">";
		$name = $person->getFullname();
		if ($SHOW_ID_NUMBERS) $name .= " (".$parents["HUSB"].")";
		$name .= " [".$pgv_lang["edit"]."]";
		echo ($name)."</a>\n";
	} else {
		echo $pgv_lang["unknown"];
	}
	print "</td></tr>";
	print "<tr><td class=\"descriptionbox\">".$pgv_lang["enter_pid"]."</td><td  class=\"optionbox\" colspan=\"3\"><input type=\"text\" size=\"10\" name=\"FATHER[$i]\" id=\"FATHER$i\" value=\"".$parents['HUSB']."\" />";
	print_findindi_link("FATHER$i","");
	print "</td></tr>";
?>
<?php if (empty($parents["HUSB"])) { ?>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_spouse_help", "qm"); print $pgv_lang["add_father"]; ?></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="FSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="FGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="FSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HFSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HFGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HFSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RFSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RFGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RFSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="FSEX<?php echo $i; ?>" tabindex="<?php print $tabkey; ?>">
			<option value="M" selected="selected"><?php print $pgv_lang["male"]; ?></option>
			<option value="F"><?php print $pgv_lang["female"]; ?></option>
			<option value="U"><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	<?php $tabkey++; ?>
	</td>
	</tr>
	<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="FBDATE<?php echo $i; ?>" id="FBDATE<?php echo $i; ?>" onblur="valid_date(this);" /><?php print_calendar_popup("FBDATE$i");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="FBPLAC<?php echo $i; ?>" id="Fbplace<?php echo $i; ?>" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("Fbplace$i"); ?>
	<?php $tabkey++; ?>
	</td>
	</tr>
	<?php print_quick_resn("FBRESN$i"); ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="FDDATE<?php echo $i; ?>" id="FDDATE<?php echo $i; ?>" onblur="valid_date(this);" /><?php print_calendar_popup("FDDATE$i");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="FDPLAC<?php echo $i; ?>" id="Fdplace<?php echo $i; ?>" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("Fdplace$i"); ?>
	<?php $tabkey++; ?>
	</td>
	</tr>
	<?php print_quick_resn("FDRESN$i");
}
?>
<?php
// NOTE: Mother
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4">
<?php
	echo $pgv_lang['mother'], ' ';
	$person=Person::getInstance($parents["WIFE"]);
	if ($person) {
		$motherrec = $person->getGedcomRecord();
		if ($person->getSex()=="M") $label = $pgv_lang["father"];
		print "<a href=\"#\" onclick=\"return quickEdit('".$parents["WIFE"]."','','{$GEDCOM}');\">";
		$name = $person->getFullName();
		if ($SHOW_ID_NUMBERS) $name .= " (".$parents["WIFE"].")";
		$name .= " [".$pgv_lang["edit"]."]";
		print PrintReady($name)."</a>\n";
	} else {
		echo $pgv_lang['unknown'];
	}
	print "</td></tr>\n";
	print "<tr><td  class=\"descriptionbox\">".$pgv_lang["enter_pid"]."</td><td  class=\"optionbox\" colspan=\"3\"><input type=\"text\" size=\"10\" name=\"MOTHER[$i]\" id=\"MOTHER$i\" value=\"".$parents['WIFE']."\" />";
	print_findindi_link("MOTHER$i","");
	?>
</td></tr>
<?php if (empty($parents["WIFE"])) { ?>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_spouse_help", "qm"); print $pgv_lang["add_mother"]; ?></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="MSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="MGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="MSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
</tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HMSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HMGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
</tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HMSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
</tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RMSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_name_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RMGIVN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
</tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RMSURN<?php echo $i; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="MSEX<?php echo $i; ?>" tabindex="<?php print $tabkey; ?>">
			<option value="M"><?php print $pgv_lang["male"]; ?></option>
			<option value="F" selected="selected"><?php print $pgv_lang["female"]; ?></option>
			<option value="U"><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	<?php $tabkey++; ?>
	</td>
</tr>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="MBDATE<?php echo $i; ?>" id="MBDATE<?php echo $i; ?>" onblur="valid_date(this);" /><?php print_calendar_popup("MBDATE$i");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="MBPLAC<?php echo $i; ?>" id="Mbplace<?php echo $i; ?>" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("Mbplace$i"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php print_quick_resn("MBRESN$i"); ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="MDDATE<?php echo $i; ?>" id="MDDATE<?php echo $i; ?>" onblur="valid_date(this);" /><?php print_calendar_popup("MDDATE$i");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="MDPLAC<?php echo $i; ?>" id="Mdplace<?php echo $i; ?>" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("Mdplace$i"); ?>
	<?php $tabkey++; ?>
	</td>
</tr>
<?php print_quick_resn("MDRESN$i");
}
// NOTE: Update fact
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_fact_help", "qm"); print $pgv_lang["update_fact"]; ?></td></tr>
<tr>
	<td class="descriptionbox">&nbsp;</td>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"]; ?></td>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"]; ?></td>
	<td class="descriptionbox"><?php print $pgv_lang["delete"]; ?></td>
</tr>
<?php
foreach($famfacts as $f=>$eventObj) {
        $fact_tag = $eventObj->getTag();
        $date = $eventObj->getValue("DATE");
        $plac = $eventObj->getValue("PLAC");
        $temp = $eventObj->getValue("TEMP");
	?>
	<tr>
		<td class="descriptionbox">
		<?php if (isset($factarray[$fact_tag])) print $factarray[$fact_tag];
			else if (isset($pgv_lang[$fact_tag])) print $pgv_lang[$fact_tag];
			else print $fact_tag;
		?>
			<input type="hidden" name="F<?php echo $i; ?>TAGS[]" value="<?php echo $fact_tag; ?>" />
		</td>
		<td class="optionbox"><input type="text" dir="ltr" tabindex="<?php print $tabkey; $tabkey++;?>" size="15" name="F<?php echo $i; ?>DATES[]" id="F<?php echo $i; ?>DATE<?php echo $f; ?>" onblur="valid_date(this);" value="<?php echo PrintReady(htmlspecialchars($date,ENT_COMPAT,'UTF-8')); ?>" /><?php print_calendar_popup("F{$i}DATE$f");?></td>
		<?php if (empty($temp) && (!in_array($fact_tag, $nonplacfacts))) { ?>
			<td class="optionbox"><input size="30" type="text" tabindex="<?php print $tabkey; $tabkey++; ?>" name="F<?php echo $i; ?>PLACS[]" id="F<?php echo $i; ?>place<?php echo $f; ?>" value="<?php print PrintReady(htmlspecialchars($plac,ENT_COMPAT,'UTF-8')); ?>" />
			<?php print_findplace_link("F".$i."place$f"); ?>
			</td>
		<?php }
		else {
			print "<td class=\"optionbox\"><select tabindex=\"".$tabkey."\" name=\"F".$i."TEMP[]\" >\n";
			print "<option value=''>".$pgv_lang["no_temple"]."</option>\n";
			foreach($TEMPLE_CODES as $code=>$temple) {
				print "<option value=\"$code\"";
				if ($code==$temp) print " selected=\"selected\"";
				print ">$temple</option>\n";
			}
			print "</select>\n</td>\n";
			$tabkey++;
		}
		?>
		<td class="optionbox center">
			<input type="hidden" name="F<?php echo $i; ?>REMS[<?php echo $f; ?>]" id="F<?php echo $i; ?>REM<?php echo $f; ?>" value="0" />
			<?php if ($date!='' || $plac!='' || $temp!='') { ?>
			<a href="javascript: <?php print $pgv_lang["delete"]; ?>" onclick="if (confirm('<?php print $pgv_lang["check_delete"]; ?>')) { document.quickupdate.closewin.value='0'; document.quickupdate.F<?php echo $i; ?>REM<?php echo $f; ?>.value='1'; document.quickupdate.submit(); } return false;">
				<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"]; ?>" border="0" alt="<?php print $pgv_lang["delete"]; ?>" />
			</a>
			<?php } ?>
		</td>
	</tr>
	<?php if ($SHOW_QUICK_RESN) {
		print_quick_resn("F".$i."RESNS[]");
	} ?>
	<?php
}
?>
<?php
// NOTE: Add new fact
?>
<?php if (count($famaddfacts)>0) { ?>
	<tr><td>&nbsp;</td></tr>
	<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_fact_help", "qm"); print $pgv_lang["add_fact"]; ?></td></tr>
	<tr>
		<td class="descriptionbox">&nbsp;</td>
		<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DATE"]; ?></td>
		<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"]; ?></td>
		<td class="descriptionbox">&nbsp;</td>
		</tr>
	<tr>
		<td class="optionbox"><select name="F<?php echo $i; ?>newfact" tabindex="<?php print $tabkey; ?>">
			<option value=""><?php print $pgv_lang["select_fact"]; ?></option>
		<?php $tabkey++; ?>
		<?php
		foreach($famaddfacts as $indexval => $fact) {
			$found = false;
			foreach($famfacts as $ind=>$value) {
				if ($fact==$value->getTag()) {
					$found=true;
					break;
				}
			}
			if (!$found) print "\t\t<option value=\"$fact\">".$factarray[$fact]."</option>\n";
		}
		?>
			</select>
		</td>
		<td class="optionbox"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="F<?php echo $i; ?>DATE" id="F<?php echo $i; ?>DATE" onblur="valid_date(this);" /><?php print_calendar_popup("F".$i."DATE");?></td>
		<?php $tabkey++; ?>
		<td class="optionbox"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="F<?php echo $i; ?>PLAC" id="F<?php echo $i; ?>place" />
		<?php print_findplace_link("F".$i."place"); ?>
		</td>
		<?php $tabkey++; ?>
		<td class="optionbox">&nbsp;</td>
	</tr>
	<?php print_quick_resn("RESN"); ?>
<?php }
// NOTE: Children
$chil = find_children_in_record($famrec, $pid);
	?>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="topbottombar" colspan="4"><?php print $pgv_lang["children"]; ?></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php print $pgv_lang["name"]; ?></td>
		<td class="descriptionbox center"><?php print $factarray["SEX"]; ?></td>
		<td class="descriptionbox"><?php print $factarray["BIRT"]; ?></td>
		<td class="descriptionbox"><?php print $pgv_lang["remove"]; ?><input type="hidden" name="F<?php echo $i; ?>CDEL" value="" /></td>
	</tr>
	<?php
		foreach($chil as $c=>$child) {
			$person=Person::getInstance($child);
			print "<tr><td class=\"optionbox\">";
			$name = $person->getFullName();
			if ($SHOW_ID_NUMBERS) $name .= " (".$child.")";
			$name .= " [".$pgv_lang["edit"]."]";
			print "<a href=\"#\" onclick=\"return quickEdit('".$child."','','{$GEDCOM}');\">";
			print PrintReady($name);
			print "</a>";
			print "</td>\n<td class=\"optionbox center\">";
			print $person->getSex();
			print "</td>\n<td class=\"optionbox\">";
			print $person->format_first_major_fact(PGV_EVENTS_BIRT, 2);
			print "</td>\n";
			?>
			<td class="optionbox center">
				<a href="javascript: <?php print $pgv_lang["remove_child"]; ?>" onclick="if (confirm('<?php print $pgv_lang["confirm_remove"]; ?>')) { document.quickupdate.closewin.value='0'; document.quickupdate.F<?php echo $i; ?>CDEL.value='<?php echo $child; ?>'; document.quickupdate.submit(); } return false;">
					<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"]; ?>" border="0" alt="<?php print $pgv_lang["remove_child"]; ?>" />
				</a>
			</td>
			<?php
			print "</tr>\n";
		}
	?>
	<tr>
		<td class="descriptionbox"><?php print $pgv_lang["add_child_to_family"]; ?></td>
		<td class="optionbox" colspan="3"><input type="text" size="10" name="CHIL[]" id="CHIL<?php echo $i; ?>" />
		<?php print_findindi_link("CHIL$i","");?>
		</td>
	</tr>
<?php
// NOTE: Add a child
?>
<tr><td>&nbsp;</td></tr>
<tr><td class="topbottombar" colspan="4"><?php print_help_link("quick_update_child_help", "qm"); print $pgv_lang["add_child_to_family"]; ?></td></tr>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>SURN" value="<?php //if (!empty($child_surname)) print $child_surname; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_given_name_help", "qm"); print $factarray["GIVN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>GIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_surname_help", "qm"); print $factarray["SURN"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>SURN" value="<?php //if (!empty($child_surname)) print $child_surname; ?>" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_GIVN_help", "qm"); print $pgv_lang["hebrew_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HC<?php echo $i; ?>GIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit__HEB_SURN_help", "qm"); print $pgv_lang["hebrew_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="HC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<?php if ($NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_GIVN_help", "qm"); print $pgv_lang["roman_givn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RC<?php echo $i; ?>GIVN" /></td>
</tr>
<?php $tabkey++; ?>
<?php if (!$NAME_REVERSE) { ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_ROMN_SURN_help", "qm"); print $pgv_lang["roman_surn"];?></td>
	<td class="optionbox" colspan="3"><input size="50" type="text" tabindex="<?php print $tabkey; ?>" name="RC<?php echo $i; ?>SURN" /></td>
</tr>
<?php $tabkey++; ?>
<?php } ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("edit_sex_help", "qm"); print $pgv_lang["sex"];?></td>
	<td class="optionbox" colspan="3">
		<select name="C<?php echo $i; ?>SEX" tabindex="<?php print $tabkey; ?>">
			<option value="M"><?php print $pgv_lang["male"]; ?></option>
			<option value="F"><?php print $pgv_lang["female"]; ?></option>
			<option value="U"><?php print $pgv_lang["unknown"]; ?></option>
		</select>
	</td></tr>
	<?php $tabkey++; ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["BIRT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="C<?php echo $i; ?>DATE" id="C<?php echo $i; ?>DATE" onblur="valid_date(this);" /><?php print_calendar_popup("C{$i}DATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>PLAC" id="c<?php echo $i; ?>place" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("c".$i."place"); ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<?php print_quick_resn("C".$i."RESN"); ?>
<tr>
	<td class="descriptionbox"><?php print_help_link("def_gedcom_date_help", "qm"); print $factarray["DEAT"]; ?>
		<?php print $factarray["DATE"];?>
	</td>
	<td class="optionbox" colspan="3"><input type="text" dir="ltr" tabindex="<?php print $tabkey; ?>" size="15" name="C<?php echo $i; ?>DDATE" id="C<?php echo $i; ?>DDATE" onblur="valid_date(this);" /><?php print_calendar_popup("C{$i}DDATE");?></td>
	</tr>
	<?php $tabkey++; ?>
	<tr>
	<td class="descriptionbox"><?php print_help_link("edit_PLAC_help", "qm"); print $factarray["PLAC"];?></td>
	<td class="optionbox" colspan="3"><input size="30" type="text" tabindex="<?php print $tabkey; ?>" name="C<?php echo $i; ?>DPLAC" id="c<?php echo $i; ?>dplace" /><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"];?>" alt="" />
	<?php print_findplace_link("c".$i."dplace"); ?>
	</td>
	<?php $tabkey++; ?>
</tr>
<?php print_quick_resn("C".$i."DRESN"); ?>
</table>
</div>
	<?php
	$i++;
}
if (PGV_USER_IS_ADMIN) {
	print "<table class=\"facts_table width80\">\n";
	print "<tr><td class=\"descriptionbox ".$TEXT_DIRECTION." wrap\">";
	print_help_link("no_update_CHAN_help", "qm");
	print $pgv_lang["admin_override"]."</td><td class=\"optionbox wrap\">\n";
	print "<input type=\"checkbox\" name=\"preserve_last_changed\" />\n";
	print $pgv_lang["no_update_CHAN"]."<br />\n";
	print "</td></tr>\n";
	print "</table>";
}
?>
<input type="submit" value="<?php print $pgv_lang["save"]; ?>" />
</form>
<?php
}
print_simple_footer();
?>
