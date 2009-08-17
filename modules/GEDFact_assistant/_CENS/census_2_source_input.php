<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census and Souce Input Area File File
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
global $pgv_lang, $TEXT_DIRECTION;

?>

<script>
	function findSource(field) {
		pastefield = field;
		findwin = window.open('find.php?type=source', '_blank', 'left=50,top=50,width=600,height=500,resizable=1,scrollbars=1');
		return false;
	}
	function openerpasteid(id) {
		window.opener.paste_id(id);
		window.close();
	}

	function paste_id(value) {
		pastefield.value = value;
	}

	function paste_char(value,lang,mag) {
		pastefield.value += value;
		language_filter = lang;
		magnify = mag;
	}

	function edit_close(newurl) {
		if (newurl)
			window.opener.location=newurl;
		else
			if (window.opener.showchanges)
				window.opener.showchanges();
		window.close();
	}
	
	function changeYear(cenyear) {
		var tbl = document.getElementById('tblSample');
		if (tbl.rows.length==0) {
			create_header();
		}
		changeAge(cenyear);
		changeCols(cenyear);
		// setPOB();
		preview();
	}
	
	function changeAge(cenyear) {
		var curryr = document.getElementById('prevYear');
		if (curryr.value !="") {
			var currcenyear=curryr.value;
		}
		var base1901 ="<?php echo $censyear; ?>";
		if (currcenyear) {
			var agecorr = (currcenyear-cenyear);
		}else{
			var agecorr = (base1901-cenyear);
		}
		var tbl = document.getElementById('tblSample');
		for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
			var tr = tbl.rows[i];
			for(var j=2; j<tr.cells.length; j++){
				if (j!=6 && j!=11) {
					//	miss out all cols except age cols
					continue;
				}else if (tr.cells[j].childNodes[0].value=="") {
					tr.cells[j].childNodes[0].value=null
				}else{
					var ageat1901 = (tr.cells[j].childNodes[0].value);
					var newage=(ageat1901-agecorr);
					// alert (newage);
					tr.cells[j].childNodes[0].value=newage;
				}
			}
		}
		var cens_ctry_a = document.getElementById('censCtry');
		var cens_ctry = cens_ctry_a.value;
		if (cens_ctry =="USA") {
			document.getElementById('Titl').value = "<?php echo "Federal Census Transcription - ".$wholename." - Household";?>";
		}else{
			document.getElementById('Titl').value = "<?php echo "Census Transcription - ".$wholename." - Household";?>";
		}
		var prev = document.getElementById('prevYear');
		prev.value = cenyear;
	}
	
	
	function changeCols(cenyear) {

		// Add or Remove columns ===========================
		var cens_ctry = document.getElementById('censCtry').value;
		var cols_0 = document.getElementsByName('col_0');
		var cols_1 = document.getElementsByName('col_1');
		var cols_2 = document.getElementsByName('col_2');
		var cols_3 = document.getElementsByName('col_3');
		var cols_4 = document.getElementsByName('col_4');
		var cols_5 = document.getElementsByName('col_5');
		var cols_6 = document.getElementsByName('col_6');
		var cols_7 = document.getElementsByName('col_7');
		var cols_8 = document.getElementsByName('col_8');
		var cols_9 = document.getElementsByName('col_9');
		var cols_10 = document.getElementsByName('col_10');
		var cols_11 = document.getElementsByName('col_11');
		var cols_12 = document.getElementsByName('col_12');
		var cols_13 = document.getElementsByName('col_13');
		var cols_14 = document.getElementsByName('col_14');
		var cols_15 = document.getElementsByName('col_15');
		var cols_16 = document.getElementsByName('col_16');
		var cols_17 = document.getElementsByName('col_17');
		var cols_18 = document.getElementsByName('col_18');
		var cols_19 = document.getElementsByName('col_19');
		var cols_20 = document.getElementsByName('col_20');
		var cols_21 = document.getElementsByName('col_21');
		var cols_22 = document.getElementsByName('col_22');
		var cols_23 = document.getElementsByName('col_23');
		var cols_24 = document.getElementsByName('col_24');
		var cols_25 = document.getElementsByName('col_25');
		var cols_26 = document.getElementsByName('col_26');
		var cols_27 = document.getElementsByName('col_27');
		var cols_28 = document.getElementsByName('col_28');
		var cols_29 = document.getElementsByName('col_29');
		var cols_30 = document.getElementsByName('col_30');
		var cols_31 = document.getElementsByName('col_31');
		var cols_32 = document.getElementsByName('col_32');
		var cols_33 = document.getElementsByName('col_33');
		var cols_34 = document.getElementsByName('col_34');
		var cols_35 = document.getElementsByName('col_35');
		var cols_36 = document.getElementsByName('col_36');
		var cols_37 = document.getElementsByName('col_37');
		var cols_38 = document.getElementsByName('col_38');
		var cols_39 = document.getElementsByName('col_39');
		var cols_40 = document.getElementsByName('col_40');
		var cols_41 = document.getElementsByName('col_41');
		var cols_42 = document.getElementsByName('col_42');
		var cols_43 = document.getElementsByName('col_43');
		var cols_44 = document.getElementsByName('col_44');
		var cols_45 = document.getElementsByName('col_45');
		var cols_46 = document.getElementsByName('col_46');
		var cols_47 = document.getElementsByName('col_47');
		var cols_48 = document.getElementsByName('col_48');
		var cols_49 = document.getElementsByName('col_49');
		var cols_50 = document.getElementsByName('col_50');
		var cols_51 = document.getElementsByName('col_51');
		var cols_52 = document.getElementsByName('col_52');
		var cols_53 = document.getElementsByName('col_53');
		var cols_54 = document.getElementsByName('col_54');


		var flip_3 = "none";
		var flip_4 = "none";
		var flip_5 = "none";
		var flip_6 = "none";
		var flip_7 = "none";
		var flip_8 = "none";
		var flip_9 = "none";
		var flip_10 = "none";
		var flip_11 = "none";
		var flip_12 = "none";
		var flip_13 = "none";
		var flip_14 = "none";
		var flip_15 = "none";
		var flip_16 = "none";
		var flip_17 = "none";
		var flip_18 = "none";
		var flip_19 = "none";
		var flip_20 = "none";
		var flip_21 = "none";
		var flip_22 = "none";
		var flip_23 = "none";
		var flip_24 = "none";
		var flip_25 = "none";
		var flip_26 = "none";
		var flip_27 = "none";
		var flip_28 = "none";
		var flip_29 = "none";
		var flip_30 = "none";
		var flip_31 = "none";
		var flip_32 = "none";
		var flip_33 = "none";
		var flip_34 = "none";
		var flip_35 = "none";
		var flip_36 = "none";
		var flip_37 = "none";
		var flip_38 = "none";
		var flip_39 = "none";
		var flip_40 = "none";
		var flip_41 = "none";
		var flip_42 = "none";
		var flip_43 = "none";
		var flip_44 = "none";
		var flip_45 = "none";
		var flip_46 = "none";
		var flip_47 = "none";
		var flip_48 = "none";
		var flip_49 = "none";
		var flip_50 = "none";
		var flip_51 = "none";
		var flip_52 = "none";
		var flip_53 = "none";
		var flip_54 = "none";
		
		if (cens_ctry=="UK") {
			if (cenyear=="1911" || cenyear=="1921") { 
				flip_3 = "";
				flip_4 = "";
				flip_6 = "";
				flip_8 = "";
				flip_15 = "";
				flip_16 = "";
				flip_17 = "";
				flip_18 = "";
				flip_30 = "";
				flip_32 = "";
				flip_33 = "";
				flip_36 = "";
				flip_41 = "";
				flip_53 = "";
			}else 
			if (cenyear=="1901") { 
				flip_3 = "";
				flip_4 = "";
				flip_6 = "";
				flip_8 = "";
				flip_30 = "";
				flip_33 = "";
				flip_36 = "";
				flip_41 = "";
				flip_53 = "";
			}else 
			if (cenyear=="1891") { 
				flip_3 = "";
				flip_4 = "";
				flip_6 = "";
				flip_8 = "";
				flip_30 = "";
				flip_34 = "";
				flip_35 = "";
				flip_37 = "";
				flip_41 = "";
				flip_53 = "";
			}else 
			if (cenyear=="1881" || cenyear=="1871" || cenyear=="1861" || cenyear=="1851") { 
				flip_3 = "";
				flip_4 = "";
				flip_6 = "";
				flip_8 = "";
				flip_30 = "";
				flip_41 = "";
				flip_53 = "";
			}else 
			if (cenyear=="1841") { 
				flip_6 = "";
				flip_8 = "";
				flip_30 = "";
				flip_42 = "";
				flip_43 = "";
			}
			
		} else if (cens_ctry=="USA") {
			if (cenyear=="1930") { 
				flip_3 = "";
				flip_5 = "";
				flip_8 = "";
				flip_9 = "";
				flip_11 = "";
				flip_14 = "";
				flip_19 = "";
				flip_38 = "";
				flip_41 = "";
				flip_44 = "";
				flip_45 = "";
				flip_46= "";
				flip_47 = "";
				flip_48 = "";
				flip_49 = "";
				flip_50 = "";
				flip_51 = "";
				flip_52 = "";
				flip_54 = "";
			}else
			if (cenyear=="1920") { 
				flip_3 = "";
				flip_5 = "";
				flip_8 = "";
				flip_9 = "";
				flip_11 = "";
				flip_14 = "";
				flip_26 = "";
				flip_27 = "";
				flip_28 = "";
				flip_38 = "";
				flip_41 = "";
				flip_44 = "";
				flip_45 = "";
				flip_46= "";
				flip_49 = "";
				flip_50 = "";
				flip_51 = "";
				flip_52 = "";
			}
		}

		// Hide or show ===============
		for (var i=0; i<cols_0.length; i++) {
			cols_3[i].style.display = flip_3;
			cols_4[i].style.display = flip_4;
			cols_5[i].style.display = flip_5;
			cols_6[i].style.display = flip_6;
			cols_7[i].style.display = flip_7;
			cols_8[i].style.display = flip_8;
			cols_9[i].style.display = flip_9;
			cols_10[i].style.display = flip_10;
			cols_11[i].style.display = flip_11;
			cols_12[i].style.display = flip_12;
			cols_13[i].style.display = flip_13;
			cols_14[i].style.display = flip_14;
			cols_15[i].style.display = flip_15
			cols_16[i].style.display = flip_16;
			cols_17[i].style.display = flip_17;
			cols_18[i].style.display = flip_18;
			cols_19[i].style.display = flip_19;
			cols_20[i].style.display = flip_20;
			cols_21[i].style.display = flip_21;
			cols_22[i].style.display = flip_22;
			cols_23[i].style.display = flip_23;
			cols_24[i].style.display = flip_24;
			cols_25[i].style.display = flip_25
			cols_26[i].style.display = flip_26;
			cols_27[i].style.display = flip_27;
			cols_28[i].style.display = flip_28;
			cols_29[i].style.display = flip_29;
			cols_30[i].style.display = flip_30;
			cols_31[i].style.display = flip_31;
			cols_32[i].style.display = flip_32;
			cols_33[i].style.display = flip_33;
			cols_34[i].style.display = flip_34;
			cols_35[i].style.display = flip_35
			cols_36[i].style.display = flip_36;
			cols_37[i].style.display = flip_37;
			cols_38[i].style.display = flip_38;
			cols_39[i].style.display = flip_39;
			cols_40[i].style.display = flip_40;
			cols_41[i].style.display = flip_41;
			cols_42[i].style.display = flip_42;
			cols_43[i].style.display = flip_43;
			cols_44[i].style.display = flip_44;
			cols_45[i].style.display = flip_45
			cols_46[i].style.display = flip_46;
			cols_47[i].style.display = flip_47;
			cols_48[i].style.display = flip_48;
			cols_49[i].style.display = flip_49;
			cols_50[i].style.display = flip_50;
			cols_51[i].style.display = flip_51;
			cols_52[i].style.display = flip_52;
			cols_53[i].style.display = flip_53;
			cols_54[i].style.display = flip_54;
		}

	}
	
/*
	// TEST FUNCTION =============================
	function checkPOB() {
		var cens_ctry = document.getElementById('censCtry').value;
		var tbl = document.getElementById('tblSample');
		for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
			var tr = tbl.rows[i];
			for(var j=2; j<tr.cells.length; j++){
				if (j!=10 && j!=11 && j!=12) {
					//	miss out all cols except the 3 birthplaces
					continue;
				//}else if (tr.cells[j].childNodes[0].value=="") {
				//	tr.cells[j].childNodes[0].value=null
				}else{
					if (cens_ctry=="USA") {
						tr.cells[10].childNodes[0].value="ENG";
						tr.cells[11].childNodes[0].value="IN";
						tr.cells[12].childNodes[0].value="OH";
					}else{
						tr.cells[10].childNodes[0].value=birthpl;
						tr.cells[11].childNodes[0].value=birthpl;
						tr.cells[12].childNodes[0].value=birthpl;
					}
				}
			}
		}
	}
	// ============================================
*/
	
</script>

<div class="optionbox" style="font-weight:bold; font-size:0.9em; text-align:left; padding:0.3em; border:0.3em outset; margin-bottom:0.3em;">
		Census:&nbsp;&nbsp;
			<script type="text/javascript">
				var censyear = new DynamicOptionList();
				censyear.addDependentFields("censCtry","censYear");
				censyear.forValue("UK").addOptions( "choose", "1841", "1851", "1861", "1871", "1881", "1891", "1901", "1911", "1921" );
				censyear.forValue("USA").addOptions( "choose", "1790", "1800", "1810", "1820", "1830", "1840", "1850", "1860", "1870", "1880", "1890", "1900", "1910", "1920", "1930");
				censyear.forValue("UK").setDefaultOptions("choose");
				censyear.forValue("USA").setDefaultOptions("choose");
			</script>
			<select id="censCtry" name="censCtry" style="font:0.9em normal;">
				<option value="UK">UK</option>
				<option value="USA" >USA</option>
			</select>
			<select onchange =	"if( this.options[this.selectedIndex].value!='') {
									changeYear(this.options[this.selectedIndex].value);
								}" 
				id="censYear" name="censYear" style="font:0.9em normal;">
			</select>
			<input type="hidden" id="prevYear" name="prevYear" value="" />&nbsp;&nbsp;&nbsp;
		Title:&nbsp;
			<script type="text/javascript">
				document.writeln('<input id="Titl" name="Titl" type="text" style="width:33em; font:0.9em normal;" value="<?php echo "Census Transcription - ".$wholename." - Household";?>" />');
			</script>
			<br />
		Enumeration:&nbsp;
			<input id="citation" name="citation" type="text" style="width:43.1em; font:0.9em normal;" value="<?php echo "Enter Enumeration number";?>" />
			<br />
		Locality:&nbsp;
			<input id="locality" name="locality" type="text" style="width:46em; font:0.9em normal;" value="<?php echo "Enter Locality";?>" />
</div>
			
