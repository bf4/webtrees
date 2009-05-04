<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census Proposed Text Area File
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

global $theme_name; 

?>
<script>
function openInNewWindow(frm)
{
	// open a blank window
	var aWindow = window.open('', 'TableAddRow2NewWindow',
	'scrollbars=yes,menubar=yes,resizable=yes,location=no,toolbar=no,width=400,height=700');
	aWindow.focus();
	
	// set the target to the blank window
	frm.target = 'TableAddRow2NewWindow';
	
	// submit
	frm.submit();
}
function help_window2(frm)
{
	// open a blank window
	var aWindow = window.open('modules/GEDFact_assistant/tableaddrow_nw.html', 'TableAddRow2NewWindow',
	'scrollbars=yes,menubar=yes,resizable=yes,location=no,toolbar=no,width=400,height=700');
	aWindow.focus();
	
	// set the target to the blank window
	frm.target = 'TableAddRow2NewWindow';
	
	// submit
	//frm.submit();
}
function help_window() {
var win02 = window.open(
"modules/GEDFact_assistant/tableaddrow_nw.html", "win02", "resizable=1, menubar=0, scrollbars=1, top=20, HEIGHT=840, WIDTH=450 ");
if (window.focus) {win02.focus();}
}

function preview(){
	var NoteTitl = document.getElementById('Titl');
		str = "";
		//str = NoteTitl.value;
		//str += "\n";
	var tbl = document.getElementById('tblSample');
	for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
		var tr = tbl.rows[i];
		var strRow = '';
		for(var j=1; j<tr.cells.length; j++){
			if (j==4) {
				//	dont show col	0	index
				//	miss out col	4	delete button
				continue;
			}else{
				strRow += (strRow==''?'':',') + tr.cells[j].childNodes[0].value;
			}
		}
		str += (str==''?'':'\n') + strRow;
	}
	var mem = document.getElementById('NOTE');
	mem.value = str;
}

</script>

			<!--   ---- The proposed Text -------- -->
			<table class="facts_table" width="100%" border=3>
				<tr>
					<?php
					echo "<td colspan=4 class=\"optionbox wrap\" ><center><textarea name=\"NOTE\" id=\"NOTE\" rows=\"8\" cols=\"68\"></textarea></center>";
						//print_specialchar_link("NOTE",true);
					echo "</td>";
					?>
				</tr>
			</table>
			
