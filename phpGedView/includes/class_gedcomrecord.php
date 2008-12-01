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

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_GEDCOMRECORD_PHP', '');

require_once 'includes/class_person.php';
require_once 'includes/class_family.php';
require_once 'includes/class_source.php';
require_once 'includes/class_repository.php';
require_once 'includes/class_media.php';
require_once 'includes/class_event.php';
require_once 'includes/class_serviceclient.php';

class GedcomRecord {
	var $gedrec     =null;  // Raw gedcom text (privatised)
	var $xref       =null;  // The record identifier
	var $ged_id     =null;  // The gedcom file, only set if this record comes from the database
	var $type       =null;  // INDI, FAM, etc.
	var $changed    =false; // Is this a new record from pgv_changes[]
	var $rfn        =null;
	var $facts      =null;
	var $changeEvent=null;
	var $disp       =true;  // Can we display details of this object
	var $dispname   =true;  // Can we display the name of this object

	// Cached results from various functions.
	// These should become private when we move to PHP5.  Do not use them from outside this class.
	var $_getAllNames=null;
	var $_getPrimaryName=null;
	var $_getSecondaryName=null;

	// Create a GedcomRecord object from either raw GEDCOM data or a database row
	function GedcomRecord($data, $simple=false) {
		if (is_array($data)) {
			// Construct from a row from the database
			$this->xref  =$data['xref'];
			$this->type  =$data['type'];
			$this->ged_id=$data['ged_id'];
			$this->gedrec=$data['gedrec'];
		} else {
			// Construct from raw GEDCOM data
			$this->gedrec=$data;
			if (preg_match('/^0 @(.+)@ (\w+)/', $data, $match)) {
				$this->xref=$match[1];
				$this->type=$match[2];
			}
		}

		//-- lookup the record from another gedcom
		$remoterfn = get_gedcom_value("RFN", 1, $this->gedrec);
		if (!empty($remoterfn)) {
			$parts = explode(':', $remoterfn);
			if (count($parts)==2) {
				$servid = $parts[0];
				$aliaid = $parts[1];
				if (!empty($servid)&&!empty($aliaid)) {
					$serviceClient = ServiceClient::getInstance($servid);
					if (!is_null($serviceClient)) {
						if (!$simple || $serviceClient->type=='local') {
							$this->gedrec = $serviceClient->mergeGedcomRecord($aliaid, $this->gedrec, true);
						}
					}
				}
			}
		}

		//-- set the gedcom record a privatized version
		$this->gedrec = privatize_gedcom($this->gedrec);
		if ($this->xref && $this->type) {
			$this->disp=displayDetailsById($this->xref, $this->type);
		}
	}

	// Get an instance of a GedcomRecord.  We either specify
	// an XREF (in the current gedcom), or we can provide a row
	// from the database (if we anticipate the record hasn't
	// been fetched previously).
	static function &getInstance($data, $simple=true) {
		global $gedcom_record_cache, $GEDCOM, $pgv_changes;

		if (is_array($data)) {
			$ged_id=$data['ged_id'];
			$pid   =$data['xref'];
		} else {
			$ged_id=get_id_from_gedcom($GEDCOM);
			$pid   =$data;	
		}

		// Check the cache first
		if (isset($gedcom_record_cache[$pid][$ged_id])) {
			return $gedcom_record_cache[$pid][$ged_id];
		}

		// Look for the record in the database
		if (!is_array($data)) {
			$data=fetch_gedcom_record($pid, $ged_id);
	
			// If we didn't find the record in the database, it may be remote
			if (!$data && strpos($pid, ':')) {
				list($servid, $remoteid)=explode(':', $pid);
				$service=ServiceClient::getInstance($servid);
				if ($service) {
					// TYPE will be replaced with the type from the remote record
					$data=$service->mergeGedcomRecord($remoteid, "0 @{$pid}@ TYPE\n1 RFN {$pid}", false);
				}
			}	

			// If we didn't find the record in the database, it may be new/pending
			if (!$data && PGV_USER_CAN_EDIT && isset($pgv_changes[$pid.'_'.$GEDCOM])) {
				$data=find_updated_record($pid);
				$fromfile=true;
			}

			// If we still didn't find it, it doesn't exist
			if (!$data) {
				return null;
			}
		}

		// Create the object
		if (is_array($data)) {
			$type=$data['type'];
		} elseif (preg_match("/^0 +@.+@ +(\w+)/", $data, $match)) {
			$type=$match[1];
		} else {
			$type='';
		}
		switch ($type) {
		case 'INDI':
			$object=new Person($data, $simple);
			break;
		case 'FAM':
			$object=new Family($data, $simple);
			break;
		case 'SOUR':
			$object=new Source($data, $simple);
			break;
		case 'REPO':
			$object=new Repository($data, $simple);
			break;
		case 'OBJE':
			$object=new Media($data, $simple);
			break;
		default:
			$object=new GedcomRecord($data, $simple);
			break;
		}
		if (!empty($fromfile)) {
			$object->setChanged(true);
		}

		// Store it in the cache
		$gedcom_record_cache[$object->xref][$object->ged_id]=&$object;
		return $object;
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

		$parts = explode(':', $this->rfn);
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
			$parts = explode(':', $this->rfn);
			if (count($parts)==2) {
				$servid = $parts[0];
				$aliaid = $parts[1];
				if (!empty($servid)&&!empty($aliaid)) {
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
		return $SERVER_URL.$this->getLinkUrl();
	}

	/**
	 * Undo the latest change to this gedcom record
	 */
	function undoChange() {
		global $GEDCOM, $pgv_changes;
		require_once 'includes/functions_edit.php';
		if (!PGV_USER_CAN_ACCEPT) {
			return false;
		}
		$cid = $this->xref."_".$GEDCOM;
		if (!isset($pgv_changes[$cid])) {
			return false;
		}
		$index = count($pgv_changes[$cid])-1;
		if (undo_change($cid, $index)) {
			return true;
		}
		return false;
	}

	/**
	 * check if this record has been marked for deletion
	 * @return boolean
	 */
	function isMarkedDeleted() {
		global $pgv_changes, $GEDCOM;

		if (!PGV_USER_CAN_EDIT) {
			return false;
		}
		if (isset($pgv_changes[$this->xref."_".$GEDCOM])) {
			$change = end($pgv_changes[$this->xref."_".$GEDCOM]);
			if ($change['type']=='delete') {
				return true;
			}
		}
		return false;
	}

	/**
	 * Can the details of this record be shown?
	 * @return boolean
	 */
	function canDisplayDetails() {
		return $this->disp;
	}

	/**
	 * Can the name of this record be shown?
	 * @return boolean
	 */
	function canDisplayName() {
		return $this->dispname;
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
				$this->_addName($this->getType(), $pgv_lang['private'], null);
			}
		}
		return $this->_getAllNames;
	}

	// If this object has no name, what do we call it?
	function getFallBackName() {
		return $this->getXref();
	}

	// Which of the (possibly several) names of this record is the primary one.
	function getPrimaryName() {
		if (is_null($this->_getPrimaryName)) {
			// Generally, the first name is the primary one....
			$this->_getPrimaryName=0;
			// ....except when the language/name use different character sets
			if (count($this->getAllNames())>1) {
				global $LANGUAGE;
				switch ($LANGUAGE) {
				case 'greek':
				case 'russian':
				case 'hebrew':
				case 'arabic':
				case 'vietnamese':
				case 'chinese':
					foreach ($this->getAllNames() as $n=>$name) {
						if ($name['type']!='_MARNM' && whatLanguage($name['sort'])==$LANGUAGE) {
							$this->_getPrimaryName=$n;
							break;
						}
					}
					break;
				default:
					foreach ($this->getAllNames() as $n=>$name) {
						if (whatLanguage($name['sort'])=='other') {
							$this->_getPrimaryName=$n;
							break;
						}
					}
					break;
				}
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
				$primary_language=whatLanguage($all_names[$this->getPrimaryName()]['sort']);
				foreach ($all_names as $n=>$name) {
					if ($n!=$this->getPrimaryName() && $name['type']!='_MARNM' && whatLanguage($name['sort'])!=$primary_language) {
						$this->_getSecondaryName=$n;
						break;
					}
				}
			}
		}
		return $this->_getSecondaryName;
	}

	// Allow the choice of primary name to be overidden, e.g. in a search result
	function setPrimaryName($n) {
		$this->_getPrimaryName=$n;
		$this->_getSecondaryName=null;
	}

	// Static helper function to sort an array of objects by name
	function Compare($x, $y) {
		return compareStrings($x->getSortName(), $y->getSortName());
	}

	// Get the three variants of the name
	function getFullName() {
		global $pgv_lang;
		if ($this->canDisplayName()) {
			$tmp=$this->getAllNames();
			return $tmp[$this->getPrimaryName()]['full'];
		} else {
			return $pgv_lang['private'];
		}
	}
	function getSortName() {
		// The sortable name is never displayed, no need to call canDisplayName()
		$tmp=$this->getAllNames();
		return $tmp[$this->getPrimaryName()]['sort'];
	}
	function getListName() {
		global $pgv_lang;
		if ($this->canDisplayName()) {
			$tmp=$this->getAllNames();
			return $tmp[$this->getPrimaryName()]['list'];
		} else {
			return $pgv_lang['private'];
		}
	}
	// Get the fullname in an alternative character set
	function getAddName() {
		if ($this->canDisplayName() && $this->getPrimaryName()!=$this->getSecondaryName()) {
			$all_names=$this->getAllNames();
			return $all_names[$this->getSecondaryName()]['full'];
		} else {
			return null;
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// Format this object for display in a list
	// If $find is set, then we are displaying items from a selection list.
	// $name allows us to use something other than the record name.
	//////////////////////////////////////////////////////////////////////////////
	function format_list($tag='li', $find=false, $name=null) {
		global $SHOW_ID_NUMBERS;

		if (is_null($name)) {
			$name=($tag=='li') ? $this->getListName() : $this->getFullName();
		}
		$dir=begRTLText($name) ? 'rtl' : 'ltr';
		if ($find) {
			$href='javascript:;" onclick="pasteid(\''.$this->getXref().'\', \''.htmlspecialchars(strip_tags($this->getFullName()), ENT_QUOTES).'\'); return false;';
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

	// Count the number of records that link to this one
	function countLinkedIndividuals() {
		return count_linked_indi($this->getXref(), $this->getType(), $this->ged_id);
	}
	function countLinkedFamilies() {
		return count_linked_fam($this->getXref(), $this->getType(), $this->ged_id);
	}
	function countLinkedSources() {
		return count_linked_sour($this->getXref(), $this->getType(), $this->ged_id);
	}
	function countLinkedMedia() {
		return count_linked_obje($this->getXref(), $this->getType(), $this->ged_id);
	}

	// Fetch the records that link to this one
	function fetchLinkedIndividuals() {
		return fetch_linked_indi($this->getXref(), $this->getType(), $this->ged_id);
	}
	function fetchLinkedFamilies() {
		return fetch_linked_fam($this->getXref(), $this->getType(), $this->ged_id);
	}
	function fetchLinkedSources() {
		return fetch_linked_sour($this->getXref(), $this->getType(), $this->ged_id);
	}
	function fetchLinkedMedia() {
		return fetch_linked_obje($this->getXref(), $this->getType(), $this->ged_id);
	}

	// Get all attributes (e.g. DATE or PLAC) from an event (e.g. BIRT or MARR).
	// This is used to display multiple events on the individual/family lists.
	// Multiple events can exist because of uncertainty in dates, dates in different
	// calendars, place-names in both latin and hebrew character sets, etc.
	// It also allows us to combine dates/places from different events in the summaries.
	function getAllEventDates($event) {
		$dates=array();
		foreach ($this->getAllFactsByType($event) as $event) {
			if ($event->getDate()->isOK()) {
				$dates[]=$event->getDate();
			}
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
		if (empty($this->facts)) {
			return null;
		}
		foreach ($this->facts as $f=>$fact) {
			if ($fact->getTag()==$factType || $fact->getType()==$factType) {
				return $fact;
			}
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
		if (is_string($factTypes)) {
			$factTypes = array($factTypes);
		}
		$facts = array();
		foreach ($factTypes as $factType) {
			foreach ($this->facts as $fact) {
				if ($fact->getTag()==$factType) {
					$facts[]=$fact;
				}
			}
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
		if (!is_null($this->facts) && is_array($this->facts)) {
			return;
		}
		$this->facts=array();
		//-- don't run this function if privacy does not allow viewing of details
		if (!$this->canDisplayDetails()) {
			return;
		}
		//-- must trim the record here because the record is trimmed in edit and it could mess up line numbers
		$this->gedrec = trim($this->gedrec);
		//-- find all the fact information
		$indilines = preg_split("/[\r\n]+/", $this->gedrec);   // -- find the number of lines in the individuals record
		$lct = count($indilines);
		$factrec = "";	 // -- complete fact record
		$line = "";   // -- temporary line buffer
		$linenum=1;
		for($i=1; $i<=$lct; $i++) {
			if ($i<$lct) {
				$line = $indilines[$i];
			} else {
				$line=" ";
			}
			if (empty($line)) {
				$line=" ";
			}
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
		if (is_null($diff)) {
			return;
		}
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

	function getEventDate($event) {
	  $srec = $this->getAllEvents($event);
		if (!$srec) {
			return '';
		}
	  $srec = $srec[0];
	  return get_gedcom_value("DATE", 2, $srec);
	  //return get_gedcom_value("PAGE", 3, $srec);
	}
	function getEventSource($event) {
	  $srec = $this->getAllEvents($event);
		if (!$srec) {
			return '';
		}
	  $srec = $srec[0];
	  return get_sub_record("SOUR", 2, $srec);
	  //return get_gedcom_value("PAGE", 3, $srec);
	}
	function getEventSourcePage($event) {
	  return get_gedcom_value("PAGE", 3, getEventSource($event));
	}

	//////////////////////////////////////////////////////////////////////////////
	// Get the last-change timestamp for this record - optionally wrapped in a
	// link to ourself.
	//////////////////////////////////////////////////////////////////////////////
	function LastChangeTimestamp($add_url) {
		global $DATE_FORMAT, $TIME_FORMAT;

		$chan = $this->getChangeEvent();

		if (is_null($chan))	{
			return '&nbsp;';
		}

		$d = $chan->getDate();
		if (preg_match('/^(\d\d):(\d\d):(\d\d)/', get_gedcom_value('DATE:TIME', 2, $chan->getGedComRecord(), '', false).':00', $match)) {
			$t=mktime($match[1], $match[2], $match[3]);
			$sort=$d->MinJD().$match[1].$match[2].$match[3];
			$text=strip_tags($d->Display(false, "{$DATE_FORMAT} -", array()).date(" {$TIME_FORMAT}", $t));
		} else {
			$sort=$d->MinJD().'000000';
			$text=strip_tags($d->Display(false, "{$DATE_FORMAT}", array()));
		}
		if ($add_url) {
			$text='<a name="'.$sort.'" href="'.encode_url($this->getLinkUrl()).'">'.$text.'</a>';
		}
		return $text;
	}

	//////////////////////////////////////////////////////////////////////////////
	// Get the last-change user for this record
	//////////////////////////////////////////////////////////////////////////////
	function LastchangeUser() {
		$chan = $this->getChangeEvent();

		if (is_null($chan))	{
			return '&nbsp;';
		}

		$chan_user = $chan->getValue("_PGVU");
		if (empty($chan_user)) {
			return '&nbsp;';
		}
		return $chan_user;
	}
}
?>
