<?php
/**
 * Disputing and Deleting Facts in Familysearch and PGV
 *
 * FamilySearch PhpGedView Module
 * Copyright (C) 2008  Neumont University
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * See LICENSE.txt for the full license.  If you did not receive a
 * copy of the license with this code, you may find a copy online
 * at http://www.opensource.org/licenses/lgpl-license.php
 *
 * @author [Your name here]
 * @author [Your name here]
 */

require_once('modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php');
require_once('modules/FamilySearch/config.php');
require_once('modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php');
print_header("Dispute a Record");
$fsPerson = null;


if(!isset($_REQUEST['submitbutton']))// check to see if the submit has been pushed and set to true/on.
{
	//if it hasnt been pushed, do the following
	if (!isset($_SESSION['$fsPersonArray'])){// do the stuff in here if there's nothing in the person array
		// that stuff gets put in if teh person is filling out the form.
		//gets the familysearch person passed in and assignes it to $fsPerson.
		$fsPersonId = $_REQUEST['fsPerson'];

		//creates a new array for the %fsPerson to go in.
		//$fsPersonArray = array();
		//creates new proxy to connect to family search.
		$proxy = new FamilySearchProxy($FS_CONFIG['family_search_url'], $FS_CONFIG['family_search_username'], $FS_CONFIG['family_search_password']);
		$xmlGed = new XmlGedcom();
		$xmlGed->setProxy($proxy);
		$fsPerson = $xmlGed->getPerson($fsPersonId);
		//calls the parseXml function on $fsPerson and assigns it to $fsPersonArray.
		$fsPersonArray = $fsPerson->getAssertions();
		$_SESSION['fsPersonArray'] = $fsPersonArray;
		//Prints the first headings for the table that displays the familySearch person.
		print "<form id=\"fsForm\" name=\"fsForm\" method=\"post\" action=\"module.php?mod=FamilySearch&pgvaction=comparePersons\">";
		print "<table width=\"40%\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"55\" align=\"center\">Dispute</td><td width=\"744\" align=\"center\">FamilySearch Item</td></tr>";
		//for loop that prints the next item in the array in it's own row, along with a checkbox assiged he id of $i.
		for($i=0; $i< count($fsPersonArray); $i++){
			print "  <tr><td align=\"center\"><input type=\"checkbox\" name=\"events[]\" value=\"".$i."\" /></td><td align=\"left\">".
			$fsPersonArray[$i]->getId().$fsPersonArray[$i]->getAssertionType(). "</td></tr>";
		}
	}
	print "<input type=\"hidden\" name=\"hiddenfsPersonID\" value=\"".$_REQUEST['fsPerson']."\">";
	print "</table><br /><br />";


	if(isset($_REQUEST['pgvPerson']))
	{
		$i;
		//gets the pgvPerson passed in and assignes it to $pgvPerson.
		$pgvPersonId = $_REQUEST['pgvPerson'];
		$pgvPerson = Person::getInstance($pgvPersonId);
		$_SESSION['pgvPerson'] = $pgvPersonId;
		//creates a new array for the %$pgvPerson to go in.
		$pgvPersonArray = array();
		//calls the parseXml function on $$pgvPerson and assigns it to $pgvPersonArray.
		$pgvPersonArray = $pgvPerson->getIndiFacts();
		print "<table width=\"40%\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"55\" align=\"center\">Dispute</td><td width=\"744\" align=\"center\">PGV Item</td></tr>";
		for($p=0; $p< count($pgvPersonArray); $p++)
		{print "<tr><td align=\"center\"><input type=\"checkbox\" name=\"pgvevents[]\" value=\"".$p."\" /></td><td align=\"left\">
				".$pgvPersonArray[$p]->print_simple_fact(true)."</td></tr>";}
		print "<input type=\"hidden\" name=\"hiddenpgvPersonID\" value=\"".$pgvPersonId."\">";
		print "</table>";
		PRINT $pgvPersonId;
		print "<input name=\"submitbutton\" type=\"submit\" />";
		print "</form>";
	}

}
if (isset($_REQUEST['submitbutton'])){
	if(isset($_REQUEST['hiddenfsPersonID'])){
		$fsPersonId = $_REQUEST['hiddenfsPersonID'];
		print $fsPersonId;
		//creates a new array for the %fsPerson to go in.
		//$fsPersonArray = array();
		//creates new proxy to connect to family search.
		$proxy = new FamilySearchProxy($FS_CONFIG['family_search_url'], $FS_CONFIG['family_search_username'], $FS_CONFIG['family_search_password']);
		$xmlGed = new XmlGedcom();
		$xmlGed->setProxy($proxy);
		$fsPerson = $xmlGed->getPerson($fsPersonId);
		//calls the parseXml function on $fsPerson and assigns it to $fsPersonArray.
		$fsPersonArray = $fsPerson->getAssertions();
		$_SESSION['fsPersonArray'] = $fsPersonArray;
		print " -Start if statement <br /> ";
		if(isset($_REQUEST["events"])){

			foreach($_REQUEST['events'] as $index=>$b) {
				print "[".$index." ".$b."]";
				print " -out of foreach loop <br /> ";
				print $fsPersonArray[$b]->getId();
				$fsPersonArray[$b]->setDisputing("true");
				print $fsPersonArray[$b]->getId().$fsPersonArray[$b]->getAssertionType();
				print "<br /><br />";
				print htmlentities($fsPersonArray[$b]->toXml());
			}
		}
	}
		if(isset($_REQUEST["hiddenpgvPersonID"])){
			$pgvPersonId = $_REQUEST['hiddenpgvPersonID'];
			$pgvPerson = Person::getInstance($pgvPersonId);
			$_SESSION['pgvPerson'] = $pgvPersonId;
			//creates a new array for the %$pgvPerson to go in.
			$pgvPersonArray = array();
			//calls the parseXml function on $$pgvPerson and assigns it to $pgvPersonArray.
			$pgvPersonArray = $pgvPerson->getIndiFacts();
			foreach($_REQUEST['pgvevents'] as $index=>$b) {
				print "[".$index." ".$b."]";
				print " -out of foreach loop <br /> ";
				$event = $pgvPersonArray[$b];
				$newrecord = $pgvPerson->getGedcomRecord();
				$newrecord = delete_fact($event->getLineNumber(), $pgvPerson->getXref(),$newrecord);
				//-- call replace_gedrec when you are done updating the person's record
				//replace_gedrec($pgvPerson->getXref(), $newrecord);
			}
		}
//$adder->addLink(Person::getInstance($pgvPersonId), $hiddenfsPersonID);

}

?>
