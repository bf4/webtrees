<?php
/**
 * phpGedView Research Assistant Tool - United States Census 1830 File
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
if (strstr($_SERVER["SCRIPT_NAME"],"Census1830.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
require_once "ra_form.php";
require_once "includes/functions_edit.php";

class Census1830 extends ra_form {

    function header($action, $tableAlign, $heading, $showchoose = false) {
    	global $pgv_lang;
    	$out = "";
    	if ($showchoose) {
	    	//Row Form
	    	$out = '<form action="module.php" method="post">';
	    	$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
	    			'<input type="hidden" name="action" value="printform" />' .
	    			'<input type="hidden" name="formname" value="Census1830" />' .
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
        $out .= '<table id="Census1830" class="list_table" align="' . $tableAlign . '">';
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
        $out .= '<td class="descriptionbox" align="center" rowspan="2">Names of heads of families</td>';
        $out .= '<td colspan="13" class="descriptionbox" align="center">Free White Males</td>';
        $out .= '<td colspan="13" class="descriptionbox" align="center">Free White Females</td>';
        $out .= '<td colspan="6" class="descriptionbox" align="center">Male Slaves</td>';
        $out .= '<td colspan="6" class="descriptionbox" align="center">Female Slaves</td>';
        $out .= '<td colspan="6" class="descriptionbox" align="center">Free Male Colored Persons</td>';
        $out .= '<td colspan="6" class="descriptionbox" align="center">Free Female Colored Persons</td>';
        $out .= '<td class="descriptionbox" align="center" rowspan="2">Totle</td>';
        $out .= '<td colspan="5" class="descriptionbox" align="center">White Persons included in foregoing.</td>';
        $out .= '<td colspan="4" class="descriptionbox" align="center">Slaves and Colored Persons, included in foregoing.</td>';
        
//		  Next row of description cells
        $out .=	'<tr><td class="descriptionbox">under 5</td><td class="descriptionbox">5 to 10</td>';
        $out .= '<td class="descriptionbox">10 to 15</td><td class="descriptionbox">15 to 20</td><td class="descriptionbox">20 to 30</td>';
        $out .=	'<td class="descriptionbox">30 to 40</td><td class="descriptionbox">40 to 50</td>';
        $out .= '<td class="descriptionbox">50 to 60</td><td class="descriptionbox">60 to 70</td><td class="descriptionbox">70 to 80</td>';
        $out .='<td class="descriptionbox">80 to 90</td><td class="descriptionbox">90 to 100</td>';
        $out .= '<td class="descriptionbox">100, &c.</td><td class="descriptionbox">under 5</td><td class="descriptionbox">5 to 10</td>';
        $out .= '<td class="descriptionbox">10 to 15</td><td class="descriptionbox">15 to 20</td><td class="descriptionbox">20 to 30</td>';
        $out .=	'<td class="descriptionbox">30 to 40</td><td class="descriptionbox">40 to 50</td>';
        $out .= '<td class="descriptionbox">50 to 60</td><td class="descriptionbox">60 to 70</td><td class="descriptionbox">70 to 80</td>';
        $out .='<td class="descriptionbox">80 to 90</td><td class="descriptionbox">90 to 100</td>';
        $out .= '<td class="descriptionbox">100, &c.</td>';
        $out .= '<td class="descriptionbox">Under 10</td><td class="descriptionbox">10 to 24</td><td class="descriptionbox">24 to 36</td>';
        $out .=	'<td class="descriptionbox">36 to 55</td><td class="descriptionbox">55 to 100</td>';
        $out .= '<td class="descriptionbox">100 & C.</td>';
        $out .= '<td class="descriptionbox">Under 10</td><td class="descriptionbox">10 to 24</td><td class="descriptionbox">24 to 36</td>';
        $out .=	'<td class="descriptionbox">36 to 55</td><td class="descriptionbox">55 to 100</td>';
        $out .= '<td class="descriptionbox">100 & C.</td>';
        $out .= '<td class="descriptionbox">Under 10</td><td class="descriptionbox">10 to 24</td><td class="descriptionbox">24 to 36</td>';
        $out .=	'<td class="descriptionbox">36 to 55</td><td class="descriptionbox">55 to 100</td>';
        $out .= '<td class="descriptionbox">100 & C.</td>';
        $out .= '<td class="descriptionbox">Under 10</td><td class="descriptionbox">10 to 24</td><td class="descriptionbox">24 to 36</td>';
        $out .=	'<td class="descriptionbox">36 to 55</td><td class="descriptionbox">55 to 100</td>';
        $out .= '<td class="descriptionbox">100 & C.</td>';
        $out .= '<td class="descriptionbox">Who are Deaf & Dumb,<br> under 14 of age</td>';
        $out .= '<td class="descriptionbox">Who are Deaf & Dumb,<br> of the age of 14 and under 25</td>';
        $out .= '<td class="descriptionbox">Who are Deaf 25 and up</td>';
        $out .= '<td class="descriptionbox">Who are Blind</td>';
        $out .= '<td class="descriptionbox">ALIENS - Foreigners<br>not naturalized</td>';
        $out .= '<td class="descriptionbox">Who are Deaf & Dumb,<br> under 14 of age</td>';
        $out .= '<td class="descriptionbox">Who are Deaf & Dumb,<br> of the age of 14 and under 25</td>';
        $out .= '<td class="descriptionbox">Who are Deaf 25 and up</td>';
        $out .= '<td class="descriptionbox">Who are Blind</td></tr>';

//		  Country, City, Page, Head of Family input boxes
		if(!isset($_REQUEST['numOfRows'])) $_REQUEST['numOfRows'] = 1;
        for($i = 0; $i < $_REQUEST['numOfRows']; $i++){
        	$row = $citation['ts_array']['rows'][$i];
        	$value = $row['headName'];
	        $out .= '<tr><td class="optionbox"><input name="headName'.$i.'" type="text" size="19" value="'.htmlentities($value).'"></td>';
	//        Free white males input boxes
			$value = $row['under5M'];
	        $out .= '<td class="optionbox"><input name="under5M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['5Thru10M'];
	        $out .= '<td class="optionbox"><input name="5Thru10M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	       	$value = $row['10Thru15M'];
	        $out .= '<td class="optionbox"><input name="10Thru15M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['15Thru20M'];
	        $out .= '<td class="optionbox"><input name="15Thru20M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['20Thru30M'];
	        $out .= '<td class="optionbox"><input name="20Thru30M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['30Thru40M'];
	        $out .= '<td class="optionbox"><input name="30Thru40M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['40Thru50M'];
	        $out .= '<td class="optionbox"><input name="40Thru50M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['50Thru60M'];
	        $out .= '<td class="optionbox"><input name="50Thru60M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['60Thru70M'];
	        $out .= '<td class="optionbox"><input name="60Thru70M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['70Thru80M'];
	        $out .= '<td class="optionbox"><input name="70Thru80M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['80Thru90M'];
	        $out .= '<td class="optionbox"><input name="80Thru90M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['90Thru100M'];
	        $out .= '<td class="optionbox"><input name="90Thru100M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['100upM'];
	        $out .= '<td class="optionbox"><input name="100upM'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';

	//		  Free white females input boxes 
			$value = $row['under5F'];
	        $out .= '<td class="optionbox"><input name="under5F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['5Thru10F'];
	        $out .= '<td class="optionbox"><input name="5Thru10F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	       	$value = $row['10Thru15F'];
	        $out .= '<td class="optionbox"><input name="10Thru15F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['15Thru20F'];
	        $out .= '<td class="optionbox"><input name="15Thru20F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['20Thru30F'];
	        $out .= '<td class="optionbox"><input name="20Thru30F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['30Thru40F'];
	        $out .= '<td class="optionbox"><input name="30Thru40F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['40Thru50F'];
	        $out .= '<td class="optionbox"><input name="40Thru50F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['50Thru60F'];
	        $out .= '<td class="optionbox"><input name="50Thru60F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['60Thru70F'];
	        $out .= '<td class="optionbox"><input name="60Thru70F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['70Thru80F'];
	        $out .= '<td class="optionbox"><input name="70Thru80F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['80Thru90F'];
	        $out .= '<td class="optionbox"><input name="80Thru90F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['90Thru100F'];
	        $out .= '<td class="optionbox"><input name="90Thru100F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['100upF'];
	        $out .= '<td class="optionbox"><input name="100upF'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';

	//  	  Other Persons and Slaves input boxes
	        $value = $row['slavesUnder10M'];
	        $out .= '<td class="optionbox"><input name="slavesUnder10M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves10To24M'];
	        $out .= '<td class="optionbox"><input name="slaves10To24M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves24To36M'];
	        $out .= '<td class="optionbox"><input name="slaves24To36M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves36To55M'];
	        $out .= '<td class="optionbox"><input name="slaves36To55M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['slaves55To100M'];
	        $out .= '<td class="optionbox"><input name="slaves55To100M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves100upM'];
	        $out .= '<td class="optionbox"><input name="slaves100upM'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slavesUnder10F'];
	        $out .= '<td class="optionbox"><input name="slavesUnder10F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves10To24F'];
	        $out .= '<td class="optionbox"><input name="slaves10To24F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves24To36F'];
	        $out .= '<td class="optionbox"><input name="slaves24To36F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves36To55F'];
	        $out .= '<td class="optionbox"><input name="slaves36To55F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['slaves55To100F'];
	        $out .= '<td class="optionbox"><input name="slaves55To100F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['slaves100upF'];
	        $out .= '<td class="optionbox"><input name="slaves100upF'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        
	// 			Free Colored Persons input boxes
			$value = $row['FreeSlavesUnder10M'];
	        $out .= '<td class="optionbox"><input name="FreeSlavesUnder10M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves10To24M'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves10To24M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves24To36M'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves24To36M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves36To55M'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves36To55M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['slaves55To100M'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves55To100M'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves100upM'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves100upM'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['FreeSlavesUnder10F'];
	        $out .= '<td class="optionbox"><input name="FreeSlavesUnder10F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves10To24F'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves10To24F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves24To36F'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves24To36F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves36To55F'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves36To55F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
			$value = $row['slaves55To100F'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves55To100F'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['FreeSlaves100upF'];
	        $out .= '<td class="optionbox"><input name="FreeSlaves100upF'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	       
	       //Other inputs
	        $value = $row['Totle'];
	        $out .= '<td class="optionbox"><input name="Totle'.$i.'" type="text" size="4" value="'.htmlentities($value).'"></td>';
	        $value = $row['DeafDumbUnder14'];
	        $out .= '<td class="optionbox"><input name="DeafDumbUnder14'.$i.'" type="text" size="15" value="'.htmlentities($value).'"></td>';
	        $value = $row['DeafDumb14to25'];
	        $out .= '<td class="optionbox"><input name="DeafDumb14to25'.$i.'" type="text" size="23" value="'.htmlentities($value).'"></td>';
	        $value = $row['Deaf25andUp'];
	        $out .= '<td class="optionbox"><input name="Deaf25andUp'.$i.'" type="text" size="16" value="'.htmlentities($value).'"></td>';
	        $value = $row['Blind'];
	        $out .= '<td class="optionbox"><input name="Blind'.$i.'" type="text" size="10" value="'.htmlentities($value).'"></td>';
	        $value = $row['ALIENS'];
	        $out .= '<td class="optionbox"><input name="ALIENS'.$i.'" type="text" size="14" value="'.htmlentities($value).'"></td>';
	        $value = $row['DeafDumbUnder14Slaves'];
	        $out .= '<td class="optionbox"><input name="DeafDumbUnder14Slaves'.$i.'" type="text" size="16" value="'.htmlentities($value).'"></td>';
	        $value = $row['DeafDumb14to25Slaves'];
	        $out .= '<td class="optionbox"><input name="DeafDumb14to25Slaves'.$i.'" type="text" size="23" value="'.htmlentities($value).'"></td>';
	        $value = $row['Deaf25andUpSlaves'];
	        $out .= '<td class="optionbox"><input name="Deaf25andUpSlaves'.$i.'" type="text" size="16" value="'.htmlentities($value).'"></td>';
	        $value = $row['BlindSlaves'];
	        $out .= '<td class="optionbox"><input name="BlindSlaves" type="text" size="10" value="'.htmlentities($value).'"></td>';
	        
        }
        $out .= '</table></td></tr>';
        return $out;
    }

    function footer() {
        return '</table></form>';
    }

    function display_form() {
        $out = $this->header("module.php?mod=research_assistant&form=Census1830&action=func&func=step2&taskid=$_REQUEST[taskid]", "center", "1830 United States Federal Census", true);
        $out .= $this->sourceCitationForm(5);
        //$out .= $this->content();
        $out .= $this->footer();
        return $out;
    }
    
    function step2() {
		global $GEDCOM, $GEDCOMS, $TBLPREFIX, $DBCONN, $factarray, $pgv_lang;
		global $INDI_FACTS_ADD;
		
		$this->processSourceCitation();
		
		$out = $this->header("module.php?mod=research_assistant&form=Census1830&action=func&func=step3&taskid=" . $_REQUEST['taskid'], "center", "1830 United States Federal Census");
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
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1830";
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
		$text = $_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", 1830 US Census";
		for($number = 0; $number < $_REQUEST['numOfRows']; $number++)
		{
			$rows[$number] = array(
			'headName'=>$_POST["headName".$number],
			'underTenM'=>$_POST["underTenM".$number],
			'tenThruFifteenM'=>$_POST["tenThruFifteenM".$number],
			"sixteenThruTwentyfiveM"=>$_POST["sixteenThruTwentyfiveM".$number],
			"twentysixThruFortyfourM"=>$_POST["twentysixThruFortyfourM".$number],
			"fortyfiveAndOverM"=>$_POST["fortyfiveAndOverM".$number],
			"underTenF"=>$_POST["underTenF".$number],
			"tenThruFifteenF"=>$_POST["tenThruFifteenF".$number],
			"sixteenThruTwentyfiveF"=>$_POST["sixteenThruTwentyfiveF".$number],
			"twentysixThruFortyfourF"=>$_POST["twentysixThruFortyfourF".$number],
			"fortyfiveAndOverF"=>$_POST["fortyfiveAndOverF".$number],
			"otherPersons"=>$_POST["otherPersons".$number],
			"slaves"=>$_POST["slaves".$number]
			);
			$text .=$number==0?"" :"\r\n";
			$text .= "\r\nHead of Family: ".$_POST["headName".$number];
			$text .= "\r\nMales under 10: ".$_POST["underTenM".$number];
			$text .= "\r\nMales Ten thru fifteen: ".$_POST["tenThruFifteenM".$number];
			$text .= "\r\nMales Sixteen thru Twenty five: ".$_POST["sixteenThruTwentyfiveM".$number];
			$text .= "\r\nMales Twenty six thru Forty four: ".$_POST["twentysixThruFortyfourM".$number];
			$text .= "\r\nMales Forty five and Over: ".$_POST["fortyfiveAndOverM".$number];
			$text .= "\r\nFemales under 10: ".$_POST["underTenF".$number];
			$text .= "\r\nFemales Ten thru fifteen: ".$_POST["tenThruFifteenF".$number];
			$text .= "\r\nFemales Sixteen thru Twenty five: ".$_POST["sixteenThruTwentyfiveF".$number];
			$text .= "\r\nFemales Twentysix thru Forty four: ".$_POST["twentysixThruFortyfourF".$number];
			$text .= "\r\nFemales Forty five and Over: ".$_POST["fortyfiveAndOverF".$number];
			$text .= "\r\nAll other Free Persons: ".$_POST["otherPersons".$number];
			$text .= "\r\nSlaves: ".$_POST["slaves".$number];
		}

		$citation = array(
			"PAGE"=>"Page: ".$_POST['page'].", Call Number/URL: ".$_POST['CallNumberURL'], 
			"QUAY"=>'', 
    		"DATE"=>!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"1830", 
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
