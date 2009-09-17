<?php
include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php");
include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php");
include_once("includes/person_class.php");
require_once("modules/FamilySearch/config.php");

if (!userGedcomAdmin(getUserName())) {
	header('Location: index.php');
	exit;
}

$pid="";
if (isset($_REQUEST['pid'])) $pid = $_REQUEST['pid'];

print_simple_header("Delete Person From FamilySearch");

if (empty($pid)) {
	print "You must specify a pid";
}
else {
	$client = new FamilySearchProxy($FS_CONFIG['family_search_url'], $FS_CONFIG['family_search_username'], $FS_CONFIG['family_search_password']);
	$xmlGed = new XmlGedcom();
	$xmlGed->setProxy($client);
	
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
<familytree version="1.0" xmlns="http://api.familysearch.org/familytree/v1"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://api.familysearch.org/familytree/v1/schema">
<persons>';
	$xml .= '<person id="'.$pid.'" fsaction:action="delete"
xmlns:fsaction="http://api.familysearch.org/familytree/v1/action" />';
	$xml .= '</persons></familytree>';
	//print "<pre>".htmlentities($xml)."</pre>";
	$res = $client->updatePerson($pid, $xml);
	print "<pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";
}

$person = "";
$note = "";
$source = "";
$LinkToRecordID = "";
$LinkType = "";

function removeLink(){
	
}

function removeNotes(){
	
}

function removeMedia(){
	
}

function removeSources(){
	
}

function removeSpouse(){
	
}

print_simple_footer();
?>