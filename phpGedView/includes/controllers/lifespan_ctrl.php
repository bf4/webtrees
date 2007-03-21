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
 * @version $Id$
 */

if (stripos($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once ("config.php");
require_once ("includes/functions_charts.php");
require_once 'includes/controllers/basecontrol.php';
require_once 'includes/person_class.php';

function compare_people($a, $b) {
	return ($a->getBirthYear() - $b->getBirthYear());
}


// GEDCOM elements that will be found but should not be displayed
$nonfacts = array("FAMS","FAMC","MAY","BLOB","OBJE","SEX","NAME","SOUR","NOTE","BAPL","ENDL","SLGC","SLGS","_TODO","CHAN","HUSB","WIFE","CHIL","BIRT","DEAT","BURI");// DEATH OF SIBLING:  DEATH OF HALF SIBLING DEATH OF MOTHER DEATH OF FATHER DEATH OF CHILD
$nonfamfacts = array("CHAN","HUSB","WIFE","CHIL");

/**
 * Main controller class for the timeline page.
 */
class LifespanControllerRoot extends BaseController {
	var $pids = array ();
	var $people = array();
	var $scale = 2;
	var $YrowLoc = 125;
	var $minYear = 0;

	// The following colours are deliberately omitted from the $colors list:
	// Blue, Red, Black, White, Green
	var $colors = array ('Aliceblue', ' Antiquewhite', 'Aqua', ' Aquamarine', '	Azure', ' Beige', ' Bisque', ' Blanchedalmond', ' Blueviolet', ' Brown', ' Burlywood', ' Cadetblue', ' Chartreuse', ' Chocolate', ' Coral', ' Cornflowerblue', ' Cornsilk', ' Crimson', ' Cyan', ' Darkcyan', ' Darkgoldenrod', ' Darkgray', ' Darkgreen', ' Darkkhaki', ' Darkmagenta', ' Darkolivegreen', ' Darkorange', ' Darkorchid', ' Darkred', ' Darksalmon', ' Darkseagreen', ' Darkslateblue', ' Darkturquoise', ' Darkviolet', ' Deeppink', ' Deepskyblue', ' Dimgray', ' Dodgerblue', ' Firebrick', ' Floralwhite', ' Forestgreen', ' Fuchsia', ' Gainsboro', ' Ghostwhite', ' Gold', ' Goldenrod', ' Gray', ' Greenyellow', ' Honeydew', ' Hotpink', ' Indianred', ' Ivory', ' Khaki', ' Lavender', ' Lavenderblush', ' Lawngreen', ' Lemonchiffon', ' Lightblue', ' Lightcoral', ' Lightcyan', ' Lightgoldenrodyellow', ' Lightgreen', ' Lightgrey', ' Lightpink', ' Lightsalmon', ' Lightseagreen', ' Lightskyblue', ' Lightslategray', ' Lightsteelblue', ' Lightyellow', ' Lime', ' Limegreen', ' Linen', ' Magenta', ' Maroon', ' Mediumaqamarine�', '� Mediumblue�', '� Mediumorchid�', '� Mediumpurple�', '� Mediumseagreen', ' Mediumslateblue', ' Mediumspringgreen', ' Mediumturquoise', ' Mediumvioletred', 'Mintcream', ' Mistyrose', ' Moccasin', ' Navajowhite', ' Oldlace', ' Olive', ' Olivedrab', ' Orange', ' Orangered', ' Orchid', ' Palegoldenrod', ' Palegreen', ' Paleturquoise', ' Palevioletred', ' Papayawhip', ' Peachpuff', ' Peru', ' Pink', ' Plum', ' Powderblue', ' Purple', ' Rosybrown', ' Royalblue', ' Saddlebrown', ' Salmon', ' Sandybrown', ' Seagreen', ' Seashell', ' Sienna', ' Silver', ' Skyblue', ' Slateblue', ' Slategray', ' Snow', ' Springgreen', ' Steelblue', ' Tan', ' Teal', ' Thistle', ' Tomato', ' Turquoise', ' Violet', ' Wheat', ' Whitesmoke', ' Yellow', ' YellowGreen');
	var $malecolorR = array('000', ' 010', ' 020', ' 030', ' 040', ' 050', ' 060', ' 070', ' 080', ' 090', ' 100', ' 110', ' 120', ' 130', ' 140', ' 150', ' 160', ' 170', ' 180', ' 190', ' 200', ' 210', ' 220', ' 230', ' 240', ' 250');
	var $malecolorG = array('000', ' 010', ' 020', ' 030', ' 040', ' 050', ' 060', ' 070', ' 080', ' 090', ' 100', ' 110', ' 120', ' 130', ' 140', ' 150', ' 160', ' 170', ' 180', ' 190', ' 200', ' 210', ' 220', ' 230', ' 240', ' 250');
	var $malecolorB = 255;
	var $femalecolorR = 255;
	var $femalecolorG = array('000', ' 010', ' 020', ' 030', ' 040', ' 050', ' 060', ' 070', ' 080', ' 090', ' 100', ' 110', ' 120', ' 130', ' 140', ' 150', ' 160', ' 170', ' 180', ' 190', ' 200', ' 210', ' 220', ' 230', ' 240', ' 250');
	var $femalecolorB = array('250', ' 240', ' 230', ' 220', ' 210', ' 200', ' 190', ' 180', ' 170', ' 160', ' 150', ' 140', ' 130', ' 120', ' 110', ' 100', ' 090', ' 080', ' 070', ' 060', ' 050', ' 040', ' 030', ' 020', ' 010', '000');
	var $color;
	var $colorindex;
	var $Fcolorindex;
	var $Mcolorindex;
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
	var $currentsex;
	/**
	 * constructor
	 */
	function TimelineRootController() {
		parent :: BaseController();
	}
	
	/**
	 * Search for individuals who had dates within the given year ranges
	 * @param int $startyear	the starting year
	 * @param int $endyear		The ending year
	 * @return array
	 */
	function search_indis_year_range($startyear, $endyear) {
		global $TBLPREFIX, $GEDCOM, $indilist, $DBCONN, $REGEXP_DB, $DBTYPE, $GEDCOMS;
	
		if (stristr($DBTYPE, "mysql")!==false) $term = "REGEXP";
		else if (stristr($DBTYPE, "pgsql")!==false) $term = "~*";
		else $term='LIKE';
	
		$myindilist = array();
		//select dategroups.d_gid, i_name, dategroups.birth, dategroups.death FROM pgv_individuals,
		//(select d_gid, d_file, MIN(d_datestamp) as birth, MAX(d_datestamp) as death from pgv_dates WHERE d_fact NOT IN ('CHAN','ENDL','SLGC','SLGS','BAPL') GROUP BY d_gid) as dategroups
		//WHERE dategroups.death>=18790000 and dategroups.birth<=18810000 AND i_file=dategroups.d_file AND i_id=dategroups.d_gid

		$sql = "SELECT i_id, i_name, i_file, i_gedcom, i_isdead, i_letter, i_surname FROM ".$TBLPREFIX."individuals, ";
		$sql .= "(select d_gid, d_file, MIN(d_datestamp) as birth, MAX(d_datestamp) as death from ".$TBLPREFIX."dates WHERE d_fact NOT IN ('CHAN','ENDL','SLGC','SLGS','BAPL') AND d_file='".$GEDCOMS[$GEDCOM]['id']."' GROUP BY d_gid) as dategroups ";
		$sql .= "WHERE dategroups.death >= ".$startyear."0000 AND dategroups.birth<=".$endyear."0000 AND i_file=dategroups.d_file AND i_id=dategroups.d_gid";
		/*
		$i=$startyear;
		while($i <= $endyear) {
			if ($i > $startyear) $sql .= " OR ";
			if ($REGEXP_DB) $sql .= "i_gedcom $term '".$DBCONN->escapeSimple("2 DATE[^\n]* ".$i)."'";
			else $sql .= "i_gedcom LIKE '".$DBCONN->escapeSimple("%2 DATE%".$i)."%'";
			$i++;
		}
		$sql .= ")";
		*/
		$sql .= " AND i_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])."'";
		//		print $sql;
		$res = dbquery($sql);
	
		while($row =& $res->fetchRow()){
			$row = db_cleanup($row);
			$myindilist[$row[0]]["names"] = get_indi_names($row[3]);
			$myindilist[$row[0]]["gedfile"] = $row[2];
			$myindilist[$row[0]]["gedcom"] = $row[3];
			$myindilist[$row[0]]["isdead"] = $row[4];
			$indilist[$row[0]] = $myindilist[$row[0]];
		}
		$res->free();
		return $myindilist;
	}
	
	/**
	 * Initialization function
	 */
	function init() {
		global $GEDCOM_ID_PREFIX;
		$this->colorindex = 0;
		$this->Fcolorindex = 0;
		$this->Mcolorindex = 0;
		$this->zoomfactor = 10;
		$this->color = "#0000FF";
		$this->currentYear = date("Y");
		$this->deathMod = 0;
		$this->endDate = $this->currentYear;
		

		//--new pid
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
		else {
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
		
		if (empty ($_REQUEST['beginYear']) || empty ($_REQUEST['endYear'])) {
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
						if (!empty ($byear) && $byear!="0000" && !empty($dyear) && $dyear!="0000" && $person->canDisplayDetails()) {
							$this->people[] = $person;
						}
					}
				}
			}
		}
		

		//--Finds if the begin year and end year textboxes are not empty
		else {
			//-- reset the people array when doing a year range search
			$this->people = array();
			//Takes the begining year and end year passed by the postback and modifies them and uses them to populate
			//the time line

			//$byear = $this->ModifyYear($_REQUEST["beginYear"],1);
			//$dyear = $this->ModifyYear($_REQUEST["endYear"],2);
			$byear = $_REQUEST["beginYear"];
			$dyear = $_REQUEST["endYear"];
			//Variables to restrict the person boxes to the year searched.
			//--Searches for individuals who had an even between the year begin and end years
			$indis = $this->search_indis_year_range($byear, $dyear);
				//			print "after query";
				//			print_execution_stats();
			//--Populates an array of people that had an event within those years
					
			foreach ($indis as $pid => $indi) {
				if (empty($_REQUEST['place']) || in_array($pid, $this->pids)) {
					$person = Person::getInstance($pid);
					if (!is_null($person)) {
						$byear = $person->getBirthYear();
						$dyear = $person->getDeathYear();
						//--Checks to see if the details of that person can be viewed
						if (!empty ($byear) && $byear!="0000" && !empty($dyear) && $dyear!="0000" && $person->canDisplayDetails()) {
							$this->people[] = $person;
						}
					}
				}
			}
			unset($_SESSION['timeline_pids']);
				//			print "after objects";
				//			print_execution_stats();
		}
		
		//--Sort the arrar in order of being year
		uasort($this->people, "compare_people");
			//		print "after sort";
			//		print_execution_stats();
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
	}
	
	/**
	 * Add a person and his or her immediate family members to
	 * the pids array
	 * @param string $newpid
	 */
	function addFamily($newpid, $gen=0) {
		if (!empty ($newpid)) {
			$person = Person::getInstance($newpid);
			if (is_null($person)) return;
			$this->pids[] = $newpid; 
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
		$this->timelineMinYear = $newStartYear;
		$newEndYear = $this->ModifyYear($endYear, 2); //ending date for timeline
		$totalYears = $newEndYear - $newStartYear; //length of timeline
		$timelineTick = $totalYears / $yearSpan; //calculates the length of the timeline

		for ($i = 0; $i < $timelineTick; $i ++) { //prints the timeline
			echo "<div class=\"sublinks_cell\" style=\"text-align: left; position: absolute; top: ".$top."px; left: ".$leftPosition."px; width: ".$tickDistance."px;\">$newStartYear<img src=\"images/timelineChunk.gif\"  alt=\"\" /></div>";  //onclick="zoomToggle('100px','100px','200px','200px',this);"
			$leftPosition += $tickDistance;
			$newStartYear += $yearSpan;

		}
		echo "<div class=\"sublinks_cell\" style=\"text-align: left; position: absolute; top: ".$top."px; left: ".$leftPosition."px; width: ".$tickDistance."px;\">$newStartYear</div>";
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
				
			//Creates appropriate color scheme to show relationships

			$this->currentsex = $value->getSex();
			if ($this->currentsex == "M"){
				$this->malecolorR[++ $this->Mcolorindex];
				$this->malecolorG[++ $this->Mcolorindex];
				$red = dechex($this->malecolorR[$this->Mcolorindex]);
				$green =dechex($this->malecolorR[$this->Mcolorindex]);
				if(strlen($red)<2){
					$red = "0".$red;
				}
				if(strlen($green)<2){
					$green = "0".$green;
				}

				$this->color = "#".$red.$green.dechex($this->malecolorB);
			}
			else if($this->currentsex == "F"){
				$this->femalecolorG[++ $this->Fcolorindex];
				$this->femalecolorB[++ $this->Fcolorindex];
				$this->color = "#".dechex($this->femalecolorR).dechex($this->femalecolorG[$this->Fcolorindex]).dechex($this->femalecolorB[$this->Fcolorindex]);
			}
			else{
				$this->color = $this->colors[$this->colorindex];
			}
				
			//set start position and size of person-box according to zoomfactor
			/* @var $value Person */
			if ($value->getBirthYear() > $int -1) {
				
				$birthYear = $value->getBirthYear();
				$deathYear = $value->getDeathYear();
				if($deathYear > date("Y")){
					$deathYear = date("Y");	
				}

				$width = ($deathYear - $birthYear) * $this->zoomfactor;
				$height = 2 * $this->zoomfactor;
				
				//				$startPos = (($birthYear - $this->timelineMinYear) * $this->zoomfactor) + 14 + $modFix;
				$startPos = (($birthYear - $this->timelineMinYear) * $this->zoomfactor) + 14 + $modFix;
				$minlength = strlen($value->getName()) * $this->zoomfactor;
				$zindex--;
				if ($startPos > 15) {
					$startPos = (($birthYear - $this->timelineMinYear) * $this->zoomfactor) + 15 + $modFix;
					$startPos = (($birthYear - $this->timelineMinYear) * $this->zoomfactor) + 15;
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
				$lifespannumeral = $deathYear - $birthYear;
				
				//Need to calculate each event and the spacing between them
				// event1 distance will be event - birthyear   that will be the distance. then each distance will chain off that

				//$event[][]  = {"Cell 1 will hold events"}{"cell2 will hold time between that and the next value"};
				//$value->add_historical_facts();
				$value->add_family_facts(false);
				$unparsedEvents = $value->getIndiFacts();
				sort_facts($unparsedEvents);
				//print_r($unparsedEvents);

				$eventinformation = Array();
				$eventspacing = Array();
				//print "UNPARSED:";
				//print_r($unparsedEvents);
				foreach($unparsedEvents as $index=>$val)
				{

					//print $val[1];
					if(preg_match('/2 DATE/',$val[1]))
					{
						$date = get_gedcom_value("DATE",2,$val[1]);
						//$evt =  ereg_replace("DATE.*", "", $val[1]);
						//$events[$evt][$date];
						//$evt =  ereg_replace("\d (\w*)","",$evt);
						$ft = preg_match("/1\s(\w+)(.*)/", $val[1], $match);
						if ($ft>0) $fact = $match[1];
						//print "EVENT:".$factarray[$fact]. " DATE:".$date;
						$date_arr = parse_date($date);
						$yearsin = $date_arr[0]["year"]-$birthYear;
						//print "YearsIN:".$yearsin;
						$eventwidth = ($yearsin/$lifespannumeral)* 100; // percent of the lifespan before the event occured used for determining div spacing
						//print "EVENTWIDTH:".$eventwidth;
						//print "YEARSIN:".$yearsin;
						//print "LIFESPAN:".$lifespan;
						// figure out some schema
						$evntwdth = $eventwidth."%";
						//-- if the fact is a generic EVENt then get the qualifying TYPE
						if ($fact=="EVEN") {
							$fact = get_gedcom_value("TYPE",2,$val[1]);
						}
						$place = get_gedcom_value("PLAC", 2, $val[1]);
						$trans = $fact;
						if (isset($factarray[$fact])) $trans = $factarray[$fact];
						else if (isset($pgv_lang[$fact])) $trans = $pgv_lang[$fact];  
						if (isset($eventinformation[$evntwdth])) $eventinformation[$evntwdth] .= "<br />\n".$trans."<br />\n".$date." ".$place;
						else $eventinformation[$evntwdth]= $trans."<br />\n".$date." ".$place;
						//$eventspacing[$eventwidth][$date];
					}
						
				}
				//sort by event width

					
				$out = ""; // this is the string we are going to build
				//what we are going to do now that we have the facts array is we are going to pass it into a function then parse all the data and print from that function
				// different display values in the box based on the size of the person-box
				if ($width > ($minlength +110)) {
					echo "\n<div id=\"bar_".$value->getXref()."\" style=\"position: absolute;top:".$Y."px; left:".$startPos."px; width:".$width."px; height:".$height."px;" .
					" background-color:".$this->color."; border: solid blue 1px; z-index:$zindex;\">";
					foreach($eventinformation as $evtwidth=>$val){
						print "<div style=\"position:absolute;left:".$evtwidth." \"><a class=\"showit\" href='#'style=\"color:White; top:-2px; font-size:10px;\"><b>".get_first_letter($val)."</b><span>".$val."</span></a></div>";
					}
					print "\n\t<table><tr>\n\t\t<td width=\"15\"><a class=\"showit\" href=\"#\"><b>" .get_first_letter($pgv_lang["birth"])."</b><span>".$value->getName()."<br/>".$pgv_lang["birth"]." ".get_changed_date($value->getBirthDate())." ".$value->getBirthPlace()."</span></a></td>" .
					"\n\t\t<td align=\"left\" width=\"100%\"><a href=\"individual.php?pid=".$value->getXref()."\">".$value->getName().":  $lifespan </a></td>" .
					"\n\t\t<td width=\"15\">";
					if ($value->isDead()) print "<a class=\"showit\" href=\"#\"><b>".get_first_letter($pgv_lang["death"])."</b><span>".$value->getName()."<br/>".$pgv_lang["death"]." ".get_changed_date($value->getDeathDate())."</span></a>";
					print "</td></tr></table>";
					echo '</div>';

				} else {
					if ($width > $minlength +5) {
						echo "\n<div style=\"text-align: left; position: absolute; top:".$Y."px; left:".$startPos."px; width:".$width."px; height:".$height."px;" .
						"  background-color:".$this->color."; border: solid blue 1px; z-index:$zindex;\">";
						foreach($eventinformation as $evtwidth=>$val){
							print "<div style=\"position:absolute;left:".$evtwidth." \"><a class=\"showit\" href='#'style=\"color:White; top:-2px; font-size:10px;\"><b>".get_first_letter($val)."</b><span>".$val."</span></a></div>";
						}
						print "\n\t<table dir=\"ltr\"><tr>\n\t\t<td width=\"15\"><a class=\"showit\" href=\"#\"><b>" .get_first_letter($pgv_lang["birth"])."</b><span>".$value->getName()."<br/>".$pgv_lang["birth"]." ".get_changed_date($value->getBirthDate())." ".$value->getBirthPlace()."</span></a></td>" .
						"\n\t\t<td align=\"left\" width=\"100%\"><a href=\"individual.php?pid=".$value->getXref()."\">".$value->getName()."</a></td>" .
						"\n\t\t<td width=\"15\">";
						if ($value->isDead()) print "<a class=\"showit\" href=\"#\"><b>".get_first_letter($pgv_lang["death"])."</b><span>".$value->getName()."<br/>".$pgv_lang["death"]." ".get_changed_date($value->getDeathDate())." ".$value->getDeathPlace()."</span></a>";
						print "</td></tr></table>";
						echo '</div>';
					} else {						
						echo "\n<div style=\"text-align: left; position: absolute;top:".$Y."px; left:".$startPos."px;width:".$width."px; height:".$height."px;" .
						" background-color:".$this->color."; border: solid blue 1px; z-index:$zindex;\">" ;
							
						print"<a class=\"showit\" href=\"individual.php?pid=".$value->getXref()."\"><b>".get_first_letter($pgv_lang["birth"])."</b><span>".$value->getName()."<br/>".$pgv_lang["birth"]." ".get_changed_date($value->getBirthDate())." ".$value->getBirthPlace()."<br/>";
						foreach($eventinformation as $evtwidth=>$val){
							print $val."<br />\n";
						}
						if ($value->isDead()) print $pgv_lang["death"]." ".get_changed_date($value->getDeathDate())." ".$value->getBirthPlace();
						print "</span></a>";
						echo '</div>';
											
					}
				}
				
				
				//remove used person from the working array
				unset ($ar[$key]);
				//change color
				if ($this->colorindex < count($this->colorindex) - 1 && $this->Mcolorindex < count($this->Mcolorindex)-1 && $this->Fcolorindex < count($this->Fcolorindex)-1) {
					//					do nothing
				} else {
					$this->colorindex = 0;
					$this->Fcolorindex = 0;
					$this->Mcolorindex = 0;
				}

				if ($maxX < $startPos + $width)
					$maxX = $startPos + $width;
			}
		}
		//move down 25 pixels
		if ($this->zoomfactor > 10){
			$Y += 25 + $this->zoomfactor;
		}
		else{
		$Y += 25;
		}

		//NOTE: this is where we'd implement the spacemanagement.
		//currently set at 25

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
	class LifespanController extends LifespanControllerRoot {
	}
}
$controller = new LifespanController();
$controller->init();
?>
