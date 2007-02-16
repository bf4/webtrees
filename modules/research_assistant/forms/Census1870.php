<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1870 File
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
if (strstr($_SERVER["SCRIPT_NAME"],"Census1870.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

class Census1870 extends ra_form {

    function header($action, $tableAlign, $heading, $showchoose = false) {
    	global $pgv_lang;
    	$out = "";
    	if ($showchoose) {
	    	//Row Form
	    	$out = '<form action="module.php" method="post">';
	    	$out .= '<INPUT tabindex="1"  type="hidden" name="mod" value="research_assistant" />' .
	    			'<INPUT tabindex="2"  type="hidden" name="action" value="printform" />' .
	    			'<INPUT tabindex="3"  type="hidden" name="formname" value="Census1870" />' .
	    			'<INPUT tabindex="4"  type="hidden" name="taskid" value="'.$_REQUEST['taskid'].'" />';
	    	if (!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = count($this->getPeople());
	    	if ($_REQUEST['numOfRows']<1) $_REQUEST['numOfRows']=1;
	    	$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
	    	for($i = 1; $i <= 20; $i++){
	    		$out .= '<option value="'.$i.'"';
	    		if ($_REQUEST['numOfRows']==$i) $out .= " selected=\"selected\"";
	    		$out .= '>'.$i;
	    	}
	    	$out .=	'</select></td></tr><tr><td colspan="2" class="topbottombar"><INPUT tabindex="5"  type="submit" value="'.$pgv_lang["okay"].'"/></td></tr></table>';
	    	$out .= '</form>';
    	}
    	
		// Split action and use it for hidden inputs
        $action = parse_url($action);
        $params = array();
        parse_str(html_entity_decode($action["query"]), $params);
        
        // Setup for our form to go through the module system
         $out .= '<script language="JavaScript" type="text/javascript">
<!--
function ValidateForm(myForm){ if(myForm.sourceid && myForm.sourceid.value == ""){ alert("You must enter a source");
return false;}return true;}
//-->
</script>';
        $out .=  '<form action="' . $action["path"] . '" method="post" onsubmit="return ValidateForm(this)">';
		$out .= '<INPUT tabindex="6"  type="hidden" name="numOfRows" value="'.$_REQUEST['numOfRows'].'" />';
        foreach ($params as $key => $value) {
            $out .= '<INPUT tabindex="7"  type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        $out .= '<table id="Census1870" class="list_table" align="' . $tableAlign . '">';
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
			<td class="optionbox" colspan="5"><input type="hidden" name="OBJE" id="OBJE" value="'.$citation['ts_obje'].'"/>';
			$out .= "<div id=\"censusPicDiv\" style=\"display";
			if(!empty($citation['ts_obje']))
			{
				$out .= ":block\">";
				/*@var $picture Media*/
				$picture = Media::getInstance($citation['ts_obje']);
				if(!is_null($picture))
				{	
					$out .= "<span id=\"censusImgSpan\">".$picture->getTitle().'</span><br/><img id="censusImage" src="'.$picture->getThumbnail().'" />';
				}
				else
				{
					$out .= "<span id=\"censusImgSpan\"></span><br /><img id=\"censusImage\" src=\"\" />";
				}
			}
			else
			{
				$out .= ":none\">";
				$out .= "<span id=\"censusImgSpan\"></span><br /><img id=\"censusImage\" src=\"\" />";
			}
			$out .="</div>";
		$out .= print_findmedia_link("OBJE", true, '', true);
		$out .= '<br /><a href="javascript:;" onclick="pastefield=document.getElementById(\'OBJE\'); window.open(\'addmedia.php?action=showmediaform\', \'\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang["add_media"].'</a>';
		$out .= '</td></tr>';
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["state"].'</td><td class="optionbox"><INPUT tabindex="9"  name="state" type="text" size="27"  value="'.htmlentities($state).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["call/url"].'</td><td class="optionbox"><INPUT tabindex="10"  name="CallNumberURL" type="text" size="27" value="'.htmlentities($callno).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["enumDate"].'</td><td class="optionbox"><INPUT tabindex="11"  name="EnumerationDate" type="text" size="27" value="'.htmlentities($date).'"></td></tr>';
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["county"].'</td><td class="optionbox"><INPUT tabindex="12"  name="county" type="text" size="27" value="'.htmlentities($county).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["city"].'</td><td class="optionbox"><INPUT tabindex="13"  name="city" type="text" size="27" value="'.htmlentities($city).'"></td>';
        $out .=	'<td class="descriptionbox">'.$pgv_lang["page"].'</td><td class="optionbox"><INPUT tabindex="14"  name="page" type="text" size="5" value="'.htmlentities($page).'"></td></tr>';
//        Next Table
        $out .= '<tr><td colspan="6">';
        
        $out .= '<table align="left" dir="ltr">
 <tr>
  <td class="descriptionbox" align="left">Dwelling Number</td>';
  for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  	$value = "";
  	if (isset($citation['ts_array']['rows'][$i]['Dwelling'])) $value = $citation['ts_array']['rows'][$i]['Dwelling'];
  	$out.='<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+15).'" TYPE="TEXT" SIZE="22" name="Dwelling'.$i.'" value="'.htmlentities($value).'" /></td>';
  }
  $out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Family Number</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
  		if (isset($citation['ts_array']['rows'][$i]['Families'])) $value = $citation['ts_array']['rows'][$i]['Families'];
		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+16).'" TYPE="TEXT" SIZE="22" name="Families'.$i.'" value="'.htmlentities($value).'" /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Name</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['NameOfPeople'])) $value = $citation['ts_array']['rows'][$i]['NameOfPeople'];
  			$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+17).'"  TYPE="TEXT" SIZE="23" name = "NameOfPeople'.$i.'" value="'.htmlentities($value).'" /></td>'; 
  		}
 $out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Age</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Age'])) $value = $citation['ts_array']['rows'][$i]['Age'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+18).'"  TYPE="TEXT" SIZE="22" name = "Age'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Sex</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Sex'])) $value = $citation['ts_array']['rows'][$i]['Sex'];
  				$out .= '<td class="optionbox" align="left">
 				Male:<INPUT tabindex="'.($i*100+19).'"  TYPE="RADIO" value="M" name="Sex'.$i.'"'.($value=='M'?' checked="checked"':'').' /> 
				Female:<INPUT tabindex="'.($i*100+20).'"  TYPE="RADIO" value="F" name="Sex'.$i.'"'.($value=='F'?' checked="checked"':'').' /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Color</td>';
  		for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
  			$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Race'])) $value = $citation['ts_array']['rows'][$i]['Race'];
  				$out .= '<td class="optionbox" align="left"><select name="Race'.$i.'" tabindex="'.($i*100+21).'">
		<option value="W"'.($value=='W'?' selected="selected"':'').'>White</option>
		<option value="B"'.($value=='B'?' selected="selected"':'').'>Black</option>
		<option value="MU"'.($value=='MU'?' selected="selected"':'').'>Mulatto</option>
		<option value="Ch"'.($value=='Ch'?' selected="selected"':'').'>Chinese</option>
		<option value="In"'.($value=='In'?' selected="selected"':'').'>Indian</option>
	</select>
  </td>';}
$out .= '</tr>
<tr>
  <td class="descriptionbox" align="left">Profession, Occupation, or Trade
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Trade'])) $value = $citation['ts_array']['rows'][$i]['Trade'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+22).'"  TYPE="TEXT" SIZE="22" name = "Trade'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>';
  				
  
  $out .= '</tr>
 <tr>
  <td class="descriptionbox" align="left">Value of Real estate</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['realestate'])) $value = $citation['ts_array']['rows'][$i]['realestate'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+23).'"  TYPE="TEXT" SIZE="22" name = "realestate'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Value of Personal estate</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['personalestate'])) $value = $citation['ts_array']['rows'][$i]['personalestate'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+24).'"  TYPE="TEXT" SIZE="22" name = "personalestate'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Place of Birth Naming the State, Territory, or Country</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['PofB'])) $value = $citation['ts_array']['rows'][$i]['PofB'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+25).'"  TYPE="TEXT" SIZE="22" name = "PofB'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 
 <tr>
  <td class="descriptionbox" align="left">Father of foreign born
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['fatherborn'])) $value = $citation['ts_array']['rows'][$i]['fatherborn'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+26).'"  TYPE="TEXT" SIZE="22" name = "fatherborn'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Mother of foreign born
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['motherborn'])) $value = $citation['ts_array']['rows'][$i]['motherborn'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+27).'"  TYPE="TEXT" SIZE="22" name = "motherborn'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
 		<tr>
  <td class="descriptionbox" align="left">If born within the year, state month (Jan, & c.)
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['borninyear'])) $value = $citation['ts_array']['rows'][$i]['borninyear'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+28).'"  TYPE="TEXT" SIZE="22" name = "borninyear'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
	<tr>
  <td class="descriptionbox" align="left">If Married within the year, state month (Jan, & c.)
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['marriedinyear'])) $value = $citation['ts_array']['rows'][$i]['marriedinyear'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+29).'"  TYPE="TEXT" SIZE="22" name = "marriedinyear'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Attended school within the year
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['schoolinyear'])) $value = $citation['ts_array']['rows'][$i]['schoolinyear'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+30).'"  TYPE="TEXT" SIZE="22" name = "schoolinyear'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Cannot read</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['cannotread'])) $value = $citation['ts_array']['rows'][$i]['cannotread'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+31).'"  TYPE="CHECKBOX" value="cannotread" name="cannotread'.$i.'" '.($value=='cannotread'?' checked="checked"':'').' /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Cannot write</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['cannotwrite'])) $value = $citation['ts_array']['rows'][$i]['cannotwrite'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+32).'"  TYPE="CHECKBOX" value="cannotwrite" name="cannotwrite'.$i.'" '.($value=='cannotwrite'?' checked="checked"':'').' /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Whether deaf and dumb, blind, insane, idiotic, pauper, or convict
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['Disablity'])) $value = $citation['ts_array']['rows'][$i]['Disablity'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+33).'"  TYPE="TEXT" SIZE="22" name = "Disablity'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>
<tr>
  <td class="descriptionbox" align="left">Male citizens of U.S. of 21 years of age and upwards
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['21andup'])) $value = $citation['ts_array']['rows'][$i]['21andup'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+34).'"  TYPE="TEXT" SIZE="22" name = "21andup'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>

<tr>
  <td class="descriptionbox" align="left">Male citizens of U.S. of 21 years of age and upwards where rights<br /> to vote is denied on other grounds than rebellion or other crime
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
	  		if (isset($citation['ts_array']['rows'][$i]['21andupbanned'])) $value = $citation['ts_array']['rows'][$i]['21andupbanned'];
  		$out .= '<td class="optionbox" align="left"><INPUT tabindex="'.($i*100+35).'"  TYPE="TEXT" SIZE="22" name = "21andupbanned'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
 		$out .='</tr>'
 		;
        $out .= 
'<tr>
  <td class="descriptionbox" align="left">Person
  </td>';
  $people = $this->getPeople();
  $persons = array_values($people);
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$pid = "";
	  		if (isset($citation['ts_array']['rows'][$i]['personid'])) $pid = $citation['ts_array']['rows'][$i]['personid'];
	  		if (empty($pid)) {
	  			if (isset($persons[$i])) $pid = $persons[$i]->getXref();
	  		}
  			$person = Person::GetInstance($pid);
  			
			$out .= '
	            <td id="peoplecell" class="optionbox">
	                   <div id="peoplelink'.$i.'">';
	                   		if (!is_null($person)) $out .= '<a id="link_'.$pid.'" href="individual.php?pid='.$pid.'">'.$person->getName().'</a> <a id="rem_'.$pid.'" href="#" onclick="clearname(\'personid\', \'link_'.$pid.'\', \''.$pid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
	                   $out .= '</div>
	                   <INPUT tabindex="'.($i*100+36).'"  type="hidden" id="personid'.$i.'" name="personid'.$i.'" size="3" value="'.$pid.'" />';
	                   if(isset($citation['ts_array']['rows'][$i]['Families'])) $searchName = $citation['ts_array']['rows'][$i]['Families'];
						else $searchName = '';
	                   $out .= print_findindi_link("personid".$i, "peoplelink".$i, true,false,'',$searchName);
	                   $out .= "<br />Create New Person: <input type=\"checkbox\" value=\"newPerson\"/>";
	                   $out .= '<br /></td>';
	        
		}
 		$out .='</tr></table>';
 		$out .= '</td></tr>';
        
        return $out;
    }

    function footer() {
        return '</table></form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1870&action=func&func=step2&taskid=".$_REQUEST['taskid'], "center", "1870 United States Federal Census", true);
        $out .= $this->sourceCitationForm(5, false);
        //$out .= $this->content();
        $out .= $this->footer();
        return $out;
    }
    
    function createPerson($i)
    {
    	$indiFact = "0 @new@ INDI\r\n";
    	$indiFact .= "1 NAME ".$_POST["NameOfPeople".$i]."\r\n";
    	
    	if(!empty($_POST["Age".$i]))
    	{
    		$age = 1870 - $_POST["Age".$i];
    		$indiFact .= "1 BIRT\r\n";
    		$indiFact .= "2 DATE ABT ".$age;
    	}
    	
    	if(!empty($_POST["PlaceOfBirth".$i]))
    	{
    		$indiFact .= "2 PLAC ".$_POST["PlaceOfBirth"];
    	}
    	
    	if(!empty($_POST["Sex".$i]))
    	{
    		$indiFact .= "1 SEX ".$_POST["Sex".$i];
    	}
    	
    	return $indiFact;
    	
    }
    
    function step2() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $factarray, $pgv_lang;
		global $INDI_FACTS_ADD;
		
		$people = array();
		$pids = array();
		$positions = array();
			
		$personid = "";
		for($number = 0; $number < $_POST['numOfRows']; $number++)
		{
			if(!empty($_POST["newPerson".$number]))
			{
				$tempPerson = $this->createPerson($number);
				$_POST["personid".$number] = append_gedrec($tempPerson,true,'');
			}
			
			if (!isset($_POST["personid".$number])) $_POST["personid".$number]="";
			$personid .= $_POST["personid".$number].";";
			$_POST["personid".$number] = trim($_POST["personid".$number], '; \r\n\t');
			
			
		}
		
		$_REQUEST['personid'] = $personid;
		$return = $this->processSourceCitation();
		
		if(empty($return))
		{
		$out = $this->header("module.php?mod=research_assistant&form=Census1870&action=func&func=step3&taskid=" . $_REQUEST['taskid'], "center", "1870 United States Federal Census");
		$out .= $this->editFactsForm(false);
		$out .= $this->footer();
		return $out;
		}
		else
		{
			
		}
	}
	
	function editFactsForm($printButton = true)
	{
		global $factarray, $pgv_lang;
		
		$facts = $this->getFactData();
		$citation = $this->getSourceCitationData();
		$out = parent::editFactsForm(false);
		$rows = $citation['ts_array']['rows'];
		$inferFacts = $this->inferFacts($rows);
		
		if(!empty($inferFacts))
		{

		$out .= '<tr><td colspan="2" id="inferData"><table class="list_table"><tbody><tr><td colspan="4" class="topbottombar">'.$pgv_lang["ra_inferred_facts"].'</td></tr>
<tr><td class="descriptionbox">'.$pgv_lang["ra_fact"].'</td><td class="descriptionbox">'.$pgv_lang["ra_person"].'</td><td class="descriptionbox">'.$pgv_lang["ra_reason"].'</td><td class="descriptionbox">'.$pgv_lang["add"].'</td></tr>'; 
		$completeFact = true;
		$occufact = true;
		foreach($inferFacts as $key=>$inferredFacts) {
			foreach($inferredFacts as $id=>$value) {
				$completeFact = true;
					foreach($facts as $factKey=>$factValues)
					{
						$ct = preg_match("/1 (\w+)/", $factValues['tf_factrec'], $match);						
						$factname = trim($match[1]);
						
						if($factValues["tf_people"] == $key  && $factname == $value["factType"])	
						{
							$completeFact = false;
						}
					
					}
					
					if($completeFact)
					{
						$out .='<tr>';
						$out .="<td class=\"optionbox\">".$factarray[$value['factType']]." ".$value['date']."</td>";
						$out .="<td class=\"optionbox\">".$value["Person"]."</td>";
						$out .="<td class=\"optionbox\">".$value["Reason"]."</td>";
						$out .="<td class=\"optionbox\">".'<INPUT type="Checkbox" id="'.$value['PersonID'].$value['factType'].'" onclick="add_ra_fact_inferred(this,\''.preg_replace("/\r?\n/", "\\r\\n",$value["Fact"]).'\',\''.$value['PersonID'].'\',\''.$value['factType'].'\',\''.htmlentities($value["Person"]).'\',\''.$value["factPeople"].'\')"></td>'."\n";
						$out .="</tr>";
					}
				
				}
		}
				
			
		
		$out .= '<tr><td class="descriptionbox" align="center" colspan="4"><INPUT tabindex="30" type="submit" value='.$pgv_lang["complete"].'></td></tr>'; 
		return $out;
	}
	}
	
	function step3() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $pgv_lang;

		$out = $this->processFactsForm();

		// Complete the Task.
		ra_functions::completeTask($_REQUEST['taskid'], $_REQUEST['form']);
		// Tell the user their form submitted successfully.
		$out .= ra_functions::print_menu();
		$out .= ra_functions::printMessage($pgv_lang["success"],true);
		
		// Return it to the buffer.
		return $out;
	}

	function getOccupation($gedcomRecord)
	{
		$occupation = get_gedcom_value("OCCU", 1, $gedcomRecord);
		return $occupation;
	}

	/**
	 * This is a function that will attempt to infer facts from the census form.
	 * If any facts can be inferred then it will attempt to validate them against the database.
	 * If a fact differs from that in the database, or there is no fact present in the databse,
	 * this function will suggest the facts to the user. 
	 */
	function inferFacts($rows){
		$people = array();
		
		for($number = 0; $number < $_POST['numOfRows']; $number++)
		{
			$inferredFacts = array();
			$person = Person::getInstance($rows[$number]["personid"]);
			if (is_null($person)) continue;
			if(!empty($person))
			{
				$bdate = $person->getBirthYear();
				$occupation = $this->getOccupation($person->getGedcomRecord());
			
			$censusAge = $rows[$number]["Age"];
			$birthDate = 1870 - $censusAge;
				
			if($occupation != $rows[$number]["Trade"])
			{
				$inferredFact["Person"] = $person->getName();
				$inferredFact["PersonID"] = $person->getXref();
				$inferredFact["Reason"] = "Add <i>".$rows[$number]["Trade"]."</i> occupation fact.";
				$inferredFact["Fact"] = "1 OCCU ".$rows[$number]["Trade"];
				$inferredFact["factType"] = 'OCCU';
				$inferredFact["factPeople"] = "indi";
				$inferredFact["date"] = '';
				$inferredFacts[] = $inferredFact;
			}
			
			if(!empty($bdate))
			{
				 $bDiff = $birthDate - $bdate;
				 if($bDiff >1 || $bDiff < 0)
				 {
				 		
				 	if($birthDate != 1870)
				 {
				 	if(!empty($rows[$number]["PlaceOfBirth"]))
				 	{
				 		$inferredFact["Person"] = $person->getName();
						$inferredFact["PersonID"] = $person->getXref();
				 		$inferredFact["Reason"] = "A birth date difference was detected";
				 		$inferredFact["Fact"] = "1 BIRT \r\n2 DATE ABT".$birthDate."\r\n2 PLAC ".$rows[$number]["PlaceOfBirth"];
				 		$inferredFact["date"] = "ABT ".$birthDate;
				 		$inferredFact["factType"] = 'BIRT';	 	
				 		$inferredFact["factPeople"] = "indi";	
						$inferredFacts[] = $inferredFact;
				 		
				 	}
				 	else
				 	{
				 		$inferredFact["Person"] = $person->getName();
						$inferredFact["PersonID"] = $person->getXref();
				 		$inferredFact["Reason"] = "A birth date difference was detected";
				 		$inferredFact["Fact"] = "1 BIRT \r\n2 DATE ABT".$birthDate;
				 		$inferredFact["date"] = "ABT ".$birthDate;	
				 		$inferredFact["factType"] = 'BIRT'; 	
				 		$inferredFact["factPeople"] = "indi";
						$inferredFacts[] = $inferredFact;
				 	}
				 }
			}
			}
		
			$people[$person->getXref()] = $inferredFacts;
			}			
		}
		return $people;
		
		
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
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1870";
		$factrec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
		
		$people = $this->getPeople();
		$pids = array_keys($people);
		//-- store the fact associations in the database
		$sql = "INSERT INTO ".$TBLPREFIX."taskfacts VALUES('".get_next_id("taskfacts", "tf_id")."'," .
			"'".$DBCONN->escapeSimple($_REQUEST['taskid'])."'," .
			"'".$DBCONN->escapeSimple($factrec)."'," .
			"'".$DBCONN->escapeSimple(implode(";", $pids))."', 'Y', 'indi')";
		$res = dbquery($sql);
		
		$rows = array();
		$text = $_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", 1870 US Census";
		for($number = 0; $number < $_POST['numOfRows']; $number++)
		{
			if (!isset($_POST["Dwelling".$number])) $_POST["Dwelling".$number]="";
			if (!isset($_POST["Families".$number])) $_POST["Families".$number]="";
			if (!isset($_POST["NameOfPeople".$number])) $_POST["NameOfPeople".$number]="";
			if (!isset($_POST["Age".$number])) $_POST["Age".$number]="";
			if (!isset($_POST["Sex".$number])) $_POST["Sex".$number]="";
			if (!isset($_POST["Race".$number])) $_POST["Race".$number]="";			
			if (!isset($_POST["Trade".$number])) $_POST["Trade".$number]="";
			if (!isset($_POST["realestate".$number])) $_POST["realestate".$number]="";
			if (!isset($_POST["PofB".$number])) $_POST["PofB".$number]="";
			if (!isset($_POST["personid".$number])) $_POST["personid".$number]="";
			if (!isset($_POST["personalestate".$number])) $_POST["personalestate".$number]="";
			if (!isset($_POST["fatherborn".$number])) $_POST["fatherborn".$number]="";
			if (!isset($_POST["motherborn".$number])) $_POST["motherborn".$number]="";
			if (!isset($_POST["borninyear".$number])) $_POST["borninyear".$number]="";
			if (!isset($_POST["marriedinyear".$number])) $_POST["borninyear".$number]="";
			if (!isset($_POST["schoolinyear".$number])) $_POST["schoolinyear".$number]="";
			if (!isset($_POST["cannotwrite".$number])) $_POST["cannotwrite".$number]="";
			if (!isset($_POST["cannotread".$number])) $_POST["cannotread".$number]="";
			if (!isset($_POST["21andup".$number])) $_POST["21andup".$number]="";
			if (!isset($_POST["21andupbanned".$number])) $_POST["21andupbanned".$number]="";
			
			
			$rows[$number] = array(
			'Dwelling'=>$_POST["Dwelling".$number],
			'Families'=>$_POST["Families".$number],
			"NameOfPeople"=>$_POST["NameOfPeople".$number],
			"Race"=>$_POST["Race".$number],
			"Sex"=>$_POST["Sex".$number],
			"Age"=>$_POST["Age".$number],
			"Trade"=>$_POST["Trade".$number],
			"realestate"=>$_POST["realestate".$number],
			"Disablity"=>$_POST["Disablity".$number],
			"PofB"=>$_POST["PofB".$number],
			"personalestate"=>$_POST["personalestate".$number],		
			"fatherborn"=>$_POST["fatherborn".$number],	
			"motherborn"=>$_POST["motherborn".$number],	
			"borninyear"=>$_POST["borninyear".$number],		
			"marriedinyear"=>$_POST["marriedinyear".$number],	
			"schoolinyear"=>$_POST["schoolinyear".$number],		
			"cannotread"=>$_POST["cannotread".$number],		
			"cannotwrite"=>$_POST["cannotwrite".$number],	
			"21andup"=>$_POST["21andup".$number],	
			"21andupbanned"=>$_POST["21andupbanned".$number],			
			"personid"=>$_POST["personid".$number]
			);
			
			$text .= "\r\n";
			if (!empty($_POST["Dwelling".$number])) $text .= "Dwelling number: ".$_POST["Dwelling".$number];
			if (!empty($_POST["Families".$number])) $text .= " Family number: ".$_POST["Families".$number];
			if (!empty($_POST["NameOfPeople".$number])) $text .= " Name: ".$_POST["NameOfPeople".$number];
			if (!empty($_POST["Race".$number])) $text .= ", Color: ".$_POST["Race".$number];
			if (!empty($_POST["Sex".$number])) $text .= ", Sex: ".$_POST["Sex".$number];
			if (!empty($_POST["Age".$number])) $text .= ", Age: ".$_POST["Age".$number];
			if (!empty($_POST["Trade".$number])) $text .= ", Profession, Occupation or Trade: ".$_POST["Trade".$number];
			if (!empty($_POST["realestate".$number])) $text .= ", ".$_POST["realestate".$number];
			if (!empty($_POST["Disablity".$number])) $text .= ", ".$_POST["Disablity".$number];
			if (!empty($_POST["PofB".$number])) $text .= ", ".$_POST["PofB".$number];
			if (!empty($_POST["personalestate".$number])) $text .= ", Value of Personal Estate: ".$_POST["personalestate".$number];
			if (!empty($_POST["fatherborn".$number])) $text .= ", ".$_POST["fatherborn".$number];
			if (!empty($_POST["motherborn".$number])) $text .= ", ".$_POST["motherborn".$number];
			if (!empty($_POST["borninyear".$number])) $text .= ", ".$_POST["borninyear".$number];
			if (!empty($_POST["marriedinyear".$number])) $text .= ", ".$_POST["marriedinyear".$number];
			if (!empty($_POST["schoolinyear".$number])) $text .= ", ".$_POST["schoolinyear".$number];
			if (!empty($_POST["cannotread".$number])) $text .= ", ".$_POST["cannotread".$number];
			if (!empty($_POST["cannotwrite".$number])) $text .= ", ".$_POST["cannotwrite".$number];
			if (!empty($_POST["21andup".$number])) $text .= ", ".$_POST["21andup".$number];
			if (!empty($_POST["21andupbanned".$number])) $text .= ", ".$_POST["21andupbanned".$number];
						
		}

		$citation = array(
			"PAGE"=>"Page: ".$_POST['page'].", Call Number/URL: ".$_POST['CallNumberURL'], 
			"QUAY"=>'', 
    		"DATE"=>!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1870", 
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
