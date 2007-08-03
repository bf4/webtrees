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
 */


require_once("includes/person_class.php");


function autosearch_options()
{
	//import the pgv lang for multi langual support
	global $pgv_lang;
	global $countries, $lang_short_cut, $LANGUAGE;
	
	if (file_exists("languages/countries.".$lang_short_cut[$LANGUAGE].".php")) require("languages/countries.".$lang_short_cut[$LANGUAGE].".php");

	asort($countries);
	$pid = "";
	//get your person object 
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
		if (!is_object($person)) return "";
	//set values
	
		$lastname = $person->getSurname();

		$bplace = $person->getBirthPlace();
		$bArray = preg_split('/, ?/', $bplace);
		//foreach($bArray as $bword=>$bplace) break;
		//$bplc = $bplace;
		$bplc = end($bArray);
		
		//$dplace = $person->getDeathPlace();
		//foreach($dplace->split(',') as $dword=>$dplace) break;
		//$dplc = $dplace;
		
		
		$bplcCode = "";
		
		//If the last entry in the PLAC string is a real country this figures it out for us
		foreach($countries as $key=>$value)
		{
			if($value == $bplc)
			{
				$bplcCode = $key;
			}
		}
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
                    	<input type=\"hidden\" name=\"searchtype\" value=\"geneanet\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>		"; 
		// If you want to use logic to show which fields you want to show you can insert it here
		$to_return .="		<tr>
					 				<td class='optionbox'>
					 					".$pgv_lang["autosearch_surname"]."</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' />&nbsp; ".$lastname."</td></tr>
		 							
									
									<td class='optionbox'>
					 					".$pgv_lang["autosearch_country"]."</td>
									<td class='optionbox'>
									<SELECT NAME='country' onChange=\"\"> ";
					if(!empty($bplcCode))
					{
						$to_return.="<OPTION value=\"".$bplcCode."\">".$countries[$bplcCode]."</OPTION>";
						$to_return.="<OPTION value=\"\"></OPTION>";
					}
					else
					{
						$to_return.="<OPTION value=\"\"></OPTION>";
					}
						
						
						foreach($countries as $key=>$value)
						{
							$to_return.="<OPTION value=\"".$key."\">".$value."</OPTION>";
						}
						
						//TODO: write in logic to make first combo option the current birth country. 
						// do the same for death and generate the dropdown list for all the countries.			
								
        $to_return.="	</select></td></tr>

							
							<tr><td class='optionbox' colspan=2 align='center'>".$pgv_lang["autosearch_plugin_name_genNet"]."</td></tr>
							<tr><td  align='center' class='topbottombar'colspan=2><input type='submit' value='".$pgv_lang["autosearch_search"]."' /></td></tr>
		 			
							</table>
						
					</form>";
		 	
		return $to_return;
}

function autosearch_process() {
	//debug line to print the request array
	//$ret = print_r($_REQUEST, true);
	
	//grab the person
	$pid = "";
	if (!empty($_REQUEST['pid'])) $pid = clean_input($_REQUEST['pid']);
	$person = Person::getInstance($pid);
		if (!is_object($person)) return "";
		
	
	//url of our search site
	$url = "http://search.geneanet.org/result.php3?";
	
	//we pull our values for each of checked options above and add them to the search string
	if(isset($_REQUEST['surname'])){
		$url .= "&name=".urlencode($_REQUEST['surname']);
	}
	else
	{
		$url .= "&name=NULL";
	}
	if(isset($_REQUEST['country'])){
		$url.= "&country=".urlencode($_REQUEST['country']);
	}
	
	
	//add any hidden fields here  $url .= &blah=1 etc
	
	// debug: print the $_REQUEST
	//return $ret;  
	
	//redirect user to the new site
	Header("Location: ".$url);
	exit;
	
}
?>