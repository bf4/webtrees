<?php
/**
 * Class that provides an API for working with GEDCOM files
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007	John Finlay
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
 * @author John Finlay
 * @version $Id: 
 * 
 */

class GedcomFile {
	var $gedcom;
	var $config;
	var $privacy;
	var $title;
	var $path;
	var $pgv_ver;
	var $imported;
	var $id;
	var $commonsurnames;

	/**
	 * Constructor which creates a GedcomFile from a $GEDCOMS array entry
	 *
	 * @param Array $gedarray
	 * @return GedcomFile
	 */
	function GedcomFile($gedarray) {
		$this->gedcom = $gedarray["gedcom"];
		$this->config = $gedarray["config"];
		$this->privacy = $gedarray["privacy"];
		$this->title = $gedarray["title"];
		$this->path = $gedarray["path"];
		$this->pgv_ver = $gedarray["pgv_ver"];
		$this->imported = $gedarray["imported"];
		$this->id = $gedarray["id"];
		$this->commonsurnames = $gedarray["commonsurnames"];
	}
	
	/**
	 * Static function used to get an instance of an object
	 * @param string $pid	the ID of the object to retrieve
	 * @return GedcomRecord
	 */
	function &getInstance($ged) {
		global $gedcom_record_cache, $GEDCOMS;

		$ged_id=get_id_from_gedcom($ged);
		// Check the cache first
		if (isset($gedcom_record_cache[$ged][$ged_id])) {
			return $gedcom_record_cache[$ged][$ged_id];
		}
		
		$gedarray = $GEDCOMS[$ged];

		
		return null;
	}
	
	function getGedcom() { return $this->gedcom; }
	function setGedcom($gedcom) { $this->gedcom = $gedcom; }
	function getConfig() { return $this->config; }
	function setConfig($config) { $this->config = $config; }
	function getPrivacy() { return $this->$privacy; }
	function setPrivacy($privacy) { $this->$privacy = $privacy; }
	function getTitle() { return $this->title; }
	function setTitle($title) { $this->title = $title; }
	function getPath() { return $this->path; }
	function setPath($path) { $this->path = $path; }
	function getVersion() { return $this->pgv_ver; }
	function setVersion($pgv_ver) { $this->pgv_ver = $pgv_ver; }
	function isImported() { return $this->imported; }
	function setImported($imported) { $this->imported = $imported; }
	function getId() { return $this->id; }
	function setId($id) { $this->id = $id; }
	function getCommonSurnames() { return $this->commonsurnames; }
	function setCommonSurnames($commonsurnames) { $this->commonsurnames = $commonsurnames; }
}

?>