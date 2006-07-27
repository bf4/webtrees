<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1930 File
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
 * @author Brandon Gagnon
 */
 //-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"Census1930.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

class Census1930 extends ra_form {

    function header($action, $tableAlign, $heading, $showchoose = false) {
    	global $pgv_lang;
    	$out = "";
    	if ($showchoose) {
	    	//Row Form
	    	$out = '<form action="module.php" method="post">';
	    	$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
	    			'<input type="hidden" name="action" value="printform" />' .
	    			'<input type="hidden" name="formname" value="Census1930" />' .
	    			'<input type="hidden" name="taskid" value="'.$_REQUEST['taskid'].'" />';
	    	if (!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = count($this->getPeople());
	    	if ($_REQUEST['numOfRows']<1) $_REQUEST['numOfRows']=1;
	    	$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
	    	for($i = 1; $i <= 20; $i++){
	    		$out .= '<option value="'.$i;
	    		if ($_REQUEST['numOfRows']==$i) $out .= " selected=\"selected\"";
	    		$out .= '">'.$i;
	    	}
	    	$out .=	'</select></td></tr><tr><td colspan="2" class="topbottombar"><input type="submit" value="'.$pgv_lang["okay"].'"/></td></tr></table>';
	    	$out .= '</form>';
    	}
    	
		// Split action and use it for hidden inputs
        $action = parse_url($action);
        $params = array();
        parse_str(html_entity_decode($action["query"]), $params);
        
        // Setup for our form to go through the module system
        $out .=  '<form action="' . $action["path"] . '" method="post">';
		$out .= '<input type="hidden" name="numOfRows" value="'.$_REQUEST['numOfRows'].'" />';
        foreach ($params as $key => $value) {
            $out .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        $out .= '<table id="Census1930" class="list_table" align="' . $tableAlign . '">';
        $out .= '<tr>';
        $out .= '<th colspan="6" align="right"class="topbottombar"><h2>' . $heading . '</h2></th>';
        $out .= '</tr>';
        return $out;
    }
    
    function getFieldValue($j, $lines) {
    	$value = "";
    	if (empty($lines[$j])) return $value;
    	$line = $lines[$j];
    	$ct = preg_match("/: (.*)/", $line, $match);
    	if ($ct>0) $value = trim($match[1]);
    	return $value;
    }
	
	/**
	 * override method from ra_form.php
	 */
    function simpleCitationForm($citation) {
    	global $pgv_lang, $factarray;
    	if (empty($_POST['data']))
    		$data = array();
    	if (empty($_REQUEST['row'])) {
    		$people = $this->getPeople();
    		$row = count($people);
    	}
    	
    	$citation = $this->getSourceCitationData();
    	$page = "";
    	$callno = "";
    	$date = $citation['ts_date'];
    	$ct = preg_match("/Page: (.*), .*: (.*)/", $citation['ts_page'], $match);
    	if ($ct > 0) {
    		$page = trim($match[1]);
    		$callno = trim($match[2]);
    	}
    	
    	$city = "";
    	$county = "";
    	$state = "";
    	if (!empty($citation['ts_array']['city'])) $city = $citation['ts_array']['city'];
    	if (!empty($citation['ts_array']['county'])) $county = $citation['ts_array']['county'];
    	if (!empty($citation['ts_array']['state'])) $state = $citation['ts_array']['state'];
    	
//        Start of Table
		$out = '<tr>
			<td class="descriptionbox">'.print_help_link("edit_media_help", "qm",'',false,true).$factarray['OBJE'].'</td>
			<td class="optionbox" colspan="5"><input type="text" name="OBJE" id="OBJE" size="5" value="'.$citation['ts_obje'].'"/>';
		$out .= print_findmedia_link("OBJE", true, '', true);
		$out .= '<br /><a href="javascript:;" onclick="pastefield=document.getElementById(\'OBJE\'); window.open(\'addmedia.php?action=showmediaform\', \'\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang["add_media"].'</a>';
		$out .= '</td></tr>';
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["state"].'</td><td class="optionbox"><input name="state" type="text" size="27"  value="'.htmlentities($state).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["call/url"].'</td><td class="optionbox"><input name="CallNumberURL" type="text" size="27" value="'.htmlentities($callno).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["enumDate"].'</td><td class="optionbox"><input name="EnumerationDate" type="text" size="27" value="'.htmlentities($date).'"></td></tr>';
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["county"].'</td><td class="optionbox"><input name="county" type="text" size="27" value="'.htmlentities($county).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["city"].'</td><td class="optionbox"><input name="city" type="text" size="27" value="'.htmlentities($city).'"></td>';
        $out .=	'<td class="descriptionbox">'.$pgv_lang["page"].'</td><td class="optionbox"><input name="page" type="text" size="5" value="'.htmlentities($page).'"></td></tr>';
//        Next Table
        $out .= '<tr><td colspan="6">';
        
        $out .= '<table>
 <tr>
  <td class="descriptionbox">Dwelling number</td>';
  for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  	$value = "";
  	if (isset($citation['ts_array']['rows'][$i]['House'])) $value = $citation['ts_array']['rows'][$i]['House'];
  	$out.='<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="House'.$i.'" value="'.htmlentities($value).'" /></td>';
  }
  $out .='</tr>
 <tr>
  <td class="descriptionbox">Family number</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
  		if (isset($citation['ts_array']['rows'][$i]['Families'])) $value = $citation['ts_array']['rows'][$i]['Families'];
		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="Families'.$i.'" value="'.htmlentities($value).'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">Name</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['NameOfPeople'])) $value = $citation['ts_array']['rows'][$i]['NameOfPeople'];
  			$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="23" name = "NameOfPeople'.$i.'" value="'.htmlentities($value).'" /></td>'; 
  		}
 $out .='</tr>
 <tr>
  <td class="descriptionbox">Relationship to the head of the family</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Relationship'])) $value = $citation['ts_array']['rows'][$i]['Relationship'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="Relationship'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Owned or rented</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Owned'])) $value = $citation['ts_array']['rows'][$i]['Owned'];
  				$out .= '<td class="optionbox"><select name="Owned'.$i.'">
		<option value=""'.($value==''?' selected="selected"':'').'></option>
		<option value="Owned"'.($value=='Owned'?' selected="selected"':'').'>Owned</option>
		<option value="Rented"'.($value=='Rented'?' selected="selected"':'').'>Rented</option>
	</select>
  </td>';
  }
  $out .= '</tr>
 <tr>
  <td class="descriptionbox">Value of home, if owned, <br />or monthly rental, if rented</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['HomeValue'])) $value = $citation['ts_array']['rows'][$i]['HomeValue'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="HomeValue'.$i.'" value="'.htmlentities($value).'" /></td>';
  }
  $out .= '</tr>
<tr>
  <td class="descriptionbox">Radio Set</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Radio'])) $value = $citation['ts_array']['rows'][$i]['Radio'];
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name="Radio'.$i.'" value="Radio Set"'.($value=='Radio Set'?' checked="checked"':'').'/></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Does this family live on a farm</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['LiveFarm'])) $value = $citation['ts_array']['rows'][$i]['LiveFarm'];
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name="LiveFarm'.$i.'" value="Live on a farm"'.($value=='Live on a farm'?' checked="checked"':'').'/></td>';
  }
  $out .= '</tr>
<tr>
  <td class="descriptionbox">Sex: Male: M   Female: F.</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Sex'])) $value = $citation['ts_array']['rows'][$i]['Sex'];
  				$out .= '<td class="optionbox">
 				Male:<INPUT TYPE="RADIO" value="M" name="Sex'.$i.'"'.($value=='M'?' checked="checked"':'').' /> 
				Female:<INPUT TYPE="RADIO" value="F" name="Sex'.$i.'"'.($value=='F'?' checked="checked"':'').' /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox">Color or Race</td>';
    for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Race'])) $value = $citation['ts_array']['rows'][$i]['Race'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="6" name = "Race'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
  $out .= '</tr>
 <tr>
 <td class="descriptionbox">Age at last birthday</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Age'])) $value = $citation['ts_array']['rows'][$i]['Age'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="10" name = "Age'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Marital Condition</td>';
    for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Single'])) $value = $citation['ts_array']['rows'][$i]['Single'];
  		$out .= '<td class="optionbox"><select name="Single'.$i.'">
		<option value=""'.($value==''?' selected="selected"':'').'></option>
		<option value="Single"'.($value=='Single'?' selected="selected"':'').'>Single</option>
		<option value="Married"'.($value=='Married'?' selected="selected"':'').'>Married</option>
		<option value="Widowed"'.($value=='Widowed'?' selected="selected"':'').'>Widowed</option>
		<option value="Divorced"'.($value=='Divorced'?' selected="selected"':'').'>Divorced</option>
	</select>
  </td>';
		}
 		$out .='</tr>
<tr>
 <td class="descriptionbox">Age at first marriage</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['MarriageAge'])) $value = $citation['ts_array']['rows'][$i]['MarriageAge'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="10" name="MarriageAge'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Attended School</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['School'])) $value = $citation['ts_array']['rows'][$i]['School'];
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name="School'.$i.'" value="Attended School"'.($value=='Attended School'?' checked="checked"':'').'/></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Can read and write</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Read'])) $value = $citation['ts_array']['rows'][$i]['Read'];
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name="Read'.$i.'" value="Can read and write"'.($value=='Can read and write'?' checked="checked"':'').'/></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of this person
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['PlaceOfBirth'])) $value = $citation['ts_array']['rows'][$i]['PlaceOfBirth'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="PlaceOfBirth'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of the father
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['FathersPlaceOfBirth'])) $value = $citation['ts_array']['rows'][$i]['FathersPlaceOfBirth'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="FathersPlaceOfBirth'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Place of birth of the mother
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['MothersPlaceOfBirth'])) $value = $citation['ts_array']['rows'][$i]['MothersPlaceOfBirth'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="MothersPlaceOfBirth'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Mother Tongue
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['MotherTongue'])) $value = $citation['ts_array']['rows'][$i]['MotherTongue'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name="MotherTongue'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
 <td class="descriptionbox">Year of immigration to the US</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['ImmigrationYear'])) $value = $citation['ts_array']['rows'][$i]['ImmigrationYear'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="10" name="ImmigrationYear'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
 <td class="descriptionbox">Naturalization</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Naturalization'])) $value = $citation['ts_array']['rows'][$i]['Naturalization'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="10" name="Naturalization'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Can speak English</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Speak'])) $value = $citation['ts_array']['rows'][$i]['Speak'];
  		$out .= '<td class="optionbox"><INPUT TYPE="CHECKBOX" name="Speak'.$i.'" value="Can speak English"'.($value=='Can speak English'?' checked="checked"':'').'/></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Occupation
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Occupation'])) $value = $citation['ts_array']['rows'][$i]['Occupation'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Occupation'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox">Industry
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Industry'])) $value = $citation['ts_array']['rows'][$i]['Industry'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="22" name = "Industry'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Class of worker</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Employer'])) $value = $citation['ts_array']['rows'][$i]['Employer'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="10" name = "Employer'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Whether actually at work</td>';
    for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['AtWork'])) $value = $citation['ts_array']['rows'][$i]['AtWork'];
  		$out .= '<td class="optionbox"><select name="AtWork'.$i.'">
		<option value=""'.($value==''?' selected="selected"':'').'></option>
		<option value="Yes"'.($value=='Yes'?' selected="selected"':'').'>Yes</option>
		<option value="No"'.($value=='No'?' selected="selected"':'').'>No</option>
	</select>
  </td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">Whether a Veteran</td>';
    for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Veteran'])) $value = $citation['ts_array']['rows'][$i]['Veteran'];
  		$out .= '<td class="optionbox"><select name="Veteran'.$i.'">
		<option value=""'.($value==''?' selected="selected"':'').'></option>
		<option value="Yes"'.($value=='Yes'?' selected="selected"':'').'>Yes</option>
		<option value="No"'.($value=='No'?' selected="selected"':'').'>No</option>
	</select>
  </td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox">What war or expidition</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['War'])) $value = $citation['ts_array']['rows'][$i]['War'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="20" name="War'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
 <td class="descriptionbox">Number of farm schedule 
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['FarmSchedule'])) $value = $citation['ts_array']['rows'][$i]['FarmSchedule'];
  		$out .= '<td class="optionbox"><INPUT TYPE="TEXT" SIZE="6" name = "FarmSchedule'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>';
 			
 		$out .='<tr><td class="descriptionbox">Person
  </td>';		
 		
 		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$pid = "";
	  		if (isset($citation['ts_array']['rows'][$i]['personid'])) $pid = $citation['ts_array']['rows'][$i]['personid'];
  			$person = Person::GetInstance($pid);
  			
			$out .= '
	            <td id="peoplecell" class="optionbox">
	                   <div id="peoplelink'.$i.'">';
	                   		if (!is_null($person)) $out .= '<a id="link_'.$pid.'" href="individual.php?pid='.$pid.'">'.$person->getName().'</a> <a id="rem_'.$pid.'" href="#" onclick="clearname(\'personid\', \'link_'.$pid.'\', \''.$pid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
	                   $out .= '</div>
	                   <input type="hidden" id="personid'.$i.'" name="personid'.$i.'" size="3" value="'.$pid.'" />';
	                   $out .= print_findindi_link("personid".$i, "peoplelink".$i, true);
	                   $out .= '<br /></td>';
	        
		}
 		
 	$out .= '</tr></table>';
        
        $out .= '</td></tr>';
        return $out;
    }

    function footer() {
        return '</table></form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1930&action=func&func=step2&taskid=$_REQUEST[taskid]", "center", "1930 United States Federal Census", true);
        $out .= $this->sourceCitationForm(5,false);
        //$out .= $this->content();
        $out .= $this->footer();
        return $out;
    }
    
    function step2() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $factarray, $pgv_lang;
		global $INDI_FACTS_ADD;
			
		$personid = "";
		for($number = 0; $number < $_POST['numOfRows']; $number++)
		{
			if (!isset($_POST["personid".$number])) $_POST["personid".$number]="";
			$personid .= $_POST["personid".$number];
			$_POST["personid".$number] = trim($_POST["personid".$number], '; \r\n\t');
		}
		$_REQUEST['personid'] = $personid;
		
		
		$this->processSourceCitation();
		
		$out = $this->header("module.php?mod=research_assistant&form=Census1930&action=func&func=step3&taskid=" . $_REQUEST['taskid'], "center", "1930 United States Federal Census");
		$out .= $this->editFactsForm();
		$out .= $this->footer();
		return $out;
	}
	
	function step3() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $pgv_lang;

		$out = $this->processFactsForm();

		// Complete the Task.
		ra_functions::completeTask($_REQUEST['taskid'], $_REQUEST['form']);
		// Tell the user their form submitted successfully.
		$out .= ra_functions::print_menu();
		$out .= ra_functions::printMessage("Success!",true);

		// Return it to the buffer.
		return $out;
	}

	/**
	 * Override method from ra_form
	 */
    function processSimpleCitation() {
    	global $TBLPREFIX, $DBCONN;
    	//-- delete any old census records
    	$sql = "DELETE FROM ".$TBLPREFIX."taskfacts WHERE tf_t_id='".$DBCONN->escapeSimple($_REQUEST['taskid'])."' AND tf_factrec LIKE '1 CENS%'";
    	$res = dbquery($sql);
    	
		// Set our output to nothing, this supresses a warning that we would otherwise get.
		$out = "";
		$factrec = "1 CENS";
		$factrec .= "\r\n2 DATE ";
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1930";
		$factrec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
		
		$people = $this->getPeople();
		$pids = array_keys($people);
		//-- store the fact associations in the database
		$sql = "INSERT INTO ".$TBLPREFIX."taskfacts VALUES('".get_next_id("taskfacts", "tf_id")."'," .
			"'".$DBCONN->escapeSimple($_REQUEST['taskid'])."'," .
			"'".$DBCONN->escapeSimple($factrec)."'," .
			"'".$DBCONN->escapeSimple(implode(";", $pids))."')";
		$res = dbquery($sql);
		
		$rows = array();
		$text = $_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", 1930 US Census";
		for($number = 0; $number < $_POST['numOfRows']; $number++)
		{
			if (!isset($_POST["House".$number])) $_POST["House".$number]="";
			if (!isset($_POST["Families".$number])) $_POST["Families".$number]="";
			if (!isset($_POST["NameOfPeople".$number])) $_POST["NameOfPeople".$number]="";
			if (!isset($_POST["Relationship".$number])) $_POST["Relationship".$number]="";
			if (!isset($_POST["Owned".$number])) $_POST["Owned".$number]="";
			if (!isset($_POST["HomeValue".$number])) $_POST["HomeValue".$number]="";
			if (!isset($_POST["Radio".$number])) $_POST["Radio".$number]="";
			if (!isset($_POST["LiveFarm".$number])) $_POST["LiveFarm".$number]="";
			if (!isset($_POST["Sex".$number])) $_POST["Sex".$number]="";
			if (!isset($_POST["Race".$number])) $_POST["Race".$number]="";
			if (!isset($_POST["Age".$number])) $_POST["Age".$number]="";
			if (!isset($_POST["Single".$number])) $_POST["Single".$number]="";
			if (!isset($_POST["MarriageAge".$number])) $_POST["MarriageAge".$number]="";
			if (!isset($_POST["School".$number])) $_POST["School".$number]="";			
			if (!isset($_POST["Read".$number])) $_POST["Read".$number]="";
			if (!isset($_POST["PlaceOfBirth".$number])) $_POST["PlaceOfBirth".$number]="";
			if (!isset($_POST["FathersPlaceOfBirth".$number])) $_POST["FathersPlaceOfBirth".$number]="";
			if (!isset($_POST["MothersPlaceOfBirth".$number])) $_POST["MothersPlaceOfBirth".$number]="";
			if (!isset($_POST["MotherTongue".$number])) $_POST["MotherTongue".$number]="";
			if (!isset($_POST["ImmigartionYear".$number])) $_POST["ImmigartionYear".$number]="";
			if (!isset($_POST["Naturalization".$number])) $_POST["Naturalization".$number]="";
			if (!isset($_POST["Speak".$number])) $_POST["Speak".$number]="";
			if (!isset($_POST["Occupation".$number])) $_POST["Occupation".$number]="";
			if (!isset($_POST["Industry".$number])) $_POST["Industry".$number]="";
			if (!isset($_POST["Employer".$number])) $_POST["Employer".$number]="";
			if (!isset($_POST["AtWork".$number])) $_POST["AtWork".$number]="";
			if (!isset($_POST["Veteran".$number])) $_POST["Veteran".$number]="";
			if (!isset($_POST["War".$number])) $_POST["War".$number]="";
			if (!isset($_POST["FarmSchedule".$number])) $_POST["FarmSchedule".$number]="";
			if (!isset($_POST["personid".$number])) $_POST["personid".$number]="";
			
			$rows[$number] = array(
			"House"=>$_POST["House".$number],
			"Families"=>$_POST["Families".$number],
			"NameOfPeople"=>$_POST["NameOfPeople".$number],
			"Relationship"=>$_POST["Relationship".$number],
			"Owned"=>$_POST["Owned".$number],
			"HomeValue"=>$_POST["HomeValue".$number],
			"Radio"=>$_POST["Radio".$number],
			"LiveFarm"=>$_POST["LiveFarm".$number],
			"Sex"=>$_POST["Sex".$number],
			"Race"=>$_POST["Race".$number],
			"Age"=>$_POST["Age".$number],
			"Single"=>$_POST["Single".$number],
			"MarriageAge"=>$_POST["MarriageAge".$number],
			"School"=>$_POST["School".$number],
			"Read"=>$_POST["Read".$number],
			"PlaceOfBirth"=>$_POST["PlaceOfBirth".$number],
			"FathersPlaceOfBirth"=>$_POST["FathersPlaceOfBirth".$number],
			"MothersPlaceOfBirth"=>$_POST["MothersPlaceOfBirth".$number],
			"MotherTongue"=>$_POST["MotherTongue".$number],
			"ImmigartionYear"=>$_POST["ImmigartionYear".$number],
			"Naturalization"=>$_POST["Naturalization".$number],
			"Speak"=>$_POST["Speak".$number],
			"Occupation"=>$_POST["Occupation".$number],
			"Industry"=>$_POST["Industry".$number],
			"Employer"=>$_POST["Employer".$number],
			"AtWork"=>$_POST["AtWork".$number],
			"Veteran"=>$_POST["Veteran".$number],
			"War"=>$_POST["War".$number],
			"FarmSchedule"=>$_POST["FarmSchedule".$number],
			"personid"=>$_POST["personid".$number]
			);
			
			$text .= "\r\n";
			if (!empty($_POST["House".$number])) $text .= "Dwelling number: ".$_POST["House".$number];
			if (!empty($_POST["Families".$number])) $text .= " Family number: ".$_POST["Families".$number];
			if (!empty($_POST["NameOfPeople".$number])) $text .= " Name: ".$_POST["NameOfPeople".$number];
			if (!empty($_POST["Relationship".$number])) $text .= ", Relation: ".$_POST["Relationship".$number];
			if (!empty($_POST["Owned".$number])) $text .= ", ".$_POST["Owned".$number];
			if (!empty($_POST["HomeValue".$number])) $text .= ", Home Value:".$_POST["HomeValue".$number];
			if (!empty($_POST["Radio".$number])) $text .= ", ".$_POST["Radio".$number];
			if (!empty($_POST["LiveFarm".$number])) $text .= ", ".$_POST["LiveFarm".$number];
			if (!empty($_POST["Sex".$number])) $text .= ", Sex: ".$_POST["Sex".$number];
			if (!empty($_POST["Race".$number])) $text .= ", Race: ".$_POST["Race".$number];
			if (!empty($_POST["Age".$number])) $text .= ", Age: ".$_POST["Age".$number];
			if (!empty($_POST["Single".$number])) $text .= ", ".$_POST["Single".$number];
			if (!empty($_POST["MarriageAge".$number])) $text .= ", Age at first marriage: ".$_POST["MarriageAge".$number];
			if (!empty($_POST["School".$number])) $text .= ", ".$_POST["School".$number];
			if (!empty($_POST["Read".$number])) $text .= ", ".$_POST["Read".$number];
			if (!empty($_POST["PlaceOfBirth".$number])) $text .= ", Place of birth: ".$_POST["PlaceOfBirth".$number];
			if (!empty($_POST["FathersPlaceOfBirth".$number])) $text .= ", Father's Place of birth: ".$_POST["FathersPlaceOfBirth".$number];
			if (!empty($_POST["MothersPlaceOfBirth".$number])) $text .= ", Mother's Place of birth: ".$_POST["MothersPlaceOfBirth".$number];
			if (!empty($_POST["MotherTongue".$number])) $text .= ", ".$_POST["MotherTongue".$number];
			if (!empty($_POST["ImmigartionYear".$number])) $text .= ", Immigration Year: ".$_POST["ImmigartionYear".$number];
			if (!empty($_POST["Naturalization".$number])) $text .= ", ".$_POST["Naturalization".$number];
			if (!empty($_POST["Speak".$number])) $text .= ", ".$_POST["Speak".$number];
			if (!empty($_POST["Occupation".$number])) $text .= ", Occupation: ".$_POST["Occupation".$number];
			if (!empty($_POST["Industry".$number])) $text .= ", Industry: ".$_POST["Industry".$number];
			if (!empty($_POST["Employer".$number])) $text .= ", ".$_POST["Employer".$number];
			if (!empty($_POST["AtWork".$number])) $text .= ", ".$_POST["AtWork".$number];
			if (!empty($_POST["Veteran".$number])) $text .= ", ".$_POST["Veteran".$number];
			if (!empty($_POST["War".$number])) $text .= ", ".$_POST["War".$number];
			if (!empty($_POST["FarmSchedule".$number])) $text .= ", Farm Schedule:".$_POST["FarmSchedule".$number];
			if (!empty($_POST["personid".$number])) $text .= ", Persons ID: ".$_POST["personid".$number];
		
		}

		$citation = array(
			"PAGE"=>"Page: ".$_POST['page'].", Call Number/URL: ".$_POST['CallNumberURL'], 
			"QUAY"=>'', 
    		"DATE"=>!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1930", 
			"TEXT"=>$text, 
			"OBJE"=>$_POST['OBJE'],
			"array"=>array(
			'city'=>$_POST['city'],
			'county'=>$_POST['county'],
			'state'=>$_POST['state'],
			'rows'=>$rows));
		
		return $citation;
    }
    
}
?>