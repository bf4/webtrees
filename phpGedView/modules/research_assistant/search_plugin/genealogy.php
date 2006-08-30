<?php 
/**
 * Search Plug-in
 *
 * This is a plug-in file for the Auto search Assistant
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
 * @version 
 * @package PhpGedView
 * @subpackage Research Assistant
 * @Author Dparker
 * 
 * For further commenting see ancestry.php
 */
require_once("includes/person_class.php");
include("modules/research_assistant/languages/lang.en.php");


function autosearch_options()
{
	global $pgv_lang;
	//Title
	$pgv_lang["autosearch_plugin_name"] = "Genealogy.com Plug-In";
	
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
		if (!is_object($person)) return "";
		$givennames = $person->getGivenNames();
		$lastname = $person->getSurname();
		$byear = $person->getBirthYear();
		$dyear = $person->getDeathYear();
		$dloc = $person->getDeathPlace();
		$bloc = $person->getBirthPlace();
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						<input type=\"hidden\" name=\"searchtype\" value=\"genealogy\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>		
		 						<tr>
					 				<td class='optionbox'>
					 					".$pgv_lang["autosearch_surname"]."</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' /> ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_givenname"]."</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' /> ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					".$pgv_lang["autosearch_byear"]."</td><td class ='optionbox'> <input type='checkbox' name='byear' value=\"".$byear."\" checked='checked' /> ".$byear."</td></tr>
					 						
";
if(isset($bloc)){	
	$to_return.="<tr><td class='optionbox'>	".$pgv_lang["autosearch_bloc"]."</td><td class='optionbox'> <input type='checkbox' name='bloc' value=\"".$bloc."\"  />".$bloc."
					 				</td>
		 						</tr>";
	}
	$to_return .=  				"<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_dyear"]."</td><td class='optionbox'> <input type='checkbox' name='dyear' value=\"".$dyear."\"  />".$dyear."
					 				</td>
		 						</tr>
								
";					 				
	if(isset($dloc)){
	$to_return.="<tr><td class='optionbox'>".$pgv_lang["autosearch_dloc"]."</td><td class='optionbox'> <input type='checkbox' name='dloc' value=\"".$dloc."\"  />".$dloc."</td></tr>	";	
	}

	
		$to_return.=		"	<tr><td class='optionbox' colspan=2 align='center'>".$pgv_lang["autosearch_plugin_name"]."</td></tr>
<tr><td  align='center' class='topbottombar'colspan=2><input type='submit' value='Search' /></td></tr>
		 			
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
		$byear = $person->getBirthYear();
		$dyear = $person->getDeathYear();
		$dloc = $person->getDeathPlace();
		$bloc = $person->getBirthPlace();
	$url = "http://www.genealogy.com/cgi-bin/wizard_search.cgi?MN=";
	
	if(isset($_REQUEST['surname'])){
		$url .= "&LN=".urlencode($_REQUEST['surname']);
	}
	
	if(isset($_REQUEST['givenname1'])){
		$url.= "&FN=".urlencode($_REQUEST['givenname1']);
	}
	if(isset($_REQUEST['byear'])){
		
			$url.= "&BDATE=".urlencode($_REQUEST['byear']);
	}
	if(isset($_REQUEST['dyear'])){
			$url.= "&DDATE=".urlencode($_REQUEST['dyear']);
		
	}
	if(isset($_REQUEST['dloc'])){
			$url.= "&DLOCATION=".urlencode($_REQUEST['dloc']);
		
	}
	if(isset($_REQUEST['bloc'])){
			$url.= "&BLOCATION=".urlencode($_REQUEST['bloc']);
		
	}
	// debug: print the $_REQUEST
	//return $ret;  
	Header("Location: ".$url);
	exit;
	
}
?>