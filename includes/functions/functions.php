<?php
/**
 * Core Functions
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
 *
 * Modifications Copyright (c) 2010 Greg Roach
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
 * @package webtrees
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_FUNCTIONS_PHP', '');

require_once WT_ROOT.'includes/classes/class_media.php';
require_once WT_ROOT.'includes/functions/functions_utf-8.php';

////////////////////////////////////////////////////////////////////////////////
// Extract, sanitise and validate FORM (POST), URL (GET) and COOKIE variables.
//
// Request variables should ALWAYS be accessed through these functions, to
// protect against XSS (cross-site-scripting) attacks.
//
// $var     - The variable to check
// $regex   - Regular expression to validate the variable (or an array of
//            regular expressions).  A number of common regexes are defined in
//            session.php as constants WT_REGEX_*.  If no value is specified,
//            the default blocks all characters that could introduce scripts.
// $default - A value to use if $var is undefined or invalid.
//
// You should always know whether your variables are coming from GET or POST,
// and always use the correct function.
//
// NOTE: when using checkboxes, $var is either set (checked) or unset (not
// checked).  This lets us use the syntax safe_GET('my_checkbox', 'yes', 'no')
//
// NOTE: when using listboxes, $regex can be an array of valid values.  For
// example, you can use safe_POST('lang', array_keys($pgv_language), WT_LOCALE)
// to validate against a list of valid languages and supply a sensible default.
////////////////////////////////////////////////////////////////////////////////

function safe_POST($var, $regex=WT_REGEX_NOSCRIPT, $default=null) {
	return safe_REQUEST($_POST, $var, $regex, $default);
}
function safe_GET($var, $regex=WT_REGEX_NOSCRIPT, $default=null) {
	return safe_REQUEST($_GET, $var, $regex, $default);
}
function safe_COOKIE($var, $regex=WT_REGEX_NOSCRIPT, $default=null) {
	return safe_REQUEST($_COOKIE, $var, $regex, $default);
}

function safe_GET_integer($var, $min, $max, $default) {
	$num=safe_GET($var, WT_REGEX_INTEGER, $default);
	$num=max($num, $min);
	$num=min($num, $max);
	return (int)$num;
}
function safe_POST_integer($var, $min, $max, $default) {
	$num=safe_POST($var, WT_REGEX_INTEGER, $default);
	$num=max($num, $min);
	$num=min($num, $max);
	return (int)$num;
}

function safe_GET_bool($var, $true='(y|Y|1|yes|YES|Yes|true|TRUE|True|on)') {
	return !is_null(safe_GET($var, $true));
}
function safe_POST_bool($var, $true='(y|Y|1|yes|YES|Yes|true|TRUE|True|on)') {
	return !is_null(safe_POST($var, $true));
}

function safe_GET_xref($var, $default=null) {
	return safe_GET($var, WT_REGEX_XREF, $default);
}
function safe_POST_xref($var, $default=null) {
	return safe_POST($var, WT_REGEX_XREF, $default);
}

function safe_REQUEST($arr, $var, $regex=WT_REGEX_NOSCRIPT, $default=null) {
	if (is_array($regex)) {
		$regex='(?:'.join('|', $regex).')';
	}
	if (array_key_exists($var, $arr) && preg_match_recursive('~^'.addcslashes($regex, '~').'$~', $arr[$var])) {
		return trim_recursive($arr[$var]);
	} else {
		return $default;
	}
}

function encode_url($url, $entities=true) {
	$url = decode_url($url, $entities); // Make sure we don't do any double conversions
	$url = str_replace(array(' ', '+', '@#', '"', "'"), array('%20', '%2b', '@%23', '%22', '%27'), $url);
	if ($entities) {
		$url = htmlspecialchars($url, ENT_COMPAT, 'UTF-8');
	}
	return $url;
}

function decode_url($url, $entities=true) {
	if ($entities) {
		$url = html_entity_decode($url, ENT_COMPAT, 'UTF-8');
	}
	$url = rawurldecode($url); // GEDCOM names can legitimately contain " " and "+"
	return $url;
}

function preg_match_recursive($regex, $var) {
	if (is_scalar($var)) {
		return preg_match($regex, $var);
	} else {
		if (is_array($var)) {
			foreach ($var as $k=>$v) {
				if (!preg_match_recursive($regex, $v)) {
					return false;
				}
			}
			return true;
		} else {
			// Neither scalar nor array.  Object?
			return false;
		}
	}
}

function trim_recursive($var) {
	if (is_scalar($var)) {
		return trim($var);
	} else {
		if (is_array($var)) {
			foreach ($var as $k=>$v) {
				$var[$k]=trim_recursive($v);
			}
			return $var;
		} else {
			// Neither scalar nor array.  Object?
			return $var;
		}
	}
}

// Convert a file upload PHP error code into user-friendly text
function file_upload_error_text($error_code) {
	switch ($error_code) {
	case UPLOAD_ERR_OK:
		return i18n::translate('File successfully uploaded');
	case UPLOAD_ERR_INI_SIZE:
	case UPLOAD_ERR_FORM_SIZE:
		return i18n::translate('Uploaded file exceeds the allowed size');
	case UPLOAD_ERR_PARTIAL:
		return i18n::translate('File was only partially uploaded, please try again');
	case UPLOAD_ERR_NO_FILE:
		return i18n::translate('No file was received. Please upload again.');
	case UPLOAD_ERR_NO_TMP_DIR:
		return i18n::translate('Missing PHP temporary directory');
	case UPLOAD_ERR_CANT_WRITE:
		return i18n::translate('PHP failed to write to disk');
	case UPLOAD_ERR_EXTENSION:
		return i18n::translate('PHP blocked file by extension');
	}
}

function load_gedcom_settings($ged_id=WT_GED_ID) {
	// Load the configuration settings into global scope
	// TODO: some of these are used infrequently - just load them when we need them
	global $ABBREVIATE_CHART_LABELS;      $ABBREVIATE_CHART_LABELS      =get_gedcom_setting($ged_id, 'ABBREVIATE_CHART_LABELS');
	global $ADVANCED_NAME_FACTS;          $ADVANCED_NAME_FACTS          =get_gedcom_setting($ged_id, 'ADVANCED_NAME_FACTS');
	global $ADVANCED_PLAC_FACTS;          $ADVANCED_PLAC_FACTS          =get_gedcom_setting($ged_id, 'ADVANCED_PLAC_FACTS');
	global $ALLOW_EDIT_GEDCOM;            $ALLOW_EDIT_GEDCOM            =get_gedcom_setting($ged_id, 'ALLOW_EDIT_GEDCOM');
	global $ALLOW_THEME_DROPDOWN;         $ALLOW_THEME_DROPDOWN         =get_gedcom_setting($ged_id, 'ALLOW_THEME_DROPDOWN');
	global $CALENDAR_FORMAT;              $CALENDAR_FORMAT              =get_gedcom_setting($ged_id, 'CALENDAR_FORMAT');
	global $CHART_BOX_TAGS;               $CHART_BOX_TAGS               =get_gedcom_setting($ged_id, 'CHART_BOX_TAGS');
	global $CHECK_MARRIAGE_RELATIONS;     $CHECK_MARRIAGE_RELATIONS     =get_gedcom_setting($ged_id, 'CHECK_MARRIAGE_RELATIONS');
	global $CONTACT_USER_ID;              $CONTACT_USER_ID              =get_gedcom_setting($ged_id, 'CONTACT_USER_ID');
	global $DEFAULT_PEDIGREE_GENERATIONS; $DEFAULT_PEDIGREE_GENERATIONS =get_gedcom_setting($ged_id, 'DEFAULT_PEDIGREE_GENERATIONS');
	global $DISPLAY_JEWISH_GERESHAYIM;    $DISPLAY_JEWISH_GERESHAYIM    =get_gedcom_setting($ged_id, 'DISPLAY_JEWISH_GERESHAYIM');
	global $DISPLAY_JEWISH_THOUSANDS;     $DISPLAY_JEWISH_THOUSANDS     =get_gedcom_setting($ged_id, 'DISPLAY_JEWISH_THOUSANDS');
	global $ENABLE_AUTOCOMPLETE;          $ENABLE_AUTOCOMPLETE          =get_gedcom_setting($ged_id, 'ENABLE_AUTOCOMPLETE');
	global $EXPAND_NOTES;                 $EXPAND_NOTES                 =get_gedcom_setting($ged_id, 'EXPAND_NOTES');
	global $EXPAND_RELATIVES_EVENTS;      $EXPAND_RELATIVES_EVENTS      =get_gedcom_setting($ged_id, 'EXPAND_RELATIVES_EVENTS');
	global $EXPAND_SOURCES;               $EXPAND_SOURCES               =get_gedcom_setting($ged_id, 'EXPAND_SOURCES');
	global $FAM_ID_PREFIX;                $FAM_ID_PREFIX                =get_gedcom_setting($ged_id, 'FAM_ID_PREFIX');
	global $FULL_SOURCES;                 $FULL_SOURCES                 =get_gedcom_setting($ged_id, 'FULL_SOURCES');
	global $GEDCOM_ID_PREFIX;             $GEDCOM_ID_PREFIX             =get_gedcom_setting($ged_id, 'GEDCOM_ID_PREFIX');
	global $GENERATE_UIDS;                $GENERATE_UIDS                =get_gedcom_setting($ged_id, 'GENERATE_UIDS');
	global $HIDE_GEDCOM_ERRORS;           $HIDE_GEDCOM_ERRORS           =get_gedcom_setting($ged_id, 'HIDE_GEDCOM_ERRORS');
	global $HIDE_LIVE_PEOPLE;             $HIDE_LIVE_PEOPLE             =get_gedcom_setting($ged_id, 'HIDE_LIVE_PEOPLE');
	global $KEEP_ALIVE_YEARS_BIRTH;       $KEEP_ALIVE_YEARS_BIRTH       =get_gedcom_setting($ged_id, 'KEEP_ALIVE_YEARS_BIRTH');
	global $KEEP_ALIVE_YEARS_DEATH;       $KEEP_ALIVE_YEARS_DEATH       =get_gedcom_setting($ged_id, 'KEEP_ALIVE_YEARS_DEATH');
	global $LANGUAGE;                     $LANGUAGE                     =get_gedcom_setting($ged_id, 'LANGUAGE');
	global $LINK_ICONS;                   $LINK_ICONS                   =get_gedcom_setting($ged_id, 'LINK_ICONS');
	global $MAX_ALIVE_AGE;                $MAX_ALIVE_AGE                =get_gedcom_setting($ged_id, 'MAX_ALIVE_AGE');
	global $MAX_DESCENDANCY_GENERATIONS;  $MAX_DESCENDANCY_GENERATIONS  =get_gedcom_setting($ged_id, 'MAX_DESCENDANCY_GENERATIONS');
	global $MAX_PEDIGREE_GENERATIONS;     $MAX_PEDIGREE_GENERATIONS     =get_gedcom_setting($ged_id, 'MAX_PEDIGREE_GENERATIONS');
	global $MAX_RELATION_PATH_LENGTH;     $MAX_RELATION_PATH_LENGTH     =get_gedcom_setting($ged_id, 'MAX_RELATION_PATH_LENGTH');
	global $MEDIA_DIRECTORY;              $MEDIA_DIRECTORY              =get_gedcom_setting($ged_id, 'MEDIA_DIRECTORY');
	global $MEDIA_DIRECTORY_LEVELS;       $MEDIA_DIRECTORY_LEVELS       =get_gedcom_setting($ged_id, 'MEDIA_DIRECTORY_LEVELS');
	global $MEDIA_EXTERNAL;               $MEDIA_EXTERNAL               =get_gedcom_setting($ged_id, 'MEDIA_EXTERNAL');
	global $MEDIA_FIREWALL_ROOTDIR;       $MEDIA_FIREWALL_ROOTDIR       =get_gedcom_setting($ged_id, 'MEDIA_FIREWALL_ROOTDIR', get_site_setting('INDEX_DIRECTORY'));
	global $MEDIA_FIREWALL_THUMBS;        $MEDIA_FIREWALL_THUMBS        =get_gedcom_setting($ged_id, 'MEDIA_FIREWALL_THUMBS');
	global $MEDIA_ID_PREFIX;              $MEDIA_ID_PREFIX              =get_gedcom_setting($ged_id, 'MEDIA_ID_PREFIX');
	global $MULTI_MEDIA;                  $MULTI_MEDIA                  =get_gedcom_setting($ged_id, 'MULTI_MEDIA');
	global $NOTE_ID_PREFIX;               $NOTE_ID_PREFIX               =get_gedcom_setting($ged_id, 'NOTE_ID_PREFIX');
	global $NO_UPDATE_CHAN;               $NO_UPDATE_CHAN               =get_gedcom_setting($ged_id, 'NO_UPDATE_CHAN');
	global $PAGE_AFTER_LOGIN;             $PAGE_AFTER_LOGIN             =get_gedcom_setting($ged_id, 'PAGE_AFTER_LOGIN');
	global $PEDIGREE_FULL_DETAILS;        $PEDIGREE_FULL_DETAILS        =get_gedcom_setting($ged_id, 'PEDIGREE_FULL_DETAILS');
	global $PEDIGREE_LAYOUT;              $PEDIGREE_LAYOUT              =get_gedcom_setting($ged_id, 'PEDIGREE_LAYOUT');
	global $PEDIGREE_ROOT_ID;             $PEDIGREE_ROOT_ID             =get_gedcom_setting($ged_id, 'PEDIGREE_ROOT_ID');
	global $PEDIGREE_SHOW_GENDER;         $PEDIGREE_SHOW_GENDER         =get_gedcom_setting($ged_id, 'PEDIGREE_SHOW_GENDER');
	global $POSTAL_CODE;                  $POSTAL_CODE                  =get_gedcom_setting($ged_id, 'POSTAL_CODE');
	global $PREFER_LEVEL2_SOURCES;        $PREFER_LEVEL2_SOURCES        =get_gedcom_setting($ged_id, 'PREFER_LEVEL2_SOURCES');
	global $QUICK_REQUIRED_FACTS;         $QUICK_REQUIRED_FACTS         =get_gedcom_setting($ged_id, 'QUICK_REQUIRED_FACTS');
	global $QUICK_REQUIRED_FAMFACTS;      $QUICK_REQUIRED_FAMFACTS      =get_gedcom_setting($ged_id, 'QUICK_REQUIRED_FAMFACTS');
	global $REPO_ID_PREFIX;               $REPO_ID_PREFIX               =get_gedcom_setting($ged_id, 'REPO_ID_PREFIX');
	global $REQUIRE_AUTHENTICATION;       $REQUIRE_AUTHENTICATION       =get_gedcom_setting($ged_id, 'REQUIRE_AUTHENTICATION');
	global $SAVE_WATERMARK_IMAGE;         $SAVE_WATERMARK_IMAGE         =get_gedcom_setting($ged_id, 'SAVE_WATERMARK_IMAGE');
	global $SAVE_WATERMARK_THUMB;         $SAVE_WATERMARK_THUMB         =get_gedcom_setting($ged_id, 'SAVE_WATERMARK_THUMB');
	global $SHOW_AGE_DIFF;                $SHOW_AGE_DIFF                =get_gedcom_setting($ged_id, 'SHOW_AGE_DIFF');
	global $SHOW_COUNTER;                 $SHOW_COUNTER                 =get_gedcom_setting($ged_id, 'SHOW_COUNTER');
	global $SHOW_DEAD_PEOPLE;             $SHOW_DEAD_PEOPLE             =get_gedcom_setting($ged_id, 'SHOW_DEAD_PEOPLE');
	global $SHOW_EMPTY_BOXES;             $SHOW_EMPTY_BOXES             =get_gedcom_setting($ged_id, 'SHOW_EMPTY_BOXES');
	global $SHOW_FACT_ICONS;              $SHOW_FACT_ICONS              =get_gedcom_setting($ged_id, 'SHOW_FACT_ICONS');
	global $SHOW_GEDCOM_RECORD;           $SHOW_GEDCOM_RECORD           =get_gedcom_setting($ged_id, 'SHOW_GEDCOM_RECORD');
	global $SHOW_HIGHLIGHT_IMAGES;        $SHOW_HIGHLIGHT_IMAGES        =get_gedcom_setting($ged_id, 'SHOW_HIGHLIGHT_IMAGES');
	global $SHOW_LAST_CHANGE;             $SHOW_LAST_CHANGE             =get_gedcom_setting($ged_id, 'SHOW_LAST_CHANGE');
	global $SHOW_LDS_AT_GLANCE;           $SHOW_LDS_AT_GLANCE           =get_gedcom_setting($ged_id, 'SHOW_LDS_AT_GLANCE');
	global $SHOW_LEVEL2_NOTES;            $SHOW_LEVEL2_NOTES            =get_gedcom_setting($ged_id, 'SHOW_LEVEL2_NOTES');
	global $SHOW_LIST_PLACES;             $SHOW_LIST_PLACES             =get_gedcom_setting($ged_id, 'SHOW_LIST_PLACES');
	global $SHOW_LIVING_NAMES;            $SHOW_LIVING_NAMES            =get_gedcom_setting($ged_id, 'SHOW_LIVING_NAMES');
	global $SHOW_MARRIED_NAMES;           $SHOW_MARRIED_NAMES           =get_gedcom_setting($ged_id, 'SHOW_MARRIED_NAMES');
	global $SHOW_MEDIA_DOWNLOAD;          $SHOW_MEDIA_DOWNLOAD          =get_gedcom_setting($ged_id, 'SHOW_MEDIA_DOWNLOAD');
	global $SHOW_MEDIA_FILENAME;          $SHOW_MEDIA_FILENAME          =get_gedcom_setting($ged_id, 'SHOW_MEDIA_FILENAME');
	global $SHOW_MULTISITE_SEARCH;        $SHOW_MULTISITE_SEARCH        =get_gedcom_setting($ged_id, 'SHOW_MULTISITE_SEARCH');
	global $SHOW_NO_WATERMARK;            $SHOW_NO_WATERMARK            =get_gedcom_setting($ged_id, 'SHOW_NO_WATERMARK');
	global $SHOW_PARENTS_AGE;             $SHOW_PARENTS_AGE             =get_gedcom_setting($ged_id, 'SHOW_PARENTS_AGE');
	global $SHOW_PEDIGREE_PLACES;         $SHOW_PEDIGREE_PLACES         =get_gedcom_setting($ged_id, 'SHOW_PEDIGREE_PLACES');
	global $SHOW_PRIVATE_RELATIONSHIPS;   $SHOW_PRIVATE_RELATIONSHIPS   =get_gedcom_setting($ged_id, 'SHOW_PRIVATE_RELATIONSHIPS');
	global $SHOW_REGISTER_CAUTION;        $SHOW_REGISTER_CAUTION        =get_gedcom_setting($ged_id, 'SHOW_REGISTER_CAUTION');
	global $SHOW_RELATIVES_EVENTS;        $SHOW_RELATIVES_EVENTS        =get_gedcom_setting($ged_id, 'SHOW_RELATIVES_EVENTS');
	global $SHOW_SPIDER_TAGLINE;          $SHOW_SPIDER_TAGLINE          =get_gedcom_setting($ged_id, 'SHOW_SPIDER_TAGLINE');
	global $SHOW_STATS;                   $SHOW_STATS                   =get_gedcom_setting($ged_id, 'SHOW_STATS');
	global $SOURCE_ID_PREFIX;             $SOURCE_ID_PREFIX             =get_gedcom_setting($ged_id, 'SOURCE_ID_PREFIX');
	global $SPLIT_PLACES;                 $SPLIT_PLACES                 =get_gedcom_setting($ged_id, 'SPLIT_PLACES');
	global $SURNAME_LIST_STYLE;           $SURNAME_LIST_STYLE           =get_gedcom_setting($ged_id, 'SURNAME_LIST_STYLE');
	global $THEME_DIR;                    $THEME_DIR                    =get_gedcom_setting($ged_id, 'THEME_DIR');
	global $THUMBNAIL_WIDTH;              $THUMBNAIL_WIDTH              =get_gedcom_setting($ged_id, 'THUMBNAIL_WIDTH');
	global $UNDERLINE_NAME_QUOTES;        $UNDERLINE_NAME_QUOTES        =get_gedcom_setting($ged_id, 'UNDERLINE_NAME_QUOTES');
	global $USE_GEONAMES;                 $USE_GEONAMES                 =get_gedcom_setting($ged_id, 'USE_GEONAMES');
	global $USE_MEDIA_FIREWALL;           $USE_MEDIA_FIREWALL           =get_gedcom_setting($ged_id, 'USE_MEDIA_FIREWALL');
	global $USE_MEDIA_VIEWER;             $USE_MEDIA_VIEWER             =get_gedcom_setting($ged_id, 'USE_MEDIA_VIEWER');
	global $USE_RELATIONSHIP_PRIVACY;     $USE_RELATIONSHIP_PRIVACY     =get_gedcom_setting($ged_id, 'USE_RELATIONSHIP_PRIVACY');
	global $USE_RIN;                      $USE_RIN                      =get_gedcom_setting($ged_id, 'USE_RIN');
	global $USE_SILHOUETTE;               $USE_SILHOUETTE               =get_gedcom_setting($ged_id, 'USE_SILHOUETTE');
	global $USE_THUMBS_MAIN;              $USE_THUMBS_MAIN              =get_gedcom_setting($ged_id, 'USE_THUMBS_MAIN');
	global $WATERMARK_THUMB;              $WATERMARK_THUMB              =get_gedcom_setting($ged_id, 'WATERMARK_THUMB');
	global $WEBMASTER_USER_ID;            $WEBMASTER_USER_ID            =get_gedcom_setting($ged_id, 'WEBMASTER_USER_ID');
	global $WEBTREES_EMAIL;               $WEBTREES_EMAIL               =get_gedcom_setting($ged_id, 'WEBTREES_EMAIL');
	global $WELCOME_TEXT_AUTH_MODE;       $WELCOME_TEXT_AUTH_MODE       =get_gedcom_setting($ged_id, 'WELCOME_TEXT_AUTH_MODE');
	global $WELCOME_TEXT_CUST_HEAD;       $WELCOME_TEXT_CUST_HEAD       =get_gedcom_setting($ged_id, 'WELCOME_TEXT_CUST_HEAD');
	global $WORD_WRAPPED_NOTES;           $WORD_WRAPPED_NOTES           =get_gedcom_setting($ged_id, 'WORD_WRAPPED_NOTES');
	global $ZOOM_BOXES;                   $ZOOM_BOXES                   =get_gedcom_setting($ged_id, 'ZOOM_BOXES');

	global $person_privacy; $person_privacy=array();
	global $person_facts;   $person_facts  =array();
	global $global_facts;   $global_facts  =array();

	$rows=WT_DB::prepare(
		"SELECT SQL_CACHE xref, tag_type, CASE resn WHEN 'none' THEN ? WHEN 'privacy' THEN ? WHEN 'confidential' THEN ? WHEN 'hidden' THEN ? END AS resn FROM `##default_resn` WHERE gedcom_id=?"
	)->execute(array(WT_PRIV_PUBLIC, WT_PRIV_USER, WT_PRIV_NONE, WT_PRIV_HIDE, $ged_id))->fetchAll();

	foreach ($rows as $row) {
		if ($row->xref!==null) {
			if ($row->tag_type!==null) {
				$person_facts[$row->xref][$row->tag_type]=(int)$row->resn;
			} else {
				$person_privacy[$row->xref]=(int)$row->resn;
			}
		} else {
			$global_facts[$row->tag_type]=(int)$row->resn;
		}
	}
}

/**
 * Webtrees Error Handling function
 *
 * This function will be called by PHP whenever an error occurs.  The error handling
 * is set in the session.php
 * @see http://us2.php.net/manual/en/function.set-error-handler.php
 */
function wt_error_handler($errno, $errstr, $errfile, $errline) {
	if ((error_reporting() > 0)&&($errno<2048)) {
		if (WT_ERROR_LEVEL==0) {
			return;
		}
		if (stristr($errstr, "by reference")==true) {
			return;
		}
		$fmt_msg="\n<br />ERROR {$errno}: {$errstr}<br />\n";
		$log_msg="ERROR {$errno}: {$errstr};";
		// Although debug_backtrace should always exist in PHP5, without this check, PHP sometimes crashes.
		// Possibly calling it generates an error, which causes infinite recursion??
		if ($errno<16 && function_exists("debug_backtrace") && strstr($errstr, "headers already sent by")===false) {
			$backtrace=debug_backtrace();
			$num=count($backtrace);
			if (WT_ERROR_LEVEL==1) {
				$num=1;
			}
			for ($i=0; $i<$num; $i++) {
				if ($i==0) {
					$fmt_msg.="0 Error occurred on ";
					$log_msg.="\n0 Error occurred on ";
				} else {
					$fmt_msg.="{$i} called from ";
					$log_msg.="\n{$i} called from ";
				}
				if (isset($backtrace[$i]["line"]) && isset($backtrace[$i]["file"])) {
					$fmt_msg.="line <b>{$backtrace[$i]['line']}</b> of file <b>".basename($backtrace[$i]['file'])."</b>";
					$log_msg.="line {$backtrace[$i]['line']} of file ".basename($backtrace[$i]['file']);
				}
				if ($i<$num-1) {
					$fmt_msg.=" in function <b>".$backtrace[$i+1]['function']."</b>";
					$log_msg.=" in function ".$backtrace[$i+1]['function'];
				}
				$fmt_msg.="<br />\n";
			}
		}
		echo $fmt_msg;
		if (function_exists('AddToLog')) {
			AddToLog($log_msg, 'error');
		}
		if ($errno==1) {
			die();
		}
	}
	return false;
}

// ************************************************* START OF GEDCOM FUNCTIONS ********************************* //

/**
 * Get first tag in GEDCOM sub-record
 *
 * This routine uses function get_sub_record to retrieve the specified sub-record
 * and then returns the first tag.
 *
 */
function get_first_tag($level, $tag, $gedrec, $num=1) {
	$temp = get_sub_record($level, $level." ".$tag, $gedrec, $num)."\n";
	$temp = str_replace("\r\n", "\n", $temp);
	$length = strpos($temp, "\n");
	if ($length===false) {
		$length = strlen($temp);
	}
	return substr($temp, 2, $length-2);
}

/**
 * get a gedcom subrecord
 *
 * searches a gedcom record and returns a subrecord of it.  A subrecord is defined starting at a
 * line with level N and all subsequent lines greater than N until the next N level is reached.
 * For example, the following is a BIRT subrecord:
 * <code>1 BIRT
 * 2 DATE 1 JAN 1900
 * 2 PLAC Phoenix, Maricopa, Arizona</code>
 * The following example is the DATE subrecord of the above BIRT subrecord:
 * <code>2 DATE 1 JAN 1900</code>
 * @author John Finlay (yalnifj)
 * @author Roland Dalmulder (roland-d)
 * @param int $level the N level of the subrecord to get
 * @param string $tag a gedcom tag or string to search for in the record (ie 1 BIRT or 2 DATE)
 * @param string $gedrec the parent gedcom record to search in
 * @param int $num this allows you to specify which matching <var>$tag</var> to get.  Oftentimes a
 * gedcom record will have more that 1 of the same type of subrecord.  An individual may have
 * multiple events for example.  Passing $num=1 would get the first 1.  Passing $num=2 would get the
 * second one, etc.
 * @return string the subrecord that was found or an empty string "" if not found.
 */
function get_sub_record($level, $tag, $gedrec, $num=1) {
	if (empty($gedrec)) {
		return "";
	}
	// -- adding \n before and after gedrec
	$gedrec = "\n".$gedrec."\n";
	$pos1=0;
	$subrec = "";
	$tag = trim($tag);
	$searchTarget = "~[\r\n]".$tag."[\s]~";
	$ct = preg_match_all($searchTarget, $gedrec, $match, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
	if ($ct==0) {
		$tag = preg_replace('/(\w+)/', "_$1", $tag);
		$ct = preg_match_all($searchTarget, $gedrec, $match, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
		if ($ct==0) {
			return "";
		}
	}
	if ($ct<$num) {
		return "";
	}
	$pos1 = $match[$num-1][0][1];
	$pos2 = strpos($gedrec, "\n$level", $pos1+1);
	if (!$pos2) {
		$pos2 = strpos($gedrec, "\n1", $pos1+1);
	}
	if (!$pos2) {
		$pos2 = strpos($gedrec, "\nWT_", $pos1+1); // WT_SPOUSE, WT_FAMILY_ID ...
	}
	if (!$pos2) {
		return ltrim(substr($gedrec, $pos1));
	}
	$subrec = substr($gedrec, $pos1, $pos2-$pos1);
	return ltrim($subrec);
}

/**
 * find all of the level 1 subrecords of the given record
 * @param string $gedrec the gedcom record to get the subrecords from
 * @param string $ignore a list of tags to ignore
 * @param boolean $families whether to include any records from the family
 * @param boolean $sort whether or not to sort the record by date
 * @param boolean $ApplyPriv whether to apply privacy right now or later
 * @return array an array of the raw subrecords to return
 */
function get_all_subrecords($gedrec, $ignore="", $families=true, $ApplyPriv=true) {
	global $GEDCOM;
	$ged_id=get_id_from_gedcom($GEDCOM);

	$repeats = array();

	$id = "";
	$gt = preg_match('/0 @('.WT_REGEX_XREF.')@/', $gedrec, $gmatch);
	if ($gt > 0) {
		$id = $gmatch[1];
	}

	$hasResn = strstr($gedrec, " RESN ");
	$prev_tags = array();
	$ct = preg_match_all('/\n1 ('.WT_REGEX_TAG.')(.*)/', $gedrec, $match, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
	for ($i=0; $i<$ct; $i++) {
		$fact = trim($match[$i][1][0]);
		$pos1 = $match[$i][0][1];
		if ($i<$ct-1) {
			$pos2 = $match[$i+1][0][1];
		} else {
			$pos2 = strlen($gedrec);
		}
		if (empty($ignore) || strpos($ignore, $fact)===false) {
			$subrec = substr($gedrec, $pos1, $pos2-$pos1);
			if (!$ApplyPriv || canDisplayFact($id, $ged_id, $subrec)) {
				if (isset($prev_tags[$fact])) {
					$prev_tags[$fact]++;
				} else {
					$prev_tags[$fact] = 1;
				}
				$repeats[] = trim($subrec)."\n";
			}
		}
	}

	//-- look for any records in FAMS records
	if ($families) {
		$ft = preg_match_all('/\n1 FAMS @('.WT_REGEX_XREF.')@/', $gedrec, $fmatch, PREG_SET_ORDER);
		for ($f=0; $f<$ft; $f++) {
			$famid = $fmatch[$f][1];
			$famrec = find_family_record($fmatch[$f][1], $ged_id);
			$parents = find_parents_in_record($famrec);
			if ($id==$parents["HUSB"]) {
				$spid = $parents["WIFE"];
			} else {
				$spid = $parents["HUSB"];
			}
			$prev_tags = array();
			$ct = preg_match_all('/\n1 ('.WT_REGEX_TAG.')(.*)/', $famrec, $match, PREG_SET_ORDER);
			for ($i=0; $i<$ct; $i++) {
				$fact = trim($match[$i][1]);
				if (empty($ignore) || strpos($ignore, $fact)===false) {
					$subrec = get_sub_record(1, "1 $fact", $famrec, $prev_tags[$fact]);
					$subrec .= "\n2 _WTS @$spid@\n2 _WTFS @$famid@\n";
					if (!$ApplyPriv || canDisplayFact($id, $ged_id, $subrec)) {
						if (isset($prev_tags[$fact])) {
							$prev_tags[$fact]++;
						} else {
							$prev_tags[$fact] = 1;
						}
						$repeats[] = trim($subrec)."\n";
					}
				}
			}
		}
	}

	return $repeats;
}

/**
 * get gedcom tag value
 *
 * returns the value of a gedcom tag from the given gedcom record
 * @param string $tag The tag to find, use : to delineate subtags
 * @param int $level The gedcom line level of the first tag to find, setting level to 0 will cause it to use 1+ the level of the incoming record
 * @param string $gedrec The gedcom record to get the value from
 * @param int $truncate Should the value be truncated to a certain number of characters
 * @param boolean $convert Should data like dates be converted using the configuration settings
 * @return string
 */
function get_gedcom_value($tag, $level, $gedrec, $truncate='', $convert=true) {
	global $SHOW_PEDIGREE_PLACES, $GEDCOM;
	$ged_id=get_id_from_gedcom($GEDCOM);

	if (empty($gedrec)) {
		return "";
	}
	$tags = explode(':', $tag);
	$origlevel = $level;
	if ($level==0) {
		$level = $gedrec{0} + 1;
	}

	$subrec = $gedrec;
	foreach ($tags as $indexval => $t) {
		$lastsubrec = $subrec;
		$subrec = get_sub_record($level, "$level $t", $subrec);
		if (empty($subrec) && $origlevel==0) {
			$level--;
			$subrec = get_sub_record($level, "$level $t", $lastsubrec);
		}
		if (empty($subrec)) {
			if ($t=="TITL") {
				$subrec = get_sub_record($level, "$level ABBR", $lastsubrec);
				if (!empty($subrec)) {
					$t = "ABBR";
				}
			}
			if (empty($subrec)) {
				if ($level>0) {
					$level--;
				}
				$subrec = get_sub_record($level, "@ $t", $gedrec);
				if (empty($subrec)) {
					return;
				}
			}
		}
		$level++;
	}
	$level--;
	$ct = preg_match("/$level $t(.*)/", $subrec, $match);
	if ($ct==0) {
		$ct = preg_match("/$level @.+@ (.+)/", $subrec, $match);
	}
	if ($ct==0) {
		$ct = preg_match("/@ $t (.+)/", $subrec, $match);
	}
	if ($ct > 0) {
		$value = trim($match[1]);
		$ct = preg_match("/@(.*)@/", $value, $match);
		if (($ct > 0 ) && ($t!="DATE")) {
			$oldsub = $subrec;
			switch ($t) {
			case 'HUSB':
			case 'WIFE':
			case 'CHIL':
				$subrec = find_person_record($match[1], $ged_id);
				break;
			case 'FAMC':
			case 'FAMS':
				$subrec = find_family_record($match[1], $ged_id);
				break;
			case 'SOUR':
				$subrec = find_source_record($match[1], $ged_id);
				break;
			case 'REPO':
				$subrec = find_other_record($match[1], $ged_id);
				break;
			default:
				$subrec = find_gedcom_record($match[1], $ged_id);
				break;
			}
			if ($subrec) {
				$value=$match[1];
				$ct = preg_match("/0 @$match[1]@ $t (.+)/", $subrec, $match);
				if ($ct>0) {
					$value = $match[1];
					$level = 0;
				} else
					$subrec = $oldsub;
			} else
				//-- set the value to the id without the @
				$value = $match[1];
		}
		if ($level!=0 || $t!="NOTE") {
			$value .= get_cont($level+1, $subrec);
		}
		$value = preg_replace("'\n'", "", $value);
		$value = preg_replace("'<br />'", "\n", $value);
		$value = trim($value);
		//-- if it is a date value then convert the date
		if ($convert && $t=="DATE") {
			$g = new GedcomDate($value);
			$value = $g->Display();
			if (!empty($truncate)) {
				if (utf8_strlen($value)>$truncate) {
					$value = preg_replace("/\(.+\)/", "", $value);
					//if (utf8_strlen($value)>$truncate) {
						//$value = preg_replace_callback("/([a-zśź]+)/ui", create_function('$matches', 'return utf8_substr($matches[1], 0, 3);'), $value);
					//}
				}
			}
		} else
			//-- if it is a place value then apply the pedigree place limit
			if ($convert && $t=="PLAC") {
				if ($SHOW_PEDIGREE_PLACES>0) {
					$plevels = explode(',', $value);
					$value = "";
					for ($plevel=0; $plevel<$SHOW_PEDIGREE_PLACES; $plevel++) {
						if (!empty($plevels[$plevel])) {
							if ($plevel>0) {
								$value .= ", ";
							}
							$value .= trim($plevels[$plevel]);
						}
					}
				}
				if (!empty($truncate)) {
					if (strlen($value)>$truncate) {
						$plevels = explode(',', $value);
						$value = "";
						for ($plevel=0; $plevel<count($plevels); $plevel++) {
							if (!empty($plevels[$plevel])) {
								if (strlen($plevels[$plevel])+strlen($value)+3 < $truncate) {
									if ($plevel>0) {
										$value .= ", ";
									}
									$value .= trim($plevels[$plevel]);
								} else
									break;
							}
						}
					}
				}
			} else
				if ($convert && $t=="SEX") {
					if ($value=="M") {
						$value = utf8_substr(i18n::translate('Male'), 0, 1);
					} elseif ($value=="F") {
						$value = utf8_substr(i18n::translate('Female'), 0, 1);
					} else {
						$value = utf8_substr(i18n::translate_c('unknown gender', 'Unknown'), 0, 1);
					}
				} else {
					if (!empty($truncate)) {
						if (strlen($value)>$truncate) {
							$plevels = explode(' ', $value);
							$value = "";
							for ($plevel=0; $plevel<count($plevels); $plevel++) {
								if (!empty($plevels[$plevel])) {
									if (strlen($plevels[$plevel])+strlen($value)+3 < $truncate) {
										if ($plevel>0) {
											$value .= " ";
										}
										$value .= trim($plevels[$plevel]);
									} else
										break;
								}
							}
						}
					}
				}
		return $value;
	}
	return "";
}

/**
 * create CONT lines
 *
 * Break input GEDCOM subrecord into pieces not more than 255 chars long,
 * with CONC and CONT lines as needed.  Routine also pays attention to the
 * word wrapped Notes option.  Routine also avoids splitting UTF-8 encoded
 * characters between lines.
 *
 * @param string $newline Input GEDCOM subrecord to be worked on
 * @return string $newged Output string with all necessary CONC and CONT lines
 */
function breakConts($newline) {
	global $WORD_WRAPPED_NOTES;

	// Determine level number of CONC and CONT lines
	$level = substr($newline, 0, 1);
	$tag = substr($newline, 1, 6);
	if ($tag!=" CONC " && $tag!=" CONT ") {
		$level ++;
	}

	$newged = "";
	$newlines = preg_split("/\r?\n/", rtrim(stripLRMRLM($newline)));
	for ($k=0; $k<count($newlines); $k++) {
		if ($k>0) {
			$newlines[$k] = "{$level} CONT ".$newlines[$k];
		}
		$newged .= trim($newlines[$k])."\n";
	}
	return $newged;
}

/**
 * get CONT lines
 *
 * get the N+1 CONT or CONC lines of a gedcom subrecord
 * @param int $nlevel the level of the CONT lines to get
 * @param string $nrec the gedcom subrecord to search in
 * @return string a string with all CONT or CONC lines merged
 */
function get_cont($nlevel, $nrec, $tobr=true) {
	global $WORD_WRAPPED_NOTES;
	$text = "";
	if ($tobr) {
		$newline = "<br />";
	} else {
		$newline = "\n";
	}

	$subrecords = explode("\n", $nrec);
	foreach ($subrecords as $thisSubrecord) {
		if (substr($thisSubrecord, 0, 2)!=$nlevel." ") {
			continue;
		}
		$subrecordType = substr($thisSubrecord, 2, 4);
		if ($subrecordType=="CONT") {
			$text .= $newline;
		}
		if ($subrecordType=="CONC" && $WORD_WRAPPED_NOTES) {
			$text .= " ";
		}
		if ($subrecordType=="CONT" || $subrecordType=="CONC") {
			$text .= rtrim(substr($thisSubrecord, 7));
		}
	}

	return rtrim($text, " ");
}

/**
 * find the parents in a family
 *
 * find and return a two element array containing the parents of the given family record
 * @author John Finlay (yalnifj)
 * @param string $famid the gedcom xref id for the family
 * @return array returns a two element array with indexes HUSB and WIFE for the parent ids
 */
function find_parents($famid) {
	$famrec = find_gedcom_record($famid, WT_GED_ID, WT_USER_CAN_EDIT);
	if (empty($famrec)) {
		return false;
	}
	return find_parents_in_record($famrec);
}

/**
 * find the parents in a family record
 *
 * find and return a two element array containing the parents of the given family record
 * @author John Finlay (yalnifj)
 * @param string $famrec the gedcom record of the family to search in
 * @return array returns a two element array with indexes HUSB and WIFE for the parent ids
 */
function find_parents_in_record($famrec) {
	if (empty($famrec)) {
		return false;
	}
	$parents = array();
	$ct = preg_match('/1 HUSB @('.WT_REGEX_XREF.')@/', $famrec, $match);
	if ($ct>0) {
		$parents["HUSB"]=$match[1];
	} else {
		$parents["HUSB"]="";
	}
	$ct = preg_match('/1 WIFE @('.WT_REGEX_XREF.')@/', $famrec, $match);
	if ($ct>0) {
		$parents["WIFE"]=$match[1];
	} else {
		$parents["WIFE"]="";
	}
	return $parents;
}

/**
 * find the children in a family
 *
 * find and return an array containing the children of the given family record
 * @author John Finlay (yalnifj)
 * @param string $famid the gedcom xref id for the family
 * @param string $me an xref id of a child to ignore, useful when you want to get a person's
 * siblings but do want to include them as well
 * @return array
 */
function find_children($famid, $me='') {
	$famrec = find_gedcom_record($famid, WT_GED_ID, true);
	if (empty($famrec)) {
		return false;
	}
	return find_children_in_record($famrec);
}

/**
 * find the children in a family record
 *
 * find and return an array containing the children of the given family record
 * @author John Finlay (yalnifj)
 * @param string $famrec the gedcom record of the family to search in
 * @param string $me an xref id of a child to ignore, useful when you want to get a person's
 * siblings but do want to include them as well
 * @return array
 */
function find_children_in_record($famrec, $me='') {
	$children = array();
	if (empty($famrec)) {
		return $children;
	}

	$num = preg_match_all('/\n1 CHIL @('.WT_REGEX_XREF.')@/', $famrec, $match, PREG_SET_ORDER);
	for ($i=0; $i<$num; $i++) {
		$child = trim($match[$i][1]);
		if ($child!=$me) {
			$children[] = $child;
		}
	}
	return $children;
}

/**
 * find all child family ids
 *
 * searches an individual gedcom record and returns an array of the FAMC ids where this person is a
 * child in the family, but only those families that are allowed to be seen by current user
 * @param string $pid the gedcom xref id for the person to look in
 * @return array array of family ids
 */
function find_family_ids($pid) {
	$indirec=find_person_record($pid, WT_GED_ID);
	return find_visible_families_in_record($indirec, "FAMC");
}

/**
 * find all spouse family ids
 *
 * searches an individual gedcom record and returns an array of the FAMS ids where this person is a
 * spouse in the family, but only those families that are allowed to be seen by current user
 * @param string $pid the gedcom xref id for the person to look in
 * @return array array of family ids
 */
function find_sfamily_ids($pid) {
	$indirec=find_person_record($pid, WT_GED_ID);
	return find_visible_families_in_record($indirec, "FAMS");
}

/**
 * find all family ids in the given record
 *
 * searches an individual gedcom record and returns an array of the FAMS|C ids
 * @param string $indirec the gedcom record for the person to look in
 * @param string $tag  The family tag to look for
 * @return array array of family ids
 */
function find_families_in_record($indirec, $tag) {
	preg_match_all("/\n1 {$tag} @(".WT_REGEX_XREF.')@/', $indirec, $match);
	return $match[1];
}

/**
 * find all family ids in the given record that should be visible to the current user
 *
 * searches an individual gedcom record and returns an array of the FAMS|C ids that are visible
 * @param string $indirec the gedcom record for the person to look in
 * @param string $tag  The family tag to look for, FAMS or FAMC
 * @return array array of family ids
 */
function find_visible_families_in_record($indirec, $tag) {
	$allfams = find_families_in_record($indirec, $tag);
	$visiblefams = array();
	// select only those that are visible to current user
	foreach ($allfams as $key=>$famid) {
		if (canDisplayRecord(WT_GED_ID, find_family_record($famid, WT_GED_ID))) {
			$visiblefams[] = $famid;
		}
	}
	return $visiblefams;
}

// ************************************************* START OF MULTIMEDIA FUNCTIONS ********************************* //
/**
 * find the highlighted media object for a gedcom entity
 * 1. Ignore all media objects that are not displayable because of Privacy rules
 * 2. Ignore all media objects with the Highlight option set to "N"
 * 3. Pick the first media object that matches these criteria, in order of preference:
 *    (a) Level 1 object with the Highlight option set to "Y"
 *    (b) Level 1 object with the Highlight option missing or set to other than "Y" or "N"
 *    (c) Level 2 or higher object with the Highlight option set to "Y"
 *    (d) Level 2 or higher object with the Highlight option missing or set to other than "Y" or "N"
 * Criterion (d) will be present in the code but will be commented out for now.
 *
 * @param string $pid the individual, source, or family id
 * @param string $indirec the gedcom record to look in
 * @return array an object array with indexes "thumb" and "file" for thumbnail and filename
 */
function find_highlighted_object($pid, $ged_id, $indirec) {
	global $MEDIA_DIRECTORY, $MEDIA_DIRECTORY_LEVELS, $WT_IMAGES, $MEDIA_EXTERNAL;

	$media = array();
	$objectA = array();
	$objectB = array();
	$objectC = array();
	$objectD = array();

	//-- handle finding the media of remote objects
	$ct = preg_match("/(.*):(.*)/", $pid, $match);
	if ($ct>0) {
		require_once WT_ROOT.'includes/classes/class_serviceclient.php';
		$client = ServiceClient::getInstance($match[1]);
		if (!is_null($client)) {
			$mt = preg_match_all('/\n\d OBJE @('.WT_REGEX_XREF.')@/', $indirec, $matches, PREG_SET_ORDER);
			for ($i=0; $i<$mt; $i++) {
				$mediaObj = Media::getInstance($matches[$i][1]);
				$mrec = $mediaObj->getGedcomRecord();
				if (!empty($mrec)) {
					$file = get_gedcom_value("FILE", 1, $mrec);
					$row = array($matches[$i][1], $file, $mrec, $matches[$i][0]);
					$media[] = $row;
				}
			}
		}
	}

	//-- find all of the media items for a person
	$media=
		WT_DB::prepare("SELECT m_media, m_file, m_gedrec, mm_gedrec FROM `##media`, `##media_mapping` WHERE m_media=mm_media AND m_gedfile=mm_gedfile AND m_gedfile=? AND mm_gid=? ORDER BY mm_order")
		->execute(array($ged_id, $pid))
		->fetchAll(PDO::FETCH_NUM);

	foreach ($media as $i=>$row) {
		if (canDisplayRecord($ged_id, $row[2]) && canDisplayFact($row[0], $ged_id, $row[3])) {
			$level=0;
			$ct = preg_match("/(\d+) OBJE/", $row[3], $match);
			if ($ct>0) {
				$level = $match[1];
			}
			if (strstr($row[3], "_PRIM ")) {
				$thum = get_gedcom_value('_THUM', $level+1, $row[3]);
				$prim = get_gedcom_value('_PRIM', $level+1, $row[3]);
			} else {
				$thum = get_gedcom_value('_THUM', 1, $row[2]);
				$prim = get_gedcom_value('_PRIM', 1, $row[2]);
			}

			if ($prim=='N') continue; // Skip _PRIM N objects
			$file = check_media_depth($row[1]);
			$thumb = thumbnail_file($row[1], true, false, $pid);
			if ($level == 1) {
				if ($prim == 'Y') {
					if (empty($objectA)) {
						$objectA['file'] = $file;
						$objectA['thumb'] = $thumb;
						$objectA['_THUM'] = $thum; // This overrides GEDCOM's "Use main image as thumbnail" option
						$objectA['level'] = $level;
						$objectA['mid'] = $row[0];
					}
				} else {
					if (empty($objectB)) {
						$objectB['file'] = $file;
						$objectB['thumb'] = $thumb;
						$objectB['_THUM'] = $thum; // This overrides GEDCOM's "Use main image as thumbnail" option
						$objectB['level'] = $level;
						$objectB['mid'] = $row[0];
					}
				}
			} else {
				if ($prim == 'Y') {
					if (empty($objectC)) {
						$objectC['file'] = $file;
						$objectC['thumb'] = $thumb;
						$objectC['_THUM'] = $thum; // This overrides GEDCOM's "Use main image as thumbnail" option
						$objectC['level'] = $level;
						$objectC['mid'] = $row[0];
					}
				} else {
					if (empty($objectD)) {
						$objectD['file'] = $file;
						$objectD['thumb'] = $thumb;
						$objectD['_THUM'] = $thum; // This overrides GEDCOM's "Use main image as thumbnail" option
						$objectD['level'] = $level;
						$objectD['mid'] = $row[0];
					}
				}
			}
		}
	}

	if (!empty($objectA)) return $objectA;
	if (!empty($objectB)) return $objectB;
	if (!empty($objectC)) return $objectC;
	//if (!empty($objectD)) return $objectD;

	return array();
}

/**
 * Determine whether the main image or a thumbnail should be sent to the browser
 */
function thumb_or_main($object) {
	global $USE_THUMBS_MAIN;

	if ($object['_THUM']=='Y' || !$USE_THUMBS_MAIN) $file = 'file';
	else $file = 'thumb';

	// Here we should check whether the selected file actually exists
	return($object[$file]);
}

/**
 * get the full file path
 *
 * get the file path from a multimedia gedcom record
 * @param string $mediarec a OBJE subrecord
 * @return the fullpath from the FILE record
 */
function extract_fullpath($mediarec) {
	preg_match("/(\d) _*FILE (.*)/", $mediarec, $amatch);
	if (empty($amatch[2])) {
		return "";
	}
	$level = trim($amatch[1]);
	$fullpath = trim($amatch[2]);
	$filerec = get_sub_record($level, $amatch[0], $mediarec);
	$fullpath .= get_cont($level+1, $filerec);
	return $fullpath;
}

/**
 * get the relative filename for a media item
 *
 * gets the relative file path from the full media path for a media item.  checks the
 * <var>$MEDIA_DIRECTORY_LEVELS</var> to make sure the directory structure is maintained.
 * @param string $fullpath the full path from the media record
 * @return string a relative path that can be appended to the <var>$MEDIA_DIRECTORY</var> to reference the item
 */
function extract_filename($fullpath) {
	global $MEDIA_DIRECTORY_LEVELS, $MEDIA_DIRECTORY;

	$filename="";
	$regexp = "'[/\\\]'";
	$srch = "/".addcslashes($MEDIA_DIRECTORY, '/.')."/";
	$repl = "";
	if (!isFileExternal($fullpath)) {
		$nomedia = stripcslashes(preg_replace($srch, $repl, $fullpath));
	} else {
		$nomedia = $fullpath;
	}
	$ct = preg_match($regexp, $nomedia, $match);
	if ($ct>0) {
		$subelements = preg_split($regexp, $nomedia);
		$subelements = array_reverse($subelements);
		$max = $MEDIA_DIRECTORY_LEVELS;
		if ($max>=count($subelements)) {
			$max=count($subelements)-1;
		}
		for ($s=$max; $s>=0; $s--) {
			if ($s!=$max) {
				$filename = $filename."/".$subelements[$s];
			} else {
				$filename = $subelements[$s];
			}
		}
	} else {
		$filename = $nomedia;
	}
	return $filename;
}


// ************************************************* START OF SORTING FUNCTIONS ********************************* //
/**
 * Function to sort GEDCOM fact tags based on their tanslations
 */
function factsort($a, $b) {
	return utf8_strcasecmp(i18n::translate($a), i18n::translate($b));
}
/**
 * Function to sort place names array
 */
function placesort($a, $b) {
	return utf8_strcasecmp($a['place'], $b['place']);
}
////////////////////////////////////////////////////////////////////////////////
// Sort a list events for the today/upcoming blocks
////////////////////////////////////////////////////////////////////////////////
function event_sort($a, $b) {
	if ($a['jd']==$b['jd']) {
		if ($a['anniv']==$b['anniv']) {
			return utf8_strcasecmp($a['fact'], $b['fact']);
		}
		else {
			return utf8_strcasecmp($a['anniv'], $b['anniv']);
		}
	} else {
		return $a['jd']-$b['jd'];
	}
}

function event_sort_name($a, $b) {
	if ($a['jd']==$b['jd']) {
		return GedcomRecord::compare($a['record'], $b['record']);
	} else {
		return $a['jd']-$b['jd'];
	}
}

/**
 * sort an array of media items
 *
 */

function mediasort($a, $b) {
	$aKey = "";
	if (!empty($a["TITL"])) {
		$aKey = $a["TITL"];
	} else {
		if (!empty($a["titl"])) {
			$aKey = $a["titl"];
		} else {
			if (!empty($a["NAME"])) {
				$aKey = $a["NAME"];
			} else {
				if (!empty($a["name"])) {
					$aKey = $a["name"];
				} else {
					if (!empty($a["FILE"])) {
						$aKey = basename($a["FILE"]);
					} else {
						if (!empty($a["file"])) {
							$aKey = basename($a["file"]);
						}
					}
				}
			}
		}
	}

	$bKey = "";
	if (!empty($b["TITL"])) {
		$bKey = $b["TITL"];
	} else {
		if (!empty($b["titl"])) {
			$bKey = $b["titl"];
		} else {
			if (!empty($b["NAME"])) {
				$bKey = $b["NAME"];
			} else {
				if (!empty($b["name"])) {
					$bKey = $b["name"];
				} else {
					if (!empty($b["FILE"])) {
						$bKey = basename($b["FILE"]);
					} else {
						if (!empty($b["file"])) {
							$bKey = basename($b["file"]);
						}
					}
				}
			}
		}
	}
	return utf8_strcasecmp($aKey, $bKey, true); // Case-insensitive compare
}
/**
 * sort an array according to the file name
 *
 */

function filesort($a, $b) {
	$aKey = "";
	if (!empty($a["FILE"])) {
		$aKey = basename($a["FILE"]);
	} else if (!empty($a["file"])) {
		$aKey = basename($a["file"]);
	}

	$bKey = "";
	if (!empty($b["FILE"])) {
		$bKey = basename($b["FILE"]);
	} else if (!empty($b["file"])) {
		$bKey = basename($b["file"]);
	}
	return utf8_strcasecmp($aKey, $bKey, true); // Case-insensitive compare
}

// Helper function to sort facts.
function compare_facts_date($arec, $brec) {
	if (is_array($arec))
		$arec = $arec[1];
	if (is_array($brec))
		$brec = $brec[1];

	// If either fact is undated, the facts sort equally.
	if (!preg_match("/2 _?DATE (.*)/", $arec, $amatch) || !preg_match("/2 _?DATE (.*)/", $brec, $bmatch)) {
		if (preg_match('/2 _SORT (\d+)/', $arec, $match1) && preg_match('/2 _SORT (\d+)/', $brec, $match2)) {
			return $match1[1]-$match2[1];
		}
		return 0;
	}

	$adate = new GedcomDate($amatch[1]);
	$bdate = new GedcomDate($bmatch[1]);
	// If either date can't be parsed, don't sort.
	if (!$adate->isOK() || !$bdate->isOK()) {
		if (preg_match('/2 _SORT (\d+)/', $arec, $match1) && preg_match('/2 _SORT (\d+)/', $brec, $match2)) {
			return $match1[1]-$match2[1];
		}
		return 0;
	}

	// Remember that dates can be ranges and overlapping ranges sort equally.
	$amin=$adate->MinJD();
	$bmin=$bdate->MinJD();
	$amax=$adate->MaxJD();
	$bmax=$bdate->MaxJD();

	// BEF/AFT XXX sort as the day before/after XXX
	if ($adate->qual1=='BEF') {
		$amin=$amin-1;
		$amax=$amin;
	} elseif ($adate->qual1=='AFT') {
		$amax=$amax+1;
		$amin=$amax;
	}
	if ($bdate->qual1=='BEF') {
		$bmin=$bmin-1;
		$bmax=$bmin;
	} elseif ($bdate->qual1=='AFT') {
		$bmax=$bmax+1;
		$bmin=$bmax;
	}

	if ($amax<$bmin) {
		return -1;
	} else {
		if ($amin>$bmax) {
			return 1;
		} else {
			//-- ranged date... take the type of fact sorting into account
			$factWeight = 0;
			if (preg_match('/2 _SORT (\d+)/', $arec, $match1) && preg_match('/2 _SORT (\d+)/', $brec, $match2)) {
				$factWeight = $match1[1]-$match2[1];
			}
			//-- fact is prefered to come before, so compare using the minimum ranges
			if ($factWeight < 0 && $amin!=$bmin) {
				return ($amin-$bmin);
			} else {
				if ($factWeight > 0 && $bmax!=$amax) {
					//-- fact is prefered to come after, so compare using the max of the ranges
					return ($bmax-$amax);
				} else {
					//-- facts are the same or the ranges don't give enough info, so use the average of the range
					$aavg = ($amin+$amax)/2;
					$bavg = ($bmin+$bmax)/2;
					if ($aavg<$bavg) {
						return -1;
					} else {
						if ($aavg>$bavg) {
							return 1;
						} else {
							return $factWeight;
						}
					}
				}
			}
			return 0;
		}
	}
}

/**
 * A multi-key sort
 * 1. First divide the facts into two arrays one set with dates and one set without dates
 * 2. Sort each of the two new arrays, the date using the compare date function, the non-dated
 * using the compare type function
 * 3. Then merge the arrays back into the original array using the compare type function
 *
 * @param unknown_type $arr
 */
function sort_facts(&$arr) {
	$dated = array();
	$nondated = array();
	//-- split the array into dated and non-dated arrays
	$order = 0;
	foreach($arr as $event) {
		$event->sortOrder = $order;
		$order++;
		if ($event->getValue("DATE")==NULL || !$event->getDate()->isOk()) $nondated[] = $event;
		else $dated[] = $event;
	}

	//-- sort each type of array
	usort($dated, array("Event", "CompareDate"));
	usort($nondated, array("Event", "CompareType"));

	//-- merge the arrays back together comparing by Facts
	$dc = count($dated);
	$nc = count($nondated);
	$i = 0;
	$j = 0;
	$k = 0;
	// while there is anything in the dated array continue merging
	while($i<$dc) {
		// compare each fact by type to merge them in order
		if ($j<$nc && Event::CompareType($dated[$i], $nondated[$j])>0) {
			$arr[$k] = $nondated[$j];
			$j++;
		}
		else {
			$arr[$k] = $dated[$i];
			$i++;
		}
		$k++;
	}

	// get anything that might be left in the nondated array
	while($j<$nc) {
		$arr[$k] = $nondated[$j];
		$j++;
		$k++;
	}

}

function gedcomsort($a, $b) {
	return utf8_strcasecmp($a["title"], $b["title"]);
}

// ************************************************* START OF MISCELLANEOUS FUNCTIONS ********************************* //
/**
 * Get relationship between two individuals in the gedcom
 *
 * function to calculate the relationship between two people.  It uses heuristics based on the
 * individual's birthyears to try and calculate the shortest path between the two individuals.
 * It uses a node cache to help speed up calculations when using relationship privacy.
 * This cache is indexed using the string "$pid1-$pid2"
 * @param string $pid1 - the ID of the first person to compute the relationship from
 * @param string $pid2 - the ID of the second person to compute the relatiohip to
 * @param bool $followspouse = whether to add spouses to the path
 * @param int $maxlength - the maximum length of path
 * @param bool $ignore_cache - enable or disable the relationship cache
 * @param int $path_to_find - which path in the relationship to find, 0 is the shortest path, 1 is the next shortest path, etc
 */
function get_relationship($pid1, $pid2, $followspouse=true, $maxlength=0, $ignore_cache=false, $path_to_find=0) {
	global $start_time, $NODE_CACHE, $NODE_CACHE_LENGTH, $USE_RELATIONSHIP_PRIVACY;

	$time_limit=get_site_setting('MAX_EXECUTION_TIME');
	$indirec = find_gedcom_record($pid2, WT_GED_ID, WT_USER_CAN_EDIT);
	//-- check the cache
	if ($USE_RELATIONSHIP_PRIVACY && !$ignore_cache) {
		if (isset($NODE_CACHE["$pid1-$pid2"])) {
			if ($NODE_CACHE["$pid1-$pid2"]=="NOT FOUND") return false;
			if (($maxlength==0)||(count($NODE_CACHE["$pid1-$pid2"]["path"])-1<=$maxlength))
				return $NODE_CACHE["$pid1-$pid2"];
			else
				return false;
		}
		//-- check the cache for person 2's children
		$famids = array();
		$ct = preg_match_all("/1 FAMS @(.*)@/", $indirec, $match, PREG_SET_ORDER);
		for ($i=0; $i<$ct; $i++) {
			$famids[$i]=$match[$i][1];
		}
		foreach ($famids as $indexval => $fam) {
			$famrec = find_gedcom_record($fam, WT_GED_ID, WT_USER_CAN_EDIT);
			$ct = preg_match_all("/1 CHIL @(.*)@/", $famrec, $match, PREG_SET_ORDER);
			for ($i=0; $i<$ct; $i++) {
				$child = $match[$i][1];
				if (!empty($child)) {
					if (isset($NODE_CACHE["$pid1-$child"])) {
						if (($maxlength==0)||(count($NODE_CACHE["$pid1-$child"]["path"])+1<=$maxlength)) {
							$node1 = $NODE_CACHE["$pid1-$child"];
							if ($node1!="NOT FOUND") {
								$node1["path"][] = $pid2;
								$node1["pid"] = $pid2;
								if (strpos($indirec, "1 SEX F")!==false)
									$node1["relations"][] = "mother";
								else
									$node1["relations"][] = "father";
							}
							$NODE_CACHE["$pid1-$pid2"] = $node1;
							if ($node1=="NOT FOUND")
								return false;
							return $node1;
						} else
							return false;
					}
				}
			}
		}

		if ((!empty($NODE_CACHE_LENGTH))&&($maxlength>0)) {
			if ($NODE_CACHE_LENGTH>=$maxlength)
				return false;
		}
	}
	//-- end cache checking

	//-- get the birth year of p2 for calculating heuristics
	$birthrec = get_sub_record(1, "1 BIRT", $indirec);
	$byear2 = -1;
	if ($birthrec!==false) {
		$dct = preg_match("/2 DATE .*(\d\d\d\d)/", $birthrec, $match);
		if ($dct>0)
			$byear2 = $match[1];
	}
	if ($byear2==-1) {
		$numfams = preg_match_all("/1 FAMS @(.*)@/", $indirec, $fmatch, PREG_SET_ORDER);
		for ($j=0; $j<$numfams; $j++) {
			// Get the family record
			$famrec = find_gedcom_record($fmatch[$j][1], WT_GED_ID, WT_USER_CAN_EDIT);

			// Get the set of children
			$ct = preg_match_all("/1 CHIL @(.*)@/", $famrec, $cmatch, PREG_SET_ORDER);
			for ($i=0; $i<$ct; $i++) {
				// Get each child's record
				$childrec = find_gedcom_record($cmatch[$i][1], WT_GED_ID, WT_USER_CAN_EDIT);
				$birthrec = get_sub_record(1, "1 BIRT", $childrec);
				if ($birthrec!==false) {
					$dct = preg_match("/2 DATE .*(\d\d\d\d)/", $birthrec, $bmatch);
					if ($dct>0)
						$byear2 = $bmatch[1]-25;
						if ($byear2>2100) $byear2-=3760; // Crude conversion from jewish to gregorian
				}
			}
		}
	}
	//-- end of approximating birth year

	//-- current path nodes
	$p1nodes = array();
	//-- ids visited
	$visited = array();

	//-- set up first node for person1
	$node1 = array();
	$node1["path"] = array();
	$node1["path"][] = $pid1;
	$node1["length"] = 0;
	$node1["pid"] = $pid1;
	$node1["relations"] = array();
	$node1["relations"][] = "self";
	$p1nodes[] = $node1;

	$visited[$pid1] = true;

	$found = false;
	$count=0;
	while (!$found) {
		//-- the following 2 lines ensure that the user can abort a long relationship calculation
		//-- refer to http://www.php.net/manual/en/features.connection-handling.php for more
		//-- information about why these lines are included
		if (headers_sent()) {
			print " ";
			if ($count%100 == 0)
				flush();
		}
		$count++;
		$end_time = microtime(true);
		$exectime = $end_time - $start_time;
		if (($time_limit>1)&&($exectime > $time_limit-1)) {
			echo "<span class=\"error\">", i18n::translate('The script timed out before a relationship could be found.'), "</span>\n";
			return false;
		}
		if (count($p1nodes)==0) {
			if ($maxlength!=0) {
				if (!isset($NODE_CACHE_LENGTH)) {
					$NODE_CACHE_LENGTH = $maxlength;
				} elseif ($NODE_CACHE_LENGTH<$maxlength) {
					$NODE_CACHE_LENGTH = $maxlength;
				}
			}
			if (headers_sent()) {
				//print "\n<!-- Relationship $pid1-$pid2 NOT FOUND | Visited ".count($visited)." nodes | Required $count iterations.<br />\n";
				//echo execution_stats();
				//print "-->\n";
			}
			$NODE_CACHE["$pid1-$pid2"] = "NOT FOUND";
			return false;
		}
		//-- search the node list for the shortest path length
		$shortest = -1;
		foreach ($p1nodes as $index=>$node) {
			if ($shortest == -1) {
				$shortest = $index;
			} else {
				$node1 = $p1nodes[$shortest];
				if ($node1["length"] > $node["length"]) {
					$shortest = $index;
				}
			}
		}
		if ($shortest==-1)
			return false;
		$node = $p1nodes[$shortest];
		if (($maxlength==0)||(count($node["path"])<=$maxlength)) {
			if ($node["pid"]==$pid2) {
			} else {
				//-- heuristic values
				$fatherh = 1;
				$motherh = 1;
				$siblingh = 2;
				$spouseh = 2;
				$childh = 3;

				//-- generate heuristic values based on the birthdates of the current node and p2
				$indirec = find_gedcom_record($node["pid"], WT_GED_ID, WT_USER_CAN_EDIT);
				$byear1 = -1;
				$birthrec = get_sub_record(1, "1 BIRT", $indirec);
				if ($birthrec!==false) {
					$dct = preg_match("/2 DATE .*(\d\d\d\d)/", $birthrec, $match);
					if ($dct>0)
						$byear1 = $match[1];
						if ($byear1>2100) $byear1-=3760; // Crude conversion from jewish to gregorian
				}
				if (($byear1!=-1)&&($byear2!=-1)) {
					$yeardiff = $byear1-$byear2;
					if ($yeardiff < -140) {
						$fatherh = 20;
						$motherh = 20;
						$siblingh = 15;
						$spouseh = 15;
						$childh = 1;
					} else
						if ($yeardiff < -100) {
							$fatherh = 15;
							$motherh = 15;
							$siblingh = 10;
							$spouseh = 10;
							$childh = 1;
						} else
							if ($yeardiff < -60) {
								$fatherh = 10;
								$motherh = 10;
								$siblingh = 5;
								$spouseh = 5;
								$childh = 1;
							} else
								if ($yeardiff < -20) {
									$fatherh = 5;
									$motherh = 5;
									$siblingh = 3;
									$spouseh = 3;
									$childh = 1;
								} else
									if ($yeardiff<20) {
										$fatherh = 3;
										$motherh = 3;
										$siblingh = 1;
										$spouseh = 1;
										$childh = 5;
									} else
										if ($yeardiff<60) {
											$fatherh = 1;
											$motherh = 1;
											$siblingh = 5;
											$spouseh = 2;
											$childh = 10;
										} else
											if ($yeardiff<100) {
												$fatherh = 1;
												$motherh = 1;
												$siblingh = 10;
												$spouseh = 3;
												$childh = 15;
											} else {
												$fatherh = 1;
												$motherh = 1;
												$siblingh = 15;
												$spouseh = 4;
												$childh = 20;
											}
				}
				//-- check all parents and siblings of this node
				$famids = array();
				$ct = preg_match_all("/1 FAMC @(.*)@/", $indirec, $match, PREG_SET_ORDER);
				for ($i=0; $i<$ct; $i++) {
					if (!isset($visited[$match[$i][1]]))
						$famids[$i]=$match[$i][1];
				}
				foreach ($famids as $indexval => $fam) {
					$visited[$fam] = true;
					$famrec = find_gedcom_record($fam, WT_GED_ID, WT_USER_CAN_EDIT);
					$parents = find_parents_in_record($famrec);
					if ((!empty($parents["HUSB"]))&&(!isset($visited[$parents["HUSB"]]))) {
						$node1 = $node;
						$node1["length"]+=$fatherh;
						$node1["path"][] = $parents["HUSB"];
						$node1["pid"] = $parents["HUSB"];
						$node1["relations"][] = "parent";
						$p1nodes[] = $node1;
						if ($node1["pid"]==$pid2) {
							if ($path_to_find>0)
								$path_to_find--;
							else {
								$found=true;
								$resnode = $node1;
							}
						} else
							$visited[$parents["HUSB"]] = true;
						if ($USE_RELATIONSHIP_PRIVACY) {
							$NODE_CACHE["$pid1-".$node1["pid"]] = $node1;
						}
					}
					if ((!empty($parents["WIFE"]))&&(!isset($visited[$parents["WIFE"]]))) {
						$node1 = $node;
						$node1["length"]+=$motherh;
						$node1["path"][] = $parents["WIFE"];
						$node1["pid"] = $parents["WIFE"];
						$node1["relations"][] = "parent";
						$p1nodes[] = $node1;
						if ($node1["pid"]==$pid2) {
							if ($path_to_find>0)
								$path_to_find--;
							else {
								$found=true;
								$resnode = $node1;
							}
						} else
							$visited[$parents["WIFE"]] = true;
						if ($USE_RELATIONSHIP_PRIVACY) {
							$NODE_CACHE["$pid1-".$node1["pid"]] = $node1;
						}
					}
					$ct = preg_match_all("/1 CHIL @(.*)@/", $famrec, $match, PREG_SET_ORDER);
					for ($i=0; $i<$ct; $i++) {
						$child = $match[$i][1];
						if ((!empty($child))&&(!isset($visited[$child]))) {
							$node1 = $node;
							$node1["length"]+=$siblingh;
							$node1["path"][] = $child;
							$node1["pid"] = $child;
							$node1["relations"][] = "sibling";
							$p1nodes[] = $node1;
							if ($node1["pid"]==$pid2) {
								if ($path_to_find>0)
									$path_to_find--;
								else {
									$found=true;
									$resnode = $node1;
								}
							} else
								$visited[$child] = true;
							if ($USE_RELATIONSHIP_PRIVACY) {
								$NODE_CACHE["$pid1-".$node1["pid"]] = $node1;
							}
						}
					}
				}
				//-- check all spouses and children of this node
				$famids = array();
				$ct = preg_match_all("/1 FAMS @(.*)@/", $indirec, $match, PREG_SET_ORDER);
				for ($i=0; $i<$ct; $i++) {
					$famids[$i]=$match[$i][1];
				}
				foreach ($famids as $indexval => $fam) {
					$visited[$fam] = true;
					$famrec = find_gedcom_record($fam, WT_GED_ID, WT_USER_CAN_EDIT);
					if ($followspouse) {
						$parents = find_parents_in_record($famrec);
						if ((!empty($parents["HUSB"]))&&((!in_arrayr($parents["HUSB"], $node1))||(!isset($visited[$parents["HUSB"]])))) {
							$node1 = $node;
							$node1["length"]+=$spouseh;
							$node1["path"][] = $parents["HUSB"];
							$node1["pid"] = $parents["HUSB"];
							$node1["relations"][] = "spouse";
							$p1nodes[] = $node1;
							if ($node1["pid"]==$pid2) {
								if ($path_to_find>0)
									$path_to_find--;
								else {
									$found=true;
									$resnode = $node1;
								}
							} else
								$visited[$parents["HUSB"]] = true;
							if ($USE_RELATIONSHIP_PRIVACY) {
								$NODE_CACHE["$pid1-".$node1["pid"]] = $node1;
							}
						}
						if ((!empty($parents["WIFE"]))&&((!in_arrayr($parents["WIFE"], $node1))||(!isset($visited[$parents["WIFE"]])))) {
							$node1 = $node;
							$node1["length"]+=$spouseh;
							$node1["path"][] = $parents["WIFE"];
							$node1["pid"] = $parents["WIFE"];
							$node1["relations"][] = "spouse";
							$p1nodes[] = $node1;
							if ($node1["pid"]==$pid2) {
								if ($path_to_find>0)
									$path_to_find--;
								else {
									$found=true;
									$resnode = $node1;
								}
							} else
								$visited[$parents["WIFE"]] = true;
							if ($USE_RELATIONSHIP_PRIVACY) {
								$NODE_CACHE["$pid1-".$node1["pid"]] = $node1;
							}
						}
					}
					$ct = preg_match_all("/1 CHIL @(.*)@/", $famrec, $match, PREG_SET_ORDER);
					for ($i=0; $i<$ct; $i++) {
						$child = $match[$i][1];
						if ((!empty($child))&&(!isset($visited[$child]))) {
							$node1 = $node;
							$node1["length"]+=$childh;
							$node1["path"][] = $child;
							$node1["pid"] = $child;
							$node1["relations"][] = "child";
							$p1nodes[] = $node1;
							if ($node1["pid"]==$pid2) {
								if ($path_to_find>0)
									$path_to_find--;
								else {
									$found=true;
									$resnode = $node1;
								}
							} else {
								$visited[$child] = true;
							}
							if ($USE_RELATIONSHIP_PRIVACY) {
								$NODE_CACHE["$pid1-".$node1["pid"]] = $node1;
							}
						}
					}
				}
			}
		}
		unset($p1nodes[$shortest]);
	} //-- end while loop
	if (headers_sent()) {
		//echo "\n<!-- Relationship $pid1-$pid2 | Visited ".count($visited)." nodes | Required $count iterations.<br />\n";
		//echo execution_stats();
		//echo "-->\n";
	}

	// Convert "generic" relationships into sex-specific ones.
	foreach ($resnode['path'] as $n=>$pid) {
		switch ($resnode['relations'][$n]) {
		case 'parent':
			switch (Person::getInstance($pid)->getSex()) {
			case 'M': $resnode['relations'][$n]='father'; break;
			case 'F': $resnode['relations'][$n]='mother'; break;
			}
			break;
		case 'child':
			switch (Person::getInstance($pid)->getSex()) {
			case 'M': $resnode['relations'][$n]='son'; break;
			case 'F': $resnode['relations'][$n]='daughter'; break;
			}
			break;
		case 'spouse':
			switch (Person::getInstance($pid)->getSex()) {
			case 'M': $resnode['relations'][$n]='husband'; break;
			case 'F': $resnode['relations'][$n]='wife'; break;
			}
			break;
		case 'sibling':
			switch (Person::getInstance($pid)->getSex()) {
			case 'M': $resnode['relations'][$n]='brother'; break;
			case 'F': $resnode['relations'][$n]='sister'; break;
			}
			break;
		}
	}
	return $resnode;
}

// Convert the result of get_relationship() into a relationship name.
function get_relationship_name($nodes) {
	if (!is_array($nodes)) {
		return '';
	}
	$pid1=$nodes['path'][0];
	$pid2=$nodes['path'][count($nodes['path'])-1];
	$path=array_slice($nodes['relations'], 1);
	// Look for paths with *specific* names first.
	// Note that every combination must be listed separately, as the same english
	// name can be used for many different relationships.  e.g.
	// brother's wife & husband's sister = sister-in-law.
	//
	// $path is an array of the 12 possible gedcom family relationships:
	// mother/father/parent
	// brother/sister/sibling
	// husband/wife/spouse
	// son/daughter/child
	//
	// This is always the shortest path, so "father, daughter" is "half-sister", not "sister".
	//
	// This is very repetitive in english, but necessary in order to handle the
	// complexities of other languages.
	//
	// TODO: handle unmarried partners, so need male-partner, female-partner, unknown-partner

	// Make each relationship parts the same length, for simpler matching.
	$combined_path='';
	foreach ($path as $rel) {
		$combined_path.=substr($rel, 0, 3);
	}

	return get_relationship_name_from_path($combined_path, $pid1, $pid2);
}

function cousin_name($n, $sex) {
	switch ($sex) {
	case 'M':
		switch ($n) {
		case  1: // I18N: Note that for Italian and Polish, "N'th cousins" are different from English "N'th cousins", and the software has already generated the correct "N" for your language.  You only need to translate - you do not need to convert.  For other languages, if your cousin rules are different from English, please contact the developers.
		         return i18n::translate_c('MALE', 'first cousin');
		case  2: return i18n::translate_c('MALE', 'second cousin');
		case  3: return i18n::translate_c('MALE', 'third cousin');
		case  4: return i18n::translate_c('MALE', 'fourth cousin');
		case  5: return i18n::translate_c('MALE', 'fifth cousin');
		case  6: return i18n::translate_c('MALE', 'sixth cousin');
		case  7: return i18n::translate_c('MALE', 'seventh cousin');
		case  8: return i18n::translate_c('MALE', 'eighth cousin');
		case  9: return i18n::translate_c('MALE', 'ninth cousin');
		case 10: return i18n::translate_c('MALE', 'tenth cousin');
		case 11: return i18n::translate_c('MALE', 'eleventh cousin');
		case 12: return i18n::translate_c('MALE', 'twelfth cousin');
		case 13: return i18n::translate_c('MALE', 'thirteenth cousin');
		case 14: return i18n::translate_c('MALE', 'fourteenth cousin');
		case 15: return i18n::translate_c('MALE', 'fifteenth cousin');
		default: return i18n::translate_c('MALE', '%d x cousin', $n);
		}
	case 'F':
		switch ($n) {
		case  1: return i18n::translate_c('FEMALE', 'first cousin');
		case  2: return i18n::translate_c('FEMALE', 'second cousin');
		case  3: return i18n::translate_c('FEMALE', 'third cousin');
		case  4: return i18n::translate_c('FEMALE', 'fourth cousin');
		case  5: return i18n::translate_c('FEMALE', 'fifth cousin');
		case  6: return i18n::translate_c('FEMALE', 'sixth cousin');
		case  7: return i18n::translate_c('FEMALE', 'seventh cousin');
		case  8: return i18n::translate_c('FEMALE', 'eighth cousin');
		case  9: return i18n::translate_c('FEMALE', 'ninth cousin');
		case 10: return i18n::translate_c('FEMALE', 'tenth cousin');
		case 11: return i18n::translate_c('FEMALE', 'eleventh cousin');
		case 12: return i18n::translate_c('FEMALE', 'twelfth cousin');
		case 13: return i18n::translate_c('FEMALE', 'thirteenth cousin');
		case 14: return i18n::translate_c('FEMALE', 'fourteenth cousin');
		case 15: return i18n::translate_c('FEMALE', 'fifteenth cousin');
		default: return i18n::translate_c('FEMALE', '%d x cousin', $n);
		}
	case 'U':
		switch ($n) {
		case  1: return i18n::translate_c('MALE/FEMALE', 'first cousin');
		case  2: return i18n::translate_c('MALE/FEMALE', 'second cousin');
		case  3: return i18n::translate_c('MALE/FEMALE', 'third cousin');
		case  4: return i18n::translate_c('MALE/FEMALE', 'fourth cousin');
		case  5: return i18n::translate_c('MALE/FEMALE', 'fifth cousin');
		case  6: return i18n::translate_c('MALE/FEMALE', 'sixth cousin');
		case  7: return i18n::translate_c('MALE/FEMALE', 'seventh cousin');
		case  8: return i18n::translate_c('MALE/FEMALE', 'eighth cousin');
		case  9: return i18n::translate_c('MALE/FEMALE', 'ninth cousin');
		case 10: return i18n::translate_c('MALE/FEMALE', 'tenth cousin');
		case 11: return i18n::translate_c('MALE/FEMALE', 'eleventh cousin');
		case 12: return i18n::translate_c('MALE/FEMALE', 'twelfth cousin');
		case 13: return i18n::translate_c('MALE/FEMALE', 'thirteenth cousin');
		case 14: return i18n::translate_c('MALE/FEMALE', 'fourteenth cousin');
		case 15: return i18n::translate_c('MALE/FEMALE', 'fifteenth cousin');
		default: return i18n::translate_c('MALE/FEMALE', '%d x cousin', $n);
		}
	}
}

function get_relationship_name_from_path($path, $pid1, $pid2) {
	if (!preg_match('/^(mot|fat|par|hus|wif|spo|son|dau|chi|bro|sis|sib)*$/', $path)) {
		// TODO: Update all the "3 RELA " values in class_person
		return '<span class="error">'.$path.'</span>';
	}
	$person1=Person::GetInstance($pid1);
	$person2=Person::GetInstance($pid2);
	$sex1=$person1 ? $person1->getSex() : 'U';

	switch ($path) {
	case '': return i18n::translate('self');

	//  Level One relationships
	case 'mot': return i18n::translate('mother');
	case 'fat': return i18n::translate('father');
	case 'par': return i18n::translate('parent');
	case 'hus': return i18n::translate('husband');
	case 'wif': return i18n::translate('wife');
	case 'spo': return i18n::translate('spouse');
	case 'son': return i18n::translate('son');
	case 'dau': return i18n::translate('daughter');
	case 'chi': return i18n::translate('child');
	case 'bro':
		if ($person1 && $person2) {
			$dob1=$person1->getBirthDate();
			$dob2=$person2->getBirthDate();
			if ($dob1->isOK() && $dob2->isOK()) {
				if (abs($dob1->JD()-$dob2->JD())<2) {
					return i18n::translate('twin brother');
				} else if ($dob1->JD()<$dob2->JD()) {
					return i18n::translate('younger brother');
				} else {
					return i18n::translate('elder brother');
				}
			}
		}
		return i18n::translate('brother');
	case 'sis':
		if ($person1 && $person2) {
			$dob1=$person1->getBirthDate();
			$dob2=$person2->getBirthDate();
			if ($dob1->isOK() && $dob2->isOK()) {
				if (abs($dob1->JD()-$dob2->JD())<2) {
					return i18n::translate('twin sister');
				} else if ($dob1->JD()<$dob2->JD()) {
					return i18n::translate('younger sister');
				} else {
					return i18n::translate('elder sister');
				}
			}
		}
		return i18n::translate('sister');
	case 'sib':
		if ($person1 && $person2) {
			$dob1=$person1->getBirthDate();
			$dob2=$person2->getBirthDate();
			if ($dob1->isOK() && $dob2->isOK()) {
				if (abs($dob1->JD()-$dob2->JD())<2) {
					return i18n::translate('twin sibling');
				} else if ($dob1->JD()<$dob2->JD()) {
					return i18n::translate('younger sibling');
				} else {
					return i18n::translate('elder sibling');
				}
			}
		}
		return i18n::translate('sibling');

	// Level Two relationships
	case 'brochi': return i18n::translate_c('brother\'s child', 'nephew/niece');
	case 'brodau': return i18n::translate_c('brother\'s daughter', 'niece');
	case 'broson': return i18n::translate_c('brother\'s son', 'nephew');
	case 'browif': return i18n::translate_c('brother\'s wife', 'sister-in-law');
	case 'chichi': return i18n::translate_c('child\'s child', 'grandchild');
	case 'chidau': return i18n::translate_c('child\'s daughter', 'granddaughter');
	case 'chihus': return i18n::translate_c('child\'s husband', 'son-in-law');
	case 'chison': return i18n::translate_c('child\'s son', 'grandson');
	case 'chispo': return i18n::translate_c('child\'s spouse', 'son/daughter-in-law');
	case 'chiwif': return i18n::translate_c('child\'s wife', 'daughter-in-law');
	case 'dauchi': return i18n::translate_c('daughter\'s child', 'grandchild');
	case 'daudau': return i18n::translate_c('daughter\'s daughter', 'granddaughter');
	case 'dauhus': return i18n::translate_c('daughter\'s husband', 'son-in-law');
	case 'dauson': return i18n::translate_c('daughter\'s son', 'grandson');
	case 'fatbro': return i18n::translate_c('father\'s brother', 'uncle');
	case 'fatchi': return i18n::translate_c('father\'s child', 'half-sibling');
	case 'fatdau': return i18n::translate_c('father\'s daughter', 'half-sister');
	case 'fatfat': return i18n::translate_c('father\'s father', 'grandfather');
	case 'fatmot': return i18n::translate_c('father\'s mother', 'grandmother');
	case 'fatpar': return i18n::translate_c('father\'s parent', 'grandparent');
	case 'fatsib': return i18n::translate_c('father\'s sibling', 'aunt/uncle');
	case 'fatsis': return i18n::translate_c('father\'s sister', 'aunt');
	case 'fatson': return i18n::translate_c('father\'s son', 'half-brother');
	case 'fatwif': return i18n::translate_c('father\'s wife', 'step-mother');
	case 'husbro': return i18n::translate_c('husband\'s brother', 'brother-in-law');
	case 'huschi': return i18n::translate_c('husband\'s child', 'step-child');
	case 'husdau': return i18n::translate_c('husband\'s daughter', 'step-daughter');
	case 'husfat': return i18n::translate_c('husband\'s father', 'father-in-law');
	case 'husmot': return i18n::translate_c('husband\'s mother', 'mother-in-law');
	case 'hussib': return i18n::translate_c('husband\'s sibling', 'brother/sister-in-law');
	case 'hussis': return i18n::translate_c('husband\'s sister', 'sister-in-law');
	case 'husson': return i18n::translate_c('husband\'s son', 'step-son');
	case 'motbro': return i18n::translate_c('mother\'s brother', 'uncle');
	case 'motchi': return i18n::translate_c('mother\'s child', 'half-sibling');
	case 'motdau': return i18n::translate_c('mother\'s daughter', 'half-sister');
	case 'motfat': return i18n::translate_c('mother\'s father', 'grandfather');
	case 'mothus': return i18n::translate_c('mother\'s husband', 'step-father');
	case 'motmot': return i18n::translate_c('mother\'s mother', 'grandmother');
	case 'motpar': return i18n::translate_c('mother\'s parent', 'grandparent');
	case 'motsib': return i18n::translate_c('mother\'s sibling', 'aunt/uncle');
	case 'motsis': return i18n::translate_c('mother\'s sister', 'aunt');
	case 'motson': return i18n::translate_c('mother\'s son', 'half-brother');
	case 'parbro': return i18n::translate_c('parent\'s brother', 'uncle');
	case 'parchi': return i18n::translate_c('parent\'s child', 'half-sibling');
	case 'pardau': return i18n::translate_c('parent\'s daughter', 'half-sister');
	case 'parfat': return i18n::translate_c('parent\'s father', 'grandfather');
	case 'parmot': return i18n::translate_c('parent\'s mother', 'grandmother');
	case 'parpar': return i18n::translate_c('parent\'s parent', 'grandparent');
	case 'parsib': return i18n::translate_c('parent\'s sibling', 'aunt/uncle');
	case 'parsis': return i18n::translate_c('parent\'s sister', 'aunt');
	case 'parson': return i18n::translate_c('parent\'s son', 'half-brother');
	case 'parspo': return i18n::translate_c('parent\'s spouse', 'step-parent');
	case 'sibchi': return i18n::translate_c('sibling\'s child', 'nephew/niece');
	case 'sibdau': return i18n::translate_c('sibling\'s daughter', 'niece');
	case 'sibson': return i18n::translate_c('sibling\'s son', 'nephew');
	case 'sibspo': return i18n::translate_c('sibling\'s spouse', 'brother/sister-in-law');
	case 'sischi': return i18n::translate_c('sister\'s child', 'nephew/niece');
	case 'sisdau': return i18n::translate_c('sister\'s daughter', 'niece');
	case 'sishus': return i18n::translate_c('sister\'s husband', 'brother-in-law');
	case 'sisson': return i18n::translate_c('sister\'s son', 'nephew');
	case 'sonchi': return i18n::translate_c('son\'s child', 'grandchild');
	case 'sondau': return i18n::translate_c('son\'s daughter', 'granddaughter');
	case 'sonson': return i18n::translate_c('son\'s son', 'grandson');
	case 'sonwif': return i18n::translate_c('son\'s wife', 'daughter-in-law');
	case 'spobro': return i18n::translate_c('spouses\'s brother', 'brother-in-law');
	case 'spochi': return i18n::translate_c('spouses\'s child', 'step-child');
	case 'spodau': return i18n::translate_c('spouses\'s daughter', 'step-daughter');
	case 'spofat': return i18n::translate_c('spouses\'s father', 'father-in-law');
	case 'spomot': return i18n::translate_c('spouses\'s mother', 'mother-in-law');
	case 'sposis': return i18n::translate_c('spouses\'s sister', 'sister-in-law');
	case 'sposon': return i18n::translate_c('spouses\'s son', 'step-son');
	case 'spopar': return i18n::translate_c('spouses\'s parent', 'mother/father-in-law');
	case 'sposib': return i18n::translate_c('spouses\'s sibling', 'brother/sister-in-law');
	case 'wifbro': return i18n::translate_c('wife\'s brother', 'brother-in-law');
	case 'wifchi': return i18n::translate_c('wife\'s child', 'step-child');
	case 'wifdau': return i18n::translate_c('wife\'s daughter', 'step-daughter');
	case 'wiffat': return i18n::translate_c('wife\'s father', 'father-in-law');
	case 'wifmot': return i18n::translate_c('wife\'s mother', 'mother-in-law');
	case 'wifsib': return i18n::translate_c('wife\'s sibling', 'brother/sister-in-law');
	case 'wifsis': return i18n::translate_c('wife\'s sister', 'sister-in-law');
	case 'wifson': return i18n::translate_c('wife\'s son', 'step-son');

	// Level Three relationships
	// I have commented out some of the unknown-sex relationships that are unlikely to to occur.
	// Feel free to add them in, if you think they might be needed
	case 'brochichi': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s child\'s child',       'great-nephew/niece');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s child\'s child',     'great-nephew/niece');
	case 'brochidau': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s child\'s daughter',    'great-niece');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s child\'s daughter',  'great-niece');
	case 'brochison': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s child\'s son',         'great-nephew');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s child\'s son',       'great-nephew');
	case 'brodauchi': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s daughter\'s child',    'great-nephew/niece');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s daughter\'s child',  'great-nephew/niece');
	case 'brodaudau': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s daughter\'s daughter', 'great-niece');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s daughter\'s daughter', 'great-niece');
	case 'brodauhus': return i18n::translate_c('brother\'s daughter\'s husband',   'nephew-in-law');
	case 'brodauson': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s daughter\'s son',      'great-nephew');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s daughter\'s son',    'great-nephew');
	case 'brosonchi': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s son\'s child',         'great-nephew/niece');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s son\'s child',       'great-nephew/niece');
	case 'brosondau': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s son\'s daughter',      'great-niece');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s son\'s daughter',    'great-niece');
	case 'brosonson': if ($sex1=='M') return i18n::translate_c('(a man\'s) brother\'s son\'s son',           'great-nephew');
	                  else            return i18n::translate_c('(a woman\'s) brother\'s son\'s son',         'great-nephew');
	case 'brosonwif': return i18n::translate_c('brother\'s son\'s wife',           'niece-in-law');
	case 'browifbro': return i18n::translate_c('brother\'s wife\'s brother',       'brother-in-law');
	case 'browifsib': return i18n::translate_c('brother\'s wife\'s sibling',       'brother/sister-in-law');
	case 'browifsis': return i18n::translate_c('brother\'s wife\'s sister',        'sister-in-law');
	case 'chichichi': return i18n::translate_c('child\'s child\'s child',          'great-grandchild');
	case 'chichidau': return i18n::translate_c('child\'s child\'s daughter',       'great-granddaughter');
	case 'chichison': return i18n::translate_c('child\'s child\'s son',            'great-grandson');
	case 'chidauchi': return i18n::translate_c('child\'s daughter\'s child',       'great-grandchild');
	case 'chidaudau': return i18n::translate_c('child\'s daughter\'s daughter',    'great-granddaughter');
	case 'chidauhus': return i18n::translate_c('child\'s daughter\'s husband',     'granddaughter\'s husband');
	case 'chidauson': return i18n::translate_c('child\'s daughter\'s son',         'great-grandson');
	case 'chisonchi': return i18n::translate_c('child\'s son\'s child',            'great-grandchild');
	case 'chisondau': return i18n::translate_c('child\'s son\'s daughter',         'great-granddaughter');
	case 'chisonson': return i18n::translate_c('child\'s son\'s son',              'great-grandson');
	case 'chisonwif': return i18n::translate_c('child\'s son\'s wife',             'grandson\'s wife');
//case 'chispomot': return i18n::translate_c('child\'s spouse\'s mother',        'daughter/son-in-law\'s father');
//case 'chispofat': return i18n::translate_c('child\'s spouse\'s father',        'daughter/son-in-law\'s father');
//case 'chispopar': return i18n::translate_c('child\'s spouse\'s parent',        'daughter/son-in-law\'s parent');
	case 'dauchichi': return i18n::translate_c('daughter\'s child\'s child',       'great-grandchild');
	case 'dauchidau': return i18n::translate_c('daughter\'s child\'s daughter',    'great-granddaughter');
	case 'dauchison': return i18n::translate_c('daughter\'s child\'s son',         'great-grandson');
	case 'daudauchi': return i18n::translate_c('daughter\'s daughter\'s child',    'great-grandchild');
	case 'daudaudau': return i18n::translate_c('daughter\'s daughter\'s daughter', 'great-granddaughter');
	case 'daudauhus': return i18n::translate_c('daughter\'s daughter\'s husband',  'granddaughter\'s husband');
	case 'daudauson': return i18n::translate_c('daughter\'s daughter\'s son',      'great-grandson');
	case 'dauhusfat': return i18n::translate_c('daughter\'s husband\'s father',    'son-in-law\'s father');
	case 'dauhusmot': return i18n::translate_c('daughter\'s husband\'s mother',    'son-in-law\'s mother');
	case 'dauhuspar': return i18n::translate_c('daughter\'s husband\'s parent',    'son-in-law\'s parent');
	case 'dausonchi': return i18n::translate_c('daughter\'s son\'s child',         'great-grandchild');
	case 'dausondau': return i18n::translate_c('daughter\'s son\'s daughter',      'great-granddaughter');
	case 'dausonson': return i18n::translate_c('daughter\'s son\'s son',           'great-grandson');
	case 'dausonwif': return i18n::translate_c('daughter\'s son\'s wife',          'grandson\'s wife');
	case 'fatbrochi': return i18n::translate_c('father\'s brother\'s child',       'first cousin');
	case 'fatbrodau': return i18n::translate_c('father\'s brother\'s daughter',    'first cousin');
	case 'fatbroson': return i18n::translate_c('father\'s brother\'s son',         'first cousin');
	case 'fatbrowif': return i18n::translate_c('father\'s brother\'s wife',        'aunt');
	case 'fatfatbro': return i18n::translate_c('father\'s father\'s brother',      'great-uncle');
	case 'fatfatfat': return i18n::translate_c('father\'s father\'s father',       'great-grandfather');
	case 'fatfatmot': return i18n::translate_c('father\'s father\'s mother',       'great-grandmother');
	case 'fatfatpar': return i18n::translate_c('father\'s father\'s parent',       'great-grandparent');
	case 'fatfatsib': return i18n::translate_c('father\'s father\'s sibling',      'great-aunt/uncle');
	case 'fatfatsis': return i18n::translate_c('father\'s father\'s sister',       'great-aunt');
	case 'fatmotbro': return i18n::translate_c('father\'s mother\'s brother',      'great-uncle');
	case 'fatmotfat': return i18n::translate_c('father\'s mother\'s father',       'great-grandfather');
	case 'fatmotmot': return i18n::translate_c('father\'s mother\'s mother',       'great-grandmother');
	case 'fatmotpar': return i18n::translate_c('father\'s mother\'s parent',       'great-grandparent');
	case 'fatmotsib': return i18n::translate_c('father\'s mother\'s sibling',      'great-aunt/uncle');
	case 'fatmotsis': return i18n::translate_c('father\'s mother\'s sister',       'great-aunt');
	case 'fatparbro': return i18n::translate_c('father\'s parent\'s brother',      'great-uncle');
	case 'fatparfat': return i18n::translate_c('father\'s parent\'s father',       'great-grandfather');
	case 'fatparmot': return i18n::translate_c('father\'s parent\'s mother',       'great-grandmother');
	case 'fatparpar': return i18n::translate_c('father\'s parent\'s parent',       'great-grandparent');
	case 'fatparsib': return i18n::translate_c('father\'s parent\'s sibling',      'great-aunt/uncle');
	case 'fatparsis': return i18n::translate_c('father\'s parent\'s sister',       'great-aunt');
	case 'fatsischi': return i18n::translate_c('father\'s sister\'s child',        'first cousin');
	case 'fatsisdau': return i18n::translate_c('father\'s sister\'s daughter',     'first cousin');
	case 'fatsishus': return i18n::translate_c('father\'s sister\'s husband',      'uncle');
	case 'fatsisson': return i18n::translate_c('father\'s sister\'s son',          'first cousin');
	case 'fatwifchi': return i18n::translate_c('father\'s wife\'s child',          'step-sibling');
	case 'fatwifdau': return i18n::translate_c('father\'s wife\'s daughter',       'step-sister');
	case 'fatwifson': return i18n::translate_c('father\'s wife\'s son',            'step-brother');
	case 'husbrowif': return i18n::translate_c('husband\'s brother\'s wife',       'sister-in-law');
//case 'hussibspo': return i18n::translate_c('husband\'s sibling\'s spouse',     'brother/sister-in-law');
	case 'hussishus': return i18n::translate_c('husband\'s sister\'s husband',     'brother-in-law');
	case 'motbrochi': return i18n::translate_c('mother\'s brother\'s child',       'first cousin');
	case 'motbrodau': return i18n::translate_c('mother\'s brother\'s daughter',    'first cousin');
	case 'motbroson': return i18n::translate_c('mother\'s brother\'s son',         'first cousin');
	case 'motbrowif': return i18n::translate_c('mother\'s brother\'s wife',        'aunt');
	case 'motfatbro': return i18n::translate_c('mother\'s father\'s brother',      'great-uncle');
	case 'motfatfat': return i18n::translate_c('mother\'s father\'s father',       'great-grandfather');
	case 'motfatmot': return i18n::translate_c('mother\'s father\'s mother',       'great-grandmother');
	case 'motfatpar': return i18n::translate_c('mother\'s father\'s parent',       'great-grandparent');
	case 'motfatsib': return i18n::translate_c('mother\'s father\'s sibling',      'great-aunt/uncle');
	case 'motfatsis': return i18n::translate_c('mother\'s father\'s sister',       'great-aunt');
	case 'mothuschi': return i18n::translate_c('mother\'s husband\'s child',       'step-sibling');
	case 'mothusdau': return i18n::translate_c('mother\'s husband\'s daughter',    'step-sister');
	case 'mothusson': return i18n::translate_c('mother\'s husband\'s son',         'step-brother');
	case 'motmotbro': return i18n::translate_c('mother\'s mother\'s brother',      'great-uncle');
	case 'motmotfat': return i18n::translate_c('mother\'s mother\'s father',       'great-grandfather');
	case 'motmotmot': return i18n::translate_c('mother\'s mother\'s mother',       'great-grandmother');
	case 'motmotpar': return i18n::translate_c('mother\'s mother\'s parent',       'great-grandparent');
	case 'motmotsib': return i18n::translate_c('mother\'s mother\'s sibling',      'great-aunt/uncle');
	case 'motmotsis': return i18n::translate_c('mother\'s mother\'s sister',       'great-aunt');
	case 'motparbro': return i18n::translate_c('mother\'s parent\'s brother',      'great-uncle');
	case 'motparfat': return i18n::translate_c('mother\'s parent\'s father',       'great-grandfather');
	case 'motparmot': return i18n::translate_c('mother\'s parent\'s mother',       'great-grandmother');
	case 'motparpar': return i18n::translate_c('mother\'s parent\'s parent',       'great-grandparent');
	case 'motparsib': return i18n::translate_c('mother\'s parent\'s sibling',      'great-aunt/uncle');
	case 'motparsis': return i18n::translate_c('mother\'s parent\'s sister',       'great-aunt');
	case 'motsischi': return i18n::translate_c('mother\'s sister\'s child',        'first cousin');
	case 'motsisdau': return i18n::translate_c('mother\'s sister\'s daughter',     'first cousin');
	case 'motsishus': return i18n::translate_c('mother\'s sister\'s husband',      'uncle');
	case 'motsisson': return i18n::translate_c('mother\'s sister\'s son',          'first cousin');
	case 'parbrowif': return i18n::translate_c('parent\'s brother\'s wife',        'aunt');
	case 'parfatbro': return i18n::translate_c('parent\'s father\'s brother',      'great-uncle');
	case 'parfatfat': return i18n::translate_c('parent\'s father\'s father',       'great-grandfather');
	case 'parfatmot': return i18n::translate_c('parent\'s father\'s mother',       'great-grandmother');
	case 'parfatpar': return i18n::translate_c('parent\'s father\'s parent',       'great-grandparent');
	case 'parfatsib': return i18n::translate_c('parent\'s father\'s sibling',      'great-aunt/uncle');
	case 'parfatsis': return i18n::translate_c('parent\'s father\'s sister',       'great-aunt');
	case 'parmotbro': return i18n::translate_c('parent\'s mother\'s brother',      'great-uncle');
	case 'parmotfat': return i18n::translate_c('parent\'s mother\'s father',       'great-grandfather');
	case 'parmotmot': return i18n::translate_c('parent\'s mother\'s mother',       'great-grandmother');
	case 'parmotpar': return i18n::translate_c('parent\'s mother\'s parent',       'great-grandparent');
	case 'parmotsib': return i18n::translate_c('parent\'s mother\'s sibling',      'great-aunt/uncle');
	case 'parmotsis': return i18n::translate_c('parent\'s mother\'s sister',       'great-aunt');
	case 'parparbro': return i18n::translate_c('parent\'s parent\'s brother',      'great-uncle');
	case 'parparfat': return i18n::translate_c('parent\'s parent\'s father',       'great-grandfather');
	case 'parparmot': return i18n::translate_c('parent\'s parent\'s mother',       'great-grandmother');
	case 'parparpar': return i18n::translate_c('parent\'s parent\'s parent',       'great-grandparent');
	case 'parparsib': return i18n::translate_c('parent\'s parent\'s sibling',      'great-aunt/uncle');
	case 'parparsis': return i18n::translate_c('parent\'s parent\'s sister',       'great-aunt');
	case 'parsishus': return i18n::translate_c('parent\'s sister\'s husband',      'uncle');
	case 'parspochi': return i18n::translate_c('parent\'s spouse\'s child',        'step-sibling');
	case 'parspodau': return i18n::translate_c('parent\'s spouse\'s daughter',     'step-sister');
	case 'parsposon': return i18n::translate_c('parent\'s spouse\'s son',          'step-brother');
	case 'sibchichi': return i18n::translate_c('sibling\'s child\'s child',        'great-nephew/niece');
	case 'sibchidau': return i18n::translate_c('sibling\'s child\'s daughter',     'great-niece');
	case 'sibchison': return i18n::translate_c('sibling\'s child\'s son',          'great-nephew');
	case 'sibdauchi': return i18n::translate_c('sibling\'s daughter\'s child',     'great-nephew/niece');
	case 'sibdaudau': return i18n::translate_c('sibling\'s daughter\'s daughter',  'great-niece');
	case 'sibdauhus': return i18n::translate_c('sibling\'s daughter\'s husband',   'nephew-in-law');
	case 'sibdauson': return i18n::translate_c('sibling\'s daughter\'s son',       'great-nephew');
	case 'sibsonchi': return i18n::translate_c('sibling\'s son\'s child',          'great-nephew/niece');
	case 'sibsondau': return i18n::translate_c('sibling\'s son\'s daughter',       'great-niece');
	case 'sibsonson': return i18n::translate_c('sibling\'s son\'s son',            'great-nephew');
	case 'sibsonwif': return i18n::translate_c('sibling\'s son\'s wife',           'niece-in-law');
//case 'sibspobro': return i18n::translate_c('sibling\'s spouse\'s brother',     'brother-in-law');
//case 'sibsposib': return i18n::translate_c('sibling\'s spouse\'s sibling',     'brother/sister-in-law');
//case 'sibsposis': return i18n::translate_c('sibling\'s spouse\'s sister',      'sister-in-law');
	case 'sischichi': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s child\'s child',          'great-nephew/niece');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s child\'s child',        'great-nephew/niece');
	case 'sischidau': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s child\'s daughter',       'great-niece');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s child\'s daughter',     'great-niece');
	case 'sischison': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s child\'s son',            'great-nephew');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s child\'s son',          'great-nephew');
	case 'sisdauchi': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s daughter\'s child',       'great-nephew/niece');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s daughter\'s child',     'great-nephew/niece');
	case 'sisdaudau': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s daughter\'s daughter',    'great-niece');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s daughter\'s daughter',  'great-niece');
	case 'sisdauhus': return i18n::translate_c('sisters\'s daughter\'s husband',   'nephew-in-law');
	case 'sisdauson': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s daughter\'s son',         'great-nephew');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s daughter\'s son',       'great-nephew');
	case 'sishusbro': return i18n::translate_c('sister\'s husband\'s brother',     'brother-in-law');
	case 'sishussib': return i18n::translate_c('sister\'s husband\'s sibling',     'brother/sister-in-law');
	case 'sishussis': return i18n::translate_c('sister\'s husband\'s sister',      'sister-in-law');
	case 'sissonchi': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s son\'s child',            'great-nephew/niece');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s son\'s child',          'great-nephew/niece');
	case 'sissondau': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s son\'s daughter',         'great-niece');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s son\'s daughter',       'great-niece');
	case 'sissonson': if ($sex1=='M') return i18n::translate_c('(a man\'s) sister\'s son\'s son',              'great-nephew');
	                  else            return i18n::translate_c('(a woman\'s) sister\'s son\'s son',            'great-nephew');
	case 'sissonwif': return i18n::translate_c('sisters\'s son\'s wife',           'niece-in-law');
	case 'sonchichi': return i18n::translate_c('son\'s child\'s child',            'great-grandchild');
	case 'sonchidau': return i18n::translate_c('son\'s child\'s daughter',         'great-granddaughter');
	case 'sonchison': return i18n::translate_c('son\'s child\'s son',              'great-grandson');
	case 'sondauchi': return i18n::translate_c('son\'s daughter\'s child',         'great-grandchild');
	case 'sondaudau': return i18n::translate_c('son\'s daughter\'s daughter',      'great-granddaughter');
	case 'sondauhus': return i18n::translate_c('son\'s daughter\'s husband',       'granddaughter\'s husband');
	case 'sondauson': return i18n::translate_c('son\'s daughter\'s son',           'great-grandson');
	case 'sonsonchi': return i18n::translate_c('son\'s son\'s child',              'great-grandchild');
	case 'sonsondau': return i18n::translate_c('son\'s son\'s daughter',           'great-granddaughter');
	case 'sonsonson': return i18n::translate_c('son\'s son\'s son',                'great-grandson');
	case 'sonsonwif': return i18n::translate_c('son\'s son\'s wife',               'grandson\'s wife');
	case 'sonwiffat': return i18n::translate_c('son\'s wife\'s father',            'daughter-in-law\'s father');
	case 'sonwifmot': return i18n::translate_c('son\'s wife\'s mother',            'daughter-in-law\'s mother');
	case 'sonwifpar': return i18n::translate_c('son\'s wife\'s parent',            'daughter-in-law\'s parent');
//case 'spobrowif': return i18n::translate_c('spouse\'s brother\'s wife',        'sister-in-law');
//case 'sposibspo': return i18n::translate_c('spouse\'s sibling\'s spouse',      'brother/sister-in-law');
//case 'sposishus': return i18n::translate_c('spouse\'s sister\'s husband',      'brother-in-law');
	case 'wifbrowif': return i18n::translate_c('wife\'s brother\'s wife',          'sister-in-law');
//case 'wifsibspo': return i18n::translate_c('wife\'s sibling\'s spouse',        'brother/sister-in-law');
	case 'wifsishus': return i18n::translate_c('wife\'s sister\'s husband',        'brother-in-law');

	// Some "special case" level four relationships that have specific names in certain languages
	case 'fatfatbrowif': return i18n::translate_c('father\'s father\'s brother\'s wife',    'great-aunt');
	case 'fatfatsibspo': return i18n::translate_c('father\'s father\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'fatfatsishus': return i18n::translate_c('father\'s father\'s sister\'s husband',  'great-uncle');
	case 'fatmotbrowif': return i18n::translate_c('father\'s mother\'s brother\'s wife',    'great-aunt');
	case 'fatmotsibspo': return i18n::translate_c('father\'s mother\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'fatmotsishus': return i18n::translate_c('father\'s mother\'s sister\'s husband',  'great-uncle');
	case 'fatparbrowif': return i18n::translate_c('father\'s parent\'s brother\'s wife',    'great-aunt');
	case 'fatparsibspo': return i18n::translate_c('father\'s parent\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'fatparsishus': return i18n::translate_c('father\'s parent\'s sister\'s husband',  'great-uncle');
	case 'motfatbrowif': return i18n::translate_c('mother\'s father\'s brother\'s wife',    'great-aunt');
	case 'motfatsibspo': return i18n::translate_c('mother\'s father\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'motfatsishus': return i18n::translate_c('mother\'s father\'s sister\'s husband',  'great-uncle');
	case 'motmotbrowif': return i18n::translate_c('mother\'s mother\'s brother\'s wife',    'great-aunt');
	case 'motmotsibspo': return i18n::translate_c('mother\'s mother\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'motmotsishus': return i18n::translate_c('mother\'s mother\'s sister\'s husband',  'great-uncle');
	case 'motparbrowif': return i18n::translate_c('mother\'s parent\'s brother\'s wife',    'great-aunt');
	case 'motparsibspo': return i18n::translate_c('mother\'s parent\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'motparsishus': return i18n::translate_c('mother\'s parent\'s sister\'s husband',  'great-uncle');
	case 'parfatbrowif': return i18n::translate_c('parent\'s father\'s brother\'s wife',    'great-aunt');
	case 'parfatsibspo': return i18n::translate_c('parent\'s father\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'parfatsishus': return i18n::translate_c('parent\'s father\'s sister\'s husband',  'great-uncle');
	case 'parmotbrowif': return i18n::translate_c('parent\'s mother\'s brother\'s wife',    'great-aunt');
	case 'parmotsibspo': return i18n::translate_c('parent\'s mother\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'parmotsishus': return i18n::translate_c('parent\'s mother\'s sister\'s husband',  'great-uncle');
	case 'parparbrowif': return i18n::translate_c('parent\'s parent\'s brother\'s wife',    'great-aunt');
	case 'parparsibspo': return i18n::translate_c('parent\'s parent\'s sibling\'s spouse',  'great-aunt/uncle');
	case 'parparsishus': return i18n::translate_c('parent\'s parent\'s sister\'s husband',  'great-uncle');
	case 'fatfatbrodau': return i18n::translate_c('father\'s father\'s brother\'s daughter','first cousin once removed ascending');
	case 'fatfatbroson': return i18n::translate_c('father\'s father\'s brother\'s son',     'first cousin once removed ascending');
	case 'fatfatbrochi': return i18n::translate_c('father\'s father\'s brother\'s child', 'first cousin once removed ascending');
	case 'fatfatsisdau': return i18n::translate_c('father\'s father\'s sister\'s daughter', 'first cousin once removed ascending');
	case 'fatfatsisson': return i18n::translate_c('father\'s father\'s sister\'s son',      'first cousin once removed ascending');
	case 'fatfatsischi': return i18n::translate_c('father\'s father\'s sister\'s child',    'first cousin once removed ascending');
	case 'fatmotbrodau': return i18n::translate_c('father\'s mother\'s brother\'s daughter','first cousin once removed ascending');
	case 'fatmotbroson': return i18n::translate_c('father\'s mother\'s brother\'s son',     'first cousin once removed ascending');
	case 'fatmotbrochi': return i18n::translate_c('father\'s mother\'s brother\'s child',   'first cousin once removed ascending');
	case 'fatmotsisdau': return i18n::translate_c('father\'s mother\'s sister\'s daughter', 'first cousin once removed ascending');
	case 'fatmotsisson': return i18n::translate_c('father\'s mother\'s sister\'s son',      'first cousin once removed ascending');
	case 'fatmotsischi': return i18n::translate_c('father\'s mother\'s sister\'s child',    'first cousin once removed ascending');
	case 'motfatbrodau': return i18n::translate_c('mother\'s father\'s brother\'s daughter','first cousin once removed ascending');
	case 'motfatbroson': return i18n::translate_c('mother\'s father\'s brother\'s son',     'first cousin once removed ascending');
	case 'motfatbrochi': return i18n::translate_c('mother\'s father\'s brother\'s child',   'first cousin once removed ascending');
	case 'motfatsisdau': return i18n::translate_c('mother\'s father\'s sister\'s daughter', 'first cousin once removed ascending');
	case 'motfatsisson': return i18n::translate_c('mother\'s father\'s sister\'s son',      'first cousin once removed ascending');
	case 'motfatsischi': return i18n::translate_c('mother\'s father\'s sister\'s child',    'first cousin once removed ascending');
	case 'motmotbrodau': return i18n::translate_c('mother\'s mother\'s brother\'s daughter','first cousin once removed ascending');
	case 'motmotbroson': return i18n::translate_c('mother\'s mother\'s brother\'s son',     'first cousin once removed ascending');
	case 'motmotbrochi': return i18n::translate_c('mother\'s mother\'s brother\'s child',   'first cousin once removed ascending');
	case 'motmotsisdau': return i18n::translate_c('mother\'s mother\'s sister\'s daughter', 'first cousin once removed ascending');
	case 'motmotsisson': return i18n::translate_c('mother\'s mother\'s sister\'s son',      'first cousin once removed ascending');
	case 'motmotsischi': return i18n::translate_c('mother\'s mother\'s sister\'s child',    'first cousin once removed ascending');
	}

	// Some "special case" level five relationships that have specific names in certain languages
	if (preg_match('/^(mot|fat|par)fatbro(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandfather\'s brother\'s granddaughter',  'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatbro(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandfather\'s brother\'s grandson',       'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatbro(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandfather\'s brother\'s grandchild',     'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatsis(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandfather\'s sister\'s granddaughter',   'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatsis(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandfather\'s sister\'s grandson',        'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatsis(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandfather\'s sister\'s grandchild',      'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatsib(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandfather\'s sibling\'s granddaughter',  'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatsib(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandfather\'s sibling\'s grandson',       'second cousin');
	} else if (preg_match('/^(mot|fat|par)fatsib(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandfather\'s sibling\'s grandchild',     'second cousin');
	} else if (preg_match('/^(mot|fat|par)motbro(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandmother\'s brother\'s granddaughter',  'second cousin');
	} else if (preg_match('/^(mot|fat|par)motbro(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandmother\'s brother\'s grandson',       'second cousin');
	} else if (preg_match('/^(mot|fat|par)motbro(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandmother\'s brother\'s grandchild',     'second cousin');
	} else if (preg_match('/^(mot|fat|par)motsis(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandmother\'s sister\'s granddaughter',   'second cousin');
	} else if (preg_match('/^(mot|fat|par)motsis(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandmother\'s sister\'s grandson',        'second cousin');
	} else if (preg_match('/^(mot|fat|par)motsis(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandmother\'s sister\'s grandchild',      'second cousin');
	} else if (preg_match('/^(mot|fat|par)motsib(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandmother\'s sibling\'s granddaughter',  'second cousin');
	} else if (preg_match('/^(mot|fat|par)motsib(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandmother\'s sibling\'s grandson',       'second cousin');
	} else if (preg_match('/^(mot|fat|par)motsib(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandmother\'s sibling\'s grandchild',     'second cousin');
	} else if (preg_match('/^(mot|fat|par)parbro(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandparent\'s brother\'s granddaughter',  'second cousin');
	} else if (preg_match('/^(mot|fat|par)parbro(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandparent\'s brother\'s grandson',       'second cousin');
	} else if (preg_match('/^(mot|fat|par)parbro(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandparent\'s brother\'s grandchild',     'second cousin');
	} else if (preg_match('/^(mot|fat|par)parsis(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandparent\'s sister\'s granddaughter',   'second cousin');
	} else if (preg_match('/^(mot|fat|par)parsis(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandparent\'s sister\'s grandson',        'second cousin');
	} else if (preg_match('/^(mot|fat|par)parsis(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandparent\'s sister\'s grandchild',      'second cousin');
	} else if (preg_match('/^(mot|fat|par)parsib(son|dau|chi)dau$/', $path)) {
		return i18n::translate_c('grandparent\'s sibling\'s granddaughter',  'second cousin');
	} else if (preg_match('/^(mot|fat|par)parsib(son|dau|chi)son$/', $path)) {
		return i18n::translate_c('grandparent\'s sibling\'s grandson',       'second cousin');
	} else if (preg_match('/^(mot|fat|par)parsib(son|dau|chi)chi$/', $path)) {
		return i18n::translate_c('grandparent\'s sibling\'s grandchild',     'second cousin');
	}

	// Look for generic/pattern relationships.
	// TODO: these are heavily based on english relationship names.
	// We need feedback from other languages to improve this.
	// Dutch has special names for 8 generations of great-great-..., so these need explicit naming
	// Spanish has special names for four but also has two different numbering patterns

	if (preg_match('/^((?:mot|fat|par)+)(bro|sis|sib)$/', $path, $match)) {
	    // siblings of direct ancestors
		$up=strlen($match[1])/3;
		$last=substr($path, -3, 3);
		$bef_last=substr($path, -6, 3);
		switch ($up) {
		case 3:
			switch ($last) {
			case 'bro':
				if ($bef_last=='fat')      return i18n::translate_c('great-grandfather\'s brother', 'great-great-uncle');
				else if ($bef_last=='mot') return i18n::translate_c('great-grandmother\'s brother', 'great-great-uncle');
				else                       return i18n::translate_c('great-grandparent\'s brother', 'great-great-uncle');
			case 'sis': return i18n::translate('great-great-aunt');
			case 'sib': return i18n::translate('great-great-aunt/uncle');
			}
			break;
		case 4:
			switch ($last) {
			case 'bro':
				if ($bef_last=='fat')      return i18n::translate_c('great-great-grandfather\'s brother', 'great-great-great-uncle');
				else if ($bef_last=='mot') return i18n::translate_c('great-great-grandmother\'s brother', 'great-great-great-uncle');
				else                       return i18n::translate_c('great-great-grandparent\'s brother', 'great-great-great-uncle');
			case 'sis': return i18n::translate('great-great-great-aunt');
			case 'sib': return i18n::translate('great-great-great-aunt/uncle');
			}
			break;
		case 5:
			switch ($last) {
			case 'bro':
				if ($bef_last=='fat')      return i18n::translate_c('great-great-great-grandfather\'s brother', 'great x4 uncle');
				else if ($bef_last=='mot') return i18n::translate_c('great-great-great-grandmother\'s brother', 'great x4 uncle');
				else                       return i18n::translate_c('great-great-great-grandparent\'s brother', 'great x4 uncle');
			case 'sis': return i18n::translate('great x4 aunt');
			case 'sib': return i18n::translate('great x4 aunt/uncle');
			}
			break;
		case 6:
			switch ($last) {
			case 'bro':
				if ($bef_last=='fat')      return i18n::translate_c('great x4 grandfather\'s brother', 'great x5 uncle');
				else if ($bef_last=='mot') return i18n::translate_c('great x4 grandmother\'s brother', 'great x5 uncle');
				else                       return i18n::translate_c('great x4 grandparent\'s brother', 'great x5 uncle');
			case 'sis': return i18n::translate('great x5 aunt');
			case 'sib': return i18n::translate('great x5 aunt/uncle');
			}
			break;
		case 7:
			switch ($last) {
			case 'bro':
				if ($bef_last=='fat')      return i18n::translate_c('great x5 grandfather\'s brother', 'great x6 uncle');
				else if ($bef_last=='mot') return i18n::translate_c('great x5 grandmother\'s brother', 'great x6 uncle');
				else                       return i18n::translate_c('great x5 grandparent\'s brother', 'great x6 uncle');
			case 'sis': return i18n::translate('great x6 aunt');
			case 'sib': return i18n::translate('great x6 aunt/uncle');
			}
			break;
		case 8:
			switch ($last) {
			case 'bro':
				if ($bef_last=='fat')      return i18n::translate_c('great x6 grandfather\'s brother', 'great x7 uncle');
				else if ($bef_last=='mot') return i18n::translate_c('great x6 grandmother\'s brother', 'great x7 uncle');
				else                       return i18n::translate_c('great x6 grandparent\'s brother', 'great x7 uncle');
			case 'sis': return i18n::translate('great x7 aunt');
			case 'sib': return i18n::translate('great x7 aunt/uncle');
			}
			break;
		default:
			// Different languages have different rules for naming generations.
			// An english great x12 uncle is a danish great x10 uncle.
			//
			// Need to find out which languages use which rules.
			switch (WT_LOCALE) {
			case 'da':
				switch ($last) {
				case 'bro': return i18n::translate('great x%d uncle', $up-4);
				case 'sis': return i18n::translate('great x%d aunt', $up-4);
				case 'sib': return i18n::translate('great x%d aunt/uncle', $up-4);
				}
			case 'pl':
				switch ($last) {
				case 'bro':
					if ($bef_last=='fat')      return i18n::translate_c('great x(%d-1) grandfather\'s brother', 'great x%d uncle', $up-2);
					else if ($bef_last=='mot') return i18n::translate_c('great x(%d-1) grandmother\'s brother', 'great x%d uncle', $up-2);
					else                       return i18n::translate_c('great x(%d-1) grandparent\'s brother', 'great x%d uncle', $up-2);
				case 'sis': return i18n::translate('great x%d aunt', $up-2);
				case 'sib': return i18n::translate('great x%d aunt/uncle', $up-2);
				}
			case 'it': // Source: Michele Locati
			case 'en':
			default:
				switch ($last) {
				case 'bro': // I18N: if you need a different number for %d, contact the developers, as a code-change is required
				            return i18n::translate('great x%d uncle', $up-2);
				case 'sis': return i18n::translate('great x%d aunt', $up-2);
				case 'sib': return i18n::translate('great x%d aunt/uncle', $up-2);
				}
			}
		}
	}
	if (preg_match('/^(?:bro|sis|sib)((?:son|dau|chi)+)$/', $path, $match)) {
		// direct descendants of siblings
		$down=strlen($match[1])/3+1; // Add one, as we count generations from the common ancestor
		$last=substr($path, -3, 3);
		$first=substr($path, 0, 3);
		switch ($down) {
		case 4:
			switch ($last) {
			case 'son':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-grandson', 'great-great-nephew');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-grandson',  'great-great-nephew');
				} else {
					return i18n::translate_c('(a woman\'s) great-great-nephew', 'great-great-nephew');
				}
			case 'dau':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-granddaughter', 'great-great-niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-granddaughter',  'great-great-niece');
				} else {
					return i18n::translate_c('(a woman\'s) great-great-niece', 'great-great-niece');
				}
			case 'chi':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-grandchild', 'great-great-nephew/niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-grandchild',  'great-great-nephew/niece');
				} else {
					return i18n::translate_c('(a woman\'s) great-great-nephew/niece', 'great-great-nephew/niece');
				}
			}
		case 5:
			switch ($last) {
			case 'son':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-great-grandson', 'great-great-great-nephew');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-great-grandson',  'great-great-great-nephew');
				} else {
					return i18n::translate_c('(a woman\'s) great-great-great-nephew',  'great-great-great-nephew');
				}
			case 'dau':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-great-granddaughter', 'great-great-great-niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-great-granddaughter',  'great-great-great-niece');
				} else {
					return i18n::translate_c('(a woman\'s) great-great-great-niece',  'great-great-great-niece');
				}
			case 'chi':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-great-grandchild', 'great-great-great-nephew/niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-great-grandchild',  'great-great-great-nephew/niece');
				} else {
					return i18n::translate_c('(a woman\'s) great-great-great-nephew/niece',  'great-great-great-nephew/niece');
				}
			}
		case 6:
			switch ($last) {
			case 'son':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-great-great-grandson', 'great x4 nephew');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-great-great-grandson',  'great x4 nephew');
				} else {
					return i18n::translate_c('(a woman\'s) great x4 nephew',  'great x4 nephew');
				}
			case 'dau':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-great-great-granddaughter', 'great x4 niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-great-great-granddaughter',  'great x4 niece');
				} else {
					return i18n::translate_c('(a woman\'s) great x4 niece',  'great x4 niece');
				}
			case 'chi':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great-great-great-grandchild', 'great x4 nephew/niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great-great-great-grandchild',  'great x4 nephew/niece');
				} else {
					return i18n::translate_c('(a woman\'s) great x4 nephew/niece',  'great x4 nephew/niece');
				}
			}
		case 7:
			switch ($last) {
			case 'son':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great x4 grandson', 'great x5 nephew');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great x4 grandson',  'great x5 nephew');
				} else {
					return i18n::translate_c('(a woman\'s) great x5 nephew',  'great x5 nephew');
				}
			case 'dau':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great x4 granddaughter', 'great x5 niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great x4 granddaughter',  'great x5 niece');
				} else {
					return i18n::translate_c('(a woman\'s) great x5 niece',  'great x5 niece');
				}
			case 'chi':
				if ($first=='bro' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) brother\'s great x4 grandchild', 'great x5 nephew/niece');
				} else if ($first=='sis' && $sex1=='M') {
					return i18n::translate_c('(a man\'s) sister\'s great x4 grandchild',  'great x5 nephew/niece');
				} else {
					return i18n::translate_c('(a woman\'s) great x5 nephew/niece',  'great x5 nephew/niece');
				}
			}
		default:
			// Different languages have different rules for naming generations.
			// An english great x12 nephew is a polish great x11 nephew.
			//
			// Need to find out which languages use which rules.
			switch (WT_LOCALE) {
			case 'pl': // Source: Lukasz Wilenski
				switch ($last) {
				case 'son':
					if ($first=='bro' && $sex1=='M') {
						return i18n::translate_c('(a man\'s) brother\'s great x(%d-1) grandson', 'great x%d nephew', $down-3);
					} else if ($first=='sis' && $sex1=='M') {
						return i18n::translate_c('(a man\'s) sister\'s great x(%d-1) grandson',  'great x%d nephew', $down-3);
					} else
						return i18n::translate_c('(a woman\'s) great x%d nephew',  'great x%d nephew', $down-3);
				case 'dau':
					if ($first=='bro' && $sex1=='M') {
						return i18n::translate_c('(a man\'s) brother\'s great x(%d-1) granddaughter', 'great x%d niece', $down-3);
					} else if ($first=='sis' && $sex1=='M') {
						return i18n::translate_c('(a man\'s) sister\'s great x(%d-1) granddaughter',  'great x%d niece', $down-3);
					} else {
						return i18n::translate_c('(a woman\'s) great x%d niece',  'great x%d niece', $down-3);
					}
				case 'chi':
					if ($first=='bro' && $sex1=='M') {
						return i18n::translate_c('(a man\'s) brother\'s great x(%d-1) grandchild', 'great x%d nephew/niece', $down-3);
					} else if ($first=='sis' && $sex1=='M') {
						return i18n::translate_c('(a man\'s) sister\'s great x(%d-1) grandchild',  'great x%d nephew/niece', $down-3);
					} else {
						return i18n::translate_c('(a woman\'s) great x%d nephew/niece',  'great x%d nephew/niece', $down-3);
					}
				}
			case 'it': // Source: Michele Locati.
			case 'en':
			default:
				switch ($last) {
				case 'son': // I18N: if you need a different number for %d, contact the developers, as a code-change is required
				            return i18n::translate('great x%d nephew', $down-2);
				case 'dau': return i18n::translate('great x%d niece', $down-2);
				case 'chi': return i18n::translate('great x%d nephew/niece', $down-2);
				}
			}
		}
	}
	if (preg_match('/^((?:mot|fat|par)*)$/', $path, $match)) {
		// direct ancestors
		$up=strlen($match[1])/3;
		$last=substr($path, -3, 3);
		switch ($up) {
		case 4:
			switch ($last) {
			case 'mot': return i18n::translate('great-great-grandmother');
			case 'fat': return i18n::translate('great-great-grandfather');
			case 'par': return i18n::translate('great-great-grandparent');
			}
			break;
		case 5:
			switch ($last) {
			case 'mot': return i18n::translate('great-great-great-grandmother');
			case 'fat': return i18n::translate('great-great-great-grandfather');
			case 'par': return i18n::translate('great-great-great-grandparent');
			}
			break;
		case 6:
			switch ($last) {
			case 'mot': return i18n::translate('great x4 grandmother');
			case 'fat': return i18n::translate('great x4 grandfather');
			case 'par': return i18n::translate('great x4 grandparent');
			}
			break;
		case 7:
			switch ($last) {
			case 'mot': return i18n::translate('great x5 grandmother');
			case 'fat': return i18n::translate('great x5 grandfather');
			case 'par': return i18n::translate('great x5 grandparent');
			}
			break;
		case 8:
			switch ($last) {
			case 'mot': return i18n::translate('great x6 grandmother');
			case 'fat': return i18n::translate('great x6 grandfather');
			case 'par': return i18n::translate('great x6 grandparent');
			}
			break;
		case 9:
			switch ($last) {
			case 'mot': return i18n::translate('great x7 grandmother');
			case 'fat': return i18n::translate('great x7 grandfather');
			case 'par': return i18n::translate('great x7 grandparent');
			}
			break;
		default:
			// Different languages have different rules for naming generations.
			// An english great x12 grandfather is a danish great x11 grandfather.
			//
			// Need to find out which languages use which rules.
			switch (WT_LOCALE) {
			case 'da': // Source: Patrick Sorensen
				switch ($last) {
				case 'mot': return i18n::translate('great x%d grandmother', $up-3);
				case 'fat': return i18n::translate('great x%d grandfather', $up-3);
				case 'par': return i18n::translate('great x%d grandparent', $up-3);
				}
			case 'it': // Source: Michele Locati
			case 'es': // Source: Wes Groleau
				switch ($last) {
				case 'mot': return i18n::translate('great x%d grandmother', $up);
				case 'fat': return i18n::translate('great x%d grandfather', $up);
				case 'par': return i18n::translate('great x%d grandparent', $up);
				}
			case 'en':
			default:
				switch ($last) {
				case 'mot': // I18N: if you need a different number for %d, contact the developers, as a code-change is required
				            return i18n::translate('great x%d grandmother', $up-2);
				case 'fat': return i18n::translate('great x%d grandfather', $up-2);
				case 'par': return i18n::translate('great x%d grandparent', $up-2);
				}
			}
		}
	}
	if (preg_match('/^((?:son|dau|chi)*)$/', $path, $match)) {
        // direct descendants
		$up=strlen($match[1])/3;
		$last=substr($path, -3, 3);
		switch ($up) {
		case 4:
			switch ($last) {
			case 'son': return i18n::translate('great-great-grandson');
			case 'dau': return i18n::translate('great-great-granddaughter');
			case 'chi': return i18n::translate('great-great-grandchild');
			}
			break;
		case 5:
			switch ($last) {
			case 'son': return i18n::translate('great-great-great-grandson');
			case 'dau': return i18n::translate('great-great-great-granddaughter');
			case 'chi': return i18n::translate('great-great-great-grandchild');
			}
			break;
		case 6:
			switch ($last) {
			case 'son': return i18n::translate('great x4 grandson');
			case 'dau': return i18n::translate('great x4 granddaughter');
			case 'chi': return i18n::translate('great x4 grandchild');
			}
			break;
		case 7:
			switch ($last) {
			case 'son': return i18n::translate('great x5 grandson');
			case 'dau': return i18n::translate('great x5 granddaughter');
			case 'chi': return i18n::translate('great x5 grandchild');
			}
			break;
		case 8:
			switch ($last) {
			case 'son': return i18n::translate('great x6 grandson');
			case 'dau': return i18n::translate('great x6 granddaughter');
			case 'chi': return i18n::translate('great x6 grandchild');
			}
			break;
		case 9:
			switch ($last) {
			case 'son': return i18n::translate('great x7 grandson');
			case 'dau': return i18n::translate('great x7 granddaughter');
			case 'chi': return i18n::translate('great x7 grandchild');
			}
			break;
		default:
			// Different languages have different rules for naming generations.
			// An english great x12 grandson is a danish great x11 grandson.
			//
			// Need to find out which languages use which rules.
			switch (WT_LOCALE) {
			case 'da': // Source: Patrick Sorensen
				switch ($last) {
				case 'mot': return i18n::translate('great x%d grandson',      $up-3);
				case 'fat': return i18n::translate('great x%d granddaughter', $up-3);
				case 'par': return i18n::translate('great x%d grandchild',    $up-3);
				}
			case 'en':
			case 'it': // Source: Michele Locati
			case 'es': // Source: Wes Groleau (adding doesn't change behavior, but needs to be better researched)
			default:
				switch ($last) {

				case 'son': // I18N: if you need a different number for %d, contact the developers, as a code-change is required
				            return i18n::translate('great x%d grandson',      $up-2);
				case 'dau': return i18n::translate('great x%d granddaughter', $up-2);
				case 'chi': return i18n::translate('great x%d grandchild',    $up-2);
				}
			}
		}
	}
	if (preg_match('/^((?:mot|fat|par)+)(?:bro|sis|sib)((?:son|dau|chi)+)$/', $path, $match)) {
		// cousins in English
		$up  =strlen($match[1])/3;
		$down=strlen($match[2])/3;
		$last=substr($path, -3, 3);
		$cousin=min($up, $down);  // Moved out of switch--en/default case--so that
		$removed=abs($down-$up);  // Spanish (and other languages) can use it, too

		// Different languages have different rules for naming cousins.  For example,
		// an english "second cousin once removed" is a polish "cousin of 7th degree".
		//
		// Need to find out which languages use which rules.
		switch (WT_LOCALE) {
		case 'pl': // Source: Lukasz Wilenski
			switch ($last) {
			case 'son': return cousin_name($up+$down+2, 'M');
			case 'dau': return cousin_name($up+$down+2, 'F');
			case 'chi': return cousin_name($up+$down+2, 'U');
			}
			break;
		case 'it':
			// Source: Michele Locati.  See italian_cousins_names.zip
			// http://webtrees.net/forums/8-translation/1200-great-xn-grandparent?limit=6&start=6
			switch ($last) {
			case 'son': return cousin_name($up+$down-3, 'M');
			case 'dau': return cousin_name($up+$down-3, 'F');
			case 'chi': return cousin_name($up+$down-3, 'U');
			}
			break;
		case 'en': // See: http://en.wikipedia.org/wiki/File:CousinTree.svg
		default:
			switch ($removed) {
			case 0:
				switch ($last) {
				case 'son': return cousin_name($cousin, 'M');
				case 'dau': return cousin_name($cousin, 'F');
				case 'chi': return cousin_name($cousin, 'U');
				}
			case 1:
				if ($up>$down) {
					switch ($last) {
					case 'son': /* I18N: %s="fifth cousin", etc. http://www.ancestry.com/learn/library/article.aspx?article=2856 */
						return i18n::translate('%s once removed ascending', cousin_name($cousin, 'M'));
					case 'dau':
						return i18n::translate('%s once removed ascending', cousin_name($cousin, 'F'));
					case 'chi':
						return i18n::translate('%s once removed ascending', cousin_name($cousin, 'U'));
					}
				} else {
					switch ($last) {
					case 'son': /* I18N: %s="fifth cousin", etc. http://www.ancestry.com/learn/library/article.aspx?article=2856 */
						return i18n::translate('%s once removed descending', cousin_name($cousin, 'M'));
					case 'dau':
						return i18n::translate('%s once removed descending', cousin_name($cousin, 'F'));
					case 'chi':
						return i18n::translate('%s once removed descending', cousin_name($cousin, 'U'));
					}
				}
			case 2:
				if ($up>$down) {
					switch ($last) {
					case 'son': /* I18N: %s="fifth cousin", etc. */
						return i18n::translate('%s twice removed ascending', cousin_name($cousin, 'M'));
					case 'dau':
						return i18n::translate('%s twice removed ascending', cousin_name($cousin, 'F'));
					case 'chi':
						return i18n::translate('%s twice removed ascending', cousin_name($cousin, 'U'));
					}
				} else {
					switch ($last) {
					case 'son': /* I18N: %s="fifth cousin", etc. */
						return i18n::translate('%s twice removed descending', cousin_name($cousin, 'M'));
					case 'dau':
						return i18n::translate('%s twice removed descending', cousin_name($cousin, 'F'));
					case 'chi':
						return i18n::translate('%s twice removed descending', cousin_name($cousin, 'U'));
					}
				}
			case 3:
				if ($up>$down) {
					switch ($last) {
					case 'son': /* I18N: %s="fifth cousin", etc. */
						return i18n::translate('%s thrice removed ascending', cousin_name($cousin, 'M'));
					case 'dau':
						return i18n::translate('%s thrice removed ascending', cousin_name($cousin, 'F'));
					case 'chi':
						return i18n::translate('%s thrice removed ascending', cousin_name($cousin, 'U'));
					}
				} else {
					switch ($last) {
					case 'son': /* I18N: %s="fifth cousin", etc. */
						return i18n::translate('%s thrice removed descending', cousin_name($cousin, 'M'));
					case 'dau':
						return i18n::translate('%s thrice removed descending', cousin_name($cousin, 'F'));
					case 'chi':
						return i18n::translate('%s thrice removed descending', cousin_name($cousin, 'U'));
					}
				}
			default:
				if ($up>$down) {
					switch ($last) {
					case 'son': /* I18N: %1$s="fifth cousin", etc., %2$d>=4 */
						return i18n::translate('%1$s %2$d times removed ascending', cousin_name($cousin, 'M'), $removed);
					case 'dau':
						return i18n::translate('%1$s %2$d times removed ascending', cousin_name($cousin, 'F'), $removed);
					case 'chi':
						return i18n::translate('%1$s %2$d times removed ascending', cousin_name($cousin, 'U'), $removed);
					}
				} else {
					switch ($last) {
					case 'son': /* I18N: %1$s="fifth cousin", etc., %2$d>=4 */
						return i18n::translate('%1$s %2$d times removed descending', cousin_name($cousin, 'M'), $removed);
					case 'dau':
						return i18n::translate('%1$s %2$d times removed descending', cousin_name($cousin, 'F'), $removed);
					case 'chi':
						return i18n::translate('%1$s %2$d times removed descending', cousin_name($cousin, 'U'), $removed);
					}
				}
			}
		}
	}

	// Try to split the relationship into sub-relationships, e.g., third-cousin's wife's fourth-cousin.
	// This next block of code is experimental.  If it doesn't work, we can remove it.....
	if (preg_match('/^(.*)(hus|wif|spo)(.*)/', $path, $match)) {
		if ($match[1]=='') {
			return i18n::translate(
				// I18N: A complex relationship, such as "husband's great-uncle"
				'%1$s\'s %2$s',
				get_relationship_name_from_path($match[2], null, null), // TODO: need the actual people
				get_relationship_name_from_path($match[3], null, null)
			);
		} elseif ($match[3]=='') {
			return i18n::translate(
				// I18N: A complex relationship, such as "second cousin's wife"
				'%1$s\'s %2$s',
				get_relationship_name_from_path($match[1], null, null),
				get_relationship_name_from_path($match[2], null, null)
			);
		} else {
			return i18n::translate(
				// I18N: A complex relationship, such as "second cousin's husband's third cousin"
				'%1$s\'s %2$s\'s %3$s',
				get_relationship_name_from_path($match[1], null, null),
				get_relationship_name_from_path($match[2], null, null),
				get_relationship_name_from_path($match[3], null, null)
			);
		}
	}

	// We don't have a specific name for this relationship, and we can't match it with a pattern.
	// Just spell it out.

	// TODO: long relationships are a bit ridiculous - although technically correct.
	// Perhaps translate long paths as "a distant blood relative", or "a distant relative by marriage"
	switch (substr($path, -3, 3)) {
	case 'mot': $relationship=i18n::translate('mother'  ); break;
	case 'fat': $relationship=i18n::translate('father'  ); break;
	case 'par': $relationship=i18n::translate('parent'  ); break;
	case 'hus': $relationship=i18n::translate('husband' ); break;
	case 'wif': $relationship=i18n::translate('wife'    ); break;
	case 'spo': $relationship=i18n::translate('spouse'  ); break;
	case 'bro': $relationship=i18n::translate('brother' ); break;
	case 'sis': $relationship=i18n::translate('sister'  ); break;
	case 'sib': $relationship=i18n::translate('sibling' ); break;
	case 'son': $relationship=i18n::translate('son'     ); break;
	case 'dau': $relationship=i18n::translate('daughter'); break;
	case 'chi': $relationship=i18n::translate('child'   ); break;
	}
	while (($path=substr($path, 0, strlen($path)-3))!='') {
		switch (substr($path, -3, 3)) {
			// I18N: These strings are used to build paths of relationships, such as "father's wife's husband's brother"
		case 'mot': $relationship=i18n::translate('mother\'s %s',   $relationship); break;
		case 'fat': $relationship=i18n::translate('father\'s %s',   $relationship); break;
		case 'par': $relationship=i18n::translate('parent\'s %s',   $relationship); break;
		case 'hus': $relationship=i18n::translate('husband\'s %s',  $relationship); break;
		case 'wif': $relationship=i18n::translate('wife\'s %s',     $relationship); break;
		case 'spo': $relationship=i18n::translate('spouse\'s %s',   $relationship); break;
		case 'bro': $relationship=i18n::translate('brother\'s %s',  $relationship); break;
		case 'sis': $relationship=i18n::translate('sister\'s %s',   $relationship); break;
		case 'sib': $relationship=i18n::translate('sibling\'s %s',  $relationship); break;
		case 'son': $relationship=i18n::translate('son\'s %s',      $relationship); break;
		case 'dau': $relationship=i18n::translate('daughter\'s %s', $relationship); break;
		case 'chi': $relationship=i18n::translate('child\'s %s',    $relationship); break;
		}
	}
	return $relationship;
}

/**
 * get theme names
 *
 * function to get the names of all of the themes as an array
 * it searches the themes directory and reads the name from the theme_name variable
 * in the theme.php file.
 * @return array and array of theme names and their corresponding directory
 */
function get_theme_names() {
	static $themes;
	if ($themes===null) {
		$themes = array();
		$d = dir("themes");
		while (false !== ($entry = $d->read())) {
			if ($entry{0}!="." && $entry!="CVS" && !stristr($entry, "svn") && is_dir(WT_ROOT.'themes/'.$entry) && file_exists(WT_ROOT.'themes/'.$entry.'/theme.php')) {
				$themefile = implode("", file(WT_ROOT.'themes/'.$entry.'/theme.php'));
				$tt = preg_match("/theme_name\s*=\s*\"(.*)\";/", $themefile, $match);
				if ($tt>0)
					$themename = trim($match[1]);
				else
					$themename = "themes/$entry";
				$themes[$themename] = "themes/$entry/";
			}
		}
		$d->close();
		uksort($themes, "utf8_strcasecmp");
	}
	return $themes;
}

/**
 * decode a filename
 *
 * windows doesn't use UTF-8 for its file system so we have to decode the filename
 * before it can be used on the filesystem
 */
function filename_decode($filename) {
	if (DIRECTORY_SEPARATOR=='\\')
		return utf8_decode($filename);
	else
		return $filename;
}

/**
 * encode a filename
 *
 * windows doesn't use UTF-8 for its file system so we have to encode the filename
 * before it can be used
 */
function filename_encode($filename) {
	if (DIRECTORY_SEPARATOR=='\\')
		return utf8_encode($filename);
	else
		return $filename;
}

////////////////////////////////////////////////////////////////////////////////
// Remove empty and duplicate values from a URL query string
////////////////////////////////////////////////////////////////////////////////
function normalize_query_string($query) {
	$components=array();
	foreach (preg_split('/(^\?|\&(amp;)*)/', urldecode($query), -1, PREG_SPLIT_NO_EMPTY) as $component)
		if (strpos($component, '=')!==false) {
			list ($key, $data)=explode('=', $component, 2);
			if (!empty($data)) $components[$key]=$data;
		}
	$new_query='';
	foreach ($components as $key=>$data)
		$new_query.=(empty($new_query)?'?':'&amp;').$key.'='.$data;

	return $new_query;
}

function getfilesize($bytes) {
	if ($bytes>=1099511627776) {
		return round($bytes/1099511627776, 2)." TB";
	}
	if ($bytes>=1073741824) {
		return round($bytes/1073741824, 2)." GB";
	}
	if ($bytes>=1048576) {
		return round($bytes/1048576, 2)." MB";
	}
	if ($bytes>=1024) {
		return round($bytes/1024, 2)." KB";
	}
	return $bytes." B";
}

/**
 * array merge function for PGV
 * the PHP array_merge function will reindex all numerical indexes
 * This function should only be used for associative arrays
 * @param array $array1
 * @param array $array2
 */
function wt_array_merge($array1, $array2) {
	foreach ($array2 as $key=>$value) {
		$array1[$key] = $value;
	}
	return $array1;
}

/**
 * checks if the value is in an array recursively
 * @param string $needle
 * @param array $haystack
 */
function in_arrayr($needle, $haystack) {
	foreach ($haystack as $v) {
		if ($needle == $v) return true;
		else if (is_array($v)) {
			if (in_arrayr($needle, $v) === true) return true;
		}
	}
	return false;
}

/**
 * function to build an URL querystring from GET or POST variables
 * @return string
 */
function get_query_string() {
	$qstring = "";
	if (!empty($_GET)) {
		foreach ($_GET as $key => $value) {
			if ($key != "view") {
				if (!is_array($value)) {
					$qstring .= "{$key}={$value}&";
				} else {
					foreach ($value as $k=>$v) {
						$qstring .= "{$key}[{$k}]={$v}&";
					}
				}
			}
		}
	} else {
		if (!empty($_POST)) {
			foreach ($_POST as $key => $value) {
				if ($key != "view") {
					if (!is_array($value)) {
						$qstring .= "{$key}={$value}&";
					} else {
						foreach ($value as $k=>$v) {
							if (!is_array($v)) {
								$qstring .= "{$key}[{$k}]={$v}&";
							}
						}
					}
				}
			}
		}
	}
	$qstring = rtrim($qstring, "&"); // Remove trailing "&"
	return encode_url($qstring);
}

//This function works with a specified generation limit.  It will completely fill
//the pdf without regard to whether a known person exists in each generation.
//ToDo: If a known individual is found in a generation, add prior empty positions
//and add remaining empty spots automatically.
function add_ancestors(&$list, $pid, $children=false, $generations=-1, $show_empty=false) {
	$total_num_skipped = 0;
	$skipped_gen = 0;
	$num_skipped = 0;
	$genlist = array($pid);
	$list[$pid]->generation = 1;
	while (count($genlist)>0) {
		$id = array_shift($genlist);
		if (strpos($id, "empty")===0) continue; // id can be something like "empty7"
		$person = Person::getInstance($id);
		$famids = $person->getChildFamilies();
		if (count($famids)>0) {
			if ($show_empty) {
				for ($i=0;$i<$num_skipped;$i++) {
					$list["empty" . $total_num_skipped] = new Person('');
					$list["empty" . $total_num_skipped]->generation = $list[$id]->generation+1;
					array_push($genlist, "empty" . $total_num_skipped);
					$total_num_skipped++;
				}
			}
			$num_skipped = 0;
			foreach ($famids as $famid => $family) {
				$husband = $family->getHusband();
				$wife = $family->getWife();
				if ($husband) {
					$list[$husband->getXref()] = $husband;
					$list[$husband->getXref()]->generation = $list[$id]->generation+1;
				} elseif ($show_empty) {
					$list["empty" . $total_num_skipped] = new Person('');
					$list["empty" . $total_num_skipped]->generation = $list[$id]->generation+1;
				}
				if ($wife) {
					$list[$wife->getXref()] = $wife;
					$list[$wife->getXref()]->generation = $list[$id]->generation+1;
				} elseif ($show_empty) {
					$list["empty" . $total_num_skipped] = new Person('');
					$list["empty" . $total_num_skipped]->generation = $list[$id]->generation+1;
				}
				if ($generations == -1 || $list[$id]->generation+1 < $generations) {
					$skipped_gen = $list[$id]->generation+1;
					if ($husband) {
						array_push($genlist, $husband->getXref());
					} elseif ($show_empty) {
						array_push($genlist, "empty" . $total_num_skipped);
					}
					if ($wife) {
						array_push($genlist, $wife->getXref());
					} elseif ($show_empty) {
						array_push($genlist, "empty" . $total_num_skipped);
					}
				}
				$total_num_skipped++;
				if ($children) {
					$childs = $family->getChildren();
					foreach($childs as $child) {
						$list[$child->getXref()] = $child;
						if (isset($list[$id]->generation))
							$list[$child->getXref()]->generation = $list[$id]->generation;
						else
							$list[$child->getXref()]->generation = 1;
					}
				}
			}
		} else
			if ($show_empty) {
				if ($skipped_gen > $list[$id]->generation) {
					$list["empty" . $total_num_skipped] = new Person('');
					$list["empty" . $total_num_skipped]->generation = $list[$id]->generation+1;
					$total_num_skipped++;
					$list["empty" . $total_num_skipped] = new Person('');
					$list["empty" . $total_num_skipped]->generation = $list[$id]->generation+1;
					array_push($genlist, "empty" . ($total_num_skipped - 1));
					array_push($genlist, "empty" . $total_num_skipped);
					$total_num_skipped++;
				} else
					$num_skipped += 2;
		}

	}
}

//--- copied from class_reportpdf.php
function add_descendancy(&$list, $pid, $parents=false, $generations=-1) {
	$person = Person::getInstance($pid);
	if ($person==null) return;
	if (!isset($list[$pid])) {
		$list[$pid] = $person;
	}
	if (!isset($list[$pid]->generation)) {
		$list[$pid]->generation = 0;
	}
	$famids = $person->getSpouseFamilies();
	if (count($famids)>0) {
		foreach ($famids as $famid => $family) {
			if ($family) {
				if ($parents) {
					$husband = $family->getHusband();
					$wife = $family->getWife();
					if ($husband) {
						$list[$husband->getXref()] = $husband;
						if (isset($list[$pid]->generation))
							$list[$husband->getXref()]->generation = $list[$pid]->generation-1;
						else
							$list[$husband->getXref()]->generation = 1;
					}
					if ($wife) {
						$list[$wife->getXref()] = $wife;
						if (isset($list[$pid]->generation))
							$list[$wife->getXref()]->generation = $list[$pid]->generation-1;
						else
							$list[$wife->getXref()]->generation = 1;
					}
				}
				$children = $family->getChildren();
				foreach($children as $child) {
					if ($child) {
						$list[$child->getXref()] = $child;
						if (isset($list[$pid]->generation))
							$list[$child->getXref()]->generation = $list[$pid]->generation+1;
						else
							$list[$child->getXref()]->generation = 2;
					}
				}
				if ($generations == -1 || $list[$pid]->generation+1 < $generations) {
					foreach($children as $child) {
						add_descendancy($list, $child->getXref(), $parents, $generations); // recurse on the childs family
					}
				}
			}
		}
	}
}

/**
 * get the next available xref
 * calculates the next available XREF id for the given type of record
 * @param string $type the type of record, defaults to 'INDI'
 * @return string
 */
function get_new_xref($type='INDI', $ged_id=WT_GED_ID) {
	global $SOURCE_ID_PREFIX, $REPO_ID_PREFIX, $MEDIA_ID_PREFIX, $FAM_ID_PREFIX, $GEDCOM_ID_PREFIX;

	switch ($type) {
	case "INDI":
		$prefix = $GEDCOM_ID_PREFIX;
		break;
	case "FAM":
		$prefix = $FAM_ID_PREFIX;
		break;
	case "OBJE":
		$prefix = $MEDIA_ID_PREFIX;
		break;
	case "SOUR":
		$prefix = $SOURCE_ID_PREFIX;
		break;
	case "REPO":
		$prefix = $REPO_ID_PREFIX;
		break;
	default:
		$prefix = $type{0};
		break;
	}

	$num=
		WT_DB::prepare("SELECT next_id FROM `##next_id` WHERE record_type=? AND gedcom_id=?")
		->execute(array($type, $ged_id))
		->fetchOne();

	// TODO?  If a gedcom file contains *both* inline and object based media, then
	// we could be generating an XREF that we will find later.  Need to scan the
	// entire gedcom for them?

	if (is_null($num)) {
		$num = 1;
		WT_DB::prepare("INSERT INTO `##next_id` (gedcom_id, record_type, next_id) VALUES(?, ?, 1)")
			->execute(array($ged_id, $type));
	}

	while (find_gedcom_record($prefix.$num, $ged_id, true)) {
		// Applications such as ancestry.com generate XREFs with numbers larger than
		// PHP's signed integer.  MySQL can handle large integers.
		$num=WT_DB::prepare("SELECT 1+?")->execute(array($num))->fetchOne();
	}

	//-- the key is the prefix and the number
	$key = $prefix.$num;

	//-- update the next id number in the DB table
	WT_DB::prepare("UPDATE `##next_id` SET next_id=? WHERE record_type=? AND gedcom_id=?")
		->execute(array($num+1, $type, $ged_id));
	return $key;
}

/**
 * check if the given string has UTF-8 characters
 *
 */
function has_utf8($string) {
	$len = strlen($string);
	for ($i=0; $i<$len; $i++) {
		$letter = substr($string, $i, 1);
		$ord = ord($letter);
		if ($ord==95 || $ord>=195)
			return true;
	}
	return false;
}

/**
 * determines whether the passed in filename is a link to an external source (i.e. contains '://')
 */
function isFileExternal($file) {
	return strpos($file, '://') !== false;
}

/*
 * Get useful information on how to handle this media file
 */
function mediaFileInfo($fileName, $thumbName, $mid, $name='', $notes='', $obeyViewerOption=true) {
	global $THUMBNAIL_WIDTH, $WT_IMAGES;
	global $LB_URL_WIDTH, $LB_URL_HEIGHT;
	global $GEDCOM, $USE_MEDIA_VIEWER, $USE_MEDIA_FIREWALL, $MEDIA_FIREWALL_THUMBS;

	$result = array();

	// -- Classify the incoming media file
	if (preg_match('~^https?://~i', $fileName)) $type = 'url_';
	else $type = 'local_';
	if ((preg_match('/\.flv$/i', $fileName) || preg_match('~^https?://.*\.youtube\..*/watch\?~i', $fileName)) && is_dir(WT_ROOT.'js/jw_player')) {
		$type .= 'flv';
	} else if (preg_match('~^https?://picasaweb*\.google\..*/.*/~i', $fileName)) {
		$type .= 'picasa';
	} else if (preg_match('/\.(jpg|jpeg|gif|png)$/i', $fileName)) {
		$type .= 'image';
	} else if (preg_match('/\.(pdf|avi|txt)$/i', $fileName)) {
		$type .= 'page';
	} else if (preg_match('/\.mp3$/i', $fileName)) {
		$type .= 'audio';
	} else if (preg_match('/\.wmv$/i', $fileName)) {
		$type .= 'wmv';
	} else if (strpos($fileName, 'http://maps.google.')===0) {
		$type .= 'streetview';
	} else {
		$type .= 'other';
	}
	// $type is now: (url | local) _ (flv | picasa | image | page | audio | wmv | streetview |other)
	$result['type'] = $type;

	// -- Determine the correct URL to open this media file
	while (true) {
		if (WT_USE_LIGHTBOX) {
			// Lightbox is installed
			require_once WT_ROOT.'modules/lightbox/lb_defaultconfig.php';
			switch ($type) {
			case 'url_flv':
				$url = encode_url('js/jw_player/flvVideo.php?flvVideo='.($fileName)) . "\" rel='clearbox(500, 392, click)' rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_COMPAT, 'UTF-8')) . "::" . htmlspecialchars($notes, ENT_COMPAT, 'UTF-8');
				break 2;
			case 'local_flv':
				$url = encode_url('js/jw_player/flvVideo.php?flvVideo='.(WT_SERVER_NAME.WT_SCRIPT_PATH.$fileName)) . "\" rel='clearbox(500, 392, click)' rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_COMPAT, 'UTF-8')) . "::" . htmlspecialchars($notes, ENT_COMPAT, 'UTF-8');
				break 2;
			case 'url_wmv':
				$url = encode_url('js/jw_player/wmvVideo.php?wmvVideo='.($fileName)) . "\" rel='clearbox(500, 392, click)' rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_COMPAT, 'UTF-8')) . "::" . htmlspecialchars($notes, ENT_COMPAT, 'UTF-8');
				break 2;
			case 'local_audio':
			case 'local_wmv':
				$url = encode_url('js/jw_player/wmvVideo.php?wmvVideo='.(WT_SERVER_NAME.WT_SCRIPT_PATH.$fileName)) . "\" rel='clearbox(500, 392, click)' rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_COMPAT, 'UTF-8')) . "::" . htmlspecialchars($notes, ENT_COMPAT, 'UTF-8');
				break 2;
			case 'url_image':
			case 'local_image':
				$url = encode_url($fileName) . "\" rel=\"clearbox[general]\" rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_COMPAT, 'UTF-8')) . "::" . htmlspecialchars($notes, ENT_COMPAT, 'UTF-8');
				break 2;
			case 'url_picasa':
			case 'url_page':
			case 'url_other':
			case 'local_page':
			// case 'local_other':
				$url = encode_url($fileName) . "\" rel='clearbox({$LB_URL_WIDTH}, {$LB_URL_HEIGHT}, click)' rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_COMPAT, 'UTF-8')) . "::" . htmlspecialchars($notes, ENT_COMPAT, 'UTF-8');
				break 2;
			case 'url_streetview':
				if (WT_SCRIPT_NAME != "media.php") {
					echo  '<iframe style="float:left; padding:5px;" width="264" height="176" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'. $fileName. '&amp;output=svembed"></iframe>';
				}
				break 2;
			}
		}

		// Lightbox is not installed or Lightbox is not appropriate for this media type
		switch ($type) {
		case 'url_flv':
			$url = "javascript:;\" onclick=\" var winflv = window.open('".encode_url('js/jw_player/flvVideo.php?flvVideo='.($fileName)) . "', 'winflv', 'width=500, height=392, left=600, top=200'); if (window.focus) {winflv.focus();}";
			break 2;
		case 'local_flv':
			$url = "javascript:;\" onclick=\" var winflv = window.open('".encode_url('js/jw_player/flvVideo.php?flvVideo='.(WT_SERVER_NAME.WT_SCRIPT_PATH.$fileName)) . "', 'winflv', 'width=500, height=392, left=600, top=200'); if (window.focus) {winflv.focus();}";
			break 2;
		case 'url_wmv':
			$url = "javascript:;\" onclick=\" var winwmv = window.open('".encode_url('js/jw_player/wmvVideo.php?wmvVideo='.($fileName)) . "', 'winwmv', 'width=500, height=392, left=600, top=200'); if (window.focus) {winwmv.focus();}";
			break 2;
		case 'local_wmv':
		case 'local_audio':
			$url = "javascript:;\" onclick=\" var winwmv = window.open('".encode_url('js/jw_player/wmvVideo.php?wmvVideo='.(WT_SERVER_NAME.WT_SCRIPT_PATH.$fileName)) . "', 'winwmv', 'width=500, height=392, left=600, top=200'); if (window.focus) {winwmv.focus();}";
			break 2;
		case 'url_image':
			$imgsize = findImageSize($fileName);
			$imgwidth = $imgsize[0]+40;
			$imgheight = $imgsize[1]+150;
			$url = "javascript:;\" onclick=\"var winimg = window.open('".encode_url($fileName)."', 'winimg', 'width=".$imgwidth.", height=".$imgheight.", left=200, top=200'); if (window.focus) {winimg.focus();}";
			break 2;
		case 'url_picasa':
		case 'url_page':
		case 'url_other':
		case 'local_other';
			$url = "javascript:;\" onclick=\"var winurl = window.open('".encode_url($fileName)."', 'winurl', 'width=900, height=600, left=200, top=200'); if (window.focus) {winurl.focus();}";
			break 2;
		case 'local_page':
			$url = "javascript:;\" onclick=\"var winurl = window.open('".encode_url(WT_SERVER_NAME.WT_SCRIPT_PATH.$fileName)."', 'winurl', 'width=900, height=600, left=200, top=200'); if (window.focus) {winurl.focus();}";
			break 2;
		case 'url_streetview':
			echo '<iframe style="float:left; padding:5px;" width="264" height="176" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="', $fileName, '&amp;output=svembed"></iframe>';
			$url = "#";
			break 2;
		}
		if ($USE_MEDIA_VIEWER && $obeyViewerOption) {
			$url = encode_url('mediaviewer.php?mid='.$mid);
		} else {
			$imgsize = findImageSize($fileName);
			$imgwidth = $imgsize[0]+40;
			$imgheight = $imgsize[1]+150;
			$url = "javascript:;\" onclick=\"return openImage('".urlencode($fileName)."', $imgwidth, $imgheight);";
		}
		break;
	}
	// At this point, $url describes how to handle the image when its thumbnail is clicked
	if ($type == 'url_streetview') {
		$result['url'] = "#";
	} else {
		$result['url'] = $url;
	}

	// -- Determine the correct thumbnail or pseudo-thumbnail
	$width = '';
	switch ($type) {
		case 'url_flv':
			$thumb = isset($WT_IMAGES["media_flashrem"]) ? $WT_IMAGES["media_flashrem"] : 'images/media/flashrem.png';
			break;
		case 'local_flv':
			$thumb = isset($WT_IMAGES["media_flash"]) ? $WT_IMAGES["media_flash"] : 'images/media/flash.png';
			break;
		case 'url_wmv':
			$thumb = isset($WT_IMAGES["media_wmvrem"]) ? $WT_IMAGES["media_wmvrem"] : 'images/media/wmvrem.png';
			break;
		case 'local_wmv':
			$thumb = isset($WT_IMAGES["media_wmv"]) ? $WT_IMAGES["media_wmv"] : 'images/media/wmv.png';
			break;
		case 'url_picasa':
			$thumb = isset($WT_IMAGES["media_picasa"]) ? $WT_IMAGES["media_picasa"] : 'images/media/picasa.png';
			break;
		case 'url_page':
		case 'url_other':
			$thumb = isset($WT_IMAGES["media_globe"]) ? $WT_IMAGES["media_globe"] : 'images/media/globe.png';
			break;
		case 'local_page':
			$thumb = isset($WT_IMAGES["media_doc"]) ? $WT_IMAGES["media_doc"] : 'images/media/doc.gif';
			break;
		case 'url_audio':
		case 'local_audio':
			$thumb = isset($WT_IMAGES["media_audio"]) ? $WT_IMAGES["media_audio"] : 'images/media/audio.png';
			break;
		case 'url_streetview':
			$thumb = null;
			break;
		default:
			$thumb = $thumbName;
			if (substr($type, 0, 4)=='url_') {
				$width = ' width="'.$THUMBNAIL_WIDTH.'"';
			}
	}

	// -- Use an overriding thumbnail if one has been provided
	// Don't accept any overriding thumbnails that are in the "images" or "themes" directories
	if (substr($thumbName, 0, 7)!='images/' && substr($thumbName, 0, 7)!='themes/') {
		if ($USE_MEDIA_FIREWALL && $MEDIA_FIREWALL_THUMBS) {
			$tempThumbName = get_media_firewall_path($thumbName);
		} else {
			$tempThumbName = $thumbName;
		}
		if (file_exists($tempThumbName)) {
			$thumb = $thumbName;
		}
	}

	// -- Use the theme-specific media icon if nothing else works
	$realThumb = $thumb;
	if (substr($type, 0, 6)=='local_' && !file_exists($thumb)) {
		if (!$USE_MEDIA_FIREWALL || !$MEDIA_FIREWALL_THUMBS) {
			$thumb = $WT_IMAGES['media'];
			$realThumb = $thumb;
		} else {
			$realThumb = get_media_firewall_path($thumb);
			if (!file_exists($realThumb)) {
				$thumb = $WT_IMAGES['media'];
				$realThumb = $thumb;
			}
		}
		$width = '';
	}

	// At this point, $width, $realThumb, and $thumb describe the thumbnail to be displayed
	$result['thumb'] = $thumb;
	$result['realThumb'] = $realThumb;
	$result['width'] = $width;

	return $result;
}

// PHP's native pathinfo() function does not work with filenames that contain UTF8 characters.
// See http://uk.php.net/pathinfo
function pathinfo_utf($path) {
	if (empty($path)) {
		return array('dirname'=>'', 'basename'=>'', 'extension'=>'', 'filename'=>'');
	}
	if (strpos($path, '/')!==false) {
		$tmp=explode('/', $path);
		$basename=end($tmp);
		$dirname=substr($path, 0, strlen($path) - strlen($basename) - 1);
	} else if (strpos($path, '\\') !== false) {
		$tmp=explode('\\', $path);
		$basename=end($tmp);
		$dirname=substr($path, 0, strlen($path) - strlen($basename) - 1);
	} else {
		$basename=$path; // We have just a file name
		$dirname='.';    // For compatibility with pathinfo()
	}

	if (strpos($basename, '.')!==false) {
		$tmp=explode('.', $path);
		$extension=end($tmp);
		$filename=substr($basename, 0, strlen($basename) - strlen($extension) - 1);
	} else {
		$extension='';
		$filename=$basename;
	}

	return array('dirname'=>$dirname, 'basename'=>$basename, 'extension'=>$extension, 'filename'=>$filename);
}

// optional extra file
if (file_exists(WT_ROOT.'includes/functions.extra.php')) {
	require WT_ROOT.'includes/functions.extra.php';
}
