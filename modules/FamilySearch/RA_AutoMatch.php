<?php
if (file_exists('modules/FamilySearch/config.php')) include_once('modules/FamilySearch/config.php');
require_once("modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php");
require_once("modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php");
require_once('includes/functions/functions_edit.php');

class RA_AutoMatch {
	var $match = true;
	var $proxy = null;
	var $XMLGed = null;
	var $serverId = null;

	var $xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><familytree xmlns=\"http://api.familysearch.org/familytree/v2\">";
	var $xmlFooter = "</familytree>";

	function &getProxy() {
		global $FS_CONFIG;
		if ($this->proxy==null) {
			$this->proxy = new FamilySearchProxy($FS_CONFIG['family_search_url'], '', '', $FS_CONFIG['family_search_key']);
			$this->proxy->setAgent("PhpGedView-JohnFinlay");
		}
		return $this->proxy;
	}

	/**
	 * 
	 * @return XmlGedcom
	 */
	function &getXMLGed() {
		if ($this->XMLGed==null) {
			$this->XMLGed = new XmlGedcom();
			$this->XMLGed->setProxy($this->getProxy());
		}
		return $this->XMLGed;
	}

	function &getServerId() {
		if ($this->serverId==null) {
			global $FS_CONFIG;
			$this->serverId = $this->addFamilySearchServer("FamilySearch", $FS_CONFIG['family_search_url']);
		}
		return $this->serverId;
	}


	function authenticate($username, $password) {
		global $FS_CONFIG;
		$this->proxy = new FamilySearchProxy($FS_CONFIG['family_search_url'], $username, $password, $FS_CONFIG['family_search_key']);
		$response = $this->proxy->authenticate();
		//		print htmlentities($response);
		//		print $this->proxy->sessionid;
		//		print $_SESSION['phpfsapi_sessionid'];
	}

	/**
	 * Get results from family search
	 *
	 * @param Person $person
	 * @return array
	 */
	function get_FS_results(&$person){
		global $FS_CONFIG;

		$people = array();
		$query = XmlGedcom::buildSearchQuery($person);
		if (empty($query)) return $people;
		//searches family search api(xml) for most likely matches to an individual
		$this->getProxy();
		$results = $this->proxy->matchByQuery($query);
		$this->getXMLGed()->setProxy($this->proxy);
		//		print $query;
		//				print htmlentities($results);
		$this->XMLGed->parseXml($results);

		$people = $this->XMLGed->getMatches();

		//-- no matches fall back to search
		if (count($people)==0 && empty($this->XMLGed->error)) {
			$results = $this->proxy->searchByQuery('familytree/v2/search?'.$query);
			$this->getXMLGed()->parseXml($results);
			$people = $this->XMLGed->getMatches();
			//			print_r($people);
			$this->match = false;
		}
			
		return $people;
	}

	/**
	 * Generates the output to display a table of the matches that were found
	 *
	 * @param Person $person
	 * @return string
	 */
	function generateResultsTable(&$person) {
		global $factarray, $pgv_lang;

		$lineCount = 1;
		$count=0;
		$people_array = $this->get_FS_results($person);

		$out = '<script type="text/javascript">
		<!--
			var selectedCounter = 0;
			function mergeboxChecked(chkbx) {
				if (chkbx.checked) {
					selectedCounter++;
					jQuery("#mergebutton").attr("disabled","");
				}
				else {
					selectedCounter--;
					if (selectedCounter<1)
						jQuery("#mergebutton").attr("disabled","disabled");
				}
				return true; 
			}
		//-->
		</script>';
		$out .= "<form action=\"module.php\" method=\"post\">
			<input type=\"hidden\" name=\"mod\" value=\"FamilySearch\" />
			<input type=\"hidden\" name=\"pid\" value=\"".$person->getXref()."\" />
			<input type=\"hidden\" name=\"pgvaction\" value=\"FS_MergePerson\" /><br/><br/>
			<table align=\"center\" border=\"0\">
				<tr>
					<td align=\"center\" class=\"topbottombar\" valign=\"top\" colspan=\"7\">".print_help_link("family_search", "qm", '', false, true)."<b>FamilySearch Matches</b></td>";

		if(count($people_array)>0){
			$out .= "<tr>
						<td class=\"topbottombar\" align=\"center\" valign=\"middle\">-</td>
						<td class=\"topbottombar\" align=\"center\" valign=\"middle\">Individual</td>
						<td class=\"topbottombar\" align=\"center\" valign=\"middle\">Spouse</td>
						<td class=\"topbottombar\" align=\"center\" valign=\"middle\">Children</td>
						<td class=\"topbottombar\" align=\"center\" valign=\"middle\">Parents</td>
						<td class=\"topbottombar\" align=\"center\" valign=\"middle\">Relevance</td>
						<td class=\"topbottombar\" align=\"center\" valigh=\"middle\">Merge</td>
					</tr>";
			$relatedFamilyCount=0;

			foreach($people_array as $match) {
				if ($match->getScore() > 1)
				{
					$relatedFamilyCount++;

					$fsperson = $match->getPerson();
					$out .= "<tr class=\"optionbox\">
									<td align=\"center\" valign=\"middle\">".$lineCount."</td>
									<td>";
					$out .= $this->getPersonDetails($fsperson);
					$out .= "</td>";
					$out .= "<td>";
					$spouses = $match->getSpouses();
					foreach($spouses as $spouse)
					{
						$out .= $this->getPersonSummary($spouse)."<br />\n";
					}
					$out .= "</td>";
					$out .= "<td>";
					$children = $match->getChildren();
					foreach($children as $child)
					{
						$out .= $this->getPersonSummary($child)."<br />\n";
					}
					$out .= "</td>";
					$out .= "<td>";
					$parents = $match->getParents();
					foreach($parents as $parent)
					{
						$out .= $this->getPersonSummary($parent)."<br />\n";
					}
					$out .= "</td>";
					$out .= "<td align=\"center\" valign=\"middle\">".$this->getStars($match->getScore())."</td>";
					$temp=$match->getId();
					$out .="<td align=\"center\" valign=\"middle\"><input type=\"checkbox\" class=\"mergebox\" onclick=\"return mergeboxChecked(this);\" name=\"merge[]\" value=\"".$temp."\" /></td> ";
					$out .= "</tr>";
				}
				$count++;
			}
			$out .= "<tr class=\"optionbox\"><td align=\"center\" colspan=\"7\">";
			if ($relatedFamilyCount>0) $out .= "<input id=\"mergebutton\" type=\"submit\" name=\"Merge\" value=\"Merge Selected Records\" disabled=\"disabled\">";
		}
		if (!$this->match || count($people_array)==0 || $relatedFamilyCount==0){
			$out .= "<tr class=\"optionbox\"><td align=\"center\" colspan=\"7\">";
			if (count($people_array)==0 || $relatedFamilyCount==0) $out .= "There are no FamilySearch results<br /><br />";
			if (userGedcomAdmin(getUserName())) {
				$out .= "<a href=\"module.php?mod=FamilySearch&amp;pgvaction=FS_AddPerson&amp;pid=".$person->getXref()."\">Add this person to FamilySearch</a>";
			}
			$out .= "</td></tr>";
		}
		$out .=	"<tr>
								<td colspan=\"7\" align=\"center\" class=\"topbottombar\"></td>
							</tr>
							</table>
					</td></tr></table></form>";
		return $out;
	}

	function getFSID(&$person) {
		$fsid = '';
		$refns = $person->getAllFactsByType("REFN");
		foreach($refns as $refn) {
			if ($refn->getType()=='FamilySearch') $fsid = $refn->getDetail();
		}
		return $fsid;
	}

	function getLinkedPerson(&$person) {
		$out = '';
		$fsid = $this->getFSID($person);
		if ($fsid) {
			$xgperson = $this->getXG_Person($fsid);
			if ($xgperson) {
				$out .= "<form action=\"module.php\" method=\"post\">
				<input type=\"hidden\" name=\"mod\" value=\"FamilySearch\" />
				<input type=\"hidden\" name=\"pid\" value=\"".$person->getXref()."\" />
				<input type=\"hidden\" name=\"pgvaction\" value=\"FS_MergePerson\" /><br/><br/>
				<table align=\"center\" border=\"0\">
					<tr>
					<td align=\"center\" class=\"topbottombar\" valign=\"top\" colspan=\"2\"><b>FamilySearch Link</b></td>
					</tr>";
				$out .= "<tr>";
				$out .= "<td class=\"optionbox\">".$this->getPersonDetails($xgperson)."</td>";
				$out .="<td align=\"center\" valign=\"middle\" class=\"optionbox\"><input type=\"hidden\" name=\"merge[]\" value=\"".$xgperson->getID()."\" /><input type=\"submit\" value=\"Merge\" /></td>";
				$out .= "</tr>";
			$out .= "</table></form>";
			}
		}
		return $out;
	}

	function isLoggedIn() {
		if (!empty($_SESSION['phpfsapi_sessionid'])) return true;
		return false;
	}

	function getPersonDetails(&$fsperson) {
		global $factarray;
		if (!$fsperson) debug_print_backtrace();
		$sex = $fsperson->getGender()->getGender();
		$birth = $fsperson->getBirthAssertion();
		$death = $fsperson->getDeathAssertion();
		$out = "<a href=\"javascript:;\" onclick=\"window.open('module.php?mod=FamilySearch&amp;pgvaction=FSView&amp;pid=".$this->getServerId().":I".$fsperson->getID()."', '_blank', 'top=50,left=50,width=650,height=450,scrollbars=1,resizable=1'); return false;\">".
		Person::sexImage($sex{0}).$fsperson->getPrimaryName()->getFullText()." (".$fsperson->getID().")</a><br/>";
		$out .= "<i>".$factarray['BIRT'].": ";
		if ($birth) {
			if ($birth->getDate()) $out .= $birth->getDate()->getOriginal();
			if ($birth->getPlace()) $out .= $birth->getPlace()->getOriginal();
		}
		$out .= "<br/>";
		$out .= $factarray['DEAT'].": ";
		if ($death) {
			if ($death->getDate()) $out .= " ".$death->getDate()->getOriginal();
			if ($death->getPlace()) $out .= " ".$death->getPlace()->getOriginal();
		}
		$out .= "</i>";
		return $out;
	}

	function getPersonSummary(&$person) {
		$ssex = $person->getGender()->getGender();
		$out = "<a href=\"javascript:;\"
				onclick=\"window.open('module.php?mod=FamilySearch&amp;pgvaction=FSView&amp;pid=".$this->getServerId().":I".$person->getRef()."', '_blank', 'top=50,left=50,width=650,height=450,scrollbars=1,resizable=1'); return false;\">".
		Person::sexImage($ssex{0}).$person->getPrimaryName()->getFullText();

		$byear = $person->getMinBirthYear();
		$dyear = $person->getMaxDeathYear();
		if (empty($byear)) {
			$event = $person->getBirthAssertion();
			if ($event) {
				if ($event->getDate()) {
					$bdate = new GedcomDate($event->getDate()->getOriginal());
					$byear = $bdate->getYear();
				}
			}
		}
		if (empty($dyear)) {
			$event = $person->getDeathAssertion();
			if ($event) {
				if ($event->getDate()) {
					$ddate = new GedcomDate($event->getDate()->getOriginal());
					$dyear = $ddate->getYear();
				}
			}
		}
		if (!empty($byear) || !empty($dyear)) {
			$out .= " (";
			if (!empty($byear)) $out .= $byear;
			$out.="-";
			if (!empty($dyear)) $out .= $dyear;
			$out .=")";
		}
		$out .= "</a>";
		return $out;
	}

	function getStars($score) {
		$out = "";
		for ($i=1; $i<$score; $i++) {
			$out .= '<img src="modules/FamilySearch/star.png" border="0" align="left" alt="'.$score.'" />';
		}
		return $out;
	}

	function combine($matches) {
		$proxy = $this->getProxy();
		$version_xml = $proxy->getPersonById(implode(",",$matches),'view=version');
		$xmlGed = $this->getXMLGed();
		$xmlGed->parseXml($version_xml);

		$xmlRecord=$this->xmlHeader."<persons><person tempId=\"A\"><personas>";

		// Loops through the people objects and builds the individual records as required by family search
		foreach($matches as $match)
		{
			$value = $xmlGed->getPerson($match);
			// <person ref="123456789" version="XXXXXXXXXXX"/> this is the format used to specify which records should be merged
			$xmlRecord.="<persona id=\"".$value->getID()."\" version=\"".$value->getVersion()."\"/>";
			$fsNumber=$value->getID();
		}

		$xmlRecord.="</personas></person></persons>"; // Closes out the XML record
		$xmlRecord.=$this->xmlFooter;
		echo htmlentities($xmlRecord)."<p />"; 
		/**
		 * Performs the actual merge operation and saves the results of the merge
		 * so we can display if it occured or not.
		 */
		$results=$proxy->mergePerson($xmlRecord);
		$xmlGed->parseXml($results);
		$persons = $xmlGed->getPersons();
		if (isset($persons["A"])) {
			$person = $persons["A"];
			return $person->getID();
		}

		//	parse($results, $pid);
		//echo "Results: <p/>".htmlentities($results)."<p />"; // Placed here to aid in parsing the response XML

		return false;
	}

	function &getXG_Person($id, $summary=true) {
		$proxy = $this->getProxy();
		$xmlged = $this->getXMLGed();
		if ($summary) return $xmlged->getPerson($id);
		else return $xmlged->getPerson($id, 'all');
	}

	function &getPGVPerson($id, $summary=true) {
		$xgperson = $this->getXG_Person($id, $summary);
		$person = null;
		if ($xgperson) {
			$indirec = $xgperson->getIndiGedcom();
//	print "<pre>".$indirec."</pre>";
			$person = new Person($indirec);
		}
		return $person;
	}

	// Add a familySearch server
	//
	// @param string $title
	// @param string $url
	// @param string $gedcom_id
	// @return mixed the serverID of the server to link to
	function addFamilySearchServer($title, $url) {
		if ($this->serverId!=null) return $this->serverId;
		$this->serverId = $this->checkExistingServer($url);
		if (!$this->serverId) {
			$gedcom_string = "0 @new@ SOUR\n";
			$gedcom_string.= "1 URL ".$url."\n";
			$gedcom_string.= "2 TYPE FamilySearch\n";
			$gedcom_string.= "1 _DBID\n";
			require_once("modules/FamilySearch/FamilySearch_ServiceClient.php");
			$service = new FamilySearch_ServiceClient($gedcom_string);
			$sid = $service->authenticate();
			if (PEAR::isError($sid)) {
				$sid = '';
			}
			if (empty($sid)) {
				echo "<span class=\"error\">failed to authenticate to remote site</span>";
			} else {
				if (empty($title)) {
					$title = $service->getServiceTitle();
				}
				$title = $service->getServiceTitle();
				$gedcom_string.= "1 TITL ".$title."\n";
				$this->serverId = append_gedrec($gedcom_string);
			}
		}
		return $this->serverId;
	}

	// check if the server already exists
	//
	// @param string $url
	// @param string $gedcom_id
	// @return mixed the id of the server to link to or false if it does not exist
	function checkExistingServer($url, $gedcom_id='') {
		global $pgv_changes;
		//-- get rid of the protocol
		$turl = preg_replace("~^\w+://~", "", $url);
		//-- check the existing server list
		foreach (get_server_list() as $id=>$server) {
			if (stristr($server['url'], $turl)) {
				if (empty($gedcom_id) || preg_match("/\n1 _DBID {$gedcom_id}/", $server['gedcom']))
				return $id;
			}
		}

		//-- check for recent additions
		foreach ($pgv_changes as $cid=>$changes) {
			$change = $changes[count($changes) - 1];
			if ($change['type']!='delete' && $change["gedcom"]==PGV_GEDCOM) {
				$gid = $change["gid"];
				$indirec = $change["undo"];
				$surl = get_gedcom_value("URL", 1, $indirec);
				if (!empty($surl) && stristr($surl, $turl)) {
					if (preg_match('/^0 @('.PGV_REGEX_XREF.')@ *('.PGV_REGEX_TAG.')/', $indirec, $match)) {
						$id = $match[1];
						$type=$match[2];
						if ($type=="SOUR") {
							if (empty($gedcom_id) || preg_match("/\n1 _DBID {$gedcom_id}/", $indirec))
							return $id;
						}
					}
				}
			}
		}
		return false;
	}

	/**
	 *
	 * @param Person $person
	 */
	function addPerson(&$person){
		/*@var $person Person*/
		//-- convert the PGV person to an XG_Person
		$xgperson = $this->getXMLGed()->addPGVPerson($person);
		//-- Start the XML
		$xml = $this->xmlHeader.'<persons>';
		//-- Get the XML from the XG_Person object
		$xml .= $xgperson->toXml(true);
		$xml .= '</persons>';
		$xml .= $this->xmlFooter;

		//-- print for debugging
		print "<b>Adding Person</b><br /><pre>".htmlentities($xml)."</pre>";

		//-- send the XML to familysearch
		$res = $this->getProxy()->addPerson($xml);
		//-- print the response for debugging
		print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";

		//-- try to get the new familysearch id from the response
		$ct = preg_match("/<person.*id=\"(.+)\"/", $res, $match);
		if ($ct>0) {
			$fsid = $match[1];
			//-- Try to add relationships to family members that are already in NFS
			if (!empty($fsid)) {
				$hasRelationships = false;
				//-- Start the XML
				$xml1 = $this->xmlHeader.'<persons>';
				//-- Get the XML from the XG_Person object
				$xml1 .= '<person id="'.$fsid.'">';
				$xml1 .= '<relationships>';
				
				$xml2 = '</relationships>';
				$xml2 .= '</person>';
				$xml2 .= '</persons>';
				$xml2 .= $this->xmlFooter;
				//-- add relationships to parents
				$famc = $person->getChildFamilies();
				foreach($famc as $family) {
					$father = $family->getHusband();
					if ($father) {
						$father_fsid = $this->getFSID($father);
						if ($father_fsid) {
							$hasRelationships = true;
							$xml = $xml1.$this->getXMLGed()->getRelationshipXml($father_fsid, "parent").$xml2;
							//-- print for debugging
							//print "<b>Adding Relationships</b><br /><pre>".htmlentities($xml)."</pre>";
									//-- send the XML to familysearch
							$res = $this->getProxy()->addRelationship($fsid, "parent", $father_fsid, $xml);
							//-- print the response for debugging
							print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";
						}
					}
					$mother = $family->getWife();
					if ($mother) {
						$mother_fsid = $this->getFSID($mother);
						if ($mother_fsid) {
							$hasRelationships = true;
							$xml = $xml1.$this->getXMLGed()->getRelationshipXml($mother_fsid, "parent").$xml2;
							//-- print for debugging
							//print "<b>Adding Relationships</b><br /><pre>".htmlentities($xml)."</pre>";
									//-- send the XML to familysearch
							$res = $this->getProxy()->addRelationship($fsid, "parent", $mother_fsid, $xml);
							//-- print the response for debugging
							print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";
						}
					}
				}
				//-- add relationships to spouses and children
				$fams = $person->getSpouseFamilies();
				foreach($fams as $family) {
					$spouse = $family->getSpouse($person);
					if ($spouse) {
						$spouse_fsid = $this->getFSID($spouse);
						if ($spouse_fsid) {
							$hasRelationships = true;
							$xml = $xml1.'<spouse id="'.$spouse_fsid.'">
					          <assertions>';
							$xml .= '<events>';
							$events = $family->getFacts();
							$temp = null;
							foreach($events as $event) {
								$xg_event = $this->getXMLGed()->convertGedcomEvent($event, $temp);
								if ($xg_event && $xg_event instanceof XG_Event) $xml .= $xg_event->toXml(true);
							}
					        $xml .= '</events>';
					        $xml .= '<ordinances>';
							$events = $family->getFacts();
							foreach($events as $event) {
								$xg_event = $this->getXMLGed()->convertGedcomEvent($event, $temp);
								if ($xg_event && $xg_event instanceof XG_Ordinance) {
									//-- only ordinances with places can be added
									if ($xg_event->getPlace()!=null && $xg_event->getPlace()->getOriginal()!="") $xml .= $xg_event->toXml(true);
								}
							}
					        $xml .= '</ordinances>';
					        $xml .= '</assertions>
					        </spouse>'.$xml2;
					        //-- print for debugging
							//print "<b>Adding Relationships</b><br /><pre>".htmlentities($xml)."</pre>";
							//-- send the XML to familysearch
							$res = $this->getProxy()->addRelationship($fsid, "spouse", $spouse_fsid, $xml);
							//-- print the response for debugging
							print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";
						}
					}
					foreach($family->getChildren() as $child) {
						if ($child) {
							$child_fsid = $this->getFSID($child);
							if ($child_fsid) {
								$hasRelationships = true;
								$xml = $xml1.$this->getXMLGed()->getRelationshipXml($child_fsid, "child").$xml2;
								//-- print for debugging
								//print "<b>Adding Relationships</b><br /><pre>".htmlentities($xml)."</pre>";
										//-- send the XML to familysearch
								$res = $this->getProxy()->addRelationship($fsid, "child", $child_fsid, $xml);
								//-- print the response for debugging
								print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";
							}
						}
					}
				}
				
				//TODO add notes
				
				return $fsid;
			}
		}
		else {
			$this->getXMLGed()->parseXml($res);
		}
		return false;
	}
	
	/**
	 * Update a Family Search person with new or deleted facts
	 * @param $fsid
	 * @param $deleted
	 * @param $copied
	 * @return unknown_type
	 */
	function updatePerson($fsid, $deleted, $copied) {
		$xgperson = $this->getXMLGed()->getPerson($fsid);
		$xml = $this->xmlHeader.'<persons>';
		$xml .= '<person id="'.$fsid.'">';
		$assertions = array();
		foreach($deleted as $assertion) {
			$xga = $this->getXMLGed()->convertGedcomEvent($assertion, $xgperson);
			$xga->setMarkedForDelete(true);
			$assertions[] = $xga;
		}
		foreach($copied as $assertion) {
			$xga = $this->getXMLGed()->convertGedcomEvent($assertion, $xgperson);
			if ($xga) $assertions[] = $xga;
		}
//		print " ".count($assertions);
		$axml = XmlGedcom::assertionsToXml($assertions, true);
		if (empty($axml)) return $fsid;
		$xml .= $axml;
		$xml .= '</person></persons>';
		$xml .= $this->xmlFooter;
	print "<pre>".htmlentities($xml)."</pre>";
		//-- send the XML to familysearch
		$res = $this->getProxy()->updatePerson($fsid, $xml);
		//-- print the response for debugging
	print "<b>Response</b><br /><pre>".htmlentities(preg_replace("/></",">\n<", $res))."</pre>";

		//-- try to get the new familysearch id from the response
		$ct = preg_match("/<person.*id=\"(.+)\"/", $res, $match);
		if ($ct>0) {
			$fsid = $match[1];
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
		global $FS_CONFIG;
		//-- add the ID to the person
		$gedrec = trim($person->getGedcomRecord());
		$oldid = $this->getFSID($person);
		if (!$oldid) {
			$gedrec .= "\r\n1 REFN ".$fsid."\r\n2 TYPE FamilySearch";
				
			//-- add the RFN linkage
			if ($FS_CONFIG['family_search_remotelink']) {
				$serverID = $this->getServerId();
				if (!empty($serverID)) {
					$gedrec .= "\r\n1 RFN ".$serverID.":".$fsid;
					$ret = true;
				}
			}
			replace_gedrec($person->getXref(), $gedrec);
		}
		else if ($oldid!=$fsid) {
			$gedrec = preg_replace("/".$oldid."/", $fsid, $gedrec);
			replace_gedrec($person->getXref(), $gedrec);
		}
	}
}
?>