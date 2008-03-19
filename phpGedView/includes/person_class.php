<?php
/**
 * Class file for a person
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008	John Finlay and Others
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

require_once 'includes/gedcomrecord.php';
require_once 'includes/family_class.php';

class Person extends GedcomRecord {
	var $sex = "U";
	var $disp = true;
	var $dispname = true;
	var $indifacts = array();
	var $otherfacts = array();
	var $globalfacts = array();
	var $mediafacts = array();
	var $facts_parsed = false;
	var $bd_parsed = false;
	var $bdate = "";
	var $ddate = "";
	var $bplace = "";
	var $dplace = "";
	var $brec = "";
	var $drec = "";
	var $best = false;
	var $dest = false;
	var $bdate2 = "";
	var $ddate2 = "";
	var $brec2 = "";
	var $drec2 = "";
	var $fams = null;
	var $famc = null;
	var $spouseFamilies = null;
	var $childFamilies = null;
	var $label = "";
	var $highlightedimage = null;
	var $file = "";
	var $age = null;
	var $isdead = -1;
	
	/**
	 * Constructor for person object
	 * @param string $gedrec	the raw individual gedcom record
	 */
	function Person($gedrec,$simple=true) {
		global $MAX_ALIVE_AGE;

		parent::GedcomRecord($gedrec, $simple);

		$st = preg_match("/1 SEX (.*)/", $this->gedrec, $smatch);
		if ($st>0) $this->sex = trim($smatch[1]);
		if (empty($this->sex)) $this->sex = "U";
		$this->disp = displayDetails($this->gedrec);
		$this->dispname = showLivingName($this->gedrec);
	}

	/**
	 * Static function used to get an instance of a person object
	 * @param string $pid	the ID of the person to retrieve
	 * @return Person	returns an instance of a person object
	 */
	function &getInstance($pid, $simple=true) {
		global $indilist, $GEDCOM, $GEDCOMS, $pgv_changes;

		if (isset($indilist[$pid])
			&& isset($indilist[$pid]['gedfile'])
			&& $indilist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($indilist[$pid]['object'])) return $indilist[$pid]['object'];
		}

		$indirec = find_person_record($pid);
		if (empty($indirec)) {
			$ct = preg_match("/(\w+):(.+)/", $pid, $match);
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				require_once 'includes/serviceclient_class.php';
				$service = ServiceClient::getInstance($servid);
				if (!empty($service)) {
					$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$pid."@ INDI\r\n1 RFN ".$pid, false);
					$indirec = $newrec;
				}
			}
		}
		if (empty($indirec)) {
			if (PGV_USER_CAN_EDIT && isset($pgv_changes[$pid."_".$GEDCOM])) {
				$indirec = find_updated_record($pid);
				$fromfile = true;
			}
		}
		//if (empty($indirec)) return null;
		$person = new Person($indirec, $simple);
		if (!empty($fromfile)) $person->setChanged(true);
		//-- update the cache
		if ($person->isRemote()) {
			$indilist[$pid]['gedcom'] = $person->gedrec;
			$indilist[$pid]['names'] = get_indi_names($person->gedrec);
			$indilist[$pid]["isdead"] = is_dead($person->gedrec);
			$indilist[$pid]["gedfile"] = $GEDCOMS[$GEDCOM]['id'];
			if (isset($indilist[$pid]['privacy'])) unset($indilist[$pid]['privacy']);
		}
		$indilist[$pid]['object'] = &$person;
		if (!isset($indilist[$pid]['gedfile'])) $indilist[$pid]['gedfile'] = $GEDCOMS[$GEDCOM]['id'];
		return $person;
	}

	/**
	 * get the name
	 * @return string
	 */
	function getName() {
		global $pgv_lang;
		if (!$this->canDisplayName()) return $pgv_lang["private"];
		$name = get_person_name($this->xref);
		if (empty($name)) return $pgv_lang["unknown"];
		return $name;
	}

	/**
	 * gets the number of names this individual has
	 * @return int 	the number of names in this individual
	 */
	function getNameCount() {
		global $indilist;
		
		if (isset($indilist[$this->getXref()]['names'])) return count($indilist[$this->getXref()]['names']);
		$names = get_indi_names($this->gedrec);
		return count($names);
	}

	/**
	 * get the sortable name
	 * @param string $subtag optional subtag _AKA _HEB etc...
	 * @param int $num which matching subtag to get
	 * @param boolean $starred option to add starredname html code
	 * @return string
	 */
	function getSortableName($subtag="", $num=1, $starred=true) {
		global $pgv_lang, $UNDERLINE_NAME_QUOTES;
		global $unknownNN, $unknownPN, $indilist;
		if (!$this->canDisplayName()) {
			if (empty($subtag)) return $pgv_lang["private"];
			else return "";
		}
		// if subtag contains '/' we assume this is a name to change as sortable
		// else we get name from gedcom record
		if (stristr($subtag, "/")) $name = $subtag;
		else if (empty($subtag)) {
			//we need only the 1 NAME main names, not all the individual names
			$namerec = get_sub_record(1, "1 NAME", $this->gedrec, $num);
			$name = get_gedcom_value("NAME", 1, $namerec, '', false);
			//-- names are stored in the $indilist cache [names] element
//			if (isset($indilist[$this->getXref()]['names'][$num-1])) $name = $indilist[$this->getXref()]['names'][$num-1][0];
		}
		else {
			// Get the sub record from *all* the 1 NAME records
			$all_name_recs=get_sub_record(1, "1 NAME", $this->gedrec, 1);
			for ($i=2; ; ++$i) {
				$tmp=get_sub_record(1, "1 NAME", $this->gedrec, $i);
				if (empty($tmp))
					break;
				else
					$all_name_recs.="\n".$tmp;
			}
			$namerec = get_sub_record(2, "2 ".$subtag, $all_name_recs, $num);
			$name = get_gedcom_value($subtag, 2, $namerec, '', false);
		}
		if (empty($name)) return "";
		// get GIVN and SURN
		list($givn, $surn, $nsfx) = explode("/", $name."//");
		$exp = explode(",",$givn); $givn = trim($exp[0]);
		if (empty($surn) or trim("@".$surn,"_")=="@" or $surn=="@N.N.") {
			$lang = whatLanguage($givn);
			$surn = $unknownNN[$lang];
		}
		if (empty($givn) or trim("@".$givn,"_")=="@" or $givn=="@P.N.") {
			$lang = whatLanguage($surn);
			$givn = $unknownPN[$lang];
		}
		else if ($starred) {
			if ($UNDERLINE_NAME_QUOTES) $givn = preg_replace("/\"(.+)\"/", "<span class=\"starredname\">$1</span>", $givn);
			$givn = preg_replace("/([^ ]+)\*/", "<span class=\"starredname\">$1</span>", $givn);
		}
		if ($nsfx) $surn .= " ".trim($nsfx);
		return trim($surn.", ".$givn);
	}

	/**
	 * get the surname
	 * @return string
	 */
	function getSurname() {
		global $pgv_lang, $indilist;
		if (!$this->canDisplayName()) return $pgv_lang["private"];
		if (!isset($indilist[$this->getXref()]['names'])) return $pgv_lang['unknown'];
		$ct = preg_match("~/(.*)/~",$indilist[$this->getXref()]['names'][0][0], $match);//pregmatch
		$name = trim($match[1]);
		if (empty($name)) return $pgv_lang["unknown"];
		return $name;
	}
	/**
	 * get the given names
	 * @return string
	 */
	function getGivenNames(){
		global $pgv_lang, $indilist;
		if (!$this->canDisplayName()) return $pgv_lang["private"];
		if (!isset($indilist[$this->getXref()]['names'])) return $pgv_lang['unknown'];
		$ct = preg_match("~^([^\s]*)~",$indilist[$this->getXref()]['names'][0][0], $match);//pregmatch
		$name = trim($match[1]);
		if (empty($name)) return $pgv_lang["unknown"];
		return $name;
	}

	/**
	 * Check if an additional name exists for this person
	 * @return string
	 */
	function getAddName() {
		if (!$this->canDisplayName()) return "";
		return get_add_person_name($this->xref);
	}
	/**
	 * Check if privacy options allow this record to be displayed
	 * @return boolean
	 */
	function canDisplayDetails() {
		return $this->disp;
	}
	/**
	 * Check if privacy options allow the display of the persons name
	 * @return boolean
	 */
	function canDisplayName() {
		return ($this->disp || $this->dispname);
	}

	/**
	 * Return whether or not this person is already dead
	 * @return boolean	true if dead, false if alive
	 */
	function isDead() {
		if ($this->isdead==-1) $this->isdead = is_dead_id($this->getXref());
		return $this->isdead;
	}

	/**
	 * get highlighted media
	 * @return array
	 */
	function findHighlightedMedia() {
		if (is_null($this->highlightedimage)) {
			$this->highlightedimage = find_highlighted_object($this->xref, $this->gedrec);
		}
		return $this->highlightedimage;
	}
	/**
	 * parse birth and death records
	 */
	function _parseBirthDeath() {
		global $MAX_ALIVE_AGE, $pgv_lang;
		
		if ($this->bd_parsed) return;
		$this->bd_parsed = true;
		
		$this->brec = trim(get_sub_record(1, "1 BIRT", $this->gedrec));
		$this->drec = trim(get_sub_record(1, "1 DEAT", $this->gedrec));
		$this->bdate = get_gedcom_value("DATE", 2, $this->brec, '', false);
		$this->ddate = get_gedcom_value("DATE", 2, $this->drec, '', false);
		$this->dplace = get_gedcom_value("PLAC", 2, $this->drec, '', false);
		$this->bplace = get_gedcom_value("PLAC", 2, $this->brec, '', false);
		//-- 2nd record with alternate date (hebrew...)
		$this->brec2 = trim(get_sub_record(1, "1 BIRT", $this->gedrec, 2));
		$this->drec2 = trim(get_sub_record(1, "1 DEAT", $this->gedrec, 2));
		$this->bdate2 = get_gedcom_value("DATE", 2, $this->brec2, '', false);
		$this->ddate2 = get_gedcom_value("DATE", 2, $this->drec2, '', false);
		//-- if no birthdate look for christening
		if (empty($this->bdate)) $this->bdate = get_gedcom_value("CHR:DATE", 1, $this->gedrec, '', false);
		if (empty($this->bplace)) $this->bplace = get_gedcom_value("CHR:PLAC", 1, $this->gedrec, '', false);
		//-- if no death look for burial
		if (empty($this->ddate)) $this->ddate = get_gedcom_value("BURI:DATE", 1, $this->gedrec, '', false);
		if (empty($this->dplace)) $this->dplace = get_gedcom_value("BURI:PLAC", 1, $this->gedrec, '', false);
		//-- if no death estimate from birth
		if (empty($this->ddate) && !empty($this->bdate)) {
			$pdate=new GedcomDate($this->bdate);
			if ($pdate->MinJD() != 0) {
				$pdate=$pdate->AddYears($MAX_ALIVE_AGE, 'BEF');
				$pdate=$pdate->MinDate();
				$this->dest = true;
				$this->ddate = $pdate->Format('@ A O E');
				$this->drec .= "\n2 DATE BEF ".$this->ddate;
			}
			else if (!empty($this->drec)) $this->ddate = $pgv_lang["yes"];
		}
		//-- if no birth estimate from death
		if (empty($this->bdate) && !empty($this->ddate)) {
			$pdate=new GedcomDate($this->ddate);
			if ($pdate->MinJD() != 0) {
				$pdate=$pdate->AddYears(-$MAX_ALIVE_AGE, 'AFT');
				$pdate=$pdate->MinDate();
				$this->best = true;
				$this->bdate = $pdate->Format('@ A O E');
				$this->brec .= "\n2 DATE AFT ".$this->bdate;
			}
			else if (!empty($this->brec)) $this->bdate = $pgv_lang["yes"];
		}
	}
	/**
	 * get birth record
	 * @param boolean $estimate		Provide an estimated birth date for people without a birth record
	 * @return string
	 */
	function getBirthRecord($estimate=true) {
		if (empty($this->brec)) $this->_parseBirthDeath();
		if (!$estimate && $this->best) return get_sub_record(1, "1 BIRT", $this->gedrec);
		return $this->brec;
	}
	/**
	 * get death record
	 * @param boolean $estimate		Provide an estimated death date for people without a death record
	 * @return string
	 */
	function getDeathRecord($estimate=true) {
		if (empty($this->drec)) $this->_parseBirthDeath();
		if (!$estimate && $this->dest) return get_sub_record(1, "1 DEAT", $this->gedrec);
		return $this->drec;
	}
	/**
	 * get birth date
	 * @return string the birth date in the GEDCOM format of '1 JAN 2006'
	 */
	function getBirthDate($estimate = true) {
		global $pgv_lang;
		//if (!$this->disp) return new GedcomDate("({$pgv_lang['private']})");
		if (!$this->disp) return $pgv_lang['private'];
		$this->_parseBirthDeath();
		//if (!$estimate && $this->best) return new GedcomDate('');
		if (!$estimate && $this->best) return '';
		return $this->bdate;
	}

	/**
	 * a function that returns the full GEDCOM line containing the birth date
	 * @return string the date line from the gedcom birth record in the format of '2 DATE 1 JAN 1900'
	 */
	function getGedcomBirthDate(){
		return trim(get_sub_record(2, "2 DATE", $this->getBirthRecord()));
	}

	/**
	 * get the birth place
	 * @return string
	 */
	function getBirthPlace() {
		$this->_parseBirthDeath();
		return $this->bplace;
	}

	/**
	 * get the birth year
	 * @return string
	 */
	function getBirthYear($est = true, $cal = ""){
		// TODO - change the design to use julian days, not gregorian years.
		$bdate = new GedcomDate($this->getBirthDate($est));
		$bdate=$bdate->MinDate();
		if ($cal) $bdate=$bdate->convert_to_cal($cal);
		return $bdate->y;
	}

	/**
	 * get death date
	 * @return string the death date in the GEDCOM format of '1 JAN 2006'
	 */
	function getDeathDate($estimate = true) {
		global $pgv_lang;
		//if (!$this->disp) return new GedcomDate("({$pgv_lang['private']})");
		if (!$this->disp) return $pgv_lang['private'];
		$this->_parseBirthDeath();
		//if (!$estimate && $this->dest) return new GedcomDate('');
		if (!$estimate && $this->dest) return '';
		return $this->ddate;
	}

	/**
	 * a function that returns the full GEDCOM line containing the death date
	 * @return string the death date line from the gedcom in the format of '2 DATE 1 JAN 1900'
	 */
	function getGedcomDeathDate(){
		return trim(get_sub_record(2, "2 DATE", $this->getDeathRecord()));
	}

	/**
	 * get the death place
	 * @return string
	 */
	function getDeathPlace() {
		$this->_parseBirthDeath();
		return $this->dplace;
	}

	/**
	 * get the death year
	 * @return string the year of death
	 */
	function getDeathYear($est = true, $cal = "") {
		// TODO - change the design to use julian days, not gregorian years.
		$ddate = new GedcomDate($this->getDeathDate($est));
		$ddate=$ddate->MinDate();
		if ($cal) $ddate=$ddate->convert_to_cal($cal);
		return $ddate->y;
	}

	/**
	 * get the sex
	 * @return string 	return M, F, or U
	 */
	function getSex() {
		return $this->sex;
	}

	/**
	 * get the person's sex image
	 * @return string 	<img ... />
	 */
	function getSexImage($style='') {
		global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;
		if ($this->getSex()=="M") $s = "sex";
		else if ($this->getSex()=="F") $s = "sexf";
		else $s = "sexn";
		$temp = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES[$s]["small"]."\" alt=\"\" class=\"gender_image\"";
		if (!empty($style)) $temp .= " style=\"$style\"";
		$temp .= " />";
		return $temp;
	}

	/**
	 * set a label for this person
	 * The label can be used when building a list of people
	 * to display the relationship between this person
	 * and the person listed on the page
	 * @param string $label
	 */
	function setLabel($label) {
		$this->label = $label;
	}
	/**
	 * get the label for this person
	 * The label can be used when building a list of people
	 * to display the relationship between this person
	 * and the person listed on the page
	 * @param string $elderdate optional elder sibling birthdate to calculate gap
	 * @param int $counter optional children counter
	 * @return string
	 */
	function getLabel($elderdate="", $counter=0) {
		global $pgv_lang, $TEXT_DIRECTION;
		$label = "";
		$gap = 0;
		if ($elderdate) {
			$p1 = new GedcomDate($elderdate);
			$p2 = new GedcomDate($this->getBirthDate(false));
			if ($p1->MinJD() && $p2->MinJD()) {
				$gap = $p2->MinJD()-$p1->MinJD(); // days
				$label .= "<div class=\"elderdate age $TEXT_DIRECTION\">";
				// warning if negative gap : wrong order
				if ($gap<0 && $counter>0) $label .= "<img alt=\"\" src=\"images/warning.gif\" /> ";
				// warning if gap<6 months
				if ($gap>1 && $gap<180 && $counter>0) $label .= "<img alt=\"\" src=\"images/warning.gif\" /> ";
				// children with same date means twin
				/**if ($gap==0 && $counter>1) {
					if ($this->getSex()=="M") $label .= $pgv_lang["twin_brother"];
					else if ($this->getSex()=="F") $label .= $pgv_lang["twin_sister"];
					else $label .= $pgv_lang["twin"];
				}**/
				// gap in years or months
				$gap = round($gap*12/365.25); // months
				if ($gap>20 or $gap<-20) $label .= round($gap/12)." ".$pgv_lang["years"];
				else if ($gap!=0) $label .= $gap." ".$pgv_lang["months"];
				$label .= "</div>";
			}
		}
		if ($counter) $label .= "<div class=\"".strrev($TEXT_DIRECTION)."\">".$pgv_lang["number_sign"].$counter."</div>";
		$label .= $this->label;
		if ($gap!=0 && $counter<1) $label .= "<br />&nbsp;";
		return $label;
	}
	/**
	 * get family with spouse ids
	 * @return array	array of the FAMS ids
	 */
	function getSpouseFamilyIds() {
		if (!is_null($this->fams)) return $this->fams;
		//$this->fams = find_families_in_record($this->gedrec, "FAMS");
		preg_match_all("/1\s*FAMS\s*@(.+)@/", $this->gedrec, $match);
		$this->fams = $match[1];
		return $this->fams;
	}
	/**
	 * get the families with spouses
	 * @return array	array of Family objects
	 */
	function getSpouseFamilies() {
		global $pgv_lang, $SHOW_LIVING_NAMES;
		if (!is_null($this->spouseFamilies)) return $this->spouseFamilies;
		$fams = $this->getSpouseFamilyIds();
		$families = array();
		foreach($fams as $key=>$famid) {
			if (!empty($famid)) {
				$family = Family::getInstance($famid);
				// only include family if it is displayable by current user
				if (!is_null($family)) {
					if ($SHOW_LIVING_NAMES || $family->disp) $families[$famid] = $family;
				}
				else echo "<span class=\"warning\">".$pgv_lang["unable_to_find_family"]." ".$famid."</span>";
			}
		}
		$this->spouseFamilies = $families;
		return $families;
	}
	
	/**
	 * get the current spouse of this person
	 * The current spouse is defined as the spouse from the latest family.
	 * The latest family is defined as the last family in the GEDCOM record
	 * @return Person  this person's spouse
	 */
	function getCurrentSpouse() {
		$fams = $this->getSpouseFamilies();
		$family = end($fams);
		if (empty($family)) return null;
		return $family->getSpouse($this);
	}

	/**
	 * get the number of children for this person
	 * @return int 	the number of children
	 */
	function getNumberOfChildren() {
		global $indilist, $GEDCOMS, $GEDCOM;
		
		//-- first check for the value in the gedcom record
		$nchi = get_gedcom_value("NCHI", 1, $this->gedrec);
		if ($nchi!="") return ($nchi+0);
		
		//-- check if the value was stored in the cache 
		if (isset($indilist[$this->xref])
				&& $indilist[$this->xref]["gedfile"] == $GEDCOMS[$GEDCOM]['id'] 
				&& isset($indilist[$this->xref]["numchil"])) return ($indilist[$this->xref]["numchil"]+0);
		$nchi=0;
		foreach ($this->getSpouseFamilies() as $famid=>$family) $nchi+=$family->getNumberOfChildren();
		return $nchi;
	}
	/**
	 * get family with child ids
	 * @return array	array of the FAMC ids
	 */
	function getChildFamilyIds() {
		if (!is_null($this->famc)) return $this->famc;
		//$this->famc = find_families_in_record($this->gedrec, "FAMC");
		preg_match_all("/1\s*FAMC\s*@(.+)@/", $this->gedrec, $match);
		$this->famc = $match[1];
		return $this->famc;
	}
	/**
	 * get an array of families with parents
	 * @return array	array of Family objects indexed by family id
	 */
	function getChildFamilies() {
		global $pgv_lang, $SHOW_LIVING_NAMES;
		if (!is_null($this->childFamilies)) return $this->childFamilies;
		$fams = $this->getChildFamilyIds();
		$families = array();
		foreach($fams as $key=>$famid) {
			if (!empty($famid)) {
				$family = Family::getInstance($famid);
				// only include family if it is displayable by current user
				if (!is_null($family)) {
					if ($SHOW_LIVING_NAMES || $family->disp) $families[$famid] = $family;
				}
				else echo "<span class=\"warning\">".$pgv_lang["unable_to_find_family"]." ".$famid."</span>";
			}
		}
		$this->childFamilies = $families;
		return $families;
	}
	/**
	 * get primary family with parents
	 * @return Family object
	 */
	function getPrimaryChildFamily() {
		$families = $this->getChildFamilies();
		if (count($families)==0) return null;
		if (count($families)==1) return reset($families);
		// If there is more than one FAMC record, choose the preferred parents:
		// a) records with "2 _PRIMARY"
		foreach ($families as $famid=>$fam)
		if (preg_match("/\n\s*1\s+FAMC\s+@{$famid}@\s*\n(\s*[2-9].*\n)*(\s*2\s+_PRIMARY Y\b)/i", $this->gedrec)) return $fam;
		// b) records with "2 PEDI birt"
		foreach ($families as $famid=>$fam)
		if (preg_match("/\n\s*1\s+FAMC\s+@{$famid}@\s*\n(\s*[2-9].*\n)*(\s*2\s+PEDI\s+birth?\b)/i", $this->gedrec)) return $fam;
		// c) records with no "2 PEDI"
		foreach ($families as $famid=>$fam)
		if (!preg_match("/\n\s*1\s+FAMC\s+@{$famid}@\s*\n(\s*[2-9].*\n)*(\s*2\s+PEDI\b)/i", $this->gedrec)) return $fam;
		// d) any record
		return reset($families);
	}
	/**
	 * get family with child pedigree
	 * @return string FAMC:PEDI value [ adopted | birth | foster | sealing ]
	 */
	function getChildFamilyPedigree($famid) {
		$subrec = get_sub_record(1, "1 FAMC @".$famid."@", $this->gedrec);
		$pedi = get_gedcom_value("PEDI", 2, $subrec, '', false);
		if (strpos($pedi, "birt")!==false) return ""; // birth=default => return an empty string
		return $pedi;
	}
	/**
	 * get the step families from the parents
	 * @return array	array of Family objects
	 */
	function getStepFamilies() {
		$families = array();
		$fams = $this->getChildFamilies();
		foreach($fams as $key=>$family) {
			if (!is_null($family)) {
				$father = $family->getHusband();
				if (!is_null($father)) {
					$pfams = $father->getSpouseFamilies();
					foreach($pfams as $key1=>$fam) {
						if (!is_null($fam) && !isset($fams[$key1]) && ($fam->getNumberOfChildren() > 0)) $families[$key1] = $fam;
					}
				}
				$mother = $family->getWife();
				if (!is_null($mother)) {
					$pfams = $mother->getSpouseFamilies();
					foreach($pfams as $key1=>$fam) {
						if (!is_null($fam) && !isset($fams[$key1]) && ($fam->getNumberOfChildren() > 0)) $families[$key1] = $fam;
					}
				}
			}
		}
		return $families;
	}
	/**
	 * get global facts
	 * @return array
	 */
	function getGlobalFacts() {
		$this->parseFacts();
		return $this->globalfacts;
	}
	/**
	 * get indi facts
	 * @return array
	 */
	function getIndiFacts() {
		$this->parseFacts();
		return $this->indifacts;
	}
	
	/**
	 * get other facts
	 * @return array
	 */
	function getOtherFacts() {
		$this->parseFacts();
		return $this->otherfacts;
	}
	/**
	 * get the correct label for a family
	 * @param Family $family		the family to get the label for
	 * @return string
	 */
	function getChildFamilyLabel(&$family) {
		global $pgv_lang;
		if (!is_null($family)) {
			$famlink = get_sub_record(1, "1 FAMC @".$family->getXref()."@", $this->gedrec);
			$ft = preg_match("/2 PEDI (.*)/", $famlink, $fmatch);
			if ($ft>0) {
				$temp = trim($fmatch[1]);
				if ($temp!="birth" && isset($pgv_lang[$temp])) return $pgv_lang[$temp]." ";
			}
		}
		return $pgv_lang["as_child"];
	}
	/**
	 * get the correct label for a step family
	 * @param Family $family		the family to get the label for
	 * @return string
	 */
	function getStepFamilyLabel(&$family) {
		global $pgv_lang;
		$label = "Unknown Family";
		if (is_null($family)) return $label;
		$childfams = $this->getChildFamilies();
		$mother = $family->getWife();
		$father = $family->getHusband();
		foreach($childfams as $key=>$fam) {
			if (!$fam->equals($family)) {
				$wife = $fam->getWife();
				$husb = $fam->getHusband();
				if ((is_null($husb) || !$husb->equals($father)) && (is_null($wife)||$wife->equals($mother))) {
					if ($mother->getSex()=="M") $label = $pgv_lang["fathers_family_with"];
					else $label = $pgv_lang["mothers_family_with"];
					if (!is_null($father)) $label .= $father->getName();
					else $label .= $pgv_lang["unknown"];
				}
				else if ((is_null($wife) || !$wife->equals($mother)) && (is_null($husb)||$husb->equals($father))) {
					if ($father->getSex()=="F") $label = $pgv_lang["mothers_family_with"];
					else $label = $pgv_lang["fathers_family_with"];
					if (!is_null($mother)) $label .= $mother->getName();
					else $label .= $pgv_lang["unknown"];
				}
				if ($label!="Unknown Family") return $label;
			}
		}
		return $label;
	}
	/**
	 * get the correct label for a family
	 * @param Family $family		the family to get the label for
	 * @return string
	 */
	function getSpouseFamilyLabel(&$family) {
		global $pgv_lang;

		$label = $pgv_lang["family_with"] . " ";
		if (is_null($family)) return $label . $pgv_lang["unknown"];
		$famlink = get_sub_record(1, "1 FAMS @".$family->getXref()."@", $this->gedrec);
		$ft = preg_match("/2 PEDI (.*)/", $famlink, $fmatch);
		if ($ft>0) {
			$temp = trim($fmatch[1]);
			if (isset($pgv_lang[$temp])) $label = $pgv_lang[$temp]." ";
		}
		$husb = $family->getHusband();
		$wife = $family->getWife();
		if ($this->equals($husb)) {
			if (!is_null($wife)) $label .= $wife->getName();
			else $label .= $pgv_lang["unknown"];
		}
		else {
			if (!is_null($husb)) $label .= $husb->getName();
			else $label .= $pgv_lang["unknown"];
		}
		return $label;
	}
	/**
	 * get updated Person
	 * If there is an updated individual record in the gedcom file
	 * return a new person object for it
	 * @return Person
	 */
	function &getUpdatedPerson() {
		global $GEDCOM, $pgv_changes, $GEDCOMS;
		if ($this->changed) return null;
		if (PGV_USER_CAN_EDIT && $this->disp) {
			if (isset($pgv_changes[$this->xref."_".$GEDCOM])) {
				$newrec = find_updated_record($this->xref);
				if (!empty($newrec)) {
					$new = new Person($newrec);
					$new->setChanged(true);
					return $new;
				}
			}
		}
		return null;
	}
	/**
	 * Parse the facts from the individual record
	 */
	function parseFacts() {
		global $nonfacts;
		//-- only run this function once
		if ($this->facts_parsed) return;
		//-- don't run this function if privacy does not allow viewing of details
		if (!$this->canDisplayDetails()) return;
		$this->facts_parsed = true;
		//-- must trim the record here because the record is trimmed in edit and it could mess up line numbers
		$this->gedrec = trim($this->gedrec);
		//-- find all the fact information
		$indilines = split("\n", $this->gedrec);   // -- find the number of lines in the individuals record
		$lct = count($indilines);
		$factrec = "";	 // -- complete fact record
		$line = "";   // -- temporary line buffer
		$f=0;	   // -- counter
		$o = 0;
		$g = 0;
		$m = 0;
		$linenum=1;
		$sexfound = false;
		for($i=1; $i<=$lct; $i++) {
		   if ($i<$lct) $line = preg_replace("/\r/", "", $indilines[$i]);
		   else $line=" ";
		   if (empty($line)) $line=" ";
		   //print "line:".$line."<br />";
		   if (($i==$lct)||($line{0}==1)) {
				  $ft = preg_match("/1\s(\w+)(.*)/", $factrec, $match);
				  if ($ft>0) $fact = $match[1];
				  else $fact="";
				  $fact = trim($fact);
				  // -- handle special name fact case
				  if ($fact=="NAME") {
						 $this->globalfacts[$g] = array($linenum, $factrec);
						 $g++;
				  }
				  // -- handle special source fact case
				  else if ($fact=="SOUR") {
						 $this->otherfacts[$o] = array($linenum, $factrec);
						 $o++;
				  }
				  // -- handle special note fact case
				  else if ($fact=="NOTE") {
						 $this->otherfacts[$o] = array($linenum, $factrec);
						 $o++;
				  }
				  // -- handle special sex case
				  else if ($fact=="SEX") {
						 $this->globalfacts[$g] = array($linenum, $factrec);
						 $g++;
						 $sexfound = true;
				  }
				  else if ($fact=="OBJE") {}
				  else if (!isset($nonfacts) || !in_array($fact, $nonfacts)) {
						 $thisfact = array($linenum, $factrec);
						 if (preg_match("/@(F.*)@/", $thisfact[1], $match)>0) {
							// a family ref in fact, only include it if family is displayable
							if ( displayDetailsByID( $match[1], "FAM" ) ) {
								$this->indifacts[$f]=$thisfact;
						 $f++;
				  }
						 }
						 else {
							$this->indifacts[$f]=$thisfact;
							$f++;
						 }
				  }
				  $factrec = $line;
				  $linenum = $i;
		   }
		   else $factrec .= "\n".$line;
		}
		//-- add a new sex fact if one was not found
		if (!$sexfound) {
			$this->globalfacts[$g] = array('new', "1 SEX U");
			$g++;
		}
	}
	/**
	 * add facts from the family record
	 * @param boolean $otherfacts	whether or not to add other related facts such as parents facts, associated people facts, and historical facts
	 */
	function add_family_facts($otherfacts = true) {
		global $GEDCOM, $nonfacts, $nonfamfacts;

		if (!isset($nonfacts)) $nonfacts = array();
		if (!isset($nonfamfacts)) $nonfamfacts = array();

		if (!$this->canDisplayDetails()) return;
		$this->parseFacts();
		//-- Get the facts from the family with spouse (FAMS)
		$fams = $this->getSpouseFamilies();
		foreach ($fams as $famid=>$family) {
			if (is_null($family)) continue;
			$famrec = $family->getGedcomRecord();
			$updfamily = $family->getUpdatedFamily(); //-- updated family ?
			if ($updfamily) $famrec = $updfamily->getGedcomRecord();
			$spouse = $family->getSpouse($this);
			$indilines = split("\n", $famrec);	 // -- find the number of lines in the record
			$lct = count($indilines);
			$factrec = "";	 // -- complete fact record
			$line = "";   // -- temporary line buffer
			$linenum = 0;
			$hasdiv = false;
			for($i=1; $i<=$lct; $i++) {
				if ($i<$lct) $line = preg_replace("/\r/", "", $indilines[$i]);
				else $line=" ";
				if (empty($line)) $line=" ";
				if (($i==$lct)||($line{0}==1)) {
					$ft = preg_match("/1\s(\w+)(.*)/", $factrec, $match);
					if ($ft>0) $fact = $match[1];
					else $fact="";
					$fact = trim($fact);
					if ($fact=="DIV") $hasdiv = true;
					$factrec = trim($factrec);
					if (!empty($factrec)) {
					// -- handle special source fact case
					if (($fact!="SOUR") && ($fact!="OBJE") && ($fact!="NOTE") && ($fact!="CHAN") && ($fact!="_UID") && ($fact!="RIN")) {
						if ((!in_array($fact, $nonfacts))&&(!in_array($fact, $nonfamfacts))) {
							if (!is_null($spouse)) $factrec.="\r\n1 _PGVS @".$spouse->getXref()."@";
							$factrec.="\r\n1 _PGVFS @$famid@\r\n";
							if ($updfamily) $factrec .= "PGV_NEW\r\n";
							$this->indifacts[]=array($linenum, $factrec);
						}
					}
					else if ($fact=="OBJE") {
						if (!is_null($spouse)) $factrec.="\r\n1 _PGVS @".$spouse->getXref()."@";
						$factrec.="\r\n1 _PGVFS @$famid@\r\n";
						$this->otherfacts[]=array($linenum, $factrec);
					}
					}
					$factrec = $line;
					$linenum = $i;
				}
				else $factrec .= "\n".$line;
			}
			if($otherfacts){
				if (!$hasdiv && !is_null($spouse)) $this->add_spouse_facts($spouse, $famrec);
				$this->add_children_facts($family);
			}
		}
		if($otherfacts){
			$this->add_parents_facts($this);
			$this->add_historical_facts();
			$this->add_asso_facts($this);
		}
	
	}
	/**
	 * add parents events to individual facts array
	 *
	 * bdate = indi birth date record
	 * ddate = indi death date record
	 *
	 * @param Person $person	Person
	 * @param int $sosa		2=father 3=mother ...
	 * @return records added to indifacts array
	 */
	function add_parents_facts(&$person, $sosa=1) {
		global $SHOW_RELATIVES_EVENTS, $factarray;

		if (is_null($person)) return;
		if (!$SHOW_RELATIVES_EVENTS) return;
		if ($sosa>7) return; // sosa max for recursive call
		if (empty($this->brec)) $this->_parseBirthDeath();
		$fams = $person->getChildFamilies();
		//-- find family as child
		foreach($fams as $famid=>$family) {
			// add father death
			$spouse = $family->getHusband();
			if ($sosa==1) $fact="_DEAT_FATH"; else if ($sosa<4) $fact="_DEAT_GPAR"; else $fact="_DEAT_GGPA";
			if ($spouse && strstr($SHOW_RELATIVES_EVENTS, $fact)) {
				$srec = $spouse->getDeathRecord(false);
				$sdate = get_sub_record(2, "2 DATE", $srec);
				if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
					$factrec = "1 ".$fact;
					if (strstr($srec, "1 BURI")) $factrec .= " ".$factarray["BURI"];
					$factrec .= "\n".trim($sdate);
					if (!showFact("DEAT", $spouse->getXref())) $factrec .= "\n2 RESN privacy";
					$factrec .= "\n2 ASSO @".$spouse->getXref()."@";
					$factrec .= "\n3 RELA sosa_".($sosa*2);
					// recorded as ASSOciate ? [ 1690092 ]
					$factrec .= "\n". get_sub_record(2, "2 ASSO @".$this->xref."@", $srec);
					$this->indifacts[]=array(0, $factrec);
				}
			}
			if ($sosa==1) $this->add_stepsiblings_facts($spouse, $famid); // stepsiblings with father
			$this->add_parents_facts($spouse, $sosa*2); // recursive call for father ancestors
			// add mother death
			$spouse = $family->getWife();
			if ($sosa==1) $fact="_DEAT_MOTH"; else if ($sosa<4) $fact="_DEAT_GPAR"; else $fact="_DEAT_GGPA";
			if ($spouse and strstr($SHOW_RELATIVES_EVENTS, $fact)) {
				$srec = $spouse->getDeathRecord(false);
				$sdate = get_sub_record(2, "2 DATE", $srec);
				if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
					$factrec = "1 ".$fact;
					if (strstr($srec, "1 BURI")) $factrec .= " ".$factarray["BURI"];
					$factrec .= "\n".trim($sdate);
					if (!showFact("DEAT", $spouse->getXref())) $factrec .= "\n2 RESN privacy";
					$factrec .= "\n2 ASSO @".$spouse->getXref()."@";
					$factrec .= "\n3 RELA sosa_".($sosa*2+1);
					// recorded as ASSOciate ? [ 1690092 ]
					$factrec .= "\n". get_sub_record(2, "2 ASSO @".$this->xref."@", $srec);
					$this->indifacts[]=array(0, $factrec);
				}
			}
			if ($sosa==1) $this->add_stepsiblings_facts($spouse, $famid); // stepsiblings with mother
			$this->add_parents_facts($spouse, $sosa*2+1); // recursive call for mother ancestors
			if ($sosa>3) return;
			// add father/mother marriages
			$parents[] = $family->getHusband();
			$parents[] = $family->getWife();
			foreach ($parents as $indexval=>$parent) {
				if (is_null($parent)) continue;
				if ($sosa==1) {
					if ($parent->getSex()=='M') {
						$fact="_MARR_FATH";
						$rela="father";
					} else {
						$fact="_MARR_MOTH";
						$rela="mother";
					}
				} else {
					// Not currently used.  Do we want separate grandmother/grandfather events?
					$fact="_MARR_GPAR";
					$rela="grandparent";
				}
				if (strstr($SHOW_RELATIVES_EVENTS, $fact)) {
					$sfamids = $parent->getSpouseFamilies();
					foreach ($sfamids as $sfamid=>$sfamily) {
						if ($sfamid==$famid && $rela=="mother") continue; // show current family marriage only for father
						$srec = $sfamily->getMarriageRecord();
						$sdate = get_sub_record(2, "2 DATE", $srec);
						if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
							$factrec = "1 ".$fact;
							$factrec .= "\n".trim($sdate);
							if (!showFact("MARR", $sfamid)) $factrec .= "\n2 RESN privacy";
							$factrec .= "\n2 ASSO @".$parent->getXref()."@";
							$factrec .= "\n3 RELA ".$rela;
							if ($rela=="father") $rela2="stepmom";
							else $rela2="stepdad";
							if ($sfamid==$famid) $rela2="mother";
							$factrec .= "\n2 ASSO @".$sfamily->getSpouseId($parent->getXref())."@";
							$factrec .= "\n3 RELA ".$rela2;
							$this->indifacts[]=array(0, $factrec);
						}
					}
				}
			}
			//-- find siblings
			$this->add_children_facts($family,$sosa, $person->getXref());
		}
	}
	/**
	 * add children events to individual facts array
	 *
	 * bdate = indi birth date record
	 * ddate = indi death date record
	 *
	 * @param string $family	Family object
	 * @param string $option Family level indicator
	 * @param string $except	Gedcom childid already processed
	 * @return records added to indifacts array
	 */
	function add_children_facts(&$family, $option="_CHIL", $except="") {
		global $SHOW_RELATIVES_EVENTS, $factarray;

		if ($option=="1") $option="_SIBL";
		if ($option=="2") $option="_FSIB";
		if ($option=="3") $option="_MSIB";
		if (strstr($SHOW_RELATIVES_EVENTS, $option)===false) return;
		if (empty($this->brec)) $this->_parseBirthDeath();

		$children = $family->getChildren();
		foreach($children as $key=>$child) {
			$spid = $child->getXref();
			if ($spid!=$except) {
				$childrec =$child->getGedcomRecord();
				$sex = $child->getSex();
				// children
				$rela="child";
				if ($sex=="F") $rela="daughter";
				if ($sex=="M") $rela="son";
				// grandchildren
				if ($option=="_GCHI") {
					$rela="grandchild";
					if ($sex=="F") $rela="granddaughter";
					if ($sex=="M") $rela="grandson";
				}
				// great-grandchildren
				if ($option=="_GGCH") {
					$rela="greatgrandchild";
					if ($sex=="F") $rela="greatgranddaughter";
					if ($sex=="M") $rela="greatgrandson";
				}
				// stepsiblings
				if ($option=="_HSIB") {
					$rela="halfsibling";
					if ($sex=="F") $rela="halfsister";
					if ($sex=="M") $rela="halfbrother";
				}
				// siblings
				if ($option=="_SIBL") {
					$rela="sibling";
					if ($sex=="F") $rela="sister";
					if ($sex=="M") $rela="brother";
				}
				// uncles/aunts
				if ($option=="_FSIB" or $option=="_MSIB") {
					$rela="uncle/aunt";
					if ($sex=="F") $rela="aunt";
					if ($sex=="M") $rela="uncle";
				}
				// firstcousins
				if ($option=="_COUS") {
					$rela="firstcousin";
					if ($sex=="F") $rela="femalecousin";
					if ($sex=="M") $rela="malecousin";
				}
				// nephew/niece
				if ($option=="_NEPH") {
					$rela="nephew/niece";
					if ($sex=="F") $rela="niece";
					if ($sex=="M") $rela="nephew";
				}
				// add child birth
				$fact = "_BIRT".$option;
				if (strstr($SHOW_RELATIVES_EVENTS, $fact)) {
					$srec = get_sub_record(1, "1 BIRT", $childrec);
					if (!$srec) $srec = get_sub_record(1, "1 CHR", $childrec);
					$sdate = get_sub_record(2, "2 DATE", $srec);
					if ($fact=="_BIRT_CHIL" or // always print child's birth event
						(compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0)
						) {
						$factrec = "1 ".$fact;
						if (strstr($srec, "1 CHR")) $factrec .= " ".$factarray["CHR"];
						$factrec .= "\n".trim($sdate);
						if (!showFact("BIRT", $spid)) $factrec .= "\n2 RESN privacy";
						$factrec .= "\n2 ASSO @".$spid."@";
						$factrec .= "\n3 RELA ".$rela;
						// add parents on grandchildren, cousin or nephew's birth
						if ($option=="_GCHI" or $option=="_GGCH" or $option=="_COUS" or $option=="_NEPH") {
							if ($family->getHusbId()) {
								$factrec .= "\n2 ASSO @".$family->getHusbId()."@";
								$factrec .= "\n3 RELA father";
							}
							if ($family->getWifeId()) {
								$factrec .= "\n2 ASSO @".$family->getWifeId()."@";
								$factrec .= "\n3 RELA mother";
							}
						}
						// recorded as ASSOciate ? [ 1690092 ]
						$factrec .= "\n". get_sub_record(2, "2 ASSO @".$this->xref."@", $srec);
						$this->indifacts[]=array(0, $factrec);
					}
				}
				// add child death
				$fact = "_DEAT".$option;
				if (strstr($SHOW_RELATIVES_EVENTS, $fact)) {
					$srec = get_sub_record(1, "1 DEAT", $childrec);
					if (!$srec) $srec = get_sub_record(1, "1 BURI", $childrec);
					$sdate = get_sub_record(2, "2 DATE", $srec);
					if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
						$factrec = "1 ".$fact;
						if (strstr($srec, "1 BURI")) $factrec .= " ".$factarray["BURI"];
						$factrec .= "\n".trim($sdate);
						if (!showFact("DEAT", $spid)) $factrec .= "\n2 RESN privacy";
						$factrec .= "\n2 ASSO @".$spid."@";
						$factrec .= "\n3 RELA ".$rela;
						// recorded as ASSOciate ? [ 1690092 ]
						$factrec .= "\n". get_sub_record(2, "2 ASSO @".$this->xref."@", $srec);
						$this->indifacts[]=array(0, $factrec);
					}
				}
				// add child marriage
				$fact = "_MARR".$option;
				if (strstr($SHOW_RELATIVES_EVENTS, $fact)) {
					foreach($child->getSpouseFamilies() as $sfamid=>$sfamily) {
						$childrec = $sfamily->getGedcomRecord();
						$srec = get_sub_record(1, "1 MARR", $childrec);
						$sdate = get_sub_record(2, "2 DATE", $srec);
						if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
							$factrec = "1 ".$fact;
							$factrec .= "\n".trim($sdate);
							if (!showFact("MARR", $sfamid)) $factrec .= "\n2 RESN privacy";
							$factrec .= "\n2 ASSO @".$spid."@";
							$factrec .= "\n3 RELA ".$rela;
							if ($rela=="son") $rela2="daughter_in_law";
							else if ($rela=="daughter") $rela2="son_in_law";
							else if ($rela=="brother" or $rela=="halfbrother") $rela2="sister_in_law";
							else if ($rela=="sister" or $rela=="halfsister") $rela2="brother_in_law";
							else if ($rela=="uncle") $rela2="aunt";
							else if ($rela=="aunt") $rela2="uncle";
							else if (strstr($rela, "cousin")) $rela2="cousin_in_law";
							else $rela2="spouse";
							$factrec .= "\n2 ASSO @".$sfamily->getSpouseId($spid)."@";
							$factrec .= "\n3 RELA ".$rela2;
							// recorded as ASSOciate ? [ 1690092 ]
							$factrec .= "\n". get_sub_record(2, "2 ASSO @".$this->xref."@", $srec);
							$this->indifacts[]=array(0, $factrec);
						}
					}
				}
				// add children of children = grandchildren
				if ($option=="_CHIL") {
					foreach($child->getSpouseFamilies() as $sfamid=>$sfamily) {
						$this->add_children_facts($sfamily, "_GCHI");
					}
				}
				// add children of grandchildren = great-grandchildren
				if ($option=="_GCHI") {
					foreach($child->getSpouseFamilies() as $sfamid=>$sfamily) {
						$this->add_children_facts($sfamily, "_GGCH");
					}
				}
				// add children of siblings = nephew/niece
				if ($option=="_SIBL") {
					foreach($child->getSpouseFamilies() as $sfamid=>$sfamily) {
						$this->add_children_facts($sfamily, "_NEPH");
					}
				}
				// add children of uncle/aunt = firstcousins
				if ($option=="_FSIB" or $option=="_MSIB") {
					foreach($child->getSpouseFamilies() as $sfamid=>$sfamily) {
						$this->add_children_facts($sfamily, "_COUS");
					}
				}
			}
		}
	}
	/**
	 * add spouse events to individual facts array
	 *
	 * bdate = indi birth date record
	 * ddate = indi death date record
	 *
	 * @param string $spouse	Person object
	 * @param string $famrec	family Gedcom record
	 * @return records added to indifacts array
	 */
	function add_spouse_facts(&$spouse, $famrec="") {
		global $SHOW_RELATIVES_EVENTS, $factarray;

		// do not show if divorced
		if (strstr($famrec, "1 DIV")) return;
		if (empty($this->brec)) $this->_parseBirthDeath();
		// add spouse death
		$fact = "_DEAT_SPOU";
		if ($spouse && strstr($SHOW_RELATIVES_EVENTS, $fact)) {
			$srec = $spouse->getDeathRecord(false);
			if (!$srec) $srec = get_sub_record(1, "1 BURI", $spouse->getGedcomRecord());
			$sdate=get_sub_record(2, "2 DATE", $srec);
			if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
				$factrec = "1 ".$fact;
				if (strstr($srec, "1 BURI")) $factrec .= " ".$factarray["BURI"];
				$factrec .= "\n".trim($sdate);
				if (!showFact("DEAT", $spouse->getXref())) $factrec .= "\n2 RESN privacy";
				$factrec .= "\n2 ASSO @".$spouse->getXref()."@";
				$factrec .= "\n3 RELA spouse";
				// recorded as ASSOciate ? [ 1690092 ]
				$factrec .= "\n". get_sub_record(2, "2 ASSO @".$this->xref."@", $srec);
				$this->indifacts[]=array(0, $factrec);
			}
		}
	}
	/**
	 * add step-siblings events to individual facts array
	 *
	 * @param Person $spouse	Father or mother Gedcom id
	 * @param string $except	Gedcom famid already processed
	 * @return records added to indifacts array
	 */
	function add_stepsiblings_facts(&$spouse, $except="") {
		if (is_null($spouse)) return;
		foreach ($spouse->getSpouseFamilies() as $famid=>$family) {
			// process children from all step families
			if ($famid!=$except) $this->add_children_facts($family, "step");
		}
	}
	/**
	 * add historical events to individual facts array
	 *
	 * @return records added to indifacts array
	 *
	 * Historical facts are imported from optional language file : histo.xx.php
	 * where xx is language code
	 * This file should contain records similar to :
	 *
	 *	$histo[]="1 EVEN\n2 TYPE History\n2 DATE 11 NOV 1918\n2 NOTE WW1 Armistice";
	 *	$histo[]="1 EVEN\n2 TYPE History\n2 DATE 8 MAY 1945\n2 NOTE WW2 Armistice";
	 * etc...
	 *
	 */
	function add_historical_facts() {
		global $LANGUAGE, $lang_short_cut;
		global $SHOW_RELATIVES_EVENTS;
		if (!$SHOW_RELATIVES_EVENTS) return;
		if (empty($this->bdate)) return; //FIXME Not sure of the logic, but if this exists but was not parsed, it will incorrectly return empty. Should probably call getBirthDate or getGedcomBirthDate()
		if (empty($this->brec)) $this->_parseBirthDeath();
		$histo=array();
		if (file_exists("languages/histo.".$lang_short_cut[$LANGUAGE].".php")) {
			@include("languages/histo.".$lang_short_cut[$LANGUAGE].".php");
		}
		foreach ($histo as $indexval=>$hrec) {
			$sdate = get_sub_record(2, "2 DATE", $hrec);
			if (compare_facts_date($this->getGedcomBirthDate(), $sdate)<0 && compare_facts_date($sdate, $this->getGedcomDeathDate())<0) {
				$this->indifacts[]=array(-1, $hrec);
			}
		}
	}
	/**
	 * add events where pid is an ASSOciate
	 *
	 * @param Person $person	Gedcom id
	 * @return records added to indifacts array
	 *
	 */
	function add_asso_facts(&$person) {
		global $factarray, $pgv_lang;
		global $assolist, $GEDCOM, $GEDCOMS;
		if (!function_exists("get_asso_list")) return;
		get_asso_list('all', $person->getXref());
		$apid = $person->getXref()."[".$GEDCOMS[$GEDCOM]["id"]."]";
		// associates exist ?
		if (isset($assolist[$apid])) {
			// if so, print all indi's where the indi is associated to
			foreach($assolist[$apid] as $indexval => $asso) {
				$ct = preg_match("/0 @(.*)@ (.*)/", $asso["gedcom"], $match);
				$rid = $match[1];
				$typ = $match[2];
				// search for matching fact
				for ($i=1; ; $i++) {
					$srec = get_sub_record(1, "1 ", $asso["gedcom"], $i);
					if (empty($srec)) break;
					$arec = get_sub_record(2, "2 ASSO @".$person->getXref()."@", $srec);
					if ($arec) {
						$temp = preg_match("/^\d (\w*)/", $srec, $factname);
						$fact = $factname[1];
						$label = $fact;
						if ($fact=="EVEN") {
							$trec = get_sub_record(2, "2 TYPE ", $srec);
							if ($trec) $label = trim(substr($trec, 7));
						}
						if (isset($factarray[$label])) $label = strip_tags($factarray[$label]);
						$sdate = get_sub_record(2, "2 DATE", $srec);
						// relationship ?
						$rrec = get_sub_record(3, "3 RELA", $arec);
						$rela = trim(substr($rrec, 7));
						if (empty($rela)) $rela = "ASSO";
						if (isset($pgv_lang[strtolower($rela)])) $rela = $pgv_lang[strtolower($rela)];
						else if (isset($factarray[$rela])) $rela = $factarray[$rela];
						// add an event record
						$factrec = "1 EVEN\n2 TYPE ".$label."<br/>[ <span class=\"details_label\">".$rela."</span> ]";
						$factrec .= "\n".trim($sdate);
						if (!showFact($fact, $rid)) $factrec .= "\n2 RESN privacy";
						if ($typ=='FAM') {
							$famrec = find_family_record($rid);
							if ($famrec) {
								$parents = find_parents_in_record($famrec);
								if ($parents["HUSB"]) $factrec .= "\n2 ASSO @".$parents["HUSB"]."@"; //\n3 RELA ".$factarray[$fact];
								if ($parents["WIFE"]) $factrec .= "\n2 ASSO @".$parents["WIFE"]."@"; //\n3 RELA ".$factarray[$fact];
							}
						}
						else $factrec .= "\n2 ASSO @".$rid."@\n3 RELA ".$label;
						//$factrec .= "\n3 NOTE ".$rela;
						$factrec .= "\n2 ASSO @".$person->getXref()."@\n3 RELA ".$rela;
						// check if this fact already exists in the list
						$found = false;
						if ($sdate) foreach($this->indifacts as $k=>$v) {
							if (strpos($v[1], trim($sdate))
							&& strpos($v[1], "2 ASSO @".$person->getXref()."@")) {
								$found = true;
								break;
							}
						}
						if (!$found) $this->indifacts[] = array(0, $factrec);
					}
				}
			}
		}
	}
	/**
	 * Merge the facts from another Person object into this object
	 * for generating a diff view
	 * @param Person $diff	the person to compare facts with
	 */
	function diffMerge(&$diff) {
		if (is_null($diff)) return;
		$this->parseFacts();
		$diff->parseFacts();
		//-- loop through new facts and add them to the list if they are any changes
		//-- compare new and old facts of the Personal Fact and Details tab 1
		for($i=0; $i<count($this->indifacts); $i++) {
			$found=false;
			foreach($diff->indifacts as $indexval => $newfact) {
				//-- remove all whitespace for comparison
				$tnf = preg_replace("/\s+/", " ", $newfact[1]);
				$tif = preg_replace("/\s+/", " ", $this->indifacts[$i][1]);
				if ($tnf==$tif) {
					$this->indifacts[$i] = $newfact;				//-- make sure the correct linenumber is used
					$found=true;
					break;
				}
			}
			//-- fact was deleted?
			if (!$found) {
				$this->indifacts[$i][1].="\r\nPGV_OLD\r\n";
			}
		}
		//-- check for any new facts being added
		foreach($diff->indifacts as $indexval => $newfact) {
			$found=false;
			foreach($this->indifacts as $indexval => $fact) {
				$tif = preg_replace("/\s+/", " ", $fact[1]);
				$tnf = preg_replace("/\s+/", " ", $newfact[1]);
				if ($tif==$tnf) {
					$found=true;
					break;
				}
			}
			if (!$found) {
				$newfact[1].="\r\nPGV_NEW\r\n";
				$this->indifacts[]=$newfact;
			}
		}
		//-- compare new and old facts of the Notes Sources and Media tab 2
		for($i=0; $i<count($this->otherfacts); $i++) {
			$found=false;
			foreach($diff->otherfacts as $indexval => $newfact) {
				if (trim($newfact[1])==trim($this->otherfacts[$i][1])) {
					$this->otherfacts[$i][0] = $newfact[0];				  //-- make sure the correct linenumber is used
					$found=true;
					break;
				}
			}
			if (!$found) {
				$this->otherfacts[$i][1].="\r\nPGV_OLD\r\n";
			}
		}
		foreach($diff->otherfacts as $indexval => $newfact) {
			$found=false;
			foreach($this->otherfacts as $indexval => $fact) {
				if (trim($fact[1])==trim($newfact[1])) {
					$found=true;
					break;
				}
			}
			if (!$found) {
				$newfact[1].="\r\nPGV_NEW\r\n";
				$this->otherfacts[]=$newfact;
			}
		}
	
		//-- compare new and old facts of the Global facts
		for($i=0; $i<count($this->globalfacts); $i++) {
			$found=false;
			foreach($diff->globalfacts as $indexval => $newfact) {
				if (trim($newfact[1])==trim($this->globalfacts[$i][1])) {
					$this->globalfacts[$i][0] = $newfact[0]; 			   //-- make sure the correct linenumber is used
					$found=true;
					break;
				}
			}
			if (!$found) {
				$this->globalfacts[$i][1].="\r\nPGV_OLD\r\n";
			}
		}
		foreach($diff->globalfacts as $indexval => $newfact) {
			$found=false;
			foreach($this->globalfacts as $indexval => $fact) {
				if (trim($fact[1])==trim($newfact[1])) {
					$found=true;
					break;
				}
			}
			if (!$found) {
				$newfact[1].="\r\nPGV_NEW\r\n";
				$this->globalfacts[]=$newfact;
			}
		}
		$newfamids = $diff->getChildFamilyIds();
		if (is_null($this->famc)) $this->getChildFamilyIds();
		foreach($newfamids as $key=>$id) {
			if (!in_array($id, $this->famc)) $this->famc[]=$id;
		}

		$newfamids = $diff->getSpouseFamilyIds();
		if (is_null($this->fams)) $this->getSpouseFamilyIds();
		foreach($newfamids as $key=>$id) {
			if (!in_array($id, $this->fams)) $this->fams[]=$id;
		}
	}

	/**
	 * get primary parents names for this person
	 * @param string $classname optional css class
	 * @param string $display optional css style display
	 * @return string a div block with father & mother names
	 */
	function getPrimaryParentsNames($classname="", $display="") {
		global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;
		$fam = $this->getPrimaryChildFamily();
		if (!$fam) return "";
		$txt = "<div";
		if ($classname) $txt .= " class=\"$classname\"";
		if ($display) $txt .= " style=\"display:$display\"";
		$txt .= ">";
		$husb = $fam->getHusband();
		if ($husb) $txt .= $pgv_lang["father"].": ".PrintReady($husb->getSortableName())."<br />";
		$wife = $fam->getWife();
		if ($wife) $txt .= $pgv_lang["mother"].": ".PrintReady($wife->getSortableName());
		$txt .= "</div>";
		return $txt;
	}

	/**
	 * get the URL to link to this person
	 * @string a url that can be used to link to this person
	 */
	function getLinkUrl() {
		return parent::getLinkUrl('individual.php?pid=');
	}
}
?>
