<?php
/**
 * phpGedView Research Assistant Tool - ra_ViewInferences
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
 * 
 * @author Mike Austin
 * @author Gavin Winkler
 * @author Mike Hessick
 * @author David Molton
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Require our base class
require_once'ra_form.php';
/**
 * Edit Folder class for the editfolder form
 * 
 * @uses ra_form
 */
 

class ra_ViewInferences extends ra_form {
	
	function getPartsTranslation($input) {
		global $factarray, $pgv_lang;
		if ($input=="FAMC:HUSB") $input = "father";
		if ($input=="FAMC:WIFE") $input = "mother";
		if ($input=="FAMS:SPOUSE") $input = "spouse";
		$parts = preg_split("/:/", $input);
		$out = "";
		foreach($parts as $part) {
			if (isset($factarray[$part])) $out .= $factarray[$part];
			else if (isset($pgv_lang[$part])) $out .= $pgv_lang[$part];
			else $out .= $part;
			$out .= " ";
		}
		return $out;
	}
    /**
     * content 
     * 
     * @param mixed $folder_id The id of the folder to edit
     * @return mixed
     */
    /**
     * The contents function access the probabilities table in the database.
     * From this table it draws out 4 things. The first level element in the gedcom record
     * The second level element in the gedcom file **If there is one**
     * The relationship that the fact is concerned with
     * The percentage of matches for this fact
     */
 	function contents() {
 		global $TBLPREFIX,$DBCONN, $GEDCOMS, $GEDCOM;
 		global $factsfile, $LANGUAGE, $factarray;
		require_once($factsfile["english"]);
		if (file_exists($factsfile[$LANGUAGE])) require_once($factsfile[$LANGUAGE]);
		 		
 		$out = "<table class=\"width80\" align=\"center\"><tr><td><p>This page analyzes the data for the active GEDCOM dataset and shows the correlations between different data elements. ";
 		$out .= "For example, there could be a 95% correlation that the surname in a local record is the same as the surname in the father's record.  This would mean that 95% of the people in this GEDCOM dataset share the same surname as their father.</p>";
 		$out .= "<p>In this version of the Research Assistant, these calculations are not being used in other areas of the program and are only provided as a" .
 				"as a help to you in your research.  In the future we plan to use this data to help provide you with meaningful suggestions of where you " .
 				"should focus some of your future research.</p></td></tr></table>";
 		$out .= "<table align='center'><tr><td class='topbottombar' colspan='4'><b>Data Correlations</b></td></tr>";
 		$out .= "<tr><td class=\"descriptionbox\">Local Data</td><td class=\"descriptionbox\">Related Record</td><td class=\"descriptionbox\">Related Data</td><td  class=\"descriptionbox\">Percent</td></tr>";
 		
 		$sql = "select * from ".$TBLPREFIX."probabilities where pr_file=".$GEDCOMS[$GEDCOM]['id']." ORDER BY (pr_matches / pr_count) DESC";
 		$result = dbquery($sql);
 		if($result->numRows()==0 || !empty($_REQUEST['recount'])) {
 			$inferences = ra_functions::inferences();
 			$result = dbquery($sql);
 		}
 		
 		//This takes the info from that database and displays it to the user
 		//The formatting is accomplished by using a HTML table
 		if($result->numRows()>0)
 		{
 			while($row = $result->fetchRow(DB_FETCHMODE_ASSOC))
 				{
 					$out .= "<tr><td class='optionbox'>";
 					$out .= $this->getPartsTranslation($row['pr_f_lvl']);
					$out .= "</td>"; 
 					$out .= "<td class='optionbox'>".$this->getPartsTranslation($row['pr_s_lvl'])."</td>"; 
 					$out .= "<td class='optionbox'>".$this->getPartsTranslation($row['pr_rel'])."</td>";
 					if ($row['pr_count']==0) $row['pr_per'] = 0;
 					else $row['pr_per'] = 100*($row['pr_matches']/$row['pr_count']); 
 					$out .= "<td class='optionbox'>". sprintf("%.2f%%",$row['pr_per'])."</td></tr>"; 
 				}
 		}
 		$out .= "<tr><td class='topbottombar' colspan='4'><form method=\"get\" action=\"\"><input type=\"button\" value=\"Recalculate\" onclick=\"window.location='http://localhost/fresh/module.php?mod=research_assistant&action=viewProbabilities&amp;recount=1';\" /></form></td></tr>";
 		//Returns the table to display
 		return $out;
 	}
	function display_form(){ 
		$output = ra_form::heading();

        $output .= ra_form::title();

        $output .= $this->contents();

        $output .= ra_form::footer();

        return $output;
	}
	//Creates the header of the HTML page
	 function heading($action = 'self', $tableAlign = 'center', $heading = '') {
        $out = '<form action="' . $action . '" method="post">
            <table class="list_table" align="' . $tableAlign . '">
                <tr>
                    <th colpsan="4" align="right"><h2>' . $heading . '</h2></th>
                </tr>';
        return $out;
    }

    /**
     * title 
     * 
     * @param string $title 
     * @access public
     * @return void
     */
     //Creates the title
    function title($title = '') {
        $out = '<tr>
                    <th>' . $title . '</th>
                </tr>';
        return $out;
    }
	
	
	
    /**
     * Show the form to the user
     * 
     * @return object
     */
 	
 }
?>
