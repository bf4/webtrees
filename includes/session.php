<?php
/**
 * Startup and session logic
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 */

// WT_SCRIPT_NAME is defined in each script that the user is permitted to load.
if (!defined('WT_SCRIPT_NAME')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// Identify ourself
define('WT_WEBTREES',        'webtrees');
define('WT_VERSION',         '1.0.7');
define('WT_VERSION_RELEASE', 'svn'); // 'svn', 'beta', 'rc1', '', etc.
define('WT_VERSION_TEXT',    trim(WT_VERSION.' '.WT_VERSION_RELEASE));
define('WT_WEBTREES_URL',    'http://webtrees.net');
define('WT_WEBTREES_WIKI',   'http://wiki.webtrees.net');
define('WT_TRANSLATORS_URL', 'https://translations.launchpad.net/webtrees');

// Enable debugging output?
define('WT_DEBUG',      false);
define('WT_DEBUG_SQL',  false);

// Error reporting
define('WT_ERROR_LEVEL', 2); // 0=none, 1=minimal, 2=full

// Required version of database tables/columns/indexes/etc.
define('WT_SCHEMA_VERSION', 6);

// Regular expressions for validating user input, etc.
define('WT_REGEX_XREF',     '[A-Za-z0-9:_-]+');
define('WT_REGEX_TAG',      '[_A-Z][_A-Z0-9]*');
define('WT_REGEX_INTEGER',  '-?\d+');
define('WT_REGEX_ALPHA',    '[a-zA-Z]+');
define('WT_REGEX_ALPHANUM', '[a-zA-Z0-9]+');
define('WT_REGEX_BYTES',    '[0-9]+[bBkKmMgG]?');
define('WT_REGEX_USERNAME', '[^<>"%{};]+');
define('WT_REGEX_PASSWORD', '.{6,}');
define('WT_REGEX_NOSCRIPT', '[^<>"&%{};]+');
define('WT_REGEX_URL',      '[\/0-9A-Za-z_!~*\'().;?:@&=+$,%#-]+'); // Simple list of valid chars
define('WT_REGEX_EMAIL',    '[^\s<>"&%{};@]+@[^\s<>"&%{};@]+');
define('WT_REGEX_UNSAFE',   '[\x00-\xFF]*'); // Use with care and apply additional validation!

// UTF8 representation of various characters
define('WT_UTF8_BOM',    "\xEF\xBB\xBF"); // U+FEFF
define('WT_UTF8_MALE',   "\xE2\x99\x82"); // U+2642
define('WT_UTF8_FEMALE', "\xE2\x99\x80"); // U+2640

// UTF8 control codes affecting the BiDirectional algorithm (see http://www.unicode.org/reports/tr9/)
define('WT_UTF8_LRM',    "\xE2\x80\x8E"); // U+200E  (Left to Right mark:  zero-width character with LTR directionality)
define('WT_UTF8_RLM',    "\xE2\x80\x8F"); // U+200F  (Right to Left mark:  zero-width character with RTL directionality)
define('WT_UTF8_LRO',    "\xE2\x80\xAD"); // U+202D  (Left to Right override: force everything following to LTR mode)
define('WT_UTF8_RLO',    "\xE2\x80\xAE"); // U+202E  (Right to Left override: force everything following to RTL mode)
define('WT_UTF8_LRE',    "\xE2\x80\xAA"); // U+202A  (Left to Right embedding: treat everything following as LTR text)
define('WT_UTF8_RLE',    "\xE2\x80\xAB"); // U+202B  (Right to Left embedding: treat everything following as RTL text)
define('WT_UTF8_PDF',    "\xE2\x80\xAC"); // U+202C  (Pop directional formatting: restore state prior to last LRO, RLO, LRE, RLE)

// Alternatives to BMD events for lists, charts, etc.
define('WT_EVENTS_BIRT', 'BIRT|CHR|BAPM|_BRTM|ADOP');
define('WT_EVENTS_DEAT', 'DEAT|BURI|CREM');
define('WT_EVENTS_MARR', 'MARR|MARB');
define('WT_EVENTS_DIV',  'DIV|ANUL|_SEPR');

// Use these line endings when writing files on the server
define('WT_EOL', "\r\n");

// Gedcom specification/definitions
define ('WT_GEDCOM_LINE_LENGTH', 255-strlen(WT_EOL)); // Characters, not bytes

// Use these tags to wrap embedded javascript consistently
define('WT_JS_START', "\n<script type=\"text/javascript\">\n//<![CDATA[\n");
define('WT_JS_END',   "\n//]]>\n</script>\n");

// Used in Google charts
define ('WT_GOOGLE_CHART_ENCODING', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.');

// Maximum number of results in auto-complete fields
define('WT_AUTOCOMPLETE_LIMIT', 500);

// Privacy constants
define('WT_PRIV_PUBLIC',  2); // Allows non-authenticated public visitors to view the marked information
define('WT_PRIV_USER',    1); // Allows authenticated users to access the marked information
define('WT_PRIV_NONE',    0); // Allows admin users to access the marked information
define('WT_PRIV_HIDE',   -1); // Hide the item to all users including the admin

// For performance, it is quicker to refer to files using absolute paths
define ('WT_ROOT', realpath(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);

// Keep track of time statistics, for the summary in the footer
$start_time=microtime(true);
$PRIVACY_CHECKS=0;

ini_set('arg_separator.output', '&amp;');
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', '1');
error_reporting(E_ALL | E_STRICT);

// Invoke the Zend Framework Autoloader, so we can use Zend_XXXXX classes
set_include_path(WT_ROOT.'library'.PATH_SEPARATOR.get_include_path());
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

// Check configuration issues that affect various versions of PHP
if (version_compare(PHP_VERSION, '6.0', '<')) {
	if (get_magic_quotes_runtime()) {
		// Magic quotes were deprecated in PHP5.3 and removed in PHP6.0
		// Disabling them on PHP5.3 will cause a strict-warning, so ignore errors.
		@set_magic_quotes_runtime(false);
	}
	// magic_quotes_gpc can't be disabled at run-time, so clean them up as necessary.
	if (get_magic_quotes_gpc() || ini_get('magic_quotes_sybase') && strtolower(ini_get('magic_quotes_sybase'))!='off') {
		$in = array(&$_GET, &$_POST, &$_REQUEST, &$_COOKIE);
		while (list($k,$v) = each($in)) {
			foreach ($v as $key => $val) {
				if (!is_array($val)) {
					$in[$k][$key] = stripslashes($val);
					continue;
				}
				$in[] =& $in[$k][$key];
			}
		}
		unset($in);
	}
}
// PHP requires a time zone to be set in php.ini
if (!ini_get('date.timezone')) {
	date_default_timezone_set(@date_default_timezone_get());
}

// Split the request "protocol://host:port/path/to/script.php?var=value" into parts
// WT_SERVER_NAME  = protocol://host:port
// WT_SCRIPT_PATH  = /path/to/   (begins and ends with /)
// WT_SCRIPT_NAME  = script.php  (already defined in the calling script)
// WT_QUERY_STRING = ?var=value  (generate as needed from $_GET.  lang=xx and theme=yy are removed as used.)
// TODO: we ought to generate this dynamically, but lots of code currently relies on this global
$QUERY_STRING=isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

define('WT_SERVER_NAME',
	(empty($_SERVER['HTTPS']) || !in_array($_SERVER['HTTPS'], array('1', 'on', 'On', 'ON')) ?  'http://' : 'https://').
	(empty($_SERVER['SERVER_NAME']) ? '' : $_SERVER['SERVER_NAME']).
	(empty($_SERVER['SERVER_PORT']) || $_SERVER['SERVER_PORT']==80 ? '' : ':'.$_SERVER['SERVER_PORT'])
);

// SCRIPT_NAME should always be correct, but is not always present.
// PHP_SELF should always be present, but may have trailing path: /path/to/script.php/FOO/BAR
if (!empty($_SERVER['SCRIPT_NAME'])) {
	define('WT_SCRIPT_PATH', substr($_SERVER['SCRIPT_NAME'], 0, stripos($_SERVER['SCRIPT_NAME'], WT_SCRIPT_NAME)));
} elseif (!empty($_SERVER['PHP_SELF'])) {
	define('WT_SCRIPT_PATH', substr($_SERVER['PHP_SELF'], 0, stripos($_SERVER['PHP_SELF'], WT_SCRIPT_NAME)));
} else {
	// No server settings - probably running as a command line script
	define('WT_SCRIPT_PATH', '/');
}

// Microsoft IIS servers don't set REQUEST_URI, so generate it for them.
if (!isset($_SERVER['REQUEST_URI']))  {
	$_SERVER['REQUEST_URI']=substr($_SERVER['PHP_SELF'], 1);
	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING'];
	}
}

// Common functions
require WT_ROOT.'includes/functions/functions.php';
require WT_ROOT.'includes/functions/functions_name.php';
require WT_ROOT.'includes/functions/functions_db.php';
require WT_ROOT.'includes/classes/class_wt_db.php';

set_error_handler('wt_error_handler');

// Load our configuration file, so we can connect to the database
if (file_exists(WT_ROOT.'data/config.ini.php')) {
	$dbconfig=parse_ini_file(WT_ROOT.'data/config.ini.php');
	// Invalid/unreadable config file?
	if (!is_array($dbconfig)) {
		header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'site-unavailable.php');
		exit;
	}
} else {
	// No config file. Set one up.
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'setup.php');
	exit;
}

require WT_ROOT.'includes/authentication.php';

// Connect to the database
try {
	WT_DB::createInstance($dbconfig['dbhost'], $dbconfig['dbport'], $dbconfig['dbname'], $dbconfig['dbuser'], $dbconfig['dbpass']);
	define('WT_TBLPREFIX', $dbconfig['tblpfx']);
	unset($dbconfig);
	// Some of the FAMILY JOIN HUSBAND JOIN WIFE queries can excede the MAX_JOIN_SIZE setting
	WT_DB::exec("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci', SQL_BIG_SELECTS=1");
	try {
		WT_DB::updateSchema(WT_ROOT.'includes/db_schema/', 'WT_SCHEMA_VERSION', WT_SCHEMA_VERSION);
	} catch (PDOException $ex) {
		// The schema update scripts should never fail.  If they do, there is no clean recovery.
		die($ex);
	}
} catch (PDOException $ex) {
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'site-unavailable.php');
	exit;
}

// If we have a preferred URL (e.g. https instead of http, or www.example.com instead of
// www.isp.com/~example), then redirect to it.
$SERVER_URL=get_site_setting('SERVER_URL');
if ($SERVER_URL && $SERVER_URL != WT_SERVER_NAME.WT_SCRIPT_PATH) {
	header('Location: '.get_site_setting('SERVER_URL'));
	exit;
}

//-- allow user to cancel
ignore_user_abort(false);

// Request more resources - if we can/want to
if (!ini_get('safe_mode')) {
	$memory_limit=get_site_setting('MEMORY_LIMIT');
	if ($memory_limit) {
		ini_set('memory_limit', $memory_limit);
	}
	$max_execution_time=get_site_setting('MAX_EXECUTION_TIME');
	if ($max_execution_time && strpos(ini_get('disable_functions'), 'set_time_limit')===false) {
		set_time_limit($max_execution_time);
	}
}

// Determine browser type
$BROWSERTYPE = 'other';
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
	if (stristr($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
		$BROWSERTYPE = 'opera';
	} elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
		$BROWSERTYPE = 'netscape';
	} elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'KHTML')) {
		$BROWSERTYPE = 'chrome';
	} elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Gecko')) {
		$BROWSERTYPE = 'mozilla';
	} elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
		$BROWSERTYPE = 'msie';
	}
}

//-- load up the code to check for spiders
require WT_ROOT.'includes/session_spider.php';

// Search engines are only allowed to see certain pages.
if ($SEARCH_SPIDER && !in_array(WT_SCRIPT_NAME , array(
	'family.php', 'famlist.php', 'index.php', 'indilist.php', 'individual.php',
	'media.php', 'medialist.php', 'note.php', 'notelist.php', 'repo.php', 'repolist.php',
	'search_engine.php', 'site-unavailable.php', 'source.php', 'sourcelist.php'
))) {
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'search_engine.php');
	exit;
}

// Store our session data in the database.
session_set_save_handler(
	create_function('', 'return true;'), // open
	create_function('', 'return true;'), // close
	create_function('$id', 'return WT_DB::prepare("SELECT session_data FROM `##session` WHERE session_id=?")->execute(array($id))->fetchOne();'), // read
	create_function('$id,$data', 'WT_DB::prepare("REPLACE INTO `##session` (session_id, user_id, ip_address, session_data) VALUES (?,?,?,?)")->execute(array($id, WT_USER_ID, $_SERVER["REMOTE_ADDR"], $data));return true;'), // write
	create_function('$id', 'WT_DB::prepare("DELETE FROM `##session` WHERE session_id=?")->execute(array($id));return true;'), // destroy
	create_function('$maxlifetime', 'WT_DB::prepare("DELETE FROM `##session` WHERE session_time < DATE_SUB(NOW(), INTERVAL ? SECOND)")->execute(array($maxlifetime));return true;') // gc
);

// Use the Zend_Session object to start the session.
// This allows all the other Zend Framework components to integrate with the session
define('WT_SESSION_NAME', 'WT_SESSION');
$cfg=array(
	'name'            => WT_SESSION_NAME,
	'cookie_lifetime' => 0,
	'gc_maxlifetime'  => get_site_setting('SESSION_TIME'),
	'cookie_path'     => WT_SCRIPT_PATH,
);
Zend_Session::start($cfg);

// Register a session "namespace" to store session data.  This is better than
// using $_SESSION, as we can avoid clashes with other modules/applications,
// and problems with servers that have enabled "register_globals".
$WT_SESSION=new Zend_Session_Namespace('WEBTREES');

if (!$SEARCH_SPIDER && !$WT_SESSION->initiated) {
	// A new session, so prevent session fixation attacks by choosing a new PHPSESSID.
	Zend_Session::regenerateId();
	$WT_SESSION->initiated=true;
} else {
	// An existing session
}

// Set the active GEDCOM
if (isset($_REQUEST['ged'])) {
	// .... from the URL or form action
	$GEDCOM=$_REQUEST['ged'];
} elseif (isset($_REQUEST['GEDCOM'])) {
	// .... is this used ????
	$GEDCOM=$_REQUEST['GEDCOM'];
} elseif (isset($_SESSION['GEDCOM'])) {
	// .... the most recently used one
	$GEDCOM=$_SESSION['GEDCOM'];
} else {
	// .... we'll need to query the DB to find one
	$GEDCOM='';
}

// Does the requested GEDCOM exist?
$ged_id=get_id_from_gedcom($GEDCOM);
if (!$ged_id) {
	// Try the site default
	$GEDCOM=get_site_setting('DEFAULT_GEDCOM');
	$ged_id=get_id_from_gedcom($GEDCOM);
	// Try any one
	if (!$ged_id) {
		foreach (get_all_gedcoms() as $ged_id=>$GEDCOM) {
			if (get_gedcom_setting($ged_id, 'imported')) {
				break;
			}
		}
	}
}
define('WT_GEDCOM', $GEDCOM);
define('WT_GED_ID', $ged_id);
define('WT_GEDURL', rawurlencode(WT_GEDCOM));

load_gedcom_settings(WT_GED_ID);

// Set our gedcom selection as a default for the next page
$_SESSION['GEDCOM']=WT_GEDCOM;

if (empty($WEBTREES_EMAIL)) {
	$WEBTREES_EMAIL='webtrees-noreply@'.preg_replace('/^www\./i', '', $_SERVER['SERVER_NAME']);
}

require WT_ROOT.'includes/functions/functions_print.php';
require WT_ROOT.'includes/functions/functions_rtl.php';

if ($MULTI_MEDIA) {
	require WT_ROOT.'includes/functions/functions_mediadb.php';
}
require WT_ROOT.'includes/functions/functions_date.php';

// Use the server date to calculate privacy, etc.
// Use the client date to show ages, etc.
define('WT_SERVER_JD', timestamp_to_jd(time()));
define('WT_CLIENT_JD', timestamp_to_jd(client_time()));

// Who are we?
define('WT_USER_ID', getUserId());

// With no parameters, init() looks to the environment to choose a language
require WT_ROOT.'includes/classes/class_i18n.php';
define('WT_LOCALE', i18n::init());

// Application configuration data - things that aren't (yet?) user-editable
require WT_ROOT.'includes/config_data.php';

//-- load the privacy functions
require WT_ROOT.'includes/functions/functions_privacy.php';

// The current user's profile - from functions in authentication.php
define('WT_USER_NAME',         getUserName());
define('WT_USER_IS_ADMIN',     userIsAdmin   (WT_USER_ID));
define('WT_USER_AUTO_ACCEPT',  userAutoAccept(WT_USER_ID));
define('WT_USER_GEDCOM_ADMIN', WT_USER_IS_ADMIN     || userGedcomAdmin(WT_USER_ID, WT_GED_ID));
define('WT_USER_CAN_ACCEPT',   WT_USER_GEDCOM_ADMIN || userCanAccept  (WT_USER_ID, WT_GED_ID));
define('WT_USER_CAN_EDIT',     WT_USER_CAN_ACCEPT   || userCanEdit    (WT_USER_ID, WT_GED_ID));
define('WT_USER_CAN_ACCESS',   WT_USER_CAN_EDIT     || userCanAccess  (WT_USER_ID, WT_GED_ID));
define('WT_USER_ACCESS_LEVEL', getUserAccessLevel(WT_USER_ID, WT_GED_ID));
define('WT_USER_GEDCOM_ID',    getUserGedcomId   (WT_USER_ID, WT_GED_ID));
define('WT_USER_ROOT_ID',      getUserRootId     (WT_USER_ID, WT_GED_ID));
define('WT_USER_PATH_LENGTH',  get_user_gedcom_setting(WT_USER_ID, WT_GED_ID, 'RELATIONSHIP_PATH_LENGTH'));

// If we are logged in, and logout=1 has been added to the URL, log out
if (WT_USER_ID && safe_GET_bool('logout')) {
	userLogout(WT_USER_ID);
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH);
	exit;
}

// Do we show context help on the page?
if (isset($_GET['show_context_help'])) {
	$_SESSION['show_context_help']=safe_GET_bool('show_context_help');
	unset($_GET['show_context_help']);
} elseif ($SEARCH_SPIDER) {
	$_SESSION['show_context_help']=false;
} elseif (!isset($_SESSION['show_context_help'])) {
	$_SESSION['show_context_help']=get_gedcom_setting(WT_GED_ID, 'SHOW_CONTEXT_HELP', true);
}

if (!isset($_SESSION['wt_user'])) $_SESSION['wt_user'] = '';

if (WT_SCRIPT_NAME!='help_text.php') {
	if (!get_gedcom_setting(WT_GED_ID, 'imported') && !in_array(WT_SCRIPT_NAME, array('editconfig_gedcom.php', 'help_text.php', 'editgedcoms.php', 'downloadgedcom.php', 'logs.php', 'login.php', 'siteconfig.php', 'admin.php', 'addmedia.php', 'client.php', 'gedcheck.php', 'useradmin.php', 'export_gedcom.php', 'edit_changes.php', 'import.php', 'pgv_to_wt.php'))) {
		header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'editgedcoms.php');
		exit;
	}

	if ($REQUIRE_AUTHENTICATION && !WT_USER_ID && !in_array(WT_SCRIPT_NAME, array('login.php', 'login_register.php', 'client.php', 'genservice.php', 'help_text.php', 'message.php'))) {
		if (!empty($_REQUEST['auth']) && $_REQUEST['auth']=='basic') {
			// if user is attempting basic authentication
			// TODO: Update if digest auth is ever implemented
			basicHTTPAuthenticateUser();
		} else {
			if (WT_SCRIPT_NAME=='index.php') {
				$url='index.php?ged='.WT_GEDCOM;
			} else {
				$url=WT_SCRIPT_NAME.'?'.$QUERY_STRING;
			}
			header('Location: '.get_site_setting('LOGIN_URL').'?url='.rawurlencode($url));
			exit;
		}
	}

	// -- setup session information for tree clippings cart features
	if ((!isset($_SESSION['cart'])) || (!empty($_SESSION['last_spider_name']))) { // reset cart everytime for spiders
		$_SESSION['cart'] = array();
	}
	$cart = $_SESSION['cart'];

	if (!isset($_SESSION['timediff'])) {
		$_SESSION['timediff'] = 0;
	}
}

if (WT_USER_ID) {
	//-- update the login time every 5 minutes
	if (!isset($_SESSION['activity_time']) || (time()-$_SESSION['activity_time'])>300) {
		userUpdateLogin(WT_USER_ID);
		$_SESSION['activity_time'] = time();
	}
}

// Set the theme
if (!defined('WT_THEME_DIR')) {
	if (get_site_setting('ALLOW_USER_THEMES')) {
		// Requested change of theme?
		$THEME_DIR=safe_GET('theme', get_theme_names());
		unset($_GET['theme']);
		// Last theme used?
		if (!$THEME_DIR && isset($_SESSION['theme_dir']) && in_array($_SESSION['theme_dir'], get_theme_names())) {
			$THEME_DIR=$_SESSION['theme_dir'];
		}
	}
	if (!$THEME_DIR) {
		// User cannot choose (or has not chosen) a theme.
		// 1) gedcom setting
		// 2) site setting
		// 3) webtrees
		// 4) first one found
		$THEME_DIR=get_gedcom_setting(WT_GED_ID, 'THEME_DIR');
		if (!in_array($THEME_DIR, get_theme_names())) {
			$THEME_DIR=get_site_setting('THEME_DIR', 'themes/webtrees/');
		}
		if (!in_array($THEME_DIR, get_theme_names())) {
			$THEME_DIR='themes/webtrees/';
		}
		if (!in_array($THEME_DIR, get_theme_names())) {
			list($THEME_DIR)=get_theme_names();
		}
	}
	define('WT_THEME_DIR', $THEME_DIR);
}

// Remember this setting
$_SESSION['theme_dir']=WT_THEME_DIR;

require WT_ROOT.WT_THEME_DIR.'theme.php';

// Page hit counter - load after theme, as we need theme formatting
if ($SHOW_COUNTER && !$SEARCH_SPIDER) {
	require WT_ROOT.'includes/hitcount.php';
} else {
	$hitCount='';
}

// define constants to be used when setting permissions after creating files/directories
if (substr(PHP_SAPI, 0, 3) == 'cgi') {  // cgi-mode, should only be writable by owner
	define('WT_PERM_EXE',  0755);  // to be used on directories, php files and htaccess files
	define('WT_PERM_FILE', 0644);  // to be used on images, text files, etc
} else { // mod_php mode, should be writable by everyone
	define('WT_PERM_EXE',  0777);
	define('WT_PERM_FILE', 0666);
}

// Lightbox needs custom integration in many places.  Only check for the module once.
define('WT_USE_LIGHTBOX', !$SEARCH_SPIDER && $MULTI_MEDIA && is_dir(WT_ROOT.'modules/lightbox'));
