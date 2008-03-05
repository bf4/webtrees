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
 * @version $Id$
 */
require_once('includes/person_class.php');
require_once('includes/functions_charts.php');

class TreeNav {
	var $rootPerson = null;
	var $bwidth = 170;
	var $zoomLevel = 0;
	var $name = 'nav';
	var $generations = 4;
	
	/**
	 * Tree Navigator Constructor
	 * @param string $rootid	the rootid of the person
	 * @param int $zoom			The starting zoom level
	 */
	function TreeNav($rootid='', $name='nav', $zoom=0) {
		if ($rootid!='none') {
			$rootid = check_rootid($rootid);
			$this->zoomLevel = $zoom;
			$this->rootPerson = Person::getInstance($rootid);
			if (is_null($this->rootPerson)) $this->rootPerson = new Person('');
		}
		$this->name = $name;
		//-- handle AJAX requests
		if (!empty($_REQUEST['navAjax'])) {
			if (!empty($_REQUEST['details'])) {
				$this->getDetails($this->rootPerson);
			}
			else if (!empty($_REQUEST['newroot'])) {
				$_SESSION['navRoot'] = $this->rootPerson->getXref();
				if (!empty($_REQUEST['drawport'])) $this->drawViewport('', "", "150px"); 
				else {
					$fam = null;
					$this->drawPerson($this->rootPerson, 4, 0, $fam);
				}
			}
			else if (!empty($_REQUEST['parent'])) {
				$person = $this->rootPerson;
				if ($_REQUEST['parent']=='f') {
					$fams = $person->getChildFamilies();
					$cfamily = end($fams);
					if (!empty($cfamily)) {
						$father = $cfamily->getHusband();
						if (!empty($father)) {
							$fam = null;
							$this->drawPerson($father, 1, 1, $fam);
						}
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
							if (!empty($mother)) {
								$fam = null;
								$this->drawPerson($mother, 1, 1, $fam);
							}
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
		global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		if (empty($id)) $id = $this->rootPerson->getXref();
		$widthS = "";
		$heightS = "";
		if (!empty($width)) $widthS = "width: $width; ";
		if (!empty($height)) $heightS = "height: $height; ";
		?>
		<?php $this->setupJS(); ?>
		
		<div id="out_<?php print $this->name; ?>" style="position: relative; <?php print $widthS.$heightS; ?>text-align: center; overflow: hidden;">
			<div id="in_<?php print $this->name; ?>" style="position: relative; left: -20px; width: auto; cursor: move;" onmousedown="dragStart(event, 'in_<?php print $this->name; ?>', <?php print $this->name; ?>);" onmouseup="dragStop(event);">
			<?php $parent=null; $this->drawPerson($this->rootPerson, $this->generations, 0, $parent); ?>
			</div>
			<div id="controls" style="position: absolute; left: 0px; top: 0px; z-index: 100; background-color: #EEEEEE">
			<table>
				<tr><td><a href="#" onclick="<?php print $this->name; ?>.zoomIn(); return false;"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['zoomin']['other'];?>" border="0" /></a></td></tr>
				<tr><td><a href="#" onclick="<?php print $this->name; ?>.zoomOut(); return false;"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['zoomout']['other'];?>" border="0" /></a></td></tr>
				<tr><td <?php if (is_null($this->rootPerson)) print "style=\"display: none;\"";?>><a id="biglink" href="#" onclick="<?php print $this->name; ?>.loadBigTree('<?php if (!is_null($this->rootPerson)) print $this->rootPerson->getXref();?>','<?php print htmlentities($GEDCOM);?>'); return false;"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['gedcom']['small'];?>" border="0" /></a></td></tr>
			</table>
			</div>
		</div>
		<script type="text/javascript">
		<!--
		var <?php print $this->name; ?> = new NavTree("out_<?php print $this->name; ?>","in_<?php print $this->name; ?>", '<?php print $this->name; ?>');
		<?php print $this->name; ?>.sizeLines();
		//-->
		</script>
		<?php
	}
	
	/**
	 * Setup the JavaScript for the tree navigator
	 */
	function setupJS() {
		require_once("js/prototype.js.htm");
		require_once("js/behaviour.js.htm");
		require_once("js/overlib.js.htm");
		require_once("js/scriptaculous.js.htm");
		?>
	<script type="text/javascript" src="js/treenav.js"></script>
	<script type="text/javascript">
	<!--
		var myrules = {
		'#out_<?php print $this->name; ?> .person_box' : function(element) {
			element.onmouseout = function() {
				if (<?php print $this->name; ?>.zoom>=-2) return false;
				return nd(); // hide helptext
			}
			element.onmouseover = function() { // show helptext
				if (<?php print $this->name; ?>.zoom>=-2) return false;
				bid = element.id.split("_");
				if (<?php print $this->name; ?>.opennedBox[bid[1]]) return false;
				helptext = this.title;
				if (helptext=='') helptext = this.value;
				if (helptext=='' || helptext==undefined) helptext = element.innerHTML;
				this.title = helptext; if (document.all) return; // IE = title
				this.value = helptext; this.title = ''; // Firefox = value
				//-- show images
				helptext=helptext.replace(/display: none;/gi, "display: inline;");
				return overlib(helptext, BGCOLOR, "#000000", FGCOLOR, "#FFFFE0");
			}
		},
		'.draggable' : function(element) {
			new Draggable(element.id, {revert:true});
		}
		}
		Behaviour.register(myrules);
		
		function dragObserver() {
			this.parent = null;
			this.onEnd = function(eventName, draggable, event) {
				this.parent.appendChild(draggable.element);
				<?php print $this->name; ?>.collapseBox = false;
			}
			this.onStart = function(eventName, draggable, event) {
				this.parent = draggable.element.parentNode;
				document.body.appendChild(draggable.element);
			}
		}
		Draggables.addObserver(new dragObserver());
	//-->
	</script>
		<?php
	}
	
	/**
	 * Get the details for a person and their spouse
	 * @param Person $person	the person to print the details for
	 */
	function getDetails(&$person) {
		global $factarray, $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		
		if (empty($person)) $person = $this->rootPerson;
		if (!$person->canDisplayDetails()) return;
		
		if (!empty($_REQUEST['famid'])) {
			$family = Family::getInstance($_REQUEST['famid']);
			if (!empty($family)) $spouse = $family->getSpouse($person);
		}
		if (empty($spouse)) {
			$spouse = $person->getCurrentSpouse();
			$fams = $person->getSpouseFamilies();
			$family = end($fams); 
		}
		
		$name = $person->getName(); 
		if ($SHOW_ID_NUMBERS) 
		$name.=" (".$person->getXref().")";
		
		?>
		<span class="name1"><a href="individual.php?pid=<?php print $person->getXref(); ?>&amp;ged=<?php print $GEDCOM; ?>" onclick="if (!<?php print $this->name;?>.collapseBox) return false;"><?php print $person->getSexImage().PrintReady($name); ?></a>
		<img id="d_<?php print $person->getXref(); ?>" alt="<?php print $person->getXref(); ?>" class="draggable" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['indi']['button']; ?>" border="0" />
		<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"];?>" border="0" width="15" onclick="<?php print $this->name;?>.newRoot('<?php print $person->getXref();?>', <?php print $this->name;?>.innerPort, '<?php print htmlentities($GEDCOM); ?>');" /> 
		</span><br />
		<div class="details1 indent">
			<b><?php print get_first_letter($factarray['BIRT']);?>:</b>
			<?php
				$bdate = $person->getBirthDate();
				if (!is_null($bdate)) print $bdate->Display();
			?>
			<?php $place = $person->getBirthPlace();  if (!empty($place)) print PrintReady($place); ?>
			<br />
			<b><?php print get_first_letter($factarray['MARR']);?>:</b>
			<?php if (!empty($family)) {
				$mdate = $family->getMarriageDate();
				if (!is_null($mdate)) print $mdate->Display()." ";
				$place=''; 
				$place = $family->getMarriagePlace();  
				if (!empty($place)) print PrintReady($place); ?>
				<a href="family.php?famid=<?php print $family->getXref(); ?>" onclick="if (!<?php print $this->name;?>.collapseBox) return false;"><img id="d_<?php print $family->getXref(); ?>" alt="<?php print $family->getXref(); ?>" class="draggable" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['family']['button']; ?>" border="0" /></a>
			<?php } ?>
			<br />
			<b><?php print get_first_letter($factarray['DEAT']);?>:</b>
			<?php
				$ddate = $person->getDeathDate(false);
				if (!is_null($ddate)) print $ddate->Display();
			?>
			<?php $place = $person->getDeathPlace();  if (!empty($place)) print PrintReady($place); ?>
		</div>
		<br />
		<span class="name1"><?php 
		if (!is_null($spouse)) {
			$name = $spouse->getName(); 
			if ($SHOW_ID_NUMBERS) 
			$name.=" (".$spouse->getXref().")";
			?>
			<a href="individual.php?pid=<?php print $spouse->getXref(); ?>&amp;ged=<?php print $GEDCOM; ?>" onclick="if (!<?php print $this->name;?>.collapseBox) return false;"> 
			<?php print $spouse->getSexImage().PrintReady($name); ?></a>
			<img id="d_<?php print $spouse->getXref(); ?>" alt="<?php print $spouse->getXref(); ?>" class="draggable" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES['indi']['button']; ?>" border="0" />
			<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"];?>" border="0" width="15" onclick="<?php print $this->name;?>.newRoot('<?php print $spouse->getXref();?>', <?php print $this->name;?>.innerPort, '<?php print htmlentities($GEDCOM); ?>');" />
			<br />
			<div class="details1 indent">
			<b><?php print get_first_letter($factarray['BIRT']);?>:</b>
			<?php
				$bdate = $spouse->getBirthDate();
				if (!is_null($bdate)) print $bdate->Display();
			?>
			<?php $place = $spouse->getBirthPlace();  if (!empty($place)) print PrintReady($place); ?>
			<br />
			<b><?php print get_first_letter($factarray['DEAT']);?>:</b>
			<?php
				$ddate = $spouse->getDeathDate(false);
				if (!is_null($ddate)) print $ddate->Display();
			?>
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
				$fam = null;
				$this->drawPerson($child, $gen-1, -1, $fam);
			}
		}
	}
	
	/**
	 * Draw a person for the chart
	 * @param Person $person		The Person object to draw the box for
	 * @param int $gen				The number of generations up or down to print
	 * @param int $state			Whether we are going up or down the tree, -1 for descendents +1 for ancestors
	 * @param Family $pfamily
	 */
	function drawPerson(&$person, $gen, $state, &$pfamily) {
		global $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES;
		
		if ($gen<0) {
			return;
		}
		if ($this->zoomLevel < -2) $style = "display: none;";
		else $style = "width: ".(10+$this->zoomLevel)."; height: ".(10+$this->zoomLevel).";";
		if (empty($person)) $person = $this->rootPerson;
		if (empty($person)) return;
		if (!$person->canDisplayDetails()) return;
		$mother = null;
		$father = null;
		if (!empty($pfamily)) $spouse = $pfamily->getSpouse($person);
		else {
			$spouse = $person->getCurrentSpouse();
			$fams = $person->getSpouseFamilies();
			$pfamily = end($fams);
		}
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
				$mcfamily = end($fams);
				if (!empty($mcfamily)) {
					$mother = $mcfamily->getHusband();
				}
			}
		}
		?>
		<table border="0" cellpadding="0" cellspacing="0" style="margin-top: 0px; margin-bottom: 1px;">
			<tbody>
				<tr>
					<?php /* print the children */
					if ($state<=0) {
						$hasChildren = false;
						if (!empty($family) && $family->getNumberOfChildren()>0) $hasChildren = true;  
					?>
					<td id="ch_<?php print $person->getXref();?>" align="right" <?php if ($gen==0 && $hasChildren) print 'id="'.$this->name.'_cload" name="'.$this->name.'_cload" onclick="'.$this->name.'.loadChild(this, \''.$person->getXref().'\');"'; ?>>
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
						<div class="person_box" id="box_<?php print $person->getXref();?>" style="text-align: left; cursor: pointer; font-size: <?php print 10 + $this->zoomLevel;?>px; width: <?php print ($this->bwidth+($this->zoomLevel*18));?>px;" onclick="<?php print $this->name; ?>.expandBox(this, '<?php print $person->getXref(); ?>', '<?php if (!empty($pfamily)) print $pfamily->getXref(); ?>');">
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
						<table cellpadding="0" cellspacing="0" border="0">
							<tbody>
								<tr>
									<?php /* there is a IE JavaScript bug where the "id" has to be the same as the "name" in order to use the document.getElementsByName() function */ ?>
									<td <?php if ($gen==0 && !empty($father)) print 'id="'.$this->name.'_pload" name="'.$this->name.'_pload" onclick="'.$this->name.'.loadParent(this, \''.$person->getXref().'\', \'f\');"'; ?>>
										<?php if (!empty($father)) $this->drawPerson($father, $gen-1, 1, $cfamily); else print "<br />\n";?>
									</td>
								</tr>
								<tr>
								<?php /* print the mother */ ?>
									<td <?php if ($gen==0 && !empty($mother)) print 'id="'.$this->name.'_pload" name="'.$this->name.'_pload" onclick="'.$this->name.'.loadParent(this, \''.$person->getXref().'\', \'m\');"'; ?>>
										<?php if (!empty($mother)) $this->drawPerson($mother, $gen-1, 1, $mcfamily); else print"<br />\n";?>
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