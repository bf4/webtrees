<?php
/**
* Displays the details about a shared note record.  Also shows how many people and families
* reference this shared note.
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
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
* @version $Id$
*/

require './config.php';
require './includes/controllers/shnote_ctrl.php';
require './includes/functions/functions_print_lists.php';

$controller=new ShnoteController();
$controller->init();

// Tell addmedia.php what to link to
$linkToID=$controller->nid;

print_header($controller->getPageTitle());

// LightBox
if ($MULTI_MEDIA && is_dir('./modules/lightbox')) {
	include './modules/lightbox/lb_defaultconfig.php';
	if (file_exists('modules/lightbox/lb_config.php')) {
		include './modules/lightbox/lb_config.php';
	}
	include './modules/lightbox/functions/lb_call_js.php';
	loadLangFile('lightbox:lang');
}

if ($controller->shnote->isMarkedDeleted()) {
	echo '<span class="error">', $pgv_lang['record_marked_deleted'], '</span>';
}

echo PGV_JS_START;
echo 'function show_gedcom_record() {';
echo ' var recwin=window.open("gedrecord.php?pid=', $controller->nid, '", "_blank", "top=0,left=0,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");';
echo '}';
echo 'function showchanges() {';
echo ' window.location="shnote.php?nid=', $controller->nid, '&show_changes=yes"';
echo '}';
echo PGV_JS_END;

echo '<table class="list_table"><tr><td>';
if ($controller->accept_success) {
	echo '<b>', $pgv_lang['accept_successful'], '</b><br />';
}
echo '<span class="name_head">', PrintReady($controller->shnote->getFullName());
if ($SHOW_ID_NUMBERS) {
	echo ' ', getLRM(), '(', $controller->nid, ')', getLRM(); 
}
echo '</span><br /></td><td valign="top" class="noprint">';
if (!$controller->isPrintPreview()) {
	$editmenu=$controller->getEditMenu();
	$othermenu=$controller->getOtherMenu();
	if ($editmenu || $othermenu) {
		echo '<table class="sublinks_table" cellspacing="4" cellpadding="0">';
		echo '<tr><td class="list_label ', $TEXT_DIRECTION, '" colspan="2">', $pgv_lang['shnote_menu'], '</td></tr>';
		echo '<tr>';
		if ($editmenu) {
			echo '<td class="sublinks_cell ', $TEXT_DIRECTION, '">', $editmenu->printMenu(), '</td>';
		}
		if ($othermenu) {
			echo '<td class="sublinks_cell ', $TEXT_DIRECTION, '">', $othermenu->printMenu(), '</td>';
		}
		echo '</tr></table>';
	}
}
echo '</td></tr><tr><td colspan="2"><table border=\"0\" class="facts_table">';

$shnotefacts=$controller->shnote->getFacts();
echo "<br /><br />";

$noterec = find_gedcom_record($controller->nid);

// echo $noterec . "<br />";

$nt = preg_match("/0 @$controller->nid@ NOTE(.*)/", $noterec, $n1match);

if ($nt==1) {
	$shnote = print_note_record("<br />".$n1match[1], 1, $noterec, false, true);
}else{
	$shnote = "No Text";
}
// echo '<tr><td align="center" class="descriptionbox">Shared Note</td><td class="optionbox">';
?>

<script>
<!--
function edit_shnote() {
	win04 = window.open(
	"edit_interface.php?action=editshnote&pid=<?php echo $linkToID; ?>", "win04", "top=70, left=70, width=600, height=500, resizable=1, scrollbars=1 ");
	if (window.focus) {win04.focus();}
}
-->
</script>
<?

echo '<tr><td align="left" class="descriptionbox">';
echo '&nbsp;&nbsp;' . $pgv_lang["shnote"];
echo '<br /><br />';
echo "<a href=\"javascript: edit_shnote()\"> ";
echo "&nbsp;&nbsp;".$pgv_lang['edit'];
echo "</a>";
echo '</td><td class="optionbox">';
echo $shnote;
echo "<br />";
echo "</td></tr>";

echo '</table><br /><br /></td></tr><tr class="center"><td colspan="2">';


// Print the tasks table
// NOT WORKING YET
/*
if (file_exists('./modules/research_assistant/research_assistant.php') && $SHOW_RESEARCH_ASSISTANT>=PGV_USER_ACCESS_LEVEL) {
	include_once './modules/research_assistant/research_assistant.php';
	$mod=new ra_functions();
	$mod->Init();
	echo $mod->getShnoteTasks($controller->nid), '</td></tr><tr class="center"><td colspan="2">';
}
*/

// Individuals linked to this shared note
if ($controller->shnote->countLinkedIndividuals()) {
	print_indi_table($controller->shnote->fetchLinkedIndividuals(), $controller->shnote->getFullName());
}

// Families linked to this shared note
if ($controller->shnote->countLinkedFamilies()) {
	print_fam_table($controller->shnote->fetchLinkedFamilies(), $controller->shnote->getFullName());
}

echo '</td></tr></table>';

print_footer();
?>
