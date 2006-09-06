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
require("modules/research_assistant/languages/lang.en.php");
global $lang_short_cut, $LANGUAGE;
if (file_exists("modules/research_assistant/languages/.".$lang_short_cut[$LANGUAGE].".php")) require("modules/research_assistant/languages/.".$lang_short_cut[$LANGUAGE].".php");

function autosearch_options()
{
	global $pgv_lang;
	//Title
	$pgv_lang["autosearch_plugin_name"] = "Ancestry.co.uk Plug-In";
	
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
		if (!is_object($person)) return "";
		$givennames = $person->getGivenNames();
		$lastname = $person->getSurname();
		$byear = $person->getBirthYear();
		$dyear = $person->getDeathYear();
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						"; //The value of the searchtype should be the same as the file name minus .php 
		$to_return ="<input type=\"hidden\" name=\"searchtype\" value=\"ancestrycouk\" />
						
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>		
		 						<tr>
					 				<td class='optionbox'>
					 					".$pgv_lang["autosearch_surname"]."</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' /> ".$lastname."</td></tr>
					 				<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_givenname"]."</td>
											<td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' /> ".$givennames."</td>
									</tr>
					 				<tr><td class ='optionbox'>
					 					".$pgv_lang["autosearch_byear"]."</td><td class ='optionbox'> <input type='checkbox' name='byear' value=\"".$byear."\" checked='checked' /> ".$byear."</td></tr>
					 				<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_dyear"]."</td><td class='optionbox'> <input type='checkbox' name='dyear' value=\"".$dyear."\"  />".$dyear."</td></tr>
									<tr><td class='optionbox' colspan=2 align='center'>
										".$pgv_lang["autosearch_plugin_name"]."</td></tr>
									<tr><td  align='center' class='topbottombar'colspan=2>
										<input type='submit' value='Search' /></td></tr>
		 			
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
	
	$url = "http://search.ancestry.co.uk/cgi-bin/sse.dll?";
	
	if(isset($_REQUEST['surname'])){
		$url .= "&gsln=".urlencode($_REQUEST['surname']);
	}
	
	if(isset($_REQUEST['givenname1'])){
		$url.= "&gsfn=".urlencode($_REQUEST['givenname1']);
	}
	if(isset($_REQUEST['byear'])){
		
			$url.= "&gsby=".urlencode($_REQUEST['byear']);
	}
	if(isset($_REQUEST['dyear'])){
			$url.= "&gsdy=".urlencode($_REQUEST['dyear']);
		
	}
	
	// debug: print the $_REQUEST
	//return $ret;  
	Header("Location: ".$url);
	exit;
	
}
?>