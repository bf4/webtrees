<?php
/**
 * Base class for all gedcom records
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
	var $ged_id=null; // only set if this record comes from a file
	var $type = "";
	var $changed = false;
	var $rfn = null;
	var $facts = null;
	var $changeEvent = null;
	var $disp = null;

	// Cached results from various functions.
	// These should become private when we move to PHP5.  Do not use them from outside this class.
	var $_getAllNames=null;
	var $_getPrimaryName=null;
	var $_getSecondaryName=null;

	/**
	 * constructor for this class
	 */
	function GedcomRecord($gedrec, $simple=false) {
		if (empty($gedrec)) return;

		$this->ged_id=PGV_GED_ID;

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
		$url = $link.$this->getXref().'&ged='.get_gedcom_from_id($this->ged_id);
		if ($this->isRemote()) {
			list($servid, $aliaid)=explode(':', $this->rfn);
			if ($aliaid && $servid) {
				require_once 'includes/serviceclient_class.php';
				$serviceClient = ServiceClient::getInstance($servid);
				if ($serviceClient) {
					$surl = $serviceClient->getURL();
					$url = $link.$aliaid;
					if ($serviceClient->getType()=='remote') {
						if (!empty($surl)) {
							$url = dirname($surl).'/'.$url;
						}
					} else {
						$url = $surl.$url;
					}
					$gedcom = $serviceClient->getGedfile();
					if ($gedcom) {
						$url .= "&ged={$gedcom}";
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
		$title = get_gedcom_setting($this->ged_id, 'title');
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
			return "<a href=\"".encode_url($this->getLinkUrl())."#content\" name=\"".preg_replace('/\D/','',$this->getXref())."\" $target>".$this->getXref()."</a>";
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
		$url = "placelist.php?action=show&level=".$level;
		for ($i=0; $i<$level; $i++) {
			$url .= "&parent[".$i."]=".trim($exp[$level-$i-1]);
		}
		$url .= "&ged=".$GEDCOM;
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
	 * get the name
	 * This method should overridden in child sub-classes
	 * @return string
	 */
	function getName() {
		return get_gedcom_value("NAME", 1, $this->gedrec);
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
	function getAllNames($fact='!', $level=1) {
		global $pgv_lang, $WORD_WRAPPED_NOTES;

		if (is_null($this->_getAllNames)) {
			$sublevel=$level+1;
			$subsublevel=$sublevel+1;
			if ($this->canDisplayName()) {
				$this->_getAllNames=array();
				$gedrec=preg_replace('/[\r\n]+\d CONC /', $WORD_WRAPPED_NOTES ? ' ' : '', $this->gedrec);
				if (preg_match_all('/^'.$level.' ('.$fact.') *([^\r\n]*)(([\r\n]+['.$sublevel.'-9][^\r\n]+)*)/m', $gedrec, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$this->_addName($match[1], $match[2] ? $match[2] : $this->getFallBackName(), $match[0]);
						if ($match[3] && preg_match_all('/^'.$sublevel.' (ROMN|FONE|_\w+) *([^\r\n]*)(([\r\n]+['.$subsublevel.'-9][^\r\n]+)*)/m', $match[3], $submatches, PREG_SET_ORDER)) {
							foreach ($submatches as $submatch) {
								$this->_addName($submatch[1], $submatch[2] ? $submatch[2] : $this->getFallBackName(), $submatch[0]);
							}
						}
					}
				}
				if (empty($this->_getAllNames)) {
					$this->_addName($this->getType(), $this->getFallBackName(), null);
				}
			} else {
				$this->_getAllNames[]=array('type'=>$fact, 'full'=>$pgv_lang['private'], 'list'=>$pgv_lang['private'], 'sort'=>'@,@');
			}
		}
		return $this->_getAllNames;
	}

	// If this object has no name, what do we call it?
	function getFallBackName() {
		return $this->getXref();
	}

	// Can we display the name of this object?
	function canDisplayName() {
		return true;
	}

	// Which of the (possibly several) names of this record is the primary one.
	function getPrimaryName() {
		if (is_null($this->_getPrimaryName)) {
			// Generally, the first name is the primary one....
			$this->_getPrimaryName=0;
			// ....except when the language/name use different character sets
			global $LANGUAGE;
			switch ($LANGUAGE) {
			case 'greek':
			case 'russian':
			case 'hebrew':
			case 'arabic':
			case 'vietnamese':
			case 'chinese':
				foreach ($this->getAllNames() as $n=>$name) {
					if ($name['type']!='_MARNM' && whatLanguage($name['full'])==$LANGUAGE) {
						$this->_getPrimaryName=$n;
						break;
					}
				}
				break;
			default:
				foreach ($this->getAllNames() as $n=>$name) {
					if (whatLanguage($name['full'])=='other') {
						$this->_getPrimaryName=$n;
						break;
					}
				}
				break;
			}
		}
		return $this->_getPrimaryName;
	}

	// Which of the (possibly several) names of this record is the secondary one.
	function getSecondaryName() {
		if (is_null($this->_getSecondaryName)) {
			// Generally, the primary and secondary names are the same
			$this->_getSecondaryName=$this->getPrimaryName();
			// ....except when there are names with different character sets
			$all_names=$this->getAllNames();
			if (count($all_names)>1) {
				$primary_language=whatLanguage($all_names[$this->getPrimaryName()]['full']);
				foreach ($all_names as $n=>$name) {
					if ($n!=$this->getPrimaryName() && $name['type']!='_MARNM' && whatLanguage($name['full'])!=$primary_language) {
						$this->_getSecondaryName=$n;
						break;
					}
				}
			}
		}
		return $this->_getSecondaryName;
	}

	// Static helper function to sort an array of objects by name
	function CompareName($x, $y) {
		return strcmp($x->getSortName(), $y->getSortName('sort'));
	}

	// Get the three variants of the name
	function getFullName() {
		$tmp=$this->getAllNames();
		return $tmp[$this->getPrimaryName()]['full'];
	}
	function getSortName() {
		$tmp=$this->getAllNames();
		return $tmp[$this->getPrimaryName()]['sort'];
	}
	function getListName() {
		$tmp=$this->getAllNames();
		return $tmp[$this->getPrimaryName()]['list'];
	}
	// Get the fullname in an alternative character set
	function getAddName() {
		if ($this->getPrimaryName() != $this->getSecondaryName()) {
			$all_names=$this->getAllNames();
			return $all_names[$this->getSecondaryName()]['full'];
		} else {
			return null;
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// Format this object for display in a list
	// If $find is set, then we are displaying items from a selection list.
	//////////////////////////////////////////////////////////////////////////////
	function format_list($tag='li', $find=false) {
		global $SHOW_ID_NUMBERS;

		$name=$this->getFullName();
		$dir=begRTLText($name) ? 'rtl' : 'ltr';
		if ($find) {
			$href='javascript:;" onclick="pasteid(\''.$this->getXref().'\'); return false;';
		} else {
			$href=encode_url($this->getLinkUrl());
		}
		$html='<a href="'.$href.'" class="list_item"><b>'.PrintReady($name).'</b>';
		if ($SHOW_ID_NUMBERS) {
			$html.=' '.PGV_LPARENS.$this->getXref().PGV_RPARENS;
		}
		$html.=$this->format_list_details();
		$html='<'.$tag.' class="'.$dir.'" dir="'.$dir.'">'.$html.'</a></'.$tag.'>';
		return $html;
	}
	// This function should be redefined in derived classes to show any major
	// identifying characteristics of this record.
	function format_list_details() {
		return '';
	}

	// Extract/format the first fact from a list of facts.
	function format_first_major_fact($facts, $style) {
		foreach ($this->getAllFactsByType(explode('|', $facts)) as $event) {
			// Only display if it has a date or place (or both)
			if ($event->getDate() || $event->getPlace()) {
				switch ($style) {
				case 1:
					return '<br /><i>'.$event->getLabel().' '.format_fact_date($event).format_fact_place($event).'</i>';
				case 2:
					return '<span class="label">'.$event->getLabel().':</span> <span class="field">'.format_fact_date($event).format_fact_place($event).'</span><br />';
				}
			}
		}
		return '';
	}

	// Get all attributes (e.g. DATE or PLAC) from an event (e.g. BIRT or MARR).
	// This is used to display multiple events on the individual/family lists.
	// Multiple events can exist because of uncertainty in dates, dates in different
	// calendars, place-names in both latin and hebrew character sets, etc.
	// It also allows us to combine dates/places from different events in the summaries.
	function getAllEventDates($event) {
		$dates=array();
		foreach ($this->getAllFactsByType($event) as $event) {
			$dates[]=$event->getDate();
		}
		return $dates;
	}
	function getAllEventPlaces($event) {
		$places=array();
		foreach ($this->getAllFactsByType($event) as $event) {
			if (preg_match_all("/^(?:2 PLAC|3 (?:ROMN|FONE|_HEB)) +(.+)/m", $event->getGedComRecord(), $ged_places)) {
				foreach ($ged_places[1] as $ged_place) {
					$places[]=$ged_place;
				}
			}
		}
		return $places;
	}

	/**
	 * Get the first Event for the given Fact type
	 *
	 * @param string $fact
	 * @return Event
	 */
	function &getFactByType($factType) {
		$this->parseFacts();
		if (empty($this->facts)) return null;
		foreach($this->facts as $f=>$fact) {
			if ($fact->getTag()==$factType || $fact->getType()==$factType) return $fact;
		}
		return null;
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
		if (empty($this->facts)) return $facts;
		foreach($this->facts as $f=>$fact) {
			if (in_array($fact->getTag(), $factTypes) || in_array($fact->getType(), $factTypes)) $facts[] = $fact;
		}
		return $facts;
	}

	/**
	 * returns an array of all of the facts
	 * @return Array
	 */
	function getFacts($nfacts=NULL) {
		$this->parseFacts($nfacts);
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
	function parseFacts($nfacts=NULL) {
		//-- only run this function once
		if (!is_null($this->facts) && is_array($this->facts)) return;
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
					$fact = $event->getTag();
					if ($nfacts==NULL || !in_array($fact, $nfacts)) {
						$event->setParentObject($this);
						$this->facts[] = $event;
					}
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
			$text=strip_tags($d->Display(false, "{$DATE_FORMAT} -", array()).date(" {$TIME_FORMAT}", $t));
		} else {
			$sort=$d->MinJD().'000000';
			$text=strip_tags($d->Display(false, "{$DATE_FORMAT}", array()));
		}
		if ($add_url)
			$text='<a name="'.$sort.'" href="'.encode_url($this->getLinkUrl()).'">'.$text.'</a>';
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
