<?php
/**
 * Add media to gedcom file
 *
 * This file allows the user to maintain a seperate table
 * of media files and associate them with individuals in the gedcom
 * and then add these records later.
 * Requires SQL mode.
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
 * @package PhpGedView
 * @subpackage MediaDB
 * @version $Id$
 */

require './config.php';

require_once 'includes/functions_print_lists.php';
require_once 'includes/functions_edit.php';

if (empty($ged)) $ged = $GEDCOM;
$GEDCOM = $ged;

if ($_SESSION["cookie_login"]) {
	header('Location: '.encode_url("login.php?type=simple&ged={$GEDCOM}&url=addmedia.php", false));
	exit;
}

// TODO use GET/POST, rather than $_REQUEST
// TODO decide what validation is required on these input parameters
$pid        =safe_REQUEST($_REQUEST, 'pid',         PGV_REGEX_XREF);
$mid        =safe_REQUEST($_REQUEST, 'mid',         PGV_REGEX_XREF);
$gid        =safe_REQUEST($_REQUEST, 'gid',         PGV_REGEX_XREF);
$linktoid   =safe_REQUEST($_REQUEST, 'linktoid',    PGV_REGEX_XREF);
$action     =safe_REQUEST($_REQUEST, 'action',      PGV_REGEX_NOSCRIPT, 'showmediaform');
$folder     =safe_REQUEST($_REQUEST, 'folder',      PGV_REGEX_UNSAFE);
$oldFolder  =safe_REQUEST($_REQUEST, 'oldFolder',   PGV_REGEX_UNSAFE);
$filename   =safe_REQUEST($_REQUEST, 'filename',    PGV_REGEX_UNSAFE);
$oldFilename=safe_REQUEST($_REQUEST, 'oldFilename', PGV_REGEX_UNSAFE, $filename);
$level      =safe_REQUEST($_REQUEST, 'level',       PGV_REGEX_UNSAFE);
$text       =safe_REQUEST($_REQUEST, 'text',        PGV_REGEX_UNSAFE);
$tag        =safe_REQUEST($_REQUEST, 'tag',         PGV_REGEX_UNSAFE);
$islink     =safe_REQUEST($_REQUEST, 'islink',      PGV_REGEX_UNSAFE);
$glevels    =safe_REQUEST($_REQUEST, 'glevels',     PGV_REGEX_UNSAFE);
// TODO are these used?
$m_ext      =safe_REQUEST($_REQUEST, 'm_ext',       PGV_REGEX_UNSAFE);
$m_titl     =safe_REQUEST($_REQUEST, 'm_titl',      PGV_REGEX_UNSAFE);
$m_file     =safe_REQUEST($_REQUEST, 'm_file',      PGV_REGEX_UNSAFE);

print_simple_header($pgv_lang["add_media_tool"]);
$disp = true;
if (empty($pid) && !empty($mid)) $pid = $mid;
if (!empty($pid)) {
	if (!isset($pgv_changes[$pid."_".$GEDCOM])) $gedrec = find_media_record($pid);
	else $gedrec = find_updated_record($pid);
	if (empty($gedrec)) $gedrec =  find_record_in_file($pid);
	$disp = displayDetailsById($pid, "OBJE");
}
if ($action=="update" || $action=="newentry") {
	if (!isset($linktoid) || $linktoid=="new") $linktoid="";
	if (empty($linktoid) && !empty($gid)) $linktoid = $gid;
	if (!empty($linktoid)) {
		$disp = displayDetailsById($linktoid);
	}
}

if (!PGV_USER_CAN_EDIT || !$disp || !$ALLOW_EDIT_GEDCOM) {
	//print "pid: $pid<br />";
	//print "gedrec: $gedrec<br />";
	print $pgv_lang["access_denied"];
	//-- display messages as to why the editing access was denied
	if (!PGV_USER_CAN_EDIT) print "<br />".$pgv_lang["user_cannot_edit"];
	if (!$ALLOW_EDIT_GEDCOM) print "<br />".$pgv_lang["gedcom_editing_disabled"];
	if (!$disp) {
		print "<br />".$pgv_lang["privacy_prevented_editing"];
		if (!empty($pid)) print "<br />".$pgv_lang["privacy_not_granted"]." pid $pid.";
	}
	print "<br /><br /><div class=\"center\"><a href=\"javascript: ".$pgv_lang["close_window"]."\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a></div>\n";
	print_simple_footer();
	exit;
}

?>
<script language="JavaScript" type="text/javascript">
<!--
	var language_filter, magnify;
	var pastefield;
	language_filter = "";
	magnify = "";
	function openerpasteid(id) {
		window.opener.paste_id(id);
		window.close();
	}

	function paste_id(value) {
		pastefield.value = value;
	}

	function paste_char(value,lang,mag) {
		pastefield.value += value;
		language_filter = lang;
		magnify = mag;
	}
//-->
</script>

<?php
// Naming conventions used in this script:
// folderName - this is the link to the folder in the standard media directory; the one that is stored in the gedcom.
// serverFolderName - this is where the file is physically located.  if the media firewall is enabled it is in the protected media directory.  if not it is the same as folderName.
// thumbFolderName - this is the link to the thumb folder in the standard media directory
// serverThumbFolderName - this is where the thumbnail file is physically located

if (empty($action)) $action="showmediaform";

// **** begin action "newentry"
// NOTE: Store the entered data
if ($action=="newentry") {
	if (empty($level)) $level = 1;

	$error = "";
	$mediaFile = "";
	$thumbFile = "";
	if (!empty($_FILES['mediafile']["name"]) || !empty($_FILES['thumbnail']["name"])) {
		// NOTE: Check for file upload
		$folderName = "";
		if (!empty($_POST["folder"])) $folderName = trim($_POST["folder"]);
		// Validate and correct folder names
		$folderName = check_media_depth($folderName."/y.z", "BACK");
		$folderName = dirname($folderName)."/";
		$thumbFolderName = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $folderName);

		if (!empty($folderName)) {
			$_SESSION["upload_folder"] = $folderName; // store standard media folder in session
			// if using the media firewall, automatically upload new files to the protected media directory
			$serverFolderName = ($USE_MEDIA_FIREWALL) ? get_media_firewall_path($folderName) : $folderName;
			// make sure the dir exists
			@mkdirs($serverFolderName);
		}
		if (!empty($thumbFolderName)) {
			// determine thumbdir and make sure it exists
			$serverThumbFolderName = ($USE_MEDIA_FIREWALL && $MEDIA_FIREWALL_THUMBS) ? get_media_firewall_path($thumbFolderName) : $thumbFolderName;
			@mkdirs($serverThumbFolderName);
		}

		$error = "";

		// Determine file name on server
		if (PGV_USER_GEDCOM_ADMIN && !empty($text[0])) {
			$parts = pathinfo(trim($text[0]));
			$mediaFile = $parts["basename"];
			if (empty($parts["extension"]) || !in_array(strtolower($parts["extension"]), $MEDIATYPE)) {
				if (!empty($_FILES["mediafile"]["name"])) {
					$parts = pathinfo($_FILES["mediafile"]["name"]);
				} else {
					$parts = pathinfo($_FILES["thumbnail"]["name"]);
				}
				$mediaFile .= ".".$parts["extension"];
			}
		} else {
			if (!empty($_FILES["mediafile"]["name"])) {
				$parts = pathinfo($_FILES["mediafile"]["name"]);
			} else {
				$parts = pathinfo($_FILES["thumbnail"]["name"]);
			}
			$mediaFile = $parts["basename"];
		}

		if (!empty($_FILES["mediafile"]["name"])) {
			$newFile = $serverFolderName.$mediaFile;
			$fileExists = file_exists(filename_decode($newFile));
			// Copy main media file into the destination directory
			if ($fileExists) {
				$error .= $pgv_lang["media_exists"]."&nbsp;&nbsp;".$newFile."<br />";
			} else {
				if (!move_uploaded_file($_FILES["mediafile"]["tmp_name"], filename_decode($newFile))) {
					// the file cannot be copied
					$error .= $pgv_lang["upload_error"]."<br />".file_upload_error_text($_FILES["mediafile"]["error"])."<br />";
				} else {
					AddToLog("Media file {$folderName}{$mediaFile} uploaded");
				}
			}
		}
		if ($error=="" && !empty($_FILES["thumbnail"]["name"])) {
			$newThum = $serverThumbFolderName.$mediaFile;
			$thumExists = file_exists(filename_decode($newThum));
			// Copy user-supplied thumbnail file into the destination directory
			if ($thumExists) {
				$error .= $pgv_lang["media_thumb_exists"]."&nbsp;&nbsp;".$newThum."<br />";
			} else {
				if (!move_uploaded_file($_FILES["thumbnail"]["tmp_name"], filename_decode($newThum))) {
					// the file cannot be copied
					$error .= $pgv_lang["upload_error"]."<br />".file_upload_error_text($_FILES["thumbnail"]["error"])."<br />";
				} else {
					AddToLog("Media file {$thumbFolderName}{$mediaFile} uploaded");
				}
			}
		}
		if ($error=="" && empty($_FILES["mediafile"]["name"]) && !empty($_FILES["thumbnail"]["name"])) {
			// Copy user-supplied thumbnail file into the main destination directory
			if (!copy(filename_decode($serverThumbFolderName.$mediaFile), filename_decode($serverFolderName.$mediaFile))) {
				// the file cannot be copied
				$error .= $pgv_lang["upload_error"]."<br />".file_upload_error_text($_FILES["thumbnail"]["error"])."<br />";
			} else {
				AddToLog("Media file {$folderName}{$mediaFile} uploaded");
			}
		}
		if ($error=="" && !empty($_FILES["mediafile"]["name"]) && empty($_FILES["thumbnail"]["name"])) {
			if (!empty($_POST['genthumb']) && ($_POST['genthumb']=="yes")) {
				// Generate thumbnail from main image
				$ct = preg_match("/\.([^\.]+)$/", $mediaFile, $match);
				if ($ct>0) {
					$ext = strtolower(trim($match[1]));
					if ($ext=="jpg" || $ext=="jpeg" || $ext=="gif" || $ext=="png") {
						// note: generate_thumbnail takes folderName and thumbFoldername as input, not serverFolderName and serverThumbFolderName
						$okThumb = generate_thumbnail($folderName.$mediaFile, $thumbFolderName.$mediaFile, "OVERWRITE");
						$thumbnail = $serverThumbFolderName.$mediaFile;
						if (!$okThumb) {
							$error .= print_text("thumbgen_error",0,1);
						} else {
							print_text("thumb_genned");
							print "<br />";
							AddToLog("Media thumbnail {$thumbFolderName}{$mediaFile} generated");
						}
					}
				}
			}
		}
		// Let's see if there are any errors generated and print it
		if (!empty($error)) {
			print "<span class=\"largeError\">".$error."</span><br />\n";
			$mediaFile = "";
			$finalResult = false;
		} else $finalResult = true;
	}
	if ($mediaFile=="") {
		// No upload: should be an existing file on server
		if ($tag[0]=="FILE") {
			if (!empty($text[0])) {
				$isExternal = isFileExternal($text[0]);
				if ($isExternal) {
					$fileName = $text[0];
					$mediaFile = $fileName;
					$folderName = "";
				} else {
					$fileName = check_media_depth($text[0], "BACK");
					$mediaFile = basename($fileName);
					$folderName = dirname($fileName)."/";
				}
			}
			if ($mediaFile=="") {
				print "<span class=\"largeError\">".$pgv_lang["illegal_chars"]."</span><br />\n";
				$finalResult = false;
			} else $finalResult = true;
		} else {
			//-- check if the file is used in more than one gedcom
			//-- do not allow it to be moved or renamed if it is
			$myFile = str_replace($MEDIA_DIRECTORY, "", $oldFolder.$oldFilename);
			$sql = "SELECT 1 FROM {$TBLPREFIX}media WHERE m_file LIKE '%".$DBCONN->escapeSimple($myFile)."' AND m_gedfile<>".PGV_GED_ID;
			$res = dbquery($sql);
			$onegedcom = true;
			if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC))
				$onegedcom = false;
			$res->free();

			// Handle Admin request to rename or move media file
			if ($filename!=$oldFilename) {
				$parts = pathinfo($filename);
				if (empty($parts["extension"]) || !in_array(strtolower($parts["extension"]), $MEDIATYPE)) {
					$parts = pathinfo($oldFilename);
					$filename .= ".".$parts["extension"];
				}
			}
			if (substr($folder,-1)!="/") $folder .= "/";
			if ($folder=="/") $folder = "";
			$folder = check_media_depth($folder."y.z", "BACK");
			$folder = dirname($folder)."/";
			if (substr($oldFolder,-1)!="/") $oldFolder .= "/";
			if ($oldFolder=="/") $oldFolder = "";
			$oldFolder = check_media_depth($oldFolder."y.z", "BACK");
			$oldFolder = dirname($oldFolder)."/";
			$_SESSION["upload_folder"] = $folder; // store standard media folder in session

			$finalResult = true;
			if ($filename!=$oldFilename || $folder!=$oldFolder) {
				if (!$onegedcom) {
					print "<span class=\"largeError\">".$pgv_lang["multiple_gedcoms"]."<br /><br /><b>";
					if ($filename!=$oldFilename) print $pgv_lang["media_file_not_renamed"];
					else print $pgv_lang["media_file_not_moved"];
					print "</b></span><br />";
					$finalResult = false;
				} else {
					$oldMainFile = $oldFolder.$oldFilename;
					$newMainFile = $folder.$filename;
					$oldThumFile = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $oldMainFile);
					$newThumFile = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $newMainFile);
					if (media_exists($oldMainFile) == 3) {
						// the file is in the media firewall directory
						$oldMainFile = get_media_firewall_path($oldMainFile);
						$newMainFile = get_media_firewall_path($newMainFile);
					}
					if (media_exists($oldThumFile) == 3) {
						$oldThumFile = get_media_firewall_path($oldThumFile);
						$newThumFile = get_media_firewall_path($newThumFile);
					}
					$isMain = file_exists(filename_decode($oldMainFile));
					$okMain = !file_exists(filename_decode($newMainFile));
					$isThum = file_exists(filename_decode($oldThumFile));
					$okThum = !file_exists(filename_decode($newThumFile));
					if ($okMain && $okThum) {
						// make sure the directories exist before moving the files
						mkdirs(dirname($newMainFile)."/");
						mkdirs(dirname($newThumFile)."/");
						if ($isMain) $okMain = @rename(filename_decode($oldMainFile), filename_decode($newMainFile));
						if ($isThum) $okThum = @rename(filename_decode($oldThumFile), filename_decode($newThumFile));
					}

					// Build text to tell Admin about the success or failure of the requested operation
					$GLOBALS["oldMediaName"] = $oldFilename;
					$GLOBALS["newMediaName"] = $filename;
					$GLOBALS["oldMediaFolder"] = $oldFolder;
					$GLOBALS["newMediaFolder"] = $folder;
					$GLOBALS["oldThumbFolder"] = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $oldFolder);
					$GLOBALS["newThumbFolder"] = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $folder);
					$mediaAction = 0;
					if ($filename!=$oldFilename) $mediaAction = 1;
					if ($folder!=$oldFolder) $mediaAction = $mediaAction + 2;

					if (!$isMain) {
						print_text("main_media_fail0");
					} else {
						if ($okMain) print_text("main_media_ok".$mediaAction);
						else {
							$finalResult = false;
							print "<span class=\"largeError\">";
							print_text("main_media_fail".$mediaAction);
							print "</span>";
						}
					}
					print "<br />";

					if (!$isThum) {
						print_text("thumb_media_fail0");
					} else {
						if ($okThum) print_text("thumb_media_ok".$mediaAction);
						else {
							$finalResult = false;
							print "<span class=\"largeError\">";
							print_text("thumb_media_fail".$mediaAction);
							print "</span>";
						}
					}
					print "<br />";

					unset($GLOBALS["oldMediaName"]);
					unset($GLOBALS["newMediaName"]);
					unset($GLOBALS["oldMediaFolder"]);
					unset($GLOBALS["newMediaFolder"]);
					unset($GLOBALS["oldThumbFolder"]);
					unset($GLOBALS["newThumbFolder"]);
				}
			}

			// Insert the 1 FILE xxx record into the arrays used by function handle_updates()
			$glevels = array_merge(array("1"), $glevels);
			$tag = array_merge(array("FILE"), $tag);
			$islink = array_merge(array(0), $islink);
			$text = array_merge(array($folder.$filename), $text);

			$mediaFile = $filename;
			$folderName = $folder;
		}
	}

	if ($finalResult && $mediaFile!="") {
		// NOTE: Build the gedcom record
		// NOTE: Level 0
		$media_id = get_new_xref("OBJE");
		$newged = "0 @".$media_id."@ OBJE\r\n";
		//-- set the FILE text to the correct file location in the standard media directory
		if (PGV_USER_GEDCOM_ADMIN) $text[0] = $folderName.$mediaFile;
		else $newged .= "1 FILE ".$folderName.$mediaFile."\r\n";

		$newged = handle_updates($newged);

		require_once 'includes/class_media.php';
		$media_obje = new Media($newged);
		$mediaid = Media::in_obje_list($media_obje);
		if (!$mediaid) $mediaid = append_gedrec($newged, $linktoid);
		if ($mediaid) {
			AddToChangeLog("Media ID ".$mediaid." successfully added.");
			if ($linktoid!="") $link = linkMedia($mediaid, $linktoid, $level);
			else $link = false;
			if ($link) {
				AddToChangeLog("Media ID ".$media_id." successfully added to $linktoid.");
			} else {
				print "<a href=\"javascript:// OBJE $mediaid\" onclick=\"openerpasteid('$mediaid'); return false;\">".$pgv_lang["paste_id_into_field"]." <b>$mediaid</b></a><br /><br />\n";
				print "<script language=\"JavaScript\" type=\"text/javascript\">\n";
				print "openerpasteid('".$mediaid."');\n";
				print "</script>\n";
			}
		}
		print $pgv_lang["update_successful"];
	}
}
// **** end action "newentry"

// **** begin action "update"
if ($action == "update") {
	if (empty($level)) $level = 1;
	//-- check if the file is used in more than one gedcom
	//-- do not allow it to be moved or renamed if it is
	$myFile = str_replace($MEDIA_DIRECTORY, "", $oldFolder.$oldFilename);
	$sql = "SELECT 1 FROM {$TBLPREFIX}media WHERE m_file LIKE '%".$DBCONN->escapeSimple($myFile)."' AND m_gedfile<>".PGV_GED_ID;
	$res = dbquery($sql);
	$onegedcom = true;
	if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC))
		$onegedcom = false;
	$res->free();

	$isExternal = isFileExternal($oldFilename) || isFileExternal($filename);
	$finalResult = true;

	// Handle Admin request to rename or move media file
	if (!$isExternal) {
		if ($filename!=$oldFilename) {
			$parts = pathinfo($filename);
			if (empty($parts["extension"]) || !in_array(strtolower($parts["extension"]), $MEDIATYPE)) {
				$parts = pathinfo($oldFilename);
				$filename .= ".".$parts["extension"];
			}
		}
		if (!isset($folder) && isset($oldFolder)) $folder = $oldFolder;
		$folder = trim($folder);
		if (substr($folder,-1)!="/") $folder .= "/";
		if ($folder=="/") $folder = "";
		$folder = check_media_depth($folder."y.z", "BACK");
		$folder = dirname($folder)."/";
		if (substr($oldFolder,-1)!="/") $oldFolder .= "/";
		if ($oldFolder=="/") $oldFolder = "";
		$oldFolder = check_media_depth($oldFolder."y.z", "BACK");
		$oldFolder = dirname($oldFolder)."/";
	}

	if ($filename!=$oldFilename || $folder!=$oldFolder) {
		if (!$onegedcom) {
			print "<span class=\"largeError\">".$pgv_lang["multiple_gedcoms"]."<br /><br /><b>";
			if ($filename!=$oldFilename) print $pgv_lang["media_file_not_renamed"];
			else print $pgv_lang["media_file_not_moved"];
			print "</b></span><br />";
			$finalResult = false;
		} else if (!$isExternal) {
			$oldMainFile = $oldFolder.$oldFilename;
			$newMainFile = $folder.$filename;
			$oldThumFile = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $oldMainFile);
			$newThumFile = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $newMainFile);
			if (media_exists($oldMainFile) == 3) {
				// the file is in the media firewall directory
				$oldMainFile = get_media_firewall_path($oldMainFile);
				$newMainFile = get_media_firewall_path($newMainFile);
			}
			if (media_exists($oldThumFile) == 3) {
				$oldThumFile = get_media_firewall_path($oldThumFile);
				$newThumFile = get_media_firewall_path($newThumFile);
			}
			$isMain = file_exists(filename_decode($oldMainFile));
			$okMain = !file_exists(filename_decode($newMainFile));
			$isThum = file_exists(filename_decode($oldThumFile));
			$okThum = !file_exists(filename_decode($newThumFile));
			if ($okMain && $okThum) {
				// make sure the directories exist before moving the files
				mkdirs(dirname($newMainFile)."/");
				mkdirs(dirname($newThumFile)."/");
				if ($isMain) $okMain = @rename(filename_decode($oldMainFile), filename_decode($newMainFile));
				if ($isThum) $okThum = @rename(filename_decode($oldThumFile), filename_decode($newThumFile));
			}

			// Build text to tell Admin about the success or failure of the requested operation
			$GLOBALS["oldMediaName"] = $oldFilename;
			$GLOBALS["newMediaName"] = $filename;
			$GLOBALS["oldMediaFolder"] = $oldFolder;
			$GLOBALS["newMediaFolder"] = $folder;
			$GLOBALS["oldThumbFolder"] = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $oldFolder);
			$GLOBALS["newThumbFolder"] = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $folder);
			$mediaAction = 0;
			if ($filename!=$oldFilename) $mediaAction = 1;
			if ($folder!=$oldFolder) $mediaAction = $mediaAction + 2;

			if (!$isMain) {
				print_text("main_media_fail0");
			} else {
				if ($okMain) print_text("main_media_ok".$mediaAction);
				else {
					$finalResult = false;
					print "<span class=\"largeError\">";
					print_text("main_media_fail".$mediaAction);
					print "</span>";
				}
			}
			print "<br />";

			if (!$isThum) {
				print_text("thumb_media_fail0");
			} else {
				if ($okThum) print_text("thumb_media_ok".$mediaAction);
				else {
					$finalResult = false;
					print "<span class=\"largeError\">";
					print_text("thumb_media_fail".$mediaAction);
					print "</span>";
				}
			}
			print "<br />";

			unset($GLOBALS["oldMediaName"]);
			unset($GLOBALS["newMediaName"]);
			unset($GLOBALS["oldMediaFolder"]);
			unset($GLOBALS["newMediaFolder"]);
			unset($GLOBALS["oldThumbFolder"]);
			unset($GLOBALS["newThumbFolder"]);
		}
	}

	if ($finalResult) {
		$_SESSION["upload_folder"] = $folder; // store standard media folder in session

		// Insert the 1 FILE xxx record into the arrays used by function handle_updates()
		$glevels = array_merge(array("1"), $glevels);
		$tag = array_merge(array("FILE"), $tag);
		$islink = array_merge(array(0), $islink);
		$text = array_merge(array($folder.$filename), $text);

		$newrec = "0 @$pid@ OBJE\r\n";
		$newrec = handle_updates($newrec);
		//print("[".$newrec."]");
		//-- look for the old record media in the file
		//-- if the old media record does not exist that means it was
		//-- generated at import and we need to append it
		//$oldrec = find_record_in_file($pid);
		//if (!empty($oldrec)) {
			if (replace_gedrec($pid, $newrec)) AddToChangeLog("Media ID ".$pid." successfully updated.");
		//} else {
		//	$pid = append_gedrec($newrec);
		//	if ($pid) AddToChangeLog("Media ID ".$pid." successfully added.");
		//}

		if ($pid && $linktoid!="") {
			$link = linkMedia($pid, $linktoid, $level);
			if ($link) {
				AddToChangeLog("Media ID ".$pid." successfully added to $linktoid.");
			}
		}
	}

	if ($finalResult) print $pgv_lang["update_successful"];
}
// **** end action "update"

// **** begin action "delete"
if ($action=="delete") {
	if (delete_gedrec($pid)) {
		AddToChangeLog("Media ID ".$pid." successfully deleted.");
		print $pgv_lang["update_successful"];
	}
}
// **** end action "delete"

// **** begin action "showmedia"
// IS THIS STILL USED?
if ($action=="showmedia") {
	$medialist = get_db_media_list();
	if (count($medialist)>0) {
		print "<table class=\"list_table\">\n";
		print "<tr><td class=\"list_label\">".$pgv_lang["delete"]."</td><td class=\"list_label\">".$pgv_lang["title"]."</td><td class=\"list_label\">".$pgv_lang["gedcomid"]."</td>\n";
		print "<td class=\"list_label\">".$factarray["FILE"]."</td><td class=\"list_label\">".$pgv_lang["highlighted"]."</td><td class=\"list_label\">order</td><td class=\"list_label\">gedcom</td></tr>\n";
		foreach($medialist as $indexval => $media) {
			print "<tr>";
			print "<td class=\"list_value\"><a href=\"".encode_url("addmedia.php?action=delete&m_id=".$media["ID"])."\">delete</a></td>";
			print "<td class=\"list_value\"><a href=\"".encode_url("addmedia.php?action=edit&m_id=".$media["ID"])."\">edit</a></td>";
			print "<td class=\"list_value\">".$media["TITL"]."</td>";
			print "<td class=\"list_value\">";
			$person=Person::getInstance($media["INDI"]);
			if ($person) {
				echo $person->format_list('span');
			} else {
				echo $pgv_lang['unknown'];
			}
			print "</td>";
			print "<td class=\"list_value\">".$media["FILE"]."</td>";
			print "<td class=\"list_value\">".$media["_PRIM"]."</td>";
			print "<td class=\"list_value\">".$media["ORDER"]."</td>";
			print "<td class=\"list_value\">".$media["GEDFILE"]."</td>";
			print "</tr>\n";
		}
		print "</table>\n";
	}
}
// **** end action "showmedia"


// **** begin action "showmediaform"
if ($action=="showmediaform") {
	if (!isset($pid)) $pid = "";
	if (empty($level)) $level = 1;
	if (!isset($linktoid)) $linktoid = "";
	show_media_form($pid, "newentry", $filename, $linktoid, $level);
}
// **** end action "showmediaform"


// **** begin action "editmedia"
if ($action=="editmedia") {
	if (!isset($pid)) $pid = "";
	if (empty($level)) $level = 1;
	show_media_form($pid, "update", $filename, $linktoid, $level);
}
// **** end action "editmedia"

print "<br />";
print "<div class=\"center\"><a href=\"#\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a></div>\n";
print "<br />";
print_simple_footer();
?>
