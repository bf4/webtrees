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

require './config.php';

?>
<html>
<head>

<!-- CSS Details -->
<style type="text/css">
<!--
.table1 {
border: 0px solid #CC6600;
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 10px;
font-style: normal;
text-transform: none;
}
.style1 {
color: #0000FF;
font-weight: bold;
}
.row1 {
background-color: #D8D8D8;
}
/*
.row2 {
background-color: #FF0000;
}
*/
-->
</style>

</head>
<body>

<?php
	$links = get_media_relations($mediaid);
	// var_dump($links);
	echo "<table width=\"350\" cellpadding=\"0\" cellspacing=\"1\" class=\"table1\">";
	echo "<tr>";
	echo "<td width=\"20\" align=\"left\" bgcolor=\"#AAAAAA\"><span class=\"style1\">#</span></td>";
	echo "<td width=\"30\" align=\"left\" bgcolor=\"#AAAAAA\"><span class=\"style1\">Id</span></td>";
	echo "<td wrap=\"nowrap\" width=\"150\" align=\"left\" bgcolor=\"#AAAAAA\"><span class=\"style1\">Name</span></td>";
	echo "<td width=\"25\" align=\"center\" bgcolor=\"#AAAAAA\" nowrap='nowrap'><span class=\"style1\">&nbsp;Link&nbsp;</span></td>";
	echo "<td width=\"25\" align=\"center\" bgcolor=\"#AAAAAA\" nowrap='nowrap'><span class=\"style1\">&nbsp;&nbsp;Unlink&nbsp;</span></td>";
	echo "</tr>";

	$keys = array_keys($links);
	$values = array_values($links);
	$i=1;
	foreach ($keys as $link) {
		echo "<tr ><td >";
		echo $i;
		echo "</td><td class=\"row2\">";
			echo $link;
		echo "</td><td class=\"row2\" >";
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
		echo "<td class=\"row2\"><input type='radio' name='rad_".$link."' checked /></td>";
		echo "<td class=\"row2\" align='center'><input type='radio' name='rad_".$link."' /></td>";
		echo "</tr>";
		$i= $i+1;
	}
	
	echo "</table>";
	echo "<br />";
?>

</body>
</html>

