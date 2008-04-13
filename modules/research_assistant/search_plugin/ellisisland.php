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
 * @version $id$
 * @package PhpGedView
 * @subpackage Research Assistant
 * @Author Dparker
 * 
 * For further commenting see ancestry.php
 */
 
require_once("includes/person_class.php");

function autosearch_options()
{
	global $pgv_lang;
	//title

	
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
	if (!is_object($person)) return "";
	$givennames = $person->getGivenNames();
	$lastname = $person->getSurname();
	$gender = $person->getSex();
	$seximg = $person->getSexImage();
	$bdate=$person->getEstimatedBirthDate();
	$byear=$bdate->gregorianYear();
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						<input type=\"hidden\" name=\"searchtype\" value=\"ellisisland\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>		
		 						<tr>
					 				<td class='optionbox'>
					 					".$pgv_lang["autosearch_surname"]."</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' />&nbsp; ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_givenname"]."</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' />&nbsp; ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					".$pgv_lang["autosearch_byear"]."</td><td class ='optionbox'> <input type='checkbox' name='byear' value=\"".$byear."\" checked='checked' />&nbsp; ".$byear."</td></tr>
					 						 						<tr><td class ='optionbox'>
					 					".$pgv_lang["autosearch_gender"]."</td><td class ='optionbox'> <input type='checkbox' name='gender' value=\"".$gender."\" checked='checked' />&nbsp; ".$gender.$seximg."</td></tr>				
								<tr><td class='optionbox' colspan=2 align='center'>".$pgv_lang["autosearch_plugin_name_ellisIsland"]."</td></tr>
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
		
	
	
	$url = "http://www.ellisislandrecords.org/search/matchMore.asp?";
	
	if(isset($_REQUEST['givenname1'])){
		$url.= "FNM=".urlencode($_REQUEST['givenname1']);
	}
	else
	{
		$url.= "FNM=none";
	}

	if(isset($_REQUEST['surname'])){
		$url .= "&LNM=".urlencode($_REQUEST['surname']);
	}
	else
	{
		$url .= "&LNM=none";
	}
		

	if(isset($_REQUEST['byear'])){
		
			$url.= "&bSYR=".urlencode($_REQUEST['byear']-2);
			$url.= "&bEYR=".urlencode($_REQUEST['byear']+2);
	}
	
	
	if(isset($_REQUEST['gender'])){
		$url.= "&CGD=".urlencode($_REQUEST['gender']);
	}

	$url .="&first_kind=1";
	
	// debug: print the $_REQUEST
	//return $ret;  
	Header("Location: ".$url);
	exit;
	
}
?>
