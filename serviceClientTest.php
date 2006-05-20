<pre>
<?php
/***
 * Hack to change encoding style to literal
 * used when calling a java service
 *
 * @param object $wsdl SOAP_WSDL object
 * @returns object modified wsdl object
 */
function literalWSDL(&$wsdl)
{
	$namespace = array_keys($wsdl->bindings);
	$operations = array_keys($wsdl->bindings[$namespace[0]]['operations']);

	for($i = 0; $i<count($operations); $i++)
	{
		$wsdl->bindings[$namespace[0]]['operations'][$operations[$i]]['input']['use'] = 'literal';
		$wsdl->bindings[$namespace[0]]['operations'][$operations[$i]]['output']['use'] = 'literal';
	}
}
require_once('config.php');
ob_start();
require_once('includes/SOAP/Client.php');

//-- put your URL here
$url = 'http://localhost/pgv/genservice.php?wsdl';

$wsdl = new SOAP_WSDL($url);

//print_r(array_keys($wsdl->bindings));

literalWSDL(&$wsdl);
$soap = $wsdl->getProxy();

$s = $soap->ServiceInfo();
print_r($s);

$result = $soap->Authenticate('', '', 'presidents.ged', '', 'GRAMPS');
print_r($result);

//$person = $soap->getPersonByID($result->SID, "I1");
//print_r($person);
require_once('includes/GrampsExport.php');
$ge= new GrampsExport();
$ge->begin_xml();
$ge->create_family(find_family_record("F1"), "F1", 1);
//$ge->create_person(find_person_record("I1"), "I1", 1);
$xml = $ge->dom->saveXML();
print htmlentities($xml);

$family = $soap->getFamilyByID($result->SID, "F1");
print_r($family);

//$ids = $soap->checkUpdates($result->SID, "01 JAN 2006");
//print_r($ids);
//
//$s = $soap->search($result->item->SID, 'week', '0','100');
//echo print_r($s,true);
//
//$res = $soap->getPersonById($result->SID, "I1");
//print_r($res);
//
/*************************************** getVar TESTS *********************************************/
/*$s = $soap->getVar($result->SID, 'GEDCOM');
print_r($s);

$s = $soap->getXref($result->SID, 'new', 'INDI');
print_r($s);

$s = $soap->checkUpdates($result->SID, '10 JAN 2005');
print_r($s);
*/
//
//$s = $soap->getVar($result->SID, 'CHARACTER_SET');
//print_r($s);
//
//$s = $soap->getVar($result->SID, 'PEDIGREE_ROOT_ID');
//print_r($s);
//
// The rest of these are examples that only work if you are
// actually authenticated as a user first not anonymously
//$s = $soap->getVar($result->SID, 'CALENDAR_FORMAT');
//print_r($s);
//
//$s = $soap->getVar($result->SID, 'LANGUAGE');
//print_r($s);
//
/************* THE REST OF THESE SCHOULD RETURN SOAP FAULTS SINCE THEY'RE NOT ALLOWED   **********/
//$s = $soap->getVar($result->SID, 'PGV_BASE_DIRECTORY');
//print_r($s);
//
//$s = $soap->getVar($result->SID, 'PGV_DATABASE');
//print_r($s);
//
//$s = $soap->getVar($result->SID, 'DBTYPE');
//print_r($s);
//
//$s = $soap->getVar($result->SID, 'SERVER_URL');
//print_r($s);
/**************************************** END OF getVar TEST *************************************/
//
//$s = $soap->appendRecord($result->SID, 'RoyalBaseGarrett05.ged', $gedrec);
//print_r($s);
//
//$s = $soap->deleteRecord($result->SID, 'RoyalBaseGarrett05.ged');
//print_r($s);

ob_end_flush();
?>
</pre>