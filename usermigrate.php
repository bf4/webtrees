<?php
/**
 * Exports users and their data to either SQL queries (Index mode) or 
 * authenticate.php and xxxxxx.dat files (MySQL mode).
 * 
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
require_once("config.php");
require($confighelpfile["english"]);
if (file_exists($confighelpfile[$LANGUAGE])) require($confighelpfile[$LANGUAGE]);

require_once("includes/functions_export.php");

//-- make sure that they have admin status before they can use this page
//-- otherwise have them login again
if (!userIsAdmin(getUserName())) {
	header("Location: login.php?url=usermigrate.php");
	exit;
}

if (!isset($proceed)) $proceed = "backup";

if ($proceed == "backup") print_header($pgv_lang["um_backup"]);
else print print_header($pgv_lang["um_header"]);

// Backup part of usermigrate
if ($proceed == "backup") {

	// If first time, let the user choose the options
	if ((!isset($_POST["um_config"])) && (!isset($_POST["um_gedcoms"])) && (!isset($_POST["um_gedsets"])) &&(!isset($_POST["um_logs"])) &&(!isset($_POST["um_usinfo"]))) {
		print "<div class=\"center\"><h2>".$pgv_lang["um_backup"]."</h2></div>";
		print "<table align=\"center\" ><tr class=\"label\"><tr><td style=\"text-align:";
		if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";
		print ";\" >".$pgv_lang["um_bu_explain"]."<br /></td></tr>";
		?></table><br /><br />
		<form action="<?php print $SCRIPT_NAME; ?>" method="post">
		<table align="center">
			<tr class="label"><td style="padding: 5px" colspan="2" class="facts_label03"><?php print $pgv_lang["options"]; ?></td></tr><br />
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";?>; "><?php print $pgv_lang["um_bu_config"]; ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_config" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";?>; "><?php print $pgv_lang["um_bu_gedcoms"]; ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_gedcoms" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";?>; "><?php print $pgv_lang["um_bu_gedsets"]; ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_gedsets" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";?>; "><?php print $pgv_lang["um_bu_logs"]; ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_logs" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";?>; "><?php print $pgv_lang["um_bu_usinfo"]; ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_usinfo" value="yes" checked="checked" /></td></tr>
			<tr><td style="padding: 5px" colspan="2" class="facts_label03"><button type="submit" name="submit"><?php print $pgv_lang["um_mk_bu"]; ?></button>
			<input type="button" value="<?php print $pgv_lang["lang_back_admin"];?>"  onclick="window.location='admin.php';"/></td></tr>
		</table></form><br /><br />
		<?php
		print_footer();
		exit;
	}

	$flist = array();

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
		um_export($proceed);
		
		// Make filelist for files to ZIP
		if (file_exists($INDEX_DIRECTORY."authenticate.php")) $flist[] = $INDEX_DIRECTORY."authenticate.php";
		if (file_exists($INDEX_DIRECTORY."news.dat")) $flist[] = $INDEX_DIRECTORY."news.dat";
		if (file_exists($INDEX_DIRECTORY."messages.dat")) $flist[] = $INDEX_DIRECTORY."messages.dat";
		if (file_exists($INDEX_DIRECTORY."blocks.dat")) $flist[] = $INDEX_DIRECTORY."blocks.dat";
		if (file_exists($INDEX_DIRECTORY."favorites.dat")) $flist[] = $INDEX_DIRECTORY."favorites.dat";
	}

	// Backup config.php
	if (isset($_POST["um_config"])) {
		$flist[] = "config.php";
	}

	// Backup gedcoms
	if (isset($_POST["um_gedcoms"])) {
		foreach($GEDCOMS as $key=> $gedcom) {
			if ($SYNC_GEDCOM_FILE && file_exists($gedcom["path"])) $flist[] = $gedcom["path"];
			else {
				$oldged = $GEDCOM;
				$GEDCOM = $gedcom;
				$gedname = $INDEX_DIRECTORY.$gedcom.".bak";
				if (($proceed == "export") || ($proceed == "exportovr")) print $pgv_lang["um_creating"]." \"$gedname\"<br /><br />";
				$gedout = fopen(filename_decode($gedname), "wb");
				print_gedcom('', '', '', '', 'yes', $gedout);
				fclose($gedout);
				$GEDCOM = $oldged;
				$flist[] = $gedname;
			}
		}
		$flist[] = $INDEX_DIRECTORY."pgv_changes.php";
	}

	// Backup gedcom settings
	if (isset($_POST["um_gedsets"])) {

		// Gedcoms file
		if (file_exists($INDEX_DIRECTORY."gedcoms.php")) $flist[] = $INDEX_DIRECTORY."gedcoms.php";

		foreach($GEDCOMS as $key => $gedcom) {

			// Config files
			if (file_exists($INDEX_DIRECTORY.$gedcom["gedcom"]."_conf.php")) $flist[] = $INDEX_DIRECTORY.$gedcom["gedcom"]."_conf.php";
			
			// Privacy files
			if (file_exists($INDEX_DIRECTORY.$gedcom["gedcom"]."_priv.php")) $flist[] = $INDEX_DIRECTORY.$gedcom["gedcom"]."_priv.php";
		}
	}

	// Backup logfiles and counters
	if (isset($_POST["um_logs"])) {
		foreach($GEDCOMS as $key => $gedcom) {

			// Gedcom counters
			if (file_exists($INDEX_DIRECTORY.$gedcom["gedcom"]."pgv_counters.php")) $flist[] = $INDEX_DIRECTORY.$gedcom["gedcom"]."pgv_counters.php";

			// Gedcom searchlogs and changelogs
			$dir_var = opendir ($INDEX_DIRECTORY);
			while ($file = readdir ($dir_var)) {
				if ((strpos($file, ".log") > 0) && ((strstr($file, "srch-".$gedcom["gedcom"]) !== false ) || (strstr($file, "ged-".$gedcom["gedcom"]) !== false ))) $flist[] = $INDEX_DIRECTORY.$file;
			}
			closedir($dir_var);
		}
		
		// PhpGedView logfiles
		$dir_var = opendir ($INDEX_DIRECTORY);
		while ($file = readdir ($dir_var)) {
			if ((strpos($file, ".log") > 0) && (strstr($file, "pgv-") !== false )) $flist[] = $INDEX_DIRECTORY.$file;
		}
		closedir($dir_var);
	}

	// Make the zip
		print "<div class=\"center\"><h2>".$pgv_lang["um_backup"]."</h2></div>";
		print "<br /><br /><table align=\"center\">";
		print "<tr class=\"label\"><td style=\"padding: 5px\" class=\"facts_label03\">".$pgv_lang["um_results"]."</td></tr>";

	if (count($flist) > 0) {
		require "includes/pclzip.lib.php";
		require "includes/adodb-time.inc.php";
		$buname = adodb_date("YmdHis").".zip";
		$fname = $INDEX_DIRECTORY.$buname;
		$comment = "Created by PhpGedView ".$VERSION." ".$VERSION_RELEASE." on ".adodb_date("r").".";
		$archive = new PclZip($fname);
		$v_list = $archive->create($flist, PCLZIP_OPT_COMMENT, $comment);
		print "<tr><td class=\"list_label\" style=\"padding: 5px;\" >";
		if ($v_list == 0) print "Error : ".$archive->errorInfo(true)."</td></tr>";
		else {
			print $pgv_lang["um_zip_succ"]."</td></tr>";
			print "<tr><td class=\"list_value\" style=\"padding: 5px;\" ><a href=\"downloadbackup.php?fname=".$buname."\" target=\"_blank\">".$pgv_lang["um_zip_dl"]." ".$fname."</a>  (";
			printf("%.0f Kb", (filesize($fname)/1024));
			print")</td></tr>";
		}
		if (isset($_POST["um_usinfo"])) {

			// Remove temporary files again
			if (file_exists($INDEX_DIRECTORY."authenticate.php")) unlink($INDEX_DIRECTORY."authenticate.php");
			if (file_exists($INDEX_DIRECTORY."news.dat")) unlink($INDEX_DIRECTORY."news.dat");
			if (file_exists($INDEX_DIRECTORY."messages.dat")) unlink($INDEX_DIRECTORY."messages.dat");
			if (file_exists($INDEX_DIRECTORY."blocks.dat")) unlink($INDEX_DIRECTORY."blocks.dat");
			if (file_exists($INDEX_DIRECTORY."favorites.dat")) unlink($INDEX_DIRECTORY."favorites.dat");
		}
	}
	else print "<td style=\"padding: 5px\" class=\"list_label\">".$pgv_lang["um_nofiles"]."</td></tr>";
	print "<tr class=\"label\"><td style=\"padding: 5px\" class=\"facts_label03\"><input type=\"button\" value=\"".$pgv_lang["lang_back_admin"]."\" onclick=\"window.location='admin.php';\" /></td></tr></table><br /><br />";

	print_footer();
	exit;
}

// User Migration part of usermigrate. The function um_export is used by backup and migrate part.

if (($proceed == "export") || ($proceed == "exportovr")) {
	print "\n\t<h2>".$pgv_lang["um_header"]."</h2>";
	print "<br /><br />";
	print $pgv_lang["um_sql_index"];
	}
if ($proceed == "import") {
	print "\n\t<h2>".$pgv_lang["um_header"]."</h2>";
	print "<br /><br />";
	print $pgv_lang["um_index_sql"];
	print "<br /><br />";
}

if (($proceed != "import") && ($proceed != "export") && ($proceed != "exportovr")) {
	print "<div class=\"center\">\n\t<h2>".$pgv_lang["um_header"]."</h2>";
	?>
	<br /><br />
	<form action="usermigrate.php" method="post"><input type="hidden" name="proceed" value="import" />
		<table align="center" width="75%" ><tr class="label">
			<tr><td style="text-align:<?php if ($TEXT_DIRECTION == "ltr") print "left"; else print "right";?>; "><?php print $pgv_lang["um_explain"].$pgv_lang["um_proceed"]?><br />
			</td></tr>
			<tr><td><input type="submit" class="button" value="<?php print $pgv_lang["um_import"];?>" />
			<input type="button" class="button" value="<?php print $pgv_lang["um_export"];?>" onclick="window.location='usermigrate.php?proceed=export';"/>
			<input type="button" value="<?php print $pgv_lang["lang_back_admin"];?>"  onclick="window.location='admin.php';"/><br /><br />
			</td></tr>
		</table>
		</form></div>
	<?php
	print_footer();
	exit;
}

if (($proceed == "export") || ($proceed == "exportovr")) {
	
	// Check if one of the files already exists
	if ($proceed == "export") {
		$i = 0;
		if (file_exists($INDEX_DIRECTORY."authenticate.php")) $i = $i + 1;
		if (file_exists($INDEX_DIRECTORY."news.dat")) $i = $i + 1;
		if (file_exists($INDEX_DIRECTORY."messages.dat")) $i = $i + 1;
		if (file_exists($INDEX_DIRECTORY."blocks.dat")) $i = $i + 1;
		if (file_exists($INDEX_DIRECTORY."favorites.dat")) $i = $i + 1;
		if ($i > 0) {
			print "<br />".$pgv_lang["um_files_exist"]."<br /><br />";
			?>
			<form "<?php print $SCRIPT_NAME; ?>" method="post">
				<input type="hidden" class="button" value="<?php print $pgv_lang["yes"];?>" />
				<input type="button" class="button" value="<?php print $pgv_lang["yes"];?>" onclick="window.location='usermigrate.php?proceed=exportovr';"/>
				<input type="button" class="button" value="<?php print $pgv_lang["no"];?>" onclick="window.location='admin.php';"/>
			</form>
			<?php
			print_footer();
			exit;
		}
		else $proceed = "exportovr";
	}

	if ($proceed = "exportovr") {
		if (file_exists($INDEX_DIRECTORY."authenticate.php")) unlink($INDEX_DIRECTORY."authenticate.php");
		if (file_exists($INDEX_DIRECTORY."news.dat")) unlink($INDEX_DIRECTORY."news.dat");
		if (file_exists($INDEX_DIRECTORY."messages.dat")) unlink($INDEX_DIRECTORY."messages.dat");
		if (file_exists($INDEX_DIRECTORY."blocks.dat")) unlink($INDEX_DIRECTORY."blocks.dat");
		if (file_exists($INDEX_DIRECTORY."favorites.dat")) unlink($INDEX_DIRECTORY."favorites.dat");
	}
	um_export($proceed);
}

if ($proceed == "import") {
	// Get users and import them
	print "<br /><br />".$pgv_lang["um_imp_users"]."<br />";
	if ((file_exists($INDEX_DIRECTORY."authenticate.php")) == false) {
		print $pgv_lang["um_nousers"]."<br /><br />";
	print  "<a href=\"admin.php\">" . $pgv_lang["lang_back_admin"] . "</a><br /><br />";
	exit;
	}
	require $INDEX_DIRECTORY."authenticate.php";
	$countold = count($users);
	$sql = "DELETE FROM ".$TBLPREFIX."users";
	$tempsql = dbquery($sql);
$res =& $tempsql;
	if (!$res) {
		print "<span class=\"error\">Unable to update <i>Users</i> table.</span><br />\n";
		exit;
	}
	foreach($users as $username=>$user) {
		if ($user["visibleonline"] == "1") $user["visibleonline"] = false;
		else $user["visibleonline"] = true;
		if ($user["editaccount"] == "1") $user["editaccount"] = false;
		else $user["editaccount"] = true;
		addUser($user, "imported");
	}
	$countnew = count(getUsers());
	if ($countold == $countnew) {
		print $pgv_lang["um_imp_succ"]."<br /><br />";
	}
	else {
		print $pgv_lang["um_imp_fail"];
		exit;
	}

	// Get messages and import them
	print $pgv_lang["um_imp_messages"]."<br />";
	$sql = "DELETE FROM ".$TBLPREFIX."messages";
	$tempsql = dbquery($sql);
$res =& $tempsql;
	if (!$res) {
		print "<span class=\"error\">Unable to update <i>Messages</i> table.</span><br />\n";
		exit;
	}
	if ((file_exists($INDEX_DIRECTORY."messages.dat")) == false) {
		print $pgv_lang["um_nomsg"]."<br /><br />";
	}
	else {
		$messages = array();
		$fp = fopen($INDEX_DIRECTORY."messages.dat", "rb");
		$mstring = fread($fp, filesize($INDEX_DIRECTORY."messages.dat"));
		fclose($fp);
		$messages = unserialize($mstring);
		foreach($messages as $newid => $message) {
			$sql = "INSERT INTO ".$TBLPREFIX."messages VALUES ($newid, '".$DBCONN->escapeSimple($message["from"])."','".$DBCONN->escapeSimple($message["to"])."','".$DBCONN->escapeSimple($message["subject"])."','".$DBCONN->escapeSimple($message["body"])."','".$DBCONN->escapeSimple($message["created"])."')";
			$tempsql = dbquery($sql);
$res =& $tempsql;
			if (!$res) {
				print "<span class=\"error\">Unable to update <i>Messages</i> table.</span><br />\n";
				exit;
			}
		}
		print $pgv_lang["um_imp_succ"]."<br /><br />";
	}

	// Get favorites and import them
	print $pgv_lang["um_imp_favorites"]."<br />";
	$sql = "DELETE FROM ".$TBLPREFIX."favorites";
	$tempsql = dbquery($sql);
$res =& $tempsql;
	if (!$res) {
		print "<span class=\"error\">Unable to update <i>Favorites</i> table.</span><br />\n";
		exit;
	}
	if ((file_exists($INDEX_DIRECTORY."favorites.dat")) == false) {
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
			if (!$res) {
				print "<span class=\"error\">Unable to update <i>Favorites</i> table.</span><br />\n";
				exit;
			}
		}
		print $pgv_lang["um_imp_succ"]."<br /><br />";
	}

	// Get news and import it
	print $pgv_lang["um_imp_news"]."<br />";
	$sql = "DELETE FROM ".$TBLPREFIX."news";
	$tempsql = dbquery($sql);
$res =& $tempsql;
	if (!$res) {
		print "<span class=\"error\">Unable to update <i>News</i> table.</span><br />\n";
		exit;
	}
	if ((file_exists($INDEX_DIRECTORY."news.dat")) == false) {
		print $pgv_lang["um_nonews"]."<br /><br />";
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
				print "<span class=\"error\">Unable to update <i>News</i> table.</span><br />\n";
				exit;
			}
		}
		print $pgv_lang["um_imp_succ"]."<br /><br />";
	}

	// Get blocks and import them
	print $pgv_lang["um_imp_blocks"]."<br />";
	$sql = "DELETE FROM ".$TBLPREFIX."blocks";
	$tempsql = dbquery($sql);
$res =& $tempsql;
	if (!$res) {
		print "<span class=\"error\">Unable to update <i>Blocks</i> table.</span><br />\n";
		exit;
	}
	if ((file_exists($INDEX_DIRECTORY."blocks.dat")) == false) {
		print $pgv_lang["um_noblocks"]."<br /><br />";
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
			$tempsql = dbquery($sql);
$res =& $tempsql;
			if (!$res) {
				print "<span class=\"error\">Unable to update <i>Blocks</i> table.</span><br />\n";
				exit;
			}
		}
		print $pgv_lang["um_imp_succ"]."<br /><br />";
	}
}

print  "<a href=\"admin.php\">" . $pgv_lang["lang_back_admin"] . "</a><br /><br />";

print_footer();
?>
