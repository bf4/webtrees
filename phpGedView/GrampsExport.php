<?php

/**
 * Gramps Export
 * Exports the clippings cart into the GRAMPS XML format
 * 
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * TODO: This page could easily be used to write XML files for a web service that
 * GRAMPS could subscribe to. Currently, this file only writes a complete XML file
 * and is tied to the clippings cart. With some modifications, it could take a request
 * for data and spit out the proper XML for the requested data.
 * 
 *
 * @package PhpGedView
 * 
 */

require_once ("person_class.php");
if (file_exists($factsfile[$LANGUAGE]))
	require_once ($factsfile[$LANGUAGE]);

if (!function_exists('id_in_cart')) {
	function id_in_cart($id)
	{
		return false;
	}
}
/**
 * Creates the root elements for the GRAMPS XML file.
 * 
 * This file could be modified to only add the nessecary elements
 * for a valid GRAMPS XML document. Right now, it adds all the 
 * root elements and appends them to the DOMDocument.
 */
class GrampsExport {

	var $mediaFiles = array();
	var $dom, $ePeople, $eFams, $eSources, $ePlaces, $eObject;
	var $familyevents = array (
	"ANUL",
	"CENS",
	"DIV",
	"DIVF",
	"ENGA",
	"MARR",
	"MARB",
	"MARC",
	"MARL",
	"MARS"
);
	var $eventsArray = array (
	"ADOP",
	"BIRT",
	"BAPM",
	"BARM",
	"BASM",
	"BLES",
	"BURI",
	"CENS",
	"CHR",
	"CHRA",
	"CONF",
	"CREM",
	"DEAT",
	"EMIG",
	"FCOM",
	"GRAD",
	"IMMI",
	"NATU",
	"ORDN",
	"RETI",
	"PROB",
	"WILL",
	"EVEN"
);
	function begin_xml() {
		global $pgv_lang, $factarray;//, $eventsArray, $dom, $ePeople, $this->eFams, $eSources, $ePlaces, $eObject;
		$user = getUserName();
		$user = getUser($user);

		$this->dom = new DomDocument("1.0", "UTF-8");
		$this->dom->formatOutput = true;

		$eRoot = $this->dom->createElementNS("http://gramps-project.org/xml/1.1.0/", "database");
		$eRoot = $this->dom->appendChild($eRoot);

		$eHeader = $this->dom->createElement("header");
		$eHeader = $eRoot->appendChild($eHeader);

		$eCreated = $this->dom->createElement("created");
		$eCreated = $eHeader->appendChild($eCreated);
		$eCreated->setAttribute("date", date("Y-m-d"));
		$eCreated->setAttribute("version", "1.1.2.6");

		$eResearcher = $this->dom->createElement("researcher");
		$eResname = $this->dom->createElement("resname");
		$etResname = $this->dom->createTextNode($user["firstname"] . " " . $user["lastname"]);
		$etResname = $eResname->appendChild($etResname);
		$eResname = $eResearcher->appendChild($eResname);
		$eResemail = $this->dom->createElement("resemail");
		$etResemail = $this->dom->createTextNode($user["email"]);
		$etResemail = $eResemail->appendChild($etResemail);
		$eResemail = $eResearcher->appendChild($eResemail);
		$eResearcher = $eHeader->appendChild($eResearcher);

		$this->ePeople = $this->dom->createElement("people");
		$this->ePeople = $eRoot->appendChild($this->ePeople);

		$this->eFams = $this->dom->createElement("families");
		$this->eFams = $eRoot->appendChild($this->eFams);

		$this->eSources = $this->dom->createElement("sources");
		$this->eSources = $eRoot->appendChild($this->eSources);

		$this->ePlaces = $this->dom->createElement("places");
		$this->ePlaces = $eRoot->appendChild($this->ePlaces);

		$this->eObject = $this->dom->createElement("objects");
		$this->eObject = $eRoot->appendChild($this->eObject);
	}

	/**
	 * Creates the date elements used throughout the GRAMPS XML file.
	 * The function will parse the date record and determine the type of date
	 * (regular, range). 
	 * 
	 * @param DOMObject $eParent - The parent element the date should be attached to
	 * @param string $dateRec - the entire GEDCOM date record to be parsed
	 * @param int $level - the level the date record was found on in the GEDCOM 
	 */
	function create_date($eParent, $dateRec, $level) {
		$date = get_gedcom_value("DATE", $level, $dateRec);
		$dateParsed = parse_date($date);

		//checks to see if there's is a 2nd date value and creates the daterange element
		if (stripos($date, "/") != null || (($dateParsed[1]["year"] != null) || ($dateParsed[1]["mon"] != null) || ($dateParsed[1]["day"] != null))) {
			$eDateRange = $this->dom->createElement("daterange");
			$eDateRange = $eParent->appendChild($eDateRange);

			//sets the start date
			$year = $dateParsed[0]["year"];
			$month = $dateParsed[0]["mon"];
			$day = $dateParsed[0]["day"];
			$eDateRange->setAttribute("start", $this->create_date_value($year, $month, $day));

			//sets the stop date
			$year = $dateParsed[1]["year"];
			$month = $dateParsed[1]["mon"];
			$day = $dateParsed[1]["day"];
			if ($year == null)
				$year = $dateParsed[0]["year"];
			if ($month == null)
				$month = $dateParsed[0]["mon"];
			if ($day == null)
				$day = $dateParsed[0]["day"];
			$eDateRange->setAttribute("stop", $this->create_date_value($year, $month, $day));
		} else {
			//if there's no dateRange, this creates the normal dateval Element
			$eDateVal = $this->dom->createElement("dateval");
			$eDateVal = $eParent->appendChild($eDateVal);

			//checks for the Type attribute values
			if (($subcomp = substr_compare($date, "about", 0, strlen($date))) > 0 && $subcomp != 1)
				$eDateVal->setAttribute("type", "about");
			if (($subcomp = substr_compare($date, "after", 0, strlen($date))) > 0 && $subcomp != 1)
				$eDateVal->setAttribute("type", "after");
			if (($subcomp = substr_compare($date, "before", 0, strlen($date))) > 0 && $subcomp != 1)
				$eDateVal->setAttribute("type", "before");

			//sets the date value
			$year = $dateParsed[0]["year"];
			$month = $dateParsed[0]["mon"];
			$day = $dateParsed[0]["day"];
			$eDateVal->setAttribute("val", $this->create_date_value($year, $month, $day));
		}
	}

	/**
	 * Returns 
	 */
	function get_all_media() {
		return $this->mediaFiles;
	}

	/**
	 * checks if each date value isset and then returns the correct string to match
	 * the GRAMPS date format
	 */
	function create_date_value($year, $month, $day) {
		if ($month == null && $day == null)
			return $year;
		else
			if ($month == null && $day != null)
				return $year . "-??-" . $day;
			else
				if ($month != null && $day == null)
					return $year . "-" . $month;
				else {
					$dateVal = "";
					if ($year == null)
						$dateVal .= "????";
					else
						$dateVal .= $year;
					$dateVal .= "-";
					if ($month == null)
						$dateVal .= "??";
					else
						$dateVal .= $month;
					$dateVal .= "-";
					if ($day == null)
						$dateVal .= "??";
					else
						$dateVal .= $day;
					return $dateVal;
				}
	}

	/**
	  * Creates the Event Element given the record passed and the event abbreviation which is then
	  * 	appended to the parent element passed into the method. When the Event element is created
	  * 	all it's child elements are created and appended to it accordingly.  If the Place for the
	  * 	given event has not been created, this method calls create_placeobj thus creating the
	  * 	place and then links them together accordingly, otherwise the method just searches for
	  * 	the place and creates the link.
	  * 
	  * @param string $indirec - the entire Family, Individual, etc. record in which the event may be found
	  * @param string $event - the abbreviation of the event to be created; BIRT, DEAT, ADOP.....
	  * @param DOMElement $eParent - the parent DOMElement to which the created Event Element is appended 
	  */
	function create_event($eParent, $indirec, $event) {
		global $factarray;
		if (($eventRec = get_sub_record(1, "1 " . $event, $indirec)) != null) {

			$eEvent = $this->dom->createElement("event");
			$eEvent->setAttribute("type", $factarray[$event]);

			if (($dateRec = get_sub_record(1, "2 DATE", $eventRec)) != null) {
				$this->create_date($eEvent, $dateRec, 2);
			}

			if (($place = get_gedcom_value($event . ":PLAC", 1, $indirec)) != null) {
				$hlink = $this->query_dom("./places/placeobj[@title=\"$place\"]/@handle");
				if ($hlink == null) {
					$hlink = $this->generateHandle();
					$this->create_placeobj($place, $hlink);
					$this->create_place($eEvent, $hlink);
				} else {
					$this->create_place($eEvent, $hlink);
				}
			}

			if (($cause = get_gedcom_value($event . ":CAUS", 1, $indirec)) != null) {
				$eCause = $this->dom->createElement("cause");
				$etCause = $this->dom->createTextNode($cause);
				$etCause = $eCause->appendChild($etCause);
				$eCause = $eEvent->appendChild($eCause);
			}

			if (($description = get_gedcom_value($event . ":TYPE", 1, $indirec)) != null) {
				$eDescription = $this->dom->createElement("description");
				$etDescription = $this->dom->createTextNode($description);
				$etDescription = $eDescription->appendChild($etDescription);
				$eDescription = $eEvent->appendChild($eDescription);
			}

			if (($note = get_sub_record(1, "2 NOTE", $eventRec)) != null) {
				$this->create_note($eEvent, $note, 2);
			}

			$num = 1;
			while (($sourcerefRec = get_sub_record(2, "2 SOUR", $eventRec, $num)) != null) {
				$this->create_sourceref($eEvent, $sourcerefRec, 2);
				$num++;
			}
			$num = 1;
			while (($nameSource = get_sub_record(1, "1 OBJE", $eventRec, $num)) != null) {

				$this->create_mediaref($eEvent, $nameSource, 1);
				
				$num++;
			}
			$eEvent = $eParent->appendChild($eEvent);
		}
	}

	/**
	 * This function creates a family relation for a person and appends the relation
	 * to the person element.
	 * 
	 * It searches through the DOMDocument first to see if the person is created,
	 * if they are not, the person is created and then the DOMDocument is queried
	 * and the persons HLINK is retrieved.
	 * 
	 * @param $eParent - the parent XML element the date element should be appended to
	 * @param personRec - the full INDI GEDCOM record of the person that the relation is being created
	 * @param $tag -  the name of the GEDCOM tag (FAMC, FAMS). This is used to allow the same function to work with childin and parent_in_family relations
	 */
	function create_fam_relation($eParent, $personRec, $tag) {
		global $pgv_lang;
		$famid = get_gedcom_value($tag, 1, $personRec);
		$handle = $this->query_dom("./families/family[@id=\"$famid\"]/@handle");
		$created = false;
		if ($handle == null && id_in_cart($famid)) {
			$frec = find_family_record($famid);
			/* 
			* If the family does not exist and their ID is in the clippings cart,
			* you must create the family before you can query them in the dom to get
			* their hlink. The hlink is generated when the person element is created.
			* This causes overhead creating objects that are never added to the XML file
			* perhaps there is some other way this can be done reducing the overhead?
			* 
			*/
			$this->create_family($frec, $famid);
			$handle = $this->query_dom("./families/family[@id=\"$famid\"]/@handle");
		}
		if ($handle != null && id_in_cart($famid)) {
			$elementName = "";
			if ($tag == "FAMC")
				$elementName = "childof";
			else
				$elementName = "parentin";

			$eChildof = $this->dom->createElement($elementName);
			$eChildof->setAttribute("hlink", $handle);
			$eChildof = $eParent->appendChild($eChildof);
		}
	}

	/**
	 * Creates the Family element and all of it's child elements, and appends it to the
	 * Families element.  This function will search through the DOMDocument looking 
	 * for people in the family. If they are not created yet and they are in the clippings
	 * cart, they will be created and ther hlink added to the family element.
	 * 
	 * @param string $frec - the full FAM GEDCOM record of the family to be created
	 * @param string $fid = the ID (F1, F2, F3) of the family that is being created
	 */
	function create_family($frec, $fid, $type=0) {
		$check = $this->query_dom("./families/family[@id=\"$fid\"]/@id");
		if (($check == null || $check != $fid) && (id_in_cart($fid)||$type==1)) {
			$famrec = $frec;
			$eFamily = $this->dom->createElement("family");
			$eFamily->setAttribute("id", $fid);
			$eFamily->setAttribute("handle", $this->generateHandle());
			$eFamily->setAttribute("change", time());
			$eFamily = $this->eFams->appendChild($eFamily);

			// Add the <father> element
			$id = get_gedcom_value("HUSB", 1, $famrec);
			$pers = $this->query_dom("./people/person[@id=\"$id\"]/@handle");
			if (!isset ($pers) && (id_in_cart($id)||$type==1)) {
				/*
				 * 
				 * If the person does not exist and their ID is in the clippings cart,
				 * you must create the person before you can query them in the dom to get
				 * their hlink. The hlink is generated when the person element is created.
				 * This causes overhead creating objects that are never added to the XML file
				 * perhaps there is some other way this can be done reducing the overhead?
				 * 
				 */
				$this->create_person(find_person_record($id), $id);
				$pers = $this->query_dom("./people/person[@id=\"$id\"]/@handle");
			}
			if (isset ($id) && trim($id) && (id_in_cart($id)||$type==1)) {
				$eFather = $this->dom->createElement("father");
				$eFather->setAttribute("hlink", $pers);
				$eFather = $eFamily->appendChild($eFather);
			}

			// Add the <mother> element
			$id = get_gedcom_value("WIFE", 1, $famrec);
			$pers = $this->query_dom("./people/person[@id=\"$id\"]/@handle");
			if (!isset ($pers) && (id_in_cart($id)||$type==1)) {
				/*
				 * 
				 * If the person does not exist and their ID is in the clippings cart,
				 * you must create the person before you can query them in the dom to get
				 * their hlink. The hlink is generated when the person element is created.
				 * This causes overhead creating objects that are never added to the XML file
				 * perhaps there is some other way this can be done reducing the overhead?
				 * 
				 */
				$this->create_person(find_person_record($id), $id);
				$pers = $this->query_dom("./people/person[@id=\"$id\"]/@handle");
			}
			if (isset ($id) && trim($id) != "" && $id != null && (id_in_cart($id)||$type==1)) {
				$eMother = $this->dom->createElement("mother");
				$eMother->setAttribute("hlink", $pers);
				$eMother = $eFamily->appendChild($eMother);
			}

			foreach ($this->familyevents as $event) {
				$this->create_event($eFamily, $frec, $event);
			}

			// Add the <child> element
			$id = get_gedcom_value("CHIL", 1, $famrec);
			$pers = $this->query_dom("./people/person[@id=\"$id\"]/@handle");

			if (isset ($id) && isset ($pers) && (id_in_cart($id)||$type==1)) {
				$eChild = $this->dom->createElement("child");
				$eChild->setAttribute("hlink", $pers);
				$eChild = $eFamily->appendChild($eChild);
			}
			if (($note = get_sub_record(1, "1 NOTE", $frec)) != null) {
				$this->create_note($eFamily, $note, 1);
			}

			$num = 1;
			while (($sourcerefRec = get_sub_record(1, "1 SOUR", $frec, $num)) != null) {
				$this->create_sourceref($eFamily, $sourcerefRec, 1);
				$num++;
			}
			$num = 1;
			while (($nameSource = get_sub_record(1, "1 OBJE", $frec, $num)) != null) {

				$this->create_mediaref($eFamily, $nameSource, 1);
				$num++;
			}
	
		}
		if ($type != 0) return $eFamily;
	}

	/**
	* Creates the lds_ord element and appends the correct information depending
	* on the type of lds_ord (Endowment, Sealing, Baptism). If there is a sealing,
	* the function will search if the family is in the clippings cart and if the 
	* family is created or not. If the family is not created yet, it will be created
	* and added to the DOMDocument 
	* 
	* @param $indirec - The full INDI GEDCOM record of the person the lds_ord is being created
	* @param $eventName - the name of the LDS event (Baptism, Sealing, Endowment, etc...)
	* @param $eventABV - the event abbreviation in the GEDCOM (ie. SLGC, BAPL, ENDL)
	* @param $eParent - The parent element the lds event is attached to
	*/
	function create_lds_event($indirec, $eventName, $eventABV, $eParent) {
		global  $ePerson, $TEMPLE_CODES, $clipping;

		if (($hasldsevent = get_sub_record(1, "1 " . $eventABV, $indirec)) != null) {

			// Create <lds_ord> and attaches the type attribute
			$eLdsEvent = $this->dom->createElement("lds_ord");
			$eLdsEvent->setAttribute("type", $eventName);

			if (($dateRec = get_sub_record(1, "2 DATE", $hasldsevent)) != null)
				$this->create_date($eLdsEvent, $dateRec, 2);

			// Create <temple>, this element is common with all lds ords
			if (($temple = get_gedcom_value($eventABV . ":TEMP", 1, $indirec)) != null) {
				$eTemple = $this->dom->createElement("temple");
				$eTemple->setAttribute("val", $temple);
				$eTemple = $eLdsEvent->appendChild($eTemple);
			}

			if (($place = get_gedcom_value($eventABV . ":PLAC", 1, $indirec)) != null) {
				$hlink = $this->query_dom("./places/placeobj[@title=\"$place\"]/@handle");
				if ($hlink == null) {
					$hlink = $this->generateHandle();
					$this->create_placeobj($place, $hlink);
					$this->create_place($eLdsEvent, $hlink);
				} else {
					$this->create_place($eLdsEvent, $hlink);
				}
			}

			// Check to see if the STAT of the ordinance is set and add it to the 
			// <lds_ord> element
			if (($stat = get_gedcom_value($eventABV . ":STAT", 1, $indirec)) != null) {
				$eStatus = $this->dom->createElement("status");
				$stat = get_gedcom_value($eventABV . ":STAT", 1, $indirec);
				$eStatus->setAttribute("val", isset ($stat));
				$eStatus = $eLdsEvent->appendChild($eStatus);
			}
			// If the event is a sealing
			if ($eventABV == "SLGC") {
				// Create an instance of person and look for their family record
				$person = Person :: getInstance($clipping["id"]);
				if($person != null)
				{
				$famId = $person->getChildFamilyIds();
				$famrec = find_family_record($famId[0]);
				$fid = $famId[0];
				$handle = $this->query_dom("./families/family[@id=\"$fid\"]/@handle");
				if ($handle == null && id_in_cart($fid)) {
					/* 
					 * If the family does not exist and their ID is in the clippings cart,
					 * you must create the family before you can query them in the dom to get
					 * their hlink. The hlink is generated when the person element is created.
					 * This causes overhead creating objects that are never added to the XML file
					 * perhaps there is some other way this can be done reducing the overhead?
					 * 
					 */
					$this->create_family($famrec, $famId[0]);
					$handle = $this->query_dom("./families/family[@id=\"$fid\"]/@handle");
					$eFam = $this->dom->createElement("sealed_to");
					$eFam->setAttribute("hlink", $handle);
					$eFam = $eLdsEvent->appendChild($eFam);
					$person = null;
				} else
					if ($handle != null && id_in_cart($fid)) {
						$eFam = $this->dom->createElement("sealed_to");
						$eFam->setAttribute("hlink", $handle);
						$eFam = $eLdsEvent->appendChild($eFam);
						$person = null;
					}
				}
			}

			if (($note = get_sub_record(1, "2 NOTE", $hasldsevent)) != null)
				$this->create_note($eLdsEvent, $note, 2);

			$num = 1;
			while (($sourcerefRec = get_sub_record(2, "2 SOUR", $hasldsevent, $num)) != null) {
				$this->create_sourceref($eLdsEvent, $sourcerefRec, 2);
				$num++;
			}
			$eLdsEvent = $eParent->appendChild($eLdsEvent);
		}
	}

	/**
	  * Creates the Note element and appends it to the parent element
	  * 
	  * @param DOMElement $eParent - the parent DOMElement to which the created Note Element is appended
	  * @param string $noteRec - the entire Family, Individual, etc. record in which the event may be found
	  * @param int $level - The GEDCOM line level where the NOTE tag may be found
	  */
	function create_note($eParent, $noteRec, $level) {
		$note = get_gedcom_value("NOTE", $level, $noteRec);
		$num = 1;
		while (($cont = get_gedcom_value("NOTE:CONT", $level, $noteRec, $num)) != null) {
			$note .= $cont;
			$num++;
		}
		$eNote = $this->dom->createElement("note");
		$etNote = $this->dom->createTextNode($note);
		$etNote = $eNote->appendChild($etNote);
		$eNote = $eParent->appendChild($eNote);
	}

	/**
	  * Creates the Person element and all of it's child elements, and appends it to the
	  * 	People element.  Given the link for certain LDS events to a family, if the Family 
	  * 	has not been previously created, create_family is called to create the family. 
	  * 	The family relations in the LDS events and in the person element are only created
	  * 	if the family they have a relation with are also included in the clippings cart   
	  * 
	  * @param string $personRec - the full INDI GEDCOM record of the person to be created
	  * @param string $personID - the ID (I1, I2, I3) of the person the is being created 
	  */
	function create_person($personRec = "", $personID = "") {
		global $pgv_lang;
		$check = $this->query_dom("./people/person[@id=\"$personID\"]");
		if ($check == null && id_in_cart($personID)) {
			$ePerson = $this->dom->createElement("person");
			$ePerson = $this->ePeople->appendChild($ePerson);
			//$ePerson = $this->ePeople->appendChild($ePerson);

			//set attributes for <person>
			$ePerson->setAttribute("id", $personID);
			$ePerson->setAttribute("handle", $this->generateHandle());
			$ePerson->setAttribute("change", time());

			$eGender = $this->dom->createElement("gender");
			$eGender = $ePerson->appendChild($eGender);
			if (($gender = get_gedcom_value("SEX", 1, $personRec)) != null)
				$etGender = $this->dom->createTextNode($gender);
			else
				$etGender = $this->dom->createTextNode("U");

			$num = 1;
			$etGender = $eGender->appendChild($etGender);
			while (($nameSource = get_sub_record(1, "1 OBJE", $personRec, $num)) != null) {

				$this->create_mediaref($ePerson, $nameSource, 1);
				$num++;
			}
			if (($nameRec = get_sub_record(1, "1 NAME", $personRec)) != null) {
				//creates name
				$eName = $this->dom->createElement("name");
				$eName->setAttribute("type", "Birth Name");

				$givn = get_gedcom_value("GIVN", 2, $nameRec);
				$eFirstName = $this->dom->createElement("first");
				if (!isset ($givn))
					$givn = $pgv_lang["unknown"];
				$etFirstName = $this->dom->createTextNode($givn);
				$etFirstName = $eFirstName->appendChild($etFirstName);
				$eFirstName = $eName->appendChild($eFirstName);

				$surn = get_gedcom_value("SURN", 2, $nameRec);
				$eLastName = $this->dom->createElement("last");
				if (!isset ($surn))
					$surn = $pgv_lang["unknown"];
				$etLastName = $this->dom->createTextNode($surn);
				$etLastName = $eLastName->appendChild($etLastName);
				$eLastName = $eName->appendChild($eLastName);
				$eName = $ePerson->appendChild($eName);

				if (($nsfx = get_gedcom_value("NSFX", 2, $nameRec)) != null) {
					$eSuffix = $this->dom->createElement("suffix");
					$etSuffix = $this->dom->createTextNode($nsfx);
					$etSuffix = $eSuffix->appendChild($etSuffix);
					$eSuffix = $eName->appendChild($eSuffix);
				}

				//retrieves name prefix 
				if (($npfx = get_gedcom_value("NPFX", 2, $nameRec)) != null) {
					$eTitle = $this->dom->createElement("title");
					$etTitle = $this->dom->createTextNode($npfx);
					$etTitle = $eTitle->appendChild($etTitle);
					$eTitle = $eName->appendChild($eTitle);
				}

				//retrieves the nickname
				if (($nick = get_gedcom_value("NICK", 2, $nameRec)) != null) {
					$eNick = $this->dom->createElement("nick");
					$etNick = $this->dom->createTextNode($nick);
					$etNick = $eNick->appendChild($etNick);
					$eNick = $ePerson->appendChild($eNick);
				}

				//creates note
				if (($nameNote = get_sub_record(2, "2 NOTE", $nameRec)) != null) {
					$this->create_note($eName, $nameNote, 2);
				}

				//creates SourceRef
				$num = 1;
				while (($nameSource = get_sub_record(2, "2 SOUR", $nameRec, $num)) != null) {
					$this->create_sourceref($eName, $nameSource, 2);
					$num++;
				}

			}

			foreach ($this->eventsArray as $event) {
				$this->create_event($ePerson, $personRec, $event);
			}

			$this->create_lds_event($personRec, "baptism", "BAPL", $ePerson);
			$this->create_lds_event($personRec, "endowment", "ENDL", $ePerson);
			$this->create_lds_event($personRec, "sealed_to_parents", "SLGC", $ePerson);

			/* This creates the family relation for a person, to link them to
			 * the family they are a child in and to link them to the family
			 * where they are a spouse. These relations will only be included
			 * if the family is also in the clippings cart. Otherwise, the relations
			 * are simply left out of the XML file.
			 * 
			 *
			*create_fam_relation($ePerson,$personRec,"FAMC");
			*create_fam_relation($ePerson,$personRec,"FAMS");		
			*/
			if (($note = get_sub_record(1, "1 NOTE", $personRec)) != null) {
				$this->create_note($ePerson, $note, 1);
			}
			$num = 1;
			while (($sourcerefRec = get_sub_record(1, "1 SOUR", $personRec, $num)) != null) {
				$this->create_sourceref($ePerson, $sourcerefRec, 1);
				$num++;
			}
		}

	}
	/**
	  * Creates the Place Element and appends it to the Parent element given   
	  * 
	  * @param DOMElement $eParent - the parent DOMElement to which the created Place Element is appended
	  * @param string $hlink - the value to which the 'hlink' attribute is set 
	  */
	function create_place($eParent, $hlink) {
		$ePlace = $this->dom->createElement("place");
		$ePlace->setAttribute("hlink", $hlink);
		$ePlace = $eParent->appendChild($ePlace);
	}

	/**
	  * Creates the PlaceObj element and appends it to the Places element  
	  * 
	  * @param string $place - the string containing the value for the placeobj to be created
	  * @param string $hlink - the value to which the 'hlink' attribute is set 
	  */
	function create_placeobj($place, $hlink) {
		$ePlaceObj = $this->dom->createElement("placeobj");
		$ePlaceObj->setAttribute("handle", $hlink);
		$ePlaceObj->setAttribute("id", $hlink);
		$ePlaceObj->setAttribute("change", time());
		$ePlaceObj->setAttribute("title", $place);
		$ePlaceObj = $this->ePlaces->appendChild($ePlaceObj);
		$num = 1;
		while (($nameSource = get_sub_record(1, "1 OBJE", $place, $num)) != null) {
			$this->create_mediaref($this->ePlaces, $nameSource, 1);
			$num++;
		}
	}

	function create_mediaref($eParent, $sourcerefRec, $level) {
		$mediaId = get_gedcom_value("OBJE", $level, $sourcerefRec);
		$eMediaRef = $this->dom->createElement("objref");
		$eMediaRef = $eParent->appendChild($eMediaRef);
		if (($sourceHlink = $this->query_dom("./objects/object[@id = \"$mediaId\"]/@handle")) == null)
			$this->create_media($mediaId, find_record_in_file($mediaId));
		$eMediaRef->setAttribute("hlink", $this->query_dom("./objects/object[@id = \"$mediaId\"]/@handle"));
		$eParent->appendChild($eMediaRef);
		//		 $mediaRecord = find_gedcom_record($mediaId);
		//               $this->create_media($mediaId,$mediaRecord);
	}
	/**
	 * $indirec gedcom - the gedcom we are searching with regular expresion
	 * 
	 */
	function create_media($mediaID, $mediaRec, $level = 1) {
		global $file, $IncludeMedia;
		//This if checks to see if both the include media is checked, and the media record is in the clippings cart.
		if (id_in_cart($mediaID)) {
			$object = $this->dom->createElement("object");
			/*primary object elements and attributes*/
			$object->setAttribute("id", $mediaID);
			$object->setAttribute("handle", $this->generateHandle());
			$object->setAttribute("change", time());
			/*elements and attributes of the object element*/
			/*File elements*/
			$file_ = get_gedcom_value("FILE", 1, $mediaRec);
			if (isset($IncludeMedia) && $IncludeMedia == "yes" && file_exists($file_))
				$this->mediaFiles[] = $file_;
			$fileNode = $object; /*for the new rng change $object with $this->dom->createElement("file");*/
	
			/*Source*/
			$src = $this->dom->createAttribute("src");
			$srcData = $this->dom->createTextNode($file_); //'.'.$file_
			$srcData = $src->appendChild($srcData);
			$src = $fileNode->appendChild($src);
			/*MIME*/
			$mime_ = get_gedcom_value("FORM", 1, $mediaRec);
			$mime = $this->dom->createAttribute("mime");
			if (empty ($mime_)) {
				$path = pathinfo($file_);
				$mime_ = $path["extension"];
			}
			$mimeData = $this->dom->createTextNode($mime_);
			$mimeData = $mime->appendChild($mimeData);
			$mime = $fileNode->appendChild($mime);
			/*DESCRIPTION*/
			$description_ = get_gedcom_value("TITL", 1, $mediaRec);
			$description = $this->dom->createAttribute("description");
			$descriptionData = $this->dom->createTextNode($description_);
			$descriptionData = $description->appendChild($descriptionData);
			$description = $fileNode->appendChild($description);
			/*fileNode elements*/
			/*For the new rng just uncomment
			$fileNode = $object->appendChild($fileNode);
	
			$fileNode = $this->dom->createElement("file");
			*/
			if (($note = get_sub_record(1, "1 NOTE", $mediaRec)) != null) {
				$this->create_note($object, $note, 1);
			}
			$num = 1;
			while (($nameSource = get_sub_record($level, $level . " SOUR", $mediaRec, $num)) != null) {
				$this->create_sourceref($object, $nameSource, 1);
				$num++;
			}
			$object = $this->eObject->appendChild($object);
			//find_highlighted_media();//find the primary picture for the gedcom record
		}
	}
	/**
	  * Creates the SourceRef element and appends it to the Parent Element.  If the actual Source has not
	  * 	been previously created, this will retrieve the record for that, and create that also.
	  * 
	  * @param DOMElement $eParent - the parent DOMElement to which the created Note Element is appended
	  * @param string $sourcerefRec - the record containing the reference to a Source
	  * @param int $level - The GEDCOM line level where the SOUR tag may be found
	  */
	function create_sourceref($eParent, $sourcerefRec, $level) {
		if (($sourceID = get_gedcom_value("SOUR", $level, $sourcerefRec)) != null) {
			if (id_in_cart($sourceID)) {
				$eSourceRef = $this->dom->createElement("sourceref");
				$eSourceRef = $eParent->appendChild($eSourceRef);
				if (($sourceHlink = $this->query_dom("./sources/source[@id = \"$sourceID\"]/@handle")) == null)
					$this->create_source($sourceID, find_record_in_file($sourceID));

				$eSourceRef->setAttribute("hlink", $this->query_dom("./sources/source[@id = \"$sourceID\"]/@handle"));

				if (($page = get_gedcom_value("SOUR:PAGE", $level, $sourcerefRec)) != null) {
					$eSPage = $this->dom->createElement("spage");
					$etSPage = $this->dom->createTextNode($page);
					$etSPage = $eSPage->appendChild($etSPage);
					$eSPage = $eSourceRef->appendChild($eSPage);
				}

				if (($comments = get_gedcom_value("SOUR:NOTE", $level, $sourcerefRec)) != null) {
					$eSComments = $this->dom->createElement("scomments");
					$etSComments = $this->dom->createTextNode($comments);
					$etSComments = $eSComments->appendChild($etSComments);
					$eSComments = $eSourceRef->appendChild($eSComments);
				}

				if (($text = get_gedcom_value("SOUR:TEXT", $level, $sourcerefRec)) != null) {
					$num = 1;
					while (($cont = get_gedcom_value("SOUR:TEXT:CONT", $level, $sourcerefRec, $num)) != null) {
						$text .= $cont;
						$num++;
					}
					$eSText = $this->dom->createElement("stext");
					$etSText = $this->dom->createTextNode($text);
					$etSText = $eSText->appendChild($etSText);
					$eSText = $eSourceRef->appendChild($eSText);
				}

				if (($dateRec = get_sub_record(1, ($level +1) . " DATE", $sourcerefRec)) != null) {
					$this->create_date($eSourceRef, $dateRec, $level +1);
				}
			}
		}
	}

	/**
	  * Creates the Source and appends it to the Sources Element
	  * 
	  * @param string $sourceID - the ID of the source to be created
	  * @param string $sourceRec - the entire GEDCOM record containing the Source
	  */
	function create_source($sourceID, $sourceRec, $level = 1) {
		$eSource = $this->dom->createElement("source");
		$eSource->setAttribute("id", $sourceID);
		$eSource->setAttribute("handle", $this->generateHandle());
		$eSource->setAttribute("change", time());
		if (($title = get_gedcom_value("TITL", $level, $sourceRec)) != null) {
			$eSTitle = $this->dom->createElement("stitle");
			$etSTitle = $this->dom->createTextNode($title);
			$etSTitle = $eSTitle->appendChild($etSTitle);
			$eSTitle = $eSource->appendChild($eSTitle);
		}
		if (($author = get_gedcom_value("AUTH", $level, $sourceRec)) != null) {
			$eSAuthor = $this->dom->createElement("sauthor");
			$etSAuthor = $this->dom->createTextNode($author);
			$etSAuthor = $eSAuthor->appendChild($etSAuthor);
			$eSAuthor = $eSource->appendChild($eSAuthor);
		}
		if (($pubInfo = get_gedcom_value("PUBL", $level, $sourceRec)) != null) {
			$eSPubInfo = $this->dom->createElement("spubinfo");
			$etSPubInfo = $this->dom->createTextNode($pubInfo);
			$etSPubInfo = $eSPubInfo->appendChild($etSPubInfo);
			$eSPubInfo = $eSource->appendChild($eSPubInfo);
		}
		if (($abbrev = get_gedcom_value("ABBR", $level, $sourceRec)) != null) {
			$eSAbbrev = $this->dom->createElement("sabbrev");
			$etSAbbrev = $this->dom->createTextNode($abbrev);
			$etSAbbrev = $eSAbbrev->appendChild($etSAbbrev);
			$eSAbbrev = $eSource->appendChild($eSAbbrev);
		}
		if (($note = get_sub_record($level, $level . " NOTE", $sourceRec)) != null) {
			$this->create_note($eSource, $note, $level);
		}
		$num = 1;
		while (($nameSource = get_sub_record(1, "1 OBJE", $sourceRec, $num)) != null) {
			$this->create_mediaref($this->eSources, $nameSource, 1);
			$num++;
		}
		$eSource = $this->eSources->appendChild($eSource);
	}

	/**
	* Generates a unique identifier for linking elements in the DOMDocument
	* This function was created to conform to how GRAMPS formats their XML
	* documents. The handle only has to be unique among the file, but this 
	* allows for a creation of a huge XML file with little chance of overlap
	*/
	function generateHandle() {
		return strtoupper("_" . dechex(rand() * (time() * 877)) . dechex(time() * rand(1, 4)));
	}

	/**
	* Reads in an xpath expression and returns the value searched for by the expression
	* 
	* @param string $query - XPath expression to be executed on the DOMDocument
	* 
	* @return string - The result of the XPath expression (null if no record is found)
	*/
	function query_dom($query) {
		$xpath = new DOMXpath($this->dom);
		$id = $xpath->query($query);
		if ($id->length == 0)
			$id = null;
		if (isset ($id)) {
			foreach ($id as $handle) {
				$id = $handle->nodeValue;
			}
		}
		return $id;
	}

	/**
	* This function takes the dom document and validates it against the
	* GRAMPS RNG schema. It then prints out the results of the validation
	* to the screen.
	* 
	* Validating against the DTD could be easily added
	* if that is deemed useful.
	* 
	* @param DOMDocument $domObj - this is the dom document to be validated
	* @param boolean $printXML - set to true, this parameter will make validate print out the DOMDocuments XML to the screen
	* 
	*/
	function validate($domObj, $printXML = true) {
		if ($printXML) {
			print "<br /><br /><br />" . nl2br(htmlentities($domObj->saveXML()));
		}
		print "Loading GRAMPS file... <br />";
		$domObj->loadXML($domObj->saveXML());
		$res = $domObj->relaxNGValidate('includes/grampsxml.rng');
		if ($res)
			print "Validation: <em style=\"color:green;\"><h1> PASSED against the .RNG</h1></em>";
		else
			print "Validation: <em style=\"color:red;\"><h1> FAILED against the .RNG</h1></em>";
	}

	function items_in_cart($elementName) {
		$passed = true;
		$people = $this->dom->getElementsByTagName($elementName);
		foreach ($people as $person) {
			$id = $person->attributes->item(0)->nodeValue;
			if (!id_in_cart($id)) {
				$passed = false;
			}
		}
		return $passed;
	}
//
//	/**
//	 * This function is not implemented yet. GRAMPS can import the XML file gzipped 
//	 * without any issue. This would be much better for users to download because
//	 * the file size would be considerably smaller.
//	 */
//	function zipGramps() {
//		global $dom;
//		// TODO: Output the XML file gzipped for download by the user
//	}
}
?>
