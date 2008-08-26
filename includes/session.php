<?php
/**
 * Startup and session logic
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
 * @subpackage admin
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

// Identify ourself
define('PGV_PHPGEDVIEW',      'PhpGedView');
define('PGV_VERSION',         '4.1.6');
define('PGV_VERSION_RELEASE', 'svn'); // 'svn', 'beta', 'rc1', '', etc.
define('PGV_VERSION_TEXT',    trim(PGV_VERSION.' '.PGV_VERSION_RELEASE));
define('PGV_PHPGEDVIEW_URL',  'http://www.phpgedview.net');
define('PGV_PHPGEDVIEW_WIKI', 'http://wiki.phpgedview.net');

// Environmental requirements
define('PGV_REQUIRED_PHP_VERSION',     '4.3.5');
define('PGV_REQUIRED_MYSQL_VERSION',   '4.1');   // Not currently enforced
define('PGV_REQUIRED_SQLITE_VERSION',  '3.2.6'); // Not currently enforced
define('PGV_REQUIRED_PRIVACY_VERSION', '3.1');

// Regular expressions for validating user input, etc.
define('PGV_REGEX_XREF',      '[A-Za-z0-9:-]+');
define('PGV_REGEX_INTEGER',   '-?\d+');
define('PGV_REGEX_ALPHA',     '[a-zA-Z]+');
define('PGV_REGEX_ALPHANUM',  '[a-zA-Z0-9]+');
define('PGV_REGEX_BYTES',     '[0-9]+[bBkKmMgG]?');
define('PGV_REGEX_PASSWORD',  '.{6,}');
define('PGV_REGEX_NOSCRIPT',  '[^<>"&%{};]+');
define('PGV_REGEX_URL',       '[\/0-9A-Za-z_!~*\'().;?:@&=+$,%#-]+'); // Simple list of valid chars
define('PGV_REGEX_EMAIL',     '[^\s<>"&%{};@]+@[^\s<>"&%{};@]+');

// UTF8 representation of various characters
define('PGV_UTF8_BOM', "\xEF\xBB\xBF"); // U+FEFF
define('PGV_UTF8_LRM', "\xE2\x80\x8E"); // U+200E
define('PGV_UTF8_RLM', "\xE2\x80\x8F"); // U+200F

// Alternatives to BMD events for lists, charts, etc.
define('PGV_EVENTS_BIRT', 'BIRT|CHR|BAPM|_BRTM|ADOP');
define('PGV_EVENTS_DEAT', 'DEAT|BURI|CREM');
define('PGV_EVENTS_MARR', 'MARR|MARB');
define('PGV_EVENTS_DIV',  'DIV|ANUL');
@ini_set('arg_separator.output', '&amp;');
@ini_set('error_reporting', 0);
@ini_set('display_errors', '1');
@error_reporting(0);

//-- required for running PHP in CGI Mode on Windows
if (!isset($_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI'] = "";

//-- list of critical configuration variables
$CONFIG_VARS = array(
	'PGV_BASE_DIRECTORY',
	'PGV_DATABASE',
	'DBTYPE',
	'DBHOST',
	'DBUSER',
	'DBPASS',
	'DBNAME',
	'TBLPREFIX',
	'INDEX_DIRECTORY',
	'AUTHENTICATION_MODULE',
	'USE_REGISTRATION_MODULE',
	'ALLOW_USER_THEMES',
	'ALLOW_REMEMBER_ME',
	'DEFAULT_GEDCOM',
	'ALLOW_CHANGE_GEDCOM',
	'LOGFILE_CREATE',
	'PGV_SESSION_SAVE_PATH',
	'PGV_SESSION_TIME',
	'GEDCOMS',
	'SERVER_URL',
	'LOGIN_URL',
	'PGV_MEMORY_LIMIT',
	'PGV_STORE_MESSAGES',
	'PGV_SIMPLE_MAIL',
	'CONFIG_VERSION',
	'CONFIGURED',
	'MANUAL_SESSON_START',
	'REQUIRE_ADMIN_AUTH_REGISTRATION',
	'COMMIT_COMMAND'
	);


//-- Detect and report Windows or OS/2 Server environment
//  Windows and OS/2 use the semi-colon as a separator in the "include_path",
//    *NIX uses a colon
//  Windows and OS/2 use the ISO character set in the server-side file system,
//    *NIX and PhpGedView use UTF-8.  Consequently, PGV needs to translate
//    from UTF-8 to ISO when handing a file/folder name to Windows and OS/2,
//    and all file/folder names received from Windows and OS/2 must be
//    translated from ISO to UTF-8 before they can be processed by PGV.
$WIN32 = false;
if(substr(PHP_OS, 0, 3) == 'WIN') $WIN32 = true;
if(substr(PHP_OS, 0, 4) == 'OS/2') $WIN32 = true;
if(substr(PHP_OS, 0, 7) == 'NetWare') $WIN32 = true;
if($WIN32) $seperator=";"; else $seperator = ":";
//-- append our 'includes/' path to the include_path ini setting for ease of use.
$ini_include_path = @ini_get('include_path');
$includes_dir = dirname(@realpath(__FILE__));
$includes_dir .= $seperator.dirname($includes_dir);
@ini_set('include_path', ".{$seperator}{$includes_dir}{$seperator}{$ini_include_path}");
unset($ini_include_path, $includes_dir); // destroy some variables for security reasons.

set_magic_quotes_runtime(0);

// magic_quotes_gpc can't be disabled at run-time, so clean them up as necessary.
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() ||
    ini_get('magic_quotes_sybase') && strtolower(ini_get('magic_quotes_sybase'))!='off') {
	foreach (array('_GET', '_POST', '_COOKIE', '_REQUEST') as $var) {
		foreach (array_keys($$var) as $key) {
			if (is_array($$var[$key])) {
				$$var[$key]=array_map('stripslashes', $$var[$key]);
			} else {
				$$var[$key]=stripslashes($$var[$key]);
			}
		}
	}
}

if (version_compare(phpversion(), PGV_REQUIRED_PHP_VERSION)<0) {
	die ('<html><body><p style="color: red;">PhpGedView requires PHP version '.PGV_REQUIRED_PHP_VERSION.' or later.</p><p>Your server is running PHP version '.phpversion().'.  Please ask your server\'s Administrator to upgrade the PHP installation.</p></body></html>');
}

//-- load file for language settings
require_once( "includes/lang_settings_std.php");
$Languages_Default = true;
if (!strstr($_SERVER["REQUEST_URI"], "INDEX_DIRECTORY=") && file_exists($INDEX_DIRECTORY . "lang_settings.php")) {
	$DefaultSettings = $language_settings;  // Save default settings, so we can merge properly
	require_once($INDEX_DIRECTORY . "lang_settings.php");
	$ConfiguredSettings = $language_settings; // Save configured settings, same reason
	$language_settings = array_merge($DefaultSettings, $ConfiguredSettings); // Copy new langs into config
	// Now copy new language settings into existing configuration
	foreach ($DefaultSettings as $lang => $settings) {
		foreach ($settings as $key => $value) {
			if (!isset($language_settings[$lang][$key])) $language_settings[$lang][$key] = $value;
		}
	}
	unset($DefaultSettings);
	unset($ConfiguredSettings);  // We don't need these any more
	$Languages_Default = false;
}

//-- build array of active languages (required for config override check)
$pgv_lang_use = array();
foreach ($language_settings as $key => $value) {
	$pgv_lang_use[$key] = $value["pgv_lang_use"];
}

/**
 *  Check for configuration variable override.
 *
 *  Each incoming URI is checked to see whether it contains any mention of
 *  certain critical global variables that should not be changed, or that
 *  can only be changed within limits.
 */
$configOverride = false;
	// Check for override of $CONFIG_VARS
if (isset($_REQUEST["CONFIG_VARS"])) $configOverride = true;

	// $CONFIG_VARS is safe: now check for any in its list
	foreach($CONFIG_VARS as $VAR) {
	if (isset($_REQUEST[$VAR])) {
		$configOverride = true; 
		break ; 
	}
}

//-- check if they are trying to hack
if ($configOverride) {
	if ((!ini_get('register_globals'))||(strtolower(ini_get('register_globals'))=="off")) {
		//-- load common functions
		require_once("includes/functions.php");
		//-- load db specific functions
		require_once("includes/functions_db.php");
		require_once("includes/authentication.php");      // -- load the authentication system
		AddToLog("MSG>Configuration override detected; script terminated.");
		AddToLog("UA>{$ua}<");
		AddToLog("URI>{$_SERVER["REQUEST_URI"]}<");
	}
	header("HTTP/1.0 403 Forbidden");
	print "Hackers are not welcome here.";
	exit;
}

//-- load file for language settings
require_once( "includes/lang_settings_std.php");
$Languages_Default = true;
if (!strstr($_SERVER["REQUEST_URI"], "INDEX_DIRECTORY=") && file_exists($INDEX_DIRECTORY . "lang_settings.php")) {
	$DefaultSettings = $language_settings;		// Save default settings, so we can merge properly
	require_once($INDEX_DIRECTORY . "lang_settings.php");
	$ConfiguredSettings = $language_settings;	// Save configured settings, same reason
	$language_settings = array_merge($DefaultSettings, $ConfiguredSettings);	// Copy new langs into config
	// Now copy new language settings into existing configuration
	foreach ($DefaultSettings as $lang => $settings) {
		foreach ($settings as $key => $value) {
			if (!isset($language_settings[$lang][$key])) $language_settings[$lang][$key] = $value;
	}
	}
	unset($DefaultSettings);
	unset($ConfiguredSettings);		// We don't need these any more
	$Languages_Default = false;
}

//-- build array of active languages (required for config override check)
$pgv_lang_use = array();
foreach ($language_settings as $key => $value) {
	$pgv_lang_use[$key] = $value["pgv_lang_use"];
}
//		Don't let incoming request change to an unsupported or inactive language
if (isset($_REQUEST["NEWLANGUAGE"])) {
	if (empty($pgv_lang_use[$_REQUEST["NEWLANGUAGE"]])) break;
	if (!$pgv_lang_use[$_REQUEST["NEWLANGUAGE"]]) break;
}

/**
 * Cleanup some variables
 */
if (!empty($_SERVER["PHP_SELF"])) $SCRIPT_NAME=$_SERVER["PHP_SELF"];
else if (!empty($_SERVER["SCRIPT_NAME"])) $SCRIPT_NAME=$_SERVER["SCRIPT_NAME"];
$SCRIPT_NAME = preg_replace("~/+~", "/", $SCRIPT_NAME);
if (!empty($_SERVER["QUERY_STRING"])) $QUERY_STRING = $_SERVER["QUERY_STRING"];
else $QUERY_STRING="";
$QUERY_STRING = preg_replace(array("/&/","/</"), array("&amp;","&lt;"), $QUERY_STRING);
$QUERY_STRING = preg_replace("/show_context_help=(no|yes)/", "", $QUERY_STRING);

//-- if not configured then redirect to the configuration script
if (!$CONFIGURED) {
   if ((strstr($SCRIPT_NAME, "admin.php")===false)
   &&(strstr($SCRIPT_NAME, "login.php")===false)
   &&(strstr($SCRIPT_NAME, "editconfig.php")===false)
   &&(strstr($SCRIPT_NAME, "config_download.php")===false)
   &&(strstr($SCRIPT_NAME, "editconfig_help.php")===false)) {
      header("Location: editconfig.php");
      exit;
   }
}
//-- allow user to cancel
ignore_user_abort(false);

if (empty($SERVER_URL)) {
	$SERVER_URL = "http://".$_SERVER["SERVER_NAME"];
	if (!empty($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"]!=80) $SERVER_URL .= ":".$_SERVER["SERVER_PORT"];
	$SERVER_URL .= dirname($SCRIPT_NAME)."/";
	$SERVER_URL = stripslashes($SERVER_URL);
}
if (substr($SERVER_URL,-1)!="/") $SERVER_URL .= "/"; // make SURE that trailing "/" is present

// try and set the memory limit
if (empty($PGV_MEMORY_LIMIT)) $PGV_MEMORY_LIMIT = "32M";
@ini_set('memory_limit', $PGV_MEMORY_LIMIT);

//--load common functions
require_once("includes/functions.php");
require_once("includes/functions_name.php");
//-- set the error handler
$OLD_HANDLER = set_error_handler("pgv_error_handler");
//-- load db specific functions
require_once("includes/functions_db.php");
// -- load the authentication system, also logging
require_once("includes/authentication.php");      

//-- setup execution timer
$start_time = getmicrotime();

//-- start the php session
$time = time()+$PGV_SESSION_TIME;
$date = date("D M j H:i:s T Y", $time);
//-- set the path to the pgv site so that users cannot login on one site
//-- and then automatically be logged in at another site on the same server
$pgv_path = "/";
if (!empty($SCRIPT_NAME)) {
     $dirname = dirname($SCRIPT_NAME);
     if (strstr($SERVER_URL, $dirname)!==false) $pgv_path = str_replace("\\", "/", $dirname);
     unset($dirname);
}
session_set_cookie_params($date, $pgv_path);
unset($date);
unset($pgv_path);
if (($PGV_SESSION_TIME>0)&&(function_exists('session_cache_expire'))) session_cache_expire($PGV_SESSION_TIME/60);
if (!empty($PGV_SESSION_SAVE_PATH)) session_save_path($PGV_SESSION_SAVE_PATH);
if (isset($MANUAL_SESSION_START) && !empty($SID)) session_id($SID);

@session_start();

//-- load up the code to check for spiders
require_once('includes/session_spider.php');

//-- import the gedcoms array
if (file_exists($INDEX_DIRECTORY."gedcoms.php")) {
	require_once($INDEX_DIRECTORY."gedcoms.php");
	if (!is_array($GEDCOMS)) $GEDCOMS = array();
	$i=0;
	foreach ($GEDCOMS as $key => $gedcom) {
		$i++;
		$GEDCOMS[$key]["commonsurnames"] = stripslashes($gedcom["commonsurnames"]);
		if (empty($GEDCOMS[$key]["id"])) $GEDCOMS[$key]["id"] = $i;
		if (empty($GEDCOMS[$key]["pgv_ver"])) $GEDCOMS[$key]["pgv_ver"] = PGV_VERSION;

		// Force the gedcom to be re-imported if the code has been significantly upgraded
		if (substr($GEDCOMS[$key]["pgv_ver"], 0, 3) != substr(PGV_VERSION, 0, 3))
			$GEDCOMS[$key]["imported"] = false;
	}
}
else $GEDCOMS=array();

//-- connect to the database
$DBPASS = str_replace(array("\\\\", "\\\"", "\\\$"), array("\\", "\"", "\$"), $DBPASS); // remove escape codes before using PW
$PGV_DB_CONNECTED = check_db();

//-- try to set the active GEDCOM
if (!isset($DEFAULT_GEDCOM)) $DEFAULT_GEDCOM = "";
if (isset($_SESSION["GEDCOM"])) $GEDCOM = $_SESSION["GEDCOM"];
if (isset($_REQUEST["GEDCOM"])) $GEDCOM = trim($_REQUEST["GEDCOM"]);
if (isset($_REQUEST["ged"])) $GEDCOM = trim($_REQUEST["ged"]);
if (!empty($GEDCOM) && is_int($GEDCOM)) $GEDCOM = get_gedcom_from_id($GEDCOM);
if (empty($GEDCOM) || empty($GEDCOMS[$GEDCOM])) $GEDCOM=$DEFAULT_GEDCOM;
if ((empty($GEDCOM))&&(count($GEDCOMS)>0)) {
         foreach($GEDCOMS as $ged_file=>$ged_array) {
	         $GEDCOM = $ged_file;
	         if (check_for_import($ged_file)) break;
         }
      }
$_SESSION["GEDCOM"] = $GEDCOM;

// privacy constants moved from privacy.php
$PRIV_HIDE = -1; //  Global constant privacy level to hide the item to all users including the admin
$PRIV_PUBLIC = 2; //  Global constant privacy level that allows non-authenticated public visitors to view the marked information
$PRIV_USER = 1; //  Global constant privacy level that only allows authenticated users to access the marked information
$PRIV_NONE = 0; //  Global constant privacy level that only allows admin users to access the marked information

/**
 * Load GEDCOM configuration
 */
require_once("config_gedcom.php");
require_once(get_config_file());
if (empty($PHPGEDVIEW_EMAIL)) {
	$PHPGEDVIEW_EMAIL = "phpgedview-noreply@".preg_replace("/^www\./i", "", $_SERVER["SERVER_NAME"]);
}

/**
 * do not include print functions when using the gdbi protocol
 */
if (strstr($SCRIPT_NAME, "client.php")===false && strstr($SCRIPT_NAME, "genservice.php")===false) {
	//-- load media specific functions
	require_once("includes/functions_print.php");
	require_once("includes/functions_rtl.php");
}

if ($MULTI_MEDIA) require_once("includes/functions_mediadb.php");
require_once("includes/functions_date.php");

if (empty($PEDIGREE_GENERATIONS)) $PEDIGREE_GENERATIONS = $DEFAULT_PEDIGREE_GENERATIONS;

/* Re-build the various language-related arrays
 *  Note:
 *  This code existed in both lang_settings_std.php and in lang_settings.php.
 *  It has been removed from both files and inserted here, where it belongs.
 */
$languages				= array();
$pgv_lang_use			= array();
$pgv_lang_self			= array();
$lang_short_cut			= array();
$lang_langcode			= array();
$pgv_language			= array();
$confighelpfile			= array();
$helptextfile			= array();
$flagsfile				= array();
$factsfile				= array();
$adminfile				= array();
$editorfile				= array();
$countryfile			= array();
$faqlistfile			= array();
$extrafile				= array();
$factsarray				= array();
$pgv_lang_name			= array();
$ALPHABET_upper			= array();
$ALPHABET_lower			= array();
$MULTI_LETTER_ALPHABET	= array();
$DICTIONARY_SORT		= array();
$DATE_FORMAT_array		= array();
$TIME_FORMAT_array		= array();
$WEEK_START_array		= array();
$TEXT_DIRECTION_array	= array();
$NAME_REVERSE_array		= array();

foreach ($language_settings as $key => $value) {
	if (!isset($value['pgv_lang_self']) || !isset($value['pgv_language']) || !file_exists($value['pgv_language'])) continue;
	$languages[$key]			 = $value["pgv_langname"];
	$pgv_lang_use[$key]          = $value["pgv_lang_use"];
	$pgv_lang_self[$key]		 = $value["pgv_lang_self"];
	$lang_short_cut[$key]        = $value["lang_short_cut"];
	$lang_langcode[$key]         = $value["langcode"];
	$pgv_language[$key]          = $value["pgv_language"];
	$confighelpfile[$key]        = $value["confighelpfile"];
	$helptextfile[$key]          = $value["helptextfile"];
	$flagsfile[$key]             = $value["flagsfile"];
	$factsfile[$key]             = $value["factsfile"];
	$adminfile[$key]             = $value["adminfile"];
	$editorfile[$key]            = $value["editorfile"];
	$countryfile[$key]           = $value["countryfile"];
	$faqlistfile[$key]           = $value["faqlistfile"];
	$extrafile[$key]             = $value["extrafile"];
	$ALPHABET_upper[$key]        = $value["ALPHABET_upper"];
	$ALPHABET_lower[$key]        = $value["ALPHABET_lower"];
	$MULTI_LETTER_ALPHABET[$key] = $value["MULTI_LETTER_ALPHABET"];
	$DICTIONARY_SORT[$key]       = $value["DICTIONARY_SORT"];
	$DATE_FORMAT_array[$key]     = $value["DATE_FORMAT"];
	$TIME_FORMAT_array[$key]     = $value["TIME_FORMAT"];;
	$WEEK_START_array[$key]      = $value["WEEK_START"];
	$TEXT_DIRECTION_array[$key]  = $value["TEXT_DIRECTION"];
	$NAME_REVERSE_array[$key]    = $value["NAME_REVERSE"];

	$pgv_lang["lang_name_$key"]  = $value["pgv_lang_self"];
}

/**
 * The following business rules are used to choose currently active language
 * 1. Use the language in visitor's browser settings if it is supported in the PGV site.
 *    If it is not supported, use the GEDCOM configuration setting.
 * 2. If the user has chosen a language from the list or the flags, use their choice.
 * 3. When the user logs in, switch to the language in their user profile unless the 
 *    user made a language choice prior to logging in.
 * 4. When a user logs out their current language choice is ignored and the site will
 *    revert back to the language they first saw when arriving at the site according to
 *    rule 1.
 */
$logout=safe_GET_bool('logout');
if ($logout) unset($_SESSION["CLANGUAGE"]);  // user is about to log out

if (($ENABLE_MULTI_LANGUAGE)&&(empty($_SESSION["CLANGUAGE"]))&&(empty($SEARCH_SPIDER))) {
	if (isset($HTTP_ACCEPT_LANGUAGE)) $accept_langs = $HTTP_ACCEPT_LANGUAGE;
	else if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $accept_langs = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	if (isset($accept_langs)) {
		// Seach list of supported languages for this Browser's preferred page languages
		$langs_array = preg_split("/(,\s*)|(;\s*)/", $accept_langs);
		$foundLanguage = false;
		foreach ($langs_array as $browserLang) {
			$browserLang = strtolower($browserLang).";";
			foreach ($pgv_lang_use as $language => $active) {
				if (!$active) continue;
				if (strpos($lang_langcode[$language], $browserLang) === false) continue;
				$LANGUAGE = $language;
				$foundLanguage = true;
				break;
			}
			if ($foundLanguage) break;
		}
	}
}
$deflang = $LANGUAGE;

if(empty($SEARCH_SPIDER)) {
   if (!empty($_SESSION['CLANGUAGE'])) $CLANGUAGE = $_SESSION['CLANGUAGE'];
   else if (!empty($HTTP_SESSION_VARS['CLANGUAGE'])) $CLANGUAGE = $HTTP_SESSION_VARS['CLANGUAGE'];
   if (!empty($CLANGUAGE)) {
      $LANGUAGE = $CLANGUAGE;
   }
}

if (($ENABLE_MULTI_LANGUAGE) && (empty($SEARCH_SPIDER))) {
	if ((isset($_REQUEST['changelanguage']))&&($_REQUEST['changelanguage']=="yes")) {
		if (!empty($_REQUEST['NEWLANGUAGE']) && isset($pgv_language[$_REQUEST['NEWLANGUAGE']])) {
			$LANGUAGE=$_REQUEST['NEWLANGUAGE'];
			unset($_SESSION["upcoming_events"]);
			unset($_SESSION["todays_events"]);
		}
	}
}

require_once("includes/templecodes.php");  //-- load in the LDS temple code translations

require_once("privacy.php");
//-- load the privacy file
require_once(get_privacy_file());
//-- load the privacy functions
require_once("includes/functions_privacy.php");

//-----------------------------------
//-- if user wishes to logout this is where we will do it
if ($logout) {
	userLogout(getUserId());
	if ($REQUIRE_AUTHENTICATION) {
		header("Location: {$SERVER_URL}");
		exit;
	}
}

// Define some constants to save calculating the same value repeatedly.
define('PGV_GEDCOM',            $GEDCOM);
define('PGV_GED_ID',            get_id_from_gedcom(PGV_GEDCOM));
define('PGV_USER_ID',           getUserId  ());
define('PGV_USER_NAME',         getUserName());
define('PGV_USER_IS_ADMIN',     userIsAdmin       (PGV_USER_ID));
define('PGV_USER_GEDCOM_ADMIN', userGedcomAdmin   (PGV_USER_ID, PGV_GED_ID));
define('PGV_USER_CAN_ACCESS',   userCanAccess     (PGV_USER_ID, PGV_GED_ID));
define('PGV_USER_CAN_EDIT',     userCanEdit       (PGV_USER_ID, PGV_GED_ID));
define('PGV_USER_CAN_ACCEPT',   userCanAccept     (PGV_USER_ID, PGV_GED_ID));
define('PGV_USER_AUTO_ACCEPT',  userAutoAccept    (PGV_USER_ID));
define('PGV_USER_ACCESS_LEVEL', getUserAccessLevel(PGV_USER_ID, PGV_GED_ID));
define('PGV_USER_GEDCOM_ID',    get_user_gedcom_setting(PGV_USER_ID, PGV_GED_ID, 'gedcomid'));
define('PGV_USER_ROOT_ID',      get_user_gedcom_setting(PGV_USER_ID, PGV_GED_ID, 'rootid'));

// Only site administrators should see debugging output.
define('PGV_DEBUG', PGV_USER_IS_ADMIN && safe_GET_bool('debug'));

// Load all the language variables and language-specific functions
loadLanguage($LANGUAGE, true);

// Check for page views exceeding the limit
CheckPageViews();

if (!isset($SCRIPT_NAME)) $SCRIPT_NAME=$_SERVER["PHP_SELF"];

$show_context_help = "";
if (!empty($_REQUEST['show_context_help'])) $show_context_help = $_REQUEST['show_context_help'];
if (!isset($_SESSION["show_context_help"])) $_SESSION["show_context_help"] = $SHOW_CONTEXT_HELP;
if (!isset($_SESSION["pgv_user"])) $_SESSION["pgv_user"] = "";
if (!isset($_SESSION["cookie_login"])) $_SESSION["cookie_login"] = false;
if (isset($SHOW_CONTEXT_HELP) && $show_context_help==='yes') $_SESSION["show_context_help"] = true;
if (isset($SHOW_CONTEXT_HELP) && $show_context_help==='no') $_SESSION["show_context_help"] = false;
if (!isset($USE_THUMBS_MAIN)) $USE_THUMBS_MAIN = false;
if ((strstr($SCRIPT_NAME, "editconfig.php")===false)
	&&(strstr($SCRIPT_NAME, "editconfig_help.php")===false)) {
	if (!$PGV_DB_CONNECTED || !adminUserExists()) {
		header("Location: editconfig.php");
		exit;
	}
	
	if ((count($GEDCOMS)==0)||(!check_for_import($GEDCOM))) {
		$scriptList = array("editconfig_gedcom.php", "help_text.php", "editconfig_help.php", "editgedcoms.php", "uploadgedcom.php", "login.php", "admin.php", "config_download.php", "addnewgedcom.php", "validategedcom.php", "addmedia.php", "importgedcom.php", "client.php", "edit_privacy.php", "upgrade33-40.php", "gedcheck.php", "printlog.php", "editlang.php", "editlang_edit.php" ,"useradmin.php");
		$inList = false;
		foreach ($scriptList as $key => $listEntry) {
			if (strstr($SCRIPT_NAME, $listEntry)) {
				$inList = true;
				break;
			}
		}
		if (!$inList) {
			header("Location: editgedcoms.php");
			exit;
		}
		unset($scriptList);
	}

	if ($REQUIRE_AUTHENTICATION) {
		if (!PGV_USER_ID) {
			if ((strstr($SCRIPT_NAME, "login.php")===false)
					&&(strstr($SCRIPT_NAME, "login_register.php")===false)
					&&(strstr($SCRIPT_NAME, "client.php")===false)
					&&(strstr($SCRIPT_NAME, "genservice.php")===false)
					&&(strstr($SCRIPT_NAME, "help_text.php")===false)
					&&(strstr($SCRIPT_NAME, "message.php")===false)) {
				if (!empty($_REQUEST['auth']) && $_REQUEST['auth']=="basic") { //if user is attempting basic authentication //TODO: Update if degest auth is ever implemented
						basicHTTPAuthenticateUser();
				} else {
					$url = basename($_SERVER["PHP_SELF"])."?".$QUERY_STRING;
					if (stristr($url, "index.php")!==false) {
						if (stristr($url, "ctype=")===false) {
							if ((!isset($_SERVER['HTTP_REFERER'])) || (stristr($_SERVER['HTTP_REFERER'],$SERVER_URL)===false)) $url .= "&ctype=gedcom";
						}
					}
					if (stristr($url, "ged=")===false)  {
						$url.="&ged=".$GEDCOM;
					}
					$url = str_replace("?&", "?", $url);
					header("Location: login.php?url=".urlencode($url));
					exit;
				}
			}
		}
	}

   // -- setup session information for tree clippings cart features
   if ((!isset($_SESSION['cart'])) || (!empty($_SESSION['last_spider_name']))) { // reset cart everytime for spiders
     $_SESSION['cart'] = array();
   }
   $cart = $_SESSION['cart'];

   $_SESSION['CLANGUAGE'] = $LANGUAGE;
   if (!isset($_SESSION["timediff"])) {
	   $_SESSION["timediff"] = 0;
   }

	//-- load any editing changes
	if (PGV_USER_CAN_EDIT && file_exists($INDEX_DIRECTORY."pgv_changes.php")) {
		require_once($INDEX_DIRECTORY."pgv_changes.php");
	} else {
		$pgv_changes = array();
	}

	if (empty($LOGIN_URL)) {
		$LOGIN_URL = "login.php";
	}

}

//-- load the user specific theme
if ($PGV_DB_CONNECTED && PGV_USER_ID && !isset($_REQUEST['logout'])) {
	//-- update the login time every 5 minutes
	if (!isset($_SESSION['activity_time']) || (time()-$_SESSION['activity_time'])>300) {
		userUpdateLogin(PGV_USER_ID);
		$_SESSION['activity_time'] = time();
	}

	$usertheme = get_user_setting(PGV_USER_ID, 'theme');
	if ((!empty($_POST["user_theme"]))&&(!empty($_POST["oldusername"]))&&($_POST["oldusername"]==PGV_USER_ID)) $usertheme = $_POST["user_theme"];
	if ((!empty($usertheme)) && (file_exists($usertheme."theme.php")))  {
		$THEME_DIR = $usertheme;
	}
}

if (isset($_SESSION["theme_dir"]))
{
	$THEME_DIR = $_SESSION["theme_dir"];
	if (PGV_USER_ID) {
		if (get_user_setting(PGV_USER_ID, 'editaccount')=='Y') unset($_SESSION["theme_dir"]);
	}
}

if (empty($THEME_DIR)) $THEME_DIR="standard/";
if (file_exists($THEME_DIR."theme.php")) require_once($THEME_DIR."theme.php");
else {
	$THEME_DIR = "themes/standard/";
	require_once($THEME_DIR."theme.php");
}

require_once("hitcount.php"); //--load the hit counter

if ($Languages_Default) {            // If Languages not yet configured
	$pgv_lang_use["english"] = false;  //  disable English
	$pgv_lang_use[$LANGUAGE] = true; //  and enable according to Browser pref.
	$language_settings["english"]["pgv_lang_use"] = false;
	$language_settings[$LANGUAGE]["pgv_lang_use"] = true;
}

// Characters with weak-directionality can confuse the browser's BIDI algorithm.
// Make sure that they follow the directionality of the page, not that of the
// enclosed text.
if ($TEXT_DIRECTION=='ltr') {
	define ('PGV_LPARENS', '&lrm;(');
	define ('PGV_RPARENS', ')&lrm;');
} else {
	define ('PGV_LPARENS', '&rlm;(');
	define ('PGV_RPARENS', ')&rlm;');
}

//-- temporarily adding back in
@import_request_variables('gcp');
?>
