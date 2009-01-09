<?php
/**
 * Italian Language file
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
 * @author Lorenzo Simionato, Fabio Parri
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

//-- CONFIGURE FILE MESSAGES
$pgv_lang["configure"]			= "Configura PhpGedView";
$pgv_lang["gedconf_head"]		= "Configurazione GEDCOM";
$pgv_lang["default_user"]		= "Create the default administrative user.";
$pgv_lang["about_user"]			= "You must first create your main administrative user.  This user will have privileges to update the configuration files, view private data, and create other users.";
$pgv_lang["access"]				= "Accesso";
$pgv_lang["add_user"]			= "Aggiungi un nuovo utente";
$pgv_lang["current_users"]		= "Lista Utenti";
$pgv_lang["leave_blank"]		= "Lascia vuoto per mantenere la password corrente.";
$pgv_lang["other_theme"]		= "Other, please type in";
$pgv_lang["performing_update"]		= "Performing update.";
$pgv_lang["config_file_read"]		= "Config file read.";
$pgv_lang["does_not_exist"]		= "non esiste";
$pgv_lang["db_setup_bad"]		= "Your current database configuration is bad.  Please check your database connection parameters and configure again.";
$pgv_lang["click_here_to_continue"]	= "Click Here to Continue.";
$pgv_lang["config_help"]		= "Configuration Help";
$pgv_lang["index"]			= "Index Files";
$pgv_lang["mysql"]			= "MySQL";
$pgv_lang["admin_gedcoms"]		= "Clicca qui per amministrare il file GEDCOM.";
$pgv_lang["current_gedcoms"]		= "Current GEDCOMs";
$pgv_lang["ged_download"]		= "Download";
$pgv_lang["ged_gedcom"]			= "GEDCOM File";
$pgv_lang["ged_title"]			= "GEDCOM Title";
$pgv_lang["ged_config"]			= "Configuration File";
$pgv_lang["show_phpinfo"]		= "Show PHPInfo Page";
$pgv_lang["confirm_gedcom_delete"]	= "Are you sure you want to delete this GEDCOM";
$pgv_lang["disabled"]			= "Disabled";
$pgv_lang["mouseover"]			= "On Mouse Over";
$pgv_lang["mousedown"]			= "On Mouse Down";
$pgv_lang["mailto"]			= "Link alla mail";
$pgv_lang["messaging2"]			= "Messaggistica interna con email";
$pgv_lang["no_messaging"]		= "Nessun contatto";
$pgv_lang["click"]			= "On Mouse Click";
$pgv_lang["no_logs"]			= "Disabilita log";
$pgv_lang["daily"]			= "Ogni giorno";
$pgv_lang["weekly"]			= "Settimanalmente";
$pgv_lang["monthly"]			= "Mensilmente";
$pgv_lang["yearly"]			= "Annualmente";
$pgv_lang["config_still_writable"]	= "Il file <i>config.php</i> è ancora scrivibile. Per sicurezza dovresti settare i permessi in sola lettura quando hai terminato di configurare il sito.";
$pgv_lang["PGV_DATABASE"] = "PhpGedView DataStore:";
$pgv_lang["PGV_DATABASE_help"] = "This tells PhpGedView what type of datastore you want to use for the GEDCOM files you import into the system.  Select 'Index Files' to use index files stored in the index directory, or select 'MySQL' to use a MySQL database.";
$pgv_lang["DBTYPE"]			= "Tipo di Database";
$pgv_lang["DBHOST"] = "Database Host";
$pgv_lang["DBHOST_help"] = "Il DNS o l'indirizzo IP del computer che ospita il tuo database. Questo parametro è ignorato se utilizzi un database SQLite.";
$pgv_lang["DBUSER"] = "Database Username:";
$pgv_lang["DBUSER_help"] = "The MySQL database username required to login to your database.";
$pgv_lang["DBPASS"] = "Database Password";
$pgv_lang["DBPASS_help"] = "The MySQL database password for the user you entered in the Username field.";
$pgv_lang["DBNAME"] = "Nome Database";
$pgv_lang["DBNAME_help"] = "The Database in the MySQL server you want PhpGedView to use.  The Username you enter in the user field must have create, insert, update, delete, and select privileges on this database.";
$pgv_lang["TBLPREFIX"] = "Prefisso Tabelle Database";
$pgv_lang["TBLPREFIX_help"] = "A prefix to append to the MySQL tables created by PhpGedView.  By changing this value you can setup multiple PhpGedView sites to use the same database but different tables.";
$pgv_lang["DEFAULT_GEDCOM"] = "Default GEDCOM:";
$pgv_lang["last_login"]			= "Ultimo log in";
$pgv_lang["date_registered"]	= "Data registrazione";
$pgv_lang["privileges"]			= "Privilegi";
$pgv_lang["server_url_note"]	= "Questo dovrebbe essere l'URL alla cartella di PhpGedView. Dovresti cambiarlo solo se sai quello che stai facendo. PhpGedView ha determinato che questo valore dovrebbe essere: <b>#GUESS_URL#</b>";
$pgv_lang["DEFAULT_GEDCOM_help"] = "The MySQL version of PhpGedView allows you to work with multiple GEDCOM datasets in the same instance of PhpGedView.  Use this variable to set a default GEDCOM dataset for all users when they first come to your site.  A blank value will select the first GEDCOM that was imported.  If you allow the user to change the GEDCOM, there will be a link on every page that allows them to switch the GEDCOM dataset they are using.";
$pgv_lang["ALLOW_CHANGE_GEDCOM"] = "Permetti di cambiare GEDCOM";
$pgv_lang["ALLOW_CHANGE_GEDCOM_help"] = "Setting this value to yes allows visitors to your site to have the option of changing GEDCOMs if you have a multiple GEDCOM environment setup.";
$pgv_lang["GEDCOM"] = "GEDCOM path:";
$pgv_lang["gedcom_path_help"] = "First upload your GEDCOM file to a location accessible by php on your server.  Then enter the path to that file here.<br /><br />See the <a href=\"readme.txt\">Readme.txt</a> file for more help.";
$pgv_lang["CHARACTER_SET"] = "Character Set Encoding:";
$pgv_lang["CHARACTER_SET_help"] = "This is the character set of your GEDCOM file.  UTF-8 is the default and should work for almost all sites.  If you export your GEDCOM using ibm windows encoding, then you should put WINDOWS here.<br /><br />NOTE: PHP does NOT support UNICODE (UTF-16) so don't try it and complain to the PHP folks :-)";
$pgv_lang["LANGUAGE"] = "Lingua";
$pgv_lang["LANGUAGE_help"] = "Assign the default language for the site.  Users have the ability to override this setting using their browser preferences or the form at the bottom of the page if ENABLE_MULTI_LANGUAGE = true.";
$pgv_lang["ENABLE_MULTI_LANGUAGE"] = "Allow user to change language:";
$pgv_lang["ENABLE_MULTI_LANGUAGE_help"] = "Set to 'yes' to give users the option of selecting a different language from a dropdown list in the footer and default to the language they have set in their browser settings.";
$pgv_lang["CALENDAR_FORMAT"] = "Calendar Format:";
$pgv_lang["CALENDAR_FORMAT_help"] = "Allows you to specify the type of Calendar you would like to use with this GEDCOM file.  Hebrew is the same as the Jewish Calendar using Hebrew characters.  Note: The values used for Jewish / Hebrew dates are calculated from the Gregorian / Julian dates. Since the Jewish calendar day starts at dusk, any even taking place from dusk till midnight will display as one day prior to the correct Jewish date. The display of Hebrew can be problematic in old browsers. Some old browsers will display the Hebrew backwards or not at all.";
$pgv_lang["DISPLAY_JEWISH_THOUSANDS"] = "Display Hebrew Thousands:";
$pgv_lang["DISPLAY_JEWISH_THOUSANDS_help"] = "Show Alafim in Hebrew Calendars. Setting this to yes will display the year of 1969 as <span lang=\"he-IL\" dir='rtl'>&#1492;'&#160;&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm; while setting it to no will display the year as <span lang=\"he-IL\" dir='rtl'>&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm;. This has no impact on the Jewish year setting. The year will display as 5729 regardless of this setting.<br />Note: This setting is similar to the php 5.0 Calendar constant CAL_JEWISH_ADD_ALAFIM.";
$pgv_lang["DISPLAY_JEWISH_GERESHAYIM"] = "Display Hebrew Gershayim:";
$pgv_lang["DISPLAY_JEWISH_GERESHAYIM_help"] = "Show single and double quotes when displaying Hebrew dates. Setting this to yes will display February 8th 1969 as  <span lang='he-IL' dir='rtl'>&#1499;'&#160;&#1513;&#1489;&#1496;&#160;&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm; while setting it to no will display it as <span lang='he-IL' dir='rtl'>&#1499;&#160;&#1513;&#1489;&#1496;&#160;&#1514;&#1513;&#1499;&#1496;</span>&lrm;. This has no impact on the Jewish year setting since quotes are not used in jewish dates displayed with Latin characters.<br />Note: This setting is similar to the php 5.0 Calendar constants CAL_JEWISH_ADD_ALAFIM_GERESH and CAL_JEWISH_ADD_GERESHAYIM. This single setting effects both.";
$pgv_lang["JEWISH_ASHKENAZ_PRONUNCIATION"] = "Jewish Ashkenaz Pronunciation:";
$pgv_lang["JEWISH_ASHKENAZ_PRONUNCIATION_help"] = "Use Jewish Ashkenazi pronunciations.<br />If set to yes the months of Cheshvan and Teves will be spelled with Ashkenazi pronunciation. Setting it to no will change the months to Hesvan and Tevet. <br />This only affects the Jewish setting. Using the Hebrew setting will use the Hebrew alphabet.";
$pgv_lang["DEFAULT_PEDIGREE_GENERATIONS"] = "Pedigree Generations:";
$pgv_lang["DEFAULT_PEDIGREE_GENERATIONS_help"] = "Set the default number of generations to display on the pedigree charts.";
$pgv_lang["MAX_PEDIGREE_GENERATIONS"] = "Maximum Pedigree Generations:";
$pgv_lang["MAX_PEDIGREE_GENERATIONS_help"] = "Set the maximum number of generations to display on the pedigree charts.";
$pgv_lang["MAX_DESCENDANCY_GENERATIONS"] = "Maximum Descendancy Generations:";
$pgv_lang["MAX_DESCENDANCY_GENERATIONS_help"] = "Set the maximum number of generations to display on the descendancy charts.";
$pgv_lang["USE_RIN"] = "Use RIN# instead of GEDCOM ID:";
$pgv_lang["USE_RIN_help"] = "Set to YES to use the RIN number instead of the GEDCOM ID when asked for Individual IDs in configuration files, user settings, and charts.  This is useful for genealogy programs that do not export GEDCOMs with concurrent individual IDs but always use the same RIN.";
$pgv_lang["PEDIGREE_ROOT_ID"] = "Default person for pedigree and descendency charts:";
$pgv_lang["PEDIGREE_ROOT_ID_help"] = "Set the ID of the default person to display on the pedigree and descendency charts.";
$pgv_lang["GEDCOM_ID_PREFIX"] = "GEDCOM ID Prefix:";
$pgv_lang["GEDCOM_ID_PREFIX_help"] = "On pedigree, descendancy, relationship, and other charts when users are prompted to enter an ID, if they do not add a prefix to the ID, this prefix will be added.";
$pgv_lang["PEDIGREE_FULL_DETAILS"] = "Show Birth and Death Details on Pedigree and descendency charts:";
$pgv_lang["PEDIGREE_FULL_DETAILS_help"] = "Tells whether or not to show the birth and death details of an individual by default.";
$pgv_lang["SHOW_EMPTY_BOXES"] = "Show empty boxes on pedigree charts:";
$pgv_lang["SHOW_EMPTY_BOXES_help"] = "Tells whether or not to show empty boxes on pedigree charts.";
$pgv_lang["ZOOM_BOXES"] = "Zoom Boxes on Charts:";
$pgv_lang["ZOOM_BOXES_help"] = "Allows a user to zoom the boxes on the charts and get more information.  Set to \"Disabled\" to disable this feature.  Set to \"MouseOver\" to zoom boxes when the user mouses over the icon in the box.  Set to \"Click\" to zoom boxes when the user clicks on the icon in the box.";
$pgv_lang["LINK_ICONS"] = "PopUp Links on Charts:";
$pgv_lang["LINK_ICONS_help"] = "Allows the user select links to other charts and close relatives of the person.  Set to \"Disabled\" to disable this feature.  Set to \"MouseOver\" to popup the links when the user mouses over the icon in the box.  Set to \"Click\" to popup the links when the user clicks on the icon in the box.";
$pgv_lang["AUTHENTICATION_MODULE"] = "Authentication Module File:";
$pgv_lang["AUTHENTICATION_MODULE_help"] = "File from which to load authentication functions.  By implementing the functions in this file, users can customize PhpGedView to use a different method to authenticate users and store users in a different user database.  Hopefully users will be willing to share their custom authentication modules with other PhpGedView users.";
$pgv_lang["PRIVACY_MODULE"] = "Privacy File:";
$pgv_lang["PRIVACY_MODULE_help"] = "File from which to load privacy functions. See <a href=\"http://gendorbendor.sourceforge.net\">http://gendorbendor.sourceforge.net</a> for more information and to download alternative privacy add-ons.";
$pgv_lang["HIDE_LIVE_PEOPLE"] = "Hide living people:";
$pgv_lang["HIDE_LIVE_PEOPLE_help"] = "The hide living people option tells PhpGedView to hide the personal detail so people who are still living.  Living people are defined to be those who do not have an event more than \$MAX_ALIVE_AGE years ago, and who doesn't have any children born more than \$MAX_ALIVE_AGE years ago.";
$pgv_lang["CHECK_CHILD_DATES"] = "Check child dates:";
$pgv_lang["CHECK_CHILD_DATES_help"] = "Checks the dates of the children when determining whether to a person is dead.  On older systems and large GEDCOMs this can slow down the response time of your site.";
$pgv_lang["MAX_ALIVE_AGE"] = "Age at which to assume a person is dead:";
$pgv_lang["MAX_ALIVE_AGE_help"] = "The maximum age that a person can have an event or the maximum age of their children to determine if they are dead or not.";
$pgv_lang["SHOW_GEDCOM_RECORD"] = "Allow users to see raw GEDCOM records:";
$pgv_lang["SHOW_GEDCOM_RECORD_help"] = "Setting this to yes will place links on individuals, sources, and families allowing users to bring up another window with the raw GEDCOM taken right out of the GEDCOM file.";
$pgv_lang["ALLOW_EDIT_GEDCOM"] = "Enable online Editing:";
$pgv_lang["ALLOW_EDIT_GEDCOM_help"] = "Enables the online editing features for this GEDCOM so that users with the edit privileges may update this GEDCOM online.";
$pgv_lang["INDEX_DIRECTORY"] = "Directory file di indice";
$pgv_lang["INDEX_DIRECTORY_help"] = "The path to a readable and writeable Directory where PhpGedView should store index files (include the trailing \"/\").";
$pgv_lang["ALPHA_INDEX_LISTS"] = "Break up long lists by the first letter:";
$pgv_lang["ALPHA_INDEX_LISTS_help"] = "For very long individual and family lists, set this to true to split the list into pages by the first letter of their last name.";
$pgv_lang["NAME_FROM_GEDCOM"] = "Get Display name from GEDCOM:";
$pgv_lang["NAME_FROM_GEDCOM_help"] = "By default PhpGedView uses the name stored in the indexes to get a person's name.  With some GEDCOM formats and languages the sortable name stored in the indexes does not get displayed properly and the best way to get the correct display name is from the GEDCOM.  Spanish names are a good example of this.  A Spanish name can take the form Given Names Father's Surname Mother's Surname.  Using the Indexes for sorting and display, the name would display like this Given Names Mother's Surname Father's Surname which is incorrect.  Going back the GEDCOM for the name will return the correct name.  However, retrieving the name from the GEDCOM will slow the program down.";
$pgv_lang["SHOW_ID_NUMBERS_help"] = "Show ID numbers in parenthesis after person names on charts.";
$pgv_lang["SHOW_PEDIGREE_PLACES"] = "Place levels to show in person boxes";
$pgv_lang["SHOW_PEDIGREE_PLACES_help"] = "This sets how much of the place information is shown in the person boxes on charts.  Setting the value to 9 will guarentee to show all places levels.  Setting the value to 0 will hide places completely.  Setting the value to 1 will show the first level, setting it to 2 will show the first 2 levels, etc.";
$pgv_lang["MULTI_MEDIA"] = "Enable multimedia features:";
$pgv_lang["MULTI_MEDIA_help"] = "GEDCOM 5.5 allows you to link pictures, videos, and other multimedia objects into your GEDCOM.  If you do not include multimedia objects in your GEDCOM then you can disable the multimedia features by setting this value to 'no'. <br />See the multimedia section in the <a href=\"readme.txt\">readme.txt</a> file for more information about including media in your site.";
$pgv_lang["MEDIA_DIRECTORY"] = "MultiMedia directory:";
$pgv_lang["MEDIA_DIRECTORY_help"] = "The path to a readable Directory where PhpGedView should look for local multi media files (include the trailing \"/\").";
$pgv_lang["MEDIA_DIRECTORY_LEVELS"] = "Multi-Media Directory Levels to Keep:";
$pgv_lang["MEDIA_DIRECTORY_LEVELS_help"] = "A value of 0 will ignore all directories in the file path for the media object.  A value of 1 will use also use the first directory containing this image.  Increasing the numbers increases number of parent directories to include in the path.  <br />For example: If you link an image in your GEDCOM with a path like this C:\\Documents and Settings\\User\\My Documents\\My Pictures\\Genealogy\\Surname Line\\grandpa.jpg then a value of 0 will translate this path to ./media/grandpa.jpg.  A value of 1 will translate this to ./media/Surname Line/grandpa.jpg, etc.  Most people will only need to use a 0.  But it is possible that some media objects will have the same name and would overwrite each other.  This allows you to keep some organization in your media and prevents name clashing.";
$pgv_lang["SHOW_HIGHLIGHT_IMAGES_help"] = "If you have enabled multimedia in your site, then you can have PhpGedView display a thumbnail image next to the person's name in charts and boxes.  Currently PhpGedView uses the first multimedia object listed in the GEDCOM record as the highlight image.  For people with multiple images, you should arrange the multimedia objects such that the one you wish to be highlighted appears first, before any others.<br />See the multimedia section in the <a href=\"readme.txt\">readme.txt</a> file for more information about including media in your site.";
$pgv_lang["ENABLE_CLIPPINGS_CART"] = "Enable Clippings Cart:";
$pgv_lang["ENABLE_CLIPPINGS_CART_help"] = "The clippings cart allows visitors to your site to be able to add people to a GEDCOM clippings file that they can download as a GEDCOM file and import into their genealogy software.";
$pgv_lang["HIDE_GEDCOM_ERRORS"] = "Hide GEDCOM errors:";
$pgv_lang["HIDE_GEDCOM_ERRORS_help"] = "Setting this to 'yes' will hide error messages produced by PhpGedView when it doesn't understand a GEDCOM tag in your GEDCOM file.  PhpGedView makes every effort to conform to the GEDCOM 5.5 standard, but many genealogy software programs include their own custom tags.  See the <a href=\"readme.txt\">readme.txt</a> file for more information.";
$pgv_lang["WORD_WRAPPED_NOTES"] = "Add spaces where notes were wrapped:";
$pgv_lang["WORD_WRAPPED_NOTES_help"] = "Some genealogy programs wrap notes at word boundaries while others wrap notes anywhere.  This can cause PhpGedView to run words together.  Setting this to 'yes' will add a space between words where they are wrapped in the GEDCOM.";
$pgv_lang["HOME_SITE_URL"] = "Main WebSite URL:";
$pgv_lang["HOME_SITE_URL_help"] = "A URL included in the supplied theme headers used to generate a link to your main home page.";
$pgv_lang["HOME_SITE_TEXT"] = "Main WebSite Text:";
$pgv_lang["HOME_SITE_TEXT_help"] = "The text used to generate the link to your main home page.";
$pgv_lang["CONTACT_EMAIL"] = "Genealogy Contact User:";
$pgv_lang["CONTACT_EMAIL_help"] = "The User visitors should contact about the genealogical data on this site.";
$pgv_lang["WEBMASTER_EMAIL"] = "Webmaster Email:";
$pgv_lang["WEBMASTER_EMAIL_help"] = "The email address visitors should contact about technical questions or errors they might encounter while on your site.";
$pgv_lang["FAVICON"] = "Favorites Icon:";
$pgv_lang["FAVICON_help"] = "Change this to point to the icon you want to display in peoples favorites menu when they bookmark your site.";
$pgv_lang["THEME_DIR"] = "Theme directory:";
$pgv_lang["THEME_DIR_help"] = "The directory where your PhpGedView theme files are kept.  You may customize any of the standard themes that come with PhpGedView to give your site a unique look and feel.  See the theme customization section of the <a href=\"readme.txt\">readme.txt</a> file for more information.";
$pgv_lang["TIME_LIMIT"] = "PHP Time Limit:";
$pgv_lang["TIME_LIMIT_help"] = "The maximum time in seconds that PhpGedView should be allowed to run.  The default is 1 minute.  Depending on the size of your GEDCOM file, you may need to increase this time limit when you need to build the indexes.  Set this value to 0, to allow PHP to run forever.<br />CAUTION: Setting this to 0, or setting it too high could cause your site to hang on certain operating systems until the script finishes.  Setting it to 0 means it may never finish until a server administrator kills the process or restarts the server.  A large pedigree chart can take a very long time to run, leaving this value as low as possible ensures that someone cannot crash your webserve by requestion a 1000 generation chart.";
$pgv_lang["PGV_SESSION_SAVE_PATH"] = "Path salvataggio sessione:";
$pgv_lang["PGV_SESSION_SAVE_PATH_help"] = "The path to store PhpGedView session files.  Some hosts do not have PHP configured properly and sessions are not maintained between page requests.  This allows site administrators to override this by saving files in one of their local directories.  The ./index/ directory is a good choice if you need to change this.  The default is to leave the field empty, which will use the save path as configured in the php.ini file.";
$pgv_lang["PGV_SESSION_TIME"] = "Timeout sessione";
$pgv_lang["PGV_SESSION_TIME_help"] = "The time in seconds that a PhpGedView session remains active before requiring a login.  The default is 120 minutes.";
$pgv_lang["SHOW_STATS"] = "Show Execution Statistics:";
$pgv_lang["SHOW_STATS_help"] = "Show runtime statistics and database queries at the bottom of every page.";
$pgv_lang["USE_REGISTRATION_MODULE"] = "Permetti agli utenti di richiedere un account";
$pgv_lang["USE_REGISTRATION_MODULE_help"] = "Gives users the option of registering themselves for an account on the site.  Administrators will have to approve the registration before it becomes active.";
$pgv_lang["ALLOW_USER_THEMES"] = "Permetti agli utenti di selezionare il proprio tema";
$pgv_lang["ALLOW_USER_THEMES_help"] = "Gives users the option of selecting their own theme.";
$pgv_lang["PGV_SIMPLE_MAIL"] = "Usa intestazioni semplici nelle email.";
$pgv_lang["CREATE_GENDEX"] = "Create Gendex Files:";
$pgv_lang["LOGFILE_CREATE"]		= "Archivia file di log";
$pgv_lang["PGV_MEMORY_LIMIT"]		= "Limite memoria";
$pgv_lang["CREATE_GENDEX_help"] = "Show PhpGedView generate Gendex files whenever a GEDCOM is imported.  Gendex files are stored in the index directory.";
$pgv_lang["PGV_STORE_MESSAGES"]		= "Permetti di salvare i messaggi online";
$pgv_lang["welcome_new"]			= "Benvenuto nel tuo nuovo sito di PhpGedView.<br /><br />Poiché stai visualizzando questa pagina, hai installato con successo PhpGedView sul tuo server e sei pronoto per configurarlo come preferisci.";
$pgv_lang["review_readme"]	= "Dovresti leggere il file <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> prima di continuare a configurare PhpGedView.<br /><br />";
$pgv_lang["return_editconfig"]		= "Potrai tornare a questa configurazione in qualunque momento andando con il tuo browser su <i>edit_config.php</i> o cliccando il link per la <b>Configurazione</b> sulla pagina <b>#pgv_lang[gedcom_adm_head]#</b>.<br />";
$pgv_lang["save_config"] 	= "Salva configurazione";
$pgv_lang["download_here"]	= "Clicca qui per scaricare il file.";
$pgv_lang["download_gedconf"]	= "Download GEDCOM configuration.";
$pgv_lang["not_writable"]	= "We have detected that you configuration file is not writable by PHP.  You can use the download button to save your settings to a file that you can upload manually.";
$pgv_lang["PRIV_USER"]				= "Mostra solo agli utenti registrati";
$pgv_lang["PRIV_NONE"]				= "Mostra solo agli amministratori";
$pgv_lang["PRIV_PUBLIC"]			= "Mostra al pubblico";
$pgv_lang["save_changed_settings"]		= "Salva cambiamenti";
$pgv_lang["upload_to_index"]	= "Upload the file to your index directory: ";

//-- edit privacy messages
$pgv_lang["edit_privacy"]	= "Configuration of the privacy-file";

//-- language edit utility
$pgv_lang["edit_langdiff"]	= "Modifica il contenuto dei file di linguaggio";
$pgv_lang["edit_lang_utility"]	= "Utility per editare i file di linguaggio";
$pgv_lang["edit_lang_utility_help"]	= "Puoi usare questa utility per modificare i contenuti di un file di linguaggio partendo dai contenuti di quello in lingua inglese.<br /><br />Vedrai i contenuti del file originale (in Inglese) e quelli dello stesso file (ce ne sono quattro) nella lingua scelta. Cliccando sul messaggio sotto la versione inglese, si aprirà una nuova finestra dove potrai modificare il testo. Potrai poi salvare i cambiamenti o annullarli.";
$pgv_lang["edit_lang_utility_warning"]	= "ATTENZIONE!<br /><br />Se premi il pulsante <b>#pgv_lang[close_window_without_refresh]#</b>, potresti non vedere i tuoi cambiamenti sullo schermo finché non ricaricherai la pagina manualmente. È possibile che il tuo file della lingua venga dsitrutto se aggiungi un messaggio che non è ancora apparso all'interno del file di linguaggio o se modifichi un messaggio ancora.<br /><br />Se non sai quello che stai facendo,  per favore non usare il pulsante <b>#pgv_lang[close_window_without_refresh]#</b>.";
$pgv_lang["language_to_edit"]	= "Linguaggio da modificare";
$pgv_lang["file_to_edit"]	= "Tipo del file di linguaggio da modificare";
$pgv_lang["check"]			= "Controlla";
$pgv_lang["lang_save"]		= "Salva";
$pgv_lang["contents"]		= "Contenuti";
$pgv_lang["editlang"]			= "Modifica";
$pgv_lang["no_content"]		= "Nessun contenuto";
$pgv_lang["editlang_help"]	= "Modifica il messaggio dal file del linguaggio";
$pgv_lang["savelang_help"]	= "Salva il messaggio modificato";
$pgv_lang["original_message"]	= "Messaggio originale";
$pgv_lang["message_to_edit"]	= "Messaggio modificato";
$pgv_lang["language_to_export"]	= "Linguaggio da esportare";
$pgv_lang["export"]		= "Esporta";
$pgv_lang["new_language"]	= "Nuovo linguaggio";
$pgv_lang["old_language"]	= "Vecchio linguaggio";
$pgv_lang["compare"]		= "Confronta";
$pgv_lang["comparing"]		= "File Linguaggio confrontati";
$pgv_lang["additions"]		= "Aggiunte";
$pgv_lang["no_additions"]	= "Nessuna aggiunta";
$pgv_lang["lang_name_italian"]		= "Italiano";
$pgv_lang["translation_forum"]		= "Forum Traduzioni di PhpGeView su SourceForge";
$pgv_lang["translation_forum_help"]	= "Questo <a href=\"http://sourceforge.net/forum/forum.php?forum_id=294245\" target=_blank><b>collegamento</b></a> apre una nuova finestra del browser. Sari reindirizzato al forum delle traduzioni di PhpGedView, dove potrai discutere su argomenti riguardo ad esse.";
$pgv_lang["system_time"]		= "Data di sistema:";
$pgv_lang["add_new_lang_button"]	= "Aggiungi nuova lingua";
$pgv_lang["hide_translated"]		= "Nascondi tradotti";
$pgv_lang["um_files_exist"] = "Uno o più file esistono già. Vuoi sovrascriverli?";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"]		= "Le nuove registrazioni devono essere approvate da un amministratore";
$pgv_lang["ALLOW_REMEMBER_ME"]		= "Mostra l'opzione <b>Ricordami</b> nella pagina di login";

?>
