<?php
/**
 * Media Assistant Control module for phpGedView
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

?>

<script>
function parseAddLinks() {
	str = "";
	var tbl = document.getElementById('tblSample');
	for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
		var tr = tbl.rows[i];
		var strRow = ''; 
		for(var j=1; j<tr.cells.length; j++){ // Start at col 1 (j=1)
			if (j>=2) {
				//	dont show col	0	index
				//	miss out col	2	name
				//	miss out col	3	relationship
				//	miss out col	4	delete button
				continue;
			}else{
				strRow += (strRow==''?'':'') + tr.cells[j].childNodes[0].value;
			}
		}
		str += (str==''?'':', ') + strRow;
	}
	// str += (str==''?'':'" '); // Adds just final single quote at end of string (\')
}

function preview() {
	parseAddLinks();
	alert (str);
}

function shiftlinks() {
//	alert('Clicking \'Set Links\' will eventually parse and save the Current and Added Links. But for now will just use Base Indi Id');
	parseAddLinks();
//	alert('string = '+ str);
	if (str) {
		document.link.more_links.value = str;
	}else{
		// leave hidden input morelinks as "No Values"
	}
}


</script>