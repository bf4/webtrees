<?php

require_once("config.php");
require_once("includes/person_class.php");

$person_id = "I328";

print_header("My simple Person Page");

$person = Person::getInstance($person_id);
if (is_null($person)) print "Person ".$person_id." not found";
else {
	print "Person's name is ".$person->getName()." ".$person->getXref();
	$families = $person->getChildFamilies();
//	print_r($families);
//	var_dump($families);
	foreach($families as $famid=>$family) {
		$father = $family->getHusband();
		$mother = $family->getWife();
		print " Father's name is ".$father->getName();
		print " Mother's name is ".$mother->getName();
	}
}
print "<br /><br /><br />";

print_r(DMSoundex($person->getSurname()));
print_footer();
?>