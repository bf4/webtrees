<?php

/**
 * phpGedView Research Assistant Tool - Functions File.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id: ra_functions.php 990 2006-06-12 20:20:27Z jchristensen $
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 * @author Mike Hessick
 * @author Mike Austin
 * @author Gavin Winkler
 * @author David Molton
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"ra_functions.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Set up our default language file.
require_once 'languages/ra_lang.en.php';
include_once("modules/research_assistant/forms/ra_privacy.php");
if (file_exists($INDEX_DIRECTORY.$GEDCOM."_ra_priv.php")) include_once($INDEX_DIRECTORY.$GEDCOM."_ra_priv.php");
define("BASEPATH", 'modules/research_assistant/');
$emptyfacts = array("BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","BAPL","CONL","ENDL","SLGC","EVEN","MARR","SLGS","MARL","ANUL","CENS","DIV","DIVF","ENGA","MARB","MARC","MARS","CHAN","_SEPR","RESI", "DATA", "MAP");
$templefacts = array("SLGC","SLGS","BAPL","ENDL","CONL");
$nonplacfacts = array("ENDL","NCHI","SLGC","SLGS","SSN");
$nondatefacts = array("ABBR","ADDR","AFN","AUTH","EMAIL","FAX","NAME","NCHI","NOTE","OBJE","PHON","PUBL","REFN","REPO","SEX","SOUR","SSN","TEXT","TITL","WWW","_EMAIL");


/**
 * Base class for the Research Assistant, contains all basic functionality
 */
class ra_functions {

	function Init() {
		$this->createDatabase();
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
			$sql = 'create table '.$TBLPREFIX.'tasks (t_id INTEGER not null,t_fr_id INTEGER not null,t_title VARCHAR(255) not null,t_description text not null, t_startdate INT not null, t_enddate INT null, t_results text null, t_form varchar(255) null, constraint '.$TBLPREFIX.'tasks_PK primary key (t_id) );';
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
			$sql = 'create table '.$TBLPREFIX.'comments (c_id INTEGER not null,'.'c_t_id INTEGER not null,'.'c_u_username VARCHAR(30) not null,'.'c_body text not null,'.'c_datetime INT not null,'.' constraint '.$TBLPREFIX.'comments_PK primary key (c_id) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'tasksource', $data)) {
			$sql = 'create table '.$TBLPREFIX.'tasksource (ts_t_id INTEGER not null,ts_s_id VARCHAR(255) not null, ts_page VARCHAR(255), ts_date VARCHAR(50), ts_text TEXT, ts_quay VARCHAR(50), ts_obje VARCHAR(20), constraint '.$TBLPREFIX.'tasksource_PK primary key (ts_s_id, ts_t_id) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'folders', $data)) {
			$sql = 'create table '.$TBLPREFIX.'folders (fr_id INTEGER not null,'.'fr_name VARCHAR(255),'.'fr_description text null,'.'fr_parentid INTEGER null,'.' constraint '.$TBLPREFIX.'folders_PK primary key (fr_id) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'individualtask', $data)) {
			$sql = 'create table '.$TBLPREFIX.'individualtask (it_t_id integer not null,'.'it_i_id VARCHAR(255) not null,'.'it_i_file integer not null,'.' constraint '.$TBLPREFIX.'individualtask_PK primary key (it_t_id, it_i_id,it_i_file) );';
			$res = dbquery($sql);
		}
		if (!in_array($TBLPREFIX.'taskfacts', $data)) {
			$sql = "CREATE TABLE ".$TBLPREFIX."taskfacts (tf_id INT, tf_t_id INT, tf_factrec TEXT, tf_people VARCHAR(255), primary key (tf_id))";
			$res = dbquery($sql);
		}
		if(!in_array($TBLPREFIX.'user_comments', $data)){
			//$sql = 'create table'.$TBLPREFIX.'user_comments (uc_id INTEGER not null,'.'uc_username VARCHAR(45) not null,'.'uc_datetime INTEGER not null,'.'uc_comment VARCHAR(500) not null,'.'uc_p_id VARCHAR(255) not null,'.'uc_f_id INTEGER not null,'.' constraint '.$TBLPREFIX.'user_comments_PK primary key (uc_id));';
			$sql = 'create table '.$TBLPREFIX.'user_comments (uc_id INT not null,'.'uc_username VARCHAR(45) not null,'.'uc_datetime INT not null,'.'uc_comment VARCHAR(500) not null,'.'uc_p_id VARCHAR(255) not null,'.'uc_f_id INT not null,'.' constraint '.$TBLPREFIX.'user_comments_PK primary key (uc_id));';
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
			$sql = "create table ".$TBLPREFIX."probabilities (pr_id int NOT NULL, pr_f_lvl varchar(200) NOT NULL, pr_s_lvl varchar(200), pr_rel varchar(200) NOT NULL, pr_matches INT NOT NULL, pr_count INT NOT NULL, pr_file INT, primary key (pr_id) )";
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
			$percent = " width='22%' ";

		// Display for the menu
global $SHOW_MY_TASKS, $SHOW_ADD_TASK, $SHOW_AUTO_GEN_TASK, $SHOW_VIEW_FOLDERS, $SHOW_ADD_FOLDER, $SHOW_ADD_UNLINKED_SOURCE, $SHOW_VIEW_PROBABILITIES;//show
		$out = '<table class="list_table" width="100%" cellpadding="2">';
		$out .= '<tr>';
		$out .= '<td align="left"'.$percent.'class="optionbox wrap">'.ra_functions :: print_top_folder($folderid).'</td>';
		//button 'My Tasks'
		if (getUserAccessLevel(getUserName())<=$SHOW_MY_TASKS)
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=mytasks"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["my_tasks"].'" border="0"></img><br />'.$pgv_lang["my_tasks"].'</a></td>';
		//button 'Add Task''
		if (getUserAccessLevel(getUserName())<=$SHOW_ADD_TASK)
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=addtask&amp;folderid='.$folderid.'">'.'<img src="modules/research_assistant/images/add_task.gif" alt="'.$pgv_lang["add_task"].'" border="0"></img><br />'.$pgv_lang["add_task"].'</a></td>';
		//button 'Auto Generate Tasks'
		if (getUserAccessLevel(getUserName())<=$SHOW_AUTO_GEN_TASK)
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=genTasks">'.'<img src="modules/research_assistant/images/add_task.gif" alt="'.$pgv_lang["gen_tasks"].'" border="0"></img><br />'.$pgv_lang["gen_tasks"].'</a></td>';
		//button 'View Folders'
		if (getUserAccessLevel(getUserName())<=$SHOW_VIEW_FOLDERS)
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant">'.'<img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["view_folders"].'" border="0"></img><br />'.$pgv_lang["view_folders"].'</a></td>';
		//button 'Add Folder'
		if (getUserAccessLevel(getUserName())<=$SHOW_ADD_FOLDER && empty ($folderid))
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=addfolder">'.'<img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["add_folder"].'" border="0"></img><br />'.$pgv_lang["add_folder"].'</a></td>';
		//button 'Add Unlinked Source'
		if (getUserAccessLevel(getUserName())<=$SHOW_ADD_UNLINKED_SOURCE && empty ($folderid))
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="javascript: '.$pgv_lang["add_unlinked_source"].'" onclick="addnewsource(\'\'); return false;"><img src="modules/research_assistant/images/add_task.gif" alt="'.$pgv_lang["add_unlinked_source"].'"border=0"></img><br />'.$pgv_lang["add_unlinked_source"].'</a></td>';
		//button 'View Probabilities'
		if (getUserAccessLevel(getUserName())<=$SHOW_VIEW_PROBABILITIES && empty ($folderid))
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=viewProbabilities">'.'<img src="modules/research_assistant/images/view_inferences.gif" alt="'.$pgv_lang["view_probabilities"].'" border="0"></img><br />'.$pgv_lang["view_probabilities"].'</a></td>';
		//button 'Configure Privacy' for ADMIN ONLY
		if(userIsAdmin(getUserName()) && empty ($folderid))
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=configurePrivacy">'.'<img src="modules/research_assistant/images/folder_blue_icon.gif" alt="'.$pgv_lang["configure_privacy"].'" border="0"></img><br />'.$pgv_lang["configure_privacy"].'</a></td>';
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
				$url = '<a href="module.php?mod=research_assistant">';

			// Finish up the links    
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.$url.'<img src="modules/research_assistant/images/folder_blue_icon.gif" alt="Up Folder" border="0"></img><br />Up Folder</a></td>';
			$out .= '<td align="center" class="optionbox" width="'.$width.'">'.'<a href="module.php?mod=research_assistant&amp;action=editfolder&amp;folderid='.$folderid.'"><img src="modules/research_assistant/images/folder_blue_icon.gif" alt="Edit Folder" border="0" /><br />'.$pgv_lang["edit_folder"].'</a></td>';
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
			$image = "modules/research_assistant/images/checkbutton.gif";
			$div = "<span style='color:green'>";
			$end = "</span>";
		} else {
			$status = "Error!";
			$image = "modules/research_assistant/images/xbutton.gif";
			$div = "<div class='error'>";
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
			return "<div class='error'>ERROR: File modules/research_assistant/forms/".$name.".php was not found.</div>";
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
		
		while ($task = & $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.$task["t_title"].'</a></td><td class="optionbox">'.get_changed_date(date("d M Y", $task["t_startdate"])).'</td><td class="optionbox" align="center">'.$this->checkComplete($task).'</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["edit"].'</a></td>'.'<td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'&amp;folder='.$folderId.'" class="link">'.$pgv_lang["delete"].'</a></td></tr>';
		}
		$out .= '</table>';
		
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
			$out = "<div class='error'>ERROR: Nothing was found in your database.</div>";
		}

		return $out;
	}
	
	/*
	 * Dipsplay tasks that a user has assigned to them.
	 */
	function print_user_list($userName)
	{
		
		global $TBLPREFIX, $pgv_lang;
		$sql = 	"Select * From ".$TBLPREFIX."tasks where t_username ='".$userName."'";
		$res = dbquery($sql);
		$out = "";
	 	
	 	$out .= "<table id='Tasks' class='list_table' align='center' width='700' border='0'>";
		$out .= "<tr><th colspan='7' class='topbottombar'><h2>".$pgv_lang["Task_View"].print_help_link("ra_view_task_help", "qm", '', false, true)."</h2></th></tr>";
		$out .= "<tr><th class='descriptionbox'><a href='module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=&amp;orderby=t_title&amp;type='>".$pgv_lang["Task_Name"]."</a></th><th class='descriptionbox'><a href='module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=&amp;orderby=t_startdate&amp;type='>".$pgv_lang["Start_Date"]."</a></th>"."<th class='descriptionbox'><a href='module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=&amp;orderby=t_enddate&amp;type='>".$pgv_lang["completed"]."</a></th><th class='descriptionbox'>".$pgv_lang["edit"]."</th><th class='descriptionbox'>".$pgv_lang["delete"]."</th></tr>";
		
		while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.$task["t_title"].'</a></td><td class="optionbox">'.get_changed_date(date("d M Y", $task["t_startdate"])).'</td><td class="optionbox" align="center">'.$this->checkComplete($task).'</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["edit"].'</a></td>'.'<td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["delete"].'</a></td></tr>';
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
	 * @param Int $folderId The folder to look forn
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

			$out = '<img src="modules/research_assistant/images/folder.gif" alt="Current Folder"></img>';

			$folder = & $res->fetchRow(DB_FETCHMODE_ASSOC);
			$out .= "<strong>".stripslashes($folder["fr_name"])."</strong>"; //"<br /><strong>Comments: </strong>" . stripslashes($folder["fr_description"]);
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
			$out .= '<tr><th colspan="2" class="topbottombar"><h2>'.$pgv_lang["Folder_View"].print_help_link("ra_fold_name_help", "qm", '', false, true).'</h2></th></tr>';
			$out .= '<tr><th class="descriptionbox"><a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folderId.'&amp;orderbyfolder=fr_name&amp;type='.$_REQUEST["type"].'">'.$pgv_lang["Folder_Name"].'</a></th>';
			$out .= '<th class="descriptionbox"><a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folderId.'&amp;orderbyfolder=fr_description&amp;type='.$_REQUEST["type"].'">'.$pgv_lang["description"].'</a></th></tr>';
		}

		while ($folders = & $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid='.$folders["fr_id"].'"><img src="modules/research_assistant/images/folder.gif" border="0" alt="Folder"></img>'.$folders["fr_name"].'</a></td><td class="optionbox wrap"><br />'.nl2br(stripslashes($folders["fr_description"])).'</td></tr>';
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
			$out .= "<table id='Tasks' class='list_table' align='center' width='700' border='0'>";
			$out .= "<tr><th colspan='7' class='topbottombar'><h2>".$pgv_lang["Task_View"].print_help_link("ra_view_task_help", "qm", '', false, true)."</h2></th></tr>";
			$out .= "<tr><th class='descriptionbox'><a href='module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=".$folderId."&amp;orderby=t_title&amp;type=".$_REQUEST["type"]."'>".$pgv_lang["Task_Name"]."</a></th><th class='descriptionbox'><a href='module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=".$folderId."&amp;orderby=t_startdate&amp;type=".$_REQUEST["type"]."'>".$pgv_lang["Start_Date"]."</a></th>"."<th class='descriptionbox'><a href='module.php?mod=research_assistant&amp;action=viewtasks&amp;folderid=".$folderId."&amp;orderby=t_enddate&amp;type=".$_REQUEST["type"]."'>".$pgv_lang["completed"]."</a></th><th class='descriptionbox'>".$pgv_lang["edit"]."</th><th class='descriptionbox'>".$pgv_lang["delete"]."</th></tr>";

		}

		// Loop through the database results and print each task
		while ($task = & $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$out .= '<tr><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task["t_id"].'">'.$task["t_title"].'</a></td><td class="optionbox">'.get_changed_date(date("d M Y", $task["t_startdate"])).'</td><td class="optionbox" align="center">'.$this->checkComplete($task).'</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["edit"].'</a></td>'.'<td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=deletetask&amp;taskid='.$task["t_id"].'&amp;folder='.$folderId.'" class="link">'.$pgv_lang["delete"].'</a></td></tr>';
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
		global $TBLPREFIX, $DBCONN;
		$sql = "UPDATE ".$TBLPREFIX."tasks SET t_enddate='".time()."', t_form='".$DBCONN->escapeSimple($form)."' WHERE t_id='".$DBCONN->escapeSimple($taskid)."'";
		dbquery($sql);
	}
	/**
	 * Function to find missing information
	 * @return mixed
	 */
	function getMissinginfo(& $person) {
		global $factarray, $templefacts, $nondatefacts, $nonplacfacts;

		$MissingReturn = array (); //Local var for the return string
		if ($person->sex == "U") //check for missing sex info
			{
			$MissingReturn[] = array("Sex", "All");
		}
		if ($person->brec != "") //check for missing birth info
			{

		} else {
			$MissingReturn[] = array("BIRT", "All");
		}
		if ($person->drec != "") //check for missing death info
			{

		} else {
			$MissingReturn[] = array("DEAT", "All");
		}
		if ($person->getGivenNames() == "unknown") {
			$MissingReturn[] = "Given Name";
		}
		if ($person->getSurname() == "unknown") {
			$MissingReturn[] = "Surname";
		}

		$indifacts = get_all_subrecords($person->gedrec, "FAMS,FAMC,NOTE,OBJE,SEX,NAME,SOUR,REFN,CHAN,AFN", false);

		foreach ($indifacts as $key => $far) {
			$match = array();
			$ft = preg_match("/1 (\w+)(.*)/", $far, $match);
			if ($ft > 0) {
				$fact = trim($match[1]);
				$event = trim($match[2]);
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
						$MissingReturn[] = array ($fact, "PLAC");
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
		
		$inferences[] = array('local'=>'SURN', 'record'=>'FAMC:HUSB', 'comp'=>'SURN', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'SURN', 'record'=>'FAMC:WIFE', 'comp'=>'SURN', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BIRT:PLAC', 'record'=>'FAMC:HUSB', 'comp'=>'BIRT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BIRT:PLAC', 'record'=>'FAMC:WIFE', 'comp'=>'BIRT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BIRT:PLAC', 'record'=>'FAMC', 'comp'=>'MARR:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'OCCU', 'record'=>'FAMC:HUSB', 'comp'=>'OCCU', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'OCCU', 'record'=>'FAMC:WIFE', 'comp'=>'OCCU', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'DEAT:PLAC', 'record'=>'', 'comp'=>'BIRT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'DEAT:PLAC', 'record'=>'FAMS', 'comp'=>'MARR:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BIRT:PLAC', 'record'=>'FAMS', 'comp'=>'MARR:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'DEAT:PLAC', 'record'=>'FAMS:SPOUSE', 'comp'=>'DEAT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BIRT:PLAC', 'record'=>'FAMS:SPOUSE', 'comp'=>'BIRT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'CHR:PLAC', 'record'=>'', 'comp'=>'BIRT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BAPM:PLAC', 'record'=>'', 'comp'=>'BIRT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'BURI:PLAC', 'record'=>'', 'comp'=>'DEAT:PLAC', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'GIVN', 'record'=>'FAMC:HUSB', 'comp'=>'GIVN', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'GIVN', 'record'=>'FAMC:WIFE', 'comp'=>'GIVN', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'GIVN', 'record'=>'FAMC:HUSB:FAMC:HUSB', 'comp'=>'GIVN', 'value'=>0, 'count'=>0);
		$inferences[] = array('local'=>'GIVN', 'record'=>'FAMC:WIFE:FAMC:WIFE', 'comp'=>'GIVN', 'value'=>0, 'count'=>0);
		
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
				if (!isset($locals[$value['local']])) {
					if ($value['local']=='SURN') $locals['SURN'] = $indi['names'][0][2];
					else if ($value['local']=='GIVN'){
						$parts = preg_split("~/~", $indi['names'][0][0]);
						$locals['GIVN'] = $parts[0];
					}
					else {
						$locals[$value['local']] = get_gedcom_value($value['local'], 1, $indi['gedcom'], '', false);
					}
				}
				
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
							if (str2lower($locals[$value['local']])==str2lower($gedval)) $inferences[$pr_id]['value']++;
							$inferences[$pr_id]['count']++;
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
		$res = $this->get_folder("");
		$out = "";
		while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$out .= "<option value=".$row['fr_id']." selected=Selected>".$row['fr_name']."</option>";
		}
		return $out;
	}

	/**
	 * tab is the function that builds the display for the different screens.
	 * These screens are identified by a tab
	 */

	//Get Tasks for Source
	function getSourceTasks(& $sId) {
		global $pgv_lang, $TBLPREFIX;
		global $indilist, $controller;
		global $factarray;
        	
		$sql = "SELECT * FROM ".$TBLPREFIX."tasks join ".$TBLPREFIX."tasksource on t_id = ts_t_id join ".$TBLPREFIX."sources on s_id = ts_s_id where s_id ='".$sId."'";
		
		$res = dbquery($sql);
	
		$out = "\n\t<table class=\"list_table\">";
		$out .= "<tr><td class ='topbottombar' colspan='4' align='center'>".print_help_link("task_list_text", "qm", '', false, true)."<b>".$pgv_lang['task_list']."</b></td></tr>";
		if ($res->numRows()==0) $out .= "<tr><td class ='topbottombar' colspan='4' align='center'>".$pgv_lang['no_indi_tasks']."</td></tr>";
		else { 
			$out .= "\n\t\t<tr><td class=\"list_label\"><strong>".$pgv_lang["details"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["title"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["completed"]."</strong></td><td class=\"list_label\"><strong>".$pgv_lang["created"]."</strong></td></tr>";
			// Loop through all the task ID's and pull the info we need on them,
			// then format them nicely to show the user.
			while ($taskid = $res->fetchrow(DB_FETCHMODE_ASSOC)) {
				$sql = "SELECT * FROM ".$TBLPREFIX."tasks WHERE t_id = '".$taskid['ts_t_id']."'";
				$result = dbquery($sql);
				$task = $result->fetchrow(DB_FETCHMODE_ASSOC);
     			$task = db_cleanup($task);
				$out .= '<tr><td class="list_label"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task['t_id'].'" class="link">'.$pgv_lang['details'].'</a></td><td class="list_label">'.$task['t_title'].'</td><td class="list_label">'.$this->checkComplete($task).'</td><td class="list_label">'.get_changed_date(date("d M Y", $task["t_startdate"])).'</td></tr>';
			}
		}
		return $out;
	
	}

	/**
	 * tab is the function that builds the display for the different screens.
	 * These screens are identified by a tab
	 */
	function tab(& $person) {
		// Start our engines.
		global $pgv_lang, $TBLPREFIX, $DBCONN, $GEDCOMS, $GEDCOM;
		global $indilist, $controller;
		global $factarray;
		if (!is_object($person)) return "";
		$givennames = $person->getGivenNames();
		$lastname = $person->getSurname();
		$byear = $person->getBirthYear();
		$dyear = $person->getDeathYear();

		if (isset ($_REQUEST['action']) && $_REQUEST['action'] == 'ra_addtask')
			$this->auto_add_task($person, $_POST['folder']);

		//$probabilities = $this->inferences();

		// gets task id from the database
		$sql = "SELECT * FROM ".$TBLPREFIX."individualtask WHERE it_i_id = '".$DBCONN->escapeSimple($person->getXref())."' AND it_i_file='".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]['id'])."'";		
		$res = dbquery($sql);

		if (PEAR::isError($res)) {
			return "<span class=\"error\">There was an error with the Research Assistant database.  Click on the <a href=\"module.php?mod=research_assistant\">Research Assistant</a> icon to create the database.<br />&nbsp;</span>";
		}
		// Start of HTML output
		$out = "\n\t<table class=\"list_table\">";
		$out .= "<tr><td class ='topbottombar' colspan='4' align='center'>".print_help_link("task_list_text", "qm", '', false, true)."<b>".$pgv_lang['task_list']."</b></td></tr>";
		if ($res->numRows()==0) $out .= "<tr><td class ='topbottombar' colspan='4' align='center'>".$pgv_lang['no_indi_tasks']."</td></tr>";
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
					$out .= '<tr><td class="list_label"><a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task['t_id'].'" class="link">'.$pgv_lang['details'].'</a></td><td class="list_label">'.$task['t_title'].'</td><td class="list_label">'.$this->checkComplete($task).'</td><td class="list_label">'.get_changed_date(date("d M Y", $task["t_startdate"])).'</td></tr>';
				}
				$result->free();
			}
		}
		//This is where the missing info check will happen
		$Missing = $this->getMissinginfo($person);

		$out .= "<tr><td class=\"topbottombar\" colspan=\"4\"><a href=\"module.php?mod=research_assistant&amp;action=addtask&amp;pid=".$person->getXref()."\">".$pgv_lang["task_entry"]."</a></td></tr></table>";
				
				//beginning of the missing information table, which gets populated with missing information for that individual and allows the user to "autoadd" tasks
				//a checkbox to view link conversion is included if a piece of missing information is already auto tasked
		$out .="
				<table width='100%'>
					<tr>
						<td width='50%'>
							<table align='right'>
									<form name='auto' action='individual.php' method='post'><input type='hidden' name='pid' value='".$controller->pid."' /><input type='hidden' name='action' value='ra_addtask' />
									<tr>
										<td align='right' colspan=2 class='topbottombar'>".print_help_link("ra_missing_info_help", "qm", '', false, true)."<b>".$pgv_lang['missing_info'].
										"</td>
									</tr>";
										foreach ($Missing as $key => $val) //every missing item gets a checkbox , so you check check it and make a task out of it
										{
											$tasktitle = "";
											if (isset($factarray[$val[0]])) $tasktitle .= $factarray[$val[0]]." ";
											if (isset($factarray[$val[1]])) $tasktitle .= $factarray[$val[1]];
											$taskid = $this->task_check($tasktitle, $person->getXref());
											if (!$taskid) // if the task_check passes, create a check box
												{
												$out .= "<tr><td width='20' class='optionbox'><input type='checkbox' name=\"missingName[]\" value=\"".$tasktitle."\" /></td><td class ='optionbox'>";
												$out .= $tasktitle."</td></tr>";
											} else // if not allow user to view the already created task
								
												$out .= "<tr><td width='20' class='optionbox'><a href=module.php?mod=research_assistant&action=viewtask&taskid=$taskid>View</a></td><td class='optionbox'>".$tasktitle."</td></tr>";
										}
										// Create the selection box and add all the folder names and values
										$out .= "<tr><td class='optionbox' colspan='2' align='center'><h5>Folder:&nbsp;&nbsp;</h><select name='folder'>";
										$out .= $this->folder_search();
										$out .= "</select></td></tr>";
										$out .= "<tr><td colspan='2'class='topbottombar'><input type='submit' value='Add Task' /></td></tr>
									</form>
							</table>
						</td>";
		//Beginning of the auto search feature which gets dynamically populated with an individuals information to be sent to ancestry or familySearch
		$out .= "<form name='ancsearch' action='' method='post' onsubmit='return false;'> 
						
						<td align='left' valign='top'>
							<table width='50%'>
								<tr>
									<td align='center' class='topbottombar' colspan='2' height='50%'><b>".print_help_link("auto_search", "qm", '', false, true)."<b>".$pgv_lang['auto_search_text']."</b>
									</td>
								</tr>		
		 						<tr>
					 				<td class='optionbox'>
					 					Include surname:</td><td class='optionbox'> <input type='checkbox' name='surname' value=".$lastname." checked='checked' /> ".$lastname."</td></tr>
					 						<tr><td class='optionbox'>
					 					Include given names:</td><td class='optionbox'> <input type='checkbox' name='givenname1' value=".$givennames." checked='checked'' /> ".$givennames."</td></tr>
					 						<tr><td class ='optionbox'>
					 					Include birth year:</td><td class ='optionbox'> <input type='checkbox' name='birthyear' value=".$byear." checked='checked' /> ".$byear."</td></tr>
					 						<tr><td class='optionbox'>
					 					Include death year:</td><td class='optionbox'> <input type='checkbox' name='deathyear' value=".$dyear." checked='checked' />".$dyear."
					 				</td>
		 						</tr>
		 						<tr>
					 				<td class='topbottombar' align='center'><input type='button' value='Search' onclick='search_ancestry();''/>
									</td>
									<td class='topbottombar'>
					 					<SELECT name='cbosite'>
										<OPTION value='listdemo1' class='1'>Ancestry.com
										<OPTION value='listdemo2' class='2'>FamilySearch.org				
										</SELECT>
									</td>
								</tr>
							</table>
						</td>
					</form>
				</tr>
			</table>";

		//Beginning of the comments feature
		$out .= "<form name='commentsSubmit' action='individual.php' method='post'>" .
				"<input type=\"hidden\" name=\"pid\" value=\"".$person->getXref()."\" />" .
				"<input type=\"hidden\" name=\"ged\" value=\"".$GEDCOM."\" />
				<center>
					
					<br/>
					<table >
						<tr>
							<td class='topbottombar' hight='50%'>
								<a class='help' tabindex='0' href='javascript:// help_comments' onclick='helpPopup('help_comments'); return false;'>
								<img src='images/small/help.gif' class='icon' width='15' height='15' alt='' />
								</a> Comments
							</td>
						</tr>
						<tr>
							<td class='optionbox'>
								<textarea rows='3'cols='55' name='comments'></textarea>
							</td>
						</tr>
							<tr>
								<td class='topbottombar'>
									<input type='submit' value='Submit' />";
									
									if(!empty($_POST['comments'])){
										$commentid = get_next_id("user_comments", "uc_id");
										$username = getUserName();
										$sql = "INSERT INTO ".$TBLPREFIX."user_comments (uc_id, uc_username, uc_datetime, uc_comment, uc_p_id, uc_f_id) "."VALUES ('".$DBCONN->escapeSimple($commentid)."', '".$DBCONN->escapeSimple($username)."', '".time()."', '".$DBCONN->escapeSimple($_POST["comments"])."', '".$DBCONN->escapeSimple($person->getXref())."', '".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]['id'])."')";
									    
										$res = dbquery($sql);
									}
						$out .= "</td>
							</tr>
					</table>
					
				</center>
				</form>";

		// Display comments
		global $pgv_lang, $TBLPREFIX;
//		$sql = 	"SELECT  uc_id, uc_username, uc_datetime, uc_comment " .
//				"FROM " . $TBLPREFIX . "user_comments " .
//				//"WHERE uc_id='" . $_REQUEST["taskid"] . "' " .
//				"Where i_id = " .$person .
//				"ORDER BY uc_datetime DESC";
		$sql = "select uc_id, uc_username, uc_datetime, uc_comment from ".$TBLPREFIX . "user_comments WHERE uc_f_id='".$GEDCOMS[$GEDCOM]['id']."' AND uc_p_id='" . $person->getXref() . "' ORDER BY uc_datetime DESC";
		$res = dbquery($sql);
		$out .= "";

		while($comment =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out .= '<div class="blockcontent"><div class="person_box" id="comment1"><span class="news_title">' .
					''.$comment["uc_username"].'' . 	// INSERT username
					'</span><br /><span class="news_date">' .
					''.get_changed_date(date("d M Y", (int)$comment["uc_datetime"])).' - '. date("g:i:s A",(int)$comment["uc_datetime"]).		// INSERT datetime
					'</span><br /><br />' .
					''.$comment["uc_comment"].'' .			// INSERT body
					'<hr size="1" />';
					
			if((userIsAdmin(getUserName())) || (getUserName() == $comment["uc_username"])){
				$out .= '<a href="javascript:;" onclick="editcomment(' .
							''.$comment["uc_id"].'' .	// INSERT commentid
							')">'.$pgv_lang["edit"].'</a> | <a href="" onclick="confirm_prompt(\''.$pgv_lang["comment_delete_check"].'\', ' .
							''.$comment["uc_id"].'' .	// INSERT commentid
							'); return false;">'.$pgv_lang["delete"].'</a>';
			}
			$out .= '<br /></div></div><br/>';
		}
		
		
//		if(isset($_REQUEST['delete']) && !empty($_REQUEST['delete'])){
//		// TODO: Verify user
//		$sql = "DELETE FROM " . $TBLPREFIX . "user_comments WHERE uc_id='$_REQUEST[delete]'";
//		$res = dbquery($sql);
		
		
		
			
		//Beginning of our JavaScript
		$out .= "
 <script language='JavaScript'>
 <!--
		function find_folder_id() {
 		form = document.auto;
 		folderID=form.folder.options[form.folder.selectedIndex].value;
 		return folderID;
 	}
 				
 	function search_ancestry() {
 		ifrm = document.getElementById('ifrm');
 		frm = document.ancsearch;
 		if (frm.cbosite.options[frm.cbosite.selectedIndex].value == 'listdemo2') { 				
 				url = 'http://www.familysearch.org/Eng/search/ancestorsearchresults.asp?';
 				if (frm.surname.checked){
 					url = url + 'last_name=' + frm.surname.value; 																		
 				}
 				if (frm.surname.checked && frm.givenname1.checked){
 					url = url + '&first_name=' + frm.givenname1.value; 					
 				}
 				else alert('You must search with a last name');			
 				//if (document.all) ifrm.location = url;
				//else ifrm.src = url;
				window.open(url, '');									
 		}
		else {
				url = 'http://search.ancestry.com/cgi-bin/sse.dll?';
				if (frm.surname.checked) {
					url = url + '&gsln='+ frm.surname.value;
				}
				if (frm.givenname1.checked) {
					url = url + '&gsfn=' + frm.givenname1.value; 			
				}
				if (frm.birthyear.checked) {
					url = url + '&gsby=' + frm.birthyear.value;
				}
				if (frm.deathyear.checked) {
					url = url + '&gsdy='+ frm.deathyear.value;
				} 	 
				// -- old iframe method 
				// if (document.all) ifrm.location = url;
				// else ifrm.src = url;
				window.open(url, '');			
		}			 		 	
 	} 		
 	//-->
 </script>
 	<!-- <iframe name='ifrm' id='ifrm' src='' width='100%' height='50%' frameborder='0'>
	 </iframe> -->";
		$out .= "\n\t<br /><br />";
		// Return the goods.		 	
		return $out;
	}
	
	/**
	 * function to handle fact edits/updates for task completion forms
	 * this function is hooked in from the edit_interface.php file.
	 * It requires at least that a command variable be put on the request
	 */
	function edit_fact() {
		global $templefacts, $nondatefacts, $nonplacfacts;
		global $factarray, $pgv_lang, $tag, $MULTI_MEDIA;
		
		if ($_REQUEST['command']=='add' && !empty($_REQUEST['fact'])) {
			$fact = $_REQUEST['fact'];
			init_calendar_popup();
			print "<form method=\"post\" action=\"edit_interface.php\" enctype=\"multipart/form-data\">\n";
			print "<input type=\"hidden\" name=\"action\" value=\"mod_edit_fact\" />\n";
			print "<input type=\"hidden\" name=\"mod\" value=\"research_assistant\" />\n";
			print "<input type=\"hidden\" name=\"command\" value=\"update\" />\n";
			
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
		if ($_REQUEST['command']=='edit' && !empty($_REQUEST['factrec'])) {
			$gedrec = $_REQUEST['factrec'];
			init_calendar_popup();
			print "<form method=\"post\" action=\"edit_interface.php\" enctype=\"multipart/form-data\">\n";
			print "<input type=\"hidden\" name=\"action\" value=\"mod_edit_fact\" />\n";
			print "<input type=\"hidden\" name=\"command\" value=\"update\" />\n";
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
		else if ($_REQUEST['command']=="update") {
			$factrec = handle_updates('');
			print "<pre>$factrec</pre>";
			print '<script type="text/javascript">window.opener.paste_edit_data(\''.preg_replace("/\r?\n/", "\\r\\n", $factrec).'\', \''.$factarray[$tag[0]].'\'); window.close();</script>';
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