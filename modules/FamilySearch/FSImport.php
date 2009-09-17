<?php
require_once("config.php");
include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php");
include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php");
include_once("includes/person_class.php");

if (!userGedcomAdmin(getUserName())) {
	header('Location: login.php?url=FSImport.php');
	exit;
}

/**
 * Send the appropriate authentication headers
 */
function basicAuthentication($message = '') {
	if (empty($message)) $message = 'Unable to authenticate user.  Access denied.';
	header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo $message;
    exit;
}

$username = '';
$password = '';
$url = 'http://ref.dev.usys.org';
//-- check for authentication if a preset username and password were not provided
if ((empty($username) || empty($password)) && !isset($_SERVER['PHP_AUTH_USER'])) {
   basicAuthentication();
}
else {
	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];
}

$client = new FamilySearchProxy($url, $username, $password);
$xmlGed = new XmlGedcom();
$xmlGed->setProxy($client);

$pid = "I3";
if (!empty($_REQUEST['pid'])) $pid = $_REQUEST['pid'];

if (isset($pgv_changes[$pid."_".$GEDCOM])) $person = new Person(find_updated_record($pid));
else $person = Person::GetInstance($pid);
if (!empty($person)) {
	if (preg_match("/1 _UID /", $person->getGedcomRecord()) == 0) {
		require_once('includes/functions_import.php');
		$person->gedrec = trim($person->gedrec) . "\r\n1 _UID " . uuid();
		require_once('includes/functions_edit.php');
		replace_gedrec($person->getXref(), $person->gedrec);
	}
	$xgperson = $xmlGed->addPGVPerson($person);
	$xml = $xgperson->toXml(true);
	//print "<pre>".htmlentities($xml)."</pre>";
	$res = $client->addPerson($xml);
	print "<pre>".htmlentities($res)."</pre>";
}
?>