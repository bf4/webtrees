<?php
/**
 * Wrapper class for common DB search functions
 *
 * This file implements the datastore functions necessary for PhpGedView to use an SQL database as its
 * datastore. This file also implements array caches for the database tables.  Whenever data is
 * retrieved from the database it is stored in a cache.  When a database access is requested the
 * cache arrays are checked first before querying the database.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @version $Id: functions_db.php 2332 2007-11-30 16:23:19Z fisharebest $
 * @package PhpGedView
 * @subpackage DB
 */

class DBSearch {
	
	var $sqlquery = null;
	var $res = null;
	var $mode = DB_FETCHMODE_ASSOC;
	
	var $next = null;
	
	/**
	 * Free up and close the result set 
	 *
	 */
	function free() {
		$this->res->free();
	}

/**
 * get a list of all the sources
 *
 * returns an array of all of the sources in the database.
 * @link http://phpgedview.sourceforge.net/devdocs/arrays.php#sources
 * @return array the array of sources
 */
function get_source_list() {
	global $GEDCOM, $GEDCOMS;
	global $TBLPREFIX, $DBCONN;

	$sourcelist = array();

	$this->sqlquery = "SELECT s_id, s_name, s_gedcom FROM ".$TBLPREFIX."sources WHERE s_file=".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])." ORDER BY s_name";
	$this->res = dbquery($this->sqlquery);
	$this->next = 'nextSource';
}

function nextSource() {
	global $sourcelist;
	
	$row = $this->res->fetchRow($this->mode);
	if ($row==false) {
		$this->free();
		return false;
	}
	
	$source = array();
	$source["name"] = $row["s_name"];
	$source["gedcom"] = $row["s_gedcom"];
	$row = db_cleanup($row);
	$source["gedfile"] = $GEDCOMS[$GEDCOM]["id"];
	$sourcelist[$row["s_id"]] = $source;
	
	return $source;
	
}

//-- get the repositorylist from the datastore
function get_repo_list() {
	global $GEDCOM, $GEDCOMS;
	global $TBLPREFIX, $DBCONN;

	$repolist = array();

	$this->sqlquery = "SELECT o_id, o_gedcom FROM {$TBLPREFIX}other WHERE o_file=".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])." AND o_type='REPO'";
	$this->res = dbquery($sql);

	while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
		$repo = array();
		$tt = preg_match("/1 NAME (.*)/", $row["o_gedcom"], $match);
		if ($tt == "0") $name = $row["o_id"]; else $name = $match[1];
		$repo["name"] = $name;
		$repo["id"] = $row["o_id"];
		$repo["gedfile"] = $GEDCOMS[$GEDCOM]["id"];
		$repo["type"] = 'REPO';
		$repo["gedcom"] = $row["o_gedcom"];
		$row = db_cleanup($row);
		$repolist[$row["o_id"]]= $repo;
	}
	asort($repolist); // sort by repo name
	return $repolist;
}
}
?>