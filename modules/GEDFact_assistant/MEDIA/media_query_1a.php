<?php
/**
* Code for Extracting Shared Note Indi Links for GEDFact_assistant
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
	echo "<table width=\"390\" cellspacing=\"1\" >";
	echo "<tr>";
	echo '<td class="topbottombar" width="20"  style="text-align:left;font-weight:100;" >#</td>';
	echo '<td class="topbottombar" width="40"  style="text-align:left;font-weight:100;" >ID:</td>';
	echo '<td class="topbottombar" width="280" style="text-align:left;font-weight:100;" >Name</td>';
	echo '<td class="topbottombar" width="20"  style="text-align:left;font-weight:100;" >&nbspKeep&nbsp;</td>';
	echo '<td class="topbottombar" width="20"  style="text-align:left;font-weight:100;" >&nbsp;Unlink&nbsp;</td>';
	echo "</tr>";

	$keys = array_keys($links);
	$values = array_values($links);
	$i=1;
	foreach ($keys as $link) {
		echo "<tr ><td class=\"row2\"><font size='2'>";
		echo $i;
		echo "</td><td class=\"row2\"><font size='2'>";
			echo $link;
		echo "</td><td class=\"row2\" ><font size='2'>";
		if ($link[0]=="I") {
			$person=Person::getInstance($link);
		} elseif ($link[0]=="F") {
			$person=Family::getInstance($link);
		} else {
			$person=Source::getInstance($link);
		}
		$nam = $person->getFullName();
		echo $nam;
		echo "</td>";
		echo "<td class=\"row2\" align='center'><input type='radio' name='rad_".$link."' checked /></td>";
		echo "<td class=\"row2\" align='center'><input type='radio' name='rad_".$link."' /></td>";
		echo "</tr>";
		$i= $i+1;
	}
	
	echo "</table>";
	echo "</td></tr></table>";
	echo "<br />";
?>

</body>
</html>



