<?php
/**
 * Census Assistant  module for phpGedView
 *
 * Census Proposed Text Area Info File
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
 ?>

<script>
var rowtimes=10;
var txtcolor = "#FFFFFF";

	place=document.getElementById("head");
	
		// creates a <table> element and a <tbody> element
		var tbl = document.createElement("table");
			tbl.setAttribute('cellspacing', '1');
			tbl.setAttribute('width', '100%');
		var tblBody = document.createElement("tbody");
			
		//Table Header
		var rowh = document.createElement("tr");
			for (var i = 1; i <=7; i++) {
				var cellh = document.createElement("td");
					var txtInp = document.createElement('input');
						txtInp.setAttribute('class', 'optionbox');
						txtInp.setAttribute('className', 'optionbox');
						
						txtInp.setAttribute('type', 'text');
						txtInp.style.fontSize="10px";
						txtInp.style.fontWeight="bold";
						txtInp.style.backgroundColor="transparent";
						txtInp.style.border="0";
				cellh.appendChild(txtInp)
			rowh.appendChild(cellh);
			if (i==1) { txtInp.setAttribute('value', 'Name:'); txtInp.setAttribute('size', '5');}
			if (i==2) { txtInp.setAttribute('value', 'Relation:'); txtInp.setAttribute('size', '5');}
			if (i==3) { txtInp.setAttribute('value', 'Gend:'); txtInp.setAttribute('size', '3');}
			if (i==4) { txtInp.setAttribute('value', 'Cond:'); txtInp.setAttribute('size', '3');}
			if (i==5) { txtInp.setAttribute('value', 'Age:'); txtInp.setAttribute('size', '3');}
			if (i==6) { txtInp.setAttribute('value', 'Occupation:'); txtInp.setAttribute('size', '8');}
			if (i==7) { txtInp.setAttribute('value', 'Birthplace:'); txtInp.setAttribute('size', '8');}
			}
		tblBody.appendChild(rowh);
			
		// create rows
		for (var j = 1; j <=rowtimes; j++) {
			var row = document.createElement("tr");
			
			// create cells
			for (var i = 1; i <=10; i++) {
				
				// Create a <td> element and a text node, make the text
				// node the contents of the <td>, and put the <td> at
				// the end of the table row
				var cell = document.createElement("td");
				var txtInp = document.createElement('input');
					txtInp.setAttribute('class', 'news_date');
					txtInp.setAttribute('className', 'news_date');
					txtInp.setAttribute('type', 'text');
					txtInp.setAttribute('id', 'OutputCell_'+j+'_'+i);
					// txtInp.id = 'OutputCell_'+j+'_'+i;
					txtInp.setAttribute('value', ''); // iteration included for debug purposes
					// txtInp.style.color=txtcolor;
					txtInp.style.fontSize="10px";
					txtInp.style.backgroundColor="transparent";
					txtInp.style.border="0";
				
				//if (i==1)  {txtInp.setAttribute('size', '5');  row.appendChild(cell);cell.appendChild(txtInp);}
				if (i==2)  {txtInp.setAttribute('size', '27'); row.appendChild(cell); cell.appendChild(txtInp);}	// Name
				if (i==3)  {txtInp.setAttribute('size', '15'); row.appendChild(cell); cell.appendChild(txtInp);}	//Relation
				if (i==4)  {txtInp.setAttribute('size', '3'); row.appendChild(cell); cell.appendChild(txtInp);}		// Gend
				if (i==5)  {txtInp.setAttribute('size', '3'); row.appendChild(cell); cell.appendChild(txtInp);}		//Cond
				//if (i==6)  {txtInp.setAttribute('size', '4'); row.appendChild(cell); cell.appendChild(txtInp);}		YOB
				if (i==7)  {txtInp.setAttribute('size', '3'); row.appendChild(cell); cell.appendChild(txtInp);}		// Age
				// if (i==8)  {txtInp.setAttribute('size', '1'); row.appendChild(cell); cell.appendChild(txtInp);}		// YMD
				if (i==9)  {txtInp.setAttribute('size', '22'); row.appendChild(cell); cell.appendChild(txtInp);}	// Occu
				if (i==10) {txtInp.setAttribute('size', '50'); row.appendChild(cell); cell.appendChild(txtInp);}	//BirthPl
			}
			
			// add the row to the end of the table body
			tblBody.appendChild(row);
			
		} // end of FOR number of rows var j
		
		// put the <tbody> in the <table>
		tbl.appendChild(tblBody);
		
	// puts the table in the id neamed "place"
	place.appendChild(tbl); 
	
		
		
</script>



