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


var NoteCtry = document.getElementById('censCtry');
var NoteYear = document.getElementById('censYear');
var NoteTitl = document.getElementById('Titl');


function preview(){
	NoteCtry = document.getElementById('censCtry');
	//if (NoteCtry.value == "USA") {
	//	str = NoteYear.value + " " + NoteCtry.value + " Federal " + NoteTitl.value;
	//	str += "\n";
	//} else {
		str = NoteYear.value + " " + NoteCtry.value + " " + NoteTitl.value;
		str += "\n";
	//}
	
	var tbl = document.getElementById('tblSample');
	for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
		var tr = tbl.rows[i];
		var strRow = '';
		for(var j=2; j<tr.cells.length; j++){
			if (NoteCtry.value=="USA") {
				var cols=12;
			}else{
				var cols=10;
			}
				if (j==5 || j==7 || j>cols) {

				//	dont show col	0	index
				//	dont show col	1	pid
				//	miss out col	5	yob
				//	miss out col	7	YMD
				//	miss out col	11	delete button
				//	miss out col	12	radio buttom
				continue;
			}else{
				if (i==0) {
					strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
				}else{
					strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
				}
			}
		}
		str += (str==''?'':'\n') + strRow;
	}
	var mem = document.getElementById('NOTE');
	mem.value = str;
}

window.onload=fillInRows;

// fillInRows - can be used to pre-load a table with a row or rows
function fillInRows() {
	hasLoaded = true;
//	create_header();
	// insertRowToTable();
	// addRowToTable();
}

// myRowObject - an object for storing information about the table rows
function myRowObject(zero, one, two, three, four, five, six, seven, eight, nine, ten, eleven, twelve, cb, ra, fifteen) {
	this.zero		 = zero;	 // text object
	this.one		 = one;		 // input text object
	this.two		 = two;		 // input text object
	this.three		 = three;	 // input text object
	this.four		 = four;	 // input text object
	this.five		 = five;	 // input text object
	this.six		 = six;		 // input text object
	this.seven		 = seven;	 // input text object
	this.eight		 = eight;	 // input text object
	this.nine		 = nine;	 // input text object
	this.ten		 = ten;		 // input text object
	this.eleven		 = eleven;	 // input text object
	this.twelve		 = twelve;	 // input text object
	this.cb			 = cb;		 // input checkbox object
	this.ra			 = ra;		 // input radio object
	this.fifteen	 = fifteen;	 // input text object
}

function change_header() {
	tbl = document.getElementById(TABLE_NAME);
	NoteCtry = document.getElementById('censCtry');
	if (tbl.tBodies[0].rows.length>0 ) {
		for (var i=1; i<tbl.tBodies[0].rows.length; i++) {
			tbl.tBodies[0].rows[i].myRow.eleven = document.getElementById('cell_'+i+'_11');
			tbl.tBodies[0].rows[i].myRow.twelve = document.getElementById('cell_'+i+'_12');
			// var Test12 = document.getElementById('InputCell_'+i+'_12');
			if (tbl.tBodies[0].rows.length>0 ) {
				if (NoteCtry.value=="USA") {
					tbl.tBodies[0].rows[i].myRow.eleven.style.display="block";
					tbl.tBodies[0].rows[i].myRow.twelve.style.display="block";
				}else{
			tbl.tBodies[0].rows[i].myRow.eleven = document.getElementById('cell_'+i+'_11');
			tbl.tBodies[0].rows[i].myRow.twelve = document.getElementById('cell_'+i+'_12');
					tbl.tBodies[0].rows[i].myRow.eleven.style.display="none";
					tbl.tBodies[0].rows[i].myRow.twelve.style.display="none";
				}
			}
		}
	}
	
	//tbl.tBodies[0].rows[1].myRow.eleven.style.display='none';
	var delrowzero = document.getElementById(this);
	if (delrowzero!=null) {
		deleteHeaderRow(delrowzero);
	}
	create_header();

}

function create_header() {
	NoteCtry = document.getElementById('censCtry');
	if (NoteCtry.value=="USA") {
		addRowToTable("", "ID", "Name", "Relation", "Sex", "Cond", "YOB", "Age", "YMD", "Occupation", "Birth Place", "Fathers BP", "Mothers BP", "Del");
	}else{
		addRowToTable("", "ID", "Name", "Relation", "Sex", "Cond", "YOB", "Age", "YMD", "Occupation", "Birth Place", "Del");
	}
}

// insertRowToTable - inserts a row into the table (and reorders)
function insertRowToTable(pid, nam, label, gend, cond, yob, age, YMD, occu, birthpl, fbirthpl, mbirthpl) {
	if (hasLoaded) {
		var tbl = document.getElementById(TABLE_NAME);
		var rowToInsertAt = tbl.tBodies[0].rows.length;
		for (var i=0; i<tbl.tBodies[0].rows.length; i++) {
			if (tbl.tBodies[0].rows[i].myRow && tbl.tBodies[0].rows[i].myRow.ra.getAttribute('type') == 'radio' && tbl.tBodies[0].rows[i].myRow.ra.checked) {
				rowToInsertAt = i;
				break;
			}
		}
		addRowToTable(rowToInsertAt, pid, nam, label, gend, cond, yob, age, YMD, occu, birthpl, fbirthpl, mbirthpl);
		reorderRows(tbl, rowToInsertAt);
	}
}

// addRowToTable - Inserts at row 'num', or appends to the end if no arguments are passed in. Don't pass in empty strings.
function addRowToTable(num, pid, nam, label, gend, cond, yob, age2, YMD, occu, birthpl, fbirthpl, mbirthpl, cb, ra, num2) {

// -- Temporary until insert variable are corrected
fbirthpl=birthpl;
mbirthpl=birthpl;
// ------------------------------------------------

	if (hasLoaded) {
		
		var tbl = document.getElementById(TABLE_NAME);
		var nextRow = tbl.tBodies[0].rows.length;
		var iteration = nextRow + ROW_BASE;
		if (num == null) { 
			num = nextRow;
			num2 = num;
		} else {
			iteration = num + ROW_BASE;
		}
		
		// Calculate age based on Census Year input ====================
		if (age2!="Age") {
			var cyear_a=document.getElementById('censYear'); 
			var cyear=cyear_a.value;
			// Check if Census year filled in -------------
			if (cyear!="choose") {
				cyear=cyear;
			}else{
				alert ("You must choose a Census year first")
				return;
			}
			var diffage=(1901-cyear);
			age=(age2-diffage);
		}else{
		// age="Age"
		}

		// add the row ==================================================
		var row = tbl.tBodies[0].insertRow(num);
		
		// a. Define Cells ==============================================
		var cell_0 = row.insertCell(0);	
			cell_0.setAttribute('id', 'cell_'+ iteration + '_0');	// Item Number
			cell_0.setAttribute('name', 'cell_'+ iteration + '_0');
		var cell_1 = row.insertCell(1);		
			cell_1.setAttribute('id', 'cell_'+ iteration + '_1');	// Indi ID
			cell_1.setAttribute('name', 'cell_'+ iteration + '_1');
		var cell_2 = row.insertCell(2);		
			cell_2.setAttribute('id', 'cell_'+ iteration + '_2');	// Full Name
			cell_2.setAttribute('name', 'cell_'+ iteration + '_2');
		var cell_3 = row.insertCell(3);		
			cell_3.setAttribute('id', 'cell_'+ iteration + '_3');	// Relationship
			cell_3.setAttribute('name', 'cell_'+ iteration + '_3');
		var cell_4 = row.insertCell(4);		
			cell_4.setAttribute('id', 'cell_'+ iteration + '_4');	// Marital Conditition
			cell_4.setAttribute('name', 'cell_'+ iteration + '_4');
		var cell_5 = row.insertCell(5);		
			cell_5.setAttribute('id', 'cell_'+ iteration + '_5');	// YOB
			cell_5.setAttribute('name', 'cell_'+ iteration + '_5');
		var cell_6 = row.insertCell(6);		
			cell_6.setAttribute('id', 'cell_'+ iteration + '_6');	// Age
			cell_6.setAttribute('name', 'cell_'+ iteration + '_6');
		var cell_7 = row.insertCell(7);		
			cell_7.setAttribute('id', 'cell_'+ iteration + '_7');	// YMD (maybe should be replaced by Age being 10y or 12mo etc)
			cell_7.setAttribute('name', 'cell_'+ iteration + '_7');
		var cell_8 = row.insertCell(8);		
			cell_8.setAttribute('id', 'cell_'+ iteration + '_8');	// Sex
			cell_8.setAttribute('name', 'cell_'+ iteration + '_8');
		var cell_9 = row.insertCell(9);		
			cell_9.setAttribute('id', 'cell_'+ iteration + '_9');	// Occupation
			cell_9.setAttribute('name', 'cell_'+ iteration + '_9');
		var cell_10 = row.insertCell(10);	
			cell_10.setAttribute('id', 'cell_'+ iteration + '_10');	// Indi Birth Place
			cell_10.setAttribute('name', 'cell_'+ iteration + '_10');
		var cell_11 = row.insertCell(11);	
			cell_11.setAttribute('id', 'cell_'+ iteration + '_11');	// Fathers Birth Place
			cell_11.setAttribute('name', 'cell_'+ iteration + '_11');
		var cell_12 = row.insertCell(12);	
			cell_12.setAttribute('id', 'cell_'+ iteration + '_12');	// Mothers Birth Place
			cell_12.setAttribute('name', 'cell_'+ iteration + '_12');
		if (NoteCtry.value=="UK") {
			cell_11.style.display = "none";
			cell_12.style.display = "none";
		}
		if (iteration == 0) {
		//	var cell_tdel = row.insertCell(13);			// text Del
		//	var cell_tra  = row.insertCell(14);			// text Radio
		}else{
			var cell_del = row.insertCell(13);			// Onclick = Delete Row
			var cell_ra = row.insertCell(14);			// Radio button used for inserting a row, rather than adding at end of table)
		}
		var cell_15 = row.insertCell(15);	
			cell_15.setAttribute('id', 'cell_'+ iteration + '_15');	// Item Number
			cell_15.setAttribute('name', 'cell_'+ iteration + '_15');
			cell_15.setAttribute('align', 'center');

		// b. Define Header Cell elements =======================================
//		if (iteration == 0) {
/*
		// Item Number ---------------------------------------------------
			var txt_itemNo = document.createElement('div');
				txt_itemNo.setAttribute('class', 'optionbox');
				txt_itemNo.style.border='0px';
				txt_itemNo.innerHTML = 'Item'; //Required for IE
				txt_itemNo.textContent = 'Item';
				txt_itemNo.setAttribute('id', '.b.Item');
				txt_itemNo.setAttribute('type', 'text');
				txt_itemNo.style.fontSize="10px";
		// Indi ID -------------------------------------------------------
			var txtInp_pid = document.createElement('div');
				txtInp_pid.setAttribute('type', 'text');
				txtInp_pid.setAttribute('class', 'optionbox');
				txtInp_pid.style.fontSize="10px";
				txtInp_pid.style.border='0px';
				txtInp_pid.innerHTML = 'Indi ID'; //Required for IE
				txtInp_pid.textContent = 'Indi ID';
				txtInp_pid.setAttribute('id', '.b.Indi ID');
		// Full Name -----------------------------------------------------
			var txtInp_nam = document.createElement('div');
				txtInp_nam.setAttribute('class', 'optionbox');
				txtInp_nam.setAttribute('type', 'text');
				txtInp_nam.style.fontSize="10px";
				txtInp_nam.style.border='0px';
				txtInp_nam.innerHTML = 'Name'; //Required for IE
				txtInp_nam.textContent = 'Name';
				txtInp_nam.setAttribute('id', '.b.Name');
		// Relationship --------------------------------------------------
			var txtInp_label = document.createElement('div');
				txtInp_label.setAttribute('type', 'text');
				txtInp_label.setAttribute('class', 'optionbox');
				txtInp_label.style.fontSize="10px";
				txtInp_label.style.border='0px';
				txtInp_label.innerHTML = 'Relation'; //Required for IE
				txtInp_label.textContent = 'Relation';
				txtInp_label.setAttribute('id', '.b.Relation');
		// Marital Conditition -------------------------------------------
			var txtInp_cond = document.createElement('div');
				txtInp_cond.setAttribute('type', 'text');
				txtInp_cond.setAttribute('class', 'optionbox');
				txtInp_cond.style.fontSize="10px";
				txtInp_cond.style.border='0px';
				txtInp_cond.innerHTML = 'Cond'; //Required for IE
				txtInp_cond.textContent = 'Cond';
				txtInp_cond.setAttribute('id', '.b.Cond');
		// YOB -----------------------------------------------------------
			var txtInp_yob = document.createElement('div');
				txtInp_yob.setAttribute('type', 'text');
				txtInp_yob.setAttribute('class', 'optionbox');
				txtInp_yob.style.fontSize="10px";
				txtInp_yob.style.border='0px';
				txtInp_yob.innerHTML = 'YOB'; //Required for IE
				txtInp_yob.textContent = 'YOB';
				txtInp_yob.setAttribute('id', '.b.YOB');
		// Age -----------------------------------------------------------
			var txtInp_age = document.createElement('div');
				txtInp_age.setAttribute('type', 'text');
				txtInp_age.setAttribute('class', 'optionbox');
				txtInp_age.style.fontSize="10px";
				txtInp_age.style.border='0px';
				txtInp_age.innerHTML = 'Age'; //Required for IE
				txtInp_age.textContent = 'Age';
				txtInp_age.setAttribute('id', '.b.Age');
		// YMD -----------------------------------------------------------
			var txtInp_YMD = document.createElement('div');
				txtInp_YMD.setAttribute('type', 'text');
				txtInp_YMD.setAttribute('class', 'optionbox');
				txtInp_YMD.style.fontSize="10px";
				txtInp_YMD.style.border='0px';
				txtInp_YMD.innerHTML = 'YMD'; //Required for IE
				txtInp_YMD.textContent = 'YMD';
				txtInp_YMD.setAttribute('id', '.b.YMD');
		// Sex -----------------------------------------------------------
			var txtInp_gend = document.createElement('div');
				txtInp_gend.setAttribute('type', 'text');
				txtInp_gend.setAttribute('class', 'optionbox');
				txtInp_gend.style.fontSize="10px";
				txtInp_gend.style.border='0px';
				txtInp_gend.innerHTML = 'Sex'; //Required for IE
				txtInp_gend.textContent = 'Sex';
				txtInp_gend.setAttribute('id', '.b.Sex');
		// Occupation ----------------------------------------------------
			var txtInp_occu = document.createElement('div');
				txtInp_occu.setAttribute('type', 'text');
				txtInp_occu.setAttribute('class', 'optionbox');
				txtInp_occu.style.fontSize="10px";
				txtInp_occu.style.border='0px';
				txtInp_occu.innerHTML = 'Occupation'; //Required for IE
				txtInp_occu.textContent = 'Occupation';
				txtInp_occu.setAttribute('id', '.b.Occupation');
		// Indi Birth Place ----------------------------------------------
			var txtInp_birthpl = document.createElement('div');
				txtInp_birthpl.setAttribute('type', 'text');
				txtInp_birthpl.setAttribute('class', 'optionbox');
				txtInp_birthpl.style.fontSize="10px";
				txtInp_birthpl.style.border='0px';
				txtInp_birthpl.innerHTML = 'Birth Place'; //Required for IE
				txtInp_birthpl.textContent = 'Birth Place';
				txtInp_birthpl.setAttribute('id', '.b.Birth Place');
		// Fathers Birth Place ----------------------------------------------
			var txtInp_fbirthpl = document.createElement('div');
				txtInp_fbirthpl.setAttribute('type', 'text');
				txtInp_fbirthpl.setAttribute('class', 'optionbox');
				txtInp_fbirthpl.style.fontSize="10px";
				txtInp_fbirthpl.style.border='0px';
				txtInp_fbirthpl.innerHTML = 'Fathers BP'; //Required for IE
				txtInp_fbirthpl.textContent = 'Fathers BP';
				txtInp_fbirthpl.setAttribute('id', '.b.Fathers BP');
		// Mothers Birth Place ----------------------------------------------
			var txtInp_mbirthpl = document.createElement('div');
				txtInp_mbirthpl.setAttribute('type', 'text');
				txtInp_mbirthpl.setAttribute('class', 'optionbox');
				txtInp_mbirthpl.style.fontSize="10px";
				txtInp_mbirthpl.style.border='0px';
				txtInp_mbirthpl.innerHTML = 'Mothers BP'; //Required for IE
				txtInp_mbirthpl.textContent = 'Mothers BP';
				txtInp_mbirthpl.setAttribute('id', '.b.Mothers BP');
				
		// Text Del Button ----------------------------------------------- 
			var txtInp_tdel = document.createElement('div');
				txtInp_tdel.setAttribute('type', 'text');
				txtInp_tdel.setAttribute('class', 'optionbox');
				txtInp_tdel.style.fontSize="10px";
				txtInp_tdel.style.border='0px';
				txtInp_tdel.innerHTML = 'Del'; //Required for IE
				txtInp_tdel.textContent = 'Del';
			txtInp_tdel.setAttribute('id', this);

		// Text Radio Button --------------------------------------------- 
			var txtInp_tra = document.createElement('div');
				txtInp_tra.setAttribute('type', 'text');
				txtInp_tra.setAttribute('class', 'optionbox');
				txtInp_tra.style.fontSize="10px";
				txtInp_tra.style.border='0px';
				txtInp_tra.innerHTML = 'Ins'; //Required for IE
				txtInp_tra.textContent = 'Ins';
*/

		// c. Define Cell Elements ======================================
//		}else{
		// Item Number ---------------------------------------------------
			var txt_itemNo = document.createTextNode(iteration);
		// Indi ID -------------------------------------------------------
			var txtInp_pid = document.createElement('input');
				if ( pid == ''){
					var txtcolor = "#000000";
					txtInp_pid.setAttribute('type', 'checkbox');
					//txtInp_pid.checked='';
					if (txtInp_pid.checked!=''){
						txtInp_pid.setAttribute('value', 'no');
					}else{
						txtInp_pid.setAttribute('value', 'add');
					}
				}else{
					var txtcolor = "#0000FF";
					txtInp_pid.setAttribute('type', 'text');
						txtInp_pid.setAttribute('value', pid);
				}
				txtInp_pid.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_1');
				txtInp_pid.setAttribute('size', '4');
				txtInp_pid.style.color=txtcolor;
				txtInp_pid.style.fontSize="10px";
		// Full Name -----------------------------------------------------
			var txtInp_nam = document.createElement('input');
				txtInp_nam.setAttribute('size', '30');
				txtInp_nam.setAttribute('value', nam);
				txtInp_nam.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_2');
				txtInp_nam.setAttribute('type', 'text');
				txtInp_nam.style.color=txtcolor;
				txtInp_nam.style.fontSize="10px";
		// Relationship --------------------------------------------------
			var txtInp_label = document.createElement('input');
				txtInp_label.setAttribute('type', 'text');
				txtInp_label.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_3');
				txtInp_label.setAttribute('size', '15');
				txtInp_label.setAttribute('value', label);
				txtInp_label.style.color=txtcolor;
				txtInp_label.style.fontSize="10px";
		// Marital Conditition -------------------------------------------
			var txtInp_cond = document.createElement('input');
				txtInp_cond.setAttribute('type', 'text');
				txtInp_cond.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_4');
				txtInp_cond.setAttribute('size', '1');
				txtInp_cond.setAttribute('value', cond);
				txtInp_cond.style.color=txtcolor;
				txtInp_cond.style.fontSize="10px";
		// YOB -----------------------------------------------------------
			var txtInp_yob = document.createElement('input');
				txtInp_yob.setAttribute('type', 'text');
				txtInp_yob.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_5');
				txtInp_yob.setAttribute('size', '2');
				txtInp_yob.setAttribute('value', yob);
				txtInp_yob.style.color=txtcolor;
				txtInp_yob.style.fontSize="10px";
		// Age -----------------------------------------------------------
			var txtInp_age = document.createElement('input');
				txtInp_age.setAttribute('type', 'text');
				txtInp_age.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_6');
				txtInp_age.setAttribute('size', '2');
				txtInp_age.setAttribute('value', age); 
				txtInp_age.style.color=txtcolor;
				txtInp_age.style.fontSize="10px";
		// YMD -----------------------------------------------------------
			var txtInp_YMD = document.createElement('input');
				txtInp_YMD.setAttribute('type', 'text');
				txtInp_YMD.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_7');
				txtInp_YMD.setAttribute('size', '1');
				txtInp_YMD.setAttribute('value', YMD); 
				txtInp_YMD.style.color=txtcolor;
				txtInp_YMD.style.fontSize="10px";
		// Sex -----------------------------------------------------------
			var txtInp_gend = document.createElement('input');
				txtInp_gend.setAttribute('type', 'text');
				txtInp_gend.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_8');
				txtInp_gend.setAttribute('size', '1');
				txtInp_gend.setAttribute('value', gend); 
				txtInp_gend.style.color=txtcolor;
				txtInp_gend.style.fontSize="10px";
		// Occupation ----------------------------------------------------
			var txtInp_occu = document.createElement('input');
				txtInp_occu.setAttribute('type', 'text');
				txtInp_occu.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_9');
				txtInp_occu.setAttribute('size', '22');
				txtInp_occu.setAttribute('value', occu); 
				txtInp_occu.style.color=txtcolor;
				txtInp_occu.style.fontSize="10px";
		// Indi Birth Place ----------------------------------------------
			var txtInp_birthpl = document.createElement('input');
				txtInp_birthpl.setAttribute('type', 'text');
				txtInp_birthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_10');
				txtInp_birthpl.setAttribute('size', '25');
				txtInp_birthpl.setAttribute('value', birthpl); 
				txtInp_birthpl.style.color=txtcolor;
				txtInp_birthpl.style.fontSize="10px";
		// Fathers Birth Place ----------------------------------------------
			var txtInp_fbirthpl = document.createElement('input');
				txtInp_fbirthpl.setAttribute('type', 'text');
				txtInp_fbirthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_11');
				txtInp_fbirthpl.setAttribute('size', '25');
				txtInp_fbirthpl.setAttribute('value', fbirthpl); 
				txtInp_fbirthpl.style.color=txtcolor;
				txtInp_fbirthpl.style.fontSize="10px";
		// Mothers Birth Place ----------------------------------------------
			var txtInp_mbirthpl = document.createElement('input');
				txtInp_mbirthpl.setAttribute('type', 'text');
				txtInp_mbirthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_12');
				txtInp_mbirthpl.setAttribute('size', '25');
				txtInp_mbirthpl.setAttribute('value', mbirthpl); 
				txtInp_mbirthpl.style.color=txtcolor;
				txtInp_mbirthpl.style.fontSize="10px";
				
		// Delete Row Button ---------------------------------------------
			var btnEl = document.createElement('input');
				btnEl.setAttribute('type', 'button');
				btnEl.setAttribute('value', 'x');
				btnEl.onclick = function () {deleteCurrentRow(this)};
		// Insert row Radio button ---------------------------------------
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
		// Item Number ---------------------------------------------------
			var txt_itemNo2 = document.createTextNode(iteration);
//		}
		// Not visible but used for row re-order process -----------------
			var cbEl = document.createElement('input');
				cbEl.type = "hidden";
			
			
		// c. Append appropriate Cell elements to each cell ==============
		cell_0.appendChild(txt_itemNo);			// Item Number
		cell_1.appendChild(txtInp_pid);			// Indi ID
		cell_2.appendChild(txtInp_nam);			// Full Name
		cell_3.appendChild(txtInp_label);		// Relationship
		cell_4.appendChild(txtInp_cond);		// Marital Condition
		cell_5.appendChild(txtInp_yob);			// YOB
		cell_6.appendChild(txtInp_age);			// Age
		cell_7.appendChild(txtInp_YMD);			// YMD (maybe should be replaced by Age being 10y or 12mo etc)
		cell_8.appendChild(txtInp_gend);		// Sex
		cell_9.appendChild(txtInp_occu);		// Occupation
		cell_10.appendChild(txtInp_birthpl);	// Indi Birthplace
		cell_11.appendChild(txtInp_fbirthpl);	// Fathers Birthplace
		cell_12.appendChild(txtInp_mbirthpl);	// Mothers Birthplace
		if (iteration == 0) {
		//	cell_tdel.appendChild(txtInp_tdel);	// Text Del
		//	cell_ra.appendChild(txtInp_tra);
		}else{
			cell_del.appendChild(btnEl);		// Onclick = Delete Row
			cell_ra.appendChild(raEl);			// Radio button used for inserting a row, rather than adding at end of table)
		}
		cell_15.appendChild(txt_itemNo2);		// Item Number
		
		// Pass in the elements to be referenced later ===================
		// Store the myRow object in each row
		row.myRow = new myRowObject(txt_itemNo, txtInp_pid, txtInp_nam, txtInp_label, txtInp_cond, txtInp_yob, txtInp_age, txtInp_YMD, txtInp_gend, txtInp_occu, txtInp_birthpl, txtInp_fbirthpl, txtInp_mbirthpl, cbEl, raEl, txt_itemNo2);
		
	}
}

// deleteCurrentRow - function to delete a row
function deleteCurrentRow(obj) {
	if (hasLoaded) {
		var delRow = obj.parentNode.parentNode;
		var tbl = delRow.parentNode.parentNode;
		var rIndex = delRow.sectionRowIndex;
		var rowArray = new Array(delRow);
		deleteRows(rowArray);
		reorderRows(tbl, rIndex);
	}
}

function deleteHeaderRow(obj) {
	if (hasLoaded) {
		var delRow = obj.parentNode.parentNode;
		var tbl = delRow.parentNode.parentNode;
		var rIndex = delRow.sectionRowIndex;
		var rowArray = new Array(delRow);
		deleteRows(rowArray);
	}
}

function deleteRows(rowObjArray) {
	if (hasLoaded) {
		for (var i=0; i<rowObjArray.length; i++) {
			var rIndex = rowObjArray[i].sectionRowIndex;
			rowObjArray[i].parentNode.deleteRow(rIndex);
		}
	}
}

// reorderRows - used to reorder rows after an insert or delete
function reorderRows(tbl, startingIndex) {
	if (hasLoaded) {
		if (tbl.tBodies[0].rows[startingIndex]) {
			var count = startingIndex + ROW_BASE;
			for (var i=startingIndex; i<tbl.tBodies[0].rows.length; i++) {
			
				// CONFIG: next 2 lines are affected by myRowObject settings
				tbl.tBodies[0].rows[i].myRow.zero.data		 = count; // text - (left column item number)
				tbl.tBodies[0].rows[i].myRow.fifteen.data	 = count; // text - (right column item number)
				
				tbl.tBodies[0].rows[i].myRow.zero.id	 = INPUT_NAME_PREFIX + count + '_0';  // input text
				tbl.tBodies[0].rows[i].myRow.one.id		 = INPUT_NAME_PREFIX + count + '_1';  // input text
				tbl.tBodies[0].rows[i].myRow.two.id 	 = INPUT_NAME_PREFIX + count + '_2';  // input text
				tbl.tBodies[0].rows[i].myRow.three.id	 = INPUT_NAME_PREFIX + count + '_3';  // input text
				tbl.tBodies[0].rows[i].myRow.four.id	 = INPUT_NAME_PREFIX + count + '_4';  // input text
				tbl.tBodies[0].rows[i].myRow.five.id	 = INPUT_NAME_PREFIX + count + '_5';  // input text
				tbl.tBodies[0].rows[i].myRow.six.id		 = INPUT_NAME_PREFIX + count + '_6';  // input text
				tbl.tBodies[0].rows[i].myRow.seven.id	 = INPUT_NAME_PREFIX + count + '_7';  // input text
				tbl.tBodies[0].rows[i].myRow.eight.id	 = INPUT_NAME_PREFIX + count + '_8';  // input text
				tbl.tBodies[0].rows[i].myRow.nine.id	 = INPUT_NAME_PREFIX + count + '_9';  // input text
				tbl.tBodies[0].rows[i].myRow.ten.id		 = INPUT_NAME_PREFIX + count + '_10'; // input text
				tbl.tBodies[0].rows[i].myRow.eleven.id	 = INPUT_NAME_PREFIX + count + '_11'; // input text
				tbl.tBodies[0].rows[i].myRow.twelve.id	 = INPUT_NAME_PREFIX + count + '_12'; // input text
				// ------------------------------------
				// ------------------------------------
				tbl.tBodies[0].rows[i].myRow.fifteen.id	 = INPUT_NAME_PREFIX + count + '_15'; // input text
				
				tbl.tBodies[0].rows[i].myRow.zero.name	 = INPUT_NAME_PREFIX + count + '_0';  // input text
				tbl.tBodies[0].rows[i].myRow.one.name	 = INPUT_NAME_PREFIX + count + '_1';  // input text
				tbl.tBodies[0].rows[i].myRow.two.name 	 = INPUT_NAME_PREFIX + count + '_2';  // input text
				tbl.tBodies[0].rows[i].myRow.three.name	 = INPUT_NAME_PREFIX + count + '_3';  // input text
				tbl.tBodies[0].rows[i].myRow.four.name	 = INPUT_NAME_PREFIX + count + '_4';  // input text
				tbl.tBodies[0].rows[i].myRow.five.name	 = INPUT_NAME_PREFIX + count + '_5';  // input text
				tbl.tBodies[0].rows[i].myRow.six.name	 = INPUT_NAME_PREFIX + count + '_6';  // input text
				tbl.tBodies[0].rows[i].myRow.seven.name	 = INPUT_NAME_PREFIX + count + '_7';  // input text
				tbl.tBodies[0].rows[i].myRow.eight.name	 = INPUT_NAME_PREFIX + count + '_8';  // input text
				tbl.tBodies[0].rows[i].myRow.nine.name	 = INPUT_NAME_PREFIX + count + '_9';  // input text
				tbl.tBodies[0].rows[i].myRow.ten.name	 = INPUT_NAME_PREFIX + count + '_10'; // input text
				tbl.tBodies[0].rows[i].myRow.eleven.name = INPUT_NAME_PREFIX + count + '_11'; // input text
				tbl.tBodies[0].rows[i].myRow.twelve.name = INPUT_NAME_PREFIX + count + '_12'; // input text
				// ------------------------------------
				// ------------------------------------
				tbl.tBodies[0].rows[i].myRow.fifteen.name = INPUT_NAME_PREFIX + count + '_15'; // input text
				
				tbl.tBodies[0].rows[i].myRow.ra.value = count; // input radio
				count++;
			}
		}
	}
}


