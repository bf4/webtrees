<?php
/**
 * phpGedView Research Assistant Tool - Form Loader Engine.
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
 * @version $Id$
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 * @author Hector Pena
 */

//-- security check, only allow access from module.php
if (preg_match("/ra_lang\...\.php$/", $_SERVER["PHP_SELF"])>0) {
	print "You cannot access a language file directly.";
	exit;
}
$pgv_lang["missing_info"] 		= "Missing Information";
$temp_out_autosearch = 	"This feature will automatically search Ancestry and FamilySearch, ";
$temp_out_autosearch .= "you may choose to search by name, and birth/death date <br />";
$pgv_lang["auto_search"]		= $temp_out_autosearch;
$pgv_lang["auto_search_text"]	= "Auto Search";
$pgv_lang["task_list"]			= "Tasks";
$pgv_lang["task_list_text"]		= "This area displays tasks you have created, click on VIEW to see the task";

// -- HELP COMMENTS
$temp_out_comments = "This section is to add comments to the person for other people to see and add feedback";
$pgv_lang["help_comments"] = $temp_out_comments;

// -- MENU ITEM MESSAGES
$pgv_lang["my_tasks"]							= "My Tasks";
$pgv_lang["add_task"]							= "Add Task";
$pgv_lang["view_folders"]						= "View Folders";
$pgv_lang["view_probabilities"]					= "View Probabilities";
$pgv_lang["up_folder"]							= "Up Folder";
$pgv_lang["edit_folder"]						= "Add/Edit Folder";
$pgv_lang["gen_tasks"]							= "Auto Generate Tasks";


// -- RA GENERAL MESSAGES
$pgv_lang["delete"]								= "Delete";
$pgv_lang["edit_task"]							= "Edit Task";
$pgv_lang["view"]								= "View";
$pgv_lang["name"]								= "Name";
$pgv_lang["folder"]								= "Folder";
$pgv_lang["completed"]							= "Completed";
$pgv_lang["comres"]								= "Comments/Results";
$pgv_lang["description"]						= "Description";
$pgv_lang["created"]							= "Created";
$pgv_lang["modified"]							= "Modified";
$pgv_lang["folder_list"]						= "Folder List";
$pgv_lang["details"]							= "Details";
$pgv_lang["result"]                     		= "Result";
$pgv_lang["okay"]                               = "Okay";

// -- RA_FOLDER MESSAGES
$pgv_lang["Edit_Task"]                 			= "Edit Task";
$pgv_lang["End_Date"]                 			= "End Date";
$pgv_lang["Start_Date"]                 		= "Start Date";
$pgv_lang["Task_Name"]                			= "Task Name";
$pgv_lang["Folder_Name"]                		= "Folder Name";
$pgv_lang["Folder_View"]                		= "Folder View";
$pgv_lang["Task_View"]                  		= "Task View";
$pgv_lang["page_header"]						= "Research Assistant Folders";
$pgv_lang["folder_new"]							= "Create New Folder";
$pgv_lang["folder_delete_check"]				= "Are you sure you want to delete this folder?";
$pgv_lang["no_folder_name"]             		= "Folder name field must be filled in.";
$pgv_lang["add_folder"]                 		= "Add Folder";
$pgv_lang["edit_folder"]                		= "Edit Folder";
$pgv_lang["folder_name"]                		= "Folder Name:";
$pgv_lang["Parent_Folder:"]             		= "Parent Folder:";
$pgv_lang["No_Parent"]                  		= "No Parent";
$pgv_lang["Folder_Description:"]        		= "Folder Description:";
$pgv_lang["Folder_names_must_be_unique"]		= "Folder names must be unique.";
$pgv_lang["folder_submitted"]          			= "Your folder has been submitted"; 
$pgv_lang["folder_problem"]             		= "There has a been problem with adding your folder, please try again";

// -- Missing Information Help 
$temp_out_missinginfo = "This area displays missing information about the record.";
$temp_out_missinginfo .= "Select a checkbox and folder, and press add task to create a task for the missing item.";  
$temp_out_missinginfo .= "Tasks already created will display 'view' instead of a checkbox <br />";
$temp_out_missinginfo .= " <a href=\"javascript:void(0);\" onClick=\"fullScreen('helpvids/MissingInformationUserHelp.htm');\">Click here to Open User Tutorial in Full Screen Window</a>";
$pgv_lang["ra_missing_info_help"] = $temp_out_missinginfo;

// -- RA_EDITFOLDER MESSAGES	
$pgv_lang["edit_research_folder"]			= "Edit Research Folder";
$pgv_lang["folder_not_exist"]				= "This folder does not exist: ";
$pgv_lang["folder_parent"]					= "Parent Folder";
$pgv_lang["parent_id"]						= "None";
$pgv_lang["folder_users"]					= "Other Users who can see this folder";

// -- RA_EDITLOG MESSAGES
$pgv_lang["edit_research_log"]				= "Edit Research Log";
$pgv_lang["log_not_exist"]					= "This log does not exist: ";

// -- RA_LOG MESSAGES
$pgv_lang["edit_log_entry"]					= "Edit Research Log Entry";
$pgv_lang["log_no_entry"]					= "ERROR: You do not have permission to access this item.";
$pgv_lang["log_modified"]					= "Last Modified";
$pgv_lang["log_modified_by"]				= "Last Modified By";
$pgv_lang["log_edit_entry"]					= "Edit this entry";

// -- RA_LISTLOGS MESSAGES
$pgv_lang["research_logs"]					= "Research Logs";
$pgv_lang["log_no_entry_folder"]			= "ERROR: You do not have permission to access this folder.";
$pgv_lang["folder_sub"]						= "Sub Folders";
$pgv_lang["folder_sub_new"]					= "Create New Sub Folder";
$pgv_lang["task_entry"]						= "Create new task.";
$pgv_lang["log_show"]						= "Show All Logs";
$pgv_lang["log_show_uncomplete"]			= "Show Uncompleted Logs";
$pgv_lang["log_show_complete"]				= "Show Completed Logs";
$pgv_lang["log_delete_check"]				= "Are you sure you want to delete this log entry?";

// -- RA_FUNCTIONS MESSAGES
$pgv_lang["function_folder_delete"]			= "ERROR: Cannot delete this folder because it still contains research log entries.<br />First move or delete these research log entries and then try to delete the folder again.";
$pgv_lang["function_subfolder_delete"]		= "ERROR: Cannot delete this folder because it still contains subfolders.<br />First move or delete these subfolders and then try to delete the folder again.";
$pgv_lang["folder_delete_ok"]				= "The folder #folder_name# has been deleted sucessfully.";
$pgv_lang["folder_update_ok"]				= "The folder #folder_name# has been sucessfully updated.";
$_SESSION['pgv_lang["keywords"]']			= "Keywords:";
$pgv_lang["folder_added"]					= "The folder #folder_name# was sucessfully added.";
$_SESSION['pgv_lang["search"]']				= "Search";

//-- RA_SEARCH MESSAGES
$pgv_lang["search_results"]					= "Search Results";
$pgv_lang["nothing_found"]					= "No matching logs found.";

//-- ERROR MESSAGES
$pgv_lang["no_folder"]						= "No folder exists yet. Please create a new folder first.";

//-- HELP MESSAGES
$pgv_lang["help_rs_folders.php"]			= "Research Assistant Folders<br /> #pgv_lang[sorry]#";
$pgv_lang["help_rs_editfolder.php"]			= "Research Assistant Edit Folders<br />#pgv_lang[sorry]#";
$pgv_lang["help_rs_editlog.php"]			= "Research Assistant Edit Log<br />#pgv_lang[sorry]#";
$pgv_lang["ra_fold_name_help"]				= "<H2><B>Folder View:</B></H2><ul><li><B>Folder Name:</B> This column contains the names of all of the folders you have created.</li><li><B>Description:</B> This column contains the description of the folders.</li></ul><br /><br /><a href=\"helpvids/ResearchAssistantUserHelp.htm\">Research Assistant Tutorial</a>";
$pgv_lang["ra_add_task_help"]				= "<H2><B>Add New Task:</B></H2></H2><ul><li><B>Title:</B>This should contain the title of the task that you are adding.</li><li><B>Folder:</B>In this field you can assign which folder you want your new task to go to.</li><li><B>Description:</B> Enter a description of the task you want to add.</li><li><B>Sources:</B> Assign any sources that you have for the task.</li><li><B>People:</B> Assign any people associated for the new task.</li></ul>";
$pgv_lang["ra_edit_folder_help"]			= "<H2><B>Edit Folder:</B></H2><ul><li><B>Folder Name:</B> This is where you should add the title of the folder that you are editing.</B></li><li><B>Parent folder:</B> You can assign the parent folder, if any, of the folder you are editing.</B></li><li><B>Folder description:</B> This is the description of the folder you are editing.</B></li><ul>";
$pgv_lang["ra_add_folder_help"]				= "<H2><B>Add Folder:</B></H2><ul><li><B>Folder Name:</B> This is where you should add the title of the folder that you are adding.</B></li><li><B>Parent folder:</B> You can assign the parent folder, if any, of the folder you are adding.</B></li><li><B>Folder description:</B> This is the description of the folder you are adding.</B></li><ul>";
$pgv_lang["ra_view_task_help"]				= "<H2><B>Task View:</B></H2><ul><li><B>Task Name:</B> This column contains the name of all of the tasks you.</B></li><li><B>Description:</B> This column contains the description of the tasks.</li><li><B>Start Date:</B> This will contain the start dates of all the tasks.</li><li><B>Completed:</B>This will show whether or not a task is completed.</li><li><B>Details:</B>This will show all the details of a task.</li><li><B>Delete:</B>This will delete the task.</li><ul><br /><a href=\"helpvids/MissingInformationUserHelp.htm\">User Tutorial</a>";
$pgv_lang["ra_task_view_help"]				= "<H2><B>View Task:</B></H2><ul><li><B>Title:</B>This should contain the title of the task that you are adding.</li><li><B>People:</B> Assign any people associated for the new task.</li><li><B>Description:</B> Enter a description of the task you want to add.</li><li><B>Sources:</B> Assign any sources that you have for the task.</li><li>Click the 'Edit Task' button to edit the details of the task.</li></ul>";
$pgv_lang["ra_comments_help"]				= "<H2><B>Comments:</B></H2><ul><li>This will contain any comments related to the task. Click the 'Add New Comment' button to add any comments.</li></ul>";
$pgv_lang["ra_GenerateTasks_help"]			= "<H2><B>Generate Tasks:</B></H2><p>This form generates tasks from the _TODO tags in your GEDCOM file.</p><ul><li><B>Generate:</B> check each task to generate when you press the Generate button.</li><li><B>Task Name:</B> This is the name the task will be given.  This defaults to the text in the actual _TODO tag, excluding any CONT tag&quot;s</li><li><B>Task Description:</B> The description the task will be given.  This is generated from the text in the _TODO tag plus all of the associated CONT tag&quots.  </li><li><B>Edit:</B> click the link to edit that task.</li><li><B>Select Folder:</B> select the folder to put the generated tasks in.</li><li><B>Generate:</B> generates the tasks that have been checked.</li><li><B>Done:</B> redirects you to the Folder View page.</li></ul>";
$pgv_lang["ra_EditGenerateTasks_help"]		= "<H2><B>Edit Generated Task:</B></H2><p>This form allows you to edit the tasks generated from _TODO tags in your GEDCOM file.</p><ul><li><B>Task Name:</B> This is the name the task will be given.  </li><li><B>Task Description:</B> The description the task will be given. </li><li><B>People:</B> click the link to select the person to associate the task with.</li><li><B>Source:</B> click the link to select the source to associate the task with.</li><li><B>Save:</B> saves all your changes and redirects you to the Generate tasks page.</li><li><B>Cancel:</B> disregards all your changes and redirects you to the Generate tasks page.</li></ul>";
$pgv_lang["ra_configure_privacy_help"]		= "<H2><B>Configure Privacy:</B></H2></H2><ul><li><B>Show To Public:</B> Makes specified task available to everyone.</li><li><B>Show Only To Authenticated Users:</B> Makes specified task available to authenticated users only.</li><li><B> Show To Admin Users:</B> Makes specified task available to admin users only.</li><li><B> Hide Even From Admin Users:</B> Makes specified task not available to anyone.</li></ul>";

//-- RA_VIEWTASK MESSAGES
$pgv_lang["view_task"]						= "View Task";
$pgv_lang["comments"]						= "Comments";
$pgv_lang["add_new_comment"]				= "Add New Comment";
$pgv_lang["no_sources"]						= "There are no sources associated with this task.";
$pgv_lang["no_people"]						= "There are no people associated with this task.";
$pgv_lang["no_indi_tasks"]					= "No tasks associated with this individual.";
$pgv_lang["edit_comment"]					= "Edit Comment";
$pgv_lang["comment_success"]				= "Your comment was successfully added.";
$pgv_lang['comment_body']					= 'Comment';

//-- RA_COMMENT MESSAGES
$pgv_lang["comment_delete_check"]		= "Are you sure you want to delete this comment?";

//-- RA_ADDTASK MESSAGES
$pgv_lang["add_new_task"]				= "Add New Task";
$pgv_lang["title"]						= "Title";
$pgv_lang["submit"]						= "Submit";

//-- RA_EDITTASK MESSAGES
$pgv_lang["edit_task"]					= "Edit Task";

//-- RA_CONFIGURE PRIVACY MESSAGES
$pgv_lang["configure_privacy"]		    = "Configure Privacy";
$pgv_lang["show_my_tasks"]              = "Show My Tasks";
$pgv_lang["show_add_task"]		        = "Show Add Task";
$pgv_lang["show_auto_gen_task"]         = "Show Auto Generate Task";
$pgv_lang["show_view_folders"]		    = "Show View Folders";
$pgv_lang["show_add_folder"]		    = "Show Add Folder";
$pgv_lang["show_add_unlinked_source"]   = "Show Add Unlinked Source";
$pgv_lang["show_view_probabilities"]	= "Show View Probabilities";




//-- COMMENT HELP
$pgv_lang["comment_title_help"]			= "Comment Title Help here.";
$pgv_lang["comment_help"]				= "Click here for help.";

//-- Census Forms
$pgv_lang["rows"]                       = "Number of Rows";
$pgv_lang["state"]                      = "State";
$pgv_lang["call/url"]                   = "Call Number/URL";
$pgv_lang["enumDate"]                   = "Enumeration Date";
$pgv_lang["county"]                     = "County";
$pgv_lang["city"]                       = "City";
$pgv_lang["page"]                       = "Page";                  
?>
