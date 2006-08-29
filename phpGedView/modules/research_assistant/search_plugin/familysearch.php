<?php 
//Author: DParker
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
		
	$to_return ="<form name='ancsearch' action='module.php' target=\"_blank\" method='post'> 
						<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />
						<input type=\"hidden\" name=\"action\" value=\"auto_search\" />
						<input type=\"hidden\" name=\"searchtype\" value=\"familysearch\" />
						<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />
							<table width='50%'>			
		 						<tr>
					 				<td class='optionbox'>
					 					Include surname:</td><td class='optionbox'> <input type='checkbox' name='surname' value=\"".$lastname."\" checked='checked' /> ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					Include given names:</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=\"".$givennames."\" checked='checked'' /> ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					Include birth year:</td><td class ='optionbox'> <input type='radio' name='year' value=\"".$byear."\" checked='checked' /> ".$byear."</td></tr>
					 						<tr><td class='optionbox'>
". //todo: Check syntax on radio button 
"
					 					Include death year:</td><td class='optionbox'> <input type='radio' name='year' value=\"".$dyear."\"  />".$dyear."
					 				</td>
		 						</tr>
		 						<tr><td class='optionbox' colspan=2 align='center'>FamilySearch.org Plug-In</td></tr>	
							<tr><td  align='center' class='topbottombar'colspan=2><input type='submit' value='Search' /></td></tr>
							</table>						
					</form>
 <script language='JavaScript'>
 <!--
		
 				
 	function search_ancestry() {
 		ifrm = document.getElementById('ifrm');
 		frm = document.ancsearch;
 			
 				url = 'http://www.familysearch.org/Eng/search/ancestorsearchresults.asp?';

 				if (frm.surname.checked){
 					url = url + 'last_name=' + frm.surname.value; 																		
 				}
 				if (frm.surname.checked && frm.givenname1.checked){
 					url = url + '&first_name=' + frm.givenname1.value; 					
 				}
 				else alert('You must search with a last name');
 				
				if(frm.year.value == ".$byear."){
 				url = url + '&event_index=1&date_range=2&from_date=' + ".$byear.";
 				}
 				else{
 					url = url + '&event_index=3&date_range=2&from_date=' + ".$dyear."; 
 				}			
 				//if (document.all) ifrm.location = url;
				//else ifrm.src = url;
				alert(url);
				window.open(url, '');	
			
		"  /*
				url = 'http://search.ancestry.com/cgi-bin/sse.dll?';
				if (frm.surname.checked) {
					url = url + '&gsln='+ frm.surname.value;
				}
				if (frm.givenname1.checked) {
					url = url + '&gsfn=' + frm.givenname1.value; 			
				}
				if (frm.birthyear.checked) {
					url = url + '&gsby=' + frm.birthyear.value;
				}
				else
				 {
					url = url + '&gsdy='+".$dyear."
				} 	 
				// -- old iframe method 
				// if (document.all) ifrm.location = url;
				// else ifrm.src = url;
				window.open(url, '');	
			*/ 
			."		
		} 	
	}

	
 	//-->
 </script>";
		 	
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
	}
	
	// debug: print the $_REQUEST
	//return $ret;  
	Header("Location: ".$url);
	exit;
	
}

?>