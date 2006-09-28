<?php
/**
 * phpGedView Research Assistant Tool.
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
 * @version $Id: research_assistant.php 979 2006-06-07 22:39:41Z jleung $
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"research_assistant.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Require our base class and db functions
require_once ("ra_functions.php");
require_once 'modules/research_assistant/forms/ra_GeneratedTask.php';

//-- commmented out because the other section should take care of redirecting
// If the user is not logged in, take them to the login page.
//if (empty ($_SESSION['pgv_user'])) {
//	header("Location: login.php?url={$PHP_SELF}");
//	exit;
//}

// If the user doesnt have access then take them to the index.
if ($SHOW_RESEARCH_ASSISTANT < getUserAccessLevel() && preg_match("/index.php/", $SCRIPT_NAME)==0) {
	header("Location: index.php");
	exit;
}

/**
 * Research Assistant created by Neumont University students.
 *
 * This class contains all of the functionality for the research
 * assistant.  It gives PGV the CRUD information for the  
 * research_assistant forms, as well as the Todo list.
 */
class research_assistant extends ra_functions {
	var $used = 0;
	/**
	* Entry point for the PGV module system.
	*
	* <p>Each module needs to have a main function.  Usually the modules
	* are classes.  You only reference the class inside of itself.</p>
	* <p>This function handles all of the output, so all of your branching
	* for the module needs to happen in this function, it's kind of like a
	* static method.
	*
	* @return	mixed	 Output to the user in HTML form
	*/
	function main() {
		// Specify our lang variable and the table prefix.
		global $pgv_lang, $TBLPREFIX, $DBCONN;
		
		// PGV modules use the mod_print_header function to print out the default PGV full header
		// above their main content. There is also a pgv_print_simple_header which prints out an empty header.
		$out = mod_print_header($pgv_lang["page_header"]);
		// We need to intialize our module to make sure we dont need to install some DB scripts.
		$out .= $this->Init();
		if (empty ($_REQUEST['action']))
			$_REQUEST['action'] = "none";
		
		// View tasks 
		if ($_REQUEST['action'] == "viewtasks") {
			$out .= $this->print_menu($_REQUEST['folderid']);
			// Print folders if we were given a folder id, otherwise tell the user there are no folders
			if (isset ($_REQUEST['folderid'])) {
				if (isset ($_REQUEST["orderbyfolder"]))
					$out .= $this->print_folder_view($_REQUEST['folderid'], $_REQUEST['orderbyfolder']);
				else {
					$_REQUEST["orderbyfolder"] = "";
					$out .= $this->print_folder_view($_REQUEST['folderid']);
				}
				if (!empty ($_REQUEST['folderid'])) {
					if (isset ($_REQUEST["orderby"]))
						$out .= $this->print_list($_REQUEST['folderid'], $_REQUEST['orderby']);
					else {
						$_REQUEST["orderby"] = "";
						$out .= $this->print_list($_REQUEST['folderid']);
					}
				}
			} else
				$out .= "<div class='error'>".$pgv_lang["no_folder"]."</div>";
		}

		// Add a task
		else
			if ($_REQUEST['action'] == "addtask") {
				$out .= $this->print_menu();
				$out .= $this->print_simple_form("ra_AddTask");
			}

		// Submit a task
		else
			if ($_REQUEST['action'] == "submittask") {
				// ADD TASK
				$taskid = get_next_id("tasks", "t_id");
				if (empty($_POST['folder']))
				{
					$out .= $this->print_menu();
					$out .= $this->printMessage("There was no folder to put the task in.",false);
					return $out;
				}

                // DEBUG 
                //die("Task id: " . $taskid);
				$sql = "INSERT INTO ".$TBLPREFIX."tasks (t_id, t_fr_id, t_title, t_description, t_startdate, t_username) "."VALUES ('".$DBCONN->escapeSimple($taskid)."', '".$DBCONN->escapeSimple($_POST["folder"])."', '".$DBCONN->escapeSimple($_POST["title"])."', '".$DBCONN->escapeSimple($_POST["desc"])."', '".time()."','".$DBCONN->escapeSimple($_POST['Users'])."')";
				$res = dbquery($sql);
				
				// ADD COMMENT
				if (!empty($_POST['comment'])) {
					$commentid = get_next_id("comments", "c_id");
					$username = getUserName();
					$sql = "INSERT INTO ".$TBLPREFIX."comments (c_id, c_t_id, c_u_username, c_body, c_datetime) "."VALUES ('".$DBCONN->escapeSimple($commentid)."', '".$DBCONN->escapeSimple($taskid)."', '".$DBCONN->escapeSimple($username)."', '".$DBCONN->escapeSimple($_POST["comment"])."', '".time()."')";
					$res = dbquery($sql);
				}

                // Catch for the taskid not being set
                if (!isset($_POST['taskid']))
                    $_POST['taskid'] = $taskid;

				// ADD PEOPLE
				if (isset ($_POST['personid'])) {
					$this->add_people();
				}

				// ADD SOURCES
				if (isset ($_POST['sourceid'])) {
					$this->add_sources($_POST['taskid'], $_POST['sourceid']);
				}

				$out .= $this->print_menu($_POST['folder']);
				$out .= $this->print_folder_view($_POST['folder']);
				$out .= $this->print_list($_POST['folder']);
				// $out .= $this->printMessage("Task Added.", true);

			}

		// Update task
		else
			if ($_REQUEST['action'] == "updatetask") {
				$sql = "UPDATE ".$TBLPREFIX."tasks SET t_fr_id='".$DBCONN->escapeSimple($_POST["folder"])."', t_title='".$DBCONN->escapeSimple($_POST["title"])."', t_description='".$DBCONN->escapeSimple($_POST["desc"])."', t_username='".$DBCONN->escapeSimple($_POST["Users"])."' WHERE t_id='".$DBCONN->escapeSimple($_REQUEST["taskid"])."'";
				$res = dbquery($sql);

				// UPDATE PEOPLE
				//  -Delete old people
				$sql = "DELETE FROM ".$TBLPREFIX."individualtask WHERE it_t_id='".$DBCONN->escapeSimple($_REQUEST["taskid"])."'";
				$res = dbquery($sql);

				if (isset ($_POST['personid'])) {
					$this->add_people();
				}

				// UPDATE SOURCES
				//  -Delete old sources
				$sql = "DELETE FROM ".$TBLPREFIX."tasksource WHERE ts_t_id='".$DBCONN->escapeSimple($_REQUEST["taskid"])."'";
				$res = dbquery($sql);

				if (isset ($_POST['sourceid'])) {
					$this->add_sources($_REQUEST["taskid"],$_POST['sourceid']);
				}
				$out .= $this->print_menu($_POST['folder']);
				$out .= $this->print_folder_view($_POST['folder']);
				$out .= $this->print_list($_POST['folder']);
			}

		// Edit task
		else
			if ($_REQUEST['action'] == "edittask" && !empty ($_REQUEST['taskid'])) {
				$out .= $this->print_menu("", $_REQUEST['taskid']);
				$out .= $this->print_simple_form("ra_EditTask");
			}
			// Delete task
		else
			if ($_REQUEST['action'] == "deletetask" && !empty ($_REQUEST['taskid'])) {
				$this->deleteTask($_REQUEST['taskid']);
				$out .= $this->print_menu("", $_REQUEST['taskid']);
				//if($_REQUEST['userName'] == "")
				//{
					$out .= $this->print_folder_view();
//					$out .= $this->print_list();
				//}
			}

		// View a task
		else
			if ($_REQUEST['action'] == "viewtask" && !empty ($_REQUEST['taskid'])) {
				$out .= $this->print_menu("", $_REQUEST['taskid']);
				$out .= $this->print_simple_form("ra_ViewTask");
			}
			
		//View assigned Tasks
		else
			if ($_REQUEST['action'] == "mytasks") 
			{
					$out .= $this->print_menu("", "");
					$out .= $this->print_user_list(GetUserName());	
			}

		// Print a form
		else
			if ($_REQUEST['action'] == "printform") {
				$array = & $_POST[];
				$name = $_REQUEST['formname'];
				$out .= $this->print_form($name, $array);
			}

		// Print the form list
		else
			if ($_REQUEST['action'] == "printformlist") {
				$pid = (int) $_REQUEST['pid'];
				$out .= $this->print_form_list($pid);
			}

		// Update a folder
		else
			if ($_REQUEST['action'] == "updateFolder") {
				$update = true;
				//-- check if folderName was not left empty in the field
				if (empty ($_POST['folderName'])) {
					$out .= '<h1>'.$pgv_lang["no_folder_name"].'</h1>';
					$update = false;
				} else {
					//-- check if the folder name alread exists in the database
					$sql = 'Select fr_name, fr_id from '.$TBLPREFIX.'folders;';
					$res = dbquery($sql);

					// Insure that our folder names are unique
					while ($folder = & $res->fetchRow(DB_FETCHMODE_ASSOC)) {
						if ($_POST['folderName'] == $folder['fr_name'] && $_POST['folderID'] != $folder['fr_id']) {
							$out .= $this->print_menu();
							$out .= $pgv_lang["Folder_names_must_be_unique"];
							$update = false;
						}

					}

					// Perform the updates
					if ($update) {
						//-- if no folder ID do an insert to add a new folder
						if (empty($_POST['folderID'])) {
							$folderid = get_next_id("folders", "fr_id");
						
							$sql = "Insert INTO ".$TBLPREFIX."folders values ('".$DBCONN->escapeSimple($folderid)."', '".$DBCONN->escapeSimple($_POST['folderName'])."', '".$DBCONN->escapeSimple($_POST['folderDescription'])."', ". $DBCONN->escapeSimple($_POST['parentFolder'])  . ");";
							$res = dbquery($sql);
						}
						else {
							//-- update the old folder with the new data
							$sql = 'update '.$TBLPREFIX.'folders set fr_name=\''.$DBCONN->escapeSimple($_POST['folderName']).'\', fr_description=\''.$DBCONN->escapeSimple($_POST['folderDescription']).'\',fr_parentid=\''.$DBCONN->escapeSimple($_POST['parentFolder']).'\' where fr_id=\''.$DBCONN->escapeSimple($_POST['folderID']).'\'';
	
							//-- if no parent folder set parent folder field to null in database
							if ($_POST['parentFolder'] == 'null') {
								$sql = 'update '.$TBLPREFIX.'folders set fr_name=\''.$DBCONN->escapeSimple($_POST['folderName']).'\', fr_description=\''.$DBCONN->escapeSimple($_POST['folderDescription']).'\',fr_parentid=null where fr_id=\''.$DBCONN->escapeSimple($_POST['folderID']).'\'';
							}
							$res = dbquery($sql);
						}

						// Tell user it worked
						
						//$out .= $this->printMessage($pgv_lang["folder_submitted"], true);
						$out .= '<tr><td colspan="5">'.$pgv_lang["folder_submitted"]. "</td></tr>";
						if($_POST['parentFolder']!= "null")
						{
							$out .= $this->print_menu($_POST['parentFolder']);
							$out .= $this->print_folder_view($_POST['parentFolder']);
							$out .= $this->print_list($_POST['parentFolder']);
						}
							else
							{
								$out .= $this->print_menu();
							$out .= $this->print_folder_view("");
							$out .= $this->print_list("");
							}
					} else
						$out .= $pgv_lang["folder_problem"];
				}
			}

		// Add a folder
		else
			if ($_REQUEST['action'] == 'addfolder') {
				$out .= $this->print_menu();
				$out .= $this->edit_Folder("");
			}
		// Delete folder
		else
			if ($_REQUEST['action'] == "deletefolder" && !empty ($_REQUEST['folderid'])) {
				$out .= $this->print_menu();
				$out .= $this->deleteFolder($_REQUEST['folderid']);
				$out .= $this->print_folder_view();
			}
		// Edit a Folder
		else
			if ($_REQUEST['action'] == "editfolder" && isset ($_REQUEST['folderid'])) {
				$out .= $this->print_menu();
				$out .= $this->edit_Folder($_REQUEST['folderid']);
			}
		else
			if($_REQUEST['action'] == "genTasks")
			{
				$out .= $this->loadGenTasks();
			}
		else
			if($_REQUEST['action'] == "generatetask")
			{
				$checkedtasks = array();
				$tasks = array();
				if(!empty($_REQUEST['checkedtasks']) && !empty($_SESSION['genTasks']))
				{
   					$checkedtasks = $_REQUEST['checkedtasks'];
   					$tasks = unserialize($_SESSION['genTasks']);
				}
   				foreach($checkedtasks as $key => $value)
   				{
   					$val1 = $tasks[$key];
   					$this->addGenTask($val1, $_REQUEST['folder']);
   				}
   				$out .= $this->loadGenTasks();
			}
		else
			if($_REQUEST['action']  == "savegentask")
			{
				$tasks = unserialize($_SESSION['genTasks']);
				foreach($tasks as $key => $value)
 					if($value->getID() == $_REQUEST['genTaskId'])
 					{
 			     		$value->setName($_REQUEST['title']);
 			     		$value->setDescription($_REQUEST['description']);
 			     		$value->setPersonId($_REQUEST['personid']);
 			     		$value->setSourceId($_REQUEST['sourceid']);
 					}
 				$_SESSION['genTasks'] = serialize($tasks);
				$out .= $this->loadGenTasks();
			}
		else
			if($_REQUEST['action'] == "editgenTasks")
			{
				$out .= $this->print_menu();
				$out .= $this->print_form('ra_EditGeneratedTask');
			}

		// Perform a function call on a form
		else
			if ($_REQUEST['action'] == "func" && !empty ($_REQUEST['func']) && !empty ($_REQUEST['form']))
				$out .= $this->form_function($_REQUEST['form'], $_REQUEST['func']);

		// Complete a task
		else 
			if ($_REQUEST['action'] == "completeTask" && !empty($_REQUEST['taskid'])) {
                $out .= $this->print_menu();
                $task = $this->getTask($_REQUEST['taskid']);
				if (!empty($task['t_form'])&&!isset($_POST['commonFrm'])) $_POST['commonFrm'] = $task['t_form'];
                $out .= $this->print_form('ra_CompleteTask');
			}
		else
			if ($_REQUEST['action'] == "viewProbabilities")
			{
				$out .= $this->print_menu();
				$out .= $this->print_form('ra_ViewInferences');
			}
		else 
			if ($_REQUEST['action'] == "editfact" && !empty($_REQUEST['taskid'])) {
				$out .= $this->print_menu();
				$task = $this->getTask($_REQUEST['taskid']);
				if (!empty($task['t_form'])&&!isset($_POST['commonFrm'])) $_POST['commonFrm'] = $task['t_form'];
				$out .= $this->print_form('ra_CompleteTask');
			}
		//Configure Privacy
		else
			if($_REQUEST['action'] == "configurePrivacy")
			{
				$out .= $this->print_menu();
				$out .= $this->print_simple_form('ra_Configure');
			}
			//Assign User To Task
		else
			if($_REQUEST['action'] == "assignUser" && !empty($_REQUEST['t_id']) && !empty($_REQUEST['t_username']))
			{
				$sql = "UPDATE ".$TBLPREFIX."tasks SET t_username='".$_REQUEST['t_username']."', t_enddate= NULL where t_id= '".$_REQUEST['t_id']."'";
				$res = dbquery($sql);
				$out .= $this->print_menu("", "");
				$out .= $this->print_user_list(GetUserName());
			}
		else if ($_REQUEST['action']=='load_search_plugin') {
			$out .= print_r($_REQUEST, true);
			if (isset($_REQUEST['plugin'])) {
				if (file_exists("modules/research_assistant/search_plugin/".$_REQUEST['plugin'])) { 
					include_once("modules/research_assistant/search_plugin/".$_REQUEST['plugin']);
					$out="";
					$out = autosearch_options();
					return $out;
				}
			}
		}
		else if ($_REQUEST['action']=='auto_search') {
			$out .= print_r($_REQUEST, true);
			if (isset($_REQUEST['searchtype'])) {
				if (file_exists("modules/research_assistant/search_plugin/".$_REQUEST['searchtype'].".php")) { 
					include_once("modules/research_assistant/search_plugin/".$_REQUEST['searchtype'].".php");
					$out = "";
					$out = autosearch_process();
					return $out;
				}
			}
		}
		// Default
		else {
			// Since there is nothing here to do, we should just show folders.
			$out .= $this->print_menu();
			$out .= $this->print_folder_view();
		}

		// PGV modules use the mod_print_footer function to print out the default PGV footer.
		$out .= "<br />";
		$out .= mod_print_footer();

		// We have to return our output for the module system to display.
		return $out;
	}

	/**
	 * Adds people to the idividualtask table
	 * @param GeneratedTask $task	used when creating a new task from a generated task object
	 * @return void
	 */
	function add_people($task='')
	{
        global $TBLPREFIX, $DBCONN, $GEDCOMS, $GEDCOM;
        
		if(!is_object($task)) {
			$task_id = $_POST["taskid"];
			$people = preg_split("/;/", $_POST['personid']);
		}
		else {
			$task_id = $task->getId();
			$people = preg_split("/;/", $task->getPersonId());
		}
		foreach($people as $i=>$person_id) {
			if (!empty($person_id)) {
				$sql = 'INSERT INTO '.$TBLPREFIX.'individualtask (it_t_id, it_i_id, it_i_file) '."VALUES ('" . $DBCONN->escapeSimple($task_id) . "', '".$DBCONN->escapeSimple($person_id)."', '".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]['id'])."')";
				$res = dbquery($sql);
			}
		}
	}
	
	function loadGenTasks()
	{
		return $this->print_menu() .
				$this->print_form('ra_GenerateTasks');
	}

	function addGenTask($task, $folderId)
	{
		global $TBLPREFIX, $DBCONN, $GEDCOMS, $GEDCOM;
		$taskid = get_next_id("tasks", "t_id");
		$task->setID($taskid);
		if (empty($folderId))
		{
			$out .= $this->print_menu();
			$out .= $this->printMessage("There was no folder to put the task in.", false);
			return $out;
		}

        // DEBUG 
		$sql = "INSERT INTO ".$TBLPREFIX."tasks (t_id, t_fr_id, t_title, t_description, t_startdate) "."VALUES ('".$DBCONN->escapeSimple($taskid)."', '".$DBCONN->escapeSimple($folderId)."', '".$DBCONN->escapeSimple($task->getName())."', '".$DBCONN->escapeSimple($task->getDescription())."', '".time()."')";
		$res = dbquery($sql);

		if ($task->getPersonId() != '') 
		{
			$this->add_people($task);
		}

		// ADD SOURCES
		if ($task->getSourceId() != '') 
		{
			$this->add_sources($taskid, $task->getSourceId());
		}
	}
}
?>