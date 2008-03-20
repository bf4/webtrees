<?php
/**
 * Base class for all gedcom records
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007	John Finlay and Others
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

require_once('includes/person_class.php');
require_once('includes/family_class.php');
require_once('includes/source_class.php');
require_once('includes/repository_class.php');
require_once('includes/media_class.php');
require_once('includes/event_class.php');

class GedcomRecord {
	var $gedrec = "";
	var $xref = "";
	var $type = "";
	var $changed = false;
	var $rfn = null;
	var $facts = null;
	var $changeEvent = null;
	var $disp = null;

	/**
	 * constructor for this class
	 */
	function GedcomRecord($gedrec, $simple=false) {
		if (empty($gedrec)) return;

		//-- lookup the record from another gedcom
		$remoterfn = get_gedcom_value("RFN", 1, $gedrec);
		if (!empty($remoterfn)) {
			$parts = preg_split("/:/", $remoterfn);
			if (count($parts)==2) {
				$servid = $parts[0];
				$aliaid = $parts[1];
				if (!empty($servid)&&!empty($aliaid)) {
					require_once 'includes/serviceclient_class.php';
					$serviceClient = ServiceClient::getInstance($servid);
					if (!is_null($serviceClient)) {
						if (!$simple || $serviceClient->type=='local') {
							$gedrec = $serviceClient->mergeGedcomRecord($aliaid, $gedrec, true);
						}
					}
				}
			}
		}

		//-- set the gedcom record a privatized version
		//-- temporarily take out this privacy, since we will probably check privacy again later
		// $this->gedrec = privatize_gedcom($gedrec);
		$this->gedrec = $gedrec;
		$ct = preg_match("/0 @(.*)@ (.*)/", $this->gedrec, $match);
		if ($ct>0) {
			$this->xref = trim($match[1]);
			$this->type = trim($match[2]);
		}
	}

	/**
	 * Static function used to get an instance of an object
	 * @param string $pid	the ID of the object to retrieve
	 * @return GedcomRecord
	 */
	function &getInstance($pid, $simple=true) {
		global $indilist, $famlist, $sourcelist, $repolist, $otherlist, $GEDCOM, $GEDCOMS, $pgv_changes;

		//-- first check for the object in the cache
		if (isset($indilist[$pid]) && $indilist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($indilist[$pid]['object'])) return $indilist[$pid]['object'];
		}
		if (isset($famlist[$pid]) && $famlist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($famlist[$pid]['object'])) return $famlist[$pid]['object'];
		}
		if (isset($sourcelist[$pid]) && $sourcelist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($sourcelist[$pid]['object'])) return $sourcelist[$pid]['object'];
		}
		if (isset($repolist[$pid]) && $repolist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($repolist[$pid]['object'])) return $repolist[$pid]['object'];
		}
		if (isset($objectlist[$pid]) && $objectlist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($objectlist[$pid]['object'])) return $objectlist[$pid]['object'];
		}
		if (isset($otherlist[$pid]) && $otherlist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($otherlist[$pid]['object'])) return $otherlist[$pid]['object'];
		}

		//-- look for the gedcom record
		$indirec = find_gedcom_record($pid);
		if (empty($indirec)) {
			$ct = preg_match("/(\w+):(.+)/", $pid, $match);
			//-- check if it is a remote object
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				require_once 'includes/serviceclient_class.php';
				$service = ServiceClient::getInstance($servid);
				//-- the INDI will be replaced with the type from the remote record
				$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$pid."@ INDI\r\n1 RFN ".$pid, false);
				$indirec = $newrec;
			}
		}
		//-- check if it is a new object not yet in the database
		if (empty($indirec)) {
			if (PGV_USER_CAN_EDIT && isset($pgv_changes[$pid."_".$GEDCOM])) {
				$indirec = find_updated_record($pid);
				$fromfile = true;
			}
		}
		if (empty($indirec)) return null;

		$ct = preg_match("/0 @.*@ (\w*)/", $indirec, $match);
		if ($ct>0) {
			$type = trim($match[1]);
			if ($type=="INDI") {
				$record = new Person($indirec, $simple);
				if (!empty($fromfile)) $record->setChanged(true);
				$indilist[$pid]['object'] = &$record;
				return $record;
			}
			else if ($type=="FAM") {
				$record = new Family($indirec, $simple);
				if (!empty($fromfile)) $record->setChanged(true);
				$famlist[$pid]['object'] = &$record;
				return $record;
			}
			else if ($type=="SOUR") {
				$record = new Source($indirec, $simple);
				if (!empty($fromfile)) $record->setChanged(true);
				$sourcelist[$pid]['object'] = &$record;
				return $record;
			}
			else if ($type=="REPO") {
				$record = new Repository($indirec, $simple);
				if (!empty($fromfile)) $record->setChanged(true);
				$repolist[$pid]['object'] = &$record;
				return $record;
			}
			else if ($type=="OBJE") {
				$record = new Media($indirec, $simple);
				if (!empty($fromfile)) $record->setChanged(true);
				$objectlist[$pid]['object'] = &$record;
				return $record;
			}
			else {
				$record = new GedcomRecord($indirec, $simple);
				if (!empty($fromfile)) $record->setChanged(true);
				$otherlist[$pid]['object'] = &$record;
				return $record;
			}
		}
		return null;
	}

	/**
	 * get the xref
	 * @return string	returns the person ID
	 */
	function getXref() {
		return $this->xref;
	}
	/**
	 * get the object type
	 * @return string	returns the type of this object "INDI","FAM", etc.
	 */
	function getType() {
		return $this->type;
	}
	/**
	 * get gedcom record
	 */
	function getGedcomRecord() {
		return $this->gedrec;
	}
	/**
	 * set gedcom record
	 */
	function setGedcomRecord($gcRec) {
		$this->gedrec = $gcRec;
	}
	/**
	 * set if this is a changed record from the gedcom file
	 * @param boolean $changed
	 */
	function setChanged($changed) {
		$this->changed = $changed;
	}
	/**
	 * get if this is a changed record from the gedcom file
	 * @return boolean
	 */
	function getChanged() {
		return $this->changed;
	}

	/**
	 * is this person from another server
	 * @return boolean 	return true if this person was linked from another server
	 */
	function isRemote() {
		if (is_null($this->rfn)) $this->rfn = get_gedcom_value("RFN", 1, $this->gedrec);
		if (empty($this->rfn) || $this->xref!=$this->rfn) return false;
		
		$parts = preg_split("/:/", $this->rfn);
		if (count($parts)==2) {
			return true;
		}
		return false;
	}

	/**
	 * check if this object is equal to the given object
	 * basically just checks if the IDs are the same
	 * @param GedcomRecord $obj
	 */
	function equals(&$obj) {
		if (is_null($obj)) return false;
		if ($this->xref==$obj->getXref()) return true;
		return false;
	}

	/**
	 * get the URL to link to this record
	 * @string a url that can be used to link to this person
	 */
	function getLinkUrl($link='gedcomrecord.php?pid=') {
		global $GEDCOM;

		$url = $link.urlencode($this->getXref()).'&amp;ged='.urlencode($GEDCOM);
		if ($this->isRemote()) {
			list($servid, $aliaid)=explode(':', $this->rfn);
			if ($aliaid && $servid) {
				require_once 'includes/serviceclient_class.php';
				$serviceClient = ServiceClient::getInstance($servid);
				if ($serviceClient) {
					$surl = $serviceClient->getURL();
					$url = $link.urlencode($aliaid);
					if ($serviceClient->getType()=='remote') {
						if (!empty($surl)) {
							$url = dirname($surl).'/'.$url;
						}
					} else {
						$url = $surl.$url;
					}
					$gedcom = $serviceClient->getGedfile();
					if ($gedcom) {
						$url.='&amp;ged='.urlencode($gedcom);
					}
				}
			}
		}
		return $url;
	}
	
	/**
	 * Get the title that should be used in the link
	 * @return string
	 */
	function getLinkTitle() {
		global $GEDCOM, $GEDCOMS;

		$title = $GEDCOMS[$GEDCOM]['title'];
		if ($this->isRemote()) {
			$parts = preg_split("/:/", $this->rfn);
			if (count($parts)==2) {
				$servid = $parts[0];
				$aliaid = $parts[1];
				if (!empty($servid)&&!empty($aliaid)) {
					require_once 'includes/serviceclient_class.php';
					$serviceClient = ServiceClient::getInstance($servid);
					if (!empty($serviceClient)) {
						$title = $serviceClient->getTitle();
					}
				}
			}
		}
		return $title;
	}

	// Get an HTML link to this object, for use in sortable lists.
	function getXrefLink($target="") {
		global $SEARCH_SPIDER;
		if (empty($SEARCH_SPIDER)) {
			if ($target) $target = "target=\"".$target."\"";
			return "<a href=\"".$this->getLinkUrl()."#content\" name=\"".preg_replace('/\D/','',$this->getXref())."\" $target>".$this->getXref()."</a>";
		}
		else
			return $this->getXref();
	}

	/**
	 * return an absolute url for linking to this record from another site
	 *
	 */
	function getAbsoluteLinkUrl() {
		global $SERVER_URL;
		return $SERVER_URL . $this->getLinkUrl();
	}

	/**
	 * Undo the latest change to this gedcom record
	 */
	function undoChange() {
		global $GEDCOM, $pgv_changes;
		require_once('includes/functions_edit.php');
		if (!PGV_USER_CAN_ACCEPT) return false;
		$cid = $this->xref."_".$GEDCOM;
		if (!isset($pgv_changes[$cid])) return false;
		$index = count($pgv_changes[$cid])-1;
		if (undo_change($cid, $index)) return true;
		return false;
	}

	/**
	 * check if this record has been marked for deletion
	 * @return boolean
	 */
	function isMarkedDeleted() {
		global $pgv_changes, $GEDCOM;

		if (!PGV_USER_CAN_EDIT) return false;
		if (isset($pgv_changes[$this->xref."_".$GEDCOM])) {
			$change = end($pgv_changes[$this->xref."_".$GEDCOM]);
			if ($change['type']=='delete') return true;
		}

		return false;
	}

	/**
	 * can the details of this record be shown
	 * This method should be overridden in sub classes
	 * @return boolean
	 */
	function canDisplayDetails() {
		if (is_null($this->disp)) $this->disp = displayDetailsById($this->xref, $this->type);
		return $this->disp;
	}
	
	/**
	 * get the URL to link to a place
	 * @string a url that can be used to link to placelist
	 */
	function getPlaceUrl($gedcom_place) {
		global $GEDCOM;
		$exp = explode(",", $gedcom_place);
		$level = count($exp);
		$url = "placelist.php?action=show&amp;level=".$level;
		for ($i=0; $i<$level; $i++) $url .= "&amp;parent[".$i."]=".urlencode(trim($exp[$level-$i-1]));
		$url .= "&amp;ged=".$GEDCOM;
		return $url;
	}

	/**
	 * get the first part of a place record
	 * @string a url that can be used to link to placelist
	 */
	function getPlaceShort($gedcom_place) {
		global $GEDCOM;
		$gedcom_place = trim($gedcom_place, " ,");
		$exp = explode(",", $gedcom_place);
		return trim($exp[0]);
	}

	/**
	 * get the sortable name
	 * This method should be overridden in child sub-classes
	 * (no class yet for NOTE record)
	 * @return string
	 */
	function getSortableName() {
		return $this->type." ".$this->xref;
	}
	
	/**
	 * get the name
	 * This method should overridden in child sub-classes
	 * @return string
	 */
	function getName() {
		return get_gedcom_value("NAME", 1, $this->gedrec);
	}
	
	/**
	 * get the additional name
	 * This method should overridden in child sub-classes
	 * @return string
	 */
	function getAddName() {
		return "";
	}
	
	/**
	 * Get the first Event for the given Fact type
	 *
	 * @param string $fact
	 * @return Event
	 */
	function &getFactByType($factType) {
		$this->parseFacts();
		foreach($this->facts as $f=>$fact) {
			if ($fact->getTag()==$factType) return $fact;
		}
	}

	/**
	 * Return an array of events that match the given types
	 *
	 * @param mixed $factTypes  may be a single string or an array of strings
	 * @return Event
	 */
	function getAllFactsByType($factTypes) {
		$this->parseFacts();
		if (is_string($factTypes)) $factTypes = array($factTypes);
		$facts = array();
		foreach($this->facts as $f=>$fact) {
			if (in_array($fact->getTag(), $factType)) $facts[] = $fact;
		}
		return $facts;
	}

	/**
	 * returns an array of all of the facts
	 * @return Array
	 */
	function getFacts() {
		$this->parseFacts();
		return $this->facts;
	}

	/**
	 * Get the CHAN event for this record
	 *
	 * @return Event
	 */
	function getChangeEvent() {
		if (is_null($this->changeEvent)) {
			$this->changeEvent = $this->getFactByType("CHAN");
		}
		return $this->changeEvent;
	}

	/**
	 * Parse the facts from the record
	 */
	function parseFacts() {
		//-- only run this function once
		if (!is_null($this->facts)) return;
		$this->facts=array();
		//-- don't run this function if privacy does not allow viewing of details
		if (!$this->canDisplayDetails()) return;
		//-- must trim the record here because the record is trimmed in edit and it could mess up line numbers
		$this->gedrec = trim($this->gedrec);
		//-- find all the fact information
		$indilines = preg_split("/[\r\n]+/", $this->gedrec);   // -- find the number of lines in the individuals record
		$lct = count($indilines);
		$factrec = "";	 // -- complete fact record
		$line = "";   // -- temporary line buffer
		$linenum=1;
		for($i=1; $i<=$lct; $i++) {
			if ($i<$lct) $line = $indilines[$i];
			else $line=" ";
			if (empty($line)) $line=" ";
			if ($i==$lct||$line{0}==1) {
				if ($i>1){
					$event = new Event($factrec, $linenum);
					$event->setParentObject($this);
					$this->facts[] = $event;
				}
				$factrec = $line;
				$linenum = $i;
			}
			else $factrec .= "\n".$line;
		}
	}
	
	/**
	 * Merge the facts from another GedcomRecord object into this object
	 * for generating a diff view
	 * @param GedcomRecord $diff	the record to compare facts with
	 */
	function diffMerge(&$diff) {
		if (is_null($diff)) return;
		$this->parseFacts();
		$diff->parseFacts();

		//-- update old facts
		foreach($this->facts as $key=>$event) {
			$found = false;
			foreach($diff->facts as $indexval => $newevent) {
				$newfact = $newevent->getGedComRecord();
				$newfact=preg_replace("/\\\/", "/", $newfact);
				if (trim($newfact)==trim($event->getGedcomRecord())) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$this->facts[$key]->gedComRecord.="\r\nPGV_OLD\r\n";
			}
		}
		//-- look for new facts
		foreach($diff->facts as $key=>$newevent) {
			$found = false;
			foreach($this->facts as $indexval => $event) {
				$newfact = $newevent->getGedcomRecord();
				$newfact=preg_replace("/\\\/", "/", $newfact);
				if (trim($newfact)==trim($event->getGedcomRecord())) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$newevent->gedComRecord.="\nPGV_NEW\n";
				$this->facts[]=$newevent;
			}
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// Get the last-change timestamp for this record - optionally wrapped in a
	// link to ourself.
	//////////////////////////////////////////////////////////////////////////////
	function LastChangeTimestamp($add_url) {
		global $DATE_FORMAT, $TIME_FORMAT;

		$chan = $this->getChangeEvent();

		if (is_null($chan))	return '&nbsp;';

		$d = $chan->getDate();
		if (preg_match('/^(\d\d):(\d\d):(\d\d)$/', get_gedcom_value('DATE:TIME', 2, $chan->getGedComRecord(), '', false).':00', $match)) {
			$t=mktime($match[1], $match[2], $match[3]);
			$sort=$d->MinJD().$match[1].$match[2].$match[3];
		} else {
			$t=mktime(0,0,0);
			$sort=$d->MinJD().'000000';
		}
		$text=strip_tags($d->Display(false, "{$DATE_FORMAT} -", array()).date(" {$TIME_FORMAT}", $t));
		if ($add_url)
			$text='<a name="'.$sort.'" href="'.$this->getLinkUrl().'">'.$text.'</a>';
		return $text;
	}

	//////////////////////////////////////////////////////////////////////////////
	// Get the last-change user for this record
	//////////////////////////////////////////////////////////////////////////////
	function LastchangeUser() {
		$chan = $this->getChangeEvent();

		if (is_null($chan))	return '&nbsp;';
		
		$chan_user = $chan->getValue("_PGVU");
		if (empty($chan_user)) return '&nbsp;';
		
		return $chan_user;
	}
}
?>
