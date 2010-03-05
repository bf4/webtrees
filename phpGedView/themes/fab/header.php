<?php
// Header for FAB theme
//
// Modifications Copyright (c) 2010 Greg Roach
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// @package PhpGedView
// @subpackage Themes
// @version $Id$

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// Definitions to simplify logic on pages with right-to-left languages
// TODO: merge this into the trunk?
if ($TEXT_DIRECTION=='ltr') {
	define ('PGV_CSS_ALIGN',         'left');
	define ('PGV_CSS_REVERSE_ALIGN', 'right');
} else {
	define ('PGV_CSS_ALIGN',         'right');
	define ('PGV_CSS_REVERSE_ALIGN', 'left');
}

echo
	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
	'<html xmlns="http://www.w3.org/1999/xhtml" dir="', $TEXT_DIRECTION, '">',
	'<head><meta http-equiv="Content-Type" content="text/html; charset=', $CHARACTER_SET, '" />',
	'<title>', htmlspecialchars($GEDCOM_TITLE), '</title>',
	'<link rel="shortcut icon" href="', $FAVICON, '" type="image/x-icon">',
	'<link rel="stylesheet" href="', $stylesheet, '" type="text/css" media="all" />';

if ($ENABLE_RSS && !$REQUIRE_AUTHENTICATION) {
	echo '<link href="', urlencode($SERVER_URL.'rss.php?ged='.PGV_GEDCOM), '" rel="alternate" type="', $applicationType, '" title="', htmlspecialchars($GEDCOM_TITLE), '" />';
}

if (PGV_USE_LIGHTBOX) {
	if ($TEXT_DIRECTION=='rtl') {
		echo
			'<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />',
			'<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />';
	} else {
		echo
			'<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />',
			'<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />';
	}
}

echo
	'<meta name="author" content="', htmlspecialchars($META_AUTHOR), '" />',
	'<meta name="publisher" content="', htmlspecialchars($META_PUBLISHER), '" />',
	'<meta name="copyright" content="', htmlspecialchars($META_COPYRIGHT), '" />',
	'<meta name="description" content="', htmlspecialchars($META_DESCRIPTION), '" />',
	'<meta name="page-topic" content="', htmlspecialchars($META_PAGE_TOPIC), '" />',
	'<meta name="audience" content="', htmlspecialchars($META_AUDIENCE), '" />',
	'<meta name="page-type" content="', htmlspecialchars($META_PAGE_TYPE), '" />',
	'<meta name="robots" content="', htmlspecialchars($META_ROBOTS), '" />',
	'<meta name="revisit-after" content="', htmlspecialchars($META_REVISIT), '" />',
	'<meta name="keywords" content="', htmlspecialchars($META_KEYWORDS), '" />',
	'<meta name="generator" content="', PGV_PHPGEDVIEW, ' ', PGV_VERSION_TEXT, '" />';

echo
	$javascript, $head, 
	'<script type="text/javascript" src="js/jquery/jquery.min.js"></script>',
	'<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>',
	'<link type="text/css" href="js/jquery/css/jquery-ui.custom.css" rel="Stylesheet" />',
	'<link type="text/css" href="<?php echo PGV_THEME_DIR?>jquery/jquery-ui_theme.css" rel="Stylesheet" />';
	
if ($TEXT_DIRECTION=='rtl') {
	echo '<link type="text/css" href="<?php echo PGV_THEME_DIR?>jquery/jquery-ui_theme_rtl.css" rel="Stylesheet" />';
}

echo
	'<link type="text/css" href="<?php echo PGV_THEME_DIR?>modules.css" rel="Stylesheet" />',
	'</head><body id="body" ', $bodyOnLoad, '>';
flush(); // Allow the browser to start fetching external stylesheets, javascript, etc.
echo '<div id="header" class="block">'; // Every page has a header
if ($view!='simple') {
	echo
		'<div style="float:', PGV_CSS_ALIGN, '; font-size:250%;">Fish&nbsp;&amp;&nbsp;Frogs</div>';
	// Print the user links
	if ($SEARCH_SPIDER) {
		// Search engines get a reduced menu
		$menu_items=array(
			MenuBar::getGedcomMenu(),
			MenuBar::getListsMenu(),
			MenuBar::getCalendarMenu()
		);
	} else {
		// Options for real users
		echo '<div style="float:', PGV_CSS_REVERSE_ALIGN, ';"><ul class="makeMenu">';
		if (PGV_USER_ID) {
			echo
				'<li><a href="edituser.php" class="link">', getUserFullName(PGV_USER_ID), '</a></li>',
				' | <li><a href="index.php?logout=1" class="link">', $pgv_lang['logout'], '</a></li>';
			if (PGV_USER_GEDCOM_ADMIN) {
				echo ' | <li><a href="admin.php" class="link">', $pgv_lang['admin'], '</a></li>';
			}
			if (PGV_USER_CAN_ACCEPT && exists_pending_change()) {
				echo ' | <li><a href="javascript:;" onclick="window.open(\'edit_changes.php\',\'_blank\',\'width=600,height=500,resizable=1,scrollbars=1\'); return false;" style="color:red;">', $pgv_lang['review_changes_block'], '</a></li>';
			}
			echo ' | ', MenuBar::getFavouritesMenu()->getMenuAsList();
			global $ENABLE_MULTI_LANGUAGE, $ALLOW_THEME_DROPDOWN, $ALLOW_USER_THEMES;
			if ($ENABLE_MULTI_LANGUAGE) {
				echo ' | ', MenuBar::getLanguageMenu()->getMenuAsList();
			}
			if ($ALLOW_THEME_DROPDOWN && $ALLOW_USER_THEMES) {
				echo ' | ', MenuBar::getThemeMenu()->getMenuAsList();
			}
		} else {
			global $LOGIN_URL;
			if (PGV_SCRIPT_NAME==basename($LOGIN_URL)) {
				echo '<li><a href="', $LOGIN_URL, '" class="link">', $pgv_lang['login'], '</a></li>';
			} else {
				$QUERY_STRING = normalize_query_string($QUERY_STRING.'&amp;logout=');
				echo '<li><a href="', $LOGIN_URL, '?url=', PGV_SCRIPT_PATH, PGV_SCRIPT_NAME, decode_url(normalize_query_string($QUERY_STRING.'&amp;ged='.PGV_GEDCOM)), '" class="link">', $pgv_lang['login'], '</a></li>';
			}
		}
		echo
			' | <form style="display:inline;" action="search.php" method="get">',
			'<input type="hidden" name="action" value="general" />',
			'<input type="hidden" name="topsearch" value="yes" />',
			'<input type="text" name="query" size="20" value="', $pgv_lang['search'], '" onfocus="if (this.value==\'', $pgv_lang['search'], '\') this.value=\'\'; focusHandler();" onblur="if (this.value==\'\') this.value=\'', $pgv_lang['search'], '\';" />',
			'</form>',
			'</ul></div>';
		$menu_items=array(
			MenuBar::getGedcomMenu(),
			MenuBar::getMygedviewMenu(),
			MenuBar::getChartsMenu(),
			MenuBar::getListsMenu(),
			MenuBar::getCalendarMenu(),
			MenuBar::getReportsMenu(),
			MenuBar::getSearchMenu(),
			MenuBar::getOptionalMenu()
		);
		foreach (MenuBar::getModuleMenus() as $menu) {
			$menu_items[]=$menu;
		}

		// Help menu
		global $helpindex, $action;
		$menu = new Menu($pgv_lang["page_help"], "#", "down");
		if (empty($helpindex)) {
			$menu->addOnclick("return helpPopup('help_".PGV_SCRIPT_NAME."&amp;action=".$action."');");
		} else {
			$menu->addOnclick("return helpPopup('".$helpindex."');");
		}
		$menu_items[]=$menu;
		echo
			'<div style="float:', PGV_CSS_ALIGN, '; clear:', PGV_CSS_ALIGN, '; font-size:175%;">',
			htmlspecialchars($GEDCOM_TITLE),
			'</div>';
	}
	// Print the menu bar
	echo '<div id="topMenu"><ul class="makeMenu">';
	foreach ($menu_items as $n=>$menu) {
		if ($menu && $menu->link) {
			if ($n>0) {
				echo ' | ';
			}
			echo $menu->getMenuAsList();
		}
	}
	unset($menu_items, $n, $menu);
	echo '</ul></div>';
}
require './sidebar.php';
echo '</div><div id="content">';
flush(); // Allow the browser to format the header/menus while we generate the page
