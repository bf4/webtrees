<?php

/**
 * phpGedView Research Assistant Tool - Functions File.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008, John Finlay and Others
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
 * @subpackage Research_Assistant
 * @version $Id$
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 * @author Mike Hessick
 * @author Mike Austin
 * @author Gavin Winkler
 * @author David Molton
 * @author Daniel Parker
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"ra_functions.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
loadLangFile("ra_lang");	// Set up our default language file

include_once("modules/research_assistant/forms/ra_privacy.php");
include_once("modules/research_assistant/forms/ra_RSFunction.php");
require_once("modules/research_assistant/forms/ra_RSSingleFactClass.php");


if (file_exists($INDEX_DIRECTORY.$GEDCOM."_ra_priv.php")) include_once($INDEX_DIRECTORY.$GEDCOM."_ra_priv.php");
define("BASEPATH", 'modules/research_assistant/');
$emptyfacts = array("BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","BAPL","CONL","ENDL","SLGC","EVEN","MARR","SLGS","MARL","ANUL","CENS","DIV","DIVF","ENGA","MARB","MARC","MARS","CHAN","_SEPR","RESI", "DATA", "MAP");
$templefacts = array("SLGC","SLGS","BAPL","ENDL","CONL");
$nonplacfacts = array("ENDL","NCHI","SLGC","SLGS","SSN");
$nondatefacts = array("ABBR","ADDR","AFN","AUTH","EMAIL","FAX","NAME","NCHI","NOTE","OBJE","PHON","PUBL","REFN","REPO","SEX","SOUR","SSN","TEXT","TITL","WWW","_EMAIL");

if (!function_exists("find_updated_record")) {
	/**
	 * find and return an updated gedcom record
	 * @param string $gid	the id of the record to find
	 * @param string $gedfile	the gedcom file to get the record from.. defaults to currently active gedcom
	 */
	function find_updated_record($gid, $gedfile="") {
		global $GEDCOMS, $GEDCOM, $pgv_changes;
		
		if (empty($gedfile)) $gedfile = $GEDCOM;
		
		if (isset($pgv_changes[$gid."_".$gedfile])) {
			$change = end($pgv_changes[$gid."_".$gedfile]);
			return $change['undo'];
		}
		return "";
	}
}

/**
 * trims a PLAC string to a certain depth for comparison purposes
 */
function trimLocation($loc) {
	$loclevels = 4;  // number of levels of detail to keep
	if (!$loc) return "";
	$newLoc = ""; 
	// reverse the array so that we get the top level first
	$levels = array_reverse(preg_split("/,/", $loc));
	foreach($levels as $pindex=>$ppart) {
		// build the location string in reverse order, up to the requested number of levels
		if ($pindex < $loclevels) $newLoc .= trim($ppart).",";
	}
	// there is an extra comma at the end, but since this string is just used for comparison purposes, it won't hurt anything
	return ($newLoc);
}

/**
 * Base class for the Research Assistant, contains all basic functionality
 */
class ra_functions {

	var $sites = array();
	function Init() {
		$this->createDatabase();
		$this->createFactLookup();
	}

	function createFactLookup() {
		// Make sure we can use our database functions
		//require_once("includes/functions_db.php");//Need to fix this the file is not found

		// Grab our database connections and table prefix from the site
		global $DBCONN, $TBLPREFIX, $DBTYPE;

		$data = $DBCONN->getListOf('tables');
		$res = 0;

		// If the Table is not in the array
		if (!in_array($TBLPREFIX.'factlookup', $data)) {
			//Then create Table
			// TODO don't use auto-inc fields... instead use the get_next_id serializer
	   	   if ($DBTYPE == "pgsql")
				$sql = 'create table '.$TBLPREFIX.'factlookup (id SERIAL,Description VARCHAR(255) not null,StartDate INT not null, EndDate INT not null, Gedcom_fact VARCHAR(10),PL_LV1 VARCHAR(255), PL_LV2 VARCHAR(255), PL_LV3 VARCHAR(255), PL_LV4 VARCHAR(255), PL_LV5 VARCHAR(255), SOUR_ID VARCHAR(255),Comment VARCHAR(255),PRIMARY KEY(id))';			
			else
				$sql = 'create table '.$TBLPREFIX.'factlookup (id INT AUTO_INCREMENT,Description VARCHAR(255) not null,StartDate INT not null, EndDate INT not null, Gedcom_fact VARCHAR(10),PL_LV1 VARCHAR(255), PL_LV2 VARCHAR(255), PL_LV3 VARCHAR(255), PL_LV4 VARCHAR(255), PL_LV5 VARCHAR(255), SOUR_ID VARCHAR(255),Comment VARCHAR(255),PRIMARY KEY(id))';
			$res = dbquery($sql);
			$this->insertInitialFacts();
		}
		
		
	}
	
	/*
	 * Gets events from within the date range supplied
	 * 
	 * @startDate the Starting date you want to look from, in the format yyyymmdd
	 * @endDate the Ending date for the range you want to look, in the format yyyymmdd
	 * @factLookingFor optional fact to narrow to a specific GEDCOM facttype
	 * @place the place you want to narrow your search to.  Comma delimited. I.E(USA,IDAHO,BOISE) Up to 5 total criteria
	 * 
	 * @return A multi-dimensional array of the valid dates
	 */
	function getEventsForDates($startDate,$endDate,$factLookingFor = "",$place = "")
	{
		global $DBCONN, $TBLPREFIX;
		if(empty($endDate))
		{
			//Add a ten year difference if no end date was sent in
			$endDate = $startDate + 00100000;
		}
		
		if(empty($factLookingFor))
		{
			$sql = 'Select * from '.$TBLPREFIX.'factlookup WHERE StartDate <= '.$endDate.' AND EndDate >= '.$startDate;
		}
		else
		{
			$sql = 'Select * from '.$TBLPREFIX.'factlookup WHERE StartDate <= '.$endDate.' AND EndDate >= '.$startDate.' AND Gedcom_fact like \'%'.$factLookingFor.'%\'';
		}
		
		if (!empty($place)) {
			$parts = preg_split("/,/",$place);
			for($i = 0; $i < count($parts); $i++)
			{
				$parts[$i] = trim($parts[$i]);
			}
			
			if(count($parts) > 0)
			{
				$numOfParts = count($parts) -1;
				for($i = 0; ($i < count($parts) && $i<5); $i++)
				{
					if (!empty($parts[$numOfParts])) {
						$sql .= ' AND PL_LV'.($i+1).' LIKE \'%'.$DBCONN->escapeSimple($parts[$numOfParts]).'%\'';
					}
					$numOfParts--;
				}
				
			}
		}
		else {
			$sql .= ' AND PL_LV1 IS NULL ';
		}
		$res = dbquery($sql);
		$rows = array();
		while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$rows[] = $row;
		}
		$res->free();
		
		return $rows;
	}
	
	
	function insertInitialFacts()
	{
		/*
		 * Yes I know the below code constructs a query and then hits the database over and over again.
		 * But it has to be done this way in the interests of compatibility
		 */
		global $DBCONN, $TBLPREFIX;
		$data = $DBCONN->getListOf('tables');
		
		if(in_array($TBLPREFIX.'factlookup', $data))
		{
			//Do the insertion of Census facts
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1800\',18000000,18001231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1810\',18100000,18101231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1820\',18200000,18201231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1830\',18300000,18301231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1840\',18100000,18401231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1850\',18500000,18501231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1860\',18600000,18601231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1870\',18700000,18701231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1880\',18800000,18801231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1890\',18900000,18901231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1900\',19000000,19001231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1910\',19100000,19101231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1920\',19200000,19201231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'US Census 1930\',19300000,19301231,\'CENS\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1841\',18410000,18411231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1851\',18510000,18511231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1861\',18610000,18611231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1871\',18710000,18711231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1881\',18810000,18811231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1891\',18910000,18911231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'UK Census 1901\',19010000,19011231,\'CENS\',\'UK\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			//end census stuff
			
			//Insert War facts here
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'Civil War\',18610412,18651231,\'_MILI\',\'USA\',null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'WWI\',19140412,19181231,\'_MILI\',null,null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'WWII\',19390412,19451231,\'_MILI\',null,null,null,null,null,null,null);';
			$res = dbquery($sql);
			$sql = 'insert into '.$TBLPREFIX.'factlookup VALUES(null,\'Korean War\',19500625,19531231,\'_MILI\',null,null,null,null,null,null,null);';
			$res = dbquery($sql);
			
			//End War Facts
		}
	}
		
	
	/**
	 * This function will make the database for the research assistant on first access.
	 * 
	 * @return void
	 */
	function createDatabase() {
		// Make sure we can use our database functions
		//require_once("includes/functions_db.php");//Need to fix this the file is not found

		// Grab our database connections and table prefix from the site
		global $DBCONN, $TBLPREFIX;

		$data = $DBCONN->getListOf('tables');
		$res = 0;

		// Create all of the tables needed for this module
		if (!in_array($TBLPREFIX.'tasks', $data)) {
			$sql = 'create table '.$TBLPREFIX.'tasks (t_id INTEGER not null,t_fr_id INTEGER not null,t_title VARCHAR(255) not null,t_description text not null, t_startdate INT not null, t_enddate INT null, t_results text null, t_form varchar(255) null, t_username varchar(45) null, constraint '.$TBLPREFIX.'tasks_PK primary key (t_id) );';
			$res = dbquery($sql);
		}
		else {
			$has_form = false;
			$info = $DBCONN->tableInfo($TBLPREFIX."tasks");
			foreach($info as $indexval => $field) {
				if ($field["name"]=="t_form") $has_form = true;
			}
			if (!$has_form) {
				$sql = "alter table ".$TBLPREFIX."tasks add t_form varchar(255)";
				$res = dbquery($sql);
			}
		}
		if (!in_array($TBLPREFIX.'comments', $data)) {
			$sql = 'create table '.$TBLPREFIX.'comments (c_id INTEGER not null,c_t_id INTEGER not null,c_u_username VARCHAR(30) not null,c_body text not null,c_datetime INT not null, constraint '.$TBLPREFIX.'comments_PK primary key (c_id) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'tasksource', $data)) {
			$sql = 'create table '.$TBLPREFIX.'tasksource (ts_t_id INTEGER not null,ts_s_id VARCHAR(255) not null, ts_page VARCHAR(255), ts_date VARCHAR(50), ts_text TEXT, ts_quay VARCHAR(50), ts_obje VARCHAR(20), ts_array TEXT, constraint '.$TBLPREFIX.'tasksource_PK primary key (ts_s_id, ts_t_id) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'folders', $data)) {
			$sql = 'create table '.$TBLPREFIX.'folders (fr_id INTEGER not null,fr_name VARCHAR(255),fr_description text null,fr_parentid INTEGER null, constraint '.$TBLPREFIX.'folders_PK primary key (fr_id) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'individualtask', $data)) {
			$sql = 'create table '.$TBLPREFIX.'individualtask (it_t_id integer not null,it_i_id VARCHAR(255) not null,it_i_file integer not null, constraint '.$TBLPREFIX.'individualtask_PK primary key (it_t_id, it_i_id,it_i_file) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'taskfacts', $data)) {
			$sql = "CREATE TABLE ".$TBLPREFIX."taskfacts (tf_id INTEGER NOT NULL, tf_t_id INTEGER, tf_factrec TEXT, tf_people VARCHAR(255),tf_multiple VARCHAR(3), tf_type VARCHAR(4), primary key (tf_id))";
			$res = dbquery($sql);
		}
		else {
			$has_multiple = false;
			$has_type = false;
			$info = $DBCONN->tableInfo($TBLPREFIX."taskfacts");
			foreach($info as $indexval => $field) {
				if ($field["name"]=="tf_multiple") $has_multiple = true;
				if ($field["name"]=="tf_type") $has_type = true;
			}
			if (!$has_multiple) {
				$sql = "alter table ".$TBLPREFIX."taskfacts add tf_multiple varchar(3)";
				$res = dbquery($sql);
			}
			if (!$has_type) {
				$sql = "alter table ".$TBLPREFIX."taskfacts add tf_type varchar(4)";
				$res = dbquery($sql);
			}
		}
		if(!in_array($TBLPREFIX.'user_comments', $data)){
			//$sql = 'create table'.$TBLPREFIX.'user_comments (uc_id INTEGER not null,uc_username VARCHAR(45) not null,uc_datetime INTEGER not null,uc_comment VARCHAR(500) not null,uc_p_id VARCHAR(255) not null,uc_f_id INTEGER not null, constraint '.$TBLPREFIX.'user_comments_PK primary key (uc_id));';
			$sql = 'create table '.$TBLPREFIX.'user_comments (uc_id INT not null,uc_username VARCHAR(45) not null,uc_datetime INT not null,uc_comment text,uc_p_id VARCHAR(255) not null,uc_f_id INT not null, constraint '.$TBLPREFIX.'user_comments_PK primary key (uc_id));';
			$res = dbquery($sql);
		}
		
		/**
		 * Note on the probabbilities table.
		 * It is used to store the calculated percentages of specific facts.
		 * An example of these facts follow.
		 * The individuals surname matches their fathers 98% of the time
		 * 
		 * The break down is the first level element, second level element, relationship, percentage 
		 */
		if (!in_array($TBLPREFIX.'probabilities', $data)) {
			$sql = "create table ".$TBLPREFIX."probabilities (pr_id int NOT NULL, pr_f_lvl varchar(200) NOT NULL, pr_s_lvl varchar(200), pr_rel varchar(200) NOT NULL, pr_matches INT NOT NULL, pr_count INT NOT NULL, pr_file INTEGER, primary key (pr_id) )";
			$res = dbquery($sql);
		}
	}
	
	/**
	 * gets the details for a task from the database
	 * @param int taskid
	 * @return array
	 */
	function getTask($taskid) {
		global $TBLPREFIX, $DBCONN;
		$sql = "select * from ".$TBLPREFIX."tasks where t_id='".$DBCONN->escapeSimple($taskid)."'";
		$res = dbquery($sql);
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();
		return $row;
	}

	/**
	 * Displays the menu for the research_assistand module
	 * 
	 * This function will print the main menu for the research_assistant.
	 * It will also do checks to make sure they are in a folder and display the relevant
	 * menu assications to what the user is doing. That way you cant delete a folder if you aren't in one etc..
	 *
	 * @param string $folderid 
	 * @access public
	 * @return mixed
	 */
	function print_menu($folderid = "", $taskid = "") {
			// Grab the global language array for internationalization
	global $pgv_lang, $TBLPREFIX;

		$width = 150;

		if (empty ($folderid) && !empty ($taskid)) {
			$sql = "select t_fr_id from ".$TBLPREFIX."tasks where t_id = $taskid";
			$result = dbquery($sql);
			if ($result->numRows() > 0) {
				$row = $result->fetchRow();
				$folderid = $row[0];
			}
		}

		// Restrict the column if we are not at the top of the module
		if ($folderid == "")
			$percent = "";
		else
			$percent = " width=\"22%\" ";

		// Display for the menu
global $SHOW_MY_TASKS, $SHOW_ADD_TASK, $SHOW_AUTO_GEN_TASK, $SHOW_VIEW_FOLDERS, $SHOW_ADD_FOLDER, $SHOW_ADD_UNLINKED_SOURCE, $SHOW_VIEW_PROBABILITIES;//show
		$out = '<table class="list_table" width="100%" cellpadding="2">';
		$out .= '<tr>';
		$out .= '<td align="left"'.$percent.'class="optionbox wrap">'.ra_functions :: print_top_folder($folderid).'</td>';
		$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["research_assistant"].'" border="0"></img><br />'.$pgv_lang["research_assistant"].'</a></td>';
		//button 'My Tasks'
		if (PGV_USER_ACCESS_LEVEL<=$SHOW_MY_TASKS)
			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=mytasks"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["my_tasks"].'" border="0"></img><br />'.$pgv_lang["my_tasks"].'</a></td>';
		//button 'Add Task''
		if (PGV_USER_ACCESS_LEVEL<=$SHOW_ADD_TASK)
			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=addtask&amp;folderid='.$folderid.'"><img src="modules/research_assistant/images/add_task.gif" alt="'.$pgv_lang["add_task"].'" border="0"></img><br />'.$pgv_lang["add_task"].'</a></td>';
		//button 'Auto Generate Tasks'
//		if (PGV_USER_ACCESS_LEVEL<=$SHOW_AUTO_GEN_TASK)
//			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=genTasks"><img src="modules/research_assistant/images/add_task.gif" alt="'.$pgv_lang["gen_tasks"].'" border="0"></img><br />'.$pgv_lang["gen_tasks"].'</a></td>';
		//button 'View Folders'
		if (PGV_USER_ACCESS_LEVEL<=$SHOW_VIEW_FOLDERS)
			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=view_folders"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["view_folders"].'" border="0"></img><br />'.$pgv_lang["view_folders"].'</a></td>';
		//button 'Add Folder'
		if (PGV_USER_ACCESS_LEVEL<=$SHOW_ADD_FOLDER )
			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=addfolder"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["add_folder"].'" border="0"></img><br />'.$pgv_lang["add_folder"].'</a></td>';
		//button 'Add Unlinked Source'
//		if (PGV_USER_ACCESS_LEVEL<=$SHOW_ADD_UNLINKED_SOURCE && PGV_USER_CAN_EDIT && empty ($folderid))
//			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="javascript: '.$pgv_lang["add_unlinked_source"].'" onclick="addnewsource(\'\'); return false;"><img src="modules/research_assistant/images/add_task.gif" alt="'.$pgv_lang["add_unlinked_source"].'"border=0"></img><br />'.$pgv_lang["add_unlinked_source"].'</a></td>';
		//button 'View Probabilities'
//		if (PGV_USER_ACCESS_LEVEL<=$SHOW_VIEW_PROBABILITIES && empty ($folderid))
//			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=viewProbabilities"><img src="modules/research_assistant/images/view_inferences.gif" alt="'.$pgv_lang["view_probabilities"].'" border="0"></img><br />'.$pgv_lang["view_probabilities"].'</a></td>';
		//button 'Configure Privacy' for ADMIN ONLY
//		if(PGV_USER_IS_ADMIN && empty ($folderid))
//			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=configurePrivacy"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["configure_privacy"].'" border="0"></img><br />'.$pgv_lang["configure_privacy"].'</a></td>';
		// Below here is "in folder" relevant information. These are only shown when the user is inside a folder.
		if (!empty ($folderid)) {
			// Lets check to see if we can go up a folder.
			$sql = "SELECT * FROM ".$TBLPREFIX."folders WHERE fr_id = ".(int) $folderid;
			$result = dbquery($sql);
			$folderinfo = $result->fetchRow(DB_FETCHMODE_ASSOC);

			// Print folder parent link 
			if (!empty ($folderinfo['fr_parentid']))
				$url = '<a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folderinfo['fr_parentid'].'">';
			else
				$url = '<a href="module.php?mod=research_assistant&amp;action=view_folders">';

			// Finish up the links    
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.$url.'<img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["up_folder"].'" border="0"></img><br />'.$pgv_lang["up_folder"].'</a></td>';
			$out .= '<td align="center" class="optionbox" width="'.$width.'"><a href="module.php?mod=research_assistant&amp;action=editfolder&amp;folderid='.$folderid.'"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["edit_folder"].'" border="0" /><br />'.$pgv_lang["edit_folder"].'</a></td>';
		}

		// Close up
		$out .= '<td align="right" class="optionbox">  </td>';
		$out .= '</tr>';
		//$out .= '</table>';

		return $out;
	}

	/**
	 * Status message display
	 * 
	 * This function prints a lovely formatted confirmation or error message depending on paramaters passed int
	 *
	 * @param mixed $message The message you want to display.
	 * @param bool $status true, false if the message is success or failure. (This controls the image shown either a check mark, or an X)
	 * @return mixed
	 */
	function printMessage($message, $status) {
		// Setup which kind of message it is
		if ($status) {
			$status = "Success!";
			$output="<br/>";
			
			//gets each person that was changed and shows their name with a link to their individual page
			$thePeopleList=split(";",$_REQUEST['personid']);
			foreach($thePeopleList as $i=>$pid) {
				if(!empty($pid)) {
						$person=Person::getInstance($pid);
						$output.='<a href="'.$person->getLinkUrl().'">'.$person->getName().'</a><br/>';
				}
			}
			
			$output.="<br/>";
			$div = "<span class=\"warning\">";
			$end = "</span>";
		} else {
			$status = "Error!";
			$image = "modules/research_assistant/images/xbutton.gif";
			$div = "<div class=\"error\">";
			$end = "</div>";
		}

		// The actual message layout
		$out = '<table align="center" width="50%" height="100" valign="center">';
		$out .= '<tr><td class="optionbox" align="center" valign="center"><img src="'.$image.'" /></td></tr>';
		$out .= '<tr><td class="optionbox" valign="center" align="center"><h3>'.$div.''.$message.''.$end.'</h3></td></tr>';
		$out .= '</table>';

		return $out;
	}

	/**
	 * Prints the edit_Folder info to the user
	 * 
	 * @param mixed $folder_id 
	 * @return mixed
	 */
	function edit_Folder($folder_id) {
		// Get the information from the form and display it to the user
		$editfrm = $this->print_form('ra_EditFolder');

		return $editfrm->content($folder_id);
	}

	/**
	* This is the short Description for the Function
	*
	* @param	string $name Name of the form to display
	* @return	string	 HTML of the form
	*/
	function print_form($name = '') {
		$out = "";
		$ext = "";
		// Make sure we have something to display
		if (!empty ($name)) {

			// Add the php extension if it's not there
			if (strstr($name, ".php") === false)
				$ext = ".php";

			$path = BASEPATH."/forms/".$name.$ext;

			// Display or show an error
			if (file_exists($path)) {
				// Load the form.
				include_once $path;
				$form = new $name ();
				$out = $form->display_form();
				return $out;
			} else {
				return '<div class="error">ERROR: File '.BASEPATH.'forms/'.$name.'.php was not found.</div>';
			}
		}

		// Show error
		if (empty ($out))
			$out = '<div class="error">ERROR: There was a problem loading a form, or no form was specified.</div>';

		return $out;
	}

	/**
	 * Basic print_form function that uses the buffer approach
	 * 
	 * @param mixed $name 
	 * @return mixed
	 */
	function print_simple_form($name) {
		$path = BASEPATH."forms/".$name.".php";

		// Find and print the form otherwise display an error
		if (file_exists($path)) {
			// Load the form.
			return $this->get_include_contents($path);
		} else {
			return "<div class=\"error\">ERROR: File modules/research_assistant/forms/".$name.".php was not found.</div>";
		}
	}

	/**
	 * Gets the contents of a file to display
	 * 
	 * Uses the buffer approach to parse a form and return that via a variable
	 * @param mixed $filename 
	 * @return mixed
	 */
	function get_include_contents($filename) {
		// Print the form if it exists
		if (is_file($filename)) {
			ob_start();
			include $filename;
			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}
		return false;
	}

	/**
	 * Pulls all of the task data from the database 
	 * 
	 * @param mixed $folderId folderId to search for
	 * @param String $orderby If passed, querry the database to order by
	 * @return mixed Results from the database
	 */
	function get_folder_list($folderId, $orderby = "") {
			// Obtain the table prefix from the site
	global $TBLPREFIX;
		$sql = "";

		if (!empty ($orderby)) {
			// Switch order by 
			$this->switch_order();

			$sql = "Select * From ".$TBLPREFIX."tasks Where t_fr_id =".(int) $folderId." order by ".$orderby." ".$_REQUEST["type"];
		} else {
			$sql = "Select * From ".$TBLPREFIX."tasks Where t_fr_id =".(int) $folderId;
		}

		$res = dbquery($sql);

		return $res;
	}

	/**
	 * Displays a list of assigned users tasks
	 * 
	 * 
	 */
	 function print_user_tasks($userName)
	 {
	 	global $res, $pgv_lang, $folderId;
		global $TBLPREFIX;
		$sql = 	"Select * From " .$TBLPREFIX. "tasks where t_username ='".$userName."'";
		$res = dbquery($sql);
		while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$date=new GedcomDate(date("d M Y", $task["t_startdate"]));
			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.PrintReady($task["t_title"]).'</a></td><td class="optionbox">'.$date->Display(false).'</td><td class="optionbox" align="center">'.$this->checkComplete($task).'</td><td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["edit"].'</a></td><td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'&amp;folder='.$folderId.'">'.$pgv_lang["delete"].'</a></td></tr>';
		}
		$out .= '</table>';
		
	 }
	 
	/**
	 * Gets a list of assigned users tasks
	 * 
	 * 
	 */
	 function get_user_tasks($userName)
	 {
	 	global $res, $pgv_lang, $folderId;
		global $TBLPREFIX;
		$sql = 	"Select * From " .$TBLPREFIX. "tasks where t_username ='".$userName."' AND t_enddate IS NULL";
		$res = dbquery($sql);
		$tasks = array();
		while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$tasks[] = $task;
		}
		return $tasks;		
	 }

	/**
	 * Displays a list of folders and their tasks
	 * 
	 * @param mixed $folderId FolderId in the Database
	 * @return string HTML code for the tasks
	 */
	function print_list($folderId, $orderId = "") {
			// Set a defualt order by
	if (!isset ($_REQUEST["type"])) {
			$_REQUEST["type"] = "asc";
		}

		$res = $this->get_folder_list($folderId, $orderId);

		$out = $this->print_tasks($folderId, $res);

		if (empty ($out)) {
			$out = "<div class=\"error\">ERROR: Nothing was found in your database.</div>";
		}

		return $out;
	}
	
	/*
	 * Dipsplay tasks that a user has assigned to them.
	 */
	function print_user_list($userName)
	{
		
		if(!empty($_REQUEST["Filter"]))
			$filter = $_REQUEST["Filter"];
		else $filter = "Incomplete";
		
		global $TBLPREFIX, $pgv_lang;
		if($filter == "All")
		{
			$sql = 	"Select * From ".$TBLPREFIX."tasks where t_username ='".$userName."'";
			$res = dbquery($sql);
			$out = "";
		}
		if($filter == "Incomplete")
		{
			$sql = 	"Select * From ".$TBLPREFIX."tasks where t_username ='".$userName."' AND t_enddate IS NULL";
			$res = dbquery($sql);
			$out = "";
		}
		if($filter == "Completed")
		{
			$sql = 	"Select * From ".$TBLPREFIX."tasks where t_username ='".$userName."' AND t_enddate IS NOT NULL";
			$res = dbquery($sql);
			$out = "";
		}
	 	
	 	$out .= "<table id=\"Tasks\" class=\"list_table\" align=\"center\" width=\"700\" border=\"0\">";
		$out .= "<tr><th colspan=\"7\" class=\"topbottombar\"><h2>".$pgv_lang["Task_View"].print_help_link("ra_view_task_help", "qm", '', false, true)."</h2>";
		$out .= "<form name=\"mytasks\" method=\"GET\" action=\"module.php\">\n";
		$out .= "<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />\n";
		$out .= "<input type=\"hidden\" name=\"action\" value=\"mytasks\" />\n";
		$out .= "<p>".$pgv_lang["FilterBy"].": <select name=\"Filter\" onchange=\"document.mytasks.submit()\">";
		
		$out .= "<option ";
		if ($filter == "All") $out .= "selected=\"selected\" ";
		$out .= "value=\"All\">".$pgv_lang["all"]."</option>";
		
		$out .= "<option ";
		if ($filter == "Completed") $out .= "selected=\"selected\" ";
		$out .= "value=\"Completed\">".$pgv_lang["completed"]."</option>";
		
		$out .= "<option ";
		if ($filter == "Incomplete") $out .= "selected=\"selected\" ";
		$out .= "value=\"Incomplete\">".$pgv_lang["incomplete"]."</option>";
        
		$out .= "</select></form></th></tr>";
		$out .= "<tr><th class=\"descriptionbox\"><a href=\"module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=&amp;orderby=t_title&amp;type=\">".$pgv_lang["Task_Name"]."</a></th><th class=\"descriptionbox\">
				<a href=\"module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=&amp;orderby=t_startdate&amp;type=\">".$pgv_lang["Start_Date"]."</a></th>"."<th class=\"descriptionbox\">
				<a href=\"module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=&amp;orderby=t_enddate&amp;type=\">".$pgv_lang["completed"]."</a></th><th class=\"descriptionbox\">".$pgv_lang["edit"]."</th><th class=\"descriptionbox\">".$pgv_lang["delete"]."</th>\n
				<th class=\"descriptionbox\">".$pgv_lang["complete"]."</tr>";
		
		while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			if (empty ($task['t_enddate'])) {
				$completeLink = "<a href=\"module.php?mod=research_assistant&amp;action=completeTask&amp;taskid=".$task["t_id"]."\">".$pgv_lang["complete"]."</a>";
				$date=new GedcomDate(date("d M Y", $task["t_startdate"]));
				$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.PrintReady($task["t_title"]).'</a></td><td class="optionbox">'.$date->Display(false).'</td><td class="optionbox" align="center">'.$this->checkComplete($task).'</td>
						<td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["edit"].'</a></td>
						<td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["delete"].'</a></td>
						<td class="optionbox" align="center">'.$completeLink.'</td></tr>';
			}
			else
			{
				$completeLink = '<a href="module.php?mod=research_assistant&amp;action=completeTask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["editform"].'</a>';
				$date=new GedcomDate(date("d M Y", $task["t_startdate"]));
				$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.PrintReady($task["t_title"]).'</a></td><td class="optionbox">'.$date->Display(false).'</td><td class="optionbox" align="center">'.$this->checkComplete($task).'</td><td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["edit"].'</a></td><td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["delete"].'</a></td><td class="optionbox" align="center">'.$completeLink.'</td></tr>';
			}
		}
		$out .= '</table>';
		return $out;
	}
	
	/**
	 * Return a folder from the database
	 * 
	 * @param string $orderby If passed, query the database to order by 
	 * @return mixed Results from the database
	 */
	function get_folder($orderby) {
		// Obtain the table prefix from the site
		global $TBLPREFIX;

		// Switch the orderby clause
		if (!empty ($orderby)) {
			$this->switch_order();

			$sql = "Select * From ".$TBLPREFIX."folders Where fr_parentid is null order by ".$orderby." ".$_REQUEST['type'];
		} else {
			$sql = "Select * From ".$TBLPREFIX."folders Where fr_parentid is null";
		}

		$res = dbquery($sql);

		return $res;
	}
	
	/*
	 * Returns if a folder currently has tasks in it and dissallows users from deleting it 
	 * @param takes in a given folder id
	 * 
	 * @returns true or false
	 */
	function folder_hastasks($folderid){
	   global $TBLPREFIX;
	  if(empty($folderid)){
	  return false;
	  } 
	  else{
		  $sql = "SELECT * FROM ".$TBLPREFIX."tasks where t_fr_id =".$folderid;
		  $res = dbquery($sql);
		  
		  //need to process the results...
		  
		  if($res->numRows()==0) {
			return true;
		  }
		  else{
		 	return false;
		  }
	  }
	}
	
	/*
	 * Returns if a folder currently has folders in it and dissallows users from deleting it 
	 * @param takes in a given folder id
	 * 
	 * @returns true or false
	 */
	function folder_hasfolders($folderid){
	   global $TBLPREFIX;
	  if(empty($folderid)){
	  return false;
	  } 
	  else{
		  $sql = "SELECT * FROM ".$TBLPREFIX."folders where fr_parentid =".$folderid;
		  $res = dbquery($sql);
		  
		  //need to process the results...
		  
		  if($res->numRows()==0) {
			return true;
		  }
		  else{
		 	return false;
		  }
	  }
	}
	/**
	 * Switch the orderby clause
	 * 
	 * @return void
	 */
	function switch_order() {
		if ($_REQUEST["type"] == "asc") {
			$_REQUEST["type"] = "desc";
		} else {
			$_REQUEST["type"] = "asc";
		}
	}

	/**
	 * Returns all of the subfolders of a folder from the database
	 * 
	 * @param int $parentId The id of the folder to look inn
	 * @param string $orderby If passed, query  the database to order by
	 * @return mixed Results from the database
	 */
	function get_subfolders($parentId, $orderby = "") {
			// Obtain the table prefix from the site
	global $TBLPREFIX;

		if (!empty ($orderby)) {
			// Switch the orderby clause
			$this->switch_order();

			$sql = "Select * From ".$TBLPREFIX."folders Where fr_parentid = ".(int) $parentId." order by ".$orderby." ".$_REQUEST['type'];
		} else {
			$sql = "Select * From ".$TBLPREFIX."folders Where fr_parentid = '$parentId'";
		}

		$res = dbquery($sql);

		return $res;
	}

	/**
	 * Returns the the top folder from the database
	 *
	 * @param Int $folderId The folder to look for
	 * @return mixed Results from the database 
	 */
	function get_top_folder($folderId) {
		// Pull the table prefix from the site
		global $TBLPREFIX;
		return dbquery("SELECT * FROM ".$TBLPREFIX."folders WHERE fr_id = ".(int) $folderId);
	}

	/**
	 * Display the top folder image and name
	 * 
	 * @param Int $folderId The folder you would like to print at the top of the screenn
	 * @return mixed HTML to print the top folder 
	 */
	function print_top_folder($folderId) {
		$out = "";

		if (!empty ($folderId)) {
			$res = $this->get_top_folder($folderId);

			$out = '<img src="modules/research_assistant/images/folder_blue_icon.gif" alt="Current Folder"></img>';

			$folder = & $res->fetchRow(DB_FETCHMODE_ASSOC);
			$out .= "<strong>".PrintReady(stripslashes($folder["fr_name"]))."</strong>"; //"<br /><strong>Comments: </strong>" . stripslashes($folder["fr_description"]);
		}

		return $out;
	}

	/**
	 * print_folder_view 
	 *
	 * @param int $folderId If passed, Gets the subfolders
		 * @param string $orderby If passed, Query the database with an order by clause
	 * @return string HTML to print out the folder view
	 */
	function print_folder_view($folderId = "", $orderby = "") {
		global $pgv_lang, $TBLPREFIX;
		$out = "";

		if (!isset ($_REQUEST["type"])) {
			$_REQUEST["type"] = "asc";
		}

		// Find the folder or sub folders
		if (empty ($folderId)) {
			$res = $this->get_folder($orderby);
		} else {
			$res = $this->get_subfolders($folderId, $orderby);
		}

		if ($res->numRows() > 0) {
			$out .= '<table class="list_table" align="center" border="0" width="700">';
			$out .= '<tr><th colspan="3" class="topbottombar"><h2>'.$pgv_lang["Folder_View"].print_help_link("ra_fold_name_help", "qm", '', false, true).'</h2></th></tr>';
			$out .= '<tr><th class="descriptionbox"><a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folderId.'&amp;orderbyfolder=fr_name&amp;type='.$_REQUEST["type"].'">'.$pgv_lang["Folder_Name"].'</a></th>';
			$out .= '<th class="descriptionbox"><a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folderId.'&amp;orderbyfolder=fr_description&amp;type='.$_REQUEST["type"].'">'.$pgv_lang["description"].'</a></th>';
			$out .= '<th class="descriptionbox">'.$pgv_lang["edit"].'</th></tr>';
		}

		while ($folders = & $res->fetchRow(DB_FETCHMODE_ASSOC)) {
 			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folders["fr_id"].'"><img src="modules/research_assistant/images/folder_blue_icon.gif" border="0" alt="Folder"></img>'.PrintReady($folders["fr_name"]).'</a></td><td class="optionbox wrap"><br />'.nl2br(PrintReady(stripslashes($folders["fr_description"]))).'</td><td class="optionbox"  align="center"><a href="module.php?mod=research_assistant&amp;action=editfolder&amp;folderid='.$folders["fr_id"].'">'.$pgv_lang["edit"].'</a></td></tr>';
		}
		$out .= '</table>';
		return $out;
	}
	/**
	 * Deletes a folder
	 */
	function deleteFolder($folderid) {
		global $TBLPREFIX;
		$sql = "DELETE from ".$TBLPREFIX."folders WHERE fr_id='".$folderid."'";
		dbquery($sql);
	}
	/**
	 * Display the tasks to the users
	 *
	 * @param int $folderId
		 * @param string $res Result from the database
	 * @return string HTML to print out the folder view
	 */
	function print_tasks($folderId, $res) {
		global $pgv_lang;
		$out = "";

		// Display tasks if we're inside of a folder
		if (!empty ($folderId)) {
			$out .= "<table id=\"Tasks\" class=\"list_table\" align=\"center\" width=\"700\" border=\"0\">";
			$out .= "<tr><th colspan=\"7\" class=\"topbottombar\"><h2>".$pgv_lang["Task_View"].print_help_link("ra_view_task_help", "qm", '', false, true)."</h2></th></tr>";
			$out .= "<tr><th class=\"descriptionbox\"><a href=\"module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=".$folderId."&amp;orderby=t_title&amp;type=".$_REQUEST["type"]."\">".$pgv_lang["Task_Name"]."</a></th>
					<th class=\"descriptionbox\"><a href=\"module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=".$folderId."&amp;orderby=t_startdate&amp;type=".$_REQUEST["type"]."\">".$pgv_lang["Start_Date"]."</a></th>\n
					<th class=\"descriptionbox\"><a href=\"module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=".$folderId."&amp;orderby=t_enddate&amp;type=".$_REQUEST["type"]."\">".$pgv_lang["completed"]."</a></th>\n
					<th class=\"descriptionbox\">".$pgv_lang["edit"]."</th><th class=\"descriptionbox\">".$pgv_lang["delete"]."</th></tr>\n";

		}

		// Loop through the database results and print each task
		while ($task = & $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.PrintReady($task["t_title"]).'</a></td>';
			$date=new GedcomDate(date("d M Y", $task["t_startdate"]));
			$out .= '<td class="optionbox">'.$date->Display(false).'</td>';
			$out .= '<td class="optionbox" align="center">'.$this->checkComplete($task).'</td>';
			$out .= '<td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'">'.$pgv_lang["edit"].'</a></td>';
			$out .= '<td class="optionbox" align="center"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'&amp;folder='.$folderId.'">'.$pgv_lang["delete"].'</a></td></tr>';
		}
		$out .= '</table>';

		return $out;
	}
	/*
	 * Deletes a task
	 */
	function deleteTask($taskid) {
		global $TBLPREFIX;
		$sql = "DELETE from ".$TBLPREFIX."tasks  WHERE t_id='".$taskid."'";
		dbquery($sql);
		$sql = "DELETE from ".$TBLPREFIX."comments  WHERE c_t_id='".$taskid."'";
		dbquery($sql);
		$sql = "DELETE from ".$TBLPREFIX."tasksource  WHERE ts_t_id='".$taskid."'";
		dbquery($sql);
		$sql = "DELETE from ".$TBLPREFIX."taskfacts WHERE tf_t_id='".$taskid."'";
		dbquery($sql);
		$sql = "DELETE from ".$TBLPREFIX."individualtask WHERE it_t_id='".$taskid."'";
		dbquery($sql);
	}
	/**
	 * Scans the form dir and prints out all of the forms that we have to add information with
	 * 
	 * @param int $pid The person id we need to be linking information to
	 * @param bool $links Tells the function to kick out links or just text 
	 * @return mixed The list of links for the forms or false
	 */
	function print_form_list($links = false) {
		$out = '';

		// Display a list of forms for the user
		if ($handle = opendir(BASEPATH."/forms")) {
			while (($file = readdir($handle)) !== false) {
				if (strpos($file, ".")) {
					list ($file, $ext) = explode(".", $file);
					// Make sure we don't show the . and .. nor any forms 
					// that start with ra_ as those are forms that we use internally
					if ($file != "." && $file != ".." && !is_dir($file) && (strstr($file, "ra_") === false)) {
						// Setup pretty print for the users
						$fileNoSpaces = preg_replace('/\s+/', '_', $file);
						$fileSpaces = preg_replace('/_/', ' ', $file);
	
						// Print a link with all the information to connect a person and their data.
						if ($links)
							$out .= '<a href="module.php?mod=research_assistant&amp;action=printform&amp;formname='.$fileNoSpaces.'">'.$fileSpaces.'</a><br/>';
						else
							$out .= $fileSpaces."\n";
					}
				}
			}
			closedir($handle);
		}
		return $out;
	}

	/**
	 * Call any function inside of the form
	 * 
	 * @param string $name Name of the class (Form) to create
	 * @param string $func Which function you want to call
	 * @param mixed $args Any arguments to the function
	 * @return mixed
	 */
	function form_function($name = '', $func = '', $args = null) {
		if (empty ($func) || empty ($name))
			return false;

		// Add the php extension if it's not there
		if (strstr($name, ".php") === false)
			$ext = ".php";

		$path = BASEPATH."/forms/".$name.$ext;
		$out = '';

		if (file_exists($path)) {
			// Perform the function
			include_once $path;
			$form = new $name ();
			$out = $form-> $func ($args);
		}

		return $out;
	}

	/**
	* Function that checks to see if a task is complete.
	* 
	* @param task Supply this with a DB array of a task, it will auto check to see if it's completed.
	* @return mixed
	*/
	function checkComplete($task) {
		// Globals
		global $pgv_lang;
		// If there is no end date the task is not complete.
		if (empty ($task['t_enddate']))
			return $pgv_lang['no'];
		// If there is an end date, it is complete.
		else
			return $pgv_lang['yes'];
	}

	/**
	* Function to complete a task
	* 
	* @param taskid for the task to be completed
	*/
	function completeTask($taskid, $form='') {
		global $TBLPREFIX, $DBCONN, $pgv_lang;
		$sql = "UPDATE ".$TBLPREFIX."tasks SET t_enddate='".time()."', t_form='".$DBCONN->escapeSimple($form)."' WHERE t_id='".$DBCONN->escapeSimple($taskid)."'";
		dbquery($sql);
	}
	/**
	 * Function to find missing information
	 * @param Person $person
	 * @return mixed
	 */
	function getMissinginfo(& $person) {
		global $factarray, $templefacts, $nondatefacts, $nonplacfacts, $pgv_lang;
		
		$perId = $person->getXref();
	
		$MissingReturn = array (); //Local var for the return string
		if ($person->sex == "U") //check for missing sex info
		{
			$MissingReturn[] = array("SEX", $pgv_lang["All"]);
		}
		$birtdate=$person->getBirthDate();
		$birtplac=$person->getBirthPlace();
		if (!$birtplac || !$birtdate->isOK()) { //check for missing birth info
			$probFacts = singleInference($perId,"BIRT");
			$MissingReturn[] = array("BIRT", $pgv_lang["All"],$probFacts);

		}
		$deatdate=$person->getDeathDate();
		$deatplac=$person->getDeathPlace();
		if ((!$deatplac || !$deatdate->isOK()) && $person->isDead()) { //check for missing death info
			$probFacts = singleInference($perId,"DEAT");
			$MissingReturn[] = array("DEAT", $pgv_lang["All"], $probFacts);
	
		}
		if ($person->getGivenNames() == "unknown") {
			$probFacts = singleInference($perId,"GIVN");
			$MissingReturn[] = array("GIVN","",$probFacts);
			
		}
		if ($person->getSurname() == "@N.N.") {
			$probFacts = singleInference($perId,"SURN");
			$MissingReturn[] = array("SURN","",$probFacts);
			
		}

		$indifacts = get_all_subrecords($person->gedrec, "FAMS,FAMC,NOTE,OBJE,SEX,NAME,SOUR,REFN,CHAN,AFN,_UID,_COMM", false);

		foreach ($indifacts as $key => $far) {
			$match = array();
			$ft = preg_match("/1 (\w+)(.*)/", $far, $match);
			if ($ft > 0) {
				$fact = trim($match[1]);
				$event = trim($match[2]);
			}
			if ($fact=="EVEN" || $fact=="FACT") {
				$fact = get_gedcom_value("TYPE", 2, $far);
			}
			$date = get_gedcom_value("DATE", 2, $far);
			if (empty ($date)) {
				if (!in_array($fact, $nondatefacts)) {
					$MissingReturn[] = array ($fact, "DATE");
				}
			}
			$source = get_gedcom_value("SOUR", 2, $far);
			if (empty ($source))
				$MissingReturn[] = array ($fact, "SOUR");
			$plac = get_gedcom_value("PLAC", 2, $far);
			if (empty ($plac)) {
				if (in_array($fact, $templefacts)) {
					$plac = get_gedcom_value("TEMP", 2, $far);
					if (empty($plac)) {
						$MissingReturn[] = array ($fact, "TEMP");
					}
				}
				else {
					if (!in_array($fact, $nonplacfacts)) {
						$probFacts = singleInference($perId,"$fact:PLAC");
						$MissingReturn[] = array ($fact, "PLAC",$probFacts);
					}
				}
			}
		}

		return $MissingReturn; //return of missing info check results empty string if no missing info
	}
	//End of the missing info functions

	/**
	 * Function to print a small table of tasks related to a particular person.
	 * 
	 * @param string Person ID that you want to pull out the tasks for
	 * @return mixed
	 */
	function addTaskPeople() {

	}

	/**
	 * Creates a new individual in the database
	 */
	function add_indi_task($tid, $itid, $ids) {
		global $TBLPREFIX, $DBCONN;
		$sql = 'INSERT INTO '.$TBLPREFIX.'individualtask(it_t_id,it_i_id, it_i_file) '."VALUES ('".$DBCONN->escapeSimple($tid)."', '".$DBCONN->escapeSimple($itid)."', '".$DBCONN->escapeSimple($ids)."')";
		$res = dbquery($sql);
	}

	/**
	 * Adds a souce to an fact
	 */
	function add_sources($task_id, $sources = -1) {
		global $TBLPREFIX, $DBCONN;
		if (!is_array($sources)) {
			$sources = preg_split("/;/", $sources);
		}
		foreach($sources as $s=>$source_id) {
			if (!empty($source_id)) {
				$sql = 'INSERT INTO '.$TBLPREFIX.'tasksource (ts_t_id, ts_s_id) '."VALUES ('".$DBCONN->escapeSimple($task_id)."', '".$DBCONN->escapeSimple($source_id)."')";
				$res = dbquery($sql);
				//-- only allow one source
				break;
			}
		}
	}
	//Add a task into the database with all of the required information
	function add_task($taskid, $folder, $title, $description, $pid, $userName="") {
		global $pgv_lang, $TBLPREFIX, $DBCONN, $GEDCOM, $GEDCOMS;
		//$sql = "SELECT * FROM ".$TBLPREFIX."tasks, ".$TBLPREFIX."individualtask WHERE(t_fr_id= ".$DBCONN->escapeSimple($folder)." and t_title = '".$DBCONN->escapeSimple($title)." AND it_i_file='".$GEDCOMS[$GEDCOM]['id'].")";
		$sql = "SELECT * FROM ".$TBLPREFIX."tasks, ".$TBLPREFIX."individualtask WHERE t_title = '".$DBCONN->escapeSimple($title)."' AND it_t_id=t_id AND it_i_id='".$DBCONN->escapeSimple($pid)."' AND it_i_file='".$GEDCOMS[$GEDCOM]['id']."'";

		$res = dbquery($sql);
		//make sure the same task does not exist already so we can add an individual task
		if ($res->numRows() == 0) {
			$sql = "INSERT INTO ".$TBLPREFIX."tasks (t_id, t_fr_id, t_title, t_description, t_startdate";
			if($userName != "")
				$sql .= ", t_username";
			$sql .= ") "."VALUES ('".$DBCONN->escapeSimple($taskid)."', '".$DBCONN->escapeSimple($folder)."', '".$DBCONN->escapeSimple($title)."', '".$DBCONN->escapeSimple($description)."', '".time()."";
			if($userName != "")
				$sql .= $DBCONN->escapeSimple($userName);
			$sql .= "')";
			$res = dbquery($sql);
			return true;
		} else {
			return false;
		}

	}
     //make sure that they are not trying to add no tasks, then loop through and all selected tasks and add them into the task
     // and individual task tables
     function auto_add_task(&$person,$folderID)
     {
       	global $TBLPREFIX, $GEDCOMS, $GEDCOM;
     	if(!empty($_REQUEST['missingName'])){
     		$missingName = $_REQUEST['missingName'];     	
     	
	     	for($i=0; $i<count($missingName); $i++) {
	     		$nextTaskID =get_next_id("tasks","t_id");
	     		$nextItaskId=get_next_id("individualtask","it_t_id");
	     		$personName = $person->getName();
	     		if($this->add_task($nextTaskID,$folderID,$missingName[$i],$personName." (auto generated)",$person->getXref()))
	     		$this->add_indi_task($nextItaskId,$person->getXref(),$GEDCOMS[$GEDCOM]["id"]);
	     	
     		//print $missingName[$i];
     		}
     	}
     }
	

	//the inferences function will look for correlations 
	//and return an array with each probability
	//so, it will have a meaningful index system such as ['FATHER:BIRT:PLAC']
	//to return the probability that an individual will have the same 
	//birth place as their father or ['BIRT:MARR:DEAT'] for the probability that 
	//an individual will have the same birth, marriage and death place
	//at each index of the array will be a description as well as a percentage liklihood of the given correlation
	function inferences() {
		global $DBCONN, $TBLPREFIX, $GEDCOMS, $GEDCOM;
		
		require_once("modules/research_assistant/ra_ViewInferencesArray.php");
		$indilist = get_indi_list();
		$famlist = get_fam_list();

		//various counts
		$total = count($indilist); 
		$nnCount = 0;
		$tempCount = 0;
		$tempInd = 0;
		$malesCount = 0;
		$femalesCount = 0;

		foreach ($indilist as $pid => $indi) {
			//assign surname, gender, birthplace and occupation for the individual
			$gender = get_gedcom_value("SEX", 1, $indi['gedcom'], '', false);
			$locals = array();
			foreach($inferences as $pr_id=>$value) {
				//-- get the local value from the the individual record
				if (!isset($locals[$value['local']])) {
					if ($value['local']=='SURN') $locals['SURN'] = $indi['names'][0][2];
					else if ($value['local']=='GIVN'){
						$parts = preg_split("~/~", $indi['names'][0][0]);
						$locals['GIVN'] = $parts[0];
					}
					else {
						$localvalue = get_gedcom_value($value['local'], 1, $indi['gedcom'], '', false);
						if ( (strpos($value['local'],':PLAC') !== false) && $localvalue) {
							// this is a PLAC string, trim it to a consistent number of levels
							$localvalue = trimLocation($localvalue);
						}               
						$locals[$value['local']] = $localvalue;
					}
				}
				
				//-- load up the gedcom record we want to compare the data from
				//-- record defaults to the indis record, after this section runs it will be 
				//-- set to the record from the inferences table that we want to compare the value to
				$record = $indi['gedcom'];
				if ($value['record']!='') {
					$rec_tags = preg_split("/:/", $value['record']);
					for($i=0; $i<count($rec_tags); $i++) {
						$tag = $rec_tags[$i];
						if ($tag=="SPOUSE") {
							$parents = find_parents_in_record($record);
							if ($parents['HUSB']==$pid) $id = $parents['WIFE']; 
							else $id = $parents['HUSB'];
							if (empty($id)) $record = '';
							else {
								if (isset($indilist[$id])) $record = $indilist[$id]['gedcom'];
								else $record = '';
							}
						}
						else {
							$match = array();
							$ct = preg_match("/1 $tag @(.*)@/", $record, $match);
							if ($ct==0) $record = "";
							else {
								$id = $match[1];
								if (isset($indilist[$id])) $record = $indilist[$id]['gedcom'];
								else if (isset($famlist[$id])) $record = $famlist[$id]['gedcom'];
								else $record = find_gedcom_record($id);
							}
						}
					}
				}
			
				if (!empty($record)) {
					if (preg_match("/SURN/", $value['comp'])) {
						$ct = preg_match("/0 @(.*)@/", $record, $match);
						if ($ct>0) {
							$gid = $match[1];
							$gedval = $indilist[$gid]['names'][0][2];
							if (str2lower($locals[$value['local']])==str2lower($gedval)) $inferences[$pr_id]['value']++;
							$inferences[$pr_id]['count']++;
						}
					}
					else if (preg_match("/GIVN/", $value['comp'])) {
//						$gender1 = get_gedcom_value("SEX", 1, $indi['gedcom'], '', false);
//						$gender2 = get_gedcom_value("SEX", 1, $record, '', false);
//						if ($gender1==$gender2) {
							$ct = preg_match("/0 @(.*)@/", $record, $match);
							if ($ct>0) {
								$gid = $match[1];
								$parts = preg_split("~/~", $indilist[$gid]['names'][0][0]);
								$gedval = $parts[0];
								$parts1 = preg_split("/\s+/", $gedval);
								$parts2 = preg_split("/\s+/", $locals['GIVN']);
								foreach($parts1 as $p1=>$part1) {
									foreach($parts2 as $p2=>$part2) {
										if (str2lower($part1)==str2lower($part2)) $inferences[$pr_id]['value']++;
										$inferences[$pr_id]['count']++;
									}
								}
							}
//						}
					}
					else {
						$gedval = get_gedcom_value($value['comp'], 1, $record, '', false);
						if (!empty($gedval) && !empty($locals[$value['local']])) {

							if ( (strpos($value['comp'],':PLAC') !== false) && $gedval) {
								// this is a PLAC string, trim it to a consistent number of levels
								$gedval = trimLocation($gedval);
							}               

							$inferences[$pr_id]['count']++;
							if (str2lower($locals[$value['local']])==str2lower($gedval)) {
								$inferences[$pr_id]['value']++; 
//								if (strpos($value['local'],':PLAC') !== false) { print "<p>".$value['local']."-".$locals[$value['local']]."<br />".$value['comp']."-".$gedval."<br />SAME!</p>"; }               
//							} else {
//								if (strpos($value['local'],':PLAC') !== false) { print "<p>".$value['local']."-".$locals[$value['local']]."<br />".$value['comp']."-".$gedval."<br />DIFFERENT</p>"; }
							}
						}
					}
				}
			}
		}
		/*
		 *The following section is used to store the calculated percentages in the database
		 */
		$sql = "DELETE FROM ".$TBLPREFIX."probabilities WHERE pr_file=".$GEDCOMS[$GEDCOM]['id'];
		$res = dbquery($sql);
		
		/**
		 * pr_id int unsigned NOT NULL auto_increment, 
		 * pr_f_lvl varchar(200) NOT NULL, 
		 * pr_s_lvl varchar(200), 
		 * pr_rel varchar(200) NOT NULL, 
		 * pr_matches int
		 * pr_count int
		 * pr_file INT
		 */
		 foreach($inferences as $pr_id=>$value) {
			$sql = "INSERT INTO ".$TBLPREFIX."probabilities VALUES ('".$DBCONN->escapeSimple(get_next_id("probabilities", "pr_id"))."'," .
					"'".$DBCONN->escapeSimple($value['local'])."'," .
					"'".$DBCONN->escapeSimple($value['record'])."'," .
					"'".$DBCONN->escapeSimple($value['comp'])."'," .
					"'".$DBCONN->escapeSimple($value['value'])."'," .
					"'".$DBCONN->escapeSimple($value['count'])."'," .
					"'".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]['id'])."')";
			$res = dbquery($sql);
		 }
		//print_r($inferences); 
		return $inferences;
	}


	/**
	 * codeToLang converts the GedCom code to its associated text
	 */
	function codeToLang($array) {
		global $factarray;

	}

	//check to see which of the suggested research tasks have already been added into the database
	function task_check($title, $pid) {
		global $TBLPREFIX, $DBCONN, $GEDCOM, $GEDCOMS;
		$sql = "SELECT t_id FROM ".$TBLPREFIX."tasks, ".$TBLPREFIX."individualtask WHERE t_title = '".$DBCONN->escapeSimple($title)."' AND it_t_id=t_id AND it_i_id='".$DBCONN->escapeSimple($pid)."' AND it_i_file='".$GEDCOMS[$GEDCOM]['id']."'";
		$res = dbquery($sql);
		if ($res->numRows() == 0)
			return false;
		$row = $res->fetchRow();
		return $row[0];
	}
	
	// call the already created get_folder() function and for each one found create a new option tag to insert into the 
	// select tag
	function folder_search() {
		global $DBCONN, $TBLPREFIX, $pgv_lang;
		//TODO: Figure out how to display folder Heiarchies correctly
		$res = $this->get_folder("");
		$out = "";
		while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$out .= "<option value=".$row['fr_id']." selected=Selected>".$row['fr_name']."</option>";
		}
		return $out;
	}

	/**
	 * Get Tasks for Source
	 */
	function getSourceTasks($sId) {
		global $pgv_lang, $TBLPREFIX, $GEDCOMS, $GEDCOM, $DBCONN;
		global $indilist;
		global $factarray;
        	
		$sql = "SELECT * FROM ".$TBLPREFIX."tasks, ".$TBLPREFIX."tasksource, ".$TBLPREFIX."sources WHERE t_id=ts_t_id AND s_id=ts_s_id AND s_id='".$DBCONN->escapeSimple($sId)."' AND s_file=".$GEDCOMS[$GEDCOM]['id'];
		$res = dbquery($sql);
	
		$out = "\n\t<table class=\"list_table\">";
		$out .= "<tr><td class=\"topbottombar\" colspan=\"4\" align=\"center\">".print_help_link("task_list_text", "qm", '', false, true)."<b>".$pgv_lang['task_list']."</b></td></tr>\n";
		if ($res->numRows()==0) $out .= "<tr><td class=\"topbottombar\" colspan=\"4\" align=\"center\">".$pgv_lang['no_sour_tasks']."</td></tr>\n";
		else { 
			$out .= "\n\t\t<tr><td class=\"list_label\"><strong>".$pgv_lang["details"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["title"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["completed"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["created"]."</strong></td></tr>";
			// Loop through all the task ID's and pull the info we need on them,
			// then format them nicely to show the user.
			while ($taskid = $res->fetchrow(DB_FETCHMODE_ASSOC)) {
				$date=new GedcomDate(date("d M Y", $taskid["t_startdate"]));
				$out .= '<tr><td class="list_label"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$taskid['t_id'].'">'.PrintReady($pgv_lang['details']).'</a></td><td class="list_label">'.PrintReady($taskid['t_title']).'</td><td class="list_label">'.$this->checkComplete($taskid).'</td><td class="list_label">'.$date->Display(false).'</td></tr>';
			}
		}
		return $out;
	
	}
	
	function autoSearchFormOptions() {
		//Load up off site search names here
		//Auto Search Plugin: To load up a new plugin follow the format for the two entries shown below
		// ex $sites["myplugin.php"] = "mywebsite.com";
		$this->sites = array();
		$this->sites["ancestry.php"] = "Ancestry.com";
		$this->sites["ancestrycouk.php"] = "Ancestry.co.uk";
		$this->sites["familysearch.php"] = "FamilySearch.org";	
		$this->sites["genealogy.php"] = "Genealogy.com";	
		$this->sites["ellisisland.php"] = "EllisIslandRecords.org";	
		$this->sites["geneanet.php"] = "GeneaNet.org";
		$this->sites["werelate.php"] = "Werelate.org";
		$this->sites["gensearchhelp.php"] = "Genealogy-Search-Help.com";
		$opts = "";
		$optCount = 1;
			//load up the options into the html
			foreach($this->sites as $key=>$value) 
			{
			    $opts .=	"<option value=\"".$key."\" class=\"".$optCount."\">".$value."</option>\n";
			    $optCount+=1;
			}
		return $opts;
	}
	
	function determineClosest(&$currentDate, $dateToCompare, $dateCompareAgainst )
	{
		if($dateCompareAgainst > $dateToCompare)
		{
			$compareDiff = $dateCompareAgainst - $dateToCompare;
		}
		else
		{
			$compareDiff = $dateToCompare - $dateCompareAgainst;
		}
		if($dateCompareAgainst > $currentDate)
		{
			$currentDiff = $dateCompareAgainst - $currentDate;
		}
		else
		{
			$currentDiff = $currentDate - $dateCompareAgainst;
		}
		
		if($compareDiff < $currentDiff)
		{
			return $dateToCompare;
		}
		else
		{
			return $currentDate;
		}
		//var_dump($people);
		return $people;
	}

	/**
	 * tab is the function that builds the display for the different screens.
	 * These screens are identified by a tab
	 * @param Person $person
	 */
	function tab(&$person) {
		// Start our engines.
		global $pgv_lang, $TBLPREFIX, $DBCONN, $GEDCOMS, $GEDCOM;
		global $indilist;
		global $factarray;
		
		if (!is_object($person)) return "";
		$givennames = $person->getGivenNames();
		$lastname = $person->getSurname();
		$bdate = $person->getEstimatedBirthDate();
		$ddate = $person->getEstimatedDeathDate();
		$byear = $bdate->gregorianYear();
		$dyear = $ddate->gregorianYear();

		if (isset ($_REQUEST['action']) && $_REQUEST['action'] == 'ra_addtask')
			$this->auto_add_task($person, $_POST['folder']);

		// gets task id from the database
		$sql = "SELECT * FROM ".$TBLPREFIX."individualtask WHERE it_i_id = '".$DBCONN->escapeSimple($person->getXref())."' AND it_i_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]['id'])."'";		
		$res = dbquery($sql);

		if (PEAR::isError($res)) {
			return "<span class=\"error\">There was an error with the Research Assistant database.  Click on the <a href=\"module.php?mod=research_assistant\">Research Assistant</a> icon to create the database.<br />&nbsp;</span>";
		}
		// Start of HTML output
		$out = "\n\t<table class=\"list_table\">";
		$out .= "<tr><td class=\"topbottombar\" colspan=\"4\" align=\"center\">".print_help_link("task_list_text", "qm", '', false, true)."<b>".$pgv_lang['task_list']."</b></td></tr>";
		if ($res->numRows()==0) $out .= "<tr><td class=\"topbottombar\" colspan=\"4\" align=\"center\">".$pgv_lang['no_indi_tasks']."</td></tr>";
		else { 
			$out .= "\n\t\t<tr><td class=\"list_label\"><strong>".$pgv_lang["details"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["title"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["completed"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["created"]."</strong></td></tr>";
			// Loop through all the task ID's and pull the info we need on them,
			// then format them nicely to show the user.
			while ($taskid = $res->fetchrow(DB_FETCHMODE_ASSOC)) {
				$sql = "SELECT * FROM ".$TBLPREFIX."tasks WHERE t_id = '".$taskid['it_t_id']."'";
				$result = dbquery($sql);
				if ($result->numRows()>0) {
					$task = $result->fetchrow(DB_FETCHMODE_ASSOC);
					$task = db_cleanup($task);
					$date=new GedcomDate(date("d M Y", $task["t_startdate"]));
					$out .= '<tr><td class="list_label"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task['t_id'].'">'.$pgv_lang['details'].'</a></td><td class="list_label">'.PrintReady($task['t_title']).'</td><td class="list_label">'.$this->checkComplete($task).'</td><td class="list_label">'.$date->Display(false).'</td></tr>';
				}
				$result->free();
			}
		}
		
		//This is where the missing info check will happen
		$Missing = $this->getMissinginfo($person);
		$out .= "<tr><td class=\"topbottombar\" colspan=\"4\"><a href=\"module.php?mod=research_assistant&amp;action=addtask&amp;pid=".$person->getXref()."\">".$pgv_lang["task_entry"]."</a></td></tr></table>\n";
				//beginning of the missing information table, which gets populated with missing information for that individual and allows the user to "autoadd" tasks
				//a checkbox to view link conversion is included if a piece of missing information is already auto tasked
		$out .='<table align="center"><tr><td valign="top">
						<form name="autotask" action="individual.php" method="post">
							<table border="0">
									<input type="hidden" name="pid" value="'.$person->getXref().'" />
									<input type="hidden" name="action" value="ra_addtask" />
									<tr>
										<td align="center" colspan="2" class="topbottombar">'.print_help_link("ra_missing_info_help", "qm", '', false, true).'<b>'.$pgv_lang['missing_info'].
										'</td>
									</tr>';
										$bdatea = $person->getEstimatedBirthDate();
										$bdatea = $bdatea->MinDate();
										$bdatea = $bdatea->convert_to_cal('gregorian');
										$bdate  = $bdatea->Format('Y');
										$bdate .= ($bdatea->m) ? $bdatea->Format('m') : '00';
										$bdate .= ($bdatea->d) ? $bdatea->Format('d') : '00';

										$ddatea = $person->getEstimatedDeathDate();
										$ddatea = $ddatea->MinDate();
										$ddatea = $ddatea->convert_to_cal('gregorian');
										$ddate  = $ddatea->Format('Y');
										$ddate .= ($ddatea->m) ? $ddatea->Format('m') : '00';
										$ddate .= ($ddatea->d) ? $ddatea->Format('d') : '00';
										
										$sourcesInferred = array();
										$sourcesPrinted = array();
										foreach ($Missing as $key => $val) //every missing item gets a checkbox , so you check check it and make a task out of it
										{
											$additionalInfer = array();
											$highest = 0;
											$factsExist = false;
											$compiled = "";
											$tasktitle = "";
											
											if (isset($factarray[$val[0]])) $tasktitle .= $factarray[$val[0]]." ";
											else if (isset($pgv_lang[$val[0]])) $tasktitle .= $pgv_lang[$val[0]]." ";
											else $tasktitle .= $val[0]." ";
											//print_r($factarray);
											
											if (isset($factarray[$val[1]])) $tasktitle .= $factarray[$val[1]];
											else if (isset($pgv_lang[$val[1]])) $tasktitle .= $pgv_lang[$val[1]];
											else $tasktitle .= $val[1];
											$taskid = $this->task_check($tasktitle, $person->getXref());
											if (!$taskid) // if the task_check passes, create a check box
												{
												$out .= "<tr><td width=\"20\" class=\"optionbox\"><input type=\"checkbox\" name=\"missingName[]\" value=\"".$tasktitle."\" /></td><td class=\"optionbox\">\n";
												$out .= "<span class=\"fact_label\">".$tasktitle."</span><br />";
												
												if(isset($val[2])){	
													foreach($val[2] as $inferKey=>$inferenceObj)
													{
														
															if($val[1] === $pgv_lang['All'] || empty($val[1]) || $val[1] === "PLAC")
															{
															
																if(strstr($inferenceObj->getFactTag(),$val[0]) && $inferenceObj->getAverage() > 0)
																{
																	if($inferenceObj->getFactValue() != "")
																	{
																		$additionalInfer[] = $inferenceObj;
																	}
																	
																	if($highest < $inferenceObj->getAverage() && $inferenceObj->getFactValue() != "")
																	{
																		
																		$compiled = array();
																		$highest = $inferenceObj->getAverage();
																		//print($highest."<br/>");
																		$compiled[0] = $this->decideInferSentence($inferenceObj->getAverage(),$inferenceObj->getFactTag());
																		$compiled[0] .= " <i>".$inferenceObj->getFactValue()."</i>";
																		$compiled[0] .= "<br />";
																		
																		if($inferenceObj->getFactTag() === "DEAT")
																		{
																			$posSources = $this->getEventsForDates($ddate - 5,$ddate+5,"",$inferenceObj->getFactValue());
																		}
																		else
																		{
																			if($inferenceObj->getFactTag() === "BIRT")
																			{
																				$posSources = $this->getEventsForDates($bdate-5,$bdate+5,"",$inferenceObj->getFactValue());
																			}
																			else
																			{
																				$posSources = $this->getEventsForDates($bdate,$ddate,"",$inferenceObj->getFactValue());
																			}
																		}
																																				
																		if(count($posSources) > 0)
																		{
																			
																			$compiled[0] .= $pgv_lang["ThereIsChance"]." ";
																			foreach($posSources as $sKey=>$sVal)
																			{
																				if(!in_array($sVal["id"],$sourcesInferred))
																				{
																					$sourcesInferred[$sVal["id"]] = $sVal;
																				}
																				
																				
																				$compiled[0] .= $sVal["description"]."<br />";
																			}
																		}
															
																		$compiled[1] = $inferenceObj->getFactTag();
																		$compiled[2] = $inferenceObj->getAverage();
																		$compiled[3] = $inferenceObj->getFactValue();
																		
																	}
																
																}
															}														
													}
													if(isset($compiled[0]))
													{
													$out .= $compiled[0];
													}
													if(!empty($additionalInfer))
													{
														$additionalFacts = false;
														$tempAdditional = "";
		
														foreach($additionalInfer as $addKey=>$addVal)
														{
															
															if($addVal->getFactValue() !== $compiled[3])
															{
															
																		if($addVal->getFactTag() === "DEAT")
																		{
																			$posSources = $this->getEventsForDates($ddate - 5,$ddate+5,"",$addVal->getFactValue());
																		}
																		else
																		{
																			if($inferenceObj->getFactTag() === "BIRT")
																			{
																				$posSources = $this->getEventsForDates($bdate-5,$bdate+5,"",$addVal->getFactValue());
																			}
																			else
																			{
																				$posSources = $this->getEventsForDates($bdate,$ddate,"",$addVal->getFactValue());
																			}
																		}
																		if(count($posSources) > 0)
																		{
																			
																			$compiledSources = "";
																			$compiledSources .= $pgv_lang["ThereIsChance"]." ";
																			foreach($posSources as $sKey=>$sVal)
																			{
																				
																				if(!in_array($sVal["id"],$sourcesInferred))
																				{
																					
																					$sourcesInferred[$sVal["id"]] = $sVal;
																				}
																				$compiledSources .= $sVal["description"]."<br />";
																			}
																		}
																$additionalFacts = true;
																$tempAdditional .= $this->decideInferSentence($addVal->getAverage(),$addVal->getFactTag());
																$tempAdditional .= ' <i>'.$addVal->getFactValue().'</i><br />';
																if(!empty($compiledSources)) $tempAdditional .= $compiledSources;
															}
														}
														if($additionalFacts)
														{
															$out .= '<br /><a href="" class="showit">'.$pgv_lang["More"].'<span>';
															$out .= $tempAdditional;
														}
													}
													
												}
												
												$out .= "</td></tr>";
												
												
												
												
											} else // if not allow user to view the already created task
								
												$out .= "<tr><td width=\"20\" class=\"optionbox\"><a href=\"module.php?mod=research_assistant&amp;action=viewtask&amp;taskid=$taskid\">View</a></td><td class=\"optionbox\">".$tasktitle."</td></tr>\n";
										}
										$factLookups = $this->getPlacesFromPerson($person);
										$person->add_family_facts(false);
										$tempDates = $person->getIndiFacts();
										
										foreach($sourcesInferred as $sKey=>$sVal)
										{
												$sourcesPrinted[$sVal["id"]] = $sVal;
												$out .= "<tr ><td width=\"20\" class=\"optionbox\">";
												$out .= "<input type=\"checkbox\" name=\"missingName[]\" value=\"".htmlentities($sVal["description"])."\" />";
												$out .= "<td class=\"optionbox\">".$sVal["description"];	
												
												foreach($tempDates as $tKey=>$tVal)
												{
													
													if(empty($greatest))
													{
														
														$tempGreatest = get_gedcom_value("DATE",2,$tVal[1]);
														print($tempGreatest);
													}
												}
												
												$out .= "</td></tr>";
										}
										
										
										foreach($factLookups as $factLKey=>$factLValue)
										{
											$tempVal = trim($factLValue);
												//print($tempVal."||");
												$events = $this->getEventsForDates($bdate,$ddate,"",$tempVal);
											if(count($events) > 0)
											{
												foreach($events as $eventKey=>$eventVal)
												{
													if(!isset($sourcesInferred[$eventVal["id"]]))
													{
														$sourcesPrinted[$eventVal["id"]] = $eventVal;
														$out .= "<tr ><td width=\"20\" class=\"optionbox\">";
														$out .= "<input type=\"checkbox\" name=\"missingName[]\" value=\"".htmlentities($eventVal["description"])."\" />";
														$out .= "<td class=\"optionbox\">".$eventVal["description"];
													foreach($tempDates as $tKey=>$tVal)
													{
														
														if(empty($greatest))
														{
															$tempGreatest = get_gedcom_value("DATE",2,$tVal[1]);
															print($tempGreatest);
														}
													}
														$out .= "</td></tr>";											
													}
													
												}
												
											}
										}
										
										$genericEvents = $this->getEventsForDates($bdate,$ddate);
										$lastPlace = null;
										foreach($genericEvents as $gKey=>$gVal)
										{
											if(!isset($sourcesPrinted[$gVal["id"]]))
											{
												$closest = null;
												$offset = null;
												$place = null;
														
												foreach($tempDates as $tKey=>$tVal)
												{
													$tempDate = get_gedcom_value("DATE",2,$tVal[1]);
													$tempPlace = get_gedcom_value("PLAC",2,$tVal[1]);
													$parsedDates = new GedcomDate($tempDate);
													$parsedDates = $parsedDates->MinDate();
													$parsedDates = $parsedDates->convert_to_cal('gregorian');

													$sortdate = $parsedDates->Format('Y');
													$sortdate.=($parsedDates->m) ? $parsedDates->Format('m') : '00';
													$sortdate.=($parsedDates->d) ? $parsedDates->Format('d') : '00';

													$place = trim($place);
														
													if(empty($closest))
													{
														$closest = $sortdate;
														$place = $tempPlace;
														$lastPlace = $place;														
													}
													else
													{
														$temp = $closest;
														$closest = $this->determineClosest($closest,$sortdate,$gVal["startdate"]);
														
														if($closest != $temp && !empty($tempPlace))
														{
															$place = $tempPlace;
															$lastPlace = $place;	
														}
																											
													}
												}
												
												$out .= "<tr ><td width=\"20\" class=\"optionbox\">";
												$out .= "<input type=\"checkbox\" name=\"missingName[]\" value=\"".htmlentities($gVal["description"])." ".$place."\" />";
												$out .= "<td class=\"optionbox\">".$gVal["description"];
												if(empty($place))
												{
													if(!empty($lastPlace))
													{													
														$out .= "<br/>".$pgv_lang["TheMostLikely"]." <i>".$lastPlace."</i>";
													}
												}
												else
												{
														$out .= "<br/>".$pgv_lang["TheMostLikely"]." <i>".$place."</i>";
												}
														$out .= "</td></tr>";	
											}
										}
									
									
										// Create the selection box and add all the folder names and values
										$out .= "<tr><td class=\"optionbox\" colspan=\"2\" align=\"center\"><h5>".$pgv_lang['Folder']."&nbsp;&nbsp;</h><select name=\"folder\">";
										$out .= $this->folder_search();
										$out .= "</select></td></tr>";
										$out .= "<tr><td colspan=\"2\" class=\"topbottombar\"><input type=\"submit\" value=\"".$pgv_lang["AddTask"]."\" /></td></tr>
							</table>\n
						</form>\n
					</td>
					<td width=\"5%\"><br /></td>\n<td valign=\"top\">
						";
		//Beginning of the auto search feature which gets dynamically populated with an individuals information to be sent to ancestry or familySearch
		$out .= "		\n<table border=\"0\">
								<tr>
									<td align=\"center\" class=\"topbottombar\" colspan=\"2\" height=\"50%\"><b>".print_help_link("auto_search", "qm", '', false, true)."<b>".$pgv_lang['auto_search_text']."</b>

									</td>
								</tr>				 				
		 						<tr>
									<td class=\"topbottombar\">
										<form name=\"selector\" action=\"\" method=\"post\" onsubmit=\"return false;\">
					 					<select name=\"cbosite\" onchange=\"search_selector('".$person->getXref()."');\">
										" .$this->autoSearchFormOptions().														
										"</select> 
										</form>
									</td>
								</tr>
							<tr><td>\n
							
							<div id=\"searchdiv\">";
							foreach($this->sites as $file=>$value) break;
							include_once("modules/research_assistant/search_plugin/".$file);
							$out .=  autosearch_options();
							$out .= "</div>
							</td></tr>\n
							</table>\n
					</td></tr></table>";
			

		//Beginning of the comments feature
		if (!empty($_REQUEST['action']) && $_REQUEST['action']=='delete_comment' && !empty($_REQUEST['uc_id'])) {
			$sql = "DELETE FROM ".$TBLPREFIX."user_comments WHERE uc_id=".$_REQUEST['uc_id'];
			$res = dbquery($sql);
		}
		$out .= '<br /><br />
		<table width="50%" align="center"><tr><td class="topbottombar">'.$pgv_lang['comments'].'</td></tr>';
		$out .= '<tr><td class="optionbox">';
		// Display comments
		$sql = "select uc_id, uc_username, uc_datetime, uc_comment from ".$TBLPREFIX . "user_comments WHERE uc_f_id='".$GEDCOMS[$GEDCOM]['id']."' AND uc_p_id='" . $person->getXref() . "' ORDER BY uc_datetime DESC";
		$res = dbquery($sql);
		$out .= "";

		while($comment = $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$comment = db_cleanup($comment);
			$date=new GedcomDate(date("d M Y", (int)$comment["uc_datetime"]));
			$out .= '<div class="blockcontent"><div class="person_box" id="comment1"><span class="news_title">' .
					$comment["uc_username"].'' . 	// INSERT username
					'</span><br /><span class="news_date">' .
					$date->Display(false).' - '. date("g:i:s A",(int)$comment["uc_datetime"]).		// INSERT datetime
					'</span><br /><br />' .
					nl2br($comment["uc_comment"]).
					'<hr size="1" />';
					
			if(PGV_USER_IS_ADMIN || PGV_USER_NAME==$comment["uc_username"]){
				$out .= '<a href="javascript:;" onclick="editcomment('.
							$comment["uc_id"].', \''.$person->getXref().'\'' .	// INSERT commentid
							')">'.$pgv_lang["edit"].'</a> | <a href="" onclick="confirm_prompt(\''.$pgv_lang["comment_delete_check"].'\', ' .
							$comment["uc_id"].	// INSERT commentid
							', \''.$person->getXref().'\'); return false;">'.$pgv_lang["delete"].'</a>';
			}
			$out .= '<br /></div></div><br />';
		}
		$out .= '</td></tr><tr><td class="topbottombar">';
		$out .= '<form action="" onsubmit="return false;">
				<input type="button" value="'.$pgv_lang["add_new_comment"].'" onclick="window.open(\'editcomment.php?pid='.$person->getXref().'\', \'\',\'top=50,left=50,width=600,height=400,resizable=1,scrollbars=1\');"></form>';
		$out .= '</td></tr></table>';		
		$out .= "\n\t<br /><br />";
		// Return the goods.		 	
		return $out;
	}
	
	function parseMonthsToInt($month)
	{
		switch($month){
			case "JAN": return 01;
			break;
			case "FEB": return 02;
			break;
			case "MAR": return 03;
			break;
			case "APR": return 04;
			break;
			case "MAY": return 05;
			break;
			case "JUN": return 06;
			break;
			case "JUL": return 07;
			break;
			case "AUG": return 08;
			break;
			case "SEP": return 09;
			break;
			case "OCT": return 10;
			break;
			case "NOV": return 11;
			break;
			case "DEC": return 12;
			break;
			default: return 00;
			break;
			
		}
	}
	
	/*
	 * This will return an array of comma delimited lists of all the PLAC facts for a person
	 * 
	 * @person The object for the person you are looking for.
	 * 
	 * @return An array containing comma delimited lists of all the PLAC facts found in the GEDCOM for this person.
	 */
	function getPlacesFromPerson($person){
		/*@var $person Person*/
		//Get the GEDCOM for the person
		$personGedcom = $person->getGedcomRecord();
		//Get all the Places for that person
		preg_match_all("/2 PLAC (.*)/",$personGedcom,$places,PREG_SET_ORDER);
		
		$returnPlaces = array();
		for($i = 0; $i < count($places);$i++)
		{
			if(!in_array($places[$i][1],$returnPlaces))
			{
				$returnPlaces[] = $places[$i][1];
			}
		}
		
		return $returnPlaces;
	}
	
	function decideInferSentence($percentage,$fact)
	{
		global $pgv_lang;
		if($fact == "BIRT:PLAC")
		{
			
			$percentage = sprintf("%.2f%%",$percentage * 100);
			
			$tempOut = $pgv_lang["InferIndvBirthPlac"];
			$tempOut = preg_replace("/%PERCENT%/",$percentage,$tempOut);
			return $tempOut;
		}
		if($fact == "DEAT:PLAC")
		{	
			
			$percentage = sprintf("%.2f%%",$percentage * 100);
			
			$tempOut = $pgv_lang["InferIndvDeathPlac"];
			$tempOut = preg_replace("/%PERCENT%/",$percentage,$tempOut);
			return $tempOut;
		}
		if($fact == "SURN")
		{	
			$percentage = sprintf("%.2f%%",$percentage * 100);
			
			$tempOut = $pgv_lang["InferIndvSurn"];
			$tempOut = preg_replace("/%PERCENT%/",$percentage,$tempOut);
			return $tempOut;
		}
		if($fact == "MARR:PLAC")
		{	
			
			$percentage = sprintf("%.2f%%",$percentage * 100);
			
			$tempOut = $pgv_lang["InferIndvMarriagePlace"];
			$tempOut = preg_replace("/%PERCENT%/",$percentage,$tempOut);
			return $tempOut;
		}
		if($fact == "GIVN")
		{	
			
			$percentage = sprintf("%.2f%%",$percentage * 100);
			
			$tempOut = $pgv_lang["InferIndvGivn"];
			$tempOut = preg_replace("/%PERCENT%/",$percentage,$tempOut);
			return $tempOut;
		}
	}
	
	
	/**
	 * function to handle fact edits/updates for task completion forms
	 * this function is hooked in from the edit_interface.php file.
	 * It requires at least that a ctype variable be put on the request
	 */
	function edit_fact() {
		global $templefacts, $nondatefacts, $nonplacfacts;
		global $factarray, $pgv_lang, $tag, $MULTI_MEDIA;
		
		if ($_REQUEST['ctype']=='add' && !empty($_REQUEST['fact'])) {
			$fact = $_REQUEST['fact'];
			if (!empty($_REQUEST['type'])) $type = $_REQUEST['type'];
			else $type='indi';
			
			init_calendar_popup();
			print "<form method=\"post\" action=\"edit_interface.php\" enctype=\"multipart/form-data\">\n";
			print "<input type=\"hidden\" name=\"action\" value=\"mod_edit_fact\" />\n";
			print "<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />\n";
			print "<input type=\"hidden\" name=\"ctype\" value=\"update\" />\n";
			print "<input type=\"hidden\" name=\"type\" value=\"$type\" />\n";
			
			print "<br /><input type=\"submit\" value=\"".$pgv_lang["add"]."\" /><br />\n";
		
			print "<table class=\"facts_table\">";
			
			create_add_form($fact);
			
			print "</table>";
		
			if ($fact!="OBJE") {
				if ($fact!="NOTE") print_add_layer("NOTE");
				if ($fact!="REPO") print_add_layer("OBJE");
			}
		
			print "<br /><input type=\"submit\" value=\"".$pgv_lang["add"]."\" /><br />\n";
			print "</form>\n";
		}
		if ($_REQUEST['ctype']=='edit' && !empty($_REQUEST['factrec'])) {
			$gedrec = $_REQUEST['factrec'];
			init_calendar_popup();
			print "<form method=\"post\" action=\"edit_interface.php\" enctype=\"multipart/form-data\">\n";
			print "<input type=\"hidden\" name=\"action\" value=\"mod_edit_fact\" />\n";
			print "<input type=\"hidden\" name=\"ctype\" value=\"update\" />\n";
			print "<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />\n";
			print "<br /><input type=\"submit\" value=\"".$pgv_lang["save"]."\" /><br />\n";
//		print "<pre>".$gedrec."</pre>";
			print "<table class=\"facts_table\">";
			$level0type = "INDI";
			create_edit_form($gedrec, 0, $level0type);
			print "</table>";
			print_add_layer("NOTE");
			print_add_layer("OBJE");
		
			print "<br /><input type=\"submit\" value=\"".$pgv_lang["save"]."\" /><br />\n";
			print "</form>\n";
		}
		else if ($_REQUEST['ctype']=="update") {
			$factrec = handle_updates('');
			if (!empty($_REQUEST['type'])) $type = $_REQUEST['type'];
			else $type='indi';
			//print "<pre>$factrec</pre>";
			print '<script type="text/javascript">window.opener.paste_edit_data(\''.preg_replace("/\r?\n/", "\\r\\n", $factrec).'\', \''.$factarray[$tag[0]].'\', \''.$type.'\'); window.close();</script>';
		}
	}
	
	/**
	 * delete all facts associated with the given task id
	 * @param string $taskid	the taskid to delete facts for
	 * @param string $indirec	the record to look in
	 * @return string 			the updated record without the associated facts
	 */
	function deleteRAFacts($taskid, $indirec) {
		if (preg_match("/\d _RATID ".$taskid."/", $indirec)>0) {
			$lines = preg_split("/[\r\n]/", $indirec);
			$newrec = $lines[0]."\r\n";
			$subrecs = get_all_subrecords($indirec, '', false, false, false);
			foreach($subrecs as $i=>$factrec) {
				if (preg_match("/\d _RATID ".$taskid."/", $factrec)==0) {
					$newrec .= trim($factrec)."\r\n";
				}
			}
			$indirec = $newrec;
		}
		return $indirec;
	}
}
?>
