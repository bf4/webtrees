<?php
/**
 * Displays the details about a repository record.  Also shows how many sources
 * reference this repository.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008 PGV Development Team.  All rights reserved.
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
 * @subpackage Charts
 * @version $Id$
 */

require("config.php");
require_once("includes/functions_print_lists.php");
require_once("includes/controllers/repository_ctrl.php");

global $linkToID;

print_header($controller->getPageTitle());
$linkToID = $controller->rid;	// -- Tell addmedia.php what to link to

// LBox ============================================================================= 
// Get Javascript variables from lb_config.php --------------------------- 
 if (file_exists("modules/lightbox/album.php")) {
	include('modules/lightbox/lb_defaultconfig.php');
	if (file_exists('modules/lightbox/lb_config.php')) include('modules/lightbox/lb_config.php');
	include('modules/lightbox/functions/lb_call_js.php');	
}
// LBox  ============================================================================	

loadLangFile("lb_lang");	// Load Lightbox language file	

?>
<?php if ($controller->repository->isMarkedDeleted()) print "<span class=\"error\">".$pgv_lang["record_marked_deleted"]."</span>"; ?>
<script language="JavaScript" type="text/javascript">
<!--
	function show_gedcom_record() {
		var recwin = window.open("gedrecord.php?pid=<?php print $controller->rid ?>", "_blank", "top=0,left=0,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");
	}
	function showchanges() {
		window.location = '<?php print $SCRIPT_NAME.normalize_query_string($QUERY_STRING."&show_changes=yes"); ?>';
	}
//-->
</script>
<table class="list_table">
	<tr>
		<td>
<?php
	if ($controller->accept_success) print "<b>".$pgv_lang["accept_successful"]."</b><br />";
?>
			<span class="name_head"><?php print PrintReady($controller->repository->getFullName()); if ($SHOW_ID_NUMBERS) print " " . getLRM() . "(".$controller->rid.")" . getLRM(); ?></span><br />
		</td>
		<td valign="top" class="noprint">
		<?php if (!$controller->isPrintPreview()) {
			 $editmenu = $controller->getEditMenu();
			 $othermenu = $controller->getOtherMenu();
			 if ($editmenu!==false || $othermenu!==false) {
		?>
			<table class="sublinks_table" cellspacing="4" cellpadding="0">
				<tr>
					<td class="list_label <?php print $TEXT_DIRECTION?>" colspan="2"><?php print $pgv_lang['repo_menu']?></td>
				</tr>
				<tr>
					<?php if ($editmenu!==false) { ?>
					<td class="sublinks_cell <?php print $TEXT_DIRECTION?>">
					<?php $editmenu->printMenu(); ?>
					</td>
					<?php
					}
					if ($othermenu!==false) {
					?>

					<td class="sublinks_cell <?php print $TEXT_DIRECTION?>">
					<?php $othermenu->printMenu(); ?>
					</td>
					<?php } ?>
				</tr>
			</table>
			<?php }
		}
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table class="facts_table">
<?php
$repositoryfacts = $controller->repository->getRepositoryFacts();
foreach($repositoryfacts as $indexval => $fact) {
	if (!empty($fact)) {
		if ($fact->getTag()=="NOTE") {
			print_main_notes($fact->getGedcomRecord(), 1, $rid, $fact->getLineNumber());
		}
		else {
			print_fact($fact);
		}
	}
}
// Print media
print_main_media($controller->rid);

//-- new fact link
if ((!$controller->isPrintPreview())&&($controller->userCanEdit())) {
	print_add_new_fact($controller->rid, $repositoryfacts, "SOUR");
		// -- new media
	print "<tr><td class=\"descriptionbox\">";
	print_help_link("add_media_help", "qm", "add_media_lbl");
	print $pgv_lang["add_media_lbl"] . "</td>";
	print "<td class=\"optionbox\">";
	print "<a href=\"javascript: ".$pgv_lang["add_media_lbl"]."\" onclick=\"window.open('addmedia.php?action=showmediaform&linktoid={$controller->rid}', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1'); return false;\">".$pgv_lang["add_media"]."</a>";
	print "<br />\n";
	print '<a href="javascript:;" onclick="window.open(\'inverselink.php?linktoid='.$controller->rid.'&linkto=repository\', \'_blank\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang["link_to_existing_media"].'</a>';
	print "</td></tr>\n";

}
?>
		</table>
		<br /><br />
		</td></tr>
		<tr class="center"><td colspan="2">
<?php

$query = "REPO @$rid@";
// -- array of sources
$mysourcelist = array();

$mysourcelist = search_sources($query);
uasort($mysourcelist, "itemsort");
$cs=count($mysourcelist);

print_sour_table($mysourcelist);

?>
	<br />
	<br />
	</td>
</tr>
</table>
<br /><br />
<?php print_footer();
?>
