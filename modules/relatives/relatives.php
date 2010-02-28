<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 PGV Development Team. All rights reserved.
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
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once PGV_ROOT.'includes/classes/class_tab.php';

class relatives_Tab extends Tab {
	

	/**
	* print family header
	* @param String family id
	* @param String family label
	* @return html table
	*/
	function printFamilyHeader($famid, $label) {
		global $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES, $SHOW_ID_NUMBERS, $SEARCH_SPIDER;
	?>
		<table>
			<tr>
				<td><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["cfamily"]["small"]; ?>" border="0" class="icon" alt="" /></td>
				<td><span class="subheaders"><?php print PrintReady($label); ?></span>
				<?php if ((!$this->controller->isPrintPreview())&&(empty($SEARCH_SPIDER))) { ?>
					- <a href="family.php?famid=<?php print $famid; ?>">[<?php print $pgv_lang["view_family"]; ?><?php if ($SHOW_ID_NUMBERS) print " " . getLRM() . "($famid)" . getLRM(); ?>]</a>
				<?php }?>
				</td>
			</tr>
		</table>
	<?php
	}

	/**
	* print parents informations
	* @param Family family
	* @param Array people
	* @param String family type
	* @return html table rows
	*/
	function printParentsRows(&$family, &$people, $type) {
		global $personcount, $pgv_changes, $pgv_lang, $factarray;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $lang_short_cut, $LANGUAGE;
		$elderdate = "";
		//-- new father/husband
		$styleadd = "";
		if (isset($people["newhusb"])) {
			$styleadd = "red";
			?>
			<tr>
				<td class="facts_labelblue"><?php print $people["newhusb"]->getLabel(); ?></td>
				<td class="<?php print $this->controller->getPersonStyle($people["newhusb"]); ?>">
					<?php print_pedigree_person($people["newhusb"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++); ?>
				</td>
			</tr>
			<?php
			$elderdate = $people["newhusb"]->getBirthDate();
		}
		//-- father/husband
		if (isset($people["husb"])) {
			?>
			<tr>
				<td class="facts_label<?php print $styleadd; ?>"><?php print $people["husb"]->getLabel(); ?></td>
				<td class="<?php print $this->controller->getPersonStyle($people["husb"]); ?>">
					<?php print_pedigree_person($people["husb"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++); ?>
				</td>
			</tr>
			<?php
			$elderdate = $people["husb"]->getBirthDate();
		}
		//-- missing father
		if ($type=="parents" && !isset($people["husb"]) && !isset($people["newhusb"])) {
			if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
				?>
				<tr>
					<td class="facts_label"><?php print $pgv_lang["add_father"]; ?></td>
					<td class="facts_value"><?php print_help_link("edit_add_parent_help", "qm"); ?> <a href="javascript <?php print $pgv_lang["add_father"]; ?>" onclick="return addnewparentfamily('<?php print $this->controller->pid; ?>', 'HUSB', '<?php print $family->getXref(); ?>');"><?php print $pgv_lang["add_father"]; ?></a></td>
				</tr>
				<?php
			}
		}
		//-- missing husband
		if ($type=="spouse" && $this->controller->indi->equals($people["wife"]) && !isset($people["husb"]) && !isset($people["newhusb"])) {
			if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
				?>
				<tr>
					<td class="facts_label"><?php print $pgv_lang["add_husb"]; ?></td>
					<td class="facts_value"><a href="javascript:;" onclick="return addnewspouse('<?php print $family->getXref(); ?>', 'HUSB');"><?php print $pgv_lang["add_husb_to_family"]; ?></a></td>
				</tr>
				<?php
			}
		}
		//-- new mother/wife
		$styleadd = "";
		if (isset($people["newwife"])) {
			$styleadd = "red";
			?>
			<tr>
				<td class="facts_labelblue"><?php print $people["newwife"]->getLabel($elderdate); ?></td>
				<td class="<?php print $this->controller->getPersonStyle($people["newwife"]); ?>">
					<?php print_pedigree_person($people["newwife"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++); ?>
				</td>
			</tr>
			<?php
		}
		//-- mother/wife
		if (isset($people["wife"])) {
			?>
			<tr>
				<td class="facts_label<?php print $styleadd; ?>"><?php print $people["wife"]->getLabel($elderdate); ?></td>
				<td class="<?php print $this->controller->getPersonStyle($people["wife"]); ?>">
					<?php print_pedigree_person($people["wife"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++); ?>
				</td>
			</tr>
			<?php
		}
		//-- missing mother
		if ($type=="parents" && !isset($people["wife"]) && !isset($people["newwife"])) {
			if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
				?>
				<tr>
					<td class="facts_label"><?php print $pgv_lang["add_mother"]; ?></td>
					<td class="facts_value"><?php print_help_link("edit_add_parent_help", "qm"); ?> <a href="javascript:;" onclick="return addnewparentfamily('<?php print $this->controller->pid; ?>', 'WIFE', '<?php print $family->getXref(); ?>');"><?php print $pgv_lang["add_mother"]; ?></a></td>
				</tr>
				<?php
			}
		}
		//-- missing wife
		if ($type=="spouse" && $this->controller->indi->equals($people["husb"]) && !isset($people["wife"]) && !isset($people["newwife"])) {
			if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
				?>
				<tr>
					<td class="facts_label"><?php print $pgv_lang["add_wife"]; ?></td>
					<td class="facts_value"><a href="javascript:;" onclick="return addnewspouse('<?php print $family->getXref(); ?>', 'WIFE');"><?php print $pgv_lang["add_wife_to_family"]; ?></a></td>
				</tr>
				<?php
			}
		}
		//-- marriage row
		if ($family->getMarriageRecord()!="" || PGV_USER_CAN_EDIT) {
			$styleadd = "";
			$date = $family->getMarriageDate();
			$place = $family->getMarriagePlace();
			$famid = $family->getXref();
			if (!$date && $this->controller->show_changes && isset($pgv_changes[$famid."_".$GEDCOM])) {
				$famrec = find_updated_record($famid);
				$marrrec = get_sub_record(1, "1 MARR", $famrec);
				if ($marrrec!=$family->getMarriageRecord()) {
					$date = new GedcomDate(get_gedcom_value("MARR:DATE", 1, $marrrec, '', false));
					$place = get_gedcom_value("MARR:PLAC", 1, $marrrec, '', false);
					$styleadd = "blue";
				}
			}
			?>
			<tr>
				<td class="facts_label"><br />
				</td>
				<td class="facts_value<?php print $styleadd ?>">
					<?php //echo "<span class=\"details_label\">".$factarray["NCHI"].": </span>".$family->getNumberOfChildren()."<br />";?>
					<?php if ($date && $date->isOK() || $place) {
						$marr_type = "MARR_".strtoupper($family->getMarriageType());
						if (isset($factarray[$marr_type])) echo "<span class=\"details_label\">".$factarray[$marr_type].": </span>";
						else echo "<span class=\"details_label\">".$factarray["MARR"].": </span>".$family->getMarriageType();
						if ($date) {
							echo $date->Display(false);
							if (!empty($place)) echo ' -- ';
						}
						if (!empty($place)) echo $place;
					}
					else if (get_sub_record(1, "1 _NMR", find_family_record($famid, PGV_GED_ID))) {
						// Allow special processing for different languages
						$func="fact_NMR_localisation_{$lang_short_cut[$LANGUAGE]}";
						if (function_exists($func)) {
							// Localise the _NMR facts
							$func("_NMR", $famid);
						}
						echo $factarray["_NMR"];
					}
					else if (get_sub_record(1, "1 _NMAR", find_family_record($famid, PGV_GED_ID))) {
						// Allow special processing for different languages
						$func="fact_NMR_localisation_{$lang_short_cut[$LANGUAGE]}";
						if (function_exists($func)) {
							// Localise the _NMR facts
							$func("_NMAR", $famid);
						}
						echo $factarray["_NMAR"];
					}
					else if ($family->getMarriageRecord()=="" && $this->controller->canedit) {
						print "<a href=\"#\" onclick=\"return add_new_record('".$famid."', 'MARR');\">".$pgv_lang['add_marriage']."</a>";
					}
					else {
						$factdetail = explode(' ', trim($family->getMarriageRecord()));
						if ($family->getMarriageType())
							$marr_type = "MARR_".strtoupper($family->getMarriageType());
						else
							$marr_type = "MARR";
						if (isset($factarray[$marr_type])) {
							if (isset($factdetail))
								if (count($factdetail) == 3)
									if (strtoupper($factdetail[2]) == "Y")
										echo "<span class=\"details_label\">".$factarray[$marr_type].": </span>".$pgv_lang["yes"];
									else if (strtoupper($factdetail[2]) == "N")
										echo "<span class=\"details_label\">".$factarray[$marr_type].": </span>".$pgv_lang["no"];
						}
						else echo "<span class=\"details_label\">".$factarray["MARR"].": </span>".$family->getMarriageType();
					}
					?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	* print children informations
	* @param Family family
	* @param Array people
	* @param String family type
	* @return html table rows
	*/
	function printChildrenRows(&$family, &$people, $type) {
		global $personcount, $pgv_lang, $factarray;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		$elderdate = $family->getMarriageDate();
		foreach($people["children"] as $key=>$child) {
			$label = $child->getLabel();
			if ($label[0]=='+')
				$styleadd = "blue";
			else if ($label[0]=='-')
				$styleadd = "red";
			else
				$styleadd = "";
			?>
			<tr>
				<td class="facts_label<?php print $styleadd; ?>"><?php if ($styleadd=="red") print $child->getLabel(); else print $child->getLabel($elderdate, $key+1); ?></td>
				<td class="<?php print $this->controller->getPersonStyle($child); ?>">
				<?php
				print_pedigree_person($child->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				?>
				</td>
			</tr>
			<?php
			$elderdate = $child->getBirthDate();
		}
		if (isset($family) && !$this->controller->isPrintPreview() && $this->controller->canedit) {
			if ($type == "spouse") {
				$action = "add_son_daughter";
				$child_m = "son";
				$child_f = "daughter";
			}
			else {
				$action = "add_sibling";
				$child_m = "brother";
				$child_f = "sister";
			}
		?>
			<tr>
				<td class="facts_label">
					<?php if (PGV_USER_CAN_EDIT && isset($people["children"][1])) {?>
					<a href="javascript:;" onclick="reorder_children('<?php print $family->getXref(); ?>');tabswitch(5);"><img src="images/topdown.gif" alt="" border="0" /> <?php print $pgv_lang['reorder_children']; ?></a>
					<?php }?>
				</td>
				<td class="facts_value"><?php print_help_link($action."_help", "qm"); ?>
					<a href="javascript:;" onclick="return addnewchild('<?php print $family->getXref(); ?>');"><?php print $pgv_lang[$action]; ?></a>
					<span style='white-space:nowrap;'>
						<a href="javascript:;" onclick="return addnewchild('<?php print $family->getXref(); ?>','M');"><?php echo Person::sexImage('M', 'small', '', $pgv_lang[$child_m]); ?></a>
						<a href="javascript:;" onclick="return addnewchild('<?php print $family->getXref(); ?>','F');"><?php echo Person::sexImage('F', 'small', '', $pgv_lang[$child_f]); ?></a>
					</span>
				</td>
			</tr>
			<?php
		}
	}
	
	public function getContent() {
		global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES, $SHOW_AGE_DIFF;
		global $pgv_changes, $GEDCOM, $ABBREVIATE_CHART_LABELS;
		global $show_full, $personcount;

		if (isset($show_full)) $saved_show_full = $show_full; // We always want to see full details here
		$show_full = 1;

		$saved_ABBREVIATE_CHART_LABELS = $ABBREVIATE_CHART_LABELS;
		$ABBREVIATE_CHART_LABELS = false; // Override GEDCOM configuration
		
		$out = "<span class=\"subheaders\">".$pgv_lang["relatives"]."</span><div id=\"relatives_content\">";

		ob_start();
		if (!$this->controller->isPrintPreview()) {
		?>
		<table class="facts_table"><tr><td style="width:20%; padding:4px"></td><td class="descriptionbox rela">
		<input id="checkbox_elder" type="checkbox" onclick="toggleByClassName('DIV', 'elderdate');" <?php if ($SHOW_AGE_DIFF) echo "checked=\"checked\"";?>/>
		<label for="checkbox_elder"><?php print_help_link("age_differences_help", "qm"); print $pgv_lang['age_differences'] ?></label>
		</td></tr></table>
		<?php
		}
		$personcount=0;
		$families = $this->controller->indi->getChildFamilies();
		if (count($families)==0) {
			print "<span class=\"subheaders\">".$pgv_lang["relatives"]."</span>";
			if (/**!$this->controller->isPrintPreview() &&**/ $this->controller->canedit) {
				?>
				<table class="facts_table">
					<tr>
						<td class="facts_value"><?php print_help_link("edit_add_parent_help", "qm"); ?><a href="javascript:;" onclick="return addnewparent('<?php print $this->controller->pid; ?>', 'HUSB');"><?php print $pgv_lang["add_father"]; ?></a></td>
					</tr>
					<tr>
						<td class="facts_value"><?php print_help_link("edit_add_parent_help", "qm"); ?><a href="javascript:;" onclick="return addnewparent('<?php print $this->controller->pid; ?>', 'WIFE');"><?php print $pgv_lang["add_mother"]; ?></a></td>
					</tr>
				</table>
				<?php
			}
		}
		//-- parent families
		foreach($families as $famid=>$family) {
			$people = $this->controller->buildFamilyList($family, "parents");
			$this->printFamilyHeader($famid, $this->controller->indi->getChildFamilyLabel($family));
			?>
			<table class="facts_table">
				<?php
				$this->printParentsRows($family, $people, "parents");
				$this->printChildrenRows($family, $people, "parents");
				?>
			</table>
		<?php
		}

		//-- step families
		foreach($this->controller->indi->getStepFamilies() as $famid=>$family) {
			$people = $this->controller->buildFamilyList($family, "step");
			$this->printFamilyHeader($famid, $this->controller->indi->getStepFamilyLabel($family));
			?>
			<table class="facts_table">
				<?php
				$this->printParentsRows($family, $people, "step");
				$this->printChildrenRows($family, $people, "step");
				?>
			</table>
		<?php
		}

		//-- spouses and children
		$families = $this->controller->indi->getSpouseFamilies();
		foreach($families as $famid=>$family) {
			$people = $this->controller->buildFamilyList($family, "spouse");
			$this->printFamilyHeader($famid, $this->controller->indi->getSpouseFamilyLabel($family));
			?>
			<table class="facts_table">
				<?php
				$this->printParentsRows($family, $people, "spouse");
				$this->printChildrenRows($family, $people, "spouse");
				?>
			</table>
		<?php
		}

		?>
		<script type="text/javascript">
		<!--
			<?php if (!$SHOW_AGE_DIFF) echo "toggleByClassName('DIV', 'elderdate');";?>
		//-->
		</script>
		<br />
		<?php
		if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
		?>
		<table class="facts_table">
		<?php if (count($families)>1) { ?>
			<tr>
				<td class="facts_value">
				<?php print_help_link("reorder_families_help", "qm"); ?>
				<a href="javascript:;" onclick="return reorder_families('<?php print $this->controller->pid; ?>');"><?php print $pgv_lang["reorder_families"]; ?></a>
				</td>
			</tr>
		<?php } ?>
			<tr>
				<td class="facts_value">
				<?php print_help_link("link_child_help", "qm"); ?>
				<a href="javascript:;" onclick="return add_famc('<?php print $this->controller->pid; ?>');"><?php print $pgv_lang["link_as_child"]; ?></a>
				</td>
			</tr>
			<?php if ($this->controller->indi->getSex()!="F") { ?>
			<tr>
				<td class="facts_value">
				<?php print_help_link("add_wife_help", "qm"); ?>
				<a href="javascript:;" onclick="return addspouse('<?php print $this->controller->pid; ?>','WIFE');"><?php print $pgv_lang["add_new_wife"]; ?></a>
				</td>
			</tr>
			<tr>
				<td class="facts_value">
				<?php print_help_link("link_new_wife_help", "qm"); ?>
				<a href="javascript:;" onclick="return linkspouse('<?php print $this->controller->pid; ?>','WIFE');"><?php print $pgv_lang["link_new_wife"]; ?></a>
				</td>
			</tr>
			<tr>
				<td class="facts_value">
				<?php print_help_link("link_new_husb_help", "qm"); ?>
				<a href="javascript:;" onclick="return add_fams('<?php print $this->controller->pid; ?>','HUSB');"><?php print $pgv_lang["link_as_husband"]; ?></a>
				</td>
			</tr>
			<?php }
			if ($this->controller->indi->getSex()!="M") { ?>
			<tr>
				<td class="facts_value">
				<?php print_help_link("add_husband_help", "qm"); ?>
				<a href="javascript:;" onclick="return addspouse('<?php print $this->controller->pid; ?>','HUSB');"><?php print $pgv_lang["add_new_husb"]; ?></a>
				</td>
			</tr>
			<tr>
				<td class="facts_value">
				<?php print_help_link("link_husband_help", "qm"); ?>
				<a href="javascript:;" onclick="return linkspouse('<?php print $this->controller->pid; ?>','HUSB');"><?php print $pgv_lang["link_new_husb"]; ?></a>
				</td>
			</tr>
			<tr>
				<td class="facts_value">
				<?php print_help_link("link_wife_help", "qm"); ?>
				<a href="javascript:;" onclick="return add_fams('<?php print $this->controller->pid; ?>','WIFE');"><?php print $pgv_lang["link_as_wife"]; ?></a>
				</td>
			</tr>
			<?php } ?>
<?php if (PGV_USER_CAN_ACCEPT) { // NOTE this function is restricted to ACCEPTORS because another bug prevents pending changes being shown on the close relatives tab of the indi page. Once that bug is fixed, this function can be opened up to all! ?>
			<tr>
				<td class="facts_value">
				<?php print_help_link("add_opf_child_help", "qm"); ?>
				<a href="javascript:;" onclick="return addopfchild('<?php print $this->controller->pid; ?>','U');"><?php print $pgv_lang["add_opf_child"]; ?></a>
				</td>
			</tr>
<?php } ?>
			<?php if (PGV_USER_GEDCOM_ADMIN) { ?>
			<tr>
				<td class="facts_value">
				<?php print_help_link("link_remote_help", "qm"); ?>
				<a href="javascript:;" onclick="return open_link_remote('<?php print $this->controller->pid; ?>');"><?php print $pgv_lang["link_remote"]; ?></a>
				</td>
			</tr>
			<?php } ?>
		</table>
		<?php } ?>
		<br />
		<?php

		$ABBREVIATE_CHART_LABELS = $saved_ABBREVIATE_CHART_LABELS; // Restore GEDCOM configuration
		unset($show_full);
		if (isset($saved_show_full)) $show_full = $saved_show_full;

		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		return $out;
	}

	public function hasContent() {
		return true;
	}
}
?>