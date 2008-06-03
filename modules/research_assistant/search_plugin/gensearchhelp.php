<?php 
/**
 * Search Plug-in
 *
 * This is a plug-in file for the Auto search Assistant
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PhpGedView Development Team.  All rights reserved.
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
require_once("includes/family_class.php");
//require("modules/research_assistant/languages/lang.en.php");
//global $lang_short_cut, $LANGUAGE;
//if (file_exists("modules/research_assistant/languages/.".$lang_short_cut[$LANGUAGE].".php")) require("modules/research_assistant/languages/.".$lang_short_cut[$LANGUAGE].".php");

function autosearch_options()
{
	global $pgv_lang;
	
	
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
		if (!is_object($person)) return "";
		$givennames = $person->getGivenNames();
		$lastname = $person->getSurname();
		$dloc = $person->getDeathPlace();
		$bloc = $person->getBirthPlace();
		
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


    $spouse = $person->getCurrentSpouse();

  	//if spouse is known give values
  	if(!empty($spouse))
  	{
  		$sgivennames = $spouse->getGivenNames();
  		$ssurname = $spouse->getSurname();
  	}
  	else
  	{
  		$sgivennames = "";
  		$ssurname = "";
  	}

		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						<input type=\"hidden\" name=\"searchtype\" value=\"gensearchhelp\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>			
		 						<tr>
					 				<td class='optionbox'>
					 					".$pgv_lang["autosearch_surname"]."</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' />&nbsp; ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					".$pgv_lang["autosearch_givenname"]."</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' />&nbsp; ".$givennames."
					 				</td>
		 						</tr>";
		 						
  if(isset($bloc)){	
	$to_return.="<tr><td class='optionbox'>".$pgv_lang["autosearch_bloc"]."</td><td class='optionbox'> <input type='text' name='bloc' value=\"".$bloc."\" /></td></tr>";
	}
	if(isset($dloc)){
	$to_return.="<tr><td class='optionbox'>".$pgv_lang["autosearch_dloc"]."</td><td class='optionbox'> <input type='text' name='dloc' value=\"".$dloc."\" /></td></tr>";	
	}


		if(!empty($fgivennames))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_fgivennames"]."</td><td class='optionbox'> <input type='checkbox' name='fgivennames' value=\"".$fgivennames."\" checked='checked' />&nbsp;".$fgivennames."
					 				</td>
		 						</tr>";
		}
		if(!empty($fsurname))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_fsurname"]."</td><td class='optionbox'> <input type='checkbox' name='fsurname' value=\"".$fsurname."\" checked='checked' />&nbsp;".$fsurname."
					 				</td>
		 						</tr>";
		}
		if(!empty($mgivennames))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_mgivennames"]."</td><td class='optionbox'> <input type='checkbox' name='mgivennames' value=\"".$mgivennames."\" checked='checked' />&nbsp;".$mgivennames."
					 				</td>
		 						</tr>";
		}
		if(!empty($msurname))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_msurname"]."</td><td class='optionbox'> <input type='checkbox' name='msurname' value=\"".$msurname."\" checked='checked' />&nbsp;".$msurname."
					 				</td>
		 						</tr>";
		}
		if(!empty($sgivennames))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_sgivennames"]."</td><td class='optionbox'> <input type='checkbox' name='sgivennames' value=\"".$sgivennames."\" checked='checked' />&nbsp;".$sgivennames."
					 				</td>
		 						</tr>";
		}
		if(!empty($ssurname))
		{
			$to_return.= "	<tr><td class='optionbox'>
											".$pgv_lang["autosearch_ssurname"]."</td><td class='optionbox'> <input type='checkbox' name='ssurname' value=\"".$ssurname."\" checked='checked' />&nbsp;".$ssurname."
					 				</td>
		 						</tr>";
		}
		 					$to_return .= "	<tr><td class='optionbox' colspan=2 align='center'>".$pgv_lang["autosearch_plugin_name_gensearchhelp"]."</td></tr>	
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
	
	$url = "http://www.genealogy-search-help.com/genealogy-search.html?";
	
    // note: the order of the query parameters seems to be important

    // ensure that we always send fnf as the first param, then we don't have to worry about any ampersands
		$url.= "fnf=";
		if(isset($_REQUEST['fgivennames'])){
		$url.= urlencode($_REQUEST['fgivennames']);
		}
		
		if(isset($_REQUEST['fsurname'])){
		$url.= "&lnf=".urlencode($_REQUEST['fsurname']);
		}

		if(isset($_REQUEST['givenname1'])){
		$url.= "&fn=".urlencode($_REQUEST['givenname1']);
		}

   	if(isset($_REQUEST['surname'])){
		$url .= "&ln=".urlencode($_REQUEST['surname']);
    }
  
		if(isset($_REQUEST['mgivennames'])){
		$url.= "&fnm=".urlencode($_REQUEST['mgivennames']);
		}
		
		if(isset($_REQUEST['msurname'])){
		$url.= "&lnm=".urlencode($_REQUEST['msurname']);
		}			

		if(isset($_REQUEST['sgivennames'])){
		$url.= "&fns=".urlencode($_REQUEST['sgivennames']);
		}
		
		if(isset($_REQUEST['ssurname'])){
		$url.= "&lns=".urlencode($_REQUEST['ssurname']);
		}			

  	if(isset($_REQUEST['bloc'])){
  	$url.= "&bp=".urlencode($_REQUEST['bloc']);
  	}

  	if(isset($_REQUEST['dloc'])){
  	$url.= "&dp=".urlencode($_REQUEST['dloc']);
  	}
					
	
	// debug: print the $_REQUEST
	//return $ret;  
	Header("Location: ".$url);
	exit;
	
}

?>
