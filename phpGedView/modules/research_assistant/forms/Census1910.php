<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1910 File
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
 * @version $Id: Birth_Information.php 200 2005-11-09 20:37:48Z jporter $
 * @author Joey DiAna
 */
 //-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"Census1910.php")) {
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
	
class Census1910 extends ra_form {

    function header($action, $tableAlign, $heading) {
    	global $pgv_lang;
    	//Row Form
    	$out = '<form action="module.php" method="post">';
    	$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
    			'<input type="hidden" name="action" value="completeTask" />' .
    			'<input type="hidden" name="commonFrm" value="Census1910" />' .
    			'<input type="hidden" name="taskid" value="'.$_REQUEST['taskid'].'" />';
    	$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
    	for($i = 1; $i <= 20; $i++){
    		$out .= '<option value="'.$i.'"';
    		if (isset($_REQUEST['numOfRows']) && $_REQUEST['numOfRows']==$i) $out .= ' selected="selected"';
			$out .= '>'.$i;
    	}
    	$out .=	'</select></td></tr><tr><td colspan="2" class="topbottombar"><input type="submit" value="'.$pgv_lang["okay"].'"/></td></tr></table>';
    	$out .= '</form>';
    	
	$out .= '<form action="' . $action . '" method="post">';
       
        return $out;
    }
	
    function content() {
    	global $pgv_lang;
    	if (empty($_POST['data']))
    		$data = array();
    	if (empty($_GET['row']))
    		$row = 1;
    	
    	if (empty($_REQUEST['numOfRows'])) $_REQUEST['numOfRows']=1;
    		
//        Start of Table
      

$out = '<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style="border-collapse:collapse;border:none">
  <td class="descriptionbox">
  Dwelling Number.
  </td>';
  for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  	$out.='<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="House'.$i.'" /></td>';
  }
  $out .='</tr>
 <tr>
  <td class="descriptionbox">Families numbered in order of visitation.
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="Families'.$i.'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">
  The name of each Person.
  </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="23" name = "NameOfPeople'.$i.'" /></td>'; 
  		}
 $out .='</tr>
 <tr>
  <td class="descriptionbox">Relationship of each person to the head of the family.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Relationship'.$i.'" /></td>';
		}
 		$out .='</tr>
  <tr>
  <td class="descriptionbox">Sex: Male: M   Female: F.
   </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 M:<INPUT TYPE="RADIO" value = "Male" name = "Sex'.$i.'"> F:<INPUT TYPE="RADIO" value = "Female" name = "Sex'.$i.'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">
  Color or race.
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Race'.$i.'" /></td>';
		}
 		$out .='</tr>
  <tr>
  	<td class="descriptionbox">Age at last birthday.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Age'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Single-S, Married-M, Widowed-W, Divorced-d.  
  </td>';
		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Marrage'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Number of years in present marriage.   
  </td>';
		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Married'.$i.'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Mother of how many children.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Children'.$i.'" /></td>';
		}
 		$out .='</tr>
  <tr>
  <td class="descriptionbox">How many of these children are living.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "LivingChildren'.$i.'" /></td>';
		}
 		$out .='</tr> 
  <tr>
  <td class="descriptionbox">Place of birth of this person.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "PlaceOfBirth'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of the father.
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "FathersPlaceOfBirth'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of the mother.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "MothersPlaceOfBirth'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Year of immagration to the US.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Immagration'.$i.'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Whether Naturalized or Alien.
 </td>';
	 		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 Natruralized:<INPUT TYPE="RADIO" value = "Naturalized" name = "Naturalization'.$i.'"> Alien:<INPUT TYPE="RADIO" value = "Alien" name = "Naturalization'.$i.'" /></td>';
	}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Can speak english if not what language. 
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "English'.$i.'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Profession, Occupation or Trade.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Trade'.$i.'" /></td>';
		}
 		$out .='</tr> 	
 			
 <tr>
  <td class="descriptionbox">General nature of this persons occupation. 
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "NatureOfTrade'.$i.'" /></td>';
		}
 		$out .='</tr>
   <td class="descriptionbox">Is this person an employer, employee, or other. 
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Employer'.$i.'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Out of work on April 15, 1910.
 </td>';
		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 Yes:<INPUT TYPE="RADIO" value = "Yes" name = "Employed'.$i.'"> No:<INPUT TYPE="RADIO" value = "No" name = "Employed'.$i.'" /></td>';
	}
 		$out .='</tr> 
 <tr>
  <td class="descriptionbox">Number of weeks out of work in 1909.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Unemployed'.$i.'" /></td>';
		}
 		$out .='</tr>
  <tr>
  <td class="descriptionbox">Can read.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Read'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Can write.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Write'.$i.'" /></td>';
		}
 		$out .='</tr> 
 <tr>
 <td class="descriptionbox">Attended school anytime since Sept. 1, 1909. 
  </td>';
			for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 Yes:<INPUT TYPE="RADIO" value = "Yes" name = "School'.$i.'"> No:<INPUT TYPE="RADIO" value = "No" name = "School'.$i.'" /></td>';
	}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Own or rent.
   </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 Rent:<INPUT TYPE="RADIO" value = "Rent" name = "OwnRent'.$i.'"> Own:<INPUT TYPE="RADIO" value = "Own" name = "OwnRent'.$i.'" /></td>';
	}
	$out .='</tr>
  <tr>
  <td class="descriptionbox">Owend free or mortgage.
   </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 Owned:<INPUT TYPE="RADIO" value = "Owned" name = "OwnOrMortgaged'.$i.'"> Mortgage:<INPUT TYPE="RADIO" value = "Mortgage" name = "OwnOrMortgaged'.$i.'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">Farm or house.
   </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 Farm:<INPUT TYPE="RADIO" value = "Farm" name = "FarmOrHouse'.$i.'"> House:<INPUT TYPE="RADIO" value = "House" name = "FarmOrHouse'.$i.'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">Number of farm schedule.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "FarmScheduale'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Whether a survivor of the Union or Conferderate Army or Navy.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Army'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Blind 
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Blind'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Deaf and Dumb
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Deaf'.$i.'" /></td>';
		}
 		$out .='</tr> 
 <tr>
  <td class="descriptionbox">Notes.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Notes'.$i.'" /></td>';
		}
 		$out .='</tr>
<tr><td align="center" colspan="'.($_REQUEST['numOfRows']+1).'" class="topbottombar"><br /><input type="submit" value="Finish Form" onclick="javascript:selectPeopleList();selectList();"/></td></tr></table></tr>
<span id="writeroot"></span>
</table>';


        return $out;
    }

    function footer() {
        return '</form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1910&action=func&func=save&taskid=$_REQUEST[taskid]", "center", "1910 United States Federal Census");
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
		 	$sql = "SELECT s_id FROM ".$TBLPREFIX."sources WHERE s_name LIKE '1910 US Census%'";
		 	$res = dbquery($sql);
		 	while($sour =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
       	 		$sourid = $sour["s_id"];
    		}
    		if($sourid == ""){
				// Make our source
				$newsour = "0 @new@ SOUR";
				$newsour .= "\r\n1 TITL 1910 US Census";
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
					$indirec = find_updated_record($personID);
				} else {
					$indirec = find_person_record($personID);
				}
				// Write to the GEDCOM
				$indirec .= "\r\n1 CENS";
				$indirec .= "\r\n2 _RAID ".$_REQUEST['taskid'];
				$indirec .= "\r\n2 DATE ";
				$indirec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1910";
				$indirec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
				$indirec .= "\r\n2 @".$sourid."@";
				$indirec .= "\r\n3 PAGE Page ".$_POST['page'].", Call Number/URL ".$_POST['CallNumberURL'];
				$indirec .= "\r\n3 DATA ";
				$indirec .= "\r\n4 TEXT City ".$_POST['city'].", County ".$_POST['county'].", State ".$_POST['state'].", 1910 US Census";
				for($number = 0; $number < $_POST['numOfRows']; $number++)
				{
					$indirec .=$number==0?"" :"\r\n5 CONT";
					$indirec .= "\r\n5 CONT Dwelling Number.".$_POST["House".$number];
					$indirec .= "\r\n5 CONT Families numbered in order of visitation ".$_POST["Families".$number];
					$indirec .= "\r\n5 CONT The name of each Person  ".$_POST["NameOfPeople".$number];
					$indirec .= "\r\n5 CONT Relationship of each person to the head of the family ".$_POST["Relationship".$number];
					$indirec .= "\r\n5 CONT Sex: Male: M-  Female: F ".$_POST["Sex".$number];
					$indirec .= "\r\n5 CONT Color or Race ".$_POST["Race".$number];
					$indirec .= "\r\n5 CONT Age at last birthday. ".$_POST["Age".$number];
					$indirec .= "\r\n5 CONT Single-S, Married-M, Widowed-W, Divorced-d. ".$_POST["Marrage".$number];
					$indirec .= "\r\n5 CONT Number of years in present marriage. ".$_POST["Married".$number];
					$indirec .= "\r\n5 CONT Mother of how many children.".$_POST["Children".$number];
					$indirec .= "\r\n5 CONT How many of these children are living. ".$_POST["LivingChildren".$number];
					$indirec .= "\r\n5 CONT Place of birth of this person. ".$_POST["PlaceOfBirth".$number];
					$indirec .= "\r\n5 CONT Place of birth of the father. ".$_POST["FathersPlaceOfBirth".$number];
					$indirec .= "\r\n5 CONT Place of birth of the mother. ".$_POST["MothersPlaceOfBirth".$number];
					$indirec .= "\r\n5 CONT Year of immagration to the US. ".$_POST["Immagration".$number];
					$indirec .= "\r\n5 CONT Whether Naturalized or Alien. ".$_POST["Naturalization".$number];
					$indirec .= "\r\n5 CONT Can speak english if not what language. ".$_POST["English".$number];
					$indirec .= "\r\n5 CONT Profession, Occupation or Trade. ".$_POST["Trade".$number];
					$indirec .= "\r\n5 CONT General nature of this persons occupation.. ".$_POST["NatureOfTrade".$number];
					$indirec .= "\r\n5 CONT Is this person an employer, employee, or other. ".$_POST["Employer".$number];
					$indirec .= "\r\n5 CONT Out of work on April 15, 1910. ".$_POST["Employed".$number];
					$indirec .= "\r\n5 CONT Number of weeks out of work in 1909. ".$_POST["Unemployed".$number];
					$indirec .= "\r\n5 CONT Can read. ".$_POST["Read".$number];
					$indirec .= "\r\n5 CONT Can write. ".$_POST["Write".$number];
					$indirec .= "\r\n5 CONT Attended school anytime since Sept. 1, 1909 ".$_POST["School".$number];					
					$indirec .= "\r\n5 CONT Owned or rent. ".$_POST["OwnRent".$number];
					$indirec .= "\r\n5 CONT Owned free or mortgaged. ".$_POST["OwnOrMortgaged".$number];
					$indirec .= "\r\n5 CONT Farm or house. ".$_POST["FarmOrHouse".$number];
					$indirec .= "\r\n5 CONT Number of farm schedule.".$_POST["FarmScheduale".$number];
					$indirec .= "\r\n5 CONT Whether a survivorof the Union or Conferderate Army or Navy.".$_POST["Army".$number];
					$indirec .= "\r\n5 CONT Blind ".$_POST["Blind".$number];
					$indirec .= "\r\n5 CONT Deaf and Dumb ".$_POST["Deaf".$number];
					$indirec .= "\r\n5 CONT Notes.".$_POST["Notes".$number];															
				}
				
				replace_gedrec($personID,$indirec);
			}
			

		// Tell the user their form submitted successfully.
		$out .= ra_functions::print_menu();
		$out .= ra_functions::printMessage("Success!",true);

		// Complete the Task
		ra_functions::completeTask($_REQUEST['taskid']);

		// Return it to the buffer.
		return $out;
    }
    
}
?>
