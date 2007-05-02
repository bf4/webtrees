<?php
/**
 * Class file for the tree navigator
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
 * @version $Id: person_class.php 981 2007-03-21 13:24:38Z fisharebest $
 */
require_once('includes/person_class.php');
require_once('includes/functions_charts.php');

class TreeNav {
	var $rootPerson;
	var $bwidth = 170;
	var $zoomLevel = 0;
	
	/**
	 * Tree Navigator Constructor
	 * @param string $rootid	the rootid of the person
	 * @param int $zoom			The starting zoom level
	 */
	function TreeNav($rootid='', $zoom=0) {
		$rootid = check_rootid($rootid);
		$this->zoomLevel = $zoom;
		$this->rootPerson = Person::getInstance($rootid);
		if (is_null($this->rootPerson)) $this->rootPerson = new Person('');
		
		//-- handle AJAX requests
		if (!empty($_REQUEST['navAjax'])) {
			if (!empty($_REQUEST['details'])) {
				$this->getDetails();
			}
			else if (!empty($_REQUEST['newroot'])) {
				$_SESSION['navRoot'] = $this->rootPerson->getXref();
				$this->drawPerson();
			}
			else if (!empty($_REQUEST['parent'])) {
				$person = $this->rootPerson;
				if ($_REQUEST['parent']=='f') {
					$fams = $person->getChildFamilies();
					$cfamily = end($fams);
					if (!empty($cfamily)) {
						$father = $cfamily->getHusband();
						if (!empty($father)) $this->drawPerson($father, 1, 1);
						else print "<br />\n";
					}
					else print "<br />\n";
				}
				else {
					if (!empty($spouse)) {
						$spouse = $person->getCurrentSpouse();
						$fams = $spouse->getChildFamilies();
						$cfamily = end($fams);
						if (!empty($cfamily)) {
							$mother = $cfamily->getHusband();
							if (!empty($mother)) $this->drawPerson($mother, 1, 1);
							else print "<br />\n";
						}
						else print "<br />\n";
					}
					else print "<br />\n";
				}
			}
			else {
				$fams = $this->rootPerson->getSpouseFamilies();
				$family = end($fams);
				$this->drawChildren($family, 1);
			}
			exit;
		}
	}
	
	/**
	 * Draw the view port which creates the draggable/zoomable framework
	 * @param string $id	an id to use for the starting HTML elements
	 * @param string $width	the width parameter for the outer style
	 * @param string $height	the height parameter for the outer style  
	 */
	function drawViewport($id='', $width='', $height='') {
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		if (empty($id)) $id = $this->rootPerson->getXref();
		$widthS = "";
		$heightS = "";
		if (!empty($width)) $widthS = "width: $width; ";
		if (!empty($height)) $heightS = "height: $height; ";
		?>
		<?php $this->setupJS(); ?>
		
		<div id="out_<?php print $id; ?>" style="position: relative; border: solid blue 1px; <?php print $widthS.$heightS; ?>text-align: center; overflow: hidden;">
			<div id="in_<?php print $id; ?>" style="position: relative; left: -190px; top: -50px; width: auto; cursor: move;" onmousedown="dragStart(event, 'in_<?php print $id; ?>');" onmouseup="dragStop(event);">
			<?php $this->drawPerson(); ?>
			</div>
			<div id="controls" style="position: absolute; left: 0px; top: 0px; z-index: 100;">
			<table>
				<tr><td><a href="#" onclick="zoomIn(); return false;"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['zoomin']['other'];?>" border="0" /></a></td></tr>
				<tr><td><a href="#" onclick="zoomOut(); return false;"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['zoomout']['other'];?>" border="0" /></a></td></tr>
			</table>
			</div>
		</div>
		<script type="text/javascript">
		<!--
		var innerPort = document.getElementById("in_<?php print $id; ?>");
		var outerPort = document.getElementById("out_<?php print $id; ?>");
		var rootTable = innerPort.getElementsByTagName("table")[0];
		sizeLines();
		//-->
		</script>
		<?php
	}
	
	/**
	 * Setup the JavaScript for the tree navigator
	 */
	function setupJS() {
		?>
	<script type="text/javascript" src="js/treenav.js"></script>
		<?php
	}
	
	/**
	 * Get the details for a person and their spouse
	 * @param Person $person	the person to print the details for
	 */
	function getDetails(&$person = '') {
		global $factarray, $SHOW_ID_NUMBERS;
		
		if (empty($person)) $person = $this->rootPerson;
		if (!$person->canDisplayDetails()) return;
		$spouse = $person->getCurrentSpouse();
		
		$name = $person->getName(); 
		if ($SHOW_ID_NUMBERS) 
		$name.=" (".$person->getXref().")";
		$fams = $person->getSpouseFamilies();
		$family = end($fams); 
		?>
		<span class="name1"><a href="#" onclick="return newRoot('<?php print $person->getXref(); ?>');"><?php print $person->getSexImage().PrintReady($name); ?></a></span><br />
		<div class="details1 indent">
			<b><?php print get_first_letter($factarray['BIRT']);?>:</b> <?php print get_changed_date($person->getBirthDate()); ?>
			<?php $place = $person->getBirthPlace();  if (!empty($place)) print PrintReady($place); ?>
			<br />
			<b><?php print get_first_letter($factarray['MARR']);?>:</b> <?php if (!empty($family)) print get_changed_date($family->getMarriageDate()); ?>
			<?php $place=''; if (!empty($family)) $place = $family->getMarriagePlace();  if (!empty($place)) print PrintReady($place); ?>
			<br />
			<b><?php print get_first_letter($factarray['DEAT']);?>:</b> <?php print get_changed_date($person->getDeathDate()); ?>
			<?php $place = $person->getDeathPlace();  if (!empty($place)) print PrintReady($place); ?>
		</div>
		<br />
		<span class="name1"><?php 
		if (!is_null($spouse)) {
			$name = $spouse->getName(); 
			if ($SHOW_ID_NUMBERS) 
			$name.=" (".$spouse->getXref().")";
			?>
			<a href="#" onclick="return newRoot('<?php print $spouse->getXref(); ?>');"> 
			<?php print $spouse->getSexImage().PrintReady($name); ?></a><br />
			<div class="details1 indent">
			<b><?php print get_first_letter($factarray['BIRT']);?>:</b> <?php print get_changed_date($spouse->getBirthDate()); ?>
			<?php $place = $spouse->getBirthPlace();  if (!empty($place)) print PrintReady($place); ?>
			<br />
			<b><?php print get_first_letter($factarray['DEAT']);?>:</b> <?php print get_changed_date($spouse->getDeathDate()); ?>
			<?php $place = $spouse->getDeathPlace();  if (!empty($place)) print PrintReady($place); ?>
			</div>
			<?php 
		} else print "<br />\n"; ?>
		</span>
		<?php
	}
	
	/**
	 * Draw the children for a family
	 * @param Family $family	The family to draw the children for
	 * @param int $gen			The number of generations of descendents to draw
	 */
	function drawChildren(&$family, $gen=2) {
		if (!empty($family) && $gen>0) {
			$children = $family->getChildren();
			foreach($children as $ci=>$child) {
				$this->drawPerson($child, $gen-1, -1);
			}
		}
	}
	
	/**
	 * Draw a person for the chart
	 * @param Person $person		The Person object to draw the box for
	 * @param int $gen				The number of generations up or down to print
	 * @param int $state			Whether we are going up or down the tree, -1 for descendents +1 for ancestors
	 */
	function drawPerson(&$person='', $gen=3, $state=0) {
		global $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES;
		
		if ($gen<0) {
			return;
		}
		if ($this->zoomLevel < -2) $style = "display: none;";
		else $style = "width: ".(10+$this->zoomLevel)."; height: ".(10+$this->zoomLevel).";";
		if (empty($person)) $person = $this->rootPerson;
		if (!$person->canDisplayDetails()) return;
		$spouse = $person->getCurrentSpouse();
		if ($state<=0) {
			$fams = $person->getSpouseFamilies();
			$family = end($fams);
		}
		if ($state>=0) {
			$fams = $person->getChildFamilies();
			$cfamily = end($fams);
			if (!empty($cfamily)) {
				$father = $cfamily->getHusband();
			}
			if (!empty($spouse)) {
				$fams = $spouse->getChildFamilies();
				$cfamily = end($fams);
				if (!empty($cfamily)) {
					$mother = $cfamily->getHusband();
				}
			}
		}
		?>
		<table border="0" cellpadding="0" cellspacing="0" style="margin-top: 1px; margin-bottom: 1px;">
			<tbody>
				<tr>
					<?php /* print the children */
					if ($state<=0) {
						$hasChildren = false;
						if (!empty($family) && $family->getNumberOfChildren()>0) $hasChildren = true;  
					?>
					<td id="ch_<?php print $person->getXref();?>" align="right" <?php if ($gen==0 && $hasChildren) print 'name="cload" onclick="loadChildren(this, \''.$person->getXref().'\');"'; ?>>
						<?php
							$this->drawChildren($family, $gen);
						?>
					</td>
					<?php  
					if ($hasChildren && $family->getNumberOfChildren()>1) { ?><td valign="top"><img style="position: absolute;" id="cline_<?php print $person->getXref();?>" name="vertline" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['vline']['other']; ?>" width="3" /></td><?php }
					}
					if ($state>0) {
						?><td><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['hline']['other']; ?>" width="8" height="3" /></td><?php
					}
					/* print the person */ ?>
					<td>
						<div class="person_box" id="box_<?php print $person->getXref();?>" style="text-align: left; cursor: pointer; font-size: <?php print 10 + $this->zoomLevel;?>px; width: <?php print ($this->bwidth+($this->zoomLevel*18));?>px;" onclick="expandBox(this, '<?php print $person->getXref(); ?>');">
						<?php $name = $person->getName(); if ($SHOW_ID_NUMBERS) $name.=" (".$person->getXref().")"; print $person->getSexImage($style).PrintReady($name); ?><br />
						<?php if (!is_null($spouse)) {$name = $spouse->getName(); if ($SHOW_ID_NUMBERS) $name.=" (".$spouse->getXref().")"; print $spouse->getSexImage($style).PrintReady($name); } else print "<br />\n"; ?>
						
						</div>
					</td>
					<?php 
					if ($state<0) {
						?><td><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['hline']['other']; ?>" width="8" height="3" /></td><?php
					}
					/* print the father */ 
					if ($state>=0 && (!empty($father) || !empty($mother))) {
						$lineid = "pline_";
						if (!empty($father)) $lineid.=$father->getXref();
						$lineid.="_";
						if (!empty($mother)) $lineid.=$mother->getXref();
						?>
					<?php if (!empty($father) && (!empty($mother))) { ?><td><img style="position: absolute;" id="<?php print $lineid;?>" name="pvertline" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['vline']['other']; ?>" width="3" /></td><?php } ?>
					<td align="left">
						<table cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td <?php if ($gen==0 && !empty($father)) print 'name="pload" onclick="loadParent(this, \''.$person->getXref().'\', \'f\');"'; ?>>
										<?php if (!empty($father)) $this->drawPerson($father, $gen-1, 1); else print "<br />\n";?>
									</td>
								</tr>
								<tr>
								<?php /* print the mother */ ?>
									<td <?php if ($gen==0 && !empty($mother)) print 'name="pload" onclick="loadParent(this, \''.$person->getXref().'\', \'m\');"'; ?>>
										<?php if (!empty($mother)) $this->drawPerson($mother, $gen-1, 1); else print"<br />\n";?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
		<?php
	}
}
?>