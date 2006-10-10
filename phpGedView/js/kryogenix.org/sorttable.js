/**
 *
 * Copyright (c) 1997-2006 Stuart Langridge (www.kryogenix.org)
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
 * @author http://www.kryogenix.org/code/browser/sorttable/
 * @package PhpGedView
 * @subpackage Display
 * @version $Id$
 */
<!--
addEvent(window, "load", sortables_init);

var SORT_COLUMN_INDEX;

function sortables_init() {
	// Find all tables with class sortable and make them sortable
	if (!document.getElementsByTagName) return;
	tbls = document.getElementsByTagName("table");
	for (ti=0;ti<tbls.length;ti++) {
		thisTbl = tbls[ti];
		if (((' '+thisTbl.className+' ').indexOf("sortable") != -1) && (thisTbl.id)) {
			//initTable(thisTbl.id);
			ts_makeSortable(thisTbl);
		}
	}
}

function ts_makeSortable(table) {
	if (table.rows && table.rows.length > 0) {
		var firstRow = table.rows[0];
	}
	if (!firstRow) return;

	// We have a first row: assume it's the header, and make its contents clickable links
	for (var i=0;i<firstRow.cells.length;i++) {
		var cell = firstRow.cells[i];
		var txt = ts_getInnerText(cell);
		cell.innerHTML = '<a href="#" class="sortheader" '+
		'onMousedown="this.style.cursor=\'wait\';" ' + // PGV: set cursor
		'onclick="ts_resortTable(this, '+i+');return false;">' +
		txt+'<span class="sortarrow">&nbsp;&nbsp;</span></a>';
	}
}

function ts_getInnerText(el) {
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };
	if (el.innerText) return el.innerText;	//Not needed but it is faster
	var str = "";

	var cs = el.childNodes;
	var l = cs.length;
	for (var i = 0; i < l; i++) {
		switch (cs[i].nodeType) {
			case 1: //ELEMENT_NODE
				str += ts_getInnerText(cs[i]);
				break;
			case 3:	//TEXT_NODE
				str += cs[i].nodeValue;
				break;
		}
	}
	return str;
}

function ts_resortTable(lnk,clid) {
	// get the span
	var span;
	for (var ci=0;ci<lnk.childNodes.length;ci++) {
		if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') span = lnk.childNodes[ci];
	}
	var spantext = ts_getInnerText(span);
	var td = lnk.parentNode;
	var column = clid || td.cellIndex;
	var table = getParent(td,'TABLE');

	// PGV : confirm action for big table
	if (table.rows.length > 300
	&& !confirm("Sorting this big table may take a long time\r\nContinue ?")) {
		lnk.style.cursor='pointer';
		return;
	}
	lnk.style.cursor='wait';
	// Work out a type for the column
	if (table.rows.length <= 1) return;
	var itm = ts_getInnerText(table.rows[1].cells[column]);
	sortfn = ts_sort_caseinsensitive;
	if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d$/)) sortfn = ts_sort_date;
	if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d$/)) sortfn = ts_sort_date;
	if (itm.match(/^[£$]/)) sortfn = ts_sort_currency;
	if (itm.match(/^[A-Z][\d\.]+$/)) sortfn = ts_sort_currency; // PGV: GEDCOM ID
	if (itm.match(/^[\d\.]+[A-Z]$/)) sortfn = ts_sort_currency; // PGV: GEDCOM ID
	if (itm.match(/^[\d\.]+$/)) sortfn = ts_sort_numeric;
	SORT_COLUMN_INDEX = column;
	var firstRow = new Array();
	var newRows = new Array();
	for (i=0;i<table.rows[0].length;i++) { firstRow[i] = table.rows[0][i]; }
	for (j=1;j<table.rows.length;j++) { newRows[j-1] = table.rows[j]; }

	newRows.sort(sortfn);

	if (span.getAttribute("sortdir") == 'down') {
		ARROW = '&nbsp;&uarr;';
		newRows.reverse();
		span.setAttribute('sortdir','up');
	} else {
		ARROW = '&nbsp;&darr;';
		span.setAttribute('sortdir','down');
	}

	// We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
	// don't do sortbottom rows
	for (i=0;i<newRows.length;i++) { if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) table.tBodies[0].appendChild(newRows[i]);}
	// do sortbottom rows only
	for (i=0;i<newRows.length;i++) { if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1)) table.tBodies[0].appendChild(newRows[i]);}

	// Delete any other arrows there may be showing
	var allspans = document.getElementsByTagName("span");
	for (var ci=0;ci<allspans.length;ci++) {
		if (allspans[ci].className == 'sortarrow') {
			if (getParent(allspans[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
				allspans[ci].innerHTML = '&nbsp;&nbsp;';
				if (allspans[ci]!=span) allspans[ci].setAttribute('sortdir','up'); // PGV: reset sortdir
			}
		}
	}

	span.innerHTML = ARROW;
	table_renum(table.id); // PGV: update line counter
	lnk.style.cursor='pointer'; // PGV: reset cursor
}

function getParent(el, pTagName) {
	if (el == null) return null;
	else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
		return el;
	else
		return getParent(el.parentNode, pTagName);
}
function ts_sort_date(a,b) {
	// y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
	if (aa.length == 10) {
		dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
	} else {
		yr = aa.substr(6,2);
		if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
		dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
	}
	if (bb.length == 10) {
		dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
	} else {
		yr = bb.substr(6,2);
		if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
		dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
	}
	if (dt1==dt2) return 0;
	if (dt1<dt2) return -1;
	return 1;
}

function ts_sort_currency(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	//return parseFloat(aa) - parseFloat(bb);
	aa = parseFloat(aa);
	if (isNaN(aa)) aa = 0;
	bb = parseFloat(bb);
	if (isNaN(bb)) bb = 0;
	if (aa<bb) return -1;
	if (aa>bb) return 1;
	// PGV: when aa==bb keep previous order (=row index)
	if (a.rowIndex<b.rowIndex) return -1
	if (a.rowIndex>b.rowIndex) return 1
	return 0;
}

function ts_sort_numeric(a,b) {
	aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
	if (isNaN(aa)) aa = 0;
	bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
	if (isNaN(bb)) bb = 0;
	//return aa-bb;
	if (aa<bb) return -1;
	if (aa>bb) return 1;
	// PGV: when aa==bb keep previous order (=row index)
	if (a.rowIndex<b.rowIndex) return -1
	if (a.rowIndex>b.rowIndex) return 1
	return 0;
}

function ts_sort_caseinsensitive(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();

	// PGV: get "title" sortkey if exists
	akey = a.cells[SORT_COLUMN_INDEX].getElementsByTagName("a");
	if (akey.length && akey[0].title) aa = akey[0].title;
	bkey = b.cells[SORT_COLUMN_INDEX].getElementsByTagName("a");
	if (bkey.length && bkey[0].title) bb = bkey[0].title;


	// PGV: clean UTF8 special chars before sorting
	aa = strclean(aa);
	bb = strclean(bb);

	// PGV: when aa==bb keep previous order (=row index)
	if (aa<bb) return -1;
	if (aa>bb) return 1;
	if (a.rowIndex<b.rowIndex) return -1
	if (a.rowIndex>b.rowIndex) return 1
	return 0;
}

function ts_sort_default(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
	if (aa==bb) return 0;
	if (aa<bb) return -1;
	return 1;
}


function addEvent(elm, evType, fn, useCapture)
// addEvent and removeEvent
// cross-browser event handling for IE5+,  NS6 and Mozilla
// By Scott Andrew
{
	if (elm.addEventListener){
		elm.addEventListener(evType, fn, useCapture);
		return true;
	} else if (elm.attachEvent){
		var r = elm.attachEvent("on"+evType, fn);
		return r;
	} else {
		alert("Handler could not be removed");
	}
}


//
// More functions for PGV
//
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
		row = table.rows[r];
		if (ts_getInnerText(row.cells[COLUMN]).indexOf(filter)==-1) disp="none";
		else {
			disp="table-row";
			if (document.all) disp="inline"; // IE
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
	if (ts_getInnerText(firstRow.cells[0]).indexOf('#')==-1) return false;
	// renumbering
	count=1;
	for (var r=1;r<table.rows.length;r++) {
		row = table.rows[r];
		if (row.style.display!='none') row.cells[0].innerHTML = count++;
	}
}

function table_filter_alive(id) {
	var table = document.getElementById(id);
	var year = document.getElementById("aliveyear").value;
	if (year<1500) return;
	// get birth and death column number
	BCOL = -1;
	DCOL = -1;
	var firstRow = table.rows[1];
	for (var c=0;c<firstRow.cells.length;c++) {
		key = firstRow.cells[c].getElementsByTagName("a");
		// <a href="url" title="YYYY-MM-DD HH:MM:SS" ...
		// is "title" a date sortkey ?
		if (key.length && key[0].title && key[0].title.substr(4,1)=='-') {
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
	for (var r=1;r<table.rows.length;r++) {
		row = table.rows[r];
		key = row.cells[BCOL].getElementsByTagName("a");
		byear = key[0].title.substring(0,4);
		key = row.cells[DCOL].getElementsByTagName("a");
		dyear = key[0].title.substring(0,4);
		if (byear>0 && dyear>0 && (year<byear || dyear<year)) disp="none";
		else {
			disp="table-row";
			if (document.all) disp="inline"; // IE
		}
		row.style.display=disp;
	}
	table_renum(id);
	return false;
}

//-->
