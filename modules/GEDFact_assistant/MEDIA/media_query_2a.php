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
color: #000000;
font-weight: bold;
}
.row1 {
background-color: #D8D8D8;
}
.row2 {
background-color: transparent;
/*
width: 5px; 
*/
height: 0px;

}
-->
</style>

<script language="javascript">
	function addlinks() {
		if (document.getElementById('linktopid').value == "") {
			alert('You MUST enter a Base individual ID to add Individual links');
		}else{
			addmedia_links(document.getElementById('INDI_NOTE'), document.getElementById('linktopid').value );
			return false;
		}
	}

	function passback(values) {
		// create an array of passback values
		values = values.split("|");
		for (var i = 0; i < values.length; i++) {
			var tmp = values[i].split(",");
			values[i] = new Array();
			for (var j = 0; j < tmp.length; j++) {
				values[i][j] = tmp[j];
			}
			alert(values[i][0]+" - "+values[i][1] );
		}
		
		var td0_0 = document.getElementById('td0_0'); // Row 0, Cell 0
		var td0_1 = document.getElementById('td0_1'); // Row 0, Cell 1
		var td1_0 = document.getElementById('td1_0'); // Row 1, Cell 0
		var td1_1 = document.getElementById('td1_1'); // Row 1, Cell 1
		var td2_0 = document.getElementById('td2_0'); // Row 2, Cell 0
		var td2_1 = document.getElementById('td2_1'); // Row 2, Cell 1

		td0_0.value = values[0][0]; // Row 0, Cell 0 - Value (contents)
		td0_1.value = values[0][1]; // Row 0, Cell 1 - Value (contents)
		td1_0.value = values[1][0]; // Row 1, Cell 0 - Value (contents)
		td1_1.value = values[1][1]; // Row 1, Cell 1 - Value (contents)
		td2_0.value = values[2][0]; // Row 2, Cell 0 - Value (contents)
		td2_1.value = values[2][1]; // Row 2, Cell 1 - Value (contents)
	}
</script>

</head>

<table border="0" cellpadding="1" cellspacing="2" ">
<tr>
<td width="350" class="row2">
<?php
	echo "B)&nbsp;&nbsp";
	echo "<input type=\"button\" name=\"Button\" value=\"Add\" onClick=\"javascript:addlinks(); return false;\">";
	echo "&nbsp;&nbsp;Then, click Add to add more Individual Links";
	echo "<br /><br >";
//	echo "<center><textarea name=\"newindis\" id=\"newindis\" rows=\"8\" cols=\"68\"></textarea></center>"
	?>
</td>
</tr>
<tr>
<td>
<?php
	echo "<table cellpadding=\"0\" cellspacing=\"1\" class=\"table1\" border=0>";
	echo "<tr>";
	echo "<td width=\"10\" align=\"left\" bgcolor=\"#AAAAAA\"><span class=\"style1\">#&nbsp;&nbsp;&nbsp;</span></td>";
	echo "<td width=\"5\" align=\"left\" bgcolor=\"#AAAAAA\"><span class=\"style1\">Id</span></td>";
	echo "<td wrap=\"nowrap\" width=\"100\" align=\"left\" bgcolor=\"#AAAAAA\"><span class=\"style1\">Name</span></td>";
	echo "<td width=\"25\" align=\"center\" bgcolor=\"#AAAAAA\" nowrap='nowrap'><span class=\"style1\">&nbsp;Link&nbsp;</span></td>";
	echo "<td width=\"25\" align=\"center\" bgcolor=\"#AAAAAA\" nowrap='nowrap'><span class=\"style1\">&nbsp;No&nbsp;Link</span></td>";
	echo "</tr>";

	for ($i=0; $i<=2; $i++) {
		echo "<tr><td>";
		echo $i+1;
		echo "</td><td>";
		echo "<input style=\" font-family: Verdana, Arial, Helvetica, sans-serif; background:transparent; border:0px; font-size:10px;\" size=\"2\" name=\"td".$i."_0\" id=\"td".$i."a\" />";
		echo "</td><td>";
		echo "<input style=\" font-family: Verdana, Arial, Helvetica, sans-serif; background:transparent; border:0px; font-size:10px;\" size=\"40\" name=\"td".$i."_1\" id=\"td".$i."\" />";
		echo "</td>";
		echo "<td class=\"row2\"><input type='radio' name='rad2_".$i."' checked /></td>";
		echo "<td class=\"row2\" align='center'><input type='radio' name='rad2_".$i."' /></td>";
		echo "</tr>";
	}

	echo "</table>";
?>
</td>
</tr>
</table>

</body>
</html>

