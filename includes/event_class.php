<?php
/**
 * Class that defines an event details object
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006	John Finlay and Others
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
 * @author Joel A. Bruce
 * @version $Id: 
 * 
 */

require_once("includes/date_class.php");

/**
 * Event
 *
 */
class Event {
//	These objects need further refinement in their implementations and parsing
//	var $address = null;
//	var $notes = array(); //[0..*]: string
//	var $sourceCitations = array(); //[0..*]: SourceCitation
//	var $multimediaLinks = array(); //[0..*]: MultimediaLink
	
	var $lineNumber = null;
	var $canShow = null;
	var $canShowDetails = null;
	var $canEdit = null;
	var $state = "";
	
	var $familiyId = null;
	var $type = null;
	var $tag = null;
	var $rawDate = null;
	var $date = null;
	var $place = null;
	var $gedComRecord = null;
	var $resn = null;
	var $dest = false;
	var $label = null;
	
	var $parentObject = null;
	
	var $detail = null;
	
	var $values = array();
	
	/**
	 * Get the first level 2 value for the given GEDCOM tag
	 *
	 * @param unknown_type $code
	 * @return unknown
	 */
	function getValue($code) {
		if ($code=="DATE") return $this->rawDate;
		else if ($code=="PLAC") return $this->place;
		
		if (isset($this->values[$code])) return $this->values[$code];
		$value = get_gedcom_value($code, 2, $this->gedComRecord,'',false);
		$this->values[$code] = $value;
		return $value;	
	}
	
	/**
	 * Parses supplied subrecord to fill in the properties of the class.
	 * Assumes the level of the subrecord is 1, and that all its associated sub records are provided.
	 * 
	 * @param string $subrecord
	 * @param int $lineNumber
	 * @return Event
	 */
	function Event($subrecord, $lineNumber=-1) {
		$this->lineNumber = $lineNumber;
		$this->gedComRecord = $subrecord;
		if (empty($subrecord)) return;
		
		//Split the record into an array of level 2 sub records
		$levelTwos = split("\n2 ", $subrecord);
		
		//Split the first entry which contains the tag of event
		$tmp = explode(" ", $levelTwos[0], 3); // preg_split("/\s/", $subrecord, 3);
		if (count($tmp)<2) return;
		$this->tag = trim($tmp[1]);
		
		//Only add detail if the tag's detail information is present
		if (!empty($tmp[2])) {
			$this->detail = trim($tmp[2]);
		}
		
		foreach($levelTwos as $lvl2Sub) {
			//Split all the records underneath the level 2 record
			$subLevels = split("\n", $lvl2Sub);
			
			//Grab the type of event and its associated value
			//The first element should be the event type
			//The second element should be the associated data
			$header = explode(" ", trim($subLevels[0]), 2);

			if ($header[0] == "DATE") {
				$this->rawDate = $header[1];
				$this->date = new GedcomDate($this->rawDate);
				continue;
			}
			
			if ($header[0] == "PLAC") {
				$this->place = $header[1];
				continue;
			}
			
			if ($header[0] == "_PGVFS") {
				$this->familyId = trim($header[1], "@");
				continue;
			}
			
			if ($header[0] == "TYPE") {
				$this->type = $header[1];
				continue;
			}
		}
	}
	
	function setState($s) {
		$this->state = $s;
	}
	
	function getState() {
		return $this->state;
	}
	
	/**
	 * Check whether or not this event can be shown
	 *
	 * @return boolean
	 */
	function canShow() {
		if (is_null($this->canShow)) {
			if (empty($this->gedComRecord)) $this->canShow = false;
			else if (!is_null($this->parentObject)) {
				$this->canShow = showFact($this->tag, $this->parentObject->getXref()) && !FactViewRestricted($this->parentObject->getXref(), $this->gedComRecord);
			}
			else $this->canShow = true;
		}
		return $this->canShow;
	}
	
	/**
	 * Check whether or not the details of this event can be shown
	 *
	 * @return boolean
	 */
	function canShowDetails() {
		if (!$this->canShow()) return false;
		if (is_null($this->canShowDetails)) {
			if (!is_null($this->parentObject)) {
				$this->canShowDetails = showFactDetails($this->tag, $this->parentObject->getXref());
			}
			else $this->canShowDetails = true;
		}
		return $this->canShowDetails;
	}
	
	/**
	 * check whether or not this fact can be edited
	 *
	 * @return boolean
	 */
	function canEdit() {
		if (!$this->canShowDetails()) return false;
		if (is_null($this->canEdit)) {
			if (!is_null($this->parentObject)) {
				$this->canEdit = !FactEditRestricted($this->parentObject->getXref(), $this->gedComRecord);
			}
			else $this->canEdit = true;
		}
		return $this->canEdit;
	}
	
	/**
	 * The 4 character event type specified by GEDCom.
	 *
	 * @return string
	 */
	function getType() {
		return $this->type;
	}
	
	/**
	 * The unlocalized (raw) date stored in the GEDCom record.
	 *
	 * @return string
	 */
	function getRawDate() {
		return $this->rawDate;
	}
	
	/**
	 * Get the date object for this event
	 *
	 * @return GedcomDate
	 */
	function getDate($estimate = true) {
		if (!$estimate && $this->dest) return null;
		return new GedcomDate($this->rawDate); // Temporary - next line returning a corrupted object?
		return $this->date;
	}
	
	/**
	 * Set the date of this event.  This method should only be used to force a date.
	 *
	 * @param GedcomDate $date
	 */
	function setDate(&$date) {
		$this->date = $date;
		$this->dest = true;
	}
	
	/**
	 * The place where the event occured.
	 *
	 * @return string
	 */
	function getPlace() {
		return $this->place;
	}
	
	/**
	 * The remaining unparsed GEDCom record
	 *
	 * @return string
	 */
	function getGedComRecord() {
		return $this->gedComRecord;
	}
	
	/**
	 * The line number, or line of occurrence in the GEDCom record.
	 *
	 * @return unknown
	 */
	function getLineNumber() {
		return $this->lineNumber;
	}
	
	/**
	 * 
	 */
	function getTag() {
		return $this->tag;
	}

	/**
	 * The Person/Family record where this Event came from
	 *
	 * @return GedcomRecord
	 */
	function getParentObject() {
		return $this->parentObject;		
	}

	/**
	 * 
	 */
	function setParentObject(&$parent) {
		$this->parentObject = $parent;
	}

	/**
	 * 
	 */
	function getDetail() {
		return $this->detail;
	}
	
	function getFamilyId() {
		return $this->getValue("_PGVFS");
	}
	
	function getSpouseId() {
		return $this->getValue("_PGVS");
	}
	
	/**
	 * Check whether this fact has information to display
	 * Checks for a date or a place
	 *
	 * @return boolean
	 */
	function hasDatePlace() {
		return (is_null($this->date) && is_null($this->place));
	}
	
	function getLabel($abbreviate = false) {
		global $pgv_lang, $factarray;
		
		if (!is_null($this->label)) return $this->label;
		
		$this->label = "";
		$tag = $this->tag;
		if ($tag=="EVEN" || $tag=="FACT") {
			$type = $this->getType();
			if (!empty($type)) $tag = $type;
		}
		if (isset($pgv_lang[$tag])) $this->label = $pgv_lang[$tag];
		else if (isset($factarray[$tag])) $this->label = $factarray[$tag];
		else $this->label = $tag;
		
		if ($abbreviate) $this->label = get_first_letter($this->label);
		
		return $this->label;
	}
	
	function print_simple_fact() {
		global $pgv_lang, $SHOW_PEDIGREE_PLACES, $factarray, $ABBREVIATE_CHART_LABELS;
		
		if (!$this->canShow()) return;
		
		if ($this->gedComRecord != "1 DEAT"){
		   print "<span class=\"details_label\">".$this->getLabel($ABBREVIATE_CHART_LABELS)."</span> ";
		}
		if ($this->canShowDetails()) {
			$emptyfacts = array("BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","BAPL","CONL","ENDL","SLGC","EVEN","MARR","SLGS","MARL","ANUL","CENS","DIV","DIVF","ENGA","MARB","MARC","MARS","OBJE","CHAN","_SEPR","RESI", "DATA", "MAP");
			print PrintReady($this->detail);
			if (!$this->dest) print_fact_date($this, false, false);
			print_fact_place($this);
		}
		print "<br />\n";
	}

	// Helper functions to sort events
	function CompareDate(&$a, &$b) {
		return GedcomDate::Compare($a->getDate(), $b->getDate());
	}

	function CompareType(&$a, &$b) {
		static $factsort=NULL;

		if (is_null($factsort))
			$factsort=array_flip(array(
				"BIRT",
				"_HNM",
				"ALIA", "_AKA", "_AKAN",
				"ADOP", "_ADPF", "_ADPF",
				"_BRTM",
				"CHR", "BAPM",
				"FCOM",
				"CONF",
				"BARM", "BASM",
				"EDUC",
				"GRAD",
				"_DEG",
				"EMIG", "IMMI",
				"NATU",
				"_MILI", "_MILT",
				"ENGA",
				"MARB", "MARC", "MARL", "_MARI", "_MBON",
				"MARR", "MARR_CIVIL", "MARR_RELIGIOUS", "MARR_PARTNERS", "MARR_UNKNOWN", "_COML",
				"_STAT",
				"_SEPR",
				"DIVF",
				"MARS",
				"_BIRT_CHIL",
				"DIV", "ANUL",
				"_BIRT_", "_MARR_", "_DEAT_", // other events of close relatives
				"CENS",
				"OCCU",
				"RESI",
				"PROP",
				"CHRA",
				"RETI",
				"FACT", "EVEN",
				"_NMR", "_NMAR", "NMR",
				"NCHI",
				"WILL",
				"_HOL",
				"_????_",
				"DEAT", "CAUS",
				"_FNRL", "BURI", "CREM", "_INTE", "CEME",
				"_YART",
				"_NLIV",
				"PROB",
				"TITL",
				"COMM",
				"NATI",
				"CITN",
				"CAST",
				"RELI",
				"SSN", "IDNO",
				"TEMP",
				"SLGC", "BAPL", "CONL", "ENDL", "SLGS",
				"AFN", "REFN", "_PRMN", "REF", "RIN",
				"ADDR", "PHON", "EMAIL", "_EMAIL", "EMAL", "FAX", "WWW", "URL", "_URL",
				"CHAN", "_TODO"
			));

		// Facts from different families stay grouped together
		$afam = $a->getFamilyId();
		if (!empty($a) && $a==$b->getFamilyId())
			return 0;

		$atag = $a->getTag();
		$btag = $b->getTag();
		
		// Events not in the above list get mapped onto one that is.
		if (!array_key_exists($atag, $factsort))
			if (preg_match('/^(_(BIRT|MARR|DEAT)_)/', $atag, $match))
				$atag=$match[1];
			else
				$atag="_????_";
		if (!array_key_exists($btag, $factsort))
			if (preg_match('/^(_(BIRT|MARR|DEAT)_)/', $btag, $match))
				$btag=$match[1];
			else
				$btag="_????_";

		return $factsort[$atag]-$factsort[$btag];
	}


// When classes are fully implemented for these methods, getters should use lazy instantiation
//	/**
//	 * The address of the place where the event occured.
//	 * Note: Future versions may want to return an actual address class.
//	 * @return string
//	 */
//	function getAddress() {
//		return $this->address;
//	}
//		
//	/**
//	 * The list of notes associated with this event.
//	 *
//	 * @return array<string>
//	 */
//	function getNotes() {
//		return $this->note;
//	}
//	
//	/**
//	 * The list of source citations associated with this event.
//	 *
//	 * @return array<SourceCitation>
//	 */
//	function getSourceCitations() {
//		return $this->sourceCitation;
//	}
//
//	/**
//	 * The list of Multimedia Links associated with this event.
//	 *
//	 * @return array<MultimediaLink>
//	 */
//	function getMultimediaLink() {
//		return $this->multimediaLink;
//	}
}
?>
