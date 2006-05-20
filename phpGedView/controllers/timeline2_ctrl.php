<?php

/**
 * Controller for the timeline chart
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005	PGV Development Team
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
 * @subpackage Charts
 * @version $Id: timeline_ctrl.php,v 1.1.2.5 2006/01/13 23:00:20 yalnifj Exp $
 */
require ("config.php");
require ("includes/functions_charts.php");
require ($factsfile["english"]);
if (file_exists($factsfile[$LANGUAGE]))
	require $factsfile[$LANGUAGE];
require_once 'includes/controllers/basecontrol.php';
require_once 'includes/person_class.php';

function compare_people($a, $b) {
	return ($a->getBirthYear() - $b->getBirthYear());
}

/**
 * Main controller class for the timeline page.
 */
class TimelineControllerRoot extends BaseController {
	var $pids = array ();
	var $people = array();
	var $scale = 2;
	var $YrowLoc = 125;
	var $minYear = 0;
	// GEDCOM elements that will be found but should not be displayed
	var $nonfacts = "FAMS,FAMC,MAY,BLOB,OBJE,SEX,NAME,SOUR,NOTE,BAPL,ENDL,SLGC,SLGS,_TODO,CHAN,HUSB,WIFE,CHIL";
	var $colors = array ('Aliceblue', ' Antiquewhite', 'Aqua', ' Aquamarine', '	Azure', '	Beige', ' Bisque', ' Blanchedalmond', ' Blue', ' Blueviolet', ' Brown', ' Burlywood', ' Cadetblue', ' Chartreuse', ' Chocolate', ' Coral', ' Cornflowerblue', ' Cornsilk', ' Crimson', ' Cyan', ' Darkcyan', ' Darkgoldenrod', ' Darkgray', ' Darkgreen', ' Darkkhaki', ' Darkmagenta', ' Darkolivegreen', ' Darkorange', ' Darkorchid', ' Darkred', ' Darksalmon', ' Darkseagreen', ' Darkslateblue', ' Darkturquoise', ' Darkviolet', ' deeppink', ' Deepskyblue', ' 	Dimgray', ' Dodgerblue', ' Firebrick', ' Floralwhite', ' Forestgreen', ' Fuchsia', ' Gainsboro', ' Ghostwhite', ' Gold', ' Goldenrod', ' Gray', ' Green', ' Greenyellow', ' Honeydew', ' Hotpink', ' Indianred', ' Ivory', ' Khaki', ' Lavender', ' Lavenderblush', ' Lawngreen', ' Lemonchiffon', ' Lightblue', ' Lightcoral', ' Lightcyan', ' Lightgoldenrodyellow', ' Lightgreen', ' Lightgrey', ' Lightpink', ' Lightsalmon', ' Lightseagreen', ' Lightskyblue', ' Lightslategray', ' Lightsteelblue', ' Lightyellow', ' 	Lime', ' Limegreen', ' Linen', ' Magenta', ' Maroon', ' Mediumauqamarine�', '� Mediumblue�', '� Mediumorchid�', '� Mediumpurple�', '� Mediumseagreen', ' Mediumslateblue', ' Mediumspringgreen', ' Mediumturquoise', ' Mediumvioletred', 'Mintcream', ' Mistyrose', ' Moccasin', ' Navajowhite', ' Oldlace', ' Olive', ' Olivedrab', ' Orange', ' Orangered', ' Orchid', ' Palegoldenrod', ' Palegreen', ' Paleturquoise', ' Palevioletred', ' Papayawhip', ' Peachpuff', ' Peru', ' Pink', ' Plum', ' Powderblue', ' Purple', ' Red', ' Rosybrown', ' Royalblue', ' Saddlebrown', ' Salmon', ' Sandybrown', ' Seagreen', ' Seashell', ' Sienna', ' Silver', ' Skyblue', ' Slateblue', ' Slategray', ' Snow', ' Springgreen', ' Steelblue', ' Tan', ' Teal', ' Thistle', ' Tomato', ' Turquoise', ' Violet', ' Wheat', ' White', ' Whitesmoke', ' Yellow', ' YellowGreen');
	var $color;
	var $colorindex;
	var $zoomfactor;
	var $timelineMinYear;
	var $timelineMaxYear;
	var $birthMod;
	var $deathMod;
	var $endMod = 0;
	var $modTest;
	var $currentYear;
	var $endDate;
	var $startDate;
	/**
	 * constructor
	 */
	function TimelineRootController() {
		parent :: BaseController();
	}
	
	/**
	 * Initialization function
	 */
	function init() {
		global $GEDCOM_ID_PREFIX;
		$this->colorindex = 0;
		$this->zoomfactor = 10;
		$this->color = $this->colors[0];
		$this->currentYear = date("Y");
		$this->deathMod = 0;
		$this->endDate = $this->currentYear;
		
		//-- new pid
		if (isset ($_REQUEST['newpid'])) {
			$newpid = clean_input($_REQUEST['newpid']);
			$person = Person::getInstance($newpid);
			if (is_null($person)) {
				//-- allow the user to enter the id without the "I" prefix
				if (stristr($newpid, $GEDCOM_ID_PREFIX) === false) {
					$newpid = $GEDCOM_ID_PREFIX.$newpid;
					$person = Person::getInstance($newpid);
				}
			}
			//-- make sure we have the id from the gedcom record
			else $newpid = $person->getXref();
			if (!empty ($newpid))
				$this->pids[] = $newpid;
		}
		
		if (isset($_REQUEST['clear'])) unset($_SESSION['timeline_pids']);
		if (isset($_SESSION['timeline_pids'])) $this->pids = $_SESSION['timeline_pids'];
		
		//-- pids array
		if (isset ($_REQUEST['pids'])) {
			$this->pids = $_REQUEST['pids'];
			if (!empty ($newpid))
				$this->pids[] = $newpid;
		}

		//-- gets the immediate family for the individual being added if the include immediate family checkbox is checked.
		if(isset ($_REQUEST['addFamily'])){
			if (isset($newpid)) $this->addFamily($newpid);
		}
		
		$remove = "";
		if (!empty ($_REQUEST['remove']))
			$remove = $_REQUEST['remove'];
		
		//-- always start with someone on the chart
		if (count($this->pids)==0) {
			$this->pids[] = $this->addFamily(check_rootid(""));
		}
		
		//-- limit to a certain place
		if (!empty($_REQUEST['place'])) {
			$place_pids = get_place_positions($_REQUEST['place']);
			if (count($place_pids)>0) {
				$this->pids = $place_pids;
			}
		}
		
		//-- store the people in the session	
		$_SESSION['timeline_pids'] = $this->pids;
		
		//-- cleanup user input
		$this->pids = array_unique($this->pids);  //removes duplicates
		foreach ($this->pids as $key => $value) {
			if ($value != $remove) {
				$value = clean_input($value);
				$this->pids[$key] = $value;
				$person = Person::getInstance($value);
				if (!is_null($person)) {
					$byear = $person->getBirthYear();
					$dyear = $person->getDeathYear();
						
					//--Checks to see if the details of that person can be viewed
					if (!empty ($byear) && !empty ($dyear) && $person->canDisplayDetails()) {
						$this->people[] = $person;
					}
				}
			}
		}

		//--Finds if the begin year and end year textboxes are empty
		if (!empty ($_REQUEST['beginYear']) && !empty ($_REQUEST['endYear'])) {
			//-- reset the people array when doing a year range search
			$this->people = array();
			//Takes the begining year and end year passed by the postback and modifies them and uses them to populate
			//the time line

			$byear = $this->ModifyYear($_REQUEST["beginYear"],1);
			$dyear = $this->ModifyYear($_REQUEST["endYear"],2);
			//Variables to restrict the person boxes to the year searched.
			//--Searches for individuals who had an even between the year begin and end years
			$indis = search_indis_year_range($byear, $dyear);
			//--Populates an array of people that had an event within those years
			foreach ($indis as $pid => $indi) {
				$person = Person :: getInstance($pid);
				if (!is_null($person)) {
					$byear = $person->getBirthYear();
					$dyear = $person->getDeathYear();
						
					//--Checks to see if the details of that person can be viewed
					if (!empty ($byear) && !empty ($dyear) && $person->canDisplayDetails()) {
						$this->people[] = $person;
					}
				}
			}
		}
		
		//--Sort the arrar in order of being year
		uasort($this->people, "compare_people");
		
		//If there is people in the array posted back this if occurs
		if (isset ($this->people[0])) {
			//Find the maximum Death year and mimimum Birth year for each individual returned in the array.
			$this->timelineMaxYear = $this->people[0]->getDeathYear();
			$this->timelineMinYear = $this->people[0]->getBirthYear();
			foreach ($this->people as $key => $value) {
				if ($this->timelineMaxYear < $value->getDeathYear())
					$this->timelineMaxYear = $value->getDeathYear();
				if ($this->timelineMinYear > $value->getBirthYear())
					$this->timelineMinYear = $value->getBirthYear();
			}
			
			if($this->timelineMaxYear > $this->currentYear){
				$this->timelineMaxYear = $this->currentYear;	
			}

		} 
		else {
			// Sets the default timeline length
			$this->timelineMinYear = date("Y") - 101;
			$this->timelineMaxYear = date("Y");
		}
	}
	
	/**
	 * Add a person and his or her immediate family members to
	 * the pids array
	 * @param string $newpid
	 */
	function addFamily($newpid, $gen=0) {
		if (!empty ($newpid)) {
			$this->pids[] = $newpid;
			$person = Person::getInstance($newpid);
			$families = $person->getSpouseFamilies();
			//-- foreach gets the spouse and children of the individual.
			foreach($families as $famID => $family){ 
				if($newpid != $family->getHusbId()) {
					if ($gen>0) $this->pids[] = addFamily($family->getHusbId(), $gen-1);
					else $this->pids[] = $family->getHusbId();
				}
				if($newpid != $family->getWifeId()) {
					if ($gen>0) $this->pids[] = addFamily($family->getWifeId(), $gen-1);
					else $this->pids[] = $family->getWifeId();
				}
				$children = $family->getChildren();
				foreach($children as $childID => $child){
					if ($gen>0) $this->pids[] = addFamily($child->getXref(), $gen-1);
					else $this->pids[] = $child->getXref();
				}					
			}
			$families = $person->getChildFamilies();
			//-- foreach gets the father, mother and sibblings of the individual.
			foreach($families as $famID => $family){
				if ($gen>0) $this->pids[] = addFamily($family->getHusbId(), $gen-1);
				else $this->pids[] = $family->getHusbId();
				if ($gen>0) $this->pids[] = addFamily($family->getWifeId(), $gen-1);
				else $this->pids[] = $family->getWifeId();
				$children = $family->getChildren();
				foreach($children as $childID => $child){
					if($newpid != $child->getXref()) {
						if ($gen>0) $this->pids[] = addFamily($child->getXref(), $gen-1);
						else $this->pids[] = $child->getXref();
					}
				}					
			}
		}
	}
	
	// sets the start year and end year to a factor of 5
	function ModifyYear($year, $key) {
		$temp = $year;
		switch ($key) {
			case 1 : //rounds beginning year
				$this->birthMod = ($year % 5);
				$year = $year - ($this->birthMod);
				if($temp == $year){
					$this->modTest = 0;	
				}
				else $this->modTest = 1;
				break;
			case 2 : //rounds end year
				$this->deathMod = ($year % 5);
				//Only executed if the year needs to be modified
				if($this->deathMod > 0) {
					$this->endMod = (5 - ($this->deathMod));		
				}
				else {
					$this->endMod = 0;	
				}
				$year = $year + ($this->endMod);
				break;
		}
		return $year;
	}
	//Prints the time line
	function PrintTimeline($startYear, $endYear) {
		$leftPosition = 14; //start point
		$width = 8; //base width
		$height = 10; //standard height
		$tickDistance = 50; //length of one timeline section
		$top = 65; //top starting position
		$yearSpan = 5; //default zoom level
		$newStartYear = $this->ModifyYear($startYear, 1); //starting date for timeline
		$newEndYear = $this->ModifyYear($endYear, 2); //ending date for timeline
		$totalYears = $newEndYear - $newStartYear; //length of timeline
		$timelineTick = $totalYears / $yearSpan; //calculates the length of the timeline

		for ($i = 0; $i < $timelineTick; $i ++) { //prints the timeline
			echo "<div style=' background-color: white; position: absolute; top: ".$top."px;left: ".$leftPosition."px; width: ".$tickDistance."px;'>$newStartYear<img src=\"images/timelineChunk.gif\" alt=\"\" /></div>";
			$leftPosition += $tickDistance;
			$newStartYear += $yearSpan;

		}
		echo "<div style='background-color: white; position: absolute; top: ".$top."px;left: ".$leftPosition."px; width: ".$tickDistance."px;'>$newStartYear</div>";
	}
	//method used to place the person boxes onto the timeline
	function fillTL($ar, $int, $Y) {
		global $maxX, $zindex, $pgv_lang, $factarray;
		
		if (empty($zindex)) $zindex = count($ar);
		
		$modFix = 0;
		if($this->modTest == 1){
			$modFix = (9 * $this->birthMod);
		}
		//base case
		if (count($ar) == 0)
			return $Y;
		

		foreach ($ar as $key => $value) {
			//set start position and size of person-box according to zoomfactor
			if ($value->getBirthYear() > $int -1) {
				
				
				$birthYear = $value->getBirthYear();
				$deathYear = $value->getDeathYear();
				if($deathYear > date("Y")){
					$deathYear = date("Y");	
				}

				$width = ($deathYear - $birthYear) * $this->zoomfactor;
				
				
				
				$startPos = (($birthYear - $this->timelineMinYear) * $this->zoomfactor) + 14 + $modFix;
				$minlength = strlen($value->getName()) * $this->zoomfactor;
				$zindex--;
				if ($startPos > 15) {
					$startPos = (($birthYear - $this->timelineMinYear) * $this->zoomfactor) + 15 + $modFix;
					$width = (($deathYear - $birthYear) * $this->zoomfactor) - 2;
				}
				//set start position to deathyear
				$int = $deathYear;
				//set minimum width for single year lifespans
				if ($width < 10)
				{
					$width = 10;
					$int = $birthYear+1;
				}
				
				$lifespan = $birthYear."-".$deathYear;
				
				// different display values in the box based on the size of the person-box
				if ($width > ($minlength +110)) {

					
					echo "\n<div style='position: absolute;;top:".$Y."px; left:".$startPos."px;width:".$width."px; height:20px;" .
							" background-color:".$this->color."; border: solid blue 1px; z-index:$zindex;'>" .
									"\n\t<table><tr>\n\t\t<td width='15'><a class='showit' href='#'><b>" .get_first_letter($pgv_lang["birth"])."</b><span>".$value->getName()."<br/>".$pgv_lang["birth"]." ".get_changed_date($value->getBirthDate())." ".$value->getBirthPlace()."</span></a></td>" .
											"\n\t\t<td width='100%'><a href=\"individual.php?pid=".$value->getXref()."\">".$value->getName().":  $lifespan </a></td>" .
											"\n\t\t<td width='15'><a class='showit' href='#'><b>".get_first_letter($pgv_lang["death"])."</b><span>".$value->getName()."<br/>".$pgv_lang["death"]." ".get_changed_date($value->getDeathDate())."</span></a></td></tr></table></div>";

				} else {
					if ($width > $minlength +5) {
						echo "\n<div style='position: absolute; top:".$Y."px; left:".$startPos."px;width:".$width."px; height:20px;" .
							" background-color:".$this->color."; border: solid blue 1px; z-index:$zindex;'>" .
									"\n\t<table><tr>\n\t\t<td width='15'><a class='showit' href='#'><b>" .get_first_letter($pgv_lang["birth"])."</b><span>".$value->getName()."<br/>".$pgv_lang["birth"]." ".get_changed_date($value->getBirthDate())." ".$value->getBirthPlace()."</span></a></td>" .
											"\n\t\t<td width='100%'><a href=\"individual.php?pid=".$value->getXref()."\">".$value->getName()."</a></td>" .
											"\n\t\t<td width='15'><a class='showit' href='#'><b>".get_first_letter($pgv_lang["death"])."</b><span>".$value->getName()."<br/>".$pgv_lang["death"]." ".get_changed_date($value->getDeathDate())." ".$value->getDeathPlace()."</span></a></td></tr></table></div>";
					} else {						
						echo	"\n<div style='position: absolute;top:".$Y."px; left:".$startPos."px;width:".$width."px; height:20px;" .
							" background-color:".$this->color."; border: solid blue 1px; z-index:$zindex;'>" .
									"<a class='showit' href=\"individual.php?pid=".$value->getXref()."\"><b>B</b><span>".$value->getName()."<br/>".$pgv_lang["birth"]." ".get_changed_date($value->getBirthDate())." ".$value->getBirthPlace()."<br/>".$pgv_lang["death"]." ".get_changed_date($value->getDeathDate())." ".$value->getBirthPlace()."</span></a></a></div>";
											
					}
				}
				
				
				//remove used person from the working array
				unset ($ar[$key]);
				//change color
				if ($this->colorindex < count($this->colors) - 1) {
					$this->color = $this->colors[++ $this->colorindex];
				} else {
					$this->colorindex = 0;
					$this->color = $this->colors[0];
				}

				if ($maxX < $startPos + $width)
					$maxX = $startPos + $width;
			}
		}
		//move down 25 pixels
		$Y += 25;
		//recursive method call
		return $this->fillTL($ar, 0, $Y);
	}
	/**
	 * check the privacy of the incoming people to make sure they can be shown
	 */
	function checkPrivacy() {
		global $CONTACT_EMAIL;
		$printed = false;
		for ($i = 0; $i < count($this->people); $i ++) {
			if (!$this->people[$i]->canDisplayDetails()) {
				if ($this->people[$i]->canDisplayName()) {
					print "&nbsp;<a href=\"individual.php?pid=".$this->people[$i]->getXref()."\">".PrintReady($this->people[$i]->getName())."</a>";
					print_privacy_error($CONTACT_EMAIL);
					print "<br />";
					$printed = true;
				} else
					if (!$printed) {
						print_privacy_error($CONTACT_EMAIL);
						print "<br />";
					}
			}
		}
	}

}
// -- end of class
//-- load a user extended class if one exists
if (file_exists('includes/controllers/timeline_ctrl_user.php')) {
	include_once 'includes/controllers/timeline_ctrl_user.php';
} else {
	class TimelineController extends TimelineControllerRoot {
	}
}
$controller = new TimelineController();
$controller->init();
?>