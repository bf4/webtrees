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

define('WT_ADMIN_FUNCTIONS_PHP', '');

require_once WT_ROOT.'includes/classes/class_media.php';
require_once WT_ROOT.'includes/functions/functions_utf-8.php';

function admin_header($title, $head="", $use_alternate_styles=true) {
	global $bwidth;
	global $bwidth, $title, $head;
	global $HOME_SITE_URL, $HOME_SITE_TEXT;
	global $BROWSERTYPE, $SEARCH_SPIDER;
	global $view, $cart;
	global $WT_IMAGE_DIR, $GEDCOM, $GEDCOM_TITLE, $COMMON_NAMES_THRESHOLD;
	global $QUERY_STRING, $action, $query, $theme_name;
	global $FAVICON, $stylesheet, $print_stylesheet, $rtl_stylesheet, $headerfile, $toplinks, $THEME_DIR, $print_headerfile;
	global $WT_IMAGES, $TEXT_DIRECTION, $ONLOADFUNCTION, $REQUIRE_AUTHENTICATION;

	header("Content-Type: text/html; charset=UTF-8");

	$META_DESCRIPTION=get_gedcom_setting(WT_GED_ID, 'META_DESCRIPTION');
	$META_ROBOTS=get_gedcom_setting(WT_GED_ID, 'META_ROBOTS');
	$META_TITLE=get_gedcom_setting(WT_GED_ID, 'META_TITLE');

	// The title often includes the names of records, which may have markup
	// that cannot be used in the page title.
	$title=html_entity_decode(strip_tags($title), ENT_QUOTES, 'UTF-8');

	if ($META_TITLE) {
		$title.=' - '.$META_TITLE;
	}
	$GEDCOM_TITLE = get_gedcom_setting(WT_GED_ID, 'title');
	$javascript = '';
	$query_string = $QUERY_STRING;
	$javascript.=WT_JS_START.'
		/* setup some javascript variables */
		var query = "'.$query_string.'";
		var textDirection = "'.$TEXT_DIRECTION.'";
		var browserType = "'.$BROWSERTYPE.'";
		var SCRIPT_NAME = "'.WT_SCRIPT_NAME.'";
		/* keep the session id when opening new windows */
		var sessionid = "'.session_id().'";
		var sessionname = "'.session_name().'";
		var accesstime = "'.time().'";
		var plusminus = new Array();
	';
	$javascript .= '
	function message(username, method, url, subject) {
		if ((!url)||(url=="")) url=\''.urlencode(WT_SCRIPT_NAME."?".$QUERY_STRING).'\';
		if ((!subject)||(subject=="")) subject="";
		window.open(\'message.php?to=\'+username+\'&method=\'+method+\'&url=\'+url+\'&subject=\'+subject+"&"+sessionname+"="+sessionid, \'_blank\', \'top=50, left=50, width=600, height=500, resizable=1, scrollbars=1\');
		return false;
	}

	var whichhelp = \'help_'.WT_SCRIPT_NAME.'&action='.$action.'\';
	//-->
	'.WT_JS_END.'<script src="../js/webtrees.js" language="JavaScript" type="text/javascript"></script>';
	$bodyOnLoad = '';
	$bodyOnLoad .= " onload=\"";
	if (!empty($ONLOADFUNCTION)) $bodyOnLoad .= $ONLOADFUNCTION;
	if ($TEXT_DIRECTION=="rtl") {
		$bodyOnLoad .= " maxscroll = document.documentElement.scrollLeft;";
	}
	$bodyOnLoad .= "\"";
	include 'admin_header.php';
}


/**
 * get theme names
 *
 * function to get the names of all of the themes as an array
 * it searches the themes directory and reads the name from the theme_name variable
 * in the theme.php file.
 * @return array and array of theme names and their corresponding directory
*/
function admin_get_theme_names() {
	$themes = array();
	$d = dir("../themes");
	while (false !== ($entry = $d->read())) {
		if ($entry{0}!="." && $entry!="CVS" && !stristr($entry, "svn") && is_dir(WT_ROOT.'themes/'.$entry) && file_exists(WT_ROOT.'themes/'.$entry.'/theme.php')) {
			$themefile = implode("", file(WT_ROOT.'themes/'.$entry.'/theme.php'));
			$tt = preg_match("/theme_name\s*=\s*\"(.*)\";/", $themefile, $match);
			if ($tt>0)
				$themename = trim($match[1]);
			else
				$themename = "../themes/$entry";
			$themes[$themename] = "../themes/$entry/";
		}
	}
	$d->close();
	uksort($themes, "utf8_strcasecmp");
	return $themes;
}

/**
 * Print a link for a popup help window
 *
*/ 
function admin_help_link($help_topic, $module='') {
	global $WT_USE_HELPIMG, $WT_IMAGES, $WT_IMAGE_DIR, $SEARCH_SPIDER;

	if ($_SESSION['show_context_help']) {
		return
			'<a class="help" tabindex="0" href="javascript: '.$help_topic.'" onclick="helpPopup(\''.$help_topic.'\',\''.$module.'\'); return false;">&nbsp;'.
			'<img src="images/help.jpg" class="icon" width="15" height="15" alt="" />'.
			'&nbsp;</a>';
	} else {
		return '';
	}
}

/**
 * find_fact icon
 *
*/
function admin_findfact_link($element_id, $ged='', $asString=false) {
	global $WT_IMAGE_DIR, $WT_IMAGES, $GEDCOM;

	$text = i18n::translate('Find fact tag');
	if (empty($ged)) $ged=$GEDCOM;
	if (isset($WT_IMAGES["find_facts"]["button"])) $Link = "<img src=\"images/find_facts.png\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"top\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findFact(document.getElementById('".$element_id."'), '".$ged."'); return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	echo $out;
}

/**
 * find current page URL
 *
*/
function curPageURL() {
$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
return $url;
}

function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

?>
