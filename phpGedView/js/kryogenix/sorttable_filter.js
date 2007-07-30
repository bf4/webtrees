/**
 *
 * Additional filtering functions for sorttable.js
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  John Finlay and Others
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
 * @version $Id$
 */

function table_filter(id, keyword, filter) {
	var table = document.getElementById(id);
	// get column number
	var firstRow = table.rows[0];
	for (var c=0;c<firstRow.cells.length;c++) {
		if (ts_getInnerText(firstRow.cells[c]).indexOf(keyword)!=-1) {
			COLUMN=c;
			break;
		}
	}
	// apply filter
	for (var r=1;r<table.rows.length;r++) {
		var row = table.rows[r];
		// don't do sortbottom last rows
		if (row.className && (row.className.indexOf('sortbottom') != -1)) break;
		// display row when matching filter
		var disp = "none";
		if (row.cells[COLUMN] && ts_getInnerText(row.cells[COLUMN]).indexOf(filter)!=-1) {
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
	if (ts_getInnerText(firstRow.cells[0])!='') return false;
	// renumbering
	var count=1;
	for (var r=1;r<table.rows.length;r++) {
		row = table.rows[r];
		// don't do sortbottom last rows
		if (row.className && (row.className.indexOf('sortbottom') != -1)) break;
		// count only visible rows
		if (row.style.display!='none') row.cells[0].innerHTML = count++;
	}
}

function table_filter_alive(id) {
	var table = document.getElementById(id);
	var year = document.getElementById("aliveyear").value;
	if (year>2500) // Approximate conversion of hebrew to gregorian year
		year-=3761;

	// Calculate Julian Day numbers for 1st Jan and 31st Dec
	var jd_1jan=1721061+365*year+Math.floor((year-1)/4)-Math.floor((year-1)/100)+Math.floor((year-1)/400);
	var jd_31dec=1721425+365*year+Math.floor(year/4)-Math.floor(year/100)+Math.floor(year/400);

	// get birth and death column number
	var BCOL = -1;
	var DCOL = -1;
	var firstRow = table.rows[1];
	var key;
	for (var c=0;c<firstRow.cells.length;c++) {
		key = firstRow.cells[c].getElementsByTagName("a");
		if (key.length && key[0].name && key[0].name.match(/^\d+$/)) {
			if (BCOL<0)
				BCOL=c;
			else {
				DCOL=c;
				break;
			}
		}
	}
	if (BCOL<0 || DCOL<0) return;

	// apply filter
	for (var r=1;r<table.rows.length;r++) {
		var row = table.rows[r];
		// don't do sortbottom last rows
		if (row.className && (row.className.indexOf('sortbottom') != -1)) break;
		// get jd of birth/death
		key=row.cells[BCOL].getElementsByTagName("a");
		b_jd=parseInt(key[0].name,10);
		key=row.cells[DCOL].getElementsByTagName("a");
		d_jd=parseInt(key[0].name,10);
		// assume birth/death based on max age of 100 years
		if (b_jd==0 && d_jd>0)
			b_jd=d_jd-36525;
		if (d_jd==0 && b_jd>0)
			d_jd=b_jd+36525;
		// hide/show
		if (b_jd<=jd_31dec && d_jd>=jd_1jan) {
			if (document.all && !window.opera)
				row.style.display="inline"; // IE
			else
				row.style.display="table-row";
		} else
			row.style.display="none";
	}
	// Resequence row numbers
	table_renum(id);
	return false;
}
