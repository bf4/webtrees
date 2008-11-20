<?php
/**
 * Popup window that will allow a user to search for a media
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
 * @author PGV Development Team
 * @package PhpGedView
 * @subpackage Display
 * @version $Id$
 */

 /* TODO:
 *	Add check for missing index.php files when creating a directory
 *	Add an option to generate thumbnails for all files on the page
 *	Add filter for correct media like php, gif etc.
 *  Check for URL instead of physical file
 *	Check array buld up use ID_GEDCOM for aray key
 */

 /* Standard variable convention media.php
 *	$filename = Filename of the media item
 *	$thumbnail = Filename of the thumbnail of the media item
 *	$gedfile = Name of the GEDCOM file
 *	$medialist = Array with all media items
 *  $directory = Current directory, starting with $MEDIA_DIRECTORY.  Has trailing "/".
 *  $dirs = list of subdirectories within current directory.  Built with medialist.
 */

require './config.php';

require_once 'includes/functions_print_lists.php';
require_once 'includes/functions_print_facts.php';
require_once 'includes/functions_edit.php';
require_once 'includes/functions_import.php';
require_once 'includes/functions_mediadb.php';

if (isset($_REQUEST['DEBUG'])) $DEBUG = $_REQUEST['DEBUG'];
else $DEBUG = false;

/**
 * This functions checks if an existing directory is physically writeable
 * The standard PHP function only checks for the R/O attribute and doesn't
 * detect authorisation by ACL.
 */
function dir_is_writable($dir) {
	$err_write = false;
	$handle = @fopen(filename_decode($dir."x.y"),"w+");
	if	($handle) {
		$i = fclose($handle);
		$err_write = true;
		@unlink(filename_decode($dir."x.y"));
	}
	return($err_write);
}

/**
 * Moves a file from one location to another, creating destination directory if needed
 * used by the routines that move files between the standard media directory and the protected media directory
 */
function move_file($src, $dest) {
	global $pgv_lang, $MEDIA_FIREWALL_ROOTDIR, $MEDIA_DIRECTORY;

	// sometimes thumbnail files are set to something like "images/media.gif", this ensures we do not move them
	// check to make sure the src file is in the standard or protected media directories
	if (preg_match("'^($MEDIA_FIREWALL_ROOTDIR)?$MEDIA_DIRECTORY'", $src)==0){
		return false;
	}
	// check to make sure the dest file is in the standard or protected media directories
	if (preg_match("'^($MEDIA_FIREWALL_ROOTDIR)?$MEDIA_DIRECTORY'", $dest)==0){
		return false;
	}

	$destdir = dirname($dest);
	if (!is_dir($destdir)) {
		@mkdirs($destdir);
		if (!is_dir($destdir)) {
			print "<div class=\"error\">".$pgv_lang["folder_no_create"]." [".$destdir."]</div>";
			return false;
		}
	}
	if(!rename($src, $dest)) {
		print "<div class=\"error\">".$pgv_lang["media_file_not_moved"]." [".$src."]</div>";
		return false;
	}
	print "<div>".$pgv_lang["media_file_moved"]." [".$src."]</div>";
	return true;
}

/**
* Recursively moves files from standard media directory to the protected media directory
* and vice-versa.  Operates directly on the filesystem, does not use the db.
*/
function move_files($path, $protect) {
	global $MEDIA_FIREWALL_THUMBS, $TIME_LIMIT, $starttime, $pgv_lang;
	if ($dir=@opendir($path)) {
		while (($element=readdir($dir))!== false) {
			$timelimit = $TIME_LIMIT ? $TIME_LIMIT : 0;
			$exectime = time() - $starttime;
			if (($timelimit != 0) && ($timelimit - $exectime) < 3) {
				// bail now to ensure nothing is lost
				print "<div class=\"error\">".$pgv_lang["move_time_exceeded"]."</div>";
				return;
			}
			// do not move certain files...
			if ($element!= "." && $element!= ".." && $element!=".svn" && $element!="watermark" && $element!="thumbs" && $element!=".htaccess" && $element!="index.php" && $element!="MediaInfo.txt" && $element!="ThumbsInfo.txt") {
				$filename = $path."/".$element;
				if (is_dir($filename)) {
					// call this function recursively on this directory
					move_files($filename, $protect);
				} else {
					if ($protect) {
						// Move single file and optionally its corresponding thumbnail to protected dir
						if (file_exists($filename)) {
							move_file($filename, get_media_firewall_path($filename));
						}
						if ($MEDIA_FIREWALL_THUMBS) {
							$thumbnail = thumbnail_file($filename,false);
							if (file_exists($thumbnail)) {
								move_file($thumbnail, get_media_firewall_path($thumbnail));
							}
						}
					} else {
						// Move single file and its corresponding thumbnail to standard dir
						$filename = get_media_standard_path($filename);
						if (file_exists(get_media_firewall_path($filename))) {
							move_file(get_media_firewall_path($filename), $filename);
						}
						$thumbnail = thumbnail_file($filename,false);
						if (file_exists(get_media_firewall_path($thumbnail))) {
							move_file(get_media_firewall_path($thumbnail), $thumbnail);
						}
					}
				}
			}
		}
		print "</td></tr></table>";
		$action="filter";
		closedir($dir);
	}
	return;
}

/**
* Recursively sets the permissions on files
* Operates directly on the filesystem, does not use the db.
*/
function set_perms($path) {
	global $MEDIA_FIREWALL_ROOTDIR, $MEDIA_DIRECTORY, $TIME_LIMIT, $starttime, $pgv_lang;
	if (preg_match("'^($MEDIA_FIREWALL_ROOTDIR)?$MEDIA_DIRECTORY'", $path."/")==0){
		return false;
	}
	if ($dir=@opendir($path)) {
		while (($element=readdir($dir))!== false) {
			$timelimit = $TIME_LIMIT ? $TIME_LIMIT : 0;
			$exectime = time() - $starttime;
			if (($timelimit != 0) && ($timelimit - $exectime) < 3) {
				// bail now to ensure nothing is lost
				print "<div class=\"error\">".$pgv_lang["setperms_time_exceeded"]."</div>";
				return;
			}
			// do not set perms on certain files...
			if ($element!= "." && $element!= ".." && $element!=".svn") {
				$fullpath = $path."/".$element;
				if (is_dir($fullpath)) {
					if (@chmod($fullpath, PGV_PERM_EXE)) {
						print "<div>".$pgv_lang["setperms_success"]." [".decoct(PGV_PERM_EXE)."] [".$fullpath."]</div>";
					} else {
						print "<div>".$pgv_lang["setperms_failure"]." [".decoct(PGV_PERM_EXE)."] [".$fullpath."]</div>";
					}
					// call this function recursively on this directory
					set_perms($fullpath);
				} else {
					if (@chmod($fullpath, PGV_PERM_FILE)) {
						print "<div>".$pgv_lang["setperms_success"]." [".decoct(PGV_PERM_FILE)."] [".$fullpath."]</div>";
					} else {
						print "<div>".$pgv_lang["setperms_failure"]." [".decoct(PGV_PERM_FILE)."] [".$fullpath."]</div>";
					}
				}
			}
		}
		closedir($dir);
	}
	return;
}

// global var used by recursive functions
$starttime = time();

// TODO Determine source and validation requirements for these variables
$filename=safe_REQUEST($_REQUEST, 'filename');
$directory=safe_REQUEST($_REQUEST, 'directory', PGV_REGEX_NOSCRIPT, $MEDIA_DIRECTORY);
$movetodir=safe_REQUEST($_REQUEST, 'movetodir');
$movefile=safe_REQUEST($_REQUEST, 'movefile');
$action=safe_REQUEST($_REQUEST, 'action', PGV_REGEX_ALPHA, 'filter');
$subclick=safe_REQUEST($_REQUEST, 'subclick', PGV_REGEX_ALPHA, 'none');
$media=safe_REQUEST($_REQUEST, 'media');
$filter=safe_REQUEST($_REQUEST, 'filter', PGV_REGEX_NOSCRIPT);

$level=safe_REQUEST($_REQUEST, 'level', PGV_REGEX_INTEGER, 0);
$start=safe_REQUEST($_REQUEST, 'start', PGV_REGEX_INTEGER, 0);
$max=safe_REQUEST($_REQUEST, 'max', PGV_REGEX_INTEGER, 20);

$showthumb=safe_REQUEST($_REQUEST, 'showthumb');

$all=safe_REQUEST($_REQUEST, 'all', 'yes', 'no');

if (isset($_REQUEST['xref'])) $xref = $_REQUEST['xref'];

if (count($_POST) == 0) $showthumb = true;

$thumbget = "";
if ($showthumb) $thumbget = "&showthumb=true";

//-- prevent script from accessing an area outside of the media directory
//-- and keep level consistency
if (($level < 0) || ($level > $MEDIA_DIRECTORY_LEVELS)){
	$directory = $MEDIA_DIRECTORY;
	$level = 0;
} elseif (preg_match("'^$MEDIA_DIRECTORY'", $directory)==0){
	$directory = $MEDIA_DIRECTORY;
	$level = 0;
}

$thumbdir = str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $directory);
$directory_fw = get_media_firewall_path($directory);
$thumbdir_fw = get_media_firewall_path($thumbdir);

//-- only allow users with Admin privileges to access script.
if (!PGV_USER_IS_ADMIN || !$ALLOW_EDIT_GEDCOM) {
	header("Location: login.php?url=media.php");
	exit;
}

//-- TODO add check for -- admin can manipulate files
$fileaccess = false;
if (PGV_USER_IS_ADMIN) {
	$fileaccess = true;
}

// Print the header of the page
print_header($pgv_lang["manage_media"]);
?>
<script language="JavaScript" type="text/javascript">
<!--
function pasteid(id) {
	window.opener.paste_id(id);
	window.close();
}

function ilinkitem(mediaid, type) {
	window.open('inverselink.php?mediaid='+mediaid+'&linkto='+type+'&'+sessionname+'='+sessionid, '_blank', 'top=50,left=50,width=400,height=300,resizable=1,scrollbars=1');
	return false;
}

function checknames(frm) {
	if (document.managemedia.subclick) button = document.managemedia.subclick.value;
	if (button == "all") {
		frm.filter.value = "";
		return true;
	}
	else if (frm.filter.value.length < 2) {
		alert("<?php print $pgv_lang["search_more_chars"]; ?>");
		frm.filter.focus();
		return false;
	}
	return true;
}

function checkpath(folder) {
	value = folder.value;
	if (value.substr(value.length-1,1) == "/") value = value.substr(0, value.length-1);
	if (value.substr(0,1) == "/") value = value.substr(1, value.length-1);
	result = value.split("/");
	if (result.length > <?php print $MEDIA_DIRECTORY_LEVELS; ?>) {
		alert('<?php print_text("max_media_depth"); ?>');
		folder.focus();
		return false;
	}
}

function showchanges() {
	window.location = '<?php print $SCRIPT_NAME."?show_changes=yes&directory=".$directory."&level=".$level."&filter=".$filter."&subclick=".$subclick; ?>';
}

//-->
</script>
<script src="phpgedview.js" language="JavaScript" type="text/javascript"></script>
<form name="managemedia" method="post" onsubmit="return checknames(this);" action="media.php">
	<input type="hidden" name="directory" value="<?php print $directory; ?>" />
	<input type="hidden" name="thumbdir" value="<?php print $thumbdir; ?>" />
	<input type="hidden" name="level" value="<?php print $level; ?>" />
	<input type="hidden" name="all" value="true" />
	<input type="hidden" name="subclick" />
	<table class="fact_table center width100 <?php print $TEXT_DIRECTION; ?>">
	<tr><td class="topbottombar" colspan="6"><?php print_help_link("manage_media_help","qm","manage_media");print $pgv_lang["manage_media"]; ?></td></tr>
	<!-- // NOTE: Filter options -->
	<tr><td class="descriptionbox wrap width25"><?php print_help_link("simple_filter_help","qm","filter"); print $pgv_lang["filter"];?></td>
	<td class="optionbox wrap"><input type="text" name="filter" value="<?php if($filter) print $filter;?>" /><br /><input type="submit" name="search" value="<?php print $pgv_lang["filter"];?>" onclick="this.form.subclick.value=this.name" />&nbsp;&nbsp;&nbsp;<input type="submit" name="all" value="<?php print $pgv_lang["display_all"]; ?>" onclick="this.form.subclick.value=this.name" /></td>

	<!-- // NOTE: Upload media files -->
	<td class="descriptionbox wrap width25"><?php print_help_link("upload_media_help","qm","upload_media"); print $pgv_lang["upload_media"]; ?></td>
	<td class="optionbox wrap"><?php print "<a href=\"#\" onclick=\"expand_layer('uploadmedia');\">".$pgv_lang["upload_media"]."</a>"; ?></td></tr>

	<!-- // NOTE: Show thumbnails -->
	<tr><td class="descriptionbox wrap width25"><?php print_help_link("show_thumb_help","qm", "show_thumbnail"); ?><?php print $pgv_lang["show_thumbnail"]; ?></td>
	<td class="optionbox wrap"><input type="checkbox" name="showthumb" value="true" <?php if ($showthumb) print "checked=\"checked\""; ?> onclick="submit();" /></td>

	<!-- // NOTE: Add media -->
	<td class="descriptionbox wrap width25"><?php print_help_link("add_media_help", "qm"); ?><?php print $pgv_lang["add_media_lbl"]; ?></td>
	<td class="optionbox wrap"><a href="javascript: <?php echo $pgv_lang["add_media_lbl"]; ?>" onclick="window.open('addmedia.php?action=showmediaform&linktoid=new', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1'); return false;"> <?php echo $pgv_lang["add_media"]; ?></a></td></tr>

	<!-- // NOTE: empty -->
	<tr><td class="descriptionbox wrap width25"></td>
	<td class="optionbox wrap"></td>

	<!-- // NOTE: Generate missing thumbnails -->
	<?php
		$tempURL = "media.php?";
		if (!empty($filter)) $tempURL .= "filter={$filter}&";
		if (!empty($subclick)) $tempURL .= "subclick={$subclick}&";
		$tempURL .= "action=thumbnail&all=yes&level={$level}&directory={$directory}".$thumbget;
		?>
	<td class="descriptionbox wrap width25"><?php print_help_link("gen_missing_thumbs_help", "qm"); ?><?php print $pgv_lang["gen_missing_thumbs_lbl"]; ?></td>
	<td class="optionbox wrap"><a href="<?php print encode_url($tempURL);?>"><?php print $pgv_lang["gen_missing_thumbs"];?></a></td></tr>
	</table>
</form>
<?php

// NOTE: Here is checked if the media structure is OK, if not , we cannot continue
if (check_media_structure()) {
/**
 * This action creates a new directory in the current directory
 *
 * Checks are made for relative filename exploits.
 * The index.php is created which points back to the medialist page
 * The same is done for the thumbnail directory to keep filesystem consistent
 *
 * Directory access checks are done during menu creation so the user
 * cannot create directories deeper than the configured level
 *
 * @name $action->newdir
 */
	if ($action == "newdir") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		// security checks, no names with . .. / \ in them
		// add more if required
		$clean = !empty($newdir);
		$badchars = array(".","/","\\");
		for ($i = 0; $i < sizeof($badchars); $i++) {
			$pos = strpos($newdir,$badchars[$i]);
			if (is_bool($pos)) continue;
			$clean = false;
			break;
		}
		if ($clean) {
			$res = mkdir($directory.$newdir);
			$res = mkdir($thumbdir.$newdir);
			if (file_exists($directory."index.php")) {
				$inddata = file_get_contents($directory."index.php");
				$inddata = str_replace(": ../",": ../../",$inddata);
				$fp = @fopen($directory.$newdir."/index.php","w+");
				if (!$fp) print "<div class=\"error\">".$pgv_lang["security_no_create"].$directory.$newdir."</div>";
				else {
					fputs($fp,$inddata);
					fclose($fp);
				}
			}
			else print "<div class=\"error\">".$pgv_lang["security_not_exist"].$directory."</div>";

			if (file_exists($thumbdir."index.php")) {
				$inddata = file_get_contents($thumbdir."index.php");
				$inddata = str_replace(": ../",": ../../",$inddata);
				$fp = @fopen($thumbdir.$newdir."/index.php","w+");
				if (!$fp) print "<div class=\"error\">".$pgv_lang["security_no_create"].$thumbdir.$newdir."</div>";
				else {
					fputs($fp,$inddata);
					fclose($fp);
				}
			}
			else print "<div class=\"error\">".$pgv_lang["security_not_exist"].$thumbdir."</div>";
		}
		else {
			print "<div class=\"error\">".$pgv_lang["illegal_chars"]."</div>";
		}

		$action="filter";
		print "</td></tr></table>";
	}

	if ($action == "deletedir") {
		print "<table class=\"list_table center width100\">";
		print "<tr><td class=\"messagebox\">";
		// Check if media directory and thumbs directory are empty
		$clean = false;
		$files = array();
		$thumbfiles = array();
		$files_fw = array();
		$thumbfiles_fw = array();
		$resdir = false;
		$resthumb = false;
		// Media directory check
		if (@is_dir(filename_decode($directory))) {
			$handle = opendir(filename_decode($directory));
			$files = array();
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $BADMEDIA)) $files[] = $file;
			}
		}
		else {
			print "<div class=\"error\">".$directory." ".$pgv_lang["directory_not_exist"]."</div>";
			AddToLog($directory." ".$pgv_lang["directory_not_exist"]);
		}

		// Thumbs directory check
		if (@is_dir(filename_decode($thumbdir))) {
			$handle = opendir(filename_decode($thumbdir));
			$thumbfiles = array();
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $BADMEDIA)) $thumbfiles[] = $file;
			}
			closedir($handle);
		}

		// Media Firewall Media directory check
		if (@is_dir(filename_decode($directory_fw))) {
			$handle = opendir(filename_decode($directory_fw));
			$files_fw = array();
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $BADMEDIA)) $files_fw[] = $file;
			}
		}

		// Media Firewall Thumbs directory check
		if (@is_dir(filename_decode($thumbdir_fw))) {
			$handle = opendir(filename_decode($thumbdir_fw));
			$thumbfiles_fw = array();
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $BADMEDIA)) $thumbfiles_fw[] = $file;
			}
			closedir($handle);
		}

		if (!isset($error)) {
			if (count($files) > 0 ) {
				print "<div class=\"error\">".$directory." -- ".$pgv_lang["directory_not_empty"]."</div>";
				AddToLog($directory." -- ".$pgv_lang["directory_not_empty"]);
				$clean = false;
			}
			if (count($thumbfiles) > 0) {
				print "<div class=\"error\">".$thumbdir." -- ".$pgv_lang["directory_not_empty"]."</div>";
				AddToLog($thumbdir." -- ".$pgv_lang["directory_not_empty"]);
				$clean = false;
			}
			if (count($files_fw) > 0 ) {
				print "<div class=\"error\">".$directory_fw." -- ".$pgv_lang["directory_not_empty"]."</div>";
				AddToLog($directory_fw." -- ".$pgv_lang["directory_not_empty"]);
				$clean = false;
			}
			if (count($thumbfiles_fw) > 0) {
				print "<div class=\"error\">".$thumbdir_fw." -- ".$pgv_lang["directory_not_empty"]."</div>";
				AddToLog($thumbdir_fw." -- ".$pgv_lang["directory_not_empty"]);
				$clean = false;
			}
			else $clean = true;
		}

		// Only start deleting if all directories are empty
		if ($clean) {
			$resdir = true;
			$resthumb = true;
			$resdir_fw = true;
			$resthumb_fw = true;
			if (file_exists(filename_decode($directory."index.php"))) @unlink(filename_decode($directory."index.php"));
			if (@is_dir(filename_decode($directory))) $resdir = @rmdir(filename_decode(substr($directory,0,-1)));
			if (file_exists(filename_decode($thumbdir."index.php"))) @unlink(filename_decode($thumbdir."index.php"));
			if (@is_dir(filename_decode($thumbdir))) $resthumb = @rmdir(filename_decode(substr($thumbdir,0,-1)));
			if (file_exists(filename_decode($directory_fw."index.php"))) @unlink(filename_decode($directory_fw."index.php"));
			if (@is_dir(filename_decode($directory_fw))) $resdir_fw = @rmdir(filename_decode(substr($directory_fw,0,-1)));
			if (file_exists(filename_decode($thumbdir_fw."index.php"))) @unlink(filename_decode($thumbdir_fw."index.php"));
			if (@is_dir(filename_decode($thumbdir_fw))) $resthumb_fw = @rmdir(filename_decode(substr($thumbdir_fw,0,-1)));
			if ($resdir && $resthumb && $resdir_fw && $resthumb_fw) {
				print $pgv_lang["delete_dir_success"];
				AddToLog($directory." -- ".$pgv_lang["delete_dir_success"]);
			} else {
				if (!$resdir) {
					print "<div class=\"error\">".$pgv_lang["media_not_deleted"]."</div>";
					AddToLog($directory." -- ".$pgv_lang["media_not_deleted"]);
				} else if (!$resdir_fw) {
					print "<div class=\"error\">".$pgv_lang["media_not_deleted"]."</div>";
					AddToLog($directory_fw." -- ".$pgv_lang["media_not_deleted"]);
				} else {
					print $pgv_lang["media_deleted"];
					AddToLog($directory." -- ".$pgv_lang["media_deleted"]);
				}
				if (!$resthumb) {
					print "<div class=\"error\">".$pgv_lang["thumbs_not_deleted"]."</div>";
					AddToLog($thumbdir." -- ".$pgv_lang["thumbs_not_deleted"]);
				} else if (!$resthumb_fw) {
					print "<div class=\"error\">".$pgv_lang["thumbs_not_deleted"]."</div>";
					AddToLog($thumbdir_fw." -- ".$pgv_lang["thumbs_not_deleted"]);
				} else {
					print $pgv_lang["thumbs_deleted"];
					AddToLog($thumbdir." -- ".$pgv_lang["thumbs_deleted"]);
				}

			}
		}

		// Back up to this directory's parent
		$i = strrpos(substr($directory,0,-1), '/');
		$directory = trim(substr($directory,0,$i), '/').'/';
		$action="filter";
		print "</td></tr></table>";
	}

/**
 * This action generates a thumbnail for the file
 *
 * @name $action->thumbnail
 */
	if ($action == "thumbnail") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		// TODO: add option to generate thumbnails for all images on page
		// Cycle through $medialist and skip all exisiting thumbs

		// Check if $all is true, if so generate thumbnails for all files that do
		// not yet have any thumbnails created. Otherwise only the file specified.
		if ($all == 'yes') {
			$medialist = get_medialist(true, $directory);
			foreach ($medialist as $key => $media) {
				if (!($MEDIA_EXTERNAL && isFileExternal($filename))) {
					// why doesn't this use thumbnail_file??
					$thumbnail = str_replace("$MEDIA_DIRECTORY",$MEDIA_DIRECTORY."thumbs/",check_media_depth($media["FILE"], "NOTRUNC"));
					if (!$media["THUMBEXISTS"]) {
						if (generate_thumbnail($media["FILE"],$thumbnail)) {
							print_text("thumb_genned");
							AddToChangeLog(print_text("thumb_genned",0,1));
						}
						else {
							print "<span class=\"error\">";
							print_text("thumbgen_error");
							print "</span>";
							AddToChangeLog(print_text("thumbgen_error",0,1));
						}
						print "<br />";
					}
				}
			}
		}
		else if ($all != 'yes') {
			if (!($MEDIA_EXTERNAL && isFileExternal($filename))) {
				$thumbnail = str_replace("$MEDIA_DIRECTORY",$MEDIA_DIRECTORY."thumbs/",check_media_depth($filename, "NOTRUNC"));
				if (generate_thumbnail($filename,$thumbnail)) {
					print_text("thumb_genned");
					AddToChangeLog(print_text("thumb_genned",0,1));
				}
				else {
					print "<span class=\"error\">";
					print_text("thumbgen_error");
					print "</span>";
					AddToChangeLog(print_text("thumbgen_error",0,1));
				}
			}
		}
		$action = "filter";
		print "</td></tr></table>";
	}

	// Move single file and optionally its corresponding thumbnail to protected dir
	if ($action == "moveprotected") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		if (strpos($filename,"../") !== false) {
			// don't allow user to access directories outside of media dir
			print "<div class=\"error\">".$pgv_lang["illegal_chars"]."</div>";
		} else {
			if (file_exists($filename)) {
				move_file($filename, get_media_firewall_path($filename));
			}
			if ($MEDIA_FIREWALL_THUMBS) {
				$thumbnail = thumbnail_file($filename,false);
				if (file_exists($thumbnail)) {
					move_file($thumbnail, get_media_firewall_path($thumbnail));
				}
			}
		}
		print "</td></tr></table>";
		$action="filter";
	}

	// Move single file and its corresponding thumbnail to standard dir
	if ($action == "movestandard") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		if (strpos($filename,"../") !== false) {
			// don't allow user to access directories outside of media dir
			print "<div class=\"error\">".$pgv_lang["illegal_chars"]."</div>";
		} else {
			if (file_exists(get_media_firewall_path($filename))) {
				move_file(get_media_firewall_path($filename), $filename);
			}
			$thumbnail = thumbnail_file($filename,false);
			if (file_exists(get_media_firewall_path($thumbnail))) {
				move_file(get_media_firewall_path($thumbnail), $thumbnail);
			}
		}
		print "</td></tr></table>";
		$action="filter";
	}

	// Move entire dir and all subdirs to protected dir
	if ($action == "movedirprotected") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		print "<strong>".$pgv_lang["move_protected"]."<br />";
		move_files(substr($directory,0,-1), true);
		print "</td></tr></table>";
		$action="filter";
	}

	// Move entire dir and all subdirs to standard dir
	if ($action == "movedirstandard") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		print "<strong>".$pgv_lang["move_standard"]."<br />";
		move_files(substr(get_media_firewall_path($directory),0,-1), false);
		print "</td></tr></table>";
		$action="filter";
	}

	if ($action == "setpermsfix") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		print "<strong>".$pgv_lang["setperms_fix"]."<br />";
		set_perms(substr($directory,0,-1));
		set_perms(substr(get_media_firewall_path($directory),0,-1));
		print "</td></tr></table>";
		$action="filter";
	}

	// Upload media items
	if ($action == "upload") {
		process_uploadMedia_form();
		$medialist = get_medialist();
		$action = "filter";
	}

	print "<div id=\"uploadmedia\" style=\"display:none\">";
	// Check if Media Directory is writeable or if Media features are enabled
	// If one of these is not true then do not continue
	if (!dir_is_writable($MEDIA_DIRECTORY) || !$MULTI_MEDIA) {
		print "<span class=\"error\"><b>";
		print $pgv_lang["no_upload"];
		print "</b></span><br />";
	} else {
		show_mediaUpload_form('media.php', $showthumb);		// We have the green light to upload media, print the form
	}

	print "</div><br />";

	$allowDelete = true;
	$removeObject = true;
	// Remove object: same as Delete file, except file isn't deleted
	if ($action == "removeobject") {
		$action = "deletefile";
		$allowDelete = false;
		$removeObject = true;
	}

	// Remove link: same as Delete file, except file isn't deleted
	if ($action == "removelinks") {
		$action = "deletefile";
		$allowDelete = false;
		$removeObject = false;
	}

	// Delete file
	if ($action == "deletefile") {
		print "<table class=\"list_table $TEXT_DIRECTION width100\">";
		print "<tr><td class=\"messagebox wrap\">";
		$xrefs = array($xref);
		$onegedcom = true;
		if ($allowDelete) {
			//-- get all of the XREFS associated with this record
			//-- and check if the file is used in multiple gedcoms
			$myFile = str_replace($MEDIA_DIRECTORY, "", $filename);
			//-- figure out how many levels are in this file
			$mlevels = preg_split("~[/\\\]~", $filename);
			$sql = "SELECT * FROM ".$TBLPREFIX."media WHERE m_file ".PGV_DB_LIKE." '%".$DBCONN->escapeSimple($myFile)."'";
			$res = dbquery($sql);

			while($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
				$rlevels = preg_split("~[/\\\]~", $row["m_file"]);
				//-- make sure we only delete a file at the same level of directories
				//-- see 1825257
				$match = true;
				$k=0;
				$i=count($rlevels)-1;
				$j=count($mlevels)-1;
				while($i>=0 && $j>=0) {
					if ($rlevels[$i] != $mlevels[$j]) {
						$match = false;
						break;
					}
					$j--;
					$i--;
					$k++;
					if ($k>$MEDIA_DIRECTORY_LEVELS) break;
				}
				if ($match) {
					if ($row["m_gedfile"]!=PGV_GED_ID) $onegedcom = false;
					else $xrefs[] = $row["m_media"];
				}
			}
			$res->free();
			$xrefs = array_unique($xrefs);
		}

		$finalResult = true;
		if ($allowDelete) {
			if (!$onegedcom) {
				print "<span class=\"error\">".$pgv_lang["multiple_gedcoms"]."<br /><br /><b>".$pgv_lang["media_file_not_deleted"]."</b></span><br />";
				$finalResult = false;
			}
			if (isFileExternal($filename)) {
				print "<span class=\"error\">".$pgv_lang["external_file"]."<br /><br /><b>".$pgv_lang["media_file_not_deleted"]."</b></span><br />";
				$finalResult = false;
			}
			if ($finalResult) {
				// Check if file exists. If so, delete it
				$server_filename = get_server_filename($filename);
				if (file_exists($server_filename) && $allowDelete) {
					if (@unlink($server_filename)) {
						print $pgv_lang["media_file_deleted"]."<br />";
						AddToChangeLog($server_filename." -- ".$pgv_lang["media_file_deleted"]);
					} else {
						$finalResult = false;
						print "<span class=\"error\">".$pgv_lang["media_file_not_deleted"]."</span><br />";
						AddToChangeLog($server_filename." -- ".$pgv_lang["media_file_not_deleted"]);
					}
				}

				// Check if thumbnail exists. If so, delete it.
				$thumbnail = str_replace("$MEDIA_DIRECTORY",$MEDIA_DIRECTORY."thumbs/",$filename);
				$server_thumbnail = get_server_filename($thumbnail);
				if (file_exists($server_thumbnail) && $allowDelete) {
					if (@unlink($server_thumbnail)) {
						print $pgv_lang["thumbnail_deleted"]."<br />";
						AddToChangeLog($server_thumbnail." -- ".$pgv_lang["thumbnail_deleted"]);
					} else {
						$finalResult = false;
						print "<span class=\"error\">".$pgv_lang["thumbnail_not_deleted"]."</span><br />";
						AddToChangeLog($server_thumbnail." -- ".$pgv_lang["thumbnail_not_deleted"]);
					}
				}
			}
		}

		//-- loop through all of the found xrefs and delete any references to them
		foreach($xrefs as $ind=>$xref) {
			// Remove references to media file from gedcom and database
			// Check for XREF
			if ($xref != "") {
				$links = get_media_relations($xref);
				foreach($links as $pid=>$type) {
					if (isset($pgv_changes[$pid."_".$GEDCOM])) $gedrec = find_updated_record($pid);
					else $gedrec = find_gedcom_record($pid);
					$gedrec = remove_subrecord($gedrec, "OBJE", $xref, -1);
					if (replace_gedrec($pid, $gedrec, true, $xref)) {
						print_text("record_updated");
						AddToChangeLog(print_text("record_updated",0,1));
					} else {
						$finalResult = false;
						print "<span class=\"error\">";
						print_text("record_not_updated");
						print "</span>";
						AddToChangeLog(print_text("record_not_updated",0,1));
					}
					print "<br />";
				}

				// Record changes to the Media object
					//-- why do we accept changes just to delete the item?
					accept_changes($xref."_".$GEDCOM);
					$objerec = find_gedcom_record($xref);

					// Remove media object from gedcom
					if (delete_gedrec($xref)) {
						print_text("record_removed");
						AddToChangeLog(print_text("record_removed",0,1));
					} else {
						$finalResult = false;
						print "<span class=\"error\">";
						print_text("record_not_removed");
						print "</span>";
						AddToChangeLog(print_text("record_not_removed",0,1));
					}
					print "<br />";

					// Add the same file as a new object
					if ($finalResult && !$removeObject && $objerec!="") {
						$xref = get_new_xref("OBJE");
						$objerec = preg_replace("/0 @.*@ OBJE/", "0 @".$xref."@ OBJE", $objerec);
						if(append_gedrec($objerec)) {
							print_text("record_added");
							AddToChangeLog(print_text("record_added",0,1));
						} else {
							$finalResult = false;
							print "<span class=\"error\">";
							print_text("record_not_added");
							print "</span>";
							AddToChangeLog(print_text("record_not_added",0,1));
						}
						print "<br />";
					}
			}
		}
		if ($finalResult) print $pgv_lang["update_successful"];
		$action = "filter";
		print "</td></tr></table>";
	}

/**
 * Generate link flyout menu
 *
 * @param string $mediaid
 */
	function print_link_menu($mediaid) {
		global $pgv_lang, $TEXT_DIRECTION;

		$classSuffix = "";
		if ($TEXT_DIRECTION=="rtl") $classSuffix = "_rtl";

		// main link displayed on page
		$menu = array();
		$menu["label"] = $pgv_lang["set_link"];
		$menu["link"] = "#";
		$menu["onclick"] = "return ilinkitem('$mediaid','person')";
//		$menu["class"] = "thememenuitem";
		$menu["class"] = "";
		$menu["hoverclass"] = "";
		$menu["submenuclass"] = "submenu";
		$menu["flyout"] = "left";
		$menu["items"] = array();

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_person"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','person')";
		$menu["items"][] = $submenu;

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_family"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','family')";
		$menu["items"][] = $submenu;

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_source"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','source')";
		$menu["items"][] = $submenu;

		print_menu($menu);
	}

	if ($action == "filter") {
		if (empty($directory)) $directory = $MEDIA_DIRECTORY;
		$medialist = get_medialist(true, $directory);
// Get the list of media items
/**
 * This is the default action for the page
 *
 * Displays a list of dirs and files. Displaying only
 * thumbnails as the images may be large and we do not want large delays
 * while administering the file structure
 *
 * @name $action->filter
 */
		// Show link to previous folder
		if ($level>0) {
			$levels = explode("/", $directory);
			$pdir = "";
			for($i=0; $i<count($levels)-2; $i++) $pdir.=$levels[$i]."/";

			$uplink = "<a href=\"".encode_url("media.php?directory={$pdir}&level=".($level-1).$thumbget)."\">";
			if ($TEXT_DIRECTION=="rtl") $uplink .= getLRM();
			$uplink .= $pdir;
			if ($TEXT_DIRECTION=="rtl") $uplink .= getLRM();
			$uplink .= "</a>\n";

			$uplink2 = "<a href=\"".encode_url("media.php?directory={$pdir}&level=".($level-1).$thumbget)."\"><img class=\"icon\" src=\"".$PGV_IMAGE_DIR."/";
			if ($TEXT_DIRECTION=="ltr") $uplink2 .= $PGV_IMAGES["larrow"]["other"];
			else $uplink2 .= $PGV_IMAGES["rarrow"]["other"];
			$uplink2 .= "\" alt=\"".PrintReady($pdir)."\" title=\"".PrintReady($pdir)."\" /></a>\n";
		}

		// Start of media directory table
		print "<table class=\"list_table width100 $TEXT_DIRECTION\">";

		// Tell the user where he is
		print "<tr>";
			print "<td class=\"topbottombar\" colspan=\"2\">";
				print $pgv_lang["current_dir"];
				print "<br />";
				print PrintReady(substr($directory,0,-1));

				print "<br /><br />";
				print "<form name=\"blah2\" action=\"media.php\" method=\"post\">";
				print "<input type=\"hidden\" name=\"directory\" value=\"".$directory."\" />";
				print "<input type=\"hidden\" name=\"level\" value=\"".($level)."\" />";
				print "<input type=\"hidden\" name=\"dir\" value=\"".$directory."\" />";
				print "<input type=\"hidden\" name=\"action\" value=\"\" />";
				print "<input type=\"hidden\" name=\"showthumb\" value=\"{$showthumb}\" />";
			if ($USE_MEDIA_FIREWALL) {
				print "<input type=\"submit\" value=\"".$pgv_lang["move_standard"]."\" onclick=\"this.form.action.value='movedirstandard';\" />";
				print_help_link("move_mediadirs_help","qm","move_mediadirs");
				print "<input type=\"submit\" value=\"".$pgv_lang["move_protected"]."\" onclick=\"this.form.action.value='movedirprotected';\" />";
				print "<br />";
			}
				print_help_link("setperms_help","qm","setperms");
				print "<input type=\"submit\" value=\"".$pgv_lang["setperms_fix"]."\" onclick=\"this.form.action.value='setpermsfix';\" />";

				print "</form>";

			print "</td>";
		print "</tr>";

		// display the directory list
		if (count($dirs) || $level) {
			sort($dirs);
			if ($level){
				print "<tr>";
					print "<td class=\"optionbox $TEXT_DIRECTION width10\">";
						print $uplink2;
					print "</td>";
					print "<td class=\"descriptionbox $TEXT_DIRECTION\">";
						print $uplink;
					print "</td>";
				print "</tr>";
			}

			foreach ($dirs as $indexval => $dir) {
				if ($dir{0}!=".") {
				print "<tr>";
					print "<td class=\"optionbox $TEXT_DIRECTION width10\">";
						// directory options
						print "<form name=\"blah\" action=\"media.php\" method=\"post\">";
						print "<input type=\"hidden\" name=\"directory\" value=\"".$directory.$dir."/\" />";
						print "<input type=\"hidden\" name=\"parentdir\" value=\"".$directory."\" />";
						print "<input type=\"hidden\" name=\"level\" value=\"".($level)."\" />";
						print "<input type=\"hidden\" name=\"dir\" value=\"".$dir."\" />";
						print "<input type=\"hidden\" name=\"action\" value=\"\" />";
						print "<input type=\"hidden\" name=\"showthumb\" value=\"{$showthumb}\" />";
						print "<input type=\"image\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"]."\" alt=\"".$pgv_lang['delete']."\" onclick=\"this.form.action.value='deletedir';return confirm('".$pgv_lang["confirm_folder_delete"]."');\" />";
						if ($USE_MEDIA_FIREWALL) {
							print "<input type=\"submit\" value=\"".$pgv_lang["move_standard"]."\" onclick=\"this.form.level.value=(this.form.level.value*1)+1;this.form.action.value='movedirstandard';\" />";
							print "<input type=\"submit\" value=\"".$pgv_lang["move_protected"]."\" onclick=\"this.form.level.value=(this.form.level.value*1)+1;this.form.action.value='movedirprotected';\" />";
						}

						print "</form>";
					print "</td>";
					print "<td class=\"descriptionbox $TEXT_DIRECTION\">";
						print "<a href=\"".encode_url("media.php?directory={$directory}{$dir}/&level=".($level+1).$thumbget)."\">";
						if ($TEXT_DIRECTION=="rtl") print getRLM();
						print $dir;
						if ($TEXT_DIRECTION=="rtl") print getRLM();
						print "</a>";
					print "</td>";
				print "</tr>";
				}
			}
		}
		// Form for ceating a new directory
		// Checks admin user can write and is not trying to create deeper level dir
		// than the configured number of levels
		if (PGV_USER_IS_ADMIN && $fileaccess && ($level < $MEDIA_DIRECTORY_LEVELS)) {
			print "<tr>";
				print "<td class=\"list_value $TEXT_DIRECTION\" colspan=\"2\">";
					print "<form action=\"media.php\" method=\"get\">";
					print "<input type=\"hidden\" name=\"directory\" value=\"".$directory."\" />";
					print "<input type=\"hidden\" name=\"level\" value=\"".$level."\" />";
					print "<input type=\"hidden\" name=\"action\" value=\"newdir\" />";
					print "<input type=\"submit\" value=\"".$pgv_lang["add"]."\" />";
					print "<input type=\"text\" name=\"newdir\" size=\"100%\" /></form>";
				print "</td>";
			print "</tr>";
		}
		print "</table>";
		print "<br />";

		// display the images
		if (count($medialist) && ($subclick=='search' || $subclick=='all')) {
			print "\n\t<table class=\"list_table width100\">";
			if ($directory==$MEDIA_DIRECTORY) {
				$httpFilter = "http";
				$passStart = 1;
			} else {
				$httpFilter = "";
				$passStart = 2;
			}
			for ($passCount=$passStart; $passCount<3; $passCount++) {
				$printDone = false;
				foreach ($medialist as $indexval => $media) {
					while (true) {
						if (!filterMedia($media, $filter, $httpFilter)) break;
						$isExternal = isFileExternal($media["FILE"]);
						if ($passCount==1 && !$isExternal) break;
						if ($passCount==2 && $isExternal) break;
						$imgsize = findImageSize($media["FILE"]);
						$imgwidth = $imgsize[0]+40;
						$imgheight = $imgsize[1]+150;

						$changeClass = "";
						if ($media["CHANGE"]=="delete") $changeClass = "change_old";
						if ($media["CHANGE"]=="replace") $changeClass = "change_new";
						if ($media["CHANGE"]=="append") $changeClass = "change_new";

						// Show column with file operations options
						$printDone = true;
						print "<tr><td class=\"optionbox $changeClass $TEXT_DIRECTION width20\">";

						if ($media["CHANGE"]!="delete") {
							// Edit File
							$tempURL = "addmedia.php?action=";
							if ($media["XREF"] != "") {
								$tempURL .= "editmedia&pid={$media['XREF']}&linktoid=";
								if (!$media["LINKED"]) {
									$tempURL .= "new";
								} else {
									foreach ($media["LINKS"] as $linkToID => $temp) break;
									$tempURL .= $linkToID;
								}
							} else {
								$tempURL .= "showmediaform&filename={$media['FILE']}&linktoid=new";
							}
							echo "<a href=\"javascript:".$pgv_lang["edit"]."\" onclick=\"window.open('", encode_url($tempURL, false), "', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1'); return false;\">", $pgv_lang["edit"], "</a><br />";

							// Edit Raw
							if ($media["XREF"] != "") {
								print "<a href=\"javascript:".$pgv_lang["edit_raw"]."\" onclick=\"return edit_raw('".$media['XREF']."');\">".$pgv_lang['edit_raw']."</a><br />\n";
							}

							// Delete File
							// 		don't delete external files
							//		don't delete files linked to more than 1 object
							$objectCount = 0;
							if (!$isExternal) {
								foreach ($medialist as $tempMedia) {
									if ($media["EXISTS"] && $media["FILE"]==$tempMedia["FILE"]) $objectCount++;
								}
								unset($tempMedia);
							}
							if (!$isExternal && $objectCount<2) {
								$tempURL = "media.php?";
								if (!empty($filter)) $tempURL.= "filter={$filter}&";
								$tempURL .= "action=deletefile&showthumb={$showthumb}&filter={$filter}&subclick={$subclick}&filename={$media['FILE']}&directory={$directory}&level={$level}&xref={$media['XREF']}&gedfile={$media['GEDFILE']}";
								print "<a href=\"".encode_url($tempURL)."\" onclick=\"return confirm('".$pgv_lang["confirm_delete_file"]."');\">".$pgv_lang["delete_file"]."</a><br />";
							}

							// Remove Object
							if (!empty($media["XREF"])) {
								$tempURL = "media.php?";
								if (!empty($filter)) $tempURL .= "filter={$filter}&";
								$tempURL .= "action=removeobject&showthumb={$showthumb}&filter={$filter}&subclick={$subclick}&filename={$media['FILE']}&directory={$directory}&level={$level}&xref={$media['XREF']}&gedfile={$media['GEDFILE']}";
								print "<a href=\"".encode_url($tempURL)."\" onclick=\"return confirm('".$pgv_lang["confirm_remove_object"]."');\">".$pgv_lang["remove_object"]."</a><br />";
							}

							// Remove links
							if ($media["LINKED"]) {
								$tempURL = "media.php?";
								if (!empty($filter)) $tempURL .= "filter={$filter}&";
								$tempURL .= "action=removelinks&showthumb={$showthumb}&filter={$filter}&subclick={$subclick}&filename={$media['FILE']}&directory={$directory}&level={$level}&xref={$media['XREF']}&gedfile={$media['GEDFILE']}";
								print "<a href=\"".encode_url($tempURL)."\" onclick=\"return confirm('".$pgv_lang["confirm_remove_links"]."');\">".$pgv_lang["remove_links"]."</a><br />";
							}

							// Set Link
							// Only set link on media that is in the DB
							if ($media["XREF"] != "") {
								print_link_menu($media["XREF"]);
							}


							// Move image between standard and protected directories
							if ($USE_MEDIA_FIREWALL && ($media["EXISTS"] > 1)) {
								$tempURL = "media.php?";
								if ($media["EXISTS"] == 2) $tempURL .= "action=moveprotected";
								if ($media["EXISTS"] == 3) $tempURL .= "action=movestandard";
								$tempURL .= "&showthumb={$showthumb}&filename={$media['FILE']}&directory={$directory}&level={$level}&xref={$media['XREF']}&gedfile=".$media["GEDFILE"];
								print "<a href=\"".encode_url($tempURL)."\">".$pgv_lang["moveto_".$media["EXISTS"]]."</a><br />";
							}

							// Generate thumbnail
							if (!$isExternal && (empty($media["THUMB"]) || !$media["THUMBEXISTS"])) {
								$ct = preg_match("/\.([^\.]+)$/", $media["FILE"], $match);
								if ($ct>0) $ext = strtolower(trim($match[1]));
								if ($ext=="jpg" || $ext=="jpeg" || $ext=="gif" || $ext=="png") {
									$tempURL = "media.php?";
									if (!empty($filter)) $tempURL .= "filter={$filter}&";
									$tempURL .= "action=thumbnail&all=no&level={$level}&directory={$directory}&filename=".$media["FILE"].$thumbget;
									print "<a href=\"".encode_url($tempURL)."\">".$pgv_lang["gen_thumb"]."</a>";
								}
							}

						}
						// NOTE: Close column for file operations
						print "</td>";

						//-- thumbnail field
						if ($showthumb) {
							$mediaTitle = "";
							if (isset($media["TITL"])) $mediaTitle = PrintReady($media["TITL"]);
							else $mediaTitle = PrintReady(basename($media["FILE"]));
							print "\n\t\t\t<td class=\"optionbox $changeClass $TEXT_DIRECTION width10\">";
							if (!$isExternal) {
								print "<a href=\"#\" onclick=\"return openImage('".encode_url($media["FILE"])."',$imgwidth, $imgheight);\">";
								print "<img src=\"{$media['THUMB']}\" class=\"thumbnail\" border=\"0\" alt=\"{$mediaTitle}\" title=\"{$mediaTitle}\" /></a>\n";
							} else {
								print "<a href=\"#\" onclick=\"return openImage('".encode_url($media["FILE"])."',$imgwidth, $imgheight);\">";
								print "<img src=\"{$media['FILE']}\" class=\"thumbnail\" width=\"{$THUMBNAIL_WIDTH}\" border=\"0\" alt=\"{$mediaTitle}\" title=\"{$mediaTitle}\" /></a>\n";
							}
							print "</td>";
						}

						//-- name and size field
						print "\n\t\t\t<td class=\"optionbox $changeClass $TEXT_DIRECTION wrap\">";
						if ($media["TITL"]!="" && begRTLText($media["TITL"]) && $TEXT_DIRECTION=="ltr") {
							if (!empty($media["XREF"])) {
								print "(".$media["XREF"].")";
								print "&nbsp;&nbsp;&nbsp;";
							}
							if ($media["TITL"]!="") print "<b>".PrintReady($media["TITL"])."</b><br />";
						} else {
							if ($media["TITL"]!="") print "<b>".PrintReady($media["TITL"])."</b>&nbsp;&nbsp;&nbsp;";
							if (!empty($media["XREF"])) {
								if ($TEXT_DIRECTION=="rtl") print getRLM();
								print "(".$media["XREF"].")";
								if ($TEXT_DIRECTION=="rtl") print getRLM();
								print "<br />";
							}
						}
						if (!$isExternal && !$media["EXISTS"]) print "<span dir=\"ltr\">".PrintReady($media["FILE"])."</span><br /><span class=\"error\">".$pgv_lang["file_not_exists"]."</span><br />";
						else if ($isExternal) {
							print "<a href=\"#\" onclick=\"return openImage('".encode_url($media["FILE"])."',$imgwidth, $imgheight);\"><b>URL</b></a><br />";
						} else {
							print "<a href=\"#\" onclick=\"return openImage('".encode_url($media["FILE"])."',$imgwidth, $imgheight);\"><span dir=\"ltr\">".PrintReady($media["FILE"])."</span></a><br />";
							if (!empty($imgsize[0])) {
								print "<sub>&nbsp;&nbsp;".$pgv_lang["image_size"]." -- ".$imgsize[0]."x".$imgsize[1]."</sub><br />";
							}
						}
						print_fact_notes($media["GEDCOM"], 1);
						print_fact_sources($media["GEDCOM"], 1);
						if ($media["LINKED"]) {
							//print "<br />".$pgv_lang["media_linked"];
							PrintMediaLinks($media["LINKS"], "normal");
						} else {
							print "<br />".$pgv_lang["media_not_linked"];
						}


						if ($USE_MEDIA_FIREWALL) {
							print "<br /><br />";
							if ($media["EXISTS"])      { print $pgv_lang["media_dir_".$media["EXISTS"]]."<br />"; }
							if ($media["THUMBEXISTS"]) { print $pgv_lang["thumb_dir_".$media["THUMBEXISTS"]]."<br />"; }
						}

						print "\n\t\t\t</td></tr>";
						break;
					}
				}
				if ($passCount==1 && $printDone) print "<tr><td class=\"optionbox\" colspan=\"3\">&nbsp;</td></tr>";
			}
			print "\n\t</table><br />";
		}
	}

	?>
<?php
}
else print $pgv_lang["media_folder_corrupt"];
print_footer();
?>
