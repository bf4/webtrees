/**
 * Census Assistant Control module for phpGedView
 *
 * Census information about an individual
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Census Assistant
 * @version $Id$
 */

// tabledeleterow.js version 1.2 2006-02-21
// mredkj.com

// CONFIG notes. Below are some comments that point to where this script can be customized.
// Note: Make sure to include a <tbody></tbody> in your table's HTML

var INPUT_NAME_PREFIX = 'InputCell_'; // this is being set via script
var RADIO_NAME = "totallyrad"; // this is being set via script
var TABLE_NAME = 'tblSample'; // this should be named in the HTML
var ROW_BASE = 1; // first number (for display)
var hasLoaded = false;

window.onload=fillInRows;

function fillInRows()
{
	hasLoaded = true;
	//insertRowToTable();
	//addRowToTable();
}

// CONFIG:
// myRowObject is an object for storing information about the table rows
function myRowObject(zero, one, two, three, four, five, six, seven, eight, nine, ten, cb, ra)
{
	this.zero	 = zero;	 // text object
	this.one	 = one;		 // input text object
	this.two	 = two;		 // input text object
	this.three	 = three;	 // input text object
	this.four	 = four;	 // input text object
	this.five	 = five;	 // input text object
	this.six	 = six;		 // input text object
	this.seven	 = seven;	 // input text object
	this.eight	 = eight;	 // input text object
	this.nine	 = nine;	 // input text object
	this.ten	 = ten;		 // input text object
	this.cb		 = cb;		 // input checkbox object
	this.ra		 = ra;		 // input radio object
}

/*
 * insertRowToTable
 * Insert and reorder
 */
function insertRowToTable(pid, nam, label, gend, cond, yob, age, YMD, occu, birthpl)
{
	if (hasLoaded) {
		var tbl = document.getElementById(TABLE_NAME);
		var rowToInsertAt = tbl.tBodies[0].rows.length;
		for (var i=0; i<tbl.tBodies[0].rows.length; i++) {
			if (tbl.tBodies[0].rows[i].myRow && tbl.tBodies[0].rows[i].myRow.ra.getAttribute('type') == 'radio' && tbl.tBodies[0].rows[i].myRow.ra.checked) {
				rowToInsertAt = i;
				break;
			}
		}
		//addRowToTable(rowToInsertAt, nam, label);
		addRowToTable(rowToInsertAt, pid, nam, label, gend, cond, yob, age, YMD, occu, birthpl);
		reorderRows(tbl, rowToInsertAt);
	}
}

/*
 * addRowToTable
 * Inserts at row 'num', or appends to the end if no arguments are passed in. Don't pass in empty strings.
 */
function addRowToTable(num, pid, nam, label, gend, cond, yob, age, YMD, occu, birthpl)
{
	if (hasLoaded) {
		var tbl = document.getElementById(TABLE_NAME);
		var nextRow = tbl.tBodies[0].rows.length;
		var iteration = nextRow + ROW_BASE;
		if (num == null) { 
			num = nextRow;
		} else {
			iteration = num + ROW_BASE;
		}
		
		// add the row
		var row = tbl.tBodies[0].insertRow(num);
		
		// CONFIG: requires classes named classy0 and classy1
		row.className = 'classy' + (iteration % 2);
		// row.className = 'descriptionbox';
		
		// CONFIG: This whole section can be configured
		
		// cell 0 - text
		var cell0 = row.insertCell(0);
		var textNode = document.createTextNode(iteration);
		cell0.appendChild(textNode);
		
		// cell 1 - input text
		var cell1 = row.insertCell(1);
			cell1.setAttribute('align', 'left');
		if ( pid == ''){
			var txtcolor = "#000000";
			var txtInp1 = document.createElement('input');
			txtInp1.setAttribute('type', 'checkbox');
			//txtInp1.checked='';
			if (txtInp1.checked!=''){
				txtInp1.setAttribute('value', 'no');
			}else{
				txtInp1.setAttribute('value', 'add');
			}
		}else{
			var txtcolor = "#0000FF";
			var txtInp1 = document.createElement('input');
			txtInp1.setAttribute('type', 'text');
				txtInp1.setAttribute('value', pid);
		}
			txtInp1.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_1');
			txtInp1.setAttribute('size', '4');
			txtInp1.style.color=txtcolor;
			txtInp1.style.fontSize="10px";
		cell1.appendChild(txtInp1);
		
		
		// cell 2 - input text
		var cell2 = row.insertCell(2);
			cell2.setAttribute('align', 'left');
		var txtInp2 = document.createElement('input');
			txtInp2.setAttribute('type', 'text');
			txtInp2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_2');
			txtInp2.setAttribute('size', '30');
			txtInp2.setAttribute('value', nam); // iteration included for debug purposes
			txtInp2.style.color=txtcolor;
			txtInp2.style.fontSize="10px";
		cell2.appendChild(txtInp2);
		
		// cell 3 - input text
		var cell3 = row.insertCell(3);
			cell3.setAttribute('align', 'left');
		var txtInp3 = document.createElement('input');
			txtInp3.setAttribute('type', 'text');
			txtInp3.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_3');
			txtInp3.setAttribute('size', '15');
			txtInp3.setAttribute('value', label); // iteration included for debug purposes
			txtInp3.style.color=txtcolor;
			txtInp3.style.fontSize="10px";
		cell3.appendChild(txtInp3);
		
		// cell 4 - input text
		var cell4 = row.insertCell(4);
			cell4.setAttribute('align', 'left');
		var txtInp4 = document.createElement('input');
			txtInp4.setAttribute('type', 'text');
			txtInp4.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_4');
			txtInp4.setAttribute('size', '1');
			txtInp4.setAttribute('value', cond); // iteration included for debug purposes
			txtInp4.style.color=txtcolor;
			txtInp4.style.fontSize="10px";
		cell4.appendChild(txtInp4);
		
		// cell 5 - input text
		var cell5 = row.insertCell(5);
			cell5.setAttribute('align', 'left');
		var txtInp5 = document.createElement('input');
			txtInp5.setAttribute('type', 'text');
			txtInp5.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_5');
			txtInp5.setAttribute('size', '2');
			txtInp5.setAttribute('value', yob); // iteration included for debug purposes
			txtInp5.style.color=txtcolor;
			txtInp5.style.fontSize="10px";
		cell5.appendChild(txtInp5);
		
		// cell 6 - input text
		var cell6 = row.insertCell(6);
			cell6.setAttribute('align', 'left');
		var txtInp6 = document.createElement('input');
			txtInp6.setAttribute('type', 'text');
			txtInp6.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_6');
			txtInp6.setAttribute('size', '2');
			txtInp6.setAttribute('value', age); // iteration included for debug purposes
			txtInp6.style.color=txtcolor;
			txtInp6.style.fontSize="10px";
		cell6.appendChild(txtInp6);
		
		// cell 7 - input text
		var cell7 = row.insertCell(7);
			cell7.setAttribute('align', 'left');
		var txtInp7 = document.createElement('input');
			txtInp7.setAttribute('type', 'text');
			txtInp7.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_7');
			txtInp7.setAttribute('size', '1');
			txtInp7.setAttribute('value', YMD); // iteration included for debug purposes
			txtInp7.style.color=txtcolor;
			txtInp7.style.fontSize="10px";
		cell7.appendChild(txtInp7);
		
		// cell 8 - input text
		var cell8 = row.insertCell(8);
			cell8.setAttribute('align', 'left');
		var txtInp8 = document.createElement('input');
			txtInp8.setAttribute('type', 'text');
			txtInp8.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_8');
			txtInp8.setAttribute('size', '1');
			txtInp8.setAttribute('value', gend); // iteration included for debug purposes
			txtInp8.style.color=txtcolor;
			txtInp8.style.fontSize="10px";
		cell8.appendChild(txtInp8);
		
		// cell 9 - input text
		var cell9 = row.insertCell(9);
			cell9.setAttribute('align', 'left');
		var txtInp9 = document.createElement('input');
			txtInp9.setAttribute('type', 'text');
			txtInp9.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_9');
			txtInp9.setAttribute('size', '22');
			txtInp9.setAttribute('value', occu); // iteration included for debug purposes
			txtInp9.style.color=txtcolor;
			txtInp9.style.fontSize="10px";
		cell9.appendChild(txtInp9);

		// cell 10 - input text
		var cell10 = row.insertCell(10);
			cell10.setAttribute('align', 'left');
		var txtInp10 = document.createElement('input');
			txtInp10.setAttribute('type', 'text');
			txtInp10.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_10');
			txtInp10.setAttribute('size', '55');
			txtInp10.setAttribute('value', birthpl); // iteration included for debug purposes
			txtInp10.style.color=txtcolor;
			txtInp10.style.fontSize="10px";
		cell10.appendChild(txtInp10);
		
		// cell btn - input button
		var cellbtn = row.insertCell(11);
		var btnEl = document.createElement('input');
			btnEl.setAttribute('type', 'button');
			btnEl.setAttribute('value', 'x');
			btnEl.onclick = function () {deleteCurrentRow(this)};
		cellbtn.appendChild(btnEl);
		
		
		// cell cb - input checkbox
		var cbEl = document.createElement('input');
		cbEl.type = "hidden";
		
		// cell ra - input radio
		var cellra = row.insertCell(12);
			cellra.setAttribute('valign', 'top');
		var raEl;
		try {
			raEl = document.createElement('<input type="radio" name="' + RADIO_NAME + '" value="' + iteration + '">');
			var failIfNotIE = raEl.name.length;
		} catch(ex) {
			raEl = document.createElement('input');
			raEl.setAttribute('type', 'radio');
			raEl.setAttribute('name', RADIO_NAME );
			raEl.setAttribute('value', iteration);
		}
		cellra.appendChild(raEl);
		
		
		// Pass in the elements you want to reference later
		// Store the myRow object in each row
		row.myRow = new myRowObject(textNode, txtInp1, txtInp2, txtInp3, txtInp4, txtInp5, txtInp6, txtInp7, txtInp8, txtInp9, txtInp10, cbEl, raEl);
	}
}

// CONFIG: this entire function is affected by myRowObject settings
// If there isn't a checkbox in your row, then this function can't be used.
function deleteChecked()
{
	if (hasLoaded) {
		var checkedObjArray = new Array();
		var cCount = 0;
	
		var tbl = document.getElementById(TABLE_NAME);
		for (var i=0; i<tbl.tBodies[0].rows.length; i++) {
			if (tbl.tBodies[0].rows[i].myRow && tbl.tBodies[0].rows[i].myRow.cb.getAttribute('type') == 'checkbox' && tbl.tBodies[0].rows[i].myRow.cb.checked) {
				checkedObjArray[cCount] = tbl.tBodies[0].rows[i];
				cCount++;
			}
		}
		if (checkedObjArray.length > 0) {
			var rIndex = checkedObjArray[0].sectionRowIndex;
			deleteRows(checkedObjArray);
			reorderRows(tbl, rIndex);
		}
	}
}

// If there isn't an element with an onclick event in your row, then this function can't be used.
function deleteCurrentRow(obj)
{
	if (hasLoaded) {
		var delRow = obj.parentNode.parentNode;
		var tbl = delRow.parentNode.parentNode;
		var rIndex = delRow.sectionRowIndex;
		var rowArray = new Array(delRow);
		deleteRows(rowArray);
		reorderRows(tbl, rIndex);
	}
}

function reorderRows(tbl, startingIndex)
{
	if (hasLoaded) {
		if (tbl.tBodies[0].rows[startingIndex]) {
			var count = startingIndex + ROW_BASE;
			for (var i=startingIndex; i<tbl.tBodies[0].rows.length; i++) {
			
				// CONFIG: next line is affected by myRowObject settings
				tbl.tBodies[0].rows[i].myRow.zero.data	 = count; // text
				
				tbl.tBodies[0].rows[i].myRow.one.id		 = INPUT_NAME_PREFIX + count + '_1'; // input text
				tbl.tBodies[0].rows[i].myRow.two.id 	 = INPUT_NAME_PREFIX + count + '_2'; // input text
				tbl.tBodies[0].rows[i].myRow.three.id	 = INPUT_NAME_PREFIX + count + '_3';  // input text
				tbl.tBodies[0].rows[i].myRow.four.id	 = INPUT_NAME_PREFIX + count + '_4';  // input text
				tbl.tBodies[0].rows[i].myRow.five.id	 = INPUT_NAME_PREFIX + count + '_5';  // input text
				tbl.tBodies[0].rows[i].myRow.six.id		 = INPUT_NAME_PREFIX + count + '_6';  // input text
				tbl.tBodies[0].rows[i].myRow.seven.id	 = INPUT_NAME_PREFIX + count + '_7';  // input text
				tbl.tBodies[0].rows[i].myRow.eight.id	 = INPUT_NAME_PREFIX + count + '_8';  // input text
				tbl.tBodies[0].rows[i].myRow.nine.id	 = INPUT_NAME_PREFIX + count + '_9';  // input text
				tbl.tBodies[0].rows[i].myRow.ten.id		 = INPUT_NAME_PREFIX + count + '_10'; // input text
				
				tbl.tBodies[0].rows[i].myRow.one.name	 = INPUT_NAME_PREFIX + count + '_1'; // input text
				tbl.tBodies[0].rows[i].myRow.two.name 	 = INPUT_NAME_PREFIX + count + '_2'; // input text
				tbl.tBodies[0].rows[i].myRow.three.name	 = INPUT_NAME_PREFIX + count + '_3';  // input text
				tbl.tBodies[0].rows[i].myRow.four.name	 = INPUT_NAME_PREFIX + count + '_4';  // input text
				tbl.tBodies[0].rows[i].myRow.five.name	 = INPUT_NAME_PREFIX + count + '_5';  // input text
				tbl.tBodies[0].rows[i].myRow.six.name	 = INPUT_NAME_PREFIX + count + '_6';  // input text
				tbl.tBodies[0].rows[i].myRow.seven.name	 = INPUT_NAME_PREFIX + count + '_7';  // input text
				tbl.tBodies[0].rows[i].myRow.eight.name	 = INPUT_NAME_PREFIX + count + '_8';  // input text
				tbl.tBodies[0].rows[i].myRow.nine.name	 = INPUT_NAME_PREFIX + count + '_9';  // input text
				tbl.tBodies[0].rows[i].myRow.ten.name	 = INPUT_NAME_PREFIX + count + '_10'; // input text
				
				// tbl.tBodies[0].rows[i].myRow.cb.value = count; // input checkbox
				tbl.tBodies[0].rows[i].myRow.ra.value = count; // input radio
				
				// CONFIG: requires class named classy0 and classy1
				tbl.tBodies[0].rows[i].className = 'classy' + (count % 2);
				
				count++;
			}
		}
	}
}

function deleteRows(rowObjArray)
{
	if (hasLoaded) {
		for (var i=0; i<rowObjArray.length; i++) {
			var rIndex = rowObjArray[i].sectionRowIndex;
			rowObjArray[i].parentNode.deleteRow(rIndex);
		}
	}
}

function openInNewWindow(frm)
{
	// open a blank window
	var aWindow = window.open('', 'TableAddRow2NewWindow',
	'scrollbars=yes,menubar=yes,resizable=yes,location=no,toolbar=no,width=550,height=700');
	aWindow.focus();
	
	// set the target to the blank window
	frm.target = 'TableAddRow2NewWindow';
	
	// submit
	frm.submit();
}
