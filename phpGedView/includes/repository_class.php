<?php
/**
 * Class file for a Repository (REPO) object
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
 * @subpackage DataModel
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_REPOSITORY_CLASS_PHP', '');

require_once 'includes/gedcomrecord.php';
require_once 'includes/serviceclient_class.php';

class Repository extends GedcomRecord {
	var $sourcelist     =null;
	var $repositoryfacts=null;

	/**
	 * Static function used to get an instance of a repository object
	 * @param string $pid	the ID of the repository to retrieve
	 */
	static function &getInstance($pid, $simple=true) {
		global $gedcom_record_cache, $GEDCOM, $pgv_changes;

		$ged_id=get_id_from_gedcom($GEDCOM);

		// Check the cache first
		if (isset($gedcom_record_cache[$pid][$ged_id])) {
			return $gedcom_record_cache[$pid][$ged_id];
		}

		// Look for the record in the database
		$data=fetch_other_record($pid, $ged_id);

		// If we didn't find the record in the database, it may be remote
		if (!$data && strpos($pid, ':')) {
			list($servid, $remoteid)=explode(':', $pid);
			$service=ServiceClient::getInstance($servid);
			if ($service) {
				// TYPE will be replaced with the type from the remote record
				$data=$service->mergeGedcomRecord($remoteid, "0 @{$pid}@ TYPE\n1 RFN {$pid}", false);
			}
		}

		// If we didn't find the record in the database, it may be new/pending
		if (!$data && PGV_USER_CAN_EDIT && isset($pgv_changes[$pid.'_'.$GEDCOM])) {
			$data=find_updated_record($pid);
			$fromfile=true;
		}

		// If we still didn't find it, it doesn't exist
		if (!$data) {
			return null;
		}

		// Create the object
		$object=new Repository($data, $simple);
		if (!empty($fromfile)) {
			$object->setChanged(true);
		}
		
		// Store it in the cache
		$gedcom_record_cache[$object->xref][$object->ged_id]=&$object;
		return $object;
	}

	/**
	 * get the repository name
	 * @return string
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * get repository facts array
	 * @return array
	 */
	function getRepositoryFacts() {
		$this->parseFacts();
		return $this->facts;
	}

	/**
	 * get the list of sources connected to this repository
	 * @return array
	 */
	function getRepositorySours() {
		global $REGEXP_DB;
		if (is_null($this->sourcelist)) {
			$query="REPO @".$this->xref."@";
			if (!$REGEXP_DB) {
				$query="%".$query."%";
			}

			$this->sourcelist=search_sources($query);
			uasort($this->sourcelist, "itemsort");
		}
		return $this->sourcelist;
	}

	/**
	 * get the count of sources connected to this repository
	 * @return array
	 */
	function countLinkedSources() {
		global $DBCONN, $TBLPREFIX, $TOTAL_QUERIES;

		$query=preg_replace('/([_%@])/', '@$1', $DBCONN->escapeSimple($this->getXref()));

		++$TOTAL_QUERIES;
		return $DBCONN->getOne(
			"SELECT COUNT(s_id) FROM {$TBLPREFIX}sources ".
			"WHERE s_gedcom LIKE '%\n1 REPO @@{$query}@@%' ESCAPE '@' ".
			"AND s_file=".$this->ged_id
		);
	}

	/**
	 * get the URL to link to this repository
	 * @string a url that can be used to link to this repository
	 */
	function getLinkUrl() {
		return parent::getLinkUrl('repo.php?rid=');
	}

	// Get an array of structures containing all the names in the record
	function getAllNames() {
		return parent::getAllNames('NAME');
	}
}
?>
