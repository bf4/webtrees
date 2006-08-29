<?php 
//@Author DParker
require_once("includes/person_class.php");

function autosearch_options()
{
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
					 					Include surname:</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' /> ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					Include given names:</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' /> ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					Include birth year:</td><td class ='optionbox'> <input type='checkbox' name='byear' value=\"".$byear."\" checked='checked' /> ".$byear."</td></tr>
					 						
";
if(isset($bloc)){	
	$to_return.="<tr><td class='optionbox'>	Include birth location:</td><td class='optionbox'> <input type='checkbox' name='bloc' value=\"".$bloc."\"  />".$bloc."
					 				</td>
		 						</tr>";
	}
	$to_return .=  				"<tr><td class='optionbox'>
					 					Include death year:</td><td class='optionbox'> <input type='checkbox' name='dyear' value=\"".$dyear."\"  />".$dyear."
					 				</td>
		 						</tr>
								
";					 				
	if(isset($dloc)){
	$to_return.="<tr><td class='optionbox'>Include death location:</td><td class='optionbox'> <input type='checkbox' name='dloc' value=\"".$dloc."\"  />".$dloc."</td></tr>	";	
	}

	
		$to_return.=		"	<tr><td class='optionbox' colspan=2 align='center'>Genealogy.com Plug-In</td></tr>
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