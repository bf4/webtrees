<?php
/**
 * Wrapper for familySearch
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 Hoang Le
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package PhpGedView
 * @subpackage FamilySearch API
 */

require_once('includes/classes/class_gedcomrecord.php');
require_once('includes/classes/class_serviceclient.php');
require_once('modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php');
require_once('modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php');

class FamilySearch_ServiceClient extends ServiceClient {

	var $xmlGed = null;
	/**
	 * Constructor
	 * @param $gedrec gedcom record
	 */
	function FamilySearch_ServiceClient($gedrec){

		parent::ServiceClient($gedrec);

		//get info from the gedcom record
		$this->url = get_gedcom_value("URL",1,$gedrec);
		$this->gedfile = get_gedcom_value("_DBID", 1, $gedrec);
		$this->title = "Family Search";
		$this->type = "remote";
		if (empty($this->url) && empty($this->gedfile)) return null;
	}
	
	/**
	* get the title for this service
	* @return string
	*/
	function getServiceTitle() {
		if (!empty($this->title)) return $this->title;
		return "FamilySearch";
	}

	/**
	 * Create the soapclient object and check if the user is
	 * authenticate, return false if the user is not authenticate,
	 * and log the error.
	 */
	function authenticate() {
		//if the soapClient is null, create a new client
		if(is_null($this->soapClient)){
			$this->soapClient = new familySearchProxy();
			$this->soapClient->setUserName($this->username);
			$this->soapClient->setPassword($this->password);
			$this->soapClient->setUrl($this->url);
			$this->soapClient->setAgent("PhpGedView-JohnFinlay");
			
			//check if the user is authenticate
			$result = $this->soapClient->authenticate(false);
			//-- the result is the XML for a user, do we need to do something with it 
			//log the error
//			if($this->soapClient->hasError){
//				AddToLog($result);
//				var_dump($result);
//				return false;
//			}
			$this->xmlGed = new XmlGedcom();
			$this->xmlGed->setProxy($this->soapClient);
		}
		else {
			$this->soapClient->hasError=false;
		}
		
		return true;
	}

	/**
	 * use this to search for anything providing the query is in the correct format
	 */
	function &search($query){
		//check for authentication
		$this->authenticate();
		//send the request
		$result = $this->soapClient->searchByQuery($query);
		//check for error
		if($this->soapClient->hasError){
			AddToLog($result);
			return;
		}
		//TODO: convert result to GEDCOM

		return $result;
	}

	/**
	 * Get a person record base on id
	 * @param $remoteId the id of the person to be retrieve
	 */
	function getRemoteRecord($remoteid) {
		
		if (!is_object($this->soapClient)) $this->authenticate();
		
		$gedcom = $this->xmlGed->getGedcomRecord($remoteid);
		return $gedcom;
	}
	
	function cacheRecordInDB($gedrec) {
		global $TBLPREFIX, $GEDCOM, $GEDCOMS;
		
		static $sql_insert_indi=null;
		static $sql_insert_fam=null;
		static $sql_insert_sour=null;
		static $sql_insert_other=null;
		if (!$sql_insert_indi) {
			$sql_insert_indi=PGV_DB::prepare(
				"INSERT INTO {$TBLPREFIX}individuals (i_id, i_file, i_rin, i_isdead, i_sex, i_gedcom) VALUES (?,?,?,?,?,?)"
			);
			$sql_insert_fam=PGV_DB::prepare(
				"INSERT INTO {$TBLPREFIX}families (f_id, f_file, f_husb, f_wife, f_chil, f_gedcom, f_numchil) VALUES (?,?,?,?,?,?,?)"
			);
			$sql_insert_sour=PGV_DB::prepare(
				"INSERT INTO {$TBLPREFIX}sources (s_id, s_file, s_name, s_gedcom, s_dbid) VALUES (?,?,?,?,?)"
			);
			$sql_insert_other=PGV_DB::prepare(
				"INSERT INTO {$TBLPREFIX}other (o_id, o_file, o_type, o_gedcom) VALUES (?,?,?,?)"
			);
		}
		
		// import different types of records
		if (preg_match('/^0 @('.PGV_REGEX_XREF.')@ ('.PGV_REGEX_TAG.')/', $gedrec, $match) > 0) {
			list(,$gid, $type)=$match;
		} elseif (preg_match('/0 ('.PGV_REGEX_TAG.')/', $gedrec, $match)) {
			$gid=$match[1];
			$type=$match[1];
		} else {
			echo $pgv_lang['invalid_gedformat'], '<br /><pre>', $gedrec, '</pre>';
			return;
		}
		
		$ged_id=$GEDCOMS[$GEDCOM]["id"];
		$xref  =$gid;
		
		switch ($type) {
		case 'INDI':
			$sex = 'U';
			if (preg_match('/\n1 SEX (.)/', $gedrec, $match)) $sex = $match[1];
			$sql_insert_indi->execute(array($xref, $ged_id, $xref, is_dead($gedrec, '', true), $sex, $gedrec));
			break;
		case 'FAM':
			if (preg_match('/\n1 HUSB @('.PGV_REGEX_XREF.')@/', $gedrec, $match)) {
				$husb=$match[1];
			} else {
				$husb='';
			}
			if (preg_match('/\n1 WIFE @('.PGV_REGEX_XREF.')@/', $gedrec, $match)) {
				$wife=$match[1];
			} else {
				$wife='';
			}
			if ($nchi=preg_match_all('/\n1 CHIL @('.PGV_REGEX_XREF.')@/', $gedrec, $match)) {
				$chil=implode(';', $match[1]).';';
			} else {
				$chil='';
			}
			if (preg_match('/\n1 NCHI (\d+)/', $gedrec, $match)) {
				$nchi=max($nchi, $match[1]);
			}
			$sql_insert_fam->execute(array($xref, $ged_id, $husb, $wife, $chil, $gedrec, $nchi));
			break;
		case 'SOUR':
			if (preg_match('/\n1 TITL (.+)/', $gedrec, $match)) {
				$name=$match[1];
			} elseif (preg_match('/\n1 ABBR (.+)/', $gedrec, $match)) {
				$name=$match[1];
			} else {
				$name=$gid;
			}
			if (strpos($gedrec, "\n1 _DBID")) {
				$_dbid='Y';
			} else {
				$_dbid=null;
			}
			$sql_insert_sour->execute(array($xref, $ged_id, $name, $gedrec, $_dbid));
			break;
		default:
			if (substr($type, 0, 1)!='_') {
				$sql_insert_other->execute(array($xref, $ged_id, $type, $gedrec));
			}
			break;
		}
			
	}

	/**
	 * merge a local gedcom record with the information from the remote site
	 * @param string $xref		the remote ID to merge with
	 * @param string $localrec	the local gedcom record to merge the remote record with
	 * @param boolean $isStub	whether or not this is a stub record
	 * @param boolean $firstLink	is this the first time this record is being linked
	 */
	function mergeGedcomRecord($xref, $localrec, $isStub=false, $firstLink=false) {
		global $FILE, $GEDCOM, $indilist, $famlist, $sourcelist, $otherlist;
		global $TBLPREFIX, $GEDCOMS, $pgv_changes;
		$FILE = $GEDCOM;
		if (!$isStub) {
			//$gedrec = find_gedcom_record($this->xref.":".$xref);
			if (!empty($gedrec)) $localrec = $gedrec;
		}

		//-- used to force an update on the first time linking a person
		if ($firstLink) {
			$this->authenticate();
			$result = $this->xmlGed->getGedcomRecord($xref);
			
//			print_r($result);
			if (PEAR::isError($result) || isset($result->faultcode) || get_class($result)=='SOAP_Fault' || is_object($result)) {
				if (isset($result->faultstring)) {
					AddToLog($result->faultstring);
					print $result->faultstring;
				}
				return $localrec;
			}
			$gedrec = $result;
			$gedrec = preg_replace("/@(.*)@/", "@".$this->xref.":$1@", $gedrec);
			$gedrec = $this->checkIds($gedrec);
			$localrec = $this->_merge($localrec, $gedrec);
			include_once("includes/functions/functions_edit.php");
			if ($this->DEBUG) print $localrec."<b>$gedrec</b>";
			$localrec = $this->UpdateFamily($localrec,$gedrec);
			if ($this->DEBUG) print "post updatefamily<pre>".$localrec."</pre>Whynot?";
			$ct=preg_match("/0 @(.*)@/", $localrec, $match);
			if ($ct>0)
			{
				$pid = trim($match[1]);
				if (preg_match("/:/", $pid)==0) replace_gedrec($pid,$localrec);
			}
		}

		//-- get the last change date of the record
		$change_date = get_gedcom_value("CHAN:DATE", 1, $localrec, '', false);
		if (empty($change_date)) {
			//print $xref." no change<br />";
			$this->authenticate();
			if (!is_object($this->soapClient) || $this->isError($this->soapClient)) return false;
			
			$result = $this->xmlGed->getGedcomRecord($xref);
			
			//print_r($result);
			if (PEAR::isError($result) || isset($result->faultcode) || get_class($result)=='SOAP_Fault' || is_object($result)) {
				if (isset($result->faultstring)) {
					AddToLog($result->faultstring);
					print $result->faultstring;
				}
				return $localrec;
			}
			$gedrec = $result;
			$gedrec = preg_replace("/@(.*)@/", "@".$this->xref.":$1@", $gedrec);
			$gedrec = $this->checkIds($gedrec);
			$localrec = $this->_merge($localrec, $gedrec);
			$ct=preg_match("/0 @(.*)@/", $localrec, $match);
			if ($ct>0)
			{
				$pid = trim($match[1]);
				if ($isStub) {
					include_once("includes/functions/functions_edit.php");
					//$indilist[$localrec->getXref()]['gedcom']=$localrec;
					$localrec = $this->UpdateFamily($localrec,$gedrec);
					if (preg_match("/:/", $pid)==0) replace_gedrec($pid,$localrec);
				}
				else {
					// uncomment to cache in local database
//					if ($this->DEBUG) debug_print_backtrace();
//					if ($this->DEBUG) print __LINE__."adding record to the database ".$localrec;
					//-- update the last change time
					$pos1 = strpos($localrec, "1 CHAN");
					if ($pos1!==false) {
						$pos2 = strpos($localrec, "\n1", $pos1+4);
						if ($pos2===false) $pos2 = strlen($localrec);
						$newgedrec = substr($localrec, 0, $pos1);
						$newgedrec .= "1 CHAN\r\n2 DATE ".date("d M Y")."\r\n";
						$newgedrec .= "3 TIME ".date("H:i:s")."\r\n";
						$newgedrec .= "2 _PGVU @".$this->xref."@\r\n";
						$newgedrec .= substr($localrec, $pos2);
						$localrec = $newgedrec;
					}
					else {
						$newgedrec = "\r\n1 CHAN\r\n2 DATE ".date("d M Y")."\r\n";
						$newgedrec .= "3 TIME ".date("H:i:s")."\r\n";
						$newgedrec .= "2 _PGVU @".$this->xref."@";
						$localrec .= $newgedrec;
					}
					$this->cacheRecordInDB($localrec);
					
				} 
			}
		}
		else {
			$chan_date = new GedcomDate($change_date);
			$chan_time_str = get_gedcom_value("CHAN:DATE:TIME", 1, $localrec, '', false);
			$chan_time = parse_time($chan_time_str);
			$change_time = mktime($chan_time[0], $chan_time[1], $chan_time[2], $chan_date->date1->m, $chan_date->date1->d, $chan_date->date1->y);
			/**
			 * @todo make the timeout a config option
			 */
			// Time Clock (determines how often a record is checked)
			if ($change_time < time()-(60*60*24)) // if the last update (to the remote individual) was made more than 1 days ago
			{
				//$change_date= "1 JAN 2000";
				$this->authenticate();
				if (!is_object($this->soapClient) || $this->isError($this->soapClient)) return false;
				
				$version = get_gedcom_value("CHAN:VERS", 1, $localrec, '', false);

				$xml = $this->soapClient->getRequestData($xref, getPersonById, '&names=none&genders=none&events=none', false);
				$this->xmlGed->parseXml($xml);
				$person = $this->xmlGed->getPerson($xref);
				if (is_null($person)) return $localrec;
				
				// If there are no changes between the local and remote copies
				if ($person->getVersion()<=$version) {
					if (isset($person->faultstring)) AddToLog($person->faultstring);
					else AddToLog($person->message);
					//-- update the last change time
					$pos1 = strpos($localrec, "1 CHAN");
					if ($pos1!==false) {
						$pos2 = strpos($localrec, "\n1", $pos1+4);
						if ($pos2===false) $pos2 = strlen($localrec);
						$newgedrec = substr($localrec, 0, $pos1);
						$newgedrec .= "1 CHAN\r\n2 DATE ".date("d M Y")."\r\n";
						$newgedrec .= "3 TIME ".date("H:i:s")."\r\n";
						$newgedrec .= "2 _PGVU @".$this->xref."@\r\n";
						$newgedrec .= substr($localrec, $pos2);
						$localrec = $newgedrec;
					}
					else {
						$newgedrec = "\r\n1 CHAN\r\n2 DATE ".date("d M Y")."\r\n";
						$newgedrec .= "3 TIME ".date("H:i:s")."\r\n";
						$newgedrec .= "2 _PGVU @".$this->xref."@";
						$localrec .= $newgedrec;
					}
					if ($this->DEBUG) print __LINE__."adding record to the database ".$localrec;
					$this->cacheRecordInDB($localrec);
				}
				// If changes have been made to the remote record
				else {
					$gedrec = $this->xmlGed->getGedcomRecord($xref);
					$gedrec = preg_replace("/@(.*)@/", "@".$this->xref.":$1@", $gedrec);
					$gedrec = $this->checkIds($gedrec);
					$ct=preg_match("/0 @(.*)@/", $localrec, $match);
					if ($ct>0)
					{
						$pid = trim($match[1]);
						if (isset($pgv_changes[$pid."_".$GEDCOM])) $localrec = find_updated_record($pid);
						$localrec = $this->_merge($localrec, $gedrec);
						if ($isStub) {
							include_once("includes/functions/functions_edit.php");
							//$indilist[$localrec->getXref()]['gedcom']=$localrec;
							$localrec = $this->UpdateFamily($localrec,$gedrec);
							if (preg_match("/:/", $pid)==0) replace_gedrec($pid,$localrec);
						}
						else {
							if ($this->DEBUG) print __LINE__."adding record to the database ".$localrec;
							$this->cacheRecordInDB($localrec);
						}
					}
				}
			}
		}

		return $localrec;
	}

}
?>