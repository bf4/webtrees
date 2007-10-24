<?php
/**
 * Online UI for editing config.php site configuration variables
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
 * This Page Is Valid XHTML 1.0 Transitional! > 17 September 2005
 *
 * @package PhpGedView
 * @subpackage GoogleMap
 * @see config.php
 * $Id$
 */

//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"menu.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require( "modules/googlemap/defaultconfig.php" );
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');

loadLangFile("pgv_lang, pgv_confighelp, pgv_help, pgv_facts, gm_lang, gm_help");

if (!isset($action)) $action="";
if (!isset($parent)) $parent=0;

// Create GM tables, if not already present
$tables = $DBCONN->getListOf('tables');
if (!in_array($TBLPREFIX."placelocation", $tables)) {
	$sql = "CREATE TABLE ".$TBLPREFIX."placelocation (pl_id int NOT NULL, pl_parent_id int default NULL, pl_level int default NULL, pl_place varchar(255) default NULL, pl_long varchar(30) default NULL, pl_lati varchar(30) default NULL, pl_zoom int default NULL, pl_icon varchar(255) default NULL, PRIMARY KEY (pl_id));";
	$res = dbquery($sql);
	$sql = "CREATE INDEX pl_level ON ".$TBLPREFIX."placelocation (pl_level)";
	$res = dbquery($sql);
	$sql = "CREATE INDEX pl_long ON ".$TBLPREFIX."placelocation (pl_long)";
	$res = dbquery($sql);
	$sql = "CREATE INDEX pl_lati ON ".$TBLPREFIX."placelocation (pl_lati)";
	$res = dbquery($sql);
	$sql = "CREATE INDEX pl_name ON ".$TBLPREFIX."placelocation (pl_place)";
	$res = dbquery($sql);
	$sql = "CREATE INDEX pl_parent_id ON ".$TBLPREFIX."placelocation (pl_parent_id)";
	$res = dbquery($sql);
	$tables = $DBCONN->getListOf('tables');
	if (!in_array($TBLPREFIX."placelocation", $tables)) {
		print "<table class=\"facts_table\">\n";
		print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_db_error"];
		print "</td></tr></table>\n";
		print "<br/><br/><br/>\n";
		print_footer();
		exit;
	}
	print "<table class=\"facts_table\">\n";
	print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_table_created"];
	print "</td></tr></table>\n";
	print "<br/><br/><br/>\n";
}

// Take a place id and find its place in the hierarchy
// Input: place ID
// Output: ordered array of id=>name values, starting with the Top Level
// e.g. array(0=>"Top Level", 16=>"England", 19=>"London", 217=>"Westminster");
// NB This function exists in both places.php and places_edit.php
function place_id_to_hierarchy($id) {
	global $DBCONN, $TBLPREFIX, $pgv_lang;
	$arr=array();
	while ($id!=0) {
		$sql="SELECT pl_parent_id, pl_place FROM {$TBLPREFIX}placelocation WHERE pl_id=".$DBCONN->escapeSimple($id);
		$res=dbquery($sql);
		$row=&$res->fetchRow();
		$res->free();
		$arr=array($id=>$row[1])+$arr;
		$id=$row[0];
	}
	return $arr;
}

// NB This function exists in both places.php and places_edit.php
function getHighestIndex() {
	global $TBLPREFIX;
	$sql="SELECT MAX(pl_id) FROM {$TBLPREFIX}placelocation WHERE 1";
	$res=dbquery($sql);
	$row=&$res->fetchRow();
	$res->free();
	if (empty($row[0]))
		return 0;
 	else
		return $row[0];
}

/**
 * Find all of the places in the hierarchy
 */
function get_place_list_loc($parent_id) {
	global $display, $TBLPREFIX, $DBCONN;
	if ($display=="inactive")
		$sql="SELECT pl_id,pl_place,pl_lati,pl_long,pl_zoom,pl_icon FROM {$TBLPREFIX}placelocation WHERE pl_parent_id=".$DBCONN->escapeSimple($parent_id)." ORDER BY pl_place";
	else
		// :TODO:
		// This method of filtering fails to distinguish "Newport, Hampshire, England" from "Newport, Gwent, Wales".
		// Fortunately it provides too many results, rather than too few.
		$sql="SELECT DISTINCT pl_id,pl_place,pl_lati,pl_long,pl_zoom,pl_icon FROM {$TBLPREFIX}placelocation INNER JOIN {$TBLPREFIX}places ON {$TBLPREFIX}placelocation.pl_place={$TBLPREFIX}places.p_place AND {$TBLPREFIX}placelocation.pl_level=".$TBLPREFIX."places.p_level WHERE pl_parent_id=".$DBCONN->escapeSimple($parent_id)." ORDER BY pl_place";
	$res=dbquery($sql);

	$placelist=array();
	while ($row=&$res->fetchRow())
		$placelist[]=array("place_id"=>$row[0], "place"=>$row[1], "lati"=>$row[2], "long"=>$row[3], "zoom"=>$row[4], "icon"=>$row[5]);
	$res->free();
	return $placelist;
}

function outputLevel($parent_id) {
	global $TBLPREFIX, $DBCONN;
	$tmp=place_id_to_hierarchy($parent_id);
	$prefix=implode(';', $tmp);
	if ($prefix!='')
		$prefix.=';';
	$suffix=str_repeat(';', 3-count($tmp));
	$level=count($tmp);

	$sql="SELECT pl_id, pl_place,pl_long,pl_lati,pl_zoom,pl_icon FROM {$TBLPREFIX}placelocation WHERE pl_parent_id=".$DBCONN->escapeSimple($parent_id)." ORDER BY pl_place";
	$res=dbquery($sql);
	while ($row=&$res->fetchRow()) {
		print "{$level};{$prefix}{$row[1]}{$suffix};{$row[2]};{$row[3]};{$row[4]};{$row[5]}\r\n";
		if ($level < 3)
			outputLevel($row[0]);
	}
	$res->free();
}

if (($action=="ExportFile") && (userIsAdmin(getUserName()))) {
	$tmp=place_id_to_hierarchy($parent);
	$tmp[0]="places";
	$outputFileName=preg_replace('/[:;\/\\\(\)\{\}\[\] $]/', '_', implode('-', $tmp)).'.csv';
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename={$outputFileName}");
	print "\"Level\";\"Country\";\"State\";\"County\";\"Place\";\"Longitude\";\"Latitude\";\"ZoomLevel\";\"Icon\"\r\n";
	outputLevel($parent);
	exit;
}

print_header($pgv_lang["edit_place_locations"]);

if (!userIsAdmin(getUserName())) {
	print "<span class=\"subheaders\">{$pgv_lang['edit_place_locations']}</span><br /><br />";
	print "<table class=\"facts_table\">\n";
	print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_admin_error"];
	print "</td></tr></table>\n";
	print "<br/><br/><br/>\n";
	print_footer();
	exit;
}

if ($action=="ImportGedcom") {
	$placelist = array();
	$j = 0;
	if ($mode == "all") {
		$sql = "SELECT i_gedcom FROM ${TBLPREFIX}individuals UNION ALL SELECT f_gedcom FROM ${TBLPREFIX}families";
	}
	else {
		if (isset($GEDCOMS[$GEDCOM]["id"])) {
			// Needed for PGV 4.0
			$sql = "SELECT i_gedcom FROM ${TBLPREFIX}individuals WHERE i_file=".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])." UNION ALL SELECT f_gedcom FROM ${TBLPREFIX}families WHERE f_file=".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"]);
		} else {
			// Needed for PGV 3.3.8
			$sql = "SELECT i_gedcom FROM ${TBLPREFIX}individuals WHERE i_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["gedcom"])."' UNION ALL SELECT f_gedcom FROM ${TBLPREFIX}families WHERE f_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["gedcom"])."'";
		}
	}
	$res = dbquery($sql);
	while ($row =& $res->fetchRow()) {
		$i = 1;
		$placerec = get_sub_record(2, "2 PLAC", $row[0], $i);
		while (!empty($placerec)) {
			if (preg_match("/2 PLAC (.+)/", $placerec, $match)) {
				$placelist[$j] = array();
				$placelist[$j]["place"] = trim($match[1]);
				if (preg_match("/4 LATI (.*)/", $placerec, $match)) {
					$placelist[$j]["lati"] = trim($match[1]);
					if (($placelist[$j]["lati"][0] != "N") && ($placelist[$j]["lati"][0] != "S")) {
						if ($placelist[$j]["lati"] < 0) {
							$placelist[$j]["lati"][0] = "S";
						} else {
							$placelist[$j]["lati"] = "N".$placelist[$j]["lati"];
						}
					}
				}
				else $placelist[$j]["lati"] = "0";
				if (preg_match("/4 LONG (.*)/", $placerec, $match)) {
					$placelist[$j]["long"] = trim($match[1]);
					if (($placelist[$j]["long"][0] != "E") && ($placelist[$j]["long"][0] != "W")) {
						if ($placelist[$j]["long"] < 0) {
							$placelist[$j]["long"][0] = "W";
						} else {
							$placelist[$j]["long"] = "E".$placelist[$j]["long"];
						}
					}
				}
				else $placelist[$j]["long"] = "0";
				$j = $j + 1;
			}
			$i = $i + 1;
			$placerec = get_sub_record(2, "2 PLAC", $row[0], $i);
		}
	}
	$res->free();
	asort($placelist);

	$prevPlace = "";
	$prevLati = "";
	$prevLong = "";
	$placelistUniq = array();
	$j = 0;
	foreach ($placelist as $k=>$place) {
		if ($place["place"] != $prevPlace) {
			$placelistUniq[$j] = array();
			$placelistUniq[$j]["place"] = $place["place"];
			$placelistUniq[$j]["lati"] = $place["lati"];
			$placelistUniq[$j]["long"] = $place["long"];
			$j = $j + 1;
		} else if (($place["place"] == $prevPlace) && (($place["lati"] != $prevLati) || ($place["long"] != $prevLong))) {
			if (($placelistUniq[$j-1]["lati"] == 0) || ($placelistUniq[$j-1]["long"] == 0)) {
				$placelistUniq[$j-1]["lati"] = $place["lati"];
				$placelistUniq[$j-1]["long"] = $place["long"];
			} else if (($place["lati"] != "0") || ($place["long"] != "0")) {
				print "Verscil: vorige waarde = $prevPlace, $prevLati, $prevLong, huidige = ".$place["place"].", ".$place["lati"].", ".$place["long"]."<br/>";
			}
		}
		$prevPlace = $place["place"];
		$prevLati = $place["lati"];
		$prevLong = $place["long"];
	}

	$highestIndex = getHighestIndex();

	$default_zoom_level=array(4,7,10,12);
	foreach ($placelistUniq as $k=>$place) {
		$parent=preg_split ("/,/", $place["place"]);
		$parent=array_reverse($parent);
		$parent_id=0;
		for($i=0; $i<count($parent); $i++) {
			if (!isset($default_zoom_level[$i]))
				$default_zoom_level[$i]=$default_zoom_level[$i-1];
			$escparent=trim($DBCONN->escapeSimple($parent[$i]));
			if ($escparent == "") {
				$escparent = "Unknown";
			}
			$psql = "SELECT pl_id,pl_long,pl_lati,pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_level=".$i." AND pl_parent_id=$parent_id AND pl_place LIKE '".$DBCONN->escapeSimple($escparent)."'";
			$res = dbquery($psql);
			$row =& $res->fetchRow();
			$res->free();
			if ($i < count($parent)-1) {
				$sql="";
				// Create higher-level places, if necessary
				if (empty($row[0])) {
					$highestIndex++;
					$sql="INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".$highestIndex.", $parent_id, ".$i.", '".$escparent."', NULL, NULL, ".$default_zoom_level[$i].", NULL);";
					$parent_id=$highestIndex;
				} else {
					$parent_id=$row[0];
				}
			} else {
				// Create lowest-level place, if necessary
				if (empty($row[0])) {
					$highestIndex++;
					$sql="INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".$highestIndex.", $parent_id, ".$i.", '".$escparent."', '".$place["long"]."', '".$place["lati"]."', ".$default_zoom_level[$i].", NULL);";
				} else {
					if (empty($row[1]) && empty($row[2]) && $place['lati']!="0" && $place['long']!="0") {
						$sql="UPDATE ".$TBLPREFIX."placelocation SET pl_lati='".$place["lati"]."',pl_long='".$place["long"]."' where pl_id=".$row[0];
					}
				}
			}
			if (!empty($sql)) {
				print "$sql<br/>";
				$res=dbquery($sql);
			}
		}
	}
	$parent=0;
}

if ($action=="ImportFile") {
	/**
	 * recursively find all of the csv files on the server
	 *
	 * @param string $path
	 */
	function findFiles($path) {
		global $placefiles;
		if (file_exists($path)) {
			$dir = dir($path);
			while (false !== ($entry = $dir->read())) {
		   		if ($entry!="." && $entry!=".." && $entry!=".svn") {
		   			if (is_dir($path."/".$entry)) findFiles($path."/".$entry); 
		   			else if (strstr($entry, ".csv")!==false) $placefiles[] = preg_replace("~modules/googlemap/extra~", "", $path)."/".$entry;
		   		}
			}
			$dir->close();
		}
	}
	
	$placefiles = array();
	findFiles("modules/googlemap/extra");
?>
<form method="post" enctype="multipart/form-data" id="importfile" name="importfile" action="module.php?mod=googlemap&pgvaction=places">
	<input type="hidden" name="action" value="ImportFile2" />
	<table class="facts_table">
		<tr>
			<td class="descriptionbox"><?php print_help_link("PLIF_FILENAME_help", "qm", "PLIF_FILENAME");?><?php print $pgv_lang["pl_places_filename"];?></td>
			<td class="optionbox"><input type="file" name="placesfile" size="50"></td>
		</tr>
		<?php if (count($placefiles)>0) { ?>
		<tr>
			<td class="descriptionbox"><?php print_help_link("PLIF_LOCALFILE_help", "qm", "pl_places_localfile");?><?php print $pgv_lang["pl_places_localfile"];?></td>
			<td class="optionbox">
				<select name="localfile">
					<option></option>
					<?php foreach($placefiles as $p=>$placefile) { ?>
					<option value="<?php print htmlspecialchars($placefile); ?>"><?php print $placefile; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td class="descriptionbox"><?php print_help_link("PLIF_CLEAN_help", "qm", "PLIF_CLEAN");?><?php print $pgv_lang["pl_clean_db"];?></td>
			<td class="optionbox"><input type="checkbox" name="cleardatabase"></td>
		</tr>
		<tr>
			<td class="descriptionbox"><?php print_help_link("PLIF_UPDATE_help", "qm", "PLIF_UPDATE");?><?php print $pgv_lang["pl_update_only"];?></td>
			<td class="optionbox"><input type="checkbox" name="updateonly"></td>
		</tr>
		<tr>
			<td class="descriptionbox"><?php print_help_link("PLIF_OVERWRITE_help", "qm", "PLIF_OVERWRITE");?><?php print $pgv_lang["pl_overwrite_data"];?></td>
			<td class="optionbox"><input type="checkbox" name="overwritedata"></td>
		</tr>
	</table>
	<input id="savebutton" type="submit" value="<?php print $pgv_lang["continue"];?>" /><br />
</form>
<?php
	print_footer();
	exit;
}

if ($action=="ImportFile2") {
	if (isset($_POST["cleardatabase"])) {
		$sql = "DELETE FROM ".$TBLPREFIX."placelocation WHERE 1";
		$res = dbquery($sql);
	}
	if (!empty($_FILES["placesfile"]["tmp_name"])) $lines = file($_FILES["placesfile"]["tmp_name"]);
	else if (!empty($_REQUEST['localfile'])) $lines = file("modules/googlemap/extra".$_REQUEST['localfile']);
	// Strip BYTE-ORDER-MARK, if present
	if (!empty($lines[0]) && substr($lines[0],0,3)==chr(239).chr(187).chr(191)) $lines[0]=substr($lines[0],3);
	asort($lines);
	$highestIndex = getHighestIndex();
	$placelist = array();
	$j = 0;
	$maxLevel = 0;
	foreach ($lines as $p => $placerec){
		$fieldrec = preg_split ("/;/", $placerec);
		if($fieldrec[0] > $maxLevel) $maxLevel = $fieldrec[0];
	}
	foreach ($lines as $p => $placerec){
		$fieldrec = preg_split ("/;/", $placerec);
		if (is_numeric($fieldrec[0]) && $fieldrec[0]<=3) {
			$placelist[$j] = array();
			$placelist[$j]["place"] = "";
			if ($fieldrec[0] > 2) $placelist[$j]["place"]  = $fieldrec[4].", ";
			if ($fieldrec[0] > 1) $placelist[$j]["place"] .= $fieldrec[3].", ";
			if ($fieldrec[0] > 0) $placelist[$j]["place"] .= $fieldrec[2].", ";
			$placelist[$j]["place"] .= $fieldrec[1];
			$placelist[$j]["long"] = $fieldrec[5];
			$placelist[$j]["lati"] = $fieldrec[6];
			$placelist[$j]["zoom"] = $fieldrec[7];
			$placelist[$j]["icon"] = trim($fieldrec[8]);
			$j = $j + 1;
		}
	}

	$prevPlace = "";
	$prevLati = "";
	$prevLong = "";
	$placelistUniq = array();
	$j = 0;
	foreach ($placelist as $k=>$place) {
		if ($place["place"] != $prevPlace) {
			$placelistUniq[$j] = array();
			$placelistUniq[$j]["place"] = $place["place"];
			$placelistUniq[$j]["lati"] = $place["lati"];
			$placelistUniq[$j]["long"] = $place["long"];
			$placelistUniq[$j]["zoom"] = $place["zoom"];
			$placelistUniq[$j]["icon"] = $place["icon"];
			$j = $j + 1;
		} else if (($place["place"] == $prevPlace) && (($place["lati"] != $prevLati) || ($place["long"] != $prevLong))) {
			if (($placelistUniq[$j-1]["lati"] == 0) || ($placelistUniq[$j-1]["long"] == 0)) {
				$placelistUniq[$j-1]["lati"] = $place["lati"];
				$placelistUniq[$j-1]["long"] = $place["long"];
				$placelistUniq[$j-1]["zoom"] = $place["zoom"];
				$placelistUniq[$j-1]["icon"] = $place["icon"];
			} else if (($place["lati"] != "0") || ($place["long"] != "0")) {
				print "Differenc: last value = $prevPlace, $prevLati, $prevLong, current = ".$place["place"].", ".$place["lati"].", ".$place["long"]."<br/>";
			}
		}
		$prevPlace = $place["place"];
		$prevLati = $place["lati"];
		$prevLong = $place["long"];
	}

	$default_zoom_level = array();
	$default_zoom_level[0] = 4;
	$default_zoom_level[1] = 7;
	$default_zoom_level[2] = 10;
	$default_zoom_level[3] = 12;
	foreach ($placelistUniq as $k=>$place) {
		$parent = preg_split ("/,/", $place["place"]);
		$parent = array_reverse($parent);
		$parent_id=0;
		for($i=0; $i<count($parent); $i++) {
			$escparent=trim(preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($parent[$i])));
			if ($escparent == "") {
				$escparent = "Unknown";
			}
			$psql = "SELECT pl_id,pl_long,pl_lati,pl_zoom,pl_icon FROM ".$TBLPREFIX."placelocation WHERE pl_level=".$i." AND pl_parent_id=$parent_id AND pl_place LIKE '".$escparent."' ORDER BY pl_place";
			$res = dbquery($psql);
			$row =& $res->fetchRow();
			$res->free();
			if (empty($row[0])) {       // this name does not yet exist: create entry
				if (!isset($_POST["updateonly"])) {
					$highestIndex = $highestIndex + 1;
					if (($i+1) == count($parent)) {
						$zoomlevel = $place["zoom"];
					}
					else {
						$zoomlevel = $default_zoom_level[$i];
					}
					if (($place["lati"] == "0") || ($place["long"] == "0") || (($i+1) < count($parent))) {
						$sql = "INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".$highestIndex.", $parent_id, ".$i.", '".$escparent."', NULL, NULL, ".$default_zoom_level[$i].",'".$place["icon"]."');";
					}
					else {
						$sql = "INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".$highestIndex.", $parent_id, ".$i.", '".$escparent."', '".$place["long"]."' , '".$place["lati"]."', ".$zoomlevel.",'".$place["icon"]."');";
					}
					$parent_id = $highestIndex;
					$res = dbquery($sql);
					if (DB::isError($res)) print __FILE__." ".__LINE__;
				}
			}
			else {
				$parent_id = $row[0];
				if ((isset($_POST["overwritedata"])) && ($i+1 == count($parent))) {
					$sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_lati='".$place["lati"]."',pl_long='".$place["long"]."',pl_zoom='".$place["zoom"]."',pl_icon='".$place["icon"]."' where pl_id=$parent_id";
					$res = dbquery($sql, true, 1);
				}
				else {
					if ((($row[1] == "0") || ($row[1] == null)) && (($row[2] == "0") || ($row[2] == null))) {
						$sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_lati='".$place["lati"]."',pl_long='".$place["long"]."' where pl_id=$parent_id";
						$res = dbquery($sql, true, 1);
					}
					if (empty($row[4]) && !empty($place['icon'])) {
						$sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_icon='".$place["icon"]."' where pl_id=$parent_id";
						$res = dbquery($sql, true, 1);
					}
				}
			}
		}
	}
	$parent=0;
}

if ($action=="DeleteRecord") {
	$sql="SELECT 1 FROM {$TBLPREFIX}placelocation WHERE pl_parent_id=".$DBCONN->escapeSimple($deleteRecord);
	$res=dbquery($sql);
	if ($res->numRows()==0) {
		$res->free();
		$sql="DELETE FROM {$TBLPREFIX}placelocation WHERE pl_id=".$DBCONN->escapeSimple($deleteRecord);
		$res=dbquery($sql, true, 1);
	} else {
		print "<table class=\"facts_table\"><tr><td class=\"optionbox\">{$pgv_lang['pl_delete_error']}</td></tr></table>";
	}
}

?>
<script language="JavaScript" type="text/javascript">
<!--
var helpWin;
function helpPopup(which) {
	if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=400,resizable=1,scrollbars=1');
	else helpWin.location = 'modules/googlemap/editconfig_help.php?help='+which;
	return false;
}

function getHelp(which) {
	if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
}

function showchanges() {
	window.location = '<?php print $_SERVER["REQUEST_URI"]; ?>&show_changes=yes';
}

function edit_place_location(placeid) {
	window.open('module.php?mod=googlemap&pgvaction=places_edit&action=update&placeid='+placeid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}

function add_place_location(placeid) {
	window.open('module.php?mod=googlemap&pgvaction=places_edit&action=add&placeid='+placeid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}

function delete_place(placeid) {
	var answer=confirm("<?php print $pgv_lang["pl_remove_location"];?>");
	if (answer == true) {
		window.location = "<?php print $_SERVER["REQUEST_URI"]; ?>&action=DeleteRecord&deleteRecord=" + placeid;
	}
}

//-->
</script>
<?php

print "<span class=\"subheaders\">{$pgv_lang['edit_place_locations']}: </span>";
$where_am_i=place_id_to_hierarchy($parent);
foreach (array_reverse($where_am_i, true) as $id=>$place) {
	if ($id==$parent)
		print PrintReady($place);
	else
		print "<a href=\"module.php?mod=googlemap&pgvaction=places&parent={$id}\">".PrintReady($place)."</a>";
	print " - ";
}
print "<a href=\"module.php?mod=googlemap&pgvaction=places&parent=0\">{$pgv_lang['top_level']}</a>";

print "<br /><br /><form name=\"active\" method=\"post\" action=\"module.php?mod=googlemap&pgvaction=places&parent=$parent\">";
print "<table><tr><td class=\"optionbox\">".$pgv_lang["list_inactive"]."  - <input type=\"checkbox\" name=\"display\" value=\"inactive\"";
if (isset($display)) print " checked=\"checked\"";
print ">";
print "   <input type=\"submit\" value=\"".$pgv_lang["view"]."\"   >";
print_help_link("PLE_ACTIVE_help", "qm", "PLE_ACTIVE");
print "</td></tr></table>";
print "</form>";

$placelist=get_place_list_loc($parent);

print "<table class=\"facts_table\"><tr>";
print "<th class=\"descriptionbox\">{$factarray['PLAC']}</th>";
print "<th class=\"descriptionbox\">{$factarray['LATI']}</th>";
print "<th class=\"descriptionbox\">{$factarray['LONG']}</th>";
print "<th class=\"descriptionbox\">{$pgv_lang['pl_zoom_factor']}</th>";
print "<th class=\"descriptionbox\">{$pgv_lang['pl_place_icon']}</th>";
print "<th class=\"descriptionbox\" colspan=\"2\">";
print_help_link('PL_EDIT_LOCATION_help', 'qm', 'PL_EDIT_LOCATION'); 
print "{$pgv_lang['pl_edit']}</th></tr>";
if (count($placelist) == 0)
	print "<tr><td colspan=\"7\" class=\"facts_value\">{$pgv_lang['pl_no_places_found']}</td></tr>";
foreach ($placelist as $place) {
	print "<tr><td class=\"optionbox\"><a href=\"module.php?mod=googlemap&pgvaction=places&parent={$place['place_id']}\">".PrintReady($place["place"])."</a></td>";
	print "<td class=\"optionbox\">{$place['lati']}</td>";
	print "<td class=\"optionbox\">{$place['long']}</td>";
	print "<td class=\"optionbox\">{$place['zoom']}</td>";
	print "<td class=\"optionbox\">";
	if (($place["icon"] == NULL) || ($place["icon"] == "")) {
		print "&nbsp;";
		print "<img src=\"http://labs.google.com/ridefinder/images/mm_20_red.png\">";
	} else {
		print "<img src=\"".$place["icon"]." \"width=\"25\" height=\"15\">";
	}
	print "</td>";
	print "<td class=\"optionbox\"><a href=\"javascript:;\" onclick=\"edit_place_location({$place['place_id']});return false;\">{$pgv_lang["edit"]}</a></td>";
	$psql = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_parent_id=".$place["place_id"];
	$res = dbquery($psql);
	$noRows =& $res->numRows();
	$res->free();
	if ($noRows == 0) { ?>
	<td class="optionbox"><a href="javascript:;" onclick="delete_place(<?php print $place["place_id"].");return false;\">";?><img src="images/remove.gif" border="0" alt="<?php print $pgv_lang["remove"];?>" /></a></td>
<?php       } else { ?>
		<td class="optionbox"><img src="images/remove-dis.png" border="0" alt="" /> </td>
<?php       } ?>
	</tr>
	<?php
}
?>
</table>
<?php
?>
	<table class="facts_table">
		<tr>
			<td class="optionbox" colspan="2"><?php print_help_link("PL_ADD_LOCATION_help", "qm", "PL_ADD_LOCATION");?><a href="javascript:;" onclick="add_place_location(<?php print $parent;?>);return false;"><?php print $pgv_lang["pl_add_place"];?></a></td>
		</tr>
		<tr>
			<td class="optionbox"><?php print_help_link("PL_IMPORT_GEDCOM_help", "qm", "PL_IMPORT_GEDCOM");?><a href="module.php?mod=googlemap&pgvaction=places&action=ImportGedcom&mode=curr"><?php print $pgv_lang["pl_import_gedcom"];?></a></td>
			<td class="optionbox"><?php print_help_link("PL_IMPORT_ALL_GEDCOM_help", "qm", "PL_IMPORT_ALL_GEDCOM");?><a href="module.php?mod=googlemap&pgvaction=places&action=ImportGedcom&mode=all"><?php print $pgv_lang["pl_import_all_gedcom"];?></a></td>
		</tr>
		<tr>
			<td class="optionbox" colspan="2"><?php print_help_link("PL_IMPORT_FILE_help", "qm", "PL_IMPORT_FILE");?><a href="module.php?mod=googlemap&pgvaction=places&action=ImportFile&mode=add"><?php print $pgv_lang["pl_import_file"];?></a></td>
		</tr>
		<tr>
			<td class="optionbox">
<?php
	if (count($where_am_i)<=4) {
		print_help_link("PL_EXPORT_FILE_help", "qm", "PL_EXPORT_FILE");
		print "<a href=\"module.php?mod=googlemap&pgvaction=places&action=ExportFile&parent={$parent}\">";
		print "{$pgv_lang['pl_export_file']}</a>";
	} else {
		print "&nbsp;";
	}
	print "</td><td class=\"optionbox\">";
	print_help_link("PL_EXPORT_ALL_FILE_help", "qm", "PL_EXPORT_ALL_FILE");
	print "<a href=\"module.php?mod=googlemap&pgvaction=places&action=ExportFile&parent=0\">";
	print "{$pgv_lang['pl_export_all_file']}</a>";
	print "</td></tr></table><br/>";
if(empty($SEARCH_SPIDER))
	print_footer();
else {
	print $pgv_lang["label_search_engine_detected"].": ".$SEARCH_SPIDER;
	print "\n</div>\n\t</body>\n</html>";
}
?>
