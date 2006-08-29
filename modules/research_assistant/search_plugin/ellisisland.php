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
		$sex = $person->getSex();
		$seximg = $person->getSexImage();
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						<input type=\"hidden\" name=\"searchtype\" value=\"ellisisland\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>		
		 						<tr>
					 				<td class='optionbox'>
					 					Include surname:</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' /> ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					Include given names:</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' /> ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					Include birth year:</td><td class ='optionbox'> <input type='checkbox' name='byear' value=\"".$byear."\" checked='checked' /> ".$byear."</td></tr>
					 						 						<tr><td class ='optionbox'>
					 					Include Gender:</td><td class ='optionbox'> <input type='checkbox' name='sex' value=\"".$sex."\" checked='checked' /> ".$sex.$seximg."</td></tr>				
								<tr><td class='optionbox' colspan=2 align='center'>Ellis Island Records Plug-In</td></tr>
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