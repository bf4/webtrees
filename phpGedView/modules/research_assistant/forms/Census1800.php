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
 * @author Brandon Gagnon
 */
 //-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"Census1800.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

class Census1800 extends ra_form {

    function header($action, $tableAlign, $heading) {
    	global $pgv_lang;
    	//Row Form
    	$out = '<form action="module.php" method="post">';
    	$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
    			'<input type="hidden" name="action" value="printform" />' .
    			'<input type="hidden" name="formname" value="Census1800" />' .
    			'<input type="hidden" name="taskid" value="'.$_REQUEST['taskid'].'" />';
    	$out .= '<table align="center"><tr><td class="descriptionbox">'.$pgv_lang["rows"].'</td><td class="optionbox"><select name="numOfRows">';
    	for($i = 1; $i <= 20; $i++){
    		$out .= '<option value="'.$i.'">'.$i;
    	}
    	$out .=	'</select></td></tr><tr><td colspan="2" class="topbottombar"><input type="submit" value="'.$pgv_lang["okay"].'"/></td></tr></table>';
    	$out .= '</form>';
    	
		// Split action and use it for hidden inputs
        $action = parse_url($action);
        parse_str(html_entity_decode($action["query"]), $params);
        
        // Setup for our form to go through the module system
        $out .=  '<form action="' . $action["path"] . '" method="post">';

        foreach ($params as $key => $value) {
            $out .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        $out .= '<table id="Census1800" class="list_table" align="' . $tableAlign . '">';
        $out .= '<tr>';
        $out .= '<th colspan="12" align="right"class="topbottombar"><h2>' . $heading . '</h2></th>';
        $out .= '</tr>';
        return $out;
    }
	
	/**
	 * override method from ra_form.php
	 */
    function simpleCitationForm($citation) {
    	global $pgv_lang;
    	if (empty($_POST['data']))
    		$data = array();
    	if (empty($_GET['row']))
    		$row = 1;
    		
//        Start of Table
        $out = '<tr><td class="descriptionbox">'.$pgv_lang["state"].'</td><td class="optionbox"><input name="state" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["call/url"].'</td><td class="optionbox"><input name="CallNumberURL" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["enumDate"].'</td><td class="optionbox"><input name="EnumerationDate" type="text" size="27"></td></tr>';
        $out .= '<tr><td class="descriptionbox">'.$pgv_lang["county"].'</td><td class="optionbox"><input name="county" type="text" size="27"></td>';
        $out .= '<td class="descriptionbox">'.$pgv_lang["city"].'</td><td class="optionbox"><input name="city" type="text" size="10"></td>';
        $out .=	'<td class="descriptionbox">'.$pgv_lang["page"].'</td><td class="optionbox"><input name="page" type="text" size="1"></td></tr>';
//        Next Table
        $out .= '<tr><td colspan="6"><table align="center" id="inputTable">';
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
        $out .= '</table></td></tr>';
        return $out;
    }

    function footer() {
        return '</table></form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1800&action=func&func=step2&taskid=$_REQUEST[taskid]", "center", "1800 United States Federal Census");
        $out .= $this->sourceCitationForm(5);
        //$out .= $this->content();
        $out .= $this->footer();
        return $out;
    }
    
    function step2() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $factarray, $pgv_lang;
		global $INDI_FACTS_ADD;
		
		$this->processSourceCitation();
		
		$out = $this->header("module.php?mod=research_assistant&form=Census1800&action=func&func=step3&taskid=" . $_REQUEST['taskid'], "center", "1800 United States Federal Census");
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
		// Set our output to nothing, this supresses a warning that we would otherwise get.
		$out = "";
		$factrec = "1 CENS";
		$factrec .= "\r\n2 _RAID ".$_REQUEST['taskid'];
		$factrec .= "\r\n2 DATE ";
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1800";
		$factrec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
		
		//-- store the fact associations in the database
		$sql = "INSERT INTO ".$TBLPREFIX."taskfacts VALUES('".get_next_id("taskfacts", "tf_id")."'," .
			"'".$DBCONN->escapeSimple($_REQUEST['taskid'])."'," .
			"'".$DBCONN->escapeSimple($factrec)."'," .
			"'')";
		$res = dbquery($sql);
		
		$text = "City ".$_POST['city'].", County ".$_POST['county'].", State ".$_POST['state'].", 1800 US Census";
		for($number = 0; $number < $_POST['Rows']; $number++)
		{
			$text .=$number==0?"" :"\r\n";
			$text .= "\r\nHead of Family ".$_POST["headName".$number];
			$text .= "\r\nFree White Males under 10 ".$_POST["underTenM".$number];
			$text .= "\r\nFree White Males Ten thru fifteen ".$_POST["tenThruFifteenM".$number];
			$text .= "\r\nFree White Males Sixteen thru Twentyfive ".$_POST["sixteenThruTwentyfiveM".$number];
			$text .= "\r\nFree White Males Twentysix thru Fortyfour ".$_POST["twentysixThruFortyfourM".$number];
			$text .= "\r\nFree White Males Fortyfive and Over ".$_POST["fortyfiveAndOverM".$number];
			$text .= "\r\nFree White Females under 10 ".$_POST["underTenF".$number];
			$text .= "\r\nFree White Females Ten thru fifteen ".$_POST["tenThruFifteenF".$number];
			$text .= "\r\nFree White Females Sixteen thru Twentyfive ".$_POST["sixteenThruTwentyfiveF".$number];
			$text .= "\r\nFree White Females Twentysix thru Fortyfour ".$_POST["twentysixThruFortyfourF".$number];
			$text .= "\r\nFree White Females Fortyfive and Over ".$_POST["fortyfiveAndOverF".$number];
			$text .= "\r\nAll other Free Persons ".$_POST["otherPersons".$number];
			$text .= "\r\nSlaves ".$_POST["slaves".$number];
		}

		$citation = array(
			"PAGE"=>"Page ".$_POST['page'].", Call Number/URL ".$_POST['CallNumberURL'], 
			"QUAY"=>'', 
    		"DATE"=>!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1800", 
			"TEXT"=>$text, 
			"OBJE"=>'');
		
		return $citation;
    }
    
}
?>
