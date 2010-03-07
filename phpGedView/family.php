<?php
/**
 * Parses gedcom file and displays information about a family.
 *
 * You must supply a $famid value with the identifier for the family.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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

define('PGV_SCRIPT_NAME', 'family.php');
require './config.php';
require_once PGV_ROOT.'includes/controllers/family_ctrl.php';

$controller = new FamilyController();
$controller->init();

print_header($controller->getPageTitle());
// completely prevent display if privacy dictates so
if (!$controller->family){
	echo "<b>", $pgv_lang["unable_to_find_record"], "</b><br /><br />";
	print_footer();
	exit;
}
else if (!$controller->family->canDisplayDetails()) {
	print_privacy_error($CONTACT_EMAIL);
	print_footer();
	exit;
}

// LB added for Lightbox viewer ==============================================================
if (PGV_USE_LIGHTBOX) {
	require PGV_ROOT.'modules/lightbox/lb_defaultconfig.php';
	require_once PGV_ROOT.'modules/lightbox/functions/lb_call_js.php';
}
// LB ======================================================================================

$PEDIGREE_FULL_DETAILS = "1";		// Override GEDCOM configuration
$show_full = "1";

?>
<?php if ($controller->family->isMarkedDeleted()) echo "<span class=\"error\">".$pgv_lang["record_marked_deleted"]."</span>"; ?>
<script language="JavaScript" type="text/javascript">
<!--
	function show_gedcom_record(shownew) {
		fromfile="";
		if (shownew=="yes") fromfile='&fromfile=1';
		var recwin = window.open("gedrecord.php?pid=<?php echo $controller->getFamilyID(); ?>"+fromfile, "_blank", "top=50, left=50, width=600, height=400, scrollbars=1, scrollable=1, resizable=1");
	}
	function showchanges() {
		window.location = 'family.php?famid=<?php echo $controller->famid; ?>&show_changes=yes';
	}
//-->
</script>
<table align="center" width="95%">
	<tr>
		<td>
		<?php
		print_family_header($controller->famid);
		?>
		</td>
		<td>
			<?php
			if (empty($SEARCH_SPIDER) && !$controller->isPrintPreview()) : 
			if ($controller->accept_success)
			{
				print "<b>".$pgv_lang["accept_successful"]."</b><br />";
			}
			endif;	// view != preview
			?>
		</td>
	</tr>
</table>
<table align="center" width="95%">
	<tr valign="top">
		<td align="left" valign="top" style="width: <?php echo $pbwidth+30 ?>px;"><!--//List of children//-->
			<?php print_family_children($controller->getFamilyID());?>
		</td>
		<td> <!--//parents pedigree chart and Family Details//-->
			<table align="left" width="100%">
				<tr>
					<td class="subheaders" valign="top"><?php echo $pgv_lang["parents"];?></td>
					<td class="subheaders" valign="top"><?php echo $pgv_lang["gparents"];?></td>
				</tr>
				<tr>
					<td colspan="2">
						<table><tr><td> <!--//parents pedigree chart //-->
						<?php
						echo print_family_parents($controller->getFamilyID());
						if (!$controller->isPrintPreview() && $controller->display && PGV_USER_CAN_EDIT) {
							$husb = $controller->getHusband();
							if (empty($husb)) { ?>
								<?php print_help_link("edit_add_parent", "qm"); ?>
			<a href="javascript <?php echo $pgv_lang["add_father"]; ?>" onclick="return addnewparentfamily('', 'HUSB', '<?php echo $controller->famid; ?>');"><?php echo $pgv_lang["add_father"]; ?></a><br />
						<?php }
							$wife = $controller->getWife();
							if (empty($wife))  { ?>
								<?php print_help_link("edit_add_parent", "qm"); ?>
			<a href="javascript <?php echo $pgv_lang["add_mother"]; ?>" onclick="return addnewparentfamily('', 'WIFE', '<?php echo $controller->famid; ?>');"><?php echo $pgv_lang["add_mother"]; ?></a><br />
						<?php }
						}
						?>
						</td></tr></table>
					</td>
				</tr>
				<tr>
					<td align="left" colspan="2">
						<br /><hr />
						<?php print_family_facts($controller->family);?>
					</td>
				</tr>
			</table>
		</td>
		<td class="noprint"> <!--//blank cell for access keys//-->
			<div class="accesskeys">
			<?php
				if (empty($SEARCH_SPIDER)) {
				?>
				<a class="accesskeys" href="<?php echo 'timeline.php?pids[0]=' . $controller->parents['HUSB'].'&amp;pids[1]='.$controller->parents['WIFE'];?>" title="<?php echo $pgv_lang['parents_timeline'] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang['accesskey_family_parents_timeline']; ?>"><?php echo $pgv_lang['parents_timeline'] ?></a>
				<a class="accesskeys" href="<?php echo 'timeline.php?' . $controller->getChildrenUrlTimeline();?>" title="<?php echo $pgv_lang["children_timeline"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang['accesskey_family_children_timeline']; ?>"><?php echo $pgv_lang['children_timeline'] ?></a>
				<a class="accesskeys" href="<?php echo 'timeline.php?pids[0]=' .$controller->getHusband().'&amp;pids[1]='.$controller->getWife().'&amp;'.$controller->getChildrenUrlTimeline(2);?>" title="<?php echo $pgv_lang['family_timeline'] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang['accesskey_family_timeline']; ?>"><?php echo $pgv_lang['family_timeline'] ?></a>
					<?php if ($SHOW_GEDCOM_RECORD) { ?>
				<a class="accesskeys" href="javascript:show_gedcom_record();" title="<?php echo $pgv_lang["view_gedcom"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang["accesskey_family_gedcom"]; ?>"><?php echo $pgv_lang["view_gedcom"] ?></a>
					<?php } ?>
			<?php } ?>
			</div>
			<?php
				if ($controller->accept_success) {
					echo "<b>".$pgv_lang["accept_successful"]."</b><br />";
				}
			?>
		</td>
	</tr>
</table>
<br />
<?php
if(empty($SEARCH_SPIDER))
	print_footer();
else {
	if($SHOW_SPIDER_TAGLINE)
		echo $pgv_lang["label_search_engine_detected"].": ".$SEARCH_SPIDER;
	echo "\n</div>\n\t</body>\n</html>";
}