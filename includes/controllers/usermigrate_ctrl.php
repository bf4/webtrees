<?php
/**
 * Controller for backup and export
 * Exports users and their data to either SQL queries (Index mode) or 
 * authenticate.php and xxxxxx.dat files (MySQL mode).
 * 
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @author Boudewijn Sjouke	sjouke@users.sourceforge.net
 * @package PhpGedView
 * @subpackage Admin
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once("config.php");
require_once 'includes/controllers/basecontrol.php';

loadLangFile("pgv_confighelp");

//-- make sure that they have admin status before they can use this page
//-- otherwise have them login again
if (strstr($_SERVER['SCRIPT_NAME'], "usermigrate_cli.php")) {
	if (userIsAdmin(getUserName()) || !isset($argc)) {
		header("Location: usermigrate.php");
		exit;
	}
}
else if (!userIsAdmin(getUserName())) {
	header("Location: login.php?url=usermigrate.php");
	exit;
}

require_once("includes/functions_export.php");

class UserMigrateControllerRoot extends BaseController {
	var $proceed;
	var $flist;
	var $v_list;
	var $fname;
	var $buname;
	var $errorMsg;
	var $fileExists = false;
	var $impSuccess = false;
	var $msgSuccess = false;
	var $favSuccess = false;
	var $newsSuccess = false;
	var $blockSuccess = false;
	
	/**
	 * constructor
	 */
	function UserMigrateControllerRoot() {
		parent::BaseController();
	}
	
	/**
	 * Initialize the controller and start the logic
	 *
	 */
	function init() {
		global $INDEX_DIRECTORY;
		if (!isset($_REQUEST['proceed'])) $this->proceed = "backup";
		else $this->proceed = $_REQUEST['proceed'];
		
		if ($this->proceed == "backup") $this->backup();
		else if ($this->proceed == "export") {
			$i = 0;
			if (file_exists($INDEX_DIRECTORY."authenticate.php")) $i = $i + 1;
			if (file_exists($INDEX_DIRECTORY."news.dat")) $i = $i + 1;
			if (file_exists($INDEX_DIRECTORY."messages.dat")) $i = $i + 1;
			if (file_exists($INDEX_DIRECTORY."blocks.dat")) $i = $i + 1;
			if (file_exists($INDEX_DIRECTORY."favorites.dat")) $i = $i + 1;
			if ($i > 0) {
				$this->fileExists = true;
			}
			else $this->proceed = "exportovr";
		}
		if ($this->proceed == "exportovr") {
			if (file_exists($INDEX_DIRECTORY."authenticate.php")) unlink($INDEX_DIRECTORY."authenticate.php");
			if (file_exists($INDEX_DIRECTORY."news.dat")) unlink($INDEX_DIRECTORY."news.dat");
			if (file_exists($INDEX_DIRECTORY."messages.dat")) unlink($INDEX_DIRECTORY."messages.dat");
			if (file_exists($INDEX_DIRECTORY."blocks.dat")) unlink($INDEX_DIRECTORY."blocks.dat");
			if (file_exists($INDEX_DIRECTORY."favorites.dat")) unlink($INDEX_DIRECTORY."favorites.dat");
			um_export($proceed);
		}
		
		if ($this->proceed == "import") {
			$this->import();
		}
	}
	
	/**
	 * Return the page title
	 *
	 * @return string
	 */
	function getPageTitle() {
		global $pgv_lang;
		
		if ($this->proceed == "backup") return $pgv_lang["um_backup"];
		else return $pgv_lang["um_header"];
	}
	
	/**
	 * generate the backup zip file
	 *
	 */
	function backup() {
		global $INDEX_DIRECTORY, $GEDCOMS, $GEDCOM, $MEDIA_DIRECTORY, $SYNC_GEDCOM_FILE;
		global $VERSION, $VERSION_RELEASE;
		$this->flist = array();
	
		// Backup user information
		if (isset($_POST["um_usinfo"])) {
			// If in pure DB mode, we must first create new .dat files and authenticate.php
			// First delete the old files
			if (file_exists($INDEX_DIRECTORY."authenticate.php")) unlink($INDEX_DIRECTORY."authenticate.php");
			if (file_exists($INDEX_DIRECTORY."news.dat")) unlink($INDEX_DIRECTORY."news.dat");
			if (file_exists($INDEX_DIRECTORY."messages.dat")) unlink($INDEX_DIRECTORY."messages.dat");
			if (file_exists($INDEX_DIRECTORY."blocks.dat")) unlink($INDEX_DIRECTORY."blocks.dat");
			if (file_exists($INDEX_DIRECTORY."favorites.dat")) unlink($INDEX_DIRECTORY."favorites.dat");
	
			// Then make the new ones
			um_export($this->proceed);
			
			// Make filelist for files to ZIP
			if (file_exists($INDEX_DIRECTORY."authenticate.php")) $this->flist[] = $INDEX_DIRECTORY."authenticate.php";
			if (file_exists($INDEX_DIRECTORY."news.dat")) $this->flist[] = $INDEX_DIRECTORY."news.dat";
			if (file_exists($INDEX_DIRECTORY."messages.dat")) $this->flist[] = $INDEX_DIRECTORY."messages.dat";
			if (file_exists($INDEX_DIRECTORY."blocks.dat")) $this->flist[] = $INDEX_DIRECTORY."blocks.dat";
			if (file_exists($INDEX_DIRECTORY."favorites.dat")) $this->flist[] = $INDEX_DIRECTORY."favorites.dat";
		}
	
		// Backup config.php
		if (isset($_POST["um_config"])) {
			$this->flist[] = "config.php";
		}
	
		// Backup gedcoms
		if (isset($_POST["um_gedcoms"])) {
			foreach($GEDCOMS as $key=> $gedcom) {
				//-- load the gedcom configuration settings
				require(get_config_file($gedcom));
				//-- check if the gedcom file is synchronized with the DB
				if ($SYNC_GEDCOM_FILE && file_exists($gedcom["path"])) $this->flist[] = $gedcom["path"];
				else {
					//-- recreate the GEDCOM file if it is not synchronized
					$oldged = $GEDCOM;
					$GEDCOM = $gedcom;
					$gedname = $INDEX_DIRECTORY.$gedcom.".bak";
					$gedout = fopen(filename_decode($gedname), "wb");
					print_gedcom('no', 'no', 'no', 'no', $gedout);
					fclose($gedout);
					$GEDCOM = $oldged;
					$this->flist[] = $gedname;
				}
			}
			//-- load up the old configuration file
			require(get_config_file($GEDCOM));
			$this->flist[] = $INDEX_DIRECTORY."pgv_changes.php";
		}
	
		// Backup gedcom settings
		if (isset($_POST["um_gedsets"])) {
	
			// Gedcoms file
			if (file_exists($INDEX_DIRECTORY."gedcoms.php")) $this->flist[] = $INDEX_DIRECTORY."gedcoms.php";
	
			foreach($GEDCOMS as $key => $gedcom) {
	
				// Config files
				if (file_exists($INDEX_DIRECTORY.$gedcom["gedcom"]."_conf.php")) $this->flist[] = $INDEX_DIRECTORY.$gedcom["gedcom"]."_conf.php";
				
				// Privacy files
				if (file_exists($INDEX_DIRECTORY.$gedcom["gedcom"]."_priv.php")) $this->flist[] = $INDEX_DIRECTORY.$gedcom["gedcom"]."_priv.php";
			}
		}
	
		// Backup logfiles and counters
		if (isset($_POST["um_logs"])) {
			foreach($GEDCOMS as $key => $gedcom) {
	
				// Gedcom counters
				if (file_exists($INDEX_DIRECTORY.$gedcom["gedcom"]."pgv_counters.php")) $this->flist[] = $INDEX_DIRECTORY.$gedcom["gedcom"]."pgv_counters.php";
	
				// Gedcom searchlogs and changelogs
				$dir_var = opendir ($INDEX_DIRECTORY);
				while ($file = readdir ($dir_var)) {
					if ((strpos($file, ".log") > 0) && ((strstr($file, "srch-".$gedcom["gedcom"]) !== false ) || (strstr($file, "ged-".$gedcom["gedcom"]) !== false ))) $this->flist[] = $INDEX_DIRECTORY.$file;
				}
				closedir($dir_var);
			}
			
			// PhpGedView logfiles
			$dir_var = opendir ($INDEX_DIRECTORY);
			while ($file = readdir ($dir_var)) {
				if ((strpos($file, ".log") > 0) && (strstr($file, "pgv-") !== false )) $this->flist[] = $INDEX_DIRECTORY.$file;
			}
			closedir($dir_var);
		}
		
		// backup media files
		if (isset($_POST["um_media"])) {
			$dir = dir($MEDIA_DIRECTORY);
			while(false !== ($entry = $dir->read())) {
				if ($entry{0} != ".") {
					if ($entry != "thumbs") $this->flist[] = $MEDIA_DIRECTORY.$entry;
				}
			}
		}
		
		// Make the zip
		if (count($this->flist) > 0) {
			require_once "includes/pclzip.lib.php";
			require_once "includes/adodb-time.inc.php";
			$this->buname = adodb_date("YmdHis").".zip";
			$this->fname = $INDEX_DIRECTORY.$this->buname;
			$comment = "Created by PhpGedView ".$VERSION." ".$VERSION_RELEASE." on ".adodb_date("r").".";
			$archive = new PclZip($this->fname);
			//-- remove ../ from file paths when creating zip
	        $ct = preg_match("~((\.\./)+)~", $INDEX_DIRECTORY, $match);
	        $rmpath = "";
	        if ($ct>0) $rmpath = $match[1];
	        $this->v_list = $archive->create($this->flist, PCLZIP_OPT_COMMENT, $comment, PCLZIP_OPT_REMOVE_PATH, $rmpath);
	        if ($this->v_list==0) $this->errorMsg = "Error : ".$archive->errorInfo(true);
			if (isset($_POST["um_usinfo"])) {
				// Remove temporary files again
				if (file_exists($INDEX_DIRECTORY."authenticate.php")) unlink($INDEX_DIRECTORY."authenticate.php");
				if (file_exists($INDEX_DIRECTORY."news.dat")) unlink($INDEX_DIRECTORY."news.dat");
				if (file_exists($INDEX_DIRECTORY."messages.dat")) unlink($INDEX_DIRECTORY."messages.dat");
				if (file_exists($INDEX_DIRECTORY."blocks.dat")) unlink($INDEX_DIRECTORY."blocks.dat");
				if (file_exists($INDEX_DIRECTORY."favorites.dat")) unlink($INDEX_DIRECTORY."favorites.dat");
			}
		}	
	}
	
	/**
	 * Import users etc. from index files
	 *
	 */
	function import() {
		global $INDEX_DIRECTORY, $TBLPREFIX, $pgv_lang, $DBCONN;
		
		if ((file_exists($INDEX_DIRECTORY."authenticate.php")) == false) {
			$this->impSuccess = false;
			return;
		}
		
		require $INDEX_DIRECTORY."authenticate.php";
		$countold = count($users);
		$sql = "DELETE FROM ".$TBLPREFIX."users";
		$res = dbquery($sql);
		if (!$res || DB::isERROR($res)) {
			$this->errorMsg = "<span class=\"error\">Unable to update <i>Users</i> table.</span><br />\n";
			$this->impSuccess = false;
			return;
		}
		foreach($users as $username=>$user) {
			if ($user["visibleonline"] == "1") $user["visibleonline"] = false;
			else $user["visibleonline"] = true;
			if ($user["editaccount"] == "1") $user["editaccount"] = false;
			else $user["editaccount"] = true;
			//-- make sure fields are set for v4.0 DB
			if (!isset($user["firstname"])) {
				if (isset($user["fullname"])) {
					$parts = preg_split("/ /", trim($user["fullname"]));
					$user["lastname"] = array_pop($parts);
					$user["firstname"] = implode(" ", $parts);
				}
				else {
					$user["firstname"] = '';
					$user["lastname"] = '';
				}
			}
			if (!isset($user["comment"])) $user["comment"] = '';
			if (!isset($user["comment_exp"])) $user["comment_exp"] = '';
			if (!isset($user["sync_gedcom"])) $user["sync_gedcom"] = 'N';
			if (!isset($user["relationship_privacy"])) $user["relationship_privacy"] = 'N';
			if (!isset($user["max_relation_path"])) $user["max_relation_path"] = '2';
			if (!isset($user["auto_accept"])) $user["auto_accept"] = 'N';
			addUser($user, "imported");
		}
		$countnew = count(getUsers());
		if ($countold == $countnew) {
			$this->impSuccess = true;
		}
		else {
			$this->impSuccess = false;
		}
		
		$sql = "DELETE FROM ".$TBLPREFIX."messages";
		$res = dbquery($sql);
		if (!$res || DB::isError($res)) {
			$this->errorMsg = "<span class=\"error\">Unable to update <i>Messages</i> table.</span><br />\n";
			$this->msgSuccess = false;
			return;
		}
		if ((file_exists($INDEX_DIRECTORY."messages.dat")) == false) {
			$this->msgSuccess = false;
		}
		else {
			$messages = array();
			$fp = fopen($INDEX_DIRECTORY."messages.dat", "rb");
			$mstring = fread($fp, filesize($INDEX_DIRECTORY."messages.dat"));
			fclose($fp);
			$messages = unserialize($mstring);
			foreach($messages as $newid => $message) {
				$sql = "INSERT INTO ".$TBLPREFIX."messages VALUES ($newid, '".$DBCONN->escapeSimple($message["from"])."','".$DBCONN->escapeSimple($message["to"])."','".$DBCONN->escapeSimple($message["subject"])."','".$DBCONN->escapeSimple($message["body"])."','".$DBCONN->escapeSimple($message["created"])."')";
				$res = dbquery($sql);
				if (!$res || DB::isError($res)) {
					$this->errorMsg = "<span class=\"error\">Unable to update <i>Messages</i> table.</span><br />\n";
					$this->msgSuccess = false;
					return;
				}
			}
			$this->msgSuccess = true;
		}
		
		$sql = "DELETE FROM ".$TBLPREFIX."favorites";
		$res = dbquery($sql);
		if (!$res || DB::isError($res)) {
			$this->errorMsg = "<span class=\"error\">Unable to update <i>Favorites</i> table.</span><br />\n";
			return;
		}
		if ((file_exists($INDEX_DIRECTORY."favorites.dat")) == false) {
			$this->favSuccess = false;
			print $pgv_lang["um_nofav"]."<br /><br />";
		}
		else {
			$favorites = array();
			$fp = fopen($INDEX_DIRECTORY."favorites.dat", "rb");
			$mstring = fread($fp, filesize($INDEX_DIRECTORY."favorites.dat"));
			fclose($fp);
			$favorites = unserialize($mstring);
			
			foreach($favorites as $newid => $favorite) {
				$res = addFavorite($favorite);
				if (!$res || DB::isError($res)) {
					$this->errorMsg = "<span class=\"error\">Unable to update <i>Favorites</i> table.</span><br />\n";
					return;
				}
			}
			$this->favSuccess = true;
		}
		
		$sql = "DELETE FROM ".$TBLPREFIX."news";
		$res = dbquery($sql);
		if (!$res) {
			$this->errorMsg = "<span class=\"error\">Unable to update <i>News</i> table.</span><br />\n";
			$this->newsSuccess = false;
			return;
		}
		if ((file_exists($INDEX_DIRECTORY."news.dat")) == false) {
			$this->newsSuccess = false;
		}
		else {
			$allnews = array();
			$fp = fopen($INDEX_DIRECTORY."news.dat", "rb");
			$mstring = fread($fp, filesize($INDEX_DIRECTORY."news.dat"));
			fclose($fp);
			$allnews = unserialize($mstring);
			foreach($allnews as $newid => $news) {
				$res = addNews($news);
				if (!$res) {
					$this->errorMsg = "<span class=\"error\">Unable to update <i>News</i> table.</span><br />\n";
					return;
				}
			}
			$this->newsSuccess = true;
		}
		
		$sql = "DELETE FROM ".$TBLPREFIX."blocks";
		$res = dbquery($sql);
		if (!$res) {
			$this->errorMsg = "<span class=\"error\">Unable to update <i>Blocks</i> table.</span><br />\n";
			exit;
		}
		if ((file_exists($INDEX_DIRECTORY."blocks.dat")) == false) {
			$this->blockSuccess = false;
		}
		else {
			$allblocks = array();
			$fp = fopen($INDEX_DIRECTORY."blocks.dat", "rb");
			$mstring = fread($fp, filesize($INDEX_DIRECTORY."blocks.dat"));
			fclose($fp);
			$allblocks = unserialize($mstring);
			foreach($allblocks as $bid => $blocks) {
				$username = $blocks["username"];
				$sql = "INSERT INTO ".$TBLPREFIX."blocks VALUES ($bid, '".$DBCONN->escapeSimple($blocks["username"])."', '".$blocks["location"]."', '".$blocks["order"]."', '".$DBCONN->escapeSimple($blocks["name"])."', '".$DBCONN->escapeSimple(serialize($blocks["config"]))."')";
				$res = dbquery($sql);
				if (!$res || DB::isError($res)) {
					$this->errorMsg = "<span class=\"error\">Unable to update <i>Blocks</i> table.</span><br />\n";
					return;
				}
			}
			$this->blockSuccess = true;
		}
	}
}
// -- end of class
//-- load a user extended class if one exists
if (file_exists('includes/controllers/usermigrate_ctrl_user.php'))
{
	include_once 'includes/controllers/usermigrate_ctrl_user.php';
}
else
{
	class UserMigrateController extends UserMigrateControllerRoot
	{
	}
}
$controller = new UserMigrateController();
$controller->init();
?>
