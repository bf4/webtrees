<?php
/**
 * Media Link Assistant Control module for phpGedView
 *
 * Media Link information about an individual
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage GEDFact_assistant
 * @version $Id$
*/

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}
?>

<html>
<head>
</head>
<body>
<?php

	$links = get_media_relations($mediaid);
	// var_dump($links);
	echo "<table><tr><td>";
	echo "<table id=\"existLinkTbl\" width=\"430\" cellspacing=\"1\" >";
	echo "<tr>";
	echo '<td class="topbottombar" width="15"  style="text-align:left;font-weight:100;" >#</td>';
	echo '<td class="topbottombar" width="50"  style="text-align:left;font-weight:100;" >ID:</td>';
	echo '<td class="topbottombar" width="340" style="text-align:left;font-weight:100;" >', $pgv_lang["name"], '</td>';
	echo '<td class="topbottombar" width="20"  style="text-align:left;font-weight:100;" >', $pgv_lang["keep"], '</td>';
	echo '<td class="topbottombar" width="20"  style="text-align:left;font-weight:100;" >', $pgv_lang["remove"], '</td>';
	echo '<td class="topbottombar" width="20"  style="text-align:left;font-weight:100;" >', $pgv_lang["nav"], '</td>';
	echo "</tr>";

	$keys = array_keys($links);
	$values = array_values($links);
	$i=1;
	foreach ($keys as $link) {
		$record=GedcomRecord::getInstance($link);
		echo "<tr ><td>";
		echo $i;
		echo "</td><td id=\"existId_".$i."\" class=\"row2\">";
			echo $link;
		echo "</td><td>";
		if ($record->getType()=='INDI') {
			$idrecord=Person::getInstance($link);
		} elseif ($record->getType()=='FAM') {
			$idrecord=Family::getInstance($link);
			if ($idrecord->getHusbId()) {
				$head=$idrecord->getHusbId();
			}else{
				$head=$idrecord->getWifeId();
			}
		} elseif ($record->getType()=='SOUR') {
			$idrecord=Source::getInstance($link);
		} else {
			
		}
		
		$nam = $idrecord->getFullName();
		echo $nam;
		echo "</td>";
		echo "<td align='center'><input alt='", $pgv_lang["keep_link"], 		"', title='", $pgv_lang["keep_link"], 		"' type='radio' id='".$link."_off' name='".$link."' checked /></td>";
		echo "<td align='center'><input alt='", $pgv_lang["remove_link"], 	"', title='", $pgv_lang["remove_link"], 	"' type='radio' id='".$link."_on'  name='".$link."' /></td>";

		if ($record->getType()=='INDI') {
			?>
			<td align="center"><a href="#"><img style="border-style:none; margin-top:5px;" src="<?php echo $PGV_IMAGE_DIR;?>/buttons/family.gif" alt="<?php echo $pgv_lang["open_nav"]; ?>" title="<?php echo $pgv_lang["open_nav"]; ?>" name="family_'<?php echo $link; ?>'" onclick="javascript:openFamNav('<?php echo $link; ?>');" /></a></td>
			<?php
		} elseif ($record->getType()=='FAM') {
			?>
			<td align="center"><a href="#"><img style="border-style:none; margin-top:5px;" src="<?php echo $PGV_IMAGE_DIR;?>/buttons/family.gif" alt="<?php echo $pgv_lang["open_nav"]; ?>" title="<?php echo $pgv_lang["open_nav"]; ?>" name="family_'<?php echo $link; ?>'" onclick="javascript:openFamNav('<?php echo $head; ?>');" /></a></td>
			<?php
		} else { 
			echo '<td></td>';
		}
		echo '</tr>';
		$i= $i+1;
	}
	
	echo "</table>";
	echo "</td></tr></table>";
	echo "<br />";
?>

</body>
</html>



