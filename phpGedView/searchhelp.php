<?php
/**
 * Search in help files
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reerved.
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
 * This Page Is Valid XHTML 1.0 Transitional! > 3 September 2005
 *
 * @package webtrees
 * @version $Id$
 */

define('PGV_SCRIPT_NAME', 'searchhelp.php');
require './config.php';

print_simple_header(i18n::translate('Search Help Text'));

// On first entry, initially check the boxes
if (!isset($action)) {
	$searchuser = "yes";
	$searchhow = "any";
	$searchintext = "true";
}

// If no admin, always search in user help
if (!PGV_USER_GEDCOM_ADMIN) {
	$searchuser = "yes";
}

// Initialize variables
$searchtext   =safe_POST('searchtext');
$searchuser   =safe_POST('searchuser',    'yes', 'no');
$searchconfig =safe_POST('searchconfig',  'yes', 'no');
$searchmodules=safe_POST('searchmodules', 'yes', 'no');
$searchhow	  =safe_POST('searchhow');
$searchintext =safe_POST('searchintext');
$found = 0;

?>
<script type="text/javascript">
<!--
function checkfrm(frm) {
	if (frm.searchtext.value.length<2) {
		alert("<?php print i18n::translate('Please enter more than one character')?>");
		frm.searchtext.focus();
		return false;
	}
	return true;
}
//-->
</script>
<?php
// Print the form for input
print '<form name="entersearch" action="'.PGV_SCRIPT_NAME.'" method="post" onsubmit="return checkfrm(this);">';
print "<input name=\"action\" type=\"hidden\" value=\"search\" />";
print "<table class=\"facts_table $TEXT_DIRECTION\">";
print "<tr><td colspan=\"2\" class=\"topbottombar\">";
print i18n::translate('Search Help Text');
print help_link('hs_title');
print "</td></tr>";

// Enter the keyword(s)
print "<tr><td class=\"descriptionbox width20 wrap vmiddle\">";
print i18n::translate('Search for');
print help_link('hs_keyword_advice');
print "</td>";
print "<td class=\"optionbox\"><input type=\"text\" name=\"searchtext\" dir=\"ltr\" value=\"".$searchtext."\" /></td></tr>";

// How to search
print "<tr ><td class=\"descriptionbox width20 wrap vmiddle\">";
print i18n::translate('Search type');
print help_link('hs_searchhow_advice');
print "</td>";
print "<td class=\"optionbox\">";
print "<input type=\"radio\" name=\"searchhow\" dir=\"ltr\" value=\"any\"";
if ($searchhow == "any") print " checked=\"checked\"";
print " />".i18n::translate('Any word')."<br />";
print "<input type=\"radio\" name=\"searchhow\" dir=\"ltr\" value=\"all\"";
if ($searchhow == "all") print " checked=\"checked\"";
print " />".i18n::translate('All words')."<br />";
print "<input type=\"radio\" name=\"searchhow\" dir=\"ltr\" value=\"sentence\"";
if ($searchhow == "sentence") print " checked=\"checked\"";
print " />".i18n::translate('Exact phrase')."<br />";
print "</td></tr>";

print "<tr><td rowspan=\"2\" class=\"descriptionbox width20 wrap vmiddle\">";
print i18n::translate('Search in');
print help_link('hs_searchin_advice');
print "</td>";
print "<td class=\"optionbox\">";
print "<input type=\"checkbox\" name=\"searchuser\" dir=\"ltr\" value=\"yes\"";
if ($searchuser == "yes") print " checked=\"checked\"";
print " />".i18n::translate('User Help')."<br />";
print "<input type=\"checkbox\" name=\"searchmodules\" dir=\"ltr\" value=\"yes\"";
if ($searchmodules == "yes") print " checked=\"checked\"";
print " />".i18n::translate('Modules Help')."<br />";
// Show "administrator help" choice only to admins
if (PGV_USER_GEDCOM_ADMIN) {
	print "<input type=\"checkbox\" name=\"searchconfig\" dir=\"ltr\" value=\"yes\"";
	if ($searchconfig == "yes") print " checked=\"checked\"";
	print " />".i18n::translate('Administrator Help');
}
print "</td></tr><tr>";
print "<td class=\"optionbox\"><input type=\"radio\" name=\"searchintext\" dir=\"ltr\" value=\"true\"";
if ($searchintext == "true") print " checked=\"checked\"";
print " />".i18n::translate('Help text only')."<br />";
print "<input type=\"radio\" name=\"searchintext\" dir=\"ltr\" value=\"all\"";
if ($searchintext == "all") print " checked=\"checked\"";
print " />".i18n::translate('All text')."<br />";
print "</td></tr>";

// Print the buttons
print "<tr><td class=\"topbottombar\" colspan=\"2\">";
print "<input type=\"submit\" name=\"entertext\" value=\"".i18n::translate('Search')."\" />";
print "<input type=\"button\" value=\"".i18n::translate('Close window')."\" onclick='self.close();' />";
print "</td></tr>";

// Perform the search
if ((!empty($searchtext)) && strlen($searchtext)>1)  {

	// Determine the language files to be searched
	$langFiles = "pgv_lang, ";
	if (PGV_USER_GEDCOM_ADMIN) $langFiles .= "pgv_admin, ";
	if (PGV_USER_CAN_EDIT) $langFiles .= "pgv_editor, ";
	if ($searchuser == "yes") $langFiles .= "pgv_help, ";
	if ($searchconfig == "yes") $langFiles .= "pgv_confighelp, ";
	if ($searchmodules == "yes") $langFiles .= "googlemap:lang, googlemap:help_text, sitemap:lang, sitemap:help_text, ";
	$langFiles = substr($langFiles, 0, -2);		// Trim last ", "

	$helpvarnames = array();
	unset($pgv_lang);

	loadLangFile($langFiles);

	// Find all helpvars, so we know what vars to check after the lang.xx file has been reloaded
	foreach ($pgv_lang as $text => $value) {
		if ($searchintext == "all") {
			$helpvarnames[] = $text;
		} elseif ((substr($text, -5) == "_help" && $value{0}!="_") || (substr($text, -4) == ".php")) {
			$helpvarnames[] = $text;
		}
	}

	// Split the search criteria if all or any is chosen. Otherwise, just fill the array with the sentence
	$criteria = array();
	if ($searchhow == "sentence") $criteria[] = $searchtext;
	else $criteria = explode(' ', $searchtext);

	// Search in the previously stored vars for a hit and print it
	foreach ($helpvarnames as $key => $value) {
		$helptxt = print_text($value,0,1);
		// Remove hyperlinks
		$helptxt = preg_replace("/<a[^<>]+>/", "", $helptxt);
		$helptxt = str_replace("</a>", "", $helptxt);
		// Remove unresolved language variables
		$helptxt = preg_replace("/#pgv[^#]+#/i", "", $helptxt);
		// Save the original text for clean search
		$helptxtorg = $helptxt;
		// Scroll through the criteria
		$cfound = 0;
		$cnotfound = 0;
		foreach ($criteria as $ckey => $criterium) {
			// See if there is a case insensitive hit
			if (strpos(UTF8_strtoupper($helptxtorg), UTF8_strtoupper($criterium))) {
				// Set the search string for preg_replace, case insensitive
				// The \\0 is for wrapping the existing string in the text with the span
				$helptxt = str_ireplace($criterium, "<span class=\"search_hit\">\\0</span>", $helptxt);
				$cfound++;
			}
			else $cnotfound++;
		}
		if (
		(($searchhow == "any") && ($cfound >= 1)) ||
		(($searchhow == "all") && ($cnotfound == 0)) ||
		(($searchhow == "sentence") && ($cfound >= 1))) {
			print "<tr><td colspan=\"2\" class=\"descriptionbox wrap $TEXT_DIRECTION\">".$helptxt."</td></tr>";
			$found++;
			//-- if there is more than 100 the user should refine their search
			if ($found>100) break;
		}
	}
}

// Print total results, if a search has been performed
if (!empty($searchtext)) {
	print "<tr><td colspan=\"2\" class=\"topbottombar\">".i18n::translate('Results found:')." ".$found;
	print "</td></tr>";
}
print "</table></form>";
?>
<script language="JavaScript" type="text/javascript">
	document.entersearch.searchtext.focus();
</script>
<?php
print_simple_footer();
?>
