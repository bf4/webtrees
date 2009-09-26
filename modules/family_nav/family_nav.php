<?php
/**
 * Family Navigator for phpGedView
 *
 * Display immediate family members table for fast navigation
 * ( Currently used with Facts and Details tab, and Album Tab pages )
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Includes
 * @version $Id$
 * @author Brian Holland
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_MOD_FAMILY_NAV_PHP', '');

require_once 'includes/classes/class_tab.php';
require_once 'includes/functions/functions_charts.php';

class family_nav_Tab extends Tab {

	public function hasContent() {
		return true;
	}

	public function getContent() {
		$out = '';
		ob_start();
		// -----------------------------------------------------------------------------
		// Function Family Nav for PHPGedView - called by individual_ctrl.php
		// -----------------------------------------------------------------------------
		// function family_nav() {
		// ------------------------------------------------------------------------------

		global $edit, $tabno, $mediacnt, $GEDCOM, $pid;
		$edit=$edit;
		global $show_full, $tabno;
		$show_full="1";

		$last_clicked=safe_get('tab');
		?>
		<script>
		selectedTab="<?php echo $last_clicked; ?>";
		</script>
		<?php
		
		// =====================================================================

	echo PGV_JS_START;
	echo 'function familyNavLoad(url) {
		if (selectedTab!="") {
			window.location = url+"&tab="+selectedTab;
			return false;
		}else{
			window.location = url+"&tab=0";
			return false;
		}

	}
	';
	echo PGV_JS_END;

//    Start Family Nav Table ----------------------------
		echo "<table class=\"nav_content\" cellpadding=\"0\">"; 
		global $pgv_lang, $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES, $PGV_MENUS_AS_LISTS;
		global $spouselinks, $parentlinks, $DeathYr, $BirthYr;
		global $TEXT_DIRECTION;

		$personcount=0;
		$families = $this->controller->indi->getChildFamilies();

		//-- parent families -------------------------------------------------------------
		foreach($families as $famid=>$family) {
			$label = $this->controller->indi->getChildFamilyLabel($family);
			$people = $this->controller->buildFamilyList($family, "parents");
			$styleadd = "";
			?>
			<tr>
				<td style="padding-bottom:4px;" align="center" colspan="2">
				<?php
				echo "<a style=\"padding:0px; width:100%;\" href=\"family.php?famid=".$famid."\" onclick=\"return familyNavLoad('family.php?famid=".$famid."');\">";
				echo "<b>".$pgv_lang["parent_family"]."&nbsp;&nbsp;</b><span class=\"age\">(".$famid.")</span>";
				echo "</a>";
				//echo "<a href=\"family.php?famid=".$famid."\">";
				//echo "<b>".$pgv_lang["parent_family"]."&nbsp;&nbsp;</b><span class=\"age\">(".$famid.")</span>";
				//echo "</a>";
				?>
				</td>
			</tr>
			<?php
			if (isset($people["husb"])) {
				$menu = new Menu("&nbsp;" . $people["husb"]->getLabel() . "&nbsp;". "\n");
				// $menu->addClass("", "", "submenu");
				if ($TEXT_DIRECTION=="ltr") { 
					$menu->addClass("", "", "submenu flyout2");
				}else{
					$menu->addClass("", "", "submenu flyout2rtl");
				}
				$slabel  = "</a>".$this->print_pedigree_person_nav($people["husb"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				$slabel .= $parentlinks."<a>";
				$submenu = new Menu($slabel);
				$menu->addSubMenu($submenu);

				if (PrintReady($people["husb"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["husb"]->getDeathYear()); }
				if (PrintReady($people["husb"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["husb"]->getBirthYear()); }
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
							$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
					</td>
					<td align="center" class="<?php print $this->controller->getPersonStyle($people["husb"]);?> nam">
						<?php
						print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($people["husb"]->getLinkUrl())."\" onclick=\"return familyNavLoad('".$people['husb']->getLinkUrl()."');\">";
						print PrintReady($people["husb"]->getFullName());
						print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
						print "</a>";
						?>
					</td>
				</tr>
				<?php
			}

			if (isset($people["wife"])) {
				$menu = new Menu("&nbsp;" . $people["wife"]->getLabel() . "&nbsp;". "\n");
				//$menu->addClass("", "", "submenu");
				if ($TEXT_DIRECTION=="ltr") { 
					$menu->addClass("", "", "submenu flyout2");
				}else{
					$menu->addClass("", "", "submenu flyout2rtl");
				}
				$slabel  = "</a>".$this->print_pedigree_person_nav($people["wife"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				$slabel .= $parentlinks."<a>";
				$submenu = new Menu($slabel);
				$menu->addSubMenu($submenu);

				if (PrintReady($people["wife"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["wife"]->getDeathYear()); }
				if (PrintReady($people["wife"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["wife"]->getBirthYear()); }
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
							$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
					</td>
					<td align="center" class="<?php print $this->controller->getPersonStyle($people["wife"]); ?> nam">
						<?php
						print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($people["wife"]->getLinkUrl())."\" onclick=\"return familyNavLoad('".$people['wife']->getLinkUrl()."');\">";
						print PrintReady($people["wife"]->getFullName());
						print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
						print "</a>";
						?>
					</td>
				</tr>
				<?php
			}

			if (isset($people["children"])) {
				$elderdate = $family->getMarriageDate();
				foreach($people["children"] as $key=>$child) {
				if ($pid == $child->getXref() ){
				}else{
					$menu = new Menu("&nbsp;" . $child->getLabel() . "\n");
				//	$menu->addClass("", "", "submenu");
					if ($TEXT_DIRECTION=="ltr") { 
						$menu->addClass("", "", "submenu flyout2");
					}else{
						$menu->addClass("", "", "submenu flyout2rtl");
					}
					$slabel  = "</a>".$this->print_pedigree_person_nav($child->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
					$slabel .= $spouselinks."<a>";
					$submenu = new Menu($slabel);
					$menu->addSubMenu($submenu);
				}
				if (PrintReady($child->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($child->getDeathYear()); }
				if (PrintReady($child->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($child->getBirthYear()); }

					?>
					<tr>
						<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php
						if ($pid == $child->getXref() ) {
							print $child->getLabel();
						}else{
							if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
								$menu->printMenu();
							if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						}
						?>
						</td>
						<td align="center" class="<?php print $this->controller->getPersonStyle($child); ?> nam">
							<?php
							if ($pid == $child->getXref()) {
								print "<span style=\"font: 12px tahoma, arial, helvetica, sans-serif;\">".PrintReady($child->getFullName())."</span>";
								print "<br /><span style=\"font: 9px tahoma, arial, helvetica, sans-serif;\">" . $BirthYr . " - " . $DeathYr . "</span>";
							}else{
								print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($child->getLinkUrl())."\" onclick=\"return familyNavLoad('".$child->getLinkUrl()."');\">";
								print PrintReady($child->getFullName());
								print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
								print "</a>";
							}
							?>
						</td>
					</tr>
					<?php
					$elderdate = $child->getBirthDate();
				}
			}
		}

		//-- step families ----------------------------------------------------------------
		foreach($this->controller->indi->getStepFamilies() as $famid=>$family) {
			$label = $this->controller->indi->getStepFamilyLabel($family);
			$people = $this->controller->buildFamilyList($family, "step");
			if ($people){
				echo "<tr><td><br /></td><td></td></tr>";
			}
			$styleadd = "";
			$elderdate = "";
			?>
			<tr>
				<td style="padding-bottom: 4px;" align="center" colspan="2">
				<?php
				echo "<a style=\"padding:0px; width:100%;\" href=\"family.php?famid=".$famid."\" onclick=\"return familyNavLoad('family.php?famid=".$famid."');\">";
				echo "<b>".$pgv_lang["step_parent_family"]."&nbsp;&nbsp;</b><span class=\"age\">(".$famid.")</span>";
				echo "</a>";
				//echo "<a href=\"family.php?famid=".$famid."\">";
				//echo "<b>".$pgv_lang["step_parent_family"]."&nbsp;&nbsp;</b><span class=\"age\">(".$famid.")</span>";
				//echo "</a>";
				?>
				</td>
			</tr>
			<?php

			//if (isset($people["husb"]) && $people["husb"]->getLabel() == ".") {
			if (isset($people["husb"]) ) {
				$menu = new Menu();
				if ($people["husb"]->getLabel() == ".") {
					$menu->addLabel($pgv_lang["stepdad"]."\n");
				}else{
					$menu->addLabel($people["husb"]->getLabel()."\n");
				}
				//$menu->addClass("", "", "submenu");
				if ($TEXT_DIRECTION=="ltr") { 
					$menu->addClass("", "", "submenu flyout2");
				}else{
					$menu->addClass("", "", "submenu flyout2rtl");
				}
				$slabel  = "</a>".$this->print_pedigree_person_nav($people["husb"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				$slabel .= $parentlinks."<a>";
				$submenu = new Menu($slabel);
				$menu->addSubMenu($submenu);

				if (PrintReady($people["husb"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["husb"]->getDeathYear()); }
				if (PrintReady($people["husb"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["husb"]->getBirthYear()); }
				?>

				<tr>
					<td class="facts_label<?php print $styleadd; ?>"  nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
						$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
					</td>
					<td align="center" class="<?php print $this->controller->getPersonStyle($people["husb"]); ?> nam">
						<?php
						print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($people["husb"]->getLinkUrl())."\" onclick=\"return familyNavLoad('".$people['husb']->getLinkUrl()."');\">";
						print PrintReady($people["husb"]->getFullName());
						print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
						print "</a>";
						?>
					</td>
				</tr>
				<?php
				$elderdate = $people["husb"]->getBirthDate();
			}

			$styleadd = "";
			//if (isset($people["wife"]) && $people["wife"]->getLabel() == ".") {
			if (isset($people["wife"]) ) {
				$menu = new Menu();
				if ($people["wife"]->getLabel() == ".") {
					$menu->addLabel($pgv_lang["stepmom"]."\n");
				}else{
					$menu->addLabel($people["wife"]->getLabel()."\n");
				}
				//$menu->addClass("", "", "submenu");
				if ($TEXT_DIRECTION=="ltr") { 
					$menu->addClass("", "", "submenu flyout2");
				}else{
					$menu->addClass("", "", "submenu flyout2rtl");
				}
				$slabel  = "</a>".$this->print_pedigree_person_nav($people["wife"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				$slabel .= $parentlinks."<a>";
				$submenu = new Menu($slabel);
				$menu->addSubMenu($submenu);

				if (PrintReady($people["wife"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["wife"]->getDeathYear()); }
				if (PrintReady($people["wife"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["wife"]->getBirthYear()); }
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
							$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
					</td>
					<td align="center" class="<?php print $this->controller->getPersonStyle($people["wife"]); ?> nam">
						<?php
						print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($people["wife"]->getLinkUrl())."\" onclick=\"return familyNavLoad('".$people['wife']->getLinkUrl()."');\">";
						print PrintReady($people["wife"]->getFullName());
						print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
						print "</a>";
						?>
					</td>
				</tr>
				<?php
			}

			$styleadd = "";
			if (isset($people["children"])) {
				$elderdate = $family->getMarriageDate();
				foreach($people["children"] as $key=>$child) {
					$menu = new Menu($child->getLabel()."\n");
					//$menu->addClass("", "", "submenu");
					if ($TEXT_DIRECTION=="ltr") { 
						$menu->addClass("", "", "submenu flyout2");
					}else{
						$menu->addClass("", "", "submenu flyout2rtl");
					}
					$slabel  = "</a>".$this->print_pedigree_person_nav($child->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
					$slabel .= $spouselinks."<a>";
					$submenu = new Menu($slabel);
					$menu->addSubMenu($submenu);

					if (PrintReady($child->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($child->getDeathYear()); }
					if (PrintReady($child->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($child->getBirthYear()); }
					?>
					<tr>
						<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
							$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
						</td>
						<td align="center" class="<?php print $this->controller->getPersonStyle($child); ?> nam">
							<?php
							print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($child->getLinkUrl())."\" onclick=\"return familyNavLoad('".$child->getLinkUrl()."');\">";
							print PrintReady($child->getFullName());
							print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
							print "</a>";
							?>
						</td>
					</tr>
					<?php
					//$elderdate = $child->getBirthDate();
				}
			}
		}

		//-- spouse and children --------------------------------------------------
		$families = $this->controller->indi->getSpouseFamilies();
		foreach($families as $famid=>$family) {
		echo "<tr><td><br /></td><td></td></tr>";
		?>
			<tr>
				<td style="padding-bottom: 4px;" align="center" colspan="2">
				<?php
				echo "<a style=\"padding:0px; width:100%;\" href=\"family.php?famid=".$famid."\" onclick=\"return familyNavLoad('family.php?famid=".$famid."');\">";
				echo "<b>".$pgv_lang["immediate_family"]."&nbsp;&nbsp;</b><span class=\"age\">(".$famid.")</span>";
				echo "</a>";
				//echo "<a href=\"family.php?famid=".$famid."\">";
				//echo "<b>".$pgv_lang["immediate_family"]."&nbsp;&nbsp;</b><span class=\"age\">(".$famid.")</span>";
				//echo "</a>";
				?>
				</td>
			</tr>
		<?php

			//$personcount = 0;
			$people = $this->controller->buildFamilyList($family, "spouse");
			if ($this->controller->indi->equals($people["husb"])){
				$spousetag = 'WIFE';
			}else{
				$spousetag = 'HUSB';
			}
			$styleadd = "";
			if ( isset($people["husb"]) && $spousetag == 'HUSB' ) {
				$menu = new Menu("&nbsp;" . $people["husb"]->getLabel() . "&nbsp;". "\n");
				//$menu->addClass("", "", "submenu");
				if ($TEXT_DIRECTION=="ltr") { 
					$menu->addClass("", "", "submenu flyout2");
				}else{
					$menu->addClass("", "", "submenu flyout2rtl");
				}
				$slabel  = "</a>".$this->print_pedigree_person_nav($people["husb"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				$slabel .= $parentlinks."<a>";
				$submenu = new Menu($slabel);
				$menu->addSubMenu($submenu);

				if (PrintReady($people["husb"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["husb"]->getDeathYear()); }
				if (PrintReady($people["husb"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["husb"]->getBirthYear()); }
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
						$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
					</td>
					<td align="center" class="<?php print $this->controller->getPersonStyle($people["husb"]); ?> nam">
						<?php
						if ($pid == $people["husb"]->getXref()) {
							print PrintReady($people["husb"]->getFullName());
							print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
						}else{
							print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($people["husb"]->getLinkUrl())."\" onclick=\"return familyNavLoad('".$people['husb']->getLinkUrl()."');\">";
							print PrintReady($people["husb"]->getFullName());
							print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
							print "</a>";
						}
						?>
					</td>
				</tr>
				<?php
			}

			if ( isset($people["wife"]) && $spousetag == 'WIFE') {
				$menu = new Menu("&nbsp;" . $people["wife"]->getLabel() . "&nbsp;". "\n");
				//$menu->addClass("", "", "submenu");
				if ($TEXT_DIRECTION=="ltr") { 
					$menu->addClass("", "", "submenu flyout2");
				}else{
					$menu->addClass("", "", "submenu flyout2rtl");
				}
				$slabel  = "</a>".$this->print_pedigree_person_nav($people["wife"]->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
				$slabel .= $parentlinks."<a>";
				$submenu = new Menu($slabel);
				$menu->addSubMenu($submenu);

				if (PrintReady($people["wife"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["wife"]->getDeathYear()); }
				if (PrintReady($people["wife"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["wife"]->getBirthYear()); }
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
						$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
					</td>
					<td align="center" class="<?php print $this->controller->getPersonStyle($people["wife"]); ?> nam">
						<?php
						if ($pid == $people["wife"]->getXref()) {
							print PrintReady($people["wife"]->getFullName());
							print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
						}else{
							print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($people["wife"]->getLinkUrl())."\" onclick=\"return familyNavLoad('".$people['wife']->getLinkUrl()."');\">";
							print PrintReady($people["wife"]->getFullName());
							print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
							print "</a>";
						}
						?>
					</td>
				</tr>
				<?php
			}

			$styleadd = "";
			if (isset($people["children"])) {
				foreach($people["children"] as $key=>$child) {
					$menu = new Menu("&nbsp;" . $child->getLabel() . "&nbsp;". "\n");
					//$menu->addClass("", "", "submenu");
					if ($TEXT_DIRECTION=="ltr") { 
						$menu->addClass("", "", "submenu flyout2");
					}else{
						$menu->addClass("", "", "submenu flyout2rtl");
					}
					$slabel = "</a>".$this->print_pedigree_person_nav($child->getXref(), 2, !$this->controller->isPrintPreview(), 0, $personcount++);
					$slabel .= $spouselinks."<a>";
					$submenu = new Menu($slabel);
					$menu->addSubmenu($submenu);

					if (PrintReady($child->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($child->getDeathYear()); }
					if (PrintReady($child->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($child->getBirthYear()); }
					?>
					<tr>
						<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap" style="width:75px;">
						<?php if ($PGV_MENUS_AS_LISTS) echo "<ul>\n";
						$menu->printMenu();
						if ($PGV_MENUS_AS_LISTS) echo "</ul>\n";
						?>
						</td>
						<td align="center" class="<?php print $this->controller->getPersonStyle($child); ?> nam">
							<?php
							print "<a style=\"padding:0px; width:100%;\" href=\"".encode_url($child->getLinkUrl())."\" onclick=\"return familyNavLoad('".$child->getLinkUrl()."');\">";
							print PrintReady($child->getFullName());
							print "<font size=\"1\"><br />" . $BirthYr . " - " . $DeathYr . "</font>";
							print "</a>";
							?>
						</td>
					</tr>
					<?php
				}
			}

		}
		echo "</table>";
		echo "<br />";
		
		// -----------------------------------------------------------------------------
		// }
		// -----------------------------------------------------------------------------
		// End Family Nav Table
		// -----------------------------------------------------------------------------

		$out .= ob_get_contents();
		ob_end_clean();
		return $out;
	}

function print_pedigree_person_nav($pid, $style=1, $show_famlink=true, $count=0, $personcount="1") {
	global $HIDE_LIVE_PEOPLE, $SHOW_LIVING_NAMES, $PRIV_PUBLIC, $factarray, $ZOOM_BOXES, $LINK_ICONS, $view, $SCRIPT_NAME, $GEDCOM;
	global $pgv_lang, $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $bwidth, $bheight, $PEDIGREE_FULL_DETAILS, $SHOW_ID_NUMBERS, $SHOW_PEDIGREE_PLACES;
	global $CONTACT_EMAIL, $CONTACT_METHOD, $TEXT_DIRECTION, $DEFAULT_PEDIGREE_GENERATIONS, $OLD_PGENS, $talloffset, $PEDIGREE_LAYOUT, $MEDIA_DIRECTORY;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $ABBREVIATE_CHART_LABELS, $USE_MEDIA_VIEWER;
	global $chart_style, $box_width, $generations, $show_spouse, $show_full;
	global $CHART_BOX_TAGS, $SHOW_LDS_AT_GLANCE, $PEDIGREE_SHOW_GENDER;
	global $SEARCH_SPIDER;

	global $spouselinks, $parentlinks, $step_parentlinks, $persons, $person_step, $person_parent, $tabno, $theme_name, $spousetag;
	global $natdad, $natmom;

	if ($style != 2) $style=1;
	if (empty($show_full)) $show_full = 0;
	if (empty($PEDIGREE_FULL_DETAILS)) $PEDIGREE_FULL_DETAILS = 0;

	if (!isset($OLD_PGENS)) $OLD_PGENS = $DEFAULT_PEDIGREE_GENERATIONS;
	if (!isset($talloffset)) $talloffset = $PEDIGREE_LAYOUT;

	$person=Person::getInstance($pid);
	if ($pid==false || empty($person)) {
		$spouselinks  = false;
		$parentlinks  = false;
		$step_parentlinks = false;
	}

	$tmp=array('M'=>'','F'=>'F', 'U'=>'NN');
	$isF=$tmp[$person->getSex()];
	$spouselinks = "";
	$parentlinks = "";
	$step_parentlinks   = "";
	$disp=$person->canDisplayDetails();

	if ($person->canDisplayName()) {
		if ($show_famlink && (empty($SEARCH_SPIDER))) {
			if ($LINK_ICONS!="disabled") {
				//-- draw a box for the family flyout
				$parentlinks 		.= "<span class=\"flyout4\"><b>".$pgv_lang['parents']."</b></span><br />";
				$step_parentlinks 	.= "<span class=\"flyout4\"><b>".$pgv_lang['parents']."</b></span><br />";
				$spouselinks 		.= "<span class=\"flyout4\"><b>".$pgv_lang['family']."</b></span><br />";
				
				$persons 			 = "";
				$person_parent 		 = "";
				$person_step 		 = "";



				//-- parent families --------------------------------------
				$fams = $person->getChildFamilies();
				foreach($fams as $famid=>$family) {

					if (!is_null($family)) {
						$husb = $family->getHusband($person);
						$wife = $family->getWife($person);
						// $spouse = $family->getSpouse($person);
						$children = $family->getChildren();
						$num = count($children);

						// Husband ------------------------------
						if ($husb || $num>0) {
							if ($TEXT_DIRECTION=="ltr") {
								$title = $pgv_lang["familybook_chart"].": ".$famid;
							}else{
								$title = $famid." :".$pgv_lang["familybook_chart"];
							}
							if ($husb) {
								$person_parent="Yes";
								if ($TEXT_DIRECTION=="ltr") {
									$title = $pgv_lang["indi_info"].": ".$husb->getXref();
								}else{
									$title = $husb->getXref()." :".$pgv_lang["indi_info"];
								}
								$parentlinks .= "<a id=\"phusb\" href=\"".encode_url($husb->getLinkUrl())."\" onclick=\"return familyNavLoad('".$husb->getLinkUrl()."');\">";
								$parentlinks .= "&nbsp;".PrintReady($husb->getFullName());
								$parentlinks .= "</a>";
								$parentlinks .= "<br />";
								$natdad = "yes";
							}
						}

						// Wife ------------------------------
						if ($wife || $num>0) {
							if ($TEXT_DIRECTION=="ltr") {
								$title = $pgv_lang["familybook_chart"].": ".$famid;
							}else{
								$title = $famid." :".$pgv_lang["familybook_chart"];
							}
							if ($wife) {
								$person_parent="Yes";
								if ($TEXT_DIRECTION=="ltr") {
									$title = $pgv_lang["indi_info"].": ".$wife->getXref();
								}else{
									$title = $wife->getXref()." :".$pgv_lang["indi_info"];
								}
								$parentlinks .= "<a id=\"pwife\" href=\"".encode_url($wife->getLinkUrl())."\" onclick=\"return familyNavLoad('".$wife->getLinkUrl()."');\">";
								$parentlinks .= "&nbsp;".PrintReady($wife->getFullName());
								$parentlinks .= "</a>";
								$parentlinks .= "<br />";
								$natmom = "yes";
							}
						}
					}
				}

				//-- step families -----------------------------------------
				$fams = $person->getStepFamilies();
				foreach($fams as $famid=>$family) {
					if (!is_null($family)) {
						$husb = $family->getHusband($person);
						$wife = $family->getWife($person);
						// $spouse = $family->getSpouse($person);
						$children = $family->getChildren();
						$num = count($children);

						if ($natdad == "yes") {
						}else{
							// Husband -----------------------
							if ($husb || $num>0) {
								if ($TEXT_DIRECTION=="ltr") {
									$title = $pgv_lang["familybook_chart"].": ".$famid;
								}else{
									$title = $famid." :".$pgv_lang["familybook_chart"];
								}
								if ($husb) {
									$person_step="Yes";
									if ($TEXT_DIRECTION=="ltr") {
										$title = $pgv_lang["indi_info"].": ".$husb->getXref();
									}else{
										$title = $husb->getXref()." :".$pgv_lang["indi_info"];
									}
									$parentlinks .= "<a id=\"shusb\" href=\"".encode_url($husb->getLinkUrl())."\" onclick=\"return familyNavLoad('".$husb->getLinkUrl()."');\">";
									$parentlinks .= "&nbsp;".PrintReady($husb->getFullName());
									$parentlinks .= "</a>";
									$parentlinks .= "<br />";
								}
							}
						}

						if ($natmom == "yes") {
						}else{
							// Wife ----------------------------
							if ($wife || $num>0) {
								if ($TEXT_DIRECTION=="ltr") {
									$title = $pgv_lang["familybook_chart"].": ".$famid;
								}else{
									$title = $famid." :".$pgv_lang["familybook_chart"];
								}
								if ($wife) {
									$person_step="Yes";
									if ($TEXT_DIRECTION=="ltr") {
										$title = $pgv_lang["indi_info"].": ".$wife->getXref();
									}else{
										$title = $wife->getXref()." :".$pgv_lang["indi_info"];
									}
									$parentlinks .= "<a id=\"swife\" href=\"".encode_url($wife->getLinkUrl())."\" onclick=\"return familyNavLoad('".$wife->getLinkUrl()."');\">";
									$parentlinks .= "&nbsp;".PrintReady($wife->getFullName());
									$parentlinks .= "</a>";
									$parentlinks .= "<br />";
								}
							}
						}
					}
				}

				// Spouse Families -------------------------------------- @var $family Family
				$fams = $person->getSpouseFamilies();
				foreach($fams as $famid=>$family) {
					if (!is_null($family)) {
						$spouse = $family->getSpouse($person);
						$children = $family->getChildren();
						$num = count($children);

						// Spouse ------------------------------
						if ($spouse || $num>0) {
							if ($TEXT_DIRECTION=="ltr") {
								$title = $pgv_lang["familybook_chart"].": ".$famid;
							}else{
								$title = $famid." :".$pgv_lang["familybook_chart"];
							}
							if ($spouse) {
								if ($TEXT_DIRECTION=="ltr") {
									$title = $pgv_lang["indi_info"].": ".$spouse->getXref();
								}else{
									$title = $spouse->getXref()." :".$pgv_lang["indi_info"];
								}
								$spouselinks .= "<a id=\"spouse\" href=\"".encode_url($spouse->getLinkUrl())."\" onclick=\"return familyNavLoad('".$spouse->getLinkUrl()."');\">";
								$spouselinks .= "&nbsp;".PrintReady($spouse->getFullName());
								$spouselinks .= "</a>";
								$spouselinks .= "<br />";
								if ($spouse->getFullName() != "") {
									$persons = "Yes";
								}
							}
						}
						$spouselinks .= "<ul class=\"clist ".$TEXT_DIRECTION."\">\n";
						// Children ------------------------------   @var $child Person
						foreach($children as $c=>$child) {
							if ($child) {
								$persons="Yes";
									$title = $pgv_lang["indi_info"].": ".$child->getXref();
									$spouselinks .= "<li id=\"flyout3\">";
									$spouselinks .= "<a href=\"".encode_url($child->getLinkUrl())."\" onclick=\"return familyNavLoad('".$child->getLinkUrl()."');\">";
									$spouselinks .= PrintReady($child->getFullName());
									$spouselinks .= "</a>";
									$spouselinks .= "</li>\n";
							}
						}
						$spouselinks .= "</ul>";
					}
				}
				
				if ($persons != "Yes") {
					$spouselinks  .= "&nbsp;(".$pgv_lang['none'].")\n\t\t";
				}
				if ($person_parent != "Yes") {
					$parentlinks .= "&nbsp;(".$pgv_lang['unknown'].")\n\t\t";
				}
				if ($person_step != "Yes") {
					$step_parentlinks .= "&nbsp;(".$pgv_lang['unknown'].")\n\t\t";
				}
			}
		}
	}
}

// ==============================================================
}
?>
