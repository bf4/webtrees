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

define('PGV_FAMILY_NAV_PHP', '');

// -----------------------------------------------------------------------------
// Function Family Nav for PHPGedView - called by individual_ctrl.php
// -----------------------------------------------------------------------------
// function family_nav() {
// ------------------------------------------------------------------------------

global $edit, $tabno, $mediacnt, $GEDCOM, $pid;

$edit=$edit;

// echo "DEFAULT TAB =" . $this->default_tab ;

// Set the tab page we are on =======================================================================
// If googlemaps is NOT installed --------------
if (file_exists('modules/googlemap/defaultconfig.php')) {
	if ($this->default_tab == "9") {
		$tabno="8";
	// If we are on the Album page and googlemaps IS installed -------------------
	}elseif ($this->default_tab == "8") {
		$tabno="8";
	// If we are on the Details page -----------------------------------------------------
	}elseif ($this->default_tab == "1") {
		$tabno="0";
	// If all fails, go to the Details page ------------------------------------------------
	}else{
		$tabno="0";
	}
// If googlemaps is NOT installed --------------
}else{
	if ($this->default_tab == "8") {
		$tabno="7";
	// If we are on the Album page and googlemaps IS installed -------------------
	}elseif ($this->default_tab == "7") {
		$tabno="7";
	// If we are on the Details page -----------------------------------------------------
	}elseif ($this->default_tab == "1") {
		$tabno="0";
	// If all fails, go to the Details page ------------------------------------------------
	}else{
		$tabno="0";
	}
}

// ================================================================================================

//     Start Family Nav Table ----------------------------
	echo "<table width='230' cellpadding=\"0\">";
		global $pgv_lang, $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES;
		$personcount=0;
		$families = $this->indi->getChildFamilies();

		//-- parent families ---------------------------------------------------------------------------------------------------
		foreach($families as $famid=>$family) {
			$label = $this->indi->getChildFamilyLabel($family);
			$people = $this->buildFamilyList($family, "parents");
			$styleadd = "";

			?>
			<tr>
				<td style="padding-bottom: 4px;" align="center" colspan="2"><b><?php echo $pgv_lang["parent_family"] ?></b></td>
			</tr>
			<?php

			if (isset($people["husb"])) {
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap"><?php print $people["husb"]->getLabel(); ?></td>
					<td class="<?php print $this->getPersonStyle($people["husb"]); ?>">
						<?php
						if ( ($people["husb"]->canDisplayDetails()) ) {
							print "<a href=\"".encode_url("individual.php?pid=".$people["husb"]->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
							print "&nbsp;" . PrintReady($people["husb"]->getFullName());
							print "</a>" . "\n" ;
						}else{
							print $pgv_lang["private"];
						}
						?>
					</td>
				</tr>
				<?php
			}

			if (isset($people["wife"])) {
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>"><?php print $people["wife"]->getLabel(); ?></td>
					<td class="<?php print $this->getPersonStyle($people["wife"]); ?>">
						<?php
						if ( ($people["wife"]->canDisplayDetails()) ) {
							print "<a href=\"".encode_url("individual.php?pid=".$people["wife"]->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
							print "&nbsp;" . PrintReady($people["wife"]->getFullName());
							print "</a>" . "\n" ;
						}else{
							print $pgv_lang["private"];
						}
						?>
					</td>
				</tr>
				<?php
			}

			if (isset($people["children"])) {
				$elderdate = $family->getMarriageDate();
				foreach($people["children"] as $key=>$child) {
					?>
					<tr>
						<td class="facts_label<?php print $styleadd; ?>"><?php print $child->getLabel(); ?></td>
						<td class="<?php print $this->getPersonStyle($child); ?>">
							<?php
							if ( ($child->canDisplayDetails()) ) {
								if ($pid == $child->getXref()) {
									print "&nbsp;" . PrintReady($child->getFullName());
									print "\n" ;
								}else{
									print "<a href=\"".encode_url("individual.php?pid=".$child->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
									print "&nbsp;" . PrintReady($child->getFullName());
									print "</a>" . "\n" ;
								}
							}else{
								print $pgv_lang["private"];
							}
							//print_pedigree_person($child->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++); ?>
						</td>
					</tr>
					<?php
					$elderdate = $child->getBirthDate(false);
				}
			}
		}

		//-- step families ---------------------------------------------------------------------------------------------------
		foreach($this->indi->getStepFamilies() as $famid=>$family) {
			$label = $this->indi->getStepFamilyLabel($family);
			$people = $this->buildFamilyList($family, "step");
			if ($people){
				echo "<tr><td><br /></td><td></td></tr>";
			}

			$styleadd = "";
			$elderdate = "";
			if (isset($people["husb"])) {
				?>
				<tr>
					<td style="padding-bottom: 4px;" align="center" colspan="2"><b><?php echo $pgv_lang["step_parent_family"] ?></b></td>
				</tr>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap">
						<?php
						if ($people["husb"]->getLabel() == ".") {
							print $pgv_lang["stepdad"];
						}else{
							print $people["husb"]->getLabel();
						}
						?>
					</td>
					<td class="<?php print $this->getPersonStyle($people["husb"]); ?>" >
						<?php
						if ( ($people["husb"]->canDisplayDetails()) ) {
							print "<a href=\"".encode_url("individual.php?pid=".$people["husb"]->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
							print "&nbsp;" . PrintReady($people["husb"]->getFullName());
							print "</a>" . "\n" ;
						}else{
							print $pgv_lang["private"];
						}
						?>
					</td>
				</tr>
				<?php
				$elderdate = $people["husb"]->getBirthDate(false);
			}

			$styleadd = "";
			if (isset($people["wife"])) {
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap">
						<?php
						if ($people["wife"]->getLabel() == ".") {
							print $pgv_lang["stepmom"];
						}else{
							print $people["wife"]->getLabel();
						}
						?>
					</td>
					<td class="<?php print $this->getPersonStyle($people["wife"]); ?>">
						<?php
						if ( ($people["wife"]->canDisplayDetails()) ) {
							print "<a href=\"".encode_url("individual.php?pid=".$people["wife"]->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
							print "&nbsp;" . PrintReady($people["wife"]->getFullName());
							print "</a>" . "\n" ;
						}else{
							print $pgv_lang["private"];
						}
						?>
					</td>
				</tr>
				<?php
			}

			$styleadd = "";
			if (isset($people["children"])) {
				$elderdate = $family->getMarriageDate();
				foreach($people["children"] as $key=>$child) {
					?>
					<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap">
						<?php
							print $child->getLabel();
						?>
					</td>
					<td class="<?php print $this->getPersonStyle($child); ?>">
						<?php
						if ( ($child->canDisplayDetails()) ) {
							print "<a href=\"".encode_url("individual.php?pid=".$child->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
							print "&nbsp;" . PrintReady($child->getFullName());
							print "</a>" . "\n" ;
						}else{
							print $pgv_lang["private"];
						}
						?>
					</td>
					</tr>
					<?php
					//$elderdate = $child->getBirthDate(false);
				}
			}
		}

		//-- spouses and children ---------------------------------------------------------------------------------------------------
		$families = $this->indi->getSpouseFamilies();

		foreach($families as $famid=>$family) {
		echo "<tr><td><br /></td><td></td></tr>";
		?>
			<tr>
				<td style="padding-bottom: 4px;" align="center" colspan="2"><b><?php echo $pgv_lang["immediate_family"] ?></b></td>
			</tr>
		<?php

			//$personcount = 0;
			$people = $this->buildFamilyList($family, "spouse");
			if ($this->indi->equals($people["husb"])){
				$spousetag = 'WIFE';
			}else{
				$spousetag = 'HUSB';
			}
			$styleadd = "";

			if ( isset($people["husb"]) && $spousetag == 'HUSB' ) {
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap"><?php print $people["husb"]->getLabel(); ?></td>
					<td class="<?php print $this->getPersonStyle($people["husb"]); ?>">
						<?php
							if ( ($people["husb"]->canDisplayDetails()) ) {
								if ($pid == $people["husb"]->getXref()) {
									print "&nbsp;" . PrintReady($people["husb"]->getFullName());
									print "\n" ;
								}else{
									print "<a href=\"".encode_url("individual.php?pid=".$people["husb"]->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
									print "&nbsp;" . PrintReady($people["husb"]->getFullName());
									print "</a>" . "\n" ;
								}
							}else{
								print $pgv_lang["private"];
							}
						?>
					</td>
				</tr>
				<?php
			}

			if ( isset($people["wife"]) && $spousetag == 'WIFE') {
				?>
				<tr>
					<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap"><?php print $people["wife"]->getLabel(); ?></td>
					<td class="<?php print $this->getPersonStyle($people["wife"]); ?>">
						<?php
							if ( ($people["wife"]->canDisplayDetails()) ) {
								if ($pid == $people["wife"]->getXref()) {
									print "&nbsp;" . PrintReady($people["wife"]->getFullName());
									print "\n" ;
								}else{
									print "<a href=\"".encode_url("individual.php?pid=".$people["wife"]->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
									print "&nbsp;" . PrintReady($people["wife"]->getFullName());
									print "</a>" . "\n" ;
								}
							}else{
								print $pgv_lang["private"];
							}
						?>
					</td>
				</tr>
				<?php
			}

			$styleadd = "";
			if (isset($people["children"])) {
				foreach($people["children"] as $key=>$child) {
					?>
					<tr>
						<td class="facts_label<?php print $styleadd; ?>" nowrap="nowrap"><?php print $child->getLabel(); ?></td>
						<td class="<?php print $this->getPersonStyle($child); ?>">
							<?php
							if ( ($child->canDisplayDetails()) ) {
								print "<a href=\"".encode_url("individual.php?pid=".$child->getXref()."&tab={$tabno}&gedcom={$GEDCOM}")."\">";
								print "&nbsp;" . PrintReady($child->getFullName());
								print "</a>" . "\n" ;
							}else{
								print $pgv_lang["private"];
							}
							?>
						</td>
					</tr>
					<?php
				}
			}

		}
		echo "</table>";


// -----------------------------------------------------------------------------
// }
// -----------------------------------------------------------------------------
// End Family Nav Table
// -----------------------------------------------------------------------------



?>
