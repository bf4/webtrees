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
"modules/GEDFact_assistant/MEDIA/help.php", "win02", "resizable=1, menubar=0, scrollbars=1, left=70 top=20, HEIGHT=500, WIDTH=450 ");
if (window.focus) {win02.focus();}
}

function parseInput() {
	var NoteTitl = document.getElementById('Titl');
		str = "";
		//str = NoteTitl.value;
		//str += "\n";
	var tbl = document.getElementById('tblSample');
	for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
		var tr = tbl.rows[i];
		var strRow = '';
		for(var j=1; j<tr.cells.length; j++){
			if (j==4 || j==3) {
				//	dont show col	0	index
				//	miss out col	4	delete button
				//	miss out col	3	relationship
				continue;
			}else{
				strRow += (strRow==''?'':',') + tr.cells[j].childNodes[0].value;
			}
		}
		str += (str==''?'':'|') + strRow;
	}
}

function preview(){
	parseInput();
	alert (str);
}

function save(){
	parseInput();
	var myparent = window.opener;
	myparent.passback(str);
	window.close();
}

</script>

			<!--   ---- Save Preview Area -------- -->
			<table class="facts_table" width="60%" border=0>
				<tr>
					<td align="center" class="descriptionbox" colspan="1">
						<input type="button" value="<?php echo $pgv_lang["page_help"]; ?>" onclick="javascript: help_window(this.form);" />
					</td>
<!-- 
					<td align="center" class="descriptionbox" colspan="2">
						<font size="1">
						<input type="button" value="<?php // echo $pgv_lang["preview"]; ?>" onclick="preview();" />
						</font>
						<b> The Proposed Media Links </b>&nbsp;&nbsp;
					</td>

					<td align="center" class="descriptionbox" colspan="1">
						<input type="button" value="<?php echo $pgv_lang['save']; ?>" onclick="save();" />
					</td>
-->
				</tr>
			</table>
			
