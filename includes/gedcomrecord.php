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
class GedcomRecord {
	var $gedrec = "";
	var $xref = "";
	var $ged_id=null; // only set if this record comes from a file
	var $type = "";
	var $changed = false;
	var $rfn = null;
	var $disp = null;

	// Cached results from various functions.
	// These should become private when we move to PHP5.  Do not use them from outside this class.
	var $_getAllNames=null;
	var $_getPrimaryName=null;

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
		$this->gedrec = privatize_gedcom($gedrec);
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
		global $gedcom_record_cache, $GEDCOM, $pgv_changes;

		$ged_id=get_id_from_gedcom($GEDCOM);
		// Check the cache first
		if (isset($gedcom_record_cache[$pid][$ged_id])) {
			return $gedcom_record_cache[$pid][$ged_id];
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

		if (preg_match("/^0 +@.+@ +(\w+)/", $indirec, $match)) {
			switch ($match[1]) {
			case 'INDI':
				$record = new Person($indirec, $simple);
				break;
			case 'FAM':
				$record = new Family($indirec, $simple);
				break;
			case 'SOUR':
				$record = new Source($indirec, $simple);
				break;
			case 'REPO':
				$record = new Repository($indirec, $simple);
				break;
			case 'OBJE':
				$record = new Media($indirec, $simple);
				break;
			default:
				$record = new GedcomRecord($indirec, $simple);
				break;
			}
			if (!empty($fromfile)) {
				$record->setChanged(true);
			}
			$record->ged_id=$ged_id;
			// Store the object in the cache
			$gedcom_record_cache[$pid][$ged_id]=&$record;
			return $record;
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
	 * @param GedcomRecord $obj
	 */
	function equals(&$obj) {
		return !is_null($obj) && $this->xref==$obj->getXref();
	}

	/**
	 * get the URL to link to this record
	 * @string a url that can be used to link to this person
	 */
	function getLinkUrl($link='gedcomrecord.php?pid=') {
		$url = $link.urlencode($this->getXref()).'&amp;ged='.urlencode(get_gedcom_from_id($this->ged_id));
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
		$title = get_gedcom_setting($this->ged_id);
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

	// Convert a name record into sortable and listable versions.  This default
	// should be OK for simple record types.  INDI records will need to redefine it.
	function _addName($type, $value, $gedrec) {
		$this->_getAllNames[]=array(
			'type'=>$type,
			'full'=>$value,
			'list'=>$value,
			'sort'=>preg_replace('/(\d+)/e', 'substr("000000000\\1", -10)', $value)
		);
	}

	// Get all the names of a record, including ROMN, FONE and _HEB alternatives.
	// Records without a name (e.g. FAM) will need to redefine this function.
	//
	// Parameters: the level 1 fact containing the name.
	// Return value: an array of name structures, each containing
	// ['type'] = the gedcom fact, e.g. NAME, TITL, FONE, _HEB, etc.
	// ['full'] = the name as specified in the record, e.g. "Vincent van Gogh" or "John Unknown"
	// ['list'] = a version of the name as might appear in lists, e.g. "van Gogh, Vincent" or "Unknown, John"
	// ['sort'] = a sortable version of the name (not for display), e.g. "Gogh, Vincent" or "@N.N., John"
	function getAllNames($fact) {
		if (is_null($this->_getAllNames)) {
			$this->_getAllNames=array();
			if (preg_match_all('/^1 ('.$fact.')\s+([^\r\n]+)(([\r\n]+[2-9][^\r\n]+)*)/m', $this->gedrec, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					$this->_addName($match[1], $match[2], $match[0]);
				}
				if ($match[3] && preg_match_all('/^2 (ROMN|FONE|_\w+) +([^\r\n]+)/m', $match[3], $submatches, PREG_SET_ORDER)) {
					foreach ($submatches as $submatch) {
						$this->_addName($submatch[1], $submatch[2], $submatch[0]);
					}
				}
			}
			if (empty($this->_getAllNames)) {
				$this->_addName($this->getType(), $this->getXref(), null);
			}
		}
		return $this->_getAllNames;
	}

	// Which of the (possibly several) names of this record is the primary one.
	function getPrimaryName() {
		if (is_null($this->_getPrimaryName)) {
			// Generally, the first name is the primary one....
			$this->getPrimaryName=0;
			// ....except on hebrew pages, when we want the first _HEB one.
			global $LANGUAGE;
			if ($LANGUAGE=='hebrew') {
				foreach ($this->getAllNames() as $n=>$name) {
					if ($name['type']=='_HEB') {
						$this->_getPrimaryName=$n;
						break;
					}
				}
			}
		}
		return $this->_getPrimaryName;
	}

	// Static helper function to sort an array of objects by name
	function CompareName($x, $y) {
		return strcmp($x->getSortName(), $y->getSortName('sort'));
	}

	// Get the three variants of the name
	function getFullName() {
		$tmp=$this->getAllNames();
		return $tmp[getPrimaryName()]['full'];
	}
	function getSortName() {
		$tmp=$this->getAllNames();
		return $tmp[getPrimaryName()]['sort'];
	}
	function getListName() {
		$tmp=$this->getAllNames();
		return $tmp[getPrimaryName()]['list'];
	}

	// Get all attributes (e.g. DATE or PLAC) from an event (e.g. BIRT or MARR).
	// This is used to display multiple events on the individual/family lists.
	// Multiple events can exist because of uncertainty in dates, dates in different
	// calendars, place-names in both latin and hebrew character sets, etc.
	// It also allows us to combine dates/places from different events in the summaries.
	function getAllEventDates($event) {
		$dates=array();
		foreach ($this->getAllEvents($event) as $event_rec) {
			if (preg_match_all("/^2 DATE +(.+)/m", $event_rec, $ged_dates)) {
				foreach ($ged_dates[1] as $ged_date) {
					$dates[]=new GedcomDate($ged_date);
				}
			}
		}
		return $dates;
	}
	function getAllEventPlaces($event) {
		$places=array();
		foreach ($this->getAllEvents($event) as $event_rec) {
			if (preg_match_all("/^(?:2 PLAC|3 (?:ROMN|FONE|_HEB)) +(.+)/m", $event_rec, $ged_places)) {
				foreach ($ged_places[1] as $ged_place) {
					$places[]=$ged_place;
				}
			}
		}
		return $places;
	}

	// Get all the events of a type.
	function getAllEvents($event) {
		$event_recs=array();
		if (ShowFactDetails($event, $this->xref)) {
			if (preg_match_all("/^1 *{$event}\b.*(?:[\r\n]+[2-9].*)*/m", $this->gedrec, $events)) {
				foreach ($events[0] as $event_rec) {
					if (!FactViewRestricted($this->xref, $event_rec)) {
						$event_recs[]=$event_rec;
					}
				}
			}	
			// Some people use "1 EVEN/2 TYPE BIRT" instead of "1 BIRT".
			// Find them and convert them back to the proper format.
			if (preg_match_all("/^1 (?:FACT|EVEN)\b[^\r\n]*((?:[\r\n]+[2-9][^\r\n]*)*)(?:[\r\n]+2 TYPE {$event})((?:[\r\n]+[2-9][^\r\n]*)*)/m", $this->gedrec, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					if (!FactViewRestricted($this->xref, $match[0])) {
						$event_recs[]='1 '.$event.$match[1].$match[2];
					}
				}
			}	
		}
		return $event_recs;
	}

	//////////////////////////////////////////////////////////////////////////////
	// Get the last-change timestamp for this record - optionally wrapped in a
	// link to ourself.
	//////////////////////////////////////////////////////////////////////////////
	function LastChangeTimestamp($add_url) {
		global $DATE_FORMAT, $TIME_FORMAT;
		$chan_rec=get_sub_record(1, '1 CHAN', $this->gedrec);
		if (empty($chan_rec))
			return '&nbsp;';
		$d=new GedcomDate(get_gedcom_value('DATE', 2, $chan_rec, '', false));
		if (preg_match('/^(\d\d):(\d\d):(\d\d)/', get_gedcom_value('DATE:TIME', 2, $chan_rec, '', false).':00', $match)) {
			$t=mktime($match[1], $match[2], $match[3]);
			$sort=$d->MinJD().$match[1].$match[2].$match[3];
			$text=strip_tags($d->Display(false, "{$DATE_FORMAT} -", array()).date(" {$TIME_FORMAT}", $t));
		} else {
			$sort=$d->MinJD().'000000';
			$text=strip_tags($d->Display(false, "{$DATE_FORMAT}", array()));
		}
		if ($add_url)
			$text='<a name="'.$sort.'" href="'.$this->getLinkUrl().'">'.$text.'</a>';
		return $text;
	}

	//////////////////////////////////////////////////////////////////////////////
	// Get the last-change user for this record
	//////////////////////////////////////////////////////////////////////////////
	function LastchangeUser() {
		$chan_rec=get_sub_record(1, '1 CHAN', $this->gedrec);
		if (empty($chan_rec))
			return '&nbsp;';
		$chan_user=get_gedcom_value("_PGVU", 2, $chan_rec, '', false);
		if (empty($chan_user))
			return '&nbsp;';
		return $chan_user;
	}
}
?>
