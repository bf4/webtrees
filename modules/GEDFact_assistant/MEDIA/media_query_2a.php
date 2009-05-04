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
		if (document.getElementById('linktoid').value == "") {
			alert('You MUST enter a Base individual ID to add Individual links');
		}else{
			addmedia_links(document.getElementById('INDI_NOTE'), document.getElementById('linktoid').value );
			return false;
		}
	}

	function passback(Values) {
		var memo = document.getElementById('newindis');
		memo.value = Values;
	}
</script>

</head>

<table width="220" border="0" cellpadding="1" cellspacing="2" class="table1">
<tr>
<td width="90" >
<?php
	$text = $pgv_lang["create_shared_note_assisted"];
	echo "<input type=\"button\" name=\"Button\" value=\"Add\" onClick=\"javascript:addlinks(); return false;\">";
	echo "<br /><br >";
	echo "<center><textarea name=\"newindis\" id=\"newindies\" rows=\"8\" cols=\"68\"></textarea></center>"
	?>

</td>
</tr>
</table>

</body>
</html>

