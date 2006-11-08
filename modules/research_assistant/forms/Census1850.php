<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1850 File
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
 * @author James Dickinson
 */
 //-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"Census1850.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

class Census1850 extends ra_form {

    function header($action, $tableAlign, $heading, $showchoose = false) {
    	global $pgv_lang;
    	$out = "";
    	if ($showchoose) {
	    	//Row Form
	    	$out = '<form action="module.php" method="post">';
	    	$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
	    			'<input type="hidden" name="action" value="printform" />' .
	    			'<input type="hidden" name="formname" value="Census1850" />' .
	    			'<input type="hidden" name="taskid" value="'.$_REQUEST['taskid'].'" />';
	    	if (!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = 1;
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
        global $params;
        parse_str(html_entity_decode($action["query"]), $params);
        
        // Setup for our form to go through the module system
        $out .=  '<form action="' . $action["path"] . '" method="post">';
		$out .= '<input type="hidden" name="numOfRows" value="'.$_REQUEST['numOfRows'].'" />';
        foreach ($params as $key => $value) {
            $out .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        $out .= '<table id="Census1850" class="list_table" align="' . $tableAlign . '">';
        $out .= '<tr>';
        $out .= '<th colspan="12" align="right"class="topbottombar"><h2>' . $heading . '</h2></th>';
        $out .= '</tr>';
        return $out;
    }
	
	/**
	 * override method from ra_form.php
	 */
    function simpleCitationForm($citation) {
    	global $pgv_lang, $factarray;
    	if (empty($_POST['data']))
    		$data = array();
    	if (empty($_REQUEST['row']))
    		$row = 1;
    	
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
        $out .= '<tr><td colspan="6"><table align="center" id="inputTable">';
        $out .= '<td class="descriptionbox" align="center" rowspan="2"rowspan="2">Dwelling -houses numbered<br>in the order of visitation</td>';
        $out .= '<td colspan="1" class="descriptionbox" align="center"rowspan="2">Families numbered in<br>the order of visitation</td>';
        $out .= '<td colspan="1" class="descriptionbox" align="center"rowspan="2">The Name of every Person whose usual place<br>of abode on the first day of June, 1850, was in this family</td>';
        $out .= '<td colspan="3" class="descriptionbox" align="center">Description</td>';
        $out .= '<td colspan="1" class="descriptionbox" align="center"rowspan="2">Profession, Occupation, or Trade of<br>each Male Person over 15 years of age</td>';
        $out .= '<td colspan="1" class="descriptionbox" align="center"rowspan="2">Value of Real Estate owned</td>';
        $out .= '<td colspan="1" class="descriptionbox" align="center" rowspan="2">Place of Birth Naming<br>the State, Territory, or Country</td>';
        $out .= '<td class="descriptionbox" align="center" rowspan="2">Married within the year</td>';
        
        $out .= '<td colspan="1" class="descriptionbox" align="center" rowspan="2">Attended School within the year</td>';
        $out .= '<td colspan="1" class="descriptionbox" align="center" rowspan="2">Persons over 20 years of<br>age who cannot read & write</td>';
        
        $out .= '<td colspan="1" class="descriptionbox" align="center" rowspan="2">Whether deaf, dumb, blind,<br>insane, idiotic, pauper, or convict</td>';
        
//		  Next row of description cells
        $out .=	'<tr><td class="descriptionbox" align="center">Age</td>';
        $out .= '<td class="descriptionbox" align="center">Sex</td>';
        $out .=	'<td class="descriptionbox" align="center">Color<br>(White, black, or mulatto)</td>';
        
//		  Country, City, Page, Head of Family input boxes
		if(!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = 1;
        for($i = 0; $i < $_REQUEST['numOfRows']; $i++){
        	$row = array();
        	if (isset($citation['ts_array']['rows'][$i])) $row = $citation['ts_array']['rows'][$i];
        	
        	$value = "";
        	if (isset($row['Dwelling'])) $value = $row['Dwelling'];
	        $out .= '<tr><td class="optionbox"><input name="Dwelling'.$i.'" type="text" size="21" value="'.htmlentities($value).'"></td>';
	//        Free white males input boxes
			$value = "";
        	if (isset($row['visitation'])) $value = $row['visitation'];
	        $out .= '<td class="optionbox"><input name="visitation'.$i.'" type="text" size="15" value="'.htmlentities($value).'"></td>';
	        $value = "";
        	if (isset($row['colum3'])) $value = $row['colum3'];
	        $out .= '<td class="optionbox"><input name="colum3'.$i.'" type="text" size="45" value="'.htmlentities($value).'"></td>';
	       	$value = "";
        	if (isset($row['Age'])) $value = $row['Age'];
	        $out .= '<td class="optionbox"><input name="Age'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = "";
        	if (isset($row['Sex'])) $value = $row['Sex'];
	        $out .= '<td class="optionbox"><input name="Sex'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = "";
        	if (isset($row['Color'])) $value = $row['Color'];
	        $out .= '<td class="optionbox"><input name="Color'.$i.'" type="text" size="17" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['Profession'])) $value = $row['Profession'];
	        $out .= '<td class="optionbox"><input name="Profession'.$i.'" type="text" size="29" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['ValueOfRealEstate'])) $value = $row['ValueOfRealEstate'];
	        $out .= '<td class="optionbox"><input name="ValueOfRealEstate'.$i.'" type="text" size="19" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['PlaceOfBirth'])) $value = $row['PlaceOfBirth'];
	        $out .= '<td class="optionbox"><input name="PlaceOfBirth'.$i.'" type="text" size="20" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['Married'])) $value = $row['Married'];
	        $out .= '<td class="optionbox"><input name="Married'.$i.'" type="text" size="17" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['AttendedSchool'])) $value = $row['AttendedSchool'];
	        $out .= '<td class="optionbox"><input name="AttendedSchool'.$i.'" type="text" size="22" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['CannotReadWrite'])) $value = $row['CannotReadWrite'];
	        $out .= '<td class="optionbox"><input name="CannotReadWrite'.$i.'" type="text" size="20" value="'.htmlentities($value).'"></td>';
			$value = "";
        	if (isset($row['DeafAndDumb'])) $value = $row['DeafAndDumb'];
	        $out .= '<td class="optionbox"><input name="DeafAndDumb'.$i.'" type="text" size="23" value="'.htmlentities($value).'"></td>';
        }
        $out .= '</table></td></tr>';
        return $out;
    }

    function footer() {
        return '</table></form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1850&action=func&func=step2&taskid=".$_REQUEST['taskid'], "center", "1850 United States Federal Census", true);
        $out .= $this->sourceCitationForm(5);
        //$out .= $this->content();
        $out .= $this->footer();
        return $out;
    }
    
    function step2() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $factarray, $pgv_lang;
		global $INDI_FACTS_ADD;
		
		$this->processSourceCitation();
		
		$out = $this->header("module.php?mod=research_assistant&form=Census1850&action=func&func=step3&taskid=" . $_REQUEST['taskid'], "center", "1850 United States Federal Census");
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
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1850";
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
		$text = $_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", 1850 US Census";
		for($number = 0; $number < $_REQUEST['numOfRows']; $number++)
		{
			$rows[$number] = array(
			'Dwelling'=>$_POST["Dwelling".$number],
			'FamiliesNumbered'=>$_POST["FamiliesNumbered".$number],
			'NameOfEveryPerson'=>$_POST["NameOfEveryPerson".$number],
			"Age"=>$_POST["Age".$number],
			"Sex"=>$_POST["Sex".$number],
			"Color"=>$_POST["Color".$number],
			"Profession"=>$_POST["Profession".$number],
			"ValueOfRealEstate"=>$_POST["ValueOfRealEstate".$number],
			"PlaceOfBirth"=>$_POST["PlaceOfBirth".$number],
			"Married"=>$_POST["Married".$number],
			"AttendedSchool"=>$_POST["AttendedSchool".$number],
			"CannotReadWrite"=>$_POST["CannotReadWrite".$number],
			"DeafAndDumb"=>$_POST["DeafAndDumb".$number]
			);
			$text .=$number==0?"" :"\r\n";
			$text .= "\r\nDwelling: ".$_POST["Dwelling".$number];
			$text .= "\r\nFamilies numbered in the order of visitation: ".$_POST["FamiliesNumbered".$number];
			$text .= "\r\nThe Name of every Person whose usual place of abode on the first day of June, 1850, was in this family: ".$_POST["NameOfEveryPerson".$number];
			$text .= "\r\nAge: ".$_POST["Age".$number];
			$text .= "\r\nSex: ".$_POST["Sex".$number];
			$text .= "\r\nColor: ".$_POST["Color".$number];
			$text .= "\r\nProfession: ".$_POST["Profession".$number];
			$text .= "\r\nValue Of Real Estate: ".$_POST["ValueOfRealEstate".$number];
			$text .= "\r\nPlace Of Birth: ".$_POST["PlaceOfBirth".$number];
			$text .= "\r\nMarried: ".$_POST["Married".$number];
			$text .= "\r\nAttended School: ".$_POST["AttendedSchool".$number];
			$text .= "\r\nCan not Read or Write: ".$_POST["CannotReadWrite".$number];
			$text .= "\r\nDeaf And Dumb: ".$_POST["DeafAndDumb".$number];
		}

		$citation = array(
			"PAGE"=>"Page: ".$_POST['page'].", Call Number/URL: ".$_POST['CallNumberURL'], 
			"QUAY"=>'', 
    		"DATE"=>!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1850", 
			"TEXT"=>$text, 
			"OBJE"=>'',
			"array"=>array(
			'city'=>$_POST['city'],
			'county'=>$_POST['county'],
			'state'=>$_POST['state'],
			'rows'=>$rows));
					
		return $citation;
    }
    
}
?>
