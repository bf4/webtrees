<?php
/**
 * Defines a protocol for interfacing remote requests over a http connection
 *
 * When $action is 'get' then the gedcom record with the given $xref is retrieved.
 * When $action is 'update' the gedcom record matching $xref is replaced with the data in $gedrec.
 * When $action is 'append' the gedcom record in $gedrec is appended to the end of the gedcom file.
 * When $action is 'delete' the gedcom record with $xref is removed from the file.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008 John Finlay and Others.  All rights reserved.
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

require 'config.php';
require_once 'includes/functions_edit.php';

header("Content-Type: text/plain; charset=$CHARACTER_SET");

$READ_ONLY = ((isset($_SESSION['readonly']))&&($_SESSION['readonly']==true)) ? 1 : 0;

// Make sure there is at least one gedcom.
if (count(get_all_gedcoms())==0) {
	addDebugLog($action." ERROR 21: No Gedcoms available on this site.");
	print "ERROR 21: No Gedcoms available on this site.\n";
	exit;
}

$gedcom=safe_GET('GEDCOM');
if ($gedcom) {
	if (!in_array($gedcom, get_all_gedcoms())) {
		addDebugLog("ERROR 21: Invalid GEDCOM specified.  Remember that the GEDCOM is case sensitive.");
		print "ERROR 21: Invalid GEDCOM specified.  Remember that the GEDCOM is case sensitive.\n";
		exit;
	}
	$GEDCOM=$gedcom;
}
$GED_ID=get_id_from_gedcom($GEDCOM);

if (!check_for_import($GEDCOM)) {
	addDebugLog($action." ERROR 22: Gedcom [$GEDCOM] needs to be imported.");
	print "ERROR 22: Gedcom [$GEDCOM] needs to be imported.\n";
	exit;
}

$action=safe_GET('action');

// The following actions can be performed without being connected.
switch ($action) {
case '':
	addDebugLog("ERROR 1: No action specified.");
	print "ERROR 1: No action specified.\n";
	exit;
case 'version':
	addDebugLog($action." SUCCESS\n".PGV_VERSION_TEXT."\n");
	print "SUCCESS\n".PGV_VERSION_TEXT."\n";
	exit;
case 'connect':
	$username=safe_GET('username');
	if ($username) {
		$password=safe_GET('password');
		$user_id=authenticateUser($username, $password);
		if ($user_id) {
			$stat=newConnection();
			if ($stat!==false) {
				addDebugLog($action." username=$username SUCCESS\n".$stat);
				print "SUCCESS\n".$stat;
			}
			$_SESSION['connected']=$user_id;
		} else {
			addDebugLog($action." username=$username ERROR 10: Username and password key failed to authenticate.");
			print "ERROR 10: Username and password key failed to authenticate.\n";
		}
	} else {
		$stat=newConnection();
		if ($stat!==false) {
			addDebugLog($action." SUCCESS\n".$stat);
			print "SUCCESS\n".$stat;
		}
		AddToLog('Read-Only Anonymous Client connection.');
		$_SESSION['connected']='Anonymous';
		$_SESSION['readonly']=1;
	}
	exit;
case 'listgedcoms':
	$out_msg = "SUCCESS\n";
	foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
		$out_msg.="$gedcom\t".get_gedcom_setting($ged_id, 'title')."\n";
	}
	addDebugLog($action." ".$out_msg);
	print $out_msg;
	exit;
default:
	// All other actions require an authenticated connection
	if (empty($_SESSION['connected'])){
		addDebugLog($action." ERROR 12: use 'connect' action to initiate a session.");
		print "ERROR 12: use 'connect' action to initiate a session.\n";
		exit;
	}
	break;
}

// The following actions can only be performed when connected
switch ($action) {
case 'get':
	$xref=safe_GET('xref', PGV_REGEX_XREF.'([ ,;]+'.PGV_REGEX_XREF.')*');
	$view=safe_GET('view', PGV_REGEX_ALPHANUM);
	if ($xref) {
		$xrefs = preg_split("/[;, ]/", $xref, 0, PREG_SPLIT_NO_EMPTY);
		$gedrecords="";
		foreach ($xrefs as $xref1) {
			if (!empty($xref1)) {
				$gedrec=find_updated_record($xref1);
				if (!$gedrec) {
					$gedrec=find_gedcom_record($xref1);
					if ($gedrec) {
						preg_match("/0 @(.*)@ (.*)/", $gedrec, $match);
						$type = trim($match[2]);
						if (!displayDetailsById($xref1, $type)) {
							//-- do not have full access to this record, so privatize it
							$gedrec = privatize_gedcom($gedrec);
						}
						else if ($view=='version' || $view=='change') {
							$chan = get_gedcom_value('CHAN', 1, $gedrec);
							if (empty($chan)) {
								$head = find_gedcom_record("HEAD");
								$head_date = get_sub_record(1, "1 DATE", $head);
								$lines = preg_split("/\n/", $head_date);
								$head_date = "";
								foreach($lines as $line) {
									$num = $line{0};
									$head_date.=($num+1).substr($line, 1)."\n";
								}
								$chan = "1 CHAN\r\n".$head_date;
							}
							$gedrec = '0 @'.$xref1.'@ '.$type."\r\n".$chan;
						}
						if (!empty($gedrec)) $gedrecords = $gedrecords . "\n".trim($gedrec);
					}
				}
			}
		}
		if (!safe_GET('keepfile')) {
			$ct = preg_match_all("/ FILE (.*)/", $gedrecords, $match, PREG_SET_ORDER);
			for($i=0; $i<$ct; $i++) {
				$mediaurl = $SERVER_URL.$MEDIA_DIRECTORY.extract_filename($match[$i][1]);
				$gedrecords = str_replace($match[$i][1], $mediaurl, $gedrecords);
			}
		}
		addDebugLog($action." xref=$xref ".$gedrecords);
		print "SUCCESS\n".$gedrecords;
	} else {
		addDebugLog($action." ERROR 3: No gedcom id specified.  Please specify a xref.");
		print "ERROR 3: No gedcom id specified.  Please specify a xref.\n";
	}
	exit;
case 'getvar':
	$var=safe_GET('var', '[A-Za-z0-9_]+');
	$public_vars = array("READ_ONLY","CHARACTER_SET","GEDCOM","PEDIGREE_ROOT_ID");
	if ($var && in_array($var, $public_vars) && isset($$var)) {
		addDebugLog($action." var=$var SUCCESS\n".$$var);
		print "SUCCESS\n".$$var;
	} else if (PGV_USER_ID && $var && isset($$var) && !in_array($var, $CONFIG_VARS)) {
		addDebugLog($action." var=$var SUCCESS\n".$$var);
		print "SUCCESS\n".$$var;
	} else {
		addDebugLog($action." var=$var ERROR 13: Invalid variable specified.  Please provide a variable.");
		print "ERROR 13: Invalid variable specified.\n";
	}
	exit;
case 'update':
	$xref=safe_GET('xref', PGV_REGEX_XREF);
	if ($xref) {
		$gedrec=safe_GET('gedrec', '.*'); // raw data may contain any characters
		if ($gedrec) {
			if (empty($_SESSION['readonly']) && PGV_USER_CAN_EDIT && displayDetails($gedrec)) {
				$gedrec = preg_replace(array("/\\\\+r/","/\\\\+n/"), array("\r","\n"), $gedrec);
				$success = replace_gedrec($xref, $gedrec);
				if ($success) {
					addDebugLog($action." xref=$xref gedrec=$gedrec SUCCESS");
					print "SUCCESS\n";
				}
			} else {
				addDebugLog($action." xref=$xref ERROR 11: No write privileges for this record.");
				print "ERROR 11: No write privileges for this record.\n";
			}
		} else {
			addDebugLog($action." xref=$xref ERROR 8: No gedcom record provided.  Unable to process request.");
			print "ERROR 8: No gedcom record provided.  Unable to process request.\n";
		}
	} else {
		addDebugLog($action." ERROR 3: No gedcom id specified.  Please specify a xref.");
		print "ERROR 3: No gedcom id specified.  Please specify a xref.\n";
	}
	exit;
case 'append':
	$gedrec=safe_GET('gedrec', '.*'); // raw data may contain any characters
	if ($gedrec) {
		if (empty($_SESSION['readonly']) && PGV_USER_CAN_EDIT) {
			$gedrec = preg_replace(array("/\\\\+r/","/\\\\+n/"), array("\r","\n"), $gedrec);
			$xref = append_gedrec($gedrec);
			if ($xref) {
				addDebugLog($action." gedrec=$gedrec SUCCESS\n$xref");
				print "SUCCESS\n$xref\n";
			}
		} else {
			addDebugLog($action." gedrec=$gedrec ERROR 11: No write privileges for this record.");
			print "ERROR 11: No write privileges for this record.\n";
		}
	} else {
		addDebugLog($action." ERROR 8: No gedcom record provided.  Unable to process request.");
		print "ERROR 8: No gedcom record provided.  Unable to process request.\n";
	}
	exit;
case 'delete':
	$xref=safe_GET('xref', PGV_REGEX_XREF);
	if ($xref) {
		if (empty($_SESSION['readonly']) && PGV_USER_CAN_EDIT && displayDetailsById($xref)) {
			$success = delete_gedrec($xref);
			if ($success) {
				addDebugLog($action." xref=$xref SUCCESS");
				print "SUCCESS\n";
			}
		} else {
			addDebugLog($action." xref=$xref ERROR 11: No write privileges for this record.");
			print "ERROR 11: No write privileges for this record.\n";
		}
	} else {
		addDebugLog($action." ERROR 3: No gedcom id specified.  Please specify a xref.");
		print "ERROR 3: No gedcom id specified.  Please specify a xref.\n";
	}
	exit;
case 'getnext':
	$xref=safe_GET('xref', PGV_REGEX_XREF);
	if ($xref) {
		$xref1 = get_next_xref($xref, $GED_ID);
		$gedrec = find_updated_record($xref1);
		if (!$gedrec) {
			$gedrec = find_gedcom_record($xref1);
		}
		if (!displayDetails($gedrec)) {
			//-- do not have full access to this record, so privatize it
			$gedrec = privatize_gedcom($gedrec);
		}
		addDebugLog($action." xref=$xref SUCCESS\n".trim($gedrec));
		print "SUCCESS\n".trim($gedrec);
	} else {
		addDebugLog($action." ERROR 3: No gedcom id specified.  Please specify a xref.");
		print "ERROR 3: No gedcom id specified.  Please specify a xref.\n";
	}
	exit;
case 'getprev':
	$xref=safe_GET('xref', PGV_REGEX_XREF);
	if ($xref) {
		$xref1 = get_prev_xref($xref, $GED_ID);
		$gedrec = find_updated_record($xref1);
		if (!$gedrec) {
			$gedrec = find_gedcom_record($xref1);
		}
		if (!displayDetails($gedrec)) {
			//-- do not have full access to this record, so privatize it
			$gedrec = privatize_gedcom($gedrec);
		}
		addDebugLog($action." xref=$xref SUCCESS\n".trim($gedrec));
		print "SUCCESS\n".trim($gedrec);
	} else {
		addDebugLog($action." ERROR 3: No gedcom id specified.  Please specify a xref.");
		print "ERROR 3: No gedcom id specified.  Please specify a xref.\n";
	}
	exit;
case 'search':
	$query=safe_GET('query');
	if ($query) {
		$sindilist = search_indis($query);
		uasort($sindilist, "itemsort");
		print "SUCCESS\n";
		addDebugLog($action." query=$query SUCCESS");
		foreach($sindilist as $xref=>$indi) {
			if (displayDetailsById($xref)) {
				print	"$xref\n";
			}
		}
	} else {
		addDebugLog($action." ERROR 15: No query specified.  Please specify a query.");
		print "ERROR 15: No query specified.  Please specify a query.\n";
	}
	exit;
case 'soundex':
	$lastname=safe_GET('lastname');
	$firstname=safe_GET('firstname');
	$place=safe_GET('place');
	$soundex=safe_GET('soundex', '\w+', 'Russell');
	
	if ($lastname || $firstname) {
		$res = search_indis_soundex($soundex, $lastname, $firstname, $place);
		print "SUCCESS\n";
		addDebugLog($action." lastname=$lastname firstname=$firstname SUCCESS");
		// -- only get the names who match soundex
		while($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
			if (displayDetailsById($row['i_id'])) {
				print $row['i_id']."\n";
			}
		}
	} else {
		addDebugLog($action." ERROR 16: No names specified.  Please specify a firstname or a lastname.");
		print "ERROR 16: No names specified.  Please specify a firstname or a lastname.\n";
	}
	exit;
case 'getxref':
	$position=safe_GET('position', array('first','last','next','prev','new'));
	$type=safe_GET('type', array('INDI','FAM','SOUR','REPO','NOTE','OBJE','OTHER'));
	$xref=safe_GET('xref', PGV_REGEX_XREF);

	if ($position=='next' && !$xref) {
		$position='first';
	}
	if ($position=='prev' && !$xref) {
		$position='last';
	}

	if (!$position || !$type) {
		addDebugLog($action." type=$type position=$position ERROR 18: Invalid \$type specification.  Valid types are INDI, FAM, SOUR, REPO, NOTE, OBJE, or OTHER");
		print "ERROR 18: Invalid \$type or \$position specification.  Valid types are INDI, FAM, SOUR, REPO, NOTE, OBJE, or OTHER\n";
		exit;
	}
	switch ($position) {
	case 'first':
		$xref=get_first_xref($type, $GED_ID);
		addDebugLog($action." type=$type position=$position SUCCESS\n$xref");
		print "SUCCESS\n$xref\n";
		break;
	case 'last':
		$xref=get_last_xref($type, $GED_ID);
		addDebugLog($action." type=$type position=$position SUCCESS\n$xref");
		print "SUCCESS\n$xref\n";
		break;
	case 'next':
		$xref=get_next_xref($xref, $GED_ID);
		addDebugLog($action." type=$type position=$position SUCCESS\n$xref");
		print "SUCCESS\n$xref\n";
		break;
	case 'prev':
		$xref=get_prev_xref($xref, $GED_ID);
		addDebugLog($action." type=$type position=$position SUCCESS\n$xref");
		print "SUCCESS\n$xref\n";
		break;
	case 'all':
		switch($type) {
			case "INDI":
				$sql="SELECT i_id FROM {$TBLPREFIX}individuals WHERE i_file={$GED_ID} ORDER BY i_id";
				break;
			case "FAM":
				$sql="SELECT f_id FROM {$TBLPREFIX}families WHERE f_file={$GED_ID} ORDER BY f_id";
				break;
			case "SOUR":
				$sql="SELECT s_id FROM {$TBLPREFIX}sources WHERE s_file={$GED_ID} ORDER BY s_id";
				break;
			case "OBJE":
				$sql="SELECT m_media FROM {$TBLPREFIX}media WHERE m_gedfile={$GED_ID} ORDER BY m_media";
				break;
			case "OTHER":
				$sql="SELECT o_id FROM {$TBLPREFIX}other WHERE o_file={$GED_ID} AND o_type NOT IN ('REPO', 'NOTE') ORDER BY o_id";
				break;
			default:
				$sql="SELECT o_id FROM {$TBLPREFIX}other WHERE o_file={$GED_ID} AND o_type='{$type}' ORDER BY o_id";
				break;
		}
		$res = dbquery($sql);
		print "SUCCESS\n";
		while ($row = $res->fetchRow()) {		
			print "$row[0]\n";
		}
		$res->free();
		addDebugLog($action." type=$type position=$position ".$msg_out);
		print $msg_out;
		break;
	case 'new':
		if (empty($_SESSION['readonly']) && PGV_USER_CAN_EDIT) {
			$gedrec = "0 @REF@ $type";
			$xref = append_gedrec($gedrec);
			if ($xref) {
				addDebugLog($action." type=$type position=$position SUCCESS\n$xref");
				print "SUCCESS\n$xref\n";
			}
		} else {
			addDebugLog($action." type=$type position=$position ERROR 11: No write privileges for this record.");
			print "ERROR 11: No write privileges for this record.\n";
		}
		break;
	}
	exit;
case 'uploadmedia':
	$error="";
	if (isset($_FILES['mediafile'])) {
		if (!move_uploaded_file($_FILES['mediafile']['tmp_name'], $MEDIA_DIRECTORY.$_FILES['mediafile']['name'])) {
			$error .= "ERROR 19: ".$pgv_lang["upload_error"]." ".file_upload_error_text($_FILES['mediafile']['error']);
		} else if (!isset($_FILES['thumbnail'])) {
			$filename = $MEDIA_DIRECTORY.$_FILES['mediafile']['name'];
			$thumbnail = $MEDIA_DIRECTORY."thumbs/".$_FILES['mediafile']['name'];
			generate_thumbnail($filename, $thumbnail);
			//if (!$thumbgenned) $error .= "ERROR 19: ".$pgv_lang["thumbgen_error"].$filename;
		}
	}
	if (isset($_FILES['thumbnail'])) {
		if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $MEDIA_DIRECTORY."thumbs/".$_FILES['thumbnail']['name'])) {
			$error .= "\nERROR 19: ".$pgv_lang["upload_error"]." ".file_upload_error_text($_FILES['thumbnail']['error']);
		}
	}
	if (!empty($error)) {
		addDebugLog($action." $error");
		print $error."\n";
	} else {
		addDebugLog($action." SUCCESS");
		print "SUCCESS\n";
	}
	exit;
case 'getchanges':
	$lastdate = new GedcomDate(safe_GET('date', '\d\d \w\w\w \d\d\d\d'));
	if ($lastdate->isOK()) {
		if ($lastdate->MinJD()<server_jd()-180) {
			addDebugLog($action." ERROR 24: You cannot retrieve updates for more than 180 days.");
			print "ERROR 24: You cannot retrieve updates for more than 180 days.\n";
		} else {
			print "SUCCESS\n";
			foreach(get_recent_changes($lastdate->MinJD()) as $change) {
				print $change['d_gid']."\n";
			}
		}
	} else {
		addDebugLog($action." ERROR 23: Invalid date parameter.  Please use a valid date in the GEDCOM format DD MMM YYYY.");
		print "ERROR 23: Invalid date parameter.  Please use a valid date in the GEDCOM format DD MMM YYYY.\n";
	}
	exit;
default:
	addDebugLog($action." ERROR 2: Unable to process request.  Unknown action.");
	print "ERROR 2: Unable to process request.  Unknown action.\n";
}
?>
