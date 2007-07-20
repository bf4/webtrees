<?php
/**
 * Controller for the Hourglass Page
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007	John Finlay and Others
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

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once("config.php");
require_once("includes/controllers/basecontrol.php");
require_once("includes/person_class.php");
require_once("includes/functions_charts.php");

$indifacts = array();			 // -- array to store the fact records in for sorting and displaying
$globalfacts = array();
$otheritems = array();			  //-- notes, sources, media objects
$FACT_COUNT=0;
// -- array of GEDCOM elements that will be found but should not be displayed
$nonfacts[] = "FAMS";
$nonfacts[] = "FAMC";
$nonfacts[] = "MAY";
$nonfacts[] = "BLOB";
$nonfacts[] = "CHIL";
$nonfacts[] = "HUSB";
$nonfacts[] = "WIFE";
$nonfacts[] = "RFN";
$nonfacts[] = "";
$nonfamfacts[] = "UID";
$nonfamfacts[] = "";
/**
 * Main controller class for the individual page.
 */
class HourglassControllerRoot extends BaseController {
	var $show_changes = "yes";
	var $action = "";
	var $pid = "";

	var $uname = "";
	var $user = false;
	var $accept_success = false;
	var $visibility = "visible";
	var $position = "relative";
	var $display = "block";
	var $canedit = false;
	var $name_count = 0;
	var $total_names = 0;
	var $SEX_COUNT = 0;
	var $sexarray = array();
	var $show_full = 1;
	var $show_spouse = 0;
	var $generations;
	var $dgenerations;
	var $view;
	var $box_width;
	var $name;
//  the following are ajax variables  //
	var $ARID;
	var $arrwidth;
	var $arrheight;
///////////////////////////////////////

	/**
	 * constructor
	 */
	function HourglassControllerRoot() {
		parent::BaseController();
	}
	/**
	 * Initialization function
	 */
	function init() {
	global $USE_RIN, $MAX_ALIVE_AGE, $GEDCOM, $bheight, $bwidth, $bhalfheight, $GEDCOM_DEFAULT_TAB, $pgv_changes, $pgv_lang, $PEDIGREE_FULL_DETAILS, $MAX_DESCENDANCY_GENERATIONS;
	global $PGV_IMAGES, $PGV_IMAGE_DIR, $TEXT_DIRECTION;


	if (!empty($_REQUEST["action"])) $this->action = $_REQUEST["action"];
	if (!empty($_REQUEST["pid"])) $this->pid = strtoupper($_REQUEST["pid"]);

	//-- flip the arrows for RTL languages
	if ($TEXT_DIRECTION=="rtl") {
		$temp = $PGV_IMAGES['larrow']['other'];
		$PGV_IMAGES['larrow']['other'] = $PGV_IMAGES['rarrow']['other'];
		$PGV_IMAGES['rarrow']['other'] = $temp;
	}
	//-- get the width and height of the arrow images for adjusting spacing
	if (file_exists($PGV_IMAGE_DIR."/".$PGV_IMAGES['larrow']['other'])) {
		$temp = getimagesize($PGV_IMAGE_DIR."/".$PGV_IMAGES['larrow']['other']);
		$this->arrwidth = $temp[0];
		$this->arrheight = $temp[1];
	}
	
	//Checks query strings to see if they exist else assign a default value
	if (isset($_REQUEST["show_full"])) $this->show_full = $_REQUEST["show_full"];
	else $this->show_full = 1;
	if (isset($_REQUEST["show_spouse"])) $this->show_spouse=$_REQUEST["show_spouse"];
	else $this->show_spouse=0;
	if (isset($_REQUEST["generations"])) $this->generations=$_REQUEST["generations"];
	else $this->generations = 3;
	if ($this->generations > $MAX_DESCENDANCY_GENERATIONS) $this->generations = $MAX_DESCENDANCY_GENERATIONS;
	if (!isset($this->view)) $this->view="";
	
	// -- Sets the sizes of the boxes
	if (isset($_REQUEST["box_width"])) $this->box_width=$_REQUEST["box_width"];
	else $this->box_width=100;
	if (empty($this->box_width)) $this->box_width = "100";
	$this->box_width=max($this->box_width, 50);
	$this->box_width=min($this->box_width, 300);
	// If show details is unchecked it makes the boxes smaller
	if (!$this->show_full) $bwidth *= $this->box_width / 150;
	else $bwidth*=$this->box_width/100;

	if (!$this->show_full) $bheight = (int)($bheight / 2);
	$bhalfheight = (int)($bheight / 2);	
	
	// -- root id
	if (!isset($this->pid)) $this->pid="";
	$this->pid=check_rootid($this->pid);
	if ((DisplayDetailsByID($this->pid))||(showLivingNameByID($this->pid))) $this->name = get_person_name($this->pid);
	else $this->name = $pgv_lang["private"];
	
	//-- check for the user
	$this->uname = getUserName();
	if (!empty($this->uname)) {
		$this->user = getUser($this->uname);
		if (!empty($this->user["default_tab"])) $this->default_tab = $this->user["default_tab"];
	}
	$this->hourPerson = Person::getInstance($this->pid);

	//Checks how many generations of descendency is for the person for formatting purposes
	$this->dgenerations = $this->max_descendency_generations($this->pid, 0);

	if (!$this->isPrintPreview()) {
		$this->visibility = "hidden";
		$this->position = "absolute";
		$this->display = "none";
	}
	//-- perform the desired action
	switch($this->action) {
		case "addfav":
			$this->addFavorite();
			break;
		case "accept":
			$this->acceptChanges();
			break;
		case "undo":
			$this->hourPerson->undoChange();
			break;
	}
}

/**
 * Prints pedigree of the person passed in. Which is the descendancy 
 * 
 * @param mixed $pid ID of person to print the pedigree for 
 * @param mixed $count generation count, so it recursively calls itself
 * @access public
 * @return void
 */
function print_person_pedigree($pid, $count) {
	global $SHOW_EMPTY_BOXES, $PGV_IMAGE_DIR, $PGV_IMAGES;
	if ($count>=$this->generations) return;
	$person = Person::getInstance($pid);
	if (is_null($person)) return;
	$families = $person->getChildFamilies();
	foreach($families as $famid => $family) {
		print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"empty-cells: show;\">\n";
		$parents = find_parents($famid);
		$height="100%";
		print "<tr>";
		print "<td height=\"50%\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
		print "<td rowspan=\"2\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]."\" width=\"7\" height=\"3\" alt=\"\" /></td>";
		print "<td rowspan=\"2\">";
		//-- print the father box
		print_pedigree_person($parents["HUSB"]);
		print "</td>";
		$ARID = $parents["HUSB"];
		print "<td id= \"td_".$ARID."\" rowspan=\"2\">";
		
		//-- print an Ajax arrow on the last generation of the adult male
		if ($count==$this->generations-1 && (count(find_family_ids($ARID))>0) && !is_null (find_family_ids($ARID))) {
			print "<a href=\"#\" onclick=\"return ChangeDiv('td_".$ARID."','".$ARID."','".$this->show_full."','".$this->show_spouse."','".$this->box_width."')\"><img id=\"arrow\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["rarrow"]["other"]."\" border=\"0\" alt=\"\" /></a> ";
		}
		//-- recursively get the father's family
		$this->print_person_pedigree($parents["HUSB"], $count+1);
		
		print "</td>";
		print "</tr>\n<tr>\n<td height=\"50%\"";
		print " style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\" ";
		print "><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td></tr>\n<tr>";
		print "<td height=\"50%\" style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
		print "<td rowspan=\"2\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]."\" width=\"7\" height=\"3\" alt=\"\" /></td>";
		print "<td rowspan=\"2\">";
		//-- print the mother box
		print_pedigree_person($parents["WIFE"]);
		print "</td>";
		$ARID = $parents["WIFE"];
		print "<td id= \"td_".$ARID."\" rowspan=\"2\">";
		
		
		//-- print an ajax arrow on the last generation of the adult female
		if ($count==$this->generations-1 && (count(find_family_ids($ARID))>0) && !is_null (find_family_ids($ARID))) {
			print "<a href=\"#\" onclick=\"return ChangeDiv('td_".$ARID."','".$ARID."','".$this->show_full."','".$this->show_spouse."','".$this->box_width."')\"><img id=\"arrow\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["rarrow"]["other"]."\" border=\"0\" alt=\"\" /></a> ";
		}
		
		//-- recursively print the mother's family
		$this->print_person_pedigree($parents["WIFE"], $count+1);
		print "</td>";
		print "</tr>";
		print "<tr>\n<td height=\"50%\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td></tr>";
		print "</table>";
		break;
		

	}
}

/**
 * Prints descendency of passed in person 
 * 
 * @param mixed $pid ID of person to print descendency for
 * @param mixed $count count of generations to print
 * @access public
 * @return void
 */
function print_descendency($pid, $count) {
	global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $pgv_lang, $bheight, $bwidth, $bhalfheight;

	if ($count>$this->dgenerations) return 0;
	$person = Person::getInstance($pid);
	if (is_null($person)) return;
	
	$tablealign = "right";
	if ($TEXT_DIRECTION=="rtl") $tablealign = "left";
	
	//	print $this->dgenerations;
	print "<!-- print_descendency for $pid -->";
	print "<table align=\"".$tablealign."\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr>";
	print "<td align=\"$tablealign\" width=\"100%\">\n";
	$numkids = 0;
	$families = $person->getSpouseFamilies();
	$famcount = count($families);
	$famNum = 0;
	$kidNum = 0;
	if ($famcount>0) {
		foreach($families as $famid => $family) {
			$famNum ++;
			$famrec = find_family_record($famid);
			$ct = preg_match_all("/1 CHIL @(.*)@/", $famrec, $match, PREG_SET_ORDER);
			if ($ct>0) {
				print "<table style=\"position: relative; top: auto; text-align: $tablealign;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
				for($i=0; $i<$ct; $i++) {
					$rowspan = 2;
					if (($i>0)&&($i<$ct-1)) $rowspan=1;
					$chil = trim($match[$i][1]);
						///////////////////////////////////////////////////////////
						//// use the $kids as the "$ARID" when you add the method call
									
					if ($count+1 < $this->dgenerations) {
						print "<tr>";
						print "<td id=\"td_".$chil."\" class=\"$TEXT_DIRECTION\" rowspan=\"$rowspan\">";
						$kids = $this->print_descendency($chil, $count+1);
						$numkids += $kids;
						print "</td>";
					} else {
						$person2 = Person::getInstance($chil);	
						$fam = $person2->getSpouseFamilies();
						if (count($fam)>0) {
							$numchil = 0;
							foreach($fam as $famid=>$family) {
								if (!is_null($family)) $numchil += $family->getNumberOfChildren();
							}
							if ($numchil>0) print "<td align=\"".$tablealign."\" id=\"td_".$chil."\" rowspan=\"$rowspan\"><a href=\".$chil.\" onclick=\"return ChangeDis('td_".$chil."','".$chil."','".$this->show_full."','".$this->show_spouse."','".$this->box_width."')\"><img id=\"arrow\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["larrow"]["other"]."\" border=\"0\" alt=\"\" /></a></td>";
							else print "<td id= \"td_".$chil."\" rowspan=\"$rowspan\"><div style=\"width: ".$this->arrwidth."px; height: ".$this->arrheight."px;\">&nbsp;</div></td>";
						} else print "<td id= \"td_".$chil."\" rowspan=\"$rowspan\"><div style=\"width: ".$this->arrwidth."px; height: ".$this->arrheight."px;\">&nbsp;</div></td>";
						
						print "<td class=\"$TEXT_DIRECTION\" rowspan=\"$rowspan\" width=\"$bwidth\" style=\"padding-top: 2px;\">";
						print_pedigree_person($chil);
						$numkids++;
						$kidNum ++;
						print "</td>";
					}
						
					$twidth = 7;
					if ($ct==1) $twidth+=3;
					print "<td rowspan=\"$rowspan\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]."\" width=\"$twidth\" height=\"3\" alt=\"\" /></td>";
					if ($ct>1) {
						if ($i==0) {
							//////////////////////
							print "<td height=\"".$bhalfheight."px\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td></tr>";
							print "<tr><td height=\"".$bhalfheight."px\"style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
						} else if ($i==$ct-1) {
							print "<td height=\"".$bhalfheight."px\" style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td></tr>";
							print "<tr><td height=\"".$bhalfheight."px\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
						} else {
							print "<td style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
						}
					}
					print "</tr>";
						
				}
				print "</table>\n";
			}
		}
		print "</td>\n";
		print "<td width=\"$bwidth\">";
	}
	// NOTE: If statement OK
	if ($numkids==0) {
		$numkids = 1;
		$tbwidth = $bwidth+16;
		for($j=$count; $j<$this->dgenerations; $j++) {
			print "<div style=\"width: ".($tbwidth)."px;\"><br /></div>\n</td>\n<td width=\"$bwidth\">";
		}
		print "<div style=\"width: ".($this->arrwidth)."px;\"><br /></div>\n</td>\n<td width=\"$bwidth\">";
	}
	//-- add offset divs to make things line up better
	if ($this->show_spouse) {
		foreach($families as $famid => $family) {
			if (!is_null($family)) {
				$marrec = $family->getMarriageRecord();
				if (!empty($marrec)) {
					print "<br />";
				}
				print "<div style=\"height: ".$bheight."px; width: ".$bwidth."px;\"><br /></div>";
			}
		}
	}
	print_pedigree_person($pid);
	// NOTE: If statement OK
	if ($this->show_spouse) {
		foreach($families as $famid => $family) {
			$famrec = find_family_record($famid);
			if (!empty($famrec)) {
				$parents = find_parents_in_record($famrec);
				$marrec = get_sub_record(1, "1 MARR", $famrec);
				if (!empty($marrec)) {
					print "<br />";
					print_simple_fact($famrec, "1 MARR", $famid);
				}
				if ($parents["HUSB"]!=$pid){
					print_pedigree_person($parents["HUSB"]);
				
				}
				else {
					print_pedigree_person($parents["WIFE"]);
					
				}
				
			}
		}
	}
	// NOTE: If statement OK
	if ($count==0) {
		$indirec = find_person_record($pid);
		// NOTE: If statement OK
		if (displayDetails($indirec) || showLivingName($indirec)) {
			// -- print left arrow for decendants so that we can move down the tree
			$person = Person::getInstance($pid);
			$famids = $person->getSpouseFamilies();
			//-- make sure there is more than 1 child in the family with parents
			$cfamids = $person->getChildFamilies();
			$num=0;
			foreach($cfamids as $famid=>$family) {
				if (!is_null($family)) {
					$num += $family->getNumberOfChildren();
				}
			}
			// NOTE: If statement OK
			if ($famids||($num>1)) {
				print "\n\t\t<div class=\"center\" id=\"childarrow\" dir=\"".$TEXT_DIRECTION."\"";
				print " style=\"position:absolute; width:".$bwidth."px; \">";
				if ($this->view!="preview") {
					print "<a href=\"javascript: ".$pgv_lang["show"]."\" onclick=\"togglechildrenbox(); return false;\" onmouseover=\"swap_image('larrow',3);\" onmouseout=\"swap_image('larrow',3);\">";
					print "<img id=\"larrow\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["darrow"]["other"]."\" border=\"0\" alt=\"\" />";
					print "</a><br />";
					
				}
				print "\n\t\t<div id=\"childbox\" dir=\"".$TEXT_DIRECTION."\" style=\"width:".$bwidth."px; height:".$bheight."px; visibility: hidden;\">";
				print "\n\t\t\t<table class=\"person_box\"><tr><td>";
				
				foreach($famids as $famid=>$family) {
					if (!is_null($family)) {
						if($pid!=$family->getHusbId()) $spid=$family->getHusbId();
						else $spid=$family->getWifeId();
						if (!empty($spid)) {
							print "\n\t\t\t\t<a href=\"hourglass.php?pid=$spid&amp;show_spouse=$this->show_spouse&amp;show_full=$this->show_full&amp;generations=$this->generations&amp;box_width=$this->box_width\"><span ";
							if (displayDetailsById($spid) || showLivingNameById($spid)) {
								$name = get_person_name($spid);
								$name = rtrim($name);
								if (hasRTLText($name))
								     print "class=\"name2\">";
				   				else print "class=\"name1\">";
								print PrintReady($name);
							}
							else print $pgv_lang["private"];
							print "<br /></span></a>";
										
						}

						$children = $family->getChildren();
						foreach($children as $id=>$child) {
							$cid = $child->getXref();
							print "\n\t\t\t\t&nbsp;&nbsp;<a href=\"hourglass.php?pid=$cid&amp;show_spouse=$this->show_spouse&amp;show_full=$this->show_full&amp;generations=$this->generations&amp;box_width=$this->box_width\"><span ";
							if (displayDetailsById($cid) || showLivingNameById($cid)) {
								$name = get_person_name($cid);
								$name = rtrim($name);
								if (hasRTLText($name))
								     print "class=\"name2\">&lt; ";
					   			else print "class=\"name1\">&lt; ";
								print PrintReady($name);
								
							}
							else print ">" . $pgv_lang["private"];
							print "<br /></span></a>";
							
						}
					}
				}
				print "<a href=\"#\"><img id=\"arrow\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["rarrow"]["other"]."\" border=\"0\" alt=\"\" /></a> ";
			
				
				//-- print the siblings
				foreach($cfamids as $famid=>$family) {
					if (!is_null($family)) {
						$parents = find_parents($famid);
						if($parents) {
							print "<span class=\"name1\"><br />".$pgv_lang["parents"]."<br /></span>";
							if (!empty($parents["HUSB"])) {
								$spid = $parents["HUSB"];
								print "\n\t\t\t\t&nbsp;&nbsp;<a href=\"hourglass.php?pid=$spid&amp;show_spouse=$this->show_spouse&amp;show_full=$this->show_full&amp;generations=$this->generations&amp;box_width=$this->box_width\"><span ";
								if (displayDetailsById($spid) || showLivingNameById($spid)) {
									$name = get_person_name($spid);
									$name = rtrim($name);
									if (hasRTLText($name))
									     print "class=\"name2\">";
					   				else print "class=\"name1\">";
									print PrintReady($name);
								}
								else print $pgv_lang["private"];
								print "<br /></span></a>";
							}
							if (!empty($parents["WIFE"])) {
								$spid = $parents["WIFE"];
								print "\n\t\t\t\t&nbsp;&nbsp;<a href=\"hourglass.php?pid=$spid&amp;show_spouse=$this->show_spouse&amp;show_full=$this->show_full&amp;generations=$this->generations&amp;box_width=$this->box_width\"><span ";
								if (displayDetailsById($spid) || showLivingNameById($spid)) {
									$name = get_person_name($spid);
									$name = rtrim($name);
									if (hasRTLText($name))
									     print "class=\"name2\">";
					   				else print "class=\"name1\">";
									print PrintReady($name);
								}
								else print $pgv_lang["private"];
								print "<br /></span></a>";
							}
						}
						$children = $family->getChildren();
						$num = $family->getNumberOfChildren();
						if ($num>1) print "<span class=\"name1\"><br />".$pgv_lang["siblings"]."<br /></span>";
						foreach($children as $id=>$child) {
							$cid = $child->getXref();
							if ($cid!=$pid) {
								print "\n\t\t\t\t&nbsp;&nbsp;<a href=\"hourglass.php?pid=$cid&amp;show_spouse=$this->show_spouse&amp;show_full=$this->show_full&amp;generations=$this->generations&amp;box_width=$this->box_width\"><span ";
								if (displayDetailsById($cid) || showLivingNameById($cid)) {
									$name = get_person_name($cid);
									$name = rtrim($name);
									if (hasRTLText($name))
									print "class=\"name2\"> ";
					   				else print "class=\"name1\"> ";
									print PrintReady($name);
								}
								else print ">". $pgv_lang["private"];
								print "<br /></span></a>";
								
							}
						}
					}
				}
				print "\n\t\t\t</td></tr></table>";
				print "\n\t\t</div>";
				print "\n\t\t</div>";
			}
		}
	}
	print "</td></tr>";
	print "</table>";
	return $numkids;
}
/////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
// Prints the descendency half of the chart via ajax

function print_descendency_ajax($pid, $count) {
	global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $pgv_lang, $bheight, $bwidth, $bhalfheight;
	
	if ($count>=$this->dgenerations) return 0;
	$person = Person::getInstance($pid);
	if (is_null($person)) return;
	
	$tablealign = "right";
	if ($TEXT_DIRECTION=="rtl") $tablealign = "left";
	
	//	print $this->dgenerations;
	print "<!-- print_descendency for $pid -->";
	print "<table align=\"$tablealign\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr>";
	print "<td align=\"$tablealign\" width=\"$bwidth\">";
	
	$numkids = 0;
	$families = $person->getSpouseFamilies();
	if (count($families)>0) {
		$firstkids = 0;
		foreach($families as $famid => $family) {
			$famrec = find_family_record($famid);
			$ct = preg_match_all("/1 CHIL @(.*)@/", $famrec, $match, PREG_SET_ORDER);
			if ($ct>0) {
			print "<table style=\"position: relative; top: auto; text-align: $tablealign;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
			for($i=0; $i<$ct; $i++) {
				$rowspan = 2;
				if (($i>0)&&($i<$ct-1)) $rowspan=1;
				$chil = trim($match[$i][1]);
				///////////////////////////////////////////////////////////
				//// use the $kids as the "$ARID" when you add the method call
				print "<tr>";			
				if ($count+1 < $this->dgenerations) {
					print "<td id=\"td_".$chil."\" rowspan=\"$rowspan\">";
					$kids = $this->print_descendency($chil, $count+1);
					if ($i==0) $firstkids = $kids;
					$numkids += $kids;
					print "</td>";
					
				}
				else {
					$person2 = Person::getInstance($chil);	
					$fam = $person2->getSpouseFamilies();
					if (count($fam)>0) {
						$numchil = 0;
						foreach($fam as $famid=>$family) {
							if (!is_null($family)) $numchil += $family->getNumberOfChildren();
						}
						if ($numchil>0) {
							print "<td id= \"td_".$chil."\" rowspan=\"$rowspan\"><a href=\".$chil.\" onclick=\"return ChangeDis('td_".$chil."','".$chil."','".$this->show_full."','".$this->show_spouse."', '".$this->box_width."')\"><img id=\"arrow\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["larrow"]["other"]."\" border=\"0\" alt=\"\" /></a></td>";
						}
						else{
							print "<td id= \"td_".$chil."\" rowspan=\"$rowspan\"><div style=\"width: ".$this->arrwidth."px; height: ".$this->arrheight."px;\">&nbsp;</div></td>";
						}
					}
					else{
						print "<td id= \"td_".$chil."\" rowspan=\"$rowspan\"><div style=\"width: ".$this->arrwidth."px; height: ".$this->arrheight."px;\">&nbsp;</div></td>";
					}

					
					print "<td class=\"$TEXT_DIRECTION\" id=\"tc_".$chil."\"  rowspan=\"".$rowspan."\" / >";
					print_pedigree_person($chil);
					// NOTE: If statement OK
					if ($this->show_spouse) {
						$cfams = $person2->getSpouseFamilies();
						foreach($cfams as $famid => $family) {
							$famrec = find_family_record($famid);
							if (!empty($famrec)) {
								$parents = find_parents_in_record($famrec);
								if ($parents["HUSB"]!=$chil){
									print_pedigree_person($parents["HUSB"]);
									print "<br />";
								}
								else {
									print_pedigree_person($parents["WIFE"]);
									print "<br />";
								}
							}
						}
					}
					$numkids++;
					print "</td>";
				}
				
				$twidth = 7;
				if ($ct==1) $twidth+=3;
				print "<td id=\"ta_".$chil."\" rowspan=\"$rowspan\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]."\" width=\"".$twidth."\" height=\"3\" alt=\"\" /></td>";
				if ($ct>1) {
					if ($i==0) {
						//////////////////////
						
						print "<td height=\"".($bhalfheight+4)."px\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td></tr>";
						print "<tr valign=\"bottom\"><td height=\"".$bhalfheight."px\" style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
					    
					} else if ($i==($ct-1)) {
						print "<td height=\"".($bhalfheight+4)."px\" style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td></tr>";
						print "<tr><td height=\"".$bhalfheight."px\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /></td>";
						
					} else {
						print "<td style=\"background: url('".$PGV_IMAGE_DIR."/".$PGV_IMAGES["vline"]["other"]."');\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" width=\"3\" alt=\"\" /> <td/>";
					}
				}
				print "</tr>";
				
			}
			print "</table>";
			}
		}
	}
	print "</td></tr>";
	print "</table>";
	return $numkids;
}


////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Calculates number of generations a person has
 * 
 * @param mixed $pid ID of person to see how far down the descendency goes
 * @param mixed $depth Pass in 0 and it calculates how far down descendency goes
 * @access public
 * @return maxdc Amount of generations the descendency actually goes
 */
function max_descendency_generations($pid, $depth) {
	if ($depth >= $this->generations) return $depth;
	$person = Person::getInstance($pid);
	if (is_null($person)) return $depth;
	$famids = $person->getSpouseFamilies();
	$maxdc = $depth;
	foreach($famids as $famid => $family){
		$ct = preg_match_all("/1 CHIL @(.*)@/", $family->gedrec, $match, PREG_SET_ORDER);
		for($i=0; $i<$ct; $i++) {
			$chil = trim($match[$i][1]);
			$dc = $this->max_descendency_generations($chil, $depth+1);
			if ($dc >= $this->generations) return $dc;
			if ($dc > $maxdc) $maxdc = $dc;
		}
	}
	if ($maxdc==0) $maxdc++;
	return $maxdc;
}

}

// -- end of class
//-- load a user extended class if one exists
if (file_exists('includes/controllers/hourglass_ctrl_user.php'))
{
	include_once 'includes/controllers/hourglass_ctrl_user.php';
}
else
{
	class HourglassController extends HourglassControllerRoot
	{
	}
}

$controller = new HourglassController();
$controller->init();
?>
