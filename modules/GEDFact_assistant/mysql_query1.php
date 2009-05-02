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
<script language="JavaScript">
<!--
//these function from:
//http://www.devshed.com/c/a/PHP/PHP-and-JavaScript-Pooling-Your-Resources/
//this function calls the child file
function attach_file(p_script_url) {
	// create new script element, set its relative URL, and load it
	script = document.createElement('script');
	script.src = p_script_url;
	document.getElementsByTagName('head')[0].appendChild( script );
}

//this function updates the status box
function show_status(status_text) {
	document.getElementById('status').innerHTML = status_text;
}
//-->
</script>

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
background-color: #FFFFFF;
}
-->
</style>
</head>
<body onLoad="javascript:attach_file('modules/GEDFact_assistant/mysql_query2.php?noteid=N1'); show_status('OK'); ">

<?php
// Test of PDO TABLE ========================================================
/*
	$statement=PGV_DB::prepare("SELECT 'INDI' AS type, l_from, l_to FROM {$TBLPREFIX}link, {$TBLPREFIX}individuals WHERE l_from LIKE 'I%' AND i_file=l_file AND i_id=l_from AND l_file='1' AND l_type='NOTE' AND l_to='N1'")->execute();
	echo '<table width="220" border="0" cellpadding="1" cellspacing="3" class="table1">';
	while ($rows = $statement->fetch(PDO::FETCH_NUM)) {
		echo "<tr><td> $rows[0]</td><td> $rows[1]</td><td> $rows[2]</td></tr>";
	}
	echo "</table>";
	$statement->closeCursor();
*/
// ==========================================================================
?>

<table width="220" border="0" cellpadding="1" cellspacing="3" class="table1">
<tr>
<td>
<?php
$refresh  = "<input class=\"button\" type=\"button\" name=\"Button\" value=\"Refresh\" ";
$refresh .= "onClick=\"javascript:attach_file('modules/GEDFact_assistant/mysql_query2.php";
$refresh .= "?noteid='+INDI_NOTE.value+'";			// 
$refresh .= "&tblprefix=$TBLPREFIX";				//
$refresh .= "&dbtype=$DBTYPE";						//  
$refresh .= "&dbhost=$DBHOST";						// 
$refresh .= "&dbuser=$DBUSER";						// 
$refresh .= "&dbpass=$DBPASS";						// 
$refresh .= "&dbname=$DBNAME";						// 
$refresh .= "'); show_status('Busy...'); \" >";
echo $refresh;
?>
</td>
<td width="120" nowrap="nowrap"><span><b>&nbsp;&nbsp; Linked to: </b></span> </td>
<td width="60" ><span id="status" /></span> </td>
</tr>
</table>
<p />

<!-- Do not remove this or the table will not show up -->
<span id="from_mysql"></span>
<br />

</body>
</html>

