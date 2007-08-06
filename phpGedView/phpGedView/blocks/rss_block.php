<?php
/**
 * RSS Block
 *
 * This is the RSS block
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * $Id$
 * @package PhpGedView
 * @subpackage Blocks
 */
$PGV_BLOCKS["print_RSS_block"]["name"]			= $pgv_lang["rss_feeds"];
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
		global $LANGUAGE, $pgv_lang, $GEDCOM, $GEDCOMS, $ctype, $SCRIPT_NAME, $QUERY_STRING, $ENABLE_MULTI_LANGUAGE;

		print "<div id=\"rss_block\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		print_help_link("rss_feed_help", "qm");
		print "<b>". $pgv_lang["rss_feeds"] . "</b>";
		print "</div></td>";
		print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
		print "</table>";
		print "<div class=\"blockcontent\">";
		print "<div class=\"center\">";
		print "<form method=\"post\" action=\"\" name=\"rssform\">\n";
		print "<br />";
		print "\n\t<select name=\"rssStyle\" class=\"header_select\" onchange=\"javascript:document.getElementById('rss_button').href = 'rss.php?ged=" . $GEDCOM . "&amp;lang=" . $LANGUAGE . "' + (document.rssform.module.value==''? '' : '&amp;module=' + document.rssform.module.value) + (document.rssform.rssStyle.value==''? '' : '&amp;rssStyle=' + document.rssform.rssStyle.value) + (document.rssform.auth.value==''? '' : '&amp;auth=' + document.rssform.auth.value);\">";
		print "\n\t\t<option value=\"ATOM\" selected=\"selected\">ATOM 1.0</option>";
		print "\n\t\t<option value=\"RSS2.0\">RSS 2.0</option>";
		print "\n\t\t<option value=\"RSS1.0\">RSS 1.0</option>";
		print "\n\t\t<option value=\"ATOM0.3\">ATOM 0.3</option>";
		print "\n\t\t<option value=\"RSS0.91\">RSS 0.91</option>";
		print "\n\t\t<option value=\"HTML\">HTML</option>";
		print "\n\t\t<option value=\"JS\">JavaScript</option>";
		print "\n\t</select>";
		print "\n\t<select name=\"module\" class=\"header_select\" onchange=\"javascript:document.getElementById('rss_button').href = 'rss.php?ged=" . $GEDCOM . "&amp;lang=" . $LANGUAGE . "' + (document.rssform.module.value==''? '' : '&amp;module=' + document.rssform.module.value) + (document.rssform.rssStyle.value==''? '' : '&amp;rssStyle=' + document.rssform.rssStyle.value) + (document.rssform.auth.value==''? '' : '&amp;auth=' + document.rssform.auth.value);\">";
		print "\n\t\t<option value=\"\">" . $pgv_lang["all"] . "</option>";
		print "\n\t\t<option value=\"today\">" . $pgv_lang["on_this_day"] . " </option>";
		print "\n\t\t<option value=\"upcoming\">" . $pgv_lang["upcoming_events"] . "</option>";
		print "\n\t\t<option value=\"gedcomStats\">" . $pgv_lang["gedcom_stats"] . "</option>";
		print "\n\t\t<option value=\"gedcomNews\">" . $pgv_lang["gedcom_news"] . "</option>";
		print "\n\t\t<option value=\"top10Surnames\">" . $pgv_lang["block_top10"] . "</option>";
		print "\n\t\t<option value=\"recentChanges\">" . $pgv_lang["recent_changes"] . "</option>";
		print "\n\t\t<option value=\"randomMedia\">" . $pgv_lang["random_picture"] . "</option>";
		print "\n\t</select>";
		print "\n\t<select name=\"auth\" class=\"header_select\" onchange=\"javascript:document.getElementById('rss_button').href = 'rss.php?ged=" . $GEDCOM . "&amp;lang=" . $LANGUAGE . "' + (document.rssform.module.value==''? '' : '&amp;module=' + document.rssform.module.value) + (document.rssform.rssStyle.value==''? '' : '&amp;rssStyle=' + document.rssform.rssStyle.value) + (document.rssform.auth.value==''? '' : '&amp;auth=' + document.rssform.auth.value);\">";
		print "\n\t\t<option value=\"\">" . $pgv_lang["no_auth_needed"] . "</option>";
		print "\n\t\t<option value=\"basic\">" . $pgv_lang["basic_auth"] . "</option>";
		//print "\n\t\t<option value=\"digest\">" . $pgv_lang["digest_auth"] . "</option>";
		print "\n\t</select>";
		print " <a id=\"rss_button\" href=\"rss.php?ged=" . $GEDCOM . "&amp;lang=" . $LANGUAGE . "\"><img class=\"icon\" src=\"images/feed-icon16x16.png\" alt=\"RSS\" title=\"RSS\" /></a>";
		print "</form></div>\n";
		print "<div class=\"center\">";
		print "</div>";
		print "</div>";
		print "</div>";
}
?>