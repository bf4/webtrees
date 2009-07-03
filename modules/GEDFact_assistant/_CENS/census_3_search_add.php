<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census Search and Add Area File
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */
if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
?>

	<table border="3">
		<?php

		//-- Search Function ------------------------------------------------------------
		?>
		<tr>
			<td align="center" class="descriptionbox"><font size=1>Search for People to add:</font></td>
		</tr>
		<tr>
			<td class="optionbox" >
				<script>
					function findindi(persid) {
						var findInput = document.getElementById('personid');
							txt = findInput.value;
						//if (txt=="") {
						//	alert('Enter a name');
						//	continue;
						//}else{
							var win02 = window.open(
								"module.php?mod=GEDFact_assistant&pgvaction=_CENS/census_3_find&callback=paste_id&action=filter&type=indi&multiple=&filter="+txt, "win02", "resizable=1, menubar=0, scrollbars=1, top=180, left=600, HEIGHT=400, WIDTH=450 ");
							if (window.focus) {win02.focus();}
						//}
					}
				</script>
				<?php
				print "<input id=personid type=\"text\" size=\"20\" STYLE=\"color: #000000;\" value=\"\" />";
				print "<a href=\"javascript: onclick=findindi()\">" ;
				print "&nbsp;<font size=\"2\">&nbsp;Find</font>";
				print '</a>';
				?>
			</td>
		</tr>
		<tr>
			<td style="border: 0px solid transparent;">
				<br /><br />
			</td>
		</tr>

				<?php
				//-- Add Family Members to Census  -------------------------------------------
				global $pgv_lang, $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES;
				global $spouselinks, $parentlinks, $DeathYr, $BirthYr;
				global $TEXT_DIRECTION, $GEDCOM; 
				// echo "CENS = " . $censyear;
				?>
				<tr>
					<td align="center" style="border: 0px solid transparent;">
						<table class="fact_table" cellspacing="0" border="0">
							<tr>
								<td align="center" colspan=3 class="descriptionbox">
									<font size=1>
									Click "H" to choose Head of family. <br />
									Click Name to add person to Census.
								</td>
							</tr>

							<tr>
								<td>
									<font size=1><br /></font>
								</td>
							</tr>
							
					<?php
					//-- Build Parent Family ---------------------------------------------------
					$personcount=0;
					$families = $this->indi->getChildFamilies();
					foreach($families as $famid=>$family) {
						$label = $this->indi->getChildFamilyLabel($family);
						$people = $this->buildFamilyList($family, "parents");
						$marrdate = $family->getMarriageDate();

						// Husband -------------------
						$styleadd = "";
						if (isset($people["husb"])) {
							$married   = GedcomDate::Compare($censdate, $marrdate);
							$nam   = $people["husb"]->getAllNames();
							$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
							$givn  = rtrim($nam[0]['givn'],'*');
							$surn  = $nam[0]['surname'];
							if (isset($nam[1])) {
								$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surnname'];
								$marn  = $nam[1]['surname'];
							}
							$menu = new Menu("&nbsp;" . $people["husb"]->getLabel() . "&nbsp;". "\n");
							$menu->addClass("", "", "submenu");
							$slabel  = print_pedigree_person_nav2($people["husb"]->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censdate);
							$slabel .= $parentlinks;
							$submenu = new Menu($slabel);
							$menu->addSubMenu($submenu);
							
							?>
							<tr>
								<td align="left" class="optionbox">
									<font size=1>
										<?php 
										//  print $people["husb"]->getLabel(); 
										$menu->printMenu();
										?>
									</font>
								</td>
								<td align="left" class="facts_value" style="display:block; background-color:#bb9900;">
									<font size=1>
										<?php 
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$people["husb"]->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($people["husb"]->canDisplayDetails()) ) {
									?>
									<a href='javaScript:insertRowToTable(" <?php
											print PrintReady($people["husb"]->getXref()) ;					 // pid = PID
										?>", "<?php 
											print PrintReady($fulln);										 // nam = Name
										?>", "<?php
											print PrintReady($people["husb"]->getLabel());					 // label = Relationship
										?>", "<?php
											print PrintReady($people["husb"]->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>=0){
												echo "M";													 // cond = Condition (Married)
											}else{
												echo "S";													 // cond = Condition (Single)
											}
										?>", "<?php
											print PrintReady($people["husb"]->getbirthyear());				 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$people["husb"]->getbirthyear());	 //  age = Census Date minus YOB
										?>", "<?php
											print "Y";														 // YMD
										?>", "<?php
											print "";														 // occu = Occupation
										?>", "<?php
											print PrintReady($people["husb"]->getcensbirthplace());			 //  birthpl = Census Place of Birth 
										?>");'><?php
											 print PrintReady($people["husb"]->getFullName());				 // Name 
										?> 
									</a> 
									<?php print "\n" ;
									}else{
										print $pgv_lang["private"];
									}
									?>
									</font>
								</td>
							</tr>
							<?php
						}
								
						if (isset($people["wife"])) {
							$married   = GedcomDate::Compare($censdate, $marrdate);
							$nam = $people["wife"]->getAllNames();
							$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
							$givn  = rtrim($nam[0]['givn'],'*');
							$surn  = $nam[0]['surname'];
							if (isset($nam[1])) {
								$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
								$marn  = $nam[1]['surname'];
							}else{
								$fulmn = $fulln;
								$marn  = $surn;
							}
							
							$menu = new Menu("&nbsp;" . $people["wife"]->getLabel() . "&nbsp;". "\n");
							$menu->addClass("", "", "submenu");
							$slabel  = print_pedigree_person_nav2($people["wife"]->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
							$slabel .= $parentlinks;
							$submenu = new Menu($slabel);
							$menu->addSubMenu($submenu);
							?>
							<tr>
								<td align="left" class="optionbox">
									<font size=1>
									<?php 
									//print $people["wife"]->getLabel(); 
									$menu->printMenu(); 
									?>
									</font>
								</td>
								<td align="left" class="facts_value" style="background-color:#bb9900;">
									<font size=1>
										<?php 
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$people["wife"]->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($people["wife"]->canDisplayDetails()) ) {
										?>
									<a href='javaScript:insertRowToTable("<?php
											print $people["wife"]->getXref() ; // pid = PID
										?>", "<?php 
											if ($married>=0 && isset($nam[1])){
												echo PrintReady($fulmn);									 // nam = Married Name
											}else{
												echo PrintReady($fulln);									 // nam = Name
											}
											?>", "<?php
											print PrintReady($people["wife"]->getLabel());					 // label = Relationship
										?>", "<?php
											print PrintReady($people["wife"]->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>=0 && isset($nam[1])){
												echo "M";													 // cond = Condition (Married)
											}else{
												echo "S";													 // cond = Condition (Single)
											}
											?>", "<?php
											print PrintReady($people["wife"]->getbirthyear());				 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$people["wife"]->getbirthyear());	 //  age = Census Date minus YOB
										?>", "<?php
											print "Y";														 // YMD
										?>", "<?php
											print "";														 // occu = Occupation
										?>", "<?php
											print PrintReady($people["wife"]->getcensbirthplace());			 //  birthpl = Census Place of Birth 
										?>");'>
										<?php 
										if ($married>=0 && isset($nam[1])){
											print PrintReady($fulmn);			 							 // Full Married Name
										} else {
											print PrintReady($fulln);			 							 // Full Name
										}
										?>
									</a> 
									<?php print "\n" ;
									}else{
										print $pgv_lang["private"];
									}
									?>
									</font>
								</td>
							</tr>
							<?php
						}
	
						if (isset($people["children"])) {
							$elderdate = $family->getMarriageDate();
							foreach($people["children"] as $key=>$child) {
								// Get child's marriage status
								$married="";
								foreach ($child->getSpouseFamilies() as $childfamily) {
									$tmp=$childfamily->getMarriageDate();
									$married = GedcomDate::Compare($censdate, $tmp);
								}
								$nam   = $child->getAllNames();
								$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
								$givn  = rtrim($nam[0]['givn'],'*');
								$surn  = $nam[0]['surname'];
								if (isset($nam[1])) {
									$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
									$marn  = $nam[1]['surname'];
								}
								
								$menu = new Menu("&nbsp;" . $child->getLabel() . "&nbsp;". "\n");
								$menu->addClass("", "", "submenu");
								$slabel  = print_pedigree_person_nav2($child->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
								$slabel .= $spouselinks;
								$submenu = new Menu($slabel);
								$menu->addSubMenu($submenu);
								
								if ($child->getXref()==$pid) {
									//Only print Head of Family in Immediate Family Block
								} else {
									?>
									<tr>
										<td align="left" class="optionbox">
											<font size=1>
											<?php 
											if ($child->getXref()==$pid) {
												print $child->getLabel();
											}else{
												$menu->printMenu();
											}
											?>
											</font>
										</td>
										<td align="left" class="facts_value" style="background-color:#bb9900;">
											<font size=1>
												<?php 
												print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$child->getXref()."&gedcom={$GEDCOM}")."\">";
												print "H";
												print "</a>";
												?>
											</font>
										</td>
										<td align="left" class="facts_value" nowrap="nowrap">
											<font size=1>
											<?php
											if ( ($child->canDisplayDetails()) ) {
												?>
												<a href='javaScript:insertRowToTable("<?php 
														print $child->getXref() ;							 // pid = PID
													?>", "<?php 
													if ($married>=0 && isset($nam[1])){
														echo PrintReady($fulmn);							 // nam = Married Name
													}else{
														echo PrintReady($fulln);							 // nam = Married Name
													}
														?>", "<?php
														if ($child->getXref()==$pid) {
															print "Head";									 // label = Head
														}else{
															print PrintReady($child->getLabel());			 // label = Relationship
														}
													?>", "<?php
														print PrintReady($child->getSex());					 // gend = Gender
													?>", "<?php
														if ($married>0) {
															echo "M";										 // cond = Condition (Married)
														} else if ($married<0 || ($married=="0") ) {
															echo "S";										 // cond = Condition (Single)
														} else {
															echo "";										 // cond = Condition (Not Known)
														}
													?>", "<?php
														print PrintReady($child->getbirthyear());			 // yob = Year of Birth
													?>", "<?php
														print PrintReady($censyear-$child->getbirthyear());	 // age = Census Date minus YOB
													?>", "<?php
														print "Y";											 // YMD
													?>", "<?php
														print "";											 // occu = Occupation
													?>", "<?php
														print PrintReady($child->getcensbirthplace());		 // birthpl = Census Place of Birth 
													?>");'><?php
														if ($married>=0 && isset($nam[1])){
															print PrintReady($fulmn);			 			 // Full Married Name
														} else {
															print PrintReady($fulln);			 			 // Full Name
														}
													?>
												</a>
												<?php print "\n" ;
											}else{
													print $pgv_lang["private"];
											}
											?>
											</font>
										</td>
									</tr>
									<?php
								}
							}
							$elderdate = $child->getBirthDate(false);
						}
					}
					
					//-- Build step families ----------------------------------------------------------------
					foreach($this->indi->getStepFamilies() as $famid=>$family) {
						$label = $this->indi->getStepFamilyLabel($family);
						$people = $this->buildFamilyList($family, "step");
						if ($people){
							echo "<tr><td><br /></td><td></td></tr>";
						}
						$marrdate = $family->getMarriageDate();
						
						// Husband -----------------------------
						$styleadd = "";
						$elderdate = "";
						if (isset($people["husb"])) {
							$married   = GedcomDate::Compare($censdate, $marrdate);
							$nam   = $people["husb"]->getAllNames();
							$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
							$givn  = rtrim($nam[0]['givn'],'*');
							$surn  = $nam[0]['surname'];
							if (isset($nam[1])) {
								$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
								$marn  = $nam[1]['surname'];
							}
							
							$menu = new Menu();
							if ($people["husb"]->getLabel() == ".") {
								$menu->addLabel("&nbsp;" . $pgv_lang["stepdad"] . "&nbsp;". "\n");
							}else{
								$menu->addLabel("&nbsp;" . $people["husb"]->getLabel() . "&nbsp;". "\n");
							}
//							$menu = new Menu("&nbsp;" . $people["husb"]->getLabel() . "&nbsp;". "\n");
							$menu->addClass("", "", "submenu");
							$slabel  = print_pedigree_person_nav2($people["husb"]->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
							$slabel .= $parentlinks;
							$submenu = new Menu($slabel);
							$menu->addSubMenu($submenu);
							if (PrintReady($people["husb"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["husb"]->getDeathYear()); }
							if (PrintReady($people["husb"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["husb"]->getBirthYear()); }
							?>
							<tr>
								<td align="left" class="optionbox">
									<font size=1>
									<?php 
									$menu->printMenu(); 
									?>
									</font>
								</td>
								<td align="left" class="facts_value" style="background-color:#bb9900;">
									<font size=1>
										<?php 
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$people["husb"]->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($people["husb"]->canDisplayDetails()) ) {
									?>
									<a href='javaScript:insertRowToTable("<?php
											print PrintReady($people["husb"]->getXref()) ;					 // pid = PID
										?>", "<?php 
											print PrintReady($fulln);										 // nam = Name
										?>", "<?php
										if ($people["husb"]->getLabel() == ".") {
											print PrintReady($pgv_lang["stepdad"]);							 // label = Relationship
										}else{
											print PrintReady($people["husb"]->getLabel());					 // label = Relationship
										}
										?>", "<?php
											print PrintReady($people["husb"]->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>=0){
												echo "M";													 // cond = Condition (Married)
											}else{
												echo "S";													 // cond = Condition (Single)
											}
										?>", "<?php
											print PrintReady($people["husb"]->getbirthyear());				 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$people["husb"]->getbirthyear());			 //  age = Census Date minus YOB
										?>", "<?php
											print "Y";														 // YMD
										?>", "<?php
											print "";														 // occu = Occupation
										?>", "<?php
											print PrintReady($people["husb"]->getcensbirthplace());			 //  birthpl = Census Place of Birth 
										?>");'>
										<?php print PrintReady($people["husb"]->getFullName());				 // Name 
										?> 
									</a> 
									<?php print "\n" ;
									}else{
										print $pgv_lang["private"];
									}
									?>
									</font>
								</td>
							</tr>
							<?php
							$elderdate = $people["husb"]->getBirthDate(false);
						}
						
						// Wife -------------------
						$styleadd = "";
						if (isset($people["wife"])) {
							$married   = GedcomDate::Compare($censdate, $marrdate);
							$nam   = $people["wife"]->getAllNames();
							$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
							$givn  = rtrim($nam[0]['givn'],'*');
							$surn  = $nam[0]['surname'];
							if (isset($nam[1])) {
								$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
								$marn  = $nam[1]['surname'];
							}else{
								$fulmn = $fulln;
								$marn  = $surn;
							}
							
							$menu = new Menu();
							if ($people["wife"]->getLabel() == ".") {
								$menu->addLabel("&nbsp;" . $pgv_lang["stepmom"] . "&nbsp;". "\n");
							}else{
								$menu->addLabel("&nbsp;" . $people["wife"]->getLabel() . "&nbsp;". "\n");
							}
//							$menu = new Menu("&nbsp;" . $people["wife"]->getLabel() . "&nbsp;". "\n");
							$menu->addClass("", "", "submenu");
							$slabel  = print_pedigree_person_nav2($people["wife"]->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
							$slabel .= $parentlinks;
							$submenu = new Menu($slabel);
							$menu->addSubMenu($submenu);
							if (PrintReady($people["wife"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["wife"]->getDeathYear()); }
							if (PrintReady($people["wife"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["wife"]->getBirthYear()); }
							?>
							<tr>
								<td align="left" class="optionbox">
									<font size=1>
									<?php 
									$menu->printMenu(); 
									?>
									</font>
								</td>
								<td align="left" class="facts_value" style="background-color:#bb9900;">
									<font size=1>
										<?php 
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$people["wife"]->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($people["wife"]->canDisplayDetails()) ) {
									?>
									<a href='javaScript:insertRowToTable("<?php
											print PrintReady($people["wife"]->getXref()) ;					 // pid = PID
										?>", "<?php 
											if ($married>=0 && isset($nam[1])){
												echo PrintReady($fulmn);									 // nam = Married Name
											}else{
												echo PrintReady($fulln);									 // nam = Married Name
											}
										?>", "<?php
										if ($people["wife"]->getLabel() == ".") {
											print PrintReady($pgv_lang["stepmom"]);							 // label = Relationship
										}else{
											print PrintReady($people["wife"]->getLabel());					 // label = Relationship
										}
										?>", "<?php
											print PrintReady($people["wife"]->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>=0 && isset($nam[1])){
												echo "M";													 // cond = Condition (Married)
											}else{
												echo "S";													 // cond = Condition (Single)
											}
											?>", "<?php
											print PrintReady($people["wife"]->getbirthyear());				 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$people["wife"]->getbirthyear());	 //  age = Census Date minus YOB
										?>", "<?php
											print "Y";														 // YMD
										?>", "<?php
											print "";														 // occu = Occupation
										?>", "<?php
											print PrintReady($people["wife"]->getcensbirthplace());			 //  birthpl = Census Place of Birth 
										?>");'>
										<?php 
										if ($married>=0 && isset($nam[1])){
											print PrintReady($fulmn);			 							 // Full Married Name
										} else {
											print PrintReady($fulln);			 							 // Full Name
										}
										?>
									</a> 
									<?php print "\n" ;
									}else{
										print $pgv_lang["private"];
									}
									?>
									</font>
								</td>
							</tr>
							<?php
						}

						// Children ---------------------
						$styleadd = "";
						if (isset($people["children"])) {
							$elderdate = $family->getMarriageDate();
							foreach($people["children"] as $key=>$child) {
								$nam   = $child->getAllNames();
								$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
								$givn  = rtrim($nam[0]['givn'],'*');
								$surn  = $nam[0]['surname'];
								if (isset($nam[1])) {
									$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
									$marn  = $nam[1]['surname'];
								}
								$menu = new Menu("&nbsp;" . $child->getLabel() . "&nbsp;". "\n");
								$menu->addClass("", "", "submenu");
								$slabel  = print_pedigree_person_nav2($child->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
								$slabel .= $spouselinks;
								$submenu = new Menu($slabel);
								$menu->addSubMenu($submenu);
								if (PrintReady($child->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($child->getDeathYear()); }
								if (PrintReady($child->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($child->getBirthYear()); }
								?>
								<tr>
									<td align="left" class="optionbox">
										<font size=1>
										<?php 
										$menu->printMenu(); 
										?>
										</font>
									</td>
									<td align="left" class="facts_value" style="background-color:#bb9900;">
										<font size=1>
											<?php
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$child->getXref()."&gedcom={$GEDCOM}")."\">";
											print "H";
											print "</a>";
											?>
										</font>
									</td>
									<td align="left" class="facts_value" nowrap="nowrap">
										<font size=1>
										<?php
										if ( ($child->canDisplayDetails()) ) {
										?>
										<a href='javaScript:insertRowToTable("<?php
												print PrintReady($child->getXref()) ;				 // pid = PID
											?>", "<?php 
												print PrintReady($fulln);							 // nam = Name
											?>", "<?php
												print PrintReady($child->getLabel());				 // label = Relationship
											?>", "<?php
												print PrintReady($child->getSex());					 // gend = Gender
											?>", "<?php
												print "";											 // cond = Condition (Married or Single)
											?>", "<?php
												print PrintReady($child->getbirthyear());			 // yob = Year of Birth
											?>", "<?php
												print PrintReady($censyear-$child->getbirthyear());	 //  age = Census Date minus YOB
											?>", "<?php
												print "Y";											 // YMD
											?>", "<?php
												print "";											 // occu = Occupation
											?>", "<?php
												print PrintReady($child->getcensbirthplace());		 //  birthpl = Census Place of Birth 
											?>");'>
												<?php print PrintReady($child->getFullName());		 // Name 
											?> 
										</a> 
										<?php print "\n" ;
										}else{
											print $pgv_lang["private"];
										}
										?>
										</font>
									</td>
								</tr>
								<?php
								//$elderdate = $child->getBirthDate(false);
							}
						}
					}
					
					print "<tr><td><font size=1><br /></font></td></tr>";
					
					//-- Build Spouse Family ---------------------------------------------------
					$families = $this->indi->getSpouseFamilies();
					//$personcount = 0;
					foreach($families as $famid=>$family) {
						$people = $this->buildFamilyList($family, "spouse");
						if ($this->indi->equals($people["husb"])) {
							$spousetag = 'WIFE';
						}else{
							$spousetag = 'HUSB';
						}
						$marrdate = $family->getMarriageDate();
						
						// Husband -------------------
						if ( isset($people["husb"])) {
							$married   = GedcomDate::Compare($censdate, $marrdate);
							
							// $bdate=$people["husb"]->getBirthDate();
							// $ddate=$people["husb"]->getDeathDate();
							// $bage = GedcomDate::GetAgeGedcom($bdate);
							// $dage = GedcomDate::GetAgeGedcom($ddate);
							// $mage = GedcomDate::GetAgeGedcom($marrdate);
							// $cendate = new GedcomDate("03 JAN 1901");
							// echo "<br /><b>Birth = </b>" .$people["husb"]->getBirthDate()->Display(false). "<br /><br />";
							// echo "<font size=1><br /><b>Marriage = </b>".$marrdate->Display(false)."</font>";
							//echo "<br /><b>Event = </b>" .$date->Display(false). "<br /><br />";
							// echo "<font size=1><br /><b>Census = </b>".$censdate->Display(false)."</font>";
							// echo "<br />";
							//$age2 = get_age_at_event($dage, true);
							//echo $bage."<br />";
							//echo $dage."<br />";
							//echo $mage."<br />";
							// echo $cendate."<br />";
							
							$nam   = $people["husb"]->getAllNames();
							$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
							$givn  = rtrim($nam[0]['givn'],'*');
							$surn  = $nam[0]['surname'];
							if (isset($nam[1])) {
								$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
								$marn  = $nam[1]['surname'];
							}
							$menu = new Menu("&nbsp;" . $people["husb"]->getLabel() . "&nbsp;". "\n");
							$menu->addClass("", "", "submenu");
							$slabel  = print_pedigree_person_nav2($people["husb"]->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
							$slabel .= $parentlinks;
							$submenu = new Menu($slabel);
							$menu->addSubMenu($submenu);
							if (PrintReady($people["husb"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["husb"]->getDeathYear()); }
							if (PrintReady($people["husb"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["husb"]->getBirthYear()); }
							?>
							<tr class="fact_value">
								<td align="left" nowrap="nowrap" class="optionbox<?php print $styleadd; ?>">
									<font size=1>
										<?php
										if ($people["husb"]->getXref()==$pid) {
											print "&nbsp" .($people["husb"]->getLabel());
										}else{
											$menu->printMenu();
										}
										?>
									</font>
								</td>
								<td align="left" class="facts_value" style="background-color:#bb9900;">
									<font size=1>
										<?php
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$people["husb"]->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($people["husb"]->canDisplayDetails()) ) {
									?>
									<a href='javaScript:insertRowToTable("<?php
											print $people["husb"]->getXref() ;								 // pid = PID
										?>", "<?php 
											print PrintReady($fulln);										 // nam = Name
										?>", "<?php
											if ($people["husb"]->getXref()==$pid) {
												print "Head";												 // label = Relationship
											}else{
												print $people["husb"]->getLabel();							 // label = Relationship
											}
										?>", "<?php
											print PrintReady($people["husb"]->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>=0){
												echo "M";													 // cond = Condition (Married)
											}else{
												echo "S";													 // cond = Condition (Single)
											}
										?>", "<?php
											print PrintReady($people["husb"]->getbirthyear());				 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$people["husb"]->getbirthyear());	 //  age = Census Date minus YOB
										?>", "<?php
											print "Y";														 // YMD
										?>", "<?php
											print "";														 // occu = Occupation
										?>", "<?php
											print PrintReady($people["husb"]->getcensbirthplace());			 //  birthpl = Census Place of Birth 
										?>");'>
										<?php 
											print PrintReady($people["husb"]->getFullName());				 // Name 
										?> 
									</a> 
									<?php print "\n" ;
									}else{
										print $pgv_lang["private"];
										}
										?>
									</font>
								</td>
							<tr>
							<?php
						} 
							
							
						// Wife -------------------
						//if ( isset($people["wife"]) && $spousetag == 'WIFE') {
						if (isset($people["wife"])) {
							$married = GedcomDate::Compare($censdate, $marrdate);
							$nam   = $people["wife"]->getAllNames();
							$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
							$givn  = rtrim($nam[0]['givn'],'*');
							$surn  = $nam[0]['surname'];
							if (isset($nam[1])) {
								$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
								$marn  = $nam[1]['surname'];
							}else{
								$fulmn = $fulln;
								$marn  = $surn;
							}
							$menu = new Menu("&nbsp;" . $people["wife"]->getLabel() . "&nbsp;". "\n");
							$menu->addClass("", "", "submenu");
							$slabel  = print_pedigree_person_nav2($people["wife"]->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $currpid, $censyear);
							$slabel .= $parentlinks;
							$submenu = new Menu($slabel);
							$menu->addSubMenu($submenu);
							if (PrintReady($people["wife"]->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($people["wife"]->getDeathYear()); }
							if (PrintReady($people["wife"]->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($people["wife"]->getBirthYear()); }
							?>
							<tr>
								<td align="left" nowrap="nowrap" class="optionbox<?php print $styleadd; ?>">
									<font size=1>
										<?php
										if ($people["wife"]->getXref()==$pid) {
											print "&nbsp" .($people["wife"]->getLabel());
										}else{
											$menu->printMenu();
										}
										?>
									</font>
								</td>
								<td align="left" class="facts_value" style="background-color:#bb9900;">
									<font size=1>
										<?php 
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$people["wife"]->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($people["wife"]->canDisplayDetails()) ) {
									?>
										<a href='javaScript:insertRowToTable("<?php 
												print $people["wife"]->getXref() ;							 // pid = PID
										?>", "<?php 
											if ($married>=0 && isset($nam[1])){
												echo PrintReady($fulmn);									 // nam = Full Married Name
											}else{
												echo PrintReady($fulln);									 // nam = Full Name
											}
										?>", "<?php
											if ($people["wife"]->getXref()==$pid) {
												print "Head";												 // label = Head
											}else{
												print PrintReady($people["wife"]->getLabel());				 // label = Relationship
											}
										?>", "<?php
											print PrintReady($people["wife"]->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>=0 && isset($nam[1])){
												echo "M";													 // cond = Condition (Married)
											}else{
												echo "S";													 // cond = Condition (Single)
											}
											?>", "<?php
											print PrintReady($people["wife"]->getbirthyear());				 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$people["wife"]->getbirthyear());	 //  age = Census Date minus YOB
										?>", "<?php
											print "Y"; 														 // YMD
										?>", "<?php
											print "";														 // occu = Occupation
										?>", "<?php
											print PrintReady($people["wife"]->getcensbirthplace());			 //  birthpl = Census Place of Birth 
										?>");'>
											<?php 
											if ($married>=0 && isset($nam[1])){
												print PrintReady($fulmn);			 						 // Full Married Name
											} else {
												print PrintReady($fulln);			 						 // Full Name
											}
											?>
										</a>
										<?php print "\n" ;
									}else{
										print $pgv_lang["private"];
									}
									?>
									</font>
								</td>
							<tr> <?php
						}
							
						// Children
						foreach($people["children"] as $key=>$child) {
								// Get child's marriage status
								$married="";
								foreach ($child->getSpouseFamilies() as $childfamily) {
									$tmp=$childfamily->getMarriageDate();
									$married = GedcomDate::Compare($censdate, $tmp);
								}
								$nam   = $child->getAllNames();
								$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
								$givn  = rtrim($nam[0]['givn'],'*');
								$surn  = $nam[0]['surname'];
								if (isset($nam[1])) {
									$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
									$marn  = $nam[1]['surname'];
								}else{
									$fulmn = $fulln;
									$marn  = $surn;
								}
								$menu = new Menu("&nbsp;" . $child->getLabel() . "&nbsp;". "\n");
								$menu->addClass("", "", "submenu");
								$slabel = print_pedigree_person_nav2($child->getXref(), 2, !$this->isPrintPreview(), 0, $personcount++, $child->getLabel(), $censyear);
								$slabel .= $spouselinks;
								$submenu = new Menu($slabel);
								$menu->addSubmenu($submenu);
								?>
							<tr>
								<td align="left" class="optionbox">
									<font size=1>
									<?php 
										//print $child->getLabel();
										$menu->printMenu();
									?>
									</font>
								</td>
								<td align="left" class="facts_value" style="background-color:#bb9900;">
									<font size=1>
										<?php
										print "<a href=\"".encode_url("edit_interface.php?action=addnewnote_assisted&noteid=newnote&pid=".$child->getXref()."&gedcom={$GEDCOM}")."\">";
										print "H";
										print "</a>";
										?>
									</font>
								</td>
								<td align="left" class="facts_value" nowrap="nowrap">
									<font size=1>
									<?php
									if ( ($child->canDisplayDetails()) ) {
									?>
									<a href='javaScript:insertRowToTable("<?php 
											print $child->getXref() ;							 // pid = PID
										?>", "<?php 
											if ($married>0 && isset($nam[1])){
												echo PrintReady($fulmn);						 // nam = Full Married Name
											}else{
												echo PrintReady($fulln);						 // nam = Full Name
											}
											?>", "<?php
											print PrintReady($child->getLabel());				 // label = Relationship
										?>", "<?php
											print PrintReady($child->getSex());					 // gend = Gender
										?>", "<?php
											if ($married>0) {
												echo "M";										 // cond = Condition (Married)
											} else if ($married<0 || ($married=="0") ) {
												echo "S";										 // cond = Condition (Single)
											} else {
												echo "";										 // cond = Condition (Not Known)
											}
										?>", "<?php
											print PrintReady($child->getbirthyear());			 // yob = Year of Birth
										?>", "<?php
											print PrintReady($censyear-$child->getbirthyear());	 //  age = Census Date minus YOB
										?>", "<?php
											print "Y";											 // YMD
										?>", "<?php
											print "";											 // occu = Occupation
										?>", "<?php
											print PrintReady($child->getcensbirthplace());		 //  birthpl = Census Place of Birth 
										?>");'>
											<?php 
											if ($married>=0 && isset($nam[1])){
												print PrintReady($fulmn);			 			 // Full Married Name
											} else {
												print PrintReady($fulln);			 			 // Full Name
											}
											?>
									</a>
									<?php print "\n" ;
								}else{
									print $pgv_lang["private"];
								}
								?>
									</font>
								</td>
							</tr>
							<?php
						} 
						
					print "<tr><td><font size=1><br /></font></td></tr>";
					}
					?>
						
						</table>
					</td>
				</tr>
			</table>
			
			
<?php
// ==================================================================
require_once 'includes/functions/functions_charts.php';
/**
 * print the information for an individual chart box
 *
 * find and print a given individuals information for a pedigree chart
 * @param string $pid	the Gedcom Xref ID of the   to print
 * @param int $style	the style to print the box in, 1 for smaller boxes, 2 for larger boxes
 * @param boolean $show_famlink	set to true to show the icons for the popup links and the zoomboxes
 * @param int $count	on some charts it is important to keep a count of how many boxes were printed
 */

function print_pedigree_person_nav2($pid, $style=1, $show_famlink=true, $count=0, $personcount="1", $currpid, $censyear) {
	global $HIDE_LIVE_PEOPLE, $SHOW_LIVING_NAMES, $PRIV_PUBLIC, $factarray, $ZOOM_BOXES, $LINK_ICONS, $view, $SCRIPT_NAME, $GEDCOM;
	global $pgv_lang, $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $bwidth, $bheight, $PEDIGREE_FULL_DETAILS, $SHOW_ID_NUMBERS, $SHOW_PEDIGREE_PLACES;
	global $CONTACT_EMAIL, $CONTACT_METHOD, $TEXT_DIRECTION, $DEFAULT_PEDIGREE_GENERATIONS, $OLD_PGENS, $talloffset, $PEDIGREE_LAYOUT, $MEDIA_DIRECTORY;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $ABBREVIATE_CHART_LABELS, $USE_MEDIA_VIEWER;
	global $chart_style, $box_width, $generations, $show_spouse, $show_full;
	global $CHART_BOX_TAGS, $SHOW_LDS_AT_GLANCE, $PEDIGREE_SHOW_GENDER;
	global $SEARCH_SPIDER;
	
	global $spouselinks, $parentlinks, $step_parentlinks, $persons, $person_step, $person_parent, $tabno, $theme_name, $spousetag;
	global $natdad, $natmom, $censyear, $censdate;

	if ($style != 2) $style=1;
	if (empty($show_full)) $show_full = 0;
	if (empty($PEDIGREE_FULL_DETAILS)) $PEDIGREE_FULL_DETAILS = 0;

	if (!isset($OLD_PGENS)) $OLD_PGENS = $DEFAULT_PEDIGREE_GENERATIONS;
	if (!isset($talloffset)) $talloffset = $PEDIGREE_LAYOUT;

	$person=Person::getInstance($pid);
	if ($pid==false || empty($person)) {
		$spouselinks 		= false;
		$parentlinks 		= false;
		$step_parentlinks	= false;
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
				//-- draw a box for the family popup
				if ($TEXT_DIRECTION=="rtl") {
				$spouselinks .= "\n\t\t\t<table class=\"person_box$isF\" style=\" position: absolute; top: -19px; left: -1px; \"><tr><td align=\"right\" style=\"font-size:10px;font-weight:normal;\" class=\"name2\" nowrap=\"nowrap\">";
				$spouselinks .= "<font size=\"1\"><b>" . $pgv_lang['family'] . "</b><br /></font>";
				$parentlinks .= "\n\t\t\t<table class=\"person_box$isF\" style=\" position: absolute; top: -19px; left: -1px; \"><tr><td align=\"right\" style=\"font-size:10px;font-weight:normal;\" class=\"name2\" nowrap=\"nowrap\">";
				$parentlinks .= "<font size=\"1\"><b>" . $pgv_lang['parents'] . "</b><br /></font>";
				$step_parentlinks .= "\n\t\t\t<table class=\"person_box$isF\" style=\" position: absolute; top: -19px; left: -1px; \"><tr><td align=\"right\" style=\"font-size:10px;font-weight:normal;\" class=\"name2\" nowrap=\"nowrap\">";
				$step_parentlinks .= "<font size=\"1\"><b>" . $pgv_lang['parents'] . "</b><br /></font>";
				}else{
				$spouselinks .= "\n\t\t\t<table class=\"person_box$isF\" style=\" position: absolute; top: -19px; right: -1px; \"><tr><td align=\"left\" style=\"font-size:10px;font-weight:normal;\" class=\"name2\" nowrap=\"nowrap\">";
				$spouselinks .= "<font size=\"1\"><b>" . $pgv_lang['family'] . "</b><br /></font>";
				$parentlinks .= "\n\t\t\t<table class=\"person_box$isF\" style=\" position: absolute; top: -19px; right: -1px; \"><tr><td align=\"left\" style=\"font-size:10px;font-weight:normal;\" class=\"name2\" nowrap=\"nowrap\">";
				$parentlinks .= "<font size=\"1\"><b>" . $pgv_lang['parents'] . "</b><br /></font>";
				$step_parentlinks .= "\n\t\t\t<table class=\"person_box$isF\" style=\" position: absolute; top: -19px; right: -1px; \"><tr><td align=\"left\" style=\"font-size:10px;font-weight:normal;\" class=\"name2\" nowrap=\"nowrap\">";
				$step_parentlinks .= "<font size=\"1\"><b>" . $pgv_lang['parents'] . "</b><br /></font>";
				}
				$persons       = "";
				$person_parent = "";
				$person_step   = "";
				
				//-- parent families --------------------------------------
				$fams = $person->getChildFamilies();
				foreach($fams as $famid=>$family) {
					
					if (!is_null($family)) {
						$husb = $family->getHusband($person);
						$wife = $family->getWife($person);
						// $spouse = $family->getSpouse($person);
						$children = $family->getChildren();
						$num = count($children);
						$marrdate = $family->getMarriageDate();
						
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
								$tmp=$husb->getXref();
								if ($husb->canDisplayName()) {
									$nam   = $husb->getAllNames();
									//$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surn'];
									$fulln = $husb->getFullName();
									$givn  = rtrim($nam[0]['givn'],'*');
									$surn  = $nam[0]['surn'];
									if (isset($nam[1]) ) {
										$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surn'];
										$marn  = $nam[1]['surn'];
									}
									
									$parentlinks .= "<a href=\"javascript:insertRowToTable(";
									$parentlinks .= "'".PrintReady($husb->getXref())."',";					// pid		=	PID
									$parentlinks .= "'".PrintReady($fulln)."',";							// nam	=	Name
									if ($currpid=="Wife" || $currpid=="Husband") {
										$parentlinks .= "'Father in Law',";									// label	=	1st Gen Male Relationship
									}else{
										$parentlinks .= "'Grand-Father',";									// label	=	2st Gen Male Relationship
									}
									$parentlinks .= "'".PrintReady($husb->getSex())."',";					// sex	=	Gender
									$parentlinks .= "''".",";												// cond	=	Condition (Married etc)
									$parentlinks .= "'".PrintReady($husb->getbirthyear())."',";				// yob	=	Year of Birth
									if ($husb->getbirthyear()>=1) {
										$parentlinks .=	"'".PrintReady($censyear-$husb->getbirthyear())."',";	// age	= 	Census Year - Year of Birth
									}else{
										$parentlinks .= "''".",";											// age	= 	Undefined
									}
									$parentlinks .= "'Y'".",";												// Y/M/D	=	Age in Years/Months/Days
									$parentlinks .= "''".",";												// occu 	=	Occupation
									$parentlinks .= "'".PrintReady($husb->getcensbirthplace())."'";			// birthpl	=	Birthplace
									$parentlinks .= ");\">";
									$parentlinks .= PrintReady($husb->getFullName());
									$parentlinks .= "</a>";
								}else{
									$parentlinks .= $pgv_lang["private"];
								}
								$parentlinks .= "<br />\n";
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
								$tmp=$wife->getXref();
								if ($wife->canDisplayName()) {
									$married = GedcomDate::Compare($censdate, $marrdate);
									$nam   = $wife->getAllNames();
									$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
									$givn  = rtrim($nam[0]['givn'],'*');
									$surn  = $nam[0]['surname'];
									if (isset($nam[1])) {
										//$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
										$fulmn = $nam[1]['fullNN'];
										$marn  = $nam[1]['surname'];
									}
									$parentlinks .= "<a href=\"javascript:insertRowToTable(";
									$parentlinks .=	"'".PrintReady($wife->getXref())."',";						// pid		=	PID
									// $parentlinks .=	"'".PrintReady($fulln)."',";							// nam	=	Name
									
									if ($married>=0 && isset($nam[1])){
										$parentlinks .= "'".PrintReady($fulmn)."',";							// nam		=	Full Married Name
									} else {
										$parentlinks .= "'".PrintReady($fulln)."',";	 						// nam		=	Full Name
									}
									
									if ($currpid=="Wife" || $currpid=="Husband") {
										$parentlinks .= "'Mother in Law',";										// label	=	1st Gen Female Relationship
									}else{
										$parentlinks .= "'Grand-Mother',";										// label	=	2st Gen Female Relationship
									}
									$parentlinks .=	"'".PrintReady($wife->getSex())."',";						// sex		=	Gender
									$parentlinks .=	"''".",";													// cond		=	Condition (Married etc)
									$parentlinks .=	"'".PrintReady($wife->getbirthyear())."',";					// yob		=	Year of Birth
									if ($wife->getbirthyear()>=1) {
										$parentlinks .=	"'".PrintReady($censyear-$wife->getbirthyear())."',";	// age		= 	Census Year - Year of Birth
									}else{
										$parentlinks .=	"''".",";												// age		= 	Undefined
									}
									$parentlinks .=	"'Y'".",";													// Y/M/D	=	Age in Years/Months/Days
									$parentlinks .=	"''".",";													// occu 	=	Occupation
									$parentlinks .=	"'".PrintReady($wife->getcensbirthplace())."'";				// birthpl	=	Birthplace
									$parentlinks .=	");\">";
									if ($married>=0 && isset($nam[1])){
										$parentlinks .= $fulmn;			 										// Full Married Name
									} else {
										$parentlinks .= $fulln;							 						// Full Name
									}
									$parentlinks .= "</a>";
								}else{
									$parentlinks .= $pgv_lang["private"];
								}
								$parentlinks .= "<br />\n";
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
						$marrdate = $family->getMarriageDate();
						
						if ($natdad == "yes") {
						}else{
							// Husband -----------------------
							if ( ($husb || $num>0) && $husb->getLabel() != "." ) {
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
									$tmp=$husb->getXref();
									if ($husb->canDisplayName()) {
										$nam   = $husb->getAllNames();
										// $fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
										$fulln = $husb->getFullName();
										$givn  = rtrim($nam[0]['givn'],'*');
										$surn  = $nam[0]['surname'];
										if (isset($nam[1])) {
											$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
											$marn  = $nam[1]['surname'];
										}
									
										$parentlinks .= "<a href=\"".encode_url("individual.php?pid={$tmp}&amp;tab={$tabno}&amp;gedcom={$GEDCOM}")."\">";
										$parentlinks .= PrintReady($fulln);
										$parentlinks .= "</a>";
									}else{
										$parentlinks .= $pgv_lang["private"];
									}
									$parentlinks .= "<br />\n";
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
									$tmp=$wife->getXref();
									if ($wife->canDisplayName()) {
										$married = GedcomDate::Compare($censdate, $marrdate);
										$nam   = $wife->getAllNames();
										$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
										$givn  = rtrim($nam[0]['givn'],'*');
										$surn  = $nam[0]['surname'];
										if (isset($nam[1])) {
											$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
											$marn  = $nam[1]['surname'];
										}
									
										$parentlinks .= "<a href=\"".encode_url("individual.php?pid={$tmp}&amp;tab={$tabno}&amp;gedcom={$GEDCOM}")."\">";
										$parentlinks .= PrintReady($fulln);
										$parentlinks .= "</a>";
									}else{
										$parentlinks .= $pgv_lang["private"];
									}
									$parentlinks .= "<br />\n";
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
						$marrdate = $family->getMarriageDate();
						
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
								$tmp=$spouse->getXref();
								if ($spouse->canDisplayName()) {
									$married = GedcomDate::Compare($censdate, $marrdate);
									$nam   = $spouse->getAllNames();
									$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
									$givn  = rtrim($nam[0]['givn'],'*');
									$surn  = $nam[0]['surname'];
									if (isset($nam[1])) {
										$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
										$marn  = $nam[1]['surname'];
									}
									
									$spouselinks .= "<a href=\"javascript:insertRowToTable(";
									$spouselinks .=	"'".PrintReady($spouse->getXref())."',";					// pid		=	PID
									//$spouselinks .=	"'".PrintReady($fulln)."',";							// nam	=	Name
									if ($married>=0 && isset($nam[1])){
										$spouselinks .= "'".PrintReady($fulmn)."',";							// Full Married Name
									} else {
										$spouselinks .= "'".PrintReady($fulln)."',";	 						// Full Name
									}
									if ($currpid=="Son" || $currpid=="Daughter") {
										if ($spouse->getSex()=="M") {
											$spouselinks .=	"'Son in Law',";									// label	=	Male Relationship
										}else{
											$spouselinks .=	"'Daughter in Law',";								// label	=	Female Relationship
										}
									}else{
										if ($spouse->getSex()=="M") {
											$spouselinks .=	"'Brother in Law',";								// label	=	Male Relationship
										}else{
											$spouselinks .=	"'Sister in Law',";									// label	=	Female Relationship
										}
									}
										$spouselinks .=	"'".PrintReady($spouse->getSex())."',";					// sex	=	Gender
										$spouselinks .=	"''".",";												// cond	=	Condition (Married etc)
										$spouselinks .=	"'".PrintReady($spouse->getbirthyear())."',";			// yob	=	Year of Birth
										if ($spouse->getbirthyear()>=1) {
											$spouselinks .=	"'".PrintReady($censyear-$spouse->getbirthyear())."',";	// age	= 	Census Year - Year of Birth
										}else{
											$spouselinks .=	"''".",";											// age	= 	Undefined
										}
										$spouselinks .=	"'Y'".",";												// Y/M/D	=	Age in Years/Months/Days
										$spouselinks .=	"''".",";												// occu 	=	Occupation
										$spouselinks .=	"'".PrintReady($spouse->getcensbirthplace())."'";		// birthpl	=	Birthplace
										$spouselinks .=	");\">";
										// $spouselinks .= PrintReady($fulln);
										if ($married>=0 && isset($nam[1])){
											$spouselinks .= "'".PrintReady($fulmn)."',";							// Full Married Name
										} else {
											$spouselinks .= "'".PrintReady($fulln)."',";	 						// Full Name
										}
										$spouselinks .= "</a>";
								}else{
									$spouselinks .= $pgv_lang["private"];
								}
								$spouselinks .= "</a><br />\n";
								if ($spouse->getFullName() != "") {
									$persons = "Yes";
								}
							}
						}
						
						// Children ------------------------------   @var $child Person 
						foreach($children as $c=>$child) {
							$cpid = $child->getXref();
							if ($child) {
								$persons="Yes";
								if ($TEXT_DIRECTION=="ltr") {
									$title = $pgv_lang["indi_info"].": ".$cpid;
									$spouselinks .= "\n\t\t\t\to&nbsp;&nbsp;";
									if ($child->canDisplayName()) {
										$nam   = $child->getAllNames();
										$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
										$givn  = rtrim($nam[0]['givn'],'*');
										$surn  = $nam[0]['surname'];
										if (isset($nam[1])) {
											$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
											$marn  = $nam[1]['surname'];
										}
									
										$spouselinks .= "<a href=\"javascript:insertRowToTable(";
										$spouselinks .=	"'".PrintReady($child->getXref())."',";					// pid		=	PID
										$spouselinks .=	"'".PrintReady($fulln)."',";							// nam		=	Name
									if ($currpid=="Son" || $currpid=="Daughter") {
										if ($child->getSex()=="M") {
											$spouselinks .=	"'Grand-Son',";										// label	=	Male Relationship
										}else{
											$spouselinks .=	"'Grand-Daughter',";								// label	=	Female Relationship
										}
									}else{
										if ($child->getSex()=="M") {
											$spouselinks .=	"'Nephew',";										// label	=	Male Relationship
										}else{
											$spouselinks .=	"'Niece',";											// label	=	Female Relationship
										}
									}
									$spouselinks .=	"'".PrintReady($child->getSex())."',";						// sex		=	Gender
									$spouselinks .=	"''".",";													// cond		=	Condition (Married etc)
									$spouselinks .=	"'".PrintReady($child->getbirthyear())."',";				// yob		=	Year of Birth
									if ($child->getbirthyear()>=1) {
										$spouselinks .=	"'".PrintReady($censyear-$child->getbirthyear())."',";	// age		= 	Census Year - Year of Birth
									}else{
										$spouselinks .=	"''".",";												// age		= 	Undefined
									}
									$spouselinks .=	"'Y'".",";													// Y/M/D	=	Age in Years/Months/Days
									$spouselinks .=	"''".",";													// occu 	=	Occupation
									$spouselinks .=	"'".PrintReady($child->getcensbirthplace())."'";			// birthpl	=	Birthplace
									$spouselinks .=	");\">";
									$spouselinks .= PrintReady($fulln);
									$spouselinks .= "</a>";
									}else{ 
										$spouselinks .= $pgv_lang["private"];
									}
									$spouselinks .= "<br />";
								}else{
									$title = $cpid." :".$pgv_lang["indi_info"];
									if ($child->canDisplayName()) {
										$spouselinks .= "<a href=\"javascript:insertRowToTable(";
										$spouselinks .=	"'".PrintReady($child->getXref())."',";						// pid		=	PID
										$spouselinks .=	"'".PrintReady($fulln)."',";								// nam	=	Name
										if ($currpid=="Son" || $currpid=="Daughter") {
											if ($child->getSex()=="M") {
												$spouselinks .=	"'Grand-Son',";										// label	=	Male Relationship
											}else{
												$spouselinks .=	"'Grand-Daughter',";											// label	=	Female Relationship
											}
										}else{
											if ($child->getSex()=="M") {
												$spouselinks .=	"'Nephew',";										// label	=	Male Relationship
											}else{
												$spouselinks .=	"'Niece',";											// label	=	Female Relationship
											}
										}
										$spouselinks .=	"'".PrintReady($child->getSex())."',";						// sex	=	Gender
										$spouselinks .=	"''".",";													// cond	=	Condition (Married etc)
										$spouselinks .=	"'".PrintReady($child->getbirthyear())."',";				// yob	=	Year of Birth
										if ($child->getbirthyear()>=1) {
											$spouselinks .=	"'".PrintReady($censyear-$child->getbirthyear())."',";	// age	= 	Census Year - Year of Birth
										}else{
											$spouselinks .=	"''".",";												// age	= 	Undefined
										}
										$spouselinks .=	"'Y'".",";													// Y/M/D	=	Age in Years/Months/Days
										$spouselinks .=	"''".",";													// occu 	=	Occupation
										$spouselinks .=	"'".PrintReady($child->getcensbirthplace())."'";			// birthpl	=	Birthplace
										$spouselinks .=	");\">";
										$spouselinks .= PrintReady($fulln);
										$spouselinks .= "</a>";
										$spouselinks .= "&nbsp;&nbsp;o";
									}else{ 
										$spouselinks .= "o&nbsp;&nbsp;";
										$spouselinks .= $pgv_lang["private"];
									}
									$spouselinks .= "<br />";
								}
							}
						}
					}
				}
				?>
				
				<?php if ($theme_name=="Xenea" || $theme_name=="Standard" || $theme_name=="Wood" || $theme_name=="Ocean") { ?>
				<style type="text/css" rel="stylesheet">
					a:hover .name2 { color: #222222; }
				</style>
				<?php } ?>
				
				<?php
				if ($persons != "Yes") {
					$spouselinks  .= "(" . $pgv_lang['none'] . ")</td></tr></table>\n\t\t";
				}else{
					$spouselinks  .= "</td></tr></table>\n\t\t";
				}
				
				if ($person_parent != "Yes") {
					$parentlinks .= "(" . $pgv_lang['unknown'] . ")</td></tr></table>\n\t\t";
				}else{
					$parentlinks .= "</td></tr></table>\n\t\t";
				}
				
				if ($person_step != "Yes") {
					$step_parentlinks .= "(" . $pgv_lang['unknown'] . ")</td></tr></table>\n\t\t";
				}else{
					$step_parentlinks .= "</td></tr></table>\n\t\t";
				}
			}
		}
	}
}
// ==============================================================
?>
