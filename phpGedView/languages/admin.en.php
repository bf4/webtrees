<?php
/**
 * English texts
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["associated_files"]		= "Associated files:";
$pgv_lang["remove_all_files"]		= "Remove all nonessential files";
$pgv_lang["warn_file_delete"]		= "This file contains important information such as language settings or pending change data.  Are you sure you want to delete this file?";
$pgv_lang["deleted_files"]          = "Deleted files:";
$pgv_lang["index_dir_cleanup_inst"]	= "To delete a file or subdirectory from the Index directory drag it to the vicinity of the Delete button or select its checkbox.  Click the Delete button to permanently remove the indicated files.<br /><br />Files marked with <img src=\"./images/RESN_confidential.gif\" /> are required for proper operation and cannot be removed.<br />Files marked with <img src=\"./images/RESN_locked.gif\" /> have important settings or pending change data and should only be deleted if you are sure you know what you are doing.<br /><br />";
$pgv_lang["index_dir_cleanup"]		= "Cleanup Index directory";
$pgv_lang["clear_cache_succes"]		= "The cache files have been removed.";
$pgv_lang["clear_cache"]			= "Clear cache files";
$pgv_lang["sanity_err0"]			= "Errors:";
$pgv_lang["sanity_err1"]			= "You need to have PHP version 4.3 or higher.";
$pgv_lang["sanity_err2"]			= "The file or directory <i>#GLOBALS[whichFile]#</i> does not exist. Please verify that the file or directory exists, was not mis-named, and Read permissions are set correctly.";
$pgv_lang["sanity_err3"]			= "The file <i>#GLOBALS[whichFile]#</i> did not upload correctly. Please try to upload the file again.";
$pgv_lang["sanity_err4"]			= "The file <i>config.php</i> is corrupt.";
$pgv_lang["sanity_err5"]			= "The <i>config.php</i> file is not writable.";
$pgv_lang["sanity_err6"]			= "The <i>#GLOBALS[INDEX_DIRECTORY]#</i> directory is not writable.";
$pgv_lang["sanity_warn0"]			= "Warnings:";
$pgv_lang["sanity_warn1"]			= "The <i>#GLOBALS[MEDIA_DIRECTORY]#</i> directory is not writable.  You will not be able to upload media files or generate thumbnails in PhpGedView.";
$pgv_lang["sanity_warn2"]			= "The <i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i> directory is not writable.  You will not be able to upload thumbnails or generate thumbnails in PhpGedView.";
$pgv_lang["sanity_warn3"]			= "The GD imaging library does not exist. PhpGedView will still function, but some of the features, such as thumbnail generation and the circle diagram, will not work without the GD library.  Please see <a href='http://www.php.net/manual/en/ref.image.php'>http://www.php.net/manual/en/ref.image.php</a> for more information.";
$pgv_lang["sanity_warn4"]			= "The XML Parser library does not exist. PhpGedView will still function, but some of the features, such as report generation and web services, will not work without the XML Parser library. Please see <a href='http://www.php.net/manual/en/ref.xml.php'>http://www.php.net/manual/en/ref.xml.php</a> for more information.";
$pgv_lang["sanity_warn5"]			= "The DOM XML library does not exist. PhpGedView will still function, but some of the features, such as Gramps Export features in the clippings cart, download, and web services, will not work. Please see <a href='http://www.php.net/manual/en/ref.domxml.php'>http://www.php.net/manual/en/ref.domxml.php</a> for more information.";
$pgv_lang["sanity_warn6"]			= "The Calendar library does not exist. PhpGedView will still function, but some of the features, such as conversion to other calendars such as Hebrew or French, will not work.  It is not essential for running PhpGedView. Please see <a href='http://www.php.net/manual/en/ref.calendar.php'>http://www.php.net/manual/en/ref.calendar.php</a> for more information.";
$pgv_lang["ip_address"]				= "IP address";
$pgv_lang["date_time"]				= "Date and time";
$pgv_lang["log_message"]			= "Log Message";
$pgv_lang["searchtype"]				= "Search type";
$pgv_lang["query"]					= "Query";
$pgv_lang["user"]					= "Authenticated user";
$pgv_lang["thumbnail_deleted"]		= "Thumbnail file successfully deleted.";
$pgv_lang["thumbnail_not_deleted"]	= "Thumbnail file could not be deleted.";
$pgv_lang["step2"]					= "Step 2 of 4:";
$pgv_lang["refresh"]				= "Refresh";
$pgv_lang["move_file_success"]		= "Media and thumbnail files successfully moved.";
$pgv_lang["media_folder_corrupt"]	= "The media folder is corrupted.";
$pgv_lang["media_file_not_deleted"]	= "Media file could not be deleted.";
$pgv_lang["gedcom_deleted"] 		= "GEDCOM [#GED#] successfully deleted.";
$pgv_lang["gedadmin"]				= "GEDCOM administrator";
$pgv_lang["full_name"]				= "Full Name";
$pgv_lang["error_header"]			= "The GEDCOM file, <b>#GEDCOM#</b>, does not exist at the specified location.";
$pgv_lang["confirm_delete_file"]	= "Are you sure you want to delete this file?";
$pgv_lang["confirm_folder_delete"] = "Are you sure you want to delete this folder?";
$pgv_lang["confirm_remove_links"]	= "Are you sure you want to remove all links to this object?";
$pgv_lang["PRIV_PUBLIC"]			= "Show to public";
$pgv_lang["PRIV_USER"]				= "Show only to authenticated users";
$pgv_lang["PRIV_NONE"]				= "Show only to admin users";
$pgv_lang["PRIV_HIDE"]				= "Hide even from admin users";
$pgv_lang["manage_gedcoms"] 		= "Manage GEDCOMs and edit Privacy";
$pgv_lang["keep_media"]				= "Keep media links";
$pgv_lang["files_in_backup"]		= "Files included in this backup";
$pgv_lang["created_remotelinks"]	= "Successfully created <i>Remotelinks</i> table.";
$pgv_lang["created_remotelinks_fail"] 	= "Unable to create <i>Remotelinks</i> table.";
$pgv_lang["created_indis"]			= "Successfully created <i>Individuals</i> table.";
$pgv_lang["created_indis_fail"] 	= "Unable to create <i>Individuals</i> table.";
$pgv_lang["created_fams"]			= "Successfully created <i>Families</i> table.";
$pgv_lang["created_fams_fail"]		= "Unable to create <i>Families</i> table.";
$pgv_lang["created_sources"]		= "Successfully created <i>Sources</i> table.";
$pgv_lang["created_sources_fail"]	= "Unable to create <i>Sources</i> table.";
$pgv_lang["created_other"]			= "Successfully created <i>Other</i> table.";
$pgv_lang["created_other_fail"] 	= "Unable to create <i>Other</i> table.";
$pgv_lang["created_places"] 		= "Successfully created <i>Places</i> table.";
$pgv_lang["created_places_fail"]	= "Unable to create <i>Places</i> table.";
$pgv_lang["created_placelinks"] 	= "Successfully created <i>Place links</i> table.";
$pgv_lang["created_placelinks_fail"]	= "Unable to create <i>Place links</i> table.";
$pgv_lang["created_media_fail"]	= "Unable to create <i>Media</i> table.";
$pgv_lang["created_media_mapping_fail"]	= "Unable to create <i>Media mappings</i> table.";
$pgv_lang["no_thumb_dir"]			= " thumbnail directory does not exist and it could not be created.";
$pgv_lang["folder_created"]			= "Directory created";
$pgv_lang["folder_no_create"]		= "Directory could not be created";
$pgv_lang["security_no_create"]		= "Security Warning: Could not create file <b><i>index.php</i></b> in ";
$pgv_lang["security_not_exist"]		= "Security Warning: File <b><i>index.php</i></b> does not exist in ";
$pgv_lang["label_add_search_server"]	= "Add IP"; 
$pgv_lang["label_add_server"]      		= "Add";
$pgv_lang["label_ban_server"]			= "Submit";
$pgv_lang["label_delete"]           	= "Delete";
$pgv_lang["progress_bars_info"]			= "The status bars below will let you know how the Import is progressing.  If the time limit runs out the Import will be stopped and you will be asked to press a <b>Continue</b> button.  If you don't see the <b>Continue</b> button, you must restart the Import with a smaller time limit value.";
$pgv_lang["upload_replacement"]			= "Upload Replacement";
$pgv_lang["about_user"]					= "You must first create your main administrative user.  This user will have privileges to update the configuration files, view private data, and create other users.";
$pgv_lang["access"]						= "Access";
$pgv_lang["add_gedcom"] 				= "Add GEDCOM";
$pgv_lang["add_new_gedcom"] 			= "Create a new GEDCOM";
$pgv_lang["add_new_language"]			= "Add files and settings for a new language";
$pgv_lang["add_user"]					= "Add a new user";
$pgv_lang["admin_gedcom"]				= "Admin GEDCOM";
$pgv_lang["admin_gedcoms"]				= "Click here to administer GEDCOMs";
$pgv_lang["admin_geds"]					= "Data and GEDCOM administration";
$pgv_lang["admin_info"]					= "Informational";
$pgv_lang["admin_site"]					= "Site administration";
$pgv_lang["admin_user_warnings"]		= "One or more user accounts have warnings";
$pgv_lang["admin_verification_waiting"] = "User accounts awaiting verification by admin";
$pgv_lang["administration"] 			= "Administration";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]		= "Allow GEDCOM switching";
$pgv_lang["ALLOW_REMEMBER_ME"]			= "Show <b>Remember Me</b> option on Login page";
$pgv_lang["ALLOW_USER_THEMES"]			= "Allow users to select their own theme";
$pgv_lang["ansi_encoding_detected"] 	= "ANSI file encoding detected.	PhpGedView works best with files encoded in UTF-8.";
$pgv_lang["ansi_to_utf8"]				= "Convert this ANSI encoded GEDCOM to UTF-8?";
$pgv_lang["apply_privacy"]				= "Apply privacy settings?";
$pgv_lang["back_useradmin"]				= "Back to User Administration";
$pgv_lang["bytes_read"] 				= "Bytes read:";
$pgv_lang["calc_marr_names"]			= "Calculating Married Names";
$pgv_lang["can_admin"]					= "User can administer";
$pgv_lang["can_edit"]					= "Access level";
$pgv_lang["change_id"]					= "Change Individual ID to:";
$pgv_lang["choose_priv"]				= "Choose privacy level:";
$pgv_lang["cleanup_places"] 			= "Cleanup Places";
$pgv_lang["cleanup_users"]				= "Cleanup users";
$pgv_lang["click_here_to_continue"]		= "Click here to continue.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Click here to go to the Pedigree tree.";
$pgv_lang["comment"]							= "Admin comments on user";
$pgv_lang["comment_exp"]						= "Admin warning at date";
$pgv_lang["config_help"]						= "Configuration help";
$pgv_lang["config_still_writable"]				= "Your <i>config.php</i> file is still writable.  For security, you should set the permissions of this file back to read-only when you have finished configuring your site.";
$pgv_lang["configuration"]						= "Configuration";
$pgv_lang["configure"]							= "Configure PhpGedView";
$pgv_lang["configure_head"]						= "PhpGedView Configuration";
$pgv_lang["confirm_gedcom_delete"]				= "Are you sure you want to delete this GEDCOM";
$pgv_lang["confirm_user_delete"]				= "Are you sure you want to delete the user";
$pgv_lang["create_user"]						= "Create User";
$pgv_lang["current_users"]						= "User List";
$pgv_lang["daily"]								= "Daily";
$pgv_lang["dataset_exists"] 					= "A GEDCOM with this file name has already been imported into the database.";
$pgv_lang["date_registered"]					= "Date registered";
$pgv_lang["day_before_month"]					= "Day before Month (DD MM YYYY)";
$pgv_lang["DEFAULT_GEDCOM"]						= "Default GEDCOM";
$pgv_lang["default_user"]						= "Create the default administrative user.";
$pgv_lang["del_gedrights"]						= "GEDCOM no longer active, remove user references.";
$pgv_lang["del_proceed"]						= "Continue";
$pgv_lang["del_unvera"]							= "User not verified by administrator.";
$pgv_lang["del_unveru"]							= "User didn't verify within 7 days.";
$pgv_lang["do_not_change"]						= "Do not change";
$pgv_lang["download_file"]						= "Download File";
$pgv_lang["download_gedcom"]					= "Download GEDCOM";
$pgv_lang["download_here"]						= "Click here to download file.";
$pgv_lang["download_note"]						= "NOTE: Large GEDCOMs can take a long time to process before downloading.  If PHP times out before the download is complete, you may not get a complete download.<br /><br />To make sure that the GEDCOM file was downloaded correctly, check that its last line is <b>0&nbsp;TRLR</b>.  GEDCOM files are text; you can use any suitable text editor, but be sure to <u>not</u> save the downloaded GEDCOM file after you have inspected it.<br /><br />In general, it could take as much time to download as it took to import your GEDCOM file.";
$pgv_lang["editaccount"]						= "Allow this user to edit his account information";
$pgv_lang["empty_dataset"]						= "Do you want to erase the old data and replace it with this new data?";
$pgv_lang["empty_lines_detected"]				= "Empty lines were detected in your GEDCOM file.	On cleanup, these empty lines will be removed.";
$pgv_lang["enable_disable_lang"]				= "Configure supported languages";
$pgv_lang["error_ban_server"]       			= "Invalid IP address.";
$pgv_lang["error_delete_person"]   				= "You must select the person whose remote link you wish to delete.";
$pgv_lang["error_header_write"] 				= "The GEDCOM file, <b>#GEDCOM#</b>, is not writable. Please check attributes and access rights.";
$pgv_lang["error_siteauth_failed"]				= "Failed to authenticate to remote site";
$pgv_lang["error_url_blank"]					= "Please do not leave remote site title or URL blank";
$pgv_lang["error_view_info"]       				= "You must select the person whose information you wish to view.";
$pgv_lang["example_date"]						= "Example of invalid date from your GEDCOM:";
$pgv_lang["example_place"]						= "Example of invalid place from your GEDCOM:";
$pgv_lang["fbsql"]								= "FrontBase";
$pgv_lang["found_record"]						= "Found record";
$pgv_lang["ged_download"]						= "Download";
$pgv_lang["ged_import"] 						= "Import";
$pgv_lang["ged_check"] 							= "Check";
$pgv_lang["gedcom_adm_head"]					= "GEDCOM Administration";
$pgv_lang["gedcom_config_write_error"]			= "E R R O R !!!<br />Could not write to file <i>#GLOBALS[whichFile]#</i>.  Please check it for proper Write permissions.";
$pgv_lang["gedcom_downloadable"] 				= "This GEDCOM file is downloadable over the internet!<br />Please see the SECURITY section of the <a href=\"readme.txt\"><b>readme.txt</b></a> file to fix this problem";
$pgv_lang["gedcom_file"]						= "GEDCOM File:";
$pgv_lang["gedcom_not_imported"]				= "This GEDCOM has not yet been imported.";
$pgv_lang["ibase"]								= "InterBase";
$pgv_lang["ifx"]								= "Informix";
$pgv_lang["img_admin_settings"] 				= "Edit Image Manipulation Configuration";
$pgv_lang["import_complete"]					= "Import complete";
$pgv_lang["import_marr_names"]					= "Import Married Names";
$pgv_lang["import_options"]						= "Import Options";
$pgv_lang["import_progress"]					= "Import Progress...";
$pgv_lang["import_statistics"]					= "Import Statistics";
$pgv_lang["import_time_exceeded"]				= "The execution time limit was reached.  Click the Continue button below to resume importing the GEDCOM file.";
$pgv_lang["inc_languages"]						= " Languages";
$pgv_lang["INDEX_DIRECTORY"]					= "Index file directory";
$pgv_lang["invalid_dates"]						= "Detected invalid date formats, on cleanup these will be changed to format of DD MMM YYYY (eg. 1 JAN 2004).";
$pgv_lang["BOM_detected"] 						= "A Byte Order Mark (BOM) was detected at the beginning of the file. On cleanup, this special code will be removed.";
$pgv_lang["invalid_header"] 					= "Detected lines before the GEDCOM header <b>0&nbsp;HEAD</b>.  On cleanup, these lines will be removed.";
$pgv_lang["label_add_server"]  					= "Add";
$pgv_lang["label_add_search_server"]			= "Add IP";
$pgv_lang["label_added_servers"]				= "Added Remote Servers";
$pgv_lang["label_ban_server"]					= "Submit";
$pgv_lang["label_banned_servers"]  				= "Ban Sites by IP";
$pgv_lang["label_families"]         			= "Families";
$pgv_lang["label_gedcom_id2"]       			= "GEDCOM ID:";
$pgv_lang["label_individuals"]      			= "Individuals";
$pgv_lang["label_manual_search_engines"]		= "Manually mark Search Engines by IP";
$pgv_lang["label_new_server"]     				= "Add new site";
$pgv_lang["label_password_id"]					= "Password";
$pgv_lang["label_remove_ip"]					= "Ban IP Address (Eg: 198.128.*.*): ";
$pgv_lang["label_remove_search"]				= "Mark IP addresses as Search Engine Spiders: ";
$pgv_lang["label_server_info"]     				= "All people remotely linked through the site:";
$pgv_lang["label_server_url"]       			= "Site URL/IP";
$pgv_lang["label_username_id"]					= "Username";
$pgv_lang["label_view_local"]       			= "View local information on person";
$pgv_lang["label_view_remote"]     			 	= "View remote information on person";
$pgv_lang["LANG_SELECTION"] 					= "Supported languages";
$pgv_lang["LANGUAGE_DEFAULT"]					= "You have not configured the languages your site will support.<br />PhpGedView will use its default actions.";
$pgv_lang["last_login"]							= "Last logged in";
$pgv_lang["lasttab"]							= "Last Visited Tab For Individual";
$pgv_lang["leave_blank"]						= "Leave password blank if you want to keep the current password.";
$pgv_lang["link_manage_servers"]   				= "Manage Sites";
$pgv_lang["logfile_content"]					= "Content of log file";
$pgv_lang["macfile_detected"]					= "Macintosh file detected.  On cleanup your file will be converted to a DOS file.";
$pgv_lang["mailto"]								= "Mailto link";
$pgv_lang["merge_records"]						= "Merge records";
$pgv_lang["message_to_all"]						= "Send message to all users";
$pgv_lang["messaging"]							= "PhpGedView internal messaging";
$pgv_lang["messaging2"]							= "Internal messaging with emails";
$pgv_lang["messaging3"]							= "PhpGedView sends emails with no storage";
$pgv_lang["month_before_day"]					= "Month before Day (MM DD YYYY)";
$pgv_lang["monthly"]							= "Monthly";
$pgv_lang["msql"]								= "Mini SQL";
$pgv_lang["mssql"]								= "Microsoft SQL server";
$pgv_lang["mysql"]								= "MySQL";
$pgv_lang["mysqli"]								= "MySQL 4.1+ and PHP 5";
$pgv_lang["never"]								= "Never";
$pgv_lang["no_logs"]							= "Disable logging";
$pgv_lang["no_messaging"]						= "No contact method";
$pgv_lang["none"]								= "None";
$pgv_lang["oci8"]								= "Oracle 7+";
$pgv_lang["page_views"]							= "&nbsp;&nbsp;page views in&nbsp;&nbsp;";
$pgv_lang["performing_validation"]				= "Performing GEDCOM validation...";
$pgv_lang["pgsql"]								= "PostgreSQL";
$pgv_lang["pgv_config_write_error"] 			= "Error!!! Cannot write to the PhpGedView configuration file.  Please check file and directory permissions and try again.";
$pgv_lang["PGV_MEMORY_LIMIT"]					= "Memory limit";
$pgv_lang["pgv_registry"]						= "View other sites using PhpGedView";
$pgv_lang["PGV_SESSION_SAVE_PATH"]				= "Session save path";
$pgv_lang["PGV_SESSION_TIME"]					= "Session timeout";
$pgv_lang["PGV_SIMPLE_MAIL"] 					= "Use simple mail headers in external mails";
$pgv_lang["PGV_STORE_MESSAGES"]					= "Allow messages to be stored online";
$pgv_lang["phpinfo"]							= "PHP information";
$pgv_lang["place_cleanup_detected"] 			= "Invalid place encodings were detected.  These errors should be fixed.";
$pgv_lang["please_be_patient"]					= "Please be patient";
$pgv_lang["privileges"]							= "Privileges";
$pgv_lang["reading_file"]						= "Reading GEDCOM file";
$pgv_lang["readme_documentation"]				= "README documentation";
$pgv_lang["remove_ip"] 							= "Remove IP";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"] 	= "Require an administrator to approve new user registrations";
$pgv_lang["review_readme"]						= "You should review the <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> file before continuing to configure PhpGedView.<br /><br />";
$pgv_lang["rootid"] 							= "Pedigree Chart Root Person";
$pgv_lang["seconds"]							= "&nbsp;&nbsp;seconds";
$pgv_lang["select_an_option"]					= "Select an option below:";
$pgv_lang["SERVER_URL"]							= "PhpGedView URL";
$pgv_lang["show_phpinfo"]						= "Show PHP information page";
$pgv_lang["siteadmin"]							= "Site administrator";
$pgv_lang["skip_cleanup"]						= "Skip Cleanup";
$pgv_lang["sqlite"]								= "SQLite";
$pgv_lang["sybase"]								= "Sybase";
$pgv_lang["sync_gedcom"]						= "Synchronize User Settings with GEDCOM Data";
$pgv_lang["system_time"]						= "Current Server Time:";
$pgv_lang["user_time"]							= "Current User Time:";
$pgv_lang["TBLPREFIX"]							= "Database Table Prefix";
$pgv_lang["themecustomization"]					= "Theme Customization";
$pgv_lang["time_limit"]							= "Time limit:";
$pgv_lang["title_manage_servers"]   			= "Manage Sites";
$pgv_lang["title_view_conns"]       			= "View Connections";
$pgv_lang["translator_tools"]					= "Translator tools";
$pgv_lang["update_myaccount"]					= "Update MyAccount";
$pgv_lang["update_user"]						= "Update User Account";
$pgv_lang["upload_gedcom"]						= "Upload GEDCOM";
$pgv_lang["USE_REGISTRATION_MODULE"]			= "Allow visitors to request account registration";
$pgv_lang["user_auto_accept"]					= "Automatically accept changes made by this user";
$pgv_lang["user_contact_method"]				= "Preferred Contact Method";
$pgv_lang["user_create_error"]					= "Unable to add user.  Please try again.";
$pgv_lang["user_created"]						= "User created successfully.";
$pgv_lang["user_default_tab"]					= "Default Tab to show on Individual Information page";
$pgv_lang["user_path_length"]					= "Max relationship privacy path length";
$pgv_lang["user_relationship_priv"]				= "Limit access to related people";
$pgv_lang["users_admin"]						= "Site Administrators";
$pgv_lang["users_gedadmin"]						= "GEDCOM Administrators";
$pgv_lang["users_total"]						= "Total number of users";
$pgv_lang["users_unver"]						= "Unverified by User";
$pgv_lang["users_unver_admin"]					= "Unverified by Administrator";
$pgv_lang["usr_deleted"]						= "Deleted user: ";
$pgv_lang["usr_idle"]							= "Number of months since the last login for a user's account to be considered inactive: ";
$pgv_lang["usr_idle_toolong"]					= "User's account has been inactive too long: ";
$pgv_lang["usr_no_cleanup"]						= "Nothing found to cleanup";
$pgv_lang["usr_unset_gedcomid"]					= "Unset GEDCOM ID for ";
$pgv_lang["usr_unset_rights"]					= "Unset GEDCOM rights for ";
$pgv_lang["usr_unset_rootid"]					= "Unset root ID for ";
$pgv_lang["valid_gedcom"]						= "Valid GEDCOM detected. No cleanup required.";
$pgv_lang["validate_gedcom"]					= "Validate GEDCOM";
$pgv_lang["verified"]							= "User verified himself";
$pgv_lang["verified_by_admin"]					= "User approved by Admin";
$pgv_lang["verify_gedcom"]						= "Verify GEDCOM";
$pgv_lang["verify_upload_instructions"]			= "A GEDCOM file with the same name has been found. If you choose to continue, the old GEDCOM file will be replaced with the file that you uploaded and the Import process will begin again.  If you choose to cancel, the old GEDCOM will remain unchanged.";
$pgv_lang["view_changelog"]						= "View changelog.txt file";
$pgv_lang["view_logs"]							= "View log files";
$pgv_lang["view_readme"]						= "View readme.txt file";
$pgv_lang["visibleonline"]						= "Visible to other users when online";
$pgv_lang["visitor"]							= "Visitor";
$pgv_lang["warn_users"]							= "Users with warnings";
$pgv_lang["weekly"]								= "Weekly";
$pgv_lang["welcome_new"]						= "Welcome to your new PhpGedView website.";
$pgv_lang["yearly"]								= "Yearly";
$pgv_lang["admin_OK_subject"]					= "Approval of account at #SERVER_NAME#";
$pgv_lang["admin_OK_message"]					= "The administrator at the PhpGedView site #SERVER_NAME# has approved your application for an account.  You may now login by accessing the following link:\r\n\r\n#SERVER_NAME#\r\n";

// Text for the Gedcom Checker
$pgv_lang["gedcheck"]     = "Gedcom checker";          // Module title
$pgv_lang["gedcheck_text"]= "This module checks the format of a GEDCOM file against the <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">5.5.1 GEDCOM Specification</a>.  It also checks for a number of common errors in your data.  Note that there are lots of versions, extensions and variations on the specification so you should not be concerned with any issues other than those flagged as \"Critical\".  The explanation for all the line-by-line errors can be found in the specification, so please check there before asking for help.";
$pgv_lang["level"]        = "Level";                   // Levels of checking
$pgv_lang["critical"]     = "Critical";
$pgv_lang["error"]        = "Error";
$pgv_lang["warning"]      = "Warning";
$pgv_lang["info"]         = "Info";
$pgv_lang["open_link"]    = "Open links in";           // Where to open links
$pgv_lang["same_win"]     = "Same tab/window";
$pgv_lang["new_win"]      = "New tab/window";
$pgv_lang["context_lines"]= "Lines of GEDCOM context"; // Number of lines either side of error
$pgv_lang["all_rec"]      = "All records";             // What to show
$pgv_lang["err_rec"]      = "Records with errors";
$pgv_lang["missing"]      = "missing";                 // General error messages
$pgv_lang["multiple"]     = "multiple";
$pgv_lang["invalid"]      = "invalid";
$pgv_lang["too_many"]     = "too many";
$pgv_lang["too_few"]      = "too few";
$pgv_lang["no_link"]      = "does not link back";
$pgv_lang["data"]         = "data";                    // Specific errors (used with general errors)
$pgv_lang["see"]          = "see";
$pgv_lang["noref"]        = "Nothing references this record";
$pgv_lang["tag"]          = "tag";
$pgv_lang["spacing"]      = "spacing";
$pgv_lang["ADVANCED_NAME_FACTS"] = "Advanced name facts";
$pgv_lang["ADVANCED_PLAC_FACTS"] = "Advanced place name facts";
$pgv_lang["SURNAME_TRADITION"] = "Surname tradition"; // Default surname inheritance
$pgv_lang["spanish"]           = "Spanish";
$pgv_lang["portuguese"]        = "Portuguese";
$pgv_lang["icelandic"]         = "Icelandic";
$pgv_lang["paternal"]          = "Paternal";

?>
