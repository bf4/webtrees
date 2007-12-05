<?php
	/*
	 * Tests for the Person class
	 */
	
	class TestOfPerson extends UnitTestCase {
		function setUp() {
	        // this to setup at the start of each test method
	        
	    }
	    function tearDown() {
	        // things to do once each test method is complete
	        
	    }
		function testCreatingNewPerson() {
			global $GEDCOM_ID_PREFIX, $GEDCOM;

			$pid = $GEDCOM_ID_PREFIX.'123456';
			$personGedcom = '0 @'.$pid.'@ INDI
1 NAME Lucy Special /ANSEL/
1 SEX F
1 BIRT
2 DATE 12 AUG 1905
1 DEAT
2 DATE 31 DEC 1990
1 FAMS @F6@
1 NOTE @N25@
1 CHAN
2 DATE 11 Jan 2001
3 TIME 16:00:23
1 RIN 2';
			$person = new Person($personGedcom);
			$this->assertEqual($person->getName(), 'Private');
			$this->assertEqual($person->getNameCount(), 1);
			$this->assertEqual($person->getSortableName(), 'Private');
			$this->assertEqual($person->getSurname(), 'Private');
			$this->assertEqual($person->getGivenNames(), 'Private');
			$this->assertEqual($person->getAddName(), '');
			$this->assertEqual($person->canDisplayDetails(), FALSE, "can display details");
			$this->assertEqual($person->canDisplayName(), FALSE, "can display name");
			$this->assertEqual($person->isDead(), TRUE, "is dead");
			$this->assertEqual($person->findHighlightedMedia(), FALSE);
			$this->assertEqual($person->getBirthRecord(), "1 BIRT\n2 DATE 12 AUG 1905");
			$this->assertEqual($person->getDeathRecord(), "1 DEAT\n2 DATE 31 DEC 1990");
			$this->assertIsA($person->getBirthEvent(), "Event");
			$this->assertIsA($person->getDeathEvent(), "Event");
			$this->assertIsA($person->getBirthDate(), "GedcomDate");
			$this->assertEqual($person->getGedcomBirthDate(), "2 DATE 12 AUG 1905");
			$this->assertEqual($person->getBirthPlace(), NULL);
			$this->assertEqual($person->getBirthYear(), 1905);
			$this->assertIsA($person->getDeathDate(), "GedcomDate");
			$this->assertEqual($person->getGedcomDeathDate(), "2 DATE 31 DEC 1990");
			$this->assertEqual($person->getDeathPlace(), NULL);
			$this->assertEqual($person->getDeathYear(), 1990);
			$this->assertEqual($person->getSex(), "F");
			$this->assertEqual($person->getSexImage(), '<img src="images/small/female.gif" alt="" class="gender_image" />');
			$this->assertEqual($person->setLabel('some label'), NULL);
			$this->assertEqual($person->getLabel(), 'some label');
			$this->assertEqual(sizeof($person->getSpouseFamilyIds()), 1);
			//$this->assertEqual($person->getSpouseFamilies(), NULL);
			//$this->assertEqual($person->getCurrentSpouse(), NULL);
			//$this->assertEqual($person->getNumberOfChildren(), "F");
			$this->assertEqual(sizeof($person->getChildFamilyIds()), 0);
			$this->assertEqual($person->getChildFamilies(), NULL);
			$this->assertEqual($person->getPrimaryChildFamily(), NULL);
			//$this->assertEqual($person->getChildFamilyPedigree(), NULL);
			$this->assertEqual(sizeof($person->getStepFamilies()), 0);
			$this->assertEqual(sizeof($person->getGlobalFacts()), 0);
			$this->assertEqual(sizeof($person->getIndiFacts()), 0);
			$this->assertEqual(sizeof($person->getOtherFacts()), 0);
			//$this->assertEqual($person->getChildFamilyLabel(), "Family with Parents");
			//$this->assertEqual($person->getStepFamilyLabel(), "Unknown Family");
			//$this->assertEqual($person->getSpouseFamilyLabel(), "Family with unknown");
			$this->assertEqual($person->getUpdatedPerson(), NULL);
			$this->assertEqual($person->parseFacts(), NULL);
			$this->assertEqual($person->add_family_facts(), NULL);
			//$this->assertEqual($person->add_parents_facts(), NULL);
			//$this->assertEqual($person->add_children_facts(), "F");
			//$this->assertEqual($person->add_spouse_facts(), NULL);
			//$this->assertEqual($person->add_stepsiblings_facts(), NULL);
			$this->assertEqual($person->add_historical_facts(), NULL);
			//$this->assertEqual($person->add_asso_facts(), "F");
			//$this->assertEqual($person->diffMerge(), NULL);
			$this->assertEqual($person->getLinkUrl(), 'individual.php?pid='.$pid.'&amp;ged='.$GEDCOM);
			//$this->assertEqual($person->getPrimaryParentsNames(), "F");
			
			
		}
	}
?>