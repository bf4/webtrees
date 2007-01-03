<?php
/**
 * Online UI for editing config.php site configuration variables
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
 * This Page Is Valid XHTML 1.0 Transitional! > 17 September 2005
 *
 * @package PhpGedView
 * @subpackage GoogleMap
 * @see config.php
 * @version $Id: editconfig.php,v$
 */

//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"menu.php")) {
    print "Now, why would you want to do that.  You're not hacking are you?";
    exit;
}
require( "modules/googlemap/defaultconfig.php" );
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');

require( $pgv_language["english"]);
if (file_exists( $pgv_language[$LANGUAGE])) require  $pgv_language[$LANGUAGE];
require $confighelpfile["english"];
if (file_exists($confighelpfile[$LANGUAGE])) require $confighelpfile[$LANGUAGE];
require $helptextfile["english"];
if (file_exists($helptextfile[$LANGUAGE])) require $helptextfile[$LANGUAGE];
require_once($factsfile["english"]);
if (file_exists( $factsfile[$LANGUAGE])) require_once $factsfile[$LANGUAGE];

require( "modules/googlemap/".$pgv_language["english"]);
if (file_exists( "modules/googlemap/".$pgv_language[$LANGUAGE])) require  "modules/googlemap/".$pgv_language[$LANGUAGE];
require( "modules/googlemap/".$helptextfile["english"]);
if (file_exists("modules/googlemap/".$helptextfile[$LANGUAGE])) require "modules/googlemap/".$helptextfile[$LANGUAGE];

if (!isset($action)) $action="";

if (($action=="ExportFile") && (userIsAdmin(getUserName()))) {
    if (!isset($parent)) $parent=array();
    if (!isset($level)) $level=0;
    $outputLevelStr = "\"Level\";\"Country\";\"State\";\"County\";\"Place\";\"Longitude\";\"Latitude\";\"ZoomLevel\";\"Icon\"\r\n";
    $outputFileName = "places";
    for ($i = 0; $i < $level; $i++) $outputFileName .= "-".$parent[$i];
    $outputFileName .= ".csv";
    outputLevel($parent, $level, 3);
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=".$outputFileName);
    print_r ($outputLevelStr);
    exit;
}

print_header($pgv_lang["edit_place_locations"]);

print "<span class=\"subheaders\">".$pgv_lang["edit_place_locations"]."</span><br/><br/>";
if (!userIsAdmin(getUserName())) {
    print "<table class=\"facts_table\">\n";
    print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_admin_error"];
    print "</td></tr></table>\n";
    print "<br/><br/><br/>\n";
    print_footer();
    exit;
}

/**
 * get place parent ID
 * @param array $parent
 * @param int $level
 * @return int
 */
function get_place_parent_id_loc($parent, $level) {
    global $DBCONN, $TBLPREFIX;

    $parent_id=0;
    for($i=0; $i<$level; $i++) {
        $escparent=ltrim(rtrim(preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($parent[$i]))));
        $psql = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_level=".$i." AND pl_parent_id=$parent_id AND pl_place LIKE '".$escparent."' ORDER BY pl_place";
        $res = dbquery($psql);
        $row =& $res->fetchRow();
        $res->free();
        if (empty($row[0])) break;
        $parent_id = $row[0];
    }
    return $parent_id;
}


/**
 * find all of the places in the hierarchy
 * The $parent array holds the parent hierarchy of the places
 * we want to get.  The level holds the level in the hierarchy that
 * we are at.
 */
function get_place_list_loc() {
    global $level, $parent, $found;
    global $TBLPREFIX, $placelist, $positions, $DBCONN;

    // --- find all of the place in the file
    if ($level==0) $sql = "SELECT pl_id,pl_place,pl_lati,pl_long,pl_zoom,pl_icon FROM ".$TBLPREFIX."placelocation WHERE pl_level=0 ORDER BY pl_place";
    else {
        $parent_id = get_place_parent_id_loc($parent, $level);
        $sql = "SELECT pl_id,pl_place,pl_lati,pl_long,pl_zoom,pl_icon FROM ".$TBLPREFIX."placelocation WHERE pl_level=$level AND pl_parent_id=$parent_id ORDER BY pl_place";
    }
    $res = dbquery($sql);

    $i = 0;
    while ($row =& $res->fetchRow()) {
        $placeloc = array();
        $placeloc["place_id"] = $row[0];
        $placeloc["place"] = $row[1];
        $placeloc["lati"] = $row[2];
        $placeloc["long"] = $row[3];
        $placeloc["zoom"] = $row[4];
        $placeloc["icon"] = $row[5];
        $placelist[$i] = $placeloc;
        $i = $i + 1;
    }
    $res->free();
}

function getHighestIndex() {
    global $TBLPREFIX;
    $sql = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE 1";
    $res = dbquery($sql);
    $i = 0;
    while ($row =& $res->fetchRow()) {
        if ($row[0] > $i) $i  = $row[0];
    }
    $res->free();
    return $i;
}

function outputLevel($parent, $level, $nofLevels) {
    global $TBLPREFIX, $outputLevelStr;
    if ($level == 0) {
        $sql = "SELECT pl_id,pl_parent_id,pl_level,pl_place,pl_long,pl_lati,pl_zoom,pl_icon FROM ".$TBLPREFIX."placelocation WHERE pl_level=0 ORDER BY pl_place ASC";
    }
    else {
        $parent_id = get_place_parent_id_loc($parent, $level);
        $sql = "SELECT pl_id,pl_parent_id,pl_level,pl_place,pl_long,pl_lati,pl_zoom,pl_icon FROM ".$TBLPREFIX."placelocation WHERE pl_level=$level AND pl_parent_id=$parent_id ORDER BY pl_place ASC";
    }
    $res = dbquery($sql);
    $parent_prefix = "";
    for($i=0; $i < $level; $i++) $parent_prefix = $parent_prefix.$parent[$i].";";
    $parent_postfix = ";";
    for($i=$nofLevels; $i > $level; $i--) $parent_postfix = $parent_postfix.";";
    while ($row =& $res->fetchRow()) {
        $outputLevelStr .= $row[2].";".$parent_prefix.$row[3].$parent_postfix.$row[4].";".$row[5].";".$row[6].";".$row[7]."\r\n";
        if($level < 3) {
            $parent[$level] = $row[3];
            outputLevel($parent, $level+1, $nofLevels);
        }
    }
    $res->free();
}

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

if ($action=="ImportGedcom") {
    $placelist = array();
    $j = 0;
    if ($mode == "all") {
        $sql = "SELECT i_gedcom FROM ".$TBLPREFIX."individuals WHERE 1";
    }
    else {
        if (isset($GEDCOMS[$GEDCOM]["id"])) {
            // Needed for PGV 4.0
            $sql = "SELECT i_gedcom FROM ".$TBLPREFIX."individuals WHERE i_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])."'";
        } else {
            // Needed for PGV 3.3.8
            $sql = "SELECT i_gedcom FROM ".$TBLPREFIX."individuals WHERE i_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["gedcom"])."'";
        }
    }
    $res = dbquery($sql);
    while ($row =& $res->fetchRow()) {
        $i = 1;
        $placerec = get_sub_record(2, "2 PLAC", $row[0], $i);
        while (($placerec != "") && ($i < 10))
        {
            $placelist[$j] = array();
            $ct = preg_match_all("/\d PLAC (.*)/", $placerec, $match, PREG_SET_ORDER);
            $placelist[$j]["place"] = rtrim(ltrim($match[0][1]));
            $ct = preg_match_all("/\d LATI (.*)/", $placerec, $match, PREG_SET_ORDER);
            if ($ct > 0) {
                $placelist[$j]["lati"] = rtrim(ltrim($match[0][1]));
                if (($placelist[$j]["lati"][0] != "N") && ($placelist[$j]["lati"][0] != "S")) {
                    if ($placelist[$j]["lati"] < 0) {
                        $placelist[$j]["lati"][0] = "S";
                    }
                    else {
                        $placelist[$j]["lati"] = "N".$placelist[$j]["lati"];
                    }
                }
            }
            else $placelist[$j]["lati"] = "0";
            $ct = preg_match_all("/\d LONG (.*)/", $placerec, $match, PREG_SET_ORDER);
            if ($ct > 0) {
                $placelist[$j]["long"] = rtrim(ltrim($match[0][1]));
                if (($placelist[$j]["long"][0] != "E") && ($placelist[$j]["long"][0] != "W")) {
                    if ($placelist[$j]["long"] < 0) {
                        $placelist[$j]["long"][0] = "W";
                    }
                    else {
                        $placelist[$j]["long"] = "E".$placelist[$j]["long"];
                    }
                }
            }
            else $placelist[$j]["long"] = "0";
            $i = $i + 1;
            $j = $j + 1;
            $placerec = get_sub_record(2, "2 PLAC", $row[0], $i);
        }
    }
    $res->free();
    if ($mode == "all") {
        $sql = "SELECT f_gedcom FROM ".$TBLPREFIX."families WHERE 1";
    }
    else {
        if (isset($GEDCOMS[$GEDCOM]["id"])) {
            // Needed for PGV 4.0
            $sql = "SELECT f_gedcom FROM ".$TBLPREFIX."families WHERE f_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])."'";
        } else {
            // Needed for PGV 3.3.8
            $sql = "SELECT f_gedcom FROM ".$TBLPREFIX."families WHERE f_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["gedcom"])."'";
        }
    }
    $res = dbquery($sql);
    while ($row =& $res->fetchRow()) {
        $i = 1;
        $placerec = get_sub_record(2, "2 PLAC", $row[0], $i);
        while (($placerec != "") && ($i < 10))
        {
            $placelist[$j] = array();
            $ct = preg_match_all("/\d PLAC (.*)/", $placerec, $match, PREG_SET_ORDER);
            $placelist[$j]["place"] = rtrim(ltrim($match[0][1]));
            $ct = preg_match_all("/\d LATI (.*)/", $placerec, $match, PREG_SET_ORDER);
            if ($ct > 0) {
                $placelist[$j]["lati"] = rtrim(ltrim($match[0][1]));
                if (($placelist[$j]["lati"][0] != "N") && ($placelist[$j]["lati"][0] != "S")) {
                    if ($placelist[$j]["lati"] < 0) {
                        $placelist[$j]["lati"][0] = "S";
                    }
                    else {
                        $placelist[$j]["lati"] = "N".$placelist[$j]["lati"];
                    }
                }
            }
            else $placelist[$j]["lati"] = "0";
            $ct = preg_match_all("/\d LONG (.*)/", $placerec, $match, PREG_SET_ORDER);
            if ($ct > 0) {
                $placelist[$j]["long"] = rtrim(ltrim($match[0][1]));
                if (($placelist[$j]["long"][0] != "E") && ($placelist[$j]["long"][0] != "W")) {
                    if ($placelist[$j]["long"] < 0) {
                        $placelist[$j]["long"][0] = "W";
                    }
                    else {
                        $placelist[$j]["long"] = "E".$placelist[$j]["long"];
                    }
                }
            }
            else $placelist[$j]["long"] = "0";
            $i = $i + 1;
            $j = $j + 1;
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

    $default_zoom_level = array();
    $default_zoom_level[0] = 4;
    $default_zoom_level[1] = 7;
    $default_zoom_level[2] = 10;
    $default_zoom_level[3] = 12;
    foreach ($placelistUniq as $k=>$place) {
        $parent = preg_split ("/,/", $place["place"]);
        $parent = array_reverse($parent);
        $parent_id=0;
        $parent_long = 0;
        $parent_lati = 0;
        for($i=0; $i<count($parent); $i++) {
            $escparent=ltrim(rtrim(preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($parent[$i]))));
            if ($escparent == "") {
                $escparent = "Unknown";
            }
            $psql = "SELECT pl_id,pl_long,pl_lati,pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_level=".$i." AND pl_parent_id=$parent_id AND pl_place LIKE '".$escparent."' ORDER BY pl_place";
            $res = dbquery($psql);
            $row =& $res->fetchRow();
            $res->free();
            if (empty($row[0])) {       // this name does not yet exist: create entry
                $highestIndex = $highestIndex + 1;
                if (!isset($default_zoom_level[$i])) $default_zoom_level[$i] = $default_zoom_level[$i-1];
                if (($place["lati"] == "0") || ($place["long"] == "0") || (($i+1) < count($parent))) {
                    $sql = "INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".$highestIndex.", $parent_id, ".$i.", '".$escparent."', NULL, NULL, ".$default_zoom_level[$i].", NULL);";
                }
                else {
                    $sql = "INSERT INTO ".$TBLPREFIX."placelocation (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (".$highestIndex.", $parent_id, ".$i.", '".$escparent."', '".$place["long"]."' , '".$place["lati"]."', ".$default_zoom_level[$i].", NULL);";
                }
                $parent_id = $highestIndex;
                print $sql."<br/>";
                if (userIsAdmin(getUserName())) {
                    $res = dbquery($sql);
                }
            }
            else {
                $parent_id = $row[0];
                if (($row[1] == "0") && ($row[2] == "0")) {
                    $sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_lati='".$place["lati"]."',pl_long='".$place["long"]."' where pl_id=$parent_id";
                    print $sql."<br/>";
                    if (userIsAdmin(getUserName())) {
                        $res = dbquery($sql, true, 1);
                    }
                }
                else {
                    $parent_long = $row[1];
                    $parent_lati = $row[2];
                }
            }
        }
    }
    $parent=array();
    $level = 0;
}
?>
<script language="JavaScript" type="text/javascript">
<!--
var helpWin;
function helpPopup(which) {
    if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
    else helpWin.location = 'modules/googlemap/editconfig_help.php?help='+which;
    return false;
}

function getHelp(which) {
    if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
}

function showchanges() {
    window.location = '<?php print $_SERVER["REQUEST_URI"]; ?>&show_changes=yes';
}
//-->
</script>
<?php
if ($action=="ImportFile") {
?>
<form method="post" enctype="multipart/form-data" id="importfile" name="importfile" action="module.php?mod=googlemap&pgvaction=places">
    <input type="hidden" name="action" value="ImportFile2" />
    <table class="facts_table">
        <tr>
            <td class="descriptionbox"><?php print_help_link("PLIF_FILENAME_help", "qm", "PLIF_FILENAME");?><?php print $pgv_lang["pl_places_filename"];?></td>
            <td class="optionbox"><input type="file" name="placesfile" size="50"></td>
        </tr>
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
    <input id="savebutton" type="submit" value="<?php print $pgv_lang["save"];?>" /><br />
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
    $lines = file($_FILES["placesfile"]["tmp_name"]);
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
        $placelist[$j] = array();
        $fieldrec = preg_split ("/;/", $placerec);
        if (($fieldrec[0] == "0") || ($fieldrec[0] == "1") || ($fieldrec[0] == "2") || ($fieldrec[0] == "3")) {
            $placelist[$j]["place"] = "";
            if ($fieldrec[0] > 2) $placelist[$j]["place"]  = $fieldrec[4].", ";
            if ($fieldrec[0] > 1) $placelist[$j]["place"] .= $fieldrec[3].", ";
            if ($fieldrec[0] > 0) $placelist[$j]["place"] .= $fieldrec[2].", ";
            $placelist[$j]["place"] .= $fieldrec[1];
            $placelist[$j]["long"] = $fieldrec[5];
            $placelist[$j]["lati"] = $fieldrec[6];
            $placelist[$j]["zoom"] = $fieldrec[7];
            $placelist[$j]["icon"] = ltrim(rtrim($fieldrec[8]));
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
        $parent_long = 0;
        $parent_lati = 0;
        for($i=0; $i<count($parent); $i++) {
            $escparent=ltrim(rtrim(preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($parent[$i]))));
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
                    if (userIsAdmin(getUserName())) {
                        $res = dbquery($sql);
                    }
                }
            }
            else {
                $parent_id = $row[0];
                if ((isset($_POST["overwritedata"])) && ($i+1 == count($parent))) {
                    $sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_lati='".$place["lati"]."',pl_long='".$place["long"]."',pl_zoom='".$place["zoom"]."',pl_icon='".$place["icon"]."' where pl_id=$parent_id";
                    if (userIsAdmin(getUserName())) {
                        $res = dbquery($sql, true, 1);
                    }
                }
                else {
                    if ((($row[1] == "0") || ($row[1] == null)) && (($row[2] == "0") || ($row[2] == null))) {
                        $sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_lati='".$place["lati"]."',pl_long='".$place["long"]."' where pl_id=$parent_id";
                        if (userIsAdmin(getUserName())) {
                            $res = dbquery($sql, true, 1);
                        }
                    }
                    else {
                        $parent_long = $row[1];
                        $parent_lati = $row[2];
                    }
                    if (($row[4] == "") || ($row[4] == null)) {
                        $sql = "UPDATE ".$TBLPREFIX."placelocation SET pl_icon='".$place["icon"]."',pl_long='".$place["long"]."' where pl_id=$parent_id";
                        if (userIsAdmin(getUserName())) {
                            $res = dbquery($sql, true, 1);
                        }
                    }
                }
            }
        }
    }
    $parent=array();
    $level = 0;

}

if ($action=="DeleteRecord") {
    $sql = "SELECT pl_id, pl_place FROM ".$TBLPREFIX."placelocation WHERE pl_parent_id=".$deleteRecord." ORDER BY pl_place";
    $res = dbquery($sql);
    if ($res->numRows() == 0) {
        $res->free();
        $sql = "DELETE FROM ".$TBLPREFIX."placelocation WHERE pl_id=".$deleteRecord;
        $res = dbquery($sql, true, 1);
    }
    else { ?>
    <table class="facts_table">
        <tr>
            <td class="optionbox" ><?php print $pgv_lang["pl_delete_error"];?></a></td>
        </tr>
    </table>
<?php }
}

if (!isset($parent)) $parent=array();
else {
    if (!is_array($parent)) $parent = array();
    else $parent = array_values($parent);
}
// Remove slashes
$lrm = chr(0xE2).chr(0x80).chr(0x8E);
$rlm = chr(0xE2).chr(0x80).chr(0x8F);
foreach ($parent as $p => $child){
    $child = stripslashes($child);
    $parent[$p] = str_replace(array($lrm, $rlm), "", $child);
}

//-- extract the place form encoded in the gedcom
$header = find_gedcom_record("HEAD");
$hasplaceform = strpos($header, "1 PLAC");
if (!isset($level)) {
    $level=0;
}
if ($level>count($parent)) $level = count($parent);
if ($level<count($parent)) $level = 0;

if ($level > 0) {
    for($i = $level; $i > 0 ; $i--) {
        print "<a href=\"module.php?mod=googlemap&pgvaction=places&level=$level";
        for($j = 0; $j < $i; $j++) {
            print "&parent[$j]=".$parent[$j];
        }
        print "\">".$parent[$i-1]."</a> - ";
    }
}
print "<a href=\"module.php?mod=googlemap&pgvaction=places&level=0\">".$pgv_lang["top_level"]."</a><br /><br />";

$placelist = array();
$positions = array();
$numfound = 0;
get_place_list_loc();
// -- sort the array

if (count($placelist) == 0) {
    print "<table class=\"facts_table\">\n";
    print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["pl_no_places_found"];
    print "</td></tr></table>\n";
    print "<br/><br/><br/>\n";
}

?>
<script language="JavaScript" type="text/javascript">
<!--
function edit_place_location(placeid) {
    var placelink = "<?php
        $placelink = "&level=".$level;
        for($j = 0; $j < count($parent); $j++) {
            $placelink .= "&parent[$j]=".$parent[$j];
        }
        print $placelink;
    ?>";
    window.open('module.php?mod=googlemap&pgvaction=places_edit&action=update&placeid='+placeid+"&"+sessionname+"="+sessionid+placelink, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
    return false;
}

function add_place_location(placeid) {
    var placelink = "<?php
        $placelink = "&level=".$level;
        for($j = 0; $j < count($parent); $j++) {
            $placelink .= "&parent[$j]=".$parent[$j];
        }
        print $placelink;
    ?>";
    window.open('module.php?mod=googlemap&pgvaction=places_edit&action=add&placeid='+placeid+"&"+sessionname+"="+sessionid+placelink, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
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
if (count($placelist) <> 0) {
?>
    <table class="facts_table">
        <tr>
            <td class="descriptionbox"><?php print $factarray["PLAC"];?></td>
            <td class="descriptionbox"><?php print $factarray["LATI"];?></td>
            <td class="descriptionbox"><?php print $factarray["LONG"];?></td>
            <td class="descriptionbox"><?php print $pgv_lang["pl_zoom_factor"];?></td>
            <td class="descriptionbox"><?php print $pgv_lang["pl_place_icon"];?></td>
            <td class="descriptionbox" colspan="2"><?php print_help_link("PL_EDIT_LOCATION_help", "qm", "PL_EDIT_LOCATION");?><?php print $pgv_lang["pl_edit"];?></td>
        </tr>
    <?php
    for($i = 0; $i < count($placelist); $i++)
    {
        $placelink = "&level=".($level+1);
        for($j = 0; $j < count($parent); $j++) {
            $placelink .= "&parent[$j]=".$parent[$j];
        }
        $placelink .= "&parent[$level]=".$placelist[$i]["place"];
        ?>
        <tr>
            <?php
            print "<td class=\"optionbox\"><a href=\"module.php?mod=googlemap&pgvaction=places".$placelink."\">".PrintReady($placelist[$i]["place"])."</a></td>\n";
            ?>

            <td class="optionbox"><?php print $placelist[$i]["lati"];?></td>
            <td class="optionbox"><?php print $placelist[$i]["long"];?></td>
            <td class="optionbox"><?php print $placelist[$i]["zoom"];?></td>
            <td class="optionbox">
<?php       if (($placelist[$i]["icon"] == NULL) || ($placelist[$i]["icon"] == "")) {
                print "&nbsp;";
                print "<img src=\"http://labs.google.com/ridefinder/images/mm_20_red.png\">";
            }
            else {
                print "<img src=\"".$placelist[$i]["icon"]." \"width=\"25\" height=\"15\">";
            } ?>
            </td>
            <td class="optionbox"><a href="javascript:;" onclick="edit_place_location(<?php print $placelist[$i]["place_id"].");return false;\">".$pgv_lang["edit"];?></a></td>
<?php
            $psql = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_parent_id=".$placelist[$i]["place_id"];
            $res = dbquery($psql);
            $noRows =& $res->numRows();
            $res->free();
            if ($noRows == 0) { ?>
            <td class="optionbox"><a href="javascript:;" onclick="delete_place(<?php print $placelist[$i]["place_id"].");return false;\">";?><img src="images/remove.gif" border="0" alt="<?php print $pgv_lang["remove"];?>" /></a></td>
<?php       } else { ?>
            <td class="optionbox"><img src="images/remove-dis.png" border="0" alt="" /> </td>
<?php       } ?>
        </tr>
        <?php
    }
    $placelink = "";
    for($j = 0; $j < count($parent); $j++) {
        $placelink .= "&parent[$j]=".$parent[$j];
    }
    ?>
    </table>
<?php
}
?>
    <table class="facts_table">
        <tr>
            <td class="optionbox" colspan="2"><?php print_help_link("PL_ADD_LOCATION_help", "qm", "PL_ADD_LOCATION");?><a href="javascript:;" onclick="add_place_location(<?php print get_place_parent_id_loc($parent, $level);?>);return false;"><?php print $pgv_lang["pl_add_place"];?></a></td>
        </tr>
        <tr>
            <td class="optionbox"><?php print_help_link("PL_IMPORT_GEDCOM_help", "qm", "PL_IMPORT_GEDCOM");?><a href="module.php?mod=googlemap&pgvaction=places&action=ImportGedcom&mode=curr&level=<?php print $level.$placelink;?>"><?php print $pgv_lang["pl_import_gedcom"];?></a></td>
            <td class="optionbox"><?php print_help_link("PL_IMPORT_ALL_GEDCOM_help", "qm", "PL_IMPORT_ALL_GEDCOM");?><a href="module.php?mod=googlemap&pgvaction=places&action=ImportGedcom&mode=all&level=<?php print $level.$placelink;?>"><?php print $pgv_lang["pl_import_all_gedcom"];?></a></td>
        </tr>
        <tr>
            <td class="optionbox" colspan="2"><?php print_help_link("PL_IMPORT_FILE_help", "qm", "PL_IMPORT_FILE");?><a href="module.php?mod=googlemap&pgvaction=places&action=ImportFile&mode=add"><?php print $pgv_lang["pl_import_file"];?></a></td>
        </tr>
        <tr>
            <td class="optionbox"><?php print_help_link("PL_EXPORT_FILE_help", "qm", "PL_EXPORT_FILE");?><a href="module.php?mod=googlemap&pgvaction=places&action=ExportFile&level=<?php print $level.$placelink;?>"><?php print $pgv_lang["pl_export_file"];?></a></td>
            <td class="optionbox"><?php print_help_link("PL_EXPORT_ALL_FILE_help", "qm", "PL_EXPORT_ALL_FILE");?><a href="module.php?mod=googlemap&pgvaction=places&action=ExportFile&level=0"><?php print $pgv_lang["pl_export_all_file"];?></a></td>
        </tr>
    </table><br/>

    <?php
if(empty($SEARCH_SPIDER))
    print_footer();
else {
    print $pgv_lang["label_search_engine_detected"].": ".$SEARCH_SPIDER;
    print "\n</div>\n\t</body>\n</html>";
}
?>
