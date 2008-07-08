<?php
/**
 * Class file for a Source (SOUR) object
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005	John Finlay and Others
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
 * @subpackage DataModel
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once('includes/datamodel/gedcomrecord_class.php');

class Source extends GedcomRecord {
	var $disp = true;
	var $sourcefacts = null;
	var $indilist = null;
	var $famlist = null;

	/**
	 * Constructor for source object
	 * @param string $gedrec	the raw source gedcom record
	 */
	function Source($gedrec) {
		parent::GedcomRecord($gedrec);
		$this->disp = displayDetailsByID($this->xref, "SOUR");
	}

	/**
	 * Static function used to get an instance of a source object
	 * @param string $pid	the ID of the source to retrieve
	 */
	function &getInstance($pid, $simple=true) {
		global $gedcom_record_cache, $GEDCOM, $pgv_changes;

		$ged_id=get_id_from_gedcom($GEDCOM);
		// Check the cache first
		if (isset($gedcom_record_cache[$pid][$ged_id])) {
			return $gedcom_record_cache[$pid][$ged_id];
		}

		$sourcerec = find_source_record($pid);
		if (empty($sourcerec)) {
			$ct = preg_match("/(\w+):(.+)/", $pid, $match);
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				require_once 'includes/serviceclient_class.php';
				$service = ServiceClient::getInstance($servid);
				$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$pid."@ SOUR\r\n1 RFN ".$pid, false);
				$sourcerec = $newrec;
			}
		}
		if (empty($sourcerec)) {
			if (PGV_USER_CAN_EDIT && isset($pgv_changes[$pid."_".$GEDCOM])) {
				$sourcerec = find_updated_record($pid);
				$fromfile = true;
			}
		}
		if (empty($sourcerec)) return null;
		$source = new Source($sourcerec, $simple);
		if (!empty($fromfile)) $source->setChanged(true);
		// Store the object in the cache
		$source->ged_id=$ged_id;
		$gedcom_record_cache[$pid][$ged_id]=&$source;
		return $source;
	}

	/**
	 * Check if privacy options allow this record to be displayed
	 * @return boolean
	 */
	function canDisplayDetails() {
		return $this->disp;
	}

	/**
	 * get source facts array
	 * @return array
	 */
	function getSourceFacts() {
		$this->parseFacts();
		return $this->sourcefacts;
	}

	/**
	 * get the count of individuals connected to this source
	 * @return array
	 */
	function countSourceIndis() {
		return get_list_size("indilist", "SOUR @".$this->xref."@");
	}

	/**
	 * get the list of individuals connected to this source
	 * @return array
	 */
	function getSourceIndis() {
		global $DBCONN, $indilist, $TBLPREFIX, $GEDCOM, $GEDCOMS;
		if (!is_null($this->indilist)) return $this->indilist;

		$this->indilist = array();

		$sql = "SELECT * FROM ".$TBLPREFIX."individuals, ".$TBLPREFIX."sourcelinks WHERE i_id=sl_gid AND i_file=sl_file AND sl_sid='".$DBCONN->escapeSimple($this->xref)."' AND sl_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])."' ORDER BY i_surname";
		$res = dbquery($sql);
	
		$ct = $res->numRows();
		while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$indi = array();
			$indi["gedcom"] = $row["i_gedcom"];
			$row = db_cleanup($row);
			$indi["names"] = array(array($row["i_name"], $row["i_letter"], $row["i_surname"], "A"));
			$indi["isdead"] = $row["i_isdead"];
			$indi["gedfile"] = $row["i_file"];
			$indilist[$row["i_id"]] = $indi;
			$this->indilist[$row["i_id"]] = $indi;
		}
		$res->free();
		
		uasort($this->indilist, "itemsort");
		
		//-- load up the families with 1 query
		$famids = array();
		foreach($this->indilist as $gid=>$indi) {
			$ct = preg_match_all("/1 FAMS @(.*)@/", $indi["gedcom"], $match, PREG_SET_ORDER);
			for($i=0; $i<$ct; $i++) {
				$famid = $match[$i][1];
				$famids[] = $famid;
			}
		}
		load_families($famids);
		return $this->indilist;
	}

	/**
	 * get the count of families connected to this source
	 * @return array
	 */
	function countSourceFams() {
		return get_list_size("famlist", "SOUR @".$this->xref."@");
	}

	/**
	 * get the list of families connected to this source
	 * @return array
	 */
	function getSourceFams() {
		global $DBCONN, $famlist, $TBLPREFIX, $GEDCOM, $GEDCOMS;
		if (!is_null($this->famlist)) return $this->famlist;
		$this->famlist = array();
		$sql = "SELECT * FROM ".$TBLPREFIX."families, ".$TBLPREFIX."sourcelinks WHERE sl_file=f_file AND sl_sid='".$DBCONN->escapeSimple($this->xref)."' AND sl_gid=f_id AND f_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])."'";
		$res = dbquery($sql);

		$ct = $res->numRows();
		while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$fam = array();
			$fam["gedcom"] = $row["f_gedcom"];
			$row = db_cleanup($row);
			$hname = get_sortable_name($row["f_husb"]);
			$wname = get_sortable_name($row["f_wife"]);
			$name = "";
			if (!empty($hname)) $name = $hname;
			else $name = "@N.N., @P.N.";
	
			if (!empty($wname)) $name .= " + ".$wname;
			else $name .= " + @N.N., @P.N.";
	
			$fam["name"] = $name;
			$fam["HUSB"] = $row["f_husb"];
			$fam["WIFE"] = $row["f_wife"];
			$fam["CHIL"] = $row["f_chil"];
			$fam["gedfile"] = $row["f_file"];
			$fam["numchil"] = $row["f_numchil"];
			$famlist[$row["f_id"]] = $fam;
			$this->famlist[$row["f_id"]] = $fam;
		}
		$res->free();
		uasort($this->famlist, "itemsort");
		return $this->famlist;
	}

	/**
	 * get the count of objects connected to this source
	 * @return array
	 */
	function countSourceObjects() {
		return get_list_size("objectlist", "SOUR @".$this->xref."@");
	}

	/**
	 * get the source name
	 * @return string
	 */
	function getName() {
		return $this->getFullName();
	}

	/**
	 * get the repository of this source record
	 * @return string
	 */
	function getRepo() {
		if (!isset($this->repo)) $this->repo = get_gedcom_value("REPO", 1, $this->gedrec, '', false);
		return $this->repo;
	}

	/**
	 * get the author of this source record
	 * @return string
	 */
	function getAuth() {
		if (!isset($this->auth)) $this->auth = get_gedcom_value("AUTH", 1, $this->gedrec, '', false);
		return $this->auth;
	}

	/**
	 * get the URL to link to this source
	 * @string a url that can be used to link to this source
	 */
	function getLinkUrl() {
		return parent::getLinkUrl('source.php?sid=');
	}

	// Get an array of structures containing all the names in the record
	function getAllNames() {
		return parent::getAllNames('TITL');
	}
}
?>
