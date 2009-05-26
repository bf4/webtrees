<?php
/**
 * Media Link Assistant Control module for phpGedView
 *
 * Media Link information about an individual
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
 * @subpackage GEDFact_assistant
 * @version $Id$
*/

// GEDFact Media assistant replacement code for inverselink.php: ===========================

require './config.php';
require './includes/functions/functions_edit.php';
//-- page parameters and checking
$linktoid	= safe_GET_xref('linktoid');
$mediaid	= safe_GET_xref('mediaid');
$linkto		= safe_GET     ('linkto', array('person', 'source', 'family'));
$action		= safe_GET     ('action', PGV_REGEX_ALPHA, 'choose');
$more_links	= safe_REQUEST($_REQUEST, 'more_links', PGV_REGEX_UNSAFE);
$gid		= safe_GET_xref('gid');

if (empty($linktoid) || empty($linkto)) {
	$paramok = false;
	$toitems = "";
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
//$paramok =  PGV_USER_CAN_EDIT;
$paramok =  PGV_USER_GEDCOM_ADMIN;
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
	echo '<form name="link" method="get" action="inverselink.php">';
	// echo '<input type="hidden" name="action" value="choose" />';
	echo '<input type="hidden" name="action" value="update" />';
	if (!empty($mediaid)) {
		echo '<input type="hidden" name="mediaid" value="', $mediaid, '" />';
	}
	if (!empty($linktoid)) {
		echo '<input type="hidden" name="linktoid" value="', $linktoid, '" />';
	}
	echo '<input type="hidden" name="linkto" value="', $linkto, '" />';
	echo '<input type="hidden" name="ged" value="', $GEDCOM, '" />';
	echo '<table class="facts_table center ', $TEXT_DIRECTION, '">';
	echo '<tr><td class="topbottombar" colspan="2">';
	print_help_link("add_media_linkid","qm", "link_media");
	echo $pgv_lang["link_media"], ' ', $toitems, '</td></tr>';
	echo '<tr><td class="descriptionbox width20 wrap">', $pgv_lang["media_id"], '</td>';
	echo '<td class="optionbox wrap">';
	if (!empty($mediaid)) {
		//-- Get the title of this existing Media item
		$title=
			PGV_DB::prepare("SELECT m_titl FROM {$TBLPREFIX}media where m_media=? AND m_gedfile=?")
			->execute(array($mediaid, PGV_GED_ID))
			->fetchOne();
		if ($title) {
			echo '<b>', PrintReady($title), '</b>&nbsp;&nbsp;&nbsp;';
			if ($TEXT_DIRECTION=="rtl") echo getRLM();
			echo '(', $mediaid, ')';
			if ($TEXT_DIRECTION=="rtl") echo getRLM();
		} else {
			echo '<b>', $mediaid, '</b>';
		}
		echo '<table><tr><td>';
		//-- Get the filename of this existing Media item
		$filename=
			PGV_DB::prepare("SELECT m_file FROM {$TBLPREFIX}media where m_media=? AND m_gedfile=?")
			->execute(array($mediaid, PGV_GED_ID))
			->fetchOne();
		$filename = str_replace(" ", "%20", $filename);
		echo '<img src = ', $filename, ' height="70" ></img>&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '</td></tr></table>';
		echo '</td></tr>';
		echo '<tr><td class="descriptionbox width20 wrap">', $pgv_lang["current_links"], '</td>';
		echo '<td class="optionbox wrap">';
			include ('modules/GEDFact_assistant/MEDIA/media_query_1a.php');
		echo '</td></tr>';
	}
	
	if (!isset($linktoid)) { $linktoid = ""; }
	
	echo '<tr><td class="descriptionbox wrap">';
	echo $pgv_lang["add_more_links"];
	echo '<td class="optionbox wrap">';
	if ($linktoid=="") {
		// ----
	} else {
		$record=Person::getInstance($linktoid);
		echo '<b>', PrintReady($record->getFullName()), '</b>&nbsp;&nbsp;&nbsp;';
		if ($TEXT_DIRECTION=="rtl") print getRLM();
		echo '(', $linktoid, ')';
		if ($TEXT_DIRECTION=="rtl") print getRLM();
	}
	print "<input type=\"text\" name=\"gid\" id=\"gid\" size=\"6\" value=\"\" />";
	
	// echo ' Enter Name or ID &nbsp; &nbsp; &nbsp; <b>OR</b> &nbsp; &nbsp; &nbsp;Search for ID ';
	
	echo '&nbsp;';
	print_findindi_link("gid", "");
	echo '&nbsp;';
	print_findfamily_link("gid");
	echo '&nbsp;';
	print_findsource_link("gid");
	
	print "<br /><sub>" . $pgv_lang["add_linkid_advice"] . "</sub>";
	
	echo '<br /><br />';
	echo '<input type="hidden" name="idName" id="idName" size="36" value="Name of ID" />';
	include ('modules/GEDFact_assistant/MEDIA/media_query_2a.php');
	echo '</td></tr>';
	echo '<tr><td colspan="2">';
	echo '</td></tr>';
	echo '<input type="hidden" name="more_links" value="No_Values" />';
	echo '<tr><td class="topbottombar" colspan="2">';
	echo '<br />';
	echo '<center><input type="submit" value="', $pgv_lang["save"], '" onclick="javascript:shiftlinks();" />';
	echo '</center></td></tr>';
	include ('modules/GEDFact_assistant/MEDIA/media_7_parse_addLinksTbl.php');
	echo '</table>';
	echo '</form>';
	echo '<br/><br/><center><a href="javascript:;" onclick="if (window.opener.showchanges) window.opener.showchanges(); window.close(); win01.close(); ">', $pgv_lang["close_window"], '</a><br /></center>';
	print_simple_footer();
	
} elseif ($action == "queue" && $paramok) {
	// TO DO
	// Create action to queue item in input field
	?>
	<script>
	alert('This is queued');
	continue;
	</script>
	<?php
	

} elseif ($action == "update" && $paramok) {
	if (isset($more_links) && $more_links!="No_Values") {
		$more_links = stripslashes($more_links);
		$more_links = substr($more_links, 0, -1);
		$add_more_links = (explode(", ", $more_links));
		foreach ($add_more_links as $link2id) {
			echo $mediaid, $pgv_lang["media_now_linked to"], '(', $link2id, ')<br />';
			linkMedia($mediaid, $link2id);
		}
	}else{
		echo $mediaid, $pgv_lang["media_now_linked to"], '(', $gid, ')<br />';
		linkMedia($mediaid, $gid);
	}
	echo '<br/><br/><center><a href="javascript:;" onclick="if (window.opener.showchanges) window.opener.showchanges(); window.close(); win01.close(); ">', $pgv_lang["close_window"], '</a><br /></center>';
	print_simple_footer();
		

} else {
	// echo '<center>You must be logged in as an Administrator<center>';
	echo '<br/><br/><center><a href="javascript:;" onclick="if (window.opener.showchanges) window.opener.showchanges(); window.close(); win01.close();">', $pgv_lang["close_window"], '</a><br /></center>';
	//print_simple_footer();
}

?>