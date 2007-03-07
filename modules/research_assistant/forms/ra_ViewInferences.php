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
include_once("ra_RSFunction.php");
/**
 * Edit Folder class for the editfolder form
 * 
 * @uses ra_form
 */
 

class ra_ViewInferences extends ra_form {
	
	function getPartsTranslation($input,$otherFact = "") {
		global $factarray, $pgv_lang;
	
		if ($input=="FAMC:HUSB") $input = $pgv_lang["father"];
		if ($input=="FAMC:WIFE") $input = $pgv_lang["mother"];
		if ($input=="FAMS:SPOUSE") $input = $pgv_lang["spouse"];
		$parts = preg_split("/:/", $input);
		$out = "";
		
			if(!empty($otherFact) && !empty($input))
			{
				if (isset($factarray[$input.":".$otherFact])) $out .= $factarray[$input.":".$otherFact];
				else if (isset($pgv_lang[$input])) $out .= $pgv_lang[$input];
				else $out .= $input;
			$out .= " ";
		}
			else
			{
				if (isset($factarray[$input])) $out .= $factarray[$input];
				else if (isset($pgv_lang[$input])) $out .= $pgv_lang[$input];
				else $out .= $input;
				if(empty($input))
				{
					$out .= "Self";
				}
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
 		global $LANGUAGE, $factarray, $pgv_lang;
				 		
 		$out = "<table class=\"width80\" align=\"center\"><tr><td><p>".$pgv_lang["ViewProbExplanation"]."</p></td></tr></table>";
 		if(isset($_REQUEST['pid']))
 		{
	 		$out .= "<table align='center'><tr><td class='topbottombar' colspan='7'><b>".$pgv_lang["DataCorrelations"]."</b></td></tr>";
	 		$out .= "<tr><td class=\"descriptionbox\">".$pgv_lang["LocalData"]."</td><td class=\"descriptionbox\">".$pgv_lang["RelatedRecord"]."</td><td class=\"descriptionbox\">".$pgv_lang["RelatedData"]."</td><td  class=\"descriptionbox\">".$pgv_lang["LocalPercent"]."</td><td  class=\"descriptionbox\">".$pgv_lang["GlobalPercent"]."</td><td  class=\"descriptionbox\">".$pgv_lang["Average"]."</td><td  class=\"descriptionbox\">".$pgv_lang["RelatedData"]."</td></tr>";
 		}
 		else
 		{
 			$out .= "<table align='center'><tr><td class='topbottombar' colspan='7'><b>".$pgv_lang["DataCorrelations"]."</b></td></tr>";
 		$out .= "<tr><td class=\"descriptionbox\">".$pgv_lang["LocalData"]."</td><td class=\"descriptionbox\">".$pgv_lang["RelatedRecord"]."</td><td class=\"descriptionbox\">".$pgv_lang["RelatedData"]."</td><td  class=\"descriptionbox\">".$pgv_lang["LocalPercent"]."</td></tr>";
 		
 		}
 		if(empty($_REQUEST["pid"]))
 		{
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
	 					$out .= "<td class='optionbox'>".$this->getPartsTranslation($row['pr_s_lvl'],$row['pr_rel'])."</td>"; 
	 					$out .= "<td class='optionbox'>".$this->getPartsTranslation($row['pr_rel'])."</td>";
	 					if ($row['pr_count']==0) $row['pr_per'] = 0;
	 					else $row['pr_per'] = 100*($row['pr_matches']/$row['pr_count']); 
	 					$out .= "<td class='optionbox'>". sprintf("%.2f%%",$row['pr_per'])."</td></tr>"; 
	 				}
	 		}
	 		$out .= "<tr><td class='topbottombar' colspan='4'><form method=\"get\" action=\"\"><input type=\"button\" value=\"".$pgv_lang["Recalculate"]."\" onclick=\"window.location='module.php?mod=research_assistant&action=viewProbabilities&recount=1';\" /></form></td></tr>";
	 		//Returns the table to display
 		}
 		else
 		{
 			
 			$avgExists = false;
 			$pInfer = run($_REQUEST["pid"]);
// 			print_r($pInfer);

 			$avLocal = 0;
 			$avGlobal = 0;
 			foreach($pInfer as $myKey=>$myVal)
 			{
 				$out .= "\n<tr>";
 				
 				foreach($myVal as $actKey=>$niceData)
 				{
 					if($actKey !== "LocalCount" && $actKey !== "GlobalCount")
 					{
 						$out .= "<td class=\"optionbox\">";
 						if($actKey === "Prob" || $actKey === "GlobalProb")
 						{
 							if($actKey === "Prob")
 							{
 								$localAvg = $niceData;
 							}
 							
 							if($actKey === "GlobalProb")
 							{
 								$globalAvg = $niceData * 100;
 							}
 							
 							if($myVal['LocalCount'] == 0 && $actKey === "Prob")
 							{
 								$out .= $pgv_lang["NoData"];
 							}
 							else
 							{
 								if($actKey === "Prob")
 								{
	 							$out .= sprintf("%.2f%%",$niceData);
 								}
 							}
 							
 							if($myVal['GlobalCount'] == 0 && $actKey === "GlobalProb")
 							{
 								$out .= $pgv_lang["NoData"];
 							}
 							else
 							{
 								if($actKey === "GlobalProb")
 								{
 									$niceData = $niceData *100;
	 							$out .= sprintf("%.2f%%",$niceData);
 								}
 							}
 						}
 						else
 						{
 							$out .= getPartsTranslation($niceData);
 						}
						$out .= "</td>";
						
						if($actKey === 2)
						{
							$factTag = $niceData;
						}
						if($actKey === 1)
						{
							$relatedTag = $niceData;
						
						}
 					}
 					
 				}
 				if($localAvg == 0 && $globalAvg == 0)
 				{
 					$out .= "<td class=\"optionbox\">".$pgv_lang["NotEnoughData"]."</td>";
 					$avgExists = false;
 				}
 				if($localAvg == 0 && $globalAvg != 0)
 				{
 					$out .= "<td class=\"optionbox\">".sprintf("%.2f%%",$globalAvg)."</td>";
 					$avgExists = true;
 				}
 				if($globalAvg == 0 && $localAvg != 0)
 				{
 					$out .= "<td class=\"optionbox\">".sprintf("%.2f%%",$localAvg)."</td>";
 					$avgExists = true;
 				}
 				if($globalAvg != 0 && $localAvg != 0)
 				{			
 					$avg = ($localAvg+$globalAvg) /2;
 					$out .= "<td class=\"optionbox\">".sprintf("%.2f%%",$avg)."</td>";
 					$avgExists = true;
 				}
				
 				//Now we check to see if enough data existed to create a average for the user
 				//if so we will display relevant data
 				if($avgExists)
 				{
 					$tempVar = $this->getFactValue($_REQUEST['pid'],$relatedTag,$factTag);
 					
 					$compoundFact = $relatedTag.":".$factTag;
 					if($compoundFact[0] == ":")
 					{
 						$compoundFact = trim($compoundFact, ":");
 					}
 					if(!empty($tempVar))
 					{
 					$out .=  "<td class=\"optionbox\"><b>".$factarray[$compoundFact].":</b> ".$tempVar."</td>";
 					}
 					else
 					{
 						$out .= "<td class=\"optionbox\"></td>";
 					}
 				}
 				else
 				{
 					$out .= "<td class=\"optionbox\">".$pgv_lang["NotEnoughData"]."</td>";
 				}
 				
 				$out .= "</tr>";
 			}
 		
 		}
 		return $out;
 	}
 	
 	function getFactValue($pid, $recordTag, $factTag)
 	{
		$tempRecord = getRecord($recordTag,$pid);
		//get the value of the tag from the related gedcom
 		if ($factTag=='SURN') {
 			$person = new Person($tempRecord);
 			$factRelation = $person->getSurname();
 		}
		else if ($factTag=='GIVN'){
			$person = new Person($tempRecord);
 			$factRelation = $person->getGivenNames();
		}
		else {
			$factRelation =	get_gedcom_value($factTag,1,$tempRecord);
		}
		
		return $factRelation;
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
