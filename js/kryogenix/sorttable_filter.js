/**
 *
 * Additional filtering functions for sorttable.js
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @see sorttable.js
 * @package PhpGedView
 * @subpackage Display
 * @version $Id: $
 */

function table_filter(id, keyword, filter) {
	var table = document.getElementById(id);
	// get column number
	var firstRow = table.rows[0];
	for (var c=0;c<firstRow.cells.length;c++) {
		if (firstRow.cells[c].textContent && firstRow.cells[c].textContent.indexOf(keyword)!=-1) {
			COLUMN=c;
			break;
		}
	}
	// apply filter
	for (var r=0;r<table.tBodies[0].rows.length;r++) {
		var row = table.tBodies[0].rows[r];
		// display row when matching filter
		var disp = "none";
		if (row.cells[COLUMN].textContent && row.cells[COLUMN].textContent.indexOf(filter)!=-1) {
			disp="table-row";
			if (document.all && !window.opera) disp = "inline"; // IE
		}
		row.style.display=disp;
	}
	table_renum(id);
	return false;
}

function table_renum(id) {
	var table = document.getElementById(id);
	// is first column counter ?
	var firstRow = table.rows[0];
	if (firstRow.cells[0].innerHTML!='') return false;
	// renumbering
	var count=1;
	for (var r=0;r<table.tBodies[0].rows.length;r++) {
		row = table.tBodies[0].rows[r];
		// count only visible rows
		if (row.style.display!='none') row.cells[0].innerHTML = count++;
	}
}

function table_filter_alive(id) {
	var table = document.getElementById(id);
	var year = document.getElementById("aliveyear").value;
	if (year<1500) return;
	// get birth and death column number
	var BCOL = -1;
	var DCOL = -1;
	var firstRow = table.rows[1];
	for (var c=0;c<firstRow.cells.length;c++) {
		atags = firstRow.cells[c].getElementsByTagName("a");
		// <a href="url" title="YYYY-MM-DD HH:MM:SS" ...
		// is "title" a date sortkey ?
		if (atags.length && atags[0].title && atags[0].title.substr(4,1)=='-') {
			if (BCOL<0) BCOL=c;
			else {
				DCOL=c;
				break;
			}
		}
	}
	if (BCOL<0) return;
	if (DCOL<0) return;
	// apply filter
	for (var r=0;r<table.tBodies[0].rows.length;r++) {
		var row = table.tBodies[0].rows[r];
		// get birth year
		atags = row.cells[BCOL].getElementsByTagName("a");
		byear = atags[0].title.substring(0,4);
		// get death year
		atags = row.cells[DCOL].getElementsByTagName("a");
		dyear = atags[0].title.substring(0,4);
		// hide/show
		var disp = "";
		if (byear>0 && dyear>0 && (year<byear || dyear<year)) disp="none";
		else {
			disp="table-row";
			if (document.all && !window.opera) disp = "inline"; // IE
		}
		row.style.display=disp;
	}
	table_renum(id);
	return false;
}
