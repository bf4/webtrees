<?php
/**
 * Link media items to indi, sour and fam records
 *
 * This is the page that does the work of linking items.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage MediaDB
 * @version $Id$
 */

require './config.php';

require './includes/functions/functions_edit.php';

//-- page parameters and checking

$linktoid=safe_GET_xref('linktoid');
$mediaid =safe_GET_xref('mediaid');
$linkto  =safe_GET     ('linkto', array('person', 'source', 'family'));
$action  =safe_GET     ('action', PGV_REGEX_ALPHA, 'choose');

if (empty($linktoid) || empty($linkto)) {
	$paramok = false;
	$toitems = "???";
} else {
	switch ($linkto) {
	case 'person':
		$toitems = $pgv_lang['to_person'];
		break;
	case 'family':
		$toitems = $pgv_lang['to_family'];
		break;
	case 'source':
		$toitems = $pgv_lang['to_source'];
		break;
	}
}

print_simple_header($pgv_lang["link_media"]." ".$toitems);

if ($ENABLE_AUTOCOMPLETE) require './js/autocomplete.js.htm';

//-- check for admin
$paramok =  PGV_USER_CAN_EDIT;
if (!empty($linktoid)) $paramok = displayDetailsById($linktoid);

if ($action == "choose" && $paramok) {
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
	var pastefield;
	var language_filter, magnify;
	language_filter = "";
	magnify = "";

	function openerpasteid(id) {
		window.opener.paste_id(id);
		window.close();
	}

	function paste_id(value) {
		pastefield.value = value;
	}

	function paste_char(value,lang,mag) {
		pastefield.value += value;
		language_filter = lang;
		magnify = mag;
	}
	//-->
	</script>
	<script src="phpgedview.js" language="JavaScript" type="text/javascript"></script>

	<?php
	print "<form name=\"link\" method=\"get\" action=\"inverselink.php\">\n";
	print "<input type=\"hidden\" name=\"action\" value=\"update\" />\n";
	if (!empty($mediaid)) print "<input type=\"hidden\" name=\"mediaid\" value=\"".$mediaid."\" />\n";
	if (!empty($linktoid)) print "<input type=\"hidden\" name=\"linktoid\" value=\"".$linktoid."\" />\n";
	print "<input type=\"hidden\" name=\"linkto\" value=\"".$linkto."\" />\n";
	print "<input type=\"hidden\" name=\"ged\" value=\"".$GEDCOM."\" />\n";
	print "<table class=\"facts_table center ".$TEXT_DIRECTION."\">";
	print "\n\t<tr><td class=\"topbottombar\" colspan=\"2\">";
	print_help_link("add_media_linkid","qm", "link_media");
	print $pgv_lang["link_media"]." ".$toitems."</td></tr>";
	print "<tr><td class=\"descriptionbox width20 wrap\">".$pgv_lang["media_id"]."</td>";
	if (!empty($mediaid)) {
		//-- Get the title of this existing Media item
		$sql = "SELECT m_titl FROM {$TBLPREFIX}media where m_media='{$mediaid}' AND m_gedfile='{$GEDCOMS[$GEDCOM]['id']}'";
		$tempsql = dbquery($sql);
		$res =& $tempsql;
		$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
		if (trim($row["m_titl"])=="") {
			print "<td class=\"optionbox wrap\"><b>".$mediaid."</b></td></tr>";
		} else {
			print "<td class=\"optionbox wrap\"><b>".PrintReady($row["m_titl"])."</b>&nbsp;&nbsp;&nbsp;";
			if ($TEXT_DIRECTION=="rtl") print getRLM();
			print "(".$mediaid.")";
			if ($TEXT_DIRECTION=="rtl") print getRLM();
			print "</td></tr>";
		}
		$res->free();
 	} else {
		print "<td class=\"optionbox wrap\"><input type=\"text\" name=\"mediaid\" id=\"mediaid\" size=\"5\" />";
		print_findmedia_link("mediaid","1media");
		print "</td></tr>";
	}

	if (!isset($linktoid)) $linktoid = "";
	print "<tr><td class=\"descriptionbox\">";
	if ($linkto == "person") {
		print $pgv_lang["enter_pid"]."</td>";
		print "<td class=\"optionbox wrap\">";
		if ($linktoid=="") {
			print "<input class=\"pedigree_form\" type=\"text\" name=\"linktoid\" id=\"linktoid\" size=\"3\" value=\"$linktoid\" />";
			print_findindi_link("linktoid","");
		} else {
			$record=Person::getInstance($linktoid);
			echo '<b>', PrintReady($record->getFullName()), '</b>&nbsp;&nbsp;&nbsp;';
			if ($TEXT_DIRECTION=="rtl") print getRLM();
			print "(".$linktoid.")";
			if ($TEXT_DIRECTION=="rtl") print getRLM();
		}
	}

	if ($linkto == "family") {
		print $pgv_lang["family"]."</td>";
		print "<td class=\"optionbox wrap\">";
		if ($linktoid=="") {
			print "<input class=\"pedigree_form\" type=\"text\" name=\"linktoid\" id=\"linktoid\" size=\"3\" value=\"$linktoid\" />";
			print_findfamily_link("linktoid");
		} else {
			$record=Family::getInstance($linktoid);
			echo '<b>', PrintReady($record->getFullName()), '</b>&nbsp;&nbsp;&nbsp;';
			if ($TEXT_DIRECTION=="rtl") print getRLM();
			print "(".$linktoid.")";
			if ($TEXT_DIRECTION=="rtl") print getRLM();
		}
	}

	if ($linkto == "source") {
		print $pgv_lang["source"]."</td>";
		print "<td  class=\"optionbox wrap\">";
		if ($linktoid=="") {
			print "<input class=\"pedigree_form\" type=\"text\" name=\"linktoid\" id=\"linktoid\" size=\"3\" value=\"$linktoid\" />";
			print_findsource_link("linktoid");
		} else {
			$record=Source::getInstance($linktoid);
			echo '<b>', PrintReady($record->getFullName()), '</b>&nbsp;&nbsp;&nbsp;';
			if ($TEXT_DIRECTION=="rtl") print getRLM();
			print "(".$linktoid.")";
			if ($TEXT_DIRECTION=="rtl") print getRLM();
		}
	}
	print "</td></tr>";
	print "<tr><td class=\"topbottombar\" colspan=\"2\"><input type=\"submit\" value=\"".$pgv_lang["set_link"]."\" /></td></tr>";
	print "</table>";
	print "</form>\n";
	print "<br/><br/><center><a href=\"javascript:;\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a><br /></center>\n";
	print_simple_footer();

}
elseif ($action == "update" && $paramok) {
	linkMedia($mediaid, $linktoid);
	print "<br/><br/><center><a href=\"javascript:;\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a><br /></center>\n";
	print_simple_footer();
}
else {
	print "<center>nothing to do<center>";

	print "<br/><br/><center><a href=\"javascript:;\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a><br /></center>\n";

	print_simple_footer();

} // $paramok

?>
