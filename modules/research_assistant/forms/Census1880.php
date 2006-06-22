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
 * @version $Id: Birth_Information.php 200 2005-11-09 20:37:48Z jporter $
 * @author Joey DiAna
 */
 //-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"Census1880.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

class Census1880 extends ra_form {

    function header($action, $tableAlign, $heading) {
    	global $pgv_lang;
    	//Row Form
    	$out = '<form action="module.php?mod=research_assistant&action=printform&formname=Census1880&taskid='.$_REQUEST['taskid'].'" method="post">';
    	$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
    	for($i = 1; $i <= 100; $i++){
    		$out .= '<option value="'.$i.'">'.$i;
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
    		
//        Start of Table
      
if (!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = 1;

$out = '<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style="border-collapse:collapse;border:none">
 <tr>
  <td class="descriptionbox">
  Dwelling houses numbered in order of visitation
  </td>';
  for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  	$out.='<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="House'.$i.'" /></td>';
  }
  $out .='</tr>
 <tr>
  <td class="descriptionbox">Families numbered in order of visitation
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="Families'.$i.'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">
  The name of each Person
  </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="23" name = "NameOfPeople'.$i.'" /></td>'; 
  		}
 $out .='</tr>
 <tr>
  <td class="descriptionbox">
  Color-White, W: Black, B:
  </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox"><SELECT name ="Race">
		<OPTION VALUE="W">White</option><option value="B">B</option><option value="MU">MU</option><option value="C">C</option>
	</SELECT>
  </td>';
  }
  $out .= '</tr>
 <tr>
  <td class="descriptionbox">Sex: Male: M   Female: F.
   </td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  				$out .= '<td class="optionbox">
 M:<INPUT TYPE="RADIO" value = "Male" name = "Sex'.$i.'"> F:<INPUT TYPE="RADIO" value = "Female" name = "Sex'.$i.'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">Age at last birthday
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Age'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">If born within the Census year give month.
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Month'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Relationship of each person to the head of the family 
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Relationship'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Single 
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Single'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Married 
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Married'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Widowed, Divorced
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "WidowedDivorced'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Profession, Occupation or Trade
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Trade'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Number of months this person has been employed during the
  census year.
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "TimeEmployed'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Is the person sick, or temporarily disabled, so as to be
  unable to attend ordinary business or duties? If so what is the sickness or
  disability?
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Disablity'.$i.'" /></td>';
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
 <td class="descriptionbox">Idiotic
</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Idiotic'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
 <td class="descriptionbox">Insane
</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Insane'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Maimed, crippled, bedridden, or otherwise disabled.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Maimed'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
 <td class="descriptionbox">Attended school within the census year 
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "School'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Can not read
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Read'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Can not write
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name = "Write'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of this person
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "PlaceOfBirth'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of the father
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "FathersPlaceOfBirth'.$i.'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of the mother
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "MothersPlaceOfBirth'.$i.'" /></td>';
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
        $out = $this->header("module.php?mod=research_assistant&form=Census1880&action=func&func=save&taskid=$_REQUEST[taskid]", "center", "1880 United States Federal Census");
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
				$newsour .= "\r\n1 TITL 1880 US Census";
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
				$indirec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1800";
				$indirec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
				$indirec .= "\r\n2 @".$sourid."@";
				$indirec .= "\r\n3 PAGE Page ".$_POST['page'].", Call Number/URL ".$_POST['CallNumberURL'];
				$indirec .= "\r\n3 DATA ";
				$indirec .= "\r\n4 TEXT City ".$_POST['city'].", County ".$_POST['county'].", State ".$_POST['state'].", 1880 US Census";
				for($number = 0; $number < $_POST['Rows']; $number++)
				{
					$indirec .=$number==0?"" :"\r\n5 CONT";
					$indirec .= "\r\n5 CONT Dwelling houses numbered in order of visitation".$_POST["House".$number];
					$indirec .= "\r\n5 CONT Families numbered in order of visitation ".$_POST["Families".$number];
					$indirec .= "\r\n5 CONT The name of each Person whose place of abode on 1st day of ".$_POST["NameOfPeople".$number];
					$indirec .= "\r\n5 CONT Color-White, W: Black, B: ".$_POST["Race".$number];
					$indirec .= "\r\n5 CONT Sex: Male: M-  Female: F ".$_POST["Sex".$number];
					$indirec .= "\r\n5 CONT Age at last birthday ".$_POST["Age".$number];
					$indirec .= "\r\n5 CONT If born within the Census year give month. ".$_POST["Month".$number];
					$indirec .= "\r\n5 CONT Relationship of each person to the head of the family ".$_POST["Relationship".$number];
					$indirec .= "\r\n5 CONT Single ".$_POST["Single".$number];
					$indirec .= "\r\n5 CONT Married ".$_POST["Married".$number];
					$indirec .= "\r\n5 CONT Widowed, Divorced ".$_POST["WidowedDivorced".$number];
					$indirec .= "\r\n5 CONT Profession, Occupation or Trade ".$_POST["Trade".$number];
					$indirec .= "\r\n5 CONT Number of months this person has been employed during the
  								census year. ".$_POST["TimeEmployed".$number];
					$indirec .= "\r\n5 CONT Is the person sick, or temporarily disabled, so as to be
  								unable to attend ordinary business or duties? If so what is the sickness or
  								disability? ".$_POST["Disablity".$number];
					$indirec .= "\r\n5 CONT Blind ".$_POST["Blind".$number];
					$indirec .= "\r\n5 CONT Deaf and Dumb ".$_POST["Deaf".$number];
					$indirec .= "\r\n5 CONT Idiotic ".$_POST["Idiotic".$number];
					$indirec .= "\r\n5 CONT Insane ".$_POST["Insane".$number];
					$indirec .= "\r\n5 CONT Maimed, crippled, bedridden, or otherwise disabled. ".$_POST["Maimed".$number];
					$indirec .= "\r\n5 CONT Attended school within the census year ".$_POST["School".$number];
					$indirec .= "\r\n5 CONT Can not read ".$_POST["Read".$number];
					$indirec .= "\r\n5 CONT Can not write ".$_POST["Write".$number];
					$indirec .= "\r\n5 CONT Place of birth of this person ".$_POST["PlaceOfBirth".$number];
					$indirec .= "\r\n5 CONT Place of birth of the father ".$_POST["FathersPlaceOfBirth".$number];
					$indirec .= "\r\n5 CONT Place of birth of the mother ".$_POST["MothersPlaceOfBirth".$number];
					$indirec .= "\r\n5 CONT Notes. ".$_POST["MothersPlaceOfBirth".$number];
					
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
