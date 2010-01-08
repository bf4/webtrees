<?php
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

// modified from tabledeleterow.js version 1.2 2006-02-21
// mredkj.com

// CONFIG notes. Below are some comments that point to where this script can be customized.
// Note: Make sure to include a <tbody></tbody> in your table's HTML

//	To load the file XXX for module YYY, call
	loadLangFile("GEDFact_assistant:lang");
	
?>
<script src="modules/GEDFact_assistant/_CENS/js/chapman_codes.js" type="text/javascript"></script>
<script>

//Load Language variables for Edit header and tooltip ============================
var HeaderName        = "<?php echo $pgv_lang["header_Name"];?>";
var TTEditName        = "<?php echo $pgv_lang["tt_edit_Name"];?>";
var HeaderRela        = "<?php echo $pgv_lang["header_Rela"];?>";
var TTEditRela        = "<?php echo $pgv_lang["tt_edit_Rela"];?>";
var HeaderMCond       = "<?php echo $pgv_lang["header_MCond"];?>";
var TTEditMCond       = "<?php echo $pgv_lang["tt_edit_MCond"];?>";
var HeaderAsset       = "<?php echo $pgv_lang["header_Asset"];?>";
var TTEditAsset       = "<?php echo $pgv_lang["tt_edit_Asset"];?>";
var HeaderAge         = "<?php echo $pgv_lang["header_Age"];?>";
var TTEditAge         = "<?php echo $pgv_lang["tt_edit_Age"];?>";
var HeaderRace        = "<?php echo $pgv_lang["header_Race"];?>";
var TTEditRace        = "<?php echo $pgv_lang["tt_edit_Race"];?>";
var HeaderSex         = "<?php echo $pgv_lang["header_Sex"];?>";
var TTEditSex         = "<?php echo $pgv_lang["tt_edit_Sex"];?>";
var HeaderYOB         = "<?php echo $pgv_lang["header_YOB"];?>";
var TTEditYOB         = "<?php echo $pgv_lang["tt_edit_YOB"];?>";
var HeaderBmth        = "<?php echo $pgv_lang["header_Bmth"];?>";
var TTEditBmth        = "<?php echo $pgv_lang["tt_edit_Bmth"];?>";
var HeaderYrsM        = "<?php echo $pgv_lang["header_YrsM"];?>";
var TTEditYrsM        = "<?php echo $pgv_lang["tt_edit_YrsM"];?>";
var HeaderChilB       = "<?php echo $pgv_lang["header_ChilB"];?>";
var TTEditChilB       = "<?php echo $pgv_lang["tt_edit_ChilB"];?>";
var HeaderChilL       = "<?php echo $pgv_lang["header_ChilL"];?>";
var TTEditChilL       = "<?php echo $pgv_lang["tt_edit_ChilL"];?>";
var HeaderChilD       = "<?php echo $pgv_lang["header_ChilD"];?>";
var TTEditChilD       = "<?php echo $pgv_lang["tt_edit_ChilD"];?>";
var HeaderAgeM        = "<?php echo $pgv_lang["header_AgM"];?>";
var TTEditAgeM        = "<?php echo $pgv_lang["tt_edit_AgM"];?>";
var HeaderOccu        = "<?php echo $pgv_lang["header_Occu"];?>";
var TTEditOccu        = "<?php echo $pgv_lang["tt_edit_Occu"];?>";
var HeaderBplace      = "<?php echo $pgv_lang["header_Bplace"];?>";    // Full format
var TTEditBplace      = "<?php echo $pgv_lang["tt_edit_Bplace"];?>";   // Full format
var HeaderBP          = "<?php echo $pgv_lang["header_BP"];?>";        // Chapman format
var TTEditBP          = "<?php echo $pgv_lang["tt_edit_BP"];?>";       // Chapman format
var HeaderFBP         = "<?php echo $pgv_lang["header_FBP"];?>";       // Chapman format
var TTEditFBP         = "<?php echo $pgv_lang["tt_edit_FBP"];?>";      // Chapman format
var HeaderMBP         = "<?php echo $pgv_lang["header_MBP"];?>";       // Chapman format
var TTEditMBP         = "<?php echo $pgv_lang["tt_edit_MBP"];?>";      // Chapman format
var HeaderNL          = "<?php echo $pgv_lang["header_NL"];?>";        
var TTEditNL          = "<?php echo $pgv_lang["tt_edit_NL"];?>";       
var HeaderHealth      = "<?php echo $pgv_lang["header_Health"];?>";
var TTEditHealth      = "<?php echo $pgv_lang["tt_edit_Health"];?>";
var HeaderYrsUS       = "<?php echo $pgv_lang["header_YrsUS"];?>";
var TTEditYrsUS       = "<?php echo $pgv_lang["tt_edit_YrsUS"];?>";
var HeaderYOI         = "<?php echo $pgv_lang["header_YOI"];?>";
var TTEditYOI         = "<?php echo $pgv_lang["tt_edit_YOI"];?>";
var HeaderNA          = "<?php echo $pgv_lang["header_NA"];?>";
var TTEditNA          = "<?php echo $pgv_lang["tt_edit_NA"];?>";
var HeaderYON         = "<?php echo $pgv_lang["header_YON"];?>";
var TTEditYON         = "<?php echo $pgv_lang["tt_edit_YON"];?>";
var HeaderEngL        = "<?php echo $pgv_lang["header_EngL"];?>";
var TTEditEngL        = "<?php echo $pgv_lang["tt_edit_EngL"];?>";
var HeaderEng         = "<?php echo $pgv_lang["header_Eng"];?>";
var TTEditEng         = "<?php echo $pgv_lang["tt_edit_Eng"];?>";
var HeaderInd         = "<?php echo $pgv_lang["header_Ind"];?>";
var TTEditInd         = "<?php echo $pgv_lang["tt_edit_Ind"];?>";
var HeaderEmp         = "<?php echo $pgv_lang["header_Emp"];?>";
var TTEditEmp         = "<?php echo $pgv_lang["tt_edit_Emp"];?>";
var HeaderEmR         = "<?php echo $pgv_lang["header_EmR"];?>";
var TTEditEmR         = "<?php echo $pgv_lang["tt_edit_EmR"];?>";
var HeaderEmD         = "<?php echo $pgv_lang["header_EmD"];?>";
var TTEditEmD         = "<?php echo $pgv_lang["tt_edit_EmD"];?>";
var HeaderEmH         = "<?php echo $pgv_lang["header_EmH"];?>";
var TTEditEmH         = "<?php echo $pgv_lang["tt_edit_EmH"];?>";
var HeaderEmN         = "<?php echo $pgv_lang["header_EmN"];?>";
var TTEditEmN         = "<?php echo $pgv_lang["tt_edit_EmN"];?>";
var HeaderEduc        = "<?php echo $pgv_lang["header_Educ"];?>";
var TTEditEduc        = "<?php echo $pgv_lang["tt_edit_Educ"];?>";
var HeaderBIC         = "<?php echo $pgv_lang["header_BIC"];?>";
var TTEditBIC         = "<?php echo $pgv_lang["tt_edit_BIC"];?>";
var HeaderBOE         = "<?php echo $pgv_lang["header_BOE"];?>";
var TTEditBOE         = "<?php echo $pgv_lang["tt_edit_BOE"];?>";
var HeaderInfirm      = "<?php echo $pgv_lang["header_Infirm"];?>";
var TTEditInfirm      = "<?php echo $pgv_lang["tt_edit_Infirm"];?>";
var HeaderVet         = "<?php echo $pgv_lang["header_Vet"];?>";
var TTEditVet         = "<?php echo $pgv_lang["tt_edit_Vet"];?>";
var HeaderTenure      = "<?php echo $pgv_lang["header_Tenure"];?>";
var TTEditTenure      = "<?php echo $pgv_lang["tt_edit_Tenure"];?>";
var HeaderParent      = "<?php echo $pgv_lang["header_Parent"];?>";
var TTEditParent      = "<?php echo $pgv_lang["tt_edit_Parent"];?>";
var HeaderMmth        = "<?php echo $pgv_lang["header_Mmth"];?>";
var TTEditMmth        = "<?php echo $pgv_lang["tt_edit_Mmth"];?>";
var HeaderMnse        = "<?php echo $pgv_lang["header_Mnse"];?>";
var TTEditMnse        = "<?php echo $pgv_lang["tt_edit_Mnse"];?>";
var HeaderWksu        = "<?php echo $pgv_lang["header_Wksu"];?>";
var TTEditWksu        = "<?php echo $pgv_lang["tt_edit_Wksu"];?>";
var HeaderMnsu        = "<?php echo $pgv_lang["header_Mnsu"];?>";
var TTEditMnsu        = "<?php echo $pgv_lang["tt_edit_Mnsu"];?>";
var HeaderHome        = "<?php echo $pgv_lang["header_Home"];?>";
var TTEditHome        = "<?php echo $pgv_lang["tt_edit_Home"];?>";
var HeaderSitu        = "<?php echo $pgv_lang["header_Situ"];?>";
var TTEditSitu        = "<?php echo $pgv_lang["tt_edit_Situ"];?>";
var HeaderWar         = "<?php echo $pgv_lang["header_War"];?>";
var TTEditWar         = "<?php echo $pgv_lang["tt_edit_War"];?>";
var HeaderInfirm1910  = "<?php echo $pgv_lang["header_Infirm1910"];?>";
var TTEditInfirm1910  = "<?php echo $pgv_lang["tt_edit_Infirm1910"];?>";
var HeaderEducpre1890 = "<?php echo $pgv_lang["header_Educpre1890"];?>";
var TTEditEducpre1890 = "<?php echo $pgv_lang["tt_edit_Educpre1890"];?>";

var HeaderLang        = "<?php echo $pgv_lang["header_Lang"];?>";
var TTEditLang        = "<?php echo $pgv_lang["tt_edit_Lang"];?>";

// Load Edit Table variables =====================================================
var INPUT_NAME_PREFIX = 'InputCell_'; // this is being set via script
var RADIO_NAME = "totallyrad"; // this is being set via script
var TABLE_NAME = 'tblSample'; // this should be named in the HTML
var ROW_BASE = 0; // first number (for display)
var hasLoaded = false;


// Load Other variables ==========================================================
var NoteCtry = document.getElementById('censCtry');
var NoteYear = document.getElementById('censYear');
var NoteTitl = document.getElementById('Titl');


function preview() {
	NoteCtry = document.getElementById('censCtry');
	NoteYear = document.getElementById('censYear');
	Citation = document.getElementById('citation');
	Locality = document.getElementById('locality');
	Notes    = document.getElementById('notes');

	str = NoteYear.value + " " + NoteCtry.value + " " + NoteTitl.value;
	str += "\n";
	if (Citation.value!="" && Citation.value!=null) {
		str += Citation.value + "\n";
	}
	if (Locality.value!="" && Locality.value!=null) {
		str += Locality.value + "\n";
	}
	str += "\n";
	str += ".start_formatted_area.";
	
	iid = "";

	var tbl = document.getElementById('tblSample');
	

	for(var i=0; i<tbl.rows.length; i++){
		var tr = tbl.rows[i];
		var strRow = '';
		
		var pidList = '';

		// ---------------------------------------------
		
		// Extract Indi id's from created list --------------------------------------
		for(var y=1; y<tr.cells.length-3; y++) {
			if ( y>=2 && y<=69) {
					continue;
			}else{
				if (i!=0) {
					// pidList += '\'' + (pidList==''?'':' ') + tr.cells[1].childNodes[0].value + '\'';
					pidList += (pidList==''?'':' ') + tr.cells[1].childNodes[0].value;
				}
			}
		}
		
		// Extract required columns for display based on Country and Year -----------
		if (NoteCtry.value=="UK") {
			// UK 1921 or 1911 ===============
			if (NoteYear.value=="1921" || NoteYear.value=="1911") {
				for(var j=2; j<tr.cells.length-3; j++) {  
					if (j==5 || j==6 || j==8 || (j>=10 && j<=15) || (j>=20 && j<=34) || j==36 || (j>=39 && j<=41) || (j>=43 && j<=49) || (j>=51 && j<=62) || (j>=64 && j<=69) ) {
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			// UK 1901 ===============
			} else if (NoteYear.value=="1901") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( j==5 || j==6 || j==8 || (j>=10 && j<=34) || j==36 || j==37 || (j>=39 && j<=41) || (j>=43 && j<=49) || (j>=51 && j<=62) || (j>=64 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			// UK 1891 ===============
			} else if (NoteYear.value=="1891") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( j==5 || j==6 || j==8 || (j>=10 && j<=34) || (j>=36 && j<=38) || j==41 || j==42 || (j>=44 && j<=49) || (j>=51 && j<=62) || (j>=64 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			// UK 1951-1881 ============
			} else if (NoteYear.value=="1851" || NoteYear.value=="1861" || NoteYear.value=="1871" || NoteYear.value=="1881") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( j==5 || j==6 || j==8 || (j>=10 && j<=34) || (j>=36 && j<=49) || (j>=51 && j<=62) || (j>=64 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			// UK 1841 ===============
			} else if (NoteYear.value=="1841") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=3 && j<=6) || j==8 || (j>=10 && j<=34) || (j>=36 && j<=51) || (j>=54 && j<=69) ) {  
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			
		} else if (NoteCtry.value=="USA") {
			// USA 1930 ===============
			if (NoteYear.value=="1930") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( j==4 || j==5 || j==7 || j==8 || j==11 || j==13 || j==14 || (j>=16 && j<=19) || (j>=21 && j<=45) || (j>=47 && j<=50) || j==52 || j==53 || j==63 || j==64 || j==67 || j==68 || j==69 ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			} 

			// USA 1920 ===============
			else if (NoteYear.value=="1920") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( j==4 || (j>=6 && j<=8) || j==11 || j==13 || j==14 || (j>=16 && j<=30) || (j>=34 && j<=45) || (j>=47 && j<=50) || j==52 || j==53 || j==57 || j==58 || (j>=63 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}

			// USA 1910 ===============
			else if (NoteYear.value=="1910") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=4 && j<=8) || j==11 || j==13 || j==14 || (j>=19 && j<=26) || j==33 || j==36 || (j>=39 && j<=42) || j==45 || j==47 || j==48 || (j>=50 && j<=64) || j==66 || j==68 || j==69 ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			// USA 1900 ===============
			else if (NoteYear.value=="1900") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=4 && j<=7) || j==10 || j==13 || j==14 || (j>=19 && j<=26) || j==33 || j==34 || (j>=36 && j<=44) || j==47 || (j>=50 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			// USA 1890 ===============
			else if (NoteYear.value=="1890") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=4 && j<=7) || j==10 || j==11 || j==13 || j==14 || (j>=19 && j<=26) || j==31 || j==33 || j==34 || j==36 || j==37 || j==39 || j==40 || (j>=42 && j<=45) || j==47 || (j>=49 && j<=63) || (j>=65 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			// USA 1880 ===============
			else if (NoteYear.value=="1880") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=3 && j<=7) || j==10 || j==11 || (j>=17 && j<=34) || (j>=37 && j<=44) || (j>=40 && j<=42) || j==46 || j==49 || j==50 || j==52 || j==53 || (j>=56 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			// USA 1870 ===============
			else if (NoteYear.value=="1870") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=3 && j<=6) || j==8 || (j>=11 && j<=20) || (j>=27 && j<=46) || (j>=48 && j<=62) || (j>=64 && j<=69) ) { 
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			// USA 1860 or 1850 ===============
			else if (NoteYear.value=="1860" || NoteYear.value=="1850") {
				for(var j=2; j<tr.cells.length-3; j++) {	// == j=2 means miss out cols 0 and 1 (# and pid), cells.length-3 means miss out del, ins and item # 
					if ( (j>=3 && j<=6) || j==8 || (j>=11 && j<=20) || j==24 || j==25 || (j>=27 && j<=46) || (j>=48 && j<=62) || (j>=64 && j<=69) ) {
							continue;
					}else{
						if (i==0) {
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].id;
						}else{
							strRow += (strRow==''?'':'|') + tr.cells[j].childNodes[0].value;
						}
					}
				}
			}
			
		}else{
			// Other country stuff
		}
		
		str += (str==''?'':'\n') + strRow;
		if (i!=0) {
			iid += (iid==''?'':'') + pidList + ', ';
		}
		
	}
	
	// --- Debug only - alert indi id's found --------
	//	if (i!=0) {
	//		alert(iid);
	//	}
	
	var mem = document.getElementById('NOTE');
	if (Notes.value!="" && Notes.value!=null) {
		mem.value = str + "\n.end_formatted_area.\n\nNotes:\n"+Notes.value;
	} else {
		mem.value = str + "\n.end_formatted_area.\n";
	}
		
	// ---- Create an array of Indi id's ----------
	var mem21 = document.getElementById('pid_array');
		mem21.value = iid.slice(0, -2);
		
} // ---- end function preview() -----


window.onload=fillInRows;

// fillInRows - can be used to pre-load a table with a header, row, or rows
function fillInRows() {
	hasLoaded = true;
	// create_header();
	// insertRowToTable();
	// addRowToTable();
}

// myRowObject - an object for storing information about the table rows
function myRowObject(	zero, one, two, three, four, five, six, seven, eight, nine, 
						ten, eleven, twelve, thirteen, fourteen, fifteen, sixteen, seventeen, eighteen, nineteen, 
						twenty, twentyone, twentytwo, twentythree, twentyfour, twentyfive, twentysix, twentyseven, twentyeight, twentynine, 
						thirty, thirtyone, thirtytwo, thirtythree, thirtyfour, thirtyfive, thirtysix, thirtyseven, thirtyeight, thirtynine, 
						forty, fortyone, fortytwo, fortythree, fortyfour, fortyfive, fortysix, fortyseven, fortyeight, fortynine, 
						fifty, fiftyone, fiftytwo, fiftythree, fiftyfour, fiftyfive, fiftysix, fiftyseven, fiftyeight, fiftynine, 
						sixty, sixtyone, sixtytwo, sixtythree, sixtyfour, sixtyfive, sixtysix, sixtyseven, sixtyeight, sixtynine, 
						cb, ra, seventytwo 
					) 
{
						
	this.zero		 = zero;		 // text object
	this.one		 = one;			 // input text object
	this.two		 = two;			 // input text object
	this.three		 = three;		 // input text object
	this.four		 = four;		 // input text object
	this.five		 = five;		 // input text object
	this.six		 = six;			 // input text object
	this.seven		 = seven;		 // input text object
	this.eight		 = eight;		 // input text object
	this.nine		 = nine;		 // input text object
	this.ten		 = ten;			 // input text object
	this.eleven		 = eleven;		 // input text object
	this.twelve		 = twelve;		 // input text object
	this.thirteen	 = thirteen;	 // input text object
	this.fourteen	 = fourteen;	 // input text object
	this.fifteen	 = fifteen;		 // input text object
	this.sixteen	 = sixteen;		 // input text object
	this.seventeen	 = seventeen;	 // input text object
	this.eighteen	 = eighteen;	 // input text object
	this.nineteen	 = nineteen;	 // input text object
	this.twenty		 = twenty;		 // input text object
	this.twentyone	 = twentyone;	 // input text object
	this.twentytwo	 = twentytwo;	 // input text object
	this.twentythree = twentythree;	 // input text object
	this.twentyfour	 = twentyfour;	 // input text object
	this.twentyfive	 = twentyfive;	 // input text object
	this.twentysix	 = twentysix;	 // input text object
	this.twentyseven = twentyseven;	 // input text object
	this.twentyeight = twentyeight;	 // input text object
	this.twentynine	 = twentynine;	 // input text object
	this.thirty		 = thirty;		 // input text object
	this.thirtyone	 = thirtyone;	 // input text object
	this.thirtytwo	 = thirtytwo;	 // input text object
	this.thirtythree = thirtythree;	 // input text object
	this.thirtyfour	 = thirtyfour;	 // input text object
	this.thirtyfive	 = thirtyfive;	 // input text object
	this.thirtysix	 = thirtysix;	 // input text object
	this.thirtyseven = thirtyseven;	 // input text object
	this.thirtyeight = thirtyeight;	 // input text object
	this.thirtynine	 = thirtynine;	 // input text object
	this.forty		 = forty;		 // input text object
	this.fortyone	 = fortyone;	 // input text object
	this.fortytwo	 = fortytwo;	 // input text object
	this.fortythree	 = fortythree;	 // input text object
	this.fortyfour	 = fortyfour;	 // input text object
	this.fortyfive	 = fortyfive;	 // input text object
	this.fortysix	 = fortysix;	 // input text object
	this.fortyseven	 = fortyseven;	 // input text object
	this.fortyeight	 = fortyeight;	 // input text object
	this.fortynine	 = fortynine;	 // input text object
	this.fifty		 = fifty;		 // input text object
	this.fiftyone	 = fiftyone;	 // input text object
	this.fiftytwo	 = fiftytwo;	 // input text object
	this.fiftythree	 = fiftythree;	 // input text object
	this.fiftyfour	 = fiftyfour;	 // input text object
	this.fiftyfive	 = fiftyfive;	 // input text object
	this.fiftysix	 = fiftysix;	 // input text object
	this.fiftyseven	 = fiftyseven;	 // input text object
	this.fiftyeight	 = fiftyeight;	 // input text object
	this.fiftynine	 = fiftynine;	 // input text object
	this.sixty		 = sixty;		 // input text object
	this.sixtyone	 = sixtyone;	 // input text object
	this.sixtytwo	 = sixtytwo;	 // input text object
	this.sixtythree	 = sixtythree;	 // input text object
	this.sixtyfour	 = sixtyfour;	 // input text object
	this.sixtyfive	 = sixtyfive;	 // input text object
	this.sixtysix	 = sixtysix;	 // input text object
	this.sixtyseven	 = sixtyseven;	 // input text object
	this.sixtyeight	 = sixtyeight;	 // input text object
	this.sixtynine	 = sixtynine;	 // input text object
	
	this.cb			 = cb;			 // input checkbox object
	this.ra			 = ra;			 // input radio object
	this.seventytwo	 = seventytwo;	 // text object
}

function create_header() {
		addRowToTable();
}

// insertRowToTable - inserts a row into the table (and reorders)
function insertRowToTable(pid, nam,  mnam, label, gend, cond, dom, dob, age, YMD, occu, birthpl, fbirthpl, mbirthpl) {
	if (hasLoaded) {
		// calculate marriage status -----------------------
		var cenyr = document.getElementById('censYear').value;
		
		var tbl = document.getElementById(TABLE_NAME);
		var rowToInsertAt = tbl.tBodies[0].rows.length;
		for (var i=1; i<tbl.tBodies[0].rows.length; i++) {  // i set to 1 to avoid header row of number 0
			if (tbl.tBodies[0].rows[i].myRow && tbl.tBodies[0].rows[i].myRow.ra.getAttribute('type') == 'radio' && tbl.tBodies[0].rows[i].myRow.ra.checked) {
				rowToInsertAt = i;
				break;
			}
		}
		addRowToTable(rowToInsertAt, pid, nam, mnam, label, gend, cond, dom, dob, age, YMD, occu, birthpl, fbirthpl, mbirthpl);
		
		reorderRows(tbl, rowToInsertAt);
		currcenyear = document.getElementById('censYear').value;
		//var currcenctry = document.getElementById('censCtry').value; 
		changeCols(currcenyear);
		changeMC(currcenyear);
		changeAge(currcenyear);
		
		preview();
		
	}
}

// addRowToTable - Inserts at row 'num', or appends to the end if no arguments are passed in. Don't pass in empty strings.
function addRowToTable(num, pid, nam, mnam, label, gend, cond, dom, dob, age2, YMD, occu, birthpl, fbirthpl, mbirthpl, cb, ra) {

	// -- Temporary until insert variable are corrected ----------------
		 var ibirthpl = '';
	//	 var fbirthpl = '';
	//	 var mbirthpl = '';
	// -----------------------------------------------------------------

		// Calculate birth places --------------------------------------
		currcenyear = document.getElementById('censYear').value;
		currcenctry = document.getElementById('censCtry').value;
		
		// DEBUG ==========================================================
		// alert("IBP = "+birthpl+"\nFBP = "+fbirthpl+"\nMBP = "+mbirthpl);
		// DEBUG ==========================================================
		
		if (num>0) {
			birthpl  = birthpl.split(', ');
			ibirthpl = birthpl.reverse();
			fbirthpl = fbirthpl.split(', ');
			fbirthpl = fbirthpl.reverse();
			mbirthpl = mbirthpl.split(', ');
			mbirthpl = mbirthpl.reverse();
			
		// DEBUG ==========================================================
		// alert("IBP = "+birthpl+"\nFBP = "+fbirthpl+"\nMBP = "+mbirthpl);
		// DEBUG ==========================================================
		
			// get Chapman Code for US ------------------
			if (birthpl[0] == "United States" || birthpl[0] == "United States Of America" || birthpl[0] == "USA") {
				var ibirthpl = getChapmanCode(birthpl[1]);
			} else {
				var ibirthpl = getChapmanCode(birthpl[0]);
			}
			
			if (fbirthpl[0] == "UNK") {
				var fbirthpl = getChapmanCode(fbirthpl[0]);
			} else if (fbirthpl[0] == "United States" || fbirthpl[0] == "United States Of America" || fbirthpl[0] == "USA") {
				var fbirthpl = getChapmanCode(fbirthpl[1]);
			} else {
				var fbirthpl = getChapmanCode(fbirthpl[0]);
			}
		
			if (mbirthpl[0] == "UNK") {
				var mbirthpl = getChapmanCode(mbirthpl[0]);
			} else if (mbirthpl[0] == "United States" || mbirthpl[0] == "United States Of America" || mbirthpl[0] == "USA") {
				var mbirthpl = getChapmanCode(mbirthpl[1]);
			} else {
				var mbirthpl = getChapmanCode(mbirthpl[0]);
			}

			// get birthplace for UK (check all countries in UK) ------------------------------------------------------------------------------
			if (birthpl[0]==null) {
				birthpl = '-';
			} else if (birthpl[0]!="England" && birthpl[0]!="Scotland" && birthpl[0]!="Wales" && birthpl[0]!="Northern Ireland" && birthpl[0]!="UK") {
				birthpl = birthpl[0]+", "+birthpl[1];
			}else {
				birthpl = birthpl[1]+", "+birthpl[2];
			}
			
		// DEBUG ==========================================================
		// alert("IBP = "+birthpl+"\nFBP = "+fbirthpl+"\nMBP = "+mbirthpl);
		// DEBUG ==========================================================
		
		}

	var cyear_a=document.getElementById('censYear'); 
	var cyear=cyear_a.value;
	var cctry_a=document.getElementById('censCtry'); 
	var cctry=cctry_a.value;
	
	var one_day   = 1000*60*60*24;
	var one_month = (365.26*one_day)/12;
	var one_year  = 365.26*one_day;
	
	// Date of Birth (dob) - passed as Julian Date String
	if (dob>1721060) {
		IJD = Math.floor(dob);
		L = Math.floor(IJD + 68569);
		N = Math.floor(4 * L / 146097);
		L = L - Math.floor((146097*N + 3)/4);
		I = Math.floor(4000*(L + 1)/1461001);
		L = L - Math.floor(1461 * I / 4) + 31;
		J = Math.floor(80 * L / 2447);
		K = L - Math.floor(2447 * J / 80);
		L = Math.floor(J/11);
		J = J + 2 - 12*L;
		I = 100*(N - 49) + I + L;
		dob = (I+", "+J+", "+K);
	}
	// Create Date of Birth object from passed string dob 
	jsdob = new Date(dob);
	
	// Date of Marriage (dom) - passed as Julian Date String
	if (dom>1721060) {
		IJD = Math.floor(dom);
		L = Math.floor(IJD + 68569);
		N = Math.floor(4 * L / 146097);
		L = L - Math.floor((146097*N + 3)/4);
		I = Math.floor(4000*(L + 1)/1461001);
		L = L - Math.floor(1461 * I / 4) + 31;
		J = Math.floor(80 * L / 2447);
		K = L - Math.floor(2447 * J / 80);
		L = Math.floor(J/11);
		J = J + 2 - 12*L;
		I = 100*(N - 49) + I + L;
		dom = (I+", "+J+", "+K);
	}
	// Create Date of Birth object from passed string dob 
	jsdom = new Date(dom);

	
	// Create dob for US Census (month year)
	if (jsdob != "Invalid Date") {
		var I=I;
		usdob = jsdob.format("NNN "+I);
		agemarr = Math.floor((jsdom-jsdob)/one_year);
/*
alert(jsdom);
alert(jsdob);
alert(agemarr);
*/
	} else {
		usdob = '-';
		agemarr = '-';
	}

	var ctry = document.getElementById('censCtry').value;
	
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
		
		// Initial Age Calculation based on 1901 Census Year input ============
		// *** NOTE *** 
		// *** This is then corrected when ChangeYear() function is run 
		// *** ChangeYear() is run each time Census Year is selected or changed
		if (age2!="Age" && age2!=null) {
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
			age="-";
		}
		
		
// DEBUG ==========================
// agey  = jsdob.format("d NNN y");
// alert("birthdate = "+agey);
// alert(cendate);
// END DEBUG =======================

		// add the row =======================================================
		var row = tbl.tBodies[0].insertRow(num);

		// **A** Define Cells ===============================================
		var cell_ = new Array(69);
		for(var i=0; i<=69; i++){
				cell_[i] = row.insertCell(i);
				cell_[i].setAttribute('id', 'col_'+i);
				cell_[i].setAttribute('name', 'col_'+i);
		}

		if (iteration == 0) {
			var cell_tdel = row.insertCell(70);		// text Del
			var cell_tra  = row.insertCell(71);		// text Radio
		}else{
			var cell_del = row.insertCell(70);		// Onclick = Delete Row
				cell_del.setAttribute('align', 'center');
			var cell_ra = row.insertCell(71);		// Radio button used for inserting a row, rather than adding at end of table)
		}
		
		var cell_72 = row.insertCell(72);			// Item Number
			cell_72.setAttribute('id', 'col_72');
			cell_72.setAttribute('name', 'col_72');
			cell_72.setAttribute('align', 'center');


		// **B** SHOW/HIDE Header Cell elements ===============================
			// ---- Basic Hidden Columns (miss out 0,1,2 and >68)
			for(var i=3; i<=69; i++){
				cell_[i].style.display = "none"; 
			}
			// ---- Show Cell Columns =========================================
			var currcenyear = document.getElementById('censYear').value; 
			changeCols(currcenyear);

		// **C** Define Header Cell elements ==================================
		if (iteration == 0) {
		// 0. Item Number -----------------------------------------------------
			var txt_itemNo = document.createElement('div');
				txt_itemNo.setAttribute('class', 'descriptionbox');
				txt_itemNo.className= 'descriptionbox'; //Required for IE
				txt_itemNo.style.border='0px';
				txt_itemNo.innerHTML = '#';
				txt_itemNo.setAttribute('id', '.b.Item');
				txt_itemNo.setAttribute('type', 'text');
				txt_itemNo.style.fontSize="10px";
				txt_itemNo.style.display="none";
		// 1. Indi ID ---------------------------------------------------------
			var txtInp_pid = document.createElement('div');
				txtInp_pid.setAttribute('type', 'text');
				txtInp_pid.setAttribute('class', 'descriptionbox');
				txtInp_pid.className= 'descriptionbox'; //Required for IE
				txtInp_pid.style.fontSize="10px";
				txtInp_pid.style.border='0px';
				txtInp_pid.innerHTML = '<a href="#" alt="ID" title="ID"> ID </a>'; 
		// 2. Name ------------------------------------------------------------
			var txtInp_nam = document.createElement('div');
				txtInp_nam.setAttribute('class', 'descriptionbox');
				txtInp_nam.className= 'descriptionbox'; //Required for IE
				txtInp_nam.setAttribute('type', 'text');
				txtInp_nam.style.fontSize="10px";
				txtInp_nam.style.border='0px';
				txtInp_nam.innerHTML = '<a href="#" alt="'+TTEditName+'" title="'+TTEditName+'">'+HeaderName+'</a>'; 
				txtInp_nam.setAttribute('id', '.b.'+HeaderName);
		// 3. Relationship_1 --------------------------------------------------
			var txtInp_label = document.createElement('div');
				txtInp_label.setAttribute('type', 'text');
				txtInp_label.setAttribute('class', 'descriptionbox');
				txtInp_label.className= 'descriptionbox'; //Required for IE
				txtInp_label.style.fontSize="10px";
				txtInp_label.style.border='0px';
				txtInp_label.innerHTML = '<a href="#" alt="'+TTEditRela+'" title="'+TTEditRela+'">'+HeaderRela+'</a>'; 
				txtInp_label.setAttribute('id', '.b.'+HeaderRela);
		// 4. Conditition_1 ---------------------------------------------------
			var txtInp_cond = document.createElement('div');
				txtInp_cond.setAttribute('type', 'text');
				txtInp_cond.setAttribute('class', 'descriptionbox');
				txtInp_cond.className= 'descriptionbox'; //Required for IE
				txtInp_cond.style.fontSize="10px";
				txtInp_cond.style.border='0px';
				txtInp_cond.innerHTML = '<a href="#" alt="'+TTEditMCond+'" title="'+TTEditMCond+'">'+HeaderMCond+'</a>'; 
				txtInp_cond.setAttribute('id', '.b.'+HeaderMCond);
		// 5. Tenure ----------------------------------------------------------
			var txtInp_tenure = document.createElement('div');
				txtInp_tenure.setAttribute('type', 'text');
				txtInp_tenure.setAttribute('class', 'descriptionbox');
				txtInp_tenure.className= 'descriptionbox'; //Required for IE
				txtInp_tenure.style.fontSize="10px";
				txtInp_tenure.style.border='0px';
				txtInp_tenure.innerHTML = '<a href="#" alt="'+TTEditTenure+'" title="'+TTEditTenure+'">'+HeaderTenure+'</a>'; 
				txtInp_tenure.setAttribute('id', '.b.'+HeaderTenure);
		// 6. Assets ----------------------------------------------------------
			var txtInp_assets = document.createElement('div');
				txtInp_assets.setAttribute('type', 'text');
				txtInp_assets.setAttribute('class', 'descriptionbox');
				txtInp_assets.className= 'descriptionbox'; //Required for IE
				txtInp_assets.style.fontSize="10px";
				txtInp_assets.style.border='0px';
				txtInp_assets.innerHTML = '<a href="#" alt="'+TTEditAsset+'" title="'+TTEditAsset+'">'+HeaderAsset+'</a>'; 
				txtInp_assets.setAttribute('id', '.b.'+HeaderAsset);
		// 7. Age_1 -----------------------------------------------------------
			var txtInp_age = document.createElement('div');
				txtInp_age.setAttribute('type', 'text');
				txtInp_age.setAttribute('class', 'descriptionbox');
				txtInp_age.className= 'descriptionbox'; //Required for IE
				txtInp_age.style.fontSize="10px";
				txtInp_age.style.border='0px';
				txtInp_age.innerHTML = '<a href="#" alt="'+TTEditAge+'" title="'+TTEditAge+'">'+HeaderAge+'</a>'; 
				txtInp_age.setAttribute('id', '.b.'+HeaderAge);
		// 8. Race_1 ----------------------------------------------------------
			var txtInp_race = document.createElement('div');
				txtInp_race.setAttribute('type', 'text');
				txtInp_race.setAttribute('class', 'descriptionbox');
				txtInp_race.className= 'descriptionbox'; //Required for IE
				txtInp_race.style.fontSize="10px";
				txtInp_race.style.border='0px';
				txtInp_race.innerHTML = '<a href="#" alt="'+TTEditRace+'" title="'+TTEditRace+'">'+HeaderRace+'</a>'; 
				txtInp_race.setAttribute('id', '.b.'+HeaderRace);
		// 9. Sex -------------------------------------------------------------
			var txtInp_gend = document.createElement('div');
				txtInp_gend.setAttribute('type', 'text');
				txtInp_gend.setAttribute('class', 'descriptionbox');
				txtInp_gend.className= 'descriptionbox'; //Required for IE
				txtInp_gend.style.fontSize="10px";
				txtInp_gend.style.border='0px';
				txtInp_gend.innerHTML = '<a href="#" alt="'+TTEditSex+'" title="'+TTEditSex+'">'+HeaderSex+'</a>'; 
				txtInp_gend.setAttribute('id', '.b.'+HeaderSex);
		// 10. Race_2 ----------------------------------------------------------
			var txtInp_race2 = document.createElement('div');
				txtInp_race2.setAttribute('type', 'text');
				txtInp_race2.setAttribute('class', 'descriptionbox');
				txtInp_race2.className= 'descriptionbox'; //Required for IE
				txtInp_race2.style.fontSize="10px";
				txtInp_race2.style.border='0px';
				txtInp_race2.innerHTML = '<a href="#" alt="'+TTEditRace+'" title="'+TTEditRace+'">'+HeaderRace+'</a>'; 
				txtInp_race2.setAttribute('id', '.b.'+HeaderRace);
		// 11. DOB/YOB ---------------------------------------------------------
			var txtInp_yob = document.createElement('div');
				txtInp_yob.setAttribute('type', 'text');
				txtInp_yob.setAttribute('class', 'descriptionbox');
				txtInp_yob.className= 'descriptionbox'; //Required for IE
				txtInp_yob.style.fontSize="10px";
				txtInp_yob.style.border='0px';
				txtInp_yob.innerHTML = '<a href="#" alt="'+TTEditYOB+'" title="'+TTEditYOB+'">'+HeaderYOB+'</a>'; 
				txtInp_yob.setAttribute('id', '.b.'+HeaderYOB);
		// 12. Age_2 -----------------------------------------------------------
			var txtInp_age2 = document.createElement('div');
				txtInp_age2.setAttribute('type', 'text');
				txtInp_age2.setAttribute('class', 'descriptionbox');
				txtInp_age2.className= 'descriptionbox'; //Required for IE
				txtInp_age2.style.fontSize="10px";
				txtInp_age2.style.border='0px';
				txtInp_age2.innerHTML = '<a href="#" alt="'+TTEditAge+'" title="'+TTEditAge+'">'+HeaderAge+'</a>'; 
				txtInp_age2.setAttribute('id', '.b.'+HeaderAge);
		// 13. MthB (if born within census year) -------------------------------
			var txtInp_bmth = document.createElement('div');
				txtInp_bmth.setAttribute('type', 'text');
				txtInp_bmth.setAttribute('class', 'descriptionbox');
				txtInp_bmth.className= 'descriptionbox'; //Required for IE
				txtInp_bmth.style.fontSize="10px";
				txtInp_bmth.style.border='0px';
				txtInp_bmth.innerHTML = '<a href="#" alt="'+TTEditBmth+'" title="'+TTEditBmth+'">'+HeaderBmth+'</a>'; 
				txtInp_bmth.setAttribute('id', '.b.'+HeaderBmth);
		// 14. Relationship_2 --------------------------------------------------
			var txtInp_label2 = document.createElement('div');
				txtInp_label2.setAttribute('type', 'text');
				txtInp_label2.setAttribute('class', 'descriptionbox');
				txtInp_label2.className= 'descriptionbox'; //Required for IE
				txtInp_label2.style.fontSize="10px";
				txtInp_label2.style.border='0px';
				txtInp_label2.innerHTML = '<a href="#" alt="'+TTEditRela+'" title="'+TTEditRela+'">'+HeaderRela+'</a>'; 
				txtInp_label2.setAttribute('id', '.b.'+HeaderRela);
		// 15. Conditition_2 ---------------------------------------------------
			var txtInp_cond2 = document.createElement('div');
				txtInp_cond2.setAttribute('type', 'text');
				txtInp_cond2.setAttribute('class', 'descriptionbox');
				txtInp_cond2.className= 'descriptionbox'; //Required for IE
				txtInp_cond2.style.fontSize="10px";
				txtInp_cond2.style.border='0px';
				txtInp_cond2.innerHTML = '<a href="#" alt="'+TTEditMCond+'" title="'+TTEditMCond+'">'+HeaderMCond+'</a>'; 
				txtInp_cond2.setAttribute('id', '.b.'+HeaderMCond);
		// 16. Years Married ---------------------------------------------------
			var txtInp_yrsm = document.createElement('div');
				txtInp_yrsm.setAttribute('type', 'text');
				txtInp_yrsm.setAttribute('class', 'descriptionbox');
				txtInp_yrsm.className= 'descriptionbox'; //Required for IE
				txtInp_yrsm.style.fontSize="10px";
				txtInp_yrsm.style.border='0px';
				txtInp_yrsm.innerHTML = '<a href="#" alt="'+TTEditYrsM+'" title="'+TTEditYrsM+'">'+HeaderYrsM+'</a>'; 
				txtInp_yrsm.setAttribute('id', '.b.'+HeaderYrsM);
		// 17. Children Born Alive ---------------------------------------------
			var txtInp_chilB = document.createElement('div');
				txtInp_chilB.setAttribute('type', 'text');
				txtInp_chilB.setAttribute('class', 'descriptionbox');
				txtInp_chilB.className= 'descriptionbox'; //Required for IE
				txtInp_chilB.style.fontSize="10px";
				txtInp_chilB.style.border='0px';
				txtInp_chilB.innerHTML = '<a href="#" alt="'+TTEditChilB+'" title="'+TTEditChilB+'">'+HeaderChilB+'</a>'; 
				txtInp_chilB.setAttribute('id', '.b.'+HeaderChilB);
		// 18. Children Still Living -------------------------------------------
			var txtInp_chilL = document.createElement('div');
				txtInp_chilL.setAttribute('type', 'text');
				txtInp_chilL.setAttribute('class', 'descriptionbox');
				txtInp_chilL.className= 'descriptionbox'; //Required for IE
				txtInp_chilL.style.fontSize="10px";
				txtInp_chilL.style.border='0px';
				txtInp_chilL.innerHTML = '<a href="#" alt="'+TTEditChilL+'" title="'+TTEditChilL+'">'+HeaderChilL+'</a>'; 
				txtInp_chilL.setAttribute('id', '.b.'+HeaderChilL);
		// 19. Children who have Died ------------------------------------------
			var txtInp_chilD = document.createElement('div');
				txtInp_chilD.setAttribute('type', 'text');
				txtInp_chilD.setAttribute('class', 'descriptionbox');
				txtInp_chilD.className= 'descriptionbox'; //Required for IE
				txtInp_chilD.style.fontSize="10px";
				txtInp_chilD.style.border='0px';
				txtInp_chilD.innerHTML = '<a href="#" alt="'+TTEditChilD+'" title="'+TTEditChilD+'">'+HeaderChilD+'</a>'; 
				txtInp_chilD.setAttribute('id', '.b.'+HeaderChilD);
		// 20. Age at first Marriage -------------------------------------------
			var txtInp_ageM = document.createElement('div');
				txtInp_ageM.setAttribute('type', 'text');
				txtInp_ageM.setAttribute('class', 'descriptionbox');
				txtInp_ageM.className= 'descriptionbox'; //Required for IE
				txtInp_ageM.style.fontSize="10px";
				txtInp_ageM.style.border='0px';
				txtInp_ageM.innerHTML = '<a href="#" alt="'+TTEditAgeM+'" title="'+TTEditAgeM+'">'+HeaderAgeM+'</a>'; 
				txtInp_ageM.setAttribute('id', '.b.'+HeaderAgeM);
		// 21. Occupation_1 ----------------------------------------------------
			var txtInp_occu = document.createElement('div');
				txtInp_occu.setAttribute('type', 'text');
				txtInp_occu.setAttribute('class', 'descriptionbox');
				txtInp_occu.className= 'descriptionbox'; //Required for IE
				txtInp_occu.style.fontSize="10px";
				txtInp_occu.style.border='0px';
				txtInp_occu.innerHTML = '<a href="#" alt="'+TTEditOccu+'" title="'+TTEditOccu+'">'+HeaderOccu+'</a>'; 
				txtInp_occu.setAttribute('id', '.b.'+HeaderOccu);
		// 22. Assets_2 --------------------------------------------------------
			var txtInp_assets2 = document.createElement('div');
				txtInp_assets2.setAttribute('type', 'text');
				txtInp_assets2.setAttribute('class', 'descriptionbox');
				txtInp_assets2.className= 'descriptionbox'; //Required for IE
				txtInp_assets2.style.fontSize="10px";
				txtInp_assets2.style.border='0px';
				txtInp_assets2.innerHTML = '<a href="#" alt="'+TTEditAsset+'" title="'+TTEditAsset+'">'+HeaderAsset+'</a>'; 
				txtInp_assets2.setAttribute('id', '.b.'+HeaderAsset);
		// 23. Birth Place_1 -----------------------------------------------
			var txtInp_birthpl = document.createElement('div');
				txtInp_birthpl.setAttribute('type', 'text');
				txtInp_birthpl.setAttribute('class', 'descriptionbox');
				txtInp_birthpl.className= 'descriptionbox'; //Required for IE
				txtInp_birthpl.style.fontSize="10px";
				txtInp_birthpl.style.border='0px';
				txtInp_birthpl.innerHTML = '<a href="#" alt="'+TTEditBplace+'" title="'+TTEditBplace+'">'+HeaderBplace+'</a>';
				txtInp_birthpl.setAttribute('id', '.b.'+HeaderBplace);
		// 24. Parentage -----------------------------------------------
			var txtInp_parent = document.createElement('div');
				txtInp_parent.setAttribute('type', 'text');
				txtInp_parent.setAttribute('class', 'descriptionbox');
				txtInp_parent.className= 'descriptionbox'; //Required for IE
				txtInp_parent.style.fontSize="10px";
				txtInp_parent.style.border='0px';
				txtInp_parent.innerHTML = '<a href="#" alt="'+TTEditParent+'" title="'+TTEditParent+'">'+HeaderParent+'</a>';
				txtInp_parent.setAttribute('id', '.b.'+HeaderParent);
		// 25. MthB_2 (if born within census year) -------------------------------
			var txtInp_bmth2 = document.createElement('div');
				txtInp_bmth2.setAttribute('type', 'text');
				txtInp_bmth2.setAttribute('class', 'descriptionbox');
				txtInp_bmth2.className= 'descriptionbox'; //Required for IE
				txtInp_bmth2.style.fontSize="10px";
				txtInp_bmth2.style.border='0px';
				txtInp_bmth2.innerHTML = '<a href="#" alt="'+TTEditBmth+'" title="'+TTEditBmth+'">'+HeaderBmth+'</a>'; 
				txtInp_bmth2.setAttribute('id', '.b.'+HeaderBmth);
		// 26. MthM (if married within census year) -------------------------------
			var txtInp_mmth = document.createElement('div');
				txtInp_mmth.setAttribute('type', 'text');
				txtInp_mmth.setAttribute('class', 'descriptionbox');
				txtInp_mmth.className= 'descriptionbox'; //Required for IE
				txtInp_mmth.style.fontSize="10px";
				txtInp_mmth.style.border='0px';
				txtInp_mmth.innerHTML = '<a href="#" alt="'+TTEditMmth+'" title="'+TTEditMmth+'">'+HeaderMmth+'</a>'; 
				txtInp_mmth.setAttribute('id', '.b.'+HeaderMmth);
		// 27. Indi Birth Place_1 -----------------------------------------------
			var txtInp_ibirthpl = document.createElement('div');
				txtInp_ibirthpl.setAttribute('type', 'text');
				txtInp_ibirthpl.setAttribute('class', 'descriptionbox');
				txtInp_ibirthpl.className= 'descriptionbox'; //Required for IE
				txtInp_ibirthpl.style.fontSize="10px";
				txtInp_ibirthpl.style.border='0px';
				txtInp_ibirthpl.innerHTML = '<a href="#" alt="'+TTEditBP+'" title="'+TTEditBP+'">'+HeaderBP+'</a>';
				txtInp_ibirthpl.setAttribute('id', '.b.'+HeaderBP);
		// 28. Fathers Birth Place_1 ---------------------------------------------
			var txtInp_fbirthpl = document.createElement('div');
				txtInp_fbirthpl.setAttribute('type', 'text');
				txtInp_fbirthpl.setAttribute('class', 'descriptionbox');
				txtInp_fbirthpl.className= 'descriptionbox'; //Required for IE
				txtInp_fbirthpl.style.fontSize="10px";
				txtInp_fbirthpl.style.border='0px';
				txtInp_fbirthpl.innerHTML = '<a href="#" alt="'+TTEditFBP+'" title="'+TTEditFBP+'">'+HeaderFBP+'</a>';
				txtInp_fbirthpl.setAttribute('id', '.b.'+HeaderFBP);
		// 29. Mothers Birth Place_1 ---------------------------------------------
			var txtInp_mbirthpl = document.createElement('div');
				txtInp_mbirthpl.setAttribute('type', 'text');
				txtInp_mbirthpl.setAttribute('class', 'descriptionbox');
				txtInp_mbirthpl.className= 'descriptionbox'; //Required for IE
				txtInp_mbirthpl.style.fontSize="10px";
				txtInp_mbirthpl.style.border='0px';
				txtInp_mbirthpl.innerHTML = '<a href="#" alt="'+TTEditMBP+'" title="'+TTEditMBP+'">'+HeaderMBP+'</a>';
				txtInp_mbirthpl.setAttribute('id', '.b.'+HeaderMBP);
		// 30. Years in USA ----------------------------------------------------
			var txtInp_yrsUS = document.createElement('div');
				txtInp_yrsUS.setAttribute('type', 'text');
				txtInp_yrsUS.setAttribute('class', 'descriptionbox');
				txtInp_yrsUS.className= 'descriptionbox'; //Required for IE
				txtInp_yrsUS.style.fontSize="10px";
				txtInp_yrsUS.style.border='0px';
				txtInp_yrsUS.innerHTML = '<a href="#" alt="'+TTEditYrsUS+'" title="'+TTEditYrsUS+'">'+HeaderYrsUS+'</a>';
				txtInp_yrsUS.setAttribute('id', '.b.'+HeaderYrsUS);
		// 31. Year of Immigration YOI_1 ----------------------------------------
			var txtInp_yoi1 = document.createElement('div');
				txtInp_yoi1.setAttribute('type', 'text');
				txtInp_yoi1.setAttribute('class', 'descriptionbox');
				txtInp_yoi1.className= 'descriptionbox'; //Required for IE
				txtInp_yoi1.style.fontSize="10px";
				txtInp_yoi1.style.border='0px';
				txtInp_yoi1.innerHTML = '<a href="#" alt="'+TTEditYOI+'" title="'+TTEditYOI+'">'+HeaderYOI+'</a>';
				txtInp_yoi1.setAttribute('id', '.b.'+HeaderYOI);
		// 32. Natualized or Alien_1 ----------------------------------------
			var txtInp_na1 = document.createElement('div');
				txtInp_na1.setAttribute('type', 'text');
				txtInp_na1.setAttribute('class', 'descriptionbox');
				txtInp_na1.className= 'descriptionbox'; //Required for IE
				txtInp_na1.style.fontSize="10px";
				txtInp_na1.style.border='0px';
				txtInp_na1.innerHTML = '<a href="#" alt="'+TTEditNA+'" title="'+TTEditNA+'">'+HeaderNA+'</a>';
				txtInp_na1.setAttribute('id', '.b.'+HeaderNA);
		// 33. Year of Naturalization YON_1 ----------------------------------------
			var txtInp_yon = document.createElement('div');
				txtInp_yon.setAttribute('type', 'text');
				txtInp_yon.setAttribute('class', 'descriptionbox');
				txtInp_yon.className= 'descriptionbox'; //Required for IE
				txtInp_yon.style.fontSize="10px";
				txtInp_yon.style.border='0px';
				txtInp_yon.innerHTML = '<a href="#" alt="'+TTEditYON+'" title="'+TTEditYON+'">'+HeaderYON+'</a>';
				txtInp_yon.setAttribute('id', '.b.'+HeaderYON);
		// 34. English if spoken, or if not, Language spoken Eng/Lang ---------------
			var txtInp_englang = document.createElement('div');
				txtInp_englang.setAttribute('type', 'text');
				txtInp_englang.setAttribute('class', 'descriptionbox');
				txtInp_englang.className= 'descriptionbox'; //Required for IE
				txtInp_englang.style.fontSize="10px";
				txtInp_englang.style.border='0px';
				txtInp_englang.innerHTML = '<a href="#" alt="'+TTEditEngL+'" title="'+TTEditEngL+'">'+HeaderEngL+'</a>';
				txtInp_englang.setAttribute('id', '.b.'+HeaderEngL);
		// 35. Occupation_2 ---------------------------------------------------------
			var txtInp_occu2 = document.createElement('div');
				txtInp_occu2.setAttribute('type', 'text');
				txtInp_occu2.setAttribute('class', 'descriptionbox');
				txtInp_occu2.className= 'descriptionbox'; //Required for IE
				txtInp_occu2.style.fontSize="10px";
				txtInp_occu2.style.border='0px';
				txtInp_occu2.innerHTML = '<a href="#" alt="'+TTEditOccu+'" title="'+TTEditOccu+'">'+HeaderOccu+'</a>'; 
				txtInp_occu2.setAttribute('id', '.b.'+HeaderOccu);
		// 36. Health --------------------------------------------------------------
			var txtInp_health = document.createElement('div');
				txtInp_health.setAttribute('type', 'text');
				txtInp_health.setAttribute('class', 'descriptionbox');
				txtInp_health.className= 'descriptionbox'; //Required for IE
				txtInp_health.style.fontSize="10px";
				txtInp_health.style.border='0px';
				txtInp_health.innerHTML = '<a href="#" alt="'+TTEditHealth+'" title="'+TTEditHealth+'">'+HeaderHealth+'</a>'; 
				txtInp_health.setAttribute('id', '.b.'+HeaderHealth);
		// 37. Industry ind_1 ------------------------------------------------------
			var txtInp_ind1 = document.createElement('div');
				txtInp_ind1.setAttribute('type', 'text');
				txtInp_ind1.setAttribute('class', 'descriptionbox');
				txtInp_ind1.className= 'descriptionbox'; //Required for IE
				txtInp_ind1.style.fontSize="10px";
				txtInp_ind1.style.border='0px';
				txtInp_ind1.innerHTML = '<a href="#" alt="'+TTEditInd+'" title="'+TTEditInd+'">'+HeaderInd+'</a>'; 
				txtInp_ind1.setAttribute('id', '.b.'+HeaderInd);
		// 38. Employ_1 ------------------------------------------------------------
			var txtInp_emp1 = document.createElement('div');
				txtInp_emp1.setAttribute('type', 'text');
				txtInp_emp1.setAttribute('class', 'descriptionbox');
				txtInp_emp1.className= 'descriptionbox'; //Required for IE
				txtInp_emp1.style.fontSize="10px";
				txtInp_emp1.style.border='0px';
				txtInp_emp1.innerHTML = '<a href="#" alt="'+TTEditEmp+'" title="'+TTEditEmp+'">'+HeaderEmp+'</a>';
				txtInp_emp1.setAttribute('id', '.b.'+HeaderEmp);
		// 39. Employer - EmR-----------------------------------------------------------
			var txtInp_emR = document.createElement('div');
				txtInp_emR.setAttribute('type', 'text');
				txtInp_emR.setAttribute('class', 'descriptionbox');
				txtInp_emR.className= 'descriptionbox'; //Required for IE
				txtInp_emR.style.fontSize="10px";
				txtInp_emR.style.border='0px';
				txtInp_emR.innerHTML = '<a href="#" alt="'+TTEditEmR+'" title="'+TTEditEmR+'">'+HeaderEmR+'</a>';
				txtInp_emR.setAttribute('id', '.b.'+HeaderEmR);
		// 40. Employed EmD ------------------------------------------------------------
			var txtInp_emD = document.createElement('div');
				txtInp_emD.setAttribute('type', 'text');
				txtInp_emD.setAttribute('class', 'descriptionbox');
				txtInp_emD.className= 'descriptionbox'; //Required for IE
				txtInp_emD.style.fontSize="10px";
				txtInp_emD.style.border='0px';
				txtInp_emD.innerHTML = '<a href="#" alt="'+TTEditEmD+'" title="'+TTEditEmD+'">'+HeaderEmD+'</a>';
				txtInp_emD.setAttribute('id', '.b.'+HeaderEmD);
		// 41. Months employed during Census Year ---------------------------------------
			var txtInp_mnsE = document.createElement('div');
				txtInp_mnsE.setAttribute('type', 'text');
				txtInp_mnsE.setAttribute('class', 'descriptionbox');
				txtInp_mnsE.className= 'descriptionbox'; //Required for IE
				txtInp_mnsE.style.fontSize="10px";
				txtInp_mnsE.style.border='0px';
				txtInp_mnsE.innerHTML = '<a href="#" alt="'+TTEditMnse+'" title="'+TTEditMnse+'">'+HeaderMnse+'</a>';
				txtInp_mnsE.setAttribute('id', '.b.'+HeaderMnse);
		// 42. Working at Home WH ----------------------------------------------------
			var txtInp_emH = document.createElement('div');
				txtInp_emH.setAttribute('type', 'text');
				txtInp_emH.setAttribute('class', 'descriptionbox');
				txtInp_emH.className= 'descriptionbox'; //Required for IE
				txtInp_emH.style.fontSize="10px";
				txtInp_emH.style.border='0px';
				txtInp_emH.innerHTML = '<a href="#" alt="'+TTEditEmH+'" title="'+TTEditEmH+'">'+HeaderEmH+'</a>';
				txtInp_emH.setAttribute('id', '.b.'+HeaderEmH);
		// 43. Not Employed EmN --------------------------------------------------------
			var txtInp_emN = document.createElement('div');
				txtInp_emN.setAttribute('type', 'text');
				txtInp_emN.setAttribute('class', 'descriptionbox');
				txtInp_emN.className= 'descriptionbox'; //Required for IE
				txtInp_emN.style.fontSize="10px";
				txtInp_emN.style.border='0px';
				txtInp_emN.innerHTML = '<a href="#" alt="'+TTEditEmN+'" title="'+TTEditEmN+'">'+HeaderEmN+'</a>';
				txtInp_emN.setAttribute('id', '.b.'+HeaderEmN);
		// 44. Weeks unemployed during Census Year ---------------------------------------
			var txtInp_wksU = document.createElement('div');
				txtInp_wksU.setAttribute('type', 'text');
				txtInp_wksU.setAttribute('class', 'descriptionbox');
				txtInp_wksU.className= 'descriptionbox'; //Required for IE
				txtInp_wksU.style.fontSize="10px";
				txtInp_wksU.style.border='0px';
				txtInp_wksU.innerHTML = '<a href="#" alt="'+TTEditWksu+'" title="'+TTEditWksu+'">'+HeaderWksu+'</a>';
				txtInp_wksU.setAttribute('id', '.b.'+HeaderWksu);
		// 45. Months unemployed during Census Year ---------------------------------------
			var txtInp_mnsU = document.createElement('div');
				txtInp_mnsU.setAttribute('type', 'text');
				txtInp_mnsU.setAttribute('class', 'descriptionbox');
				txtInp_mnsU.className= 'descriptionbox'; //Required for IE
				txtInp_mnsU.style.fontSize="10px";
				txtInp_mnsU.style.border='0px';
				txtInp_mnsU.innerHTML = '<a href="#" alt="'+TTEditMnsu+'" title="'+TTEditMnsu+'">'+HeaderMnsu+'</a>';
				txtInp_mnsU.setAttribute('id', '.b.'+HeaderMnsu);
		// 46. Education -----------------------------------------------------------
			var txtInp_educ = document.createElement('div');
				txtInp_educ.setAttribute('type', 'text');
				txtInp_educ.setAttribute('class', 'descriptionbox');
				txtInp_educ.className= 'descriptionbox'; //Required for IE
				txtInp_educ.style.fontSize="10px";
				txtInp_educ.style.border='0px';
				txtInp_educ.innerHTML = '<a href="#" alt="'+TTEditEduc+'" title="'+TTEditEduc+'">'+HeaderEduc+'</a>';
				txtInp_educ.setAttribute('id', '.b.'+HeaderEduc);
		// 47. Education pre 1890 Census ---------------------------------------------
			var txtInp_educpre1890 = document.createElement('div');
				txtInp_educpre1890.setAttribute('type', 'text');
				txtInp_educpre1890.setAttribute('class', 'descriptionbox');
				txtInp_educpre1890.className= 'descriptionbox'; //Required for IE
				txtInp_educpre1890.style.fontSize="10px";
				txtInp_educpre1890.style.border='0px';
				txtInp_educpre1890.innerHTML = '<a href="#" alt="'+TTEditEducpre1890+'" title="'+TTEditEducpre1890+'">'+HeaderEducpre1890+'</a>';
				txtInp_educpre1890.setAttribute('id', '.b.'+HeaderEducpre1890);
		// 48. English Spoken y/n eng_1 ----------------------------------------
			var txtInp_eng1 = document.createElement('div');
				txtInp_eng1.setAttribute('type', 'text');
				txtInp_eng1.setAttribute('class', 'descriptionbox');
				txtInp_eng1.className= 'descriptionbox'; //Required for IE
				txtInp_eng1.style.fontSize="10px";
				txtInp_eng1.style.border='0px';
				txtInp_eng1.innerHTML = '<a href="#" alt="'+TTEditEng+'" title="'+TTEditEng+'">'+HeaderEng+'</a>';
				txtInp_eng1.setAttribute('id', '.b.'+HeaderEng);
		// 49. Home Ownership  -------------------------------------------------
			var txtInp_home = document.createElement('div');
				txtInp_home.setAttribute('type', 'text');
				txtInp_home.setAttribute('class', 'descriptionbox');
				txtInp_home.className= 'descriptionbox'; //Required for IE
				txtInp_home.style.fontSize="10px";
				txtInp_home.style.border='0px';
				txtInp_home.innerHTML = '<a href="#" alt="'+TTEditHome+'" title="'+TTEditHome+'">'+HeaderHome+'</a>';
				txtInp_home.setAttribute('id', '.b.'+HeaderHome);
		// 50. Birth Place_2 -----------------------------------------------
			var txtInp_birthpl2 = document.createElement('div');
				txtInp_birthpl2.setAttribute('type', 'text');
				txtInp_birthpl2.setAttribute('class', 'descriptionbox');
				txtInp_birthpl2.className= 'descriptionbox'; //Required for IE
				txtInp_birthpl2.style.fontSize="10px";
				txtInp_birthpl2.style.border='0px';
				txtInp_birthpl2.innerHTML = '<a href="#" alt="'+TTEditBplace+'" title="'+TTEditBplace+'">'+HeaderBplace+'</a>';
				txtInp_birthpl2.setAttribute('id', '.b.'+HeaderBplace);
		// 51. Indi Birth Place_2 ---------------------------------------------
			var txtInp_ibirthpl2 = document.createElement('div');
				txtInp_ibirthpl2.setAttribute('type', 'text');
				txtInp_ibirthpl2.setAttribute('class', 'descriptionbox');
				txtInp_ibirthpl2.className= 'descriptionbox'; //Required for IE
				txtInp_ibirthpl2.style.fontSize="10px";
				txtInp_ibirthpl2.style.border='0px';
				txtInp_ibirthpl2.innerHTML = '<a href="#" alt="'+TTEditBP+'" title="'+TTEditBP+'">'+HeaderBP+'</a>';
				txtInp_ibirthpl2.setAttribute('id', '.b.'+HeaderBP);
		// 52. Born in Same Country (ENG) -----------------------------------------------
			var txtInp_bic = document.createElement('div');
				txtInp_bic.setAttribute('type', 'text');
				txtInp_bic.setAttribute('class', 'descriptionbox');
				txtInp_bic.className= 'descriptionbox'; //Required for IE
				txtInp_bic.style.fontSize="10px";
				txtInp_bic.style.border='0px';
				txtInp_bic.innerHTML = '<a href="#" alt="'+TTEditBIC+'" title="'+TTEditBIC+'">'+HeaderBIC+'</a>';
				txtInp_bic.setAttribute('id', '.b.'+HeaderBIC)
		// 53. Born outside England (SCO, IRE, WAL, FOReign ----------------------------
			var txtInp_boe = document.createElement('div');
				txtInp_boe.setAttribute('type', 'text');
				txtInp_boe.setAttribute('class', 'descriptionbox');
				txtInp_boe.className= 'descriptionbox'; //Required for IE
				txtInp_boe.style.fontSize="10px";
				txtInp_boe.style.border='0px';
				txtInp_boe.innerHTML = '<a href="#" alt="'+TTEditBOE+'" title="'+TTEditBOE+'">'+HeaderBOE+'</a>';
				txtInp_boe.setAttribute('id', '.b.'+HeaderBOE)
		// 54. Fathers Birth Place_2 ---------------------------------------------
			var txtInp_fbirthpl2 = document.createElement('div');
				txtInp_fbirthpl2.setAttribute('type', 'text');
				txtInp_fbirthpl2.setAttribute('class', 'descriptionbox');
				txtInp_fbirthpl2.className= 'descriptionbox'; //Required for IE
				txtInp_fbirthpl2.style.fontSize="10px";
				txtInp_fbirthpl2.style.border='0px';
				txtInp_fbirthpl2.innerHTML = '<a href="#" alt="'+TTEditFBP+'" title="'+TTEditFBP+'">'+HeaderFBP+'</a>';
				txtInp_fbirthpl2.setAttribute('id', '.b.'+HeaderFBP);
		// 55. Mothers Birth Place_2 ---------------------------------------------
			var txtInp_mbirthpl2 = document.createElement('div');
				txtInp_mbirthpl2.setAttribute('type', 'text');
				txtInp_mbirthpl2.setAttribute('class', 'descriptionbox');
				txtInp_mbirthpl2.className= 'descriptionbox'; //Required for IE
				txtInp_mbirthpl2.style.fontSize="10px";
				txtInp_mbirthpl2.style.border='0px';
				txtInp_mbirthpl2.innerHTML = '<a href="#" alt="'+TTEditMBP+'" title="'+TTEditMBP+'">'+HeaderMBP+'</a>';
				txtInp_mbirthpl2.setAttribute('id', '.b.'+HeaderMBP);
		// 56. Native Language ----------------------------------------------------
			var txtInp_lang = document.createElement('div');
				txtInp_lang.setAttribute('type', 'text');
				txtInp_lang.setAttribute('class', 'descriptionbox');
				txtInp_lang.className= 'descriptionbox'; //Required for IE
				txtInp_lang.style.fontSize="10px";
				txtInp_lang.style.border='0px';
				txtInp_lang.innerHTML = '<a href="#" alt="'+TTEditNL+'" title="'+TTEditNL+'">'+HeaderNL+'</a>';
				txtInp_lang.setAttribute('id', '.b.'+HeaderNL);
		// 57. Year of Immigration YOI_2 ----------------------------------------
			var txtInp_yoi2 = document.createElement('div');
				txtInp_yoi2.setAttribute('type', 'text');
				txtInp_yoi2.setAttribute('class', 'descriptionbox');
				txtInp_yoi2.className= 'descriptionbox'; //Required for IE
				txtInp_yoi2.style.fontSize="10px";
				txtInp_yoi2.style.border='0px';
				txtInp_yoi2.innerHTML = '<a href="#" alt="'+TTEditYOI+'" title="'+TTEditYOI+'">'+HeaderYOI+'</a>';
				txtInp_yoi2.setAttribute('id', '.b.'+HeaderYOI);
		// 58. Natualized or Alien_2 ----------------------------------------
			var txtInp_na2 = document.createElement('div');
				txtInp_na2.setAttribute('type', 'text');
				txtInp_na2.setAttribute('class', 'descriptionbox');
				txtInp_na2.className= 'descriptionbox'; //Required for IE
				txtInp_na2.style.fontSize="10px";
				txtInp_na2.style.border='0px';
				txtInp_na2.innerHTML = '<a href="#" alt="'+TTEditNA+'" title="'+TTEditNA+'">'+HeaderNA+'</a>';
				txtInp_na2.setAttribute('id', '.b.'+HeaderNA);
		// 59. English Spoken y/n eng_2 ----------------------------------------
			var txtInp_eng2 = document.createElement('div');
				txtInp_eng2.setAttribute('type', 'text');
				txtInp_eng2.setAttribute('class', 'descriptionbox');
				txtInp_eng2.className= 'descriptionbox'; //Required for IE
				txtInp_eng2.style.fontSize="10px";
				txtInp_eng2.style.border='0px';
				txtInp_eng2.innerHTML = '<a href="#" alt="'+TTEditEng+'" title="'+TTEditEng+'">'+HeaderEng+'</a>';
				txtInp_eng2.setAttribute('id', '.b.'+HeaderEng);
		// 60. Occupation_3 -----------------------------------------------------
			var txtInp_occu3 = document.createElement('div');
				txtInp_occu3.setAttribute('type', 'text');
				txtInp_occu3.setAttribute('class', 'descriptionbox');
				txtInp_occu3.className= 'descriptionbox'; //Required for IE
				txtInp_occu3.style.fontSize="10px";
				txtInp_occu3.style.border='0px';
				txtInp_occu3.innerHTML = '<a href="#" alt="'+TTEditOccu+'" title="'+TTEditOccu+'">'+HeaderOccu+'</a>'; 
				txtInp_occu3.setAttribute('id', '.b.'+HeaderOccu);
		// 61. Industry ind_2 ------------------------------------------------------
			var txtInp_ind2 = document.createElement('div');
				txtInp_ind2.setAttribute('type', 'text');
				txtInp_ind2.setAttribute('class', 'descriptionbox');
				txtInp_ind2.className= 'descriptionbox'; //Required for IE
				txtInp_ind2.style.fontSize="10px";
				txtInp_ind2.style.border='0px';
				txtInp_ind2.innerHTML = '<a href="#" alt="'+TTEditInd+'" title="'+TTEditInd+'">'+HeaderInd+'</a>'; 
				txtInp_ind2.setAttribute('id', '.b.'+HeaderInd);
		// 62. Employ_2 ------------------------------------------------------------
			var txtInp_emp2 = document.createElement('div');
				txtInp_emp2.setAttribute('type', 'text');
				txtInp_emp2.setAttribute('class', 'descriptionbox');
				txtInp_emp2.className= 'descriptionbox'; //Required for IE
				txtInp_emp2.style.fontSize="10px";
				txtInp_emp2.style.border='0px';
				txtInp_emp2.innerHTML = '<a href="#" alt="'+TTEditEmp+'" title="'+TTEditEmp+'">'+HeaderEmp+'</a>';
				txtInp_emp2.setAttribute('id', '.b.'+HeaderEmp);
		// 63. Infirmaties Infirm -------------------------------------------------------
			var txtInp_infirm = document.createElement('div');
				txtInp_infirm.setAttribute('type', 'text');
				txtInp_infirm.setAttribute('class', 'descriptionbox');
				txtInp_infirm.className= 'descriptionbox'; //Required for IE
				txtInp_infirm.style.fontSize="10px";
				txtInp_infirm.style.border='0px';
				txtInp_infirm.innerHTML = '<a href="#" alt="'+TTEditInfirm+'" title="'+TTEditInfirm+'">'+HeaderInfirm+'</a>';
				txtInp_infirm.setAttribute('id', '.b.'+HeaderInfirm);
		// 64. Situation (1890)  ------------------------------------------------------
			var txtInp_situ = document.createElement('div');
				txtInp_situ.setAttribute('type', 'text');
				txtInp_situ.setAttribute('class', 'descriptionbox');
				txtInp_situ.className= 'descriptionbox'; //Required for IE
				txtInp_situ.style.fontSize="10px";
				txtInp_situ.style.border='0px';
				txtInp_situ.innerHTML = '<a href="#" alt="'+TTEditSitu+'" title="'+TTEditSitu+'">'+HeaderSitu+'</a>';
				txtInp_situ.setAttribute('id', '.b.'+HeaderSitu);
		// 65. Veteran  ------------------------------------------------------
			var txtInp_vet = document.createElement('div');
				txtInp_vet.setAttribute('type', 'text');
				txtInp_vet.setAttribute('class', 'descriptionbox');
				txtInp_vet.className= 'descriptionbox'; //Required for IE
				txtInp_vet.style.fontSize="10px";
				txtInp_vet.style.border='0px';
				txtInp_vet.innerHTML = '<a href="#" alt="'+TTEditVet+'" title="'+TTEditVet+'">'+HeaderVet+'</a>';
				txtInp_vet.setAttribute('id', '.b.'+HeaderVet);
		// 66. War or Expedition ---------------------------------------------
			var txtInp_war = document.createElement('div');
				txtInp_war.setAttribute('type', 'text');
				txtInp_war.setAttribute('class', 'descriptionbox');
				txtInp_war.className= 'descriptionbox'; //Required for IE
				txtInp_war.style.fontSize="10px";
				txtInp_war.style.border='0px';
				txtInp_war.innerHTML = '<a href="#" alt="'+TTEditWar+'" title="'+TTEditWar+'">'+HeaderWar+'</a>';
				txtInp_war.setAttribute('id', '.b.'+HeaderWar);
		// 67. Infirm1910 (1910) -----------------------------------------------
			var txtInp_infirm1910 = document.createElement('div');
				txtInp_infirm1910.setAttribute('type', 'text');
				txtInp_infirm1910.setAttribute('class', 'descriptionbox');
				txtInp_infirm1910.className= 'descriptionbox'; //Required for IE
				txtInp_infirm1910.style.fontSize="10px";
				txtInp_infirm1910.style.border='0px';
				txtInp_infirm1910.innerHTML = '<a href="#" alt="'+TTEditInfirm1910+'" title="'+TTEditInfirm1910+'">'+HeaderInfirm1910+'</a>';
				txtInp_infirm1910.setAttribute('id', '.b.'+HeaderInfirm1910);
		// Hidden Items ------------------------------------------------------
/*
		// 68. DOB -------------------------------------------------
			var txt_DOB = document.createElement('div');
				txt_DOB.setAttribute('type', 'text');
				txt_DOB.setAttribute('class', 'descriptionbox');
				txt_DOB.className= 'descriptionbox'; //Required for IE
				txt_DOB.style.fontSize="10px";
				txt_DOB.style.border='0px';
				txt_DOB.innerHTML = 'DOB';
				txt_DOB.setAttribute('id', '.b.DOB');
				// txt_DOB.style.display="none";
		// 69. DOM -------------------------------------------------
			var txt_DOM = document.createElement('div');
				txt_DOM.setAttribute('type', 'text');
				txt_DOM.setAttribute('class', 'descriptionbox');
				txt_DOM.className= 'descriptionbox'; //Required for IE
				txt_DOM.style.fontSize="10px";
				txt_DOM.style.border='0px';
				txt_DOM.innerHTML = 'DOM';
				txt_DOM.setAttribute('id', '.b.DOM');
				// txt_DOM.style.display="none";
*/
		// 70. Text Del Button ------------------------------------------------- 
			var txtInp_tdel = document.createElement('div');
				txtInp_tdel.setAttribute('type', 'text');
				txtInp_tdel.setAttribute('class', 'descriptionbox');
				txtInp_tdel.className= 'descriptionbox'; //Required for IE
				txtInp_tdel.style.fontSize="10px";
				txtInp_tdel.style.border='0px';
				txtInp_tdel.innerHTML = 'Del';
				txtInp_tdel.setAttribute('id', this);
		// 71. Text Radio Button ----------------------------------------------- 
			var txtInp_tra = document.createElement('div');
				txtInp_tra.setAttribute('type', 'text');
				txtInp_tra.setAttribute('class', 'descriptionbox');
				txtInp_tra.className= 'descriptionbox'; //Required for IE
				txtInp_tra.style.fontSize="10px";
				txtInp_tra.style.border='0px';
				txtInp_tra.innerHTML = 'Ins';

		// 72. Item Number 2 -------------------------------------------------
			var txt_itemNo2 = document.createElement('div');
				txt_itemNo2.setAttribute('class', 'descriptionbox');
				txt_itemNo2.className= 'descriptionbox'; //Required for IE
				txt_itemNo2.style.border='0px';
				txt_itemNo2.innerHTML = '#';
				txt_itemNo2.setAttribute('id', '.b.Item2');
				txt_itemNo2.setAttribute('type', 'text');
				txt_itemNo2.style.fontSize="10px";

		}else{

		// **D** Define Cell Elements =======================================
			var txtcolor = "#0000FF";
		// 0. Item Number ---------------------------------------------------
			// var txt_itemNo = document.createTextNode(iteration);
			var txt_itemNo = document.createElement('div');
			// 	txt_itemNo.style.display="none";
		// 1. Indi ID -------------------------------------------------------
				if ( pid == ''){
					var txtcolor = "#000000";
					// This adds a checkbox for adding an indi id  .... to be implemented later
						var txtInp_pid = document.createElement('input');
						txtInp_pid.setAttribute('type', 'checkbox');
						if (txtInp_pid.checked!=''){
							txtInp_pid.setAttribute('value', 'no');
						}else{
							txtInp_pid.setAttribute('value', 'add');
						}
					// -------------------------------------------------------------------------
					txtInp_pid.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_1');
					txtInp_pid.setAttribute('size', '1');
					txtInp_pid.style.fontSize="11px";
				}else{
					var txtInp_pid = document.createElement('input');
						txtInp_pid.style.border='0px';
						txtInp_pid.style.background='#9999ff';
						// txtInp_pid.style.display='none'
						txtInp_pid.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_1');
						txtInp_pid.setAttribute('value', pid);
						txtInp_pid.setAttribute('size', '4');
						txtInp_pid.style.fontSize="10px";
				}
		// 2. Full Name -----------------------------------------------------
			var txtInp_nam = document.createElement('input');
				txtInp_nam.setAttribute('type', 'text');
				txtInp_nam.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_2');
				txtInp_nam.setAttribute('size', '30');
				txtInp_nam.setAttribute('value', mnam);
				txtInp_nam.style.color=txtcolor;
				txtInp_nam.style.fontSize="10px";
		// 3. Relationship_1 --------------------------------------------------
			var txtInp_label = document.createElement('input');
				txtInp_label.setAttribute('type', 'text');
				txtInp_label.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_3');
				txtInp_label.setAttribute('size', '15');
				txtInp_label.setAttribute('value', label);
				txtInp_label.style.color=txtcolor;
				txtInp_label.style.fontSize="10px";
		// 4. Conditition_1 ---------------------------------------------------
			var txtInp_cond = document.createElement('input');
				txtInp_cond.setAttribute('type', 'text');
				txtInp_cond.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_4');
				txtInp_cond.setAttribute('size', '1');
				txtInp_cond.setAttribute('value', cond);
				txtInp_cond.style.color=txtcolor;
				txtInp_cond.style.fontSize="10px";
				txtInp_cond.style.width="1em";
		// 5. Tenure ----------------------------------------------------------
			var txtInp_tenure = document.createElement('input');
				txtInp_tenure.setAttribute('type', 'text');
				txtInp_tenure.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_5');
				txtInp_tenure.setAttribute('size', '1');
				txtInp_tenure.setAttribute('maxlength', '2');
				txtInp_tenure.setAttribute('value', '');
				txtInp_tenure.style.color=txtcolor;
				txtInp_tenure.style.fontSize="10px";
				txtInp_tenure.style.width="1.6em";
		// 6. Assets_1 --------------------------------------------------------
			var txtInp_assets = document.createElement('input');
				txtInp_assets.setAttribute('type', 'text');
				txtInp_assets.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_6');
				txtInp_assets.setAttribute('size', '7');
				txtInp_assets.setAttribute('maxlength', '9');
				txtInp_assets.setAttribute('value', '');
				txtInp_assets.style.color=txtcolor;
				txtInp_assets.style.fontSize="10px";
		// 7. Age_1 -----------------------------------------------------------
			var txtInp_age = document.createElement('input');
				txtInp_age.setAttribute('type', 'text');
				txtInp_age.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_7');
				txtInp_age.setAttribute('size', '2');
				txtInp_age.setAttribute('maxlength', '4');
				txtInp_age.setAttribute('value', age);
				if (txtInp_age.value>=0) {
					txtInp_age.style.color=txtcolor;
				} else {
					//txtInp_age.style.color="red";
					txtInp_age.style.color=txtcolor;
				}
				txtInp_age.style.fontSize="10px";
				txtInp_age.style.width="2.2em";
		// 8. Race_1 -----------------------------------------------------------
			var txtInp_race = document.createElement('input');
				txtInp_race.setAttribute('type', 'text');
				txtInp_race.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_8');
				txtInp_race.setAttribute('size', '1');
				txtInp_race.setAttribute('maxlength', '1');
				txtInp_race.setAttribute('value', ''); 
				txtInp_race.style.color=txtcolor;
				txtInp_race.style.fontSize="10px";
				txtInp_race.style.width="1em";
		// 9. Sex -----------------------------------------------------------
			var txtInp_gend = document.createElement('input');
				txtInp_gend.setAttribute('type', 'text');
				txtInp_gend.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_9');
				txtInp_gend.setAttribute('size', '1');
				txtInp_gend.setAttribute('maxlength', '1');
				txtInp_gend.setAttribute('value', gend); 
				txtInp_gend.style.color=txtcolor;
				txtInp_gend.style.fontSize="10px";
				txtInp_gend.style.width="1em";
		// 10. Race_2 -----------------------------------------------------------
			var txtInp_race2 = document.createElement('input');
				txtInp_race2.setAttribute('type', 'text');
				txtInp_race2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_10');
				txtInp_race2.setAttribute('size', '1');
				txtInp_race2.setAttribute('maxlength', '1');
				txtInp_race2.setAttribute('value', ''); 
				txtInp_race2.style.color=txtcolor;
				txtInp_race2.style.fontSize="10px";
				txtInp_race2.style.width="1em";
		// 11. DOB/YOB ---------------------------------------------------------
			var txtInp_yob = document.createElement('input');
				txtInp_yob.setAttribute('type', 'text');
				txtInp_yob.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_11');
				txtInp_yob.setAttribute('size', '3');
				txtInp_yob.setAttribute('maxlength', '8');
				txtInp_yob.setAttribute('value', usdob);
				txtInp_yob.style.color=txtcolor;
				txtInp_yob.style.fontSize="10px";
				txtInp_yob.style.width="4.5em";
		// 12. Age_2 -----------------------------------------------------------
			var txtInp_age2 = document.createElement('input');
				txtInp_age2.setAttribute('type', 'text');
				txtInp_age2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_12');
				txtInp_age2.setAttribute('size', '2');
				txtInp_age2.setAttribute('maxlength', '4');
				txtInp_age2.setAttribute('value', age); 
				if (txtInp_age2.value>=0) {
					txtInp_age2.style.color=txtcolor;
				} else {
					// txtInp_age2.style.color="red";
					txtInp_age2.style.color=txtcolor;
				}
				txtInp_age2.style.fontSize="10px";
				txtInp_age2.style.width="2.0em";
		// 13. Birth month if born in Census Year ------------------------------
			var txtInp_bmth = document.createElement('input');
				txtInp_bmth.setAttribute('type', 'text');
				txtInp_bmth.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_13');
				txtInp_bmth.setAttribute('size', '1');
				txtInp_bmth.setAttribute('maxlength', '3');
				txtInp_bmth.setAttribute('value', ''); 
				txtInp_bmth.style.color=txtcolor;
				txtInp_bmth.style.fontSize="10px";
				txtInp_bmth.style.width="2.4em";
		// 14. Relationship_2 --------------------------------------------------
			var txtInp_label2 = document.createElement('input');
				txtInp_label2.setAttribute('type', 'text');
				txtInp_label2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_14');
				txtInp_label2.setAttribute('size', '15');
				txtInp_label2.setAttribute('value', label);
				txtInp_label2.style.color=txtcolor;
				txtInp_label2.style.fontSize="10px";
		// 15. Conditition_2 ---------------------------------------------------
			var txtInp_cond2 = document.createElement('input');
				txtInp_cond2.setAttribute('type', 'text');
				txtInp_cond2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_15');
				txtInp_cond2.setAttribute('size', '1');
				txtInp_cond2.setAttribute('maxlength', '1');
				txtInp_cond2.setAttribute('value', cond);
				txtInp_cond2.style.color=txtcolor;
				txtInp_cond2.style.fontSize="10px";
				txtInp_cond2.style.width="1em";
		// 16. Years Married (or Yes if married in Census Year) ----------------
			var txtInp_yrsm = document.createElement('input');
				txtInp_yrsm.setAttribute('type', 'text');
				txtInp_yrsm.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_16');
				txtInp_yrsm.setAttribute('size', '1');
				txtInp_yrsm.setAttribute('maxlength', '2');
				txtInp_yrsm.setAttribute('value', '');
				txtInp_yrsm.style.color=txtcolor;
				txtInp_yrsm.style.fontSize="10px";
				txtInp_yrsm.style.width="1.4em";
		// 17. Children Born Alive --------------------------------------------
			var txtInp_chilB = document.createElement('input');
				txtInp_chilB.setAttribute('type', 'text');
				txtInp_chilB.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_17');
				txtInp_chilB.setAttribute('size', '1');
				txtInp_chilB.setAttribute('maxlength', '2');
				txtInp_chilB.setAttribute('value', '');
				txtInp_chilB.style.color=txtcolor;
				txtInp_chilB.style.fontSize="10px";
				txtInp_chilB.style.width="1.4em";
		// 18. Children Still Living ------------------------------------------
			var txtInp_chilL = document.createElement('input');
				txtInp_chilL.setAttribute('type', 'text');
				txtInp_chilL.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_18');
				txtInp_chilL.setAttribute('size', '1');
				txtInp_chilL.setAttribute('maxlength', '2');
				txtInp_chilL.setAttribute('value', '');
				txtInp_chilL.style.color=txtcolor;
				txtInp_chilL.style.fontSize="10px";
				txtInp_chilL.style.width="1.4em";
		// 19. Children who have Died ==---------------------------------------
			var txtInp_chilD = document.createElement('input');
				txtInp_chilD.setAttribute('type', 'text');
				txtInp_chilD.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_19');
				txtInp_chilD.setAttribute('size', '1');
				txtInp_chilD.setAttribute('maxlength', '2');
				txtInp_chilD.setAttribute('value', '');
				txtInp_chilD.style.color=txtcolor;
				txtInp_chilD.style.fontSize="10px";
				txtInp_chilD.style.width="1.4em";
		// 20. Age at first marriage -------------------------------------------
			var txtInp_ageM = document.createElement('input');
				txtInp_ageM.setAttribute('type', 'text');
				txtInp_ageM.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_20');
				txtInp_ageM.setAttribute('size', '1');
				txtInp_ageM.setAttribute('maxlength', '2');
				txtInp_ageM.setAttribute('value', agemarr); 
				txtInp_ageM.style.color=txtcolor;
				txtInp_ageM.style.fontSize="10px";
				txtInp_ageM.style.width="1.4em";
		// 21. Occupation_1 ----------------------------------------------------
			var txtInp_occu = document.createElement('input');
				txtInp_occu.setAttribute('type', 'text');
				txtInp_occu.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_21');
				txtInp_occu.setAttribute('size', '22');
				txtInp_occu.setAttribute('value', ''); 
				txtInp_occu.style.color=txtcolor;
				txtInp_occu.style.fontSize="10px";
		// 22. Assets_2 -------------------------------------------
			var txtInp_assets2 = document.createElement('input');
				txtInp_assets2.setAttribute('type', 'text');
				txtInp_assets2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_22');
				txtInp_assets2.setAttribute('size', '7');
				txtInp_assets2.setAttribute('maxlength', '9');
				txtInp_assets2.setAttribute('value', '');
				txtInp_assets2.style.color=txtcolor;
				txtInp_assets2.style.fontSize="10px";
		// 23. Birth Place_1 (Full format) ---------------------------------------
			var txtInp_birthpl = document.createElement('input');
				txtInp_birthpl.setAttribute('type', 'text');
				txtInp_birthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_23');
				txtInp_birthpl.setAttribute('size', '25');
				txtInp_birthpl.setAttribute('value', birthpl); 
				txtInp_birthpl.style.color=txtcolor;
				txtInp_birthpl.style.fontSize="10px";
		// 24. Parentage - x-x = Father foreign born Y/N and Mother foreign born Y/N --
			var txtInp_parent = document.createElement('input');
				txtInp_parent.setAttribute('type', 'text');
				txtInp_parent.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_24');
				txtInp_parent.setAttribute('size', '1');
				txtInp_parent.setAttribute('maxlength', '3');
				txtInp_parent.setAttribute('value', ''); 
				txtInp_parent.style.color=txtcolor;
				txtInp_parent.style.fontSize="10px";
				txtInp_parent.style.width="2em";
		// 25. Birth month Bmth_2) (if born in Census Year) ----------------------
			var txtInp_bmth2 = document.createElement('input');
				txtInp_bmth2.setAttribute('type', 'text');
				txtInp_bmth2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_25');
				txtInp_bmth2.setAttribute('size', '1');
				txtInp_bmth2.setAttribute('maxlength', '3');
				txtInp_bmth2.setAttribute('value', ''); 
				txtInp_bmth2.style.color=txtcolor;
				txtInp_bmth2.style.fontSize="10px";
				txtInp_bmth2.style.width="2.4em";
		// 26. Married month if married in Census Year ---------------------------
			var txtInp_mmth = document.createElement('input');
				txtInp_mmth.setAttribute('type', 'text');
				txtInp_mmth.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_26');
				txtInp_mmth.setAttribute('size', '1');
				txtInp_mmth.setAttribute('maxlength', '3');
				txtInp_mmth.setAttribute('value', ''); 
				txtInp_mmth.style.color=txtcolor;
				txtInp_mmth.style.fontSize="10px";
				txtInp_mmth.style.width="2.4em";
		// 27. POB_1 Indi Birth Place_1 (Chapman format) ------------------------
			var txtInp_ibirthpl = document.createElement('input');
				txtInp_ibirthpl.setAttribute('type', 'text');
				txtInp_ibirthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_27');
				txtInp_ibirthpl.setAttribute('size', '1');
				txtInp_ibirthpl.setAttribute('maxlength', '3');
				txtInp_ibirthpl.setAttribute('value', ibirthpl); 
				txtInp_ibirthpl.style.color=txtcolor;
				txtInp_ibirthpl.style.fontSize="10px";
				txtInp_ibirthpl.style.width="2.4em";
		// 28. FPOB_1 Fathers Birth Place_1 (Chapman format) ---------------------
			var txtInp_fbirthpl = document.createElement('input');
				txtInp_fbirthpl.setAttribute('type', 'text');
				txtInp_fbirthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_28');
				txtInp_fbirthpl.setAttribute('size', '1');
				txtInp_fbirthpl.setAttribute('maxlength', '3');
				txtInp_fbirthpl.setAttribute('value', fbirthpl); 
				txtInp_fbirthpl.style.color=txtcolor;
				txtInp_fbirthpl.style.fontSize="10px";
				txtInp_fbirthpl.style.width="2.4em";
		// 29. FPOB_1 Mothers Birth Place_1 (Chapman format )---------------------
			var txtInp_mbirthpl = document.createElement('input');
				txtInp_mbirthpl.setAttribute('type', 'text');
				txtInp_mbirthpl.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_29');
				txtInp_mbirthpl.setAttribute('size', '1');
				txtInp_mbirthpl.setAttribute('maxlength', '3');
				txtInp_mbirthpl.setAttribute('value', mbirthpl); 
				txtInp_mbirthpl.style.color=txtcolor;
				txtInp_mbirthpl.style.fontSize="10px";
				txtInp_mbirthpl.style.width="2.4em";
		// 30. Years in USA ----------------------------------------------------
			var txtInp_yrsUS = document.createElement('input');
				txtInp_yrsUS.setAttribute('type', 'text');
				txtInp_yrsUS.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_30');
				txtInp_yrsUS.setAttribute('size', '1');
				txtInp_yrsUS.setAttribute('maxlength', '2');
				txtInp_yrsUS.setAttribute('value', ''); 
				txtInp_yrsUS.style.color=txtcolor;
				txtInp_yrsUS.style.fontSize="10px";
				txtInp_yrsUS.style.width="1.4em";
		// 31. Year of Immigration YOI_1 ----------------------------------------
			var txtInp_yoi1 = document.createElement('input');
				txtInp_yoi1.setAttribute('type', 'text');
				txtInp_yoi1.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_31');
				txtInp_yoi1.setAttribute('size', '2');
				txtInp_yoi1.setAttribute('maxlength', '4');
				txtInp_yoi1.setAttribute('value', ''); 
				txtInp_yoi1.style.color=txtcolor;
				txtInp_yoi1.style.fontSize="10px";
		// 32. Naturalized or Alien N-A_1 ---------------------------------------
			var txtInp_na1 = document.createElement('input');
				txtInp_na1.setAttribute('type', 'text');
				txtInp_na1.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_32');
				txtInp_na1.setAttribute('size', '1');
				txtInp_na1.setAttribute('maxlength', '1');
				txtInp_na1.setAttribute('value', ''); 
				txtInp_na1.style.color=txtcolor;
				txtInp_na1.style.fontSize="10px";
				txtInp_na1.style.width="1em";
		// 33. Year of naturalization YON ---------------------------------------
			var txtInp_yon = document.createElement('input');
				txtInp_yon.setAttribute('type', 'text');
				txtInp_yon.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_33');
				txtInp_yon.setAttribute('size', '2');
				txtInp_yon.setAttribute('maxlength', '4');
				txtInp_yon.setAttribute('value', ''); 
				txtInp_yon.style.color=txtcolor;
				txtInp_yon.style.fontSize="10px";
		// 34. English spoken, or if not, other Language spoken Eng/Lang --------
			var txtInp_englang = document.createElement('input');
				txtInp_englang.setAttribute('type', 'text');
				txtInp_englang.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_34');
				txtInp_englang.setAttribute('size', '8');
				txtInp_englang.setAttribute('maxlength', '10');
				txtInp_englang.setAttribute('value', ''); 
				txtInp_englang.style.color=txtcolor;
				txtInp_englang.style.fontSize="10px";
		// 35. Occupation_2 ----------------------------------------------------
			var txtInp_occu2 = document.createElement('input');
				txtInp_occu2.setAttribute('type', 'text');
				txtInp_occu2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_35');
				txtInp_occu2.setAttribute('size', '22');
				txtInp_occu2.setAttribute('value', ''); 
				txtInp_occu2.style.color=txtcolor;
				txtInp_occu2.style.fontSize="10px";
		// 36. Health ----------------------------------------------------------
			var txtInp_health = document.createElement('input');
				txtInp_health.setAttribute('type', 'text');
				txtInp_health.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_36');
				txtInp_health.setAttribute('size', '3');
				txtInp_health.setAttribute('maxlength', '5');
				txtInp_health.setAttribute('value', ''); 
				txtInp_health.style.color=txtcolor;
				txtInp_health.style.fontSize="10px";
		// 37. Industry_1 ------------------------------------------------------
			var txtInp_ind1 = document.createElement('input');
				txtInp_ind1.setAttribute('type', 'text');
				txtInp_ind1.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_37');
				txtInp_ind1.setAttribute('size', '22');
				txtInp_ind1.setAttribute('value', ''); 
				txtInp_ind1.style.color=txtcolor;
				txtInp_ind1.style.fontSize="10px";
		// 38. Employ_1 --------------------------------------------------------
			var txtInp_emp1 = document.createElement('input');
				txtInp_emp1.setAttribute('type', 'text');
				txtInp_emp1.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_38');
				txtInp_emp1.setAttribute('size', '12');
				txtInp_emp1.setAttribute('value', ''); 
				txtInp_emp1.style.color=txtcolor;
				txtInp_emp1.style.fontSize="10px";
		// 39. Employer EmR ----------------------------------------------------
			var txtInp_emR = document.createElement('input');
				txtInp_emR.setAttribute('type', 'text');
				txtInp_emR.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_39');
				txtInp_emR.setAttribute('size', '1');
				txtInp_emR.setAttribute('maxlength', '1');
				txtInp_emR.setAttribute('value', ''); 
				txtInp_emR.style.color=txtcolor;
				txtInp_emR.style.fontSize="10px";
				txtInp_emR.style.width="1em";
		// 40. Employed EmD ----------------------------------------------------
			var txtInp_emD = document.createElement('input');
				txtInp_emD.setAttribute('type', 'text');
				txtInp_emD.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_40');
				txtInp_emD.setAttribute('size', '1');
				txtInp_emD.setAttribute('maxlength', '1');
				txtInp_emD.setAttribute('value', ''); 
				txtInp_emD.style.color=txtcolor;
				txtInp_emD.style.fontSize="10px";
				txtInp_emD.style.width="1em";
		// 41. Months employed -------------------------------------------------
			var txtInp_mnsE = document.createElement('input');
				txtInp_mnsE.setAttribute('type', 'text');
				txtInp_mnsE.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_41');
				txtInp_mnsE.setAttribute('size', '1');
				txtInp_mnsE.setAttribute('maxlength', '2');
				txtInp_mnsE.setAttribute('value', ''); 
				txtInp_mnsE.style.color=txtcolor;
				txtInp_mnsE.style.fontSize="10px";
				txtInp_mnsE.style.width="1.4em";
		// 42. Working at Home WH ----------------------------------------------
			var txtInp_emH = document.createElement('input');
				txtInp_emH.setAttribute('type', 'text');
				txtInp_emH.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_42');
				txtInp_emH.setAttribute('size', '1');
				txtInp_emH.setAttribute('maxlength', '1');
				txtInp_emH.setAttribute('value', ''); 
				txtInp_emH.style.color=txtcolor;
				txtInp_emH.style.fontSize="10px";
				txtInp_emH.style.width="1em";
		// 43. Not Employed EmN ------------------------------------------------
			var txtInp_emN = document.createElement('input');
				txtInp_emN.setAttribute('type', 'text');
				txtInp_emN.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_43');
				txtInp_emN.setAttribute('size', '1');
				txtInp_emN.setAttribute('maxlength', '1');
				txtInp_emN.setAttribute('value', ''); 
				txtInp_emN.style.color=txtcolor;
				txtInp_emN.style.fontSize="10px";
				txtInp_emN.style.width="1em";
		// 44. Weeks unemployed ------------------------------------------------
			var txtInp_wksU = document.createElement('input');
				txtInp_wksU.setAttribute('type', 'text');
				txtInp_wksU.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_44');
				txtInp_wksU.setAttribute('size', '1');
				txtInp_wksU.setAttribute('maxlength', '2');
				txtInp_wksU.setAttribute('value', ''); 
				txtInp_wksU.style.color=txtcolor;
				txtInp_wksU.style.fontSize="10px";
				txtInp_wksU.style.width="1.4em";
		// 45. Months unemployed -----------------------------------------------
			var txtInp_mnsU = document.createElement('input');
				txtInp_mnsU.setAttribute('type', 'text');
				txtInp_mnsU.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_45');
				txtInp_mnsU.setAttribute('size', '1');
				txtInp_mnsU.setAttribute('maxlength', '2');
				txtInp_mnsU.setAttribute('value', ''); 
				txtInp_mnsU.style.color=txtcolor;
				txtInp_mnsU.style.fontSize="10px";
				txtInp_mnsU.style.width="1.4em";
		// 46. Education - xxx = School/Able to Read/Able to Write -------------
			var txtInp_educ = document.createElement('input');
				txtInp_educ.setAttribute('type', 'text');
				txtInp_educ.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_46');
				txtInp_educ.setAttribute('size', '1');
				txtInp_educ.setAttribute('maxlength', '3');
				txtInp_educ.setAttribute('value', ''); 
				txtInp_educ.style.color=txtcolor;
				txtInp_educ.style.fontSize="10px";
				txtInp_educ.style.width="1.8em";
		// 47. Education pre 1890 Census - xxx = School/Cannot Read/Cannot Write ----
			var txtInp_educpre1890 = document.createElement('input');
				txtInp_educpre1890.setAttribute('type', 'text');
				txtInp_educpre1890.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_47');
				txtInp_educpre1890.setAttribute('size', '1');
				txtInp_educpre1890.setAttribute('maxlength', '3');
				txtInp_educpre1890.setAttribute('value', ''); 
				txtInp_educpre1890.style.color=txtcolor;
				txtInp_educpre1890.style.fontSize="10px";
				txtInp_educpre1890.width="1.8em";
		// 48. English Spoken?_1 eng_1 -----------------------------------------
			var txtInp_eng1 = document.createElement('input');
				txtInp_eng1.setAttribute('type', 'text');
				txtInp_eng1.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_48');
				txtInp_eng1.setAttribute('size', '1');
				txtInp_eng1.setAttribute('maxlength', '1');
				txtInp_eng1.setAttribute('value', ''); 
				txtInp_eng1.style.color=txtcolor;
				txtInp_eng1.style.fontSize="10px";
				txtInp_eng1.style.width="1em";
		// 49. Home Ownership - x-x-x-xxxx = O/R-F/M-F/H-#### = Owned/Rented-Free/Mortgaged-Farm/House-Farm Schedule number ----
			var txtInp_home = document.createElement('input');
				txtInp_home.setAttribute('type', 'text');
				txtInp_home.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_49');
				txtInp_home.setAttribute('size', '7');
				txtInp_home.setAttribute('maxlength', '12');
				txtInp_home.setAttribute('value', '');
				txtInp_home.style.color=txtcolor;
				txtInp_home.style.fontSize="10px";
		// 50. Birth Place_2 (full format) -------------------------------------
			var txtInp_birthpl2 = document.createElement('input');
				txtInp_birthpl2.setAttribute('type', 'text');
				txtInp_birthpl2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_50');
				txtInp_birthpl2.setAttribute('size', '25');
				txtInp_birthpl2.setAttribute('value', birthpl); 
				txtInp_birthpl2.style.color=txtcolor;
				txtInp_birthpl2.style.fontSize="10px";
		// 51. POB_2 Indi Birth Place_2 ----------------------------------------
			var txtInp_ibirthpl2 = document.createElement('input');
				txtInp_ibirthpl2.setAttribute('type', 'text');
				txtInp_ibirthpl2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_51');
				txtInp_ibirthpl2.setAttribute('size', '1');
				txtInp_ibirthpl2.setAttribute('maxlength', '3');
				txtInp_ibirthpl2.setAttribute('value', ibirthpl); 
				txtInp_ibirthpl2.style.color=txtcolor;
				txtInp_ibirthpl2.style.fontSize="10px";
				txtInp_ibirthpl2.style.width="2.4em";
		// 52. Born in Same Country BIC ----------------------------------------
			var txtInp_bic = document.createElement('input');
				txtInp_bic.setAttribute('type', 'text');
				txtInp_bic.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_52');
				txtInp_bic.setAttribute('size', '1');
				txtInp_bic.setAttribute('maxlength', '1');
				txtInp_bic.setAttribute('value', ''); 
				txtInp_bic.style.color=txtcolor;
				txtInp_bic.style.fontSize="10px";
				txtInp_bic.style.width="1em";
		// 53. Born outside England BOE ----------------------------------------
			var txtInp_boe = document.createElement('input');
				txtInp_boe.setAttribute('type', 'text');
				txtInp_boe.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_53');
				txtInp_boe.setAttribute('size', '1');
				txtInp_boe.setAttribute('maxlength', '3');
				txtInp_boe.setAttribute('value', ''); 
				txtInp_boe.style.color=txtcolor;
				txtInp_boe.style.fontSize="10px";
				txtInp_boe.style.width="2.4em";
		// 54. FPOB_2 Birth Place_2 --------------------------------------------
			var txtInp_fbirthpl2 = document.createElement('input');
				txtInp_fbirthpl2.setAttribute('type', 'text');
				txtInp_fbirthpl2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_54');
				txtInp_fbirthpl2.setAttribute('size', '1');
				txtInp_fbirthpl2.setAttribute('maxlength', '3');
				txtInp_fbirthpl2.setAttribute('value', fbirthpl); 
				txtInp_fbirthpl2.style.color=txtcolor;
				txtInp_fbirthpl2.style.fontSize="10px";
				txtInp_fbirthpl2.style.width="2.4em";
		// 55. MPOB_2 Birth Place_2 --------------------------------------------
			var txtInp_mbirthpl2 = document.createElement('input');
				txtInp_mbirthpl2.setAttribute('type', 'text');
				txtInp_mbirthpl2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_55');
				txtInp_mbirthpl2.setAttribute('size', '1');
				txtInp_mbirthpl2.setAttribute('maxlength', '3');
				txtInp_mbirthpl2.setAttribute('value', mbirthpl); 
				txtInp_mbirthpl2.style.color=txtcolor;
				txtInp_mbirthpl2.style.fontSize="10px";
				txtInp_mbirthpl2.style.width="2.4em";
		// 56. Native Language -------------------------------------------------
			var txtInp_lang = document.createElement('input');
				txtInp_lang.setAttribute('type', 'text');
				txtInp_lang.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_56');
				txtInp_lang.setAttribute('size', '8');
				txtInp_lang.setAttribute('maxlength', '10');
				txtInp_lang.setAttribute('value', ''); 
				txtInp_lang.style.color=txtcolor;
				txtInp_lang.style.fontSize="10px";
		// 57. Year of Immigration YOI_2 ---------------------------------------
			var txtInp_yoi2 = document.createElement('input');
				txtInp_yoi2.setAttribute('type', 'text');
				txtInp_yoi2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_57');
				txtInp_yoi2.setAttribute('size', '2');
				txtInp_yoi2.setAttribute('maxlength', '4');
				txtInp_yoi2.setAttribute('value', ''); 
				txtInp_yoi2.style.color=txtcolor;
				txtInp_yoi2.style.fontSize="10px";
		// 58. Naturalized or Alien N-A_2 --------------------------------------
			var txtInp_na2 = document.createElement('input');
				txtInp_na2.setAttribute('type', 'text');
				txtInp_na2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_58');
				txtInp_na2.setAttribute('size', '1');
				txtInp_na2.setAttribute('maxlength', '1');
				txtInp_na2.setAttribute('value', ''); 
				txtInp_na2.style.color=txtcolor;
				txtInp_na2.style.fontSize="10px";
				txtInp_na2.style.width="1em";
		// 59. English Spoken?_2 eng_2 -----------------------------------------
			var txtInp_eng2 = document.createElement('input');
				txtInp_eng2.setAttribute('type', 'text');
				txtInp_eng2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_59');
				txtInp_eng2.setAttribute('size', '1');
				txtInp_eng2.setAttribute('maxlength', '1');
				txtInp_eng2.setAttribute('value', ''); 
				txtInp_eng2.style.color=txtcolor;
				txtInp_eng2.style.fontSize="10px";
				txtInp_eng2.style.width="1em";
		// 60. Occupation_3 ----------------------------------------------------
			var txtInp_occu3 = document.createElement('input');
				txtInp_occu3.setAttribute('type', 'text');
				txtInp_occu3.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_60');
				txtInp_occu3.setAttribute('size', '22');
				txtInp_occu3.setAttribute('value', ''); 
				txtInp_occu3.style.color=txtcolor;
				txtInp_occu3.style.fontSize="10px";
		// 61. Industry_2 -----------------------------------------------------
			var txtInp_ind2 = document.createElement('input');
				txtInp_ind2.setAttribute('type', 'text');
				txtInp_ind2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_61');
				txtInp_ind2.setAttribute('size', '22');
				txtInp_ind2.setAttribute('value', ''); 
				txtInp_ind2.style.color=txtcolor;
				txtInp_ind2.style.fontSize="10px";
		// 62. Employ_2 -------------------------------------------------------
			var txtInp_emp2 = document.createElement('input');
				txtInp_emp2.setAttribute('type', 'text');
				txtInp_emp2.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_62');
				txtInp_emp2.setAttribute('size', '12');
				txtInp_emp2.setAttribute('value', ''); 
				txtInp_emp2.style.color=txtcolor;
				txtInp_emp2.style.fontSize="10px";
		// 63. Infirmaties ----------------------------------------------------
			var txtInp_infirm = document.createElement('input');
				txtInp_infirm.setAttribute('type', 'text');
				txtInp_infirm.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_63');
				txtInp_infirm.setAttribute('size', '3');
				txtInp_infirm.setAttribute('maxlength', '4');
				txtInp_infirm.setAttribute('value', ''); 
				txtInp_infirm.style.color=txtcolor;
				txtInp_infirm.style.fontSize="10px";
				txtInp_infirm.style.width="2.3em";
		// 64. Health / Situation = Disease-Infirmaties-Convict,Pauper etc ----
			var txtInp_situ = document.createElement('input');
				txtInp_situ.setAttribute('type', 'text');
				txtInp_situ.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_64');
				txtInp_situ.setAttribute('size', '12');
				txtInp_situ.setAttribute('value', ''); 
				txtInp_situ.style.color=txtcolor;
				txtInp_situ.style.fontSize="10px";
		// 65. Veteran ? ------------------------------------------------------
			var txtInp_vet = document.createElement('input');
				txtInp_vet.setAttribute('type', 'text');
				txtInp_vet.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_65');
				txtInp_vet.setAttribute('size', '1');
				txtInp_vet.setAttribute('maxlength', '1');
				txtInp_vet.setAttribute('value', ''); 
				txtInp_vet.style.color=txtcolor;
				txtInp_vet.style.fontSize="10px";
				txtInp_vet.style.width="1em";
		// 66. War or Expedition ----------------------------------------------
			var txtInp_war = document.createElement('input');
				txtInp_war.setAttribute('type', 'text');
				txtInp_war.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_66');
				txtInp_war.setAttribute('size', '8');
				txtInp_war.setAttribute('value', ''); 
				txtInp_war.style.color=txtcolor;
				txtInp_war.style.fontSize="10px";
		// 67. Infirmaties (Census 1910) - x-x = Blind (both eyes) Y/N - Deaf and dumb Y/N ----
			var txtInp_infirm1910 = document.createElement('input');
				txtInp_infirm1910.setAttribute('type', 'text');
				txtInp_infirm1910.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_67');
				txtInp_infirm1910.setAttribute('size', '1');
				txtInp_infirm1910.setAttribute('maxlength', '2');
				txtInp_infirm1910.setAttribute('value', ''); 
				txtInp_infirm1910.style.color=txtcolor;
				txtInp_infirm1910.style.fontSize="10px";
				txtInp_infirm1910.style.width="1.4em";
		// Hidden --------------------------------------------------------------------------
		// 68. DOB ------------------------------------------------------------
			var txtInp_DOB = document.createElement('input');
				txtInp_DOB.setAttribute('type', 'text');
				txtInp_DOB.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_68');
				txtInp_DOB.setAttribute('size', '8');
				txtInp_DOB.setAttribute('maxlength', '20');
				txtInp_DOB.setAttribute('value', dob); 
				txtInp_DOB.style.color=txtcolor;
				txtInp_DOB.style.fontSize="10px";
				txtInp_DOB.style.width="5.4em";
				txtInp_DOB.type = "hidden";
		// 69. DOM ------------------------------------------------------------
			var txtInp_DOM = document.createElement('input');
				txtInp_DOM.setAttribute('type', 'text');
				txtInp_DOM.setAttribute('id', INPUT_NAME_PREFIX + iteration + '_69');
				txtInp_DOM.setAttribute('size', '8');
				txtInp_DOM.setAttribute('maxlength', '20');
				txtInp_DOM.setAttribute('value', dom); 
				txtInp_DOM.style.color=txtcolor;
				txtInp_DOM.style.fontSize="10px";
				txtInp_DOM.style.width="1.4em";
				txtInp_DOM.type = "hidden";
		// 70. Delete Row Button ----------------------------------------------
			var btnEl = document.createElement('input');
				btnEl.setAttribute('type', 'button');
				btnEl.setAttribute('value', 'x');
				btnEl.onclick = function () {deleteCurrentRow(this)};
		// 71. Insert row Radio button ----------------------------------------
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
		// 72. Item Number ----------------------------------------------------
			var txt_itemNo2 = document.createTextNode(iteration);
		}
		// Not visible but used for row re-order process ----------------------
			var cbEl = document.createElement('input');
				cbEl.type = "hidden";


		// **E** Append appropriate Cell elements to each cell ================
		cell_[0].appendChild(txt_itemNo);			// Item Number
		cell_[1].appendChild(txtInp_pid);			// Indi ID
		cell_[2].appendChild(txtInp_nam);			// Name
		cell_[3].appendChild(txtInp_label);			// Relationship_1
		cell_[4].appendChild(txtInp_cond);			// Condition_1
		cell_[5].appendChild(txtInp_tenure);		// Tenure
		cell_[6].appendChild(txtInp_assets);		// Assets_1
		cell_[7].appendChild(txtInp_age);			// Age_1
		cell_[8].appendChild(txtInp_race);			// Race_1
		cell_[9].appendChild(txtInp_gend);			// Sex
		
		cell_[10].appendChild(txtInp_race2);		// Race_2
		cell_[11].appendChild(txtInp_yob);			// DOB/YOB
		cell_[12].appendChild(txtInp_age2);			// Age_2
		cell_[13].appendChild(txtInp_bmth);			// Birth Month
		cell_[14].appendChild(txtInp_label2);		// Relationship_2
		cell_[15].appendChild(txtInp_cond2);		// Condition_2
		cell_[16].appendChild(txtInp_yrsm);			// Years Married
		cell_[17].appendChild(txtInp_chilB);		// Children Born Alive
		cell_[18].appendChild(txtInp_chilL);		// Children Still Living
		cell_[19].appendChild(txtInp_chilD);		// Children who have Died
		
		cell_[20].appendChild(txtInp_ageM);			// Age st first marriage
		cell_[21].appendChild(txtInp_occu);			// Occupation_1
		cell_[22].appendChild(txtInp_assets2);		// Assets_2
		cell_[23].appendChild(txtInp_birthpl);		// Place of Birth_1
		cell_[24].appendChild(txtInp_parent);		// Parentage
		cell_[25].appendChild(txtInp_bmth2);		// Month if born in Census Year - bmth2
		cell_[26].appendChild(txtInp_mmth);			// Month if married in Census Year
		cell_[27].appendChild(txtInp_ibirthpl);		// Indis POB_1
		cell_[28].appendChild(txtInp_fbirthpl);		// Father FPOB_1
		cell_[29].appendChild(txtInp_mbirthpl);		// Mother MPOB_1
		
		cell_[30].appendChild(txtInp_yrsUS);		// Years in USA
		cell_[31].appendChild(txtInp_yoi1);			// Year of Immigration YOI_1
		cell_[32].appendChild(txtInp_na1);			// Naturalized or Alien N-A_1
		cell_[33].appendChild(txtInp_yon);			// Year of Naturalization YON
		cell_[34].appendChild(txtInp_englang);		// English spoken, if not, Other Language spoken Eng/Lang
		cell_[35].appendChild(txtInp_occu2);		// Occupation_2
		cell_[36].appendChild(txtInp_health);		// Health - 5 parameters x--xx etc
		cell_[37].appendChild(txtInp_ind1);			// Industry ind_1
		cell_[38].appendChild(txtInp_emp1);			// Employ_1
		cell_[39].appendChild(txtInp_emR);			// Employer EmR
		
		cell_[40].appendChild(txtInp_emD);			// Employed EmD
		cell_[41].appendChild(txtInp_mnsE);			// Months employed during Census Year 
		cell_[42].appendChild(txtInp_emH);			// Working At Home WH
		cell_[43].appendChild(txtInp_emN);			// Not Employed EmN
		cell_[44].appendChild(txtInp_wksU);			// Weeks unemployed during Census Year 
		cell_[45].appendChild(txtInp_mnsU);			// Months unemployed during Census Year 
		cell_[46].appendChild(txtInp_educ);			// Education 3 parameters Sch-Read-Write  -xx
		cell_[47].appendChild(txtInp_educpre1890);	// Education (pre 1890 Census) - 3 parameters = Sch, Cannot Read, Cannot Write  -xx
		cell_[48].appendChild(txtInp_eng1);			// English spoken Y/N  eng_1
		cell_[49].appendChild(txtInp_home);			// Home Ownership x-x-x-xxxx = Owned/Rented - Free/Morgaged - Farm/House - Farm Sched #
		
		cell_[50].appendChild(txtInp_birthpl2);		// Birth Place_2
		cell_[51].appendChild(txtInp_ibirthpl2);	// Indis POB_2
		cell_[52].appendChild(txtInp_bic);			// Born in County (UK)
		cell_[53].appendChild(txtInp_boe);			// Born outside England (UK)
		cell_[54].appendChild(txtInp_fbirthpl2);	// Fathers FPOB_2
		cell_[55].appendChild(txtInp_mbirthpl2);	// Mothers MPOB_2
		cell_[56].appendChild(txtInp_lang);			// Mother Tongue lang
		cell_[57].appendChild(txtInp_yoi2);			// Year of Immigration YOI_2
		cell_[58].appendChild(txtInp_na2);			// Naturalized or Alien N-A_2
		cell_[59].appendChild(txtInp_eng2);			// English spoken Y/N  eng_2
		
		cell_[60].appendChild(txtInp_occu3);		// Occupation_3
		cell_[61].appendChild(txtInp_ind2);			// Industry ind_2
		cell_[62].appendChild(txtInp_emp2);			// Employ_2
		cell_[63].appendChild(txtInp_infirm);		// Infirmaties - up to 5 parameters x--xx etc
		cell_[64].appendChild(txtInp_situ);			// Health Situation 1890 - Disease, Infimaties, Convict, Pauper etc
		cell_[65].appendChild(txtInp_vet);			// Veteran ?
		cell_[66].appendChild(txtInp_war);			// War or expedition 
		cell_[67].appendChild(txtInp_infirm1910);	// Infirmaties - xx = Blind (both eyes) Y/N/-, Deaf and Dumb Y/N/-
		
		// Hidden Cells ----------------------------------------------------------------------------------------------
		cell_[68].appendChild(txtInp_DOB);			// Date of Birth
		cell_[69].appendChild(txtInp_DOM);			// Date of Marriage

		if (iteration == 0) {
			cell_tdel.appendChild(txtInp_tdel);		// Text Del
			cell_tra.appendChild(txtInp_tra);		// Text Ins
		}else{
			cell_del.appendChild(btnEl);			// Onclick = Delete Row
			cell_ra.appendChild(raEl);				// Radio button used for inserting a row, rather than adding at end of table)
		}

		// cell_71.appendChild(txt_itemNo2);			// Text Item Number
// alert("2. - "+nam);

		// **F** Pass in the elements to be referenced later ================
		// Store the myRow object in each row
		row.myRow = new myRowObject(	txt_itemNo, txtInp_pid, txtInp_nam, txtInp_label, txtInp_cond, txtInp_tenure, txtInp_assets, txtInp_age, txtInp_race, txtInp_gend, 
										txtInp_race2, txtInp_yob, txtInp_age2, txtInp_bmth, txtInp_label2, txtInp_cond2, txtInp_yrsm, txtInp_chilB, txtInp_chilL, txtInp_chilD, 
										txtInp_ageM, txtInp_occu, txtInp_assets2, txtInp_birthpl, txtInp_parent, txtInp_bmth2, txtInp_mmth, txtInp_ibirthpl, txtInp_fbirthpl, txtInp_mbirthpl, 
										txtInp_yrsUS, txtInp_yoi1, txtInp_na1, txtInp_yon, txtInp_englang, txtInp_occu2, txtInp_health, txtInp_ind1, txtInp_emp1, txtInp_emR, 
										txtInp_emD, txtInp_mnsE, txtInp_emH, txtInp_emN, txtInp_wksU, txtInp_mnsU, txtInp_educ, txtInp_educpre1890, txtInp_eng1, txtInp_home,  
										txtInp_birthpl2, txtInp_ibirthpl2, txtInp_bic, txtInp_boe, txtInp_fbirthpl2, txtInp_mbirthpl2, txtInp_lang, txtInp_yoi2, txtInp_na2, txtInp_eng2, 
										txtInp_occu3, txtInp_ind2, txtInp_emp2, txtInp_infirm, txtInp_situ, txtInp_vet, txtInp_war, txtInp_infirm1910, txtInp_DOB, txtInp_DOM, 
										cbEl, raEl, txt_itemNo2
									);
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
		preview();
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
		for (var i=0; i<rowObjArray.length; i++) {  // i set to 1 to avoid table header row of number 0
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
				tbl.tBodies[0].rows[i].myRow.zero.data			 = count; // text - (left column item number)
				tbl.tBodies[0].rows[i].myRow.seventytwo.data	 = count; // text - (right column item number)
				
				// ------------------------------------
				tbl.tBodies[0].rows[i].myRow.one.id				 = INPUT_NAME_PREFIX + count + '_1';  // input text
				tbl.tBodies[0].rows[i].myRow.two.id 			 = INPUT_NAME_PREFIX + count + '_2';  // input text
				tbl.tBodies[0].rows[i].myRow.three.id			 = INPUT_NAME_PREFIX + count + '_3';  // input text
				tbl.tBodies[0].rows[i].myRow.four.id			 = INPUT_NAME_PREFIX + count + '_4';  // input text
				tbl.tBodies[0].rows[i].myRow.five.id			 = INPUT_NAME_PREFIX + count + '_5';  // input text
				tbl.tBodies[0].rows[i].myRow.six.id				 = INPUT_NAME_PREFIX + count + '_6';  // input text
				tbl.tBodies[0].rows[i].myRow.seven.id			 = INPUT_NAME_PREFIX + count + '_7';  // input text
				tbl.tBodies[0].rows[i].myRow.eight.id			 = INPUT_NAME_PREFIX + count + '_8';  // input text
				tbl.tBodies[0].rows[i].myRow.nine.id			 = INPUT_NAME_PREFIX + count + '_9';  // input text
				
				tbl.tBodies[0].rows[i].myRow.ten.id				 = INPUT_NAME_PREFIX + count + '_10'; // input text
				tbl.tBodies[0].rows[i].myRow.eleven.id			 = INPUT_NAME_PREFIX + count + '_11'; // input text
				tbl.tBodies[0].rows[i].myRow.twelve.id			 = INPUT_NAME_PREFIX + count + '_12'; // input text
				tbl.tBodies[0].rows[i].myRow.thirteen.id		 = INPUT_NAME_PREFIX + count + '_13';  // input text
				tbl.tBodies[0].rows[i].myRow.fourteen.id		 = INPUT_NAME_PREFIX + count + '_14';  // input text
				tbl.tBodies[0].rows[i].myRow.fifteen.id			 = INPUT_NAME_PREFIX + count + '_15';  // input text
				tbl.tBodies[0].rows[i].myRow.sixteen.id			 = INPUT_NAME_PREFIX + count + '_16';  // input text
				tbl.tBodies[0].rows[i].myRow.seventeen.id		 = INPUT_NAME_PREFIX + count + '_17';  // input text
				tbl.tBodies[0].rows[i].myRow.eighteen.id		 = INPUT_NAME_PREFIX + count + '_18';  // input text
				tbl.tBodies[0].rows[i].myRow.nineteen.id		 = INPUT_NAME_PREFIX + count + '_19';  // input text
				
				tbl.tBodies[0].rows[i].myRow.twenty.id			 = INPUT_NAME_PREFIX + count + '_20'; // input text
				tbl.tBodies[0].rows[i].myRow.twentyone.id		 = INPUT_NAME_PREFIX + count + '_21';  // input text
				tbl.tBodies[0].rows[i].myRow.twentytwo.id 		 = INPUT_NAME_PREFIX + count + '_22';  // input text
				tbl.tBodies[0].rows[i].myRow.twentythree.id		 = INPUT_NAME_PREFIX + count + '_23';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyfour.id		 = INPUT_NAME_PREFIX + count + '_24';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyfive.id		 = INPUT_NAME_PREFIX + count + '_25';  // input text
				tbl.tBodies[0].rows[i].myRow.twentysix.id		 = INPUT_NAME_PREFIX + count + '_26';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyseven.id		 = INPUT_NAME_PREFIX + count + '_27';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyeight.id		 = INPUT_NAME_PREFIX + count + '_28';  // input text
				tbl.tBodies[0].rows[i].myRow.twentynine.id		 = INPUT_NAME_PREFIX + count + '_29';  // input text
				
				tbl.tBodies[0].rows[i].myRow.thirty.id			 = INPUT_NAME_PREFIX + count + '_30'; // input text
				tbl.tBodies[0].rows[i].myRow.thirtyone.id		 = INPUT_NAME_PREFIX + count + '_31';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtytwo.id		 = INPUT_NAME_PREFIX + count + '_32';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtythree.id		 = INPUT_NAME_PREFIX + count + '_33';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyfour.id		 = INPUT_NAME_PREFIX + count + '_34';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyfive.id		 = INPUT_NAME_PREFIX + count + '_35';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtysix.id		 = INPUT_NAME_PREFIX + count + '_36';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyseven.id		 = INPUT_NAME_PREFIX + count + '_37';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyeight.id		 = INPUT_NAME_PREFIX + count + '_38';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtynine.id		 = INPUT_NAME_PREFIX + count + '_39';  // input text
				
				tbl.tBodies[0].rows[i].myRow.forty.id			 = INPUT_NAME_PREFIX + count + '_40'; // input text
				tbl.tBodies[0].rows[i].myRow.fortyone.id		 = INPUT_NAME_PREFIX + count + '_41';  // input text
				tbl.tBodies[0].rows[i].myRow.fortytwo.id		 = INPUT_NAME_PREFIX + count + '_42';  // input text
				tbl.tBodies[0].rows[i].myRow.fortythree.id		 = INPUT_NAME_PREFIX + count + '_43';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyfour.id		 = INPUT_NAME_PREFIX + count + '_44';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyfive.id		 = INPUT_NAME_PREFIX + count + '_45';  // input text
				tbl.tBodies[0].rows[i].myRow.fortysix.id		 = INPUT_NAME_PREFIX + count + '_46';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyseven.id		 = INPUT_NAME_PREFIX + count + '_47';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyeight.id		 = INPUT_NAME_PREFIX + count + '_48';  // input text
				tbl.tBodies[0].rows[i].myRow.fortynine.id		 = INPUT_NAME_PREFIX + count + '_49';  // input text
				
				tbl.tBodies[0].rows[i].myRow.fifty.id			 = INPUT_NAME_PREFIX + count + '_50';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyone.id		 = INPUT_NAME_PREFIX + count + '_51';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftytwo.id		 = INPUT_NAME_PREFIX + count + '_52';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftythree.id		 = INPUT_NAME_PREFIX + count + '_53';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyfour.id		 = INPUT_NAME_PREFIX + count + '_54';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyfive.id		 = INPUT_NAME_PREFIX + count + '_55';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftysix.id		 = INPUT_NAME_PREFIX + count + '_56';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyseven.id		 = INPUT_NAME_PREFIX + count + '_57';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyeight.id		 = INPUT_NAME_PREFIX + count + '_58';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftynine.id		 = INPUT_NAME_PREFIX + count + '_59';  // input text
				
				tbl.tBodies[0].rows[i].myRow.sixty.id			 = INPUT_NAME_PREFIX + count + '_60';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyone.id		 = INPUT_NAME_PREFIX + count + '_61';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtytwo.id		 = INPUT_NAME_PREFIX + count + '_62';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtythree.id		 = INPUT_NAME_PREFIX + count + '_63';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyfour.id		 = INPUT_NAME_PREFIX + count + '_64';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyfive.id		 = INPUT_NAME_PREFIX + count + '_65';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtysix.id		 = INPUT_NAME_PREFIX + count + '_66';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyseven.id		 = INPUT_NAME_PREFIX + count + '_67';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyeight.id		 = INPUT_NAME_PREFIX + count + '_68';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtynine.id		 = INPUT_NAME_PREFIX + count + '_69';  // input text
				// ------------------------------------
				
				// ------------------------------------
				tbl.tBodies[0].rows[i].myRow.one.name			 = INPUT_NAME_PREFIX + count + '_1';  // input text
				tbl.tBodies[0].rows[i].myRow.two.name 			 = INPUT_NAME_PREFIX + count + '_2';  // input text
				tbl.tBodies[0].rows[i].myRow.three.name			 = INPUT_NAME_PREFIX + count + '_3';  // input text
				tbl.tBodies[0].rows[i].myRow.four.name			 = INPUT_NAME_PREFIX + count + '_4';  // input text
				tbl.tBodies[0].rows[i].myRow.five.name			 = INPUT_NAME_PREFIX + count + '_5';  // input text
				tbl.tBodies[0].rows[i].myRow.six.name			 = INPUT_NAME_PREFIX + count + '_6';  // input text
				tbl.tBodies[0].rows[i].myRow.seven.name			 = INPUT_NAME_PREFIX + count + '_7';  // input text
				tbl.tBodies[0].rows[i].myRow.eight.name			 = INPUT_NAME_PREFIX + count + '_8';  // input text
				tbl.tBodies[0].rows[i].myRow.nine.name			 = INPUT_NAME_PREFIX + count + '_9';  // input text
				
				tbl.tBodies[0].rows[i].myRow.ten.name			 = INPUT_NAME_PREFIX + count + '_10'; // input text
				tbl.tBodies[0].rows[i].myRow.eleven.name		 = INPUT_NAME_PREFIX + count + '_11'; // input text
				tbl.tBodies[0].rows[i].myRow.twelve.name		 = INPUT_NAME_PREFIX + count + '_12'; // input text
				tbl.tBodies[0].rows[i].myRow.thirteen.name		 = INPUT_NAME_PREFIX + count + '_13';  // input text
				tbl.tBodies[0].rows[i].myRow.fourteen.name		 = INPUT_NAME_PREFIX + count + '_14';  // input text
				tbl.tBodies[0].rows[i].myRow.fifteen.name		 = INPUT_NAME_PREFIX + count + '_15';  // input text
				tbl.tBodies[0].rows[i].myRow.sixteen.name		 = INPUT_NAME_PREFIX + count + '_16';  // input text
				tbl.tBodies[0].rows[i].myRow.seventeen.name		 = INPUT_NAME_PREFIX + count + '_17';  // input text
				tbl.tBodies[0].rows[i].myRow.eighteen.name		 = INPUT_NAME_PREFIX + count + '_18';  // input text
				tbl.tBodies[0].rows[i].myRow.nineteen.name		 = INPUT_NAME_PREFIX + count + '_19';  // input text
				
				tbl.tBodies[0].rows[i].myRow.twenty.name		 = INPUT_NAME_PREFIX + count + '_20'; // input text
				tbl.tBodies[0].rows[i].myRow.twentyone.name		 = INPUT_NAME_PREFIX + count + '_21';  // input text
				tbl.tBodies[0].rows[i].myRow.twentytwo.name		 = INPUT_NAME_PREFIX + count + '_22';  // input text
				tbl.tBodies[0].rows[i].myRow.twentythree.name	 = INPUT_NAME_PREFIX + count + '_23';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyfour.name	 = INPUT_NAME_PREFIX + count + '_24';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyfive.name	 = INPUT_NAME_PREFIX + count + '_25';  // input text
				tbl.tBodies[0].rows[i].myRow.twentysix.name		 = INPUT_NAME_PREFIX + count + '_26';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyseven.name	 = INPUT_NAME_PREFIX + count + '_27';  // input text
				tbl.tBodies[0].rows[i].myRow.twentyeight.name	 = INPUT_NAME_PREFIX + count + '_28';  // input text
				tbl.tBodies[0].rows[i].myRow.twentynine.name	 = INPUT_NAME_PREFIX + count + '_29';  // input text
				
				tbl.tBodies[0].rows[i].myRow.thirty.name		 = INPUT_NAME_PREFIX + count + '_30'; // input text
				tbl.tBodies[0].rows[i].myRow.thirtyone.name		 = INPUT_NAME_PREFIX + count + '_31';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtytwo.name 	 = INPUT_NAME_PREFIX + count + '_32';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtythree.name	 = INPUT_NAME_PREFIX + count + '_33';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyfour.name	 = INPUT_NAME_PREFIX + count + '_34';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyfive.name	 = INPUT_NAME_PREFIX + count + '_35';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtysix.name		 = INPUT_NAME_PREFIX + count + '_36';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyseven.name	 = INPUT_NAME_PREFIX + count + '_37';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtyeight.name	 = INPUT_NAME_PREFIX + count + '_38';  // input text
				tbl.tBodies[0].rows[i].myRow.thirtynine.name	 = INPUT_NAME_PREFIX + count + '_39';  // input text
				
				tbl.tBodies[0].rows[i].myRow.forty.name			 = INPUT_NAME_PREFIX + count + '_40'; // input text
				tbl.tBodies[0].rows[i].myRow.fortyone.name		 = INPUT_NAME_PREFIX + count + '_41';  // input text
				tbl.tBodies[0].rows[i].myRow.fortytwo.name 		 = INPUT_NAME_PREFIX + count + '_42';  // input text
				tbl.tBodies[0].rows[i].myRow.fortythree.name	 = INPUT_NAME_PREFIX + count + '_43';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyfour.name		 = INPUT_NAME_PREFIX + count + '_44';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyfive.name		 = INPUT_NAME_PREFIX + count + '_45';  // input text
				tbl.tBodies[0].rows[i].myRow.fortysix.name		 = INPUT_NAME_PREFIX + count + '_46';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyseven.name	 = INPUT_NAME_PREFIX + count + '_47';  // input text
				tbl.tBodies[0].rows[i].myRow.fortyeight.name	 = INPUT_NAME_PREFIX + count + '_48';  // input text
				tbl.tBodies[0].rows[i].myRow.fortynine.name		 = INPUT_NAME_PREFIX + count + '_49';  // input text
				
				tbl.tBodies[0].rows[i].myRow.fifty.name			 = INPUT_NAME_PREFIX + count + '_50';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyone.name		 = INPUT_NAME_PREFIX + count + '_51';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftytwo.name		 = INPUT_NAME_PREFIX + count + '_52';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftythree.name	 = INPUT_NAME_PREFIX + count + '_53';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyfour.name		 = INPUT_NAME_PREFIX + count + '_54';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyfive.name		 = INPUT_NAME_PREFIX + count + '_55';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftysix.name		 = INPUT_NAME_PREFIX + count + '_56';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyseven.name	 = INPUT_NAME_PREFIX + count + '_57';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftyeight.name	 = INPUT_NAME_PREFIX + count + '_58';  // input text
				tbl.tBodies[0].rows[i].myRow.fiftynine.name		 = INPUT_NAME_PREFIX + count + '_59';  // input text
				
				tbl.tBodies[0].rows[i].myRow.sixty.name			 = INPUT_NAME_PREFIX + count + '_60';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyone.name		 = INPUT_NAME_PREFIX + count + '_61';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtytwo.name		 = INPUT_NAME_PREFIX + count + '_62';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtythree.name	 = INPUT_NAME_PREFIX + count + '_63';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyfour.name		 = INPUT_NAME_PREFIX + count + '_64';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyfive.name		 = INPUT_NAME_PREFIX + count + '_65';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtysix.name		 = INPUT_NAME_PREFIX + count + '_66';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyseven.name	 = INPUT_NAME_PREFIX + count + '_67';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtyeight.name	 = INPUT_NAME_PREFIX + count + '_68';  // input text
				tbl.tBodies[0].rows[i].myRow.sixtynine.name		 = INPUT_NAME_PREFIX + count + '_69';  // input text
				
				// ------------------------------------
				tbl.tBodies[0].rows[i].myRow.ra.value = count; // input radio
				count++;
			}
		}
	}
}

</script>



