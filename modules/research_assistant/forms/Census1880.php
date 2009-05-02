<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1880 File
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 * @author Brandon Gagnon
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once "ra_form.php";
require_once "includes/functions/functions_edit.php";

class Census1880 extends ra_form {

    function header($action, $tableAlign, $heading, $showchoose = false) {
		global $pgv_lang;
		$out = "";
		if ($showchoose) {
			//Row Form
			$out = '<form action="module.php" method="post">';
			$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
					'<input type="hidden" name="action" value="printform" />' .
					'<input type="hidden" name="formname" value="Census1880" />' .
					'<input type="hidden" name="taskid" value="'.$_REQUEST['taskid'].'" />';
			if (!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = count($this->getPeople());
			if ($_REQUEST['numOfRows']<1) $_REQUEST['numOfRows']=1;
			$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
			for($i = 1; $i <= 20; $i++){
				$out .= '<option value="'.$i.'"';
				if ($_REQUEST['numOfRows']==$i) $out .= " selected=\"selected\"";
				$out .= '>'.$i;
			}
			$out .= '</select></td></tr><tr><td colspan="2" class="topbottombar"><input type="submit" value="'.$pgv_lang["okay"].'"/></td></tr></table>';
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
		$out .= '<input type="hidden" name="numOfRows" value="'.$_REQUEST['numOfRows'].'" />';
        foreach ($params as $key => $value) {
            $out .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        $out .= '<table id="Census1880" class="list_table" align="' . $tableAlign . '">';
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
					$out .= "<span id=\"censusImgSpan\">".$picture->getFullName().'</span><br/><img id="censusImage" src="'.$picture->getThumbnail().'" />';
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
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["state"].'</td><td class="optionbox"><input name="state" type="text" size="27"  value="'.htmlentities($state).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["call/url"].'</td><td class="optionbox"><input name="CallNumberURL" type="text" size="27" value="'.htmlentities($callno).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["enumDate"].'</td><td class="optionbox"><input name="EnumerationDate" type="text" size="27" value="'.htmlentities($date).'"></td></tr>';
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["county"].'</td><td class="optionbox"><input name="county" type="text" size="27" value="'.htmlentities($county).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["city"].'</td><td class="optionbox"><input name="city" type="text" size="27" value="'.htmlentities($city).'"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["page"].'</td><td class="optionbox"><input name="page" type="text" size="5" value="'.htmlentities($page).'"></td></tr>';
//        Next Table
        $out .= '<tr><td colspan="6">';

        $out .= '<table align="left" dir="ltr">
 <tr>
  <td class="descriptionbox" align="left">Dwelling number</td>';
  for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
		if (isset($citation['ts_array']['rows'][$i]['House'])) $value = $citation['ts_array']['rows'][$i]['House'];
		$out.='<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name="House'.$i.'" value="'.htmlentities($value).'" /></td>';
  }
  $out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Family number</td>';
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
  <td class="descriptionbox" align="left">Color-White, W: Black, B:</td>';
			for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
				$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Race'])) $value = $citation['ts_array']['rows'][$i]['Race'];
					$out .= '<td class="optionbox" align="left"><select name="Race'.$i.'">
		<option value="W"'.($value=='W'?' selected="selected"':'').'>White</option>
		<option value="B"'.($value=='B'?' selected="selected"':'').'>Black</option>
		<option value="MU"'.($value=='MU'?' selected="selected"':'').'>MU</option>
		<option value="C"'.($value=='C'?' selected="selected"':'').'>C</option>
	</select>
  </td>';
  }
  $out .= '</tr>
 <tr>
  <td class="descriptionbox" align="left">Gender: Male: M   Female: F.</td>';
			for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
				$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Gender'])) $value = $citation['ts_array']['rows'][$i]['Gender'];
					$out .= '<td class="optionbox" align="left">
					Male:<INPUT TYPE="RADIO" value="M" name="Gender'.$i.'"'.($value=='M'?' checked="checked"':'').' />
				Female:<INPUT TYPE="RADIO" value="F" name="Gender'.$i.'"'.($value=='F'?' checked="checked"':'').' /></td>';
	}
	$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Age at last birthday</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Age'])) $value = $citation['ts_array']['rows'][$i]['Age'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "Age'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">If born within the Census year give month.</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Month'])) $value = $citation['ts_array']['rows'][$i]['Month'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "Month'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Relationship to the head of the family</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Relationship'])) $value = $citation['ts_array']['rows'][$i]['Relationship'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "Relationship'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Single</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Single'])) $value = $citation['ts_array']['rows'][$i]['Single'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Single'.$i.'" value="Single"'.($value=='Single'?' checked="checked"':'').' /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Married
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Married'])) $value = $citation['ts_array']['rows'][$i]['Married'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Married'.$i.'" value="Married"'.($value=='Married'?' checked="checked"':'').' /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Widowed, Divorced
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['WidowedDivorced'])) $value = $citation['ts_array']['rows'][$i]['WidowedDivorced'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="WidowedDivorced'.$i.'" value="Widowed/Divorced"'.($value=='Widowed/Divorced'?' checked="checked"':'').' /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Profession, Occupation, or Trade
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Trade'])) $value = $citation['ts_array']['rows'][$i]['Trade'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "Trade'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Number of months employed
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['TimeEmployed'])) $value = $citation['ts_array']['rows'][$i]['TimeEmployed'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "TimeEmployed'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Sick or disabled
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Disablity'])) $value = $citation['ts_array']['rows'][$i]['Disablity'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "Disablity'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Blind
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Blind'])) $value = $citation['ts_array']['rows'][$i]['Blind'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Blind'.$i.'" value="Blind"'.($value=='Blind'?' checked="checked"':'').'/></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Deaf and Dumb
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Deaf'])) $value = $citation['ts_array']['rows'][$i]['Deaf'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Deaf'.$i.'" value="Deaf"'.($value=='Deaf'?' checked="checked"':'').' /></td>';
		}
			$out .='</tr>
 <tr>
 <td class="descriptionbox" align="left">Idiotic
</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Idiotic'])) $value = $citation['ts_array']['rows'][$i]['Idiotic'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Idiotic'.$i.'" value="Idiotic"'.($value=='Idiotic'?' checked="checked"':'').' /></td>';
		}
			$out .='</tr>
 <tr>
 <td class="descriptionbox" align="left">Insane
</td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Insane'])) $value = $citation['ts_array']['rows'][$i]['Insane'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Insane'.$i.'" value="Insane"'.($value=='Insane'?' checked="checked"':'').' /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Maimed, crippled, bedridden, or otherwise disabled.
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Maimed'])) $value = $citation['ts_array']['rows'][$i]['Maimed'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Maimed'.$i.'" value="Maimed, crippled, bedridden, or otherwise disabled"'.($value=='Maimed, crippled, bedridden, or otherwise disabled'?' checked="checked"':'').'/></td>';
		}
			$out .='</tr>
 <tr>
 <td class="descriptionbox" align="left">Attended school within the census year
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['School'])) $value = $citation['ts_array']['rows'][$i]['School'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="School'.$i.'" value="Attended school"'.($value=='Attended school'?' checked="checked"':'').'/></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Can not read
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Read'])) $value = $citation['ts_array']['rows'][$i]['Read'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Read'.$i.'" value="Cannot read"'.($value=='Cannot read'?' checked="checked"':'').'/></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Can not write
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['Write'])) $value = $citation['ts_array']['rows'][$i]['Write'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="CHECKBOX" name="Write'.$i.'" value="Cannot write"'.($value=='Cannot write'?' checked="checked"':'').'/></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Place of birth of this person
   </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['PlaceOfBirth'])) $value = $citation['ts_array']['rows'][$i]['PlaceOfBirth'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name="PlaceOfBirth'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Place of birth of the father
  </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['FathersPlaceOfBirth'])) $value = $citation['ts_array']['rows'][$i]['FathersPlaceOfBirth'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name="FathersPlaceOfBirth'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>
 <tr>
  <td class="descriptionbox" align="left">Place of birth of the mother
 </td>';
	for($i=0; $i<$_REQUEST['numOfRows']; $i++) {
		$value = "";
				if (isset($citation['ts_array']['rows'][$i]['MothersPlaceOfBirth'])) $value = $citation['ts_array']['rows'][$i]['MothersPlaceOfBirth'];
			$out .= '<td class="optionbox" align="left"><INPUT TYPE="TEXT" SIZE="22" name = "MothersPlaceOfBirth'.$i.'" value="'.htmlentities($value).'" /></td>';
		}
			$out .='</tr>';
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
				if (!is_null($person)) $out .= '<a id="link_'.$pid.'" href="'.$person->getLinkUrl().'">'.$person->getFullName().'</a> <a id="rem_'.$pid.'" href="#" onclick="clearname(\'personid\', \'link_'.$pid.'\', \''.$pid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
	                   $out .= '</div>
	                   <input type="hidden" id="personid'.$i.'" name="personid'.$i.'" size="3" value="'.$pid.'" />';
	                   if(isset($citation['ts_array']['rows'][$i]['NameOfPeople'])) $searchName = $citation['ts_array']['rows'][$i]['NameOfPeople'];
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
        $out = $this->header("module.php?mod=research_assistant&form=Census1880&action=func&func=step2&taskid=".$_REQUEST['taskid'], "center", "1880 United States Federal Census", true);
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
			$age = 1880 - $_POST["Age".$i];
			$indiFact .= "1 BIRT\r\n";
			$indiFact .= "2 DATE ABT ".$age;
		}

		if(!empty($_POST["PlaceOfBirth".$i]))
		{
			$indiFact .= "2 PLAC ".$_POST["PlaceOfBirth"];
		}

		if(!empty($_POST["Gender".$i]))
		{
			$indiFact .= "1 SEX ".$_POST["Gender".$i];
		}

		return $indiFact;

    }

  function step2() {
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
		$out = $this->header("module.php?mod=research_assistant&form=Census1880&action=func&func=step3&taskid=" . $_REQUEST['taskid'], "center", "1880 United States Federal Census");
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
						$out .="<td class=\"optionbox\">".'<input type="Checkbox" id="'.$value['PersonID'].$value['factType'].'" onclick="add_ra_fact_inferred(this,\''.preg_replace("/\r?\n/", "\\r\\n",$value["Fact"]).'\',\''.$value['PersonID'].'\',\''.$value['factType'].'\',\''.htmlentities($value["Person"]).'\',\''.$value["factPeople"].'\')"></td>'."\n";
						$out .="</tr>";
					}

				}
		}



		$out .= '<tr><td class="descriptionbox" align="center" colspan="4"><input type="submit" value='.$pgv_lang["complete"].'></td></tr>';
		return $out;
	}
	}

	function step3() {
		global $pgv_lang;

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
				$bdate=$person->getEstimatedBirthDate();
				$bdate=$bdate->gregorianYear();
				$occupation = $this->getOccupation($person->getGedcomRecord());

			$censusAge = $rows[$number]["Age"];
			$birthDate = 1880 - $censusAge;

			if($occupation != $rows[$number]["Trade"])
			{
				$inferredFact["Person"] = $person->getFullName();
				$inferredFact["PersonID"] = $person->getXref();
				$inferredFact["Reason"] = "Add <i>".$rows[$number]["Trade"]."</i> occupation fact.";
				$inferredFact["Fact"] = "1 OCCU ".$rows[$number]["Trade"]."\r\n2 DATE ABT 1880";
				$inferredFact["factType"] = 'OCCU';
				$inferredFact["factPeople"] = "indi";
				$inferredFact["date"] = '';
				$inferredFacts[] = $inferredFact;
			}
			if($rows[$number]["Single"] == "Widowed")
			{

				$spouseFams = $person->getSpouseFamilies();
				foreach($spouseFams as $sFamKey => $sFamValue)
				{
					$spouse = $sFamValue->getSpouse($person);
					$deathDate = $spouse->getEstimatedDeathDate();
					$deathYear = $deathDate->gregorianYear();
					if ($deathYear) $diff = $deathYear - 1880;
						if($diff)
						{
							if($diff > 1 || $diff < 0)
							{
								$tempArray = array();
								$inferredFact["Person"] = $spouse->getFullName();
								$inferredFact["PersonID"] = $spouse->getXref();
								$inferredFact["Reason"] = "A death Date can be inferred!";
								$inferredFact["Fact"] = "1 DEAT \r\n2 DATE BEF 1880";
								$inferredFact["factType"] = 'DEAT';
								$inferredFact["date"] = 'BEF 1880';
								$inferredFact["factPeople"] = "indi";
								$inferredFacts[] = $inferredFact;
							}
						}

				}

			}

			if($rows[$number]["Single"] == "Married")
			{

				$spouseFams = $person->getSpouseFamilies();
				foreach($spouseFams as $sFamKey => $sFamValue)
				{
					$marriage = $sFamValue->getMarriageRecord();
					if(!$marriage)
					{
						if(!is_null($sFamValue))
						{
							$tempArray = array();
							$inferredFact["Person"] = $sFamValue->getFullName();
							$inferredFact["PersonID"] = $sFamValue->getXref();
							$inferredFact["Reason"] = "A Marriage Date can be inferred!";
							$inferredFact["Fact"] = "1 MARR \r\n2 DATE BEF 1880";
							$inferredFact["factType"] = 'MARR';
							$inferredFact["date"] = 'BEF 1880';
							$inferredFact["factPeople"] = "fam";
							$tempArray[] = $inferredFact;
							$people[$sFamValue->getXref()] = $tempArray;
						}
					}
				}

			}

			if(!empty($bdate))
			{
				 $bDiff = $birthDate - $bdate;
				 if($bDiff >1 || $bDiff < 0)
				 {

						if($birthDate != 1880)
				 {
						if(!empty($rows[$number]["PlaceOfBirth"]))
						{
							$inferredFact["Person"] = $person->getFullName();
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
							$inferredFact["Person"] = $person->getFullName();
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
		global $TBLPREFIX;
		//-- delete any old census records
		PGV_DB::prepare("DELETE FROM {$TBLPREFIX}taskfacts WHERE tf_t_id=? AND tf_factrec ".PGV_DB_LIKE." ?")->execute(array($_REQUEST['taskid'], '1 CENS%'));

		// Set our output to nothing, this supresses a warning that we would otherwise get.
		$out = "";
		$factrec = "1 CENS";
		$factrec .= "\r\n2 DATE ";
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1880";
		$factrec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";

		$people = $this->getPeople();
		$pids = array_keys($people);
		//-- store the fact associations in the database
		PGV_DB::prepare("INSERT INTO {$TBLPREFIX}taskfacts (tf_id, tf_t_id, tf_factrec, tf_people, tf_multiple, tf_type) VALUES (?, ?, ?, ?, ?, ?)")->execute(array(get_next_id("taskfacts", "tf_id"), $_REQUEST['taskid'], $factrec, implode(";", $pids), 'Y', 'indi'));

		$rows = array();
		$text = $_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", 1880 US Census";
		for($number = 0; $number < $_POST['numOfRows']; $number++)
		{
			if (!isset($_POST["House".$number])) $_POST["House".$number]="";
			if (!isset($_POST["Families".$number])) $_POST["Families".$number]="";
			if (!isset($_POST["NameOfPeople".$number])) $_POST["NameOfPeople".$number]="";
			if (!isset($_POST["Race".$number])) $_POST["Race".$number]="";
			if (!isset($_POST["Gender".$number])) $_POST["Gender".$number]="";
			if (!isset($_POST["Age".$number])) $_POST["Age".$number]="";
			if (!isset($_POST["Month".$number])) $_POST["Month".$number]="";
			if (!isset($_POST["Relationship".$number])) $_POST["Relationship".$number]="";
			if (!isset($_POST["Single".$number])) $_POST["Single".$number]="";
			if (!isset($_POST["Married".$number])) $_POST["Married".$number]="";
			if (!isset($_POST["WidowedDivorced".$number])) $_POST["WidowedDivorced".$number]="";
			if (!isset($_POST["Trade".$number])) $_POST["Trade".$number]="";
			if (!isset($_POST["TimeEmployed".$number])) $_POST["TimeEmployed".$number]="";
			if (!isset($_POST["Disablity".$number])) $_POST["Disablity".$number]="";
			if (!isset($_POST["Blind".$number])) $_POST["Blind".$number]="";
			if (!isset($_POST["Deaf".$number])) $_POST["Deaf".$number]="";
			if (!isset($_POST["Idiotic".$number])) $_POST["Idiotic".$number]="";
			if (!isset($_POST["Insane".$number])) $_POST["Insane".$number]="";
			if (!isset($_POST["Maimed".$number])) $_POST["Maimed".$number]="";
			if (!isset($_POST["School".$number])) $_POST["School".$number]="";
			if (!isset($_POST["Read".$number])) $_POST["Read".$number]="";
			if (!isset($_POST["Write".$number])) $_POST["Write".$number]="";
			if (!isset($_POST["PlaceOfBirth".$number])) $_POST["PlaceOfBirth".$number]="";
			if (!isset($_POST["FathersPlaceOfBirth".$number])) $_POST["FathersPlaceOfBirth".$number]="";
			if (!isset($_POST["MothersPlaceOfBirth".$number])) $_POST["MothersPlaceOfBirth".$number]="";
			if (!isset($_POST["personid".$number])) $_POST["personid".$number]="";

			$rows[$number] = array(
			'House'=>$_POST["House".$number],
			'Families'=>$_POST["Families".$number],
			"NameOfPeople"=>$_POST["NameOfPeople".$number],
			"Race"=>$_POST["Race".$number],
			"Gender"=>$_POST["Gender".$number],
			"Age"=>$_POST["Age".$number],
			"Month"=>$_POST["Month".$number],
			"Relationship"=>$_POST["Relationship".$number],
			"Single"=>$_POST["Single".$number],
			"Married"=>$_POST["Married".$number],
			"WidowedDivorced"=>$_POST["WidowedDivorced".$number],
			"Trade"=>$_POST["Trade".$number],
			"TimeEmployed"=>$_POST["TimeEmployed".$number],
			"Disablity"=>$_POST["Disablity".$number],
			"Blind"=>$_POST["Blind".$number],
			"Deaf"=>$_POST["Deaf".$number],
			"Idiotic"=>$_POST["Idiotic".$number],
			"Insane"=>$_POST["Insane".$number],
			"Maimed"=>$_POST["Maimed".$number],
			"School"=>$_POST["School".$number],
			"Read"=>$_POST["Read".$number],
			"Write"=>$_POST["Write".$number],
			"PlaceOfBirth"=>$_POST["PlaceOfBirth".$number],
			"FathersPlaceOfBirth"=>$_POST["FathersPlaceOfBirth".$number],
			"MothersPlaceOfBirth"=>$_POST["MothersPlaceOfBirth".$number],
			"personid"=>$_POST["personid".$number]
			);

			$text .= "\r\n";
			if (!empty($_POST["House".$number])) $text .= "Dwelling number: ".$_POST["House".$number];
			if (!empty($_POST["Families".$number])) $text .= " Family number: ".$_POST["Families".$number];
			if (!empty($_POST["NameOfPeople".$number])) $text .= " Name: ".$_POST["NameOfPeople".$number];
			if (!empty($_POST["Race".$number])) $text .= ", Color: ".$_POST["Race".$number];
			if (!empty($_POST["Gender".$number])) $text .= ", Gender: ".$_POST["Gender".$number];
			if (!empty($_POST["Age".$number])) $text .= ", Age: ".$_POST["Age".$number];
			if (!empty($_POST["Month".$number])) $text .= ", Month: ".$_POST["Month".$number];
			if (!empty($_POST["Relationship".$number])) $text .= ", Relationship: ".$_POST["Relationship".$number];
			if (!empty($_POST["Single".$number])) $text .= ", ".$_POST["Single".$number];
			if (!empty($_POST["Married".$number])) $text .= ", ".$_POST["Married".$number];
			if (!empty($_POST["WidowedDivorced".$number])) $text .= ", ".$_POST["WidowedDivorced".$number];
			if (!empty($_POST["Trade".$number])) $text .= ", Profession, Occupation or Trade: ".$_POST["Trade".$number];
			if (!empty($_POST["TimeEmployed".$number])) $text .= ", Number of months employed: ".$_POST["TimeEmployed".$number];
			if (!empty($_POST["Disablity".$number])) $text .= ", ".$_POST["Disablity".$number];
			if (!empty($_POST["Blind".$number])) $text .= ", ".$_POST["Blind".$number];
			if (!empty($_POST["Deaf".$number])) $text .= ", ".$_POST["Deaf".$number];
			if (!empty($_POST["Idiotic".$number])) $text .= ", ".$_POST["Idiotic".$number];
			if (!empty($_POST["Insane".$number])) $text .= ", ".$_POST["Insane".$number];
			if (!empty($_POST["Maimed".$number])) $text .= ", ".$_POST["Maimed".$number];
			if (!empty($_POST["School".$number])) $text .= ", ".$_POST["School".$number];
			if (!empty($_POST["Read".$number])) $text .= ", ".$_POST["Read".$number];
			if (!empty($_POST["Write".$number])) $text .= ", ".$_POST["Write".$number];
			if (!empty($_POST["PlaceOfBirth".$number])) $text .= ", Place of birth: ".$_POST["PlaceOfBirth".$number];
			if (!empty($_POST["FathersPlaceOfBirth".$number])) $text .= ", Father's Place of birth: ".$_POST["FathersPlaceOfBirth".$number];
			if (!empty($_POST["MothersPlaceOfBirth".$number])) $text .= ", Mother's Place of birth: ".$_POST["MothersPlaceOfBirth".$number];

		}

		$citation = array(
			"PAGE"=>"Page: ".$_POST['page'].", Call Number/URL: ".$_POST['CallNumberURL'],
			"QUAY"=>'',
			"DATE"=>!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1880",
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
