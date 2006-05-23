<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1800 File
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
 * @package PhpGedView
 * @subpackage Research_Assistant
 * @version $Id: Census1800.php,v 1.4 2006/04/06 20:12:35 yalnifj Exp $
 * @author Brandon Gagnon
 */
 //-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"Census1800.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

function getSources(){
	global $TBLPREFIX;
		$sql = 	"SELECT s_name, s_id FROM ".$TBLPREFIX."sources WHERE s_id IN " .
				"(SELECT ts_s_id FROM ".$TBLPREFIX."tasksource WHERE ts_t_id='$_REQUEST[taskid]')";
		$res = dbquery($sql);
		$out = "";
		while($sources =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out .= '<option value="'.$sources["s_id"].'">'.$sources["s_name"];
		}
		return $out;
	}
	function getPeople(){
		global $TBLPREFIX;
		$sql = 	"SELECT i_name, i_id, i_file FROM ".$TBLPREFIX."individuals WHERE i_id IN " .
				"(SELECT it_i_id FROM ".$TBLPREFIX."individualtask WHERE it_t_id='$_REQUEST[taskid]')";
		$res = dbquery($sql);
		$out = "";
		while($people =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out .= '<option value="'.$people["i_id"]."#".$people["i_file"].'">'.$people["i_name"];
		}
		return $out;
	}
	
class Census1800 extends ra_form {

    function header($action, $tableAlign, $heading) {
    	global $pgv_lang;
    	//Row Form
    	$out = '<form action="module.php?mod=research_assistant&action=printform&formname=Census1800&taskid='.$_REQUEST['taskid'].'" method="post">';
    	$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
    	for($i = 1; $i <= 100; $i++){
    		$out .= '<option value="'.$i.'">'.$i;
    	}
    	$out .=	'</select></td></tr><tr><td colspan="2" class="topbottombar"><input type="submit" value="'.$pgv_lang["okay"].'"/></td></tr></table>';
    	$out .= '</form>';
    	
		$out .= '<form action="' . $action . '" method="post">';
        $out .= '<table id="Census1800" class="list_table" align="' . $tableAlign . '">';
        $out .= '<tr>';
        $out .= '<th colspan="12" align="right"class="topbottombar"><h2>' . $heading . '</h2></th>';
        $out .= '</tr>';
        return $out;
    }
	
    function content() {
    	global $pgv_lang;
    	if (empty($_POST['data']))
    		$data = array();
    	if (empty($_GET['row']))
    		$row = 1;
    		
//        Start of Table
        $out = '<tr><td class="descriptionbox">'.$pgv_lang["state"].'</td><td class="optionbox"><input name="state" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["call/url"].'</td><td class="optionbox"><input name="CallNumberURL" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["enumDate"].'</td><td class="optionbox"><input name="EnumerationDate" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["county"].'</td><td class="optionbox"><input name="county" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["city"].'</td><td class="optionbox"><input name="city" type="text" size="10"></td>';
        $out .=	'<td class="descriptionbox">'.$pgv_lang["page"].'</td><td class="optionbox"><input name="page" type="text" size="1"></td></tr>';
//        Next Table
        $out .= '<tr><table align="center" id="inputTable">';
        $out .= '<td class="descriptionbox" align="center" rowspan="2">Names of heads of families</td>';
        $out .= '<td colspan="5" class="descriptionbox" align="center">Free White Males</td>';
        $out .= '<td colspan="5" class="descriptionbox" align="center">Free White Females</td>';
        $out .= '<td class="descriptionbox" align="center" rowspan="2">All other<br/> free persons</td>';
        $out .= '<td class="descriptionbox" align="center" rowspan="2">Slaves</td>';
//		  Next row of description cells
        $out .=	'<tr><td class="descriptionbox">Under 10</td><td class="descriptionbox">10 thru 15</td>';
        $out .= '<td class="descriptionbox">16 thru 25</td><td class="descriptionbox">26 thru 44</td><td class="descriptionbox">45 and over</td>';
        $out .=	'<td class="descriptionbox">Under 10</td><td class="descriptionbox">10 thru 15</td>';
        $out .= '<td class="descriptionbox">16 thru 25</td><td class="descriptionbox">26 thru 44</td><td class="descriptionbox">45 and over</td></tr>';
//		  Country, City, Page, Head of Family input boxes
		if(!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = 1;
        for($i = 0; $i < $_REQUEST['numOfRows']; $i++){
	        $out .= '<tr><td class="optionbox"><input name="headName'.$i.'" type="text" size="19"></td>';
	//        Free white males input boxes
	        $out .= '<td class="optionbox"><input name="underTenM'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="tenThruFifteenM'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="sixteenThruTwentyfiveM'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="twentysixThruFortyfourM'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="fortyfiveAndOverM'.$i.'" type="text" size="4"></td>';
	//		  Free white females input boxes 
	        $out .= '<td class="optionbox"><input name="underTenF'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="tenThruFifteenF'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="sixteenThruTwentyfiveF'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="twentysixThruFortyfourF'.$i.'" type="text" size="4"></td>';
	        $out .= '<td class="optionbox"><input name="fortyfiveAndOverF'.$i.'" type="text" size="4"></td>';
	//  	  Other Persons and Slaves input boxes
	        $out .= '<td class="optionbox"><input name="otherPersons'.$i.'" type="text" size="5"></td>';
	        $out .= '<td class="optionbox"><input name="slaves'.$i.'" type="text" size="4"></td></tr>';
        }
//		  People and Sources
        $out .= '<tr><td colspan="13"><input type="hidden" name="Rows" value="'.$_REQUEST['numOfRows'].'"></input><table width="100%"><tr><td class="descriptionbox" width="20%">Person(s) Related To</td><td class="optionbox" align="right" width="30%"><select name="people[]" id="peopleSelect" size="6" style="width: 100%;" multiple>'.getPeople().'</select><br /><a href="javascript:;" class="link" onclick="javascript:openPeopleSearch()">search</a>&nbsp;<a href="javascript:;" class="link" onclick="javascript:deleteSelectedItemsFromPeopleList()">remove</a></td>';
		$out .= '<td class="descriptionbox" width="20%">Source(s)</td><td class="optionbox" align="right" colspan="8"><select name="sources[]" id="sourcesSelect" size="6" style="width:100%;" multiple="multiple">'.getSources().'</select><br/><a href="javascript:;" class="link" onclick="javascript:openSourceSearch()">'.$pgv_lang["search"].'</a>&nbsp;<a href="javascript:;" class="link" onclick="javascript:deleteSelectedItemsFromList()">'.$pgv_lang["remove"].'</a></td></tr></table></td></tr>';
//        Submit button 
         $out .= '<tr><td colspan="13" align="center" class="topbottombar"><br /><input type="submit" value="Finish Form" onclick="javascript:selectPeopleList();selectList();"/></td></tr></table></tr>';
         $out .= '<span id="writeroot"></span>';
        return $out;
    }

    function footer() {
        return '</table></form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1800&action=func&func=save&taskid=$_REQUEST[taskid]", "center", "1800 United States Federal Census");
        $out .= $this->content();
        $out .= $this->footer();
        return $out;
    }

    function save() {
        // Specify the global var GEDCOM so we know what file were using.
		global $GEDCOM;
		global $TBLPREFIX;

		// Set our output to nothing, this supresses a warning that we would otherwise get.
		$out = "";



		// We should have had people and sources posted to here, lets get those now and 
		// set those to a different variable thats easier to work with.
		
		// UPDATE PEOPLE
				//  -Delete old people
		$sql = "DELETE FROM ".$TBLPREFIX."individualtask WHERE it_t_id='".(int) $_REQUEST["taskid"]."'";
		$res = dbquery($sql);

		if (isset ($_POST['people'])) {
			for ($i = 0; $i < count($_POST['people']); $i ++) {
				$pos = strrpos($_POST['people'][$i], "#");
				$it_i_id = substr($_POST['people'][$i], 0, $pos);
				$it_i_file = substr($_POST['people'][$i], $pos +1);
				$sql = 'INSERT INTO '.$TBLPREFIX.'individualtask (it_t_id, it_i_id, it_i_file) '."VALUES ('" . $_REQUEST["taskid"] . "', '$it_i_id', '$it_i_file')";
				$res = dbquery($sql);
			}
		}
		
		// UPDATE SOURCES
				//  -Delete old sources
		$sql = "DELETE FROM ".$TBLPREFIX."tasksource WHERE ts_t_id='".(int) $_REQUEST["taskid"]."'";
		$res = dbquery($sql);

		if (isset ($_POST['sources'])) {
			for ($i = 0; $i < count($_POST['sources']); $i ++) {
				$sql = 'INSERT INTO '.$TBLPREFIX.'tasksource (ts_t_id, ts_s_id) '."VALUES ('" . $_REQUEST["taskid"] . "', '".$_POST['sources'][$i]."')";
				$res = dbquery($sql);
			}
		}
				
		
		
		
		$people = & $_POST['people'];
		
		// Set sourid to nothing, again to suppress a warning.
		$sourid = "";

		$sql = "SELECT ts_s_id FROM ".$TBLPREFIX."tasksource WHERE ts_t_id='$_REQUEST[taskid]'";
		$res = dbquery($sql);
		while($id =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
       	 	$sourid = $id["ts_s_id"];
    	}
		
		 if($sourid == "")
		 {
		 	$sql = "SELECT s_id FROM ".$TBLPREFIX."sources WHERE s_name LIKE '1800 US Census%'";
		 	$res = dbquery($sql);
		 	while($sour =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
       	 		$sourid = $sour["s_id"];
    		}
    		if($sourid == ""){
				// Make our source
				$newsour = "0 @new@ SOUR";
				$newsour .= "\r\n1 TITL 1800 US Census";
				$newsour .= "\r\n1 AUTH US Government";
				$newsour .= "\r\n1 PUBL Washington [District of Columbia] : National Archives. Central Plains Region, 1949, 1958-1960";
				$sourid = append_gedrec($newsour);
		 	}
		 }

			// Append it to every person thats related to this.
			for ($k = 0; $k < count($people); $k ++) {
				$pos = strrpos($_POST['people'][$k], "#");
				$personID = substr($_POST['people'][$k], 0, $pos);
				if (isset ($pgv_changes['"'.$personID.'"'.$GEDCOM])) {
					$indirec = find_record_in_file($personID);
				} else {
					$indirec = find_person_record($personID);
				}
				// Write to the GEDCOM
				$indirec .= "\r\n1 CENS";
				$indirec .= "\r\n2 _RAID ".$_REQUEST['taskid'];
				$indirec .= "\r\n2 DATE ";
				$indirec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1800";
				$indirec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
				$indirec .= "\r\n2 @".$sourid."@";
				$indirec .= "\r\n3 PAGE Page ".$_POST['page'].", Call Number/URL ".$_POST['CallNumberURL'];
				$indirec .= "\r\n3 DATA ";
				$indirec .= "\r\n4 TEXT City ".$_POST['city'].", County ".$_POST['county'].", State ".$_POST['state'].", 1800 US Census";
				for($number = 0; $number < $_POST['Rows']; $number++)
				{
					$indirec .=$number==0?"" :"\r\n5 CONT";
					$indirec .= "\r\n5 CONT Head of Family ".$_POST["headName".$number];
					$indirec .= "\r\n5 CONT Free White Males under10 ".$_POST["underTenM".$number];
					$indirec .= "\r\n5 CONT Free White Males Ten thru fifteen ".$_POST["tenThruFifteenM".$number];
					$indirec .= "\r\n5 CONT Free White Males Sixteen thru Twentyfive ".$_POST["sixteenThruTwentyfiveM".$number];
					$indirec .= "\r\n5 CONT Free White Males Twentysix thru Fortyfour ".$_POST["twentysixThruFortyfourM".$number];
					$indirec .= "\r\n5 CONT Free White Males Fortyfive and Over ".$_POST["fortyfiveAndOverM".$number];
					$indirec .= "\r\n5 CONT Free White Females under10 ".$_POST["underTenF".$number];
					$indirec .= "\r\n5 CONT Free White Females Ten thru fifteen ".$_POST["tenThruFifteenF".$number];
					$indirec .= "\r\n5 CONT Free White Females Sixteen thru Twentyfive ".$_POST["sixteenThruTwentyfiveF".$number];
					$indirec .= "\r\n5 CONT Free White Females Twentysix thru Fortyfour ".$_POST["twentysixThruFortyfourF".$number];
					$indirec .= "\r\n5 CONT Free White Females Fortyfive and Over ".$_POST["fortyfiveAndOverF".$number];
					$indirec .= "\r\n5 CONT All other Free Persons ".$_POST["otherPersons".$number];
					$indirec .= "\r\n5 CONT Slaves ".$_POST["slaves".$number];
				}
				
				replace_gedrec($personID,$indirec);
			}
			
header('Location: module.php?mod=research_assistant');
exit;
		// Tell the user their form submitted successfully.
//		$out .= ra_functions::print_menu();
		//$out .= ra_functions::printMessage("Success!",true);
		
		// Complete the Task
//		ra_functions::completeTask($_REQUEST['taskid']);

		// Return it to the buffer.
	//	return $out;
    }
    
}
?>
