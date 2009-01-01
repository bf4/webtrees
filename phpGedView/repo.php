<?php
/**
* Displays the details about a repository record.  Also shows how many sources
* reference this repository.
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
require './includes/controllers/repository_ctrl.php';
require './includes/functions/functions_print_lists.php';

$controller=new RepositoryController();
$controller->init();

// Tell addmedia.php what to link to
$linkToID=$controller->rid;

print_header($controller->getPageTitle());

// LightBox
if (is_dir('./modules/lightbox')) {
	include './modules/lightbox/lb_defaultconfig.php';
	if (file_exists('modules/lightbox/lb_config.php')) {
		include './modules/lightbox/lb_config.php';
	}
	include './modules/lightbox/functions/lb_call_js.php';
	loadLangFile('lightbox:lang');
}

if ($controller->repository->isMarkedDeleted()) {
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

echo '<table class="list_table"><tr><td>';
if ($controller->accept_success) {
	echo '<b>', $pgv_lang['accept_successful'], '</b><br />';
}
echo '<span class="name_head">', PrintReady($controller->repository->getFullName());
if ($SHOW_ID_NUMBERS) {
	echo ' ', getLRM(), '(', $controller->rid, ')', getLRM(); 
}
echo '</span><br /></td><td valign="top" class="noprint">';
if (!$controller->isPrintPreview()) {
	$editmenu=$controller->getEditMenu();
	$othermenu=$controller->getOtherMenu();
	if ($editmenu || $othermenu) {
		echo '<table class="sublinks_table" cellspacing="4" cellpadding="0">';
		echo '<tr><td class="list_label ', $TEXT_DIRECTION, '" colspan="2">', $pgv_lang['source_menu'], '</td></tr>';
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
echo '</td></tr><tr><td colspan="2"><table class="facts_table">';

$repositoryfacts=$controller->repository->getFacts();
foreach ($repositoryfacts as $fact) {
	if ($fact) {
		if ($fact->getTag()=='NOTE') {
			print_main_notes($fact->getGedcomRecord(), 1, $controller->sid, $fact->getLineNumber());
		} else {
			print_fact($fact);
		}
	}
}

// Print media
print_main_media($controller->rid);

// new fact link
if (!$controller->isPrintPreview() && $controller->userCanEdit()) {
	print_add_new_fact($controller->rid, $repositoryfacts, 'REPO');
	// new media
	echo '<tr><td class="descriptionbox">';
	print_help_link('add_media_help', 'qm', 'add_media_lbl');
	echo $pgv_lang['add_media_lbl'] . '</td>';
	echo '<td class="optionbox">';
	echo '<a href="javascript: ', $pgv_lang['add_media_lbl'], '" onclick="window.open(\'addmedia.php?action=showmediaform&linktoid=', $controller->sid, '\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">', $pgv_lang['add_media'], '</a>';
	echo '<br />';
	echo '<a href="javascript:;" onclick="window.open(\'inverselink.php?linktoid='.$controller->rid.'&linkto=repository\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang['link_to_existing_media'].'</a>';
	echo '</td></tr>';
}
echo '</table><br /><br /></td></tr><tr class="center"><td colspan="2">';


// Sources linked to this repository
if ($controller->repository->countLinkedSources()) {
	print_sour_table($controller->repository->fetchLinkedSources(), $controller->repository->getFullName());
}

echo '</td></tr></table>';

print_footer();
?>
