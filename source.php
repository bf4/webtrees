<?php
/**
* Displays the details about a source record.  Also shows how many people and families
* reference this source.
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
require './includes/controllers/source_ctrl.php';
require './includes/functions/functions_print_lists.php';

// We have finished writing to $_SESSION, so release the lock
session_write_close();

$controller=new SourceController();
$controller->init();

// Tell addmedia.php what to link to
$linkToID=$controller->sid;

print_header($controller->getPageTitle());

// If LightBox installed ---------------------------------
if ($MULTI_MEDIA && file_exists('./modules/lightbox.php')) {
	include './modules/lightbox/lb_defaultconfig.php';
	if (file_exists('./modules/lightbox/lb_config.php')) {
		include './modules/lightbox/lb_config.php';
	}
	include './modules/lightbox/functions/lb_call_js.php';
	loadLangFile('lightbox:lang');
}
if (!$controller->source){
	echo "<b>".$pgv_lang["unable_to_find_record"]."</b><br /><br />";
	print_footer();
	exit;
}
else if ($controller->source->isMarkedDeleted()) {
	echo '<span class="error">', $pgv_lang['record_marked_deleted'], '</span>';
}

echo PGV_JS_START;
echo 'function show_gedcom_record() {';
echo ' var recwin=window.open("gedrecord.php?pid=', $controller->sid, '", "_blank", "top=0,left=0,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");';
echo '}';
echo 'function showchanges() {';
echo ' window.location="source.php?sid=', $controller->sid, '&show_changes=yes"';
echo '}';
echo PGV_JS_END;

if ($controller->accept_success) {
	echo '<b>', $pgv_lang['accept_successful'], '</b><br />';
}
echo '<br /><span class="name_head">', PrintReady(htmlspecialchars($controller->source->getFullName()));
if ($SHOW_ID_NUMBERS) {
	echo ' ', getLRM(), '(', $controller->sid, ')', getLRM(); 
}
echo '</span><br />';
echo '<table class="facts_table">';

$sourcefacts=$controller->source->getFacts();
foreach ($sourcefacts as $fact) {
	if ($fact) {
		if ($fact->getTag()=='NOTE') {
			print_main_notes($fact->getGedcomRecord(), 1, $controller->sid, $fact->getLineNumber());
		} else {
			print_fact($fact);
		}
	}
}

// Print media
print_main_media($controller->sid);

// new fact link
if (!$controller->isPrintPreview() && $controller->userCanEdit()) {
	print_add_new_fact($controller->sid, $sourcefacts, 'SOUR');
	// new media
	echo '<tr><td class="descriptionbox">';
	print_help_link('add_media_help', 'qm', 'add_media_lbl');
	echo $pgv_lang['add_media_lbl'] . '</td>';
	echo '<td class="optionbox">';
	echo '<a href="javascript: ', $pgv_lang['add_media_lbl'], '" onclick="window.open(\'addmedia.php?action=showmediaform&linktoid=', $controller->sid, '\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">', $pgv_lang['add_media'], '</a>';
	echo '<br />';
	echo '<a href="javascript:;" onclick="window.open(\'inverselink.php?linktoid='.$controller->sid.'&linkto=source\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang['link_to_existing_media'].'</a>';
	echo '</td></tr>';
}
echo '</table><br /><br />';

// Print the tasks table
if (file_exists('./modules/research_assistant/research_assistant.php') && $SHOW_RESEARCH_ASSISTANT>=PGV_USER_ACCESS_LEVEL) {
	include_once './modules/research_assistant/research_assistant.php';
	$mod=new ra_functions();
	$mod->Init();
	echo $mod->getSourceTasks($controller->sid, $controller->source->getFullName());
}

// Individuals linked to this source
if ($controller->source->countLinkedIndividuals()) {
	print_indi_table($controller->source->fetchLinkedIndividuals(), $controller->source->getFullName());
}

// Families linked to this source
if ($controller->source->countLinkedFamilies()) {
	print_fam_table($controller->source->fetchLinkedFamilies(), $controller->source->getFullName());
}

// Media Items linked to this source
if ($controller->source->countLinkedMedia()) {
	print_media_table($controller->source->fetchLinkedMedia(), $controller->source->getFullName());
}

// TO DO Shared Notes linked to this source
if ($controller->source->countLinkedNotes()) {
	print_note_table($controller->source->fetchLinkedNotes(), $controller->source->getFullName());
}

print_footer();
?>
