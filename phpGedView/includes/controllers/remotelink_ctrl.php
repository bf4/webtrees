<?php
/**
 *  Add Remote Link Page
 *
 *  Allow a user the ability to add links to people from other servers and other gedcoms.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Charts
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_REMOTELINK_CTRL_PHP', '');

require_once('includes/controllers/basecontrol.php');
require_once("includes/functions_edit.php");
require_once("includes/serviceclient_class.php");

class RemoteLinkController extends BaseController {
	var $has_familysearch = false;
	var $pid="";
	var $action="";
	var $success = false;
	var $person = null;
	var $server_list = array();

	/**
	 * Initialize the controller for the add remote link
	 *
	 */
	function init() {
		global $GEDCOM;

		if (file_exists("modules/FamilySearch/familySearchWrapper.php")) {
			$this->has_familysearch = true;
			require_once("modules/FamilySearch/familySearchWrapper.php");
		}

		//-- require that the user have entered their password
		if ($_SESSION["cookie_login"]) {
			header('Location: '.encode_url("login.php?type=simple&ged={$GEDCOM}&url=".urlencode("edit_interface.php?".decode_url($QUERY_STRING)), false));
			exit;
		}

		if (isset($_REQUEST['pid'])) $this->pid = $_REQUEST['pid'];
		if (isset($_REQUEST['action'])) $this->action = $_REQUEST['action'];

		//check for pid
		if (empty($this->pid)) {
			$name="no name passed";
			$this->disp = false;
		} else{
			$this->pid = clean_input($this->pid);

			if (!isset($pgv_changes[$this->pid."_".$GEDCOM])) $this->person = Person::getInstance($this->pid);
			else {
				$gedrec = find_updated_record($this->pid);
				$this->person = new Person($gedrec);
			}
			$this->server_list = get_server_list();
		}
	}

	/**
	 * Perform the desired action
	 *
	 */
	function runAction() {
		global $pgv_lang, $GEDCOM;
		$this->success = false;
		if ($this->action=="addlink") {
			$serverID = trim(stripslashes(safe_POST('cbExistingServers')));
			if (empty($serverID)) {
				$is_remote = trim(stripslashes(safe_POST('location')));
				switch ($is_remote) {
				case "remote":
					$server_URL = trim(stripslashes(safe_POST('txtURL', PGV_REGEX_URL, '')));
					if (empty($server_URL)) {
						echo "Must enter a URL";
					} else {
						$server_title = trim(stripslashes(safe_POST('txtTitle', '[^<>"%{};]+', '')));
						$gedcom_id = trim(stripslashes(safe_POST('txtGID', PGV_REGEX_NOSCRIPT, '')));
						$username = trim(stripslashes(safe_POST('txtUsername', PGV_REGEX_NOSCRIPT, '')));
						$password = trim(stripslashes(safe_POST('txtPassword', PGV_REGEX_NOSCRIPT, '')));

						$serverID = $this->addRemoteServer($server_title, $server_URL, $gedcom_id, $username, $password);
					}
					break;
				case "FamilySearch":
				//this will happen when the family search radio button is selected.
				//TODO: Make sure that it is merging correctly
					$server_URL = trim(stripslashes(safe_POST('txtFS_URL', PGV_REGEX_URL, '')));
					if (empty($server_URL)) {
						echo "Must enter a URL";
					} else {
						$server_title = trim(stripslashes(safe_POST('txtFS_Title', '[^<>"%{};]+', '')));
						$gedcom_id = trim(stripslashes(safe_POST('txtFS_GID', PGV_REGEX_NOSCRIPT, '')));
						$username = trim(stripslashes(safe_POST('txtFS_Username', PGV_REGEX_NOSCRIPT, '')));
						$password = trim(stripslashes(safe_POST('txtFS_Password', PGV_REGEX_NOSCRIPT, '')));

						$serverID = $this->addFamilySearchServer($server_title, $server_URL, $gedcom_id, $username, $password);
					}
					break;
				default:		// Must be a local database
					$server_title = trim(stripslashes(safe_POST('txtCB_Title', '[^<>"%{};]+', '')));
					$gedcom_id = trim(stripslashes(safe_POST('txtCB_GID', PGV_REGEX_NOSCRIPT, '')));
					$serverID = $this->addLocalServer($server_title, $gedcom_id);
					break;
				}
			}

			$link_pid = safe_POST('txtPID');
			$relation_type = safe_POST('cbRelationship');
			if (!empty($serverID)&&!empty($link_pid)) {
				if (isset($pgv_changes[$this->pid."_".$GEDCOM])) $indirec = find_updated_record($this->pid);
				else $indirec = find_person_record($this->pid);

				switch ($relation_type) {
				case "father":
					$indistub = "0 @new@ INDI\r\n";
					$indistub .= "1 SOUR @".$serverID."@\r\n";
					$indistub .= "2 PAGE ".$link_pid."\r\n";
					$indistub .= "1 RFN ".$serverID.":".$link_pid."\r\n";
					$stub_id = append_gedrec($indistub, false);
					$indistub = find_updated_record($stub_id);

					$gedcom_fam = "0 @new@ FAM\r\n";
					$gedcom_fam.= "1 HUSB @".$stub_id."@\r\n";
					$gedcom_fam.= "1 CHIL @".$pid."@\r\n";
					$fam_id = append_gedrec($gedcom_fam);

					$indirec.= "\r\n";
					$indirec.= "1 FAMC @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($pid, $indirec);

					$serviceClient = ServiceClient::getInstance($serverID);
					$indistub = $serviceClient->mergeGedcomRecord($link_pid, $indistub, true, true);
					$indistub.= "\r\n1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($stub_id, $indistub, false);
					break;
				case "mother":
					$indistub = "0 @NEW@ INDI\r\n";
					$indistub .= "1 SOUR @".$serverID."@\r\n";
					$indistub .= "2 PAGE ".$link_pid."\r\n";
					$indistub .= "1 RFN ".$serverID.":".$link_pid."\r\n";
					$stub_id = append_gedrec($indistub, false);
					$indistub = find_updated_record($stub_id);

					$gedcom_fam = "0 @NEW@ FAM\r\n";
					$gedcom_fam.= "1 WIFE @".$stub_id."@\r\n";
					$gedcom_fam.= "1 CHIL @".$pid."@\r\n";
					$fam_id = append_gedrec($gedcom_fam);

					$indirec.= "\r\n";
					$indirec.= "1 FAMC @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($pid, $indirec);

					$serviceClient = ServiceClient::getInstance($serverID);
					$indistub = $serviceClient->mergeGedcomRecord($link_pid, $indistub, true, true);
					$indistub.= "\r\n1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($stub_id, $indistub, false);
					break;
				case "husband":
					$indistub = "0 @NEW@ INDI\r\n";
					$indistub .= "1 SOUR @".$serverID."@\r\n";
					$indistub .= "2 PAGE ".$link_pid."\r\n";
					$indistub .= "1 RFN ".$serverID.":".$link_pid."\r\n";
					$stub_id = append_gedrec($indistub, false);
					$indistub = find_updated_record($stub_id);

					$gedcom_fam = "0 @NEW@ FAM\r\n";
					$gedcom_fam.= "1 WIFE @".$pid."@\r\n";
					$gedcom_fam.= "1 HUSB @".$stub_id."@\r\n";
					$fam_id = append_gedrec($gedcom_fam);

					$indirec.= "\r\n";
					$indirec.= "1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($pid, $indirec);

					$serviceClient = ServiceClient::getInstance($serverID);
					$indistub = $serviceClient->mergeGedcomRecord($link_pid, $indistub, true, true);
					$indistub.= "\r\n1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($stub_id, $indistub, false);
					break;
				case "wife":
					$indistub = "0 @NEW@ INDI\r\n";
					$indistub .= "1 SOUR @".$serverID."@\r\n";
					$indistub .= "2 PAGE ".$link_pid."\r\n";
					$indistub .= "1 RFN ".$serverID.":".$link_pid."\r\n";
					$stub_id = append_gedrec($indistub, false);
					$indistub = find_updated_record($stub_id);

					$gedcom_fam = "0 @NEW@ FAM\r\n";
					$gedcom_fam.= "1 WIFE @".$stub_id."@\r\n";
					$gedcom_fam.= "1 HUSB @".$pid."@\r\n";
					$fam_id = append_gedrec($gedcom_fam);

					$indirec.= "\r\n";
					$indirec.= "1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($pid, $indirec);

					$serviceClient = ServiceClient::getInstance($serverID);
					$indistub = $serviceClient->mergeGedcomRecord($link_pid, $indistub, true, true);
					$indistub.= "\r\n1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($stub_id, $indistub, false);
					break;
				case "son":
				case "daughter":
					$indistub = "0 @NEW@ INDI\r\n";
					$indistub .= "1 SOUR @".$serverID."@\r\n";
					$indistub .= "2 PAGE ".$link_pid."\r\n";
					$indistub .= "1 RFN ".$serverID.":".$link_pid."\r\n";
					$stub_id = append_gedrec($indistub, false);
					$indistub = find_updated_record($stub_id);

					$sex = get_gedcom_value("SEX", 1, $indirec, '', false);
					if ($sex=="M") {
						$gedcom_fam = "0 @NEW@ FAM\r\n";
						$gedcom_fam.= "1 HUSB @".$pid."@\r\n";
						$gedcom_fam.= "1 CHIL @".$stub_id."@\r\n";
					} else {
						$gedcom_fam = "0 @NEW@ FAM\r\n";
						$gedcom_fam.= "1 WIFE @".$pid."@\r\n";
						$gedcom_fam.= "1 CHIL @".$stub_id."@\r\n";
					}
					$fam_id = append_gedrec($gedcom_fam);
					$indirec.= "\r\n";
					$indirec.= "1 FAMS @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($pid, $indirec);

					$serviceClient = ServiceClient::getInstance($serverID);
					$indistub = $serviceClient->mergeGedcomRecord($link_pid, $indistub, true, true);
					$indistub.= "\r\n1 FAMC @".$fam_id."@\r\n";
					$answer2 = replace_gedrec($stub_id, $indistub,false);
					break;
				default:		// assume "self"
					$indirec.="\r\n";
					$indirec.="1 RFN ".$serverID.":".$link_pid."\r\n";
					$indirec.="1 SOUR @".$serverID."@\r\n";

					$serviceClient = ServiceClient::getInstance($serverID);
					if (!is_null($serviceClient)) {
						//-- get rid of change date
						$pos1 = strpos($indirec, "\n1 CHAN");
						if ($pos1!==false) {
							$pos2 = strpos($indirec, "\n1", $pos1+5);
							if ($pos2===false) $indirec = substr($indirec, 0, $pos1+1);
							else $indirec= substr($indirec, 0, $pos1+1).substr($indirec, $pos2+1);
						}
						//print "{".$indirec."}";
						$indirec = $serviceClient->mergeGedcomRecord($link_pid, $indirec, true, true);
					} else print "Unable to find server";
					//$answer2 = replace_gedrec($pid, $indirec);
					break;
				}
				print "<b>".$pgv_lang["link_success"]."</b>";
				$this->success = true;
			}
		}
		return $this->success;
	}

	/**
	 * Add a remote server
	 *
	 * @param string $title
	 * @param string $url
	 * @param string $gedcom_id
	 * @param string $username
	 * @param string $password
	 * @return mixed	the serverID of the server to link to
	 */
	function addRemoteServer($title, $url, $gedcom_id, $username, $password) {
		if (preg_match("/\?wsdl$/", $url)==0) $url.="?wsdl";
		$serverID = $this->checkExistingServer($url, $gedcom_id);
		if ($serverID===false) {
			$gedcom_string = "0 @new@ SOUR\r\n";
			$gedcom_string.= "1 URL ".$url."\r\n";
			$gedcom_string.= "1 _DBID ".$gedcom_id."\r\n";
			$gedcom_string.= "2 _USER ".$username."\r\n";
			$gedcom_string.= "2 _PASS ".$password."\r\n";
			//-- only allow admin users to see password
			$gedcom_string.= "3 RESN confidential\r\n";
			$service = new ServiceClient($gedcom_string);
			$sid = $service->authenticate();
			if (PEAR::isError($sid)) $sid = '';
			if (empty($sid)) print "<span class=\"error\">failed to authenticate to remote site</span>";
			else {
				if (empty($title)) $title = $service->getServiceTitle();
				$gedcom_string.= "1 TITL ".$title."\r\n";
				$serverID = append_gedrec($gedcom_string);
			}
		}
		return $serverID;
	}

	/**
	 * Add a familySearch server
	 *
	 * @param string $title
	 * @param string $url
	 * @param string $gedcom_id
	 * @param string $username
	 * @param string $password
	 * @return mixed	the serverID of the server to link to
	 */
	function addFamilySearchServer($title, $url, $gedcom_id, $username, $password) {
		$serverID = $this->checkExistingServer($url, $gedcom_id);
		if ($serverID===false) {
			$gedcom_string = "0 @new@ SOUR\r\n";
			$gedcom_string.= "1 URL ".$url."\r\n";
			$gedcom_string.= "2 TYPE FamilySearch\r\n";
			$gedcom_string.= "1 _DBID ".$gedcom_id."\r\n";
			$gedcom_string.= "2 _USER ".$username."\r\n";
			$gedcom_string.= "2 _PASS ".$password."\r\n";
			//-- only allow admin users to see password
			$gedcom_string.= "3 RESN confidential\r\n";
			$service = new FamilySearchWrapper($gedcom_string);
			$sid = $service->authenticate();
			if (PEAR::isError($sid)) $sid = '';
			if (empty($sid)) print "<span class=\"error\">failed to authenticate to remote site</span>";
			else {
				if (empty($title)) $title = $service->getServiceTitle();
				$title = $service->getServiceTitle();
				$gedcom_string.= "1 TITL ".$title."\r\n";
				$serverID = append_gedrec($gedcom_string);
			}
		}
		return $serverID;
	}

	/**
	 * Add a server record for a local remote link
	 *
	 * @param string $gedcom_id
	 * @return mixed	the serverID of the server to link to
	 */
	function addLocalServer($title, $gedcom_id) {
		global $SERVER_URL, $GEDCOMS;
		$serverID = $this->checkExistingServer($SERVER_URL, $gedcom_id);
		if ($serverID===false) {
			$gedcom_string = "0 @new@ SOUR\r\n";
			if (empty($title)) $title = $GEDCOMS[$gedcom_id]["title"];
			$gedcom_string.= "1 TITL ".$title."\r\n";
			$gedcom_string.= "1 URL ".$SERVER_URL."\r\n";
			$gedcom_string.= "1 _DBID ".$gedcom_id."\r\n";
			$gedcom_string.= "2 _BLOCK false\r\n";
			$serverID = append_gedrec($gedcom_string);
		}
		return $serverID;
	}

	/**
	 * check if the server already exists
	 *
	 * @param string $url
	 * @param string $gedcom_id
	 * @return mixed	the id of the server to link to or false if it does not exist
	 */
	function checkExistingServer($url, $gedcom_id='') {
		global $pgv_changes;
		//-- get rid of the protocol
		$turl = preg_replace("~^\w+://~", "", $url);
		//-- check the existing server list
		foreach ($this->server_list as $id=>$server) {
			if (stristr($server['url'], $turl)) {
				if (empty($gedcom_id) || preg_match("/_DBID $gedcom_id/", $server['gedcom']))
				return $id;
			}
		}

		//-- check for recent additions
		foreach ($pgv_changes as $cid=>$changes) {
			$change = $changes[count($changes) - 1];
			if ($change['type']!='delete') {
				$gid = $change["gid"];
				$indirec = $change["undo"];
				$surl = get_gedcom_value("URL", 1, $indirec);
				if (!empty($surl) && stristr($surl, $turl)) {
					if (preg_match("/0\s*@(.+)@\s*(\w+)/", $gedrec, $match)) {
						$id = $match[1];
						$type=$match[2];
						if ($type=="SOUR") {
							if (empty($gedcom_id) || preg_match("/_DBID $gedcom_id/", $indirec))
								return $id;
						}
					}
				}
			}
		}
		return false;
	}

	/**
	 * whether or not the user has access to this area
	 *
	 * @return boolean
	 */
	function canAccess() {
		global $ALLOW_EDIT_GEDCOM;
		if (!$ALLOW_EDIT_GEDCOM) return false;
		if (!PGV_USER_GEDCOM_ADMIN) return false;
		if (!$this->person->canDisplayDetails()) return false;
		return true;
	}
}

?>
