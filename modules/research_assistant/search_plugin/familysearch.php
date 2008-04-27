<?php 
/**
 * Search Plug-in
 *
 * This is a plug-in file for the Auto search Assistant
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Research Assistant
 * @Author Dparker
 *  
 * For further commenting see ancestry.php
 */
 
require_once("includes/person_class.php");
require_once("includes/family_class.php");

function autosearch_options()
{
	global $pgv_lang;
	
	
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
	if (!is_object($person)) return "";
	$givennames = $person->getGivenNames();
	$lastname = $person->getSurname();
	$bdate=$person->getEstimatedBirthDate();
	$ddate=$person->getEstimatedDeathDate();
	$byear=$bdate->gregorianYear();
	$dyear=$ddate->gregorianYear();
		
		//Retrieving mother and father information
		$families = $person->getChildFamilies();
		
	
		//get  the first family
		foreach($families as $key=>$family) break;
	
		//if the family exists which it should
		if(!empty($family))
		{
			$father = $family->getHusband();
			$mother = $family->getWife();
			
			//if father is known give values
			if(!empty($father))
			{
				$fgivennames = $father->getGivenNames();
				$fsurname = $father->getSurname();
			}
			else
			{
				$fgivennames = "";
				$fsurname = "";
			}
			
			//if mother is known give values
			if(!empty($mother))
			{
				$mgivennames = $mother->getGivenNames();
				$msurname = $mother->getSurname();
			}
			else
			{
				$mgivennames = "";
				$msurname = "";
			}
		}
		else
		{
			$father = "";
			$mother = "";
			$fgivennames = "";
			$fsurname = "";
			$mgivennames = "";
			$msurname = "";
		}
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						<input type=\"hidden\" name=\"searchtype\" value=\"familysearch\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>			
		 						<tr>
					 				<td class='optionbox'>
					 					".$pgv_lang["autosearch_surname"]."</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' />&nbsp; ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_givenname"]."</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' />&nbsp; ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					".$pgv_lang["autosearch_byear"]."</td><td class ='optionbox'> <input type='radio' name='year' value=\"".$byear."\" checked='checked' /> &nbsp;".$byear."</td></tr>
					 						<tr><td class='optionbox'>
											".$pgv_lang["autosearch_dyear"]."</td><td class='optionbox'> <input type='radio' name='year' value=\"".$dyear."\"  />&nbsp;".$dyear."
					 				</td>
		 						</tr>";
		 						
		if(!empty($fgivennames))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_fgivennames"]."</td><td class='optionbox'> <input type='checkbox' name='fgivennames' value=\"".$fgivennames."\"  />&nbsp;".$fgivennames."
					 				</td>
		 						</tr>";
		}
		if(!empty($fsurname))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_fsurname"]."</td><td class='optionbox'> <input type='checkbox' name='fsurname' value=\"".$fsurname."\"  />&nbsp;".$fsurname."
					 				</td>
		 						</tr>";
		}
		if(!empty($mgivennames))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_mgivennames"]."</td><td class='optionbox'> <input type='checkbox' name='mgivennames' value=\"".$mgivennames."\"  />&nbsp;".$mgivennames."
					 				</td>
		 						</tr>";
		}
		if(!empty($msurname))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_msurname"]."</td><td class='optionbox'> <input type='checkbox' name='msurname' value=\"".$msurname."\"  />&nbsp;".$msurname."
					 				</td>
		 						</tr>";
		}
		 					$to_return .= "	<tr><td class='optionbox' colspan=2 align='center'>".$pgv_lang["autosearch_plugin_name_fs"]."</td></tr>	
							<tr><td  align='center' class='topbottombar'colspan=2><input type='submit' value='".$pgv_lang["autosearch_search"]."' /></td></tr>
							</table>						
					</form>";
 
		 	
		return $to_return;
}
function autosearch_process() {
	//debug line to print the request array
	//$ret = print_r($_REQUEST, true);
	
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
	if (!is_object($person)) return "";
	$bdate=$person->getEstimatedBirthDate();
	$ddate=$person->getEstimatedDeathDate();
	$byear=$bdate->gregorianYear();
	$dyear=$ddate->gregorianYear();
	
	$url = "http://www.familysearch.org/Eng/search/ancestorsearchresults.asp?";
	
	if(isset($_REQUEST['surname'])){
		$url .= "last_name=".urlencode($_REQUEST['surname']);
		
		//these are all dependant on if the first box is checked
		if(isset($_REQUEST['givenname1'])){
		$url.= "&first_name=".urlencode($_REQUEST['givenname1']);
		}
		if(isset($_REQUEST['year'])){
			if($_REQUEST['year'] == $byear){
				$url.= "&event_index=1&date_range=2&from_date=".urlencode($_REQUEST['year']);
			}
			else{
				$url.= "&event_index=3&date_range=2&from_date=".urlencode($_REQUEST['year']);
			}
		}
		
		if(isset($_REQUEST['fgivennames'])){
		$url.= "&fathers_first_name=".urlencode($_REQUEST['fgivennames']);
		}
		
		if(isset($_REQUEST['fsurname'])){
		$url.= "&fathers_last_name=".urlencode($_REQUEST['fsurname']);
		}
		if(isset($_REQUEST['mgivennames'])){
		$url.= "&mothers_first_name=".urlencode($_REQUEST['mgivennames']);
		}
		
		if(isset($_REQUEST['msurname'])){
		$url.= "&mothers_last_name=".urlencode($_REQUEST['msurname']);
		}			
	}
	
	
	// debug: print the $_REQUEST
	//return $ret;  
	Header("Location: ".$url);
	exit;
	
}

?>
