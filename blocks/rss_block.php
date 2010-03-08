<?php
/**
 * RSS Block
 *
 * This is the RSS block
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @package PhpGedView
 * @subpackage Blocks
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_RSS_BLOCK_PHP', '');

$PGV_BLOCKS["print_RSS_block"]["name"]			= i18n::translate('RSS Feeds');
$PGV_BLOCKS["print_RSS_block"]["descr"]			= "rss_descr";
$PGV_BLOCKS["print_RSS_block"]["type"]			= "gedcom";
$PGV_BLOCKS["print_RSS_block"]["canconfig"]		= false;
$PGV_BLOCKS["print_RSS_block"]["config"]		= array("cache"=>0);
/**
 * Print RSS Block
 *
 * Prints a block allowing the user to login to the site directly from the portal
 */
function print_RSS_block($block = true, $config="", $side, $index) {
	global $LANGUAGE;

	$id="rss_block";
	$title = print_help_link("rss_feed", "qm","",false,true);
	$title .= i18n::translate('RSS Feeds');
	$content = "<div class=\"center\">";
	$content .= "<form method=\"post\" action=\"\" name=\"rssform\">";
	$content .= "<br />";
	$content .= "<select name=\"rssStyle\" class=\"header_select\" onchange=\"javascript:document.getElementById('rss_button').href = '".encode_url("rss.php?ged=".PGV_GEDCOM."&lang={$LANGUAGE}") . "' + (document.rssform.module.value==''? '' : '&module=' + document.rssform.module.value) + (document.rssform.rssStyle.value==''? '' : '&rssStyle=' + document.rssform.rssStyle.value);\">";
	$content .= "<option value=\"ATOM\" selected=\"selected\">ATOM 1.0</option>";
	$content .= "<option value=\"RSS2.0\">RSS 2.0</option>";
	$content .= "<option value=\"RSS1.0\">RSS 1.0</option>";
	$content .= "<option value=\"ATOM0.3\">ATOM 0.3</option>";
	$content .= "<option value=\"RSS0.91\">RSS 0.91</option>";
	$content .= "<option value=\"HTML\">HTML</option>";
	$content .= "<option value=\"JS\">JavaScript</option>";
	$content .= "</select>";
	$content .= "<select name=\"module\" class=\"header_select\" onchange=\"javascript:document.getElementById('rss_button').href = '".encode_url("rss.php?ged=".PGV_GEDCOM."&lang={$LANGUAGE}") . "' + (document.rssform.module.value==''? '' : '&module=' + document.rssform.module.value) + (document.rssform.rssStyle.value==''? '' : '&rssStyle=' + document.rssform.rssStyle.value);\">";
	$content .= "<option value=\"\">" . i18n::translate('ALL') . "</option>";
	$content .= "<option value=\"today\">" . i18n::translate('On This Day ...') . " </option>";
	$content .= "<option value=\"upcoming\">" . i18n::translate('Upcoming Events') . "</option>";
	$content .= "<option value=\"gedcomStats\">" . i18n::translate('GEDCOM Statistics') . "</option>";
	$content .= "<option value=\"gedcomNews\">" . i18n::translate('News') . "</option>";
	$content .= "<option value=\"top10Surnames\">" . i18n::translate('Top 10 Surnames') . "</option>";
	$content .= "<option value=\"recentChanges\">" . i18n::translate('Recent Changes') . "</option>";
	$content .= "<option value=\"randomMedia\">" . i18n::translate('Random Picture') . "</option>";
	$content .= "</select>";
	$content .= " <a id=\"rss_button\" href=\"".encode_url("rss.php?ged=".PGV_GEDCOM."&lang={$LANGUAGE}") . "\"><img class=\"icon\" src=\"images/feed-icon16x16.png\" alt=\"RSS\" title=\"RSS\" /></a>";
	$content .= "</form></div>";
	$content .= "<div class=\"center\">";
	$content .= "</div>";

	global $THEME_DIR;
	if ($block) {
		require $THEME_DIR.'templates/block_small_temp.php';
	} else {
		require $THEME_DIR.'templates/block_main_temp.php';
	}
}
?>
