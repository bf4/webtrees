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

// require_once './includes/functions/functions_print_facts.php';
// require_once './includes/functions/functions_print.php';

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

$nt = preg_match("/0 @$controller->nid@ NOTE (.*)/", $noterec, $n1match);
if ($nt==1) {
	$shnote = print_note_record($n1match[1], 1, $noterec, false, true);
}else{
	$shnote = "No Text";
}

echo '<tr><td align="center" class="descriptionbox">Shared Note</td><td class="optionbox">';
echo $shnote;
echo "<br />";
echo "</td></tr>";

foreach ($shnotefacts as $fact) {
	if ($fact->getTag()=='CONT') {
/*
	} elseif ($fact->getTag()=='CONT') {
		if ($fact->getLineNumber()=='1') {
			echo '<tr><td align="center" class="descriptionbox">Shared Note</td><td class="optionbox">';
		}else{
			echo '<tr><td align="center" class="descriptionbox"></td><td class="optionbox">';
		}
		echo ereg_replace("1 CONT", "", PrintReady($fact->getGedcomRecord()) );
		echo "<br />";
		echo "</td></tr>";
*/
	} else {
		print_fact($fact);
	}
}

			
// Print media
print_main_media($controller->nid);
		
// new fact link
if (!$controller->isPrintPreview() && $controller->userCanEdit()) {
	print_add_new_fact($controller->nid, $shnotefacts, 'NOTE');
	// new media
	echo '<tr><td class="descriptionbox">';
	print_help_link('add_media_help', 'qm', 'add_media_lbl');
	echo $pgv_lang['add_media_lbl'] . '</td>';
	echo '<td class="optionbox">';
	echo '<a href="javascript: ', $pgv_lang['add_media_lbl'], '" onclick="window.open(\'addmedia.php?action=showmediaform&linktoid=', $controller->nid, '\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">', $pgv_lang['add_media'], '</a>';
	echo '<br />';
	echo '<a href="javascript:;" onclick="window.open(\'inverselink.php?linktoid='.$controller->nid.'&linkto=shnote\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang['link_to_existing_media'].'</a>';
	echo '</td></tr>';
}
echo '</table><br /><br /></td></tr><tr class="center"><td colspan="2">';

// Print the tasks table
//BH NOT WORKING YET
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
