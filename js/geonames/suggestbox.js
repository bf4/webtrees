/**
 * Place name autocompletion by request to www.geonames.org
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @subpackage Webservice
 * @version $Id$
 * @see http://www.geonames.org/export/ajax-postalcode-autocomplete.html
 * @see http://www.geonames.org/export/geonames-search.html
 */

// Place input field id
var PLAC_id="";
// geoCodes is filled by the JSON callback and used by the mouse event handlers of the suggest box
var geoCodes;
// this function will be called by our JSON callback
// the parameter jData will contain an array with geoCode objects
function getLocation(jData) {
	if (jData == null) {
		// There was a problem parsing search results
		return;
	}
	// save place array in 'geoCodes' to make it accessible from mouse event handlers
	geoCodes = jData.geonames;
	// sort by place name
	function geoSort(a, b) 	{
		aa=a.name+a.adminCode4+a.adminName2+a.adminName1+a.countryCode;
		aa=aa.toLowerCase();
		bb=b.name+b.adminCode4+b.adminName2+b.adminName1+b.countryCode;
		bb=bb.toLowerCase();
		if(aa<bb) return -1
		if(aa>bb) return 1
		return 0
	}
	geoCodes.sort(geoSort);
	if (geoCodes.length > 0) {
		// we got several places for the geoCode
		// make suggest box visible
		document.getElementById(PLAC_id+'_suggestBox').style.visibility = 'visible';
		var suggestBoxHTML  = '';
		// iterate over places and build suggest box content
		for (i=0;i< geoCodes.length;i++) {
			// for every geoCode record we create a html div
			// each div gets an id using the array index for later retrieval
			// define mouse event handlers to highlight places on mouseover
			// and to select a place on click
			// all events receive the geoCode array index as input parameter
			placecode = geoCodes[i].countryCode+'.';
			if (geoCodes[i].adminCode1) placecode += geoCodes[i].adminCode1;
			placecode += '.';
			if (geoCodes[i].adminCode2) placecode += geoCodes[i].adminCode2;
			placecode += '.';
			if (geoCodes[i].adminCode3) placecode += geoCodes[i].adminCode3;
			placecode += '.';
			if (geoCodes[i].adminCode4) placecode += geoCodes[i].adminCode4;
			suggestBoxHTML += '<div class="suggestions" id=pcId' + i
			+ ' onmousedown="suggestBoxMouseDown(' + i + ')"'
			+ ' onmouseover="suggestBoxMouseOver(' + i + ')"'
			+ ' onmouseout="suggestBoxMouseOut(' + i + ')"'
			+ ' alt="'+placecode+'"'
			+ ' title="'+placecode+'"'
			+ '>' ;
			var s = '';
			s += geoCodes[i].name;
			s += ', '+geoCodes[i].adminName2;
			s += ', '+geoCodes[i].adminName1;
			s += ', '+geoCodes[i].countryCode;
			if (suggestBoxHTML.indexOf('>'+s+'<')==-1) suggestBoxHTML += s; // avoid dups
			suggestBoxHTML += '</div>' ;
		}
		suggestBoxHTML += '<div class="credits">Free webservice by: <a target="_BLANK" href="http://www.geonames.org">www.geonames.org</a></div>';
		document.getElementById(PLAC_id+'_suggestBox').innerHTML = suggestBoxHTML;
		// exactly one place
		// directly fill the form, no suggest box required
		/** if (geoCodes.length == 1) suggestBoxMouseDown(0);**/
	} else {
		// no match
		closeSuggestBox();
	}
}
function closeSuggestBox() {
	document.getElementById(PLAC_id+'_suggestBox').innerHTML = '';
	document.getElementById(PLAC_id+'_suggestBox').style.visibility = 'hidden';
}
// remove highlight on mouse out event
function suggestBoxMouseOut(obj) {
	document.getElementById('pcId'+ obj).className = 'suggestions';
}
// the user has selected a place name from the suggest box
function suggestBoxMouseDown(obj) {
	// update PLAC
	document.getElementById(PLAC_id).value = document.getElementById('pcId'+ obj).innerHTML;
	// update LATI & LONG
	if (confirm("Update LATI/LONG ?")) {
		var found = false;
		var inputs = document.getElementsByTagName("input");
	  for (var j = 0; j < inputs.length; j++) {
	    if (inputs[j].id == PLAC_id) found=true;
			if (found && inputs[j].id.indexOf('LATI') > -1) {
				inputs[j].value = geoCodes[obj].lat;
				valid_lati_long(inputs[j], 'N', 'S');
			}
			if (found && inputs[j].id.indexOf('LONG') > -1) {
				inputs[j].value = geoCodes[obj].lng;
				valid_lati_long(inputs[j], 'E', 'W');
				break; // we assume that LONG is always after LATI
			}
	  }
	}
	closeSuggestBox();
}
// function to highlight places on mouse over event
function suggestBoxMouseOver(obj) {
	document.getElementById('pcId'+ obj).className = 'suggestionMouseOver';
}
// call the geonames.org JSON webservice to fetch an array of places
function geoLookup(id) {
	PLAC_id = id;
	var q = document.getElementById(id).value;
	// display loading in suggest box
	document.getElementById(PLAC_id+'_suggestBox').style.visibility = 'visible';
	document.getElementById(PLAC_id+'_suggestBox').innerHTML = '<img src="images/loading.gif" alt="loading ..." />';
	//var lng = 'en';
	var lang = document.getElementById(PLAC_id+'_suggestBoxLang').value; // en fr de it ...
	// @see http://www.geonames.org/export/geonames-search.html
	request = 'http://ws.geonames.org/searchJSON?q=' + q + '&lang='+ lang + '&fcode=CMTY&fcode=ADM4&fcode=PPL&fcode=PPLA&fcode=PPLC&style=full&callback=getLocation';
	// Create a new script object
	aObj = new JSONscriptRequest(request);
	// Build the script tag
	aObj.buildScriptTag();
	// Execute (add) the script tag
	aObj.addScriptTag();
}
