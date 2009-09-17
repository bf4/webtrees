<?php
/**
 * This is the file that contains some basic functions for adding people to Family Search and linking them up.
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
 * @author Jarrett Coggin
 */

include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php");
include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php");
include_once("includes/classes/class_person.php");
require_once("modules/FamilySearch/config.php");
include_once("modules/FamilySearch/RA_AutoMatch.php");

class FSAdd{
	
	/**
	 * Don't have to worry about checking to see if the record already exists in FamilySearch, this method does that as well.
	 *
	 * @param Person $person
	 */
	function addPerson(&$person){
	/*@var $person Person*/
		//if (checkForExistingFSRecord($person)){
		global $FS_CONFIG;
		/*@var $person Person */
		//-- create a connection to FamilySearch
		$client = new FamilySearchProxy($FS_CONFIG['family_search_url'], $FS_CONFIG['family_search_username'], $FS_CONFIG['family_search_password'], $FS_CONFIG['family_search_key']);
		//-- create an XMLGEDCOM object that can be used to convert to XML
		$xmlGed = new XmlGedcom();
		$xmlGed->setProxy($client);
		//-- Check to make sure the person has a UID
		/*-- this doesn't make a difference at the moment, may use it in the future
		 if (preg_match("/1 _UID /", $person->getGedcomRecord()) == 0) {
		 require_once('includes/functions_import.php');
		 $person->gedrec = trim($person->gedrec) . "\r\n1 _UID " . uuid();
		 require_once('includes/functions_edit.php');
		 replace_gedrec($person->getXref(), $person->gedrec);
		 }*/

		//-- convert the PGV person to an XG_Person
		$xgperson = $xmlGed->addPGVPerson($person);
		//-- Start the XML
		$xml = '<?xml version="1.0" encoding="utf-8"?>
<familytree version="1.0" xmlns="http://api.familysearch.org/familytree/v1"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://api.familysearch.org/familytree/v1/schema">
<persons>';
		//-- Get the XML from the XG_Person object
		$xml .= $xgperson->toXml(true);
		$xml .= '</persons></familytree>';
		
		//-- print for debugging
		//print "<b>Adding Person</b><br /><pre>".htmlentities($xml)."</pre>";

		//-- print the response for debugging
		//print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";
		
		$res = "";
		//-- send the XML to familysearch
		$res = $client->addPerson($xml);
		print "<b><i><font color='green' size='4'>".$person->getName()." Added</font></i></b><br /><br />";
		
		//-- try to get the new familysearch id from the response
		$ct = preg_match("/<person id=\"(.+)\"/", $res, $match);		
		if ($ct>0) {		
			$fsid = $match[1];
			addLink($person,$fsid);
			$xgperson = $xmlGed->getPerson($fsid);
			return $fsid;
		}
		return false;
	}

	/**
	 * Adds a link to the person between our PGV person to the same person in FamilySearch.
	 *
	 * @param Person $person
	 * @param String $fsid
	 */
	function addLink(&$person, $fsid){
	//-- add the RFN linkage
		$ret = false;
		include_once('includes/controllers/remotelink_ctrl.php');
		$controller = new RemoteLinkController();
		global $FS_CONFIG;
		$serverID = $controller->addFamilySearchServer("Family Search", $FS_CONFIG['family_search_url'], '');
		if (!empty($serverID)) {
			//-- add the ID to the person
			$gedrec = trim($person->getGedcomRecord());
			$gedrec .= "\r\n1 RFN ".$serverID.":".$fsid;
			$gedrec .= "\r\n1 REFN ".$fsid."\r\n2 TYPE FamilySearch";
			replace_gedrec($person->getXref(), $gedrec);
			$ret = true;
		}
		return $ret;
	}

	/**
	 * The $relationship variable is of the string type and will only take one of three values:
	 * 		- "spouse"
	 * 		- "parent"
	 * 		- "child"
	 * The value states of what relation the $primaryperson variable is to the $secondaryperson variable.
	 * A $primaryperson of type "spouse" would be a husband or wife of the $secondaryperson variable, "parent" would be a parent of the $secondaryperson, and "child" would be a child of the $secondaryperson.
	 *
	 * @param Person $primaryperson
	 * @param Person $secondaryperson
	 * @param string $relationship
	 * @param string $date
	 */
	function addRelationship($primaryperson, $secondaryperson, $relationship, $date=""){
		$xg = new XmlGedcom();
		$xgperson = $xg->addPGVPerson($primaryperson);
		$xgsecondaryperson = $xg->addPGVPerson($secondaryperson);
		switch ($relationship){
			case "spouse":
						$fact = new XG_Fact();
		$fact->setType("spouse-spouse");
		$spouse = new XG_PersonRef();
		$spouse->setRef($xgsecondaryperson->getID());
		$spouse->setRole("spouse");
		$fact->setSpouse($spouse);
		$xgperson->addSpouse($spouse);
		$xgperson->addAssertion($fact);
		$xgperson->addSpouse($xgsecondaryperson);
				break;
			case "child":
						$fact = new XG_Fact();
		$fact->setType("parent-child");
		$parent = new XG_PersonRef();
		$parent->setRef($xgsecondaryperson->getID());
		$parent->setRole("parent");
		$fact->setParent($parent); 
		$xgperson->addParent($parent);
		$xgperson->addAssertion($fact);
		$xgperson->addParent($xgsecondaryperson);
				break;
			case "parent":
						$fact = new XG_Fact();
		$fact->setType("parent-child");
		$child = new XG_PersonRef();
		$child->setRef($xgsecondaryperson->getid());
		$child->setRole("child");
		$fact->setChild($child); 
		$xgperson->addChild($child);
		$xgperson->addAssertion($fact);
		$xgperson->addChild($xgsecondaryperson);
				break;
		}
		//-- Start the XML
		$xmlOfPerson = '<?xml version="1.0" encoding="utf-8"?>
<familytree version="1.0" xmlns="http://api.familysearch.org/familytree/v1"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://api.familysearch.org/familytree/v1/schema">
<persons><person version="'.$xgperson->getVersion().'" id="'.$xgperson->getID().'"><assertions>';
		$xmlOfPerson .= $fact->toXml();
		$xmlOfPerson .='</assertions></person></persons></familytree>';
		
	}
	
	function getCloseFamily($person){	
		global $TEXT_DIRECTION;
		/*@var $person Person*/
		?><table class="pedigree_table <?php print $TEXT_DIRECTION; ?>" align='center'><tr class="topbottombar" style="text-align:center; "><td>Name</td><td>Relation</td><td>Add?</td></tr><?php
		$closefamily[] = array();
		$families = $person->getChildFamilies();
		/*@var $family Family*/
		foreach($families as $famid=>$family) {
			$father = $family->getHusband();
			if(!is_null($father)){
			$closefamily[] = $father;
			?><tr><td class="descriptionbox wrap"><?php print $father->getName();?></td><td class="optionbox">Father</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$father);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print '1 match'; }
										else print $i.' matches'; 
									}
								else print '<input type="checkbox" value="Father" name="father[]">'
								?></td></tr><?php
			}
			$mother = $family->getWife();
			if(!is_null($mother)){
			$closefamily[] = $mother;
			?><tr><td class="descriptionbox wrap"><?php print $mother->getName();?></td><td class="optionbox">Mother</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$mother);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print "1 match"; }
										else print $i." matches"; 
									}
								else print '<input type="checkbox" value="Mother" name="mother[]">'
								?></td></tr><?php
			}
			$spouse = $person->getCurrentSpouse();	
			if(!is_null($spouse)){		
				if($spouse->getSex() == 'M'){
					?><tr><td class="descriptionbox wrap"><?php  print $spouse->getName();?></td><td class="optionbox">Husband</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$spouse);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print "1 match"; }
										else print $i." matches"; 
									}
								else print '<input type="checkbox" value="Spouse" name="spouse[]">'
								?></td></tr><?php
				}
				else ?><tr><td class="descriptionbox wrap"><?php  print $spouse->getName();?></td><td class="optionbox">Wife</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$spouse);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print "1 match"; }
										else print $i." matches"; 
									}
								else print '<input type="checkbox" value="Spouse" name="spouse[]">'
								?></td></tr><?php
			}
			$siblings = $family->getChildren();
			foreach ($siblings as $sib){
				if (!is_null($sib)){
					/*@var $sib Person*/ 
					if($sib->getXref() != $person->getXref()){
					$closefamily[] = $sib;
					switch($sib->getSex())
					{
						case 'M':
							?><tr><td class="descriptionbox wrap"><?php print $sib->getName();?></td><td class="optionbox">Brother</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$sib);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print "1 match"; }
										else print $i." matches"; 
									}
								else print '<input type="checkbox" value="Sibling" name="sibling[]">'
								?></td></tr><?php
							break;
						case 'F':
							?><tr><td class="descriptionbox wrap"><?php print $sib->getName();?></td><td class="optionbox">Sister</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$sib);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print "1 match"; }
										else print $i." matches"; 
									}
								else print '<input type="checkbox" value="Sibling" name="sibling[]">'
								?></td></tr><?php
							break;
						case 'U':
							?><tr><td class="descriptionbox wrap"><?php print $sib->getName();?></td><td class="optionbox">Sibling</td><td class="optionbox"><?php
								$matcher = new RA_AutoMatch();
								$people = $matcher->get_FS_results(&$sib);
								$i = 0;
								foreach($people as $p){ $i++; }
								if ($i > 0)
									{
										if ($i == 1){ print "1 match"; }
										else print $i." matches"; 
									}
								else print '<input type="checkbox" value="Sibling" name="sibling[]">'
								?></td></tr><?php
							break;
					}
				}}
			}
		}
		$forthechildren = $person->getSpouseFamilies();
		/*@var $cfamily Family*/
		foreach($forthechildren as $famid=>$cfamily){
			$children = $cfamily->getChildren();
			foreach($children as $child){
				/*@var $child Person*/
				if(!is_null($child)){
					?><tr><td class="descriptionbox wrap">
					<a href="individual.php?pid=<?php print $child->getXref(); ?>"><?php print $child->getName();?></a><br />
					<?php $birt = $child->getBirthEvent(); if ($birt!=null) $birt->print_simple_fact(); ?>
					<?php $birt = $child->getDeathEvent(); if ($birt!=null) $birt->print_simple_fact(); ?>
					</td>
					<td class="optionbox">Child</td><td class="optionbox">
						<input type="checkbox" value="Child" name="child[]">
					</td></tr><?php
				}
			}
		}
		?><tr align="center"><td class="topbottombar" colspan="3"><input type="button" value="Submit"></td></tr></table><?php
	}
	
	function checkForExistingFSRecord($person){
		$matcher = new RA_AutoMatch();
		return $matcher->get_FS_results($person);
	}
	
	/** Methods that haven't been implemented yet.
	function addSource($person, $source){
		//don't worry about this. FS doesn't have this fully hooked up.
	}

	function addMedia(){
		//don't worry about this. FS doesn't have this fully  hooked up.
	}*/
}
?>
