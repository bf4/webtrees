<?php
chdir("../../");
include_once("config.php");
include_once("modules/FamilySearch/FSParse/XMLGEDCOM.php");
include_once("includes/person_class.php");

$xmlGed = new XmlGedcom();
$person = Person::getInstance("I1");
$XGPerson = $xmlGed->addPGVPerson($person);
$person = Person::getInstance("I3");
$XGPerson = $xmlGed->addPGVPerson($person);

print_r($XGPerson);

print "<pre>".htmlentities($xmlGed->toXml())."</pre>";
?>