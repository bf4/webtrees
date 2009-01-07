<?php
/**
 * Slovenian texts
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @translator Leon Kos
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["upload_a_gedcom"] 		= "Naloži datoteko GEDCOM";
$pgv_lang["start_entering"] 		= "Začni vnašati podatke";
$pgv_lang["add_gedcom_from_path"] 	= "Dodaj GEDCOM z imenika";
$pgv_lang["get_started_instructions"]	= "Izberi eno od teh možnosti za začetek dela v PhpGedView";

$pgv_lang["admin_users_exists"]		= "Obstajajo naslednji upravitelji:";
$pgv_lang["install_step_1"] = "Preveri okolje";
$pgv_lang["install_step_2"] = "Povezava z bazo";
$pgv_lang["install_step_3"] = "Izdelaj tabele";
$pgv_lang["install_step_4"] = "Nastavitve spletne strani";
$pgv_lang["install_step_5"] = "Jeziki";
$pgv_lang["install_step_6"] = "Shrani nastavitve";
$pgv_lang["install_step_7"] = "Dodaj upravitelja";
$pgv_lang["install_wizard"] = "Namestitveni čarovnik";
$pgv_lang["basic_site_config"] = "Osnovne nastavitve";
$pgv_lang["adv_site_config"] = "Napredne nastavitve";
$pgv_lang["config_not_saved"] = "*Vaše nastavitve ne bodo<br />shranjene vse do koraka 6";
$pgv_lang["download_config"] = "Potegni config.php";
$pgv_lang["site_unavailable"] = "Spletna stran je trenutno nedostopna";
$pgv_lang["to_manage_users"] = "Za upravljanje uporabnikov uporabite stran <a href=\"useradmin.php\">Upravljanje uporabnikov</a>.";
$pgv_lang["db_tables_created"] = "Tabele podatkovne baze so bile uspešno izdelane";
$pgv_lang["config_saved"] = "Nastavitve so bile uspešno shranjene";
$pgv_lang["checking_errors"]		= "Preverjanje na možne napake...";
$pgv_lang["checking_php_version"]		= "Preverjanje zahtevane različice PHP:";
$pgv_lang["failed"]		= "Neuspešno";
$pgv_lang["pgv_requires_version"]		= "PhpGedView potrebije PHP različico ".PGV_REQUIRED_PHP_VERSION." ali višjo.";
$pgv_lang["using_php_version"]		= "Uporabljate PHP različico ".PHP_VERSION;
$pgv_lang["checking_db_support"]		= "Preverjanje minimalnih zahtev za podatkovno bazo:";
$pgv_lang["no_db_extensions"]		= "Nimate nobene podprte razširitve za dostop do podatkovne baze.";
$pgv_lang["db_ext_support"]		= "Imate podporo #DBEXT#";
$pgv_lang["checking_config.php"]		= "Preverjanje config.php:";
$pgv_lang["config.php_missing"]		= "Datoteka config.php ni bila najdena.";
$pgv_lang["config.php_missing_instr"]		= "Ta namestitveni čarovnik ne bo mogel zapisati nastavitve v datoteko config.php.  Lahko pa skopirate datoteko config.dist in jo preimenujete v config.php.  Možno je tudi, da po tem, ko končate s tem čarovnikom, dolvzamete nastavitve in jih naložite v ciljno datoteko config.php.";
$pgv_lang["config.php_not_writable"]		= "config.php ni dostopen za pisanje.";
$pgv_lang["config.php_not_writable_instr"]		= "Ta namestitveni čarovnik ne bo model zapisati vaše nastavitve v datoteko config.php.  Za datoteko lahko nastavite dovoljenje za pisanje ali pa po tem, ko končate s tem čarovnikom, dolvzamete nastavitve in jih naložite v ciljno datoteko config.php.";
$pgv_lang["passed"]		= "Preverjeno";
$pgv_lang["config.php_writable"]		= "config.php je dostopen za pisanje.";
$pgv_lang["checking_warnings"]		= "Preverjanje opozoril...";
$pgv_lang["checking_timelimit"]		= "Preverjanje možnosti za spremembo časovne omejitve:";
$pgv_lang["cannot_change_timelimit"]		= "Sprememba časovne omejitve ni možna.";
$pgv_lang["cannot_change_timelimit_instr"]		= "Ne boste mogli poganjati vseh funkcij v veliki bazi z vekiko osebami.";
$pgv_lang["current_max_timelimit"]		= "Vaša časovna omejitev je";
$pgv_lang["check_memlimit"]		= "Preverjanje zmožnosti za spremembo omejitve spomina:";
$pgv_lang["cannot_change_memlimit"]		= "Sprememba omejitve spomina ni možna.";
$pgv_lang["cannot_change_memlimit_instr"]		= "Ne boste mogli poganjati vseh funkcij v veliki bazi z vekiko osebami.";
$pgv_lang["current_max_memlimit"]		= "Vaša trenutna omejitev spomina je";
$pgv_lang["check_upload"]		= "Preverjanje možnosti za nalaganje datotek:";
$pgv_lang["current_max_upload"]		= "Največja velikost naložene datoteke je:";
$pgv_lang["check_gd"]		= "Preverjanje prisotnosti slikovne knjižnice GD:";
$pgv_lang["cannot_use_gd"]		= "Nimate likovne knjižnice GD.  Samodejna izdelava sličic ne bo možna.";
$pgv_lang["check_sax"]		= "Preverjanje prisotnosti knjižnice SAX XML:";
$pgv_lang["cannot_use_sax"]		= "Nimate knjižnice SAX XML.  Ne boste mogli poganjati poročil in še nekaterih pomožnih funkcij.";
$pgv_lang["check_dom"]		= "Preverjanje prisotnosti knjižnice DOM XML:";
$pgv_lang["cannot_use_dom"]		= "Nimate knjižnice DOM XML.  Ne boste mogli izvažati XML.";
$pgv_lang["check_calendar"]		= "Checking for Advanced Calendar library:";
$pgv_lang["cannot_use_calendar"]		= "You do not have the advanced calendar support. You will not be able to run some advanced calendar functions.";
$pgv_lang["warnings_passed"]		= "Preverjena so vsa opozorila.";
$pgv_lang["warning_instr"]		= "If any of the warnings do not pass you may still be able to run PhpGedView on this server, but some functionality may be disabled or you may experience poor performance.";

$pgv_lang["associated_files"]		= "Associated files:";
$pgv_lang["remove_all_files"]		= "Remove all nonessential files";
$pgv_lang["warn_file_delete"]		= "This file contains important information such as language settings or pending change data.  Are you sure you want to delete this file?";
$pgv_lang["deleted_files"]          = "Deleted files:";
$pgv_lang["index_dir_cleanup_inst"]	= "To delete a file or subdirectory from the Index directory drag it to the wastebasket or select its checkbox.  Click the Delete button to permanently remove the indicated files.<br /><br />Files marked with <img src=\"./images/RESN_confidential.gif\" alt=\"\" /> are required for proper operation and cannot be removed.<br />Files marked with <img src=\"./images/RESN_locked.gif\" alt=\"\" /> have important settings or pending change data and should only be deleted if you are sure you know what you are doing.<br /><br />";
$pgv_lang["index_dir_cleanup"]		= "Počisti imenik Index";
$pgv_lang["clear_cache_succes"]		= "Vmesne datote so bile odstranjene.";
$pgv_lang["clear_cache"]			= "Pošisti vmesne datotek";
$pgv_lang["sanity_err0"]			= "Napake:";
$pgv_lang["sanity_err1"]			= "You need to have PHP version #PGV_REQUIRED_PHP_VERSION# or higher.";
$pgv_lang["sanity_err2"]			= "Datoteka ali imenik <i>#GLOBALS[whichFile]#</i> ne obstaja. Prosim preverite ali datoteka oz. imenik obstaja, da ni bila napačno poimenovana in da je dovoljenje za branje pravilno nastavljeno.";
$pgv_lang["sanity_err3"]			= "Datoteka <i>#GLOBALS[whichFile]#</i> se ni pravilno naložila. Poskusite naložiti datoteko ponovno.";
$pgv_lang["sanity_err4"]			= "Datoteka <i>config.php</i> je pokvarjena.";
$pgv_lang["sanity_err5"]			= "Datoteka <i>config.php</i> ni zapisljiva.";
$pgv_lang["sanity_err6"]			= "Imenik <i>#GLOBALS[INDEX_DIRECTORY]#</i> ni pisljiv.";
$pgv_lang["sanity_warn0"]			= "Opozorila:";
$pgv_lang["sanity_warn1"]			= "Imenik <i>#GLOBALS[MEDIA_DIRECTORY]#</i> ni pisljiv. Ne boste mogli naložiti fotografi in izdelati sličic iz njih.";
$pgv_lang["sanity_warn2"]			= "Imenik <i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i> ni pisljiv. Ne boste mogli naložiti fotografi in izdelati sličic iz njih.";
$pgv_lang["sanity_warn3"]			= "The GD imaging library does not exist. PhpGedView will still function, but some of the features, such as thumbnail generation and the circle diagram, will not work without the GD library.  Please see <a href='http://www.php.net/manual/en/ref.image.php'>http://www.php.net/manual/en/ref.image.php</a> for more information.";
$pgv_lang["sanity_warn4"]			= "The XML Parser library does not exist. PhpGedView will still function, but some of the features, such as report generation and web services, will not work without the XML Parser library. Please see <a href='http://www.php.net/manual/en/ref.xml.php'>http://www.php.net/manual/en/ref.xml.php</a> for more information.";
$pgv_lang["sanity_warn5"]			= "The DOM XML library does not exist. PhpGedView will still function, but some of the features, such as Gramps Export features in the clippings cart, download, and web services, will not work. Please see <a href='http://www.php.net/manual/en/ref.domxml.php'>http://www.php.net/manual/en/ref.domxml.php</a> for more information.";
$pgv_lang["sanity_warn6"]			= "The Calendar library does not exist. PhpGedView will still function, but some of the features, such as conversion to other calendars such as Hebrew or French, will not work.  It is not essential for running PhpGedView. Please see <a href='http://www.php.net/manual/en/ref.calendar.php'>http://www.php.net/manual/en/ref.calendar.php</a> for more information.";
$pgv_lang["ip_address"]				= "IP naslov";
$pgv_lang["date_time"]				= "Datum in čas";
$pgv_lang["log_message"]			= "Sporočilo dnevnika";
$pgv_lang["searchtype"]				= "Tip iskanja";
$pgv_lang["query"]					= "Poizvedba";
$pgv_lang["user"]					= "Prijavljen uporabnik";
$pgv_lang["thumbnail_deleted"]		= "Sličica je bila uspešno izbrisana.";
$pgv_lang["thumbnail_not_deleted"]	= "Sličice ni bilo mogoče izbrisati.";
$pgv_lang["step2"]					= "Korak 2 od 4:";
$pgv_lang["refresh"]				= "Osveži";
$pgv_lang["move_file_success"]		= "Datoteke fotografij in sličic so bile uspešo premeknjene.";
$pgv_lang["media_folder_corrupt"]	= "Imenik fotografij je pokvarjen.";
$pgv_lang["media_file_not_deleted"]	= "Datoteka fotografije ne more biti izbrisana.";
$pgv_lang["gedcom_deleted"] 		= "GEDCOM [#GED#] uspešno izbrisan.";
$pgv_lang["gedadmin"]				= "GEDCOM upravitelj";
$pgv_lang["full_name"]				= "Polno ime";
$pgv_lang["error_header"]			= "Datoteka GEDCOM, <b>#GEDCOM#</b>, ne obstaja na podanem mestu.";
$pgv_lang["confirm_delete_file"]	= "Ali res želite izbrisati to datoteko?";
$pgv_lang["confirm_folder_delete"] = "Ali res želite izbrisati ta imenik?";
$pgv_lang["confirm_remove_links"]	= "Are you sure you want to remove all links to this object?";
$pgv_lang["PRIV_PUBLIC"]			= "Show to public";
$pgv_lang["PRIV_USER"]				= "Show only to authenticated users";
$pgv_lang["PRIV_NONE"]				= "Show only to admin users";
$pgv_lang["PRIV_HIDE"]				= "Hide even from admin users";
$pgv_lang["manage_gedcoms"] 		= "Upravljaj GEDCOM-e in nastavi zasebnost";
$pgv_lang["keep_media"]				= "Ohrani povezave fotografij";
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
$pgv_lang["label_delete"]           	= "Izbriši";
$pgv_lang["progress_bars_info"]			= "The status bars below will let you know how the Import is progressing.  If the time limit runs out the Import will be stopped and you will be asked to press a <b>Continue</b> button.  If you don't see the <b>Continue</b> button, you must restart the Import with a smaller time limit value.";
$pgv_lang["upload_replacement"]			= "Naloži zamenjavo";
$pgv_lang["about_user"]					= "You must first create your main administrative user.  This user will have privileges to update the configuration files, view private data, and create other users.";
$pgv_lang["access"]						= "Dostop";
$pgv_lang["add_gedcom"] 				= "Dodaj GEDCOM";
$pgv_lang["add_new_gedcom"] 			= "Naredi nov GEDCOM";
$pgv_lang["add_new_language"]			= "Dodaj datoteke in nastavitve za nov jezik";
$pgv_lang["add_user"]					= "Dodaj novega uporabnika";
$pgv_lang["admin_gedcom"]				= "Upravljaj GEDCOM";
$pgv_lang["admin_gedcoms"]				= "Klikni sem ua upravljanje GEDCOM-ov";
$pgv_lang["admin_geds"]					= "Upravljanje podatkov in GEDCOM-ov";
$pgv_lang["admin_info"]					= "Informatiivno";
$pgv_lang["admin_site"]					= "Upravljanje spletne strani";
$pgv_lang["admin_user_warnings"]		= "En ali več računov ima opozorila";
$pgv_lang["admin_verification_waiting"] = "Uporabniški računi čakajo na odobritev upravitelja";
$pgv_lang["administration"] 			= "Upravljanje";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]		= "Dovoli izbiro GEDCOM";
$pgv_lang["ALLOW_REMEMBER_ME"]			= "Pokaži možnost <b>Zapomni si me</b> na prijavni stranie";
$pgv_lang["ALLOW_USER_THEMES"]			= "Allow users to select their own theme";
$pgv_lang["ansi_encoding_detected"] 	= "ANSI file encoding detected.	PhpGedView works best with files encoded in UTF-8.";
$pgv_lang["ansi_to_utf8"]				= "Convert this ANSI encoded GEDCOM to UTF-8?";
$pgv_lang["apply_privacy"]				= "Apply privacy settings?";
$pgv_lang["back_useradmin"]				= "Nazaj na upravljanje uporabnikov";
$pgv_lang["bytes_read"] 				= "Prebranih znakov:";
$pgv_lang["can_admin"]					= "Uporabnik lahko upravlja spletne strani";
$pgv_lang["can_edit"]					= "Nivo dostopa";
$pgv_lang["change_id"]					= "Spremeni ID osebe v:";
$pgv_lang["choose_priv"]				= "Choose privacy level:";
$pgv_lang["cleanup_places"] 				= "Počisti kraje";
$pgv_lang["cleanup_users"]				= "Počisti uporabnike";
$pgv_lang["click_here_to_continue"]		= "Klikni sem za nadaljevanje.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Click here to go to the Pedigree tree.";
$pgv_lang["comment"]							= "Admin comments on user";
$pgv_lang["comment_exp"]						= "Admin warning at date";
$pgv_lang["config_help"]						= "Pomoč nastavitev";
$pgv_lang["config_still_writable"]				= "Your <i>config.php</i> file is still writable.  For security, you should set the permissions of this file back to read-only when you have finished configuring your site.";
$pgv_lang["configuration"]						= "Nastavitve";
$pgv_lang["configure"]							= "Nastavi PhpGedView";
$pgv_lang["configure_head"]						= "Nastavitve PhpGedView";
$pgv_lang["confirm_gedcom_delete"]				= "Are you sure you want to delete this GEDCOM";
$pgv_lang["confirm_user_delete"]				= "Are you sure you want to delete the user";
$pgv_lang["create_user"]						= "Izdelaj uporabnika";
$pgv_lang["current_users"]						= "Seznam uporabnikov";
$pgv_lang["daily"]								= "Dnevno";
$pgv_lang["dataset_exists"] 					= "A GEDCOM with this file name has already been imported into the database.";
$pgv_lang["unsync_warning"] 					= "This GEDCOM file is <em>not</em> synchronized with the database.  It may not contain the latest version of your data.  To re-import from the database rather than the file, you should download and re-upload.";
$pgv_lang["date_registered"]					= "Datum registracije";
$pgv_lang["day_before_month"]					= "Day before Month (DD MM YYYY)";
$pgv_lang["DEFAULT_GEDCOM"]						= "Default GEDCOM";
$pgv_lang["default_user"]						= "Create the default administrative user.";
$pgv_lang["del_gedrights"]						= "GEDCOM no longer active, remove user references.";
$pgv_lang["del_proceed"]						= "Nadaljuj";
$pgv_lang["del_unvera"]							= "User not verified by administrator.";
$pgv_lang["del_unveru"]							= "User didn't verify within 7 days.";
$pgv_lang["do_not_change"]						= "Do not change";
$pgv_lang["download_gedcom"]					= "Download GEDCOM";
$pgv_lang["download_here"]						= "Click here to download file.";
$pgv_lang["download_note"]						= "NOTE: Large databases can take a long time to process before downloading.  If PHP times out before the download finishes, the downloaded file may not be complete.<br /><br />To make sure that the file was downloaded correctly, check that the last line of a file in GEDCOM format is <b>0&nbsp;TRLR</b> or that the last line of a file in XML format is <b>&lt;/database&gt;</b>.  These files are text; you can use any suitable text editor, but be sure to <u>not</u> save the downloaded file after you have inspected it.<br /><br />In general, it could take as much time to download as it took to import your original GEDCOM file.";
$pgv_lang["editaccount"]						= "Allow this user to edit his account information";
$pgv_lang["empty_dataset"]						= "Do you want to erase the old data and replace it with this new data?";
$pgv_lang["empty_lines_detected"]				= "Empty lines were detected in your GEDCOM file.	On cleanup, these empty lines will be removed.";
$pgv_lang["enable_disable_lang"]				= "Nastavi podprte jezike";
$pgv_lang["error_ban_server"]       			= "Napačen IP naslov.";
$pgv_lang["error_delete_person"]   				= "You must select the person whose remote link you wish to delete.";
$pgv_lang["error_header_write"] 				= "The GEDCOM file, <b>#GEDCOM#</b>, is not writable. Please check attributes and access rights.";
$pgv_lang["error_remove_site"]					= "The remote server could not be removed.";
$pgv_lang["error_remove_site_linked"]			= "The remote server could not be removed because its Connections list is not empty.";
$pgv_lang["error_remote_duplicate"]				= "This remote database is already in the list as <i>#GLOBALS[whichFile]#</i>";
$pgv_lang["error_siteauth_failed"]				= "Failed to authenticate to remote site";
$pgv_lang["error_url_blank"]					= "Please do not leave remote site title or URL blank";
$pgv_lang["error_view_info"]       				= "You must select the person whose information you wish to view.";
$pgv_lang["example_date"]						= "Example of invalid date from your GEDCOM:";
$pgv_lang["example_place"]						= "Example of invalid place from your GEDCOM:";
$pgv_lang["fbsql"]								= "FrontBase";
$pgv_lang["found_record"]						= "Found record";
$pgv_lang["ged_download"]						= "Download";
$pgv_lang["ged_import"] 						= "Uvoz";
$pgv_lang["ged_export"] 						= "Izvoz";
$pgv_lang["ged_check"] 							= "Check";
$pgv_lang["gedcom_adm_head"]					= "Upravljanje GEDCOM-ov";
$pgv_lang["gedcom_config_write_error"]			= "E R R O R !!!<br />Could not write to file <i>#GLOBALS[whichFile]#</i>.  Please check it for proper Write permissions.";
$pgv_lang["gedcom_downloadable"] 				= "This GEDCOM file is downloadable over the internet!<br />Please see the SECURITY section of the <a href=\"readme.txt\"><b>readme.txt</b></a> file to fix this problem";
$pgv_lang["gedcom_file"]						= "GEDCOM File:";
$pgv_lang["gedcom_not_imported"]				= "This GEDCOM has not yet been imported.";
$pgv_lang["ibase"]								= "InterBase";
$pgv_lang["ifx"]								= "Informix";
$pgv_lang["img_admin_settings"] 				= "Edit Image Manipulation Configuration";
$pgv_lang["autoContinue"]						= "Automatically press «Continue» button";
$pgv_lang["import_complete"]					= "Uvoz končan";
$pgv_lang["import_options"]					= "Možnosti uvoza";
$pgv_lang["import_progress"]					= "Napredovanje uvoza...";
$pgv_lang["import_statistics"]					= "Uvozi statistiko";
$pgv_lang["import_time_exceeded"]				= "The execution time limit was reached.  Click the Continue button below to resume importing the GEDCOM file.";
$pgv_lang["inc_languages"]						= " Jeziki";
$pgv_lang["INDEX_DIRECTORY"]					= "Index file directory";
$pgv_lang["invalid_dates"]						= "Detected invalid date formats, on cleanup these will be changed to format of DD MMM YYYY (eg. 1 JAN 2004).";
$pgv_lang["BOM_detected"] 						= "A Byte Order Mark (BOM) was detected at the beginning of the file. On cleanup, this special code will be removed.";
$pgv_lang["invalid_header"] 					= "Detected lines before the GEDCOM header <b>0&nbsp;HEAD</b>.  On cleanup, these lines will be removed.";
$pgv_lang["label_added_servers"]				= "Remote Servers";
$pgv_lang["label_banned_servers"]  				= "Ban Sites by IP";
$pgv_lang["label_families"]         			= "Družine";
$pgv_lang["label_gedcom_id2"]       			= "Database ID:";
$pgv_lang["label_individuals"]      			= "Osebe";
$pgv_lang["label_manual_search_engines"]		= "Manually mark Search Engines by IP";
$pgv_lang["label_new_server"]     				= "Dodaj novo spletno stran";
$pgv_lang["label_password_id"]					= "Geslo";
$pgv_lang["label_server_info"]     				= "All people and families who are remotely linked through the site: ";
$pgv_lang["label_server_url"]       			= "Site URL/IP";
$pgv_lang["label_username_id"]					= "Uporabniško ime";
$pgv_lang["label_view_local"]       			= "View local information on person";
$pgv_lang["label_view_remote"]     			 	= "View remote information on person";
$pgv_lang["LANG_SELECTION"] 					= "Podprti jeziki";
$pgv_lang["LANGUAGE_DEFAULT"]					= "You have not configured the languages your site will support.<br />PhpGedView will use its default actions.";
$pgv_lang["last_login"]							= "Zadnjič prijavljen";
$pgv_lang["lasttab"]							= "Zavihek zadnjega obiska za uporabnika";
$pgv_lang["leave_blank"]						= "Leave password blank if you want to keep the current password.";
$pgv_lang["link_manage_servers"]   				= "Upravljaj spletne strani";
$pgv_lang["logfile_content"]					= "Vsebina dnevnika";
$pgv_lang["macfile_detected"]					= "Macintosh file detected.  On cleanup your file will be converted to a DOS file.";
$pgv_lang["mailto"]								= "Mailto link";
$pgv_lang["merge_records"]						= "Združi zapise";
$pgv_lang["message_to_all"]						= "Pošlji sporočilo vsem uporabnikom";
$pgv_lang["messaging"]							= "PhpGedView internal messaging";
$pgv_lang["messaging2"]							= "Internal messaging with emails";
$pgv_lang["messaging3"]							= "PhpGedView sends emails with no storage";
$pgv_lang["month_before_day"]					= "Month before Day (MM DD YYYY)";
$pgv_lang["monthly"]							= "Mesečno";
$pgv_lang["msql"]								= "Mini SQL";
$pgv_lang["mssql"]								= "Microsoft SQL server";
$pgv_lang["mysql"]								= "MySQL";
$pgv_lang["mysqli"]								= "MySQL 4.1+ and PHP 5";
$pgv_lang["never"]								= "Nikoli";
$pgv_lang["no_logs"]							= "Disable logging";
$pgv_lang["no_messaging"]						= "No contact method";
$pgv_lang["oci8"]								= "Oracle 7+";
$pgv_lang["page_views"]							= "&nbsp;&nbsp;page views in&nbsp;&nbsp;";
$pgv_lang["performing_validation"]				= "Performing GEDCOM validation...";
$pgv_lang["pgsql"]								= "PostgreSQL";
$pgv_lang["pgv_config_write_error"] 			= "Error!!! Cannot write to the PhpGedView configuration file.  Please check file and directory permissions and try again.";
$pgv_lang["PGV_MEMORY_LIMIT"]					= "Memory limit";
$pgv_lang["pgv_registry"]						= "Poglejte druge PhpGedView spletne strani";
$pgv_lang["PGV_SESSION_SAVE_PATH"]				= "Session save path";
$pgv_lang["PGV_SESSION_TIME"]					= "Session timeout";
$pgv_lang["PGV_SIMPLE_MAIL"] 					= "Use simple mail headers in external mails";
$pgv_lang["PGV_SMTP_ACTIVE"] 					= "Use SMTP to send external mails";
$pgv_lang["PGV_SMTP_HOST"] 						= "Outgoing server (SMTP) name";
$pgv_lang["PGV_SMTP_HELO"] 						= "Sending domain name";
$pgv_lang["PGV_SMTP_PORT"] 						= "SMTP Port";
$pgv_lang["PGV_SMTP_AUTH"] 						= "Use name and password";
$pgv_lang["PGV_SMTP_AUTH_USER"] 				= "User name";
$pgv_lang["PGV_SMTP_AUTH_PASS"] 				= "Password";
$pgv_lang["PGV_STORE_MESSAGES"]					= "Allow messages to be stored online";
$pgv_lang["phpinfo"]							= "Informacije o PHP";
$pgv_lang["place_cleanup_detected"] 			= "Invalid place encodings were detected.  These errors should be fixed.";
$pgv_lang["please_be_patient"]					= "Please be patient";
$pgv_lang["privileges"]							= "Privilegiji";
$pgv_lang["reading_file"]						= "Branje datoteke GEDCOM";
$pgv_lang["readme_documentation"]				= "Besedilo README";
$pgv_lang["remove_ip"] 							= "Remove IP";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"] 	= "Require an administrator to approve new user registrations";
$pgv_lang["review_readme"]						= "You should review the <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> file before continuing to configure PhpGedView.<br /><br />";
$pgv_lang["rootid"] 							= "Pedigree Chart Root Person";
$pgv_lang["seconds"]							= "&nbsp;&nbsp;seconds";
$pgv_lang["select_an_option"]					= "Izberi spodnje možnosti:";
$pgv_lang["SERVER_URL"]							= "PhpGedView URL";
$pgv_lang["show_phpinfo"]						= "Pokaži informacije o PHP";
$pgv_lang["siteadmin"]							= "Upravitel spletne strani";
$pgv_lang["sqlite"]								= "SQLite";
$pgv_lang["sybase"]								= "Sybase";
$pgv_lang["sync_gedcom"]						= "Synchronize User Settings with GEDCOM Data";
$pgv_lang["system_time"]						= "Trenutna ura strežnika:";
$pgv_lang["user_time"]							= "Trenutna ura uporabnikae:";
$pgv_lang["TBLPREFIX"]							= "Database Table Prefix";
$pgv_lang["themecustomization"]					= "Theme Customization";
$pgv_lang["time_limit"]							= "Časovna omejitev:";
$pgv_lang["title_manage_servers"]   			= "Upravljaj spletne strani";
$pgv_lang["title_view_conns"]       			= "Poglej povezave";
$pgv_lang["translator_tools"]					= "Orodja prevejalca";
$pgv_lang["update_myaccount"]					= "Update MyAccount";
$pgv_lang["update_user"]						= "Popravi račun";
$pgv_lang["upload_gedcom"]						= "naloži GEDCOM";
$pgv_lang["USE_REGISTRATION_MODULE"]			= "Allow visitors to request account registration";
$pgv_lang["user_auto_accept"]					= "Automatically accept changes made by this user";
$pgv_lang["user_contact_method"]				= "Preferred Contact Method";
$pgv_lang["user_create_error"]					= "Unable to add user.  Please try again.";
$pgv_lang["user_created"]						= "User created successfully.";
$pgv_lang["user_default_tab"]					= "Default Tab to show on Individual Information page";
$pgv_lang["user_path_length"]					= "Max relationship privacy path length";
$pgv_lang["user_relationship_priv"]				= "Limit access to related people";
$pgv_lang["users_admin"]						= "Upraviteljev spletne strani";
$pgv_lang["users_gedadmin"]						= "GEDCOM upravitelji";
$pgv_lang["users_total"]						= "Skupno število uporabnikov";
$pgv_lang["users_unver"]						= "Nepreverjeno s strani uporabnika";
$pgv_lang["users_unver_admin"]					= "Nepreverjeno s strani upravitelja";
$pgv_lang["usr_deleted"]						= "Izbrisan uporabnik: ";
$pgv_lang["usr_idle"]							= "Število mesecev do zadnje prijave v uporabnički račun, da je voden kot neaktiven: ";
$pgv_lang["usr_idle_toolong"]					= "User's account has been inactive too long: ";
$pgv_lang["usr_no_cleanup"]						= "Nothing found to cleanup";
$pgv_lang["usr_unset_gedcomid"]					= "Unset GEDCOM ID for ";
$pgv_lang["usr_unset_rights"]					= "Unset GEDCOM rights for ";
$pgv_lang["usr_unset_rootid"]					= "Unset root ID for ";
$pgv_lang["valid_gedcom"]						= "Valid GEDCOM detected. No cleanup required.";
$pgv_lang["validate_gedcom"]					= "Validate GEDCOM";
$pgv_lang["verified"]						= "Uporabnik je potrdil sebe";
$pgv_lang["verified_by_admin"]					= "Upravitelj odobril uporabnika";
$pgv_lang["verify_gedcom"]						= "Verify GEDCOM";
$pgv_lang["verify_upload_instructions"]			= "A GEDCOM file with the same name has been found. If you choose to continue, the old GEDCOM file will be replaced with the file that you uploaded and the Import process will begin again.  If you choose to cancel, the old GEDCOM will remain unchanged.";
$pgv_lang["view_changelog"]						= "View changelog.txt file";
$pgv_lang["view_logs"]							= "View log files";
$pgv_lang["view_readme"]						= "View readme.txt file";
$pgv_lang["visibleonline"]						= "Visible to other users when online";
$pgv_lang["visitor"]							= "Gost";
$pgv_lang["warn_users"]							= "Uporabnikov z opozorilom";
$pgv_lang["weekly"]								= "Tedensko";
$pgv_lang["welcome_new"]						= "Welcome to your new PhpGedView website.";
$pgv_lang["yearly"]								= "Letno";
$pgv_lang["admin_OK_subject"]					= "Odobritev uporabniškega računa na #SERVER_NAME#";
$pgv_lang["admin_OK_message"]					= "Upravitelj rodovnika na spletni strani #SERVER_NAME# je odobril vaš zahtevek za uporabniški račun. Sedaj se lahko prijavite tako da sledite naslednji povezavi:\r\n\r\n#SERVER_NAME#\r\n";

$pgv_lang["batch_update"]="Izdelaj skupke popravkov/ureditev vašega GEDCOM-a";

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
$pgv_lang["all_rec"]      = "Vseh zapisov";             // What to show
$pgv_lang["err_rec"]      = "Zapisov z napakami";
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
$pgv_lang["SURNAME_TRADITION"]		= "Surname tradition"; // Default surname inheritance
$pgv_lang["tradition_spanish"]		= "Spanish";
$pgv_lang["tradition_portuguese"]	= "Portuguese";
$pgv_lang["tradition_icelandic"]	= "Icelandic";
$pgv_lang["tradition_paternal"]		= "Paternal";
$pgv_lang["tradition_polish"]		= "Polish";
$pgv_lang["tradition_none"]			= "None";

?>
